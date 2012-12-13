<?php
class eapi_api {
    function run_query( $url ){
//        ob_start();
//            $curl = curl_init();
//            curl_setopt( $curl , CURLOPT_URL , "$url" );
//            curl_setopt( $curl , CURLOPT_HTTPHEADER , array("Accept: application/json") );
//            curl_exec( $curl );
//        $html = ob_get_contents();
//        ob_end_clean();
        $html = json_encode(array());
        return $this->clean_json( $html ); 
    }
    function clean_json($json){
       $json = str_replace('NaN', '0', $json );
       
       
       return $json;
    }
    function order_images( $order_num){
        $query = "http://internal.apps.eapi.com/Utils/Order/$order_num/images";
        return $this->run_query($query);
    }
    function query_account( $string , $return_string = false ){
        
        $string = str_replace('+', '%20', $string);
        $string = str_replace(" ", '%20', $string);
        //$string = urlencode($string);
        $query = 'http://internal.apps.eapi.com/Utils/AccountContactInfo/' . $string;
        if( $return_string == true){
            return $query;
        }
        
        return $this->run_query($query);
    }
    
    function full_account_info( $string , $overide ){
        $string = str_replace('+', '%20', $string);
        $string = str_replace(" ", '%20', $string);
      $options['limit'] = '';
      $options['return_query'] = false;
      foreach( $overide as $n => $v ){
          $options[$n] = $v;
          
      }
      if( $options['limit'] == '' ){
          $limit = '';
      } else {
          $limit = '/' . $options['limit'];
      }
      $query = "http://internal.apps.eapi.com/Utils/Account/$string$limit";
      if( $options['return_query'] == true ){
          return $query;
      }
        return $this->run_query($query);
    }
    function order_detail_lookup( $string ){
                $string = str_replace('+', '%20', $string);
        $string = str_replace(" ", '%20', $string);
        // Just to test
       $tmp = '{"Id":5058760,"Location":"MN","OrderForm":"<!-- ROES3378778-12-->\u000d\u000a<html><head><title>Order 5058760<\/title>\u000d\u000a<STYLE TYPE=\"text\/css\">\u000d\u000a<!--\u000d\u000a.tablestyle TD\u000d\u000a{\u000d\u000afont-family: Verdana;\u000d\u000afont-size: 8pt;\u000d\u000a}\u000d\u000a-->\u000d\u000a<\/STYLE>\u000d\u000a<\/head>\u000d\u000a<body><font face=\"Verdana\" size=-1>\u000d\u000a<!-- cancel text -->\u000d\u000a<table border=\"0\" width=\"100%\"><tr><td>\u000d\u000a<font size=+1><b>Order #5058760<\/b><\/font><br>\u000d\u000aAccount Number: <b>27919<\/b><br>\u000d\u000a<b>eapi Net Test Account<\/b><br>\u000d\u000a2840 Lone Oak Parkway<br>\u000d\u000aEagan, MN 55121<br>\u000d\u000a<br><\/td>\u000d\u000a<td align=\"right\"><font size=-1>5058760 - 9\/19<\/font> - <b>Sun<\/b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>\u000d\u000a<img src=\"http:\/\/pro.eapi.com\/Barcode.aspx?BarcodeType=EAN128A&ImageFormat=Png&BarWidth=1&Height=45&TextPosition=NotShown&AddOnTextPosition=NotShown&Data=27919@5058760$\"><br>Received on: September 17, 2010, 3:56 pm<br>\u000d\u000aShipping Method: <b>UPS-Ground<\/b><br>\u000d\u000aReference: \u000d\u000a<\/td><\/tr><\/table><br><hr><!-- start dropship --><!-- end dropship --><b>ROES Order<\/b><br><center>Paper: Lustre<\/center><hr>Order Notes: <b>test order, please void<\/b><hr><b>Print Totals by Size:<\/b><table border=0 class=\"tablestyle\" cellpadding=1><tr><td align=right>8x10:<\/td><td><b>1<\/b><\/td><\/tr><\/table><br><b>Prints\/Finishing Services:<\/b><br><table border=1 cellpadding=2 class=\"tablestyle\"><tr><td><b>apc box 2.jpg<\/b><\/td><\/tr>\u000d\u000a<tr><td align=right>8x10<\/td><td>1<\/td>\u000d\u000a<\/table>Order successfully entered into billing system.\u000d\u000a<!-- <?xml version=\"1.0\" encoding=\"UTF-8\"?>\u000d\u000a<eapiOrder>\u000d\u000a\u0009<fileversion>1.0<\/fileversion>\u000d\u000a\u0009<labworksimport>true<\/labworksimport>\u000d\u000a\u0009<returndata>\u000d\u000a\u0009\u0009<data>true<\/data>\u000d\u000a\u0009\u0009<xml>true<\/xml>\u000d\u000a\u0009<\/returndata>\u000d\u000a\u0009<orderid>5058760<\/orderid>\u000d\u000a\u0009<account>27919<\/account>\u000d\u000a\u0009<orderedby>ROES<\/orderedby>\u000d\u000a\u0009<reference><\/reference>\u000d\u000a\u0009<ordertype>1<\/ordertype>\u000d\u000a\u0009<productiondays>0<\/productiondays>\u000d\u000a\u0009<productionlocation>1<\/productionlocation>\u000d\u000a\u0009<shippingcharge><\/shippingcharge>\u000d\u000a\u0009<ordersurcharge><\/ordersurcharge>\u000d\u000a\u0009<dropshipaddress><\/dropshipaddress>\u000d\u000a\u0009<minimumorder>12<\/minimumorder>\u000d\u000a\u0009<billingitems>\u000d\u000a\u0009\u0009\u0009<item>\u000d\u000a\u0009\u0009\u0009\u0009<code>810810<\/code>\u000d\u000a\u0009\u0009\u0009\u0009<quantity>1<\/quantity>\u000d\u000a\u0009\u0009\u0009<\/item>\u000d\u000a\u0009<\/billingitems>\u000d\u000a<\/eapiOrder> \u000d\u000a <?xml version=\"1.0\" encoding=\"utf-8\"?>\u000d\u000a<eapiReturnXML><fileversion>1.0<\/fileversion><returntype>GP Import<\/returntype><validimport>True<\/validimport><orderid>5058760<\/orderid><ordertotal>12.85<\/ordertotal><salestaxtotal>0.85<\/salestaxtotal><duedate>2010-09-19<\/duedate><OrderItems><Item><code>810810<\/code><description>Photo Print 8x10<\/description><quantity>1<\/quantity><each>1<\/each><price>2.20<\/price><\/Item><Item><code>9999<\/code><description>Minimum Order Charge<\/description><quantity>1<\/quantity><each>1<\/each><price>9.80<\/price><\/Item><\/OrderItems><\/eapiReturnXML> \u000d\u000a LW time: 1.35554289818 GP time: 1.16791415215 \u000d\u000a <?xml version=\"1.0\" encoding=\"UTF-8\"?>\u000a\u0009\u0009<eapiReturnXML>\u000a\u0009\u0009\u0009<fileversion>1.0<\/fileversion>\u000a\u0009\u0009\u0009<returntype>Labworks Import<\/returntype>\u000a\u0009\u0009\u0009<validimport>True<\/validimport>\u000a\u0009\u0009\u0009<orderid>5058760<\/orderid>\u000a\u0009\u0009\u0009<labworksid>4608547<\/labworksid>\u000a\u0009\u0009\u0009<ordertotal>12.86<\/ordertotal>\u000a\u0009\u0009\u0009<salestaxtotal>.855<\/salestaxtotal>\u000a\u0009\u0009\u0009<duedate>2010-09-19<\/duedate>\u000a\u0009\u0009\u0009<OrderItems>\u000a\u0009\u0009\u0009\u0009<Item>\u000a\u0009\u0009\u0009\u0009\u0009<code>810810<\/code>\u000a\u0009\u0009\u0009\u0009\u0009<description>Photo Print 8x10<\/description>\u000a\u0009\u0009\u0009\u0009\u0009<quantity>1<\/quantity>\u000a\u0009\u0009\u0009\u0009\u0009<each>1<\/each>\u000a\u0009\u0009\u0009\u0009\u0009<price>2.2<\/price>\u000a\u0009\u0009\u0009\u0009<\/Item>\u000a\u0009\u0009\u0009\u0009<Item>\u000a\u0009\u0009\u0009\u0009\u0009<code>9999<\/code>\u000a\u0009\u0009\u0009\u0009\u0009<description>Minimum Order Charge<\/description>\u000a\u0009\u0009\u0009\u0009\u0009<quantity>1<\/quantity>\u000a\u0009\u0009\u0009\u0009\u0009<each>1<\/each>\u000a\u0009\u0009\u0009\u0009\u0009<price>9.8<\/price>\u000a\u0009\u0009\u0009\u0009<\/Item>\u000a\u0009\u0009\u0009<\/OrderItems>\u000a\u0009\u0009<\/eapiReturnXML> -->\u000d\u000a<\/body><\/html>","ShipInfo":null,"Statuses":[{"Status":"Order Form Printed","StatusTime":"9\/17\/2010 3:56:56 PM"},{"Status":"Order Voided","StatusTime":"9\/17\/2010 4:48:44 PM"}]}';
       //return $tmp; 
       return $this->run_query("http://internal.apps.eapi.com/Utils/order/$string");
    }
    
}
?>
