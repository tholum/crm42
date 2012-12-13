<?php
$oa = $this->db->fetch_assoc($this->db->query("SELECT OrderNumber FROM cases WHERE case_id='$module_id'"));
if( $oa['OrderNumber'] != '' ){
    $eapi_api = new eapi_api();
    $json = $eapi_api->order_detail_lookup($oa['OrderNumber']);
    $order_details = json_decode($json);
    $defaults["priority_date"] = date( "Y-m-d" , strtotime($order_details->Statuses[0]->StatusTime) );
    $defaults['optional1'] = $order_details->Location;
//    file_put_contents('/tmp/debug.txt', $tmp);
}

?>
