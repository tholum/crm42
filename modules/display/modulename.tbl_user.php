<?php
$dataarr = $this->db->fetch_assoc($this->db->query( "SELECT * FROM tbl_user WHERE user_id = '$module_id'"));

$display_name = $dataarr["first_name"] . " " . $dataarr["last_name"];

//$display_name = "USER: $module_id";
 ?>
 
