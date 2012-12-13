<?php
$mod_arr = explode(":|:" , $original );
$module_name = $mod_arr[0];
$module_id = $mod_arr[1];
if(strtoupper($module_name) != 'CASES'){
    $result = $this->db->query("SELECT OrderNumber FROM cases WHERE module_name = '" . strtoupper($module_name) . "' AND module_id = '$module_id' ");
} else {
    $result = $this->db->query("SELECT OrderNumber FROM cases WHERE case_id = '$module_id' ");
}
$target = "javascript: casecreation.right_bottom_panel('$module_id', '' , '" . strtoupper($module_name) . "' ,{target:'right_bottom_panel'});";
$ids = array();
while( $row=$this->db->fetch_assoc($result)){
    $ids[] = $row["OrderNumber"];
}
if( $format_options['return_csv'] != true && $format_options['return_csv'] != 1 ){
    $string = implode(",", $ids);
    $string = (strlen($string) > 13) ? substr($string,0,10).'...' : $string;
    if( count($ids) == 0 ){
        //$target = "javascript:casecreation.right_bottom_panel('CREATE', '$module_name' ,'" . strtoupper($module_name) . "' , {preloader:'prl',  onUpdate:function(response,root){ document.getElementById('right_bottom_panel').innerHTML = response; autocomplete_customer_name();}});";
        //$clean = "<a href='#' onclick=\"$target\">New Case</a>";
    } else {
    $clean="<a  href='#' onclick=\"" . $this->page_link("order_search" , array('order_id' => $ids[0] ) ) . "\">$string</a>";
    }//$clean = $this->module_displayname( $module_name , $module_id );
} else {
    $clean = implode(" ", $ids);
}

?>