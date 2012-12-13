<?php

class secure {
    var $db;
    var $sleep;
    var $max_tryes;
    function __construct( ) {
        $this->sleep = '1'; // Time at start of any decryption or encryption calls
        $this->max_tryes = array();
        $this->max_tryes["creditcard"] = "100";
        $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);;
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
        function validate_card_expire($mmyy) {
      if (!is_numeric($mmyy) || strlen($mmyy) != 4) {
        return false;
      }      
      $mm = substr($mmyy, 0, 2);
      $yy = substr($mmyy, 2, 2);        
      if ($mm < 1 || $mm > 12) {
        return false;
      }
      $year = date('Y');
      $yy = substr($year, 0, 2) . $yy; // eg 2007
      if (is_numeric($yy) && $yy >= $year && $yy <= ($year + 10)) {
      } else {
        return false;
      }
      if ($yy == $year && $mm < date('n')) {
        return false;
      }      
      return true;
    }

    // luhn algorithm
    function validate_card_number($card_number) {
      $card_number = ereg_replace('[^0-9]', '', $card_number);      
      if ($card_number < 9) return false;
      $card_number = strrev($card_number);
      $total = 0;
      for ($i = 0; $i < strlen($card_number); $i++) {
        $current_number = substr($card_number, $i, 1);
        if ($i % 2 == 1) {
          $current_number *= 2;
        }
        if ($current_number > 9) {
          $first_number = $current_number % 10;
          $second_number = ($current_number - $first_number) / 10;
          $current_number = $first_number + $second_number;
        }
        $total += $current_number;
      }
      return ($total % 10 == 0);
    }
    function add_module_address( $module_name , $module_id , $street_addr , $city , $state , $zip,$type="Work"){
        $a = array();
        $a["module_id"] = $module_id;
        $a["module_name"] = $module_name;
        $a["street_address"] = $street_addr;
        $a["city"] = $city;
        $a["state"] = $state;
        $a["zip"] = $zip;
        $a["type"] = $type;
        $this->db->insert('module_address', $a);
        return $this->db->last_insert_id();
                        
    }
    function get_address_by_id( $address_id ){
             $sql = "SELECT street_address , city , state , zip FROM module_address WHERE address_id = '$address_id'";
       
        $return = array();
        $result = $this->db->query($sql);
        while( $row = $this->db->fetch_assoc($result)){
            $return[] = $row; 
        }
        return $return;   
    }
    function get_address( $module_name , $module_id ){
        if( $module_name == "contacts" ){
            $sql = "SELECT street_address , city , state , zip FROM contacts_address WHERE contact_id = '$module_id'";
        } else {
             $sql = "SELECT street_address , city , state , zip FROM module_address WHERE module_id = '$module_id' AND module_name = '$module_name'";
        }
        $return = array();
        $result = $this->db->query($sql);
        while( $row = $this->db->fetch_assoc($result)){
            $return[] = $row; 
        }
        return $return;               
    }
    function submitCreditCard_ui( $module_name , $module_id , $clock_key , $ccnum , $cvv , $expr , $type , $name_oc , $street_addr , $city , $state , $zip ){
        $error = array();
        if( $this->validate_card_number($ccnum) == false ){
            $error["ccnum"] = "invalid card number";
        }
        if($this->validate_card_expire($expr) == false ){
            $error["expr"] = "invalid expiration date";
        }
        if( $street_addr == '' ){
            $error["street"] = 'No street given';
        }
        if( $city == '' ){
            $error["city"] = 'No street given';
        }
        if( $state == '' ){
            $error["state"] = 'No state given';
        }
        if(strlen($state) != 2 ){
            $error["state"] = 'Invalid State';
        }
        if( $zip == '' ){
            $error["zip"] = 'No zip given';
        }
        if( count( $error) == 0 ){
            $addcard = $this->add_creditcard( $ccnum , $cvv , $expr , '0' , $name_oc , $type , $module_name, $module_id , $clock_key);
            if(array_key_exists("error", $addcard) ){
                if( $addcard["error"] == "Invalid ClockKey"){
                    $error["clock_key"] = "Invalid ClockKey";
                } else {
                    $error["generic"] = $addcard["error"];
                }
            } else { // No Errors With Adding Card
                $address = $this->add_module_address('creditcard', $addcard["info"], $street_addr, $city, $state, $zip);
                $this->db->update("credit_cards", array('address_id' => $address ), 'ccid', $addcard["info"]);
                return "<script>secure.creditcard_box_inner('$module_name' , '$module_id' , { target: 'cc_main_$module_name$module_id' }</script>";
            }            
            
        } 
        //// Error's with something
            //$type , $name_oc , $street_addr , $city , $state , $zip
            $v = array();
            $v["clock_key"] = $clock_key;
            $v["ccnum"] = $ccnum;
            $v["cvv"] = $cvv;
            $v["expr"] = $expr;
            $v["type"] = $type;
            $v["name"] = $name_oc;
            $v["street"] = $street_addr;
            $v["city"] = $city;
            $v["state"] = $state;
            $v["zip"] = $zip;
            return $this->addCreditCard_ui($module_name, $module_id, $v, $error);
            
         
                   
    }
    
    
    function get_creditcard_type_dropdown( $name , $selected='', $overide=array() ){
        $option["css"] = "";
        foreach( $overide as $n => $v){
            $option[$n] = $v;
        }
        
        
        
        $result = $this->db->query("SELECT * FROM erp_dropdown_options WHERE option_name = 'credit_card_types'");
        ob_start();
        ?>
            <select style="<?php echo $option["css"]; ?>" id="<?php echo $name; ?>" name="<?php echo $name;?>" >
            <?php while($row=$this->db->fetch_assoc($result)){ ?>
                <option value="<?php echo $row["identifier"]; ?>" <?php if($row["identifier"] == "$selected"){ echo "SELECTED"; } ?> ><?php echo $row["name"]; ?></option>
                
            <?php } ?>
            </select>

        <?php
        
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
    
    
    function addCreditCard_ui( $module_name , $module_id , $values=array(),$error=array() ){
        ob_start();
        $rand = rand( 0 , 100000000 );
        
        ?>
        
        <div id="cc_<?php echo $rand . "_$module_name" . "_$module_id";?>">
            <?php if(array_key_exists("generic", $error)){ ?>
            <div style="width: 100%; background: red;color: white;font-weight: bold;font-size: large;text-align: center"><?php echo $error["generic"]; ?></div>
            
            <?php } ?>
            <table border="0" style="width: 100%;" >
                <tr>
                    <td colspan="3" style="text-align: center;width:230px;" >Clock Security Code</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center;width:230px;" ><input <?php if(array_key_exists("clock_key", $values)){ echo ' value="' . $values["clock_key"] . '"';}   ?>  style="width: 80px;<?php if(array_key_exists("clock_key", $error)){ echo 'background: red;';} ?>" id="SecCode_<?php echo $module_name . '_' . $module_id;?>" ></td>
                </tr>                
                <tr>
                    <th style="text-align: center; width: 150px;">Credit Card Number</th>
                    <th style="text-align: center; width: 40px;" >CVV</th>
                    <th style="text-align: center; width: 40px;" >Expr (MMYY)</th>
                    <th style="text-align: left; " >&nbsp;&nbsp;Type</th>
                </tr>
                <tr>
                    <td><input <?php if(array_key_exists("ccnum", $values)){ echo ' value="' . $values["ccnum"] . '"';}   ?> style="width: 150px;<?php if(array_key_exists("ccnum", $error)){ echo 'background: red;';} ?>" id="ccnum_<?php echo $module_name . '_' . $module_id;?>" name="ccnum_<?php echo $module_name . '_' . $module_id;?>" type="text"></td>
                    <td><input <?php if(array_key_exists("cvv", $values)){ echo ' value="' . $values["cvv"] . '"';}   ?> style="width: 40px;<?php if(array_key_exists("cvv", $error)){ echo 'background: red;';} ?>" id="cvv_<?php echo $module_name . '_' . $module_id;?>"></td>
                    <td><input <?php if(array_key_exists("expr", $values)){ echo ' value="' . $values["expr"] . '"';}   ?>style="width: 40px;<?php if(array_key_exists("expr", $error)){ echo 'background: red;';} ?>" id="exp_<?php echo $module_name . '_' . $module_id;?>" > </td>
                    <td style="text-align: left;">
                       <!-- <input <?php if(array_key_exists("type", $values)){ echo ' value="' . $values["type"] . '"';}   ?> style="width: 40px;<?php if(array_key_exists("type", $error)){ echo 'background: red;';} ?>" id="type_<?php echo $module_name . '_' . $module_id;?>" > -->
                        <?php 
                        $dd_overide = array();
                        $dd_select='';
                        if(array_key_exists("type", $values)){ $dd_select = $values["type"];}
                        if(array_key_exists("type", $error)){ $dd_overide["css"] = "background: red;"; }
                        echo $this->get_creditcard_type_dropdown("type_" . $module_name . '_' . $module_id, $dd_select, $dd_overide); ?>
                    </td>
                </tr>
                <tr>
                    <th style="text-align: center; width: 242px;" colspan="3">Name on Card</th>
                    <th></th>
                </tr>
                <tr>
                    <td colspan="3"><input <?php if(array_key_exists("name", $values)){ echo ' value="' . $values["name"] . '"';}   ?> id="name_<?php echo $module_name . '_' . $module_id;?>" style="width: 242px;" ></td>
                    <td></td>
                </tr>
                <tr>
                    <th style="text-align: center; width: 242px;" colspan="3">Address</th>
                    <th></th>                  
                </tr>
                <tr><td colspan="3" >
                <select style="width: 242px;" >
                    <option onmousedown="">--SELECT--</option>
                    <option onmousedown="$('#address_tr_<?php echo $module_name . '_' . $module_id;?>').show();$('#cc_street_addr<?php echo $module_name . '_' . $module_id;?>').val('');$('#cc_city_addr<?php echo $module_name . '_' . $module_id;?>').val('');$('#cc_state_addr<?php echo $module_name . '_' . $module_id;?>').val('');$('#cc_zip_addr<?php echo $module_name . '_' . $module_id;?>').val('');" >Enter New</option>
                    <?php foreach($this->get_address($module_name, $module_id) as $addr){?>
                    <option onmousedown="$('#cc_street_addr<?php echo $module_name . '_' . $module_id;?>').val('<?php echo $addr["street_address"]; ?>');$('#cc_city_addr<?php echo $module_name . '_' . $module_id;?>').val('<?php echo $addr["city"]; ?>');$('#cc_state_addr<?php echo $module_name . '_' . $module_id;?>').val('<?php  echo$this->convert_statename_to_abbr( $addr["state"] ); ?>');$('#cc_zip_addr<?php echo $module_name . '_' . $module_id;?>').val('<?php echo $addr["zip"]; ?>')" ><?php echo $addr["street_address"]; ?></option>  
                    <?}?>
                </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" >
                        <div  id="address_tr_<?php echo $module_name . '_' . $module_id;?>" style="display: block;"  >
                        <table style="width: 242px;" >
                            <tr>
                                <td colspan="3" >
                                    <input <?php if(array_key_exists("street", $values)){ echo ' value="' . $values["street"] . '"';}   ?> style="width: 242px;<?php if(array_key_exists("street", $error)){ echo 'background: red;';} ?>" id="cc_street_addr<?php echo $module_name . '_' . $module_id;?>" >
                                </td>
                            </tr>
                            <tr>
                                <td><input <?php if(array_key_exists("city", $values)){ echo ' value="' . $values["city"] . '"';}   ?> id="cc_city_addr<?php echo $module_name . '_' . $module_id;?>" style="width: 150px;<?php if(array_key_exists("city", $error)){ echo 'background: red;';} ?>" ></td>
                                <td><input <?php if(array_key_exists("state", $values)){ echo ' value="' . $values["state"] . '"';}   ?> id="cc_state_addr<?php echo $module_name . '_' . $module_id;?>" style="width: 30px;<?php if(array_key_exists("state", $error)){ echo 'background: red;';} ?>" ></td>
                                <td><input <?php if(array_key_exists("zip", $values)){ echo ' value="' . $values["zip"] . '"';}   ?> id="cc_zip_addr<?php echo $module_name . '_' . $module_id;?>" style="width: 50px;<?php if(array_key_exists("zip", $error)){ echo 'background: red;';} ?>" ></td>
                            </tr>
                        </table>
                            <a href="#" onclick="secure.submitCreditCard_ui( '<?php echo $module_name; ?>' , 
                                '<?php echo $module_id;?>' , 
                                $('#SecCode_<?php echo $module_name . '_' . $module_id;?>').val() , 
                                $('#ccnum_<?php echo $module_name . '_' . $module_id;?>').val() , 
                                $('#cvv_<?php echo $module_name . '_' . $module_id;?>').val() ,  
                                $('#exp_<?php echo $module_name . '_' . $module_id;?>').val() , 
                                $('#type_<?php echo $module_name . '_' . $module_id;?>').val() , 
                                $('#name_<?php echo $module_name . '_' . $module_id;?>').val() , 
                                $('#cc_street_addr<?php echo $module_name . '_' . $module_id;?>').val() , 
                                $('#cc_city_addr<?php echo $module_name . '_' . $module_id;?>').val() , 
                                $('#cc_state_addr<?php echo $module_name . '_' . $module_id;?>').val() ,  
                                $('#cc_zip_addr<?php echo $module_name . '_' . $module_id;?>').val() , { target: 'add_creditcard_<?php echo $module_name . '_' . $module_id;?>' } );" 
                                style="font-weight: bold !important;">Save</a>&nbsp;&nbsp;&nbsp;<a href="#" onclick="$('#cc_<?php echo $rand . "_$module_name" . "_$module_id";?>').remove();" style="font-weight: bold !important;">Close</a><br/><br/>
                        
                        
                        
                        </div>
                    </td>
                </tr>
            </table>
        
        
        
        
        
        </div>
        <?php
        $html = ob_get_contents();
        ob_clean();
        return $html;
        
    }
    /*
     * Call This function to display the credit card info, This sould be the only thing needed for most ui's
     * The reason I have both creditcard_box and creditcard_box_inner is becouse 
     * this allows me to update with a known fixed point I can in my code anyware run 
     * secure.creditcard_box_inner( '$module_name' , '$module_id' , { target: 'cc_main_$module_name$module_id' } );
     * and it will update the box, No need to know what my parent div is ext...
     */
    function creditcard_box( $module_name , $module_id){
        ob_start();
        
        echo "<div class='profile_box1' id='cc_main_$module_name$module_id' >" . $this->creditcard_box_inner($module_name, $module_id) . "</div>";
        
        $html = ob_get_contents();
        ob_clean();
        return $html;
    }
    
    
    function creditcard_box_inner( $module_name , $module_id ){
        ob_start();
        $cc = $this->get_creditcards($module_name, $module_id);
        //echo "$module_name -> $module_id<br/>";
        //echo str_replace("\n", "<br/>", print_r($cc,true));
        ?>
        
            <h4> Credit Cards</h4>
            <?php if( count($cc) != 0 ){ ?>
                <table style="width: 100%;">
                    <tr><th style="">Name</th><th>Type</th><th>Expiration</th></tr>
                <?php foreach( $cc as $n => $v ){ ?>
                    <tr><td><?php echo $v["name"]; ?></td><td><?php echo $v["type"];?></td><td><?php echo $v["expiration"] ?></td></tr>           
                <?php } ?>
                </table>
            <?php } ?>
            <div id="add_creditcard_<?php echo $module_name . "_". $module_id;?>" ></div>
            <a href="#" onClick="secure.addCreditCard_ui('<?php echo $module_name;?>' , '<?php echo $module_id;?>' , { target:'add_creditcard_<?php echo $module_name . '_' . $module_id;?>'})" >add another</a>
        
        
        
        
        
        <?php
        $html = ob_get_contents();
        ob_clean();
        return $html;
    }
    
    function get_keyinfo($type){
        $result = $this->db->query("SELECT * FROM securekey WHERE type = '$type'");
        $keyarr = $this->db->fetch_assoc($result);
        return $keyarr;
    }
    
    function add_creditcard( $ccnum , $cvv , $exp , $addr_id , $fullname , $type , $module_name, $module_id , $clock_key){
        $encnum = $this->clock_encrypt($ccnum, 'creditcard', $clock_key);
        $enccvv =$this->clock_encrypt($cvv, 'creditcard', $clock_key);
        if(array_key_exists("error", $encnum) || array_key_exists("error", $enccvv )){
            $retstr = 'an error ocured';
            if( $encnum["error"] == "Invalid ClockKey" || $enccvv["error"] == "Invalid ClockKey"){
                $retstr = "Invalid ClockKey";
            }
            return array("error" => "$retstr");
        }
        if( $this->validate_card_expire($exp) == false){
            return array("error" => "invalid expiration date");
        }
        if( $this->validate_card_number($ccnum) == false ){
            return array("error" => "invalid card number " );
        }
        $ccarr = array();
        $ccarr["ccnum"] = $encnum["data"];
        $ccarr["cvv"] = $enccvv["data"];
        $ccarr["expiration"] = $exp; //MUST BE IN MMYY
        $ccarr["address_id"] = $addr_id;
        $ccarr["name"] = $fullname;
        $ccarr["type"] = $type;
        $ccarr["module_name"] = $module_name;
        $ccarr["module_id"] = $module_id;
        
        $this->db->insert('credit_cards', $ccarr );
        return array("info" => $this->db->last_insert_id() );
    }
    
    function get_creditcards( $module_name , $module_id ){
        $result = $this->db->query("SELECT ccid , address_id , name , type , expiration FROM credit_cards WHERE module_name = '$module_name' AND module_id = '$module_id'");
        $return = array();
        while($row=$this->db->fetch_assoc($result)){
            $return[$row["ccid"]] = $row;
        }
        return $return;
    }
    function get_creditcard( $ccid ){
        $result = $this->db->query("SELECT ccid , address_id , name , type , expiration FROM credit_cards WHERE ccid = '$ccid'");
        $return = array();
        while($row=$this->db->fetch_assoc($result)){
            $return[$row["ccid"]] = $row;
        }
        return $return;
    }
    function decrypte_creditcard( $ccid , $clock_key ){
        $cardinfo = $this->db->fetch_assoc($this->db->query("SELECT * FROM credit_cards WHERE ccid = '$ccid' "));
        //$return = $cardinfo;
        $ccnumarr = $this->clock_decrypt($cardinfo["ccnum"], 'creditcard', $clock_key); 
        $cccvvarr = $this->clock_decrypt($cardinfo["cvv"], 'creditcard', $clock_key);
        if( $cccvvarr["error"] != '' || $ccnumarr["error"] != '' ){
            if( $cccvvarr["error"] == 'Invalid ClockKey' ){
                return array('error' => "Invalid ClockKey");
            } else {
                return array( 'error' => "An Error Occured" , 'errorcvv' => $cccvvarr , 'errorccnum' => $ccnumarr);
            }
        } else {
            $return = $cardinfo;
            $return["ccnum"] = $ccnumarr["data"];
            $return["cvv"] = $cccvvarr["data"];
            return $return;
        }
        
    }
    function update_clock_key( $old , $new , $type ){
        $return = array();
        $keyinfo = $this->get_keyinfo($type);
        if( is_array( $keyinfo) != 0 ){
            if(array_key_exists("enc_key", $keyinfo)){
                $key = $this->decrypt($keyinfo["enc_key"], $old . SALT );
                if( HASH('sha512' , $key ) == $keyinfo["hash"] ){
                    $newEncKey = $this->encrypt($key, $new . SALT);
                    if( $this->decrypt($newEncKey, $new . SALT) == $this->decrypt( $keyinfo["enc_key"], $old . SALT) && HASH('sha512' ,  $this->decrypt($newEncKey, $new . SALT) )== $keyinfo["hash"]){
                        $this->db->update('securekey', array('enc_key' => $newEncKey), 'type', $type);
                    } else {
                        $return["error"] = "Internal Error";
                    }                
                        
                } else {
                    sleep(1); // This will Slow down any attacks 
                    $return["error"] = "Invalid ClockKey";
                }      
                
            } else {
                $return["error"] = 'Type ' . $type . 'Does not exsist';
            }       
        } else {
            $return["error"] = 'Type ' . $type . 'Does not exsist';
        }
        return $return;
        
    }
    function check_attempts( $type ){
       $ctarr = $this->db->fetch_assoc( $this->db->query("SELECT current_attempts FROM securekey WHERE type='$type'") );
       if( $ctarr["current_attempts"] > $this->max_tryes[$type] ){
           return false;
       } else {
           return true;
       }
    }
    function clock_decrypt( $data , $type , $clock_key ){
        while( $this->check_attempts($type) == false ){
            sleep(1); // This will wait 1 second for attempts to go down,
        }
        $this->db->query("UPDATE securekey SET current_attempts=current_attempts+1 WHERE type = '$type'"); //Set this session as a current attempt
        sleep( $this->sleep );
        $return = array();
        $keyinfo = $this->get_keyinfo($type);
        if( is_array( $keyinfo) != 0 ){
            if(array_key_exists("enc_key", $keyinfo)){
                $key = $this->decrypt($keyinfo["enc_key"], $clock_key . SALT );
                if( HASH('sha512' , $key ) == $keyinfo["hash"] ){
                    $return["data"] = $this->decrypt($data, $key);                    
                    
                } else {
                    sleep($this->sleep); // This will Slow down any attacks 
                    $return["error"] = "Invalid ClockKey";
                }      
                
            } else {
                $return["error"] = 'Type ' . $type . 'Does not exsist';
            }       
        } else {
            $return["error"] = 'Type ' . $type . 'Does not exsist';
        }
        $this->db->query("UPDATE securekey SET current_attempts=current_attempts-1 WHERE type = '$type'");
        return $return;
    }
    
    function clock_encrypt( $data , $type , $clock_key ){
        while( $this->check_attempts($type) == false ){
            sleep(1); // This will wait 1 second for attempts to go down,
        }
        $this->db->query("UPDATE securekey SET current_attempts=current_attempts+1 WHERE type = '$type'"); //Set this session as a current attempt

        sleep( $this->sleep );
        $return = array();
        $keyinfo = $this->get_keyinfo($type);
        if( is_array( $keyinfo) != 0 ){
            if(array_key_exists("enc_key", $keyinfo)){
                $key = $this->decrypt($keyinfo["enc_key"], $clock_key . SALT );
                if( HASH('sha512' , $key ) == $keyinfo["hash"] ){
                    $return["data"] = $this->encrypt($data, $key);                    
                    
                } else {
                    sleep($this->sleep); // This will Slow down any attacks 
                    $return["error"] = "Invalid ClockKey";
                }      
                
            } else {
                $return["error"] = 'Type ' . $type . 'Does not exsist';
            }       
        } else {
            $return["error"] = 'Type ' . $type . 'Does not exsist';
        }
        $this->db->query("UPDATE securekey SET current_attempts=current_attempts-1 WHERE type = '$type'");
        return $return;
        
    }   
    function encrypt($text , $key ) 
    { 
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
    } 

    function decrypt($text , $key) 
    { 
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))); 
    }   
    
}
?>