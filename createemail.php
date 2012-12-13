<?php
ini_set("display_errors" , 1);
require_once('app_code/global.config.php');
require_once('class/config.inc.php');
require_once('class/class.email_client.php');
require_once('class/class.flags.php');
require_once('class/class.GlobalTask.php');
require_once('class/class.smtp.php');
$smtp = new smtp();
$smtp->send_email("TBL_GROUP", "1", 'tholum@couleetechlink.com,pythonholum@gmail.com', 'phpdemo@couleetechlink.com', 'Another Email Demo', '<b>Hello</b>', array('/tmp/file.csv' , '/tmp/file.txt') );
echo "OK";
?>
