<?php
//error_reporting(E_ERROR );
//ini_set('display_errors' , 1 );
ob_start();
require_once('app_code/global.config.php');
require_once('class/config.inc.php');
require_once('class/class.email_client.php');
require_once('class/class.flags.php');
require_once('class/class.GlobalTask.php');
require_once('class/class.smtp.php');
require_once('class/class.display.php');
require_once('class/class.casecreation.php');
require_once('class/class.dynamicpage.php');
require_once('class/class.FctSearchScreen.php');
require_once('class/class.eapi_order.php');
require_once('class/class.eapi_account.php');
require_once('class/class.cases.php');
require_once('class/class.note.php');
require_once('class/class.knowledgebase.php');
require_once('class/class.FctSearchScreen.php');
ob_end_clean();
header("Content-type: text/csv");  
header("Cache-Control: no-store, no-cache");  
header('Content-Disposition: attachment; filename="filename.csv"');  
$page = $_REQUEST['page'];
$arr = array();
$options = array();
$op = $_REQUEST['options'];
foreach( $_REQUEST as $n => $v){
    if( strtolower(substr($n, 0 , 7) ) == 'search_' ){
        $options[ substr($n , 7 )] = $v;
    }
}
switch( $page ){
    case "bucket_search":
        $fct = new FctSearchScreen();
        $arr = $fct->display_flowchart_task2( $options , array( 'return_csv' => true , 'user_id' => $_SESSION['user_id'] ) );
    break;
    case "case_search":
        $case = new cases;
        $arr = $case->display_search( $options , array('return_csv' => true ,'user_id' => $_SESSION["user_id"]) );
    break;
   
}
foreach( $arr['body'] as $row ){
    $clean_row = array();
    foreach( $row as $name => $col ){
        $clean_row[$name] = $col;
    }
    echo '"' . trim( str_replace( "\n" , '' , implode('","', $clean_row) . '"' ) ) . "\n";
}

?>