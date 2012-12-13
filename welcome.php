<?php
error_reporting(E_ERROR);
ini_set("display_errors",0);
//echo __LINE__ . "\n";
if(!file_exists('class/global.config.php')){
    header("Location: install/");
}
ob_start();
require_once('app_code/global.config.php');
require_once('class/config.inc.php');
require_once('class/class.email_client.php');
require_once('class/class.flags.php');
require_once('class/class.GlobalTask.php');
require_once('class/class.smtp.php');
require_once('class/class.tasks.php');
require_once('class/class.display.php');
require_once('class/class.casecreation.php');
require_once('class/class.dynamicpage.php');
require_once('class/class.contacts.php');
require_once('class/class.FctSearchScreen.php');
require_once('class/class.eapi_order.php');
require_once('class/class.eapi_account.php');
require_once('class/class.cases.php');
require_once('class/class.note.php');
require_once('class/class.knowledgebase.php');
require_once('class/class.welcome.php');
require_once('app_code/config.inc.php');
require_once( 'class/class.contacts.php');
require_once('app_code/zipcode.class.php');
require_once('app_code/class.Event_Contacts.php');
require_once('class/class.local_calendar.php');
require_once('class/class.twitter.php');
require_once('class/class.workspace.php');
ob_end_clean();
//echo __LINE__ . "\n";
$twitter = new twitter();
$workspace = new workspace();
$dynamic_page = new dynamic_page();
$page = new basic_page();
$fctsearch = new FctSearchScreen();
$welcome = new welcome();
$contact = new Company_Global();
/*******Checking Authentication****************/
//echo __LINE__ . "\n";
$page->auth->Checklogin();
$calendar = new calendar();
$user = new User();
//echo __LINE__ . "\n";
$notify = new Notification();
$task=new tasks();
$emaildash = new email_client();
$global_task = new GlobalTask();
$flags = new Flags();
$smtp = new smtp();
$note = new Note;
$knowledgebase = new knowledge_base( $page );
$em = new Event_Contacts();
/*
 * CTLTODO: We need to fix this so we use only $casecreation or $case_creation
 * 
 */
$casecreation = new case_creation( $page );
$case_creation = $casecreation;
$eapi_order = new eapi_order;
$eapi_account = new eapi_account;
$ajax = new PHPLiveX();
$cases = new cases;
//echo __LINE__ . "\n";

$ajax->AjaxifyObjects(array( "workspace" , "twitter" , "calendar", "task", "em" , "contact", "welcome", "user" , "emaildash","global_task","flags","smtp","case_creation","casecreation", "dynamic_page","fctsearch","eapi_order","cases","page","eapi_account","note","knowledgebase"));
//echo __LINE__ . "\n";
$default_page = 'workspace';
/**********************************************/
//echo __LINE__ . "\n";

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
$page -> setPageTitle("CTL - SLIMCRM");
$page -> setActiveButton('3');
$page -> setInnerNav('');

$page->setImportCss1('css/workspace.css');
$page->setImportCss2('css/twitter.css');
//$page -> setImportCss2('form.css'); // each page should have it's own .css sheet.  Link mulitple sheets here and below

//$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');

$page -> setImportCss3('fullcalendar/fullcalendar.css');
$page -> setImportCss4('src/css/border-radius.css');
$page -> setImportCss5('src/css/win2k/win2k.css');
$page -> setImportCss('tablesort/themes/blue/style.css');
$page -> setImportCss('css/jquery.colorpicker.css');
$page -> setImportCss('main.css');
$page -> setImportCss7('contact_profile.css');
$page -> setImportCss8('css/JTip.css');
$page -> setImportCss9('auto/style.css');
$page -> setImportCss10('css/email_client.css');
//$page -> setExtJavaScripts('<script src="js/sprockets.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script src="js/php.full.min.js" type="text/javascript"></script>');

//jquery.ctl.addons.js
$page -> setExtJavaScripts('<script src="js/301a.js" type="text/javascript"></script>'); // might not need //
//$page -> setExtJavaScripts('<script type="text/javascript" src="src/js/jscal2.js"></script>'); // might not need // 
//$page -> setExtJavaScripts('<script type="text/javascript" src="src/js/lang/en.js"></script>'); // might not need kb_object
$page -> setExtJavaScripts('<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>');
$page -> setExtJavaScripts('<script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>');
$page -> setExtJavaScripts('<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/backbone.js/0.9.2/backbone-min.jss"></script>');
$page -> setExtJavaScripts('<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.4.2/underscore-min.js"></script>');
$page -> setExtJavaScripts('<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/1.7.2/moment.min.js"></script>');
////
$page -> setExtJavaScripts('<script src="js/jquery.colorpicker.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script src="js/jquery.ui.colorpicker-en.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script src="js/jquery.dd.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script src="js/jquery.ctl.sidetab.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script src="js/jquery.ctl.addons.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script src="js/jquery.upload.js" type="text/javascript"></script>');
//jquery.qtip.min
$page -> setExtJavaScripts('<script src="js/jquery.qtip.min.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script src="js/themeswitchertool.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script type="text/javascript" src="js/jquery.printPage.js"></script>');
$page -> setExtJavaScripts('<script src="js/email_client.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script src="js/slimcrm.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script src="js/kb_object.js" type="text/javascript"></script>');
//$page -> setExtJavaScripts5('<script type="text/javascript" src="upload_attachment/jquery-1.4.2.min.js"></script>');
$page -> setExtJavaScripts('<script type="text/javascript" src="upload_attachment/swfobject.js"></script>');
$page -> setExtJavaScripts('<script type="text/javascript" src="upload_attachment/jquery.uploadify.v2.1.4.min.js"></script>');
$page -> setExtJavaScripts('<script type="text/javascript" src="autocomplete/jquery.jSuggest.1.0.js"></script>');
$page -> setExtJavaScripts('<script type="text/javascript" src="tablesort/jquery.tablesorter.js"></script>');
$page -> setExtJavaScripts('<script type="text/javascript" src="tablesort/jquery.tablesorter.pager.js"></script>'); 
$page -> setExtJavaScripts('<script type="text/javascript" src="tablesort/chili-1.8b.js"></script>');
$page -> setExtJavaScripts('<script language="javascript" SRC="js/jtip.js"></script>'); 
$page -> setExtJavaScripts('<script src="autocomplete/jquery.fcbkcomplete.js" type="text/javascript" charset="utf-8"></script>');
$page -> setExtJavaScripts('<script type="text/javascript" src="table2csv/table2CSV.js"></script>');
$page -> setExtJavaScripts('<script src="tiny_mce/tiny_mce.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script src="js/jquery-ui-timepicker-addon.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script src="fullcalendar/fullcalendar.min.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script src="js/timetracker.js" type="text/javascript"></script>');
//


/*$page -> setExtJavaScripts12('<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>');*/

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

	function autocomplete_customer_name(){	
		$(document).ready(function() {        
			$("#casecreation_customer_creat").fcbkcomplete({
			json_url: "useslist.php?type=user",
			cache: false,
			filter_hide: true,
			filter_selected: true,
			maxitems: 1,
		  });		 
		});
	}
     
		          		  
');	
$page_style = '
';
$page -> setCustomJavaScripts('

');

//$page_style = '';

$page -> setPageStyle($page_style);

$page -> displayPageTop('full');
$ajax->Run('phplivex.js'); 
// Must be called inside the 'html' or 'body' tags  
// **********************Start html for content column ****************************//

//$notify->Notify();
?>
<div id="left_panel">
  <?php $casecreation->left_panel(); ?>
</div>
<div id="right_top_panel">
  <?php $casecreation->right_top_panel(); ?>
</div>
<div id="right_bottom_panel">
  <?php echo $casecreation->right_bottom_panel(); ?>
</div>
<div id="prl" style="display:none;"><img  id="prl_image" src="images/spinner.gif"  /></div>

<div  class="main_area" ><div id="dynamic_main" class="inner_main" >&nbsp;</div></div>
<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>

<script>


    $(function() {
        slimcrm.cases.update_case_info();
    window.onbeforeunload= function(){
        return 'You are about to leave this page, OK?';
    };
    $(window).resize(slimcrm.screen_resize);
    $('#tab_left_panel').click();
<?php
    //echo ' dynamic_page.phplivex_page(\'' . $default_page . '\' , { target: \'dynamic_main\' ' . $dynamic_page->phplivex_options($default_page) .  ' });';  
    echo $page->page_link($default_page);
?>
});
            slimcrm.setup_tinyMCE();
       
        email_client.start_tick();
        slimcrm.tick.start();
//        $('a[title]:not .qtip').qtip({ style: { name: 'cream', tip: true } }).addClass('qtip');
        <?php if( $page->auth->isAdmin() == true ){ ?>
        slimcrm.script_console=false;
        $(document).click( slimcrm.onclick );
        $('html').keypress( 
            function(event){
                slimcrm.onkeypress(event);
                if( ( event.altKey == false && event.ctrlKey == true && event.charCode == '19') || (  event.altKey == false && event.ctrlKey == true && event.charCode == '83' && event.shiftKey == true )){
                    if( slimcrm.script_console == false){
                        slimcrm.script_console = true;
                        $('body').append('<div id="script_console" style="background:black;width: 100%; height: 40px;position:fixed;bottom: 0px;right:0px;color: white;z-index: 10000000;">CTL javascript cmd<br/><input style="background: #222222;color: white;width: 95%;" id="script_to_run" onkeypress="" ><a onclick="eval($(\'#script_to_run\').val());$(\'#script_to_run\').val(\'\');$(\'#script_to_run\').focus();" id="script_console_go" ><button style="width: 4%;">GO</button></a></div>                ');
                        $('#script_to_run').keypress(function(evt){
                            if( evt.charCode=='0'){
                                $('#script_console_go').click();
                            }
                        });
                        $('#script_to_run').focus();
                    } else {
                        slimcrm.script_console=false;
                        $('#script_console').remove();
                    }
                   } else {
                        slimcrm.evt = event;
                   } 
            }
          
        )
        <?php } ?>

</script>
<div style="display: none;" ><button class="no_focus"></button></div>
<?php
//Load all the templates
        if ($handle = opendir('templates/')) {
            $options = array();
            while (false !== ($entry = readdir($handle))) {
                $file_ob = explode("." , $entry);                
                if( count( $file_ob) == 2 ){                    
                   // $options[] = $entry . ":" . count($file_ob);
                    if( $file_ob[1] == "tpl"){
                        $options[] = $file_ob;
                    }
                }
            }
            closedir($handle);
        }
        foreach( $options as $n => $v){
            ?>
            <script type="text/templates" id="template_<?php echo $v[0]; ?>" ><?php include('templates/' . $v[0] . ".tpl"); ?></script>
            <script type="text/javascript" >$(function(){ slimcrm.templates.<?php echo $v[0]; ?> = _.template( $('#template_<?php echo $v[0]; ?>').html() ); });</script>
            <?php
        }
        $user_result = $page->db->query("SELECT user_id , first_name , last_name , image FROM tbl_user");
        $user_data = array();
        while( $row = $page->db->fetch_assoc($user_result)){
            if( $row['image'] == '' ){
                $row['image'] = 'images/default.jpg';
            }
            $user_data[$row['user_id']] = $row;
        }
        ?>
        <script type="text/javascript" >
            slimcrm.user_list = <?php echo json_encode( $user_data ); ?>;
        </script><?php
        
         $contacts_result = $page->db->query("SELECT contact_id , first_name , last_name , company , company_name , type FROM contacts");
        $contacts_data = array();
        while( $row = $page->db->fetch_assoc($contacts_result)){
            $contacts_data[$row['contact_id']] = $row;
        }
        ?>
        <script type="text/javascript" >
            slimcrm.contacts_list = <?php echo json_encode( $contacts_data ); ?>;
        </script><?php

$page -> displayPageBottom();
?>
