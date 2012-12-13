<?php
//ini_set('display_errors',1);
require('app_code/config.inc.php');

require 'class/class.tasks.php';

$page = new basic_page();

$page->auth->Checklogin();

$ajax = new PHPLiveX();

$user = new User();

$task = new Tasks();

$task -> SetUserObject($user);

$task -> SetUserID($page->auth->Get_user_id());
  
$ajax -> AjaxifyObjects(array("task"));  

$notify = new Notification();

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("TASK");
$page -> setActiveButton('3');
$page -> setInnerNav('');
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');


$page -> setImportCss3('src/css/jscal2.css');
$page -> setImportCss4('src/css/border-radius.css');
$page -> setImportCss5('src/css/win2k/win2k.css');
$page -> setImportCss6('tablesort/themes/blue/style.css');

$page -> setExtJavaScripts1('<script src="js/301a.js" type="text/javascript"></script>'); // might not need
$page -> setExtJavaScripts2('<script type="text/javascript" src="src/js/jscal2.js"></script>'); // might not need
$page -> setExtJavaScripts3('<script type="text/javascript" src="src/js/lang/en.js"></script>'); // might not need
$page -> setExtJavaScripts4('<script type="text/javascript" src="autocomplete/jquery-1.2.6.min.js"></script>');
$page -> setExtJavaScripts5('<script type="text/javascript" src="tablesort/jquery.tablesorter.js"></script>'); 
$page -> setExtJavaScripts6('<script type="text/javascript" src="tablesort/chili-1.8b.js"></script>'); 

$page -> setCustomJavaScripts('
	$(function() {			
		$("#display_search") 
		.tablesorter({widthFixed: true, widgets: ["zebra"], sortList: [[0,0]] } ) 
	});																	
');
//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '';

$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//
$notify->Notify();

$ajax->Run(); // Must be called inside the 'html' or 'body' tags   
?>
<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
<div id="content_column_header">
<div id="display_tag" class="display_tag" style="display:none">&nbsp;</div>
	<div class="event_form small_text">
		<?php echo $task->searchTaskHeader(); ?>
		<?php //$task->TaskHeader(); ?>
		<?php // $task->Get_Tag($_REQUEST[module_id],'display_tag'); ?>
	</div>
</div>
<div class="form_main">
	<div class="textb" id="task_area">
		<?php //echo $task->GetTask('','','','','','','','','','','','',1); ?>
	    <?php echo $task->show_searchTaskHeader('','','','','','','','','',''); ?>
	</div>
</div>

<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>


<div id="Add_Edit_Task" class="form_bg">
<?
//print_r($_POST);
$task->AddTask('server');
if($_POST['Save']=='Add this Task')
$task->AddTask('server');
else
 $task->AddTask('local');


if($_POST['save_edit']=='Save')
	$task->EditTask('server',$_POST['task_id']);

 
?>
<div class="Clear ">&nbsp;</div>
</div>


<?php
$page -> displayPageBottom();
?>