<?php
require('class/config.inc.php');
$user = new User();
$to = $_GET[to];
echo $user->GetUsersJson($_SESSION[user_name],$to);
?>
