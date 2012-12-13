<?php

/***********************************************************************************

			Class Discription : 
			
			Class Memeber Functions :
			
			
			Describe Function of Each Memeber function: 
			
									
									 

************************************************************************************/
class Event_Hotel
{
	var $contact_id;
	var $event_status;
	var $name;
	var $phone;
	var $address1;
	var $address2;
	var $city;
	var $state;
	var $zip;
	var $fax;
	var $geocode;
	var $distance;
	var $db;
	var $validity;
	var $Form;
	var $range_in_mile;
	var $zip_obj;

	
		function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
		$this->zip_obj = new zipcode_class();
	}
	
	function addHotel($runat,$target,$name='',$address1='',$address2='',$city='',$state='',$zip='',$phone='',$fax='',$event_id,$price='')
	{
		ob_start();
		switch($runat){
				
				case 'local':
				//create client side validation
						$FormName='frm_Add_Hotel';
						$ControlNames=array("name"			=>array('name',"''","Please Enter Hotel Name !! ","span_hotel_name"),
											"address1"			=>array('address1',"''","Please Enter Address !! ","span_address1"),
											"city"					=>array('city',"''","Please Enter City !! ","span_city"),
											"state"					=>array('state',"''","Please Enter State !! ","span_state"),
											"phone"					=>array('phone',"''","Please Enter Phone Number !! ","span_phone")

											);
											
						
						$this->ValidationFunctionName="AddHotel_CheckValidity";
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,
												$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						?>
				<div class="prl">&nbsp;</div>
				<div id="lightbox">
				 
					<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle">Add Hotel</div>
					<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_credential').style.display='none';"><img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content">
				<div style="padding:20px;"  class="form_main">
				
						<form action=""  method="post" enctype="multipart/form-data" name="<?php echo $FormName; ?>">
												  
						  <table class="table" width="100%">
						  	<tr>
							  <td colspan="4">
							  <ul id="error_list">
							    <li><span id="span_hotel_name" class="normal"></span></li>
								<li ><span id="span_address1" class="normal"></span></li>
								<li ><span id="span_city" class="normal"></span></li>
								<li ><span id="span_state" class="normal"></span></li>
								<li ><span id="span_phone" class="normal"></span></li>
							  </ul>
							  </td>
							</tr><tr>
							  <td width="28%"><h2>Hotel Name </h2></td>
								  <td colspan="3" align="left"><input type="text" name="name" id="name" size="30" value="<?php echo $_POST['name'];?>" /></td>
								  </tr>
								  <tr>
								  <td><h2>Street Address 1 </h2></td>
								  <td colspan="3" align="left"><input type="text" name="address1" id="address1" size="30" value="<?php echo $_POST['address1'];?>" /></td>
								  </tr>
								  <tr>
								  <td><h2>Street Address 2 </h2></td>
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
								   <td><h2>Price</h2></td>
								   <td colspan="3"><input type="text" name="price" id="price" size="30" value="<?php echo $_POST['price'];?>" /></td>
								  </tr>
								 
								  <tr>
								  <td colspan="4" align="right">
								  
								  <input type="button" name="submit" id="submit" value="Ok" onclick="javascript: 
					 if(<?php echo $this->ValidationFunctionName; ?>()) {
					  hotel.addHotel('server','<?php echo $target; ?>',this.form.name.value,this.form.address1.value,this.form.address2.value,this.form.city.value,this.form.state.value,this.form.zip.value,this.form.phone.value,this.form.fax.value,'',this.form.price.value,{target:'div_credential', preloader: 'prl'}); } return false;" style="width:auto"/>
								  </td></tr>
								  </table>
								  	
						  
						  </form>
						  </div></div></div>
						 
						   <?php
				
				
				break;
				
				case 'server':
				
				
				$this->name=$name;
				$this->address1=$address1;
				$this->address2=$address2;
				$this->city=$city;
				$this->state=$state;
				$this->zip=$zip;			
				$this->phone=$phone;
				$this->fax=$fax;
				$this->price=$price;
				
					$return =true;
					if($this->Form->ValidField($name,'empty','Please Enter Hotel Name')==false)
						$return =false;
					if($this->Form->ValidField($address1,'empty','Please Enter Address')==false)
						$return =false;
					if($this->Form->ValidField($city,'empty','Please Enter City')==false)
						$return =false;
					if($this->Form->ValidField($state,'empty','Please Enter State')==false)
						$return =false;	
					if($this->Form->ValidField($phone,'empty','Please Enter Phone Number')==false)
						$return =false;	

					
					if($return){
						 $insert_sql_array = array();
						 $insert_sql_array[name] = $this->name;
						 $insert_sql_array[address1] = $this->address1;
						 $insert_sql_array[address2] = $this->address2;
						 $insert_sql_array[city] = $this->city;
						 $insert_sql_array[state] = $this->state;
						 $insert_sql_array[zip] = $this->zip;						
						 $insert_sql_array[phone] = $this->phone;
						 $insert_sql_array[fax] = $this->fax;
						 $insert_sql_array[price] = $this->price;
						 
						 
						 $this->db->insert(EM_HOTEL,$insert_sql_array);
						 
						 $hotel_id = $this->db->last_insert_id();
						
						// to add hotel to event //
						$this->event_id=$event_id; 
						if($event_id!='')
						{
							$this->assignHotel($this->event_id,$hotel_id);
							?>
							<script type="text/javascript">
					document.getElementById('hotelbasicadd').innerHTML='';		
					hotel.basicAddHotel('<?php  echo $this->event_id ?>',{target:'hotelbasicadd',preloader: 'prl'});
					hotel.GetHotelsInEvents('<?php echo $this->event_id ?>','table',{target:'get_hotel',preloader: 'prl'});
					//hotel.searchHotel('','','','',0,'<?php //echo $this->event_id ?>',{target:'showhotel',preloader: 'prl'});	
							</script>
							<?php
						}				 
						
						
						else
						{
						?>
						<script type="text/javascript">
				  		document.getElementById('div_credential').style.display='none'; 
				 		hotel.getHotels('',{onUpdate: function(response,root){
document.getElementById('showhotel').innerHTML=response;
$('#search_table')
.tablesorter({widthFixed: true, widgets: ["zebra"], sortList: [[5,0]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}}) }})
						</script>
						<?php
						}
							
					
				}
				else
				{
				echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
				$this->addHotel('local',$target);
				} 

				
				break;
			   default : echo 'Wrong Paramemter passed';	
				
		}// end of switch		
				
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}// end of add hotel
	
	
	function editHotel($runat,$hotel_id='',$target='',$name='',$address1='',$address2='',$city='',$state='',$zip='',$phone='',$fax='',$price='')
	{
		ob_start();
		$this->$hotel_id=$hotel_id;
		switch($runat){
				
				case 'local':
									$sql="Select * from ".EM_HOTEL." where hotel_id='".$this->$hotel_id."'";
									$result=$this->db->query($sql,__FILE__,__LINE__);
									$row=$this->db->fetch_array($result);
									
				//create client side validation
						$FormName='frm_Edit_Hotel';
						$ControlNames=array("name"			=>array('name',"''","Please Enter Hotel Name !! ","span_hotel_name"),
											"address1"			=>array('address1',"''","Please Enter Address !! ","span_address1"),
											"city"					=>array('city',"''","Please Enter City !! ","span_city"),
											"state"					=>array('state',"''","Please Enter State !! ","span_state"),
											"phone"					=>array('phone',"''","Please Enter Phone Number !! ","span_phone")

											);
											
						
						$this->ValidationFunctionName="CheckValidity";
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,
												$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						?>
						
						<div class="prl">&nbsp;</div>
				<div id="lightbox">
				
					<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle">Edit Hotel</div>
					<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_credential').style.display='none';"><img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;">
				
						<form action=""  method="post" enctype="multipart/form-data" name="<?php echo $FormName; ?>">
												  
						  <table class="table" width="100%">
						  	<tr>
							  <td colspan="4">
							  <ul id="error_list">
							    <li><span id="span_hotel_name"></span></li>
								<li ><span id="span_address1"></span></li>
								<li ><span id="span_city"></span></li>
								<li ><span id="span_state"></span></li>
								<li ><span id="span_phone"></span></li>
							  </ul>
							  </td>
							</tr><tr>
								  <td><h2>Hotel Name </h2></td>
								  <td colspan="3"><input type="text" name="name" id="name" size="30" value="<?php echo $row['name'];?>" /></td>
								  </tr>
								  <tr>
								  <td><h2>Street Address 1 </h2></td>
								  <td colspan="3"><input type="text" name="address1" id="address1" size="30" value="<?php echo $row['address1'];?>" /></td>
								  </tr>
								  <tr>
								  <td><h2>Street Address 2 </h2></td>
								  <td colspan="3"><input type="text" name="address2" id="address2" value="<?php echo $row['address2'];?>" /></td>
								  </tr>
								  
								  <tr>
								  <td><h2>City </h2></td>
								  <td colspan="3"><input type="text" name="city" id="city" value="<?php echo $row['city'];?>" /></td>
								  </tr>
								  <tr>
								  <td><h2>State </h2></td>
								  <td>
								  <select name="state" id="state" style="width:100%">
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
								  <td><h2>Zip </h2></td>
								  <td><input type="text" name="zip" id="zip" size="30" value="<?php echo $row['zip'];?>" /></td>
								  </tr>
								  
								  							  
								  <tr>
								  <td><h2>Phone </h2></td>
								  <td><input type="text" name="phone" id="phone" size="30" value="<?php echo $row['phone'];?>" /></td>
								  <td><h2>Fax </h2></td>
								  <td><input type="text" name="fax" id="fax" size="30" value="<?php echo $row['fax'];?>" /></td>
								  </tr>
								  <tr>
								   <td><h2>Price</h2></td>
								   <td colspan="3"><input type="text" name="price" id="price" size="30" value="<?php echo $row['price'];?>" /></td>
								  </tr>
								 
							
								  <tr>
								  <td colspan="4" align="right">
								  <input type="button" name="submit" id="submit" value="Update" onclick="javascript: 
					 if(<?php echo $this->ValidationFunctionName; ?>()) {
					  hotel.editHotel('server',<?php echo $this->$hotel_id;?>,'<?php echo $target; ?>',this.form.name.value,this.form.address1.value,this.form.address2.value,this.form.city.value,this.form.state.value,this.form.zip.value,this.form.phone.value,this.form.fax.value,this.form.price.value,{target:'div_credential', preloader: 'prl'}); } return false;" style="width:auto"/>
								  </td></tr>
								  </table>
								  	
						  
						  </form>
						   <?php
				
				
				break;
				
				case 'server':
				
				$this->hotel_id=$hotel_id;
				
				
				$this->name=$name;
				$this->address1=$address1;
				$this->address2=$address2;
				$this->city=$city;
				$this->state=$state;
				$this->zip=$zip;
				$this->phone=$phone;
				$this->fax=$fax;
				$this->price=$price;
				
					$return =true;
					if($this->Form->ValidField($name,'empty','Please Enter Hotel Name')==false)
						$return =false;
					if($this->Form->ValidField($address1,'empty','Please Enter Address')==false)
						$return =false;
					if($this->Form->ValidField($city,'empty','Please Enter City')==false)
						$return =false;
					if($this->Form->ValidField($state,'empty','Please Enter State')==false)
						$return =false;	
					if($this->Form->ValidField($phone,'empty','Please Enter Phone Number')==false)
						$return =false;	

					
					if($return){
						 $update_sql_array = array();
						 $update_sql_array[name] = $this->name;
						 $update_sql_array[address1] = $this->address1;
						 $update_sql_array[address2] = $this->address2;
						 $update_sql_array[city] = $this->city;
						 $update_sql_array[state] = $this->state;
						 $update_sql_array[zip] = $this->zip;
						 $update_sql_array[phone] = $this->phone;
						 $update_sql_array[fax] = $this->fax;					 
						 $update_sql_array[price] = $this->price;
						 $this->db->update(EM_HOTEL,$update_sql_array,'hotel_id',$this->hotel_id);
						 
						$tar="hotellist_".$this->hotel_id;				 
						 //$_SESSION['msg']='Hotel Updated Successfully';
						?>
						<script type="text/javascript">
						document.getElementById('div_credential').style.display='none'; 
				 		hotel.refreshlist('<?php echo $this->hotel_id ?>',{target:'<?php echo $tar ?>',preloader: 'prl'})
						</script>
						<?php
						
							
					
				}
				else
				{
				echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
				$this->editHotel('local');
				} 

				
				break;
			   default : echo 'Wrong Paramemter passed';	
				
		}// end of switch	
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}// end of edit hotel
		
		
		function refreshlist($hotel_id)
		{
		  ob_start();
			$this->$hotel_id=$hotel_id;
			$sql="Select * from ".EM_HOTEL." where hotel_id='".$this->$hotel_id."'";
			$result=$this->db->query($sql,__FILE__,__LINE__);
			$row=$this->db->fetch_array($result);
			?>
				
						<td></td>
						<td><?php echo $row['name'];?></td>
						<td><?php echo $row['phone'];?></td>
						<td><?php echo $row['city'];?></td>
						<td><?php echo $row['state'];?></td>
						<td><?php $sql_avg_cost="SELECT *,AVG(booking_cost) FROM ".EM_HOTEL_STAY." where hotel_id='".$this->$hotel_id."' GROUP BY event_id";
							  $result_avg_cost=$this->db->query($sql_avg_cost,__FILE__,__LINE__);
							  $row_avg_cost=$this->db->fetch_array($result_avg_cost);
							  echo $row_avg_cost['booking_cost'];?>
							  </td>
						<td>
						<a href="javascript:void(0)" onclick="javascript: hotel.editHotel('local',<?php echo $row['hotel_id']; ?>,'div_credential',
			{ onUpdate: function(response,root){ 	
		   					 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';
							 
							 }, preloader: 'prl'
						} ); return false;">Edit</a> <a href="javascript:void(0)" onclick="javascript: if(confirm('Are you sure?')){  hotel.deleteHotel(<?php echo $row['hotel_id']; ?>, {preloader: 'prl'
						} );} return false;"><img src="images/trash.gif" /></a></td>
			<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;			
		} // end of refresh function
		
		
		
		function getHotels($searchval='',$event_id='', $seatchtype='',$city='',$state='',$zip='',$rad=0)
		{
		
			ob_start();
				
				$this->searchval=$searchval;
				$FormName="showhotellist";
			
				if($this->searchval=='')
				{
					$sql="Select * from ".EM_HOTEL." where 1 ";
				}
				else
				{
					$sql="Select * from ".EM_HOTEL." where name like '$this->searchval%' ";
						
				}
				
				if($city!=''){
					$sql .= " and city like '$city%'";
				}
				if($state!=''){
					$sql .= " and state like '$state%'";
				}
				if($zip!='' and $rad==0){
					$sql .= " and zip like '$zip%'";
				}
				//echo $sql;
				$result=$this->db->query($sql,__FILE__,__LINE__);
			
				/************************************************************************/
				$contact = array();
				$contactInRange = array();
				$x=0;
				while($row=$this->db->fetch_array($result)){
					foreach($row as $key=>$value){
						$contact[$x][$key] = $value;
					}
					$x++;
				}
				/*************************************************************************/
				if($zip!='' and $rad!=0){
					$x=0;
					$contact_list = array();
					$row = $this->zip_obj->get_zip_point($zip);
					if($row[lat]){
						$sql = "SELECT e.*,c.lat,c.lon,c.zip_code , (((acos( sin( ( ".$row[lat]." * pi( ) /180 ) ) * sin( (c.lat * pi( )/180 ) ) + cos( ( ".$row[lat]." * pi( ) /180 ) ) * cos( (c.lat * pi( ) /180 )) * cos( ((".$row[lon]." - c.lon) * pi( ) /180 )))) *180 / pi( )) *60 * 1.1515) AS distance FROM ".ZIP_CODE." c,".EM_HOTEL." e WHERE e.zip = c.zip_code";
						if($rad>0){
							$sql .= " HAVING distance<=$rad";
						} 
						
						$sql .= " ORDER BY distance ASC";
			
						$result = $this->db->query($sql,__FILE__,__LINE__);	
						
						while($row_c = $this->db->fetch_array($result)){
						
							foreach($row_c as $key=>$value){
								$contact_list[$x][$key] = $value;
							}
							$x++;
						}
						
					}
					
					$x=0;
					foreach($contact as $key=>$value){				
						foreach($contact_list as $key_zip=>$value_zip){
							if($value_zip[zip]==$value[zip]){
								$contactInRange[$x] = $value;
								$contactInRange[$x]['distance'] = $value_zip['distance'];
								$x++;
								break;
							}
						}
					}
				} else {
					$contactInRange = $contact;
				}
			?>	
				<form action=""  method="post" enctype="multipart/form-data" name="<?php echo $FormName; ?>">
				<table id="search_table" class="event_form small_text" width="100%">
				<thead>
				<tr>
				<th></th>
				<th>Hotel Name</th>
				<th>Phone</th>
				<th>City</th>
				<th>St</th>
				<th>Avg-$</th>
				<th>Action</th>
				</tr>
				</thead>
				<tbody>
			<?php	
			$x=0;
			
				foreach($contactInRange as $key=>$value)
				{
					?>	
						
						<tr id="hotellist_<?php echo $value['hotel_id']; ?>">
							<td></td>
							<td><?php echo $value['name'];?></td>
							<td><?php echo $value['phone'];?></td>
							<td><?php echo $value['city'];?></td>
							<td><?php echo $value['state'];?></td>
							<td><?php $sql_avg_price="SELECT *,AVG(booking_cost) FROM ".EM_HOTEL_STAY." where hotel_id='".$value['hotel_id']."' GROUP BY event_id";
							  $result_avg_price=$this->db->query($sql_avg_price,__FILE__,__LINE__);
							  $row_avg_price=$this->db->fetch_array($result_avg_price);
							  echo $row_avg_price['booking_cost'];?></td>
							<td><a href="javascript:void(0)" onclick="javascript: hotel.editHotel('local',<?php echo $value['hotel_id']; ?>,'div_credential',
			{ onUpdate: function(response,root){ 	
		   					 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';
							 
							 }, preloader: 'prl'
						} ); return false;">Edit</a> <a href="javascript:void(0)" onclick="javascript: if(confirm('Are you sure?')){  hotel.deleteHotel(<?php echo $value['hotel_id']; ?>, {preloader: 'prl'
						} );} return false;"><img src="images/trash.gif" /></a></td>
						</tr>
					<?php
					$x++;
				}
				if($x==0)
				{
				?>
				<tr id="hotellist_<?php echo $value['hotel_id'];?>" onClick="javascript:click(<?php echo $x;?>)" style="line-height:50px;" >
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>no result </td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
				</tr>
				<?php 
				}
				?>
				</tbody>
				</table>
				<div class="verysmall_text form_bg">*Hold 'Shift Key' to sort multiple field</div>
				</form>
				<?php 
			$html = ob_get_contents();
			ob_end_clean();
			return $html;	
		} // end of get hotel
		
	function deleteHotel($hotel_id)
	{
		ob_start();
		$this->hotel_id=$hotel_id;
		
		$sql = "select * from ".EM_HOTEL_STAY." where hotel_id='".$this->hotel_id."'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($result)>0){
		?>
		<script language="javascript" type="text/javascript">
		alert('<?php echo "Can not DELETE Hotel. Hotel Assigned to an event first remove Hotel from Event"; ?>');
		</script>
		<?php 
		}
		else 
		{
			$sql_del = "delete from ".EM_HOTEL." where hotel_id='".$this->hotel_id."'";
			$this->db->query($sql_del,__FILE__,__LINE__);
			?>
			<script language="javascript" type="text/javascript">
			alert('<?php echo "Hotel Deleted Successfully"; ?>');
			hotel.getHotels({onUpdate: function(response,root){
document.getElementById('showhotel').innerHTML=response;
$('#search_table')
.tablesorter({widthFixed: true, widgets: ["zebra"], sortList: [[5,0]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}}) }});
			

			</script>
			<?php
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	// end of delete Hotel
				
		
	function HoetlSearchBox($obj='hotel',$functn='getHotels',$event_id='',$searchtype='hotel')
	{
		$this->event_id=$event_id;
		$this->searchtype=$searchtype;
	?>
<div> <form onsubmit="return false;">
<table width="100%" class="table">
  <tr>
    <td><h2>Name:</h2></td>
    <td><h2>City:</h2></td>
    <td><h2>State:</h2></td>
    <td><h2>Zip:</h2></td>
    <td><h2>Radius:</h2></td>
  </tr>
  <tr>
    <td><input name="name" type="text" id="name" size="60" 
	onkeyup="<?php echo $obj; ?>.<?php echo $functn; ?>(this.value,
	'<?php echo $this->event_id; ?>',
	'<?php echo $this->searchtype;?>',
	this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,
	{onUpdate: function(response,root){
					document.getElementById('showhotel').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
			 	}});" autocomplete='off' /></td>
    <td><input name="ct" type="text" id="ct" size="60" onkeyup="<?php echo $obj; ?>.<?php echo $functn; ?>(this.form.name.value,'<?php echo $this->event_id; ?>','<?php echo $this->searchtype;?>',this.value,this.form.st.value,this.form.zip.value,this.form.rad.value, {onUpdate: function(response,root){
					document.getElementById('showhotel').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
			 	}});" autocomplete='off' /></td>
    <td><select name="st" id="st" onchange="<?php echo $obj; ?>.<?php echo $functn; ?>(this.form.name.value,'<?php echo $this->event_id; ?>','<?php echo $this->searchtype;?>', this.form.ct.value,this.value,this.form.zip.value,this.form.rad.value,{onUpdate: function(response,root){
					document.getElementById('showhotel').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
			 	}});" >
		<option value="">Select State</option>
		<?php
			$state=file("../state_us.inc");
			foreach($state as $val){
			$state = trim($val);
		?>
		<option  value="<?php echo $state;?>"><?php echo $state;?></option>
		<?php
			}
		?>
		</select></td>
    <td><input name="zip" type="text" id="zip" size="60" onkeyup="<?php echo $obj; ?>.<?php echo $functn; ?>(this.form.name.value,'<?php echo $this->event_id; ?>','<?php echo $this->searchtype;?>', this.form.ct.value,this.form.st.value,this.value,this.form.rad.value,{onUpdate: function(response,root){
					document.getElementById('showhotel').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
			 	}});" autocomplete='off' /></td>
    <td><input name="rad" type="text" id="rad" size="60" onkeyup="<?php echo $obj; ?>.<?php echo $functn; ?>(this.form.name.value,'<?php echo $this->event_id; ?>','<?php echo $this->searchtype;?>',this.form.ct.value,this.form.st.value,this.form.zip.value,this.value, {onUpdate: function(response,root){
					document.getElementById('showhotel').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
			 	}});" autocomplete='off' /></td>
  </tr>
</table>

 <div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
</form>
  <!--or  <a href="#">More Options</a> --></div>
	<?
	} // end of HoetlSearchBox
	
	
	function HoetlSearchBox2($obj='hotel',$functn='searchHotel',$event_id='',$searchtype='')
	{
		$this->event_id=$event_id;
		$this->searchtype=$searchtype;
	?>
<div> <form onsubmit="return false;">
<table class="table" width="100%" >
<tr>
	<td>Hotel:</td>
	<td><input name="search1" type="text" id="search1" value="" size="60" onkeyup="<?php echo $obj; ?>.<?php echo $functn; ?>(this.value,document.getElementById('search2').value,document.getElementById('search3').value,document.getElementById('search4').value,document.getElementById('search5').value,'<?php echo $this->event_id; ?>','<?php echo $this->searchtype;?>', {onUpdate: function(response,root){
					document.getElementById('showhotel').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], headers: { 0:{sorter: false}, 16:{sorter: false}, 17:{sorter: false}}})
			 	}});" autocomplete='off' /></td>
	<td>City:</td>
	<td><input name="search2" type="text" id="search2" value="" size="60" onkeyup="<?php echo $obj; ?>.<?php echo $functn; ?>(document.getElementById('search1').value,this.value,document.getElementById('search3').value,document.getElementById('search4').value,document.getElementById('search5').value,'<?php echo $this->event_id; ?>','<?php echo $this->searchtype;?>', {onUpdate: function(response,root){
					document.getElementById('showhotel').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], headers: { 0:{sorter: false}, 16:{sorter: false}, 17:{sorter: false}}})
			 	}});" autocomplete='off' /></td>
	</tr><tr>
	<td>State:</td>
	<td>	
	<select name="search3" id="search3" style="width:100%" onchange="<?php echo $obj; ?>.<?php echo $functn; ?>(document.getElementById('search1').value,document.getElementById('search2').value,this.value,document.getElementById('search4').value,document.getElementById('search5').value,'<?php echo $this->event_id; ?>','<?php echo $this->searchtype;?>', {onUpdate: function(response,root){
					document.getElementById('showhotel').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], headers: { 0:{sorter: false}, 16:{sorter: false}, 17:{sorter: false}}})
			 	}});" autocomplete='off'  >
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
		</select>	</td>
	<td>
	  Distance(miles):</td>
	<td><input name="search5" type="text" id="search5" value="" size="60" onkeyup="<?php echo $obj; ?>.<?php echo $functn; ?>(document.getElementById('search1').value,document.getElementById('search2').value,document.getElementById('search3').value,document.getElementById('search4').value,this.value,'<?php echo $this->event_id; ?>','<?php echo $this->searchtype;?>', {onUpdate: function(response,root){
					document.getElementById('showhotel').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], headers: { 0:{sorter: false}, 16:{sorter: false}, 17:{sorter: false}}})
			 	}});" autocomplete='off' />
				</td>
	</tr>
	<tr>
	<td><!--Zip:-->&nbsp;</td>
	<td><input name="search4" type="text" id="search4" value="" size="60" onkeyup="<?php echo $obj; ?>.<?php echo $functn; ?>(document.getElementById('search1').value,document.getElementById('search2').value,document.getElementById('search3').value,this.value,document.getElementById('search5').value,'<?php echo $this->event_id; ?>','<?php echo $this->searchtype;?>', {onUpdate: function(response,root){
					document.getElementById('showhotel').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
			 	}});" autocomplete='off' style="display:none" /></td>
	</tr>
	</table>
	
	
	<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
</form>
  <!--or  <a href="#">More Options</a> --></div>
	<?
	} // end of HoetlSearchBox2
	
	function addhotel_toEvent($event_id,$target)
	{	
		ob_start();
			$this->event_id=$event_id;
			$sql_event="select * from ".EM_EVENT." where event_id='".$this->event_id."'";
			$result_event=$this->db->query($sql_event,__FILE__,__LINE__);
			$row_event=$this->db->fetch_array($result_event);

				?>

			<div class="prl">&nbsp;</div>
				<div id="lightbox">
				<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle">Add Hotel To Event</div>
					<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_credential').style.display='none'; document.getElementById('div_credential').style.display='none'; 
		hotel.GetHotelsInEvents('<?php echo $this->event_id ?>',{target:'eventhotel',preloader: 'prl'});
		hotel.getSuggestedHotel('<?php echo $this->event_id ?>',{target:'suggesthotel',preloader: 'prl'});
		$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}});"><img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
					
				<div style="padding:20px;">
			
			<div id="get_hotel">	
			<?php echo $this->GetHotelsInEvents($this->event_id,'table'); ?>
			</div>
			
				
			<table class="table" width="100%">
			<tr><td colspan="4"><div id="hotelbasicadd"><?php echo $this->basicAddHotel($this->event_id); ?></div></td></tr>
			</table>
			
			<table class="table" width="100%">
			
			<tr><td colspan="2">&nbsp;</td></tr>
			
			<tr>
				<td colspan="2" align="right">

			<a href="javascript:void(0)" onclick="javascript: if(get_radio_value('frm_searchHotel','rd_searchHotel')){ hotel.assignHotel('<?php echo $this->event_id;?>', 
			get_radio_value('frm_searchHotel','rd_searchHotel')
			, 'eventhotel',{onUpdate: function(response,root){
				document.getElementById('showhotel').innerHTML=response;
				
				hotel.GetHotelsInEvents('<?php echo $this->event_id ?>','table',{target:'get_hotel',preloader: 'prl'});
				hotel.searchHotel('','','','',0,'<?php echo $this->event_id ?>',{ onUpdate: function(response,root){ 	
		   					 document.getElementById('showhotel').innerHTML=response;
		  				 	 document.getElementById('showhotel').style.display='';
							 $('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], headers: { 0:{sorter: false}, 16:{sorter: false}, 17:{sorter: false}}})}} );
			 	},preloader:'prl'}); 
						
				}
				else
				{
					alert('please select a hotel first..');
					
				} return false;">Add Hotel</a>
					
				</td>			
			</tr>
			
			<tr>
			<td><h2>Search:</h2></td>
			<td>
				<div align="right"> 
				<a href="#" onclick="table2CSV($('#search_table')); return false;"> 
				<img src="images/csv.png"  alt="Export to CSV" /> 
				</a> 
				</div>
			    <?php $this->HoetlSearchBox2($obj='hotel', 'searchHotel', $this->event_id, 'hotel'); ?>
			</td>
			
			<tr>
			<td colspan="2" align="right">
				<div id="showhotel"><?php echo $this->searchHotel('','','','',0,$this->event_id); ?></div>
			</td>
			</tr>
			</table>
			
			</div></div></div>
			<?php
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
	}// end of add hotel to event
	
		
	function assignHotel($event_id,$hotel_id,$target='eventhotel')
	{
		ob_start();
		$this->hotel_id=$hotel_id;
		$this->event_id=$event_id;
		
		$tar=$target;
		$sql = "select * from ".EM_HOTEL_STAY." where hotel_id='".$this->hotel_id."' and event_id='".$this->event_id."'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($result)<=0){
		  $insert_sql_array[hotel_id] = $this->hotel_id;
		  $insert_sql_array[event_id] = $this->event_id;
		  $this->db->insert(EM_HOTEL_STAY,$insert_sql_array);
		} else {
		?>
		<script type="text/javascript">
		  alert('Hotel already in list')
		</script>
		<?php
		}
		

			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		
	} // end of assign hotel
	
		function unassignHotel($hotel_stay_id,$event_id,$target='eventhotel')
	{
		ob_start();
		$tar=$target;
		$this->hotel_stay_id=$hotel_stay_id;
		$this->event_id=$event_id;
		
		$sql="delete from ".EM_HOTEL_STAY." where hotel_stay_id='".$this->hotel_stay_id."'";
		
		$this->db->query($sql,__FILE__,__LINE__);
		

		
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
	} // end of unassign hotel
	
	function searchHotel($hotel='',$city='',$state='',$zip='',$distance=0,$event_id='',$searchtype='',$order_by='',$order_type='',$obj='hotel')
		{
		
			ob_start();
				
				$this->hotel=$hotel;
				$this->city=$city;
				$this->state=$state;
				$this->zip=$zip;
				$this->event_id=$event_id;
				$this->searchtype=$searchtype;
				$this->distance = $distance;

				$hotels = array();
				$FormName = 'frm_searchHotel';
				$sql="Select * from ".EM_HOTEL." where name like '$this->hotel%' and city like '$this->city%' and state like '$this->state%' and zip like '$this->zip%' ";
				if($order_by!='' and $order_by!='distance' and  $order_by!='price') {
					$sql.="order by ".$order_by." ".$order_type;
					}
							
				$result=$this->db->query($sql,__FILE__,__LINE__);
				$x=0;
				while($row=$this->db->fetch_array($result)){
					foreach($row as $key=>$value){
						$hotels[$x][$key] = $value;
					}
					$x++;
				}
				
				$sql_event="select * from ".EM_EVENT." where event_id='".$this->event_id."'";
				$result_event=$this->db->query($sql_event,__FILE__,__LINE__);
				$row_event=$this->db->fetch_array($result_event);
				$hotelInRange = array();
				/*if(!$this->distance)
				  $this->distance = $this->getHotelRange();*/
				if($order_by=='distance') {
				$hotels_list = $this->getHotelsSearch($row_event[zip],$this->distance, $order_type, '');
				}
				else
				if($order_by=='price')
				{
				$hotels_list = $this->getHotelsSearch($row_event[zip],$this->distance, '', $order_type);
				}
				else if($order_by=='')
				{
				$hotels_list = $this->getHotelsSearch($row_event[zip],$this->distance);
				}
				else
				{
				$hotels_list = $this->getHotelsSearch($row_event[zip],$this->distance,'','');
				}
				$hotelInRange = $this->getHotelsInRange($hotels,$hotels_list);
				echo "<div>hotels:</div><pre>";
				//print_r($hotelInRange);
				//print_r($hotels_list);
				echo "</pre>";
				/*if($this->distance){
				  if($this->hotel=='' and $this->city=='' and $this->state=='' and $this->zip==''){
				  	$hotels_list = $this->getHotelsSearch($row_event[zip],$this->distance);
					$hotelInRange = $hotels_list;
				  } else {
					$hotels_list = $this->getHotelsSearch($row_event[zip],$this->distance);
					$hotelInRange = $this->getHotelsInRange($hotels,$hotels_list);
				  }
				}
				else{
					$hotelInRange = $hotels;
				}*/
				
?>				<form action=""  method="post" enctype="multipart/form-data" name="<?php echo $FormName; ?>">	
				<input type="hidden" id="name_head" name="name_head"  
				<?php if($order_by=='name' and $order_type=='desc') 
				{ echo "value='asc'";
				 } 
				else if($order_by=='name' and $order_type=='asc')
				{ echo "value='desc'";}
				else 
				{
				echo "value='asc'";
				}
				 ?>
				
				 />
				<input type="hidden" id="phone_head" name="phone_head" 				
				<?php if($order_by=='phone' and $order_type=='desc') 
				{ echo "value='asc'";
				 } 
				else if($order_by=='phone' and $order_type=='asc')
				{ echo "value='desc'";}
				else 
				{
				echo "value='asc'";
				}
				 ?>  />
				<input type="hidden" id="city_head" name="city_head" 				
				<?php if($order_by=='city' and $order_type=='desc') 
				{ echo "value='asc'";
				 } 
				else if($order_by=='city' and $order_type=='asc')
				{ echo "value='desc'";}
				else
				{
				echo "value='asc'";
				}
				 ?>  />

				<input type="hidden" id="state_head" name="state_head" 				
				<?php if($order_by=='state' and $order_type=='desc') 
				{ echo "value='asc'";
				 } 
				else if($order_by=='state' and $order_type=='asc')
				{ echo "value='desc'";}
				else 
				{
				echo "value='asc'";
				}
				 ?>  />

				<input type="hidden" id="price_head" name="price_head" 				
				<?php if($order_by=='price' and $order_type=='desc') 
				{ echo "value='asc'";
				 } 
				else if($order_by=='price' and $order_type=='asc')
				{ echo "value='desc'";}
				else
				{
				echo "value='asc'";
				}
				 ?>  />

				<input type="hidden" id="distance_head" name="distance_head" 				
				<?php if($order_by=='distance' and $order_type=='desc') 
				{ echo "value='asc'";
				 } 
				else if($order_by=='distance' and $order_type=='asc')
				{ echo "value='desc'";}
				else
				{
				echo "value='asc'";
				}
				 ?>  />

				<input type="hidden" id="zip_head" name="zip_head" 				
				<?php if($order_by=='zip' and $order_type=='desc') 
				{ echo "value='asc'";
				 } 
				else if($order_by=='zip' and $order_type=='asc')
				{ echo "value='desc'";}
				else
				{
				echo "value='asc'";
				}
				?>  />
				<table id="search_table" class="event_form small_text" width="100%">
				<thead>
				<tr>
					<th></th>
					<th>Hotel Name</th>
					<th>Phone</th>
					<th>City</th>
					<th>State</th>
					<th>Avg-$</th>
					<th>Distance(miles)</th>
					<th>Zip</th>
				</tr>
				</thead>
				<tbody>
			<?php	
				$x=0;
				
				foreach($hotelInRange as $key=>$value)
				{
					$sql_stay="Select * from ".EM_HOTEL_STAY." where event_id='".$this->event_id."' and hotel_id='".$value['hotel_id']."'";
					
					$result_stay=$this->db->query($sql_stay,__FILE__,__LINE__);
					if($this->db->num_rows($result_stay)<=0)
					{
						?>	
							<tr id="hotellist_<?php echo $value['hotel_id'];?>">
							 <td><input type="radio" name="rd_searchHotel" id="rd_searchHotel<?php echo $value['hotel_id'];?>" value="<?php echo $value['hotel_id'];?>" /></td>
							 <td><?php echo $value['name'];?></td>
							 <td><?php echo $value['phone'];?></td>
							 <td><?php echo $value['city'];?></td>
							 <td><?php echo $value['state'];?></td>
							 <td><?php
							  $sql_avg_price="SELECT *,AVG(booking_cost) FROM ".EM_HOTEL_STAY." where hotel_id='".$value['hotel_id']."' GROUP BY event_id";
							  $result_avg_price=$this->db->query($sql_avg_price,__FILE__,__LINE__);
							  $row_avg_price=$this->db->fetch_array($result_avg_price);
							  echo $row_avg_price['booking_cost'];?>
							 </td>
							 <td><?php echo number_format($value['distance'],1,'.',''); ?></td>
							 <td><?php echo $value['zip'];?></td>
							</tr>
						<?php
						
					}
					$x++;
					
				}
				if($x==0)
				{
					?>
					<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>no result </td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					</tr>
					<?php
				}

				?>
				</tbody>
				</table>
				<div class="verysmall_text form_bg" align="left">*Hold 'Shift Key' to sort multiple field</div>
				</form>
			<?php 
			$html = ob_get_contents();
			ob_end_clean();
			return $html;	
		} // end of get hotel
		
		
	function getHotelsSearch($zip, $distance=0,$order_typedist='asc', $order_typeprice='asc'){
		$x=0;
		$hotels = array();
		$row = $this->zip_obj->get_zip_point($zip);
		if($row[lat]){
		if($distance){
		  $sql = "SELECT a.*,c.lat,c.lon,c.zip_code , (((acos( sin( ( ".$row[lat]." * pi( ) /180 ) ) * sin( (c.lat * pi( )/180 ) ) + cos( ( ".$row[lat]." * pi( ) /180 ) ) * cos( (c.lat * pi( ) /180 )) * cos( ((".$row[lon]." - c.lon) * pi( ) /180 )))) *180 / pi( )) *60 * 1.1515) AS distance FROM ".EM_HOTEL." a, ".ZIP_CODE." c WHERE a.zip = c.zip_code HAVING distance <= ".$distance;
		  if($order_typedist!='')
		  $sql.=" ORDER BY distance ".$order_typedist;
		  if($order_typprice!='')
		  $sql.=" , a.price  ".$order_typeprice;
		} else {
		  $sql = "SELECT a.*,c.lat,c.lon,c.zip_code , (((acos( sin( ( ".$row[lat]." * pi( ) /180 ) ) * sin( (c.lat * pi( )/180 ) ) + cos( ( ".$row[lat]." * pi( ) /180 ) ) * cos( (c.lat * pi( ) /180 )) * cos( ((".$row[lon]." - c.lon) * pi( ) /180 )))) *180 / pi( )) *60 * 1.1515) AS distance FROM ".EM_HOTEL." a, ".ZIP_CODE." c WHERE a.zip = c.zip_code";
		  if($order_typedist!='')
		  $sql.=" ORDER BY distance $order_typedist ";
		  if($order_typprice!='')
		  $sql.=" , a.price  $order_typeprice";
		}
		
		//echo $sql;
		$result = $this->db->query($sql,__FILE__,__LINE__);	
		while($row_hotel=$this->db->fetch_array($result)){
			foreach($row_hotel as $key=>$value){
				$hotels[$x][$key] = $value;
				
			}
			$x++;
		}
		}
		return $hotels;
	}
	
	function getHotelsInRange($hotels,$hotels_list){
		$hotelInRange = array();
		$x=0;
		foreach($hotels as $key=>$value){				
			foreach($hotels_list as $key_zip=>$value_zip){
				if($value_zip[zip]==$value[zip]){
					$hotelInRange[$x] = $value;
					$hotelInRange[$x]['distance'] = $value_zip[distance];
					$x++;
					break;
				}
			}
		}
		return $hotelInRange;
	}
	
	function getHotelRange()
	{
		$sql = "SELECT * FROM ".EM_SUGGESTION_MASTER." where suggestion_type = 'HOTEL'";
		$result = $this->db->query($sql,__FILE__,__LINE__);	
		$row = $this->db->fetch_array($result);
		return $row[distance];
	}	
	
	function getSuggestedHotel($event_id)
	{
		ob_start();
		$this->event_id = $event_id;
		$sql_event="select * from ".EM_EVENT." where event_id='".$this->event_id."'";
		$result_event=$this->db->query($sql_event,__FILE__,__LINE__);
		$row_event=$this->db->fetch_array($result_event);
		$range = $this->getHotelRange();
		$sugg_hotel = $this->getHotelsSearch($row_event[zip],$range);
		
		$v=0;
		while($sugg_hotel[$v][distance])
		{ 
			$sql_hotel_stay="select * from ".EM_HOTEL_STAY." where event_id='".$this->event_id."' and hotel_id='".$sugg_hotel[$v][hotel_id]."'";
			$result_hotel_stay=$this->db->query($sql_hotel_stay,__FILE__,__LINE__);
			
			if($this->db->num_rows($result_hotel_stay)<=0)
			{
		?>
		<table class="table">
		  <tr>
		    <td>
			  <?php echo "<h2>Suggested -</h2> $ ".$sugg_hotel[$v]['price']." - ".number_format($sugg_hotel[$v][distance],1)." distance<br />"; ?>
			  <a href="javascript:void(0)" onclick="javascript: hotel.assignHotel('<?php echo $this->event_id;?>','<?php echo $sugg_hotel[$v][hotel_id] ?>', 'eventhotel',{ onUpdate: function(response,root){ 	
		   					 hotel.GetHotelsInEvents('<?php echo $this->event_id ?>',{target:'eventhotel',preloader: 'prl'});
							 hotel.getSuggestedHotel('<?php echo $this->event_id ?>',{target:'suggesthotel',preloader: 'prl'});
							 
							 }, preloader: 'prl'
						} );return false;">add</a>
			  <?php echo $sugg_hotel[$v][name] ?><br />
			  <?php echo $sugg_hotel[$v][address1]; ?> <?php echo $sugg_hotel[$v][address2]; ?><br />
			  <?php echo $sugg_hotel[$v][city] ?>, <?php echo $sugg_hotel[$v][state] ?> <?php echo $sugg_hotel[$v][zip] ?><br />
			  <a href="javascript:void(0)" onclick="javascript: hotel.addhotel_toEvent('<?php echo $this->event_id;?>','div_credential',
			{ onUpdate: function(response,root){ 	
		   					 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';
							 
							 }, preloader: 'prl'
						} ); return false;">...find more </a>
			</td>
		  </tr>
		</table>
		<?php
			break;
		 } 
			$v++;
		} // end of while
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		
	}
	
	function basicAddHotel($event_id)
	{
		ob_start();
		$this->event_id=$event_id;
		$target='div_credential';
		
		//create client side validation
				$FormName='frm_Add_Hotel';
				$ControlNames=array("name"			=>array('name',"''","Please Enter Hotel Name !! ","span_hotel_name"),
									"address1"			=>array('address1',"''","Please Enter Address !! ","span_address1"),
									"city"					=>array('city',"''","Please Enter City !! ","span_city"),
									"state"					=>array('state',"''","Please Enter State !! ","span_state"),
									"phone"					=>array('phone',"''","Please Enter Phone Number !! ","span_phone")

									);
											
						
						$this->ValidationFunctionName="AddHotel_CheckValidity";
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,
												$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						?>
		<form action=""  method="post" enctype="multipart/form-data" name="<?php echo $FormName; ?>">
												  
						  <table class="table" width="100%">
						  	<tr>
							  <td colspan="4">
							  <ul id="error_list">
							    <li><span id="span_hotel_name"></span></li>
								<li ><span id="span_address1"></span></li>
								<li ><span id="span_city"></span></li>
								<li ><span id="span_state"></span></li>
								<li ><span id="span_phone"></span></li>
							  </ul>
							  </td>
							</tr><tr>
							  <td width="28%"><h2>Hotel Name </h2></td>
								  <td colspan="3" align="left"><input type="text" name="name" id="name" size="30" value="<?php echo $_POST['name'];?>" /></td>
								  </tr>
								  <tr>
								  <td><h2>Street Address 1 </h2></td>
								  <td colspan="3" align="left"><input type="text" name="address1" id="address1" size="30" value="<?php echo $_POST['address1'];?>" /></td>
								  </tr>
								  <tr>
								  <td><h2>Street Address 2 </h2></td>
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
								  <td colspan="4" align="right">
								  
								  <input type="button" name="submit" id="submit" value="Ok" onclick="javascript: 
					 if(<?php echo $this->ValidationFunctionName; ?>()) {
					  hotel.addHotel('server','<?php echo $target; ?>',this.form.name.value,this.form.address1.value,this.form.address2.value,this.form.city.value,this.form.state.value,this.form.zip.value,this.form.phone.value,this.form.fax.value,<?php echo $this->event_id; ?>,{preloader: 'prl'}); } return false;" style="width:auto"/>
					  </td></tr></table></form>
	<?php	
			$html = ob_get_contents();
			ob_end_clean();
			return $html;

	} // end of basic add hotel form
	
	function GetHotelsInEvents($event_id, $showtype='normal')
	{
		ob_start();
		$this->event_id=$event_id;
		$this->showtype=$showtype;
		
		$sql="Select * from ".EM_HOTEL_STAY." where event_id='".$this->event_id."'";
		$result=$this->db->query($sql,__FILE__,__LINE__);
		
		if($this->showtype=='normal')
		{
		$i=0;
		while($row=$this->db->fetch_array($result))
		{
		
			$this->hotel_id=$row['hotel_id'];
			
			$sql_hotel="Select * from ".EM_HOTEL." where hotel_id='".$this->hotel_id."'";
			$result_hotel=$this->db->query($sql_hotel,__FILE__,__LINE__);
			?><?php
			
			while($row_hotel=$this->db->fetch_array($result_hotel))
			{
				
				?>
				<?php if($i%3==0) { ?> <div class="clear">&nbsp;</div><?php } ?>
				<div class="hotel" style="width: 200px;">
				<?php
				echo $row_hotel['name']."<br />";
				echo $row_hotel['address1']."<br />";
				echo $row_hotel['city'].", ".$row_hotel['state'].", ".$row_hotel['zip']."<br />";
				echo $row_hotel['phone']."<br />";
				?>
				</div>
				<?php 
				
			}
			?><?php $i++;
	 	}	
	} // end of if 
	
	elseif($this->showtype=='table')
	{
		
		$FormName='display_event_hotels';
		?>
		<form action=""  method="post" enctype="multipart/form-data" name="<?php echo $FormName; ?>">
		<table width="100%" class="event_form small_text">
		<tr>
		<th>Name</th>
		<th>City/St/Zip</th>
		<th>Prepaid</th>
		<th>Cost</th>
		<th>Confirmation Code</th>
		<th>&nbsp;</th>
		</tr>
		
		<?php
		$i=1;
		while($row=$this->db->fetch_array($result))
		{
			$this->hotel_id=$row['hotel_id'];
		
			$sql_hotel="Select * from ".EM_HOTEL." where hotel_id='".$this->hotel_id."'";
			$result_hotel=$this->db->query($sql_hotel,__FILE__,__LINE__);
			
			$sql_hotel_stay="Select * from ".EM_HOTEL_STAY." where hotel_id='".$this->hotel_id."' and event_id='".$this->event_id."'";
			$result_hotel_stay=$this->db->query($sql_hotel_stay,__FILE__,__LINE__);
			$row_hotel_stay=$this->db->fetch_array($result_hotel_stay);
			$x=1;
			
			while($row_hotel=$this->db->fetch_array($result_hotel))
			{
			?>
			<tr height="20px" <?php if($i%2==0) { echo 'class="alt2"'; } ?> >
				<td><?php echo $row_hotel['name'] ?></td>
				<td><?php echo $row_hotel['city'].", ".$row_hotel['state']." ".$row_hotel['zip'] ?></td>
				<td><div id="confirmation_select">
					<select name="confrm<?php echo $x; ?>" id="confrm<?php echo $x; ?>" onchange="javascript: 
					  hotel.UpdateHotelprepaid('<?php echo $row_hotel_stay['hotel_stay_id'] ?>',this.value,'confirmation_select<?php echo $x; ?>',{ preloader: 'prl'});  return false;">
					<option value="no" <?php if($row_hotel_stay['prepaid']=='no'){echo 'selected="selected"';}?> >No</option>
					<option value="yes" <?php if($row_hotel_stay['prepaid']=='yes'){echo 'selected="selected"';}?>>Yes</option>
					</select>
					</div>
					
			    </td>
				
				
				<td><div id="booking_cost<?php echo $x; ?>">
					<input type="text" name="booking_cost" id="booking_cost" value="<?php echo $row_hotel_stay['booking_cost'] ?>" style="width:100px" onchange="javascript: 
					  hotel.UpdateHotelprice('<?php echo $row_hotel_stay['hotel_stay_id'] ?>',this.value,'booking_cost<?php echo $x; ?>',{ preloader: 'prl'});  return false;" />
					  </div>
				</td>
				<td><div id="confirmation_num<?php echo $x; ?>">
					<input type="text" name="confirmation_code" id="confirmation_code" value="<?php echo $row_hotel_stay['confirmation_no'] ?>" style="width:100px" onchange="javascript: 
					  hotel.UpdateHotelconfirmationnum('<?php echo $row_hotel_stay['hotel_stay_id'] ?>',this.value,'confirmation_num<?php echo $x; ?>',{ preloader: 'prl'});  return false;" />
					  </div>
				</td>
				<td>
			<a href="javascript:void(0)" onclick="javascript: if(confirm('are you sure?')){ hotel.unassignHotel('<?php echo $row['hotel_stay_id'] ?>','<?php echo $this->event_id ?>','eventhotel',{onUpdate: function(response,root){
				
				hotel.GetHotelsInEvents('<?php echo $this->event_id ?>','table',{target:'get_hotel',preloader: 'prl'});
				hotel.searchHotel('','','','',0,'<?php echo $this->event_id ?>',{ onUpdate: function(response,root){ 	
		   					 document.getElementById('showhotel').innerHTML=response;
		  				 	 document.getElementById('showhotel').style.display='';
							 $('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], headers: { 0:{sorter: false}, 16:{sorter: false}, 17:{sorter: false}}})}} );
			 	},preloader:'prl'})} else {return false; }"><img src="images/trash.gif" /></a></td>
			</tr>
			<?php 
			$i++;
			$x++;
		 	}
		}?>
		</table>
		</form>
		<?php 	
	} 
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
	 
	} // end of GetHotelsInEvent
	
	function UpdateHotelprepaid($hotel_stay_id,$prepaid,$target='')
	{
	
		ob_start();
		$this->hotel_stay_id=$hotel_stay_id;
		$this->prepaid=$prepaid;
		
		  //$tar=$target;
		  
		  $update_sql_array = array();
		  $update_sql_array[prepaid] = $this->prepaid;
		  
		  $this->db->update(EM_HOTEL_STAY,$update_sql_array,'hotel_stay_id',$this->hotel_stay_id);

			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		
		
	} // end of UpdateHotelConfirmatin
	
	
		function UpdateHotelconfirmationnum($hotel_stay_id,$confirmation_code,$target='')
	{
	
		ob_start();
		$this->hotel_stay_id=$hotel_stay_id;
		$this->confirmation_code=$confirmation_code;
		
		  //$tar=$target;
		  
		  $update_sql_array = array();
		  $update_sql_array[confirmation_no] = $this->confirmation_code;
		  
		  $this->db->update(EM_HOTEL_STAY,$update_sql_array,'hotel_stay_id',$this->hotel_stay_id);

			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		
		
	} // end of UpdateHotelConfirmatin
	
			function UpdateHotelprice($hotel_stay_id,$booking_cost,$target='')
	{
	
		ob_start();
		$this->hotel_stay_id=$hotel_stay_id;
		$this->booking_cost=$booking_cost;
		
		  //$tar=$target;
		  
		  $update_sql_array = array();
		  $update_sql_array[booking_cost] = $this->booking_cost;
		  
		  $this->db->update(EM_HOTEL_STAY,$update_sql_array,'hotel_stay_id',$this->hotel_stay_id);

			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		
		
	} // end of UpdateHotelConfirmatin
	
}
?>

