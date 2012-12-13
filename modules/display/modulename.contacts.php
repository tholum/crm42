<?php
//$eapi_account = new eapi_account;
$result = $this->db->query("SELECT company_name FROM contacts WHERE contact_id = '$module_id'",__LINE__ , __FILE__ , array('show_error' => "false"));
if( mysql_num_rows( $result ) != 0 ){
    $array = $this->db->fetch_assoc($result);
    $display_name = '<a onclick="' .  $this->page_link('contacts' , array( 'contact_id' => $module_id ) ) . '" >' . $array["company_name"] . '</a>';
} else {
    $display_name = "No Contact Found";
}
?>