<?php
session_start();
require_once("activeMailLib.php");
?>
<link href="calendar/calendar/calendar.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="calendar/calendar/calendar.js"></script>
<style type="text/css">
body { font-size: 11px; font-family: "verdana"; }
pre { font-family: "verdana"; font-size: 10px; background-color: #FFFFCC; padding: 5px 5px 5px 5px; }
pre .comment { color: #008000; }
pre .builtin { color:#FF0000;  }
</style>
<?php
require_once('class.career.php');
$career = new career_class;
if($_POST[sub]=='Submit'){
echo $career ->career_display('test');
}
else {
echo $career ->career_display('local');
}

?>