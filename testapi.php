<?php
require_once( 'class/class.eapi_api.php');
$eapi = new eapi_api();
$string=$_REQUEST["string"];
$apicall = $_REQUEST["api"];
$json = $eapi->$apicall($string);
$array = json_decode($json,TRUE);
var_dump( $array );
?>
