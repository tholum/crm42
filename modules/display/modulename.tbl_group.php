<?php
$dataarr = $this->db->fetch_assoc($this->db->query( "SELECT * FROM `tbl_usergroup` WHERE group_id = '$module_id'"));

$display_name = $dataarr["group_name"];
?>
