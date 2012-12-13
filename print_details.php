<?php
//ini_set("display_errors",1);

require_once('class/config.inc.php');
require_once('app_code/global.config.php');
require 'class/class.tasks.php';
require_once('class/class.PrintDetails.php');
$page = new basic_page();

/*******Checking Authentication****************/

$page->auth->Checklogin();

$user = new User();

$notify = new Notification();

$print = new PrintDetails();


$ajax = new PHPLiveX();

$ajax->AjaxifyObjects(array("print"));  

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
$page -> setPageTitle("PRINT");
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
		.tablesorter({widthFixed: true, widgets: ["zebra"], sortList: [[0,0]] });
		
	});

');	
$page_style = '
	ul.holder{
	background-color:#fff !important;
	width: 100% !important;
	}
	.facebook-auto {
		width: 200px !important;
	}
	.print_divs{ 
		margin: 20px 5px;
		background:url(images/background_opera.bmp) scroll;
		} 
';

//$page_stylse = '';

$page -> setPageStyle($page_style);

$page -> displayPageTop('full');

$ajax->Run('phplivex.js'); // Must be called inside the 'html' or 'body' tags  
// **********************Start html for content column ****************************//

$notify->Notify();
?>
<div id="prl" style="display:none;"><img  id="prl_image" src="images/spinner.gif"  /></div>

<div  style="float:left;width:40%;">
    <div class="print_divs" id="content_column_header" style="background:url(images/background_opera.bmp) scroll;">
        <h2>Print Search Screen</h2>
        <?php echo $print->search_screen(); ?>
    </div>
    <div class="print_divs">
        <h2>Summary of Orders</h2>
		<div id="print_details" style="overflow-y:auto; overflow-x:hidden; height:125px;">
		   <?php echo $print->order_summary(); ?>
        <!--<iframe src="iframe_print_details.php" height="100" width="354" frameborder="0"></iframe>-->
		</div>
    </div>
    <div class="print_divs" id="fabric_roll" >
        <h2>Fabric Rolls</h2>
        <div id="fabric_table" style="overflow-y:auto; overflow-x:hidden; height:175px;">
        	<?php echo $print->display_fabricrolls(); ?>
        </div>
		<div id="add_fabric" align="right">
		    <?php echo $print->addFabricRolls('rolls'); ?>
        </div>
    </div>
    <div class="print_divs">
        <h2>Groups</h2>
	  <div id="group_show" style="overflow-y: auto; height: 86px;">
        <?php echo $print->groups(); ?>
	  </div>
    </div>
	<div class="print_divs">
	  <h2>Printer</h2>
      <div id="printer" style="overflow-y:auto;overflow-x:hidden; height:150px;">
        <?php echo $print->printer(); ?>
      </div>
      <div id="add_printer" align="right">
	    <?php echo $print->addFabricRolls('printer'); ?>
        </div>
	</div>
	
	<!--<div>
	  <h2>Pro Path Scheduling</h2>
      <div id="propath" style="overflow-y:auto;overflow-x:hidden; height:450px;">
        <?php //echo $print->predictPathStatus(); ?>
      </div>
	</div>-->
	
  </div>
    <div id="content_column_header" style="float:right; width:55%;margin-top:20px;background:url(images/background_opera.bmp) scroll;">
		<h6>Work Orders</h6>
		<a href="javascript:void(0);" onclick="javascript:print.work_orders({preloader:'prl',target:'show_work_order'});">Refresh</a>
        <div id="show_work_order" style="overflow-y:auto; max-height:815px;">
	        <?php echo $print->work_orders(); ?>   
        </div>
    </div>
    <div style="clear:both;"></div>
</div>

<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>

<?php
$page -> displayPageBottom();
?>