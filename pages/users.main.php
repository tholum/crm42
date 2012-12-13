<?php
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
    require_once('class/class.cases.php');
    ob_end_clean(); 
    $user = new user;
    $user->Manage_users(); 
 ?>