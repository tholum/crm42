<?php
require_once('class/class.dynamicpage.php');
require_once('class/class.Authentication.php');
if( PHONE_SYSTEM == "asterisk"){
 require_once( "class/class.asterisk.php");
}
require_once('class/class.email_client.php');
require_once('class/class.casecreation.php');
require_once('class/class.GlobalTask.php');
require_once('class/class.follow.php');
include_once("class/class.eapi_account.php");
include_once('class/class.cases.php');
include_once('class/class.contacts.php');
include_once('class/class.timeTracker.php');
 /***********************************************************************************

		Class Discription : basic_page 
		
		Class Memeber Functions : setPageKeywords($keywords)
								  setAccessRules($rule)
								  setAccessRulesType($type)
								  setPageDescription($description)
								  setPageTitle($title)
								  setInnerNav($type)
								  setActiveButton($ab)
								  setImportCss1($css_1)
								  setPageStyle($style)
								  setExtJavaScripts1($ext_custom_scripts)
								  setExtJavaScripts2($ext_custom_scripts)
								  setCustomJavaScripts($custom_scripts)
								  setBodyScript($script)
								  displayPageTop()
								  displayPageBottom()
								  printDoctype()
								  printHTMLStart()
								  printHeadStart()
								  printCharEncod()
								  printTitle()
								  printMetaAuthor()
								  printMetaKeywords()
								  printMetaDesc()
								  printFOUC()
								  printExtJavaScripts()
								  printCustomJavaScripts()
								  printMainStyle()
								  printPageStyle()
								  printHeadEnd()
								  printBodyStart()
								  printHeader()
								  printContentAreaStart()
								  printContentColumnStart()
								  printContentColumnEnd()
								  printInfoColumnEnd()
								  printFooter()
								  printContentAreaEnd()
								  printGoogAna()
								  printBodyEnd()
								  printHTMLEnd()
								  CheckAuthorization()
								  gotoPage($page)
		
		
		Describe Function of Each Memeber function :
		
								  1. function setPageKeywords($keywords)
								  
								  2. function setAccessRules($rule)
								  
								  3. function setAccessRulesType($type)
								  
								  4. function setPageDescription($description)
								  
								  5. function setPageTitle($title)
								  
								  6. function setInnerNav($type)
								  
								  7. function setActiveButton($ab)
								  
								  8. function setImportCss1($css_1)
								  
								  9. function setPageStyle($style)
								  
								  10. function setExtJavaScripts1($ext_custom_scripts)
								  
								  11. function setExtJavaScripts2($ext_custom_scripts)
								  
								  12. function setCustomJavaScripts($custom_scripts)
								  
								  13. function setBodyScript($script)
								  
								  14. function displayPageTop()
								  
								  15. function displayPageBottom()
								  
								  16. function printDoctype()
								  
								  17. function printHTMLStart()
								  
								  18. function printHeadStart()
								  
								  19. function printCharEncod()
								  
								  20. function printTitle()
								  
								  21. function printMetaAuthor()
								  
								  22. function printMetaKeywords()
								  
								  23. function printMetaDesc()
								  
								  24. function printFOUC()
								  
								  25. function printExtJavaScripts()
								  
								  26. function printCustomJavaScripts()
								  
								  27. function printMainStyle()
								  
								  28. function printPageStyle()
								  
								  29. function printHeadEnd()
								  
								  30. function printBodyStart()
								  
								  31. function printHeader()
								  
								  32. function printContentAreaStart()
								  
								  33. function printContentColumnStart()
								  
								  34. function printContentColumnEnd()
								  
								  35. function printInfoColumnEnd()
								  
								  36. function printFooter()
								  
								  37. function printContentAreaEnd()
								  
								  38. function printGoogAna()
								  
								  39. function printBodyEnd()
								  
								  40. function printHTMLEnd()
								  
								  41. function CheckAuthorization()
								  
								  42. function gotoPage($page)

					



************************************************************************************/
class basic_page
{
  var $db;
  var $page_keywords;
  var $page_description;
  var $page_title;
  var $active_button;  // The active button for navagation (navagation section)
  var $inner_nav; // The active page for navagation
  var $css;
  var $css1; // normally main_style.css which is the style sheet that define the standard elements of all pages.
  var $css2;
  var $css3;
  var $css4;
  var $css5;
  var $css6;
  var $css7;
  var $css8;
  var $css9;
  var $css10;
  var $css11;
  var $css12;
  var $css13;
  
  var $dynamic_css1;
  var $dynamic_css2;
  var $dynamic_css3;
  var $dynamic_css4;
  var $dynamic_css5;
  var $dynamic_css6;
  var $dynamic_css7;
  var $dynamic_css8;
  var $dynamic_css10;
  var $dynamic_css11;
  var $dynamic_css12;
  var $dynamic_css13;
    
  var $page_style; // this should be used sparingly; Use external style sheets.
  var $ext_java_scripts = array();
  var $ext_java_scripts1; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts2; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts3; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts4; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts5;
  var $ext_java_scripts6;
  var $ext_java_scripts7;
  var $ext_java_scripts8;
  var $ext_java_scripts9;
  var $ext_java_scripts10;
  var $ext_java_scripts11;
  var $ext_java_scripts12;
  var $ext_java_scripts13;
  var $ext_java_scripts14;  
  var $ext_java_scripts15;
  var $ext_java_scripts16;
  var $ext_java_scripts17; 
  
  
  var $custom_java_scripts;  /* the <script> tags are already printed.  This if for javascript functions */
  var $body_script; // Add an onLoad script into the <body> tag.  Should be in the form of 'onLoad="javascriptFunction()"'
  var $auth;	//Authentication variable
  var $access_rules=array();			// It stores the user group & their types in which user needs to be in to access this page (may be all or them or any of them)
  var $access_rules_type=array();		// any or all , it determines how rules condition should be applied
  var $asterisk;
  var $emailclient;
  var $casecreation;
  var $dynamic_page;
  var $cache_displayname = array();
  public $eapi_account;
  var $flags;
  public $eapi_api;
   function __construct( $objects=array() )
   {   
       if( PHONE_SYSTEM == "asterisk"){
            $this->asterisk = new Asterisk;
        }
        
        
        
        $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	 $this->auth=new Authentication();

         $this->css = array();
         $this->dynamic_page = new dynamic_page();
         if(class_exists("follow")){
             $this->follow = new follow( $this );
         }
         if(class_exists("eapi_api")){
             $this->eapi_api = new eapi_api();
         }
         if(class_exists("eapi_order")){
             $this->eapi_order = new eapi_order( $this );
         }
         if(class_exists("eapi_account")){
             $this->eapi_account = new eapi_account($this);
         }
          if(class_exists("flags")){
             $this->flags = new flags();
         }
         //eapi_account
   }
   
  // sets the meta-keywords for the new page
   function decode_text( $encoding , $string ){
       $return = '';
       switch( $encoding ){
           default:
           case "plain":
               $return = utf8_encode( $string );
           break;
           case "text":
               $return=utf8_encode( str_replace( array("\n" , "\r\n" ), "<br/>\n", $string) );
           break;
           case "base64":
               $return = base64_decode($string);
           break;
           case "htmlspecialchars":
               $return = htmlspecialchars_decode( $string );
           break;
       }
       return $return;
   }
   
   function log_activity( $module_name , $module_id , $action , $from , $to , $attached_module_name='' , $attached_module_id='' ){
       $i = array();
       $i['module_name'] = $module_name;
       $i['module_id'] = $module_id;
       $i['action'] = $action;
       $i['from'] = $from;
       $i['to'] = $to;
       $i['attached_module_name'] = $attached_module_name;
       $i['attached_module_id'] = $attached_module_id;
       $i['user_id'] = $_SESSION['user_id'];
       $this->db->insert('activity_log' , $i);
       
   }
   function get_activity_log_by_module($module_name , $module_id , $overide=array() ){
       $options = array();
       $options['order'] = 'DESC';
       $options['order_column'] = 'a.activity_id';
       foreach( $overide as $n => $v ){
           $options[$n] = $v;
       }
      $res = $this->db->query("SELECT a.* , b.first_name , b.last_name FROM activity_log a LEFT JOIN tbl_user b ON a.user_id = b.user_id WHERE module_name = '$module_name' AND module_id='$module_id' ORDER BY " . $options['order_column'] . " " . $options['order'] );
      $return = array();
      while( $row=$this->db->fetch_assoc($res)){
          $return[] = $row;
      }
      return $return;
   }
  function edit_dropdown_option( $option_name , $overide=array()){
      $options = array();
      $options['container'] = "edit_dropdown_option_$option_name";
      $options['title'] = $option_name;
      foreach( $overide as $n => $v ){
          $options[$n] = $v;
      }
      ob_start();
      ?>
<div class="edit_dropdown_option_title_<?php echo $option_name;?> edit_dropdown_option_title"><?php echo $options["title"];?></div>
<div id="<?php echo $options['container'];?>" class="edit_dropdown_option_<?php echo $option_name;?> edit_dropdown_option" >
<?php echo $this->edit_dropdown_option_inner($option_name , $overide); ?>
</div>
      <?php
      $html=ob_get_contents();
      ob_end_clean();
      return $html ;
  }
  function get_setting( $user_id , $name ){
      $result = $this->db->query("SELECT * FROM user_settings WHERE user_id = '$user_id' AND name = '$name'");
      if( $this->db->num_rows($result) != 0 ){
          $arr = $this->db->fetch_assoc($result);
          return $arr["value"];
      } else {
          return false;
      }
  }
  function set_setting( $user_id , $name , $value ){
      $this->db->query( "INSERT INTO user_settings (`user_id` , `name` , `value`) VALUES('$user_id' , '$name' , '$value' ) ON DUPLICATE KEY UPDATE `value` = '$value'");
      return 'ok';
  }
  function add_dropdown_option( $option_name , $overide=array() ){
      ob_start();
      $options = array();
      $options['container'] = "edit_dropdown_option_$option_name";
      $options['title'] = $option_name;
      foreach( $overide as $n => $v ){
          $options[$n] = $v;
      }
      
      $html=ob_get_contents();
      ob_end_clean();
      return $html ;
  }
  function get_email_addr_by_module( $module_name , $module_id ){
      $email = '';
      $format = strtolower($module_name);
      if(file_exists("./modules/display/module_email.$format.php")){
          include("modules/display/module_email.$format.php");
      } 
      return $email;
  }
  function edit_dropdown_option_inner( $option_name , $overide = array()){
      ob_start();
      $options = array();
      $options['container'] = "edit_dropdown_option_$option_name";
      $options['title'] = $option_name;
      foreach( $overide as $n => $v ){
          $options[$n] = $v;
      }
      $array = $this->get_dropdown_array($option_name);
      ?>
<table class="edit_dropdown_option_<?php echo $option_name;?> edit_dropdown_option" >
    <tr><td>Identifier</td><td>Display Name</td><td></td></tr>
    <tr><td><input id="identifyer_<?php echo $options["container"]; ?>" ></td><td><input id="display_<?php echo $options["container"]; ?>" ></td><td><div class="add_button_<?php echo $option_name; ?> add_button" >&nbsp;</div></td></tr> 
</table>
<?php
      $html=ob_get_contents();
      ob_end_clean();
      return $html ;
  }
  
  function get_cache_displayname( $module_name , $module_id ){
      
      if(array_key_exists("$module_name", $this->cache_displayname)){
         if(array_key_exists("$module_id", $this->cache_displayname[$module_name])){
             return $this->cache_displayname[$module_name][$module_id];
         } else {
             return false;
         }
      } else {
          return false;
      }
  }
  function set_cache_displayname( $module_name , $module_id , $display_name ){
      if(array_key_exists("$module_name", $this->cache_displayname) == false ){
          $this->cache_displayname[$module_name] = array();
      }
      $this->cache_displayname[$module_name][$module_id] = $display_name;
  }
  
  function setPageKeywords($keywords)
  {
	$this->page_keywords = $keywords;
  }
  function format_data( $format , $original , $overide=array() ){
      $format_options = array();
      $format_options['return_csv'] = false;
      foreach( $overide as $n => $v ){
          $format_options[$n] = $v;
      }
      if(file_exists("./modules/display/dataformat.$format.php")){
          include("modules/display/dataformat.$format.php");
      } else {
          return $original;
      }
      return $clean;
  }
  function get_api( $type , $options=array() ){
      $array = array();
      if(file_exists("./modules/api/$type.php")){
          include("modules/api/$type.php");
      } 
      return $array;
  }  
  function get_ajax( $type , $options=array() ){
      $array = array();
      if(file_exists("./modules/ajax/$type.php")){
          include("modules/ajax/$type.php");
      } 
      return $array;
  }
  
  function get_dropdown_array( $option_name ){
      $result  = $this->db->query("SELECT * FROM erp_dropdown_options WHERE option_name = '$option_name' AND active = '1' ORDER BY name");
      $return = array();
      while( $row = $this->db->fetch_assoc($result) ){
          $return[] = $row;
      }
      return $return;
          
  }
  function get_dropdown_options( $option_name , $overide = array() ){
      $options = array();
      $options["selected"] = '';
      $options["name"] = "name";
      $options["value"] = "identifier";
      foreach( $overide as $n => $v ){
          $options[$n] = $v;
      }
      $array = $this->get_dropdown_array($option_name);
      return $this->array_to_options( $array , $options );
      
  }
  function get_query_to_array( $sql , $overide=array()){
      $result  = $this->db->query($sql , __FILE__ , __LINE__);
      $return = array();
      while( $row = $this->db->fetch_assoc($result) ){
          $return[] = $row;
      }
      return $return;
  }
  function get_dropdown_options_query( $sql , $overide = array() ){
      $options = array();
      $options["selected"] = '';
      $options["name"] = "name";
      $options["value"] = "value";
      $options["pre_name"] = '';
      $options["post_name"] = '';
      foreach( $overide as $n => $v ){
          $options[$n] = $v;
      }
      $array = $this->get_query_to_array($sql);
      return $this->array_to_options( $array , $options );
  }
  function display_searchbar_selector( $type , $name ,  $options=array() ){
      include('modules/display/searchbar.' . $type . '.php');
      
  }
  function array_to_options( $array , $overide = array() ){
      $return = '';
      
      $options = array();
      $options["selected"] = '';
      $options["name"] = "name";
      $options["value"] = "value";
      
      $options["pre_name"] = '';
      $options["post_name"] = '';
      $options['title'] = '';
      foreach( $overide as $n => $v ){
          $options[$n] = $v;
      }
      foreach( $array as $op ){
          $title = '';
          if( $options['title'] != '' ){
              
              $title =  "title='" . str_replace( array('#VALUE#' , '#NAME#'), array($op['value'] , $op['name']), $options['title']) . "' ";
          } 
          if( $options['selected'] == $op[$options["value"]] && $options['selected'] != '' ){
                    $selected = 'SELECTED'; 
              } else { 
                    $selected = ''; 
              }
          //$return .= '<!--' . $selected . "|" . $options['selected'] . "|". $op[$options["value"]] . "|". ' -->';
          $return .= "<option value='" . $op[$options["value"]] . "' $title $selected >" . $options["pre_name"] . $op[$options["name"]] . $options["post_name"] . "</option>";
          
      }
      return $return;
  }
  
  function module_displayname( $module_name , $module_id , $format_options=array() ){
      $display_name =$this->get_cache_displayname($module_name , $module_id);
      if( $display_name === false ){
      $module_name = strtolower($module_name);
        if(file_exists("./modules/display/modulename.$module_name.php")){
            include("modules/display/modulename.$module_name.php");
        } else {
            return "$module_name $module_id";
        }
        $this->set_cache_displayname($module_name , $module_id , $display_name );
      }
      return $display_name;     
  }

  function encrypt($decrypted, $password, $salt='') {
      if( $salt == ''){
          $salt = 'I1oIEFxB8U3l9IZx7HbfwOqRcqN7IW5i25b6H2nrw0ItFiKOGBxWA3f9p6QbiSk';
      }
 // Build a 256-bit $key which is a SHA256 hash of $salt and $password.
 $key = hash('SHA256', $salt . $password, true);
 // Build $iv and $iv_base64.  We use a block size of 128 bits (AES compliant) and CBC mode.  (Note: ECB mode is inadequate as IV is not used.)
 srand(); $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
 if (strlen($iv_base64 = rtrim(base64_encode($iv), '=')) != 22) return false;
 // Encrypt $decrypted and an MD5 of $decrypted using $key.  MD5 is fine to use here because it's just to verify successful decryption.
 $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $decrypted . md5($decrypted), MCRYPT_MODE_CBC, $iv));
 // We're done!
 return $iv_base64 . $encrypted;
 }

function decrypt($encrypted, $password, $salt='') {
    if( $salt == ''){
        $salt = 'I1oIEFxB8U3l9IZx7HbfwOqRcqN7IW5i25b6H2nrw0ItFiKOGBxWA3f9p6QbiSk';
    }
 // Build a 256-bit $key which is a SHA256 hash of $salt and $password.
 $key = hash('SHA256', $salt . $password, true);
 // Retrieve $iv which is the first 22 characters plus ==, base64_decoded.
 $iv = base64_decode(substr($encrypted, 0, 22) . '==');
 // Remove $iv from $encrypted.
 $encrypted = substr($encrypted, 22);
 // Decrypt the data.  rtrim won't corrupt the data because the last 32 characters are the md5 hash; thus any \0 character has to be padding.
 $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($encrypted), MCRYPT_MODE_CBC, $iv), "\0\4");
 // Retrieve $hash which is the last 32 characters of $decrypted.
 $hash = substr($decrypted, -32);
 // Remove the last 32 characters from $decrypted.
 $decrypted = substr($decrypted, 0, -32);
 // Integrity check.  If this fails, either the data is corrupted, or the password/salt was incorrect.
 if (md5($decrypted) != $hash) return false;
 // Yay!
 return $decrypted;
 }
 
   function phplivex_pagify( $serial_data = '' , $page = '1'){
      $serial_data = $_SESSION[$serial_data];
      //$serial_data = $this->decrypt($serial_data, 'iBhD2iIPPRQ4V8Cb8RVvHzOne2LRMY8BSJinlbNhg6uQDAA96PRaooDfiEnJ73N');
      $s_arr = unserialize($serial_data);
      
      $sql = $s_arr["sql"];
      $query_overide = $s_arr["query_overide"];
      $pagify_overide = $s_arr["pagify_overide"] ;
      $pagify_overide["page"] = $page;
      return $this->pagify_query_inner($sql, $query_overide, $pagify_overide);
      
  }
  
  function set_global_setting( $setting_name , $setting_value , $encrypt = false ){
      if( $encrypt == true ){
          $value = $this->encrypt($setting_value, 'settings_pass' . SALT );
      } else {
          $value = $setting_value;
      }
      $this->db->query( "INSERT INTO global_settings ( `value` , `setting_name` ) VALUES( '$value' , '$setting_name' ) ON DUPLICATE KEY UPDATE `value` = '$value'" );
      
  }
  function get_global_setting( $setting_name , $encrypted = false ){
      $result = $this->db->query("SELECT value FROM global_settings WHERE setting_name = '$setting_name'");
      $info = $this->db->fetch_assoc($result);
      if( $encrypted == true ){
          $value = $this->decrypt( $info['value'], 'settings_pass' . SALT );
      } else {
          $value = $info['value'];
      }
      return $value;
  }
  
  function manage_global_setting($setting_name ,  $encrypted =false , $overide=array()){
      $current_value = $this->get_global_setting( $setting_name , $encrypted);
      $options = array();
      $options['type'] = 'text';
      foreach($overide as $n => $v){
          $options[$n] = $v;
      }
      ob_start();  
      switch(  strtolower($options['type'])){
          case "text":         
      ?>
<input 
    id="manage_global_setting_<?php echo $setting_name; ?>" 
    class="manage_global_setting_<?php echo $setting_name; ?> manage_global_setting" 
    value="<?php if( $encrypted == false ){ echo $current_value; } else { echo "***";} ?>"
    onchange="page.set_global_setting('<?php echo $setting_name; ?>' , $(this).val() , '<?php echo $encrypted; ?>' , {} )"/>
    <?php 
        break;
        case "checkbox":
      ?>
<input 
    id="manage_global_setting_<?php echo $setting_name; ?>" 
    type="checkbox"
    class="manage_global_setting_<?php echo $setting_name; ?> manage_global_setting" 
    <?php if( $current_value == '1'){ echo " checked "; } ?>
    onchange="page.set_global_setting('<?php echo $setting_name; ?>' , $(this).ctl_checked() , '<?php echo $encrypted; ?>' , {} )"/>
    <?php            
        break;
      }
    $html = ob_get_contents();
      ob_end_clean();
      return $html;
  }
  
  
  
  function pagify_query($sql , $query_overide=array() , $pagify_overide=array()){
      $container_id = md5( $sql );
      $container_id .= "_" .  rand( 0 , 99999999);
      $pagify_overide["container"] = $container_id;
      $data = $this->pagify_query_inner($sql, $query_overide, $pagify_overide);
      if( $data != "no data"){
      
      $return = "<div id='$container_id'>";
      $return.= $data;
      $return .= "</div>";
      return $return ;
      } else {
          return $data ;
      }
  }
  function get_sort_table( $table_id , $user_id ){
      $result = $this->db->query("SELECT * FROM page_tablecolumn WHERE sort <> '' AND table_id = '$table_id' AND ( user_id = '0' OR user_id = '$user_id' ) ORDER BY user_id DESC");
      if(mysql_num_rows($result)!= 0 ){
          $data = $this->db->fetch_assoc($result);
          return $data;
      } else {
          return false;
      }
      
  }
  function pagify_query_inner( $sql , $query_overide=array() , $pagify_overide=array()   ){
      ob_start();
      $options["page"] = "1";
      $options["per_page"] = "15";
      $options['pre_sort'] = '';
      $options["link"] = '';
      $options["container"] = 'pagify_query';
      $options["table_id"] = 'none';
      $options["pre_number_html"] = '';
      $options['return_no_data'] = true;
      //CTLTODO: Change;
      $options["user_id"] = "1";
      foreach( $pagify_overide as $n => $v ){
          $options[$n] = $v;
      }
      if( $options['table_id'] != 'none'){
           $qty_pp = $this->get_setting($_SESSION['user_id'] , 'results_per_page_' . $options["table_id"] );
//          $qty_pp = $this->get_setting($_SESSION['user_id'] , 'result_per_page_' . $options['table_id'] );
          if( $qty_pp != '' ){ $options["per_page"] = $qty_pp; }
      } 
//      var_dump( $options);
//      echo "<br/>Qty: $qty_pp<br>";
      $sort_data = $this->get_sort_table($options["table_id"], $options["user_id"]);
      $sort_options= unserialize($sort_data['options']);
//      print_r($sort_options); 
      if( is_array($sort_data) ){
          
          if(array_key_exists("order_by" , $sort_options)){
              $st_cn = $sort_options['order_by'];
          } else {
              $st_cn = $sort_data["column_name"];
          }
          $order_by = "ORDER BY " . $options['pre_sort'] . $st_cn . " " . $sort_data["sort"];
      } else {
          $order_by = '';
      }
      $s_arr = array();
      $s_arr["sql"] = $sql;
      $sql = str_replace("SELECT ", "SELECT SQL_CALC_FOUND_ROWS ", $sql);
      $limit = ( $options["page"] * $options["per_page"] ) - $options["per_page"];
      $limit_query = "$order_by LIMIT $limit , " . $options["per_page"];
      $sql .= $limit_query;
      $result = $this->db->query($sql);
      $tr_ar = $this->db->fetch_assoc($this->db->query("SELECT FOUND_ROWS() fr"));
      if( $this->db->num_rows($result) == 0 && $options['return_no_data'] == true ){
          echo "no data";
      } else {
         
        $total_rows = $tr_ar["fr"];
        if( $total_rows == 0 ){
            $new_sql = str_replace("SELECT ", "SELECT count(*) new_sql_count, ", $s_arr["sql"]);
            $new_total_rows = $this->db->fetch_assoc($this->db->query($new_sql));
            $total_rows = $new_total_rows['new_sql_count'];
        }
        //print_r($total_rows);
        $array = array();
        while( $row = $this->db->fetch_assoc($result) ){
            $array[] = $row;
        }
        $md5_hash = md5( $sql );
        echo $options["pre_number_html"] . "  <div id='page_$md5_hash' class='pages' ><span class='pagify'>Page</span>";
        $page_count = ceil( $total_rows / $options["per_page"] );
        $page = 1;


        $s_arr["query_overide"] = $query_overide;
        $s_arr["pagify_overide"] = $options;
        $serial_data = serialize($s_arr);
//        $serial_data = $this->encrypt($serial_data, 'iBhD2iIPPRQ4V8Cb8RVvHzOne2LRMY8BSJinlbNhg6uQDAA96PRaooDfiEnJ73N');
        $pagify_id = md5( rand( 0 , 9999999999999999999) );
        $_SESSION['pagify_sd_' . $pagify_id] = $serial_data;
        $session = 'pagify_sd_' . $pagify_id;
//        echo "$page:$page_count:$total_rows:" . $options["per_page"] . ":$sql";
        while( $page <= $page_count){
            if( $page == $options["page"]){ $class = "active"; } else { $class = "inactive"; }
            echo "<span class='pagify_page pagify_page_$class' onclick=\"page.phplivex_pagify( '$session' , '$page' , { target: '" . $options["container"] . "'} );\" ><a class='pagify_page pagify_page_$class' >$page</a></span>";
            $page++;
        }
        echo "</div><div id='pagify'>";
        //echo $pages;

        echo $this->table_from_array($array, $query_overide , $options);
        echo "</div>";
      }
      $html=ob_get_contents();
      ob_end_clean();
      return $html ;
      
  }
  function reset_all_page_tablecolumn(){
      $this->db->query("DELETE FROM page_tablecolumn WHERE user_id <> '0'");
  }
  function reset_my_page_tablecolumn(){
      $this->db->query("DELETE FROM page_tablecolumn WHERE user_id = '" . $_SESSION['user_id'] . "'");
  }
  function reset_user_page_tablecolumn(){
      $this->db->query("DELETE FROM page_tablecolumn WHERE user_id = '" . $_SESSION['user_id'] . "'");
  }
  function array_to_searchbar($array , $overide=array() ){
      $options = array();
      $options["search_name"] = "a_default_search";
      $options["columns"] = "3";
      $options["class"] = "default_searchbar";
      $options["js_object"] = $options["search_name"];
      $options["run_on_update"] = '';
      $options["table_id"] = "none";
      foreach( $overide as $n => $v){
          $options[$n] = $v;
      }
      $scripts = array();
      ob_start();

      $col = 0;
      ?>

<div class="<?php echo $options["table_id"]; ?>_search_header search_header">
       <table  ><tr><?php
      foreach( $array as $name => $array_options ){
          if( $col == $options["columns"] ){
              $cs = 1;
              ?></tr>
          <tr><?php
          $col = 0;
          }
          if(array_key_exists("show_name", $array_options) == false || $array_options["show_name"] != "no" ){
            ?>
              <td  class="<?php echo $options["table_id"]; ?>_search_td search_td search_td_label" ><span class="<?php echo $options["table_id"]; ?>_search_span search_span"><?php echo $array_options["name"] . ": </td>";
          } else {
              $cs++;
          }
          echo "<td colspan='$cs' >";
          include 'modules/display/searchbar.' . $array_options["type"] . ".php";
          ?></span></td><?php
          $col++;
      }
      while( $col != $options["columns"] ){
          echo "<td colspan='2' ></td>";
          $col++;
      }
      
      ?></tr></table><br/>
      <?php 
      if($options["table_id"] != "none"){
          $options["target"] = $options["table_id"] .'_search_field';
          ?>
      <script>
      <?php echo $options["table_id"]; ?>_search_json=<?php echo json_encode($options); ?>;
         function set_column_sliders(){
             $('.sortable').sortable({ update: slimcrm.columnsort });
             $('.search_slider').each( function(){ 
                                
                           
             var id_arr = $(this).attr('id').split('-');
                                var name = id_arr[2];
                                var width = id_arr[3];
                                $(this).slider(
                                { 
                                    min: 0 , 
                                    max: 200 , 
                                    value: width,
                                    //TMPHOLD
                                    change: function( event , ui ){
                                        slimcrm.tmpui = ui;
                                        page.set_column_settings( 
                                            '<?php echo $options["table_id"]; ?>' , 
                                            page_object.user_id , 
                                            name , 
                                            'width' , 
                                            ui.value , 
                                            { 
                                                target: '<?php echo $options["target"];?>'
                                            } 
                                            , { 
                                                target: '<?php echo $options["target"];?>' ,  
                                                onUpdate: function( root , response ){ 
                                                    refresh_search(); 
                                                    set_column_sliders()
                                                } 
                                              } 
                                         );
                                            
                                            
                                        //alert( name );
                                    }
                                });
                      }); 
         } 
         
        </script>
      <div class="<?php echo $options["table_id"]; ?>_search_options search_options" >
          <span class="<?php echo $options["table_id"]; ?>_search_span search_span">
              <a onclick="
                  $('#<?php echo $options["table_id"]; ?>_search_field').toggle();
                  page.edit_column_settings( '<?php echo $options["table_id"]; ?>' , page_object.user_id ,  <?php echo $options["table_id"]; ?>_search_json , 
                    { target: '<?php echo $options["table_id"]; ?>_search_field' , 
                        onUpdate: function( response , root ){ 
                            $('.sortable').sortable({ update: slimcrm.columnsort });
                            set_column_sliders()
                            
                        } 
                     }  );
                    
  
                 ">Search Options</a></span>
          <div id="<?php echo $options["table_id"]; ?>_search_field" class="<?php echo $options["table_id"]; ?>_search_options_field search_options_field" >
              
                 </div><span class="csv_export_span" ><a onclick="slimcrm.download_csv('<?php echo $options["table_id"]; ?>' , <?php echo $options["js_object"]; ?>)" >CSV Export</a></span>
      </div>

                 
      <?php
      }
      ?>
<!--      <a onclick="slimcrm.download_csv('<?php echo $options["table_id"]; ?>' , <?php echo $options["js_object"]; ?>)" >CSV EXPORT</a>-->
</div>

          <?php
      
      
      
      $html=ob_get_contents();
      ob_end_clean();
 ob_start();
 ?>
    <script>
        var <?php echo $options["js_object"];?> = new Object;
        function run_on_start(){
            <?php 
            foreach( $scripts as $script ){
                echo $script . "\n";
            }
            ?>
        }
        function refresh_search(){
             <?php echo str_replace(array("#RESULTS#","#JS_OBJECT#") , array( $options["js_object"] , $options["js_object"] ) , $options["run_on_update"]); ?>
        }
    function <?php echo $options["js_object"];?>_update_object(name , value){
        
        <?php echo $options["js_object"];?>[name] = value;
        <?php echo str_replace( array("#RESULTS#","#JS_OBJECT#") , array( $options["js_object"] , $options["js_object"] ), $options["run_on_update_only"]); ?>
        <?php echo str_replace( array("#RESULTS#","#JS_OBJECT#") , array( $options["js_object"] , $options["js_object"] ), $options["run_on_update"]); ?>
        
    }
    
    
    </script>
<?php
          $js=ob_get_contents();
      ob_end_clean();
      return $js . $html;
  }
  function set_sort_column( $table_id , $user_id , $column_name , $setting , $value , $overide=array()){
      $sql = "SELECT * FROM page_tablecolumn WHERE user_id='0' AND table_id = '$table_id' AND column_name = '$column_name'";
      $check_sortable = $this->db->fetch_assoc($this->db->query($sql));
      if( $check_sortable['sortable'] == 1 ){
        $result = $this->db->query("SELECT * FROM page_tablecolumn WHERE user_id='$user_id' AND table_id = '$table_id' AND sort <> ''");
        $set = false;
        //Set the curent sort to off, if not the current column
        if( mysql_num_rows($result) != 0 ){

            while( $info = $this->db->fetch_assoc($result)) {
                if( $info["column_name"] != $column_name ){
                    $this->db->query("UPDATE page_tablecolumn SET sort = '' WHERE page_tablecolumn_id = '" . $info['page_tablecolumn_id'] . "'");
                } else {
                    switch($info["sort"]){
                        default:
                            $this->db->query("UPDATE page_tablecolumn SET sort = 'ASC' WHERE page_tablecolumn_id = '" . $info['page_tablecolumn_id'] . "'");
                            $set = true;
                        break;
                        case "ASC":
                            $this->db->query("UPDATE page_tablecolumn SET sort = 'DESC' WHERE page_tablecolumn_id = '" . $info['page_tablecolumn_id'] . "'");
                            $set = true;
                        break;

                        }
                }

            }
        }

        if( $set === false ){
            $result3 = $this->db->query("SELECT * FROM page_tablecolumn WHERE user_id='$user_id' AND table_id = '$table_id' AND column_name = '$column_name'");
            if( mysql_num_rows($result3) != 0 ){
                $info = $this->db->fetch_assoc($result3);
                $this->db->query("UPDATE page_tablecolumn SET sort = 'ASC' WHERE page_tablecolumn_id = '" . $info['page_tablecolumn_id'] . "'");
            } else {
                $result4 = $this->db->query("SELECT * FROM page_tablecolumn WHERE user_id='0' AND table_id = '$table_id' AND column_name = '$column_name'"); 
                $info = $this->db->fetch_assoc($result4);
                unset( $info['page_tablecolumn_id']);
                $info["sort"] = 'ASC';
                $info["user_id"] = $user_id;
                $this->db->insert('page_tablecolumn', $info);
            }
        } 
      }
      return  $this->edit_column_settings( $table_id , $user_id , $overide );    
  }
  function set_bulk_order_settings($array ){
//      ksort($array );
      $x = 1;
      foreach( $array as $n => $v ){
          $exp = explode('-' , $v);
          if( count($exp) == 3 && is_numeric($n) ){
            $this->set_column_settings( $exp[1] , $_SESSION['user_id'] , $exp[0] , 'priority' , $x , array('return' => false ) );
            $x++;
          }
      }
      return print_r( $array , true );
  }
  
  function set_column_settings( $table_id , $user_id , $column_name , $setting , $value , $overide=array()){
      $options = array();
      $options['return'] = true;
      foreach($overide as $n => $v ){
          $options[$n] = $v;
      }
      if( $setting == 'display_column'){
      if( $value == 'undefined'){
          $value = false;
          }
          if( $value == 'checked'){
              $value=true;
          }
      }
      $result = $this->db->query("SELECT * FROM page_tablecolumn WHERE user_id='$user_id' AND table_id = '$table_id' AND column_name = '$column_name'");
      if(mysql_num_rows($result) >= 1 ){
          $info = $this->db->fetch_assoc($result);
          $array = unserialize($info["options"]);
          $new_array = $array["$setting"] = $value;
          $update = array();
          $update["options"] = serialize($array);
          $this->db->update("page_tablecolumn", $update, 'page_tablecolumn_id', $info["page_tablecolumn_id"]);
      } else {
          $result = $this->db->query("SELECT * FROM page_tablecolumn WHERE user_id='0' AND table_id = '$table_id' AND column_name = '$column_name'");
          $info=$this->db->fetch_assoc($result);
          $insert = array();
          $insert['user_id'] = $user_id;
          $insert['table_id'] = $table_id;
          $insert['column_name'] = $column_name;
          $array=unserialize($info["options"]);
          $array[$setting] = $value;
          $insert['options'] = serialize($array);
          $insert['sort'] = $info['sort'];
          $insert['sortable'] = $info['sortable'];
          $this->db->insert('page_tablecolumn', $insert);
          
      }
      if( $options['return'] == true ){
        return $this->edit_column_settings( $table_id , $user_id , $overide );
      }
  }
  
  
  
  function edit_column_settings( $table_id , $user_id , $overide=array() ){
      $options["target"] = '';
      $options["run_on_update"] = '';
      foreach($overide as $n => $v){
          $options[$n] = $v;
      }
      ob_start();
            $table_arr = array();
      $result = $this->db->query("SELECT * FROM page_tablecolumn WHERE table_id = '$table_id' AND user_id = '0'");
      while( $row = $this->db->fetch_assoc($result)){
          $table_arr[$row["column_name"]] = $row;
      }
      $result = $this->db->query("SELECT * FROM page_tablecolumn WHERE table_id = '$table_id' AND user_id = '$user_id'");
      while( $row = $this->db->fetch_assoc($result)){
          $table_arr[$row["column_name"]] = $row;
      }
      ?>
    <ul style="position:absolute; top: 8px; right: 8px;"  class="ui-widget ui-helper-clearfix" >
        <li  class="ui-state-default ui-corner-all" title="Close Search Options" onclick="$(this).parent().parent().hide();" >
    <span  class="ui-icon ui-icon-circle-close" ></span>
        </li >
    </ul>
    <table style="width: 100%">
        <tr><td style="text-align: left;"><span title="Qtys over 30 may slow results">Search Results per page</span></td><td style="text-align: right;">
            <?php $tmpqty = $this->get_setting($_SESSION['user_id'] , 'results_per_page_' . $table_id ); 
            if( $tmpqty == '' )
                { 
                    $tmpqty = '30';
                } 
            ?><input style="width: 40px;" value="<?php echo $tmpqty; ?>" onchange="page.set_setting( '<?php echo $_SESSION['user_id']; ?>' , '<?php echo 'results_per_page_' . $table_id ; ?>' , $(this).val() , { onUpdate: function(result,root){refresh_search();}})" ></td></tr>
        <tr><td style="text-align: left;">column_title</td><td style="text-align: right;" >Column Name</td><td>Dsp</td><td></td></tr>
    </table>
    <ul class="sortable" id="sortable_<?php echo $table_id; ?>" >
        <?php
        $ordered_arr = array();
        $tmp_order = 1000;
        foreach( $table_arr as $name => $value){
            if( $value['sort'] != '' && $value['user_id'] == 0 ){
                $res1 = $result = $this->db->query("SELECT * FROM page_tablecolumn WHERE user_id='$user_id' AND table_id = '$table_id' AND sort <> ''");
                if( count( $res1 != 0 )){
                    $value['sort'] = '';
                }
            } 
            $data = unserialize($value["options"]);
            $dn = '';
            if(array_key_exists("name", $data)){
                $dn = $data["name"];
            } else {
                $dn = $name;
            }
            $width = 100;
            if( array_key_exists('width' , $data )){
                $width= $data['width'];
            }
            if(array_key_exists("display_column", $data)){
                if($data["display_column"] == false ){
                    $show = '';
                } else {
                    $show='CHECKED';
                }
            } else {
                $show='CHECKED';
            }
            $pre_arr = array( 'sortable' => $value["sortable"] , 'sort' => $value["sort"] ,  'name' => $name , 'show' => $show , 'dn' => $dn , 'id' => $value['page_tablecolumn_id'] , 'width' => $width, 'data' => $data );
            if( array_key_exists("priority", $data) == true ){
                if( array_key_exists( $data["priority"] , $ordered_arr) == false ){
                    $ordered_arr[$data["priority"]] = $pre_arr;
                } else {
                    $ordered_arr[$tmp_order] = $pre_arr;
                    $tmp_order++;
                }
            } else {
                    $ordered_arr[$tmp_order] = $pre_arr;
                    $tmp_order++;
            }
            
            
            
        }
        ksort( $ordered_arr );
        foreach( $ordered_arr as $n => $v ){
            $name = $v['name'];
            $show = $v['show'];
            $data = $v['data'];
            
            $width = $v['width'];
            $sort = $v['sort'];
            $sortable = $v['sortable'];
            $dn = $v['dn'];
            //TMPHOLD
            ?>
        
            <li class="ui-state-default" id="<?php echo $name; ?>-<?php echo $table_id . "-sortli"; ?>" >
                <table style="width: 100%;"><tr><td style="text-align: left;width: 40%;overflow:hidden;" ><a title='<?php echo $v['data']['dataformat']; ?>'><?php echo $name; ?></a></td>
                        <td style="text-align: right;" >
                            <input onchange="page.set_column_settings( '<?php echo $table_id;?>' , page_object.user_id , '<?php echo $name;?>' , 'name' , $(this).val() , { target: '<?php echo $options["target"];?>'} , { target: '<?php echo $options["target"];?>' ,  onUpdate: function( root , response ){ refresh_search();set_column_sliders(); } } );" value="<?php echo $dn; ?>" /></td>
                        <td><input type="checkbox" <?php echo $show;?> onchange="page.set_column_settings( '<?php echo $table_id;?>' , page_object.user_id , '<?php echo $name;?>' , 'display_column' ,$(this).attr('checked'), { target: '<?php echo $options["target"];?>'}, { target: '<?php echo $options["target"];?>' , onUpdate: function( root , response ){ refresh_search();set_column_sliders(); }} );" ></td>
                        <td><?php if( $sortable ){ ?>
                            <div 
                                onclick="page.set_sort_column( '<?php echo $table_id;?>' , page_object.user_id , '<?php echo $name;?>' , 'name' , '' , { target: '<?php echo $options["target"];?>'} , { target: '<?php echo $options["target"];?>' ,  onUpdate: function( root , response ){ refresh_search();set_column_sliders(); } } );" 
                                class="search_options_sort<?php echo $sort;?>"  >&nbsp;</div>
                                <?php } else { ?>
                            <div style="width: 15px;" >&nbsp;</div>
                            <?php } ?>
                        </td>
                        <td style="width: 100px;"><div id="search-slider-<?php echo $name; ?>-<?php echo $width; ?>" class="search_slider" ></div></td>
                    </tr></table></li>   
            <?php 
        }
        ?>     
    </ul>
    <?php
      $html=ob_get_contents();
      ob_end_clean();
      return $html;
  }
  
  
  function array_to_table($array , $overide=array() , $option_overide = array()){
      return $this->table_from_array($array , $overide=array() , $option_overide );
  }
  
  function table_from_array( $array , $overide=array() , $option_overide = array()  ){
     
      $default_column_options = array();
      $default_column_options["display_column"] = true;
      $default_column_options["dataformat"] = "none";
      $options=array();
      $options["table_id"] = "none";
      $options["auto_tablesort"] = "true";
      $options["add_class"] = '';
      $options['pagify'] = 'no';
      $options['return_csv'] = false;
      $options['js_run_object'] = '';
      $options["user_id"] = $_SESSION['user_id'];
      $options['var_dump'] = false;
      $options['autowidth'] = true;
      $options["run_on_update_only"] = '';
      $options["run_on_update"] = '';
      $total_column_width=0;
      foreach( $option_overide as $n => $v){
          $options[$n] = $v;
      }
      $sort_arr = array();
      $sortable_arr = array();
      if( $options["table_id"] != "none"){
          $column_options = array();
          $result=$this->db->query("SELECT * FROM page_tablecolumn WHERE user_id = '0' AND table_id = '" .$options["table_id"]. "'");
          while($row=$this->db->fetch_assoc($result)){
              $column_options[$row["column_name"]] = unserialize($row["options"]);
              $sort_arr[$row["column_name"]] = $row['sort'];
              $sortable_arr[$row["column_name"]] = $row['sortable'];
          }
          $result=$this->db->query("SELECT * FROM page_tablecolumn WHERE user_id = '" .$options["user_id"]. "' AND table_id = '" .$options["table_id"]. "'");
          while($row=$this->db->fetch_assoc($result)){
              $column_options[$row["column_name"]] = unserialize($row["options"]);
              $sort_arr[$row["column_name"]] = $row['sort'];
              $sortable_arr[$row["column_name"]] = $row['sortable'];
          }
          $overide["column_options"] = $column_options;
          $overide['ran_query'] = 'yes' ." SELECT * FROM page_tablecolumn WHERE user_id = '" .$options["user_id"]. "' AND table_id = '" .$options["table_id"]. "'" ;
      }
      
      
      
      if(array_key_exists('column_options', $overide)){
          foreach( $overide['column_options'] as $n => $v ){
                  $display = true;
                  if(array_key_exists('display_column', $v )){
                      if( $v['display_column'] == false ){
                          $display = false;
                      }
                  }
              
              if(array_key_exists('width', $v )){

                  if( $display == true ){
                    $total_column_width = $total_column_width + $v['width'];
                  }
              } else {
                  if( $display == true ){
                    $total_column_width = $total_column_width + 100;
                  }
              }
          }
      }
      
      
      $cn_options = array();
      ob_start();
      if($options['var_dump'] == true ){
          var_dump($options);
      }
      $cn = array();
      $column_to_joined = array();
      $joined_columns = array();
      if(array_key_exists("copy_columns", $overide)){
          foreach( $overide["copy_columns"] as $orig_name => $new_name ){
              foreach( $array as $key => $row ){
                  if(array_key_exists("$orig_name", $row )){
                      $array[$key][$new_name] = $array[$key][$orig_name];
                      //$total_column_width = $total_column_width + 100;
                  }
              }
          }
      }
      $rand= rand(0 , 999999999999999 );
      if(array_key_exists("joined_columns", $overide)){
          foreach( $overide["joined_columns"] as $jcn => $jcarr ){
              $joined_columns["$jcn"] = $jcarr; // This makes it so I dont have to constantly do if( array_key_exsits( "joined_columns" , $overide );
             foreach( $jcarr as  $sub_jcv ){
                 $column_to_joined["$sub_jcv"] = $jcarr;
             } 
          }
      }
      
      $header_presort = array();
      $tmp_priority = 0;
?>
    
<table id="<?php echo $options["table_id"]; ?>" class="event_form small_text tablesorter<?php echo $rand;?> <?php echo $options['add_class'];?>" width="100%">
    <thead class="<?php echo $options['add_class'];?>" ><tr><?php
    $col_ct = 0;
    $sort = array();
    foreach( $array[0] as $name => $value ){
        $tmp_op = $default_column_options; //default_options
        $tmp_op['orig_name'] = $name;
        $tmp_op["name"] = $name;
        
        if(array_key_exists("column_options", $overide)){
            if(array_key_exists("$name", $overide["column_options"])){
                foreach($overide["column_options"]["$name"] as $con => $cov ){
                    $tmp_op[$con] = $cov;
                }
            }
        }
        if( $tmp_op["display_column"] == true && array_key_exists("$name", $joined_columns ) == false  ){
            $pr = $tmp_priority;
            $tmp_priority++;
            $tmp_op['orig_name'] = $name;
            if(array_key_exists('priority', $tmp_op)==true){
                $pr = $tmp_op['priority'] * 1000;
                $header_presort[ $pr + $tmp_priority ] = $tmp_op;
            } else {
                $header_presort[ 50000 + $tmp_priority ] = $tmp_op;
            }
            /*
            $cn[] = $name;            
            echo "<th>" . $tmp_op["name"] . "</th>\n";
            $cn_options["$name"] = $tmp_op;
            if(array_key_exists("sort", $tmp_op ) ){
                $sort[] = array('col'=>$col_ct , 'order' => $tmp_op['sort']);
            }
            $col_ct++;
            */
        }
    }
    foreach( $joined_columns as $jc_name => $jc_arr ){
        $tmp_op = $default_column_options; //default_options
        $tmp_op["name"] = $jc_name;
        $tmp_op['orig_name'] = $jc_name;
        if(array_key_exists("column_options", $overide)){
            if(array_key_exists("$jc_name", $overide["column_options"])){
                foreach($overide["column_options"]["$jc_name"] as $con => $cov ){
                    $tmp_op[$con] = $cov;
                }
            }
        }
        if( $tmp_op["display_column"] == true   ){
            $pr = $tmp_priority;
            $tmp_priority++;
            //$tmp_op['orig_name'] = $name;
            if(array_key_exists('priority', $tmp_op)==true){
                $pr = $tmp_op['priority'] * 1000;
                $header_presort[ $pr + $tmp_priority ] = $tmp_op;
                //$header_presort[ $tmp_op['priority'] + $tmp_priority ] = $tmp_op;
            } else {
                $header_presort[ 50000 + $tmp_priority ] = $tmp_op;
            }
        }        
    }
    //var_dump( $joined_columns);
    ksort($header_presort);
    $arr_headers = array();
    $arr_body = array();
    $used_columns = array();
    foreach( $header_presort as $n => $column ){
       // $name =  "<table><tr><td>";
            $name = $column['orig_name'];
//            if(array_key_exists($name, $used_columns)== false ){
                $used_columns[$name] = $name;
                $cn[] = $name;    
                $headers[$name] = $column["name"];
                ob_start();
                 if( $sortable_arr[$name] == 1 ){ ?>
                <div 
                    onclick="" 
                    class="search_options_sort<?php echo $sort_arr[$name];?>" style="display: inline-block;height: 15px !important;"  >&nbsp;</div>
                    <?php } else { ?>
                <div style="width: 15px;display: inline-block;height: 15px !important;float: left;" >&nbsp;</div>
                <?php } 
                $div = ob_get_contents();
                ob_end_clean();
                
                $style_tmp="";
                $tmp_click = "";
                 if( $sortable_arr[$name] == 1 ){
                     $tmp_click = "page.set_sort_column( '" . $options['table_id']  . "' , page_object.user_id , '" .  $name . "' , 'name' , '' , { target: 'none'} , {  onUpdate: function( root , response ){ refresh_search();set_column_sliders(); } } );";
                     switch($sort_arr[$name]){
                         default:
                             $style_tmp = 'cursor:pointer;background-image: url("tablesort/themes/blue/bg.gif");background-position: 100% 50%;background-repeat: no-repeat;';
                         break;
                         case "ASC":
                             $style_tmp = 'cursor:pointer;background-image: url("tablesort/themes/blue/desc.gif");background-position: 100% 50%;background-repeat: no-repeat;';
                         break;
                     case "DESC":
                             $style_tmp = 'cursor:pointer;background-image: url("tablesort/themes/blue/asc.gif");background-position: 100% 50%;background-repeat: no-repeat;';
                         break;  
                     }
                     
                     } 
                
                $inside = "<table style='width: 100%;margin: 0px;padding:0px;'><tr style='margin: 0px;padding:0px;'><td>" . $column["name"] . "</td><td style='text-align: right; align: rigth;width: 16px;margin: 0px;padding:0px;' >$div</td></tr></table>";
                echo "<th onclick=\"$tmp_click\" style='$style_tmp' class='" . $options['add_class'] . " " . $options['add_class'] . "$name table_$name' >" . $column["name"] . "
                        </th>\n";
                $cn_options["$name"] = $column;
                if(array_key_exists("sort", $column ) ){
                    $sort[] = array('col'=>$col_ct , 'order' => $column['sort']);
                }
                $col_ct++;
//            }
    }
//    var_dump( $header_presort );
    
    ?></tr></thead>
    <tbody class="<?php echo $options['add_class'];?>" >
        <?php
        $x = "odd";
        foreach( $array as $line ){
            
            //$pagify_options['add_class'] = 'bucket_search_%overdue%';
            $class = $options["add_class"];
            foreach( $line as $n => $v){
                $class = str_replace('%' . $n . '%', $v , $class);
            }
            echo "<tr class='$class' >";
             $tmp_arr = array();
            foreach( $cn as $col ){
                //$total_column_width;
                if(array_key_exists('width', $cn_options[$col])){
                    $width = floor( ( $cn_options[$col]['width'] / $total_column_width ) * 100 );
                } else {
                    $width = floor( ( 100 / $total_column_width ) * 100 );
                }
                if( $options["auto_tablesort"] != "true"){ $eo_class = "table_$x $class" . "_$x"; } else { $eo_class = '';}
                echo "<td  class='$class $eo_class' style='";
                
                if($options['autowidth'] == true ){
                    echo "width: $width%;tmp_cn_option: " . $cn_options[$col]['width'] . ";total_col_width: $total_column_width;";
                }
                echo "' >";
                ob_start();
                if(array_key_exists($col, $line)){
                    echo $this->format_data($cn_options[$col]["dataformat"], $line[$col] , array( 'return_csv' => $options['return_csv'] , 'line' => $line ) );
                } elseif(array_key_exists($col, $joined_columns)) {
                    $value = array();
                    foreach( $joined_columns[$col] as  $jc_val ){
                        if(array_key_exists($jc_val, $line)){
                            $value[] = $line[$jc_val];
                        } else {
                            $value[] = ''; // We require this so it always gives the same data to dataformat
                        }
                    }
                    $data = implode(":|:", $value );
                    echo $this->format_data($cn_options[$col]["dataformat"], $data , array( 'return_csv' => $options['return_csv']));
                }
//                var_dump($options);
//                echo "<br/>\n";
                $tmp_str = ob_get_contents();
                ob_end_clean();
                echo $tmp_str;
                if( $tmp_str == '' && $options['return_csv'] == false ){ echo "&nbsp;"; }
                $tmp_arr[$col] = str_replace(array("\r", "\r\n", "\n"), '', $tmp_str );
                echo "</td>";
            }
            
            $arr_body[] = $tmp_arr;
            echo "</tr>";
            if( $x == "even" ){ $x = "odd"; } else { $x = "even"; }
        }
        ?>
    </tbody>




</table>
     <?php if($options["auto_tablesort"] == "true"){
          
          ?>

<script language="javascript" type="text/javascript">
	$(function() {	
            <?php 
                if( count( $sort ) != 0 ){
                    $s2 = array();
                    foreach( $sort as $row ){
                        if( strtolower( $row['order'] ) == 'desc'){
                            $s2[] = "[".$row['col'].",1]";
                        } else {
                            $s2[] = "[".$row['col'].",0]";
                        }
                    }
                    $sortList = "[" . implode(",", $s2). "]";
                } else {
                    $sortList = "[[0,0]]";
                }
            ?>
            setTimeout('$(".tablesorter<?php echo $rand;?>").tablesorter({widthFixed: false, widgets: ["zebra"], sortList: <?php echo $sortList; ?> } );',250);
	});
</script>
 <?php } ?>
      
 <?php     $html=ob_get_contents();
		ob_end_clean();
                if( $options['return_csv'] == true ){
                    return array( 'body' => $arr_body , 'head' => $arr_headers , 'array' => $array );
                }
                
		return $html;
  }
  function setAccessRules($rule)
  {
  	$this->access_rules = $rule;
  }
  
  function setAccessRulesType($type)
  {
  	$this->access_rules_type = $type;
  }

  // sets the meta-description for the new page
  function setPageDescription($description)
  {
	$this->page_description = $description;
  }

  // sets the page title for the new page
  function setPageTitle($title)
  {
    $this->page_title = $title;
  }

  // sets the inner nav of selected page
  function setInnerNav($type)
  {
    $this->inner_nav = $type;
  }

  // sets active button for navagation
  function setActiveButton($ab)
  {
	$this->active_button = $ab;
  }
  function setImportCss( $css ){
      $this->css[] = $css;
  }
  // sets imported css.  #1 is the main_style.css
  function setImportCss1($css_1)
  {
    $this->css1 = $css_1;
  }
  
  // sets next css import file
  function setImportCss2($css_2)
  {
    $this->css2 = $css_2;
  }

  // sets next css import file
  function setImportCss3($css_3)
  {
    $this->css3 = $css_3;
  }

  // sets next css import file
  function setImportCss4($css_4)
  {
    $this->css4 = $css_4;
  }

  // sets next css import file
  function setImportCss5($css_5)
  {
    $this->css5 = $css_5;
  }
  
  // sets next css import file
  function setImportCss6($css_6)
  {
    $this->css6 = $css_6;
  }
  
    // sets next css import file
  function setImportCss7($css_7)
  {
    $this->css7 = $css_7;
  }
  
    // sets next css import file
  function setImportCss8($css_8)
  {
    $this->css8 = $css_8;
  }
  
    // sets next css import file
  function setImportCss9($css_9)
  {
    $this->css9 = $css_9;
  }
  
    // sets next css import file
  function setImportCss10($css_10)
  {
    $this->css6 = $css_10;
  }
  
    // sets next css import file
  function setImportCss11($css_8)
  {
    $this->css11 = $css_8;
  }
  
    // sets next css import file
  function setImportCss12($css_9)
  {
    $this->css12= $css_9;
  }
  
    // sets next css import file
  function setImportCss13($css_10)
  {
    $this->css13 = $css_10;
  }	
    
	
	
	
	
	
	// sets next css import file
  function SetDynamicCSS_1($css_10)
  {
    $this->dynamic_css1 = $css_10;
  }	
    // sets next css import file
  function SetDynamicCSS_2($css_10)
  {
    $this->dynamic_css2 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_3($css_10)
  {
    $this->dynamic_css3 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_4($css_10)
  {
    $this->dynamic_css4 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_5($css_10)
  {
    $this->dynamic_css5 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_6($css_10)
  {
    $this->dynamic_css6 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_7($css_10)
  {
    $this->dynamic_css7 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_8($css_10)
  {
    $this->dynamic_css8 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_9($css_10)
  {
    $this->dynamic_css9 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_10($css_10)
  {
    $this->dynamic_css10 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_11($css_10)
  {
    $this->dynamic_css11 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_12($css_10)
  {
    $this->dynamic_css12 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_13($css_10)
  {
    $this->dynamic_css13 = $css_10;
  }	  
  
  
  
  
    // sets any additional css that the page requires
  function setPageStyle($style)
  {
	$this->page_style = $style;
  }

  function setExtJavaScripts($ext_custom_scripts)
  {
	$this->ext_java_scripts[] = $ext_custom_scripts;
  }
  // sets external java scripts that the page requires
  function setExtJavaScripts1($ext_custom_scripts)
  {
	$this->ext_java_scripts1 = $ext_custom_scripts;
  }

  function setExtJavaScripts2($ext_custom_scripts)
  {
	$this->ext_java_scripts2 = $ext_custom_scripts;
  }

  function setExtJavaScripts3($ext_custom_scripts)
  {
	$this->ext_java_scripts3 = $ext_custom_scripts;
  }

  function setExtJavaScripts4($ext_custom_scripts)
  {
	$this->ext_java_scripts4 = $ext_custom_scripts;
  }

  function setExtJavaScripts5($ext_custom_scripts)
  {
	$this->ext_java_scripts5 = $ext_custom_scripts;
  }

  function setExtJavaScripts6($ext_custom_scripts)
  {
	$this->ext_java_scripts6 = $ext_custom_scripts;
  }
  
  function setExtJavaScripts7($ext_custom_scripts)
  {
	$this->ext_java_scripts7 = $ext_custom_scripts;
  }
  
    function setExtJavaScripts8($ext_custom_scripts)
  {
	$this->ext_java_scripts8 = $ext_custom_scripts;
  }
  
    function setExtJavaScripts9($ext_custom_scripts)
  {
	$this->ext_java_scripts9 = $ext_custom_scripts;
  }
  
    function setExtJavaScripts10($ext_custom_scripts)
  {
	$this->ext_java_scripts10 = $ext_custom_scripts;
  }
  
    function setExtJavaScripts11($ext_custom_scripts)
  {
	$this->ext_java_scripts11 = $ext_custom_scripts;
  }
  
    function setExtJavaScripts12($ext_custom_scripts)
  {
	$this->ext_java_scripts12 = $ext_custom_scripts;
  }
  
    function setExtJavaScripts13($ext_custom_scripts)
  {
	$this->ext_java_scripts13 = $ext_custom_scripts;
  }  
  
      function setExtJavaScripts14($ext_custom_scripts)
  {
	$this->ext_java_scripts14 = $ext_custom_scripts;
  }
  
      function setExtJavaScripts15($ext_custom_scripts)
  {
	$this->ext_java_scripts15= $ext_custom_scripts;
  }
  
      function setExtJavaScripts16($ext_custom_scripts)
  {
	$this->ext_java_scripts16 = $ext_custom_scripts;
  }
  
      function setExtJavaScripts17($ext_custom_scripts)
  {
	$this->ext_java_scripts17 = $ext_custom_scripts;
  }
  
    // sets internal java scripts functions that the page requires
  function setCustomJavaScripts($custom_scripts)
  {
	$this->custom_java_scripts = $custom_scripts;
  }
	
  // sets any onLoad javascripts in <body> tag
  function setBodyScript($script)
  {
	$this->body_script = ' '.$script;
  }

  function enable_chat_head(){
      echo '        <script type="text/javascript" src="./js/dom.js"></script>
        <script type="text/javascript" src="./js/chat.js"></script>
        <script type="text/javascript" src="./js/jquery-1.5.1.min.js"></script>
        <script type="text/javascript" src="./js/jquery-ui-1.7.1.custom.min.js"></script>   

        <link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.13.custom.css" />
        <link rel="stylesheet" href="css/chat.css" type="text/css" />
        ';
  }
  function enable_chat_body(){
      echo "<script type='text/javascript' >
          var chat_module_name = \"TBL_USER\";
          var chat_module_id = '" . $_SESSION['user_id'] . "'
          chat_start();
          </script>";
  }
  
  function page_object(){
      ?>
    <script type='text/javascript' >
        var page_object = new Object;
        page_object.user_id = '<?php echo $this->auth->Get_user_id();?>';
    </script>
      <?php
  }
  
  function displayPageTop($full='' , $header = 'yes')
  {
    $this->printDocType();
	$this->printHTMLStart();
	$this->printHeadStart();
        //$this->enable_chat_head();
	$this->printCharEncod();
	$this->printTitle();
	$this->printMetaAuthor();
	$this->printMetaKeywords();
    $this->printMetaDesc();
	$this->printFOUC();
	$this->printMainStyle();
	//$this->printPageStyle();
        $this->page_object();
	$this->printExtJavaScripts();
	$this->printCustomJavaScripts();
	$this->printHeadEnd();
	$this->printBodyStart();
        //$this->enable_chat_body();
        if( $header == "yes"){
            $this->printHeader();
        }
        $this->printContentAreaStart();
	$this->printContentColumnStart($full);
  }

  function displayPageBottom()
  {
	$this->printInfoColumnEnd();
	$this->printFooter();
	$this->printContentAreaEnd();
	$this->printGoogAna();
	$this->printBodyEnd();
	$this->printHTMLEnd();
  }

  // display functions

  function printDoctype()
  {
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
  }
  
  function printHTMLStart()
  {
    echo '
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">';
  }
  
  function printHeadStart()
  {
    echo '
<head>';
  }
  
  function printCharEncod()
  {
    echo '
<meta http-equiv="X-UA-Compatible" content="IE=9" >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
  }
  
  function printTitle()
  {
    echo '
<title>'.$this->page_title.'</title>';
  }

  function printMetaAuthor()
  {
    echo '
<meta name="author" content="TimIvey.com" />';
  }
  
  function printMetaKeywords()
  {
    echo '
<meta name="keywords" content="'.$this->page_keywords.'" />';
  }

  function printMetaDesc()
  {
    echo '
<meta name="description" content="'.$this->page_description.'" />';
  }
    
  function printFOUC() // stops unstyled html from appear.  May be obsolete now.
  {
    echo '
';
  }
  
  function printExtJavaScripts()
  {
      foreach( $this->ext_java_scripts as $script ){
          echo $script . "\n";
      }
     if ( !empty($this->ext_java_scripts1) )
	 {
     	echo $this->ext_java_scripts1;
     }
     if ( !empty($this->ext_java_scripts2) )
	 {
     	echo $this->ext_java_scripts2;
     }
     if ( !empty($this->ext_java_scripts3) )
	 {
     	echo $this->ext_java_scripts3;
     }
     if ( !empty($this->ext_java_scripts4) )
	 {
     	echo $this->ext_java_scripts4;
     }
     if ( !empty($this->ext_java_scripts5) )
	 {
     	echo $this->ext_java_scripts5;
     }
	 if ( !empty($this->ext_java_scripts6) )
	 {
     	echo $this->ext_java_scripts6;
     }
	 if ( !empty($this->ext_java_scripts7) )
	 {
     	echo $this->ext_java_scripts7;
     }
	 if ( !empty($this->ext_java_scripts8) )
	 {
     	echo $this->ext_java_scripts8;
     }
	 if ( !empty($this->ext_java_scripts9) )
	 {
     	echo $this->ext_java_scripts9;
     }
	 if ( !empty($this->ext_java_scripts10) )
	 {
     	echo $this->ext_java_scripts10;
     }
	 if ( !empty($this->ext_java_scripts11) )
	 {
     	echo $this->ext_java_scripts11;
     }
	 if ( !empty($this->ext_java_scripts12) )
	 {
     	echo $this->ext_java_scripts12;
     }	 
	 if ( !empty($this->ext_java_scripts13) )
	 {
     	echo $this->ext_java_scripts13;
     }
	 if ( !empty($this->ext_java_scripts14) )
	 {
     	echo $this->ext_java_scripts14;
     }	 
	 if ( !empty($this->ext_java_scripts15) )
	 {
     	echo $this->ext_java_scripts15;
     }
	 if ( !empty($this->ext_java_scripts16) )
	 {
     	echo $this->ext_java_scripts16;
     }
	 if ( !empty($this->ext_java_scripts17) )
	 {
     	echo $this->ext_java_scripts17;
     }
}
 
  function printCustomJavaScripts()
  {
     if ( !empty($this->custom_java_scripts) )
	 {
     echo '
	<script language="javascript" type="text/javascript">'.
	$this->custom_java_scripts.'
	
	
	
	</script>';
	 }
	?>
	<script type="text/javascript">
function CheckCall( ext )
{
var cid;
try //Internet Explorer
  {
  xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
  }
catch(e)
  {
  try //Firefox, Mozilla, Opera, etc.
    {
    xmlDoc=document.implementation.createDocument("","",null);
    }
  catch(e)
    {
    alert(e.message);
    return;
    }
  }
xmlDoc.async=false;
xmlDoc.load("/asterisk/phone/checkcall.beta.php?ext=" + ext + "&s=YES&account=<?php echo ASTERISK_ACCOUNT_CODE;?>" );
cid=xmlDoc.getElementsByTagName("root")[0].childNodes[0].nodeValue;
return cid;
}

 function sleep(delay)
 {
     var start = new Date().getTime();
     while (new Date().getTime() < start + delay);
 }

function loopcheck_ext( ext ){
var cid;

	while( 0 < 1 ){
		cid = CheckCall( ext );
		if( cid != 'NONE' ){
			document.getElementById('phone_call').src='tab.php?phone='+cid;
		//	document.getElementById('phone_call').contentWindow.location.reload(true);
			document.getElementById('phone_call').style.display='';
                        setTimeout("loopcheck_ext(" + ext + ")",2500);
			break;
			
		} else {
                   document.getElementById('phone_call').style.display='none';
                        setTimeout("loopcheck_ext(" + ext + ")",2500);
			break;
                }
				
	}
}

	</script>
	<?
  }
  
  function printMainStyle()
  {
	// the first css are for the drop down navigation on the home page
	echo '
<style type="text/css" media="all">';
	//echo '@import url(css/ui-darkness/jquery-ui-1.8.18.custom.css);';
        echo '@import url(css/jqueryui-eapi/jquery-ui-1.8.18.custom.css);';
        foreach( $this->css as $css_import ){
            echo '@import url('.$css_import.');';
        }
    if ( !empty($this->css1) ) echo '@import url('.$this->css1.');';
    if ( !empty($this->css2) ) echo '@import url('.$this->css2.');';
    if ( !empty($this->css3) ) echo '@import url('.$this->css3.');';
    if ( !empty($this->css4) ) echo '@import url('.$this->css4.');';
    if ( !empty($this->css5) ) echo '@import url('.$this->css5.');';
	if ( !empty($this->css6) ) echo '@import url('.$this->css6.');';
	if ( !empty($this->css7) ) echo '@import url('.$this->css7.');';
	if ( !empty($this->css8) ) echo '@import url('.$this->css8.');';
	if ( !empty($this->css9) ) echo '@import url('.$this->css9.');';
	if ( !empty($this->css10) ) echo '@import url('.$this->css10.');';
	if ( !empty($this->css11) ) echo '@import url('.$this->css11.');';
	if ( !empty($this->css12) ) echo '@import url('.$this->css12.');';
	if ( !empty($this->css13) ) echo '@import url('.$this->css13.');';    
	echo '

</style>';
	if ( !empty($this->dynamic_css1) ) include($this->dynamic_css1);
	if ( !empty($this->dynamic_css2) ) include($this->dynamic_css2);
	if ( !empty($this->dynamic_css3) ) include($this->dynamic_css3);
	if ( !empty($this->dynamic_css4) ) include($this->dynamic_css4);
	if ( !empty($this->dynamic_css5) ) include($this->dynamic_css5);
	if ( !empty($this->dynamic_css6) ) include($this->dynamic_css7);
	if ( !empty($this->dynamic_css7) ) include($this->dynamic_css7);
	if ( !empty($this->dynamic_css8) ) include($this->dynamic_css8);
	if ( !empty($this->dynamic_css9) ) include($this->dynamic_css9);
	if ( !empty($this->dynamic_css10) ) include($this->dynamic_css10);
	if ( !empty($this->dynamic_css11) ) include($this->dynamic_css11);
	if ( !empty($this->dynamic_css12) ) include($this->dynamic_css12);
	if ( !empty($this->dynamic_css13) ) include($this->dynamic_css13);

  }
  
  function printPageStyle()
  {
    if ( !empty($this->page_style) )
	{
    	echo '
<style type="text/css">'.
$this->page_style.'
</style>';
     }
  }

  function printHeadEnd()
  {
    echo '
</head>';
  }

  function printBodyStart()
  {
    echo '
<body'.$this -> body_script.'>'; 
echo '<!--[if lte IE 6]><script src="ie6/warning.js"></script><script>window.onload=function(){e("ie6/")}</script><![endif]-->';
  }
  
  
  
  function menuLineString( $item ){
                 if ($this -> active_button == '1'){ 
                 $returnHtml .= '<li class="active">'; 
             } else {
                 $returnHtml .= '<li>';
             }
                if(array_key_exists("url", $item)){
                    $returnHtml .= '<a id=\'' . $item["id"] . '\' onclick="add_newtab(\'' . $item["url"] . '\', \'' . $item["name"] . '\');"><img style="width: 35px; height:35px" src="' . $item["image"] . '" /></a></li>';    
                } elseif( array_key_exists("script", $item) ){
                    $returnHtml .= '<a id=\'' . $item["id"] . '\' onclick="' . $item["script"] . '"><img style="width: 35px; height:35px" src="' . $item["image"] . '" /></a></li>';
                }
                  return $returnHtml;
      
  }
 /* 
  function menuFromArray( $array ){
      $returnHtml = '<ul id="main_navigation">';
      foreach( $array as $item ){
          if( $item["access"] == "ALL" || $this->auth->inGroup( $item["access"]) ){
              if ($this -> active_button == '1'){ 
                 $returnHtml .= '<li class="active">'; 
             } else {
                 $returnHtml .= '<li>';
             }
                if(array_key_exists("url", $item)){
                    $returnHtml .= '<a id=\'' . $item["id"] . '\' onclick="add_newtab(\'' . $item["url"] . '\', \'' . $item["name"] . '\');"><img style="width: 35px; height:35px" src="' . $item["image"] . '" /></a></li>';    
                } elseif( array_key_exists("script", $item) ){
                    $returnHtml .= '<a id=\'' . $item["id"] . '\' onclick="' . $item["script"] . '"><img style="width: 35px; height:35px" src="' . $item["image"] . '" /></a></li>';
                }
          } 
         

      }
  
      $returnHtml .= '</ul>';
      
      return $returnHtml;
  }
  */
    function page_link( $page , $vars=array() ){
        //$(\'.menu_bar\').removeClass(\'menu_bar_active\').addClass(\'menu_bar_inactive\');$(this).parent().removeClass(\'menu_bar_inactive\').addClass(\'menu_bar_active\');
        $op = array();
        foreach( $vars as $n => $v ){
            $op[] = "$n: '$v' ";
        }
        $item_class = preg_replace('/^[a-z0-9]/' , '' , strtolower($page) );
        $options = "{" . implode(",", $op) . "}";
        return '$(\'.menu_bar\').removeClass(\'menu_bar_active\').addClass(\'menu_bar_inactive\');$(\'.' . $item_class . '\').removeClass(\'menu_bar_inactive\').addClass(\'menu_bar_active\');dynamic_page.phplivex_page(\'' . $page . '\' ,' . $options . ' ,  { target: \'dynamic_main\' ' . $this->dynamic_page->phplivex_options($page) .  ' });';
    }
    function menuFromArray( $array ){
      $returnHtml = '<ul id="main_navigation">';
      $submenu = array();
      foreach( $array as $item ){
          if( $item["access"] == "ALL" || $this->auth->inGroup( $item["access"]) ){
              if(array_key_exists("submenu", $item) == false ){
                  if ( strtolower( $this -> active_button ) == strtolower( $item["name"] )){ 
                     $returnHtml .= '<li class="menu_bar menu_bar_active ' . preg_replace('/^[a-z0-9]/' , '' , strtolower($item['url']) ) . '">';
                     $tmpCss = "color: white !important;font: 13px/27px Arial,sans-serif !important; font-weight: 800 !important; ";
                     $tmpCss = '';
                 } else {
                     $returnHtml .= '<li class="menu_bar menu_bar_inactive menu_bar_' . $item["id"] . ' ' . preg_replace('/^[a-z0-9]/' , '' , strtolower($item['url']) ) . ' ">';
                     $tmpCss = "color: #cccccc !important;font: 13px/27px Arial,sans-serif !important;font-weight:400 !important;   ";
                     $tmpCss = '';
                 }
                    if(array_key_exists("url", $item)){
                        $returnHtml .= '<a style="' . $tmpCss . '" id=\'' . $item["id"] . '\' onclick="'. $this->page_link($item["url"]) . '" >' . $item["name"] . '</a></li>';    
                    } elseif( array_key_exists("script", $item) ){
                        $returnHtml .= '<a style="' . $tmpCss . '" id=\'' . $item["id"] . '\' onclick="' . $item["script"] . '">' . $item["name"] . '</a></li>';
                    }
              } else {
                  if( array_key_exists( $item["submenu"] , $submenu ) == false ){
                      $submenu[ $item["submenu"] ] = "";
                  }
                  $submenu[ $item["submenu"] ] .= "<div class='submenu_item' onclick='". $this->page_link($item["url"]) . " >" . $item["name"] . "</div>";
                  
              }
        } 
         

      }
  
//CTLTODO: Re-enable Submenu
//      $returnHtml .= '<li><img style="width: 16px; height: 16px;position: absolute; right: 40px; top: 8px;" onclick="window.location=\'signout.php\';" src="images/power_button.png" /><img onclick="$(' . "'" . '#submenu_settings' . "'" . ').toggle();" style="width: 16px; height: 16px;position: absolute; right: 15px; top: 8px;" src="images/white_cog.png" /></li></ul><div id="config_submenu" style="position: absolute; top: 34px; right: 0px; width: 200px; background: url(images/transparent_60.png); display: none;" >&nbsp;<div>TEST1</div><div>TEST1</div><div>TEST1</div>    </div>';
//      foreach( $submenu as $n => $v ){
//          $returnHtml .= "<div style='position: absolute; right: 2px; top: 32px; display: none; background: white;' id='submenu_$n' >" . $v . "</div>";
//      }  
      $returnHtml .= "<div class='menu_bar_inactive' style='position: absolute; right: 35px; top: 8px; font-weight: normal;' >" . $_SESSION['first_name'] . " " . $_SESSION['last_name'] . "</div>";
      return $returnHtml;
  }
  
  function printHeader()
  {
  ?>
  <iframe src="tab.php" class="newcall" id="phone_call" name="phone_call" style="display:none;" ></iframe>
  <?php 
	echo '
<div id="header">';

        /*  I made it so the config is in an array 
         * config_slimcrm_menu() is in the global.config.php
         * any question email me
         * tholum@couleetechlink.com
         */
        global $DEBUG_TIME ;
        $DEBUG_TIME .= "\n" . __LINE__ . ":" . __FILE__ . "::" . date("H:i:s") . ":" . microtime();
        echo  $this->menuFromArray( config_slimcrm_menu() );    
   $DEBUG_TIME .= "\n" . __LINE__ . ":" . __FILE__ . "::" . date("H:i:s") . ":" . microtime();
echo '</div><div style="width: 10px; height: 32px;">&nbsp;</div>

';
	}
	
	function printContentAreaStart()
	{
		echo '
<div id="content_area">';
	}
 
	function printContentColumnStart($full='')
	{
		if($full=='full')
		echo '<div id="content_column1">';
		else
		echo '<div id="content_column">';
	}
  
	function printContentColumnEnd()
	{
		echo '
	</div>
	
	<div id="info_column">';
	}
	
	function printInfoColumnEnd()
	{
		echo '
	</div>';
	}
	
	function printFooter()
	{
	echo '
	<div id="footer"></div>
	';
	}
	
	function printContentAreaEnd()
	{
		echo '
        </div><div id="dialog_popup" class="dialog"  title="popup" >&nbsp;</div>';
	}
  
  function printGoogAna() // google analytics code
  {
	echo '';
  }

  function printBodyEnd()
  {
	echo '
            
    </body>';
  }
  
  function printHTMLEnd()
  {
    echo '
    </html>';
  }
  
  function CheckAuthorization()
  {
  	$this->auth->CheckAuthorization($this->access_rules, $this->access_rules_type);
  }
  
  function gotoPage($page)
  {
    echo '<script type="text/javascript">
			window.location="'.$page.'";
		  </script>';
  }
  
  /*function left_panel( $html, $overide=array() ){
     echo '<div id="content_left_panel">' . $this->emailclient->display_mail_content(1) . '</div>
	 <div id="tab_left_panel" class="right_arrow" onclick="if(document.getElementById(\'content_left_panel\').style.display==\'none\'){ $(\'#content_left_panel\').show(); $(\'#tab_left_panel\').removeClass(\'right_arrow\'); $(\'#tab_left_panel\').addClass(\'left_arrow\'); } else { $(\'#content_left_panel\').hide(); $(\'#tab_left_panel\').removeClass(\'left_arrow\'); $(\'#tab_left_panel\').addClass(\'right_arrow\'); } "></div>
	 ';
  
  
  }
  
  function right_top_panel( $html, $overide=array() ){
     echo '
	 <div id="tab_right_top_panel" class="left_arrow" onclick="if(document.getElementById(\'content_right_top_panel\').style.display==\'none\'){ $(\'#content_right_top_panel\').show(); $(\'#tab_right_top_panel\').removeClass(\'left_arrow\'); $(\'#tab_right_top_panel\').addClass(\'right_arrow\'); } else { $(\'#content_right_top_panel\').hide(); $(\'#tab_right_top_panel\').removeClass(\'right_arrow\'); $(\'#tab_right_top_panel\').addClass(\'left_arrow\'); } "></div>
	 <div id="content_right_top_panel"></div>
	 ';
  
  
  
  }
  
  function right_bottom_panel( $html, $overide=array() ){
     echo '
	 <div id="tab_right_bottom_panel" class="left_arrow" onclick="if(document.getElementById(\'content_right_bottom_panel\').style.display==\'none\'){ $(\'#content_right_bottom_panel\').show(); $(\'#tab_right_bottom_panel\').removeClass(\'left_arrow\'); $(\'#tab_right_bottom_panel\').addClass(\'right_arrow\'); } else { $(\'#content_right_bottom_panel\').hide(); $(\'#tab_right_bottom_panel\').removeClass(\'right_arrow\'); $(\'#tab_right_bottom_panel\').addClass(\'left_arrow\'); } "></div>
	 <div id="content_right_bottom_panel">' . $this->casecreation->display_case_creation() . '</div>';
  
  }
  
  function right_top_panel( $html, $overide=array() ){
     echo '
	 <div id="tab_right_top_panel" class="left_arrow" onclick="if(document.getElementById(\'content_right_top_panel\').style.display==\'none\'){ $(\'#content_right_top_panel\').show(); $(\'#tab_right_top_panel\').removeClass(\'left_arrow\'); $(\'#tab_right_top_panel\').addClass(\'right_arrow\'); } else { $(\'#content_right_top_panel\').hide(); $(\'#tab_right_top_panel\').removeClass(\'right_arrow\'); $(\'#tab_right_top_panel\').addClass(\'left_arrow\'); } "></div>
	 <div id="content_right_top_panel"></div>
	 ';
  
  
  
  }*/
  
}

?>


