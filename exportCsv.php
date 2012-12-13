<?php
@session_start();
if($_REQUEST[download]==true and $_SESSION[csv]!='') {
		

		header("Content-Disposition: attachment; filename=employer.csv");
		header("Content-Length: ".strlen($_SESSION[csv] ));
		header("Connection: close");
		header("Content-Type: text/x-csv;");
		echo $_SESSION[csv] ;
		$_SESSION[csv] = '';
		exit();
}



	
require('class/config.inc.php');

require 'class/class.contacts.php';

$page = new basic_page();

$page->auth->Checklogin();

$contact=new Company_Global();

$contact->ExportCsv('server');
?>
<script type="text/javascript">
window.location='exportCsv.php?download=ture';
</script>
