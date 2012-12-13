<?php
$ca = explode( ":|:" , $original);
$module_name = $ca[0];
$module_id = $ca[1];
switch( strtolower( $module_name )){
    case "cases";
        $contact = $this->db->fetch_assoc( $this->db->query("SELECT contact_module_id , contact_module_name FROM cases WHERE case_id = '$module_id'"));
        $clean = $contact['contact_module_id'];
    break;    
}
//$clean = $original;
?>