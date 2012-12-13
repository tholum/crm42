<?php
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
require_once('class/class_attachment.php');
//require_once('class/class.display.php');
require_once('app_code/class.Event.php');


if($_REQUEST['doc']!='' and $_REQUEST['name']!=''){
 //$page = new basic_page();
 //$page->auth->Checklogin();
 $evt= new Event();
 $evt->download_document($_REQUEST['doc'],$_REQUEST['name']);
}
else{
 $att= new Attachment();
 $id=$_REQUEST['id'];
 $user_id=$_REQUEST['user_id'];
 $omessage_id=$_REQUEST['omessage_id'];
 $att->download_attachment($id,$omessage_id,$user_id);
}
?>