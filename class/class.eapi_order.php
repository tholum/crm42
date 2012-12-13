<?php
require_once("class/class.eapi_api.php");
class eapi_order {

 var $flags;
 var $db;
 var $api;
 
    function __construct($page=''){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
                $this->api = new eapi_api;
                if( $page == ''){
                    $this->page = new basic_page();
                } else {
                    $this->page = $page;
                }
    }
    function display_order( $order_id ){
        $order = $this->api->order_detail_lookup($order_id);
        //file_put_contents("orderdetails.txt", $order);
        $order_arr = json_decode($order);
        $return = '';
        $return .= "Location: " . $order_arr->Location . "</br>\n";
        foreach( $order_arr->Statuses as $stat ){
            $time = strtotime($stat->StatusTime );
            $return .= date("D n/j/Y H:i" , $time ) . " - " . $stat->Status . "</br>\n";
        }
        $return .= "<hr class='order_line'><br/>";
        $return .= "Order #" .$order_arr->Id . "</br>\n";
        foreach( $order_arr->Shipments as $ship ){
            $return .= "Shipment ID: " . $ship->Id . "</br>\n";
            $return .= "Tracking: <a target='_BLANK' href='http://wwwapps.ups.com/WebTracking/processInputRequest?sort_by=status&tracknums_displ%20ayed=1&TypeOfInquiryNumber=T&loc=en_US&InquiryNumber1=" . $ship->Tracking . "&track.x=0&track.y=0'>" . $ship->Tracking . "</a> UPS Cost: $" . number_format($ship->Cost, 2, '.', '') . "</br>\n";
        }
        $return .= "<hr class='order_line'><br/><a  onclick=\"if( $.browser.mozilla ){ $('.order_form_print').print(); } else { window.open('print_order.php?order_id=" .$order_arr->Id . "');}\" ><button>print <div class='print_button in_button'>&nbsp;</div></button></a><div class='order_form_print' >";
       // $return .= $order_arr->Account;
        $a_json = $this->api->full_account_info($order_arr->Account );
        $account = json_decode($a_json);
        $new_studio = "<a onclick=\"" . $this->page->page_link('accountdashboard' , array('account_id' => $order_arr->Account )) . "\" >" . $account->Studio . "</a>";
        
        $return .= str_replace($account->Studio , $new_studio , $order_arr->OrderForm );
        //$return .= $order_arr->OrderForm;
        $return .= '</div>';
        //file_put_contents("order_vardump.txt", print_r($order_arr , true) );
        return $return;
    }
}
?>
