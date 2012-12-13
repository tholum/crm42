<?php
ini_set('display_errors',1);
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
require_once('class/class.cases.php');
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
 $cases = new cases;
$cert = new Certification_Type();
$app = new Application();
$dynamic_page = new dynamic_page();
$global_tasks = new GlobalTask();

?>
<table style="width: 100%"><tr><td style="width:70%;vertical-align: top;padding-right: 20px;" >
<div id="div_credential"  class="" style="display:none;"></div>
<div id="prl" style="display:none;"><img  id="prl_image" src="images/spinner.gif"  /></div>
<div id="content_column_header">
		<?php echo $em->GetContactHead($contact_id,'display_tag','em'); ?>
		
			
			<div class="edit_link" id="edit_link"><?php /*?> $app->getResumeLink($em->GetContactID()); ?> | <?php echo $em->ResetPassword($em->GetContactID()) ?> |
			<a style="color:#FF0000;" href="javascript:void(0)" onclick="javascript: em.addLocation('local',<?php echo $em->GetContactID(); ?>,'div_credential',
			{ onUpdate: function(response,root){ 	
		   					 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';							 
							 }, preloader: 'prl'
						} ); return false;" >Location</a> |<?php */?> 
<a onclick="<?php echo $dynamic_page->phplivex_subpage_link('contacts', 'edit', 'display_contact_area', array('contact_id' => $contact_id ) ); ?>" style="color:#FF0000;">Edit this contact</a>
			&nbsp;
			<a onclick="var answer = confirm('Are you sure ?'); if(answer){em.delete_contact('<?php echo $contact_id; ?>' , {target: 'display_contact_area'});}"><img src="images/trash.gif" border="0" /></a></div>
		  
		    <div class="clear"></div>
                    
</div>
<div id="account_case_target" class="account_loader account_padding" >&nbsp;<?php 
       echo $cases->case_by_module( 'CONTACTS' , $vars['contact_id'] , array( 'limit' => '10' ) );
?>
                    </div>

<div class="contact_form">
	<div>
		<?php echo $em->show_order($contact_id);?>
	</div>
<?php
if($_REQUEST['action']=='upload') {
	$doc_id=$_REQUEST['doc_id'];
	if($doc_id){	
	$evt->addContactDocuments('local',$contact_id,$doc_id);
	}
	else {
		$evt->addContactDocuments('local',$contact_id);
	}
}
else {
?>
<div id="documents"><?php  echo $em->showDocuments($contact_id); ?> </div>
<div id="div_editContact"  class="" style="display:none;">
    
<?php
$em->SetContactRefreshJs($dynamic_page->phplivex_subpage_link('contacts', 'contact_profile', 'display_contact_area', array('contact_id' => $contact_id ) ));
if(!$_REQUEST[cont_id]){
	if($_REQUEST['btnSubmit']=='Submit'){
		echo $em->addContactsInCompany('server','em',$contact_id);
	}
	else{
		echo $em->addContactsInCompany('local','em',$contact_id);
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
        $note->SetRefreshJs( $dynamic_page->phplivex_subpage_link('contacts', 'contact_profile', 'display_contact_area', array('contact_id' => $contact_id ) ) );
	if($_POST[submit]=='add message') 
	{	$note->Create_Note('server',$em->GetContactID(),$contact->module,$page->auth->Get_user_id());		
		exit();
	}
	else
		$note->Create_Note('local',$em->GetContactID(),$contact->module,$_SESSION['user_id']); 
	?></div>
	<div>
	<?php echo $note->Get_Note($em->GetContactID(),$contact->module); ?>		
    </div>
        </td><td style="vertical-align: top;">

<div class="form_main">
<div class="form_bg" style="display:none;" id="task_form">
<?
$task->SetModuleID($contact_id);
$task->SetRefreshJs( $dynamic_page->phplivex_subpage_link('contacts', 'contact_profile', 'display_contact_area', array('contact_id' => $contact_id ) ) );
$task->AddTask('local');

 ?>
<div class="Clear"><a href="" onclick="<?php echo $dynamic_page->phplivex_subpage_link('contacts', 'contact_profile', 'display_contact_area', array('contact_id' => $contact_id ) ); ?>; return false;">cancel</a></div>
</div>
	
</div>

<?php //////////////////////    Generate work order ////////////////////// ?>

<div id="gererate"> <?php //  echo $em->linktogenerateorder($em->GetContactID(),'em');?></div>

<h4>Tasks</h4>
<div class="form_main">
<div id="task_area" class="small_text">


<?php 
echo $task->GetTaskForProjectProfile('','','','','','','',1,'contacts',$contact_id); 
if($_POST['save_edit']=='Save') 
	$task->EditTask('server',$_POST['task_id'],'task',$em->GetContactID());
?>
</div>
</div>
<H4>Flow Chart Tasks</H4>
<div><div><?php echo $global_tasks->FlowChartDiv( 'contacts' , $vars['contact_id'] , 'tbl_contacts'); ?></div></div>
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
</div>
        </td></tr></table>