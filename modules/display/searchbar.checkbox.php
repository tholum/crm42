<input type="checkbox"  class="<?php echo $options["js_object"] . '_' . $name;?>"  onchange="<?php echo $options["js_object"];?>_update_object('<?php echo $name; ?>', $(this).ctl_checked() );" />