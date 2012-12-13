<?php 
    $array = array();
    $time = new TimeTracker();
    $time->setModuleName($options['module_name']);
    $time->setModuleID($options['module_id']);
    $array=array( 'success' => $time->setTimeEnd($options['module_name'],$options['module_id'],date('Y-m-d H:i:s')) );

?>