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
$page -> setPageTitle("FILE");
$page -> setActiveButton('4');
//$page -> setInnerNav('');
//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('form.css');
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');


$page -> setImportCss4('css/JTip.css');
//$page -> setImportCss4('test.css');
$page -> setImportCss3('auto/style.css');
$page -> setExtJavaScripts1('<script language="javascript" SRC="js/jtip.js"></script>'); // might not need
$page -> setExtJavaScripts2('<script language="javascript" SRC="js/jquery.js"></script>'); // might not need
$page -> setExtJavaScripts4('<script src="auto/jquery.js" type="text/javascript" charset="utf-8"></script>');
$page -> setExtJavaScripts5('<script src="auto/jquery.fcbkcomplete.js" type="text/javascript" charset="utf-8"></script>');

$page -> setCustomJavaScripts('
var tlist2;
function initializeFacebook(){
        $(document).ready(function() 
        {        
          $("#select2").fcbkcomplete({
            json_url: "contactsjson.php",
            cache: false,
            filter_hide: true,
            //onremove: "testme",
			//onselect: "testme",
            filter_selected: true,
			maxitems: 10
          });		 
        });
		
		function testme(item)
	  	{	if ($.browser.mozilla) 
			{
				console.log(item);
			}
			else
			{
				alert(item);
			}		  	
	  	}    
}

');

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
<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>

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
<div id="content_column_header">
	<h2>Add a new document: </h2>
</div>

<div class="form_bg">
<div class="form_main">
<?
if($_POST['submit']=='add this file')
$filesobj->AddFile('server');
else
$filesobj->AddFile('local');
?>
</div>
</div>
<?php /*?><textarea style="width: 500px; height: 200px;"><? print_r($_SESSION); ?></textarea><?php */?>
<?

//******************************************************************************//
$page -> displayPageBottom();
?>
