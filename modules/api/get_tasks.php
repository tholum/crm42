<?php


$avalible_keys = array(
    "chart_assign_id" => "chart_assign_id" ,
    "tree_id" => "tree_id" ,
    "module" => "module" ,
    "module_name" => "module" ,
    "flow_chart_id" => "flow_chart_id" ,    
    "task_status" => "task_status" ,
    "profile_page" => "profile_page" ,
    "module_id" => "module_id" ,
    "created_date" => "created_date" ,
    "optional1" => "optional1" ,
    "due_date" => "due_date" ,
    "completion_date" => "completion_date" ,
    "projected_path_due_date" => "projected_path_due_date" ,
    "owner_module_name" => "owner_module_name" ,
    "owner_module_id" => "owner_module_id" ,
    "Owner" => "Owner" ,   
    "due_date_min" => "due_date_min" ,
    "due_date_max" => "due_date_max" ,
    "CreatedOn_min" => "CreatedOn_min" ,
    "created_after_date" => "created_after_date" ,
    "CreatedOn_max" => "CreatedOn_max" ,
    "created_befor_date" => "created_befor_date" ,
    "attached_module" => "attached_module" ,
    "owner_module" => "owner_module" ,
    "status_id" => "status_id" ,
    "completion_result" => "completion_result" , 
    "name" => "name" ,
    "global_task_id" => "global_task_id" ,
    "department_id" => "department_id" 
);

$search=array();
foreach($options as $n => $v){
    if(array_key_exists($n , $avalible_keys)){
        $search[$avalible_keys[$n]] = $v;
    }
    switch( $n ){
        case 'self':
            if( $v == 'true'){
                $search['Owner'] = $_SESSION['user_id'];
            }
        break;
    }
}



$array = array();
$global_tasks=new GlobalTask();
if( !array_key_exists('task_type',$options) || $options['task_type'] == 'global_task' ){
    $tasks = $global_tasks->search_flow_chart_tasks( $search );

    foreach($tasks as $n => $v){
        //$v['name'] = $v['subject'];
        $v['task_type'] = 'global_task';
        $v['module_name'] = $v['module'];
        $v['task_id'] = $v['chart_assign_id'];
        $v['access'] = $global_tasks->check_permitions($v);
        $v['options'] = $global_tasks->get_flowchart_options($v['flow_chart_id']);
        $array['data'][] = $v;
    }
}
?>