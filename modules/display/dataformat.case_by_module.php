<?php
$mod_arr = explode(":|:" , $original );
$module_name = $mod_arr[0];
$module_id = $mod_arr[1];
$options = array();
$options['OrderNumber']='';

    if( count( $mod_arr) > 2 ){
        $ct = 2;
        $quit = false;
        while($quit==false){
            if(array_key_exists($ct, $mod_arr) && array_key_exists($ct + 1, $mod_arr)  ){
                $options[$mod_arr[$ct]] = $mod_arr[$ct + 1 ];
            } else {
                $quit = true;
            }
            $ct++;
            $ct++;
        }
    }
    $stop = false;
    switch( strtolower($module_name ) ){
        default: 
            $result = $this->db->query("SELECT case_id FROM cases WHERE module_name = '" . strtoupper($module_name) . "' AND module_id = '$module_id' ");
        break;
        case "eapi_account":
            $result = $this->db->query("SELECT case_id FROM cases WHERE contact_module_name ='eapi_ACCOUNT' AND contact_module_id = '$module_id'");
        break;
        case "order":
            $result = $this->db->query("SELECT case_id FROM cases WHERE OrderNumber = '$module_id'");
        break;    
        case "cases";
                $target = "javascript: casecreation.right_bottom_panel('$module_id', '' , '" . strtoupper($module_name) . "' ,{target:'right_bottom_panel'});";
                $clean="<a onclick=\"$target\">$module_id</a>";
                $stop = true;
        break;        
    }
    if( $stop != true ){

            //$target = "javascript: casecreation.right_bottom_panel('$module_id', '' , '" . strtoupper($module_name) . "' ,{target:'right_bottom_panel'});";

            $ids = array();
            while( $row=$this->db->fetch_assoc($result)){
                $ids[] = $row["case_id"];
            }
            $target = "javascript: casecreation.right_bottom_by_case_id_array([" .implode(",", $ids) . "],{target:'right_bottom_panel'});";
            if( $format_options['return_csv'] != true && $format_options['return_csv'] != 1 ){
                $string = implode(",", $ids);

                $string = (strlen($string) > 13) ? substr($string,0,10).'...' : $string;
            } else {
            $string = implode(" ", $ids); 
            }
            if( $format_options['return_csv'] != true && $format_options['return_csv'] != 1 ){
                if( count($ids) == 0 ){
                    ob_start();
                    ?>
            casecreation.create_case_by_array(
                {
                    'module_name': '<?php echo $options['module_name'];?>',
                    'module_id': '<?php echo $options['module_id'];?>',
                    'contact_module_name': '<?php echo $options['contact_module_name'];?>',
                    'contact_module_id': '<?php echo $options['contact_module_id'];?>',
                    'OrderNumber': '<?php echo $options['OrderNumber'];?>',
                    'Status': 'Active', 
                    'Owner': '<?php echo $_SESSION['user_id']; ?>'} ,

                                    {       
                                        onUpdate: function(response,root){ $('#right_bottom_panel').html(response);slimcrm.flash_sidepanel();
                                        casecreation.show_updated_case_from_table( 
                                            '<?php echo $module_name; ?>',
                                            '<?php echo $module_id; ?>' , { onUpdate: function(response2 , root2 ){$('.tmp_case_<?php echo $module_name; ?>-<?php echo $module_id;?>').html(response2)} });}
                                    });
                    <?php
                    $target = ob_get_contents();
                    ob_end_clean();
                    $clean = "<div id='tmp_case_$module_name-$module_id' class='tmp_case_$module_name-$module_id' ><a href='#' onclick=\"$target\">New Case</a></div>";
                } else {
                $clean="<a onclick=\"$target\">$string</a>";
                }//$clean = $this->module_displayname( $module_name , $module_id );
            } else {
                $clean = $string;
            }
    }
    if( $format_options['return_csv'] == true || $format_options['return_csv'] == 1  ){
        if( $stop == true ){
            $clean=$module_id;
        } else {
            $clean = implode(":" , $ids);
        }
    }

//$clean = $original;
?>
