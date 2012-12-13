<?php

class ProductInventory{

var $db;
var $ad;
var $company_id;
var $validity;

	function __construct(){
	$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	$this->validity = new ClsJSFormValidation();
	$this->Form = new ValidateForm();
	}

  function InVeDetails() {
     ob_start();
	 $formName = "frm_search";?>
	  <script>
	 function formatProName(str){
	 	var a=Array();
		a =str.split("'");
		var n = a.length;
		var i;
		var r = '';
		if(n <= 2 ){
			var pro_name = str.replace("'","&#039;");
			return pro_name;
		}
		else{
		 for(i=0;i<(n-1);i++)
			{
				r +=a[i]+"&#039;"
			}
		return r;
		}	
	 }
	 </script>
 	 <form name="<?php echo $formName;?>" method="post" action="">
		<table width="100%" class="table" >
			<tr>
			<td>Product ID</td>
			<td>Product Name</td>
			<td>Type</td>
		    </tr>
			<tr>
				<td width="33%"><input type="text" name="txt_name" id="txt_name" 
							onchange="javascript:obj_product.add_clone('delete',
														  			 {preloader: 'prl'});
							var pro_name = formatProName(document.getElementById('pro_name').value);
							 obj_product.show_searchproductinventory(
										document.getElementById('txt_name').value,
										pro_name,
										document.getElementById('select_type').value,
										document.getElementById('item_number').value,
										document.getElementById('product_type').value,
										document.getElementById('product_status').value,
										document.getElementById('vendor').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
												document.getElementById('show_value').style.display='none';
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} }) }});
												"/></td>	
				<td width="33%"><input type="text" name="pro_name" id="pro_name" 
							onchange="javascript:obj_product.add_clone('delete',
														  			 {preloader: 'prl'});
							var pro_name = formatProName(document.getElementById('pro_name').value);
							 obj_product.show_searchproductinventory(
										document.getElementById('txt_name').value,
										pro_name,
										document.getElementById('select_type').value,
										document.getElementById('item_number').value,
										document.getElementById('product_type').value,
										document.getElementById('product_status').value,
										document.getElementById('vendor').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
												document.getElementById('show_value').style.display='none';
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} }) }});
												"/></td>	
				<td width="33%">
					<select   style="width:100%;" name="select_type" id="select_type" 
							onchange="javascript:obj_product.add_clone('delete',
														  			 {preloader: 'prl'});
							var pro_name = formatProName(document.getElementById('pro_name').value);
							 obj_product.show_searchproductinventory(
										document.getElementById('txt_name').value,
										pro_name,
										this.value,
										document.getElementById('item_number').value,
										document.getElementById('product_type').value,
										document.getElementById('product_status').value,
										document.getElementById('vendor').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
												document.getElementById('show_value').style.display='none';
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: {5:{sorter: false}} }) }});
												">
							<option value="">-Select-</option>
							<option value="Cycling">Cycling</option>
							<option value="Jersey">Jersey</option>
							<option value="Tri/Run">Tri/Run</option>
							<option value="Nordic">Nordic</option>
							<option value="Alpine">Alpine</option>
							<option value="Wrestling">Wrestling</option>
							<option value="PreCuts">PreCuts</option>
					 </select>
				</td>
				
				<tr>
				<td>Product Type</td>
			    <td>Product Status</td>
			    <td>Item Number</td>
			    </tr>
				<td width="33%">
				<select style="width:100%;" name="product_type" id="product_type" 
				                onchange="javascript:obj_product.add_clone('delete',
														  			 {preloader: 'prl'});
								var pro_name = formatProName(document.getElementById('pro_name').value);
								 obj_product.show_searchproductinventory(
										document.getElementById('txt_name').value,
										pro_name,
										document.getElementById('select_type').value,
										document.getElementById('item_number').value,
										document.getElementById('product_type').value,
										document.getElementById('product_status').value,
										document.getElementById('vendor').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
												document.getElementById('show_value').style.display='none';
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: {5:{sorter: false}} }) }});
												">
				  <option value="">--Select--</option>
				  <option value="Full Custom">Full Custom</option>
				  <option value="Custom">Custom</option>
				</select>
				</td>
				<td width="33%">
				    <select name="product_status"  style="width:100%;" id="product_status"
					           onchange="javascript:obj_product.add_clone('delete',
														  			 {preloader: 'prl'});
								var pro_name = formatProName(document.getElementById('pro_name').value);
							    obj_product.show_searchproductinventory(
										document.getElementById('txt_name').value,
										pro_name,
										document.getElementById('select_type').value,
										document.getElementById('item_number').value,
										document.getElementById('product_type').value,
										this.value,
										document.getElementById('vendor').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
												document.getElementById('show_value').style.display='none';
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: {5:{sorter: false}} }) }});
												">
					   <option value="Active" selected="selected">Active</option>
					   <option value="Inactive">Inactive</option>
					   <option value="Research">Research</option>
					   
				   </select>
				</td>
				<td width="33%"><input type="text" name="item_number" id="item_number"				 
							onchange="javascript:obj_product.add_clone('delete',
														  			 {preloader: 'prl'});
							var pro_name = formatProName(document.getElementById('pro_name').value);
							obj_product.show_searchproductinventory(
										document.getElementById('txt_name').value,
										pro_name,
										document.getElementById('select_type').value,
										document.getElementById('item_number').value,
										document.getElementById('product_type').value,
										document.getElementById('product_status').value,
										document.getElementById('vendor').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
												document.getElementById('show_value').style.display='none';
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: {5:{sorter: false}} }) }});
												"/>
				</td>
				</tr>
			 <tr>
			    <td>Uses...</td>
			 </tr>
			 <tr>
			 	<td>
				   <select name="vendor" id="vendor"
				           onchange="javascript:obj_product.add_clone('delete',
														  			 {preloader: 'prl'});
										var pro_name = formatProName(document.getElementById('pro_name').value);
						                document.getElementById('uses_id').value='';
										for(i=0; i<document.getElementById('vendor').length; i++){
										
											if(document.getElementById('vendor')[i].selected==true){
												document.getElementById('uses_id').value += document.getElementById('vendor')[i].value+',';
											 }
										 }
										 document.getElementById('uses_id').value = 											                                                   document.getElementById('uses_id').value.substr(0,
												   document.getElementById('uses_id').value.length-1);
										
										 obj_product.show_searchproductinventory(document.getElementById('txt_name').value,
																				 pro_name,
																				 document.getElementById('select_type').value,
																				 document.getElementById('item_number').value,
																				 document.getElementById('product_type').value,
																				 document.getElementById('product_status').value,
																				 document.getElementById('uses_id').value,
																				 {preloader:'prl',
																				 onUpdate: function(response,root){
																	document.getElementById('task_area').innerHTML=response;
																	document.getElementById('show_value').style.display='none';
																	$('#search_table')
																 .tablesorter({widthFixed:true,widgets:['zebra'],sortList:[[0,0]], headers: {5:{sorter: false}} }) }});	
					                   						  
								    ">
		            </select>
					<input name="uses_id" type="hidden" id="uses_id" value="" size="60" />
				</td>
			 </tr>
           </table>
	       </form>
  		<?php    
        $html = ob_get_contents();
		ob_end_clean();
		return $html;    
    }   //////end of function InVeDetails
	
	function GetVendorJson( $pattern='', $type_id='' ){
		ob_start();
		$contact_json = "";
		if( $type_id == '' ){
			$sql="select inventory_id, name from ".TBL_INVENTORY_DETAILS." where name LIKE '$pattern%'";
			$record=$this->db->query($sql,__LINE__,__FILE__);
			while($row = $this->db->fetch_array($record)){
			$cmp = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $row[name]);
			$contact_json .='{"caption":"'.$cmp.'","value":"'.$row[inventory_id].'"},';
			}
			$contact_json = '['.substr($contact_json,0,strlen($contact_json)-1).']';
			return $contact_json;
		} else {
		
			$sql="select inventory_id, name from ".TBL_INVENTORY_DETAILS." where name LIKE '$pattern%' and type_id = '$type_id'";
			$record=$this->db->query($sql,__LINE__,__FILE__);
			while($row = $this->db->fetch_array($record)){
			$cmp = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $row[name]);
			$contact_json .='{"caption":"'.$cmp.'","value":"'.$row[inventory_id].'"},';
			}
			$contact_json = '['.substr($contact_json,0,strlen($contact_json)-1).']';
			return $contact_json;
		 }
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} ///////end of function GetVendorJson
	
	function show_searchproductinventory($product_id='',$pro_name='',$p_type='',$item_num='',$product_type='',$product_status='',$uses='') {
		ob_start();
		
		if($product_status!=''){
	    $sql = "select * from ". erp_PRODUCT ." where group_product_id='0'"; }
		else{
	    $sql = "select * from ". erp_PRODUCT ." where product_status='Active' and group_product_id='0'"; }
		if($product_id){
		$sql.=" and product_id like '%$product_id%' ";}
		
		if($pro_name){
		$sql.=" and product_name like '%$pro_name%' ";}
		
		if($p_type){
		$sql.=" and type = '$p_type' ";}
		
		if($item_num){
		$sql.=" and item_number like '%$item_num%' ";}
		
		if($product_type){
		$sql.=" and product_type = '$product_type' ";}
		
		if($product_status){
		$sql.=" and product_status = '$product_status' ";}
		
		if($uses){
		$sql.=" and product_id in (Select product_id from ".erp_ASSIGN_INVENTORY." where inventory_id = '$uses')";}
		
		$result= $this->db->query($sql,_FILE_,_LINE_);
		$total_rows=$this->db->num_rows($result);
		?>
		<table id="search_table" class="event_form small_text" width="100%">
			<thead>
			   <tr>
				  <th>ID</th>
				  <th>Product</th>
				  <th>Item Num</th>
				  <th>Type</th>
				  <th>Product Type</th>
				  <th>&nbsp;</th>
			  </tr>
			</thead>
			<tbody>
			  <?php 
			  if($total_rows >0 ){
				 while($row=$this->db->fetch_array($result)){?>
				 <tr>
					<td><?php echo $row['product_id'];?></td>	  
					<td><a href="javascript:void(0);" 
									onclick="javascipt:
											obj_product.add_clone('delete',
														   {preloader: 'prl'});
											obj_product.show_inve(
													  '<?php echo $row['product_id']; ?>',
													  '<?php echo $row['type']; ?>',
													  '<?php echo $row['item_number']; ?>',
													  '<?php echo $row['product_type']; ?>',
													  '<?php echo $row['product_status']; ?>',
														 {preloader: 'prl',
													 onUpdate: function(response,root){
													 document.getElementById('show_value').innerHTML=response;
													 document.getElementById('show_value').style.display='';
													 	$(function() {		
															$('#capacity_table')
															.tablesorter({widthFixed: true, widgets: ['zebra'],sortList: [[0,0]], headers: {0:{sorter: false},1:{sorter: false},2:{sorter: false},3:{sorter: false},4:{sorter: false},5:{sorter: false},6:{sorter: false},7:{sorter: false},8:{sorter: false}} } ) }); }});">
													<?php echo $row['product_name']; ?></a></td>
					<td><?php echo $row['item_number'];  ?></td>
					<td><?php echo $row['type'];   ?></td>
					<td><?php echo $row['product_type'];  ?></td>
					<td align="center">
					  <a href="javascript:void(0);" 
					       onclick="javascript:obj_product.add_clone('delete',
														  			 {preloader: 'prl'});
						             if(confirm('Are You Sure You Want to Delete this Product.')){
                                                
											 obj_product.checkStatus('<?php echo $row[product_id];?>',
																	 {preloader:'prl',
																	 onUpdate: function(response,root){
																	 var a = response;
													
											 if( a != ''){
													if(confirm('Would you like to remove this Product from all active Orders '+ a)){
													var name=prompt('You are about to remove this Product from all active Orders.... ARE YOU SURE YOU WANT TO DO THIS','yes/no');
													if (name!=null && name!=''){
													  if(name == 'yes'){
													  
														  obj_product.deletethis('<?php echo $row['product_id']; ?>',
																			     'assigned',
																 {preloader:'prl',
																 onUpdate: function(response,root){
																 document.getElementById('show_value').style.display='none';
														  obj_product.show_searchproductinventory({	
																 onUpdate: function(response,root){
																 document.getElementById('task_area').innerHTML=response;
																$('#search_table').tablesorter({widthFixed:true,
																widgets:['zebra'],sortList:[[0,0]],headers :{5:{sorter: false}}	});}});                                                       }});
													     }
													
													     else if(name == 'no')
														    { return false; }
													 } else { return false; }			
														}
													else{ return false; }
												} else {
													       obj_product.deletethis('<?php echo $row['product_id']; ?>',
																	              'unassigned',
																 {preloader:'prl',
																 onUpdate: function(response,root){
																 document.getElementById('show_value').style.display='none';
													obj_product.show_searchproductinventory('','','','','',
																							'<?php echo $row['product_status'];?>',{	
																 onUpdate: function(response,root){
																 document.getElementById('task_area').innerHTML=response;  
																$('#search_table').tablesorter({widthFixed:true,
																widgets:['zebra'],sortList:[[0,0]],headers :{5:{sorter: false}}	});}});                                                     }});
													    }
													   }});
                                            } else { return false; }"><img src="images/trash.gif" border="0" /></a>
					</td>
				</tr> 
				<?php } 
				}
			else
			{
			?>
			<tr><td colspan="6" align="center">No Record Found!!!!</td></tr>
			<?php
			}
			?>
			</tbody>
		</table>
		<?php
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
				
	}
	
	function checkExistance( $inventory_id='', $product_id='', $product='' ){
		ob_start();
		if( $product == '' ){
			$sql="SELECT inventory_id,product_id FROM ".erp_ASSIGN_INVENTORY." WHERE inventory_id='$inventory_id' and product_id='$product_id' and group_inventory_id = '0'";
			$result= $this->db->query($sql,_FILE_,_LINE_);
			if($this->db->num_rows($result) > 0)
			{ return 'c'; }
			
			$sql="SELECT status FROM ".TBL_INVENTORY_DETAILS." WHERE inventory_id=$inventory_id and status='inactive'";
			$result= $this->db->query($sql,_FILE_,_LINE_);
			if($this->db->num_rows($result) > 0)
			{ return 'd'; }
		} else {
			$sql="SELECT inventory_id,product_id FROM ".erp_ASSIGN_INVENTORY." WHERE inventory_id='$inventory_id' and product_id='$product_id' and group_inventory_id = '$product'";
			$result= $this->db->query($sql,_FILE_,_LINE_);
			if($this->db->num_rows($result) > 0)
			{ return 'c'; }
			
			$sql="SELECT status FROM ".TBL_INVENTORY_DETAILS." WHERE inventory_id=$inventory_id and status='inactive'";
			$result= $this->db->query($sql,_FILE_,_LINE_);
			if($this->db->num_rows($result) > 0)
			{ return 'd'; }
		
		}
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function ShowGroup( $group_id='' ){
	   ob_start(); ?>
	   <table class="table" width="100%">
		   <tr>
		   <?php 
		   $sql2 = "SELECT * FROM ". erp_PRODUCT . " WHERE product_id ='$group_id'";
		   $result2 = $this->db->query($sql2,__FILE__,__LINE__);
		   $row2 = $this->db->fetch_array($result2);
		   $data2 = $row2[product_name];
			$m2 = 0;
			foreach (count_chars($data2, 1) as $i2 => $val2) {
			   //echo "<br/>There were $val2 instance(s) of \"" , chr($i2) , "\" in the string.\n";
			   $m2 += $val2;
			   //echo 'indivisual-'.$m2;
			}
			//echo '<br/>total'.$m2.'<br/><br/>';
		    $total_width = ( $m2 * 1.3 );
		   ?>
		     <td bordercolor="#000000" width="<?php echo $total_width; ?>%" style="color:#009900;">
			     <a href="javascript:void(0);" 
									onclick="javascipt:obj_product.show_inve('<?php echo $row2['product_id']; ?>',
																			  '<?php echo $row2['type']; ?>',
																			  '<?php echo $row2['item_number']; ?>',
																			  '<?php echo $row2['product_type']; ?>',
																			  '<?php echo $row2['product_status']; ?>',
																			  '',
																			  'a',
																 {preloader: 'prl',
																 onUpdate: function(response,root){
																 document.getElementById('pro_content').innerHTML=response;
																 document.getElementById('pro_content').style.display='';
															$(function() {		
																$('#capacity_table')
																.tablesorter({widthFixed: true, widgets: ['zebra'],sortList: [[0,0]], headers: {0:{sorter: false},1:{sorter: false},2:{sorter: false},3:{sorter: false},4:{sorter: false},5:{sorter: false},6:{sorter: false},7:{sorter: false},8:{sorter: false}} } ) }); }});"> <?php echo $row2[product_name]; ?> </a>
			  </td>
		   
			   <?php 
			    $sql_sub = "SELECT * FROM ".erp_PRODUCT." WHERE group_product_id ='$group_id' and product_id <> '$group_id' ";
				$result_sub = $this->db->query($sql_sub,__FILE__,__LINE__);
				while( $row_sub = $this->db->fetch_array($result_sub) ){
				    $data = $row_sub[product_name];
					$m = 0;
					foreach (count_chars($data, 1) as $i => $val) {
					   //echo "<br/>There were $val instance(s) of \"" , chr($i) , "\" in the string.\n";
					   $m += $val;
					   //echo 'indivisual-'.$m;
					}
					//echo '<br/>total'.$m2.'<br/><br/>';
					$tot_width = ( $m * 1.3 );
				?>
			      <td bordercolor="#000000" width="<?php echo $tot_width; ?>%" style="color:#009900;">
					  <a href="javascript:void(0);" 
										onclick="javascipt:obj_product.show_inve(
														  '<?php echo $row_sub['product_id']; ?>',
														  '<?php echo $row_sub['type']; ?>',
														  '<?php echo $row_sub['item_number']; ?>',
														  '<?php echo $row_sub['product_type']; ?>',
														  '<?php echo $row_sub['product_status']; ?>',
														  '',
														  'a',
															 {preloader: 'prl',
														 onUpdate: function(response,root){
														 document.getElementById('pro_content').innerHTML=response;
														 document.getElementById('pro_content').style.display='';
														 document.getElementById('clone_selected_product').style.display='none';
															$(function() {		
																$('#capacity_table')
																.tablesorter({widthFixed: true, widgets: ['zebra'],sortList: [[0,0]], headers: {0:{sorter: false},1:{sorter: false},2:{sorter: false},3:{sorter: false},4:{sorter: false},5:{sorter: false},6:{sorter: false},7:{sorter: false},8:{sorter: false}} } ) }); }});"> <?php echo $row_sub[product_name]; ?> </a>
			      </td>
			      <td width="4px">
				    <a href="javascript:void(0);" 
						onclick="javascript:
									if(confirm('Are You Sure To Delete This Sub Product From The Product')){
										   obj_product.deleteProduct('<?php echo $row_sub[product_id]; ?>',
																     '',
																     '',
																     '<?php echo $row_sub[group_product_id]; ?>',
														{preloader:'prl',
														 onUpdate:function(response,root){
														obj_product.ShowGroup('<?php echo $group_id; ?>',
																			{preloader:'prl',
																			target:'group_product'});
																	}});
									   }"> <img src="images/trash.gif" border="0" /> </a>
				  </td>
		       <?php }?>
			   <td>
				   <div id="list_product">
					   <a href="javascript:void(0);" 
					      onclick="javascript:obj_product.add_item('local',
																 '<?php echo $group_id; ?>',
																 {preloader:'prl',
																 onUpdate:function(response,root){
																 document.getElementById('div_order').innerHTML=response;
																 document.getElementById('div_order').style.display='';
																 }});"> <img src="images/add.png" alt="Group Products" /> </a>
				   </div>
			   </td>
		   </tr>
	   </table>
	
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
	}
	
	function show_inve( $product_id='',$p_type='',$item_num='',$product_type='',$product_status='',$uses='',$check='',$z='',$product_old='' ) {
	       ob_start();
		    $sql1 = "SELECT * FROM ". erp_PRODUCT . " where product_id ='$product_id'";
			$result=$this->db->query($sql1,__FILE__,__LINE__);
			$row=$this->db->fetch_array($result);
			$str = array("'","&#039;");
			$replace = "&#096;";
			$pro_name = str_replace($str,$replace,$row[product_name]);
			
			if( $z == '' ){
			
			if($check==''){?>
			<div id="group_product">
			<?php echo $this->ShowGroup( $product_id ); ?>
			</div><?php }?>
			
			<div id="clone_selected_product"><?php echo $this->show_link($product_id,$p_type,$item_num,$product_type,$product_status);?></div>
			<div id="pro_content">
			<table class="table" width="100%">
			<tr>
			<th>Product :</th>
			<td>
			<div id="item_name_box_<?php echo $product_id; ?>">
			<?php echo $this->returnLink($pro_name,$product_id,'item_name_box_'.$product_id,'product_name'); ?>
			</div>
			<th>Product Type : </th>
			<td>
			<div id="product_type_<?php echo $product_id; ?>">
			<?php echo $this->returnLink($row['product_type'],$product_id,'product_type_'.$product_id,'product_type'); ?>
			</div>
			</td>
			</tr>
			<tr>
			<th>Item Number :</th>
			<td>
			<div id="item_num_<?php echo $product_id; ?>">
			<?php echo $this->returnLink($row['item_number'],$product_id,'item_num_'.$product_id,'item_number'); ?>
			</div>
			</td>
			<th>Product Status :</th>
			<td>
			<div id="product_status_<?php echo $product_id; ?>">
			<?php echo $this->returnLink($row['product_status'],$product_id,'product_status_'.$product_id,'product_status'); ?>
			</div>
			</td>
			</tr>
			<tr>
			<th>Type :</th>
			<td colspan="3">
			<div id="p_type_<?php echo $product_id; ?>">
			<?php echo $this->returnLink($row['type'],$product_id,'p_type_'.$product_id,'type'); ?>
			</div>
			</td>
			</tr>
			</table>
			<?php
			} else {
			$sql2 = "SELECT * FROM ". erp_PRODUCT . " where product_id ='$product_old'";
			$result2 = $this->db->query($sql2,__FILE__,__LINE__);
			$row2 = $this->db->fetch_array($result2); ?>
			<table class="table" width="100%">
			<tr>
			<th width="10%">Product :</th>
			<td width="10%"><input type="text" name="product_name" id="product_name" size="7" value="<?php echo $row2['product_name']; ?>" /></td>
			<th width="10%">Product Type :</th>
			<td width="10%">
				<select name="type_name" id="type_name">
					<option value="">-select-</option>
					<option value="Full Custom" <?php if('Full Custom'==$row2['product_type']) echo 'selected="selected"';?>>Full Custom</option>
					<option value="Custom" <?php if('Custom'==$row2['product_type']) echo 'selected="selected"';?>>Custom</option>
				</select>
			</td>
			</tr>
			<tr>
			<th width="10%">Item Number :</th>
			<td width="10%"><input type="text" name="item_num" id="item_num" size="7" value="<?php echo $row['item_number']; ?>" /></td>
			<th width="10%">Product Status :</th>
			<td width="10%">
				<select name="product_stats" id="product_stats">
					<option value="">-select-</option>
					<option value="Active" <?php if('Active'==$row2['product_status']) echo 'selected="selected"';?>>Active</option>
					<option value="Inactive" <?php if('Inactive'==$row2['product_status']) echo 'selected="selected"';?>>Inactive</option>
					<option value="Research" <?php if('Research'==$row2['product_status']) echo 'selected="selected"';?>>Research</option>
				</select>
			</td>
			</tr>
			<tr>
			<th width="10%">Type :</th>
			<td width="10%">
				<select name="type" id="type">
					<option value="">-select-</option>
					<option value="Cycling" <?php if('Cycling'==$row2['type']) echo 'selected="selected"';?>>Cycling</option>
					<option value="Tri/Run" <?php if('Tri/Run'==$row2['type']) echo 'selected="selected"';?>>Tri/Run</option>
					<option value="Jersey" <?php if('Jersey'==$row2['type']) echo 'selected="selected"';?>>Jersey</option>
					<option value="Nordic" <?php if('Nordic'==$row2['type']) echo 'selected="selected"';?>>Nordic</option>
					<option value="Alpine" <?php if('Alpine'==$row2['type']) echo 'selected="selected"';?>>Alpine</option>
					<option value="Wrestling" <?php if('Wrestling'==$row2['type']) echo 'selected="selected"';?>>Wrestling</option>
					<option value="PreCuts" <?php if('PreCuts'==$row2['type']) echo 'selected="selected"';?>>PreCuts</option>
				</select>
			</td>
			<th>&nbsp; </th>
			<td>&nbsp;</td>
			</tr>
			</table>
			<?php } ?>
			<table class="table" width="100%">
			<tr >
			<td>&nbsp;</td>
			<td>6-12</td>
			<td>13-24</td>
			<td>25-49</td>
			<td>50-74</td>
			<td>75+</td>
			<td>&nbsp;</td>
			</tr>
			<tr >
			<th>Base Price</th>
			<td width="55px">
			<input width="10px"type="text" name="qty_6_12" id="qty_6_12" value="<?php echo $row['quantity_6_12']; ?>"
			<?php if($z == ''){?> readonly="true" ondblclick="this.readOnly=false" <?php }?>
			onblur="javascript: if(this.value!=<?php echo $row['quantity_6_12']; ?>) {
									if(confirm('Are you sure to change this Quantity from <?php echo $row['quantity_6_12']; ?> to'+ this.value)){
											obj_product.updateField(this.value,
											'<?php echo $product_id; ?>',
											'quantity_6_12',
											{preloader:'prl'});
											<?php if($z == ''){?>this.readOnly=true; <?php }?>
									  } else {
											this.value=<?php echo $row['quantity_6_12']; ?>;
											<?php if($z == ''){?>this.readOnly=true;<?php }?>
										}
								   } else{<?php if($z == ''){?>this.readOnly=true; <?php }?>}"/>
			</td>
			<td width="55px">
			<input type="text" name="qty_13_24" id="qty_13_24" value="<?php echo $row['quantity_13_24']; ?>"
			<?php if($z == ''){?> readonly="true" ondblclick="this.readOnly=false" <?php }?>
			onblur="javascript: if(this.value!=<?php echo $row['quantity_13_24']; ?>) {
									if(confirm('Are you sure to change this Quantity from <?php echo $row['quantity_13_24']; ?> to'+ this.value)){
										obj_product.updateField(this.value,
										'<?php echo $product_id; ?>',
										'quantity_13_24',
										{preloader:'prl'});
										<?php if($z == ''){?> this.readOnly=true; <?php }?>
									 } else {
										this.value=<?php echo $row['quantity_13_24']; ?>;
										<?php if($z == ''){?>this.readOnly=true;<?php }?>
									}
								  } else{<?php if($z == ''){?> this.readOnly=true;<?php }?>}"/>
			</td>
			<td width="55px">
			<input type="text" name="qty_25_49" id="qty_25_49" value="<?php echo $row['quantity_25_49']; ?>"
			<?php if($z == ''){?> readonly="true" ondblclick="this.readOnly=false"<?php }?>
			onblur="javascript: if(this.value!=<?php echo $row['quantity_25_49']; ?>) {
									if(confirm('Are you sure to change this Quantity from <?php echo $row['quantity_25_49']; ?> to'+ this.value)){
										obj_product.updateField(this.value,
										'<?php echo $product_id; ?>',
										'quantity_25_49',
										{preloader:'prl'});
										<?php if($z == ''){?> this.readOnly=true;<?php }?>
									} else {
										this.value=<?php echo $row['quantity_25_49']; ?>;
										<?php if($z == ''){?> this.readOnly=true;<?php }?>
									}
								 } else{<?php if($z == ''){?> this.readOnly=true;<?php }?>}"/>
			</td>
			<td width="55px">
			<input type="text" name="qty_50_74" id="qty_50_74" value="<?php echo $row['quantity_50_74']; ?>"
			<?php if($z == ''){?> readonly="true" ondblclick="this.readOnly=false"<?php }?>
			onblur="javascript: if(this.value!=<?php echo $row['quantity_50_74']; ?>) {
									if(confirm('Are you sure to change this Quantity from <?php echo $row['quantity_50_74']; ?> to'+ this.value)) {
										obj_product.updateField(this.value,
										'<?php echo $product_id; ?>',
										'quantity_50_74',
										{preloader:'prl'});
										<?php if($z == ''){?> this.readOnly=true;<?php }?>
									}else {
										this.value=<?php echo $row['quantity_50_74']; ?>;
										<?php if($z == ''){?> this.readOnly=true;<?php }?>
									}
								 } else{<?php if($z == ''){?> this.readOnly=true;<?php }?>}"/>
			</td>
			<td width="55px">
			<input type="text" name="qty_75" id="qty_75" value="<?php echo $row['quantity_75']; ?>"
			<?php if($z == ''){?> readonly="true" ondblclick="this.readOnly=false"<?php }?>
			onblur="javascript: if(this.value!=<?php echo $row['quantity_75']; ?>) {
									if(confirm('Are you sure to change this Quantity from <?php echo $row['quantity_75']; ?> to'+ this.value)) {
										obj_product.updateField(this.value,
										'<?php echo $product_id; ?>',
										'quantity_75',
										{preloader:'prl'});
										<?php if($z == ''){?> this.readOnly=true;<?php }?>
									}else {
										this.value=<?php echo $row['quantity_75']; ?>;
										<?php if($z == ''){?> this.readOnly=true;<?php }?>
									}
								 }else{<?php if($z == ''){?> this.readOnly=true;<?php }?>}"/>
			</td>
			</tr>
			<tr>
			<td>&nbsp;</td>
			<td>XS</td>
			<td>S</td>
			<td>M</td>
			<td>L</td>
			<td>XL</td>
			<td>&nbsp;</td>
			</tr>
			<tr>
			<td>&nbsp;</td>
			<td>
			$<input style="width:40px" type="text" name="size_xs" id="size_xs" value="<?php echo $row['size_xs']; ?>"
			<?php if($z == ''){?> readonly="true" ondblclick="this.readOnly=false"<?php } ?>
			onblur="javascript: if(this.value!=<?php echo $row['size_xs']; ?>) {
									if(confirm('Are you sure to change this size XS from <?php echo $row['size_xs']; ?> to'+ this.value)) {
										obj_product.updateField(this.value,
										'<?php echo $product_id; ?>',
										'size_xs',
										{preloader:'prl'});
										<?php if($z == ''){?> this.readOnly=true;<?php } ?>
									}else {
										this.value=<?php echo $row['size_xs']; ?>;
										<?php if($z == ''){?> this.readOnly=true;<?php } ?>
									}
								 }else{<?php if($z == ''){?> this.readOnly=true;<?php } ?>}"/>
			</td>
			<td>
			$<input style="width:40px" type="text" name="size_s" id="size_s" value="<?php echo $row['size_s']; ?>"
			<?php if($z == ''){?> readonly="true" ondblclick="this.readOnly=false"<?php } ?>
			onblur="javascript: if(this.value!=<?php echo $row['size_s']; ?>) {
									if(confirm('Are you sure to change this size S from <?php echo $row['size_s']; ?> to'+ this.value)) {
										obj_product.updateField(this.value,
										'<?php echo $product_id; ?>',
										'size_s',
										{preloader:'prl'});
										<?php if($z == ''){?> this.readOnly=true;<?php } ?>
									}else {
										this.value=<?php echo $row['size_s']; ?>;
										<?php if($z == ''){?> this.readOnly=true;<?php } ?>
									}
								 }else{<?php if($z == ''){?> this.readOnly=true;<?php } ?>}"/>
			</td>
			<td >
			$<input style="width:40px" type="text" name="size_m" id="size_m" value="<?php echo $row['size_m']; ?>"
			<?php if($z == ''){?> readonly="true" ondblclick="this.readOnly=false"<?php } ?>
			onblur="javascript: if(this.value!=<?php echo $row['size_m']; ?>) {
									if(confirm('Are you sure to change this size M from <?php echo $row['size_m']; ?> to'+ this.value)) {
										obj_product.updateField(this.value,
										'<?php echo $product_id; ?>',
										'size_m',
										{preloader:'prl'});
										<?php if($z == ''){?> this.readOnly=true;<?php } ?>
									} else {
										this.value=<?php echo $row['size_m']; ?>;
										<?php if($z == ''){?> this.readOnly=true;<?php } ?>
									}
								 }else{<?php if($z == ''){?> this.readOnly=true;<?php } ?>}"/>
			</td>
			<td >
			$<input style="width:40px" type="text" name="size_l" id="size_l" value="<?php echo $row['size_l']; ?>"
			<?php if($z == ''){?> readonly="true" ondblclick="this.readOnly=false"<?php } ?>
			onblur="javascript: if(this.value!=<?php echo $row['size_l']; ?>) {
									if(confirm('Are you sure to change this size L from <?php echo $row['size_l']; ?> to'+ this.value)) {
										obj_product.updateField(this.value,
										'<?php echo $product_id; ?>',
										'size_l',
										{preloader:'prl'});
										<?php if($z == ''){?> this.readOnly=true; <?php } ?>
									} else {
										this.value=<?php echo $row['size_l']; ?>;
										<?php if($z == ''){?> this.readOnly=true;<?php } ?>
									}
								 }else{<?php if($z == ''){?> this.readOnly=true; <?php } ?>}"/>
			</td>
			<td>
			$<input style="width:40px" type="text" name="size_xl" id="size_xl" value="<?php echo $row['size_xl']; ?>"
			<?php if($z == ''){?> readonly="true" ondblclick="this.readOnly=false"<?php } ?>
			onblur="javascript: if(this.value!=<?php echo $row['size_xl']; ?>) {
									if(confirm('Are you sure to change this size XL from <?php echo $row['size_xl']; ?> to'+ this.value)){
										obj_product.updateField(this.value,
										'<?php echo $product_id; ?>',
										'size_xl',
										{preloader:'prl'});
										<?php if($z == ''){?> this.readOnly=true;<?php } ?>
									}else {
										this.value=<?php echo $row['size_xl']; ?>;
										<?php if($z == ''){?> this.readOnly=true;<?php } ?>
									}
								 }else{<?php if($z == ''){?> this.readOnly=true; <?php } ?>}"/>
			</td>
			<td>&nbsp;</td>
			</tr>
			<tr>
			<td>&nbsp;</td>
			<td>2x</td>
			<td>3x</td>
			<td colspan="4">4x</td>
			</tr>
			<tr>
			<td>&nbsp;</td>
			<td>
			$<input style="width:40px" type="text" name="size_2x" id="size_2x" value="<?php echo $row['size_2x']; ?>"
			<?php if($z == ''){?> readonly="true" ondblclick="this.readOnly=false"<?php } ?>
			onblur="javascript: if(this.value!=<?php echo $row['size_2x']; ?>) {
									if(confirm('Are you sure to change this size 2x from <?php echo $row['size_2x']; ?> to'+ this.value)) {
										obj_product.updateField(this.value,
										'<?php echo $product_id; ?>',
										'size_2x',
										{preloader:'prl'});
										<?php if($z == ''){?> this.readOnly=true;<?php } ?>
									}else {
										this.value=<?php echo $row['size_2x']; ?>;
										<?php if($z == ''){?> this.readOnly=true;<?php } ?>
									}
								 }else{<?php if($z == ''){?> this.readOnly=true;<?php } ?>}"/>
			</td>
			<td>
			$<input style="width:40px" type="text" name="size_3x" id="size_3x" value="<?php echo $row['size_3x']; ?>"
			<?php if($z == ''){?> readonly="true" ondblclick="this.readOnly=false"<?php } ?>
			onblur="javascript: if(this.value!=<?php echo $row['size_3x']; ?>) {
									if(confirm('Are you sure to change this size 3x from <?php echo $row['size_3x']; ?> to'+ this.value)) {
										obj_product.updateField(this.value,
										'<?php echo $product_id; ?>',
										'size_3x',
										{preloader:'prl'});
										<?php if($z == ''){?> this.readOnly=true;<?php } ?>
									}else {
										this.value=<?php echo $row['size_3x']; ?>;
										<?php if($z == ''){?> this.readOnly=true;<?php } ?>
									}
								 }else{<?php if($z == ''){?> this.readOnly=true;<?php } ?>}"/>
			</td>
			<td>
			$<input style="width:40px" type="text" name="size_4x" id="size_4x" value="<?php echo $row['size_4x']; ?>"
			<?php if($z == ''){?> readonly="true" ondblclick="this.readOnly=false"<?php } ?>
			onblur="javascript: if(this.value!=<?php echo $row['size_4x']; ?>) {
									if(confirm('Are you sure to change this size 4x from <?php echo $row['size_4x']; ?> to'+ this.value)){
										obj_product.updateField(this.value,
										'<?php echo $product_id; ?>',
										'size_4x',
										{preloader:'prl'});
										<?php if($z == ''){?>  this.readOnly=true;<?php } ?>
									}else {
										this.value=<?php echo $row['size_4x']; ?>;
										<?php if($z == ''){?> this.readOnly=true;<?php } ?>
									}
								 }else{<?php if($z == ''){?>  this.readOnly=true; <?php } ?>}"/>
			</td>
			<td colspan="4"></td>
			</tr>
			</table>
			
			<div>
				<a href="javascript:void(0);"
				onclick="javascript: obj_product.showcapacity('<?php echo $product_id; ?>',
															  '<?php echo $z; ?>',
															  {preloader:'prl', onUpdate: function(response,root)
															  {document.getElementById('showTable').innerHTML=response;
															  $('#capacity_table').tablesorter({widthFixed:true,
															  widgets:['zebra'],sortList:[[0,0]], headers: {0:{sorter: false},
															  1:{sorter: false},2:{sorter: false},3:{sorter: false},4:{sorter: false},5:{sorter: false},6:{sorter: false},7:{sorter: false},8:{sorter: false}}})}});">Capacity </a>&nbsp;
				
				<a href="javascript:void(0);"
				onclick="javascript: obj_product.showConsumable('<?php echo $product_id; ?>',
																'<?php echo $z; ?>',
																{preloader:'prl', onUpdate: function(response,root)
																{document.getElementById('showTable').innerHTML=response;
																$('#capacity_table').tablesorter({widthFixed:true,
																widgets:['zebra'],sortList:[[0,0]] })}});">Consumable </a>&nbsp;
				
				<span id="Lock" onclick="if(this.innerHTML=='Locked') this.innerHTML ='Unlocked';
											else this.innerHTML ='Locked';"><?php echo 'Locked';?>
					
				</span>
			</div>
			
			<div id="showTable" style="overflow-y:scroll; height:700px;"><?php echo $this->showcapacity($product_id,$z); ?></div> </div> 
			<?php
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}   //////end of function show_inve
	
	function showcapacity( $product_id='', $z='' ){
		ob_start();
		$sql = "Select * from ".erp_PRODUCT." where product_id = '$product_id'";
		$result=$this->db->query($sql,__FILE__,__LINE__);
		$row=$this->db->fetch_array($result);?>
		<h3>Capacity</h3>
		<table id="capacity_table" class="event_form small_text" width="100%">
			<thead>
			   <tr>
					<th width="20%" colspan="2">Material</th>
					<th width="10%"><img src="images/cost_increase.JPG" /></th>
					<th width="10%"><img src="images/csr.JPG" /></th>
					<th width="10%"><img src="images/art.JPG" /></th>
					<th width="10%"><img src="images/print.JPG" /></th>
					<th width="10%"><img src="images/dye_sub.JPG" /></th>
					<th width="10%"><img src="images/cut.JPG" /></th>
					<th width="10%"><img src="images/sew.JPG" /></th>
					<th width="10%"><img src="images/shipping.JPG" /></th>
			   </tr>
			</thead>
			<tbody>
			  	<tr>
                    <td>&nbsp;</td>
					<td width="20%">Order Specific</td>
					<td width="10%">
						<input type="text" name="order_cost_increase" id="order_cost_increase" value="<?php echo $row['order_cost_increase']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?> size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['order_cost_increase']; ?>) {
												
							                       obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'order_cost_increase',
																	   {preloader:'prl'});
																	   <?php if( $z == '' ){ ?> this.readOnly=true;  <?php } ?>
													}
													else{<?php if( $z == '' ){?> this.readOnly=true; <?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="order_csr" id="order_csr" value="<?php echo $row['order_csr']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?> size="5" 
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['order_csr']; ?>) {
												
												obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'order_csr',
																	   {preloader:'prl'});
																	   <?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="order_art" id="order_art" value="<?php echo $row['order_art']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?> size="5" 
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['order_art']; ?>) {
												
												obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'order_art',
																	   {preloader:'prl'});
																	   <?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>
												  }
													else{<?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="order_print" id="order_print" value="<?php echo $row['order_print']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?> size="5" 
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['order_print']; ?>) {
												
												obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'order_print',
																	   {preloader:'prl'});
																	  <?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="order_dye_sub" id="order_dye_sub" value="<?php echo $row['order_dye_sub']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?> size="5" 
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['order_dye_sub']; ?>) {
												
												obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'order_dye_sub',
																	   {preloader:'prl'});
																	  <?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="order_cut" id="order_cut" value="<?php echo $row['order_cut']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false"  <?php } ?> size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['order_cut']; ?>) {
												
												obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'order_cut',
																	   {preloader:'prl'});
																	  <?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="order_sew" id="order_sew" value="<?php echo $row['order_sew']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?> size="5" 
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['order_sew']; ?>) {
												
												obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'order_sew',
																	   {preloader:'prl'});
																	 <?php if( $z == '' ){ ?>  this.readOnly=true; <?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="order_shipping" id="order_shipping" value="<?php echo $row['order_shipping']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?> size="5" 
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['order_shipping']; ?>) {
												
												obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'order_shipping',
																	   {preloader:'prl'});
																	 <?php if( $z == '' ){ ?>  this.readOnly=true; <?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
			  	</tr>
			  	<tr>
                    <td>&nbsp;</td>
					<td width="20%">Per Item</td>
					<td width="10%">
					<input type="text" name="per_item_cost_increase" id="per_item_cost_increase" value="<?php echo $row['per_item_cost_increase'];?>"
							 <?php if( $z == '' ){ ?>readonly="true" 
							 ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>  size="5"


							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['per_item_cost_increase']; ?>) {
												
												 obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'per_item_cost_increase',
																	   {preloader:'prl'});
																	  <?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>
													}
													else{ <?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="per_item_csr" id="per_item_csr" value="<?php echo $row['per_item_csr']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?> size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['per_item_csr']; ?>) {
												
												  obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'per_item_csr',
																	   {preloader:'prl'});
																	  <?php if( $z == '' ){ ?> this.readOnly=true;<?php } ?>
													}
													else{ <?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="per_item_art" id="per_item_art" value="<?php echo $row['per_item_art']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['per_item_art']; ?>) {
												
												  obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'per_item_art',
																	   {preloader:'prl'});
																	  <?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>
													}
													else{ <?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="per_item_print" id="per_item_print" value="<?php echo $row['per_item_print']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false"<?php } ?>  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['per_item_print']; ?>) {
												
												  obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'per_item_print',
																	   {preloader:'prl'});
																	  <?php if( $z == '' ){ ?>  this.readOnly=true;<?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true;<?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="per_item_dye_sub" id="per_item_dye_sub" value="<?php echo $row['per_item_dye_sub']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?> size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){ 
												if(this.value!=<?php echo $row['per_item_dye_sub']; ?>) {
												
												 obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'per_item_dye_sub',
																	   {preloader:'prl'});
																	  <?php if( $z == '' ){ ?>  this.readOnly=true;<?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true;<?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="per_item_cut" id="per_item_cut" value="<?php echo $row['per_item_cut']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?> size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['per_item_cut']; ?>) {
												
												  obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'per_item_cut',
																	   {preloader:'prl'});
																	  <?php if( $z == '' ){ ?>  this.readOnly=true;<?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true;<?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="per_item_sew" id="per_item_sew" value="<?php echo $row['per_item_sew']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false"<?php } ?>  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['per_item_sew']; ?>) {
												
												 obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'per_item_sew',
																	   {preloader:'prl'});
																	 <?php if( $z == '' ){ ?>   this.readOnly=true;<?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true;<?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="per_item_shipping" id="per_item_shipping" value="<?php echo $row['per_item_shipping']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?> size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['per_item_shipping']; ?>) {
												
												obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'per_item_shipping',
																	   {preloader:'prl'});
																	  <?php if( $z == '' ){ ?>  this.readOnly=true;<?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
			  	</tr>
			 	<tr>
                    <td>&nbsp;</td>
					<td width="20%">Per Size</td>
					<td width="10%">
					<input type="text" name="per_size_cost_increase" id="per_size_cost_increase" value="<?php echo $row['per_size_cost_increase'];?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false"  <?php } ?> size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['per_size_cost_increase']; ?>) {
												
												 obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'per_size_cost_increase',
																	   {preloader:'prl'});
																	  <?php if( $z == '' ){ ?>  this.readOnly=true; <?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="per_size_csr" id="per_size_csr" value="<?php echo $row['per_size_csr']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?> size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['per_size_csr']; ?>) { 
												
												 obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'per_size_csr',
																	   {preloader:'prl'});
																	  <?php if( $z == '' ){ ?>  this.readOnly=true; <?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="per_size_art" id="per_size_art" value="<?php echo $row['per_size_art']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>  size="5"
							onblur="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked'){
												 if(this.value!=<?php echo $row['per_size_art']; ?>) { 
												
												  obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'per_size_art',
																	   {preloader:'prl'});
																	 <?php if( $z == '' ){ ?>   this.readOnly=true; <?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="per_size_print" id="per_size_print" value="<?php echo $row['per_size_print']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?> size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['per_size_print']; ?>) {  
												
												 obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'per_size_print',
																	   {preloader:'prl'});
																	  <?php if( $z == '' ){ ?>  this.readOnly=true; <?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="per_size_dye_sub" id="per_size_dye_sub" value="<?php echo $row['per_size_dye_sub']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?> size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['per_size_dye_sub']; ?>) {  
												
												 obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'per_size_dye_sub',
																	   {preloader:'prl'});
																	  <?php if( $z == '' ){ ?>  this.readOnly=true; <?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="per_size_cut" id="per_size_cut" value="<?php echo $row['per_size_cut']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?> size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['per_size_cut']; ?>) { 
												
												  obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'per_size_cut',
																	   {preloader:'prl'});
																	 <?php if( $z == '' ){ ?>   this.readOnly=true; <?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="per_size_sew" id="per_size_sew" value="<?php echo $row['per_size_sew']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false"  <?php } ?> size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['per_size_sew']; ?>) {  
												
												 obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'per_size_sew',
																	   {preloader:'prl'});
																	 <?php if( $z == '' ){ ?>   this.readOnly=true; <?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true; <?php } ?>}}"/>
					</td>
					<td width="10%">
						<input type="text" name="per_size_shipping" id="per_size_shipping" value="<?php echo $row['per_size_shipping']; ?>"
							<?php if( $z == '' ){ ?> readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row['per_size_shipping']; ?>) {    
												
												 obj_product.updateField(this.value,
																	  '<?php echo $product_id; ?>',
																	  'per_size_shipping',
																	   {preloader:'prl'});
																	<?php if( $z == '' ){ ?>    this.readOnly=true; <?php } ?>
													}
													else{<?php if( $z == '' ){ ?> this.readOnly=true;<?php } ?> }}"/>
					</td>
			  	</tr>
			  	<?php 
			  	$sql_inve= "select * from ".erp_ASSIGN_INVENTORY." where product_id = '$row[product_id]' and group_inventory_id = '0'";
				$result_inve=$this->db->query($sql_inve,_FILE_,_LINE_);
				while ($row_inve=$this->db->fetch_array($result_inve)){?>
				<tr>
				<?php if( $z == '' ){ ?>
                    <td><a href="javascript:void(0);" 
					       onclick="javascript: if(confirm('Are you sure you want to delete this inventory.')){
                                                  obj_product.deleteProduct('<?php echo $row_inve[product_id]; ?>',
                                                                            '<?php echo $row_inve[inventory_id]; ?>',
					                             { preloader:'prl', onUpdate: function(response,root){ 
                                                  obj_product.showcapacity('<?php echo $row[product_id]; ?>',
											     { onUpdate: function(response,root){
												  document.getElementById('showTable').innerHTML=response;
												  }}); }}); }"><img src="images/trash.gif" border="0" /></a>
					</td>
				 <?php } ?>
				 <?php if( $z != '' ){ ?>
				 	<td>&nbsp;</td><?php } ?>
					<td width="20%"><?php echo $row_inve['name']; ?></td>
					<td width="10%">
					$<input style="width:40px" type="text" name="inventory_cost_increase" id="inventory_cost_increase" 
					<?php if( $z == '' ){ ?> readonly="true" 
					ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>
							value="<?php echo $row_inve['inventory_cost_increase']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['inventory_cost_increase']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'inventory_cost_increase','<?php echo $row_inve["product_id"]; ?>',
																				   {preloader:'prl'});
																				  <?php if( $z == '' ){ ?> this.readOnly=true;<?php } ?>
													}
													else{<?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="inventory_csr" id="inventory_csr" 
					<?php if( $z == '' ){ ?> readonly="true" 
					ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>
							value="<?php echo $row_inve['inventory_csr']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['inventory_csr']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'inventory_csr', '<?php echo $row_inve["product_id"]; ?>',
																				   {preloader:'prl'});
																				  <?php if( $z == '' ){ ?> this.readOnly=true;<?php } ?>
													}
													else{<?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="inventory_art" id="inventory_art" 
					<?php if( $z == '' ){ ?> readonly="true" 
					ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>
							value="<?php echo $row_inve['inventory_art']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['inventory_art']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'inventory_art','<?php echo $row_inve["product_id"]; ?>',
																				   {preloader:'prl'});
																				  <?php if( $z == '' ){ ?> this.readOnly=true;<?php } ?>
													}
													else{ <?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="inventory_print" id="inventory_print" 
					<?php if( $z == '' ){ ?>readonly="true" 
					ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>
							value="<?php echo $row_inve['inventory_print']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['inventory_print']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'inventory_print','<?php echo $row_inve["product_id"]; ?>',
																				   {preloader:'prl'});
																				 <?php if( $z == '' ){ ?>  this.readOnly=true;<?php } ?>
													}
													else{<?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="inventory_dye_sub" id="inventory_dye_sub" 
					<?php if( $z == '' ){ ?>readonly="true" 
					ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>
							value="<?php echo $row_inve['inventory_dye_sub']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['inventory_dye_sub']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'inventory_dye_sub','<?php echo $row_inve["product_id"]; ?>',
																				   {preloader:'prl'});
																				  <?php if( $z == '' ){ ?> this.readOnly=true;<?php } ?>
													}
													else{<?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="inventory_cut" id="inventory_cut" 
					<?php if( $z == '' ){ ?>readonly="true" 
					ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>
							value="<?php echo $row_inve['inventory_cut']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['inventory_cut']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'inventory_cut','<?php echo $row_inve["product_id"]; ?>',
																				   {preloader:'prl'});
																				<?php if( $z == '' ){ ?>   this.readOnly=true;<?php } ?>
													}
													else{<?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="inventory_sew" id="inventory_sew"
					<?php if( $z == '' ){ ?> readonly="true" 
					ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>
							value="<?php echo $row_inve['inventory_sew']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['inventory_sew']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'inventory_sew','<?php echo $row_inve["product_id"]; ?>',
																				   {preloader:'prl'});
																				<?php if( $z == '' ){ ?>   this.readOnly=true;<?php } ?>
													}
													else{<?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>}}"/>
					</td>					
					<td width="10%">
					<input type="text" name="inventory_shipping" id="inventory_shipping" 
					<?php if( $z == '' ){ ?>readonly="true" 
					ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>
							value="<?php echo $row_inve['inventory_shipping']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['inventory_shipping']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'inventory_shipping','<?php echo $row_inve["product_id"]; ?>',
																				   {preloader:'prl'});
																				 <?php if( $z == '' ){ ?>  this.readOnly=true;<?php } ?>
													}
													else{<?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>}}"/>
					</td>											
				</tr><?php }?>
		   </tbody>
	     </table>
		
			 <div id="groupinventory">
			 <?php
			   $sql1 = "SELECT group_name,product_group FROM ". erp_GROUP_INVENTORY . " WHERE group_id ='$row[product_id]'";
			   $result1 = $this->db->query($sql1,__FILE__,__LINE__);
			   if( $this->db->num_rows($result1) > 0 ){
			   echo $this->GroupInventory( 'inventory', $row[product_id] ,'','',$z); } ?>
			 </div>
		 
		 <table class="table" width="100%">
		        <tr>
				<?php if( $z == '' ){ ?>
				   <td colspan="8" align="right">
				     <a href="javascript:void(0);"
					    onclick="javascript:obj_product.GroupInventory('local',
						                                             '<?php echo $row[product_id]; ?>',
																	 '<?php echo $row[group_name]; ?>',
																	 '<?php echo $row[product_group]; ?>',
					 												{preloader:'prl',
                                                                     onUpdate:function(response,root){
																     document.getElementById('div_order').innerHTML=response;
																     document.getElementById('div_order').style.display='';
																	}});
																	">Group</a>
				   </td>
				 <?php } ?>
				</tr>
				<tr>
				   <td width="100%" colspan="9">
					 <div id="product_box_<?php echo ($i+1); ?>"></div>
				   </td>
			    </tr>
				<tr>
				<?php if( $z == '' ){ ?>
			       <td width="30%">
					 <a href="javascript:void(0);" id="hideanchor"
                        onclick="javascript: obj_product.addProduct('<?php echo ($i+1);?>',
																  '<?php echo $row[product_id]; ?>',
                                                      { preloader:'prl',
                                                        onUpdate:function(response,root){
													   document.getElementById('div_order').innerHTML=response;
													   document.getElementById('div_order').style.display='';
													   
													   autosuggest(); }}); "><p id="hide">Add</p></a>
					</td>
				<?php } ?>
					<?php if( $z != '' ){ ?>
					<td>&nbsp;</td>
					<?php } ?>
					<td width="10%"><img src="images/minutes.JPG" /></td>
					<td width="10%"><img src="images/minutes.JPG" /></td>
					<td width="10%"><img src="images/inch_min.JPG" /></td>
					<td width="10%"><img src="images/inch_min.JPG" /></td>
					<td width="10%"><img src="images/minutes.JPG" /></td>
					<td width="10%"><img src="images/minutes.JPG" /></td>
					<td width="10%"><img src="images/minutes.JPG" /></td>
			  </tr>
			  <?php if( $z != '' ){ ?>
			  <tr>
			        <td><a href="javascript:void(0);"
					              onclick="javascript:obj_product.add_clone('update',
								  											'<?php echo $product_id;?>',
																			document.getElementById('type').value,
																			document.getElementById('type_name').value,
																			document.getElementById('product_stats').value,
																			document.getElementById('product_name').value,
																			document.getElementById('item_num').value,
																				{preloader: 'prl'});
								  
								  					  obj_product.add_clone('check',
								  											'<?php echo $product_id;?>',
																			document.getElementById('type').value,
																			document.getElementById('type_name').value,
																			document.getElementById('product_stats').value,
																			document.getElementById('product_name').value,
																			document.getElementById('item_num').value,
																			{preloader: 'prl',
																			onUpdate: function(response,root){
																			if(response=='a'){
																				alert('rename the clone');
																				return false;
																			}
																			else{
															obj_product.show_searchproductinventory({	
																									 onUpdate: function(response,root){
																									 document.getElementById('task_area').innerHTML=response;  
																									 $('#search_table').tablesorter({widthFixed:true,
																									 widgets:['zebra'],sortList:[[0,0]],headers :{5:{sorter: false}}	});}});
												        obj_product.show_inve('<?php echo $product_id; ?>',
																	  		  {preloader: 'prl',
																			 onUpdate: function(response,root){
																			 document.getElementById('show_value').innerHTML=response;
																			 document.getElementById('show_value').style.display='';
																			$(function() {		
																				$('#capacity_table')
																				.tablesorter({widthFixed: true, widgets: ['zebra'],sortList: [[0,0]], headers: {0:{sorter: false},1:{sorter: false},2:{sorter: false},3:{sorter: false},4:{sorter: false},5:{sorter: false},6:{sorter: false},7:{sorter: false},8:{sorter: false}} } ) }); }});
																			  }}}); "> clone </a>
					</td>
			  </tr>
			 <?php } ?>
		</table>
		<?php 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;					
	}
	
	function GroupInventory( $runat='', $product='', $name='', $product_group='',$z='' ){
	   ob_start();
	   switch( $runat ){
	   case 'local' : ?>
	   <div class="prl">&nbsp;</div>
			  <div id="lightbox" style="margin-left:50px;">
				 <div style="background-color:#ADC2EB; max-width:280px; min-width:250px;" align="left" class="ajax_heading">
				  <div id="TB_ajaxWindowTitle">Creat Group</div>
				  <div id="TB_closeAjaxWindow">
					<a href="javascript:void(0)" onclick="javascript: document.getElementById('div_order').style.display='none';">
					<img border="0" src="images/close.gif" alt="close" /></a>
				  </div>
				</div>
			  <div class="white_content" style="max-width:280px; min-width:250px;"> 
			  <div style="padding:20px;" class="form_main">
			  <table class="table">
				  <tr>
				    <td>Name: </td>
					<td><input type="text" name="Set" id="Set" /></td>
				  </tr>
				  <tr>
				    <td>Product Group: </td>
					<td>
					<?php
					    $sql = "SELECT type_id, type_name FROM ".erp_ITEM_TYPE." WHERE type_id <> '2'";
						$result = $this->db->query($sql,__FILE__,__LINE__); ?>
						  <select name="product_group" id="product_group" style="width:auto;">
							<option value="">-Select-</option>
							<?php while( $row = $this->db->fetch_array($result) ){
							$sql1 = "SELECT product_group FROM ".erp_GROUP_INVENTORY." WHERE product_group = $row[type_id] and group_id = '$product'";
						    $result1 = $this->db->query($sql1,__FILE__,__LINE__);
							if( $this->db->num_rows($result1) == 0 ){
							?>
							<option value="<?php echo $row[type_id]; ?>"><?php echo $row[type_name]; ?></option>
							<?php } } ?>
						  </select>
					</td>
				  </tr>
				  <tr>
				    <td colspan="2" align="center">
					   <input type="button" name="group_inventory" id="group_inventory" value="Add" style="width:52px;"
					           onclick="javascript:obj_product.GroupInventory('server',
							   											      '<?php echo $product; ?>',
																			  document.getElementById('Set').value,
																			  document.getElementById('product_group').value,
																		{preloader:'prl',
																		onUpdate: function(response,root){
																		document.getElementById('div_order').innerHTML = response;
																		document.getElementById('div_order').style.display = 'none';
																			}});
																	obj_product.GroupInventory('inventory',
																							   '<?php echo $product; ?>','','',
																							   '<?php echo $z;?>',
																	{preloader:'prl',
																	target:'groupinventory'}); "/>
					</td>
				  </tr>
			  </table>
			  </div></div></div>
	  <?php
	      break;
		case 'server' :
		   $insert_sql_array = array();
		   $insert_sql_array[group_id] = $product;
		   $insert_sql_array[group_name] = $name;
		   $insert_sql_array[product_group] = $product_group;
		
		   $this->db->insert(erp_GROUP_INVENTORY,$insert_sql_array);
		
		  break;
		case 'inventory' : ?>
		   <script>
				function autogroup(type_id){
					$(document).ready(function() {        
					  $("#add_group_product").fcbkcomplete({
						json_url: "useslist.php?type=product_group&type_id="+type_id,
						cache: false,
						filter_hide: true,
						filter_selected: true,
						maxitems: 1,
					  });		 
					});	
				}
		   </script>
		   <table id="capacity_table" class="event_form small_text" width="100%">
			<?php
			 $sql1 = "SELECT group_name, product_group,inventory_group_id FROM ". erp_GROUP_INVENTORY . " WHERE group_id ='$product'";
		     $result1 = $this->db->query($sql1,__FILE__,__LINE__);
		     while( $row1 = $this->db->fetch_array($result1) ){
			 
			 $sql2 = "SELECT type_name FROM ".erp_ITEM_TYPE." WHERE type_id = '$row1[product_group]'";
			 $result2 = $this->db->query($sql2,__FILE__,__LINE__);
			 $row2 = $this->db->fetch_array($result2);
		    ?>
			   <tr>
			        <td colspan="2">&nbsp;</td>
					<th colspan="3" style="color:#009933; background-color:#FFFFFF;" align="right">Name : <?php echo $row1[group_name]; ?></th>
					
					<th colspan="3" style="color:#009933; background-color:#FFFFFF;" align="left">Product Group : <?php echo $row2[type_name]; ?></th>
					<td colspan="2" align="left">
					<?php if($z == ''){?>
					<a href="javascript:void(0);" 
					      onclick="javascript: if(confirm('Are You Sure To Delete This Inventory Group')){
                                                   obj_product.deletethis('<?php echo $product; ?>',
												                          'group',
						                                                  '<?php echo $row1[inventory_group_id]; ?>',
															   {preloader:'prl',
															   onUpdate: function(response,root){ 
															   obj_product.GroupInventory('inventory',
																						  '<?php echo $product; ?>',
																			   {preloader:'prl',
																			   target:'groupinventory'});
					                                }});
												  }"> <img src="images/trash.gif" border="0" /> </a>
					<?php } ?>
					</td>
			   </tr>
			    <?php if($z == ''){?>
			   <tr>
			    <td colspan="10">
					  <a href="javascript:void(0);" id="hideanchors"
                        onclick="javascript: obj_product.addProduct('',
																    '<?php echo $product; ?>',
																    '<?php echo $row1[inventory_group_id]; ?>',
                                                      { preloader:'prl',
                                                        onUpdate:function(response,root){
													   document.getElementById('div_order').innerHTML=response;
													   document.getElementById('div_order').style.display='';
													   
													   autosuggest();
													   autogroup('<?php echo $row1[product_group]; ?>');
													    }}); "><p id="hide">Add In Group</p></a>
					</td>
			   </tr>
			  	<?php  }
			  	$sql_inve= "select * from ".erp_ASSIGN_INVENTORY." where product_id = '$product' and group_inventory_id = '$row1[inventory_group_id]'";
				$result_inve=$this->db->query($sql_inve,_FILE_,_LINE_);
				while ($row_inve=$this->db->fetch_array($result_inve)){?>
				<tr>
				<?php if($z==''){?>
                    <td><a href="javascript:void(0);" 
					       onclick="javascript: if(confirm('Are You Sure You Want To Delete This Inventory From The Group.')){
                                                
                                                  obj_product.deleteProduct('<?php echo $row_inve[product_id]; ?>',
                                                                            '<?php echo $row_inve[inventory_id]; ?>',
																		    '<?php echo $row_inve[group_inventory_id]; ?>',
					                            { preloader:'prl', onUpdate: function(response,root){ 
                                                  obj_product.GroupInventory('inventory',
												                             '<?php echo $product; ?>',
											    { onUpdate: function(response,root){
												  document.getElementById('groupinventory').innerHTML=response;
												  }}); }}); }"><img src="images/trash.gif" border="0" /></a>
					</td>
					<?php }?>
					<td width="20%"><?php echo $row_inve['name']; ?></td>
					<td width="20%">
					$<input style="width:40px" type="text" name="inventory_cost_increase" id="inventory_cost_increase" 
					<?php if($z == ''){ ?>readonly="true" 
						ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>
							value="<?php echo $row_inve['inventory_cost_increase']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['inventory_cost_increase']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'inventory_cost_increase','<?php echo $row_inve["product_id"]; ?>',
																				   {preloader:'prl'});
																				   <?php if($z == ''){ ?> this.readOnly=true;<?php } ?>
													}
													else{<?php if($z == ''){ ?> this.readOnly=true;<?php } ?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="inventory_csr" id="inventory_csr" 
					<?php if($z == ''){ ?> readonly="true" 
					ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>
							value="<?php echo $row_inve['inventory_csr']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['inventory_csr']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'inventory_csr','<?php echo $row_inve["product_id"]; ?>',
																				   {preloader:'prl'});
																				 <?php if($z == ''){ ?>  this.readOnly=true;<?php } ?>
													}
													else{<?php if($z == ''){ ?> this.readOnly=true;<?php } ?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="inventory_art" id="inventory_art" 
					<?php if($z == ''){ ?>readonly="true" 
					ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>
							value="<?php echo $row_inve['inventory_art']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['inventory_art']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'inventory_art','<?php echo $row_inve["product_id"]; ?>',
																				   {preloader:'prl'});
																				 <?php if($z == ''){ ?>  this.readOnly=true;<?php } ?>
													}
													else{<?php if($z == ''){ ?> this.readOnly=true;<?php } ?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="inventory_print" id="inventory_print" 
					<?php if($z == ''){ ?>readonly="true" 
					ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>
							value="<?php echo $row_inve['inventory_print']; ?>"  size="5"
							onblur="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked'){
											   if(this.value!=<?php echo $row_inve['inventory_print']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'inventory_print','<?php echo $row_inve["product_id"]; ?>',
																				   {preloader:'prl'});
																				  <?php if($z == ''){ ?> this.readOnly=true;<?php } ?>
													}
													else{
													<?php if($z == ''){ ?>this.readOnly=true;<?php } ?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="inventory_dye_sub" id="inventory_dye_sub" 
					<?php if($z == ''){ ?>readonly="true" 
					ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>
							value="<?php echo $row_inve['inventory_dye_sub']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['inventory_dye_sub']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'inventory_dye_sub','<?php echo $row_inve["product_id"]; ?>',
																				   {preloader:'prl'});
																				<?php if($z == ''){ ?>   this.readOnly=true;<?php } ?>
													}
													else{<?php if($z == ''){ ?>this.readOnly=true;<?php } ?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="inventory_cut" id="inventory_cut" 
					<?php if($z == ''){ ?>readonly="true" 
					ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>
							value="<?php echo $row_inve['inventory_cut']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['inventory_cut']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'inventory_cut','<?php echo $row_inve["product_id"]; ?>',
																				   {preloader:'prl'});
																				<?php if($z == ''){ ?>   this.readOnly=true;<?php } ?>
													}
													else{<?php if($z == ''){ ?>this.readOnly=true;<?php } ?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="inventory_sew" id="inventory_sew"
					<?php if($z == ''){ ?> readonly="true" 
					ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>
							value="<?php echo $row_inve['inventory_sew']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['inventory_sew']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'inventory_sew','<?php echo $row_inve["product_id"]; ?>',
																				   {preloader:'prl'});
																				  <?php if($z == ''){ ?> this.readOnly=true;<?php } ?>
													}
													else{<?php if($z == ''){ ?>this.readOnly=true;<?php } ?>}}"/>
					</td>					
					<td width="10%">
					<input type="text" name="inventory_shipping" id="inventory_shipping" 
					<?php if($z == ''){ ?>readonly="true" 
					ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false"<?php } ?> 
							value="<?php echo $row_inve['inventory_shipping']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['inventory_shipping']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'inventory_shipping','<?php echo $row_inve["product_id"]; ?>',
																				   {preloader:'prl'});
																				  <?php if($z == ''){ ?> this.readOnly=true;<?php } ?>
													}
													else{<?php if($z == ''){ ?>this.readOnly=true;<?php } ?>}}"/>
					</td>											
				</tr><?php } }?>
		 </table><?php
		  break;
		  }
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
	}
	
	function deleteProduct( $product_id='', $inventory_id='', $group_inventory_id='',$group_product_id='' ){
	    ob_start();
		if( $inventory_id != '' ){
			$sql_inve = "SELECT type_name FROM ".TBL_INVENTORY_DETAILS." a, ".erp_ITEM_TYPE." b WHERE a.inventory_id = '$inventory_id' and a.type_id = b.type_id";
			$result = $this->db->query($sql_inve,__FILE__,__LINE__);
			$row = $this->db->fetch_array($result);
			$type = strtolower("$row[type_name]");
		}
		
		if( $group_inventory_id == '' && $group_product_id == ''){
		     
			  $sql = "DELETE FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id' and inventory_id = '$inventory_id' and group_inventory_id = '0'";
	          $this->db->query($sql,__FILE__,__LINE__);
			  
			  $update_sql_array = array();				
			  $update_sql_array[$type] = '';
			  
			  $this->db->update(erp_WORK_ORDER,$update_sql_array,'product_id',$product_id);
		  } 
		  
		  else if( $group_inventory_id != '' && $group_product_id == ''){
		  
			   $sql="DELETE FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id' and inventory_id = '$inventory_id' and group_inventory_id = '$group_inventory_id'";
			   $this->db->query($sql,__FILE__,__LINE__);
			   
			   $update_sql_array = array();				
			   $update_sql_array[$type] = '';
			  
			   $this->db->update(erp_WORK_ORDER,$update_sql_array,'product_id',$product_id);
			}
			
		  else if($group_product_id != ''){
		  		
			   $sql_W = "SELECT workorder_id FROM ".erp_PRODUCT_ORDER." WHERE  product_id='$product_id'";
			   $result_W = $this->db->query($sql_W,__FILE__,__LINE__);
			   while($row_W = $this->db->fetch_array($result_W)){
			   
			  	  	$sql = "DELETE FROM ".TBL_NOTES." WHERE product_id = '$row_W[workorder_id]'";
				    $this->db->query($sql,__FILE__,__LINE__); 
					 
				    $sql = "DELETE FROM ".erp_REWORK." WHERE product_id = '$row_W[workorder_id]'";
				    $this->db->query($sql,__FILE__,__LINE__);  
					
					$sql = "DELETE FROM ".erp_GROUP." WHERE fabric_id = '$row_W[workorder_id]'";
				    $this->db->query($sql,__FILE__,__LINE__);
					 
				    $sql = "DELETE FROM ".erp_ASSIGN_FCT." WHERE module_id = '$row_W[workorder_id]'";
				    $this->db->query($sql,__FILE__,__LINE__);
					
					$sql_tp="select task_id from ".ASSIGN_TASK." where product_id = '$row_W[workorder_id]' ";
					$result_tp=$this->db->query($sql_tp,__FILE__,__LINE__);
					while( $row_tp=$this->db->fetch_array($result_tp) ){
					
						$sql = "DELETE FROM ".erp_TASKS_RELATION." WHERE task_id = '$row_tp[task_id]'";
						$this->db->query($sql,__FILE__,__LINE__);
						
						$sql = "DELETE FROM ".TASKS." WHERE task_id = '$row_tp[task_id]'";
						$this->db->query($sql,__FILE__,__LINE__);
				     }
					 
					 $sql = "DELETE FROM ".ASSIGN_TASK." WHERE  product_id = '$row_W[workorder_id]'";
				     $this->db->query($sql,__FILE__,__LINE__);
			   }
		  
			   $sql="delete from ".erp_PRODUCT." where product_id='$product_id' and group_product_id ='$group_product_id' ";
			   $this->db->query($sql,__FILE__,__LINE__);
			  
			   $sql="delete from ".erp_PRODUCT_ORDER." where product_id='$product_id'";
			   $this->db->query($sql,__FILE__,__LINE__);
			   
			   $sql = "DELETE FROM ".erp_GROUP_INVENTORY." WHERE group_id = '$product_id'";
			   $this->db->query($sql,__FILE__,__LINE__);
			   
			   $sql = "DELETE FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id'";
			   $this->db->query($sql,__FILE__,__LINE__);
		  }
		  
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	  }  ////////////end of function deleteProduct

	function addProduct( $i='', $product_id='', $product='' ){
	     ob_start(); 
		   ?>
		   <div class="prl">&nbsp;</div>
				<div id="lightbox" style="margin-left:40px;">
					<div style="background-color:#ADC2EB; max-width:540px; min-width:500px;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle">Add Inventory</div>
					<div id="TB_closeAjaxWindow">
					<a href="javascript:void(0)" onclick="javascript: document.getElementById('div_order').style.display='none';">
					<img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div class="white_content" style="max-width:540px; min-width:500px;"> 
				<div style="padding:20px;" class="form_main">
		   <table class="table" width="100%">
		     <tr>
			   <td width="15%">
		         <select name="<?php if( $product == '' ){ ?>add_product<?php }else{?>add_group_product<?php } ?>" id="<?php if( $product == '' ){ ?>add_product<?php }else{?>add_group_product<?php } ?>" 
				 					onchange="javascript: 
                                            document.getElementById('product_id').value='';
										<?php if( $product == '' ){ ?>
											for(i=0; i<document.getElementById('add_product').length; i++){ 
											  if(document.getElementById('add_product')[i].selected==true){
												document.getElementById('product_id').value += document.getElementById('add_product')[i].value+',';
											  }
											}
										<?php } else { ?>
										    for(i=0; i<document.getElementById('add_group_product').length; i++){ 
											  if(document.getElementById('add_group_product')[i].selected==true){
												document.getElementById('product_id').value += document.getElementById('add_group_product')[i].value+',';
											  }
											}
										<?php } ?>
										
										 document.getElementById('product_id').value = 											                                         document.getElementById('product_id').value.substr(0,document.getElementById('product_id').value.length-1);
												   
										    if(this.value == '') {
											   return false;
										   } 
										   else{
										   obj_product.checkExistance(document.getElementById('product_id').value,
                                                                      '<?php echo $product_id; ?>',
																	  '<?php echo $product; ?>',
                                                 { preloader: 'prl', onUpdate: function(response,root){
                                                  if(response=='c'){
                                                  alert('Inventory is already exist!!');
                                                  return false;
                                                 }
												 else if(response=='d'){
                                                  alert('Inactive Inventory Can not be Assigned!!');
                                                  return false;
                                                 }
                                                 else{  obj_product.addField('local',
																			 document.getElementById('product_id').value,
																			 '<?php echo $product_id; ?>',
																			 '<?php echo $i; ?>',
																			 '<?php echo $product; ?>',
																			 {preloader:'prl', target:'add_product_box'});
								                      }}}); }">
				</select>
				<input type="hidden" name="product_id" id="product_id" />
				</td>
				<td width="85%"><div id="add_product_box"></div></td>
			 </tr>
			</table>
			</div></div></div>
	     <?php 
	     $html = ob_get_contents();
		 ob_end_clean();
		 return $html;
	    }  //////end of function addProduct
		
	function addField($runat='',$inventory='',$product_id='',$i='',$product='',$cost_increase='',$csr='',$art='',$text_print='',$dye_sub='',$cut='',$sew='',$shipping=''){
	      ob_start();
		     switch($runat){
			    case 'local': ?>
				<form name="field" method="post" action="" enctype="multipart/form-data">
				  <table class="table" width="100%">
				        <tr>
						  <td>Cost Increase</td>
						  <td>CSR</td>
						  <td>ART</td>
						  <td>Print</td>
						  <td>Dye Sub</td>
						  <td>Cut</td>
						  <td>Sew</td>
						  <td>Shipping</td>
						</tr>
						<tr>
						  <td width="13%"><input type="text" name="cost_increase" id="cost_increase" size="5" /></td>
						  <td width="13%"><input type="text" name="csr" id="csr"  size="5"/></td>
						  <td width="13%"><input type="text" name="art" id="art" size="5" /></td>
						  <td width="13%"><input type="text" name="text_print" id="text_print" size="5" /></td>
						  <td width="13%"><input type="text" name="dye_sub" id="dye_sub" size="5" /></td>
						  <td width="13%"><input type="text" name="cut" id="cut" size="5" /></td>
						  <td width="13%"><input type="text" name="sew" id="sew" size="5" /></td>
						  <td width="13%"><input type="text" name="shipping" id="shipping" size="5" /></td>
						</tr>

						<tr>  
						  <td><input type="button" value="Add" width="auto"
							   onclick="javascript:obj_product.addField('server',
							                                          '<?php echo $inventory; ?>',
																	  '<?php echo $product_id; ?>',
																	  '<?php echo $i; ?>',
																	  '<?php echo $product; ?>',
																	  document.getElementById('cost_increase').value,
																	  document.getElementById('csr').value,
																	  document.getElementById('art').value,
																	  document.getElementById('text_print').value,
																	  document.getElementById('dye_sub').value,
																	  document.getElementById('cut').value,
																	  document.getElementById('sew').value,
																	  document.getElementById('shipping').value,
                                                     { preloader:'prl', onUpdate: function(response,root){
													                document.getElementById('div_order').innerHTML = response;
																	document.getElementById('div_order').style.display = 'none';
                                                                        obj_product.showcapacity('<?php echo $product_id; ?>',
													 { onUpdate: function(response,root){
																	  document.getElementById('showTable').innerHTML=response;
																	  }}); }} );">
						  </td>
						  <td colspan="7">&nbsp;</td>
						</tr>
				  </table>
				  </form>
	      <?php 
	      break;
		  case 'server':
		     extract($_POST);
			 $return=true;
			 if( $i != '' ){
				 $sql_check="SELECT name FROM ".erp_ASSIGN_INVENTORY." WHERE inventory_id = '$inventory' and product_id = '$product_id' and group_inventory_id = '0'";
				 $result_check=$this->db->query($sql_check,_FILE_,_LINE_);
				 $row_check=$this->db->fetch_array($result_check);
				 if($this->db->num_rows($result_check) > 0){ ?>
					 <script>
						alert("Inventory Is already exists!!");
					 </script>
		     <?php
			     return false; }
			 }
			 else if( $product != '' ){
				 $sql_check="SELECT name FROM ".erp_ASSIGN_INVENTORY." WHERE inventory_id = '$inventory' and product_id = '$product_id' and group_inventory_id = '$product'";
			     $result_check=$this->db->query($sql_check,_FILE_,_LINE_);
				 $row_check=$this->db->fetch_array($result_check);
				 if($this->db->num_rows($result_check) > 0){ ?>
					 <script>
						alert("Inventory Is already exists In This Group!!");
					 </script>
			  <?php
			     return false; } //end of if
			  }
			 if($return){
				 $sql_name="SELECT name FROM ".TBL_INVENTORY_DETAILS." WHERE inventory_id = '$inventory'";
				 $result_name=$this->db->query($sql_name,_FILE_,_LINE_);
				 $row_name=$this->db->fetch_array($result_name);
					
					  $insert_sql_array = array();
					  $insert_sql_array[inventory_id] = $inventory;
					  $insert_sql_array[name] = $row_name[name];
					  $insert_sql_array[product_id] = $product_id;
					  $insert_sql_array[inventory_cost_increase] = $cost_increase;
					  $insert_sql_array[inventory_csr] = $csr;
					  $insert_sql_array[inventory_art] = $art;
					  $insert_sql_array[inventory_print] = $text_print;
					  $insert_sql_array[inventory_dye_sub] =  $dye_sub;
					  $insert_sql_array[inventory_cut] =  $cut;
					  $insert_sql_array[inventory_sew] =  $sew;
					  $insert_sql_array[inventory_shipping] = $shipping;
					  
					  if( $i == '' ){ $insert_sql_array[group_inventory_id] = $product; }
					  
					  $this->db->insert(erp_ASSIGN_INVENTORY,$insert_sql_array);
			 } //end of if
			  break;
			 }  //////////end of switch
			 $html = ob_get_contents();
			 ob_end_clean();
			 return $html;
	
	} /////end of function addField 
	   
	function showConsumable( $product_id='', $z='' ){
		ob_start(); ?>
		<table id="capacity_table" class="event_form small_text" width="100%">
			<thead>
			<tr>
				<th>Material</th>
				<th>XS</th>
				<th>S</th>
				<th>M</th>
				<th>L</th>
				<th>XL</th>

				<th>2x</th>
				<th>3x</th>
				<th>4x</th>
			</tr>
			</thead>
			<tbody>
			<?php $sql_inve = "SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id' and group_inventory_id = '0'";
			$result_inve = $this->db->query($sql_inve,__FILE__,__LINE__);
			if( $this->db->num_rows($result_inve) > 0 ){
			while ( $row_inve=$this->db->fetch_array($result_inve) ){ ?>
			<tr>
			    
				<td width="20%"><?php echo $row_inve['name']; ?></td>
				<td width="10%">
					<input type="text" name="xs_inventory_usage" id="xs_inventory_usage" <?php if( $z == '' ){ ?>readonly="true" 
									ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false"<?php } ?>
						value="<?php echo $row_inve['xs_inventory_usage']; ?>" size="5"
						onblur="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked'){
											if(this.value!=<?php echo $row_inve['xs_inventory_usage']; ?>) {
											
											   obj_product.updateFieldForInventory(this.value,
																				'<?php echo $row_inve[inventory_id]; ?>',
																				'xs_inventory_usage','<?php echo $row_inve["product_id"]; ?>',
																				{preloader:'prl'});
														<?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>
											}
											else{<?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>}}"/>
				</td>
				<td width="10%">
				<input type="text" name="s_inventory_usage" id="s_inventory_usage" <?php if( $z == '' ){ ?>readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false"<?php } ?>
					value="<?php echo $row_inve['s_inventory_usage']; ?>" size="5"
					onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
										if(this.value!=<?php echo $row_inve['s_inventory_usage']; ?>) {
										
											obj_product.updateFieldForInventory(this.value,
																			  '<?php echo $row_inve[inventory_id]; ?>',
																			  's_inventory_usage','<?php echo $row_inve["product_id"]; ?>',
																			  {preloader:'prl'});
													  <?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>
										}
										else{<?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>}}"/>
				</td>
				<td width="10%">
				<input type="text" name="m_inventory_usage" id="m_inventory_usage" <?php if( $z == '' ){ ?>readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false"<?php } ?>
					value="<?php echo $row_inve['m_inventory_usage']; ?>" size="5"
					onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
										if(this.value!=<?php echo $row_inve['m_inventory_usage']; ?>) {
										
											obj_product.updateFieldForInventory(this.value,
																			  '<?php echo $row_inve[inventory_id]; ?>',
																			  'm_inventory_usage','<?php echo $row_inve["product_id"]; ?>',
																			  {preloader:'prl'});
													  <?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>
										}
										else{<?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>}}"/>
				</td>
				<td width="10%">
				<input type="text" name="l_inventory_usage" id="l_inventory_usage" <?php if( $z == '' ){ ?>readonly="true" 
				  ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false"<?php } ?>
					value="<?php echo $row_inve['l_inventory_usage']; ?>" size="5"
					onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
										if(this.value!=<?php echo $row_inve['l_inventory_usage']; ?>) {
										
											obj_product.updateFieldForInventory(this.value,
																			  '<?php echo $row_inve[inventory_id]; ?>',
																			  'l_inventory_usage','<?php echo $row_inve["product_id"]; ?>',
																			  {preloader:'prl'});
													  <?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>
										}
										else{<?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>}}"/>
				</td>
				<td width="10%">
				<input type="text" name="xl_inventory_usage" id="xl_inventory_usage" <?php if( $z == '' ){ ?>readonly="true"
						 ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false"<?php } ?>
					value="<?php echo $row_inve['xl_inventory_usage']; ?>" size="5"
					onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
										if(this.value!=<?php echo $row_inve['xl_inventory_usage']; ?>) {
										
											obj_product.updateFieldForInventory(this.value,
																			  '<?php echo $row_inve[inventory_id]; ?>',
																			  'xl_inventory_usage','<?php echo $row_inve["product_id"]; ?>',
																			  {preloader:'prl'});
													  <?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>
										}
										else{<?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>}}"/>
				</td>
				<td width="10%">
				<input type="text" name="2x_inventory_usage" id="2x_inventory_usage" <?php if( $z == '' ){ ?>readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false"<?php } ?>
					value="<?php echo $row_inve['2x_inventory_usage']; ?>" size="5"
					onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
										if(this.value!=<?php echo $row_inve['2x_inventory_usage']; ?>) {
										
											obj_product.updateFieldForInventory(this.value,
																			  '<?php echo $row_inve[inventory_id]; ?>',
																			  '2x_inventory_usage','<?php echo $row_inve["product_id"]; ?>',
																			  {preloader:'prl'});
													  <?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>
										}
										else{<?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>}}"/>
				</td>
				<td width="10%">
				<input type="text" name="3x_inventory_usage" id="3x_inventory_usage" <?php if( $z == '' ){ ?>readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false"<?php } ?>
					value="<?php echo $row_inve['3x_inventory_usage']; ?>" size="5"
					onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
										if(this.value!=<?php echo $row_inve['3x_inventory_usage']; ?>) {
										
											obj_product.updateFieldForInventory(this.value,
																			  '<?php echo $row_inve[inventory_id]; ?>',
																			  '3x_inventory_usage','<?php echo $row_inve["product_id"]; ?>',
																			  {preloader:'prl'});
													  <?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>
										}
										else{<?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>}}"/>
				</td>
				<td width="10%">
				<input type="text" name="4x_inventory_usage" id="4x_inventory_usage" <?php if( $z == '' ){ ?>readonly="true" 
							ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false"<?php } ?>
					value="<?php echo $row_inve['4x_inventory_usage']; ?>" size="5"
					onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
										if(this.value!=<?php echo $row_inve['4x_inventory_usage']; ?>) {
										
											obj_product.updateFieldForInventory(this.value,
																			  '<?php echo $row_inve[inventory_id]; ?>',
																			  '4x_inventory_usage','<?php echo $row_inve["product_id"]; ?>',
																			  {preloader:'prl'});
													  <?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>
										}
										else{<?php if( $z == '' ){ ?>this.readOnly=true;<?php } ?>}}"/>
				</td>
			</tr><?php } 
			     } else { ?>
				   <tr><td colspan="9" align="center"> No Inventory Has Been Assigned !! </td></tr>
				<?php } ?>
			</tbody>
		</table>
		<div id="groupinventory_consumable">
		 <?php
		   $sql1 = "SELECT group_name,product_group FROM ". erp_GROUP_INVENTORY . " WHERE group_id ='$product_id'";
		   $result1 = $this->db->query($sql1,__FILE__,__LINE__);
		   if( $this->db->num_rows($result1) > 0 ){
		   echo $this->consumable($product_id,$z); } ?>
		 </div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function consumable($product='',$z=''){
		ob_start();?>
		 <table id="capacity_table1" class="event_form small_text" width="100%">
			<?php
			 $sql1 = "SELECT inventory_group_id, group_name, product_group FROM ". erp_GROUP_INVENTORY . " WHERE group_id ='$product'";
		     $result1 = $this->db->query($sql1,__FILE__,__LINE__);
		     while( $row1 = $this->db->fetch_array($result1) ){
			 
			 $sql2 = "SELECT type_name FROM ".erp_ITEM_TYPE." WHERE type_id = '$row1[product_group]'";
			 $result2 = $this->db->query($sql2,__FILE__,__LINE__);
			 $row2 = $this->db->fetch_array($result2); ?>
			   <tr>
			        <td colspan="2">&nbsp;</td>
					<th colspan="4" style="color:#009933; background-color:#FFFFFF;" align="right">Name : <?php echo $row1[group_name]; ?></th>
					
					<th colspan="4" style="color:#009933; background-color:#FFFFFF;" align="left">Product Group : <?php echo $row2[type_name]; ?></th>
			   </tr>
			   
			   <?php 
			  	$sql_inve= "select * from ".erp_ASSIGN_INVENTORY." where product_id = '$product' and group_inventory_id = '$row1[inventory_group_id]'";
				$result_inve=$this->db->query($sql_inve,_FILE_,_LINE_);
				while ($row_inve=$this->db->fetch_array($result_inve)){?>
				<tr>
                   	<td width="20%" colspan="2"><?php echo $row_inve['name']; ?></td>
					<td width="20%">
					 <input  type="text" name="xs_inventory_usage" id="xs_inventory_usage" 
					 <?php if($z == ''){?>readonly="true" size="4" 
					 		ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php } ?>
							value="<?php echo $row_inve['xs_inventory_usage'];?>"  
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['xs_inventory_usage']; ?>) {
												obj_product.updateFieldForInventory(this.value,
																						  '<?php echo $row_inve[inventory_id]; ?>',
																						  'xs_inventory_usage',
																						  '<?php echo $product;?>',
																						  {preloader:'prl'});
																	<?php if($z == ''){?>this.readOnly=true;<?php }?>
													}
													else{<?php if($z == ''){?>this.readOnly=true;<?php }?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="s_inventory_usage" id="s_inventory_usage" 
					<?php if($z == ''){?>readonly="true" ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php }?>
							value="<?php echo $row_inve['s_inventory_usage']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['s_inventory_usage']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  's_inventory_usage',
																				  '<?php echo $product;?>',
																				  {preloader:'prl'});
																<?php if($z == ''){?> this.readOnly=true;<?php }?>
													}
													else{<?php if($z == ''){?>this.readOnly=true;<?php }?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="m_inventory_usage" id="m_inventory_usage" 
					<?php if($z == ''){?>readonly="true" ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php }?>
							value="<?php echo $row_inve['m_inventory_usage']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['m_inventory_usage']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'm_inventory_usage',
																				  '<?php echo $product;?>',
																				  {preloader:'prl'});
																<?php if($z == ''){?>  this.readOnly=true;<?php }?>
													}
													else{<?php if($z == ''){?>this.readOnly=true;<?php }?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="l_inventory_usage" id="l_inventory_usage" 
					<?php if($z == ''){?>readonly="true" ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php }?>
							value="<?php echo $row_inve['l_inventory_usage']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['l_inventory_usage']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'l_inventory_usage',
																				  '<?php echo $product;?>',
																				  {preloader:'prl'});
																	<?php if($z == ''){?> this.readOnly=true;<?php }?>
													}
													else{<?php if($z == ''){?>this.readOnly=true;<?php }?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="xl_inventory_usage" id="xl_inventory_usage" 
					<?php if($z == ''){?>readonly="true" ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php }?>
							value="<?php echo $row_inve['xl_inventory_usage']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['xl_inventory_usage']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  'xl_inventory_usage',
																				  '<?php echo $product;?>',
																				  {preloader:'prl'});
																	<?php if($z == ''){?> this.readOnly=true;<?php }?>
													}
													else{<?php if($z == ''){?>this.readOnly=true;<?php }?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="2x_inventory_usage" id="2x_inventory_usage" 
					<?php if($z == ''){?>readonly="true" ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php }?>
							value="<?php echo $row_inve['2x_inventory_usage']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['2x_inventory_usage']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  '2x_inventory_usage',
																				  '<?php echo $product;?>',
																				  {preloader:'prl'});
																<?php if($z == ''){?>   this.readOnly=true;<?php }?>
													}
													else{<?php if($z == ''){?>this.readOnly=true;<?php }?>}}"/>
					</td>
					<td width="10%">
					<input type="text" name="3x_inventory_usage" id="3x_inventory_usage" 
					<?php if($z == ''){?>readonly="true" ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php }?>
							value="<?php echo $row_inve['3x_inventory_usage']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['3x_inventory_usage']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  '3x_inventory_usage',
																				  '<?php echo $product;?>',
																				  {preloader:'prl'});
																	<?php if($z == ''){?>  this.readOnly=true;<?php }?>
													}
													else{<?php if($z == ''){?>this.readOnly=true;<?php }?>}}"/>
					</td>					
					<td width="10%">
					<input type="text" name="4x_inventory_usage" id="4x_inventory_usage"
					<?php if($z == ''){?> readonly="true" ondblclick="javascript:if(document.getElementById('Lock').innerHTML=='Unlocked') this.readOnly=false" <?php }?>
							value="<?php echo $row_inve['4x_inventory_usage']; ?>"  size="5"
							onblur="javascript: if(document.getElementById('Lock').innerHTML=='Unlocked'){
												if(this.value!=<?php echo $row_inve['4x_inventory_usage']; ?>) {
												
												obj_product.updateFieldForInventory(this.value,
																				  '<?php echo $row_inve[inventory_id]; ?>',
																				  '4x_inventory_usage',
																				  '<?php echo $product;?>',
																				  {preloader:'prl'});
																<?php if($z == ''){?>   this.readOnly=true;<?php }?>
													}
													else{<?php if($z == ''){?>this.readOnly=true;<?php }?>}}"/>
					</td>											
		<?php }}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	
	}
	
	function add_item( $runat='', $group_id='' ){
	    ob_start();
		switch($runat){
		   case 'local':
		   if( $group_id != '' ){ ?>
			  <div class="prl">&nbsp;</div>
				  <div id="lightbox" style="margin-left:-100px; margin-top:5px;">
					 <div style="background-color:#ADC2EB; max-width:680px; min-width:550px;" align="left" class="ajax_heading">
					  <div id="TB_ajaxWindowTitle">Add Sub-Product</div>
					  <div id="TB_closeAjaxWindow">
						<a href="javascript:void(0);" onclick="javascript: document.getElementById('div_order').style.display='none';">
						<img border="0" src="images/close.gif" alt="close" /></a>
					  </div>
					</div>
				  <div class="white_content" style="max-width:680px;min-width:550px;min-height:500;max-height:750px;"> 
				  <div style="padding:20px;" class="form_main">
			<?php
			}
		 $FormName='add_items';
				$ControlNames=array("product_nam" =>	array('product_nam',"''","Please Enter Product name","span_prod_name"),
									    "product_stat"=>array('product_stat',"''","Please Select Status","span_prod_status"),
									    "product_typ"=>	array('product_typ',"''","Please Select Product type","span_prod_type"),
									    "item_num" =>   array('item_num',"' '","Please Enter Item Number","span_item_num"),
									    "item_type" =>	array('item_type',"' '","Please Select Type","span_type")
									   );
				$ValidationFunctionName='ValidateInventory';
				$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
				echo $JsCodeForFormValidation; ?>
		     <form name="add_items" method="post" enctype="multipart/form-data" action="">
             
                         <span id="span_prod_name"></span>
						 <span id="span_prod_status"></span>
                         <span id="span_prod_type"></span>
                         <span id="span_item_num"></span>
                         <span id="span_type"></span>
			 <table class="table" width="100%">
			   <tr>
			     <th>Product :</th>
				 <td><input type="text" name="product_nam" id="product_nam" /></td>
				 <th>Product Type :</th>
				 <td>
				     <select name="product_typ" id="product_typ">
				        <option value="">-select-</option>
						<option value="Full Custom">Full Custom</option>
						<option value="Custom">Custom</option>
					 </select>
				 </td>
			   </tr>
			   <tr>
			     <th>Item Number :</th>
				 <td><input type="text" name="item_num" id="item_num" /></td>
				 <th>Product Status :</th>
				 <td>
				    <select name="product_stat" id="product_stat">
					   <option value="">-select-</option>
					   <option value="Active">Active</option>
					   <option value="Inactive">Inactive</option>
					   <option value="Research">Research</option>
					</select>
				 </td>
			   </tr>
			   <tr>
			     <th>Type :</th>
				 <td>
				    <select name="item_type" id="item_type">
				        <option value="">-select-</option>
					    <option value="Cycling">Cycling</option>
					    <option value="Tri/Run">Tri/Run</option>
					    <option value="Jersey">Jersey</option>
						<option value="Nordic">Nordic</option>
						<option value="Alpine">Alpine</option>
						<option value="Wrestling">Wrestling</option>
						<option value="PreCuts">PreCuts</option>
					</select>
					<input type="hidden" name="group_id" id="group_id" value="<?php echo $group_id;?>"/>
				 </td>
			   </tr>
			 </table>
			 <table class="table" width="100%">
					 <tr > 
						<td>&nbsp;</td>
						<td>6-12</td>
						<td>13-24</td>
						<td>25-49</td>
						<td>50-74</td>
						<td>75+</td>
						<td>&nbsp;</td>
					 </tr>
					 <tr >
						<th>Base Price</th>
						<td  width="55px">		
							 <input width="10px" type="text" name="quty_6_12" id="quty_6_12" />
						</td>
						<td width="55px">
							<input type="text" name="quty_13_24" id="quty_13_24" />								
						 </td>
						 <td width="55px">
						 <input type="text" name="quty_25_49" id="quty_25_49"  />								 
						 </td>
						 <td width="55px">
						 <input type="text" name="quty_50_74" id="quty_50_74" />								 
						 </td>
						 <td width="55px">
						 <input type="text" name="quty_75" id="quty_75" />								 
						 </td>
					 </tr>
					 <tr> 
						<td>&nbsp;</td>
						<td>XS</td>
						<td>S</td>
						<td>M</td>
						<td>L</td>
						<td>XL</td>
						<td>&nbsp;</td>
					 </tr>						 
					 <tr>
						<td>&nbsp;</td>
						<td>
							$<input style="width:40px" type="text" name="siz_xs" id="siz_xs"/>							 
						</td>
						<td> 
							$<input style="width:40px" type="text" name="siz_s" id="siz_s"/>									 
						</td>
						<td >
							$<input style="width:40px" type="text" name="siz_m" id="siz_m"/>									
						</td>
						<td > 
							$<input style="width:40px" type="text" name="siz_l" id="siz_l" />									
						</td>
						<td> 
							$<input style="width:40px" type="text" name="siz_xl" id="siz_xl" />									
						</td>
						<td>&nbsp;</td>
					 </tr>
					 <tr>
						<td>&nbsp;</td>
						<td>2x</td>
						<td>3x</td>
						<td colspan="4">4x</td>
					 </tr>
					 <tr>
						<td>&nbsp;</td>
						<td> 
							$<input style="width:40px" type="text" name="siz_2x" id="siz_2x" />									
						</td>
						<td> 
							$<input style="width:40px" type="text" name="siz_3x" id="siz_3x" />									
						 </td>
						<td> 
							$<input style="width:40px" type="text" name="siz_4x" id="siz_4x"/>									
						</td>
						<td colspan="4"></td>
					 </tr>
			 </table>
			 <table id="capacity_table" class="event_form small_text" width="100%">
			  <thead>
			   <tr>
					<th width="20%">Material</th>
					<th width="10%"><img src="images/cost_increase.JPG" /></th>
					<th width="10%"><img src="images/csr.JPG" /></th>
					<th width="10%"><img src="images/art.JPG" /></th>
					<th width="10%"><img src="images/print.JPG" /></th>
					<th width="10%"><img src="images/dye_sub.JPG" /></th>
					<th width="10%"><img src="images/cut.JPG" /></th>
					<th width="10%"><img src="images/sew.JPG" /></th>
					<th width="10%"><img src="images/shipping.JPG" /></th>
			   </tr>
			</thead>
			<tbody>
			  	<tr>
					<td width="20%">Order Specific</td>
					<td width="10%">
						<input type="text" name="ordr_cost_increase" id="ordr_cost_increase" size="5"/>						
					</td>
					<td width="10%">
						<input type="text" name="ordr_csr" id="ordr_csr" size="5"/>						
					</td>
					<td width="10%">
						<input type="text" name="ordr_art" id="ordr_art" size="5"/>						
					</td>
					<td width="10%">
						<input type="text" name="ordr_print" id="ordr_print" size="5"/>
					</td>
					<td width="10%">
						<input type="text" name="ordr_dye_sub" id="ordr_dye_sub" size="5"/>						
					</td>
					<td width="10%">
						<input type="text" name="ordr_cut" id="ordr_cut" size="5"/>						
					</td>
					<td width="10%">
						<input type="text" name="ordr_sew" id="ordr_sew" size="5"/>						
					</td>
					<td width="10%">
						<input type="text" name="ordr_shipping" id="ordr_shipping" size="5"/>						
					</td>
			  	</tr>
			  	<tr>
					<td width="20%">Per Item</td>
					<td width="10%">
					<input type="text" name="pr_item_cost_increase" id="pr_item_cost_increase" size="5"/>												
					</td>
					<td width="10%">
						<input type="text" name="pr_item_csr" id="pr_item_csr" size="5"/>												
					</td>
					<td width="10%">
						<input type="text" name="pr_item_art" id="pr_item_art" size="5"/>												
					</td>
					<td width="10%">
						<input type="text" name="pr_item_print" id="pr_item_print" size="5"/>												
					</td>
					<td width="10%">
						<input type="text" name="pr_item_dye_sub" id="pr_item_dye_sub" size="5"/>												
					</td>
					<td width="10%">
						<input type="text" name="pr_item_cut" id="pr_item_cut" size="5"/>												
					</td>
					<td width="10%">
						<input type="text" name="pr_item_sew" id="pr_item_sew" size="5"/>												
					</td>
					<td width="10%">
						<input type="text" name="pr_item_shipping" id="pr_item_shipping" size="5"/>												
					</td>
			  	</tr>
			 	<tr>
					<td width="20%">Per Size</td>
					<td width="10%">
					<input type="text" name="pr_size_cost_increase" id="pr_size_cost_increase" size="5"/>												
					</td>
					<td width="10%">
						<input type="text" name="pr_size_csr" id="pr_size_csr" size="5"/>												
					</td>
					<td width="10%">
						<input type="text" name="pr_size_art" id="pr_size_art" size="5"/>												
					</td>
					<td width="10%">
						<input type="text" name="pr_size_print" id="pr_size_print" size="5"/>												
					</td>
					<td width="10%">
						<input type="text" name="pr_size_dye_sub" id="pr_size_dye_sub" size="5"/>												
					</td>
					<td width="10%">
						<input type="text" name="pr_size_cut" id="pr_size_cut" size="5"/>												
					</td>
					<td width="10%">
						<input type="text" name="pr_size_sew" id="pr_size_sew" size="5"/>												
					</td>
					<td width="10%">
						<input type="text" name="pr_size_shipping" id="pr_size_shipping"  size="5"/>												
					</td>
			  	</tr>
				<tr>
				    <td><input type="submit" name="add_this_item" id="add_this_item" value="add" onclick="return <?php echo $ValidationFunctionName ?>();" /></td>
				</tr>
		      </tbody>
	         </table>
			 </form>
			 <?php if( $group_id != '' ){ ?>
			 </div></div></div>
			 <?php } ?>
		      
	   <?php
	    break;
		case 'server':
		   extract($_POST); ?>
		   	<script>alert("the product with these entries is being created")</script>
          <?php
		   $return=true;
		   $name = mysql_real_escape_string($product_nam);
		   $sql_item = "SELECT product_name FROM ".erp_PRODUCT." WHERE product_name = '$name'";
		   $record_item=$this->db->query($sql_item,__FILE__,__LINE__);
		   if($this->db->num_rows($record_item) > 0){ ?>
		      <script> 
				  alert("Product name is already exist !! It cannot be created with this name");
			  </script>
		   <?php 
		     $return=false;
		   }
		   if($return){
		          $insert_sql_array = array();
		          $insert_sql_array[product_name] = htmlspecialchars($product_nam, ENT_QUOTES);
				  $insert_sql_array[product_type] = $product_typ;
				  $insert_sql_array[item_number] = $item_num;
				  $insert_sql_array[product_status] = $product_stat;
				  $insert_sql_array[type] =  $item_type;
				  
				  $insert_sql_array[quantity_6_12] =  $quty_6_12;
				  $insert_sql_array[quantity_13_24] =  $quty_13_24;
				  $insert_sql_array[quantity_25_49] = $quty_25_49;
				  $insert_sql_array[quantity_50_74] = $quty_50_74;
				  $insert_sql_array[quantity_75] = $quty_75;
				  
				  $insert_sql_array[size_xs] = $siz_xs;
				  $insert_sql_array[size_s] = $siz_s;
				  $insert_sql_array[size_m] = $siz_m;
				  $insert_sql_array[size_l] = $siz_l;
				  $insert_sql_array[size_xl] = $siz_xl;
				  $insert_sql_array[size_2x] = $siz_2x;
				  $insert_sql_array[size_3x] = $siz_3x;
				  $insert_sql_array[size_4x] = $siz_4x;
				  
				  $insert_sql_array[order_cost_increase] =  $ordr_cost_increase;
				  $insert_sql_array[order_csr] =  $ordr_csr;
				  $insert_sql_array[order_art] = $ordr_art;
				  $insert_sql_array[order_print] = $ordr_print;
				  $insert_sql_array[order_dye_sub] = $ordr_dye_sub;
				  $insert_sql_array[order_cut] = $ordr_cut;
				  $insert_sql_array[order_sew] = $ordr_sew;
				  $insert_sql_array[order_shipping] = $ordr_shipping;
				  
				  $insert_sql_array[per_item_cost_increase] =  $pr_item_cost_increase;
				  $insert_sql_array[per_item_csr] =  $pr_item_csr;
				  $insert_sql_array[per_item_art] = $pr_item_art;
				  $insert_sql_array[per_item_print] = $pr_item_print;
				  $insert_sql_array[per_item_dye_sub] = $pr_item_dye_sub;
				  $insert_sql_array[per_item_cut] = $pr_item_cut;
				  $insert_sql_array[per_item_sew] = $pr_item_sew;
				  $insert_sql_array[per_item_shipping] = $pr_item_shipping;
				  
				  $insert_sql_array[per_size_cost_increase] =  $pr_size_cost_increase;
				  $insert_sql_array[per_size_csr] =  $pr_size_csr;
				  $insert_sql_array[per_size_art] = $pr_size_art;
				  $insert_sql_array[per_size_print] = $pr_size_print;
				  $insert_sql_array[per_size_dye_sub] = $pr_size_dye_sub;
				  $insert_sql_array[per_size_cut] = $pr_size_cut;
				  $insert_sql_array[per_size_sew] = $pr_size_sew;
				  $insert_sql_array[per_size_shipping] = $pr_size_shipping;
				  
				  if( $group_id != '' ){
				      $insert_sql_array[group_product_id] = $group_id;
					 }
				  
				  $this->db->insert(erp_PRODUCT,$insert_sql_array);
				  $last_id = $this->db->last_insert_id();
				  
				  if( $group_id != '' ){
					  $sql = "SELECT order_id,workorder_id FROM ".erp_PRODUCT_ORDER." WHERE product_id = '$group_id' and gp_id = '0'";
					  $result = $this->db->query($sql,__FILE__,__LINE__);
					   if( $this->db->num_rows($result) > 0 ){
					     while( $row = $this->db->fetch_array($result) ){
						 
						   $insert_array = array();
						   $insert_array[gp_id] = $row[workorder_id];
						   $insert_array[product_id] = $last_id;
						   $insert_array[product_name] = $product_nam;
						   $insert_array[order_id] = $row[order_id];
						   $insert_array[status] = 'In Progress';
						 
						  $this->db->insert(erp_PRODUCT_ORDER,$insert_array);
						  $last_id_sub = $this->db->last_insert_id();
						  
						  $insert_assign_array = array();
						  $insert_assign_array[product_id] = $last_id_sub;
						  $insert_assign_array[order_id] = $row[order_id];
						  
						  $this->db->insert(erp_WORK_ORDER,$insert_assign_array);
					      }
					   }
				   }
				  
				  ?>
				 <script>
				 	window.location="<?php echo $_SERVER['PHP_SELF']; ?>";
				 </script>
		  <?php }  /////end of if
	      break;
	   } //////end of switch
	     $html = ob_get_contents();
	     ob_end_clean();
	     return $html;
	} ////////end of function add_item
	  
	function show_contacts($contact_id='',$inventory_id='',$old_contact_id='',$type='') {
	        ob_start();
			if($type=='edit'){ ?>
			   <table class="table">
					<tr>
					  <th>Contact :</th>
					  <td colspan="3">
						 <?php
						 $sql="select distinct contact_id,first_name, last_name from ".TBL_INVE_CONTACTS." where type='People' and                         company='$contact_id'"; ?>	
						 <select name="contact" id="contact" onChange="javascript: var type_name; 
																		  var type_id = this.value; 
																		  obj_product.getName(this.value,
																						   'people',
																		 { onUpdate: function(response,root){
																		  type_name = response;
																		  if(confirm('Are you sure you want to change your people from current person to '+ type_name)){
																			 obj_product.updateField('contact_id',
																			 				       type_id,
																								   '<?php echo $inventory_id; ?>',
																								   'people',
																					  {target:'contacts_box_<?php echo                                                                                       $inventory_id; ?>' ,preloader:'prl'});
															 }}	  
														 });">
							<option value="">-select-</option>
							<?php			
							$record=$this->db->query($sql,__FILE__,__LINE__);
							while($row=$this->db->fetch_array($record)){ ?>					
							<option value="<?php echo $row['contact_id']; ?>"><?php echo $row['first_name'].' '.$row['last_name']; ?></option>
							<?php } ?>
						 </select>
					  </td>
					</tr>
			</table>
			<?php
			}
			if($type=='add') {
			?>
				<table class="table">
				<tr>
				  <th>Contact :</th>
				  <td colspan="3">
					 <?php
					 $sql="select distinct contact_id,first_name, last_name from ".TBL_INVE_CONTACTS." where type='People' and  company='$contact_id'";	?>	
					 <select name="contact" id="contact">
						<option value="">-select-</option>
						<?php			
						$record=$this->db->query($sql,__FILE__,__LINE__);
						while($row=$this->db->fetch_array($record)){ ?>					
						<option value="<?php echo $row['contact_id']; ?>"><?php echo $row['first_name'].' '.$row['last_name'] ; ?> </option>
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

	function returnLink($variable='',$product_id='',$div_id='',$choice=''){
		ob_start();
		
		switch($choice) {
		     case 'product_name':
				if($variable !=''){
			 		 $str = array("'","&#039;");
					$replace = "&#096;";
					$pro_name = str_replace($str,$replace,$variable);	
					
					$sql = "select product_name from erp_product where product_id = '$product_id'";
					$result = $this->db->query($sql,__FILE__,__LINE__);
					$row=$this->db->fetch_array($result); ?>
					<a href="javascript:void(0);" 
							onclick="javascript: obj_product.showTextBox('<?php echo $pro_name; ?>',
																	   '<?php echo $product_id; ?>',
																	   '<?php echo $div_id; ?>',
																	   '<?php echo $choice; ?>',
																	   { target: '<?php echo $div_id; ?>'}
																	   ); ">
					<?php echo $row['product_name']; ?></a><?php 
				}
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: obj_product.showTextBox('N/A',
																		'<?php echo $product_id; ?>',
																		'<?php echo $choice; ?>',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			 break;
		     case 'product_type':
				if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: obj_product.showdropdown('<?php echo $variable; ?>',
																	   '<?php echo $product_id; ?>',
																	   '<?php echo $div_id; ?>',
																	   '<?php echo $choice; ?>',
																	   { target: '<?php echo $div_id; ?>'}
																	   ); ">
					<?php echo $variable; ?></a><?php 
				}
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: obj_product.showdropdown('N/A',
																		'<?php echo $product_id; ?>',
																		'<?php echo $choice; ?>',
																		'<?php echo $contact_id;?>',
																		{ target: '<?php echo $div_id; ?>'}
																		); ">
					N/A</a>
				   <?php 
				     }
			 break;
			 case 'product_status':
			    if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: obj_product.showdropdown('<?php echo $variable; ?>',
																	   '<?php echo $product_id; ?>',
																	   '<?php echo $div_id; ?>',
																	   '<?php echo $choice; ?>',
																	   { target: '<?php echo $div_id; ?>'}
																	   ); ">
					<?php echo $variable; ?></a><?php 
				}
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: obj_product.showdropdown('N/A',
																	   '<?php echo $product_id; ?>',
																	   '<?php echo $div_id; ?>',
																	   '<?php echo $choice; ?>',
																	   { target: '<?php echo $div_id; ?>'}
																	   ); ">
					N/A</a>
				   <?php 
				     }
			  break;	
			  case 'item_number': 
			     if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: obj_product.showTextBox('<?php echo $variable; ?>',
																	   '<?php echo $product_id; ?>',
																	   '<?php echo $div_id; ?>',
																	   '<?php echo $choice; ?>',
																	   { target: '<?php echo $div_id; ?>'}
																	   ); ">
					<?php echo $variable; ?></a><?php 
				}
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: obj_product.showTextBox('N/A',
																	   '<?php echo $product_id; ?>',
																	   '<?php echo $div_id; ?>',
																	   '<?php echo $choice; ?>',
																	   { target: '<?php echo $div_id; ?>'}
																	   ); ">
					N/A</a>
				   <?php 
				     }
			  break;
			  case 'type':
			     if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: obj_product.showdropdown('<?php echo $variable; ?>',
																	   '<?php echo $product_id; ?>',
																	   '<?php echo $div_id; ?>',
																	   '<?php echo $choice; ?>',
																	   { target: '<?php echo $div_id; ?>'}
																	   ); ">
					<?php echo $variable; ?></a>
					<?php }
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: obj_product.showdropdown('N/A',
																	   '<?php echo $product_id; ?>',
																	   '<?php echo $div_id; ?>',
																	   '<?php echo $choice; ?>',
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
	
	function checkStatus($product_id=''){
	   ob_start();
	   $sql = "SELECT a.product_id,b.order_id FROM ".erp_PRODUCT_ORDER." a , ".erp_ORDER." b WHERE a.product_id = '$product_id' AND a.order_id = b.order_id  AND b.status = 'In Progress'";
	   $result = $this->db->query($sql,__FILE__,__LINE__);
	   $order_id = '';
	   while( $row = $this->db->fetch_array($result) ){
	          $order_id .=$row[order_id].','; }
	   echo $order_id;
	   $html = ob_get_contents();
	   ob_end_clean();
	   return $html;
	} ////////////////end of function checkStatus
	
	function showdropdown($variable='',$product_id='',$div_id='',$choice=''){
	        ob_start();
			switch($choice){
			  case'product_status':?>
			      <select name="product_status" id="product_status"  
				    onblur="javascript: var p_type_name=this.value;
											if(p_type_name != '<?php echo $variable; ?>'){
					                       if(confirm('Are you sure you want to change your type from <?php echo $variable; ?> to '+ p_type_name)){
										   <?php if( $variable == 'Active' ){ ?>
											        if(p_type_name == 'Active'){
													alert('Please Select Inactive to Change Status');
													return false;
													}
													obj_product.checkStatus('<?php echo $product_id;?>',
													           {preloader:'prl',
																onUpdate: function(response,root){
																var a = response;
													if(confirm('Would you like to remove this Product from all active Orders '+ a)){
													var name=prompt('You are about to remove this Product from all active Orders.... ARE YOU SURE YOU WANT TO DO THIS','yes/no');
													if (name!=null && name!=''){
													  if(name == 'yes'){
													obj_product.updateField( p_type_name,
																			'<?php echo $product_id;?>',
																			'<?php echo $choice ;?>',
																			1,
														   					{onUpdate:function(response,root){
														   					obj_product.returnLink(p_type_name,
																								   '<?php echo $product_id;?>',
																								   '<?php echo $div_id;?>',
																								   '<?php echo $choice ;?>',
																			  {target:'<?php  echo $div_id; ?>',preloader:'prl'});
														 }});
																				 
												  obj_product.show_searchproductinventory({preloader:'prl',
																						  onUpdate: function(response,root)
																						  {document.getElementById('task_area').innerHTML=response;
																						  $('#search_table').tablesorter({widthFixed:true,
																						  widgets:['zebra'],sortList:[[0,0]],headers :{5:{sorter: false}} }); }});
													  
													 } else if(name == 'no'){
													obj_product.returnLink('<?php echo $variable; ?>',
																		   '<?php echo $product_id;?>',
																		   '<?php echo $div_id;?>',
																		   '<?php echo $choice ;?>',
																   {target:'<?php  echo $div_id; ?>',preloader:'prl'});
													  }
													} else {
													      obj_product.returnLink('<?php echo $variable; ?>',
																	   '<?php echo $product_id;?>',
																	   '<?php echo $div_id;?>',
																	   '<?php echo $choice ;?>',
													           {target:'<?php  echo $div_id; ?>',preloader:'prl'});
													      }			
														}
													else{
														 obj_product.returnLink('<?php echo $variable; ?>',
																	   '<?php echo $product_id;?>',
																	   '<?php echo $div_id;?>',
																	   '<?php echo $choice ;?>',
													           {target:'<?php  echo $div_id; ?>',preloader:'prl'});
													    }
													}});
											 <?php } else { ?> 
												        obj_product.updateField( p_type_name,
																	'<?php echo $product_id;?>',
																	'<?php echo $choice ;?>',
														   {onUpdate:function(response,root){
														   
														   obj_product.returnLink(p_type_name,
																			   '<?php echo $product_id;?>',
																			   '<?php echo $div_id;?>',
																			   '<?php echo $choice ;?>',
														  {target:'<?php  echo $div_id; ?>',preloader:'prl'});
																				 }});
																				 
														  obj_product.show_searchproductinventory(
							                              {preloader:'prl',
														  onUpdate: function(response,root)
														  {document.getElementById('task_area').innerHTML=response;
														  $('#search_table').tablesorter({widthFixed:true,
														  widgets:['zebra'],sortList:[[0,0]],headers :{5:{sorter: false}} }); }});
												<?php } ?> }
												 else {
												  obj_product.returnLink('<?php echo $variable; ?>',
																	   '<?php echo $product_id;?>',
																	   '<?php echo $div_id;?>',
																	   '<?php echo $choice ;?>',
													{target:'<?php  echo $div_id; ?>',preloader:'prl'});						
											}}else{
											obj_product.returnLink('<?php echo $variable; ?>',
																	   '<?php echo $product_id;?>',
																	   '<?php echo $div_id;?>',
																	   '<?php echo $choice ;?>',
													{target:'<?php  echo $div_id; ?>',preloader:'prl'});
											}">
				<option value="" >--Select--</option>
					<option value="Active" <?php if('Active'==$variable) echo 'selected="selected"';?>>Active</option>
					<option value="Inactive" <?php if('Inactive'==$variable) echo 'selected="selected"';?>>Inactive</option>
					<option value="Research" <?php if('Research'==$variable) echo 'selected="selected"';?>>Research</option>
				</select>
			   <?php
			   break;
			  case'product_type':?>
			      <select name="product_type" id="product_type"  
				    onblur="javascript: var p_type_name=this.value;
										if(p_type_name != '<?php echo $variable; ?>'){
					                       if(confirm('Are you sure you want to change your type from <?php echo $variable; ?> to '+ p_type_name)){
										      obj_product.updateField( p_type_name,
																	'<?php echo $product_id;?>',
																	'<?php echo $choice ;?>',
																	 {onUpdate:function(response,root){
																	  obj_product.returnLink(p_type_name,
																	                       '<?php echo $product_id;?>',
																						   '<?php echo $div_id;?>',
																						   '<?php echo $choice ;?>',
																						   {target:'<?php  echo $div_id; ?>',preloader:'prl'});}});
                                                 obj_product.show_searchproductinventory(
							                              {preloader:'prl', onUpdate: function(response,root)
														  {document.getElementById('task_area').innerHTML=response;
														  $('#search_table').tablesorter({widthFixed:true,
														  widgets:['zebra'],sortList:[[0,0]],headers :{5:{sorter: false}} })}});}
											else{
												  obj_product.returnLink('<?php echo $variable; ?>',
																	   '<?php echo $product_id;?>',
																	   '<?php echo $div_id;?>',
																	   '<?php echo $choice ;?>',
																	   {target:'<?php  echo $div_id; ?>',preloader:'prl'});}}
												  else{
												  obj_product.returnLink('<?php echo $variable; ?>',
																	   '<?php echo $product_id;?>',
																	   '<?php echo $div_id;?>',
																	   '<?php echo $choice ;?>',
																	   {target:'<?php  echo $div_id; ?>',preloader:'prl'});}">
				    <option value="" >--Select--</option>
					<option value="Full Custom" <?php if('Full Custom'==$variable) echo 'selected="selected"';?>>Full Custom</option>
					<option value="Custom" <?php if('Custom'==$variable) echo 'selected="selected"';?>>Custom</option>
				</select>
			   <?php
			   break;
			 
			   case'type':?>
			      <select name="p_type" id="p-type"  
				    onblur="javascript: var p_type_name=this.value;
										if(p_type_name != '<?php echo $variable; ?>'){	
					                       if(confirm('Are you sure you want to change your type from <?php echo $variable; ?> to '+ p_type_name)){
										      obj_product.updateField( p_type_name,
																	'<?php echo $product_id;?>',
																	'<?php echo $choice ;?>',
																	 {onUpdate:function(response,root){
																	  obj_product.returnLink(p_type_name,
																	                       '<?php echo $product_id;?>',
																						   '<?php echo $div_id;?>',
																						   '<?php echo $choice ;?>',
																						   {target:'<?php  echo $div_id; ?>',preloader:'prl'});}});
                                                  obj_product.show_searchproductinventory(
							                              {preloader:'prl', onUpdate: function(response,root)
														  {document.getElementById('task_area').innerHTML=response;
														  $('#search_table').tablesorter({widthFixed:true,
														  widgets:['zebra'],sortList:[[0,0]],headers :{5:{sorter: false}} })}});}
											else{
												  obj_product.returnLink('<?php echo $variable; ?>',
																	   '<?php echo $product_id;?>',
																	   '<?php echo $div_id;?>',
																	   '<?php echo $choice ;?>',
																	   {target:'<?php  echo $div_id; ?>',preloader:'prl'});}}
											else{
											obj_product.returnLink('<?php echo $variable; ?>',
																	   '<?php echo $product_id;?>',
																	   '<?php echo $div_id;?>',
																	   '<?php echo $choice ;?>',
																	   {target:'<?php  echo $div_id; ?>',preloader:'prl'});}">
				    <option value="" >--Select--</option>
					<option value="Cycling" <?php if('Cycling'==$variable) echo 'selected="selected"';?>>Cycling</option>
					<option value="Jersey" <?php if('Jersey'==$variable) echo 'selected="selected"';?>>Jersey</option>
					<option value="Tri/Run" <?php if('Tri/Run'==$variable) echo 'selected="selected"';?>>Tri/Run</option>
					<option value="Nordic" <?php if('Nordic'==$variable) echo 'selected="selected"';?>>Nordic</option>
					<option value="Alpine" <?php if('Alpine'==$variable) echo 'selected="selected"';?>>Alpine</option>
					<option value="Wrestling" <?php if('Wrestling'==$variable) echo 'selected="selected"';?>>Wrestling</option>
					<option value="PreCuts" <?php if('PreCuts'==$variable) echo 'selected="selected"';?>>PreCuts</option>
				</select>
			   <?php
			   break;
			 
			}
			$html=ob_get_contents();
			ob_end_clean();
			return $html;
	}

	function updateField($variable='',$product_id='',$choice='',$z=''){
		ob_start();
		if( $z != '' ){
		    $sql = "update ".erp_PRODUCT." set $choice= '$variable' where product_id='$product_id'";
		    $result = $this->db->query($sql,__FILE__,__LINE__);
			
			$sql="DELETE FROM ".erp_PRODUCT_ORDER." WHERE product_id = '$product_id'";
	        $this->db->query($sql,__FILE__,__LINE__);
			
			$sql="DELETE FROM ".erp_WORK_ORDER." WHERE product_id = '$product_id'";
	        $this->db->query($sql,__FILE__,__LINE__);
		
		} else {
			$sql = "update ".erp_PRODUCT." set $choice= '$variable' where product_id='$product_id'";
			$result = $this->db->query($sql,__FILE__,__LINE__);
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}   ////////////////end of function updateField

	function updateFieldForInventory($variable='',$inventory_id='',$choice='',$product_id=''){
		ob_start();
		$sql = "update ".erp_ASSIGN_INVENTORY." set $choice= '$variable' where inventory_id='$inventory_id' AND product_id='$product_id'";
		echo $sql;
		$result = $this->db->query($sql,__FILE__,__lINE__);	
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}   ////////////////end of function updateFieldForInventory
	
	function showTextBox($variable='',$product_id='',$div_id='',$choice=''){
	 	 ob_start();
		 switch($choice) {
		        case 'product_name':
					 $sql = "select product_name from ".erp_PRODUCT." where product_id = '$product_id'";
					$result = $this->db->query($sql,__FILE__,__LINE__);
					$row=$this->db->fetch_array($result); ?>
				<input  type="text" name="<?php  echo $variable; ?>" id="<?php echo $variable; ?>" value="<?php  echo $row[product_name]; ?>"
						onblur="javascript: var type_name =this.value;
											
										if(type_name!='<?php echo $variable; ?>') {
								      if(confirm('Are you sure you want to change your product name from <?php echo $variable; ?> to '+ type_name)){	 
									  		obj_product.update_textbox('<?php echo $product_id; ?>',
																	   document.getElementById('<?php echo $variable; ?>').value,
																	   '<?php echo $div_id; ?>',
																	   '<?php echo $choice; ?>',
																	   { onUpdate: function(response,root){
																		 obj_product.returnLink(
																		 			   document.getElementById('<?php echo $variable; ?>').value,
																					   '<?php echo $product_id;?>',
																					   '<?php echo $div_id;?>',
																					   '<?php echo $choice ;?>',
																					   {target:'<?php  echo $div_id; ?>',preloader:'prl'});}});
                                         obj_product.show_searchproductinventory(
							                              {preloader:'prl', onUpdate: function(response,root)
														  {document.getElementById('task_area').innerHTML=response;
														  $('#search_table').tablesorter({widthFixed:true,
														  widgets:['zebra'],sortList:[[0,0]],headers :{5:{sorter: false}} })}});}
										 else{
											  obj_product.returnLink('<?php echo  $variable; ?>',
																   '<?php echo $product_id;?>',
																   '<?php echo $div_id;?>',
																   '<?php echo $choice ;?>',
																   {target:'<?php  echo $div_id; ?>',preloader:'prl'});}}
						else{
							obj_product.returnLink('<?php echo  $variable; ?>',
																   '<?php echo $product_id;?>',
																   '<?php echo $div_id;?>',
																   '<?php echo $choice ;?>',
																   {target:'<?php  echo $div_id; ?>',preloader:'prl'});}"> 
				<?php  
				break;
				case 'item_number': ?>
				<input  type="text" name="<?php  echo $variable; ?>" id="<?php echo $variable; ?>" value="<?php  echo $variable; ?>"
						onblur="javascript: var type_name=this.value; 
										if(type_name!='<?php echo $variable; ?>') {
								      if(confirm('Are you sure you want to change your item number from <?php echo $variable; ?> to '+ type_name)){	 
									  		obj_product.update_textbox('<?php echo $product_id;?>',
																	   document.getElementById('<?php echo $variable; ?>').value,
																	   '<?php echo $div_id; ?>',
																	   '<?php echo $choice; ?>',
																	   { onUpdate: function(response,root){
																		 obj_product.returnLink(
																		 			   document.getElementById('<?php echo $variable; ?>').value,
																					   '<?php echo $product_id;?>',
																					   '<?php echo $div_id;?>',
																					   '<?php echo $choice ;?>',
																					   {target:'<?php  echo $div_id; ?>',preloader:'prl'});}});
                                         obj_product.show_searchproductinventory(
							                              {preloader:'prl', onUpdate: function(response,root)
														  {document.getElementById('task_area').innerHTML=response;
														  $('#search_table').tablesorter({widthFixed:true,
														  widgets:['zebra'],sortList:[[0,0]],headers :{5:{sorter: false}} })}});}
										 else{
											  obj_product.returnLink('<?php echo $variable; ?>',
																   '<?php echo $product_id;?>',
																   '<?php echo $div_id;?>',
																   '<?php echo $choice ;?>',
																   {target:'<?php  echo $div_id; ?>',preloader:'prl'} );}}
						else{
						obj_product.returnLink('<?php echo $variable; ?>',
																   '<?php echo $product_id;?>',
																   '<?php echo $div_id;?>',
																   '<?php echo $choice ;?>',
																   {target:'<?php  echo $div_id; ?>',preloader:'prl'});}"> 
				<?php  
				break;
			}    /////////////end of switch
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}  ////////////////end of function showTextBox
	
	function update_textbox($product_id,$variable,$div_id,$choice){
	    ob_start();
		$sql = "update ".erp_PRODUCT." set $choice= '$variable' where product_id='$product_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);	
        $html=ob_get_contents();
		ob_end_clean();
		return $html;
	 } ////////////end of function update_textbox
	 
	 function deletethis( $product_id='', $runat='', $product_group='' ){
	    ob_start();
		switch($runat){
			case 'unassigned' :
			
				  $sql="DELETE FROM ".erp_PRODUCT." WHERE product_id = '$product_id'";
				  $this->db->query($sql,__FILE__,__LINE__);
				  
				  $sql="DELETE FROM ".erp_PRODUCT." WHERE group_product_id = '$product_id'";
				  $this->db->query($sql,__FILE__,__LINE__);
				  
				  $sql="DELETE FROM ".erp_GROUP_INVENTORY." WHERE group_id = '$product_id' and inventory_group_id = '$product_group'";
				  $this->db->query($sql,__FILE__,__LINE__);
			 
				break;
			 case 'assigned' :
				  
				  $sql="SELECT workorder_id FROM ".erp_PRODUCT_ORDER." where product_id='$product_id' ";
				  $result = $this->db->query($sql,__FILE__,__lINE__);	
				  while($row=$this->db->fetch_array($result))
				  {
				  	 $sql = "DELETE FROM ".erp_SIZE." WHERE product_id = '$row[workorder_id]'";
				     $this->db->query($sql,__FILE__,__LINE__);
					 
					 $sql = "DELETE FROM ".TBL_NOTES." WHERE product_id = '$row[workorder_id]'";
				     $this->db->query($sql,__FILE__,__LINE__);
					 
					 $sql = "DELETE FROM ".erp_REWORK." WHERE product_id = '$row[workorder_id]'";
				     $this->db->query($sql,__FILE__,__LINE__);
					 
					 $sql = "DELETE FROM ".erp_WORK_ORDER." WHERE product_id = '$row[workorder_id]'";
				     $this->db->query($sql,__FILE__,__LINE__);
					 
					 $sql = "DELETE FROM ".erp_SIZE_DEPENDENT." WHERE product_id = '$row[workorder_id]'";
					 $this->db->query($sql,__FILE__,__LINE__);
					 
					 $sql = "DELETE FROM ".erp_GROUP." WHERE fabric_id = '$row[workorder_id]'";
				     $this->db->query($sql,__FILE__,__LINE__);
					 
					 $sql = "DELETE FROM ".erp_ASSIGN_FCT." WHERE module_id = '$row[workorder_id]'";
				     $this->db->query($sql,__FILE__,__LINE__);
					 
					 $sql_tp="select task_id from ".ASSIGN_TASK." where product_id = '$row[workorder_id]' ";
					 $result_tp=$this->db->query($sql_tp,__FILE__,__LINE__);
					 while( $row_tp=$this->db->fetch_array($result_tp) ){
					
						$sql = "DELETE FROM ".erp_TASKS_RELATION." WHERE task_id = '$row_tp[task_id]'";
						$this->db->query($sql,__FILE__,__LINE__);
						
						$sql = "DELETE FROM ".TASKS." WHERE task_id = '$row_tp[task_id]'";
						$this->db->query($sql,__FILE__,__LINE__);
				     }
					 
					 $sql = "DELETE FROM ".ASSIGN_TASK." WHERE  product_id = '$row[workorder_id]'";
				     $this->db->query($sql,__FILE__,__LINE__);
					 
					 $sql_w="SELECT workorder_id FROM ".erp_PRODUCT_ORDER." where gp_id='$row[workorder_id]' ";
				     $result_w = $this->db->query($sql_w,__FILE__,__lINE__);
					 while($row_w=$this->db->fetch_array($result_w))
				 		 {	
						 	$sql = "DELETE FROM ".TBL_NOTES." WHERE product_id = '$row_w[workorder_id]'";
				            $this->db->query($sql,__FILE__,__LINE__);
							
							 $sql = "DELETE FROM ".erp_REWORK." WHERE product_id = '$row_w[workorder_id]'";
				             $this->db->query($sql,__FILE__,__LINE__);
							 
							 $sql = "DELETE FROM ".erp_WORK_ORDER." WHERE product_id = '$row_w[workorder_id]'";
				             $this->db->query($sql,__FILE__,__LINE__);
							 
							 $sql = "DELETE FROM ".erp_SIZE_DEPENDENT." WHERE product_id = '$row_w[workorder_id]'";
						     $this->db->query($sql,__FILE__,__LINE__);
							 
							 $sql = "DELETE FROM ".erp_GROUP." WHERE fabric_id = '$row_w[workorder_id]'";
				             $this->db->query($sql,__FILE__,__LINE__);
							 
							 $sql = "DELETE FROM ".erp_ASSIGN_FCT." WHERE module_id = '$row_w[workorder_id]'";
				             $this->db->query($sql,__FILE__,__LINE__);
							 
							 $sql_t="select task_id from ".ASSIGN_TASK." where product_id = '$row_w[workorder_id]' ";
							 $result_t=$this->db->query($sql_t,__FILE__,__LINE__);
							 while( $row_t=$this->db->fetch_array($result_t) ){
								    
									$sql = "DELETE FROM ".erp_TASKS_RELATION." WHERE task_id = '$row_t[task_id]'";
									$this->db->query($sql,__FILE__,__LINE__);
									
									$sql = "DELETE FROM ".TASKS." WHERE task_id = '$row_t[task_id]'";
									$this->db->query($sql,__FILE__,__LINE__);
							    }
							$sql = "DELETE FROM ".ASSIGN_TASK." WHERE  product_id = '$row_w[workorder_id]'";
							$this->db->query($sql,__FILE__,__LINE__);
						}
						 
					 $sql = "DELETE FROM ".erp_PRODUCT_ORDER." WHERE gp_id = '$row[workorder_id]'";
				     $this->db->query($sql,__FILE__,__LINE__);
					
					 $sql = "DELETE FROM ".erp_PRODUCT_ORDER." WHERE product_id = '$product_id'";
				     $this->db->query($sql,__FILE__,__LINE__);
					 }
				 
				  $sql_a="SELECT product_id FROM ".erp_PRODUCT." where group_product_id='$product_id' ";
				  $result_a = $this->db->query($sql_a,__FILE__,__lINE__);	
				  while($row_a=$this->db->fetch_array($result_a))
				   {
						$sql = "DELETE FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$row_a[product_id]'";
						$this->db->query($sql,__FILE__,__LINE__);
						
						$sql = "DELETE FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id'";
						$this->db->query($sql,__FILE__,__LINE__);
						
						$sql = "DELETE FROM ".erp_GROUP_INVENTORY." WHERE group_id = '$product_id'";
				        $this->db->query($sql,__FILE__,__LINE__);
						
						$sql = "DELETE FROM ".erp_GROUP_INVENTORY." WHERE group_id = '$row_a[product_id]'";
				        $this->db->query($sql,__FILE__,__LINE__);
				   }
				   
				  $sql="DELETE FROM ".erp_PRODUCT." WHERE product_id = '$product_id'";
				  $this->db->query($sql,__FILE__,__LINE__);
				  
				  $sql="DELETE FROM ".erp_PRODUCT." WHERE group_product_id = '$product_id'";
				  $this->db->query($sql,__FILE__,__LINE__);
			  
				 break;
				 
			  case 'group' :
				  $sql_g="SELECT workorder_id FROM ".erp_PRODUCT_ORDER." where product_id 	='$product_id' ";
				  $result_g= $this->db->query($sql_g,__FILE__,__lINE__);	
				  while($row_g=$this->db->fetch_array($result_g)){
				  	
					$sql_inve = "SELECT type_name FROM ".erp_GROUP_INVENTORY." a, ".erp_ITEM_TYPE." b WHERE a.product_group = b.type_id and a.group_id = '$product_id' and a.inventory_group_id = '$product_group'";
					$result = $this->db->query($sql_inve,__FILE__,__LINE__);
					$row = $this->db->fetch_array($result);
					$type = strtolower("$row[type_name]");
		
					$update_array = array();
					$update_array[$type] = '';
					$this->db->update(erp_WORK_ORDER,$update_array,'product_id',$row_g[workorder_id]);
				  }
				  
				  $sql="DELETE FROM ".erp_GROUP_INVENTORY." WHERE group_id = '$product_id' and inventory_group_id = '$product_group'";
				  $this->db->query($sql,__FILE__,__LINE__);
				  
				  $sql="DELETE FROM ".erp_ASSIGN_INVENTORY." WHERE product_id = '$product_id' and group_inventory_id = '$product_group'";
				  $this->db->query($sql,__FILE__,__LINE__);
				  
				  $sql="DELETE FROM ".erp_SIZE_DEPENDENT." WHERE group = '$product_group'";
				  $this->db->query($sql,__FILE__,__LINE__);
			  break;
		  }
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	  }  ////////////end of function deletethis
	  
     function show_link($product_id='',$p_type='',$item_num='',$product_type='',$product_status=''){
  	     ob_start(); 
		 ?>
	     <a href="javascript:void(0);" onClick="javascript:
										   obj_product.add_clone('local',
																 '<?php echo $product_id; ?>',
																 '<?php echo $p_type; ?>',
																 '<?php echo $product_type; ?>',
																 '<?php echo $product_status; ?>',
													{preloader: 'prl',
													onUpdate: function(response,root){
													
													obj_product.show_inve(response,
																		  '<?php echo $p_type; ?>',
																		  '<?php echo $item_num; ?>',
																		  '<?php echo $product_type; ?>',
																		  '<?php echo $product_status; ?>',
																		  '',
																		  '',
																		  1,
																		  '<?php echo $product_id; ?>',
														 {preloader: 'prl',
														 onUpdate: function(response,root){
														 document.getElementById('show_value').innerHTML=response;
														 document.getElementById('show_value').style.display='';
													 	$(function() {		
															$('#capacity_table')
															.tablesorter({widthFixed: true, widgets: ['zebra'],sortList: [[0,0]], headers: {0:{sorter: false},1:{sorter: false},2:{sorter: false},3:{sorter: false},4:{sorter: false},5:{sorter: false},6:{sorter: false},7:{sorter: false},8:{sorter: false}} } ) }); }}); }});">clone selected product</a>	
		    <?php
			$html = ob_get_contents();
			ob_end_clean();
			return $html;	
	}  ////////////////end of function show_link
	
	function add_clone($runat='',$product_id='',$p_type='',$product_type='',$product_status='',$product_name='',$item_num='') {

	         ob_start(); 
			    switch($runat) {
				  case 'local': 
				    $sql="SELECT * FROM ". erp_PRODUCT ." WHERE product_id ='$product_id'";
				    $result=$this->db->query($sql,__FILE__,__LINE__);
					$row=$this->db->fetch_array($result);
						  
						  $insert_sql_array = array();
						  $insert_sql_array[group_product_id] = $row[group_product_id];
						  $insert_sql_array[product_name] = 'test=clone';
						  $insert_sql_array[product_type] = $row[product_type];
						  $insert_sql_array[item_number] = $row[item_number];
						  $insert_sql_array[product_status] = $row[product_status];
						  $insert_sql_array[type] =  $row[type];
						  
						  $insert_sql_array[quantity_6_12] =  $row[quantity_6_12];
						  $insert_sql_array[quantity_13_24] =  $row[quantity_13_24];
						  $insert_sql_array[quantity_25_49] = $row[quantity_25_49];
						  $insert_sql_array[quantity_50_74] = $row[quantity_50_74];
						  $insert_sql_array[quantity_75] = $row[quantity_75];
						  
						  $insert_sql_array[size_xs] = $row[size_xs];
						  $insert_sql_array[size_s] = $row[size_s];
						  $insert_sql_array[size_m] = $row[size_m];
						  $insert_sql_array[size_l] = $row[size_l];
						  $insert_sql_array[size_xl] = $row[size_xl];
						  $insert_sql_array[size_2x] = $row[size_2x];
						  $insert_sql_array[size_3x] = $row[size_3x];
						  $insert_sql_array[size_4x] = $row[size_4x];
						  
						  $insert_sql_array[order_cost_increase] =  $row[order_cost_increase];
						  $insert_sql_array[order_csr] =  $row[order_csr];
						  $insert_sql_array[order_art] = $row[order_art];
						  $insert_sql_array[order_print] = $row[order_print];
						  $insert_sql_array[order_dye_sub] = $row[order_dye_sub];
						  $insert_sql_array[order_cut] = $row[order_cut];
						  $insert_sql_array[order_sew] = $row[order_sew];
						  $insert_sql_array[order_shipping] = $row[order_shipping];
						  
						  $insert_sql_array[per_item_cost_increase] =  $row[per_item_cost_increase];
						  $insert_sql_array[per_item_csr] =  $row[per_item_csr];
						  $insert_sql_array[per_item_art] = $row[per_item_art];
						  $insert_sql_array[per_item_print] = $row[per_item_print];
						  $insert_sql_array[per_item_dye_sub] = $row[per_item_dye_sub];
						  $insert_sql_array[per_item_cut] = $row[per_item_cut];
						  $insert_sql_array[per_item_sew] = $row[per_item_sew];
						  $insert_sql_array[per_item_shipping] = $row[per_item_shipping];
						  
						  $insert_sql_array[per_size_cost_increase] =  $row[per_size_cost_increase];
						  $insert_sql_array[per_size_csr] =  $row[per_size_csr];
						  $insert_sql_array[per_size_art] = $row[per_size_art];
						  $insert_sql_array[per_size_print] = $row[per_size_print];
						  $insert_sql_array[per_size_dye_sub] = $row[per_size_dye_sub];
						  $insert_sql_array[per_size_cut] = $row[per_size_cut];
						  $insert_sql_array[per_size_sew] = $row[per_size_sew];
						  $insert_sql_array[per_size_shipping] = $row[per_size_shipping];
						  $insert_sql_array[check_clone] = '1';
						  
						  $this->db->insert(erp_PRODUCT,$insert_sql_array);
						  $last_id = $this->db->last_insert_id();
					
					
						  
					  $sql_group="SELECT * FROM ". erp_GROUP_INVENTORY ." WHERE group_id ='$product_id'";
					  $result_group=$this->db->query($sql_group,__FILE__,__LINE__);
					  if($this->db->num_rows($result_group)>0){
					    while($row_group=$this->db->fetch_array($result_group)){
						
							  $insert_sql_array = array();
							  $insert_sql_array[group_id] = $last_id;
							  $insert_sql_array[group_name] = $row_group[group_name];
							  $insert_sql_array[product_group] = $row_group[product_group];
							  $insert_sql_array[check_clone] = '1';
							  
							  $this->db->insert(erp_GROUP_INVENTORY,$insert_sql_array);
							  $last_group_id = $this->db->last_insert_id();
							  
						
						  
							  $sql_inve="SELECT * FROM ". erp_ASSIGN_INVENTORY ." WHERE product_id ='$product_id' and group_inventory_id='$row_group[inventory_group_id]'";
							  $result_inve=$this->db->query($sql_inve,__FILE__,__LINE__);
							  while($row_inve=$this->db->fetch_array($result_inve)){ 
									  $insert_sql_array = array();
									  $insert_sql_array[group_inventory_id] =$last_group_id;
									  $insert_sql_array[inventory_id] = $row_inve[inventory_id];
									  $insert_sql_array[name] = $row_inve[name];
									  $insert_sql_array[product_id] = $last_id;
									 
									  $insert_sql_array[inventory_cost_increase] = $row_inve[inventory_cost_increase];
									  $insert_sql_array[inventory_csr] = $row_inve[inventory_csr];
									  $insert_sql_array[inventory_art] = $row_inve[inventory_art];
									  $insert_sql_array[inventory_print] = $row_inve[inventory_print];
									  $insert_sql_array[inventory_dye_sub] = $row_inve[inventory_dye_sub];
									  $insert_sql_array[inventory_cut] = $row_inve[inventory_cut];
									  $insert_sql_array[inventory_sew] = $row_inve[inventory_sew];
									  $insert_sql_array[inventory_shipping] = $row_inve[inventory_shipping];
									 
									  $insert_sql_array[xs_inventory_usage] = $row_inve[xs_inventory_usage];
									  $insert_sql_array[s_inventory_usage] = $row_inve[s_inventory_usage];
									  $insert_sql_array[m_inventory_usage] = $row_inve[m_inventory_usage];
									  $insert_sql_array[l_inventory_usage] = $row_inve[l_inventory_usage];
									  $insert_sql_array[xl_inventory_usage] = $row_inve[xl_inventory_usage];
									  $insert_sql_array['2x_inventory_usage'] = $row_inve['2x_inventory_usage'];
									  $insert_sql_array['3x_inventory_usage'] = $row_inve['3x_inventory_usage'];
									  $insert_sql_array['4x_inventory_usage'] = $row_inve['4x_inventory_usage'];
									  $insert_sql_array['check_clone'] = '1';
									  
									  $this->db->insert(erp_ASSIGN_INVENTORY,$insert_sql_array);
								}
							}
						 }
								
					  $sql_inve="SELECT * FROM ". erp_ASSIGN_INVENTORY ." WHERE product_id ='$product_id' and group_inventory_id='0'";
					  $result_inve=$this->db->query($sql_inve,__FILE__,__LINE__);
					  while($row_inve=$this->db->fetch_array($result_inve)){ 
							  $insert_sql_array = array();
							  $insert_sql_array[group_inventory_id] ='0';
							  $insert_sql_array[inventory_id] = $row_inve[inventory_id];
							  $insert_sql_array[name] = $row_inve[name];
							  $insert_sql_array[product_id] = $last_id;
							 
							  $insert_sql_array[inventory_cost_increase] = $row_inve[inventory_cost_increase];
							  $insert_sql_array[inventory_csr] = $row_inve[inventory_csr];
							  $insert_sql_array[inventory_art] = $row_inve[inventory_art];
							  $insert_sql_array[inventory_print] = $row_inve[inventory_print];
							  $insert_sql_array[inventory_dye_sub] = $row_inve[inventory_dye_sub];
							  $insert_sql_array[inventory_cut] = $row_inve[inventory_cut];
							  $insert_sql_array[inventory_sew] = $row_inve[inventory_sew];
							  $insert_sql_array[inventory_shipping] = $row_inve[inventory_shipping];
							 
							  $insert_sql_array[xs_inventory_usage] = $row_inve[xs_inventory_usage];
							  $insert_sql_array[s_inventory_usage] = $row_inve[s_inventory_usage];
							  $insert_sql_array[m_inventory_usage] = $row_inve[m_inventory_usage];
							  $insert_sql_array[l_inventory_usage] = $row_inve[l_inventory_usage];
							  $insert_sql_array[xl_inventory_usage] = $row_inve[xl_inventory_usage];
							  $insert_sql_array['2x_inventory_usage'] = $row_inve['2x_inventory_usage'];
							  $insert_sql_array['3x_inventory_usage'] = $row_inve['3x_inventory_usage'];
							  $insert_sql_array['4x_inventory_usage'] = $row_inve['4x_inventory_usage'];
							  $insert_sql_array['check_clone'] = '1';
							  
							  $this->db->insert(erp_ASSIGN_INVENTORY,$insert_sql_array);
						}
								
						$sql="SELECT * FROM ". erp_PRODUCT ." WHERE group_product_id ='$product_id'";
				        $result=$this->db->query($sql,__FILE__,__LINE__);
						 while($row=$this->db->fetch_array($result)){
						  
							  $insert_sql_array = array();
							  $insert_sql_array[group_product_id] = $last_id;
							  $insert_sql_array[product_name] = $row[product_name];
							  $insert_sql_array[product_type] = $row[product_type];
							  $insert_sql_array[item_number] = $row[item_number];
							  $insert_sql_array[product_status] = $row[product_status];
							  $insert_sql_array[type] =  $row[type];
							  
							  $insert_sql_array[quantity_6_12] =  $row[quantity_6_12];
							  $insert_sql_array[quantity_13_24] =  $row[quantity_13_24];
							  $insert_sql_array[quantity_25_49] = $row[quantity_25_49];
							  $insert_sql_array[quantity_50_74] = $row[quantity_50_74];
							  $insert_sql_array[quantity_75] = $row[quantity_75];
							  
							  $insert_sql_array[size_xs] = $row[size_xs];
							  $insert_sql_array[size_s] = $row[size_s];
							  $insert_sql_array[size_m] = $row[size_m];
							  $insert_sql_array[size_l] = $row[size_l];
							  $insert_sql_array[size_xl] = $row[size_xl];
							  $insert_sql_array[size_2x] = $row[size_2x];
							  $insert_sql_array[size_3x] = $row[size_3x];
							  $insert_sql_array[size_4x] = $row[size_4x];
							  
							  $insert_sql_array[order_cost_increase] =  $row[order_cost_increase];
							  $insert_sql_array[order_csr] =  $row[order_csr];
							  $insert_sql_array[order_art] = $row[order_art];
							  $insert_sql_array[order_print] = $row[order_print];
							  $insert_sql_array[order_dye_sub] = $row[order_dye_sub];
							  $insert_sql_array[order_cut] = $row[order_cut];
							  $insert_sql_array[order_sew] = $row[order_sew];
							  $insert_sql_array[order_shipping] = $row[order_shipping];
							  
							  $insert_sql_array[per_item_cost_increase] =  $row[per_item_cost_increase];
							  $insert_sql_array[per_item_csr] =  $row[per_item_csr];
							  $insert_sql_array[per_item_art] = $row[per_item_art];
							  $insert_sql_array[per_item_print] = $row[per_item_print];
							  $insert_sql_array[per_item_dye_sub] = $row[per_item_dye_sub];
							  $insert_sql_array[per_item_cut] = $row[per_item_cut];
							  $insert_sql_array[per_item_sew] = $row[per_item_sew];
							  $insert_sql_array[per_item_shipping] = $row[per_item_shipping];
							  
							  $insert_sql_array[per_size_cost_increase] =  $row[per_size_cost_increase];
							  $insert_sql_array[per_size_csr] =  $row[per_size_csr];
							  $insert_sql_array[per_size_art] = $row[per_size_art];
							  $insert_sql_array[per_size_print] = $row[per_size_print];
							  $insert_sql_array[per_size_dye_sub] = $row[per_size_dye_sub];
							  $insert_sql_array[per_size_cut] = $row[per_size_cut];
							  $insert_sql_array[per_size_sew] = $row[per_size_sew];

							  $insert_sql_array[per_size_shipping] = $row[per_size_shipping];
							  $insert_sql_array[check_clone] = '1';
							  
							  $this->db->insert(erp_PRODUCT,$insert_sql_array);
							  $last_id_sub = $this->db->last_insert_id();
						  
						  
						$sql_group="SELECT * FROM ". erp_GROUP_INVENTORY ." WHERE group_id ='$row[product_id]'";
						$result_group=$this->db->query($sql_group,__FILE__,__LINE__);
						if( $this->db->num_rows($result_group) > 0 ){
						   while($row_group=$this->db->fetch_array($result_group)){
							
								$insert_sql_array = array();
								$insert_sql_array[group_id] = $last_id_sub;
								$insert_sql_array[group_name] = $row_group[group_name];
								$insert_sql_array[product_group] = $row_group[product_group];
								$insert_sql_array[check_clone] = '1';
								  
								$this->db->insert(erp_GROUP_INVENTORY,$insert_sql_array);
								$last_group_sub = $this->db->last_insert_id();
						  
								$sql_inve="SELECT * FROM ". erp_ASSIGN_INVENTORY ." WHERE product_id ='$row[product_id]' and group_inventory_id='$row_group[inventory_group_id]'";
								$result_inve=$this->db->query($sql_inve,__FILE__,__LINE__);
								while($row_inve=$this->db->fetch_array($result_inve)){ 
								
									  $insert_sql_array = array();
									  $insert_sql_array[group_inventory_id] = $last_group_sub;
									  $insert_sql_array[inventory_id] = $row_inve[inventory_id];
									  $insert_sql_array[name] = $row_inve[name];
									  $insert_sql_array[product_id] = $last_id_sub;
									 
									  $insert_sql_array[inventory_cost_increase] = $row_inve[inventory_cost_increase];
									  $insert_sql_array[inventory_csr] = $row_inve[inventory_csr];
									  $insert_sql_array[inventory_art] = $row_inve[inventory_art];
									  $insert_sql_array[inventory_print] = $row_inve[inventory_print];
									  $insert_sql_array[inventory_dye_sub] = $row_inve[inventory_dye_sub];
									  $insert_sql_array[inventory_cut] = $row_inve[inventory_cut];
									  $insert_sql_array[inventory_sew] = $row_inve[inventory_sew];
									  $insert_sql_array[inventory_shipping] = $row_inve[inventory_shipping];
									 
									  $insert_sql_array[xs_inventory_usage] = $row_inve[xs_inventory_usage];
									  $insert_sql_array[s_inventory_usage] = $row_inve[s_inventory_usage];
									  $insert_sql_array[m_inventory_usage] = $row_inve[m_inventory_usage];
									  $insert_sql_array[l_inventory_usage] = $row_inve[l_inventory_usage];
									  $insert_sql_array[xl_inventory_usage] = $row_inve[xl_inventory_usage];
									  $insert_sql_array['2x_inventory_usage'] = $row_inve['2x_inventory_usage'];
									  $insert_sql_array['3x_inventory_usage'] = $row_inve['3x_inventory_usage'];
									  $insert_sql_array['4x_inventory_usage'] = $row_inve['4x_inventory_usage'];
									  $insert_sql_array['check_clone'] = '1';
									  
									  $this->db->insert(erp_ASSIGN_INVENTORY,$insert_sql_array);
								 }
						     }
						  }
						   
						$sql_inve="SELECT * FROM ". erp_ASSIGN_INVENTORY ." WHERE product_id ='$row[product_id]' and group_inventory_id='0'";
						$result_inve=$this->db->query($sql_inve,__FILE__,__LINE__);
						while($row_inve=$this->db->fetch_array($result_inve)){ 
					        
							  $insert_sql_array = array();
							  $insert_sql_array[group_inventory_id] = '0';
							  $insert_sql_array[inventory_id] = $row_inve[inventory_id];
							  $insert_sql_array[name] = $row_inve[name];
							  $insert_sql_array[product_id] = $last_id_sub;
							 
							  $insert_sql_array[inventory_cost_increase] = $row_inve[inventory_cost_increase];
							  $insert_sql_array[inventory_csr] = $row_inve[inventory_csr];
							  $insert_sql_array[inventory_art] = $row_inve[inventory_art];
							  $insert_sql_array[inventory_print] = $row_inve[inventory_print];
							  $insert_sql_array[inventory_dye_sub] = $row_inve[inventory_dye_sub];
							  $insert_sql_array[inventory_cut] = $row_inve[inventory_cut];
							  $insert_sql_array[inventory_sew] = $row_inve[inventory_sew];
							  $insert_sql_array[inventory_shipping] = $row_inve[inventory_shipping];
							 
							  $insert_sql_array[xs_inventory_usage] = $row_inve[xs_inventory_usage];
							  $insert_sql_array[s_inventory_usage] = $row_inve[s_inventory_usage];
							  $insert_sql_array[m_inventory_usage] = $row_inve[m_inventory_usage];
							  $insert_sql_array[l_inventory_usage] = $row_inve[l_inventory_usage];
							  $insert_sql_array[xl_inventory_usage] = $row_inve[xl_inventory_usage];
							  $insert_sql_array['2x_inventory_usage'] = $row_inve['2x_inventory_usage'];
							  $insert_sql_array['3x_inventory_usage'] = $row_inve['3x_inventory_usage'];
							  $insert_sql_array['4x_inventory_usage'] = $row_inve['4x_inventory_usage'];
							  $insert_sql_array['check_clone'] = '1';
							  
							  $this->db->insert(erp_ASSIGN_INVENTORY,$insert_sql_array);
						   }
					 }
				echo $last_id;
							
               break;
			case 'delete':
			      $sql = "DELETE FROM ".erp_PRODUCT." WHERE check_clone = '1'";
				  $this->db->query($sql,__FILE__,__LINE__);
				  
				  $sql = "DELETE FROM ".erp_GROUP_INVENTORY." WHERE check_clone = '1'";
				  $this->db->query($sql,__FILE__,__LINE__);
				  
				  $sql = "DELETE FROM ".erp_ASSIGN_INVENTORY." WHERE check_clone = '1'";
				  $this->db->query($sql,__FILE__,__LINE__);
		   
	           break;
			 case 'update':
			      $sql = "update ".erp_PRODUCT." set check_clone= '0' where 1";
		          $this->db->query($sql,__FILE__,__LINE__);
				  
				  $sql = "update ".erp_GROUP_INVENTORY." set check_clone= '0' where 1";
		          $this->db->query($sql,__FILE__,__LINE__);
				  
				  $sql = "update ".erp_ASSIGN_INVENTORY." set check_clone= '0' where 1";
		          $this->db->query($sql,__FILE__,__LINE__);
				  
				  $insert_sql_array = array();
				  $insert_sql_array[product_name] = $product_name;
				  $insert_sql_array[product_type] = $product_type;
				  $insert_sql_array[item_number] = $item_num;
				  $insert_sql_array[product_status] = $product_status;
				  $insert_sql_array[type] =  $p_type;
				  
				  $this->db->update(erp_PRODUCT,$insert_sql_array,'product_id',$product_id);
	           break;
			   
			 case 'check':
			 
			 	$sql="SELECT product_name  FROM ". erp_PRODUCT ." WHERE product_id !='$product_id'";
				$result=$this->db->query($sql,__FILE__,__LINE__);
				while($row=$this->db->fetch_array($result)){ 
					if($product_name==$row[product_name]){
						return 'a';
					}
				}
			 break;
	        }  ///////end of switch
	           $html = ob_get_contents();
			   ob_end_clean();
			   return $html;
	 }   ////////////////end of function add_clone
 }   ///////////end of class
?>