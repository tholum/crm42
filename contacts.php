<?php
//ini_set("display_errors" , 1 );
require_once('app_code/config.inc.php');

require_once( 'class/class.contacts.php');
require_once('app_code/zipcode.class.php');
require_once('app_code/class.Event_Contacts.php');
if( PHONE_SYSTEM == "asterisk"){
    require_once('class/class.asterisk.php');
    $asterisk = new Asterisk;
}

$page = new basic_page();

$page->auth->Checklogin();

$notify= new Notification();

$ajax=new PHPLiveX();

//$contact=new Company_Global();

$em = new Event_Contacts();

$em->SetGroups($page->auth->Get_group());

$em->SetUserID($page->auth->Get_user_id());

$em->SetUserName($page->auth->Get_user_name());

$ajax->AjaxifyObjects(array("em"));  


$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("CONTACTS");
$page -> setActiveButton('2');
//$page -> setInnerNav('');
//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('form.css');
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');


$page -> setImportCss3('');
$page -> setImportCss4('');
$page -> setImportCss5('');
//$page -> setExtJavaScripts($external_js); // might not need



//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '
';

$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//
$ajax->Run('js/phplivex.js'); // Must be called inside the 'html' or 'body' tags  

$notify->Notify();
$isAdminGroup = $page->auth->checkPermessionEdit('TBL_CONTACT',$_REQUEST[contact_id])==1 or $page->auth->isOwner(TBL_CONTACT," and contact_id='$_REQUEST[contact_id]'")
?>

<div id="content_column_header">
	<div class="form_main">
		<div class="head">Contact Search</div>
		<div class="head contact_textbox">
		  <!--<input type="text" name="textfield" />&nbsp; or &nbsp; <a href="#">More Options</a>-->
		  <?php $em->ContactSearchBox('em'); ?>
	    </div>
	</div>
</div>
<div class="form_main">
	<div><?php $em->ContactSearchContainer('em'); ?></div>
</div>








<?php
$notify->Notify();

// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>
<div class="form_main">
	<?php /*?><div><a href="contact_addperson.php"><img src="images/add_person.jpg" border="0" /></a></div><?php */?>
	<div><a href="contact_addcompany.php"><img src="images/add_company.jpg" /></a></div>
	<?php /*?><div><a href="csv_import.php"><img src="images/import_outlook.png" /></a></div>
	<div><a href="vcard_import.php"><img src="images/import_vcard.png" /></a></div>
	<div><a href="csv_export.php"><img src="images/export_outlook.png" /></a></div><?php */?>
	<div class="form_main">	<?php echo $em->GetALLTagsAtoZ();?>	</div>
</div>






<?php
$page -> displayPageBottom();
?>
