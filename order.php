<?php
//ini_set("display_errors" , 1);
session_start();
$_SESSION[est_date] = '0';
$_SESSION[total_est_day] = 0;

//echo $_SESSION['contact_id'];  
//ini_set('display_errors',1);

require_once('app_code/config.inc.php');
require_once('class/class.contacts.php');
require_once('class/class.project.php');
require_once('class/class.note.php');
require_once('class/class.tasks.php');
require_once('class/class.GlobalTask.php');
require_once('class/class.WorkOrder.php');
require_once('app_code/class.Event_Contacts.php');
require_once('app_code/zipcode.class.php');
require_once('class/class.CalcDate.php');

$fileserver = new fileserver;
//echo "FILESERVER_PRE";
 $fileserver->check_fileUpload();

//echo "FILESERVER_POST";
$page = new basic_page();
$page->auth->Checklogin();

$notify= new Notification();
$ajax=new PHPLiveX();

$workorder = new WorkOrder();
$project=new Project();
$note = new Note();
$user = new User();
$task = new Tasks();
$global_task = new GlobalTask();
$em = new Event_Contacts();




$task -> SetUserObject($user);
$task -> SetUserID($page->auth->Get_user_id());

$ajax->AjaxifyObjects(array( "fileserver" ,"global_task", "project","task","global_task","workorder","em","note"));


$em->SetUserID($page->auth->Get_user_id());

$em->SetContactID($_SESSION['contact_id']);

$em->SetUserName($page->auth->Get_user_name());

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("WORK ORDER");
$page -> setActiveButton('9');

$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');

$page -> setImportCss3('src/css/jscal2.css');
$page -> setImportCss4('src/css/border-radius.css');
//$page -> setImportCss5('src/css/win2k/win2k.css');
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
$page -> setExtJavaScripts9('<script language="javascript" SRC="js/jtip.js"></script>'); 
$page -> setExtJavaScripts8('<script src="auto/jquery.fcbkcomplete.js" type="text/javascript" charset="utf-8"></script>');


//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '
#lightbox  {
	margin-top:107px !important;
	left: 44%;
	}

	.no_padding td{
	padding: 0px !important;
	}
	.no_padding{
	padding: 0px !important;
	}
form ul.holder {
    width: 200px;
}
#description {
width: 90% !important;
}
.table{
width: 100%;
}

';

$page -> setCustomJavaScripts('
	$(function() {		
		$("#display_order")
		.tablesorter({ widthFixed: true, widgets: ["zebra"], sortList: [[0,0]], headers: {6:{sorter: false}} })
	});
	
 function autoProduct(){
   $(document).ready(function() {        
    $("#add_product").fcbkcomplete({
	json_url: "work_order_productList.php",
	cache: false,
	filter_hide: true,
	filter_selected: true,
	maxitems: 1,
  });		 
});	
}
');

$page -> setPageStyle($page_style);

$page -> displayPageTop('full');

// **********************Start html for content column ****************************//
$notify->Notify();
$ajax->Run('js/phplivex.js'); // Must be called inside the 'html' or 'body' tags  

$order_id = $_REQUEST['order_id'];
$_SESSION[order_id]=$_REQUEST['order_id'];// generated order id
if($workorder->validateOrderId($order_id) == 'true'){

?>
<?php //echo $global_task->estDueDate((2*24*3600),'','2011-11-13').'aaaaa';
//echo getWorkingDays("2011-11-5","2011-11-15");
?>
<div id="div_order"   class="" style="display:none;"></div>

<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
<div>
	<div style="width:60%; background-color:#fff; border-right:1px #ccc solid; border-bottom:1px #ccc solid; margin-right:2%; float:left;">
		<div id="div_project"   class="" style="display:none;"></div>
		<div style="font-size:24px; height:20%">Mt. erp Order</div>
		<div class="contact_form">
			<div id="div_contact_people" style="float:left; width:30%;">
			    <?php echo $workorder->showCompleteContactDetails($_REQUEST[order_id]);?>
		    </div>
			<div style="float:left; width:70%;">
			    <?php echo $workorder->showOrderSizeQuantity($_SESSION['contact_id'],$_REQUEST[order_id]);?>
			</div>
                        
			<div class="Clear"></div>
                        <div>           <?php 
           echo $fileserver->display_files($_REQUEST[order_id], "order" , array( "header_text_style" => 'color: #5f0000;font-size: 14px;font-weight: bold;' , "header_color" => "#f3f3f3", "main_width" => "100%" , "nostyle" => true, "class" => "profile_box1" , "main_style_overide" => 'font-weight:bold;margin-left:16px;' ));
                   ?></div>
		</div>
	</div>
   
	<div style="width:37%; float:right;">
		<h4 style="margin-top: 0px;">Tasks : ORDER <?php //echo $_REQUEST[order_id].'a'.$_SESSION[contact_id];?></h4>
		<div class="form_main">
				<div class="small_text" style="background-color:#CCCCCC">
					<a href="javascript:void(0);" id="task_link" 
									onclick="javascript:document.getElementById('task_form').style.display='';
														workorder.addTodo('ORDER',
																	      'order.php',
																		  'order_id',
																		  '<?php echo $_REQUEST[order_id]; ?>',
																		  '',
																		  '',
																		  {preloader: 'prl',
																		   onUpdate:function(response,root){
																			document.getElementById('task_form').innerHTML = response;
																			showCalender();
																		  }});">add Todo</a><br />
			
				
																		  <?php 
																		  if($_REQUEST['Save_ORDER']=='Add this Task'){
																		  	echo $workorder->addTodo('ORDER','order.php','order_id',$_REQUEST[order_id],'','server');
																		  }
																		  ?></div>
														
						<div class="form_bg" style="display:none;" id="task_form"></div>
						<div  id="task_area" style="background-color:#CCCCCC">
					       <?php echo $task->GetTaskForProjectProfile('','','','','','','',1,'ORDER',$_REQUEST[order_id]);  
						   		 
								 if($_REQUEST['save_edit']=='Save'){
									echo $task->EditTask('server',$_SESSION['task_id'],'task','order_profile');
								 }						   
						    ?>													  
				        </div>
					<div style="background-color:#CCCCCC">
						<?php  echo  $workorder->FlowChartDiv("order", $_REQUEST[order_id]);  ?>
					</div>
		</div>
		<div id="div_list_global_task"><?php //echo $global_task->taskPreviewUser($project_id,'Project',$page->auth->Get_user_id());?></div>
			<div style="background-color:#E9E9E9">
				<?php  echo $workorder->customerInformation($_REQUEST[order_id]); ?>
				<?php echo $workorder->date_display($_REQUEST[order_id]); ?>
			</div>
			<div class="contact_form">
				<div class="profile_box1">
					<?php echo $em->erpContactScreenCustom($_SESSION['contact_id'],$_REQUEST[order_id]);?>
				</div>
			</div>
			
			<!--<div>
			  <h2>Pro Path Scheduling</h2>
			  <div id="propath" style="overflow-y:scroll;overflow-x:hidden; height:450px;">
				<?php 
				      //echo $workorder->predictPathStatus();
					  //echo $workorder->percentDay();?>
			  </div>
		    </div>-->
                

	   </div>
	</div>
</div>
<div class="Clear">
	
</div>

<div id="dyanmic_div" style="width:100%;"><?php echo $workorder->showproductname($_REQUEST['order_id']); ?></div>
<div class="Clear"></div>

<div><?php //echo $note->Get_Note($_SESSION['contact_id'],'TBL_CONTACT'); ?></div>
<div class="Clear"></div>

<?php /****************************************  point 3.1   **************************************/ ?>
<!------------------------------------------------------------------------------------------------>
<?php /*?><div>
	<h4>Tasks</h4>
	<div class="form_main">
		<div id="task_area" class="small_text"><?php echo $workorder->showTask('1',"show_dropdown"); ?></div>
	</div>
	<h4>User Generated Tasks</h4>
	<div class="form_main">
		<div id="task_area" class="small_text"><?php echo $task->GetTaskForProjectProfile('','','','','','','',1,'TBL_CONTACT','11059'); ?></div>
	</div>
	<h4>Task Stats</h4>
	<div class="form_main">
		<div id="task_area" class="small_text"><?php //echo $global_task->show_predict(); ?></div>
	</div>
	<h4>Documents</h4><div style="height:auto" id="show_doc">
	<iframe src="upload_doc.php?action=show" frameborder="0" scrolling="yes" height="100%" width="100%" style="padding: 0px 0px 0px 0px"></iframe>
</div>
<?php */?>

<div style="width:68%;">
		<?php
			if($_POST[submit]=='add message') 
			{	$note->Create_Note('server',$_SESSION['order_id'],'ORDER',$page->auth->Get_user_id());		
				exit();
			}
			else
				$note->Create_Note('local',$_SESSION['order_id'],'ORDER',$page->auth->Get_user_id()); ?>
</div>
<div><?php echo $note->Get_Note($_SESSION['order_id'],'ORDER'); ?></div>

<?php
$page -> displayPageBottom();
}
else { ?>
<div id="content_area">
<h2 style="margin-top:100px; margin-left:200px;"> THE ORDER WITH ORDER ID <i><?php echo $order_id; ?></i> HAS NOT BEEN GENERATED YET , THANK YOU !! </h2>
</div>
<?php } ?>
