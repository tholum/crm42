<?php
require('class/config.inc.php');
require_once('class/class.file.php');
$page = new basic_page();

/*******Checking Authentication****************/

$page->auth->Checklogin();

$file = new File();

$notify = new Notification();

/**********************************************/


					
$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("FILE MANAGAMENT");
$page -> setActiveButton('4');
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
$page -> setExtJavaScripts1(''); // might not need

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
	case 'Add'  :?>
	
	<div id='content_column_header'>
		<div class="form_main"><h2>Add Category</h2>
		</div>
		</div>
		<div class="file_m_padding">
		<?php
					if($go=="Add Category")
						$file->AddFileCategory("server");
					else
						$file->AddFileCategory("local");
					break;?>
			</div>
	<?php
	
	case 'Edit' :?>
	<div id='content_column_header'>
		<div class="form_main"><h2>Edit Category</h2>
		</div></div>
		<div class="file_m_padding">
		<?php
					if($go=="Go")
						$file->EditFileCategory("server",$file_category_id);
					else
						$file->EditFileCategory("local",$file_category_id);
					break;?>
		</div>
		<?php
	

	case 'Delete' :
					$file->DeleteCategory($file_category_id);
					break;

	
	default :?>
					<div id='content_column_header'>
					<div class="form_main"><h2>Manage File Category</h2>
					<div align="right"><a href="<?php echo $_SERVER['PHP_SELF']?>?index=Add">Add New </a></div>
					</div>
					</div>
					<div class="file_m_padding">
					<?php
					$file->ShowAllCategory();?>
				</div>
				<?php
}

// **********************Start code for Info Column ****************************//
$page -> displayPageBottom();
?>