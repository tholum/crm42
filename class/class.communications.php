<?php

/*****************************************************************************************
Declare the variable's that mods will be able to add to
NOTE: They must be array's due to muliple mods needing to be able to add and edit
*****************************************************************************************/
$class_module_communications_information_array = array();
$class_module_communications_information_array["EMAIL"] = array( "source" => "local" , "info_function" => "get_email_info" , "list_function" => "mod_email_project" );
//$class_module_communications_information_array["TEST"] = array( "source" => "external" , "info_function" => "mod_project_test_info_function" , "list_function" => "mod_project_test_project" );

/****************************************************************************************
This section injects the mods and run there hook() function
for example if we had a mod mod.communications.mymod.php that is in the class folder
this would include class/mod.communications.mymod.php and run the function
mod_project_mymod_hook(); with no variables and no expectations as to what will happen
****************************************************************************************/
$path = get_include_path();
$patharray = explode(":", $path);
foreach( $patharray as $dir ){
	
	if( is_dir( $dir . "/class" )){
		if ($handle = opendir($dir . "/class" )) {
   		while (false !== ($file = readdir($handle))) {
    		//echo $file . "\n";
    	  	//Includes all files that 
        		if ( preg_match( "/mod.communications/i", $file ) && !preg_match( "/~/i" , $file ) ) {
         		include_once( "class/" .$file );
            	$namearray = explode("." , $file );
            	if( function_exists( $namearray[0] . "_" . $namearray[1] . "_" . $namearray[2] . "_hook"  ) ){
            		$function = $namearray[0] . "_" . $namearray[1] . "_" . $namearray[2] . "_hook";      	
            		$function();
            	}
        		}
    		}
    	closedir($handle);
		}
	}
}

/*****************************************************************************************
Communications class
*****************************************************************************************/
class communications {
	var $information_array;
	var $db;
	function __construct(){
		global $class_module_communications_information_array;
		$this->information_array = $class_module_communications_information_array;
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	}	
	
	/*****************************************************************************************
	Get eather user or contact id by there email address
	*****************************************************************************************/
	function get_person_by_email( $email ){
		$result = $this->db->query("SELECT * FROM " . CONTACT_EMAIL . " LEFT JOIN " . TBL_CONTACT . " ON " . CONTACT_EMAIL . ".contact_id = " . TBL_CONTACT . ".contact_id WHERE " . CONTACT_EMAIL . ".email = '$email'" , __LINE__ , __FILE__);
		$return = array();
		if( mysql_num_rows($result) != 0 ){
			$row = mysql_fetch_assoc($result);
			$return["type"] = "TBL_CONTACT";
			$return["id"] = $row["contact_id"];
			if( $row["type"] == "People" ){
				$return["name"] = $row["first_name"] . " " . $row["last_name"];	
			} elseif( $row["type"] == "Company" ){
				$return["name"] = $row["company_name"];	
			}	else {
				$return["name"] = $row["company_name"] . $row["first_name"] . " " . $row["last_name"];	
			}
			
		}
		$result2 = $this->db->query("SELECT * FROM " . TBL_USER . " WHERE email_id = '$email'" , __LINE__ , __FILE__);
		if(mysql_num_rows($result2) != 0){
			$row = mysql_fetch_assoc($result2);
			$return["type"] = "TBL_USER";
			$return["id"] = $row["user_id"];
			$return["name"] = $row["first_name"] . " " . $row["last_name"];
		}
		if( array_key_exists("type", $return) == false ){
			$return["type"] = "NONE";
			$return["id"] = 0;
			$return["name"] = $email;	
		}
		return $return;
	}
	
	function get_email_info( $id ){
		$result = $this->db->query("SELECT * FROM " . EML_MESSAGE . " WHERE mid = '$id'"  , __LINE__ , __FILE__ );
		$row = mysql_fetch_array($result);
		$return = array();
		$return["subject"] = $row["subject"];
		$return["body"] = $row["body"];
		$return["unixtime"] = $row["unixtime"];
		$return["person"] = array();
		$return["person"][$row["from_mailbox"] . "@" . $row["from_host"]] = $this->get_person_by_email( $row["from_mailbox"] . "@" . $row["from_host"]);
		$result2 = $this->db->query("SELECT * FROM " . EML_EMAIL . " WHERE mid = '$id'" , __LINE__ , __FILE__);
		while( $row = mysql_fetch_array($result2) ){
			$return["person"][$row["mailbox"] . "@" . $row["host"]] = $this->get_person_by_email( $row["mailbox"] . "@" . $row["host"]);
		}
		return $return;
	}	
		
	function get_communications_by_module( $module , $module_id ){
		$result = $this->db->query("SELECT * FROM " . COMMUNICATIONS . " WHERE link_module_type = '$module' AND link_module_id = '$module_id'" , __LINE__ , __FILE__);
		/*****************************************************************************************
		
		*****************************************************************************************/		

		$links = array();
		//echo mysql_num_rows($result) . "\n";
		while( $row = mysql_fetch_assoc($result) ){	
			$links[] = array( "module_type" => $row["source_module_type"] , "module_id" => $row["source_module_id"] , "type" => "to" );								
		}
		
		$return = array();
		foreach( $links as $row ){
		/*****************************************************************************************
		This next Line is complex so Ill explain at least what is going on in my head :) 
		First this is checking the information array for the specify-ed module type, then it is 
		selecting the info_function and then feeding the module_id to the function and putting the
		output in a new line in the return array.
		ADDED: Switch so if it is local it adds $this-> in front of the function
		*****************************************************************************************/
			switch( $this->information_array[ $row["module_type"] ]["source"] ){
				case "local":
					$function = $this->information_array[$row["module_type"]]["info_function"];
					$return[] = $this->$function($row["module_id"]);							
				break;
				case "external":
				default:
					$return[] = $this->information_array[$row["module_type"]]["info_function"]($row["module_id"]);
				break;			
			}
		}
		return $return;		
	}
	
}
?>