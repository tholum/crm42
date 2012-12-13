<?php
$order_arr = $this->db->fetch_assoc($this->db->query("SELECT * FROM erp_product_order WHERE workorder_id = '" . $module_id. "'"));
$order_id = $order_arr["order_id"];
$contact_arr = $this->db->fetch_assoc("SELECT * FROM erp_order WHERE order_id = '$order_id'");
$return = array();
$return[] = $contact_arr["vendor_contact_id"];
if( $contact_arr["contact_id"] != '' && $full == 1 ){
    $return[] = $contact_arr["contact_id"];
}
?>
