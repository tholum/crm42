<?php

//ini_set('display_errors',1);
 
require_once('app_code/config.inc.php');
require_once('class/class.contacts.php');
require_once('class/class.project.php');
require_once('class/class.note.php');
require_once('class/class.tasks.php');
require_once('class/class.GlobalTask.php');
require_once('class/class.WorkOrder.php');
require_once('app_code/class.Event_Contacts.php');
require_once('app_code/zipcode.class.php');
require_once('class/class.group.php');


$page = new basic_page();

/*******Checking Authentication****************/
$page->auth->Checklogin();

$user = new User();

$notify = new Notification();
$global_task = new GlobalTask();
$group_display = new group();
$workorder = new WorkOrder();
$ajax = new PHPLiveX();

$ajax->AjaxifyObjects(array("group_display" ,"global_task","workorder"));  

/**********************************************/


/*******Setting Page access Rules & checking Authorization****************/

/*$access_rule = array(	"Admin"		=>"Admin" 
					);
$page -> setAccessRules($access_rule);
$page -> setAccessRulesType('all');		// any or all
$page -> CheckAuthorization();
*/
/************************************************************************/
					
$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("GROUP");
$page -> setActiveButton('3');
$page -> setInnerNav('');

//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('form.css'); // each page should have it's own .css sheet.  Link mulitple sheets here and below

$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');

$page -> setImportCss3('src/css/jscal2.css');
$page -> setImportCss4('src/css/border-radius.css');
//$page -> setImportCss5('src/css/win2k/win2k.css');
$page -> setImportCss6('tablesort/themes/blue/style.css');
$page -> setImportCss7('contact_profile.css');
$page -> setImportCss8('css/JTip.css');
$page -> setImportCss9('auto/style.css');



$page -> setExtJavaScripts1('<script src="js/301a.js" type="text/javascript"></script>'); // might not need //
$page -> setExtJavaScripts2('<script type="text/javascript" src="src/js/jscal2.js"></script>'); // might not need // 
$page -> setExtJavaScripts3('<script type="text/javascript" src="src/js/lang/en.js"></script>'); // might not need
$page -> setExtJavaScripts4('<script type="text/javascript" src="autocomplete/jquery-1.2.6.min.js"></script>');
$page -> setExtJavaScripts5('<script type="text/javascript" src="autocomplete/jquery.jSuggest.1.0.js"></script>');
$page -> setExtJavaScripts6('<script type="text/javascript" src="tablesort/jquery.tablesorter.js"></script>'); 
$page -> setExtJavaScripts7('<script type="text/javascript" src="tablesort/chili-1.8b.js"></script>');
$page -> setExtJavaScripts9('<script language="javascript" SRC="js/jtip.js"></script>'); 
$page -> setExtJavaScripts8('<script src="auto/jquery.fcbkcomplete.js" type="text/javascript" charset="utf-8"></script>');

//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page -> setCustomJavaScripts('
	$(function() {		
		$("#capacity_table")
		.tablesorter({widthFixed: true, widgets: ["zebra"], sortList: [[0,0]] } )
		
	});
	$(function() {		
		$("#search_table")
		.tablesorter({widthFixed: true, widgets: ["zebra"], sortList: [[0,0]], headers: {5:{sorter: false}} } )
		
	});



$(document).ready(function() {        
  $("#vendor").fcbkcomplete({
	json_url: "useslist.php?type=user",
	cache: false,
	filter_hide: true,
	filter_selected: true,
	maxitems: 10,
  });		 
});

function autosuggest(){
	$(document).ready(function() {        
	  $("#add_product").fcbkcomplete({
		json_url: "useslist.php?type=product",
		cache: false,
		filter_hide: true,
		filter_selected: true,
		maxitems: 1,
	  });		 
	});	
}


');	
$page_style = '
ul.holder{
background-color:#fff !important;
width: 100% !important;
}
.facebook-auto {
    width: 200px !important;
}

#content_column_header {
    font-weight: normal;
    line-height: 20px;
	color: white;
}
';

//$page_style = '';

$page -> setPageStyle($page_style);

$page -> displayPageTop('full');

$ajax->Run('phplivex.js'); // Must be called inside the 'html' or 'body' tags  
// **********************Start html for content column ****************************//

$notify->Notify();
?>
<div id="prl" style="display:none;"><img  id="prl_image" src="images/spinner.gif"  /></div>
<div id="content_column_header" style="background-color:#b2b2b2;">
	<h2>GROUP</h2>
    <div><?php echo $group_display->ShowGroup(); ?></div>
	<h3>clone Selected Product</h3>
</div>
<div id="task_area" style="float:left;width: 49%;"><?php echo $group_display->show_searchtable(); ?></div>
<div id="show_detail" style="float:right;background-color:#b2b2b2; margin-top:8px;width: 49%;" ></div>

<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>

<?php
$page -> displayPageBottom();
?>
