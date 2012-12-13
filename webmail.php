<?php
date_default_timezone_set("America/Chicago");
//ini_set("display_errors",1);
require('class/config.inc.php');

require 'class/class.tasks.php';

require_once 'class/class.roundcube.php';

//ini_set("display_errors",1);
$page = new basic_page();

$page->auth->Checklogin();

$ajax = new PHPLiveX();

$user = new User();


$zimbra = new zimbra;
$url = $zimbra-> zimbra_auth( $page->auth->Get_user_id() );
//$url = "/zimbra/?loginOp=login&username=" . $emailauth["user"] . "@" . MAIL_DEFAULT_DOMAIN . "&password=" . $emailauth["pass"] . "&client=preferred&skin=fields";
?>
<script type="text/javascript">
window.location='<?php echo $url; ?>';
</script>
