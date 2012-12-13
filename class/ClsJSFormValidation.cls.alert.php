<?php
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
	//														Md. Aminul Islam (aminulsumon@yahoo.com)									//
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
	//		This Page is to Generate Javascript Codes For Form Validation																//
	//				[ Limitation: ]																										//
	//==================================================================================================================================//
	//	function ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields="",$ErrorMsgForSameFields=""):- 	//
	//			This Function Takes 5 Parameter																							//
	//	$FormName = The Name of the Form Inside which the HTML Controls(Eg. Textbox, List) are.											//
	//	$ControlNames = Two dimensional Array Consists Control Name, Value for Check and Error Message(expected).						//
	//	$ValidationFunctionName = The Name of Javascript Function Which will generated.													//
	//	$SameFields = Two Control Names in One Dimensional Format which Contents needed to be Same.										//
	//	$ErrorMsgForSameFields = Expected Error Message Which Will Returned For Same Field.												//
	//----------------------------------------------------------------------------------------------------------------------------------//
	/*
		Example:- 
				require_once("ClsJSFormValidation.cls.php");
				$Validity=new ClsJSFormValidation;
				$FormName="all";
				$ControlNames=array("txtName"			=>array("txtName","''","Please Enter Your Name.","spanname"));
				$ValidationFunctionName="CheckValidity";
				$SameFields=array("txtPassword","txtConfirmPassword");
				$ErrorMsgForSameFields="Password and Confirm Password Are not Same.";
				$JsCodeForFormValidation=$Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
				echo $JsCodeForFormValidation;
	*/		
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~//
	class ClsJSFormValidationAlert
	{
		function ShowJSFormValidationCodeAlert($FormName,$ControlNames,$ValidationFunctionName,$SameFields="",$ErrorMsgForSameFields="")
		{
			$JSValidation="<script language='javascript1.2'>
			
							function $ValidationFunctionName()
							{	
							
						
							var ck_name = /^[A-Za-z0-9 ]{3,20}$/;			
							var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i; 
							var ck_username = /^[A-Za-z0-9_]{3,20}$/;
							var ck_password =  /^[A-Za-z0-9!@#$%^&*()_]{6,20}$/;
							var ck_number = /^\d+$/;

							var returnValue=true;\n";
			foreach($ControlNames as $SingleControlName) {
					switch($SingleControlName[1])
					{
						case "''": 		
										$JSValidation.="
											if(document.$FormName.$SingleControlName[0].value=='')
											{
												//document.getElementById('$SingleControlName[3]').className='required';
												alert('$SingleControlName[2]');
												document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
												return returnValue;
											 }
											 else
											 {
												//document.getElementById('$SingleControlName[3]').className='normal';
												//document.getElementById('$SingleControlName[3]').innerHTML='';
											 }	\n";
									
										break;
						case "EMail":	$JSValidation.="
											var checkEmail;
											checkEmail=document.$FormName.$SingleControlName[0].value;

											if (!ck_email.test(checkEmail))
											{
												//document.getElementById('$SingleControlName[3]').className='required';
												alert('$SingleControlName[2]');
												document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
												return returnValue;
											}
											else
											 {
												//document.getElementById('$SingleControlName[3]').className='normal';
												//document.getElementById('$SingleControlName[3]').innerHTML='';
											 }		\n";
										break;
						case "VEmail":	$JSValidation.="
											var checkEmail;
											checkEmail=document.$FormName.$SingleControlName[0].value;

											if(checkEmail!=''){
											if (!ck_email.test(checkEmail))
											{
												//document.getElementById('$SingleControlName[3]').className='required';
												alert('$SingleControlName[2]');
												document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
												return returnValue;
											}
											else
											 {
												//document.getElementById('$SingleControlName[3]').className='normal';
												//document.getElementById('$SingleControlName[3]').innerHTML='';
											 }
											 }	
											 	\n";
										break;
						case "UserName":	$JSValidation.="
											var username;
											username=document.$FormName.$SingleControlName[0].value;
											if(!ck_username.test(username))
											{
												//document.getElementById('$SingleControlName[3]').className='required';
												alert('$SingleControlName[2]');
												document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
												return returnValue;
											}
											else
											 {
												//document.getElementById('$SingleControlName[3]').className='normal';
												//document.getElementById('$SingleControlName[3]').innerHTML='';
											 }		\n";
										break;
						case "Password":	$JSValidation.="
											var password;
											password=document.$FormName.$SingleControlName[0].value;
											if(!ck_password.test(password))
											{
												//document.getElementById('$SingleControlName[3]').className='required';
												alert('$SingleControlName[2]');
												document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
												return returnValue;
											}
											else
											 {
												//document.getElementById('$SingleControlName[3]').className='normal';
												//document.getElementById('$SingleControlName[3]').innerHTML='';
											 }		\n";
										break;
						case "RePassword":	$JSValidation.="
											var repassword;
											repassword=document.$FormName.$SingleControlName[0].value;
											password=document.$FormName.$SingleControlName[4].value;
											if(!ck_password.test(repassword))
											{
												//document.getElementById('$SingleControlName[3]').className='required';
												alert('$SingleControlName[2]');
												document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
												return returnValue;
											}
											else if(repassword!=password) {
												//document.getElementById('$SingleControlName[3]').className='required';
												alert('$SingleControlName[2]');
												document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
												return returnValue;
											}
											else {
												//document.getElementById('$SingleControlName[3]').className='normal';
												//document.getElementById('$SingleControlName[3]').innerHTML='';
											 }		\n";
										break;
										
						case "Tag":	$JSValidation.="
											var tag;
											tag=document.$FormName.$SingleControlName[0].value;
											tag_id=document.$FormName.$SingleControlName[4].value;
											if((tag=='' && tag_id==''))
											{
												document.getElementById('$SingleControlName[3]').className='required';
												document.getElementById('$SingleControlName[3]').innerHTML='$SingleControlName[2]';
												//document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
											}
											else {
												document.getElementById('$SingleControlName[3]').className='normal';
												document.getElementById('$SingleControlName[3]').innerHTML='';
											 }		\n";
										break;
						
						case "Number":	$JSValidation.="
											var number;
											number=document.$FormName.$SingleControlName[0].value;

											if (!ck_number.test(number))
											{
												//document.getElementById('$SingleControlName[3]').className='required';
												alert('Please enter number only.');
												document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
												return returnValue;
											}
											else
											 {
												//document.getElementById('$SingleControlName[3]').className='normal';
												//document.getElementById('$SingleControlName[3]').innerHTML='';
											 }		\n";
										break;

						default: 			$JSValidation.="
											if(document.$FormName.$SingleControlName[0].value=='')
											{
												//document.getElementById('$SingleControlName[3]').className='required';
												alert('$SingleControlName[2]');
												document.$FormName.$SingleControlName[0].focus();			
												returnValue=false;
												return returnValue;
											 }
											  else
											 {
												//document.getElementById('$SingleControlName[3]').className='normal';
												//document.getElementById('$SingleControlName[3]').innerHTML='';
											 }	\n";
									

					}	//End of Switch
				}		
			$JSValidation.=" return returnValue;
							 }		//End of JS Validation Function
							 </script>	\n";
			return $JSValidation;
		}
	
	
}
?>