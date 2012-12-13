<?php
require_once('class/config.inc.php');
require_once('class/class.contacts.php');
require_once('class/class.casecreation.php');

$casecreation = new case_creation();

switch($_REQUEST['type']){
case 'user' : echo $casecreation->GetVendorJson($_REQUEST[tag]);
break;
}
?>
