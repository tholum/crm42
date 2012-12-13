<?php
require_once('class/class.ConvertLength.php');

 class PrintDetails extends ConvertLength{
 
	 
	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
	} /////end of function __construct
	
	var $total_inch = 0;
	var $total_inch_c = 0;
    function search_screen(){
	   ob_start();
	   $formName = "frm_search"; ?>
       <form name="<?php echo $formName; ?>" action="" method="post">
          <table class="table" width="100%">
             <tr>
             <td>Color Profile :</td>
               <td colspan="5">
                     <select style="width:100%;" name="select_color" id="select_color" 
							 onchange="javascript:print.work_orders(
                                        document.getElementById('select_color').value,
										document.getElementById('txt_woid').value,
										document.getElementById('select_type').value,
										document.getElementById('start_date').value,
										document.getElementById('end_date').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('show_work_order').innerHTML=response;
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
												);">
                            <option value="">-select-</option>
							<?php
							$sql="select * from ".erp_DROPDOWN_OPTION." where option_name='Profile JV5'";
							//echo $sql;
						    $result=$this->db->query($sql,__FILE__,__LINE__);
							while($row=$this->db->fetch_array($result)){?>
							<option value="<?php echo $row[identifier]; ?>"><?php echo $row[name]; ?></option>
                            <?php } ?>
                 </select>
               </td>
             </tr>
             <tr>
             <td>Order ID :</td>
               <td colspan="5">
                     <input type="text" name="txt_woid" id="txt_woid" 
							onchange="javascript:print.work_orders(
										document.getElementById('select_color').value,
                                        document.getElementById('txt_woid').value,
										document.getElementById('select_type').value,
										document.getElementById('start_date').value,
										document.getElementById('end_date').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('show_work_order').innerHTML=response;
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
												);"/>
               </td>
             </tr>
             <tr>
             <td>Type :</td>
               <td colspan="5">
                     <select style="width:100%;" name="select_type" id="select_type" 
							onchange="javascript:print.work_orders(
                                        document.getElementById('select_color').value,
										document.getElementById('txt_woid').value,
										document.getElementById('select_type').value,
										document.getElementById('start_date').value,
										document.getElementById('end_date').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('show_work_order').innerHTML=response;
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
												);">
                            <option value="">-Select-</option>
                            <?php $sql_type="SELECT * FROM ".TBL_INVENTORY_DETAILS." WHERE type_id = '5'";
							      $result_type=$this->db->query($sql_type,__FILE__,__LINE__);
								  while($row_type=$this->db->fetch_array($result_type)) {?>
							<option value="<?php echo $row_type[inventory_id]; ?>"><?php echo $row_type[name]; ?></option>
                            <?php } ?>
                 </select>
					  <input  type="hidden"  id="count_no" name="count_no" value="1"/>
               </td>
             </tr>
             <tr>
                <th>Due :</th>
                <th>
                    <input  type="text" name="start_date" id="start_date" value="<?php //echo $row_order['event_date']; ?>" size="10"/>
                         <script type="text/javascript">	 
							 function start_cal(){
							 new Calendar({
							 inputField   	: "start_date",
							 dateFormat		: "%Y-%m-%d",
							 trigger		: "start_date",
							 weekNumbers   	: true,
							 bottomBar		: true,				 
							 onSelect		: function() {
                                                    this.hide();
                                                    document.getElementById('start_date').value=this.selection.print("%Y-%m-%d");
                                                    print.work_orders(
													document.getElementById('select_color').value,
													document.getElementById('txt_woid').value,
													document.getElementById('select_type').value,
													document.getElementById('start_date').value,
													document.getElementById('end_date').value,
											       {preloader:'prl',
												   onUpdate: function(response,root){
												   document.getElementById('show_work_order').innerHTML=response;
												   $('#search_table')
												  .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]}) }});		
                                                       }
                                          });			
                                       }
                         start_cal();
                         </script>
               </th>
				   <td>
				       <a href="javascript:void(0);" 
				          onclick="javascript:document.getElementById('start_date').value = '';
						                      print.work_orders(
													document.getElementById('select_color').value,
													document.getElementById('txt_woid').value,
													document.getElementById('select_type').value,
													document.getElementById('start_date').value,
													document.getElementById('end_date').value,
											       {preloader:'prl',
												   onUpdate: function(response,root){
												   document.getElementById('show_work_order').innerHTML=response;
												   $('#search_table')
												  .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]}) }});
						   "><img src="images/trash.gif" border="0" /></a>
				   </td>
                   <th>thru:</th>
                   <th>
                       <input type="text" name="end_date" id="end_date" value="<?php //echo $row_order[ship_date]; ?>" size="10"/>
                         <script type="text/javascript">	
							 function end_cal(){
							 new Calendar({
							 inputField   	: "end_date",
							 dateFormat		: "%Y-%m-%d",
							 trigger		: "end_date",
							 weekNumbers   	: true,
							 bottomBar		: true,				 
							 onSelect		: function() {
                                                    this.hide();
                                                    document.getElementById('end_date').value=this.selection.print("%Y-%m-%d");
                                                    print.work_orders(
													document.getElementById('select_color').value,
													document.getElementById('txt_woid').value,
													document.getElementById('select_type').value,
													document.getElementById('start_date').value,
													document.getElementById('end_date').value,
											       {preloader:'prl',
												   onUpdate: function(response,root){
												   document.getElementById('show_work_order').innerHTML=response;
												   $('#search_table')
												  .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]}) }});		
                                                      }				
                                          });
                                       }
                         end_cal();
                         </script>
               </th>
					<td>
					   <a href="javascript:void(0);" 
				          onclick="javascript:document.getElementById('end_date').value = '';
						                      print.work_orders(
													document.getElementById('select_color').value,
													document.getElementById('txt_woid').value,
													document.getElementById('select_type').value,
													document.getElementById('start_date').value,
													document.getElementById('end_date').value,
											       {preloader:'prl',
												   onUpdate: function(response,root){
												   document.getElementById('show_work_order').innerHTML=response;
												   $('#search_table')
												  .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]}) }});
						"><img src="images/trash.gif" border="0" /></a>
				    </td>
            </tr>
         </table>
</form>
	 <?php    
        $html = ob_get_contents();
		ob_end_clean();
		return $html; 	 
	 } ////end of function search_screen
	 
	 function order_summary(){
		 ob_start();
		 $sql_type = "SELECT distinct name as inventory_name,inventory_id,type_id,measured_in FROM ".TBL_INVENTORY_DETAILS." WHERE type_id = '5'";
		 //echo $sql_type;
		 $result_type = $this->db->query($sql_type,__FILE__,__LINE__); 
		 $total_rows = $this->db->num_rows($result_type); ?>
          <table id="search_table" class="event_form small_text" width="100%">
			<thead>
			   <tr>
				  <th>Fabric</th>
				  <th>Total Yards</th>
				  <th>Earliest Print</th>
				  <th>Earliest Ship</th>
			  </tr>
			</thead>
			<tbody>
			  <?php 
			    if($total_rows > 0 ){
			    while($row_type = $this->db->fetch_array($result_type)) { ?>
                <tr>
              <?php $z = 0;
			    
			    $sql_size = "SELECT a.*, b.*, c.order_id, c.product_id, c.workorder_id, f.ship_date FROM ".erp_ASSIGN_FCT." a, ".erp_ASSIGN_INVENTORY." b, ".erp_PRODUCT_ORDER." c, ".erp_GLOBAL_TASK." d, ".erp_WORK_ORDER." e, ".erp_ORDER." f,".erp_USERGROUP." g  WHERE  g.group_name = 'Print' and d.department_id = g.group_id and  b.inventory_id = '$row_type[inventory_id]' and b.product_id = c.product_id and a.module IN( 'work order','rework order') and c.workorder_id = a.module_id and c.order_id = a.product_id and a.flow_chart_id = d.global_task_id and a.task_status = 'Active'  and a.product_id = e.order_id and a.module_id = e.product_id and e.fabric = '$row_type[inventory_id]' and f.order_id = a.product_id ORDER BY due_date";
			    //echo $sql_size.'<br/>';
				$result_size = $this->db->query($sql_size,__FILE__,__LINE__);
				$total = 0;
				if( $this->db->num_rows($result_size) > 0 ){
					while($row_size = $this->db->fetch_array($result_size)){
					  $inch = 0;
					  //echo $row_size[module].' mod '.$row_type[inventory_name].' ';
					  if( $row_size[module] == 'work order' ){
					    $sql_sub = "select gp_id,product_id from ".erp_PRODUCT_ORDER." where workorder_id = '$row_size[workorder_id]'";
						//echo $sql_sub;
						$result_sub = $this->db->query($sql_sub,__FILE__,__LINE__);
						$row_sub = $this->db->fetch_array($result_sub); 
						
						if( $row_sub[gp_id] == 0 )
						{ $product = $row_size[workorder_id]; }
						else
						{ $product = $row_sub[gp_id]; }
						
					    
						$sql_inch = "SELECT size,quantity FROM ".erp_SIZE." WHERE product_id = '$product' and order_id = '$row_size[order_id]'";
						//$sql_inch = "SELECT size,quantity FROM erp_size WHERE product_id = '28' and order_id = '7'"; 
						//echo $sql_inch.'<br/>';
						$result_inch = $this->db->query($sql_inch,__FILE__,__LINE__);
						
						while( $row_inch = $this->db->fetch_array($result_inch) ){
							$size_array = explode("_",$row_inch[size]);
							$field_name = strtolower($size_array[1]).'_inventory_usage';
							
							$sql_consumable = "Select $field_name from ".erp_ASSIGN_INVENTORY." where product_id = '$row_sub[product_id]' and group_inventory_id='0' and inventory_id = '$row_type[inventory_id]'";
							//echo $sql_consumable.'<br>';
							$result_consumable = $this->db->query($sql_consumable,__FILE__,__LINE__);
							$row_consumable = $this->db->fetch_array($result_consumable);
							
							$inch += ($row_consumable[$field_name] * $row_inch[quantity]);
						 }
					  } elseif( $row_size[module] == 'rework order' ) {
				 
						 $sql_r = "SELECT distinct rework_id, fabric_scrap FROM ".erp_REWORK." WHERE product_id = '$row_size[module_id]' and order_id = '$row_size[order_id]'";
						 $result_r = $this->db->query($sql_r,__FILE__,__LINE__);
						 while( $row_r = $this->db->fetch_array($result_r) ){
						   $inch += $row_r[fabric_scrap];						   
						 }
				       }
					   $total += $inch;
					  // echo 'bbb'.$total.'<br>';
						 //echo $row_type['inventory_name'].'<br/>product-->'.$row_size[product_id].' total-->'.$total.' order->'.$row_size[order_id].'<br/>';
						 if( $z == 0 ){
							 $earliest_print = $row_size[due_date];
							 $earliest_print1 = $row_size[due_date];
							 $earliest_ship1 =  $row_size[ship_date];
							 $earliest_ship =  $row_size[ship_date];
						 } else {
						     $earliest_ship2 = $row_size[ship_date];
							 $earliest_print2 = $row_size[due_date];
							 
							 if( $earliest_ship1 <= $earliest_ship2 ){
							     $earliest_ship = $earliest_ship1;
								 
							 } else {
							     $earliest_ship1 = $earliest_ship2;
								 $earliest_ship = $earliest_ship2;
							   }
							   
							  
							 if( $earliest_print1 <= $earliest_print2 ){
							     $earliest_print = $earliest_print1;
								 
							 } else {
							     $earliest_print1 = $earliest_print2;
								 $earliest_print = $earliest_ship2;
							   }
							   
						  }
							 $z++;
					 } /////end of while
						 echo '<td>'.$row_type[inventory_name].'</td>';
						 echo '<td>'.round($this->convert($row_type['measured_in'],"Yards",$total),2).'</td>';
						 echo '<td>'.$earliest_print.'</td>';
						 echo '<td>'.$earliest_ship.'</td>';
					   }//end of if ?>
				</tr> 
				<?php } //end of while 
				} /////end of if
			else
			{
			?>
			<tr><td colspan="4" align="center">No Record Found!!!!</td></tr>
			<?php
			} //end of else
			?>
			</tbody>
		</table>
		 
	 <?php    
        $html = ob_get_contents();
		ob_end_clean();
		return $html;
	 } /////end of function order_summary
	 
	 function create_group(){
		  ob_start();
		  ?>
			<script>
				var id=0;
				var total_inches = 0;
				var len = document.frm_work_order.group_id.length;
				//alert(len);
				for(i=0;i<len;i++){
					if(eval("document.frm_work_order.group_id[" + i + "].checked") == true)
						{ 
						val=eval("document.frm_work_order.group_id[" + i + "].value");
						//alert(val);
						id=id+','+ val;	
						//alert(id);				
						}
				}
				if(len>0){
				 document.getElementById('hide').value=id;
				}else{
				document.getElementById('hide').value=1;
				}
				//alert(document.getElementById('hide').value);
			
			</script>
			<?php
			$html=ob_get_contents();
			ob_end_clean();
			return $html;
			}
			
	function hide_value($check='',$hidden_value='',$g_id='',$measured_in=''){
	   ob_start();
	   switch($check){
		  case 'insert' :
			$id=$hidden_value;
			if($g_id != ''){
				$sql_del="DELETE FROM ".erp_GROUP." WHERE group_id = '$g_id'";
				mysql_query($sql_del);
			}
			$sql_del="DELETE FROM ".erp_GROUP." WHERE workorder_id =''";
			mysql_query($sql_del);
			$sql_gd_id="SELECT * FROM " . erp_GROUP . " ORDER BY g_id DESC ";
			$result_id =$this->db->query($sql_gd_id);
			$row_gd_id=$this->db->fetch_array($result_id);
			
			$gd_id=$row_gd_id[group_id];
			
			if($gd_id==0)
			   $gd_id=1;
			else
			   $gd_id +=1;
			
			echo $id;   
			$seperate = explode(",",$id);
			$len= count($seperate);
			$inven_name;
			$insert_sql_array = array();
			for($i=1;$i<$len;$i++){
				$d1=explode("_",$seperate[$i]);
                                if( count( $d1 ) > 1 ){
									$this->total_inch += $d1[3];
									$inven_name = $d1[2];	
																
									$fct_ids = explode('-',$d1[5]);
									for($j=0;$j<(count($fct_ids));$j++){
										if($fct_ids[$j]){
											$insert_sql_array[group_id] = $gd_id;
											$insert_sql_array[assign_fct_id] = $fct_ids[$j];
											$insert_sql_array[type] = $d1[4];
											$insert_sql_array[rework_id] = $d1[7];
											$insert_sql_array[fabric_id] = $d1[6];
											$insert_sql_array[inches] = $d1[3];
											$insert_sql_array[created] = date("y-m-d");
											$insert_sql_array[inventory_name] = $d1[2];
											$insert_sql_array[workorder_id] = $d1[0];
											$insert_sql_array[order_id] = $d1[1];
		
											$this->db->insert(erp_GROUP,$insert_sql_array);
										}
									}
                                }
			}
			//echo $this->total_inch;
			
			$total_inches = $this->total_inch;
 			$sql_in = "update ".erp_GROUP." set total_inch = '$total_inches' where group_id = '$gd_id'";
			$result_in = $this->db->query($sql_in,__FILE__,__LINE__);
			
		  break;
		  case 'check' :
		   if($hidden_value!=1){
		     $seperate = explode(",",$hidden_value);
			 $len= count($seperate);
			 $inve_name = 'a';
			 $n = 0;
			 
			 for( $i=1;$i<$len;$i++ ){
			 
				$d1=explode("_",$seperate[$i]);
				$inve_name .= ','.$d1[2];
			 }
			 
			 $w = array();
			 $w = explode(",",$inve_name);
			 $tot=count($w);
			 echo  $tot;
			 }else{
			 echo '1';
			 }
			 /*for( $i=1;$i<($len-1);$i++ ){
			 //echo $w[$i];
				if( $w[1] == $w[$i+1] ){
				
			     } else { $n = 1; }
			   
			  }//end of for
			  //print_r($w);
			  //print_r($inve_name);
			  echo $n;*/
		 break;
		 /*case 'check_inserted' :
		     $seperate = explode(",",$hidden_value);
			 $len= count($seperate);
			 $product = 0;
			 $order = 0;
			 $n = 0;
			 //echo 'b';
			 for( $i=1;$i<$len;$i++ ){
			 
				$d1=explode("_",$seperate[$i]);
				$product += ','.$d1[0];
				$order += ','.$d1[1];
			 }
			 $p = explode(",",$product);
			 $o = explode(",",$order);
			 
			 $sql_group = "SELECT workorder_id,order_id FROM ".erp_GROUP;
			 $result_group = $this->db->query($sql_group,__FILE__,__LINE__);
			 while($row_group = $this->db->fetch_array($result_group)){
			 
			 for( $i=1;$i<$len;$i++ ){
			   if($row_group[workorder_id] == $p[$i] and $row_group[order_id] == $o[$i]){
			      $m =1;
				}  
			   }//end of for
			  }//end of while
			  $n = $m;
		 break;*/
		}
	    $html=ob_get_contents();
		ob_end_clean();
		return $html;
     }/////end of function hide_value
	 
	 function work_orders($color_profile='',$order_id='',$inventory_id='',$due_date='',$thru_date='',$inventory_name='',$g_id='',$module_id='',$inches='',$flag='',$checked='',$grouped='',$measured_in=''){
		ob_start();
		$sql_type = "SELECT group_id FROM ".erp_USERGROUP." WHERE group_name = 'Print'";
		$result_type = $this->db->query($sql_type,__FILE__,__LINE__);
		$row_type = $this->db->fetch_array($result_type);
		
        $sql_print = "SELECT distinct a.chart_assign_id, a.due_date,a.module_id,a.module, d.*, e.fabric, e.profile_JV5, e.product_id, f.name, f.inventory_id,f.measured_in FROM ".erp_ASSIGN_FCT." a, ".erp_GLOBAL_TASK." b, ".erp_ORDER." d, ".erp_WORK_ORDER." e, ".TBL_INVENTORY_DETAILS." f WHERE a.flow_chart_id = b.global_task_id and a.task_status = 'Active' and b.department_id = '$row_type[group_id]' and a.module IN ('work order','rework order') and a.product_id = d.order_id and d.order_id = e.order_id and a.module_id = e.product_id and e.fabric = f.inventory_id ";
		
		if($color_profile){
		   $sql_print .= " and e.profile_JV5 = '$color_profile' " ;   }
		
		if($inventory_id){
		   $sql_print .= " and f.inventory_id = '$inventory_id' " ;   }
		
		if($order_id){
		   $sql_print .= " and d.order_id = '$order_id' " ;   }
		
		if($due_date != '' and $thru_date == ''){
		   $sql_print .=" and a.due_date >= '$due_date' "; }
			
		if($due_date == '' and $thru_date != ''){
		   $sql_print .=" and a.due_date <= '".date("Y-m-d",strtotime(' +1 day',strtotime($thru_date)))."' "; }
			
		if($due_date != '' and $thru_date != ''){
		   $sql_print .=" and a.due_date >= '$due_date' and a.due_date <='".date("Y-m-d",strtotime(' +1 day',strtotime($thru_date)))."' "; }
		
	   if($module_id){
			$sql="select workorder_id from ".erp_GROUP." where inventory_name='$inventory_name'";
			$result = $this->db->query($sql,__FILE__,__LINE__);
			if($this->db->num_rows($result)>0){
			while($row = $this->db->fetch_array($result)){
				$s .= $row[workorder_id].',';
				
			}
			$fabric_ids = substr($s,0,-1);
			$sql_print .= " and a.module_id NOT in ($fabric_ids)";
			}
		}
		$sql_print.= " ORDER BY e.work_order_id" ;   
		//echo $sql_print;
	
		$result_order = $this->db->query($sql_print,__FILE__,__LINE__);
		$total_rows = $this->db->num_rows($result_order);
		if($flag == '1'){
			$this->total_inch_c = round($this->convert($measured_in,"Yards",$inches),2);
			?><script> document.getElementById('<?php echo $checked;?>').checked = 'checked'; </script><?php
		}
		?>
        
        <form method="post" name="frm_work_order" action="addgroup.php">
		 <input  type="hidden"  id="hide" name="hide"/>
		 
		 <input  type="hidden"  id="hide_inches" name="hide_inches"/>		 

		 <input style="display:none;" type="button" name="addgroup" id="addgroup" value="Add To Group"
							            onclick="javascript:print.create_group(
							                           {preloader:'prl',
													    onUpdate:function(response,root){
                                                        print.hide_value('check',
														                 document.getElementById('hide').value,
																		 '',
															 {preloader:'prl',
															  onUpdate:function(response,root){


															 
															  if(response == 2){
																  alert('**Please Select more than one inventory to Build a Group!!');
																  var len = document.frm_work_order.group_id.length;
				
																  for(i=0;i<len;i++){
																	document.frm_work_order.group_id[ i ].checked = false;
																   }
																  return false;
																 }
															 else{
															    				  
																print.hide_value('insert',
																				 document.getElementById('hide').value,
																				 '<?php echo $g_id;?>',
																				 {preloader:'prl',
																				  onUpdate:function(response,root){
																print.work_orders({preloader:'prl',
																				  target:'show_work_order'});
																print.groups({preloader:'prl',
																			 target:'group_show'});
																				 }}); } }});
                                                    }});"/>
		<input type="button" name="creategroup" id="creategroup" value="Create Group" 
							 onclick="javascript:print.create_group(
							                           {preloader:'prl',
													    onUpdate:function(response,root){
														print.hide_value('check',
														                 document.getElementById('hide').value,
																		 '',
															 {preloader:'prl',
															  onUpdate:function(response,root){
															  if(response == 2){
																  alert('Please Select more than one inventory to Build a Group!!');
																  var len = document.frm_work_order.group_id.length;
				
																  for(i=0;i<len;i++){
																	document.frm_work_order.group_id[ i ].checked = false;
																   }
																  return false;
																 }
																 else if(response==1){
																    alert('you cant make group of single inventory!!');
																	print.work_orders({preloader:'prl',
																					  target:'show_work_order'});
																	print.groups({preloader:'prl',
																				 target:'group_show'});
																 }
																 else{
																					  
																	print.hide_value('insert',
																					 document.getElementById('hide').value,
																					 '',
																					 '<?php echo $measured_in; ?>',
																					 {preloader:'prl',
																					  onUpdate:function(response,root){
																	print.work_orders({preloader:'prl',
																					  target:'show_work_order'});
																	print.groups({preloader:'prl',
																				 target:'group_show'});
																				 }}); } }});
                                                    }});"/>
			
			Group Has Print Size:<span id="inches"><?php echo $this->total_inch_c;?></span>
								
        <table id="search_table" class="event_form small_text" width="100%">
			<thead>
			   <tr>
			      <th>Order ID</th>
				  <th>Type</th>
				  <th colspan="2">WO ID</th>
				  <th>Name</th>
				  <th>Profile</th>
				  <th>Yards</th>
                  <th>Due To Print</th>
				  <th>Ship Date</th>
			  </tr>
			</thead>
			<tbody>
			<?php 
				
			  if(($this->db->num_rows($result_order) > 0)){
				  $j = 0 ;
			  while( $row_order = $this->db->fetch_array($result_order) ){			  
			      $inch = 0;
				  $rework_id = '';
				  if( $row_order[module] == 'work order' ){
						$sql_sub = "select gp_id,product_id from ".erp_PRODUCT_ORDER." where workorder_id = '$row_order[module_id]'";
						//echo $sql_sub;
						$result_sub = $this->db->query($sql_sub,__FILE__,__LINE__);
						$row_sub = $this->db->fetch_array($result_sub);
						
						if( $row_sub[gp_id] == 0 )
						{ $product = $row_order[module_id]; }
						else
						{ $product = $row_sub[gp_id]; }
				  
				  
					 $sql_inch = "SELECT size,quantity FROM ".erp_SIZE." WHERE product_id = '$product' and order_id = '$row_order[order_id]'";
					 //$sql_inch = "SELECT size,quantity FROM erp_size WHERE product_id = '28' and order_id = '7'";
					 //echo $sql_inch;
					 $result_inch = $this->db->query($sql_inch,__FILE__,__LINE__);					 
					 while( $row_inch = $this->db->fetch_array($result_inch) ){
							$inch_array = explode("_",$row_inch[size]);
							$field_name = strtolower($inch_array[1]).'_inventory_usage';
							
							$sql_consumable = "Select $field_name from ".erp_ASSIGN_INVENTORY." where product_id = '$row_sub[product_id]' and group_inventory_id='0' and inventory_id = '$row_order[inventory_id]'";
							//echo $sql_consumable.'<br>';
							$result_consumable = $this->db->query($sql_consumable,__FILE__,__LINE__);
							$row_consumable = $this->db->fetch_array($result_consumable);
							$inch += ($row_consumable[$field_name] * $row_inch[quantity]);
					 }
					// echo '<>'.$inch;
				 } else {
				 
				     $sql_re = "SELECT distinct rework_id, fabric_scrap FROM ".erp_REWORK." WHERE product_id = '$row_order[module_id]' and order_id = '$row_order[order_id]'";
					 //echo $sql_re;
					 $result_re = $this->db->query($sql_re,__FILE__,__LINE__);
					 $row_re = $this->db->fetch_array($result_re);
				     $inch = $row_re[fabric_scrap];
					 $rework_id = $row_re[rework_id];
					 //echo 'aaaa'.$inch;
					 
					 $sql_su = "select product_id from ".erp_PRODUCT_ORDER." where workorder_id = '$row_order[module_id]'";
					 
					 $result_su = $this->db->query($sql_su,__FILE__,__LINE__);
					 $row_su = $this->db->fetch_array($result_su);
				   }
				 
				 $k = 0;
				 $m = 0;
				 $z = 0;
				 if( $inventory_name == '' or $checked != '' ){
					 $sql_group = "SELECT workorder_id FROM ".erp_GROUP." WHERE order_id = '$row_order[order_id]' and workorder_id = '$row_order[module_id]' and type = '$row_order[module]' and assign_fct_id = '$row_order[chart_assign_id]'";
					 //echo $sql_group;
					 $result_group = $this->db->query($sql_group,__FILE__,__LINE__);
					 if($this->db->num_rows($result_group)){
					    if( $inventory_name == '' ){ $z = 1; }
						if( $checked != '' ){ $m = 1; }
					 }
				 }
				 
				 if($grouped){
					$sql_gp = "SELECT assign_fct_id, type, workorder_id, order_id FROM ".erp_GROUP." WHERE group_id <> '$grouped'";
					//echo $sql_gp;
					$result_gp = $this->db->query($sql_gp,__FILE__,__LINE__);
					if( $this->db->num_rows($result_gp) > 0 ){
						while($row_gp = $this->db->fetch_array($result_gp)){
						
							if( $row_gp[type] == $row_order[module] and $row_gp[workorder_id] == $row_order[module_id] and $row_gp[order_id] == $row_order[order_id] and $row_gp[assign_fct_id] == $row_order[chart_assign_id] ){
							$k = 1;
							}
						}
					}
				}
				 if( $z == 0 ){
				     $y = 1;

					if(($k == 0 and $grouped) or ($m==0 and !$grouped)){
                ?>				
				<tr>
					<input  type="hidden"  id="inventory" name="inventory" value="<?php echo $row_order[name];?>"/>
					
				    <td><?php echo $row_order[order_id]; ?></td>
					<td><?php echo $row_order[module]; ?></td>
					<td>
					  <?php if( $inventory_name != '' and $inventory_name == $row_order[name] ){ 
					  		$fct_id = '';
							$sql_assign_fct = "Select * from ".erp_ASSIGN_FCT." where module = '$row_order[module]' and module_id = '$row_order[module_id]' and product_id = '$row_order[order_id]' and task_status = 'Active'";
					  		//echo $sql_assign_fct;
							$result_assign_fct = $this->db->query($sql_assign_fct,__FILE__,__LINE__);	
							while($row_assign_fct = $this->db->fetch_array($result_assign_fct)){
								$fct_id .= $row_assign_fct[chart_assign_id].'-';
							}
							?>
					        <input type="checkbox" name="group_id" id="<?php echo $row_order[module_id].'_'.$row_order[order_id].'_'.$row_order[module].'_'.$row_order[chart_assign_id];?>" value="<?php echo $row_order[module_id].'_'.$row_order[order_id].'_'.str_replace(',', "", $row_order[name]).'_'.round($this->convert($row_order['measured_in'],"Inches",$inch),2).'_'.$row_order[module].'_'.$fct_id.'_'.$row_order[inventory_id].'_'.$rework_id; ?>" 
							 			onclick="javascript:
                                        if(this.checked){
										print.total_inches(document.getElementById('inches').innerHTML,
															'<?php echo $inch;?>',
															'',
															'<?php echo $row_order['measured_in']; ?>',
															{preloader:'prl',
															  onUpdate: function(response,root){
															  document.getElementById('inches').innerHTML = response;}});}
												else{
                                                      var len = document.frm_work_order.group_id.length;
                                                      var c = 'false';
                                                         for(i=0;i<len;i++){
                                                            if(document.frm_work_order.group_id[ i ].checked){
                                                                c= 'true';
                                                               }}
                                                         if(c == 'true'){
										 print.total_inches(document.getElementById('inches').innerHTML,
															'<?php echo $inch;?>',
															'uncheck',
															'<?php echo $row_order['measured_in']; ?>',
															{preloader:'prl',
															  onUpdate: function(response,root){
															  document.getElementById('inches').innerHTML = response;
															  }});}
															else{
                                                              	print.work_orders({preloader:'prl',target:'show_work_order'});
                                                              }} "/>
																			  
				<?php
					} else if( $inventory_name == '' and $inventory_name != $row_order[name] ){ ?>
					
					           <input type="checkbox" name="group_id" id="<?php echo $row_order[module_id].'_'.$row_order[order_id].'_'.$row_order[module].'_'.$row_order[chart_assign_id];?>"
							    value="<?php echo $row_order[module_id].'_'.$row_order[order_id].'_'.$row_order[name].'_'.$inch.'_'.$row_order[module].'_'.$row_order[chart_assign_id]; ?>" 
								onclick="javascript:print.work_orders('',
														  '',
														  '<?php echo $row_order[inventory_id]; ?>',
														  '',
														  '',
														  '<?php echo $row_order[name]; ?>',
														  '',
														  '<?php //echo $row_order[module_id];?>',
														  '<?php echo $inch;?>',
														  '1',
                                                          '<?php echo $row_order[module_id].'_'.$row_order[order_id].'_'.$row_order[module].'_'.$row_order[chart_assign_id];?>',
														  '',
														  '<?php echo $row_order['measured_in']; ?>',
														 {preloader:'prl',
														  onUpdate: function(response,root){
														  document.getElementById('show_work_order').innerHTML = response;
														  document.getElementById('<?php echo $row_order[module_id].'_'.$row_order[order_id].'_'.$row_order[module].'_'.$row_order[chart_assign_id]; ?>').checked = true;
													}});" />
					   <?php } ?>
					   
					</td>
					<td><?php echo $row_order[module_id].'-';
					         if($row_order[module] == 'work order'){ echo $row_sub[product_id]; }
							 else{ echo $row_su[product_id]; }?></td>
                    
					<td><?php echo $row_order[name]; ?></td>


                      <input type="hidden" name="hidden_inches" id="in_<?php echo $row_order['module_id'] ;?>" value="<?php if($inch!=''){ echo $inch; } else { echo '#'; } ?>"/>
					<td>
					  <?php if($row_order[profile_JV5]!=''){
								$sql="SELECT name from ".erp_DROPDOWN_OPTION." WHERE option_name = 'Profile JV5' and identifier = '$row_order[profile_JV5]'";
								$result = $this->db->query($sql,__FILE__,__LINE__);
								$row=$this->db->fetch_array($result);
								echo $row[name];
							} else { echo '#'; } ?>
					</td>
					<td><?php if($inch!=''){
								   if( $row_order[module] == 'work order' ){
								       echo round($this->convert($row_order['measured_in'],"Yards",$inch),2);
							       } elseif( $row_order[module] == 'rework order' ) { echo round($this->convert($row_order['measured_in'],"Yards",$inch),2);  }
							  } else { echo '#'; } ?></td>
					<td><?php echo $row_order[due_date]; ?></td>
                    <td><?php echo $row_order[ship_date]; ?></td>
			    </tr> 
				<?php   }
				      }
				    } //end of while 
				if( $z == 1 && $y != 1 ){?>
				<tr><td colspan="8">No Inventory Is Left To Creat A Group!!!!</td></tr>
			    <?php }
				} //end of if
			else{ ?>
				<tr><td colspan="8" align="center">No Records Found!!!!</td></tr>
			<?php
			    } //end if else
			?>
			</tbody>
	  	 </table>
		</form>
	    <?php    
        $html = ob_get_contents();
		ob_end_clean();
		return $html;	 
	 } /////end of function work_orders
	 
	 function total_inches($total='',$inch='',$check='',$measured_in=''){
	 	ob_start();
		$inch = round($this->convert($measured_in,"Yards",$inch),2);
		if($check == ''){
			$this->total_inch_c = $total+$inch;}
		else{
			$this->total_inch_c = $total-$inch;
		}
		echo $this->total_inch_c;
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	 }
	 
	 function display_fabricrolls(){
		 ob_start();
		 ?>
		 
		 <table id="fabric_table" class="event_form small_text" width="100%">
			<thead>
			   <tr>
				  <th>Fabric</th>
				  <th>Roll #</th>
				  <th>Yards</th>
                  <th>Location</th>
			  </tr>
			</thead>
			<tbody>
			  <?php 
				$sql_type= "select * from ".TBL_FABRIC_ROLLS." ORDER BY created_date DESC ";
				//echo $sql_type;
				$result_type=$this->db->query($sql_type,__FILE__,__LINE__);
				if( $this->db->num_rows($result_type)>0 ){
					while($row_type=$this->db->fetch_array($result_type)){?>
					<tr>
					<?php
					$sql_fabric= "select * from ".TBL_INVENTORY_DETAILS." where inventory_id = '$row_type[fabric_type]' ";
					$result_fabric=$this->db->query($sql_fabric,__FILE__,__LINE__);
					$row_fabric=$this->db->fetch_array($result_fabric);
					?>
					    <td><?php echo $row_fabric['name']; ?></td>
						<td><?php echo $row_type['id']; ?></td>
						<td>
                             <span id="inches_box_<?php echo $row_type['id']; ?>">
						       <?php echo $this->returnLink(round(($row_type[inches]*0.027778),2),'inches_box_'.$row_type[id],'inch',$row_type[id]); ?>
                             </span>
                        </td>
						<td>
                             <span id="location_box_<?php echo $row_type['id']; ?>">
						       <?php echo $this->returnLink($row_type[location_id],'location_box_'.$row_type[id],'location',$row_type[id]); ?>
                             </span>
                        </td>
                   </tr> 
                <?php
					} 
				}
				else
				{
				?>
					<tr><td colspan="4">No Record Found!!!!</td></tr>
				<?php
				}
				?>
			</tbody>
		 </table>
		<?php
        $html = ob_get_contents();
		ob_end_clean();
		return $html;
	}  /////end of function display_fabricrolls
	
	function add_fabricrolls($runat='', $fabric_type='', $inches='', $location=''){
		ob_start();
		switch($runat){
		case 'local':
		$FormName='add_fabricroll'; ?>
		<form name="add_fabricroll" method="post" enctype="multipart/form-data" action="">
			<table class="table" width="100%">
				<tr>
					<th>Fabric:</th>
                    <td>
                    <?php 
					$sql="select * from ".TBL_INVENTORY_DETAILS." where type_id='5' and status = 'Active'";
					$result = $this->db->query($sql,__FILE__,__LINE__);
					?>
                     <select name="fabric_type" id="fabric_type" style="width:75px">
							<option value="">-Select-</option>
							<?php while( $row = $this->db->fetch_array($result)){?>
							<option value="<?php echo $row[inventory_id]; ?>"><?php echo $row[name]; ?></option>
							<?php } ?>
					 </select> 
                    </td>
					<!--<td><input type="text" name="fabric_type" id="fabric_type" /></td>-->
					<th>Inches:</th>
					<td><input type="text" name="inches" id="inches" /></td>
					<th>Location:</th>
					<td><input type="text" name="location" id="location" /></td>
				</tr>
				<tr>
					<td><input id="add_fabric_rolls" name="add_fabric_rolls" value="add" type="button" size="2px" 
                               onClick="javascript:print.add_fabricrolls('server',
                                                                         document.getElementById('fabric_type').value,
                                                                         document.getElementById('inches').value,
                                                                         document.getElementById('location').value, 	  
                                                                        {preloader:'prl',
                                                                        onUpdate: function(response,root){
                                                                        print.display_fabricrolls(
                                                                        {onUpdate: function(response,root){
																	  document.getElementById('fabric_table').innerHTML=response;
                                                                      $('#search_table')
											          .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] });
																	  	}});}});
													   print.addFabricRolls('rolls',
														     {preloader:'prl',target:'add_fabric'});
																		">
                   </td>
                   <td colspan="5">&nbsp;</td>
				</tr>
			</table>
	    </form>
        <?php
		break;     
		case 'server':
	    extract($_POST); ?>
		    <script>alert("the fabric roll is being added");
            //document.getElementById('add_fabric').style.display='none';</script>
        <?php
		          $insert_sql_array = array();
		          $insert_sql_array[fabric_type] = $fabric_type;
				  $insert_sql_array[inches] = $inches;
				  $insert_sql_array[location_id] = $location;
				  $insert_sql_array[created_date] = date("y-m-d");

				  $this->db->insert(TBL_FABRIC_ROLLS,$insert_sql_array);
	    break;
		} ///end of switch
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}/////end of function add_fabricrolls
	
	function addFabricRolls( $runat='' ){
	   ob_start();
	   switch( $runat ){
	   case 'rolls':?>
	   <a href="javascript:void(0);" onClick="javascript:print.add_fabricrolls('local',
                                                                     {onUpdate: function(response,root){
																	  document.getElementById('add_fabric').innerHTML=response;
																	  document.getElementById('add_fabric').style.display='';
																	 },preloader:'prl'});">add</a>
	   
	
	<?php
	   break;
	  case 'printer': ?>
	  <a href="javascript:void(0);" onClick="javascript:print.add_printer('local',
                                                                     {onUpdate: function(response,root){
																	  document.getElementById('add_printer').innerHTML=response;
																	  document.getElementById('add_printer').style.display='';
																	 },preloader:'prl'});">add</a>
	<?php
	}
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
	}
	
	function add_printer($runat='', $printer='', $paper=''){
		ob_start();
		switch($runat){
		case 'local':
		$FormName='add_printer'; ?>
		<form name="add_printer" method="post" enctype="multipart/form-data" action="">
			<table class="table" width="100%">
				<tr>
					<th>Printer:</th>
                   <td><input type="text" name="printer_name" id="printer_name" /></td>
					<th>Paper:</th>
					<td><input type="text" name="Paper" id="Paper" /></td>
				</tr>
				<tr>
					<td><input id="add_printer" name="add_printer" value="add" type="button" size="2px" 
                               onClick="javascript:print.add_printer('server',
                                                                         document.getElementById('printer_name').value,
                                                                         document.getElementById('Paper').value,
                                                                         {preloader:'prl',
                                                                        onUpdate: function(response,root){
                                                                        print.printer(
                                                                        {onUpdate: function(response,root){
																	  document.getElementById('printer_table').innerHTML=response;
													   print.addFabricRolls('printer',
														         {preloader:'prl',
																 target:'add_printer'});
																  $('#search_table')
											          .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] });
																	  	}});}});">
                   </td>
                   <td colspan="5">&nbsp;</td>
				</tr>
			</table>
	    </form>
        <?php
		break;     
		case 'server':
	    extract($_POST); ?>
		    <script>alert("The printer is being added");
            //document.getElementById('add_printer').style.display='none';</script>
        <?php
		          $insert_sql_array = array();
		          $insert_sql_array[printer] = $printer;
				  $insert_sql_array[paper] = $paper;
				 
				  $this->db->insert(erp_PRINTER_PAPER,$insert_sql_array);
	    break;
		} ///end of switch
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}/////end of function add_printer
	  
	  function groups(){
		  ob_start(); ?>
		  <table id="search_table" class="event_form small_text" width="100%">
			<thead>
			   <tr>
				  <th width="18%">Group Id</th>
				  <th width="20%">Fabric</th>
				  <th width="16%">Yards</th>
				  <th width="32%">Created Date</th>
				  <th width="14%">&nbsp;</th>
			  </tr>
			</thead>
			<tbody>
			  <?php
                          //echo erp_GROUP;
			  $sql = "SELECT distinct a.group_id,a.inventory_name,a.total_inch,a.created,b.measured_in FROM ".erp_GROUP." a, ".TBL_INVENTORY_DETAILS." b where a.fabric_id = b.inventory_id and b.type_id = '5' ORDER BY group_id ASC";
			  //echo $sql;
			  $result = $this->db->query($sql,__FILE__,__LINE__);
			  $total_rows=$this->db->num_rows($result);	
				 if( $total_rows > 0 ){
				 while( $row = $this->db->fetch_array($result) ){?>
				 <tr>
					<td>
					  <a href="javascript:void(0);" 
					     onclick="if(validation()){print.work_orders('',
						                                             '',
																	 '',
																	 '',
																	 '',
																	 '<?php echo $row['inventory_name'];?>',
																	 '<?php echo $row['group_id'];?>',
																	 '',
																	 '<?php echo $row['total_inch'];?>',
																	 '1',
																	 '',
																	 '<?php echo $row['group_id'];?>',
																	 'I',
														  {preloader:'prl',
														   onUpdate: function(response,root){
														   document.getElementById('show_work_order').innerHTML = response;
														   document.getElementById('creategroup').style.display='none';
														   document.getElementById('addgroup').style.display='block';
														   
						                                   print.editgroup('<?php echo $row['group_id'];?>',
						                                                   '<?php echo $row['inventory_name'];?>',
						                                          {preloader:'prl'});
													 }});}"><?php echo $row['group_id'];?></a>
					</td>	  
					<td><?php echo $row['inventory_name'];?></td>
					<td><?php echo round($this->convert("Inches","Yards",$row['total_inch']),2);?></td>
					<td><?php echo $row['created'];?></td>
					<td><a href="javascript:void(0);" onClick="javascript:
									  if(confirm('Are You Sure to Delete ?')){
									  		print.delete_group('<?php echo $row['group_id'];?>',
																		{preloader:'prl',
																	    onUpdate: function(response,root){ 
											print.work_orders({preloader:'prl',
																				  target:'show_work_order'});
											print.groups({preloader:'prl',
														             target:'group_show'});                                              
																  }});
									  	}
									   else{ return false; } ">
									  <img src="images/trash.gif" border="0" /></a> </td>	
				</tr>
               	<?php }
				} else { ?>
				<tr>
				  <td colspan="5" align="center"> No Records Found !!</td>
				</tr>
				<?php } ?>
			</tbody>
		 </table>
		 <script>
		 function validation()
		{
				document.getElementById('addgroup').style.display='block';
				
				return true;
		}
		</script>
		 
	  <?php
        $html = ob_get_contents();
		ob_end_clean();
		return $html;
	  }  /////end of function groups
	  
	 function delete_group($group_id)
	{
		$sql="delete from ".erp_GROUP." where group_id='$group_id'";
		$result=$this->db->query($sql,__FILE__,__LINE__);
	}
	  
	  function editgroup($group_id='',$inventory_name=''){
		 ob_start();
		  $sql_edit = "SELECT assign_fct_id, type, workorder_id, order_id FROM ".erp_GROUP." WHERE group_id = '$group_id'";
		  $result_edit =  $this->db->query($sql_edit,__FILE__,__LINE__);
			echo '$result_edit';
		  if( $this->db->num_rows($result_edit) > 0 ){
			  $j=0;
			  while( $row_edit = $this->db->fetch_array($result_edit) ){ //$j++;
				  /*$fabric=$row_edit['workorder_id'];
				  $id=explode("_",$fabric);
				  $fabric_id=$id[0];
				  $order_id=$id[1];
				  
				  $sql="SELECT a.*,b.*,c.* FROM erp_work_order a,erp_inventory_details b,erp_create_group c WHERE a.product_id = $fabric_id and a.order_id = $order_id and a.fabric = b.inventory_id ";
				  $result = $this->db->query($sql,__FILE__,__LINE__);
				  $row=$this->db->fetch_array($result);*/
				  
				  ?>
				  <script>
					//alert('<?php //echo $row_edit[workorder_id].'_'.$row_edit[order_id].'_'.$row_edit[type].'_'.$row_edit[assign_fct_id]; ?>');
					document.getElementById('<?php echo $row_edit[workorder_id].'_'.$row_edit[order_id].'_'.$row_edit[type].'_'.$row_edit[assign_fct_id]; ?>').checked = "checked";
				  </script>
				  <?php 
			  }//end of while
		  }//end of if
		  
		  $html = ob_get_contents();
		  ob_end_clean();
		  return $html;
	  }///end of editgroup
	  
	  function printer(){
		  ob_start();
		  $sql_print = "SELECT id,printer,paper FROM ".erp_PRINTER_PAPER;
		  $result_print = $this->db->query($sql_print,__FILE__,__LINE__);
		  $total_rows = $this->db->num_rows($result_print);?>
		  <table id="printer_table" class="event_form small_text" width="100%">
			<thead>
			   <tr>
				  <th>Printer</th>
				  <th colspan="2">Paper</th>
			   </tr>
			</thead>
			<tbody>
			  <?php 
			  if($total_rows > 0 ){
			  while($row_print=$this->db->fetch_array($result_print)){?>
			   <tr>
				 <td>
					<div id="print_box_<?php echo $row_print[id]; ?>"> 
					<?php echo $this->returnLink($row_print[printer],'print_box_'.$row_print[id],'print'); ?>
                    </div>
                 </td>	  
				 <td>
				    <div id="paper_box_<?php echo $row_print[id]; ?>">
					  <?php echo $this->returnLink($row_print[paper],'paper_box_'.$row_print[id],'paper'); ?>
                    </div>
                 </td>
                 <td>
                    <a href="javascript:void(0);" 
                                        onclick="javascript:print.showTextBox('paper',
                                                                              '<?php echo $row_print[id]; ?>',
                                                                  {preloader:'prl',
                                                                  onUpdate:function(response,root){         
                                                                  document.getElementById('paper_box_<?php echo $row_print[id]; ?>').innerHTML=response;
                                                                  document.getElementById('paper_box_<?php echo $row_print[id]; ?>').style.display='';
                                                                            }});">edit</a>
                 </td>
			  </tr>
				<?php } //end of while
				} //end of if
			else
			{
			?>
			<tr><td colspan="2">No Record Found!!!!</td></tr>
			<?php
			} /////end of else
			?>
			</tbody>
		 </table>
	  <?php
        $html = ob_get_contents();
		ob_end_clean();
		return $html;
	  }  /////end of function printer
	  
	  function returnLink($variable='',$div_id='',$choice='',$roll_id=''){
		  ob_start();
		  switch($choice) {
		     case 'inch':
			 if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: print.showTextBox('<?php echo $choice; ?>',
																   '<?php echo $roll_id; ?>',
																 { target: '<?php echo $div_id; ?>',preloader:'prl'}
																	   ); ">
					<?php echo $variable; ?></a><?php 
				}
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: print.showTextBox('<?php echo $choice; ?>',
																   '<?php echo $roll_id; ?>',
															     { target: '<?php echo $div_id; ?>',preloader:'prl'}
																		); ">
					N/A</a>
				   <?php 
				     }
			 break;
			 case 'location':
			 if($variable !=''){ ?>
					<a href="javascript:void(0);" 
							onclick="javascript: print.showTextBox('<?php echo $choice; ?>',
																   '<?php echo $roll_id; ?>',
																 { target: '<?php echo $div_id; ?>',preloader:'prl'}
																	   ); ">
					<?php echo $variable; ?></a><?php 
				}
				else { ?>
					<a href="javascript:void(0);" 
							onclick="javascript: print.showTextBox('<?php echo $choice; ?>',
																   '<?php echo $roll_id; ?>',
															     { target: '<?php echo $div_id; ?>',preloader:'prl'}
																		); ">
					N/A</a>
				   <?php 
				     }
			 break;
			 case 'print':
			 if($variable !=''){ echo $variable;  }
			 else {  echo 'N/A'; }
			 break;
			 case 'paper':
			 if($variable !=''){  echo $variable; }
				else { echo 'N/A'; }
			 break;
		  } ///end of switch
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	  } ///end of function returnLink
	  
	  function showTextBox($choice='',$roll_id=''){
	 	 ob_start();
		 $sql_type= "select * from ".TBL_FABRIC_ROLLS." where id = '$roll_id'";
			//echo $sql_type;
			$result_type=$this->db->query($sql_type,__FILE__,__LINE__);
			$row_type=$this->db->fetch_array($result_type);
			$yards = round(($row_type[inches] * 0.027778),2);
		 switch($choice){
			case 'inch':
		     ?>
				<input  type="text" name="<?php  echo $yards; ?>" id="<?php echo $yards; ?>" size="3"
				        value="<?php echo $yards; ?>"
						onblur="javascript: var type_name=this.value; 
									if(type_name!='<?php echo $yards; ?>'){
								      if(confirm('Are you sure you want to change your inches from <?php echo $yards; ?> to '+ type_name)){	 
									  		print.update_textbox('<?php echo $roll_id; ?>',
                                                                 document.getElementById('<?php echo $yards; ?>').value,
                                                                 'inches',
                                                                 'locate_inch',
										   { onUpdate: function(response,root){
											 print.returnLink(document.getElementById('<?php echo $yards; ?>').value,
                                                              'inches_box_<?php echo $roll_id; ?>',
                                                              'inch',
															  '<?php echo $roll_id; ?>',
                                                              {preloader:'prl',
                                                        onUpdate:function(response,root){
                                                        document.getElementById('inches_box_<?php echo $roll_id; ?>').innerHTML=response;
                                                        }});
											        }} );}
										 else{
											  print.returnLink('<?php echo $yards; ?>',
                                                              'inches_box_<?php echo $roll_id; ?>',
                                                              'inch',
															   '<?php echo $roll_id; ?>',
                                                       {preloader:'prl',
                                                        onUpdate:function(response,root){
                                                        document.getElementById('inches_box_<?php echo $roll_id; ?>').innerHTML=response;
                                                        }});}}
											else{
												print.returnLink('<?php echo $yards; ?>',
																  'inches_box_<?php echo $roll_id; ?>',
																  'inch',
																   '<?php echo $roll_id; ?>',
                                                       {preloader:'prl',
                                                        onUpdate:function(response,root){
                                                        document.getElementById('inches_box_<?php echo $roll_id; ?>').innerHTML=response;
                                                        }});
														}">
                 <?php
                 break;
                 case 'location': ?>                                       
                 <input  type="text" name="<?php  echo $row_type[location_id]; ?>" id="<?php echo $row_type[location_id]; ?>" size="3"
				        value="<?php echo $row_type[location_id]; ?>"
						onblur="javascript: var type_name=this.value;
									if(type_name!='<?php echo $row_type[location_id]; ?>'){ 
								      if(confirm('Are you sure you want to change your location from <?php echo $row_type[location_id]; ?> to '+ type_name)){	 
									  		print.update_textbox('<?php echo $roll_id;?>',
                                                                 document.getElementById('<?php echo $row_type[location_id]; ?>').value,
                                                                 'location_id',
                                                                 'locate',
										   { onUpdate: function(response,root){
											 print.returnLink(document.getElementById('<?php  echo $row_type[location_id]; ?>').value,
                                                              'location_box_<?php echo $roll_id; ?>',
                                                              'location',
															  '<?php echo $roll_id; ?>',
                                                        {target:'location_box_<?php echo $roll_id; ?>',preloader:'prl'});
											        }} );}
										 else{
											  print.returnLink('<?php echo $row_type[location_id]; ?>',
                                                              'location_box_<?php echo $roll_id; ?>',
                                                              'location',
															  '<?php echo $roll_id; ?>',
                                                              {target:'location_box_<?php echo $roll_id; ?>',preloader:'prl'});}}
												else{
													print.returnLink('<?php echo $row_type[location_id]; ?>',
                                                              'location_box_<?php echo $roll_id; ?>',
                                                              'location',
															  '<?php echo $roll_id; ?>',
                                                              {target:'location_box_<?php echo $roll_id; ?>',preloader:'prl'});
												}"> 
		   <?php 
		   break;
		   case 'paper':
		   $sql_paper= "select * from ".erp_PRINTER_PAPER." where id = '$roll_id'";
			//echo $sql_type;
			$result_paper=$this->db->query($sql_paper,__FILE__,__LINE__);
			$row_paper=$this->db->fetch_array($result_paper); ?>
                <input  type="text" name="txt_paper" id="txt_paper" size="3"
				        value="<?php echo $row_paper[paper]; ?>"
						onblur="javascript: var type_name=this.value;
									if(type_name!='<?php echo  $row_paper[paper]; ?>'){  
								      if(confirm('Are you sure you want to change your paper from <?php echo $row_paper[paper]; ?> to '+ type_name)){	 
									  		print.update_textbox('<?php echo $roll_id; ?>',
                                                                 document.getElementById('txt_paper').value,
                                                                 'paper',
                                                                 'printer',
										   { onUpdate: function(response,root){
											 print.returnLink(document.getElementById('txt_paper').value,
                                                              'paper_box_<?php echo $roll_id; ?>',
                                                              'paper',
                                             {preloader:'prl',
                                             onUpdate:function(response,root){
                                             document.getElementById('paper_box_<?php echo $roll_id; ?>').innerHTML=response;
                                                        }});
											        }} );}
										 else{
											  print.returnLink('<?php echo $row_paper[paper]; ?>',
                                                               'paper_box_<?php echo $roll_id; ?>',
                                                               'paper',
                                              {preloader:'prl',
                                              onUpdate:function(response,root){
                                              document.getElementById('paper_box_<?php echo $roll_id; ?>').innerHTML=response;
                                              }});}}
											else{
											  print.returnLink('<?php echo $row_paper[paper]; ?>',
                                                               'paper_box_<?php echo $roll_id; ?>',
                                                               'paper',
                                              {preloader:'prl',
                                              onUpdate:function(response,root){
                                              document.getElementById('paper_box_<?php echo $roll_id; ?>').innerHTML=response;
                                              }});
											}">
            <?php
		    break; } /////end of switch
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}  ////////////////end of function showTextBox
	
	function update_textbox($roll_id,$variable,$choice,$paper){
	    ob_start();
		switch($paper){
			case 'locate_inch':
			$variable = ($variable/0.027778);
			$sql = "update ".TBL_FABRIC_ROLLS." set $choice= '$variable' where id='$roll_id'";
			$result = $this->db->query($sql,__FILE__,__LINE__);
			break;
			
			case 'locate':
			$sql = "update ".TBL_FABRIC_ROLLS." set $choice= '$variable' where id='$roll_id'";
			$result = $this->db->query($sql,__FILE__,__LINE__);
			break;
			
			case 'printer':
			$sql_paper = "update ".erp_PRINTER_PAPER." set $choice= '$variable' where id='$roll_id'";
			$result_paper = $this->db->query($sql_paper,__FILE__,__LINE__);
			break;
		  } ////end of switch
        $html=ob_get_contents();
		ob_end_clean();
		return $html;
	 } ////////////end of function update_textbox
	 
 } ///////end of class
?>