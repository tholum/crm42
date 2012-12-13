<?php
$return[] = $module_id;
if( $full == 1 ){
    $carr = $this->db->fetch_assoc($this->db->query("SELECT * FROM contacts WHERE company = '$module_id'"));
    foreach( $carr as $v){
        $return[] = $v["contact_id"];
    }
}
?>
