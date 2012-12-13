<?php
// Remove This and add it to the global config
require_once( 'class/class.shipping.php');

class ups {
    var $freight_url = "/webservice/FreightPickup";
    var $ship_request = "/ups.app/xml/ShipConfirm";
    var $ship_accept = "/ups.app/xml/ShipAccept";
    var $void_ship = '/ups.app/xml/Void';
    
    var $ship_from; //Person Object
    var $ship_to; //Person Object
    var $payer; //Person Object
    var $db;
    var $ship_class;
    function __construct() {
        
    }
    
    function convert_statename_to_abbr( $name ){
        
        	$state = array();
		$state['ALABAMA']='AL';
		$state['ALASKA']='AK';
		$state['AMERICAN SAMOA']='AS';
		$state['ARIZONA']='AZ';
		$state['ARKANSAS']='AR';
		$state['CALIFORNIA']='CA';
		$state['COLORADO']='CO';
		$state['CONNECTICUT']='CT';
		$state['DELAWARE']='DE';
		$state['DISTRICT OF COLUMBIA']='DC';
		$state['FEDERATED STATES OF MICRONESIA']='FM';
		$state['FLORIDA']='FL';
		$state['GEORGIA']='GA';
		$state['GUAM']='GU';
		$state['HAWAII']='HI';
		$state['IDAHO']='ID';
		$state['ILLINOIS']='IL';
		$state['INDIANA']='IN';
		$state['IOWA']='IA';
		$state['KANSAS']='KS';
		$state['KENTUCKY']='KY';
		$state['LOUISIANA']='LA';
		$state['MAINE']='ME';
		$state['MARSHALL ISLANDS']='MH';
		$state['MARYLAND']='MD';
		$state['MASSACHUSETTS']='MA';
		$state['MICHIGAN']='MI';
		$state['MINNESOTA']='MN';
		$state['MISSISSIPPI']='MS';
		$state['MISSOURI']='MO';
		$state['MONTANA']='MT';
		$state['NEBRASKA']='NE';
		$state['NEVADA']='NV';
		$state['NEW HAMPSHIRE']='NH';
		$state['NEW JERSEY']='NJ';
		$state['NEW MEXICO']='NM';
		$state['NEW YORK']='NY';
		$state['NORTH CAROLINA']='NC';
		$state['NORTH DAKOTA']='ND';
		$state['NORTHERN MARIANA ISLANDS']='MP';
		$state['OHIO']='OH';
		$state['OKLAHOMA']='OK';
		$state['OREGON']='OR';
		$state['PALAU']='PW';
		$state['PENNSYLVANIA']='PA';
		$state['PUERTO RICO']='PR';
		$state['RHODE ISLAND']='RI';
		$state['SOUTH CAROLINA']='SC';
		$state['SOUTH DAKOTA']='SD';
		$state['TENNESSEE']='TN';
		$state['TEXAS']='TX';
		$state['UTAH']='UT';
		$state['VERMONT']='VT';
		$state['VIRGIN ISLANDS']='VI';
		$state['VIRGINIA']='VA';
		$state['WASHINGTON']='WA';
		$state['WEST VIRGINIA']='WV';
		$state['WISCONSIN']='WI';
		$state['WYOMING']='WY';
                if(strlen($name) != 2 ){
                    return $state[strtoupper($name)];
                }  else {
                    return strtoupper($name);
                }
    }
    /*
     * The address should be an address_object
     */
    
    function gen_AuthXml(){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><AccessRequest><AccessLicenseNumber>' . UPS_API_KEY . '</AccessLicenseNumber><UserId>' . UPS_USERNAME . '</UserId><Password>' . UPS_PASSWORD . '</Password></AccessRequest>';
        return $xml;
    }
    
    function gen_ShipAccXml( $digest ){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<ShipmentAcceptRequest>
		<Request>
		<RequestOption>01</RequestOption>
		<TransactionReference>
		<CustomerContext>JAXB Test Client</CustomerContext>
		</TransactionReference>
	</Request>
	<ShipmentDigest>' . $digest . '</ShipmentDigest>
</ShipmentAcceptRequest>';
    return $xml;
    }
    function gen_VoidReqXML($upsTrackingNumber , $comment=''){
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<VoidShipmentRequest>
    <Request>
        <TransactionReference>
            <CustomerContext>' . $comment . '</CustomerContext>
            <XpciVersion>1.0</XpciVersion>
        </TransactionReference>
        <RequestAction>1</RequestAction>
        <RequestOption>1</RequestOption>
    </Request>
    <ShipmentIdentificationNumber>' . $upsTrackingNumber . '</ShipmentIdentificationNumber>
</VoidShipmentRequest>';
        return $xml;
    }
    
    function gen_ShipReqXml( $shipper='' , $shipfrom='' , $shipto='' , $weight , $weight_unit = "LBS" , $service = "03" , $package_type = "02" , $product_description = "An Item" , $insuredvalue = '' ){
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<ShipmentConfirmRequest>
<Request><RequestOption>nonvalidate</RequestOption>
	<TransactionReference>
		<CustomerContext>SlimCRM Shipment</CustomerContext>
	</TransactionReference>
</Request>
<Shipment>
    <Description>' . $product_description . '</Description>
    <Shipper>
            <Name>' . $shipper["name"] . '</Name>
            <AttentionName>' . $shipper["attention"] . '</AttentionName>
            <ShipperNumber>' . $shipper["shippernumber"] . '</ShipperNumber>
            <PhoneNumber>' . $shipper["phone"] . '</PhoneNumber>
            <Address>
                    <AddressLine1>' . $shipper["address"]["street"] . '</AddressLine1>
                    <City>' . $shipper["address"]["city"] . '</City>
                    <StateProvinceCode>' . $shipper["address"]["state"] . '</StateProvinceCode>
                    <PostalCode>' . $shipper["address"]["zip"] . '</PostalCode>
                    <CountryCode>' . $shipper["address"]["country"] . '</CountryCode>
            </Address>
    </Shipper>
    <ShipTo>
            <CompanyName>' . $shipto["name"] . '</CompanyName>
            <AttentionName>' . $shipto["attention"] . '</AttentionName>
            <PhoneNumber>' . $shipto["phone"] . '</PhoneNumber>
            <Address>
                    <AddressLine1>' . $shipto["address"]["street"] . '</AddressLine1>
                    <City>' . $shipto["address"]["city"] . '</City>
                    <StateProvinceCode>' . $shipto["address"]["state"] . '</StateProvinceCode>
                    <PostalCode>' . $shipto["address"]["zip"] . '</PostalCode>
                    <CountryCode>' . $shipto["address"]["country"] . '</CountryCode>
            </Address>
    </ShipTo>
    <ShipFrom>
            <CompanyName>' . $shipfrom["name"] . '</CompanyName>
            <AttentionName>' . $shipfrom["attention"] . '</AttentionName>
            <Address>
                    <AddressLine1>' . $shipfrom["address"]["street"] . '</AddressLine1>
                    <City>' . $shipfrom["address"]["city"] . '</City>
                    <StateProvinceCode>' . $shipfrom["address"]["state"] . '</StateProvinceCode>
                    <PostalCode>' . $shipfrom["address"]["zip"] . '</PostalCode>
                    <CountryCode>' . $shipfrom["address"]["country"] . '</CountryCode>
            </Address>
    </ShipFrom>
    <PaymentInformation>
        <Prepaid>
            <BillShipper>
                <AccountNumber>' . $shipper["shippernumber"] . '</AccountNumber>
            </BillShipper>
        </Prepaid>
    </PaymentInformation>
    
    <Service>
        <Code>' . $service . '</Code>
    </Service>
    <Package>
        <PackagingType>
            <Code>' . $package_type . '</Code>
        </PackagingType>
        <PackageWeight>
            <UnitOfMeasurement>
                <Code>' . $weight_unit . '</Code>
                <Description>Pounds</Description>
            </UnitOfMeasurement>
            <Weight>' . $weight  . '</Weight>
        </PackageWeight>
        <PackageServiceOptions>
        ';
        if( $insuredvalue != '' ){
                $xml .= '<InsuredValue>
                        <Type>
                                <Code>01</Code>
                        </Type>
                        <CurrencyCode>USD</CurrencyCode>
                        <MonetaryValue>' . $insuredvalue . '</MonetaryValue>
                </InsuredValue>';
        }
    $xml .= '
        </PackageServiceOptions></Package>
</Shipment>
<LabelSpecification>
    <LabelPrintMethod>
        <Code>GIF</Code>
        <Description>gif file</Description>
    </LabelPrintMethod>
    <HTTPUserAgent>Mozilla/4.5</HTTPUserAgent>
    <LabelImageFormat>
        <Code>GIF</Code>
        <Description>GIF</Description>
    </LabelImageFormat>
</LabelSpecification>
</ShipmentConfirmRequest>
';
   return $xml;     
    }
    
    /* Returns a nested array of types, code is the numeric value that gets passed to ups, name is the text description of the value  02 should be default */
    function getPackageTypes(){
        $return = array();
        //$return[] = array( "code" => "01" , "name" => "UPS Letter"  );
        $return[] = array( "code" => "02" , "name" => "Customer Supplied Package" , "default" => "yes" );
        //$return[] = array( "code" => "03" , "name" => "Tube"  );
        //$return[] = array( "code" => "04" , "name" => "PAK"  );
        //$return[] = array( "code" => "21" , "name" => "UPS Express Box"  );
        //$return[] = array( "code" => "24" , "name" => "UPS 25KG Box"  );
        //$return[] = array( "code" => "25" , "name" => "UPS 10KG Box"  );
        //$return[] = array( "code" => "30" , "name" => "Pallet"  );
        //$return[] = array( "code" => "2a" , "name" => "Small Express Box"  );
        //$return[] = array( "code" => "2b" , "name" => "Medium Express Box"  );
        //$return[] = array( "code" => "2c" , "name" => "Large Express Box"  );
        return $return;
    }
    /* Returns a nested array of types, code is the numeric value that gets passed to ups, name is the text description of the value  11 should be default */
    function getShippmentTypes(){
        $return = array();
        $return[] = array( "code" => "01" , "name" => "Next Day Air"  );
        $return[] = array( "code" => "02" , "name" => "2nd Day Air"  );
        $return[] = array( "code" => "03" , "name" => "Ground" , "default" => "yes" );
        //$return[] = array( "code" => "07" , "name" => "Express"  );
        //$return[] = array( "code" => "08" , "name" => "Expedited"  );
        //$return[] = array( "code" => "11" , "name" => "UPS Standard"  );
        //$return[] = array( "code" => "12" , "name" => "3 Day Select"  );
        return $return;
        
        
    }
    
    function run_shippment($shiper , $shipfrom , $shipto  , $weight , $weight_unit = "LBS" , $service = "03" , $package_type = "02" , $product_description = "An Item" , $insuredvalue = '' ){
       $xml = $this->gen_AuthXml() .  $this->gen_ShipReqXml( $shiper , $shipfrom , $shipto  , $weight , $weight_unit  , $service  , $package_type , $product_description , $insuredvalue   );
       return $this->run_xml($xml, $this->ship_request);
    }
    function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    function base64url_decode($data) {
      return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
    
    function xml2array($xml) {
        $arXML=array();
        $arXML['name']=trim($xml->getName());
        $arXML['value']=trim((string)$xml);
        $t=array();
        foreach($xml->attributes() as $name => $value){
            $t[$name]=trim($value);
        }
        $arXML['attr']=$t;
        $t=array();
        foreach($xml->children() as $name => $xmlchild) {
            $t[$name][]=$this->xml2array($xmlchild); //FIX : For multivalued node
        }
        $arXML['children']=$t;
        return($arXML);
    }
    
    function AcceptShip( $digest ){
        $xml = $this->gen_AuthXml() . $this->gen_ShipAccXml($digest);
        return $this->run_xml($xml, $this->ship_accept );
    }
    
    function VoidShippment( $tracker , $comment=''){
        $xml= $this->gen_AuthXml() . $this->gen_VoidReqXML($tracker, $comment);
        return $this->run_xml($xml, $this->void_ship );
    }
    
    function run_xml( $xml , $page ){
        ob_start();
        $ch = curl_init();
        curl_setopt($ch ,  CURLOPT_URL, UPS_API_SERVER .  $page );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml");
        $output_NU = curl_exec($ch);
        curl_close($ch);
        $output = ob_get_contents();
        ob_end_clean();
        //echo $output;
        $xmlDoc = simplexml_load_string($output);
        $xmlArr = $this->xml2array($xmlDoc);
        $xmlArr["originalXML"] = $output;
        $xmlArr["sourceXML"] = $xml;
        file_put_contents("/var/www/tholum/ups2/xml/sent-" . $page. date("H-i-s") . "_" . rand(1 , 100)  . ".xml", $xml);
        file_put_contents("/var/www/tholum/ups2/xml/receved-" . $page. date("H-i-s") . "_" . rand(1 , 100)  . ".xml", $output);
        return $xmlArr;
    }
    
    /* State must be in 2 letter caps formate */
    function address_object( $street , $city , $state ,  $zip , $country = "US" ){
        $return = array();
        $return["street"] = $street;
        $return["city"] = $city;
        $return["state"] = $state;
        $return["zip"] = $zip;
        $return["country"] = $country;
        return $return;
        
    }
    
    /* Address is an address object*/
    function person_object( $name , $attention , $phone , $address  , $shippernumber=''  , $email='' ){
        $return=array();
        $return["name"] = $name;
        $return["attention"] = $attention;
        $return["shippernumber"] = $shippernumber;
        $return["phone"] = $phone;
        $return["address"] = $address;
        return $return;
        
    }
    function set_db(){
        $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
    }
    function set_ship(){
        $this->ship_class = new shipping();
    }
    function estamate_shipping_by_module( $module_name , $module_id , $shipment_type="03"){
        $debug = "no";
        if( $debug == "yes" ){ echo __LINE__ . "<br>\n";}
                       //echo __LINE__;     
        $this->set_db(); // Database access is required for this module
        if( $debug == "yes" ){ echo __LINE__ . "<br>\n";}       
        switch( $module_name){
            case "order":
            if( $debug == "yes" ){ echo __LINE__ . "<br>\n";}//           echo __LINE__;
                $sql = "SELECT a.* , b.first_name contact_first_name , b.last_name contact_last_name FROM erp_order a LEFT JOIN contacts b ON a.contact_id = b.contact_id WHERE order_id = '$module_id'";
               // echo $sql;
                                
                $order_info = $this->db->fetch_assoc($this->db->query($sql));
                        if( $debug == "yes" ){ echo __LINE__ . "<br>\n";}
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

                        if( $debug == "yes" ){ echo __LINE__ . "<br>\n";}
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
                            if( $debug == "yes" ){ echo __LINE__ . str_replace( "\n" , "<br>\n" . __LINE__ . ":" , print_r($err,true) ) . "<br>\n";}
                    if( $contact_id != ''){
                                if( $debug == "yes" ){ echo __LINE__ . "<br>\n";}
                        $sql = "SELECT a.* , c.first_name csr_first_name , c.last_name csr_last_name FROM contacts a LEFT JOIN erp_contactscreen_custom b ON a.contact_id = b.contact_id LEFT JOIN tbl_user c ON b.csr = c.user_id WHERE a.contact_id = '$contact_id'";
                        //echo $sql;
                        if( $debug == "yes" ){ echo __LINE__ . ":$sql<br>\n";}
                        //return $sql;
                        $get_contact_res = $this->db->query($sql);
                        if($this->db->num_rows($get_contact_res) != 0 ){
                            $to_person = $this->db->fetch_assoc($get_contact_res);
                            if( $debug == "yes" ){ echo __LINE__ . str_replace( "\n" , "<br>\n" . __LINE__ . ":" , print_r($to_person,true) ) . "<br>\n";}
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
                                    if( $debug == "yes" ){ echo __LINE__ . ":tmp_type = $tmp_type<br>\n" ;}
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
                                        if( $debug == "yes" ){ echo __LINE__ . ":tmp_type = $tmp_type  > found = $found<br>\n" ;}
                                        $to_person["phone"] = $row["number"];
                                         if( $debug == "yes" ){ echo __LINE__ . str_replace( "\n" , "<br>\n" . __LINE__ . ":" , print_r($row,true) ) . "<br>\n";}
                                        $found = $tmp_type;
                                    }

                                }

                            } else {
                                $to_person["phone"] = UPS_DEFAULT_SHIPPER_PHONE;
                            }
                            if( $debug == "yes" ){ echo __LINE__  . ":found = $found\n<br/>" . __LINE__ . str_replace( "\n" , "<br>\n" . __LINE__ . ":" , print_r($to_person,true) ) . "<br>\n";}
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
                 //print_r($from_attr);
				 // echo 'sadad'.count($err).'saa';
                    if( $debug == "yes" ){ echo __LINE__ . str_replace( "\n" , "<br>\n" . __LINE__ . ":" , print_r($err,true) ) . "<br>\n";}
                    if( count($err) == 0 ){
						//echo 'asa';
                        $ups = new ups;
                        $from_address = $ups->address_object(UPS_DEFAULT_FROM_STREET, UPS_DEFAULT_FROM_CITY, UPS_DEFAULT_FROM_STATE, UPS_DEFAULT_FROM_ZIP, UPS_DEFAULT_FROM_COUNTRY);
						//print_r($from_address);
                        $to_address = $ups->address_object($address["street_address"], $address["city"], $ups->convert_statename_to_abbr( $address["state"] ), $address["zip"]);
						//print_r($to_address);
                        $from = $ups->person_object(UPS_DEFAULT_SHIPPER_NAME, $from_attr ,UPS_DEFAULT_SHIPPER_PHONE, $from_address , UPS_DEFAULT_SHIPPER_NUMBER );

						//print_r($from);
                        if( $debug == "yes" ){ echo __LINE__ . str_replace( "\n" , "<br>\n" . __LINE__ . ":" , print_r($to_address,true) ) . "<br>\n";}
                        if( $debug == "yes" ){ echo __LINE__ . str_replace( "\n" , "<br>\n" . __LINE__ . ":" , print_r($from_address,true) ) . "<br>\n";}
                        $to = $ups->person_object($to_person["display_name"], $to_attr ,$to_person["phone"], $to_address  );
						//print_r($to);
                        $xmlArr = $ups->run_shippment($from , $from, $to , $order_info["weight"] , "LBS" , $shipment_type , "02" ,  'Order - ' . $order_info["order_id"] , $order_info["grant_total"]  );
						//print_r($xmlArr);
                        //echo str_replace(array("\n" , " "), array('<br/>', '&nbsp;' ), print_r($xmlArr["children"] , true));
                        //file_put_contents("request.xml", $xmlArr["sourceXML"]  );
                        //file_put_contents("responce.xml", $xmlArr["originalXML"]  );
                        /*$accept = $ups->AcceptShip($xmlArr["children"]["ShipmentDigest"][0]["value"]);
                        $up_order = array();
                        $shipping_label = $accept["children"]["ShipmentResults"][0]["children"]["PackageResults"][0]["children"]["LabelImage"][0]["children"]["GraphicImage"][0]["value"];
                        $up_order["shipment_label"] = $shipping_label;
                        if( $accept["children"]["ShipmentResults"][0]["children"]["ControlLogReceipt"][0]["children"]["GraphicImage"][0]["value"] != ''){
                            $hvr = $accept["children"]["ShipmentResults"][0]["children"]["ControlLogReceipt"][0]["children"]["GraphicImage"][0]["value"];
                            $up_order["shipment_hvr"] = $hvr;
                        }*/

                        //$this->db->update('erp_order', $up_order, "order_id", $order_info["order_id"]);
                        
                        if( $debug == "yes" ){ echo __LINE__ . str_replace( "\n" , "<br>\n" . __LINE__ . ":" , print_r($xmlArr,true) ) . "<br>\n";}
                   
					   $return = $xmlArr["children"]["ShipmentCharges"][0]["children"]["TotalCharges"][0]["children"]["MonetaryValue"][0]["value"];
						//echo $return;
                        

                    }
                   return $return;
                break;
                case "work order":

                break;    
            }    
		//return $return;  
    }
    
    
}



/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
