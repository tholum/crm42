<?php
require('class/config.inc.php');

$page = new basic_page();

/*******Checking Authentication****************/

$page->auth->Checklogin();

$user = new User();

$ajax=new PHPLiveX();

$ajax->AjaxifyObjects(array("user"));  

$notify = new Notification();

/**********************************************/

/*******Setting Page access Rules & checking Authorization****************/

$access_rule = array(	"Admin"		=>"Admin" 
					);
$page -> setAccessRules($access_rule);
$page -> setAccessRulesType('all');		// any or all
$page -> CheckAuthorization();

/************************************************************************/
					
$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("GROUP MANAGEMENT");
$page -> setActiveButton('5');
$page -> setInnerNav('');
//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('form.css'); // each page should have it's own .css sheet.  Link mulitple sheets here and below

$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');


$page -> setImportCss3('css/JTip.css');
$page -> setImportCss4('');
$page -> setImportCss5('');
$page -> setExtJavaScripts2('<script language="javascript" SRC="js/jtip.js"></script>'); // might not need
$page -> setExtJavaScripts1('<script language="javascript" SRC="js/jquery.js"></script>'); // might not need
$page -> setExtJavaScripts3(''); // might not need
$page -> setExtJavaScripts4(''); // might not need


//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '';

$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//

$ajax->Run('js/phplivex.js'); // Must be called inside the 'html' or 'body' tags  

$notify->Notify();

$index=$_REQUEST[index];

$user->user_id=$_REQUEST[user_id];
?>

<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>

<div id="content_column_header" class="textb small_text">
	<span>Manage Users and Groups</span><span id="adminnav"><a href="UserManagement.php">users</a> |	<a href="GroupManagement.php">groups</a></span>
</div>
<div class="form_main">

<?php $user->Manage_groups(); ?>
</div>

<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>
<div id="content_column_header">
	<h2>Add a new group: </h2>
</div>

<div class="form_bg">
<div class="form_main">
<?php 
if($_POST[submit]=='add group')
	$user->CreateUserGroup('server');
else
	$user->CreateUserGroup('local');
?>
</div>
</div>

<?php
$page -> displayPageBottom();
?>