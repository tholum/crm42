<?php
class CapacityCalc {
	var $db;
	var $final_total = 0; 
	
	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	}
        
    function calculate_capacity( $module_name , $module_id , $global_task_id ){  
        /*
         * Module Name is eather "order" or "work order", $global_task_id is global_task_id of tbl_global_task
         * I need from giving these variables it to calculate the capacity of that one order
         *  
        */	
			$capacity = 0;
			$fcts_info = array();
			$fcts_info = $this->getAllGTID($module_name , $module_id, $global_task_id);
			
			//print_r($fcts_info);
          	foreach($fcts_info as $fct_info){
				//print_r($fct_info);
				//echo '<br>';
				if($fct_info['fct_id'] == $global_task_id and $fct_info['module'] == $module_name and $fct_info['module_id'] == $module_id){
					//echo '<br>'."$fct_info[fct_id] == $global_task_id and $fct_info[root_id] == $global_task_id and $fct_info[module] == $module_name and $fct_info[module_id] == $module_id";
					if($fct_info['check_point'] == 'yes'){					
						//echo $fct_info['total_min'].'--'.$fct_info['est_min'];
						$capacity = $fct_info['total_min'] + $fct_info['est_min'];
					}
					else{
					  	$capacity = $fct_info['est_min'];
					 }
				}
			}		
		   //echo $capacity;		
		   return $capacity;
        }
		
	function getAllGTID($module_name='', $module_id='', $global_task_id=''){

		$sql= "Select a.*,b.est_day_dep,b.est_min_task,b.department_chk_tsk,c.group_name from ".erp_ASSIGN_FCT." a, ".erp_GLOBAL_TASK." b, ".erp_USERGROUP." c where a.task_status = 'Active' and a.flow_chart_id = b.global_task_id and b.department_id = c.group_id and a.flow_chart_id in ( select global_task_id from tbl_global_task where global_task_tree_id in (select global_task_tree_id from tbl_global_task where global_task_id = '$global_task_id') ) and a.module = '$module_name' and a.module_id = '$module_id' order by due_date";
		//echo $sql;
		
		$result = $this->db->query($sql,__FILE__,__LINE__); 
		while( $row = $this->db->fetch_array($result) ){
			//$this->fct_array[] = array($row['flow_chart_id'],$row['group_name'],$row['due_date'],$row['est_day_dep'],$row['est_min_task']);
			$tot_min = $this->showProductValue($row['module'],$row['module_id'],$row['flow_chart_id']); 	
						
			$this->fct_array[] = array("fct_id" => $row['flow_chart_id'], "department" => $row['group_name'], "due_date" => $row['due_date'], "est_day" => $row['est_day_dep'], "est_min" => $row['est_min_task'], "check_point" => $row['department_chk_tsk'] , "assign_id" => $row['chart_assign_id'],"order_id" => $row['product_id'], "module" => $row['module'],"module_id" => $row['module_id'],"root_id" => $row['flow_chart_id'], "total_min" => $tot_min);
			
			echo $this->predictPathFct($row['flow_chart_id'],$row['due_date'],$row['est_day_dep'],'active',$row['flow_chart_id'],$row['module'],$row['module_id'],$tot_min,$row['product_id']); 
		}
		
		return $this->fct_array;
	}

	function predictPathFct($gtid='', $due_date='' ,$est_day='',$flag='',$root_id='',$module='' ,$module_id = '',$tot_min='',$order_id = ''){
	    ob_start();
		$total_est_day= 0;
		 //echo $gtid;
		 $sql_status = "Select * from ".GLOBAL_TASK_STATUS." where global_task_id = '$gtid' and default_path = '1'";
		 
		 $result_status = $this->db->query($sql_status,__FILE__,__LINE__);
		 $row_status = $this->db->fetch_array($result_status);
		 $status_id = $row_status[global_task_status_id];
		 
		 $sql_name = "Select global_task_id from ".erp_GLOBAL_TASK_STATUS_RESULT." where global_task_status_id = '$status_id'";
		 $result_name = $this->db->query($sql_name,__FILE__,__LINE__);
		 $row_name=$this->db->fetch_array($result_name);
		 $sub_task = $row_name[global_task_id];
		 
		 $sql_task = "Select a.global_task_id, a.name, a.module, a.est_day_dep, a.est_min_task,a.department_chk_tsk, b.group_name from ".erp_GLOBAL_TASK." a,".erp_USERGROUP." b where a.global_task_id = '$sub_task' and a.default_path = '1' and a.department_id = b.group_id";
		 $result_task = $this->db->query($sql_task,__FILE__,__LINE__);
		 
		 if($this->db->num_rows($result_task) > 0){
			 $row_task = $this->db->fetch_array($result_task);
			 
			 $sub_task = $row_task[global_task_id];
			 $task_name = $row_task[name];
			 $task_mod = $row_task[module];			 
			 
			 //$this->fct_array[] = array($sub_task,$row_task[group_name],$est_date,$row_task[est_day_dep],$row_task[est_min_task]);
			 $this->fct_array[] = array("fct_id" => $sub_task, "department" => $row_task[group_name], "due_date" => $est_date, "est_day" => $row_task['est_day_dep'], "est_min" => $row_task['est_min_task'], "check_point" => $row_task['department_chk_tsk'], "module" => $module, "module_id" => $module_id, "root_id" => $root_id, "total_min" => $tot_min);
			 echo $this->predictPathFct($sub_task,$est_date,$row_task[est_day_dep],'',$root_id,$module,$module_id,$tot_min,$order_id);
		  }//end of if		  
		  		  
		//echo $fct_array;
		
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	} /////end of function predict_path
		
	function showProductValue($module_name='', $module_id='',$global_task_id=''){		
	  $this->final_total= 0;
	  
	  $sql_min = "SELECT a.workorder_id, a.gp_id, a.product_id, a.order_id, a.product_name, count(a.product_id) as 'total', SUM(b.quantity) as 'quantity' FROM ".erp_PRODUCT_ORDER." a, ".erp_SIZE." b WHERE 1 ";
	  
	  if( $module_name == 'order' and !$sub_module_id){
	  	  $sql_min .= " and a.order_id = '$module_id'";
	  } 
	  else if($module_name == 'work order'){
	  	  $sql_min .= " and a.workorder_id = '$module_id'";
	  }

	  if($module_name == 'order') $sql_min .= " and a.order_id = b.order_id and (a.workorder_id = b.product_id or a.gp_id = b.product_id) GROUP BY a.product_id";
	  else $sql_min .= " and (a.workorder_id = b.product_id or a.gp_id = b.product_id) GROUP BY a.workorder_id";

	  //echo '<br><br>'.$sql_min.'<br><br>';
	  $result_min = $this->db->query($sql_min,__FILE__,__LINE__);
	  if( $this->db->num_rows($result_min) > 0 ){
		  while( $row_min = $this->db->fetch_array($result_min) ){
				 $product_name = $row_min[product_name];
				 $count_product = $row_min[total];
				 $quantity = $row_min[quantity];
				 $i++;
				 
				 $sql_fct = "SELECT c.group_name FROM ".erp_ASSIGN_FCT." a, ".erp_GLOBAL_TASK." b, ".erp_USERGROUP." c WHERE a.module = '$module_name' and a.module_id = '$module_id' and a.task_status = 'Active' and a.flow_chart_id = b.global_task_id and b.global_task_id = '$global_task_id' and b.department_id = c.group_id";
			     //echo '<br>'.$sql_fct;
				 $result_fct = $this->db->query($sql_fct,__FILE__,__LINE__);
				// $row_fct = $this->db->fetch_array($result_fct);
		       	 while($row_fct = $this->db->fetch_array($result_fct)){

					$fct = strtolower("$row_fct[group_name]");
					$sql_prod = "SELECT * FROM ".erp_PRODUCT." WHERE product_id = '$row_min[product_id]'";				
					$result_prod = $this->db->query($sql_prod,__FILE__,__LINE__);
					$row_prod = $this->db->fetch_array($result_prod);
	
					$tot_inve = '';
					$tot_size_dep_inve = '';
					$sql_in = "SELECT * FROM ".erp_WORK_ORDER." WHERE product_id = '$row_min[workorder_id]'";
					//echo $sql_in;
					
					$result_in = $this->db->query($sql_in,__FILE__,__LINE__);
					$row_in = $this->db->fetch_array($result_in);
					if( $row_in[fabric] != '' ){
						$tot_inve .= $row_in[fabric].'_';
					} 
					if( $row_in[zipper] != '' ){
						$tot_inve .= $row_in[zipper].'_';
					}
					if( $row_in[pad] != '' ){
						$tot_inve .= $row_in[pad].'_';
					}
					if( $row_in[elastic] != '' ){
						$tot_inve .= $row_in[elastic].'_';
					}
					if( $row_in[lining] != '' ){
						$tot_inve .= $row_in[lining].'_';
					}					
					
					$inven = explode('_',$tot_inve);
					$len = count($inven);
				   
					$inve_total = 0;
					for($i=0;$i<($len-1);$i++){
					
					    $inve_ids = explode(',',$inven[$i]);
						//print_r($inve_ids);
						if(count($inve_ids) > 1){						
							for($j=0;$j<(count($inve_ids)-1);$j++){
								$sql_size_group="SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE product_id='$row_min[product_id]' and inventory_id='$inve_ids[$j]' and group_inventory_id = '$inve_ids[0]'";
								//echo $sql_size_group;
								$result_size_group = $this->db->query($sql_size_group,__FILE__,__LINE__);
								$row_size_group = $this->db->fetch_array($result_size_group);
								$inve_total += ($row_size_group['inventory_'.$fct] * $row_min[quantity]);
							}
						}			
					 	else{
							$sql_size="SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE product_id='$row_min[product_id]' and inventory_id='$inven[$i]' and group_inventory_id = '0'";
							//echo $sql_size.'aaaaaaaaa<br>';
							$result_size = $this->db->query($sql_size,__FILE__,__LINE__);
							$row_size = $this->db->fetch_array($result_size);
							$inve_total += ($row_size['inventory_'.$fct] * $row_min[quantity]);
						}
						//echo '<br><br><br>inve total = '.$inve_total.'<br>';
					}
					
					$inve_total1 = 0;
					$sql_size1 = "SELECT * FROM ".erp_SIZE_DEPENDENT." WHERE product_id = '$row_min[workorder_id]'";
					//echo "<br>sql_size1:  $sql_size1<br>";
					$result_size1 = $this->db->query($sql_size1,__FILE__,__LINE__);
					while($row_size1 = $this->db->fetch_array($result_size1)){
						$sizes = array('xs','s','m','l','xl','2x','3x','4x');
						foreach($sizes as $size){						
							$size_dep = $size."_size_dependant";
							
							if($row_size1[$size_dep] != ''){ 
								$split_size = explode('_',$row_size1[$size_dep]);
								$sql_check = "Select * from erp_size where product_id = '$row_min[workorder_id]'";
								//echo $sql_check;
								$result_check = $this->db->query($sql_check,__FILE__,__LINE__);
								$row_check = $this->db->fetch_array($result_check);
								if($this->db->num_rows($result_check) > 0){
									$prod_id = $row_min[workorder_id];
								}
								else {
									$sql_check1 = "Select * from erp_product_order where workorder_id = '$row_min[workorder_id]'";
									//echo $sql_check1;
									$result_check1 = $this->db->query($sql_check1,__FILE__,__LINE__);
									$row_check1 = $this->db->fetch_array($result_check1);
									$prod_id = $row_check1[gp_id];
								}		
													
								$sql_get_qty = "SELECT a.size,a.quantity from erp_size a, erp_size_dependant b where a.product_id = b.product_id and a.product_id = '$prod_id' and a.order_id =b.order_id and b.order_id = '$row_min[order_id]' and lOWER(SPLIT_STR(a.size, '_', 2))  =  '$split_size[1]'";
								
								//echo $sql_get_qty;
								$result_get_qty = $this->db->query($sql_get_qty,__FILE__,__LINE__);
								$row_get_qty = $this->db->fetch_array($result_get_qty);
								
								$sql_size2 = "SELECT `group` FROM ".erp_SIZE_DEPENDENT." WHERE option_type='$row_size1[option_type]' and product_id = '$row_min[workorder_id]' and order_id = '$row_min[order_id]'";
								$result_size2 = $this->db->query($sql_size2,__FILE__,__LINE__);
								$row_size2 = $this->db->fetch_array($result_size2);
										
								if($row_size2['group'] > 0){
									$multi_inve = explode(',',$split_size[0]);
									$len1 = count($multi_inve);
									for($i=0;$i<($len1);$i++){								
										$sql_size_group="SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE product_id='$row_min[product_id]' and inventory_id='$multi_inve[$i]' and group_inventory_id = '$row_size2[group]'";
										//echo $sql_size_group;
										$result_size_group = $this->db->query($sql_size_group,__FILE__,__LINE__);
										$row_size_group = $this->db->fetch_array($result_size_group);
										$inve_total1 += ($row_size_group['inventory_'.$fct] * $row_get_qty[quantity]);
										//echo 'inve total1gp = '.$inve_total1.'--'.$row_size_group['inventory_'.$fct].'--'.$row_get_qty[quantity].'<br>'; 
									}
								}
								else {
									$sql_size="SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE product_id='$row_min[product_id]' and inventory_id='$split_size[0]' and group_inventory_id = '0'";
									//echo $sql_size;
									$result_size = $this->db->query($sql_size,__FILE__,__LINE__);
									$row_size = $this->db->fetch_array($result_size);
									$inve_total1 += ($row_size['inventory_'.$fct] * $row_get_qty[quantity]);
									//echo 'inve total1 = '.$inve_total1.'--'.$row_size['inventory_'.$fct].'--'.$row_get_qty[quantity].'<br>'; 									
								}	
							}
						}			
					}
					$total = ($inve_total ) + ($inve_total1) + $row_prod['order_'.$fct] + ($row_prod['per_item_'.$fct] * $row_min[quantity]) + ($row_prod['per_size_'.$fct] * $count_product);
					//echo $global_task_id.'====='.$inve_total.'non size '.$inve_total1 .' size '.$row_prod['order_'.$fct] .' wo specific '.($row_prod['per_item_'.$fct]* $row_min[quantity]).' per item '.($row_prod['per_size_'.$fct] * $count_product).' per size '.$row_min[quantity].'<br>';	
					
					
					$this->final_total += $total;
					//echo $this->final_total;
			    }
			}
		}
		return $this->final_total;
	} /////end of function showProductValue     			
}
?>
