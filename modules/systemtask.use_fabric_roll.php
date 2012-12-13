<?php
$selected_option = $args[0];
$chart_assign_id = $args[1];
$module_name = $args[2];
$module_id = $args[3];
$type_id = "5"; //Which is fabric, the only thing that gets printed on at this time
switch ($module_name) {
    case 'order':
         $column = 'order_id';

    break;
    case 'work order':
        $column = 'workorder_id';
        break;
}

$sql = "SELECT b.* , d.* FROM `erp_product_order` a 
JOIN erp_size b ON ( a.workorder_id = b.product_id AND a.gp_id = '0' ) OR ( a.gp_id = b.product_id AND a.gp_id <> '0' ) 
JOIN erp_product c ON a.product_id = c.product_id 
LEFT JOIN erp_assign_inventory d ON c.product_id = d.product_id 
JOIN erp_inventory_details e ON d.inventory_id = e.inventory_id
WHERE a.$column = '$module_id' AND e.type_id = '$type_id'";


$result1 = $this->db->query("SELECT a.module , a.module_id , b.type , b.printer , b.fabric_rolles FROM `assign_flow_chart_task` a
JOIN erp_create_group b ON a.chart_assign_id = b.assign_fct_id
WHERE a.module='work order' AND a.module_id='$module_id'");
$info = $this->db->fetch_assoc( $result1);
$result = $this->db->query($sql);
$iv = array();
$used = array();
if( $info != FALSE ){
    while( $row = $this->db->fetch_assoc($result)){
        $sz = explode("_" , $row["size"] );
        if( count($sz) == 2 ){
            $deduct_table = strtolower($sz[1]) . "_inventory_usage";
        } else {
            $deduct_table = '';
        }
         if( $deduct_table != ''){
             $total_used = $row[$deduct_table] * $row["quantity"] ;
         } else {
             $total_used = '0';
         } 
         $row["total_used"] = $total_used;
        $iv[] = $row;
        $sql = "UPDATE erp_fabric_rolls SET inches = inches - '" . $total_used . "' WHERE id = '" . $info["fabric_rolles"] . "'";
        $this->db->query($sql);
    }
}


$return = array(); // The only way to retern varables
$return["stop"] = ""; // SET to anything to not allow the flowchart task to complete, USE SPARINGLY
$return["javascript"] = "";// SET to anything to exictue code on submition, for example 'alert('Hello");'
$return["html"] = "";
?>
