<?php
class Flags
{
	var $db;
	var $validity;
	var $Form;
        var $cache_color;
	 function __construct()
	 {
	    $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
	}
	 function __destruct()
	 {
	    $this->db->close();
	}
        function get_flag_color( $flag_type_id ){
            if(array_key_exists($flag_type_id, $this->cache_color )){
                return $this->cache_color[$flag_type_id];
            } else {
                $arr = $this->db->fetch_assoc($this->db->query("SELECT color FROM flag_type WHERE flag_type_id = '$flag_type_id'"));
                $this->cache_color[$flag_type_id] = $arr['color'];
                return $arr['color'];
            }
        }
	function get_flags_by_module( $module_name , $module_id , $overide ){
	  $sql = "SELECT * FROM ".FLAGS." a LEFT JOIN ".FLAG_TYPE." b ON a.flag_type_id = b.flag_type_id WHERE a.module_name = '$module_name' AND a.module_id = '$module_id'";
	  $result = $this->db->query($sql);
	  $return = array();
	  if($this->db->num_rows($result)>0){		  
		  while($row=$this->db->fetch_assoc($result)){
			  $return[] = $row;
		  }

      }
      return $return;
	}
        function add_flag_color(  $color = 'aeaeae' ){
            $this->db->insert('flag_type' , array('color' => $color ));
            
        }
        function remove_flag_color( $flag_type_id ){
            $this->db->query("DELETE FROM flag_type WHERE flag_type_id = '$flag_type_id'");
        }
        function edit_flag_color( $flag_type_id , $color ){
            $this->db->update('flag_type', array('color' => $color ), 'flag_type_id', $flag_type_id );
        }
        function edit_flag_description( $flag_type_id , $description ){
            $this->db->update('flag_type', array('description' => $description ), 'flag_type_id', $flag_type_id );
        }
	function add_flags_by_module( $module_name , $module_id , $flag_type_id ){
                $result = $this->db->query("SELECT * FROM " . FLAGS . " WHERE module_name = '$module_name' AND module_id = '$module_id' AND flag_type_id = '$flag_type_id'");
                if(  $this->db->num_rows($result) == 0 ){
                    $insert_sql_array = array();
                    $insert_sql_array['module_name'] = $module_name;
                    $insert_sql_array['module_id'] = $module_id;
                    $insert_sql_array['flag_type_id'] = $flag_type_id;
                    $insert_sql_array['owner_module_name'] = "TBL_USER";
                    $insert_sql_array['owner_module_id'] = "*";		
                    $this->db->insert(FLAGS,$insert_sql_array);
                }
           if( strtoupper( $module_name ) == "EMAIL"){
              $u= array();
              $u['rand'] = md5(date("Y-m-d H:i:s"));
              $this->db->update("eml_message", $u , 'mid', $module_id);
          }
	}
	
	function remove_flags_by_module_id($flag_type_id='',$module_id='',$module_name='EMAIL'){
		$sql_remove = $this->db->query("DELETE FROM ".FLAGS." WHERE `flag_type_id` = '$flag_type_id' and module_id='$module_id' and module_name='$module_name'");
	}	

	function add_flags_type( $color ){
		$insert_sql_array = array();
		$insert_sql_array['color'] = $color;	
		$this->db->insert(FLAG_TYPE,$insert_sql_array);	
		return $this->db->last_insert_id();
	}

	function display_flags_by_module_inner( $module_name , $module_id , $overide ) {
            $options['class'] = '';
		foreach( $overide as $n => $v ){
	            $options[$n] = '';
	            $options[$n] = $v;
	    }		
             $rand = rand(0 , 999999999999);
		?>
		<table>
			<tr>
				<td><a onclick='$(".add_edit_show_flags_<?php echo $options[0]. "_$rand" .'_'.$module_id ; ?>").toggle().css("top",(slimcrm.mouse_y - 125 )+ "px");'><?php echo $this->show_flag_link($module_name,$module_id,$overide);?></a></td>
				<td>
				<div id="toggleshowflags" style="position: absolute;left:500px;width:200px;" class="show_flags add_edit_show_flags_<?php echo $options[0]. "_$rand".'_'.$module_id; ?> <?php echo $options['class']; ?>" id="add_edit_show_flags_<?php echo $options[0].'_'.$module_id; ?>" >
					  <div  >
                                              <?php//CTLTODO: Make it so it is not using numeric key ?>
						<?php echo $this->display_all_flags('ADD_EDIT', $module_name , $module_id,$options[0]. "_$rand" ); ?>
					  </div>
				  </div>
				</td>				
			</tr>
		</table>
		<?php
	}
	
	function show_flag_link($module_name='',$module_id='',$overide){
		ob_start();
	    
		foreach( $overide as $n => $v ){
	            $options[$n] = '';
	            $options[$n] = $v;
	    }
		
		$rows = $this->get_flags_by_module($module_name, $module_id);
		$i = 0;
		$count = count($rows);	
		foreach($rows as $row){
			$color .= $row['color'];
			if($i != ($count - 1)){
				$color .= "|";
			}
			$i++;
		}	
		if($color == ''){
			$color = "999999";
		}	
		?>
		<div id="div_flags_<?php echo $options[0].'_'.$module_id; ?>">
			<img src="image.flag.php?color=<?php echo $color;?>&size=16" />
		</div>
		<?php 
		$html=ob_get_contents();
		ob_end_clean();
		return $html;			
	}
	
	function display_flags_by_module( $module_name , $module_id , $overide ) {
		$this->db->connect();
		return $this->display_flags_by_module_inner( $module_name , $module_id , $overide ) ;
		$this->db->close();
	}
	
        function display_flags_radio( $overide = array()){
            $options['columns'] = '2';
            $options['js_object'] = 'flags';
            $options['class_name'] = rand(0 , 9999999 );
            $options['name'] = 'flag_type_id';
            $options['onclick'] = $options["js_object"]. '_update_object(\'#name#\' , \'#flag_type_id#\' );';
            foreach($overide as $n => $v){
                $options[$n] = $v;
            }
            ob_start();
            $array = $this->get_all_flags();
            ?>
            <table class="flags_radio" ><tr>
<?php
$x = 0;
foreach($array as $n => $v ){
    if( $x >= $options['columns']){
        $x = 0;
        echo '</tr><tr>';
    }
    echo str_replace( array('#flag_type_id#' , '#name#' , '#singlequote#' ), array( $v['flag_type_id'] , $options['name'] , "'" ),'<td><div style="background: url(\'image.flag.php?color=' . $v['color'] . '&size=16\'); width: 16px; height: 16px; " class="flags_radio_' . $options['class_name'] . '" onclick="$(\'.flags_radio_' . $options['name'] . '\').css(\'border\' , \'0px\');$(this).css(\'border\' , \'2px solid black\');' . $options['onclick'] . '" >&nbsp;</div></td>' );
    $x++;
}

?>
                </tr></table>
            <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
        
        
	function get_all_flags(){
	   $return = array();
	   $result = $this->db->query("SELECT * FROM ".FLAG_TYPE);
	   while( $row = $this->db->fetch_assoc($result) ){
	        $return[] = $row;
	   }
	   return $return;
	}
	/*
         * CTLTODO: Remove div_id
         */
	function display_all_flags($type , $module_name , $module_id,$div_id , $overide=array() ){
            $options= array();
            $options['columns'] = 2;
            $options['max_height'] = 15;
            $options["div"] = $div_id;
            $options['show_null'] = false;
            $options['custom_class'] = '';
            foreach( $overide as $n => $v){
                $options[$n] = $v;
            }

	   ob_start();
	   $array = $this->get_all_flags();
	   $module_flags = $this->get_flags_by_module($module_name,$module_id);	

	   if($type == "SEARCH") $full_div_id = "show_flags";
	   if($type == "ADD_EDIT") $full_div_id = "add_edit_show_flags_".$div_id."_".$module_id;
	   
	   ?>
	   <script>
	     function get_div_array(){
			var div_type = new Array("<?php echo $div_id; ?>");
			return div_type;
		 }
	   </script>	   
	   <table class="emaildashboard_search_flags" >
			  <tr>
			  		<td>
						<a href="javascript:void(0);" onclick='<?php if( array_key_exists('closejs',$options) == false ){ ?> $(".<?php echo $full_div_id; ?>").hide();<?php
                                                } else {echo str_replace( "'" , '"' , $options["closejs"] ); } ?>'>
							<img src="images/cancel.png" alt="close" height="10px" width="10px" />
						</a>
					</td>
			  </tr>		 
		  <?php $flag = 0;
                  if($options['show_null'] == true ){
                    $array['NULL'] = array('flag_type_id' => "NULL" ,'color' => 'aaaaaa' );
                  }
                  $xt = 0;
                  while( (count($array) / $options['columns'] >  $options['max_height']) ){
                    
                      $options['columns']++;
                      $xt++;
                  }
		   foreach( $array as $option ){
			   if( ($flag%$options['columns']) == 0 ){ ?>
				  <tr>
			   <?php } ?>
					  <td>
						<input type="checkbox" name="search_checkbox_flag" id="search_checkbox_flag" value="<?php echo $option["flag_type_id"]; ?>"
							 <?php if($this->flag_in_array($option['flag_type_id'],$module_flags)) echo "checked = checked"; ?>
                                                       class="<?php echo $options['custom_class']; ?> auto_flag_<?php echo $option["flag_type_id"]; ?>"
							 onclick="javascript: <?php if(array_key_exists('onclick', $options) != true ){ ?>
										  //check_flag();
										  var get_info = get_mail_info(); 
										  <?php
                                                                                  
                                                                                    if($type == 'SEARCH') { ?>
                                                                                                    emaildash.display_email_by_module(document.getElementById('client_name').value,
                                                                                                                                                                            document.getElementById('client_id').value,
                                                                                                                                                                            get_info,
                                                                                                                                                                            {preloader:'prl',
                                                                                                                                                                            onUpdate: function(response,root){
                                                                                                                                                            document.getElementById('show_info').innerHTML=response;
                                                                                                                                                            }});
                                                                                    <?php } if($type == 'ADD_EDIT'){ ?>
                                                                                            if(this.checked){												  
                                                                                                    flags.add_flags_by_module('<?php echo $module_name; ?>',
                                                                                                                                                            '<?php echo $module_id; ?>',
                                                                                                                                                            '<?php echo $option['flag_type_id']; ?>',
                                                                                                                                                            {preloader:'prl',
                                                                                                                                                            onUpdate: function(response,root){
                                                                                                                                                            var div_loc = get_div_array();
                                                                                                                                                            flags.show_flag_link('<?php echo $module_name; ?>',
                                                                                                                                                                                                    '<?php echo $module_id; ?>',
                                                                                                                                                                                                    div_loc,
                                                                                                                                                            {onUpdate: function(response,root){
                                                                                                            document.getElementById('div_flags_<?php echo 'email_details_'.$module_id; ?>').innerHTML=response;
                                                                                                                            <?php if($div_id == "email_list"){ ?>
                                                                                                                    document.getElementById('div_flags_<?php echo 'email_details_'.$module_id; ?>').innerHTML=response;
                                                                                                                            <?php } ?>
                                                                                                                                                                    },
                                                                                                                                                                    preloader:'prl'});
                                                                                                                                                    }});	
                                                                                            }
                                                                                            else {
                                                                                                    flags.remove_flags_by_module_id('<?php echo $option['flag_type_id']; ?>',
                                                                                                                                                                    '<?php echo $module_id; ?>',
                                                                                                                                                                    {preloader:'prl',
                                                                                                                                                                    onUpdate: function(response,root){
                                                                                                                                                                    var div_loc = get_div_array();
                                                                                                                                                                    flags.show_flag_link('<?php echo $module_name; ?>',
                                                                                                                                                                                                            '<?php echo $module_id; ?>',
                                                                                                                                                                                                            div_loc,
                                                                                                                                                                    {onUpdate: function(response,root){
                                                                                                            document.getElementById('div_flags_<?php echo 'email_details_'.$module_id; ?>').innerHTML=response;
                                                                                                                            <?php if($div_id == "email_list"){ ?>
                                                                                                                    document.getElementById('div_flags_<?php echo 'email_details_'.$module_id; ?>').innerHTML=response;
                                                                                                                            <?php } ?>
                                                                                                                                                                            },
                                                                                                                                                                    preloader:'prl'});
                                                                                                                                                            }});	 
                                                                                                }
                                                                                                    <?php }
                                                                                  } else { echo str_replace( array( '#flag_type_id#' ), array( $option['flag_type_id'] ), $options['onclick'] ); }?>" />
					   </td>
					   <td>
						<img src="image.flag.php?color=<?php echo $option["color"]; ?>&size=16" class="flag_onblur" />
					   </td>
                                           <td style="color: black !important;"><?php echo $option['description']; ?></td>
				 <?php
				  $flag++; if( ($flag%$options['columns']) == 0 ){ ?>
				  </tr>
				 <?php }
			  } 
			  ?>
		 </table>
		<?php
		$html=ob_get_contents();
		ob_end_clean();
		return $html;		   
	}
	
	function flag_in_array($flag_type_id, $module_flags) {		
		foreach($module_flags as $module_flag){
			if($flag_type_id == $module_flag['flag_type_id']){
				return true;
			}
		}
		return false;		
	}
	
}
?>	
