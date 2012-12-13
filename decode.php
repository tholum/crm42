<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$URL = $_GET["URL"];
$URL = str_replace( "|" , "&" , $URL );
//echo $URL;
header("Location: $URL")
?>
