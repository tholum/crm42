<?php
 

$mod_arr = explode(":|:" , $original );
$module_name = $mod_arr[0];
$module_id = $mod_arr[1];

$result = $this->db->fetch_assoc($this->db->query("SELECT count( chart_assign_id) ct FROM `assign_flow_chart_task` WHERE module = '$module_name' AND module_id = '$module_id' AND task_status = 'Active'"));

$clean = $result['ct'];
?>
