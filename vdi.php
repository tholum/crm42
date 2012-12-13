<?php
require_once( 'class/class.vdi.php');



$vdi = new vdi();
$vdi->start_session('Jason.Ehlers');
$vdi->end_session();
//$v = $vdi->run_query($xml);
//file_put_contents('/tmp/xml', $v);
//$v = file_get_contents('/tmp/xml');
//$vdi->set_session_data($v);
//echo $vdi->session_id . "\n";
//echo $vdi->user_id . "\n";
//echo $vdi->set_gmt_offset() . "\n";

/*$xmlObj = simplexml_load_string($v);
$xmlArr = $vdi->objectsIntoArray( $xmlObj);
var_dump($xmlArr);
$atr = "@attributes";*/

?>
