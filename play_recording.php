<?php
ob_start();
$id = $_GET["id"];
require('app_code/config.inc.php');
require_once 'class/global.config.php';
require_once 'class/class.asterisk.php';
ob_end_clean();
$a = new Asterisk;
$message = $a->get_recording($id);
header("Content-Type: audio/mp3", true);
//header("Content-Disposition: attachment; filename=$id.wav");
echo $message["file"];

?>