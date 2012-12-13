<?php
ob_start();
date_default_timezone_set('America/Chicago');
require_once('class/global.config.php');
require_once('class/database.inc.php');
$db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
$term = $_REQUEST['term'];
ob_end_clean();
$wa = array();
$term_arr = explode( " " , $term );
foreach( $term_arr as $t ){
    $wa[] = "(a.global_task_id LIKE '%$t%' OR a.name LIKE '%$t%' OR a.module  LIKE '%$t%' OR b.global_task_tree_name LIKE '%$t%' )";
}
$where = implode(" AND " , $wa );
if( count($wa) != 0){
    $where = "WHERE $where";
}
$query = "SELECT a.global_task_id , a.name , a.module ,  b.global_task_tree_name FROM `tbl_global_task` a LEFT JOIN tbl_global_task_tree  b ON a.global_task_tree_id = b.global_task_tree_id $where";
$result = $db->query($query);
$a = array();
while( $row = $db->fetch_assoc($result)){
    $row['value'] = $row['global_task_id'];
    $row['label'] = $row['global_task_id'] . ": " . $row['name'] . " - " . $row['global_task_tree_name'] . "(" . $row['module'] . ")";
    $a[] = $row;
}
echo json_encode($a);

?>