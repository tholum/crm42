<?php
require('../class/config.inc.php');
require_once('../class/class.contacts.php');
$contact = new Company_Global();
echo $contact->GetContactsJson($_REQUEST[keyword]);
?>
