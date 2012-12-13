<?php
$time = strtotime($original);
if( $time > 0 ){
    if( date('Y') == date('Y', $time)){
        $clean = date("m-d" , $time);
    } else {
        $clean = date("Y-m-d" , $time);
    }
} else {
    $clean = '';
}

?>
