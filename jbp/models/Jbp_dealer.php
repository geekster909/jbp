<?php

	class Jbp_dealer
	{
		function __construct()
    	{
    		$this->create_dealer_db();
    	}
		/**
	     * Create Dealer DB
	     */
	    public function create_dealer_db()
	    {

	        global $wpdb;
	            
	        $querystr = "
	            CREATE TABLE IF NOT EXISTS _jbp_dealers 
	            (
	                id INT NOT NULL AUTO_INCREMENT, 
	                PRIMARY KEY(id), 
	                name VARCHAR(255), 
	                address_1 VARCHAR(255), 
	                address_2 VARCHAR(255), 
	                city VARCHAR(255), 
	                state VARCHAR(255), 
	                zip VARCHAR(255), 
	                country VARCHAR(255), 
	                website VARCHAR(255), 
	                phone VARCHAR(255), 
	                latitude VARCHAR(255), 
	                longitude VARCHAR(255)
	            )
	         ";

	        $results = $wpdb->get_results($querystr, OBJECT);
	    }
		public function getDealers(){

			global $wpdb;
			
			$querystr = "
			    SELECT * 
			    FROM _jbp_dealers
			 ";

			$results = $wpdb->get_results($querystr, OBJECT);
			
			return $results;
		}

		public function updateDealer(){

			$data = $_POST;

			global $wpdb;
			
			$querystr = "
			    UPDATE _jbp_dealers
			    SET
			    	name = 		'".$data['name']."',
			    	address_1 = '".$data['address_1']."',
			    	address_2 = '".$data['address_2']."',
			    	city = 		'".$data['city']."',
			    	state = 	'".$data['state']."',
			    	zip = 		'".$data['zip']."',
			    	country = 	'".$data['country']."',
			    	website = 	'".$data['website']."',
			    	phone = 	'".$data['phone']."',
			    	latitude = 	'".$data['latitude']."',
			    	longitude = '".$data['longitude']."'
			    WHERE id = ".$data['dealer_id']."
			 ";

			$results = $wpdb->get_results($querystr, OBJECT);
			
			header('Location: admin.php?page=jbp_dealer&performed=Update&status=1');

	    	exit;
		}

		public function addDealer()
		{
			$data = $_POST;

			if ($data['name']) {
				global $wpdb;
				
				$querystr = "
				    INSERT INTO _jbp_dealers
				    (
						name,
						address_1,
				    	address_2,
				    	city,
				    	state,
				    	zip,
				    	country,
				    	website,
				    	phone,
				    	latitude,
				    	longitude
				    )
				    VALUES (
				    	'".$data['name']."',
				    	'".$data['address_1']."',
				    	'".$data['address_2']."',
				    	'".$data['city']."',
				    	'".$data['state']."',
				    	'".$data['zip']."',
				    	'".$data['country']."',
				    	'".$data['website']."',
				    	'".$data['phone']."',
				    	'".$data['latitude']."',
				    	'".$data['longitude']."'
				    )
				 ";

				$results = $wpdb->get_results($querystr, OBJECT);
				
				header('Location: admin.php?page=jbp_dealer&performed=Add&status=1');

		    	exit;

		    } else {
		    	header('Location: admin.php?page=jbp_dealer&performed=Add&status=2');

		    	exit;
		    }
		}

		public function deleteDealer()
		{
			$data = $_POST;

			global $wpdb;

			$querystr = "
				DELETE FROM _jbp_dealers
				WHERE id = ".$data['dealer_id']."
			";

			$results = $wpdb->get_results($querystr, OBJECT);
			
			header('Location: admin.php?page=jbp_dealer&performed=Delete&status=1');

	    	exit;

		}

		public function getColumns()
		{
			global $wpdb;

			$existing_columns = $wpdb->get_col("DESC _jbp_dealers", 0);

			return $existing_columns;
		}

		public function importDealers($filename)
		{
			
		}

		public function getDataForExport()
		{
			global $wpdb;

			return $wpdb->get_results( "SELECT * FROM _jbp_dealers" );
		}
	}