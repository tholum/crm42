<?php

$result = $this->db->query( "SELECT * FROM tbl_user WHERE user_id = '$original'",__LINE__ , __FILE__ , array('show_error' => "false"));
if( mysql_num_rows($result) != 0 ){
    $dataarr = $this->db->fetch_assoc($result);
    $clean = $dataarr["first_name"] . " " . $dataarr["last_name"];
} else {
    $clean = "No Data";
}
?>
