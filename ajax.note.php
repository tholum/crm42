<?php
require_once('app_code/config.inc.php');
require_once('class/class.contacts.php');
require_once('app_code/zipcode.class.php');
require_once('app_code/class.Event_Contacts.php');
require_once'class/class.note.php';
require_once 'class/class.tasks.php';
require_once('app_code/global.config.php');
require_once('app_code/class.CertificationType.php');
require_once('app_code/class.application.php');
require_once('app_code/class.Event.php');
require_once('class/class.securenote.php');
$secure = new secure;
if( PHONE_SYSTEM == "asterisk"){
    require_once('class/class.asterisk.php');
    $asterisk = new Asterisk;
}
$contact_id=$vars['contact_id'];
$em = new Event_Contacts();
$em->SetContactID($contact_id);
$contact = new Contacts();
$evt= new Event();
$page=new basic_page();
$note = new Note();
$task=new Tasks();
$notify = new Notification();

$cert = new Certification_Type();
$app = new Application();
$dynamic_page = new dynamic_page();
$note->Create_Note('server','','',$_SESSION['user_id']);
?>
