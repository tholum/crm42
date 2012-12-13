<?php
ini_set("display_errors" , 0 );
error_reporting();
require_once('class/class.imap.php');
$imap = new imap;
$imap->check_all_mailboxes();
$imap->read_email("21");
?>
