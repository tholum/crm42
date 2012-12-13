<?php
//ini_set('display_errors',1);
require_once('app_code/config.inc.php');
require_once('class/class.contacts.php');
require_once('class/class.project.php');
$project=new Project();
switch($_REQUEST[page]){	
	case 'project':
		echo $project->addEditProject('server');
		break;
	case 'doc':
		echo $project->addDocument('server',$_REQUEST[project_id]);
		break;
	case 'person':
		//echo $project->addPerrsonToProject('server',$_REQUEST[project_id],$_REQUEST[role]);
		break;
	case 'project_new':
		echo $project->addProject('server',$_REQUEST[project_id],$_REQUEST[relation]);
		break;

}
?>