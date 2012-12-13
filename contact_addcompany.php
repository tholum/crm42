<?php
require_once('app_code/config.inc.php');
require_once('class/class.contacts.php');
require_once('app_code/zipcode.class.php');
require_once('app_code/class.Event_Contacts.php');

$page = new basic_page();

$page->auth->Checklogin();

$ajax=new PHPLiveX();

$em = new Event_Contacts();

$em->SetUserID($page->auth->Get_user_id());

$ajax->AjaxifyObjects(array("em"));  


$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("CONTACT ADD COMPANY");
$page -> setActiveButton('2');
//$page -> setInnerNav('');
//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('form.css');
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');

$page -> setImportCss3('');
$page -> SetDynamicCSS_3('css/all.php');
$page -> setImportCss5('');
$page -> setExtJavaScripts1('<script src="sprockets.js" type="text/javascript"></script>'); // might not need



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
		<div class="heading"><img src="images/person.gif" class="image_border" align="absmiddle" />Add a new company</div>
</div>
<div class="contact_form">
	<div><?php 
	if($_POST['submit']=='Add Contact')
	{
	$em->AddContact('server','Company');
	}
	else
	{
	$em->AddContact('local','Company');
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