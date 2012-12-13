<?php
$query=$_REQUEST["term"];
ob_start();
require_once('app_code/global.config.php');

require_once('class/config.inc.php');
//echo __LINE__ . "\n";
require_once('class/class.email_client.php');
//echo __LINE__ . "\n";
require_once('class/class.flags.php');
//echo __LINE__ . "\n";
require_once('class/class.GlobalTask.php');

//echo __LINE__ . "\n";
require_once('class/class.smtp.php');
//echo __LINE__ . "\n";
require_once('class/class.display.php');
//echo __LINE__ . "\n";
require_once('class/class.casecreation.php');
//echo __LINE__ . "\n";
require_once('class/class.dynamicpage.php');
//echo __LINE__ . "\n";
require_once('class/class.FctSearchScreen.php');

//echo __LINE__ . "\n";
require_once('class/class.eapi_order.php');
require_once('class/class.eapi_account.php');
require_once('class/class.cases.php');
require_once('class/class.note.php');

$emaildash = new email_client();
$query=$_REQUEST["term"];
$templates = $emaildash->get_templates('TBL_USER', $_SESSION['user_id'] , 'ALL' , 'ALL',array('query' => $query ));
foreach( $templates as $n => $v ){
    $templates[$n]['value'] = $templates[$n]['title'];
    $templates[$n]['name'] = $templates[$n]['title'];
}
ob_end_clean();
echo json_encode($templates);
?>