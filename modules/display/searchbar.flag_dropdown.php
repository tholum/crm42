<?php 
$tmp_arr=array();
$tmp_arr['js_object'] =  $options["js_object"];
$tmp_arr['name'] = $name;
//display_all_flags($type , $module_name , $module_id,$div_id , $overide=array() )

if(array_key_exists('onclick', $options) == true ){
    $onclick = $options["onclick"];
} else {
$onclick = "
    if(\$(this).ctl_checked()==true){ " .$options["js_object"].".$name"."_tmp['#flag_type_id#'] = '#flag_type_id#'; } 
    else { delete " .$options["js_object"].".$name"."_tmp['#flag_type_id#']; }
    " .$options["js_object"]."_update_object('$name' , " .$options["js_object"].".$name"."_tmp );";
}

$onclick .= "slimcrm.flag.apply_image_link(" .$options["js_object"].".$name"."_tmp , '$name" . "_flag_image');";
$scripts[] = $options["js_object"].".$name"."_tmp = [];";
$closejs = "$('#toggleshowflags$name' ).toggle(600);";
?>
<div style="position: absolute" >
<a onclick='$("#toggleshowflags<?php echo $name; ?>" ).toggle(600);'><img id="<?php echo $name; ?>_flag_image" class="<?php $name; ?>_flag_image" src="image.flag.php?color=aaaaaa&size=16" alt="default" /></a>
                                  <div id="toggleshowflags<?php echo $name; ?>" class="toggleshowflags show_flags" style="bottom: -10px;">
                                          <div id="show_flags<?php echo $name; ?>"  >
                                             <?php echo $this->flags->display_all_flags( 'SEARCH' , '' , '' , '' , array('onclick' => $onclick , 'closejs' => $closejs , 'show_null' => true )); ?>
                                          </div>
                                  </div>
</div>
