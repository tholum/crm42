<?php
//$eapi_account = new eapi_account;
$result = $this->db->query("SELECT display_name FROM eapi_account_displayname WHERE account_id = '$module_id'",__LINE__ , __FILE__ , array('show_error' => "false"));
if( mysql_num_rows( $result ) == 0 ){
    /*$account = json_decode($this->eapi_account->get_account( $module_id ) );
    $display_name = $account->Studio;
    $insert = array();
    $insert["account_id"] = $module_id;
    $insert["display_name"] = $display_name;
    $this->db->insert('eapi_account_displayname' , $insert );*/
    $rand = rand( 0 , 10000000000);
    if( $format_options['return_csv'] != true && $format_options['return_csv'] != 1 ){
    $display_name = "<div id='eapi_cache_$rand' class='eapi_cache_$rand' ><a onclick='eapi_account.cache_account(\"$module_id\" , { target: \"eapi_cache_$rand\" });'>Not Cached</a></div>";
    } else {
        $display_name = '';
    }
    
} else {
    $array = $this->db->fetch_assoc($result);
    $display_name = $array["display_name"];
}
?>
