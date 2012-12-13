<?php
/***********************************************************************************

Class Discription :
					A task is something that needs to get done by a certain date about a particular entry in a
					module tied to a specific user. Like tags, tasks can be tied to a specific entry of one of the
					modules such as a date, a contact, or a file.
					An example of a task might be to �Follow up with Rishish� in regards to a that is assigned to
					
					
					the contact �Rod� and �needs to be completed by tomorrow�.
					Another example could be �Get paper work turned in� assigned to

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
var $refresh_js = '';
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
                $this->module_id=$module_id;
	}	
	
	//upload function 
	
	function getRandomName($filename) {
	$file_array = explode(".",$filename);
	$file_ext = end($file_array);
	$new_file_name = uniqid().date('m').date('d').date('Y').date('G').date('i').date('s').".".$file_ext;
	return $new_file_name;
    }
        function SetRefreshJs( $js ){
            $this->refresh_js = $js;
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
					//$this->module_id=$module_id;
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
					<div id="calendar-container<?php echo $order_id.$pro_id; ?>"></div>
					<span id="spandue_date<?php echo $order_id.$pro_id; ?>"></span>
					</td>
					</tr>
					<tr align="center" style="display:none;" id="cal">
					<td colspan="2">
					</td></tr>
					<script type="text/javascript">//<![CDATA[
						function showCalender(){	
							Calendar.setup({
							cont          : "calendar-container<?php echo $order_id.$pro_id; ?>",
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
					<input type="hidden" id="module_id" name="module_id" value="<?php echo $this->module_id;?>"> 
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
																			}});
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
																	}}">
					<option value="">select category</option>
					<?php
					$sql="select * from ".TASKS_CATEGORY." order by name";
					$result=$this->db->query($sql,__FILE__,__LINE__);
					while($row=$this->db->fetch_array($result)){
					?>
                    <option value="<?php echo $row['cat_id'];?>"><?php echo $row['name']; ?></option>
					 <?php } ?>
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
					<input type="submit" name="<?php if($btn_name=='ORDER'){ echo "Save_ORDER"; } else if($btn_name=='WORK_ORDER'){ echo "Save_WORK_ORDER"; } else if($btn_name == 'REWORK_ORDER') { echo 'save_REWORK_ORDER'; } else { echo "Save"; } ?>" value="Add this Task" id="Save" style="width:auto"  onClick="slimcrm.test_serialize = $('#<?php echo $FormName;?>').serialize();$.post('task.php', $('#<?php echo $FormName;?>').serialize() , function( data, textStatus,jqXHR){ $('#task_form').hide();<?php echo $this->refresh_js; ?>} ); return false;" />
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
					$_SESSION['msg']='Task has been successfully created';
					?>
					<script type="text/javascript">
						window.location="<?php echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];?>";
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

	function EditTask($runat,$task_id,$obj='task',$type='')
	{
		ob_start();
		$_SESSION['task_id'] = $task_id;
		$this->task_id=$task_id;
		switch($runat){
			case 'local':
					//get data for this task from the tasks table.
					$sql="select * from ".TASKS." a, ".ASSIGN_TASK." b where a.task_id='$this->task_id' and a.task_id=b.task_id";
					
					$record=$this->db->query($sql,__FILE__,__LINE__);
					$row=$this->db->fetch_array($record);
					extract($row);
					
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
					$ControlNames=array("title"	=>array('title',"''","Please enter title","spannew_task"),
										);
					$ValidationFunctionName='frm_task_edit_ValidateTask';
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation; ?>
					
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
																			}});
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
																	}}">
					<option value="">select category</option>
					<?php
					$sql="select * from ".TASKS_CATEGORY." order by name";
					$result=$this->db->query($sql,__FILE__,__LINE__);
					while($row=$this->db->fetch_array($result)){
					?>
                    <option value="<?php echo $row['cat_id'];?>" <?php if($this->cat_id==$row['cat_id']) echo 'selected="selected"';?> ><?php echo $row['name']; ?></option>
					 <?php } ?>
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
					<input type="submit" name="save_edit" id="Save" value="Save" style="width:auto"/>
					<?php  
					if($type=='contact_profile'){ ?>
					      <input type="button" value="Cancel" name="cancel" style="width:auto" 
									onClick="javascript:location.replace('contact_profile.php?contact_id=<?php echo $this->contact_id; ?>'); 
					      								return false" />
					<?php	}
					else if($type=='order_profile'){ ?>
					      <input type="button" value="Cancel" name="cancel" style="width:auto" 
									onClick="javascript:location.replace('order.php?order_id=<?php echo $_SESSION[order_id]; ?>'); 
					      								return false" />
					<?php	}					
					else {  ?>
					      <input type="button" value="Cancel" name="cancel" style="width:auto" onClick="javascript:location.replace('task.php'); 
					                                                                                                return false" />
					<?php	}	?>																				 
					</td></tr></table>
					</form>
				<?php	
				break;
			case 'server':
					//Reading Post Data
					extract($_POST);
					$this->due_date=$due_date;
					$this->check=$check;
					$this->cat_id=$cat_id;
					$this->title=$title;
					$this->comment=$txt_comment;										
					$this->assigned_to=$assigned_to;					
					$this->document_name=$_FILES['myfile']['name'];
					$this->document_size=$_FILES['myfile']['size'];
					$this->destination_path= "doc/";
					$this->tdocname=$_FILES['myfile']['name'];	
					
					if($this->document_size>0)
					{
						$doc_name = $this->getRandomName($this->document_name);
					    $target_path = $this->destination_path.basename( $doc_name);
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
						
						$this->due_date=mktime($calhours, $calminutes, 0, $date[1], $date[0], $date[2]);
					}
					
					$return =true;
					if($this->Form->ValidField($title,'empty','Please enter task title')==false)
						$return =false;

					if($return){	
					
					$update_sql_array = array();
					$update_sql_array['user_id'] = $this->user_id;
					$update_sql_array['due_date'] = $this->due_date;
					$update_sql_array['title'] = $this->title;
					$update_sql_array['comment'] = $this->comment;
					$update_sql_array['doc_name'] = $this->tdocname;
					$update_sql_array['doc_server_name'] = $doc_name;					
					
					if($this->check=='yes')
					$update_sql_array['is_global'] = $this->check;
					else
					$update_sql_array['is_global'] = 'no';
					
					$update_sql_array['cat_id'] = $this->cat_id;
					
					$this->db->update(TASKS,$update_sql_array,"task_id",$this->task_id);
														
					if($this->assigned_to){
						$this->module = 'TBL_USER';
						$this->module_id = $this->assigned_to;
						$update_sql_array = array();
						$update_sql_array['module'] = $this->module;
						$update_sql_array['module_id'] = $this->module_id;
						$this->db->update(TASK_RELATION,$update_sql_array,"task_id",$this->task_id);
					}
					
					if($user_group){
						$this->module = 'TBL_USERGROUP';
						$this->module_id = $user_group;
						$update_sql_array = array();
						$update_sql_array['module'] = $this->module;
						$update_sql_array['module_id'] = $this->module_id;
						$this->db->update(TASK_RELATION,$update_sql_array,"task_id",$this->task_id);
					}
					$_SESSION['msg']='Task has been successfully saved';
                    ?>
                    <script type="text/javascript">
					window.location="<?php echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];?>";
					</script>
					<?php
					exit();
					}
				else
				{
/*				echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
				$this->AddTask('local');*/
				}
				break;
		default : echo 'Wrong Paramemter passed';
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function AddTaskCategory($runat)  // $runat=local/server 
	{
		switch($runat){
		
			case 'local':
						if(count($_POST)>0 and $_POST['go']=='Go'){
						  extract($_POST);
						  $this->name = $name;
						  $this->color = $color;
						}
						$FormName = "frm_task_category";
						$ControlNames=array("name"			=>array('name',"''","Please enter Category Name","spanname"),
											"color"			=>array('color',"''","Please select Color","spancolor")
											);

						$ValidationFunctionName="CheckValidity";
					
						$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
					//Display Form		
					?>		
					<div id="colorpicker301" class="colorpicker301"></div>	
					<form method="post" action="" enctype="multipart/form-data" name="frm_task_category" id="frm_task_category">
					
					<table cellspacing="5" cellpadding="10">
					<tr><th>Category Name : </th>
					<td><input type="text" name="name" id="name" value="<?php echo $this->name; ?>" /><span id="spanname"></span></td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><th>
					<a href="javascript:onclick=showColorGrid3('color','sample_3');"> Select Color:</a></th>
					<td><input  name="color" id="color"  class="color" value="<?php echo $this->color; ?>" /><span id="spancolor"></span></td>
					</tr>
					<tr><td colspan="2">
					<div ID="sample_3" class="image_border" style="background-color:<?php echo $datarow['color'];?>;color:#FFFFFF; padding:5px; font-weight:800;">&nbsp;</div>
					</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
				
					<tr><td colspan="2"><input type="submit" name="go" id="go" value="Go" onClick="return <?php echo $ValidationFunctionName?>();" />&nbsp;<input type="button" name="cancel" id="cancel" value="Cancel" onClick="location.replace('categoryManagement.php'); return false;" /></td>
					</tr></table>
					</form>
					<?php
					break;
		case 'server':
					//Reading Post Date
					extract($_POST);
					$this->name=$name;
					$this->color=$color;
					
					//server side validation
					$return =true;
					if($this->Form->ValidField($name,'empty','Category name field is Empty or Invalid')==false)
						$return =false;
					if($this->Form->ValidField($color,'empty','Color field is Empty or Invalid')==false)
						$return =false;	
											
					if($return){
					$valid_user = $this->CheckName($this->name);
					if($valid_user){
					$insert_sql_array = array();
					$insert_sql_array['name'] = $name;
					$insert_sql_array['color'] = $color;
					$this->db->insert(TASKS_CATEGORY,$insert_sql_array);
					$_SESSION['msg'] = "Category added successfully";
					?>
					<script type="text/javascript">
						window.location = "<?php echo $_SERVER['PHP_SELF']; ?>"
					</script>
					<?php
					}else {
								echo '<div class="errortxt"><li>Sorry !! This Category name already exists.</li></div>'; 
								$this->AddTaskCategory('local');							
							}
					} else {
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
					$this->AddTaskCategory('local');
					}
					break;
		default : echo 'Wrong Paramemter passed';
		
		}
	}
	//edit task_category
	function EditTask_Category($runat,$cat_id)
	{
		$this->cat_id=$cat_id;
	
		switch($runat){
		case 'local':
						if(count($_POST)>0 and $_POST['go']=='Save'){
						  extract($_POST);
						  $this->name = $name;
						  $this->color = $color;
						}
						$FormName = "frm_task_category";
						$ControlNames=array("name"			=>array('name',"''","Please enter Category Name","spanname"),
											"color"			=>array('color',"''","Please select Color","spancolor")
											);
						$ValidationFunctionName="CheckValidity";
					
						$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
					//Display Form
					$sql="select * from ".TASKS_CATEGORY." where cat_id='$this->cat_id' order by name";
					$result=$this->db->query($sql,__FILE__,__LINE__);
					$datarow=$this->db->fetch_array($result);		
					?>		
					<div id="colorpicker301" class="colorpicker301"></div>	
					<form method="post" action="" enctype="multipart/form-data" name="frm_task_category">
					<table cellspacing="5" cellpadding="10">
					<tr><th>Category Name : </th>
					<td><input type="text" name="name" id="name" value="<?php echo $datarow['name']; ?>" /><span id="spanname"></span></td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><th>
					<a href="javascript:onclick=showColorGrid3('color','sample_3');"> Select Color:</a></th>
					<td><input  name="color" id="color"  class="color"  value="<?php echo $datarow['color']; ?>" /><span id="spancolor"></span></td>
					</tr>
					<tr><td colspan="2">
					<div ID="sample_3" class="image_border" style="background-color:<?php echo $datarow['color'];?>;color:#FFFFFF; padding:5px; font-weight:800;">&nbsp;</div>
					</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><input type="submit" name="go" id="go" value="Save" onClick="return <?php echo $ValidationFunctionName?>();" />&nbsp;<input type="button" name="cancel" id="cancel" value="Cancel" onClick="location.replace('categoryManagement.php'); return false;" /></td></tr>
					</table>
					</form>
					<?php
					break;
					
		case 'server':
					//Reading Post Date
					extract($_POST);
					$this->name=$name;
					$this->color=$color;
					//server side validation
					$return =true;
					if($this->Form->ValidField($name,'empty','Category name field is Empty or Invalid')==false)
						$return =false;
					if($this->Form->ValidField($color,'empty','Color field is Empty or Invalid')==false)
						$return =false;	
					if($return){
					$valid_category = $this->CheckName($this->name,$this->cat_id);
					if($valid_category){
					$update_sql_array = array();
					$update_sql_array['name'] = $this->name;
					$update_sql_array['color'] = $this->color;
					$this->db->update(TASKS_CATEGORY,$update_sql_array,"cat_id",$this->cat_id);
					$_SESSION['msg'] = "Category saved successfully";
					?>
					<script type="text/javascript">
						window.location = "<?php echo $_SERVER['PHP_SELF']; ?>"
					</script>
					<?php
					}else {
								echo '<div class="errortxt"><li>Sorry !! This Category name already exists.</li></div>'; 
								$this->EditTask_Category('local',$this->cat_id);							
							}
					} else {
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
					$this->EditTask_Category('local',$this->cat_id);
					}
					break;
			default : echo 'Wrong Paramemter passed';
		}
	}
	
	function DeleteCategory($cat_id){
			$this->cat_id=$cat_id;
			$sql="delete from ".TASKS_CATEGORY." where cat_id='$this->cat_id'";
			$this->db->query($sql,__FILE__,__LINE__);
			$_SESSION['msg'] = "Category has been deleted successfully";
			?>
			<script type="text/javascript">
				window.location = "<?php echo $_SERVER['PHP_SELF']; ?>"
			</script>
			<?php
			exit();
	}
	function GetAllCategory(){
		$sql = "select * from ".TASKS_CATEGORY." order by name";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		return $record;
	}	
	
	function ShowAllCategory(){
		$record = $this->GetAllCategory();
		if($this->db->num_rows($record)>0){
		?>	
		<table class="table" width="70%">
			<tr>
			  <th>Category Name</th>
			  <td>&nbsp;&nbsp;</td>
			  <th>Action</th>
			</tr>
		<?php  while($row = $this->db->fetch_array($record)){ ?>
			<tr>
			  <td><div class="image_border" style="background-color:#<?php echo str_replace('#','',$row['color']);?>; color:#FFFFFF; padding:5px; font-weight:800;">
					<?php echo $row['name'];?></div>
			</td>
			 <td>&nbsp;&nbsp;</td>
			  <td><a href="<?php $_SERVER['PHP_SELF']?>?cat_id=<?php echo $row[cat_id]; ?>&index=Edit">edit</a>&nbsp;|&nbsp;<a href="<?php $_SERVER['PHP_SELF']?>?cat_id=<?php echo $row[cat_id]; ?>&index=Delete" onClick="return confirm('Do You want to delete this category')">Delete</a></td>
			</tr>
		  <?php 
		  }
		?></table><?php
		} else {
			echo "No Task Category currently Added";
		}	
	}
	
// for delete records in the database
	function DeleteTask($task_id)
	{	
	
			$this->task_id=$task_id;
			$sql="select doc_server_name from ".TASKS." where task_id='$this->task_id'";
		    $result=$this->db->query($sql,__FILE__,__LINE__);
		    $datarow=$this->db->fetch_array($result);	
		    
			$tmpfile = "doc/".$datarow['doc_server_name'];
            echo $tmpfile;
			unlink($tmpfile);
			
			$sql="delete from ".TASKS." where task_id='$this->task_id'";
			$this->db->query($sql,__FILE__,__LINE__);
			$sql="delete from ".ASSIGN_TASK." where task_id='$this->task_id'";
			$this->db->query($sql,__FILE__,__LINE__);	
			$sql="delete from ".TAGS_DATA." where module='TASKS' and module_id='$this->task_id'";
			$this->db->query($sql,__FILE__,__LINE__);

			return $tmpfile;;
	}
	
	function CheckName($name,$cat_id='')
	{
		$sql="select * from ".TASKS_CATEGORY." where name='$name'";
		if($cat_id!='')
		$sql.="  and cat_id!='$cat_id'";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0)
		return false;
		else return true;
	}	

// Get_module_Id function 
	public function Get_Module_Id($module)
	{ if($module!='') {
		$sql="select * from ";
			switch($module){
			case 'TBL_FILE':
				$sql.= TBL_FILE;  
			break;
			case 'TBL_USER':
				$sql.= TBL_USER;  
			break;
			case 'TBL_DATE':
				$sql.= TBL_DATE;  
			break;
			case 'TBL_CONTACT':
				$sql.= TBL_CONTACT;  
			break;
			}

		$options[0] = array("value" => "", "text" => "Choose Contact"); 
		$result=$this->db->query($sql,__FILE__,__LINE__);
		while($row=$this->db->fetch_array($result))
		{				
			switch($module){
			case 'TBL_FILE':
			$options[] = array("value" => $row[TBL_FILE_PRIMARY], "text" => $row[TBL_FILE_TITLE]);  
			break;
			case 'TBL_USER':
			$options[] = array("value" => $row[TBL_USER_PRIMARY], "text" => $row[TBL_USER_TITLE]);  
			break;
			case 'TBL_DATE':
			$options[] = array("value" => $row[TBL_DATE_PRIMARY], "text" => $row[TBL_DATE_TITLE]);  
			break;
			case 'TBL_CONTACT':
			if($row[TBL_CONTACT_TITLE]!='') { $options[] = array("value" => $row[TBL_CONTACT_PRIMARY], "text" => $row[TBL_CONTACT_TITLE]);  }
			break;
			default : echo 'Wrong Paramemter passed';
			}
		}
		return $options;
		}
		return '';
	}
	
	public function GetValue_Module_Id($module,$module_id,$profile_page,$profile_id)
	{ ob_start();
		switch($module){
			case 'TBL_FILE':
			$sql="select ".TBL_FILE_TITLE." from ".TBL_FILE." where ".TBL_FILE_PRIMARY."=$module_id";  
			break;
			case 'TBL_USER':
			$sql="select ".TBL_USER_TITLE." from ".TBL_USER." where ".TBL_USER_PRIMARY."=$module_id";  
			break;
			case 'TBL_DATE':
			$sql="select ".TBL_DATE_TITLE." from ".TBL_DATE." where ".TBL_DATE_PRIMARY."=$module_id";  
			break;
			case 'TBL_CONTACT':
			$sql="select *  from ".TBL_CONTACT." where ".TBL_CONTACT_PRIMARY."=$module_id"; 
			break;
			case 'EM_EVENT':
			$sql="select * from ".EM_EVENT." where ".TBL_EVENT_PRIMARY."=$module_id";  
			break;
			case 'PROJECT':
			$sql="select * from ".PROJECT." where ".TBL_PROJECT_PRIMARY."=$module_id";  
			break;			
			case 'TBL_USERGROUP':
			$sql="select * from ".TBL_USERGROUP." where ".TBL_USERGROUP_PRIMARY."=$module_id";  
			break;
			case 'Project':
			$sql="select * from ".PROJECT." where ".TBL_PROJECT_PRIMARY."=$module_id";  
			break;
			case 'ORDER':
			$sql="select * from ".erp_ORDER." where order_id='$module_id'";  
			break;
			case 'WORK_ORDER':
			case 'REWORK_ORDER':			
			$sql="select * from ".erp_PRODUCT_ORDER." where workorder_id='$module_id'";  
			break;			
		}

		$result = $this->db->query($sql,__FILE__,__LINE__);
		$row=$this->db->fetch_array($result);
		
		if($module=='TBL_CONTACT') {?>
		<a href="<?php echo $profile_page; ?>?<?php echo $profile_id; ?>=<?php echo $module_id; ?>"   >
		<?php
		if($row['type']=='People')
			{ 
				echo $row[TBL_CONTACT_TITLE];
			} 
			else
			{
				echo $row['company_name'];
			}?>
		</a>
		<?php }
			else if($module=='EM_EVENT')
		{?>
		<a href="<?php echo $profile_page; ?>?<?php echo $profile_id; ?>=<?php echo $module_id; ?>"  >
		<?php
				echo $row[group_event_id];
		?>
		</a>
		<?php		
		}
		else if($module=='PROJECT')
		{?>
		<a href="<?php echo $profile_page; ?>?<?php echo $profile_id; ?>=<?php echo $module_id; ?>"  >
		<?php
				echo $row[project_id];
		?>
		</a>
		<?php		
		}
		 else if($module=='TBL_USERGROUP')
		{
		?>
		<a href="<?php echo $profile_page; ?>"  >
		<?php
				echo $row[group_name];
		?>
		</a>
		<?php		
		}
		 else if($module=='ORDER')
		{
		?>
		<a href="<?php echo $profile_page.'?order_id='.$_SESSION[order_id]; ?>" >
		<?php
			  echo $row['order_id']; 
		?>
		</a>
		<?php		
		}		
		 else if($module=='WORK_ORDER' )
		{
		?>
		<a href="<?php echo $profile_page.'?order_id='.$_SESSION[order_id]; ?>" >
		<?php
			  echo $row['workorder_id']; 
		?>
		</a>
		<?php		
		}			
		else { 
		echo $row[0];
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
		
	function getCatName($cat_id)
	{
		$sql="select * from ".TASKS_CATEGORY." where cat_id='$cat_id'";
		$result=$this->db->query($sql,__FILE__,__LINE__);
		$datarow=$this->db->fetch_array($result);	
		return $datarow['name'];	
	}
	
	function getCatColor($cat_id)
	{
		$sql="select * from ".TASKS_CATEGORY." where cat_id='$cat_id'";
		$result=$this->db->query($sql,__FILE__,__LINE__);
		$datarow=$this->db->fetch_array($result);	
		return $datarow['color'];	
	}

    //function checked
	function Complete_Task($task_id,$module_id='',$cat_id='',$listonly=0,$assigned_to_module='',$assigned_to_module_id='',$search_task='',$completed_by='')
	{
	   ob_start();
	   if($search_task == 'search'){
			$this->task_id=$task_id;
			$update_sql_array = array();
			$update_sql_array['completed'] = 'Yes';
			$update_sql_array['completed_on'] = time();
			$update_sql_array['completed_by'] = $completed_by;
			$this->db->update(TASKS,$update_sql_array,"task_id",$this->task_id);
			
			$sql = "Select * from ".ASSIGN_TASK." a, ".TASKS." b where a.module_id = '$assigned_to_module_id' and a.module = 'PROJECT' and a.task_id = b.task_id";
			$result=$this->db->query($sql,__FILE__,__LINE__);
			$count = 0;
			while($row=$this->db->fetch_array($result)){
				if($row['completed'] == 'Yes'){
					$count++;
				}
			}
			if($count == $this->db->num_rows($result)){	
				$sql = "update ".PROJECT." set complete = '100' where project_id='$assigned_to_module_id'";
				$result = $this->db->query($sql,__FILE__,__LINE__);	
			}	
			?>
			<script>
				window.location = "<?php echo $_SERVER['PHP_SELF']; ?>";				
			</script>
			<?php
		}
		else{
			$this->task_id=$task_id;
			$update_sql_array = array();
			$update_sql_array['completed'] = 'Yes';
			$update_sql_array['completed_on'] = time();
			$this->db->update(TASKS,$update_sql_array,"task_id",$this->task_id);		
			return $this->GetTask($module_id,$cat_id,'','','','','',$listonly,$assigned_to_module,$assigned_to_module_id);
		}
	$html = ob_get_contents();
	ob_end_clean();
	return $html;		
	}
	
	function Uncomplete_Task($task_id,$module_id='',$cat_id='')
	{
		$this->task_id=$task_id;
		$update_sql_array = array();
		$update_sql_array['completed'] = 'No';
		$update_sql_array['completed_on'] = '';
		$this->db->update(TASKS,$update_sql_array,"task_id",$this->task_id);
		return $this->GetTask($module_id,$cat_id);
	}
	
	function searchTaskHeader()
	{ 
		$formName = "frm_search"; ?>
    <form name="<?php echo $formName;?>" method="post" action="">
		<table width="100%">
			<tr>
				<td>Task</td>
				<td>Connection</td>
				<td colspan="2">User</td>				
			</tr>
			<tr>
				<td><input type="text" name="txt_task" id="txt_task" 
							onchange="javascript:task.show_searchTaskHeader(document.getElementById('txt_task').value,
																			document.getElementById('txt_connection').value,
																			document.getElementById('select_user').value,
																			document.getElementById('select_usergroup').value,
																			document.getElementById('select_created').value,
																			document.getElementById('txt_due_from').value,
																			document.getElementById('txt_due_to').value,
																			document.getElementById('txt_created_from').value,
																			document.getElementById('txt_created_to').value,
																			document.getElementById('select_category').value,
																			document.getElementById('completed').value,
																			document.getElementById('txt_completed_from').value,
																			document.getElementById('txt_completed_to').value,																			
																			{preloader:'prl',onUpdate: function(response,root){
																			document.getElementById('task_area').innerHTML=response;
																			$('#display_search')
																			.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																			);"/></td>
																													 
				<td>
					<select name="txt_connection" id="txt_connection" 
							onchange="javascript:task.show_searchTaskHeader(document.getElementById('txt_task').value,
																			document.getElementById('txt_connection').value,
																			document.getElementById('select_user').value,
																			document.getElementById('select_usergroup').value,
																			document.getElementById('select_created').value,
																			document.getElementById('txt_due_from').value,
																			document.getElementById('txt_due_to').value,
																			document.getElementById('txt_created_from').value,
																			document.getElementById('txt_created_to').value,
																			document.getElementById('select_category').value,
																			document.getElementById('completed').value,
																			document.getElementById('txt_completed_from').value,
																			document.getElementById('txt_completed_to').value,																			
																			{preloader:'prl',onUpdate: function(response,root){
																			document.getElementById('task_area').innerHTML=response;
																			$('#display_search')
																			.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																			);">
						<option value="">-Select-</option>
						<?php 
						$sql_connection = "Select DISTINCT module from ".ASSIGN_TASK;
						$result_connection=$this->db->query($sql_connection,__FILE__,__LINE__);
     		            while($row_connection=$this->db->fetch_array($result_connection)){
						if($row_connection['module'] == 'TBL_CONTACT'){?>
						<option value="<?php echo $row_connection['module']; ?>"><?php echo 'CONTACT'; ?></option>
						<?php } 
						else { ?>
						<option value="<?php echo $row_connection['module']; ?>"><?php echo $row_connection['module']; ?></option>
						<?php } } ?>
					</select>
				</td>
				<td colspan="2">
					<select name="select_user" id="select_user" 
							onchange="javascript:task.show_searchTaskHeader(document.getElementById('txt_task').value,
																			document.getElementById('txt_connection').value,
																			document.getElementById('select_user').value,
																			document.getElementById('select_usergroup').value,
																			document.getElementById('select_created').value,
																			document.getElementById('txt_due_from').value,
																			document.getElementById('txt_due_to').value,
																			document.getElementById('txt_created_from').value,
																			document.getElementById('txt_created_to').value,
																			document.getElementById('select_category').value,
																			document.getElementById('completed').value,
																			document.getElementById('txt_completed_from').value,
																			document.getElementById('txt_completed_to').value,																			
																			{preloader:'prl',onUpdate: function(response,root){
																			document.getElementById('task_area').innerHTML=response;
																			$('#display_search')
																			.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																			);">
						<option value="">-Select-</option>
						<?php 
						$sql_user = "Select * from ".TBL_USER;
						$result_user=$this->db->query($sql_user,__FILE__,__LINE__);
     		            while($row_user=$this->db->fetch_array($result_user)){
						?>
						<option value="<?php echo $row_user['user_id']; ?>"><?php echo $row_user['first_name'].' '.$row_user['last_name']; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>User Group</td>		
				<td>Category</td>	
				<td>Created</td>
				<td>&nbsp;</td>				
			</tr>
			<tr>		
				<td>
					<select name="select_usergroup" id="select_usergroup" 
							onchange="javascript:task.show_searchTaskHeader(document.getElementById('txt_task').value,
																			document.getElementById('txt_connection').value,
																			document.getElementById('select_user').value,
																			document.getElementById('select_usergroup').value,
																			document.getElementById('select_created').value,
																			document.getElementById('txt_due_from').value,
																			document.getElementById('txt_due_to').value,
																			document.getElementById('txt_created_from').value,
																			document.getElementById('txt_created_to').value,
																			document.getElementById('select_category').value,
																			document.getElementById('completed').value,
																			document.getElementById('txt_completed_from').value,
																			document.getElementById('txt_completed_to').value,																			
																			{preloader:'prl',onUpdate: function(response,root){
																			document.getElementById('task_area').innerHTML=response;
																			$('#display_search')
																			.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																			);">
						<option value="">-Select-</option>
						<?php 
						$sql_usergroup = "Select * from ".TBL_USERGROUP;
						$result_usergroup=$this->db->query($sql_usergroup,__FILE__,__LINE__);
     		            while($row_usergroup=$this->db->fetch_array($result_usergroup)){
						?>
						<option value="<?php echo $row_usergroup['group_id']; ?>"><?php echo $row_usergroup['group_name']; ?></option>
						<?php } ?>
					</select>					
				</td>	
				<td>
					<select name="select_category" id="select_category" 
							onchange="javascript:task.show_searchTaskHeader(document.getElementById('txt_task').value,
																			document.getElementById('txt_connection').value,
																			document.getElementById('select_user').value,
																			document.getElementById('select_usergroup').value,
																			document.getElementById('select_created').value,
																			document.getElementById('txt_due_from').value,
																			document.getElementById('txt_due_to').value,
																			document.getElementById('txt_created_from').value,
																			document.getElementById('txt_created_to').value,
																			document.getElementById('select_category').value,
																			document.getElementById('completed').value,
																			document.getElementById('txt_completed_from').value,
																			document.getElementById('txt_completed_to').value,																			
																			{preloader:'prl',onUpdate: function(response,root){
																			document.getElementById('task_area').innerHTML=response;
																			$('#display_search')
																			.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																			);">
						<option value="">-Select-</option>
						<?php 
						$sql_user = "select * from ".TASKS_CATEGORY." order by name";
						$result_user=$this->db->query($sql_user,__FILE__,__LINE__);
     		            while($row_user=$this->db->fetch_array($result_user)){ ?>
						<option value="<?php echo $row_user['cat_id']; ?>"><?php echo $row_user['name']; ?></option>
						<?php } ?>
					</select>					
				</td>	
				<td>
					<select name="select_created" id="select_created" 
							onchange="javascript:task.show_searchTaskHeader(document.getElementById('txt_task').value,
																			document.getElementById('txt_connection').value,
																			document.getElementById('select_user').value,
																			document.getElementById('select_usergroup').value,
																			document.getElementById('select_created').value,
																			document.getElementById('txt_due_from').value,
																			document.getElementById('txt_due_to').value,
																			document.getElementById('txt_created_from').value,
																			document.getElementById('txt_created_to').value,
																			document.getElementById('select_category').value,
																			document.getElementById('completed').value,
																			document.getElementById('txt_completed_from').value,
																			document.getElementById('txt_completed_to').value,																			
																			{preloader:'prl',onUpdate: function(response,root){
																			document.getElementById('task_area').innerHTML=response;
																			$('#display_search')
																			.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																			);">
						<option value="">-Select-</option>
						<?php 
						$sql_created = "SELECT * FROM ".TBL_USER." WHERE `user_id` in (Select Distinct module_id from ".TASK_RELATION." where `module` = 'TBL_USER')";
						$result_created=$this->db->query($sql_created,__FILE__,__LINE__);
     		            while($row_created=$this->db->fetch_array($result_created)){
						?>
						<option value="<?php echo $row_created['user_id']; ?>"><?php echo $row_created['first_name'].' '.$row_created['last_name']; ?></option>
						<?php } ?>
					</select>				
				</td>						
				<td>&nbsp;</td>					
			</tr>
			<tr>
				<td colspan="2">Due Date</td>
				<td colspan="2">Created Date</td>

			</tr>
			<tr>
				<td colspan="2">
					<input type="text" name="txt_due_from" id="txt_due_from" value="" size="20" autocomplete='off' readonly="true" />to
					<script type="text/javascript">	 
					 function start_cal(){
					 new Calendar({
					 inputField   	: "txt_due_from",
					 dateFormat		: "%Y-%m-%d",
					 trigger		: "txt_due_from",
					 weekNumbers   	: true,
					 bottomBar		: true,				 
					 onSelect		: function() {
											this.hide();
											document.<?php echo $formName;?>.txt_due_from.value=this.selection.print("%Y-%m-%d");										
											task.show_searchTaskHeader(document.<?php echo $formName;?>.txt_task.value,
																	   document.<?php echo $formName;?>.txt_connection.value,
																	   document.<?php echo $formName;?>.select_user.value,
																	   document.<?php echo $formName;?>.select_usergroup.value,
																	   document.<?php echo $formName;?>.select_created.value,
																	   document.<?php echo $formName;?>.txt_due_from.value,
																	   document.<?php echo $formName;?>.txt_due_to.value,
																	   document.<?php echo $formName;?>.txt_created_from.value,
																	   document.<?php echo $formName;?>.txt_created_to.value,
																	   document.<?php echo $formName;?>.select_category.value,
																	   document.<?php echo $formName;?>.completed.value,
																	   document.<?php echo $formName;?>.txt_completed_from.value,
																	   document.<?php echo $formName;?>.txt_completed_to.value,									   
																	   {preloader:'prl',onUpdate: function(response,root){
																		document.getElementById('task_area').innerHTML=response;
																		$('#display_search')
																		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																	  );
											}				
					  });
					}
					start_cal();
					</script>																		
																													 
					<input type="text" name="txt_due_to" id="txt_due_to" value="" size="20" autocomplete='off' readonly="true" />																			
					<script type="text/javascript">	
					 function end_cal(){ 
					 new Calendar({
					 inputField   	: "txt_due_to",
					 dateFormat		: "%Y-%m-%d",
					 trigger		: "txt_due_to",
					 weekNumbers   	: true,
					 bottomBar		: true,	
					 onSelect		: function() {
											this.hide();
											document.<?php echo $formName;?>.txt_due_to.value=this.selection.print("%Y-%m-%d");
											task.show_searchTaskHeader(document.<?php echo $formName;?>.txt_task.value,
																	   document.<?php echo $formName;?>.txt_connection.value,
																	   document.<?php echo $formName;?>.select_user.value,
																	   document.<?php echo $formName;?>.select_usergroup.value,
																	   document.<?php echo $formName;?>.select_created.value,
																	   document.<?php echo $formName;?>.txt_due_from.value,
																	   document.<?php echo $formName;?>.txt_due_to.value,
																	   document.<?php echo $formName;?>.txt_created_from.value,
																	   document.<?php echo $formName;?>.txt_created_to.value,
																	   document.<?php echo $formName;?>.select_category.value,
																	   document.<?php echo $formName;?>.completed.value,
																	   document.<?php echo $formName;?>.txt_completed_from.value,
																	   document.<?php echo $formName;?>.txt_completed_to.value,									   
																	   {preloader:'prl',onUpdate: function(response,root){
																		document.getElementById('task_area').innerHTML=response;
																		$('#display_search')
																		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																	  );
										}				
					  });
					  }
					end_cal(); 
					</script>
					<a href="javascript:void(0);" onClick="javascript: document.<?php echo $formName;?>.txt_due_from.value = '';
																	   document.<?php echo $formName;?>.txt_due_to.value = '';
																	   task.show_searchTaskHeader(document.getElementById('txt_task').value,
																			document.getElementById('txt_connection').value,
																			document.getElementById('select_user').value,
																			document.getElementById('select_usergroup').value,
																			document.getElementById('select_created').value,
																			document.getElementById('txt_due_from').value,
																			document.getElementById('txt_due_to').value,
																			document.getElementById('txt_created_from').value,
																			document.getElementById('txt_created_to').value,
																			document.getElementById('select_category').value,
																			document.getElementById('completed').value,
																			document.getElementById('txt_completed_from').value,
																			document.getElementById('txt_completed_to').value,																				
																			{preloader:'prl',onUpdate: function(response,root){
																			document.getElementById('task_area').innerHTML=response;
																			$('#display_search')
																			.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																			);">
																			
					<img src="images/trash.gif" border="0"  align="absmiddle"/>
					</a>														
				</td>
				<td colspan="2">
					<input type="text" name="txt_created_from" id="txt_created_from" value="" size="20" autocomplete='off' readonly="true" />to
					<script type="text/javascript">	 
					 function start_cal(){
					 new Calendar({
					 inputField   	: "txt_created_from",
					 dateFormat		: "%Y-%m-%d",
					 trigger		: "txt_created_from",
					 weekNumbers   	: true,
					 bottomBar		: true,				 
					 onSelect		: function() {
											this.hide();
											document.<?php echo $formName;?>.txt_created_from.value=this.selection.print("%Y-%m-%d");										
											task.show_searchTaskHeader(document.<?php echo $formName;?>.txt_task.value,
																	   document.<?php echo $formName;?>.txt_connection.value,
																	   document.<?php echo $formName;?>.select_user.value,
																	   document.<?php echo $formName;?>.select_usergroup.value,
																	   document.<?php echo $formName;?>.select_created.value,
																	   document.<?php echo $formName;?>.txt_due_from.value,
																	   document.<?php echo $formName;?>.txt_due_to.value,
																	   document.<?php echo $formName;?>.txt_created_from.value,
																	   document.<?php echo $formName;?>.txt_created_to.value,
																	   document.<?php echo $formName;?>.select_category.value,
																	   document.<?php echo $formName;?>.completed.value,
																	   document.<?php echo $formName;?>.txt_completed_from.value,
																	   document.<?php echo $formName;?>.txt_completed_to.value,										   
																	   {preloader:'prl',onUpdate: function(response,root){
																		document.getElementById('task_area').innerHTML=response;
																		$('#display_search')
																		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																	  );
											}				
					  });
					}
					start_cal();
					</script>
					
					<input type="text" name="txt_created_to" id="txt_created_to" value="" size="20" autocomplete='off' readonly="true" />
					<script type="text/javascript">	
					 function end_cal(){ 
					 new Calendar({
					 inputField   	: "txt_created_to",
					 dateFormat		: "%Y-%m-%d",
					 trigger		: "txt_created_to",
					 weekNumbers   	: true,
					 bottomBar		: true,	
					 onSelect		: function() {
											this.hide();
											document.<?php echo $formName;?>.txt_created_to.value=this.selection.print("%Y-%m-%d");
											task.show_searchTaskHeader(document.<?php echo $formName;?>.txt_task.value,
																	   document.<?php echo $formName;?>.txt_connection.value,
																	   document.<?php echo $formName;?>.select_user.value,
																	   document.<?php echo $formName;?>.select_usergroup.value,
																	   document.<?php echo $formName;?>.select_created.value,
																	   document.<?php echo $formName;?>.txt_due_from.value,
																	   document.<?php echo $formName;?>.txt_due_to.value,
																	   document.<?php echo $formName;?>.txt_created_from.value,
																	   document.<?php echo $formName;?>.txt_created_to.value,
																	   document.<?php echo $formName;?>.select_category.value,
																	   document.<?php echo $formName;?>.completed.value,
																	   document.<?php echo $formName;?>.txt_completed_from.value,
																	   document.<?php echo $formName;?>.txt_completed_to.value,										   
																	   {preloader:'prl',onUpdate: function(response,root){
																		document.getElementById('task_area').innerHTML=response;
																		$('#display_search')
																		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																	  );
										}				
					  });
					  }
					end_cal(); 
					</script>
					<a href="javascript:void(0);" onClick="javascript: document.<?php echo $formName;?>.txt_created_from.value = '';
																	   document.<?php echo $formName;?>.txt_created_to.value = '';
																	   task.show_searchTaskHeader(document.getElementById('txt_task').value,
																			document.getElementById('txt_connection').value,
																			document.getElementById('select_user').value,
																			document.getElementById('select_usergroup').value,
																			document.getElementById('select_created').value,
																			document.getElementById('txt_due_from').value,
																			document.getElementById('txt_due_to').value,
																			document.getElementById('txt_created_from').value,
																			document.getElementById('txt_created_to').value,
																			document.getElementById('select_category').value,
																			document.getElementById('completed').value,
																			document.getElementById('txt_completed_from').value,
																			document.getElementById('txt_completed_to').value,																				
																			{preloader:'prl',onUpdate: function(response,root){
																			document.getElementById('task_area').innerHTML=response;
																			$('#display_search')
																			.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																			);">
																			
					<img src="images/trash.gif" border="0"  align="absmiddle"/>
					</a>		
				</td>
			</tr>
			<tr>
				<td>Completed Tasks
					<input type="checkbox" name="completed" id="completed"
							 onclick="javascript:if(this.checked == true) { 
							 						this.value = 'yes'; 
												 	document.getElementById('div_completed').style.display='block';
												 }
							 					 else { 
												 	this.value = ''; 
												 	document.getElementById('div_completed').style.display='none';
												 }
							 					 task.show_searchTaskHeader(document.getElementById('txt_task').value,
																			document.getElementById('txt_connection').value,
																			document.getElementById('select_user').value,
																			document.getElementById('select_usergroup').value,
																			document.getElementById('select_created').value,
																			document.getElementById('txt_due_from').value,
																			document.getElementById('txt_due_to').value,
																			document.getElementById('txt_created_from').value,
																			document.getElementById('txt_created_to').value,
																			document.getElementById('select_category').value,
																			document.getElementById('completed').value,
																			document.getElementById('txt_completed_from').value,
																			document.getElementById('txt_completed_to').value,																				
																			{preloader:'prl',onUpdate: function(response,root){
																			document.getElementById('task_area').innerHTML=response;
																			$('#display_search')
																			.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																			); "/>
				</td>
				<td colspan="3">
					<div id="div_completed" style="display:none">
						Completed Date
						<input type="text" name="txt_completed_from" id="txt_completed_from" value="" size="20" autocomplete='off' readonly="true" />to
						<script type="text/javascript">	 
						 function start_cal(){
						 new Calendar({
						 inputField   	: "txt_completed_from",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "txt_completed_from",
						 weekNumbers   	: true,
						 bottomBar		: true,				 
						 onSelect		: function() {
												this.hide();
												document.<?php echo $formName;?>.txt_completed_from.value=this.selection.print("%Y-%m-%d");										
												task.show_searchTaskHeader(document.<?php echo $formName;?>.txt_task.value,
																		   document.<?php echo $formName;?>.txt_connection.value,
																		   document.<?php echo $formName;?>.select_user.value,
																		   document.<?php echo $formName;?>.select_usergroup.value,
																		   document.<?php echo $formName;?>.select_created.value,
																		   document.<?php echo $formName;?>.txt_due_from.value,
																		   document.<?php echo $formName;?>.txt_due_to.value,
																		   document.<?php echo $formName;?>.txt_created_from.value,
																		   document.<?php echo $formName;?>.txt_created_to.value,
																		   document.<?php echo $formName;?>.select_category.value,
																		   document.<?php echo $formName;?>.completed.value,
																	       document.<?php echo $formName;?>.txt_completed_from.value,
																	       document.<?php echo $formName;?>.txt_completed_to.value,								   
																		   {preloader:'prl',onUpdate: function(response,root){
																			document.getElementById('task_area').innerHTML=response;
																			$('#display_search')
																			.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																		  );
												}				
						  });
						}
						start_cal();
						</script>
						<input type="text" name="txt_completed_to" id="txt_completed_to" value="" size="20" autocomplete='off' readonly="true" />
						<script type="text/javascript">	
						 function end_cal(){ 
						 new Calendar({
						 inputField   	: "txt_completed_to",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "txt_completed_to",
						 weekNumbers   	: true,
						 bottomBar		: true,	
						 onSelect		: function() {
												this.hide();
												document.<?php echo $formName;?>.txt_completed_to.value=this.selection.print("%Y-%m-%d");
												task.show_searchTaskHeader(document.<?php echo $formName;?>.txt_task.value,
																		   document.<?php echo $formName;?>.txt_connection.value,
																		   document.<?php echo $formName;?>.select_user.value,
																		   document.<?php echo $formName;?>.select_usergroup.value,
																		   document.<?php echo $formName;?>.select_created.value,
																		   document.<?php echo $formName;?>.txt_due_from.value,
																		   document.<?php echo $formName;?>.txt_due_to.value,
																		   document.<?php echo $formName;?>.txt_created_from.value,
																		   document.<?php echo $formName;?>.txt_created_to.value,
																		   document.<?php echo $formName;?>.select_category.value,
																		   document.<?php echo $formName;?>.completed.value,
																	   	   document.<?php echo $formName;?>.txt_completed_from.value,
																	       document.<?php echo $formName;?>.txt_completed_to.value,										   																		   {preloader:'prl',onUpdate: function(response,root){
																			document.getElementById('task_area').innerHTML=response;
																			$('#display_search')
																			.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																		  );
											}				
						  });
						  }
						end_cal(); 
						</script>
						<a href="javascript:void(0);" onClick="javascript: document.<?php echo $formName;?>.txt_completed_from.value = '';
																		   document.<?php echo $formName;?>.txt_completed_to.value = '';
																		   task.show_searchTaskHeader(document.getElementById('txt_task').value,
																				document.getElementById('txt_connection').value,
																				document.getElementById('select_user').value,
																				document.getElementById('select_usergroup').value,
																				document.getElementById('select_created').value,
																				document.getElementById('txt_due_from').value,
																				document.getElementById('txt_due_to').value,
																				document.getElementById('txt_created_from').value,
																				document.getElementById('txt_created_to').value,
																				document.getElementById('select_category').value,
																				document.getElementById('completed').value,
																				document.getElementById('txt_completed_from').value,
																				document.getElementById('txt_completed_to').value,																				
																				{preloader:'prl',onUpdate: function(response,root){
																				document.getElementById('task_area').innerHTML=response;
																				$('#display_search')
																				.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																				);">
						<img src="images/trash.gif" border="0"  align="absmiddle"/>
						</a>
				</td>
			</tr>			
		</table>
	</form>
	<?php
	}	 
			
	function show_searchTaskHeader($task='',$connection='',$user='',$usergroup='',$created='',$due_from='',$due_to='',$created_from='',$created_to='',$category='',$complete='',$completed_from='',$completed_to=''){
	ob_start();
	
	if($complete == 'yes'){
		$sql = "select distinct a.task_id,a.user_id,a.title,a.due_date,a.cat_id,a.task_timestamp,a.completed,a.completed_on,b.module AS conn,b.module_id AS conn_id,f.task_id,f.module,f.module_id from tasks a,assign_task b, tbl_user c, tbl_usergroup d, task_relation f where a.completed = 'Yes' and a.task_id = b.task_id and a.task_id = f.task_id and ((a.user_id = c.user_id) or (a.user_id = d.group_id)) and ((d.group_id = f.module_id and f.module = 'TBL_USERGROUP') or (c.user_id = f.module_id and f.module = 'TBL_USER'))";
	}
	if($complete != 'yes'){
		$sql = "select distinct a.task_id,a.user_id,a.title,a.due_date,a.cat_id,a.task_timestamp,a.completed,a.completed_on,b.module AS conn,b.module_id AS conn_id,f.task_id,f.module,f.module_id from tasks a, assign_task b, tbl_user c, tbl_usergroup d, task_relation f where a.completed = 'No' and a.task_id = b.task_id and a.task_id = f.task_id and ((a.user_id = c.user_id) or (a.user_id = d.group_id)) and ((d.group_id = f.module_id and f.module = 'TBL_USERGROUP') or (c.user_id = f.module_id and f.module = 'TBL_USER'))";
    }
	if($task){
		$sql .=" and a.title like '%$task%'";
	}
	if($connection){
		$sql .= " and b.module = '$connection'";
	}
	if($user){
		$sql .=" and a.user_id = c.user_id and a.user_id = '$user' ";
	}
	if($usergroup){
		$sql .=" and d.group_id = '$usergroup'";
	}
	if($created){
		$sql .=" and f.module_id = '$created'";
	}
	$due_date_from = strtotime($due_from);	
	$due_date_to = strtotime($due_to);	
	
	if($due_from && !$due_to){
		$due_date_from = strtotime("-1 days".$due_from);
		$sql .=" and a.due_date >= '$due_date_from'";	
	}		
	if($due_from && $due_to){
		$sql .=" and a.due_date between '$due_date_from' and '$due_date_to'";
	}
	
	if($created_from && !$created_to){
		$sql .=" and a.task_timestamp >= '$created_from'";
	}
	if($created_from && $created_to){
		$sql .=" and a.task_timestamp between '$created_from' and '$created_to'";
	}
	$completed_date_from = strtotime($completed_from);
	$completed_date_to = strtotime($completed_to);	

	if($completed_from && !$completed_to){
		$due_date_from = strtotime("-1 days".$completed_from);
		$sql .=" and a.completed_on >= '$completed_date_from'";	
	}	
	
    if($completed_from && $completed_to){
		$sql .=" and a.completed_on between '$completed_date_from' and '$completed_date_to'";
	}
	
	$sql.=" order by a.due_date ASC";	
	$result = $this->db->query($sql,__FILE__,__LINE__); ?>
	<table id="display_search" class="event_form small_text" width="100%">
		<thead>
		  <tr>
		  	<?php if($complete != 'yes'){ ?>
			<th>&nbsp;</th>
			<?php } ?>
			<th>Category</th>
			<th>Task</th>
			<th>Connection ID</th>
			<th>Responsible</th>
			<th>User Group</th>	
			<th>Created By</th>	
			<th>Due Date</th>
			<th>Created Date</th>
			<?php if($complete == 'yes'){ ?>
			<th>Completed Date</th>
			<?php } ?>
		  </tr>
		</thead>
		<tbody>
		<?php
		if($this->db->num_rows($result)>0) {
			while($row=$this->db->fetch_array($result)){
			?>
			<tr>
			    <?php if($complete != 'yes'){ ?>
				<td><input type="checkbox" name="chk_task[]" id="chk_task[]" onClick="javascript: task.Complete_Task('<?php echo $row[task_id]; ?>',
																											     '','','','','',  
																												 'search',
																												 {preloader:'prl'});">				
				</td>
				<?php } ?>
				<td><?php 
				$sql_cat = "select * from tasks_category where cat_id = '$row[cat_id]'";
				$result_cat = $this->db->query($sql_cat,__FILE__,__LINE__);
				$row_cat=$this->db->fetch_array($result_cat);
				echo $row_cat['name'];  ?></td>				
				<td>
					<a href="javascript:void(0);" onClick="javascript: task.EditTask('local',
																					'<?php echo $row['task_id']; ?>',
																					'task',
																					{preloader: 'prl',
																					 onUpdate: function(response,root){
																					     document.getElementById('Add_Edit_Task').innerHTML=response;
																						 document.getElementById('Add_Edit_Task').style.display='';
																						 cal123();
																					 }}); ">
					<?php echo $row['title'];  ?></a>
				</td>
 			    <td>
					<?php 
					if($row['conn_id'] != ''){
						if($row['conn'] == 'PROJECT'){ ?>
							<a href = "project_profile.php?project_id=<?php echo $row['conn_id']; ?>"><?php echo $row['conn_id'];  ?></a>
						<?php }
						if($row['conn'] == 'TBL_CONTACT'){ ?>
							<a href = "contact_profile.php?contact_id=<?php echo $row['conn_id']; ?>">
								<?php
								$sql_connt = "select * from tbl_user where user_id = '$row[user_id]'";
								$result_connt = $this->db->query($sql_connt,__FILE__,__LINE__);
								$row_connt=$this->db->fetch_array($result_connt);								
								echo $row_connt['first_name'].' '.$row_connt['last_name'];  
								?>
							</a>
						<?php } 
					}
					else
					   echo '-';
						?>					
				</td>
				<td>	
				  <?php 
				  if($row['module'] == 'TBL_USER')	{
					$sql1 = "select first_name, last_name  from ".TBL_USER." where user_id = '$row[module_id]'";
					$result1 = $this->db->query($sql1,__FILE__,__LINE__);
					$row1=$this->db->fetch_array($result1);
					echo $row1['first_name'].' '.$row1['last_name']; 
				  }
				  ?>
				  </td>
				  <td>
				  <?php 
				  if($row['module'] == 'TBL_USERGROUP')	{	
					$sql2 = "select group_name from ".TBL_USERGROUP." where group_id = '$row[module_id]'";	
					$result2 = $this->db->query($sql2,__FILE__,__LINE__);
					$row2=$this->db->fetch_array($result2);
					echo $row2['group_name']; 
				  }
				?>	
				</td>
				<td><?php echo $row1['first_name'].' '.$row1['last_name']; ?></td>
				<td><?php echo date('Y-m-d',$row['due_date']);	?></td>
				<td><?php echo date('Y-m-d',strtotime($row['task_timestamp']));  ?></td>
				<?php if($complete == 'yes'){  ?>
				<td><?php echo date('Y-m-d',$row['completed_on']);  ?></td>
				<?php } ?>
			</tr>
			<?php } 
		}
		else{ ?>
		    <tr>
				<td><?php echo "No Records Found!!!!!!!!"; ?></td>
			</tr>
		<?php }	?>
		</tbody>
	</table>
	<?php		
	$html = ob_get_contents();
	ob_end_clean();
	return $html;	
	}		
			
	function TaskHeader()
	{ ?>
		<div align="right" class="head">
			<a href="" onClick="javascript: task.GetTask( document.getElementById('contact_id').value, 
														  document.getElementById('category').value, 
														  '',
														  'yes',
														  '','','','','','','','',
														  document.getElementById('include_all_visible').checked, 
														  {target: 'task_area', preloader:'prl'}); 
														  return false;">Upcoming</a> | 
			<a href="" onClick="javascript: task.GetTask( document.getElementById('contact_id').value, 
														  document.getElementById('category').value, 
														  'Yes', 
														  {target: 'task_area', preloader:'prl'}); 
														  return false;">Completed</a> | 
			<a href="" onClick="javascript: task.GetTask( '', '', '','', 
														  '<?php echo $this->user_id; ?>',
														  '','','','','','','',
														  document.getElementById('include_all_visible').checked, 
														  {target: 'task_area', preloader:'prl'});
														  return false;">Assigned</a> | 
			<a href="" onClick="javascript: task.GetTask( '', '', '','', '','','','','','',
														  '<?php echo $this->user_id; ?>', 
														  {target: 'task_area', preloader:'prl'});
														  return false;">Assigned to me</a>
		</div>
		<div class="head">task assigned to:
			<?php $this->user->GetAllUserInList('contact_id',
												$this->user_id, 
												"javascript: task.GetTask(this.value, 
																		  document.getElementById('category').value,
																		  '','','','','','','','','',
																		  'TBL_USER', 
																		  {target: 'task_area', preloader:'prl'});", 
												'Yes'); ?>
			&nbsp;in category:
			<select name="category" id="category" onChange="javascript: task.GetTask( document.getElementById('contact_id').value,
																					  this.value,
																					  '','','','','','','','','','',
																					  document.getElementById('include_all_visible').checked, 
																					  {target: 'task_area', preloader:'prl'});" >
				<option value="">All Category</option>
				<?php
				$sql="select * from ".TASKS_CATEGORY." order by name";
				$result_temp=$this->db->query($sql,__FILE__,__LINE__);
				while($rows=$this->db->fetch_array($result_temp)){
					?>
					<option value="<?php echo $rows['cat_id'];?>" <?php if($rows['cat_id']==$row['cat_id']) echo 'selected="selected"'; ?> >
						<?php echo $rows['name']; ?>
					</option>			
					<?php
				 }
				 ?>
			</select>	
			<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
			&nbsp;in group: 
			<?php $this->user->GetAllGroupInList('group_id',
												 '', 
												 "javascript: task.GetTask(this.value, 
																		   document.getElementById('category').value,
																		   '','','','','','','','','',
																		   'TBL_USERGROUP',  
																		   {target: 'task_area', preloader:'prl'});", 
												'Yes'); ?>
			<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
		</div>
		<div class="head" align="right">Only view tasks you are responsible for: 
		<input type="checkbox" value="yes" checked="checked" name="include_all_visible" id="include_all_visible" 
						onChange="javascript:task.GetTask('', 
														  document.getElementById('category').value,
														  '','','','','','','','','','',
														  this.checked , 
														  {target: 'task_area', preloader:'prl'});" />
		</div>
		<?php
	}
				
	function Add_Fly_Cat($runat,$name='',$color=''){
	switch($runat){
		case 'local':
					//Display Form		
					?>	<div id="colorpicker301" class="colorpicker301"></div>
					<div class="contact_form">
					<form method="post" action="" enctype="multipart/form-data" name="frm_task_category" id="frm_task_category">
					<table cellspacing="5" cellpadding="10">
					<tr><th>Category Name : </th>
					<td><input type="text" name="name" id="name" /><span id="spanname"></span></td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><th>
					<a href="javascript:onclick=showColorGrid3('color','sample_3');"> Select Color:</a></th>
					<td><input  name="color" id="color"  class="color" /><span id="spancolor"></span></td>
					</tr>
					<tr><td colspan="2">
					<input type="text" id="sample_3" size="1" value="" style="width:98%"> 
					</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><input type="button" name="go" id="go" value="Go" onClick="task.Add_Fly_Cat('server', this.form.name.value, this.form.color.value,{target: 'display_cat', preloader: 'prl'});" />or <span class="verysmall_text" onClick="document.getElementById('display_cat').style.display='none'; task.GetTaskCategoryJson({content_type:'json', target:'cat_id', preloader:'prl'}); "> Close </span>
					</td></tr></table>
					</form></div>
					<?php
					break;
		case 'server':
					$valid_category = $this->CheckName($name);
					if($valid_category){
					  $insert_sql_array = array();
					  $insert_sql_array['name'] = $name;
					  $insert_sql_array['color'] = $color;
					  $this->db->insert(TASKS_CATEGORY,$insert_sql_array);
					  return 1;
					} else {
					return 0;
					}
				}
			}
	
	function GetTaskCategoryJson()
	{
		ob_start();
		$sql="select * from ".TASKS_CATEGORY." order by name";
		$result=$this->db->query($sql,__FILE__,__LINE__);
		echo '[{"value":"","text":"select category"},';

		while($row=$this->db->fetch_array($result)){
			echo '{"value":"'.$row["cat_id"].'","text":"'.$row["name"].'"},';
		 }
		 echo '{"value":"NewCat","text":"new category"}]';
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
		
	function printTask($row,$listonly=0,$assigned_to_module='',$assigned_to_module_id='',$assigned_to='',$upcomming='',$completed='',$showday='')
	{
	 ?>
	 	<div id="divmaintask_<?php echo $row[task_id];?>">
		<div id="taskmain<?php echo $row[task_id];?>" <?php if(!$listonly) { ?>
												onmouseover="document.getElementById('<?php echo $row[task_id];?>').style.display='';
													   		 document.getElementById('task_action_<?php echo $row[task_id];?>').style.display=''; " 
												onmouseout="document.getElementById('<?php echo $row[task_id];?>').style.display='none'; 
												           document.getElementById('task_action_<?php echo $row[task_id];?>').style.display='none';" 			
			                                           <?php } ?> class="<?php if(!$listonly) echo 'task_padding'; ?>">
		<?php if(!$listonly) { ?>
				<span id="task_action_<?php echo $row[task_id];?>"  style="display:none; " class="task_action">				
						<a href="edit_task.php?task_id=<?php echo $row[task_id]; ?>" onClick="">
						<img src="images/edit.gif" border="0"  align="absmiddle"/></a>&nbsp;
						<a href="#" onClick="if(confirm('Are you sure ?')){	task.DeleteTask(<?php echo $row[task_id]; ?>,
						                               {onUpdate: function(response,root)
													   {document.getElementById('divmaintask_<?php echo $row[task_id];?>').innerHTML='';},
									                    target: 'taskmain<?php echo $row[task_id];?>', preloader:'prl'});
									         } else return false;">
						<img src="images/trash.gif" border="0"  align="absmiddle"/>&nbsp;</a>
				</span>
		<?php } ?>					
		<?php
		if($showday=='showday')	{ 
			?><span class="textb red task_day"> <?php  echo date("D",$row[due_date]).' -'; ?></span> 
		<?php } ?>
		<input type="checkbox" name="chk_task[]" id="chk_task[]" <?php if($row[completed]=='Yes') {?>checked="checked"<?php } ?> 
		onclick="javascript: document.getElementById('taskdiv<?php echo $row[task_id];?>').className= <?php if($row[completed]=='Yes') { ?>''<?php }
																											 else { ?>'complete'<?php } ?>; 
		<?php if($row[completed]=='Yes') { ?>
				task.Uncomplete_Task(<?php echo $row[task_id]; ?>,
				<?php if(!$listonly) { ?>
					document.getElementById('contact_id').value,
					document.getElementById('category').value, 
		        <?php }?>
		       {target: 'task_area', 	preloader:'prl'});
		 <?php }
		  else {  ?>
				task.Complete_Task(<?php echo $row[task_id]; ?>,  
				<?php if(!$listonly) { ?>
					document.getElementById('contact_id').value,
					document.getElementById('category').value, 
				<?php } 
				 else { ?>
				'','',
				<?php } ?>
				'<?php echo $listonly; ?>','<?php echo $assigned_to_module;?>','<?php echo $assigned_to_module_id;?>',
				{target: 'task_area', preloader:'prl'});
		 <?php } ?>">
		
		<?php if($this->getCatName($row['cat_id'])!=''){ ?>
			<span class="image_border" style="background-color:#<?php echo str_replace('#','',$this->getCatColor($row['cat_id']));?>; 
			color:#FFFFFF; padding:2px; font-weight:800;">
			<?php echo $this->getCatName($row['cat_id']);?>
			</span> 
		<?php } ?>
		
        <span id="taskdiv<?php echo $row[task_id];?>" class="<?php if($row[completed]=='Yes') { echo 'complete'; } ?>" ><?php echo $row['title']; ?>        </span>
		
		<?php if($row['module']!='' and $row['module_id']!='') { ?> 
		, (Re: <?php echo $this->GetValue_Module_Id($row['module'],$row['module_id'],$row['profile_page'],$row['profile_id']); echo ')'; 
		}?>
		
		<span class="small_text"><?php 
		if($assigned_to!='' or $upcomming!='') {
			echo " assigned by ".$this->user->GetUserNameById($row[user_id]);			
			echo ",<br/> Comment: ".$row['comment'];			
		}?> 
		
		<br/>Document: <a href="doc/<?php echo $row['doc_server_name']; ?>" TARGET="_blank"><?php echo $row['doc_name']; ?></a> 
		<?php 
		if($completed!='' and $row['completed_on'] ) { echo 'on '.date('d-m-Y , h:i a',$row['completed_on']); } 
		?></span>&nbsp;
		
		<?php if(!$listonly) { ?>
			<span class="verysmall_text">
				<ul  style="display:inline" class="link_list">
					<li><img class="tags_thumb" alt="" src="images/tag_icon.png"  align="absmiddle"/></li>
					<span id="alltags<?php echo $row[task_id]; ?>">
					      <?php echo $this->ShowTags('task','TASKS', $row[task_id], ''); ?>
					</span>
					<li id="edit_link_<?php echo $row[task_id];?>">  
						<a id='<?php echo $row[task_id];?>' class="verysmall_text"  href="#" 
							  onclick="document.getElementById('edit_link_<?php echo $row[task_id];?>').style.display='none'; 
							  task.TagModule_id('local',
												'task',
												'TASKS',
												 <?php echo $row[task_id]; ?>,
												 '','',
												 'alltags<?php echo $row[task_id]; ?>',
												 'alltags<?php echo $row[task_id]; ?>',
												 'document.getElementById(\'edit_link_<?php echo $row[task_id];?>\').style.display=\'\';',
												 {target: 'alltags<?php echo $row[task_id]; ?>', preloader: 'prl'}
												);">			
						- Edit tags</a>
					</li>
				</ul>
			</span>
		<?php } 
		?>
		</div>	
		</div>	
	<?php
	}

	function printTaskForProjectProfile($row,$listonly=0,$assigned_to_module='',$assigned_to_module_id='',$assigned_to='',$upcomming='',$completed='',$showday='')
	{
	if($assigned_to_module=='ORDER' || $assigned_to_module=='WORK_ORDER' || $assigned_to_module=='REWORK_ORDER'  ){
		$mod_type = "order_profile";
	}
	else{
		$mod_type='contact_profile';
	 }
	 ?>
	 	<div id="divmaintask_<?php echo $row[task_id];?>">
		<div id="taskmain<?php echo $row[task_id];?>" <?php if(!$listonly) { ?>
												onmouseover="document.getElementById('<?php echo $row[task_id];?>').style.display='';
													   		 document.getElementById('task_action_<?php echo $row[task_id];?>').style.display=''; " 
												onmouseout="document.getElementById('<?php echo $row[task_id];?>').style.display='none'; 
												           document.getElementById('task_action_<?php echo $row[task_id];?>').style.display='none';" 			
			                                           <?php } ?> class="<?php if(!$listonly) echo 'task_padding'; ?>">
		<?php if(!$listonly) { ?>
				<span id="task_action_<?php echo $row[task_id];?>"  style="display:none; " class="task_action">				
						<a href="edit_task.php?task_id=<?php echo $row[task_id]; ?>" onClick="">
						<img src="images/edit.gif" border="0"  align="absmiddle"/></a>&nbsp;
						<a href="#" onClick="if(confirm('Are you sure ?')){	task.DeleteTask(<?php echo $row[task_id]; ?>,
						                               {onUpdate: function(response,root)
													   {document.getElementById('divmaintask_<?php echo $row[task_id];?>').innerHTML='';},
									                    target: 'taskmain<?php echo $row[task_id];?>', preloader:'prl'});
									         } else return false;">
						<img src="images/trash.gif" border="0"  align="absmiddle"/>&nbsp;</a>
				</span>
		<?php } ?>					
		<?php
		if($showday=='showday')	{ 
			?><span class="textb red task_day"> <?php  echo date("D",$row[due_date]).' -'; ?></span> 
		<?php } 
		
		if($assigned_to_module == 'REWORK_ORDER'){
			$trgt = "task_area".$assigned_to_module_id.'rework';
		}
		else if($assigned_to_module == 'WORK_ORDER'){
			$trgt = "task_area".$assigned_to_module_id;
		}	
		else { $trgt = "task_area";  } ?>
		
		<input type="checkbox" name="chk_task[]" id="chk_task[]" <?php if($row[completed]=='Yes') {?>checked="checked"<?php } ?> 
		onclick="javascript: 
		document.getElementById('taskdiv<?php echo $row[task_id];?>').className= 
																	            <?php if($row[completed]=='Yes') {
																				         ?>'' <?php 
																					  }else {  
																					      ?>'complete'<?php 
																					  } ?>; 
		<?php if($row[completed]=='Yes') { ?>
				task.Uncomplete_Task(<?php echo $row[task_id]; ?>,
				<?php if(!$listonly) { ?>
					document.getElementById('contact_id').value,
					document.getElementById('category').value, 
		        <?php }?>
		       {target: '<?php echo $trgt ?>', preloader:'prl'});
		 <?php }
		  else {  ?>
				task.Complete_Task(<?php echo $row[task_id]; ?>,  
				<?php if(!$listonly) { ?>
					document.getElementById('contact_id').value,
					document.getElementById('category').value, 
				<?php } 
				 else { ?>
					'','',
				<?php } ?>
				'<?php echo $listonly; ?>','<?php echo $assigned_to_module;?>','<?php echo $assigned_to_module_id;?>',
				{target: '<?php echo $trgt ?>', preloader:'prl'});
		 <?php } ?>">
		
        <span style=" font-weight:bold; font-size:16px" id="taskdiv<?php echo $row[task_id];?>" 
			  class="<?php if($row[completed]=='Yes') { echo 'complete'; } ?> default_link_color" >
		   <?php echo $row['title']; ?>
		</span>

		<span class="verysmall_text">
           <a href="javascript:void(0);" onClick="javascript: task.EditTask('local',
		   																	'<?php echo $row[task_id]; ?>',
																			'task',
																			'<?php echo $mod_type;?>',
																			{preloader: 'prl',
																			 onUpdate: function(response,root){
																				 document.getElementById('taskmain<?php echo $row[task_id];?>').innerHTML=response;
																				 document.getElementById('taskmain<?php echo $row[task_id];?>').style.display='';
																				 cal123();
																			 }});">edit</a>
		</span>
		<span class="verysmall_text default_link_color" >
		   <?php echo '&nbsp; Due Date : '.date('d/m/Y , h:i A',$row[due_date]); ?>       
		   <br/>
		</span>
		
		<?php if($row['module']!='' and $row['module_id']!='') { ?> 
		(Re: <?php echo $this->GetValue_Module_Id($row['module'],$row['module_id'],$row['profile_page'],$row['profile_id']); echo ')'; 
		}?>
		
		<span class="small_text"><?php 
		if(($assigned_to!='' or $upcomming!='') and  $row[user_id]) {
		
			$record_user=$this->db->query("select first_name,last_name from  tbl_user where user_id='$row[user_id]'",__FILE__,__LINE__);
			$row_user=$this->db->fetch_array($record_user);
			echo " assigned by ".$row_user[first_name].' '.$row_user[last_name];
		}
		if($row['comment'] != '') {
			echo ",<br/> Comment: ".$row['comment'];	
		}
		if($row['doc_server_name'] != ''){
			?> 
			<br/>Document: <a href="doc/<?php echo $row['doc_server_name']; ?>" " TARGET="_blank"><?php echo $row['doc_name']; ?></a> 
			<?php 
		}
		if($completed!='' and $row['completed_on'] ) { echo 'on '.date('d-m-Y , h:i a',$row['completed_on']); } 
	    ?></span>&nbsp;
		
		<?php if(!$listonly) { ?>
			<span class="verysmall_text">
				<ul  style="display:inline" class="link_list">
					<li><img class="tags_thumb" alt="" src="images/tag_icon.png"  align="absmiddle"/></li>
					<span id="alltags<?php echo $row[task_id]; ?>">
					      <?php echo $this->ShowTags('task','TASKS', $row[task_id], ''); ?>
					</span>
					<li id="edit_link_<?php echo $row[task_id];?>">  
						<a id='<?php echo $row[task_id];?>' class="verysmall_text"  href="#" 
							  onclick="document.getElementById('edit_link_<?php echo $row[task_id];?>').style.display='none'; 
							  task.TagModule_id('local',
												'task',
												'TASKS',
												 <?php echo $row[task_id]; ?>,
												 '','',
												 'alltags<?php echo $row[task_id]; ?>',
												 'alltags<?php echo $row[task_id]; ?>',
												 'document.getElementById(\'edit_link_<?php echo $row[task_id];?>\').style.display=\'\';',
												 {target: 'alltags<?php echo $row[task_id]; ?>', preloader: 'prl'}
												);">			
						- Edit tags</a>
					</li>
				</ul>
			</span>
		<?php } 
		?>
		</div>	
		</div>	
	<?php
	}
		
	function GetTask($module_id='', $cat_id='', $completed='', $upcomming='', $assigned='', $tagModule='', $tagModule_id='',$listonly=0,$assigned_to_module='',$assigned_to_module_id='',$assigned_to='',$module_name='',$include_all_visible=0)
	{
		//either it is assigned to user or it belong to user's group
		ob_start();
		$filter='';
		$sql="select distinct a.*,b.*,c.module as rel_module,c.module_id as rel_module_id from ".TASKS." a, ".ASSIGN_TASK." b,".TASK_RELATION." c where a.task_id=b.task_id and a.task_id=c.task_id";
		if($completed=='' and $upcomming=='' and $assigned=='' and $assigned_to=='')
		$upcomming='yes';
		
		if($tagModule_id!='')
		{
		 $sql.=" and a.task_id=$tagModule_id";
		}
		if($module_id!='')
		{
		 $sql.=" and c.module_id='$module_id' and c.module='$module_name'";
		}
		
		if($cat_id!='')
		{
		 $sql.=" and a.cat_id=$cat_id";
		}
		
		if($completed!='')
		{
		 $filter='complete:';
		 $sql.=" and (a.completed='$completed' and (a.user_id=$this->user_id or c.module_id='$this->user_id'))";
		}
		else
		 $sql.=" and a.completed='No'";
		
		if($assigned!='')
		{
		 $filter='assigned:';
		 $sql.=" and a.user_id=$assigned";
		}
		
		if($assigned_to_module!='' and $assigned_to_module_id!='')
		{
		 $sql.=" and b.module='$assigned_to_module' and b.module_id='$assigned_to_module_id' ";
		}
		
		if($assigned_to!='')
		{
		 $filter='assigned to me:';
		 $sql.=" and c.module_id='$assigned_to' ";
		}
		
		if($upcomming!='')
		{
		 $filter='upcoming:';
			 if( !$include_all_visible) {
				 $sql.=" and (((c.module_id='$this->user_id' and c.module='TBL_USER') or (c.module_id in (select group_id from ".GROUP_ACCESS." where user_id='$this->user_id') and c.module='TBL_USERGROUP')) or a.is_global='yes' )";
				 }		 
			 else { 
			 	$sql.=" and (((c.module_id='$this->user_id' and c.module='TBL_USER') or (c.module_id in (select group_id from ".GROUP_ACCESS." where user_id='$this->user_id') and c.module='TBL_USERGROUP')) )";
				 }		 
			 	
		}
		
		$sql.="order by due_date asc";
		
        $mm=0;
		$overdue = array();
		$dueToday = array();
		$dueTomorrow = array();
		$dueThisWeek = array();
		$dueNextWeek = array();
		$dueThisMonth = array();
		$dueNextMonth = array();
		$dueLaterThisYear = array();
		$dueNextYear= array();
		$later = array(); 
		$task_id_array = array();
		
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0){
		while($row=$this->db->fetch_array($record))
		{
			if(!(in_array($row[task_id], $task_id_array))){
				if($row[due_date]=='Later')
				$later[]=$row;
				else
				{
					$N=date("N", time());
					$dayDiff=$this->db->getDateDiff(date('Y-m-d',$row[due_date]));
					
					if($dayDiff==0)
					$dueToday[]=$row;
					
					elseif($dayDiff<0)
					
					$overdue[]=$row;
					
					elseif($dayDiff==1)
					$dueTomorrow[]=$row;
					////////////////////////////changes////////////////////////////////////
					
					////////////////////////////CHANES end/////////////////////////////////
					
					elseif($dayDiff>1)
				      {
					    if(($N+$dayDiff)<=5 and $N<=5)
		                $dueThisWeek[]=$row;
						
						if(($N+$dayDiff)<=13 and $N>=6)
		                $dueThisWeek[]=$row;	
						
						if(((($N+$dayDiff)>5) and (($N+$dayDiff)<=12))and $N<=5)
		                $dueNextWeek[]=$row;
						
						if(($N+$dayDiff)<=20 and $N>=6)
		                $dueNextWeek[]=$row;
			            						
                        if((((($N+$dayDiff)>12))and $N<=5) and $dayDiff<=(date("t",time())-date("d",time())))
		                $dueThisMonth[]=$row;  
		                
						if((((($N+$dayDiff)>20))and $N>=6) and $dayDiff<=(date("t",time())-date("d",time())))
		                $dueThisMonth[]=$row;  
		
		$mm=date("m",time());	            
		if($mm==12) {
		if( $dayDiff >(date("t",time())-date("d",time())) and $dayDiff <= (date("t",mktime(0,0,0,date("m"),date("d"),date("Y")+1))+date("t",time())-date("d",time())) )
		                
						$dueNextMonth[]=$row; }
        if($mm<12) {
		if( $dayDiff >(date("t",time())-date("d",time())) and $dayDiff <= (date("t",mktime(0,0,0,date("m")+1,date("d"),date("Y")))+date("t",time())-date("d",time())) )
		                
						$dueNextMonth[]=$row; }
		
						if($dayDiff>61 and $dayDiff<=(364+date("L",time())-(date("z",time()))))
		                $dueLaterThisYear[]=$row;	
           				
		if($dayDiff>61 and 
		$dayDiff<=(364+date("L",time())-(date("z",time()))+364+date("L",mktime(0,0,0,date("m"),date("d"),date("Y")+1))))
		        $dueNextYear[]=$row;
				}
			
				}
				$task_id_array[] = $row[task_id];
			}
		}
		} else {
			$filter = 'no tasks';
		} ?>
		<h2><?php echo $filter;?></h2><br />
		<?php 
		if(count($overdue)>0){ ?>
		<ul class="dueTask"><div class="textb red ">Overdue</div>
		<?php
		foreach($overdue as $row)
		{
			$this->printTask($row,$listonly,$assigned_to_module,$assigned_to_module_id,$assigned_to,$upcomming,$completed);
		}
		?>
		</ul>
		<?php
		}?>
		
		<?php if(count($dueToday)>0){ ?>
			<ul class="dueTask"><div class="textb red">Due Today</div>
		<?php
		foreach($dueToday as $row)
		{
			$this->printTask($row,$listonly,$assigned_to_module,$assigned_to_module_id,$assigned_to,$upcomming,$completed);
		}
		?>
		</ul>
		<?php
		}
		?>
		<?php if(count($dueTomorrow)>0){ ?>
			<ul class="dueTask"><div class="textb red">Due Tomorrow</div>
		<?php
		foreach($dueTomorrow as $row)
		{
			$this->printTask($row,$listonly,$assigned_to_module,$assigned_to_module_id,$assigned_to,$upcomming,$completed);
		}	
		?>
		</ul>
		<?php
		}
		?>
		
		<?php if(count($dueThisWeek)>0){ ?>
			<ul class="dueTask"><div class="textb red">Due This Week</div>
		<?php
		foreach($dueThisWeek as $row)
		{
			$this->printTask($row,$listonly,$assigned_to_module,$assigned_to_module_id,$assigned_to,$upcomming,$completed,'showday');
		}
		?>
		</ul>
		<?php
		}
		?>
		
		<?php if(count($dueNextWeek)>0){ ?>
			<ul class="dueTask"><div class="textb red">Due Next Week</div>
		<?php
		foreach($dueNextWeek as $row)
		{
			$this->printTask($row,$listonly,$assigned_to_module,$assigned_to_module_id,$assigned_to,$upcomming,$completed,'showday');
		}
		?>
		</ul>
		<?php
		}
		?>
		<?php 
	
		if(count($dueThisMonth)>0){ ?>
		<ul class="dueTask"><div class="textb red ">Due This Month</div>
		<?php
		foreach($dueThisMonth as $row)
		{
			$this->printTask($row,$listonly,$assigned_to_module,$assigned_to_module_id,$assigned_to,$upcomming,$completed);
		}
		?>
		</ul>
		<?php
		}
		if(count($dueNextMonth)>0){ ?>
		<ul class="dueTask"><div class="textb red ">Due Next Month</div>
		<?php
		foreach($dueNextMonth as $row)
		{
			$this->printTask($row,$listonly,$assigned_to_module,$assigned_to_module_id,$assigned_to,$upcomming,$completed);
		}
		?>
		</ul>
		<?php
		}
		if(count($dueLaterThisYear)>0){ ?>
		<ul class="dueTask"><div class="textb red ">Due Later This Year</div>
		<?php
		foreach($dueLaterThisYear as $row)
		{
			$this->printTask($row,$listonly,$assigned_to_module,$assigned_to_module_id,$assigned_to,$upcomming,$completed);
		}
		?>
		</ul>
		<?php
		}
		if(count($dueNextYear)>0){ ?>
		<ul class="dueTask"><div class="textb red ">Due Next Year</div>
		<?php
		foreach($dueNextYear as $row)
		{
			$this->printTask($row,$listonly,$assigned_to_module,$assigned_to_module_id,$assigned_to,$upcomming,$completed);
		}
		?>
		</ul>
		<?php
		}
		if(count($later)>0){ ?>
			<ul class="dueTask"><div class="textb red">Later</div>
		<?php
		foreach($later as $row)
		{
			$this->printTask($row,$listonly,$assigned_to_module,$assigned_to_module_id,$assigned_to,$upcomming,$completed);
		}
		?>
		</ul>
		<?php
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function GetTaskForProjectProfile($module_id='', $cat_id='', $completed='', $upcomming='', $assigned='', $tagModule='', $tagModule_id='',$listonly=0,$assigned_to_module='',$assigned_to_module_id='',$assigned_to='',$module_name='',$include_all_visible=0)
	{
	
		//either it is assigned to user or it belong to user's group
		ob_start();
		$filter='';
		$sql="select distinct a.*,b.*,c.module as rel_module,c.module_id as rel_module_id from ".TASKS." a, ".ASSIGN_TASK." b,".TASK_RELATION." c where a.task_id=b.task_id and a.task_id=c.task_id";
		if($completed=='' and $upcomming=='' and $assigned=='' and $assigned_to=='')
		$upcomming='yes';
		
		if($tagModule_id!='')
		{
		 $sql.=" and a.task_id=$tagModule_id";
		}
		if($module_id!='')
		{
		 $sql.=" and c.module_id='$module_id' and c.module='$module_name'";
		}
		
		if($cat_id!='')
		{
		 $sql.=" and a.cat_id=$cat_id";
		}
		
		if($completed!='')
		{
		 $filter='complete:';
		 $sql.=" and (a.completed='$completed' and (a.user_id=$this->user_id or c.module_id='$this->user_id'))";
		}
		else
		 $sql.=" and a.completed='No'";
		
		if($assigned!='')
		{
		 $filter='assigned:';
		 $sql.=" and a.user_id=$assigned";
		}
		
		if($assigned_to_module!='' and $assigned_to_module_id!='')
		{
		 $sql.=" and b.module='$assigned_to_module' and b.module_id='$assigned_to_module_id' ";
		}
		
		if($assigned_to!='')
		{
		 $filter='assigned to me:';
		 $sql.=" and c.module_id='$assigned_to' ";
		}
		
		if($upcomming!='')
		{
		 $filter='upcoming:';
			 if( !$include_all_visible) {
				 $sql.=" and (((c.module_id='$this->user_id' and c.module='TBL_USER') or (c.module_id in (select group_id from ".GROUP_ACCESS." where user_id='$this->user_id') and c.module='TBL_USERGROUP')) or a.is_global='yes' )";
				 }		 
			 else { 
			 	$sql.=" and (((c.module_id='$this->user_id' and c.module='TBL_USER') or (c.module_id in (select group_id from ".GROUP_ACCESS." where user_id='$this->user_id') and c.module='TBL_USERGROUP')) )";
				 }			 	
		}
		
		$sql.="order by due_date asc";
        $mm=0;
		$overdue = array();
		$dueToday = array();
		$dueTomorrow = array();
		$dueThisWeek = array();
		$dueNextWeek = array();
		$dueThisMonth = array();
		$dueNextMonth = array();
		$dueLaterThisYear = array();
		$dueNextYear= array();
		$later = array(); 
		$task_id_array = array();
		
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0){
			while($row=$this->db->fetch_array($record))
			{
				$this->printTaskForProjectProfile($row,$listonly,$assigned_to_module,$assigned_to_module_id,$assigned_to,$upcomming,$completed);
			}
		} else {
			$filter = 'no tasks';
		}
	 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function GetTask_info($module_id='', $cat_id='', $completed='', $upcomming='', 
	$assigned='', $tagModule='', $tagModule_id='')
	{
		ob_start();
		$sql="select * from ".TASKS." a, ".ASSIGN_TASK." b where a.task_id=b.task_id and a.task_id='$tagModule_id' and (a.assigned_to='$this->user_id' or a.is_global='yes' or a.user_id=$this->user_id)";

		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0)
		while($row=$this->db->fetch_array($record))
		{ ?>
		<div class="contact_match">
		<div class="Clear">
		<?php if($this->getCatName($row['cat_id'])!=''){ ?>
		<div class="floatleft image_border" 
		style="background-color:#<?php echo str_replace('#','',$this->getCatColor($row['cat_id']));?>; 
		color:#FFFFFF; padding:5px; font-weight:800;">
		<?php echo $this->getCatName($row['cat_id']);?></div>
		<?php } ?>
		<div>&nbsp;&nbsp;
		<span class="<?php if($row[completed]=='Yes') echo 'complete'; ?>">
		<a href="task.php"><span class="heading bcolor"><?php echo $row['title']; ?></span></a> </span><?php if($row['module']!='' and $row['module_id']!='') 
		{ ?> , (Re: <?php echo $this->GetValue_Module_Id($row['module'],$row['module_id'],$row['profile_page'],$row['profile_id']); 
		echo ')'; } ?>
	
		&nbsp;&nbsp;<img class="tags_thumb" alt="" src="images/tag_icon.png"  align="absmiddle"/>
		<span class="verysmall_text">
		<ul class="link_list" style="display: inline;">
		<span>
		<?php echo $this->ShowTags('','TASKS', $row[task_id], ''); ?>
		</span>
		</ul>
		</span>
		<?php } ?>
		</div>	
		</div>
		</div>	<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function recent_task($limit='')
	{
		ob_start();
		$sql="select * from ".TASKS." a, ".ASSIGN_TASK." b where a.task_id=b.task_id and a.completed='No' order by a.task_id desc limit 0,$limit";

		$overdue = array();
		$dueToday = array();
		$dueTomorrow = array();
		$dueThisWeek = array();
		$dueNextWeek = array();
		$later = array(); 
		
		$record=$this->db->query($sql,__FILE__,__LINE__);
		while($row=$this->db->fetch_array($record))
		{
			if($row[is_global]=='yes' or ( $row[is_global]=='no' and $row[user_id]==$this->user_id))
			{
				if($row[due_date]=='Later')
				$later[]=$row;
				else
				{
					$N=date("N", time());
					$dayDiff=$this->db->getDateDiff(date('Y-m-d',$row[due_date]));
					
					if($dayDiff==0)
					$dueToday[]=$row;
					
					elseif($dayDiff<0)
					$overdue[]=$row;
					
					elseif($dayDiff==1)
					$dueTomorrow[]=$row;
					
					elseif($dayDiff>1)
					{
						if(($N+$dayDiff)<=5 and $N<5)
						$dueThisWeek[]=$row;
						else
						if(($N+$dayDiff)<=12 and $N<=7)
						$dueThisWeek[]=$row;

						else
						$dueNextWeek[]=$row;
					}
			
				}
			}
		}
		if($upcomming==''){
		if(count($overdue)>0){ ?>
		<?php
		foreach($overdue as $row)
		{
			$this->printRecentTask($row);
		}
		}
		}
	   if(count($dueToday)>0){ ?>
		<?php
		foreach($dueToday as $row)
		{
			$this->printRecentTask($row);
		}
		}
		if(count($dueTomorrow)>0){ ?>
		<?php
		foreach($dueTomorrow as $row)
		{
			$this->printRecentTask($row);
		}	
		}
		if(count($dueThisWeek)>0){ ?>
		<?php
		foreach($dueThisWeek as $row)
		{
			$this->printRecentTask($row);
		}
		}
		if(count($dueNextWeek)>0){ ?>
		<?php
		foreach($dueNextWeek as $row)
		{
			$this->printRecentTask($row);
		}
		}
		if(count($later)>0){ ?>
		<?php
		foreach($later as $row)
		{
			$this->printRecentTask($row);
		}
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function printRecentTask($row)
	{
	 ?>
		<li class="message_icon">
		<span class="message_title">
		<?php echo $row['title'].' on '. date('d/m/Y',$row[due_date]); ?>
		</span>
		</li>
		<?php
	}
	
		function AddTaskOnTheFly($task,$assigntask)
		{
		$task_id='';
		
		if(count($task)>0)
		{
			$insert_sql_array_task = array();
			$insert_sql_array_task[user_id] = $task[user_id];
			$insert_sql_array_task[title] = $task[title];
			$insert_sql_array_task[due_date] = $task[due_date];
			$insert_sql_array_task[is_global] = $task[is_global];
			$insert_sql_array_task[completed] = $task[completed];
			
			$this->db->insert(TASKS,$insert_sql_array_task);
			$task_id=$this->db->last_insert_id();
			}
			if($task_id=='')
			{
				$task_id=$assigntask[task_id];
			}
			if(count($assigntask)>0)
			{
			$insert_sql_array = array();
			$insert_sql_array[task_id] = $task_id;
			$insert_sql_array[module] = $assigntask[module];
			$insert_sql_array[module_id] = $assigntask[module_id];
			$insert_sql_array[profile_page] = $assigntask[profile_page];
			$insert_sql_array[profile_id] = $assigntask[profile_id];
		 
			$this->db->insert(ASSIGN_TASK,$insert_sql_array);
			}
			
			if($task[assigned_to]){
				$insert_sql_array = array();
				$insert_sql_array['task_id'] = $task_id;
				$insert_sql_array['module'] = 'TBL_USER';
				$insert_sql_array['module_id'] = $task[assigned_to];
				$this->db->insert(TASK_RELATION,$insert_sql_array);
			}		
		} // AddTaskOnTheFly
	
	function addGlobalTask(){
		ob_start();
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
}
?>