<?php
require_once __DIR__ . '/Jbp_dealer.php';
class Jbp_importer
{
	protected $fileName;
	protected $tmpDir;
	protected $type;

	function __construct()
    {
        $this->tmpDir = wp_upload_dir()['basedir'] . '/jbp_tmp/';
        $this->fileName = $this->tmpDir . 'temp.csv';

        if (!file_exists($this->tmpDir)) {
            mkdir($this->tmpDir);
        }
    }

    public function import()
    {
        $this->type = $_POST['type'];

        $this->fileUpload();
        $this->processCsv();

        header('Location: admin.php?page=' . strtolower(get_class()) . '&status=success');
    }

	public function export()
    {
        $module = isset($_GET['module']) ? $_GET['module'] : null;
        $type = isset($_GET['type']) ? $_GET['type'] : null;
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if (!$type || !$module) {
            return false;
        }

        $this->generate($module, $type, $id);
    }

    public function generate($module, $type, $id = null)
    {
        $dealer = new Jbp_dealer;

        $dealer_columns = $dealer->getColumns();

        $columns = array_merge($dealer_columns);
        
        $filename = strtolower($module) . '_' . strtolower($type);

        if ($id) {
            $filename .= '_' . $id;
        }

        $filename .= ".csv";

        // if (!file_exists($this->tmpDir . $filename)) {
        //     touch($this->tmpDir . $filename);
        // }

        $csv = implode(',', $columns) . "\n";

        if ($type == 'all') {

            $data = $this->prepareDataForExport();

            foreach ($data as $row) {

	            $array = array();

	            array_walk($row, function($val, $i) use(&$array) {
		            $array[$i] = str_replace('"', '""', $val);
	            });

	            $csv .= "\"" . implode('","', array_values($array)) . "\"" . "\n";
            }
        }

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Pragma: no-cache");
        header("Expires: 0");

        echo $csv;

        exit();
    }

    /**
     * Uploads CSV
     */
    public function fileUpload()
    {
        if (empty($_FILES) || !isset($_FILES['file'])) {
            return;
        }

        if (file_exists($this->fileName)) {
            unlink($this->fileName);
        }

        move_uploaded_file($_FILES['file']["tmp_name"], $this->fileName);
    }

    /**
     * Process CSV
     */
    public function processCsv()
    {
        $dealer = new Jbp_dealer();
        $dealer->{ 'import' . ucfirst( $this->type ) }($this->fileName);

        return;
    }

    /**
     * Removing the temp CSV file.
     */
    public function removeTempFile() 
    {
        return unlink($this->fileName);
    }

    public function prepareDataForExport()
    {
    	$_dealer = new Jbp_dealer();

    	$_dealers = $_dealer->getDataForExport();

    	$rows = $_dealers;

    	return $rows;
    }
}