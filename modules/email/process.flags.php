<?php
$insert = array();
$insert['module_name'] = 'EMAIL';
$insert['module_id'] = $message_data['mid'];
$insert['flag_type_id'] = $data['flag_type_id'];
$insert['owner_module_name'] = 'TBL_USER';
$insert['owner_module_id'] = '*';
$result = $this->db->query("SELECT * FROM flags WHERE module_id = '" . $message_data['mid'] . "' AND module_name ='EMAIL' AND flag_type_id = '" . $data['flag_type_id']. "'");
if(mysql_num_rows($result) == 0 ){
    $this->db->insert('flags' , $insert);
}

print_r( $insert );
//print_r($data);
?>
