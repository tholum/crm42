<?php

/*knowledgebase_search
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
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
require_once('class/class.knowledgebase.php');
ob_end_clean();
$kb = new knowledge_base();
echo json_encode($kb->knowledgebase_search($query));
?>
