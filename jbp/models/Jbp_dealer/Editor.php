<?php

class Jbp_dealer_editor
{
	public $dealer;
	public $recordId;
	public $title;

	function __construct()
    {
        
    }

    public function getDealer($recordId){
		global $wpdb;
		
		$querystr = "
		    SELECT * 
		    FROM _jbp_dealers
		    WHERE id=".$recordId."
		 ";
		$dealer = $wpdb->get_results($querystr, OBJECT);
		$dealer = $dealer[0];

		$this->setTitle($dealer->name);
		return $this->dealer = $dealer;
	}

	/**
     * Sets Editor Page Title.
     */
    public function setTitle($dealer)
    {
	    return $this->title = $dealer;
    }

    /**
     * Sets record ID for editor
     */
    public function setRecordId($recordId)
    {
	    return $this->recordId = $recordId;
    }
}