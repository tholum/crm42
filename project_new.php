<?php
//ini_set('display_errors',1);
require_once('app_code/config.inc.php');
require_once('class/class.contacts.php');
require_once('app_code/zipcode.class.php');
require_once('app_code/class.Event_Contacts.php');
require_once('class/class.tasks.php');
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

$ajax->AjaxifyObjects(array("project_new","task"));  

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
$page -> setImportCss9('slider/jquery.treeview.css');
$page -> setImportCss10('slider/screen.css');

$page -> setExtJavaScripts1('<script src="js/301a.js" type="text/javascript"></script>'); // might not need //
$page -> setExtJavaScripts2('<script type="text/javascript" src="src/js/jscal2.js"></script>'); // might not need // 
$page -> setExtJavaScripts3('<script type="text/javascript" src="src/js/lang/en.js"></script>'); // might not need
$page -> setExtJavaScripts4('<script type="text/javascript" src="autocomplete/jquery-1.2.6.min.js"></script>');
$page -> setExtJavaScripts5('<script type="text/javascript" src="autocomplete/jquery.jSuggest.1.0.js"></script>');
$page -> setExtJavaScripts6('<script type="text/javascript" src="tablesort/jquery.tablesorter.js"></script>');
$page -> setExtJavaScripts7('<script type="text/javascript" src="tablesort/chili-1.8b.js"></script>');
$page -> setExtJavaScripts8('<script language="javascript" SRC="js/jtip.js"></script>'); 
$page -> setExtJavaScripts9('<script src="auto/jquery.fcbkcomplete.js" type="text/javascript" charset="utf-8"></script>');
$page -> setExtJavaScripts10('<script src="slider/jquery.cookie.js" type="text/javascript"></script>');
$page -> setExtJavaScripts11('<script src="slider/jquery.treeview.js" type="text/javascript"></script>');
$page -> setExtJavaScripts12('<script src="slider/demo.js" type="text/javascript"></script>');

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

function autosuggest2(){
$(document).ready(function() {        
  $("#edit_cust_id").fcbkcomplete({
	json_url: "vendorlist.php?type=customer",
	cache: false,
	filter_hide: true,
	filter_selected: true,
	maxitems: 1,
  });		 
});
}

function autosuggest3(){
	$(document).ready(function() {        
	  $("#edit_u_id").fcbkcomplete({
		json_url: "useslist.php?type=project",
		cache: false,
		filter_hide: true,
		filter_selected: false,
		maxitems: 1,
	  });		 
	});
 }
 
 function autosuggest4(){
	$(document).ready(function() {        
	  $("#task_edit_u_id").fcbkcomplete({
		json_url: "useslist.php?type=project",
		cache: false,
		filter_hide: true,
		filter_selected: false,
		maxitems: 1,
	  });		 
	});
 }

function autosuggest5(){
$(document).ready(function() {        
  $("#conn_edit_cust_id").fcbkcomplete({
	json_url: "vendorlist.php?type=customer",
	cache: false,
	filter_hide: true,
	filter_selected: true,
	maxitems: 1,
  });		 
});
}

function autosuggest6(){
	$(document).ready(function() {        
	  $("#conn_edit_u_id").fcbkcomplete({
		json_url: "useslist.php?type=project",
		cache: false,
		filter_hide: true,
		filter_selected: false,
		maxitems: 1,
	  });		 
	});
 }
');	

$page_style = '
#lightbox {
	top:20px;
	margin-top:0px !important;
}
ul.holder{
background-color:#fff !important;
width: 210px !important;
}
.facebook-auto {
    width: 200px !important;
}

.form_main div {
padding-bottom: 0px !important;
}
table.event_form {
margin: 0px !important;
}
.treeview ul {
margin-top: 0px !important;
}
.treeview li {
padding: 0 0 0 16px !important;
}
table.event_form tbody td {
	padding: 0px !important;
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
<div style="background-color:#eee">
	<div class="form_main">
	  <script language="javascript">autosuggest1(); autosuggest(); autosuggest2(); autosuggest3(); autosuggest4();</script>
	  <?php $project_new->showProjectSearch(); ?>
	</div>
</div>

<div class="form_main">
    <div id="show_search_result">
		<?php
		if($_POST['search']=='Apply')
		{
			if($_POST[chk_unread] == 'on'){
				$note_status = 'unread'; 
				?>
				<script>
				document.getElementById('show_advance_search').style.display='block';
				document.getElementById('chk_unread').checked = true;
				</script>
				<?php
			}
			else
				$note_status = '';
			
			if($_POST[chk_important] == 'on'){
				$note_imp = 'important';
				?>
				<script>			
				document.getElementById('show_advance_search').style.display='block';	
				document.getElementById('chk_important').checked = true;
				</script>
				<?php				
			}
			else
				$npte_imp = '';
				
			if($_POST[chk_unclaimed] == 'on'){
				$note_claim = 'unclaimed';
				?>
				<script>	
				document.getElementById('show_advance_search').style.display='block';			
				document.getElementById('chk_unclaimed').checked = true;
				</script>
				<?php				
			}
			else
				$note_claim = 'claimed';
			
			if($_POST[chk_complete] == 'on'){
				$note_complete = 'yes';
				?>
				<script>	
				document.getElementById('show_advance_search').style.display='block';			
				document.getElementById('chk_complete').checked = true;
				</script>
				<?php				
			}
			else
				$note_complete = 'no';
			
			
			echo $project_new->searchProjectList($_POST[customer_id],$_POST[user_id],$note_status,$note_imp,$note_claim,'',$note_complete); 
		}

		/*if($_POST['search1']=='Go')
		{
			echo $project_new->searchProjectList($_POST[customer_id],$_POST[user_id],'','','claimed'); 
		}*/
		
		else{
			echo $project_new->searchProjectList('','','','','claimed'); 
		}?>
	</div>
</div>


<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>
<div id="divProduct" style="background-color:#eee">
<?php 
if($_REQUEST['add_project'] == 'Add This Project'){
	echo $project_new->showProjectDetails('server',$_REQUEST['project_id']);
}
else if($_REQUEST['add_project'] == 'Update'){
	echo $project_new->showProjectDetails('server');
}
else{
	echo $project_new->showProjectDetails('local');
}
?>
</div>
<div class="Clear ">&nbsp;</div>
<div id="divAddTask" style="background-color:#eee">
<?php
if($_REQUEST['add_task'] == 'Add This Task'){
	echo $project_new->showTaskDetails('server',$_REQUEST['project_id'], $_REQUEST['task_id']);
}
else if($_REQUEST['add_task'] == 'Update Task'){
	echo $project_new->showTaskDetails('server');
}

if($_REQUEST['add_project'] == 'Add Connected Project'){
	echo $project_new->showConnectedProject('server',$_REQUEST['parent_project_id']);
}


?>
</div>

<?php
$page -> displayPageBottom();
?>
