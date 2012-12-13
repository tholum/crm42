<?php
//ini_set('display_errors',1);
require_once('class/config.inc.php');
require 'class/class.tasks.php';
require_once('class/class.PrintDetails.php');
$add = new PrintDetails();
 echo $add->create_group();
?>
