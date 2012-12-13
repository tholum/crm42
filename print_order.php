<?php
$order_id = $_REQUEST['order_id'];
//error_reporting(E_ERROR );
//ini_set('display_errors' , 1 );
//echo __LINE__;
//echo "<br/><br/>" . get_include_path ();
//echo "<br/><br/>" ;
//ob_start();
require_once('app_code/global.config.php');
require_once('class/class.dynamicpage.php');
require_once('class/config.inc.php');
//echo __LINE__;
//echo __LINE__ . "\n";
require_once('class/class.email_client.php');
//echo __LINE__ . "\n";
require_once('class/class.flags.php');
//echo __LINE__ . "\n";
require_once('class/class.GlobalTask.php');

//echo __LINE__ . "\n";
require_once('class/class.smtp.php');
//echo __LINE__ . "\n";

//echo __LINE__ . "\n";
require_once('class/class.casecreation.php');
//echo __LINE__ . "\n";

//echo __LINE__ . "\n";
require_once('class/class.FctSearchScreen.php');

//echo __LINE__ . "\n";
require_once('class/class.eapi_order.php');
require_once('class/class.eapi_account.php');
require_once('class/class.cases.php');
require_once('class/class.note.php');
require_once('class/class.imap.php');
//ob_end_clean();
require_once('class/class.display.php');
$eapi_api = new eapi_api;
$order_json = $eapi_api->order_detail_lookup($order_id);
$order = json_decode($order_json);
?>
<head>
    
</head>
<body onload="window.print();">
    <?php echo $order->OrderForm;?>
</body>