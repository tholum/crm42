<?php
require_once 'class/global.config.php';
require_once 'class/class.zimbra.php';
require_once 'class/database.inc.php';
$zim = new zimbra();
echo $zim->zimbra_auth("41" , '',  "http://www.google.com");
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
