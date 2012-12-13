<?php
$module_name = $args[2];
$module_id = $args[3];
$selected_option = $args[0];
$chart_assign_id = $args[1];
$return["stop"] = "";
$return["javascript"] = "";
//$continue = $options_from_function['continue'];
if( strtolower($module_name)=='cases'){
    
    $result = $this->db->query("
            SELECT *
FROM `assign_flow_chart_task`
WHERE `module` LIKE 'cases'
AND `task_status` = 'Active'
AND `module_id` = '$module_id'
AND `module` = '$module_name'
AND `chart_assign_id` <> '$chart_assign_id'");
    if( mysql_num_rows($result) != 0 ){
        ob_start();
        ?>
                slimcrm.close_case();
        <?php
        $js = ob_get_contents();
        ob_end_clean();
        $return["javascript"] = $js;
    } else {
        $update=array();
        $update['Status'] = 'Completed';
        $this->db->update('cases' , $update , 'case_id' , $module_id);
        $return['javascript'] = "$('.right_tab_right_arrow_active').click();";
    }
}

?>