<?php
if( MOD_ACCDEN ){ require('mod.accden.php');}


/***********************************************************************************

			Class Discription : 
			
			Class Memeber Functions :
			
			
			Describe Function of Each Memeber function: 
			
									
									 

************************************************************************************/
class Event
{	
	var $grid;
	var $rank;
	var $first_name;
	var $last_name;
	var $phone;
	var $cell;
	var $email;
	var $document_name;
	var $document_status;
	var $document_server_name;
	var $contact_id;
	var $event_status;
	var $customer_name;
	var $address_name;
	var $street_name_1;
	var $street_name_2;
	var $city;
	var $state;
	var $zip;
	var $est_cost;
	var $actual_cost;
	var $geocode;
	var $db;
	var $validity;
	var $Form;
	var $user;
	var $event_id;
	var $mod_accden;
		function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		if( MOD_ACCDEN ){
			$this->mod_accden = new mod_accden();
			$this->mod_accden->proccess_request_from_get();
			$accden = $_GET["accden"];
		}
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
		$this->user = new User();		
		}

	/*function gridCode(){
	$this->grid="
	
	javascript: evt.editEvent('local','<?php echo $this->event_id;?>',
					{ onUpdate: function(response,root){
							 document.getElementById('div_event').innerHTML=response;
		  				 	 document.getElementById('div_event').style.display='';	
															
							
							var thegrid3 = new drasticGrid('grid3', {							
							path: 'grid_event_services.php',
							pathimg: '<?php echo PATHDRASTICTOOLS; ?>img/',
							pagelength:6,
							addparams:'&col_name=event_id&col_value='+<?php echo $event_id;?>,
							columns: [
								
								{name : 'service_type',displayname:'Service Type', width:150},
								{name : 'quantity_requested',displayname:'Quantity Requested', width:150 },
								{name : 'quantity_performed',displayname:'Quantity Performed', width:150 },
								{name : 'service_code',displayname:'Service Code', width:150 }
							 ]
						});
							
							var thegrid2 = new drasticGrid('grid2', {
									path: 'grid_event_date.php',
									pathimg: '<?php echo PATHDRASTICTOOLS; ?>img/',
									pagelength:6,
									addparams:'&col_name=event_id&col_value='+<?php echo $event_id;?>,
									columns: [
																		 	
									 	{name : 'event_date', displayname:'Date(YYYY-MM-DD)', width:140  },
										{name : 'start_time',displayname:'Start Time(HH:MM:SS)', width:140 },
										{name : 'end_time', displayname:'End Time(HH:MM:SS)', width:140 }										
									 ]
								});
								
							var thegrid1 = new drasticGrid('grid1', {						
							path: 'grid_event_poc.php.php',
							pathimg: '<?php echo PATHDRASTICTOOLS; ?>img/',
							pagelength:6,
							addparams:'&col_name=event_id&col_value='+<?php echo $event_id;?>,
							columns: [
								
								{name : 'rank',displayname:'Rank', width:120  },
								{name : 'first_name',displayname:'First Name', width:120  },
								{name : 'last_name',displayname:'Last Name', width:120  },
								{name : 'phone',displayname:'Phone', width:100  },
								{name : 'cell',displayname:'Cell', width:100  },
								{name : 'email',displayname:'E-Mail',type: DDTYPEMAILTO, width:200  }
							 ]
						});
							
						 					 
							 }, preloader: 'prl'
						} ); return false;
	
	
	";
	return $this->grid;
	}*/
	
	
	function addEvent($runat,$target,$appointment_id='',$group_event_id='',$service_request_id='',$provider_id='',$event_status='',$customer_name='',$address_name='',$street_name_1='',$street_name_2='',$city='',$state='',$zip='',$est_cost='',$actual_cost='',$em_note='',$lat='',$long='',$user_responsible='',$start_date='',$end_date='') {
		ob_start();
		$this->appointment_id=$appointment_id;
		$this->group_event_id=$group_event_id;
		$this->service_request_id=$service_request_id;
		$this->provider_id=$provider_id;
		$this->event_status=$event_status;
		$this->customer_name=$customer_name;
		$this->address_name=$address_name;
		$this->street_name_1=$street_name_1;
		$this->street_name_2=$street_name_2;
		$this->city=$city;
		$this->state=$state;
		$this->zip=$zip;
		$this->est_cost=$est_cost;
		$this->actual_cost=$actual_cost;
		$this->lat=$lat;
		$this->long=$long;
		$this->user_responsible=$user_responsible;	
		$this->start_date=$start_date;
		$this->end_date=$end_date;	
		$this->em_note=$em_note;		

		switch($runat){
				
				case 'local':
						
						
						if(count($_POST)>0) {
							extract($_POST);
									$this->appointment_id=$appointment_id;
									$this->group_event_id=$group_event_id;
									$this->service_request_id=$service_request_id;
									$this->provider_id=$provider_id;
									$this->event_status=$event_status;
									$this->customer_name=$customer_name;
									$this->address_name=$address_name;
									$this->street_name_1=$street_name_1;
									$this->street_name_2=$street_name_2;
									$this->city=$city;
									$this->state=$state;
									$this->zip=$zip;
									$this->est_cost=$est_cost;
									$this->actual_cost=$actual_cost;
									$this->lat=$lat;
									$this->long=$long;
									$this->user_responsible=$user_responsible;
									$this->start_date=$start_date;
									$this->end_date=$end_date;
									$this->em_note=$em_note;					
						}		
						
						//create client side validation
						
						$FormName='frm_Add_Events';
						$ControlNames=array("group_event_id"		=>array('group_event_id',"''","Please Enter Group Event ID !! ","group_event_id"),
											"event_status"			=>array('event_status',"''","Please Enter Event Status !! ","span_event_status"),
											"customer_name"			=>array('customer_name',"''","Please Enter Customer Name !! ","span_customer_name")											
											);
											
						
						$ValidationFunctionName="CheckValidity";
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						$sql = "select * from ".EM_EVENT_STATUS;
						$result = $this->db->query($sql,__FILE__,__lINE__);
						
						?>
						<div class="prl">&nbsp;</div>
						<div id="lightbox">
							<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
							<div id="TB_ajaxWindowTitle">Add Event</div>
							<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_event').style.display='none';"><img border="0" src="images/close.gif" alt="close" /></a></div>
						</div>
						<div  class="white_content"> 
						<div style="padding:20px;" class="form_main">			
						<ul id="error_list">
							  	<li><span id="group_event_id" class="normal"></span></li>
							    <li><span id="span_event_status" class="normal"></span></li>
								<li ><span id="span_customer_name" class="normal"></span></li>
						</ul>		
						<form action=""  method="post" enctype="multipart/form-data" name="<?php echo $FormName; ?>">
				<table class="table" width="100%">
				<tr><td>								  
						  <table class="table" width="91%">
							  <tr>
							  <th width="42%">Appointment ID </th>
							  <td width="58%"><input type="text" name="appointment_id" id="appointment_id" size="25" value="<?php echo $_POST['appointment_id'];?>" /></td>
							  </tr>
							  <tr>
							  <th>Group Event ID </th>
							  <td><input type="text" name="group_event_id" id="group_event_id" size="25" value="<?php echo $_POST['group_event_id'];?>" /></td>
							  </tr>
							  <tr>
							  <th>Service Request ID </th>
							  <td><input type="text" name="service_request_id" id="service_request_id" size="25" value="<?php echo $_POST['service_request_id'];?>" /></td>
							  </tr>
							  <tr>
							  <th>Provider ID </th>
							  <td><input type="text" name="provider_id" id="provider_id" size="25" value="<?php echo $_POST['provider_id'];?>" /></td>
							  </tr>

							  <tr>
							  <th>Customer Name </th>
							  <td><input type="text" name="customer_name" id="customer_name" size="50" value="<?php echo $_POST['customer_name'];?>" /></td>
							  </tr>
							  <tr>
							  <th>Address Name </th>
							  <td><input type="text" name="address_name" id="address_name" value="<?php echo $_POST['address_name'];?>" /></td>
							  </tr>
							  
							  <tr>
							  <th>Street Name 1 </th>
							  <td><input type="text" name="street_name_1" id="street_name_1" value="<?php echo $_POST['street_name_1'];?>" /></td>
							  </tr>
							  
							  <tr>
							  <th>Street Name 2 </th>
							  <td><input type="text" name="street_name_2" id="street_name_2" value="<?php echo $_POST['street_name_2'];?>" /></td>
							  </tr>
							  
							  <tr>
							  <th>City </th>
							  <td><input type="text" name="city" id="city" size="30" value="<?php echo $_POST['city'];?>" /></td>
							  </tr>
							  
							  <tr>
							  <th>State </th>
							  <td >
							  <select name="state" id="state" >
								<option value="">Select State</option>
								<?php
									$state=file("../state_us.inc");
									foreach($state as $val){
									$state = trim($val);
								?>
								<option <?php if($_POST['state']==$state){echo 'selected="selected"';}?> value="<?php echo $state;?>"><?php echo $state;?></option>
								<?php
									}
								?>
							 </select>
							  
							  </td>
							  </tr>
							  
							  <tr>
							  <th>Zip </th>
							  <td><input type="text" name="zip" id="zip" size="10" value="<?php echo $_POST['zip'];?>" /></td>
							  </tr>
							  
							  <tr>
							  <th>Estimated Revenue </th>
							  <td><input type="text" name="est_cost" id="est_cost" size="20" value="<?php echo $_POST['est_cost'];?>" /></td>
							  </tr>
							  
							  <tr>
							  <th>Actual Revenue </th>
							  <td><input type="text" name="actual_cost" id="actual_cost" size="20" value="<?php echo $_POST['actual_cost'];?>" /></td>
							  </tr>								  
							  

							  
							 					
				  </table>			
				</td><td>
				
				<table class="table" width="100%">
				<tr align="left"><td><h2>Event Status:</h2></td>
					<td><select name="event_status" id="event_status">
						<option value="">Select</option>
						<?php
						while($row=$this->db->fetch_array($result))
						{
							?>
							<option value="<?php echo $row['event_status_id']; ?>"><?php echo $row['event_status']; ?></option> 
							<?php
						}
						?>
						</select>
				  </td>
				</tr>	
				<tr align="left"><td colspan="2"><h2>Note:</h2></td></tr>
				<tr align="left"><td colspan="2"><textarea cols="30" name="em_note" id="em_note"></textarea></td></tr>
				</table>
				
				 <tr>
							  <td colspan="2" align="center">
							  <input type="button" name="submit" value="Create" id="submit" style="width:auto" onClick="javascript: 
					 if(<?php echo $ValidationFunctionName?>()) { evt.addEvent('server','<?php echo $target ?>',this.form.appointment_id.value,this.form.group_event_id.value,this.form.service_request_id.value,this.form.provider_id.value,this.form.event_status.value,this.form.customer_name.value,this.form.address_name.value,this.form.street_name_1.value,this.form.street_name_2.value,this.form.city.value,this.form.state.value,this.form.zip.value,this.form.est_cost.value,this.form.actual_cost.value,this.form.em_note.value,{target:'div_event', preloader: 'prl'}); }" />							  </td>
							  </tr>	
				
				</td>
				</tr>
				</table>		  		  
						</form></div></div></div>
						   <?php

						  break;		
				case 'server':			

					$return =true;
			
					if($this->Form->ValidField($group_event_id,'empty','Please Enter Group Event Id')==false)
						$return =false;
					if($this->Form->ValidField($event_status,'empty','Please Enter Your Event Status')==false)
						$return =false;
					if($this->Form->ValidField($customer_name,'empty','Please Enter Your Coustomer Name')==false)
						$return =false;
				
					
					if($return){
						 $insert_sql_array = array();

						 $insert_sql_array[appointment_id] = $this->appointment_id;
						 $insert_sql_array[group_event_id] = $this->group_event_id;
						 $insert_sql_array[service_request_id] = $this->service_request_id;
						 $insert_sql_array[provider_id] = $this->provider_id;
						 $insert_sql_array[event_status] = $this->event_status;
						 $insert_sql_array[customer_name] = $this->customer_name;
						 $insert_sql_array[address_name] = $this->address_name;
						 $insert_sql_array[street_name_1] = $this->street_name_1;
						 $insert_sql_array[street_name_2] = $this->street_name_2;
						 $insert_sql_array[city] = $this->city;
						 $insert_sql_array[state] = $this->state;
						 $insert_sql_array[zip] = $this->zip;
						 $insert_sql_array[est_cost] = $this->est_cost;
						 $insert_sql_array[actual_cost] = $this->actual_cost;
						 $insert_sql_array[note] = $this->em_note;
						 
								 
						 $this->db->insert(EM_EVENT,$insert_sql_array);
						 
						 
						 $event_id = $this->db->last_insert_id();
						 $this->AddDate('server',$event_id,$this->start_date,$this->end_date);
						 
							
						?>
						<script type="text/javascript">
						document.getElementById('div_event').style.display='none'; 
				 		evt.showEvents({target:'div_show_event',preloader: 'prl'})
						</script>
						<?php						
					
					}
										
				break;
			   default : echo 'Wrong Paramemter passed';		
	
		}// end of switch()
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
		
		
	} // end of addEvent
	
	
	function editEvent($runat,$event_id,$appointment_id='',$group_event_id='',$service_request_id='',$provider_id='',$event_status='',$customer_name='',$address_name='',$street_name_1='',$street_name_2='',$city='',$state='',$zip='',$est_cost='',$actual_cost='',$em_note='') {
		ob_start();
					
		$this->event_id=$event_id;
		
		switch($runat){
				
				case 'local':								
						
						//create client side validation
						
						$FormName='frm_edit_Events';
						$ControlNames=array("group_event_id"		=>array('group_event_id',"''","Please Enter Group Event ID !! ","group_event_id"),
											"event_status"			=>array('event_status',"''","Please Enter Event Status !! ","span_event_status"),
											"customer_name"			=>array('customer_name',"''","Please Enter Customer Name !! ","span_customer_name")											
											);
											
						
						$ValidationFunctionName="CheckValidity";
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						$sql = "select * from ".EM_EVENT." where event_id='".$this->event_id."'";
						$result = $this->db->query($sql,__FILE__,__lINE__);
						$row=$this->db->fetch_array($result);
						
						$sql_status = "select * from ".EM_EVENT_STATUS;
						$result_status = $this->db->query($sql_status,__FILE__,__lINE__);
						
						$sql_poc = "select * from ".EM_POC." where event_id='".$this->event_id."'";
						$result_poc = $this->db->query($sql_poc,__FILE__,__lINE__);						
						?>
						
						<div class="prl">&nbsp;</div>
						<div id="lightbox">
							<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
							<div id="TB_ajaxWindowTitle">Edit Event</div>
							<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_event').style.display='none'; evt.getEventStatus('<?php echo $this->event_id ?>',{target:'div_event_status',preloader: 'prl'}); evt.Show_Event_Info('<?php echo $this->event_id ?>',{target:'event_info',preloader: 'prl'}); evt.ShowEventPOC('<?php echo $this->event_id ?>',{target:'event_poc',preloader: 'prl'}); evt.ShowEventNote('<?php echo $this->event_id ?>',{target:'event_note',preloader: 'prl'});"><img border="0" src="images/close.gif" alt="close" /></a></div>
						</div>
						<div  class="white_content"> 
						<div style="padding:20px;" class="form_main">			
						<ul id="error_list">
							  	<li><span id="group_event_id" class="normal"></span></li>
							    <li><span id="span_event_status" class="normal"></span></li>
								<li ><span id="span_customer_name" class="normal"></span></li>
						</ul>			
						<form action=""  method="post" enctype="multipart/form-data" name="<?php echo $FormName; ?>">
				<table class="table" width="100%">
				<tr><td>								  
						  <table class="table" width="91%">
							
							  <tr>
							  <th width="42%">Appointment ID </th>
							  <td width="58%"><input type="text" name="appointment_id" id="appointment_id" size="25" value="<?php echo $row['appointment_id'];?>" /></td>
							  </tr>
							  <tr>
							  <th>Group Event ID </th>
							  <td><input type="text" name="group_event_id" id="group_event_id" size="25" value="<?php echo $row['group_event_id'];?>" /></td>
							  </tr>
							  <tr>
							  <th>Service Request ID </th>
							  <td><input type="text" name="service_request_id" id="service_request_id" size="25" value="<?php echo $row['service_request_id'];?>" /></td>
							  </tr>
							  <tr>
							  <th>Provider ID </th>
							  <td><input type="text" name="provider_id" id="provider_id" size="25" value="<?php echo $row['provider_id'];?>" /></td>
							  </tr>
							  
							  
							  
							 
							  <tr>
							  <th>Customer Name </th>
							  <td><input type="text" name="customer_name" id="customer_name" size="50" value="<?php echo $row['customer_name'];?>" /></td>
							  </tr>
							  <tr>
							  <th>Address Name </th>
							  <td><input type="text" name="address_name" id="address_name" value="<?php echo $row['address_name'];?>" /></td>
							  </tr>
							  
							  <tr>
							  <th>Street Name 1 </th>
							  <td><input type="text" name="street_name_1" id="street_name_1" value="<?php echo $row['street_name_1'];?>" /></td>
							  </tr>
							  
							  <tr>
							  <th>Street Name 2 </th>
							  <td><input type="text" name="street_name_2" id="street_name_2" value="<?php echo $row['street_name_2'];?>" /></td>
							  </tr>
							  
							  <tr>
							  <th>City </th>
							  <td><input type="text" name="city" id="city" size="30" value="<?php echo $row['city'];?>" /></td>
							  </tr>
							  
							  <tr>
							  <th>State </th>
							  <td >
							  <select name="state" id="state" >
								<option value="">Select State</option>
								<?php
									$state=file("../state_us.inc");
									foreach($state as $val){
									$state = trim($val);
								?>
								<option <?php if($row['state']==$state){echo 'selected="selected"';}?> value="<?php echo $state;?>"><?php echo $state;?></option>
								<?php
									}
								?>
							 </select>
							  
							  </td>
							  </tr>
							  
							  <tr>
							  <th>Zip </th>
							  <td><input type="text" name="zip" id="zip" size="10" value="<?php echo $row['zip'];?>" /></td>
							  </tr>
							  <tr>
							  
							  <th>Estimated Revenue </th>
							  <td><input type="text" name="est_cost" id="est_cost" size="20" value="<?php echo $row['est_cost'];?>" /></td>
							  </tr>
							  
							  <tr>
							  <th>Actual Revenue </th>
							  <td><input type="text" name="actual_cost" id="actual_cost" size="20" value="<?php echo $row['actual_cost'];?>" /></td>
							  </tr>								  
				  </table>			
				</td><td>
				
				<table class="table" width="100%">
				<tr align="left"><td><h2>Event Status:</h2></td>
					<td><select name="event_status" id="event_status">
						<option value="">Select</option>
						<?php
						while($row_status=$this->db->fetch_array($result_status))
						{
							?>
							<option <?php if($row['event_status']==$row_status['event_status_id']){echo 'selected="selected"';}?>   value="<?php echo $row_status['event_status_id']; ?>"><?php echo $row_status['event_status']; ?></option> 
							<?php
						}
						?>
						</select>
				  </td>
				</tr>
				<? if( MOD_ACCDEN ){	$this->mod_accden->gui_from_get(); } ?>
				<tr align="left"><td colspan="2"><h2>Note:</h2></td></tr>
				<tr align="left"><td colspan="2"><textarea cols="30" name="em_note" id="em_note" <?php echo $row['note'];?> ><?php echo $row['note'];?></textarea></td></tr>
				</table>
				
				 <tr>
							  <td colspan="2" align="center">
							  <input type="button" name="submit" value="Update" id="submit" style="width:auto" onClick="javascript: 
					 if(<?php echo $ValidationFunctionName?>()) { evt.editEvent('server','<?php echo $this->event_id ?>',this.form.appointment_id.value,this.form.group_event_id.value,this.form.service_request_id.value,this.form.provider_id.value,this.form.event_status.value,this.form.customer_name.value,this.form.address_name.value,this.form.street_name_1.value,this.form.street_name_2.value,this.form.city.value,this.form.state.value,this.form.zip.value,this.form.est_cost.value,this.form.actual_cost.value,this.form.em_note.value,{target:'div_event', preloader: 'prl'}); }" />							  </td>
							  </tr>	
				
				</td>
				</tr>
				
				</table>		  		  
						</form>
				<div id="grid3"></div>
				<div id="grid2"></div>
				<div id="grid1"></div>
				</div></div></div>
						   <?php

						  break;
						  		
				case 'server':			
					
					$this->event_id=$event_id;
					$this->appointment_id=$appointment_id;
					$this->group_event_id=$group_event_id;
					$this->service_request_id=$service_request_id;
					$this->provider_id=$provider_id;
					$this->event_status=$event_status;
					$this->customer_name=$customer_name;
					$this->address_name=$address_name;
					$this->street_name_1=$street_name_1;
					$this->street_name_2=$street_name_2;
					$this->city=$city;
					$this->state=$state;
					$this->zip=$zip;
					$this->est_cost=$est_cost;
					$this->actual_cost=$actual_cost;				
					$this->em_note=$em_note;
					
				
					$return =true;
					
					if($this->Form->ValidField($group_event_id,'empty','Please Enter Group Event Id')==false)
						$return =false;
					if($this->Form->ValidField($event_status,'empty','Please Enter Your Event Status')==false)
						$return =false;
					if($this->Form->ValidField($customer_name,'empty','Please Enter Your Coustomer Name')==false)
						$return =false;
					
					
					if($return){
						 $update_sql_array = array();
						 $update_sql_array[appointment_id] = $this->appointment_id;
						 $update_sql_array[group_event_id] = $this->group_event_id;
						 $update_sql_array[service_request_id] = $this->service_request_id;
						 $update_sql_array[provider_id] = $this->provider_id;
						 $update_sql_array[event_status] = $this->event_status;
						 $update_sql_array[customer_name] = $this->customer_name;
						 $update_sql_array[address_name] = $this->address_name;
						 $update_sql_array[street_name_1] = $this->street_name_1;
						 $update_sql_array[street_name_2] = $this->street_name_2;
						 $update_sql_array[city] = $this->city;
						 $update_sql_array[state] = $this->state;
						 $update_sql_array[zip] = $this->zip;
						 $update_sql_array[est_cost] = $this->est_cost;
						 $update_sql_array[actual_cost] = $this->actual_cost;
						 $update_sql_array[note] = $this->em_note;						 
						 $this->db->update(EM_EVENT,$update_sql_array,'event_id',$this->event_id);		 
						 										 
						 ?>
						 <script type="text/javascript">
						 document.getElementById('div_event').style.display='none';
						 evt.getEventStatus('<?php echo $this->event_id ?>',{target:'div_event_status',preloader: 'prl'});
				 		 evt.Show_Event_Info('<?php echo $this->event_id ?>',{target:'event_info',preloader: 'prl'});
						 evt.ShowEventDate('<?php echo $this->event_id ?>',{target:'event_date',preloader: 'prl'});
						 evt.ShowEventServices('<?php echo $this->event_id ?>',{target:'event_services',preloader: 'prl'});
						 evt.ShowEventPOC('<?php echo $this->event_id ?>',{target:'event_poc',preloader: 'prl'});
						 evt.ShowEventNote('<?php echo $this->event_id ?>',{target:'event_note',preloader: 'prl'});
						 </script>
						 <?php						
					
					 }
										
				break;
			   default : echo 'Wrong Paramemter passed';		
	
		}// end of switch()
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
		
		
	} // end of editEvent
	
	function start_date($event_id) {		
		$this->event_id=$event_id;
		$sql = "select * from ".EM_DATE." where event_id= ".$this->event_id." order by  event_date asc limit 1";
		$result = $this->db->query($sql,__FILE__,__lINE__);			
		$row = $this->db->fetch_array($result);		
		$start_date=$row['event_date'];		
		echo $start_date;		
	}
	function return_start_date($event_id) {		
		$this->event_id=$event_id;
		$sql = "select * from ".EM_DATE." where event_id= ".$this->event_id." order by  event_date asc limit 1";
		$result = $this->db->query($sql,__FILE__,__lINE__);			
		$row = $this->db->fetch_array($result);		
		$start_date=$row['event_date'];		
		return $start_date;		
	}
	function end_date($event_id) {		
		$this->event_id=$event_id;
		$sql = "select * from ".EM_DATE." where event_id= ".$this->event_id." order by  event_date desc limit 1";
		$result = $this->db->query($sql,__FILE__,__LINE__);			
		$row = $this->db->fetch_array($result);		
		$end_date=$row['event_date'];		
		echo $end_date;		
	}
	
	function showEvents($all='',$type='') {
	
		//$sql_event = "select distinct(a.event_id) from ".EM_EVENT." a , ."EM_STAFFING". b where (a.event_id = b.event_id and b.status='Vacant') or a.event_id !=b.event_id";
		//$result_st = $this->db->query($sql,__FILE__,__LINE__);
		
		
		?>
		
		<table id="search_table" class="event_form small_text" width="100%">
		<thead>
		<tr>
		<th>GE ID::</th>
		<th>Start Day::</th>
		<th>End Day::</th>
		<th>City::</th>
		<th>St::</th>
		<th>Status::</th>
		</tr>
		</thead>
		<tbody>
		<?php 
			if($all=='all' && $type!='all') {
				$sql_st = "select distinct(a.event_id) from ".EM_EVENT." a , ".EM_STAFFING." b, ".EM_DATE." c where (a.event_id = b.event_id and a.event_id = c.event_id and b.status='Vacant') or (a.event_id !=b.event_id and a.event_id = c.event_id ) and c.event_date >'".date("Y-m-d")."' order by  c.event_date desc";
			}
			else if($all=='all' && $type=='all') {
				$sql_st = "select event_id from ".EM_EVENT." order by  event_id desc";
			}
			else {
				$sql_st = "select distinct(a.event_id) from ".EM_EVENT." a , ".EM_STAFFING." b, ".EM_DATE." c where ((a.event_id = b.event_id and a.event_id = c.event_id ) or (a.event_id !=b.event_id and a.event_id = c.event_id ))   order by  c.event_date asc ";
			}
			$result_st = $this->db->query($sql_st,__FILE__,__LINE__);
				//echo $sql_st;
				$i=0;
				$k=0;
							
				while($row_st = $this->db->fetch_array($result_st)) {		
				$sql= "select * from ".EM_EVENT." where event_id='".$row_st['event_id']."'";
				$result= $this->db->query($sql,__FILE__,__LINE__);
				$row = $this->db->fetch_array($result);	
				
				$sql_stafing_check= "select * from ".EM_STAFFING." where event_id='".$row['event_id']."'";
				$result_stafing_check= $this->db->query($sql_stafing_check,__FILE__,__LINE__);
				$staf_state= 'full';
				while($row_stafing_check = $this->db->fetch_array($result_stafing_check))
				{
					if($row_stafing_check['contact_id'] == '' || $row_stafing_check['contact_id']=='0')
					{
						$staf_state= 'empty';
					}
				}
				
				$strt_date = $this->return_start_date($row['event_id']); 
				//echo $strt_date."       ";
				$todays_date = date("Y-m-d"); 
				$today = strtotime($todays_date); 
				//echo $today."    ";
				$evnt_start_date = strtotime($strt_date); 
				//echo $evnt_start_date;
				//echo "<br>".$sql_st;
				
				if ($evnt_start_date>=$today && $i<5 && $staf_state == 'empty') { 
				?>		
				<tr >
				<td><a href= "event_profile.php?event_id=<?php echo $row[event_id];?>"><?php echo $row[group_event_id]; ?></a></td>
				<td><?php $this->start_date($row[event_id]); ?></td>
				<td><?php $this->end_date($row[event_id]); ?></td>
				<td><?php echo $row[city]; ?></td>
				<td><?php echo $row[state]; ?></td>
				<td><?php
						 $sql_event_status= "select * from ".EM_EVENT_STATUS." where event_status_id='".$row[event_status]."'";
						 $result_event_status= $this->db->query($sql_event_status,__FILE__,__LINE__);
						 $row_event_status = $this->db->fetch_array($result_event_status);	
						 echo $row_event_status[event_status]; 
					?></td>
				</tr>				
				<?php
				$k++;
				$i++;
				}
				}
			if ($k == 0){?>
			<tr>
				<td></td>
				<td></td>
				<td>no result</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php } ?>	
		
		</tbody>
		</table>
		
		<?php 
		if($all !='all')
		{
		?>
		<div align="right"><a href="show_all.php?action=allevents">more..</a></div>
	<?php } 	
		
	}
	
	
	function Show_Event_Info($event_id) {
		ob_start();
		$this->event_id=$event_id;		
		$sql = "select * from ".EM_EVENT." where event_id = '".$this->event_id."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		$row=$this->db->fetch_array($result);
		?>
			
		<table>
		<tr>
		<td>Provider ID</td>
		<td>::</td>
		<td><?php echo $row[provider_id]; ?></td>		
		</tr>
		
		<tr>
		<td>Group ID</td>
		<td>::</td>
		<td><?php echo $row[group_event_id]; ?></td>		
		</tr>
		
		<tr>
		<td>Appointment ID</td>
		<td>::</td>
		<td><?php echo $row[appointment_id]; ?></td>		
		</tr>
		
		<tr>		
		<td colspan="3"><?php echo $row[customer_name]; ?></td>		
		</tr>
		
		<tr>		
		<td colspan="3"><?php echo $row[address_name]; ?>;</td>		
		</tr>
		
		<tr>		
		<td colspan="3"><?php echo $row[street_name_1]; ?>; <?php echo $row[street_name_2]; ?>;</td>		
		</tr>
		
		
		<tr>		
		<td colspan="3"><?php echo $row[city]; ?>;</td>		
		</tr>
		
		<tr>		
		<td colspan="3"><?php echo $row[state]; ?>-<?php echo $row[zip]; ?></td>		
		</tr>
		
		<tr><td colspan="3">&nbsp;</td></tr>
		
		<tr>
			<td colspan="3">
				<a href="#" onclick="javascript: evt.editEvent('local','<?php echo $this->event_id;?>',
					{ onUpdate: function(response,root){
							 document.getElementById('div_event').innerHTML=response;
		  				 	 document.getElementById('div_event').style.display='';	
							 }, preloader: 'prl'
						} );" >Edit</a>
			</td>
		</tr>
		</table>
		
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	/*function addPoc($runat,$target,$event_id,$rank='',$first_name='',$last_name='',$phone='',$cell='',$email='') {
		$this->event_id = $event_id;
		$this->rank = $rank;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->phone = $phone;
		$this->cell = $cell;
		$this->email = $email;
		switch($runat) {
			case 'local':
				?>
				<form name="" method="post">
				
				<table class="table">
				
				<tr>
				<td>Rank</td>
				<td><input type="text" name="rank" id="rank" value="<?php echo $this->rank; ?>"/></td>
				</tr>
				
				<tr>
				<td>First Name</td>
				<td><input type="text" name="first_name" id="first_name" value="<?php echo $this->first_name; ?>"/></td>
				</tr>
				
				<tr>
				<td>Last Name</td>
				<td><input type="text" name="last_name" id="last_name" value="<?php echo $this->last_name; ?>"/></td>
				</tr>
				
				<tr>
				<td>Phone</td>
				<td><input type="text" name="phone" id="phone" value="<?php echo $this->phone; ?>"/></td>
				</tr>
				
				<tr>
				<td>Cell</td>
				<td><input type="text" name="cell" id="cell" value="<?php echo $this->cell; ?>"/></td>
				</tr>
				
				<tr>
				<td>E-mail</td>
				<td><input type="text" name="email" id="email" value="<?php echo $this->email; ?>"/></td>
				</tr>
				
				<tr>
				<td></td>
				<td><input type="button" name="save" id="save" value="Save" onclick="javascript: return <?php ;?>"/></td>
				</tr>
				
				</table>
				
				</form>
				<?php
				
				break;
									
			case 'server':
				
				
				$insert_sql_array[event_id] = $this->event_id;
				$insert_sql_array[rank] = $this->rank;
				$insert_sql_array[first_name] = $this->first_name;
				$insert_sql_array[last_name] = $this->document_name;
				$insert_sql_array[document_status] = $this->document_status;
				$insert_sql_array[document_name] = $this->document_name;
				$insert_sql_array[document_status] = $this->document_status;						 
				$this->db->insert(EM_DOCUMENTS,$insert_sql_array);
				
				
				break;
	
	
	
		}	
	}*/
	function addDocuments($runat,$event_id,$doc_id='',$document_name='',$document_status='',$document_server_name='') {
		$this->event_id=$event_id;
		switch($runat) {
			case 'local':
				$sql = "select * from ".EM_DOCUMENTS." where document_id = '".$doc_id."'";
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
						window.location="event_profile.php?event_id=<?php echo $this->event_id; ?>";
						}
					  else if(success == 2){
						alert('Record Updated Successfully..');
						window.location="event_profile.php?event_id=<?php echo $this->event_id; ?>";
						}
					  else if(success == 3){
						 result = '<span>Selected File Is Of 0 Bytes!!<br/><\/span><br/><br/>';
						 document.getElementById('upload_process').style.visibility = 'hidden';
					  	 document.getElementById('upload_form').innerHTML = result + '<label>Document Status:<select name="document_status"><option value="">--Select--</option><option value="Team Lead" selected="selected">Team Lead</option><option value="User">User</option><option value="internal">Internal Staff</option></select></label><label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>';
						 document.getElementById('upload_form').style.visibility = 'visible';
					  	}
					 else if(success == 0){
						 result = '<span>One Or More Fields are Empty!!<\/span><br/><br/>';
						 document.getElementById('upload_process').style.visibility = 'hidden';
					  	 document.getElementById('upload_form').innerHTML = result + '<label>Document Status:<select name="document_status"><option value="">--Select--</option><option value="Team Lead" selected="selected">Team Lead</option><option value="User">User</option><option value="internal">Internal Staff</option></select></label><label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>';
						 document.getElementById('upload_form').style.visibility = 'visible';
					  	}else
					  
					        
					  return true;   
				}
				
				</script> 			   
			   	
				
				<div class="prl">&nbsp;</div>

				<div id="lightbox" style=" position:absolute;!important;" >		
				<div style="background-color:#ADC2EB;" class="ajax_heading">
					<div id="TB_ajaxWindowTitle"><?php if($doc_id) echo 'Edit Document'; else echo'Add Document' ?> </div>
					
					<div id="TB_closeAjaxWindow"><a href="event_profile.php?event_id=<?php echo $this->event_id; ?>" 
					onclick="javascript: document.getElementById('upload_target').display='none';">
					<img border="0" src="images/close.gif" alt="close" /></a></div>	
					</div>
				<div  class="white_content"> 
				<p id="upload_process" style="visibility:hidden"><img src="loader.gif" /></p>
				
				
				
				<form action="upload.php?event_id=<?php echo $this->event_id; if($doc_id) echo "&doc_id=".$doc_id."&document_server_name=".$row['document_server_name'];?>" id="upload_form" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" >
					 <table width="100%" class="table">   
					 <tr><td>
                         Document Status:
						 </td>
						 <td>  
                              <select name="document_status">
							  <option value="" selected="selected">--Select--</option>
							  <option value="Team Lead" <?php if($row['document_status']=='Team Lead') echo 'selected="selected"';?>  selected="selected">Team Lead</option>
							  <option value="User" <?php if($row['document_status']=='User') echo 'selected="selected"';?> >User</option>
							  <option value="Internal Staff" <?php if($row['document_status']=='Internal Staff') echo 'selected="selected"';?> >Internal</option>
						
							  </select>
                         </td>
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
                     
                 </form></div></div>		 					
	
				<?php  
				break;
				
				
			case 'server':
				
				$this->document_name=$document_name;
				$this->document_status=$document_status;
				$this->document_server_name=$document_server_name;
				
				if($doc_id){
					$update_sql_array = array();
					$update_sql_array[document_status] = $this->document_status;					 					 
					$this->db->update(EM_DOCUMENTS,$update_sql_array,'document_id',$doc_id);				
					}
				else {
					
					$insert_sql_array = array();
					$insert_sql_array[event_id] = $this->event_id;
					$insert_sql_array[document_name] = $this->document_name;
					$insert_sql_array[document_status] = $this->document_status;
					$insert_sql_array[document_server_name] = $this->document_server_name;						 
					$this->db->insert(EM_DOCUMENTS,$insert_sql_array);
					
				}				
				break;
				
				
						
			}		
		}//end of add document function

	function deleteDocument($event_id,$doc_id){
		ob_start();		
		
		$sql = "select * from ".EM_DOCUMENTS." where document_id = '".$doc_id."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		$row=$this->db->fetch_array($result);
		unlink("uploads/".$row[document_server_name]);		
		$sql = "delete from ".EM_DOCUMENTS." where document_id= ".$doc_id;
		$this->db->query($sql,__FILE__,__lINE__);	
				
		?>
		<script type="text/javascript">
		evt.showDocuments('<?php echo $event_id ?>',{target:'documents'});
		</script>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
		
	function showDocuments($event_id) {		
		
		ob_start();	
		$this->event_id=$event_id;
		$sql = "select * from ".EM_DOCUMENTS." where event_id = '".$this->event_id."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		?>		
		<table width="50%" >
		<?php while($row=$this->db->fetch_array($result)) {?>
		<tr>
		<td ><a href="uploads/<?php echo $row[document_server_name]; ?>" target="_blank"> <?php echo $row[document_name]; ?> </a></td>
		<td > <?php echo $row[document_status]; ?> </td>
		<td><a href="event_profile.php?event_id=<?php echo $event_id; ?>&action=upload&doc_id=<?php echo $row['document_id'];?>">edit</a></td>
		<td><a href="#" onclick="javascript: if(confirm('Are you sure?')){evt.deleteDocument('<?php echo $this->event_id; ?>','<?php echo $row['document_id'];?>', {preloader: 'prl'} ); } return false;" ><img src="images/trash.gif" border="0" /></a>	</td>
		</tr>
		<?php } ?>				
</table>	
		<?php	
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	
		/*function AddDate($runat,$event_id='',$start_date='',$end_date='',$FormName='frm_Add_Events', $note='')
	{
	
		switch($runat){
				
				case 'local':
					?>
						
						<tr>
						<th>Start Date</th><td>
						<input type="text" name="start_date" id="start_date" value="<?php echo $this->start_date;?>"  readonly="true"/>
						
					  	
						<script type="text/javascript">
						
						 var exp_date;
						 function start_cal()  {
						  var cal11=new Calendar({
								  inputField   	: "start_date",
								  dateFormat	: "%m/%d/%Y",
								  trigger		: "start_date",
								  weekNumbers   : true,
								  bottomBar		: true,
								  showTime      : 12,
								  onSelect		: function() {
														this.hide();
														document.<?php echo $FormName;?>.start_date.value=this.selection.print("%m/%d/%Y");														
														if(exp_date<=this.selection.get()) {
															document.getElementById('end_date').value="";
														}												
														end_cal(this.selection.get());	
														
													},
													
								
						  });
						  }
						
						</script>		
							</td></tr><tr>
							<th>End Date</th><td>
							<input name="end_date" type="text"  id="end_date" value="<?php echo $this->end_date;?>" readonly="true"/>
						
						<script type="text/javascript">
						  
						 function end_cal(minDate) { 
						  var cal12=new Calendar({
								  inputField   	: "end_date",
								  dateFormat	: "%m/%d/%Y",
								  trigger		: "end_date",
								  weekNumbers   : true,
								  bottomBar		: true,
								  min			: minDate,
								  showTime      : 12,
								  onSelect		: function() {
														this.hide();
														document.<?php echo $FormName;?>.expiration_date.value=this.selection.print("%m/%d/%Y");
														exp_date=this.selection.get();
													},
													
								
						  });
						  }
						 
					
               		 </script>						
							</td>	
						</tr>
						
					<?php 
				break;
				
				case 'server':
					
					$this->event_id=$event_id;
					$this->start_date=$start_date;
					$this->end_date=$end_date;
					$this->note=$note;
					
					$insert_sql_array[start_date] = $this->start_date;
					$insert_sql_array[end_date] = $this->end_date;
					$insert_sql_array[note] = $this->note;
					$insert_sql_array[event_id] = $this->event_id;
							 
					 $this->db->insert(EM_DATE,$insert_sql_array);
				
				break;
				
				default : echo 'Wrong Paramemter passed';
				
				break;
		}
		
	} // end of add date function
	
	*/
	function CreateEventStatus($runat)
	{
	
/*		ob_start();*/
		switch($runat){
		
			case 'local':
			
					$sql = "select * from ".EM_EVENT_STATUS;
					$result = $this->db->query($sql,__FILE__,__lINE__);
					$result2 = $this->db->query($sql,__FILE__,__lINE__);
					
					
					$FormName='frm_Add_Events_Status';
					$ControlNames=array("event_status"		=>array('event_status',"''","Please Enter Event Status !!  ","span_event_status"),
											);
											
						
						$ValidationFunctionName="frm_Add_Events_Status_CheckValidity";
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;

					?>
					<script language="javascript" type="text/javascript">
						function validateStatusFeild() {						
						//alert(document.getElementById("cert_type_select_credential").selectedIndex);
						if(document.getElementById("type_select").value==document.getElementById("type_select_replace").value) {
							alert("Plz.. select different event status ");
							return false;
						}
						else {					
						var location="manage_masterfields.php?id="+document.getElementById("type_select").value + "&replcedstatuswith="+document.getElementById("type_select_replace").value+ "&action=delete_event_status";						
							window.location=location;
						
						}
					
					}
				
					</script>
						
						<ul id="error_list">
							    <li><span id="span_event_status" class="normal"></span></li>
						</ul>		
					
					<form action=""  method="post" enctype="multipart/form-data" name="<?php echo $FormName; ?>">
					
					<table class="table"  width="100%">
					<tr>
					  <th width="21%">Add Event Status:</th>
					  <td width="32%"><input type="text" name="event_status" id="event_status"  /></td>
					  <td width="6%"><input type="submit" value="go" name="submit" id="submit" style="width:auto" 
					  				  onClick="return <?php echo $ValidationFunctionName?>();" />
					  </td>
					  <td width="5%">&nbsp;</td>	
				      <td width="13%"><select name="type_select" id="type_select">
                          <?php
				      
				      while($row = $this->db->fetch_array($result)){
				      	?>
                          <option value="<?php echo $row[event_status_id] ?>" ><?php echo $row[event_status] ?></option>
                          <?php
				      }
				      ?>
                        </select></td>
						
				      <td width="13%">
				      <select name="type_select_replace" id="type_select_replace">
				      <?php
				      
				      while($row = $this->db->fetch_array($result2)){
				      	?>
				      	<option value="<?php echo $row[event_status_id] ?>" ><?php echo $row[event_status] ?></option>
				      	<?php
				      }
				      ?>
				      </select>
					  </td>
				      <td width="10%"><a href="#" onclick= "javascript: if(confirm('Are you sure?')) return validateStatusFeild();">
					  				  <img src="images/trash.gif" border="0" /></a>
					  </td>
				      
					  </tr>
						</table>
						</form>
						
						
					<?php 
			break;
			
			case 'server':
					
					extract($_POST);
					$this->event_status=$event_status;
					
					$return =true;
					if($this->Form->ValidField($event_status,'empty','Please Enter Your Event Status')==false)
						$return =false;
					if($return){	
						$insert_sql_array = array();
						$insert_sql_array[event_status] = $this->event_status;
						$this->db->insert(EM_EVENT_STATUS,$insert_sql_array);
					
					$_SESSION[msg]="Event Status Created Successfully!!";
			?>
				<script type="text/javascript">
				 window.location = '<?php echo $_SERVER[PHP_SELF] ?>';
				</script>
			<?php  }
			else
			{
			?>
				<script type="text/javascript">
				 alert('Please enter event status');
				</script>
			<?php			}
			break;
			
			
			default : echo 'Wrong Paramemter passed';
				
			break;
			
			
		
		}// end of switch
/*			$html = ob_get_contents();
			ob_end_clean();
			return $html;*/
		
		
	} // end of CreateEventStatus	
	
	
	
		function Delete_Status($type_select, $type_select_replace)
		{
		
			$sql = "delete from ".EM_EVENT_STATUS." where event_status_id = ".$type_select."";
			//echo $sql;
			$this->db->query($sql,__FILE__,__LINE__);

			if($type_select_replace!='')
			{
				$sql_contact = "update ".EM_EVENT." set event_status = '".$type_select_replace."' where  event_status = '".$type_select."'";
				//echo $sql_contact;
				$sql_position = "update ".EM_STAFFING." set type = '".$type_select_replace."' where type = '".$type_select."'";
				//echo $sql_position;
				$this->db->query($sql_contact,__FILE__,__LINE__);
				$this->db->query($sql_position,__FILE__,__LINE__);
			}
				
			    $_SESSION[msg]="Event Status Replaced Successfully!!";
			?>
				<script type="text/javascript">
				 window.location = '<?php echo $_SERVER[PHP_SELF] ?>';
				</script>
			<?php			
		
		
	} // end of Del Status
	
/*	function showGrid($tablename='em_date')
	{
		$this->tablename=$tablename;
		
		?>
		<div id="grid1"></div>
		<script type="text/javascript">
		var thegrid = new drasticGrid('grid1', {
			pathimg: "app_code/classes.datagrid/img/"
			});
		</script>
		<?php 
		
	} // end of showGrid
	*/
	function ShowEventNote($event_id)
	{
		ob_start();
		$this->event_id=$event_id;
		$sql = "select * from ".EM_EVENT." where event_id='".$this->event_id."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		$row=$this->db->fetch_array($result);
		?>
		<p class="gray">Note:<br />
		<?php if($row['note']!='0') echo $row['note']; ?></p>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html; 
	} // end of show note

	function getEventStatus($event_id)
	{
		ob_start();
		$this->event_id=$event_id;
		$sql = "select * from ".EM_EVENT." where event_id='".$this->event_id."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		$row=$this->db->fetch_array($result);
		
		
		$sql_status = "select * from ".EM_EVENT_STATUS." where event_status_id='".$row['event_status']."'";
		$result_status = $this->db->query($sql_status,__FILE__,__lINE__);
		$row_status=$this->db->fetch_array($result_status);
		echo $row_status['event_status'];
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html; 
	}
	function ShowEventDate($event_id)
	{
		ob_start();
		$this->event_id=$event_id;
		$sql = "select * from ".EM_DATE." where event_id='".$this->event_id."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		?><br/><p class="gray">Dates:<br/><?php
		while($row=$this->db->fetch_array($result)) {
		$d = explode("-",$row['event_date']);			
		echo date("D jS M " ,mktime(0, 0, 0, $d[1], $d[2], $d[0]));
		echo $row['start_time']." - ".$row['end_time'];
		?>
		<a href="#" onclick="javascript: evt.editEventDate('local','<?php echo $this->event_id;?>','<?php echo $row['date_id'];?>',
					{ onUpdate: function(response,root){
							 document.getElementById('div_event').innerHTML=response;
		  				 	 document.getElementById('div_event').style.display='';
							 start_cal();		 
							 $('#start_time').ptTimeSelect();
							 $('#end_time').ptTimeSelect();
					}, preloader: 'prl'
						} ); return false;" >Edit</a>
						
		<a href="#" onclick="javascript: if(confirm('Are you sure?')){evt.deleteEventDate('<?php echo $this->event_id;?>','<?php echo $row['date_id'];?>', {preloader: 'prl'} ); } return false;" ><img src="images/trash.gif" border="0" /></a>							
		<br/>
		<?php	
		}
		?></p>
		<a href="#" onclick="javascript: evt.addEventDate('local','<?php echo $this->event_id;?>',
					{ onUpdate: function(response,root){
							 document.getElementById('div_event').innerHTML=response;
		  				 	 document.getElementById('div_event').style.display='';
							 start_cal();
							 $('#start_time').ptTimeSelect();
							 $('#end_time').ptTimeSelect();
							 }, preloader: 'prl'
						} );  return false;" >Add</a><br/><br/>
		
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function addEventDate($runat,$event_id,$event_date='',$start_time='',$end_time=''){
		ob_start();			
		$this->event_id=$event_id;
		switch($runat) {
			case 'local':
				$FormName='FrmAddDate';				
				$ControlNames=array("event_date"	=>array('event_date',"''","Please Select Event Date ","span_event_date")															
									);
										
				$ValidationFunctionName="CheckValidity";
				$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
				echo $JsCodeForFormValidation;						
				?>				
				<script type="text/javascript">
				function checkTime(start_time,end_time){					
					var temp_start1 = new Array();
					var temp_end1 = new Array();
					var temp_start = new Array();
					var temp_end = new Array()
					var flag=0;
					temp_start1 = start_time.split(" ");
					temp_end1 = end_time.split(" ");
					temp_start = temp_start1[0].split(":");
					temp_end = temp_end1[0].split(":");
									
					if(temp_start1[1]=='pm') {
						temp_start[0]=parseInt(temp_start[0])+12;
					}
						
					if(temp_end1[1]=='pm') {
						temp_end[0]=parseInt(temp_end[0])+12;
					}
					if(parseInt(temp_start[0]) > parseInt(temp_end[0])) {
						flag=1;
						}
					else if(parseInt(temp_start[0]) == parseInt(temp_end[0]) && parseInt(temp_start[1]) > parseInt(temp_end[1])) {
						flag=1;
						}
					if(flag==1) { alert('Start time is greater then end time !!'); return false; }
					else return true;			
				
				}				
				
				</script>				
				<div class="prl">&nbsp;</div>
				<div id="lightbox">
				
					<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle">Add Event Date</div>
					<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_event').style.display='none';jQuery.ptTimeSelect.closeCntr();"><img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;" class="form_main">
				<ul id="error_list">
						<li><span id="span_event_date"></span></li>									
				</ul>		
				<form name="<?php echo $FormName; ?>" method="post" action="" enctype="multipart/form-data" >
				<table class="table" width="100%">
				<tr>
					<th>Event Date</th><td>
					<?php 
					if($event_date=='')
					{
					  
						$sql = "select * from ".EM_DATE." where event_id= ".$this->event_id." order by  event_date desc limit 1";
						$result = $this->db->query($sql,__FILE__,__lINE__);			
						$row = $this->db->fetch_array($result);		
						$d = explode("-",$row['event_date']);		
						if($row['event_date']) $event_date= date("Y-m-d",mktime(0, 0, 0, $d[1], ($d[2]+1), $d[0]));
						else $event_date= date("Y-m-d");												
					}
					?>
				<input name="event_date" type="text"  id="event_date" value="<?php echo $event_date;?>" readonly="true"/>			
				<script type="text/javascript">	
				 
				 function start_cal()  {				 
				 new Calendar({
				 inputField   	: "event_date",
				 dateFormat		: "%Y-%m-%d",
				 trigger		: "event_date",
				 weekNumbers   	: true,
				 bottomBar		: true,	
				 selected       : <?php echo $event_date;?>,			 
				 onSelect		: function() {
										this.hide();
										document.<?php echo $FormName;?>.event_date.value=this.selection.print("%Y-%m-%d");									
									}				
				  });
				  }
				
				</script>
				<a href="javascript:void(0);" onclick="javascript: document.<?php echo $FormName;?>.event_date.value='';"><img src="images/trash.gif" border="0"/></a>		
					</td>
					<th>Start Time</th>
					<td><input id='start_time' name="start_time" type='text' value="00:00 am" size="8" maxlength="8" ></td>
					</td>
					<th>End Time</th>
					<td><input id='end_time' name="end_time" type='text' value="00:00 pm" size="8" maxlength="8" ></td>
					<td><input type="button" name="submit" value="Add" id="submit" onClick="javascript: if(<?php echo $ValidationFunctionName; ?>()) { if(checkTime(this.form.start_time.value,this.form.end_time.value)) {  evt.checkConflict(this.form.event_date.value,'<?php echo $event_id; ?>',{onUpdate: function(response,root){
					res_arr = response.split('^');
					if(res_arr[0]>0 || res_arr[1]>0){
					 if(confirm('you have conflict in '+res_arr[0]+' contact(s) <?php if(count($_SESSION[contact_conflict])>0){ echo "with contact id(s) ";
					 for($k=0;$k<count($_SESSION[contact_conflict]);$k++)
					 	echo $_SESSION[contact_conflict][$k].","; } ?> and '+res_arr[1]+' equipment(s) <?php if(count($_SESSION[equipment_conflict])>0){ echo "with equipment id(s) ";
					 		for($k=0;$k<count($_SESSION[equipment_conflict]);$k++)
					 	echo $_SESSION[equipment_conflict][$k].","; } ?>.\nDo you wish to continue.'))
					 {					 	
						if(res_arr[0]>0){
							evt.addTaskForContact('<?php echo $event_id; ?>',{preloader:'prl'});
						}
						if(res_arr[1]>0){
							evt.addTaskForEquipment('<?php echo $event_id; ?>',{preloader:'prl'});
						}
						evt.addEventDate('server','<?php echo $event_id; ?>',document.getElementById('event_date').value,document.getElementById('start_time').value,document.getElementById('end_time').value,{target:'div_event', preloader: 'prl'});
					 } else {
					 document.getElementById('div_event').style.display='none';
					 evt.ShowEventDate('<?php echo $event_id ?>',{target:'event_date',preloader: 'prl'});
					 }					 
				} else {
						evt.addEventDate('server','<?php echo $event_id; ?>',document.getElementById('event_date').value,document.getElementById('start_time').value,document.getElementById('end_time').value,{target:'div_event', preloader: 'prl'});
					}
					
					}, preloader: 'prl'});   } } jQuery.ptTimeSelect.closeCntr();return false;" /></td>
					</tr>
					</table>
				  </form>
					</div></div></div>
				<?php
				break;
			case 'server':
								
				$sql_date = "select event_date from ".EM_DATE." where event_id= '".$event_id."'";
				$result_date = $this->db->query($sql_date,__FILE__,__LINE__);
				while($row_date = $this->db->fetch_array($result_date))
					if($row_date['event_date']==$event_date) $flag=1;				
				if($flag==1) {
					?><script> alert("Select a new date!! This date is already exist!!");
						evt.addEventDate('local','<?php echo $this->event_id;?>',
						{ onUpdate: function(response,root){
							 document.getElementById('div_event').innerHTML=response;
		  				 	 document.getElementById('div_event').style.display='';
							 start_cal();					 					 
							 }, preloader: 'prl'
						} );							
						</script> <?php				
					}
				else {
					$insert_sql_array[event_date] = $event_date;
					$insert_sql_array[end_time] = $end_time;
					$insert_sql_array[start_time] = $start_time;
					$insert_sql_array[event_id] = $event_id;	
					$this->db->insert(EM_DATE,$insert_sql_array);
					$con = new Event_Contacts();
					$sql = "select * from ".EM_DATE." where event_id= '".$event_id."'";
					$result = $this->db->query($sql,__FILE__,__LINE__);
					
					$sql_staff = "select * from ".EM_STAFFING." where event_id= '".$event_id."' and status='Staffed'";
					$result_staff = $this->db->query($sql_staff,__FILE__,__LINE__);
					while($row_staff = $this->db->fetch_array($result_staff)) {
					  if($row_staff[contact_id]){
						while($row = $this->db->fetch_array($result)) {
							$con->Availability($row_staff[contact_id],$event_id,$row['event_date'],$row['start_time'],$row['end_time'],'Event Assigned');
						}
					  }
					}
					/********************************************/
					$sql = "select * from ".EM_EVENT." where event_id= '".$event_id."'";
					$result = $this->db->query($sql,__FILE__,__LINE__);
					$row = $this->db->fetch_array($result);
					if(count($_SESSION[equipment_conflict])>0){
						$sql_eve = "select * from ".EM_EVENT_EQUIPMENT." where event_id= '".$event_id."'";
						$result_eve = $this->db->query($sql_eve,__FILE__,__LINE__);
						while($row_eve = $this->db->fetch_array($result_eve)) {
						  $sql_avail = "update ".EM_EQUIPMENT_AVAILABILITY." set `status`='".$row[group_event_id]."' where `event_id`= '".$event_id."' and `unavailability_date`='".$event_date."' and `equipment_id`='".$row_eve[equipment_id]."'";
						  ?><script type="text/javascript">alert('<?php echo $sql_avail; ?>')</script><?
						  $this->db->query($sql_avail,__FILE__,__LINE__);
						}
					} 
					else {
						$equipment = new Equipment();
						$sql = "select * from ".EM_DATE." where event_id= '".$event_id."'";
						$result = $this->db->query($sql,__FILE__,__LINE__);
						
						$sql_equip = "select * from ".EM_EVENT_EQUIPMENT." where event_id= '".$event_id."'";
						$result_equip = $this->db->query($sql_equip,__FILE__,__LINE__);
						while($row_equip = $this->db->fetch_array($result_equip)) {
							while($row = $this->db->fetch_array($result)) {
								$equipment->assignEquipment($row_equip[equipment_id],$event_id);
							}
						}
					}
				}
				?>
				<script type="text/javascript">
				document.getElementById('div_event').innerHTML='';
				evt.ShowEventDate('<?php echo $this->event_id ?>',{target:'event_date',preloader: 'prl'});
				</script>
				<?php				
				break;
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} 
	
		
	function editEventDate($runat,$event_id,$date_id='',$event_date='',$start_time='',$end_time=''){
		ob_start();		
		$this->event_id=$event_id;
		switch($runat) {
			case 'local':
				$FormName='FrmEditDate';				
				$ControlNames=array("event_date"	=>array('event_date',"''","Please Select Event Date ","span_event_date")														
									);
												
						$ValidationFunctionName="CheckValidity";
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
				?>
				<script>
				function checkTime(start_time,end_time){
					
					var temp_start1 = new Array();
					var temp_end1 = new Array();
					var temp_start = new Array();
					var temp_end = new Array()
					var flag=0;
					temp_start1 = start_time.split(" ");
					temp_end1 = end_time.split(" ");
					temp_start = temp_start1[0].split(":");
					temp_end = temp_end1[0].split(":");
									
					if(temp_start1[1]=='pm') {
						temp_start[0]=parseInt(temp_start[0])+12;
					}
						
					if(temp_end1[1]=='pm') {
						temp_end[0]=parseInt(temp_end[0])+12;
					}
					if(parseInt(temp_start[0]) > parseInt(temp_end[0])) {
						flag=1;
						}
					else if(parseInt(temp_start[0]) == parseInt(temp_end[0]) && parseInt(temp_start[1]) > parseInt(temp_end[1])) {
						flag=1;
						}
					
					if(flag==1) { alert('Start time is greater then end time !!'); return false; }
					else return true;			
				
				}				
				</script>				
				<div class="prl">&nbsp;</div>
				<div id="lightbox">
				
					<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle">Edit Event Date</div>
					<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_event').style.display='none';jQuery.ptTimeSelect.closeCntr();"><img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;" class="form_main">
				<ul id="error_list">
						<li><span id="span_event_date"></span></li>								
				</ul>
						
				<?php				
				$sql = "select * from ".EM_DATE." where date_id= ".$date_id;
				$result = $this->db->query($sql,__FILE__,__lINE__);			
				$row = $this->db->fetch_array($result);	
				?>
				<form name="<?php echo $FormName; ?>" method="post" action="" enctype="multipart/form-data" >
				<input name="event_date_orig" type="hidden"  id="event_date_orig" value="<?php echo $row['event_date'];?>" />
				<table class="table" width="100%">
				<tr>
				<th>Event Date</th><td><input name="event_date" type="text"  id="event_date" value="<?php echo $row['event_date'];?>" readonly="true"/>			
				<script type="text/javascript">	
				 
				 function start_cal()  {
				 //alert('sdkjfsdkj');
				 new Calendar({
				 inputField   	: "event_date",
				 dateFormat		: "%Y-%m-%d",
				 trigger		: "event_date",
				 weekNumbers   	: true,
				 bottomBar		: true,				
				 onSelect		: function() {
										this.hide();
										document.<?php echo $FormName;?>.event_date.value=this.selection.print("%Y-%m-%d");									
									}				
				  });
				  }
				
				</script>	
				<a href="javascript:void(0);" onclick="javascript: document.<?php echo $FormName;?>.event_date.value='';"><img src="images/trash.gif" border="0"/></a>	
					</td>
					
					<th>Start Time</th>
					<td><input id='start_time' name="start_time" type='text' value="<?php echo $row['start_time'];?>" size=8 maxlength=8 ></td>
					<?php 
							$time=explode(" ",$row['start_time']);
					
					?>
					</td>
					<th>End Time</th>
					<td><input id='end_time' name="end_time" type='text' value="<?php echo $row['end_time'];?>" size=8 maxlength=8 ></td>
					<?php 
							$time=explode(" ",$row['end_time']);
					
					?>					<td><input type="button"  name="submit" value="Set" id="submit" onClick="javascript: if(<?php echo $ValidationFunctionName; ?>()) { 
if(checkTime(this.form.start_time.value,this.form.end_time.value))
 { if(!(this.form.event_date.value==this.form.event_date_orig.value))
			{
					evt.checkConflict(this.form.event_date.value,'<?php echo $event_id; ?>',
					{onUpdate: function(response,root)
						{
							res_arr = response.split('^');
							if(res_arr[0]>0 || res_arr[1]>0)
							{
								 if(confirm('you have conflict in '+res_arr[0]+' contact(s) and '+res_arr[1]+' equipment.\nDo you wish to continue.'))
								 {					 	
									if(res_arr[0]>0){
										evt.addTaskForContact('<?php echo $event_id; ?>',{preloader:'prl'});
									}
									if(res_arr[1]>0){
										evt.addTaskForEquipment('<?php echo $event_id; ?>',{preloader:'prl'});
									}
									evt.editEventDate('server','<?php echo $event_id; ?>','<?php echo $row[date_id]?>',document.getElementById('event_date').value,document.getElementById('start_time').value,document.getElementById('end_time').value,{ preloader: 'prl'});
									} else 
									{
										 document.getElementById('div_event').style.display='none';
										 evt.ShowEventDate('<?php echo $event_id ?>',{target:'event_date',preloader: 'prl'});
									 }					 
								} else {
						evt.editEventDate('server','<?php echo $event_id; ?>','<?php echo $row[date_id]; ?>',document.getElementById('event_date').value,document.getElementById('start_time').value,document.getElementById('end_time').value,{ preloader: 'prl'});
					}
				}, preloader: 'prl'}); }
				else
				{
				evt.editEventDate('server','<?php echo $event_id; ?>','<?php echo $row[date_id]?>',document.getElementById('event_date').value,document.getElementById('start_time').value,document.getElementById('end_time').value,{ preloader: 'prl'});
				
				}
				 document.getElementById('div_event').style.display='none'; } }jQuery.ptTimeSelect.closeCntr();; return false;" /></td>
					</tr>
					</table>
				  </form>
					</div></div></div>
				<?php
				break;
				
			case 'server':		
				
				$sql_date_pre = "select event_date from ".EM_DATE." where date_id= '".$date_id."'";
				$result_date_pre = $this->db->query($sql_date_pre,__FILE__,__LINE__);
				$row_date_pre = $this->db->fetch_array($result_date_pre);
				$pre_date=$row_date_pre['event_date'];
				
				$sql_date = "select event_date from ".EM_DATE." where event_id= '".$event_id."'";
				$result_date = $this->db->query($sql_date,__FILE__,__LINE__);
				$flag=0;
				while($row_date = $this->db->fetch_array($result_date))
					if($row_date['event_date']==$event_date && $pre_date != $event_date) $flag=1;				
				//if($pre_date == $event_date) $flag=1;	
				if($flag==1) {
					?><script> alert("Select a new date!! This date is already exist!!");
						evt.editEventDate('local','<?php echo $this->event_id;?>','<?php echo $date_id; ?>',
						{ onUpdate: function(response,root){
							 document.getElementById('div_event').innerHTML=response;
		  				 	 document.getElementById('div_event').style.display='';
							 start_cal();					 					 
							 }, preloader: 'prl'
						} );							
						</script> <?php				
					}
				else {
					$update_sql_array[end_time] = $end_time;
					$update_sql_array[start_time] = $start_time;
					$update_sql_array[event_date] = $event_date;
					$this->db->update(EM_DATE,$update_sql_array,'date_id',$date_id);
					?>
					<script type="text/javascript">
					document.getElementById('div_event').innerHTML='';
					evt.ShowEventDate('<?php echo $this->event_id ?>',{target:'event_date',preloader: 'prl'});
					</script>
					<?php
					break;
				}
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} 
	
	function deleteEventDate($event_id='',$date_id){
		ob_start();		
		$sql = "select * from ".EM_DATE." where date_id= ".$date_id;
		$result = $this->db->query($sql,__FILE__,__lINE__);	
		$row = $this->db->fetch_array($result);
		
		$sql = "delete from ".EM_DATE." where date_id= ".$date_id;
		$this->db->query($sql,__FILE__,__lINE__);	
		
		$sql = "delete from ".EM_EQUIPMENT_AVAILABILITY." where unavailability_date= '".$row[event_date]."' and event_id='".$event_id."'";
		$this->db->query($sql,__FILE__,__lINE__);	
		?>
		<script type="text/javascript">
		document.getElementById('div_event').innerHTML='';
		evt.ShowEventDate('<?php echo $event_id ?>',{target:'event_date'});
		</script>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} 
	
	
	function ShowEventPOC($event_id)
	{
		ob_start();
		$this->event_id=$event_id;
		$sql = "select * from ".EM_POC." where event_id='".$this->event_id."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		?><p class="gray">POC:<?php
		while($row=$this->db->fetch_array($result)) {
		?><br/><?php 
		echo $row['rank']." ".$row['first_name']." ".$row['last_name']; ?>::
		<a href="#" onclick="javascript: evt.editEventPOC('local','<?php echo $this->event_id;?>','<?php echo $row['poc_id'];?>',
					{ onUpdate: function(response,root){
							 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';				
							
							 }, preloader: 'prl'
						} ); return false;" >Edit</a>
		<a href="#" onclick="javascript: evt.deleteEventPOC('<?php echo $row['poc_id'];?>','<?php echo $this->event_id;?>',
					{ onUpdate: function(response,root){
							 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';				
							
							 }, preloader: 'prl'
						} ); return false;" ><img src="images/trash.gif" border="0" /></a>				
		
		<br/>	
		Phone: &nbsp;<?php echo '('.substr($row[phone], 0, 3).')'.substr($row[phone], 3, 3).'-'.substr($row[phone], 6, 4);?>
		&nbsp;&nbsp;Cell: &nbsp;<?php echo $row['cell']; ?><br/>
		<a href="mailto: <?php echo $row['email']; ?>"><?php echo $row['email']; ?></a>
		<?php
		}
		?>
		<br />
		<a href="#" onclick="javascript: evt.editEventPOC('local','<?php echo $this->event_id;?>',
					{ onUpdate: function(response,root){
							 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';				
							
							 }, preloader: 'prl'
						} ); return false;" >Add</a>	
		</p><br /><?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html; 
	} 
	
	function deleteEventPOC($poc_id,$event_id)
	{
		ob_start();
		
		$sql="delete from ".EM_POC." where poc_id='".$poc_id."'";
		$this->db->query($sql,__FILE__,__lINE__);
		?>
		<script type="text/javascript">
		document.getElementById('div_credential').style.display='none'; 
		evt.ShowEventPOC('<?php echo $event_id ?>',{target:'event_poc',preloader: 'prl'})
		</script>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html; 
		
		
	}
	
	function editEventPOC($runat,$event_id,$poc_id='',$rank='',$first_name='',$last_name='',$phone='',$cell='',$email='')
	{
		ob_start();			
		$this->event_id=$event_id;
		$this->poc_id=$poc_id;
		switch($runat) {
			case 'local':
			
			
			$FormName='frm_Add_Events_poc';
			
			$ControlNames=array("first_name"	=>array('first_name',"''","Please Enter First Name !! ","span_first_name"),
								"last_name" =>array('last_name',"''","Please Enter Last Name !! ","span_last_name")
									);
											
						
						$this->ValidationFunctionName="Addpoc_CheckValidity";
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,
												$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
			
			$sql="select * from ".EM_POC." where poc_id='".$this->poc_id."'";
			$result=$this->db->query($sql,__FILE__,__LINE__);
			$row=$this->db->fetch_array($result);
			

			?>
			<div class="prl">&nbsp;</div>
						<div id="lightbox">
							<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
							<div id="TB_ajaxWindowTitle">Event POC</div>
							<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_credential').style.display='none';"><img border="0" src="images/close.gif" alt="close" /></a></div>
						</div>
						<div  class="white_content"> 
						<div style="padding:20px;" class="form_main">		
							  <ul id="error_list">
							    <li><span id="span_first_name" class="normal"></span></li>
								<li ><span id="span_last_name" class="normal"></span></li>
							  </ul>		
						<form action=""  method="post" enctype="multipart/form-data" name="<?php echo $FormName; ?>">
				<table class="table" width="100%">
				<tr>
					<th>Rank</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Phone</td>
					<th>Cell</td>
					<th>Email</td>
				</tr>
				<tr>
					<td><input type="text" name="rank" id="rank" size="30" value="<?php echo $row['rank'];?>" /></td>
					<td><input type="text" name="first_name" id="first_name" size="30" value="<?php echo $row['first_name'];?>" /></td>
					<td><input type="text" name="last_name" id="last_name" size="30" value="<?php echo $row['last_name'];?>" /></td>
					<td><input type="text" name="phone" id="phone" size="30" value="<?php echo $row['phone'];?>" /></td>
					<td><input type="text" name="cell" id="cell" size="30" value="<?php echo $row['cell'];?>" /></td>
					<td><input type="text" name="email" id="email" size="30" value="<?php echo $row['email'];?>" /></td>
				</tr>
				<tr>
					<td colspan="4" align="right" ><input type="button" name="btn" value="Ok" id="btn" onClick="javascript: if(<?php echo $this->ValidationFunctionName?>()) { evt.editEventPOC('server','<?php echo $this->event_id ?>','<?php echo $this->poc_id ?>',this.form.rank.value,this.form.first_name.value,this.form.last_name.value,this.form.phone.value,this.form.cell.value,this.form.email.value,{target:'div_credential', preloader: 'prl'}); } return false;"  style="width:auto" />				 </td>
				</tr>	
				</table>
				</form>		
						</div></div></div>
			<?php 
			break;
			case 'server':
				$this->event_id=$event_id;
				$this->poc_id=$poc_id;
				$this->rank=$rank;
				$this->first_name=$first_name;
				$this->last_name=$last_name;
				$symbols= explode(',',SYMBOLS);
				$this->phone=str_replace($symbols,'',$phone);
				$this->cell=$cell;
				$this->email=$email;
				
					$return =true;
					if($this->Form->ValidField($first_name,'empty','Please Enter First Name')==false)
						$return =false;
					if($this->Form->ValidField($last_name,'empty','Please Enter Last Name')==false)
						$return =false;
					
		
					
					if($return){
					
						 if($this->poc_id)
						 {
						 	$update_sql_array = array();
						 	$update_sql_array[rank] = $this->rank;
						 	$update_sql_array[first_name] = $this->first_name;
						 	$update_sql_array[last_name] = $this->last_name;
						 	$update_sql_array[phone] = $this->phone;
							$update_sql_array[cell] = $this->cell;
						 	$update_sql_array[email] = $this->email;
									 
						 	$this->db->update(EM_POC,$update_sql_array,'poc_id',$this->poc_id);
						 }
						 else
						 {
						 	
						 	$insert_sql_array = array();
							$insert_sql_array[event_id] = $this->event_id;
						 	$insert_sql_array[rank] = $this->rank;
						 	$insert_sql_array[first_name] = $this->first_name;
						 	$insert_sql_array[last_name] = $this->last_name;
						 	$insert_sql_array[phone] = $this->phone;
							$insert_sql_array[cell] = $this->cell;
						 	$insert_sql_array[email] = $this->email;
							
							$this->db->insert(EM_POC,$insert_sql_array);		 
						 	
						 
						 }
						 
						 ?>
						<script type="text/javascript">
						document.getElementById('div_credential').style.display='none'; 
				 		evt.ShowEventPOC('<?php echo $this->event_id ?>',{target:'event_poc',preloader: 'prl'})
						</script>
						<?php
						}
			 break;
			default : echo 'Wrong Paramemter passed';	
		} // end of switch	
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html; 
		
	}  // end of editEventPOC
	
	function checkConflict($event_date,$event_id)
	{
		ob_start();
		
		$contact_conflict_array = array();
		$equipment_conflict_array = array();
		$x1 = 0;
		$x2 = 0;
		$return = false;
		$sql_event_contact = "select * from ".EM_STAFFING." where event_id='$event_id'";
		$result_event_contact = $this->db->query($sql_event_contact,__FILE__,__lINE__);
		while($row_event_contact = $this->db->fetch_array($result_event_contact)){
			$sql_unavail = "select * from ".EM_CONTACT_UNAVAILABILITY." where unavailable_date='$event_date' and contact_id='$row_event_contact[contact_id]'";
			$result_unavail = $this->db->query($sql_unavail,__FILE__,__lINE__);
			if($this->db->num_rows($result_unavail)>0){
				$row_unavail = $this->db->fetch_array($result_unavail);
				$contact_conflict_array[$x1++] = $row_unavail[contact_id];
				$return = true;
			}
		}
		$_SESSION[contact_conflict] = $contact_conflict_array;
		
		/**********************************************************/
		/*$sql_date = "select * from ".EM_DATE." where event_id= ".$this->event_id." order by  event_date desc limit 1";
		$result_date = $this->db->query($sql_date,__FILE__,__lINE__);			
		$row_date = $this->db->fetch_array($result_date);	
		$dt_arr	= explode('-',$row_date['event_date']);
		$m = $row_date['event_date'][1];
		$d = $row_date['event_date'][2];
		$y = $row_date['event_date'][0];
		$Eve_dt = date('Y-m-d',mktime(0, 0, 0, $m  , $d+3, $y));	*/
		
		$sql_event_equipment = "select * from ".EM_EVENT_EQUIPMENT." where event_id='$event_id'";
		$result_event_equipment = $this->db->query($sql_event_equipment,__FILE__,__lINE__);
		while($row_event_equipment = $this->db->fetch_array($result_event_equipment)){
			$sql_unavail_equipment = "select * from ".EM_EQUIPMENT_AVAILABILITY." where unavailability_date='$event_date' and equipment_id='$row_event_equipment[equipment_id]'";
			$result_unavail_equipment = $this->db->query($sql_unavail_equipment,__FILE__,__lINE__);
			if($this->db->num_rows($result_unavail_equipment)>0){
				$row_unavail_equipment = $this->db->fetch_array($result_unavail_equipment);
				$equipment_conflict_array[$x2++] = $row_unavail_equipment[equipment_id];
				$return = true;
			}
		}
		$_SESSION[equipment_conflict] = $equipment_conflict_array;
		$equip_count = count($equipment_conflict_array);
		$cont_count = count($contact_conflict_array);
		return $cont_count.'^'.$equip_count;
		$html = ob_get_contents();
		ob_end_clean();
		return $html; 
	}
	
	function addTaskForContact($event_id){
		ob_start();
		$task = new Tasks();
		$contacts = $_SESSION[contact_conflict];
		foreach($contacts as $value){
			$sql = "select * from ".TBL_CONTACT." WHERE contact_id='$value'";
			$result = $this->db->query($sql);
			$row = $this->db->fetch_array($result);
			
			$cont_arr_task = array();			
			$cont_arr_task_assign = array();
			$cont_arr_task[user_id] = $_SESSION[user_id];
			$cont_arr_task[assigned_to] = $_SESSION[user_id];
			$cont_arr_task[title] = 'Fix '.$row[first_name].' '.$row[last_name].', assigned to multiple events';
			$cont_arr_task[due_date] = time();
			$cont_arr_task[is_global] = 'no';
			$cont_arr_task[completed] = 'No';
			
			$cont_arr_task_assign[module] = 'EM_EVENT';
			$cont_arr_task_assign[module_id] = $event_id;
			$cont_arr_task_assign[profile_page] = 'event_profile.php';
			$cont_arr_task_assign[profile_id] = 'event_id';
/*		?><script type="text/javascript">alert('<?php echo print_r($cont_arr_task); ?>')</script><?php
*/			
			$task->AddTaskOnTheFly($cont_arr_task,$cont_arr_task_assign);
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html; 
	}
	
	function addTaskForEquipment($event_id){
		ob_start();
		$task = new Tasks();
		$equipment = $_SESSION[equipment_conflict];
		foreach($equipment as $value){
			$sql = "select * from ".EM_EQUIPMENT." WHERE equipment_id='$value'";
			$result = $this->db->query($sql);
			$row = $this->db->fetch_array($result);
			
			$sql_eve = "select * from ".EM_EVENT." WHERE event_id='$event_id'";
			$result_eve = $this->db->query($sql_eve);
			$row_eve = $this->db->fetch_array($result_eve);
			
			$equip_arr_task = array();			
			$equip_arr_task_assign = array();
			$equip_arr_task[user_id] = $_SESSION[user_id];
			$equip_arr_task[assigned_to] = $_SESSION[user_id];
			$equip_arr_task[title] = 'WARNING - Equipment Availability Conflict '.$row[equipment_name].' with '.$row_eve[group_event_id];
			$equip_arr_task[due_date] = time();
			$equip_arr_task[is_global] = 'no';
			$equip_arr_task[completed] = 'No';
			
			$equip_arr_task_assign[module] = 'EM_EVENT';
			$equip_arr_task_assign[module_id] = $event_id;
			$equip_arr_task_assign[profile_page] = 'event_profile.php';
			$equip_arr_task_assign[profile_id] = 'event_id';
			
			$task->AddTaskOnTheFly($equip_arr_task,$equip_arr_task_assign);
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html; 
	}
	
	function addContactDocuments($runat,$contact_id,$doc_id='',$document_name='',$document_status='',$document_server_name='') {
		$this->contact_id=$contact_id;
		switch($runat) {
			case 'local':
				$sql = "select * from ".EM_CONTACT_DOCUMENTS." where document_id = '".$doc_id."'";
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
						window.location="contact_profile.php?contact_id=<?php echo $this->contact_id; ?>";
						}
					  else if (success == 2){
						alert('Record Updated Successfully..');
						window.location="contact_profile.php?contact_id=<?php echo $this->contact_id; ?>";
						}
					  else if (success == 3){
						 result = '<span class="emsg">Selected File Is Of 0 Bytes!!<br/><\/span><br/><br/>';
						 document.getElementById('upload_process').style.visibility = 'hidden';
					  	 document.getElementById('upload_form').innerHTML = result + '<label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>';
						 document.getElementById('upload_form').style.visibility = 'visible';
					  	}
					 else if (success == 0){
						 result = '<span class="emsg">One Or More Fields Are empty!!<br/><\/span><br/><br/>';
						 document.getElementById('upload_process').style.visibility = 'hidden';
					  	 document.getElementById('upload_form').innerHTML = result + '<label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>';
						 document.getElementById('upload_form').style.visibility = 'visible';
					  	}
					  
					        
					  return true;   
				}
				
				</script> 			   
			   	
				
				<div class="prl">&nbsp;</div>

				<div id="lightbox" style=" position:absolute;!important;" >		
				<div style="background-color:#ADC2EB;" class="ajax_heading">
					<div id="TB_ajaxWindowTitle"><?php if($doc_id) echo 'Edit Document'; else echo'Add Document' ?> </div>
					
					<div id="TB_closeAjaxWindow"><a href="contact_profile.php?contact_id=<?php echo $this->contact_id; ?>" 
					onclick="javascript: document.getElementById('upload_target').display='none';">
					<img border="0" src="images/close.gif" alt="close" /></a></div>	
					</div>
				<div  class="white_content"> 
				<p id="upload_process" style="visibility:hidden"><img src="loader.gif" /></p>
				
								
				<form action="upload.php?flag=contact&contact_id=<?php echo $this->contact_id; if($doc_id) echo "&doc_id=".$doc_id."&document_server_name=".$row['document_server_name'];?>" id="upload_form" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" >
					 <table width="100%" class="table">   
					 <tr><!--<td>
                         Document Status:
						 </td>
						 <td>  
                              <select name="document_status">
							  <option value="" selected="selected">--Select--</option>
							  <option value="Public" <?php if($row['document_status']=='Public') echo 'selected="selected"';?> >Public</option>
							  <option value="Internal Staff" <?php if($row['document_status']=='Internal') echo 'selected="selected"';?> >Internal</option>			  
							  </select>
                         </td>-->
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
                     
                 </form></div></div>		 					
	
				<?php  
				break;
				
				
			case 'server':
			
				$this->document_name=$document_name;
				$this->document_status=$document_status;
				$this->document_server_name=$document_server_name;
				/*if($this->document_sever_name){ ?><script> alert('aaaaaaaa'); </script> <?php }*/
				if($doc_id){
					$update_sql_array = array();
					if($this->document_name)
					//$update_sql_array[document_name] = $this->document_name;
					//$update_sql_array[document_status] = $this->document_status;
					$update_sql_array[document_server_name] = $this->document_server_name;					 					 
					$this->db->update(EM_CONTACT_DOCUMENTS,$update_sql_array,'document_id',$doc_id);								
					}
				else {
					$insert_sql_array[contact_id] = $this->contact_id;
					$insert_sql_array[document_name] = $this->document_name;
					//$insert_sql_array[document_status] = $this->document_status;
					$insert_sql_array[document_server_name] = $this->document_server_name;	
					$this->db->insert(EM_CONTACT_DOCUMENTS,$insert_sql_array);										
				}
				?>					
				<script>
					window.href = "contact_profile.php?contact_id=<?php echo $this->contact_id; ?>";
				</script>
				<?php					
				break;					
			}		
		}//end of add document function
	
	function download_document($doc='',$name=''){

	  $doc = 'uploads/'.$doc;
	  $path_parts = pathinfo($doc);
	  $ctype = @mime_content_type($doc);
	  header("cache-control:must-revalidate,must-revalidate,post-check=0,");
	  header("content-disposition:attechment,filename=$name");
	  header("content-length:" . filesize($doc));
	  header("content-type:$ctype");
	  ob_clean();
	  flush();
	  readfile($doc);
	  exit();
	}	

}


?>
