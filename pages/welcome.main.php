<?php
require_once('app_code/config.inc.php');
require_once 'class/class.tasks.php';
require_once('class/class.message.php');
require_once('class/class.news.php');
require_once 'class/class.contacts.php';
require_once 'class/class.bugs.php';
require_once 'Zend/Loader.php';
require_once('class/class.CapacityReport.php');
require_once('class/class.WorkOrder.php');
require_once('class/class.welcome.php');
$welcome = new welcome();
$welcome_message = $welcome->get_news();
?>
<div style="position: relative;left: 35px; display: inline-block" >
    <?php echo base64_decode( $welcome_message['news'] ); ?>
</div>
<div style="padding: 10px;padding-right: 35px;position: absolute;right: 0px;top: 2px;display:inline-block;width: 250px;min-width: 250px;background: #A7CDF0" >

Quick Search: <input class="welcome_quicksearch" onfocus="$(this).select()" />
</div>