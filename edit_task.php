<?php
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
$page -> setPageTitle("EDIT TASK");
$page -> setActiveButton('3');
//$page -> setInnerNav('');
//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('form.css');
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');


$page -> setImportCss3('src/css/jscal2.css');
$page -> setImportCss4('src/css/border-radius.css');
$page -> setImportCss5('src/css/win2k/win2k.css');
 

$page -> setExtJavaScripts1('<script src="js/301a.js" type="text/javascript"></script>'); // might not need
$page -> setExtJavaScripts2('<script type="text/javascript" src="src/js/jscal2.js"></script>'); // might not need
$page -> setExtJavaScripts3('<script type="text/javascript" src="src/js/lang/en.js"></script>'); // might not need
$page -> setExtJavaScripts4(''); // might not need


//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '
';

$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//
$notify->Notify();

$ajax->Run(); // Must be called inside the 'html' or 'body' tags   
?>

<div id="content_column_header">
<div id="display_tag" class="display_tag" style="display:none">&nbsp;</div>
	<div class="form_main">
		<?php $task->TaskHeader(); 
		?>
		<?php // $task->Get_Tag($_REQUEST[module_id],'display_tag'); ?>
	</div>
</div>
<div class="form_main">
	<div class="textb" id="task_area"><?php echo $task->GetTask(); ?></div>
</div>

<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>


<div class="form_bg">
<?
if($_POST['Save']=='Save')
$task->EditTask('server',$_REQUEST[task_id]);
else
 $task->EditTask('local',$_REQUEST[task_id]);
?>
<div class="Clear ">&nbsp;</div>
</div>


<?php
$page -> displayPageBottom();
?>