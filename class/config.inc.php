<?php
	@session_start();
	
	//**************** include classes *************************
	require_once("global.config.php");
	$theme = $_GET["theme"];
	if( $theme == '' ){
		require_once("theme.config.php");
	} else {
		require_once("theme.config.$theme.php");
	}
	require_once('database.inc.php');
	require_once("class.Authentication.php");
	require_once("class.user.php");	
	require_once('class.tags.php');
	require_once("ClsJSFormValidation.cls.php");
	require_once("ClsJSFormValidation.cls.alert.php");
	require_once("class.FormValidation.php");
	require_once("PHPLiveX.php");
	require_once("class.display.php");	
	require_once('file.upload.inc.php');
	require_once('class.element_permission.php');
	require_once('class.Notification.php');
	require_once('class.phpmailer.php');
	require_once("csv.lib.php");
	require_once('Contact_Vcard_Parse.php');
    require_once('class.vcard.php');

	class APP_SETTINGS {
	var $logo;
	
		function update_logo($runat) {
		
			switch($runat) {
			
			case 'local' :
			
				//give user option to upaldo new logo
				?>
				<form action="" enctype="multipart/form-data" method="post" name="upload">
				<tr>
				<th>logo.png</th><td>
				<input type="file" name="logo" id="logo" /></td>
				</tr>
				<tr>
				<td><input type="submit" name="submit" value="Submit" /></td>
				</tr>
				</form>
				<?php
				break;
			
			case 'server' :
				//update logo image in /images/
				
				$_FILES["logo"]["size"];
				$_FILES["logo"]["type"];
				
				if(move_uploaded_file($_FILES["logo"]["tmp_name"],"images/logo.png" ))
				{
					echo "file is uploaded";
				}
				else
				{
					echo "fail to upload";
				}
				?>
				<script type="text/javascript" language="javascript">
				window.location="<?php $_SERVER['PHP_SELF']; ?>"
				</script>
				<?php
			break;
			}
		}
	}

?>
