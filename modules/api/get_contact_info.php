<?php
//ini_set('display_errors',1);
$case = new cases();
$global_tasks = new GlobalTask();
$contacts = new contacts();
//$sql = $case->search_query( array('case_id' => $options['case_id'] ) );
$sql = "SELECT * FROM contacts WHERE contact_id = '" .$options['contact_id']. "'";
$array = $this->db->fetch_assoc($this->db->query($sql));
$array['tasks'] = array();
$tasks = $global_tasks->search_flow_chart_tasks( array('module'=>'contacts' , 'module_id' => $options['contact_id']) );
foreach($tasks as $n => $v){
    //$v['name'] = $v['subject'];
    $v['task_type'] = 'global_task';
    $v['task_id'] = $v['chart_assign_id'];
    $v['access'] = $global_tasks->check_permitions($v);
    $v['options'] = $global_tasks->get_flowchart_options($v['flow_chart_id']);
    $array['tasks'][] = $v;
}
$array['activity'] = $this->get_activity_log_by_module('contacts' , $options['contact_id'] );
$array['followers'] = $this->follow->get_followers_by_module('contacts' , $options['contact_id'] );
$array['phone'] = $contacts->get_contact_phone( $options['contact_id'] );
$array['email'] = $contacts->get_contact_email( $options['contact_id'] );
$array['address'] = $contacts->get_contact_address($options['contact_id']);
$array['im'] = $contacts->get_contact_im($options['contact_id']);
if( array_key_exists($_SESSION['user_id'] , $array['followers'] )){
    $array['following'] = true;
} else {
    $array['following'] = false;
}

$sql = $case->search_query( array( 'module_name' => 'contacts' , 'module_id' => $options['contact_id'] ) );
$result = $this->db->query($sql);
$array['cases'] = array();

while($row = $this->db->fetch_assoc($result)){
    $array['cases'][] = $row;
}


?>