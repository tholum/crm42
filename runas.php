<?php
require('app_code/config.inc.php');

require 'class/class.tasks.php';

require('class/class.message.php');

require('class/class.news.php');

require 'class/class.contacts.php';


require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Calendar');
Zend_Loader::loadClass('Zend_Http_Client');

require_once('class/class.calendar.php');

$cal=new GCalendar(USER,PASS);

$page = new basic_page();

$user=new User();

$page->auth->Checklogin();

$notify= new Notification();

$contact = new Company_Global();

$contact->SetUserID($page->auth->Get_user_id());

$contact->SetUserName($page->auth->Get_user_name());

$news= new News();

$message= new Message();

$ajax = new PHPLiveX();

$user = new User();

$task = new Tasks();

$task -> SetUserObject($user);

$task -> SetUserID($page->auth->Get_user_id());

$ajax -> AjaxifyObjects(array("task"));  

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("RUN AS");
$page -> setActiveButton('1');
$page -> setInnerNav('0');
//$em = new Event_Contacts();
//$em->SetContactID($_REQUEST[contact_id]);
$contact_id = $_REQUEST["contact_id"];
$db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
$sql="select * from ".EM_WEB_APP_INFO." where contact_id = '$contact_id'";
$result=$db->query($sql,__FILE__,__LINE__);
$_SESSION['contact_user_name'] = mysql_result( $result , 0 , "username");
$_SESSION['contact_id'] = mysql_result( $result , 0 , "contact_id" );
//header("location: /client/welcome.php");
//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.

?>
<html>
<head>
</head>
<body onLoad="window.location='../welcome.php'">
</body>