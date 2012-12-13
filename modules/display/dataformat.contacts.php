<?php
$display_name = $this->module_displayname( "CONTACTS" , $original );
//if( $format_options['return_csv'] != true && $format_options['return_csv'] != 1 ){
//    $clean = '<a onclick="' . $this->page_link('accountdashboard' , array('account_name' => $display_name , 'account_id' => $original ) ) . '" >' . $display_name . '</a>';
//} else {
//    $clean = $display_name;
//}
$clean = $display_name;
if($original == 0 ){
    $clean = "";
}
