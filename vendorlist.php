<?php
require_once('class/config.inc.php');
require_once('class/class.contacts.php');
require_once('class/class.InventoryVendor.php');
$vender = new InventoryVendor();
echo $vender->GetVendorJson($_REQUEST[tag]);
?>