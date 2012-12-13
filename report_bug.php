<?php
//ini_set("display_errors" , 1 );
require_once('class/global.config.php');
require_once('class/database.inc.php');
require_once ('class/class.bugs.php');
$bugs = new Bugs();
?><div id="div_bugs"><?php
if($_REQUEST['submit']=='submit'){
	echo $bugs->bugTracking('server');
	}
else{
	echo $bugs->bugTracking('local','client');
}?>
</div>