<?php
$time = strtotime($original);
$date = date("y-m-d" , $time);
 if(file_exists("./modules/display/dataformat.default_date.php")){
          include("modules/display/dataformat.default_date.php");
      } else {
          $clean =  $original;
      }
      $rand = rand( 10 , 99999);
      if( $format_options['return_csv'] != true && $format_options['return_csv'] != 1 ){
          ob_start();
          ?>
<div id="bucket_edit_date_containter_<?php echo $format_options['line']['chart_assign_id']; ?>" >
    <input id="bucket_edit_date_<?php echo $format_options['line']['chart_assign_id']; ?>" type="text" value="<?php echo $date;  ?>" style="width: 1px;visibility: hidden;" >
<a href="#" onclick="
    $('#bucket_edit_date_<?php echo $format_options['line']['chart_assign_id']; ?>').datepicker({ 
        dateFormat: 'yy-mm-dd' , 
        onSelect: function( date , inst ){ 
            global_task.set_due_date( '<?php echo $format_options['line']['chart_assign_id']; ?>' , date , { onUpdate: function( response , root ){ refresh_search(); }} );
            
        }
    });
    $('#bucket_edit_date_<?php echo $format_options['line']['chart_assign_id']; ?>').datepicker('show');" ><?php echo $clean; ?></a>

</div>
          <?php
          $clean = ob_get_contents();
          ob_end_clean();
      }
//chart_assign_id
      
?>
