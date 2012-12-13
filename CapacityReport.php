<?php
session_start();
//ini_set('display_errors',1);
require_once('class/config.inc.php');
require_once('app_code/global.config.php');
require_once('class/global.config.php');
require 'class/class.tasks.php';
require_once('class/class.CapacityReport.php');
require_once('class/class.CapacityCalc.php');
require_once('class/class.Capacity.php');
require_once('class/class.CalcDate.php');

$page = new basic_page();

/*******Checking Authentication****************/

//$page->auth->Checklogin();

$user = new User();

$notify = new Notification();

$capacity = new CapacityReport();

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
$page -> setPageTitle("CAPACITY REPORT");
$page -> setActiveButton('3');
$page -> setInnerNav('');

//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('form.css'); // each page should have it's own .css sheet.  Link mulitple sheets here and below

$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');

$page -> setImportCss2('css/jgauge.css');  
$page -> setImportCss3('src/css/jscal2.css');
$page -> setImportCss4('src/css/border-radius.css');



$page -> setExtJavaScripts1('<script src="js/301a.js" type="text/javascript"></script>'); // might not need //
$page -> setExtJavaScripts2('<script type="text/javascript" src="src/js/jscal2.js"></script>'); // might not need // 
$page -> setExtJavaScripts3('<script type="text/javascript" src="src/js/lang/en.js"></script>'); // might not need
/*$page -> setExtJavaScripts4('<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>');
$page -> setExtJavaScripts5('<script src="js/jQueryRotate.min.js" type="text/javascript"></script>'); 
$page -> setExtJavaScripts6('<script type="text/javascript" src="js/jgauge-0.3.0.a3.js"></script>'); */


//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page -> setCustomJavaScripts('
	$(function() {		
		$("#search_tables")
		.tablesorter({ widthFixed: true, widgets: ["zebra"],  headers: {} })
	});
');	
$page_style = '


';

//$page_style = '';

$page -> setPageStyle($page_style);

$page -> displayPageTop("full");

$ajax->Run('phplivex.js'); // Must be called inside the 'html' or 'body' tags  
// **********************Start html for content column ****************************//

$notify->Notify();
?>
<div id="prl" style="display:none;"><img  id="prl_image" src="images/spinner.gif"  /></div>
<div id="content_column_header" style="background-color:#3F3F3F;">
    <?php echo $capacity->showDateTextbox(); ?>
</div>
	<div id="capacity">
	   <script>table();</script>
	</div>
	<div id="meter1">
		<?php 
			$start_date = date('Y-m-d');
			if(!$end_date) $end_date=date('Y-m-d',strtotime("+6 days",strtotime($start_date)));
	
			echo $capacity->searchDueDateData($start_date,$end_date,'week'); 
		?>
	</div>
	<div class="clear"></div>
			<script language="javascript" type="text/javascript" src="js/jquery-1.4.2.min.js"></script> <!-- jQuery JavaScript library. -->
			<script language="javascript" type="text/javascript" src="js/jQueryRotate.min.js"></script> <!-- jQueryRotate plugin used for needle movement. -->
			<script language="javascript" type="text/javascript" src="js/jgauge-0.3.0.a3.js"></script> <!-- jGauge JavaScript. -->	
<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>

<?php
$page -> displayPageBottom();
?>
