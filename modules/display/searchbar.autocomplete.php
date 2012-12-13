<?php 
/*
 * Avalible Options
 * For Single Autocomplete Option
 * $array_options["autocomplete_url"] is the url of what is needed
 * 
 */
$rand = rand( 0 , 9999999 );?>
<input class="<?php echo $options["js_object"] . '_' . $name;?>" onchange="if($(this).val()==''){<?php echo $options["js_object"] . 
        "_update_object( '" . $array_options["search_column"] . "' ,  '' )"; ?>}" type="text" id="autocomplete_<?php echo $name . $rand?>" />
<?php 
$script = "$('#autocomplete_$name$rand').autocomplete({source: '" .
        $array_options["autocomplete_url"] . 
        "' , select: function( event, ui ) { 
            " .$options["js_object"] . "_update_object( '" . $array_options["search_column"] . "' ,  ui.item." .$array_options["autocomplete_column"] . " );";
            $script.=$options["js_object"] . "_update_object( '$name' , ui.item.value );";
            if(array_key_exists("search_column2", $array_options)){
               $script .= $options["js_object"] . "_update_object( '" . $array_options["search_column2"] . "' ,  ui.item." .$array_options["autocomplete_column2"] . " );"; 
            }
        
 $script .="       }});";
$scripts[] = $script;
?>