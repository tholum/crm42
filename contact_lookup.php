<?php
//ini_set('display_errors',1);
$query=$_REQUEST["term"];
ob_start();
require_once('app_code/global.config.php');

require_once('class/config.inc.php');
//echo __LINE__ . "\n";
require_once('class/class.email_client.php');
//echo __LINE__ . "\n";
require_once('class/class.flags.php');
//echo __LINE__ . "\n";
require_once('class/class.GlobalTask.php');

//echo __LINE__ . "\n";
require_once('class/class.smtp.php');
//echo __LINE__ . "\n";
require_once('class/class.display.php');
//echo __LINE__ . "\n";
require_once('class/class.casecreation.php');
//echo __LINE__ . "\n";
require_once('class/class.dynamicpage.php');
require_once('class/class.contacts.php');
//echo __LINE__ . "\n";
require_once('class/class.FctSearchScreen.php');
require_once('class/class.cases.php');
require_once('class/class.note.php');
ob_end_clean();
$contacts = new Contacts();
echo $contacts->json_search_contact($query);
?>
