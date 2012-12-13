<?php
/*
 * Use this as if it was called within a function, 
 * Absolutly no redeclaring funcitons classes ext...
 * Use $this->db for database access
 */
$module_name = $args[2];
$module_id = $args[3];
$selected_option = $args[0];
$chart_assign_id = $args[1];
$return["stop"] = "";
$return["javascript"] = "";
$contact_id = '';
$err = array();
switch( $module_name){
    case "order":
        $order_info = $this->db->fetch_assoc($this->db->query("SELECT a.* , b.first_name contact_first_name , b.last_name contact_last_name FROM erp_order a LEFT JOIN contacts b ON a.contact_id = b.contact_id WHERE order_id = '$module_id'"));
        if( is_array( $order_info)){
            if(key_exists("vendor_contact_id", $order_info)){
                $contact_id = $order_info["vendor_contact_id"];
                $address_id = $order_info["shipping_address"];
            } else {
               $err["modulenotfound"] = "The order requested does not exsist, Shipping can not continue"; 
            }
        } else {
            $err["modulenotfound"] = "The order requested does not exsist, Shipping can not continue";
        }
        
    break;
    case "work order":
    
    break;    
}

if( $address_id != ''){
    $get_address_res = $this->db->query("SELECT * FROM module_address WHERE address_id = '$address_id'");
    if( $this->db->num_rows($get_address_res) != 0){
        $address = $this->db->fetch_assoc($get_address_res);
    } else {
    $err["addressnotfound"] = "No shipping address found";        
    }
} else {
    $err["addressnotfound"] = "No shipping address found";
}
if( $contact_id != ''){
    $get_contact_res = $this->db->query("SELECT a.* , c.first_name csr_first_name , c.last_name csr_last_name FROM contacts a LEFT JOIN erp_contactscreen_custom b ON a.contact_id = b.contact_id LEFT JOIN tbl_user c ON b.csr = c.user_id WHERE a.contact_id = '$contact_id'");
    if($this->db->num_rows($get_contact_res) != 0 ){
        $to_person = $this->db->fetch_assoc($get_contact_res);
        if( $to_person["type"] == "Company"){
            $to_person["display_name"] = $to_person["company_name"];
        } else {
            $to_person["display_name"] == $to_person["first_name"] . " " . $to_person["last_name"];
        }
        if( $to_person["csr_first_name"] != '' OR $to_person["csr_last_name"] != ''){
           $from_attr = $to_person["csr_first_name"] . " " . $to_person["csr_last_name"];
        } else {
            $from_attr = UPS_DEFAULT_SHIPPER_NAME;
        }
        $phone_res = $this->db->query("SELECT * FROM contacts_phone WHERE contact_id = '" . $contact_id . "'");
        if( $this->db->num_rows($phone_res) != 0 ){
            $found = 1;
            $to_person["phone"] = UPS_DEFAULT_SHIPPER_PHONE;
            while($row=$this->db->fetch_assoc($phone_res)){
                $tmp_type = 1;
                switch( strtolower($row["type"]) ){
                    case "work":
                        $tmp_type = 5; // Prefered type
                    break;
                    case "mobile":
                        $tmp_type = 3; // Better then most, not better then work
                    break;
                    case "home":
                        $tmp_type = 2; // acceptable
                    break;
                    case "fax":
                        $tmp_type = 0; // worse then anything ( for ups anyway )
                    break;
                        
                }
                if( $tmp_type > $found ){
                    $to_person["phone"] = $row["phone"];
                    $found = $tmp_type;
                }
                
            }
            
        } else {
            $to_person["phone"] = UPS_DEFAULT_SHIPPER_PHONE;
        }
        
    } else {
        $err["contactnotfound"] = "There is no contact tied to shippment";
    }
} else {
    $err["contactnotfound"] = "There is no contact tied to shippment";
}
if( $order_info["weight"] == '0' OR $order_info["weight"] == ''){
    $err["weightblank"] = "The weight is empty";
    //$return["javascript"] .= "alert('Shipping Weight is not set');$('#shipping_weight').focus();$('#shipping_weight_div').css('background','#ff0000');";
    $return["javascript"] .= "";

    $return["stop"] = 'YES';
}
if( $order_info["contact_first_name"] != '' OR $order_info["contact_last_name"] != ''){
    $to_attr = $order_info["contact_first_name"] . " " . $order_info["contact_last_name"];
} else {
  $to_attr = $to_person["display_name"];
}
if( count($err) == 0 ){
    $ups = new ups; //$address["state"]
    $from_address = $ups->address_object(UPS_DEFAULT_FROM_STREET, UPS_DEFAULT_FROM_CITY, UPS_DEFAULT_FROM_STATE, UPS_DEFAULT_FROM_ZIP, UPS_DEFAULT_FROM_COUNTRY);
    $to_address = $ups->address_object($address["street_address"], $address["city"], $ups->convert_statename_to_abbr( $address["state"] ) , $address["zip"]);
    $from = $ups->person_object(UPS_DEFAULT_SHIPPER_NAME, $from_attr ,UPS_DEFAULT_SHIPPER_PHONE, $from_address , UPS_DEFAULT_SHIPPER_NUMBER );
    
    $to = $ups->person_object($to_person["display_name"], $to_attr ,$to_person["phone"], $to_address  );
    $xmlArr = $ups->run_shippment($from , $from, $to , $order_info["weight"] , "LBS" , $order_info["shipment_type"] , "02" ,  'Order - ' . $order_info["order_id"] , $order_info["grant_total"]  );
    //file_put_contents("request.xml", $xmlArr["sourceXML"]  );
    //file_put_contents("responce.xml", $xmlArr["originalXML"]  );
    $accept = $ups->AcceptShip($xmlArr["children"]["ShipmentDigest"][0]["value"]);
    $up_order = array();
    $shipping_label = $accept["children"]["ShipmentResults"][0]["children"]["PackageResults"][0]["children"]["LabelImage"][0]["children"]["GraphicImage"][0]["value"];
    $up_order["shipment_label"] = $shipping_label;
    $ups->set_ship();
    $ups->ship_class->add_shipment($module_name, $module_id, "Shippment for order $module_id", $xmlArr["children"]["ShipmentIdentificationNumber"][0]["value"], "UPS");
    if( $accept["children"]["ShipmentResults"][0]["children"]["ControlLogReceipt"][0]["children"]["GraphicImage"][0]["value"] != ''){
        $hvr = $accept["children"]["ShipmentResults"][0]["children"]["ControlLogReceipt"][0]["children"]["GraphicImage"][0]["value"];
        $up_order["shipment_hvr"] = $hvr;
    }
        //file_put_contents("request.xml", $xmlArr["sourceXML"]  );
    file_put_contents("responce2.xml", $accept["originalXML"]  );
    $this->db->update('erp_order', $up_order, "order_id", $order_info["order_id"]);
    $return["html"] = "
        <div id='systemtask_shipping_tmp' style='position: absolute; top: 0px; left: 50%;'>
            <div id='systemtask_shipping_tmp2'style='position: relative;right: 325px; top: 100px; height: 450px;width: 750;background: white; z-index: 10;'>" .
            '<img id=\'systemtask_shipping_tmp3\' src="data:image/gif;base64,' . $shipping_label  . '" width="651px" height="392px"/><br/>'
            
            . '
                <a href="#" onclick="window.open(\'print.php?module=shipping&order_id=' . $order_info["order_id"] . '\');$(\'#systemtask_shipping_greyout\').remove();$(\'#systemtask_shipping_tmp\').remove();$(\'#systemtask_shipping_img\').remove();" >Print Document</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="$(\'#systemtask_shipping_greyout\').remove();$(\'#systemtask_shipping_tmp\').remove();$(\'#systemtask_shipping_img\').remove();">Close This Popup</a>
</div></div><div id="systemtask_shipping_greyout" style=\'width: 100%; height: 100%; position: fixed; top: 0px; right: 0px; background: black; opacity: 0.5;\'></div><div id="systemtask_shipping_img" style="z-index: 11;position: relative;"><img width="52" height="60" src="images/UPS_LOGO_L.gif"/></div>';
            //'<img src="data:image/gif;base64,' . $shipping_label  . '" width="651px" height="392px"/></div>';
    
    
}
//$return["javascript"] = "alert('" . print_r( $err , true) . "');";

?>
