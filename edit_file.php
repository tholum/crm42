<?php
require('class/config.inc.php');

require 'class/class.file.php';

$page = new basic_page();

$page->auth->Checklogin();

$filesobj=new File('files');

$filesobj->SetUserID($page->auth->Get_user_id());

$filesobj->SetUserGroup($page->auth->Get_group_string());

$ajax=new PHPLiveX();

$ajax->AjaxifyObjects(array("filesobj")); 

$notify= new Notification();

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle(MASTER_BROWSER_TITLE);
$page -> setActiveButton('4');
//$page -> setInnerNav('');
//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('form.css');
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');

$page -> setImportCss3('css/JTip.css');
$page -> setImportCss4('');
$page -> setImportCss5('');
$page -> setExtJavaScripts2('<script language="javascript" SRC="js/jtip.js"></script>'); // might not need
$page -> setExtJavaScripts1('<script language="javascript" SRC="js/jquery.js"></script>'); // might not need




//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '';

$page -> setPageStyle($page_style);

$page -> displayPageTop();
//////////////////////////****************//////////*****************************************************
$ajax->Run('js/phplivex.js'); // Must be called inside the 'html' or 'body' tags  
$notify->Notify();
// **********************Start html for content column ****************************//
?>
<div id="div_file" style="display:none"></div>
<div id="content_column_header">
<div id="display_tag" class="display_tag" style="display:none">&nbsp;</div>
	<div class="form_main">
		<?php
			$filesobj->displayFileHead(); ?>
	</div>
</div>

<div class="form_main">
<div class="textb" id="file_area">
<?php
echo $filesobj->displayFileOfCategorySummary();
?>
</div>
</div>

<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();

// **********************Start code for Info Column ****************************//
?>
<div class="form_bg">
<?
if($_POST['submit']=='save this file')
$filesobj->EditFile('server',$_REQUEST[file_id]);
else
$filesobj->EditFile('local',$_REQUEST[file_id]);
?>
<div class="Clear ">&nbsp;</div>
</div>
<?

//******************************************************************************//
$page -> displayPageBottom();
?>