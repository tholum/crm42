<?php
//ini_set("display_errors",1);
require_once('app_code/global.config.php');
require_once('class/config.inc.php');
require_once('class/class.case_client.php');
require_once('class/class.flags.php');
require_once('class/class.GlobalTask.php');
require_once('class/class.smtp.php');
//require_once('class/class.display.php');
require_once('class/class.casecreation.php');

$page = new basic_page();

/*******Checking Authentication****************/

$page->auth->Checklogin();

$user = new User();

$notify = new Notification();

$casedash = new case_client();
$global_task = new GlobalTask();
$casecreation = new case_creation();
$flags = new Flags();
$smtp = new smtp();

$ajax = new PHPLiveX();

$ajax->AjaxifyObjects(array("casedash","global_task","flags","smtp","casecreation"));

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
$page -> setPageTitle("CASE DASH BOARD");
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
$page -> setImportCss10('css/email_client.css');



$page -> setExtJavaScripts1('<script src="js/301a.js" type="text/javascript"></script>'); // might not need //
$page -> setExtJavaScripts2('<script type="text/javascript" src="src/js/jscal2.js"></script>'); // might not need // 
$page -> setExtJavaScripts3('<script type="text/javascript" src="src/js/lang/en.js"></script>'); // might not need
$page -> setExtJavaScripts4('<script type="text/javascript" src="autocomplete/jquery-1.2.6.min.js"></script>');
$page -> setExtJavaScripts5('<script type="text/javascript" src="autocomplete/jquery.jSuggest.1.0.js"></script>');
$page -> setExtJavaScripts6('<script type="text/javascript" src="tablesort/jquery.tablesorter.js"></script>'); 
$page -> setExtJavaScripts7('<script type="text/javascript" src="tablesort/chili-1.8b.js"></script>');
$page -> setExtJavaScripts9('<script language="javascript" SRC="js/jtip.js"></script>'); 
$page -> setExtJavaScripts8('<script src="auto/jquery.fcbkcomplete.js" type="text/javascript" charset="utf-8"></script>');
$page -> setExtJavaScripts10('<script type="text/javascript" src="table2csv/table2CSV.js"></script>');

$page -> setExtJavaScripts11('<script src="tiny_mce/tiny_mce.js" type="text/javascript"></script>');
$page -> setExtJavaScripts12('<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>');

//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page -> setCustomJavaScripts('
	function getText(id){
  		return tinyMCE.get("message").getContent();
	}						
	document.observe("dom:loaded", function() {
			  // init
	  tlist2 = new FacebookList("facebook-demo", "facebook-auto");
	  
	  // fetch and feed
	  new Ajax.Request("Usersjson.php?to=user", {
		onSuccess: function(transport) {
			transport.responseText.evalJSON(true).each(function(t){tlist2.autoFeed(t)});
		}
	  });
	  
	  tlist3 = new FacebookList("facebook-demo3", "facebook-auto1");
	  
	  // fetch and feed
	  new Ajax.Request("Usersjson.php?to=group", {
		onSuccess: function(transport) {
			transport.responseText.evalJSON(true).each(function(t){tlist3.autoFeed(t)});
		}
	  });
	});  
	
	function setTextAtCursorPoint(message){
		var myField = tinyMCE.get("SMSBody");

	    if (document.selection) {
			 myField.focus();
			 sel = document.selection.createRange();
			 sel.text = message;
			 var startPos = myField.selectionStart;
			 alert(startPos);
		}
		else if (document.getSelection) {
			 tinyMCE.activeEditor.selection.setContent(message);
			 myField.focus();
		}	
	}
	
	


');	
$page_style = '
';

//$page_style = '';

$page -> setPageStyle($page_style);

$page -> displayPageTop('full');

$ajax->Run('phplivex.js'); // Must be called inside the 'html' or 'body' tags  
// **********************Start html for content column ****************************//

$notify->Notify();
?>
<div id="left_panel">
  <?php $casecreation->left_panel('',''); ?>
</div>
<div id="right_top_panel">
  <?php $casecreation->right_top_panel('',''); ?>
</div>
<div id="right_bottom_panel">
  <?php echo $casecreation->right_bottom_panel('',''); ?>
</div>
<div id="prl" style="display:none;"><img  id="prl_image" src="images/spinner.gif"  /></div>

<div id="content_column_header" style="background-color:#3F3F3F;">
   <?php echo $casedash->dispaly_search_panel(); ?>
</div>
<div id="casedashboard_display">
  <?php //echo $casedash->display_case_result(); ?>
</div>


<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>

<?php
$page -> displayPageBottom();
?>
