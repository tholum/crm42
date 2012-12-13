<?php
//ini_set('display_errors' , 1 );
$module_name = $args[2];
$module_id = $args[3];
$payment_array =  Array();

$card = 0;
$check = 0;
$res = $this->db->query("SELECT * FROM payments WHERE  for_module_name = 'order' AND for_module_id='$module_id' AND refund='no'");
while($p=$this->db->fetch_assoc($res)){
    switch( $p["payment_module"]){
         case "creditcard":
             $card = $card + $p["amount"];
         break;
         default:
             $check = $check + $p["amount"];
         break;    
    }    
}
//$this->db->fetch_assoc($this-db->query("SELECT * FROM PAYMENTS WHERE  for_module_name = 'order' AND for_module_id='$module_id' AND refund='no' "));
$this->db->query("UPDATE payments SET refund='yes' WHERE for_module_name = 'order' AND for_module_id='$module_id' AND refund='no' ");
ob_start();
?>
<div id='payment_parrent_div' style='position: fixed;right: 0px;bottom:0px; width: 50%;height: 50%;' >
<div style='z-index: 21;position: relative;left: -200px;top: -150px;width: 400px; height: 300px;background: white;border-top-left-radius: 20px;border-top-right-radius: 20px;' >
    <div style="width: 100%;background: black;border-top-left-radius: 20px;border-top-right-radius: 20px;height: 20px;"><a style="position: relative;left: 20px;top: 5px;font-weight: bold;" href="#" onclick="$('#payment_parrent_div').remove()">Close</a></div>   
    <div style="width: 100%; height: 10px;" >&nbsp;</div>

    <div style="width: 100%;text-align: center;font-size: 12px;font-weight: bold;">Credit Card: <?php echo $card;  ?></div>
    <div style="width: 100%;text-align: center;font-size: 12px;font-weight: bold;">Write a check for: <?php echo $check; ?></div>
    
</div><div style="z-index: 20;position:fixed;width: 100%;height: 100%;top: 0px;left: 0px;background: black;opacity: .5" >&nbsp;</div></div>

<?php

$html = ob_get_contents();
ob_clean();

$return["html"] = $html;

?>
