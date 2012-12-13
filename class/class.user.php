<?php
if( PHONE_SYSTEM == "asterisk"){
    require_once 'class/class.asterisk.php';
}
if(defined('ALT_AUTH') == true ){
    $auth_arr = explode(";", ALT_AUTH);
    foreach( $auth_arr as $auth ){
        include_once('modules/auth/' . $auth . '.pre_include.php');
    }
}
/***********************************************************************************

Class Discription : This class will handle the creation and modification
					of users and user groups that are used on the platform.
					Users � Anyone who can log into the system
					User groups � Users can be placed into one or more user groups. Every user
					of the system will be in the lowest user group called �user�

Class Memeber Functions :CreateUser($runat)
						 EditUser($runat, $user_id,$first_name='', $last_name='', $email_id='')
						 CreateUserGroup($runat)
						 EditUserGroup($runat,$group_id,$disc='')
						 GetUser($user_id)
						 GetAllUser()
						 GetAllUserInList($name,$current_user_id='',$onchnageCode='',$displayempty='',$select='')
						 UserLogin($runat)
						 Reset_password($runat,$user_id)
						 ForgetPassword($runat)
						 Show_Group($runat ,$user_id=0,$type='Add')
						 getIndex($groups,$group_id)
						 checkEmail($email_id)
						 checkUser($user_name,$email_id='')
						 checkGroup($group_name)
						 BlockUser($user_id)
						 UnblockUser($user_id)
						 ManageUser()
						 Manage_groups()
						 DisplayGroup($group_id,$mode='normal',$runat='',$disc='')
						 deleteGroup($group_id,$group_name)
						 RemoveUserFromGroup($user_id,$group_id,$type='group')
						 AddUserToGroup($user_id,$group_id,$type='group')
						 GetUsersList($group_id,$type='',$user_id=false)
						 Manage_users()
						 DisplayUser($user_id,$mode='normal',$runat='',$first_name='',$last_name='',$email_id='')
						 deleteUser($user_id,$user_name)
						 GetGroupsList($user_id,$type='',$group_id=false)
						 sendResetPasswordLink($user_id,$user_name,$email_id,$first_name='')
						 setResetFlag($user_id,$user_name)
						 unsetResetFlag($user_id)
						 GetUserIDfromflag($flag)
						 checkResetlink($flag)
						 GetUserNameById($user_id)
						 GetUsersJson($user_name,$to)
						 Valid_Password($password)
						 Change_Password($runat,$user_id)
						 Add_Update_Profile_Button($runat)
						 Update_Profile($runat,$user_id)


Describe Function of Each Memeber function:
					1. CreateUser($runat) // $runat=local/server 
						Add user_id ,user_name,first_name,middle_name,last_name,email_id, in the database in tbl_user table	
					
					2. function  EditUser($runat, $user_id)// $runat=local/server,$user_id=user_id of the user which is uniqe
						Edit user_id ,user_name,first_name,middle_name,last_name,email_id in the database in tbl_user table	
					
					3. function CreateUserGroup($runat)// $runat=local/server
						Add group_name,group_id in the database in tbl_usergroup table
						
					4. function EditUserGroup($runat,$group_id)// $runat=local/server,$group_id=group_id of the user which is uniqe
						Get group_name in the database in tbl_usergroup table
						
					5. function GetUser($user_id)//$user_id=user_id of the user which is uniqe
						Get user_name,first_name,middle_name,last_name,email_id, in the database in tbl_user table	
						
					6. function GetAllUser() 
						Get group_name in the database in tbl_usergroup table
						
					7. function GetAllUserInList($name,$current_user_id='',$onchnageCode='',$displayempty='',$select='')
						display list of all names of user order by user_name.
						
					8. function UserLogin($runat) // $runat=local/server 
						login function for user to login if session is set.
						
					9. function Reset_password($runat,$user_id) // $runat=local/server 
						function to change the password
					
				   10. function ForgetPassword($runat)  // $runat=local/server 
						 function to recover forgot password. This function sends a link to the email address of the user to recover his password
					
					11. function Show_Group($runat ,$user_id=0,$type='Add')
						  this function shows all the groups to which the user is authenticated.
						 
					12. function getIndex($groups,$group_id)
						  returns the number of groups.
					
					13. function checkEmail($email_id)
					      this function checks that the email_id entered already exist in the database or not if exist it return false
						  else it return true.
					
					14. function checkUser($user_name,$email_id='')
						  function to check the username name is available or not. It checks the username in the database if username
						  exist it return false else it return true.
					
					15. function checkGroup($group_name)
					      function to check the the group name entered is available or not. It checks the database if group is not available
						  it return false else it return true.
					
					16. function BlockUser($user_id)
						  user can block any person or group. user_id of the person or group is passed and the table tbl_user is updated
						  'yes' is passed in the block column.
					
					17. function UnblockUser($user_id)
						  function to unblock the blocked user. user_id of the person or group is passed and the table tbl_user is updated
						  with 'on' in the block column field.
					
					18. function ManageUser()
						  function to manage the user and perform the selected action record 
					
					19. function Manage_groups()
					      this function shows all the groups order by group name.
					
					20. function DisplayGroup($group_id,$mode='normal',$runat='',$disc='')
						  display the groups with group image and group name. Also provieds an option to delete of edit group.
					
					21. function deleteGroup($group_id,$group_name)
						  delete the selected group . the group_id and the group name is passed of the seleted group and the 
						  selected group is then deleted from the database.
					
					22. function RemoveUserFromGroup($user_id,$group_id,$type='group')
						  can remove any selected user from the group.
					
					23. function AddUserToGroup($user_id,$group_id,$type='group')
						  can add a new person or company to the group.
					
					24. function GetUsersList($group_id,$type='',$user_id=false)
						  function to get the group it of the selected group.
					
					25. function Manage_users()
						  display the list of users to manage.
					
					26. function DisplayUser($user_id,$mode='normal',$runat='',$first_name='',$last_name='',$email_id='')
						  display the detsils of the selected user. The user_id of the selected user is passed and the details of that user is shown
						  the option to edit and delete is also shown.						  
					
					27. function deleteUser($user_id,$user_name)
						  this function deletes the selected user from the database record.
					
					28. function GetGroupsList($user_id,$type='',$group_id=false)
						  to display the list of users in the selected group. Three types of list are generated CSV- comma seperated list, select 
						  list and comma seperated with delete link.
					
					29. function sendResetPasswordLink($user_id,$user_name,$email_id,$first_name='')
						  send the password reset link to the email_id of the requesting user.
					
					30. function setResetFlag($user_id,$user_name)
						  set the flag for the user requesting for the password reset.
					
					31. function unsetResetFlag($user_id)
						  unset the flag for the user after the password reset process is over.
					
					32. function GetUserIDfromflag($flag)
						  get the user id from the set value of flag.
					
					33. function checkResetlink($flag)
						  checks that the value of the flag for the reset password link is set or not , if set returns true else
						  return false.
					
					34. function GetUserNameById($user_id)
						  get the user name by the user_id from the database.
					
					35. function GetUsersJson($user_name,$to)
						  returns the Json form of the data.
					
					36. function Valid_Password($password)
						  checks that the password entered is valid or not if valid returns true else return false.
					
					37. function Change_Password($runat,$user_id)
						  function to change the password. 
					
					38. function Add_Update_Profile_Button($runat)
						  adds the update file button.
					
					39. function Update_Profile($runat,$user_id)
						  function to update the profile. Edit first_name, middle_name, last_name, phone, mobile, website and 
						  image. 
					
						
						
					
************************************************************************************/
if( WEBMAIL == true ){ require_once "class.roundcube.php"; }
class User // Basic class for contact 
{
	const MODULE='TBL_USER';
	var $user_id;
	var $password;
	var $repassword;
	var $group_id;
	var $user_name;	
	var $first_name;	    
	var $middle_name;	
	var $last_name;		
	var $email_id;
	var $phone;
	var $mobile;
	var $website;
	var $image;
	var $old_file_name;
	var $objFileUpload;
	var $directory;
	var $group_name;
	var $group_description;	
	var $db;
	var $old_pwd;
	var $new_pwd;
	var $to ;
	var $body;
	var $subject;
	var $Validity;
	var $Form;
	var $auth;
	var $groups = array();
	var $group_access = array();
	var $mailer;
	var $google_apps_id;
	var $google_apps_password;
	var $eml;
        var $asterisk;
	 function __construct()
	 {
	    $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->Validity=new ClsJSFormValidation();
		$this->Form=new ValidateForm();
		$this->auth=new Authentication();
		$this->mailer=new PHPMailer();
		$this->directory='Picture/user';
		$this->objFileUpload = new FileUpload();
                $this->page = new basic_page();
		if( WEBMAIL == true ){ 	$this->eml = new email_functions(); }
                if( PHONE_SYSTEM == "asterisk" ){ $this->asterisk = new Asterisk( $this ); }
        }
        function json_search_group( $search ){
            $arr = explode(" ", $search);
            $sql = "SELECT * FROM tbl_usergroup";
            $where = array();
            foreach( $arr as $value ){
                if( $value != ''){
                    $where[] = "( group_name LIKE '%$value%' OR group_description LIKE '%$value%' )";
                }
            }
            if( count($where) != 0 ){
                $sql .= " WHERE " . implode(" AND ", $where);
            }
            $result = $this->db->query($sql);
            $json = array();
            while( $row = $this->db->fetch_assoc($result)){
                $row["value"] = $row["group_name"];
                $row["label"] = $row["group_name"];
                //$row["sql"] = $sql;
                $json[] = $row;
            }
            return json_encode($json);            
        }
	function json_search_user( $search , $include_groups = false ){
            $arr = explode(" ", $search);
            $sql = "SELECT user_id , first_name , last_name FROM tbl_user";
            $where = array();
            foreach( $arr as $value ){
                if( $value != ''){
                    $where[] = "( user_name LIKE '%$value%' OR first_name LIKE '%$value%' OR last_name LIKE '%$value%' )";
                }
            }
            if( count($where) != 0 ){
                $sql .= " WHERE " . implode(" AND ", $where);
            }
            $result = $this->db->query($sql);
            $json = array();
            while( $row = $this->db->fetch_assoc($result)){
                $row["value"] = $row["first_name"] . " " . $row["last_name"];
                $row["label"] = $row["value"];
                $row["module_name"] = "TBL_USER";
                $row["module_id"] = $row['user_id'];
                //$row["sql"] = $sql;
                $json[] = $row;
            }
            if( $include_groups ){
                $sql2 = "SELECT group_id , group_name FROM tbl_usergroup";
                $where2 = array();
                foreach( $arr as $value ){
                    if( $value != ''){
                        $where2[] = "( group_name LIKE '%$value%' OR group_description LIKE '%$value%' )";
                    }
                }
                if( count($where2) != 0 ){
                    $sql2 .= " WHERE " . implode(" AND ", $where2);
                }
                $result2 = $this->db->query($sql2);
                while( $row = $this->db->fetch_assoc($result2)){
                    $row["value"] = "Group:" . $row["group_name"];
                    $row["label"] = $row["value"];
                    $row["module_name"] = "TBL_GROUP";
                    $row["module_id"] = $row['group_id'];
                    //$row["sql"] = $sql;
                    $json[] = $row;
                }
            }
            
            return json_encode($json);
        }
	function CreateUser($runat)
	{
		switch($runat){
		
		case 'local':
					if(count($_POST)>0 and $_POST['submit']=='Submit'){
					extract($_POST);
					$this->user_name=$user_name;
					$this->password=$password;
					$this->repassword=$repassword;
					$this->first_name=$first_name;	
					$this->last_name=$last_name;
					$this->email_id=$email_id;
					}
					else {
					//$this->first_name='first';
					//$this->last_name='last';
					}
					//create client side validation
					$FormName='frm_registration';
											
					$ControlNames=array("user_name"			=>array('user_name',"''","enter a valid username","spanuser_name"),
										"email_id"			=>array('email_id',"EMail","invalid email address","spanemail_id"),
										"password"			=>array('password',"Password","invalid password","spanpassword"),
										"repassword"		=>array('repassword',"RePassword","repeat password does not match","spanrepassword",'password'),
										"first_name"		=>array('first_name',"''","enter first name","spanfirst_name")
										);

					$ValidationFunctionName="CheckValidity";
					
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
					
					?>
					<form action="" enctype="multipart/form-data" method="post" name="<?php echo $FormName; ?>">
					<ul>
					<li><span id="spanfirst_name" class="normal"></span></li>
				<!--	<li><span id="spanlast_name" class="normal"></span></li>-->
					<li><span id="spanemail_id" class="normal"></span></li>
					<li><span id="spanuser_name" class="normal"></span></li>
					<li><span id="spanpassword" class="normal"></span></li>
					<li><span id="spanrepassword" class="normal"></span></li>
					</ul><table class="table" width="100%">
					
					<tr><th width="16%">name:</th>
					<td width="47%">
					<input type="text"  name="first_name" id="first_name" value="<?php echo $this->first_name;?>"/>
					</td>
					<td width="37%">
					<input type="text"  name="last_name" id="last_name" value="<?php echo $this->last_name;?>" />
					</td>
					</tr>
					
					<tr><th>email:</th>
					<td colspan="2">
					<input type="text" name="email_id" id="email_id" value="<?php echo $this->email_id;?>" />
					</td>
					</tr>
					
					<tr>
					<td colspan="3">&nbsp;</td>
					</tr>
					
				   	<tr><th><span class="formInfo">
					<a href="FormHelp.php?width=375&type=username" class="jTip" id="user_nameHelp" name="Username should follow these rules :" onclick="return false;">?</a></span>&nbsp;username: </th>
					<td colspan="2">
					<input type="text" name="user_name" id="user_name" value="<?php echo $this->user_name;?>" />
					</td>
					</tr>

					<tr><th><span class="formInfo"><a href="FormHelp.php?width=375&type=password" class="jTip" id="passwordHelp" name="Password should follow these rules :" onclick="return false;">?</a></span>&nbsp;password: </th>
					<td colspan="2">
					<input type="password" name="password" id="password" value="<?php echo $this->password;?>"/>
					</td>
					</tr>
					
					<tr><th>re-enter:</th>
					<td colspan="2">
					<input type="password" name="repassword" id="repassword"  value="<?php echo $this->repassword;?>"/>
					</td>
					</tr>
					
					<?php //echo $this->Show_Group('local'); ?>	

					
					
					
					<tr>
					<td colspan="3" align="right">
					<input type="submit" name="submit" id="submit" value="add user"   style="width:auto" onClick="return <?php echo $ValidationFunctionName?>();" />
					</td></tr>
					</table>
					</form>	
					<?php
						
		
			break;
			case 'server':
					
					extract($_POST);
					$this->user_name=trim($user_name);
					$this->password=$password;
					$this->repassword=$repassword;
					$this->first_name=$first_name;
					$this->last_name=$last_name;
					//$this->middle_name=$middle_name;
					$this->email_id=$email_id;
					$zmprov = shell_exec("/usr/bin/ssh -i /var/www/.ssh/id_rsa zimbra@mail.slimcrm.com 'zmprov ca " . $this->user_name . "@" . ZIMBRA_EMAIL . " " . $this->password . "'");
					//server side validation
					$return =true;
//					if($this->Form->ValidField($user_name,'UserName','Username field is Empty or Invalid')==false&&1==2)
//						$return =false;
//					if($this->Form->ValidField($email_id,'email','Email field is Empty or Invalid')==false)
//						$return =false;	
					if($this->Form->ValidField($password,'Password','Password field Empty or Invalid')==false)
						$return =false;
					if($this->Form->ValidField($repassword,'Password','Repeat password does not match',$password)==false)
						$return =false;
					if($this->Form->ValidField($first_name,'empty','Please Enter First name')==false)
						$return =false;
					
					if($return) {	
					$valid_user=$this->checkUser($this->user_name,$this->email_id);
					if($valid_user) {
					$insert_sql_array = array();
					$insert_sql_array['user_name'] = $this->user_name;
					$insert_sql_array['email_id'] = $this->email_id;
					$insert_sql_array['password'] = $this->password;
					$insert_sql_array['first_name'] = $this->first_name;
					$insert_sql_array['last_name'] = $this->last_name;
					//$insert_sql_array['middle_name'] = $this->middle_name;
					
					$this->db->insert(TBL_USER,$insert_sql_array);
					$this->user_id=$this->db->last_insert_id();
					$this->Show_Group('server',$this->user_id);				
					$_SESSION['msg']='Thank You , Your have Registered successfully';
					?>
					<script type="text/javascript">
					window.location="<?php echo $_SERVER['PHP_SELF'];?>";
					</script>
					<?php
					exit();
					}
					else
					{
					echo '<div class="errortxt"><li>Sorry !! This username or email id already exist</li></div>'; 
					$this->CreateUser('local');
					}
					}
					else
					{
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
					$this->CreateUser('local');
					}
					
			break;
		default : echo 'Wrong Paramemter passed';
		
	}
	}
	
	function EditUser_basicInfo($row)
	{
					
		?>
		<td width="10%" valign="top" align="center">
		<div>
			<a href="profile.php?id=<?php echo $row[user_id]; ?>"><?php if(!file_exists($this->directory.'/'.$row[image]) or $row[image]=='') { ?><img src="images/person.gif" border="0"  alt="Current Picture"/><?php } else {?><div style="max-height:50px; overflow:hidden; padding-right:5px;"><img src="thumb.php?file=<?php echo $this->directory.'/'.$row[image] ?>&sizex=100&sizey=70" border="0"  alt="<?php echo $row[first_name] ?>" width="50"/></div><?php } ?></a></div>
		<!--<span class="verysmall_text"><a href="" onclick="return false;">Change</a></span>-->					</td>
		<td valign="top" width="2%">&nbsp;</td>
		<td valign="top" width="25%">
		<div class="width70"><input type="text" name="first_name" id="first_name" value="<?php echo $row['first_name'];?>" />
		<div id="spanfirst_name_edit"></div></div>	
					
		<div class="width70">
		<input type="text" name="last_name" id="last_name" value="<?php echo $row['last_name'];?>" />
		<div id="spanlast_name_edit"></div></div>
		
		<div class="textb">Email: </div>
		<div>
		<!--<input type="text" name="email_id" id="email_id" value="<?php echo $row['email_id'];?>" />-->
		<a href="mailto:<?php echo $row['email_id'];?>"><?php echo $row['email_id'];?></a>
		<!--<div id="spanemail_id_edit">--></div>
		</td>
		 <td width="2%" valign="top">&nbsp;</td>
		<?php
	
	
	}
	
	
	function EditUser_groups($row,$user_id,$ValidationFunctionName,$ajaxObj='user',$editfunction='DisplayUser',$deleteFunction='deleteUser') 
	{
		?>
		 <td width="25%" valign="top">
		 <div class='addGroup'><span class="textb"> Add Groups:&nbsp;</span> 
		 <span id="select_user_<?php echo $user_id; ?>"></span> 
		 </div>
		 
		 <div class="textareadiv verysmall_text" id="textareadiv_user_<?php echo $user_id; ?>">
		 <?php 
		echo  $this->GetGroupsList($user_id,$type='CsvWithDeleteLink',false,$ajaxObj);  					
		?>
		 </div>	 </td>
		 
		 <td width="19%" valign="top" align="right"><span onclick="<?php echo $ajaxObj; ?>.DisplayUser(<?php echo $user_id;?>,'normal','','','',
		 					'<?php echo $ajaxObj;?>','<?php echo $editfunction; ?>','<?php echo $deleteFunction; ?>',
							{target:'user<?php echo $user_id;?>', preloader: 'prl'});return false;">X</span></td>
		 </tr>
		 <tr><td colspan="10" align="right">
		<input type="button" name="submit" id="submit" value="update user" style="width:auto"
		 onClick="if(<?php echo $ValidationFunctionName; ?>()){ <?php echo $ajaxObj; ?>.<?php echo $editfunction; ?>(<?php echo $user_id;?>,
					'edit', 'server',this.form.first_name.value,this.form.last_name.value, 
					<?php 
						if($editfunction=='Edit_Agent')
						{
						?>
					this.form.mls_id.value,this.form.mls_name.value,this.form.office_id.value,
					<?php } ?>
					'<?php echo $ajaxObj;?>','<?php echo $editfunction; ?>','<?php echo $deleteFunction; ?>',
					{target:'user<?php echo $user_id;?>', preloader: 'prl'});}" />
		</td>
		<?php
	
	}
	
    function EditUser($runat, $user_id,$first_name='',$last_name='',$ajaxObj='user',$editfunction='DisplayUser',$deleteFunction='deleteUser')
	{
		$this->user_id=$user_id;
		switch($runat){
		
		case 'local':
					//create client side validation
					$FormName='frm_edit_user';
											
					$ControlNames=array(
										/*"email_id"=>array('email_id',"EMail","invalid email address","spanemail_id_edit"),*/
										"first_name"=>array('first_name',"''","enter first name","spanfirst_name_edit")
										);

					$ValidationFunctionName="CheckValidityEditUser";
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
									
					$sql="select * from ".TBL_USER." where user_id='$this->user_id'";
					$record=$this->db->query($sql,__FILE__,__LINE__);
					$row=$this->db->fetch_array($record);
					?>
	
					<div class="edit_border small_text">
					<form action="" enctype="multipart/form-data" method="post"  name="<?php echo $FormName; ?>">
					<table width="100%"><tr>

					<?php $this->EditUser_basicInfo($row); ?>
					<?php $this->EditUser_groups($row,$user_id,$ValidationFunctionName,'user'); ?>
										
					</tr></table>
					</form></div>
					<?php
						
		
			break;
			case 'server':
					
				
					$this->first_name=$first_name;
					$this->last_name=$last_name;
					//$this->email_id=$email_id;
					
					//server side validation
					$return =true;
					/*if($this->Form->ValidField($email_id,'email','email field is empty Or invalid')==false)
						$return =false;*/	
					if($this->Form->ValidField($first_name,'empty','please enter first name')==false)
						$return =false;
					
					if($return) {
			

					$update_sql_array = array();
					$update_sql_array['first_name'] = $this->first_name;
					$update_sql_array['last_name'] = $this->last_name;
					//$update_sql_array['email_id'] = $this->email_id;
					
					$this->db->update(TBL_USER,$update_sql_array,"user_id",$this->user_id);
					
					echo $this->DisplayUser($user_id,'normal','','','',$ajaxObj,$editfunction,$deleteFunction);
					}
					else
					{
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
					$this->EditUser('local',$this->user_id);
					}
			break;
		default : echo 'Wrong Paramemter passed';
		
	}
	

  }
  function CreateUserGroup($runat)
	{
		switch($runat){
		
		case 'local':
		            if(count($_POST)>0){
					extract($_POST);
					$this->group_name=$group_name;
					$this->group_description=$group_description;
					}
					//create client side validation
					$FormName='frm_create_user_group';
											
					$ControlNames=array("group_name"=>array('group_name',"''","Please Enter User Group Name","spangroup_name")
										);

					$ValidationFunctionName="frm_create_user_groupCheckValidity";
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
					
				 ?>
					<form action="" enctype="multipart/form-data" method="post"  name="<?php echo $FormName; ?>">
					<table class="table" width="100%" ><tr>
					<th valign="top" >Name:</th>
					<td valign="top">
					<input type="text" name="group_name" id="group_name" />
					<span id="spangroup_name"></span></td></tr>
					<tr><th valign="top">Description:</th>
					<td valign="top">
					  <textarea name="group_description" id="group_description"></textarea>
					  <span id="spangroup_description"></span></td></tr>
				
				<tr><td align="right" colspan="2">
					<input type="submit" name="submit" id="submit" value="add group" style="width:auto" onClick="return <?php echo $ValidationFunctionName ?>();"/>
					</td></tr></table>
					</form>	
					<?php
						
		
			break;
			case 'server':
					extract($_POST);
					$this->group_name=trim($group_name);
					$this->group_description=$group_description;
					
					//server side validation
					$return =true;
					if($this->Form->ValidField($group_name,'empty','Please enter a valid group name')==false)
					$return =false;
					

					if($return) {
					$valid_group=$this->checkGroup($this->group_name);
					if($valid_group) {
					
					$insert_sql_array = array();
					$insert_sql_array['group_name'] = $this->group_name;
					$insert_sql_array['group_description'] = $this->group_description;
				    $this->db->insert(TBL_USERGROUP,$insert_sql_array);
					$_SESSION['msg']='User Group Created Successfully';
					?>
					<script type="text/javascript">
					window.location="<?php echo $_SERVER['PHP_SELF'];?>";
					</script>
					<?php
					exit();
					}
					else
					{
					echo '<div class="errortxt"><li>Sorry !! This Group Name already exist</li></div>'; 
					$this->CreateUserGroup('local');
					}
					}
					else
					{
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
					$this->CreateUserGroup('local');
					}
					
			break;
		default : echo 'Wrong Paramemter passed';
		
	}
	}
	function EditUserGroup($runat,$group_id,$disc='')
	{	
		$this->group_id=$group_id;
		ob_start();
		$sql="select * from ".TBL_USERGROUP." where group_id=$group_id";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		$row=$this->db->fetch_array($record);

		switch($runat){
		
		case 'local':
					//Edit UserGroup
					$FormName='frm_edit_user_group_$group_id';
											
					$ControlNames=array("group_name"=>array('group_name',"''","Please Enter User Group Name","spangroup_name_edit")
										);

					$ValidationFunctionName="frm_edit_user_groupCheckValidity_$group_id";
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
					?>
					<div class="edit_border small_text">
					<form action="" enctype="multipart/form-data" method="post"  name="<?php echo $FormName; ?>">
					<table width="100%" ><tr>
					<td width="39%" valign="top">
					<input type="text" name="group_name" id="group_name" value="<?php echo $row['group_name']; ?>" readonly="true" />
					<div id="spangroup_name_edit"></div>
					<div class="textb">Description:</div>
					  <textarea class="small_text" name="group_description" id="group_description" rows="3" cols="30"><?php echo $row['group_description']; ?></textarea>
					  <div id="spangroup_description_edit"></div>					 </td>
					 
					 <td width="4%" valign="top">&nbsp;</td>
					 <td width="38%" valign="top">
					 <div class='addGroup'><span class="textb"> Add Users:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span> 
					 <span id="select_group_<?php echo $group_id; ?>"></span>

					 </div>
					 
					 <div class="textareadiv verysmall_text" id="textareadiv_group_<?php echo $group_id; ?>">
					 <?php 
					echo  $this->GetUsersList($group_id,$type='CsvWithDeleteLink'); 					
					?>
					 </div>					 </td>
					 <td width="19%" valign="top" align="right"><span onclick="user.DisplayGroup(<?php echo $group_id;?>,'normal', 'local',
										{target:'group<?php echo $group_id;?>', preloader: 'prl'});return false;">X</span></td>
					 </tr>
					 <tr><td colspan="4" align="right">
					<input type="button" name="submit" id="submit" value="update group" style="width:auto"
					 onClick="if(<?php echo $ValidationFunctionName; ?>()){ user.DisplayGroup(<?php echo $group_id;?>,
					 			'edit', 'server',this.form.group_description.value,{target:'group<?php echo $group_id;?>', preloader: 'prl'});}" />
					</td></tr></table>
					</form></div>
					<?php
						
		
			break;
			case 'server':
					
					
					$this->group_description=$disc;
					
					$update_sql_array = array();
					$update_sql_array['group_description'] = $this->group_description;
				    $this->db->update(TBL_USERGROUP,$update_sql_array,"group_id",$this->group_id);
					echo $this->DisplayGroup($group_id,'normal');
		break;
		default : echo 'Wrong Paramemter passed';
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	}
   function GetUser($user_id)

	{
		$sql="select * from ".TBL_USER." where user_id='$user_id'";

		$record=$this->db->query($sql,__FILE__,__LINE__);
		$row=$this->db->fetch_array($record);
		return $row;
		
	}

	function GetAllUser()
	{
				
		$sql="select * from ".TBL_USER." order by user_name";

		$record=$this->db->query($sql,__FILE__,__LINE__);
		return $record;
		
	}
	
	function GetAllUserInList($name,$current_user_id='',$onchnageCode='',$displayempty='',$select='')
	{
	    ?>
		<select name="<?php echo $name; ?>" id="<?php echo $name;?>" onchange="<?php echo $onchnageCode; ?>" >
		<?php if($displayempty!='') { ?>
		<option value="">All Users</option> <?php } ?>
		<option value="<?php echo $current_user_id; ?>" <?php if($select==$current_user_id) echo 'selected="selected"'; ?> >Me</option>
		<?php		
		$sql="select * from ".TBL_USER." where user_id <> '$current_user_id'  order by user_name";

		$result=$this->db->query($sql,__FILE__,__LINE__);
		
		while($row=$this->db->fetch_array($result)){
					?>
                    <option value="<?php echo $row['user_id']; ?>" <?php if($select==$row['user_id']) echo 'selected="selected"'; ?> ><?php echo $row['user_name']; ?></option>
					 <?php
					 }
					 ?>
       </select>
		<span id="span<?php echo $name;?>"></span>
		<?php
		
	}
	
	
	function GetAllGroupInList($name,$current_group_id='',$onchnageCode='',$displayempty='',$select='')
	{
		?>
		<select name="<?php echo $name; ?>" id="<?php echo $name;?>" onchange="<?php echo $onchnageCode; ?>" >
		<?php if($displayempty!='') { ?>
		<option value="">All Groups</option> <?php } ?>
		<?php		
		$sql="select * from ".TBL_USERGROUP." order by group_name";

		$result=$this->db->query($sql,__FILE__,__LINE__);
		
		while($row=$this->db->fetch_array($result)){
					?>
                    <option value="<?php echo $row['group_id']; ?>" <?php if($select==$row['group_id']) echo 'selected="selected"'; ?> ><?php echo $row['group_name']; ?></option>
					 <?php
					 }
					 ?>
       </select>
		<span id="span<?php echo $name;?>"></span>
		<?php
		
	}
	
	
        function check_external( $username , $password ){
            if(defined('ALT_AUTH') == true ){
                $auth_arr = explode(";", ALT_AUTH);
                $preauth = false;
                foreach( $auth_arr as $auth ){
                    if( $preauth == false ){
                        include('modules/auth/' . $auth . '.php');
                    }
                }
            }
            
            return $user_info;
        }
        
        
	function UserLogin($runat)
	{
	
		switch($runat){
			case 'local':
					if(count($_SESSION[post])>0 and $_SESSION[post]['login']=='Login'){
					extract($_SESSION[post]);
                                        
                                        
					$this->user_name=$user_name;
					//$this->password=$password;
                                        $this->password=hash("sha256", $password . SALT );
					unset($_SESSION[post]);
					}
			         //create client side validation
					$FormName='frm_employee';
					$ControlNames=array("user_name"=>array('user_name',"''","Please enter username","error_message_username"),
										"password"=>array('password',"''","Please enter password","error_message_password")
										);

					$ValidationFunctionName="CheckValidityLogin";
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,                    $ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;		
					
						?>						
						<form action="" name="<?php echo $FormName; ?>" enctype="multipart/form-data" method="post">
						<table width="100%" cellspacing="5" cellpadding="5"> 
						<tr> 
						<td class="lable">Username </td>
						<td class="field">
						<input type="text" name="user_name" id="user_name" value="<?php echo $this->user_name;?>"/>
						
						</td></tr>
						<tr>
						<td class="lable">Password </td>
						<td class="field">
						<input type="password" name="password" id="password" value=""/>
					
						</td></tr>
						<tr>
						<td colspan="2" align="center">
						<input type="submit" name="login"  value="Login" 
						 id="subbutt" onClick="return <?php echo $ValidationFunctionName?>();"/>
						</td></tr>
						</table>
						</form>	
						<?php
							
			
				break;
				case 'server':
						
						extract($_POST);
						$this->user_name=$user_name;
                                                $this->password=hash("sha256", $password . SALT );
						//$this->password=$password;
                                                
						
						//server side validation
					$return =true;
					if($this->Form->ValidField($user_name,'empty','Please enter username')==false)
						$return =false;
					if($this->Form->ValidField($password,'empty','Please enter password')==false)
						$return =false;
						
					if($return){
                                                $external_auth = $this->check_external( $this->user_name , $password );
                                                
						$sql="select * from ".TBL_USER." where user_name='$this->user_name' AND ( password='$this->password' OR md5(password)='". md5($password) ."' )";
				
						$result=$this->db->query($sql,__FILE__,__LINE__);
						
						if($this->db->num_rows($result)>0)
							{
								$row=$this->db->fetch_array($result);
                                                                // This prevents people from just putting hashed password's into slim
                                                                if( strlen( $row["password"]) == 64 && $row["password"] == $password ){
                                                                    $_SESSION['msg']='Looks like a hash injection, Please support 608-406-2338 ';
                                                                    $_SESSION[post]=$_POST;
                                                                } else {
                                                                    //Automaticly hashes the password if it is not already
                                                                   

                                                                    if($row['block']=='') 
                                                                        {
                                                                        $this->user_id=$row['user_id'];
                                                                        $this->first_name=$row['first_name'];
                                                                        $this->last_name=$row['last_name'];

                                                                        $sql_temp="select * from ".GROUP_ACCESS." a, ".TBL_USERGROUP." b 
                                                                                                where user_id=$this->user_id and a.group_id=b.group_id";
                                                                        $record_temp=$this->db->query($sql_temp,__FILE__,__LINE__);
                                                                        $group_ids = array();
                                                                        while($row_temp=$this->db->fetch_array($record_temp))
                                                                        {
                                                                        $group_ids[$row_temp['group_id']] = $row_temp['group_id'];
                                                                        //$this->groups[$row_temp['group_name']]=$row_temp['access_level'];
                                                                        $this->groups[$row_temp['group_name']]=$row_temp['group_name'];
                                                                        }
                                                                        $_SESSION['group_id'] = $group_ids;
                                                                        $this->auth->Create_Session($this->user_name,$this->user_id,$this->groups,$this->first_name,
                                                                                                                                $this->last_name);
                                                                                                                                            if( strlen( $row["password"]) != '64' ){
                                                                        $update_sql_array = array();
                                                                        $update_sql_array['password'] = hash('sha256' , $password . SALT ) ;
                                                                        $this->db->update(TBL_USER,$update_sql_array,"user_id",$this->user_id);
                                                                    }
                                                                        ?>
                                                                        <script type="text/javascript">
                                                                        window.location="welcome.php";
                                                                        </script>
                                                                        <?php
                                                                        
                                                                        exit();
                                                                    }
                                                                    else
                                                                    {
                                                                        $_SESSION['msg']='Your account has been blocked , Please contact Administrator.';
                                                                        $_SESSION[post]=$_POST;
                                                                    }
                                                                }
                                                                
                                                                
							}
							else
							{
								$_SESSION['msg']='Invalid username or password, please try again ...' . ALT_AUTH . $external_auth;
								$_SESSION[post]=$_POST;
							}
						?>
						<script type="text/javascript">
						window.location="login.php";
						</script>
						<?php
						exit();
						}
						else
						{
						$_SESSION[error]=$this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix;
						$this->UserLogin('local');
						}
			break;
			default : echo 'Wrong Paramemter passed';
		
		}
	}
	
	//function for change password
	function Reset_password($runat,$user_id){
		$this->user_id=$user_id;
		switch($runat){
			case 'local':
			         //create client side validation
					$FormName='frm_reset_password';
					$ControlNames=array(
										"repassword"	=>array('repassword',"RePassword","repeat password does not match",
																"error_message_password",'password')
										);

					$ValidationFunctionName="resetCheckValidity";
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,                    $ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;		
					
						?>						
						<form action="" name="<?php echo $FormName; ?>" enctype="multipart/form-data" method="post">
						<table width="100%" ><tr>
						<td class="lable">password:</td>
						<td class="field">
						<input type="password" name="password" id="password" value=""/>
						</td>
						</tr>
						<tr>
						<td class="lable">re-enter:</td>
						<td class="field">
						<input type="password" name="repassword" id="repassword" value=""/>
						</td>
						</tr>
						<tr><td colspan="2" align="center">
						<input type="submit" name="save" id="subbutt"  value="reset password" onClick="return <?php echo $ValidationFunctionName?>();"/>
						</td></tr>
						</table>
						</form>	
						<?php
				break;
				case 'server':
						extract($_POST);
				
						$return =true;
						if($this->Form->ValidField($password,'Password','Password field Empty or Invalid')==false)
							$return =false;
						if($this->Form->ValidField($repassword,'Password','Repeat password does not match',$password)==false)
							$return =false;
						if($return){
						$update_sql_array = array();
						$update_sql_array['password'] = hash('sha256' , $password . SALT ) ;
				    	$this->db->update(TBL_USER,$update_sql_array,"user_id",$this->user_id);
						$this->unsetResetFlag($this->user_id);
						$_SESSION['msg']='Your password chnaged successfully';
						?>
						<script type="text/javascript">
						window.location="login.php";
						</script>
						<?php
						exit();
						}
						else
						{
						$_SESSION[error]=$this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
						$this->Reset_password('local',$this->user_id);					
						}
			break;
			default : echo 'Wrong Paramemter passed';
		
		}
	}
		
		
	// function for forget password

	function ForgetPassword($runat){
		switch($runat){
			case 'local' :
							//Enter your registered email address and click 'Submit' button. You will shortly receive an email which will allow you to reset your password. 
							if(count($_POST)>0 and $_POST['submit']=='Send me reset instructions'){
							  extract($_POST);
							  $this->email_id = $email_id;
							}							
							$FormName = 'frm_forgetPassword';
							$ControlNames=array("email_id"	=>array('email_id',"EMail","Please enter email address","error_message_email"));
							$ValidationFunctionName="CheckValidity";
							
							$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
							echo $JsCodeForFormValidation;
							?>
							<div id="forgetpassword_form">
							<div id="error_message_email"></div>
							<h2>Can't sign in? Forget your password?</h2>
							<span id="help_text">Enter your email address below and we'll send you password reset instructions.
							</span>
							<h2>Enter your email address</h2>
							<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName?>" >
							<input type="text" name="email_id" id="email_id" value="<?php echo $this->email_id ?>" /><br />
							 <input type="submit" name="submit" id="subbutt" value="Send me reset instructions" onclick="return <?php echo $ValidationFunctionName?>();"/>
							</form>
							<br />
							<p>Nevermind, <a href="<?php echo $_SERVER['PHP_SELF'] ?>"><span class="italic_text">send me back to the sign in screen</span></a></p> 
							</div>
							<?php
							break;
			case 'server' :
							extract($_POST);
							$this->email_id = $email_id;
							
							//server side validation
							$return =true;
							if($this->Form->ValidField($email_id,'email','Have you typed your email address correctly?')==false)
								$return =false;
								
							if($return){
							$invalid_user = $this->CheckEmail($this->email_id);
							if(!$invalid_user){
							$sql = "select * from ".TBL_USER." where email_id='$this->email_id'";
							$record=$this->db->query($sql,__FILE__,__LINE__);
							$row = $this->db->fetch_array($record);
							$this->first_name = $row[first_name];
							$this->last_name = $row[last_name];
							$this->sendResetPasswordLink($row[user_id],$row[user_name],$row[email_id],$row[first_name]);
							$_SESSION['msg']="Instructions for signing in have been emailed to you";
							?>
							<script type="text/javascript">
								window.location="login.php";
							</script>
							<?php
							exit();
							}
							else {
							
								$_SESSION['msg']= "Sorry! we couldn't find anyone with that email address"; 
								?>
									<script type="text/javascript">
										window.location="login.php?index=Forget";
									</script>
								<?php
								exit();
							}
							} 
							else {
							$_SESSION[forget_error] = $this->Form->ErrorString; 
							$this->ForgetPassword('local');
							}
							break;
							
		}
	}	
	
	function Show_Group($runat ,$user_id=0,$type='Add'){
		switch($runat){
		
		case 'local':
		
					$sql="select * from ".GROUP_ACCESS." where user_id=$user_id";
					$record=$this->db->query($sql,__FILE__,__LINE__);
					while($row=$this->db->fetch_array($record))
					{
					$this->groups[]=$row[group_id];	
					$this->group_access[]=$row[access_level];		
					}

					$sql="select * from ".TBL_USERGROUP;
					$record=$this->db->query($sql,__FILE__,__LINE__);
					while($row=$this->db->fetch_array($record)){
					?>
					<div class="Clear">
					<div class="Label"><?php echo $row[group_name]?> :</div>
					<div class="Field">&nbsp;&nbsp;
					  <input type="checkbox" name="group[]" id="group[]" value= "<?php echo $row[group_id]?>" 
					<?php if(in_array($row[group_id],$this->groups)) echo 'checked="checked"';?>  />
					
					<?php /*?>
					<select name="access_level<?php echo $row[group_id]?>" id="access_level<?php echo $row[group_id]?>">
					<option value="Admin" <?php if($this->group_access[$this->getIndex($this->groups,$row[group_id])]=='Admin') { echo 'selected="selected"'; } ?>  >Admin</option>
					<option  <?php if($this->group_access[$this->getIndex($this->groups,$row[group_id])]=='User') { echo 'selected="selected"'; } ?> value="User">User</option>
					</select><?php */?>
					</div>
					</div>
					<?php
					}
					break;
					
		case 'server':
					extract($_POST);
					if($type=='Edit')
					{
					$sql="delete from ".GROUP_ACCESS." where user_id=$user_id";
					$this->db->query($sql,__FILE__,__LINE__);
					}				
	
					$this->user_id=$user_id;
					$this->groups=$group;
					if(count($this->groups)>0) {
					foreach($this->groups as $group)
					{
					$insert_sql_array = array();
					$insert_sql_array['group_id'] = $group;
					//$insert_sql_array['access_level'] =$_POST["access_level$group"];
					$insert_sql_array['user_id'] = $this->user_id;
				    $this->db->insert(GROUP_ACCESS,$insert_sql_array);
					}
					}
					
					break;
			default : echo 'Wrong Paramemter passed';
		}				
	}
	
	function getIndex($groups,$group_id)
	{
		$i=0;
		foreach($groups as $g){
		if($g==$group_id)
		return $i;
		$i++;
		}
		
	}

	function checkEmail($email_id)
	{
		$sql="select * from ".TBL_USER." where email_id='$email_id'";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0)
		return false;
		else return true;
	}	
	
	function checkUser($user_name,$email_id='')
	{
		$sql="select * from ".TBL_USER." where user_name='$user_name' or email_id='$email_id'";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0)
		return false;
		else return true;
	}	
	
	function checkGroup($group_name)
	{
		$sql="select * from ".TBL_USERGROUP." where group_name='$group_name'";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0)
		return false;
		else return true;
	}	
	
	//view all user

	function BlockUser($user_id)
	{
	   	$sql="update ".TBL_USER." set block='yes' where user_id=$user_id" ;
		$this->db->query($sql,__FILE__,__LINE__);
		$_SESSION['msg']='User has been blocked successfully';
		?>
		<script type="text/javascript">
		window.location="<?php echo $_SERVER['PHP_SELF'];?>";
		</script>
		<?php
		exit();
	}
        function remove_user_from_group( $group_id , $user_id ){
            $this->db->query("DELETE FROM group_access WHERE user_id = '$user_id' AND group_id = '$group_id'");
            return $this->ManageUsers_inner();
        }
	function delete_user( $user_id ){
            $this->db->query("DELETE FROM tbl_user WHERE user_id = '$user_id'");
            return $this->ManageUsers_inner();
        }
 	function delete_group( $group_id ){
            //the reqired = 0 is so we dont let people delete Admin group and csradmin group ( for eapi build )
            $this->db->query("DELETE FROM tbl_usergroup WHERE group_id = '$group_id' AND required = 0 ");
            return $this->ManageGroups_inner();
        }
	function UnblockUser($user_id)
	{
	   	$sql="update ".TBL_USER." set block='' where user_id=$user_id" ;
		$this->db->query($sql,__FILE__,__LINE__);
		$_SESSION['msg']='User has been Unblocked successfully';
		?>
		<script type="text/javascript">
		window.location="<?php echo $_SERVER['PHP_SELF'];?>";
		</script>
		<?php
		exit();
	}
        function create_new_group( $group_name , $desc ){
            $result = $this->db->query("SELECT * FROM tbl_usergroup WHERE group_name = '$group_name'");
            if( $this->db->num_rows($result) != 0 ){
                return '<div class="ui-state-error ui-corner-all">Group Already Exsists</div>' . $this->ManageGroups_inner();
            } else {
                $i = array();
                $i['group_name'] = $group_name;
                $i['group_description'] = $desc;
                $this->db->insert('tbl_usergroup', $i);
                return $this->ManageGroups_inner();
            }            
            return $this->ManageGroups_inner();
            
        }
        function create_new_user( $username , $password , $firstname='' , $lastname='' ){
            $result = $this->db->query("SELECT * FROM tbl_user WHERE user_name = '$username'");
            if( $this->db->num_rows($result) != 0 ){
                return '<div class="ui-state-error ui-corner-all">User Already Exsists</div>' . $this->ManageUsers_inner();
            } else {
                $i = array();
                $i['user_name'] = $username;
                $i['password'] = $password;
                $i['first_name'] = $firstname;
                $i['last_name'] = $lastname;
                $this->db->insert('tbl_user', $i);
                return $this->ManageUsers_inner();
            }
            
        }
        function add_user_to_group( $user_id , $group_id ){
            $result = $this->db->query("SELECT * FROM group_access WHERE user_id = '$user_id' AND group_id = '$group_id'");
            if( $this->db->num_rows($result) == 0 ){
                $i = array();
                $i['group_id'] = $group_id;
                $i['user_id'] = $user_id;
                $i['access_level'] = 'Admin';
                $this->db->insert('group_access' , $i);
                
            }
            return $this->ManageUsers_inner();
        }
        // A Replacement for Manage User
        function ManageUsers(){
            
            return "<div id='manage_users_div' >". $this->ManageUsers_inner()."</div>";
        }
        //CTLTODO Place all the javascript into slimcrm.js
        function ManageUsers_inner(){
            ob_start(); ?>
                <script>
function add_user(){
    var rand_id = Math.floor( Math.random() * 11 );
    html = '<table>';
    html = html + "<tr><td>username:</td><td> <input id=username" + rand_id + " ></td></tr>";
    html = html + "<tr><td>first name:</td><td> <input id=firstname" + rand_id + " ></td></tr>";
    html = html + "<tr><td>last name:</td><td> <input id=lastname" + rand_id + " ></td></tr>";
    html = html + "<tr><td>password: </td><td><input type=password id=password" + rand_id + " ></td></tr>";
    html = html + "<tr><td>password again:</td><td> <input type=password id=password2" + rand_id + " ></td></tr>";
    html = html + "</table><div id='add_user_errors' style='display: none' ></div>";
    $('body').append('<div id=user_add' + rand_id + ' >'+ html + '</div>');
    $('#user_add' + rand_id ).attr('title','New User' );
   $( '#user_add' + rand_id  ).dialog(
    { 
        close: function(event , ui){  
            $(this).remove();$(this).dialog('destroy'); 
        } , 
        resizable: false,
        height:200,
        modal: true,
        buttons: {
             'Create User' : function(){
                var username = $('#username' + rand_id).val();
                var firstname = $('#firstname' + rand_id).val();
                var lastname = $('#lastname' + rand_id).val();
                var password = $('#password' + rand_id).val();
                var password2 = $('#password2' + rand_id).val();
                if( username != '' && password == password2 ){
                     user.create_new_user( username , password , firstname , lastname , { target: 'manage_users_div'} );
                    $(this).remove();$(this).dialog('destroy');
                    
                } else {
                    error = "";
                    if( username == '' ){
                        error = error + 'username is blank<br/>';
                        //$('#add_user_errors').html('username is blank<br/>').addClass('ui-state-error').addClass('ui-corner-all');
                    }
                    if( password != password2 ){
                        error = error + 'passwords dont match<br/>';
                        $('#add_user_errors' ).html('passwords dont match<br/>').addClass('ui-state-error').addClass('ui-corner-all');
                    }
                    $('#add_user_errors').html(error).addClass('ui-state-error').addClass('ui-corner-all').show();;
                }
                 
                 
                 
               
            },
            Cancel: function() { 
                $(this).remove();$( this ).dialog( 'close' );
            }
        }
     });
}

		


function delete_user( user_id , display_name ){
    var rand_id = Math.floor( Math.random() * 11 );
    $('body').append('<div id=group_add' + rand_id + ' >Are you sure you want to delete ' + display_name + '</div>');
    $('#group_add' + rand_id ).attr('title','Delete ' + display_name );
    $( '#group_add' + rand_id  ).dialog(
    { 
        close: function(event , ui){  
            $(this).remove();$(this).dialog('destroy'); 
        } , 
        resizable: false,
        height:140,
        modal: true,
        buttons: {
            'Delete' : function(){
                user.delete_user( user_id , { target: 'manage_users_div'} );
                $(this).remove();$(this).dialog('destroy');
            },
            Cancel: function() { 
                $(this).remove();$( this ).dialog( 'close' );
            }
        }
     });
}

function remove_from_group( group_id , user_id , group_name , display_name ){
    var rand_id = Math.floor( Math.random() * 11 );
    $('body').append('<div id=group_add' + rand_id + ' >Are you sure you want to <br/>remove ' + display_name + ' from ' + group_name + '</div>');
    $('#group_add' + rand_id ).attr('title','Remove ' + display_name  + ' From ' + group_name );
    $( '#group_add' + rand_id  ).dialog(
    { 
        close: function(event , ui){  
            $(this).remove();$(this).dialog('destroy'); 
        } , 
        resizable: false,
        height:140,
        modal: true,
        buttons: {
            'Delete' : function(){
                user.remove_user_from_group( group_id , user_id , { target: 'manage_users_div'} );
                $(this).remove();$(this).dialog('destroy');
            },
            Cancel: function() { 
                $(this).remove();$( this ).dialog( 'close' );
            }
        }
     });
}
            
function add_to_group( user_id , display_name ){
        var rand_id = Math.floor( Math.random() * 11 );
        $('body').append('<div id=group_add' + rand_id + ' ><input id=group_ac_' + user_id + rand_id +' /></div>');
        $('#group_add' + rand_id ).attr('title','Add Groups To ' + display_name );
   $( '#group_add' + rand_id  ).dialog(
    { 
        close: function(event , ui){  
            $(this).remove();$(this).dialog('destroy'); 
        } , 
        resizable: false,
        height:140,
        modal: true,
        buttons: {
            
            Cancel: function() { 
                $(this).remove();$( this ).dialog( 'close' );
            }
        }
     });
    $('#group_ac_' + user_id + rand_id  ).autocomplete(
        {
            source: 'group_lookup.php' , 
            select: function( event, ui ) {
                user.add_user_to_group( user_id , ui.item.group_id , {target: 'manage_users_div' } );
                $('#group_ac_' + user_id + rand_id  ).val('').focus();
//                alert(ui.item.group_id);
            }
        }
    );
}
 </script>
             <a onclick="add_user();"><button>Add user<div class="add_button in_button">&nbsp;</button></a>
<!--             <a onclick="user.ManageUsers({target: 'manage_users_div'});"><button>refresh<div class="add_button in_button">&nbsp;</button></a>-->
            <table style="width: 100%;" > <?php
            $record=$this->GetAllUser();
            
            while($row=$this->db->fetch_array($record))
            { ?>
                <tr style="background: #A7CDF0;" >
                    <td style="background: #A7CDF0;" ><?php echo $row['user_name']; ?></td>
                    <td style="background: #A7CDF0;" ><?php echo $row['first_name']; ?></td>
                    <td style="background: #A7CDF0;" ><?php echo $row['last_name']; ?></td>
                    <td style="background: #A7CDF0;" >
                        <a onclick="add_to_group( '<?php echo $row['user_id']; ?>' , '<?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?>' );"><button>Add To Group<div class="add_button in_button">&nbsp;</button></a>
                        <a onclick="delete_user( '<?php echo $row['user_id']; ?>' , '<?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?>' );"><button>Delete User<div class="trash_can_normal in_button">&nbsp;</button></a>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <?php $groups = $this->GetGroupsList($row['user_id']);
                        //var_dump($groups);
                        foreach($groups as $g ){
//                            var_dump( $g );
                            echo "<a style='padding-left: 5px;' title='" . $g['group_description'] . "' >" .$g['group_name'] . "</a>"; ?>
                        <a onclick="remove_from_group( '<?php echo $g['group_id']; ?>' , '<?php echo $row['user_id']; ?>' , '<?php echo $g['group_name']; ?>' , '<?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?>' );" >
                            <div class="trash_can_normal " style="display: inline-block;">&nbsp;</div></a>
                        <?php
                        }
                        ?>
                    </td>
                </tr>
           <?php }
            
            ?> </table><?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
        function change_group_desc( $group_id , $desc ){
            $u = array();
            $u['group_description'] = $desc;
            $this->db->update('tbl_usergroup', $u , 'group_id', $group_id);
        }
        function ManageGroups( $overide=array() ){
            $options['div'] = 'manage_groups_div';
            foreach( $overide as $n => $v){
                $options[$n] = $v;
            }
            return "<div id='manage_groups_div' >". $this->ManageGroups_inner($options)."</div>";
        }
        
	function ManageGroups_inner(){
            ob_start();
            $options['div'] = 'manage_groups_div';
            foreach( $overide as $n => $v){
                $options[$n] = $v;
            }
            $div = $options['div'];
            $result = $this->db->query('SELECT * FROM tbl_usergroup');
            ?>
<script>

function delete_group( group_id , group_name  ){
    var rand_id = Math.floor( Math.random() * 11 );
    $('body').append('<div id=group_add' + rand_id + ' >Are you sure you want to delete ' + group_name + '</div>');
    $('#group_add' + rand_id ).attr('title','Delete ' + group_name );
    $( '#group_add' + rand_id  ).dialog(
    { 
        close: function(event , ui){  
            $(this).remove();$(this).dialog('destroy'); 
        } , 
        resizable: false,
        height:140,
        modal: true,
        buttons: {
            'Delete' : function(){
                user.delete_group( group_id , { target: '<?php echo $div;?>'} );
                $(this).remove();$(this).dialog('destroy');
            },
            Cancel: function() { 
                $(this).remove();$( this ).dialog( 'close' );
            }
        }
     });
}

function add_group(){
    var rand_id = Math.floor( Math.random() * 11 );
    html = '<table>';
    html = html + "<tr><td>group name:</td><td> <input id=groupname" + rand_id + " ></td></tr>";
    html = html + "<tr><td>description:</td><td> <input id=desc" + rand_id + " ></td></tr>";
    $('body').append('<div id=user_add' + rand_id + ' >'+ html + '</div>');
    $('#user_add' + rand_id ).attr('title','New Group' );
   $( '#user_add' + rand_id  ).dialog(
    { 
        close: function(event , ui){  
            $(this).remove();$(this).dialog('destroy'); 
        } , 
        resizable: true,
        height:200,
        modal: true,
        buttons: {
             'Create Group' : function(){
                var groupname = $('#groupname' + rand_id).val();
                var desc = $('#desc' + rand_id).val();
                if( 1 == 1 ){
                     user.create_new_group( groupname , desc  , { target: '<?php echo $div;?>'} );
                    $(this).remove();$(this).dialog('destroy');
                    
                } else {
                    error = "";
                    if( username == '' ){
                        error = error + 'username is blank<br/>';
                        //$('#add_user_errors').html('username is blank<br/>').addClass('ui-state-error').addClass('ui-corner-all');
                    }
                    if( password != password2 ){
                        error = error + 'passwords dont match<br/>';
                        $('#add_user_errors' ).html('passwords dont match<br/>').addClass('ui-state-error').addClass('ui-corner-all');
                    }
                    $('#add_user_errors').html(error).addClass('ui-state-error').addClass('ui-corner-all').show();;
                }
                 
                 
                 
               
            },
            Cancel: function() { 
                $(this).remove();$( this ).dialog( 'close' );
            }
        }
     });
}

</script>
<a onclick="add_group();"><button>Add Group<div class="add_button in_button"></div></button></a>
<table style="width: 100%;">
            <?php
            while( $row = $this->db->fetch_assoc($result)){
                $button_onclick = '';
                $disabled = '';
                if( $row['required'] == 1 ){
                    $button_onclick = "alert('" . $row['group_name'] . " is reqired for the system to operate');";
                    $disabled = 'disabled="disabled"';
                } else {
                    $button_onclick  = "delete_group( '" . $row['group_id'] . "' , '" . $row['group_name'] . "' )";
                }
              ?>
    <tr><td><?php echo $row['group_name']; ?></td><td style="text-align: right;"><a onclick="<?php echo $button_onclick; ?>" ><button <?php echo $disabled; ?> >Delete Group<div class="in_button trash_can_normal"></div></button></a></td></tr>
    <tr><td colspan="2" style="width: 100%;" ><input onchange="user.change_group_desc( '<?php echo $row["group_id"]; ?>' , $(this).val() , {} );" style="width: 100%;" value="<?php echo str_replace('"', "'", $row['group_description'] ); ?>" /></td></tr>
              <?php
            }
            ?>
</table>
    <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
        }
        
	function ManageUser()
	{
		?>
		<table cellpadding="5" cellspacing="5" width="90%">
		<tr>
		<th>User Name</th>
		<th>Name</th>
		<th>Action</th>
		</tr>
		<?php
		$record=$this->GetAllUser();
		while($row=$this->db->fetch_array($record))
		{
			?>
			<tr>
			<td><?php echo $row[user_name];?></td>
			<td><?php echo $row[first_name].' '.$row[middle_name].' '.$row[last_name];?></td>
			<td><a href="<?php $_SERVER['PHP_SELF']?>?user_id=<?php echo $row[user_id];?>&index=Edit">Edit</a> ,<a href="<?php $_SERVER['PHP_SELF']?>?user_id=<?php echo $row[user_id];?>&index=<?php if($row[block]==''){?>Block<?php } else { ?>Unblock<?php } ?>"><?php if($row[block]==''){?>Block<?php } else { ?>Unblock<?php } ?></a></td>
			</tr>
			<?php
		}
		?>
</table>
	
	
	<?
	
	} 

	/********************Group Management Related functions **************************************/
	
	function Manage_groups()
	{
		?>
		<div id='group_header'>
		Current Platform Groups:
		</div>
		<?php
		$sql="select * from ".TBL_USERGROUP." order by group_name";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		while($row=$this->db->fetch_array($record))
		{
		?>
		<div class="Clear" id="group<?php echo $row[group_id];?>"
		onmouseover="if(document.getElementById('action_<?php echo $row[group_id];?>'))document.getElementById('action_<?php echo $row[group_id];?>').style.display=''; " 
		onmouseout="if(document.getElementById('action_<?php echo $row[group_id];?>'))document.getElementById('action_<?php echo $row[group_id];?>').style.display='none'; ">
			<?php echo $this->DisplayGroup($row[group_id]); ?>
		</div>
		<?php
		}
	}
	
	function DisplayGroup($group_id,$mode='normal',$runat='',$disc='')
	{
		ob_start();
		$sql="select * from ".TBL_USERGROUP." where group_id=$group_id";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		$row=$this->db->fetch_array($record);
		
		switch($mode) {
		
		case 'normal':
		?>
					
			<span id="action_<?php echo $group_id;?>"  style="display:none;" class="group_action">
			<img src="images/edit.gif" border="0"  align="absmiddle" 
			onclick="user.DisplayGroup(<?php echo $group_id;?>,'edit', 'local',
										{onUpdate: function(response,root){ 
										document.getElementById('group<?php echo $group_id;?>').innerHTML=response;
										user.GetUsersList(<?php echo $group_id;?>,'SelectList', 
										{target:'select_group_<?php echo $group_id; ?>', preloader: 'prl'}); 
										}, preloader: 'prl'}  
										);return false;"/>
			</span>

			<table width="90%">
			<tr><td width="4%" valign="middle">
			<img src="images/person.gif"  alt="Current Picture"/>
			</td>
			<td width="27%" valign="top">
			<h3><?php echo $row[group_name]; ?></h3>
			<p class="gray small_text"><?php echo $row[group_description]; ?></p>			</td>
			<td width="3%" valign="top" class="verysmall_text">&nbsp;</td>
			<td width="56%" valign="top" class="verysmall_text"><span class="textb">Users: </span>
			<span><?php echo $this->GetUsersList($group_id,'Csv'); ?></span></td>
			<td width="3%" valign="top">&nbsp;</td>
			<td width="7%" valign="top">
			<img src="images/trash.gif"  border="0" 
			onclick="if(confirm('are you sure ?')){user.deleteGroup(<?php echo $group_id;?>,'<?php echo $row[group_name]; ?>', 
										{target:'group<?php echo $group_id;?>', preloader: 'prl'});}"/></td>
			</tr>
			</table>
		<?php
		break;
		
		case 'edit': $this->EditUserGroup($runat,$group_id,$disc);
		break;
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;

	}
	
	function deleteGroup($group_id,$group_name)
	{
		$sql_group="delete from ".TBL_USERGROUP." where group_id=$group_id";
		$sql_group_access="delete from ".GROUP_ACCESS." where group_id=$group_id";
		$sql_elementpermession="delete from ".TBL_ELEMENT_PERMISSION." where access_to='$group_name'";
		
		$this->db->query($sql_group,__LINE__,__FILE__);
		$this->db->query($sql_group_access,__LINE__,__FILE__);
		$this->db->query($sql_elementpermession,__LINE__,__FILE__);
		
		return '';
	}
	
	function RemoveUserFromGroup($user_id,$group_id,$type='group',$ajaxObj='user')
	{
		$sql="delete from ".GROUP_ACCESS." where group_id=$group_id and user_id=$user_id";
		$this->db->query($sql,__LINE__,__FILE__);
		
		if($type=='group')
		return $this->GetUsersList($group_id,'CsvWithDeleteLink');
		else
		return $this->GetGroupsList($user_id,'CsvWithDeleteLink',false,$ajaxObj);
	}
		
		
	function AddUserToGroup($user_id,$group_id,$type='group',$ajaxObj='user')
	{
		$insert_sql_array = array(); 
		$insert_sql_array['group_id'] = $group_id;
		$insert_sql_array['user_id'] = $user_id;
		$this->db->insert(GROUP_ACCESS,$insert_sql_array);

		if($type=='group')
		return $this->GetUsersList($group_id,'CsvWithDeleteLink');
		else
		return $this->GetGroupsList($user_id,'CsvWithDeleteLink',false,$ajaxObj);
	}
	
	
	function GetUsersList($group_id,$type='',$user_id=false)
	{
		$sql="select * from ".GROUP_ACCESS." a, ".TBL_USER." b where a.group_id=$group_id and a.user_id=b.user_id";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		$output='';	
		switch($type){
		
		case 'Csv':	$i=0;
					$count=$this->db->num_rows($record);
					while($row=$this->db->fetch_array($record)) 
					{
						if(!$user_id)
						$output.=$row['user_name'];
						else
						$output.=$row['user_id'];
						if($i!=$count-1) $output.=', ';
						$i++;
					}
					if($output=='' and $user_id)
					$output="''";
					return $output;
		break;
		
		case 'SelectList':
					//$options=array();
					//$options[0] = array("value" => "", "text" => ""); 
					
					$output=$this->GetUsersList($group_id,'Csv',true);
					$sql="select DISTINCT b.user_id, b.user_name from ".TBL_USER." b where b.user_id NOT IN ($output)";
					$record=$this->db->query($sql,__LINE__,__FILE__);
					
					$output="<select name='select_group_$group_id' id='idselect_group_$group_id'
					  class='verysmall_text'  onchange='if(this.value!=\"\")
					  {
					  	javascript:
						if(confirm(\"are you sure to add \"+this.text+\" to this group ?\")){
						user.AddUserToGroup(this.value,$group_id,
						{onUpdate: function(response,root){ 
										document.getElementById(\"textareadiv_group_$group_id\").innerHTML=response;
										user.GetUsersList($group_id,\"SelectList\", {target:\"select_group_$group_id\", preloader: \"prl\"}); 
										}}  
										);
						}else{
					  this.options[0].selected = true; 
					  this.selectedIndex=0;}
					  
					  }'>";
					$output.="<option value=''></option>";
					while($row=$this->db->fetch_array($record)) 
					{
					$output.="<option value='$row[user_id]'>$row[user_name]</option>";
					//$options[] = array("value" => $row[user_id], "text" => $row[user_name]); 
					}
					$output.="</select>";
					return $output;
		
		
		break;
		
		case 'CsvWithDeleteLink': $i=0;
					$count=$this->db->num_rows($record);
					while($row=$this->db->fetch_array($record)) 
					{
						$output.=$row['user_name']."<img 
						onClick='javascript:
						if(confirm(\"are you sure ?\")){
						user.RemoveUserFromGroup($row[user_id],$row[group_id],
						{onUpdate: function(response,root){ 
										document.getElementById(\"textareadiv_group_$group_id\").innerHTML=response;
										user.GetUsersList($group_id,\"SelectList\", 
										{target:\"select_group_$group_id\", preloader: \"prl\"}); 
										}}  
										);
						}'
						src='images/trash.gif'  border='0'/>";
						
						if($i!=$count-1) 
						$output.=', ';
						
						$i++;
					}
					return $output;
		
		break;
		
		default :
		
		
		}
	}
	
	/**********************************************************************************************************/
	
	/****************************************User Management Related functions*********************************/
	
	function Manage_users()
	{
		?>
		<div id='group_header'>
		Current Platform Users:
		</div>
		<?php
		$sql="select * from ".TBL_USER." order by user_name";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		while($row=$this->db->fetch_array($record))
		{
		?>
		<div class="Clear" id="user<?php echo $row[user_id];?>"
		onmouseover="if(document.getElementById('action_<?php echo $row[user_id];?>'))document.getElementById('action_<?php echo $row[user_id];?>').style.display=''; " 
		onmouseout="if(document.getElementById('action_<?php echo $row[user_id];?>'))document.getElementById('action_<?php echo $row[user_id];?>').style.display='none'; ">
			<?php echo $this->DisplayUser($row[user_id]); ?>
		</div>
		<?php
		}
	}
	
	function DisplayUser($user_id,$mode='normal',$runat='',$first_name='',$last_name='',$ajaxObj='user',$editfunction='DisplayUser',$deleteFunction='deleteUser')
	{
		ob_start();
		$sql="select * from ".TBL_USER." where user_id=$user_id";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		$row=$this->db->fetch_array($record);

		switch($mode) {
		
		case 'normal':
		?>
					
			<span id="action_<?php echo $user_id;?>"  style="display:none;" class="group_action">
			<img src="images/edit.gif" border="0"  align="absmiddle" 
			onclick="<?php echo $ajaxObj;?>.<?php echo $editfunction; ?>(<?php echo $user_id;?>,'edit', 'local','','',
				<?php 
						if($editfunction=='Edit_Agent')
						{
						?>
							'','','',
						<?php } ?>
					'<?php echo $ajaxObj;?>','<?php echo $editfunction; ?>',
										{onUpdate: function(response,root){ 
										document.getElementById('user<?php echo $user_id;?>').innerHTML=response;
										<?php echo $ajaxObj;?>.GetGroupsList(<?php echo $user_id;?>,'SelectList',false,'<?php echo $ajaxObj; ?>', 
										{target:'select_user_<?php echo $user_id; ?>', preloader: 'prl'}); 
										}, preloader: 'prl'}  
										);return false;"/>
			</span>

			<table width="90%">
			<tr><td width="4%" valign="middle">
			<?php if(!file_exists($this->directory.'/'.$row[image]) or $row[image]=='') { ?><a href="profile.php?id=<?php echo $user_id; ?>"><img src="images/person.gif" border="0"  alt="Current Picture"/></a><?php } else {?><div style="height:50px; overflow:hidden; padding-right:5px;"><a href="profile.php?id=<?php echo $user_id; ?>"><img src="thumb.php?file=<?php echo $this->directory.'/'.$row[image] ?>&sizex=100&sizey=70" border="0"  alt="<?php echo $row[first_name] ?>" width="50"/></a></div><?php } ?>
			</td>
			<td width="27%" valign="top">
			<h3><?php echo $row[first_name].' '.$row[last_name]; ?></h3>
			<p class="gray small_text"><a href="mailto:<?php echo $row[email_id]; ?>"><?php echo $row[email_id]; ?></a></p>			</td>
			<td width="3%" valign="top">&nbsp;</td>
			<td width="42%" valign="top" class="verysmall_text"><span class="textb">Groups: </span>
			<span><?php echo $this->GetGroupsList($user_id,'Csv'); ?></span></td>
			<td width="2%" valign="top">&nbsp;</td>
			<td width="16%" valign="top">
			<span class="verysmall_text">
			<a href="#" onclick="javascript:
								if(confirm('are you sure ?')){
									<?php echo $ajaxObj; ?>.sendResetPasswordLink(<?php echo $user_id;?>,
																'<?php echo $row[user_name]; ?>',
																'<?php echo $row[email_id]; ?>',
																'<?php echo $row[first_name]; ?>',
																{  }
																);
								}return false;">
			Reset Password</a></span></td>
			<td width="6%" valign="top">
			<img src="images/trash.gif"  border="0" 
			onclick="if(confirm('are you sure ?')){<?php echo $ajaxObj; ?>.<?php echo $deleteFunction; ?>(<?php echo $user_id;?>,'<?php echo $row[user_name]; ?>', 
										{target:'user<?php echo $user_id;?>', preloader: 'prl'});}"/></td>
			</tr>
			</table>
		<?php
		break;
		
		case 'edit': 
						$this->EditUser($runat,$user_id,$first_name,$last_name,$ajaxObj,$editfunction);
					break;
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;

	}
	
	function deleteUser($user_id,$user_name)
	{
		$sql_group="delete from ".TBL_USER." where user_id=$user_id";
		$sql_group_access="delete from ".GROUP_ACCESS." where user_id=$user_id";
		
		$this->db->query($sql_group,__LINE__,__FILE__);
		$this->db->query($sql_group_access,__LINE__,__FILE__);
		
		return '';
	}
	
	
	function GetGroupsList($user_id,$type='',$group_id=false,$ajaxObj='user')
	{
		$sql="select * from ".GROUP_ACCESS." a, ".TBL_USERGROUP." b where a.user_id=$user_id and a.group_id=b.group_id";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		$output='';	
		switch($type){
		
		case 'Csv':	$i=0;
					$count=$this->db->num_rows($record);
					while($row=$this->db->fetch_array($record)) 
					{
						if(!$group_id)
						$output.=$row['group_name'];
						else
						$output.=$row['group_id'];
						if($i!=$count-1) $output.=', ';
						$i++;
					}
					if($output=='' and $group_id)
					$output="''";
					return $output;
		break;
		
		case 'SelectList':

					$output=$this->GetGroupsList($user_id,'Csv',true);
					$sql="select b.group_id, b.group_name from ".TBL_USERGROUP." b where  b.group_id NOT IN ($output)";
					
					$record=$this->db->query($sql,__LINE__,__FILE__);
					
					$output="<select name='select_group_$group_id' id='idselect_group_$group_id'
					  class='verysmall_text'  onchange='if(this.value!=\"\")
					  {
					  	javascript:
						if(confirm(\"are you sure to add \"+this.text+\" to this group ?\")){
						$ajaxObj.AddUserToGroup($user_id,this.value,\"user\",\"$ajaxObj\",
						{onUpdate: function(response,root){ 
										document.getElementById(\"textareadiv_user_$user_id\").innerHTML=response;
										$ajaxObj.GetGroupsList($user_id,\"SelectList\", \"\",\"$ajaxObj\",
										{target:\"select_user_$user_id\", preloader: \"prl\"}); 
										}}  
										);
						}else{
					  this.options[0].selected = true; 
					  this.selectedIndex=0;}
					  
					  }'>";
					$output.="<option value=''></option>";
					while($row=$this->db->fetch_array($record)) 
					{
					$output.="<option value='$row[group_id]'>$row[group_name]</option>";
					//$options[] = array("value" => $row[user_id], "text" => $row[user_name]); 
					}
					$output.="</select>";
					return $output;		
		
		break;
		
		case 'CsvWithDeleteLink': $i=0;
					$count=$this->db->num_rows($record);
					while($row=$this->db->fetch_array($record)) 
					{
						$output.=$row['group_name']."<img 
						onClick='javascript:
						if(confirm(\"are you sure ?\")){
						$ajaxObj.RemoveUserFromGroup($row[user_id],$row[group_id],\"user\", \"$ajaxObj\",
						{onUpdate: function(response,root){ 
										document.getElementById(\"textareadiv_user_$user_id\").innerHTML=response;
										$ajaxObj.GetGroupsList($user_id,\"SelectList\",  \"\",\"$ajaxObj\",
										{target:\"select_user_$user_id\", preloader: \"prl\"}); 
										}}  
										);
						}'
						src='images/trash.gif'  border='0'/>";
						
						if($i!=$count-1) 
						$output.=', ';
						
						$i++;
					}
					return $output;
		
		break;
		
		default :
                    $return = array();
                    while( $row = $this->db->fetch_assoc($record)){
                        $return[] = $row;
                    }
                    return $return;
		}
	}

	
	
	/******************New**************************/
	function sendResetPasswordLink($user_id,$user_name,$email_id,$first_name='')
	{
		$flag=$this->setResetFlag($user_id,$user_name);
		
		
		$uri=explode('/',$_SERVER[SCRIPT_NAME]);

		$scriptpath = str_replace($uri[count($uri)-1],'',$_SERVER[SCRIPT_NAME]);

		$_SERVER['FULL_URL'] = 'http://' . $_SERVER['SERVER_NAME'] . $scriptpath.'password_reset.php?id='.$flag; 		

		ob_start();
		?>
			<div bgcolor="#f5f6f7" link="#0099cc" alink="#0099cc" vlink="#0099cc" style="text-align: left;">
			<div style="width: 100%; margin: 30px 0pt;">
			<center>
			<table border="0" cellpadding="0" cellspacing="0" width="700">
			  <tr>
				<td style="padding-top: 10px; padding-bottom: 0pt; text-align: left;">
						<center>
						 <table border="0" cellpadding="0" cellspacing="0" width="650">
						   <tbody><tr>
							 <td style="padding: 15px; font-family: Helvetica,Arial,sans-serif; font-size: 16px; line-height: 1.3em; text-align: left;">
			  					<p> Platform  </p>
							 </td>
						   </tr>
						   <tr>
							 <td style="padding: 10px 15px 40px; font-family: Helvetica,Arial,sans-serif; font-size: 16px; line-height: 1.3em; text-align: left;" valign="top">
			<h1 style="font-family: Helvetica,Arial,sans-serif; color: rgb(34, 34, 34); font-size: 28px; line-height: normal; letter-spacing: -1px;">Reset your Platform password</h1>
			
			<p>Hi <?php echo $first_name; ?>,</p>
			
			<p>Can't remember your password? Don't worry about it � it happens. We can help.</p>
			
			<p>Your username is: <strong><?php echo $user_name; ?></strong></p>
			
			<p><b>Just click this link to reset your password:</b><br>
			
			<a href="<?php echo $_SERVER['FULL_URL'];?>" target="_blank"><?php echo $_SERVER['FULL_URL'];?></a></p>
							   
			<hr style="margin-top: 30px; border-right: medium none; border-width: 1px medium medium; border-style: solid none none; border-color: rgb(204, 204, 204) -moz-use-text-color -moz-use-text-color;">
			
			<p style="font-size: 13px; line-height: 1.3em;"><b>Didn't ask to reset your password?</b><br>
			If you didn't ask for your password, it's likely that another user entered your username or email address by mistake while trying to reset their password. If that's the case, you don't need to take any further action and can safely disregard this email.</p>
			
							</td>
						  </tr>
						</tbody></table>
					  </center>
				</td>
			  </tr>
			</tbody></table>
			
			</center>
			</div>
			
			</div>
	<?php
		$html = ob_get_contents();
		ob_end_clean();
		$objMail = new PHPMailer();
		$objMail->From = "no-reply@platform.com";
		$objMail->FromName = "No Reply";
		$objMail->Subject = "Reset your password";
		
		$objMail->IsHTML(true);
		$objMail->Body = $html;
		$objMail->AddAddress($email_id,$first_name);	
		$objMail->Send();
	
	}
	
	function setResetFlag($user_id,$user_name)
	{
		$update_sql_array = array();
		$update_sql_array['flag'] = md5($user_name);
		
		$this->db->update(TBL_USER,$update_sql_array,"user_id",$user_id);
		return $update_sql_array['flag'];
	
	}
	
	function unsetResetFlag($user_id)
	{
		$update_sql_array = array();
		$update_sql_array['flag'] = '';
		
		$this->db->update(TBL_USER,$update_sql_array,"user_id",$user_id);
	
	}
	
	function GetUserIDfromflag($flag)
	{
		$sql="select * from ".TBL_USER." where flag='$flag'";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		$row=$this->db->fetch_array($record);
		return $row[user_id];
		
	}
	function checkResetlink($flag)
	{
		$sql="select * from ".TBL_USER." where flag='$flag'";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		$cnt=$this->db->num_rows($record);
		if($cnt) return true;
		else return false;
	
	}
	
	function GetUserNameById($user_id){
		$sql = "select * from ".TBL_USER." where user_id='$user_id'";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		$row=$this->db->fetch_array($record);
		return $row[user_name];
	}
	
	function GetUsersJson($user_name,$to){
		$user_json = "";
		switch($to){
			case 'user' :
				$sql = "select * from ".TBL_USER." where user_name!='$user_name'";
				$record=$this->db->query($sql,__LINE__,__FILE__);
				while($row = $this->db->fetch_array($record)){
					$user_json .= '{"caption":"'.$row[first_name].' '.$row[last_name].' ('.$row[user_name].')'.'","value":"'.$row[user_name].'"},';
				}
				break;
			case 'group' :
				$sql = "select * from ".TBL_USERGROUP." order by group_name";
				$record=$this->db->query($sql,__LINE__,__FILE__);
				while($row = $this->db->fetch_array($record)){
					$user_json .= '{"caption":"'.$row[group_name].'","value":" group:'.$row[group_name].'"},';
				}
				break;
		}

		
		$user_json = '['.substr($user_json,0,strlen($user_json)-1).']';
		return $user_json;
	}
	
	function Valid_Password($password){
                $password = hash("sha256" , $password . SALT );
		if($password){
			$sql = "select * from ".TBL_USER." where user_id = '$this->user_id' and password = '$password'";
			$record=$this->db->query($sql,__FILE__,__LINE__);
			if($this->db->num_rows($record)>0)
				return true;
			else 
				return false;
		} else {
			return false;
		}
	}
	
	function Change_Password($runat,$user_id){
		$this->user_id=$user_id;
		switch($runat){
			case 'local':
			         //create client side validation
					$FormName='frm_change_password';
					$ControlNames=array("oldpassword"		=>array('oldpassword',"Password","invalid old password","spanoldpassword"),
										"newpassword"		=>array('newpassword',"Password","invalid new password","spannewpassword"),
										"newrepassword"	=>array('newrepassword',"RePassword","repeat password does not match",
																"spannewrepassword",'newpassword')
										);

					$ValidationFunctionName="Validator_change_password";
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,                    $ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;		
					
						?>	
						<table width="100%" class="table">
		<tr>
			<td colspan="2"><a href="#" id="password_link" <?php if($_POST[flag]!='') echo "style='display:none;'"; else echo "style='display:block;'";  ?> onclick="document.getElementById('password_form').style.display=''; this.style.display='none'; return false;">change password</a><br />
			<div class="form_bg" <?php if($_POST[flag]=='') echo "style='display:none;'"; else echo "style='display:block;'";  ?> id="password_form">					
						<form action="" name="<?php echo $FormName; ?>" enctype="multipart/form-data" method="post">
						<input type="hidden" name="flag" id="flag" value="change" />
						<table width="100%" >
						<tr><td colspan="2">	
							<ul>
							<li><span id="spanoldpassword" class="normal"></span></li>
							<li><span id="spannewpassword" class="normal"></span></li>
							<li><span id="spannewrepassword" class="normal"></span></li>
							</ul>
						</td></tr>
						<tr>
						<th >old password:</th>
						<td >
						<input type="password" name="oldpassword" id="oldpassword" value=""/>
						</td>
						</tr>
						<tr>
						<th >new password:</th>
						<td >
						<input type="password" name="newpassword" id="newpassword" value=""/>
						</td>
						</tr>
						<tr>
						<th >new password re-enter:</th>
						<td >
						<input type="password" name="newrepassword" id="newrepassword" value=""/>
						</td>
						</tr>
						<tr><td colspan="2" align="center">
						<input type="submit" name="submit" id="subbutt" style="width:auto"  value="change password" onClick="return <?php echo $ValidationFunctionName?>();"/> or <span class="Clear"><a href="#" onclick="document.getElementById('password_form').style.display='none'; document.getElementById('password_link').style.display=''; return false;" >cancel</a></span>
						</td></tr>
						</table>
						</form>	
						</div>
			</td>
		</tr>
		</table>
						<?php
				break;
				case 'server':
						extract($_POST);
						if($this->Valid_Password($oldpassword)){
						$return =true;
						if($this->Form->ValidField($oldpassword,'Password','Old Password field Empty or Invalid')==false)
							$return =false;
						if($this->Form->ValidField($newpassword,'Password','New Password field Empty or Invalid')==false)
							$return =false;
						if($this->Form->ValidField($newrepassword,'Password','Repeat password does not match',$password)==false)
							$return =false;
						if($return){
						$update_sql_array = array();
						$update_sql_array['password'] = hash("sha256" , $newpassword . SALT );
				    	$this->db->update(TBL_USER,$update_sql_array,"user_id",$this->user_id);
						$_SESSION['msg']='Your password changed successfully';
						?>
						<script type="text/javascript">
						window.location="<?php echo $_SERVER['PHP_SELF'] ?>";
						</script>
						<?php
						exit();
						}
						else
						{
						$_SESSION[error]=$this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
						$this->Change_Password('local',$this->user_id);					
						}
						}
						else {
						    echo $this->Form->ErrtxtPrefix."Invalid password".$this->Form->ErrtxtSufix;
							$this->Change_Password('local',$this->user_id);	
							exit();
						}
			break;
			default : echo 'Wrong Paramemter passed';
		
		}
	}
	
	function Add_Update_Profile_Button($runat,$validation)
	{	
		switch($runat){
		
		case 'local' :
					?>
					<input type="submit" name="submit" id="submit" style="width:auto" value="Update Profile" onclick="return <?php echo $validation ?>()" /> or 
					<a href="<?php echo $_SERVER['PHP_SELF'].'?id='.$this->user_id; ?>">cancel</a>
	<?php
		break;
		default :
		 //empty runat action.
		}			
	}
	
	function profileBasic_Info($row){
	?>
			<tr>
			    <td colspan="2"><h3>Editing Profile</h3></td>
			</tr>
			<tr>
			<td valign="top" >
			<?php if(!file_exists($this->directory.'/'.$row[image]) or $row[image]==''){ ?><img src="images/person.gif" /><?php } else{ ?>
			<div style="max-width:250px; overflow:hidden"><img src="thumb.php?file=<?php echo $this->directory.'/'.$row[image] ?>&sizex=250" /></div><?php } ?>
			</td>
			<td width="80%">
				<table width="90%" class="table">
					<input type="hidden" name="old_file_name" id="old_file_name" value="<?php echo $row[image];?>" />
				<tr>
					<th >First Name:</th>
				  <td ><input type="text" name="first_name" id="first_name" value="<?php echo $row[first_name]; ?>" /></td>
				</tr>
<!--				<tr>
					<th>Middle Name:</th>
					<td><input type="text" name="middle_name" value="<?php echo $row[middle_name]; ?>" /></td>
				</tr>-->
				<tr>
					<th>Last Name:</th>
					<td><input type="text" name="last_name" id="last_name" value="<?php echo $row[last_name]; ?>" /></td>
				</tr>
				<tr>
					<th>Phone:</th>
					<td><input type="text" name="phone" id="phone" value="<?php echo $row[phone]; ?>" /></td>
				</tr>
				<tr>
					<th>Mobile:</th>
					<td><input type="text" name="mobile"  id="mobile" value="<?php echo $row[mobile]; ?>" /></td>
				</tr>
				<tr>
					<th>Home Phone:</th>
					<td><input type="text" name="home_phone"  id="home_phone" value="<?php echo $row[home_phone]; ?>" /></td>
				</tr>
				<tr>
					<th>Fax:</th>
					<td><input type="text" name="fax"  id="fax" value="<?php echo $row[fax]; ?>" /></td>
				</tr>
				<tr>
					<th>Website:</th>
					<td><input type="text" name="website"  id="website" value="<?php echo $row[website]; ?>" /></td>
				</tr>
                               <tr>
					<th>Image:</th>
					<td><input type="file" id="image" name="image" value="" /></td>
				</tr>
                                <?php
                                    if( PHONE_SYSTEM == "asterisk"){
                                        $this->asterisk->edit_settings( $this->user_id);

                                    }
				
                                    if( EMAIL_SYSTEM == "gmail"){
                                        $this->Google_Account('local',$row[user_id],$row);
                                    }
                                ?>
				<?php

					if( WEBMAIL == true  && EMAIL_SYSTEM == "roundcube" ){
						$this->eml->settings('local', $row["user_id"] );
								}
						$encpw = $_POST["email_password_enc"];

						if( WEBMAIL == true && $encpw != "***NOPASSSET***" && $encpw != '' ){
							$this->eml->settings('server', $row["user_id"] );
						}
				?>

			</table></td></tr>
	<?php
	}
	
	function profilePassword_Change(){
	?>
		
			<?
			if($_POST['submit']=='change password')
				$this->Change_Password('server',$this->user_id);
			else
				$this->Change_Password('local',$this->user_id);
			?>
			
			
	<?php
	}

	
	function Update_Profile($runat,$user_id){
		$this->user_id = $user_id;
		switch($runat){
			case 'local' :
						if(count($_POST)>0 and $_POST['submit']=='Update Profile'){
						  extract($_POST);
						  $this->first_name = $first_name;
						  $this->middle_name = $middle_name;
						  $this->last_name = $last_name;
						  $this->mobile = $mobile;
						  $this->phone = $phone;
						  $this->website = $website;
						}
						$formName = 'frm_update_profile';
						$ControlNames=array("first_name" =>array('first_name',"''","Please enter Name","spanname"),
											"google_apps_id"			=>array('google_apps_id',"VEmail","Please enter valid google apps email","spangoogle_apps_id"));

						$validation="Validator_edit_office";
					
						$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($formName,$ControlNames,$validation,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						$sql="select * from ".TBL_USER." where user_id='$this->user_id'"; 
						$record=$this->db->query($sql,__FILE__,__LINE__);
						$row=$this->db->fetch_array($record);
						?>
						<form method="post" action="" enctype="multipart/form-data" name="<?php echo $formName?>" >
						<table width="100%" class="table">
						<tr><td colspan="2">	
							<ul>
							<li><span id="spanname" class="normal"></span></li>
							<li><span id="spangoogle_apps_id" class="normal"></span></li>
							<!--<li><span id="spanphone" class="normal"></span></li>-->
							</ul>
						</td></tr>			
						<?php 
						$this->profileBasic_Info($row); 
						?>
						<tr><td colspan="2" align="right">
						<?php
						$this->Add_Update_Profile_Button('local',$validation);
						?>
						</td></tr></table></form>
						<?php
						break;
			case 'server' :
						extract($_POST);
						$this->first_name = $first_name;
						$this->middle_name = $middle_name;
						$this->last_name = $last_name;
						$this->mobile = $mobile;
						$this->home_phone = $home_phone;
						$this->fax = $fax;
						$this->website = $website;
						$this->image = $image;
						$this->phone = $phone;
						$this->old_file_name=$old_file_name;
						
							//server side validation
						$return =true;
						if($this->Form->ValidField($first_name,'empty','User name field is Empty or Invalid')==false)
							$return =false;
						
						if($return){
						if($_FILES[image][name]!="")
						{
							$type = $this->objFileUpload->CheckFileType($_FILES[image][type]);

							if( $type == 'true') 
							{
								$this->objFileUpload->UploadMode         = "Edit";
								$this->objFileUpload->IsSaveByRandomName = true;
								$this->objFileUpload->OldFileName=$this->old_file_name;
								$this->objFileUpload->UploadContent =$_FILES[image];
								$this->objFileUpload->UploadFolder =$this->directory;
								$this->objFileUpload->NeedReturnStatement = true;
								$file=$this->objFileUpload->Upload();
								$file_names=explode("|",$file);
								$this->image=$file_names[1];
							}
							else
							{
								echo $this->Form->ErrtxtPrefix.'<li>Invalid image, please uplaod jpg, gif or png images only</li>'.$this->Form->ErrtxtSufix; 
								$this->Update_Profile('local',$this->user_id);
								exit();
							}

						}
						else
							$this->image=$this->old_file_name;
							
						$update_sql_array = array();
						$update_sql_array['first_name'] = $this->first_name;
						$update_sql_array['middle_name'] = $this->middle_name;
						$update_sql_array['last_name'] = $this->last_name;
						$update_sql_array['mobile'] = $this->mobile;
						$update_sql_array['home_phone'] = $this->home_phone;
						$update_sql_array['fax'] = $this->fax;
						$update_sql_array['website'] = $this->website;
						$update_sql_array['phone'] = $this->phone;
						$update_sql_array['image'] = $this->image;
						
						$this->db->update(TBL_USER,$update_sql_array,'user_id',$this->user_id);
						if( WEBMAIL == true ){
						$this->eml->settings('server', $this->user_id );
								}
						$this->Google_Account('server',$this->user_id);
						
						$_SESSION['msg']='Profile has been saved successfully';
						?>
						<script type="text/javascript">
							window.location = "<?php echo $_SERVER['PHP_SELF'].'?id='.$this->user_id; ?>"
						</script>
						<?php
						exit();
						} else {
							echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
							$this->Update_Profile('local',$this->user_id);
						}
						break;
			default :
						echo 'wrong parameter passed';
		}
	}
	
		
	function ProfileUser_Start($row){
	?>
			<table width="100%" class="table">
		    <tr>
		      <td width="12%"></td>
		      <td width="65%">&nbsp;</td>
		      <td width="23%" align="right" class="small_text"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?id=<?php echo $row[user_id];?>&index=Edit">edit profile </a></td>
	      </tr>
		    <tr>
			    <td valign="top"><div id="upload_area"><?php if(!file_exists($this->directory.'/'.$row[image]) or $row[image]==''){ ?><img src="images/person.gif" /><?php } else{ ?>
			<div style="max-width:250px; overflow:hidden"><img src="thumb.php?file=<?php echo $this->directory.'/'.$row[image] ?>&sizex=250" /></div><?php } ?></div>
				
				<?php /*?>	<div id="log" style="display:none;" >
					<form action="UpdateImage.php" method="post"  enctype="application/x-www-form-urlencoded" onsubmit="return sendForm(this);">
							<input type="hidden" name="user_id" value="<?php echo $row[user_id] ?>"
							<input type="hidden" name="maxSize" value="9999999999" />
							<input type="hidden" name="maxW" value="200" />
							<input type="hidden" name="fullPath" value="<?php echo $this->directory ?>/" />
							<input type="hidden" name="relPath" value="<?php echo $this->directory ?>/" />
							<input type="hidden" name="colorR" value="255" />
							<input type="hidden" name="colorG" value="255" />
							<input type="hidden" name="colorB" value="255" />
							<input type="hidden" name="maxH" value="300" />
							<input type="hidden" name="filename" value="filename" />
							<p><input type="file" name="filename" id="filename" value="filename"  width="321px" onchange="this.form.submit();" /> or 
							<a href="#" onClick="document.getElementById('log').style.display='none';document.getElementById('changepic').style.display='';return false;"/>cancel</p>
					</form>
				</div>

				<a href="#" id="changepic" onClick="document.getElementById('log').style.display='';document.getElementById('changepic').style.display='none'; return false;">change photo</a>
									<?php */?>
					<?php
							/*if(isset($_POST[filename]))
							{
								
								$filename = strip_tags($_REQUEST['filename']);
								$maxSize = strip_tags($_REQUEST['maxSize']);
								$maxW = strip_tags($_REQUEST['maxW']);
								$fullPath = strip_tags($_REQUEST['fullPath']);
								$relPath = strip_tags($_REQUEST['relPath']);
								$colorR = strip_tags($_REQUEST['colorR']);
								$colorG = strip_tags($_REQUEST['colorG']);
								$colorB = strip_tags($_REQUEST['colorB']);
								$maxH = strip_tags($_REQUEST['maxH']);
								$filesize_image = $_FILES[$filename]['size'];
								if($filesize_image > 0){
									$upload_image = $this->objFileUpload->uploadImage($filename, $maxSize, $maxW, $fullPath, $relPath, $colorR, $colorG, $colorB, $maxH);
									if(is_array($upload_image)){
										foreach($upload_image as $key => $value) {
											if($value == "-ERROR-") {
												unset($upload_image[$key]);
											}
										}
										$document = array_values($upload_image);
										for ($x=0; $x<sizeof($document); $x++){
											$errorList[] = $document[$x];
										}
										$imgUploaded = false;
									}else{
										$imgUploaded = true;
									}
								}else{
									$imgUploaded = false;
									$errorList[] = "File Size Empty";
								}
							
								if($imgUploaded){
									echo "<script>document.getElementById(\"upload_area\").innerHTML = \"<img src='".$upload_image."' border='0' /> \"</script>";
									
									
								}else{
									echo '<img src="images/error.gif" width="16" height="16px" border="0" style="marin-bottom: -3px;" /> Error(s) Found: ';
									foreach($errorList as $value){
											echo $value.', ';
									}
								}


							}*/// end of isset 
					
					
						?>
					</td>
				<td><table width="100%" class="table">
                  <tr>
                    <td colspan="2" class="profile_head"><?php echo $row[first_name].' '.$row[middle_name].' '.$row[last_name] ?></td>
                  </tr>
                  <tr>
                    <th width="28%">work:</th>
                    <td width="72%"><?php echo $row[phone] ?></td>
                  </tr>
                  <tr>
                    <th>mobile:</th>
                    <td><?php echo $row[mobile] ?></td>
                  </tr>
                  <tr>
                    <th>home:</th>
                    <td><?php echo $row[home_phone] ?></td>
                  </tr>
                  <tr>
                    <th>fax:</th>
                    <td><?php echo $row[fax] ?></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <th>email:</th>
                    <td><a href="mailto:<?php echo $row[email_id] ?>"><?php echo $row[email_id] ?></a></td>
                  </tr>
                  <tr>
                    <th>website:</th>
                    <td><?php echo $row[website] ?></td>
                  </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="2" class="profile_head">Login information: </td>
                  </tr>
                  <tr>
                    <th>username:</th>
                    <td><?php echo $row[user_name] ?></td>
                  </tr>
                  <tr>
                    <th>password:</th>
                    <td>******<?php //echo $row[password] ?></td>
                  </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                  <?php if( PHONE_SYSTEM == "asterisk"){
                        $this->asterisk->display_settings( $this->user_id );
                      ?>

                  
                  <?php } //This ends PHONE_SYSTEM == asterisk?>
                  <?php if( EMAIL_SYSTEM == "gmail"){ ?>
                  <tr>
                    <td colspan="2" class="profile_head">Google Apps login: </td>
                  </tr>

				  <?php
                                    
                                        $this->Google_Account_Display($row);
                                    }
                                  ?>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                  </tr>
	<?php
	}
	
	function ProfileUser_End(){
	?>
                </table></td>
				<td>&nbsp;</td>
			</tr>
		</table>
	<?php
	}
	
	
	function Display_Profile($user_id){
		$this->user_id = $user_id;
		$sql="select * from ".TBL_USER." where user_id='$this->user_id'"; 
		$record=$this->db->query($sql,__FILE__,__LINE__);
		$row=$this->db->fetch_array($record);
		$this->ProfileUser_Start($row);
		$this->ProfileUser_End();
	}
	
	function Google_Account($index,$user_id,$row=''){
		$this->user_id = $user_id;
		switch($index){
			case 'local' :
						?>
						    <tr>
							    <th>Google Apps Id:</th>
								<td><input type="text" name="google_apps_id" id="google_apps_id" value="<?php echo $row[google_apps_id] ?>"  /></td>
							</tr>
						    <tr>
							    <th>Google Apps Password:</th>
								<td><input type="text" name="google_apps_password" id="google_apps_password" value="<?php echo $row[google_apps_password] ?>"  /></td>
							</tr>
						<?php
						break;
			case 'server' :
						extract($_POST);
						$this->google_apps_id = $google_apps_id;
						$this->google_apps_password = $google_apps_password;
						
						$update_sql_array[google_apps_id] = $this->google_apps_id;
						$update_sql_array[google_apps_password] = $this->google_apps_password;
						$this->db->update(TBL_USER,$update_sql_array,'user_id',$this->user_id);

		}
	}
	
	function Google_Account_Display($row){
	?>
		<tr>
			<th>username:</th>
			<td><?php echo $row[google_apps_id] ?></td>
		</tr>
		<tr>
			<th>password:</th>
			<td><?php echo $row[google_apps_password]; ?></td>
		</tr>
	<?php
	}

	function UpdatePhoto($_POST,$_FILES){
		/*print_r($_FILES);
		echo $_FILES['filename']['name'];
		*/$filename = strip_tags($_POST['filename']);
		$maxSize = strip_tags($_POST['maxSize']);
		$maxW = strip_tags($_POST['maxW']);
		$fullPath = strip_tags($_POST['fullPath']);
		$relPath = strip_tags($_POST['relPath']);
		$colorR = strip_tags($_POST['colorR']);
		$colorG = strip_tags($_POST['colorG']);
		$colorB = strip_tags($_POST['colorB']);
		$maxH = strip_tags($_POST['maxH']);
		$filesize_image = $_FILES[$filename]['size'];
		if($filesize_image > 0){
			$upload_image = $this->objFileUpload->uploadImage($filename, $maxSize, $maxW, $fullPath, $relPath, $colorR, $colorG, $colorB, $maxH);
			if(is_array($upload_image)){
				foreach($upload_image as $key => $value) {
					if($value == "-ERROR-") {
						unset($upload_image[$key]);
					}
				}
				$document = array_values($upload_image);
				for ($x=0; $x<sizeof($document); $x++){
					$errorList[] = $document[$x];
				}
				$imgUploaded = false;
			}else{
				$imgUploaded = true;
			}
		}else{
			$imgUploaded = false;
			$errorList[] = "File Size Empty";
		}
	$this->image = $upload_image;
	$update_sql_array['image'] = $this->image;
	$this->db->update(TBL_USER,$update_sql_array,'user_id',$_POST[user_id]);
	}
        
        /*  The new class of user stuff
         *  There can be ABSOLUTLY NO html code in the same function as a database call, 
         * Variables should be passed as nested arrays 
         * 
         * Also no echoing out, return everything in a string
         */
        function invite_user_form_html(){
            $return = '<form action="" enctype="multipart/form-data" method="post" name="AddUser">
					<table class="table" width="100%">
					<tr><th>email:</th>
					<td colspan="2">
					<input type="text" name="email_id" id="email_id" value="" />
					</td>
					</tr>
					
					<tr>
					<td colspan="3" align="right"><input type="hidden" name="inviteUser" value="yes"/>
					<input type="submit" name="submit" id="submit" value="Invite User"   style="width:auto"  />
					</td></tr>
					</table>
					</form>	';
            return $return;
        }
        function hash_invite( $url ){
            include("/var/www/production/slimcrm/class/salt.php");
                  $return = sha1( $url . $invite_salt );
                  $invite_salt = '';
                  return "SALT=" . $return;
        }
        function Get_current_folder(){
                    $request = $_SERVER["REQUEST_URI"];
                    $r_arr = explode("/" , $request);
                    $count = count($r_arr);
                    $x=0;
                    while( $x < $count - 1 ){
                        $return = $r_arr[$x];
                        $x++;
                    }
                    return  $return;
                }
        /*  You may give this muliple user's and it will send out an invite to each one
         *  to invite one user simply pass array( array( "email" => 'someuser@somedomain' )) 
         * ** Please note the nested array
         * 
         * extra config per user
         * validtill = unixtime ( or strtotime output ), This is the expiration date of the invite
         * 
         */
        function invite_users( $array ){
            foreach( $array as $user ){
                if( isset( $user["validtill"]) ){
                    $validtill = $user["validtill"];
                } else {
                    $validtill = strtotime("+1 week");
                }
                    $platform = $this->Get_current_folder();
                    $url = "https://www.slimcrm.com/invite.php?platform=$platform&validtill=$validtill&";
                    $url = $url . $this->hash_invite($url);
                    $headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    mail( $user["email"] , "You Have Been Invited to slimcrm.com by " . $_SESSION["user_name"] , $_SESSION["user_name "] . " Would like to have you as a user of his SlimCRM </br>\n" . 
                            "To join click on the following link <a href='$url' >HERE</a> </br>\n" .
                            "or paste the following link into your web browser </br>\n$url\n</br>ps: Make sure to paste the entire link in or it will not work " , $headers );
                    
            }
        }

}
?>
