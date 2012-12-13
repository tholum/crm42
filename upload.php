<?php
//ini_set("display_errors",1);
/*?><script> alert('aaaaaaaa'); </script> <?php*/
require_once('app_code/config.inc.php');
require_once('app_code/class.Event.php');
require_once('class/class.contacts.php');
require_once('class/class.project.php');
$evt= new Event();
$document_name_pre= $_REQUEST['document_server_name'];
$document_name=$_FILES['myfile']['name'];
$document_status=$_POST['document_status'];
$document_size=$_FILES['myfile']['size'];
/*?><script> alert('<?php echo $document_size; ?>'+',flag=<?php echo $_REQUEST[flag]; ?>'); </script> <?php*/
$destination_path = "uploads/";
$result = 0;
$doc_id=$_REQUEST['doc_id'];
$user_id=$_REQUEST['user_id'];


function getRandomName($filename) {
	$file_array = explode(".",$filename);
	$file_ext = end($file_array);
	$new_file_name = uniqid().date('m').date('d').date('Y').date('G').date('i').date('s').".".$file_ext;
	return $new_file_name;
}
/*function errorMessage($error){
	switch($error){
		case 0: $message= "No error in Upload";
				break;
		case 1: $message= "The file is bigger than this PHP installation allows";
				break;
		case 2: $message= "The file is bigger than this form allows";
				break;
		case 3: $message= "Only part of the file was uploaded";
				break;
		case 4: $message= "No file was uploaded";
				break;
		
	}
	return $message;
	
}*/
if($document_size > 0){
	if($_REQUEST['flag']=='contact'){
		$contact_id = $_REQUEST['contact_id'];
		
		
		if($doc_id){
			if($document_name!='') {
				$target_path = $destination_path. basename( $document_name_pre);
				move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path);		
			}
			$evt->addContactDocuments('server',$contact_id,$doc_id,'','',$document_name_pre);		
			$error=$_FILES['myfile']['error'];
			$result=2;
			}	
		else {
			$doc_name = getRandomName($document_name);
			if($document_name!='') {
			$target_path = $destination_path. basename( $doc_name);
			/*if(getRandomName($document_name)){ ?><script> alert('<?php echo $target_path; ?>'+'aaaaaaaa'); </script> <?php }*/
			move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path);			
			$evt->addContactDocuments('server',$contact_id,'',$document_name,'', $doc_name,$user_id);
			$error=$_FILES['myfile']['error'];
			$result = 1;
			}	
			
		}
	}
	else if($_REQUEST['flag']=='project'){
		$project_id = $_REQUEST['project_id'];
		$project=new Project();
		if($doc_id){
			if($document_name!='') {
				$target_path = $destination_path. basename( $document_name_pre);
				move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path);		
			}
			$project->addDocuments('server',$project_id,$doc_id,'','');		
			$error=$_FILES['myfile']['error'];
			$result=2;
			}	
		else {
			$doc_name = getRandomName($document_name);
			if($document_name!='') {
			$target_path = $destination_path. basename( $doc_name);
			move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path);
			$project->addDocuments('server',$project_id,'',$document_name,$doc_name,$user_id);	
/*?><script> alert('<?php echo $document_size; ?>'+',flag=<?php echo $_REQUEST[flag]; ?>'); </script> <?php
*/			$error=$_FILES['myfile']['error'];
			$result = 1;
			}	
			
		}
	}
	else {
		$event_id = $_REQUEST['event_id'];
		if($doc_id){
			if($document_name!='') {
				$target_path = $destination_path. basename($document_name_pre);
				move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path);		
			}
			$evt->addDocuments('server',$event_id,$doc_id,'',$document_status,'');		
			$error=$_FILES['myfile']['error'];
			$result=2;
			}	
		else {
			$doc_name = getRandomName($document_name);
			if($document_name!=''&& $document_status!='') {
			$target_path = $destination_path. basename( $doc_name);
			move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path);
			$evt->addDocuments('server',$event_id,'',$document_name,$document_status,$doc_name);	
			$error=$_FILES['myfile']['error'];
			$result = 1;
			}	
			
		}	
	}
}
else if($document_name!='' && $document_size == 0) $result = 3;
sleep(1);
//$error= errorMessage($error);
?>
<script language="javascript" type="text/javascript">window.top.window.stopUpload('<?php echo $result; ?>');</script>   