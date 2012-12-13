<?php
$default_date = "date_md";
      if(file_exists("./modules/display/dataformat.$default_date.php")){
          include("modules/display/dataformat.$default_date.php");
      } else {
          $clean =  $original;
      }
?>
