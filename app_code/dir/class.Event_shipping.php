<?php

/***********************************************************************************

			Class Discription : 
			
			Class Memeber Functions :
			
			
			Describe Function of Each Memeber function: 
			
									
									 

************************************************************************************/
class Event_Shipping
{
	var $contact_id;
	var $equipment_id;
	var $to;
	var $tracking_number;
	var $shipping_vendor;
	var $phone;
	var $address1;
	var $address2;
	var $city;
	var $state;
	var $zip;
	var $fax;
	var $db;
	var $validity;
	var $Form;
	var $equipment;


	
		function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
		$this->equipment = new Equipment();

	}

	function getAssignShipping($event_id,$type='')
	{
		ob_start();
		
			$sql="select * from ".EM_SHIPPING." where event_id='".$event_id."'";
			$result=$this->db->query($sql,__FILE__,__LINE__);
			?>
			<table width="100%" <?php if ($type == 'onfly') { echo 'class="event_form small_text"';} else { echo 'class="table"'; }?>>
			<?php 
			$i=1;
			while($row=$this->db->fetch_array($result))
			{
				?>
				<tr <?php if($i%2==0 && $type == 'onfly') { echo 'class="alt2"'; } ?> >
					<td>
					<?php
					 $sql_eq="select * from ".EM_EQUIPMENT." where equipment_id='".$row['equipment_id']."'";
					 $result_eq=$this->db->query($sql_eq,__FILE__,__LINE__);
					 $row_eq=$this->db->fetch_array($result_eq);
					 
					 echo $row_eq['equipment_name']; ?></td>
					<td>::</td>
					<td><?php echo $row['shipping_vendor']; ?></td>
					<td>::</td>
					<td><?php echo $row['tracking_number']; ?></td>
					<td>::</td>
					<td><?php echo $row['address1']." ".$row['city']." ".$row['state'].", ".$row['zip']; ?></td>
					<?php 
					if ($type == 'onfly')
					{
					?>
					<td><a href="javascript:void(0)" onclick="javascript: if(confirm('are you sure?')){ship.DeleteShipping(<?php echo $row['shipping_id']; ?>,'<?php echo $event_id; ?>',{onUpdate: function(response,root){
				ship.getAssignShipping('<?php echo $event_id; ?>','onfly',{target:'shipping_onfly',preloader: 'prl'})  ;
				
			 	},preloader:'prl'})} return false; "><img src="images/trash.gif" border="0" /></a></td>
					<?php 
					}
					?>
				</tr>
				<?php 
				$i++;
			}
			?>
			</table>
			<?php 
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
		
	function addShippingToEvent($runat,$event_id,$start_date='',$end_date='',$equipment_id='',$shipping_vendor='',$to='',$address1='',$address2='',$city='',$state='',$zip='',$phone='',$fax='',$tracking_number='',$target = '')
	{
		ob_start();
		switch($runat){
				
				case 'local':
				//create client side validation
						$FormName='frm_Add_Shipping_To_Event';
						$ControlNames=array("to"			=>array('to',"''","Please Enter Shipping To !! ","span_to"),
											"address1"			=>array('address1',"''","Please Enter Address !! ","span_address1"),
											"city"					=>array('city',"''","Please Enter City !! ","span_city"),
											"state"					=>array('state',"''","Please Enter State !! ","span_state"),
											"phone"					=>array('phone',"''","Please Enter Phone Number !! ","span_phone"),
											"tracking_number"					=>array('tracking_number',"''","Please Enter Tracking Number !! ","span_tracking_number"),

											);
											
						
						$this->ValidationFunctionName="AddShipping_CheckValidity";
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,
												$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						?>
				<div class="prl">&nbsp;</div>
				<div id="lightbox">
					<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle">Add Shipping</div>
					<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_credential').style.display='none';
ship.getAssignShipping('<?php echo $event_id; ?>',{target:'shipping',preloader: 'prl'}); "><img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;"  class="form_main">
					
						<form action=""  method="post" enctype="multipart/form-data" name="<?php echo $FormName; ?>">
						<?php 
						$sql="select * from ".EM_EVENT." where event_id='".$event_id."'";
		 				$result=$this->db->query($sql,__FILE__,__LINE__);
		 				$row=$this->db->fetch_array($result);
						?>
								
						   <table class="table" width="100%">
						  	<tr>
							  <td colspan="4">
							  <ul id="error_list">
							    <li><span id="span_to" class="normal"></span></li>
								<li ><span id="span_address1" class="normal"></span></li>
								<li ><span id="span_city" class="normal"></span></li>
								<li ><span id="span_state" class="normal"></span></li>
								<li ><span id="span_phone" class="normal"></span></li>
								<li ><span id="span_tracking_number" class="normal"></span></li>
							  </ul>
							  </td>
							</tr>
							
							<tr>
								<td><h2>Equipment</h2></td>
								<td><select name="equipment_id" id="equipment_id">
									  <option value="">--Select--</option>
									  <?php 
									  $sql_equip="select * from ".EM_EVENT_EQUIPMENT." where event_id='".$event_id."'";
									  $result_equip=$this->db->query($sql_equip,__FILE__,__LINE__);
									  while($row_equip=$this->db->fetch_array($result_equip))
									  {
										$sql_eq="select * from ".EM_EQUIPMENT." where equipment_id='".$row_equip['equipment_id']."'";
										$result_eq=$this->db->query($sql_eq,__FILE__,__LINE__);
										$row_eq=$this->db->fetch_array($result_eq);
										?>
										<option value="<?php echo $row_eq['equipment_id']; ?>"><?php echo $row_eq['equipment_name']; ?></option>
										<?php 
									  }								  
									  ?>
									  </select>
								 </td>
							</tr>
							<tr>
								 <?php 
								$sql_max_day = "select * from ".EM_MAX_DAY_SHIPPING;
								$result_max_day = $this->db->query($sql_max_day,__FILE__,__lINE__);			
								$row_max_day = $this->db->fetch_array($result_max_day);	
								$max_day = $row_max_day['max_day'];
								 
								 ?>
								 <td><h2>Reserve Days Before Event </h2></td>
								 <td> <select name="start_date" id="start_date">
									  <?php 
									  for($i=0; $i<=$max_day; $i++)
									  {
										?>
										<option value="<?php echo $i;?>"><?php echo $i;?></option>
										<?php 
									  }
									  ?>
									  </select>
								 </td>
							</tr>
							<tr>
								 <td><h2>Reserve Days After Event </h2></td>
								 <td>
									  <select name="end_date" id="end_date">
									  <?php 
									  for($i=0;$i<=$max_day;$i++)
									  {
										?>
										<option value="<?php echo $i;?>"><?php echo $i;?></option>
										<?php 
									  }
									  ?>
									  </select>
								 </td>
							</tr>
							<tr>
							  <td width="28%"><h2>Shipping Vendor</h2></td>
								  <td colspan="3" align="left">
								  <select name="shipping_vendor" id="shipping_vendor">
								  <option value="">-Select-</option>
								  <option>USPS</option>
								  <option>UPS</option>
								  <option>FedEx</option>
								  <option>Other</option>
								  </select>
								  </td>
								  </tr>
							
							<tr>
							  <td width="28%"><h2>To </h2></td>
								  <td colspan="3" align="left">
								  <input type="text" name="to" id="to" size="30" value="<?php echo $_POST['to'];?>" />
								  </td>
								  </tr>
								  <tr>
								  <td>
								 <a href="javascript:void(0)" onclick="javascript: {
								 document.getElementById('address1').value='<?php echo $row['street_name_1']; ?>';
								 document.getElementById('address2').value='<?php echo $row['street_name_2']; ?>';
								 document.getElementById('city').value='<?php echo $row['city']; ?>';
								 document.getElementById('state').value='<?php echo $row['state']; ?>';
								 document.getElementById('zip').value='<?php echo $row['zip']; ?>';
								 }
								 
								 return false;">Add Address</a>	
								  </td>
								  </tr>
								  <tr>
								  <td><h2>Address 1 </h2></td>
								  <td colspan="3" align="left"><input type="text" name="address1" id="address1" size="30" value="<?php echo $_POST['address1'];?>" /></td>
								  </tr>
								  <tr>
								  <td><h2>Address 2 </h2></td>
								  <td colspan="3" align="left"><input type="text" name="address2" id="address2"  value="<?php echo $_POST['address2'];?>" /></td>
								  </tr>
								  
								  <tr>
								  <td><h2>City </h2></td>
								  <td colspan="3" align="left"><input type="text" name="city" id="city" value="<?php echo $_POST['city'];?>" /></td>
								  </tr>
								  <tr>
								  <td><h2>State </h2></td>
								  <td width="26%"><select name="state" id="state" >
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
									</select></td>
								  <td width="8%"><h2>Zip </h2></td>
								  <td width="38%"><input type="text" name="zip" id="zip" size="30"  value="<?php echo $_POST['zip'];?>" /></td>
								  </tr>								  
								 
								  <tr>
								  <td><h2>Phone </h2></td>
								  <td><input type="text" name="phone" id="phone" size="30" value="<?php echo $_POST['phone'];?>" /></td>
								  <td><h2>Fax </h2></td>
								  <td><input type="text" name="fax" id="fax" size="30" value="<?php echo $_POST['fax'];?>" /></td>
								  </tr>
								  <tr>
								   <td><h2>Tracking Number</h2></td>
								   <td colspan="3"><input type="text" name="tracking_number" id="tracking_number" size="30" value="<?php echo $_POST['tracking_number'];?>" /></td>
								  </tr>
								 
								  <tr>
								  <td colspan="4" align="right">
								  
								  <input type="button" name="sub" id="sub" value="Ok" onclick="javascript: 
					 if(<?php echo $this->ValidationFunctionName; ?>()) {  ship.addShippingToEvent('server','<?php echo $event_id; ?> ',this.form.start_date.value,this.form.end_date.value,this.form.equipment_id.value,this.form.shipping_vendor.value,this.form.to.value,this.form.address1.value,this.form.address2.value,this.form.city.value,this.form.state.value,this.form.zip.value,this.form.phone.value,this.form.fax.value,this.form.tracking_number.value,{target:'div_credential', preloader: 'prl'}); } return false;" style="width:auto"/>
								  </td></tr>
								  </table>
								  
						<div class="profile_box1" id="div_ship" align="left">Shipping: </div>
						<div id="shipping_onfly"><?php echo $this->getAssignShipping($event_id,'onfly'); ?></div>		  
						  </form>
						  </div></div></div>
				<?php
				break;
				case 'server':
				
				$this->event_id=$event_id;
				$this->equipment_id=$equipment_id;
				$this->shipping_vendor=$shipping_vendor;
				$this->to=$to;
				$this->address1=$address1;
				$this->address2=$address2;
				$this->city=$city;
				$this->state=$state;
				$this->zip=$zip;			
				$this->phone=$phone;
				$this->fax=$fax;
				$this->tracking_number=$tracking_number;
				
					$return =true;
				if($this->Form->ValidField($to,'empty','Please Enter Shipping To')==false)
						$return =false;
					if($this->Form->ValidField($address1,'empty','Please Enter Address')==false)
						$return =false;
					if($this->Form->ValidField($city,'empty','Please Enter City')==false)
						$return =false;
					if($this->Form->ValidField($state,'empty','Please Enter State')==false)
						$return =false;	
					if($this->Form->ValidField($phone,'empty','Please Enter Phone Number')==false)
						$return =false;	
					if($this->Form->ValidField($tracking_number,'empty','Please Enter Tracking Number')==false)
						$return =false;	

					
					if($return){
						$insert_sql_array = array();
						$insert_sql_array[event_id] = $this->event_id;
						$insert_sql_array[equipment_id] = $this->equipment_id;
						$insert_sql_array[shipping_vendor] = $this->shipping_vendor;
						$insert_sql_array[to] = $this->to;
						$insert_sql_array[address1] = $this->address1;
						$insert_sql_array[address2] = $this->address2;
						$insert_sql_array[city] = $this->city;
						$insert_sql_array[state] = $this->state;
						$insert_sql_array[zip] = $this->zip;						
						$insert_sql_array[phone] = $this->phone;
						$insert_sql_array[fax] = $this->fax;
						$insert_sql_array[tracking_number] = $this->tracking_number;
						 						 
						$this->db->insert(EM_SHIPPING,$insert_sql_array);
						
						if($start_date && $end_date){
							$sql="delete  from ".EM_EQUIPMENT_AVAILABILITY." WHERE equipment_id='".$this->equipment_id."' and status= 'Shipping' ";					
							$this->db->query($sql,__FILE__,__LINE__);						
							$dtm = $this->equipment->dateArray($event_id,$start_date,$end_date);
							for($i=0; $i<count($dtm); $i+=1){
							
								$insert_sql_array = array();
								$insert_sql_array['unavailability_date'] = $dtm[$i];
								$insert_sql_array['start_time'] = $row['start_time'];
								$insert_sql_array['end_time'] = $row['end_time'];
								$insert_sql_array['note'] = 'Shipping';
								$insert_sql_array['equipment_id'] = $equipment_id;
								$insert_sql_array['event_id'] = $event_id;
								$insert_sql_array['status'] = 'Shipping';								
								$this->db->insert(EM_EQUIPMENT_AVAILABILITY,$insert_sql_array);										
							}							
						}
						?>
						<!--<script type="text/javascript">
						document.getElementById('div_credential').style.display='none';
						document.getElementById('div_credential').innerHTML='';
						ship.getAssignShipping('<?php echo $event_id; ?>',{target:'shipping',preloader: 'prl'})
						</script>-->
						<?php
				}
				else
				{
				echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
				$this->addShippingToEvent('local',$event_id);
				} 

				
				break;
			   default : echo 'Wrong Paramemter passed';	
				
		}// end of switch		
				
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}// end 
	

	function DeleteShipping($shipping_id,$event_id='')
	{
		ob_start();
		$sql="delete from ".EM_SHIPPING." where shipping_id='".$shipping_id."'";
		$result=$this->db->query($sql,__FILE__,__LINE__);

		$html = ob_get_contents();
		ob_end_clean();
		return $html;
		
	}
	
	function addMaxDay($runat) {
	
		switch($runat) {
			
			case 'local':
				$sql_max_day = "select * from ".EM_MAX_DAY_SHIPPING;
				$result_max_day = $this->db->query($sql_max_day,__FILE__,__lINE__);			
				$row_max_day = $this->db->fetch_array($result_max_day);	
				?>
				<form action="" method="post">
				<table class="table">
				<tr>
				<td>Default Equipment Unavailability and Max Shipping days </td>
				<td>
				<select name="max_day" id="max_day">
					<?php for($i=1;$i<=10;$i+=1) { ?>
					<option value="<?php echo $i; ?>" <?php if($i== $row_max_day['max_day']) echo 'selected';?>><?php echo $i; ?></option>
					<?php } ?>
				</select>
				</td>
				<td><input type="submit" name="submit" id="submit" value="Go"/></td>
				</tr>
				</table>				
				</form>
				<?php
				break;
				
			case 'server':
							
				$sql="select * from ".EM_MAX_DAY_SHIPPING;
				$result=$this->db->query($sql,__FILE__,__LINE__);
				
				if($this->db->num_rows($result) > 0){
					
					$row = $this->db->fetch_array($result);
					$update_sql_array = array();					
					$update_sql_array['max_day'] = $_POST['max_day'];
					$this->db->update(EM_MAX_DAY_SHIPPING,$update_sql_array,'max_day_id',$row['max_day_id']);					
					}
				else {
					$insert_sql_array = array();
					$insert_sql_array[max_day] = $_POST['max_day'];
					$this->db->insert(EM_MAX_DAY_SHIPPING,$insert_sql_array);
					}
				?>
				<script type="text/javascript">
				window.location = '<?php echo $_SERVER['PHP_SELF'] ?>';
				</script>
				<?php		
				break;
			}
		}
}
?>