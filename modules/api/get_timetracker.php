<?php 
    $array = array();
    $time = new TimeTracker();
    $time->setModuleName($options['module_name']);
    $time->setModuleID($options['module_id']);
    $array['entries'] = $time->getTimeEntries();
    $array['open'] = $time->getOpenEntryId($array['entries'] );
    $array['module_name'] = $options['module_name'];
    $array['module_id'] = $options['module_id'];
?>