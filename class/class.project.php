<?php
class Project extends Company_Global {

var $project_id;
var $db;
var $validity;
var $Form;
var $user_id;

	function __construct(){
	$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	$this->validity = new ClsJSFormValidation();
	$this->Form = new ValidateForm();
	}
	
	function addEditProject($runat,$project_id='',$parent=''){
		ob_start();
		
		switch($runat){
			case 'local':
				$formName='frm_addProject';
				$ControlNames=array("title"		=>array('title',"''","Please Enter Title !! ","title_span")
									);
				$ValidationFunctionName="CheckValidity";
				$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($formName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
				echo $JsCodeForFormValidation;
				if($project_id!=''){
					$sql_p = "select * from ".PROJECT." where project_id='$project_id'";
					$result_p = $this->db->query($sql_p,__FILE__,__lINE__);
					$row_p=$this->db->fetch_array($result_p);
				}
				if($parent){
					$sql_par = "select title from ".PROJECT." where project_id='$parent'";
					$result_par = $this->db->query($sql_par,__FILE__,__lINE__);
					$row_par=$this->db->fetch_array($result_par);
				}
				?>
				<div class="prl">&nbsp;</div>
				<div id="lightbox">
					<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle">Add Project</div>
					<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_project').style.display='none';">
					<img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;" class="form_main">			
				<ul id="error_list">
				  	<li><span id="title_span" class="normal"></span></li>
				</ul>
				<form metdod="post" action="save_project.php?page=project" enctype="multipart/form-data" name="<?php echo $formName; ?>">
				<table class="table" width="100%">
				
				<?php if($parent){?>
				<tr>
				<th colspan="3">Parent Project</th>
				</tr>
				<tr>
				<tr>
				  <td colspan="3">
				  <input type="text" name="parent_project" id="parent_project" style="width:90%" value="<?php echo $row_par[title]?>" readonly="true" />
				  <input type="hidden" name="parent_project_id" id="parent_project_id" value="<?php echo $parent;?>"/>				  </td>
				 </tr>
				<tr>
				<?php } ?>
				<tr>
				<th colspan="3">Project Title </th>
				</tr>
				<tr>
				  <td colspan="3"><input name="title" id="title" style="width:100%" value="<?php echo $row_p[title];?>" /></td>
				  </tr>
				<tr>
				  <th colspan="3">Project Description</th>
				  </tr>
				<tr>
				  <td colspan="3"><textarea name="desc" id="desc" style="width:100%;"><?php echo $row_p[description];?></textarea></td>
				  </tr>
				<tr>
				<td colspan="3"><table width="100%"><tr><?php echo $this->addPersonToProjectBox('','contact');?></tr></table></td>
				</tr>
				<tr>
				<td colspan="3"><table width="100%"><tr><?php echo $this->addPersonToProjectBox('','user');?></tr></table></td>
				  </tr>
				<tr>
<?php /*?>				  <th width="44%">Importance
				  <select name="importance_type_id" id="importance_type_id" style="width:70%">
				  <option value="" >--Select--</option>
					<?php
					$sql = "select * from ".IMPORTANCE_TYPE;
					$result = $this->db->query($sql,__FILE__,__lINE__);
					while($row=$this->db->fetch_array($result)){
					?>
				  	<option value="<?php echo $row['importance_type_id'];?>" 
					<?php if($row_p['importance_type_id']==$row['importance_type_id']) echo 'selected="selected"';?> >
					<?php echo $row['importance_type_value'];?></option>
					<?php } ?>
				  </select>				  </th>
				  <th width="6%">&nbsp;</th><?php */?>
				  <th width="23%">Department </th>
				  <td width = "70%">
				  <select name="department_id" id="department_id" style="width:70%">
				  <option value="" >--Select--</option>
					<?php
					$sql = "select * from ".DEPARTMENT;
					$result = $this->db->query($sql,__FILE__,__lINE__);
					while($row=$this->db->fetch_array($result)){
					?>
				  	<option value="<?php echo $row['department_id'];?>"
					<?php if($row_p['department_id']==$row['department_id']) echo 'selected="selected"';?> >
					<?php echo $row['department_value'];?></option>
					<?php } ?>
				  </select>				  </td>
				  </tr>
				<tr>
				  <th>Due Date
					<input type="text" name="due_dt" id="due_dt" value="<?php echo $row_p['due_date']; ?>"  readonly="true"/>
					<script type="text/javascript">
					 var exp_date;
					 function start_cal()  {
					  var cal11=new Calendar({
							  inputField   	: "due_dt",
							  dateFormat	: "%Y/%m/%d",
							  trigger		: "due_dt",
							  weekNumbers   : true,
							  bottomBar		: true,
							  showTime      : 12,
							  onSelect		: function() {
													this.hide();
													document.<?php echo $formName;?>.due_dt.value=this.selection.print("%Y/%m/%d");														
												},
					  });
					  }
					</script>				  </th>
				  
				  <th>Started
					<input type="text" name="started" id="started" value="<?php echo $row_p['started'];?>"  readonly="true"/>
					<script type="text/javascript">
					 var exp_date;
					 function start_cal1()  {
					  var cal11=new Calendar({
							  inputField   	: "started",
							  dateFormat	: "%Y/%m/%d",
							  trigger		: "started",
							  weekNumbers   : true,
							  bottomBar		: true,
							  showTime      : 12,
							  onSelect		: function() {
													this.hide();
													document.<?php echo $formName;?>.started.value=this.selection.print("%Y/%m/%d");														
												},
					  });
					  }
					</script>				  </th><th>&nbsp;</th>
				  </tr>
				<tr>
				  <th colspan="3"><input type="checkbox" value="yes" name="go_to_project" id="go_to_project" />
				  Go To Project On Submit </th>
				  </tr>
				<tr>
				  <th colspan="2">&nbsp;</th>
				  <th>
				  <?php if($project_id!=''){ ?>
				  <input type="hidden" name="project_id" id="project_id" value="<?php echo $project_id; ?>" />
				  <?php } ?>
				  <input type="submit" value="<?php if($project_id!='') echo 'Update'; else echo 'Add' ; ?>" name="submit" id="submit" 
				  onClick="  fillcontact();
				  			 if(<?php echo $ValidationFunctionName?>()) {
							 return PLX.Submit(form, {
									'preloader':'prl',
									'onFinish': function(response){
									document.getElementById('div_project').style.display='None';
									document.getElementById('div_project').innerHTML='';
									}
									});
							}
							else { return false; } " />				  </th>
				  </tr>
				</table>
				</form>
				</div></div></div>			
				<?php
				break;
			case 'server':
				$insert_sql_array = array();
				$insert_sql_array[title] = $_REQUEST['title'];
				$insert_sql_array[description] = $_REQUEST['desc'];
				$insert_sql_array[importance_type_id] = $_REQUEST['importance_type_id'];
				$insert_sql_array[department_id] = $_REQUEST['department_id'];
				$insert_sql_array[due_date] = $_REQUEST['due_dt'];
				$insert_sql_array[started] = $_REQUEST['started'];
				if($_REQUEST['project_id']!=''){
					$this->db->update(PROJECT,$insert_sql_array,'project_id',$_REQUEST['project_id']);
				}
				else {
					if($_REQUEST['parent_project_id']) {
						$insert_sql_array[parent_project_id] = $_REQUEST['parent_project_id'];
					}
					$insert_sql_array[user_id] = $_SESSION['user_id'];
					//print_r($insert_sql_array);
					$this->db->insert(PROJECT,$insert_sql_array);
					$project_id= $this->db->last_insert_id();
					//echo $project_id;
					echo $this->addPersonToProject('server',$project_id,'contact',$_REQUEST[contact],'add_project');
					echo $this->addPersonToProject('server',$project_id,'user',$_REQUEST[user_id],'add_project');
					if($_REQUEST['parent_project_id']) {
						$insert_sql_array1 = array();
						$insert_sql_array1[connected_project_id] = $project_id;
						$insert_sql_array1[project_id] = $_REQUEST['parent_project_id'];
						$this->db->insert(CONNECTED_PROJECT,$insert_sql_array1);
						?><script type="text/javascript">
						 document.getElementById('div_project').style.display='none';					 
						 project.showConnectedProject('<?php echo $_REQUEST['parent_project_id'];?>',{target : 'connected_project', preloader: 'prl'} );
						 </script><?php		
					 }
					
				}
				if(!$_REQUEST['parent_project_id']) {
				?>
				 <script type="text/javascript">
				 document.getElementById('div_project').style.display='none';
				 window.location = "project.php";
<?php /*?>				 project.projectList('', { target : 'div_project_list' , preloader: 'prl' });
				 $(function() {		
				 $('#search_table')
				 .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: { 2:{sorter: false} , 8:{sorter: false} } } )						
				 });<?php */?>
				 </script>
				<?php
				} 
				
				if($_REQUEST['go_to_project'] == 'yes'){
				?>
				<script>
					window.location = "project_profile.php?project_id=<?php echo $project_id; ?>";
				</script>
				<?php
				}
				break;
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function deleteProject($project_id){
		ob_start();		
		$sql = "delete from ".PROJECT." where project_id= ".$project_id;
		$this->db->query($sql,__FILE__,__lINE__);	
		
		$sql = "delete from ".CONNECTED_PROJECT." where project_id= ".$project_id;
		$this->db->query($sql,__FILE__,__lINE__);	

		$sql = "update ".PROJECT." set parent_project_id ='' where parent_project_id= ".$project_id;
		$this->db->query($sql,__FILE__,__lINE__);	

		?>
		<script type="text/javascript">
		 project.projectList('',
		{ onUpdate: function(response,root){
			 document.getElementById('div_project_list').innerHTML=response;
			 document.getElementById('div_project_list').style.display='';		
			 $(function() {		
			$('#search_table')
			.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: { } } )						
			});
			 }, preloader: 'prl'
		} );
		</script>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
		function projectList($parent_project_id='',$dd_from='', $dd_to='', $status='', $importance='', $department='', $user='', $contact='',$root_project='',$contact_id='',$finished='',$default=''){
		ob_start();

		$sql = "select distinct a.* from ".PROJECT." a";
		
		if($status or $finished){
			$sql .= ", ".PROJECT_STATUS." b";
		}
		if($user or $default){
			$sql .=', '.TBL_USER." c, ".USER_IN_PROJECT." e ";
		}
		if($contact){
			$sql .=', '.TBL_CONTACT." b, ".CONTACT_IN_PROJECT." f ";
		}

		$sql .= " where 1 ";

/*		if($user){
			$sql .= "and  b.contact_id=f.contact_id " ;
		}

		if($contact){
			$sql .= " and b.contact_id=f.contact_id ";
		}
*/

		if($dd_from){
			$sql .= " and a.due_date >= '$dd_from'";
		}

		if($dd_to){
			$sql .= " and a.due_date <= '$dd_to'";
		}

		if($status){
			$sql .= " and a.status_id='$status'";
		}

		if($title){
			$sql .= " and a.title='$title'";
		}

		if($importance){
			$sql .= " and a.importance_type_id='$importance'";
		}

		if($department){
			$sql .= " and a.department_id='$department'";
		}

		if($user){
			$sql .= " and (c.first_name like '%$user%' or c.last_name like '%$user%') and a.project_id = e.project_id and c.user_id = e.user_id";
		}
		else if($default=='default'){
			$sql .= " and c.user_id = $_SESSION[user_id] and a.project_id = e.project_id and c.user_id = e.user_id";
		}

		if($contact){
			$sql .= " and (b.first_name like '%$contact%' or b.last_name like '%$contact%') and a.project_id = f.project_id and b.contact_id = f.contact_id";
		}

		if($root_project){
			$sql .= " and a.parent_project_id = ''";
		}

		if($finished){
			$sql .= " and b.status !='Finished' and (b.status_id = a.status_id or a.status_id = '')";
		}

		if($contact_id!=''){
			$sql = "select distinct a.* from ".PROJECT." a, ".CONTACT_IN_PROJECT." b where a.project_id=b.project_id and b.contact_id = '$contact_id'";
		}
		//echo $sql;
		//$sql .=" limit 100";
		$sql .= " and a.title != ''";
		//echo $sql;
		$result = $this->db->query($sql,__FILE__,__lINE__);
		?>
		<table id="search_table" class="event_form small_text" width="100%">
		<thead>
		<tr>
		<th><?php if(!$parent_project_id) echo 'Project Id.::'; else echo'&nbsp;';?></th>
		<th>Title::</th>
		<th>Due Date::</th>
		<th>Started::</th>
		<th>Status::</th>
		<?php /*?><th>Me To Project::</th><?php */?>
		<?php if(!contact_id) { ?><th>Action</th><?php } ?>
		</tr>
		</thead>
		<tbody>

		<?php $i=0; while($row=$this->db->fetch_array($result)){ $i+=1;	?>
		<tr>
		<td><?php echo $row[project_id]; ?></td>
		<td><a href="project_profile.php?project_id=<?php echo $row[project_id]; ?>"><?php echo $row[title]; ?></td>
		<td><?php echo $row[due_date]; ?></td>
		<td><?php echo $row[started]; ?></td>
		<td>
			<?php
			$sql_p1= "select * from ".PROJECT." where project_id= '$row[project_id]'";
			$result_p1 = $this->db->query($sql_p1,__FILE__,__lINE__);		
			$row_p1=$this->db->fetch_array($result_p1);
 
			$sql_status = "select * from ".PROJECT_STATUS." where status_id = '$row_p1[status_id]'";
			$result_status = $this->db->query($sql_status,__FILE__,__lINE__);	?>
			<?php $row_status=$this->db->fetch_array($result_status); ?>
			
			<div id="status_select_box_<?php echo $row[project_id]; ?>"> 
				<?php echo $this->returnLink($row_status[status],$row[project_id]); ?>
		    </div>		
		</td>
		<?php /*?><td><?php echo $row3[me_to_project_value]; ?></td><?php */?>
		<?php if(!$contact_id) {?><td>
		<?php if($parent_project_id){ ?>
		<a  href="#"
		 onclick="javascript: project.addParentProject('<?php echo $parent_project_id;?>','<?php echo $row['project_id'];?>',
		 						{ target : 'div_project', preloader: 'prl'} ); return false;" >Select</a>
		<?php }
		else { ?>
		<a  href="#"
		 onclick="javascript: project.addEditProject('local','<?php echo $row['project_id'];?>','','div_project',
							{ onUpdate: function(response,root){
									 document.getElementById('div_project').innerHTML=response;
									 document.getElementById('div_project').style.display='';
									 start_cal();
									 start_cal1();
									 initializeFacebook();
									 }, preloader: 'prl'
								} ); return false;" >Edit</a>/
		<a href="#" onclick="javascript: if(confirm('Are you sure?')){
								project.deleteProject('<?php echo $row['project_id'];?>',
								{preloader: 'prl'} );
							} return false;" ><img src="images/trash.gif" border="0" /></a>
		<?php } ?>
		</td>
		<?php } ?>
		</tr>
		<?php }
		if($i==0){
			?><tr><td colspan="8" align="center">No Record To Display !!</td></tr><?php
		}
		?>
		</table>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function addParentProject($project_id='',$parent_project_id='') {
		ob_start();
		
		$sql = "select parent_project_id from ".PROJECT." where project_id='$parent_project_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		$row=$this->db->fetch_array($result);
		if($row[parent_project_id]){
		$sql = "delete from ".CONNECTED_PROJECT." where project_id='$parent_project_id' and connected_project_id='$project_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		}
		
		$insert_sql_array[parent_project_id] = $parent_project_id;
		$this->db->update(PROJECT,$insert_sql_array,'project_id',$project_id);
		
		$insert_sql_array1[connected_project_id] = $project_id;
		$insert_sql_array1[project_id] = $parent_project_id;
		$this->db->insert(CONNECTED_PROJECT,$insert_sql_array1);
		
		?>
		<script type="text/javascript">
		document.getElementById('div_project').style.display='none';
		project.showParentProject('<?php echo $project_id;?>', { target : 'parent_project' , preloader: 'prl' });
		</script>
		<?php


		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function returnProjectHead($project_id){
		ob_start();
		$sql = "select * from ".PROJECT." where project_id='$project_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		$row=$this->db->fetch_array($result);
		
		?><h2><?php echo $row[title];?>&nbsp;&nbsp;<a style="font-weight:normal;"  href="#"
											 onclick="javascript: project.addEditProject('local','<?php echo $project_id;?>','','div_project',
																{ onUpdate: function(response,root){
																		 document.getElementById('div_project').innerHTML=response;
																		 document.getElementById('div_project').style.display='';
																		 start_cal();
																		 start_cal1();
																		 initializeFacebook();
																		 }, preloader: 'prl'
																	} ); return false;" >edit</a></h2>
		<p style=" font-size: 12px; font-weight: normal;"><?php echo 'Submitted : '.date('Y/m/d h:i:s A',strtotime($row[cur_timestamp]));?></p><?
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	
	function projectProfile($project_id){
		$sql = "select * from ".PROJECT." where project_id='$project_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		$row=$this->db->fetch_array($result);
		
		$sql1 = "select importance_type_value from ".IMPORTANCE_TYPE." where importance_type_id='$row[importance_type_id]'";
		$result1 = $this->db->query($sql1,__FILE__,__lINE__);
		$row1=$this->db->fetch_array($result1);
		
		$sql2 = "select department_value from ".DEPARTMENT." where department_id='$row[department_id]'";;
		$result2 = $this->db->query($sql2,__FILE__,__lINE__);
		$row2=$this->db->fetch_array($result2);

		?>
<?php /*?>		<div class="contact_form">
		<div class="profile_box1">
		<form name="frm_date" action="" method="post"/>
		<table class="table" width="100%">
		<tr>
		<th>Started</th>
		<td>
		<div id="div_started"><?php echo $this->returnStarted($row['project_id']);?></div>
		<input type="text" name="started" id="started" value="<?php echo $row['started'];?>"  readonly="true"/>
					<script type="text/javascript">
					 var exp_date;
					 function start_cal1()  {
					  var cal11=new Calendar({
							  inputField   	: "started",
							  dateFormat	: "%Y/%m/%d",
							  trigger		: "started",
							  weekNumbers   : true,
							  bottomBar		: true,
							  showTime      : 12,
							  onSelect		: function() {
													this.hide();
													document.frm_date.started.value=this.selection.print("%Y-%m-%d");														
												},
					  });
					  }
					 start_cal1();
					</script>
					<a  href="#" onclick="javascript: 
								project.updateField('started',document.frm_date.started.value,'<?php echo $row['project_id'];?>',
								'div_started',
								{ onUpdate: function(response,root){
										 document.getElementById('div_started').innerHTML=response;
										 document.getElementById('div_started').style.display='';
										 project.returnStarted('<?php echo $project_id;?>',{target : 'div_started',preloader: 'prl'});		
										 }, preloader: 'prl'
									} ); return false;" >
								Update</a>/

					<a  href="#"
							onclick="javascript: document.frm_date.started.value= document.frm_date.started_p.value; return false;" >Cancel</a>

					</td>
					
		<th>Due- Date </th>
		<td>
		<div id="div_dd"><?php echo $this->returnDueDate($project_id);?></div>
		<input type="text" name="due_dt" id="due_dt" value="<?php echo $row['due_date']; ?>"  readonly="true"/>
					<script type="text/javascript">
					 var exp_date;
					 function start_cal2()  {
					  var cal11=new Calendar({
							  inputField   	: "due_dt",
							  dateFormat	: "%Y/%m/%d",
							  trigger		: "due_dt",
							  weekNumbers   : true,
							  bottomBar		: true,
							  showTime      : 12,
							  onSelect		: function() {
													this.hide();
													document.frm_date.due_dt.value=this.selection.print("%Y-%m-%d");														
												},
					  });
					  }
					  start_cal2();
					</script>
					<a  href="#" onclick="javascript: 
								project.updateField('due_date',document.frm_date.due_dt.value,'<?php echo $project_id;?>',
								'div_dd',
								{ onUpdate: function(response,root){
										 document.getElementById('div_dd').innerHTML=response;
										 document.getElementById('div_started').style.display='';
										 project.returnDueDate('<?php echo $project_id;?>',{target : 'div_dd',preloader: 'prl'});		
										 }, preloader: 'prl'
									} ); return false;" >
								Update</a>/

					<a  href="#"
							onclick="javascript: document.frm_date.due_dt.value= document.frm_date.due_dt_p.value; return false;" >Cancel</a>
					</td>
		</tr>
		</table>
		</form>
		</div></div>
<?php */?>		
		<div class="contact_form">
		<div class="profile_box1" style="font-weight:bold;">
		<a style="color:#FF0000; font-size:15px" onclick="javascript: if(this.innerHTML=='-'){
																this.innerHTML = '+';
																document.getElementById('description').style.visibility = 'visible';
																}
																else {
																this.innerHTML = '+';
																document.getElementById('description').style.visibility = 'hidden';
																this.innerHTML = '-';
																} ">-</a>&nbsp;Project Description</div> 
		<div  id="description"  class="contact_form" style="visibility:visible">
		  <textarea name="desc" id="desc" style="width:100%;height:200px;" readonly="true" ondblclick="this.readOnly=false" 
		  onblur="javascript: project.updateField('description',this.value,'<?php echo $project_id;?>',{ preloader: 'prl' });
		  			this.readOnly=true;" ><?php echo $row[description];?></textarea></div>
		</div>
		
		
		<div class="contact_form">
			<div class="profile_box1" style="font-weight:bold;"><a style="color:#FF0000; font-size:15px" onclick="javascript: if(this.innerHTML=='+'){
		  														this.innerHTML = '-';
																document.getElementById('connection').style.display = 'block';
																}
																else {
																this.innerHTML = '+';
																document.getElementById('connection').style.display = 'none';
																} ">+</a>&nbsp;Connection</div>
		<div id="connection" class="contact_form" style="display:none">
			<div class="contact_form">
			<div class="profile_box1">
			<table class="table" width="100%">
			<tr>
			  <th width="40%">Parent Project</th>
			  <th width="60%"><a  href="#"
								onclick="javascript: project.addProject('local','<?php echo $project_id;?>','parent','div_project',
									{ onUpdate: function(response,root){
											 document.getElementById('div_project').innerHTML=response;
											 document.getElementById('div_project').style.display='';
											 start_cal();
											 end_cal();
											 initializeFacebook();	
											 }, preloader: 'prl'
										} ); return false;" ><?php if($row['parent_project_id']) echo 'update'; else echo 'add';?></a></th>
			</tr>
			<tr>
			  <td colspan="2"><div id="parent_project"><?php echo $this->showParentProject($project_id);?></div></td>
			</tr>
			</table>
			</div></div>
			
			<div class="contact_form">
			<div class="profile_box1">
			<table class="table" width="100%">
			<tr>
			  <th width="40%">Connected Project</th>
			  <th width="60%"><a  href="#"
								onclick="javascript: project.addEditProject('local','','<?php echo $project_id;?>','div_project',
									{ onUpdate: function(response,root){
											 document.getElementById('div_project').innerHTML=response;
											 document.getElementById('div_project').style.display='';		
											 start_cal();
											 start_cal1();
											 initializeFacebook();
											 }, preloader: 'prl'
										} ); return false;" >add</a></th>
			</tr>
			<tr>
			  <td colspan="2"><div id="connected_project"><?php echo $this->showConnectedProject($project_id);?></div></td>
			</tr>			
			</table>
			</div></div>
			</div>
		</div>

 		<?php
		$sql_bug_check="select distinct a.* from ".TBL_BUG." a, ".PROJECT." b, ".TBL_BUG_LINKER." c where b.project_id = c.project_id and b.project_id = '$project_id' and  a.bug_id = c.bug_id";
		$result_bug_check = $this->db->query($sql_bug_check,__FILE__,__LINE__);
		if($this->db->num_rows($result_bug_check) > 0) {
		?>
		
		<div class="contact_form">
		<div class="profile_box1">		
		<table class="table" width="100%" style="font-weight:bold;">		
		<tr>
		  <th width="100%">
		  <a style="color:#FF0000; font-size:15px" onclick="javascript: if(this.innerHTML=='+'){
		  														this.innerHTML = '-';
																document.getElementById('bug_info_div').style.display = 'block';
																}
																else {
																this.innerHTML = '+';
																document.getElementById('bug_info_div').style.display = 'none';
																} ">+</a>&nbsp;Bug Details&nbsp;
		<a style="right:20px;" onclick="javascript: document.getElementById('bug_info_div').style.display = 'block';
													project.displayProjectBugInfo('<?php echo $project_id; ?>',
																				 {target:'bug_info_div',preloader:'prl'});">Refresh List</a>
		 </th>
		</tr>
		<tr>
		  <td>
		  <div id="bug_info_div" class="contact_form" style="display:none;"><?php echo $this->displayProjectBugInfo($project_id);?></div>
		  </td>
		</tr>
		
		</table>
		
		</div></div>
		
		<?php
	}
 }
	
	function displayProjectBugInfo($project_id){
		ob_start();
		$sql_bug_info="select distinct a.* from ".TBL_BUG." a, ".PROJECT." b, ".TBL_BUG_LINKER." c where b.project_id = c.project_id and b.project_id = '$project_id' and  a.bug_id = c.bug_id order by importance asc";
		$result_bug_info = $this->db->query($sql_bug_info,__FILE__,__LINE__);
		?><table width="100%" class="table" border="1px #ccc solid;">
		<tr>
		<th>Variable Name</th>
		<th>Value</th>
		<th>Importance</th>
		</tr><?php 
		while($row_bug_info = $this->db->fetch_array($result_bug_info)){ 
			if($row_bug_info[importance]=='high'){
			?>		
			<tr bgcolor="#e9e9e9" style="font-weight: bold;">
			<td><?php echo $row_bug_info[variable];?></td>
			<td><?php 
				if(strlen($row_bug_info[value]) > 100 ) echo str_replace('","','",<br>"',$row_bug_info[value]);
				else echo $row_bug_info[value];?></td>
			<td><input type="checkbox" checked="true" onClick="javascript: if(this.checked == true) 
													project.setImportance('<?php echo $row_bug_info[bug_info_id]; ?>','high',{preloader:'prl'});	
												else 
													project.setImportance('<?php echo $row_bug_info[bug_info_id]; ?>','normal',{preloader:'prl'});" /></td>
			</tr>
			<?php 
				}
			else{
			?>		
			<tr bgcolor="#FFFFFF">
			<td><?php echo $row_bug_info[variable];?></td>
			<td><?php 
				if(strlen($row_bug_info[value]) > 100 ) echo str_replace('","','",<br>"',$row_bug_info[value]);
				else echo $row_bug_info[value];?></td>
			<td><input type="checkbox" onClick="javascript: if(this.checked == true) 
													project.setImportance('<?php echo $row_bug_info[bug_info_id]; ?>','high',{preloader:'prl'});	
												else 
													project.setImportance('<?php echo $row_bug_info[bug_info_id]; ?>','normal',{preloader:'prl'});" /></td>
			</tr>
			<?php 
				}
			}
		?></table><?php 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	
	}
	
	function setImportance($bug_info_id,$type=''){
		ob_start();
		$sql="update ".TBL_BUG." set importance = '$type' where bug_info_id = '$bug_info_id'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function addProject($runat,$project_id,$relation){
		ob_start();
		switch($runat){
			case 'local':	
				?>
				<div class="prl">&nbsp;</div>
				<div id="lightbox">
					<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle"><?php if($relation=='parent') echo 'Add Parent Project'; else echo 'Add Connected Project';?></div>
					<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_project').style.display='none';">
					<img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;" class="form_main">			
				<ul id="error_list">
					<li><span id="project_span" class="normal"></span></li>
				</ul>
				<?php echo $this->searchboxProject($project_id); ?>
				<div id="div_project_list"><?php 
				echo $this->projectList($project_id,'','','','','','','','yes','','no','default'); 
				?></div>
				</div></div></div>
				<?php
				break;
				
			case 'server':
				


				$insert_sql_array = array();
				$insert_sql_array1 = array();
				if($relation=='parent'){

					$sql = "select parent_project_id from ".PROJECT." where project_id='$_REQUEST[project_id_new]'";
					$result = $this->db->query($sql,__FILE__,__lINE__);		
					$row=$this->db->fetch_array($result);
					if($row[parent_project_id]){
					$sql = "delete from ".CONNECTED_PROJECT." where project_id='$_REQUEST[project_id_new]' and connected_project_id='$_REQUEST[project_id]'";
					$result = $this->db->query($sql,__FILE__,__lINE__);		
					}
				
					$insert_sql_array[parent_project_id] = $_REQUEST['project_id_new'];
					$this->db->update(PROJECT,$insert_sql_array,'project_id',$project_id);
					
					$insert_sql_array1[connected_project_id] = $_REQUEST['project_id'];
					$insert_sql_array1[project_id] = $_REQUEST['project_id_new'];
					$this->db->insert(CONNECTED_PROJECT,$insert_sql_array1);
				}
				else {
					$insert_sql_array[connected_project_id] = $_REQUEST['project_id_new'];
					$insert_sql_array[project_id] = $_REQUEST['project_id'];
					$this->db->insert(CONNECTED_PROJECT,$insert_sql_array);
					
					$insert_sql_array1[parent_project_id] = $_REQUEST['project_id'];
					$this->db->update(PROJECT,$insert_sql_array1,'project_id',$project_id);
				}
				?>
				<script type="text/javascript">
				document.getElementById('div_project').style.display='none';
				project.showParentProject('<?php echo $project_id;?>', { target : 'parent_project' , preloader: 'prl' });
				</script>
				<?php

				
				break;
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function updateField($field='',$value='',$project_id=''){
		ob_start();
		$sql = "update ".PROJECT." set $field= '$value' where project_id='$project_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	
	
	function returnStarted($project_id=''){
		ob_start();
		$sql = "select started from ".PROJECT." where project_id='$project_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		$row=$this->db->fetch_array($result);
		?><input type="hidden" name="started_p" id="started_p" value="<?php echo $row['started'];?>"/><?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	

	function returnDueDate($project_id=''){
		ob_start();
		$sql = "select due_date from ".PROJECT." where project_id='$project_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		$row=$this->db->fetch_array($result);
		?><input type="hidden" name="due_dt_p" id="due_dt_p" value="<?php echo $row['due_date'];?>"/><?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	


	function returnTitle($project_id=''){
		ob_start();
		$sql = "select started from ".PROJECT." where project_id='$project_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		$row=$this->db->fetch_array($result);
		?><input type="hidden" name="started_p" id="started_p" value="<?php echo $row['started'];?>"/><?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	

	function returnDescription($project_id=''){
		ob_start();
		$sql = "select started from ".PROJECT." where project_id='$project_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		$row=$this->db->fetch_array($result);
		?><input type="hidden" name="started_p" id="started_p" value="<?php echo $row['started'];?>"/><?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	



	function addDocuments($runat,$project_id,$doc_id='',$document_name='',$document_server_name='',$user_id='') {
		$this->project_id=$project_id;
		switch($runat) {
			case 'local':
				$sql = "select * from ".PROJECT_DOCUMENT." where document_id = '".$doc_id."'";
				$result = $this->db->query($sql,__FILE__,__lINE__);
				$row=$this->db->fetch_array($result);
				?>
				<script language="javascript" type="text/javascript">
				function startUpload(){
					  document.getElementById('upload_process').style.visibility = 'visible';
					  document.getElementById('upload_form').style.visibility = 'hidden';					  
					  return true;
				}
				
				function stopUpload(success,error){
				
					  if (success == 1){
						alert('Document Uploaded Successfully..');
						window.location="project_profile.php?project_id=<?php echo $this->project_id; ?>";
						}
					  else if(success == 2){
						alert('Record Updated Successfully..');
						window.location="project_profile.php?project_id=<?php echo $this->project_id; ?>";
						}
					  else if(success == 3){
						 result = '<span>Selected File Is Of 0 Bytes!!<br/><\/span><br/><br/>';
						 document.getElementById('upload_process').style.visibility = 'hidden';
					  	 document.getElementById('upload_form').innerHTML = result + '<label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>';
						 document.getElementById('upload_form').style.visibility = 'visible';
					  	}
					 else if(success == 0){
						 result = '<span>One Or More Fields are Empty!!<\/span><br/><br/>';
						 document.getElementById('upload_process').style.visibility = 'hidden';
					  	 document.getElementById('upload_form').innerHTML = result + '<label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>';
						 document.getElementById('upload_form').style.visibility = 'visible';
					  	}else
					  
					        
					  return true;   
				}
				
				</script> 			   
			   	
				
				<div class="prl">&nbsp;</div>
               <div id="lght1" style="margin-top:-400px;!important;">
				<div id="lightbox" style="position:absolute;!important;" >		
				<div style="background-color:#ADC2EB;" class="ajax_heading">
					<div id="TB_ajaxWindowTitle"><?php if($doc_id) echo 'Edit Document'; else echo'Add Document' ?> </div>
					
					<div id="TB_closeAjaxWindow"><a href="project_profile.php?project_id=<?php echo $this->project_id; ?>" 
					onclick="javascript: document.getElementById('upload_target').display='none';">
					<img border="0" src="images/close.gif" alt="close" /></a></div>	
					</div>
				<div  class="white_content"> 
				<p id="upload_process" style="visibility:hidden"><img src="loader.gif" /></p>
				
				
				
				<form action="upload.php?project_id=<?php echo $this->project_id; if($doc_id) echo '&doc_id='.$doc_id.'&document_server_name='.$row['document_server_name']?>&flag=project&user_id=<?php echo $user_id;?>" id="upload_form" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" >
					 <table width="100%" class="table">   
					 <tr><?php /*?><td>
                         Document Status:
						 </td>
						 <td>  
                              <select name="document_status">
							  <option value="" selected="selected">--Select--</option>
							  <option value="Team Lead" <?php if($row['document_status']=='Team Lead') echo 'selected="selected"';?> >Team Lead</option>
							  <option value="User" <?php if($row['document_status']=='User') echo 'selected="selected"';?> >User</option>

							  <option value="Internal Staff" <?php if($row['document_status']=='Internal Staff') echo 'selected="selected"';?> >Internal</option>
						
							  </select>
                         </td><?php */?>
						 <td>File:</td>
						 <td>  
                              <input name="myfile" type="file" size="30" value=''/>
                         </td>
                         <td>
                             <input type="submit" name="submitBtn" class="sbtn" value="Upload" />
                         </td>

					</tr>
					</table>
                     </p>
					 </form>
					 </div>
					 </div></div>		 					
	
				<?php  
				break;
				
				
			case 'server':
				
				$this->document_name=$document_name;
				$this->document_server_name=$document_server_name;
				$this->$user_id = $user_id;
				if($doc_id){
					$update_sql_array = array();
					$this->db->update(PROJECT_DOCUMENT,$update_sql_array,'document_id',$doc_id);				
					}
				else {
					
					$insert_sql_array = array();
					$insert_sql_array[project_id] = $this->project_id;
					$insert_sql_array[document_name] = $this->document_name;
					$insert_sql_array[document_server_name] = $this->document_server_name;	
					$insert_sql_array[user_id] = $this->$user_id ;	
					$this->db->insert(PROJECT_DOCUMENT,$insert_sql_array);	
				}				
				break;
				
				
						
			}		
		}//end of add document function

	function deleteDocument($project_id,$doc_id){
		ob_start();		
		
		$sql = "select * from ".PROJECT_DOCUMENT." where document_id = '".$doc_id."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		$row=$this->db->fetch_array($result);
		unlink("uploads/".$row[document_server_name]);		
		$sql = "delete from ".PROJECT_DOCUMENT." where document_id= ".$doc_id;
		$this->db->query($sql,__FILE__,__lINE__);	
				
		?>
		<script type="text/javascript">
		project.showDocuments('<?php echo $project_id ?>',{target:'documents'});
		</script>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
		
	function showDocuments($project_id) {		
		
		ob_start();	
		$this->project_id=$project_id;
		$sql = "select a.*,b.first_name,b.last_name from ".PROJECT_DOCUMENT." a, ".TBL_USER." b where a.user_id=b.user_id and a.project_id = '".$this->project_id."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		?>		
		<table width="100%" >
		<?php while($row=$this->db->fetch_array($result)) {?>
		<tr>
		<td  width="25%"><a href="uploads/<?php echo $row[document_server_name]; ?>" target="_blank"> <?php echo $row[document_name]; ?> </a></td>
	<?php /*?>		<td > <?php echo $row[document_status]; ?> </td>
	<td><a href="project_profile.php?project_id=<?php echo $event_id; ?>&action=upload&doc_id=<?php echo $row['document_id'];?>">edit</a></td><?php */?>
		<td  width="25%" ><a href="#" onclick="javascript: if(confirm('Are you sure?')){project.deleteDocument('<?php echo $this->project_id; ?>','<?php echo $row['document_id'];?>', {preloader: 'prl'} ); } return false;" ><img src="images/trash.gif" border="0" /></a></td>
		<td  width="50%" align="right"><?php echo 'User: '.date('Y/m/d h:i:s A',strtotime($row[cur_timestamp]));?>&nbsp;&nbsp;&nbsp;&nbsp;
		<?php echo $row[first_name].' '.$row[last_name];?></td>
		</tr>
		<?php } ?>				
</table>	
		<?php	
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function projectStat($project_id){
		$sql_p= "select * from ".PROJECT." where project_id= $project_id";
		$result_p = $this->db->query($sql_p,__FILE__,__lINE__);		
		$row_p=$this->db->fetch_array($result_p);
		?>
		<table class="table" width="100%">
		<tr><th>Project Status ::</th></tr>
		<tr><td><?php 
		$sql = "select * from ".PROJECT_STATUS;
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		?><select name="status_id" id="status_id"  onchange="project.updateField('status_id',this.value,'<?php echo $project_id;?>',{ preloader: 'prl' });" >
		<option value="" >--Select--</option>
		<?php while($row=$this->db->fetch_array($result)){ ?>
		<option value="<?php echo $row[status_id]; ?>" <?php if($row[status_id]==$row_p[status_id]) echo 'selected="selected"';?> >
		<?php echo $row[status]; ?> </option>
		<?php } ?>
		</select>
		</td></tr>
		
		<tr><td>&nbsp;</td></tr>
		
<?php /*?>		<tr><th>Project Importance ::</th></tr>
		<tr><td><?php 
		$sql = "select * from ".IMPORTANCE_TYPE;
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		?><select name="importance_type_id" id="importance_type_id" onchange="project.updateField('importance_type_id',this.value,'<?php echo $project_id;?>',{ preloader: 'prl' });">
		<option value="" >--Select--</option>
		<?php while($row=$this->db->fetch_array($result)){ ?>
		<option value="<?php echo $row[importance_type_id]; ?>" <?php if($row[importance_type_id]==$row_p[importance_type_id]) echo 'selected="selected"';?> >
		<?php echo $row[importance_type_value]; ?> </option>
		<?php } ?>
		</select>
		</td></tr>

		<tr><td>&nbsp;</td></tr>
		
		<tr><th>Department ::</th></tr>
		<tr><td><?php 
		$sql = "select * from ".DEPARTMENT;
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		?><select name="department_id" id="department_id" onchange="project.updateField('department_id',this.value,'<?php echo $project_id;?>',{ preloader: 'prl' });">
		<option value="" >--Select--</option>
		<?php while($row=$this->db->fetch_array($result)){ ?>
		<option value="<?php echo $row[department_id]; ?>" <?php if($row[department_id]==$row_p[department_id]) echo 'selected="selected"';?> >
		<?php echo $row[department_value]; ?> </option>
		<?php } ?>
		</select>
		</td></tr>

		<tr><td>&nbsp;</td></tr>
		
		<tr><th>% Complete ::</th></tr>
		<tr><td> 
		<select name="complete" id="complete" onchange="project.updateField('complete',this.value,'<?php echo $project_id;?>',{ preloader: 'prl' });">
		<option value="" >--Select--</option>
		<?php for($i=10;$i<=100;$i+=10){ ?>
		<option value="<?php echo $i; ?>" <?php if($i==$row_p[complete]) echo 'selected="selected"';?> >
		<?php echo $i; ?> </option>
		<?php } ?>
		</select>
		</td></tr>
<?php */?>
		<tr><td>&nbsp;</td></tr>
		
		<tr><th>User Involved In Project ::
		<a  href="#"
		onclick="javascript: project.addPersonToProject('local','<?php echo $project_id;?>','user','div_project',
			{ onUpdate: function(response,root){
					 document.getElementById('div_project').innerHTML=response;
					 document.getElementById('div_project').style.display='';
					 }, preloader: 'prl'
				} ); return false;" >add</a>
		</th></tr>
		<tr><td>
		<div id="user_list"><?php echo $this->userList($project_id);?></div>
		</td></tr>

		<tr><td>&nbsp;</td></tr>
		
		<tr><th>Contacts ::
		<a  href="#"
		onclick="javascript: project.addPersonToProject('local','<?php echo $project_id;?>','contact','div_project',
			{ onUpdate: function(response,root){
					 document.getElementById('div_project').innerHTML=response;
					 document.getElementById('div_project').style.display='';
					 initializeFacebook();
					}, preloader: 'prl'
				} ); return false;" >add</a>
		</th></tr>
		<tr><td>
		<div id="contact_list"><?php echo $this->contactList($project_id);?></div>
		</td></tr>

		</table>
		<?php
	}
	function addPersonToProjectBox($project_id,$role='',$person_id=''){
		ob_start();
		?>
			<th width="20%"><?php if($role=='contact') echo 'Select Contact'; else echo 'Select User';?></th>
			<td width="70%">
			<?php 
			if($role=='contact')
			$sql = "select contact_id,first_name,last_name from ".TBL_CONTACT."  where contact_id not in( select distinct(contact_id) from ".CONTACT_IN_PROJECT." where project_id='$project_id')"; 
			else 
			$sql = "select user_id,first_name,last_name from ".TBL_USER."  where user_id not in( select distinct(user_id) from ".USER_IN_PROJECT." where project_id='$project_id')";	
			
			$result = $this->db->query($sql,__FILE__,__lINE__);			

			if($role=='contact') { ?>
				<select id="contact_id" name="contact_id">
				</select>

				<input type="hidden" value="" name="contact" id="contact" /> 
			<?php }
			else { ?>
				<select name="user_id" id="user_id">
				<option value="">--Select--</option>
				<?php while($row=$this->db->fetch_array($result)){ ?>
				<option value="<?php if($role=='contact') echo $row[user_id]; else echo $row[user_id]; ?>">
				<?php echo $row[first_name].' '.$row[last_name]; ?></option>
				<?php } ?>
				</select>
			<?php } ?>
			</td>
			<script>
			function fillcontact(){
				for(i=0; i<document.getElementById('contact_id').length; i++){
					if(document.getElementById('contact_id')[i].selected==true) {
						document.getElementById('contact').value += document.getElementById('contact_id')[i].value+','; 
					}
				}
			}				
			</script>
		<?php 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	function addPersonToProject($runat,$project_id,$role='',$person_id='',$location=''){
		ob_start();
		switch($runat){
			case 'local':	
				$formName='frm_addperson';
				if($role=='contact')
				$ControlNames=array("contact"	=>array('contact',"''","Please Select Someone !! ","person_span") );
				else
				$ControlNames=array("user_id"	=>array('user_id',"''","Please Select Someone !! ","person_span") );
				
				$ValidationFunctionName="CheckValidity";
				$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($formName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
				echo $JsCodeForFormValidation;
				?>
				<div class="prl">&nbsp;</div>
				<div id="lightbox">
					<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle"><?php if($role=='contact') echo 'Add Contact'; else echo 'Add User';?></div>
					<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_project').style.display='none';">
					<img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;" class="form_main">			
				<ul id="error_list">
					<li><span id="person_span" class="normal"></span></li>
				</ul>
				<form metdod="post" action="" enctype="multipart/form-data" name="<?php echo $formName; ?>">
				<table class="table" width="100%" >
				<tr>
				<?php echo $this->addPersonToProjectBox($project_id,$role,$person_id);?>
				<?php if($location !='add_project'){ ?>
				<td><input type="button" value="Add" name="submit" id="submit" 
				  onClick="<?php if($role=='contact') { echo 'fillcontact();'; }?>		
							if(<?php echo $ValidationFunctionName?>()) {
							project.addPersonToProject('server','<?php echo $project_id;?>','<?php echo $role;?>',
							this.form.<?php if($role=='contact') echo 'contact'; else echo 'user_id' ;?>.value,
							'div_project',{ onUpdate: function(response,root){
									 document.getElementById('div_project').innerHTML=response;
									 document.getElementById('div_project').style.display='';
									}, preloader: 'prl'
								} );			
							}" /></td> <?php } ?>
				</tr>
				</table>
				</form>
				</div></div></div>
				<?php
				break;
				
			case 'server':
				if(isset($_REQUEST) && $location!='add_project'){
					extract($_REQUEST);
					if($role=='contact'){ 
						$person_id = $_REQUEST['plxa']['3'];
						}
					else {
						//$person_id = $user_id;
                          $person_id = $_REQUEST['plxa']['3'];
					}
				}

				$insert_sql_array = array();
				if($role=='contact'){
					foreach(explode("," , $person_id, -1) as $arr){
						$insert_sql_array[contact_id] = $arr;
						$insert_sql_array[project_id] = $project_id;
						$this->db->insert(CONTACT_IN_PROJECT,$insert_sql_array);
					}
				}
				else {
					$insert_sql_array[user_id] = $person_id;
					$insert_sql_array[project_id] = $project_id;
					$this->db->insert(USER_IN_PROJECT,$insert_sql_array);
				}
                                $debug = $_GET["debug"];
                                if( $debug == "yes"){
                                echo "<textarea style='position: absolute; bottom: 0; left: 0; width: 400px; height: 300px;'>";
                                    echo "insert_sql_array\n";
                                    print_r($insert_sql_array);
                                    echo "\nREQUEST\n";
                                    print_r( $_REQUEST);
                                    echo "\nTEST";
                                    echo $person_id = $_REQUEST['plxa']['3'];
                                    echo "</textarea>";
                                }
                                if($location!='add_project'){
				?>
                               
				<script type="text/javascript">
				document.getElementById('div_project').style.display='none';
				project.userList('<?php echo $project_id;?>', { target : 'user_list' , preloader: 'prl' });
				project.contactList('<?php echo $project_id;?>', { target : 'contact_list' , preloader: 'prl' });
				</script>
				<?php
				}
				
				break;
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function userList($project_id){
		ob_start();
		$sql = "select b.user_id,b.first_name, b.last_name from ".USER_IN_PROJECT." a, ".TBL_USER." b where a.project_id='$project_id' and a.user_id=b.user_id";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		?><table class="table" width="100%"><?php
		while($row=$this->db->fetch_array($result)){
			?>
			<tr>
			<td><?php echo $row[first_name].' '.$row[last_name];?></td>
			<td><a  href="javascript:void(0);" onclick="javascript: project.deleteUserFromProject('<?php echo $project_id;?>',
																'<?php echo $row['user_id'];?>',{ preloader: 'prl'} );" >Del</a></td>
			</tr><?php
		}	
		?>
		</table>
		<?php	
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	
	function contactList($project_id){
		ob_start();
		$sql = "select b.contact_id,b.first_name, b.last_name from ".CONTACT_IN_PROJECT." a, ".TBL_CONTACT." b where a.project_id='$project_id' and a.contact_id=b.contact_id";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		?><table class="table" width="100%"><?php
		while($row=$this->db->fetch_array($result)){
			?><tr>
			<td><?php echo $row[first_name].''.$row[last_name];?></td>
			<td><a  href="javascript:void(0);" onclick="javascript: project.deleteUserFromProject('<?php echo $project_id;?>',
																'<?php echo $row['contact_id'];?>',{ preloader: 'prl'} );" >Del</a></td>
			</tr><?php
		}	
		?>
		</table>
		<?php	
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function deleteUserFromProject($project_id='',$user_id=''){
		ob_start();
		$sql = "delete from ".USER_IN_PROJECT." where project_id='$project_id' and user_id='$user_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		?><script>
		project.userList('<?php echo $project_id;?>', { target : 'user_list' , preloader: 'prl' });
		</script><?php 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} 
	
	function deleteContactFromProject($project_id='',$contact_id=''){
		ob_start();
		$sql = "delete from ".CONTACT_IN_PROJECT." where project_id='$project_id' and contact_id='$contact_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		?><script>
		project.contactList('<?php echo $project_id;?>', { target : 'contact_list' , preloader: 'prl' });
		</script><?php 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} 
	
	function showParentProject($project_id){
		ob_start();
		$sql = "select parent_project_id from ".PROJECT." where project_id='$project_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		$row=$this->db->fetch_array($result);
		
		$sql = "select * from ".PROJECT." where project_id='$row[parent_project_id]'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		$row=$this->db->fetch_array($result);
		
		$sql_i = "select importance_type_value from ".IMPORTANCE_TYPE." where  importance_type_id='$row[importance_type_id]'";
		$result_i = $this->db->query($sql_i,__FILE__,__lINE__);
		$row_i=$this->db->fetch_array($result_i);

		if($row[title]){
		echo ($row[complete]*1).'% '.$row[title].' - <a href="project_profile.php?project_id='.$row[project_id].'">'.$row[project_id].'</a> - '.$row_i[importance_type_value].' - '.$row[due_date];
		}

		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function showConnectedProject($project_id){
		ob_start();
		$sql = "select b.* from ".CONNECTED_PROJECT." a, ".PROJECT." b where  a.project_id='$project_id' and a.connected_project_id = b.project_id";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		if($this->db->num_rows($result) > 0) {
			?><table class="small_text" width="100%" style="padding-left:10px"><?php
			while($row=$this->db->fetch_array($result)){
				
				$sql_i = "select importance_type_value from ".IMPORTANCE_TYPE." where  importance_type_id='$row[importance_type_id]'";
				$result_i = $this->db->query($sql_i,__FILE__,__lINE__);
				$row_i=$this->db->fetch_array($result_i)
				?><tr><td width="3%"></td>
				<td width="97%">
				<?php echo $row[complete].'% '.$row[title].' - <a href="project_profile.php?project_id='.$row[project_id].'">'.$row[project_id].'</a> - '.$row_i[importance_type_value].' - '.$row[due_date];
				echo $this->showConnectedProject($row[project_id]);
				?>
				</td></tr><?php
				}	
				?></table><?php
			}	
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function GetContactsJson($pattern=''){
		ob_start();
		$contact_json = "";
		$sql="select * from ".TBL_CONTACT." where first_name LIKE '$pattern%' or company_name  LIKE '$pattern%' limit 0, 20";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		while($row = $this->db->fetch_array($record)){
			$contact_json .= '{"caption":"'.$row[first_name].' '.$row[last_name].'","value":"'.$row[contact_id].'"},';
		}
		$contact_json = '['.substr($contact_json,0,strlen($contact_json)-1).']';
		return $contact_json;
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}


	function clearCrieria(){
		ob_start();
		?>
		<script>        
		document.getElementById('dd_from').value = '';
		document.getElementById('dd_to').value = '';
		document.getElementById('status').value = '';
		document.getElementById('importance').value = '';
		document.getElementById('department').value = '';
		document.getElementById('user').value = '';
		document.getElementById('contact').value = '';
		document.getElementById('root_project').checked = false;
		document.getElementById('remove_finished').checked = false;
		project.projectList('','','','','','','','','','','',
								{ onUpdate: function(response,root){
								 document.getElementById('div_project_list').innerHTML=response;
								 document.getElementById('div_project_list').style.display='';
								 $(function() {		
								 $('#search_table')
								 .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: {  } } )						
								 });
								}
							  } );		
		</script>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	
	
	function searchboxProject($parent_project_id=''){
		ob_start();
		$formName = 'frm_projectSearchBox';
		?>
		<form method="post" name="<?php echo $formName; ?>" id="<?php echo $formName; ?>"  onsubmit="return false;">
		<h2>Search Project</h2>
			<table class="table" width="100%" >
			<tr>
				<th>Due Date From</th>
				<th>Due Date to</th>
				<th>Project Status</th>
				<th>Importance</th>
			</tr>
			<tr>
				<td><input name="dd_from" type="text" id="dd_from" value="" size="60" autocomplete='off' readonly="true" />
				
				<script type="text/javascript">	 
				 
				 function start_cal(){
				 new Calendar({
				 inputField   	: "dd_from",
				 dateFormat		: "%Y-%m-%d",
				 trigger		: "dd_from",
				 weekNumbers   	: true,
				 bottomBar		: true,				 
				 onSelect		: function() {
										
										this.hide();
										document.<?php echo $formName;?>.dd_from.value=this.selection.print("%Y-%m-%d");										
										
										if(document.<?php echo $formName;?>.root_project.checked==true)
										root='yes';
										else root='';
										if(document.<?php echo $formName;?>.remove_finished.checked==true)
										finished='no';
										else finished='';
										project.projectList('<?php echo $parent_project_id;?>',
												document.<?php echo $formName;?>.dd_from.value,
												document.<?php echo $formName;?>.dd_to.value,
												document.<?php echo $formName;?>.status.value,
												document.<?php echo $formName;?>.importance.value,
												document.<?php echo $formName;?>.department.value,
												document.<?php echo $formName;?>.user.value,
												document.<?php echo $formName;?>.contact.value,
												root,'',
												finished,
												{ onUpdate: function(response,root){
												 document.getElementById('div_project_list').innerHTML=response;
												 document.getElementById('div_project_list').style.display='';
												 $(function() {		
												 $('#search_table')
												 .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: {  } } )						
												 });
												}
											  } );
										}				
				  });
				}
				start_cal();
				</script>
				<a href="javascript:void(0);" onclick="javascript: document.<?php echo $formName;?>.dd_from.value=''; 
				if(document.<?php echo $formName;?>.root_project.checked==true)
				root='yes';
				else root='';
				if(document.<?php echo $formName;?>.remove_finished.checked==true)
				finished='no';
				else finished='';
				project.projectList('<?php echo $parent_project_id;?>',
										document.<?php echo $formName;?>.dd_from.value,
										document.<?php echo $formName;?>.dd_to.value,
										document.<?php echo $formName;?>.status.value,
										document.<?php echo $formName;?>.importance.value,
										document.<?php echo $formName;?>.department.value,
										document.<?php echo $formName;?>.user.value,
										document.<?php echo $formName;?>.contact.value,
										root,'',
										finished,
										{ onUpdate: function(response,root){
										 document.getElementById('div_project_list').innerHTML=response;
										 document.getElementById('div_project_list').style.display='';
										 $(function() {		
										 $('#search_table')
										 .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: {  } } )						
										 });
										}
									  } );								"><img src="images/trash.gif" border="0"/></a>				</td>		
				
				<td><input name="dd_to" type="text" id="dd_to" value="" size="60"  autocomplete='off' readonly="true" />
				<script type="text/javascript">	
				 function end_cal(){ 
				 new Calendar({
				 inputField   	: "dd_to",
				 dateFormat		: "%Y-%m-%d",
				 trigger		: "dd_to",
				 weekNumbers   	: true,
				 bottomBar		: true,	
				 onSelect		: function() {
										this.hide();
										document.<?php echo $formName;?>.dd_to.value=this.selection.print("%Y-%m-%d");

										if(document.<?php echo $formName;?>.root_project.checked==true)
										root='yes';
										else root='';
										if(document.<?php echo $formName;?>.remove_finished.checked==true)
										finished='no';
										else finished='';
										project.projectList('<?php echo $parent_project_id;?>',
												document.<?php echo $formName;?>.dd_from.value,
												document.<?php echo $formName;?>.dd_to.value,
												document.<?php echo $formName;?>.status.value,
												document.<?php echo $formName;?>.importance.value,
												document.<?php echo $formName;?>.department.value,
												document.<?php echo $formName;?>.user.value,
												document.<?php echo $formName;?>.contact.value,
												root,'',
												finished,
												{ onUpdate: function(response,root){
												 document.getElementById('div_project_list').innerHTML=response;
												 document.getElementById('div_project_list').style.display='';
												 $(function() {		
												 $('#search_table')
												 .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: {  } } )						
												 });
												}
											  } );
									}				
				  });

				  }
				end_cal(); 
				</script>
					
				<a href="javascript:void(0);" onclick="javascript: document.<?php echo $formName;?>.dd_to.value='';
				if(document.<?php echo $formName;?>.root_project.checked==true)
				root='yes';
				else root='';
				if(document.<?php echo $formName;?>.remove_finished.checked==true)
				finished='no';
				else finished='';
				project.projectList('<?php echo $parent_project_id;?>',
										document.<?php echo $formName;?>.dd_from.value,
										document.<?php echo $formName;?>.dd_to.value,
										document.<?php echo $formName;?>.status.value,
										document.<?php echo $formName;?>.importance.value,
										document.<?php echo $formName;?>.department.value,
										document.<?php echo $formName;?>.user.value,
										document.<?php echo $formName;?>.contact.value,
										root,'',
										finished,
										{ onUpdate: function(response,root){
										 document.getElementById('div_project_list').innerHTML=response;
										 document.getElementById('div_project_list').style.display='';
										 $(function() {		
										 $('#search_table')
										 .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: {  } } )						
										 });
										}
									  } );
								"><img src="images/trash.gif" border="0"/></a>				</td>
				
				<td>
				<select name="status" id="status" style="width:100%" onchange="javascript:
								if(document.<?php echo $formName;?>.root_project.checked==true)
								root='yes';
								else root='';
								if(document.<?php echo $formName;?>.remove_finished.checked==true)
								finished='no';
								else finished='';
								project.projectList('<?php echo $parent_project_id;?>',
										document.<?php echo $formName;?>.dd_from.value,
										document.<?php echo $formName;?>.dd_to.value,
										document.<?php echo $formName;?>.status.value,
										document.<?php echo $formName;?>.importance.value,
										document.<?php echo $formName;?>.department.value,
										document.<?php echo $formName;?>.user.value,
										document.<?php echo $formName;?>.contact.value,
										root,'',
										finished,
										{ onUpdate: function(response,root){
										 document.getElementById('div_project_list').innerHTML=response;
										 document.getElementById('div_project_list').style.display='';
										 $(function() {		
										 $('#search_table')
										 .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: {  } } )						
										 });
										}
									  } );">
							<option value="">-Select-</option>
							  <?php
						  $sql = "select * from ".PROJECT_STATUS;
						  $result = $this->db->query($sql,__FILE__,__lINE__);
						  while($row = $this->db->fetch_array($result)){
							?>
							  <option value="<?php echo $row[status_id] ?>" ><?php echo $row[status] ?></option>
							  <?php
						  }
						  ?>
				  </select>				</td>

				<td>
				<select name="importance" id="importance" style="width:100%" onchange="javascript:
								if(document.<?php echo $formName;?>.root_project.checked==true)
								root='yes';
								else root='';
								if(document.<?php echo $formName;?>.remove_finished.checked==true)
								finished='no';
								else finished='';
								project.projectList('<?php echo $parent_project_id;?>',
										document.<?php echo $formName;?>.dd_from.value,
										document.<?php echo $formName;?>.dd_to.value,
										document.<?php echo $formName;?>.status.value,
										document.<?php echo $formName;?>.importance.value,
										document.<?php echo $formName;?>.department.value,
										document.<?php echo $formName;?>.user.value,
										document.<?php echo $formName;?>.contact.value,
										root,'',
										finished,
										{ onUpdate: function(response,root){
										 document.getElementById('div_project_list').innerHTML=response;
										 document.getElementById('div_project_list').style.display='';
										 $(function() {		
										 $('#search_table')
										 .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: {  } } )						
										 });
										}
									  } );">
							<option value="">-Select-</option>
							  <?php
						  $sql = "select * from ".IMPORTANCE_TYPE;
						  $result = $this->db->query($sql,__FILE__,__lINE__);
						  while($row = $this->db->fetch_array($result)){
							?>
							  <option value="<?php echo $row[importance_type_id] ?>" ><?php echo $row[importance_type_value] ?></option>
							  <?php
						  }
						  ?>
				  </select>				
			   </td>
			</tr>
			<tr>
				<th>Department</th>
				<th>User Involved</th>
				<th colspan="2"> Contacts</th>
			  </tr>
			<tr>
				<td>
				<select name="department" id="department" style="width:100%" onchange="javascript:
								if(document.<?php echo $formName;?>.root_project.checked==true)
								root='yes';
								else root='';
								if(document.<?php echo $formName;?>.remove_finished.checked==true)
								finished='no';
								else finished='';
								project.projectList('<?php echo $parent_project_id;?>',
										document.<?php echo $formName;?>.dd_from.value,
										document.<?php echo $formName;?>.dd_to.value,
										document.<?php echo $formName;?>.status.value,
										document.<?php echo $formName;?>.importance.value,
										document.<?php echo $formName;?>.department.value,
										document.<?php echo $formName;?>.user.value,
										document.<?php echo $formName;?>.contact.value,
										root,'',
										finished,
										{ onUpdate: function(response,root){
										 document.getElementById('div_project_list').innerHTML=response;
										 document.getElementById('div_project_list').style.display='';
										 $(function() {		
										 $('#search_table')
										 .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: {  } } )						
										 });
										}
									  } );">
							<option value="">-Select-</option>
							  <?php
						  $sql = "select * from ".DEPARTMENT;
						  $result = $this->db->query($sql,__FILE__,__lINE__);
						  while($row = $this->db->fetch_array($result)){
							?>
							  <option value="<?php echo $row[department_id] ?>" ><?php echo $row[department_value] ?></option>
							  <?php
						  }
						  ?>
				  </select>				</td>
				<td>
				<?php 
				$sql_user = "select first_name,last_name from ".TBL_USER." where user_id='$_SESSION[user_id]'";
				$result_user = $this->db->query($sql_user,__FILE__,__lINE__);
				$row_user=$this->db->fetch_array($result_user);
				?>
				<input name="user" type="text" id="user" value="<?php echo $row_user[first_name]; ?>" size="60" onkeyup="javascript:
								if(document.<?php echo $formName;?>.root_project.checked==true)
								root='yes';
								else root='';
								if(document.<?php echo $formName;?>.remove_finished.checked==true)
								finished='no';
								else finished='';
								project.projectList('<?php echo $parent_project_id;?>',
										document.<?php echo $formName;?>.dd_from.value,
										document.<?php echo $formName;?>.dd_to.value,
										document.<?php echo $formName;?>.status.value,
										document.<?php echo $formName;?>.importance.value,
										document.<?php echo $formName;?>.department.value,
										document.<?php echo $formName;?>.user.value,
										document.<?php echo $formName;?>.contact.value,
										root,'',
										finished,
										{ onUpdate: function(response,root){
										 document.getElementById('div_project_list').innerHTML=response;
										 document.getElementById('div_project_list').style.display='';
										 $(function() {		
										 $('#search_table')
										 .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: {  } } )						
										 });
										}
									  } );" autocomplete='off' /></td>


				<td colspan="2"><select name="contacts" id="contacts" ></select>
				<a href="javascript:void(0);" onclick="javascript: 
				document.getElementById('contact').value='';
				for(i=0; i<document.getElementById('contacts').length; i++){
					if(document.getElementById('contacts')[i].selected==true){
							document.getElementById('contact').value += document.getElementById('contacts')[i].value+',';
					}
				}
				document.getElementById('contact').value = document.getElementById('contact').value.substr(0,document.getElementById('contact').value.length-1);
				

				if(document.<?php echo $formName;?>.root_project.checked==true)
				root='yes';
				else root='';
				if(document.<?php echo $formName;?>.remove_finished.checked==true)
				finished='no';
				else finished='';
				project.projectList('<?php echo $parent_project_id;?>',
						document.<?php echo $formName;?>.dd_from.value,
								document.<?php echo $formName;?>.dd_to.value,
								document.<?php echo $formName;?>.status.value,
								document.<?php echo $formName;?>.importance.value,
								document.<?php echo $formName;?>.department.value,
								document.<?php echo $formName;?>.user.value,
								document.<?php echo $formName;?>.contact.value,
								root,'',
								finished,
								{ onUpdate: function(response,root){
								 document.getElementById('div_project_list').innerHTML=response;
								 document.getElementById('div_project_list').style.display='';
								 $(function() {		
								 $('#search_table')
								 .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: {  } } )						
								 });
								}
							  } );
								">Search For Conatct</a>
				<input name="contact" type="hidden" id="contact" value="" size="60" /></td>
				
			  </tr>
			<tr><th><input name="root_project" checked="checked" id="root_project" value="yes" onclick="javascript:
								if(document.<?php echo $formName;?>.root_project.checked==true)
								root='yes';
								else root='';
								if(document.<?php echo $formName;?>.remove_finished.checked==true)
								finished='no';
								else finished='';
								project.projectList('<?php echo $parent_project_id;?>',
										document.<?php echo $formName;?>.dd_from.value,
										document.<?php echo $formName;?>.dd_to.value,
										document.<?php echo $formName;?>.status.value,
										document.<?php echo $formName;?>.importance.value,
										document.<?php echo $formName;?>.department.value,
										document.<?php echo $formName;?>.user.value,
										document.<?php echo $formName;?>.contact.value,
										root,'',
										finished,
										{ onUpdate: function(response,root){
										 document.getElementById('div_project_list').innerHTML=response;
										 document.getElementById('div_project_list').style.display='';
										 $(function() {		
										 $('#search_table')
										 .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: {  } } )						
										 });
										}
									  } );" type="checkbox" />&nbsp;Root Project Only</th>
			 <th colspan="3"><input name="remove_finished" checked="checked" id="remove_finished" value="no" onclick="javascript:
								if(document.<?php echo $formName;?>.root_project.checked==true)
								root='yes';
								else root='';
								if(document.<?php echo $formName;?>.remove_finished.checked==true)
								finished='no';
								else finished='';
								project.projectList('<?php echo $parent_project_id;?>',
										document.<?php echo $formName;?>.dd_from.value,
										document.<?php echo $formName;?>.dd_to.value,
										document.<?php echo $formName;?>.status.value,
										document.<?php echo $formName;?>.importance.value,
										document.<?php echo $formName;?>.department.value,
										document.<?php echo $formName;?>.user.value,
										document.<?php echo $formName;?>.contact.value,
										root,'',
										finished,
										{ onUpdate: function(response,root){
										 document.getElementById('div_project_list').innerHTML=response;
										 document.getElementById('div_project_list').style.display='';
										 $(function() {		
										 $('#search_table')
										 .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: {  } } )						
										 });
										}
									  } );" type="checkbox" />&nbsp;Remove Finished project</th></tr>

			</table>
		
		</form>
		<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;		
	}
	
	function traceContactByCall($phone,$obj='',$task,$asterisk){
		ob_start();
		$contact_id=$this->GetContactByPhone($phone);
		?>
		<div class="newcall">
			<table width="100%" class="newcall"><tr>
			<td valign="top"><?php 
			echo $this->GetContactProfileById($contact_id);
			?>
			<div class="clear"><?php echo '('.substr($phone, 0, 3).')'.substr($phone, 3, 3).'-'.substr($phone, 6, 4); ?></div>
			</td>
			<td valign="top">
			<div id="" style="width:375px">
				
				<?php /*?><ul>
					<li><?php<a href="#calls"> */?><span style="color:#FFFFFF">Call Log:</span><?php /*?></a></li><?php */?>
					
<?php /*?>					<li><a href="#projects">Projects</a></li>
					<li><a href="#tasks">Tasks</a></li>
<?php 				</ul>*/?>
				
			<?php /*?>	<div id="calls"><?php */?>
				<?php echo $asterisk->get_formatted_phone_log($phone, 10); ?>
			<?php /*?>	</div><?php */?>
				
<?php /*?>				<div id="projects">
				<?php echo $this->projectList('','','','','','','','','',$contact_id)?>
				</div>
				
				<div id="tasks">
				<?php 
				echo $task->GetTask('','','','','','','',1,'TBL_CONTACT',$contact_id); 
				?>
				</div>
<?php */?>			
			 </div>
			</td>
			</tr></table>
			</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
	
	function SetUserObject($user){
		$this->user=$user;
	}
	
	function SetUserID($user_id)
	{
		$this->user_id=$user_id;
	}
	
	function getName($id=''){
		ob_start();
		 $sql = "select * from ".PROJECT_STATUS." where status_id = '$id'";
		 $result = $this->db->query($sql,__FILE__,__lINE__);	
		 $row=$this->db->fetch_array($result);
		 echo $row[status];
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
	function showDropDown($status='',$project_id=''){
	 
	 ob_start();
	 $sql_dropdown = "select * from ".PROJECT_STATUS;
	 $result_dropdown = $this->db->query($sql_dropdown,__FILE__,__lINE__);	?>

	 <select name="status_id" id="status_id" style="width:80%" 
	 onchange="javascript: var status_name; 
						   var status_id = this.value; 
						   project.getName(this.value,
										  { onUpdate: function(response,root){
										    status_name = response;
                                            if(confirm('Are you sure you want to change your status from <?php echo $status; ?> to '+ status_name)){																					
                                                project.updateField('status_id',
                                                                     status_id,
                                                                     '<?php echo $project_id;?>',
		                                                             { onUpdate: function(response,root){
		                                                                project.returnLink(status_name,
																		                   '<?php echo $project_id; ?>',
		                                                                                   {target:'status_select_box_<?php echo $project_id; ?>'});
                                                                        }, preloader: 'prl' 
																	  })
									          }}
											});">
		<option value="" >--Select--</option>
		<?php while($row_dropdown=$this->db->fetch_array($result_dropdown)){ ?>
		<option value="<?php echo $row_dropdown[status_id]; ?>" <?php if($row_dropdown[status]==$status) echo 'selected="selected"';?>>
			<?php echo $row_dropdown[status]; ?> 
		</option>
		<?php } ?>
	</select>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	return $html;	
	}
	
	function returnLink($status='',$project_id=''){
		ob_start();
		if($status !=''){ ?>
			<a href="javascript:void(0);" onclick="javascript: project.showDropDown('<?php echo $status; ?>',
																					'<?php echo $project_id; ?>',
																					{ target: 'status_select_box_<?php echo $project_id; ?>'}
																					); ">
			<?php echo $status; ?></a><?php 
		}
		else { ?>
			<a href="javascript:void(0);" onclick="javascript: project.showDropDown('N/A',
																					'<?php echo $project_id; ?>',
																					{ target: 'status_select_box_<?php echo $project_id; ?>'}
																					); ">
			N/A</a><?php
		} 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	} 
		
}
?>