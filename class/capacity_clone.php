<?php
//ini_set('display_errors',1);
require_once('class/config.inc.php');
require_once('class/global.config.php');
require 'class/class.tasks.php';
require_once('class/class.CapacityReport.php');
require_once("class/phpdial_gauge.php");

$page = new basic_page();

/*******Checking Authentication****************/

$page->auth->Checklogin();

$user = new User();

$notify = new Notification();

$capacity = new CapacityReport('11');

$my_gauge = new dial_gauge('11');

$ajax = new PHPLiveX();

$ajax->AjaxifyObjects(array("capacity"));  

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
$page -> setPageTitle(MASTER_BROWSER_TITLE);
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
	


');	
$page_style = '


';

//$page_style = '';

$page -> setPageStyle($page_style);

$page -> displayPageTop();

$ajax->Run('phplivex.js'); // Must be called inside the 'html' or 'body' tags  
// **********************Start html for content column ****************************//

$notify->Notify();
?>
<div id="prl" style="display:none;"><img  id="prl_image" src="images/spinner.gif"  /></div>
<div id="content_column_header" style="background-color:#3F3F3F;">
    
	  <?php echo $capacity->TreeFct(); ?>
	
</div>
<div id="capacity">
   <?php echo $capacity->Capacity(); ?>
</div>
<div id="final_capacity">
   <?php //echo $capacity->FinalCapacity(); 
   //$capacity->dial_gauge('11');
   ?>
</div>
<div id="capacity_meter" onclick="javascript:capacity.Capacity({target:'final_capacity'});">
<iframe src="show.php" style="border:none" width="130px"></iframe>
   <?php //header("Content-Type: image/gif"); 	// gif output
		 //$my_gauge->display_gif();
		 //$capacity->CapacityMeter(); ?>
</div>
<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>

<?php
$page -> displayPageBottom();
?>
