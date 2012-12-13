<?php
require_once('app_code/config.inc.php');
require_once('class/class.contacts.php');
require_once('app_code/zipcode.class.php');
require_once("app_code/class.application.php");
require_once('app_code/class.Event_Contacts.php');
$page = new basic_page();

/*******Checking Authentication****************/

$page->auth->Checklogin();

$app = new Application();
$notify = new Notification();

					
$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("APPLICATION");
$page -> setActiveButton('2');
$page -> setInnerNav('');
//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('form.css'); // each page should have it's own .css sheet.  Link mulitple sheets here and below

$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');

$page -> setImportCss4('css/all.css');
$page -> setImportCss5('../js/zapatec/winxp.css');
$page -> setImportCss6('css/all.css');
$page -> setImportCss7('../css/style.css'); // should be page specific css such as index.css
$page -> setImportCss8('contact_profile.css');

$page -> setExtJavaScripts1(''); // might not need
$page -> setExtJavaScripts3(''); // might not need
$page -> setExtJavaScripts4(''); // might not need
//$page -> setCustomJavaScripts('');


$page -> setExtJavaScripts1('<script language="javascript" type="text/javascript" src="../js/zapatec/zapatec.js"></script>'); // might not need //
$page -> setExtJavaScripts2('<script language="javascript" type="text/javascript" src="../js/zapatec/calendar.js"></script>'); // might not need //
$page -> setExtJavaScripts3('<script language="javascript" type="text/javascript" src="../js/zapatec/calendar-en.js"></script>'); // might not need //
$page -> setExtJavaScripts4('<script language="javascript" type="text/javascript" src="sprockets.js"></script>'); // might

//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style =  '
input {
width :auto !important;
}
body 
{ color:#000000;
font-family:Verdana,Arial,Helvetica,sans-serif !important;
font-size:11px !important;
font-weight:normal !important;
padding:5px !important;
text-align:justify;
text-decoration:none;
}

#content_column{
background:url("images/transparent_90.png") repeat scroll 0 0 transparent;
border-bottom:1px solid #999999;
border-right:1px solid #999999;
float:left;
width:100%;
}
';

$page -> setPageStyle($page_style);

$page -> displayPageTop();


// **********************Start html for content column ****************************//

$notify->Notify();

$index=$_REQUEST[index];
extract($_REQUEST);

?>
<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif" /></div>
<div id="content_column_header" class="textb small_text">
	<span>Resume</span>
</div>
<div class="form_main">

<?php
switch($index){ 
case 'Edit' :
    	if(isset($_POST['setform']))
		{
			
			$app->EditResume('server',$_REQUEST[contact_id]);
			
		}
		else 
		{
			
			$app->EditResume('local',$_REQUEST[contact_id]);
			
		}
		break;
default :
		//$app->EditResume('server',$_REQUEST[contact_id]);
		$app->ViewResume($_REQUEST[contact_id]); 
}
?>
</div>

<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>
<?php
$page -> displayPageBottom();
?>