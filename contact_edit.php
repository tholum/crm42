<?php
require_once('app_code/config.inc.php');
require_once('class/class.contacts.php');
require_once('app_code/zipcode.class.php');
require_once('app_code/class.Event_Contacts.php');

$page = new basic_page();

$page->auth->Checklogin();
$em = new Event_Contacts();

//if($_REQUEST[contact_id]=='') {
//  $page->gotoPage('contacts.php');
//  exit();
// }



if(		$page->auth->checkPermessionEdit('TBL_CONTACT',$_REQUEST[contact_id])==0 and 
		$page->auth->isOwner(TBL_CONTACT," and contact_id='$_REQUEST[contact_id]'")==0)
{
		$page->auth->SendToRefrerPage();
		exit();
}


$ajax=new PHPLiveX();


$em->SetUserID($page->auth->Get_user_id());

$ajax->AjaxifyObjects(array("em"));  



$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("CONTACT EDIT");
$page -> setActiveButton('2');
//$page -> setInnerNav('');
//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('form.css');
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');

$page -> setImportCss3('');
$page -> SetDynamicCSS_3('css/all.php'); 
$page -> setImportCss5('autocomplete/styles.css');

$page -> setExtJavaScripts1('<script src="sprockets.js" type="text/javascript"></script>'); // might not need
$page -> setExtJavaScripts2('<script type="text/javascript" src="autocomplete/prototype.js"></script>');
$page -> setExtJavaScripts3('<script type="text/javascript" src="autocomplete/autocomplete.js"></script>');



//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '
';

$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//
$ajax->Run(); // Must be called inside the 'html' or 'body' tags  
?>

<div id="content_column_header">
		<?php echo $em->getContactType($_REQUEST[contact_id],'yes'); ?>
</div>
<div class="contact_form">
	<div><?php 
	if($_POST['save']=='Save')
	{
	$em->editEvent_Contact('server',$_REQUEST[contact_id],'editEvent_Contact',$em->getContactType($_REQUEST[contact_id],''));
	}
	else
	{
	$em->editEvent_Contact('local',$_REQUEST[contact_id],'editEvent_Contact',$em->getContactType($_REQUEST[contact_id],''));
	}
	 ?></div>
</div>








<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>
<div class="form_main">
	<?php /*?><div><a href="contact_addperson.php"><img src="images/add_person.jpg" border="0" /></a></div><?php */?>
	<div><a href="contact_addcompany.php"><img src="images/add_company.jpg" /></a></div>
</div>





<?php
$page -> displayPageBottom();
?>