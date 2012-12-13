<?php
$array = array('status'=>'error', 'data' => 'command not found');
    switch( $options['module_name'] ){
        case "cases":
            $case = new cases();
            $array = $case->set_values( $options['module_id'] , $options['type'] , $options);
        break;
    }


?>