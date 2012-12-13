<?php
//ini_set('display_errors',1);
require_once('app_code/config.inc.php');
require_once('class/class.contacts.php');
require_once( 'class/class.tasks.php' );
require_once('class/class.project.php');

$page = new basic_page();
$page->auth->Checklogin();
$notify= new Notification();
$ajax=new PHPLiveX();
$project=new Project();

$ajax->AjaxifyObjects(array("project"));  


$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("PROJECT");
$page -> setActiveButton('9');
//$page -> setInnerNav('');
//$page -> SetDynamicCSS_1('main_style.css');
//$page -> SetDynamicCSS_2('form.css');
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');


$page -> setImportCss3('css/all.css');
$page -> setImportCss4('src/css/jscal2.css');
$page -> setImportCss5('src/css/border-radius.css');
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
$page -> setExtJavaScripts8('<script src="auto/jquery.fcbkcomplete.js" type="text/javascript" charset="utf-8"></script>');

$page -> setExtJavaScripts9('<script language="javascript" SRC="js/jtip.js"></script>'); // might not need
$page -> setExtJavaScripts10('<script src="auto/jquery.fcbkcomplete.js" type="text/javascript" charset="utf-8"></script>');


//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '
form ul.holder {
    width: 99% !important;
	}
#lightbox { margin-top: 50px; !important }';
$page -> setCustomJavaScripts('

	$(function() {		
		$("#search_table")
		.tablesorter({widthFixed: true, widgets: ["zebra"], sortList: [[0,0]], headers: { 2:{sorter: false}, 8:{sorter: false} } } )
		
	});
$(function(){
	
	
	$("#search").jSuggest({
		url: "search.php",
		data: "pattern",
		autoChange: false,
		minchar: 1,
		delay: 0,
		type: "GET"
		
	});
});

var tlist2;

function initializeFacebook(){
        $(document).ready(function() {        
		 $("#contact_id").fcbkcomplete({
			json_url: "contactlist.php",
			cache: false,
			filter_hide: true,
			filter_selected: true,
			maxitems: 10
		  });		  
        });		

}
initializeFacebook();

$(document).ready(function() {        
  $("#contacts").fcbkcomplete({
	json_url: "contactlist.php",
	cache: false,
	filter_hide: true,
	filter_selected: true,
	maxitems: 10,
  });		 
});
');


$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//
$notify->Notify();
$ajax->Run('js/phplivex.js'); // Must be called inside the 'html' or 'body' tags  
?>
<div id="div_project"   class="" style="display:none;"></div>
<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>


<div id="content_column_header">Projects </div>

<div class="form_main">
<div class="profile_box1">
	<div style="float:left"><h2>Project List</h2></div>
	<div style="float:right">
			<a href="#" onClick="project.clearCrieria({preloader:'prl'});">Clear</a>
			</div>
	<div style="clear:both"></div>
</div>
<div id="searchboxProject"><?php echo $project->searchboxProject(); ?></div>
<div id="div_project_list"><?php echo $project->projectList('','','','','','','','','yes','','no','default'); ?></div>
</div>


<?php
// **********************Closes the Content Column and begins Info Column ****************************//

$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>
<a  href="#"
 onclick="javascript: project.addEditProject('local','','','div_project',
					{ onUpdate: function(response,root){
							 document.getElementById('div_project').innerHTML=response;
		  				 	 document.getElementById('div_project').style.display='';		
							 start_cal();
							 start_cal1();
							 initializeFacebook();
							 }, preloader: 'prl'
						} ); return false;" ><img src="images/add_project.jpg" alt="Add Project" />
</a><br />		
<?php
$page -> displayPageBottom();
?>
