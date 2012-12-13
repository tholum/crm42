<?php

include ('gauge.class.php');

$value  = $_GET['value'];
$size   = $_GET['size'];
$text   = $_GET['text'];
$red  = $_GET['red'];
$yellow = $_GET['yellow'];
$min    = $_GET['min'];
$max    = $_GET['max'];
$span   = $_GET['span'];

$used = $_GET["used"];
$ava = $_GET["available"];

if( $used != '' && $ava != '' ){
    $value = ( $used / $ava ) * 100;
}

if( $value > 100 ){ $value = 100; }


$gauge = new gauge();
$gauge->setPos($value);
$gauge->setImagesize($size);
$gauge->setLegend($text);
//$gauge->setGreen($green);
//$gauge->setYellow($yellow);
//$gauge->setMax($max);
//$gauge->setMin($min);
//$gauge->setSpan($span);
$gauge->plot();

?>
