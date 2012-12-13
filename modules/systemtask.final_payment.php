<?php
$secure = new secure();
$payflow = new payflow();
$module_name = $args[2];
$module_id = $args[3];
$selected_option = $args[0];
$chart_assign_id = $args[1];

$result = $this->db->query("SELECT * FROM payments WHERE chart_assign_id = '$chart_assign_id'");
    $tmp1 = $this->db->fetch_assoc($this->db->query( "SELECT grant_total FROM erp_order WHERE order_id = $module_id"));
    $full_amount = $tmp1["grant_total"];
    $bill_amount = $full_amount;
    $total_arr = $this->db->fetch_assoc($this->db->query("SELECT SUM( amount ) total FROM `payments` WHERE for_module_name = 'order' AND for_module_id = '$module_id' AND refund='no'"));
    $already_payed = $total_arr["total"];

   // $return["javascript"] = "alert('ba: $bill_amount|ap: $already_payed');";
//$return["javascript"] = "alert('" . $this->db->num_rows($result) . "| $already_payed | $bill_amount" . "');";
if( $this->db->num_rows($result) == 0 && $already_payed < $bill_amount ){
    $return["stop"] = "YES";
    $bill = $bill_amount - $already_payed;




$contacts = $this->get_contact_by_module($module_id, $module_name);
$credit_cards = $secure->get_creditcards( "contacts", $contacts[0]);
$tmp1 = $this->db->fetch_assoc($this->db->query( "SELECT ccid FROM erp_order WHERE order_id = $module_id"));
$ccid = $tmp1["ccid"];
if(array_key_exists($ccid, $credit_cards) == false && $ccid != '0'){
    $cc2 = $secure->get_creditcard($ccid);
    $credit_cards[$ccid] = $cc2[$ccid];
}
$ccoptions = '<option value="">--SELECT ONE--</option>';
foreach( $credit_cards as $num => $card ){
    if( $num = $ccid ){
        $select = " SELECTED ";
    } else {
        $select = '';
    }
    $ccoptions .= "<option $select value='" . $card["ccid"] . "' >" .  $card["name"] . " - " . $card["type"]  . " " . $card["expiration"]. "</option>";
}
ob_start();
?>
<div id='payment_parrent_div' style='position: fixed;right: 0px;bottom:0px; width: 50%;height: 50%;' >
<div style='z-index: 21;position: relative;left: -200px;top: -150px;width: 400px; height: 300px;background: white;border-top-left-radius: 20px;border-top-right-radius: 20px;' >
    <div style="width: 100%;background: black;border-top-left-radius: 20px;border-top-right-radius: 20px;height: 20px;"><a style="position: relative;left: 20px;top: 5px;font-weight: bold;" href="#" onclick="$('#payment_parrent_div').remove()">Close</a></div>   
    <div style="width: 100%; height: 10px;" >&nbsp;</div>
    
    <div style="width: 100%;text-align: center;" ><button class="payment_button" style="background: white;" onclick="$('.payment_button').css('background' , 'white');$(this).css('background' , 'blue');$('.payment_creditcard').show();$('.payment_all').show();systask_payment_type='creditcard'"  style="background: white;" >Credit Card</button><button class="payment_button"  style="background: white;"  onclick="$('.payment_button').css('background' , 'white');$(this).css('background' , 'blue');$('.payment_creditcard').hide();$('.payment_all').show();systask_payment_type='check'">Check</button><button class="payment_button"  style="background: white;"  onclick="$('.payment_button').css('background' , 'white');$(this).css('background' , 'blue');$('.payment_creditcard').hide();$('.payment_all').show();systask_payment_type='wiretransfer'">Wire Transfer</button></div>
    <div class="payment_creditcard" style="width: 100%;text-align: center;font-size: 12px;font-weight: bold;display: none;">Please Enter The ClockKey</div>
    <div class="payment_creditcard" style="width: 100%;font-size: 12px;text-align: center;font-weight: bold;display: none;">Clock Key: <input id="payment_clockkey" style="width: 75px;"></div>
    <div class="payment_creditcard" style="width: 100%;font-size: 12px;text-align: center;font-weight: bold;display: none;">Credit Card: <select id="payment_creditcard" style="width: 75px;"><?php echo $ccoptions; ?></select></div>
    <div class="payment_all" style="width: 100%;font-size: 12px;text-align: center;font-weight: bold;display: none;">Amount Billed: $<?php echo number_format($bill, 2);?></div>        
    <div class="payment_all" style="width: 100%;text-align: center;font-size: 12px;font-weight: bold;display: none;"><button style="width: 75px;" onclick="global_task.livex_call( 'final_payment_post', { clockkey: $('#payment_clockkey').val() , module_name: '<?php echo $module_name;?>' , module_id: '<?php echo $module_id; ?>' , chart_assign_id: '<?php echo $chart_assign_id; ?>', selected_option: '<?php echo $selected_option; ?>' , ccid: $('#payment_creditcard').val() , contact_id: '<?php echo $contacts[0];?>' , target: '<?php echo $args[4] ?>' ,  global_task_status_id: '<?php echo $selected_option; ?>' , payment_type : systask_payment_type } , {target: 'payment_result'})">Submit</button></div>
    <div style="width: 100%;text-align: center;font-size: 12px;font-weight: bold;" id="payment_result"></div>
</div><div style="z-index: 20;position:fixed;width: 100%;height: 100%;top: 0px;left: 0px;background: black;opacity: .5" >&nbsp;</div></div>

<?php
}
$html = ob_get_contents();
ob_clean();
$return["html"] = $html;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
