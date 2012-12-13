<?php 
$con= mysql_connect("localhost","3306","root","bsZwnboF756Znk3csf7w");
if(!$con){
   die('could not connect' . mysql_error());
}		
mysql_select_db("erp_workorder" , $con);
?>