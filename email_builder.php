<?php
session_start();
//ini_set('display_errors',1);
require('class/config.inc.php');
require_once('app_code/config.inc.php');
require('class/class.EmailBuilder.php');
require('class/class_attachment.php');

$att= new Attachment();
$page = new basic_page();



/*******Checking Authentication****************/

$page->auth->Checklogin();

$notify = new Notification();

$e_builder= new EmailBuilder();
$ajax = new PHPLiveX();

$ajax -> AjaxifyObjects(array("e_builder"));  

/**********************************************/

/*******Setting Page access Rules & checking Authorization****************/


/************************************************************************/
					
$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("EMAIL BUILDER");
$page -> setActiveButton('7');
$page -> setInnerNav('');

$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php'); // each page should have it's own .css sheet.  Link mulitple sheets here and below

$page -> setImportCss3('css/news.css');
//$page -> setImportCss4('css/message.css');
$page -> SetDynamicCSS_3('css/message.php');
$page -> setImportCss4('auto/style.css');

$page -> setImportCss5('test.css');
//$page -> setImportCss6('welcome.css');
$page -> setExtJavaScripts1('<script type="text/javascript" src="autocomplete/jquery-1.2.6.min.js"></script>');
$page -> setExtJavaScripts2('<script type="text/javascript" src="autocomplete/jquery.jSuggest.1.0.js"></script>');
$page -> setExtJavaScripts3('<script src="protoculous-effects-shrinkvars.js" type="text/javascript" charset="utf-8"></script>'); // might not need
$page -> setExtJavaScripts4('<script src="textboxlist.js" type="text/javascript" charset="utf-8"></script>'); // might not need
$page -> setExtJavaScripts5('<script src="test.js" type="text/javascript" charset="utf-8"></script>'); // might not need
$page -> setExtJavaScripts6('<script src="tiny_mce/tiny_mce.js" type="text/javascript" charset="utf-8"></script>'); // might not need
$page -> setExtJavaScripts7('<script src="js/301a.js" type="text/javascript"></script>'); // might not need //
$page -> setExtJavaScripts8('<script type="text/javascript" src="src/js/jscal2.js"></script>'); // might not need //
$page -> setExtJavaScripts9('<script type="text/javascript" src="src/js/lang/en.js"></script>'); // might not need

$page -> setCustomJavaScripts('
	function getText(id){
  		return tinyMCE.get(id).getContent();
	}
	
	tinyMCE.init({
			mode : "textareas",
			theme : "advanced" ,
			theme_advanced_buttons1 : "bold,italic,underline,justifyleft,justifycenter,justifyright,justifyfull,undo,redo,link,unlink,forecolor",
			theme_advanced_buttons2 : "",
			theme_advanced_buttons3 : ""
	});
								
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


//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '#content_column {
    background: url("images/transparent_90.png") repeat scroll 0 0 transparent;
    border-bottom: 1px solid #999999;
    border-right: 1px solid #999999;
    float: left;
    width: 60%;
}

ul.holder{
background-color:#fff !important;
width: 100% !important;
}
.facebook-auto {
width: 200px !important;
}
';

$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//
$ajax->Run('phplivex.js'); // Must be called inside the 'html' or 'body' tags  

$notify->Notify();
?>
<div id="div_module" class="" style="display:none;"></div>
<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
<div id="content_column_header">
	Email Builder &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="<?php echo $_SERVER['PHP_SELF']; ?>" onClick="javascript:e_builder.SendMessage('',
																		   'local',
																		   '',
																		   'new',
																		   {onUpdate:
																				function(response,root){
																				document.getElementById('show_message').innerHTML=response;
																			}});">New Template</a>
	<div class="contact_form">
		<div class="profile_box1">
			<div id='show_message'>
			<?php echo $e_builder->SendMessage('','local','','new'); ?>
			</div>
		</div>
	</div>	
</div>
<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>

<div class="contact_form">
	<?php echo $e_builder->enterDetails(); ?>
</div>
<?php
$page -> displayPageBottom();
?>