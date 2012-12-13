<?php
//ini_set("display_errors" , "1");
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
//echo __LINE__ . "\n";
require_once('class/class.FctSearchScreen.php');

//echo __LINE__ . "\n";
require_once('class/class.eapi_order.php');
require_once('class/class.eapi_account.php');
require_once('class/class.cases.php');
require_once('class/class.note.php');
//ini_set("display_errors" , "1");
require_once("class/global.config.php");
require_once('class/database.inc.php');
require_once("class/class.Authentication.php");
require_once('class/file.upload.inc.php');
require_once('class/class.phpmailer.php');
require_once("class/class.user.php");	
require_once("class/ClsJSFormValidation.cls.php");
require_once("class/class.FormValidation.php");
require_once("class/PHPLiveX.php");
require_once('app_code/global.config.php');
//require_once('class/class_attachment.php');
//require_once('app_code/class.Event.php');
require_once('class/class.email_client.php');
 $html=ob_get_contents();
ob_end_clean();

if($_REQUEST['file_id']!=''){
 $emaildash = new email_client();
 echo $emaildash->download_file($_REQUEST['file_id']);
}

?>