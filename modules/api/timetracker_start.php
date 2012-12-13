<?php 
    $array = array();
    $time = new TimeTracker();
    $time->setModuleName($options['module_name']);
    $time->setModuleID($options['module_id']);
    $checktime = $time->getOpenEntryId($time->getTimeEntries() );
    if($checktime){
        $array['time_tracker_id'] = $checktime; 
    } else {
        $id = $time->newTimeEntry($_SESSION['user_id'],$options['module_name'],$options['module_id'],date('Y-m-d H:i:s'));
        $array['time_tracker_id'] = $id;
    }
?>