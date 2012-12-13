<?php

//include "class/class.payflowpro.php";
$return = '';

$secure = new secure();
$payflow = new payflow();
$module_id = $args["module_id"];

$payment_type = $args["payment_type"];

if( $payment_type == "creditcard"){
    $card = $secure->decrypte_creditcard($args["ccid"], $args["clockkey"] );
    if(key_exists("error", $card) == false ){
        $address = $secure->get_address_by_id($card["address_id"] );
        $name = $card["name"];
        $namearr = explode( " " , $name );
        if( count($namearr) == 1 ){

            $last = $name;
            $first = '';
        } else {
            $first = '';
            $x = 1;
            while( $x < count( $namearr) ){
                if( $first != '' ){ $first .= " "; }
                $first .= $namearr[$x-1];
                $x++;
            }
            $last = $namearr[$x-1];
        }
        
        
        $tmp1 = $this->db->fetch_assoc($this->db->query( "SELECT shipping_charges , grant_total , state_tax , county_tax , stadium_tax , total_tax FROM erp_order WHERE order_id = $module_id"));
        $full = $tmp1["grant_total"];
        $bill_amount = $full;
        $total_arr = $this->db->fetch_assoc($this->db->query("SELECT SUM( amount ) total FROM `payments` WHERE for_module_name = 'order' AND for_module_id = '$module_id' AND refund='no'"));
        $already_payed = $total_arr["total"];

        $workorder = new WorkOrder;
        $tax = $workorder->calculate_tax($module_id, '0');

        if( round( $bill_amount , 2 ) > round( $already_payed, 2) ){
            $bill_amount =  round(round( $bill_amount , 2) - round( $already_payed , 2 ) , 2 );
            $data_array = $payflow->get_data_array( $first , $last , $address["street_address"] , $address["city"] , $address["state"] , $address["zip"] , $card["cvv"] , "order_id - " . $args["module_id"] );
            $cr = $payflow->sale_transaction($card["ccnum"], $card["expiration"], $bill_amount,'USD', $data_array , $card["name"]);
            if( $cr["RESPMSG"] == 'Approved' ){

                $pa = array();
                $pa["payee_module_name"] = "contacts";
                $pa["payee_module_id"] = $args["contact_id"];
                $pa["for_module_name"] = "order";
                $pa["for_module_id"] = $module_id;
                $pa["chart_assign_id"] = $args["chart_assign_id"];
                $pa["amount"] = $bill_amount;
                $pa["curency"] = "USD";
                $pa["payment_module"] = "creditcard";
                $pa["payment_module_id"] = $args["ccid"];
                $pa["payment_type"] = "final";

                $pa["state_tax"] = $tmp1["state_tax"];
                $pa["county_tax"] = $tmp1["county_tax"];
                $pa["stadium_tax"] = $tmp1["stadium_tax"];
                $pa["total_tax"] = $tmp1["total_tax"];
                $pa["atid"] = $tax["atid"];
                $pa["shipping_amt"] = $tmp1["shipping_charges"];
                $pa["info"] = print_r($cr,true);
                $this->db->insert("payments" , $pa );
                $chart_assign_id = $args["chart_assign_id"];
                $target = $args["target"];
                $value = $args["global_task_status_id"];
                $return .= "<div style='width: 100%;background: green;' >Card Approved ( \$$bill_amount )</div>
                <script>global_task.submit_flowchartTask('$value' , '$chart_assign_id' ,'order', '$module_id', '$target' , '', '$module_id' , { target: '$target'});$('#payment_parrent_div').remove();</script>";

            } else {
                $return .= "<div style='width: 100%;background: red;' >An Error Occured<br/>" .  $payflow->errors  . "</div>";
               // $return .= "<div>" . $card["ccnum"] . '|'. $card["expiration"]. '|'. $bill_amount. '|'.'USD'. '|'. '$data_array' . '|'. $card["name"] . '|'. . "</div>";

            }
        } else {
            $return .= "<div style='width: 100%;background: green;' >No Down Payment Needed</div>
                <script>global_task.submit_flowchartTask('$value' , '$chart_assign_id' ,'order', '$module_id', '$target' , '', '$module_id' , { target: '$target'});$('#payment_parrent_div').remove();</script>";

        }


    } else {
        $return .= "<div style='width: 100%;background: red;' >" .  $card["error"] . "</div>";
    }

} else {
        $tmp1 = $this->db->fetch_assoc($this->db->query( "SELECT shipping_charges , grant_total , state_tax , county_tax , stadium_tax , total_tax FROM erp_order WHERE order_id = $module_id"));
        $full = $tmp1["grant_total"];
        $bill_amount = $full;
        $total_arr = $this->db->fetch_assoc($this->db->query("SELECT SUM( amount ) total FROM `payments` WHERE for_module_name = 'order' AND for_module_id = '$module_id' AND refund='no'"));
        $already_payed = $total_arr["total"];
        if( round( $bill_amount , 2 ) > round( $already_payed, 2) ){
            $bill_amount =  round(round( $bill_amount , 2) - round( $already_payed , 2 ) , 2 );
		$workorder = new WorkOrder;
		$tax = $workorder->calculate_tax($module_id, '0');
            $pa = array();
                $pa["payee_module_name"] = "contacts";
                $pa["payee_module_id"] = $args["contact_id"];
                $pa["for_module_name"] = "order";
                $pa["for_module_id"] = $module_id;
                $pa["chart_assign_id"] = $args["chart_assign_id"];
                $pa["amount"] = $bill_amount;
                $pa["curency"] = "USD";
                $pa["payment_module"] = $payment_type;
                $pa["payment_module_id"] = $args["ccid"];
                $pa["payment_type"] = "final";

                $pa["state_tax"] = $tmp1["state_tax"];
                $pa["county_tax"] = $tmp1["county_tax"];
                $pa["stadium_tax"] = $tmp1["stadium_tax"];
                $pa["total_tax"] = $tmp1["total_tax"];
                $pa["atid"] = $tax["atid"];
                $pa["shipping_amt"] = $tmp1["shipping_charges"];
                $pa["info"] = "";
                $this->db->insert("payments" , $pa );
                $chart_assign_id = $args["chart_assign_id"];
                $target = $args["target"];
                $value = $args["global_task_status_id"];
                $return .= "<div style='width: 100%;background: green;' >Card Approved ( \$$bill_amount )</div>
                <script>global_task.submit_flowchartTask('$value' , '$chart_assign_id' ,'order', '$module_id', '$target' , '', '$module_id' , { target: '$target'});$('#payment_parrent_div').remove();</script>";
        }
}
//$return .= print_r( $card , true ) . "<br>" . print_r($address , true) . "<br/>" . print_r($cr,true);
?>
