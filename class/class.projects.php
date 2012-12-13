<?php
require_once("class/database.inc.php");
require_once("class/global.config.php");
require_once("class/class.contacts.php");
require_once("class/class.link.php");
require_once("class/class.communications.php");
/*****************************************************************************************
Declare the variable's that mods will be able to add to
NOTE: They must be array's due to muliple mods needing to be able to add and edit
*****************************************************************************************/
$class_module_project_information_array = array();
$class_module_project_information_array["TBL_CONTACT"] = array( "source" => "local" , "info_function" => "get_conatct_info" , "list_function" => "mod_project_test_project" );
//$class_module_project_information_array["TEST"] = array( "source" => "external" , "info_function" => "mod_project_test_info_function" , "list_function" => "mod_project_test_project" );

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
        		if ( preg_match( "/mod.projects/i", $file ) && !preg_match( "/~/i" , $file ) ) {
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
class projects {
	var $db;
	var $information_array;
	var $links;
	var $contacts;
	function __construct(){
		global $class_module_project_information_array;
		$this->information_array = $class_module_project_information_array;
		$this->links = new links;
		$this->contacts = new Contacts;
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		
	}
	function get_conatct_info( $cid ){
		$contact_info = $this->contacts->get_contact_as_array( $cid );
		$return = array();		
		if( $contact_info["type"] == 'Company' ){
			$return["name"] = $contact_info["company_name"];	
		} elseif( $contact_info["type"] == "People" ) {
			$return["name"] = $contact_info["first_name"] . " " . $contact_info["last_name"];	
		} else {
			$return["name"] = $contact_info["company_name"] . $contact_info["first_name"] . " " . $contact_info["last_name"];	
		}
		$sql = "SELECT number phone, email FROM `contacts` left JOIN contacts_phone ON contacts.contact_id = contacts_phone.contact_id LEFT JOIN contacts_email ON contacts.contact_id = contacts_email.contact_id WHERE contacts.contact_id = $cid";
		$result = $this->db->query( $sql );
		$pande = mysql_fetch_assoc( $result );
		$return = array_merge( $return , $pande );
		$return["onclick"] = "contact_profile.php?contact_id=";
		$return["id"] = $cid;	
		return $return;	
	}
	
	function get_link_information( $pid ){
		//echo "$module_type , $module_id\n";
		$link = $this->links->get_links( "PROJECT" , $pid );
		
		$return = array();
		foreach( $link as $row ){
		/*****************************************************************************************
		This next Line is complex so Ill explain at least what is going on in my head :) 
		First this is checking the information array for the specify-ed module type, then it is 
		selecting the info_function and then feeding the module_id to the function and putting the
		output in a new line in the return array.
		ADDED: Switch so if it is local it adds $this-> in front of the function
		
		Required Key's
		"name"
		Optional keys
		"phone" , "email" , "location" , "onclick"
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
Create's a new project
*****************************************************************************************/
	function create_project( $title , $user ){
		$project = array();
		$project["title"] = $title;
		$project["createdby"] = $user;
		$project["created"] = date("Y-m-d H:i:s" );
		$this->db->query("INSERT INTO " . PROJECT_TABLE . " SET title = '$title' , createdby = '$user' , created = NOW()");
		//$this->db->insert( PROJECT_TABLE  , $project);
		return $this->db->last_insert_id();	
	}
	
	function add_user_to_project( $project_id , $user_id , $position_id ){
		$this->db->query("INSERT INTO " . PROJECT_USER . " SET ppid = '$project_id' , user_id = '$user_id' , pid = '$project_id'" , __LINE__ , __FILE__);
		/*$pu = array();
		$pu["ppid"] = $position_id;
		$pu["user_id"] = $user_id;
		$pu["pid"] = $project_id;
		$this->db->insert( PROJECT_USER  , $pu );*/
	}
	
/*****************************************************************************************	
	Retrieve all of the projects of a specific module if $closed = true it will return 
	all projects even closed but defaults to showing only open projects
*****************************************************************************************/
	function get_projects_by_module( $module , $mid , $closed = false ){

			$closed_sql = '';
			if( $closed == false ){ $closed_sql = "AND " . PROJECT_TABLE . ".closed IS NULL"; }
			$sql = "SELECT * FROM " . MODULE_LINK . " LEFT JOIN " . PROJECT_TABLE . " ON " . PROJECT_TABLE . ".pid = " . MODULE_LINK . ".from_module_id WHERE " . MODULE_LINK . ".from_module_type = 'PROJECT' AND " . MODULE_LINK . ".to_module_type = '$module' AND " . MODULE_LINK . ".to_module_id = '$mid' $closed_sql";

			$result = $this->db->query($sql);

			$return = array();
			$num = mysql_num_rows($result);
			$x = 0;			
			while( $row = mysql_fetch_assoc($result) ){
				$return[$x] = array();
				$return[$x] = $row;
				$x++;
			}
			return $return;
	}
/*****************************************************************************************

*****************************************************************************************/
	function get_projects_by_user( $user_id ){
		$sql = "SELECT cb.first_name cb_first_name , cb.last_name cb_last_name , clb.first_name clb_first_name , clb.last_name clb_last_name , projects.pid pid , project_positions.title position , projects.title title, projects.created , projects.closed , projects.createdby , projects.closedby FROM `project_user` LEFT JOIN project_positions ON project_user.ppid = project_positions.ppid RIGHT JOIN projects ON project_user.pid = projects.pid LEFT JOIN tbl_user cb ON projects.createdby = cb.user_id LEFT JOIN tbl_user clb ON projects.closedby = clb.user_id WHERE project_user.user_id = $user_id";
		$result = $this->db->query( $sql );
		$return = array();		
		while( $row = mysql_fetch_assoc($result) ){
			$return[] = $row;
		}		
		return $return;
	}

/*****************************************************************************************
Gives a nested array of postions and possion id's for a specific user
*****************************************************************************************/
	function get_user_positions( $user_id ){
		$sql = "SELECT title , " . PROJECT_POSITIONS . ".ppid FROM `" . GROUP_ACCESS . "` RIGHT JOIN " . PROJECT_POSITIONS_GROUP . " on " . GROUP_ACCESS . ".group_id = " . PROJECT_POSITIONS_GROUP . ".group_id LEFT JOIN " . PROJECT_POSITIONS . " ON " . PROJECT_POSITIONS_GROUP . ".ppid = " . PROJECT_POSITIONS . ".ppid WHERE " . GROUP_ACCESS . ".user_id = $user_id";	
		//echo $sql;
		$result = $this->db->query($sql , __LINE__ , __FILE__ );
		//echo "<option>" . mysql_num_rows($result) . "</option>";
		$return = array();

		while( $row = mysql_fetch_assoc($result) ){
			$return[] = $row;

		}
		return $return;
	}
/*****************************************************************************************
Get's a list of users for a specific project
*****************************************************************************************/
	function get_project_users( $project_id ){
		$sql = "SELECT phone , mobile , email_id , " . TBL_USER . ".first_name , " . TBL_USER . ".last_name, " . PROJECT_POSITIONS . ".title , " . TBL_USER . ".user_id FROM `" . PROJECT_USER . "` LEFT JOIN `" . TBL_USER . "` ON " . PROJECT_USER . ".user_id = " . TBL_USER . ".user_id LEFT JOIN " . PROJECT_POSITIONS . " ON " . PROJECT_USER . ".ppid = " . PROJECT_POSITIONS . ".ppid WHERE " . PROJECT_USER . ".pid = $project_id";
		//echo $sql;
		$result = $this->db->query( $sql);
		$return = array();

		while( $row = mysql_fetch_assoc($result) ){
			//$return[] = array("type" => "Person" , "name" =>  $row["first_name"] . " " . $row["last_name"] , "link" => "UserMan");
			$return[] = $row;
		}
			return $return;
	}
	
	

}

/*****************************************************************************************
USELESS NOTES: Tech 27 - 3 Lead 25 - 1
*****************************************************************************************/
/*****************************************************************************************
TEST CODE DELETE BEFORE PRODUCTION
*****************************************************************************************/
/*
$cm = new communications;
$m = $cm->get_email_info( 1 );
foreach( $m as $n => $v ){
	echo "$n => $v\n";	
	if( is_array( $v ) ){
		foreach( $v as $nn => $vv ){
			echo "\t$nn => $vv\n";
			if( is_array($vv) ){
				foreach( $vv as $nnn => $vvv ){
					echo "\t\t$nnn => $vvv\n";	
					if( is_array( $vvv )){
						foreach( $vvv as $nnnn => $vvvv ){	
							echo "\t\t\t$nnnn => $vvvv\n";
						}
					}
				}
			}	
		}
	}
}



foreach( $m as $n => $v ){
	//echo "$n => $v\n";
	if( $n == 0 ){
		foreach( $v as $nn => $vv ){
			echo $nn . "\t";
		}
		echo "\n";
	}
	foreach( $v as $nn => $vv ){
		echo $vv . "\t";
	}
	echo "\n";
		
}
*/
?>