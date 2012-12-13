<?php
require_once('class/config.inc.php');
require_once('class/class.contacts.php');
require_once('class/class.project.php');
$project = new Project();
echo $project->GetContactsJson($_REQUEST[tag]);
?>
