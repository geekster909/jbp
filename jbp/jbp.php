<?php

require_once __DIR__ . '/models/Jbp_dashboard.php';
require_once __DIR__ . '/models/Jbp_dealer.php';
require_once __DIR__ . '/models/Jbp_importer.php';

class Jbp {

    protected $action;
    protected $recordId;
    protected $page;

    /**
     * Version number
     * @var string
     */
    protected $version;

    /**
     * Menu and Page name in WP
     * @var string
     */
    protected $menuName;

    /**
     * Client's name
     * @var string
     */
    protected $client;

    /**
     * General Settings
     * @var array
     */
    protected $settings;

    protected $model;
    protected $editor;

    function __construct()
    {
        if (current_user_can( 'manage_options' )){
            $this->version = '0.1';
            $this->menuName = 'jbp';
            $this->client = 'JBP';

            $this->settings = [];

            $this->settings['icon'] = JBP_APP_URL . 'assets/images/icon_default.png';
            $this->settings['error_page'] = JBP_VIEWS_PATH . '404.php';

            if (isset($_REQUEST['page']) && strpos($_REQUEST['page'], 'jbp') !== false) {

                $this->page = $_REQUEST['page'];

                $this->setQueryParameters();

                $model = ucfirst($this->page);

                $this->model = new $model;
            }

            if ($this->action) {
                if (method_exists($this->model, $this->action)) {
                    $result = call_user_func([$this->model, $this->action]);
                } else {
                    /**
                     * TO-DO: Eliminate this check. Need a fallback
                     */
                    $this->runAction();
                }
            }

            $this->initialize();

            return $this;
        } 
    }

    public function setQueryParameters()
    {
        $this->action = isset($_REQUEST['action']) ? str_replace('jbp_', '', $_REQUEST['action']) : null;
        $this->recordId = isset($_REQUEST['recordId']) ? $_REQUEST['recordId'] : null;

        return;
    }

    /**
     * TO-DO: Eliminate this method.
     */
    public function runAction()
    {
        switch ($this->action) {
            case 'edit':
                call_user_func(array($this, 'initializeEditor'));
                break;

            default:
                break;
        }
    }

    /**
     * Initialize PostType
     */
    public function initialize()
    {
        add_action( 'admin_init', array($this, 'enqueueStyles') );
        add_action( 'admin_enqueue_scripts', array($this, 'enqueueScripts') );
        add_action( 'admin_menu', array($this, 'registerManagerMenu') );
        // add_action( 'wp_ajax_exec', array($this, 'runAjax'));
    }

    /**
     * Enqueue Admin Panel Styles
     */
    public function enqueueStyles()
    {
        wp_enqueue_style( 'jbp_admin_style', JBP_APP_URL . 'assets/css/styles.css', array(), $this->version, false );
    }


    /**
     * Enqueue Admin Panel Scripts
     */
    public function enqueueScripts($hook_suffix)
    {
        if ( strrpos($hook_suffix, strtolower($this->menuName)) < 1 ) {
            return;
        }

        wp_register_script( 'jbp_admin_script', JBP_APP_URL . 'assets/js/dmcm.js', array('jquery'), $this->version, true );

        wp_enqueue_script( 'jquery_ui_script', '//code.jquery.com/ui/1.10.3/jquery-ui.js', array('jquery'), $this->version, false );
        wp_enqueue_script( 'jbp_admin_script');
    }

    /**
     * Registers the menu page.
     */
    public function registerManagerMenu()
    {
        add_menu_page( $this->client . ' :: Jbp Manager', $this->client, 'manage_options', 'jbp_dashboard', array($this, 'loadView'), $this->getSetting('icon'), 3);
        add_submenu_page( 'jbp_dashboard', $this->client . ' :: Jbp Manager', 'Dashboard', 'manage_options', 'jbp_dashboard', array($this, 'loadView'));
        add_submenu_page( 'jbp_dashboard', 'Manage Dealers', 'Manage Dealers', 'manage_options', 'jbp_dealer', array($this, 'loadView'));
        add_submenu_page( 'jbp_dashboard', 'Import/Export', 'Import/Export', 'manage_options', 'jbp_importer', array($this, 'loadView'));
    }

    /**
     * Returns a setting set on the applications settings variable.
     * @param $setting
     * @return null (if setting doesn't exist)
     */
    public function getSetting($setting) {
        return isset($this->settings[$setting]) ? $this->settings[$setting] : null;
    }

    /**
     * Loads View
     */
    public function loadView()
    {
        $filename =  JBP_VIEWS_PATH;

        if ($this->action == 'edit') {
            $filename .= $this->page . '/editor/index.php';
        } elseif ($this->action == 'add') {
            $filename .= $this->page . '/add/index.php';
        } else {
            $filename .= $this->page . '/index.php';
        }

        if (!file_exists($filename)) {
            $filename = $this->getSetting('error_page');
        }

        if (!$this->action || $this->action !== 'process') {
            include_once JBP_APP_PATH . 'assets/images/svg-defs.php';
        }

        require_once $filename;
    }

    /**
     * Returns Model Instance
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Returns Editor Instance
     */
    public function getEditor()
    {
        return $this->editor;
    }

    public function initializeEditor()
    {
        $class_name = 'Jbp_editor';

        if (file_exists(__DIR__ . '/models/' . ucfirst($this->page) . '/Editor.php')) {
            require_once __DIR__ . '/models/' . ucfirst($this->page) . '/Editor.php';
            $class_name = ucfirst($this->page) . '_editor';
        } else {
            require_once __DIR__ . '/models/Jbp_editor.php';
        }

        $this->editor = new $class_name;

        $this->editor->setRecordId($this->recordId);
        // $this->editor->setTitle();
        $this->editor->getDealer($this->recordId);

    }
}