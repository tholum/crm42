<?php
$qtemp=$_REQUEST["term"];
$qta = explode("," , $qtemp);
// This should allow it so if you type tholum@couleetechlink.com,rho it only looks for rho
// Which allows us to have muliple addresses
$query='';
$pre='';
foreach($qta as $q ){
    $pre.=$query;
    if( $query != '' ){
      $pre.= ","; 
    }
    $query = $q;
}
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
ob_end_clean();
//CTLTODO Put this in contacts class
$db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
$qa = explode(' ' , $query );
$wa = array();
foreach( $qa as $v ){
    $wa[] = "( b.first_name LIKE '%$v%' OR b.last_name LIKE '%$v%' OR a.email LIKE '%$v%' )";
}
$where = implode(" AND " , $wa);
$result = $db->query('SELECT a.email , b.first_name , b.last_name FROM `contacts_email` a LEFT JOIN `contacts` b ON a.contact_id = b.contact_id WHERE ' . $where );
$array = array();
while( $row=$db->fetch_assoc($result)){
    $row['label'] = $row['first_name'] . " " . $row['last_name'];
    $row['value'] = $pre.$row['email'];
    $array[] = $row;
}
echo json_encode($array);
?>
