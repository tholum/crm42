<?php

class group {

var $db;
var $ad;
var $company_id;
var $Validity;

	function __construct(){
	$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	$this->validity = new ClsJSFormValidation();
	$this->Form = new ValidateForm();
	}

  function ShowGroup() {
     ob_start();
	 $formName = "frm_search";	?>
 	 <form name="<?php echo $formName;?>" method="post" action="">
		<table width="100%" class="table" >
			<tr>
				<td>Group Id</td>
				<td>Fabric</td>
				<td>Printer</td>
				<td>Task</td>
			</tr>
			<tr>
				<td width="25%"><input type="text" name="txt_name" id="txt_name" 
								onchange="javascript:group_display.show_searchtable(
										document.getElementById('txt_name').value,
										document.getElementById('select_fabric').value,
										document.getElementById('select_printer').value,
										document.getElementById('select_task').value,
										document.getElementById('txt_task_due').value,
										document.getElementById('txt_task_due1').value,
										document.getElementById('txt_ordership').value,
										document.getElementById('txt_ordership1').value,
										document.getElementById('txt_orderId').value,
										document.getElementById('select_ProStatus').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} })}}
												);"/></td>	
				<td width="25%" >
					<select name="select_fabric" id="select_fabric" style="width:100%;"
									onchange="javascript:group_display.show_searchtable(
										document.getElementById('txt_name').value,
										document.getElementById('select_fabric').value,
										document.getElementById('select_printer').value,
										document.getElementById('select_task').value,
										document.getElementById('txt_task_due').value,
										document.getElementById('txt_task_due1').value,
										document.getElementById('txt_ordership').value,
										document.getElementById('txt_ordership1').value,
										document.getElementById('txt_orderId').value,
										document.getElementById('select_ProStatus').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
												);">
							<option value="">-select-</option>
							<?php 
							$sql="select * from erp_inventory_details where type_id=5";
							$result=$this->db->query($sql,__FILE__,__LINE__);
							while($row=$this->db->fetch_array($result)) {?>
												
							<option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
							<?php } ?>
					</select>
				</td>
				<td width="25%">
					<select name="select_printer" id="select_printer" style="width:100%;"
							onchange="javascript:group_display.show_searchtable(
										document.getElementById('txt_name').value,
										document.getElementById('select_fabric').value,
										document.getElementById('select_printer').value,
										document.getElementById('select_task').value,
										document.getElementById('txt_task_due').value,
										document.getElementById('txt_task_due1').value,
										document.getElementById('txt_ordership').value,
										document.getElementById('txt_ordership1').value,
										document.getElementById('txt_orderId').value,
										document.getElementById('select_ProStatus').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
												);">
						<option value="">-select-</option>						
						<?php 
						$sql_p = "SELECT * FROM erp_printer_paper ";
						$result_p = $this->db->query($sql_p,__FILE__,__LINE__);
           				while($row_p=$this->db->fetch_array($result_p)){ ?>
                        <option value="<?php echo $row_p["id"];?>"><?php echo $row_p["printer"]; ?></option>
						<?php } ?>						
					</select>
				</td>
				<td width="25%"><select name="select_task" id="select_task" style="width:100%;"
									onchange="javascript:group_display.show_searchtable(
										document.getElementById('txt_name').value,
										document.getElementById('select_fabric').value,
										document.getElementById('select_printer').value,
										document.getElementById('select_task').value,
										document.getElementById('txt_task_due').value,
										document.getElementById('txt_task_due1').value,
										document.getElementById('txt_ordership').value,
										document.getElementById('txt_ordership1').value,
										document.getElementById('txt_orderId').value,
										document.getElementById('select_ProStatus').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
												);">
						<option value="">-select-</option>
						<?php 
							$sql_t = "select * from tbl_global_task ";
							$result_t = $this->db->query($sql_t,__FILE__,__LINE__);
							while($row_t=$this->db->fetch_array($result_t)) {?>
												
							<option value="<?php echo $row_t['global_task_id']; ?>"><?php echo $row_t['global_task_id'].' - '.$row_t['name']; ?></option>
						<?php } ?>
				    </select>
				</td>
			</tr>
			<tr>
				 <td>Task Due</td>
				 <td>Order Ship</td>
				 <td>Order Id</td>
				 <td>Department</td>
		   </tr>
		    
		   <tr>
			   <td width="25%" >
					<table width="100%" >
					<tr>	
						<td>		   
						<input width="150px" type="text" name="txt_task_due" id="txt_task_due" value="" size="5"/>
								 <script type="text/javascript">	 
								 function start_cal(){
								 new Calendar({
								 inputField   	: "txt_task_due",
								 dateFormat		: "%Y-%m-%d",
								 trigger		: "txt_task_due",
								 weekNumbers   	: true,
								 bottomBar		: true,				 
								 onSelect		: function() {
														this.hide();
														   document.getElementById('txt_task_due').value=this.selection.print("%Y-%m-%d");
														   group_display.show_searchtable(
																	document.getElementById('txt_name').value,
																	document.getElementById('select_fabric').value,
																	document.getElementById('select_printer').value,
																	document.getElementById('select_task').value,
																	document.getElementById('txt_task_due').value,
																	document.getElementById('txt_task_due1').value,
																	document.getElementById('txt_ordership').value,
																	document.getElementById('txt_ordership1').value,
																	document.getElementById('txt_orderId').value,
																	document.getElementById('select_ProStatus').value,
																		{preloader:'prl',onUpdate: function(response,root){
																			document.getElementById('task_area').innerHTML=response;
																			$('#search_table')
																			.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																			);												
																}
												   }
												 );			
									}
								 start_cal();
								 </script> </td>
								 <td>to</td>
						<td>
						<input  width="150px" type="text" name="txt_task_due1" id="txt_task_due1" value="" size="5"/>
								 <script type="text/javascript">	 
								 function start_cal(){
								 new Calendar({
								 inputField   	: "txt_task_due1",
								 dateFormat		: "%Y-%m-%d",
								 trigger		: "txt_task_due1",
								 weekNumbers   	: true,
								 bottomBar		: true,				 
								 onSelect		: function() {
														this.hide();
														   document.getElementById('txt_task_due1').value=this.selection.print("%Y-%m-%d");
														   group_display.show_searchtable(
																	document.getElementById('txt_name').value,
																	document.getElementById('select_fabric').value,
																	document.getElementById('select_printer').value,
																	document.getElementById('select_task').value,
																	document.getElementById('txt_task_due').value,
																	document.getElementById('txt_task_due1').value,
																	document.getElementById('txt_ordership').value,
																	document.getElementById('txt_ordership1').value,
																	document.getElementById('txt_orderId').value,
																	document.getElementById('select_ProStatus').value,
																		{preloader:'prl',onUpdate: function(response,root){
																			document.getElementById('task_area').innerHTML=response;
																			$('#search_table')
																			.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																			);	
																}
												   }
												 );			
									}
								 start_cal();
								 </script>
						</td>
					</tr>	
					</table>
			 </td>
		
			   <td width="25%">
			   <table width="100%" >
			   <tr>	
				<td>
			   <input  type="text" name="txt_ordership" id="txt_ordership" value="" size="10"/>
                         <script type="text/javascript">	 
                         function start_cal(){
                         new Calendar({
                         inputField   	: "txt_ordership",
                         dateFormat		: "%Y-%m-%d",
                         trigger		: "txt_ordership",
                         weekNumbers   	: true,
                         bottomBar		: true,				 
                         onSelect		: function() {
                                                this.hide();
                                                   document.getElementById('txt_ordership').value=this.selection.print("%Y-%m-%d");
                                                   group_display.show_searchtable(
																	document.getElementById('txt_name').value,
																	document.getElementById('select_fabric').value,
																	document.getElementById('select_printer').value,
																	document.getElementById('select_task').value,
																	document.getElementById('txt_task_due').value,
																	document.getElementById('txt_task_due1').value,
																	document.getElementById('txt_ordership').value,
																	document.getElementById('txt_ordership1').value,
																	document.getElementById('txt_orderId').value,
																	document.getElementById('select_ProStatus').value,
																		{preloader:'prl',onUpdate: function(response,root){
																			document.getElementById('task_area').innerHTML=response;
																			$('#search_table')
																			.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																			);												
                                                        }
                                           }
                                         );			
                            }
                         start_cal();
                         </script></td><td>to</td>
												
				<td><input  type="text" name="txt_ordership1" id="txt_ordership1" value="" size="10"/>
                         <script type="text/javascript">	 
                         function start_cal(){
                         new Calendar({
                         inputField   	: "txt_ordership1",
                         dateFormat		: "%Y-%m-%d",
                         trigger		: "txt_ordership1",
                         weekNumbers   	: true,
                         bottomBar		: true,				 
                         onSelect		: function() {
                                                this.hide();
                                                   document.getElementById('txt_ordership1').value=this.selection.print("%Y-%m-%d");
                                                   group_display.show_searchtable(
																	document.getElementById('txt_name').value,
																	document.getElementById('select_fabric').value,
																	document.getElementById('select_printer').value,
																	document.getElementById('select_task').value,
																	document.getElementById('txt_task_due').value,
																	document.getElementById('txt_task_due1').value,
																	document.getElementById('txt_ordership').value,
																	document.getElementById('txt_ordership1').value,
																	document.getElementById('txt_orderId').value,
																	document.getElementById('select_ProStatus').value,
																		{preloader:'prl',onUpdate: function(response,root){
																			document.getElementById('task_area').innerHTML=response;
																			$('#search_table')
																			.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
																			);												
                                                        }
                                           }
                                         );			
                            }
                         start_cal();
                         </script>
			   </td>
			   </tr>
			   </table>	
			   <td width="25%"><input type="text" name="txt_orderId" id="txt_orderId"
			   			onchange="javascript:group_display.show_searchtable(
										document.getElementById('txt_name').value,
										document.getElementById('select_fabric').value,
										document.getElementById('select_printer').value,
										document.getElementById('select_task').value,
										document.getElementById('txt_task_due').value,
										document.getElementById('txt_task_due1').value,
										document.getElementById('txt_ordership').value,
										document.getElementById('txt_ordership1').value,
										document.getElementById('txt_orderId').value,
										document.getElementById('select_ProStatus').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
												);"/></td>	
			   <td width="25%">
						<select name="select_ProStatus" id="select_ProStatus" style="width:100%;"
								onchange="javascript:group_display.show_searchtable(
										document.getElementById('txt_name').value,
										document.getElementById('select_fabric').value,
										document.getElementById('select_printer').value,
										document.getElementById('select_task').value,
										document.getElementById('txt_task_due').value,
										document.getElementById('txt_task_due1').value,
										document.getElementById('txt_ordership').value,
										document.getElementById('txt_ordership1').value,
										document.getElementById('txt_orderId').value,
										document.getElementById('select_ProStatus').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
												$('#search_table')
												.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}}
												);">
						   <option value="">-select-</option>
						   <?php
							$sql_g = "SELECT group_id, group_name FROM ".erp_USERGROUP;
							$result_g = $this->db->query($sql_g,__FILE__,__LINE__);
							while( $row_g = $this->db->fetch_assoc($result_g) ){ ?>
						   <option value="<?php echo $row_g[group_id];?>"><?php echo $row_g[group_name]; ?></option>
						   <?php } ?>
				 </select>
			 </td>
		   </tr>
	   </table>
</form>
  		<?php    
        $html = ob_get_contents();
		ob_end_clean();
		return $html;  
    }   //////end of function InVeDetails
	
	function show_searchtable($group_id='',$fabric='',$printer='',$task='',$due_date='',$due='',$ship_date='',$ship='',$order_id='',$department='')
	{
		ob_start();
			if($group_id=='' && $fabric=='' && $printer=='' && $task=='' && $order_id=='' && $department=='' && $due_date=='' && $due=='' && $ship_date=='' && $ship=='')
			  {
				$sql="SELECT distinct a.group_id,a.total_inch,a.printer,a.fabric_rolles FROM erp_create_group a WHERE 1";
				$result=$this->db->query($sql);
			   }
			else{
			$sql="SELECT distinct a.group_id,a.total_inch,a.printer,a.fabric_rolles,c.name ";
			if($task) $sql .= " , b.flow_chart_id " ;
			if($order_id) $sql .=",". a.".".order_id;
			$sql .= " FROM erp_create_group a, erp_inventory_details c, erp_work_order e ";
			
			if($task or $department) $sql .= " ,".erp_ASSIGN_FCT." b ";
			
			if($department) $sql .= " ,".erp_GLOBAL_TASK." f,".erp_USERGROUP." g ";
			
			$sql .= " where a.inventory_name = c.name ";
			
			if($group_id){
				$sql.=" and a.group_id = '$group_id' ";}
			 
		    if($fabric){
				$sql.=" and c.name = '$fabric' ";}
			
		    if($task){
				$sql.=" and b.flow_chart_id='$task' and b.module_id=a.workorder_id and b.module='work order' and b.task_status='Active' ";}
			
		    if($printer){
				$sql.="and a.printer= '$printer' ";}
		   
		    if($department){
				$sql.="and g.group_id = '$department' and g.group_id = f.department_id and f.global_task_id = b.flow_chart_id and b.module = 'work order' and b.task_status='Active' and b.chart_assign_id = a.assign_fct_id ";}
		   
		    if($order_id){
				$sql.=" and a.order_id = '$order_id' ";}
			
			if($due_date != '' and $due == ''){
			   $sql .=" and a.est_task_due_date >= '".date('Y-m-d h:i:s',strtotime($due_date))."' "; }
				
			if($due_date == '' and $due != ''){
			   $sql .=" and a.est_task_due_date <= '".date('Y-m-d h:i:s',strtotime($due))."' "; }
				
			if($due_date != '' and $due != ''){
			   $sql .=" and a.est_task_due_date BETWEEN '".date('Y-m-d h:i:s',strtotime($due_date))."' and '".date('Y-m-d h:i:s',strtotime($due))."' "; }
				
			if($ship_date != '' and $ship == ''){
			   $sql .=" and a.est_ship_date >= '".date('Y-m-d h:i:s',strtotime($ship_date))."' "; }
				
			if($ship_date == '' and $ship != ''){
			   $sql .=" and a.est_ship_date <= '".date('Y-m-d h:i:s',strtotime($ship))."' "; }
				
			if($ship_date != '' and $ship != ''){
			   $sql .=" and a.est_ship_date BETWEEN '".date('Y-m-d h:i:s',strtotime($ship_date))."' and '".date('Y-m-d h:i:s',strtotime($ship))."' "; }
			//echo $sql;
			$result=$this->db->query($sql);
		   }
			$total_rows = $this->db->num_rows($result);
		?>
		<table id="search_table" class="event_form small_text" width="101%">
			<thead>
			   <tr>
				  <th width="9%">Group ID</th>
				  <th width="12%">Inches</th>
				  <th width="12%">Printer</th>
				  <th width="18%">Fabric</th>
				  <th width="26%">Ship Date</th> 
				  <th width="23%">Task Due</th>
			  </tr>
			</thead>
			<tbody>
		     <?php 
			  if($total_rows >0 ){
				 while($row=$this->db->fetch_array($result)){?>
				 <tr>
					<td><a href="javascript:void(0);" onClick="javascript:group_display.showdetail_data('<?php echo $row['group_id'];?>',
																				{target: 'show_detail',
																				   onUpdate: function(response,root){
																				   document.getElementById('show_detail').innerHTML = response;}});" >
																				   <?php echo $row['group_id'];?></a></td>	  
					<td><?php echo $row['total_inch'];?></td>
					<?php 
					$sql_p="Select printer from ".erp_PRINTER_PAPER." where id='$row[printer]'";
					$result_p=$this->db->query($sql_p,__FILE__,__LINE__);
					$row_p=$this->db->fetch_array($result_p);
					?>
					<td><?php echo $row_p['printer'];?> </td>
					<?php 
					$sql_f="Select a.name from ".TBL_INVENTORY_DETAILS." a , ".TBL_FABRIC_ROLLS." b where b.id='$row[fabric_rolles]' and a.inventory_id=b.fabric_type";
					$result_f=$this->db->query($sql_f,__FILE__,__LINE__);
					$row_f=$this->db->fetch_array($result_f);?>
					
					<td><?php echo $row_f['name'];?></td>
					<td><?php  echo $this->get_ship_duedate($row['group_id'],'est_ship_date');?></td>
					<td><?php echo $this->get_ship_duedate($row['group_id'],'est_task_due_date');?></td>
				</tr> 
				<?php } 
				}
			else
			{
			?>			<tr><td colspan="6" align="center">No Record Found!!!!</td></tr>
			<?php } ?>	
		    </tbody>
</table>
		<?php
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function get_ship_duedate($group_id='',$variable='')
	{
		ob_start();
		$ship=array();
		$i=0;
		$sql_ship="select order_id,workorder_id from ".erp_GROUP." where group_id = '$group_id' ";
		$result_ship=$this->db->query($sql_ship,__FILE__,__LINE__);
		while($row_ship=$this->db->fetch_array($result_ship)){
			
			if($variable == 'est_ship_date')
			{
				$sql="select ship_date from ".erp_ORDER." where order_id='$row_ship[order_id]' ";
				$result=$this->db->query($sql,__FILE__,__LINE__);
				while($row=$this->db->fetch_array($result)){
					$ship[$i]=$row['ship_date'];
					$i++;
				  }
			}
			
			if($variable == 'est_task_due_date')
			{
				$sql="select due_date from ".erp_ASSIGN_FCT." where product_id='$row_ship[order_id]' and module_id='$row_ship[workorder_id]'";
				$result=$this->db->query($sql,__FILE__,__LINE__);
				while($row=$this->db->fetch_array($result)){
					$ship[$i]=$row['due_date'];
					$i++;
				 }
			}
		}
		$c=strtotime($ship[0]);
		for($j=0;$j<$i;)
		{	
			if($c > strtotime($ship[$j+1])){
				$c=strtotime($ship[$j]);
				$j++;
			}
			else{
				$c=strtotime($ship[$j+1]);
				$j++;
			}
		}
		$date = date("Y-m-d h:i:s A",$c);
		echo $date;
		$sql_date_update = "UPDATE ".erp_GROUP." SET $variable = '$date' WHERE group_id = '$group_id'";
		$this->db->query($sql_date_update,__FILE__,__LINE__);

		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function get_printer_dropdown_options( $selected = ''){
		ob_start();
		$result = $this->db->query("SELECT * FROM `erp_printer_paper`");
		while( $row = $this->db->fetch_assoc($result)){
			?>
			<option value="<?php echo $row["id"];?>" <?php if($selected == $row["id"] ){ echo "SELECTED"; }?> ><?php echo $row["printer"]; ?></option>
			<?
		}
		$html=ob_get_contents();
		ob_clean();
		return $html;
	}
        
	function set_group_option( $field , $value , $group_id ){
		$this->db->update( 'erp_create_group', array( $field => $value ), 'group_id', $group_id);
	}
	
	function showdetail_data($group_id='')
	{
          $group_info = $this->db->fetch_assoc($this->db->query("SELECT * FROM `erp_create_group` WHERE group_id = '$group_id'"));
		ob_start();?>
		<table class="table" width="100%">
		<tbody>
			 <tr>
				<th>Printer :</th>
					<td>
					<select name="select_printer" id="select_printer" style="width:100%;" 
									onchange="javascript:group_display.set_group_option( 'printer' , this.value ,  '<?php echo $group_id; ?>',
																					{preloader:'prl'});
																					
													group_display.show_searchtable({preloader:'prl',
																					onUpdate: function(response,root){
												                                    document.getElementById('task_area').innerHTML=response;
																					$('#search_table')
												                                    .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]}); }});
																					">
								<option value="">--Select--</option>
									<?php  $result = $this->db->query("SELECT * FROM `erp_printer_paper`");
										while( $row = $this->db->fetch_assoc($result)){
											?>
											<option value="<?php echo $row["id"];?>" <?php if($group_info["printer"] == $row["id"] ){ echo "SELECTED"; }?> >
												<?php echo $row["printer"]; ?>
											</option> 
											<? } ?>
                      </select>
					</td>
			 </tr>
			 <tr>
			 <th>Fabric Roll :</th>
				 <td>
				 <select name="fabric" id="fabric" style="width:100%;"
				 					onchange="javascript:group_display.set_group_option( 'fabric_rolles' , this.value , '<?php echo $group_id; ?>',
																					{preloader:'prl'});
																					
													group_display.show_searchtable({preloader:'prl',
																					onUpdate: function(response,root){
												                                    document.getElementById('task_area').innerHTML=response;
																					$('#search_table')
												                                    .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]}); }});
																					">
							<option value="">--Select--</option>
							<?php 
							
							$sql_inve="Select distinct a.inventory_id,a.name,c.id,c.inches from ".TBL_INVENTORY_DETAILS." a, ".erp_GROUP." b, ".TBL_FABRIC_ROLLS." c where a.name=b.inventory_name and b.group_id='$group_id' and c.fabric_type=a.inventory_id";
							$result_inve=$this->db->query($sql_inve,__FILE__,__LINE__);
							
							if( $this->db->num_rows($result_inve)>0 ){
							
							while($row_inve=$this->db->fetch_array($result_inve)){?>
						     <option value="<?php echo $row_inve['id'] ;?>" <?php if($group_info["fabric_rolles"] == $row_inve["id"] ){ echo "SELECTED"; }?>  ><?php echo $row_inve['id'].'-'.$row_inve['name'].'-'.$row_inve['inches'] ;?></option>
							 <?php }} ?>
				   </select>
				 </td>
			 </tr>
			 <tr>
			 	<th>Tasks</th>
			 </tr>
			 <tr>
				 <td colspan="2">
                                     
				 <div id="group_task_<?php echo $group_id;?>" >
					<?php
					$global_task = new GlobalTask();
                    $global_task->displayByGroupID( $group_id );
					?>
				 </div>
				 </td>
			 </tr>
			 <tr>
			 	<th>Orders</th>
			</tr>
			<tr>
				<table width="100%" class="event_form small_text">
					<thead>
					<tr>
						<th>Order Id</th>
						<th>WO Id</th>
						<th>Module</th>						
						<th>Name</th>
						<th>Inches</th>
						<th>Due to Print</th>
						<th>Ship Date</th>
					</tr>
					</thead>
					<tbody>
					  <?php 
						$sql_type= "select distinct a.*,b.ship_date from ".erp_GROUP." a , " .erp_ORDER." b where a.group_id='$group_id' and a.order_id=b.order_id" ;
						
						$result_type=$this->db->query($sql_type,__FILE__,__LINE__);
						if( $this->db->num_rows($result_type)>0 ){
							while($row_type=$this->db->fetch_array($result_type)){?>
							<tr>
								<td><a href="order.php?order_id=<?php echo $row_type['order_id'];?>"><?php echo $row_type['order_id']; ?></a></td>
								<td><a href="order.php?order_id=<?php echo $row_type['order_id'];?>"><?php echo $row_type['workorder_id']; ?></a></td>	
								<td><?php echo $row_type['type'];?></td>								  
								<td><?php echo $row_type['inventory_name']; ?></td>
								<td><?php echo $row_type['inches']; ?></td>
								<?php 
									$sql_due="Select due_date from ".erp_ASSIGN_FCT." where chart_assign_id='$row_type[assign_fct_id]'";
									$result_due=$this->db->query($sql_due,__FILE__,__LINE__);
									$row_due=$this->db->fetch_array($result_due);
								?>
								<td><?php echo $row_due['due_date']; ?></td>
								<td><?php echo $row_type['ship_date']; ?></td>
					  </tr> 
						<?php
							} 
						}
						else
						{?>
							<tr><td colspan="4">No Record Found!!!!</td></tr>
						<?php } ?>
					</tbody>
				</table>
			</tr>
			<tr>
				<th>Available Fabric</th>
			</tr>
			<tr>
				<table id="fabric_table" class="event_form small_text" width="100%">
			<thead>
			   <tr>
				  <th>Fabric</th>
				  <th>Roll #</th>
				  <th>Inches</th>
                  <th>Location</th>
			  </tr>
			</thead>
			<tbody>
			  <?php 
			  $sql_type="Select distinct a.inventory_id,a.name,c.* from ".TBL_INVENTORY_DETAILS." a, ".erp_GROUP." b, ".TBL_FABRIC_ROLLS." c where a.name=b.inventory_name and b.group_id='$group_id' and c.fabric_type=a.inventory_id";
			  $result_type=$this->db->query($sql_type,__FILE__,__LINE__);
				if( $this->db->num_rows($result_type)>0 ){
					while($row_type=$this->db->fetch_assoc($result_type)){?>
					<tr>
						<td><?php echo $row_type['name']; ?></td>	  
						<td><?php echo $row_type['id']; ?></td>
						<td><?php echo $row_type['inches']; ?></td>
						<td><?php echo $row_type['location_id']; ?></td>
			  </tr> 
                <?php
					} 
				}
				else
				{ ?>
					<tr><td colspan="4">No Record Found!!!!</td></tr>
				<?php } ?>
			</tbody>
		 </table>
			</tr>
		  </tbody>
		</table>
		<?php
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}
}?>