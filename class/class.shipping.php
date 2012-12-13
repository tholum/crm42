<?php
class shipping {
        var $db;
        function __construct() {
            $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
        }
        function add_shipment($module_name , $module_id , $desc , $tracking , $shipping_module ){
           $s = array();
           $s["module_name"] = $module_name;
           $s["module_id"] = $module_id;
           $s["description"] = $desc;
           $s["tracking_number"] = $tracking;
           $s["shipping_module"] = $shipping_module;
           $this->db->insert("shipping_tracking", $s);
           return $this->db->last_insert_id();
        }
        function get_shipments_by_module( $module_name , $module_id ){
            $result = $this->db->query("SELECT * FROM shipping_tracking WHERE module_name='$module_name' AND module_id='$module_id'");
            $return = array();
            while($row=$this->db->fetch_assoc($result)){
                $return[] = $row;
            }
            return $return;
        }

}
?>
