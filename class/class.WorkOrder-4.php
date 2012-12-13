
<?php
require_once('class/class.fileserver.php');
require_once('class/class.tasks.php');
require_once('class/class.ups.php');

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
var $total_est_day;

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
		//$row = $this->db->fetch_array($result);
		if($this->db->num_rows($result) > 0){
		   return true;
		} else { return false; }
	
	}/////end of function
	
	function showCompleteContactDetails($order_id=''){
		
		$sql_order = "Select * from ".erp_ORDER." where order_id = '$order_id'";
		$result_order = $this->db->query($sql_order,__FILE__,__LINE__);
		$row_order=$this->db->fetch_array($result_order);
		$contact_id = $row_order['vendor_contact_id'];
		$_SESSION[contact_id] = $contact_id;	

		$sql = "Select * from ".TBL_CONTACT." where contact_id = '$contact_id'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		$row=$this->db->fetch_array($result);
		
		$sql_name = "Select * from ".TBL_CONTACT." where company = '$contact_id' and contact_id = '$row_order[contact_id]'";
		$result_name = $this->db->query($sql_name,__FILE__,__LINE__);
		$row_name=$this->db->fetch_array($result_name);
		
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


				<a href="contact_profile.php?contact_id=<?php echo $contact_id; ?>"><?php echo $row['company_name'].'<br>&nbsp;&nbsp;&nbsp;&nbsp;';
				if($this->db->num_rows($result_name) > 0){
				echo $row_name['first_name'].' '.$row_name['last_name'].' - '.substr($row_phone['number'], 0, 3).'-'.substr($row_phone['number'], 3, 3).'-'.substr($row_phone['number'], 6, 4); } ?></a>
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
						    <?php echo $this->returnLink($row_name['first_name'],$contact_id,'people_box_'.$contact_id,'people',$order_id); ?>
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
									echo $this->returnLink($row_ad['street_address'],$contact_id,'address_box_'.$contact_id,'address',$order_id);
								}
								else{
								 echo $this->returnLink('',$contact_id,'address_box_'.$contact_id,'address',$order_id);
								}}
								else{?>
								District Office :
									<?php echo '<br>'. $row_address['street_address'].'<br>'.$row_address['city'].' '.$row_address['state'].' '.$row_address['zip'].'<br>'.$row_address['country'];

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
									echo $this->returnLink($row_ad['street_address'],$contact_id,'address_box2_'.$contact_id,'billing_address',$order_id);
								}
								else{
									echo $this->returnLink('',$contact_id,'address_box2_'.$contact_id,'billing_address',$order_id);
								}}
								else{?>
						    		District Office :
									<?php echo '<br>'. $row_address['street_address'].'<br>'.$row_address['city'].' '.$row_address['state'].' '.$row_address['zip'].'<br>'.$row_address['country'];
								} ?>
					   </span>
					</td>
				</tr>				
				<tr><td colspan="2">&nbsp;</td></tr>
			</table>				
			</div>
		<?php 
	} // End of function showCompleteContactDetails()
	
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
		//$order_id=2; 
		//$contact_id=44;
		$sql = "SELECT * FROM ".erp_PRODUCT_ORDER ." WHERE order_id = '$order_id' and gp_id='0'" ;
		$result = $this->db->query($sql,__FILE__,__LINE__);
		?>
		<input type="hidden" name="cnt" id="cnt" value="1" />
		<?php if( $check == '' ){?>
		<div class="profile_box1" style="font-weight:bold; width:85%">
			<a style="color:#FF0000; font-size:15px" onClick="javascript: if(this.innerHTML=='+'){
																	this.innerHTML = '-';
																	document.getElementById('quantity_details').style.display = 'block';
																	}
																	else {
																	this.innerHTML = '+';
																	document.getElementById('quantity_details').style.display = 'none';}">-</a>&nbsp;<u>Order Size and Quantity</u>
		</div>
		<?php } ?>
		<div  id="quantity_details"  class="contact_form" style="display:block; float:left; width:88%">
		<table id="display_order" class="event_form small_text" width="100%">
			<thead>
				<tr>
					<th width="30%">PRODUCT</th>
					<th width="20%">Unit Price</th>
					<th width="10%">QTY</th>
					<th width="10%">SIZE</th>
					<th width="30%">Total</th>
					<th width="10%">&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php while($row=$this->db->fetch_array($result)){ ?>
			  	<tr>
					<td width="30%">
					    <span id="product_name_box_<?php echo $order_id.''.$row['workorder_id']; ?>"> 
						    <?php echo $row['product_name']; ?>
					    </span>
					</td>
					<td width="20%">
					    <span id="unit_<?php echo $order_id.''.$row['workorder_id']; ?>"> 
						    <?php echo $this->returnLink($row['unit_price'],$row['product_id'],'unit_'.$order_id.''.$row['workorder_id'],'unit',$order_id,$contact_id,$row['workorder_id']); ?>
					    </span>
					
					<?php //echo $row['unit_price']; ?></td>
					<td width="10%">
					    <span id="quantity_box_<?php echo $order_id.''.$row['workorder_id']; ?>"> 
						    <?php echo $this->returnLink($row['quantity'],$row['product_id'],'quantity_box_'.$order_id.''.$row['workorder_id'],'quantity',$order_id,$contact_id,$row['workorder_id']); ?>
					    </span>
					</td>
					<td width="30%">
					    <span id="size_box_<?php echo $order_id.''.$row['workorder_id']; ?>"> 
						    <?php echo $this->returnLink($row['size'],$row['product_id'],'size_box_'.$order_id.''.$row['workorder_id'],'size',$order_id,$contact_id,$row['workorder_id']); ?>
					    </span><br />
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
					<td width="30%">
					    <span id="total_<?php echo $order_id.''.$row['workorder_id']; ?>"> 
						    <?php echo $this->returnLink($row['total'],$row['product_id'],'total_'.$order_id.''.$row['workorder_id'],'total',$order_id,$contact_id,$row['workorder_id']); ?>
					    </span>
					
					
					<?php //echo '$'.$row['total']; ?></td>
					<td align="center"><a href="javascript:void(0);" 
					      onclick="javascript: if(confirm('Are you sure to delete this product')){
                                                   workorder.deletethis('<?php echo $row['product_id']; ?>',
						                                                '<?php echo $order_id; ?>',
																		'',
																		'<?php echo $row['workorder_id']; ?>',
					                                   { preloader:'prl',
														 onUpdate: function(response,root){ 
													   	 workorder.showOrderSizeQuantity('<?php echo $contact_id; ?>',
														                                 '<?php echo $order_id; ?>',
																						 'a',
													   { preloader:'prl',
													     onUpdate: function(response,root){
														  document.getElementById('quantity_details').innerHTML=response; 
														  $('#display_order').tablesorter({widthFixed:true,
														  widgets:['zebra'],sortList:[[0,0]],headers: {5:{sorter: false}}	});}}); 
														
					                              }});
													
														  workorder.showproductname('<?php echo $order_id; ?>',
																				  {preloader:'prl',
																				  target:'dyanmic_div'});
												 }"> <img src="images/trash.gif" border="0" /> </a>
					</td>
				</tr>
				<?php
				$this->order_id = $row['order_id'];
				//$this->total_price += $row['total'];
				
				}  // End of While loop
				$sql_total = "SELECT total FROM ".erp_SIZE." WHERE order_id = '$order_id'";
				$result_total = $this->db->query($sql_total,__FILE__,__LINE__);
				while( $row_total = $this->db->fetch_array($result_total) ){
				$this->total_price += $row_total[total]; }
				
				?>
			</tbody>
		  </table>
		  <div id="ship_id">
		  <table class="table" width="100%">	
				<tr>
					<td width="150%" colspan="4">
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
                $this->multilier = $row_order['multiplier'];
				?>	
				<tr>
					<td width="90%">&nbsp;</td>
					<td width="20%">Shipping</td>
					<td width="20%">
					    <div id="shipping">
						   <?php echo $this->edit_value($row_order['shipping_charges']); ?>
						</div>
					</td>
					<td width="20%">
                                            
					   <select name="fedex" id="fedex"
					           onblur="javascript: var shipping_id= this.value;
							   if( $('#shipping_weight').val() != 0 ){
							   if(confirm('Are you sure to change the shipping to '+ $( this).find(':selected').text()))
											{
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
															    { preloader:'prl',
																  onUpdate:function(response,root){
																		
																document.getElementById('total_box').innerHTML=response;
																		}}); }});
                                                                                                                                            workorder.get_value_from_order( 'shipping_charges' , <?php echo $row_order["order_id"]; ?> , { target: 'shipping'});
                                                                                                                                            workorder.get_value_from_order( 'shipping_charges' , <?php echo $row_order["order_id"]; ?> , { target: 'shipping'});<?php // I needed to add 3 becouse it was not updating properly, If you remove them Make sure you test it out at least 10 times ?>
                                                                                                                                            workorder.get_value_from_order( 'shipping_charges' , <?php echo $row_order["order_id"]; ?> , { target: 'shipping'});
                                                                                                                                            }                                                                                                                                        
													else {
														    <?php if($row_order[shipping_charges] != ''){ ?>
																		this.value=<?php echo $row_order['shipping_charges']; ?>;
															<?php }//end of if
																  else { ?>
																		this.value='';
															<?php }//end of else ?>
														}
                                                                                                                
                                                           }  else { alert('A Weight Needs to be entered');}                                                   ">
					      <option value="">-select-</option>
                                              <?php $ups = new ups(); 
                                              $shipTypes = $ups->getShippmentTypes();
                                              foreach( $shipTypes as $type ){
                                                  ?><option value="<?php echo $type["code"]; ?>" <?php if($type["code"]==$row_order['shipment_type']) echo 'selected="selected"'; ?>><?php echo $type["name"]; ?></option><?php
                                              }
                                              
                                              
                                              ?>

					  </select>
                                            <?php ?>
					</td>
				</tr>
				<tr>
					<td width="90%">&nbsp;</td>
					<td width="20%">Multiplier </td>
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
											    { preloader:'prl',
												  onUpdate:function(response,root){
														   document.getElementById('total_box').innerHTML=response; }}); }});
														   
												 workorder.showOrderSizeQuantity('<?php echo $contact_id; ?>',
														                                 '<?php echo $order_id; ?>',
																						 'a',
													   { preloader:'prl',
													     onUpdate: function(response,root){
														  document.getElementById('quantity_details').innerHTML=response; 
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
									       }
												"/>x
				    </td>
					<input type="hidden" id="hiden_multiply" value="<?php echo $this->multilier;?>" />
					<td width="20%">&nbsp;</td>
				</tr>
				<tr>
					<td width="90%">&nbsp;</td>
					<td width="20%">Grand Total</td>
					<td width="20%"><div id="total_box"><?php echo $this->calculate($this->total_price,$row_order['shipping_charges'],$row_order['multiplier']); ?></div></td>
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
		
		function date_display($order_id=''){
		   ob_start(); 
		   $sql_order = "select * from ".erp_ORDER." where order_id = '$this->order_id'";
		   $result_order = $this->db->query($sql_order,__FILE__,__lINE__);
		   $row_order=$this->db->fetch_array($result_order);
		   ?>
		   <div>
			<table class="event_form small_text" width="100%">
				<tr>
				 <th>
					Event date :<input  type="text" name="start_date" id="start_date" value="<?php echo $row_order['event_date']; ?>"/>
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
												var day = Array;
												day = this.selection.getDates();
												var partA = day.toString().split(' ');
												if(partA[0] == 'Sun' || partA[0] == 'Sat')
												{
													alert('You cannot have weekends as your Event date');
													return true;
													document.getElementById('start_date').value = '';
												} else {
												    this.hide();
													document.getElementById('start_date').value=this.selection.print("%Y-%m-%d %I:%M %p");
													
													workorder.insert_date(document.getElementById('start_date').value,
																		  document.getElementById('end_date').value,
																		  {preloader:'prl'});
												       }
											}
									 });			
							}
							start_cal();
							</script>
						</th>
					<?php /*?><td>&nbsp;</td><?php */?>
				</tr>
				<tr>
					<th>Ship Date:<input type="text" name="end_date" id="end_date" value="<?php echo $row_order[ship_date]; ?>"/>
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
													document.getElementById('end_date').value=this.selection.print("%Y-%m-%d %I:%M %p");
													workorder.insert_date(document.getElementById('start_date').value,
																		  document.getElementById('end_date').value,
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
                                    <th><div id="shipping_weight_div" >Shipping weight&nbsp;<input type="text" id="shipping_weight" onBlur="$('#shipping_weight_div').css('background','#ffffff');workorder.update_order_weight( '<?php echo $row_order['order_id']; ?>' , this.value , { preloader:'prl' , target:'shipping_weight_div'} )" value="<?php echo $row_order['weight']; ?>"></div></th>
                                </tr>
			</table>		
		</div>
		
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
		}
	function update_order_weight($order_id , $weight ){
            ob_start();
            $this->db->update('erp_order', array('weight' => $weight ), 'order_id', $order_id);
            $row_order = $this->db->fetch_assoc($this->db->query("SELECT * FROM erp_order WHERE order_id = '$order_id'"));
            ?>
                Shipping weight&nbsp;<input type="text" id="shipping_weight" onBlur="$('#shipping_weight_div').css('backround' , '#ffffff');workorder.update_order_weight( '<?php echo $row_order['order_id']; ?>' , this.value , { preloader:'prl' , target:'shipping_weight_div'} )" value="<?php echo $row_order['weight']; ?>">
                <?
            //$return = '<div id="shipping_weight_id" >Shipping weight&nbsp;<input type="text" id="shipping_weight" onblur="" value="' . $row_order['weight']; '"></div>';
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
            }
	function insert_date($start_date,$end_date){
		 $update_sql_array = array();				
		 $update_sql_array[event_date] = $start_date;
		 $update_sql_array[ship_date] = $end_date;
		 $this->db->update(erp_ORDER,$update_sql_array,'order_id',$_REQUEST[order_id]);
	}// end of Function insert_date
	
	function GetVendorJson($pattern=''){
		ob_start();
		$contact_json = "";
		$sql = "Select * FROM ".erp_PRODUCT." WHERE product_name LIKE '%$pattern%' AND product_status = 'Active'";
		//echo $sql;
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
					<div style="background-color:#ADC2EB; max-width:540px; min-width:500px;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle">Add Product</div>
					<div id="TB_closeAjaxWindow">
					<a href="javascript:void(0)" onClick="javascript: document.getElementById('div_order').style.display='none';">
					<img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div class="white_content" style="max-width:540px; min-width:500px;"> 
				<div style="padding:20px;" class="form_main">			
				<form method="post" enctype="multipart/form-data">
					<table class="" id="tbl_<?php echo $i; ?>">
						<tr>
							<td>Product Name : </td>
							<td>
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
															    document.getElementById('new').innerHTML=response;}});
															">
								</select>
															
								<input type="hidden" name="product_id" id="product_id" />
							<?php } 
							else {
								echo $this->getName($product_id,'product_name');
							}?>
							</td>
						</tr>
						<tr>
							<td>Quantity :</td>
							<td>
								<input type="text" name="quantity" id="quantity" style="size:200px"
									onchange="javascript:
									             <?php if( $product_id == '' ){ ?>
													if(this.value >5){ <?php } ?>
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
																			}
													 <?php if( $product_id == '' ){ ?> } else {
															alert('select quantity more than 5');
														} <?php } ?>
												"/>
							</td>
						</tr>
						<tr>
							<td colspan="2"><div id="show_prod_details_<?php echo $i; ?>"></div></td>
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
				<table class="table">
					<tr>
						<td>Base Price : </td>
						<td>
						    <input type="text" id="base_price" style="border:hidden" />
						 </td>
					</tr>
					<tr>
						<td>Size :</td>
						<td>
						<?php $sql1="select * from ".erp_PRODUCT." where product_id = '$product_id'";
							  $result1=$this->db->query($sql1,__FILE__,__LINE__);
							  //echo $sql1;
							  $size_value=array();
							  $i=0;
							  $total_qty = 0;
							  if( $workorder_id != '' ){
								  $sql_size="select size, quantity from ".erp_SIZE." where order_id = '$order_id' and product_id = '$workorder_id'";
								  //echo $sql_size;
								  $result_size=$this->db->query($sql_size,__FILE__,__LINE__);
								  while( $row_size=$this->db->fetch_array($result_size) ) 
									{
										$total_qty += $row_size[quantity];
										$size=explode('_',$row_size['size']);
										$sizevalue[$i] = $size[1];
										$i++;
									}
							  }
								//echo $total_qty; ?>
								
							      <input type="hidden" id="total_qty" name="total_qty" value="<?php echo $total_qty + $quantity; ?>" />
								  
								  <select name="sel_size" id="sel_size" onFocus="<?php if($quantity == ''){?>
								  												  alert('Please Enter Quantity First');
																				  document.getElementById('quantity').focus();
																				  <?php } ?>"
										  onchange="javascrpit: var size=this.value;
															if(size==''){
																 alert('Please Enter Quantity');}
															else{
																 workorder.check_product_existence('<?php echo $product_id; ?>',
																                                   size,
																				                   'size',
																  { preloader: 'prl',
																  onUpdate: function(response,root){
																  if(response=='c'){
																	 alert('Selected Size Is Not Available. Please Select Any Other !!');
																	 return false;
												                   }
												                 }});
																 
																	 workorder.showprice(this.value,
																						'<?php echo $quantity; ?>',
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
									
									 if( $c == 0 or $workorder_id == '' ){ ?>
									<option  value="<?php  echo $row1['size_xs'].'_XS';?>">XS</option><?php } ?>
									<?php $c=0;
									for($j=0;$j<$i;$j++){
										if($sizevalue[$j] == 'S') $c++;
									}
									
									 if( $c == 0 or $workorder_id == '' ){ ?>
									<option  value="<?php  echo $row1['size_s'].'_S';?>">S</option><?php } ?>
									<?php $c=0;
									for($j=0;$j<$i;$j++){
										if($sizevalue[$j] == 'M') $c++;
									}
									
									 if( $c == 0 or $workorder_id == '' ){ ?>
									<option  value="<?php  echo $row1['size_m'].'_M';?>">M</option><?php } ?>
									<?php $c=0;
									 for($j=0;$j<$i;$j++){
										if($sizevalue[$j] == 'L') $c++;
									}
									
									 if( $c == 0 or $workorder_id == '' ){ ?>
									<option  value="<?php  echo $row1['size_l'].'_L';?>">L</option><?php } ?>
									<?php $c=0;
									for($j=0;$j<$i;$j++){
										if($sizevalue[$j] == 'XL') $c++;
									}
									
									 if( $c == 0 or $workorder_id == '' ){ ?>
									<option  value="<?php  echo $row1['size_xl'].'_XL';?>">XL</option><?php } ?>
									<?php $c=0;
									for($j=0;$j<$i;$j++){
										if($sizevalue[$j] == '2X') $c++;
									}
									
									 if( $c == 0 or $workorder_id == '' ){ ?>
									<option  value="<?php  echo $row1['size_2x'].'_2X';?>">2X</option><?php  }?>
									<?php $c=0;
									for($j=0;$j<$i;$j++){
										if($sizevalue[$j] == '3X') $c++;
									}
									
									 if( $c == 0 or $workorder_id == '' ){ ?>
									<option  value="<?php  echo $row1['size_3x'].'_3X';?>">3X</option><?php  }?>
									<?php $c=0;
									for($j=0;$j<$i;$j++){
										if($sizevalue[$j] == '4X') $c++;
									}
									
									 if( $c == 0 or $workorder_id == '' ){ ?>
									<option  value="<?php  echo $row1['size_4x'].'_4X';?>">4X</option><?php } ?>
									<?php }?>
									</select>
						     
						</td>
					</tr>
					<tr>
						<td>Total : </td>
						<td>
							<div id="total">$0</div>
						</td>
					</tr>
					<tr>	
						<td>&nbsp;</td>
						<td>
							<input type="button" value="add"  style="size:auto"
									onclick="javascript: var qty = document.getElementById('base_price').value;
									                     var quantity = document.getElementById('total_qty').value;
														 
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
																				   document.getElementById('base_price').value,
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
															document.getElementById('quantity_details').innerHTML=response;  
															$('#display_order').tablesorter({widthFixed:true,
															 widgets:['zebra'],sortList:[[0,0]],headers:{5:{sorter: false}}}); }}); }});
															 workorder.showproductname('<?php echo $product_id; ?>',
															 						   '<?php echo $row['product_name']; ?>',
																					 { onUpdate:function(response,root){
																		document.getElementById('dyanmic_div').innerHTML=response;                                                                        }}); }" />
						</td>
					</tr>
				<?php 
				break;
			
			case 'server':
				extract($_POST);
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
						$insert_array[size] = $size;
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
						$insert_array[size] = $size;
						$insert_array[total] = $total;	
						$insert_array[quantity] = $quantity;
						$insert_array[order_id] = $order_id;
					
						$this->db->insert(erp_SIZE,$insert_array);
						$last_id = $this->db->last_insert_id();
						
						$sql = "select unit_price, size_alot_id from ".erp_SIZE." where product_id = '$workorder_id' and order_id = '$order_id'";
						$result = $this->db->query($sql,__FILE__,__LINE__);
						while( $row = $this->db->fetch_array($result) ){
						
							   $total = $revise_quantity * $row[unit_price];
								
							   $sql_total = "update ".erp_SIZE." set total = '$total' where size_alot_id = '$row[size_alot_id]'";
				               $this->db->query($sql_total,__FILE__,__LINE__);
							}
						
				} ?>
				<script>
					window.location = "order_n.php?order_id=<?php echo $order_id; ?>";
				</script>












				<?php	
				break;				
			}
			 $html = ob_get_contents();
			 ob_end_clean();
			 return $html;			
	}
	
	function showprice($size,$quantity,$product_id){
	        ob_start();
			$sql="select * from ".erp_PRODUCT." where product_id='$product_id'";
			$result=$this->db->query($sql,__FILE__,__LINE__);
			$row=$this->db->fetch_array($result);
			
			    if($quantity <= 5){ $base_price = 0; }
				
				if($quantity>=6 && $quantity<=12){
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
				 echo $base_price + $size_array[0];
				 
			 $html=ob_get_contents();
			 ob_end_clean();
			 return $html;
	} /////end of function showprice
	
	function showproductname($order_id=''){
			ob_start();
			$sql="select * from ". erp_PRODUCT_ORDER . " where order_id='$order_id' ORDER BY product_name";
			$result=$this->db->query($sql,_FILE_,_LINE_);
                        //echo $sql;
			while($row=$this->db->fetch_array($result)){ ?>
				<div class="profile_box1" style="font-weight:bold;background-color:#CCCCCC;float:left; width:59%;">
					<a style="color:#FF0000; font-size:15px" 
						onclick="javascript: if(this.innerHTML=='-'){
													this.innerHTML = '+';
													document.getElementById('div_<?php echo $row[workorder_id]; ?>').style.display = 'none';
													document.getElementById('wo_task<?php echo $row[workorder_id]; ?>').style.display = 'none';
													}
													else {
													this.innerHTML = '-';
													document.getElementById('div_<?php echo $row[workorder_id]; ?>').style.display = 'block';
													document.getElementById('wo_task<?php echo $row[workorder_id]; ?>').style.display = 'block';
												} ">-</a>&nbsp;<?php echo $row[product_name]; ?>
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
					Tasks : WO <?php echo $row[product_name];?>
				</div>
				
				<div id="div_<?php echo $row[workorder_id];?>">
					<div style="float:left">
					
						<div id="rework<?php echo $row[workorder_id];?>">
						  <?php
						   $sql_re = "SELECT * FROM ".erp_REWORK." WHERE order_id = '$order_id' and product_id = '$row[workorder_id]'";
						   $result_re = $this->db->query($sql_re,__FILE__,__LINE__);
						   if( $this->db->num_rows($result_re) > 0 ){
						       echo $this->ShowRework( $order_id, $row[workorder_id] ); } ?>
						</div>
						
						<?php echo $this->orderDetails( $order_id, $row[product_name], $row[product_id], $row[workorder_id] ); ?>
						
						<div id="note_div<?php echo $row[workorder_id];?>">
						  <?php echo $this->noteDetails('local',$order_id,$row[product_name],'','','',$row[workorder_id]); ?>
						</div>
                                                <div><?php echo $this->fileserver->display_files($row['product_id'], "work order" , array( "header_text_style" => 'color: #5f0000;font-size: 14px;font-weight: bold;' , "header_color" => "#f3f3f3", "main_width" => "100%" , "nostyle" => true, "class" => "profile_box1" , "main_style_overide" => 'font-weight:bold;margin-left:16px;width:621px' ));?></div>
					</div>
									
					<div style="float:right">
						<div class="form_main" id="wo_task<?php echo $row[workorder_id];?>">
							<div id="task_area" class="small_text" style="background-color:#CCCCCC;">
								
							<a href="javascript:void(0);" id="task_link<?php echo $row[workorder_id];?>" 
							   onclick="javascript:document.getElementById('task_form<?php echo $row[workorder_id];?>').style.display='';
										workorder.addTodo('WORK_ORDER',
														  'order_n.php', 
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
														echo $this->addTodo('WORK_ORDER','order_n.php','work_order_id',$order_id,$product_id,'server');
												   }?>
										
								<div class="form_bg" style="display:none;" id="task_form<?php echo $row[workorder_id];?>"></div>																					  									<div id="tab<?php echo $row['product_id'];?>"><?php echo $this->show_title($order_id,$row['product_id'],'WORK_ORDER');?></div>
								<?php echo $this->FlowChartDiv("work order", $row["workorder_id"]);?>
								
							</div>
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
				//if( $this->db->num_rows($result) > 0 ){
				while( $row = $this->db->fetch_array($result) ){
				?>
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
			   	   <th>Fabric Scrap :</th>
				   <td>
				   <span id="fabric_scrap_<?php echo $row[rework_id].$product_id; ?>"> 
						<?php echo $this->returnLink($row_fab[fabric_scrap],$product_id,'fabric_scrap_'.$row[rework_id].$product_id,'fabric_edit',$order_id,$row[rework_id]); ?>
					</span>
				  </td>
				   <th>Printer :</th>
				   <td>
				   <span id="printer_<?php echo $row[rework_id].$product_id; ?>"> 
					<?php echo $this->returnLink($row_fab[printer_used],$product_id,'printer_'.$row[rework_id].$product_id,'printer_edit',$order_id,$row[rework_id]); ?>
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
							$row_inve = $this->db->fetch_array($result_inve);
							//echo $sql_inve;
							?>
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
							   <?php if($printer==''){?>
								<th>Printer :</th>
								<td>
								  <input type="text" name="printer" id="printer"  value=""/>
								</td>
								<?php }
								else{?>
								<input type="hidden" name="printer" id="printer" value="<?php echo $printer;?>" />
								<?php }?>
								
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
							 }
							?>
							
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
		   } 
		   
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
			$sql="select a.title from tasks a,assign_task b where a.task_id=b.task_id and b.module='order' and b.order_id=$order_id";
			$result=$this->db->query($sql,__FILE__,__LINE__);
			if($this->db->num_rows($result)>0){
			while($row=$this->db->fetch_array($result)){?>
			<tr>
			<td><?php echo $row['title'];?></td>
			</tr>
			<?php
			}
			}
			
		 }
		 else{
			//echo 'product'.$pro_id;
			$sql="select a.title from tasks a,assign_task b where a.task_id=b.task_id and b.module='WORK_ORDER' and b.product_id= $pro_id and b.order_id= $order_id";
			$result=$this->db->query($sql,__FILE__,__LINE__);
			if($this->db->num_rows($result)>0){
			while($row=$this->db->fetch_array($result)){?>
			<tr>
			<td><?php echo $row['title'];?></td>
			</tr>
			<?php
			}
			}
		 }?>
		 </table>
	  <?php
	 $html=ob_get_contents();
	 ob_end_clean();
	 return $html;
	}
	
	function addTodo($module='',$profile_page='',$module_id='',$order_id='',$product_id='',$type=''){
		ob_start();
		
			$tasks = new Tasks();
			$tasks -> SetUserID($_SESSION['user_id']);
			$tasks -> SetModuleID($_SESSION['contact_id']);
			if($type == 'server'){
			//echo 'server'.$product_id;
				$tasks->AddTask('server',$module,$profile_page,$module_id,'',$module,$order_id,$product_id);}
			else{
			//echo 'local'.$product_id;
				$tasks->AddTask('local',$module,$profile_page,$module_id,'',$module,$order_id,$product_id);}
			?>
			<a href="javascript:void(0);" onClick="document.getElementById('task_link<?php echo $product_id;?>').style.display=''; 
												   document.getElementById('task_form<?php echo $product_id;?>').style.display='none';
												   return false;">cancel</a>
		<?php 
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function orderDetails( $order_id='', $product_name='', $product_id='', $workorder_id='' ){
		ob_start(); 
		?>
		
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
			     $pad='';
				 $elastic='';
				 $fabric='';
				 $lining='';
				 $zipper='';
				 $fabric_groups = array();
				 $lining_groups = array();
				 $pad_groups = array();
				 $elastic_groups = array();
				 $zipper_groups = array();
				 //print_r($zipper_groups);
				 
				 $sql_pro="select a.inventory_id, a.name from ".erp_ASSIGN_INVENTORY." a, ".erp_PRODUCT_ORDER." b where a.product_id = b.product_id and order_id = '$order_id' and b.workorder_id = '$workorder_id' and a.group_inventory_id = '0'";
			     //echo $sql_pro;
				 $result_pro = $this->db->query($sql_pro,_FILE_,_LINE_);
				 while($row_pro=$this->db->fetch_array($result_pro)){
					 $invent=$row_pro[inventory_id];
					 $invent_name=$row_pro[name];
				   //echo "inventory".$invent."<br>";
					 $sql_invent="select type_id from " . TBL_INVENTORY_DETAILS ." where inventory_id='$invent'";
				   //echo $sql_invent;
					 $result_invent = $this->db->query($sql_invent,_FILE_,_LINE_);
					 while($row_invent=$this->db->fetch_array($result_invent)){
						 $type_id=$row_invent[type_id];
					   //echo "type_id".$type_id."<br>";
						 $sql_type="select type_name from " . erp_ITEM_TYPE ." where type_id='$type_id'";
						 $result_type= $this->db->query($sql_type,_FILE_,_LINE_);
						 while($row_type=$this->db->fetch_array($result_type)){
							 $type_name=$row_type[type_name];
						  
							 switch($type_name){
								case 'Fabric': $fabric.="_".$invent_name;
												break;
								case 'Lining': $lining.="_".$invent_name;
												break;
								case'Pad': $pad.="_".$invent_name;
											break;
								case'Elastic': $elastic.="_".$invent_name;
												break;
								case'Zipper': $zipper.="_".$invent_name;
											  break;
							  }   /////end of switch
						} //// end of 3rd while
					}   //// end of 2nd while
               	 }  //// end of 1st while 
				
				$sql_group = "SELECT inventory_group_id, group_name, product_group FROM " .erp_GROUP_INVENTORY." WHERE group_id = '$product_id'";
				$result_group = $this->db->query($sql_group,_FILE_,_LINE_);
				if( $this->db->num_rows($result_group) > 0 ){
				   while( $row_group = $this->db->fetch_array($result_group) ){
				      
					 $sql_pro = "SELECT inventory_id, name FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id' and group_inventory_id = '$row_group[inventory_group_id]'";
			         //echo $sql_pro;
					 $result_pro = $this->db->query($sql_pro,_FILE_,_LINE_);
					 if( $this->db->num_rows($result_pro) > 0 ){
					  while( $row_pro = $this->db->fetch_array($result_pro) ){
					         
							 $sql_type="SELECT type_name FROM " . erp_ITEM_TYPE ." WHERE type_id='$row_group[product_group]'";
							 $result_type= $this->db->query($sql_type,_FILE_,_LINE_);
							 $row_type=$this->db->fetch_array($result_type);
								 $type_name = $row_type[type_name];
							  
								 switch($type_name){
									case 'Fabric': $fabric_group .= "=".$row_pro[name];
													break;
									case 'Lining': $lining_group .= "=".$row_pro[name];

													break;
									case 'Pad': $pad_group .= "=".$row_pro[name];
												break;
									case 'Elastic': $elastic_group .= "=".$row_pro[name];
													break;
									case 'Zipper': $zipper_group .= "=".$row_pro[name];
												  break;
								  }   /////end of switch
					     }
					   }
						if( $fabric_group != '' ){
						    $group_fabric .= $row_group[inventory_group_id].'='.$row_group[group_name].''.$fabric_group;
							$fabric_group = '';
							
							$fabric_groups[] = $group_fabric;
							$fabric_len  = count($fabric_groups);
							$group_fabric = '';
						
						} else if( $lining_group != '' ){
						           $group_linning .= $row_group[inventory_group_id].'='.$row_group[group_name].''.$lining_group;
								   $lining_group = '';
								   
								   $lining_groups[] = $group_linning;
								   $lining_len  = count($lining_groups);
								   $group_linning = '';
						
						  } else if( $pad_group != '' ){
						             $group_pad .= $row_group[inventory_group_id].'='.$row_group[group_name].''.$pad_group;
									 $pad_group = '';
									 
									 $pad_groups[] = $group_pad;
									 $pad_len  = count($pad_groups);
									 $group_pad = '';
						  
						    } else if( $elastic_group != '' ){
							           $group_elastic .= $row_group[inventory_group_id].'='.$row_group[group_name].''.$elastic_group;
									   $elastic_group = '';
									   $elastic_groups[] = $group_elastic;
									   $elastic_len  = count($elastic_groups);
									   $group_elastic = '';
							
							  } else if( $zipper_group != '' ){
							             $group_zipper .= $row_group[inventory_group_id].'='.$row_group[group_name].''.$zipper_group;
										 $zipper_group = '';
										 $zipper_groups[] = $group_zipper;
										 $zipper_len  = count($zipper_groups);
										 $group_zipper = '';
							  
							  }
				   }
				}
				// echo 'ma'.$zipper_group;
				// print_r($zipper_groups);
				$sql_check = "select * from ".erp_WORK_ORDER." where product_id = '$workorder_id' and order_id = '$order_id'";
				//echo $sql_check;
				//echo $len;
				$result_check = $this->db->query($sql_check,__FILE__,__LINE__);
				$row_check = $this->db->fetch_array($result_check);
				?>
				
				<table class="table" width="100%">
                <?php if( $fabric != '' or $fabric_len > 0 ){ ?>
                  <tr>
                    <td>Fabric Options:</td>
                    <td><div id="box_fabric_<?php echo $workorder_id.$order_id; ?>">
							<?php 
							   $len_fab = count(explode(",",$row_check['fabric']));
							   $box_fabric = "box_fabric_".$workorder_id.$order_id;
							   if( $row_check['fabric'] != '' and $len_fab < 2 ){
						           echo $this->againlink( '',$fabric,'fabric',$product_id,$order_id,$box_fabric,$fabric_groups,$workorder_id );
							  }
							  else {
								   echo $this->itemDisplay( '',$fabric,'fabric',$product_id,$order_id,$box_fabric,$fabric_groups,'',$workorder_id );
							       } ?>
                        </div>
                    </td>
                  </tr>
                  <?php } if( $zipper != '' or $zipper_len > 0 ){ ?>
                  <tr>
                    <td>
					Zipper Options:</td>
                    <td><div id="box_zipper_<?php echo $workorder_id.$order_id; ?>">
							<?php
							   $len_zip = count(explode(",",$row_check['zipper']));
						       $box_zipper = "box_zipper_".$workorder_id.$order_id;
							   if( $row_check['zipper'] != '' and $len_zip < 2 ){
								   echo $this->againlink( '',$zipper,'zipper',$product_id,$order_id,$box_zipper,$zipper_groups,$workorder_id ); 
								}
							  else {
								   echo $this->itemDisplay( '',$zipper,'zipper',$product_id,$order_id,$box_zipper,$zipper_groups,'',$workorder_id );
							       } ?>
                        </div>
                    </td>
                  </tr>


                  <?php } if( $pad != '' or $pad_len > 0 ){ ?>
                  <tr>
                    <td>
					 Pad Options :</td>
                     <td><div id="box_pad_<?php echo $workorder_id.$order_id; ?>">
					 <?php
					    $len_pad = count(explode(",",$row_check['pad']));
						$box_pad = "box_pad_".$workorder_id.$order_id;
						  if( $row_check['pad'] != '' and $len_pad < 2 ){
					 		  echo $this->againlink( '',$pad,'pad',$product_id,$order_id,$box_pad,$pad_groups,$workorder_id );
						}
						else { 
							  echo $this->itemDisplay( '',$pad,'pad',$product_id,$order_id,$box_pad,$pad_groups,'',$workorder_id );
						   } ?>
                         </div>
                     </td>
                   </tr>
                   <?php } if( $elastic != '' or $elastic_len > 0 ){ ?>
                   <tr>
                     <td>
					Elastic Options:</td>
                    <td><div id="box_elastic_<?php echo $workorder_id.$order_id; ?>">
							<?php
							 $len_elast = count(explode(",",$row_check['elastic']));
							 $box_elastic = "box_elastic_".$workorder_id.$order_id;
							  if( $row_check['elastic'] != '' and $len_elast < 2 ){ 
								  echo $this->againlink( '',$elastic,'elastic',$product_id,$order_id,$box_elastic,$elastic_groups,$workorder_id );
							  }
							  else {
								  echo $this->itemDisplay( '',$elastic,'elastic',$product_id,$order_id,$box_elastic,$elastic_groups,'',$workorder_id );
							       } ?>
                        </div>
                    </td>
                  </tr>
                  <?php } if( $lining != '' or $lining_len > 0 ){ ?>
                  <tr>
                    <td>
					Lining Options:</td>
                    <td><div id="box_lining_<?php echo $workorder_id.$order_id; ?>">
							<?php 
							  $len_lin = count(explode(",",$row_check['lining']));
							  $box_lining = "box_lining_".$workorder_id.$order_id;
							  if( $row_check['lining'] != '' and $len_lin < 2 ){
								  echo $this->againlink( '',$lining,'lining',$product_id,$order_id,$box_lining,$lining_groups,$workorder_id );
							  }
							  else {
								  echo $this->itemDisplay( '',$lining,'lining',$product_id,$order_id,$box_lining,$lining_groups,'',$workorder_id );
							       } ?>
                        </div>
                     </td>
                   </tr>
				   <?php } ?>
				   
                  </table>
                  </div>
                  </td>
                  <td>
                  <table class="table" width="100%">
                   <tr>
                    <td width="105px">Binding Options:</td>
					  <td>
					    <span id="binding<?php echo $workorder_id.''.$order_id; ?>">
						  <?php if($row_check['binding'] != '')
								     { 
									 $tgt="binding".$workorder_id.$order_id; 
									 echo $this->newlink($row_check['binding'],'Binding Options','',$workorder_id,$order_id,$tgt);  
									 
							  }
							    else { echo $this->displayOption('Binding Options',$workorder_id,$order_id,'binding',$tgt);} ?>
					    </span>
					  </td>
                 
                   </tr>
                   <tr>
                     <td>Seam Option :</td>
                   
					  <td>
					    <span id="seamoption<?php echo $workorder_id.''.$order_id; ?>">
						  <?php if($row_check['seamoption'] != '')
								     { 
									 $tgt="seamoption".$workorder_id.$order_id; 
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
					      <?php if($row_check['variableinfo'] != '')
								     { 
									 $tgt="variableinfo".$workorder_id.$order_id; 
									 echo $this->newlink($row_check['variableinfo'],'Variable Info','',$workorder_id,$order_id,$tgt); }
							    else { echo $this->displayOption('Variable Info',$workorder_id,$order_id,'variableinfo',$tgt); } ?>
					    </span>
					  </td>
                   </tr>
                   <tr>
                    <td>Profile JV5 :</td>
                   
					  <td>
					     <span id="profile_JV5<?php echo $workorder_id.''.$order_id; ?>">
					        <?php if($row_check['profile_JV5'] != '')
								       { 
									   $tgt="profile_JV5".$workorder_id.$order_id;
									   echo $this->newlink($row_check['profile_JV5'],'Profile JV5','',$workorder_id,$order_id,$tgt); }
								  else { echo $this->displayOption('Profile JV5',$workorder_id,$order_id,'profile_JV5',$tgt); } ?>
						 </span>
					  </td>
                   </tr>
                   <tr>
                    <td>Seam Crossing:</td>
                   
					  <td>
					    <span id="seamcrossing<?php echo $workorder_id.''.$order_id; ?>">
					       <?php if($row_check['seamcrossing'] != '')
								       { 
									   $tgt="seamcrossing".$workorder_id.$order_id;
									   echo $this->newlink($row_check['seamcrossing'],'Seam Crossing','',$workorder_id,$order_id,$tgt); }
								 else  { echo $this->displayOption('Seam Crossing',$workorder_id,$order_id,'seamcrossing',$tgt); } ?>
					    </span>
					  </td>
                   </tr>
                   <tr>
					   <td>Group ID :</td>
					   <?php
					   $sql_group="select group_id from ".erp_GROUP." where order_id='$order_id' and fabric_id = '$workorder_id'";
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
	
	function againlink( $show='',$value='',$item,$product_id,$order_id,$box_fabric,$value_group=array(),$workorder_id='' ){
	   ob_start();
		 
		 if( $show == '' ){
			 $sql="select * from ".erp_WORK_ORDER." where product_id = '$workorder_id' and order_id = '$order_id'";
			 //echo $sql;
			 $result=$this->db->query($sql,__FILE__,__LINE__);
			 $row = $this->db->fetch_array($result);
			 
			 if($item=='fabric')
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
							}
				$size1 = explode(",",$inv_id);	
				//echo $size1[0];
				
					$sql_name="select name from ".TBL_INVENTORY_DETAILS." where inventory_id = '$size1[0]' ";
					$result_name= $this->db->query($sql_name,__FILE__,__LINE__);
					while($row_name=$this->db->fetch_array($result_name)){
						  $show_value=$row_name['name'];
					}
				
		   }
		
		/*if($show)
		{
		$show_value=$show;
		}*/
		//echo $value;
		$len = count(explode("_",$value));
		if( $len == 2 ){
		    echo $show_value;  } 
		else {?>
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
																			{target:'<?php echo $box_fabric;?>'});"> <?php echo $show_value;?> </a>
		<?php }
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
		}
		?>
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
					while($row_option = $this->db->fetch_array($result_group)){
					?>
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
	
	function SingleInventory( $show='', $inventory='', $item='', $product_id='', $order_id='', $div_id='', $type_item_group=array(), $workorder_id='' ){
	   ob_start();
	      
		$sql_id = "SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE name = '$inventory' and product_id = '$product_id' and group_inventory_id = '0'";
		//echo $sql_id;
		$result_id = $this->db->query($sql_id,__FILE__,__LINE__);
		$row_id = $this->db->fetch_array($result_id);
		$p = '_inventory_usage';
		
		$size_dependant =  $this->checkSizeDependant('check',$row_id['xs'.$p],$row_id['s'.$p],$row_id['m'.$p],$row_id['l'.$p],$row_id['xl'.$p],$row_id['2x'.$p],$row_id['3x'.$p],$row_id['4x'.$p]);
		//echo $size_dependant;
		if( $size_dependant == 'true' ){
		  
		      echo $inventory;
		  
			$sql_inve = "update ".erp_WORK_ORDER." set $item = '$row_id[inventory_id]',assign_id = '$row_id[assign_id]' where product_id = '$workorder_id' and order_id = '$order_id'";
			//echo $sql_inve;
			$result_inve = $this->db->query($sql_inve,__FILE__,__LINE__);
		
		} else {
		    $c = $row_id[inventory_id].',';
		    $check_size = $this->check_sizedependent( $c, $product_id, $order_id, '', $workorder_id );
			if( $check_size != '' ){
				$sql_id = "SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE name = '$row_id[name]' and product_id = '$product_id'";
				$result_id = $this->db->query($sql_id,__FILE__,__LINE__);
				$row_id = $this->db->fetch_array($result_id);
				?>
				<p style="color:#FF0000";>Size Dependant</p>
				<?php
				echo $this->show_size( 'local',$product_id,$order_id,$row_id[inventory_id],$row_id[assign_id],$item,$div_id,'','','','',$workorder_id );
			}
		}
	   
	$html=ob_get_contents();
    ob_end_clean();
    return $html;
	}
	
	function GroupInventory( $show='', $inventory='', $item='', $product_id='', $order_id='', $div_id='', $type_item_group='' , $group_id = '', $workorder_id='', $z='', $group_item=array() ){
	   ob_start();
	    
		//print_r($group_item);
	    
	    $item_group = explode("=",$type_item_group);
		$p = '_inventory_usage';
		//echo 'inve>'.$inventory;
		
		$len = count($item_group);
		
		//$a = 1;
		$b = '';
		$c = '';
		
		$sql_id = "SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id' and group_inventory_id = '$item_group[0]'";
		//echo $sql_id;
		$result_id = $this->db->query($sql_id,__FILE__,__LINE__);
		
		while( $row_id = $this->db->fetch_array($result_id) ){
		
		$size_dependant =  $this->checkSizeDependant('check',$row_id['xs'.$p],$row_id['s'.$p],$row_id['m'.$p],$row_id['l'.$p],$row_id['xl'.$p],$row_id['2x'.$p],$row_id['3x'.$p],$row_id['4x'.$p]);
		
		
			if( $size_dependant == 'true' ){
					$sql = "SELECT name,inventory_id FROM ".TBL_INVENTORY_DETAILS." WHERE name = '$row_id[name]'";
					//echo $sql;
					$result = $this->db->query($sql,__FILE__,__LINE__);
					$row = $this->db->fetch_array($result);
					//echo $size_dependant;
					
					$b .= $row[name].',';
					$x .= $row[inventory_id].',';
			} else{
					$sql = "SELECT name,inventory_id FROM ".TBL_INVENTORY_DETAILS." WHERE name = '$row_id[name]'";
					//echo $sql;
					$result = $this->db->query($sql,__FILE__,__LINE__);
					$row = $this->db->fetch_array($result);
					$c .= $row[inventory_id].',';
					//$y = 2; 
			 }
		
		 }
		 //echo 'group->'.$type_item_group.'-'.$group_id;
		  //$size_depend = explode(",",$c);
		  ?>
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
		  
		  <?php 
		  $inv = explode(",",$b);
		  $len3 = count($inv);
		  if( $b != '' ){
		    echo '<br/> ( ';
		  }
		  for($i=0;$i<($len3-1);$i++){
		     echo $inv[$i];
			 
			 if($inv[$i+1]){ echo ',';}
			 
		  }
		  if( $b != '' ){ echo ' )'; }
		  //echo 'group->'.$type_item_group.'-'.$group_id;
		  $merge = $item_group[0].','.$x;
		  
		  $sql_up = "UPDATE ".erp_WORK_ORDER." Set $item = '', assign_id = '$row_id[assign_id]' WHERE product_id = '$workorder_id' and order_id = '$order_id'";
		  //echo $sql_inve;
	      $this->db->query($sql_up,__FILE__,__LINE__);
		  
		  if( $x != '' ){
		  $sql = "UPDATE ".erp_WORK_ORDER." Set $item = '$merge', assign_id = '$row_id[assign_id]' WHERE product_id = '$workorder_id' and order_id = '$order_id'";
		  //echo $sql_inve;
	      $this->db->query($sql,__FILE__,__LINE__);
		  }
		  
		  
				
		  //$check_size1 = $this->check_sizedependent( $c,$product_id,$order_id,$item_group[0],$workorder_id );
		  //echo 'b'.$b.'c'.$c;
		  if( $c != '' ){
			  $sql_sub = "select gp_id from ".erp_PRODUCT_ORDER." where workorder_id = '$workorder_id'";
			  //echo $sql_sub;
			  $result_sub = $this->db->query($sql_sub,__FILE__,__LINE__);
			  $row_sub = $this->db->fetch_array($result_sub);
				
				if( $row_sub[gp_id] == 0 )
				{ $product = $workorder_id; }
				else
				{ $product = $row_sub[gp_id]; }
				
			  $sql_size = "SELECT * FROM ".erp_SIZE." WHERE order_id = '$order_id' and product_id = '$product'";
			  //echo $sql_size;
			  $result_size = $this->db->query($sql_size,__FILE__,__LINE__);
			  $inve_id = explode(',',$c);
			  ?>
			  <table width="100%">
			  <?php $j = 1;
			   while($row_size = $this->db->fetch_array($result_size)){
				  $size1 = explode('_',$row_size[size]);
				  $size = strtolower("$size1[1]");
				  $len = count($inve_id);
				  $a = '';
				  
				   for($i=0;$i<($len-1);$i++){
					  
					
					  $sql = "SELECT name FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id' and inventory_id = '$inve_id[$i]' and ".$size."_inventory_usage <> '0' and group_inventory_id = '$item_group[0]'";
					  //echo $sql;
					  $result = $this->db->query($sql,__FILE__,__LINE__);
					  if( $this->db->num_rows($result) > 0 ){
						  $a .= $inve_id[$i].',';
					   }
					}
			  
					$inve = explode(',',$a);
					$len2 = count($inve);
					//echo 'len'.$len2;
					if( $inve[0] != '' ){
						$row = $this->db->fetch_array($result);
						?>
						
						 <tr>
						   <td><?php echo $size1[1]; ?></td>
						   <td>:</td>
						   <td>
						   <?php
							  for($i=0;$i<($len2-1);$i++){
								 echo $this->invenName($inve[$i]);
								 
								 if( $inve[$i+1] != '' ){ echo ',';}
								 
							   }
						   ?>
						   </td>
						 </tr>	
						
				  <?php }
				    $y = $a.'_'.$size;
				    $y = str_replace(",_","_","$y");
					if( $j == 1 ){
					
					    $sql_del = "DELETE FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' and product_id = '$workorder_id' AND option_type = '$item'";
		                $this->db->query($sql_del,__FILE__,__LINE__);
		  
						$insert_array = array();
						$insert_array[group] = $item_group[0];				
						$insert_array[product_id] = $workorder_id;
						$insert_array[order_id] = $order_id;
						$insert_array[option_type] = $item;
						$insert_array[$size.'_size_dependant'] = $y;	
						
						$this->db->insert(erp_SIZE_DEPENDENT,$insert_array);
					} else if( $j > 1 ){
					    $sql = "UPDATE ".erp_SIZE_DEPENDENT." SET ".$size."_size_dependant = '$y' WHERE order_id = '$order_id' and product_id = '$workorder_id' and option_type = '$item'";
						$this->db->query($sql,__FILE__,__LINE__);
					
					  }
					 $j++;
				 }?>
			</table>
	     <?php
		 }
	$html=ob_get_contents();
    ob_end_clean();
    return $html;
	}
	
	function SplitInventory( $product_id='', $type_item='' ){
	   ob_start();
		$b = '';
	    $c = '';
		$p = '_inventory_usage';
		$explode_type_item = explode("_",$type_item);
		
		$len_type_item = count($explode_type_item);
		
		for($i=1;$i<$len_type_item;$i++){
				   
			$sql_id = "SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE name = '$explode_type_item[$i]' and product_id = '$product_id' and group_inventory_id = '0'";
			//echo $sql_id;
			$result_id = $this->db->query($sql_id,__FILE__,__LINE__);
			$row_id = $this->db->fetch_array($result_id);
			
			$size_dependant =  $this->checkSizeDependant('check',$row_id['xs'.$p],$row_id['s'.$p],$row_id['m'.$p],$row_id['l'.$p],$row_id['xl'.$p],$row_id['2x'.$p],$row_id['3x'.$p],$row_id['4x'.$p]);
			
			if( $size_dependant == 'true' ){
				$sql = "SELECT name,inventory_id FROM ".TBL_INVENTORY_DETAILS." WHERE name = '$row_id[name]'";
				//echo $sql;
				$result = $this->db->query($sql,__FILE__,__LINE__);
				$row = $this->db->fetch_array($result);
				//echo $size_dependant;
				
				$b .= $row[name].',';
				//$x = 1;
			} else{
			    $sql = "SELECT name,inventory_id FROM ".TBL_INVENTORY_DETAILS." WHERE name = '$row_id[name]'";
				//echo $sql;
				$result = $this->db->query($sql,__FILE__,__LINE__);
				$row = $this->db->fetch_array($result);
				$c .= $row[inventory_id].',';
				//$y = 2; 
			}
		 }//end of for
		 $a = $b.'_'.$c;
		 return $a;
	 
	 $html=ob_get_contents();
     ob_end_clean();
     return $html;
	 }

	function check_sizedependent( $c='', $product='', $order_id='', $group_id='', $workorder_id='' ){
	   ob_start();
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
			   
		$html=ob_get_contents();
		ob_end_clean();
		return $html;	
	  }
	
	function itemDisplay( $show='',$type_item='',$item='',$product_id='',$order_id='',$div_id='',$type_item_group=array(),$z='',$workorder_id='' ){
	   ob_start();
		?>
	   <!--<input type="text" id="group_array" value="<?php print_r($type_item_group); ?>" />-->
	   
	   <?php
		$sql_size = "SELECT * FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' and product_id = '$workorder_id' and option_type = '$item'";
		//echo $sql_size;
		$result = $this->db->query($sql_size,__FILE__,__LINE__);
		$row = $this->db->fetch_array($result);
		
		$len = count($type_item_group);
		
		
		
		for($i=0;$i<$len;$i++){
			$e = explode("=",$type_item_group[$i]);
			
			if( $e[0] == $row['group'] ){
				   $selected_group = $type_item_group[$i];
			}
			
		}
		$num = count($type_item_group);
		//echo $type_item.' - '.$type_item_group;
		$explode_type_item = explode("_",$type_item);
		
		$len_type_item = count($explode_type_item);
		//echo $len_type_item.' - '.$explode_type_item[1];
		if( $len_type_item == 2 and $num < 1 ){
			
			echo $this->SingleInventory( $show, $explode_type_item[1], $item, $product_id, $order_id, $div_id, $type_item_group, $workorder_id );
			
	    }
		 /*else if( $type_item == '' and $num > 0 ){
			
			//$item_group = explode("=",$type_item_group);
			
			echo $this->GroupInventory( $show, $explode_type_item[1], $item, $product_id, $order_id, $div_id, '',$row[group], $workorder_id, $z, $type_item_group );
			
			}  ////end of else if*/
			
		else if( $len_type_item > 2 or ( $len_type_item == 2 and $num > 0 ) or ( $len_type_item < 2 and $num > 0 ) ){
			//print_r($explode_type_item);
				   $ro;
				   $b = '';
			       $c = '';
				   $p = '_inventory_usage';
				   $item_group = explode("=",$type_item_group);
				   
				   $split = $this->SplitInventory( $product_id, $type_item );
				   $split1 = explode("_",$split);
				   $b = $split1[0];
				   $c = $split1[1];
				   //echo 'split'.$split;
				    //echo ' b'.$b.' c'.$c; 
					
					$check_size = $this->check_sizedependent( $c, $product_id, $order_id, '', $workorder_id );
					
			        if( $this->db->num_rows($result) == 0 || $z == 1 ){
					
					$sql_check = "select * from ".erp_WORK_ORDER." where product_id = '$workorder_id' and order_id = '$order_id'";
					//echo $sql_check;
				
					$result_check = $this->db->query($sql_check,__FILE__,__LINE__);
					$row_check = $this->db->fetch_array($result_check);
					$wk = explode(",",$row_check[$item]);
					$num1 = count($wk);
					
					if( $num1 < 2 || $z == 1 ){
					?>
					<select id="<?php echo $item ; ?>" 
							onchange="javascript: var inve_name = this.value;
							                  if(confirm('Are You Sure To Change This Inventory')){
											    workorder.deletethis('<?php echo $product_id; ?>',
																	 '<?php echo $order_id; ?>',
																	 '<?php echo $item; ?>',
																	 '<?php echo $workorder_id; ?>',
																	 {preloader:'prl'});
												var group_item = new Array();
											    <?php $i=0; foreach($type_item_group as $type_itm){ 
												?>group_item[<?php echo $i; ?>] = '<?php echo $type_itm;?>';<?php
													$i++;
											    }?> 
												var k = inve_name.split('_');
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
																				  1,
																				  group_item,
																	      {target:'<?php echo $div_id; ?>'});
													 
													 
												 } else {
												  if( inve_name != 'multiple' ){
													  
													  var srl = inve_name.split('_');
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
													 } else {
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
																  {preloader:'prl',target:'<?php echo $div_id;?>'});
																			 
													  }
												 }
											   }">
						<option value="">-select-</option>
						<?php
							$inve = explode(',',$b);
							$len = count($inve);
							for($i=0;$i<($len-1);$i++){
							$sql_inve = "SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE name = '$inve[$i]' and product_id = '$product_id' and group_inventory_id = '0'";
							$result_inve = $this->db->query($sql_inve,__FILE__,__LINE__);
							$row_inve = $this->db->fetch_array($result_inve); 
							$ro=$row_inve[assign_id]; ?>
							
						<option  value="<?php echo $row_inve[inventory_id].'_'.$row_inve[assign_id]; ?>" <?php if($inve[$i] == $field)	echo "selected=selected"; ?> ><?php echo $inve[$i]; ?></option>
						
						<?php } //end of for
						if( $c != '' and $check_size != '' ){?>
						<option value="multiple">Size Dependant</option>
						<?php } 
						if( $type_item_group != '' ){
						    $num = count($type_item_group);
							//echo $len;
							for($i=0;$i<$num;$i++){
							$split_group = explode("=",$type_item_group[$i]);
							//echo $e[0];
							
						?>
						<option value="<?php echo 'group_'.$type_item_group[$i]; ?>"><?php echo $split_group[1]; ?></option>
						<?php } } ?>
					</select>
			
			<?php } else {
			        for($i=0;$i<$len;$i++){
						$f = explode("=",$type_item_group[$i]);
						
						if( $f[0] == $wk[0] ){
							   $select_group = $type_item_group[$i];
						}
						
					}
					//echo $select_group.'wk'.$wk[0];
			        echo $this->GroupInventory( '',$type_item,$item,$product_id,$order_id,$div_id,$select_group,$wk[0],$workorder_id, '', $type_item_group );
			
			      }
			 } else {
			 		if($row[group] != ''){
			        	echo $this->GroupInventory( '',$type_item,$item,$product_id,$order_id,$div_id,$selected_group,$row['group'],$workorder_id, '', $type_item_group );
					}
					else {
						echo $this->show_size( 'multiple',$product_id,$order_id,$c,'',$item,$div_id,'',$type_item,$type_item_group,'',$workorder_id );
					} 	
			   }
        } //end of if
            $html=ob_get_contents();
            ob_end_clean();
            return $html;		
		
     } /////end of function itemDisplay
	 
	 function show_size( $runat='',$product_id='',$order_id='',$inventory_id='',$assign_id='',$item='',$div_id='',$inve_name='',$type_item='',$type_item_group=array(),$z='',$workorder_id='' ){
	    ob_start();
		switch($runat){ //, erp_size b
		   case 'local':
		   
		    $sql_sub = "select gp_id from ".erp_PRODUCT_ORDER." where workorder_id = '$workorder_id'";
			//echo $sql_sub;
			$result_sub = $this->db->query($sql_sub,__FILE__,__LINE__);
			$row_sub = $this->db->fetch_array($result_sub);
			
			if( $row_sub[gp_id] == 0 )
			{ $product = $workorder_id; }
			else
			{ $product = $row_sub[gp_id]; }
			 
		   
		   $sql_size = "SELECT * FROM ".erp_SIZE." WHERE order_id = '$order_id' and product_id = '$product'";
		   //echo $sql_size;
		   $result_size = $this->db->query($sql_size,__FILE__,__LINE__);
		   $a = ''; ?>
		   <table class="table">
		   <?php
		   while($row_size = $this->db->fetch_array($result_size)){
		      $size1 = explode('_',$row_size[size]);
			  $size = strtolower("$size1[1]");
		      
			  
		   $sql = "SELECT name,".$size."_inventory_usage FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id' and inventory_id = '$inventory_id' and ".$size."_inventory_usage <> '0' and group_inventory_id = '0'";
		   //echo $sql;
		   $result = $this->db->query($sql,__FILE__,__LINE__);
		   if( $this->db->num_rows($result) > 0 ){
			   $row = $this->db->fetch_array($result);
			   $a .= $size.',';
			   ?>
			   
			     <tr>
				   <td><?php echo $size1[1]; ?></td>
				   <td>:</td>
				   <td>
					  <?php echo $this->invenName($inventory_id);
					  //echo $this->sizeLink('multiple',$product_id,$order_id,$inventory_id,$assign_id,$item,$div_id,$row[name],$type_item,$size,''); 
					  echo $this->show_size('server',$product_id,$order_id,$inventory_id.'_'.$size,$assign_id,$item,$div_id,$inve_name,$type_item,'','',$workorder_id); ?>
			       </td>
				 </tr>
				 
				<?php

	   	          }
		        } ?>
				 </table>
		<?php
		break;
		case 'multiple':
		 
		   ?>
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
		   <?php
		    $sql_sub = "select gp_id from ".erp_PRODUCT_ORDER." where workorder_id = '$workorder_id'";
			//echo $sql_sub;
			$result_sub = $this->db->query($sql_sub,__FILE__,__LINE__);
			$row_sub = $this->db->fetch_array($result_sub);
			
			if( $row_sub[gp_id] == 0 )
			{ $product = $workorder_id; }
			else
			{ $product = $row_sub[gp_id]; }
			 //echo $row_sub[gp_id].'--'.$product;
		   
		   $sql_size = "SELECT * FROM ".erp_SIZE." WHERE order_id = '$order_id' and product_id = '$product'";
		   //echo $sql_size;
		   $result_size = $this->db->query($sql_size,__FILE__,__LINE__);
		   $a = '';
		   
		   $inve_id = explode(',',$inventory_id);
		   ?>
		   <table class="table">
		   <?php
		   while($row_size = $this->db->fetch_array($result_size)){
		      $size1 = explode('_',$row_size[size]);
			  $size = strtolower("$size1[1]");
 			  $len = count($inve_id);
			  
			  $b = '';
			  
			  
			  for($i=0;$i<($len-1);$i++){
			   if( $z == '' ){
			     $sql = "SELECT name,".$size."_inventory_usage FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id' and inventory_id = '$inve_id[$i]' and ".$size."_inventory_usage <> '0' and group_inventory_id = '0'";
			   } else {
			     $sql = "SELECT name,".$size."_inventory_usage FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id' and group_inventory_id = '$z' and inventory_id = '$inve_id[$i]' and ".$size."_inventory_usage <> '0'";
			   
			   }
			   //echo $sql;
			   $result = $this->db->query($sql,__FILE__,__LINE__);
			   if( $this->db->num_rows($result) > 0 ){
				   
				   $b .= $inve_id[$i].',';
				   }
			   }//end of for
			   //echo $b.'--'.$inventory_id;
			   
			    $inve = explode(',',$b);
			    $len2 = count($inve);
				//echo 'len'.$len2;
			    if( $inve[0] != '' ){
			    $row = $this->db->fetch_array($result);
			    if( $len2 == 2 ){ ?>
				
			     <tr>
				   <td><?php echo $size1[1]; ?></td>
				   <td>:</td>
				   <td>
				   <?php echo $this->invenName($inve[0]);
				   
					     echo $this->show_size('server',$product_id,$order_id,$inve[0].'_'.$size,$assign_id,$item,$div_id,$inve_name,$type_item,$type_item_group,$z,$workorder_id);
					
				   ?>
			       </td>
				 </tr>
			  <?php
			  } else {
			  
				  $sql = "SELECT option_type,".$size."_size_dependant FROM ".erp_SIZE_DEPENDENT." WHERE product_id = '$workorder_id' and order_id = '$order_id' and option_type = '$item' and ".$size."_size_dependant <> ''";
				   //echo $sql;
				  $result = $this->db->query($sql,__FILE__,__LINE__);
				  if( $this->db->num_rows($result) > 0 ){
				  $row_size = $this->db->fetch_array($result); ?>
				  
					  <tr>
					   <td><?php echo $size1[1]; ?></td>
					   <td>:</td>
					   <td>
						  <?php
						  echo $this->sizeLink('multiple',$product_id,$order_id,$row_size[$size.'_size_dependant'],$assign_id,$item,$div_id,'',$type_item,$size,$b,$type_item_group,$z,$workorder_id);
						  ?>
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
			     }//end of while ?>
			</table>
		<?php
		break;
		case 'server':
			
			$sql_size = "SELECT * FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' and product_id = '$workorder_id' and option_type = '$item'";
		   //echo $sql_size;
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
				
				$this->db->insert(erp_SIZE_DEPENDENT,$insert_array);
				
				echo $inve_name;
			}
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
																	   }});
																	   ">
						 <option value="">-Select-</option>
						 <?php
						   $len1 = count($inve);
						   for($i=0;$i<($len1-1);$i++){
						   $sql_id = "SELECT name FROM ".erp_ASSIGN_INVENTORY." WHERE inventory_id = '$inve[$i]'";
						   $result_id = $this->db->query($sql_id,__FILE__,__LINE__);
						   $row_id = $this->db->fetch_array($result_id);
						   
						 ?>
						  <option value="<?php echo $inve[$i].'_'.$size; ?>"><?php echo $row_id[name]; ?></option>
						  <?php } ?>
				</select>
			</span>
		 <?php
		 break;
		 
		 case 'multiple':
		 $inve = explode('_',$inventory_id);
		 ?>
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
					
					$sql="select * from ".erp_WORK_ORDER." where product_id = '$product_id' and order_id = '$order_id'";
					$result = $this->db->query($sql,__FILE__,__LINE__);
					$row=$this->db->fetch_array($result);
					echo $row[$type_name];
					
					$sql_del = "DELETE FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' and product_id = '$product_id' AND option_type = '$item'";
		            $this->db->query($sql_del,__FILE__,__LINE__);
		      }//end of else
		  
		  $html = ob_get_contents();
		  ob_end_clean();
		  return $html;												
	  } ///// end of function update_inve
	
function noteDetails( $runat='',$order_id='',$product_name='',$note_name='',$note_content='',$contact_id='',$product_id='' ){
		  ob_start();
		  //echo $product_id;
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
                               <option value="Art">Art</option>
                               <option value="Print">Print</option>
                               <option value="Transfer">Transfer</option>
                               <option value="Cutting">Cutting</option>
                               <option value="Sewing">Sewing</option>
                               <option value="Shipping">Shipping</option>
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
						$row_order=$this->db->fetch_array($result_order);
						
					?>
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
					
					$sql_name = "Select * from ".TBL_CONTACT." where company = '$row_order[vendor_contact_id]' and contact_id = '$row_order[contact_id]'";
					$result_name = $this->db->query($sql_name,__FILE__,__LINE__);
					$row_name=$this->db->fetch_array($result_name);
					
					
					if($row[note_type]=='Art'){ ?>
					<tr>
						<th>Art :</th>
                        <td><?php echo $row['note_content'].'........................'.$row_name['first_name'].' '.$row_name['last_name']; ?></td>
                    </tr>
                    <?php } if($row[note_type]=='Print'){ ?>
                    <tr>
                        <th>Print :</th>
                        <td><?php echo $row['note_content'].'........................'.$row_name['first_name'].' '.$row_name['last_name']; ?></td>
                    </tr>
                    <?php } if($row[note_type]=='Transfer'){ ?>
                    <tr>
                        <th>Transfer :</th>
                        <td><?php echo $row['note_content'].'........................'.$row_name['first_name'].' '.$row_name['last_name']; ?></td>
                    </tr>
                    <?php } if($row[note_type]=='Cutting'){ ?>
                    <tr>
                        <th>Cutting :</th>
                        <td><?php echo $row['note_content'].'........................'.$row_name['first_name'].' '.$row_name['last_name']; ?></td>
                    </tr>
                    <?php } if($row[note_type]=='Sewing'){ ?>
                    <tr>
                        <th>Sewing :</th>
                        <td><?php echo $row['note_content'].'........................'.$row_name['first_name'].' '.$row_name['last_name']; ?></td>
                    </tr>
                    <?php } if($row[note_type]=='Shipping'){ ?>
                    <tr>
                        <th>Shipping :</th>
                        <td><?php echo $row['note_content'].'........................'.$row_name['first_name'].' '.$row_name['last_name']; ?></td>
                    </tr>
                    <?php } 

					 
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
		   //echo $sql_g;
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
			//echo $sql;
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
	
	  function updatefield($shipping_charge='',$old_shipping_charge='',$multiplier='',$old_multiplier='',$total='',$choice=''){
	      ob_start();
              $ups = new ups();
              $calc = $ups->estamate_shipping_by_module( 'order' , $_REQUEST['order_id'] , $shipping_charge);
              
              $shipping_type = $shipping_charge;
              $shipping_charge = $calc;
	      switch($choice){
			 case 'shipping':
				 $update_sql_array = array();				
				 $update_sql_array["shipment_type"] = $shipping_type;
                                 $update_sql_array["shipping_charges"] = $shipping_charge;

				 $this->db->update(erp_ORDER,$update_sql_array,'order_id',$_REQUEST[order_id]);
                                 echo $calc;
			 break;
			 case 'multiplier':
				 $update_sql_array = array();				
				 $update_sql_array[multiplier] = $multiplier;
				 $this->db->update(erp_ORDER,$update_sql_array,'order_id',$_REQUEST[order_id]);
			 break;
		   }  /////end of switch
		      $this->total_price = ($this->total_price + $shipping_charge) * $multiplier;
			 // echo $this->total_price.' '.$shipping_charge.' '.$multiplier;
		 
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	  }  ////////////end of function updatefield
	  
	  function calculate($total='',$shipping_charge='',$multiplier=''){
	      ob_start();
		      $sql = "Select shipping_charges,multiplier from ".erp_ORDER." where order_id = '$_REQUEST[order_id]'";
			  $result = $this->db->query($sql,__FILE__,__LINE__);
			  $row = $this->db->fetch_array($result);
					
			  $total = ($total + $row[shipping_charges]) * $row[multiplier];
			  echo '$'. number_format($total,2);
			  
			  $update_sql_array = array();				
			  $update_sql_array[grant_total] = $total;
			  $this->db->update(erp_ORDER,$update_sql_array,'order_id',$_REQUEST[order_id]);
		  
		  $html=ob_get_contents();
		  ob_end_clean();
		  return $html;
	  }  ////////////end of function calculate
	  
	  function deletethis( $product_id='', $order_id='', $option_type='', $workorder_id='' ){
	      ob_start();
		  if( $option_type == '' ){
		  
		      $sql="SELECT workorder_id FROM ".erp_PRODUCT_ORDER." WHERE gp_id = '$workorder_id'";
			  $result=$this->db->query($sql,__FILE__,__LINE__);
			  if( $this->db->num_rows($result) > 0 ){
				  while( $row=$this->db->fetch_array($result) ){
					 $sub_product_id = $row[workorder_id];
					 
					 $sql="DELETE FROM ".erp_WORK_ORDER." WHERE order_id = '$order_id' AND product_id = '$sub_product_id'";
					 $this->db->query($sql,__FILE__,__LINE__);
					 
					 $sql="DELETE FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' AND product_id = '$sub_product_id'";
			         $this->db->query($sql,__FILE__,__LINE__);
					 
					 $sql="DELETE FROM ".erp_ASSIGN_FCT." WHERE  module_id = '$sub_product_id'";
			         $this->db->query($sql,__FILE__,__LINE__);
					 
					 $sql = "DELETE FROM ".TBL_NOTES." WHERE product_id = '$sub_product_id'";
				     $this->db->query($sql,__FILE__,__LINE__);
					 
					 $sql = "DELETE FROM ".erp_REWORK." WHERE order_id = '$order_id' AND product_id = '$sub_product_id'";
				     $this->db->query($sql,__FILE__,__LINE__);
					 
					 $sql = "DELETE FROM ".erp_GROUP." WHERE fabric_id = '$sub_product_id'";
				     $this->db->query($sql,__FILE__,__LINE__);
					 
					 $sql_t="select task_id from ".ASSIGN_TASK." where order_id = '$order_id' AND product_id = '$sub_product_id' ";
					 $result_t=$this->db->query($sql_t,__FILE__,__LINE__);
					 while( $row_t=$this->db->fetch_array($result_t) ){
					 	
						$sql = "DELETE FROM ".erp_TASKS_RELATION." WHERE task_id = '$row_t[task_id]'";
				        $this->db->query($sql,__FILE__,__LINE__);
						
						$sql = "DELETE FROM ".TASKS." WHERE task_id = '$row_t[task_id]'";
				        $this->db->query($sql,__FILE__,__LINE__);
					}
					$sql = "DELETE FROM ".ASSIGN_TASK." WHERE order_id = '$order_id' AND product_id = '$sub_product_id'";
				    $this->db->query($sql,__FILE__,__LINE__);
				 }
			   }
			   
			    $sql_tp="select task_id from ".ASSIGN_TASK." where order_id = '$order_id' AND product_id = '$workorder_id' ";
				$result_tp=$this->db->query($sql_tp,__FILE__,__LINE__);
			    while( $row_tp=$this->db->fetch_array($result_tp) ){
					
					$sql = "DELETE FROM ".erp_TASKS_RELATION." WHERE task_id = '$row_tp[task_id]'";
					$this->db->query($sql,__FILE__,__LINE__);
					
					$sql = "DELETE FROM ".TASKS." WHERE task_id = '$row_tp[task_id]'";
					$this->db->query($sql,__FILE__,__LINE__);
				}
				
				$sql = "DELETE FROM ".ASSIGN_TASK." WHERE order_id = '$order_id' AND product_id = '$workorder_id'";
				$this->db->query($sql,__FILE__,__LINE__);
			
			    $sql = "DELETE FROM ".erp_GROUP." WHERE fabric_id = '$workorder_id'";
				$this->db->query($sql,__FILE__,__LINE__);
			   
			   $sql = "DELETE FROM ".erp_REWORK." WHERE order_id = '$order_id' AND product_id = '$workorder_id'";
			   $this->db->query($sql,__FILE__,__LINE__);
			   
			   $sql = "DELETE FROM ".TBL_NOTES." WHERE product_id = '$workorder_id'";
			   $this->db->query($sql,__FILE__,__LINE__);
			   
			  $sql="DELETE FROM ".erp_ASSIGN_FCT." WHERE  module_id = '$workorder_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
			   
			  $sql="DELETE FROM ".erp_WORK_ORDER." WHERE order_id = '$order_id' AND product_id = '$workorder_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
			  
			  $sql="DELETE FROM ".erp_SIZE." WHERE order_id = '$order_id' AND product_id = '$workorder_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
			  
			  $sql="DELETE FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' AND product_id = '$workorder_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
			  
			  $sql="DELETE FROM ".erp_PRODUCT_ORDER." WHERE workorder_id = '$workorder_id' OR gp_id = '$workorder_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
			  
		  ?>
		  <script>
			 window.location = "order_n.php?order_id=<?php echo $order_id; ?>";
		  </script>
		  
		  <?php 
		 } else {
		    $sql="DELETE FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' AND product_id = '$workorder_id' AND option_type = '$option_type'";
			$this->db->query($sql,__FILE__,__LINE__);
		  
		  }
		  $html=ob_get_contents();
		  ob_end_clean();
		  return $html;
	  }  ////////////end of function deletethis
	  
          function get_value_from_order( $field , $order_id ){
              $sql = "SELECT * FROM erp_order WHERE order_id = '$order_id'";
              $order_info = $this->db->fetch_assoc( $this->db->query($sql) );
              switch( $field ){
                  default:
                      return $order_info[$field];
                  break;
                  case "shipping_charges":
                      return "$" . $order_info[$field];
                  break;    
              }
          }
          
	  function edit_value($shipping_charge=''){
	      ob_start(); 
			 if($shipping_charge!=''){
			 //echo $total_price; 
			 echo '$'.$shipping_charge; 
			 }
			 else{
			  echo '$0';
			 }
						   
		  $html=ob_get_contents();
		  ob_end_clean();
		  return $html;
	  }  /////end of function edit_value
	  
	  function returnLink( $variable='', $contact_id='', $div_id='', $choice='', $order_id='', $people_contact_id='', $workorder_id='' ){
		  ob_start();
		  switch($choice) {
		     case 'printer_edit':
			 	
				if($variable !=''){ ?>
					  <a href="javascript:void(0);"
					  		onclick="javascript: workorder.showdropdown('<?php echo $variable; ?>',
																		'<?php echo $contact_id; ?>',
																		'<?php echo $div_id; ?>',
																		'<?php echo $choice; ?>',
																		'<?php echo $order_id;?>',
																		'',
																		'<?php echo $people_contact_id; ?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					<?php echo $variable; ?></a>
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
					</a>
					<?php }
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
					</a>
					<?php }
			 break;
			 
		     case 'people':
			 		$sql_order = "Select * from ".erp_ORDER." where order_id = '$order_id'";
					$result_order = $this->db->query($sql_order,__FILE__,__lINE__);
					$row_order=$this->db->fetch_array($result_order);
					$contact_id = $row_order['vendor_contact_id'];
				
			        $sql_name = "Select * from ".TBL_CONTACT." where company = '$contact_id' and contact_id = '$row_order[contact_id]'";
					$result_name = $this->db->query($sql_name,__FILE__,__lINE__);
					$row_name=$this->db->fetch_array($result_name);

			        if($variable !=''){ ?>
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
                        
					
						$sql_phone = "Select * from ".CONTACT_PHONE." where contact_id = '$row_order[contact_id]'";
					    $result_phone = $this->db->query($sql_phone,__FILE__,__lINE__);
					    $row_phone=$this->db->fetch_array($result_phone);
						if($row_phone['number']){ 	
						echo substr($row_phone['number'], 0, 3).'-'.substr($row_phone['number'], 3, 3).'-'.substr($row_phone['number'], 6, 4).'<br/>';
						}

					
						$sql_email = "Select * from ".CONTACT_EMAIL." where contact_id = '$row_order[contact_id]'";
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
				if($variable !=''){ ?>
					District Office :<a href="javascript:void(0);" 
							onclick="javascript: workorder.showdropdown('<?php echo $variable; ?>',
																	    '',
																	    '<?php echo $div_id; ?>',
																	    '<?php echo $choice; ?>',
																	    '<?php echo $order_id; ?>',
																	   { target: '<?php echo $div_id; ?>'}
																	   ); ">
					<?php echo '<br>'. $variable; ?></a><?php 
					$sql_address = "Select * from ".TBL_MODULE_ADDRESS." where module_id = '$order_id'";
	                $result_address = $this->db->query($sql_address,__FILE__,__lINE__);			   
	                $row_address=$this->db->fetch_array($result_address);
					echo '<br>'.$row_address['city'].' '.$row_address['state'].' '.$row_address['zip'].'<br>'.$row_address['country'];
				}
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: workorder.showdropdown('',
																		'',
																		'<?php echo $div_id; ?>',
																		'<?php echo $choice; ?>',
																		'<?php echo $order_id; ?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			 break;
			 case 'billing_address':
				if($variable !=''){ ?>
					District Office :<a href="javascript:void(0);" 
							onclick="javascript: workorder.showdropdown('<?php echo $variable; ?>',
																	    '<?php echo $contact_id; ?>',
																	    '<?php echo $div_id; ?>',
																	    '<?php echo $choice; ?>',
																	    '<?php echo $order_id; ?>',
																	   { target: '<?php echo $div_id; ?>'}
																	   ); ">
					<?php echo '<br>'. $variable; ?></a><?php 
					$sql_address = "Select * from ".TBL_MODULE_ADDRESS." where module_id = '$order_id'";
	                $result_address = $this->db->query($sql_address,__FILE__,__lINE__);			   
	                $row_address=$this->db->fetch_array($result_address);
					echo '<br>'.$row_address['city'].' '.$row_address['state'].' '.$row_address['zip'].'<br>'.$row_address['country'];
				}
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: workorder.showdropdown('',
																		'<?php echo $contact_id; ?>',
																		'<?php echo $div_id; ?>',
																		'<?php echo $choice; ?>',
																		'<?php echo $order_id; ?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			 break;
			 /*case 'product_name':
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
			 break;*/
			 case 'quantity':
				
				$sql = "Select distinct * from ".erp_SIZE." where product_id ='$workorder_id'";
	            $result = $this->db->query($sql,__FILE__,__LINE__);
				$i = 1;
	            while($row = $this->db->fetch_array($result)){ 
				if( $row[quantity] !='' ){?>
				<span id="<?php echo $div_id.''.$i; ?>">
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
																	   ); ">
					<?php echo $row[quantity]; ?></a>
				</span><br/>
					<?php
				 } else { ?>
				     <span id="<?php echo $div_id.''.$i; ?>">
					  <a href="javascript:void(0);" 
							onclick="javascript: workorder.showTextBox('N/A',
																		'<?php echo $contact_id; ?>',
																		'<?php echo $div_id.','.$i; ?>',
																		'<?php echo $choice; ?>',
																		'<?php echo $order_id; ?>',
																		'<?php echo $row[size_alot_id]; ?>',
																		'<?php echo $people_contact_id; ?>',
																		'<?php echo $row[size]; ?>',
																		'<?php echo $workorder_id; ?>',
															  { target: '<?php echo $div_id.''.$i; ?>'}
																		); ">
					  N/A</a>
					</span><br/>
				   <?php 
				     }
				    $i++; }//end of while
			 break;
			 case 'size':
			    
			    $sql = "Select size,size_alot_id,quantity from ".erp_SIZE." where product_id ='$workorder_id'";
	            $result = $this->db->query($sql,__FILE__,__LINE__);
				$i = 1;
	            while($row = $this->db->fetch_array($result)){
				if( $row[size] != '' ){
				$size = explode('_',$row[size]); ?>
				<span id="<?php echo $div_id.''.$i; ?>">
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
															">
					<?php echo $size[1]; ?></a>
				</span><br/>
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
			 case 'total':

			    $sql = "Select total from ".erp_SIZE." where product_id ='$workorder_id'";
	            $result = $this->db->query($sql,__FILE__,__LINE__);
				$i = 1;
	            while($row = $this->db->fetch_array($result)){ ?>
				<div id="<?php echo $div_id.''.$i; ?>">
					<?php echo '$'.$row[total]; ?>
				</div>
				<?php 
				//$this->total_price += $row[total];
				$i++;
				}
			 break;
			 case 'unit':
			    $sql = "Select * from ".erp_SIZE." where product_id ='$workorder_id'";
	            $result = $this->db->query($sql,__FILE__,__LINE__);
				$i = 1;
	            while($row = $this->db->fetch_array($result)){ 
				if( $row[unit_price] !='' ){?>
				<span id="<?php echo $div_id.''.$i; ?>">
					 <a href="javascript:void(0);" 
							onclick="javascript: workorder.showTextBox('<?php echo $row[unit_price]; ?>',
																	    '<?php echo $contact_id; ?>',
																	    '<?php echo $div_id.','.$i; ?>',
																	    '<?php echo $choice; ?>',
																	    '<?php echo $order_id; ?>',
																		'<?php echo $row[size_alot_id]; ?>',
																		'<?php echo $people_contact_id; ?>',
																		'<?php echo $row[size]; ?>',
																		'<?php echo $workorder_id; ?>',
															  { target: '<?php echo $div_id.''.$i; ?>'}
																	   ); ">
					<?php echo $row[unit_price]; ?></a>
				</span><br/>
					<?php
				 } else { ?>
				     <span id="<?php echo $div_id.''.$i; ?>">
					  <a href="javascript:void(0);" 
							onclick="javascript: workorder.showTextBox('N/A',
																		'<?php echo $contact_id; ?>',
																		'<?php echo $div_id.','.$i; ?>',

																		'<?php echo $choice; ?>',
																		'<?php echo $order_id; ?>',
																		'<?php echo $row[size_alot_id]; ?>',
																		'<?php echo $people_contact_id; ?>',
																		'<?php echo $row[size]; ?>',
																		'<?php echo $workorder_id; ?>',
															  { target: '<?php echo $div_id.''.$i; ?>'}
																		); ">
					  N/A</a>
					</span><br/>
				   <?php 
				     }
				    $i++; }//end of while
			 break;
			 
			 
			 }    /////////////end of switch
				$html = ob_get_contents();
				ob_end_clean();
				return $html;	
	}   ////////////////end of function returnLink
	
	function showdropdown( $type='', $vendor_contact_id='', $div_id='', $choice='', $order_id='', $size_alot_id='', $contact_id='', $quantity='', $workorder_id='' ){

	 	 ob_start();
		 switch($choice) {
		   case 'printer_edit': ?>
		   	 <select name="printer" id="printer" style="width:80%" 
				  onblur="javascript:
								   var type_name = this.value;
								   if(this.value!='<?php echo $type; ?>') { 
								  if(confirm('Are you sure you want to change your printer from <?php echo $type; ?> to '+ type_name)){
																																	
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
														    workorder.returnLink(type_name,
																			     '<?php echo $vendor_contact_id; ?>',
																			     '<?php echo $div_id; ?>',
																			     '<?php echo $choice; ?>',
																			     '<?php echo $order_id; ?>',
																				 '<?php echo $contact_id; ?>',
																			     {target:'<?php echo $div_id; ?>', preloader: 'prl'});
													   }}
													else {
														    workorder.returnLink(type_name,
																			     '<?php echo $vendor_contact_id; ?>',
																			     '<?php echo $div_id; ?>',
																			     '<?php echo $choice; ?>',
																			     '<?php echo $order_id; ?>',
																				 '<?php echo $contact_id; ?>',
																			     {target:'<?php echo $div_id; ?>', preloader: 'prl'});
													   }">
					<option value="" <?php if($type=='') echo 'selected="selected"';?> >--Select--</option>
					<option value="M2" <?php if($type=='M2') echo 'selected="selected"';?>>M2</option>
					<option value="M3" <?php if($type=='M3') echo 'selected="selected"';?>>M3</option>
					<option value="M4" <?php if($type=='M4') echo 'selected="selected"';?>>M4</option>
					<option value="M5" <?php if($type=='M5') echo 'selected="selected"';?>>M5</option>
					<option value="M6" <?php if($type=='M6') echo 'selected="selected"';?>>M6</option>
					<option value="M7" <?php if($type=='M7') echo 'selected="selected"';?>>M7</option>
					
			    </select>
				<?php
		   break;
		   case 'people': 
			    $sql_dropdown = "select * from ".TBL_INVE_CONTACTS." where type='People' and company='$vendor_contact_id'";
			    $result_dropdown = $this->db->query($sql_dropdown,__FILE__,__lINE__); ?>
			 
				 <select name="contact_id" id="contact_id" style="width:80%" 
				  onblur="javascript: var contact_name; 
								   var type_id = this.value; 
								   workorder.getName(this.value,
								                     'contact',
												  { onUpdate: function(response,root){
													contact_name = response;
													if(contact_name!='<?php echo $type; ?>') {
													if(confirm('Are you sure you want to change your contact from <?php echo                                                                $type; ?> to '+ contact_name)){
																																	
														workorder.updateOrderField(type_id,
																		'<?php echo $order_id;?>',
																		'contact_id',
														 {onUpdate: function(response,root){
														 
															workorder.returnLink(contact_name,
																				'<?php echo $vendor_contact_id; ?>',
																				'<?php echo $div_id; ?>',
																				'<?php echo $choice; ?>',
																				'<?php echo $order_id; ?>',
																				type_id,
																        {target:'<?php  echo $div_id; ?>', preloader: 'prl'}); 
														    }});
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
			   /*case 'product_name': 
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
				break;*/
				case 'size': 
			       $sql_size = "Select distinct * from ".erp_PRODUCT." where product_id ='$vendor_contact_id'";
				   $result_size=$this->db->query($sql_size,__FILE__,__LINE__);
				   $div = explode(',',$div_id);
				   $size = explode('_',$type);
				
				   ?>
				 <select name="size_id" id="size_id" style="width:80%" 
				  onblur="javascript: var type_id = this.value; 
				  							if(this.value !='<?php echo $type; ?>') {
											if(confirm('Are you sure you want to change this size from <?php 
													 echo $size[1]; ?> to '+ type_id)){
													  
													 workorder.check_product_existence('<?php echo $vendor_contact_id; ?>',
																					   type_id,
																					   'size',
															 {preloader: 'prl',
															  onUpdate: function(response,root){
															  if(response=='c'){
																	alert('Selected Size is Not exists. Please Select Any Other !!');
																	return false;
												               }
												            }});
																
																 workorder.showprice(type_id,
																					'<?php echo $quantity; ?>',
																					'<?php echo $vendor_contact_id; ?>',
																{onUpdate:function(response,root){
																 
																 var qty = response;
													 
																 var tot = (qty * '<?php echo $quantity ; ?>');
																 var total = tot.toFixed(2);
																 
																
																
																workorder.updateSize(type_id,
																				    qty,
																					'size',
																					total,
																					'<?php echo $size_alot_id; ?>',
																{onUpdate: function(response,root){
													 			 
																 workorder.showOrderSizeQuantity('<?php echo $contact_id; ?>',
                                                                                                 '<?php echo $order_id; ?>',
																								 'a',
																{onUpdate: function(response,root){
																 document.getElementById('quantity_details').innerHTML=response;  
																 $('#display_order').tablesorter({widthFixed:true,
																 widgets:['zebra'],sortList:[[0,0]],headers:{5:{sorter: false}}}); }});
													   }}); }});
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
																	 {target:'<?php  echo $div[0]; ?>', preloader: 'prl'});
												}">
					    <option value="" <?php if($type=='') echo 'selected="selected"';?>>-Select-</option>
					    <?php while($row_size=$this->db->fetch_array($result_size)){ ?> 
					<option  value="<?php  echo $row_size['size_xs'].'_XS';?>" <?php if($type==$row_size['size_xs'].'_XS') echo 'selected="selected"';?>>XS</option>
					<option  value="<?php  echo $row_size['size_s'].'_S';?>" <?php if($type==$row_size['size_s'].'_S') echo 'selected="selected"';?>>S</option>
					<option  value="<?php  echo $row_size['size_m'].'_M';?>" <?php if($type==$row_size['size_m'].'_M') echo 'selected="selected"';?>>M</option>
					<option  value="<?php  echo $row_size['size_l'].'_L';?>" <?php if($type==$row_size['size_l'].'_L') echo 'selected="selected"';?>>L</option>
					<option  value="<?php  echo $row_size['size_xl'].'_XL';?>" <?php if($type==$row_size['size_xl'].'_XL') echo 'selected="selected"';?>>XL</option>
					<option  value="<?php  echo $row_size['size_2x'].'_2X';?>" <?php if($type==$row_size['size_2x'].'_2X') echo 'selected="selected"';?>>2X</option>
					<option  value="<?php  echo $row_size['size_3x'].'_3X';?>" <?php if($type==$row_size['size_3x'].'_3X') echo 'selected="selected"';?>>3X</option>
					<option  value="<?php  echo $row_size['size_4x'].'_4X';?>" <?php if($type==$row_size['size_4x'].'_4X') echo 'selected="selected"';?>>4X</option>
						<?php }?>
			    </select>
					
				<?php
				break;
				}  ////end of switch
				$html = ob_get_contents();
				ob_end_clean();
				return $html;			
		} ///end of function showDropDown
			
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
	
	/*function updateProductOrder($variable='',$order_id='',$choice='',$product_id='',$new_product_id=''){
			ob_start();
			$sql = "update ".erp_PRODUCT_ORDER." set $choice= '$variable', product_id= '$new_product_id' where order_id='$order_id' and product_id='$product_id'";
			$result = $this->db->query($sql,__FILE__,__LINE__);	
			
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
	}   ////////////////end of function updateProductOrder*/
	
	function updateSize($variable='',$unit_price='',$choice='',$total='',$size_alot_id=''){
			ob_start();
				 
				$sql = "UPDATE ".erp_SIZE." SET $choice = '$variable', unit_price = '$unit_price', total = '$total' WHERE size_alot_id = '$size_alot_id'";
				$result = $this->db->query($sql,__FILE__,__LINE__);
			
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
	}   ////////////////end of function updateSize
	
	   function update_textbox_field($field='',$choice='',$order_id='',$product_id='',$rework_id=''){
	    ob_start();
		   $sql = "UPDATE ".erp_REWORK." SET  $field='$choice' WHERE order_id='$order_id' and product_id='$product_id' and rework_id='$rework_id'";
		   $this->db->query($sql,__FILE__,__LINE__);
	    $html = ob_get_contents();
	    ob_end_clean();
	    return $html;
	  }
	 
	   function showTextBox( $variable='',$product_id='',$div_id='',$choice='',$order='',$size_alot_id='',$contact_id='',$size='',$workorder_id='' ){
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
														   {target:'<?php  echo $div_id; ?>',preloader:'prl'});
												}"><?php  
				break;
				case 'quantity':
				$sql="SELECT quantity FROM ".erp_SIZE." WHERE product_id='$workorder_id' and order_id='$order' and size_alot_id <> '$size_alot_id'";
				echo $sql;
			    $result = $this->db->query($sql,__FILE__,__LINE__);
				while( $row = $this->db->fetch_array($result) ){
				       $total += $row[quantity];
				}
				$div = explode(',',$div_id);
				?>
				<input type="text" name="total_quant" id="total_quant" value="<?php echo $total; ?>" />
				<input  type="text" name="<?php  echo $variable; ?>" id="<?php echo $variable; ?>" size="5"
				        value="<?php echo $variable; ?>"
						onblur="javascript: var type_name = this.value;
											if(document.getElementById('total_quant').value !=''){
												var tot_quantity_value = document.getElementById('total_quant').value;
											}
											else {
												var tot_quantity_value = '0';
											}
						              var total_quantity = parseInt(tot_quantity_value) + parseInt(type_name);
									  if( total_quantity < 6 ){
									      alert('Total Quantity Is Less Than 6 for This Product.');
										  return false;
										 }
									  if(this.value!='<?php echo $variable; ?>') {
								      if(confirm('Are you sure you want to change the Quantity from <?php echo $variable; ?> to '+ type_name)){
											 
																 
									         workorder.showprice('<?php echo $size ; ?>',
																type_name,
																'<?php echo $product_id; ?>',
													{onUpdate:function(response,root){
													 var qty = response;
										 
													 var tot = (qty * total_quantity);
													 var total = tot.toFixed(2);
													 
									  
									  		  workorder.update_textbox(qty,
																	   document.getElementById('<?php echo $variable; ?>').value,
																	   '<?php echo $choice; ?>',
																	   total,
																	   '<?php echo $size_alot_id; ?>',
										     {onUpdate: function(response,root){
											 
											  workorder.showOrderSizeQuantity('<?php echo $contact_id; ?>',
                                                                              '<?php echo $order; ?>',
																			  'a',
													  {onUpdate: function(response,root){
													   document.getElementById('quantity_details').innerHTML=response;  
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
				$div = explode(',',$div_id);
				?>
				<input  type="text" name="<?php  echo $variable; ?>" id="<?php echo $variable; ?>" size="5"
				        value="<?php echo $variable; ?>"
						onblur="javascript: var type_name = this.value; 
									 if(this.value!='<?php echo $variable; ?>') {
								       if(confirm('Are you sure you want to change the unit price from <?php echo $variable; ?> to '+ type_name)){	 
									             workorder.showquantity('<?php echo $product_id ; ?>',
																		'<?php echo $order ; ?>',
																		'<?php echo $size_alot_id; ?>',
													{onUpdate:function(response,root){   
														var unt = response;
														 var tot = (unt * type_name);
														 var total = tot.toFixed(2);
									  		  workorder.update_textbox1(document.getElementById('<?php echo $variable; ?>').value,
											  							total,
																	   '<?php echo $choice; ?>',
																	   '<?php echo $size_alot_id; ?>',
										     {onUpdate: function(response,root){
											 
											  workorder.showOrderSizeQuantity('<?php echo $contact_id; ?>',
                                                                              '<?php echo $order; ?>',
																			  'a',
													  {onUpdate: function(response,root){
													   document.getElementById('quantity_details').innerHTML=response;  
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
												}
									    } else {
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
			}    /////////////end of switch
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}  ////////////////end of function showTextBox
	
		function showquantity($product_id,$order,$size_alot_id)
	{
	ob_start();
	$sql="select quantity from erp_size where order_id=$order and product_id=$product_id and size_alot_id=$size_alot_id";
	$result=$this->db->query($sql,__FILE__,__LINE__);
	$row=$this->db->fetch_array($result);
	$qty=$row['quantity'];
	return $qty;
	$html=ob_get_contents();
	ob_end_clean();
	return $html;
	}
	
	function update_textbox1($unit_price,$total,$choice,$size_alot_id)
	{
	ob_start();
	$sql = "UPDATE ".erp_SIZE." SET  unit_price = '$unit_price', total='$total 'WHERE size_alot_id = '$size_alot_id'";
	$this->db->query($sql,__FILE__,__LINE__);
	
	$html=ob_get_contents();
	ob_end_clean();
	return $html;
	}

	
	function update_textbox($unit_price,$variable,$choice,$total,$size_alot_id){
	    ob_start();
		$sql = "UPDATE ".erp_SIZE." SET $choice= '$variable', unit_price = '$unit_price', total = '$total' WHERE size_alot_id = '$size_alot_id'";
		$this->db->query($sql,__FILE__,__LINE__);
		
        $html=ob_get_contents();
		ob_end_clean();
		return $html;
	 } ////////////end of function update_textbox
	 
	function checkSystemTask($global_task_status_id='',$module='',$module_id='',$group_id=''){
		ob_start();
		
		$a='';
		  if($module == 'Order'){
		  	$tbl = "erp_order";
			$mod_id = "order_id";
		  }
		  else if($module == 'Work Order'){
		  	$tbl = "erp_work_order";
			$mod_id = "work_order_id";
		  }		  
		  
		  $sql_s ="Select a.*,b.* from template a, assign_report_to_system_task b where a.id = b.report_id and b.selection_option_id = '$global_task_status_id'"; 
		  //echo $sql_s;
		  
		  $result_s = $this->db->query($sql_s,__FILE__,__LINE__);
		  if($this->db->num_rows($result_s) > 0){
				while($row_s = $this->db->fetch_array($result_s)){
					if($row_s['module'] == $module && $row_s['subject'] != ''){
						$message = $row_s['message'];
						if($message !='NULL'){						
							$sql_c = "Select * from insert_to_report where timestamp = '$row_s[timestamp]'";
							//echo $sql_c;
							$result_c = $this->db->query($sql_c,__FILE__,__LINE__);
							if($this->db->num_rows($result_c) > 0){
								while($row_c = $this->db->fetch_array($result_c)){
			
									$sql="select b.$row_c[field_name] from $tbl a, $row_c[table_name] b where a.$row_c[column_name_main]=b.$row_c[column_name] and a.$mod_id=$module_id";	
									$result=$this->db->query($sql,__FILE__,__LINE__);
									$row =$this->db->fetch_array($result);
									
									$new_message = str_replace($row_c['field_value'],$row[$row_c['field_name']],$message);
									$message = $new_message;
								}
							}
							else {
								$new_message = $message;
							}
							$sql_e = "Select * from contacts_email where contact_id = '$_SESSION[contact_id]' limit 1";
							$result_e = $this->db->query($sql_e,__FILE__,__LINE__);
							$row_e = $this->db->fetch_array($result_e);
							//echo $this->sentMailToUser($row_e[email],$row_s[subject],$row_s[title],$new_message);	
							
						}

						$a .= $row_s[timestamp].'_' ;
					}
					if($row_s['module'] == $module && (($row_s['message'] =='NULL') || ($row_s['message'] ==''))) {
						echo $this->runReduceOnhand($row_s['title'],$module,$module_id,$group_id);
					}
			   }				
		  }	
		
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	 }
 
	function sentMailToUser($to1='',$sub='',$title='',$msg=''){
	 	ob_start();
		
		$to = $to1;		
		//$to = "amita.rani@twamail.com";
		$subject = $sub;
        $message = "<strong>".$title."</strong>".'<br>'.$msg;			
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'To: '.$to."\r\n";
		$headers .= 'From: Coulee TechLink' . "\r\n";
		mail($to, $subject, $message, $headers);		 
		
		$html=ob_get_contents();
		ob_end_clean();
		return $html;	 
	 }

/*?>	 function sentMailToUser($global_task_status_id='',$module='',$msg=''){
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
									
								//$new_message = str_replace($row_c['field_value'],$row[$row_c['field_name']],$message);
									//$message = $new_message; 
								}
							}
							//else {
								//$new_message = $message; 	
						//}
							$sql_e = "Select * from contacts_email where contact_id = '$_SESSION[contact_id]' limit 1";
							$result_e = $this->db->query($sql_e,__FILE__,__LINE__);
							$row_e = $this->db->fetch_array($result_e);
							//$to = $to1;
							$to = $row_e[email];
							//$to = "amita.rani@twamail.com";
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
<?php */ 

  function FlowChartDiv( $module_name , $module_id ){

	  $module_name_true = $module_name;
      $module_name = str_replace( ' ' , '' , $module_name );
      ob_start();
	  $global_task = new GlobalTask();
      ?>
      <div id="flowcharttask_<?php echo $module_name . '_' . $module_id;?>">
        <a href="javascript:void(0);" onClick="javascript: workorder.check_shipdate('<?php echo $_REQUEST[order_id];?>',
																					{onUpdate:function(response,root){
																						if(response){
																						  $('#flowcharttask_add_<?php echo $module_name . '_' . $module_id;?>').show();
																						}
																						else{
																						 alert('Ship Date for this order does not exist');
																						}
																					}}); ">
			add Flow Chart Task
		</a>
        <div style="display: none;" id="flowcharttask_add_<?php echo $module_name . '_' . $module_id;?>">
            <?php echo $global_task->AddFlowChartTask($module_name_true, $module_id , '' , '' , "$('#flowcharttask_add_" . $module_name . '_' . $module_id . "').hide()" , $_REQUEST[order_id]);?>
        </div>

		<div id="flowcharttask_options_<?php echo $module_name . '_' . $module_id;?>">
			<?php echo $global_task->displayByModuleId($module_name_true, $module_id, "flowcharttask_options_" . $module_name . '_' . $module_id,'',$_REQUEST[order_id]);?>
		</div>
      </div>


      <?php
      $html=ob_get_contents();
      ob_end_clean();
      return $html;
  }
   function check_shipdate($order_id)
   {
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
		
		$sql_log = "INSERT INTO ".erp_INVENTORY_LOG." values('', '$inve_name', '$order_id', '$product_id', '$amt_onhand', '$inve_used', '$cur_amt_onhand')";
		$this->db->query($sql_log,__FILE__,__lINE__);
		
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



/*////////////////////////////////////////////////////////////////////////////

    function percentDay(){
	    ob_start();
		
		  $sql = "SELECT ship_date FROM erp_order WHERE order_id = '$_REQUEST[order_id]'";
		  $result = $this->db->query($sql,__FILE__,__LINE__);
		  $row = $this->db->fetch_array($result);
		  $date1 = $row[ship_date];
		  $date2 = strtotime($date1);
		  
		  echo 'ship'.$date1.'-'.$date2;
		  $date3 = strtotime(date("Y-m-d h:i:s A"));
		  echo'<br/>'.date("Y-m-d h:i:s A").'<>'.$date3;
		  $str = ( $date2 - $date3 );
		  echo 'str'.$str;
		  
		  $min = $str/60;
		  $hour = $min/60;
		  $day = $hour/24;
		  
		  echo '<br/>hour '.$hour.' day '.round($day).'-'.$day.'<->'.$_SESSION[total_est_day];
		  
		  $percentage = round($day)/$_SESSION[total_est_day];
		  
		  echo 'per'.$percentage;
		
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	
	} /////end of function predictPathStatus

    function predictPathStatus($tree_id=1){
	    ob_start();
		
		 $sql_tree = "Select root_task_id from ".GLOBAL_TASK_TREE." where global_task_tree_id = '$tree_id'";
		 $result_tree = $this->db->query($sql_tree,__FILE__,__LINE__);
		 $row_tree = $this->db->fetch_array($result_tree);
		 $gtid = $row_tree[root_task_id];
		 
		 $sql_task = "Select name,module,est_day_dep from ".erp_GLOBAL_TASK." where global_task_id = '$gtid' and default_path = '1'";
		 $result_task = $this->db->query($sql_task,__FILE__,__LINE__);
		 $row_task = $this->db->fetch_array($result_task);
		 
		 $task_name = $row_task[name];
		 $task_mod = $row_task[module];
		 $task_est = $row_task[est_day_dep];
			 
		 //echo $this->showPathFct($gtid,$task_name,$task_mod,$task_est);
		 //$est_date = $this->estDueDate($task_est);
		 
		 echo $this->predictPathFct( $gtid, $task_est, $task_est );
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	
	} /////end of function predictPathStatus
	
	 function predictPathFct( $gtid='', $task_est='', $est_day ){
	    ob_start();
		$task_est_day = $task_est;
		
		
		?>
		
		<table width="100%">
		<?php if( $gtid == 1 ){ ?>
		  <tr>
		    <td>FCT ID</td>
			<td>est_day</td>
			<td>total est day</td>
		  </tr>
		  <?php } ?>
		  <tr>
		    <td><?php echo $gtid; ?></td>
			<td><?php echo $est_day; ?></td>
			<td><?php echo $task_est_day; ?></td>
		  </tr>
		</table>
		
		<?php
		//echo '<br/>-gtid'.$gtid.'est_day'.$task_est_day.'ma';

		 $sql_status = "Select * from ".erp_GLOBAL_TASK_STATUS." where global_task_id = '$gtid' and default_path = '1'";
		 //echo $sql_status;
		 $result_status = $this->db->query($sql_status,__FILE__,__LINE__);
		 $row_status = $this->db->fetch_array($result_status);
		 $status_id = $row_status[global_task_status_id]; ?>
		
		 <?php
		 $sql_name = "Select global_task_id from ".erp_GLOBAL_TASK_STATUS_RESULT." where global_task_status_id = '$status_id'";
		 $result_name = $this->db->query($sql_name,__FILE__,__LINE__);
		 while($row_name=$this->db->fetch_array($result_name)){
			 $sub_task = $row_name[global_task_id];
			 
			 $sql_task = "Select global_task_id,name,module,est_day_dep from ".erp_GLOBAL_TASK." where global_task_id = '$sub_task' and default_path = '1'";
			 $result_task = $this->db->query($sql_task,__FILE__,__LINE__);
			 if($this->db->num_rows($result_task) > 0){
			 $row_task = $this->db->fetch_array($result_task);
			 $sub_task = $row_task[global_task_id];
			 $task_name = $row_task[name];
			 $task_mod = $row_task[module];
			 $task_est = $row_task[est_day_dep];
			 
			 
			 $task_est_day += $task_est;
			 $_SESSION[total_est_day] = $task_est_day;
			 
			 //echo $est_date = $this->estDueDate($task_est,'0');
		 	 echo $this->predictPathFct( $sub_task, $task_est_day, $task_est );
		  }//end of if
		 }//end of while
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	} /////end of function predict_path

	 function estDueDate($w_days='',$i){
	    ob_start();
		//$aa = array();
		$i=1;
	      if($_SESSION[est_date] == 0){ $_SESSION[est_date] = date("Y-m-d"); }
		
			$cur_date=date("Y-m-d",strtotime($_SESSION[est_date]));
			//$w_days = 28;
			while($w_days >= 0){
			if(!in_array(date("N",strtotime($cur_date)),array(6,7))) $w_days--;
			$cur_date = date ("Y-m-d", strtotime ("+1 day", strtotime($cur_date))); 
			//exit();
			}
			$cur_date = date ("Y-m-d", strtotime ("-2 day", strtotime($cur_date)));
			if(!in_array(date("N",strtotime($cur_date)),array(6,7))) 
			$cur_date = date ("Y-m-d", strtotime ("+1 day", strtotime($cur_date))); 
			if(!in_array(date("N",strtotime($cur_date)),array(6,7))) 
			$cur_date = date ("Y-m-d", strtotime ("+1 day", strtotime($cur_date))); 
			//echo $cur_date;
			
			$cur_date = date ("Y-m-d", strtotime ("-2 day", strtotime($cur_date)));
			$aa[0] = $cur_date;
			//echo 'k'.$cur_date;
			$_SESSION[est_date];
			if($cur_date != ''){
			$aa[1] = $_SESSION[est_date];
			$_SESSION[est_date] = $cur_date;
			//echo 'a'.$_SESSION[est_dateas];
			$this->final_date = $cur_date; 
			//echo "array".$aa[0];
			
			}$_SESSION[newvalue]=$aa[0];
			echo $_SESSION[newvalue];

	$html=ob_get_contents();
	ob_end_clean();
	return $html;
	
	} /////end of function estDueDate



///////////////////////////////////////////////////////////////////////
*/

}  //////////end of class
?>