<?php

	class Jbp_settings
	{
		function __construct()
    	{
    		$this->create_jpb_settings_db();
    	}

    	/**
	     * Create JBP Settings DB
	     */
	    private function create_jpb_settings_db()
	    {

	        global $wpdb;
	            
	        $querystr = "
	            CREATE TABLE IF NOT EXISTS _jbp_settings 
	            (
	                id INT NOT NULL AUTO_INCREMENT, 
	                PRIMARY KEY(id), 
	                map_geo_api VARCHAR(255)
	            )
	         ";

	        $results = $wpdb->get_results($querystr, OBJECT);

	        if (count($this->getJbpSettings()) < 1) {
		        $querystr = "
		            INSERT INTO _jbp_settings 
					(
						map_geo_api
					)
					VALUES (
					    ''
					)
		         ";

		        $results = $wpdb->get_results($querystr, OBJECT);
		    }
	    }

	    public function getJbpSettings()
	    {

			global $wpdb;
			
			$querystr = "
			    SELECT * 
			    FROM _jbp_settings
			 ";

			$results = $wpdb->get_results($querystr, OBJECT);
			
			return $results;
		}

	    public function saveJbpSettings()
		{
			$data = $_POST;

			global $wpdb;
			
			$querystr = "
			    UPDATE _jbp_settings
		    	SET
		    		map_geo_api = '".$data['geo-map-api']."'
		    	WHERE id = 1
			 ";

			$results = $wpdb->get_results($querystr, OBJECT);
			
			header('Location: admin.php?page=jbp_settings&status=success');

	    	exit;
		}
	}