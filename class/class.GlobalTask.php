<?php
/* Includes for all the modules that are needed*/
require_once('class/class.ups.php');
require_once('class/class.payflowpro.php');
require_once('class/class.securenote.php');
require_once('class/class.CalcDate.php');
require_once('class/class.CapacityCalc.php');
require_once('class/class.capacity.php');
require_once('class/class.casecreation.php');

//require_once('class/class.WorkOrder.php'); 
class GlobalTask{

  var $global_task_id;
  var $db;
  var $validity;
  var $Form;
  var $predicted_due_date;
  var $task_est_day = 0;
  var $newvalue = 0;
  var $percentage = 0;
  var $est_date = 0;
  var $capacity_calc;
  var $page = '';
  function __construct(){
	  $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	  $this->validity = new ClsJSFormValidation();
	  $this->Form = new ValidateForm();
          $this->capacity_calc = new CapacityCalc;
          
  }
  function use_page(){
      if( $this->page == ''){
          $this->page = new basic_page();
      }
  }
  /*
   * Call is basicly a catch all feature for function names :)
   */
  function __call( $name , $args){
      $return = array();
       $options = $args[7];
       $options_from_function = $args[8];
      if(file_exists(getcwd() . "/modules/systemtask." . $name . ".php" )){
        include( "modules/systemtask." . $name . ".php");
      } else {
          $return["error"] = "Function $name does not exsist, Nor does file: " . getcwd() . "/modules/systemtask." . $name . ".php";
      }
      if( $return["stop"] != '' ){
        $this->set_systemtask_stop( $args[1] , $name);
      }
      return $return;
      logme( $args[2] , $args[3], 'systemtask_' . $name , "Arguments: \n" . print_r( $args , true ) . "\nReturn:\n" . print_r($return , true ) );
  }
  function add_holiday_ui(){
      ob_start();
      ?>
Date: <input id="add_holiday_ui_input" class="add_holiday_ui_input" > Name: <input id="add_holiday_ui_input_name" >
      <?php
      $html=  ob_get_contents();
      ob_end_clean();
      return $html;
  }
  function get_holidays( $overide=array()){
      $res = $this->db->query("SELECT * FROM tbl_global_task_holidays WHERE date > NOW() ORDER BY date ASC");
      $return = array();
      while( $row = $this->db->fetch_assoc($res)){
          $return[] = $row;
      }
      return $return;
  }
  function set_holiday( $date , $name ){
      $i = array();
      $i['date'] = $date;
      $i['name'] = $name;
      $this->db->insert('tbl_global_task_holidays', $i);
      
  }
  function delete_holiday( $date_id){
      $this->db->query("DELETE FROM tbl_global_task_holidays WHERE date_id = '$date_id'");
  }
  function set_due_date( $chart_assign_id , $date ){
      $this->use_page();
      
      $flow_chart = $this->db->fetch_assoc($this->db->query("SELECT module , module_id , due_date FROM assign_flow_chart_task WHERE chart_assign_id = '$chart_assign_id'"));
      
      $update = array();
      $update['due_date'] = date('Y-m-d 00:00:00' , strtotime($date) );
      $this->db->update('assign_flow_chart_task' , $update , 'chart_assign_id' , $chart_assign_id );
      $this->page->log_activity( $flow_chart["module"] , $flow_chart["module_id"] , 'due_date_change' , $flow_chart['due_date'] , $update['due_date'] , 'flowchart_task' ,  $chart_assign_id );
      $this->page->log_activity( 'flowchart_task' ,  $chart_assign_id , 'due_date_change' , $flow_chart['due_date'] , $update['due_date'] , $flow_chart["module"] , $flow_chart["module_id"] );
  }
  function logme( $module_name , $module_id , $sub_proccess , $message ){
    //This allows me to turn on or off logging depending
    $log_arr = array();
    $log_arr['module_name'] = $module_name;
    $log_arr['module_id'] = $module_id;
    $log_arr["sub_proccess"] = $sub_proccess;
    $log_arr["message"] = $message;
    $this->db->insert('log' , $log_arr);
      
  }
  
  function is_systemtask_stoped($chart_assign_id){
      $sql = "SELECT systemtask_stopped FROM assign_flow_chart_task WHERE chart_assign_id = '$chart_assign_id'";
      $array = $this->db->fetch_assoc($this->db->query($sql));
      if( $array["systemtask_stopped"] == '' ){
          return false;
      } else {
          return true;
      }
  }
  function json_search_fctname( $search ){
            $arr = explode(" ", $search);
            $sql = "SELECT a.name , a.global_task_id FROM `tbl_global_task` a LEFT JOIN tbl_global_task_tree b ON a.global_task_tree_id = b.global_task_tree_id";
            $where = array();
            foreach( $arr as $value ){
                if( $value != ''){
                    $where[] = "( a.name LIKE '%$value%' OR b.global_task_tree_name LIKE '%$value%' )";
                }
            }
            if( count($where) != 0 ){
                $sql .= " WHERE " . implode(" AND ", $where);
            }
            $result = $this->db->query($sql);
            $json = array();
            while( $row = $this->db->fetch_assoc($result)){
                $row["value"] = $row["name"];
                $row["label"] = $row["value"];
                
                //$row["sql"] = $sql;
                $json[] = $row;
            }
            //$json[] = array('sql' => $sql);
            return json_encode($json);
        }
        
  function set_systemtask_stop( $chart_assign_id , $systemtask = 'default'){
      $sql = "UPDATE assign_flow_chart_task SET systemtask_stopped = CONCAT(`systemtask_stopped` , '$systemtask' ) WHERE chart_assign_id = '$chart_assign_id'";
  
      $this->db->query($sql);
  }
  function livex_call( $name , $object = array() ){
      
      $args = array();
      foreach( $object as $n => $v){
          $args[$n] = $v;
      }
            $return = '';
      if(file_exists(getcwd() . "/modules/phplivex." . $name . ".php" )){
        include( "modules/phplivex." . $name . ".php");
      } else {
          $return["error"] = "Function $name does not exsist, Nor does file: " . getcwd() . "/modules/systemtask." . $name . ".php";
      }
      
      return $return;
  }

  
  /************************************************* ADMIN VIEW **********************************************************************************************/ 
  function getTreeList(){
      $result = $this->db->query("SELECT * FROM `tbl_global_task_tree`");
      $return = array();
      while($row=$this->db->fetch_assoc($result)){
          $return[] = $row;
      }
      
      return $return;
  }
  /*
   * I did this in a moduler techniqe that allows us to write one file if there becomes more module's that we need
   * If full is set 1 one it pulls all the contacts, otherwise it just pulls the best guess single contact
   * 
   */
  function get_contact_by_module( $module_id , $module_name , $full=0 ){
      $module_name_substr = str_replace(" ", "_", $module_name);
      include( "modules/contact_info.$module_name_substr.php");
      return $return; // Should be a contact id
  }
  
  function getAvalibleTask( $tree , $module_name ){
      $result = $this->db->query("SELECT * FROM `tbl_global_task` WHERE global_task_tree_id = '$tree' AND module = '$module_name'");
      $return = array();
      while($row=$this->db->fetch_assoc($result)){
          $return[] = $row;
      }

      return $return;

  }
  
  function array2options( $array , $valKey , $displayKey , $selectVal='' ){
      $return = '';
      foreach( $array as $option ){
          if( $option[$valKey] == $selectVal ){
              $select= "SELECTED";
          } else { $select = ''; }
          $return .= '<option value="' . $option[$valKey] . '" ' . $select . ' >' . $option[$displayKey] . '</option>';
      }
      return $return;
  }
  
  function get_default_owner_by_module( $module_name , $module_id , $global_task_id ) {
      $result = $this->db->query("SELECT * FROM tbl_global_task WHERE global_task_id = '$global_task_id'");
      $return = array();
      while($row=$this->db->fetch_assoc($result)){
          $return = array('module_name' => 'TBL_GROUP' , 'module_id' => $row['department_id']);
      }

      return $return;
  }
  
  function get_posible_owners_by_module( $module_name , $module_id , $global_task_id ) {
	  $return = array();

      $result_group = $this->db->query("SELECT a.department_id , b.group_name FROM `tbl_global_task` a LEFT JOIN tbl_usergroup b ON a.department_id = b.group_id WHERE global_task_id = '$global_task_id'");
      while($row_group=$this->db->fetch_assoc($result_group)){
          $return[] = array( "module_name" => "TBL_GROUP" , "module_id" => $row_group['department_id'] , "display_name" => $row_group['group_name'] );
      }

      $result_user = $this->db->query("SELECT c.user_id , c.first_name , c.last_name FROM `tbl_global_task` a LEFT JOIN group_access b ON a.department_id = b.group_id LEFT JOIN tbl_user c ON b.user_id = c.user_id WHERE global_task_id = '$global_task_id'");
      while($row_user=$this->db->fetch_assoc($result_user)){
          if($row_user['user_id'] != '' ){
            $return[] = array( "module_name" => "TBL_USER" , "module_id" => $row_user['user_id'] , "display_name" => $row_user['first_name'] . " " . $row_user['last_name'] ); 
          }
      }
	  
      return $return;
  } 
  function get_defaults($module_name, $module_id , $options = array() ){
      $defaults = array();
      if(file_exists("./modules/fct/defaults.$module_name.php")){
          include("modules/fct/defaults.$module_name.php");
      }
      return $defaults;
  }
  function calculate_due_date( $module_name="" , $module_id="" , $est_day_dep="" ){
      if(file_exists("./modules/fct/due_date.$module_name.php")){
            include("modules/fct/due_date.$module_name.php");
        } elseif(file_exists("./modules/fct/due_date.default.php")) {
            include("modules/fct/due_date.default.php");
        } else {
            if( $est_day_dep == ''){
                $due_date = strtotime("now");
            } else {
                $due_date = strtotime("+" . $est_day_dep . " day");
            }
        }
      return $due_date;
  }
  function InsertFlowChartTask($module_name,$module_id,$tree='',$global_task_id=''){
		// ob_start();
		 
		 $sql_task = "Select name, est_day_dep, department_id,module from ".erp_GLOBAL_TASK." where global_task_id = '$global_task_id'";
		 //echo $sql_task;
		 $result_task = $this->db->query($sql_task,__FILE__,__LINE__);
		 $row_task = $this->db->fetch_array($result_task);
		 
// 		  $sql = "SELECT ship_date FROM erp_order WHERE order_id = '$order_id'";
//		  $result = $this->db->query($sql,__FILE__,__LINE__);
//		  $row = $this->db->fetch_array($result);
	 
 
		 $total_est_day = $this->totalEstDay($global_task_id);
		 $this->task_est_day = ($total_est_day + $row_task['est_day_dep']) * 86400;		 
		 //$task_estimated_date = $this->estDueDate($this->task_est_day,time(),$row['ship_date']);		 
		 //$percentage = $task_estimated_date/($total_est_day + $row_task['est_day_dep']);		 
		 //$per_day = $row_task['est_day_dep'] * $percentage;
		 $est_seconds = ($row_task['est_day_dep'] * 24 * 60 * 60);
		 //$est_date = $this->estDueDate($est_seconds,time());
		 
		 $predicted = $this->estDueDate(($task_estimated_date*86400),time());
		 
		 $owner_module_info = $this->get_default_owner_by_module( $module_name , $module_id , $global_task_id );
		 //print_r($owner_module_info);
		 $owner_module_name = $owner_module_info['module_name'];
		 $owner_module_id = $owner_module_info['module_id'];
        if( $row_task['est_day_dep'] == ''){
            $est_date = strtotime("now");
        } else {
            $est_date = strtotime("+" . $row_task['est_day_dep'] . " day");
        }
        
//        $ia["due_date"] = date("Y-m-d H:i:s" , $est_time);//$predicted;
//        file_put_contents('tmp.log', $sql_task . "\n" . print_r($row_task , true ));
        $fct = array();
        $fct["tree_id"] = $tree;
        $fct["module"] = $module_name;
        $fct["flow_chart_id"] = $global_task_id;
        $fct["module_id"] = $module_id;
        $fct["created_date"] = date('Y-m-d h:i:s A');
        $fct["task_status"] = "Active";
		$fct["due_date"] = $est_date;
		$fct["projected_path_due_date"] = $predicted;	
		$fct["owner_module_name"] = $owner_module_name;
		$fct["owner_module_id"] = $owner_module_id;	
                $fct["due_date"] = $this->calculate_due_date( $module_name , $module_id , $row_task['est_day_dep'] );//date("Y-m-d H:i:s" , $est_date );
        $tmp = $this->get_defaults($module_name, $module_id , $fct );
        foreach( $tmp as $n => $v){
            $fct[$n] = $v;
        }
        $this->db->insert("assign_flow_chart_task" , $fct);	
		$last_assign_id = $this->db->last_insert_id();
		$_SESSION['last_assign_id'] = $last_assign_id;
        $this->use_page();
		$this->page->log_activity( $module_name , $module_id , 'flowchart_task_created' , '' , $global_task_id , 'flowchart_task' ,  $last_assign_id );
		$fct = array();
		$fct["module"] = $module_name;
		$fct["module_id"] = $module_id;       
		$fct["flow_chart_id"] = $global_task_id;
                //Fix this as well
		$fct["due_date"] = $this->calculate_due_date( $module_name , $module_id , $row_task['est_day_dep'] );;
		$fct["chart_assign_id"]=$_SESSION['last_assign_id'];
		$this->db->insert("predicted_flow_chart_task" , $fct);		
		
		
/*		$last_insert_id = $this->db->last_insert_id();		
		$sql_1= "select * from predicted_flow_chart_task where module='$module_name' and module_id='$module_id' and order_id='$order_id' and  flow_chart_id='$global_task_id'";
		$result_1 = $this->db->query($sql_1,__FILE__,__LINE__);		
		if($this->db->num_rows($result_1) <= 1){
			$sql = "update predicted_flow_chart_task set calculated_time='".$this->capacity_calc->calculate_capacity($module_name, $module_id, $global_task_id)."' where predicted_assign_id='$last_insert_id'";		
		}
		else {
			$row = $this->db->fetch_array($result_1);	
			$sql = "update predicted_flow_chart_task set calculated_time='".$row['calculated_time']."' where predicted_assign_id='$last_insert_id'";
		}
		$this->db->query($sql,__FILE__,__LINE__);
*/
		return $_SESSION['last_assign_id'];	
		
	/*	$html=ob_get_contents();
		ob_end_clean();
		return $html;	*/
  }

	function predictPathStatus($tree_id='', $module_name='',$module_id='',$est_date='',$gtid=''){
	    ob_start();		 
		 
		 $sql_task = "Select global_task_id,name,module,est_day_dep from ".erp_GLOBAL_TASK." where global_task_id = '$gtid' and default_path = '1'";
		 $result_task = $this->db->query($sql_task,__FILE__,__LINE__);
		 $row_task = $this->db->fetch_array($result_task);
		 $task_name = $row_task[name];
		 $task_mod = $row_task[module];
		 $task_est = $row_task[est_day_dep];
		 
 		  $sql = "SELECT ship_date FROM erp_order WHERE order_id = '$order_id'";
		  $result = $this->db->query($sql,__FILE__,__LINE__);
		  $row = $this->db->fetch_array($result);
		 
		 
		 $total_est_day = $this->totalEstDay($gtid);
		 $this->task_est_day = ($total_est_day + $row_task['est_day_dep']) * 86400;		 
		 //$task_estimated_date = $this->estDueDate($this->task_est_day,time(),$row['ship_date']);		 
		 //$percentage = $task_estimated_date/($total_est_day + $row_task['est_day_dep']);		 
		 //$per_day = $task_est * $percentage;
		 $est_seconds = ($task_est * 24 * 60 * 60);
		 
		 if(!$est_date) $est_date = $this->estDueDate($est_seconds,time());

		 echo $this->predictPathFct($gtid,strtotime($est_date),$module_name,$module_id);
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	
	} /////end of function predictPathStatus

	 function predictPathFct($gtid='',$est_date='',$module_name='',$module_id='',$chart_assign_id=''){
	    ob_start();
		
		 if($chart_assign_id) $_SESSION['last_assign_id'] = $chart_assign_id; 
		 
		 $new_est_date = 0;
		 $sql_status = "Select * from tbl_global_task_status where global_task_id = '$gtid' and default_path = '1'";
		 $result_status = $this->db->query($sql_status,__FILE__,__LINE__);
		 $row_status = $this->db->fetch_array($result_status);
		 $status_id = $row_status[global_task_status_id]; ?>
		
		 <?php
		 $sql_name = "Select global_task_id from tbl_global_task_status_result where global_task_status_id = '$status_id'";
		 $result_name = $this->db->query($sql_name,__FILE__,__LINE__);
		 while($row_name=$this->db->fetch_array($result_name)){
			 $sub_task = $row_name[global_task_id];
			 
			 $sql_task = "Select global_task_id,name,module,est_day_dep, department_id,module from ".erp_GLOBAL_TASK." where global_task_id = '$sub_task' and default_path = '1'";
			 $result_task = $this->db->query($sql_task,__FILE__,__LINE__);
			 if($this->db->num_rows($result_task) > 0){
				 $row_task = $this->db->fetch_array($result_task);
				 $sub_task = $row_task[global_task_id];
				 $task_name = $row_task[name];
				 $task_mod = $row_task[module];
				 
				 $task_est = $row_task[est_day_dep];
				 $est_seconds = ($task_est * 24 * 60 * 60);
				 //
				 if(!$est_date and !$chart_assign_id){ $est_date = time();}
				 
				 $new_est_date = $this->estDueDate($est_seconds,$est_date,'');
				 				 
				$fct = array();
				$fct["module"] = $module_name;
				$fct["module_id"] = $module_id;       
				$fct["flow_chart_id"] = $sub_task;
				$fct["due_date"] = $this->calculate_due_date( $module_name , $module_id , $row_task['est_day_dep'] );
				$fct["chart_assign_id"]=$_SESSION['last_assign_id'];
				$this->db->insert("predicted_flow_chart_task" , $fct);				 
				 
				 
				$last_insert_id = $this->db->last_insert_id();		
				$sql_1= "select * from predicted_flow_chart_task where module='$module_name' and module_id='$module_id' and order_id='$order_id' and  flow_chart_id='$sub_task'";
				$result_1 = $this->db->query($sql_1,__FILE__,__LINE__);				
				if($this->db->num_rows($result_1) <= 1){
					$sql = "update predicted_flow_chart_task set calculated_time='".$this->capacity_calc->calculate_capacity($module_name, $module_id, $sub_task)."' where predicted_assign_id='$last_insert_id'";		
				}
				else{
					$row = $this->db->fetch_array($result_1);	
					$sql = "update predicted_flow_chart_task set calculated_time='".$row['calculated_time']."' where predicted_assign_id='$last_insert_id'";
				}
				$this->db->query($sql,__FILE__,__LINE__);				 
				 
				echo $this->predictPathFct($sub_task,strtotime($new_est_date),$module_name,$module_id);
		  }//end of if
		 }//end of while 
		 $this->predicted_due_date = $new_est_date;
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	} /////end of function predict_path

	function updateFlowChartTask($order_id,$ship_date='',$chart_assign_id = ''){
		ob_start();
  		  $capacity_cal = 0;
		  
		  if(!$ship_date){
			  $sql_ship = "SELECT * FROM erp_order WHERE order_id = '$order_id'";
			  $result_ship = $this->db->query($sql_ship,__FILE__,__LINE__);
			  $row_ship = $this->db->fetch_array($result_ship);
			  $ship_date = $row_ship["ship_date"];
		  }		 
		 
		  $sql = "SELECT * FROM assign_flow_chart_task WHERE product_id = '$order_id' and task_status = 'Active'";
		  $result = $this->db->query($sql,__FILE__,__LINE__);
		  while($row = $this->db->fetch_array($result)){
				 $sql_task = "Select * from ".erp_GLOBAL_TASK." where global_task_id = '$row[flow_chart_id]'";
				 //echo $sql_task;
				 $result_task = $this->db->query($sql_task,__FILE__,__LINE__);
				 $row_task = $this->db->fetch_array($result_task);
			
				 $total_est_day = $this->totalEstDay($row['flow_chart_id']);
				 $this->task_est_day = ($total_est_day + $row_task['est_day_dep']) * 86400;		 
				 $task_estimated_date = $this->estDueDate($this->task_est_day, strtotime($row['created_date']) ,$ship_date);		 
				 $percentage = $task_estimated_date/($total_est_day + $row_task['est_day_dep']);		 
				 $per_day = $task_est * $percentage;
				 $est_seconds = ($per_day * 24 * 60 * 60);
				 $est_date = $this->estDueDate($est_seconds, strtotime($row['created_date']) );
				 
				 $predicted = $this->estDueDate(($task_estimated_date*86400), strtotime($row['created_date']) );
				
				 $fct = array();
				 $fct["due_date"] = $est_date;
				 $fct["projected_path_due_date"] = $predicted;		
				 $this->db->update("assign_flow_chart_task" , $fct, "chart_assign_id", $row['chart_assign_id']);	
				
				 if($this->isFCTDefaultPath($row['flow_chart_id']) == 1){
					
					if($this->getPredictedData($chart_assign_id)){
						$sql = "update predicted_flow_chart_task set due_date='".$est_date."' , chart_assign_id = '".$row['chart_assign_id']."' where chart_assign_id = '$chart_assign_id' and flow_chart_id ='".$row['flow_chart_id']."'";
						$this->db->query($sql,__FILE__,__LINE__);				 
					}
					else {						
						 $cap_cal = new CapacityCalc;

						 $fct = array();
						 $fct["module"] = $row['module'];
						 $fct["module_id"] = $row['module_id'];
						 $fct["order_id"] = $order_id;        
						 $fct["flow_chart_id"] = $row['flow_chart_id'];
						 $fct["due_date"] = $this->calculate_due_date( $row['module'] , $row['module_id'] , $row_task['est_day_dep'] );
						 $fct["chart_assign_id"]=$row['chart_assign_id'];
						 $fct["debug"] = $row_task['department_id'];
						 $this->db->insert("predicted_flow_chart_task" , $fct);	
						 
						 $last_insert_id = $this->db->last_insert_id();	
						 $sql = "update predicted_flow_chart_task set calculated_time='".$this->capacity_calc->calculate_capacity($row['module'], $row['module_id'], $row['flow_chart_id'])."' where predicted_assign_id='$last_insert_id'";	
						 $this->db->query($sql,__FILE__,__LINE__);			
					}					
                 }
				 else{
				 	$this->removeFctFromPredictedTable($chart_assign_id);
				 }				 
				 $this->task_est_day = 0;
				 echo $this->updatePredictPathStatus($row["tree_id"],$row[module],$row[module_id],$order_id,$est_date,$row['chart_assign_id'],$row['flow_chart_id'],$chart_assign_id);	
		  }	 
		  
		 $this->removeCompletedFctFromPredictedTable($chart_assign_id);
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function isFCTDefaultPath($gtid=''){
		 $sql = "Select default_path from tbl_global_task where global_task_id = '$gtid'";
		 $result = $this->db->query($sql,__FILE__,__LINE__);
		 $row = $this->db->fetch_array($result);
		 return $row['default_path'];
	}

	function getPredictedData($chart_assign_id=''){
		  $sql = "SELECT * FROM predicted_flow_chart_task WHERE chart_assign_id = '$chart_assign_id'";
		  $result = $this->db->query($sql,__FILE__,__LINE__);
		  if($this->db->num_rows($result)){
		  		return true;
		  }
		  else return false;
	}

	function removeFctFromPredictedTable($chart_assign_id=''){
		$sql_remove = $this->db->query("DELETE FROM `predicted_flow_chart_task` WHERE `chart_assign_id` = '$chart_assign_id'");
	}

	function removeCompletedFctFromPredictedTable($chart_assign_id=''){
		  $sql = "SELECT * FROM assign_flow_chart_task WHERE chart_assign_id = '$chart_assign_id'";
		  $result = $this->db->query($sql,__FILE__,__LINE__);
		  $row = $this->db->fetch_array($result);
		  
		  $sql_remove = $this->db->query("DELETE FROM `predicted_flow_chart_task` WHERE `chart_assign_id` = '$chart_assign_id' and flow_chart_id = '$row[flow_chart_id]'");
	}

	function updatePredictPathStatus($tree_id='', $module_name='',$module_id='', $order_id='',$est_date='',$new_chart_assign_id='',$gtid='',$old_chart_assign_id=''){
	    ob_start();		 
		 
		 $sql_task = "Select global_task_id,name,module,est_day_dep from ".erp_GLOBAL_TASK." where global_task_id = '$gtid'";
		 //echo $sql_task;
		 $result_task = $this->db->query($sql_task,__FILE__,__LINE__);
		 $row_task = $this->db->fetch_array($result_task);
		 $task_name = $row_task[name];
		 $task_mod = $row_task[module];
		 $task_est = $row_task[est_day_dep];
		 
 		  $sql = "SELECT ship_date FROM erp_order WHERE order_id = '$order_id'";
		  $result = $this->db->query($sql,__FILE__,__LINE__);
		  $row = $this->db->fetch_array($result);
		 
		 $total_est_day = $this->totalEstDay($gtid);
		 $this->task_est_day = ($total_est_day + $row_task['est_day_dep']) * 86400;		 
		 $task_estimated_date = $this->estDueDate($this->task_est_day, strtotime($est_date) ,$row['ship_date']);		 
		 $percentage = $task_estimated_date/($total_est_day + $row_task['est_day_dep']);		 
		 
		 //echo $gtid.'--'.$task_estimated_date.'---'.$total_est_day.'---'.$row_task['est_day_dep'];
		 
		 $per_day = $task_est * $percentage;
		 $est_seconds = ($per_day * 24 * 60 * 60);
		 
		 $est_date = $this->estDueDate($est_seconds,strtotime($est_date));
		 
		 echo $this->updatePredictPathFct($gtid, $percentage,strtotime($est_date),$order_id,$module_name,$module_id,$new_chart_assign_id,$old_chart_assign_id);
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	
	} /////end of function predictPathStatus

	 function updatePredictPathFct($gtid='', $percent='',$est_date='',$order_id='',$module_name='',$module_id='',$new_chart_assign_id='',$old_chart_assign_id=''){
	    ob_start();
		
		 if($chart_assign_id) $_SESSION['last_assign_id'] = $new_chart_assign_id; 
		 
		 $new_est_date = 0;
		 $sql_status = "Select * from tbl_global_task_status where global_task_id = '$gtid' and default_path = '1'";
		 $result_status = $this->db->query($sql_status,__FILE__,__LINE__);
		 $row_status = $this->db->fetch_array($result_status);
		 $status_id = $row_status[global_task_status_id]; ?>
		
		 <?php
		 $sql_name = "Select global_task_id from tbl_global_task_status_result where global_task_status_id = '$status_id'";
		 $result_name = $this->db->query($sql_name,__FILE__,__LINE__);
		 while($row_name=$this->db->fetch_array($result_name)){
			 $sub_task = $row_name[global_task_id];
			 
			 $sql_task = "Select global_task_id,name,module,est_day_dep, department_id,module from ".erp_GLOBAL_TASK." where global_task_id = '$sub_task' and default_path = '1'";
			 $result_task = $this->db->query($sql_task,__FILE__,__LINE__);
			 if($this->db->num_rows($result_task) > 0){
				 $row_task = $this->db->fetch_array($result_task);
				 $sub_task = $row_task[global_task_id];
				 $task_name = $row_task[name];
				 $task_mod = $row_task[module];
				 
				 $task_est = $row_task[est_day_dep] * $percent;
				 $est_seconds = ($task_est * 24 * 60 * 60);
				 //
				 if(!$est_date and !$chart_assign_id){ $est_date = time();}
				 
				 $new_est_date = $this->estDueDate($est_seconds,$est_date,'');
			
				if($this->getPredictedData($old_chart_assign_id)){
					$sql = "update predicted_flow_chart_task set due_date='".$new_est_date."' , chart_assign_id = '".$_SESSION['last_assign_id']."' where chart_assign_id = '".$old_chart_assign_id."' and flow_chart_id ='".$sub_task."'";
					$this->db->query($sql,__FILE__,__LINE__);				 
				}
				else {					 
					 $fct = array();
					 $fct["module"] = $module_name;
					 $fct["module_id"] = $module_id;
					 $fct["order_id"] = $order_id;        
					 $fct["flow_chart_id"] = $sub_task;
					 $fct["due_date"] = $this->calculate_due_date( $module_name ,$module_id , $row_task['est_day_dep'] );
					 $fct["chart_assign_id"]=$_SESSION['last_assign_id'];
					 $fct["debug"] = $row_task['department_id'];
					 $this->db->insert("predicted_flow_chart_task" , $fct);	
					 
					 $last_insert_id = $this->db->last_insert_id();	
					 $sql = "update predicted_flow_chart_task set calculated_time='".$this->capacity_calc->calculate_capacity($module_name, $module_id, $sub_task)."' where predicted_assign_id='$last_insert_id'";	
					 $this->db->query($sql,__FILE__,__LINE__);						 				
				}					
				
				echo $this->updatePredictPathFct($sub_task,$percent,strtotime($new_est_date),$order_id,$module_name,$module_id,$new_chart_assign_id,$old_chart_assign_id);
		  }//end of if
		 }//end of while 
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	} /////end of function predict_path
		
	function totalEstDay($gtid=''){
		 $sql_status = "Select * from tbl_global_task_status where global_task_id = '$gtid' and default_path = '1'";
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
				 $task_name = $row_task[name];
				 $task_mod = $row_task[module];
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
		  
		  if($ship_date != ''){
		   	$est_day = $date->getWorkingDays(date('Y-m-d'),$ship_date);
			return floor($est_day);
		  }
		  
		  else {
			$est_date = date("Y-m-d h:i:s A", $date->addBusinessDays($est_timestamp,$w_days));
			return $est_date;	  
		  }		  
	 } /////end of function estDueDate
	
	function percentDay( $total_est_day='', $order_id='' ){
		
		  $sql = "SELECT ship_date FROM erp_order WHERE order_id = '$order_id'";
		  $result = $this->db->query($sql,__FILE__,__LINE__);
		  $row = $this->db->fetch_array($result);
		  
		  $date2 = strtotime($row[ship_date]);
		  
		  $date3 = time();
		  $str = ( $date2 - $date3 );
		  
		  
		  $min = $str/60;
		  $hour = $min/60;
		  $day = $hour/24;
		  
		 
		  $percentage = round($day)/$total_est_day;
		  
		  $this->percentage = $percentage;
		  //echo 'ma'.$this->percentage;
		  return $this->percentage;
	} /////end of function percentDay
  function get_default_tree( $module_name ){
      $ar = $this->db->fetch_assoc($this->db->query("
              SELECT b.global_task_tree_id FROM tbl_global_task_default a
              LEFT JOIN tbl_global_task b ON a.global_task_id = b.global_task_id 
              WHERE a.module_name = '$module_name'"));
      return $ar['global_task_tree_id'];
  }
  function AddFlowChartTask($module_name,$module_id,$tree='',$global_task_id='',$whenDonejs='',$div_id='' , $disable_tree=false){
      
      $module_name_true = $module_name;
      $module_name = str_replace( ' ' , '' , $module_name );

      ob_start();
	  
      if( $tree != '' && $global_task_id != '' ){
	    echo $this->InsertFlowChartTask($module_name, $module_id, $tree, $global_task_id); 
		?>
		<select class="bucket_tree_list bucket_tree_list_<?php echo $module_name; ?> bucket_list" onChange="javascript:global_task.AddFlowChartTask('<?php echo $module_name_true; ?>', 
																  '<?php echo $module_id; ?>',
																  this.value,
																  '',
																  '<?php  echo str_replace( "'" , "\\'" , $whenDonejs); ?>', 
																  '<?php echo $div_id; ?>',
																  { target:'<?php echo $div_id; ?>'} );" >
			<option value="" >-- SELECT --</option>
			<?php echo $this->array2options($this->getTreeList(), 'global_task_tree_id', 'global_task_tree_name' ); ?>
		</select>
		
		<?php } else { ?>
		<select <?php if($disable_tree == true){ ?> style="display: none;" <?php } ?> class="bucket_options_list bucket_options_list_<?php echo $module_name; ?> bucket_list" onChange="javascript:global_task.AddFlowChartTask('<?php echo $module_name_true; ?>', 
																  '<?php echo $module_id; ?>',
																  this.value,
																  '',
																  '<?php  echo str_replace( "'" , "\\'" , $whenDonejs); ?>',
																  '<?php echo $div_id; ?>',
																  { target:'<?php echo $div_id;?>'} ); " >
			<option value="" >-- SELECT --</option>
			<?php echo $this->array2options($this->getTreeList(), 'global_task_tree_id', 'global_task_tree_name' , $tree); ?>
		</select>

		<?php if( $tree != '' ){ ?>
			<select onChange="javascript:global_task.AddFlowChartTask('<?php echo $module_name_true; ?>', 
																	  '<?php echo $module_id; ?>',
																	  '<?php echo $tree; ?>',
																	  this.value,
																	  '<?php  echo str_replace( "'" , "\\'" , $whenDonejs); ?>',
																	  '<?php echo $div_id; ?>',
																  	  { target:'<?php echo $div_id;?>'});																
										<?php echo $whenDonejs ; ?>;
										global_task.displayByModuleId('<?php echo  $module_name_true . "',
																			  '" . $module_id . "',
																			  '" . $div_id ."',
																			  '" . $show_all . "',"; ?> 
																			  { target:'<?php echo $div_id; ?>'} );
										global_task.predictPathStatus('<?php echo $tree; ?>',
																	  '<?php echo $module_name_true; ?>', 
																	  '<?php echo $module_id; ?>',
				                                         		 {preloader:'prl'});
										<?php if($module_name_true == 'case'){ ?>
												case_creation.showFlowChartTask('<?php echo $module_name_true; ?>', 
																				'<?php echo $module_id; ?>',
																				{target:'show_fct_<?php echo $module_id; ?>'});
										<?php } ?>" >
				<option value="" >-- SELECT --</option>
				<?php echo $this->array2options($this->getAvalibleTask( $tree , $module_name_true ), 'global_task_id', 'name'); ?>
			</select>
		<? } 
      }
       $html=ob_get_contents();
      ob_end_clean();
      return $html;
  }  
	
	function getNextModuleInfo( $current_module_id , $current_module_name , $requested_task ){
		
		$infoArr = $this->db->fetch_assoc($this->db->query("SELECT est_day_dep ,  module , global_task_tree_id tree , department_id group_id FROM tbl_global_task WHERE global_task_id= '$requested_task'"));
		$return = array();
		//echo $current_module_name.'--'.$infoArr["module"];
		
		if( $infoArr["module"] == $current_module_name ||  $current_module_name == "rework order"){
			$return[] = array("module_name" => $current_module_name , "module_id" => $current_module_id , "tree" => $infoArr["tree"] , 'group_id' => $infoArr['group_id'] , "est_day_dep" => $infoArr['est_day_dep'] );
		} else {
			switch($infoArr["module"]){
				case "order":
					if( $current_module_name == "work order"){
						$result = $this->db->query("SELECT * FROM erp_product_order WHERE workorder_id='$current_module_id'");
						while( $row=$this->db->fetch_assoc($result)){
							$return[] = array( "module_id" => $row["order_id"] , "module_name" => $infoArr["module"] , "tree" => $infoArr["tree"] );
						}
					}
				break;
				case "work order":
					if( $current_module_name == "order"){
						$result = $this->db->query("SELECT * FROM erp_product_order WHERE order_id='$current_module_id'");
						while( $row=$this->db->fetch_assoc($result)){
							$return[] = array( "module_id" => $row["workorder_id"] , "module_name" => $infoArr["module"] , "tree" => $infoArr["tree"] );
						}
					}				
				break;
			}
		}
		return $return;
	}
    function delete_flowchartTask( $chart_assign_id ){
        $this->db->query("DELETE FROM assign_flow_chart_task WHERE chart_assign_id = '$chart_assign_id'");
    }
    function submit_flowchartTask( $value='' , $chart_assign_id='' ,$module_name='', $module_id='', $target='' , $show_all='0',$type = '',$fct_id='' , $overide = array() ){
        //ini_set('display_errors',1);
        $check = $this->db->fetch_assoc($this->db->query("SELECT * FROM assign_flow_chart_task WHERE chart_assign_id = '$chart_assign_id'"));
        if( strtolower($check['task_status']) != "complete"){
            $this->use_page();
            $this->page->log_activity( $module_name , $module_id , 'flowchart_task_submited' , '' , $value , 'flowchart_task' ,  $chart_assign_id );
            $options = array();
            foreach( $overide as $n => $v){
                $options[$n] = $v;
            }
                ob_start();
                $flag_refresh = false;

                $nameArr = $this->db->fetch_assoc( $this->db->query("SELECT * FROM tbl_global_task_status WHERE global_task_status_id = '$value'") );
                $res1 = $this->db->query("SELECT * FROM `assign_report_to_system_task` a LEFT JOIN `template` b ON a.report_id = b.id  WHERE `selection_option_id` = '$value'");
                while( $row=$this->db->fetch_assoc($res1)){
                    $system_tasks[] = $row;
                }
                $stop = '';
                $javascript = '';
                $rt = array();
                            $stopped = "";
                //$stopped = $this->is_systemtask_stoped($chart_assign_id);
                foreach( $system_tasks as $n => $st ){
                    //$rt[] = $st["type"] . "== FILE $n";
                    if( $st["type"] == "FILE"){
                        $tmp = $st["file"];
                        $return = $this->$tmp($value , $chart_assign_id ,$module_name, $module_id, $target , $show_all, $order_id , array("stopped" => $stopped) , $options );
                        //$return = $this->$tmp( $module_name , $module_id , $value , $chart_assign_id );
                    //  $rt[]=$return;

                        $stop .= $return["stop"];
                        $javascript .= $return["javascript"];
                        $rhtml .= $return["html"];
                    } elseif( $st["type"] = "EMAIL" ){
                        $st["module_name"] = $module_option;
                        $st["module_id"] = $module_id;
                        $st["module"] = $module_option;
                        $st["value"] = $value;
                        $st["chart_assign_id"] = $chart_assign_id;
                        $return = $this->email( $st );
                        $stop .= $return["stop"];
                        $javascript .= $return["javascript"];
                        $rhtml .= $return["html"];
                    }


                }        

                if( $stop == ''){
                                    $sql_q= $this->db->query("UPDATE assign_flow_chart_task SET task_status = 'Complete' , `completion_date` = NOW() , `completion_result` = '" . $nameArr["global_task_status_name"]  . "' , `completed_by` = '" . $_SESSION['user_id'] . "'  WHERE chart_assign_id = '$chart_assign_id' ");
                                    //$sql_remove = $this->db->query("DELETE FROM `predicted_flow_chart_task` WHERE `chart_assign_id` = '$chart_assign_id'");					 
                                    $result = $this->db->query("SELECT * FROM tbl_global_task_status_result WHERE global_task_status_id = '$value'");
                                    $assignOld = $this->db->fetch_assoc($this->db->query("SELECT * FROM assign_flow_chart_task WHERE chart_assign_id='$chart_assign_id'"));
                                    $ia = array();
                                    while( $row = $this->db->fetch_assoc($result)){

                                                    $cm_info = $this->getNextModuleInfo( $module_id , $module_name , $row["global_task_id"]);
                                                    //print_r($cm_info);
    //						$sql_ship_date = "SELECT ship_date FROM erp_order WHERE order_id = '$order_id'";
    //						$result_ship_date = $this->db->query($sql_ship_date,__FILE__,__LINE__);
    //						$row_ship_date = $this->db->fetch_array($result_ship_date);

    //						$total_est_day = $this->totalEstDay($row["global_task_id"]);
    //						$this->task_est_day = ($total_est_day + $row['est_day_dep']) * 86400;		 
    //						$task_estimated_date = $this->estDueDate($this->task_est_day,time(),$row_ship_date['ship_date']);		 
    //						$percentage = $task_estimated_date/($total_est_day + $row['est_day_dep']);		 
    //						$per_day = $task_est * $percentage;
    //						$est_seconds = ($per_day * 24 * 60 * 60);
    //						$est_date = $this->estDueDate($est_seconds,time());

    //						$predicted = $this->estDueDate(($task_estimated_date*86400),time());

                                                    foreach( $cm_info as $info ){
                                                            if($module_name == $info['module_name']){
                                                                    $flag_refresh = true;
                                                            }//TMPHOL
    //                                                        $ia['debug'] = print_r( $info , true ) . print_r( $row , true);
                                                            $ia["owner_module_name"] = 'TBL_GROUP';
                                                            $ia["owner_module_id"] = $info['group_id'];
                                                            $ia["tree_id"] = $info["tree"];
                                                            $ia["module"] = $info["module_name"];
                                                            $ia["flow_chart_id"] = $row["global_task_id"];
                                                            $ia["task_status"] = "Active";
                                                            $ia["created_date"] = date('Y-m-d');
                                                            $ia["module_id"] = $info["module_id"];
                                                            $ia['parent_id'] = $chart_assign_id;
                                                            //$ia["product_id"] = $order_id;          //////////here product_id as a order_id///////////

                                                            //$ia["rework_id"] = $rework_id;
                                                            if( $info['est_day_dep'] == ''){
                                                                $est_time = strtotime("now");
                                                            } else {
                                                                $est_time = strtotime("+" . $info['est_day_dep'] . " day");
                                                            }

                                                            $ia["due_date"] = $this->calculate_due_date( $info["module_name"] ,$info["module_id"] , $info['est_day_dep'] );//$predicted;
                                                            $tmp = $this->get_defaults($ia["module"] , $ia["module_id"], $ia);
                                                            foreach( $tmp as $n => $v){
                                                                $ia[$n] = $v;
                                                            }
                                                            $this->db->insert('assign_flow_chart_task' , $ia );

                                                            $_SESSION['last_assign_id'] = $this->db->last_insert_id();

                                                    //	if($type == 'group'){
                                                                    $sql_update_group = $this->db->query("UPDATE erp_create_group SET assign_fct_id = '".$_SESSION['last_assign_id']."' WHERE order_id = '$order_id'  and assign_fct_id = '$chart_assign_id' ");
                                                    //	}
                                                            //echo $this->predictPathStatus($info["tree"],$info["module_name"],$info["module_id"],$order_id,$est_date,$row["global_task_id"]);	
                                                    }
                                    }

                                    //echo $this->updateFlowChartTask($order_id,'',$chart_assign_id);	
                            }




                            /*
                if(!$flag_refresh){
                                    ?>
                                    <script>window.location = "order.php?order_id=<?php echo $order_id; ?>";</script>
                                    <?php
                            }*/
            } else { $javascript = "alert('Task Has Already been completed');"; }
           echo $this->displayByModuleId($module_name, $module_id, $target, $show_all, $javascript , $rhtml);
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
         }

	function insertIntoGroup($module='',$module_id='',$inventory_id='',$inventory_name='',$last_assign_id='',$rework_id='',$order_id='',$measured_in=''){
		ob_start();
			
		  if( $module == 'work order' ){
				$sql_sub = "select gp_id,product_id from ".erp_PRODUCT_ORDER." where workorder_id = '$module_id'";
				//echo $sql_sub;
				$result_sub = $this->db->query($sql_sub,__FILE__,__LINE__);
				$row_sub = $this->db->fetch_array($result_sub);
				
				if( $row_sub[gp_id] == 0 )
				{ $product = $module_id; }
				else
				{ $product = $gp_id; }
		  
		  
			 $sql_inch = "SELECT size,quantity FROM ".erp_SIZE." WHERE product_id = '$product' and order_id = '$order_id'";
			 //$sql_inch = "SELECT size,quantity FROM erp_size WHERE product_id = '28' and order_id = '7'";
			 //echo $sql_inch;
			 $result_inch = $this->db->query($sql_inch,__FILE__,__LINE__);					 
			 while( $row_inch = $this->db->fetch_array($result_inch) ){
					$inch_array = explode("_",$row_inch[size]);
					$field_name = strtolower($inch_array[1]).'_inventory_usage';
					
					$sql_consumable = "Select $field_name from ".erp_ASSIGN_INVENTORY." where product_id = '$row_sub[product_id]' and group_inventory_id='0' and inventory_id = '$inventory_id'";
					//echo $sql_consumable.'<br>';
					$result_consumable = $this->db->query($sql_consumable,__FILE__,__LINE__);
					$row_consumable = $this->db->fetch_array($result_consumable);
					$inch += ($row_consumable[$field_name] * $row_inch[quantity]);
					
					//echo $inch.'aaaaaaa<br>';
			 }
		 } else {
			 $sql_re = "SELECT distinct rework_id, fabric_scrap FROM ".erp_REWORK." WHERE product_id = '$module_id' and order_id = '$order_id'";
			 //echo $sql_re;
			 $result_re = $this->db->query($sql_re,__FILE__,__LINE__);
			 $row_re = $this->db->fetch_array($result_re);
			 $inch = $row_re[fabric_scrap];
			 $rework_id = $row_re[rework_id];
			 
		   }
			
			$obj = new ConvertLength();
			
			
			$sql_gd_id="SELECT * FROM " . erp_GROUP . " where order_id='$order_id' and workorder_id='$module_id' ORDER BY g_id DESC";
			$result_id =$this->db->query($sql_gd_id);
			$row_gd_id=$this->db->fetch_array($result_id);
			$gd_id=$row_gd_id[group_id];
			
			if($gd_id==0)
			   $gd_id=1;
			else
			   $gd_id +=1;
			
			//echo $inch.'aaaaaaaaaa';
			$individual_inch = round($obj->convert($measured_in,"Inches",$inch),2);
			$total_inch = $individual_inch + $row_gd_id['total_inch'];
			
			if($row_gd_id[group_id]){
				$insert_sql_array[group_id] = $row_gd_id[group_id];
				$insert_sql_array[assign_fct_id] = $last_assign_id;
				$insert_sql_array[type] = $module;
				$insert_sql_array[rework_id] = $rework_id;
				$insert_sql_array[fabric_id] = $inventory_id;
				$insert_sql_array[inches] = $individual_inch;
				$insert_sql_array[created] = date("y-m-d");
				$insert_sql_array[inventory_name] = $inventory_name;
				$insert_sql_array[workorder_id] = $module_id;
				$insert_sql_array[order_id] = $order_id;
				$insert_sql_array[total_inch] = $total_inch;
				//print_r($insert_sql_array);
				
				$this->db->insert(erp_GROUP,$insert_sql_array);
			}
			
			$sql_q= $this->db->query("UPDATE erp_create_group SET total_inch = '" . $total_inch . "' WHERE group_id = '". $row_gd_id[group_id] ."' ");
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function array_exist($sub_array=array(),$main_array=array()){
		$flag = 0;
		foreach($main_array as $fields){
			if($fields[0]==$sub_array[0] and $fields[1]==$sub_array[1]) { $flag=1; return $flag; break;} 
		}
		return $flag;
	}
        /* This funciton should be used rarily
         * It is essencally a quick hack to get a 
         * project done by its deadline
         */
        function submit_flowchartTask_by_group( $group_id , $option , $flow_chart_id ){
            $sql = "SELECT a.* , b.module , b.module_id , c.* , d.* , e.* FROM `erp_create_group` a
JOIN assign_flow_chart_task b ON a.assign_fct_id = b.chart_assign_id AND ( a.type = 'work order' OR a.type = 'rework order' )
LEFT JOIN assign_flow_chart_task c ON b.module = c.module AND b.module_id = c.module_id AND c.task_status = 'Active'
JOIN tbl_global_task d ON c.flow_chart_id = d.global_task_id
JOIN tbl_usergroup e ON d.department_id = e.group_id
WHERE a.group_id = '$group_id' AND ( e.group_name = 'Print' or e.group_name = 'Dye Sublimation' OR e.group_name = 'Cut' ) AND c.flow_chart_id = '$flow_chart_id'";
            $result = $this->db->query($sql );
            $return = '';
            while($row=$this->db->fetch_assoc($result)){
                if( $row["module"] == "work order "){
                    $module_name = $row["module"];
                    $rework_order = '';
                } elseif( $row["module"] == "rework order"){
                    $module_name = "work order";
                    $rework_order = 'rework order';
                    
                    
                } else {
                    $module_name = $row["module"];
                    $rework_order = '';
                }
                
                $return .= "$option, $row[chart_assign_id], $module_name, $row[module_id], '', '', $row[order_id], $rework_order<br/>";
                $return .= $this->submit_flowchartTask($option, $row["chart_assign_id"], $module_name, $row["module_id"], '', '', $row["order_id"], $rework_order,"group");
              //$this->submit_flowchartTask($value, $chart_assign_id, $module_name, $module_id, $target, $show_all, $order_id, $rework_order)
            }
            
            ob_start();
			//echo $return;
            $this->displayByGroupID($group_id);
            $html=ob_get_contents();
                        ob_clean();
                        //return $html;
            /* pythonholum 11.30.11 5:17 */
            return $html ;//. "<br>$return";
            
            
            
        }
        
        function displayByGroupID( $group_id  ){
            $sql = "SELECT a.* , b.module , b.module_id , c.* , d.* , e.* FROM `erp_create_group` a
JOIN assign_flow_chart_task b ON a.assign_fct_id = b.chart_assign_id AND ( a.type = 'work order' OR a.type = 'rework order' )
LEFT JOIN assign_flow_chart_task c ON b.module = c.module AND b.module_id = c.module_id AND c.task_status = 'Active'
JOIN tbl_global_task d ON c.flow_chart_id = d.global_task_id
JOIN tbl_usergroup e ON d.department_id = e.group_id
WHERE a.group_id = '$group_id' AND  ( e.group_name = 'Print' or e.group_name = 'Dye Sublimation' OR e.group_name = 'Cut' ) 
group by c.flow_chart_id";
           // echo $sql;ob_start();
            $result = $this->db->query( $sql );
				//echo "displayByModuleId $module_name $module_id $target $show_all <br/>";
				echo $rhtml;  if($javascript != ''){?><script type="text/javascript">  <?php echo $javascript; ?>  </script><?php  } 
				while( $row = $this->db->fetch_assoc($result) ){ ?>
				<table class="table" >
				<tr>
					<td>
						<?
						$sql2 = "SELECT * FROM tbl_global_task WHERE global_task_id = '" . $row["flow_chart_id"] . "'";
						//echo $sql2;
						$result2 = $this->db->query( $sql2 );
						$FlowChart = $this->db->fetch_assoc($result2);					
						$sql_access = "SELECT * FROM tbl_usergroup a, group_access b WHERE a.group_id = '$FlowChart[department_id]' and a.group_id = b.group_id   and  b.user_id='$_SESSION[user_id]'";
						//echo $sql_access;
						$result_access = $this->db->query( $sql_access );
						
						$sql_admin = "SELECT * FROM group_access WHERE access_level = 'Admin' and user_id='$_SESSION[user_id]'";
						$result_admin = $this->db->query( $sql_admin );
						
						$access = false;
						
						if($this->db->num_rows($result_access) > 0 or $this->db->num_rows($result_admin) > 0) $access = true; ?>  
						<select name="result_task" id="result_task" <?php if(!$access){ echo "disabled='disabled'"; } ?>					        
						onchange="javascript: var selection_id = this.value;									 
									global_task.submit_flowchartTask_by_group( '<?php echo $group_id; ?>' , this.value, '<?php echo $row["flow_chart_id"];?>' , { target:'group_task_<?php echo $group_id;?>'} );
									<?php if( $module_name_true == 'work order' ){ ?>
									workorder.checkSystemTask(this.value,
															  '<?php echo $module_name_true; ?>',
															  '<?php echo $module_id; ?>',
															  document.getElementById('group_<?php echo $module_id.'_'.$order_id; ?>').innerHTML,
															  {preloader:'prl'});
									 <?php } ?>">
							<option>SELECT</option><?php
							$sql3 = "SELECT * FROM tbl_global_task_status WHERE global_task_id = '" . $row["flow_chart_id"] . "' ORDER BY `order`";
							//echo $row["product_id"] . "," . $row["module"] , "," , $row["flow_chart_id"] . "<br/>";
							$result3 = $this->db->query( $sql3 );
							$sql4 = "SELECT * FROM tbl_usergroup WHERE group_id = '" . $FlowChart['department_id'] . "'";
							$result4 = $this->db->query( $sql4 );
							$groups = $this->db->fetch_assoc($result4);
							while( $row2 = $this->db->fetch_assoc($result3)){ ?>
								<option value='<?php echo $row2["global_task_status_id"]; ?>'>
									<?php echo $row2["global_task_status_name"]; ?>
								</option> 
							<?php } ?> 
						</select>
					</td>
					<td style="color:#FF0000;"><?php echo $groups["group_name"];?></td>
					<td style="color:#999999;"><?php echo $FlowChart["name"];?></td>
					<td><div id="show_div"></div></td>
				</tr></table>
			<?php }// end of While         
        }
        function FlowChartDiv( $module_name , $module_id , $div_type , $overide = array() ){
                $disable_tree = false;
                $rand = rand( 0 , 99999999999999);
                $module_name_true = $module_name;
                $module_name = str_replace( ' ' , '' , $module_name );
                ob_start();
                $div_type .= "_$rand";
                $global_task = new GlobalTask();
                ?>
                <div id="flowcharttask_<?php echo $div_type . '_' . $module_name . '_' . $module_id;?>">
                        <table >
                          <tr >
                                <td colspan="2">
                                    <a href="javascript:void(0);" 
                                       <?php 
                                       if(true){ 
                                        $default_task = $this->get_default_flowchart($module_name);
                                        //if( $default_task !== false && $this->has_flowchart_task($module_name, $module_id) == false  ){ 
                                        if( true ){
                                            $div_id = "flowcharttask_" . $div_type . '_' . $module_name . '_' . $module_id; ?>
                                           onclick="javascript:slimcrm.case_fct_single=true;setTimeout( function(){ if( slimcrm.case_fct_single == true ){global_task.AddFlowChartTask('<?php echo $module_name_true; ?>', 
    															  '<?php echo $module_id; ?>',
																  '<?php echo $default_task['global_task_id']; ?>',
																  '<?php echo $default_task['global_task_id']; ?>',
																  '<?php  echo str_replace( "'" , "\\'" , $whenDonejs); ?>', 
																  '<?php echo $div_id; ?>',
																  { target:'<?php echo $div_id; ?>' , onUpdate: function(response , root){$('.right_tab_right_arrow_active').click();}} ); }} , 500 );"
                                        <?php } else { ?>
                                           onclick="$('.flowcharttask_add_<?php echo $div_type . '_' . $module_name . '_' . $module_id ; ?>').show();" 
                                        <?php }
                                            
                                       ?>
                                       ondblclick="slimcrm.case_fct_single=false;$('.flowcharttask_add_<?php echo $div_type . '_' . $module_name . '_' . $module_id ; ?>').show();"
                                       <?php } ?>
                                       ><button class="add_flowchart" >Create Flow Chart Task<div class="add_button in_button" >&nbsp;</div></button></a>
                                </td>
                          </tr>
                          <tr colspan="2">
                                <td>
                                  <div style="display:none;" class="flowcharttask_add_<?php echo $div_type . '_' . $module_name . '_' . $module_id;?> flowcharttask_add_<?php echo $module_name;?>" id="flowcharttask_add_<?php echo $div_type . '_' . $module_name . '_' . $module_id;?>">
                                    <?php 
                                    //   $global_task->AddFlowChartTask($module_name,      $module_id, $tree, $global_task_id, $whenDonejs, $div_id)
                                    echo $global_task->AddFlowChartTask($module_name_true, $module_id , $global_task->get_default_tree( $module_name)  , ''             , "$('.flowcharttask_add_" . $div_type . '_' . $module_name . '_' . $module_id . "_$rand" . "').hide();$('.right_tab_right_arrow_active').click();","flowcharttask_add_" . $div_type . '_' . $module_name . '_' . $module_id , $disable_tree);?>
                                  </div>
                                </td>
                          </tr>
                          <tr >
                                <td colspan="2">
                                    <div id="flowcharttask_options_<?php echo $div_type . '_' . $module_name . '_' . $module_id;?>" class="flowcharttask_options flowcharttask_options_<?php echo $module_name; ?>">
                                    <?php echo $global_task->displayByModuleId($module_name_true, $module_id, "flowcharttask_options_" . $div_type . '_' . $module_name . '_' . $module_id,'');?>
                                    </div>
                                </td>
                          </tr>
                        </table>
                </div>
                <?php
                $html=ob_get_contents();
                ob_end_clean();
                return $html;
   }
        function FlowChartDiv2( $module_name , $module_id , $div_type ){
                $rand = rand( 0 , 99999999999999);
                $module_name_true = $module_name;
                $module_name = str_replace( ' ' , '' , $module_name );
                ob_start();
                $div_type .= "_$rand";
                ?>
                <div id="flowcharttask_<?php echo $div_type . '_' . $module_name . '_' . $module_id;?>">
                        <table class="emaildashboard_fct">
                          <tr>
                                <td>
                                  <a href="javascript:void(0);" onclick="$('.flowcharttask_add_<?php echo $div_type . '_' . $module_name . '_' . $module_id ; ?>').show();"><img src="images/flag_fct/fct.gif" alt="add FCT" /></a>
                                </td>
                                <td>
                                  <div style="display:none;" class="flowcharttask_add_<?php echo $div_type . '_' . $module_name . '_' . $module_id;?> flowcharttask_add_<?php echo $module_name;?>" id="flowcharttask_add_<?php echo $div_type . '_' . $module_name . '_' . $module_id;?>">
                                    <?php echo $this->AddFlowChartTask($module_name_true, $module_id , '' , '' , "$('.flowcharttask_add_" . $div_type . '_' . $module_name . '_' . $module_id . "_$rand" . "').hide()","flowcharttask_add_" . $div_type . '_' . $module_name . '_' . $module_id);?>
                                  </div>
                                </td>
                                <td>
									  <div id="flowcharttask_options_<?php echo $div_type . '_' . $module_name . '_' . $module_id;?>" class="flowcharttask_options flowcharttask_options_<?php echo $module_name; ?>">
										<?php echo $this->displayByModuleId($module_name_true, $module_id, "flowcharttask_options_" . $div_type . '_' . $module_name . '_' . $module_id,'');?>
									  </div>
                                </td>
                          </tr>
                        </table>
                </div>
                <?php
                $html=ob_get_contents();
                ob_end_clean();
                return $html;
   }
   
         function check_permitions( $afct_arr ){
             $return = false;
             switch( strtolower( $afct_arr['owner_module_name']) ){
                 case "tbl_group":
                     $gp = $this->db->fetch_assoc($this->db->query("SELECT group_name FROM tbl_usergroup WHERE group_id = '" . $afct_arr['owner_module_id'] . "'"));
                     if(array_key_exists($gp["group_name"], $_SESSION["groups"])==true){
                         $return = true;
                     }
                 break;
                 case "tbl_user":
                     if( $_SESSION['user_id'] == $afct_arr['owner_module_id'] ){
                         $return = true;
                     }
                 break;
             }
             if(array_key_exists("Admin", $_SESSION["groups"])==true){
                         $return = true;
             }
             if(array_key_exists("admin", $_SESSION["groups"])==true){
                         $return = true;
             }
             return $return;
             
         }
         function has_flowchart_task($module_name , $module_id ){
             $ct = $this->db->num_rows( $this->db->query("SELECT * FROM assign_flow_chart_task WHERE module = '$module_name' AND module_id = '$module_id' LIMIT 1" ) );
             if( $ct == 0 ){
                 return false;
             } else {
                 return true;
             }
         }
         function has_active_flowchart_task($module_name , $module_id ){
             $ct = $this->db->num_rows( $this->db->query("SELECT * FROM assign_flow_chart_task WHERE module = '$module_name' AND module_id = '$module_id'  AND task_status <> 'Complete' LIMIT 1" ) );
             if( $ct == 0 ){
                 return false;
             } else {
                 return true;
             }
             
         }
    
    // function get_tasks_by_module( $module_name , $module_id , ){}
    
    function get_flowchart_options( $flow_chart_id ){
        $sql3 = "SELECT * FROM tbl_global_task_status WHERE global_task_id = '" . $flow_chart_id . "' ORDER BY `order`";
		$result3 = $this->db->query( $sql3 ); 
        $array = array();
        while( $row = $this->db->fetch_assoc($result3)){
            $row['access'] = $access;
            $array[] = $row;
        }
        return $array; 
    }
    
    
	 function displayByModuleId($module_name='',$module_id='' , $target='' , $show_all=0 , $javascript='' , $rhtml=''){
	    $module_name_true = $module_name;
		$module_name = str_replace( ' ' , '' , $module_name );
		$main_module_id = $module_id;
		$exclude = array();
		
		ob_start();
		
		$sql = "SELECT * FROM assign_flow_chart_task WHERE module = '$module_name' AND module_id = '$module_id'";
		if( $show_all != 1 ){
			$sql .= " AND task_status <> 'Complete'";
		}
		//echo $sql;
		$result = $this->db->query( $sql );
		//echo "displayByModuleId $module_name $module_id $target $show_all <br/>";
		echo $rhtml;  if($javascript != ''){?><script type="text/javascript">  <?php echo $javascript; ?>  </script><?php  } 
		while( $row = $this->db->fetch_assoc($result) ){ ?>
		<table class="flowchart_item_<?php echo $module_name; ?> flowchart_item event_form small_text display_by_module_<?php echo $module_name;?> display_by_module" width="100%">
		<tr>
			<td class="flowchart_item_col1_<?php echo $module_name; ?> flowchart_item_col1" >
				<?
				$sql2 = "SELECT * FROM tbl_global_task WHERE global_task_id = '" . $row["flow_chart_id"] . "'";
				$result2 = $this->db->query( $sql2 );
				$FlowChart = $this->db->fetch_assoc($result2);
				$access = $this->check_permitions($row);
                                ?>
				<select class=" result_task_<?php echo $module_name;?> result_task" name="result_task" id="result_task" <?php if(!$access){ echo "disabled='disabled'"; } ?>					        
				onchange="javascript: var selection_id = this.value;									 
							global_task.submit_flowchartTask(this.value,
															  '<?php echo $row["chart_assign_id"]."',
															  '".$module_name."',
															  '".$main_module_id."',
															  '".$target."',
															  '".$show_all."',";?> 
															  { target:'<?php echo $target; ?>',onUpdate: function(response,root){$('.right_tab_right_arrow_active').click();}} );">
					<option>SELECT</option><?php //"'
					$sql3 = "SELECT * FROM tbl_global_task_status WHERE global_task_id = '" . $row["flow_chart_id"] . "' ORDER BY `order`";
					//echo $row["product_id"] . "," . $row["module"] , "," , $row["flow_chart_id"] . "<br/>"; '
					$result3 = $this->db->query( $sql3 ); 
					
					$owner_module_name = $this->getOwnerModuleName($row['owner_module_name'] , $row['owner_module_id']);				
										
					while( $row2 = $this->db->fetch_assoc($result3)){ ?>
						<option value='<?php echo $row2["global_task_status_id"]; ?>'>
							<?php echo $row2["global_task_status_name"]; ?>
						</option> 
					<?php } ?> 
				</select>
			</td>
			<td class="flowchart_item_col2_<?php echo $module_name; ?> flowchart_item_col2" ><?php echo $this->displayOwnerModuleLink($row['module'],$row['module_id'],$row['flow_chart_id'],$owner_module_name, $row['chart_assign_id']); ?></td>
			<td class="flowchart_item_col3_<?php echo $module_name; ?> flowchart_item_col3"><?php echo $FlowChart["name"];?></td>
			<td><?php if( $row['parent_id'] == '0' ){ ?><div id="show_div" onclick="slimcrm.delete_flowchart('<?php echo $row['chart_assign_id']; ?>');" class="trash_can_normal"></div><?php } ?></td>
		</tr></table>
	<?php }// end of While

		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	  }
  
  	function getOwnerModuleName($owner_module_name , $owner_module_id){
		if($owner_module_name == "TBL_GROUP"){
			$result4 = $this->db->query("SELECT * FROM tbl_usergroup WHERE group_id = '" . $owner_module_id . "'");
			$groups = $this->db->fetch_assoc($result4);
			$owner_module_name = $groups["group_name"];
		}
							
		if($owner_module_name == "TBL_USER"){
			$result_user = $this->db->query("SELECT * FROM tbl_user WHERE user_id = '" . $owner_module_id . "'");
			$row_user = $this->db->fetch_assoc($result_user);
			$owner_module_name = $row_user["first_name"]." ".$row_user["last_name"];
		}	
		
		return $owner_module_name;		
	}
  
  	function displayOwnerModuleLink($module , $module_id , $flow_chart_id , $owner_module_name, $chart_assign_id){
		ob_start();		
                $rand = rand(0 , 999999999999);
                $div_id = $module_name . "_$module_id"."_$rand";
                if( trim($owner_module_name) == '' ){
                    $owner_module_name = "no name";
                }
                   
		?>
		<span id="div_owner_module_name_<?php echo $div_id;?>" <?php if($module == 'case') echo 'class="emaildashboard_menutitle"'; ?>>
                <a href="javascript:void(0);" 
                   onclick="slimcrm.global_task_sc=true;
                   setTimeout(function(){
                       if(slimcrm.global_task_sc==true){
                           global_task.updateOwnerInfo(
                            '<?php echo $chart_assign_id; ?>',
                            'TBL_USER-<?php echo $_SESSION['user_id']; ?>',
                            {
                                preloader:'prl',
                                onUpdate: function(response,root){
                                    global_task.displayOwnerModuleLink(
                                        '<?php echo $module; ?>',
                                        '<?php echo $module_id; ?>',
                                        '<?php echo $flow_chart_id; ?>',
                                        response,
                                        '<?php echo $chart_assign_id; ?>',
                                        {
                                            preloader:'prl',
                                            target:'div_owner_module_name_<?php echo $div_id;?>'
                                        }
                                    ); 
                                }
                            }
                        );
                       }
                       },500);"
                   ondblclick="
                   slimcrm.global_task_sc=false;
                    global_task.displayOwnerModuleDropDown(
                        '<?php echo $module; ?>',
                        '<?php echo $module_id; ?>',
                        '<?php echo $flow_chart_id; ?>',
                        '<?php echo $chart_assign_id; ?>',
                        { div_id: '<?php echo $div_id;?>'},
                        {preloader:'prl',target:'div_owner_module_name_<?php echo $div_id;?>'}
                    );">
                <?php echo $owner_module_name; ?>
			</a>	
		</span>	
		<?php
		$html=ob_get_contents();
		ob_end_clean();
		return $html;		
	}
	
	function displayOwnerModuleDropDown($module , $module_id , $flow_chart_id ,$chart_assign_id , $overide=array() ){
                $options["div_id"] = "";
                foreach( $overide as $n => $v){
                    $options[$n] = $v;
                }
		ob_start();
		$owner_module_info = $this->get_posible_owners_by_module($module, $module_id , $flow_chart_id);
		?>
		<select onchange="
                    global_task.updateOwnerInfo(
                        '<?php echo $chart_assign_id; ?>',
                        this.value,
                        {
                            preloader:'prl',
                            onUpdate: function(response,root){
                                global_task.displayOwnerModuleLink(
                                    '<?php echo $module; ?>',
                                    '<?php echo $module_id; ?>',
                                    '<?php echo $flow_chart_id; ?>',
                                    response,
                                    '<?php echo $chart_assign_id; ?>',
                                    {
                                        preloader:'prl',
                                        target:'div_owner_module_name_<?php echo $options["div_id"]; ?>'
                                    }
                                ); 
                            }
                        }
                    );">
                                    
			<option value="">-Select-</option>
			<?php foreach($owner_module_info as $owner_info){ ?>
				<option value="<?php echo $owner_info['module_name'].'-'.$owner_info['module_id']; ?>"><?php echo $owner_info['display_name']; ?></option>
			<?php } ?>
 		</select>
		<?php
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function getGlobalTaskId($module_name , $module_id){
		$result = $this->db->query("SELECT * FROM assign_flow_chart_task WHERE module = '$module_name' AND module_id = '$module_id' and task_status = 'Active'");
		$row = $this->db->fetch_assoc($result);
		return $row["global_task_id"];
	}
	
	function updateOwnerInfo($chart_assign_id  , $owner_module_info){
		$info = explode("-",$owner_module_info);
		$owner_module_name = $info[0];
		$owner_module_id = $info[1];

		$update_sql_array = array();		
		$update_sql_array['owner_module_name'] = $owner_module_name;
		$update_sql_array['owner_module_id'] = $owner_module_id;
		
		$this->db->update("assign_flow_chart_task",$update_sql_array,'chart_assign_id',$chart_assign_id );
		$name = $this->getOwnerModuleName($owner_module_name , $owner_module_id);
		return $name;
	}
  
	function count_win($global_task_id,$global_task,$level=1){
		
		$sql_comment = "Select * from tbl_global_task_status where global_task_id = '$global_task'";
		$result_comment = $this->db->query($sql_comment);
		
		if($this->db->num_rows($result_comment)){
			while($row_comment = $this->db->fetch_array($result_comment)){
				$_SESSION[inc]+=1 ;
				
				echo $level.' ,'.' '.$_SESSION[inc].' '.'  ,'.$global_task_id.'<br>';
				
				$sql_result = "Select * from tbl_global_task_status_result where global_task_status_id = '$row_comment[global_task_status_id]'";
				$result_result = $this->db->query($sql_result);
				if($this->db->num_rows($result_result)){
					while($row_result = $this->db->fetch_array($result_result)){
						$level += 1;
						$_SESSION[inc]+=1 ;
						echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$level.' ,'.' '.$_SESSION[inc].' '.'  ,'.$global_task_id.'<br>';
						//echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$level.' '.'target_id'.$_SESSION[inc].' '.'root'.$global_task_id.'<br>';
						$this->count_win($global_task_id,$row_result[global_task_id],($level+1));
					}
					echo '<br><br>';
				}
			}
		}
	}
  
	  function count_win1($global_task_id,$global_task_i,$level){
	      
		$sql_comment = "Select * from tbl_global_task_status where global_task_id = '$global_task_id'";
		$result_comment = $this->db->query($sql_comment);
		if($this->db->num_rows($result_comment)){
			while($row_comment = $this->db->fetch_array($result_comment)){
				$_SESSION[inc]+=1  ;
				//$level++;
				$source_id = $level;
				echo '<br>  '.$source_id;
				echo '   '.$_SESSION[inc];
				
				//$this->db->query("insert into erp_window_position(source_id,target_id,windows,root_id) values ('$source_id','$b','$row_comment[global_task_status_id]','$global_task_i')");
				
				
				$sql_result = "Select * from tbl_global_task_status_result where global_task_status_id = '$row_comment[global_task_status_id]'";
				$result_result = $this->db->query($sql_result);
				  $val_level = $level;
				  //echo '<br>Old_source'.$val_level;
			if($this->db->num_rows($result_comment)){
				while($row_result = $this->db->fetch_array($result_result)){
					//$val_level++;
					$a = $val_level;
					$c = ++$_SESSION[inc];
					echo '<br>  '.$a;
					echo '  '.$c;
				
				//$this->db->query("insert into erp_window_position(source_id,target_id,windows,root_id) values ('$a','$c','$row_comment[global_task_status_id]','$global_task_i')");
				
					$d = $val_level+1;
					//echo '<br>d'.$d;
					$this->count_win1($row_result[global_task_id],$global_task_id,$d);
				}
			}			
		} 
	 }
  
  }
  function get_default_flowchart( $module_name ){
        $da = $this->db->fetch_assoc($this->db->query("SELECT a.* , b.global_task_tree_id tree FROM `tbl_global_task_default` a LEFT JOIN tbl_global_task b ON a.global_task_id = b.global_task_id WHERE module_name = '$module_name'"));
        return $da;

  }
  function set_default_flowchart_status( $module_name , $global_task_id , $status ){
      if( $status == true ){
        $sql = "INSERT INTO tbl_global_task_default (`module_name` , `global_task_id`   ) VALUES ( '$module_name' , '$global_task_id' ) ON DUPLICATE KEY UPDATE `global_task_id` = '$global_task_id' ";
        $this->db->query($sql);
        return $sql;
      } else {
          $sql = "DELETE FROM tbl_global_task_default WHERE `global_task_id` = '$global_task_id'";
          $this->db->query($sql);
          return $sql;
      }
  }  
  function returnTaskSelectionOptions($gtid=''){
	ob_start();
	$sql = "Select * from tbl_global_task_status where global_task_id = '$gtid'";
	$result = $this->db->query($sql,__FILE__,__LINE__);
	?>
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
	}
	
     function task_preview($task_tree='',$gtid='',$vari=''){
  	  ob_start();
	  
	  $FormName = "frm_global_task_preview";
	  
	  $sql_gt = "Select root_task_id from tbl_global_task_tree where global_task_tree_id = '$task_tree'";
	  $result_gt = $this->db->query($sql_gt,__FILE__,__LINE__);
	  $row_gt = $this->db->fetch_array($result_gt);
	  if($gtid == ''){
	  $gtid = $row_gt[root_task_id]; }

  	  $sql_g ="Select a.*,b.* from tbl_global_task a, tbl_usergroup b where ( a.department_id = b.group_id OR a.department_id = '0' )and a.global_task_id  = '$gtid'";
	  $result_g = $this->db->query($sql_g,__FILE__,__LINE__);
	  $row_g = $this->db->fetch_array($result_g); 
	  //echo $sql_g;
	  ?>
		
	     <form name="<?php echo $FormName; ?>" enctype="multipart/form-data" action="" method="post">
	  	    <table>
			   <tr>
				 <td>
					<div id="new_task" style="font-weight:bold;">
							<?php echo $this->showLink('task_list_new','New Task'); ?>
					</div>
				 </td>
			   </tr>
			   <tr>
				 <td colspan="4" style="padding-left:10px;">
						<div id="task_list_new" style="display:none; padding-left:10px;">
							<table>
								<tr style="border-bottom: #0066FF 1px solid;">
									<th>Task Name</th>
									<th>Department Responsible</th>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td><input type="text" name="task_name_new" id="task_name_new" /></td>
									<td><?php echo $this->listDepartment('','new'); ?></td>
									<td><a href="javascript:void(0)" onClick="javascript: global_task.saveTask(
												'',
												document.getElementById('task_name_new').value,
												document.getElementById('departmentnew').value,
												'<?php echo $task_tree;?>',
												{onUpdate: function(response,root){
												var gtid = response;
												global_task.showSubTask(gtid,
														{target:'div_sub_task'});
												document.getElementById('task_name_new').value='';
												document.getElementById('departmentnew').value='';
												document.getElementById('task_list_new').style.display='none';
												}});"><img src="images/save3.png" border="0" height="20px" width="20px" /></a></td>
								  </tr>
								  <tr>
									<td colspan="4" style="padding-left:20px">
										<div id="div_sub_task">
										</div>
									</td>
								</tr>
							</table>
						</div>							
						<div id="task_list" class="contact_form">
							<table>
								<tr>
									<th>Task Preview:</th>
									<td><div id="div_preview_task_option"><?php echo $this->returnTaskSelectionOptions($gtid);?></div></td> 
									<th style="color:#FF0000"><?php echo $row_g['group_name'];?></th>
									<th style="color:#999999"><?php echo $row_g['name']; ?></th>
								</tr>								
								<tr style="border-bottom: #0066FF 1px solid;">
									<th>ID</th>
									<th>Task Name</th>
									<th>Department Responsible</th>
									<td><b><?php echo $row_g[module];?></b></td>
								</tr>
								<tr>
                                                                    <td><input type="text" name="task_id" id="task_id" style="width:30%" value="<?php echo $row_g['global_task_id'];?>"/><input style="position: relative;left: 10px;" name="input_cb_<?php echo $row_g['global_task_id'] ?>" id="input_cb_<?php echo $row_g['global_task_id'] ?>" class="input_cb_<?php echo $row_g['global_task_id'] ?>" <?php $tmpa =$this->get_default_flowchart($row_g['module']); if( $tmpa['global_task_id'] == $row_g['global_task_id'] ){ echo "CHECKED"; }; ?> type="checkbox" onchange="global_task.set_default_flowchart_status('<?php echo $row_g['module']; ?>','<?php echo $row_g['global_task_id']; ?>',$(this).ctl_checked() , {});" ></td>
									<td><input type="text" name="task_name<?php echo $row_g[global_task_id];?>" 
									id="task_name<?php echo $row_g[global_task_id];?>" value="<?php echo $row_g['name']; ?>"/></td>
									<td><?php echo $this->listDepartment($row_g['department_id'],$row_g['global_task_id']); ?></td>
									<td>
									  <a href="javascript:void(0);" onClick="javascript: global_task.saveTask(
												'<?php echo $row_g[global_task_id]; ?>',
												document.getElementById('task_name<?php echo $row_g[global_task_id];?>').value,
												document.getElementById('department<?php echo $row_g[global_task_id];?>').value,
												'<?php echo $task_tree;?>',
												{onUpdate: function(response,root){
												var gtid = response;
												global_task.showSubTask('<?php echo $row_g['global_task_id'];?>',
																		'<?php echo $row_g['department_id'];?>',
																		{target:'div_sub_task<?php echo $row_g[global_task_id];?>'});
												}});"><img src="images/save3.png" border="0" height="20px" width="20px" /></a>
                                                                            
									  <a href="javascript:void(0);" onClick="javascript: global_task.deleteall('<?php echo $row_g['global_task_id']; ?>',
											{onUpdate: function(response,root){
											
											global_task.showSubTask('<?php echo $this->global_task_id;?>',
																	'<?php echo $row_g[user_group];?>',
															{target:'div_sub_task<?php echo $this->global_task_id;?>'});
															
											global_task.returnTaskSelectionOptions('<?php echo $this->global_task_id; ?>',
																		   {target:'div_preview_task_option'});
											
											 }});"><img src="images/trash.gif" border="0" /></a>
									</td>
								  </tr>
								  <tr>
									<td colspan="4" style="padding-left:20px">
										<div id="div_sub_task<?php echo $gtid;?>">
											<?php echo $this->showSubTask($gtid,$row_g['department_id'],$task_tree);?>
										</div>
									</td>
								</tr>
							</table>
							<table class="table">
								<tr>
								    <td><h2>Task Settings</h2></td>
								</tr>
								<tr>
									<td align="right">Department Checkpoint :</td>
									<td>
									    <select id="dep_check" name="dep_check" 
										 onchange="javascript:global_task.update_estimate(
										                                    document.getElementById('dep_check').value,
																			document.getElementById('est_day_dep').value,
																			document.getElementById('est_min_task').value,
																			'<?php echo $gtid; ?>',
																			{preloader:'prl',
																			onUpdate:function(response,root){
																			var a = response; }} );">
									       <option value="no"<?php if($row_g[department_chk_tsk] == 'no') echo "selected='selected'" ?>>No</option>
										   <option value="yes"<?php if($row_g[department_chk_tsk] == 'yes') echo "selected='selected'" ?>>Yes</option>
										   
										</select>
									</td>
								</tr>
								<tr>
									<td align="right">Est Days in Department :</td>
									<td style="width:50%">
									    <input type="text" name="est_day_dep" id="est_day_dep" size="5" value="<?php echo $row_g[	est_day_dep]; ?>"
										        onchange="javascript:global_task.update_estimate(
												                                    document.getElementById('dep_check').value,
																					document.getElementById('est_day_dep').value,
																					document.getElementById('est_min_task').value,
																							  '<?php echo $gtid; ?>',
																							  {preloader:'prl',
																							  onUpdate:function(response,root){
																							  var a = response;
																							 }} );" />
									</td>
							    </tr>
								<tr>
									<td align="right">Est min For Task :</td>
									<td style="width:50%">
									    <input type="text" name="est_min_task" id="est_min_task" size="5" value="<?php echo $row_g[est_min_task]; ?>"
										       onchange="javascript:global_task.update_estimate(
											                                        document.getElementById('dep_check').value,
																					document.getElementById('est_day_dep').value,
																					document.getElementById('est_min_task').value,
																							 '<?php echo $gtid; ?>',
																							 {preloader:'prl',
																							 onUpdate:function(response,root){
																							 var a = response;
																							}} );" />
									</td>
								</tr>
								<tr>
									<td>Task Level</td>
									<td>
										<div id="show_path">
										<?php /*$sql_check = "select * from tbl_global_task where global_task_id = '$gtid'";
											  $result_check = $this->db->query($sql_check,__FILE__,__LINE__);
											  $row_check = $this->db->fetch_array($result_check);*/
											   
										  echo $this->show_module('local',$row_g[module],$gtid);?>
										</div>
									</td>
								</tr>
							</table>
						  </div>
				    </td>
			    </tr>		
		    </table>
	      </form>
	  <?php
	  $html = ob_get_contents();
	  ob_end_clean();
	  return $html;	
     } // End of function task_preview()
  
       function show_module($runat='',$module='',$gtid=''){
	    ob_start();
		switch($runat){
		    case 'local':
		?>
			<select name="module_type" id="module_type" 
			        onchange="javascript:global_task.show_module('server',
			 													 this.value,'<?php echo $gtid; ?>',
																 {preloader:'prl',
																 onUpdate:function(response,root){
																 
										 global_task.task_preview('','<?php echo $gtid; ?>','',
										                         {target:'div_task_list'}); }});">
			 <option value="">-select-</option>
			 <?php
			$sql="select * from ".erp_DROPDOWN_OPTION." where option_name= 'Flow Chart Task Options'";
			$result =$this->db->query($sql,__FILE__,__lINE__);	
			while($row=$this->db->fetch_array($result)){ ?>
				 <option value="<?php echo $row['identifier']; ?>" <?php if($row['identifier']==$module) echo 'selected="selected"'; ?>><?php echo $row['name']; ?></option>
			<?php } ?>
			</select>
	 
	 <?php break;
	       case 'server':
		   	
		     $update_sql_array = array();				
			 $update_sql_array[module] = $module;
			 $this->db->update('tbl_global_task',$update_sql_array,'global_task_id',$gtid);
	       }
		  
	 $html = ob_get_contents();
	 ob_end_clean();
	 return $html;
	 } /////end of function show_module
  
	 function update_estimate($depment_ch_pt='',$est_day_dep='',$est_min_task='',$gtid=''){
	    ob_start();
	 
		    $update_sql_array = array();				
			$update_sql_array[department_chk_tsk] = $depment_ch_pt;
			$update_sql_array[est_day_dep] = $est_day_dep;
			$update_sql_array[est_min_task] = $est_min_task;
			
			$this->db->update('tbl_global_task',$update_sql_array,'global_task_id',$gtid);
	      
	  $html = ob_get_contents();
	  ob_end_clean();
	  return $html;
	 } /////end of function update_estimate
 
     function show_path($task_tree){
	     ob_start(); ?>
	     <?php
				$sql_tree = "select * from tbl_projected_path where global_task_tree_id = '$task_tree'";
				$result_tree = $this->db->query($sql_tree,__FILE__,__LINE__);
				?>
							<table class="table">
							    <tr>
								  <th>path</th>
								  <th>from</th>
								  <th>to</th>
								</tr>
								<?php
								while($row_tree = $this->db->fetch_array($result_tree)){
								?>
								<tr>
								  <td><?php //$row_tree[]; ?></td>
								  <td><?php echo $row_tree[from_name]; ?></td>
								  <td><?php echo $row_tree[to_name]; ?></td>
								</tr>
								<?php } ?>
							</table>
	 <?php
	 $html = ob_get_contents();
	 ob_end_clean();
	 return $html;
	 }

     function deleteall($global_task_id=''){
				ob_start();
				
				$sql = "select global_task_tree_id from tbl_global_task where global_task_id = '$global_task_id'";
				$result = $this->db->query($sql,__FILE__,__LINE__);
				$row = $this->db->fetch_array($result);
				$tree_id = $row[global_task_tree_id];
				
				$sql1="delete from tbl_global_task where global_task_id = '$global_task_id'";
				$result1 = $this->db->query($sql1,__FILE__,__LINE__);
				
				$gtid=$global_task_id;
					$sql2="select global_task_status_id from tbl_global_task_status where global_task_id = '$global_task_id'";
					$result2=$this->db->query($sql2);
					
					while($row2=$this->db->fetch_array($result2)){
						$gtsid=$row2['global_task_status_id'];
//						echo $gtsid;
						$sql3="delete from tbl_global_task_status_result where global_task_status_id ='$gtsid'";


						$result3=$this->db->query($sql3);
						}
						$sql4 = "delete from tbl_global_task_status where global_task_id = '$gtid'";
						$result4 = $this->db->query($sql4,__FILE__,__LINE__);
						
						$sql5 ="delete from tbl_global_task_link where from_module_id = '$global_task_id' AND from_module_name = 'task'";
		                $result5 = $this->db->query($sql5,__FILE__,__LINE__);
		
		                $sql6 = "delete from tbl_global_task_link where to_module_id = '$global_task_id' AND to_module_name = 'task'";
		                $result6 = $this->db->query($sql6,__FILE__,__LINE__);
						?>
						<script language="javascript" type="text/javascript">
						window.location="FlowChartTasks.php";
						</script>
				<?php
				$html = ob_get_contents();
				ob_end_clean();
				return $html;	
	 } ////end of function deleteall

	 function saveTask($global_task_id='',$global_task_name='',$user_group='',$global_task_tree_id='',$task_type='1'){
			$est_hr=$global_estimate_hrs;
			$hrs_consumed=$global_task_hrs_comsumed;
			
			$insert_estimate_hr = array();
			$insert_estimate_hr=explode('.',$est_hr);
			
			$insert_actual_date= array();
			$insert_actual_date=explode('.',$hrs_consumed);
			
			//echo $insert[1];
			$today= strtotime('now');
			//echo $today;
			$mod= $insert_estimate_hr[0] / 8;
			$insert_estimate_mode= array();
			$insert_estimate_mode=explode('.',$mod);
			
			$mod= $insert_actual_date[0] / 8;
			$insert_actual_mode= array();
			$insert_actual_mode=explode('.',$mod);
			//echo "<br> mode".$insert_estimate_mode[1];
			//$actual_date=date("d:m:y");
			$actual_new_date=$today+(($insert_actual_mode[0])*24*60*60);
			$actual_date=date("Y-m-d",$actual_new_date);
			//echo "<br>".$day;
			//echo "<br>mode ".$mod;
			$new_date=$today+(($insert_estimate_mode[0])*24*60*60);
			$expected_date=date("Y-m-d",$new_date);
			//echo "<br>".$date;

	    ob_start();
	    if(!$global_task_id){		 
			 
			$sql="select `left`,`top` from tbl_global_task where global_task_tree_id = '$global_task_tree_id' order by top desc limit 1";
			$result=$this->db->query($sql);
			$row=$this->db->fetch_array($result);

			 
			 $sql_array = array();
			 $sql_array[name] = $global_task_name;
			 $sql_array[department_id] = $user_group;
			 $sql_array[task_type] = $task_type;
			 $sql_array[global_task_tree_id] = $global_task_tree_id;
			 $sql_array[top] = $row[top] + 30 ;			 

			 $this->db->insert('tbl_global_task',$sql_array);
			
			 
			 $last_val=$this->db->last_insert_id();			 
			  return $last_val;
		    } //end of if
	    else{
			 $sql_array = array();
			 $sql_array[name] = $global_task_name;
			 $sql_array[department_id] = $user_group;
			 $this->db->update('tbl_global_task',$sql_array,'global_task_id',$global_task_id);
	        } //end of else
	   $html = ob_get_contents();
	   ob_end_clean();
	   return $html;	
	  } //////end of function saveTask
         function update_task_status($gtsid,$value){
             $u = array();
             $u['order'] = $value;
             $this->db->update('tbl_global_task_status', $u, 'global_task_status_id', $gtsid);
         }
	 function showSubTask($gtid='',$parent_task_usergroup ='',$task_tree=''){
         ob_start();
         $this->global_task_id = $gtid;
		 $FormName = 'frm_sub_task'; ?>
		 <form name="<?php echo $FormName; ?>" enctype="multipart/form-data" action="" method="post">
			<table>
				<tr style="border-bottom: #0066FF 1px solid;">
					<th width="21%">Task Selection Option</th>
					<th>&nbsp;</th>
					<th width="22%">Group Permission</th>
					<th>&nbsp;</th>
				</tr>
				<?php 
				  $sql_g ="Select * from tbl_global_task_status where global_task_id  = '$gtid' ORDER BY `order` DESC";
				  $result_g = $this->db->query($sql_g,__FILE__,__LINE__);
				  while($row_g = $this->db->fetch_array($result_g)){
					?>
					<tr>
						<td><input type="text" name="task_selection_option<?php echo $row_g[global_task_status_id];?>"
						 id="task_selection_option<?php echo $row_g[global_task_status_id];?>" size="14" value="<?php echo $row_g[global_task_status_name];?>" />
						</td>
						<td><input type="text" style="width: 50px;" value="<?php echo $row_g['order']; ?>" onchange="global_task.update_task_status('<?php echo $row_g['global_task_status_id'];?>',$(this).val(),{});" /></td>
						<td><?php echo $this->listDepartment($row_g[user_group],$row_g[global_task_status_id]); ?></td>
						<td><a href="javascript:void(0)" onClick="javascript: global_task.saveTaskSelectionOption(
												'<?php echo $row_g[global_task_status_id];?>',
												'<?php echo $this->global_task_id; ?>',
												document.getElementById('task_selection_option<?php echo $row_g[global_task_status_id];?>').value,
												document.getElementById('department<?php echo $row_g[global_task_status_id];?>').value,
												'<?php echo $task_tree; ?>',
												{onUpdate: function(response,root){
												global_task.showSubTask('<?php echo $this->global_task_id;?>',
																		'<?php echo $row_g[user_group];?>',
																		'<?php echo $task_tree;?>',
																		{target:'div_sub_task<?php echo $this->global_task_id;?>'});
												global_task.returnTaskSelectionOptions('<?php echo $this->global_task_id; ?>',
																					   {target:'div_preview_task_option'});
												}});"><img src="images/save3.png" border="0" height="20px" width="20px" /></a>
							<a href="javascript:void(0)" onClick="javascript: global_task.deleteTaskSelectionOption(
											'<?php echo $row_g[global_task_status_id];?>',
											{onUpdate: function(response,root){
											
											global_task.showSubTask('<?php echo $this->global_task_id;?>',
																	'<?php echo $row_g[user_group];?>',
																	'<?php echo $task_tree;?>',
															{target:'div_sub_task<?php echo $this->global_task_id;?>'});
															
											global_task.returnTaskSelectionOptions('<?php echo $this->global_task_id; ?>',
																		   {target:'div_preview_task_option'}); }});"><img src="images/trash.gif" border="0" /></a>									
					<tr><td colspan="4" style="padding-left:20px">
						<div id="div_result_list<?php echo $row_g[global_task_status_id];?>">
							<?php echo $this->showResultTask($this->global_task_id,$row_g[global_task_status_id],$row_g[user_group],$task_tree); ?>
						</div>
					</td></tr>
					<?php } ?>
				<tr>
				<td colspan="4"><div id="show_sub_task<?php echo $gtid;?>" style="display:none;">
					<table><tr>
						<td><input type="text" name="task_selection_option_new<?php echo $gtid;?>" id="task_selection_option_new<?php echo $gtid;?>" />
						</td>
						<td>&nbsp;</td>
						<td><?php echo $this->listDepartment($parent_task_usergroup,'_new'.$gtid); ?></td>
						<td>
                                        <a href="javascript:void(0)" onClick="javascript: global_task.saveTaskSelectionOption(
                                            '',
                                            '<?php echo $this->global_task_id; ?>',
                                            document.getElementById('task_selection_option_new<?php echo $gtid;?>').value,
                                            document.getElementById('department<?php echo '_new'.$gtid;?>').value,
                                            '<?php echo $task_tree; ?>',
                                            {onUpdate: function(response,root){

                                            global_task.showSubTask('<?php echo $this->global_task_id; ?>',
                                                                                            '<?php echo $parent_task_usergroup; ?>',
                                                                                            '<?php echo $task_tree;?>',
                                                                            {target:'div_sub_task<?php echo $this->global_task_id;?>'});

                                            global_task.returnTaskSelectionOptions('<?php echo $this->global_task_id; ?>',
                                                                                            {target:'div_preview_task_option'});

                                            }});"><img src="images/save3.png" border="0" height="20px" width="20px" /></a>
						</td>
					<tr><td colspan="4" style="padding-left:40px">
						<div id="div_result<?php echo $gtid;?>"></div>
					</td></tr></table></div>
					 
					 <div id="show_sub_link_top">
						<?php 
						$uid = 'show_sub_task'.$gtid;
						echo $this->showLink($uid,'Task Selection');?>
					 </div>
				</td>
				</tr>
			</table>
		  </form>
		 <?php
		 $html = ob_get_contents();
		 ob_end_clean();
	 	 return $html;	
	  } /////end of function showSubTask

	  function saveTaskSelectionOption($global_task_status_id='',$global_task_id='',$global_task_status_name='',$user_group='',$global_task_tree_id=''){
		 ob_start();
		 
		 
		 $sql_array = array();
		 $sql_array[global_task_status_name] = $global_task_status_name;
		 $sql_array[user_group] = $user_group;
		 $sql_array[global_task_id] = $global_task_id;
		 $sql_array[global_task_tree_id] = $global_task_tree_id;
		 
		 if(!$global_task_status_id){		 
			  
			  $sql="select `left`,`top` from tbl_global_task_status where global_task_tree_id = '$global_task_tree_id' order by top desc limit 1";
			  echo $sql;
			  $result=$this->db->query($sql);
			  $row=$this->db->fetch_array($result);
			  $insert_array[top] = $row[top] + 30 ;
			  $insert_array[left] = 20;
			  
			  $this->db->insert('tbl_global_task_status',$sql_array);
			  $status_id = $this->db->last_insert_id();
			  
			  $insert_array = array();
		      $insert_array[from_module_name] = 'task';
		      $insert_array[from_module_id] = $global_task_id;
		      $insert_array[to_module_name] = 'status';
			  $insert_array[to_module_id] = $status_id;
			  $insert_array[global_task_tree_id] = $global_task_tree_id;
	  
			  
			  
			  $this->db->insert('tbl_global_task_link',$insert_array);
			 }
		 else{
			  $this->db->update('tbl_global_task_status',$sql_array,'global_task_status_id',$global_task_status_id);
			  //$this->db->update('erp_window_position',array('task_comment'=>$global_task_status_id),'window',$global_task_id);
		     }
	 $html = ob_get_contents();
	 ob_end_clean();
	 return $html;	
	} ////end of function saveTaskSelectionOption
	
	function deleteTaskSelectionOption($global_task_status_id=''){
	 ob_start();
		$sql="delete from tbl_global_task_status where global_task_status_id = '$global_task_status_id'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		
		$sql1 ="delete from tbl_global_task_status_result where global_task_status_id = '$global_task_status_id'";
		$result1 = $this->db->query($sql1,__FILE__,__LINE__);
		
		$sql3 ="delete from tbl_global_task_link where from_module_id = '$global_task_status_id' AND from_module_name = 'status'";
		$result3 = $this->db->query($sql3,__FILE__,__LINE__);
		
		$sql4 ="delete from tbl_global_task_link where to_module_id = '$global_task_status_id' AND to_module_name = 'status'";
		$result4 = $this->db->query($sql4,__FILE__,__LINE__);
		
	  $html = ob_get_contents();
	  ob_end_clean();
	  return $html;	
	 } ////end of function deleteTaskSelectionOption
	
	 function showResultTask($global_task_id='',$global_task_status_id='',$parent_task_usergroup='',$task_tree_id=''){
	    ob_start(); ?>
			<table width="340px">
				<tr style="border-bottom: #0066FF 1px solid;">
					<td>&nbsp;</td>
					<td width="20%">Id</td>
					<td width="43%">Selection Result</td>
					<td width="40%">Department Resposible</td>
				</tr>
				<?php 
				  $sql_g ="Select a.*,b.* from tbl_global_task a, tbl_global_task_status_result b where a.global_task_id = b.global_task_id and b.global_task_status_id = '$global_task_status_id'";
				  $result_g = $this->db->query($sql_g,__FILE__,__LINE__);
				  while($row_g = $this->db->fetch_array($result_g)){
					
					$sql = "select default_path from tbl_global_task where default_path > 0 and global_task_id='$row_g[global_task_id]'";
					$result = $this->db->query($sql);
					if($this->db->num_rows($result) > 0) $predicted=true; else $predicted=false;
					?>
					<tr>
						<td  valign="bottom"><input <?php if($predicted) echo 'checked="checked"';?> type="radio" name="for_track" id="for_track" 
						                                      onclick="javascript: global_task.setPredictedPath(
																					'<?php echo $task_tree_id;?>',
																					'<?php echo $global_task_id;?>',
																					'<?php echo $global_task_status_id;?>',
																					'<?php echo $row_g[global_task_id];?>',
																					{preloader:'prl'});" />&nbsp;</td>
						<td><?php echo $row_g[global_task_id];?>
						<?php /*?><input style="width:20%" type="text" value="<?php echo $row_g[global_task_id];?>" /><?php */?>
						</td>
						<td><?php echo $row_g[name];?>
						<?php /*?><input style="width:90%" type="text" name="task_selection_result<?php echo $row_g[global_task_status_result_id];?>"
						 id="task_selection_result<?php echo $row_g[global_task_status_result_id];?>" value="<?php echo $row_g[name];?>" /><?php */?>
						</td>
						<td><?php echo $this->getUserGroupById($row_g[department_id]); ?></td>

						<td>
						  <a href="javascript:void(0);" onClick="javascript: global_task.deleteTaskSelectionResult(
												'<?php echo $row_g[global_task_status_result_id];?>',
												'<?php echo $row_g[global_task_id]; ?>',
												{preloader:'prl',
												onUpdate: function(response,root){
												
												global_task.showResultTask('<?php echo $this->global_task_id; ?>',
												                           '<?php echo $global_task_status_id; ?>',
																		   '<?php echo $parent_task_usergroup; ?>',
																		   '<?php echo $task_tree_id; ?>',
																   {target:'div_result_list<?php echo $global_task_status_id;?>'});
											 }});"><img src="images/trash.gif" border="0" /></a>
						</td>					
					</tr>
				<?php } 
				 
				 ///////////////////////   SYSTEM TASK   //////////////////////////////
				 
				  $sql_s ="Select a.*,b.* from template a, assign_report_to_system_task b where a.id = b.report_id and b.selection_option_id = '$global_task_status_id'";
				  $result_s = $this->db->query($sql_s,__FILE__,__LINE__);
				  if($this->db->num_rows($result_s) > 0){
				  
				  while($row_s = $this->db->fetch_array($result_s)){
					
					$sql1 = "select default_path from tbl_global_task where default_path > 0 and global_task_id='$row_g[global_task_id]'";
					$result1 = $this->db->query($sql1);
					if($this->db->num_rows($result1) > 0) $predicted1=true; else $predicted1=false; ?>
					<tr>
						<td  valign="bottom"><input <?php if($predicted1) echo 'checked="checked"';?> type="radio" name="for_track1" id="for_track1" 
						                                      onclick="javascript: global_task.setPredictedPath(
																					'<?php echo $task_tree_id;?>',
																					'<?php echo $global_task_id;?>',
																					'<?php echo $global_task_status_id;?>',
																					'<?php echo $row_s[global_task_id];?>',
																					{preloader:'prl'});" />&nbsp;</td>
						<td><?php echo $row_s[id];?></td>
						<td><?php echo $row_s[title];?></td>
						<td><?php echo 'System Task'; ?></td>
						<td>
						  <a href="javascript:void(0);" onClick="javascript: global_task.deleteTaskSelectionResult(
																'<?php echo $row_s[system_task_id];?>',
																'<?php echo $row_s[id]; ?>',
																'system_task',
																{preloader:'prl',
																onUpdate: function(response,root){
																global_task.showResultTask('<?php echo $this->global_task_id; ?>',
																						   '<?php echo $global_task_status_id; ?>',
																						   '<?php echo $parent_task_usergroup; ?>',
																						   '<?php echo $task_tree_id; ?>',
																				   {target:'div_result_list<?php echo $global_task_status_id;?>'});
															 }});"><img src="images/trash.gif" border="0" /></a>
						</td>					
					</tr>					
				<?php } 
					}   //////////////////////////////    END OF SYSTEM TASK   ///////////////////////////'
			    ?>
				<tr>
				<td colspan="4"><div id="show_sub_task_result<?php echo $global_task_status_id;?>" style="display:none;">
					<table><tr>
						<td><?php /*
						<select name="task_selection" id="task_selection<?php echo $global_task_status_id;?>"
						        onchange="javascript:global_task.selection(this.value,
						                                                   '<?php echo $global_task_status_id;?>',
																		   '<?php echo $task_tree_id;?>',
																		   '<?php echo $this->global_task_id; ?>',
										              {preloader:'prl',onUpdate: function(response,root){
										document.getElementById('show<?php echo $global_task_status_id;?>').innerHTML=response;
												}} );">
						<option value="1" selected="selected">Global Task</option>
						<option value="2">System Task</option>
						</select> */ ?>
                        <select name="task_selection" id="task_selection<?php echo $global_task_status_id;?>"
    					        onchange="javascript:global_task.selection(this.value,
						                                                   '<?php echo $global_task_status_id;?>',
																		   '<?php echo $task_tree_id;?>',
																		   '<?php echo $this->global_task_id; ?>',
										              {preloader:'prl',onUpdate: function(response,root){
										document.getElementById('show<?php echo $global_task_status_id;?>').innerHTML=response;
                                        $('.other_tree_ac').autocomplete({ source: 'global_task_search.php' , select: function(event,ui){ $('.selection_task').val( ui.item.global_task_id );}});
												}} );">
						<option value="1" selected="selected">Global Task</option>
						<option value="2">System Task</option>
                        <option value="other_tree" >Another Tree</option>
						</select>
						</td>
						<td>
						<div id="show<?php echo $global_task_status_id;?>">
						<?php echo $this->selection(1,$global_task_status_id,$task_tree_id); ?>
						</div>
						</td>
						<td>
						   <a href="javascript:void(0)" 
						      onClick="javascript: 
							  	if(document.getElementById('task_selection<?php echo $global_task_status_id;?>').value == 1 || document.getElementById('task_selection<?php echo $global_task_status_id;?>').value == 'other_tree'){	
									global_task.saveTaskSelectionResult(
						                        '<?php echo $this->global_task_id; ?>',
												'<?php echo $global_task_status_id; ?>',
												document.getElementById('selection_task<?php echo $global_task_status_id;?>').value,
												'<?php echo $task_tree_id; ?>',
												{onUpdate: function(response,root){
												
												global_task.insert_window(
												'<?php echo $this->global_task_id; ?>',
												document.getElementById('selection_task<?php echo $global_task_status_id;?>').value,
												{onUpdate: function(response,root){
												
												global_task.showResultTask('<?php echo $this->global_task_id; ?>',
												                           '<?php echo $global_task_status_id; ?>',
																		   '<?php echo $parent_task_usergroup; ?>',
																		   '<?php echo $task_tree_id; ?>',
																   {target:'div_result_list<?php echo $global_task_status_id;?>'});
												},preloader:'prl'}); }});
								}
							   else {
							   	   global_task.updateSystemTask(document.getElementById('selection_task<?php echo $global_task_status_id;?>').value,
															   '<?php echo $global_task_status_id; ?>',
															   '<?php echo $this->global_task_id; ?>',
															   '<?php echo $task_tree_id; ?>',
															   {preloader:'prl',
															   	onUpdate: function(response,root){
																	global_task.showResultTask('<?php echo $this->global_task_id; ?>',
																							   '<?php echo $global_task_status_id; ?>',
																							   '<?php echo $parent_task_usergroup; ?>',
																							   '<?php echo $task_tree_id; ?>',
																					{target:'div_result_list<?php echo $global_task_status_id;?>'});
																	}});
							   }"><img src="images/save3.png" border="0" height="20px" width="20px" /></a>
						</td>
						</tr></table></div>
					 <div id="show_sub_link_top">
						<?php
						$uid = 'show_sub_task_result'.$global_task_status_id;
						echo $this->showLink($uid,'Selection Result');?>
					 </div>
				</td>
				</tr>
			</table>
		 <?php
	  $html = ob_get_contents();
	  ob_end_clean();
	  return $html;	
	 } ////end of function showResultTask

	  function saveTaskSelectionResult($global_task_id='',$global_task_status_id='',$global_task_i='',$global_task_tree_id='',$type=''){
		 ob_start();
		 if(!$global_task_status_result_id){		 		 
			 $sql_array = array();
			 $sql_array[global_task_id] = $global_task_i;
			 $sql_array[global_task_status_id] = $global_task_status_id;
			 
			 $this->db->insert('tbl_global_task_status_result',$sql_array);
			 
			 $insert_array = array();
			 $insert_array[from_module_name] = 'status';
			 $insert_array[from_module_id] = $global_task_status_id;
			 $insert_array[to_module_name] = 'task';
			 $insert_array[to_module_id] = $global_task_i;
			 $insert_array[global_task_tree_id] = $global_task_tree_id;
			 
			 $this->db->insert('tbl_global_task_link',$insert_array);
			 
		 } 
	    $html = ob_get_contents();
	    ob_end_clean();
	    return $html;	
	  } ////end of function saveTaskSelectionResult
	  
	
	 function deleteTaskSelectionResult($global_task_status_result_id='',$global_task_id='',$task_type=''){
	    ob_start();
		if($task_type==''){
	
			/*$sql = "Select * from tbl_global_task_link where from_module_name = 'task' and 	from_module_id = '$global_task_id'";
			$result = $this->db->query($sql,__FILE__,__LINE__);
			while($row = $this->db->fetch_array($result)){ //start of while
			
			$sql2 ="delete from tbl_global_task_link where from_module_id = '$row[to_module_id]' AND from_module_name = 'status'";
			$result2 = $this->db->query($sql2,__FILE__,__LINE__);
			
			} //end of while*/
			
			$sql = "Select global_task_status_id, global_task_id from ".erp_GLOBAL_TASK_STATUS_RESULT." where global_task_status_result_id = '$global_task_status_result_id'";
			$result = $this->db->query($sql,__FILE__,__LINE__);
			$row = $this->db->fetch_array($result);
			
			$sql_g ="delete from ".erp_GLOBAL_TASK_STATUS_RESULT." where global_task_status_result_id = '$global_task_status_result_id'";
			$result_g = $this->db->query($sql_g,__FILE__,__LINE__);
			

			/*$sql ="delete from tbl_global_task where global_task_id = '$global_task_id'";
			$result = $this->db->query($sql,__FILE__,__LINE__);
			
			$sql3 ="delete from tbl_global_task_link where from_module_id = '$global_task_id' AND from_module_name = 'task'";
			$result3 = $this->db->query($sql3,__FILE__,__LINE__);*/
			
			$sql4 ="delete from ".GLOBAL_TASK_LINK." where from_module_name = 'status' and from_module_id = '$row[global_task_status_id]' and to_module_id = '$row[global_task_id]' AND to_module_name = 'task'";
			$result4 = $this->db->query($sql4,__FILE__,__LINE__);
		}
		else {
			$sql_system ="delete from assign_report_to_system_task where system_task_id = '$global_task_status_result_id'";
			$result_system = $this->db->query($sql_system,__FILE__,__LINE__);
			
			$sql_system_link ="delete from tbl_global_task_link where to_module_id = '$global_task_id' AND to_module_name = 'system'";
			$result_system_link = $this->db->query($sql_system_link,__FILE__,__LINE__);
			
		}
		
	  $html = ob_get_contents();
	  ob_end_clean();
	  return $html;	
	 } ////end of function deleteTaskSelectionResult
	
	
	 function deleteTask($global_task_id=''){
	    ob_start();
	    $sql="select * from tbl_global_task_status where global_task_id = '$global_task_id'";
		$result=$this->db->query($sql,_FILE_,_LINE_);
		while($row=$this->db->fetch_array($result)){
		      $d=$row['global_task_status_id'];
			  $sql_status="delete from tbl_global_task_status_result where global_task_status_id = '$d'";
			  $result_status=$this->db->query($sql_status,__FILE__,__LINE__);
			  } 
			
		$sql="delete from tbl_global_task_status where global_task_id = '$global_task_id'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
				
		$sql ="delete from tbl_global_task where global_task_id = '$global_task_id'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		
	  $html = ob_get_contents();
	  ob_end_clean();
	  return $html;	
	 } ////end of function deleteTask
	/*
	 function selection($id='',$status_id='',$task_tree='',$global_task_id=''){
	  ob_start(); 
	  if($id == 2){
			$sql = "Select * from template";
			$result = $this->db->query($sql,__FILE__,__LINE__);	?>
			<select name="selection_task<?php echo $status_id; ?>" id="selection_task<?php echo $status_id; ?>">
				<option value="">-Select-</option>
				<?php  

				while($row = $this->db->fetch_array($result)){ ?>
				<option value="<?php echo $row[id]; ?>"><?php echo $row[title]; ?></option>
			<?php } ?>		 			
			</select> 
	  <?php  }
	  else{
		//echo $id; ?>
			<select name="selection_task<?php echo $status_id; ?>" id="selection_task<?php echo $status_id; ?>">
				<option value="">-Select-</option>
				<?php  
				$sql = "Select * from tbl_global_task where global_task_tree_id = '$task_tree'";
				if($id){
				$sql .= " and task_type = '$id'"; }
				$result = $this->db->query($sql,__FILE__,__LINE__);
				while($row = $this->db->fetch_array($result)){ ?>
				<option value="<?php echo $row['global_task_id']; ?>">
				<?php echo $row['name']; ?></option>
			<?php } ?>		 			
			</select> 	  
	  <?php
	  }
   	  $html = ob_get_contents();
	  ob_end_clean();
	  return $html;	
     } /////end of function selection	*/
          function selection($id='',$status_id='',$task_tree='',$global_task_id=''){
             $log = $this->enable_log;
            
            if( $log == true){
                $log_data = (strtotime("NOW") +  microtime() ).  ":" .get_class() . ":" . __FUNCTION__ . ":" . " " . __LINE__ . __FILE__;
                file_put_contents('/var/log/platform.time.log', $log_data . "\n" , FILE_APPEND);
            }
	  ob_start(); 
	  if($id == 2){
			$sql = "Select * from template";
			$result = $this->db->query($sql,__FILE__,__LINE__);	?>
			<select name="selection_task<?php echo $status_id; ?>" id="selection_task<?php echo $status_id; ?>">
				<option value="">-Select-</option>
				<?php  

				while($row = $this->db->fetch_array($result)){ ?>
				<option value="<?php echo $row[id]; ?>"><?php echo $row[title]; ?></option>
			<?php } ?>		 			
			</select> 
	  <?php  }
	  elseif( $id == 1 ){
		//echo $id; ?>
			<select name="selection_task<?php echo $status_id; ?>" id="selection_task<?php echo $status_id; ?>">
				<option value="">-Select-</option>
				<?php  
				$sql = "Select * from tbl_global_task where global_task_tree_id = '$task_tree'";
				if($id){
				$sql .= " and task_type = '$id'"; }
				$result = $this->db->query($sql,__FILE__,__LINE__);
				while($row = $this->db->fetch_array($result)){ ?>
				<option value="<?php echo $row['global_task_id']; ?>">
				<?php echo $row['name']; ?></option>
			<?php } ?>		 			
			</select> 	  
	  <?php
	  } else {
    ?> <input class="other_tree_ac" style="width: 50px" /> <input class="selection_task"  style="width: 20px; " name="selection_task<?php echo $status_id; ?>" id="selection_task<?php echo $status_id; ?>"/> <?php   
	  }
   	  $html = ob_get_contents();
	  ob_end_clean();
	  return $html;	
     } /////end of function selection	
	
	 function updateSystemTask($id='',$selection_option_id='',$global_task_id='',$tree_id=''){
		ob_start();	 
		$sql_check = "SELECT * FROM assign_report_to_system_task WHERE global_task_id = '$global_task_id' and selection_option_id = '$selection_option_id' and report_id = '$id'";
		$result_check = $this->db->query($sql_check);
		$row_check = $this->db->fetch_array($result_check);
		
		if($this->db->num_rows($result_check) > 0){
			 $sql_array = array();
			 $sql_array[global_task_id] = $global_task_id;
			 $sql_array[selection_option_id] = $selection_option_id;			 
			 $sql_array[report_id] = $id;
			 $this->db->update('assign_report_to_system_task',$sql_array,'system_task_id',$row_check['system_task_id']);
		}
		else {
			 $sql_array = array();
			 $sql_array[global_task_id] = $global_task_id;
			 $sql_array[selection_option_id] = $selection_option_id;			 
			 $sql_array[report_id] = $id;
			 $this->db->insert('assign_report_to_system_task',$sql_array);		
			 //$last_system_task_id = $this->db->last_insert_id();				 
			 
			 $insert_array = array();
			 $insert_array[from_module_name] = 'status';
			 $insert_array[from_module_id] = $selection_option_id;
			 $insert_array[to_module_name] = 'system';
			 $insert_array[to_module_id] = $id;
			 $insert_array[global_task_tree_id] = $tree_id;
			 $this->db->insert('tbl_global_task_link',$insert_array);
			 
		}
	   $html = ob_get_contents();
	   ob_end_clean();
	   return $html;				 
	 }

	 function insert_window($global_task_id='',$created_gt_id=''){
	    ob_start();
		$sql_check = "SELECT * FROM ".erp_WINDOW." WHERE root_id = '$global_task_id' and global_task_id = '$global_task_id'";
		$result_check = $this->db->query($sql_check);
		$row_check = $this->db->fetch_array($result_check);
		$i = 1;
		if($this->db->num_rows($result_check) < 0){
			   $insert_sql_array = array();
			   $insert_sql_array[global_task_id] = $global_task_id;
			   $insert_sql_array[window] = $i;
			   $insert_sql_array[root_id] = $global_task_id;
			
			   $this->db->insert(erp_WINDOW,$insert_sql_array);
			 } //end of if
		else {
			   $sql_check1 = "SELECT * FROM ".erp_WINDOW." WHERE root_id = '$global_task_id'";
		       $result_check1 = $this->db->query($sql_check1);
		       while($row_check1 = $this->db->fetch_array($result_check1)){
			   
			       $i = $i+1;
				  } //end of while
			   $insert_sql_array = array();
			   $insert_sql_array[global_task_id] = $created_gt_id;
			   $insert_sql_array[window] = $i;
			   $insert_sql_array[root_id] = $global_task_id;
				
			   $this->db->insert(erp_WINDOW,$insert_sql_array);
			 } //end of else
	  $html = ob_get_contents();
	  ob_end_clean();
	  return $html;
	 }
	 
     function track_result($global_task_tree_id='',$global_task_status_id='',$global_task_i='',$global_task_id=''){
	     ob_start();
		 
		 $sql2 = "SELECT * FROM tbl_projected_path WHERE start_from = '$global_task_id' and end_to = '$global_task_i'";
		       $result2 = $this->db->query($sql2,__FILE__,__LINE__);
		       $row2 = $this->db->fetch_array($result2);
			   if($this->db->num_rows($result2) == 0){
		 
         $sql = "SELECT * FROM tbl_global_task WHERE global_task_id = '$global_task_id'";


		       $result = $this->db->query($sql,__FILE__,__LINE__);
		       $row = $this->db->fetch_array($result);
		       $from = $row[name];
			   
			   $sql1 = "SELECT * FROM tbl_global_task WHERE global_task_id = '$global_task_i'";
		       $result1 = $this->db->query($sql1,__FILE__,__LINE__);
		       $row1 = $this->db->fetch_array($result1);
		       $next = $row1[name];
			   
			   $sql_gt = "Select root_task_id from tbl_global_task_tree where global_task_tree_id = '$global_task_tree_id'";
	  		   $result_gt = $this->db->query($sql_gt,__FILE__,__LINE__);
	  		   $row_g = $this->db->fetch_array($result_gt);
			   $task_id = $row_g[root_task_id];
			   
	     $sql_array = array();
		 
		 $sql_array[global_task_tree_id] = $global_task_tree_id;
		 $sql_array[end_to] = $global_task_i;
		 $sql_array[global_task_status_id] = $global_task_status_id;
		 $sql_array[start_from] = $global_task_id;
		 $sql_array[root_id] = $task_id;
		 $sql_array[from_name] = $from;
		 $sql_array[to_name] = $next;
		 
		 $this->db->insert('tbl_projected_path',$sql_array);
		 
		 }
		    
		 /*$sql = "Select * from tbl_global_task_status  where global_task_status_id='$global_task_status_id'";
		 $result = $this->db->query($sql,__FILE__,__LINE__);
		 $row = $this->db->fetch_array($result);
		 $pre_global_task_id=$row['global_task_id'];
		 
		 $sql = "Select * from tbl_global_task_status_result  where global_task_status_id='$global_task_status_id' order by global_task_status_result_id";
		 $result = $this->db->query($sql,__FILE__,__LINE__);
		 $row = $this->db->fetch_array($result);
		 $result_name=$row['global_task_id'];
		 
		 $sql = "Select * from tbl_global_task_status  where  global_task_status_id='$global_task_status_id'";
		 $result = $this->db->query($sql,__FILE__,__LINE__);
		 $row = $this->db->fetch_array($result);
		 $global_task_status_name=$row['global_task_status_name'];
		 
		 $sql_array = array();
		 $sql_array[global_task_id] = $pre_global_task_id;
		 $sql_array[global_task_status_id] = $global_task_status_id;
		 $sql_array[task_sel_opt] = $global_task_status_name;
		 $sql_array[result_td] = $global_task_i;
		 
		 $this->db->insert('global_task_predict',$sql_array);*/
		
	   $html = ob_get_contents();
	   ob_end_clean();
	   return $html;	
	 } ////end of function track_result
	 
     function showLink($div_id='',$link_content=''){
		ob_start(); ?>
		 <a style="color:#FF0000; font-size:15px" onClick="javascript: 
		                                if(this.innerHTML=='+'){
													this.innerHTML = '-';
													document.getElementById('<?php echo $div_id; ?>').style.display = 'block';
													}
													else {
													this.innerHTML = '+';
													document.getElementById('<?php echo $div_id; ?>').style.display = 'none';
													} ">+</a>&nbsp;<?php echo $link_content; ?>	 
	 <?php
	 $html = ob_get_contents();
	 ob_end_clean();
	 return $html;	
  } /////end of function showLink

  function listDepartment($group_id='',$u_id=''){
      ob_start();
      ?>	  
      <select name="department<?php echo $u_id;?>" id="department<?php echo $u_id;?>" style="width:100%" >
	    <option value="">-Select-</option>
		<?php  
		$sql = "Select * from tbl_usergroup";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		while($row = $this->db->fetch_array($result)){ ?>
			<option value="<?php echo $row['group_id']; ?>" 
			<?php if($row['group_id'] == $group_id) echo "selected='selected'" ?>>
			<?php echo $row['group_name']; ?></option>
		<?php } ?>		 			
	  </select> 	  
	  <?php
   	  $html = ob_get_contents();
	  ob_end_clean();
	  return $html;	
  } //End of function listDepartment()  
   
  function taskListing($task_name='',$user_group=''){
  	ob_start();
	?>
		<table style="font-size:14px">
			<tr>
				<td width="10%" style="color:#666666">ID</td>
				<td width="20%" style="color:#666666">Task Name</td>
				<td width="20%" style="color:#666666">Department</td>
			</tr>
			<tr><td colspan="3"><hr color="#0033CC" /></td></tr>
			<?php 
			 $sql = "Select a.*,b.* from tbl_global_task a, tbl_usergroup b where a.department_id = b.group_id";
			 if($task_name) $sql .= " and a.name like '%$task_name%'";
			 if($user_group) $sql .= " and b.group_id = '$user_group'";
			 $result = $this->db->query($sql,__FILE__,__LINE__);
			 while($row = $this->db->fetch_array($result)){ ?>
			 <tr>
			 	<td><a href="FlowChartTasks.php?gtid=<?php echo $row['global_task_id']; ?>" ><?php echo $row['global_task_id']; ?></a></td>
				<td><a href="FlowChartTasks.php?gtid=<?php echo $row['global_task_id']; ?>" ><?php echo $row['name']; ?></a></td>
				<td><?php echo $row['group_name']; ?></td>
			</tr> 
			<?php } ?>
		</table>
	<?php
	 $html = ob_get_contents();
	 ob_end_clean();
	 return $html;	
  }
  
  function getUserGroupById($group_id=''){
	  $sql_user_group = "Select * from tbl_usergroup where group_id = '$group_id'";
	  $result_user_group = $this->db->query($sql_user_group,__FILE__,__LINE__);
	  $row_user_group = $this->db->fetch_array($result_user_group);
	  return $row_user_group['group_name'];
  } // End of function getUserName()
  
  
  
  

  /****************************************************************************************************************************************************/
  
  
  /************************************************* USER VIEW ************************************************************************************/ 
  
  function taskPreviewUser($module_id='',$module_name='',$created_user_id=''){
  	  ob_start();
	  $FormName = "frm_global_task_preview";
  	  $sql_g ="Select a.name,a.department_id,b.group_name,b.group_id,c.global_task_id,c.global_task_link_id from tbl_global_task a, tbl_usergroup b,tbl_global_task_link c where a.department_id = b.group_id and a.global_task_id  = c.global_task_id and c.module_id = '$module_id' and c.module_name = '$module_name' and (c.completed_user_id = '' or c.completed_user_id = 0)";
	  $result_g = $this->db->query($sql_g,__FILE__,__LINE__);
	  ?>
	  <form name="<?php echo $FormName; ?>" enctype="multipart/form-data" action="" method="post">
	  	<table width="100%">
			<tr><th style="background-color:#CCCCCC" colspan="3">Tasks :</th></tr>
			<?php while($row_g = $this->db->fetch_array($result_g)){
			
			$sql_check = "select * from ".GROUP_ACCESS." where user_id = '$created_user_id' and group_id= '$row_g[group_id]'";			
			$result_check = $this->db->query($sql_check,__FILE__,__LINE__);
			$row_check = $this->db->fetch_array($result_check);
			if($this->db->num_rows($result_check)>0){
			?>			
			<tr>
				<td><div id="div_preview_task_option">
					<?php echo $this->returnTaskSelectionOptionsUser($row_g['global_task_link_id'],$row_g['global_task_id'],$module_id,$module_name,$created_user_id,$row_g['department_id'],$row_g['group_id']);?>
					</div></td> 
				<th style="color:#FF0000"><?php echo $row_g['group_name'];?></th>
				<th style="color:#999999"><?php echo $row_g['name']; ?></th>
			</tr>
			<?php }
			else { ?>
			<tr>
				<td><div id="div_preview_task_option">
					<?php echo $this->returnTaskSelectionOptionsUser($row_g['global_task_link_id'],$row_g['global_task_id'],'','',$created_user_id,'',$row_g['group_id']);?>
					</div></td> 
				<th style="color:#999999"><?php echo $row_g['group_name'];?></th>
				<th style="color:#999999"><?php echo $row_g['name']; ?></th>
			</tr>				 
			<?php } 
			}?>
			<tr>
				<td>&nbsp;</td>
				<td colspan="2">
					<a href="javascript:void(0)" onClick="javascript: global_task.addTaskToModule(
																		'local',
																		'',
																		'<?php echo $module_id;?>',
																		'<?php echo $module_name;?>',
																		'<?php echo $created_user_id;?>',
																		{preloader:'prl',target:'div_add_task'});">add</a>
				</td>
			</tr>
		</table>
		<div id="div_add_task"></div>
	  </form>
	  <?php
	 $html = ob_get_contents();
	 ob_end_clean();
	 return $html;	
  } // End of function task_preview()
  
  function addTaskToModule($runat='',$global_task_id='',$module_id='',$module_name='',$created_user_id=''){
	 ob_start();
	 switch($runat){
		case 'local':
				
				 $sql = "Select * from tbl_global_task";
				 $result = $this->db->query($sql,__FILE__,__LINE__);
				 ?>
				 <select id="global_task_id" name="global_task_id" style="width:40%">
				 <option value="">--Select Task--</option>
				 <?php
				 while($row = $this->db->fetch_array($result)){ ?>
				 	<option value="<?php echo $row['global_task_id']; ?>"><?php echo $row['name']; ?></option>
				 <?php } ?>
				 </select>
				 <input type="button" value="Save" onClick="javascript: global_task.addTaskToModule(
																		'server',
																		this.form.global_task_id.value,
																		'<?php echo $module_id;?>',
																		'<?php echo $module_name;?>',
																		'<?php echo $created_user_id;?>',
																		{preloader:'prl',target:'div_add_task',
																		onUpdate: function(response,root){
																			global_task.taskPreviewUser(
																			'<?php echo $module_id;?>',
																			'<?php echo $module_name;?>',
																			'<?php echo $created_user_id;?>',
																			{target:'div_list_global_task'});
																			}
																		});"/>
				<?php				
				break;
		case 'server':
			 $sql_array = array();
			 $sql_array[global_task_id] = $global_task_id;
			 $sql_array[module_id] = $module_id;
			 $sql_array[module_name] = $module_name;
			 $sql_array[created_user_id] = $created_user_id;
			 $this->db->insert('tbl_global_task_link',$sql_array);
			 break;
		}
	 $html = ob_get_contents();
	 ob_end_clean();
	 return $html;	

  }
  
  function returnTaskSelectionOptionsUser($global_task_link_id='',$gtid='',$module_id='',$module_name='',$created_user_id='',$department_id='',$group_id=''){
	ob_start();
	$sql = "Select * from tbl_global_task_status where global_task_id = '$gtid' and user_group IN ( Select group_id from group_access where user_id = '$created_user_id')";
	$result = $this->db->query($sql,__FILE__,__LINE__);
	?>
	<select name="preview_task_option" id="preview_task_option" style="width:100%" 
										onchange="javascript: global_task.selectionResult(
													'<?php echo $global_task_link_id;?>',
													this.value,
													'<?php echo $gtid;?>',
													'<?php echo $module_id;?>',
													'<?php echo $module_name;?>',
													'<?php echo $created_user_id;?>',
													{preloader: 'prl',
													onUpdate: function(response,root){
														global_task.taskPreviewUser(
														'<?php echo $module_id;?>',
														'<?php echo $module_name;?>',
														'<?php echo $created_user_id;?>',
														{target:'div_list_global_task'});
														}
													})">
		<option value="">-Select-</option>
		<?php while($row = $this->db->fetch_array($result)){ ?>
		<option value="<?php echo $row['global_task_status_id']; ?>" >
		<?php echo $row['global_task_status_name']; ?></option>
		<?php } ?>
	</select>
	<?php
	 $html = ob_get_contents();
	 ob_end_clean();
	 return $html;	
	}
	
	function selectionResult($global_task_link_id,$global_task_status_id='',$global_task_id='',$module_id='',$module_name='',$created_user_id=''){
		 ob_start();
			 $sql_array = array();
			 $sql_array[completed_user_id] = $created_user_id;
			 $sql_array[completed_date_time] = time();
			 $this->db->update('tbl_global_task_link',$sql_array,'global_task_link_id',$global_task_link_id);
			 
			 $sql = "Select global_task_id from tbl_global_task_status_result where global_task_status_id = '$global_task_status_id'";
			 $result = $this->db->query($sql,__FILE__,__LINE__);
			 while($row = $this->db->fetch_array($result)){ 
			 	echo $this->addTaskToModule('server',$row[global_task_id],$module_id,$module_name,$created_user_id);
			 }
		 $html = ob_get_contents();
		 ob_end_clean();
		 return $html;	
		}

   
      function task_stats(){
  	     ob_start(); ?> 
			<table style="font-size:14px">
				
				<tr>
					<td width="10%" style="color:#666666">Task</td>
					<td width="20%" style="color:#666666">Expected</td>
					<td width="20%" style="color:#666666">Actual</td>
				</tr>
				<tr>
					<td colspan="3"><hr color="#0033CC" /></td>
				</tr>
					<?php 
					$sql = "Select * from tbl_global_task ";
					$result = $this->db->query($sql,__FILE__,__LINE__);
					while($row = $this->db->fetch_array($result)){ ?>
				<tr>
					<td><?php echo $row['name']; ?></td>
					<td><?php echo $row['expected_date']; ?></td>
					<td><?php echo $row['actual_date']; ?></td>
				</tr> 
				<?php } ?>
			</table>
	  <?php
	    $html = ob_get_contents();
	    ob_end_clean();
	    return $html;	
     } /////end of function task_stats

	function insertDueDate($global_task_id='',$module_id=''){

		$date = date("Y-m-d");
		
		$sql_due = "Select est_day_dep from tbl_global_task where global_task_id = '$global_task_id'";
		$result_due = $this->db->query($sql_due,__FILE__,__LINE__);
		$row_due = $this->db->fetch_array($result_due);
				
		$date_est = $row_due["est_day_dep"];
		$due_date = $this->calDueDate($date_est,$date,1);
		
		$sql = "Select ship_date from erp_order where order_id = '$_REQUEST[order_id]'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		$row = $this->db->fetch_array($result);
		$ship_date  = $row["ship_date"];

/*		if($ship_date < $_SESSION[newvalue]){ 
			$diff = abs(strtotime($ship_date) - strtotime($_SESSION[newvalue]));
			$years = floor($diff / (365*60*60*24));
			$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			if($days > 0){
				$sub_date = $this->calDueDate($days,$due_date,'');
				return $sub_date;
			}
			if($days == 0 and $months > 0){
				$day_month = $months*30;
				$sub_date = $this->calDueDate($day_month,$due_date,'');
				return $sub_date;
			}
		}
		else{
			return $due_date;
		}
*/
	}
	
	  function calDueDate($w_days='',$est_date='',$a=''){
	    ob_start();
	      if($a!=''){	
			$cur_date=date("Y-m-d",strtotime($est_date));
			while($w_days >= 0){
				if(!in_array(date("N",strtotime($cur_date)),array(6,7))) $w_days--;
				$cur_date = date ("Y-m-d", strtotime ("+1 day", strtotime($cur_date))); 
			}
			
			$cur_date = date ("Y-m-d", strtotime ("-2 day", strtotime($cur_date)));
			if(!in_array(date("N",strtotime($cur_date)),array(6,7))) 
			$cur_date = date ("Y-m-d", strtotime ("+1 day", strtotime($cur_date))); 
			if(!in_array(date("N",strtotime($cur_date)),array(6,7))) 
			$cur_date = date ("Y-m-d", strtotime ("+1 day", strtotime($cur_date))); 

			$cur_date = date ("Y-m-d", strtotime ("-2 day", strtotime($cur_date)));
			return $cur_date;
		  }
		  else {
			$cur_date=date("Y-m-d",strtotime($est_date));
			$w_days = $w_days+1;
			while($w_days >= 0){
				if(!in_array(date("N",strtotime($cur_date)),array(6,7))) $w_days--;
				$cur_date = date ("Y-m-d", strtotime ("-1 day", strtotime($cur_date))); 
			}
			
			$cur_date = date ("Y-m-d", strtotime ("+2 day", strtotime($cur_date)));
			if(!in_array(date("N",strtotime($cur_date)),array(6,7))) 
			$cur_date = date ("Y-m-d", strtotime ("-1 day", strtotime($cur_date))); 
			if(!in_array(date("N",strtotime($cur_date)),array(6,7))) 
			$cur_date = date ("Y-m-d", strtotime ("-1 day", strtotime($cur_date))); 
			
			$cur_date = date ("Y-m-d", strtotime ("+2 day", strtotime($cur_date)));
			return $cur_date;
		  }

	$html=ob_get_contents();
	ob_end_clean();
	return $html;
	
	} /////end of function calDueDate
 /*********************************************************************************************************************************************************/
  
      function task_tree($task_tree=''){
		 ob_start(); 
		 ?>
        	<form method="post" action="">
			<table class="table" width="100%">
			  <tr>
                <td width="22px">
				  <a href="#" onClick="SavePositions()"><img  src="images/save.png" alt="Move Task" /></a>
				</td>
				<?php /*?><td width="60px">
				  <a href="javascript:void(0);" onclick="javascript:global_task.windows(1,
																					{preloader:'prl',
																					onUpdate:function(response,root){
																					global_task.display_task(
																					{preloader:'prl',
																					onUpdate:function(response,root){
													 document.getElementById('iframe_div').innerHTML=response;
																					}});
																					}});">Insert</a>
				</td>
				<td width="60px">
				  <a href="javascript:void(0);"><img src="images/save3.png" border="0" height="22%" width="22%" /></a>
				</td><?php */?>
				
				<?php
				  $sql_task = "SELECT * FROM ".GLOBAL_TASK_TREE;
				  $result_task = $this->db->query($sql_task);
				?>
				<td>Task Tree :
				  <select id="task_tre" name="task_tre">
				    <option value="">-select-</option>
					<?php while($row_task = $this->db->fetch_array($result_task)){ ?>
					<option value="<?php echo $row_task[global_task_tree_id]; ?>"<?php if($row_task[global_task_tree_id] == $task_tree) echo "selected='selected'" ?>><?php echo $row_task[global_task_tree_name]; ?></option>
					<?php }?>
				  </select>
				  <input type="submit" value="ok" name="ok" style="width:auto"/></td>
				  <td>
				     <a href="javascript:void(0);" 
				         onclick="javascript: var name = prompt('Please enter task tree name','task tree');
					    														global_task.add_tree(name,
																				{preloader:'prl',
																				onUpdate:function(response,root){
																				global_task.task_tree('<?php echo $task_tree; ?>',
																				{preloader:'prl',
																				onUpdate:function(response,root){
																	document.getElementById('task_tree').innerHTML=response;
																				}});
																				}}); ">ADD TREE</a>
				  
				  </td>	
				  <td>
				     <?php 
					 $sql = "Select root_task_id from tbl_global_task_tree where global_task_tree_id = '$task_tree' and root_task_id > 0";
					 $result = $this->db->query($sql,__FILE__,__LINE__);
					 if($this->db->num_rows($result) <= 0 ){ ?>
						 <a href="javascript:void(0);" 
							 onclick="javascript: var root_task_id = prompt('Please Enter Task Id','0');
												  global_task.setRootTask('<?php echo $task_tree; ?>',root_task_id,
													{preloader:'prl'}); ">Set Root Task</a>
					<?php } ?>

				  </td>		
			  </tr>
			</table>
			</form>
	 <?php
	  $html = ob_get_contents();
	  ob_end_clean();
	  return $html;
	 } /////end of function task_tree  

	 function setRootTask($global_task_tree_id,$root_task_id){
		ob_start();	 
		$sql_insert = array();
		$sql_insert[root_task_id] = $root_task_id;
		$this->db->update('tbl_global_task_tree',$sql_insert,'global_task_tree_id',$global_task_tree_id);
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	 }
	 
	 function add_tree($task_tree=''){
	    ob_start();
	      $insert_sql_array = array();
		  $insert_sql_array[global_task_tree_name] = $task_tree;
			
		  $this->db->insert('tbl_global_task_tree',$insert_sql_array);
	    
	  $html = ob_get_contents();
	  ob_end_clean();
	  return $html;
	 }

         function get_tree_objects( $global_tree_id ){
             $return = array();
             $sql_tasks = "SELECT * FROM `" . GLOBAL_TASK . "` WHERE `global_task_tree_id` = '$global_tree_id'";
             $res = $this->db->query($sql_tasks);

             while( $row = $this->db->fetch_assoc($res)){
                 $return[] = array( "module" => "task"  , "module_id" => $row["global_task_id"]  , "name" => $row["name"] , "group_id" => $row["department_id"] , "top" => $row["top"] , "left" => $row["left"] );
             }
             
             $sql_tasks = "SELECT * FROM `" . GLOBAL_TASK_STATUS . "` WHERE `global_task_tree_id` = '$global_tree_id'";
             $res = $this->db->query($sql_tasks);
             
             while( $row = $this->db->fetch_assoc($res)){
                 $return[] = array( "module" => "status"  , "module_id" => $row["global_task_status_id"]  , "name" => $row["global_task_status_name"] , "group_id" => $row["user_group"] , "top" => $row["top"] , "left" => $row["left"] );
             } 
			 
             $sql_tasks = "SELECT * FROM `" . TBL_TEMPLATE. "` a, `".GLOBAL_TASK_LINK."` b where a.id = b.to_module_id and b.global_task_tree_id = '$global_tree_id' and b.to_module_name = 'system'";
             $res = $this->db->query($sql_tasks);
       
             while( $row = $this->db->fetch_assoc($res)){
                 $return[] = array( "module" => "system"  , "module_id" => $row["id"]  , "name" => '<div style="width:100%;" align="center"><img src="images/tux.png" width="30px"></div><br>'. $row["title"] , "group_id" => $row["user_group"] , "top" => $row["top"] , "left" => $row["left"] );
             } 
			         
             return $return;
             
         }
         
         function isDefaultPath( $info ){
			 if($info["to_module_name"] != "system" and $info["from_module_name"] != "system"){
				 $tableArr = array( "task" => "tbl_global_task" , "status" => "tbl_global_task_status");
				 $tableColArr = array( "task" => "global_task_id" , "status" => "global_task_status_id");
				 $sql1 = "SELECT default_path FROM `" . $tableArr[ $info["from_module_name"]] . "` WHERE `" . $tableColArr[ $info["from_module_name"]] . "` = '" . $info["from_module_id"] . "'";             
				 $sql2 = "SELECT default_path FROM `" . $tableArr[ $info["to_module_name"]] . "` WHERE `" . $tableColArr[ $info["to_module_name"]] . "` = '" . $info["to_module_id"] . "'";
				 $res1 = $this->db->query($sql1);
				 $res2 = $this->db->query($sql2);
				 $arr1 = $this->db->fetch_assoc($res1);
				 $arr2 = $this->db->fetch_assoc($res2);
				 if( $arr1["default_path"] == 1 && $arr2["default_path"] == 1 ){
					 return true;
				 } else {
					 return false;
				 }
			 }
        }
         
		 function setPredictedPath($global_task_tree_id,$gt,$from,$to){
		 	ob_start();
			 
			$sql = "update tbl_global_task_status set default_path='0' where global_task_id='$gt' and global_task_tree_id='$global_task_tree_id'";
			$result = $this->db->query($sql);

			$sql = "update tbl_global_task_status set default_path='1' where global_task_status_id='$from' and global_task_id='$gt' and global_task_tree_id='$global_task_tree_id'";
			$result = $this->db->query($sql);
			
			$sql = "update tbl_global_task set default_path='0' where global_task_id in( select distinct global_task_id from tbl_global_task_status_result where global_task_status_id in (select distinct global_task_status_id from tbl_global_task_status where global_task_id='$gt')) and global_task_tree_id='$global_task_tree_id'";
			$result = $this->db->query($sql);
			
			$sql = "update tbl_global_task set default_path='1' where global_task_id='$to' and global_task_tree_id='$global_task_tree_id'";
			$result = $this->db->query($sql);
			
			
						 
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		 }
         
         function get_module_links($global_tree_id){
             $return = array();
             $sql_tasks = "SELECT * FROM `" . GLOBAL_TASK_LINK . "` WHERE `global_task_tree_id` = '$global_tree_id'";
             $res = $this->db->query($sql_tasks);

             while( $row = $this->db->fetch_assoc($res)){
                              
                 
                 
                 $return[] = $row;

                
             }
             return $return;
             
         }
         
         function check_for_links( $module_name , $module_id , $tree){
       
             $sql = "SELECT * FROM `tbl_global_task_link` WHERE  (from_module_name = '$module_name' AND from_module_id = '$module_id' ) OR ( to_module_name = '$module_name' AND to_module_id = '$module_id' )";
             $sql2 = "SELECT * FROM `tbl_global_task_link` WHERE global_task_tree_id = '$tree'";
             if( $this->db->num_rows($this->db->query($sql)) == 0 AND  $this->db->num_rows($this->db->query($sql2)) != 0 ){
                 return false;
             } else {
                 return true;
             }
         }
         
         
         function writeJSPlumbArea( $global_tree_id=''){
	    ob_start(); ?>
	    <div id="task_display">
		<?php 
                /*
                 * 
                 */
                $jsPlumbObjects = $this->get_tree_objects($global_tree_id);
                ?>
                <!-- <?php print_r($jsPlumbObjects); ?> -->
                <?php
                foreach( $jsPlumbObjects as  $object){
                    if( $object["module"] == "status" ){ 
						$pre_idtxt = "status"; 
						$bg_color = '#CCCC99'; 
						$inside_html = $object["name"]; 
					} else if( $object["module"] == "system" ){ 
						$pre_idtxt = "system"; 
						$bg_color = '#CCC'; 
						$inside_html = $object["name"]; 
					} else { 
						$pre_idtxt = "window";
						$bg_color = '#ffffff'; 
						$inside_html = $this->returnLink($global_tree_id,$object['module_id'],$object['name'],'window'.$object["module_id"],'task_name'); 
					}
                                        
                    if( $this->check_for_links($object["module"], $object["module_id"] , $global_tree_id) == true ){                    
                    ?>
                    <div class="window" id="<?php echo $pre_idtxt . $object["module_id"];?>"  style="background-color: <?php echo $bg_color; ?>;left:<?php echo $object["left"]; ?>px; top:<?php echo $object["top"]; ?>px">
                    <?php echo $inside_html; ?>
                    </div>
                    <?php
                    } else {
                        ?><!--  NoLinks for <?php print_r( $object ); ?>  --> <?php
                    }
                }?>
		
         </div>
		 
		<div id="show_value<?php echo $global_tree_id; ?>"></div>
		<script type="text/javascript">
		;(function() {
			
			window.jsPlumbDemo = {
				init : function() {
					jsPlumb.Defaults.DragOptions = { cursor: 'pointer', zIndex:2000 };
					jsPlumb.Defaults.EndpointStyles = [{ fillStyle:'#558822' }, { fillStyle:'#558822' }];
					jsPlumb.Defaults.Endpoints = [ [ "Dot", {radius:7} ], [ "Dot", { radius:11 } ]];
					jsPlumb.setMouseEventsEnabled(true);
											
					jsPlumb.Defaults.Overlays = [
						[ "Arrow", { location:.66 , width: 10, lenght: 30   } ],
                                                [ "Arrow", { location:.33 , width: 10, lenght: 30 } ],
						[ "Label", { 
							location:0.9,
							label:function(label) {
								return label.connection.labelText || ""; 
							},
							cssClass:"aLabel"
						}] 
					];
		
					var connectorPaintStyle = {
						lineWidth:3,
						strokeStyle:"#000000",
                                                
                                                gradient:{
                                                 stops:[[0,'#757575'], [1,'#020B96']]
                                                },
						joinstyle:"round"
					},
                                        connectorPaintStyleDP = {

						lineWidth:6,
						strokeStyle:"#000000",
                                                
                                                gradient:{
                                                 stops:[[0,'#C40000'], [1,'#FF9100']]
                                                },
						joinstyle:"round"
					},
					connectorHoverStyle = {
						lineWidth:7,
						strokeStyle:"#2e2aF8"
					},
                                        sourceEndpointDP = {
						endpoint:"Dot",
						paintStyle:{ fillStyle:"#00B7FF",radius:1 },
						isSource:true,
                                                maxConnections:200,
						connector:[ "Flowchart", { stub:20 } ],
                                                //connector: "Straight",
						isTarget:true,
						connectorStyle:connectorPaintStyleDP,
					},
					sourceEndpoint = {
						endpoint:"Dot",
						paintStyle:{ fillStyle:"#00B7FF",radius:1 },
						isSource:true,
                                                maxConnections:200,
						connector:[ "Flowchart", { stub:20 } ],
						isTarget:true,
						connectorStyle:connectorPaintStyle,
					},
					bottomSource = jsPlumb.extend( { anchor:[[ 0.51 , 0 , 0 , -1] , [ .51 , 1 , 1 , 0  ] , [ 0 , .51 , -1 , 0 ] , [ 1 , .51 , 1 , 0 ]]  }, sourceEndpoint),
                                        bottomSourceDP = jsPlumb.extend( { anchor:[[ 0.51 , 0 , 0 , -1] , [ .51 , 1 , 1 , 0  ] , [ 0 , .51 , -1 , 0 ] , [ 1 , .51 , 1 , 0 ]]  }, sourceEndpointDP),
					targetEndpoint = {
						endpoint:"Dot",					
						paintStyle:{ fillStyle:"#558822",radius:1 },
						isSource:true,
						hoverPaintStyle:connectorHoverStyle,
						maxConnections:200,
						//connector:[ "Flowchart", { stub:20 } ],                                              
						dropOptions:{ hoverClass:"", activeClass:"active" },
						//isTarget:true,
						anchor: [[ 0.49 , 0 , 0 , -1] , [ .49 , 1 , 0 , 0  ] , [ 0 , .49 , -1 , 0 ] , [ 1 , .49 , 1 , 0 ]]  
                                                //anchor: [[ .5 , .5, 0 , -1] , [ .5 , .5 , 0 , 0  ] , [ .5 , .5 , -1 , 0 ] , [ .5 , .5 , 1 , 0 ]] 

					};
					
					
					var windows=new Array();
					var windows1=new Array();
					var windows2=new Array();
					<?php
                                        $str_window = '';
                                        
                                        foreach( $jsPlumbObjects as $n => $object ){
                                            if( $n != 0 ){
                                                $str_window .= ',';
                                            }
                                            
                                            if( $object["module"] == "status" )
												{ $pre_idtxt = "status"; } 
											else if( $object["module"] == "system" )
												{ $pre_idtxt = "system"; } 
											else { $pre_idtxt = "window"; }                    
                                            $str_window .= '"' . $pre_idtxt . $object["module_id"] . '"';
                                        }
				    ?> 
					
					
					// listen for new connections; initialise them the same way we initialise the connections at startup.
					jsPlumb.bind("jsPlumbConnection", function(connInfo) { 
						init(connInfo.connection);
					});
					
					<?php
                                            $ObjInvert = array();
                                            foreach( $jsPlumbObjects as $n => $v ){
                                              $ObjInvert[ $v["module"] . $v["module_id"] ] = $n;
                                            } 
                                            
                                            $links = $this->get_module_links($global_tree_id);
                                              
                                            foreach( $links as $link ){  
                                              
						if( $link["from_module_name"] . $link["from_module_id"]  != '' && $link["to_module_name"] . $link["to_module_id"]  != '' ){?>
                                                        if ( $('#<?php echo str_replace( "task" , "window" , $link["from_module_name"] . $link["from_module_id"] ); ?>').length > 0 && $('#<?php echo str_replace( "task" , "window" , $link["to_module_name"] . $link["to_module_id"]); ?>').length > 0 ){        
							jsPlumb.connect({<?php/*
								source:targetEndpoints[<?php echo $ObjInvert[ $link["from_module_name"] . $link["from_module_id"] ]; ?>],
								target:sourceEndpoints[<?php echo $ObjInvert[ $link["to_module_name"] . $link["to_module_id"] ]; ?>]*/
                                                                
                                                        ?>
                                                                source: jsPlumb.addEndpoint( "<?php echo str_replace( "task" , "window" , $link["from_module_name"] . $link["from_module_id"] ); ?>" , targetEndpoint  ),
                                                                target: jsPlumb.addEndpoint( "<?php echo str_replace( "task" , "window" , $link["to_module_name"] . $link["to_module_id"]); ?>" ,bottomSource<?php if( $this->isDefaultPath( $link )){ echo "DP";}?> ),
                                                                
	
							}); 
                                                        }
					<?php 	} 
					}?>

				}
			};
		})();
		</script>
		
	 <?php
	 $html = ob_get_contents();
	 ob_end_clean();
	 return $html;
         }
  	  
						  
	 function returnComment($window='',$global_task_status_id=''){
	    ob_start();
		  $sql_comment = "SELECT global_task_status_name FROM tbl_global_task_status where global_task_status_id = '$global_task_status_id' and global_task_id='$window'";
		  $result_comment = $this->db->query($sql_comment);
		  $row_comment = $this->db->fetch_array($result_comment); 
		  
		  echo $row_comment[global_task_status_name];
		 $html = ob_get_contents();
		 ob_end_clean();
		 return $html;
	 } /////end of function returnComment

	 function delete_value($source_id='',$target_id=''){
	    ob_start();
	 
	      $sql_dele = "DELETE FROM ".erp_WINDOW_POSITION." WHERE source_id = '$source_id' and target_id = '$target_id'";
		  $result_dele = $this->db->query($sql_dele);
						  
	 $html = ob_get_contents();
	 ob_end_clean();
	 return $html;
	 } /////end of function delete_value
	 
	 function showInLink($source='',$target='',$comment=''){
	    ob_start(); ?>
	      <a href="javascript:void(0);" onClick="javascript:global_task.text_display('<?php echo $comment; ?>',
																			         '<?php echo $source; ?>',
																			         '<?php echo $target; ?>',
																			         'task_comment',
																		   { target: 'show_value<?php echo $source; ?>'}
																		   );"><?php echo $comment; ?></a>
																		   
	 <?php	
	 $html = ob_get_contents();
	 ob_end_clean();
	 return $html;		
	 } /////end of function showInLink
	 
	 function hidden_value($source_id='',$target_id=''){
	    ob_start();
	 
	      $insert_sql_array = array();
		  $insert_sql_array[source_id] = $source_id;
		  $insert_sql_array[target_id] = $target_id;
		  $insert_sql_array[task_comment] = 'comment here';
			
		  $this->db->insert(erp_WINDOW_POSITION,$insert_sql_array);
						  
	 $html = ob_get_contents();
	 ob_end_clean();
	 return $html;
	 }
	 
	 function show($source='',$target='',$comment=''){
	    ob_start();
		$sql_check = "SELECT * FROM tbl_global_task_status WHERE source_id = '$source' and target_id = '$target'";
		echo $sql_check;
		$result_check = $this->db->query($sql_check);
		//$row_check = $this->db->fetch_array($result_check);
		if($this->db->num_rows($result_check) > 0){
		  while($row = $this->db->fetch_array($result_check)){
		   echo $this->showInLink($row['source_id'],$row['target_id'],$row['global_task_status_name']);
		
		//$update_sql_array = array();
	    //$update_sql_array[task_comment] = $comment;
		//$this->db->update(erp_WINDOW_POSITION,$update_sql_array,"contact_id",$this->contact_id);
			}
		$sql = "update ".erp_WINDOW_POSITION." set task_comment= '$comment' where source_id = '$source' and target_id = '$target'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		} //end of if
		else{
		
		$insert_sql_array = array();
		$insert_sql_array[source_id] = $source;
		$insert_sql_array[target_id] = $target;
		$insert_sql_array[task_comment] = $comment;
		
		$this->db->insert(erp_WINDOW_POSITION,$insert_sql_array);
	    
		} //end of else
		echo $comment;
	 $html = ob_get_contents();
	 ob_end_clean();
	 return $html;		
	 } /////end of function show
	 
	 function returnLink($task_tree_id='',$window_id='',$window_val='',$div_id='',$choice=''){
	    ob_start();
		switch($choice){
		   case 'task_name':?>
			  <a href="javascript:void(0);" 
			      onclick="javascript: global_task.task_preview('<?php echo $task_tree_id; ?>','<?php echo $window_id; ?>','1',
				                                                
																  
																{ target: 'div_task_list'}
																); "><?php echo $window_val; ?></a>
																<?php //echo $window_val; ?>
																						
	   <?php
		  break;
	   }
	 $html = ob_get_contents();
	 ob_end_clean();
	 return $html;
	} /////end of function returnLink
		
	 
	 
	 function display_task($window=''){
		ob_start();
        echo $this->writeJSPlumbArea($window);?>
		
		
        
		<!-- wrapper div is needed for opera because it shows scroll bars for reason -->
        <!--<div class="wrapper">
            <span>
                <a id="in" href="#">+</a>
                <a id="out" href="#">-</a>
                <a id="fit" href="#">fit</a>
                <a id="orig" href="#">orig</a>
            </span>
            <div id="viewer" class="viewer" style="width:710px;"></div>
        </div>-->
        <script type="text/javascript">
            var $ = jQuery;
            $(document).ready(function(){
                  $("#viewer").iviewer(
                       {
                       src: "images/test_image.jpg", 
                       update_on_resize: false,
                       initCallback: function ()
                       {
                           var object = this;
                           $("#in").click(function(){ object.zoom_by(1);}); 
                           $("#out").click(function(){ object.zoom_by(-1);}); 
                           $("#fit").click(function(){ object.fit();}); 
                           $("#orig").click(function(){  object.set_zoom(100); }); 
                           $("#update").click(function(){ object.update_container_info();});
                       },
                       onMouseMove: function(object, coords) { },
                       onStartDrag: function(object, coords) { return false; }, //this image will not be dragged
                       onDrag: function(object, coords) { }
                  });
                  
            });
        </script>
        
	 <?php	 
	 $html = ob_get_contents();
	 ob_end_clean();
	 return $html; 
	 } /////end of function display_task
 /*********************************************************************************************************************************************************/
 function update_global_task_tree($tree_id , $field , $value ){
        $this->db->update('tbl_global_task_tree' , array($field => $value) , 'global_task_tree_id' , $tree_id);
     }
         function saveMGTPosition( $global_task_id , $top , $left ){
             $update = array("top"=>$top,"left"=>$left);
             //$this->db->update($table, $DataArray, $updateOnField, $updateOnFieldValue);
             $this->db->update(erp_GLOBAL_TASK , $update ,  "global_task_id" , $global_task_id  );
         }
         function saveMGTSPosition( $global_task_status_id , $top , $left ){
             $update = array("top"=>$top,"left"=>$left);
             //$this->db->update($table, $DataArray, $updateOnField, $updateOnFieldValue);
             $this->db->update(erp_GLOBAL_TASK_STATUS , $update ,  "global_task_status_id" , $global_task_status_id  );
         }
         function saveSystemTaskPosition( $system_task_id , $top , $left ){
             $update = array("top"=>$top,"left"=>$left);
             //$this->db->update($table, $DataArray, $updateOnField, $updateOnFieldValue);
             $this->db->update(TBL_TEMPLATE , $update ,  "id" , $system_task_id  );
         }
         
         //Need's to be rewritten ( way to long)
 function search_flow_chart_tasks( $search_arr=array() , $overide=array() ){
            $search_values = array();
            $search_values["task_status"] = "Active"; // Sets the default value of task_status to active 
            $options["extra_joins"] = '';
            $options["extra_columns"] = '';
            $options["return_sql"] = '';
            foreach( $search_arr as $n => $val ){
                $search_values[$n] = $val;
            }
            $options = array();
            foreach( $overide as $n => $v){
                $options[$n] = $v;
            }
            
            $sql = "SELECT a.* , b.name " . $options["extra_columns"] . " FROM `assign_flow_chart_task` a LEFT JOIN tbl_global_task b ON a.flow_chart_id = b.global_task_id " . $options["extra_joins"];
            $warr = array();
            foreach( $search_values as $column => $value ){
                if( $value != '' ){
                    switch( $column ){
                        case "chart_assign_id":
                        case "tree_id":
                        case "module":
                        case "flow_chart_id":
                        
                        case "task_status":
                        case "profile_page":
                        case "module_id":
                        case "created_date":
                        case "optional1":
                        case "due_date":
                        case "completion_date":
                        case "projected_path_due_date":
                        case "owner_module_name":
                        case "owner_module_id":
                          if( $value != '' ){
                            $warr[] = "a.$column = '$value'";  
                          }
                        break;
                        case "Owner":
                        
                            if( $value != '' && 1 == 2 ){
                                $warr[] = "a.owner_module_name = 'TBL_USER'";
                                $warr[] = "a.owner_module_id = '$value'";
                            }
                        break;    
                        case "due_date_min":
                            $warr[] = "a.due_date >= '$value 00:00:00'";
                        break;
                        case "due_date_max":
                            $warr[] = "a.due_date <= '$value 23:59:59'";
                        break;
                        case "CreatedOn_min":
                        case "created_after_date":
                            $warr[] = "a.created_date >= '$value 00:00:00'";
                        break;
                        case "CreatedOn_max":
                        case "created_befor_date":
                            $warr[] = "a.created_date <= '$value 23:59:59'";
                        break;
                        
                        case "attached_module":
                           $ep = explode(":|:" , $value);
                            if( count($ep) == 2 ){
                               $warr[] = "a.module = '" .$ep[0] . "'";
                               $warr[] = "a.module_id = '" .$ep[1] . "'";
                            }
                        break;
                        case "owner_module":
                           $ep = explode(":|:" , $value);
                            if( count($ep) == 2 ){
                               $warr[] = "a.owner_module_name = '" .$ep[0] . "'";
                               $warr[] = "a.owner_module_id = '" .$ep[1] . "'";
                            }
                        break;
                        case "status_id":
                        case "completion_result":
                         $warr[] = "a.$column LIKE '%$value%'";  
                        break;   
                        case "name":
                            $warr[] = "b.$column LIKE '%$value%'";
                        break;
                        case "global_task_id":
                        case "department_id":
                            if( $value != '' ){
                                $warr[] = "b.$column = '$value'";
                            }
                        break;
                        case "client_id":
                            //$warr[] = "c.";
                        break;
                        case "client_name":
                            //, c.display_name custom_display_name , c.email , c.account_id , c.phone_number
                            $warr[] = "c.display_name LIKE '%$value%'";
                        break;
                        case "client_email":
                            $warr[] = "c.email LIKE '%$value%'";
                        break;
                        case "client_account_id":
                            $warr[] = "c.account_id LIKE '%$value%'";
                        break;
                        case "client_phone_number":
                            $warr[] = "c.phone_number LIKE '%$value%'";
                        break;
                        case "order_id":
                            $warr[] = "d.OrderNumber = '$value'";
                        break;
                        case "contact_module_id":
                        $warr[] = "d.$column = '$value'";
                        break;
                    }
                }
            }
            if( count( $warr ) != 0 ){
                $where = " WHERE " . implode(" AND ", $warr);
            }
            $sql .= $where;
            if($options["return_sql"] != "yes" ){
                $result = $this->db->query($sql);
            // echo $sql;
                $return = array();
                while( $row=$this->db->fetch_assoc($result)){
                    //$row["sql"] = $sql;
                    $return[] = $row;
                }
                return $return;
            } else {
                return $sql;
            }
        }


} // End of class GlobalTask
?>
