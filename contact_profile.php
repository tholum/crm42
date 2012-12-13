<?php
session_start();

//ini_set('display_errors',1);
$open_task = $_GET["open_task"];
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

$page = new basic_page();

$page->auth->Checklogin();

if($_REQUEST[contact_id]=='') 
{
  $page->gotoPage('contacts.php');
  exit();
}


 /*
if(		$page->auth->checkPermessionView('TBL_CONTACT',$_REQUEST[contact_id])==0 and 
		$page->auth->isOwner(TBL_CONTACT," and contact_id='$_REQUEST[contact_id]'")==0)
{
		$page->auth->SendToRefrerPage();
		exit();
}
*/
$em = new Event_Contacts();
$contact = new Contacts();
$ajax = new PHPLiveX();

$evt= new Event();

$note = new Note();

$notify = new Notification();

$cert = new Certification_Type();
$app = new Application();

/******************** Contact Object ***********************/
$em->SetUserID($page->auth->Get_user_id());

$em->SetContactID($_REQUEST[contact_id]);

$em->SetUserName($page->auth->Get_user_name());

$_SESSION['contact_id'] = $em->GetContactID();

/*********************************************************/

/******************** Task Object ***********************/
$user = new User();

$task = new Tasks();

$task -> SetUserObject($user);

$task -> SetUserID($page->auth->Get_user_id());

$ajax->AjaxifyObjects(array("secure" , "cert","em","task","note"));  


/*******************************************************/

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("CONTACT PROFILE");
$page -> setActiveButton('2');
//$page -> setInnerNav('');
//$page -> setImportCss1('main_style.css');
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');
$page -> setImportCss2('contact_profile.css');

//$page -> setImportCss3('form.css');

$page -> setImportCss3('src/css/jscal2.css');
$page -> setImportCss4('src/css/border-radius.css');
//$page -> setImportCss5('src/css/win2k/win2k.css');
$page -> setImportCss6('tablesort/themes/blue/style.css');


$page -> setExtJavaScripts1('<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>'); // might not need
$page -> setExtJavaScripts2('<script src="js/301a.js" type="text/javascript"></script>'); // might not need
$page -> setExtJavaScripts3('<script type="text/javascript" src="src/js/jscal2.js"></script>'); // might not need
$page -> setExtJavaScripts4('<script type="text/javascript" src="src/js/lang/en.js"></script>'); // might not need
$page -> setExtJavaScripts5('<script type="text/javascript" src="tablesort/jquery.tablesorter.js"></script>'); 
$page -> setExtJavaScripts6('<script type="text/javascript" src="tablesort/chili-1.8b.js"></script>'); 
$page -> setExtJavaScripts7('<script src="sprockets.js" type="text/javascript"></script>'); // might not need
$page -> setExtJavaScripts8('<script type="text/javascript" src="autocomplete/prototype.js"></script>');
$page -> setExtJavaScripts9('<script type="text/javascript" src="autocomplete/autocomplete.js"></script>');

$page -> setCustomJavaScripts('
	$(function() {		
		$("#search_table")
		.tablesorter({widthFixed: true, widgets: ["zebra"], sortList: [[0,0]] } )
		
	});																
');



//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '
#lightbox {  top: 0 !important; }
caption, th {   vertical-align: top !important;}
#description {
width: 90% !important;
}

';

$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//
$ajax->Run(); // Must be called inside the 'html' or 'body' tags  

$notify->Notify();

$index=$_REQUEST[index];
if($index=='Remove')
{
if($page->auth->checkPermessionEdit('TBL_CONTACT',$em->GetContactID())==1 or $page->auth->isOwner(TBL_CONTACT," and contact_id='$_REQUEST[contact_id]'")==1)
	$em->RemoveContact($em->GetContactID());
else{
		$page->auth->SendToRefrerPage();
		exit();
	}
}
?>

<div id="div_credential"  class="" style="display:none;"></div>
<div id="prl" style="display:none;"><img  id="prl_image" src="images/spinner.gif"  /></div>
<div id="content_column_header">
		<?php echo $em->GetContactHead($em->GetContactID(),'display_tag','em'); ?>
		<?php if($page->auth->checkPermessionEdit('TBL_CONTACT',$_REQUEST[contact_id])==1 or $page->auth->isOwner(TBL_CONTACT," and contact_id='$_REQUEST[contact_id]'")==1){  ?>
			
			<div class="edit_link"><?php /*?> $app->getResumeLink($em->GetContactID()); ?> | <?php echo $em->ResetPassword($em->GetContactID()) ?> |
			<a style="color:#FF0000;" href="javascript:void(0)" onclick="javascript: em.addLocation('local',<?php echo $em->GetContactID(); ?>,'div_credential',
			{ onUpdate: function(response,root){ 	
		   					 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';							 
							 }, preloader: 'prl'
						} ); return false;" >Location</a> |<?php */?> 
			<a href="contact_edit.php?contact_id=<?php echo $em->GetContactID(); ?>" style="color:#FF0000;">Edit this contact</a>
			&nbsp;
			<a href="<?php $_SERVER['PHP_SELF']?>?contact_id=<?php echo $em->GetContactID(); ?>&index=Remove"
				 onclick="return confirm('Are you sure ?');"><img src="images/trash.gif" border="0" /></a></div>
		    <?php } ?>
		    <div class="clear"></div>
</div>


<div class="contact_form">
	<div class="profile_box1">
		<div id="div_doc" >
		<h2>Contact Documents:</h2> 
			<div align="right">
				<a href="contact_profile.php?contact_id=<?php echo $em->GetContactID(); ?>&action=upload" >Add Documents</a>
			</div>
		
		</div>
	</div>
	<div>
		<?php echo $em->show_order($_REQUEST[contact_id]);?>
	</div>
<?php
if($_REQUEST['action']=='upload') {
	$doc_id=$_REQUEST['doc_id'];
	if($doc_id){	
	$evt->addContactDocuments('local',$em->GetContactID(),$doc_id);
	}
	else {
		$evt->addContactDocuments('local',$em->GetContactID());
	}
}
else {
?>
<div id="documents"><?php  echo $em->showDocuments($em->GetContactID()); ?> </div>
<div id="div_editContact"  class="" style="display:none;">
<?php
if(!$_REQUEST[cont_id]){
	if($_REQUEST['btnSubmit']=='Submit'){
		echo $em->addContactsInCompany('server','em',$em->GetContactID());
	}
	else{
		echo $em->addContactsInCompany('local','em',$em->GetContactID());
	 }
}
 ?>
</div>
<?php
}
?>
<iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;">
</iframe>
<iframe id="add_target" name="add_target" src="#" style="width:0;height:0;border:0px solid #fff;">
</iframe>
</div>
<div class="contact_form">
<div class="profile_box1">
<div id="div_doc" >
<?php /*?>    <h2>Latest Calls</h2>
<?php */?>    <?php
    if( PHONE_SYSTEM == "asterisk"){
        $asterisk->display_call_rec( $em->get_contact_phone( $em->GetContactID() ) , "5" );
    }?>

    
</div></div></div>

<?php


	?><div class="Clear"><?php
	if($_POST[submit]=='add message') 
	{	$note->Create_Note('server',$em->GetContactID(),$contact->module,$page->auth->Get_user_id());		
		exit();
	}
	else
		$note->Create_Note('local',$em->GetContactID(),$contact->module,$page->auth->Get_user_id()); 
	?></div>
	<div>
	<?php echo $note->Get_Note($em->GetContactID(),$contact->module); ?>		
    </div>
<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>

<div class="form_main">
<a href="task.php?contact_id=<?php echo $em->GetContactID(); ?>" id="task_link" onclick="document.getElementById('task_form').style.display=''; this.style.display='none'; return false;"><img src="images/add_task.png" class="action_links" /></a><br />

<div class="form_bg" style="display:none;" id="task_form">
<?
if($_POST['Save']=='Add this Task')
$task->AddTask('server');
else


 $task->AddTask('local');
?>
<div class="Clear"><a href="" onclick="document.getElementById('task_link').style.display=''; document.getElementById('task_form').style.display='none'; return false;">cancel</a></div>
</div>

<!--	<div><a href="csv_import.php"><img src="images/import_outlook.png" /></a></div>
	<div><a href="vcard_import.php"><img src="images/import_vcard.png" /></a></div>
	<div><a href="csv_export.php"><img src="images/export_outlook.png" /></a></div>
<div><a href="exportVcard.php?contact_id=<?php echo $em->GetContactID() ?>" target="_blank"><img src="images/export_vcard.png" /></a></div>
	<div><a href="runas.php?contact_id=<?php echo $em->GetContactID() ?>"><img src="images/runas.png" /></a></div>
-->	
</div>

<?php //////////////////////    Generate work order ////////////////////// ?>

<div><a href="javascript:void(0);" onclick="javascript: em.generateorder('<?php echo $em->GetContactID();?>','em',{preloader:'prl'});"><img src="images/generate_order.png" alt="Generate Order" /></a></div>
<div id="gererate"> <?php //  echo $em->linktogenerateorder($em->GetContactID(),'em');?></div>



<div class="profile_box1" ><?php echo $em->erpContactScreenCustom($em->GetContactID());?></div>
<?php echo $secure->creditcard_box('contacts', $em->GetContactID())?>
<h4>Tasks</h4>
<div class="form_main">
<div id="task_area" class="small_text">


<?php 
echo $task->GetTaskForProjectProfile('','','','','','','',1,'TBL_CONTACT',$em->GetContactID()); 
if($_POST['save_edit']=='Save') 
	$task->EditTask('server',$_POST['task_id'],'task',$em->GetContactID());
?>
</div>
</div>

<div class="data_box">
<?php echo $em->GetContactProfile($em->GetContactID());?>
</div>

<?php if($open_task == "yes"){ ?>
<script type="text/javascript">
document.getElementById('task_form').style.display='';
document.getElementById('task_link').style.display='none';
</script>
<?php
}
?>
<div id="div_contacts_in_company" class="data_box form_main">
<?php 
echo $em->GetPeopleInCompnay($em->GetContactID(),'em','no_image');
?>
</div>
<a href="javascript:void(0);" onclick="javascript: document.getElementById('div_editContact').style.display='block';">add</a>
<?php

$page -> displayPageBottom();
?>