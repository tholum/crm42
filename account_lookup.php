<?php
$query=$_REQUEST["term"];
require_once("class/class.eapi_api.php");
$eapi_api = new eapi_api();
$results = $eapi_api->query_account($query);
$object_res = json_decode($results);
foreach( $object_res->SearchResult as $n => $v ){
   $object_res->SearchResult[$n]->value = $v->Studio ;
   $object_res->SearchResult[$n]->label = $v->Studio ;
}
echo json_encode($object_res->SearchResult);
?>
