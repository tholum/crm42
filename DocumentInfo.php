<?php
require('class/config.inc.php');

require 'class/class.file.php';

$page = new basic_page();

$page->auth->Checklogin();

$files_obj=new File('files');

$files_obj->SetUserID($page->auth->Get_user_id());

$doc_id=$_REQUEST[doc_id];

$files_obj->DocumentInfo($doc_id); 

?>
