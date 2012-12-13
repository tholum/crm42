<?php
//ini_set('display_errors',1);

require('app_code/config.inc.php');
require_once 'class/class.contacts.php';
require('class/class.ManageCategories.php'); 

$page = new basic_page();
$user=new User();
$page->auth->Checklogin();
$obj = new ManageCategories();

//$notify= new Notification();

$contact = new Company_Global();
$contact->SetUserID($page->auth->Get_user_id());
$contact->SetUserName($page->auth->Get_user_name());


$ajax = new PHPLiveX();

//$ajax -> AjaxifyObjects(array(""));  

//$task -> SetUserObject($user);

//$task -> SetUserID($page->auth->Get_user_id());

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("MANAGE CATEGORIES");
$page -> setActiveButton('5');
$page -> setInnerNav('0');

$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');


$page -> setCustomJavaScripts('');
//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.


$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//
//$notify->Notify();
$ajax->Run(); // Must be called inside the 'html' or 'body' tags   
?>


<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
<div id="content_column_header">
Manage master fields :
</div>
<div class="form_main" style="text-align:left !important;">
<?php
 	 /////////BindingOptions/////////////
	if(@$_POST['go7']=='go')
		echo $obj->set_archive('server');
	else
		echo $obj->set_archive('local');
?>
</div>
<?php
	if(@$_REQUEST['action6']=='delete_archive') {	
		$obj->delete_archive(@$_REQUEST['event12'],@$_REQUEST['event13']);
	}
?>
<div class="form_main" style="text-align:left !important;">
<?php
 	 /////////BindingOptions/////////////
	if(@$_POST['go8']=='go')
		echo $obj->set_read('server');
	else
		echo $obj->set_read('local');
?>
</div>
<?php
	if(@$_REQUEST['action7']=='delete_read') {	
		$obj->delete_read(@$_REQUEST['event12'],@$_REQUEST['event13']);
	}
?>
<div class="form_main" style="text-align:left !important;">
<?php
 	 /////////FCT/////////////
	if(@$_POST['go_fct_search_level']=='go')
		echo $obj->set_fct_search_level('server');
	else
		echo $obj->set_fct_search_level('local');
?>
</div>
<?php
	if(@$_REQUEST['action_fct_search_level']=='delete_fct_search_level') {	
		$obj->delete_fct_search_level(@$_REQUEST['event14'],@$_REQUEST['event15']);
	}
?>
<div class="form_main" style="text-align:left !important;">
<?php
 	 /////////Origin/////////////
	if(@$_POST['go_origin']=='go')
		echo $obj->set_origin('server');
	else
		echo $obj->set_origin('local');
?>
</div>
<?php
	if(@$_REQUEST['action_origin']=='delete_origin') {	
		$obj->delete_origin(@$_REQUEST['event16'],@$_REQUEST['event17']);
	}
?>
<div class="form_main" style="text-align:left !important;">
<?php
 	 /////////Type/////////////
	if(@$_POST['go_type']=='go')
		echo $obj->set_type('server');
	else
		echo $obj->set_type('local');
?>
</div>
<?php
	if(@$_REQUEST['action_type']=='delete_type') {	
		$obj->delete_type(@$_REQUEST['event18'],@$_REQUEST['event19']);
	}
?>
<div class="form_main" style="text-align:left !important;">
<?php
 	 /////////staus/////////////
	if(@$_POST['go_staus']=='go')
		echo $obj->set_staus('server');
	else
		echo $obj->set_staus('local');
?>
</div>
<?php
	if(@$_REQUEST['action_staus']=='delete_staus') {	
		$obj->delete_staus(@$_REQUEST['event20'],@$_REQUEST['event21']);
	} ?>
<div class="form_main" style="text-align:left !important;">
<div id="case_type_suboption">
<?php
 	 /////////Case Type Suboptions/////////////
	if(@$_POST['go_type_suboption']=='go')
		echo $obj->set_type_suboptions('server');
	else
		echo $obj->set_type_suboptions('local');
?>
</div>
</div>
<?php
	if(@$_REQUEST['action_type_suboption']=='delete_type_suboption') {	
		$obj->delete_typ_suboption(@$_REQUEST['event28'],@$_REQUEST['event29'],@$_REQUEST['tion']);
	}
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
$page -> displayPageBottom();
?>
