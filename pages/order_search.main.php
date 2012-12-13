<?php 
if(array_key_exists( "order_id", $vars)){
    ob_start();
    require_once('app_code/global.config.php');

    require_once('class/config.inc.php');
    //echo __LINE__ . "\n";
    require_once('class/class.email_client.php');
    //echo __LINE__ . "\n";
    require_once('class/class.flags.php');
    //echo __LINE__ . "\n";
    require_once('class/class.GlobalTask.php');

    //echo __LINE__ . "\n";
    require_once('class/class.smtp.php');
    //echo __LINE__ . "\n";
    require_once('class/class.display.php');
    //echo __LINE__ . "\n";
    require_once('class/class.casecreation.php');
    //echo __LINE__ . "\n";
    require_once('class/class.dynamicpage.php');
    //echo __LINE__ . "\n";
    require_once('class/class.FctSearchScreen.php');

    //echo __LINE__ . "\n";
    require_once('class/class.eapi_order.php');
    require_once('class/class.cases.php');
    ob_end_clean(); 
    $eapi_order = new eapi_order;
    $cases = new cases;
}

?>
<script >
function open_order( order_id ){
        eapi_order.display_order( order_id , { target: 'order_search_result'});
    cases.case_by_order( order_id , { target: 'order_search_case_result'});
    slimcrm.last_order=order_id;
    $('.order_search_searchbox').val( order_id );
}
function order_run_on_start(){
    <?php 
if(array_key_exists( "order_id", $vars)){
  echo 'slimcrm.last_order="' . $vars["order_id"] . '"; ';  
}
?>
    if( typeof(slimcrm.last_order) != 'undefined'){
        open_order( slimcrm.last_order );
    }
}
</script>
<div class="order_search_header search_header account_padding">
    <div class="order_search_title" >Search By Order ID</div>
    <div class="order_search_searchbox_container" ><input class="order_search_searchbox" onkeydown="if(event.keyCode == 13 ){$('#order_search_button_txt').click();}" /><a href="#" onclick="open_order( $('.order_search_searchbox').val() );" id="order_search_button_txt"><button class="order_search_button">Search</button></a></div>

</div>

<div class="order_search_results account_padding" id="order_search_result"><?php 
//if(array_key_exists( "order_id", $vars)){
//    echo $eapi_order->display_order($vars["order_id"]);
//}
?></div>
<div class="order_search_case_results" style="width: 500px;" id="order_search_case_result" ><?php 
//if(array_key_exists( "order_id", $vars)){
//    echo $cases->case_by_order($vars["order_id"]);
//}
?></div>
