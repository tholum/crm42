<?php
//ini_set('display_errors',1);
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
ob_end_clean();
$auth = false;

$page = new basic_page();
$key = $page->get_global_setting('api_key', '9Y1Wesd9mq3Sw6LDMFUnB4');
$req_key = $_REQUEST['api_key'];
//CTLTODO Make other forms of authentication,
if( $key == $req_key){
    $auth = true;
}


if( $auth == true ){
$action = $_REQUEST["action"];
$format = $_REQUEST["format"];
$options = $_REQUEST;
$array = $page->get_api($action,$options);
} else {
    $array = array('authorization failed');
}
switch( $type ){
    default:
    case "json":
        echo json_encode($array);
    break;
}
?>