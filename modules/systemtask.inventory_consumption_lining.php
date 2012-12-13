<?php
$selected_option = $args[0];
$chart_assign_id = $args[1];
$module_name = $args[2];
$module_id = $args[3];
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * SELECT * FROM `erp_product_order` a JOIN erp_size b ON a.workorder_id = b.product_id JOIN erp_product c ON a.product_id = c.product_id LEFT JOIN erp_assign_inventory d ON c.product_id = d.product_id WHERE a.order_id = '162'
 */


/* This Must be run under an order */
$type_id = "1";
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
WHERE a.workorder_id = '$module_id' AND e.type_id = '$type_id'";

$result = $this->db->query($sql);
$iv = array();
$used = array();
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
    $sql = "UPDATE erp_inventory_details SET allocated = allocated - '" . $total_used . "' , 	amt_onhand = 	amt_onhand - '" . $total_used . "'WHERE inventory_id = '" . $row["inventory_id"] . "'";
    $this->db->query($sql);
}

//$return["stop"] = "yes";

?>
