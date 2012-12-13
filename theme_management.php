<?php
require_once('class/config.inc.php');
require_once('class/class.ThemeManagement.php');
$page = new basic_page();

/*******Checking Authentication****************/

$page->auth->Checklogin();

$user = new User();

$notify = new Notification();

$theme = new ThemeManagement();

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
$page -> setPageTitle("THEME MANAGEMENT");
$page -> setActiveButton('2');
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

$notify->Notify();

$index=$_REQUEST[index];

$user->user_id=$_REQUEST[user_id];
?>
<div id="content_column_header">
	<h2>Theme Management</h2>
</div>
<div class="form_main">
<?php
if($_POST[submit]=='Submit')
{
$theme->manageTheme('server');

}
else
{
$theme->manageTheme('local');
}
?>

</div>




<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>
<div class="form_main">
	<div><a href="UserManagement.php"><img src="images/manage_user.png" border="0" /></a></div>
	<div><a href="GroupManagement.php"><img src="images/manage_groups.png" border="0" /></a></div>
	<div><a href="report_builder.php"><img src="images/report_builder.png" alt="Report Builder" border="0" /></a></div>
</div>
<?php
$page -> displayPageBottom();
?>