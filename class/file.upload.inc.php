<?php
	class FileUpload {
		// Upload mode (Add / Edit)
		public $UploadMode = "Add";
		// If upload mode is Edit
		public $OldFileName;
		// Upload contain variable
		// Example: $_FILES['html_control_name'];
		public $UploadContent;
		// Folder name where the file will be uploaded
		public $UploadFolder;
		// Save the file by using random name or not
		public $IsSaveByRandomName = true;
		// Need return statement
		public $NeedReturnStatement = true;		
		// Upload function		
		
		public function getRandomName($filename) {
			$file_array = explode(".",$filename);
			$file_ext = end($file_array);
			$new_file_name = uniqid().date('m').date('d').date('Y').date('G').date('i').date('s').".".$file_ext;
			return $new_file_name;
		}
		
		public function CreateFolder($Path,$Permission) {
			if(!is_dir($Path) == true) {
				@mkdir($Path,$Permission) or $this->error($php_errormsg);
				@chmod($Path,$Permission) or $this->error($php_errormsg);
			}
		}
		
		public function Upload() {
			$filename = $this->UploadContent['name'];
			if($this->IsSaveByRandomName == true) {
				$filename = $this->getRandomName($filename);	
			}
			if(is_dir($this->UploadFolder)==false) {
				$this->CreateFolder($this->UploadFolder,0777);
			}
			if($this->UploadMode == "Edit") {
				$OldFilePath = $this->UploadFolder."/".$this->OldFileName;
				if(file_exists($OldFilePath)==true) {
					@unlink($OldFilePath);
				}
			}
			$filepath = $this->UploadFolder."/".$filename;
			move_uploaded_file($this->UploadContent['tmp_name'],$filepath);
			chmod($filepath,0777);
			if($this->NeedReturnStatement == true) {
				return $this->ReturnStatement($filename);
			}
			
		}
		
		public function error($err) {
		
		
		}
		
		public function ReturnStatement($FileName) {
		    return $this->UploadContent['name']."|".$FileName."|".$this->UploadContent['type']."|".$this->UploadContent['size'];
		}
		
		public function CheckFileType($fileobject, $filetyp='image/jpg|image/gif|image/jpeg|images/png') 
		{
		    
			$filetype = array();
			$filetype = explode('|', $filetyp);
			$sscount = count($filetype);
			//$imgtype = explode('.', $fileobject);
			//echo $sscount; //to test the variable
			if($sscount>0){
				for($i = 1; $i <= $sscount; $i++){
					if ( strcasecmp($fileobject,$filetype[$i])==0 ) 
					{
						$type = 'true';
						break;
					}
					else
					{
					    $type = 'false';
					}

				}
			return ($type);
			}
	}
	
	public function uploadImage($fileName, $maxSize, $maxW, $fullPath, $relPath, $colorR, $colorG, $colorB, $maxH = null){
		$folder = $relPath;
		$maxlimit = $maxSize;
		$allowed_ext = "jpg,jpeg,gif,png,bmp";
		$match = "";
		$filesize = $_FILES[$fileName]['size'];
		if($filesize > 0){	
			$filename = strtolower($_FILES[$fileName]['name']);
			$filename = preg_replace('/\s/', '_', $filename);
		   	if($filesize < 1){ 
				$errorList[] = "File size is empty.";
			}
			if($filesize > $maxlimit){ 
				$errorList[] = "File size is too big.";
			}
			if(count($errorList)<1){
				$file_ext = preg_split("/\./",$filename);
				$allowed_ext = preg_split("/\,/",$allowed_ext);
				foreach($allowed_ext as $ext){
					if($ext==end($file_ext)){
						$match = "1"; // File is allowed
						$NUM = time();
						$front_name = substr($file_ext[0], 0, 15);
						$newfilename = $front_name."_".$NUM.".".end($file_ext);
						$filetype = end($file_ext);
						$save = $folder.$newfilename;
						if(!file_exists($save)){
							list($width_orig, $height_orig) = getimagesize($_FILES[$fileName]['tmp_name']);
							if($maxH == null){
								if($width_orig < $maxW){
									$fwidth = $width_orig;
								}else{
									$fwidth = $maxW;
								}
								$ratio_orig = $width_orig/$height_orig;
								$fheight = $fwidth/$ratio_orig;
								
								$blank_height = $fheight;
								$top_offset = 0;
									
							}else{
								if($width_orig <= $maxW && $height_orig <= $maxH){
									$fheight = $height_orig;
									$fwidth = $width_orig;
								}else{
									if($width_orig > $maxW){
										$ratio = ($width_orig / $maxW);
										$fwidth = $maxW;
										$fheight = ($height_orig / $ratio);
										if($fheight > $maxH){
											$ratio = ($fheight / $maxH);
											$fheight = $maxH;
											$fwidth = ($fwidth / $ratio);
										}
									}
									if($height_orig > $maxH){
										$ratio = ($height_orig / $maxH);
										$fheight = $maxH;
										$fwidth = ($width_orig / $ratio);
										if($fwidth > $maxW){
											$ratio = ($fwidth / $maxW);
											$fwidth = $maxW;
											$fheight = ($fheight / $ratio);
										}
									}
								}
								if($fheight == 0 || $fwidth == 0 || $height_orig == 0 || $width_orig == 0){
									die("FATAL ERROR REPORT ERROR CODE [add-pic-line-67-orig] to <a href='http://www.atwebresults.com'>AT WEB RESULTS</a>");
								}
								if($fheight < 45){
									$blank_height = 45;
									$top_offset = round(($blank_height - $fheight)/2);
								}else{
									$blank_height = $fheight;
								}
							}
							$image_p = imagecreatetruecolor($fwidth, $blank_height);
							$white = imagecolorallocate($image_p, $colorR, $colorG, $colorB);
							imagefill($image_p, 0, 0, $white);
							switch($filetype){
								case "gif":
									$image = @imagecreatefromgif($_FILES[$fileName]['tmp_name']);
								break;
								case "jpg":
									$image = @imagecreatefromjpeg($_FILES[$fileName]['tmp_name']);
								break;
								case "jpeg":
									$image = @imagecreatefromjpeg($_FILES[$fileName]['tmp_name']);
								break;
								case "png":
									$image = @imagecreatefrompng($_FILES[$fileName]['tmp_name']);
								break;
							}
							@imagecopyresampled($image_p, $image, 0, $top_offset, 0, 0, $fwidth, $fheight, $width_orig, $height_orig);
							switch($filetype){
								case "gif":
									if(!@imagegif($image_p, $save)){
										$errorList[]= "PERMISSION DENIED [GIF]";
									}
								break;
								case "jpg":
									if(!@imagejpeg($image_p, $save, 100)){
										$errorList[]= "PERMISSION DENIED [JPG]";
									}
								break;
								case "jpeg":
									if(!@imagejpeg($image_p, $save, 100)){
										$errorList[]= "PERMISSION DENIED [JPEG]";
									}
								break;
								case "png":
									if(!@imagepng($image_p, $save, 0)){
										$errorList[]= "PERMISSION DENIED [PNG]";
									}
								break;
							}
							@imagedestroy($filename);
						}else{
							$errorList[]= "CANNOT MAKE IMAGE IT ALREADY EXISTS";
						}	
					}
				}		
			}
		}else{
			$errorList[]= "NO FILE SELECTED";
		}
		if(!$match){
		   	$errorList[]= "File type isn't allowed: $filename";
		}
		if(sizeof($errorList) == 0){
			//return $newfilename;
			return $fullPath.$newfilename;
		}else{
			$eMessage = array();
			for ($x=0; $x<sizeof($errorList); $x++){
				$eMessage[] = $errorList[$x];
			}
		   	return $eMessage;
		}
	}
}
?>