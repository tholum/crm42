<?php 
//ini_set('display_errors',1);
if($_REQUEST[phone]) {
require_once('app_code/config.inc.php');
require_once('class/class.contacts.php');
require_once 'class/class.tasks.php';
require_once('class/class.project.php');
require_once('class/class.asterisk.php');


$ajax = new PHPLiveX();

$page = new basic_page();

$user = new User();

$task = new Tasks();

$task -> SetUserObject($user);

$task -> SetUserID($page->auth->Get_user_id());

$ajax->AjaxifyObjects(array("task"));  

$project= new Project();

$asterisk = new Asterisk();
?>
<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.8.6.custom.min.js" type="text/javascript"></script>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.6.custom.css" rel="stylesheet" />

<style>
/*	body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 50px;}
	.demoHeaders { margin-top: 2em; }*/
	.newcall h1 {font-size:14px; }
	.newcall {
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px; 
	}
	.newcall table {
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px; 
	}
	.newcall table h2{
		display:none;
	}
	.dis{
	 display:none;
	}
	.call_log td{color:#FFF; }
	.newcall #phone_no {
	display:none;
	}
</style>
	<script type="text/javascript" language="javascript">
	$(function(){
		$("#tabs").tabs();
		$("#dialog_link, ul#icons li").hover(
			function() { $(this).addClass("ui-state-hover"); }, 
			function() { $(this).removeClass("ui-state-hover"); }
		);
		
	});

	</script>

<?
 echo $project->traceContactByCall($_REQUEST[phone],'project',$task,$asterisk);
}

?>
