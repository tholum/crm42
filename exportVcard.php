<?php
@session_start();
if($_REQUEST[download]==true and $_SESSION[vcard]!='') {
		
		$name=$_SESSION[filename];
		Header("Content-Disposition: attachment; filename=$name");
		Header("Content-Length: ".strlen($_SESSION[vcard] ));
		Header("Connection: close");
		Header("Content-Type: text/x-vCard; name=$name");
		echo $_SESSION[vcard] ;
		$_SESSION[vcard] = '';
		$_SESSION[vcardname] = '';
		exit();
}



	
require('class/config.inc.php');

require 'class/class.contacts.php';

$page = new basic_page();

$page->auth->Checklogin();

$contact=new Company_Global();

$contact_id=$_REQUEST[contact_id];
$contact->ExportVcard($contact_id);
?>
<script type="text/javascript">
window.location='exportVcard.php?download=ture';
</script>
