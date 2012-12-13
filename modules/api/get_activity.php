<?php
$array = array();
if(array_key_exists('module_name',$options)){
    $array['data'] = $this->get_activity_log_by_module($options['module_name'] , $options['module_id'] );
    $array['from'] = 'activity_log';
} elseif(array_key_exists('user_id',$options)){
   $array['data'] =  $this->follow->followed_activity($options['user_id']);
   $array['from'] = 'follow';
} else {
    $array['data'] =  $this->follow->followed_activity();
    $array['from'] = 'follow';
}
?>