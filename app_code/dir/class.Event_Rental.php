<?php

/***********************************************************************************

			Class Discription : 
			
			Class Memeber Functions :
			
			
			Describe Function of Each Memeber function: 
			
									
									 

************************************************************************************/
class Event_Renatl
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
	
	function getCarRental($event_id)
	{
		ob_start();
		$this->event_id=$event_id;
		$sql = "select * from ".EM_RENTAL." where event_id='".$this->event_id."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		$i=0;
		while($row=$this->db->fetch_array($result))
			{
				?>
				<?php if($i%3==0) { ?> <div class="clear">&nbsp;</div><?php } ?>
				<div class="hotel">
				<?php
				echo $row['name']."<br />";
				echo $row['address1']."<br />";
				echo $row['city'].", ".$row['state'].", ".$row['zip']."<br />";
				echo $row['phone']."<br />";
				?>
				</div>
				<?php 
				$i++; 
			}
			?>
			<div class="clear">&nbsp;</div>
			<?
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		
		 
	}// show rental	
	
	
		function EditRental($event_id)
	{
		ob_start();
		$this->event_id=$event_id;
		$sql = "select * from ".EM_RENTAL." where event_id='".$this->event_id."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		
		?>
		<div class="prl">&nbsp;</div>
				<div id="lightbox">
				<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle">Car Rental</div>
					<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_credential').style.display='none'; document.getElementById('div_credential').style.display='none'; 
		rental.getCarRental('<?php echo $this->event_id ?>',{target:'car_rental',preloader: 'prl'});"><img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
					
				<div style="padding:20px;">
		<div id="carrentaltable">		
			<?php echo $this->showCarOnFly($this->event_id) ?>
			</div>
			<div id="addbutton">
			<a href="javascript:void(0)" onclick="javascript:document.getElementById('addcar').style.display=''; document.getElementById('addbutton').style.display='none'; return false;">add</a>
			</div>
			<div id="addcar" style="display:none">
			
			<?php $FormName='addCar'; ?>
			<form action=""  method="post" enctype="multipart/form-data" name="<?php echo $FormName; ?>">
			<table width="60%"  class="table" >
			
			<tr>
			<th>Name</th>
			<td>
				<input type="text" name="name" id="name" value="" />
			</td>
			</tr>
			<tr>
				<th>Address 1</th>
				<td><input type="text" name="address1" id="address1" /></td>
			</tr>	
			
			<tr>	
				<th>Address 2</th>
				<td><input type="text" name="address2" id="address2" /></td>
			</tr>
			
			<tr>	
				<th>City</th>
				<td><input type="text" name="city" id="city" /></td>
			</tr>	
			
			<tr>	
				<th>State</th>
				<td>
				<select name="state" id="state" style="width:100%" >
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
				<th>Zip</th>
				<td><input type="text" name="zip" id="zip" /></td>
			</tr>
			
			<tr>	
				<th>Phone</th>
				<td><input type="text" name="phone" id="phone" /></td>
			</tr>
			
			<tr>	
				<th>Fax</th>
				<td><input type="text" name="fax" id="fax" /></td>
			</tr>
			
			<tr>	
				<th>Confirmation Code</th>
				<td><input type="text" name="confirmation_code" id="confirmation_code" /></td>
			</tr>

				<tr>
				<td colspan="2">
<input type="button" name="add" id="add" value="Ok" onclick="javascript: rental.AddNewCarRental(this.form.name.value,this.form.address1.value,this.form.address2.value,this.form.city.value,this.form.state.value,this.form.zip.value,this.form.phone.value,this.form.fax.value,this.form.confirmation_code.value,'<?php echo $this->event_id ?>',{onUpdate: function(response,root){
					rental.showCarOnFly('<?php echo $this->event_id ?>',{ onUpdate: function(response,root){ 	
		   					 document.getElementById('carrentaltable').innerHTML=response;
		  				 	 document.getElementById('carrentaltable').style.display='';
							  document.getElementById('addcar').style.display='none'; 
							  document.getElementById('addbutton').style.display='';
							 }} );
					},preloader:'prl'});   return false;" style="width:auto"/>
				</td>
				</tr>
				</table>
				</form>
			<a href="javascript:void(0)" onclick="javascript:document.getElementById('addbutton').style.display=''; document.getElementById('addcar').style.display='none'; return false;">cancel</a>
			</div>
			
			</div></div></div>
			
	<?php 
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
	
	}// end of edit rental
	
	function UpdateCarRental($updatecol, $value='' , $rowid)
	{
		ob_start();
		$this->updatecol=$updatecol;
		$this->value=$value;
		$this->rowid=$rowid;
		
		//$tar=$target;
		
		$update_sql_array = array();
		$update_sql_array[$this->updatecol] = $this->value;
		
		$this->db->update(EM_RENTAL,$update_sql_array,'rental_id',$this->rowid);
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	
	}
	
		function AddNewCarRental($name='', $address1='', $address2='', $city='', $state='', $zip='', $phone='', $fax='', $confirmation_code='', $event_id)
	{
		ob_start();
		$this->event_id=$event_id;
		$this->name=$name;
		$this->address1=$address1;
		$this->address2=$address2;
		$this->city=$city;
		$this->state=$state;
		$this->zip=$zip;
		$this->phone=$phone;
		$this->fax=$fax;
		$this->confirmation_code=$confirmation_code;
		
		//$tar=$target;
		
		 $insert_sql_array = array();
		 $insert_sql_array[event_id] = $this->event_id;
		 $insert_sql_array[name] = $this->name;
		 $insert_sql_array[address1] = $this->address1;
		 $insert_sql_array[address2] = $this->address2;
		 $insert_sql_array[city] = $this->city;
		 $insert_sql_array[state] = $this->state;
		 $insert_sql_array[zip] = $this->zip;						
		 $insert_sql_array[phone] = $this->phone;
		 $insert_sql_array[fax] = $this->fax;
		 $insert_sql_array[confirmation_code] = $this->confirmation_code;
		 
		  $this->db->insert(EM_RENTAL,$insert_sql_array);	
	
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	
	}
	
	
	
	function showCarOnFly($event_id)
	{
		ob_start();
		$this->event_id=$event_id;
		$sql = "select * from ".EM_RENTAL." where event_id='".$this->event_id."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		$FormName='showCarRental';
		?>
		<form action=""  method="post" enctype="multipart/form-data" name="<?php echo $FormName; ?>">

		<table class="event_form small_text" width="100%"  >
		<thead>
		<th>Name</th>
		<th>Address 1</th>
		<th>Address 2</th>
		<th>City</th>
		<th>State</th>
		<th>Zip</th>
		<th>Phone</th>
		<th>Fax</th>
		<th>Confirmation Code</th>
		<th></th>
		</thead>
		<tbody>
		
		<?php 
		$x=0;
		
		while($row=$this->db->fetch_array($result))
			{
				?>
		<tr>
			<td>
				<a href="javascript:void(0)" onclick="javascript: document.getElementById('editname<?php echo $x;?>').style.display=''; 
				document.getElementById('save<?php echo $x;?>').style.display=''; 
				document.getElementById('name<?php echo $x;?>').style.display='none';
				document.getElementById('carname<?php echo $x?>').focus();">
				<div id="name<?php echo $x;?>">
				<?php 
					if($row['name']==''){echo "--";}
					else{ echo $row['name'];}
				?>
				</div>
				<span id="editname<?php echo $x;?>" style="display:none">
				<input type="text" name="carname<?php echo $x?>" id="carname<?php echo $x?>" value="<?php echo $row['name'];?>" 
				 onchange="javascript:  rental.UpdateCarRental('name',this.value,'<?php echo $row['rental_id']; ?>',{ preloader: 'prl'}); 
				document.getElementById('editname<?php echo $x;?>').style.display='none'; 
				document.getElementById('save<?php echo $x;?>').style.display='none'; 
				if(document.getElementById('carname<?php echo $x?>').value=='' || document.getElementById('name<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('name<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('name<?php echo $x;?>').innerHTML=document.getElementById('carname<?php echo $x?>').value;} 
				document.getElementById('name<?php echo $x;?>').style.display='';  return false;"
				    style="width:100px" />
				</span>
				</a>
				<span id="save<?php echo $x;?>" style="display:none">
				<img src="images/save.png" onclick="javascript: 
				document.getElementById('editname<?php echo $x;?>').style.display='none'; 
				document.getElementById('save<?php echo $x;?>').style.display='none'; 
				if(document.getElementById('carname<?php echo $x?>').value=='' || document.getElementById('name<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('name<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('name<?php echo $x;?>').innerHTML=document.getElementById('carname<?php echo $x?>').value;} 
				document.getElementById('name<?php echo $x;?>').style.display='';  return false;" />
				</span>
			</td>
				
			<td>
				<a href="javascript:void(0)" 
				onclick="javascript:document.getElementById('editadd1<?php echo $x;?>').style.display='';
				document.getElementById('save2<?php echo $x;?>').style.display=''; 
				 document.getElementById('add1<?php echo $x;?>').style.display='none';
				 document.getElementById('address1<?php echo $x?>').focus(); return false;">
					<div id="add1<?php echo $x;?>">
					<?php 
					if($row['address1']==''){echo "--";}
					else{ echo $row['address1'];}
					?>
					</div>
				<span id="editadd1<?php echo $x;?>" style="display:none">
				<input type="text" name="address1<?php echo $x?>" id="address1<?php echo $x?>" value="<?php echo $row['address1'];?>"
				onchange="javascript:  rental.UpdateCarRental('address1',this.value,'<?php echo $row['rental_id']; ?>',{ preloader: 'prl'}); 
				 document.getElementById('editadd1<?php echo $x;?>').style.display='none'; 
				 document.getElementById('save2<?php echo $x;?>').style.display='none'; 
				 if(document.getElementById('address1<?php echo $x?>').value=='' || document.getElementById('add1<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('add1<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('add1<?php echo $x;?>').innerHTML=document.getElementById('address1<?php echo $x?>').value;}
				document.getElementById('add1<?php echo $x;?>').style.display='';  return false;" style="width:100px" />
				</span>
				</a>
				<span id="save2<?php echo $x;?>" style="display:none">
				<img src="images/save.png" onclick="javascript: 
				document.getElementById('editadd1<?php echo $x;?>').style.display='none'; 
				document.getElementById('save2<?php echo $x;?>').style.display='none'; 
				if(document.getElementById('address1<?php echo $x?>').value=='' || document.getElementById('add1<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('add1<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('add1<?php echo $x;?>').innerHTML=document.getElementById('address1<?php echo $x?>').value;} 
				document.getElementById('add1<?php echo $x;?>').style.display='';  return false;" />
				</span>
			</td>
				
			<td>
				<a href="javascript:void(0)" 
				onclick="javascript:document.getElementById('editadd2<?php echo $x;?>').style.display='';
				document.getElementById('save3<?php echo $x;?>').style.display='';
				 document.getElementById('add2<?php echo $x;?>').style.display='none';
				 document.getElementById('caraddress2<?php echo $x?>').focus();  return false;">
				<div id="add2<?php echo $x;?>">
				<?php 
					if($row['address2']==''){echo "--";}
					else{ echo $row['address2'];}
				?>
				</div>
				<span id="editadd2<?php echo $x;?>" style="display:none">
				<input type="text" name="caraddress2<?php echo $x?>" id="caraddress2<?php echo $x?>" value="<?php echo $row['address2'];?>" 
				onchange="javascript:  rental.UpdateCarRental('address2',this.value,'<?php echo $row['rental_id']; ?>',{ preloader: 'prl'});  
				document.getElementById('editadd2<?php echo $x;?>').style.display='none'; 
				document.getElementById('save3<?php echo $x;?>').style.display='none'; 
				if(document.getElementById('caraddress2<?php echo $x?>').value=='' || document.getElementById('add2<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('add2<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('add2<?php echo $x;?>').innerHTML=document.getElementById('caraddress2<?php echo $x?>').value;}
			
				document.getElementById('add2<?php echo $x;?>').style.display='';  return false;" style="width:100px" />
				</span>
				</a>
				<span id="save3<?php echo $x;?>" style="display:none">
				<img src="images/save.png" onclick="javascript: 
				document.getElementById('editadd2<?php echo $x;?>').style.display='none'; 
				document.getElementById('save3<?php echo $x;?>').style.display='none'; 
				if(document.getElementById('caraddress2<?php echo $x?>').value=='' || document.getElementById('add2<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('add2<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('add2<?php echo $x;?>').innerHTML=document.getElementById('caraddress2<?php echo $x?>').value;} 
				document.getElementById('add2<?php echo $x;?>').style.display='';  return false;" />
				</span>
			</td>
				
			<td>
				<a href="javascript:void(0)" 
				onclick="javascript:document.getElementById('editcity<?php echo $x;?>').style.display='';
				document.getElementById('save4<?php echo $x;?>').style.display='';
				 document.getElementById('carcity<?php echo $x?>').focus();
				 document.getElementById('city<?php echo $x;?>').style.display='none'; return false;">
				 
				<div id="city<?php echo $x;?>">
				<?php 
					if($row['city']==''){echo "--";}
					else{ echo $row['city'];}
				?>
				</div>
				
				<span id="editcity<?php echo $x;?>" style="display:none">
				<input type="text" name="carcity<?php echo $x?>" id="carcity<?php echo $x?>" value="<?php echo $row['city'];?>" 
				onchange="javascript:  rental.UpdateCarRental('city',this.value,'<?php echo $row['rental_id']; ?>',{ preloader: 'prl'});  
				document.getElementById('editcity<?php echo $x;?>').style.display='none'; 
				document.getElementById('save4<?php echo $x;?>').style.display='none'; 
				if(document.getElementById('carcity<?php echo $x?>').value=='' || document.getElementById('city<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('city<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('city<?php echo $x;?>').innerHTML=document.getElementById('carcity<?php echo $x?>').value;} 
				
				document.getElementById('city<?php echo $x;?>').style.display='';  return false;" style="width:100px" />
				</span>
				</a>
				
				<span id="save4<?php echo $x;?>" style="display:none">
				<img src="images/save.png" onclick="javascript: 
				document.getElementById('editcity<?php echo $x;?>').style.display='none'; 
				document.getElementById('save4<?php echo $x;?>').style.display='none'; 
				if(document.getElementById('carcity<?php echo $x?>').value=='' || document.getElementById('city<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('city<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('city<?php echo $x;?>').innerHTML=document.getElementById('carcity<?php echo $x?>').value;} 
				document.getElementById('city<?php echo $x;?>').style.display='';  return false;" />
				</span>
			</td>
				
			<td>
				<a href="javascript:void(0)" 
				onclick="javascript:document.getElementById('editstate<?php echo $x;?>').style.display='';
				document.getElementById('save5<?php echo $x;?>').style.display='';
				 document.getElementById('carstate<?php echo $x?>').focus();
				 document.getElementById('state<?php echo $x;?>').style.display='none'; return false;">
				 
				<div id="state<?php echo $x;?>">
				<?php 
					if($row['state']==''){echo "--";}
					else{ echo $row['state'];}
				?>
				</div>
				
				<span id="editstate<?php echo $x;?>" style="display:none">
				<input type="text" name="carstate<?php echo $x?>" id="carstate<?php echo $x?>" value="<?php echo $row['state'];?>" 
				onchange="javascript:  rental.UpdateCarRental('state',this.value,'<?php echo $row['rental_id']; ?>',{ preloader: 'prl'});  
				document.getElementById('editstate<?php echo $x;?>').style.display='none'; 
				document.getElementById('save5<?php echo $x;?>').style.display='none'; 
				if(document.getElementById('carstate<?php echo $x?>').value=='' || document.getElementById('state<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('state<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('state<?php echo $x;?>').innerHTML=document.getElementById('carstate<?php echo $x?>').value;} 
				
				document.getElementById('state<?php echo $x;?>').style.display='';  return false;" style="width:100px" />
				</span>
				</a>
				
				<span id="save5<?php echo $x;?>" style="display:none">
				<img src="images/save.png" onclick="javascript: 
				document.getElementById('editstate<?php echo $x;?>').style.display='none'; 
				document.getElementById('save5<?php echo $x;?>').style.display='none'; 
				if(document.getElementById('carstate<?php echo $x?>').value=='' || document.getElementById('state<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('state<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('state<?php echo $x;?>').innerHTML=document.getElementById('carstate<?php echo $x?>').value;}
				document.getElementById('state<?php echo $x;?>').style.display='';  return false;" />
				</span>
			</td>
				
			<td>
				<a href="javascript:void(0)" 
				onclick="javascript:document.getElementById('editzip<?php echo $x;?>').style.display=''; 
				document.getElementById('zip<?php echo $x;?>').style.display='none';
				document.getElementById('save6<?php echo $x;?>').style.display='';
				document.getElementById('carzip<?php echo $x?>').focus();  return false;">
				<div id="zip<?php echo $x;?>">
				<?php 
					if($row['zip']==''){echo "--";}
					else{ echo $row['zip'];}
				?>
				</div>
				<span id="editzip<?php echo $x;?>" style="display:none">
				<input type="text" name="carzip<?php echo $x?>" id="carzip<?php echo $x?>" value="<?php echo $row['zip'];?>" 
				onchange="javascript:  rental.UpdateCarRental('zip',this.value,'<?php echo $row['rental_id']; ?>',{ preloader: 'prl'});  
				document.getElementById('editzip<?php echo $x;?>').style.display='none'; 
				document.getElementById('save6<?php echo $x;?>').style.display='none';
				if(document.getElementById('carzip<?php echo $x?>').value=='' || document.getElementById('zip<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('zip<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('zip<?php echo $x;?>').innerHTML=document.getElementById('carzip<?php echo $x?>').value;}
				
				document.getElementById('zip<?php echo $x;?>').style.display='';  return false;" style="width:100px" />
				</span>
				</a>
				<span id="save6<?php echo $x;?>" style="display:none">
				<img src="images/save.png" onclick="javascript: 
				document.getElementById('editzip<?php echo $x;?>').style.display='none'; 
				document.getElementById('save6<?php echo $x;?>').style.display='none'; 
				if(document.getElementById('carzip<?php echo $x?>').value=='' || document.getElementById('zip<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('zip<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('zip<?php echo $x;?>').innerHTML=document.getElementById('carzip<?php echo $x?>').value;}
				document.getElementById('zip<?php echo $x;?>').style.display='';  return false;" />
				</span>
			</td>
				
			<td>
				<a href="javascript:void(0)" 
				onclick="javascript:document.getElementById('editphone<?php echo $x;?>').style.display='';
				document.getElementById('save7<?php echo $x;?>').style.display=''; 
				document.getElementById('phone<?php echo $x;?>').style.display='none';
				document.getElementById('carphone<?php echo $x?>').focus(); return false;">
				<div id="phone<?php echo $x;?>">
				<?php 
					if($row['phone']==''){echo "--";}
					else{ echo $row['phone'];}
				?>
				</div>
				<span id="editphone<?php echo $x;?>" style="display:none">
				<input type="text" name="carphone<?php echo $x?>" id="carphone<?php echo $x?>" value="<?php echo $row['phone'];?>" 
				onchange="javascript:  rental.UpdateCarRental('phone',this.value,'<?php echo $row['rental_id']; ?>',{ preloader: 'prl'});  
				document.getElementById('editphone<?php echo $x;?>').style.display='none';
				document.getElementById('save7<?php echo $x;?>').style.display='none'; 
				if(document.getElementById('carphone<?php echo $x?>').value=='' || document.getElementById('phone<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('phone<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('phone<?php echo $x;?>').innerHTML=document.getElementById('carphone<?php echo $x?>').value;}
				
				document.getElementById('phone<?php echo $x;?>').style.display='';  return false;" style="width:100px" />
				</span>
				</a>
				<span id="save7<?php echo $x;?>" style="display:none">
				<img src="images/save.png" onclick="javascript: 
				document.getElementById('editphone<?php echo $x;?>').style.display='none'; 
				document.getElementById('save7<?php echo $x;?>').style.display='none'; 
				if(document.getElementById('carphone<?php echo $x?>').value=='' || document.getElementById('phone<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('phone<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('phone<?php echo $x;?>').innerHTML=document.getElementById('carphone<?php echo $x?>').value;}
				document.getElementById('phone<?php echo $x;?>').style.display='';  return false;" />
				</span>
				
			</td>
				
			<td>
				<a href="javascript:void(0)" 
				onclick="javascript:document.getElementById('editfax<?php echo $x;?>').style.display=''; 
				document.getElementById('save8<?php echo $x;?>').style.display=''; 
				document.getElementById('fax<?php echo $x;?>').style.display='none';
				document.getElementById('carfax<?php echo $x?>').focus(); return false;">
				<div id="fax<?php echo $x;?>">
				<?php 
					if($row['fax']==''){echo "--";}
					else{ echo $row['fax'];}
				?>
				</div>
				<span id="editfax<?php echo $x;?>" style="display:none">
				<input type="text" name="carfax<?php echo $x?>" id="carfax<?php echo $x?>" value="<?php echo $row['fax'];?>" 
				onchange="javascript:  rental.UpdateCarRental('fax',this.value,'<?php echo $row['rental_id']; ?>',{ preloader: 'prl'});  
				document.getElementById('editfax<?php echo $x;?>').style.display='none'; 
				document.getElementById('save8<?php echo $x;?>').style.display='none'; 
				if(document.getElementById('carfax<?php echo $x?>').value=='' || document.getElementById('fax<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('fax<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('fax<?php echo $x;?>').innerHTML=document.getElementById('carfax<?php echo $x?>').value;}
				
				document.getElementById('fax<?php echo $x;?>').style.display='';  return false;" style="width:100px" />
				</span>
				</a>
				<span id="save8<?php echo $x;?>" style="display:none">
				<img src="images/save.png" onclick="javascript: 
				document.getElementById('editfax<?php echo $x;?>').style.display='none'; 
				document.getElementById('save8<?php echo $x;?>').style.display='none'; 
				if(document.getElementById('carfax<?php echo $x?>').value=='' || document.getElementById('fax<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('fax<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('fax<?php echo $x;?>').innerHTML=document.getElementById('carfax<?php echo $x?>').value;}
				document.getElementById('fax<?php echo $x;?>').style.display='';  return false;" />
				</span>
			</td>
				
			<td>
				<a href="javascript:void(0)" 
				onclick="javascript:document.getElementById('editconfirmation_code<?php echo $x;?>').style.display=''; 
				document.getElementById('save9<?php echo $x;?>').style.display='';
				document.getElementById('confirmation_code<?php echo $x;?>').style.display='none';
				document.getElementById('carconfirmation_code<?php echo $x?>').focus(); return false;">
				<div id="confirmation_code<?php echo $x;?>">
				<?php 
					if($row['confirmation_code']==''){echo "--";}
					else{ echo $row['confirmation_code'];}
				?>
				</div>
				<span id="editconfirmation_code<?php echo $x;?>" style="display:none">
				<input type="text" name="carconfirmation_code<?php echo $x?>" id="carconfirmation_code<?php echo $x?>" value="<?php echo $row['confirmation_code'];?>" 
				onchange="javascript:  rental.UpdateCarRental('confirmation_code',this.value,'<?php echo $row['rental_id']; ?>',{ preloader: 'prl'}); 
				document.getElementById('editconfirmation_code<?php echo $x;?>').style.display='none'; 
				document.getElementById('save9<?php echo $x;?>').style.display='none';
				if(document.getElementById('carconfirmation_code<?php echo $x?>').value=='' || document.getElementById('confirmation_code<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('confirmation_code<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('confirmation_code<?php echo $x;?>').innerHTML=document.getElementById('carconfirmation_code<?php echo $x?>').value;}
				 document.getElementById('confirmation_code<?php echo $x;?>').style.display='';  return false;" style="width:100px" />
				</span>
				</a>
				<span id="save9<?php echo $x;?>" style="display:none">
				<img src="images/save.png" onclick="javascript: 
				document.getElementById('editconfirmation_code<?php echo $x;?>').style.display='none'; 
				document.getElementById('save9<?php echo $x;?>').style.display='none'; 
				if(document.getElementById('carconfirmation_code<?php echo $x?>').value=='' || document.getElementById('confirmation_code<?php echo $x;?>').innerHTML=='--')
				{document.getElementById('confirmation_code<?php echo $x;?>').innerHTML='--';}
				else
				{document.getElementById('confirmation_code<?php echo $x;?>').innerHTML=document.getElementById('carconfirmation_code<?php echo $x?>').value;} 
				document.getElementById('confirmation_code<?php echo $x;?>').style.display='';  return false;" />
				</span>
				</td>
				
				<td><a href="#" onclick="javascript: rental.DelCarRental('<?php echo $row['rental_id']; ?>',{onUpdate: function(response,root){
					rental.showCarOnFly('<?php echo $this->event_id ?>',{ onUpdate: function(response,root){ 	
		   					 document.getElementById('carrentaltable').innerHTML=response;
		  				 	 document.getElementById('carrentaltable').style.display='';
							 }} );
					},preloader:'prl'});   return false;"><img src="images/trash.gif" /></a></td>
				</tr>
				<?php $x++;
			}
			?>
			</tbody>
			</table>
			</form>
			<?php 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function DelCarRental($rental_id)
	{
		ob_start();
		
		$sql_del = "delete from ".EM_RENTAL." where rental_id='".$rental_id."'";
		$this->db->query($sql_del,__FILE__,__LINE__);
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;

		
	}
	
}
?>