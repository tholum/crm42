<?php
//ini_set('display_errors',1);
require_once('app_code/config.inc.php');
require_once('class/class.tasks.php');
require_once('class/class.contacts.php');
require_once('app_code/zipcode.class.php');
require_once('app_code/class.Event_Contacts.php');
require_once('class/class.ProjectNew.php');


if( PHONE_SYSTEM == "asterisk"){
    require_once('class/class.asterisk.php');
    $asterisk = new Asterisk;
}

$page = new basic_page();
$page->auth->Checklogin();
$notify= new Notification();

$ajax=new PHPLiveX();

$contact=new Company_Global();
$em = new Event_Contacts();
$task = new Tasks();
$user = new User();
$project_new = new ProjectNew();

$task -> SetUserObject($user);

$task -> SetUserID($page->auth->Get_user_id());

$ajax->AjaxifyObjects(array("project_new"));  

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle(MASTER_BROWSER_TITLE);
$page -> setActiveButton('2');
//$page -> setInnerNav('');
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');

$page -> setImportCss3('src/css/jscal2.css');
$page -> setImportCss4('src/css/border-radius.css');
$page -> setImportCss5('tablesort/themes/blue/style.css');
$page -> setImportCss6('contact_profile.css');
$page -> setImportCss7('css/JTip.css');
$page -> setImportCss8('auto/style.css');
$page -> setImportCss10('css/ddaccordion.css');

$page -> setExtJavaScripts1('<script src="js/301a.js" type="text/javascript"></script>'); // might not need //
$page -> setExtJavaScripts2('<script type="text/javascript" src="src/js/jscal2.js"></script>'); // might not need // 
$page -> setExtJavaScripts3('<script type="text/javascript" src="src/js/lang/en.js"></script>'); // might not need
$page -> setExtJavaScripts4('<script type="text/javascript" src="autocomplete/jquery-1.2.6.min.js"></script>');
$page -> setExtJavaScripts5('<script type="text/javascript" src="autocomplete/jquery.jSuggest.1.0.js"></script>');
$page -> setExtJavaScripts6('<script type="text/javascript" src="tablesort/jquery.tablesorter.js"></script>');
$page -> setExtJavaScripts7('<script type="text/javascript" src="tablesort/chili-1.8b.js"></script>');
$page -> setExtJavaScripts8('<script language="javascript" SRC="js/jtip.js"></script>'); 
$page -> setExtJavaScripts9('<script src="auto/jquery.fcbkcomplete.js" type="text/javascript" charset="utf-8"></script>');
$page -> setExtJavaScripts10('<script src="js/ddaccordion.js" type="text/javascript"></script>');


//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page -> setCustomJavaScripts('
	$(function() {		
		$("#display_search")
		.tablesorter({widthFixed: true, widgets: ["zebra"], sortList: [[0,0]], headers: {} } )
		
	});

function autosuggest1(){
$(document).ready(function() {        
  $("#cust_id").fcbkcomplete({
	json_url: "vendorlist.php?type=customer",
	cache: false,
	filter_hide: true,
	filter_selected: true,
	maxitems: 1,
  });		 
});
}

function autosuggest(){
	$(document).ready(function() {        
	  $("#u_id").fcbkcomplete({
		json_url: "useslist.php?type=project",
		cache: false,
		filter_hide: true,
		filter_selected: false,
		maxitems: 1,
	  });		 
	});
 }

ddaccordion.init({
	headerclass: "submenuheader",
	contentclass: "submenu", 
	revealtype: "click", 
	mouseoverdelay: 200, 
	collapseprev: true, 
	defaultexpanded: [], 
	onemustopen: false, 
	animatedefault: false, 
	persiststate: true, 
	toggleclass: ["", ""],
	togglehtml: ["suffix", "<img src=images/plus.gif class=statusicon />", "<img src=images/minus.gif class=statusicon />"], 
	animatespeed: "fast", 
	oninit:function(headers, expandedindices){ 		
	},
	onopenclose:function(header, index, state, isuseractivated){ 		
	}
})




');	

$page_style = '
ul.holder{
background-color:#fff !important;
width: 210px !important;
}
.facebook-auto {
    width: 200px !important;
}
';

$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//
$ajax->Run('js/phplivex.js'); // Must be called inside the 'html' or 'body' tags  

$notify->Notify();

$isAdminGroup = $page->auth->checkPermessionEdit('TBL_CONTACT',$_REQUEST[contact_id])==1 or $page->auth->isOwner(TBL_CONTACT," and contact_id='$_REQUEST[contact_id]'")
?>

<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
<div id="content_column_header">
	<div class="form_main">
	  <script language="javascript">autosuggest(); autosuggest1();</script>
	  <?php $project_new->showProjectSearch(); ?>
	</div>
</div>
<div class="form_main">
	<div id="show_search_result" class="form_main">
		<?php echo $project_new->searchProjectList(); ?>
	</div>
</div>

<?php





// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>


<?php
$page -> displayPageBottom();
?>