<?php 
require_once('class/config.inc.php');

require_once('class/class.contacts.php');

require_once('class/class.note.php');

$page = new basic_page();

$page->auth->Checklogin();

$contact = new Company_Global();

$contact->SetGroups($page->auth->Get_group());

$contact->SetUserID($page->auth->Get_user_id());

$pattern=$_REQUEST[query];
echo "{ 
query :'".$pattern."',";
echo $output=$contact->GetContact($pattern,'','','yes');
echo '
}';

 ?>