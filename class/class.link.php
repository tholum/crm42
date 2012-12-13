<?php
/*****************************************************************************************
This is the class that allows you to dynamicly link any module to any other module
This will be used for what I am calling objects for example, contacts , equipment, networks
ext.
*****************************************************************************************/
require_once("class/database.inc.php");
require_once("class/global.config.php");
/*****************************************************************************************
Declare the variable's that mods will be able to add to
NOTE: They must be array's due to muliple mods needing to be able to add and edit
*****************************************************************************************/
$class_module_link_information_array = array();
$class_module_link_information_array["TBL_CONTACT"] = array( "source" => "local" , "info_function" => "get_conatct_info" , "list_function" => "mod_project_test_project" );
//$class_module_link_information_array["TEST"] = array( "source" => "external" , "info_function" => "mod_project_test_info_function" , "list_function" => "mod_project_test_project" );

/****************************************************************************************
This section injects the mods and run there hook() function
for example if we had a mod mod.projects.mymod.php that is in the class folder
this would include class/mod.projects.mymod.php and run the function
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
        		if ( preg_match( "/mod.link/i", $file ) && !preg_match( "/~/i" , $file ) ) {
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
	This is the start of class projects
*****************************************************************************************/
class links {
	var $db;
	var $information_array;
	function __construct(){	
		global $class_module_link_information_array;
		$this->information_array = $class_module_link_information_array;
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	
	
	}
/*****************************************************************************************
This returns a nested array of all the links for a specific module, $source can be eather
"to" "from" or "all" depending on wheather you want things linked to from or eather an object
for example
a is linked to b
b is linked to c

get_links( module_name_of_b , module_id_of_b , "to" );
returns c
get_links( module_name_of_b , module_id_of_b , "from" );
returns a
get_links( module_name_of_b , module_id_of_b , "all" ); or get_links( module_name_of_b , module_id_of_b );
returns a and c
*****************************************************************************************/
	function get_links( $module_name , $module_id , $source="all" ){
		switch( $source ){
			case "to":
				$where = "from_module_type = '$module_name' AND from_module_id = '$module_id'";
			break;
			case "from":
				$where = "to_module_type = '$module_name' AND from_module_id = '$module_id'";
			break;
			case "all":
			default:
				$where = " ( from_module_type = '$module_name' AND from_module_id = '$module_id' ) OR ( to_module_type = '$module_name' AND from_module_id = '$module_id' )";
			
			
			break;	
		}		
		
		$result = $this->db->query( "SELECT * FROM " . MODULE_LINK . " WHERE $where" , __LINE__ , __FILE__);
		$return = array();
		//echo mysql_num_rows($result) . "\n";
		while( $row = mysql_fetch_assoc($result) ){
				if( $row["to_module_type"] == $module_name && $row["to_module_id"] == $module_id ){
					$return[] = array( "module_type" => $row["from_module_type"] , "module_id" => $row["from_module_id"] , "type" => "to" );				
				} elseif( $row["from_module_type"] == $module_name && $row["from_module_id"] ){
					$return[] = array( "module_type" => $row["to_module_type"] , "module_id" => $row["to_module_id"] , "type" => "from" );
				}
				
		}
		return $return;		
	}
	
	function get_links_filter_module( $module_name , $module_id , $source="all" , $filter_module ){
		switch( $source ){
			case "to":
				$where = "from_module_type = '$module_name' AND from_module_id = '$module_id'";
			break;
			case "from":
				$where = "to_module_type = '$module_name' AND from_module_id = '$module_id'";
			break;
			case "all":
			default:
				$where = " ( from_module_type = '$module_name' AND from_module_id = '$module_id' ) OR ( to_module_type = '$module_name' AND from_module_id = '$module_id' )";
			
			
			break;	
		}		
		
		$result = $this->db->query( "SELECT * FROM " . MODULE_LINK . " WHERE $where" , __LINE__ , __FILE__);
		$return = array();
		//echo mysql_num_rows($result) . "\n";
		while( $row = mysql_fetch_assoc($result) ){
				if( $row["from_module_type"] == $module_name && $row["from_module_id"] && $row["to_module_type"] == $filter_module ){
					$return[] = array( "module_type" => $row["from_module_type"] , "module_id" => $row["from_module_id"] , "type" => "to" );				
				} elseif( $row["to_module_type"] == $module_name && $row["to_module_id"] && $row["from_module_type"] == $filter_module ){
					$return[] = array( "module_type" => $row["to_module_type"] , "module_id" => $row["to_module_id"] , "type" => "from" );
				}
				
		}
		return $return;		
	}

/*****************************************************************************************
Returns a nested array with module info ( ie name, url , type )
*****************************************************************************************/

		function get_information( $module_type , $module_id ){
			//echo "$module_type , $module_id\n";
			$links = $this->get_links( $module_type , $module_id );
			
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
/*****************************************************************************************
This function is for returning contact information
*****************************************************************************************/
	function get_conatct_info( $cid ){
		$contact = new Contacts;
		$ct = $contact->get_contact_as_array( $cid );
		switch( $ct["type"] ){
			default:
			case "People":		
				return array("type" => "Person" , "name" =>  $ct["first_name"] . " " . $ct["last_name"] , "link" => "contact_profile.php?contact_id=$cid");
			break;
			case "Company":
				return array("type" => "Company" , "name" => $ct["company_name"]  , "link" => "contact_profile.php?contact_id=$cid");
			break;
		}
	}
}


?>