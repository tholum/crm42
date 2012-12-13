<?php
require_once('class/global.config.php');
require_once('class/database.inc.php');
require_once('class/class.fileserver.php');
$fileserver = new fileserver;
$fileserver->check_fileUpload();
?>
<head>
    <script type="text/javascript" src="js/jquery.min.js"></script>
</head>
<form name="myform" action="tmp.fileserver.php" method="post" enctype="multipart/form-data"  >
    <input id="fileUplaod" type="file" size="45" name="fileUpload" class="input" ></input>
    <input id="something" type="text" name="something" value="Yup" ></input>
    <input type="submit" ></input>    
</form>
<?php
echo $fileserver->display_files('1','tmp' );
?>
<textarea style="width:100%;height: 80%" >
<?php

print_r( $_FILES );
echo "\n\n";
print_r( $_REQUEST);
echo "\n\n";
print_r( $_POST);
if( is_array($_FILES["fileUpload"])){
    echo "YES\n";
    $fileserver->upload_file('1', 'tmp', '1', $_FILES["fileUpload"]);
}
?>

</textarea>