<?php
$ca = explode( ":|:" , $original);
$module_name = $ca[0];
$module_id = $ca[1];
switch( strtolower( $module_name )){
    case "cases";
        $contact = $this->db->fetch_assoc( $this->db->query("SELECT contact_module_id , contact_module_name FROM cases WHERE case_id = '$module_id'"));
        if( strtolower( $contact['contact_module_name'] ) == "eapi_account"){
            $tmp_orig = $original;
            $original =  $contact['contact_module_id'];
            include 'modules/display/dataformat.eapi_account.php';
            
        } else {
            $clean = $this->module_displayname( $contact['contact_module_name'] , $contact['contact_module_id'] );
        }
    break;    
}
//$clean = $original;
?>
