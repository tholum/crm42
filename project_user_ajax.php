<?php
//ini_set("display_errors", 1);
require_once("class/class.projects.php");
require_once("class/config.inc.php");
require_once("class/global.config.php");
require_once("class/class.note.php");
$proj = new projects;
$notes = new Note;

$title = $_POST["user_id"];
$action = $_POST["action"];
$project_id = $_POST["project_id"];



?>