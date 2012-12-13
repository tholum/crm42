<?php
require('class/config.inc.php');

require 'class/class.contacts.php';

require 'class/class.note.php';

$page = new basic_page();

//$page->auth->Checklogin();

$contact = new Company_Global();

$contact->SetGroups($page->auth->Get_group());

$contact->SetUserID($page->auth->Get_user_id());

$contact->SetUserName($page->auth->Get_user_name());

$pattern=trim($_REQUEST['pattern']);

if($pattern!=''){
echo '<ul>';
echo $contact->GetContact($pattern,'','listonly');
echo '</ul>';
}
?>
