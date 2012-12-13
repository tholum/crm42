<?php

require_once('class/class.eapi_api.php');
require_once('class/class.display.php');
class eapi_account {
    var $db;
    var $eapi_api;
    var $page;
  function __construct( $page = "none"){
	  $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
          $this->eapi_api = new eapi_api;
          if( $page == "none"){
            $this->page = new basic_page();
          } else {
              $this->page = $page;
          }
  }
  
  function cache_account( $account_id ){
      $account = $this->get_account( $account_id );
      $account_info = json_decode($account);
      $my_display_name = htmlspecialchars($account_info->Studio,ENT_QUOTES); 
      if( $account_id != '' && $account_id != '0'){
          $sql = "INSERT INTO eapi_account_displayname ( `account_id` , `display_name` ) VALUES ( '$account_id' , '". $my_display_name ."' ) ON DUPLICATE KEY UPDATE display_name = '". $my_display_name ."'";
        $this->db->query($sql);
      }
      //return $account_info->Studio;
      return $my_display_name;
  }
  function search_account( $string ){
      $results = $this->eapi_api->query_account($string);
      return $results;
  }
  function get_account( $account , $overide ){
      $options['limit'] = '';
      foreach( $overide as $n => $v ){
          $options[$n] = $v;
          
      }
      return $this->eapi_api->full_account_info($account , $options );
      
  }
  
  function get_account_orders( $account ){
      $json = $this->eapi_api->full_account_info($account);
      $ac_ob = json_decode($json);
      $return = array();
      foreach( $ac_ob->Orders as $order ){
          $return[] = $order->OrderId;
      }
      return $return;
  }
  function display_account_orders( $account_info , $overide = array() ){
      $options = array();
      //$options["limit"] = 30;
      $options['pagify'] = 'yes';
      foreach( $overide as $n => $v){
          $options[$n] = $v;
      }
      ob_start();
      $array = array();
      $tmp_arr = array();
      $x = 0;
      foreach( $account_info->Orders as $order ){
          
          $tmp = array();
          $tmp["date"] = $order->ReceivedTime;
          $tmp["order"] = $order->OrderId;
          $tmp["reference"] = $order->OrderReference;
          $tmp["status"] = $order->LastStatus;
          $tmp["shipmethod"] = $order->ShippingMethod;
          $tmp["location"] = $order->ProductionLocation;
          $tmp["type"] = $order->Type;
          $tmp["case"] = "order:|:" . $order->OrderId . ":|:OrderNumber:|:" . $order->OrderId . ":|:contact_module_name:|:eapi_ACCOUNT:|:contact_module_id:|:" . $account_info->Id. ":|:module_name:|:eapi_ACCOUNT:|:module_id:|:" . $account_info->Id;
          $array[ strtotime($order->ReceivedTime) . $x] = $tmp;
          //CTLTODO: Find a better way to do this
          $tmp_arr[] = strtotime($order->ReceivedTime) . $x;
          $x++;
          
      }
      
      $y = 0;
      sort($tmp_arr);
      $final_arr = array();
      foreach( $tmp_arr as $v){
          //if( $y < $options["limit"]){
            $final_arr[] = $array[$v];
          //}
          $y++;
      }
      $options = array(
          
          "column_options" => 
          array(
              "date" => array( "name" => "Date", "dataformat" => "default_date" ),
              "order" => array( "name" => "Order Number", "dataformat" => "order_link" , "sort" => "desc" ),
              "reference" => array( "name" => "Reference" ),
              "status" => array( "name" => "Status" ),
              "shipmethod" => array( "name" => "Ship Method" ),
              "location" => array( "name" => "Location" ),
              "type" => array( "name" => "Type"),
              "case" => array( "name" => "Case", "dataformat" => "case_by_module" )
              )  
          );
      ?>
<div class="account_table account_table_orders"  >
          <?php
      echo $this->page->table_from_array($final_arr, $options , array('js_run_object' => 'ac_dash.run_all') );
      echo '</div>';
      $html=ob_get_contents();
      ob_end_clean();
      return $html;
  }
  function display_contact_info( $account ){
      $account_info = json_decode($this->get_account($account));
      ob_start();
      ?>
<hr class='account_line'>
<div class="">Contact <?php echo $account_info->Attention;?></div>
<div class="account_page_contact_main contact_main" >
    <table class="account_contact_table">
    <tr><td class="account_contact_table" >Phone:</td><td> <?php echo $account_info->Phone;?></td></tr>
    <tr><td>Email:</td><td> <?php echo $account_info->Email;?></td></tr>
<!--    <tr><td>Address:</td><td> <?php echo $account_info->Address1;?></td></tr>
    <?php if( $account_info->Address2 != '' ){?>
<tr><td></td><td><?php echo $account_info->Address2;?></td></tr>
    <?php } ?>
    <?php if( $account_info->Address3 != '' ){?>
<tr><td></td><td><?php echo $account_info->Address3;?></td></tr>
    <?php } ?>
<tr><td></td><td><?php echo $account_info->City;?>, <?php echo $account_info->State;?> <?php echo $account_info->Zipcode;?></td></tr>-->
</table> 
<hr class='account_line'>
    
</div>
    
    
    <?php
      $html=ob_get_contents();
      ob_end_clean();
      return $html;
  }
  function display_account_info($account , $limit = '75'){
      $overide =  array('limit' => $limit );
      $account_info = json_decode($this->get_account($account,$overide));
      ob_start();
//      var_dump('overide');
      ?>

        <table class="account_info">
            <tr><td>
                    <div class="account_info_column account_info_column1" >
                        <div style="font-weight: bold;">#<?php echo $account_info->Id;?> - <?php echo $account_info->Studio;?></div>
                      <?php echo $account_info->Phone;?><br/>
                      <?php echo $account_info->Email;?><br/>
                      <?php echo $account_info->DefaultShippingMethod; ?><br/>
                      <?php // var_dump( $account_info ); ?>
                          
                      <hr class='account_line'>
                      Account opened: <?php echo date( "F Y" , strtotime($account_info->ClientMoney->Created) );?><br/>
                      <hr class='account_line'>
                      YTD: $<?php echo $account_info->ClientMoney->YTD ;?> <?php $ytd = $account_info->ClientMoney->YTDChange; ?><img style="position: relative; top: 3px;" src="images/ytd_<?php if($ytd >= 0 ){ echo 'up'; } else { echo "down"; } ?>.png" /><span class="ytd_change_<?php if($ytd >= 0 ){ echo 'up'; } else { echo "down"; } ?>" ><?php if($ytd < 0 ){ echo "(";} ?> $<?php echo $ytd ;?> <?php if($ytd < 0 ){ echo ")";} ?></span> </br>
                      Last 12 Months Total: $<?php echo $account_info->ClientMoney->Last12MonthsTotal ;?><br/>
                      Last Year Total: $<?php echo $account_info->ClientMoney->LastYearTotal ;?><br/>
                      Lifetime: $<?php echo $account_info->ClientMoney->Lifetime ;?><br/>
                      <hr class='account_line'>
                      For last 30 days: <?php echo $account_info->Statistics->OrdersQtyLast30days;?> Orders, <?php echo $account_info->Statistics->RemakesQtyLast30days . " Remakes ( " . ( $account_info->Statistics->RemakesPercentLast30days  ) . "% )";?></br>
                      For last 180 days: <?php echo $account_info->Statistics->OrdersQtyLast180days;?> Orders, <?php echo $account_info->Statistics->RemakesQtyLast180days . " Remakes ( " . ( $account_info->Statistics->RemakesPercentLast180days  ) . "% )";?></br>
                    </div>
                </td><td>
                    <div class="account_info_column account_info_column2" >
                        Shipping Address:<br/>
                        Attn: <?php echo $account_info->Attention;?></br>
                        <?php echo $account_info->Address1;?></br>
                        <?php if( $account_info->Address2 != '' ){
                            echo $account_info->Address2 ."<br/>";
                        }
                        if( $account_info->Address3 != '' ){
                            echo $account_info->Address3 ."<br/>";
                        }
                        ?>
                        <?php echo $account_info->City;?>, <?php echo $account_info->State;?> <?php echo $account_info->Zipcode;?> <?php echo $account_info->Country;?><br/>
                        <hr class='account_line'>
                        Next Day Saver Available: <?php if( $account_info->DaysInTransit->isNDS == 1 ){ echo "Yes"; } else { echo "No"; }?><br/>
                        Saturday Delivery Available: <?php if( $account_info->DaysInTransit->isSaturdayDelivery == 1 ){ echo "Yes"; } else { echo "No"; };?><br/>
                        Ground Time from MN: <?php echo $account_info->DaysInTransit->GroundTimeFromMN ;?><br/>
                        Photo/Press Production Location: <?php echo $account_info->DaysInTransit->ProductionLocation ;?><br/>
                        
                    </div>
                </td></tr>
        </table>
<div class="account_padding" >
<hr class='account_line'>
<table style="width: 100%"><tr><td style="text-align: left;font-weight: bold;">Orders:<span class="order_count_text" >Showing last 75 orders</span></td><td style="text-align: right;" ><a onclick="eapi_account.display_account_info( '<?php echo $account; ?>' , '' , {target: 'account_target', onUpdate: function(response,root){$('.order_count_text').html('Showing last 150 orders');} } );" ><button>show last 150 orders</button></a></td></tr></table>


        <div class="account_orders_scroll"><?php echo $this->display_account_orders($account_info);?></div>
</div>
      <?php
      $html=ob_get_contents();
      ob_end_clean();
      return $html;
  }
}
?>
