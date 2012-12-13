<?php
/***********************************************************************************

			Class Discription : Element_Permission
								This module creates the permission access for the user. 
			
			Class Memeber Functions : SetModule_id($module_id)
									  SetModule_name($module)
									  Isowner($module,$module_id,$primary_column,$owner_column,$user_id)
									  GetElementAccessArray($module, $module_id)
									  GetElementAccessArrayWithAccessType($module, $module_id)
									  Add_Rule_Webform($runat)
									  Element_Permission_everyone()
									  Element_Permission_Admin()
									  Element_Permission_group($access_to_id)
									  Element_Permission_people($type_people)
									  Edit_Permission($runat)
									  Add_Permission_Fly($module='',$module_id='',$access_to_type='',$access_to='',$access_type='',$display='')
			
			Describe Function of Each Memeber function :
										
									  1. function SetModule_id($module_id)  //$module_id= $module_id of the user
									      gets the $module_id of the user
									  
									  2. function SetModule_name($module)  //$module= name of the module
									      gets the name of the module
									  
									  3. function Isowner($module,$module_id,$primary_column,$owner_column,$user_id)  //$module= table name, 
									  																					$module_id= $module_id of 					                                           this function checks that is the user is owner or not. If the user is owner it
										   returns true, else it returns false.   
									  
									  4. function GetElementAccessArray($module, $module_id)  //$module= table name, $module_id= $module_id of table
									  	   returns the list of the groups who have the access to the module passed.
									  
									  5. function GetElementAccessArrayWithAccessType($module, $module_id)
									  	   return the list of persons and groups with there access type.
									  
									  6. function Add_Rule_Webform($runat)   // $runat=local/server  
									       creates the web form with the access type for the user to select the access type for the perticular act.
									  
									  7. function Element_Permission_everyone()
									       this function makes the record viewable to everyone
									  
									  8. function Element_Permission_Admin()
									       this function makes record available to the admins only
									  
									  9. function Element_Permission_group($access_to_id)
									       this function makes record available to group selected.
									  
									  10. function Element_Permission_people($type_people)
									       this function provides the access ti the table tbl_user to make the records for viewonly.
									  
									  11. function Edit_Permission($runat)   // $runat=local/server 
									       this function edits the access permission for the records.
									  
									  12. function Add_Permission_Fly($module='',$module_id='',$access_to_type='',$access_to='',$access_type='',$display='')
									       this function saves the permission access for the record of the user. It adds the module, module_id, 
										   access_to_type, access_to_id, access_type to the database table tbl_element_permission.
											



************************************************************************************/
class Element_Permission{
	var $module;
	var $module_id;
	var $access_to_type;	
	var $access_to_id;
	var $access_type;
	/************************************************************************************/
	 //constructor
	 function __construct()
	 {
	    $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	 }
	 /************************************************************************************/   
	// for adding permission
	
	function SetModule_id($module_id)
	{
		$this->module_id=$module_id;
	}
	
	function SetModule_name($module)
	{
		$this->module=$module;
	}
	
	
	function Isowner($module,$module_id,$primary_column,$owner_column,$user_id)
	{
	  $sql="select ".$primary_column." from ".$module." where ".$primary_column."=".$module_id." and ".$owner_column."=".$user_id;
	 
	  if($this->db->record_number($sql)>0) 
	  return true; 
	  else 
	  return false;
	}
	
	
	function GetElementAccessArray($module, $module_id)
	{
		$sql="select DISTINCT * from ".TBL_ELEMENT_PERMISSION.
				" where module='$this->module' and module_id='$this->module_id' and access_to_type='$this->access_to_type'";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		$group_array=array();
		while($row=$this->db->fetch_array($record)) 
		$group_array[]=$row[access_to];
		
		return $group_array;
	
	}
	
	function GetElementAccessArrayWithAccessType($module, $module_id)
	{
		$sql="select * from ".TBL_ELEMENT_PERMISSION.
				" where module='$this->module' and module_id='$this->module_id' and access_to_type='$this->access_to_type'";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		$group_array=array();
		while($row=$this->db->fetch_array($record)) 
		$group_array[$row[access_to]]=$row[access_type];
		
		return $group_array;
	
	}
	
	
	function Add_Rule_Webform($runat)
	{
		switch($runat){
		case 'local':
					?>
					
					
				    	
						<tr>
							<td colspan="2" >
								<table class="table_permession">
								<tr><th colspan="2"><h2>Who else can see this contact</h2></th></tr>
								<tr><td >
								<input type="radio" name="permission_type" id="permission_type" value="everyone" style="width:auto"
								 onclick="javascript: document.getElementById('span_permission_type_group').style.display='none';"  />&nbsp;&nbsp;Everyone</td>
								</tr>
								<tr>
								  <td><input type="radio" name="permission_type" id="permission_type" value="I" style="width:auto"
								   onclick="javascript: document.getElementById('span_permission_type_group').style.display='none';" />&nbsp;&nbsp;Only I can</td>
								</tr>
								<tr>		
								  <td><input type="radio" name="permission_type" id="permission_type" value="group" style="width:auto" onclick="javascript: document.getElementById('span_permission_type_group').style.display='';" />&nbsp;&nbsp;select groups and users</td>
								  </tr>
								  <tr id="span_permission_type_group" style="display:none">
									<td>
									<select name="access_to_id[]" class="small_text" size="6" style="width:30%" multiple="multiple" id="access_to_id[]" ><optgroup label="---Groups------">
											<?php 
											$sql="select * from ".TBL_USERGROUP." where group_name<>'contact_admin' and group_name<>'Contact_Admin'" ;
											$record=$this->db->query($sql,__FILE__,__LINE__);
											while($row=$this->db->fetch_array($record)){
											?>
											
											<option value="<?php echo $row[group_name]?>"><?php echo $row[group_name]?></option>
											<?php } ?></optgroup>
											</select>
											
											<select name="type_people[]" id="type_people[]" size="6" multiple="multiple" class="small_text" style="width:30%">
											<optgroup label="---Users------">
										  <?php  
											$sql = "select * from ".TBL_USER." order by first_name,middle_name,last_name";
											$record = $this->db->query($sql,__FILE__,__LINE__);  
											while($row = $this->db->fetch_array($record)){
											?>
											<option value="<?php echo $row[user_name] ?>"><?php echo ucfirst($row[first_name])." ".ucfirst($row[middle_name])." ".ucfirst($row[last_name])?></option>
											<?php
											}
										  ?></optgroup>
										</select>

										</span><br />
										<div class="verysmall_text gray">*use Ctrl to select multiple.</div></td>
								</tr>
							</table>
						</td>
						</tr>						
						
					<?php
					break;
		case 'server':
					extract($_POST);
					if($permission_type=='everyone'){
						$this->Element_Permission_everyone();
					}
					if($permission_type=='I'){
						
					}
					if($permission_type=='group'){
						$this->Element_Permission_group($access_to_id);
						$this->Element_Permission_people($type_people);
					}
					if($permission_type==''){
						$this->Element_Permission_everyone();
					}
					//insert_admin
					$this->Element_Permission_Admin();
					break;
		default : echo 'Wrong Paramemter passed';
		
		}
	}
	
	function Element_Permission_everyone(){
		$this->access_to_id='*';
		$this->access_to_type='*';
		$this->access_type='VIEWONLY';
		$this->Add_Permission_Fly($this->module, $this->module_id, $this->access_to_type,  $this->access_to_id, $this->access_type);
	}
	
	function Element_Permission_Admin(){
		$this->access_to_id='contact_admin';
		$this->access_to_type='TBL_USERGROUP';
		$this->access_type='FULL';
		$this->Add_Permission_Fly($this->module, $this->module_id, $this->access_to_type,  $this->access_to_id, $this->access_type, 'none');
	}

	function Element_Permission_group($access_to_id){
		$this->access_to_id=$access_to_id;
		$this->access_to_type='TBL_USERGROUP';
		$this->access_type='VIEWONLY';
		
		if(count($this->access_to_id)>0)
		foreach($this->access_to_id as $to)
		$this->Add_Permission_Fly($this->module, $this->module_id, $this->access_to_type,  $to, $this->access_type);
					
	}
	
	function Element_Permission_people($type_people){
		$this->access_to_id=$type_people;
		$this->access_to_type='TBL_USER';
		$this->access_type='VIEWONLY';

		if(count($this->access_to_id)>0)
		foreach($this->access_to_id as $to)
		$this->Add_Permission_Fly($this->module, $this->module_id, $this->access_to_type,  $to, $this->access_type);
	}
	
	/************************************************************************************/
	// for edit permission
	function Edit_Permission($runat)
	{
		$this->access_to_type='TBL_USERGROUP';
		$this->access_type='VIEWONLY';
		$selection = "";
		switch($runat){
		case 'local':	
						$sql="select * from ".TBL_ELEMENT_PERMISSION." where module='$this->module' and module_id='$this->module_id' and display=''";
						$record=$this->db->query($sql,__FILE__,__LINE__);
						$record_temp=$record;
						$num_record = $this->db->num_rows($record);
						if($num_record==0){
						  $selection = 'I';
						} else if($num_record==1){
						  $row=$this->db->fetch_array($record_temp);
						  if($row[access_to_type]=='*' and $row[access_to]=='*')
						    $selection = 'everyone';
						else if($num_record>0){
						  $selection = 'group';
						}
						}
						
						$group_array=array();
						$user_array=array();
						if($selection=='group'){
						$record=$this->db->query($sql,__FILE__,__LINE__);
						while($row=$this->db->fetch_array($record)) {
						  if($row[access_to_type]=='TBL_USERGROUP')
						   $group_array[] = $row[access_to];
						  else if($row[access_to_type]=='TBL_USER')
						    $user_array[] = $row[access_to];
						}
						}
						
						
						?>
						
						<tr>
							<td colspan="2" >
								<table class="table_permession">
								<tr><th colspan="2"><h2>Who else can see this contact</h2></th></tr>
								<tr><td >
								<input type="radio" name="permission_type" id="permission_type" value="everyone" style="width:auto"
								onclick="javascript: document.getElementById('span_permission_type_group').style.display='none';" 
								 <?php if($selection == 'everyone') echo 'checked'; ?> />&nbsp;&nbsp;Everyone</td>
								</tr>
								<tr>
								  <td><input type="radio" name="permission_type" id="permission_type" value="I" style="width:auto"
								  onclick="javascript: document.getElementById('span_permission_type_group').style.display='none';" 
								   <?php if($selection == 'I') echo 'checked'; ?>/>&nbsp;&nbsp;Only I can</td>
								</tr>
								<tr>		
								  <td><input type="radio" name="permission_type" id="permission_type" value="group" <?php if($selection == 'group') echo 'checked'; ?> style="width:auto" onclick="javascript: document.getElementById('span_permission_type_group').style.display='';" />&nbsp;&nbsp;select groups and users</td>
								  </tr>
								  <tr id="span_permission_type_group" <?php if(!($selection=='group')) echo 'style="display:none"'; ?> >
									<td>
									<select name="access_to_id[]" class="small_text" size="6" style="width:30%" multiple="multiple" id="access_to_id[]" ><optgroup label="---Groups------">
											<?php 
											$sql="select * from ".TBL_USERGROUP." where group_name<>'contact_admin' and group_name<>'Contact_Admin'" ;
											$record=$this->db->query($sql,__FILE__,__LINE__);
											while($row=$this->db->fetch_array($record)){
											?>
											
											<option value="<?php echo $row[group_name]?>" <?php if(in_array($row[group_name],$group_array)) echo'selected="selected"'; ?>><?php echo $row[group_name]?></option>
											<?php } ?></optgroup>
											</select>
											
											<select name="type_people[]" id="type_people[]" size="6" multiple="multiple" class="small_text" style="width:30%">
											<optgroup label="---Users------">
										  <?php  
											$sql = "select * from ".TBL_USER." order by first_name,middle_name,last_name";
											$record = $this->db->query($sql,__FILE__,__LINE__);  
											while($row = $this->db->fetch_array($record)){
											?>
											<option value="<?php echo $row[user_name] ?>" <?php if(in_array($row[user_name],$user_array)) echo'selected="selected"'; ?>><?php echo ucfirst($row[first_name])." ".ucfirst($row[middle_name])." ".ucfirst($row[last_name])?></option>
											<?php
											}
										  ?></optgroup>
										</select>

										</span><br />
										<div class="verysmall_text gray">*use Ctrl to select multiple.</div></td>
								</tr>
							</table>
						</td>
						</tr>						
						<?php
		break;
		case 'server':
					extract($_POST);
					
					$sql="delete  from ".TBL_ELEMENT_PERMISSION." where module='$this->module' and module_id='$this->module_id' and display='' ";
					$this->db->query($sql,__FILE__,__LINE__);
					if($permission_type=='everyone'){
						$this->Element_Permission_everyone();
					}
					if($permission_type=='I'){
						
					}
					if($permission_type=='group'){
						$this->Element_Permission_group($access_to_id);
						$this->Element_Permission_people($type_people);
					}
												
		break;
		default : echo 'Wrong Paramemter passed';
		
		}
	}
	
	
	/*function Edit_Permission($runat)
	{
		$this->access_to_type='TBL_USERGROUP';
		$this->access_type='VIEWONLY';
		
		switch($runat){
		case 'local':	
						$sql="select * from ".TBL_ELEMENT_PERMISSION." where module='$this->module' and module_id='$this->module_id' and access_to_type='$this->access_to_type'";
						$record=$this->db->query($sql,__FILE__,__LINE__);
						$group_array=array();
						while($row=$this->db->fetch_array($record)) 
						$group_array[]=$row[access_to];
						
						?>
						
						<tr>
						<th><h2>Who else can see this person </h2></th>
						<td><div class="contact_forms">
								<select name="access_to_id[]" size="5" multiple="multiple" id="access_to_id[]" >
								<?php 
								$sql="select * from ".TBL_USERGROUP." where group_name<>'contact_admin' and group_name<>'contact_admin'" ;
								$record=$this->db->query($sql,__FILE__,__LINE__);
								while($row=$this->db->fetch_array($record)){
								?>
								<option value="<?php echo $row[group_name]?>"  <?php if(in_array($row[group_name],$group_array)) echo'selected="selected"'; ?> >
								<?php echo $row[group_name]?></option>
								<?php } ?>
								</select>
								</div>
						</td>
						</tr>
						<?php
		break;
		case 'server':
		extract($_POST);
					extract($_POST);
					$this->access_to_id=$access_to_id;
					$this->access_to_type='TBL_USERGROUP';
					$this->access_type='VIEWONLY';
					
					$sql="delete  from ".TBL_ELEMENT_PERMISSION." where module='$this->module' and module_id='$this->module_id' and display='' ";
					$this->db->query($sql,__FILE__,__LINE__);
					if(count($this->access_to_id)>0)
					foreach($this->access_to_id as $to)
					$this->Add_Permission_Fly($this->module, $this->module_id, $this->access_to_type,  $to, $this->access_type);	
												
		break;
		default : echo 'Wrong Paramemter passed';
		
		}
	}*/
	/************************************************************************************/

	function Add_Permission_Fly($module='',$module_id='',$access_to_type='',$access_to='',$access_type='',$display='')
	{

		$insert_sql_array = array();
		$insert_sql_array['module'] = $module;
		$insert_sql_array['module_id'] = $module_id;
		$insert_sql_array['access_to_type'] = $access_to_type;
		$insert_sql_array['access_to'] = $access_to;
		$insert_sql_array['access_type'] = $this->access_type;
		$insert_sql_array['display'] = $display;
		$this->db->insert(TBL_ELEMENT_PERMISSION,$insert_sql_array);	
	}
	
}
?>