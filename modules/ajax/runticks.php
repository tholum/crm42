<?php
$time = strtotime("-10 Minutes");
$this->db->query("DELETE FROM currentcalls WHERE timestamp < '" . date('Y-m-d H:i:s' , $time) . "'");
$result = $this->db->query("SELECT * FROM currentcalls WHERE ( ip_address = '" . $_SERVER['REMOTE_ADDR'] . "' OR user_id = '" . $_SESSION['user_id']. "' )");
while( $row=$this->db->fetch_assoc($result)){
    $array[] = array('javascript' => "slimcrm.tick.phone.check_phone('" . $row['phone_number'] . "','" . $row['currentcalls_id'] . "');");
}

?>
