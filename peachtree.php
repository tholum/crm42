<?php
$error = $_REQUEST["error"];
if( $error == "yes"){
    ini_set("display_errors" , "1");
    error_reporting(E_ALL);
}
ob_start();
require_once('app_code/config.inc.php');
require_once('class/class.peachtree.php');
$peachtree = new peachtree();
$data = $_REQUEST["data"];
switch( $data ){
    case "prepay":
        $tr = $peachtree->getNewTransactions();
        $peach = $peachtree->transArr2PeachArr($tr , $data );
        $tr2 = $peachtree->getRefundTransactions();
        $peach2 = $peachtree->transRefundArr2PeachArr($tr2, $data);
        $peach = array_merge($peach, $peach2);
    break;
    case "final":
        $tr = $peachtree->getNewTransactions();
        $peach = $peachtree->transArr2PeachArr($tr , $data );
    break;
    case "invoice":
        $tr = $peachtree->getNewTransactions();
        $peach = $peachtree->transArr2SalesArr($tr , $data );
    break;
}
ob_end_clean();
$type = $_REQUEST["type"];
if( $type == ''){
    $type='csv';
}
$run = "yes";
//$run = $_REQUEST["run"];
if( $run == "yes"){
    echo $peachtree->array2csv($peach , $type );
}
//echo str_replace("\n", "<br/>", str_replace( array( "\t" , " " ), "&nbsp;",  print_r($tr,true)));
//echo "<br/>";

?>
