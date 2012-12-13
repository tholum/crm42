<?php
if(!file_exists('../class/global.config.php')){
require_once '../class/database.inc.php';
$action = $_REQUEST['action'];
switch( $action ){
    case 'check_db':
        $db = new database(
                $_REQUEST['database_hostname'],
                $_REQUEST['database_port'],
                $_REQUEST['database_user'],
                $_REQUEST['database_password'],
                $_REQUEST['database_name'],
                true );
        echo json_encode( array( 'status' => 'success')) ;
    break;
    case 'install':
         $db = new database(
                $_REQUEST['database_hostname'],
                $_REQUEST['database_port'],
                $_REQUEST['database_user'],
                $_REQUEST['database_password'],
                $_REQUEST['database_name'],
                true );
        
        include 'database.php';
        include 'default_config.php';
        $sql_explode = explode(';' , $sql );
        foreach( $sql_explode as $sql_part){
            if( $sql_part != ''){
                $db->query($sql_part , __LINE__, __FILE__ , array('show_error' => false) );
            }
        }
        file_put_contents('../class/global.config.php', $default_config);
        echo json_encode( array( 'status' => 'success')) ;
    break;
    
}
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
