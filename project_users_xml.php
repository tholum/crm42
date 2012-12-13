<?php
ini_set("display_errors" , 0 );
header ("content-type: text/xml");
echo '<?xml version="1.0"?>';
require_once("class/class.projects.php");
require_once("class/config.inc.php");
require_once("class/global.config.php");
require_once("class/class.displaynew.php");
$disp = new displaynew;
$proj = new projects;
$project_id = $_GET["project_id"];

echo $disp->array_to_yuixml( $proj->get_project_users( $project_id  ) );

?>