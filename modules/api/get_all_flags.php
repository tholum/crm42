<?php
$flags = new flags();
$array = array();
$all_flags = $flags->get_all_flags();
$flags_by_id = array();
if( array_key_exists('module_name' , $options) && array_key_exists('module_id' , $options)){
    foreach( $all_flags as $n => $v ){
        $flags_by_id[$v['flag_type_id']] = $v;
        $flags_by_id[$v['flag_type_id']]['selected'] = false;
    }
    $selected = $flags->get_flags_by_module( $options['module_name'] , $options['module_id'] );   
    foreach( $selected as $n => $v ){
        $flags_by_id[$v['flag_type_id']]['selected'] = true;
    }
}

$array['data'] = $flags_by_id;

?>