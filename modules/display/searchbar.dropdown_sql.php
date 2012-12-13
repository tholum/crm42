<select class="<?php echo $options["js_object"] . '_' . $name;?>" onchange="<?php echo $options["js_object"];?>_update_object('<?php echo $name;?>' , $(this).val() );">
      <?php  if(array_key_exists('rename_select', $array_options)){
        $select_txt = $array_options['rename_select'];
    } else {
        $select_txt = "Select One";
    } ?>
    <option value="" ><?php echo $select_txt; ?></option>
    <?php echo $this->get_dropdown_options_query($array_options['sql']);?></select>