<?php

/***********************************************************************************

			Class Discription : 
			
			Class Memeber Functions :
			
			
			Describe Function of Each Memeber function: 
			 
									
									 

************************************************************************************/
class Event_Contacts extends Company_Global
{
	
	var $username;
	var $password;
	var $em_email;
	var $event_auth;
	var $contact_id;
	var $lat;
	var $long;
	var $flag=0;	
	var $event_id;
	var $date;
	var $start_time;
	var $end_time;
	var $note;	
	var $zip_obj;
	var $mail_obj;
					
	function __construct(){
		parent::__construct();
		$this->event_auth=new Event_Mgt_Auth();
		$this->zip_obj = new zipcode_class();
	}
	
	
	function addEvent_contact($runat){
	
	switch($runat){
				
				case 'local':
						
						//Display Form
						if(count($_POST[person])>0){
						extract($_POST[person]);
						$this->first_name=$first_name;
						$this->last_name=$last_name;
						$this->title=$title;
						$this->company=$company_name;
						$this->comments=$comments;
						$this->username=$username;
						$this->em_email=$em_email;
						$this->type='People';
						}
						//create client side validation
						$FormName='frm_Add_Events';
						$ControlNames=array("person_first_name"			=>array('person_first_name',"''","Required !! ","span_person_first_name"),
											"person_last_name"			=>array('person_last_name',"''","Required !! ","span_person_last_name"),
											/*"username"					=>array('username',"UserName","enter a valid username","span_person_username"),
											"password"					=>array('password',"Password","invalid password","span_person_password"),
											"em_email"					=>array('em_email',"EMail","invalid Email","span_person_email")*/
											);
	
						$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,
												$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						?>
						<div class="new_party_dialog" id="new_person_dialog" style="overflow: visible;">
					 	 <div class="container edit">
						<div class="Left">
						  <div class="col">	
						 <form action="" autocomplete="off" class="new_person" id="new_person" method="post" 
						 enctype="multipart/form-data" name="<?php echo $FormName; ?>">
						<?php $this->basicinfo_contact();  ?>
						
<?php /*?>	//............................Webapp related code...............................//

					<div class="page_header">
						<table class="subject_header">
							<tbody><tr>
							<td class="name" style="vertical-align: middle;">
						  <table class="contact_types" cellpadding="0" cellspacing="0">
						  <tr>
						  <th><h2>Username </h2></th>
						  <td><input type="text" name="username" id="username" size="30" value="<?php echo $this->username; ?>" />
						  <ul id="error_list"><li><span id="span_person_username"></span></li></ul></td>
						  </tr>
						  <tr>
						  <th><h2>Password </h2></th>
						  <td><input type="password" name="password" id="password" size="30" />
						  <ul id="error_list"><li><span id="span_person_password"></span></li></ul></td>
						  </tr>
						  <tr>
						  <th><h2>Email </h2></th>
						  <td><input type="text" name="em_email" id="em_email" size="30" value="<?php echo $this->em_email; ?>" />
						  <ul id="error_list"><li><span id="span_person_email"></span></li></ul></td>
						  </tr>
						  </table>
						  </td></tr>
						  </tbody>
						  </table>	
						  </div><?php 
						  
		//....................................................................................................//				  
						  */?>
						<div class="innercol">
						  <table class="contact_types" id="contact_section" cellpadding="0" cellspacing="0">
						  <tbody>
						<?php 
						$this->AddContactDeatils('local');
						?>
						  </tbody></table></div>
						  </form></div></div></div></div>
						  <script type="text/javascript">
								new Autocomplete("query", { serviceUrl:"CompanyList.php" });
							</script>
						<?php
						
						//create client side validation
						$ControlNames=array("frm_People_Contacts'"=>
						array('frm_People_Contacts',"Type","Invalid Image Type !!","spanpicture")
											);
						$this->ValidationFunctionName = 'Type';
	
						$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
					break;		
				case 'server':
							
						 
						  
						 //Reading Post Data
/*						 extract($_POST);
						 $this->username=$username;
						 $this->password=$password;
						 $this->em_email=$em_email;*/
						 
						
						 
						//$check_username=$this->checkusername($this->username);
						$check_username=true;
						if($check_username == true){
						
						 $this->AddContactPeople('server');
						
						/* $insert_sql_array = array();
						 $insert_sql_array[username] = $this->username;
						 $insert_sql_array[password] = $this->password;
						 $insert_sql_array[email] = $this->em_email;
						 $insert_sql_array[contact_id] = $this->contact_id;
						 
						 $this->db->insert(EM_WEB_APP_INFO,$insert_sql_array);*/
						 
						 
						 
						 exit();
						}
						else
						{
						echo '<div class="errortxt"><li>Sorry !! This username already exist</li></div>'; 
						$this->addEvent_contact('local');
						}
	
						 
	
			   break;
			   default : echo 'Wrong Paramemter passed';		
	
	}// end of switch()
	
	}// end of addwebappinfo()


	function editEvent_Contact($runat,$contact_id,$editFunction='editEvent_Contact',$type=''){
	$this->contact_id = $contact_id;
		if($type=='Company')
		{
			$this->EditContactCompany($runat,$this->contact_id);
		}
		else
		{
			
				switch($runat){
							
							case 'local':
									//$sql="Select * from ".TBL_CONTACT." a,".EM_WEB_APP_INFO." b where a.contact_id='$this->contact_id' and a.contact_id = b.contact_id";
									$sql="Select * from ".TBL_CONTACT." where contact_id='$this->contact_id'";
									$result=$this->db->query($sql,__FILE__,__LINE__);
									$row=$this->db->fetch_array($result);
									
									/*$sql_em="Select * from ".EM_WEB_APP_INFO." where contact_id = '$row[contact_id]'";
									$result_em=$this->db->query($sql_em,__FILE__,__LINE__);
									$row_em=$this->db->fetch_array($result_em);*/
									
									//Display Form
									if(count($_POST[person])>0){
									extract($_POST[person]);
									$this->first_name=$first_name;
									$this->last_name=$last_name;
									$this->title=$title;
									$this->company=$company_name;
									$this->comments=$comments;
									$this->type='People';
									}
									//create client side validation
									$FormName='frm_Edit_Events';
									$ControlNames=array("person_first_name"			=>array('person_first_name',"''","Required !! ","span_person_first_name"),
														"person_last_name"			=>array('person_last_name',"''","Required !! ","span_person_last_name")
														);
				
									$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,
															$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
									echo $JsCodeForFormValidation;
									?>
									<div class="new_party_dialog" id="new_person_dialog" style="overflow: visible;">
									 <div class="container edit">
									<div class="Left">
									  <div class="col">	
									 <form action="" autocomplete="off" class="new_person" id="new_person" method="post" 
									 enctype="multipart/form-data" name="<?php echo $FormName; ?>">
									 <input type="hidden" name="person[old_file_name]" value="<?php echo $row[picture];?>" />
									
									
									<div class="innercol">
									  <table class="contact_types" id="contact_section" cellpadding="0" cellspacing="0">
									  <tbody>
									<?php 
									$this->EditContactDeatils('local');
									?>
									  </tbody></table></div>
									  </form></div></div></div></div>
									  <script type="text/javascript">
											new Autocomplete("query", { serviceUrl:"CompanyList.php" });
										</script>
									<?php
									
									//create client side validation
									$ControlNames=array("frm_People_Contacts'"=>
									array('frm_People_Contacts',"Type","Invalid Image Type !!","spanpicture")
														);
									$this->ValidationFunctionName = 'Type';
				
									$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
									echo $JsCodeForFormValidation;
									
								break;		
							case 'server':
										extract($_POST);
										
									  
									 //Reading Post Data
									 
									/* $this->username=$username;
						             $this->password=$password;
									 $this->em_email=$em_email;
									 */
									 
									 //$check_username=$this->checkusername($this->username,$this->contact_id);
									 $check_username=true;
									 if($check_username == true or trim($this->username)==''){
									 
									 $this->EditContactPeople('server',$this->contact_id,'editEvent_Contact');
						
									 /* $sql_web_app = "Select * from ".EM_WEB_APP_INFO." where contact_id = '$this->contact_id'";
									 $result_web_app=$this->db->query($sql_web_app,__FILE__,__LINE__);
									 
									 if($this->db->num_rows($result_web_app)>0){
									
									 $update_sql_array = array();
									 if(trim($this->username) !='') { $update_sql_array[username] = $this->username; }
						             $update_sql_array[password] = $this->password;
									 $update_sql_array[email] = $this->em_email;
									 
									 $this->db->update(EM_WEB_APP_INFO,$update_sql_array,'contact_id',$this->contact_id);
									 }
									 else{
									 $insert_sql_array = array();
									 $insert_sql_array[contact_id] = $this->contact_id;
									 $insert_sql_array[username] = $this->username;
						             $insert_sql_array[password] = $this->password;
									 $insert_sql_array[email] = $this->em_email;
									 
									 $this->db->insert(EM_WEB_APP_INFO,$insert_sql_array);
									 }
									 
									 exit(); */
									 }
									else
									{
									echo '<div class="errortxt"><li>Sorry !! This username already exist</li></div>'; 
									$this->editEvent_Contact('local', $this->contact_id);
									}								
										 
						   break;
						   default : echo 'Wrong Paramemter passed';		
				
				}// end of switch()
		}
	
	}
	
	
	function ContactSignin($runat)
	{
	
		switch($runat){
			case 'local':
					if(count($_SESSION[post])>0 and $_SESSION[post]['login']=='Login'){
					extract($_SESSION[post]);
					$this->username=$username;
					$this->password=$password;
					unset($_SESSION[post]);
					}
			         //create client side validation
					$FormName='frm_contactSignin';
					$ControlNames=array("username"=>array('username',"''","Please enter username","error_message_username"),
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
						<input type="text" name="username" id="username" value="<?php echo $this->username;?>"/>
						
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
						$this->username=$username;
						$this->password=$password;
						
						//server side validation
				
						$sql="select * from ".EM_WEB_APP_INFO." where username='$this->username' AND password='$this->password'";
				
						$result=$this->db->query($sql,__FILE__,__LINE__);
						
						if($this->db->num_rows($result)>0)
							{
								$row=$this->db->fetch_array($result);
								
								if($row[password]!=''){

								$this->contact_id=$row['contact_id'];
								$this->username=$row['username'];
								
								$sql2="select * from ".TBL_CONTACT." where contact_id='$this->contact_id'";
								$result2=$this->db->query($sql2,__FILE__,__LINE__);
								$row2=$this->db->fetch_array($result2);
								
								$this->first_name=$row2['first_name'];
								$this->last_name=$row2['last_name'];
								
								$sql3="select * from ".EM_CONTACT_STATUS." where contact_id='$this->contact_id'";
								$result3=$this->db->query($sql3,__FILE__,__LINE__);
								$row3=$this->db->fetch_array($result3);
								
								if ($row3['user_status'] == 'Active' )
								{

									$this->event_auth->Create_Session($this->username,$this->contact_id,'',$this->first_name,$this->last_name);

									$sql_login="select * from ".EM_WEB_APP_INFO." where contact_id='$this->contact_id'";
									$result_login=$this->db->query($sql_login,__FILE__,__LINE__);
									$row_login=$this->db->fetch_array($result_login);
									
									$update_sql_array = array();
									$update_sql_array[last_accessed] = $row_login[last_login];
									$this->db->update(EM_WEB_APP_INFO,$update_sql_array,'contact_id',$this->contact_id);

									$update_sql_array = array();
									$update_sql_array[last_login] = time();
									$this->db->update(EM_WEB_APP_INFO,$update_sql_array,'contact_id',$this->contact_id);
									?>
									<script type="text/javascript">
									window.location="welcome.php";
									</script>
									<?php
									exit();
								}
								else	
								{
									$_SESSION['msg']='Your account is not active!! Contact administrator';
									$_SESSION[post]=$_POST;
								}
								}
							}
							else
							{
								$_SESSION['msg']='Invalid username or password, please try again ...';
								$_SESSION[post]=$_POST;
							}
						?>
						<script type="text/javascript">
						window.location="contact_login.php";
						</script>
						<?php
						exit();
						
			break;
			default : echo 'Wrong Paramemter passed';
		
		}
	}	
	
	function lastLogout($contact_id)
	{
		$update_sql_array = array();
		$update_sql_array[last_login] = time();
		$this->db->update(EM_WEB_APP_INFO,$update_sql_array,'contact_id',$contact_id);
	}
	
	function sendResetPasswordLink($contact_id,$contact_user_name,$email_id,$first_name='', $basepath='')
	{
		if($contact_user_name=='' and $email_id==''){
			$ret = "user level access has not been set for this contact please edit this contact and generate a user name and password with a primary email address";
			return $ret;
		} else {
		$flag=$this->setResetFlag($contact_id,$contact_user_name);
		
		$uri=explode('/',$_SERVER[SCRIPT_NAME]);
		$scriptpath = str_replace($uri[count($uri)-1],'',$_SERVER[SCRIPT_NAME]);
		$scriptpath = str_replace($uri[count($uri)-2].'/','',$scriptpath);
		
		if($basepath!='') $scriptpath='/webapp'.$scriptpath;
		$_SERVER['FULL_URL'] = 'http://' . $_SERVER['SERVER_NAME'] . $scriptpath.'password_reset_contact.php?id='.$flag; 
		
		//echo $_SERVER['FULL_URL'];
		/*$url = $_SERVER[SCRIPT_URI];
		echo $url;
		$url_in1 = strrpos($url,'/');
		$uri1 = substr($url,0,$url_in1);
		$url_in2 = strrpos($uri1,'/');
		$uri2 = substr($uri1,0,$url_in2);
		$script_name = $uri2.'password_reset_contact.php?id='.$flag;
		$_SERVER['FULL_URL'] = $script_name;
		echo $script_name;
		$script_name='/em/webapp/password_reset_contact.php?id='.$flag;
		$_SERVER['FULL_URL'] = 'http';
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on')
		 {
   		 	$_SERVER['FULL_URL'] .=  's';
		 }
		$_SERVER['FULL_URL'] .=  '://';
		
		if($_SERVER['SERVER_PORT']!='80')  {
			$_SERVER['FULL_URL'] .=$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$script_name;
		} else {
			$_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'].$script_name;
		}*/



		//write email
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
			  					<p> Completemobiledentistry  </p>
							 </td>
						   </tr>
						   <tr>
							 <td style="padding: 10px 15px 40px; font-family: Helvetica,Arial,sans-serif; font-size: 16px; line-height: 1.3em; text-align: left;" valign="top">
			<h1 style="font-family: Helvetica,Arial,sans-serif; color: rgb(34, 34, 34); font-size: 28px; line-height: normal; letter-spacing: -1px;">Reset your Completemobiledentistry password</h1>
			
			<p>Hi <?php echo $first_name; ?>,</p>
			
			<p>Can't remember your password? Don't worry about it � it happens. We can help.</p>
			
			<p>Your username is: <strong><?php echo $contact_user_name; ?></strong></p>
			
			<p><b>Just click this link to reset your password: All Passwords must be over 6 characters long</b><br>
			
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
		return 0;
		}
	
	}
	
	function setResetFlag($contact_id,$contact_user_name)
	{
		$update_sql_array = array();
		$update_sql_array['flag'] = md5($contact_user_name);
		
		$this->db->update(EM_WEB_APP_INFO,$update_sql_array,"contact_id",$contact_id);
		return $update_sql_array['flag'];
	
	}
	
	function unsetResetFlag($contact_id)
	{
		$update_sql_array = array();
		$update_sql_array['flag'] = '';
		
		$this->db->update(EM_WEB_APP_INFO,$update_sql_array,"contact_id",$contact_id);
	
	}
	
	function GetContactID($field='',$value='')
	{	
		if($field=='' and $value=='')
		{
		return parent::GetContactID();
		}
		else
		{
		$sql="select * from ".EM_WEB_APP_INFO." where ".$field."='".$value."'";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		$row=$this->db->fetch_array($record);
		return $row[contact_id];
		}
		
	}
	function checkResetlink($flag)
	{
		$sql="select * from ".EM_WEB_APP_INFO." where flag='$flag'";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		$cnt=$this->db->num_rows($record);
		if($cnt) return true;
		else return false;
	
	}
	


	function Contact_Reset_password($runat,$contact_id){
		$this->contact_id=$contact_id;
		switch($runat){
			case 'local':
			         //create client side validation
					$FormName='frm_reset_password';
					$ControlNames=array("password"		=>array('password',"Password","invalid password","error_message_password"),
										"repassword"	=>array('repassword',"RePassword","repeat password does not match",
																"error_message_repassword",'password')
										);

					$ValidationFunctionName="resetCheckValidity";
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,                    $ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;		
					
						?>						
						<form action="" name="<?php echo $FormName; ?>" enctype="multipart/form-data" method="post">
						<table width="100%" ><tr>
						<td >password:</td>
						<td >
						<input type="password" name="password" id="password" /><span id="error_message_password"></span>
						</td>
						</tr>
						<tr>
						<td >re-enter:</td>
						<td >
						<input type="password" name="repassword" id="repassword" /><span id="error_message_repassword"></span>
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
				
						
						$update_sql_array = array();
						$update_sql_array['password'] = $password;
				    	$this->db->update(EM_WEB_APP_INFO,$update_sql_array,"contact_id",$this->contact_id);
						$this->unsetResetFlag($this->contact_id);
						$_SESSION['msg']='Your password changed successfully. Please login with new password.';
						?>
						<script type="text/javascript">
						window.location="contact_login.php";
						</script>
						<?php 
						
			break;
			default : echo 'Wrong Paramemter passed';
		
		}
	} // end of pass reset


	function checkusername($username,$contact_id='')
	{
		if($contact_id!=''){
			$sql="select * from ".EM_WEB_APP_INFO." where username='$username' and contact_id<>'$contact_id'";
		}
		else
		    $sql="select * from ".EM_WEB_APP_INFO." where username='$username'";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0) return false;
		else return true;
		break;		
	} // end of checkusername
	
	// function for forget password

	function ForgetPassword($runat, $basepath=''){
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
							$sql = "select * from ".EM_WEB_APP_INFO." a,".TBL_CONTACT." b where a.email='$this->email_id' and a.contact_id=b.contact_id";
							$record=$this->db->query($sql,__FILE__,__LINE__);
							$row = $this->db->fetch_array($record);
							$this->first_name = $row[first_name];
							$this->last_name = $row[last_name];
							
							$this->sendResetPasswordLink($row[contact_id],$row[username],$row[email],$row[first_name], $basepath);
							$_SESSION['msg']="Instructions for signing in have been emailed to you";
							?>
							<script type="text/javascript">
								window.location="contact_login.php";
							</script>
							<?php
							exit();
							}
							else {
							
								$_SESSION['msg']= "Sorry! we couldn't find anyone with that email address"; 
								?>
									<script type="text/javascript">
										window.location="contact_login.php?index=Forget";
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
	
	function checkEmail($email_id)
	{
		$sql="select * from ".EM_WEB_APP_INFO." where email='$email_id'";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0)
		return false;
		else return true;
	}	
	
	function addLocation($runat,$contact_id,$target,$lat='',$long='') {
	ob_start();
	$sql_type = "select * from ".EM_CONTACT_LOCATION." where ".CONTACT_ID." = '".$contact_id."'";
	$result = $this->db->query($sql_type,__FILE__,__LINE__);
	$row = $this->db->fetch_array($result);
	if ($this->db->num_rows($result)>0)
	$this->flag=1;
	else $this->flag=0;
	switch($runat) {
	case 'local':			 				
					if(count($_POST)>0 and $_POST['submit']=='Ok'){
					  extract($_POST);
					  $this->lat = $lat;
					  $this->long = $long;
					}
					else if($this->flag==1) {
					  $this->lat = $row['lat'];
					  $this->long = $row['long'];					  
					}							
					$FormName = 'frm_location';
					$ControlNames=array("lat"	=>array('lat',"Double","Plz Enter the valid lat no..","error_message_lat"),
										"long"	=>array('long',"Double","Plz Enter the valid long no..","error_message_long")
										);
					$ValidationFunctionName="CheckValidityLocation";
					
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
					?>
					<div class="prl">&nbsp;</div>
					<div id="lightbox" >
					 
						<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
						<div id="TB_ajaxWindowTitle">Add Loacation</div>
						<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_credential').style.display='none';"><img border="0" src="images/close.gif" alt="close" /></a></div>
					</div>
					<div  class="white_content">
					<div style="padding:20px;">
					<ul id="error_list">
						<li><span id="error_message_lat" ></span></li>
						<li><span id="error_message_long" ></span></li>					
					</ul>	
					
					<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName ;?>" >
					  <table class="table" align="center">
						  <tr>
							<td align="right" class="textb">Enter Lat</td>
							<td><input type="text" name="lat" id="lat" value="<?php echo $this->lat ;?>" /></td>
							<td align="right" class="textb">Enter Long</td>
							<td><input type="text" name="long" id="long" value="<?php echo $this->long ;?>" /></td>
							<td><input type="button" name="submit" id="submit" value="Ok" onclick="javascript: 
					 if(<?php echo $ValidationFunctionName; ?>()) {
					  em.addLocation('server',<?php echo $contact_id; ?>,'<?php echo $target; ?>',this.form.lat.value,this.form.long.value,{target:'div_credential', preloader: 'prl'}); } return false;"/></td>
						  </tr>
					  </table>

					  
					  
					</form></div></div></div>
					</div>
					<?php
					break;

	
	case 'server':					
					extract($_POST);
					$this->contact_id= $contact_id;
					$this->lat=$lat;
					$this->long=$long;				
					$return =true;
					
					if($this->Form->ValidField($lat,'Double','Please Enter Lat Number')==false)
					$return =false;
					
					if($this->Form->ValidField($long,'Double','Please Enter Long Number')==false)
					$return =false;
					
					if($return) {				
						if($this->contact_id){
							if($this->flag==0) {
								$insert_sql_array = array();
								$insert_sql_array[contact_id] = $this->contact_id;
								$insert_sql_array[lat] = $this->lat;
								$insert_sql_array[long] = $this->long;																		
								$this->db->insert(EM_CONTACT_LOCATION,$insert_sql_array);
								}
							else {
								$update_sql_array = array();
								$update_sql_array[contact_id] = $this->contact_id;
								$update_sql_array[lat] = $this->lat;
								$update_sql_array[long] = $this->long;															
								$this->db->update(EM_CONTACT_LOCATION,$update_sql_array,contact_id,$this->contact_id);
							}								
						}								
					}
						
					break;					
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	
	
	function showPermitionlevel($contact_id)
	{
	
		ob_start();
			$this->contact_id=$contact_id;
			$sql="select * from ".EM_CONTACT_STATUS." where contact_id='".$this->contact_id."'";
			$result = $this->db->query($sql,__FILE__,__LINE__);
			$row = $this->db->fetch_array($result);
			?>			
		  <table >
		  <tr><td><b>Edit Permission Levels</b></td><td>&nbsp;</td></tr>
		  <tr>
		  <td>User Status</td>
		  <td><a href="javascript:void(0)" onclick="javascript: em.status('local',<?php echo $contact_id ?>,'div_credential',
			{ onUpdate: function(response,root){ 	
		   					 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';
							 
							 }, preloader: 'prl'
						} ); return false;"> 
						<?php if ($row['user_status'] == '')
						{
						echo "Empty";
						}
						else 
						{
						echo $row['user_status'];
						}
						 ?> </a>
						&nbsp;</td>	
		  </tr>
		  <tr>
		  <td>system Status</td>
		  <td><a href="javascript:void(0)" onclick="javascript: em.status('local',<?php echo $contact_id ?>,'div_credential',
			{ onUpdate: function(response,root){ 	
		   					 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';
							 
							 }, preloader: 'prl'
						} ); return false;"> 
						<?php if ($row['system_status'] == '')
						{
						echo "Empty";
						}
						else 
						{
						echo $row['system_status'];
						}
						 ?> </a>
						&nbsp;</td>
		  
		  <tr>
		  <td>Recruiting Status</td>
		  <td><a href="javascript:void(0)" onclick="javascript: em.status('local',<?php echo $contact_id ?>,'div_credential',
			{ onUpdate: function(response,root){ 	
		   					 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';
							 
							 }, preloader: 'prl'
						} ); return false;"> 
						<?php if ($row['recruitment_status'] == '')
						{
						echo "Empty";
						}
						else 
						{
						$sql_recruit="select * from ".EM_RECRUITING_STATUS." where recruiting_status_id='".$row['recruitment_status']."'";
						$result_recruit = $this->db->query($sql_recruit,__FILE__,__LINE__);
						$row_recruit = $this->db->fetch_array($result_recruit);
						echo $row_recruit['recruiting_status'];
						}
						 ?> </a>
						&nbsp;</td>	
		  </tr>
		  
		  <tr>
		  <td>Independant Contractor</td>
		  <td><a href="javascript:void(0)" onclick="javascript: em.status('local',<?php echo $contact_id ?>,'div_credential',
			{ onUpdate: function(response,root){ 	
		   					 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';
							 
							 }, preloader: 'prl'
						} ); return false;"> 
						<?php if ($row['ic'] == ''){
							echo "Empty";
						}
						else {
							echo $row[ic];
						}
						 ?> </a>
						&nbsp;</td>	
		  </tr>
		  
		  </table>
	<?php
	
					
		$html = ob_get_contents();
		ob_end_clean();
		return $html;

	}
	
	
	function status($runat,$contact_id,$target,$user_status='',$system_status='',$recruitment_status='',$ic=''){
		ob_start();
		$this->contact_id = $contact_id;
		switch ($runat){
			
			case 'local' :
						
						$formName= 'editstatus';
						
						$sql="select * from ".EM_CONTACT_STATUS." where contact_id='".$this->contact_id."'";
						$result = $this->db->query($sql,__FILE__,__LINE__);
		                $row = $this->db->fetch_array($result);
				?>
				<div class="prl">&nbsp;</div>
				<div id="lightbox">
					<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle">Edit Permission Levels</div>
					<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_credential').style.display='none';"><img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;">

				<form method="post" action="" enctype="multipart/form-data" name="<?php echo $formName?>" >
				
				  <table  class="table" align="center">
				    <tr align="left">
				      <td>User Status</td><td><select name="user_status"> 
											<option value="Active" <?php if($row['user_status']=='Active') echo 'selected="selected"'; ?>>Active</option>
											<option value="Inactive" <?php if($row['user_status']=='Inactive') echo 'selected="selected"'; ?>>Inactive</option>
											</select>
											</td>
					</tr>
					<tr align="left">						
				      <td>System Status</td><td><select name="system_status"> 
					   						<option value="User" <?php if($row['system_status']=='User') echo 'selected="selected"'; ?>>User</option>
											<option value="Team Lead" <?php if($row['system_status']=='Team Lead') echo 'selected="selected"'; ?>>Team Lead</option>
											<option value="Inactive" <?php if($row['system_status']=='Inactive') echo 'selected="selected"'; ?>>Inactive</option>
											</select>
					  					 </td>
					</tr>
					<tr align="left">
					<?php
					$sql_rec="select * from ".EM_RECRUITING_STATUS;
					$result_rec = $this->db->query($sql_rec,__FILE__,__LINE__);
		            
					?>					 
				      <td>Recruiting Status</td>
				      <td><select name="recruitment_status">
                     <?php while($row_rec = $this->db->fetch_array($result_rec)) { ?>
						<option value="<?php echo $row_rec['recruiting_status_id'];?>" <?php if($row['recruitment_status']==$row_rec['recruiting_status_id']) echo 'selected="selected"'; ?>><?php echo $row_rec['recruiting_status']; ?> </option>
                        <?php } ?>
                      </select></td>
					</tr>
					
					<tr>
					<td>Independant Contractor</td>
					<td><select name="ic" id="ic" >
					<option value="Yes" <?php if($row['ic']=='Yes') echo 'selected="selected"'; ?> >Yes</option>
					<option value="No" <?php if($row['ic']=='No') echo 'selected="selected"'; ?> >No</option>
					</select>
					</td>
					</tr>
					<tr align="center">
					<td colspan="2"><input type="button" name="submit" id="submit" style="width:auto" value="Go" onclick="javascript: 
					 if(1) {
					  em.status('server',<?php echo $contact_id ?>,'<?php echo $target ?>',this.form.user_status.value,this.form.system_status.value,this.form.recruitment_status.value,this.form.ic.value,{target:'div_credential', preloader: 'prl'}); } return false;"></td>
				    </tr>
				  </table>
				  
				</form>
				</div></div></div>
				<?php
				break;
				
			case 'server' :
									 
						 $this->contact_id=$contact_id;
						 $this->user_status=$user_status;
						 $this->system_status=$system_status;
						 $this->recruitment_status=$recruitment_status;
						 
						 $sql="select * from ".EM_CONTACT_STATUS." where contact_id='".$this->contact_id."'";
						 $result = $this->db->query($sql,__FILE__,__LINE__);
						 if ($this->db->num_rows($result)>0){
						 
						 	 $update_sql_array = array();
							 $update_sql_array[contact_id] = $this->contact_id;
							 $update_sql_array[user_status] = $this->user_status;
							 $update_sql_array[system_status] = $this->system_status;
							 $update_sql_array[recruitment_status] = $this->recruitment_status;
							 $update_sql_array[ic] = $ic;
														
							 $this->db->update(EM_CONTACT_STATUS,$update_sql_array,contact_id,$this->contact_id);
						 }
						 
						 else {
						 
							 $insert_sql_array = array();
							 $insert_sql_array[contact_id] = $this->contact_id;
							 $insert_sql_array[user_status] = $this->user_status;
							 $insert_sql_array[system_status] = $this->system_status;
							 $insert_sql_array[recruitment_status] = $this->recruitment_status;
							 $insert_sql_array[ic] = $ic;
						 
						 	 $this->db->insert(EM_CONTACT_STATUS,$insert_sql_array);
						 }
					
					 	
						 				
				?>
				<script type="text/javascript">
				  document.getElementById('div_credential').style.display='none'; 
				  em.showPermitionlevel(<?php echo $this->contact_id ?>,{target:'showPermitionlevel',preloader: 'prl'})
				</script>
				<?php

																	
				break;
				default : echo 'Wrong Paramemter passed';
					
				break;
			}	
					
				
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	
	function RemoveContact($contact_id)
	{
/*		$sql="delete from ".EM_CONTACT_STATUS." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);
		
		$sql="delete from ".EM_WEB_APP_INFO." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);
	
		$sql="delete from ".EM_CERTIFICATION." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);
		
		$sql="delete from ".EM_CONTACT_LOCATION." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);

		$sql="delete from ".EM_APPLICATION_GENERAL." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);
		
		$sql="delete from ".EM_APPLICATION_EDUCATION." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);

		$sql="delete from ".EM_APPLICATION_REFERENCES." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);

		$sql="delete from ".EM_APPLICATION_PREVIOUS_EMPLOYMENT." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);

		$sql="delete from ".EM_APPLICATION_MILITARY_SERVICE." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);

		$sql="delete from ".EM_CONTACT_DOCUMENTS." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);

		$sql="delete from ".EM_CONTACT_UNAVAILABILITY." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);
		*/
		parent::RemoveContact($contact_id);	
	}
	
	
	function ResetPassword($contact_id)
	{
			$this->contact_id=$contact_id;
			
			$sql="select * from ".EM_WEB_APP_INFO." where contact_id='".$this->contact_id."'";
			$result = $this->db->query($sql,__FILE__,__LINE__);
			$row = $this->db->fetch_array($result);
			
			$sql_contact="select * from ".TBL_CONTACT." where contact_id='".$this->contact_id."'";
			$result_contact = $this->db->query($sql_contact,__FILE__,__LINE__);
			$row_contact = $this->db->fetch_array($result_contact);
				
					
				?>
				<a style="color:#FF0000;" href="#" onclick="javascript:
								if(confirm('are you sure ?')){ 
								  
									em.sendResetPasswordLink(<?php echo $this->contact_id; ?>,
																'<?php echo $row['username']; ?>',
																'<?php echo $row['email']; ?>',
																'<?php echo $row_contact['first_name']; ?>',
																{ onUpdate: function(response,root){
																	if(response){
																		alert(response);
																	}
																} }
																);
								}return false;">
			Reset Password </a>
			<?php
		
	}
	/*******************************************************************************************************/
	
	function GetQaErrorRate($contact_id)
	{
		$x=0;
		$sql_qa1="select * from ".EM_QA_CHECK." where contact_id='$contact_id'";
		$result_qa1=$this->db->query($sql_qa1,__FILE__,__LINE__);
		while($row_qa1= $this->db->fetch_array($result_qa1))
		{
			$performed=$performed+$row_qa1['quantity'];
			$err=$err+$row_qa1['quantity_incorrect'];
			$x++;
		}
		if($err!=0)
		{
			$errorRate1=($err/$performed)*100;
			$errorRate=number_format ($errorRate1, 2);
			return $errorRate;
		}
		else
		{
			$errorRate=0;
			return $errorRate;
		}	
	} // end of GetQaError
	
	function getAssignedJobs($contact_id){
		ob_start();
		$sql = "select * from ".EM_STAFFING." where contact_id='$contact_id'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		
		$x=0;
		$sql_qa1="select * from ".EM_QA_CHECK." where contact_id='$contact_id'";
		$result_qa1=$this->db->query($sql_qa1,__FILE__,__LINE__);
		while($row_qa1= $this->db->fetch_array($result_qa1))
		{
			
			$x++;
		}
				
		$eve_obj = new Event();
		?>
		<table>
		  <tr>
		    <td colspan="8"><h2>Assigned Jobs</h2></td>
			<td><b>QA</b> <?php echo $this->GetQaErrorRate($contact_id); ?>% error rate over <?php echo $x; ?> events</td>
		  </tr>
		<?php
		while($row = $this->db->fetch_array($result)){
		$sql_eve = "select * from ".EM_EVENT." where event_id='$row[event_id]'";
		$result_eve = $this->db->query($sql_eve,__FILE__,__LINE__);
		$row_eve = $this->db->fetch_array($result_eve);
		
		$sql_cert = "select * from ".EM_CERTIFICATION_TYPE." where certification_id='$row[type]'";
		$result_cert = $this->db->query($sql_cert,__FILE__,__LINE__);
		$row_cert = $this->db->fetch_array($result_cert);
		?>
		  <tr>
		    <td>GEID <a href= "event_profile.php?event_id=<?php echo $row_eve[event_id];?>"><?php echo $row_eve[group_event_id]; ?></a></td>
			<td>::</td>
			<td><?php echo $row_cert[cert_type]; ?></td>
			<td>::</td>
			<td><?php $eve_obj->start_date($row_eve[event_id])?> - <?php $eve_obj->end_date($row_eve[event_id]); ?></td>
			<td>::</td>
			<td><?php echo $row_eve[city].', '.$row_eve[state]; ?></td>
			<td>::</td>
			<td>
			<?php

				$sql_qa="select * from ".EM_QA_CHECK." where contact_id='$this->contact_id' and event_id='$row_eve[event_id]'";
				$result_qa=$this->db->query($sql_qa,__FILE__,__LINE__);
				$row_qa= $this->db->fetch_array($result_qa);
				if($row_qa)
					{
						?>
						<a href="#" onclick="javascript: em.EnterQaReport('<?php echo $row_eve[event_id];?>','<?php echo $contact_id;?>',
					{ onUpdate: function(response,root){
							 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';	
						
							var thegrid1 = new drasticGrid('grid1', {						
							path: 'grid_contact_profile_QaReport2.php',
							pathimg: '<?php echo PATHDRASTICTOOLS; ?>img/',
							pagelength:6, 
							addparams:'&col_name=contact_id|event_id&col_value='+<?php echo $contact_id;?>+'|'+<?php echo $row_eve[event_id];?>,
							columns: [
								{name : 'quantity',displayname:'Quantity', width:120 },
								{name : 'quantity_incorrect',displayname:'Quantity Incorrect', width:120  },
								{name : 'note',displayname:'Note', width:200 }
							 ]
						});

							 }, preloader: 'prl'
						} ); return false;" >
						<?php 
						echo "&nbsp;&nbsp;".$row_qa['quantity']."&nbsp;Performed &nbsp; :: &nbsp;  ".$row_qa['quantity_incorrect']." errors";  
						?>
						</a>
						<?php 
					}
				else
					{	
			?>
			<a href="#" onclick="javascript: em.EnterQaReport('<?php echo $row_eve[event_id];?>','<?php echo $contact_id;?>',
					{ onUpdate: function(response,root){
							 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';	
						
							var thegrid1 = new drasticGrid('grid1', {						
							path: 'grid_contact_profile_QaReport.php',
							pathimg: '<?php echo PATHDRASTICTOOLS; ?>img/',
							pagelength:6, 
							addparams:'&col_name=contact_id|event_id&col_value='+<?php echo $contact_id;?>+'|'+<?php echo $row_eve[event_id];?>,
							columns: [
								{name : 'quantity',displayname:'Quantitye', width:120  },
								{name : 'quantity_incorrect',displayname:'Quantitye Incorrect', width:120  },
								{name : 'note',displayname:'Note', width:200  }
							 ]
						});

							 }, preloader: 'prl'
						} ); return false;" >enter QA Report</a>
				<?php }
				?>
			</td>
		  </tr>		
		<?php
		}
		?></table><?php
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function EnterQaReport($event_id,$contact_id)
	{
		ob_start();
		?>
		<div class="prl">&nbsp;</div>
				<div id="lightbox">
				
					<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle">Enter QA Report</div>
					<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_credential').style.display='none';
em.getAssignedJobs('<?php echo $contact_id ?>',{target:'showassignedJobs',preloader: 'prl'})
					"><img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;">

				<div id="grid1"></div>
				
				</div></div></div>		
			<?php 			
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}

	function Availability($contact_id,$event_id,$unavailable_date,$start_time,$end_time,$unavailable_status,$note) {
	 
		$this->contact_id =$contact_id;
		$this->event_id =$event_id;
		$this->unavailable_date =$unavailable_date;
		$this->start_time =$start_time;
		$this->end_time =$end_time;
		$this->note =$note; 
		 
		$insert_sql_array = array();
		$insert_sql_array[unavailable_date] = $this->unavailable_date;
		$insert_sql_array[start_time] = $this->start_time;
		$insert_sql_array[end_time] = $this->end_time;
		$insert_sql_array[note] = $this->note;
		$insert_sql_array[contact_id] = $this->contact_id;
		$insert_sql_array[event_id] = $this->event_id;
		$insert_sql_array[unavailability_status] = $unavailable_status;
		 
		$this->db->insert(EM_CONTACT_UNAVAILABILITY,$insert_sql_array);
		
	}
function Update_Availability($event_id,$unavailable_date,$start_time,$end_time,$action) {
	
	$this->event_id =$event_id;
	$this->unavailable_date =$unavailable_date;
	$this->start_time =$start_time;
	$this->end_time =$end_time;	
	
	$update_sql_array = array();
	$update_sql_array['unavailable_date'] = $this->unavailable_date;
	$update_sql_array['start_time'] = $this->start_time;
	$update_sql_array['end_time'] = $this->end_time;
	
	$this->db->update(EM_CONTACT_UNAVAILABILITY,$update_sql_array,'event_id',$this->event_id);
	
	}
	
function Delete_Availability($event_id,$unavailable_date,$start_time,$end_time,$action) {
	
	$this->event_id =$event_id;	
	$sql = "delete from ".EM_CONTACT_UNAVAILABILITY." where event_id='".$this->event_id."'";
	$result_eve = $this->db->query($sql,__FILE__,__LINE__);
	}
	
	
	function licenseSearchBox() {
		$formName='frm_license';
		?>
		<form method="post" enctype="multipart/form-data" name="<?php echo $formName; ?>"> 
			<div align="right"><a href="#" onclick="javascript:
				evt_contact.ExpiredLicensesList('all',
										document.<?php echo $formName;?>.user_id.value,
										document.<?php echo $formName;?>.first_name.value,
										document.<?php echo $formName;?>.last_name.value,
										document.<?php echo $formName;?>.license.value,
										document.<?php echo $formName;?>.exp_from.value,
										document.<?php echo $formName;?>.exp_to.value,
										document.<?php echo $formName;?>.e_c.value,
										document.<?php echo $formName;?>.u_s.value,
										document.<?php echo $formName;?>.s_s.value,
										document.<?php echo $formName;?>.r_s.value,
										document.<?php echo $formName;?>.ct.value,
										document.<?php echo $formName;?>.st.value,
										document.<?php echo $formName;?>.available_on.value,
										document.<?php echo $formName;?>.available_till.value,
										document.<?php echo $formName;?>.zip.value,
										document.<?php echo $formName;?>.rad.value,
										'csv',
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										table2CSV($('#search_ExpiredLicensesList'));
																
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) 
										
									}} );
									
									evt_contact.ExpiredLicensesList('all',
											document.<?php echo $formName;?>.user_id.value,
											document.<?php echo $formName;?>.first_name.value,
											document.<?php echo $formName;?>.last_name.value,
											document.<?php echo $formName;?>.license.value,
											document.<?php echo $formName;?>.exp_from.value,
											document.<?php echo $formName;?>.exp_to.value,
											document.<?php echo $formName;?>.e_c.value,
											document.<?php echo $formName;?>.u_s.value,
											document.<?php echo $formName;?>.s_s.value,
											document.<?php echo $formName;?>.r_s.value,
											document.<?php echo $formName;?>.ct.value,
											document.<?php echo $formName;?>.st.value,
											document.<?php echo $formName;?>.available_on.value,
											document.<?php echo $formName;?>.available_till.value,
											document.<?php echo $formName;?>.zip.value,
											document.<?php echo $formName;?>.rad.value,
											{onUpdate: function(response,root){
											document.getElementById('div_license').innerHTML=response;
											$('#search_ExpiredLicensesList')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
											headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) 
											
										}} );
				 return false;">
				<img src="images/csv.png"  alt="Export to CSV" /> </a>
			</div>

			<table class="table form_bg" width="100%" >
				<tr>
					<td>Contact Id</td>					
					<td>First Name</td>
					<td>Last Name</td>			
					<td>License</td>
				</tr>
				<tr>
					<td><input name="user_id" type="text" id="user_id" value="" size="60"
					 onkeyup="evt_contact.ExpiredLicensesList('all',
										this.form.user_id.value,
										this.form.first_name.value,
										this.form.last_name.value,
										this.form.license.value,
										this.form.exp_from.value,
										this.form.exp_to.value,
										this.form.e_c.value,
										this.form.u_s.value,
										this.form.s_s.value,
										this.form.r_s.value,
										this.form.ct.value,
										this.form.st.value,
										this.form.available_on.value,
										this.form.available_till.value,
										this.form.zip.value,
										this.form.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );"
									
									autocomplete='off' /></td>
				
					
					
					<td><input name="first_name" type="text" id="first_name" value="" size="60" 
					onkeyup="evt_contact.ExpiredLicensesList('all',
										this.form.user_id.value,
										this.form.first_name.value,
										this.form.last_name.value,
										this.form.license.value,
										this.form.exp_from.value,
										this.form.exp_to.value,
										this.form.e_c.value,
										this.form.u_s.value,
										this.form.s_s.value,
										this.form.r_s.value,
										this.form.ct.value,
										this.form.st.value,
										this.form.available_on.value,
										this.form.available_till.value,
										this.form.zip.value,
										this.form.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );" autocomplete='off' /></td>
				
					<td><input name="last_name" type="text" id="last_name" value="" size="60" 
					onkeyup="evt_contact.ExpiredLicensesList('all',
										this.form.user_id.value,
										this.form.first_name.value,
										this.form.last_name.value,
										this.form.license.value,
										this.form.exp_from.value,
										this.form.exp_to.value,
										this.form.e_c.value,
										this.form.u_s.value,
										this.form.s_s.value,
										this.form.r_s.value,
										this.form.ct.value,
										this.form.st.value,
										this.form.available_on.value,
										this.form.available_till.value,
										this.form.zip.value,
										this.form.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );" autocomplete='off' /></td>
			
					<td>
					<select name="license" id="license" style="width:100%" 
					onchange="evt_contact.ExpiredLicensesList('all',
										this.form.user_id.value,
										this.form.first_name.value,
										this.form.last_name.value,
										this.form.license.value,
										this.form.exp_from.value,
										this.form.exp_to.value,
										this.form.e_c.value,
										this.form.u_s.value,
										this.form.s_s.value,
										this.form.r_s.value,
										this.form.ct.value,
										this.form.st.value,
										this.form.available_on.value,
										this.form.available_till.value,
										this.form.zip.value,
										this.form.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );">
					<option value="">-Select-</option>
					  <?php
					  $sql = "select * from ".EM_CERTIFICATION_TYPE;
					  $result = $this->db->query($sql,__FILE__,__lINE__);
					  while($row = $this->db->fetch_array($result)){ ?>
						  <option value="<?php echo $row[certification_id] ?>" ><?php echo $row[cert_type] ?></option>
					  <?php  }  ?>
					</select>
					</td>	
				
				</tr>					
				<tr>
					<td>Expire From</td>
					<td>Expire To</td>
					<td>Event Count</td>
					<td>User Status</td>
				</tr>		
				
				<tr>					
					
					<td><input name="exp_from" type="text" id="exp_from" size="60" autocomplete='off' readonly/>
					
					<script type="text/javascript">	 
					 function start_cal(){
					 new Calendar({
					 inputField   	: "exp_from",
					 dateFormat		: "%Y-%m-%d",
					 trigger		: "exp_from",
					 weekNumbers   	: true,
					 bottomBar		: true,				 
					 onSelect		: function() {
											
											this.hide();
											document.<?php echo $formName;?>.exp_from.value=this.selection.print("%Y-%m-%d");
											evt_contact.ExpiredLicensesList('all',
											document.<?php echo $formName; ?>.user_id.value,
											document.<?php echo $formName; ?>.first_name.value,
											document.<?php echo $formName; ?>.last_name.value,
											document.<?php echo $formName; ?>.license.value,
											document.<?php echo $formName; ?>.exp_from.value,
											document.<?php echo $formName; ?>.exp_to.value,
											document.<?php echo $formName; ?>.e_c.value,
											document.<?php echo $formName; ?>.u_s.value,
											document.<?php echo $formName; ?>.s_s.value,
											document.<?php echo $formName; ?>.r_s.value,
											document.<?php echo $formName; ?>.ct.value,
											document.<?php echo $formName; ?>.st.value,
											document.<?php echo $formName; ?>.available_on.value,
											document.<?php echo $formName; ?>.available_till.value,
											document.<?php echo $formName; ?>.zip.value,
											document.<?php echo $formName; ?>.rad.value,
											{onUpdate: function(response,root){
											document.getElementById('div_license').innerHTML=response;
											$('#search_ExpiredLicensesList')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
											headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );
											end_cal(this.selection.get()+1);
										}				
					  });
					}
					start_cal();
					</script>
					<a href="javascript:void(0);" 
					onclick="javascript:
						document.<?php echo $formName; ?>.exp_from.value='';
						evt_contact.ExpiredLicensesList('all',
											document.<?php echo $formName; ?>.user_id.value,
											document.<?php echo $formName; ?>.first_name.value,
											document.<?php echo $formName; ?>.last_name.value,
											document.<?php echo $formName; ?>.license.value,
											document.<?php echo $formName; ?>.exp_from.value,
											document.<?php echo $formName; ?>.exp_to.value,
											document.<?php echo $formName; ?>.e_c.value,
											document.<?php echo $formName; ?>.u_s.value,
											document.<?php echo $formName; ?>.s_s.value,
											document.<?php echo $formName; ?>.r_s.value,
											document.<?php echo $formName; ?>.ct.value,
											document.<?php echo $formName; ?>.st.value,
											document.<?php echo $formName; ?>.available_on.value,
											document.<?php echo $formName; ?>.available_till.value,
											document.<?php echo $formName; ?>.zip.value,
											document.<?php echo $formName; ?>.rad.value,
											{onUpdate: function(response,root){
											document.getElementById('div_license').innerHTML=response;
											$('#search_ExpiredLicensesList')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
											headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} ); return false;"><img src="images/trash.gif" border="0"/></a>			
					</td>		
					
					<td><input name="exp_to" type="text" id="exp_to" size="60"  autocomplete='off' readonly/>
					<script type="text/javascript">	
					 function end_cal(minDate){	 
					 new Calendar({
					 inputField   	: "exp_to",
					 dateFormat		: "%Y-%m-%d",
					 trigger		: "exp_to",
					 weekNumbers   	: true,
					 bottomBar		: true,
					 min			: minDate,				 
					 onSelect		: function() {
											this.hide();
											document.<?php echo $formName;?>.exp_to.value=this.selection.print("%Y-%m-%d");		
										evt_contact.ExpiredLicensesList('all',
										document.<?php echo $formName; ?>.user_id.value,
										document.<?php echo $formName; ?>.first_name.value,
										document.<?php echo $formName; ?>.last_name.value,
										document.<?php echo $formName; ?>.license.value,
										document.<?php echo $formName; ?>.exp_from.value,
										document.<?php echo $formName; ?>.exp_to.value,
										document.<?php echo $formName; ?>.e_c.value,
										document.<?php echo $formName; ?>.u_s.value,
										document.<?php echo $formName; ?>.s_s.value,
										document.<?php echo $formName; ?>.r_s.value,
										document.<?php echo $formName; ?>.ct.value,
										document.<?php echo $formName; ?>.st.value,
										document.<?php echo $formName; ?>.available_on.value,
										document.<?php echo $formName; ?>.available_till.value,
										document.<?php echo $formName; ?>.zip.value,
										document.<?php echo $formName; ?>.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );
									}				
					  });
					 }
					 dd = '0000-01-01';
					end_cal(dd);
					</script>	
					<a href="javascript:void(0);" 
						onclick="javascript:
						document.<?php echo $formName; ?>.exp_to.value='';
						evt_contact.ExpiredLicensesList('all',
										document.<?php echo $formName; ?>.user_id.value,
										document.<?php echo $formName; ?>.first_name.value,
										document.<?php echo $formName; ?>.last_name.value,
										document.<?php echo $formName; ?>.license.value,
										document.<?php echo $formName; ?>.exp_from.value,
										document.<?php echo $formName; ?>.exp_to.value,
										document.<?php echo $formName; ?>.e_c.value,
										document.<?php echo $formName; ?>.u_s.value,
										document.<?php echo $formName; ?>.s_s.value,
										document.<?php echo $formName; ?>.r_s.value,
										document.<?php echo $formName; ?>.ct.value,
										document.<?php echo $formName; ?>.st.value,
										document.<?php echo $formName; ?>.available_on.value,
										document.<?php echo $formName; ?>.available_till.value,
										document.<?php echo $formName; ?>.zip.value,
										document.<?php echo $formName; ?>.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );">
									<img src="images/trash.gif" border="0"/></a>
					</td>				
			
			<td><input name="e_c" type="text" id="e_c" value="" size="60" 
			onkeyup="evt_contact.ExpiredLicensesList('all',
										this.form.user_id.value,
										this.form.first_name.value,
										this.form.last_name.value,
										this.form.license.value,
										this.form.exp_from.value,
										this.form.exp_to.value,
										this.form.e_c.value,
										this.form.u_s.value,
										this.form.s_s.value,
										this.form.r_s.value,
										this.form.ct.value,
										this.form.st.value,
										this.form.available_on.value,
										this.form.available_till.value,
										this.form.zip.value,
										this.form.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );" autocomplete='off' /></td>
		    
			<td><select name="u_s" id="u_s" style="width:100%" 
			onchange="evt_contact.ExpiredLicensesList('all',
										this.form.user_id.value,
										this.form.first_name.value,
										this.form.last_name.value,
										this.form.license.value,
										this.form.exp_from.value,
										this.form.exp_to.value,
										this.form.e_c.value,
										this.form.u_s.value,
										this.form.s_s.value,
										this.form.r_s.value,
										this.form.ct.value,
										this.form.st.value,
										this.form.available_on.value,
										this.form.available_till.value,
										this.form.zip.value,
										this.form.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );">
		    <option value="">-Select-</option>
			<option value="Active">Active</option>
		    <option value="Inactive">Inactive</option>
			<option value="Not_defined">No Status Set</option>			
		    </select> </td>

			 </tr>
				
		<tr>
		    <td>System Status</td>
		    <td>Recruiting Status</td>
			<td>City</td>
		    <td>State</td>
		</tr>
		<tr>
		    
		    <td><select name="s_s" id="s_s" style="width:100%" 
			onchange="evt_contact.ExpiredLicensesList('all',
										this.form.user_id.value,
										this.form.first_name.value,
										this.form.last_name.value,
										this.form.license.value,
										this.form.exp_from.value,
										this.form.exp_to.value,
										this.form.e_c.value,
										this.form.u_s.value,
										this.form.s_s.value,
										this.form.r_s.value,
										this.form.ct.value,
										this.form.st.value,
										this.form.available_on.value,
										this.form.available_till.value,
										this.form.zip.value,
										this.form.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );">
		    <option value="">-Select-</option>
			<option value="User">User</option>
		    <option value="Team Lead">Team Lead</option>
			<option value="Inactive">Inactive</option>
		    </select> </td>
		    <td><select name="r_s" id="r_s" style="width:100%" 
			onchange="evt_contact.ExpiredLicensesList('all',
										this.form.user_id.value,
										this.form.first_name.value,
										this.form.last_name.value,
										this.form.license.value,
										this.form.exp_from.value,
										this.form.exp_to.value,
										this.form.e_c.value,
										this.form.u_s.value,
										this.form.s_s.value,
										this.form.r_s.value,
										this.form.ct.value,
										this.form.st.value,
										this.form.available_on.value,
										this.form.available_till.value,
										this.form.zip.value,
										this.form.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );">
		    <option value="" selected="selected">-Select-</option>
			<?php 
			$sql_rct="select * from ".EM_RECRUITING_STATUS;
			$result_rct = $this->db->query($sql_rct,__FILE__,__LINE__);
			while($row_rct = $this->db->fetch_array($result_rct))
			{
			?>
		    <option value="<?php echo $row_rct['recruiting_status_id']; ?>" ><?php echo $row_rct['recruiting_status']; ?></option>
			<?php } ?>
		    </select> </td>
			
			<td><input name="ct" type="text" id="ct" value="" size="60" 
			onkeyup="evt_contact.ExpiredLicensesList('all',
										this.form.user_id.value,
										this.form.first_name.value,
										this.form.last_name.value,
										this.form.license.value,
										this.form.exp_from.value,
										this.form.exp_to.value,
										this.form.e_c.value,
										this.form.u_s.value,
										this.form.s_s.value,
										this.form.r_s.value,
										this.form.ct.value,
										this.form.st.value,
										this.form.available_on.value,
										this.form.available_till.value,
										this.form.zip.value,
										this.form.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );" autocomplete='off' /></td>
		    <td><select name="st" id="st" style="width:100%" 
			onchange="evt_contact.ExpiredLicensesList('all',
										this.form.user_id.value,
										this.form.first_name.value,
										this.form.last_name.value,
										this.form.license.value,
										this.form.exp_from.value,
										this.form.exp_to.value,
										this.form.e_c.value,
										this.form.u_s.value,
										this.form.s_s.value,
										this.form.r_s.value,
										this.form.ct.value,
										this.form.st.value,
										this.form.available_on.value,
										this.form.available_till.value,
										this.form.zip.value,
										this.form.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );">
						<option value="">Select State</option>
						<?php
							$state=file("../state_us.inc");
							foreach($state as $val){
							$state = trim($val);
						?>
						<option value="<?php echo $state;?>"><?php echo $state;?></option>
						<?php
							}
						?>
					 </select>
					 </td>
			
		</tr>

		<tr>
			  <td>Available On</td>
			  <td>Available Till</td>
			  <td>Zip</td>
			  <td>Radius</td>
 		</tr>
		<tr>
			<td><input name="available_on" type="text" id="available_on" value="" size="60"  autocomplete='off' readonly="true"/>
			<script type="text/javascript">	
			 function start_cal1(){	 
			 new Calendar({
			 inputField   	: "available_on",
			 dateFormat		: "%Y-%m-%d",
			 trigger		: "available_on",
			 weekNumbers   	: true,
			 bottomBar		: true,				 
			 onSelect		: function() {
									this.hide();
									document.<?php echo $formName;?>.available_on.value=this.selection.print("%Y-%m-%d");		
									evt_contact.ExpiredLicensesList('all',
										document.<?php echo $formName; ?>.user_id.value,
										document.<?php echo $formName; ?>.first_name.value,
										document.<?php echo $formName; ?>.last_name.value,
										document.<?php echo $formName; ?>.license.value,
										document.<?php echo $formName; ?>.exp_from.value,
										document.<?php echo $formName; ?>.exp_to.value,
										document.<?php echo $formName; ?>.e_c.value,
										document.<?php echo $formName; ?>.u_s.value,
										document.<?php echo $formName; ?>.s_s.value,
										document.<?php echo $formName; ?>.r_s.value,
										document.<?php echo $formName; ?>.ct.value,
										document.<?php echo $formName; ?>.st.value,
										document.<?php echo $formName; ?>.available_on.value,
										document.<?php echo $formName; ?>.available_till.value,
										document.<?php echo $formName; ?>.zip.value,
										document.<?php echo $formName; ?>.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );
									end_cal1(this.selection.get()+1);
     								}				
			  });
			  }
			 start_cal1();
			</script>	
			<a href="javascript:void(0);" 
			onclick="javascript:
			document.<?php echo $formName; ?>.available_on.value='';
			evt_contact.ExpiredLicensesList('all',
										document.<?php echo $formName; ?>.user_id.value,
										document.<?php echo $formName; ?>.first_name.value,
										document.<?php echo $formName; ?>.last_name.value,
										document.<?php echo $formName; ?>.license.value,
										document.<?php echo $formName; ?>.exp_from.value,
										document.<?php echo $formName; ?>.exp_to.value,
										document.<?php echo $formName; ?>.e_c.value,
										document.<?php echo $formName; ?>.u_s.value,
										document.<?php echo $formName; ?>.s_s.value,
										document.<?php echo $formName; ?>.r_s.value,
										document.<?php echo $formName; ?>.ct.value,
										document.<?php echo $formName; ?>.st.value,
										document.<?php echo $formName; ?>.available_on.value,
										document.<?php echo $formName; ?>.available_till.value,
										document.<?php echo $formName; ?>.zip.value,
										document.<?php echo $formName; ?>.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} ); return false;">
										<img src="images/trash.gif" border="0"/></a>			</td>
			
			<td><input name="available_till" type="text" id="available_till" value="" size="60"  autocomplete='off' readonly="true"/>
			<script type="text/javascript">	
			function end_cal1(minDate1){		 
			 new Calendar({
			 inputField   	: "available_till",
			 dateFormat		: "%Y-%m-%d",
			 trigger		: "available_till",
			 weekNumbers   	: true,
			 bottomBar		: true,	
			 min			: minDate1,			 
			 onSelect		: function() {
									this.hide();
									document.<?php echo $formName;?>.available_till.value=this.selection.print("%Y-%m-%d");		
									evt_contact.ExpiredLicensesList('all',
										document.<?php echo $formName; ?>.user_id.value,
										document.<?php echo $formName; ?>.first_name.value,
										document.<?php echo $formName; ?>.last_name.value,
										document.<?php echo $formName; ?>.license.value,
										document.<?php echo $formName; ?>.exp_from.value,
										document.<?php echo $formName; ?>.exp_to.value,
										document.<?php echo $formName; ?>.e_c.value,
										document.<?php echo $formName; ?>.u_s.value,
										document.<?php echo $formName; ?>.s_s.value,
										document.<?php echo $formName; ?>.r_s.value,
										document.<?php echo $formName; ?>.ct.value,
										document.<?php echo $formName; ?>.st.value,
										document.<?php echo $formName; ?>.available_on.value,
										document.<?php echo $formName; ?>.available_till.value,
										document.<?php echo $formName; ?>.zip.value,
										document.<?php echo $formName; ?>.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );
								}				
			  });
			  }
			
			</script>	
			<a href="javascript:void(0);" 
			onclick="javascript:
			document.<?php echo $formName; ?>.available_till.value='';
			evt_contact.ExpiredLicensesList('all',
										document.<?php echo $formName; ?>.user_id.value,
										document.<?php echo $formName; ?>.first_name.value,
										document.<?php echo $formName; ?>.last_name.value,
										document.<?php echo $formName; ?>.license.value,
										document.<?php echo $formName; ?>.exp_from.value,
										document.<?php echo $formName; ?>.exp_to.value,
										document.<?php echo $formName; ?>.e_c.value,
										document.<?php echo $formName; ?>.u_s.value,
										document.<?php echo $formName; ?>.s_s.value,
										document.<?php echo $formName; ?>.r_s.value,
										document.<?php echo $formName; ?>.ct.value,
										document.<?php echo $formName; ?>.st.value,
										document.<?php echo $formName; ?>.available_on.value,
										document.<?php echo $formName; ?>.available_till.value,
										document.<?php echo $formName; ?>.zip.value,
										document.<?php echo $formName; ?>.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} ); return false;">
										<img src="images/trash.gif" border="0"/></a>			</td>
			
			<td><input name="zip" type="text" id="zip" value="" size="60" 
			onkeyup="evt_contact.ExpiredLicensesList('all',
										this.form.user_id.value,
										this.form.first_name.value,
										this.form.last_name.value,
										this.form.license.value,
										this.form.exp_from.value,
										this.form.exp_to.value,
										this.form.e_c.value,
										this.form.u_s.value,
										this.form.s_s.value,
										this.form.r_s.value,
										this.form.ct.value,
										this.form.st.value,
										this.form.available_on.value,
										this.form.available_till.value,
										this.form.zip.value,
										this.form.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );" autocomplete='off' /></td>
		  
		  <td><input name="rad" type="text" id="rad" value="" size="60" onkeyup="evt_contact.ExpiredLicensesList('all',
										this.form.user_id.value,
										this.form.first_name.value,
										this.form.last_name.value,
										this.form.license.value,
										this.form.exp_from.value,
										this.form.exp_to.value,
										this.form.e_c.value,
										this.form.u_s.value,
										this.form.s_s.value,
										this.form.r_s.value,
										this.form.ct.value,
										this.form.st.value,
										this.form.available_on.value,
										this.form.available_till.value,
										this.form.zip.value,
										this.form.rad.value,
										{onUpdate: function(response,root){
										document.getElementById('div_license').innerHTML=response;
										$('#search_ExpiredLicensesList')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
										headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );" autocomplete='off' /></td>
		  </tr>
				
		  </table>
		<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
		</form>
		<?php
					
	}
									
	function ExpiredLicensesList($all='',$contact_id='',$first_name='',$last_name='',$license='',$exp_from='',$exp_to='',$event_count='',$user_status='',$system_status='',$rec_status='',$city='',$state='',$available_on='',$available_till='',$zip='',$rad=0,$csv='')
	{
		ob_start();
		if($exp_from=='') $exp_from='1730-01-01';
		if($exp_to=='') $exp_to='9999-12-31';
		if($available_on=='') $available_on='0000-01-01';
		if($available_till=='') $available_till='9999-12-31';		

		//echo $all.' '.$user_id.' '.$first_name.' '.$last_name.' '.$license.' '.$exp_from.' '.$exp_to.' '.$event_count.' '.$user_status.' '.$system_status.' '.$rec_status.' '.$city.' '.$state.' '.$available_on.' '.$available_till.' '.$zip.' '.$rad.'<br>';
		
		if($zip!='' and $rad!=0 ){
			$x=0;
			$contact_list = array();
			$contact_string = '0,';
			$row = $this->zip_obj->get_zip_point($zip);

			if($row[lat]){
				$sql = "SELECT e.*,c.lat,c.lon,c.zip_code , (((acos( sin( ( ".$row[lat]." * pi( ) /180 ) ) * sin( (c.lat * pi( )/180 ) ) + cos( ( ".$row[lat]." * pi( ) /180 ) ) * cos( (c.lat * pi( ) /180 )) * cos( ((".$row[lon]." - c.lon) * pi( ) /180 )))) *180 / pi( )) *60 * 1.1515) AS distance FROM ".ZIP_CODE." c,".CONTACT_ADDRESS." e WHERE e.zip = c.zip_code";
	
				if($rad>0){
					$sql .= " HAVING distance<=$rad";
				} 
				
				$sql .= " ORDER BY distance ASC";
				$result = $this->db->query($sql,__FILE__,__LINE__);	
				
				while($row_c = $this->db->fetch_array($result)){
				
					foreach($row_c as $key=>$value){
						$contact_list[$x][$key] = $value;
					}
					$contact_string .= $row_c[contact_id].',';
					$x++;
				}
				$contact_string = substr($contact_string,0,strlen($contact_string)-1);
			}
		}
				
		$sql = "select distinct a.contact_id, a.first_name, a.last_name, d.*, e.cert_type, e.credential_type, b.expiration_date from ".TBL_CONTACT." a, ".EM_CERTIFICATION." b, ".CONTACT_ADDRESS." d, ".EM_CERTIFICATION_TYPE." e ";
				
		if($user_status or $system_status or $rec_status){
			//if($user_status!='Not_defined')
			$sql .=" ,".EM_CONTACT_STATUS." c";
		}		
				
		$sql .=" where a.contact_id=b.contact_id and a.type='People'  and a.contact_id=d.contact_id and b.certification_type_id=e.certification_id and b.expiration_date >= '".date("Y-m-d")."'";
		
		if($contact_id){
			$sql .=" and a.contact_id like '$contact_id%'";
		}

		if($user_status or $system_status or $rec_status){
		 	if($user_status!='Not_defined'){
			$sql .=" and a.contact_id=c.contact_id";
			}
		}
		
		if($user_status){
			if($user_status!='Not_defined')
				$sql .= " and c.user_status='$user_status'";
			else
				$sql .= " and a.contact_id not in ( select distinct contact_id from ".EM_CONTACT_STATUS.")";
		}
		
	
		if($first_name){
			$sql .= " and a.first_name like '$first_name%'";
		}
		
		if($last_name){
			$sql .= " and a.last_name like '$last_name%'";
		}
		
		
		
		if($license){
			$sql .= " and b.certification_type_id='$license'";
		}
		
		if($exp_from!='' or $exp_to!=''){
			$sql .= " and b.expiration_date between '$exp_from' and '$exp_to'";
		}


		if($system_status){
			$sql .= " and c.system_status like '$system_status'";
		}
		
		if($rec_status){
			$sql .= " and c.recruitment_status like '$rec_status'";
		}
		
		if($city) {
			$sql .=" and d.city like '$city%'";
		}
		
		if($state) {
			$sql .=" and d.state like '$state%'";
		}
		
		if($zip!='' and $rad==0) {
			$sql .=" and d.zip like '$zip%'";
		}
		
		if($zip!='' and $rad!=0){
			$sql .=" and a.contact_id in (".$contact_string.")";
		}
		
		
		if($all!='all') $sql .=" limit 5";
		//else $sql .=" limit 0,100";
		
		
		//echo '<br>'.$sql;	
		$result = $this->db->query($sql,__FILE__,__LINE__);
		
		
		$contact = array();
		$x=0;
		while($row=$this->db->fetch_array($result)){
			foreach($row as $key=>$value){
				$contact[$x][$key] = $value;
			}
			$sql_staff = "select count(*) as event_count from ".EM_STAFFING." where contact_id='$row[contact_id]' ";
			$result_staff = $this->db->query($sql_staff,__FILE__,__LINE__);	
			$row_staff = $this->db->fetch_array($result_staff);
			$contact[$x]['event_count'] = $row_staff['event_count'];
			$x++;
		}
		if($event_count!=''){
			$contact_temp = array();
			$x=0;
			foreach($contact as $key=>$value){				
				if($value[event_count]>=$event_count){
					$contact_temp[$x] = $value;
					$x++;
				}
			}
			while(count($contact)) array_shift($contact);
			$contact = $contact_temp;
		}
		$contactInRange = array();
		if($zip!='' and $rad!=0){

		$x=0;
		//print_r($contact);
		foreach($contact as $key=>$value){				
			foreach($contact_list as $key_zip=>$value_zip){
				//if($value_zip[zip]==$value[zip]){

					$contactInRange[$x] = $value;
					$contactInRange[$x]['distance'] = $value_zip['distance'];
					$x++;
					break;
				//}
			}
		}
			$tmp = array();
			foreach($contactInRange as $ma)
				$tmp[] = $ma['distance'];
			array_multisort($tmp,SORT_ASC,SORT_NUMERIC,$contactInRange);
		} else $contactInRange = $contact;
			//print_r($contactInRange);
			
			?>
			<table id="search_ExpiredLicensesList" class="event_form small_text" width="100%">
			<thead>
						
			<tr>
			<?php if($csv!=''){ ?>
				<th>Address::</th>
				<th>City::</th>
				<th>State::</th>
				<th>Zip::</th>
				<th>Email::</th>
			<?php }	?>
			<th>Contact Id::</th>
			<th>First Name::</th>
			<th>Last Name::</th>
			<th>Cert/Lic Type::</th>
			<th>Cert/Lic::</th>
			<th>Expiration::</th>
			</tr>
			</thead>
			<tbody>
			
			<?php	
			$i=0;	
			$contact_processed_array = array();
			$contacts='';
			foreach($contactInRange as $key=>$value){		
				
				if(!(in_array($value['contact_id'],$contact_processed_array))){
				$flag = "available";
				if($available_on!='0000-01-01' or $available_till!='9999-12-31'){
				if($available_on!='0000-01-01' and $available_till=='9999-12-31'){
					$sql_unavailabledate="select * from ".EM_CONTACT_UNAVAILABILITY." where contact_id='".$value[contact_id]."' and unavailable_date='$available_on'";
				} else
					$sql_unavailabledate="select * from ".EM_CONTACT_UNAVAILABILITY." where contact_id='".$value[contact_id]."' and unavailable_date between '$available_on' and '$available_till'";
				$result_unavailabledate=$this->db->query($sql_unavailabledate,__FILE__,__LINE__);
				
					if($this->db->num_rows($result_unavailabledate)>0)				
					{
						$flag = "unavailable";
					}
				}
				if($flag == "available"){
				
				$contacts= $contacts . $value[contact_id].',';
				$i++;
				?>
				<tr> 
				<?php if($csv!=''){
				 	$sql_email = "select email from ".CONTACT_EMAIL." where contact_id='$value[contact_id]' ";
					$result_email = $this->db->query($sql_email,__FILE__,__LINE__);	
					$row_email = $this->db->fetch_array($result_email);
					?>
					<td><?php echo $value[street_address]; ?></td>
					<td><?php echo $value[city]; ?></td>
					<td><?php echo $value[state]; ?></td>
					<td><?php echo $value[zip]; ?></td>
					<td><?php echo $row_email[email]; ?></td>
				<?php }	?>
				<td><a href= "contact_profile.php?contact_id=<?php echo $value[contact_id];?>"><?php echo $value[contact_id]; ?></a></td>
				<td><a href= "contact_profile.php?contact_id=<?php echo $value[contact_id];?>"><?php echo $value[first_name]; ?></a></td>
				<td><a href= "contact_profile.php?contact_id=<?php echo $value[contact_id];?>"><?php echo $value[last_name]; ?></a></td>
				<td><?php echo $value[cert_type]; ?></td>
				<td><?php echo $value[credential_type]; if($value[credential_type]=='License') echo '('.$value[state].')'; ?></td>
				<td><?php echo $value[expiration_date]; ?></td>
				</tr>
				<?php
				$contact_processed_array[] = $value['contact_id'];
				 }
				}
			}

		if($i==0) { ?>
		<tr>
		<?php if($csv!=''){ ?><td colspan="5">&nbsp;</td><?php } ?>
		<td colspan="6" align="center">no result</td></tr>
		<?php }
		else { 
		$contacts = substr($contacts,0,strlen($contacts)-1);
		?>
		<tr>
		<?php if($csv!=''){ ?><td colspan="5">&nbsp;</td><?php } ?>
		<td colspan="6" align="right">
			<a href="javascript:void(0);" onclick="javascript: evt_contact.emailToAll('local','<?php echo $contacts;?>','evt_contact',
													{ preloader: 'prl',
													onUpdate: function(response,root){
													 document.getElementById('div_event').innerHTML=response;
													 document.getElementById('div_event').style.display='';
													 }});">Email To Contacts</a>
			</td></tr>
		<?php }	?>
		</tbody>
		</table>
		<div class="verysmall_text form_bg">*Hold 'Shift Key' to sort multiple field</div>
		<?php
		
		if($all !='all')
		{
		?>
		<div align="right"><a href="show_all.php?action=license">more..</a></div>
	<?php } 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;			
	}
	
	function EmailToExpiredLicenses()
	{
		
		$sql= "select a.*, b.* from ".TBL_CONTACT." a, ".EM_CERTIFICATION." b where a.contact_id=b.contact_id and  b.expiration_date<'".date("Y/m/d")."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
	
		while($row = $this->db->fetch_array($result)) 
		{	
		
		$sql_positn = "select * from ".EM_CERTIFICATION_TYPE." where certification_id='".$row[certification_type_id]."'";
		$result_positn = $this->db->query($sql_positn,__FILE__,__lINE__);
		$row_positn = $this->db->fetch_array($result_positn);
				

		
		$subject=$row[first_name]." ".$row[last_name]." shows your ".$row_positn[cert_type]." expires on ".$row[expiration_date];
		$message = "This is an automated message for ".$row[first_name]." ".$row[last_name].". Our records show that your ".$row_positn[cert_type]." is either expired or is going to expire on ".$row[expiration_date].". <br>To continue receiving event notifications and stay current with-in our network please fax your license to us at XXX-XXX-XXXX or call our credentialing team at 9238498234";
		
	
		
		$objMail = new PHPMailer();
		$objMail->From = "no-reply@eventManagement.com";
		$objMail->FromName = "No Reply";
		$objMail->Subject = $subject;
		
		$objMail->IsHTML(true);
		$objMail->Body = $message;	
		
		
		$sql_email= "select * from ".CONTACT_EMAIL." where contact_id='".$row['contact_id']."'";
		$result_email = $this->db->query($sql_email,__FILE__,__lINE__);
		$email=array();
		while($row_email= $this->db->fetch_array($result_email))
		{
			$objMail->AddAddress($row_email['email'],"$row[first_name] $row[last_name]");
		}

		echo $subject."<br>";
		echo $message."<br><br><br><br>";
		if(!$objMail->Send()) { echo 'email not send<br>'; }
		
		}
	
		
		
	}
	
	
	function CreateRecruitingStatus($runat)
	{
	

		switch($runat)
		{
			
			case 'local' :
				
				$formName = 'frm_manage_Recruiting_status';
				$ControlNames=array("recruiting_status"			=>array('recruiting_status',"''","Please enter Recruiting Status","span_recruiting_status")
							);
					
				$ValidationFunctionName="frm_addrecriting_CheckValidity";
				
				$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($formName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
				echo $JsCodeForFormValidation;
				
				?>
					<script language="javascript" type="text/javascript">
					function frm_manage_Recruiting_type_validaterecruiting() {						
						 if(document.getElementById("recruiting_status").value=='')
						 {
							document.getElementById('spancertification_type_id_frm_edit_Recruiting').innerHTML="please enter recruiting status";
							return false;
						 }						 		
					}				
					function frm_manage_Recruiting_type_validateFeildRecruiting() {						
						//alert(document.getElementById("cert_type_select_credential").selectedIndex);
						if(document.getElementById("type_select3").value==document.getElementById("type_select_replace3").value) {
							alert("Plz.. select different different recruiting status");
							return false;
						}
						else {					
						var location="manage_masterfields.php?id="+document.getElementById("type_select3").value + "&replcedwith="+document.getElementById("type_select_replace3").value+ "&action=delete_recruiting_status";						
							window.location=location;
						
						}
					
					
					}
				
					</script>
					
					<ul id="error_list">
					  <li><span id="span_recruiting_status" class="normal" ></span></li>
					</ul>
				  <form method="post" action="" enctype="multipart/form-data" name="<?php echo $formName?>" >
				  <table width="100%" class="table">

				    <tr>
					 <th width="21%">Add Recruiting Status:</th>
				     <td width="32%" ><input type="text" name="recruiting_status" id="recruiting_status" /></td>
					 <td width="6%" ><input type="submit" name="recruitingsub" id="recruitingsub"  style="width:auto" value="go" 
					 					onclick="return <?php echo $ValidationFunctionName ?>();"/></td>
					 
					 <td width="5%">&nbsp;</td>
				   
				     <td width="13%" ><select name="type_select3" id="type_select3">
                          <?php
				      $sql = "select * from ".EM_RECRUITING_STATUS;
				      $result = $this->db->query($sql,__FILE__,__LINE__);
				      while($row = $this->db->fetch_array($result)){
				      	?>
                          <option value="<?php echo $row[recruiting_status_id] ?>" ><?php echo $row[recruiting_status] ?></option>
                          <?php
				      }
				      ?>
                        </select></td>
				      <td width="13%" >
				      <select name="type_select_replace3" id="type_select_replace3">
				      <?php
				      $sql = "select * from ".EM_RECRUITING_STATUS;
				      $result = $this->db->query($sql,__FILE__,__LINE__);
				      while($row = $this->db->fetch_array($result)){
				      	?>
				      	<option value="<?php echo $row[recruiting_status_id] ?>" ><?php echo $row[recruiting_status] ?></option>
				      	<?php
				      }
				      ?>
				      </select>				      </td>
				      <td width="10%"><a href="#" onclick= "javascript: if(confirm('Are you sure?')) return frm_manage_Recruiting_type_validateFeildRecruiting();">
					  				  <img src="images/trash.gif" border="0" /></a></td>
			      
				      </td>
				    </tr>
				  </table>
				</form>
				<?php
				break;
				
			case 'server' :
					extract($_POST);
					$this->recruiting_status=$recruiting_status;
						$insert_sql_array = array();
						$insert_sql_array[recruiting_status] = $this->recruiting_status;
						$this->db->insert(EM_RECRUITING_STATUS,$insert_sql_array);
		
					
					$_SESSION[msg]="Recruiting Status Created Successfully!!";
			?>
				<script type="text/javascript">
				 window.location = '<?php echo $_SERVER[PHP_SELF] ?>';
				</script>
			<?php
			break;
			
			
			default : echo 'Wrong Paramemter passed';
				
			break;
			
			
		
		}// end of switch

		
		
	} // end of recruiting status	
	
	
	
		function Delete_Recruiting_Status($type_select, $type_select_replace){
		
			$sql = "delete from ".EM_RECRUITING_STATUS." where recruiting_status_id = '".$type_select."'";
			$this->db->query($sql,__FILE__,__LINE__);

			if($type_select_replace!='')
			{
				$sql_contact = "update ".EM_CONTACT_STATUS." set recruitment_status = '".$type_select_replace."' where recruitment_status = '".$type_select."'";		
				$this->db->query($sql_contact,__FILE__,__LINE__);
			}
			
		
			    $_SESSION[msg]="Recruiting Status Replaced Successfully!!";
			?>
				<script type="text/javascript">
				 window.location = "<?php echo $_SERVER[PHP_SELF] ?>";
				</script>
			<?php			
		
	} // end of Del recruiting status
	
	
	
	function Contact_Unavailability($contact_id,$all='',$onmore='')
	{
		ob_start();		
		?>	
		<table width="100%" class="event_form small_text">
		<tr>
			<td colspan="2"><h2>Contact Unavailability</h2></td>
			<?php 
		if($onmore!='onmore')
		{
		?>
			<td align="right" colspan="3"><a href="javascript:void(0);" onclick="javascript: em.addUnavailableDate('local','','','<?php echo $contact_id ?>',{ preloader: 'prl',
	onUpdate: function(response,root){
		 document.getElementById('div_credential').innerHTML=response;
		 document.getElementById('div_credential').style.display='';	 
		 start_cal();
		 end_cal();
		 }});  return false;">add new</a></td>
	 <?php 
	 }
	 ?>
		</tr>
		
		<tr>
		<th width="19%">Unavailabe Dates</td>
		<th width="15%">Event</td>
		<th width="20%">Unavailability Status</td>
		<th width="40%">Note</td>
		<th width="6%">&nbsp;</td>
	
		</tr>	
		<?php
	
		if($all=='all') {
			$sql="select distinct(unavailable_date),note,event_id,unavailable_id,unavailability_status from ".EM_CONTACT_UNAVAILABILITY." where contact_id='".$contact_id."' and unavailable_date>='".date('Y-m-d',time())."' order by unavailable_date asc";
		}
		else {
		 $sql="select distinct(unavailable_date),note,event_id,unavailability_status from ".EM_CONTACT_UNAVAILABILITY." where contact_id='".$contact_id."' and unavailable_date>='".date('Y-m-d',time())."'  order by unavailable_date asc limit 0,6";
		 //$sql="select distinct(unavailable_date),note,event_id,unavailability_status from ".EM_CONTACT_UNAVAILABILITY." where contact_id='".$contact_id."' and unavailable_date>='".date('Y-m-d',time())."' and unavailable_date<='".date('Y-m-d',mktime(0,0,0,date('m'),date('d')+7,date('Y')))."' order by unavailable_date asc";
		}
		$result=$this->db->query($sql,__FILE__,__LINE__);
		while($row=$this->db->fetch_array($result))
		{
		?>
		<tr>
			<td><?php echo $row['unavailable_date']; ?></td>
			<td>
			<?php 
				$sql_event="select * from ".EM_EVENT." where event_id='".$row['event_id']."'";
				$result_event=$this->db->query($sql_event,__FILE__,__LINE__);
				$row_event=$this->db->fetch_array($result_event);
				?>
				<a href="event_profile.php?event_id=<?php echo $row['event_id']; ?>">
				<?php if($row['event_id']){ echo 'GE ID '.$row_event['group_event_id']; } ?></a>
			</td>
			<td>
			<?php
			if($all!='') {
			?>	<select name="unavailability_status" id="unavailability_status" onchange="javascript: em.editUnavailabllity('<?php echo $row['unavailable_id'];?>',this.value,{preloader:'prl'})">
					<?php
					$sql_avl="select * from ".EM_UNAVAILABILITY_STATUS;
					$result_avl=$this->db->query($sql_avl,__FILE__,__LINE__);
					?><option value="">--- Select---</option><?php
					while($row_avl=$this->db->fetch_array($result_avl)) { ?>
						<option value="<?php echo $row_avl['unavailability_status_id']; ?>" <?php if($row['unavailability_status']==$row_avl['unavailability_status_id']) echo 'selected="selected"'; ?> ><?php echo $row_avl['unavailability_status']; ?></option> 
					<?php } ?>
				</select>
			<?php }
			else {
				$sql_avl="select unavailability_status from ".EM_UNAVAILABILITY_STATUS." where unavailability_status_id=' ".$row['unavailability_status']."'";
				$result_avl=$this->db->query($sql_avl,__FILE__,__LINE__);
				$row_avl=$this->db->fetch_array($result_avl);
				echo $row_avl['unavailability_status'];
				}
			?>
			</td>
			<td><?php echo $row['note']; ?></td>
			<td>
			<?php 
			if($onmore=='onmore')
			{
				?>
				<a href="javascript:void(0)" onclick="javascript: if(confirm('are you sure?')){ em.deleteContactDates('<?php echo $row['unavailable_id'] ?>',{onUpdate: function(response,root){
				em.Contact_Unavailability('<?php echo $contact_id ?>','all','onmore',{target:'showalldates',preloader: 'prl'});
				
			 	},preloader:'prl'})} else {return false; }"><img src="images/trash.gif" /></a>
				<?php 
			}
			?>
			</td>
		</tr>	
		<?
		}
	if($all!='all'){
	?>
	<tr>
	 <td colspan="5" align="right">
	 <a href="javascript:void(0);" onclick="javascript: em.showUnavailableDate('<?php echo $contact_id ?>',
	 'onmore',{ preloader: 'prl',
	onUpdate: function(response,root){
	 document.getElementById('div_credential').innerHTML=response;
	 document.getElementById('div_credential').style.display='';
	 }});  return false;">more...</a></td>
	</tr>
	<?php } ?>
	
	</table>
	<?php 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function editUnavailabllity($unavailable_id,$unavailability_status){
		ob_start();
		
		
		echo $unavailable_id;
		echo $unavailability_status;
		
		$sql = "update ".EM_CONTACT_UNAVAILABILITY." set unavailability_status = '".$unavailability_status."' where unavailable_id = '".$unavailable_id."'";
		$this->db->query($sql,__FILE__,__LINE__);
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
		
		}
		
	function deleteContactDates($unavailable_id)
	{
		ob_start();

		$sql="select * from ".EM_CONTACT_UNAVAILABILITY." where unavailable_id='".$unavailable_id."'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		$row=$this->db->fetch_array($result);

		if($row['event_id']==0)
		{
		$sql_del="delete from ".EM_CONTACT_UNAVAILABILITY." where unavailable_id='".$unavailable_id."'";
		$this->db->query($sql_del,__FILE__,__LINE__);
		?>
		<script type="text/javascript">
		  alert('Date Deleted');
		</script>
		<?php  
		}
		else
		{
		?>
		<script type="text/javascript">
		  alert('Date assigned to event can not be Deleted');
		</script>
		<?php  
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	
	}
	
	function showUnavailableDate($contact_id,$onmore='') {
		ob_start();
		?>
		<div class="prl">&nbsp;</div>
		<div id="lightbox">
		<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
		<div id="TB_ajaxWindowTitle">All Unavailable Dates</div>							
		<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_credential').style.display='none'; em.Contact_Unavailability('<?php echo $contact_id; ?>',{target:'div_Unavailability'})"><img border="0" src="images/close.gif" alt="close" /></a></div>
		</div>
		<div  class="white_content"> 
		<div style="padding:20px;" >
		<div id="showalldates">	
		<?php	
		echo $this->Contact_Unavailability($contact_id,'all',$onmore);				
		?>
		</div>		
		</div></div></div>
		
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;

	}
	
	function addUnavailableDate($runat,$start_date='',$end_date='',$contact_id='',$unavailability_status='',$note='')
	{
		ob_start();
		switch ($runat) {
			case 'local':
				$formName = 'frm_addUnavailableDate';
				$ControlNames=array("start_date"	=>array('start_date',"''","Please select start date","span_start_date_display"),
									"unavailability_status"	=>array('unavailability_status',"''","Please select unavailability status ","span_unavailability_status")									
									);
					
				$ValidationFunctionName="frm_addEquipment_CheckValidity";
				
				$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($formName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
				echo $JsCodeForFormValidation;
				?>
				
				<div class="prl">&nbsp;</div>
				<div id="lightbox">
				<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
				<div id="TB_ajaxWindowTitle">Add Unavailable Date</div>							
				<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_credential').style.display='none';"><img border="0" src="images/close.gif" alt="close" /></a></div>

				</div>
				<div  class="white_content"> 
				<div style="padding:20px;" >
				<ul id="error_list">
				<li><span id="span_start_date_display"></span></li>
				<li><span id="span_end_date_display"></span></li>
				<li><span id="span_unavailability_status"></span></li>
				</ul>	
				
				<form action="" method="POST" name="<?php echo $formName; ?>" id="<?php echo $formName; ?>" enctype="multipart/form-data">
				<table class="table" width="100%">
				  <tr>
				    <th>Start Date:</th>
					<td><input name="start_date" type="text"  id="start_date" value="" readonly="true"/>
					<script type="text/javascript">
						
						 var exp_date;
						 function start_cal()  {
						  var cal11=new Calendar({
								  inputField   	: "start_date",
								  dateFormat	: "%Y-%m-%d",
								  trigger		: "start_date",
								  weekNumbers   : true,
								  bottomBar		: true,
								  showTime      : 12,
								  onSelect		: function() {
														this.hide();
														document.<?php echo $formName;?>.start_date.value=this.selection.print("%Y-%m-%d");														
														if(exp_date<=this.selection.get()) {
															document.getElementById('expiration_date').value="";
														}														
														end_cal(this.selection.get()+1);	
													},
													
								
						  });
						  }
						
						</script>
					</td>
					<th>End Date:</th>
					<td><input type="text" name="end_date" id="end_date" value="" readonly="true" />
					<script type="text/javascript">
						  
						 function end_cal(minDate) { 
						  var cal12=new Calendar({
								  inputField   	: "end_date",
								  dateFormat	: "%Y-%m-%d",
								  trigger		: "end_date",
								  weekNumbers   : true,
								  bottomBar		: true,
								  min			: minDate,
								  showTime      : 12,
								  onSelect		: function() {
														this.hide();
														document.<?php echo $formName;?>.end_date.value=this.selection.print("%Y-%m-%d");
														exp_date=this.selection.get();
													},
													
								
						  });
						  }
						 
					
               		 </script>
					</td>
					<td><input type="button" name="set" id="set" value="Set" onclick="javascript:if(this.form.start_date.value!=''){
						em.checkConflict(this.form.start_date.value,this.form.end_date.value,this.form.unavailability_status.value,this.form.notes.value,'<?php echo $contact_id ?>',{
						onUpdate: function(response,root){ 	
						  document.getElementById('div_credential').style.display='none';
						  em.Contact_Unavailability('<?php echo $contact_id ?>',{target:'div_Unavailability',preloader: 'prl'});
						},preloader: 'prl'
						}); 
					} else { alert('Please select start date'); } return false;" /></td>
				  </tr>
				  <tr>
				    <th>Unavailability Status:</th>
					<td colspan="4">
					<select name="unavailability_status" id="unavailability_status" >
					<?php
				      $sql = "select * from ".EM_UNAVAILABILITY_STATUS;
					  $result = $this->db->query($sql,__FILE__,__lINE__);
				      while($row = $this->db->fetch_array($result)){ ?>
                         <option value="<?php echo $row[unavailability_status_id] ?>" ><?php echo $row[unavailability_status] ?></option>
                       <?php }  ?>
					</select></td>
				  </tr>
				  <tr>
				    <th>Note:</th>
					<td colspan="4"><textarea name="notes" id="notes" style="width:100%"></textarea></td>
				  </tr>
				</table>
				</form>
				</div>
				</div></div>
				<?php 
				break;
		
			case 'server':
				
				/*$init_date = strtotime($start_date);
				$dst_date = strtotime($end_date);				
				$offset = $dst_date-$init_date;				
				$dates = floor($offset/60/60/24) + 1;				
				for ($i = 0; $i < $dates; $i++)	{
					$newdate = date("Y-m-d", mktime(12,0,0,date("m", strtotime($start)),(date("d", strtotime($start)) + $i), date("Y", strtotime($start))));
					$insert_sql_array = array();
					$insert_sql_array[unavailable_date] = $newdate;
					$insert_sql_array[note] = $note;
					$insert_sql_array[contact_id] = $contact_id;
					$insert_sql_array[unavailability_status] = $unavailability_status;
							 
					$this->db->insert(EM_CONTACT_UNAVAILABILITY,$insert_sql_array);
					}
				*/
				break;
			default: echo 'wrong parameter passed';
		
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function checkConflict($start_date='',$end_date='',$unavailability_status='',$notes='',$contact_id='')
	{
		ob_start();
		$date_arr = array();
		
		if($end_date!=''){
			$date_arr = $this->getDateArray($start_date,$end_date);
		}
		else {
			$date_arr[0] = $start_date;
		}
		for($i=0 ; $i < count($date_arr); $i++){
			$sql = "select * from ".EM_CONTACT_UNAVAILABILITY." where contact_id='".$contact_id."' and unavailable_date='".$date_arr[$i]."'";
			$result = $this->db->query($sql,__FILE__,__LINE__);
			if($this->db->num_rows($result)>0){
				$row = $this->db->fetch_array($result);
				if($row[event_id]>0 and $row[event_id]!=''){
				$sql_eve = "select * from ".EM_EVENT." where event_id='".$row[event_id]."'";
			    $result_eve = $this->db->query($sql_eve,__FILE__,__LINE__);
			    $row_eve = $this->db->fetch_array($result_eve);
				  ?><script type="text/javascript">alert('This date is unavailable for the event GE ID '+<?php echo $row_eve['group_event_id']; ?>)</script><?php
				}
				else {
					?><script type="text/javascript">alert('This date is already booked \nNote: <?php echo $row['note']; ?>')</script><?php
				}
			} else {
				$this->Availability($contact_id,'',$date_arr[$i],'','',$unavailability_status,$notes);
			}
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
		
	}
	
	function getDateArray($start_date,$end_date)
	{
		$dates = array();
		$s_date = explode('-',$start_date);
		$e_date = explode('-',$end_date);
		$date1 = mktime(0,0,0,$s_date[1],$s_date[2],$s_date[0]);
		$date2 = mktime(0,0,0,$e_date[1],$e_date[2],$e_date[0]);
		$dateDiff = $date2 - $date1;
		$fullDays = floor($dateDiff/(60*60*24));	
		for($i=0;$i<$fullDays;$i++){
			$dates[$i] = date('Y-m-d',mktime(0,0,0,$s_date[1],$s_date[2]+$i,$s_date[0]));
		}
		return $dates;
	}
	
	function deleteDocument($contact_id,$doc_id){
		ob_start();		
		
		$sql = "select * from ".EM_CONTACT_DOCUMENTS." where document_id = '".$doc_id."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);	
		$row=$this->db->fetch_array($result);
		unlink("uploads/".$row[document_server_name]);
		
		$sql = "delete from ".EM_CONTACT_DOCUMENTS." where document_id= ".$doc_id;
		$this->db->query($sql,__FILE__,__lINE__);	
		?>
		<script type="text/javascript">
		em.showDocuments('<?php echo $contact_id ?>',{target:'documents',preloader: 'prl'});
		</script>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	
	}
		
	function showDocuments($contact_id) {			
		ob_start();	
		$this->contact_id=$contact_id;
		$sql = "select * from ".EM_CONTACT_DOCUMENTS." where contact_id = '".$this->contact_id."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		?>
		
		<table width="50%" >
		<?php while($row=$this->db->fetch_array($result)) {?>
		<tr>
		<td ><a href="download.php?doc=<?php echo $row[document_server_name]; ?>&name=<?php echo $row[document_name];?>">
				<?php echo $row[document_name]; ?> </a>
		</td>
		
		
		<td><a href="#" onclick="javascript: if(confirm('Are you sure?')){em.deleteDocument('<?php echo $this->contact_id; ?>','<?php echo $row['document_id'];?>', {preloader: 'prl'} ); } return false;" ><img src="images/trash.gif" border="0" /></a>	</td>
		</tr>
		<?php } ?>				
</table>	
		<?php	
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	
	function CreateUnavailabilityStatus($runat)
	{
	
		ob_start();
		switch($runat){
		
			case 'local':
			
					$sql = "select * from ".EM_UNAVAILABILITY_STATUS;
					$result = $this->db->query($sql,__FILE__,__lINE__);
					$result2 = $this->db->query($sql,__FILE__,__lINE__);
					
					
					$FormName='frm_Add_Unavailability_Status';
			$ControlNames=array("unavailability_status"		=>array('unavailability_status',"''","please enter unavailability status !!  ","span_frm_Add_Unavailability_Status_unavailability_status"),
											);
											
						
						$ValidationFunctionName="frm_Add_Unavailability_Status_CheckValidity";
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;

					?>
					<script language="javascript" type="text/javascript">
						function validateUnavailabilityStatusFeild() {						
						//alert(document.getElementById("cert_type_select_credential").selectedIndex);
						if(document.getElementById("type_select_unavailability").value==document.getElementById("type_select_replace_unavailability").value) {
							alert("Plz.. select different unavailability status ");
							return false;
						}
						else {					
						var location="manage_masterfields.php?id="+document.getElementById("type_select_unavailability").value + "&replcedstatuswith="+document.getElementById("type_select_replace_unavailability").value+ "&action=delete_unavailability_status";						
							window.location=location;
						
						}
					
					}
				
					</script>
						
						<ul id="error_list">
							    <li><span id="span_frm_Add_Unavailability_Status_unavailability_status" class="normal"></span></li>
						</ul>		
					
					<form action=""  method="post" enctype="multipart/form-data" name="<?php echo $FormName; ?>">
					
					<table class="table"  width="100%">
					<tr>
					  <th width="21%">Add Unavailability Status:</th>
					  <td width="32%"><input type="text" name="unavailability_status" id="unavailability_status" /></td>
					  <td width="6%"><input type="submit" value="go" name="unavailabilitysubmit" id="unavailabilitysubmit" style="width:auto" 
					                 onClick="return <?php echo $ValidationFunctionName?>();" /></td>
				      <td width="5%">&nbsp;</td>	
				      <td width="13%"><select name="type_select_unavailability" id="type_select_unavailability">
                          <?php
				      
				      while($row = $this->db->fetch_array($result)){
				      	?>
                          <option value="<?php echo $row[unavailability_status_id] ?>" ><?php echo $row[unavailability_status] ?></option>
                          <?php
				      }
				      ?>
                        </select>
					  </td>
						
				      <td width="13%">
				      <select name="type_select_replace_unavailability" id="type_select_replace_unavailability">
				      <?php
				      
				      while($row = $this->db->fetch_array($result2)){
				      	?>
				      	<option value="<?php echo $row[unavailability_status_id] ?>" ><?php echo $row[unavailability_status] ?></option>
				      	<?php
				      }
				      ?>
				      </select>
					  </td>
				      <td width="10%"><a href="#" onclick= "javascript: if(confirm('Are you sure?')) return validateUnavailabilityStatusFeild();">
					  				  <img src="images/trash.gif" border="0" /></a>
					  </td>
				      
					  </tr>
					  </table>
					  </form>

						
					<?php 
			break;
			
			case 'server':
					
					extract($_POST);
					$this->unavailability_status=$unavailability_status;
					
					$return =true;
					if($this->Form->ValidField($unavailability_status,'empty','Please Enter Your Unavailability Status')==false)
						$return =false;
					if($return){	
						$insert_sql_array = array();
						$insert_sql_array[unavailability_status] = $this->unavailability_status;
						$this->db->insert(EM_UNAVAILABILITY_STATUS,$insert_sql_array);
					}
					
					$_SESSION[msg]="Unavailability Status Created Successfully!!";
			?>
				<script type="text/javascript">
				 window.location = '<?php echo $_SERVER[PHP_SELF] ?>';
				</script>
			<?php
			break;
			
			
			default : echo 'Wrong Paramemter passed';
				
			break;
			
			
		
		}// end of switch
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		
		
	} // end of CreateEventStatus	
	
	
	
		function Delete_Unavailability_Status($type_select, $type_select_replace){
	
			$sql = "delete from ".EM_UNAVAILABILITY_STATUS." where unavailability_status_id = '".$type_select."'";
			//echo $sql;
			$this->db->query($sql,__FILE__,__LINE__);

			if($type_select_replace!='')
			{
				$sql_contact = "update ".EM_CONTACT_UNAVAILABILITY." set unavailability_status = '".$type_select_replace."' where unavailability_status = '".$type_select."'";
				//echo $sql_contact;
				$this->db->query($sql_contact,__FILE__,__LINE__);
			}
	
			    $_SESSION[msg]="Event Status Replaced Successfully!!";
			?>
				<script type="text/javascript">
				 window.location = '<?php echo $_SERVER[PHP_SELF] ?>';
				</script>
			<?php
	} // end of Del Status
	
	
	function erpContactScreenCustom($contact_id,$order_id=''){
		ob_start();
		$sql = "select * from ".erp_CONTACTSCREEN_CUSTOM." where contact_id = '$contact_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		$row=$this->db->fetch_array($result);
                //A quick hack to fix the issue of no id
                if( $row == false){
                    $this->db->insert(erp_CONTACTSCREEN_CUSTOM ,  array('contact_id' => $contact_id ));
                    $result = $this->db->query($sql,__FILE__,__lINE__);
                    $row=$this->db->fetch_array($result);
                }
                
		$contact_csr_id = $row[csr];
		
		if($_REQUEST[order_id] !=''){
			$sql_c = "select * from erp_order where order_id = '$order_id'";
			$result_c = $this->db->query($sql_c,__FILE__,__lINE__);		
			$row_c=$this->db->fetch_array($result_c);
			$order_csr_id = $row_c[csr];
		}
			
		if($contact_csr_id==0){
			 $csr_id=$_SESSION[user_id];
		}
		else if($order_id != ''){
	  	     $csr_id=$order_csr_id;
		}
		else{
			 $csr_id=$contact_csr_id;
		}
		
		
		if($this->db->num_rows($result)==0){
			echo $this->saveerpCustomFields('',$contact_id,'','','',$_SESSION[user_id],'');
		}
		
		//echo $sql;
		$FormName = 'frm_erp_contact_screen';
		$ControlNames=array("account_id"	=>array('account_id',"''","Plz Enter Account Id","span_account_id"),
							"account_type"	=>array('account_type',"''","Plz Enter Account Type","span_account_type"),
							"csr"	=>array('csr',"''","Plz Enter CSR","span_csr")
							);
		$ValidationFunctionName="CheckValidityCustom";
		
		$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
		echo $JsCodeForFormValidation;
		?>
		<ul id="error_list">
			<li><span id="span_account_id" ></span></li>
			<li><span id="span_account_type" ></span></li>
			<li><span id="span_csr" ></span></li>
		</ul>	
		<form name="frm_erp_contact_screen" action="" method="post" >
		
		<table class="table">
		<tr>
		<th align="right">Account Id:</th>
		<td><input name="account_id" id="account_id" type="text" value="<?php echo $row[account_id];?>" /></td>
		</tr>
		
		<tr>
		<th align="right">Account Type:</th>
		<td>
			<select id="account_type" name="account_type">
			    <option value="">-Select Account Type-</option>
				<option value="Vendor" <?php if($row[account_type] == 'Vendor') echo 'selected="selected"';?> >Vendor</option>
				<option value="Custom" <?php if($row[account_type] == 'Custom') echo 'selected="selected"';?>>Custom</option>
				<option value="Wholesale" <?php if($row[account_type] == 'Wholesale') echo 'selected="selected"';?>>Wholesale</option>
				<option value="Distributor" <?php if($row[account_type] == 'Distributor') echo 'selected="selected"';?>>Distributor</option>
				<option value="Consumer" <?php if($row[account_type] == 'Consumer') echo 'selected="selected"';?>>Consumer</option>
			</select>	
<?php /*?>		<input name="account_type" id="account_type" type="text" value="<?php echo $row[account_type];?>" />
<?php */?>		</td>
		</tr>		
		<tr>
		<th align="right">CSR:</th>
		<td><select name="csr" id="csr" type="text" >
			<option value="">--Select User--</option>
			<?php 
			$sql = "select * from ".TBL_USER;
			$result = $this->db->query($sql,__FILE__,__lINE__);		
			while($row1=$this->db->fetch_array($result)){?>
			<option value="<?php echo $row1[user_id];?>" <?php if($csr_id == $row1[user_id]) echo 'selected="selected"';?> >
				<?php echo $row1[first_name].' '.$row1[last_name];?>
			</option>
			<?php } ?>
			</select>
		</td>
		</tr>
		<th align="right">Sales:</th>
		<td><select name="sales" id="sales" type="text" >
			<option value="">--Select Sales--</option>
			<option value="DYSTEJ" <?php if( $row["sales"] == "DYSTEJ" ){ echo "SELECTED"; } ?> >DYSTEJ</option>
			<option value="WIZBEN" <?php if( $row["sales"] == "WIZBEN" ){ echo "SELECTED"; } ?> >WIZBEN</option>
                        <option value="KONMAR" <?php if( $row["sales"] == "KONMAR" ){ echo "SELECTED"; } ?> >KONMAR</option>
                        <option value="ASPKAR" <?php if( $row["sales"] == "ASPKAR" ){ echo "SELECTED"; } ?> >ASPKAR</option>			
	
			</select>
		</td>
		</tr>
		<tr>
		  <th>Tax Exempt:</th>
		  <td>
		    <select name="text_exempt" id="text_exempt">
			  <option value="">-Select-</option>
			  <option value="Yes" <?php if( $row["text_exempt"] == "Yes"  ){ echo 'selected="selected"'; } ?>>Yes</option>
			  <option value="No" <?php if( $row["text_exempt"] == "No" || $row["text_exempt"] == '' ){ echo 'selected="selected"'; } ?>>No</option>
			</select>
		  </td>
		</tr>


                </tr>
                		<tr><td colspan="2">
			<a href="javascript:void(0);" onclick="javascript: if(<?php echo $ValidationFunctionName?>()){
																em.saveerpCustomFields(
																	'<?php echo $row[contact_screen_custom_id];?>',
																	'<?php echo $contact_id;?>',
																	'<?php echo $order_id; ?>',
																	document.frm_erp_contact_screen.account_id.value,
																	document.frm_erp_contact_screen.account_type.value,
																	document.frm_erp_contact_screen.csr.value,
																	document.frm_erp_contact_screen.sales.value,
																	document.frm_erp_contact_screen.text_exempt.value,
																	{preloader:'prl'});
																}">Save</a>			
			</td></tr>		
		</table>
		</form>
		<?php 
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function saveerpCustomFields($contact_screen_custom_id='',$contact_id='',$order_id='',$account_id='',$account_type='',$csr='',$sales='',$text_exempt=''){
		ob_start();
		//echo $contact_screen_custom_id.'aaa'.$order_id;
		$sql_array = array();
		$sql_array[account_id] = $account_id;
		$sql_array[account_type] = $account_type;
		$sql_array[contact_id] = $contact_id;
		if( !$order_id ){
		$sql_array[csr] = $csr; }
		$sql_array[sales] = $sales;
		$sql_array[text_exempt] = $text_exempt;
		
		$sql_insert = array();
		$sql_insert[account_id] = $account_id;
		$sql_insert[account_type] = $account_type;
		$sql_insert[csr] = $csr;
		$sql_insert[contact_id] = $contact_id;
		$sql_insert[sales] = $sales;
		$sql_insert[text_exempt] = $text_exempt;
		
		if( $contact_screen_custom_id ) $this->db->update(erp_CONTACTSCREEN_CUSTOM,$sql_array,'contact_screen_custom_id',$contact_screen_custom_id);		
		else $this->db->insert(erp_CONTACTSCREEN_CUSTOM,$sql_array);	
		
		$sql_update = array();
		$sql_update[CSR] = $csr;
		
		if($order_id != ''){
		  $this->db->update("erp_order",$sql_update,'order_id',$order_id);
		}	
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
}
?>
