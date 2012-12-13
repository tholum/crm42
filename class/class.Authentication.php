<?php
/***********************************************************************************

			Class Discription : Authentication // Basic class for authentication
								this class authenticate the user from viewing the pages. If user is not authenticated to view a perticular
								page an error message is shown and the user is directed to the previous page.
			
			Class Memeber Functions :setHttp_Referer($http_referer)
									 Create_Session($user_name,$user_id,$groups,$first_name,$last_name)
									 Get_user_id()
									 Get_user_name()
									 function Get_user_full_name()
									 Get_group()
									 Get_group_string()
									 Destroy_Session()
									 checkAuthentication()
									 Checklogin()
									 GotoLogin()
									 GotoWelcomePage()
									 SendToRefrerPage()
									 CheckAuthorization($access_rules,$access_rules_type,$returnValue=false)
									 WelcomeMessage()
									 checkPermessionView($module, $module_id, $return=false)
									 isOwner($module, $where)
									 checkPermessionEdit($module, $module_id, $return=false)
									 isAdmin()
			
			
			Describe Function of Each Memeber function:
									 
									1. function setHttp_Referer($http_referer)
										  Every browser sends a referer in the header. (The HTTP header contains additional information about you and
										  the webpage you�re requesting). The referer is the site you�ve been on before requesting the current site. 
										  That means you can see where people came from. This function creates the link for the page which user visited 
										  before the current page. 
									
									2. function Create_Session($user_name,$user_id,$groups,$first_name,$last_name)
									
											this function creates the session variables of user_name, user_id, groups, first_name,
											last_name.
									
									3. function Get_user_id() // returns the user_id
									
									4. function Get_user_name()  // returns the user_name
									
									5. function Get_user_full_name() // returns the full name of user
									
									6. function Get_group()  // returns the group
									
									7. function Get_group_string() // returns the string of the groups
									
									8. function Destroy_Session()  // Distroy the session and user logout and directed to the login page
									
									9. function checkAuthentication()  // checks that the value of the session variable user_name is set or not 
																		  and return true if set
									
									10. function Checklogin()  // checks that the user is logged in or not if user user not logged in redirect the user to 
																  the login page and after login direct the user to the requested page.
									
									11. function GotoLogin()  // Directs to the login page
									
									12. function GotoWelcomePage()  // Directs to the welcome page
									
									13. function SendToRefrerPage()  // if value of the HTTP_REFERER is null direct to the welcome page
									
									14. function CheckAuthorization($access_rules,$access_rules_type,$returnValue=false)  
									
											// checks that the page user is visiting 
											   is accessable to him or not if not redirects to the previous page
									
									15. function WelcomeMessage()  // Displayes the Welcome message
									


************************************************************************************/
class Authentication // Basic class for authentication
{
var $user_id;
var $user_name;
var $first_name;
var $last_name;
var $db;	
var $groups=array();
var $groups_string;


		 function __construct()
		 {
			$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
			if(isset($_SESSION['user_name'])){
			$this->user_name=$_SESSION['user_name'];
			$this->user_id=$_SESSION['user_id'];
			$this->groups=$_SESSION['groups'];
			$this->first_name = $_SESSION['first_name'];
			$this->last_name = $_SESSION['last_name'];
			}
		 }  
		 
		function setHttp_Referer($http_referer)
		{
			$_SESSION['http_referer'] =	'..'.$http_referer;		
		}
                function Get_current_folder(){
                    $return = '';
                    $request = $_SERVER["REQUEST_URI"];
                    $r_arr = explode("/" , $request);
                    $count = count($r_arr);
                    $x=0;
                    while( $x < $count - 1 ){
                        $return .= $r_arr[$x] . "/";
                        $x++;
                    }
                    return $return;
                }
		function Create_Session($user_name,$user_id,$groups,$first_name,$last_name){
			$this->user_name=$user_name;
			$this->user_id=$user_id;
			$this->groups=$groups;
			$this->first_name=$first_name;
			$this->last_name=$last_name;
			$_SESSION['user_name'] = $this->user_name;
			$_SESSION['user_id'] = $this->user_id;
			$_SESSION['groups']= $this->groups;
			$_SESSION['msg']=$this->WelcomeMessage();
			$_SESSION['first_name']=$this->first_name;
			$_SESSION['last_name']=$this->last_name;
                        $_SESSION['url'] = $this->Get_current_folder();
		}
		
		function Get_user_id()
		{
			return $this->user_id;
		}

		function Get_user_name()
		{
			return $this->user_name;
		}
		
		function Get_user_full_name()
		{
			return $this->first_name.' '.$this->last_name;
		}
		
		function Get_group()
		{
			return $this->groups;
		}
		
		function Get_group_string()
		{
			$group_string=array();
			foreach($this->groups as $key => $value)
			{
				$group_string[]=$key;
			}
			return implode('^', $group_string);
		}
		
		function Destroy_Session(){
    		unset($_SESSION['user_name']); 
			$_SESSION['msg']='You have logged out successfully';
                        unset($_SESSION["url"]);
                        $this->GotoLogin();
		}
		
		function checkAuthentication()
		{
			//check for the valid login
			if(isset($_SESSION['user_name']) && $_SESSION["url"] == $this->Get_current_folder() )
			return true;
			else return false;
		}
		
		function Checklogin()
		{
			$this->setHttp_Referer($_SERVER['REQUEST_URI']);  
			if(!$this->checkAuthentication()){
			$_SESSION['msg']='Please login here first..';
			$this->GotoLogin();
			exit();
			}
		
		
		}
		
		function GotoLogin()
		{
			?>
				<script type="text/javascript">
				window.location='login.php';
				</script>
			<?
			exit();
		}
		
		function GotoWelcomePage()
		{
			?>
				<script type="text/javascript">
				window.location='welcome.php';
				</script>
			<?
			exit();
		}
		
		function SendToRefrerPage()
		{	
			if($_SERVER['HTTP_REFERER']==''){
				$this->GotoWelcomePage();
			}
			else
			{
			?>
				<script type="text/javascript">
				window.location='<?php echo $_SERVER['HTTP_REFERER']; ?>';
				</script>
			<?
			}		
			exit();
		}
		
		function CheckAuthorization($access_rules,$access_rules_type,$returnValue=false)
		{
			//check for the group access
			$access=true;
			foreach($access_rules as $key => $value)
			{
				if (array_key_exists($key, $this->groups))
				{
					if($value!=$this->groups[$key])
					{
						$access=false;
						if($access_rules_type=='all')
						break;
					}
					else
					{
						$access=true;
						if($access_rules_type=='any')
						break;
					}
				}
				else
				{
						$access=false;
						if($access_rules_type=='all')
						break;
				}
			}
			
			if(!$access and !$returnValue)
			{
				$_SESSION['msg']='oops !! Your are not authorised to access this page, Please contact Administrator.';
				$this->SendToRefrerPage();
			}
			else
			return $access;
		}
		
		function WelcomeMessage()
		{
			return "Welcome ".trim($this->Get_user_full_name()).'!';
		}
		
		function checkPermessionView($module, $module_id, $return=false)
		{
			$this->groups_string="('".implode("','",$this->groups)."','*')";	
			$sql="select * from ".TBL_ELEMENT_PERMISSION." where module='$module' and module_id='$module_id' 
			and ((access_to in $this->groups_string and (access_to_type='TBL_USERGROUP or access_to_type='*'')) or (access_to='$this->user_name' and access_to_type='TBL_USER'))
					and (access_type='FULL' or access_type='VIEWONLY')";
			
			if(!$return)
			return $this->db->record_number($sql);
			else
			{
				$this->SendToRefrerPage();
			}		
			exit();
		}
		
		function isOwner($module, $where)
		{
		
			$sql="select * from ".$module." where user_id='$this->user_id' ".$where;
			return $this->db->record_number($sql);
		}
		
		function checkPermessionEdit($module, $module_id, $return=false)
		{
			$this->groups_string="('".implode("','",$this->groups)."')";	
			$sql="select * from ".TBL_ELEMENT_PERMISSION." where module='$module' and module_id='$module_id' 
					and access_to in $this->groups_string and access_type='FULL'";
		
			if(!$return)
			return $this->db->record_number($sql);
			else
			{
				$this->SendToRefrerPage();
			}		
			exit();
		}
		function inGroup( $group ){
                        if(in_array($group,$this->groups))
			return true;
			else
			return false;
                    
                }
                
		function isAdmin()
		{
			if(in_array('Admin',$this->groups))
			return true;
			else
			return false;
		}
		
	}	
?>
