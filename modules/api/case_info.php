<?php
//ini_set('display_errors',1);
$case = new cases();
$global_tasks = new GlobalTask();

$sql = $case->search_query( array('case_id' => $options['case_id'] ) );
$array = $this->db->fetch_assoc($this->db->query($sql));
$array['tasks'] = array();
$tasks = $global_tasks->search_flow_chart_tasks( array('module'=>'cases' , 'module_id' => $options['case_id']) );
foreach($tasks as $n => $v){
    //$v['name'] = $v['subject'];
    $v['task_type'] = 'global_task';
    $v['task_id'] = $v['chart_assign_id'];
    $v['access'] = $global_tasks->check_permitions($v);
    $v['options'] = $global_tasks->get_flowchart_options($v['flow_chart_id']);
    $array['tasks'][] = $v;
}
$array['activity'] = $this->get_activity_log_by_module('cases' , $options['case_id'] );
$array['followers'] = $this->follow->get_followers_by_module('cases' , $options['case_id'] );
if( array_key_exists($_SESSION['user_id'] , $array['followers'] )){
    $array['following'] = true;
} else {
    $array['following'] = false;
}
    $time = new TimeTracker();
    $time->setModuleName('cases');
    $time->setModuleID($options['case_id']);
    $array['time'] = array();
    $array['time']['entries'] = $time->getTimeEntries();
    $array['time']['open'] = $time->getOpenEntryId($array['time']['entries'] );
    
?>
