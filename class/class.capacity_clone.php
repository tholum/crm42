<?php
require_once('class/class.CapacityCalc.php');
require_once('class/class.Capacity.php');
class CapacityReport{

var $capacity_cal;	
var $main_array = array();
var $fct_array = array();	
var $daily_array = array();	

    function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
		$this->capacity_cal = new CapacityCalc();
	}
	
	function showDateTextbox(){
		ob_start();
		?>
		<table>
		   <tr>
			  <td>
				 <input  type="text" name="start_date" id="start_date" value="<?php echo date('Y-m-d'); ?>" size="10"/>
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
										document.getElementById('meter1').style.display='none';
										capacity.searchDueDateData( document.getElementById('start_date').value,
																	document.getElementById('end_date').value,
																	'week',
																	{preloader:'prl',
																	 onUpdate: function(response,root){
																		 document.getElementById('capacity').innerHTML = response;
																		 start_cal();
																		 end_cal();
																		 $('#search_tables').tablesorter({widthFixed:true,
																		 widgets:['zebra'],sortList:[[0,0]],headers: {}	});
																}});
									  }
								  });			
							   }
						start_cal();	   
				 </script>			  
			  </td>
			  <td> to </td>
			  <td>
				 <input  type="text" name="end_date" id="end_date" value="" size="10"/>
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
										document.getElementById('meter1').style.display='none';
										capacity.searchDueDateData( document.getElementById('start_date').value,
																	document.getElementById('end_date').value,
																	'week',
																	{preloader:'prl',
																	 onUpdate: function(response,root){
																		 document.getElementById('capacity').innerHTML = response;
																		 start_cal();
																		 end_cal();
																		 $('#search_tables').tablesorter({widthFixed:true,
																		 widgets:['zebra'],sortList:[[0,0]],headers: {}	});
																}});
									  }
								  });			
							   }
						end_cal();	
				 </script>			  
			  </td>			  
		   </tr>			
		</table>
		<?php
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function searchDueDateData($start_date='',$end_date='',$dis_type='',$department=''){
		ob_start();
		if($dis_type == 'week'){ 
			$add_days = '6';
			$start_day = date('N',strtotime($start_date));
			if($start_day != 1)	$start_date=date('Y-m-d',strtotime("last monday",strtotime($start_date)));
			
			if($end_date){
				 $end_day = date('N',strtotime($end_date));
				 if($end_day != 7) $end_date=date('Y-m-d',strtotime("next sunday",strtotime($end_date)));
			}
			else {
				 if($start_day != 7) $end_date=date('Y-m-d',strtotime("next sunday",strtotime($start_date)));
			}

			$s_date = $start_date;
			while(strtotime($s_date) < strtotime($end_date)){
				$sql_department = "Select distinct c.group_id,c.group_name from ".erp_ASSIGN_FCT." a, ".erp_GLOBAL_TASK." b, ".erp_USERGROUP." c where a.task_status='Active' and a.flow_chart_id=b.global_task_id and b.department_id=c.group_id ";
				//echo $sql_department;
				$result_department = $this->db->query($sql_department,__FILE__,__LINE__);  
				while($row_department = $this->db->fetch_array($result_department)) {
					$this->main_array[] = array($s_date, date("Y-m-d", strtotime("+".$add_days." day", strtotime($s_date))),$row_department['group_id'],$row_department['group_name']);
				}
				if($dis_type == 'week')	$s_date = date ("Y-m-d", strtotime ("+".($add_days+1)." day", strtotime($s_date)));
			}		
			foreach($this->main_array as $array_data){
				$this->fct_array = array();
				if(($week_start_date != $array_data[0]) and ($week_end_date != $array_data[1])){
					$week_start_date = $array_data[0];
					$week_end_date = $array_data[1]; ?>
					<div><?php echo $array_data[0].' - '.$array_data[1]."<br><br>"; ?></div><?php 
				}				
				$show_val = $this->activeGTID($array_data[0],$array_data[1],$array_data[2]);
				echo $this->CapacityMeter("week",$array_data[3],'','','',$show_val,($array_data[0].'_'.$array_data[1]),'daily','','','',$array_data[2]);
			}
		}	
		else if($dis_type == 'daily'){
			$add_days = '1';
			$daily_date = $start_date;
			while(strtotime($daily_date) <= strtotime($end_date)){
				$sql_department = "Select c.group_name from ".erp_ASSIGN_FCT." a, ".erp_GLOBAL_TASK." b, ".erp_USERGROUP." c where a.task_status='Active' and a.flow_chart_id=b.global_task_id and b.department_id=c.group_id and b.department_id = '$department'";
				//echo $sql_department;
				$result_department = $this->db->query($sql_department,__FILE__,__LINE__);  
				$row_department = $this->db->fetch_array($result_department);		
				$this->daily_array[] = array($daily_date, date("Y-m-d", strtotime("+".$add_days." day", strtotime($daily_date))),$department,$row_department['group_name']);
				$daily_date = date ("Y-m-d", strtotime ("+".($add_days)." day", strtotime($daily_date)));
			}	
			foreach($this->daily_array as $daily_data){
				$this->fct_array = array();
				$week_day = date('l',strtotime($daily_data[0]));
				$show_val = $this->activeGTID($daily_data[0],$daily_data[1],$daily_data[2]);
				echo $this->CapacityMeter('week_day',$daily_data[3],$week_day,'','',$show_val,$daily_data[0],'daily','','','',$daily_data[2]);
			}	
		}
		//print_r($this->daily_array);	
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	
	function activeGTID($start_date='',$end_date='',$group_id=''){
		ob_start();
		
		$sql= "Select a.*,b.est_day_dep,b.est_min_task,b.department_chk_tsk,c.group_name,c.group_id from ".erp_ASSIGN_FCT." a, ".erp_GLOBAL_TASK." b, ".erp_USERGROUP." c where a.task_status='Active' and a.flow_chart_id=b.global_task_id and b.department_id=c.group_id and b.department_id='$group_id' and a.due_date BETWEEN '$start_date' AND '$end_date' order by due_date";
		//echo $sql;
		
		$result = $this->db->query($sql,__FILE__,__LINE__); 
		$i=0;
		if($this->db->num_rows($result)>0){
			while( $row = $this->db->fetch_array($result) ){
				$i++;
	
				$sql_ship = "SELECT ship_date FROM erp_order WHERE order_id = '$row[product_id]'";
				$result_ship = $this->db->query($sql_ship,__FILE__,__LINE__);
				$row_ship = $this->db->fetch_array($result_ship);
				 
				$total_est_day = $this->totalEstDay($row['flow_chart_id']);
				$this->task_est_day = ($total_est_day + $row['est_day_dep']) * 86400;		 
				$task_estimated_date = $this->estDueDate($this->task_est_day,time(),$row_ship['ship_date']);		 
				$percentage = $task_estimated_date/($total_est_day + $row['est_day_dep']);		 
							
				$system_min = $this->capacity_cal->calculate_capacity($row['module'],$row['module_id'],$row['flow_chart_id']);	
							
				$this->fct_array[] = array("fct_id" => $row['flow_chart_id'], "department" => $row['group_name'], "due_date" => $row['due_date'], "est_day" => $row['est_day_dep'], "est_min" => $row['est_min_task'], "check_point" => $row['department_chk_tsk'] , "assign_id" => $row['chart_assign_id'],"order_id" => $row['product_id'], "module" => $row['module'],"module_id" => $row['module_id'],"root_id" => $row['flow_chart_id'], "total_min" => $system_min);
	
				//echo $i." - Module Name : ".$row['module']." ,  Module Id : ".$row['module_id']." ,  FCT Id : ".$row['flow_chart_id']." ,  System Min : ".$system_min." ,  Check Point : ".$row['department_chk_tsk']." ,  Est MIn : ".$row['est_min_task']." ,  Due Date : ".$row['due_date']." ,  Department :  ".$row[group_name]." <br><br>";
	
				echo $this->predictPathFct($row['flow_chart_id'],$row['due_date'],$row['est_day_dep'],'active',$row['flow_chart_id'],$row['module'],$row['module_id'],$system_min , $row['product_id'],$percentage,$row['group_id']); 
			}
		}
		
		//print_r($this->fct_array);
		
		$total = 0;
		foreach($this->fct_array as $array)
		{
		   $total += $array['total_min'];
		}
		echo $total;
		
		
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function totalEstDay($gtid=''){
		 $sql_status = "Select * from tbl_global_task_status where global_task_id = '$gtid' and default_path = '1'";
		 //echo $sql_status;
		 $result_status = $this->db->query($sql_status,__FILE__,__LINE__);
		 $row_status = $this->db->fetch_array($result_status);
		 $status_id = $row_status[global_task_status_id]; 
		 
		 $sql_name = "Select global_task_id from tbl_global_task_status_result where global_task_status_id = '$status_id'";
		 $result_name = $this->db->query($sql_name,__FILE__,__LINE__);
		 while($row_name=$this->db->fetch_array($result_name)){
			 $sub_task = $row_name[global_task_id];
			 
			 $sql_task = "Select global_task_id,name,module,est_day_dep from ".erp_GLOBAL_TASK." where global_task_id = '$sub_task' and default_path = '1'";
			 $result_task = $this->db->query($sql_task,__FILE__,__LINE__);
			 if($this->db->num_rows($result_task) > 0){
				 $row_task = $this->db->fetch_array($result_task);
				 $sub_task = $row_task[global_task_id];
				 $task_est = $row_task[est_day_dep];
				 
				 $this->task_est_day += $task_est;
				 $this->totalEstDay($sub_task);
		 	 }//end of if
		 }//end of while
		return  $this->task_est_day; 
	} /////end of function predict_path

	 function estDueDate($w_days='',$est_timestamp='',$ship_date=''){
		  $date = new DateHelper; 
		  $est_day = 0;
		  if($ship_date){
		   	$est_day = $date->getWorkingDays(date('Y-m-d'),$ship_date);
			//echo round($w_days,2).' :: '.date("Y-m-d h:i:s A",$est_timestamp).' :: '.$est_day;
			return floor($est_day);
		  }
		  else {
		  	$est_date = date("Y-m-d h:i:s A", $date->addBusinessDays($est_timestamp,$w_days));
			return $est_date;	  
		  }
	} /////end of function estDueDate
	
	function predictPathFct($gtid='', $due_date='' ,$est_day='',$flag='',$root_id='',$module='' ,$module_id = '',$tot_min='',$order_id = '',$percent='',$grp_id=''){
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
		 
		 $sql_task = "Select a.global_task_id, a.name, a.module, a.est_day_dep, a.est_min_task,a.department_chk_tsk, b.group_name from ".erp_GLOBAL_TASK." a,".erp_USERGROUP." b where a.global_task_id = '$sub_task' and a.default_path = '1' and a.department_id = b.group_id and a.department_id='$grp_id'";
		 //echo $sql_task;
		 
		 $result_task = $this->db->query($sql_task,__FILE__,__LINE__);
		 
		 if($this->db->num_rows($result_task) > 0){
			 $row_task = $this->db->fetch_array($result_task);
			 
			 $sub_task = $row_task[global_task_id];
			 $task_name = $row_task[name];
			 $task_mod = $row_task[module];
				 
			 $task_est = $row_task['est_day_dep'] * $percent;
			 $est_seconds = ($task_est * 24 * 60 * 60);
			 $est_date = $this->estDueDate($est_seconds,strtotime($due_date));
			 $system_min = $this->capacity_cal->calculate_capacity($module,$module_id,$sub_task);	
			 
			 //echo $i." - Module Name : ".$module." ,  Module Id : ".$module_id." ,  FCT Id : ".$sub_task." ,  System Min : ".$system_min." ,  Check Point : ".$row_task['department_chk_tsk']." ,  Est MIn : ".$row_task['est_min_task']." ,  Due Date : ".$est_date." ,  Department :  ".$row_task[group_name]." <br>";
			 
			 $this->fct_array[$first_id][$second_id] = array("fct_id" => $sub_task, "department" => $row_task[group_name], "due_date" => $est_date, "est_day" => $row_task['est_day_dep'], "est_min" => $row_task['est_min_task'], "check_point" => $row_task['department_chk_tsk'], "module" => $module, "module_id" => $module_id, "root_id" => $root_id, "total_min" => $system_min);
			 echo $this->predictPathFct($sub_task,$est_date,$row_task[est_day_dep],'',$root_id,$module,$module_id,$tot_min,$order_id,$percent,$grp_id);
		  }//end of if		  
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	} /////end of function predict_path
	
	function CapacityMeter($runat='',$department='',$day='',$name='',$total='',$show_val='',$first_date='',$type='',$un_id='',$i='',$due_date='',$department_id='',$flag=''){
	   ob_start();
	   
	   $size = 120;
	   $sch_capacity = 0;
	   if(!$un_id) $un_id = rand(10,100000);	   
	   $div_id = $department.'_'.$un_id;
	   //if(!$due_date) $due_date = date('Y-m-d');
	   	   
	   switch($runat) {
	   case 'week' : 
	   		$dates = array();
			$dates = explode("_",$first_date);
			
			$sql = "select * from tbl_capacity where due_date BETWEEN '$dates[0]' AND '$dates[1]' and department = '$department'";
			$result = $this->db->query($sql,__FILE__,__LINE__);
			while($row = $this->db->fetch_array($result)){
				$sch_capacity += $row['shedule_capacity'];
				$show_sum_val += $row['estimated_capacity'];
			}
			if($flag == 'refresh') $show_val = $show_sum_val;
			$percentage = ((round($show_val,2)) / $sch_capacity) * 100;
			//echo '<br>'.round($percentage,2).'--'.$show_val;
			?>
			<div id="week_<?php echo $department.strtotime($dates[0]); ?>" style="float:left; width:10%; margin-top:5px">
				<a href="javascript:void(0);"
						 onclick="javascript:capacity.searchDueDateData('<?php echo $dates[0]; ?>',
																		'<?php echo $dates[1]; ?>',
																		'daily',
																		'<?php echo $department_id; ?>',
																		{preloader:'prl',target:'<?php echo $div_id; ?>'});"><img src='<?php echo 'class/gauge.php?value='.round($percentage,2).'&text='.$department.'&size='.$size; ?>' border='0' /></a>
						
			</div>  
			<div id="<?php echo $div_id; ?>" style="float:right;width:88%"></div>
			<div style="clear:both"></div>
			<?php break;
	    case 'week_day' : 
			 $sql = "select * from tbl_capacity where due_date = '$first_date' and department = '$department'";
			 //echo $sql;
			 $result = $this->db->query($sql,__FILE__,__LINE__);
			 $row = $this->db->fetch_array($result);
			 $sch_capacity = $row['shedule_capacity'];
			 $percentage = ($show_val / $sch_capacity) * 100;		 
			 ?>
		    <script>
				document.getElementById('<?php echo $div_id; ?>').style.display = 'block';
			</script>
			<div id="<?php echo $department.$unid; ?>" style="float:left;width:130px">
				<?php if($day == 'Saturday' or $day == 'Sunday'){ echo $day.'<br>'; ?>
					<a href="javascript:void(0);">Add</a>
				<?php } 
				else { ?>					
					<span id="<?php echo 'range_'.$department.$day.$un_id; ?>"></span>
					<div id="<?php echo $department.$day.$un_id; ?>">
						<a href="javascript:void(0);" 
						 onclick="javascript:capacity.CapacityMeter('display',
																	'<?php echo $department; ?>',
																	'<?php echo $day;?>',
																	'',
																	'',
																	'<?php echo $show_val; ?>',
																	'<?php echo $first_date; ?>',
																	'',
																	'<?php echo $un_id; ?>',
																	'','',
																	'<?php echo $department_id; ?>',
																	{preloader:'prl',target:'<?php echo 'range_'.$department.$day.$un_id; ?>'});">
							<img src='<?php echo 'class/gauge.php?value='.round($percentage,2).'&text='.$day.'&size='.$size; ?>' border='0' /></a>
					</div>
				<?php } ?>
			</div>
			<?php
			 break;
	      case 'display' :
			  $i=0; 
			  $span = explode("_",$name);
			  $sql = "select * from tbl_capacity where due_date = '$first_date' and department = '$department'";
			  //echo $sql;
			  $result = $this->db->query($sql,__FILE__,__LINE__);
			  $row = $this->db->fetch_array($result);
			  //echo $first_date;
			  $start_day = date('N',strtotime($first_date));
			  if($start_day != '1') $date1=date('Y-m-d',strtotime("last monday",strtotime($first_date)));
			  $date2=date('Y-m-d',strtotime("next sunday",strtotime($first_date)));

			  $dates = $date1.'_'.$date2;
			  
			  ?>
			  <script>
			  	document.getElementById('<?php echo 'range_'.$department.$day.$un_id; ?>').style.display = 'block';
			  </script>
			  <span>
				<input type="text" style="width:40px" name="system_val_<?php echo $department.$day.$un_id; ?>" readonly='true' id="system_val_<?php echo $department.$day.$un_id; ?>" value="<?php echo $show_val; ?>" />Of
				<input type="text" style="width:40px" name="manager_val_<?php echo $department.$day.$un_id; ?>" id="manager_val_<?php echo $department.$day.$un_id; ?>" 
						value="<?php echo $row['shedule_capacity']; ?>" 
						onchange="javascript:if(this.value > 0 ){ 
											capacity.saveIntoCapacityTable(
															this.value,
															document.getElementById('system_val_<?php echo $department.$day.$un_id; ?>').value,
															'<?php echo $first_date; ?>',
															'<?php echo $department; ?>',
															'<?php echo $row['capacity_id']; ?>',
															{onUpdate: function(response,root){
																capacity.CapacityMeter('week',
																		'<?php echo $department; ?>',
																		'','','',
																		'<?php echo $show_val; ?>',
																		'<?php echo $dates; ?>',
																		'daily',
																		'','','',
																		'<?php echo $department_id; ?>',
																		'refresh',
																		{preloader:'prl',
																		 onUpdate:function(response,root){	
																		 alert('week_<?php echo $department.strtotime($date1); ?>');
																		   document.getElementById('week_<?php echo $department.strtotime($date1); ?>').style.display='block';
																		   document.getElementById('week_<?php echo $department.strtotime($date1); ?>').innerHTML=response;
																		}});  
																capacity.CapacityMeter('week_day',
																		'<?php echo $department; ?>',
																		'<?php echo $day;?>',
																		'40',
																		'',
																		'<?php echo $show_val; ?>',
																		'<?php echo $first_date; ?>',
																		'',
																		'<?php echo $un_id; ?>',
																		'','',
																		'<?php echo $department_id; ?>',
																		{preloader:'prl',
																		 onUpdate:function(response,root){					  
																		   document.getElementById('<?php echo $department.$day.$un_id; ?>').style.display='block';
																		   document.getElementById('<?php echo $department.$day.$un_id; ?>').innerHTML=response;
																		}});
															}}); }" />																 
			  </span>			  
			  <?php
			  break;
	}
	$html = ob_get_contents();
    ob_end_clean();
	return $html;
	} /////end of function CapacityMeter
	
	function saveIntoCapacityTable($sch_capacity='',$est_capacity='',$due_date='',$department='',$cap_id=''){	
		if($cap_id){
			$update_sql_array["shedule_capacity"] = $sch_capacity;
			$this->db->update("tbl_capacity",$update_sql_array,'capacity_id',$cap_id);
		}
		else{
			$insert_sql_array = array();				
			$insert_sql_array["shedule_capacity"] = $sch_capacity;
			$insert_sql_array["estimated_capacity"] = $est_capacity;
			$insert_sql_array["due_date"] = $due_date;
			$insert_sql_array["department"] = $department;
		
			$this->db->insert("tbl_capacity",$insert_sql_array);		
		}
	}
}////// end of class FinalCapacity
?>