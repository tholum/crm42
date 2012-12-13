
<?php
require_once('class/class.fileserver.php');
require_once('class/class.tasks.php');
require_once('class/class.ups.php');
require_once('class/class.contacts.php');

require_once('class/class.CalcDate.php');
require_once('class/class.CapacityCalc.php');
require_once('class/class.capacity.php');

/******************************************************************************************************

			Class Description : WorkOrder
			
			Class Memeber Functions : function showCompleteContactDetails($contact_id)
									  function customerInformation($contact_id)		
									  function showOrderSizeQuantity()
									  function mensShort()
									  function mensSSJersey()
									  function orderDetails()
									  function noteDetails()
									  function showTask($gtid,$display='')
									  function returnTaskSelectionOptions($gtid='')
									  function taskStats()
									  function showDocuments()
			
			Describe Function of Each Memeber function :
									  
									  1. function showCompleteContactDetails($contact_id) 
									  2. function customerInformation($contact_id)
									  3. function showOrderSizeQuantity()
									  4. function mensShort()
									  5. function mensSSJersey()
									  6. function orderDetails()
									  7. function noteDetails()
									  8. function showTask($gtid,$display='')
									  9. function returnTaskSelectionOptions($gtid='')
									  10. function taskStats()
									  11. function showDocuments()

**********************************************************************************************************/

class WorkOrder extends Company_Global {

var $total_price = 0;
var $shipping_charge;
var $multilier;
var $order_id;
var $final_date =0;
var $fileserver;
var $base = 0;
var	$size_price = 0;
var $each_price = 0;

	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
                $this->fileserver = new fileserver;
	}
	function get_shipping_label($order_id){
            $array = $this->db->fetch_assoc($this->db->query("SELECT shipment_label FROM erp_order WHERE order_id = '$order_id'"));
            return $array["shipment_label"];
        }
        function get_hvr_label( $order_id ){
                        $array = $this->db->fetch_assoc($this->db->query("SELECT shipment_hvr FROM erp_order WHERE order_id = '$order_id'"));
            return $array["shipment_hvr"];
        }
	function validateOrderId($order_id=''){
	    $sql = "Select order_id FROM ".erp_ORDER." WHERE order_id = '$order_id'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($result) > 0){
		   return true;
		} else { return false; }
	}/////end of function
	
	function set_address_module_address( $order_id='' ){

		$result_order = $this->db->query("Select vendor_contact_id from ".erp_ORDER." where order_id = '$order_id'");
		$row_order = $this->db->fetch_assoc($result_order);
		
		$result_contact = $this->db->query("Select * from ".TBL_CONTACTS_ADDRESS." where contact_id = '$row_order[vendor_contact_id]'");
		while( $row_contact = $this->db->fetch_assoc($result_contact) ){
		      $street = str_replace( "'" , "&#039;" , $row_contact[street_address] );
			  $city = str_replace( "'" , "&#039;" , $row_contact[city] );
			  $state = str_replace( "'" , "&#039;" , $row_contact[state]);
			  
			  $result_module = $this->db->query("Select module_id from ".TBL_MODULE_ADDRESS." where module_id = '$order_id' and module_name='order' and street_address='$street' and city='$city' and state='$state' and zip='$row_contact[zip]'");
			  if($this->db->num_rows($result_module) == 0){
			  
			        $insert_sql_array = array();
					$insert_sql_array[module_id]		= $order_id;
					$insert_sql_array[module_name]		= 'order';
					$insert_sql_array[street_address]	= $row_contact[street_address];
					$insert_sql_array[city] 			= $row_contact[city];
					$insert_sql_array[state] 			= $row_contact[state];
					$insert_sql_array[zip] 				= $row_contact[zip];
					$insert_sql_array[country] 			= $row_contact[country];
					$insert_sql_array[type]				= $row_contact[type];
										
					$this->db->insert(TBL_MODULE_ADDRESS,$insert_sql_array);
			  
			  }
		}
	} //end of function
	
	function showCompleteContactDetails($order_id=''){
		ob_start();
		$this->set_address_module_address($order_id);
		
		$sql_order = "Select * from ".erp_ORDER." where order_id = '$order_id'";
		$result_order = $this->db->query($sql_order,__FILE__,__LINE__);
		$row_order=$this->db->fetch_array($result_order);
		$contact_id = $row_order['vendor_contact_id'];
		$_SESSION[contact_id] = $contact_id;	

		$sql = "Select * from ".TBL_CONTACT." where contact_id = '$contact_id'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		$row=$this->db->fetch_array($result);
		
		$sql_name = "Select * from ".TBL_INVE_CONTACTS." where company = '$contact_id' and type='People'";
		$result_name = $this->db->query($sql_name,__FILE__,__LINE__);
		$row_name=$this->db->fetch_array($result_name);
		
		$sql_n = "Select first_name,last_name from ".TBL_INVE_CONTACTS." where company = '$contact_id' and type='People' and contact_id='$row_order[contact_id]'";
		$result_n = $this->db->query($sql_n,__FILE__,__LINE__);
		$row_n=$this->db->fetch_array($result_n);
		
		$sql_phone = "Select * from ".CONTACT_PHONE." where contact_id = '$row_order[contact_id]'";
		$result_phone = $this->db->query($sql_phone,__FILE__,__LINE__);
		$row_phone=$this->db->fetch_array($result_phone);	

		$sql_email = "Select * from ".CONTACT_EMAIL." where contact_id = '$row_order[contact_id]'";
		$result_email = $this->db->query($sql_email,__FILE__,__LINE__);
		$row_email=$this->db->fetch_array($result_email);	

	    $sql_address = "Select * from ".TBL_MODULE_ADDRESS." where module_id = '$order_id'";
	    $result_address = $this->db->query($sql_address,__FILE__,__LINE__);			   
	    $row_address=$this->db->fetch_array($result_address); 
		?>
		 <div class="profile_box1" style="font-weight:bold; width:85%">
				<a style="color:#FF0000; font-size:15px" onClick="javascript: if(this.innerHTML=='+'){
		  														this.innerHTML = '-';
																document.getElementById('contact_details').style.display = 'block';
																}
																else {
																this.innerHTML = '+';
																document.getElementById('contact_details').style.display = 'none';
															} ">-</a>&nbsp;
				<a href="contact_profile.php?contact_id=<?php echo $contact_id; ?>"><?php echo $row['company_name'];?></a> </br>
				<?php
				if($this->db->num_rows($result_n) > 0){
				echo $row_n['first_name'].' '.$row_n['last_name'].' - '.substr($row_phone['number'], 0, 3).'-'.substr($row_phone['number'], 3, 3).'-'.substr($row_phone['number'], 6, 4); } ?></a>
        </div> 
		<div  id="contact_details"  class="contact_form" style="display:block; float:left">
			<table>
				<tr>
					<td>&nbsp;</td>
					<td>Contact</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
					   <span id="people_box_<?php echo $contact_id; ?>"> 
					    <?php 
						if($this->db->num_rows($result_name) == '0'){
							echo 'No Record Found';
						} 
						else if($this->db->num_rows($result_name) == '1'){
							echo $row_name['first_name'].' '. $row_name['last_name'];
							 $this->updateAddress($order_id,$row_name[contact_id],'contact_id');
						}
						else if($row_order[contact_id]!='0'){
								echo $this->returnLink($row_order[contact_id],$contact_id,'people_box_'.$contact_id,'people',$order_id); 
							}
							else{
								echo $this->returnLink('',$contact_id,'people_box_'.$contact_id,'people',$order_id); 
							}
							?>
					   </span>
					</td>
					<td>&nbsp;</td>
				</tr>	
				<tr><td colspan="2">&nbsp;</td></tr>				
				<tr>
					<td>&nbsp;</td>
					<td>Shipping Address</td>
					<td>&nbsp;</td>
				</tr>							
				<tr>
					<td>&nbsp;</td>
					<td>
					   <span id="address_box_<?php echo $contact_id; ?>"> 
					   	    <?php 
							if($this->db->num_rows($result_address) != 1){
								if($row_order[shipping_address]!=''){
									$sql_ad = "Select * from ".TBL_MODULE_ADDRESS." where address_id='$row_order[shipping_address]'";
									$result_ad = $this->db->query($sql_ad,__FILE__,__LINE__);			   
									$row_ad=$this->db->fetch_array($result_ad); 
									echo $this->returnLink($row_ad['street_address'],$contact_id,'address_box_'.$contact_id,'address',$order_id,$row_order[shipping_address]);
								}
								else{
								    echo $this->returnLink('',$contact_id,'address_box_'.$contact_id,'address',$order_id);
								}
							}
							else{?>
								District Office :<?php echo '<br>'. $row_address['street_address'].'<br>'.$row_address['city'].' '.$row_address['state'].' '.$row_address['zip'].'<br>'.$row_address['country'];
								echo $this->updateAddress($order_id,$row_address[address_id],'shipping_address');
							} ?>
					   </span>
					</td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>				
				<tr>
					<td>&nbsp;</td>
					<td>Billing Address</td>
					<td>&nbsp;</td>
				</tr>							
				<tr>
					<td>&nbsp;</td>
					<td>
					   <span id="address_box2_<?php echo $contact_id; ?>"> <?php	
					   if($this->db->num_rows($result_address) != 1){				   
							if($row_order[billing_address]!=''){
								$sql_ad = "Select * from ".TBL_MODULE_ADDRESS." where address_id='$row_order[billing_address]'";
								$result_ad = $this->db->query($sql_ad,__FILE__,__LINE__);			   
								$row_ad=$this->db->fetch_array($result_ad); 
								echo $this->returnLink($row_ad['street_address'],$contact_id,'address_box2_'.$contact_id,'billing_address',$order_id,$row_order[billing_address]);
							}
							else{
								$this->returnLink('',$contact_id,'address_box2_'.$contact_id,'billing_address',$order_id);
							}
						}
						else{?>
							District Office :<?php echo '<br>'.$row_address['street_address'].'<br>'.$row_address['city'].' '.$row_address['state'].' '.$row_address['zip'].'<br>'.$row_address['country'];
							$this->updateAddress($order_id,$row_address[address_id],'billing_address');
						} ?>
					   </span>
					</td>
				</tr>				
				<tr><td colspan="2">&nbsp;</td></tr>
			</table>				
			</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} // End of function showCompleteContactDetails()
	
	function updateAddress($order_id='',$address_id='',$type=''){
		$update_sql_array=array();
		$update_sql_array[$type] = $address_id;				
		$this->db->update(erp_order,$update_sql_array,'order_id',$order_id);
	}
	
	function customerInformation($order_id){
		$sql_order = "Select * from ".erp_ORDER." where order_id = '$order_id'";
		$result_order = $this->db->query($sql_order,__FILE__,__LINE__);
		$row_order=$this->db->fetch_array($result_order);
	
		$sql_csr = "Select * from ".TBL_USER." a, ".erp_CONTACTSCREEN_CUSTOM." b where a.user_id = b.csr and b.contact_id = '$row_order[vendor_contact_id]'";	
		$result_csr = $this->db->query($sql_csr,__FILE__,__LINE__);
		$row_csr=$this->db->fetch_array($result_csr);
		
		$sql_customer = "Select * from ".TBL_CONTACT." where contact_id = '$row_order[vendor_contact_id]'";
		$result_customer = $this->db->query($sql_customer,__FILE__,__LINE__);
		$row_customer=$this->db->fetch_array($result_customer);		
		?>
		<table align="center">
			<tr>
				<td align="right">CSR : </td>
				<td><?php echo $row_csr['first_name'].' '.$row_csr['last_name']; ?></td>
			</tr>
			<tr>
				<td align="right">Customer : </td>
				<td><?php echo $row_customer['company_name']; ?></td>
			</tr>	
			<tr>
				<td align="right">Order ID :</td>
				<td><?php echo $row_order['order_id']; ?></td>
			</tr>		
		</table>
		<?php		
	} // End of function customerInformation()
	
	function showOrderSizeQuantity($contact_id='',$order_id='',$check=''){
		ob_start();
		$sql = "SELECT * FROM ".erp_PRODUCT_ORDER ." WHERE order_id = '$order_id' and gp_id='0'" ;
		$result = $this->db->query($sql,__FILE__,__LINE__);
		?>
		<input type="hidden" name="cnt" id="cnt" value="1" />
		<?php //if( $check == '' ){?>
		<div class="profile_box1" style="font-weight:bold; width:94%">
			<a style="color:#FF0000; font-size:15px" onClick="javascript: if(this.innerHTML=='+'){
																	this.innerHTML = '-';
																	document.getElementById('quantity_details').style.display = 'block';
																	}
																	else {
																	this.innerHTML = '+';
																	document.getElementById('quantity_details').style.display = 'none';}">-</a>&nbsp;
																	<u>Order Size and Quantity</u>
		</div>
		<?php //} ?>
		<div  id="quantity_details"  class="contact_form" style="display:block; float:left; width:94%; overflow:scroll; height:450px;">
		<table id="display_order" class="event_form small_text" width="100%">
			<thead>
				<tr>
					<th width="30%">PRODUCT</th>
					<th width="20%">Unit Price</th>
					<th width="10%">QTY</th>
					<th width="10%">SIZE</th>
					<th width="15%">Price Each</th>
					<th width="15%">Total</th>
					<th width="10%">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php while($row=$this->db->fetch_array($result)){ ?>
			  	<tr style="border-bottom:solid 2px">
					<td width="30%">
					    <span id="product_name_box_<?php echo $order_id.''.$row['workorder_id']; ?>"> 
						    <?php echo $row['product_name']; ?>
					    </span>
					</td>
					<td width="20%">
					    <span id="unit_<?php echo $order_id.''.$row['workorder_id']; ?>"> 
						    <?php echo $this->returnLink($row['unit_price'],$row['product_id'],'unit_'.$order_id.''.$row['workorder_id'],'unit',$order_id,$contact_id,$row['workorder_id']); ?>
					    </span>
					</td>
					<td width="10%">
					    <span id="quantity_box_<?php echo $order_id.''.$row['workorder_id']; ?>"> 
						    <?php echo $this->returnLink($row['quantity'],$row['product_id'],'quantity_box_'.$order_id.''.$row['workorder_id'],'quantity',$order_id,$contact_id,$row['workorder_id']); ?>
					    </span>
					</td>
					<td width="30%">
					    <span id="size_box_<?php echo $order_id.''.$row['workorder_id']; ?>"> 
						    <?php echo $this->returnLink($row['size'],$row['product_id'],'size_box_'.$order_id.''.$row['workorder_id'],'size',$order_id,$contact_id,$row['workorder_id']); ?>
					    </span>					
					</td>
					<td>
					    <span id="priceunit_<?php echo $order_id.''.$row['workorder_id']; ?>"> 
						    <?php echo $this->returnLink($row['unit_price'],$row['product_id'],'priceunit_'.$order_id.''.$row['workorder_id'],'priceunit',$order_id,$contact_id,$row['workorder_id']); ?>
					    </span>
					</td>
					<td width="30%">
					    <span id="total_<?php echo $order_id.''.$row['workorder_id']; ?>"> 
						    <?php echo $this->returnLink($row['total'],$row['product_id'],'total_'.$order_id.''.$row['workorder_id'],'total',$order_id,$contact_id,$row['workorder_id']); ?>
					    </span>					
					</td>
					<td align="center"><a href="javascript:void(0);" 
					      onclick="javascript: if(confirm('Are you sure to delete this product')){
                                                   workorder.deletethis('<?php echo $row['product_id']; ?>',
						                                                '<?php echo $order_id; ?>',
																		'option_type',
																		'<?php echo $row['workorder_id']; ?>',
					                                   { preloader:'prl',
														 onUpdate: function(response,root){ 
													   	 workorder.showOrderSizeQuantity('<?php echo $contact_id; ?>',
														                                 '<?php echo $order_id; ?>',
																						 'a',
													   { preloader:'prl',
													     onUpdate: function(response,root){
														  document.getElementById('order_size_quantity').innerHTML=response; 
														  $('#display_order').tablesorter({widthFixed:true,
														  widgets:['zebra'],sortList:[[0,0]],headers: {5:{sorter: false}}	});}}); 
														
					                              }});
													 workorder.showproductname('<?php echo $order_id; ?>',
																			  {preloader:'prl',
																			  target:'dyanmic_div'});
												 }"> <img src="images/trash.gif" border="0" /> </a>
						<div id="size<?php echo $row[product_id]; ?>">
						  <a href="javascript:void(0);" 
						     onclick="javascript:workorder.addOrder('<?php echo $order_id ; ?>',
																    '<?php echo ($i+1); ?>',
																    '<?php echo $contact_id; ?>',
																    '<?php echo $row[product_id]; ?>',
																	'<?php echo $row[workorder_id]; ?>',
																 { preloader:'prl',
																   onUpdate:function(response,root){
																   document.getElementById('div_order').innerHTML=response;
																   document.getElementById('div_order').style.display='';
                                                                   autoProduct();
                                                               }});"><img src="images/add-notes.png" border="0" height="20px" width="20px" alt="Add" /></a>
					    </div>
					</td>
				</tr>
				<?php
				$this->order_id = $row['order_id'];
				}  // End of While loop
				$sql_total = "SELECT total FROM ".erp_SIZE." WHERE order_id = '$order_id'";
				$result_total = $this->db->query($sql_total,__FILE__,__LINE__);
				while( $row_total = $this->db->fetch_array($result_total) ){
				$this->total_price += $row_total[total]; } ?>
			</tbody>
		  </table>
		  <div id="ship_id">
		  <table class="table" width="100%">	
				<tr>
					<td width="150%" colspan="5">
						<a href="javascript:void(0);" 
						   onClick="javascript:workorder.addOrder('<?php echo $order_id ; ?>',
																  '<?php echo ($i+1); ?>',
																  '<?php echo $contact_id; ?>',
																  '',
																  '',
												      { preloader:'prl',
												      onUpdate:function(response,root){
												   document.getElementById('div_order').innerHTML=response;
												   document.getElementById('div_order').style.display='';
												   autoProduct();
												}});">add product</a>
					</td>
				</tr>
				<?php 
				$sql_order = "select * from ".erp_ORDER." where order_id = '$_REQUEST[order_id]'";
				$result_order = $this->db->query($sql_order,__FILE__,__LINE__);
				$row_order=$this->db->fetch_array($result_order);
				$this->shipping_charge = $row_order['shipping_charges'];
                $this->multilier = $row_order['multiplier']; ?>	
				<tr>
					<td width="90%">&nbsp;</td>
					<th width="20%">Shipping:</th>
					<td width="20%">
					    <div id="shipping">
						   <?php 
						   if($row_order['auto_shipping_charge'] == 0){ echo $this->edit_value($row_order['shipping_charges']); }
						   else { 
						   	  echo $this->returnShippingTextbox('',$row_order['shipping_charges'],$row_order['multiplier'],$contact_id,$order_id,$this->total_price);
						   }
						   ?>
						</div>
					</td>
					<td width="20%">
                       <select name="fedex" id="fedex"
					           onblur="javascript: var shipping_id= this.value;
							   					   var auto_shipping = 'unchecked';
												   if(document.getElementById('auto_shipping').checked == true){ auto_shipping='checked'; }
									if(confirm('Are you sure to change the shipping charge') && auto_shipping != 'checked'){ 
										workorder.updatefield(shipping_id,
														 '<?php echo $row_order['shipping_charges']; ?>',
														  1,
														 '',
														 '<?php echo $this->total_price; ?>',
														 'shipping',
																{ preloader:'prl',
																  onUpdate:function(response,root){
																 workorder.calculate('<?php echo $this->total_price; ?>',
																					 shipping_id,
																					 '<?php echo $row_order['multiplier']; ?>',
																					 'grand_total', 
															    { preloader:'prl',
																  onUpdate:function(response,root){
																
																document.getElementById('total_box').innerHTML=response;
																		}}); }});
									workorder.showOrderSizeQuantity('<?php echo $contact_id; ?>',
																	 '<?php echo $order_id; ?>',
																	 'a',
																	   { preloader:'prl',
																		 onUpdate: function(response,root){
																		  document.getElementById('order_size_quantity').innerHTML=response; 
																		  $('#display_order').tablesorter({widthFixed:true,
																		  widgets:['zebra'],sortList:[[0,0]],headers: {5:{sorter: false}}	});}}); }
									if(confirm('Are you sure to change the shipping charge') && auto_shipping == 'checked'){
										workorder.updateShippingCharge(this.value,
																	   '<?php echo $order_id; ?>',{});
									}
													else {
														    <?php if($row_order[shipping_charges] != ''){ ?>
																		this.value=<?php echo $row_order['shipping_charges']; ?>;
															<?php }//end of if
																  else { ?>
																		this.value='';
															<?php }//end of else ?>
														}">
					     					 <option value="">-select-</option>
                                              <?php $ups = new ups(); 
                                              $shipTypes = $ups->getShippmentTypes();
                                              foreach( $shipTypes as $type ){?>
											<option value="<?php echo $type["code"]; ?>" <?php if($type["code"]==$row_order['shipment_type']) echo 'selected="selected"'; ?>><?php echo $type["name"]; ?></option><?php } ?>

					  </select>
					 </td>
					 <td width="20%">
					  <input type="checkbox" name="auto_shipping" id="auto_shipping" <?php if($row_order['auto_shipping_charge'] == 1){ echo "checked='checked'";} ?>
					  			onchange="javascript:if(this.checked == true){ 
													  workorder.returnShippingTextbox(document.getElementById('fedex').value,
																					  '<?php echo $row_order['shipping_charges']; ?>',
																					  '<?php echo $row_order['multiplier']; ?>',
																					  '<?php echo $contact_id; ?>',
																					  '<?php echo $order_id; ?>',
																					  '<?php echo $this->total_price; ?>',
																					  {preloader:'prl',target:'shipping'});
													   }
													   else {
													    this.checked=false;
													   	workorder.updatefield(document.getElementById('fedex').value,
												                     '<?php echo $row_order['shipping_charges']; ?>',
																	  1,
																	 '',
																	 '<?php echo $this->total_price; ?>',
																	 'shipping',
																{ preloader:'prl',
																  onUpdate:function(response,root){
																 workorder.calculate('<?php echo $this->total_price; ?>',
																					 document.getElementById('fedex').value,
																					 '<?php echo $row_order['multiplier']; ?>',
																					 'grand_total', 
															    { preloader:'prl',
																  onUpdate:function(response,root){
																
																document.getElementById('total_box').innerHTML=response;
																		}}); }});
														workorder.showOrderSizeQuantity('<?php echo $contact_id; ?>',
																						'<?php echo $order_id; ?>',
																						'a',
																						{ preloader:'prl',
																						 onUpdate: function(response,root){
																		  document.getElementById('order_size_quantity').innerHTML=response; 
																		  $('#display_order').tablesorter({widthFixed:true,
																		  widgets:['zebra'],sortList:[[0,0]],headers: {5:{sorter: false}}});}});
													   }" />
                    </td>
				</tr>
				<tr>
					<td width="90%">&nbsp;</td>
					<th width="20%">Multiplier: </th>
					<td width="20%">
				  <?php if($row_order['multiplier']){ 
					      $multiplier_val=$row_order['multiplier'];
						  }
						  else {
						    $multiplier_val=1.0;
						  } ?>
					    <input style="width:40px" type="text" name="multiplier" id="multiplier" 
						     value="<?php echo $row_order['multiplier']; ?>"
						     readonly="true" onDblClick="this.readOnly=false" size="4"
							  onblur="javascript: var multiplier=this.value;
							  if( multiplier != '' ){
							  if(multiplier != <?php echo $row_order['multiplier'];?>){
							  if(confirm('Are you sure to change the multiplier from <?php echo $row_order['multiplier']; ?> to '+ multiplier))
												{ workorder.updatefield(0,
												                        '',
												                        this.value,
												                        '<?php echo $row_order['multiplier']; ?>',
																		'<?php echo $this->total_price; ?>',
																	   'multiplier',
											    { preloader:'prl',
												  onUpdate:function(response,root){
												           document.getElementById('hiden_multiply').value = multiplier;
												  workorder.calculate('<?php echo $this->total_price; ?>',
																	  '<?php echo $row_order['shipping_charges']; ?>',
																	   multiplier,
																	  'grand_total',

											    { preloader:'prl',
												  onUpdate:function(response,root){
														   document.getElementById('total_box').innerHTML=response; }}); }});
														   
												 workorder.showOrderSizeQuantity('<?php echo $contact_id; ?>',
														                                 '<?php echo $order_id; ?>',
																						 'a',
													   { preloader:'prl',
													     onUpdate: function(response,root){
														  document.getElementById('order_size_quantity').innerHTML=response; 
														  $('#display_order').tablesorter({widthFixed:true,
														  widgets:['zebra'],sortList:[[0,0]],headers: {5:{sorter: false}}	});}}); 
														  
														   this.readOnly=true;
										  } else {
													<?php if($row_order[multiplier] != ''){ ?>
															  this.value=<?php echo $row_order['multiplier']; ?>;
															  this.readOnly=true;
												    <?php }//end of if
												          else { ?>
													    	  this.value='';
													          this.readOnly=true;
												    <?php }//end of else ?>
												}}
												else{
												  this.readOnly=true;
												}

									} else { alert('Please Insert Some Value For Multiplier !!');
									         return false;
									       }"/>x
				    </td>
					<input type="hidden" id="hiden_multiply" value="<?php echo $this->multilier;?>" />
					<td width="20%">&nbsp;</td>
					<td width="20%">&nbsp;</td>
				</tr>
				<tr>
					<td width="90%">&nbsp;</td>
					<th width="20%">Total Tax:</th>
					<td width="20%"><div id="total_tax"><?php echo $this->calculate($this->total_price,$row_order['shipping_charges'],$row_order['multiplier'],'total_tax'); ?></div></td>
					<td width="20%">&nbsp;</td>	
					<td width="20%">&nbsp;</td>				
				</tr>
				<tr>
					<td width="90%">&nbsp;</td>
					<th width="20%">Grand Total:</th>
					<td width="20%"><div id="total_box"><?php echo $this->calculate($this->total_price,$row_order['shipping_charges'],$row_order['multiplier'],'grand_total'); ?></div></td>
					<td width="20%">&nbsp;</td>	
					<td width="20%">&nbsp;</td>				
				</tr>
				<?php $i++; ?>		
			</table>
			</div>
		</div>
		
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
		} // End of function showOrderSizeQuantity()
		
		function updateShippingCharge($shipment_type='',$order_id=''){
			$sql = "update erp_order set shipment_type='".$shipment_type."' where order_id='$order_id'";	
			$this->db->query($sql,__FILE__,__LINE__);			
		}
		
		function returnShippingTextbox($shipping_charges='',$old_shipping_charges='',$multiplier='',$contact_id='',$order_id='',$total_price=''){
			ob_start();
			?>
			$<input type="text" name="auto_shipping_val" id="auto_shipping_val" value="<?php echo $old_shipping_charges; ?>" style="width:40px" 
					onblur="javascript: var shipping_id= this.value;						  
							   if(confirm('Are you sure to change the shipping charge'))
											{ workorder.updatefield(document.getElementById('fedex').value,
												                     '',
																	  1,
																	 '',
																	 '<?php echo $total_price; ?>',
																	 'shipping',
																	 this.value,
																{ preloader:'prl',
																  onUpdate:function(response,root){
																 workorder.calculate('<?php echo $total_price; ?>',
																					 shipping_id,
																					 '<?php echo $multiplier; ?>',
																					 'grand_total', 
															    { preloader:'prl',
																  onUpdate:function(response,root){																
																document.getElementById('total_box').innerHTML=response;
																		}}); }});
									workorder.showOrderSizeQuantity('<?php echo $contact_id; ?>',
																	 '<?php echo $order_id; ?>',
																	 'a',
																	   { preloader:'prl',
																		 onUpdate: function(response,root){
																		  document.getElementById('order_size_quantity').innerHTML=response; 
																		  $('#display_order').tablesorter({widthFixed:true,
																		  widgets:['zebra'],sortList:[[0,0]],headers: {5:{sorter: false}}	});}}); }
													else {
															workorder.returnShippingTextbox(document.getElementById('fedex').value,
																							'<?php echo $old_shipping_charges; ?>',
																							'<?php echo $multiplier; ?>',
																							'<?php echo $contact_id; ?>',
																							'<?php echo $order_id; ?>',
																							'<?php echo $total_price; ?>',
																							{target:'shipping'});
														}"/>
			<?php	
			$html = ob_get_contents();
			ob_end_clean();
			return $html;					
		}
		
		function date_display($order_id=''){
		   ob_start(); 
		   $sql_order = "select * from ".erp_ORDER." where order_id = '$order_id'";
		   $result_order = $this->db->query($sql_order,__FILE__,__lINE__);
		   $row_order=$this->db->fetch_array($result_order);
		   $event_date = explode(" ",$row_order['event_date']);
		   $ship_date = explode(" ",$row_order[ship_date]);
		   ?>
		   <div>
			<table class="event_form small_text" width="100%">
				<tr>
				 <th>
					Event date :<input  type="text" name="start_date" id="start_date" value="<?php if($row_order['event_date']) echo date("Y-m-d",strtotime($row_order['event_date'])); ?>"/>
								<input  type="hidden" name="hid_start_date" id="hid_start_date" value="<?php if($row_order['event_date']) echo $row_order['event_date']; ?>"/>
						 <script type="text/javascript">	 
						 function start_cal(){
						 new Calendar({
						 inputField   	: "start_date",
						 dateFormat		: "%Y-%m-%d %I:%M %p",
						 trigger		: "start_date",
						 weekNumbers   	: true,
						 bottomBar		: true,
						 showTime       : 12,
						 onSelect		: function() {
												
												    this.hide();
													document.getElementById('start_date').value=this.selection.print("%Y-%m-%d");
													document.getElementById('hid_start_date').value=this.selection.print("%Y-%m-%d %I:%M %p");
													workorder.insert_date("event_date",
																		  document.getElementById('hid_start_date').value,
																		  {preloader:'prl'});
											}
									 });			
							}
							start_cal();
							</script>
						</th>
					<?php /*?><td>&nbsp;</td><?php */?>
				</tr>
				<tr>
					<th>Ship Date:<input type="text" name="end_date" id="end_date" value="<?php if($row_order['ship_date']) echo date("Y-m-d",strtotime($row_order['ship_date'])); ?>"/>
								  <input  type="hidden" name="hid_end_date" id="hid_end_date" value="<?php if($row_order['ship_date']) echo $row_order['ship_date']; ?>"/>	
					<script type="text/javascript">	
						 function end_cal(){
						 new Calendar({
						 inputField   	: "end_date",
						 dateFormat		: "%Y-%m-%d %I:%M %p",
						 trigger		: "end_date",
						 weekNumbers   	: true,
						 bottomBar		: true,
						 showTime       : 12,
						 onSelect		: function() {
						                        var day = Array;
												day = this.selection.getDates();
												var partA = day.toString().split(' ');
												if(partA[0] == 'Sun' || partA[0] == 'Sat')
												{
													alert('You cannot have weekends as your Ship date');
													return true;
													document.getElementById('end_date').value = '';
												} else {
												    this.hide();
													document.getElementById('end_date').value=this.selection.print("%Y-%m-%d");
													document.getElementById('hid_end_date').value=this.selection.print("%Y-%m-%d %I:%M %p");
													workorder.insert_date("ship_date",
																		  document.getElementById('hid_end_date').value,
																		  {preloader:'prl'});
													global_task.updateFlowChartTask('<?php echo $order_id; ?>',
																				    document.getElementById('hid_end_date').value,
																				    {preloader:'prl'});
													   }				
											}				
									  });
							  }
							  end_cal();
							  </script>	 
	
							</th>
					<?php /*?><td>&nbsp;</td><?php */?>
				</tr>
				<tr>
					<th><div id="shipping_weight_div" >Shipping weight&nbsp;<input type="text" id="shipping_weight" 
					     onBlur="$('#shipping_weight_div').css('background','#ffffff');workorder.update_order_weight( '<?php echo $row_order['order_id']; ?>' , this.value ,'shipping_weight', { preloader:'prl' , target:'shipping_weight_div'} )" value="<?php echo $row_order['weight']; ?>">
						 </div>
					</th>
				</tr>
				<tr>
					<th><div id="size_kit_div" >Size Kit weight&nbsp;<input type="text" id="size_kit_weight" 
					     onBlur="$('#size_kit_div').css('background','#ffffff');workorder.update_order_weight( '<?php echo $row_order['order_id']; ?>' , this.value ,'size_kit', { preloader:'prl' , target:'size_kit_div'} )" value="<?php echo $row_order['size_kit']; ?>">
						 </div>
					</th>
				</tr>
				<tr>
					<th><div id="po_number_div" >PO Number&nbsp;<input type="text" id="po_number" 
					     onBlur="$('#po_number_div').css('background','#ffffff');workorder.update_order_weight( '<?php echo $row_order['order_id']; ?>' , this.value ,'po_number', { preloader:'prl' , target:'po_number_div'} )" value="<?php echo $row_order['po_number']; ?>">
						 </div>
					</th>
				</tr>
				<tr>
					<th><div id="customer_notes_div" >Customer Notes&nbsp;<textarea id="customer_notes" 
					     onBlur="$('#customer_notes_div').css('background','#ffffff');workorder.update_order_weight( '<?php echo $row_order['order_id']; ?>' , this.value ,'customer_notes', { preloader:'prl' , target:'customer_notes_div'} )"><?php echo $row_order['customer_notes']; ?></textarea>
						 </div>
					</th>
				</tr>
			</table>		
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
		}
	function update_order_weight($order_id , $value,$choice ){
            ob_start();
			if($choice == 'shipping_weight'){
            $this->db->update('erp_order', array('weight' => $value ), 'order_id', $order_id);
            $row_order = $this->db->fetch_assoc($this->db->query("SELECT * FROM erp_order WHERE order_id = '$order_id'"));
            ?>
                Shipping weight&nbsp;<input type="text" id="shipping_weight" onBlur="$('#shipping_weight_div').css('backround' , '#ffffff');workorder.update_order_weight( '<?php echo $row_order['order_id']; ?>' , this.value ,'shipping_weight', { preloader:'prl' , target:'shipping_weight_div'} )" value="<?php echo $row_order['weight']; ?>">
			<? }
			
			if($choice == 'size_kit'){
			$this->db->update('erp_order', array('size_kit' => $value ), 'order_id', $order_id);
			$row_order = $this->db->fetch_assoc($this->db->query("SELECT * FROM erp_order WHERE order_id = '$order_id'"));
			?>
				Size Kit weight&nbsp;<input type="text" id="size_kit_weight" onBlur="$('#size_kit_div').css('backround' , '#ffffff');workorder.update_order_weight( '<?php echo $row_order['order_id']; ?>' , this.value ,'size_kit', { preloader:'prl' , target:'size_kit_div'} )" value="<?php echo $row_order['size_kit']; ?>">
			<? 
			}
			if($choice == 'po_number'){
            $this->db->update('erp_order', array('po_number' => $value ), 'order_id', $order_id);
            $row_order = $this->db->fetch_assoc($this->db->query("SELECT * FROM erp_order WHERE order_id = '$order_id'"));
            ?>
                PO Number&nbsp;<input type="text" id="po_number" onBlur="$('#po_number_div').css('background','#ffffff');workorder.update_order_weight( '<?php echo $row_order['order_id']; ?>' , this.value ,'po_number', { preloader:'prl' , target:'po_number_div'} )" value="<?php echo $row_order['po_number']; ?>">
			<? }
			
			if($choice == 'customer_notes'){
			$this->db->update('erp_order', array('customer_notes' => $value ), 'order_id', $order_id);
			$row_order = $this->db->fetch_assoc($this->db->query("SELECT * FROM erp_order WHERE order_id = '$order_id'"));
			?>
				Customer Notes&nbsp;<textarea id="customer_notes" onBlur="$('#customer_notes_div').css('background','#ffffff');workorder.update_order_weight( '<?php echo $row_order['order_id']; ?>' , this.value ,'customer_notes', { preloader:'prl' , target:'customer_notes_div'} )"><?php echo $row_order['customer_notes']; ?></textarea>
				
			<? 
			}
        $html = ob_get_contents();
		ob_end_clean();
		return $html;
            }
	function insert_date($field_name,$field_value){
		 $update_sql_array = array();				
		 $update_sql_array[$field_name] = $field_value;
		 $this->db->update(erp_ORDER,$update_sql_array,'order_id',$_REQUEST[order_id]);
	}// end of Function insert_date
	
	function GetVendorJson($pattern=''){
		ob_start();
		$contact_json = "";
		$sql = "Select * FROM ".erp_PRODUCT." WHERE product_name LIKE '%$pattern%' AND product_status = 'Active' and group_product_id = '0'";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		while($row = $this->db->fetch_array($record)){
		$cmp = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $row[product_name]);
		$contact_json .='{"caption":"'.$cmp.'","value":"'.$row[product_id].'"},';
		}
		$contact_json = '['.substr($contact_json,0,strlen($contact_json)-1).']';
		return $contact_json;
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} ///////end of function GetVendorJson
	
	function addOrder( $order_id='', $i='', $contact_id='', $product_id='', $workorder_id = '' ){
	    ob_start(); ?>
			<div class="prl">&nbsp;</div>
				<div id="lightbox">
					<div style="background-color:#ADC2EB; max-width:540px; min-width:450px;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle">Add Product</div>
					<div id="TB_closeAjaxWindow">
					<a href="javascript:void(0)" onClick="javascript: document.getElementById('div_order').style.display='none';">
					<img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div class="white_content" style="max-width:450px; min-width:450px;"> 
				<div style="padding:20px;" class="">			
				<form method="post" enctype="multipart/form-data">
					<table class="table" id="tbl_<?php echo $i; ?>" width="100%">
						<tr>
							<td width="40%">Product Name : </td>
							<td width="60%">
							<?php if( $product_id == '' ){ ?>
								<select name="add_product" id="add_product" 
									onchange="javascript: 
											  document.getElementById('product_id').value='';
											  for(i=0; i<document.getElementById('add_product').length; i++){ 
											  if(document.getElementById('add_product')[i].selected==true){
											  document.getElementById('product_id').value += 
														document.getElementById('add_product')[i].value+',';
											  }
											}
										 document.getElementById('product_id').value = 											                                         document.getElementById('product_id').value.substr(0,document.getElementById('product_id').value.length-1);
											if(this.value == '') {
											  alert('Please Select a Product to Continue');
											  return false;
										   } 
										    workorder.product_details('local',
																		document.getElementById('product_id').value,
																		'',
																		'',
																		'',
																		'',
																		'',
																		'<?php echo $order_id; ?>',
																		'<?php echo $contact_id; ?>',
														   { preloader: 'prl',
															   onUpdate: function(response,root){
															    document.getElementById('new').innerHTML=response;}});">
								</select>
								<input type="hidden" name="product_id" id="product_id" />
							<?php } 
							else {
								echo $this->getName($product_id,'product_name');
							}?>
							</td>
						</tr>
						<tr>
							<td width="40%">Quantity :</td>
							<td width="60%">
								<input type="text" name="quantity" id="quantity" style="width:208px;"
									onchange="javascript:
									             if('<?php echo $product_id !='' ?>'){
															workorder.product_details('local',
																						'<?php echo $product_id;?>',
																						'',
																						'',
																						'',
																						'',
																						this.value,
																						'<?php echo $order_id; ?>',
																						'<?php echo $contact_id; ?>',
																						'<?php echo $product_id;?>',
																						'',
																						'<?php echo $workorder_id;?>',
																			{ preloader: 'prl', onUpdate: function(response,root){
																			document.getElementById('new').innerHTML=response;}});
															 } else {
															    workorder.product_details('local',
												 							document.getElementById('product_id').value,
																			'',
																			'',
																			'',
																			'',
																			this.value,
																			'<?php echo $order_id; ?>',
																			'<?php echo $contact_id; ?>',
																			'',
																			'',
																			'',
																			{ preloader: 'prl', onUpdate: function(response,root){
																			document.getElementById('new').innerHTML=response;}});
																}"/>
							</td>
						</tr>
						<tr>
							<td colspan="2" width="100%"><div id="show_prod_details_<?php echo $i; ?>"></div></td>
						</tr>
					</table>
				</form>
				<div id="new">
				   <?php echo $this->product_details('local',$product_id,'','','','','',$order_id,$contact_id,$product_id,'',$workorder_id); ?>
				</div>
				</div></div></div>			
		<script>document.getElementById('cnt').value = <?php echo ($i+1);?>;
		</script>
		<?php
		 $html = ob_get_contents();
		 ob_end_clean();
		 return $html;			
	}
	
	function check_product_existence($product_id='',$order_id='',$choice=''){
		ob_start();
		switch ($choice){
		  case 'product':
			$sql = "Select * from ".erp_PRODUCT_ORDER." where product_id = '$product_id' and order_id = '$order_id'";
			$result=$this->db->query($sql) or die( $this->db->error());
			$exist=$this->db->num_rows($result);
			if($exist>0){
			return 'c';
			}
			break;
		  case 'size':
		     $size1 = explode('_',$order_id);
			 $size = strtolower("$size1[1]");
		     $sql = "Select * from ".erp_PRODUCT." where product_id ='$product_id' and size_$size = '0'";
			 $result=$this->db->query($sql) or die( $this->db->error());
             
			 if( $this->db->num_rows($result) > 0 ){
			 return 'c';
			 }
		    break;
		 }
	    $html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
    function product_details( $runat,$product_id='',$product_name='',$unit_price='',$size='',$total='',$quantity='',$order_id='',$contact_id='',$product='', $revise_quantity='', $workorder_id = '' ){
	    ob_start();
		switch($runat){
			case 'local':
			    $sql = "Select * from ".erp_PRODUCT." where product_id = '$product_id'";
				$result=$this->db->query($sql,_FILE_,_LINE_);
				$row=$this->db->fetch_array($result);
				?>
				<table class="table" width="100%">
					<tr>
						<td width="34%">Base Price : </td>
						<td width="66%">
						    <input type="text" id="base_price" style="border:hidden" />
						 </td>
					</tr>
					<tr>
						<td width="39%">Size :</td>
						<td width="61%">
						<?php $sql1="select * from ".erp_PRODUCT." where product_id = '$product_id'";
							  $result1=$this->db->query($sql1,__FILE__,__LINE__);
							  $size_value=array();
							  $i=0;
							  $total_qty = 0;
							  if( $workorder_id != '' ){
								  $sql_size="select size, quantity from ".erp_SIZE." where order_id = '$order_id' and product_id = '$workorder_id'";
								  $result_size=$this->db->query($sql_size,__FILE__,__LINE__);
								  while( $row_size=$this->db->fetch_array($result_size) ) 
									{
										$total_qty += $row_size[quantity];
										$size=explode('_',$row_size['size']);
										$sizevalue[$i] = $size[1];
										$i++;
									}
							  }?>
								<input type="hidden" id="total_qty" name="total_qty" value="<?php echo $total_qty + $quantity; ?>" />
								  <select name="sel_size" id="sel_size" style="width: 210px;"
										  onchange="javascript:
										  				if(document.getElementById('quantity').value == '')
														{
															alert('please enter quantity first');
															return false;
														}
														 var size=this.value;
															if(size==''){
																 alert('Please Select Any Size First.');}
															else{
																 workorder.showprice(this.value,
																					document.getElementById('total_qty').value,
																					'<?php echo $row['product_id']; ?>',
																	{onUpdate:function(response,root){
																	 document.getElementById('base_price').value=response;
																	}});
																}">
									<option value="">--select--</option>
									<?php
									while($row1=$this->db->fetch_array($result1)){ ?> 
									<?php 
									$c=0;
									for($j=0;$j<$i;$j++){
										if($sizevalue[$j] == 'XS') $c++;
									}
									
									 if(( $c == 0 or $workorder_id == '') and ($row1['size_xs'] >= 0 )){ ?>
									<option  value="<?php  echo $row1['size_xs'].'_XS';?>">XS</option><?php } ?>
									<?php $c=0;
									for($j=0;$j<$i;$j++){
										if($sizevalue[$j] == 'S') $c++;
									}
									
									 if(( $c == 0 or $workorder_id == '') and ($row1['size_s'] >= 0 )){ ?>
									<option  value="<?php  echo $row1['size_s'].'_S';?>">S</option><?php } ?>
									<?php $c=0;
									for($j=0;$j<$i;$j++){
										if($sizevalue[$j] == 'M') $c++;
									}
									
									 if(( $c == 0 or $workorder_id == '') and ($row1['size_m'] >= 0 )){ ?>
									<option  value="<?php  echo $row1['size_m'].'_M';?>">M</option><?php } ?>
									<?php $c=0;
									 for($j=0;$j<$i;$j++){
										if($sizevalue[$j] == 'L') $c++;
									}
									
									 if(( $c == 0 or $workorder_id == '') and ($row1['size_l'] >= 0 )){ ?>
									<option  value="<?php  echo $row1['size_l'].'_L';?>">L</option><?php } ?>
									<?php $c=0;
									for($j=0;$j<$i;$j++){
										if($sizevalue[$j] == 'XL') $c++;
									}
									
									 if( ( $c == 0 or $workorder_id == '') and ($row1['size_xl'] >= 0) ){ ?>
									<option  value="<?php  echo $row1['size_xl'].'_XL';?>">XL</option><?php } ?>
									<?php $c=0;
									for($j=0;$j<$i;$j++){
										if($sizevalue[$j] == '2X') $c++;
									}
									
									 if(( $c == 0 or $workorder_id == '') and ($row1['size_2x'] >= 0 )){ ?>
									<option  value="<?php  echo $row1['size_2x'].'_2X';?>">2X</option><?php  }?>
									<?php $c=0;
									for($j=0;$j<$i;$j++){
										if($sizevalue[$j] == '3X') $c++;
									}
									
									 if(( $c == 0 or $workorder_id == '') and ($row1['size_3x'] >= 0 )){ ?>
									<option  value="<?php  echo $row1['size_3x'].'_3X';?>">3X</option><?php  }?>
									<?php $c=0;
									for($j=0;$j<$i;$j++){
										if($sizevalue[$j] == '4X') $c++;
									}
									
									 if(( $c == 0 or $workorder_id == '') and ($row1['size_4x'] >= 0 )){ ?>
									<option  value="<?php  echo $row1['size_4x'].'_4X';?>">4X</option><?php } ?>
									<?php }?>
									</select>
						 </td>
					</tr>
					<tr>
						<td width="34%">Total : </td>
						<td width="66%">
							<div id="total">$0</div>
						</td>
					</tr>
					<tr>	
						<td colspan="2" width="100%">
							<input type="button" value="add" style="width:60px"
									onclick="javascript: var qty = document.getElementById('base_price').value;
									                     var quantity = document.getElementById('total_qty').value;
														
														   workorder.showprice('',
																				quantity,
																				'<?php echo $product_id; ?>',
																				1,
																				{onUpdate:function(response,root){
																				var base = response;
															 
														 if(qty == ''){
														 	alert('Please Enter quantity');
															return false;
														     }
														 else{
														 var tot = (qty * quantity);
														 var total = tot.toFixed(2);
														 document.getElementById('total').innerHTML='$'+total;
														 
														 workorder.product_details('server',
														 						   '<?php echo $product_id; ?>',
																				   '<?php echo $row['product_name']; ?>',
																				   base,
																				   document.getElementById('sel_size').value,
																				   total,
																				   '<?php echo $quantity; ?>',
																				   '<?php echo $order_id; ?>',
                                                                                   '<?php echo $contact_id; ?>',
																				   '<?php echo $product; ?>',
																				   quantity,
																				   '<?php echo $workorder_id; ?>',
														  { preloader:'prl',
													        onUpdate: function(response,root){ 
													   	    workorder.showOrderSizeQuantity('<?php echo $contact_id; ?>',
                                                                                            '<?php echo $order_id; ?>',
                                                          { onUpdate: function(response,root){
															document.getElementById('order_size_quantity').innerHTML=response;  
															$('#display_order').tablesorter({widthFixed:true,
															 widgets:['zebra'],sortList:[[0,0]],headers:{5:{sorter: false}}}); }}); }}); 
														 workorder.showproductname('<?php echo $order_id; ?>',
															 						   
																					 { onUpdate:function(response,root){
																		document.getElementById('dyanmic_div').innerHTML=response;                                                                        }}); } }});  
															 " />
						</td>
					</tr>
				<?php 
				break;
			
			case 'server':
			    $size_array = explode("_",$size);
				if( $product == '' ){
				
						$insert_sql_array = array();				
						$insert_sql_array[product_id] = $product_id;
						$insert_sql_array[product_name] = $product_name;
						$insert_sql_array[order_id] = $order_id;
						$insert_sql_array[status] = "In Progress";
					
						$this->db->insert(erp_PRODUCT_ORDER,$insert_sql_array);
						$last_product_id = $this->db->last_insert_id();
						
						$sql="select * from ".erp_PRODUCT." where group_product_id = '$product_id'";
						$result=$this->db->query($sql,__FILE__,__LINE__);
						if( $this->db->num_rows($result) > 0 ){
							while( $row=$this->db->fetch_array($result) ){
							    $sub_product_id = $row[product_id];
				
								$insert_sql_array = array();				
								$insert_sql_array[product_id] = $sub_product_id;
								$insert_sql_array[gp_id] = $last_product_id;
								$insert_sql_array[product_name] = $row[product_name];
								$insert_sql_array[order_id] = $order_id;
								$insert_sql_array[status] = "In Progress";
							
								$this->db->insert(erp_PRODUCT_ORDER,$insert_sql_array);
								$sub_product_id = $this->db->last_insert_id();
								
								$insert_assign_array = array();
								$insert_assign_array[product_id] = $sub_product_id;
								$insert_assign_array[order_id] = $order_id;
								  
								$this->db->insert(erp_WORK_ORDER,$insert_assign_array);
							  }
						}
						$insert_array = array();				
						$insert_array[product_id] = $last_product_id;
						$insert_array[unit_price] = $unit_price;
						$insert_array[per_size_price] = $size_array[0];
						$insert_array[size] = $size;
						$insert_array[base_price] = $_SESSION[base];
						$insert_array[total] = $total;	
						$insert_array[quantity] = $quantity;
						$insert_array[order_id] = $order_id;
					
						$this->db->insert(erp_SIZE,$insert_array);
						
						$insert_assign_array = array();
						$insert_assign_array[product_id] = $last_product_id;
						$insert_assign_array[order_id] = $order_id;
						
						$this->db->insert(erp_WORK_ORDER,$insert_assign_array);
						
				} else {
						$insert_array = array();				
						$insert_array[product_id] = $workorder_id;
						$insert_array[unit_price] = $unit_price;
						$insert_array[per_size_price] = $size_array[0];
						$insert_array[size] = $size;
						$insert_array[base_price] = $_SESSION[base];
						$insert_array[total] = $total;	
						$insert_array[quantity] = $quantity;
						$insert_array[order_id] = $order_id;
					
						$this->db->insert(erp_SIZE,$insert_array);
						$last_id = $this->db->last_insert_id();
						
						$sql_base = "update ".erp_SIZE." set base_price = '$_SESSION[base]' where product_id = '$workorder_id' and order_id='$order_id'";
				        $this->db->query($sql_base,__FILE__,__LINE__);
						
						echo $sql_base;
						
						$sql = "select unit_price, size_alot_id from ".erp_SIZE." where product_id = '$workorder_id' and order_id = '$order_id'";
						$result = $this->db->query($sql,__FILE__,__LINE__);
						while( $row = $this->db->fetch_array($result) ){
						
							   $total = $revise_quantity * $row[unit_price];
								
							   $sql_total = "update ".erp_SIZE." set total = '$total' where size_alot_id = '$row[size_alot_id]'";
				               $this->db->query($sql_total,__FILE__,__LINE__);
							}
				} ?>
				<script>
					window.location = "order.php?order_id=<?php echo $order_id; ?>";
				</script>
				<?php	
				break;				
			}
			 $html = ob_get_contents();
			 ob_end_clean();
			 return $html;			
	}
	
	function showprice( $size='', $quantity='', $product_id='', $z='' ){
	        ob_start();
			$sql="select * from ".erp_PRODUCT." where product_id='$product_id'";
			$result=$this->db->query($sql,__FILE__,__LINE__);
			$row=$this->db->fetch_array($result);
			
			    if($quantity>=1 && $quantity<=12){
				  $base_price=$row['quantity_6_12'];
				 }
				if($quantity>=13 && $quantity<=24){
				   $base_price=$row['quantity_13_24'];
				 }
				if($quantity>=25 && $quantity<=49){
				   $base_price=$row['quantity_25_49'];
				 }
				if($quantity>=50 && $quantity<=74){
				   $base_price=$row['quantity_50_74'];
				 }
				if($quantity>=75){
				   $base_price=$row['quantity_75'];
				 }
				 $size_array=explode("_",$size);
				 
				 $_SESSION[base] = $base_price;
				 $_SESSION[size_price] = $size_array[0];
				 if( $z == '' ){
				   echo $base_price + $size_array[0];
				 } else {
				   echo $base_price;
				   }
				 
			 $html=ob_get_contents();
			 ob_end_clean();
			 return $html;
	  } /////end of function showprice
	
	function showproductname($order_id=''){
			ob_start();
			$sql="select * from ". erp_PRODUCT_ORDER . " where order_id='$order_id' ORDER BY product_name";
			$result=$this->db->query($sql,_FILE_,_LINE_);
            while($row=$this->db->fetch_array($result)){ ?>
				<div class="profile_box1" style="font-weight:bold;background-color:#CCCCCC;float:left; width:59%;">
					<a style="color:#FF0000; font-size:15px" 
						onclick="javascript: if(this.innerHTML=='-'){
													this.innerHTML = '+';
													document.getElementById('div_<?php echo $row[workorder_id]; ?>').style.display = 'none';
													document.getElementById('wo_task<?php echo $row[workorder_id]; ?>').style.display = 'none';
                                                    document.getElementById('wo_task<?php echo $row[workorder_id].'rework'; ?>').style.display = 'none';													}
													else {
													this.innerHTML = '-';
													document.getElementById('div_<?php echo $row[workorder_id]; ?>').style.display = 'block';
													document.getElementById('wo_task<?php echo $row[workorder_id]; ?>').style.display = 'block';
													document.getElementById('wo_task<?php echo $row[workorder_id].'rework'; ?>').style.display = 'block';
												} ">-</a>&nbsp;<?php echo $row[workorder_id].' '.$row[product_name]; ?>
				<a href="javascript:void(0);" style="margin-left:104px;"
				   onclick="javascript:workorder.GenerateRework('local',
				                                                '<?php echo $row[workorder_id]; ?>',
				   												'<?php echo $order_id; ?>',
																
				   												{preloader:'prl',
                                                                 onUpdate:function(response,root){                                                                 document.getElementById('div_order').innerHTML=response;
																 document.getElementById('div_order').style.display='';
				   											  }});" >Generate Rework</a>
				</div>
	
				<div class="profile_box1" style="font-weight:bold;background-color:#CCCCCC;float:right; width:34%;">
					Tasks : <?php echo $row[product_name];?>
				</div>
				
				<div id="div_<?php echo $row[workorder_id];?>">
					<div style="float:left">
					
						<div id="rework<?php echo $row[workorder_id];?>">
						  <?php
						   $sql_re = "SELECT * FROM ".erp_REWORK." WHERE order_id = '$order_id' and product_id = '$row[workorder_id]'";
						   $result_re = $this->db->query($sql_re,__FILE__,__LINE__);
						   $check_num = $this->db->num_rows($result_re);
						   if( $check_num > 0 ){
						       echo $this->ShowRework( $order_id, $row[workorder_id] ); } ?>
						</div>
						
						<?php echo $this->orderDetails( $order_id, $row[product_name], $row[product_id], $row[workorder_id] ); ?>
						
						<div id="note_div<?php echo $row[workorder_id];?>">
						  <?php echo $this->noteDetails('local',$order_id,$row[product_name],'','','',$row[workorder_id]); ?>
						</div>
                        <div><?php echo $this->fileserver->display_files($row['workorder_id'], "work order" , array( "header_text_style" => 'color: #5f0000;font-size: 14px;font-weight: bold;' , "header_color" => "#f3f3f3", "main_width" => "100%" , "nostyle" => true, "class" => "profile_box1" , "main_style_overide" => 'font-weight:bold;margin-left:16px;width:621px' ));?></div>
					</div>
					<div style="float:right; width:34%">
					<?php if( $check_num > 0 ){ 
						while($row_num = $this->db->fetch_array($result_re)){
						?>
					    <div class="form_main" id="wo_task<?php echo $row_num['rework_id'];?>" style="background-color:#CCCCCC;">
							<b>Rework : <?php echo $row_num['rework_id']; ?></b>
							<div  id="task_area<?php echo $row[workorder_id].'rework';?>" class="small_text" style="background-color:#CCCCCC;">
							<a href="javascript:void(0);" id="task_link<?php echo $row_num['rework_id'];?>" 
							   onclick="javascript:document.getElementById('task_form<?php echo $row_num['rework_id'];?>').style.display='';
										workorder.addTodo('REWORK_ORDER',
														  'order.php', 
														  'rework_id',
														  '',
														  '<?php echo $row_num['rework_id']; ?>',
														  '',
														  'rework',
														  {preloader: 'prl',
														  onUpdate:function(response,root){
														    document.getElementById('task_form<?php echo $row_num['rework_id'];?>').innerHTML = response;
														    showCalender();
														  }});">add Todo</a><br />
									<?php  $product_id = $_POST["hide_pro123"];
									 if($_REQUEST['save_REWORK_ORDER']=='Add this Task'){
								 		echo $this->addTodo('REWORK_ORDER','order.php','rework_id',$order_id,$row_num['rework_id'],'server');
								     }?>
								
								<div class="form_bg" style="display:none;" id="task_form<?php echo $row_num['rework_id'];?>"></div>																					  								<div id="tab<?php echo $row_num['rework_id']; ?>" style="background-color:#CCCCCC">
									<?php 
									$task1 = new Tasks();
									echo $task1->GetTaskForProjectProfile('','','','','','','',1,'REWORK_ORDER',$row_num['rework_id']);?>
								</div></div>
								<?php echo $this->FlowChartDiv("work order", $row["workorder_id"], $row_num['rework_id']);?>
								
							</div>
				  <?php }  } ?>
						
						<div class="form_main" id="wo_task<?php echo $row[workorder_id];?>" style="background-color:#CCCCCC;">
							<b>Work Order :</b>
							<div id="task_area<?php echo $row[workorder_id];?>" class="small_text" style="background-color:#CCCCCC;">
								
							<a href="javascript:void(0);" id="task_link<?php echo $row[workorder_id];?>" 
							   onclick="javascript:document.getElementById('task_form<?php echo $row[workorder_id];?>').style.display='';
										workorder.addTodo('WORK_ORDER',
														  'order.php', 
														  'work_order_id',
														  '',
														  '<?php echo $row['workorder_id']; ?>',
														  '',
														  {preloader: 'prl',
														  onUpdate:function(response,root){
														    document.getElementById('task_form<?php echo $row[workorder_id];?>').innerHTML = response;
														    showCalender();
														  }});">add Todo</a><br />
									<?php  $product_id = $_POST["hide_pro123"];
									
											if($_REQUEST['Save_WORK_ORDER']=='Add this Task'){
												echo $this->addTodo('WORK_ORDER','order.php','work_order_id',$_REQUEST[order_id],$row['workorder_id'],'server');}?>
								
								<div class="form_bg" style="display:none;" id="task_form<?php echo $row[workorder_id];?>"></div>																					  								<div id="tab<?php echo $row['workorder_id']; ?>" style="background-color:#CCCCCC">
									<?php 
									$task2 = new Tasks();
									echo $task2->GetTaskForProjectProfile('','','','','','','',1,'WORK_ORDER',$row['workorder_id']);?>
								</div></div>
								<?php echo $this->FlowChartDiv("work order", $row["workorder_id"]);?>
						</div>
					</div>
				</div>
				<div class="Clear"></div>
			<?php  }
			$html=ob_get_contents();
			ob_end_clean();
			return $html;
	} ////end of function showproductname
	
	function ShowRework( $order_id='', $product_id='' ){
	   ob_start(); ?>
	   <div class="profile_box1" style="font-weight:bold;margin-left:20px;width:617px;">
			<a style="color:#FF0000; font-size:15px" id="rework_link<?php echo $product_id;?>"
			   onclick="javascript: if(this.innerHTML=='+ Rework Details'){
									   this.innerHTML = '- Rework Details';
									   document.getElementById('rework_detail<?php echo $order_id.''.$product_id; ?>').style.display = 'block';
									 } else {
									     this.innerHTML = '+ Rework Details';
									     document.getElementById('rework_detail<?php echo $order_id.''.$product_id; ?>').style.display = 'none';
											} ">+ Rework Details</a>
           </div>
		   <div id="rework_detail<?php echo $order_id.''.$product_id; ?>" style="display:none;margin-left:44px;">
		   
		   <?php
			    $sql = "SELECT distinct  order_id,product_id,rework_id FROM ".erp_REWORK." WHERE order_id = '$order_id' and product_id = '$product_id'";
				$result = $this->db->query($sql,__FILE__,__LINE__);
				while( $row = $this->db->fetch_array($result) ){ ?>
			 <div id="add_item<?php echo $order_id.''.$product_id.''.$row[rework_id]; ?>">
		     <table id="display_order" class="event_form small_text" width="100%">
			   <tr>
			     <th>QTY</th>
				 <th>List Items Requested</th>
				 <th>Unique Marks</th>
				 <th>Defect Category</th>
				 <th>Describe Problem</th>
				 <th>Notes</th>
				 <th>&nbsp;</th>
			   </tr>
			   <?php 
			   $sql_re = "SELECT * FROM ".erp_REWORK." WHERE order_id = '$order_id' and product_id = '$product_id' and rework_id=$row[rework_id]";
			   $result_re = $this->db->query($sql_re,__FILE__,__LINE__); 
			   while($row_re = $this->db->fetch_array($result_re)){
			    
				$sql_category ="SELECT group_name FROM ".erp_USERGROUP." WHERE group_id = '$row_re[defect_category_id]'";
				$result_category = $this->db->query($sql_category,__FILE__,__LINE__);
				$row_category = $this->db->fetch_array($result_category);
				
			   $sql_fab = "SELECT * FROM ".erp_REWORK." WHERE order_id = '$order_id' and product_id = '$product_id' and rework_id=$row[rework_id]";
			   $result_fab = $this->db->query($sql_fab,__FILE__,__LINE__); 
			   $row_fab = $this->db->fetch_array($result_fab)?>
			   <tr>
			     <td><?php echo $row_re[qty]; ?></td>
				 <td>
				   <a href="javascript:void(0);" 
				 	onclick="javascript:workorder.GenerateRework('local',
				                                                '<?php echo $product_id; ?>',
				   												'<?php echo $order_id; ?>',
																'<?php echo $row_re[qty]; ?>',
																'<?php echo $row_re[unique_marks]; ?>',
																'<?php echo $row_category[group_name]; ?>',
																'<?php echo $row_re[describe_problem ]; ?>',
																'<?php echo $row_re[notes]; ?>',
																'<?php echo $row_fab[fabric_scrap]; ?>',
																'<?php echo $row_fab[printer_used];?>',
																'<?php echo $row_re[list_item_requested]; ?>',
																'<?php echo $row_re[rework_id];?>',
																'edit_item',
																'<?php echo $row_re[rework_item_id];?>',
				   												{preloader:'prl',
                                                                 onUpdate:function(response,root){                                                                 				                                                                 document.getElementById('div_order').innerHTML=response;
																 document.getElementById('div_order').style.display='';
				   											  }});"><?php echo $row_re[list_item_requested]; ?>
				   </a>
				 </td>
				 <td><?php echo $row_re[unique_marks]; ?></td>
				 <td><?php echo $row_category[group_name]; ?></td>
				 <td><?php echo $row_re[describe_problem ]; ?></td>
				 <td><?php echo $row_re[notes]; ?></td>
				 <td><a href="javascript:void(0);" 
					      onclick="javascript: if(confirm('Are you sure to delete this rework item')){
                                                   workorder.delete_rework('<?php echo $product_id; ?>',
						                                                   '<?php echo $order_id; ?>',
																		   '<?php echo $row_re[rework_item_id];?>',
					                                   { preloader:'prl',
														 onUpdate: function(response,root){ 
													   	 
											workorder.ShowRework('<?php echo $order_id; ?>',
																 '<?php echo $product_id; ?>',
																{preloader:'prl',
																onUpdate:function(response,root){
												  
												  document.getElementById('rework<?php echo $product_id;?>').innerHTML= response;
												  document.getElementById('rework_link<?php echo $product_id;?>').innerHTML='- Rework Details';
												  document.getElementById('rework_detail<?php echo $order_id.''.$product_id; ?>').style.display = 'block';}}); 
					                              }});}"><img src="images/trash.gif" border="0" /></a>
					</td>
			   </tr>
			   <?php } ?>
			  </table>
			 </div>
			 <div>
			 <table class="table">
			  <tr>
			   	   <th style="width:104px;">Fabric Scrap :</th>
				   <td>
				   <span id="fabric_scrap_<?php echo $row[rework_id].$product_id; ?>"> 
						<?php echo $this->returnLink($row_fab[fabric_scrap],$product_id,'fabric_scrap_'.$row[rework_id].$product_id,'fabric_edit',$order_id,$row[rework_id]); ?>
					</span> Inches
				  </td>
				   <th style="width:103px;">Printer :</th>
				   <td>
				   <span id="printer_<?php echo $row[rework_id].$product_id; ?>"> 
				   <?php echo $this->returnLink($row_fab[printer_used],$product_id,'printer_'.$row[rework_id].$product_id,'printer_edit',$order_id,$row[rework_id]);?>
				   </span>
				  </td>
				   <td><a href="javascript:void(0);" onClick="javascript:workorder.GenerateRework('local',
				   																				  '<?php echo $product_id; ?>',
				   												                                   '<?php echo $order_id; ?>',
																								  '',
																								  '',
																								  '',
																								  '',
																								  '',
																								  '<?php echo $row_fab[fabric_scrap]; ?>',
																								  '<?php echo $row_fab[printer_used];?>',
																								  '',
				  																				  '<?php echo  $row[rework_id];?>',
																								  'add_item',
				                                               										{preloader:'prl',
																									 onUpdate:function(response,root){                                                                 				                     document.getElementById('div_order').innerHTML=response;
																									 document.getElementById('div_order').style.display='';
																								  }});">Add</a></td>
			   </tr>
			 </table>
			 </div>
			 <?php } ?>
		   </div>
	<?php
	$html=ob_get_contents();
	ob_end_clean();
	return $html;
	}
	
	function delete_rework($product_id='',$order_id='',$rework_item_id=''){
	  ob_start();
	   $sql="DELETE FROM ".erp_REWORK." WHERE order_id = '$order_id' AND product_id = '$product_id' and rework_item_id='$rework_item_id'";
	   $this->db->query($sql,__FILE__,__LINE__);
	  $html=ob_get_contents();
	  ob_end_clean();
	  return $html;
	 }  ////////////end of function delete_rework
	  
	
	function GenerateRework( $runat='', $product_id='', $order_id='', $quantity='', $marks='', $department='', $description='', $notes='', $fabric_scrap='', $printer='', $list_item='', $rework_id='',$item='',$rework_item_id='' ){
	   ob_start();

	   switch($runat){
		   case 'local' : ?>
		   <div class="prl">&nbsp;</div>
				<div id="lightbox">
					<div style="background-color:#ADC2EB; max-width:540px; min-width:500px;" align="left" class="ajax_heading">
					<?php if($item==''){?>
					<div id="TB_ajaxWindowTitle">Add Rework</div><?php }?>
					<?php if($item=='add_item'){?>
					<div id="TB_ajaxWindowTitle">Add Rework Item</div><?php }?>
					<?php if($item=='edit_item'){?>
					<div id="TB_ajaxWindowTitle">Edit Rework Item</div><?php }?>
					<div id="TB_closeAjaxWindow">
					<a href="javascript:void(0)" onClick="javascript: document.getElementById('div_order').style.display='none';">
					<img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div class="white_content" style="max-width:540px; min-width:500px;"> 
				<div style="padding:20px;" class="form_main">
				<form metdod="post" enctype="multipart/form-data">
						<table class="" id="tbl_<?php echo $i; ?>">
						    <tr>
							<?php
							$sql_inve = "SELECT b.name FROM erp_work_order a, ".TBL_INVENTORY_DETAILS." b WHERE a.order_id = '$order_id' and a.product_id = '$product_id' and a.fabric = b.inventory_id";
				            $result_inve = $this->db->query($sql_inve,__FILE__,__LINE__);
							$row_inve = $this->db->fetch_array($result_inve);?>
							    <th>inventory name:</th>
								<td><?php echo $row_inve[name]; ?></td>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr><td colspan="4">&nbsp;</td></tr>
							<tr>
								<th>Quantity : </th>
								<td>
								  <input type="text" name="quantity_rework" id="quantity_rework"  value="<?php echo $quantity;?>" />
								</td>
								<th>Unique Marks :</th>
								<td>
								  <input type="text" name="unique_mark" id="unique_mark"  value="<?php echo $marks?>" />
								</td>
							</tr>
							<tr><td colspan="4">&nbsp;</td></tr>
							<tr>
								<th>Department :</th>
								<td>
								  <select name="department" id="department" style="width:auto;">
									<option value="">-Select-</option>
									<?php
									$sql ="SELECT group_id, group_name FROM ".erp_USERGROUP;
									$result = $this->db->query($sql,__FILE__,__LINE__);
									while( $row = $this->db->fetch_array($result) ){
									?>
									<option value="<?php echo $row[group_id]; ?>"  <?php if($department==$row[group_name]){echo 'selected="selected"'; }?> ><?php echo $row[group_name]; ?></option>
									<?php } ?>
								  </select>
								</td>
								<th>Description :</th>
								<td>
								  <input type="text" name="description" id="description"  value="<?php echo $description; ?>"/>
								</td>
							</tr>
							<tr><td colspan="4">&nbsp;</td></tr>
							<tr>
								<th>Notes :</th>
								<td>
								  <input type="text" name="rework_notes" id="rework_notes" value="<?php echo $notes;?>"/>
								</td>
								<?php if($fabric_scrap==''){?>
								<th>Fabric Scrap :</th>
								<td>
								  <input type="text" name="fabric_scrap" id="fabric_scrap" value="" />
								</td>
								<?php }
								else{?>
								<input type="hidden" name="fabric_scrap" id="fabric_scrap" value="<?php echo $fabric_scrap;?>" />
								<?php }?>
							</tr>
							<tr><td colspan="4">&nbsp;</td></tr>
							<tr>
							   <th>Printer :</th>
								<td>
								 <select style="width:100%;" name="printer" id="printer" >
									<option value="">-select-</option>
									<?php
									$sql_p = "select id, printer from ".erp_PRINTER_PAPER;
									$result_p = $this->db->query($sql_p,__FILE__,__LINE__);
									while($row_p = $this->db->fetch_array($result_p)){?>
									<option value="<?php echo $row_p[id]; ?>" <?php if($printer==$row_p[id]){echo 'selected="selected"'; }?>><?php echo $row_p[printer]; ?></option>
									<?php } ?>
                                  </select>
								</td>
								<th>List Item Requested :</th>
								<td>
								  <input type="text" name="list_item" id="list_item" value="<?php echo $list_item;?>"/>
								</td>
							</tr>
							<tr><td colspan="4">&nbsp;</td></tr>
							<?php
							if($item==''){
							 $sql="select * from ".erp_REWORK." where order_id='$order_id' and product_id='$product_id'";
							 $result = $this->db->query($sql,__FILE__,__LINE__);
									 if($this->db->num_rows($result)==0){
									  $rework_id=1;
									 }
									 else{
									 while($row=$this->db->fetch_array($result)){
									  $rework=$row[rework_id];
									}
									$rework_id=$rework+1;
									 }}
							 else{
							   $rework_id=$rework_id;
							 }?>
							<tr>	
								<td colspan="2">&nbsp;</td>
								<td colspan="2" align="left">
								<?php if($item==''){?>
								   <input type="button" value="Add"  style="size:auto"
								           onclick="workorder.GenerateRework('server',
																		    '<?php echo $product_id; ?>',
																		    '<?php echo $order_id; ?>',
																		    document.getElementById('quantity_rework').value,
																		    document.getElementById('unique_mark').value,
																			document.getElementById('department').value,
																		    document.getElementById('description').value,
																			document.getElementById('rework_notes').value,
																		    document.getElementById('fabric_scrap').value,
																			document.getElementById('printer').value,
																			document.getElementById('list_item').value,
																			'<?php echo $rework_id;?>',
																			'<?php echo $item;?>',
																			'<?php echo $rework_item_id;?>',
																		   {preloader:'prl',
																			onUpdate: function(response,root){
																			document.getElementById('div_order').innerHTML = response;
																			document.getElementById('div_order').style.display = 'none';
																		
														workorder.ShowRework('<?php echo $order_id; ?>',
																			 '<?php echo $product_id; ?>',
																			 {preloader:'prl',
																			  target:'rework<?php echo $product_id; ?>'});
															}});"/><?php }
															
										else{ ?>
										<input type="button" value="OK"  style="size:auto"
								           onclick="workorder.GenerateRework('server',
																		    '<?php echo $product_id; ?>',
																		    '<?php echo $order_id; ?>',
																		    document.getElementById('quantity_rework').value,
																		    document.getElementById('unique_mark').value,
																			document.getElementById('department').value,
																		    document.getElementById('description').value,
																			document.getElementById('rework_notes').value,
																		    document.getElementById('fabric_scrap').value,
																			document.getElementById('printer').value,
																			document.getElementById('list_item').value,
																			'<?php echo $rework_id;?>',
																			'<?php echo $item;?>',
																			'<?php echo $rework_item_id;?>',
																		    
												   {preloader:'prl',
													onUpdate: function(response,root){
													
													document.getElementById('add_item<?php echo $order_id.''.$product_id.''.$rework_id; ?>').innerHTML = response;
													document.getElementById('div_order').style.display = 'none';
																		
											workorder.ShowRework('<?php echo $order_id; ?>',
																 '<?php echo $product_id; ?>',
																{preloader:'prl',
																onUpdate:function(response,root){
												  
												  document.getElementById('rework<?php echo $product_id; ?>').innerHTML= response;
												  document.getElementById('rework_link<?php echo $product_id;?>').innerHTML='- Rework Details';
												  document.getElementById('rework_detail<?php echo $order_id.''.$product_id; ?>').style.display = 'block';}}); 
															}});"/>
									<?php	} ?>
								</td>
							</tr>
						</table>
					</form>
				
				</div></div></div>
	<?php  break;
	       case 'server' :
		   
		    $insert_sql_array = array();
			$insert_sql_array[rework_id] = $rework_id;
			$insert_sql_array[order_id] = $order_id;				
			$insert_sql_array[product_id] = $product_id;
			$insert_sql_array[qty] = $quantity;
			$insert_sql_array[list_item_requested] = $list_item;
			$insert_sql_array[unique_marks] = $marks;

			$insert_sql_array[defect_category_id] = $department;	
			$insert_sql_array[describe_problem] = $description;
			$insert_sql_array[notes] = $notes;
			$insert_sql_array[fabric_scrap] = $fabric_scrap;
			$insert_sql_array[printer_used] = $printer;
		
			if($rework_item_id!='' ){
				$this->db->update(erp_REWORK,$insert_sql_array,'rework_item_id',$rework_item_id);

		   } else {
				$this->db->insert(erp_REWORK,$insert_sql_array);
		   } ?>
		    <script>
			  window.location = "order.php?order_id=<?php echo $order_id; ?>";
			</script>
	    <?php
		   break;
		}
	$html=ob_get_contents();
	ob_end_clean();
	return $html;
	}
	
	function show_title($order_id,$pro_id='',$module){
	  ob_start();?>
		<table>
		<?php
		 if($module=='ORDER') {
			$sql="select a.title from tasks a,assign_task b where a.task_id=b.task_id and b.module='ORDER' and b.order_id=$order_id";
			$result=$this->db->query($sql,__FILE__,__LINE__);
			if($this->db->num_rows($result)>0){
			while($row=$this->db->fetch_array($result)){?>
			<tr>
			<td><?php echo $row['title'];?></td>
			</tr>
			<?php
			}}
		}
		 else{
			$sql="select a.title from tasks a,assign_task b where a.task_id=b.task_id and b.module='WORK_ORDER' and b.product_id= $pro_id and b.order_id= $order_id";
			$result=$this->db->query($sql,__FILE__,__LINE__);
			if($this->db->num_rows($result)>0){
			while($row=$this->db->fetch_array($result)){?>
			<tr>
			<td><?php echo $row['title'];?></td>
			</tr>
			<?php
			}}
		 }?>
		 </table>
	  <?php
	 $html=ob_get_contents();
	 ob_end_clean();
	 return $html;
	}
	
	function addTodo($module='',$profile_page='',$module_id='',$order_id='',$product_id='',$type='',$task_type = ''){
		ob_start();
			$tasks = new Tasks();
			$tasks -> SetUserID($_SESSION['user_id']);
			$tasks -> SetModuleID($_SESSION['contact_id']);
			if($type == 'server'){
				$tasks->AddTask('server',$module,$profile_page,$module_id,'',$module,$order_id,$product_id); }
			else{
				$tasks->AddTask('local',$module,$profile_page,$module_id,'',$module,$order_id,$product_id);}
			?>
			<a href="javascript:void(0);" onClick="document.getElementById('task_link<?php echo $product_id.$task_type;?>').style.display=''; 
												   document.getElementById('task_form<?php echo $product_id.$task_type;?>').style.display='none';
												   return false;">cancel</a>
		<?php 
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function orderDetails( $order_id='', $product_name='', $product_id='', $workorder_id='' ){
		ob_start(); ?>
		<div class="profile_box1" style="font-weight:bold;margin-left:16px;width:621px;">
			<a style="color:#FF0000; font-size:15px" onClick="javascript: if(this.innerHTML=='+ Order Details'){
																	this.innerHTML = '- Order Details';
																	document.getElementById('order_details_<?php echo $workorder_id; ?>').style.display = 'block';
																	}
																	else {
																	this.innerHTML = '+ Order Details';
																	document.getElementById('order_details_<?php echo $workorder_id; ?>').style.display = 'none';
																} ">+ Order Details</a>
           </div> 
           <div id="order_details_<?php echo $workorder_id; ?>" style="display:none;margin-left:35px;">
           <table class="table" width="100%">
             <tr>
              <td>
              <div style="float:left;">
              <?php
			     $m = 0;
			     /*$pad='';
				 $elastic='';
				 $fabric='';
				 $lining='';
				 $zipper='';*/
				 
				 $sql_t = "SELECT type_name FROM " . erp_ITEM_TYPE;
				 $result_t = $this->db->query($sql_t,_FILE_,_LINE_);
				 while($row_t = $this->db->fetch_array($result_t)){
						$a = strtolower($row_t[type_name]);
						$$a = '';
						$b = $a.'_groups';
						$$b = array();
				    //echo $a.'- '.$$a;
				}
				 /*$fabric_groups = array();
				 $lining_groups = array();
				 $pad_groups = array();
				 $elastic_groups = array();
				 $zipper_groups = array();*/
				 
				 $sql_pro="select a.inventory_id, a.name from ".erp_ASSIGN_INVENTORY." a, ".erp_PRODUCT_ORDER." b where a.product_id = b.product_id and order_id = '$order_id' and b.workorder_id = '$workorder_id' and a.group_inventory_id = '0'";
			     $result_pro = $this->db->query($sql_pro,_FILE_,_LINE_);
				 while($row_pro=$this->db->fetch_array($result_pro)){
					 $invent=$row_pro[inventory_id];
					 $str = array("'","&#039;");
					 $replace = "&#096;";
					 $invent_name = str_replace($str,$replace,$row_pro[name]);
					 
					 /*
					 NOTE:- "$invent_name = str_replace("," , "" , $invent_name);"  
					        This statement makes the name different from its actual name so their arise a conflict in matching records from erp_assign_inventory table.					 
					 */
					 
					 //$invent_name = str_replace("," , "" , $invent_name);
                                         
					 $sql_invent = "select type_id from " . TBL_INVENTORY_DETAILS ." where inventory_id='$invent'";
				   	 $result_invent = $this->db->query($sql_invent,_FILE_,_LINE_);
					 $row_invent = $this->db->fetch_array($result_invent);
						 $type_id=$row_invent[type_id];
					    
						 $sql_type = "select type_name from " . erp_ITEM_TYPE ." where type_id='$type_id'";
						 $result_type = $this->db->query($sql_type,_FILE_,_LINE_);
						 $row_type = $this->db->fetch_array($result_type);
							 $type_name=$row_type[type_name];
						  
							 switch($type_name){
							 
								case 'Fabric': $fabric.="~".$invent_name;
								               $m = 1;
												break;
								case 'Lining': $lining.="~".$invent_name;
								               $m = 1;
												break;
								case'Pad': $pad.="~".$invent_name;
								           $m = 1;
											break;
								case'Elastic': $elastic.="~".$invent_name;
								               $m = 1;
												break;
								case'Zipper': $zipper.="~".$invent_name;
								              $m = 1;
											  break;
											  
								case 'Label': $label.="~".$invent_name;
								               $m = 1;
												break;
								case 'Printing': $printing.="~".$invent_name;
								               $m = 1;
												break;
								case'Trims': $trims.="~".$invent_name;
								           $m = 1;
											break;
								case'Paper': $paper.="~".$invent_name;
								               $m = 1;
												break;
								case'Ink': $ink.="~".$invent_name;
								              $m = 1;
											  break;
								case'Misc': $misc.="~".$invent_name;
								              $m = 1;
											  break;
								case'Precut': $precut.="~".$invent_name;
								              $m = 1;
											  break;
							  }   /////end of switch
						//} //// end of 3rd while
					//}   //// end of 2nd while
               	 }  //// end of 1st while
				 
				$sql_group = "SELECT inventory_group_id, group_name, product_group FROM " .erp_GROUP_INVENTORY." WHERE group_id = '$product_id'";
				$result_group = $this->db->query($sql_group,_FILE_,_LINE_);
				if( $this->db->num_rows($result_group) > 0 ){
				   while( $row_group = $this->db->fetch_array($result_group) ){
				      
					 $sql_pro = "SELECT inventory_id, name FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id' and group_inventory_id = '$row_group[inventory_group_id]'";
			         $result_pro = $this->db->query($sql_pro,_FILE_,_LINE_);
					 if( $this->db->num_rows($result_pro) > 0 ){
					  while( $row_pro = $this->db->fetch_array($result_pro) ){
						     $str = array("'","&#039;");
							 $replace = "&#096;";
							 $group_inve = str_replace($str,$replace,$row_pro[name]);
							 
							 /*
							 NOTE:- "$invent_name = str_replace("," , "" , $invent_name);"  
									This statement makes the name different from its actual name so their arise a conflict in matching records from erp_assign_inventory table.					 
							 */
                             //$group_inve = str_replace("," , "" , $group_inve);
							 
							 $sql_type="SELECT type_name FROM " . erp_ITEM_TYPE ." WHERE type_id='$row_group[product_group]'";
							 $result_type= $this->db->query($sql_type,_FILE_,_LINE_);
							 $row_type=$this->db->fetch_array($result_type);
								 $type_name = $row_type[type_name];
								 
							    switch($type_name){
									case 'Fabric': $fabric_group .= "~".$group_inve;
									               $m = 1;
													break;
									case 'Lining': $lining_group .= "~".$group_inve;
									               $m = 1;
                                                   break;
									case 'Pad': $pad_group .= "~".$group_inve;
									            $m = 1;
												break;
									case 'Elastic': $elastic_group .= "~".$group_inve;
									                $m = 1;
													break;
									case 'Zipper': $zipper_group .= "~".$group_inve;
									               $m = 1;
												  break;
												  
									case 'Label': $label_group .= "~".$group_inve;
									               $m = 1;
													break;
									case 'Printing': $printing_group .= "~".$group_inve;
									               $m = 1;
                                                   break;
									case 'Trims': $trims_group .= "~".$group_inve;
									            $m = 1;
												break;
									case 'Paper': $paper_group .= "~".$group_inve;
									                $m = 1;
													break;
									case 'Ink': $ink_group .= "~".$group_inve;
									               $m = 1;
												  break;
									case'Misc': $misc_group .="~".$group_inve;
								              $m = 1;
											  break;
									case'Precut': $precut_group .="~".$group_inve;
								              $m = 1;
											  break;			  
								  }   /////end of switch
					     }
					   }
					   
					     /*$sql_t = "SELECT type_name FROM " .erp_ITEM_TYPE." WHERE type_id = '$row_group[product_group]'";
						 $result_t = $this->db->query($sql_t,_FILE_,_LINE_);
						 $row_t = $this->db->fetch_array($result_t);
								$ab = strtolower($row_t[type_name]).'_group';	
								$ba = 'group_'.strtolower($row_t[type_name]);
								$length = strtolower($row_t[type_name]).'_len';
							    echo '-'.$ab.'- '.$$ab;
							
					   if( $$ab != '' ){
						    $$ba .= $row_group[inventory_group_id].'~'.$row_group[group_name].''.$$ab;
							$$ab = '';
							$group_inve = str_replace("'","`",$$ba);
							$$b[] = $group_inve;
							$length  = count($$b);
							$$ba = '';
						
						}*/
					   
					   
						if( $fabric_group != '' ){
						    $group_fabric .= $row_group[inventory_group_id].'~'.$row_group[group_name].''.$fabric_group;
							$fabric_group = '';
							$group_inve = str_replace("'","`",$group_fabric);
							$fabric_groups[] = $group_inve;
							$fabric_len  = count($fabric_groups);
							$group_fabric = '';
						
						} else if( $lining_group != '' ){
						           $group_linning .= $row_group[inventory_group_id].'~'.$row_group[group_name].''.$lining_group;
								   $lining_group = '';
								   $group_line = str_replace("'","`",$group_linning);
								   $lining_groups[] = $group_line;
								   $lining_len  = count($lining_groups);
								   $group_linning = '';
						
						  } else if( $pad_group != '' ){
						             $group_pad .= $row_group[inventory_group_id].'~'.$row_group[group_name].''.$pad_group;
									 $pad_group = '';
									 $group_pad_p = str_replace("'","`",$group_pad);
									 $pad_groups[] = $group_pad_p;
									 $pad_len  = count($pad_groups);
									 $group_pad = '';
						  
						    } else if( $elastic_group != '' ){
							           $group_elastic .= $row_group[inventory_group_id].'~'.$row_group[group_name].''.$elastic_group;
									   $elastic_group = '';
									   $group_elas = str_replace("'","`",$group_elastic);
									   $elastic_groups[] = $group_elas;
									   $elastic_len  = count($elastic_groups);
									   $group_elastic = '';
							
							  } else if( $zipper_group != '' ){
							             $group_zipper .= $row_group[inventory_group_id].'~'.$row_group[group_name].''.$zipper_group;
										 $zipper_group = '';
										 $group_zip = str_replace("'","`",$group_zipper);
										 $zipper_groups[] = $group_zip;
										 $zipper_len  = count($zipper_groups);
										 $group_zipper = '';
							  
							  } 
							  
							  else if( $label_group != '' ){
							             $group_label .= $row_group[inventory_group_id].'~'.$row_group[group_name].''.$label_group;
										 $label_group = '';
										 $group_label = str_replace("'","`",$group_label);
										 $label_groups[] = $group_label;
										 $label_len  = count($label_groups);
										 $group_label = '';
							  
							  } else if( $printing_group != '' ){
							             $group_printing .= $row_group[inventory_group_id].'~'.$row_group[group_name].''.$printing_group;
										 $printing_group = '';
										 $group_printing = str_replace("'","`",$group_printing);
										 $printing_groups[] = $group_printing;
										 $printing_len  = count($printing_groups);
										 $group_printing = '';
							  
							  } else if( $trims_group != '' ){
							             $group_trims .= $row_group[inventory_group_id].'~'.$row_group[group_name].''.$trims_group;
										 $trims_group = '';
										 $group_trims = str_replace("'","`",$group_trims);
										 $trims_groups[] = $group_trims;
										 $trims_len  = count($trims_groups);
										 $group_trims = '';
							  
							  } else if( $paper_group != '' ){
							             $group_paper .= $row_group[inventory_group_id].'~'.$row_group[group_name].''.$paper_group;
										 $paper_group = '';
										 $group_paper = str_replace("'","`",$group_paper);
										 $paper_groups[] = $group_paper;
										 $paper_len  = count($paper_groups);
										 $group_paper = '';
							  
							  } else if( $ink_group != '' ){
							             $group_ink .= $row_group[inventory_group_id].'~'.$row_group[group_name].''.$ink_group;
										 $ink_group = '';
										 $group_ink = str_replace("'","`",$group_ink);
										 $ink_groups[] = $group_ink;
										 $ink_len  = count($ink_groups);
										 $group_ink = '';
							  
							  }else if( $misc_group != '' ){
							             $group_misc .= $row_group[inventory_group_id].'~'.$row_group[group_name].''.$misc_group;
										 $misc_group = '';
										 $group_misc = str_replace("'","`",$group_misc);
										 $misc_groups[] = $group_misc;
										 $misc_len  = count($misc_groups);
										 $group_misc = '';
							  
							  } else if( $precut_group != '' ){
							             $group_precut .= $row_group[inventory_group_id].'~'.$row_group[group_name].''.$precut_group;
										 $precut_group = '';
										 $group_precut = str_replace("'","`",$group_precut);
										 $precut_groups[] = $group_precut;
										 $precut_len  = count($precut_groups);
										 $group_precut = '';
							  
							  }
							  
				   }
				}
				$sql_check = "select * from ".erp_WORK_ORDER." where product_id = '$workorder_id' and order_id = '$order_id'";
				$result_check = $this->db->query($sql_check,__FILE__,__LINE__);
				$row_check = $this->db->fetch_array($result_check); ?>
				
				<table class="table" width="100%">
				<?php
				  $result_ty = $this->db->query("SELECT type_name FROM ".erp_ITEM_TYPE);
				  
				  while($row_ty = $this->db->fetch_assoc($result_ty)){
						$lower_name = strtolower($row_ty[type_name]);
						$lower_len = $lower_name.'_len';
						$option_type = $order_id.'.'.$workorder_id.''.$lower_name;
						$box = 'box_'.$lower_name.'_'.$workorder_id.''.$order_id;
						$type_grup = $lower_name.'_groups';
						
						//echo $lower_name.'-len '.$lower_len.'-tr '.$option_type.'-div '.$box.'-grup '.$type_grup.'-chek '.$row_check[$lower_name].'<br/>';
						//echo $$lower_name.'<br/>';
						if( $$lower_name != '' or $$lower_len > 0 ){ ?>
						
						
						<tr id="<?php echo $option_type; ?>">
						  <td><?php echo $row_ty[type_name]; ?> Options:</td>
						  <td>
						    <div id="<?php echo $box; ?>">
						    <?php 
							   $len_type = count(explode(",",$row_check[$lower_name]));
							   //echo 'length-'.$len_type;
							   if( $row_check[$lower_name] != '' and $len_type < 2 ){
							       
								   echo $this->againlink( '',$$lower_name,$lower_name,$product_id,$order_id,$box,$$type_grup,$workorder_id,$option_type );
							  }
							  else {
								   echo $this->itemDisplay( '',$$lower_name,$lower_name,$product_id,$order_id,$box,$$type_grup,'',$workorder_id,$option_type );
							       }
							   
							   ?>
							   
							  </div>
							</td>
						  </tr>
					<?php }
					  }
					?>
				
                <?php /*if( $fabric != '' or $fabric_len > 0 ){
				  $option_fab = $order_id.'.'.$workorder_id.'fabric';?>
                  <tr id="<?php echo $option_fab; ?>">
                    <td>Fabric Options:</td>
                    <td><div id="box_fabric_<?php echo $workorder_id.$order_id; ?>">
							<?php 
							   $len_fab = count(explode(",",$row_check['fabric']));
							   $box_fabric = "box_fabric_".$workorder_id.$order_id;
							   if( $row_check['fabric'] != '' and $len_fab < 2 ){
						           echo $this->againlink( '',$fabric,'fabric',$product_id,$order_id,$box_fabric,$fabric_groups,$workorder_id,$option_fab );
							  }
							  else {
								   echo $this->itemDisplay( '',$fabric,'fabric',$product_id,$order_id,$box_fabric,$fabric_groups,'',$workorder_id,$option_fab );
							       } ?>
                        </div>
                    </td>
                  </tr>
				  
				  
                  <?php } if( $zipper != '' or $zipper_len > 0 ){
				  $option_zip = $order_id.'.'.$workorder_id.'zipper';?>
                  <tr id="<?php echo $option_zip; ?>">
                    <td>Zipper Options:</td>
                    <td><div id="box_zipper_<?php echo $workorder_id.$order_id; ?>">
							<?php
							   $len_zip = count(explode(",",$row_check['zipper']));
						       $box_zipper = "box_zipper_".$workorder_id.$order_id;
							   if( $row_check['zipper'] != '' and $len_zip < 2 ){
								   echo $this->againlink( '',$zipper,'zipper',$product_id,$order_id,$box_zipper,$zipper_groups,$workorder_id,$option_zip ); 
								}
							  else {
								   echo $this->itemDisplay( '',$zipper,'zipper',$product_id,$order_id,$box_zipper,$zipper_groups,'',$workorder_id,$option_zip );
							       } ?>
                        </div>
                    </td>
                  </tr>

				  <?php } if( $pad != '' or $pad_len > 0 ){
				  $option_pad = $order_id.'.'.$workorder_id.'pad';?>
                  <tr id="<?php echo $option_pad; ?>">
                    <td>Pad Options :</td>
                     <td><div id="box_pad_<?php echo $workorder_id.$order_id; ?>">
					 <?php
					    $len_pad = count(explode(",",$row_check['pad']));
						$box_pad = "box_pad_".$workorder_id.$order_id;
						  if( $row_check['pad'] != '' and $len_pad < 2 ){
					 		  echo $this->againlink( '',$pad,'pad',$product_id,$order_id,$box_pad,$pad_groups,$workorder_id,$option_pad );
						}
						else { 
							  echo $this->itemDisplay( '',$pad,'pad',$product_id,$order_id,$box_pad,$pad_groups,'',$workorder_id,$option_pad );
						   } ?>
                         </div>
                     </td>
                   </tr>
                   <?php } if( $elastic != '' or $elastic_len > 0 ){
				   $option_elast = $order_id.'.'.$workorder_id.'elastic';?>
                   <tr id="<?php echo $option_elast; ?>">
                     <td>Elastic Options:</td>
                    <td><div id="box_elastic_<?php echo $workorder_id.$order_id; ?>">
							<?php
							 $len_elast = count(explode(",",$row_check['elastic']));
							 $box_elastic = "box_elastic_".$workorder_id.$order_id;
							  if( $row_check['elastic'] != '' and $len_elast < 2 ){ 
								  echo $this->againlink( '',$elastic,'elastic',$product_id,$order_id,$box_elastic,$elastic_groups,$workorder_id,$option_elast );
							  }
							  else {
								  echo $this->itemDisplay( '',$elastic,'elastic',$product_id,$order_id,$box_elastic,$elastic_groups,'',$workorder_id,$option_elast );
							       } ?>
                        </div>
                    </td>
                  </tr>
                  <?php } if( $lining != '' or $lining_len > 0 ){
				  $option_lin = $order_id.'.'.$workorder_id.'lining';?>
                  <tr id="<?php echo $option_lin; ?>">
                    <td>Lining Options:</td>
                    <td><div id="box_lining_<?php echo $workorder_id.$order_id; ?>">
							<?php 
							  $len_lin = count(explode(",",$row_check['lining']));
							  $box_lining = "box_lining_".$workorder_id.$order_id;
							  if( $row_check['lining'] != '' and $len_lin < 2 ){
								  echo $this->againlink( '',$lining,'lining',$product_id,$order_id,$box_lining,$lining_groups,$workorder_id,$option_lin );
							  }
							  else {
								  echo $this->itemDisplay( '',$lining,'lining',$product_id,$order_id,$box_lining,$lining_groups,'',$workorder_id,$option_lin );
							       } ?>
                        </div>
                     </td>
                   </tr>
				   <?php }*/ ?>
				  </table>
                  </div>
                  </td>
                  <td>
                  <table class="table" width="100%" <?php if( $m == 0 ){ ?>style="margin-left:350px;"<?php } ?>>
                                      <tr>
                     <td>Seam Option :</td>
                     <td>
					    <span id="seamoption<?php echo $workorder_id.''.$order_id; ?>">
						  <?php 
						   $tgt="seamoption".$workorder_id.$order_id; 
									 
						  if($row_check['seamoption'] != '')
								     { 
									echo $this->newlink($row_check['seamoption'],'Seam','',$workorder_id,$order_id,$tgt); 
									 }
								else { echo $this->displayOption('Seam',$workorder_id,$order_id,'seamoption',$tgt); } ?>
						</span>
					  </td>
                   </tr>
                   <tr>
                    <td>Variable Info :</td>
                    <td>
					    <span id="variableinfo<?php echo $workorder_id.''.$order_id; ?>">
					      <?php 
						   $tgt="variableinfo".$workorder_id.$order_id; 
						   if($row_check['variableinfo'] != ''){ 
								 echo $this->newlink($row_check['variableinfo'],'Variable Info','',$workorder_id,$order_id,$tgt); }
							else { echo $this->displayOption('Variable Info',$workorder_id,$order_id,'variableinfo',$tgt); } ?>
					    </span>
					  </td>
                   </tr>
                   <tr>
                    <td>Profile JV5 :</td>
                     <td>
					     <span id="profile_JV5<?php echo $workorder_id.''.$order_id; ?>">
					        <?php
							 $tgt="profile_JV5".$workorder_id.$order_id;
								  if($row_check['profile_JV5'] != ''){ 
								   	echo $this->newlink($row_check['profile_JV5'],'Profile JV5','',$workorder_id,$order_id,$tgt); }
								  else { echo $this->displayOption('Profile JV5',$workorder_id,$order_id,'profile_JV5',$tgt); } ?>
						 </span>
					  </td>
                   </tr>
                   <tr>
                    <td>Zipper Finish:</td> 
                        <td>
                                            <span id="binding<?php echo $workorder_id.''.$order_id; ?>">
                                                <?php
                                                        $tgt="binding".$workorder_id.$order_id;
                                                                if($row_check['binding'] != ''){
                                                                        echo $this->newlink($row_check['binding'],'Binding Options','',$workorder_id,$order_id,$tgt);  }
                                                                        else { echo $this->displayOption('Binding Options',$workorder_id,$order_id,'binding',$tgt);} ?>
                                                 </span>
                                            </td>
                   </tr>
                   <tr>
                    <td>Seam Crossing:</td>
                   
					  <td>
					    <span id="seamcrossing<?php echo $workorder_id.''.$order_id; ?>">
					       <?php 
						   $tgt="seamcrossing".$workorder_id.$order_id;
						   if($row_check['seamcrossing'] != ''){ 
									    echo $this->newlink($row_check['seamcrossing'],'Seam Crossing','',$workorder_id,$order_id,$tgt); }
						   else{ echo $this->displayOption('Seam Crossing',$workorder_id,$order_id,'seamcrossing',$tgt); } ?>
					    </span>
					  </td>
                   </tr>
                   <tr>
					   <td>Group ID :</td>
					   <?php
					   $sql_group="select group_id from ".erp_GROUP." where order_id='$order_id' and workorder_id = '$workorder_id'";
					   $result_group = $this->db->query($sql_group,__FILE__,__LINE__); 
					   $row_group = $this->db->fetch_array($result_group); ?>
					   <td>
					    <div id="group_<?php echo $workorder_id.'_'.$order_id; ?>">
						  <?php echo $row_group[group_id]; ?>
						</div>
					   </td>
                   </tr>
                 </table>
				</td>
                </tr>
               </table>
              </div>
			<?php
            $html=ob_get_contents();
            ob_end_clean();
            return $html;
	} // end of function orderDetails
	
	function againlink( $show='',$value='',$item,$product_id,$order_id,$box_fabric,$value_group=array(),$workorder_id='',$tr_id='' ){
	   ob_start();
	
		 if( $show == '' ){
			 $sql="select * from ".erp_WORK_ORDER." where product_id = '$workorder_id' and order_id = '$order_id'";
			 $result=$this->db->query($sql,__FILE__,__LINE__);
			 $row = $this->db->fetch_assoc($result);
			 
			 $inv_id=$row[$item];
			 /*if($item=='fabric')
			 { 
			 $inv_id=$row['fabric'];}
				else if($item=='pad')
				 {
				 $inv_id=$row['pad'];}
					else if($item=='zipper')
					{
					$inv_id=$row['zipper'];}
						else if($item=='elastic')
						{
						$inv_id=$row['elastic'];}
							else if($item=='lining')
							{
							$inv_id=$row['lining'];
							}*/
				$size1 = explode(",",$inv_id);	
				
				$sql_name="select name from ".TBL_INVENTORY_DETAILS." where inventory_id = '$size1[0]' ";
				$result_name= $this->db->query($sql_name,__FILE__,__LINE__);
				$row_name=$this->db->fetch_array($result_name);
					  $show_value=$row_name['name'];
			}
		$grp=count($value_group);
		$len = count(explode("~",$value));
		
		if(($len>2) || ($len==2 && $grp!='' )){?>
		    <a href="javascript:void(0);" 
								 onclick="javascript: var group_item = new Array();
													  <?php $i=0; foreach($value_group as $typ_group){ 
													  ?>group_item[<?php echo $i; ?>] = '<?php echo $typ_group;?>';
													  <?php $i++;
													  }?>
													  workorder.itemDisplay('<?php echo $show; ?>',
																			'<?php echo $value; ?>',
																			'<?php echo $item;?>',
																			'<?php echo $product_id; ?>',
																			'<?php echo $order_id;?>',
																			'<?php echo $box_fabric;?>',
																			group_item,
																			'',
																			'<?php echo $workorder_id; ?>',
																			'<?php echo $tr_id; ?>',
																			{target:'<?php echo $box_fabric;?>'});"> <?php echo $show_value;?> </a>
		<?php }
		else{
			echo $show_value;
		}
		$this->normalInventory( $order_id, $workorder_id, $item, $size1[0], 1 );
		$html=ob_get_contents();
		ob_end_clean();
	    return $html;
	} 
	
	function newlink($inventory='',$option='',$value='',$product_id='',$order_id='',$tgt='')
	{
		ob_start();
		if($value==''){
			if($option=='Binding Options'){
				$inv=$inventory;
				$type='binding';}
			
			if($option=='Seam'){
				$inv=$inventory;
				$type='seamoption';}
			
			if($option=='Variable Info'){
				$inv=$inventory;
				$type='variableinfo';}	
			
			if($option=='Profile JV5'){
				$inv=$inventory;
				$type='profile_JV5';}	
			
			if($option=='Seam Crossing'){
				$inv=$inventory;
				$type='seamcrossing';}	
			
			$sql_name="select name from ".erp_DROPDOWN_OPTION." where identifier = $inv and option_name='$option'";
			$result_name= $this->db->query($sql_name,__FILE__,__LINE__);
			while($row_name=$this->db->fetch_array($result_name)){
			$show_value=$row_name['name'];	
		}}
		
		if($value){
			$show_value=$value;
			
			if($option=='Binding Options'){
			$type='binding';}
			
			if($option=='Seam'){
			$type='seamoption';}
			
			if($option=='Variable Info'){
			$type='variableinfo';}
			
			if($option=='Profile JV5'){
			$type='profile_JV5';}
			
			if($option=='Seam Crossing'){
			$type='seamcrossing';}
		}?>
		 <a href="javascript:void(0);" 
							onclick="javascript: workorder.displayOption('<?php echo $option; ?>',
																		'<?php echo $product_id; ?>',
																		'<?php echo $order_id;?>',
																		'<?php echo $type;?>',
																		'<?php echo $tgt; ?>',
																		{target:'<?php echo $tgt; ?>'});"> <?php echo $show_value;?> </a>
		<?php
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}

	
	function inventory_name($inventory_id='',$options=''){
	   ob_start();
	      if($options != ''){
			$sql="SELECT name from ".erp_DROPDOWN_OPTION." WHERE option_name = '$options' and identifier = '$inventory_id'";
			$result = $this->db->query($sql,__FILE__,__LINE__);
			$row=$this->db->fetch_array($result);
			echo $row[name];
		} else {
	        $sql_invent = "SELECT name FROM ".TBL_INVENTORY_DETAILS." WHERE inventory_id = '$inventory_id'";
			$result_inven = $this->db->query($sql_invent,__FILE__,__LINE__);
			$row_inven = $this->db->fetch_array($result_inven);
			echo $row_inven[name]; 
		}
		$html=ob_get_contents();
        ob_end_clean();
        return $html;
	}  /////end of function inventory_name
	
	
	function displayOption($options='',$product_id='',$order_id='',$type='',$tgt=''){
	   ob_start();
			$sql_group = "SELECT identifier,name from ".erp_DROPDOWN_OPTION." WHERE option_name = '$options'";
			$result_group = $this->db->query($sql_group,__FILE__,__LINE__); ?>
				<select name="binding_option" id="binding_option"
				        onchange="javascript:workorder.update_inve(this.value,
																   '<?php echo $type; ?>',
																   '<?php echo $options; ?>',
																   '<?php echo $product_id; ?>',
																   '<?php echo $order_id; ?>',
																   '<?php echo $tgt; ?>',
																   {preloader:'prl',
																	onUpdate:function(response,root){
																	
																	workorder.newlink(document.getElementById('dropdown').value,
																					  '<?php echo $options; ?>',
																					  response,
																					  '<?php echo $product_id; ?>',
																   					  '<?php echo $order_id; ?>',
																					  '<?php echo $tgt; ?>',
																	{preloader:'prl',
																	target:'<?php echo $tgt; ?>'});
																	}});">
					<option value="">-select-</option>
					<?php
					while($row_option = $this->db->fetch_array($result_group)){?>
					<option value="<?php echo $row_option[identifier]; ?>" id="dropdown"><?php echo $row_option[name]; ?></option>
					<?php } ?>
				</select>
	        <?php
            $html=ob_get_contents();
            ob_end_clean();
            return $html;
	}
	
	function checkSizeDependant( $runat, $xs, $s, $m, $l, $xl, $ax, $bx, $cx ){
	   ob_start();
	   switch( $runat ){
		   case 'check' :
			   $aa = 'true';
			   $bb='false';
			   if( $xs != '0' && $s != '0' && $m != '0' && $l != '0' && $xl != '0' && $ax != '0' && $bx != '0' && $cx != '0' ){
				   return $aa;
				} else { return $bb; }
			 break;
		 }
	$html=ob_get_contents();
    ob_end_clean();
    return $html;
	}
	
	function SingleInventory( $show='', $inventory='', $item='', $product_id='', $order_id='', $div_id='', $type_item_group=array(), $workorder_id='', $tr_id='' ){
	   ob_start();
	    $sql_m = "SELECT assign_inventory FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' and product_id = '$workorder_id' and option_type = '$item'";
	    $result_m = $this->db->query($sql_m,__FILE__,__LINE__);
	    if( $this->db->num_rows($result_m) == 0 ){
						  
			$sql_id = "SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE name = '$inventory' and product_id = '$product_id' and group_inventory_id = '0'";
			$result_id = $this->db->query($sql_id,__FILE__,__LINE__);
			$row_id = $this->db->fetch_array($result_id);
			$p = '_inventory_usage';
			
			$size_dependant =  $this->checkSizeDependant('check',$row_id['xs'.$p],$row_id['s'.$p],$row_id['m'.$p],$row_id['l'.$p],$row_id['xl'.$p],$row_id['2x'.$p],$row_id['3x'.$p],$row_id['4x'.$p]);
			if( $size_dependant == 'true' ){
				echo $inventory;
////////////////////////////////////********FOR NORMAL INVENTORY*******///////////////////////////////////////
			  
				$sql_inve = "update ".erp_WORK_ORDER." set $item = '$row_id[inventory_id]',assign_id = '$row_id[assign_id]' where product_id = '$workorder_id' and order_id = '$order_id'";
				$result_inve = $this->db->query($sql_inve,__FILE__,__LINE__);
				
				echo $this->updateCapacity();
			
	   //****************************FOR PRICE********************************//
				
				$this->normalInventory( $order_id, $workorder_id, $item, $row_id[inventory_id] );
				
       //*********************************************************************//
	   
//////////////////////////////////////////**********************/////////////////////////////////////////////////
			} else {
////////////////////////////////////********FOR SIZE DEPENDANT INVENTORY*******///////////////////////////////////////

				
				echo $this->show_size( 'local',$product_id,$order_id,$row_id[inventory_id],$row_id[assign_id],$item,$div_id,'','','','',$workorder_id,'','',$tr_id );
				
				echo $this->updateCapacity();
///////////////////////////////////////////////**************************///////////////////////////////////////////
			}
		} else {
			
			$row_m = $this->db->fetch_array($result_m);
			echo $this->show_size( 'local',$product_id,$order_id,$row_m[assign_inventory],'',$item,$div_id,'','','','',$workorder_id,'','',$tr_id );
		
		 }
	$html=ob_get_contents();
    ob_end_clean();
    return $html;
	}
	
	function normalInventory( $order_id='', $workorder_id='', $item='', $inventory_id='', $z='' ){
	   	    $sql_q = "select product_id, gp_id from ".erp_PRODUCT_ORDER." where workorder_id = '$workorder_id' and order_id = '$order_id'";
			$result_q = $this->db->query($sql_q,__FILE__,__LINE__);
			$row_q = $this->db->fetch_array($result_q);
			
			if( $row_q[gp_id] == 0 )
				{ $product = $workorder_id; }
				else
				{ $product = $row_q[gp_id]; }
				
			if( $z == '' ){
				$sql_del = "DELETE FROM ".erp_INVENTORY_PRICE." WHERE order_id = '$order_id' AND work_orderid = '$workorder_id' AND inventory_type = '$item'";
				$this->db->query($sql_del,__FILE__,__LINE__);
			}
	
			
					
			$sql_s = "Select size from ".erp_SIZE." where product_id ='$product'";
			$result_s = $this->db->query($sql_s,__FILE__,__LINE__);
			
			while($row_s = $this->db->fetch_array($result_s)){
				  $s = explode("_",$row_s[size]);
				  $s = strtolower($s[1]);
				  $ab = $s.'_inventory_usage';
				  
				$sql_p = "Select inventory_cost_increase, $ab from ".erp_ASSIGN_INVENTORY." where product_id ='$row_q[product_id]' and inventory_id = '$inventory_id' and group_inventory_id = '0'";
				$result_p = $this->db->query($sql_p,__FILE__,__LINE__);
				if( $this->db->num_rows($result_p) > 0 ){
			      
				  $row_p = $this->db->fetch_array($result_p);
				  if( $row_p[$ab] != 0 ){
					if( $z != '' ){
						$sql_i = "Select inventory from ".erp_INVENTORY_PRICE." where order_id = '$order_id' and work_orderid = '$workorder_id' and inventory_type = '$item' and size_type = '$s' and group_id = '0'";
						$result_i = $this->db->query($sql_i,__FILE__,__LINE__);
						if( $this->db->num_rows($result_i) == 0 ){
						
							$insert_sql_array = array();				
							$insert_sql_array[work_orderid] = $workorder_id;
							$insert_sql_array[inventory_type] = $item;
							$insert_sql_array[sub_product_id] = $row_q[gp_id];
							$insert_sql_array[size_type] = $s;
							$insert_sql_array[inventory] = $inventory_id;
							$insert_sql_array[cost_increase] = $row_p[inventory_cost_increase];
							$insert_sql_array[group_id] = '0';
							$insert_sql_array[order_id] = $order_id;
						
							$this->db->insert(erp_INVENTORY_PRICE,$insert_sql_array);
						  }
					} else {
				
						$insert_sql_array = array();				
						$insert_sql_array[work_orderid] = $workorder_id;
						$insert_sql_array[inventory_type] = $item;
						$insert_sql_array[sub_product_id] = $row_q[gp_id];
						$insert_sql_array[size_type] = $s;
						$insert_sql_array[inventory] = $inventory_id;
						$insert_sql_array[cost_increase] = $row_p[inventory_cost_increase];
						$insert_sql_array[group_id] = '0';
						$insert_sql_array[order_id] = $order_id;
					
						$this->db->insert(erp_INVENTORY_PRICE,$insert_sql_array);
					 }
					 }
					 
				  }
			}
	}
	
	function GroupInventory( $show='', $inventory='', $item='', $product_id='', $order_id='', $div_id='', $type_item_group='' , $group_id = '', $workorder_id='', $z='', $group_item=array() ){
	   ob_start();
	    $item_group = explode("~",$type_item_group);
		$p = '_inventory_usage';
		$len = count($item_group);
		
		$b = '';
		$c = '';
		
		$sql_id = "SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id' and group_inventory_id = '$item_group[0]'";
		$result_id = $this->db->query($sql_id,__FILE__,__LINE__);
		
		while( $row_id = $this->db->fetch_array($result_id) ){
		
		$size_dependant =  $this->checkSizeDependant('check',$row_id['xs'.$p],$row_id['s'.$p],$row_id['m'.$p],$row_id['l'.$p],$row_id['xl'.$p],$row_id['2x'.$p],$row_id['3x'.$p],$row_id['4x'.$p]);
		
		
			if( $size_dependant == 'true' ){
					$sql = "SELECT name,inventory_id FROM ".TBL_INVENTORY_DETAILS." WHERE name = '$row_id[name]'";
					$result = $this->db->query($sql,__FILE__,__LINE__);
					$row = $this->db->fetch_array($result);
					//$b .= $row[name].',';
					$x .= $row[inventory_id].',';
			} else{
					$sql = "SELECT name,inventory_id FROM ".TBL_INVENTORY_DETAILS." WHERE name = '$row_id[name]'";
					$result = $this->db->query($sql,__FILE__,__LINE__);
					$row = $this->db->fetch_array($result);
					$c .= $row[inventory_id].',';
			}
		
		 }
		 
		 if( $z == '' ){?>
		  <a href="javascript:void(0);" onClick="javascript:var group_item = new Array();
															 <?php $i=0; foreach($group_item as $type_itm){ 
															 ?>group_item[<?php echo $i; ?>] = '<?php echo $type_itm;?>';<?php
																$i++;
															 }?> 
		                                                     workorder.itemDisplay('',
		   																		   '<?php echo $inventory; ?>',
																				   '<?php echo $item; ?>',
																				   '<?php echo $product_id; ?>',
																		           '<?php echo $order_id;?>',
																				   '<?php echo $div_id;?>',
																				   group_item,
																				   1,
																				   '<?php echo $workorder_id;?>',
																				   {target:'<?php echo $div_id; ?>'});
																		"> <?php echo $item_group[1]; ?></a>
		  
		  <?php } elseif( $z == '2' ){ ?><e style="color:#FF0000;"><?php echo $item_group[1]; ?></e><?php }
		  /*$inv = explode(",",$b);
		  $len3 = count($inv);
		  if( $b != '' ){
		    echo '<br/> ( ';
		  }
		  for($i=0;$i<($len3-1);$i++){
		     echo $inv[$i];
			 
			 if($inv[$i+1]){ echo ',';}
		  }
		  
		  if( $b != '' ){ echo ' )'; }
		  $merge = $item_group[0].','.$x;*/
		  ////////////////////////////////////////
		  
		  /*$sql_up = "UPDATE ".erp_WORK_ORDER." Set $item = '', assign_id = '$row_id[assign_id]' WHERE product_id = '$workorder_id' and order_id = '$order_id'";
		  $this->db->query($sql_up,__FILE__,__LINE__);*/
		 
		 
		 //if( $x != '' ){
		 
		    $mn = '';
		    $sql_check = "select $item from ".erp_WORK_ORDER." where product_id = '$workorder_id' and order_id = '$order_id' and $item <> ''";
		    $result_check = $this->db->query($sql_check,__FILE__,__LINE__);
		    $row_check = $this->db->fetch_array($result_check);
			$mn = $row_check[$item];
			 
		    if( $this->db->num_rows($result_check) == 0 ){
		      //if( $x != '' ){
			      $merge = $item_group[0].','.$x;
				  
				  $sql = "UPDATE ".erp_WORK_ORDER." Set $item = '$merge', assign_id = '$row_id[assign_id]' WHERE product_id = '$workorder_id' and order_id = '$order_id'";
				  $this->db->query($sql,__FILE__,__LINE__);
			   
			      $mn = $merge;
			  //}
			 }
			 
			 
/////////////////////////////////////////***********///////////////////////////////////////////////////////

			    $sql_q = "select product_id, gp_id from ".erp_PRODUCT_ORDER." where workorder_id = '$workorder_id' and order_id = '$order_id'";
				$result_q = $this->db->query($sql_q,__FILE__,__LINE__);
				$row_q = $this->db->fetch_array($result_q);
				
				if( $row_q[gp_id] == 0 )
					{ $product = $workorder_id; }
					else
					{ $product = $row_q[gp_id]; }
				
				//$in = explode(",",$x);
				
				$in = explode(",",$mn);
				if( $in[1] != '' ){
				
					$len4 = count($in);
					for($i=1;$i<($len4-1);$i++){
				
				        $sql_ab = "Select name from ".TBL_INVENTORY_DETAILS." where inventory_id = '$in[$i]'";
						//echo $sql_ab;
						$result_ab = $this->db->query($sql_ab,__FILE__,__LINE__);
						$row_ab = $this->db->fetch_assoc($result_ab);
						 
						if( $i == 1 ){ echo '<br/> ( '; }
						echo $row_ab[name];
							
						if($in[$i+1]){ echo ','; }
						if( $i == ($len4-2) ){ echo ' )'; }
						
						$sql_p = "Select inventory_cost_increase, name from ".erp_ASSIGN_INVENTORY." where product_id ='$row_q[product_id]' and inventory_id = '$in[$i]' and group_inventory_id = '$item_group[0]'";
						//echo $sql_p;
						$result_p = $this->db->query($sql_p,__FILE__,__LINE__);
						if( $this->db->num_rows($result_p) > 0 ){
							$row_p = $this->db->fetch_assoc($result_p);
							
					  
							$sql_s = "Select size from ".erp_SIZE." where product_id ='$product'";
							$result_s = $this->db->query($sql_s,__FILE__,__LINE__);
							
							while($row_s = $this->db->fetch_array($result_s)){
								  $s = explode("_",$row_s[size]);
								  $s = strtolower($s[1]);
								  
								$sql_siz = "SELECT * FROM ".erp_INVENTORY_PRICE." WHERE order_id = '$order_id' and work_orderid = '$workorder_id' and inventory_type = '$item' and size_type = '$s' and inventory = '$in[$i]' and group_id ='$item_group[0]'";
								 $result_siz = $this->db->query($sql_siz,__FILE__,__LINE__);
								if( $this->db->num_rows($result_siz) == 0 ){
								
									$insert_sql_array = array();				
									$insert_sql_array[work_orderid] = $workorder_id;
									$insert_sql_array[inventory_type] = $item;
									$insert_sql_array[sub_product_id] = $row_q[gp_id];
									$insert_sql_array[size_type] = $s;
									$insert_sql_array[inventory] = $in[$i];
									$insert_sql_array[cost_increase] = $row_p[inventory_cost_increase];
									$insert_sql_array[group_id] = $item_group[0];
									$insert_sql_array[order_id] = $order_id;
								
									$this->db->insert(erp_INVENTORY_PRICE,$insert_sql_array);
								}
							}
						}
					}
				}
////////////////////////////////////////////////*****************/////////////////////////////////////////
		  //}
		   //if( $c != '' ){
			  $sql_sub = "select product_id, gp_id from ".erp_PRODUCT_ORDER." where workorder_id = '$workorder_id'";
			  $result_sub = $this->db->query($sql_sub,__FILE__,__LINE__);
			  $row_sub = $this->db->fetch_array($result_sub);
				
				if( $row_sub[gp_id] == 0 )
				{ $product = $workorder_id; }
				else
				{ $product = $row_sub[gp_id]; }
				
			  $sql_size = "SELECT * FROM ".erp_SIZE." WHERE order_id = '$order_id' and product_id = '$product'";
			  $result_size = $this->db->query($sql_size,__FILE__,__LINE__);
			  $inve_id = explode(',',$c);
			  ?>
			  <table width="100%">
			  <?php
			   while($row_size = $this->db->fetch_array($result_size)){
			      
				  $size1 = explode('_',$row_size[size]);
				  $size = strtolower("$size1[1]");
				  $len = count($inve_id);
				  $a = '';
				  for($i=0;$i<($len-1);$i++){
					  $sql = "SELECT name FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id' and inventory_id = '$inve_id[$i]' and ".$size."_inventory_usage <> '0' and group_inventory_id = '$item_group[0]'";
					  $result = $this->db->query($sql,__FILE__,__LINE__);
					  if( $this->db->num_rows($result) > 0 ){
						  $a .= $inve_id[$i].',';
					   }
					}
					
					$y = $a.'_'.$size;
				    $y = str_replace(",_","_","$y");
					$sk = $size.'_size_dependant';
					
					//if( $a != '' ){
						  $sql_m = "SELECT * FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' and product_id = '$workorder_id' and option_type = '$item'";
					      $result_m = $this->db->query($sql_m,__FILE__,__LINE__);
						  if( $this->db->num_rows($result_m) > 0 ){
						  
						    $sql_ch = "SELECT * FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' and product_id = '$workorder_id' and option_type = '$item' and $sk <> ''";
					        $result_ch = $this->db->query($sql_ch,__FILE__,__LINE__);
						    if( $this->db->num_rows($result_ch) == 0 ){
						
							   $sql = "UPDATE ".erp_SIZE_DEPENDENT." SET $sk = '$y' WHERE order_id = '$order_id' and product_id = '$workorder_id' and option_type = '$item'";
							   $this->db->query($sql,__FILE__,__LINE__);
							 }
							 
							
						 } else {
							 $insert_array = array();
							 $insert_array[group] = $item_group[0];				
							 $insert_array[product_id] = $workorder_id;
							 $insert_array[order_id] = $order_id;
							 $insert_array[option_type] = $item;
							 $insert_array[$sk] = $y;	
							
							 $this->db->insert(erp_SIZE_DEPENDENT,$insert_array);
						  }
						//}
						
					$sql_c = "SELECT $sk FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' and product_id = '$workorder_id' and option_type = '$item' and $sk <> ''";
					$result_c = $this->db->query($sql_c,__FILE__,__LINE__);
					$row_c = $this->db->fetch_array($result_c);
					
					//$inve = explode(',',$a);
					
					$inv = explode('_',$row_c[$sk]);
					if( $inv[0] != '' ){
					
						$inve = explode(',',$inv[0]);
						$len2 = count($inve);
					
						$row = $this->db->fetch_array($result);?>
						<tr>
						   <td><?php echo $size1[1]; ?></td>
						   <td>:</td>
						   <td>
						   <?php
							  for($i=0;$i<($len2);$i++){
								echo $this->invenName($inve[$i]);
								 
								if( $inve[$i+1] != '' ){ echo ',';}

///////////////////////////////////////********************//////////////////////////////////////////////
								$sz = strtolower($size1[1]);
								
								$sql_p = "Select inventory_cost_increase from ".erp_ASSIGN_INVENTORY." where product_id ='$row_sub[product_id]' and inventory_id = '$inve[$i]' and group_inventory_id = '$item_group[0]'";
								$result_p = $this->db->query($sql_p,__FILE__,__LINE__);
								$row_p = $this->db->fetch_array($result_p);
									
								$sql_si = "SELECT * FROM ".erp_INVENTORY_PRICE." WHERE order_id = '$order_id' and work_orderid = '$workorder_id' and inventory_type = '$item' and size_type = '$sz' and inventory = '$inve[$i]' and group_id ='$item_group[0]'";
					  		 	$result_si = $this->db->query($sql_si,__FILE__,__LINE__);
						       if( $this->db->num_rows($result_si) == 0 ){	
							  
									$insert_sql_array = array();				
									$insert_sql_array[work_orderid] = $workorder_id;
									$insert_sql_array[inventory_type] = $item;
									$insert_sql_array[sub_product_id] = $row_sub[gp_id];
									$insert_sql_array[size_type] = $size;
									$insert_sql_array[inventory] = $inve[$i];
									$insert_sql_array[cost_increase] = $row_p[inventory_cost_increase];
									$insert_sql_array[group_id] = $item_group[0];
									$insert_sql_array[order_id] = $order_id;
									
									$this->db->insert(erp_INVENTORY_PRICE,$insert_sql_array);
								 }
							   }
/////////////////////////////////////**********************////////////////////////////////////////
						   ?>
						   </td>
						 </tr>	
					<?php }
				}?>
			</table>
	     <?php //}
	$html=ob_get_contents();
    ob_end_clean();
    return $html;
	}
	
	function SplitInventory( $product_id='', $type_item='' ){
	   ob_start();

		$b = '';
	    $c = '';
		$p = '_inventory_usage';
		$explode_type_item = explode("~",$type_item);
		$len_type_item = count($explode_type_item);
		
		for($i=1;$i<$len_type_item;$i++){
				   
			$str = array("`","&#096;");
			$replace = "&#039;";
			$inv_name = str_replace($str,$replace,$explode_type_item[$i]); 
			$sql_id = "SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE name = '$inv_name' and product_id = '$product_id' and group_inventory_id = '0'";
			
			$result_id = $this->db->query($sql_id,__FILE__,__LINE__);
			$row_id = $this->db->fetch_array($result_id);
			
			$size_dependant =  $this->checkSizeDependant('check',$row_id['xs'.$p],$row_id['s'.$p],$row_id['m'.$p],$row_id['l'.$p],$row_id['xl'.$p],$row_id['2x'.$p],$row_id['3x'.$p],$row_id['4x'.$p]);
			
			if( $size_dependant == 'true' ){
				
				$b .= $row_id[inventory_id].',';
			} else{
			    
				$c .= $row_id[inventory_id].',';
			}
		 }//end of for
		 
		 $a = $b.'='.$c;
		 return $a;
	 $html=ob_get_contents();
     ob_end_clean();
     return $html;
	 }

	function check_sizedependent( $c='', $product='', $order_id='', $group_id='', $workorder_id='' ){
	   
	      $b = '';
	      $sql_sub = "select gp_id from ".erp_PRODUCT_ORDER." where workorder_id = '$workorder_id'";
		  $result_sub = $this->db->query($sql_sub,__FILE__,__LINE__);
		  $row_sub = $this->db->fetch_array($result_sub);
			
			if( $row_sub[gp_id] == 0 )
			{ $product_id = $workorder_id; }
			else
			{ $product_id = $row_sub[gp_id]; }
			
	      $sql_size = "SELECT * FROM ".erp_SIZE." WHERE order_id = '$order_id' and product_id = '$product_id'";
		  $result_size = $this->db->query($sql_size,__FILE__,__LINE__);
		  $inve_id = explode(',',$c);
		  
		   while($row_size = $this->db->fetch_array($result_size)){
		      $size1 = explode('_',$row_size[size]);
			  $size = strtolower("$size1[1]");
 			  $len = count($inve_id);
			  
			  for($i=0;$i<($len-1);$i++){
			      if( $group_id == '' ){
				     @$group = 0;
				  } else { @$group = $group_id; }
				
				  $sql = "SELECT name FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product' and inventory_id = '$inve_id[$i]' and ".$size."_inventory_usage <> '0' and group_inventory_id = '$group'";
			      $result = $this->db->query($sql,__FILE__,__LINE__);
			      if( $this->db->num_rows($result) > 0 ){
				      $b .= $inve_id[$i].',';
				   }
			    }
			 }
			 return $b;
			   
	  }
	
	function itemDisplay( $show='',$type_item='',$item='',$product_id='',$order_id='',$div_id='',$type_item_group=array(),$z='',$workorder_id='',$tr_id='' ){
	   ob_start();
	    $s = 0;
		$combine = array();
		//echo $type_item;
		$sql_size = "SELECT * FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' and product_id = '$workorder_id' and option_type = '$item'";
		$result = $this->db->query($sql_size,__FILE__,__LINE__);
		$row = $this->db->fetch_array($result);
		
		$len = count($type_item_group);
		
		for($i=0;$i<$len;$i++){
			$e = explode("~",$type_item_group[$i]);
			if( $e[0] == $row['group'] ){
				   $selected_group = $type_item_group[$i];
			}
		}
		$num = count($type_item_group);
		$explode_type_item = explode("~",$type_item);

		$len_type_item = count($explode_type_item);
		if( $len_type_item == 2 and $num < 1 ){
			echo $this->SingleInventory( $show, $explode_type_item[1], $item, $product_id, $order_id, $div_id, $type_item_group, $workorder_id,$tr_id );
		}
		else if( $len_type_item > 2 or ( $len_type_item == 2 and $num > 0 ) or ( $len_type_item < 2 and $num > 1 ) ){
				   $ro;
				   $b = '';
			       $c = '';
				   $p = '_inventory_usage';
				   $item_group = explode("~",$type_item_group);
				   
				   $split = $this->SplitInventory( $product_id, $type_item );
				   
				   $split1 = explode("=",$split);
				   $b = $split1[0];
				   $c = $split1[1];
				   $combine[0] = $b;
				   $combine[1] = $num;
				   //echo 'b'.$b.'c'.$c;
					$check_size = $this->check_sizedependent( $c, $product_id, $order_id, '', $workorder_id );
			        if( $this->db->num_rows($result) == 0 || $z == 1 ){
					
					if( $b == '' and $c != '' and $num < 1 ){
					  
					   echo $this->show_size( 'multiple',$product_id,$order_id,$c,'',$item,$div_id,'',$type_item,$type_item_group,'',$workorder_id,'','','',$combine ); ?>
					   <script>
							document.getElementById('<?php echo $item.'_'; ?>').innerHTML = '';
					   </script>
					   
				<?php	
					}
					
					$sql_check = "select * from ".erp_WORK_ORDER." where product_id = '$workorder_id' and order_id = '$order_id'";
					$result_check = $this->db->query($sql_check,__FILE__,__LINE__);
					$row_check = $this->db->fetch_array($result_check);
					$wk = explode(",",$row_check[$item]);
					$num1 = count($wk);
					if( $num1 < 2 || $z == 1 ){ ?>
					<div id="<?php echo $item.'_' ; ?>">
					<select id="<?php echo $item ; ?>" 
							onchange="javascript: var inve_name = this.value;
							                  if(confirm('Are You Sure To Change This Inventory')){
											    workorder.deletethis('<?php echo $product_id; ?>',
																	 '<?php echo $order_id; ?>',
																	 'delete',
																	 '<?php echo $workorder_id; ?>',
																	 '<?php echo $item; ?>',
																	 {preloader:'prl'});
												var group_item = new Array();
											     <?php $i=0; foreach($type_item_group as $type_itm){ 
												$str = array("'","&#039;");
												$replace = "&#096;";
												$t_name = str_replace($str,$replace,$type_itm);
												?>group_item[<?php echo $i; ?>] = '<?php echo $t_name;?>';<?php
													$i++;
											    }?> 
												var k = inve_name.split('*');
												if( k[0] == 'group' ){
													     workorder.GroupInventory('',
													                              '<?php echo $type_item; ?>',
																				  '<?php echo $item;?>',
																	              '<?php echo $product_id; ?>',
																	              '<?php echo $order_id; ?>',
																			      '<?php echo $div_id;?>',
																				  k[1],
																				  '<?php echo $row['group']; ?>',
																				  '<?php echo $workorder_id; ?>',
																				  '',
																				  group_item,
																				  
																	      {target:'<?php echo $div_id; ?>'});
														 workorder.updateCapacity({preloader:'prl'});
												 } else {
												  if( inve_name != 'multiple' ){
													  var srl = inve_name.split('*');
													  workorder.update_inve(srl[0],
																			'<?php echo $item ; ?>',
																			srl[1],
																			'<?php echo $workorder_id; ?>',
																			'<?php echo $order_id; ?>',
																			'',
																			'<?php echo $item; ?>',
																			{preloader:'prl',
																			onUpdate:function(response,root){
																			workorder.inventory_name(inve_name,
																			{preloader:'prl',
																			onUpdate:function(response,root){
																			workorder.againlink('<?php echo $show; ?>',
																								'<?php echo $type_item; ?>',
																								'<?php echo $item; ?>',
																								'<?php echo $product_id; ?>',
																								'<?php echo $order_id; ?>',
																								'<?php echo $div_id;?>',
																								group_item,
																								'<?php echo $workorder_id;?>',
																								
																								
																			{preloader:'prl',
																			target:'<?php echo $div_id; ?>'});
																			}});
																			}}); 
																workorder.updateCapacity({preloader:'prl'});					
													 } else {
													    var combine = new Array();
														<?php $u=0; foreach($combine as $combine_inve){ ?>
														combine[<?php echo $u; ?>] = '<?php echo $combine_inve;?>';<?php
															$u++;
														}?>
													 
														 workorder.show_size(inve_name,
																			 '<?php echo $product_id; ?>',
																			 '<?php echo $order_id; ?>',
																			 '<?php echo $c; ?>',
																			 '',
																			 '<?php echo $item; ?>',
																			 '<?php echo $div_id; ?>',
																			 '',
																			 '<?php echo $type_item; ?>',
																			 group_item,
																			 '<?php //echo $item_group[0];?>',
																			 '<?php echo $workorder_id; ?>',
																			 '','','',
																			 combine,
																  {preloader:'prl',target:'<?php echo $div_id;?>'});
															workorder.updateCapacity({preloader:'prl'});
													  }}
												 workorder.showOrderSizeQuantity('<?php echo $_SESSION['contact_id']; ?>',
																					 '<?php echo $order_id; ?>',
																					 'a',
															   { preloader:'prl',
																 onUpdate: function(response,root){
															document.getElementById('order_size_quantity').innerHTML=response; 
																 $('#display_order').tablesorter({widthFixed:true,
																 widgets:['zebra'],sortList:[[0,0]],headers: {5:{sorter: false}}	});}});
																				   }">
						<option value="">-select-</option>
						<?php
							$inve = explode(',',$b);
							$len = count($inve);
							for($i=0;$i<($len-1);$i++){
							$s = 1;
							$sql_inve = "SELECT assign_id, name FROM ".erp_ASSIGN_INVENTORY." WHERE inventory_id = '$inve[$i]' and product_id = '$product_id' and group_inventory_id = '0'";
							$result_inve = $this->db->query($sql_inve,__FILE__,__LINE__);
							$row_inve = $this->db->fetch_array($result_inve); 
							$ro=$row_inve[assign_id]; ?>
							
						<option  value="<?php echo $inve[$i].'*'.$row_inve[assign_id]; ?>" <?php //if($inve[$i] == $field)	echo "selected=selected"; ?> ><?php echo $row_inve[name]; ?></option>
						<?php } //end of for
						if( $c != '' and $check_size != '' ){?>
						<option value="multiple">Size Dependant</option>
						<?php $s = 1; } 
						if( $type_item_group != '' ){
						    $num = count($type_item_group);
							for($i=0;$i<$num;$i++){
							$split_group = explode("~",$type_item_group[$i]);
							
							$num_inve = count($split_group);
							for($j=2;$j<$num_inve;$j++){
								$sql_invent="select inventory_id from " . TBL_INVENTORY_DETAILS ." where name='$split_group[$j]'";
							    $result_invent = $this->db->query($sql_invent,_FILE_,_LINE_);
								$row_invent=$this->db->fetch_array($result_invent);
								$d .= $row_invent[inventory_id].',';
							  }
							 $check_size2 = $this->check_sizedependent( $d, $product_id, $order_id, $split_group[0], $workorder_id );
							 if( $check_size2 != '' ){ ?>
							 <option value="<?php echo 'group*'.$type_item_group[$i]; ?>"><?php echo $split_group[1]; ?></option>
							<?php } } } ?>
					</select>
					</div>
			<?php } else {
			        for($i=0;$i<$len;$i++){
						$f = explode("~",$type_item_group[$i]);
						if( $f[0] == $wk[0] ){
							   $select_group = $type_item_group[$i];
						}
						
					}
					echo $this->GroupInventory( '',$type_item,$item,$product_id,$order_id,$div_id,$select_group,$wk[0],$workorder_id, '', $type_item_group );
			     }
			 } else {
			 		if($row[group] != ''){
			        	echo $this->GroupInventory( '',$type_item,$item,$product_id,$order_id,$div_id,$selected_group,$row['group'],$workorder_id, '', $type_item_group );
					}
					else {
						echo $this->show_size( 'multiple',$product_id,$order_id,$c,'',$item,$div_id,'',$type_item,$type_item_group,'',$workorder_id,'','','',$combine );
					} 	
			   }
			 } //end of elseif
			  elseif( ( $len_type_item < 2 and $num == 1 ) ){
			      
				  echo $this->GroupInventory( '',$type_item,$item,$product_id,$order_id,$div_id,$type_item_group[0],$row['group'],$workorder_id, 2, $type_item_group );
			  }
            $html=ob_get_contents();
            ob_end_clean();
            return $html;		
	} /////end of function itemDisplay
	 
	 function show_size( $runat='',$product_id='',$order_id='',$inventory_id='',$assign_id='',$item='',$div_id='',$inve_name='',$type_item='',$type_item_group=array(),$z='',$workorder_id='', $m='', $inventory='', $tr_id='', $combine=array() ){
	    ob_start();
		switch($runat){ 
		   case 'local':
		   
		    $sql_sub = "select gp_id from ".erp_PRODUCT_ORDER." where workorder_id = '$workorder_id'";
			$result_sub = $this->db->query($sql_sub,__FILE__,__LINE__);
			$row_sub = $this->db->fetch_array($result_sub);
			
			if( $row_sub[gp_id] == 0 )
			{ $product = $workorder_id; }
			else
			{ $product = $row_sub[gp_id]; }
			 
		   $sql_size = "SELECT * FROM ".erp_SIZE." WHERE order_id = '$order_id' and product_id = '$product'";
		   $result_size = $this->db->query($sql_size,__FILE__,__LINE__);
		   $a = '';
		   $ab = '';
		   ?>
		   <p style="color:#FF0000";>Size Dependant</p>
		   <table class="table">
		   <?php
		   while($row_size = $this->db->fetch_array($result_size)){
		      $size1 = explode('_',$row_size[size]);
			  $size = strtolower("$size1[1]");
		      $sk = $size.'_size_dependant';
			  
			  $sql_ch = "SELECT $sk FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' and product_id = '$workorder_id' and option_type = '$item' and $sk <> ''";
			  //echo $sql_ch;
			  $result_ch = $this->db->query($sql_ch,__FILE__,__LINE__);
			  if( $this->db->num_rows($result_ch) == 0 ){
			    
				$sql_a = "SELECT name,".$size."_inventory_usage FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id' and inventory_id = '$inventory_id' and group_inventory_id = '0'";
			    $result_a = $this->db->query($sql_a,__FILE__,__LINE__);
			    if( $this->db->num_rows($result_a) > 0 ){
				   $row_a = $this->db->fetch_array($result_a);
				   
				   if( $row_a[$size.'_inventory_usage'] != 0 ){
				     $inve_size = $inventory_id.'_'.$size;
					} else { $inve_size = '_'.$size;  }
					
				       echo $this->show_size('server',$product_id,$order_id,$inve_size,$assign_id,$item,$div_id,$inve_name,$type_item,'','',$workorder_id,'',$inventory_id);
				  }	   
				}
				
				$sql_c = "SELECT $sk FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' and product_id = '$workorder_id' and option_type = '$item' and $sk <> ''";
			    //echo $sql_c;
			    $result_c = $this->db->query($sql_c,__FILE__,__LINE__);
				$row_c = $this->db->fetch_array($result_c);
				
				$in_size = explode("_",$row_c[$sk]);
				if( $in_size[0] != '' ){
				  $ab = 1;
				 ?>
					<tr>
					   <td><?php echo $size1[1]; ?></td>
					   <td>:</td>
					   <td>
					  <?php echo $this->invenName($in_size[0]); ?>
					   </td>
					</tr>
			<?php }
			  } //echo $ab;
			  if( $ab == '' ){?>
				<script>
					document.getElementById('<?php echo $tr_id; ?>').innerHTML = '';
				</script>
				<?php }
			  ?>
		  </table>
		<?php
		break;
		case 'multiple':
		  if( $combine[0] == '' and $combine[1] < 1 ){
		?>
		<em style="color:#FF0000;">Size Dependant</em>
		<?php } else { ?>
		   <a href="javascript:void(0);" onClick="javascript:var group_item = new Array();
															 <?php $i=0; foreach($type_item_group as $type_itm){ 
															 ?>group_item[<?php echo $i; ?>] = '<?php echo $type_itm;?>';<?php
																$i++;
															 }?> 
		                                                     workorder.itemDisplay('',
		   																		   '<?php echo $type_item; ?>',
																				   '<?php echo $item; ?>',
																				   '<?php echo $product_id; ?>',
																		           '<?php echo $order_id;?>',
																				   '<?php echo $div_id;?>',
																				   group_item,
																				   1,
																				   '<?php echo $workorder_id;?>',
																				   {target:'<?php echo $div_id; ?>'});
																		">Size Dependant</a>
		   <?php }
		    $sql_sub = "select gp_id from ".erp_PRODUCT_ORDER." where workorder_id = '$workorder_id'";
			$result_sub = $this->db->query($sql_sub,__FILE__,__LINE__);
			$row_sub = $this->db->fetch_array($result_sub);
			
			if( $row_sub[gp_id] == 0 )
			{ $product = $workorder_id; }
			else
			{ $product = $row_sub[gp_id]; }
		   
		   $sql_size = "SELECT * FROM ".erp_SIZE." WHERE order_id = '$order_id' and product_id = '$product'";
		   $result_size = $this->db->query($sql_size,__FILE__,__LINE__);
		   $a = '';
		   $inve_id = explode(',',$inventory_id); ?>
		   <table class="table">
		   <?php
		   while($row_size = $this->db->fetch_array($result_size)){
		      $size1 = explode('_',$row_size[size]);
			  $size = strtolower("$size1[1]");
 			  $len = count($inve_id);
			  $b = '';
			  $sk = $size.'_size_dependant';
			  
				  for($i=0;$i<($len-1);$i++){
				   if( $z == '' ){
					 $sql = "SELECT name,".$size."_inventory_usage FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id' and inventory_id = '$inve_id[$i]' and ".$size."_inventory_usage <> '0' and group_inventory_id = '0'";
				   } else {
					 $sql = "SELECT name,".$size."_inventory_usage FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id' and group_inventory_id = '$z' and inventory_id = '$inve_id[$i]' and ".$size."_inventory_usage <> '0'";
				   
				   }
				   $result = $this->db->query($sql,__FILE__,__LINE__);
				   if( $this->db->num_rows($result) > 0 ){
					   
					   $b .= $inve_id[$i].',';
					   }
				   }//end of for
				   
				   $inve = explode(',',$b);
				   $len2 = count($inve);
				   //echo $len2.'--'.$b;
			   if( $len2 <= 2 ){
			      
			   $sql_ch = "SELECT $sk FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' and product_id = '$workorder_id' and option_type = '$item' and $sk <> ''";
			   //echo $sql_ch;
			   $result_ch = $this->db->query($sql_ch,__FILE__,__LINE__);
			   if( $this->db->num_rows($result_ch) == 0 ){
			    
				    echo $this->show_size('server',$product_id,$order_id,$inve[0].'_'.$size,$assign_id,$item,$div_id,$inve_name,$type_item,$type_item_group,$z,$workorder_id);
				}
				
				
				    $sql_h = "SELECT $sk FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' and product_id = '$workorder_id' and option_type = '$item' and $sk <> ''";
			        //echo $sql_ch;
			        $result_h = $this->db->query($sql_h,__FILE__,__LINE__);
			   
					$row_h = $this->db->fetch_array($result_h);
					$enter_inve = explode("_",$row_h[$sk]);
					if( $enter_inve[0] != '' ){
					?>
						<tr>
						   <td><?php echo $size1[1]; ?></td>
						   <td>:</td>
						   <td>
						   <?php echo $this->invenName($enter_inve[0]); ?>
						   </td>
						</tr>
			    
			<?php    }
			    }
				
				 if( $inve[0] != '' ){
				   if( $len2 > 2 ) {
				     $sql_s = "SELECT ".$size."_size_dependant FROM ".erp_SIZE_DEPENDENT." WHERE product_id = '$workorder_id' and order_id = '$order_id' and option_type = '$item'";
					 $result_s = $this->db->query($sql_s,__FILE__,__LINE__);
					 $row_s = $this->db->fetch_array($result_s);
					 $s = explode("_",$row_s[$size.'_size_dependant']);
					 if( $s[0] != '' or $row_s[$size.'_size_dependant'] == '' ){
					 
					  $sql = "SELECT option_type,".$size."_size_dependant FROM ".erp_SIZE_DEPENDENT." WHERE product_id = '$workorder_id' and order_id = '$order_id' and option_type = '$item' and ".$size."_size_dependant <> ''";
					  $result = $this->db->query($sql,__FILE__,__LINE__);
					  if( $this->db->num_rows($result) > 0 ){
					  $row_size = $this->db->fetch_array($result); ?>
					  
						  <tr>
						   <td><?php echo $size1[1]; ?></td>
						   <td>:</td>
						   <td>
							  <?php
							  echo $this->sizeLink('multiple',$product_id,$order_id,$row_size[$size.'_size_dependant'],$assign_id,$item,$div_id,'',$type_item,$size,$b,$type_item_group,$z,$workorder_id); ?>
						   </td>
						  </tr>
					  <?php
						} else { ?>
						  <tr>
						   <td><?php echo $size1[1]; ?></td>
						   <td>:</td>
						   <td>
							 <?php
							  echo $this->sizeLink('dropdown',$product_id,$order_id,$inventory_id,$assign_id,$item,$div_id,'',$type_item,$size,$b,$type_item_group,$z,$workorder_id);
							 ?>
						   </td>
						 </tr>
						<?php }
					    }
					 }
				  }
					
			   }//end of while ?>
			</table>
		<?php
		break;
		case 'server':
			$size1 = explode('_',$inventory_id);
			
			$sql_size = "SELECT * FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' and product_id = '$workorder_id' and option_type = '$item'";
		    $result_size = $this->db->query($sql_size,__FILE__,__LINE__);
			if( $this->db->num_rows($result_size) > 0 ){
				$row_size = $this->db->fetch_array($result_size);
				$size1 = explode('_',$inventory_id);
				
				$sql = "UPDATE ".erp_WORK_ORDER." SET $item = '' WHERE order_id = '$order_id' and product_id = '$workorder_id'";
			    $this->db->query($sql,__FILE__,__LINE__);
				
				$sql = "UPDATE ".erp_SIZE_DEPENDENT." SET ".$size1[1]."_size_dependant = '$inventory_id' WHERE order_id = '$order_id' and product_id = '$workorder_id' and option_type = '$item'";
				$this->db->query($sql,__FILE__,__LINE__);
				
				echo $inve_name;
			} else {
			
			    $sql = "UPDATE ".erp_WORK_ORDER." SET $item = '' WHERE order_id = '$order_id' and product_id = '$workorder_id'";
			    $this->db->query($sql,__FILE__,__LINE__);
				
			    $size1 = explode('_',$inventory_id);
			    $insert_array = array();
				$insert_array[group] = $z;				
				$insert_array[product_id] = $workorder_id;
				$insert_array[order_id] = $order_id;
                $insert_array[option_type] = $item;
				$insert_array[$size1[1].'_size_dependant'] = $inventory_id;	
				$insert_array[assign_inventory] = $inventory;
				
				$this->db->insert(erp_SIZE_DEPENDENT,$insert_array);
				
				echo $inve_name;
			}
/////////////////////////////////////////////////////////***********************//////////////////////////////////////

			
			if( $size1[0] != '' ){
				$sql_q = "select product_id, gp_id from ".erp_PRODUCT_ORDER." where workorder_id = '$workorder_id' and order_id = '$order_id'";
				$result_q = $this->db->query($sql_q,__FILE__,__LINE__);
				$row_q = $this->db->fetch_array($result_q);
				
				$sql_p = "Select inventory_cost_increase from ".erp_ASSIGN_INVENTORY." where product_id ='$row_q[product_id]' and inventory_id = '$size1[0]' and group_inventory_id = '0'";
				$result_p = $this->db->query($sql_p,__FILE__,__LINE__);
				$row_p = $this->db->fetch_array($result_p);
					
				
				$sql_size = "SELECT * FROM ".erp_INVENTORY_PRICE." WHERE order_id = '$order_id' and work_orderid = '$workorder_id' and inventory_type = '$item' and size_type = '$size1[1]' and group_id ='0'";
				$result_size = $this->db->query($sql_size,__FILE__,__LINE__);
				if( $this->db->num_rows($result_size) > 0 && $m == 1 ){
					
					$sql = "UPDATE ".erp_INVENTORY_PRICE." SET inventory = '$size1[0]', cost_increase = '$row_p[inventory_cost_increase]' WHERE order_id = '$order_id' and work_orderid = '$workorder_id' and inventory_type = '$item' and size_type = '$size1[1]' and group_id ='0'";
					$this->db->query($sql,__FILE__,__LINE__);
					
				 } else if($this->db->num_rows($result_size) == 0) {
					$insert_sql_array = array();				
					$insert_sql_array[work_orderid] = $workorder_id;
					$insert_sql_array[inventory_type] = $item;
					$insert_sql_array[sub_product_id] = $row_q[gp_id];
					$insert_sql_array[size_type] = $size1[1];
					$insert_sql_array[inventory] = $size1[0];
					$insert_sql_array[cost_increase] = $row_p[inventory_cost_increase];
					$insert_sql_array[group_id] = '0';
					$insert_sql_array[order_id] = $order_id;
					
					$this->db->insert(erp_INVENTORY_PRICE,$insert_sql_array);
				   }
			   
			   
			 }
///////////////////////////////////////////////**********************///////////////////////////////////////////
		break;
	  }
	 $html=ob_get_contents();
     ob_end_clean();
     return $html;
	 }
	 
	 function invenName($inventory_id=''){
	    ob_start();
		$sql_name = "select name from ".TBL_INVENTORY_DETAILS." where inventory_id = '$inventory_id' ";
		$result_name = $this->db->query($sql_name,__FILE__,__LINE__);
		$row_name = $this->db->fetch_array($result_name);
		echo $row_name[name];
		
		$html=ob_get_contents();
        ob_end_clean();
        return $html;
	 }
	 
	  function sizeLink( $runat='',$product_id='',$order_id='',$inventory_id='',$assign_id='',$item='',$div_id='',$inve_name='',$type_item='',$size='',$b='',$group_item=array(),$z='',$workorder_id='' ){
	     ob_start();
		 switch($runat){
		 case 'dropdown':
		 $inve = explode(',',$b);?>
		  <span id="<?php echo $item.''.$div_id.''.$size; ?>">
		    <select name="size<?php echo $size; ?>" id="size<?php echo $size; ?>"
								  onchange="javascript: var group_item = new Array();
													    <?php $i=0; foreach($group_item as $type_itm){ ?>
														  group_item[<?php echo $i; ?>] = '<?php echo $type_itm;?>';<?php
														  $i++;
													    }?>
								                       var size_name = this.value;
													   workorder.show_size('server',
																		   '<?php echo $product_id; ?>',
																		   '<?php echo $order_id;?>',
																		   this.value,
																		   '<?php echo $assign_id; ?>',
																		   '<?php echo $item; ?>',
																		   '<?php echo $div_id;?>',
																		   '<?php echo $inve_name;?>',
																		   '<?php echo $type_item;?>',
																		   group_item,
																		   '<?php echo $z;?>',
																		   '<?php echo $workorder_id;?>',
																		   1,
																	   {preloader:'prl',
																	   onUpdate:function(response,root){
																	   workorder.sizeLink('multiple',
																	                      '<?php echo $product_id; ?>',
																		                  '<?php echo $order_id;?>',
																						  size_name,
																		                  '<?php echo $assign_id;?>',
																						  '<?php echo $item;?>',
																						  '<?php echo $div_id; ?>',
																		                  '<?php echo $inve_name;?>',

																						  '<?php echo $type_item;?>',
																						  '<?php echo $size;?>',
																						  '<?php echo $b;?>',

																						  group_item,
																		                  '<?php echo $z;?>',
																						  '<?php echo $workorder_id;?>',
																	   {target:'<?php echo $item.''.$div_id.''.$size; ?>'});
																	   workorder.updateCapacity({preloader:'prl'});
																	   }});
													   workorder.showOrderSizeQuantity('<?php echo $_SESSION['contact_id']; ?>',
																					   '<?php echo $order_id; ?>',
																					   'a',
															   { preloader:'prl',
																 onUpdate: function(response,root){
																 document.getElementById('order_size_quantity').innerHTML=response; 
																 $('#display_order').tablesorter({widthFixed:true,
																 widgets:['zebra'],sortList:[[0,0]],headers: {5:{sorter: false}}	});
														}});
														 ">
						 <option value="">-Select-</option>
						 <?php
						   $len1 = count($inve);
						   for($i=0;$i<($len1-1);$i++){
						   $sql_id = "SELECT name FROM ".erp_ASSIGN_INVENTORY." WHERE inventory_id = '$inve[$i]'";
						   $result_id = $this->db->query($sql_id,__FILE__,__LINE__);
						   $row_id = $this->db->fetch_array($result_id); ?>
						  <option value="<?php echo $inve[$i].'_'.$size; ?>"><?php echo $row_id[name]; ?></option>
						  <?php } ?>
				</select>
			</span>
		 <?php
		 break;
		 case 'multiple':
		 $inve = explode('_',$inventory_id); ?>
		 <span id="<?php echo $item.''.$div_id.''.$size; ?>">
			 <a href="javascript:void(0);" onClick="javascript:var group_item = new Array();
															   <?php $i=0; foreach($group_item as $type_itm){ ?>
															   group_item[<?php echo $i; ?>] = '<?php echo $type_itm;?>';<?php
															   $i++;
															   }?>
			                                                   workorder.sizeLink('dropdown',
																				  '<?php echo $product_id; ?>',
																				  '<?php echo $order_id;?>',
																				  '<?php echo $inventory_id; ?>',
																				  '<?php echo $assign_id;?>',
																				  '<?php echo $item;?>',
																				  '<?php echo $div_id; ?>',
																				  '<?php echo $inve_name;?>',
																				  '<?php echo $type_item;?>',
																				  '<?php echo $size;?>',
																				  '<?php echo $b;?>',
																				  group_item,
																		          '<?php echo $z;?>',
																				  '<?php echo $workorder_id;?>',
																		  {target:'<?php echo $item.''.$div_id.''.$size; ?>'});
																		  
																		"><?php echo $this->invenName($inve[0]); ?></a>
	     </span>
	  <?php break;
	     }
	  $html=ob_get_contents();
      ob_end_clean();
      return $html;
	  }

	  function update_inve($inventory_id='',$type_name='',$assign_id='',$product_id='', $order_id='',$span_id='',$item=''){
		 ob_start();
		     if($span_id != ''){
					$sql_update = "update ".erp_WORK_ORDER." set $type_name = '$inventory_id' where product_id = '$product_id' and order_id = '$order_id'";
					$result_update = $this->db->query($sql_update,__FILE__,__LINE__);
				   
					$sql="select name from ".erp_DROPDOWN_OPTION." where option_name = '$assign_id' and identifier = '$inventory_id'";
					$result = $this->db->query($sql,__FILE__,__LINE__);
					$row=$this->db->fetch_array($result);
					echo $row[name];
		     } else {
					$sql_update = "update ".erp_WORK_ORDER." set $type_name = '$inventory_id',assign_id = '$assign_id' where product_id = '$product_id' and order_id = '$order_id'";
					$result_update = $this->db->query($sql_update,__FILE__,__LINE__);
					
//////////////////////////////////////////////*******************///////////////////////////////////////////////////////

					$sql_q = "select product_id, gp_id from ".erp_PRODUCT_ORDER." where workorder_id = '$product_id' and order_id = '$order_id'";
					$result_q = $this->db->query($sql_q,__FILE__,__LINE__);
					$row_q = $this->db->fetch_array($result_q);
					
					if( $row_q[gp_id] == 0 )
						{ $product = $product_id; }
					else
						{ $product = $row_q[gp_id]; }
					
					$sql_p = "Select inventory_cost_increase from ".erp_ASSIGN_INVENTORY." where product_id ='$row_q[product_id]' and inventory_id = '$inventory_id' and group_inventory_id = '0'";
					$result_p = $this->db->query($sql_p,__FILE__,__LINE__);
					$row_p = $this->db->fetch_array($result_p);
						
					$sql_s = "Select size from ".erp_SIZE." where product_id ='$product'";
					$result_s = $this->db->query($sql_s,__FILE__,__LINE__);
					
					while($row_s = $this->db->fetch_array($result_s)){
					      $s = explode("_",$row_s[size]);
						  $s = strtolower($s[1]);
					
						$insert_sql_array = array();				
						$insert_sql_array[work_orderid] = $product_id;
						$insert_sql_array[inventory_type] = $item;
						$insert_sql_array[sub_product_id] = $row_q[gp_id];
						$insert_sql_array[size_type] = $s;
						$insert_sql_array[inventory] = $inventory_id;
						$insert_sql_array[cost_increase] = $row_p[inventory_cost_increase];
						$insert_sql_array[group_id] = '0';
						$insert_sql_array[order_id] = $order_id;
					
						$this->db->insert(erp_INVENTORY_PRICE,$insert_sql_array);
					}
/////////////////////////////////////////***************************////////////////////////////////////////
					$sql_del = "DELETE FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' and product_id = '$product_id' AND option_type = '$item'";
		            $this->db->query($sql_del,__FILE__,__LINE__);
		      }//end of else
		  $html = ob_get_contents();
		  ob_end_clean();
		  return $html;												
	  } ///// end of function update_inve
	
function noteDetails( $runat='',$order_id='',$product_name='',$note_name='',$note_content='',$contact_id='',$product_id='' ){
		  ob_start();
		  switch($runat){
			case 'local':
			$sql = "Select * from ".TBL_NOTES." where product_id = '$product_id'";	
			$result =  $this->db->query($sql,__FILE__,__LINE__); ?>
			<div class="profile_box1" style="font-weight:bold;margin-left:16px;width:621px;">
				<a id="link_img<?php echo $product_id;?>" style="color:#FF0000; font-size:15px" onClick="javascript: if(this.innerHTML =='+ Notes'){
																		this.innerHTML = '- Notes';
																		document.getElementById('note_details_<?php echo $product_id; ?>').style.display = 'block';
																		}
																		else {
																		this.innerHTML = '+ Notes';
																		document.getElementById('note_details_<?php echo $product_id; ?>').style.display = 'none';
																	}">+ Notes</a>
				<a onClick="document.getElementById('div_add_note_<?php echo $product_id; ?>').style.display='block';"
                   ondblclick="document.getElementById('div_add_note_<?php echo $product_id; ?>').style.display='none';" style="font-weight:bold;margin-left:104px;">Add note</a>		
			</div> 
			<div id="div_add_note_<?php echo $product_id; ?>" class="contact_form" style="display:none;margin-left:35px;">
				<table class="table">
                    <tr>
                        <td>Note Type :</td>
                        <td>
                            <select name="note_name" id="note_name<?php echo $product_id;?>">
                               <option value="">-select-</option>
							   <?php 
							   $sql_depart = "Select * from tbl_usergroup";
							   $result_depart = $this->db->query($sql_depart,__FILE__,__LINE__);
							   while($row_depart=$this->db->fetch_array($result_depart)){?>
							   		<option value="<?php echo $row_depart['group_name']; ?>"><?php echo $row_depart['group_name']; ?></option>
							   <?php } ?>
                            </select>
                        </td>
                    </tr>
					<tr>
						<td>Notes : </td>
						<td><input type="text" name="note_content" id="note_content<?php echo $product_id;?>" /></td>
					</tr>
					<?php 
					$sql_order = "Select * from ".erp_ORDER." where order_id = '$order_id'";
					$result_order = $this->db->query($sql_order,__FILE__,__LINE__);
					$row_order=$this->db->fetch_array($result_order); ?>
					<tr>
						<td><input type="button" name="add_note" id="add_note" value="Add"
                                   onclick="javascript:workorder.noteDetails('server',
                                                                             '<?php echo $order_id; ?>',
                                                                             '<?php echo $product_name; ?>',
                                                                             document.getElementById('note_name<?php echo $product_id;?>').value,
                                                                             document.getElementById('note_content<?php echo $product_id;?>').value,
                                                                             '<?php echo $row_order[contact_id];?>',
                                                                             '<?php echo $product_id; ?>',
                                                                             {preloader:'prl',
                                                                             onUpdate:function(response,root){
																			 document.getElementById('div_add_note_<?php echo $product_id; ?>').style.display='none';
                                                                             document.getElementById('note_div<?php echo $product_id;?>').innerHTML= response;
																			 document.getElementById('link_img<?php echo $product_id;?>').innerHTML= '- Notes';
																			 document.getElementById('note_details_<?php echo $product_id; ?>').style.display='block';
                                                                             }});" />
                        </td>
                        <td>&nbsp;</td>
					</tr>
				</table>
			</div>
			<div id="note_details_<?php echo $product_id; ?>"  class="contact_form" style="display:none;margin-left:35px;">
				<table width="100%">
					<?php while($row = $this->db->fetch_array($result)){ 
							
							$sql_name = "Select * from ".TBL_USER." where user_id = '$_SESSION[user_id]'";
							$result_name = $this->db->query($sql_name,__FILE__,__LINE__);
							$row_name=$this->db->fetch_array($result_name); ?>
							<tr>
								<th><?php echo $row['note_type'].' : '; ?></th>
								<td><?php echo $row['note_content'].'........................'.$row_name['first_name'].' '.$row_name['last_name']; ?></td>
							</tr>
							<?php				 
					 } //end of while ?>
				</table>
			</div>
			<?php
			break;
			case 'server':
				$insert_sql_array = array();				
				$insert_sql_array[note_type] = $note_name;
				$insert_sql_array[note_content] = $note_content;
				$insert_sql_array[contact_id] = $contact_id;	
			    $insert_sql_array[product_id] = $product_id;
				
				$this->db->insert(erp_notes,$insert_sql_array);
				echo $this->noteDetails('local',$order_id,$product_name,'','','',$product_id);
			break;
			} //end of switch
		    $html = ob_get_contents();
		    ob_end_clean();
		    return $html;
	} // end of function noteDetails()		
      
	  function showTask($gtid,$display=''){
		   $sql_g ="Select a.*,b.* from ".erp_GLOBAL_TASK." a, ".erp_USERGROUP." b where a.department_id = b.group_id and a.global_task_id  = '$gtid'";
		   $result_g = $this->db->query($sql_g,__FILE__,__LINE__);
		   $row_g = $this->db->fetch_array($result_g);  ?>			
			<table width="100%">
				<tr>
					<?php if($display == "show_dropdown") { ?>
					<td><div id="div_preview_task_option"><?php //echo $this->returnTaskSelectionOptions($gtid);?></div></td> 
					<? } ?>
					<th style="color:#FF0000"><?php echo $row_g['group_name'];?></th>
					<th>&nbsp; -  &nbsp;</th>
					<th style="color:#999999"><?php echo $row_g['name']; ?></th>	   
				</tr>
			</table>
		<?php 
		}//End of function showTask()
		
	function returnTaskSelectionOptions($gtid=''){
			ob_start();
			$sql = "Select * from tbl_global_task_status where global_task_id = '$gtid'";
			$result = $this->db->query($sql,__FILE__,__LINE__); ?>
			<select name="preview_task_option" id="preview_task_option" style="width:100%">
				<option value="">-Select-</option>
				<?php while($row = $this->db->fetch_array($result)){ ?>
				<option value="<?php echo $row['global_task_status_id']; ?>"><?php echo $row['global_task_status_name']; ?></option>
				<?php } ?>
			</select>
			<?php
			 $html = ob_get_contents();
			 ob_end_clean();
			 return $html;	
	 }//End of function returnTaskSelectionOptions
	
	function getShippingName($shipping_charge = ''){
		switch($shipping_charge){
			 case "01":
				 $shipping_name = "Next Day Air";
			 break;
			 case "02":
				 $shipping_name = '2nd Day Air';
			 break;
			 case "03":
				 $shipping_name = 'Ground';
			 break;
			 case "04":
				 $shipping_name = 'Free Shipping';
			 break;		
		}				
        return $shipping_name;
	}
	
	  function updatefield($shipping_charge='',$old_shipping_charge='',$multiplier='',$old_multiplier='',$total='',$choice='',$auto_shipping=''){
	      ob_start();
              $auto_shipping_flag = 0;
			  $ups = new ups();
              
              $shipping_type = $shipping_charge;
                  switch( $shipping_charge ){
                     case "01":
                         $shipping_charge = '75';
                     break;
                     case "02":
                         $shipping_charge = '50';
                     break;
                     case "03":
                         $shipping_charge = '30';
                     break;
					 case "04":
                         $shipping_charge = '0';
                     break;

                 }
				// echo $shipping_charge.'aaaaaaa<br>';
                $shipping_charge=  $ups->estamate_shipping_by_module("order", $_REQUEST['order_id'], $shipping_type);
				
				if($auto_shipping != ''){
					$shipping_charge = $auto_shipping;
					$auto_shipping_flag = 1;
				}
				// echo $shipping_charge.'aaaaaaa<br>';
                //print_r($shipping_charge);
	      switch($choice){
			 case 'shipping':
				 $update_sql_array = array();				
				 $update_sql_array["shipment_type"] = $shipping_type;
                 $update_sql_array["shipping_charges"] = $shipping_charge;
				 $update_sql_array["auto_shipping_charge"] = $auto_shipping_flag;
				 $this->db->update(erp_ORDER,$update_sql_array,'order_id',$_REQUEST[order_id]);
			 break;
			 case 'multiplier':
				 $update_sql_array = array();				
				 $update_sql_array[multiplier] = $multiplier;
				 $this->db->update(erp_ORDER,$update_sql_array,'order_id',$_REQUEST[order_id]);
			 break;
		   }  /////end of switch
		      $this->total_price = ($this->total_price + $shipping_charge) * $multiplier;
		 
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	  }  ////////////end of function updatefield
	  
          function calculate_tax( $order_id , $pretotal ){
               $sql = "SELECT d.*
						FROM `erp_order`a
						JOIN module_address b ON a.shipping_address = b.address_id
						JOIN zip_code c ON b.zip = c.zip_code  
						JOIN accounting_tax d ON LOWER( c.state_prefix ) = LOWER( d.state ) 
						AND LOWER( c.county ) = LOWER( d.county )
						WHERE order_id = '$order_id'";
             $tax_info = $this->db->fetch_assoc($this->db->query($sql));
             $check_taxexempt = $this->db->fetch_assoc($this->db->query("SELECT b.text_exempt FROM erp_order a JOIN erp_contactscreen_custom b ON a.`vendor_contact_id` = b.contact_id WHERE a.order_id = '$order_id'"));
             if( $check_taxexempt != FALSE ){
                 if( strtolower($check_taxexempt["text_exempt"]) == "yes"){
                     $tax_info = $this->db->fetch_assoc($this->db->query("SELECT * FROM accounting_tax WHERE county = 'Tax Exempt'"));
                 }
             }
             if( $tax_info == FALSE ){
                 $tax_info = $this->db->fetch_assoc($this->db->query("SELECT * FROM accounting_tax WHERE county = 'Out Of State'"));
             }
             $return = array();
             $return["atid"] = $tax_info["atid"];
             $return["state_tax"] = $pretotal * ( $tax_info["state_rate"] / 100 );
             $return["county_tax"] = $pretotal * ( $tax_info["county_rate"] / 100 );
             $return["stadium_tax"] = $pretotal * ( $tax_info["stadium_rate"] / 100 );
			 $return["total_tax"] = $pretotal * ( $tax_info["total_rate"] / 100 );
             return $return;
          }
          
          
	  function calculate($total='',$shipping_charge='',$multiplier='',$choice=''){
	      ob_start();
		      $sql = "Select shipping_charges,total_tax,multiplier from ".erp_ORDER." where order_id = '$_REQUEST[order_id]'";
			  $result = $this->db->query($sql,__FILE__,__LINE__);
			  $row = $this->db->fetch_array($result);
                          $total = ($total + $row[shipping_charges]) * $row[multiplier];
                          $pretotal = $total;
                          $tax_info = $this->calculate_tax( $_REQUEST['order_id'] , $pretotal );
                          $total = round($total,2) + round($tax_info["total_tax"],2);
                          
			  if($choice == 'grand_total'){
			  echo '$'. number_format($total,2);}
			  else{
			  	echo '$'. number_format($tax_info["total_tax"],2);
			  }
						  $update_sql_array = array();				
						  $update_sql_array[grant_total] = $total;
                          $update_sql_array['sub_total'] = round($pretotal,2);
                          $update_sql_array['state_tax'] = $tax_info["state_tax"];
                          $update_sql_array['county_tax'] = $tax_info["county_tax"];
                          $update_sql_array['stadium_tax'] = $tax_info["stadium_tax"];
                          $update_sql_array['total_tax'] = round($tax_info["total_tax"],2);
                          
			  $this->db->update(erp_ORDER,$update_sql_array,'order_id',$_REQUEST[order_id]);
		  
		  $html=ob_get_contents();
		  ob_end_clean();
		  return $html;
	  }  ////////////end of function calculate
	  
	  function deletethis( $product_id='', $order_id='', $choice='', $workorder_id='', $type='' ){
	      ob_start();
		  switch($choice)
		  {
		   case 'option_type':
		   
		      $sql_up="select * from ".erp_PRODUCT_ORDER." where order_id='$order_id'";
			  $result_up=$this->db->query($sql_up,__FILE__,__LINE__);
			  if( $this->db->num_rows($result_up) == 0 ){
			   	 $update_sql_array = array();				
				 $update_sql_array['grant_total'] = '';
				 $update_sql_array['total_tax'] = '';
				  $update_sql_array['shipping_charges'] = '';
				 $this->db->update(erp_ORDER,$update_sql_array,'order_id',$order_id);
			  }
		  
		      $sql="SELECT workorder_id FROM ".erp_PRODUCT_ORDER." WHERE gp_id = '$workorder_id'";
			  $result=$this->db->query($sql,__FILE__,__LINE__);
			  if( $this->db->num_rows($result) > 0 ){
				  while( $row=$this->db->fetch_array($result) ){
					 $sub_product_id = $row[workorder_id];
					 
					 $sql="DELETE FROM ".erp_WORK_ORDER." WHERE order_id = '$order_id' AND product_id = '$sub_product_id'";
					 $this->db->query($sql,__FILE__,__LINE__);
					 
					 $sql="DELETE FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' AND product_id = '$sub_product_id'";
			         $this->db->query($sql,__FILE__,__LINE__);
					 
					  $sql="DELETE FROM ".TBL_NOTES." WHERE product_id = '$sub_product_id'";
			          $this->db->query($sql,__FILE__,__LINE__);
					  
					  $sql="DELETE FROM ".erp_ASSIGN_FCT." WHERE product_id = '$order_id' AND module_id = '$sub_product_id'";
			 		  $this->db->query($sql,__FILE__,__LINE__);
				  }
			   }
			   
			  $sql="DELETE FROM ".erp_PRODUCT_ORDER." WHERE workorder_id = '$workorder_id' OR gp_id = '$workorder_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
			  
			  $sql="DELETE FROM ".erp_WORK_ORDER." WHERE order_id = '$order_id' AND product_id = '$workorder_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
			  
			  $sql="DELETE FROM ".erp_SIZE." WHERE order_id = '$order_id' AND product_id = '$workorder_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
			  
			  $sql="DELETE FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' AND product_id = '$workorder_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
			  
			  $sql="DELETE FROM ".TBL_NOTES." WHERE product_id = '$workorder_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
			  
			  $sql="DELETE FROM ".erp_INVENTORY_PRICE." WHERE work_orderid = '$workorder_id' OR sub_product_id = '$workorder_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
			  
			  $sql="DELETE FROM ".erp_ASSIGN_FCT." WHERE product_id = '$order_id' AND module_id = '$workorder_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
		  ?>
		  <script>
			 window.location = "order.php?order_id=<?php echo $order_id; ?>";
		  </script>
		  <?php 
		break;
		case 'delete':
		    $sql_up = "UPDATE ".erp_WORK_ORDER." Set $type = '' WHERE product_id = '$workorder_id' and order_id = '$order_id'";
		    $this->db->query($sql_up,__FILE__,__LINE__);
		  
		    $sql="DELETE FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' AND product_id = '$workorder_id' AND option_type = '$type'";
			$this->db->query($sql,__FILE__,__LINE__);
			
			$sql_del = "DELETE FROM ".erp_INVENTORY_PRICE." WHERE order_id = '$order_id' AND work_orderid = '$workorder_id' AND inventory_type = '$type'";
			$this->db->query($sql_del,__FILE__,__LINE__);
		  
		 break;
		 }
		  $html=ob_get_contents();
		  ob_end_clean();
		  return $html;
	  }  ////////////end of function deletethis
	  
	  function edit_value($shipping_charge=''){
	      ob_start(); 
			 if($shipping_charge!=''){
				 echo '$'.$shipping_charge; 
			 }else{
			  	echo '$0';}
						   
		  $html=ob_get_contents();
		  ob_end_clean();
		  return $html;
	  }  /////end of function edit_value
	  
	  function returnLink( $variable='', $contact_id='', $div_id='', $choice='', $order_id='', $people_contact_id='', $workorder_id='',$size_alot_id='',$base_per='' ){
		  ob_start();
		  switch($choice) {
		     case 'printer_edit':
			 	
				if($variable !=''){
				$sql = "SELECT printer FROM ".erp_PRINTER_PAPER." WHERE id = '$variable'";
				$result = $this->db->query($sql,__FILE__,__LINE__);
				$row = $this->db->fetch_array($result); ?>
					  <a href="javascript:void(0);"
					  		onclick="javascript: workorder.showdropdown('<?php echo $variable; ?>',
																		'<?php echo $contact_id; ?>',
																		'<?php echo $div_id; ?>',
																		'<?php echo $choice; ?>',
																		'<?php echo $order_id;?>',
																		'<?php echo $row[printer]; ?>',
																		'<?php echo $people_contact_id; ?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					<?php echo $row[printer]; ?></a>
					<?php }
					
				 else{?>
						<a href="javascript:void(0);" 
							onclick="javascript: workorder.showdropdown('',
																		'<?php echo $contact_id; ?>',
																		'<?php echo $div_id; ?>',
																		'<?php echo $choice; ?>',
																		'<?php echo $order_id;?>',
																		'',
																		'<?php echo $people_contact_id; ?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					<?php echo 'N/A'; ?>
					</a> <?php }
			 break;
			 case 'fabric_edit':
			 	
				if($variable !=''){ ?>
					  <a href="javascript:void(0);"
					  		onclick="javascript: workorder.showTextBox('<?php echo $variable; ?>',
																	    '<?php echo $contact_id; ?>',
																	    '<?php echo $div_id; ?>',
																	    '<?php echo $choice; ?>',
																	    '<?php echo $order_id; ?>',
																		'',
																		'<?php echo $people_contact_id; ?>',
																		'',
															 			{ target: '<?php echo $div_id; ?>'} ); ">
					<?php echo $variable; ?></a>
					<?php }
				 else{?>
						<a href="javascript:void(0);" 
							onclick="javascript: workorder.showTextBox('',
																	    '<?php echo $contact_id; ?>',
																	    '<?php echo $div_id; ?>',
																	    '<?php echo $choice; ?>',
																	    '<?php echo $order_id; ?>',
																		'',
																		'<?php echo $people_contact_id; ?>',
																		'',
															 			{ target: '<?php echo $div_id; ?>'} ); ">
					<?php echo 'N/A'; ?>
					</a><?php }
			 break;
			 
		     case 'people':
			        $sql_name = "Select * from ".TBL_INVE_CONTACTS." where company = '$contact_id' and contact_id='$variable' and type='People'";
					$result_name = $this->db->query($sql_name,__FILE__,__lINE__);
					$row_name=$this->db->fetch_array($result_name);

			        if($variable !=''){
					 ?>
					  <a href="javascript:void(0);" 
							onclick="javascript: workorder.showdropdown('<?php echo $variable; ?>',
																		'<?php echo $contact_id; ?>',
																		'<?php echo $div_id; ?>',
																		'<?php echo $choice; ?>',
																		'<?php echo $order_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					<?php echo $row_name['first_name'].' '.$row_name['last_name']; ?>
					<br/></a>
					<?php
                        
					
						$sql_phone = "Select number from ".CONTACT_PHONE." where contact_id = '$variable'";
					    $result_phone = $this->db->query($sql_phone,__FILE__,__lINE__);
					    $row_phone=$this->db->fetch_array($result_phone);
						if($row_phone['number']){ 	
						echo substr($row_phone['number'], 0, 3).'-'.substr($row_phone['number'], 3, 3).'-'.substr($row_phone['number'], 6, 4).'<br/>';
						}
					
						$sql_email = "Select email from ".CONTACT_EMAIL." where contact_id = '$variable'";
					    $result_email = $this->db->query($sql_email,__FILE__,__lINE__);
					    $row_email=$this->db->fetch_array($result_email);
						if($row_email['email']){
							$email = array();
							$email = explode(",",$row_email['email']);
							if($email){
								for($i=0;$i< count($email);$i++){ ?>
									<a href="<?php echo 'mailto:'.$email[$i]; ?>"><?php echo $email[$i]; ?></a><br />
								<?php }
							 }
						    else { ?>
								<a href="<?php echo 'mailto:'.$row_email['email']; ?>"><?php echo $row_email['email']; ?></a>
						<?php } } }
				  else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: workorder.showdropdown('N/A',
																		'<?php echo $contact_id; ?>',
																		'<?php echo $div_id; ?>',
																		'<?php echo $choice; ?>',
																		'<?php echo $order_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			  break;
		     case 'address':
				if($variable !=''){ 
					$sql_address = "Select * from ".TBL_MODULE_ADDRESS." where module_id = '$order_id' and address_id='$people_contact_id'";
	                $result_address = $this->db->query($sql_address,__FILE__,__lINE__);			   
	                $row_address=$this->db->fetch_array($result_address);				
					?>
					District Office :<a href="javascript:void(0);" 
							onclick="javascript: workorder.Update_Address('local',
																	      '<?php  echo $people_contact_id;?>',
																		  '<?php echo $order_id;?>',
																		  '','','','','',
																		  'shipping_address',
																	      {preloader:'prl',
                                                                          onUpdate:function(response,root){                                                                  		 document.getElementById('div_order').innerHTML=response;
																         document.getElementById('div_order').style.display='';
																	   }}); ">
					<?php echo '<br>'.$variable.'<br>'.$row_address[city].' '.$row_address[state].' '.$row_address[zip].'<br>'.$row_address[country]; ?></a><?php 
				}
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: workorder.Update_Address('local',
																		  '',
																	      '<?php echo $order_id;?>',
																		  '','','','','',
																		  'shipping_address',
																		  {preloader:'prl',
                                                                          onUpdate:function(response,root){                                                                  		 document.getElementById('div_order').innerHTML=response;
																         document.getElementById('div_order').style.display='';
																	   }}); ">
					N/A</a>
				   <?php 
				     }
			 break;
			 case 'billing_address':
				if($variable !=''){ 
					$sql_address = "Select * from ".TBL_MODULE_ADDRESS." where module_id = '$order_id' and address_id='$people_contact_id'";
	                $result_address = $this->db->query($sql_address,__FILE__,__lINE__);			   
	                $row_address=$this->db->fetch_array($result_address);	
					?>
					District Office :<a href="javascript:void(0);" 
							onclick="javascript: workorder.Update_Address('local',
																	      '<?php  echo $people_contact_id;?>',
																		  '<?php echo $order_id;?>',
																		  '','','','','',
																		  'billing_address',
																	      {preloader:'prl',
                                                                          onUpdate:function(response,root){                                                                  		 document.getElementById('div_order').innerHTML=response;
																         document.getElementById('div_order').style.display='';
																	   }}); ">
					<?php echo '<br>'.$variable.'<br>'.$row_address[city].' '.$row_address[state].' '.$row_address[zip].'<br>'.$row_address[country]; ?></a><?php 
				}
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: workorder.Update_Address('local',
																	    '',
																		'<?php echo $order_id;?>',
																		  '','','','','',
																		'billing_address',
																	    {preloader:'prl',
                                                                          onUpdate:function(response,root){                                                                  		 document.getElementById('div_order').innerHTML=response;
																         document.getElementById('div_order').style.display='';
																	   }}); ">
					N/A</a>
				   <?php 
				     }
			 break;
			 case 'product_name':
				if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: workorder.showdropdown('<?php echo $variable; ?>',
																	    '<?php echo $contact_id; ?>',
																	    '<?php echo $div_id; ?>',
																	    '<?php echo $choice; ?>',
																	    '<?php echo $order_id; ?>',
																		'',
																		'',
																		'',
																		'<?php echo $workorder_id; ?>',
																	   { target: '<?php echo $div_id; ?>'}
																	   ); ">
					<?php echo $variable; ?></a>
					<?php }
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: workorder.showdropdown('N/A',
																		'<?php echo $contact_id; ?>',
																		'<?php echo $div_id; ?>',
																		'<?php echo $choice; ?>',
																		'<?php echo $order_id; ?>',
																		'',
																		'',
																		'',
																		'<?php echo $workorder_id; ?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			 break;
			 case 'unit':
			    
				$sql_q = "Select * from ".erp_SIZE." where product_id ='$workorder_id'";
	            $result_q = $this->db->query($sql_q,__FILE__,__LINE__);
	            while($row_q = $this->db->fetch_array($result_q)){
				      $tot_quant += $row_q[quantity];
				}
				if($size_alot_id == ''){
			    $sql = "Select * from ".erp_SIZE." where product_id ='$workorder_id'";}
				else{
				$sql = "Select * from ".erp_SIZE." where product_id ='$workorder_id' and size_alot_id='$size_alot_id'";
				}
				
	            $result = $this->db->query($sql,__FILE__,__LINE__);
				$i = 1;
	            while($row = $this->db->fetch_array($result)){
				$s =explode("_",$row[size]);
				$div = $div_id.'base'.$s[1];
				?>
				<table class="table">
				<?php if($base_per == 'base' || $base_per == ''){?>
				    <tr>
					<td width="50%" align="left">
					 <span id="<?php echo $div; ?>">
					     <?php if( $row[base_price] != 0 ){ ?>
								 $<a href="javascript:void(0);" 
										onclick="javascript: workorder.showTextBox('<?php echo number_format($row[base_price],2); ?>',
																					'<?php echo $contact_id; ?>',
																					'<?php echo $div_id.','.$i; ?>',
																					'<?php echo $choice; ?>',
																					'<?php echo $order_id; ?>',
																					'<?php echo $row[size_alot_id]; ?>',
																					'<?php echo $people_contact_id; ?>',
																					'<?php echo $s[1]; ?>',
																					'<?php echo $workorder_id; ?>',
																					'<?php echo $row[size_alot_id];?>',
																			{ target: '<?php echo $div; ?>'}
																				   ); "> <?php echo number_format($row[base_price],2); ?> </a>
					   <?php
				            } else { ?>
								  $<a href="javascript:void(0);" 
										onclick="javascript: workorder.showTextBox('<?php echo number_format($row[base_price],2); ?>',
																					'<?php echo $contact_id; ?>',
																					'<?php echo $div_id.','.$i; ?>',
																					'<?php echo $choice; ?>',
																					'<?php echo $order_id; ?>',
																					'<?php echo $row[size_alot_id]; ?>',
																					'<?php echo $people_contact_id; ?>',
																					'<?php echo $s[1]; ?>',
																					'<?php echo $workorder_id; ?>',
																					'<?php echo $row[size_alot_id];?>',
																			{ target: '<?php echo $div; ?>'}
																					); "> <?php echo number_format($row[base_price],2); ?> </a>
					<?php } ?>
					   </span>	
					 </td>
					 <td width="50%" align="left"><?php if($size_alot_id == '' && $base_per == ''){echo 'Base Price'; }?></td>
					</tr>
					<?php }?>
					<?php if($base_per == 'per_size' || $base_per == ''){ //echo $row[per_size_price];
					if( $row[per_size_price] > 0 ){ ?>	
					<tr>
					<td width="50%" align="left">
					<span id="<?php echo $div_id.'per'.$s[1]; ?>">
					     <?php if( $row[per_size_price] != 0 ){ ?>
							 
								 $<a href="javascript:void(0);" 
										onclick="javascript: workorder.showTextBox('<?php echo number_format($row[per_size_price],2); ?>',
																					'<?php echo $contact_id; ?>',
																					'<?php echo $div_id.','.$i; ?>',
																					'unit_per',
																					'<?php echo $order_id; ?>',
																					'<?php echo $row[size_alot_id]; ?>',
																					'<?php echo $people_contact_id; ?>',
																					'<?php echo $s[1]; ?>',
																					'<?php echo $workorder_id; ?>',
																					'<?php echo $row[size_alot_id];?>',
																			{ target: '<?php echo $div_id.'per'.$s[1]; ?>'}
																				   ); "> <?php echo number_format($row[per_size_price],2); ?> </a>
					   <?php
				            } else { ?>
								  $<a href="javascript:void(0);" 
										onclick="javascript: workorder.showTextBox('<?php echo number_format($row[per_size_price],2); ?>',
																					'<?php echo $contact_id; ?>',
																					'<?php echo $div_id.','.$i; ?>',
																					'unit_per',
																					'<?php echo $order_id; ?>',
																					'<?php echo $row[size_alot_id]; ?>',
																					'<?php echo $people_contact_id; ?>',
																					'<?php echo $s[1]; ?>',
																					'<?php echo $workorder_id; ?>',
																					'<?php echo $row[size_alot_id];?>',
																			{ target: '<?php echo $div_id.'per'.$s[1]; ?>'}
																					); "> <?php echo number_format($row[per_size_price],2); ?> </a>
					<?php } ?>
					   </span>
					 </td>
					 <td width="50%" align="left"><?php if($size_alot_id == '' && $base_per == ''){echo $s[1].' Size';} ?></td>
					</tr>
					 <?php } } ?>
					  <?php if($size_alot_id == '' && $base_per == ''){?>
					<tr>
					 <td colspan="2">
					 <?php echo $this->showCostIncrease( $workorder_id, $contact_id, $order_id, $s[1],$div_id );?>
					 </td>
					</tr>
					<?php } ?>
				  </table>
				   <?php 
				    $i++; }//end of while
			 break;
			 case 'quantity':
			    
				$sql = "Select distinct * from ".erp_SIZE." where product_id ='$workorder_id'";
	            $result = $this->db->query($sql,__FILE__,__LINE__);
				$i = 1;
	            while($row = $this->db->fetch_array($result)){
				      $count = 0;
				      $siz = explode('_',$row[size]);
				      $s = strtolower($siz[1]);
				      $sql_i = "Select inventory, cost_increase,price_id from ".erp_INVENTORY_PRICE." where order_id = '$order_id' and (work_orderid = '$workorder_id' or sub_product_id = '$workorder_id') and size_type = '$s'";
			          $result_i = $this->db->query($sql_i,__FILE__,__LINE__);
			          while($row_i = $this->db->fetch_array($result_i)){
				        $count++;
				       }?>
				   <table class="table" style="height:<?php echo (($count+2)*28.3); ?>px">
				     <tr>
						<td>
						   <span id="<?php echo $div_id.''.$i; ?>">
						     <?php if( $row[quantity] != '0' ){?>
							 <a href="javascript:void(0);" 
									onclick="javascript: workorder.showTextBox('<?php echo $row[quantity]; ?>',
																				'<?php echo $contact_id; ?>',

																				'<?php echo $div_id.','.$i; ?>',
																				'<?php echo $choice; ?>',
																				'<?php echo $order_id; ?>',
																				'<?php echo $row[size_alot_id]; ?>',
																				'<?php echo $people_contact_id; ?>',
																				'<?php echo $row[size]; ?>',
																				'<?php echo $workorder_id; ?>',
																	  { target: '<?php echo $div_id.''.$i; ?>'}
																			   ); "> <?php echo $row[quantity]; ?> </a>
			  <?php
						   } else { ?>
							  <a href="javascript:void(0);" 
									onclick="javascript: workorder.showTextBox('<?php echo $row[quantity]; ?>',
																				'<?php echo $contact_id; ?>',
																				'<?php echo $div_id.','.$i; ?>',
																				'<?php echo $choice; ?>',
																				'<?php echo $order_id; ?>',
																				'<?php echo $row[size_alot_id]; ?>',
																				'<?php echo $people_contact_id; ?>',
																				'<?php echo $row[size]; ?>',
																				'<?php echo $workorder_id; ?>',
																	  { target: '<?php echo $div_id.''.$i; ?>'}
																				); "> N/A </a>
						  </span><br/>
					   <?php } ?>
						 </td>
						 <td>&nbsp;</td>
					  </tr>
				   </table>
				<?php
				    $i++; }//end of while
			 break;
			 case 'size':
			    
			    $sql = "Select size,size_alot_id,quantity from ".erp_SIZE." where product_id ='$workorder_id'";
				$result = $this->db->query($sql,__FILE__,__LINE__);
				$i = 1;
	            while($row = $this->db->fetch_array($result)){
				      $count = 0;
				      $siz = explode('_',$row[size]);
				      $s = strtolower($siz[1]);
				      $sql_i = "Select inventory, cost_increase,price_id from ".erp_INVENTORY_PRICE." where order_id = '$order_id' and (work_orderid = '$workorder_id' or sub_product_id = '$workorder_id') and size_type = '$s'";
			          $result_i = $this->db->query($sql_i,__FILE__,__LINE__);
			          while($row_i = $this->db->fetch_array($result_i)){
				        $count++;
				       }
				if( $row[size] != '' ){
				$size = explode('_',$row[size]); ?>
				<span id="<?php echo $div_id.''.$i; ?>">
				  <table class="table" style="height:<?php echo (($count+2)*28.3); ?>px">
				     <tr>
						<td>
							<a href="javascript:void(0);" 
									onclick="javascript: workorder.showdropdown('<?php echo $row[size]; ?>',
																				'<?php echo $contact_id; ?>',
																				'<?php echo $div_id.','.$i; ?>',
																				'<?php echo $choice; ?>',
																				'<?php echo $order_id; ?>',
																				'<?php echo $row[size_alot_id]; ?>',
																				'<?php echo $people_contact_id; ?>',
																				'<?php echo $row[quantity]; ?>',
																				'<?php echo $workorder_id; ?>',
																	  { target: '<?php echo $div_id.''.$i; ?>'});
																	"> <?php echo $size[1]; ?> </a>
					    </td>
						 <td>&nbsp;</td>
					  </tr>
				   </table>
				</span>
				<?php
				} else { ?>
				    <span id="<?php echo $div_id.''.$i; ?>">
					  <a href="javascript:void(0);" 
							onclick="javascript: workorder.showdropdown('N/A',
																		'<?php echo $contact_id; ?>',
																		'<?php echo $div_id.','.$i; ?>',
																		'<?php echo $choice; ?>',
																		'<?php echo $order_id; ?>',
																		'<?php echo $row[size_alot_id]; ?>',
																		'<?php echo $people_contact_id; ?>',
																		'<?php echo $row[quantity]; ?>',
																		'<?php echo $workorder_id; ?>',
															  { target: '<?php echo $div_id.''.$i; ?>'});
															">
					  N/A</a>
					</span><br/>
				   <?php 
				     }
				   $i++; }//end of while
			 break;
			 case 'priceunit':
			    
			    $sql = "Select size_alot_id, size, per_size_price, base_price from ".erp_SIZE." where product_id ='$workorder_id'";
	            $result = $this->db->query($sql,__FILE__,__LINE__);
	            while($row = $this->db->fetch_array($result)){
				  $count = 0;
				  $siz = explode('_',$row[size]);
				  $s = strtolower($siz[1]);
				  $tot_price = 0;
				  
				  $sql_p = "Select cost_increase from ".erp_INVENTORY_PRICE." where order_id = '$order_id' and (work_orderid = '$workorder_id' or sub_product_id = '$workorder_id') and size_type = '$s'";
				  $result_p = $this->db->query($sql_p,__FILE__,__LINE__);
				  while( $row_p = $this->db->fetch_array($result_p) ){
				         $tot_price += $row_p[cost_increase];
						 $count++;
				  }
				  $tot_price = $tot_price + $row[per_size_price] + $row[base_price];
				  $this->db->update(erp_SIZE, array('unit_price' => $tot_price ), 'size_alot_id', $row[size_alot_id]); ?>
					  <table width="100%" style="height:<?php echo (($count+2)*28.3); ?>px">
						 <tr>
							<td>
							  <span id="<?php echo $div_id.'each_price'.$i; ?>">
							    $<?php echo number_format($tot_price,2); ?>
							  </span><br/>
							</td>
							 <td>&nbsp;</td>
						  </tr>
					   </table>
			  <?php 
				  }//end of while
			 break;
			 
			 case 'total':
                	 
				$sql = "Select size_alot_id, size, per_size_price, base_price, quantity from ".erp_SIZE." where product_id ='$workorder_id'";
	            $result = $this->db->query($sql,__FILE__,__LINE__);
	            while($row = $this->db->fetch_array($result)){
				  $count = 0;
				  $siz = explode('_',$row[size]);
				  $s = strtolower($siz[1]);
				  $tot_price = 0;
				  $i = 2;
				  $sql_p = "Select cost_increase from ".erp_INVENTORY_PRICE." where order_id = '$order_id' and (work_orderid = '$workorder_id' or sub_product_id = '$workorder_id') and size_type = '$s'";
				  $result_p = $this->db->query($sql_p,__FILE__,__LINE__);
				  while( $row_p = $this->db->fetch_array($result_p) ){
				         $tot_price += $row_p[cost_increase];
						 $count++;
				  }
				  $tot_price = $tot_price + $row[per_size_price] + $row[base_price];
				  $tot_price = ($tot_price * $row[quantity]);
				  
				  $this->db->update(erp_SIZE, array('total' => $tot_price ), 'size_alot_id', $row[size_alot_id]);
				  ?>
				<table width="100%" style="height:<?php echo (($count+2)*28.3); ?>px">
				 <tr>
					<td>
						<div id="<?php echo $div_id.'tot'.$i; ?>">
							<?php echo '$'.number_format($tot_price,2); ?>
						</div>
					</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					 <td colspan="2">&nbsp;</td>
				  </tr>
				  <tr>
					 <td colspan="2">&nbsp;</td>
				  </tr>
			   </table>
				<?php 
				}//end of while
			 break;
			 }    /////////////end of switch
				$html = ob_get_contents();
				ob_end_clean();
				return $html;	
	}   ////////////////end of function returnLink
	
	function show_baseprice($product_id='',$order_id='')
	{
		ob_start();
		
		$sql = "Select sum(quantity) as tot_quantity from ".erp_SIZE." where product_id ='$product_id' and order_id='$order_id'";
		$result = $this->db->query($sql,__FILE__,__LINE__); 
		$row = $this->db->fetch_array($result);
		//echo $sql;
		echo $row[tot_quantity];
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
	function showCostIncrease($workorder_id='', $product_id='', $order_id='', $size='',$div_id='',$price_id=''){
	 	ob_start();
		if($price_id== ''){
			$sql = "Select inventory, cost_increase, price_id from ".erp_INVENTORY_PRICE." where order_id = '$order_id' and ( work_orderid = '$workorder_id' or sub_product_id = '$workorder_id' ) and size_type = '$size' and cost_increase <> '0'";
		} else {
			$sql = "Select inventory, cost_increase, price_id from ".erp_INVENTORY_PRICE." where price_id='$price_id'";
		}
		 $result = $this->db->query($sql,__FILE__,__LINE__); ?>
		 <table width="100%">
		 <?php
		 while($row = $this->db->fetch_array($result)){
		 	
			 $sql_p = "Select name from ".TBL_INVENTORY_DETAILS." where inventory_id ='$row[inventory]'";
			 $result_p = $this->db->query($sql_p,__FILE__,__LINE__);
			 $row_p = $this->db->fetch_array($result_p);?>
			  <tr>
				<td width="50%" align="left">
				 <span id="<?php echo $div_id.'cost'.$row[price_id]; ?>">
				 <?php if( $row[cost_increase] !='' ){?>
				 $<a href="javascript:void(0);" 
							onclick="javascript: workorder.showTextBox('<?php echo number_format($row[cost_increase],2); ?>',
																		'<?php echo $product_id;?>',
																		'<?php echo $div_id.','.$row[price_id]; ?>',
																		'unit_cost',
																		'<?php echo $order_id; ?>',
																		'<?php echo $row[price_id]; ?>',
																		'',
																		'',
																		'<?php echo $workorder_id; ?>',
																		'<?php echo $size;?>',
															   { target:'<?php echo  $div_id.'cost'.$row[price_id]; ?>'}
																); "><?php echo number_format($row[cost_increase],2); ?></a>
				<?php } else{ ?>
					<a href="javascript:void(0);" 
								onclick="javascript: workorder.showTextBox('<?php echo number_format($row[cost_increase],2);?>',
																			'<?php echo $product_id;?>',
																			'<?php echo $div_id.','.$row[price_id]; ?>',
																			'unit_cost',
																			'<?php echo $order_id; ?>',
																			'<?php echo $row[price_id]; ?>',
																			'',
																			'',
																			'<?php echo $workorder_id; ?>',
																			'<?php echo $size;?>',
																   { target:'<?php echo  $div_id.'cost'.$row[price_id]; ?>'}
																	); "><?php echo number_format($row[cost_increase],2);?></a>
				<?php }?>
				</span>
				</td>
				<td width="50%" align="left"><?php if($price_id == ''){echo $row_p[name]; }?></td>
			  </tr> 
		 <?php } ?>
		 </table>
	  <?php
	    $html = ob_get_contents();
		ob_end_clean();
		return $html;
	
	}
	
	function Update_Address($runat,$address_id='',$order_id='',$street_address='',$city='',$state='',$zip='',$country='',$type=''){
	   ob_start();
           /*
            * Start: Tims Patch for address sync between contact and order
            */
            
           $addr_res1 = $this->db->query("SELECT * , md5( concat( `street_address` ,`city` , `state` , `zip` ) ) hash FROM module_address WHERE module_name = 'order' AND module_id = '$order_id'");
           $having = '';
           $tmp = 0;
           while( $row = $this->db->fetch_assoc($addr_res1)){
               if( $tmp != 0 ){
                   $having .= " AND ";
               } else {
                   $having = "HAVING ";
               }
               $tmp = 1;
              $having.= " hash <> '" . $row["hash"] . "'";
           }       
                      
           $vci_arr = $this->db->fetch_assoc($this->db->query("SELECT vendor_contact_id FROM erp_order WHERE order_id = '$order_id'"));
                  
            $sql1="SELECT * , md5( concat( `street_address` ,`city` , `state` , `zip` ) ) hash FROM contacts_address WHERE contact_id = '" . $vci_arr["vendor_contact_id"] . "' $having";
            $addr_res2 = $this->db->query($sql1);
            
            while($row=$this->db->fetch_assoc($addr_res2)){
                $ar1 = array();
                $ar1["module_name"] = "order";
                $ar1["module_id"] = $order_id;
                $ar1["street_address"] = $row["street_address"];
                $ar1["city"] = $row["city"];
                $ar1["state"] = $row["state"];
                $ar1["zip"] = $row["zip"];
                $ar1['country'] = $row["country"];
                $this->db->insert('module_address', $ar1 );
            }
           /*
            * End: Tims Patch for address sync between contact and order
            */
	   switch($runat){
		   case 'local' : ?>
		   <div class="prl">&nbsp;</div>
				<div id="lightbox">
					<div style="background-color:#ADC2EB; max-width:540px; min-width:500px;" align="left" class="ajax_heading">
					<?php if($address_id!=''){?>
					<div id="TB_ajaxWindowTitle">Update Address</div><?php } ?>
					
					<?php if($address_id==''){?>
					<div id="TB_ajaxWindowTitle">Set Address</div><?php } ?>
					<div id="TB_closeAjaxWindow">
					<a href="javascript:void(0)" onClick="javascript: document.getElementById('div_order').style.display='none';">
					<img border="0" src="images/close.gif" alt="close" /></a></div>
				    </div>
				<div class="white_content" style="max-width:540px; min-width:500px;"> 
				<div style="padding:20px;" class="form_main">
				<form metdod="post" enctype="multipart/form-data">
						<table class="" id="tbl_<?php echo $i; ?>">
						 <tr>
							<?php
							$sql_assign_add = "SELECT * FROM erp_order  WHERE order_id = '$order_id'";
				            $result_assign_add = $this->db->query($sql_assign_add,__FILE__,__LINE__);
						    $row_assign_add = $this->db->fetch_array($result_assign_add);
							
							$sql_add = "SELECT * FROM module_address  WHERE module_id = '$order_id' AND module_name = 'order'";
				            $result_add = $this->db->query($sql_add,__FILE__,__LINE__);
							?>
							<th>Address :</th>
							  <td>
							  <select name="street_address" id="street_address" style="width:auto;">
									<option value="">-Select-</option>
									<?php
									while( $row_add = $this->db->fetch_array($result_add) ){
									?>
									<option value="<?php echo $row_add[address_id]; ?>"  <?php if($row_assign_add[$type]==$row_add[address_id]){echo 'selected="selected"'; }?> ><?php echo $row_add[street_address].$row_add[city].$row_add[state].$row_add[zip].$row_add[country]; ?></option>
									<?php } ?>
								  </select>
							 </td>
							 
							<tr>	
								<td colspan="2">&nbsp;</td>
								<td colspan="2" align="left">
								   <input type="button" value="OK"  style="size:auto"
								            onclick="workorder.Update_Address('server',
																			 '',
																		    '<?php echo $order_id; ?>',
																			document.getElementById('street_address').value,
																			'','','','',
																			'<?php echo $type; ?>',
																		    {preloader:'prl',
																			onUpdate: function(response,root){
																			document.getElementById('div_order').innerHTML = response;
																			document.getElementById('div_order').style.display = 'none';
																		  }});"/>
								</td>
							</tr>
						</table>
					</form>
				</div></div></div>
	
	<?php  break;
	       case 'server' :
		   
		    if($address_id){
				$update_sql = array();
				$update_sql[street_address] = $street_address;				
				$update_sql[city] = $city;
				$update_sql[state] = $state;
				$update_sql[zip] = $zip;
				$update_sql[country] = $country;
				
				$this->db->update(module_address,$update_sql,'address_id',$address_id);
			}
			else{ 
				if($street_address){

					$update_sql_array=array();
					$update_sql_array[$type] = $street_address;				
					
					$this->db->update(erp_order,$update_sql_array,'order_id',$order_id);
				}
			} ?>
		  <script>
			 window.location = "order.php?order_id=<?php echo $order_id; ?>";
		  </script>
		  <?php 
           break;
		}
	$html=ob_get_contents();
	ob_end_clean();
	return $html;
	}
	
	function showdropdown( $type='', $vendor_contact_id='', $div_id='', $choice='', $order_id='', $size_alot_id='', $contact_id='', $quantity='', $workorder_id='' ){
	 	 ob_start();
		 switch($choice) {
		   case 'printer_edit': ?>
		   	 <select name="printer" id="printer" style="width:80%" 
				  onblur="javascript:
								   var type_name = this.value;
                                workorder.getName(this.value,
                                                  'printer_name',
                                                  {onUpdate: function(response,root){
                                                  var printer = response;
                                   if(this.value!='<?php echo $type; ?>') { 
								   if(confirm('Are you sure you want to change your printer from <?php echo $size_alot_id; ?> to '+ printer)){
																																	
									workorder.update_textbox_field('printer_used',
																	type_name,
																	'<?php echo $order_id; ?>',
																	'<?php echo $vendor_contact_id;?>',
																	'<?php echo $contact_id; ?>',
															 {onUpdate: function(response,root){
											workorder.ShowRework('<?php echo $order_id; ?>',
																 '<?php echo $vendor_contact_id; ?>',
																{preloader:'prl',
																onUpdate:function(response,root){
												  
												  document.getElementById('rework<?php echo $vendor_contact_id; ?>').innerHTML= response;
												  document.getElementById('rework_link<?php echo $vendor_contact_id;?>').innerHTML='- Rework Details';
												  document.getElementById('rework_detail<?php echo $order_id.''.$vendor_contact_id; ?>').style.display = 'block';}}); 	 
											 							}});
										             } else {
														    workorder.returnLink('<?php echo $type; ?>',
																			     '<?php echo $vendor_contact_id; ?>',
																			     '<?php echo $div_id; ?>',
																			     '<?php echo $choice; ?>',
																			     '<?php echo $order_id; ?>',
																				 '<?php echo $contact_id; ?>',
																			     {target:'<?php echo $div_id; ?>', preloader: 'prl'});
													   }}
													else {
														    workorder.returnLink('<?php echo $type; ?>',
																			     '<?php echo $vendor_contact_id; ?>',
																			     '<?php echo $div_id; ?>',
																			     '<?php echo $choice; ?>',
																			     '<?php echo $order_id; ?>',
																				 '<?php echo $contact_id; ?>',
																			     {target:'<?php echo $div_id; ?>', preloader: 'prl'});
													   }}});">
													   
						<option value="">-select-</option>
						<?php
						$sql_p = "select id, printer from ".erp_PRINTER_PAPER;
						$result_p = $this->db->query($sql_p,__FILE__,__LINE__);
						while($row_p = $this->db->fetch_array($result_p)){?>
						<option value="<?php echo $row_p[id]; ?>" <?php if($type==$row_p[id]){echo 'selected="selected"'; }?>><?php echo $row_p[printer]; ?></option>
						<?php } ?>
			    </select>
				<?php
		   break;
		   case 'people': 
			     $sql_dropdown = "select * from ".TBL_INVE_CONTACTS." where type='People' and company='$vendor_contact_id'";
			    $result_dropdown = $this->db->query($sql_dropdown,__FILE__,__lINE__); 
				
				$sql = "select first_name,last_name from ".TBL_INVE_CONTACTS." where type='People' and company='$vendor_contact_id' and contact_id='$type'";
			    $result = $this->db->query($sql,__FILE__,__lINE__);
				$row=$this->db->fetch_array($result);?>
			    
				 <select name="contact_id" id="contact_id" style="width:80%" 
				  onblur="javascript: var contact_name; 
								   var type_id = this.value; 
								   workorder.getName(this.value,
								                     'contact',
												  { onUpdate: function(response,root){
													contact_name = response;
													if(contact_name!='<?php echo $type; ?>') {
													if(confirm('Are you sure you want to change your contact from <?php echo                                                                $row['first_name'].''. $row['last_name']; ?> to '+ contact_name)){
																																	
														workorder.updateOrderField(type_id,
																		'<?php echo $order_id;?>',
																		'contact_id',
														 {onUpdate: function(response,root){
														 
															workorder.returnLink(type_id,
																				'<?php echo $vendor_contact_id; ?>',
																				'<?php echo $div_id; ?>',
																				'<?php echo $choice; ?>',
																				'<?php echo $order_id; ?>',
																				type_id,
																        {target:'<?php  echo $div_id; ?>', preloader: 'prl'}); 
															 
															}});
													workorder.showCompleteContactDetails('<?php echo $order_id; ?>',
													                   {target:'div_contact_people',preloader: 'prl'});
															
										             } else {
														    workorder.returnLink('<?php echo $type; ?>',
																			     '<?php echo $vendor_contact_id; ?>',
																			     '<?php echo $div_id; ?>',
																			     '<?php echo $choice; ?>',
																			     '<?php echo $order_id; ?>',
																			     type_id,
																         {target:'<?php echo $div_id; ?>', preloader: 'prl'});
													   }}
													     else {
														    workorder.returnLink('<?php echo $type; ?>',
																			     '<?php echo $vendor_contact_id; ?>',
																			     '<?php echo $div_id; ?>',
																			     '<?php echo $choice; ?>',
																			     '<?php echo $order_id; ?>',
																			     type_id,
																         {target:'<?php echo $div_id; ?>', preloader: 'prl'});
													   }}});
													">
					<option value="" >--Select--</option>
					<?php while($row_dropdown=$this->db->fetch_array($result_dropdown)){ ?>
					<option value="<?php echo $row_dropdown[contact_id]; ?>" <?php if($row_dropdown[first_name]==$type) 
					echo 'selected="selected"';?>>
					<?php echo $row_dropdown[first_name].' '.$row_dropdown[last_name]; ?> 
					</option>
					<?php } ?>
			    </select>
				<?php
				break;
				case'address':
				   $sql_adres = "select * from ".TBL_MODULE_ADDRESS." where module_id='$order_id'";
			       $result_adres = $this->db->query($sql_adres,__FILE__,__lINE__);
				 ?>
			      <select name="address_id" id="address_id"  
				    onblur="javascript: var address_name;
					                       var type_id = this.value; 
								           workorder.getName(type_id,
								                             'address',
															 '<?php echo $order_id; ?>',
										 { onUpdate: function(response,root){
										   address_name = response; 
										   if(address_name != '<?php echo $type;  ?>'){
					                       if(confirm('Are you sure you want to change this shipping address from <?php echo $type; ?> to '+ address_name)){ 
										      workorder.updateOrderField(type_id,
																	'<?php echo $order_id;?>',
																	'shipping_address',
												 {onUpdate:function(response,root){
												  workorder.returnLink(address_name,
																	   type_id,
																	   '<?php echo $div_id; ?>',
																	   '<?php echo $choice; ?>',
																	   '<?php echo $order_id; ?>',
												 {target:'<?php  echo $div_id; ?>',preloader:'prl'});
												 }});}
											else{
												  workorder.returnLink('<?php echo $type; ?>',
																	   '<?php echo $vendor_contact_id; ?>',
																	   '<?php echo $div_id; ?>',
																	   '<?php echo $choice; ?>',
																	   '<?php echo $order_id; ?>',
												 {target:'<?php  echo $div_id; ?>',preloader:'prl'}																		   
																	   
												  );}}
												  else{
												  workorder.returnLink('<?php echo $type; ?>',
																	   '<?php echo $vendor_contact_id; ?>',
																	   '<?php echo $div_id; ?>',
																	   '<?php echo $choice; ?>',
																	   '<?php echo $order_id; ?>',
												 {target:'<?php  echo $div_id; ?>',preloader:'prl'}																		   
																	   
												  );}											
											}});">
				<option value="" >--Select--</option>
					<?php while($row_adres=$this->db->fetch_array($result_adres)){ ?>
					<option value="<?php echo $row_adres[address_id]; ?>" <?php if($row_adres[street_address]==$type) 
					echo 'selected="selected"';?>>
					<?php echo $row_adres[street_address]; ?> 
					</option>
					<?php } ?>
			    </select>
			   <?php
			   break;
			   case'billing_address':
				   $sql_adres = "select * from ".TBL_MODULE_ADDRESS." where module_id='$order_id'";
			       $result_adres = $this->db->query($sql_adres,__FILE__,__lINE__);
				 ?>
			      <select name="billing_address_id" id="billing_address_id"  
				    onblur="javascript: var address_name;
					                       var type_id = this.value; 
								           workorder.getName(this.value,
								                             'address',
															 '<?php echo $order_id; ?>',
										 { onUpdate: function(response,root){
										   address_name = response;
										    if(address_name!='<?php echo $type;  ?>'){
											if(confirm('Are you sure you want to change this billing address from <?php echo $type; ?> to '+ address_name)){
										      workorder.updateOrderField(type_id,
																	'<?php echo $order_id;?>',
																	'billing_address',
												 {onUpdate:function(response,root){
												  workorder.returnLink(address_name,
																	   type_id,
																	   '<?php echo $div_id; ?>',
																	   '<?php echo $choice; ?>',
																	   '<?php echo $order_id; ?>',
												 {target:'<?php  echo $div_id; ?>',preloader:'prl'});
											     }});}
											else{
												  workorder.returnLink('<?php echo $type; ?>',
																	   '<?php echo $vendor_contact_id; ?>',
																	   '<?php echo $div_id; ?>',
																	   '<?php echo $choice; ?>',
																	   '<?php echo $order_id; ?>',
												 {target:'<?php  echo $div_id; ?>',preloader:'prl'}																		   
																	   
												  );}}
												 else{
												  workorder.returnLink('<?php echo $type; ?>',
																	   '<?php echo $vendor_contact_id; ?>',
																	   '<?php echo $div_id; ?>',
																	   '<?php echo $choice; ?>',
																	   '<?php echo $order_id; ?>',
												 {target:'<?php  echo $div_id; ?>',preloader:'prl'}																		   
																	   
												  );} 											
											}});">
				<option value="" >--Select--</option>
					<?php while($row_adres=$this->db->fetch_array($result_adres)){ ?>
					<option value="<?php echo $row_adres[address_id]; ?>" <?php if($row_adres[street_address]==$type) 
					echo 'selected="selected"';?>>
					<?php echo $row_adres[street_address]; ?> 
					</option>
					<?php } ?>
			    </select>
			   <?php
			   break;
			   case 'product_name': 
			    $sql_dropdown = "select * from ".erp_PRODUCT;
			    $result_dropdown = $this->db->query($sql_dropdown,__FILE__,__lINE__); ?>
			 
				 <select name="product_name_id" id="product_name_id" style="width:80%" 
				  onblur="javascript: var product_name; 
								   var type_id = this.value; 
								   workorder.getName(this.value,
								                     'product_name',
												  { onUpdate: function(response,root){
													product_name = response;
													if(product_name !='<?php echo $type; ?>') {
													if(confirm('Are you sure you want to change your product from <?php echo $type; ?> to '+ product_name)){
																																	
														workorder.updateProductOrder(product_name,
																		'<?php echo $order_id;?>',
																		'product_name',
																		'<?php echo $vendor_contact_id; ?>',
																		type_id,
														 { onUpdate: function(response,root){
														 
															workorder.returnLink(product_name,
																		type_id,
																		'<?php echo $div_id; ?>',
																		'<?php echo $choice; ?>',
																		'<?php echo $order_id; ?>',
														   {target:'<?php  echo $div_id; ?>', preloader: 'prl' 
														});}});}
										  else {
												 workorder.returnLink('<?php echo $type; ?>',
															 '<?php echo $vendor_contact_id; ?>',
															 '<?php echo $div_id; ?>',
															 '<?php echo $choice; ?>',
															 '<?php echo $order_id; ?>',
												 {target:'<?php  echo $div_id; ?>', preloader: 'prl'});}
												 }
												 else {
												 workorder.returnLink('<?php echo $type; ?>',
															 '<?php echo $vendor_contact_id; ?>',
															 '<?php echo $div_id; ?>',
															 '<?php echo $choice; ?>',
															 '<?php echo $order_id; ?>',
												 {target:'<?php  echo $div_id; ?>', preloader: 'prl'});}
												 }});">
					<option value="" >--Select--</option>
					<?php while($row_dropdown=$this->db->fetch_array($result_dropdown)){ ?>
					<option value="<?php echo $row_dropdown[product_id]; ?>" <?php if($row_dropdown[product_name]==$type) 
					echo 'selected="selected"';?>>
					<?php echo $row_dropdown[product_name]; ?> 
					</option>
					<?php } ?>
			    </select>
				<?php
				break;
				case 'size': 
			       $sql_size = "Select distinct * from ".erp_PRODUCT." where product_id ='$vendor_contact_id'";
				   $result_size=$this->db->query($sql_size,__FILE__,__LINE__);
				   $div = explode(',',$div_id);
				   $size = explode('_',$type); ?>
				 <select name="size_id" id="size_id" style="width:70px" 
				  onblur="javascript: var type_id = this.value; 
				  							if(this.value !='<?php echo $type; ?>') {
											if(confirm('Are you sure you want to change this size from <?php 
													 echo $size[1]; ?> to '+ type_id)){
												 workorder.size_update_table( '<?php echo $workorder_id; ?>',
																                              '<?php echo $order_id; ?>',
																							  '<?php echo $size[1]; ?>',
																							  {preloader: 'prl'});
																workorder.updateSize(type_id,
																				    '',
																					'size',
																					'<?php echo $order_id; ?>',
																					'<?php echo $size_alot_id; ?>',
																{onUpdate: function(response,root){
																 workorder.showproductname('<?php echo $order_id; ?>',
																				  {preloader:'prl',
																				  target:'dyanmic_div'});
													 			 
																 workorder.showOrderSizeQuantity('<?php echo $contact_id; ?>',
                                                                                                 '<?php echo $order_id; ?>',
																								 'a',
																{onUpdate: function(response,root){													                                                             document.getElementById('order_size_quantity').innerHTML=response;  
															 $('#display_order').tablesorter({widthFixed:true,
															 widgets:['zebra'],sortList:[[0,0]],headers:{5:{sorter: false}}}); }});
																 
													            }});
										       } else {
														workorder.returnLink('<?php echo $type; ?>',
																			 '<?php echo $vendor_contact_id; ?>',
																			 '<?php echo $div[0]; ?>',
																			 '<?php echo $choice; ?>',
																			 '<?php echo $order_id; ?>',
																			 '<?php echo $contact_id; ?>',
																			 '<?php echo $workorder_id; ?>',
																	 {target:'<?php  echo $div[0]; ?>', preloader: 'prl'});
												}}
												else {
														workorder.returnLink('<?php echo $type; ?>',
																			 '<?php echo $vendor_contact_id; ?>',
																			 '<?php echo $div[0]; ?>',
																			 '<?php echo $choice; ?>',
																			 '<?php echo $order_id; ?>',
																			 '<?php echo $contact_id; ?>',
																			 '<?php echo $workorder_id; ?>',
																	 {target:'<?php  echo $div[0]; ?>', preloader: 'prl'});}">
					    <option value="" <?php if($type=='') echo 'selected="selected"';?>>-Select-</option>
					    <?php    
						    $i=0;
						    $sql="select size from ".erp_SIZE." where order_id = '$order_id' and product_id = '$workorder_id'";
						    echo $sql_size;
						   $result =$this->db->query($sql,__FILE__,__LINE__);
						   while( $row =$this->db->fetch_array($result) ) 
							{
								$size=explode('_',$row['size']);
								$sizevalue[$i] = $size[1];
								$i++;
							}?> 
						<?php
						$row1=$this->db->fetch_array($result_size); ?> 
						<?php 
						$c=0;
						for($j=0;$j<$i;$j++){
							if($sizevalue[$j] == 'XS') $c++;
						}
						
						 if(( $c == 0) && ( $row1['size_xs'] >= 0 )){ ?>
						<option  value="<?php  echo $row1['size_xs'].'_XS';?>">XS</option><?php } ?>
						<?php $c=0;
						for($j=0;$j<$i;$j++){
							if($sizevalue[$j] == 'S') $c++;
						}
						
						 if(( $c == 0) && ( $row1['size_s'] >= 0 )){ ?>
						<option  value="<?php  echo $row1['size_s'].'_S';?>">S</option><?php } ?>
						<?php $c=0;
						for($j=0;$j<$i;$j++){
							if($sizevalue[$j] == 'M') $c++;
						}
						
						 if(( $c == 0) && ( $row1['size_m'] >= 0 )){ ?>
						<option  value="<?php  echo $row1['size_m'].'_M';?>">M</option><?php } ?>
						<?php $c=0;
						 for($j=0;$j<$i;$j++){
							if($sizevalue[$j] == 'L') $c++;
						}
						
						 if(( $c == 0) && ( $row1['size_l'] >= 0 )){ ?>
						<option  value="<?php  echo $row1['size_l'].'_L';?>">L</option><?php } ?>
						<?php $c=0;
						for($j=0;$j<$i;$j++){
							if($sizevalue[$j] == 'XL') $c++;
						}
						
						 if(( $c == 0) && ( $row1['size_xl'] >= 0 )){ ?>
						<option  value="<?php  echo $row1['size_xl'].'_XL';?>">XL</option><?php } ?>
						<?php $c=0;
						for($j=0;$j<$i;$j++){
							if($sizevalue[$j] == '2X') $c++;
						}
						
						 if(( $c == 0) && ( $row1['size_2x'] >= 0 )){ ?>
						<option  value="<?php  echo $row1['size_2x'].'_2X';?>">2X</option><?php  }?>
						<?php $c=0;
						for($j=0;$j<$i;$j++){
							if($sizevalue[$j] == '3X') $c++;
						}
						
						 if(( $c == 0) && ( $row1['size_3x'] >= 0 )){ ?>
						<option  value="<?php  echo $row1['size_3x'].'_3X';?>">3X</option><?php  }?>
						<?php $c=0;
						for($j=0;$j<$i;$j++){
							if($sizevalue[$j] == '4X') $c++;

						}
						 if(( $c == 0) && ( $row1['size_4x'] >= 0 )){ ?>
						<option  value="<?php  echo $row1['size_4x'].'_4X';?>">4X</option><?php } ?>
			    </select>
				<?php
				break;
				}  ////end of switch
			$html = ob_get_contents();
			ob_end_clean();
			return $html;			
		 } ///end of function showDropDown
		
		function updateSize($variable='',$unit_price='',$choice='',$order_id='',$size_alot_id=''){
			ob_start();
				$per_size = explode("_",$variable);
				$sql = "UPDATE ".erp_SIZE." SET $choice = '$variable', per_size_price = '$per_size[0]' WHERE size_alot_id = '$size_alot_id'";
				$result = $this->db->query($sql,__FILE__,__LINE__); ?>
			<script>
			  window.location = "order.php?order_id=<?php echo $order_id; ?>";
			</script>
			<?php
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
	     }   ////////////////end of function updateSize
		
		function size_update_table($workorder_id='',$order_id='',$size=''){
			ob_start();
			$s = strtolower($size);
			$m = $s.'_size_dependant';
			
			$sql_sub = "select workorder_id from ".erp_PRODUCT_ORDER." where gp_id = '$workorder_id'";
			$result_sub = $this->db->query($sql_sub,__FILE__,__LINE__);
			while($row_sub = $this->db->fetch_array($result_sub)){
			
				$product = $row_sub[workorder_id];
				
				$sql_up = "UPDATE ".erp_SIZE_DEPENDENT." Set $m = '' WHERE product_id IN ('$product','$workorder_id') AND order_id = '$order_id'";
				$this->db->query($sql_up,__FILE__,__LINE__);
				   
				$sql="DELETE FROM ".erp_INVENTORY_PRICE." WHERE order_id = '$order_id' AND work_orderid IN ('$product','$workorder_id') AND size_type = '$s'";
				$this->db->query($sql,__FILE__,__LINE__);
			
			}
			
			$html = ob_get_contents();
			ob_end_clean();
			return $html;		
		}
			
			function getName( $id='', $choice='', $order_id='' ){
				ob_start();
				switch($choice) {
				case 'contact':
					 $sql= "select * from ".TBL_INVE_CONTACTS." where contact_id = '$id'";
					 $result = $this->db->query($sql,__FILE__,__lINE__);	
					 $row=$this->db->fetch_array($result);
					 echo $row[first_name];
				  break;
				  case 'address':
					 $sql= "select * from ".TBL_MODULE_ADDRESS." where address_id = '$id'";
					 $result = $this->db->query($sql,__FILE__,__lINE__);	
					 $row=$this->db->fetch_array($result);
					 echo $row[street_address];
				  break;
				  case 'product_name':
					 $sql= "select * from ".erp_PRODUCT." where product_id = '$id'";
					 $result = $this->db->query($sql,__FILE__,__lINE__);	
					 $row=$this->db->fetch_array($result);
					 echo $row[product_name];
				  break;
				  
				   case 'printer_name':
				       $sql_pri = "select printer from ".erp_PRINTER_PAPER." WHERE id='$id'";
					   $result_pri = $this->db->query($sql_pri,__FILE__,__LINE__);
					   $row_pri = $this->db->fetch_array($result_pri);
					   echo $row_pri[printer];
				 break;
				  }  /////////////end of switch
					$html = ob_get_contents();
					ob_end_clean();
					return $html;	
				}   ////////////////end of function getName
			
		function updateOrderField($variable='',$order_id='',$choice=''){
			ob_start();
			$sql = "update ".erp_ORDER." set $choice= '$variable' where order_id='$order_id'";
			$result = $this->db->query($sql,__FILE__,__lINE__);	
			
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
	}   ////////////////end of function updateOrderField
	
	function updateProductOrder($variable='',$order_id='',$choice='',$product_id='',$new_product_id=''){
			ob_start();
			$sql = "update ".erp_PRODUCT_ORDER." set $choice= '$variable', product_id= '$new_product_id' where order_id='$order_id' and product_id='$product_id'";
			$result = $this->db->query($sql,__FILE__,__LINE__);	
			
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
	}   ////////////////end of function updateProductOrder
	
	   function update_textbox_field($field='',$choice='',$order_id='',$product_id='',$rework_id=''){
	    ob_start();
		   $sql = "UPDATE ".erp_REWORK." SET  $field='$choice' WHERE order_id='$order_id' and product_id='$product_id' and rework_id='$rework_id'";
		   $this->db->query($sql,__FILE__,__LINE__);
	    $html = ob_get_contents();
	    ob_end_clean();
	    return $html;
	  }
	 
	   function showTextBox( $variable='',$product_id='',$div_id='',$choice='',$order='',$size_alot_id='',$contact_id='',$size='',$workorder_id='',$size_i='' ){
	 	 ob_start();
		 switch($choice) {
		 		case 'fabric_edit': ?>
					<input  type="text" name="<?php  echo $variable; ?>" id="<?php echo $variable; ?>" size="5"
				        value="<?php echo $variable; ?>"
						onblur="javascript: var type_name = this.value;
						if(this.value!='<?php echo $variable; ?>') {
						 if(confirm('Are you sure you want to change the fabric_scrap from <?php echo $variable; ?> to '+ type_name)){
											   workorder.update_textbox_field('fabric_scrap',
											   									type_name,
																				'<?php echo $order; ?>',
																				'<?php echo $product_id;?>',
																				'<?php echo $contact_id; ?>',
										    							 {onUpdate: function(response,root){
											 workorder.ShowRework('<?php echo $order; ?>',
																 '<?php echo $product_id; ?>',
																{preloader:'prl',
																onUpdate:function(response,root){
												  
												  document.getElementById('rework<?php echo $product_id;?>').innerHTML= response;
												  document.getElementById('rework_link<?php echo $product_id;?>').innerHTML='- Rework Details';
												  document.getElementById('rework_detail<?php echo $order.''.$product_id; ?>').style.display = 'block';}}); 	 
											 							}});
										} else {
											  workorder.returnLink('<?php echo $variable; ?>',
																   '<?php echo $product_id;?>',
																   '<?php echo $div_id;?>',
																   '<?php echo $choice ;?>',
																   '<?php echo $order; ?>',
																   '<?php echo $contact_id; ?>',
														   {target:'<?php  echo $div_id; ?>',preloader:'prl'});
												}}
												else {
											  workorder.returnLink('<?php echo $variable; ?>',
																   '<?php echo $product_id;?>',
																   '<?php echo $div_id;?>',
																   '<?php echo $choice ;?>',
																   '<?php echo $order; ?>',
																   '<?php echo $contact_id; ?>',
														   {target:'<?php  echo $div_id; ?>',preloader:'prl'});}"><?php  
				break;
				case 'quantity':
				$sql="SELECT quantity FROM ".erp_SIZE." WHERE product_id='$workorder_id' and order_id='$order' and size_alot_id <> '$size_alot_id'";
			    $result = $this->db->query($sql,__FILE__,__LINE__);
				while( $row = $this->db->fetch_array($result) ){
				       $total += $row[quantity];
				}
				$div = explode(',',$div_id);
				?>
				<input type="hidden" name="total_quant" id="total_quant" value="<?php if($total){ echo $total; } else{ echo "0.00"; } ?>" />
				<input  type="text" name="<?php  echo $variable; ?>" id="<?php echo $variable; ?>" size="5" style="width:40px"
				        value="<?php echo $variable; ?>"
						onblur="javascript: var type_name = this.value;
								if(this.value!='<?php echo $variable; ?>') {
								      if(confirm('Are you sure you want to change the Quantity from <?php echo $variable; ?> to '+ type_name)){
									     var total_quantity = 0;
									     total_quantity = parseInt(document.getElementById('total_quant').value) + parseInt(type_name);
									    
									     workorder.showprice('<?php echo $size ; ?>',
																total_quantity,
																'<?php echo $product_id; ?>',
																1,
													{onUpdate:function(response,root){
													 var qty = response;
													 
									  		  workorder.update_textbox(qty,
																	   document.getElementById('<?php echo $variable; ?>').value,
																	   '<?php echo $choice; ?>',
																	   '<?php echo $order; ?>',
																	   '<?php echo $size_alot_id; ?>',
																	   '<?php echo $workorder_id; ?>',
										     {onUpdate: function(response,root){
											 
											  workorder.showOrderSizeQuantity('<?php echo $contact_id; ?>',
                                                                              '<?php echo $order; ?>',
																			  'a',
													  {onUpdate: function(response,root){
													   document.getElementById('order_size_quantity').innerHTML=response;  
													   $('#display_order').tablesorter({widthFixed:true,
													   widgets:['zebra'],sortList:[[0,0]],headers:{5:{sorter: false}}});
													 }});
											 
											        }}); }});
										} else {
											  workorder.returnLink('<?php echo $variable; ?>',
																   '<?php echo $product_id;?>',
																   '<?php echo $div[0];?>',
																   '<?php echo $choice ;?>',
																   '<?php echo $order; ?>',
																   '<?php echo $contact_id; ?>',
																   '<?php echo $workorder_id; ?>',
														   {target:'<?php  echo $div[0]; ?>',preloader:'prl'});
												}}
								 else {
									  workorder.returnLink('<?php echo $variable; ?>',
														   '<?php echo $product_id;?>',
														   '<?php echo $div[0];?>',
														   '<?php echo $choice ;?>',
														   '<?php echo $order; ?>',
														   '<?php echo $contact_id; ?>',
														   '<?php echo $workorder_id; ?>',
												   {target:'<?php  echo $div[0]; ?>',preloader:'prl'});
												}"> 
				<?php  
				break;
				case 'unit':
				$div = explode(',',$div_id); ?>
				$<input  type="text" name="<?php  echo $variable; ?>" id="<?php echo $variable; ?>" size="5"
				        value="<?php echo $variable; ?>"
						onblur="javascript: var type_name = this.value; 
								 if(this.value!='<?php echo $variable; ?>') {
								   if(confirm('Are you sure you want to change the unit price from <?php echo $variable; ?> to '+ type_name)){
									   if(confirm('This price puts your products in a new price catagory, do you want to change previous base prices?')){
									           
									  		   workorder.update_textbox1(document.getElementById('<?php echo $variable; ?>').value,
											  							'<?php echo $size_alot_id; ?>',
																	   'base',
										     {onUpdate: function(response,root){
											 
											  workorder.showOrderSizeQuantity('<?php echo $product_id; ?>',
                                                                              '<?php echo $order; ?>',
																			  'a',
													  {onUpdate: function(response,root){
													   document.getElementById('order_size_quantity').innerHTML=response;  
													   $('#display_order').tablesorter({widthFixed:true,
													   widgets:['zebra'],sortList:[[0,0]],headers:{6:{sorter: false}}});
													 }});
											 }});
										  } else {
										  workorder.returnLink('<?php echo $variable; ?>',
															   '<?php echo $product_id;?>',
															   '<?php echo $div[0];?>',
															   '<?php echo $choice ;?>',
															   '<?php echo $order; ?>',
															   '<?php echo $contact_id; ?>',
															   '<?php echo $workorder_id; ?>',
															   '<?php echo $size_i; ?>',
															   'base',
													   {target:'<?php  echo $div[0].'base'.$size; ?>',preloader:'prl'});
											}
									  } else {
										  workorder.returnLink('<?php echo $variable; ?>',
															   '<?php echo $product_id;?>',
															   '<?php echo $div[0];?>',
															   '<?php echo $choice ;?>',
															   '<?php echo $order; ?>',
															   '<?php echo $contact_id; ?>',
															   '<?php echo $workorder_id; ?>',
															   '<?php echo $size_i; ?>',
															   'base',
													   {target:'<?php  echo $div[0].'base'.$size; ?>',preloader:'prl'});
											}
									} else {
											 workorder.returnLink('<?php echo $variable; ?>',
															   '<?php echo $product_id;?>',
															   '<?php echo $div[0];?>',
															   '<?php echo $choice ;?>',
															   '<?php echo $order; ?>',
															   '<?php echo $contact_id; ?>',
															   '<?php echo $workorder_id; ?>',
															   '<?php echo $size_i; ?>',
															   'base',
													   {target:'<?php  echo $div[0].'base'.$size; ?>',preloader:'prl'});}"> 
				<?php  
				break;
				case 'unit_per':
				$div = explode(',',$div_id); ?>
				$<input  type="text" name="<?php  echo $variable; ?>" id="<?php echo $variable; ?>" size="5"
				        value="<?php echo $variable; ?>"
						onblur="javascript: var type_name = this.value; 
								 if(this.value!='<?php echo $variable; ?>') {
								   if(confirm('Are you sure you want to change the unit price from <?php echo $variable; ?> to '+ type_name)){
								     if(confirm('This price puts your products in a new price catagory, do you want to change previous prices?')){
									           
									  		   workorder.update_textbox1(document.getElementById('<?php echo $variable; ?>').value,
											  							'<?php echo $size_alot_id; ?>',
																	   'per_size',
										     {onUpdate: function(response,root){
											 
											  workorder.showOrderSizeQuantity('<?php echo $product_id; ?>',
                                                                              '<?php echo $order; ?>',
																			  'a',
													  {onUpdate: function(response,root){
													   document.getElementById('order_size_quantity').innerHTML=response;  
													   $('#display_order').tablesorter({widthFixed:true,
													   widgets:['zebra'],sortList:[[0,0]],headers:{6:{sorter: false}}});
													 }});
											        }});
										} else {
											  workorder.returnLink('<?php echo $variable; ?>',
																   '<?php echo $product_id;?>',
																   '<?php echo $div[0];?>',
																   'unit',
																   '<?php echo $order; ?>',
																   '<?php echo $contact_id; ?>',
																   '<?php echo $workorder_id; ?>',
																   '<?php echo $size_i; ?>',
																   'per_size',
														   {target:'<?php  echo $div[0].'per'.$size; ?>',preloader:'prl'});	
											 }
									  } else {
										  workorder.returnLink('<?php echo $variable; ?>',
															   '<?php echo $product_id;?>',
															   '<?php echo $div[0];?>',
															   'unit',
															   '<?php echo $order; ?>',
															   '<?php echo $contact_id; ?>',
															   '<?php echo $workorder_id; ?>',
															   '<?php echo $size_i; ?>',
															   'per_size',
													   {target:'<?php  echo $div[0].'per'.$size; ?>',preloader:'prl'});
											}
									} else {
											 workorder.returnLink('<?php echo $variable; ?>',
																   '<?php echo $product_id;?>',
																   '<?php echo $div[0];?>',
																   'unit',
																   '<?php echo $order; ?>',
																   '<?php echo $contact_id; ?>',
																   '<?php echo $workorder_id; ?>',
																   '<?php echo $size_i; ?>',
																   'per_size',
													   {target:'<?php  echo $div[0].'per'.$size; ?>',preloader:'prl'});
												}"> 
				<?php  
				break;
				case 'unit_cost':
				$div = explode(',',$div_id);?>
				<input  type="text" name="<?php  echo $variable; ?>" id="<?php echo $variable; ?>" size="5"
				        value="<?php echo $variable; ?>"
						onblur="javascript: var type_name = this.value; 
								 if(this.value!='<?php echo $variable; ?>') {
								   if(confirm('Are you sure you want to change the unit price from <?php echo $variable; ?> to '+ type_name)){
								     if(confirm('This price puts your products in a new price catagory, do you want to change previous prices?')){
									           
									  		   workorder.update_textbox1(document.getElementById('<?php echo $variable; ?>').value,
											  							'<?php echo $size_alot_id; ?>',
																	   'cost',
										     {onUpdate: function(response,root){
											 
											  workorder.showOrderSizeQuantity('<?php echo $product_id; ?>',
                                                                              '<?php echo $order; ?>',
																			  'a',
													  {onUpdate: function(response,root){
													   document.getElementById('order_size_quantity').innerHTML=response;  
													   $('#display_order').tablesorter({widthFixed:true,
													   widgets:['zebra'],sortList:[[0,0]],headers:{6:{sorter: false}}});
													 }});
											 
											        }});
										 } else {
											   workorder.showCostIncrease('<?php echo $workorder_id; ?>',
																		  '<?php echo $product_id;?>',
																		  '<?php echo $order; ?>',
																		  '<?php echo $size_i;?>',
																		  '<?php echo $div[0];?>',
																		  '<?php echo $size_alot_id;?>',
																		 {target:'<?php  echo $div[0].'cost'.$size_alot_id; ?>',preloader:'prl'});
											}
									  } else {
									   workorder.showCostIncrease('<?php echo $workorder_id; ?>',
																  '<?php echo $product_id;?>',
																  '<?php echo $order; ?>',
																  '<?php echo $size_i;?>',
																  '<?php echo $div[0];?>',
																  '<?php echo $size_alot_id;?>',
																 {target:'<?php  echo $div[0].'cost'.$size_alot_id; ?>',preloader:'prl'});
											}
									} else {
										workorder.showCostIncrease('<?php echo $workorder_id; ?>',
																  '<?php echo $product_id;?>',
																  '<?php echo $order; ?>',
																  '<?php echo $size_i;?>',
																  '<?php echo $div[0];?>',
																   '<?php echo $size_alot_id;?>',
														{target:'<?php  echo $div[0].'cost'.$size_alot_id; ?>',preloader:'prl'});
										}"> 
				<?php  
				break;
			}    /////////////end of switch
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}  ////////////////end of function showTextBox
	
	
	function update_textbox1($price='',$size_alot_id='',$unit_choice='')
	{
	ob_start();
		if($unit_choice == 'base'){
			$sql = "UPDATE ".erp_SIZE." SET  base_price  = '$price' WHERE size_alot_id = '$size_alot_id'";
			$this->db->query($sql,__FILE__,__LINE__);
		}
		else if($unit_choice == 'per_size'){
			$sql = "UPDATE ".erp_SIZE." SET  per_size_price  = '$price' WHERE size_alot_id = '$size_alot_id'";
			$this->db->query($sql,__FILE__,__LINE__);
	 	} 
		else{
			$sql = "UPDATE ".erp_INVENTORY_PRICE." SET  cost_increase  = '$price' WHERE price_id = '$size_alot_id'";
			$this->db->query($sql,__FILE__,__LINE__);
		}
		echo $this->updateCapacity();
	
	$html=ob_get_contents();
	ob_end_clean();
	return $html;
	}

	
	function update_textbox($base_price,$variable,$choice,$order_id='',$size_alot_id,$workorder_id=''){
        $this->calculate();
	    ob_start();
		$sql = "UPDATE ".erp_SIZE." SET $choice= '$variable', base_price = '$base_price' WHERE size_alot_id = '$size_alot_id'";
		$this->db->query($sql,__FILE__,__LINE__);
		
		$sql = "UPDATE ".erp_SIZE." SET base_price = '$base_price' WHERE order_id = '$order_id' and product_id = '$workorder_id'";
		$this->db->query($sql,__FILE__,__LINE__);
		
		echo $this->updateCapacity();
		
        $html=ob_get_contents();
		ob_end_clean();
		return $html;
	 } ////////////end of function update_textbox
	 
	function updateCapacity(){
		$this->capacity_calc = new CapacityCalc;
		$global_task = new GlobalTask();
		
		$sql_s ="Select a.chart_assign_id, a.module, a.module_id, b.flow_chart_id, b.due_date, b.predicted_assign_id from assign_flow_chart_task a, predicted_flow_chart_task b where a.product_id = '$_REQUEST[order_id]' and a.task_status = 'Active' and a.chart_assign_id = b.chart_assign_id "; 
		//echo $sql_s;
		$result_s = $this->db->query($sql_s,__FILE__,__LINE__);
		if($this->db->num_rows($result_s) > 0){
		  while($row_s = $this->db->fetch_array($result_s)){
		   
			$cal_time = $this->capacity_calc->calculate_capacity($row_s[module], $row_s[module_id], $row_s[flow_chart_id]);
		
			$sql = "UPDATE predicted_flow_chart_task SET calculated_time= '$cal_time' WHERE predicted_assign_id = '$row_s[predicted_assign_id]'";
		    $this->db->query($sql,__FILE__,__LINE__);
			
			
		  }
	   }
	} 
	 
	function checkSystemTask($global_task_status_id='',$module='',$module_id='',$group_id=''){
		ob_start();

		  $sql_s ="Select a.*,b.* from template a, assign_report_to_system_task b where a.id = b.report_id and b.selection_option_id = '$global_task_status_id'"; 
		  $result_s = $this->db->query($sql_s,__FILE__,__LINE__);
		  if($this->db->num_rows($result_s) > 0){
				while($row_s = $this->db->fetch_array($result_s)){
					if($row_s['module'] == $module && (($row_s['message'] =='NULL') || ($row_s['message'] ==''))) {
						echo $this->runReduceOnhand($row_s['title'],$module,$module_id,$group_id);
					}
			   }				
		  }	
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	 }

	  function sentMailToUser($global_task_status_id='',$module='',$msg=''){
	 	ob_start();
		
		  $sql_s ="Select a.*,b.* from template a, assign_report_to_system_task b where a.id = b.report_id and b.selection_option_id = '$global_task_status_id'"; 
		  $result_s = $this->db->query($sql_s,__FILE__,__LINE__);
		  if($this->db->num_rows($result_s) > 0){
				while($row_s = $this->db->fetch_array($result_s)){
					if($row_s['module'] == $module && $row_s['subject'] != ''){
						$message = $row_s['message'];

						if($message !='NULL'){						
							$sql_c = "Select * from insert_to_report where timestamp = '$row_s[timestamp]'";
							$result_c = $this->db->query($sql_c,__FILE__,__LINE__);
							if($this->db->num_rows($result_c) > 0){
								while($row_c = $this->db->fetch_array($result_c)){ 
									$sql="select b.$row_c[field_name] from $tbl a, $row_c[table_name] b where a.$row_c[column_name_main]=b.$row_c[column_name] and a.$mod_id=$module_id";	
									$result=$this->db->query($sql,__FILE__,__LINE__);
									$row =$this->db->fetch_array($result);
								}
							}
							$sql_e = "Select * from contacts_email where contact_id = '$_SESSION[contact_id]' limit 1";
							$result_e = $this->db->query($sql_e,__FILE__,__LINE__);
							$row_e = $this->db->fetch_array($result_e);
							$to = $row_e[email];
							$subject = $row_s[subject];
							$message = "<strong>".$row_s[title]."</strong>".'<br>'.$msg;			
							$headers  = 'MIME-Version: 1.0' . "\r\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
							$headers .= 'To: '.$to."\r\n";
							$headers .= 'From: Coulee TechLink' . "\r\n";
							mail($to, $subject, $message, $headers);	
						}
					}
				}
			}
		$html=ob_get_contents();
		ob_end_clean();
		return $html;	 
	 } /////end of function sentMailToUser()

  function FlowChartDiv( $module_name , $module_id , $rework_id=''){

	  $module_name_true = $module_name;
      $module_name = str_replace( ' ' , '' , $module_name );
      ob_start();
	  $global_task = new GlobalTask();
      ?>
      <div id="flowcharttask_<?php echo $module_name . '_' . $module_id . '_' . $rework_id;?>">
        <a href="javascript:void(0);" onClick="javascript: workorder.check_shipdate('<?php echo $_REQUEST[order_id];?>',
																					{onUpdate:function(response,root){
																						if(response){
																						  $('#flowcharttask_add_<?php echo $module_name . '_' . $module_id . '_' . $rework_id;?>').show();
																						}
																						else{
																						 alert('Ship Date for this order does not exist');
																						}
																					}}); ">
			add Flow Chart Task
		</a>
        <div style="display: none;" id="flowcharttask_add_<?php echo $module_name . '_' . $module_id . '_' . $rework_id;?>">
            <?php echo $global_task->AddFlowChartTask($module_name_true, $module_id , '' , '' , "$('#flowcharttask_add_" . $module_name . '_' . $module_id . '_' . $rework_id . "').hide()" , $_REQUEST[order_id] , $rework_id);?>
        </div>

		<div id="flowcharttask_options_<?php echo $module_name . '_' . $module_id . '_' . $rework_id;?>">
			<?php echo $global_task->displayByModuleId($module_name_true, $module_id, "flowcharttask_options_" . $module_name . '_' . $module_id . '_' . $rework_id,'',$_REQUEST[order_id],'','', $rework_id);?>
		</div>
      </div>
      <?php
      $html=ob_get_contents();
      ob_end_clean();
      return $html;
  }
  
   function check_shipdate($order_id){
    ob_start();
		$sql = "Select * FROM ".erp_ORDER." WHERE order_id='$order_id'";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		$row=$this->db->fetch_array($record);
		if($row[ship_date]==''){
		 return false;
		}else{
		 return true;
		}
	$html=ob_get_contents();
    ob_end_clean();
    return $html;
	}
  
  function runReduceOnhand($title='',$module='',$module_id='',$group_id=''){
       ob_start();
	   $order_id = $_REQUEST[order_id];
	   $t = strtolower($title);
	   $inve_name = '';
	   $inve_used = 0;
	   
	   if($title == 'Fabric'){
			$inve_detail = $this->getFabricInveUsed($order_id, $module_id);
			$inve = explode("_", $inve_detail);
			$inve_name = $inve[0];
			$inve_used = $inve[1];
	   }
	   else{
			$sql_size = "SELECT size, quantity FROM ".erp_SIZE." WHERE order_id = '$order_id' AND product_id = '$module_id'";
			$result_size = $this->db->query($sql_size,_FILE_,_LINE_); 
			
			if($this->db->num_rows($result_size) > 0){
				while($row_size = $this->db->fetch_array($result_size)){
					$s = explode("_", $row_size['size']);
					$size = strtolower($s[1]);
					$qty = $row_size['quantity'];
					$inve_id = $this->getInveId($t, $order_id, $module_id, $size);
					$inve_name = $this->invenName($inve_id);					
					$inve_used = $this->getInveUsed($size, $inve_id, $module_id, $qty);		
				} 			
			}
	   }	   
	  echo $this->updateAmtOnhand($inve_name, $inve_used, $order_id, $module_id);
	   
      $html=ob_get_contents();
      ob_end_clean();
      return $html;	 
 }  //////////end of function runReduceOnhand()
	   
	function getFabricInveUsed($order_id='', $fabric_id=''){
		ob_start();
		
		$inve_used = 0;		
		$sql_group="select * from ".erp_GROUP." where order_id='$order_id' and fabric_id = '$fabric_id'";
		$result_group = $this->db->query($sql_group,_FILE_,_LINE_); 
		
		$row_grp = $this->db->fetch_array($result_group);
		$inve_name = $row_grp['inventory_name'];	   
		$inve_used = $row_grp['total_inch'];
		echo $inve_name.'_'.$inve_used;
				
		$html=ob_get_contents();
		ob_end_clean();
		return $html;	   
	}  //////////end of function getFabricInveUsed()
	   
	function getInveId($option_type='', $order_id='', $product_id='', $size=''){
		ob_start();
		
		$inve_id = 0; 
		$sql_inve = "SELECT $option_type from ".erp_WORK_ORDER." WHERE order_id = '$order_id' AND product_id = '$product_id'";
		$result_inve = $this->db->query($sql_inve);
		$row_inve = $this->db->fetch_array($result_inve);
		
		if($row_inve[$option_type] != ''){						
			$inve_id = $row_inve[$option_type];
		}
		else {
			$name2 = $size.'_size_dependant';
			
			$sql_size_dependent = "SELECT $name2 from ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' AND product_id = '$product_id' AND option_type = '$option_type'";
			$result_size_dependent = $this->db->query($sql_size_dependent);
			$row_size_dependent = $this->db->fetch_array($result_size_dependent);
			
			$inve = explode("_", $row_size_dependent[$name2]);
			$inve_id = $inve[0];
		}
		echo $inve_id;
		
		$html=ob_get_contents();
		ob_end_clean();

		return $html;	   
	}  //////////end of function getInveId()  
				
	function getInveUsed($size='', $inve_id='', $product_id='', $qty=''){
		ob_start();
		
		$inve_used = 0;			
		$name = $size.'_inventory_usage';					
		
		$sql_price = "SELECT $name from ".erp_ASSIGN_INVENTORY." WHERE inventory_id = '$inve_id' AND product_id = '$product_id'";
		$result_price = $this->db->query($sql_price);
		$row_price = $this->db->fetch_array($result_price);
		
		$unit_price = $row_price[$name];
		$price = $qty * $unit_price;
		$inve_used = $inve_used + $price;
		
		echo $inve_used;
		
		$html=ob_get_contents();
		ob_end_clean();
		return $html;	   
	}  //////////end of function getInveUsed()  

	function updateAmtOnhand($inve_name='', $inve_used='', $order_id='', $product_id=''){
		ob_start();
			
		$sql_f="select amt_onhand from " .TBL_INVENTORY_DETAILS." where name = '$inve_name'";
		$result_f = $this->db->query($sql_f,_FILE_,_LINE_); 
		$row_f = $this->db->fetch_array($result_f);
		$amt_onhand = $row_f['amt_onhand'];
		$cur_amt_onhand = $amt_onhand - $inve_used;	
		
		$sql = "update ".TBL_INVENTORY_DETAILS." set amt_onhand = '$cur_amt_onhand' where name = '$inve_name'";
		$this->db->query($sql,__FILE__,__lINE__);	
		echo $sql;
		
		if($inve_name !=''){
			$sql_log = "INSERT INTO ".erp_INVENTORY_LOG." values('', '$inve_name', '$order_id', '$product_id', '$amt_onhand', '$inve_used', '$cur_amt_onhand')";
			$this->db->query($sql_log,__FILE__,__lINE__);
		}
		
		$html=ob_get_contents();
		ob_end_clean();
		return $html;	   
	}  //////////end of function updateAmtOnHand()  
	
	function email_php($selection_option_id=''){
		ob_start(); ?>

		<form method="post" enctype="multipart/form-data">
			<table border="1">
				<tr bgcolor="#FFFF99">
				  <td>Phone</td>
				  <td>fax</td>
				  <td>Customer PO</td>
				  <td>Payment terms</td>
				</tr>
				<tr>
					<td>a</td>
					<td>b</td>
					<td>c</td>
					<td>d</td>
				</tr>
				<tr>
					<td>p</td>
					<td>q</td>
					<td>r</td>
					<td>s</td>
				</tr>
			</table>
		</form>
		
		<?php		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}  //////////end of function email_php()  

}  //////////end of class
?>