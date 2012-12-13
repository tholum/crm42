<?php
require_once('class/global.config.php');
ob_start();
$rand=$_REQUEST["rand"];
$action=$_REQUEST["action"];
//define("TMP_UPLOAD","/home/apache/tmp/" );
switch( $action ){
    default:
    case "upload":
        if(!file_exists(TMP_UPLOAD."/".$rand)){
            mkdir(TMP_UPLOAD ."/". $rand );
        }
        if(!empty($_FILES)){
            $files = $_FILES;
            foreach( $files as $n => $v ){
            $tempFile = $_FILES[$n]['tmp_name'];                          // 1

            $targetPath = TMP_UPLOAD."/".$rand."/";  // 2
            $targetFile =  str_replace('//','/',$targetPath) . $_FILES[$n]['name']; // 3
            $filename = $_FILES[$n]['name'];
            if( move_uploaded_file($tempFile,$targetFile)){                       // 4
                echo true;
            }else{
                echo false;
            }
            }
        }
    break;
    case "remove":
        $filename = $_REQUEST["filename"];
        $full_file = TMP_UPLOAD."$rand/$filename";
        $result = unlink($full_file);
        echo $result;
        //delete(TMP_UPLOAD.$rand."/".$filename);
    break;
}
//file_put_contents("/home/apache/test.txt", "hello");
$html = ob_get_contents();
ob_end_clean();
if( $action=='upload' ){
   echo json_encode(array('filename' => $filename , 'debug' => '' ));
} else {
    echo $html;
}
?>