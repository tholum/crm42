<?php
session_start();
$_SESSION[inc] = 1;
$_SESSION[y] = 1;
$_SESSION[total_est_day] = 0;
$_SESSION[percentage] = 0;
//$_SESSION[count12]='';
//ini_set('display_errors',1);
require_once('class/config.inc.php');
require_once('app_code/config.inc.php');
require_once 'class/class.tasks.php';

require_once 'class/class.GlobalTask.php';
$page = new basic_page();

$page->auth->Checklogin();

$user = new User();

$notify = new Notification();

$global_task = new GlobalTask();

$ajax = new PHPLiveX();

$ajax -> AjaxifyObjects(array("global_task"));  

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
$page -> setPageTitle("FLOW CHART TASK");
$page -> setActiveButton('3');
$page -> setInnerNav('');
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');


$page -> setImportCss3('src/css/jscal2.css');
$page -> setImportCss4('src/css/border-radius.css');
$page -> setImportCss5('src/css/win2k/win2k.css');
$page -> setImportCss6('tablesort/themes/blue/style.css');
$page -> setImportCss7('flowchart/jquery-ui-1.8.14.custom/css/ui-lightness/jquery-ui-1.8.14.custom.css');
//$page -> setImportCss8('flowchart/css/jsplumb.css');
//$page -> setImportCss9('flowchart/css/jsplumb1.css');
$page -> setImportCss10('flowchart/css/flowchartDemo.css');
$page -> setImportCss11('flowchart/css/jsPlumbDemo.css');
$page -> setImportCss11('jquery.iviewer-0.4.3/jquery.iviewer.css');

//$page -> setExtJavaScripts('<script type="text/javascript" src="flowchart/excanvas_r3/excanvas.js"></script>'); // might not need
//$page -> setExtJavaScripts('<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>'); 
//$page -> setExtJavaScripts('<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>'); 
//$page -> setExtJavaScripts('<script type="text/javascript" src="flowchart/jquery-ui-1.8.14.custom/js/jquery.jsPlumb-1.3.1-all-min.js"></script>');
//$page -> setExtJavaScripts('<script type="text/javascript" src="flowchart/jquery-ui-1.8.14.custom/js/demo-helper-jquery.js"></script>');
//$page -> setExtJavaScripts('<script type="text/javascript" src="flowchart/jquery-ui-1.8.14.custom/js/draggableConnectorsDemo-jquery.js"></script>');
//$page -> setExtJavaScripts('<script type="text/javascript" src="jquery.iviewer-0.4.3/test/jquery.mousewheel.min.js"></script>');
//$page -> setExtJavaScripts('<script type="text/javascript" src="jquery.iviewer-0.4.3/jquery.iviewer.js"></script>');
//$page -> setExtJavaScripts('<script type="text/javascript" src="js/slimcrm.js"></script>');
//$page -> setExtJavaScripts('<script type="text/javascript" src="js/jquery.ctl.addons.js"></script>');
//
$page -> setExtJavaScripts('<script src="js/301a.js" type="text/javascript"></script>'); // might not need //
//$page -> setExtJavaScripts('<script type="text/javascript" src="src/js/jscal2.js"></script>'); // might not need // 
//$page -> setExtJavaScripts('<script type="text/javascript" src="src/js/lang/en.js"></script>'); // might not need kb_object
$page -> setExtJavaScripts('<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>');
$page -> setExtJavaScripts('<script type="text/javascript" src="js/jquery-ui-1.8.17.custom.min.js"></script>');
$page -> setExtJavaScripts('<script src="js/jquery.colorpicker.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script src="js/jquery.ui.colorpicker-en.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script src="js/jquery.dd.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script src="js/jquery.ctl.sidetab.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script src="js/jquery.ctl.addons.js" type="text/javascript"></script>');
$page -> setExtJavaScripts('<script src="js/jquery.upload.js" type="text/javascript"></script>');
//jquery.qtip.min

$page -> setExtJavaScripts('<script type="text/javascript" src="flowchart/jquery-ui-1.8.14.custom/js/jquery.jsPlumb-1.3.1-all-min.js"></script>');
$page -> setExtJavaScripts('<script type="text/javascript" src="flowchart/jquery-ui-1.8.14.custom/js/demo-helper-jquery.js"></script>');
$page -> setExtJavaScripts('<script type="text/javascript" src="flowchart/jquery-ui-1.8.14.custom/js/draggableConnectorsDemo-jquery.js"></script>');
$page -> setExtJavaScripts('<script type="text/javascript" src="jquery.iviewer-0.4.3/test/jquery.mousewheel.min.js"></script>');
$page -> setExtJavaScripts('<script type="text/javascript" src="jquery.iviewer-0.4.3/jquery.iviewer.js"></script>');

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
$page -> setExtJavaScripts('<script src="tiny_mce/tiny_mce.ctl.addons.js" type="text/javascript"></script>');

//*********************Page Style *******************************//

// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page -> setCustomJavaScripts('
	

			
');	
$page_style = '
	ul.holder{
	background-color:#fff !important;
	width: 100% !important;
	}
	.facebook-auto {
		width: 200px !important;
	}
';

//$page_style = '';

$page -> setPageStyle($page_style);

$page -> displayPageTop('full');

$ajax->Run('phplivex.js'); // Must be called inside the 'html' or 'body' tags  
// **********************Start html for content column ****************************//

$notify->Notify();
?>
<script>
    
  $(document).ready( function(){
//      $("#taskwindow").draggable();
//      $(window).scroll( function(){ 
//          $("#mainToolBar").animate({"top" : $(window).scrollTop() + "px" } , 350);
////          var WindowBottom;
////          WindowBottom = $(window).scrollTop() + $(window).height();
//          TWBottom = $("#taskwindow").position().top + $("#taskwindow").height();
//
//          
//          if( WindowBottom < TWBottom ){
//              var NewHight = WindowBottom - $("#taskwindow").height();
//              $("#taskwindow").animate({"top" : NewHight + "px" } , 350);
//          }
//          if( $("#taskwindow").position().top < $(window).scrollTop() ){
//              //$("#taskwindow").css("top", $(window).scrollTop() + "px");
//              $("#taskwindow").animate({"top" : $(window).scrollTop() + "px" } , 350);
//          }
//          
//                    
//      });
  } );
function SavePositions(){
	$("#task_display").children().each( function(){
           // alert($(this).attr('id'));
            var tmpid = $(this).attr('id');
            if( tmpid.substring(0 , 6) == 'window' ){
                //$("#helper").html( $("#helper").html() + "<br>" + $(this).attr('id') + " - "  + $(this).position().left + "ID:" + $(this).attr('id').substring( 6 )  );
                global_task.saveMGTPosition( $(this).attr('id').substring( 6 ) , $(this).position().top , $(this).position().left , {});
                
            }
            if( tmpid.substring(0 , 6) == 'status' ){
               // $("#helper").html( $("#helper").html() + "<br>" + $(this).attr('id') + " - "  + $(this).position().left + "ID:" + $(this).attr('id').substring( 6 )  );
                global_task.saveMGTSPosition( $(this).attr('id').substring( 6 ) , $(this).position().top , $(this).position().left , {});
                
            }
            if( tmpid.substring(0 , 6) == 'system' ){
                //$("#helper").html( $("#helper").html() + "<br>" + $(this).attr('id') + " - "  + $(this).position().left + "ID:" + $(this).attr('id').substring( 6 )  );
                global_task.saveSystemTaskPosition( $(this).attr('id').substring( 6 ) , $(this).position().top , $(this).position().left , {});
                
            }
    });
    function toggle_arrow(){
            if( $("#toolbar_arrow_button").css("background") == "url(images/down.png)"){
            $("#" + session_id + "\\:MINMAX").css("background" , "url(images/up.png')");
        } else {
            $("#" + session_id + "\\:MINMAX").css("background" , "url('images/down.png')");
        }
    }

}
    function testAdd(){
        $('#display_window').html( $('#display_window').html() + '<div class="window" id="demoWindow"  style="background-color: white;left: 500px; top: 50px">Test</div>');
    }
</script>
<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>


<style>
		.viewer
		{
			width: 50%;
			height: 520px;
			border: 1px solid black;
			position: relative;
		}
		.wrapper
		{
			overflow: hidden;
		}
</style>
<div id="mainToolBar" style="position: fixed; top: 0px; left: 50%;">
<div id="toggleToolBar" style="position: relative; top: 0px; left: -240px;z-index: 10" >
<div style="background: #729bc7;width: 480px;" id="toolbar">
    <div id="task_tree" style="width: 100%"><?php echo $global_task->task_tree($_POST[task_tre]); ?><a onclick="testAdd()" >Test Add</a></div>
</div>
    
        <div onclick="$('#toolbar').toggle();toggle_arrow();" style="background: url(images/silver3.jpeg); width: 480px; height: 8px;" ><div style="background: url(images/down.png);width: 25px; height: 8px; position: relative;left: 230px;" id="toolbar_arrow_button" >&nbsp;</div></div>
</div>
    </div>

 <div id="display_window" style="background-image:url(images/background.bmp); width:100%; height:300%; position: absolute; top: 0px; left: 0px; z-index: 0;">
    <?php
	if($_POST[ok]=='ok'){
	
	
	    //$global_task->count_win($_POST[task_tre],$_POST[task_tre],1);
		echo $global_task->display_task($_POST[task_tre]);
		
		//echo $_POST[task_tre];
	}
	?>
  </div>

<!--  <div id="taskwindow" style="float:right; background-image:url(images/background_opera.bmp); width:430px; height:628px; z-index: 1000000000; position:relative;">-->
      <div id="taskwindow" style="width:430px; height: 100%; z-index: 11; position:fixed;top: 0px; right: 0px; background: #729bc7;overflow: scroll;">
     <div id="div_task_list">
		<?php 
		if($_POST[task_tre]){
		echo $global_task->task_preview($_POST[task_tre]);
		
		}?>
		<?php //echo $_POST[task_tre]; echo $global_task->task_info(); ?>
	 </div>
     
  </div>


<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
$page -> displayPageBottom();
?>
