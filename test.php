<?php
//error_reporting(E_ERROR );
ini_set('display_errors' , 1 );
define('ALT_AUTH' , 'active_directory');
////echo __LINE__;
////echo "<br/><br/>" . get_include_path ();
////echo "<br/><br/>" ;
////ob_start();
require_once('class/global.config.php');
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
require_once('class/class.user.php');
//ob_end_clean();
require_once('class/class.email_client.php');
require_once('class/class.display.php');
require_once('class/class.knowledgebase.php');
$kb = new knowledge_base();
echo $kb->phone_cid_lookup('6087838324');

//$casecreation = new case_creation();
//echo $casecreation->right_bottom_panel('2857');
echo "YES";
//$mid = $_REQUEST['mid'];
//$page = new basic_page();
//$emaildash = new email_client();
//$defaults = array("archive"=>"1");
//$defaults['pagation'] = true;
//$defaults['page'] = '1';
//$defaults['limit'] = '10';
//$defaults['archive'] = '';
//$t1 = strtotime('NOW') + microtime();
// $emaildash->display_email_by_module('' , '' , $defaults );
// $t2 = strtotime('NOW') + microtime();
// echo "Total Time Ran:" . ($t2 - $t1) . "<br/>";
// echo $total_page_querys;
//echo '<table>';
//$qt = 0;
////var_dump($querys_ran);
// foreach($querys_ran as $v){
//     echo '<tr><td>' . $v['time'] . '</td><td>' . $v['sql'] . '</td></tr>';
//     $qt = $qt + $v['time'];
// }
//echo '</table>';
//echo $qt;
//echo $page->module_displayname( 'eapi_ACCOUNT' , '129974' );
//$eapi_account = new eapi_account();
//$eapi_account->cache_account( '129974' );
////echo $page->decode_text( 'PLAIN' , $ba["body"] );
?>