<?php
    class Services
	{
	var $service_id;
	var $event_id;
	var $service_type;
	var $quantity_requested;
	var $quantity_performed;
	var $service_code;
	var $db;
	var $validity;
	var $Form;
	
	function __construct(){
	    $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
		}
	
	
	function ShowEventServices($event_id)
	{
		ob_start();
		$this->event_id=$event_id;
		$sql = "select * from ".EM_SERVICES." where event_id='".$this->event_id."' order by service_id";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		?><p class="gray">Services:<br /><?php
		while($row=$this->db->fetch_array($result)) {
		$sql_type = "select * from ".EM_SERVICES_TYPE." where services_id='".$row['service_type']."'";
		$result_type = $this->db->query($sql_type,__FILE__,__lINE__);
		$row_type=$this->db->fetch_array($result_type);
		echo $row['quantity_requested']."' '".$row_type['services_type']."' '".$row['service_code']; ?>::
		<a href="#" onclick="javascript: service.editEventService('local','<?php echo $this->event_id;?>','<?php echo $row['service_id'];?>',
					{ onUpdate: function(response,root){
							 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';				
							
							 }, preloader: 'prl'
						} ); return false;" >Edit</a>
		<a href="#" onclick="javascript: service.deleteEventService('<?php echo $row['service_id'];?>','<?php echo $this->event_id;?>',
					{ onUpdate: function(response,root){
							 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';				
							
							 }, preloader: 'prl'
						} ); return false;" ><img src="images/trash.gif" border="0" /></a>						
		
		<br/>
		<?php
		}
		?><a href="#" onclick="javascript: service.editEventService('local','<?php echo $this->event_id;?>',
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
	
	function editEventService($runat,$event_id,$service_id='',$quantity_requested='',$quantity_performed='',$service_type='',$service_code='')
	{
		ob_start();			
		$this->event_id=$event_id;
		$this->service_id=$service_id;
		switch($runat) {
			case 'local':
			
			
			$FormName='frm_Add_Events_service';
			
			$ControlNames=array("service_type"	=>array('service_type',"''","Please Enter Service Type !! ","span_service_type"),
								"quantity_requested" =>array('quantity_requested',"Number","Please Enter Quantity Requested !! ","span_quantity_requested"),
							
								"service_code" =>array('service_code',"''","Please Enter Service Code !! ","span_service_code")
									);
											
						
						$this->ValidationFunctionName="AddService_CheckValidity";
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,
												$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
			
			$sql="select * from ".EM_SERVICES." where service_id='".$this->service_id."'";
			$result=$this->db->query($sql,__FILE__,__LINE__);
			$row=$this->db->fetch_array($result);
			

			?>
			<div class="prl">&nbsp;</div>
						<div id="lightbox">
							<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
							<div id="TB_ajaxWindowTitle">Event Service</div>
							<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_credential').style.display='none';"><img border="0" src="images/close.gif" alt="close" /></a></div>
						</div>
						<div  class="white_content"> 
						<div style="padding:20px;" class="form_main">		
							  <ul id="error_list">
							    <li><span id="span_service_type" class="normal"></span></li>
								<li ><span id="span_quantity_requested" class="normal"></span></li>
								<li ><span id="span_service_code" class="normal"></span></li>
							  </ul>		
						<form action=""  method="post" enctype="multipart/form-data" name="<?php echo $FormName; ?>">
				<table class="table" width="100%">
				<tr>
					<td><h2>Service Type</h2></td>
					<td><h2>Quantity Requested</h2></td>
					<td><h2>Quantity Performed</h2></td>
					<td><h2>Service Code</h2></td>
				</tr>
				<tr>
					<td>
					<select name="service_type" id="service_type">
					<?php 
					$sql_status="select * from ".EM_SERVICES_TYPE;
					$result_status=$this->db->query($sql_status,__FILE__,__LINE__);
					while($row_status=$this->db->fetch_array($result_status))
					{
					?>	
						<option value="<?php echo $row_status['services_id'] ?>" <?php if($row_status['services_id']==$row['service_type']){echo 'selected="selected"';}?>><?php echo $row_status['services_type'] ?></option>
					<?php 
					}
					
					?>
					</select>					</td>
					<td><input type="text" name="quantity_requested" id="quantity_requested" size="30" value="<?php echo $row['quantity_requested'];?>" /></td>
					<td><input type="text" name="quantity_performed" id="quantity_performed" size="30" value="<?php echo $row['quantity_performed'];?>" /></td>
					<td><input type="text" name="service_code" id="service_code" size="30" value="<?php echo $row['service_code'];?>" /></td>
				</tr>
				<tr>
					<td colspan="4" align="right" ><input type="button" name="btn" value="Ok" id="btn" onClick="javascript: if(<?php echo $this->ValidationFunctionName?>()) { service.editEventService('server','<?php echo $this->event_id ?>','<?php echo $this->service_id ?>',this.form.quantity_requested.value,this.form.quantity_performed.value,this.form.service_type.value,this.form.service_code.value,{target:'div_credential', preloader: 'prl'}); } return false;"  style="width:auto" />				 </td>
				</tr>	
				</table>
				</form>		
						</div></div></div>
			<?php 
			break;
			case 'server':

				$this->event_id=$event_id;
				$this->service_id=$service_id;
				$this->quantity_requested=$quantity_requested;
				$this->quantity_performed=$quantity_performed;
				$this->service_type=$service_type;
				$this->service_code=$service_code;
				
					$return =true;
					if($this->Form->ValidField($quantity_requested,'empty','Please Enter Quantity Requested')==false)
						$return =false;
					if($this->Form->ValidField($service_type,'empty','Please Enter Service Type')==false)
						$return =false;
					
		
					
					if($return){
					
						 if($this->service_id)
						 {
						 	$update_sql_array = array();
						 	$update_sql_array[quantity_requested] = $this->quantity_requested;
						 	$update_sql_array[quantity_performed] = $this->quantity_performed;
						 	$update_sql_array[service_type] = $this->service_type;
						 	$update_sql_array[service_code] = $this->service_code;
									 
						 	$this->db->update(EM_SERVICES,$update_sql_array,'service_id',$this->service_id);
						 }
						 else
						 {
						 	
						 	$insert_sql_array = array();
							$insert_sql_array[event_id] = $this->event_id;
						 	$insert_sql_array[quantity_requested] = $this->quantity_requested;
						 	$insert_sql_array[quantity_performed] = $this->quantity_performed;
						 	$insert_sql_array[service_type] = $this->service_type;
						 	$insert_sql_array[service_code] = $this->service_code;
							
							$this->db->insert(EM_SERVICES,$insert_sql_array);		 
						 	
						 
						 }
						 
						 ?>
						<script type="text/javascript">
						document.getElementById('div_credential').style.display='none'; 
				 		service.ShowEventServices('<?php echo $this->event_id ?>',{target:'event_services',preloader: 'prl'})
						</script>
						<?php
						}
			 break;
			default : echo 'Wrong Paramemter passed';	
		} // end of switch	
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html; 
		
	}  // end of editEventService
	
	function deleteEventService($service_id,$event_id)
	{
		ob_start();
		
		$sql="delete from ".EM_SERVICES." where service_id='".$service_id."'";
		$this->db->query($sql,__FILE__,__lINE__);
		?>
		<script type="text/javascript">
		document.getElementById('div_credential').style.display='none'; 
		service.ShowEventServices('<?php echo $event_id ?>',{target:'event_services',preloader: 'prl'})
		</script>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html; 
		
		
	}
	
	
	function CreateServiceStatus($runat)
	{
	

		switch($runat)
		{
			
			case 'local' :
				
				$formName = 'frm_manage_Service';
				
				$ControlNames=array("services_type"		=>array('services_type',"''","Please Enter Event Status !!  ","span_service"),
											);
											
						
						$ValidationFunctionName="frmgvhjdggCheckValidity";
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
				?>
					<script language="javascript" type="text/javascript">
					function validaterecruitingservice() {						
						 if(document.getElementById("services_type").value=='')
						 {
							document.getElementById('span_service').innerHTML="Please enter service type !!";
							return false;
						 }						 		
					}				
					function validateFeildRecruitingservice() {						
						//alert(document.getElementById("cert_type_select_credential").selectedIndex);
						if(document.getElementById("type_select_service").value==document.getElementById("type_select_replace_service").value) {
							alert("Plz.. select different different service type");
							return false;
						}
						else {					
						var location="manage_masterfields.php?id="+document.getElementById("type_select_service").value + "&replcedwith="+document.getElementById("type_select_replace_service").value+ "&action=delete_service_type";						
							window.location=location;
							return false;
						
						}
					
					
					}
				
					</script>
					
					<ul id="error_list">
					<li><span id="span_service" class="required" ></span></li>
					</ul>
				  
				 
					 <form method="post" action="" enctype="multipart/form-data" name="<?php echo $formName?>" >
					 <table width="100%" class="table">
					 <tr>
					 	<td width="21%"><h2>Add Service Type:</h2></td>
				      	<td width="32%" ><input type="text" name="services_type" id="services_type" /></td>
					    <td width="6%" ><input type="submit" name="servicestypebtn" id="servicestypebtn"  style="width:auto" value="go" 
											onClick="return validaterecruitingservice();" /></td>
					    <td width="5%">&nbsp;</td>
				   		<td width="13%" ><select name="type_select_service" id="type_select_service">
                          <?php
				      $sql = "select * from ".EM_SERVICES_TYPE;
				      $result = $this->db->query($sql,__FILE__,__LINE__);
				      while($row = $this->db->fetch_array($result)){
				      	?>
                          <option value="<?php echo $row[services_id] ?>" ><?php echo $row[services_type] ?></option>
                          <?php
				      }
				      ?>
                        </select></td>
				        <td width="13%" >
						  <select name="type_select_replace_service" id="type_select_replace_service">
						  <?php
						  $sql = "select * from ".EM_SERVICES_TYPE;
						  $result = $this->db->query($sql,__FILE__,__LINE__);
						  while($row = $this->db->fetch_array($result)){
							?>
							<option value="<?php echo $row[services_id] ?>" ><?php echo $row[services_type] ?></option>
							<?php
						  }
						  ?>
						  </select>				      
						  </td>
				      <td width="10%"><a href="#" onclick= "javascript: if(confirm('Are you sure?')) return validateFeildRecruitingservice();">
					  				  <img src="images/trash.gif" border="0" /></a>
					   </td>
					  </tr>
					  </table>
					  </form>
			
				<?php
				break;
				
			case 'server' :
					extract($_POST);
					$this->services_type=$services_type;
						$insert_sql_array = array();
						$insert_sql_array[services_type] = $this->services_type;
						$this->db->insert(EM_SERVICES_TYPE,$insert_sql_array);
		
					
					$_SESSION[msg]="Service Type Created Successfully!!";
			?>
				<script type="text/javascript">
				 window.location = '<?php echo $_SERVER[PHP_SELF] ?>';
				</script>
			<?php
			break;
			
			
			default : echo 'Wrong Paramemter passed';

		
		}// end of switch
		
	} // end of CreateServiceStatus
	
	
	function Delete_Service_type($type_select, $type_select_replace){

			$sql = "delete from ".EM_SERVICES_TYPE." where services_id = '".$type_select."'";
			//echo $sql;
			$this->db->query($sql,__FILE__,__LINE__);

			if($type_select_replace!='')
			{
				$sql_contact = "update ".EM_SERVICES." set service_type = '".$type_select_replace."' where service_type = '".$type_select."'";
				//echo $sql_contact;
				$this->db->query($sql_contact,__FILE__,__LINE__);
			}
			
		    $_SESSION[msg]="Service Type Replaced Successfully!!";

				?>
				<script type="text/javascript">
				 window.location ='<?php echo $_SERVER[PHP_SELF] ?>';
				</script>
				<?php 
			}
			
				
}

?>				
