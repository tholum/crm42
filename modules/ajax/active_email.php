<?php
$time = strtotime("-10 Minutes");
$this->db->query("DELETE FROM eml_open WHERE timestamp < '" . date('Y-m-d H:i:s' , $time) . "'");
$result = $this->db->query('SELECT * FROM eml_open');
while( $row = $this->db->fetch_assoc($result)){
    $array[] = $row;
}

?>
