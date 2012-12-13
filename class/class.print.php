<?php
require_once('class/printipp/CupsPrintIPP.php');
class cups_print {
	var $data = '';
	var $printer_url = "/printers/label1";
	var $ipp_server="localhost";
	var $ssl=false;
	var $port=false;
	var $user="guest";
	var $password="";
	var $banners=array();
	var $job_billing = "php PrintIPP";
	var $ipp;
	var $attributes = array(
                        "job_originating_host_name",
                        "job_originating_user_name",
                        "job_id",
                        "job_printer_uri",
                        "job_name",
                        "job_state",
                        "job_state_reasons",
                        "time_at_creation",
                        "time_at_completed",
                        "job_media_sheets_completed",
                        "job_billing",
                        "document_name",
                        "document_format",
                        );

	function __construct( $data='' , $options = array()){
		$this->ipp = new CupsPrintIPP();
		foreach($options as $n => $v ){
			$this->$n = $v;
		}
		echo __LINE__ ."\n";
		$this->set_data($data);
		echo __LINE__ . "\n";
		$this->ipp->setHost($this->ipp_server);
		echo __LINE__ . "\n";
		$this->ipp->setPrinterURI($this->printer_url);
		echo __LINE__ . "\n";
		$this->ipp->setUserName($this->user);
		echo __LINE__ . "\n";
		$this->ipp->setAttribute('job-sheets',$this->banners);
		echo __LINE__ . "\n";
        $this->ipp->setAttribute('fit-to-page', 1);
		$this->ipp->setAttribute('job-billing',$this->job_billing);			echo __LINE__ . "\n";	
		$this->ipp->setDocumentName("test file printed from web page");
	
	}
	function set_data($data){
		$this->data = $data;
		echo __LINE__ . "\n";
		$this->ipp->setData( $this->data );
	}
	function print_doc(){
		echo __LINE__ . "\n";
		$this->ipp->printJob();
	}
	function get_status(){
		echo __LINE__ . "\n";
		$result = $this->ipp->printJob();
		echo __LINE__ . "\n";
  		$job = $this->ipp->last_job;
		echo __LINE__ . "\n";
		$ja = $this->ipp->getJobAttributes($this->attributes);
		var_dump( $result );
		var_dump( $job );
		var_dump( $ja );
	}	
		
}

?>
