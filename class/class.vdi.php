<?php
class vdi {
    var $url = "https://recordings.lab.eapi.com/ws/vpiddservice.svc/vpiddscommand";
    public $session_id='';
    public $user_id='';
    public $session_start_time='';
    public $gmt_offset;
    public $resource_id;
    public $login_name;
    public $vpi_order_start = '00000000-0000-0000-0000-000000000101';
    public $vpi_order_commit = '00000000-0000-0000-0000-000000000102';
    function set_gmt_offset(){
            $str = strtotime("NOW");
            $this->resource_id = "45D831B4-9E6E-440E-95B7-09B979606ADF";
            $this->gmt_offset = ( date("H" , $str) - gmdate('H', $str) ) * 60;
            
            return $this->gmt_offset;
    }
    function run_query( $xml ){
        ob_start();
            $curl = curl_init();
            curl_setopt( $curl , CURLOPT_URL , $this->url );
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt( $curl , CURLOPT_HTTPHEADER , array("Accept: application/json",'Content-Type: text/xml') );
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "$xml");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $html = curl_exec( $curl );
            curl_close($curl);
        $html .= ob_get_contents();
        ob_end_clean();
        return $html;
    }
    function set_session_data($session_xml){
        $xmlObj = simplexml_load_string($session_xml);
        $xmlArr = $this->objectsIntoArray( $xmlObj);
        var_dump( $xmlArr);
        $this->session_id = $xmlArr['Results']['Session']['@attributes']['SessionID'];
        $this->user_id = $xmlArr['Results']['Session']['@attributes']['UserID'];
        //echo "\nSID: $this->session_id\nUID\n$this->user_id\n";
    }
    function objectsIntoArray($arrObjData, $arrSkipIndices = array())
    {
        $arrData = array();

        // if input is object, convert into array
        if (is_object($arrObjData)) {
            $arrObjData = get_object_vars($arrObjData);
        }

        if (is_array($arrObjData)) {
            foreach ($arrObjData as $index => $value) {
                if (is_object($value) || is_array($value)) {
                    $value = $this->objectsIntoArray($value, $arrSkipIndices); // recursive call
                }
                if (in_array($index, $arrSkipIndices)) {
                    continue;
                }
                $arrData[$index] = $value;
            }
        }
        return $arrData;
    }
    function end_session(){
        $end_time = strtotime("NOW");
        $duration = $end_time - $this->session_start_time;
        $xml = "<VPIDDSCommand xmlns:i=\"http://www.w3.org/2001/XMLSchema-instance\"><Plugin>FactFinder</Plugin>";
        $xml .= "<Command>EndSession</Command><Token></Token>";
        $xml .= "<Parameters><FactFinder><SessionID>$this->session_id</SessionID>";
        $xml .= "<Duration>$duration</Duration>";
        $xml .= "<EndTime>" . date( "Y-m-d H:i:s" , $end_time ) .  "</EndTime></FactFinder></Parameters></VPIDDSCommand>";
        $end_session_xml = $this->run_query($xml);
        $dom = simplexml_load_string($end_session_xml);
        var_dump( $dom);
    }
    function start_session($login=""){
        if( $login != ''){
            $this->login_name = $login;
        }
        $this->session_start_time = strtotime("NOW");
        $this->set_gmt_offset();
        $xml = "<VPIDDSCommand xmlns:i=\"http://www.w3.org/2001/XMLSchema-instance\"><Plugin>FactFinder</Plugin>";
        $xml .= "<Command>StartSession</Command><Token></Token>";
        $xml .="<Parameters><FactFinder>";
        $xml .= "<StartTime>" . date( "Y-m-d H:i:s" , $this->session_start_time ) . "</StartTime>";
        $xml .= "<GmtOffset>$this->gmt_offset</GmtOffset>";
        $xml .= "<ResourceID>$this->resource_id</ResourceID>";
        $xml .= "<Login>$this->login_name</Login>";
        $xml .= "<IPAddress></IPAddress><SessionName>Tag Account Session</SessionName></FactFinder></Parameters></VPIDDSCommand>";
        $session_xml = $this->run_query($xml);
        echo $session_xml . "\n\n$xml";
        $this->set_session_data($session_xml);
    }
    function apply_tag( $account_id , $order_id='' , $event_time="NOW"){
        $this->tag_event($this->vpi_order_start, $account_id, $order_id, $event_time);
        $this->tag_event($this->vpi_order_commit, $account_id, $order_id, $event_time);
    }
    function tag_event( $event_type_id , $account_id , $order_id='', $event_time="NOW" ){
        
        $xml = "<VPIDDSCommand xmlns:i=\"http://www.w3.org/2001/XMLSchema-instance\"><Plugin>FactFinder</Plugin>";
        $xml .= "<Command>AddEvent</Command><Token ></Token>";
        $xml .= "<Parameters><FactFinder>";
        $xml .= "<SessionID>$this->session_id</SessionID>";
        $xml .= "<UserID>$this->user_id</UserID>";
        $xml .= "<EventTypeID>$event_type_id</EventTypeID>";
        $xml .= "<EventTime>" . date( "Y-m-d H:i:s" , strtotime($event_time) ) . "</EventTime>";
        $xml .= "<GmtOffset>$this->gmt_offset</GmtOffset>";
        $xml .= "<CustomerID>$account_id</CustomerID>";
        if ($order_id = ''){
            $xml .= "<TransactionID>$order_id</TransactionID>";
        }
        $xml .= "<EventFailed>0</EventFailed></FactFinder></Parameters></VPIDDSCommand>";
        $tag_xml = $this->run_query($xml);
    }
}
?>
