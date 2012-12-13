<?php
//ini_set('display_errors',1);
require_once('class/config.inc.php');
require 'class/class.tasks.php';
require_once('class/class.InventoryVendor.php');
//require_once('class/class.InventoryVendor_2.php');
$page = new basic_page();

/*******Checking Authentication****************/

$page->auth->Checklogin();

$user = new User();

$notify = new Notification();

$inventory = new InventoryVendor();
$ajax = new PHPLiveX();

$ajax->AjaxifyObjects(array("inventory"));  

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
$page -> setPageTitle("INVENTORY");
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
		$("#search_table")
		.tablesorter({widthFixed: true, widgets: ["zebra"], sortList: [[0,0]], headers: { 9:{sorter: false} } } )
		
	});
	
	$(document).ready(function() {        
  $("#vendor").fcbkcomplete({
	json_url: "vendorlist.php",
	cache: false,
	filter_hide: true,
	filter_selected: true,
	maxitems: 1,
  });		 
});

function autosuggest(){
	$(document).ready(function() {        
	  $("#vendor_id").fcbkcomplete({
		json_url: "vendorlist.php",
		cache: false,
		filter_hide: true,
		filter_selected: true,
		maxitems: 1,
	  });		 
	});
 }
function autosuggest1(){
	$(document).ready(function() {        
	  $("#vendor_idd").fcbkcomplete({
		json_url: "vendorlist.php",
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
width: 90% !important;
}
.facebook-auto {
    width: 200px !important;
}

#content_column_header {
    background-color: #C0CAD6;
}
element.style {
    background-color: #C0CAD6;
}

';

//$page_style = '';

$page -> setPageStyle($page_style);

$page -> displayPageTop();

$ajax->Run('phplivex.js'); // Must be called inside the 'html' or 'body' tags  
// **********************Start html for content column ****************************//

$notify->Notify();
?>
<div id="prl" style="display:none;"><img  id="prl_image" src="images/spinner.gif"  /></div>
<div id="content_column_header" style="background-color:#EEEEEE;">
	<h2>Inventory Manager</h2>
    <div id="content_column_header" style="background-color:#EEEEEE;">
	   <?php echo $inventory->InVeDetails(); ?></div>
    </div>
<div class="form_main">
<div id="task_area" style="float:left; overflow-y:scroll; overflow-x:hidden; height:690px; width:650px;">
  <?php echo $inventory->show_searchInventoryVendor(); ?></div>
</div>


<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>
<div class="form_main">
	<div>
		<a href="javascript:void(0);" onclick="javascript:inventory.add_InVeDetails('local',
														 {onUpdate: function(response,root){
														  document.getElementById('show_value').innerHTML=response;
														  document.getElementById('show_value').style.display='';		
		                                                  autosuggest();}, preloader: 'prl'} );" >Add Product</a>
	</div>

<?php /*?>	<div><a href="GroupManagement.php"><img src="images/manage_groups.png" border="0" /></a></div>
	<div><a href="report_builder.php"><img src="images/report_builder.png" alt="Report Builder" border="0" /></a></div>
<?php */?>    
    <div id="show_value" style="background-color:#EEEEEE; "></div>
	
<?php
if($_REQUEST['add_this']=='Add'){
 echo $inventory->add_InVeDetails('server');
}
?>
<?php
if($_REQUEST['clone_this']=='Clone'){
 echo $inventory->add_clone('server','');
}
?>
     
</div>
<?php
$page -> displayPageBottom();
?>
