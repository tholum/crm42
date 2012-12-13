<?php
//ini_set("display_errors" , 1 );
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

$page = new basic_page();

$page->auth->Checklogin();

$user = new User();

$task = new Tasks();

$task -> SetUserObject($user);

$task -> SetUserID($page->auth->Get_user_id());

if($_POST['Save']=='Add this Task')
$task->AddTask('server');
else
 $task->AddTask('local');

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("TASK POPUP");
$page -> setActiveButton('2');
//$page -> setInnerNav('');
//$page -> setImportCss1('main_style.css');
//$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');
$page -> setImportCss2('contact_profile.css');

//$page -> setImportCss3('form.css');

$page -> setImportCss4('src/css/jscal2.css');
$page -> setImportCss5('src/css/border-radius.css');
$page -> setImportCss6('src/css/win2k/win2k.css');
/*$page -> setImportCss7('autocomplete/jssuggest.css');*/

$page -> setImportCss8(PATHDRASTICTOOLS.'css/grid_default.css');





$page -> setExtJavaScripts1('<script src="js/301a.js" type="text/javascript"></script>'); // might not need
$page -> setExtJavaScripts2('<script type="text/javascript" src="src/js/jscal2.js"></script>'); // might not need
$page -> setExtJavaScripts3('<script type="text/javascript" src="src/js/lang/en.js"></script>'); // might not need
/*$page -> setExtJavaScripts4('<script type="text/javascript" src="autocomplete/jquery-1.2.6.min.js"></script>');*/
/*$page -> setExtJavaScripts5('<script type="text/javascript" src="autocomplete/jquery.jSuggest.1.0.js"></script>');*/
$page -> setExtJavaScripts6('<script type="text/javascript" src="'.PATHDRASTICTOOLS.'js/mootools-1.2-core.js"></script>');
$page -> setExtJavaScripts7('<script type="text/javascript" src="'.PATHDRASTICTOOLS.'js/drasticGrid.js"></script>');

$page -> setCustomJavaScripts('
/*$(function(){
	$("#search").jSuggest({
		url: "search.php",
		data: "pattern",
		autoChange: false,
		minchar: 1,
		delay: 0,
		type: "GET"

	});
});
*/
');
$page -> setPageStyle($page_style);

$page -> displayPageTop();

?>
?>
