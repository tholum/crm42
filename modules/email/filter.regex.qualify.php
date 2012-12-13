<?php
$pr = preg_match($info['info'], $string);
if( $pr != 0 ){
    $pass++;$passfail = "pass";
} else {
    $passfail = "fail";
}

?>
