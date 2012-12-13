<input class="daterange <?php echo $options["js_object"] . '_' . $name;?>_min" id="daterange_<?php echo $name;?>_min" onchange="<?php echo $options["js_object"];?>_update_object('<?php echo $name;?>_min' , $(this).val() );" /><?php
?><span class="<?php echo $options["table_id"]; ?>_search_span search_span"> To  </span><input class="daterange <?php echo $options["js_object"] . '_' . $name;?>_max" id="daterange_<?php echo $name;?>_max" onchange="<?php echo $options["js_object"];?>_update_object('<?php echo $name;?>_max' , $(this).val() );" />
<?php
$scripts[] = "$( '#daterange_$name" . "_min' ).datepicker({ dateFormat: 'yy-mm-dd' });";
$scripts[] = "$( '#daterange_$name" . "_max' ).datepicker({ dateFormat: 'yy-mm-dd' });";
?>