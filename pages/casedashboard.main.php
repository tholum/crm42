<?php
require_once('app_code/global.config.php');
require_once('class/config.inc.php');

require_once('app_code/global.config.php');
require_once('class/config.inc.php');
require_once('class/class.email_client.php');
require_once('class/class.flags.php');
require_once('class/class.GlobalTask.php');
require_once('class/class.smtp.php');
require_once('class/class.display.php');
require_once('class/class.casecreation.php');

require_once('class/class.cases.php');
$case = new cases;
?>


<?php
echo $case->display_search_bar();
$object = array();
$object['Status'] = 'Active';
?>

    
<br/>
<div style="width: 100%;" id="search_result" ><?php echo $case->display_search( $object , array('user_id' => $_SESSION["user_id"]) );?></div>
