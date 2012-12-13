<?php
/***********************************************************************************

Class Discription :
					A task is something that needs to get done by a certain date about a particular entry in a
					module tied to a specific user. Like tags, tasks can be tied to a specific entry of one of the
					modules such as a date, a contact, or a file.
					An example of a task might be to “Follow up with Rishish” in regards to a that is assigned to
					
					
					the contact “Rod” and “needs to be completed by tomorrow”.
					Another example could be “Get paper work turned in” assigned to

Class Memeber Functions :
					1. function AddTask($runat) // $runat=local/server 	
					2. function AddTaskCategory($runat)  // $runat=local/server 	
					3. function EditTask($runat,$task_id) // $runat=local/server,,$task_id=task id of the task(uniqe)
					4. function EditTask_Category($runat,$cat_id) // $runat=local/server,$task_id=task id of the task(uniqe)
					5. function DeleteTask($runat,$task_id) // $runat=local/server,$task_id=task id of the task(uniqe)

Describe Function of Each Memeber function
					
					1. function AddTask($runat) // $runat=local/server 
						Add category,module,module_id,description,due_date in the database in task table	
					
					2. function AddTaskCategory($runat)  // $runat=local/server 
						Add category name and color in the database in task_category table	
					
					3. function EditTask($runat,$task_id) // $runat=local/server,,$task_id=task id of the task(uniqe)
						Edit category,module,module_id,description,due_date in the database in task table
					
					4. function EditTask_Category($runat,$cat_id) // $runat=local/server,$task_id=task id of the task(uniqe)
						Edit category name and color in the database in task_category table	
					
					5. function DeleteTask($runat,$task_id) // $runat=local/server,$task_id=task id of the task(uniqe)
						Delete category,module,module_id,description,due_date in the database in task table	

************************************************************************************/
class Tasks extends Tags// Basic class for contact 
{
const MODULE='TASKS';
var $title;
var $comment;
var $tdocname;
var $tservername;
var $task_id;
var $user_id ;
var $cat_id;
var $description; 
var $due_date;
var $name;
var $color;
var $assigned_to;
var $user;
var $module;
var $module_id;
var	$db;
var $contact_id;
var $Validity;
var $Form;
var $check;
var $profile_page;
var $profile_id;

//upload 
var $document_size;
var $document_name;
var $destination_path ;
var $doc_name;
var $target_path;


var $timearray=array();

	function __construct(){
	parent::__construct();
	$this->contact_id=$_REQUEST['contact_id'];
	$this->Validity=new ClsJSFormValidation();
	
	$this->timearray[weekendday]=5;
	///////////////////////////////////////////////FOR TODAY and TOMMOROW////////////////////////////////////
	$this->timearray[today] = time();
	$this->timearray[tomorrow] =time() + (1 * 24 * 60 * 60);
	
	$N=date("N", time());
	
	if($N<$this->timearray[weekendday]){ $this->timearray[daytoAdd] = $this->timearray[weekendday]- $N; }
	else if($N==$this->timearray[weekendday]) { $this->timearray[daytoAdd] = 0; }
	else { $this->timearray[daytoAdd] = 12- $N; }
	
	//////////////////////////////////////////////FOR THIS WEEK and NEXT WEEk/////////////////////////////////
	$this->timearray[thisweek] =time() + ($this->timearray[daytoAdd] * 24 * 60 * 60);
	$this->timearray[nextweek] =time() + (($this->timearray[daytoAdd] + 7) * 24 * 60 * 60);
	
	//////////////////////////////////////////////FOR THIS MONTH and NEXT MONTH///////////////////////////////
	$td1=date("t",time());
    $pd1=date("d",time());
    $rd1=$td1-$pd1;
    //echo "this month=".$rd1;
    $this->timearray[thismonth] = time() + ($rd1 * 24 * 60 * 60);
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $rd2=$rd1+date("t",mktime(0,0,0,date("m")+1,date("d"),date("Y")));
    //echo "next month=".$rd2;
    $this->timearray[nextmonth] = time() + ($rd2 * 24 * 60 * 60);
	//////////////////////////////////////////////FOR LATER THIS YEAR/////////////////////////////////////////////
    $td3=364+date("L",time());
    $pd3=date("z",time());
    $rd3=$td3-$pd3;
    //echo "later this year=".$rd3;
    $this->timearray[laterthisyear] = time() + ($rd3 * 24 * 60 * 60);
	//////////////////////for the next year////////////////////////////
    $td4=364+date("L",mktime(0,0,0,date("m"),date("d"),date("Y")+1));
    $pd4=date("z",time());
    $rd4=$td4-$pd4;
    $rd4+=$td4;
    //echo "OUTPUT=".$rd4;
	$this->timearray[nextyear] = time() + ($rd4 * 24 * 60 * 60);
	}
	
	function SetUserObject($user){
	$this->user=$user;
	}
	
	function SetUserID($user_id)
	{
		$this->user_id=$user_id;
	}	

	function SetModuleID($module_id)
	{
		$this->contact_id=$module_id;
	}	
	
	//upload function 
	
	
	function getRandomName($filename) {
	$file_array = explode(".",$filename);
	$file_ext = end($file_array);
	$new_file_name = uniqid().date('m').date('d').date('Y').date('G').date('i').date('s').".".$file_ext;
	return $new_file_name;
    }
	
	
	
	/////Methods///////
	function AddTask($runat,$module='',$profile_page='',$profile_id='',$obj='task',$btn_name='',$order_id='',$pro_id='')
	{	
			if($module==''){
			   $module='TBL_CONTACT';
			}
			
			if($profile_page==''){
				$profile_page='contact_profile.php';
			}
			if($profile_id==''){
				$profile_id='contact_id';
			}
	
	
		switch($runat){
		case 'local':
					//display form
					if(count($_POST)>0){
					extract($_POST);
					$this->due_date=$due_date;
					$this->description=$description;
					$this->check=$check;
					$this->cat_id=$cat_id;
					$this->module=$module;
					$this->module_id=$module_id;
					$this->contact_id=$this->module_id;
					$this->title=$title;
					$this->comment=$comment;
					$this->order_id=$order_id;
					$this->pro=$pro_id;
					
					$this->assigned_to=$assigned_to;
					}
					//create client side validation
					$FormName='frm_task';
					$ControlNames=array("title"			=>array('title',"''","Please enter title","spannew_task"),
										);
					$ValidationFunctionName='ValidateTask';
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
					?>
					
					<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName;?>" id="<?php echo $FormName;?>" autocomplete="off">
					<input type="hidden" value="" name="caldate" id="caldate" />
					<input type="hidden" value="" name="calhours"  id="calhours"/>
					<input type="hidden" value="" name="calminutes" id="calminutes" />
					
					<table class="table" width="100%" ><tr>
					<td colspan="2" class="textb">Add New Task&nbsp;<span id="spannew_task"></span></td>
					</tr>
					<tr><td colspan="2">
					<input type="text" name="title" id="title" value=""/>
					</td></tr>
					<tr>
					<td colspan="2">
					Comment:<br/>
					<textarea id="comment" name="comment"  style="height:100px ;width:290px"  value=""></textarea>
					
					</td>
					</tr>
					
					<tr><th>
					When's it due :</th>
					</tr>
					<tr>
					<td class="no_padding">
					<div id="calendar-container"></div>
					<?php /*?><select name="due_date" id="due_date" onchange="javascript:if(this.value!='') { document.getElementById('cal').style.display='none'; }
						else { document.getElementById('cal').style.display=''; }
					">
					<option value="<?php  echo $this->timearray[today] ; ?>" >Today</option>
					<option value="<?php echo $this->timearray[tomorrow] ; ?>" >Tomorrow</option>
					<option value="<?php echo $this->timearray[thisweek] ;?>" >Due This Week</option>
					<option value="<?php echo $this->timearray[nextweek] ;?>" >Due Next Week</option>
					<?php $td1=date("t",time());
                          $pd1=date("d",time());
                          $rd1=$td1-$pd1;
                    if($rd1>14){?> 
					<option value="<?php echo $this->timearray[thismonth] ;?>" >
					<?php echo 'Due This Month';}?></option>
					<option value="<?php echo $this->timearray[nextmonth] ;?>" >Due Next Month</option>
					<?php $month=date("m",time());
					      $restmonth=12-$month;
						  if($restmonth>2){?>
					<option value="<?php echo $this->timearray[laterthisyear] ;?>" >
					<?php echo 'Later This Year';}?></option>
					<option value="<?php echo $this->timearray[nextyear] ;?>" >Next Year</option>
					<option value="Later">Later</option>
					<option value="" id="date_time" >Specific date and time</option>
					</select><?php */?> 
					<span id="spandue_date"></span>
					</td>
					</tr>
					<tr align="center" style="display:none;" id="cal">
					<td colspan="2">
					</td></tr>
					<script type="text/javascript">//<![CDATA[
						function showCalender(){	
							Calendar.setup({
							cont          : "calendar-container",
							weekNumbers   : true,
							selection     : Calendar.dateToInt(new Date()),
							showTime      : 12,
							onSelect      : function(cal) {
							document.getElementById('<?php echo $FormName;?>').caldate.value=this.selection.print("%d-%m-%Y");
							document.getElementById('<?php echo $FormName;?>').calhours.value=cal.getHours();
							document.getElementById('<?php echo $FormName;?>').calminutes.value=cal.getMinutes();
							},
							onTimeChange  : function(cal) {
							
							document.getElementById('<?php echo $FormName;?>').caldate.value=this.selection.print("%d-%m-%Y");
							document.getElementById('<?php echo $FormName;?>').calhours.value=cal.getHours();
							document.getElementById('<?php echo $FormName;?>').calminutes.value=cal.getMinutes();
							}
							});
						}
						showCalender();
					//]]></script>
					
					
					<input type="hidden" name="module" id="module" value="TBL_CONTACT"/>
					<input type="hidden" id="module_id" name="module_id" value="<?php echo $this->contact_id;?>"> 
					<!-- To DO : 
					<div class="Clear">
					<div class="Label">Who's responsible:</div><div class="space">&nbsp;</div>
					<div class="Field">
					<select name="module" onchange="task.Get_Module_Id(this.value, {content_type:'json', target:'module_id', preloader:'prl'})" >
                    <option value="">Select MODULE</option>
                	<option value="TBL_FILE">File</option>
                    <option value="TBL_DATE">Date</option>
                    <option value="TBL_USER">User</option>
                 	<option value="TBL_CONTACT">Contact</option>
                    </select>
					<span id="spanname"></span>
					</div>
					</div>
					-->
				<tr><th>
					Who's responsible :</th>
					</tr>
					<tr>
					<td>			 
					<?php 
					$users = new User();
					echo $users->GetAllUserInList('assigned_to',$this->user_id,'','yes',$this->user_id); ?>
    			 	
				    <span id="spanmodule_id"></span>
					<?php
					$sql_group = "select * from ".TBL_USERGROUP." order by group_name";
					$result_group = $this->db->query($sql_group,__FILE__,__LINE__); ?>
					<select name="user_group" id="user_group">
						<option value="">---select group---</option>
						<?php while($row_group = $this->db->fetch_array($result_group)){ ?>
							<option value="<?php echo $row_group['group_id'];?>"><?php echo $row_group['group_name'];?></option>
						<?php } ?>
					</select>
					</td>
				  	</tr>
					<tr>
					<td colspan="2">
					<input name="check" type="checkbox" checked="checked" id="check" value="yes" style="width:auto"/>
					&nbsp;&nbsp;Let everyone to see this task
					<span id="spanchek"></span>
					</td></tr>
					<tr><th>
					Category : </th></tr>
					<tr>
					<td><span>
					<select name="cat_id" id="cat_id" onChange="if(this.value=='NewCat'){ 
																	category = prompt('Enter name of category','');
																	if(category!=null){
																	if(category.length>0)
																	{
																		<?php echo $obj; ?>.Add_Fly_Cat('server',category,'#0000FF',
																		{	onUpdate: function(response,root){  
																			if(response==1)
																			<?php echo $obj; ?>.GetTaskCategoryJson({content_type:'json', target:'cat_id', preloader:'prl'});
																			else {
																			alert('Sorry !! category with name '+category+' already exists');
																			document.getElementById('cat_id').options[0].selected = true;
																			document.getElementById('cat_id').selectedIndex=0;
																			return true;
																				}
																			}
																		}
																		);
																	}
																	else{
																	this.options[0].selected = true;
																	this.selectedIndex=0;
																	return true;
																	} }
																	else 
																	{
																	this.options[0].selected = true;
																	this.selectedIndex=0;
																	return true;
																	}
																}
																 ">
					<option value="">select category</option>
					<?php
					$sql="select * from ".TASKS_CATEGORY." order by name";
					$result=$this->db->query($sql,__FILE__,__LINE__);
					while($row=$this->db->fetch_array($result)){
					?>
                    <option value="<?php echo $row['cat_id'];?>"><?php echo $row['name']; ?></option>
					 <?php
					 }
					 ?>
					 <option value="NewCat" >new category</option>
					</select></span>&nbsp;<span><a href="categoryManagement.php">edit categories</a></span>
					<span id="spancat_id"></span>
					</td></tr>
					<tr>
					<td colspan="2">
					Select the Document to upload: <input type="file" name="myfile"> 
					</td>
					
					</tr>
					
					
					<tr>
					<td colspan="2">
					<input type="hidden" id="hide_pro123" name="hide_pro123" value="<?php echo $this->pro;?>"/>
					
					<input type="submit" name="<?php if($btn_name=='ORDER'){ echo "Save_ORDER"; } else if($btn_name=='WORK_ORDER'){ echo "Save_WORK_ORDER"; } ?>" value="Add this Task" id="Save" style="width:auto"  onClick="if(this.form.assigned_to.value=='' && this.form.user_group.value==''){ alert('Please select a user or user group'); return false; } else { return <?php echo $ValidationFunctionName?>(); }" />
					</td></tr></table>
					</form>
				
					<?php
					
				break;
			case 'server':
			
					//Reading Post Date
					$this->module=$module;
					$this->profile_page = $profile_page;
					$this->profile_id = $profile_id;
					
					extract($_POST);
					$this->due_date=$due_date;
					$this->description=$description;
					$this->check=$check;
					$this->cat_id=$cat_id;
					$this->module_id=$module_id;
					$this->title=$title;
					$this->comment=$comment;
					$this->assigned_to=$assigned_to;
					$this->order_id=$order_id;
					$this->product_id=$pro_id;
					
					$this->document_name=$_FILES['myfile']['name'];
					$this->document_size=$_FILES['myfile']['size'];
					$this->destination_path= "doc/";
					
					$this->tdocname=$_FILES['myfile']['name'];			
				
					if($this->document_size>0)
					{
					///ne upload
					$doc_name = $this->getRandomName($this->document_name);
			       $target_path = $this->destination_path.basename( $doc_name);
			/*if(getRandomName($document_name)){ ?><script> alert('<?php echo $target_path; ?>'+'aaaaaaaa'); </script> <?php }*/
			        move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path);
					
					$this->tservername=$this->getRandomName($this->document_name);
					
					}
					
								
					if($this->due_date=='')
					{
						
						$date=array();
						$date=explode('-',$caldate);
						
						if(substr($date[0],0,1)=='0')
						$date[0]=substr($date[0],1,1);
						if(substr($date[1],0,1)=='0')
						$date[1]=substr($date[1],1,1);
						//////////////////////////////////////////////////date///////////////////////////
						$this->due_date=mktime($calhours, $calminutes, 0, $date[1], $date[0], $date[2]);
					}
					//echo $this->due_date;
					$return =true;
					if($this->Form->ValidField($title,'empty','Please enter task title')==false)
						$return =false;

					if($return){
					
					$insert_sql_array = array();
					$insert_sql_array['title'] = $this->title;
					$insert_sql_array['user_id'] = $this->user_id;
					$insert_sql_array['comment'] = $this->comment;
					$insert_sql_array['doc_name'] = $this->tdocname;
					$insert_sql_array['doc_server_name'] = $doc_name;
					$insert_sql_array['due_date'] = $this->due_date;
					$insert_sql_array['description'] = $this->description;
					if($this->check!=''){
					$insert_sql_array['is_global'] = $this->check;
					}
					//$insert_sql_array['assigned_to'] = $this->assigned_to;
					$insert_sql_array['cat_id'] = $this->cat_id;
					
					$this->db->insert(TASKS,$insert_sql_array);
					$this->task_id=$this->db->last_insert_id();
					
					
					$insert_sql_array = array();
					$insert_sql_array['task_id'] = $this->task_id;
					$insert_sql_array['module'] = $this->module;
					$insert_sql_array['module_id'] = $this->module_id;
					$insert_sql_array['profile_page'] = $this->profile_page;
					$insert_sql_array['profile_id'] = $this->profile_id;
					$insert_sql_array['order_id'] = $this->order_id;
					$insert_sql_array['product_id'] = $this->product_id;
					$this->db->insert(ASSIGN_TASK,$insert_sql_array);
					
					
					//uploadfile
					
					  
					
						
					
					if($this->assigned_to){
						$this->module = 'TBL_USER';
						$this->module_id = $this->assigned_to;
						$insert_sql_array = array();
						$insert_sql_array['task_id'] = $this->task_id;
						$insert_sql_array['module'] = $this->module;
						$insert_sql_array['module_id'] = $this->module_id;
						$this->db->insert(TASK_RELATION,$insert_sql_array);
					}
					
					if($user_group){
						$this->module = 'TBL_USERGROUP';
						$this->module_id = $user_group;
						$insert_sql_array = array();
						$insert_sql_array['task_id'] = $this->task_id;
						$insert_sql_array['module'] = $this->module;
						$insert_sql_array['module_id'] = $this->module_id;
						$this->db->insert(TASK_RELATION,$insert_sql_array);
						
					}
					//$check= $target_path;
					$_SESSION['msg']='Task has been successfully created';
					?>
					<script type="text/javascript">
					window.location="<?php echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];?>";
					//window.location="<?php echo $_SERVER['PHP_SELF'];?>";
					</script>
					<?php
					exit();
					}
				else
				{
				echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
				$this->AddTask('local');
				}
				break;
				
		default : echo 'Wrong Paramemter passed';
		}
	}
	
// function of edit task along with task id	
	function EditTask($runat,$task_id,$obj='task',$type='')
	{
		ob_start();
		$this->task_id=$task_id;
		switch($runat){
			case 'local':
					//get data for this task from the tasks table.
					$sql="select * from ".TASKS." a, ".ASSIGN_TASK." b where a.task_id='$this->task_id' and a.task_id=b.task_id";
					
					$record=$this->db->query($sql,__FILE__,__LINE__);
					$row=$this->db->fetch_array($record);
					extract($row);
					//print_r($row);
/*					$duedate=$row[4]; 
					$this->due_date=$due_date;
					$this->check=$check;
					$this->cat_id=$cat_id;
					$this->title=$title;
					$this->comment=$comment;
					$this->assigned_to=$assigned_to;
					$this->is_global=$is_global;*/
					
					
					if(count($_POST)>0){
					extract($_POST);
					$this->due_date=$due_date;
					$this->check=$check;
					$this->cat_id=$cat_id;
					$this->title=$title;
					$this->comment=$comment;
					$this->assigned_to=$assigned_to;
					$this->is_global=$is_global;
					}
					//create client side validation
					$FormName='frm_task_edit';
					$ControlNames=array("title"			=>array('title',"''","Please enter title","spannew_task"),
										);
					$ValidationFunctionName='frm_task_edit_ValidateTask';
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
					?>
					
					<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName;?>" id="<?php echo $FormName;?>" autocomplete="off">
					<input type="hidden" value="" name="caldate" id="caldate" />
					<input type="hidden" value="" name="calhours"  id="calhours"/>
					<input type="hidden" value="" name="calminutes" id="calminutes" />
					<input type="hidden" value="<?php echo $this->task_id; ?>" name="task_id" id="task_id" />
					<table class="table" width="100%" ><tr>
					<td colspan="2" class="textb">Editing Task&nbsp;<span id="spannew_task"></span></td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="text" name="title" id="title" value="<?php echo $this->title; ?>"/>
						</td>
					</tr>
					<tr>
						<td colspan="2">Comment:<br/>
							<textarea id="txt_comment" name="txt_comment"  style="height:100px ;width:290px"><?php echo $this->comment; ?></textarea>						
						</td>
					</tr>
					<tr><th>When's it due</th></tr>
					<tr>
						<td colspan="2" class="no_padding">
							<div id="calendar-container"></div>
							<span id="spandue_date"></span>
						</td>
					</tr>
					<tr align="center"  id="cal">
						<td colspan="2">
							<div id="calendar-container"></div>
						</td>
					</tr>
					<script type="text/javascript">//<![CDATA[
						function cal123(){
							Calendar.setup({
							cont          : "calendar-container",
							weekNumbers   : true,
							selection     : Calendar.dateToInt(new Date()),
							showTime      : 12,
							onSelect      : function(cal) {
							document.getElementById('<?php echo $FormName;?>').caldate.value=this.selection.print("%d-%m-%Y");
							document.getElementById('<?php echo $FormName;?>').calhours.value=cal.getHours();
							document.getElementById('<?php echo $FormName;?>').calminutes.value=cal.getMinutes();
							},
							onTimeChange  : function(cal) {
							
							document.getElementById('<?php echo $FormName;?>').caldate.value=this.selection.print("%d-%m-%Y");
							document.getElementById('<?php echo $FormName;?>').calhours.value=cal.getHours();
							document.getElementById('<?php echo $FormName;?>').calminutes.value=cal.getMinutes();
							}
							});
						}
					//]]></script>
<?php /*?>					<tr><th>When's it due :</th>
						<td>					
				  <select name="due_date" id="due_date" onChange="javascript:if(this.value!='') { document.getElementById('cal').style.display='none'; }
						else { document.getElementById('cal').style.display=''; }
					">
					<option value="<?php  echo $this->timearray[today] ; ?>" >Today</option>
					<option value="<?php echo $this->timearray[tomorrow] ; ?>" >Tomorrow</option>
					<option value="<?php echo $this->timearray[thisweek] ;?>" >Due This Week</option>
					<option value="<?php echo $this->timearray[nextweek] ;?>" >Due Next Week</option>
					<?php $td1=date("t",time());
                          $pd1=date("d",time());
                          $rd1=$td1-$pd1;
                    if($rd1>14){?> 
					<option value="<?php echo $this->timearray[thismonth] ;?>" >
					<?php echo 'Due This Month';}?></option>
					<option value="<?php echo $this->timearray[nextmonth] ;?>" >Due Next Month</option>
					<?php $month=date("m",time());
					      $restmonth=12-$month;
						  if($restmonth>2){?>
					<option value="<?php echo $this->timearray[laterthisyear] ;?>" >
					<?php echo 'Later This Year';}?></option>
					<option value="<?php echo $this->timearray[nextyear] ;?>" >Next Year</option>
					<option value="Later">Later</option>
					<option value="" id="date_time" selected="selected" >Specific date and time</option>
					</select> 
					<span id="spandue_date"></span>	
					</td>
					</tr>
					<tr align="center" style="display:'';" id="cal">
					<td colspan="2">
					<div id="calendar-container"></div>
					</td></tr>
					<script type="text/javascript">//<![CDATA[
							Calendar.setup({
							cont          : "calendar-container",
							weekNumbers   : true,
							//selection     : $duedate,
					        //selection     : Calendar.dateToInt(new Date())
			date:  <?php echo $ro=(date("Ymd",$row[due_date])-(date("Ymd",$row[due_date])%100))+1;?>,
							//selection     : <?php echo date("Ymd",$this->due_date); ?>,
							selection     : <?php echo date("Ymd",$row[due_date]);?>,  
							//date          : 20101001, 
							//time		  : 2145,
							showTime      : 12,
							onSelect      : function(cal) {
							document.getElementById('<?php echo $FormName;?>').caldate.value=this.selection.print("%d-%m-%Y");
							document.getElementById('<?php echo $FormName;?>').calhours.value=cal.getHours();
							document.getElementById('<?php echo $FormName;?>').calminutes.value=cal.getMinutes();
							},
							onTimeChange  : function(cal) {
							
							document.getElementById('<?php echo $FormName;?>').caldate.value=this.selection.print("%d-%m-%Y");
							document.getElementById('<?php echo $FormName;?>').calhours.value=cal.getHours();
							document.getElementById('<?php echo $FormName;?>').calminutes.value=cal.getMinutes();
							
						
							}
						});
						
					//]]></script><?php */?>
					<tr><th>
					Who's responsible :</th><td>
									 
					<?php $sql_rel_user = "select * from ".TASK_RELATION." where task_id='$row[task_id]' and module='TBL_USER'"; 
							$result_rel_user = $this->db->query($sql_rel_user,__FILE__,__LINE__);
							$row_rel_user = $this->db->fetch_array($result_rel_user);
					?>
					<?php $sql_rel_usergr = "select * from ".TASK_RELATION." where task_id='$row[task_id]' and module='TBL_USERGROUP'"; 
							$result_rel_usergr = $this->db->query($sql_rel_usergr,__FILE__,__LINE__);
							$row_rel_usergr = $this->db->fetch_array($result_rel_usergr);
					?>
					<?php $this->user->GetAllUserInList('assigned_to',$this->user_id,'','yes',$row_rel_user[module_id]); ?>
    			 	<select name="user_group" id="user_group">
					<option value="">--select group--</option>
					<?php
					$sql = "select * from ".TBL_USERGROUP." order by group_name";
					$result = $this->db->query($sql,__FILE__,__LINE__);
					while($row = $this->db->fetch_array($result)){
					?>
					<option value="<?php echo $row[group_id];?>" <?php if($row[group_id]==$row_rel_usergr[module_id]) echo "selected"; ?>><?php echo $row[group_name];?></option>
					<?php
					}
					?>
					</select>
				    <span id="spanmodule_id"></span>
					</td>
				  	</tr>
					<tr>
					<td colspan="2">
					<input name="check" type="checkbox" id="check" value="yes" <?php if($this->is_global=='yes') echo ' checked="checked"'; ?>  style="width:auto"/>
					&nbsp;&nbsp;Let everyone to see this task
					<span id="spanchek"></span>
					</td></tr>
					<tr><th>
					Category : </th>
					<td><select name="cat_id" id="cat_id" onChange="if(this.value=='NewCat'){ 
																	category = prompt('Enter name of category','');
																	if(category!=null){
																	if(category.length>0)
																	{
																		<?php echo $obj; ?>.Add_Fly_Cat('server',category,'#0000FF'
																		{	onUpdate: function(response,root){  
																			if(response==1)
																			<?php echo $obj; ?>.GetTaskCategoryJson({content_type:'json', target:'cat_id', preloader:'prl'});
																			else {
																			alert('Sorry !! category with name '+category+' already exists');
																			document.getElementById('cat_id').options[0].selected = true;
																			document.getElementById('cat_id').selectedIndex=0;
																			return true;
																				}
																			}
																		}
																		);
																	}
																	else{
																	this.options[0].selected = true;
																	this.selectedIndex=0;
																	return true;
																	} }
																	else 
																	{
																	this.options[0].selected = true;
																	this.selectedIndex=0;
																	return true;
																	}
																}
																 ">
					<option value="">select category</option>
					<?php
					$sql="select * from ".TASKS_CATEGORY." order by name";
					$result=$this->db->query($sql,__FILE__,__LINE__);
					while($row=$this->db->fetch_array($result)){
					?>
                    <option value="<?php echo $row['cat_id'];?>" <?php if($this->cat_id==$row['cat_id']) echo 'selected="selected"';?> ><?php echo $row['name']; ?></option>
					 <?php
					 }
					 ?>
					 <option value="NewCat" >new category</option>
					</select></span>&nbsp;<span><a href="categoryManagement.php">edit categories</a></span>
					<span id="spancat_id"></span>
					</td></tr>
					<tr>
						<td colspan="2">
							Select the Document to upload: <input type="file" name="myfile"> 
						</td>
					</tr>
					<tr>
					<td colspan="2">				
<?php /*?>					<input type="submit" name="Save" value="Save" id="Save" style="width:auto" onclick="<?php echo $this->EditTask('server',$this->task_id,'task'); ?>"/><?php */?>
					<input type="submit" name="save_edit" id="Save" value="Save" style="width:auto" />
					<?php  
					if($type=='contact_profile'){ ?>
					      <input type="button" value="Cancel" name="cancel" style="width:auto" 
									onClick="javascript:location.replace('contact_profile.php?contact_id=<?php echo $this->contact_id; ?>'); 
					      								return false" />
					<?php	}
					else {  ?>
					      <input type="button" value="Cancel" name="cancel" style="width:auto" onClick="javascript:location.replace('task.php'); 
					                                                                                                return false" />
					<?php	}	?>																				 
					</td></tr></table>
<?php }}} ?>					