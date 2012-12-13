<?php
 
class InventoryVendor {

var $db;
var $ad;
var $company_id;
var $Validity;

	function __construct(){
	$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	$this->validity = new ClsJSFormValidation();
	$this->Form = new ValidateForm();
	}

  function InVeDetails() {
     ob_start();
	 $formName = "frm_search";	?>
 	 <form name="<?php echo $formName;?>" method="post" action="">
		<table width="100%" class="table" >
			<tr>
			<td>Name</td>
			<td>Type</td>
		    <td>Product Status</td>
			<td>Vendor</td>
			</tr>
			<tr>
				<td><input type="text" name="txt_name" id="txt_name" 
							onchange="javascript:inventory.show_searchInventoryVendor(
										document.getElementById('txt_name').value,
										document.getElementById('select_type').value,
										document.getElementById('select_prodstatus').value,
										document.getElementById('vendor').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: { 9:{sorter: false}} }) }});
												"/></td>	
				<td>
					<select name="select_type" id="select_type" 
							onchange="javascript:inventory.show_searchInventoryVendor(
							            document.getElementById('txt_name').value,
										document.getElementById('select_type').value,
										document.getElementById('select_prodstatus').value,
										document.getElementById('vendor').value,																			
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response; 
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: { 9:{sorter: false}} }) }});
												" >
							<option value="">-Select-</option>
							<?php
							   $sql = "SELECT * FROM erp_item_type";
							   $result=$this->db->query($sql,__FILE__,__LINE__);
							   while($row=$this->db->fetch_array($result)) {?>
							   <option value="<?php echo $row['type_id']; ?>"><?php echo $row['type_name']; ?></option>
							<?php } ?>
					 </select>
				</td>
				<td>
					<select name="select_prodstatus" id="select_prodstatus" 
							onchange="javascript:inventory.show_searchInventoryVendor(
							            document.getElementById('txt_name').value,
										document.getElementById('select_type').value,
										document.getElementById('select_prodstatus').value,
										document.getElementById('vendor').value,																			
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response; 
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: { 9:{sorter: false}} }) }});
												" >
						<option value="Active" selected="selected">Active</option>
						<option value="Inactive">Inactive</option>
					</select>
				</td>
				<td>
				    <select name="vendor" id="vendor" onChange="javascript:document.getElementById('contact').value='';
											for(i=0; i<document.getElementById('vendor').length; i++){ 
												if(document.getElementById('vendor')[i].selected==true){
												   document.getElementById('contact').value += 
												   document.getElementById('vendor')[i].value+',';}}
											       document.getElementById('contact').value = 											                                                   document.getElementById('contact').value.substr(0,
												   document.getElementById('contact').value.length-1);
												   inventory.show_searchInventoryVendor(document.getElementById('txt_name').value,
												     document.getElementById('select_type').value,
												     document.getElementById('select_prodstatus').value,
												     document.getElementById('contact').value,																			
													{preloader:'prl',onUpdate: function(response,root){
													 document.getElementById('task_area').innerHTML=response; 
													 $('#search_table')
													 .tablesorter({widthFixed:true,
													  widgets:['zebra'],sortList:[[0,0]], headers: { 9:{sorter: false}}																				 	
												})}});">
					</select>
					<input name="contact" type="hidden" id="contact" value="" size="60" /></td>
			</tr>
           </table>
	       </form>
  		<?php    
        $html = ob_get_contents();
		ob_end_clean();
		return $html;    
    }   //////end of function InVeDetails
	
	function GetVendorJson($pattern=''){
		ob_start();
		$contact_json = "";
		$sql="select company_name,contact_id from ".TBL_CONTACT." where company_name LIKE '%$pattern%' and type='Company' limit 0, 20";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		while($row = $this->db->fetch_array($record)){
		$cmp = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $row[company_name]);
		$contact_json .='{"caption":"'.$cmp.'","value":"'.$row[contact_id].'"},';
		}
		$contact_json = '['.substr($contact_json,0,strlen($contact_json)-1).']';
		return $contact_json;
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} ///////end of function GetVendorJson

    function show_searchInventoryVendor($name='' , $type='' , $prodstatus='' , $vendor='') {
		ob_start();
		
	    if($prodstatus!=''){
		$sql= "SELECT distinct a.inventory_id, a.name, a.Item_number, a.type_id, a.status, a.measured_in, a.allocated, a.ordered, a.amt_onhand, a.reorder, a.warning_amount, b.contact_id, c.type_id, c.type_name FROM erp_inventory_details a , contacts b , erp_item_type c WHERE a.contact_id = b.contact_id and a.type_id = c.type_id";
	    }
		else{
		$sql= "SELECT distinct a.inventory_id, a.name, a.Item_number, a.type_id, a.status, a.measured_in, a.allocated, a.ordered, a.amt_onhand, a.reorder, a.warning_amount, b.contact_id, c.type_id, c.type_name FROM erp_inventory_details a , contacts b , erp_item_type c WHERE a.contact_id = b.contact_id and a.type_id = c.type_id and a.status = 'Active'";
		}
				
		if($name){
		$sql.= " and a.name like '%$name%' " ;    }
		
		if($type){
		$sql.= " and c.type_id = '$type' " ;    }
		
		if($prodstatus) {
		$sql.= " and a.status = '$prodstatus' "  ;    }
		
		if($vendor) {
		$sql.= " and b.contact_id = '$vendor' " ;   }

		//echo $sql;
		$result = $this->db->query($sql,__FILE__,__LINE__);
		?>
		
		<table id="search_table" class="event_form small_text" width="100%">
			<thead>
			   <tr>
				  <th width="5%">ID</th>
				  <th width="11%">Name</th>
				  <th width="8%">Type</th>
				  <th width="13%">Measured In</th>
				  <th width="11%">Allocated</th>
				  <th width="11%">Ordered</th>
				  <th width="11%">Onhand</th>
				  <th width="11%">Reorder</th>
				  <th width="11%">(O+O)-A</th>
                  <th width="3%">&nbsp;</th>
			   </tr>
			</thead>
			<tbody>
			 <?php if( $this->db->num_rows($result)>0 ){
					while($row=$this->db->fetch_array($result)){ 
						$this->ad = ( $row['amt_onhand'] + $row['ordered'] ) - $row['allocated'];
						 ?>
						 <tr>
						    
							<td><?php echo $row['inventory_id']; ?></td>	  
							<td>
							   <a href="javascript:void(0);" 
											onclick="javascipt:inventory.show_inve('local',
													  '<?php echo $row['inventory_id']; ?>',
													  '<?php echo $row['name'];?>',
													  '<?php echo $row['Item_number'];?>',
													  '<?php echo $row['type_name']; ?>',
													  '<?php echo $row['status']; ?>',
													  '<?php echo $row['measured_in']; ?>',
													  '<?php echo $row['contact_id']?>',
													  '<?php echo $row['type_id']; ?>',
													  {preloader: 'prl',
														 onUpdate: function(response,root){
														 document.getElementById('show_value').innerHTML=response;
														 document.getElementById('show_value').style.display='';
													   }});"><?php echo $row['name']; ?></a>
							</td>
							<td><?php echo $row['type_name'];  ?></td>
							<td><?php echo $row['measured_in'];   ?></td>
							<td><?php echo $row['allocated'];  ?></td>
							<td><?php echo $row['ordered'];   ?></td>
							<?php if($row['amt_onhand'] < $row['reorder']) { ?>
							   <td style="background-color:#FF0000"><?php echo $row['amt_onhand']; ?></td> 
							<?php }
							else { ?>
								<td><?php echo $row['amt_onhand']; ?></td>
							 <?php }  ?>
							<td><?php echo $row['reorder'];   ?></td>
							 <?php  if($this->ad < $row['warning_amount']) {						
							     echo '<td style="background-color:#FF0000">' . $this->ad . '</td>';  
							          }
							        else {
							              echo '<td>' . $this->ad . '</td>';
							          }
							?></td>
                            <td width="5%" align="center">
                               <a href="javascript:void(0);" 
                                  onclick="javascript:
                                  if(confirm('Are You Sure to Delete ?')){
								  
								         inventory.checkStatus('<?php echo $row[inventory_id];?>',
												   {preloader:'prl',
													onUpdate: function(response,root){
													var a = response;
												if( a != ''){
													if(confirm('Would you like to remove this Inventory from all active Products '+ a)){
													var name=prompt('You are about to remove this Inventory from all active Products.... ARE YOU SURE YOU WANT TO DO THIS','yes/no');
													if (name!=null && name!=''){
													  if(name == 'yes'){
													  
													  inventory.deleteInventory('<?php echo $row['inventory_id']; ?>',
								                            					'',
																				'<?php echo $row['name']; ?>',
																 {preloader:'prl',
																 onUpdate: function(response,root){ 
													   	 inventory.show_searchInventoryVendor({	
														 onUpdate: function(response,root){
														  document.getElementById('task_area').innerHTML=response;  
														  $('#search_table').tablesorter({widthFixed:true,
														  widgets:['zebra'],sortList:[[0,0]],headers: {0:{sorter: false}, 10:{sorter: false}}	});}});                                              
					                              }});
													  
													  }
													
													  else if(name == 'no'){ return false; }
													} else { return false; }			
														}
													else{ return false; }
													  } else {
													       inventory.deleteInventory('<?php echo $row['inventory_id']; ?>',
								                            					     1,
																					 '<?php echo $row['name']; ?>',
																		 {preloader:'prl',
																		 onUpdate: function(response,root){ 
													   	 inventory.show_searchInventoryVendor({	
														 onUpdate: function(response,root){
														  document.getElementById('task_area').innerHTML=response;  
														  $('#search_table').tablesorter({widthFixed:true,
														  widgets:['zebra'],sortList:[[0,0]],headers: {9:{sorter: false}}	});}});                                              
					                              }});
													  }
													}});
											 } else { return false; }"><img src="images/trash.gif" border="0" /></a>                            </td> 		
						 </tr>
					<?php } 
					}
					else { ?><tr><td colspan="10" align="center">No Records Found!!!!!! </td></tr><?php } ?>
	   		</tbody>
	 	</table>
	    <?php	
			
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	   
     }   //////end of function show_searchInventoryVendor
	 
	function show_inve($runat,$id='',$name='',$num='',$type='',$status='',$maesured_in='',$contact_id='',$type_id='') {
	     ob_start();
		 //$name = stripslashes($name1);
		 ?>
  	 	 <div id="clone_item"><?php echo $this->show_link($id); ?></div>
		 <?php
		 switch($runat){
			case 'local':
				  ?>
				  <table class="table" width="100%">
					 <tr>
						<th>Item Name :</th>
						<td>
                           <span id="item_name_box_<?php echo $id; ?>"> 
								<?php echo $this->returnLink($name,$id,'item_name_box_'.$id,'item_name_edit'); ?>
							</span>
						<th>Status : </th>
						<td>
							<span id="status_box_<?php echo $id; ?>"> 
								<?php echo $this->returnLink($status,$id,'status_box_'.$id,'status'); ?>
							</span>								
						</td>
					  </tr>
					  <tr>
						<th>Item Type :</th>
						<td>
							<span id="type_box_<?php echo $id; ?>"> 
								<?php echo $this->returnLink($type,$id,'type_box_'.$id,'item_type'); ?>
							</span>							
						</td>
						<th>Measured In :</th>
						<td>
							<span id="measured_in_box_<?php echo $id; ?>">
								<?php echo $this->returnLink($maesured_in,$id,'measured_in_box_'.$id,'measured_in'); ?>
						   </span>
						</td>
					  </tr>
					  <tr>
						<th colspan="2">&nbsp;</th>
						<th>Item Number :</th>
						<td>
						   <span id="item_num_box_<?php echo $id; ?>"> 
								<?php echo $this->returnLink($num,$id,'item_num_box_'.$id,'item_num_edit'); ?>
						   </span>
						</td>
					  </tr>
					  <tr>
						<th>Vendor :</th>
						<td colspan="3">
							   <span id="vendor_box_<?php echo $id; ?>">
								   <?php $sql="select  contact_id,company_name from ". TBL_INVE_CONTACTS." where type='Company' and contact_id = '".$contact_id."'";
								         
										 $record=$this->db->query($sql,__FILE__,__LINE__);
										 $row=$this->db->fetch_array($record);
										 echo $this->returnLink($row['company_name'],$id,'vendor_box_'.$id,'vendor',$contact_id);
								   ?>
							   </span>
						</td>			
					  </tr>	
					  <tr>
						<td>&nbsp;</td>
						<td colspan="3">
							<span id="contacts_box_<?php echo $id; ?>">
								<?php
								$sql2="select a.*,b.* from ".TBL_INVE_CONTACTS." a, ".TBL_INVENTORY_DETAILS." b where inventory_id = '$id' and a.type = 'People' and b.vendor_id = a.contact_id";
								
								$record2=$this->db->query($sql2,__FILE__,__LINE__);
								$row2=$this->db->fetch_array($record2);
								if($row2['first_name'] !=''){
								echo $this->returnLink($row2['first_name'],$id,'contacts_box_'.$id,'contact',$row2[contact_id]);
								} ?>
						   </span>
						</td>
					  </tr>                 
					  <tr>
						 <td colspan="4">
						<?php $this->onhand_add($id); ?>
						 </td>
					  </tr>
					  <tr>
						 <td colspan="4">
						<?php $this->Reorder_threashhold($id); ?>
						 </td>
					  </tr>
					  <tr>
						<th>Estimated costs :</th>
						<td colspan="3">
							 $ <span id="estimated_cost_box1_<?php echo $id; ?>">
								  <?php $sql="select estimated_cost from ".TBL_INVENTORY_DETAILS." where inventory_id=
										'". $id ."'";
										$record=$this->db->query($sql,__FILE__,__LINE__);
										$row=$this->db->fetch_array($record);
										echo $this->returnLink($row['estimated_cost'],$id,'estimated_cost_box1_'.$id,'estimated_cost'); ?>
							  </span> per
							  <span id="estimated_cost_box2_<?php echo $id; ?>">
								  <?php $sql="select estimated_cost from ".TBL_INVENTORY_DETAILS." where inventory_id=
										'". $id ."'";
										$record=$this->db->query($sql,__FILE__,__LINE__);
										$row=$this->db->fetch_array($record);
										echo $this->returnLink($row['estimated_cost'],$id,'estimated_cost_box2_'.$id,
										'estimated_cost_2'); ?>
							  </span>
						</td>
					  </tr>
					  <tr>
						<th colspan="4">Reorder Instructions :</th>
					  </tr>
					  <tr>
					  <td colspan="4" style="border-bottom-color:#fff !important;">
							 <span id="reorder_instruction_box_<?php echo $id;  ?>">
								 <?php $sql="select reorder_instruction from ".TBL_INVENTORY_DETAILS." where inventory_id=
									   '".$id."'";
									   $record=$this->db->query($sql,__FILE__,__LINE__);
									   $row=$this->db->fetch_array($record);
									   echo $this->returnLink($row['reorder_instruction'],$id,'reorder_instruction_box_'.$id,                                   'reorder_instruction'); ?>
							  </span>
						</td>
					  </tr>
<?php /*?>				  <tr>
				  	<td colspan="4">
						<a href="javascript:void(0);" 
							onclick="javascript:inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});">Finish Edit	</a>
					</td>
				  </tr><?php */?>
				</table>
	 		<?php 
	         }  ////////end of switch
	       		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	   
    }   //////end of function show_inve
	   
	function onhand_add($inventory_id){
			   $sql="select * from ". TBL_INVENTORY_DETAILS ." where inventory_id= '$inventory_id'";
			   $result=$this->db->query($sql) or die($this->db->error()); 
			   $row= $this->db->fetch_array($result);?>
			   <table class="table">
			   <tr>
				   <td>&nbsp;</td>
				   <th>Onhand:</th>
				   <td colspan="2">
				        <span id="onhand_box_<?php echo $inventory_id; ?>">
					       <?php echo $this->returnLink($row['amt_onhand'],$inventory_id,'onhand_box_'.$inventory_id,'onhand'); ?>
					   </span>
				   </td>
			   </tr>	
			   <tr>
				   <td>&nbsp;</td>	   
				   <th>Ordered:</th>
				   <td colspan="2">
				        <span id="ordered_box_<?php echo $inventory_id; ?>">
					       <?php echo $this->returnLink($row['ordered'],$inventory_id,'ordered_box_'.$inventory_id,'ordered'); ?>
					   </span>
				   </td>
			   </tr>
			   </table>	   
			   <div id="inventory_div"></div>
			   <?php
			 }  //////end of function onhand_add
				 
	  function Reorder_threashhold($inventory_id){
					$sql="select * from ". TBL_INVENTORY_DETAILS ." where inventory_id= '$inventory_id'";
					$result=$this->db->query($sql) or die($this->db->error());
					$row= $this->db->fetch_array($result); ?>
			        <table class="table">
					<tr>
					   <td>&nbsp;</td>
					   <th>Reorder Threashhold : </th> 
					   <td colspan="2">
					        <span id="Reorder_threashhold_box_<?php echo $inventory_id; ?>">
					            <?php echo $this->returnLink($row['reorder'],$inventory_id,'Reorder_threashhold_box_'.                                      $inventory_id,'Reorder_threashhold'); ?>
					        </span>
					   </td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<th>(O+O)- A Warning : </th>
						<td colspan="2">
						     <span id="warning_box_<?php echo $inventory_id; ?>">
						         <?php echo $this->returnLink($row['warning_amount'],$inventory_id,'warning_box_'.$inventory_id,                                      'warning_amount'); ?>
						     </span>
						</td>
					</tr>
					</table>
					<?php
	         }   //////end of function Reorder_threashhold
			 
     	

    function add_InVeDetails($runat) {
			  ob_start();
			  switch($runat) {
				  case 'local':
				  $FormName='add_item';
					$ControlNames=array("item_name" =>	array('item_name',"''","Please Enter Item name","span_item_name"),
							  "status"=>		array('status',"''","Please Select Status","span_status"),
							  "item_type"=>		array('item_type',"''","Please Select item type","span_item_type"),
							  "measured_in" =>	array('measured_in',"' '","Please Enter Measured In","span_measured_in"),
							  "company_id" =>	array('company_id',"' '","Please Select Vendor","span_vendor_id")
							  );
					$ValidationFunctionName='ValidateInventory';
					$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;?>
                   
				  
                  <form name="add_item" method="post" action=""> 
                 
                  		  <span id="span_item_name"></span>
						 <span id="span_status"></span>
                         <span id="span_item_type"></span>
                         <span id="span_measured_in"></span>
                         <span id="span_vendor_id"></span>
                         <span id="span_allocated"></span>
                         <span id="span_onhand"></span>
                         <span id="span_ordered"></span>
                         <span id="span_reorder_threashhold"></span>
                         <span id="span_warning"></span>
                         <span id="span_estimated_cost"></span>
						 <span id="span_reorder_instruction"></span>
                 
                  
				  <table class="table" width="100%">
				  <tr>
					<th>Item Name :</th>
					<td><input type="text" name="item_name" id="item_name" /></td>
                     
                    
                    
					<th>Status :</th>
					<td>
						<select name="status" id="status">
						   <option value="">--select--</option>
						   <option value="Active">Active</option>
						   <option value="Inactive">Inactive</option>
						</select>
					</td>
                    <ul>
                  		<li><span id="status"></span></li>
                    </ul>
				 </tr>
				 <tr>
				    <th>Item Type :</th>
					<td>
					<?php
						$sql="SELECT * FROM erp_item_type ";
						$result=$this->db->query($sql); ?>
						<select name="item_type" id="item_type">
						   <option value="">--select--</option>
						   <?php while($row=$this->db->fetch_array($result)) { ?>
						   <option value="<?php echo $row['type_id']; ?>"><?php echo $row['type_name'];  ?></option>
						   <?php }  ?>
						</select>
					</td>
				 </tr>	
				 <tr>	
				    <th>Measured In :</th>
				    <td>
						<select name="measured_in" id="measured_in">
                         <option value="">--Select--</option>
						  <option value="Inches">Inches</option>
						  <option value="Yards">Yards</option>
						  <option value="Meters">Meters</option>
						  <option value="Millimeters">Millimeters</option>
						  <option value="Pieces">Pieces</option>
						  <option value="Sets">Sets</option>
						  <option value="Misc">Misc</option>
						</select>					  
					</td>
				 </tr>
				 <tr>
					<th>Vendor :</th>
					<td colspan="3">
					  	<select name="vendor_id" id="vendor_id" onChange="javascript: document.getElementById('company_id').value='';
											for(i=0; i<document.getElementById('vendor_id').length; i++){ 
												if(document.getElementById('vendor_id')[i].selected==true){
												   document.getElementById('company_id').value += 
												   document.getElementById('vendor_id')[i].value+',';
												 }
											}
											document.getElementById('company_id').value = 											                                            document.getElementById('company_id').value.substr(0,
											document.getElementById('company_id').value.length-1);
											
						 					inventory.show_contacts(document.getElementById('company_id').value,
																	'','','add',	
																	{target:'contacts',preloader:'prl'});"></select> 
						<input type="hidden" id="company_id" name="company_id" value="" />
					 </td>			
				  </tr>	
				  <tr>
				    <td colspan="4">
                  		<div id="contacts"></div>
					</td>
				  </tr>
				  <tr>
				      <th>Allocated</th>
					  <td>
					    <input type="text" name="allocated" id="allocated" />
					  </td>
				  </tr>				  
	              <tr>
				      <th>Onhand :</th>
				      <td colspan="3">
					     <input type="text" name="onhand" id="onhand">
				      </td>
				   </tr>
				   <tr>	   
	   	              <th>Ordered :</th>
				      <td colspan="3">
    			  	     <input type="text" name="ordered" id="ordered">
					  </td>
				  </tr>
				  <tr>
				      <th>Reorder Threashhold : </th> 
				      <td colspan="2">
					      <input type="text" name="reorder_threashhold" id="reorder_threashhold">
					   </td>
				  </tr>
				  <tr>
					  <th>(O+O)- A Warning: </th>
					  <td colspan="3">
					     <input type="text" name="warning" id="warning">
					  </td>
				 </tr>
				 <tr>
				      <th>Estimated costs :</th>
					  <td colspan="3">
					      <input type="text" name="estimated_cost" id="estimated_cost">
					  </td>
				  </tr>
				  <tr>
					  <th colspan="4">Reorder Instructions</th>
				  </tr>
				  <tr>
					  <td colspan="4" style="border-bottom-color:#fff !important;">
						  <input type="text" name="reorder_instruction" id="reorder_instruction">
					  </td>
				  </tr>
				  <tr>
				      <td>&nbsp;</td>
					  <td>
					  <input type="submit" name="add_this" value="Add" onClick="return <?php echo $ValidationFunctionName ?>();"/>
					  </td>
				  </tr>
				  </table>
              <?php	  
	              break;
				  
		  case 'server':
		          extract($_POST);
				  $return=true;
				  $name = mysql_real_escape_string($item_name);
				  $sql1="SELECT * FROM ". TBL_INVENTORY_DETAILS." where name= '$name'";
				  $result1=$this->db->query($sql1,__FILE__,__LINE__);
				  if($this->db->num_rows($result1) > 0) { ?>
					  <script> 
					     alert("Item name is already exist");
					  </script>
				<?php 
				 return false; 
				}
				if($return)	{
				  $insert_sql_array = array();
				  $insert_sql_array[name] = htmlspecialchars($item_name, ENT_QUOTES);
				  $insert_sql_array[type_id] = $item_type;
				  $insert_sql_array[status] = $status;
				  $insert_sql_array[measured_in] = $measured_in;
				  $insert_sql_array[allocated] =  $allocated;
				  $insert_sql_array[ordered] =  $ordered;
				  $insert_sql_array[amt_onhand] =  $onhand;
				  $insert_sql_array[reorder] = $reorder_threashhold;
				  $insert_sql_array[estimated_cost] = $estimated_cost;
				  $insert_sql_array[reorder_instruction] = $reorder_instruction;
				  $insert_sql_array[warning_amount] = $warning;
				  $insert_sql_array[contact_id] = $contact;
				
				  
				 $this->db->insert(TBL_INVENTORY_DETAILS,$insert_sql_array); ?>
				 <script>
				 	window.location="<?php echo $_SERVER['PHP_SELF']; ?>";
				 </script>
				 <?php } ////end of if
				 break;		
	        }  ///////end of switch
		
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
	    }  ///////end of function add_InVeDetails
	  
	  function show_contacts($contact_id='',$inventory_id='',$old_contact_id='',$type='') {
	        ob_start();
			if($type=='edit'){ ?>
			   <table class="table" width="100%">
					<tr>
					  <th>Contact :</th>
					  <td colspan="3">
						 <?php
						 $sql="select distinct contact_id,first_name,last_name from ".TBL_INVE_CONTACTS." where type='People' and                         company='$contact_id'"; ?>	
						 <select name="contact" id="contact" 
						                        onChange="javascript: var type_name; 
														  var type_id = this.value; 
														  inventory.getName(this.value,
																		   'people',
														 { onUpdate: function(response,root){
														  type_name = response;
														  if(confirm('Are you sure you want to change your people from current person to '+ type_name)){
														  inventory.updateField('contact_id',
															type_id,
															'<?php echo $inventory_id; ?>',
															'choice',
														  { onUpdate: function(response,root){
															inventory.returnLink(type_name,
															 '<?php echo $inventory_id; ?>',
															 'contacts_box_<?php echo $inventory_id; ?>',
															 'people',
															 type_id,
														  {target:'contacts_box_<?php echo $inventory_id; ?>'
														  ,preloader:'prl'});}});
											   }}	  
										 });">
							<option value="">-select-</option>
							<?php			
							$record=$this->db->query($sql,__FILE__,__LINE__);
							while($row=$this->db->fetch_array($record)){ ?>					
							<option value="<?php echo $row['contact_id']; ?>"><?php echo $row['first_name'].' '.$row['last_name']                            ; ?></option>
							<?php } ?>
						 </select>
					  </td>
					</tr>
			</table>
			<?php
			}
			if($type=='add') {
			?>
				<table class="table" width="100%">
				<tr>
				  <th>Contact :</th>
				  <td colspan="3">
					 <?php
					 $sql="select distinct contact_id,first_name, last_name from ".TBL_INVE_CONTACTS." where type='People' and                     company='$contact_id'";	?>	
					 <select style="width:100%" name="contact" id="contact">
						<option value="">-select-</option>
						<?php			
						$record=$this->db->query($sql,__FILE__,__LINE__);
						while($row=$this->db->fetch_array($record)){ ?>					
						<option value="<?php echo $row['contact_id']; ?>"><?php echo $row['first_name'].' '.$row['last_name'] ; ?>                        </option>
						<?php } ?>
					 </select>
				  </td>
				</tr>
				</table>
	      <?php  } 
	        $html = ob_get_contents();
			ob_end_clean();
			return $html;
	  	  
	  }  //////////////end of function show_contacts

	function returnLink($variable='',$inventory_id='',$div_id='',$choice='',$contact_id=''){
		ob_start();
		
		switch($choice) {
		     case 'item_type':
				if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('<?php echo $variable; ?>',
																   '<?php echo $inventory_id; ?>',
																   'item_type',
																   '<?php echo $contact_id;?>',
																   { target: '<?php echo $div_id; ?>'}
																   ); ">
					<?php echo $variable; ?></a><?php 
				}
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('N/A',
																		'<?php echo $inventory_id; ?>',
																		'item_type',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			 break;
			 case 'status': 
			    if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('<?php echo $variable; ?>',
																		'<?php echo $inventory_id; ?>',
																		'status',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					<?php echo $variable; ?></a><?php 
				}
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('N/A',
																		'<?php echo $inventory_id; ?>',
																		'status',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			 break;
			 case 'measured_in':
			    if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('<?php echo $variable; ?>',
																		'<?php echo $inventory_id; ?>',
																		'measured_in',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					<?php echo $variable; ?></a><?php 
				}
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('N/A',
																		'<?php echo $inventory_id; ?>',
																		'measured_in',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
					 <?php 
				     }
			  break;	
			  case 'item_name': 
			     if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('<?php echo mysql_real_escape_string($variable); ?>',
																		'<?php echo $inventory_id; ?>',
																		'item_name',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					<?php echo $variable; ?></a><?php 
				}
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('N/A',
																		'<?php echo $inventory_id; ?>',
																		'item_name',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			  break;
			  case 'item_name_edit': 
			     if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('<?php echo $variable; ?>',
																		'<?php echo $inventory_id; ?>',
																		'item_name_edit',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					<?php echo $variable; ?></a><?php 
				}
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('N/A',
																		'<?php echo $inventory_id; ?>',
																		'item_name_edit',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			  break;
			  
			  case 'item_num_edit': 
			     if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('<?php echo $variable; ?>',
																		'<?php echo $inventory_id; ?>',
																		'item_num_edit',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					<?php echo $variable; ?></a><?php 
				}
				else {
				      echo $this->showDropDown('',$inventory_id,'item_num_edit',$contact_id,$div_id);
				     }
			  break;
			  case 'vendor':
			     if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('<?php echo $variable; ?>',
																		'<?php echo $inventory_id; ?>',
																		'vendor',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>',
																		  onUpdate: function(response,root){
																		  	autosuggest1();
																		  }
																		}
												 );">
					<?php echo $variable; ?></a>
					<?php $sql="SELECT * FROM contacts_address WHERE contact_id ='".$contact_id."'";
						  $record=$this->db->query($sql,__FILE__,__LINE__);
				          $row=$this->db->fetch_array($record);
							  if($row['street_address'] !='' or $row['city'] !='' or $row['state'] !='' or $row['zip'] !='')                             { echo '<br/>'.$row['street_address'].', ';
							  echo $row['city'].' '.$row['state'].' '. $row['zip']; 
							   }  /////////////end of first if
							}  ///////////end of second if
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('N/A',
																		'<?php echo $inventory_id; ?>',
																		'vendor',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			  break;
			  case 'people':
			     if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.show_contacts('<?php echo $variable; ?>',
																		'<?php echo $inventory_id; ?>',
																		'people',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					<?php echo $variable; ?>
					<br/></a>
					<?php 
						$sql4="select * from ".TBL_CONTACTS_PHONE." where contact_id='".$contact_id."'";						
						$record4=$this->db->query($sql4,__FILE__,__LINE__);
						$row4=$this->db->fetch_array($record4); 
						if($row4['number']){ 	
						echo '('.substr($row4['number'], 0, 3).')'.substr($row4['number'], 3, 3).'-'.substr($row4['number'],
						 6, 4)."<br/>";
						}
					
						$sql3="select * from ".TBL_CONTACTS_EMAIL." where contact_id='".$contact_id."'";
						$record3=$this->db->query($sql3,__FILE__,__LINE__);
						$row3=$this->db->fetch_array($record3); 
						if($row3['email']){?>
						<a href="<?php echo 'mailto:'.$row3['email']; ?>"><?php echo $row3['email']; ?></a>
						<?php } } 
				  else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.show_contacts('N/A',
																		'<?php echo $inventory_id; ?>',
																		'people',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			  break;
			  case 'contact':
			     if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('<?php echo $variable; ?>',
																		'<?php echo $inventory_id; ?>',
																		'contact',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					<?php echo $variable;?></a>
					<?php 
						$sql4="select * from ".TBL_CONTACTS_PHONE." where contact_id='".$contact_id."'";						
						$record4=$this->db->query($sql4,__FILE__,__LINE__);
						$row4=$this->db->fetch_array($record4); 
						if($row4['number']){ 	
						echo '('.substr($row4['number'], 0, 3).')'.substr($row4['number'], 3, 3).'-'.substr($row4['number'],
						 6, 4)."<br/>";
						}  ///////end of if
					
						$sql3="select * from ".TBL_CONTACTS_EMAIL." where contact_id='".$contact_id."'";
						$record3=$this->db->query($sql3,__FILE__,__LINE__);
						$row3=$this->db->fetch_array($record3);
						if($row3['email']){?>
						<a href="<?php echo 'mailto:'.$row3['email']; ?>"><?php echo $row3['email']; ?></a>
						<?php } //////end of if
						 }  //////end of if
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('N/A',
																		'<?php echo $inventory_id; ?>',
																		'contact',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }  //////end of else
			  break;
			  case 'estimated_cost':
			     if($variable !=''){ ?>
					 <a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('<?php echo trim($variable); ?>',
																		'<?php echo $inventory_id; ?>',
																		'estimated_cost',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					<?php echo $variable;?></a>
					<?php 
				} else { ?>
					 <a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('N/A',
																		'<?php echo $inventory_id; ?>',
																		'estimated_cost',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			  break;
			  case 'estimated_cost_2':
			     if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('<?php echo $variable; ?>',
																		'<?php echo $inventory_id; ?>',
																		'estimated_cost_2',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					<?php echo $variable; ?></a>
				    <?php $sql = "SELECT measured_in FROM erp_inventory_details WHERE inventory_id='$inventory_id'";
						  $record=$this->db->query($sql,__FILE__,__LINE__);
						  $row=$this->db->fetch_array($record);
						  echo $row['measured_in'];
				}
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('N/A',
																		'<?php echo $inventory_id; ?>',
																		'estimated_cost_2',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			  break;
			  case 'reorder_instruction':
			     if($variable !=''){ ?>
				    <textarea name="area" id="area" cols="30" rows="10"	 
							onclick="javascript: inventory.showDropDown('<?php echo $variable; ?>',
																		'<?php echo $inventory_id; ?>',
																		'reorder_instruction',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		);"><?php echo $variable; ?></textarea>
				<?php } else { ?>
					  <a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('N/A',
																		'<?php echo $inventory_id; ?>',
																		'reorder_instruction',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			  break;
			  case 'onhand':
			     if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('<?php echo $variable; ?>',
																		'<?php echo $inventory_id; ?>',
																		'onhand',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					<?php echo $variable; ?></a>
					<?php $sql = "SELECT measured_in FROM erp_inventory_details WHERE inventory_id='$inventory_id'";
						  $record=$this->db->query($sql,__FILE__,__LINE__);
						  $row=$this->db->fetch_array($record);
						  echo $row['measured_in'];
					?>
					<?php }
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('N/A',
																		'<?php echo $inventory_id; ?>',
																		'onhand',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			  break;
			  case 'ordered':
			     if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('<?php echo $variable; ?>',
																		'<?php echo $inventory_id; ?>',
																		'ordered',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					<?php echo $variable; ?></a>
					<?php $sql = "SELECT measured_in FROM erp_inventory_details WHERE inventory_id='$inventory_id'";
						  $record=$this->db->query($sql,__FILE__,__LINE__);
						  $row=$this->db->fetch_array($record);
						  echo $row['measured_in'];
					?>
					<?php }
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('N/A',
																		'<?php echo $inventory_id; ?>',
																		'ordered',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			  break;
			  case 'Reorder_threashhold':
			     if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('<?php echo $variable; ?>',
																		'<?php echo $inventory_id; ?>',
																		'Reorder_threashhold',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					<?php echo $variable; ?></a>
					<?php $sql = "SELECT measured_in FROM erp_inventory_details WHERE inventory_id='$inventory_id'";
						  $record=$this->db->query($sql,__FILE__,__LINE__);
						  $row=$this->db->fetch_array($record);
						  echo $row['measured_in'];
					?>
					<?php }
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('N/A',
																		'<?php echo $inventory_id; ?>',
																		'Reorder_threashhold',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			  break;
			  case 'warning_amount':
			     if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('<?php echo $variable; ?>',
																		'<?php echo $inventory_id; ?>',
																		'warning_amount',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					<?php echo $variable; ?></a>
					<?php }
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: inventory.showDropDown('N/A',
																		'<?php echo $inventory_id; ?>',
																		'warning_amount',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			  break;
			  
				 
				}    /////////////end of switch
				$html = ob_get_contents();
				ob_end_clean();
				return $html;	
	}   ////////////////end of function returnLink
	
	function checkStatus($inventory_id=''){
	   ob_start();
	   $sql = "SELECT a.product_id,b.product_status FROM erp_assign_inventory a, ".erp_PRODUCT." b WHERE a.inventory_id = '$inventory_id' AND a.product_id = b.product_id AND b.product_status = 'Active'";
	   //echo $sql;
	   $result = $this->db->query($sql,__FILE__,__LINE__);
	   $product_id = '';
	   while( $row = $this->db->fetch_array($result) ){
	      $product_id .=$row[product_id].','; 
	     
	   }
	   echo $product_id;
	   $html = ob_get_contents();
	   ob_end_clean();
	   return $html;
	
	} ////////////////end of function checkStatus

	function showDropDown($type='',$inventory_id='',$choice='',$contact_id=''){
	 	 ob_start();
		 switch($choice) {
		     case 'item_type': 
			 $sql_dropdown = "select * from erp_item_type";
			 $result_dropdown = $this->db->query($sql_dropdown,__FILE__,__LINE__); ?>
				 <select name="type_id" id="type_id" style="width:80%" 
				  onblur="javascript: var type_name; 
								   var type_id = this.value; 
								   inventory.getName(this.value,
								                     'item_type',
												  { onUpdate: function(response,root){
													type_name = response;
													if(confirm('Are you sure you want to change your item type from <?php echo                                                                $type; ?> to '+ type_name)){																					
														inventory.updateField('type_id',
														 type_id,
														 '<?php echo $inventory_id;?>',
														 'choice',
														 { onUpdate: function(response,root){
															inventory.returnLink(type_name,
														   '<?php echo $inventory_id; ?>',
														   'type_box_<?php echo $inventory_id; ?>',
														   'item_type',
														   {target:'type_box_<?php echo $inventory_id; ?>', preloader: 'prl' 
														});
														
														inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
											  }}
										 );}
										 else{
										 inventory.returnLink('<?php echo $type; ?>',
															 '<?php echo $inventory_id; ?>',
															 'type_box_<?php echo $inventory_id; ?>',
															 'item_type',
															 {target:'type_box_<?php echo $inventory_id; ?>', preloader: 'prl'}
															 );
										 
										 inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
										 } }});">
					<option value="" >--Select--</option>
					<?php while($row_dropdown=$this->db->fetch_array($result_dropdown)){ ?>
					<option value="<?php echo $row_dropdown[type_id]; ?>" <?php if($row_dropdown[type_name]==$type) echo 'selected="selected"';?>>
					<?php echo $row_dropdown[type_name]; ?> 
					</option>
					<?php } ?>
			    </select>
				<?php
				break;
				case 'status': ?>
				  <select name="status_id" id="status_id" style="width:80%" 
				    onblur="javascript: var type_name=this.value;
									  if(confirm('Are You Sure You Want to Change Your Status from <?php 
												  echo $type; ?> to '+ type_name)){
										 <?php if( $type == 'Active' ){ ?>
												if(type_name == 'Active'){
												alert('Please Select Inactive to Change Status');
												return false;
												}
												inventory.checkStatus('<?php echo $inventory_id;?>',
														   {preloader:'prl',
															onUpdate: function(response,root){
															var a = response;
												if(confirm('Would you like to remove this Inventory from all active Products '+ a)){
												var name=prompt('You are about to remove this Inventory from all active Products.... ARE YOU SURE YOU WANT TO DO THIS','yes/no');
												if (name!=null && name!=''){
												  if(name == 'yes'){
												  
												  inventory.updateField('status',
																		'Inactive',
																		'<?php echo $inventory_id;?>',
																		'delete_product',
																		
													{ onUpdate: function(response,root){
													 inventory.returnLink(type_name,
																		 '<?php echo $inventory_id; ?>',
																		 'status_box_<?php echo $inventory_id; ?>',
																		 'status',
													 {target:'status_box_<?php echo $inventory_id; ?>', preloader: 'prl'});
													 
													 inventory.show_searchInventoryVendor({preloader:'prl',
													 onUpdate: function(response,root)
													 {document.getElementById('task_area').innerHTML=response; 
													  $('#search_table').tablesorter({widthFixed:true,
													  widgets:['zebra'],sortList:[[0,0]]	})}});
												  }});
												  
												  }
												
												  else if(name == 'no'){
												  inventory.returnLink('<?php echo $type; ?>',
																	   '<?php echo $inventory_id; ?>',
																	   'status_box_<?php echo $inventory_id; ?>',
																	   'status',
													 {target:'status_box_<?php echo $inventory_id; ?>', preloader: 'prl'});
												  }
												} else {
													  inventory.returnLink('<?php echo $type; ?>',
																		   '<?php echo $inventory_id; ?>',
																		   'status_box_<?php echo $inventory_id; ?>',
																		   'status',
													  {target:'status_box_<?php echo $inventory_id; ?>', preloader: 'prl'});
													  }			
															}
												else{
													 inventory.returnLink('<?php echo $type; ?>',
																		  '<?php echo $inventory_id; ?>',
																		  'status_box_<?php echo $inventory_id; ?>',
																		  'status',
													 {target:'status_box_<?php echo $inventory_id; ?>', preloader: 'prl'});
													}
												}});
												
										 <?php } else { ?> 
													inventory.updateField('status',
													type_name,
													'<?php echo $inventory_id;?>',
													'choice',
													{ onUpdate: function(response,root){
													 inventory.returnLink(type_name,
													 '<?php echo $inventory_id; ?>',
													 'status_box_<?php echo $inventory_id; ?>',
													 'status',
													 {target:'status_box_<?php echo $inventory_id; ?>', preloader: 'prl'});
													 
													 inventory.show_searchInventoryVendor({preloader:'prl',
											 onUpdate: function(response,root)
											 {document.getElementById('task_area').innerHTML=response; 
											  $('#search_table').tablesorter({widthFixed:true,
												 widgets:['zebra'],sortList:[[0,0]]	})}});
										  }}
									 ); <?php } ?>}
									 else{
									 inventory.returnLink('<?php echo $type; ?>',
														  '<?php echo $inventory_id; ?>',
														  'status_box_<?php echo $inventory_id; ?>',
														  'status',
									 {target:'status_box_<?php echo $inventory_id; ?>', preloader: 'prl'});
									 
									 inventory.show_searchInventoryVendor({preloader:'prl',
											 onUpdate: function(response,root)
											 {document.getElementById('task_area').innerHTML=response; 
											  $('#search_table').tablesorter({widthFixed:true,
												 widgets:['zebra'],sortList:[[0,0]]	})}});
										 }">
					<option value="" >--Select--</option>
					<option value="Active" <?php if('Active'==$type) echo 'selected="selected"';?>>Active</option>
					<option value="Inactive" <?php if('Inactive'==$type) echo 'selected="selected"';?>>Inactive</option>
				</select>
				<?php
				break;
				case 'measured_in': ?>
				   <select name="type_id" id="type_id" style="width:80%" 
				    onblur="javascript: var type_name=this.value;
								          if(confirm('Are you sure you want to change your measure from <?php 
										              echo $type; ?> to '+ type_name)){
												       inventory.updateField('measured_in',
														type_name,
														'<?php echo $inventory_id;?>',
														'choice',
														{ onUpdate: function(response,root){
														 inventory.returnLink(type_name,
														 '<?php echo $inventory_id; ?>',
														 'measured_in_box_<?php echo $inventory_id; ?>',
														 'measured_in',
														{target:'measured_in_box_<?php echo $inventory_id; ?>', preloader:'prl'});
														
														inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
											  }}
										 );}
										 else{
										 inventory.returnLink('<?php echo $type; ?>',
										 '<?php echo $inventory_id; ?>',
										 'measured_in_box_<?php echo $inventory_id; ?>',
										 'measured_in',
										 {target:'measured_in_box_<?php echo $inventory_id; ?>', preloader: 'prl'}
										 );
										 inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
										 }">
					 <option value="" >-Select-</option>
					 <option value="Inches" <?php if('Inches'==$type) echo 'selected="selected"';?>>Inches</option>
					 <option value="Yards" <?php if('Yards'==$type) echo 'selected="selected"';?>>Yards</option>
					 <option value="Meters" <?php if('Meters'==$type) echo 'selected="selected"';?>>Meters</option>
					 <option value="Millimeters" <?php if('Millimeters'==$type) echo 'selected="selected"';?>>Millimeters</option>
					 <option value="Pieces" <?php if('Pieces'==$type) echo 'selected="selected"';?>>Pieces</option>
					 <option value="Sets" <?php if('Sets'==$type) echo 'selected="selected"';?>>Sets</option>
					 <option value="Misc" <?php if('Misc'==$type) echo 'selected="selected"';?>>Misc</option>
				  </select>
				<?php
				break;
				case 'item_name':
				//$type1 = stripslashes($type);
				$sql = "select name from erp_inventory_details where inventory_id = '$inventory_id'";
				$result = $this->db->query($sql,__FILE__,__lINE__);
				$row=$this->db->fetch_array($result) ?>
				   <input type="text" name="name_id" id="name_id" value="<?php echo $row['name']; ?>" style="width:80%"
				    onblur="javascript: var type_name=this.value; 
								  if(confirm('Are you sure you want to change your item name <?php echo                                              $type1; ?> to '+ type_name)){																					
														inventory.updateField('',
														 type_name,
														 '<?php echo $inventory_id; ?>',
														 'item_name',
														 { onUpdate: function(response,root){
														   inventory.returnLink(type_name,
														   '<?php echo $inventory_id; ?>',
														   'item_name_box_<?php echo $inventory_id; ?>',
														   'item_name',
														   {target:'item_name_box_<?php echo $inventory_id; ?>                                                            ',preloader:'prl'});
														   inventory.show_searchInventoryVendor({preloader:'prl',
																 onUpdate: function(response,root)
																 {document.getElementById('task_area').innerHTML=response; 
																  $('#search_table').tablesorter({widthFixed:true,
																	 widgets:['zebra'],sortList:[[0,0]]	})}});
											  }}
										 );}
										 else{
                                             inventory.returnLink('<?php echo $type1; ?>',
                                                                  '<?php echo $inventory_id; ?>',
                                                                  'item_name_box_<?php echo $inventory_id; ?>',
                                                                  'item_name',
                                             {target:'item_name_box_<?php echo $inventory_id; ?>', preloader: 'prl'}
                                             );
											 inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
											 }">">
				<?php 
				break;
				case 'item_name_edit':
				$sql = "select name from erp_inventory_details where inventory_id = '$inventory_id'";
				$result = $this->db->query($sql,__FILE__,__LINE__);
				$row=$this->db->fetch_array($result);
				//$type1 = addslashes($type); ?>
				   <input type="text" name="edit_name_id" id="edit_name_id" value="<?php echo $row['name']; ?>" style="width:80%"
				    onblur="javascript: var type_name=this.value; 
								  if(confirm('Are you sure you want to change your item name <?php echo                                              $type; ?> to '+ type_name)){																					
											  inventory.updateField('name',
                                                                    type_name,
                                                                    '<?php echo $inventory_id; ?>',
                                                                    'choice_name',
																	'<?php echo $type; ?>',
                                             { onUpdate: function(response,root){
                                             
                                               inventory.returnLink(type_name,
                                                                   '<?php echo $inventory_id; ?>',
                                                                   'item_name_box_<?php echo $inventory_id; ?>',
                                                                   'item_name_edit',
									        {target:'item_name_box_<?php echo $inventory_id; ?>',preloader:'prl'});
											
											inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
											  }}
										 );}
                                         else{
                                             inventory.returnLink('<?php echo $type; ?>',
                                                                  '<?php echo $inventory_id; ?>',
                                                                  'item_name_box_<?php echo $inventory_id; ?>',
                                                                  'item_name_edit',
                                             {target:'item_name_box_<?php echo $inventory_id; ?>', preloader: 'prl'}
                                             );
											 inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
											 }">
				<?php 
				break;
				case 'vendor': 
					$sql_vendor="Select * from ".TBL_CONTACT." where contact_id = '$contact_id'";
					//echo $sql_vendor;
					$result_vendor = $this->db->query($sql_vendor);
					$row_vendor = $this->db->fetch_array($result_vendor);				
				?>
				    <select name="vendor_idd" id="vendor_idd" 
                            onblur="javascript:document.getElementById('lst_vendor').value='';
											for(i=0; i<document.getElementById('vendor_idd').length; i++){ 
												if(document.getElementById('vendor_idd')[i].selected==true){
												   document.getElementById('lst_vendor').value += 
												   document.getElementById('vendor_idd')[i].value+',';
												 }
											}
											document.getElementById('lst_vendor').value = 											                                            document.getElementById('lst_vendor').value.substr(0,
											document.getElementById('lst_vendor').value.length-1);

										    var type_name,type_id; 
											if(document.getElementById('lst_vendor').value == '<?php echo $contact_id; ?>'){
											    type_id = '<?php echo $contact_id; ?>'; 
											}
											else {
											    type_id = document.getElementById('lst_vendor').value ; 
											}
										    inventory.getName(type_id,
															 'vendor',
														    {onUpdate: function(response,root){
															type_name = response;
															if(confirm('Are you sure you want to change your vendor from <?php echo $type; ?> to '+ type_name)){
															
										inventory.show_contacts(type_id,
															  '<?php echo $inventory_id; ?>',
															  '<?php echo $contact_id; ?>',
															  'edit',
															  {target:'contacts_box_<?php echo $inventory_id; ?>',                                                               preloader:'prl'});																				
										inventory.updateField('vendor_id',
															  type_id,
															  '<?php echo $inventory_id; ?>',
															  'choice',
                                      { onUpdate: function(response,root){
                                        inventory.returnLink(type_name,
                                                            '<?php echo $inventory_id; ?>',
                                                            'vendor_box_<?php echo $inventory_id; ?>',
                                                            'vendor',
                                                            type_id,
                                                        {target:'vendor_box_<?php echo $inventory_id; ?>',
                                                         preloader: 'prl'});
														 
										inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
										}});}
									else{
										 inventory.returnLink('<?php echo $type; ?>',
										 '<?php echo $inventory_id; ?>',
										 'vendor_box_<?php echo $inventory_id; ?>',
										 'vendor',
										 {target:'vendor_box_<?php echo $inventory_id; ?>', preloader: 'prl'});
										 inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
										 }}});">
										 
					<option value="<?php echo $row_vendor['contact_id']; ?>"><?php echo $row_vendor['company_name']; ?></option>
				</select>
				<input name="lst_vendor" type="hidden" id="lst_vendor" value="<?php echo $row_vendor['contact_id']; ?>" size="60" />
				<?php
				break;
				case 'contact': 
			    $sql_dropdown = "select a.contact_id,a.type,a.first_name,a.last_name,a.company,b.inventory_id
				 from ".TBL_INVE_CONTACTS." a,".TBL_INVENTORY_DETAILS." b where a.type = 'people' and a.company = b.contact_id and b.inventory_id = '$inventory_id'";
			    $result_dropdown = $this->db->query($sql_dropdown,__FILE__,__LINE__); ?>
			 
				 <select name="contact_id" id="contact_id" style="width:80%" 
				  onblur="javascript: var contact_name; 
								   var type_id = this.value; 
								   inventory.getName(this.value,
								                     'contact',
												  { onUpdate: function(response,root){
													contact_name = response;
													if(confirm('Are you sure you want to change your contact from <?php echo                                                                $type; ?> to '+ contact_name)){																					
														inventory.updateField('vendor_id',
														 type_id,
														 '<?php echo $inventory_id;?>',
														 'choice',
														 { onUpdate: function(response,root){
															inventory.returnLink(contact_name,
														   '<?php echo $inventory_id; ?>',
														   'contacts_box_<?php echo $inventory_id; ?>',
														   'contact',
														    type_id,
														   {target:'contacts_box_<?php echo $inventory_id; ?>', preloader: 'prl' 
														});
														
														inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
											  }}
										 );}
										else{
										 inventory.returnLink('<?php echo $type; ?>',
										 '<?php echo $inventory_id; ?>',
										 'contacts_box_<?php echo $inventory_id; ?>',
										 'contact',
										 {target:'contacts_box_<?php echo $inventory_id; ?>', preloader: 'prl'});
										 inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
										 }}});">
					<option value="" >--Select--</option>
					<?php while($row_dropdown=$this->db->fetch_array($result_dropdown)){ ?>
					<option value="<?php echo $row_dropdown[contact_id]; ?>" <?php if($row_dropdown[first_name]==$type) 
					echo 'selected="selected"';?>>
					<?php echo $row_dropdown[first_name].' '.$row_dropdown[last_name]; ?> 
					</option>

					<?php }
					print_r($row_dropdown); ?>
			    </select>
				<?php
				break;
				case 'estimated_cost':
				$sql = "select estimated_cost from erp_inventory_details where inventory_id = '$inventory_id'";
				$result = $this->db->query($sql,__FILE__,__lINE__);
				$row=$this->db->fetch_array($result); ?>
				     <input type="text" name="cost_id" id="cost_id" value="<?php echo $row['estimated_cost']; ?>" 
					  style="width:80%"
				      onblur="javascript: var type_name=this.value; 
								    if(confirm('Are you sure you want to change your estimated cost from <?php                                                 echo $type; ?> to '+ type_name)){																					
												inventory.updateField('estimated_cost',
												 type_name,
												 '<?php echo $inventory_id;?>',
												 'choice',
												 { onUpdate: function(response,root){
												  inventory.returnLink(type_name,
												  '<?php echo $inventory_id; ?>',
												  'estimated_cost_box1_<?php echo $inventory_id; ?>',
												  'estimated_cost',
												 {target:'estimated_cost_box1_<?php echo $inventory_id; ?>', preloader: 'prl'});
												 
												 inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
											  }}
										 );}
										 else{
										 inventory.returnLink('<?php echo $type; ?>',
										 '<?php echo $inventory_id; ?>',
										 'estimated_cost_box1_<?php echo $inventory_id; ?>',
										 'estimated_cost',
										 {target:'estimated_cost_box1_<?php echo $inventory_id; ?>', preloader: 'prl'}
										 );
										 inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
										 }">
				
				<?php 
				break;
				case 'estimated_cost_2':
				$sql = "select estimated_cost,measured_in from erp_inventory_details where inventory_id = '$inventory_id'";
				$result = $this->db->query($sql,__FILE__,__lINE__);
				$row=$this->db->fetch_array($result); ?>
				     <input type="text" name="cost_id" id="cost_id" value="<?php echo $row['estimated_cost']; ?>" 
					  style="width:80%"
				      onblur="javascript: var type_name=this.value; 
								    if(confirm('Are you sure you want to change your estimated cost from  <?php                                                 echo $type; ?> to '+ type_name)){																					
												inventory.updateField('estimated_cost',
													 type_name,
													 '<?php echo $inventory_id;?>',
													 'choice',
													{ onUpdate: function(response,root){
													  inventory.returnLink(type_name,
													  '<?php echo $inventory_id; ?>',
													  'estimated_cost_2',
													  {target:'estimated_cost_box2_<?php echo $inventory_id; ?>', 
													  preloader: 'prl'});
													  
													  inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
											  }}
										 );}
										 else{
										 inventory.returnLink('<?php echo $type; ?>',
										 '<?php echo $inventory_id; ?>',
										 'estimated_cost_box2_<?php echo $inventory_id; ?>',
										 'estimated_cost_2',
										 {target:'estimated_cost_box2_<?php echo $inventory_id; ?>', preloader: 'prl'}
										 );
										 inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
										 }">
				
				<?php 
				break;
				case 'reorder_instruction':
				$sql = "select reorder_instruction from erp_inventory_details where inventory_id = '$inventory_id'";
				$result = $this->db->query($sql,__FILE__,__lINE__);
				$row=$this->db->fetch_array($result); ?>
					 <textarea name="reorder_id" id="reorder_id" style="width:80%"
				      onblur="javascript: var type_name=this.value; 
								   if(confirm('Are you sure you want to change your reorder instruction from <?php
								               echo $type; ?> to '+ type_name)){																					
												inventory.updateField('reorder_instruction',
												 type_name,
												 '<?php echo $inventory_id;?>',
												 'choice',
												 { onUpdate: function(response,root){
													inventory.returnLink(type_name,
												   '<?php echo $inventory_id; ?>',
												   'reorder_instruction_box_<?php echo $inventory_id; ?>',
												   'reorder_instruction',
												 {target:'reorder_instruction_box_<?php echo $inventory_id; ?>',                                                  preloader:'prl'});
												 
												 inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
											  }}
										 );}
										 else{
										 inventory.returnLink('<?php echo $type; ?>',
										 '<?php echo $inventory_id; ?>',
										 'reorder_instruction_box_<?php echo $inventory_id; ?>',
										 'reorder_instruction',
										 {target:'reorder_instruction_box_<?php echo $inventory_id; ?>', preloader: 'prl'}
										 );
										 inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
										 }"><?php echo $row['reorder_instruction']; ?></textarea>
				
				<?php 
				break;
				case 'onhand':
				$sql = "select amt_onhand from erp_inventory_details where inventory_id = '$inventory_id'";
				$result = $this->db->query($sql,__FILE__,__lINE__);
				$row=$this->db->fetch_array($result); ?>
				    <input type="text" name="onhand_id" id="onhand_id" value="<?php echo $row['amt_onhand']; ?>" style="width:80%"
				    onblur="javascript: var type_name=this.value; 
								      if(confirm('Are you sure you want to change your reorder threashhold from <?php echo $type;                                                  ?> to '+ type_name)){																					
												 inventory.updateField('amt_onhand',
												   type_name,
												   '<?php echo $inventory_id;?>',
												   'choice',
												  { onUpdate: function(response,root){
													inventory.returnLink(type_name,
													'<?php echo $inventory_id; ?>',
													'onhand_box_<?php echo $inventory_id; ?>',
													'onhand',
											       {target:'onhand_box_<?php echo $inventory_id; ?>', preloader: 'prl' 
											  });
											  
											  inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)

												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
											  }}
										 );}
										 else{
										 inventory.returnLink('<?php echo $type; ?>',
										 '<?php echo $inventory_id; ?>',
										 'onhand_box_<?php echo $inventory_id; ?>',
										 'onhand',
										 {target:'onhand_box_<?php echo $inventory_id; ?>', preloader: 'prl'}
										 );
										 inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
										 }">
				
				<?php 
				break;
				case 'ordered':
				$sql = "select ordered from erp_inventory_details where inventory_id = '$inventory_id'";
				$result = $this->db->query($sql,__FILE__,__lINE__);
				$row=$this->db->fetch_array($result); ?>
				    <input type="text" name="order_id" id="order_id" value="<?php echo $row['ordered']; ?>" style="width:80%"
				    onblur="javascript: var type_name=this.value; 
								      if(confirm('Are you sure you want to change your reorder threashhold from <?php echo $type;                                                  ?> to '+ type_name)){																					
												 inventory.updateField('ordered',
												   type_name,
												   '<?php echo $inventory_id;?>',
												   'choice',
												  { onUpdate: function(response,root){
													inventory.returnLink(type_name,
													'<?php echo $inventory_id; ?>',
													'ordered_box_<?php echo  $inventory_id; ?>',
													'ordered',
												   {target:'ordered_box_<?php echo $inventory_id; ?>', preloader: 'prl' 
											  });
											  
											  inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});

											  }}
										 );}
										 else{
										 inventory.returnLink('<?php echo $type; ?>',
										 '<?php echo $inventory_id; ?>',
										 'ordered_box_<?php echo $inventory_id; ?>',
										 'ordered',
										 {target:'ordered_box_<?php echo $inventory_id; ?>', preloader: 'prl'}
										 );
										 inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
										 }">
				
				<?php 
				break;
				case 'Reorder_threashhold':
				$sql = "select reorder from erp_inventory_details where inventory_id = '$inventory_id'";
				$result = $this->db->query($sql,__FILE__,__lINE__);
				$row=$this->db->fetch_array($result); ?>
				     <input type="text" name="threashhold_id" id="threashhold_id" value="<?php echo $row['reorder']; ?>" 
					  style="width:80%"
				      onblur="javascript: var type_name=this.value; 
								   if(confirm('Are you sure you want to change your reorder threashhold from<?php echo $type;                                     ?> to '+ type_name)){																					
									inventory.updateField('reorder',
									type_name,
									'<?php echo $inventory_id;?>',
									'choice',
									{ onUpdate: function(response,root){
									inventory.returnLink(type_name,
									 '<?php echo $inventory_id; ?>',
									 'Reorder_threashhold_box_<?php  echo $inventory_id; ?>',
									 'Reorder_threashhold',
									 {target:'Reorder_threashhold_box_<?php  echo $inventory_id; ?>'});
									 
									 inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
											  }}
										 );}
										 else{
										 inventory.returnLink('<?php echo $type; ?>',
										 '<?php echo $inventory_id; ?>',
										 'Reorder_threashhold_box_<?php echo $inventory_id; ?>',
										 'Reorder_threashhold',
										 {target:'Reorder_threashhold_box_<?php echo $inventory_id; ?>', preloader: 'prl'}
										 );
										 inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
										 }">
				
				<?php 
				break;
				case 'warning_amount':
				   $sql = "select warning_amount from erp_inventory_details where inventory_id = '$inventory_id'";
				   $result = $this->db->query($sql,__FILE__,__lINE__); 
				   $row=$this->db->fetch_array($result); ?>
				        <input type="text" name="warning_id" id="warning_id" style="width:80%" 
						 value="<?php echo $row['warning_amount']; ?>"
						 onblur="javascript: var type_name=this.value; 
								      if(confirm('Are you sure you want to change your warning amount from <?php                                                   echo $type; ?> to '+ type_name)){																					
										inventory.updateField('warning_amount',
										 type_name,
										 '<?php echo $inventory_id;?>',
										 'choice',
										 { onUpdate: function(response,root){
										  inventory.returnLink(type_name,
										  '<?php echo $inventory_id; ?>',
										  'warning_box_<?php echo $inventory_id; ?>',
										  'warning_amount',
										 {target:'warning_box_<?php echo $inventory_id; ?>', preloader: 'prl'});
										 
										 inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
											  }}
										 );}
										 else{
										 inventory.returnLink('<?php echo $type; ?>',
										 '<?php echo $inventory_id; ?>',
										 'warning_box_<?php echo $inventory_id; ?>',
										 'warning_amount',
										 {target:'warning_box_<?php echo $inventory_id; ?>', preloader: 'prl'}
										 );
										 inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
										 }">
				
				<?php 
				break;
				case 'item_num_edit':
				$sql = "select Item_number from erp_inventory_details where inventory_id = '$inventory_id'";
				$result = $this->db->query($sql,__FILE__,__LINE__);
				$row=$this->db->fetch_array($result);
				$item=$row['Item_number'];
				?>
				   <input type="text" name="edit_num_id" id="edit_num_id" value="<?php echo $row['Item_number']; ?>" style="width:80%"
				    onblur="javascript: var type_num=this.value; 
								  if(confirm('Are you sure you want to change your item number <?php echo $type; ?> to '+ type_num)){																					
											  inventory.updateField('Item_number',
                                                                    type_num,
                                                                    '<?php echo $inventory_id; ?>',
                                                                    'choice_name',
																	'<?php echo $type; ?>',
                                             { onUpdate: function(response,root){
                                             
                                               inventory.returnLink(type_num,
                                                                   '<?php echo $inventory_id; ?>',
                                                                   'item_num_box_<?php echo $inventory_id; ?>',
                                                                   'item_num_edit',
									        {target:'item_num_box_<?php echo $inventory_id; ?>',preloader:'prl'});
											
											inventory.show_searchInventoryVendor({preloader:'prl',
							                     onUpdate: function(response,root)
												 {document.getElementById('task_area').innerHTML=response; 
												  $('#search_table').tablesorter({widthFixed:true,
												     widgets:['zebra'],sortList:[[0,0]]	})}});
											  }}
										 );}
                                         else if ('<?php echo  $item ?>'!=''){
										 		inventory.returnLink('<?php echo $item; ?>',
																 '<?php echo $inventory_id; ?>',
																 'item_num_box_<?php echo $inventory_id; ?>',
																 'item_num_edit',
																 {target:'item_num_box_<?php echo $inventory_id; ?>', preloader: 'prl'}
																 );
											 inventory.show_searchInventoryVendor({preloader:'prl',
													 onUpdate: function(response,root)
													 {document.getElementById('task_area').innerHTML=response; 
													  $('#search_table').tablesorter({widthFixed:true,
														 widgets:['zebra'],sortList:[[0,0]]	})}});
										     }
										 else{
										     inventory.returnLink('',
																 '<?php echo $inventory_id; ?>',
																 'item_num_box_<?php echo $inventory_id; ?>',
																 'item_num_edit',
																 {target:'item_num_box_<?php echo $inventory_id; ?>', preloader: 'prl'}
																 );
											 inventory.show_searchInventoryVendor({preloader:'prl',
													 onUpdate: function(response,root)
													 {document.getElementById('task_area').innerHTML=response; 
													  $('#search_table').tablesorter({widthFixed:true,
														 widgets:['zebra'],sortList:[[0,0]]	})}});
										 
                                       }">
				<?php 
				break;
				
				 }  /////////////end of switch
				$html = ob_get_contents();
				ob_end_clean();
				return $html;	
				}  ////////////////end of function showDropDown
	
	function getName($id='',$choice=''){
				ob_start();
				switch($choice) {
				  case 'item_type':
					 $sql = "select * from erp_item_type where type_id = '$id'";
					 $result = $this->db->query($sql,__FILE__,__lINE__);	
					 $row=$this->db->fetch_array($result);
					 echo $row[type_name];
				  break;
				  case 'contact':
					 $sql= "select * from ".TBL_INVE_CONTACTS." where contact_id = '$id'";
					 $result = $this->db->query($sql,__FILE__,__lINE__);	
					 $row=$this->db->fetch_array($result);
					 echo $row[first_name];
				  break;
				  /*case 'measured_in':
					 $sql= "select * from erp_inventory_details where inventory_id = '$id'";
					 $result = $this->db->query($sql,__FILE__,__lINE__);	
					 $row=$this->db->fetch_array($result);
					 echo $row[measured_in];
				  break;*/
				  case 'vendor':
					 $sql= "select * from contacts where contact_id = '$id'";
					 $result = $this->db->query($sql,__FILE__,__lINE__);	
					 $row=$this->db->fetch_array($result);
					 echo $row[company_name];
				  break;
				  case 'people':
				     $sql= "select * from contacts where contact_id = '$id'";
					 $result = $this->db->query($sql,__FILE__,__lINE__);	
					 $row=$this->db->fetch_array($result);
					 echo $row[first_name].' '.$row[last_name];
				  break;
					 
				  }  /////////////end of switch
					$html = ob_get_contents();
					ob_end_clean();
					return $html;	
				}   ////////////////end of function getName

	function updateField($field='',$value='',$inventory_id='',$choice='',$inventory_name=''){
			ob_start();
				switch($choice) {
				  case 'choice':
					$sql = "update ".TBL_INVENTORY_DETAILS." set $field= '$value' where inventory_id='$inventory_id'";
					$this->db->query($sql,__FILE__,__LINE__);	
				  break;
				  case 'choice_name':
					$val = htmlspecialchars($value, ENT_QUOTES);
					//$val = mysql_real_escape_string($value);
					//echo $val; 
					$sql = "update ".TBL_INVENTORY_DETAILS." set $field= '$val' where inventory_id='$inventory_id'";
					$this->db->query($sql,__FILE__,__LINE__);
					
					$sql = "update erp_assign_inventory set $field= '$val' where inventory_id='$inventory_id'";
					$this->db->query($sql,__FILE__,__LINE__);
					
					$sql = "update erp_create_group set inventory_name = '$val' where inventory_name = '$inventory_name'";
					$this->db->query($sql,__FILE__,__LINE__);
				  break;				  
				  case 'item_name':
					 $sql = "select * from".TBL_INVENTORY_DETAILS."where inventory_id='$inventory_id'";
					 $result = $this->db->query($sql,__FILE__,__LINE__);
					 $row=$this->db->fetch_array($result);
					 $select_sql_values = array();
					 $select_sql_values[name] = htmlspecialchars($value, ENT_QUOTES);
					 $select_sql_values[type_id] = $row[type_id];
					 $select_sql_values[status] = $row[status];
					 $select_sql_values[measured_in] = $row[measured_in];
					 $select_sql_values[allocated] = $row[allocated];
					 $select_sql_values[ordered] = $row[ordered];
					 $select_sql_values[amt_onhand] = $row[amt_onhand];
					 $select_sql_values[reorder] = $row[reorder];
					 $select_sql_values[estimated_cost] = $row[estimated_cost];
					 $select_sql_values[reorder_instruction] = $row[reorder_instruction];
					 $select_sql_values[warning_amount] = $row[warning_amount];
					 $select_sql_values[contact_id] = $row[contact_id];
					 
					 $this->db->insert(TBL_INVENTORY_DETAILS,$insert_sql_array);
					  
				  break;
				  case 'delete_product':
					$sql = "update ".TBL_INVENTORY_DETAILS." set $field= '$value' where inventory_id='$inventory_id'";
					$result = $this->db->query($sql,__FILE__,__LINE__);
					
					$sql="DELETE FROM erp_assign_inventory WHERE inventory_id = '$inventory_id'";
	                $this->db->query($sql,__FILE__,__LINE__);
					
					$sql = "UPDATE erp_work_order SET fabric = NULL WHERE fabric = '$inventory_id'";
				    $this->db->query($sql,__FILE__,__LINE__);
				  
				    $sql = "UPDATE erp_work_order SET zipper = NULL WHERE zipper = '$inventory_id'";
				    $this->db->query($sql,__FILE__,__LINE__);
				  
				    $sql = "UPDATE erp_work_order SET pad = NULL WHERE pad = '$inventory_id'";
				    $this->db->query($sql,__FILE__,__LINE__);
				  
				    $sql = "UPDATE erp_work_order SET elastic = NULL WHERE elastic = '$inventory_id'";
				    $this->db->query($sql,__FILE__,__LINE__);
				  
				    $sql = "UPDATE erp_work_order SET lining = NULL WHERE lining = '$inventory_id'";
				    $this->db->query($sql,__FILE__,__LINE__);
				  break;
			  
			 } ///////////end of switch 
				$html = ob_get_contents();
				ob_end_clean();
				return $html;
	}   ////////////////end of function updateField 

  function show_link($id){
  	ob_start();
	?>
	<a href="javascript:void(0);" onClick="javascript:inventory.add_clone('local',
											                              '<?php echo $id; ?>',
                                                        {preloader: 'prl', onUpdate: function(response,root){
                                                        document.getElementById('show_value').innerHTML=response;
                                                        document.getElementById('show_value').style.display='';
                                                        autosuggest();
                                                        }});">clone selected product</a>	
			<?php
			$html = ob_get_contents();
			ob_end_clean();
			return $html;	
	}  ////////////////end of function show_link


	  function add_clone($runat='',$inventory_id='') {
	         ob_start(); 
			    switch($runat) {
				  case 'local': 
				        $sql1="SELECT * FROM ". TBL_INVENTORY_DETAILS ." WHERE inventory_id ='$inventory_id'";
				        $result1=$this->db->query($sql1,__FILE__,__LINE__);
				        $row1=$this->db->fetch_array($result1); ?>
				  <form name="add_clone" method="post" action="">
				  <table class="table" width="100%">
				  <tr>
					<th>Item Name :</th>
					<td><input type="text" name="item_name" id="item_name" value="<?php echo $row1['name']; ?>" /></td>
					<th>Status :</th>
					<td>
						<select name="status" id="status">
						   <option value="">-select-</option>
						   <option value="Active"<?php if($row1['status']=='Active') echo 'selected="selected"';?>>Active</option>
						   <option value="Inactive"<?php if($row1['status']=='Inactive') echo 'selected="selected"';?>>Inactive
						   </option>
						</select>
					</td>
				 </tr>
				 <tr>
				    <th>Item Type :</th>
					<td>
					<?php
						$sql="SELECT * FROM erp_item_type ";
						$result=$this->db->query($sql); ?>
						<select name="item_type" id="item_type">
						   <option value="">-select-</option>
						   <?php while($row=$this->db->fetch_array($result)) { ?>
						   <option value="<?php echo $row['type_id']; ?>"<?php if($row1['type_id']==$row['type_id']) 
						   echo 'selected="selected"'; ?>><?php echo $row['type_name']; ?></option>
						   <?php }  ?>
						</select>
					</td>
				 </tr>	
				 <tr>	
				    <th>Measured In :</th>
				    <td>
						<select name="measured_in" id="measured_in">
						  <option value="">-select-</option>
						  <option value="Inches"<?php if($row1['measured_in']=='Inches') echo 'selected="selected"';?>>Inches
						  </option>
						  <option value="Yards"<?php if($row1['measured_in']=='Yards') echo 'selected="selected"';?>>Yards
						  </option>
						  <option value="Meters"<?php if($row1['measured_in']=='Meters') echo 'selected="selected"';?>>Meters
						  </option>
						  <option value="Millimeters"<?php if($row1['measured_in']=='Millimeters') echo 'selected="selected"';?>
						  >Millimeters</option>
						  <option value="Pieces"<?php if($row1['measured_in']=='Pieces') echo 'selected="selected"';?>>Pieces
						  </option>
						  <option value="Sets"<?php if($row1['measured_in']=='Sets') echo 'selected="selected"';?>>Sets</option>
						  <option value="Misc"<?php if($row1['measured_in']=='Misc') echo 'selected="selected"';?>>Misc</option>
						</select>					  
					</td>
				 </tr>
				 <tr>
					<th>Vendor :</th>
					<td colspan="3">
						<?php 
						$sql_vendor="Select * from ".TBL_CONTACT." where contact_id = '$row1[contact_id]'";
						//echo $sql_vendor;
						$result_vendor = $this->db->query($sql_vendor);
						$row_vendor = $this->db->fetch_array($result_vendor);
                        ?>
					  	<select name="vendor_id" id="vendor_id" onChange="javascript: document.getElementById('company_id').value='';
											for(i=0; i<document.getElementById('vendor_id').length; i++){ 
												if(document.getElementById('vendor_id')[i].selected==true){
												   document.getElementById('company_id').value += 
												   document.getElementById('vendor_id')[i].value+',';
												 }
											}
											document.getElementById('company_id').value = 											                                            document.getElementById('company_id').value.substr(0,
											document.getElementById('company_id').value.length-1);
											if(document.getElementById('company_id').value == '<?php echo $row_vendor['contact_id']; ?>'){
												inventory.show_contacts('<?php echo $row_vendor['contact_id']; ?>',
																	'','','add',	
																	{target:'contacts',preloader:'prl'});
											}
											else{
						 					inventory.show_contacts(document.getElementById('company_id').value,
																	'','','add',	
																	{target:'contacts',preloader:'prl'});}">
						<option value="<?php echo $row_vendor['contact_id']; ?>"><?php echo $row_vendor['company_name']; ?></option>
						</select>
						<input type="hidden" id="company_id" name="company_id" value="<?php echo $row_vendor['contact_id']; ?>" />
					 </td>			
				  </tr>	
				  <tr>
				    <td colspan="4">
                  		<div id="contacts"></div>
					</td>
				  </tr>
				  <tr>
				      <th>Allocated</th>
					  <td>
					    <input type="text" name="allocated" id="allocated" value="<?php echo $row1['allocated']; ?>" />
					  </td>
				  </tr>				  
	              <tr>
				      <th>Onhand :</th>
				      <td colspan="3">
					     <input type="text" name="onhand" id="onhand" value="<?php echo $row1['amt_onhand']; ?>" />
				      </td>
				   </tr>
				   <tr>	   
	   	              <th>Ordered :</th>
				      <td colspan="3">
    			  	     <input type="text" name="ordered" id="ordered" value="<?php echo $row1['ordered']; ?>"/>
					  </td>
				  </tr>
				  <tr>
				      <th>Reorder Threashhold : </th> 
				      <td colspan="2">
					     <input type="text" name="reorder_threashhold" id="reorder_threashhold" value="<?php echo $row1['reorder']                         ; ?>" />
					   </td>
				  </tr>
				  <tr>
					  <th>(O+O)- A Warning: </th>
					  <td colspan="3">
					     <input type="text" name="warning" id="warning" value="<?php echo $row1['warning_amount']; ?>" />
					  </td>
				 </tr>
				 <tr>
				      <th>Estimated costs :</th>
					  <td colspan="3">
					      <input type="text" name="estimated_cost" id="estimated_cost" value="<?php echo $row1['estimated_cost'];                          ?>" />
					  </td>
				  </tr>
				  <tr>
					  <th colspan="4">Reorder Instructions</th>
				  </tr>
				  <tr>
					  <td colspan="4" style="border-bottom-color:#fff !important;">
					      <textarea name="reorder_instruction" id="reorder_instruction" cols="30" rows="8">
						  <?php echo $row1['reorder_instruction']; ?></textarea>
 				      </td>
				  </tr>
				  <tr>
				      <td>&nbsp;</td>
					  <td>
					  <input type="submit" name="clone_this" value="Clone" />
					  </td>
				  </tr>
				  </table>
				  </form>
              <?php	  
	              break;
				  
		  case 'server':
		          extract($_POST);
				  $return=true;
				  $name = mysql_real_escape_string($item_name);
				  $sql1="SELECT * FROM ". TBL_INVENTORY_DETAILS." where name= '$name'";
				  $result1=$this->db->query($sql1,__FILE__,__LINE__);
				  if($this->db->num_rows($result1) > 0) { ?>
					  <script> 
					     alert("Item name is already exist");
					  </script>
				<?php 
				 return false; }
			if($return)	{	  
				  $insert_sql_array = array();
				  $insert_sql_array[name] = htmlspecialchars($item_name, ENT_QUOTES);
				  $insert_sql_array[type_id] = $item_type;
				  $insert_sql_array[status] = $status;
				  $insert_sql_array[measured_in] = $measured_in;
				  $insert_sql_array[allocated] =  $allocated;
				  $insert_sql_array[ordered] =  $ordered;
				  $insert_sql_array[amt_onhand] =  $onhand;
				  $insert_sql_array[reorder] = $reorder_threashhold;
				  $insert_sql_array[estimated_cost] = $estimated_cost;
				  $insert_sql_array[reorder_instruction] = $reorder_instruction;
				  $insert_sql_array[warning_amount] = $warning;
				  $insert_sql_array[contact_id] = $contact;
				  
				 $this->db->insert(TBL_INVENTORY_DETAILS,$insert_sql_array); ?>
				 <script>
				      window.location="<?php echo $_SERVER['PHP_SELF']; ?>";     
				 </script>
				 <?php } break;		
	        }  ///////end of switch
	  
	           $html = ob_get_contents();
			   ob_end_clean();
			   return $html;
	 }   ////////////////end of function add_clone 
	 
	 function deleteInventory($inventory_id='',$z='',$inventory_name=''){
	      ob_start();
		  if( $z != '' ){
		      $sql = "DELETE FROM ".TBL_INVENTORY_DETAILS." WHERE inventory_id = '$inventory_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
		  } else {
			  $sql = "DELETE FROM ".TBL_INVENTORY_DETAILS." WHERE inventory_id = '$inventory_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
			  
			  $sql = "DELETE FROM erp_assign_inventory WHERE inventory_id = '$inventory_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
			  
			  $sql = "DELETE FROM erp_create_group WHERE inventory_name = '$inventory_name'";
			  $this->db->query($sql,__FILE__,__LINE__);
			  
			  $sql = "UPDATE erp_work_order SET fabric = NULL WHERE fabric = '$inventory_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
			  
			  $sql = "UPDATE erp_work_order SET zipper = NULL WHERE zipper = '$inventory_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
			  
			  $sql = "UPDATE erp_work_order SET pad = NULL WHERE pad = '$inventory_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
			  
			  $sql = "UPDATE erp_work_order SET elastic = NULL WHERE elastic = '$inventory_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
			  
			  $sql = "UPDATE erp_work_order SET lining = NULL WHERE lining = '$inventory_id'";
			  $this->db->query($sql,__FILE__,__LINE__);
		  }

		  $html=ob_get_contents();
		  ob_end_clean();
		  return $html;
	  }  ////////////end of function deleteInventory

   }   ///////////end of class
   
   ?>