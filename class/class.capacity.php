<?php
class Capacity {
    var $db;
    var $final_total = 0; 
    var $cc;
    var $start_date;
    var $end_date;
        function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
                $this->cc = new CapacityCalc;
                $this->start_date = strtotime("now");
                $this->end_date = strtotime("+1 week");
		//$this->validity = new ClsJSFormValidation();
		//$this->Form = new ValidateForm();
	}
        function set_date_range( $start_date , $end_date ){
            $this->start_date = strtotime($start_date);
            $this->end_date = strtotime($end_date);
        }
        function getWorkingDays($startDate,$endDate,$holidays){
            // do strtotime calculations just once
            $endDate = strtotime($endDate);
            $startDate = strtotime($startDate);


            //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
            //We add one to inlude both dates in the interval.
            $days = ($endDate - $startDate) / 86400 + 1;

            $no_full_weeks = floor($days / 7);
            $no_remaining_days = fmod($days, 7);

            //It will return 1 if it's Monday,.. ,7 for Sunday
            $the_first_day_of_week = date("N", $startDate);
            $the_last_day_of_week = date("N", $endDate);

            //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
            //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
            if ($the_first_day_of_week <= $the_last_day_of_week) {
                if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
                if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
            }
            else {
                // (edit by Tokes to fix an edge case where the start day was a Sunday
                // and the end day was NOT a Saturday)

                // the day of the week for start is later than the day of the week for end
                if ($the_first_day_of_week == 7) {
                    // if the start date is a Sunday, then we definitely subtract 1 day
                    $no_remaining_days--;

                    if ($the_last_day_of_week == 6) {
                        // if the end date is a Saturday, then we subtract another day
                        $no_remaining_days--;
                    }
                }
                else {
                    // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
                    // so we skip an entire weekend and subtract 2 days
                    $no_remaining_days -= 2;
                }
            }

            //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
        //---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
           $workingDays = $no_full_weeks * 5;
            if ($no_remaining_days > 0 )
            {
              $workingDays += $no_remaining_days;
            }

            //We subtract the holidays
            foreach($holidays as $holiday){
                $time_stamp=strtotime($holiday);
                //If the holiday doesn't fall in weekend
                if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
                    $workingDays--;
            }

            return $workingDays;
        }

        function get_order_from_workorder( $work_order ){
            $order_arr = $this->db->fetch_assoc($this->db->query("SELECT * FROM erp_product_order WHERE workorder_id = '" . $work_order. "'"));
            $order_id = $order_arr["order_id"];
            return $order_id;
        }
        function get_order_info( $order_id ){
            $sql = "SELECT * FROM erp_order WHERE order_id = '$order_id'";
            return $this->db->fetch_assoc($this->db->query($sql));
        }
        function get_tasks(){
            $sql="SELECT * FROM `assign_flow_chart_task` WHERE task_status = 'Active'";
            $result = $this->db->query($sql);
            $tasks = array();
            while( $row=$this->db->fetch_assoc($result)){
                if( $row["module"] == "order" || $row["module"] == "work order"){ //Need to make sure that we only check capacity on order or work order
                    if( $row["module"] == "order"){
                        $row["order_id"] = $row["module_id"];
                    } else {
                        $row["order_id"] = $this->get_order_from_workorder($row["module_id"]);
                    }
                    $tasks[] = $row;
                }
                
            }
            return $tasks;
        }
        
        function get_tasks_next_task( $flow_chart_id ){
            $sql = "SELECT c.* FROM `tbl_global_task_link` a 
            LEFT JOIN `tbl_global_task_link` b ON a.to_module_name = b.from_module_name AND a.to_module_id = b.from_module_id  
            JOIN tbl_global_task c ON b.to_module_id = c.global_task_id
            JOIN tbl_global_task_status d ON a.to_module_id = .d.global_task_status_id AND a.to_module_name = 'status'
            WHERE 
            a.from_module_id = '$flow_chart_id' 
            AND a.from_module_name = 'task' 
            AND b.to_module_name = 'task' 
            AND c.default_path = '1'
            AND d.default_path = '1'";
            
            $result = $this->db->query($sql);
            //return $sql;
            
                return $this->db->fetch_assoc($result);

        }
        
        function get_task_info( $flow_chart_id ){
            $sql = "SELECT * FROM tbl_global_task WHERE global_task_id = '$flow_chart_id'";
            return $this->db->fetch_assoc($this->db->query($sql));
        }
         
        function get_prorate_percent( $order_id , $flow_chart_id ){
            $predicted_days = $this->get_total_predicted_path_days($flow_chart_id);
            $order_info = $this->get_order_info($order_id);
            $working_days = $this->getWorkingDays(date("Y-m-d"), $order_info["ship_date"]);
            $percent = $working_days / $predicted_days ;
            if( $percent < 0 ){ $percent = 0; }
            return $percent;
        }
        
        function get_shedule_cap( $due_date , $department ){
            return 0;
        }
        
        function set_capacitys($array=''){
            if( $array == '' ){
                $array = $this->group_by_department_then_date();
            }
            foreach( $array as $department => $dates ){
                foreach( $dates as $date => $cap ){
                    $da= $this->db->fetch_assoc($this->db->query("SELECT * FROM tbl_capacity WHERE due_date LIKE '" . $date . "%' AND department = '$department'"));
                    if( $da == false ){
                        $insert_array = array();
                        $insert_array["estimated_capacity"] = $cap;
                        $insert_array["due_date"] = $date;
                        $insert_array["department"] = $department;
                        $insert_array["shedule_capacity"] = $this->get_shedule_cap($date, $department);
                        $this->db->insert('tbl_capacity', $insert_array );
                    } else {
                        $this->db->query("UPDATE tbl_capacity SET `estimated_capacity` = '$cap' WHERE due_date LIKE '$due_date%' AND department = '$department'" );
                    }
                }
                
                
                //$this->db->query("INSERT INTO tbl_capacity  ");
                
            }
        }
        
        function get_total_predicted_path_days( $current ){
            $x = 0;
            $total = 0;
            while( $x == 0 ){
                $row = $this->get_tasks_next_task($current);
                if( $row != false ){
                    $current = $row["global_task_id"];
                    //echo $current . "<br>\n";
                    $total = $total + $row["est_day_dep"];
                } else { $x = 1; }
            }
            return $total;
        }
        
        
        
        
        function get_prorated_duedates(){
            $tasks = $this->get_tasks();
            $return = array();
            foreach( $tasks as $task ){
                //$order_info = $this->get_order_info($task["order_id"]);
                $percent = $this->get_prorate_percent($task["order_id"], $task["flow_chart_id"]);
                $current = $task["flow_chart_id"];
                $date = mktime();
                $tmparr = $this->get_task_info($task["flow_chart_id"]);
                $tmparr["prorate_days"] = $tmparr["est_day_dep"] * $percent;
                $date = $date + ($tmparr["prorate_days"] * 86400 );
                $tmparr["duedate"] = date("Y-m-d" , $date );
                $tmparr["product"] = '';
                if(strtotime($tmparr["duedate"]) >= $this->start_date && strtotime($tmparr["duedate"]) <= $this->end_date){
                    $tmparr["capacity"] = $this->cc->calculate_capacity($task["module"], $task["module_id"], $tmparr["global_task_id"]);
                } else {
                   $tmparr["capacity"] = ''; 
                }
                $tmparr["master"] = "yes";
                $tmparr["order_id"] = $task["order_id"];
                $return[] = array_merge($tmparr , $task );
                $pr = $tmparr;
                $x = 0;
                while( $x == 0 ){
                    //echo __LINE__ . "x \n";
                    $row = $this->get_tasks_next_task($current);
                    if( $row != false ){
                          
                          $row["prorate_days"] = $row["est_day_dep"] * $percent;
                          $date = $date + ($row["prorate_days"] * 86400 );
                          $row["duedate"] = date("Y-m-d" , $date );
                          $row["product"] = '';
                          if(strtotime($row["duedate"]) >= $this->start_date && strtotime($row["duedate"]) <= $this->end_date){
                            $row["capacity"] =  $this->cc->calculate_capacity($task["module"], $task["module_id"], $row["global_task_id"]);
                          } else {
                             $row["capacity"] =  ''; 
                          }
                          //$row["capacity"] = '';
                          $row["master"] = "no";
                          $row["order_id"] = $task["order_id"];
                          if( $pr["module"] == "order" && $row["module"] == "work order"){
                              $sql = "SELECT * FROM `erp_product_order` WHERE order_id='" . $task["order_id"] . "' AND gp_id = '0'";
                              $result = $this->db->query($sql);
                              while( $row2 = $this->db->fetch_assoc($result)){
                                  //echo __LINE__ . "row2 \n";
                                  $y = 0;
                                  $current2 = $current;
                                  while( $y == 0 ){
                                     // echo __LINE__ . "y \n";
                                      $date2 = $date;
                                      
                                      $row3 = $this->get_tasks_next_task($current2);
                                    // print_r( $row3 );
                                      if( $row3 != false ){
                                        $row3["prorate_days"] = $row3["est_day_dep"] * $percent;
                                          $date2 = $date2 + ($row3["prorate_days"] * 86400 );
                                          $row3["duedate"] = date("Y-m-d" , $date2 );
                                          $row3["product"] = $row2["product_name"];
                                          if( $row3["module"] == "order"){
                                              $mid = $row2["order_id"];
                                          } else {
                                              $mid = $row2["workorder_id"];
                                          }
                                          if(strtotime($row3["duedate"]) >= $this->start_date && strtotime($row3["duedate"]) <= $this->end_date){
                                            $row3["capacity"] =  $this->cc->calculate_capacity($row3["module"], $mid, $row3["global_task_id"]);
                                          } else {
                                             $row3["capacity"] = ''; 
                                          }
                                          //$row3["capacity"] = '';
                                          $row3["master"] = "no";
                                          $row3["order_id"] = '';
                                          $current2 = $row3["global_task_id"];
                                          $return[] = array_merge( $row3 , $task );
                                      } else { $y = 1; }
                                  }
                              }
                                  
                              $x = 1;
                          } else {
                              $return[] = array_merge( $row , $task );
                              $pr = $row;
                              $current = $row["global_task_id"];
                          }
                    } else { $x = 1; }   
                }
                
                
            }
            return $return;
        }
        
        function get_department_name_arr(){
            $result = $this->db->query("SELECT * FROM tbl_usergroup");
            $return = array();
            while( $row=$this->db->fetch_assoc($result)){
                $return[$row["group_id"]] = $row["group_name"];
            }
            return $return;
        }
        
        
        function generate_daterange_arr(){
            $return = array();
            $now = $this->start_date;
            while( $now <= $this->end_date ){
                $return[ date("Y-m-d" , $now)] = '';
                $now = strtotime("+1 day" , $now);
            }
            
            return $return;
        }
        
        function check_capacity_table( $department , $date ){
            $date = $this->db->fetch_assoc($this->db->query("SELECT * FROM tbl_capacity WHERE due_date = '$date' AND department = '$department'"));
            if( $date == false){
                $this->db->insert( 'tbl_capacity', array( 'due_date' => $date , 'department' => $department));
                $date = $this->db->fetch_assoc($this->db->query("SELECT * FROM tbl_capacity WHERE due_date = '$date' AND department = '$department'"));
            }
            return $date;
        }
        
        function get_weekly_avalible_capacity( $department ){
            $start = date("Y-m-d" , $this->start_date);
            $end = date("Y-m-d" , $this->end_date);
            $date = $this->db->fetch_assoc($this->db->query("SELECT sum( shedule_capacity ) total FROM tbl_capacity WHERE due_date BETWEEN '$start' AND '$end' AND department = '$department'"));
            return $date["total"];
            
        }
        
          function get_daily_avalible_capacity( $department , $date ){
            $datea = $this->db->fetch_assoc($this->db->query("SELECT shedule_capacity FROM tbl_capacity WHERE due_date LIKE '$date%' AND department = '$department'"));
            if( $datea == false ){
                $this->db->insert("tbl_capacity" , array("department" => $department , "due_date" => $date));
            }
            return $datea["shedule_capacity"];
            
        }      
        
        function group_by_department_then_date(){
            $capacity_arr = $this->get_prorated_duedates();
            $return = array();
            $gp = $this->get_department_name_arr();
            foreach( $capacity_arr as $cap ){
                if(array_key_exists($gp[$cap["department_id"]], $return) == false ){
                    $return[$gp[$cap["department_id"]]] = array();
                }
                if(array_key_exists($gp[$cap["duedate"]], $return[$cap["department_id"]]) == false ){
                    $return[$gp[$cap["department_id"]]][$cap["duedate"]] = $cap["capacity"];
                } else {
                    $return[$gp[$cap["department_id"]]][$cap["duedate"]] = $return[$cap["department_id"]][$cap["duedate"]] + $cap["capacity"];
                }
            }
            $cleanr = array();
            foreach( $return as $department => $datearr){
                $cleanr[$department] = $this->generate_daterange_arr();
                foreach( $cleanr[$department] as $n => $v){
                    if(array_key_exists("$n", $datearr)){
                        $cleanr[$department][$n] = $datearr[$n];
                    }
                }
                
            }
            
            return $cleanr;
        }
        
        function calculate_used_per_order( $module_name , $module_id , $product_prepend ){
            $capacity_used = 0;
            if( $module_name == 'order'){
                $wo_where = "order_id = '$module_id'";
            } else {
                $wo_where = "workorder_id = '$module_id'";
            }
            $wosql = "SELECT * FROM erp_product_order a JOIN erp_product b ON a.product_id = b.product_id WHERE $wo_where GROUP BY a.product_id";
            //echo $wosql . "<br>";
            $wo_result = $this->db->query($wosql);
            $woarr = array();
            while( $row = $this->db->fetch_assoc($wo_result)){
                //echo "order_$product_prepend " .$row["order_" . $product_prepend] . "<br/>" ;
                $capacity_used = $capacity_used + $row["order_" . $product_prepend];
            }
            return $capacity_used;
        }
        
        
        
        function calculate_capacity( $module_name , $module_id , $global_task_id ){
            $sql = "SELECT * FROM tbl_global_task a JOIN tbl_usergroup b ON a.department_id = b.group_id WHERE global_task_id = '$global_task_id'";
            $global_task_info = $this->db->fetch_assoc($this->db->query($sql)) ;
            $capacity_used = $global_task_info["est_min_task"];
            $capacity_used = 0;
            if( $global_task_info["department_chk_tsk"] == "yes"){
                switch( $global_task_info["group_name"]){
                    
                    default:
                        $product_prepend = strtolower($global_task_info["group_name"]);
                    break;
                    case "Dye Sublimation":
                        $product_prepend = "sub";
                    break;
                    case "Ship":
                        $product_prepend = "shipping";
                    break; 
                }
                $capacity_used = $capacity_used + $this->calculate_used_per_order($module_name, $module_id, $product_prepend);
                
            }
            
            return $capacity_used;
        }
        
        
        
/*           
    function calculate_capacity( $module_name , $module_id , $global_task_id ){  

			$capacity = 0;
			$fcts_info = array();
			$fcts_info = $this->activeGTID($module_name , $module_id, $global_task_id);
			
			//print_r($fcts_info);
          	foreach($fcts_info as $fct_info){
				if(($fct_info['fct_id'] == $global_task_id) and ($fct_info['module'] == $module_name) and ($fct_info['module_id'] == $module_id)){
					//echo 'br'.$fct_info['total_min'].'======'.$fct_info['est_min'];
					$capacity = $fct_info['total_min'] + $fct_info['est_min'];
				}
			}		   
		   return $capacity;
        }
*/		
	function activeGTID($module_name='', $module_id='', $global_task_id=''){

		$sql= "Select a.*,b.est_day_dep,b.est_min_task,b.department_chk_tsk,c.group_name from ".erp_ASSIGN_FCT." a, ".erp_GLOBAL_TASK." b, ".erp_USERGROUP." c where a.task_status = 'Active' and a.flow_chart_id = b.global_task_id and b.department_id = c.group_id  order by due_date";
		//echo $sql;
		
		$result = $this->db->query($sql,__FILE__,__LINE__); 
		while( $row = $this->db->fetch_array($result) ){
			//$this->fct_array[] = array($row['flow_chart_id'],$row['group_name'],$row['due_date'],$row['est_day_dep'],$row['est_min_task']);
	
			$tot_min = $this->showProductValue($row['module'],$row['module_id']); 
							
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
		
	function showProductValue($module_name='', $module_id='', $sub_module_id=''){	

	  if(!$sub_module_id) $this->final_total = 0;
	  if($module_id and ($module_name == 'order') and $sub_module_id) { $flag=1;   }

	  $i = 0;
	  
	  $sql_min = "SELECT a.workorder_id, a.product_id, a.order_id, a.product_name, count(a.product_id) as 'total', SUM(b.quantity) as 'quantity' FROM ".erp_PRODUCT_ORDER." a, ".erp_SIZE." b WHERE 1 ";
	  
	  if( $module_name == 'order' and !$sub_module_id){
	  	  $sql_min .= " and a.order_id = '$module_id'";
		  $module_name = 'order';
	  } 
	  else if($module_name == 'work order'){
	  	  $sql_min .= " and a.workorder_id = '$module_id'";
		  $module_name = 'work order';	
	  }
	  else if($sub_module_id !=''){
	  	  $sql_min .= " and a.workorder_id = '$sub_module_id'";
		  $variable = 'order';	
	  }

	  if($module_name == 'order') $sql_min .= " GROUP BY a.product_id";
	  else $sql_min .= " and a.workorder_id = b.product_id GROUP BY a.workorder_id";

		//echo $sql_min;
	  $result_min = $this->db->query($sql_min,__FILE__,__LINE__);
	  if( $this->db->num_rows($result_min) > 0 ){
		  while( $row_min = $this->db->fetch_array($result_min) ){
				 $product_name = $row_min[product_name];
				 $count_product = $row_min[total];
				 $quantity = $row_min[quantity];
				 
				 if($module_name == 'work order'){
				     $module = $row_min[workorder_id];
				 } else { $module = $module_id; }
				 
				 $i++;
				 
				 $sql_fct = "SELECT c.group_name FROM ".erp_ASSIGN_FCT." a, ".erp_GLOBAL_TASK." b, ".erp_USERGROUP." c WHERE a.module = '$module_name' and a.module_id = '$module_id' and a.task_status = 'Active' and a.flow_chart_id = b.global_task_id and b.department_id = c.group_id ";
			     //echo $sql_fct;
				 $result_fct = $this->db->query($sql_fct,__FILE__,__LINE__);
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
						if(count($inve_ids) > 1){						
							for($j=1;$j<(count($inve_ids)-1);$j++){
								$sql_size_group="SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE product_id='$row_min[product_id]' and inventory_id='$inve_ids[$j]' and group_inventory_id = '$inve_ids[0]'";
								$result_size_group = $this->db->query($sql_size_group,__FILE__,__LINE__);
								$row_size_group = $this->db->fetch_array($result_size_group);
								$inve_total += $row_size_group['inventory_'.$fct];
							}
						}			
					 	else{
							$sql_size="SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE product_id='$row_min[product_id]' and inventory_id='$inven[$i]' and group_inventory_id = '0'";
							$result_size = $this->db->query($sql_size,__FILE__,__LINE__);
							$row_size = $this->db->fetch_array($result_size);
							$inve_total += $row_size['inventory_'.$fct];
						}
						//echo 'inve total = '.$inve_total.'<br>';
					}
					
					$inve_total1 = 0;
					$sql_size1 = "SELECT * FROM ".erp_SIZE_DEPENDENT." WHERE product_id = '$row_min[workorder_id]'";
					//echo $sql_size1;
					$result_size1 = $this->db->query($sql_size1,__FILE__,__LINE__);
					while($row_size1 = $this->db->fetch_array($result_size1)){
						
						if( $row_size1['xs_size_dependant'] != '' ){
							$explode_string = explode('_',$row_size1['xs_size_dependant']);
							$tot_size_dep_inve .= $explode_string[0].'_';
						} 
						if( $row_size1['s_size_dependant'] != '' ){				
							$explode_string = explode('_',$row_size1['s_size_dependant']);
							$tot_size_dep_inve .= $explode_string[0].'_';
						}
						if( $row_size1['m_size_dependant'] != '' ){
							$explode_string = explode('_',$row_size1['m_size_dependant']);
							$tot_size_dep_inve .= $explode_string[0].'_';
						}
						if( $row_size1['l_size_dependant'] != '' ){
							$explode_string = explode('_',$row_size1['l_size_dependant']);
							$tot_size_dep_inve .= $explode_string[0].'_';
						}
						if( $row_size1['xl_size_dependant'] != '' ){
							$explode_string = explode('_',$row_size1['xl_size_dependant']);
							$tot_size_dep_inve .= $explode_string[0].'_';
						}
						if( $row_size1['2x_size_dependant'] != '' ){
							$explode_string = explode('_',$row_size1['2x_size_dependant']);
							$tot_size_dep_inve .= $explode_string[0].'_';
						}
						if( $row_size1['3x_size_dependant'] != '' ){
							$explode_string = explode('_',$row_size1['3x_size_dependant']);
							$tot_size_dep_inve .= $explode_string[0].'_';
						}
						if( $row_size1['4x_size_dependant'] != '' ){
							$explode_string = explode('_',$row_size1['4x_size_dependant']);
							$tot_size_dep_inve .= $explode_string[0].'_';
						}
					
						$inven1 = explode('_',$tot_size_dep_inve);
						$len1 = count($inven1);
					   
						for($i=0;$i<($len1-1);$i++){
							$inve_ids1 = explode(',',$inven1[$i]);
							if($row_size1['group']){						
								for($j=0;$j<count($inve_ids1);$j++){
									$sql_size_group="SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE product_id='$row_min[product_id]' and inventory_id='$inve_ids1[$j]' and group_inventory_id = '$row_size1[group]'";
									//echo $sql_size_group;
									$result_size_group = $this->db->query($sql_size_group,__FILE__,__LINE__);
									$row_size_group = $this->db->fetch_array($result_size_group);
									$inve_total1 += $row_size_group['inventory_'.$fct];	
									//echo 'inve total1 = '.$inve_total1.'----'.$row_size1[group].'<br>';
								}
							}
							else {
								$sql_size="SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE product_id='$row_min[product_id]' and inventory_id='$inve_ids1[$j]' and group_inventory_id = '0'";
								//echo $sql_size;
								$result_size = $this->db->query($sql_size,__FILE__,__LINE__);
								$row_size = $this->db->fetch_array($result_size);
								$inve_total1 += $row_size['inventory_'.$fct];	
								//echo 'inve total1 = '.$inve_total1.'---'.$row_size1[group].'<br>';				
							}	
						}
					} 
					$total = $inve_total + $inve_total1 + $row_prod['order_'.$fct] + ($row_prod['per_item_'.$fct] * $quantity) + ($row_prod['per_size_'.$fct] * $count_product);
					//echo $inve_total.'non size '.$inve_total1.' size '.$row_prod['order_'.$fct] .' wo specific '.($row_prod['per_item_'.$fct] * $quantity).' per item '.($row_prod['per_size_'.$fct] * $count_product).' per size <br>';	
					$this->final_total += $total;
					//echo $this->final_total;
					///////////     Code for Sub Product Id      /////////////		
					$sql_sub = "SELECT workorder_id FROM ".erp_PRODUCT_ORDER." WHERE gp_id = '$row_min[workorder_id]' and order_id = '$row_min[order_id]'";
					//echo $sql_sub;
					$result_sub = $this->db->query($sql_sub,__FILE__,__LINE__);
					if( $this->db->num_rows($result_sub) > 0 ){							
						while( $row_sub = $this->db->fetch_array($result_sub) ){								
							$this->showProductValue('order', $row_min[order_id],$row_sub[workorder_id]);								
						}
					}
					else { continue; }	
			    }
			}
		}
		return $this->final_total;
	} /////end of function showProductValue 
        
}
?>
