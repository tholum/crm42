<?php
require_once('class/config.inc.php');
require_once('class/class.tasks.php');
$page = new basic_page();

/*******Checking Authentication****************/

$page->auth->Checklogin();

$task = new Tasks();

$notify = new Notification();

/**********************************************/

					
$page -> setPageKeywords('');
$page -> setPageDescription(''); 
$page -> setPageTitle("CATEGORY MANAGEMENT");
$page -> setActiveButton('3');
$page -> setInnerNav('0');
//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('form.css'); // each page should have it's own .css sheet.  Link mulitple sheets here and below
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');

$page -> setImportCss4('');
$page -> setImportCss5('');
$page -> setExtJavaScripts2(''); // might not need
$page -> setExtJavaScripts3(''); // might not need
$page -> setExtJavaScripts4(''); // might not need
$page -> setExtJavaScripts1('<script src="js/301a.js" type="text/javascript"></script>'); // might not need

//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '';

$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//

extract($_REQUEST);

$notify->Notify();

switch($index)
{
	case 'Add'  :
					echo "<h2>Add Category</h2>";
					if($go=="Go")
						$task->AddTaskCategory("server");
					else
						$task->AddTaskCategory("local");
					break;
	
	case 'Edit' :
					echo "<h2>Edit Category</h2>";
					if($go=="Save")
						$task->EditTask_Category("server",$cat_id);
					else
						$task->EditTask_Category("local",$cat_id);
					break;
	

	case 'Delete' :
					$task->DeleteCategory($cat_id);
					break;

	
	default :
					?>
					<div id='content_column_header'>
					<div class="form_main">
					<h2>Manage Category</h2><div style="text-align:right"><a href="<?php echo $_SERVER['PHP_SELF']?>?index=Add">Add New </a></div>
					</div>
					</div>
					
					<?php
					$task->ShowAllCategory();
}

// **********************Start code for Info Column ****************************//
$page -> displayPageBottom();
?>