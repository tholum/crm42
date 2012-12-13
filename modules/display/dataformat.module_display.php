<?php
$mod_arr = explode(":|:" , $original );
$module_name = $mod_arr[0];
$module_id = $mod_arr[1];
$clean = $this->module_displayname( $module_name , $module_id , $format_options);
//$clean = "$module_name $module_id";
?>
