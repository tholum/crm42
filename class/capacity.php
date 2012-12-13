<?php

class CapacityReport{

var $total_est_time = 0;
var $fct_array = '';
var $fct = '';
    function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
	}

    function TreeFct(){
	   ob_start();
	   
	   $sql = "SELECT a.name, a.department_id, a.est_day_dep, a.est_min_task, b.product_id, b.module_id, b.flow_chart_id FROM ".erp_GLOBAL_TASK." a, ".erp_ASSIGN_FCT." b WHERE b.flow_chart_id = a.global_task_id and b.task_status = 'Active'";
	   //echo $sql;
	   $result= $this->db->query($sql,__FILE__,__LINE__); ?>
	   
	   <table id="search_table" class="event_form small_text" width="100%">
			<thead>
			   <tr>
			      <th width="17%">Order ID</th>
				  <th width="12%">Product ID</th>
				  <th width="18%">FCT</th>
				  <th width="14%">Department</th>
				  <th width="15%">Days In Deparment</th>
				  <th width="10%">Time To Do Task</th>
				  <th width="14%">Total Time For A Task</th>
			  </tr>
			</thead>
			<tbody>
			  <?php 
			  	$a = 1;
			     if( $this->db->num_rows($result) > 0 ){
					 while( $row = $this->db->fetch_array($result) ){
					 $_SESSION[est_time] = $row[est_min_task];
					 echo $this->predictPathFct($row[flow_chart_id], $row[product_id], $row[module_id], $a);
					 
					 $sql_group = "SELECT group_name FROM ".erp_USERGROUP." WHERE group_id = $row[department_id]";
					 $result_group = $this->db->query($sql_group,__FILE__,__LINE__);
					 $row_group = $this->db->fetch_array($result_group); ?>
					 <tr>
					    <td><?php echo 'FCT '.$row[flow_chart_id].' order-'.$row[product_id]; ?></td>	  
						<td><?php echo $row[module_id]; ?></td>
						<td><?php echo $row[name]; ?></td>	  
						<td><?php echo $row_group[group_name]; ?></td>
						<td><?php echo $row[est_day_dep]; ?></td>
						<td><?php echo $row[est_min_task]; ?></td>
						<td><?php echo $this->total_est_time; ?></td>
					 </tr>
					 <?php $a++;
					 }
				 }
				 ?>
			 </tbody>
		</table>
	


    <?php
    $html = ob_get_contents();
    ob_end_clean();
	return $html;    
    }   //////end of function TreeFct
	
	function predictPathFct($gtid='', $order='', $product='', $count=''){
	    ob_start();
		 //echo $gtid;
		 $sql_status = "Select * from ".GLOBAL_TASK_STATUS." where global_task_id = '$gtid' and default_path = '1'";
		 
		 $result_status = $this->db->query($sql_status,__FILE__,__LINE__);
		 $row_status = $this->db->fetch_array($result_status);
		 $status_id = $row_status[global_task_status_id];
		 
		 $sql_name = "Select global_task_id from ".erp_GLOBAL_TASK_STATUS_RESULT." where global_task_status_id = '$status_id'";
		 $result_name = $this->db->query($sql_name,__FILE__,__LINE__);
		 $row_name=$this->db->fetch_array($result_name);
		 $sub_task = $row_name[global_task_id];
		 
		 $sql_task = "Select global_task_id, name, module, est_day_dep, est_min_task from ".erp_GLOBAL_TASK." where global_task_id = '$sub_task' and default_path = '1'";
		 $result_task = $this->db->query($sql_task,__FILE__,__LINE__);
		 
		 if($this->db->num_rows($result_task) > 0){
			 $row_task = $this->db->fetch_array($result_task);
			 
			 $sub_task = $row_task[global_task_id];
			 $task_name = $row_task[name];
			 $task_mod = $row_task[module];
			 $task_est = $row_task[est_min_task];

			 $this->total_est_time = $this->total_est_time + $task_est;
			 
			 $fct_array .= $row_task[global_task_id].'_';
			 
			 //echo '<br/>count-'.$count.' taskID-'.$sub_task.' name-'.$task_name.' module-'.$task_mod.' est-'.$task_est.' total-'.$this->total_est_time;
			 echo $this->predictPathFct($row_task['global_task_id']);
		  }//end of if		  
		  		  
			 echo $fct_array;
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
	
	function Capacity(){
	   ob_start();

	   $sql_fct = "SELECT a.order_id, b.due_date, b.flow_chart_id, b.module_id, b.module,b.product_id, c.name, c.est_min_task, c.department_chk_tsk, d.group_name,b.chart_assign_id FROM ".erp_ORDER." a, ".erp_ASSIGN_FCT." b, ".erp_GLOBAL_TASK." c, ".erp_USERGROUP." d WHERE a.order_id = b.product_id and b.task_status = 'Active' and b.flow_chart_id = c.global_task_id and c.department_id = d.group_id ORDER BY b.due_date ";
	   //echo $sql_fct;
	   $result_fct = $this->db->query($sql_fct,__FILE__,__LINE__); 
	   if( $this->db->num_rows($result_fct) > 0 ){
			 while( $row_fct = $this->db->fetch_array($result_fct) ){
				 $this->fct .= $this->predictPathFct($row_fct['flow_chart_id']);
				 $this->fct = $this->fct.''.$row_fct['flow_chart_id'].'_';
			 }
		}

		echo $this->finalCapacity($this->fct); ?>
		
		  <table class="table">
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
                                                    var due = week(document.getElementById('start_date').value);
													alert(due);
													capacity.finalCapacity('<?php echo $this->fct; ?>',
													                       due,
													
																   {preloader:'prl',
																   onUpdate: function(response,root){
																   document.getElementById('capacity_meter').innerHTML=response;
																   document.getElementById('capacity_meter').style.display= 'none';
																}});		
                                                       }
                                          });			
                                       }
                         start_cal();
                         </script>
                   </th>
				    <!--<td>
				       <a href="javascript:void(0);" 
				          onclick="javascript:document.getElementById('start_date').value = '';
						                      capacity.work_orders(
													
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
                                                    capacity.work_orders(
													
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
						                      capacity.work_orders(
													
													document.getElementById('start_date').value,
													document.getElementById('end_date').value,
											       {preloader:'prl',
												   onUpdate: function(response,root){
												   document.getElementById('show_work_order').innerHTML=response;
												   $('#search_table')
												  .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]}) }});
						"><img src="images/trash.gif" border="0" /></a>
				    </td>-->
                </tr>
		  </table>
		  
		  <script>
		      //alert('<? echo date("l"); ?>');
			  //week(document.getElementById('date_format').value);
			  
				//week("2011-10-30");
				
				function week(to){
				    
					var t1=Date.parse(to);
					var minutes=1000*60;
					var hours=minutes*60;
					var days=hours*24;
					var years=days*365;
					var y=t1/years;
					d1=new Date(t1);
					
					
					var today =d1;
					var d=today.getDay();
					//alert(t1 + 'today' + today + ' day ' + d);
					
					//var in_a_week =new Date().setDate(today.getDate()+7);
					//var ten_days_ago=new Date().setDate(today.getDate()-10);
					//var t=new Date(ten_days_ago);
					//alert(today);
					//alert(d);
					
					switch(d){
						case 1 :
						
						var ten_days_ago=today.setDate(today.getDate());
						var t=new Date(ten_days_ago);
						//alert(t);
						
						var be =today.setDate(today.getDate()+6);
						var tt=new Date(be);
						//alert(tt);
						
						var su=t+'/'+tt;
						//alert(su);
						return su;
						
						break;
						case 2 :
						var ten_days_ago=today.setDate(today.getDate()-1);
						var t=new Date(ten_days_ago);
						//alert(t+ ' <->' +today);
						var be =today.setDate(today.getDate()+6);
						var tt=new Date(be);
						//alert(tt);
						var su=t+'/'+tt;
						//alert(su);
						return su;
						break;
						
						case 3 :
						var ten_days_ago=today.setDate(today.getDate()-2);
						var t=new Date(ten_days_ago);
						//alert(t);
						var be =today.setDate(today.getDate()+6);
						var tt=new Date(be);
						//alert(tt);
						var su=t+'/'+tt;
						//alert(su);
						return su;
						break;
						
						case 4 :
						var ten_days_ago=today.setDate(today.getDate()-3);
						var t=new Date(ten_days_ago);
						//alert(t);
						var be =today.setDate(today.getDate()+6);
						var tt=new Date(be);
						//alert(tt);
						var su=t+'/'+tt;
						//alert(su);
						return su;
						break;
						
						case 5 :
						var ten_days_ago=today.setDate(today.getDate()-4);
						var t=new Date(ten_days_ago);
						//alert(t);
						var be =today.setDate(today.getDate()+6);
						var tt=new Date(be);
						//alert(tt);
						var su=t+'/'+tt;
						//alert(su);
						return su;
						break;
						
						case 6 :
						var ten_days_ago=today.setDate(today.getDate()-5);
						var t=new Date(ten_days_ago);
						//alert(t);
						var be =today.setDate(today.getDate()+6);
						var tt=new Date(be);
						var su=t+'/'+tt;
						//alert(t);
						return su;
						break;
						
						case 0 :
						var ten_days_ago=today.setDate(today.getDate()-6);
						var t=new Date(ten_days_ago);
						//alert(t);
						var be =today.setDate(today.getDate()+6);
						var tt=new Date(be);
						var su=t+'/'+tt;
						//alert(t);
						return su;
						break;
					
					}
				}
		 </script>
		  
    <?php
    $html = ob_get_contents();
    ob_end_clean();
	return $html;
    }   //////end of function Capacity
	
	
	function finalCapacity( $fct='', $date_range='', $daily='' ){
	   ob_start();
	   $array_fct = explode("_",$fct);
	   ?>	
	   
		<table id="search_tables" class="event_form small_text" width="80%">
		<thead>
		<tr>
		<th width="14%">FCT ID</th>
		<th width="23%">FCT Name</th>
		<th width="21%">Department</th>
		<th width="24%">Day In Department</th>
		<th width="18%">Time in Min</th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach($array_fct as $fct_id){
		$sql="SELECT name,est_day_dep,est_min_task,department_id FROM ".erp_GLOBAL_TASK." WHERE global_task_id='$fct_id' ORDER BY department_id";
		//echo $sql;
		$result = $this->db->query($sql,__FILE__,__LINE__);
		if( $this->db->num_rows($result) > 0 ){
		while( $row = $this->db->fetch_array($result) ){
		
		$sql_dep = "SELECT group_name FROM ".erp_USERGROUP." WHERE group_id = '$row[department_id]'";
		$result_dep = $this->db->query($sql_dep,__FILE__,__LINE__);
		$row_dep = $this->db->fetch_array($result_dep);
		?>
		<tr>
		<td><?php echo $fct_id; ?></td>
		<td><?php echo $row['name']; ?></td>
		<td><?php echo $row_dep['group_name']; ?></td>
		<td><?php echo $row['est_day_dep']; ?></td>
		<td><?php echo $row['est_min_task']; ?></td>
		</tr>
		<?php
		}
		}
		} ?>
		</tbody>
		</table>
	   
	   
	   	
	   <table id="search_tables" class="event_form small_text" width="80%">
		<thead>
		   <tr>
			  <th>Due Date</th>
			  <th>Department</th>
			  <th>Time in Min</th>
			  <th>Days In Department</th>
		  </tr>
		</thead>
		<tbody>
	   
	   <?php
	    $time_in_min = 0;
		$days_department = 0;
	   
	    $sql_fct = "SELECT c.global_task_id, c.name, c.est_day_dep, c.est_min_task, c.department_chk_tsk, d.group_name FROM  ".erp_USERGROUP." d, ".erp_GLOBAL_TASK." c LEFT JOIN ".erp_ASSIGN_FCT." b ON ((b .flow_chart_id = c.global_task_id and b.task_status = 'Active') or (b.flow_chart_id != c.global_task_id and b.task_status != 'complete') ) and c.global_task_id IN (";
		$sql_fct .= substr(implode(",",array_unique ($array_fct)),0,-1);
		$sql_fct .= " ) where c.department_id = d.group_id and c.default_path = '1'";
		
		
		if($daily != ""){
			$sql_fct .=" , b.due_date ";
		}
		
		$sql_fct .= " ORDER BY b.due_date ";		
		
		  
	    echo '<br/><br/><-1-> '.$sql_fct;
		//print_r($array_fct); 
		
	    $result_fct = $this->db->query($sql_fct,__FILE__,__LINE__);
		$w = 1;

		if( $this->db->num_rows($result_fct) > 0 ){
			while( $row_fct = $this->db->fetch_array($result_fct) ){
			
			   $total_min = $this->calcTimeInMinutes($row_fct);?>
				<tr>
				   <td><?php echo $row_fct[due_date]; ?></td>
				   <td><?php echo $row_fct[group_name]; ?></td>
				   <td><?php echo $total_min; ?></td>
				   <td><?php echo $row_fct[task_status]; ?></td>
				   <td><?php //echo $this->CapacityMeter('today',$row_fct[group_name],'','','','','',$total_min);  ?></td>
				</tr> 
				 <?php
				} 
		    }  ?>
			
			 </tbody>
		</table>
		<?php			
    $html = ob_get_contents();
    ob_end_clean();
	return $html;
    } /////end of function finalCapacity
	
	function calcTimeInMinutes($row_fct,$due_date=''){
		ob_start();	
			
		 $sql_fct_match = "SELECT distinct a.order_id, b.task_status, b.due_date, b.flow_chart_id, b.module, c.name, c.est_day_dep, c.est_min_task, c.department_chk_tsk, d.group_name,b.chart_assign_id FROM ".erp_ORDER." a, ".erp_ASSIGN_FCT." b, ".erp_GLOBAL_TASK." c, ".erp_USERGROUP." d WHERE a.order_id = b.product_id and ((b.flow_chart_id = c.global_task_id and b.task_status = 'Active') or (b.flow_chart_id != c.global_task_id and b.status != 'Complete') ) and c.global_task_id  IN (";
		 $sql_fct_match .= substr(implode(",",array_unique ($array_fct)),0,-1);
		 $sql_fct_match .= ") and c.department_id = d.group_id and d.group_name='$row_fct[group_name]'";
		 echo '     II - '.$sql_fct_match;
		 $result_fct_match = $this->db->query($sql_fct_match,__FILE__,__LINE__); 
		 
		 $c=0;
		 $total_min = '';
		 $total_day = '';
		 $min1 = '';
		 $min2 = '';
		 $day1 = '';
		 $day2 = '';
		 $this->total_est_time = 0;
		 $this->total_est_day = 0;
					 
		 while( $row_fct_match = $this->db->fetch_array($result_fct_match) ){ 						
			if(($row_fct['group_name']==$row_fct_match['group_name']) && (strtotime($row_fct['due_date'])==strtotime($row_fct_match['due_date']))){
				$c++;
				
				if(($c>=2) && ($row_fct['department_chk_tsk'] != 'yes')){
					$min1 = $row_fct_match['est_min_task'];							
					$min2 = $row_fct['est_min_task'];
					$total_min = ($min1 + $min2);
					
					$day1 = $row_fct_match['est_day_dep'];
					$day2 = $row_fct['est_day_dep'];
					$total_day = ($day1 + $day2);
				}

				if(($c<2) && ($row_fct['department_chk_tsk'] != 'yes')){
					
					$total_min = $row_fct['est_min_task'];
					$total_day = $row_fct['est_day_dep'];
				}							
				
				if(($c>=2) && ($row_fct['department_chk_tsk'] == 'yes')){ 
					if($row_fct['module'] == 'work order') { 
					   $min1 = $this->showProductValue($row_fct_match['order_id'],$row_fct_match['chart_assign_id'],$row_fct['est_min_task']);
					   $min2 = $this->showProductValue($row_fct_match['order_id'],$row_fct_match['chart_assign_id'],$row_fct_match['est_min_task']);
					   $total_min = ($min1 + $min2);
					   
					   $day1 = $row_fct['est_day_dep'];
					   $day2 = $row_fct_match['est_day_dep'];
					   $total_day = ($day1 + $day2);								   
					   
					} 
					else {
					   $min1 = $this->showProductValue($row_fct_match['order_id'],'',$row_fct['est_min_task']);
					   $min2 = $this->showProductValue($row_fct_match['order_id'],'',$row_fct_match['est_min_task']);	
					   $total_min = ($min1 + $min2);
														
					   $day1 = $row_fct['est_day_dep'];
					   $day2 = $row_fct_match['est_day_dep'];
					   $total_day = ($day1 + $day2);																																							
					}
				}
				
				if(($c<2) && ($row_fct['department_chk_tsk'] == 'yes')){
					if($row_fct['module'] == 'work order') { 
					   $total_min = $this->showProductValue($row_fct_match['order_id'],$row_fct_match['chart_assign_id'],$row_fct['est_min_task']);	
					   
					   $day1 = $row_fct['est_day_dep'];
					   $day2 = $row_fct_match['est_day_dep'];
					   $total_day = ($day1 + $day2);								
					}
					else {
					   $total_min = $this->showProductValue($row_fct_match['order_id'],'',$row_fct['est_min_task']);
					   
					   $day1 = $row_fct['est_day_dep'];
					   $day2 = $row_fct_match['est_day_dep'];
					   $total_day = ($day1 + $day2);	
					}
				}							
			 }						 
		  } 
		
		echo $total_min;  
		  	
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function showProductValue( $order_id='', $chart_assign_id='', $est_min_time=''){
	  ob_start();
	  $product_name;
	  $count_product;
	  $quantity;	  
	  $i = 0;
	  
	  if( $chart_assign_id != '' ){
		  $sql_id = "select module_id from ".erp_ASSIGN_FCT." where chart_assign_id='$chart_assign_id'";
		  //echo $sql_id;
		  $result_id = $this->db->query($sql_id,__FILE__,__LINE__);
		  $row_id = $this->db->fetch_array($result_id);
		  $product_id = $row_id['module_id'];
	  }
	  
	  $sql_min = "SELECT a.product_id, a.product_name, count(a.product_id) as 'total', SUM(b.quantity) as 'quantity' FROM ".erp_PRODUCT_ORDER." a, ".erp_SIZE." b WHERE a.order_id = '$order_id' and a.order_id = b.order_id ";
	  
	  if( $chart_assign_id != '' ){
	  	  $sql_min .= "and a.product_id = '$product_id'";
		  $variable = 'work order';
	  } 
	  else {
	      $variable = 'order';
	  }
		
	  $sql_min .= " and a.product_id = b.product_id GROUP BY a.product_id";
      //echo '<br/>'.$sql_min;
	  $result_min = $this->db->query($sql_min,__FILE__,__LINE__);
	  if( $this->db->num_rows($result_min) > 0 ){
		  while( $row_min = $this->db->fetch_array($result_min) ){
				 $product_name = $row_min[product_name];
				 $count_product = $row_min[total];
				 $quantity = $row_min[quantity];
				 
				 //echo $product_name.' '.$count_product.' '.$quantity.' '.$product_id;
				 
				 if( $chart_assign_id != '' ){
				     $module = $row_min[product_id];
				 } else { $module = $order_id; }
		  
				 //echo '<br/><br/>product_id ='.$row_min[product_id].', name=>'.$product_name.', total sizes ='.$count_product.', quantity ='.$quantity.'<br/>';
				 $i++;
				 
				 $sql_fct = "SELECT c.group_name FROM ".erp_ASSIGN_FCT." a, ".erp_GLOBAL_TASK." b, ".erp_USERGROUP." c WHERE a.product_id = '$order_id' and a.module_id = '$module' and a.module = '$variable' and a.task_status = 'Active' and a.flow_chart_id = b.global_task_id and b.department_id = c.group_id ";
				 //echo $sql_fct.'<br/>';
				 $result_fct = $this->db->query($sql_fct,__FILE__,__LINE__);
				 $row_fct = $this->db->fetch_array($result_fct);
				 $fct = strtolower("$row_fct[group_name]");

				$sql_prod = "SELECT * FROM ".erp_PRODUCT." WHERE product_id = '$row_min[product_id]'";
				$result_prod = $this->db->query($sql_prod,__FILE__,__LINE__);
				$row_prod = $this->db->fetch_array($result_prod);

				$a = '';
				$b = '';
				$sql_in = "SELECT * FROM ".erp_WORK_ORDER." WHERE order_id = '$order_id' and product_id = '$row_min[product_id]'";
				$result_in = $this->db->query($sql_in,__FILE__,__LINE__);
				$row_in = $this->db->fetch_array($result_in);
				if( $row_in[fabric] != '' ){
					$a .= $row_in[fabric].'_';
				} 
				if( $row_in[zipper] != '' ){
					$a .= $row_in[zipper].'_';
				}
				if( $row_in[pad] != '' ){
					$a .= $row_in[pad].'_';
				}
				if( $row_in[elastic] != '' ){
					$a .= $row_in[elastic].'_';
				}
				if( $row_in[lining] != '' ){
					$a .= $row_in[lining].'_';
				}					
				
				$inven = explode('_',$a);
				$len = count($inven);
			   
				$inve_total = 0;
				for($i=0;$i<($len-1);$i++){
					$sql_size = "SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE product_id='$row_min[product_id]' and inventory_id='$inven[$i]'";
					$result_size = $this->db->query($sql_size,__FILE__,__LINE__);
					$row_size = $this->db->fetch_array($result_size);
					$inve_total += $row_size['inventory_'.$fct];
					//echo 'aaaa'.$est_min_time.' '.$inve_total;
				}
				
				
				$sql_size1 = "SELECT * FROM ".erp_SIZE_DEPENDENT." WHERE order_id = '$order_id' and product_id = '$row_min[product_id]'";
				$result_size1 = $this->db->query($sql_size1,__FILE__,__LINE__);
				while($row_size1 = $this->db->fetch_array($result_size1)){
					
					$row_size1['xs_size_dependant'];
					
					if( $row_size1['xs_size_dependant'] != '' ){
						$c = explode('_',$row_size1['xs_size_dependant']);
						$b .= $c[0].'_';
					} 
					if( $row_size1['s_size_dependant'] != '' ){
						$c = explode('_',$row_size1['s_size_dependant']);
						$b .= $c[0].'_';
					}
					if( $row_size1['m_size_dependant'] != '' ){
						$c = explode('_',$row_size1['m_size_dependant']);
						$b .= $c[0].'_';
					}
					if( $row_size1['l_size_dependant'] != '' ){
						$c = explode('_',$row_size1['l_size_dependant']);
						$b .= $c[0].'_';
					}
					if( $row_size1['xl_size_dependant'] != '' ){
						$c = explode('_',$row_size1['xl_size_dependant']);
						$b .= $c[0].'_';
					}
					if( $row_size1['2x_size_dependant'] != '' ){
						$c = explode('_',$row_size1['2x_size_dependant']);
						$b .= $c[0].'_';
					}
					if( $row_size1['3x_size_dependant'] != '' ){
						$c = explode('_',$row_size1['3x_size_dependant']);
						$b .= $c[0].'_';
					}
					if( $row_size1['4x_size_dependant'] != '' ){
						$c = explode('_',$row_size1['4x_size_dependant']);
						$b .= $c[0].'_';
					}
				}
				$inven1 = explode('_',$b);
				$len1 = count($inven1);
			   
				$inve_total1 = 0;
				for($i=0;$i<($len1-1);$i++){					
					$sql_size = "SELECT * FROM ".erp_ASSIGN_INVENTORY." WHERE product_id='$row_min[product_id]' and inventory_id='$inven1[$i]'";
					$result_size = $this->db->query($sql_size,__FILE__,__LINE__);
					$row_size = $this->db->fetch_array($result_size);
					$inve_total1 += $row_size['inventory_'.$fct];					
				}
				
				$total = $inve_total + $inve_total1 + $row_prod['order_'.$fct] + ($row_prod['per_item_'.$fct] * $quantity) + ($row_prod['per_size_'.$fct] * $count_product);
				//echo $inve_total.'_'.$inve_total1.'_'.$row_prod['order_'.$fct].'_'.($row_prod['per_item_'.$fct] * $quantity).'_'.($row_prod['per_size_'.$fct] * $count_product);
					
				$final_total += $total;
			  }
			  
			  $f_total = $final_total + $est_min_time;
			  echo $f_total;
		}
		$_SESSION[count] = 1;
    $html = ob_get_contents();
    ob_end_clean();
	return $html;
	} /////end of function showProductValue
	
	function CapacityMeter( $runat='', $department='',$day='', $name='', $left='40', $total='', $gross='300' , $show_val = '' ){
	   ob_start();
	   $size = 120;
	   //echo $show_val;
	   switch($runat) {
	   case 'today' : ?>
	      <table class="table">		   
			  <tr><td colspan="4">&nbsp;</td></tr>
			  <tr>
				<th><?php echo $department; ?></th>
				<td>
				  <a href="javascript:void(0);"
				     onclick="javascript:capacity.CapacityMeter('week',
					 											'<?php echo $department; ?>',
				  												{preloader:'prl',target:'<?php echo $department; ?>'});"><img src='<?php echo 'class/gauge.php?value='.$show_val.'&text='.$department.'&size='.$size; ?>' border='0' /></a>
				</td>
				<td>&nbsp;</td>
				<td><span id="<?php echo $department; ?>"></span></td>
			  </tr>	  
		  </table>  
	<?php break;
	    case 'week' : ?>
		    <script>
				document.getElementById('<? echo $department; ?>').style.display = 'block';
			</script>
	        <table class="table" >
				  <tr style="background-color:#3F3F3F;">
				    <td><span id="<?php echo $department.'monday'; ?>"></span></td>
					<td><span id="<?php echo $department.'tuesday'; ?>"></span></td>
					<td><span id="<?php echo $department.'wednesday'; ?>"></span></td>
					<td><span id="<?php echo $department.'thursday'; ?>"></span></td>
					<td><span id="<?php echo $department.'friday'; ?>"></span></td>
					<td><span id="<?php echo $department.'saturday'; ?>"></span></td>
					<td><span id="<?php echo $department.'sunday'; ?>"></span></td>
				  </tr>
				  <tr style="background-color:#003399">
				  	<th style="color:#FFFFFF">Monday</th>
					<th style="color:#FFFFFF">Tuesday</th>
					<th style="color:#FFFFFF">Wednesday</th>
					<th style="color:#FFFFFF">Thursday</th>
					<th style="color:#FFFFFF">Friday</th>
					<th style="color:#FFFFFF">Saturday</th>
					<th style="color:#FFFFFF">Sunday</th>
				  </tr>					  
				  <tr bordercolor="#3F3F3F">
					<td>
					   <a href="javascript:void(0);" 
					     onclick="javascript:capacity.CapacityMeter('display',
						  											'<?php echo $department; ?>',
																	'monday',
																	'left_<?php echo $department.'monday'; ?>',
																	'',
																	'gross_<?php echo $department.'monday'; ?>',
																	'',
																	{preloader:'prl',target:'<?php echo $department.'monday'; ?>'});"><img src='<?php echo 'class/gauge.php?value=41&text=M&size='.$size; ?>' border='0' /></a>
					</td>
					<td>
					  <a href="javascript:void(0);" 
					     onclick="javascript:capacity.CapacityMeter('display',
						  											'<?php echo $department; ?>',
																	'tuesday',
																	'left_<?php echo $department.'tuesday'; ?>',
																	'',
																	'gross_<?php echo $department.'tuesday'; ?>',
																	'',
																	{preloader:'prl',target:'<?php echo $department.'tuesday'; ?>'});"><img src='<?php echo 'class/gauge.php?value=41&text=T&size='.$size; ?>' border='0' /></a></td>
				  
					<td>
					  <a href="javascript:void(0);" 
					     onclick="javascript:capacity.CapacityMeter('display',
						  											'<?php echo $department; ?>',
																	'wednesday',
																	'left_<?php echo $department.'wednesday'; ?>',
																	'',
																	'gross_<?php echo $department.'wednesday'; ?>',
																	'',
																	{preloader:'prl',target:'<?php echo $department.'wednesday'; ?>'});"><img src='<?php echo 'class/gauge.php?value=41&text=W&size='.$size; ?>' border='0' /></a></td>
				  
					<td>
					  <a href="javascript:void(0);" 
					     onclick="javascript:capacity.CapacityMeter('display',
						  											'<?php echo $department; ?>',
																	'thursday',
																	'left_<?php echo $department.'thursday'; ?>',
																	'',
																	'gross_<?php echo $department.'thursday'; ?>',
																	'',
																	{preloader:'prl',target:'<?php echo $department.'thursday'; ?>'});"><img src='<?php echo 'class/gauge.php?value=41&text=T&size='.$size; ?>' border='0' /></a></td>
				  
					<td>
					  <a href="javascript:void(0);" 
					     onclick="javascript:capacity.CapacityMeter('display',
						  											'<?php echo $department; ?>',
																	'friday',
																	'left_<?php echo $department.'friday'; ?>',
																	'',
																	'gross_<?php echo $department.'friday'; ?>',
																	'',
																	{preloader:'prl',target:'<?php echo $department.'friday'; ?>'});"><img src='<?php echo 'class/gauge.php?value=41&text=F&size='.$size; ?>' border='0' /></a></td>
					<td>6 <a href="javascript:void(0);">ADD</a></td>
					<td>7 <a href="javascript:void(0);">ADD</a></td>
				  </tr>
             </table>	
	<?php break;
	      case 'display' : 
		  echo $day;
		  $span = explode("_",$name);
		  ?>
		  <script>
		    document.getElementById('<?php echo $department.'monday'; ?>').style.display = 'none';
			document.getElementById('<?php echo $department.'tuesday'; ?>').style.display = 'none';
			document.getElementById('<?php echo $department.'wednesday'; ?>').style.display = 'none';
			document.getElementById('<?php echo $department.'thursday'; ?>').style.display = 'none';
			document.getElementById('<?php echo $department.'friday'; ?>').style.display = 'none';
			document.getElementById('<?php echo $department.'saturday'; ?>').style.display = 'none';
			document.getElementById('<?php echo $department.'sunday'; ?>').style.display = 'none';
			document.getElementById('<?php echo $span[1]; ?>').style.display = 'block';
		  </script>
		  
		  <table class="table">
		    <tr>
			  <td>
			    <input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo $left; ?>" />
			  </td>
			  <td style="color:#CCCCCC;">Of</td>
			  <td>
			    <input type="text" name="<?php echo $total; ?>" id="<?php echo $total; ?>" value="<?php echo $gross; ?>" />
			  </td>
		    </tr>
		  </table>
		  
		  <?php
		  break;
	}
	$html = ob_get_contents();
    ob_end_clean();
	return $html;
	} /////end of function CapacityMeter

}////// end of class FinalCapacity
?>