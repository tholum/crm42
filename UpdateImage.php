<?php
require_once('class/config.inc.php');

$user = new User();
print_r($_FILES);
if(isset($_POST[filename])){
	$user->UpdatePhoto($_POST,$_FILES);
}
echo 'hi';
?>
