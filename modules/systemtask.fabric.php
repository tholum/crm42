<?php
$global_task_status_id = $args[0];
$module = $args[1];
$module_id = $args[2];
$group_id = $args[3];

$sql_s ="Select a.*,b.* from template a,assign_report_to_system_task b where a.id=b.report_id and b.selection_option_id='$global_task_status_id' and a.type = 'FILE'"; 
//echo $sql_s;
$result_s = $this->db->query($sql_s,__FILE__,__LINE__);
if($this->db->num_rows($result_s) > 0){
	while($row_s = $this->db->fetch_array($result_s)){
		if($row_s['module'] == $module && (($row_s['message'] =='NULL') || ($row_s['message'] ==''))) {
		   if($row_s['file'] == 'fabric'){
				
				$inve_used = 0;		
				$sql_group="select * from ".erp_GROUP." where order_id='$_SESSION[order_id]' and fabric_id = '$module_id'";
				$result_group = $this->db->query($sql_group,_FILE_,_LINE_); 
				
				$row_grp = $this->db->fetch_array($result_group);
				$inve_name = $row_grp['inventory_name'];	   
				$inve_used = $row_grp['total_inch'];
		
				//$inve_detail = $this->getFabricInveUsed($_SESSION[order_id], $module_id);
				
		   }
		   else{
				$sql_size = "SELECT size, quantity FROM ".erp_SIZE." WHERE order_id = '$order_id' AND product_id = '$module_id'";
				$result_size = $this->db->query($sql_size,_FILE_,_LINE_); 
				
				if($this->db->num_rows($result_size) > 0){
					while($row_size = $this->db->fetch_array($result_size)){
						$s = explode("_", $row_size['size']);
						$size = strtolower($s[1]);
						$qty = $row_size['quantity'];
						
						/*****************************getInveId()****************************/
						
						$inve_id = 0; 
						$sql_inve = "SELECT $row_s['file'] from ".erp_WORK_ORDER." WHERE order_id = '$order_id' AND product_id = '$module_id'";
						$result_inve = $this->db->query($sql_inve);
						$row_inve = $this->db->fetch_array($result_inve);
						
						if($row_inve[$t] != ''){						
							$inve_id = $row_inve[$t];
						}
						else {
							$name2 = $size.'_size_dependant';
							
							$sql_size_dependent = "SELECT $name2 from ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' AND product_id = '$module_id' AND option_type = '$t'";
							$result_size_dependent = $this->db->query($sql_size_dependent);
							$row_size_dependent = $this->db->fetch_array($result_size_dependent);
							
							$inve = explode("_", $row_size_dependent[$name2]);
							$inve_id = $inve[0];
						}
											
						//$inve_id = $this->getInveId($t, $order_id, $module_id, $size);
						
						/*****************************invenName()****************************/
						
						$sql_name = "select name from ".TBL_INVENTORY_DETAILS." where inventory_id = '$inve_id' ";
						$result_name = $this->db->query($sql_name,__FILE__,__LINE__);
						$row_name = $this->db->fetch_array($result_name);
						$inve_name =  $row_name[name];					
						//$inve_name = $this->invenName($inve_id);
						/*****************************getInveUsed()****************************/
						$inve_used = 0;			
						$name = $size.'_inventory_usage';					
						
						$sql_price = "SELECT $name from ".erp_ASSIGN_INVENTORY." WHERE inventory_id = '$inve_id' AND product_id = '$module_id'";
						$result_price = $this->db->query($sql_price);
						$row_price = $this->db->fetch_array($result_price);
						
						$unit_price = $row_price[$name];
						$price = $qty * $unit_price;
						$inve_used = $inve_used + $price;
						
						//$inve_used = $this->getInveUsed($size, $inve_id, $module_id, $qty);		
					} 			
				}
			} // End of Else condition
									
			$sql_f="select amt_onhand from " .TBL_INVENTORY_DETAILS." where name = '$inve_name'";
			$result_f = $this->db->query($sql_f,_FILE_,_LINE_); 
			$row_f = $this->db->fetch_array($result_f);
			$amt_onhand = $row_f['amt_onhand'];
			
			$cur_amt_onhand = $amt_onhand - $inve_used;	
			
			$sql = "update ".TBL_INVENTORY_DETAILS." set amt_onhand = '$cur_amt_onhand' where name = '$inve_name'";
			$this->db->query($sql,__FILE__,__lINE__);	
			
			echo $sql;
			
			if($inve_name !=''){
				$sql_log = "INSERT INTO ".erp_INVENTORY_LOG." values('', '$inve_name', '$_SESSION[order_id]', '$module_id', '$amt_onhand', '$inve_used', '$cur_amt_onhand')";
				$this->db->query($sql_log,__FILE__,__lINE__);
			}						
		}
   }				
}		
?>