<?php
	class career_class{
		function career_display($runat){
		switch($runat){
		case'local':
		?>
			
		<form method="post"  enctype="multipart/form-data" name="frm1"onSubmit="return checkform();">
		
			<table>
				<tr>
					
					<td colspan="3" align="center"><h2>Career Form</h2></td>
				</tr>
				<tr>
					<th>Name</th>
					<td>:</td>
					<td><input  type="text" name="username" id="username"/></td>
				</tr>
				<tr>
					<th>Father's Name</th>
					<td>:</td>
					<td><input  type="text" name="fname" id="fname"/></td>
				</tr>
				
				
				<tr>
					<th>Resume</th>
					<td>:</td>
					<td><input  type="file" name="att" id="att"/> *Should Be in word Format.Along with Recent Photogragh</td>
				</tr>
				<tr>
					<td  colspan="2"><input  type="submit" name="sub" id="sub"  value="Submit"/></td>
					<td><input  type="reset" name="rest" id="rest"  value="Reset"/></td>
				</tr>
			</table>
		</form>
		<?php
		break;
		case'server':
		extract($_POST);
		
		$ext = end(explode(".",$att));
		if($ext=='docx' || $ext=='doc'){
		$to = 'hr@mrbrownbakery.com';
		$sub = 'Subject';
		$from = 'Resume';
		//echo $from;
		$msg = 'Resume';
		//move_uploaded_file($_FILES['f1']['name'],'upload/'.$_FILES['f1']['tmp_name']);
		mail($to,$sub,$msg,$from);
		?>
		<script>
		alert("Mail Succussfully Sent");
		window.location = 'career_frm.php';
		</script>
		<?php
		}
		else{
			?>
			<script>
			alert("Invalid file format");
			window.location = 'career_frm.php';
			</script>
			<?php
		}
		
		break;
		case 'test':
		extract($_POST);
		$msg .= "Name:".$username."<br>";
		$msg .='Father Name:'.$fname.'<br>';
		$msg .='Date Of Birth:'.$dob.'<br>';
		$msg .='Area Of Interest:'.$interest.'<br>';

		$name_of_uploaded_file = basename($_FILES['att']['name']);
		$type_of_uploaded_file =substr($name_of_uploaded_file,strrpos($name_of_uploaded_file, '.') + 1);
		$size_of_uploaded_file =$_FILES["uploaded_file"]["size"]/1024;//size in KBs
		$max_allowed_file_size = 1000; // size in KB
		/*$allowed_extensions = array("docx", "doc");
		for($i=0; $i<sizeof($allowed_extensions); $i++){
 			 if(strcasecmp($allowed_extensions[$i],$type_of_uploaded_file) == 0){
   					 $allowed_ext = true;
 				 }
			}*/
		$allowed_ext = true;
		if($allowed_ext){
		//$errors .= "\n The uploaded file is not supported file type. "." Only the following file types are supported: ".implode(',',$allowed_extensions);
		
		$upload_folder = 'upload_folder/';
		$path_of_uploaded_file = $upload_folder . $name_of_uploaded_file;
		$tmp_path = $_FILES["att"]["tmp_name"];
		if(is_uploaded_file($tmp_path)){
			if(!copy($tmp_path,$path_of_uploaded_file)){
			$errors .= '\n error while copying the uploaded file';
			}
		}
		$email = new activeMailLib("html");
		$email->enableAddressValidation();
		$email->From("webmaster@yourhost.com");//set a valid E-mail
		$email->To("manish.gupta@twamail.com");//set a valid E-mail
		$email->Subject("Resume");
		$email->Message($msg);
		$email->Attachment($path_of_uploaded_file,$name_of_uploaded_file);
		$email->Send();
		?>
		<script>
		alert("Mail Succussfully Sent");
		window.location = 'career_frm.php';
		</script>
		<?php
		}
		else{
			?>
			<script>
			alert("Invalid file format");
			window.location = 'career_frm.php';
			</script>
			<?php
		}
		break;
		}
		}
	}


?>