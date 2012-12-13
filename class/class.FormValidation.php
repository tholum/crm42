<?php
/***********************************************************************************

	Class Discription : ValidateForm
	
	Class Memeber Functions : IsEmail($value)
							  IsUserName($value)
							  IsPassword($value)
							  IsRePassword($value,$revalue)
							  IsText()
							  IsNull($value)
							  IsHtml
							  IsSCheck($value)
							  IsMCheck($value)
							  function IsEmpty($value)
							  IsValidType($Type)
							  IsValidType($Type)
							  IsNumeric($value)
							  SetError($Err)
							  ValidField($Value,$CType,$ErrText="Invalid Error Type",$Params=array('Default'=>'','Min'=>false,'Max'=>false),$revalue='')
	
	
	Describe Function of Each Memeber function :
							  
							  1. function IsEmail($value)
							  
							  2. function IsUserName($value)
							  
							  3. function IsPassword($value)
							  
							  4. function IsRePassword($value,$revalue)
							  
							  5. function IsText()
							  
							  6. function IsNull($value)
							  
							  7. function IsHtml
							  
							  8. function IsSCheck($value)
							  
							  9. function IsMCheck($value)
							  
							  10. function function IsEmpty($value)
							  
							  11. function IsValidType($Type)
							  
							  12. function IsValidType($Type)
							  
							  13. function IsNumeric($value)
							  
							  14. function SetError($Err)
							  
							  15. function ValidField($Value,$CType,$ErrText="Invalid Error Type",$Params=array('Default'=>'','Min'=>false,'Max'=>false),$revalue='')			



************************************************************************************/
class ValidateForm
{
  var $ValidType=array('text','numeric','email','empty','null','UserName','Password','RePassword');
  var $ErrString="";
  var $retval=false;
  var $ErrtxtPrefix='<div class="errortxt">';
  var $ErrtxtSufix='</div>';
  var $ErrPrefix='<li>';
  var $ErrSufix='</li>';

	function IsEmail($value)
	{
		if(!$this->IsEmpty($value)){
		 if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $value)) 
		 return false;
		 else return true; 
		 }
		 elseif($this->IsEmpty($value)){
		   return false;
		  }
	}
	
	
	function IsUserName($value)
	{
		if(!$this->IsEmpty($value)){
		 if (!eregi("^[A-Za-z0-9_]{3,20}$", $value)) 
		 return false;
		 else return true; 
		 }
		 elseif($this->IsEmpty($value)){
		   return false;
		  }
	}
	
	
	function IsPassword($value)
	{
		if(!$this->IsEmpty($value)){
		 if (!eregi("^[A-Za-z0-9!@#$%^&*()_]{6,20}$", $value)) 
		 return false;
		 else return true; 
		 }
		 elseif($this->IsEmpty($value)){
		   return false;
		  }
	}

	
	
	function IsRePassword($value,$revalue)
	{
		if(!$this->IsEmpty($value)){
		 if (!eregi("^[A-Za-z0-9!@#$%^&*()_]{6,20}$", $value)) 
		 return false;
		 else if($value==$revalue) return true;
		 else return false; 
		 }
		 elseif($this->IsEmpty($value)){
		   return false;
		  }
	}


	function IsText()
	{
	$this->retval=false;
	 if(!$this->IsEmpty($value)){
	  if(is_string($value)) return true;else return false;
	   }elseif($this->IsEmpty($value)){
	   return false;
	   }
	}

	function IsNull($value){
	if(is_null($value)) return true;else return false;
	}
	
	function IsHtml(){
	}
	function IsSCheck($value){
	if(count($value)<=0) return false;else return true;
	}
	function IsMCheck($value){
	if(count($value)<=1) return false;else return true;
	}
	function IsEmpty($value){
	 if(strlen(trim($value))==0 || trim($value)=="") return true;else return false;
	}
	
	function IsValidType($Type){
	 if(in_array($this->ValidType) ) return true;else return false;
	}
			
	function IsNumeric($value){
	 if(!$this->IsEmpty($value)){
	  if(ctype_digit($value)){return true;} else{ return false;}
	   }else if($this->IsEmpty($value)){
	   return false;
	  }
	}
	
	
	function IsDouble($value)
	{
		if(!$this->IsEmpty($value)){
		 if (!eregi("^[-]?([0-9]{0,}(\.[0-9]+)?|0(\.[0-9]+)?|\.[0-9]+)", $value)) 
		 return false;
		 else return true; 
		 }
		 elseif($this->IsEmpty($value)){
		   return false;
		  }
	}

		
	function SetError($Err){
	  $this->ErrorString.=$this->ErrPrefix.$Err.$this->ErrSufix;
	}		
	
	 
	function ValidField($Value,$CType,$ErrText="Invalid Error Type",$Params=array('Default'=>'','Min'=>false,'Max'=>false),
	$revalue=''){
	  switch($CType){
	  case "text":
	  if(!$this->IsEmpty($Value) && $Min && strlen(trim($Value))<$Min)
	   {
		$ErrText=" Required Minimum  ".$Min." Character";  
		$this->SetError($ErrText);   
		$this->retval=false;  
	   }elseif(!$this->IsEmpty($Value) && $Max && strlen(trim($Value))>$Max)
	   {
		 $ErrText=" Required Maximum ".$Max." Character";
		$this->SetError($ErrText);   
		$this->retval=false;
		}elseif(!$this->IsText($Value))     {$this->SetError($ErrText);$this->retval=false;} else $this->retval=true;
	   break;
	   case "numeric":
		   if(!$this->IsEmpty($Value) && $Params['Min'] && strlen(trim($Value))<$Params['Min'])
		   {
			$ErrText=" Required Minimum  ".$Params['Min']." Number"; 
			$this->SetError($ErrText);   
			$this->retval=false;
		   }elseif(!$this->IsEmpty($Value) && $Params['Max'] && strlen(trim($Value))>$Params['Max'])
		   {
			$ErrText=" Required Maximum  ".$Params['Max']." Number"; 
			$this->SetError($ErrText);
			$this->retval=false;  
		   }elseif(!$this->IsNumeric($Value) || is_float($Value)){
		   $ErrText="Invalid Phone Number";
		   $this->SetError($ErrText);
		   $this->retval=false;
		   }elseif($this->IsEmpty($Value))  {$this->SetError($ErrText);$this->retval=false;} else $this->retval=true;
		   break;
	   
	   case "Double":
		   if(!$this->IsEmpty($Value) && $Params['Min'] && strlen(trim($Value))<$Params['Min'])
		   {
			$ErrText=" Required Minimum  ".$Params['Min']." Number"; 
			$this->SetError($ErrText);   
			$this->retval=false;
		   }elseif(!$this->IsEmpty($Value) && $Params['Max'] && strlen(trim($Value))>$Params['Max'])
		   {
			$ErrText=" Required Maximum  ".$Params['Max']." Number"; 
			$this->SetError($ErrText);
			$this->retval=false;  
		   }elseif(!$this->IsDouble($Value)){
		   $ErrText="Invalid Number";
		   $this->SetError($ErrText);
		   $this->retval=false;
		   }elseif($this->IsEmpty($Value))  {$this->SetError($ErrText);$this->retval=false;} else $this->retval=true;
		   break;
	   
	   
	   case "email":
		   if(!$this->IsEmail($Value))    {$this->SetError($ErrText);$this->retval=false;} else $this->retval=true;
		   break;
	   
	   case "empty":
			if($this->IsEmpty($Value))    {$this->SetError($ErrText);$this->retval=false;} else $this->retval=true;
		   break;

	   case "or":
			if($this->IsEmpty($Value) and $this->IsEmpty($revalue))    {$this->SetError($ErrText);$this->retval=false;} else $this->retval=true;
		   break;
	   
	   case "null":
		   if($this->IsNull($Value))     {$this->SetError($ErrText);$this->retval=false;} else $this->retval=true;
		   break;
	   
	   case "SCheck":
		   if(!$this->IsSCheck($Value))   {$this->SetError($ErrText);$this->retval=false;} else $this->retval=true;
		   break;
	   
	   case "MCheck":
		   if(!$this->IsMCheck($Value))   {$this->SetError($ErrText);$this->retval=false;} else $this->retval=true;
		   break;
		   
	   case "RCheck":
		   if(!isset($Value) || !$this->IsSCheck($Value))   {$this->SetError($ErrText);$this->retval=false;} else $this->retval=true;
		   break;
	   
	   case "Select":
		   if(!isset($Value) || $Value==$Params['Default'])   {$this->SetError($ErrText);$this->retval=false;} else $this->retval=true;
		   break;
	   
	   case "UserName":
	   		if(!$this->IsUserName($Value))    {$this->SetError($ErrText);$this->retval=false;} else $this->retval=true;
		   break;
		   
	   case "Password":
	  		 if(!$this->IsPassword($Value))    {$this->SetError($ErrText);$this->retval=false;} else $this->retval=true;
		   break;
		   
	   case "RePassword":
	  		 if(!$this->IsRePassword($Value,$revalue))    {$this->SetError($ErrText);$this->retval=false;} else $this->retval=true;
		   break;
	 
	   default:
	   $this->SetError($this->ErrString);
	   break;  
	   
	  }
	 return $this->retval;
	}
						
}
?>