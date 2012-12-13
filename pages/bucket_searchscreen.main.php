<?php
require_once('app_code/global.config.php');
require_once('class/config.inc.php');
require_once('class/class.FctSearchScreen.php');
$fctsearch = new FctSearchScreen();
?>
    
	  <?php //echo $fctsearch->search_flowchart_tasks2();
          echo $fctsearch->display_search_bar( array() , array('user_id' => $_SESSION['user_id']));?>

	
<div id="form_main" class="bucket_search_screen_results">
    
    <div id="search_result" >
 <?php

   echo $fctsearch->display_flowchart_task2( array() , array('user_id' => $_SESSION['user_id'])  );
 ?>
    </div>
  
</div>