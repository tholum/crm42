<?php
//ini_set('display_errors',1);
require('app_code/config.inc.php');

require 'class/class.ReportBuilder.php';

$page = new basic_page();

$page->auth->Checklogin();

$access_rule = array(	"Admin"		=>"Admin" 
					);
$page -> setAccessRules($access_rule);
$page -> setAccessRulesType('all');		// any or all
$page -> CheckAuthorization();


$report_builder = new ReprtBuilder();

$ajax = new PHPLiveX();

$ajax -> AjaxifyObjects(array("report_builder"));  

$notify = new Notification();

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle(MASTER_BROWSER_TITLE);
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
$page -> setExtJavaScripts7('<script type="text/javascript" src="table2csv/table2CSV.js"></script>'); 

$page -> setCustomJavaScripts('
');
//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '';

$page -> setPageStyle($page_style);

$page -> displayPageTop('full');

// **********************Start html for content column ****************************//
$notify->Notify();

$ajax->Run(); // Must be called inside the 'html' or 'body' tags   
?>
<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
<div id="content_column_header">Report Builder
	<div id="div_report_bulider_box" style="padding: 10px 0px 0px 30px;">
		<?php echo $report_builder->customizeReportBuilderEdit($_REQUEST[search_report_id]); ?>
	</div>
</div>
<div class="form_main" id="div_result_report_builder"></div>
<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
$page -> displayPageBottom();
?>