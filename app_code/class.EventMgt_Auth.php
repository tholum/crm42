<?php
class Event_Mgt_Auth extends Authentication 
{
	
	var $contact_id;
	var $contact_user_name;
	
	
	function __construct(){
		parent::__construct();
		$this->contact_id = $_SESSION['contact_id'];
		$this->contact_user_name = $_SESSION['contact_user_name'];
	}
	
	function Create_Session($contact_user_name,$contact_id,$groups='',$first_name='',$last_name=''){
			$this->contact_user_name=$contact_user_name;
			$this->contact_id=$contact_id;
			$this->groups=$groups;
			$this->first_name=$first_name;
			$this->last_name=$last_name;
			$_SESSION['contact_user_name'] = $this->contact_user_name;
			$_SESSION['contact_id'] = $this->contact_id;
			//$_SESSION['groups']= $this->groups;
			$_SESSION['contact_msg']=$this->WelcomeMessage();
			$_SESSION['contact_first_name']=$this->first_name;
			$_SESSION['contact_last_name']=$this->last_name;
	}
	
	function Get_contact_id(){
		return $this->contact_id;
	}

	function Get_contact_user_name(){
		return $this->contact_user_name;
	}
	function Destroy_Session(){
    	unset($_SESSION['contact_user_name']); 
		$_SESSION['msg']='You have logged out successfully';
		$this->GotoLogin();			
	}
	
	function GotoLogin()
	{
		?>
			<script type="text/javascript">
			window.location='contact_login.php';
			</script>
		<?
		exit();
	}
	
	function checkAuthentication(){
		//check for the valid login
		if(isset($_SESSION['contact_user_name']))
		return true;
		else return false;
	}
	
	function GotoWelcomePage(){
		?>
			<script type="text/javascript">
			window.location='welcome.php';
			</script>
		<?
		exit();
	}
	
	function checkPermessionView($module, $module_id, $return=false){
		$this->groups_string="('".implode("','",$this->groups)."','*')";	
		$sql="select * from ".TBL_ELEMENT_PERMISSION." where module='$module' and module_id='$module_id' 
		and ((access_to in $this->groups_string and (access_to_type='TBL_USERGROUP or access_to_type='*'')) or (access_to='$this->contact_user_name' and access_to_type='TBL_CONTACT'))
				and (access_type='FULL' or access_type='VIEWONLY')";
		
		if(!$return)
		return $this->db->record_number($sql);
		else
		{
			$this->SendToRefrerPage();
		}		
		exit();
	}
	
	function isOwner($module, $where){
		$sql="select * from ".$module." where user_id='$this->contact_id' ".$where;
		return $this->db->record_number($sql);
	}
}
?>