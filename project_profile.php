<?php
//ini_set('display_errors',1);

require_once('app_code/config.inc.php');
require_once('class/class.contacts.php');
require_once('class/class.project.php');
require_once( 'class/class.note.php');
require_once( 'class/class.tasks.php');
require_once( 'class/class.GlobalTask.php');


$page = new basic_page();
$page->auth->Checklogin();

$notify= new Notification();

$ajax=new PHPLiveX();

$project=new Project();

$note = new Note();

$user = new User();

$task = new Tasks();

$global_task = new GlobalTask();

$task -> SetUserObject($user);

$task -> SetUserID($page->auth->Get_user_id());

$ajax->AjaxifyObjects(array("project","task","global_task"));  


$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("PROJECT PROFILE");
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
$page -> setExtJavaScripts7('<script type="text/javascript" src="tablesort/jquery.tablesorter.js"></script>'); 
$page -> setExtJavaScripts8('<script type="text/javascript" src="tablesort/chili-1.8b.js"></script>'); 

$page -> setExtJavaScripts9('<script language="javascript" SRC="js/jtip.js"></script>'); // might not need
$page -> setExtJavaScripts10('<script src="auto/jquery.fcbkcomplete.js" type="text/javascript" charset="utf-8"></script>');

//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '
#lightbox  {
	margin-top: 107px !important;
	}

.no_padding td{
padding: 0px !important;
}
.no_padding{
padding: 0px !important;
}
';

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
        $(document).ready(function() 
        {        
          $("#contact_id").fcbkcomplete({
            json_url: "contactlist.php",
            cache: false,
            filter_hide: true,
            filter_selected: true,
			maxitems: 10
          });
		  
		   $("#contacts	").fcbkcomplete({
            json_url: "contactlist.php",
            cache: false,
            filter_hide: true,
            filter_selected: true,
			maxitems: 10,
          });
		  		 
      });		

}

');

$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//
$notify->Notify();
$ajax->Run('js/phplivex.js'); // Must be called inside the 'html' or 'body' tags  

$project_id=$_GET[project_id];



?>
<div id="div_project"   class="" style="display:none;"></div>
<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>


<div id="content_column_header" ><?php echo $project->returnProjectHead($project_id);?></div>

<div class="form_main">
<?php $project->projectProfile($_REQUEST['project_id']);?>

<div class="contact_form">
<div class="profile_box1" id="div_doc" style="font-weight:bold;">
<a style="color:#FF0000; font-size:15px" onclick="javascript: if(this.innerHTML=='+'){
		  														this.innerHTML = '-';
																document.getElementById('documents').style.display = 'block';
																}
																else {
																this.innerHTML = '+';
																document.getElementById('documents').style.display = 'none';
																} ">+</a>&nbsp;
Project Documents: <a href="project_profile.php?project_id=<?php echo $project_id; ?>&action=upload" >Add Documents</a></div>
<?php
if($_REQUEST['action']=='upload') {
	$doc_id=$_REQUEST['doc_id'];
	if($doc_id){	
	$project->addDocuments('local',$project_id,$doc_id);
	}
	else {
	$project->addDocuments('local',$project_id,'','','',$page->auth->Get_user_id());
	}
}
else {
?>
<div id="documents" class="contact_form" style="display:none;"><?php  echo $project->showDocuments($project_id); ?> </div>
<?php
}
?>
<iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;">
</iframe>
</div></div>

<div class="contact_form">
<div class="profile_box1" id="div_doc" style="font-weight:bold;">
<a style="color:#FF0000; font-size:15px;" onclick="javascript: if(this.innerHTML=='-'){
																this.innerHTML = '+';
																document.getElementById('note_div').style.visibility = 'hidden';
																}
																else {
																this.innerHTML = '+';
																document.getElementById('note_div').style.visibility = 'visible';
																this.innerHTML = '-';
																} ">-</a>&nbsp;Note</div></div> 
<?php
	?><div class="Clear" id="note_div" style="visibility:visible"><?php
	if($_POST[submit]=='add message') 
	{	$note->Create_Note('server',$_REQUEST[project_id],'Project',$page->auth->Get_user_id());		
		exit();
	}
	else
		$note->Create_Note('local',$_REQUEST[project_id],'Project',$page->auth->Get_user_id()); 
	?></div>
</div>
	<?php echo $note->Get_Note($_REQUEST[project_id],'Project'); 		


// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>
<?php /*?><a href="javascript:void(0);" id="task_link" onclick="document.getElementById('task_form').style.display=''; this.style.display='none'; return false;"><img src="images/add_task.png" class="action_links" /></a><br />

<div class="form_bg" style="display:none;" id="task_form">
<?
$task->SetModuleID($project_id);
if($_POST['Save']=='Add this Task')
$task->AddTask('server','PROJECT','project_profile.php','project_id');
else
 $task->AddTask('local');
?>
<div class="Clear"><a href="" onclick="document.getElementById('task_link').style.display=''; document.getElementById('task_form').style.display='none'; return false;">cancel</a></div>
</div>
<?php */?>
<div class="form_main">
<div id="div_list_global_task"><?php echo $global_task->taskPreviewUser($project_id,'Project',$page->auth->Get_user_id());?></div>

<h4>Tasks</h4>
<div class="form_main">
<div id="task_area" class="small_text">

<?php /************* Add link for task **************/ ?>
<a href="javascript:void(0);" id="task_link" onclick="document.getElementById('task_form').style.display=''; this.style.display='none'; return false;">add</a><br /><br />
<div class="form_bg" style="display:none;" id="task_form">
<?
$task->SetModuleID($project_id);
if($_POST['Save']=='Add this Task')
$task->AddTask('server','PROJECT','project_profile.php','project_id');
else
 $task->AddTask('local');
?>
<div class="Clear"><a href="" onclick="document.getElementById('task_link').style.display=''; document.getElementById('task_form').style.display='none'; return false;">cancel</a></div>
</div>
<?php /**************** End of div add task ************/ ?>

<?php // echo $task->GetTask('','','','','','','',1,'PROJECT',$project_id);
 echo $task->GetTaskForProjectProfile('','','','','','','',1,'PROJECT',$project_id); ?>
</div>	</div>
</div>
<?php 
$project->projectStat($_REQUEST[project_id]);

?>

<?php
$page -> displayPageBottom();
?>