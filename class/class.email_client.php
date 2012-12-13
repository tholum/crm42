<?php
require_once('class/class.flags.php');
require_once('class/class.casecreation.php');

class email_client{

var $flags;
var $qa_filter_options = array();
public $cache = array();
	function __construct( $page = ''){
			$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
			$this->validity = new ClsJSFormValidation();
			$this->Form = new ValidateForm();
			$this->flags = new Flags();
			$this->casecreation = new case_creation();
                        $this->cases = new cases();
                        if( $page == ''){
                            $this->page = new basic_page();
                        } else {
                            $this->page = $page;
                        }
                        $this->cache['message'] = array();
        }
        
        function manage_filters( $mailbox_id , $overide=array() ){
           global $run_on_start;
           
            $options=array();
            $options['container'] = 'manage_mailbox' . $mailbox_id;
            $rand = rand( 0 , 99999999);
            $create_js = '$( \'#new_filter_' . $rand . '\' ).dialog({ close: function(event , ui){ $(this).dialog(\'destroy\'); } , resizable: false,height:140,modal: true,buttons: {\'Create Filter\': function() { $(\'#manage_mailbox1\').accordion( \'destroy\' ); emaildash.create_new_filter( \'' . $mailbox_id . '\' , $(\'#new_filter_name_'. $rand . '\').val() , { target: \'' . $options['container'] .'\' , onUpdate : function( root , response ){ $(\'#manage_mailbox1\').accordion(); }}); $(this).remove();$( this ).dialog( \'close\' ); },Cancel: function() { $(this).remove();$( this ).dialog( \'close\' );}}});';
            return '<a onclick="$(\'#content_area\').append(\'<div id=new_filter_' . $rand . ' class=dialog >Name: <input id=new_filter_name_'. $rand . ' /></div>\');' . $create_js .'" ><button>New Filter <div class="add_button in_button" >&nbsp;</div></button></a><div class="manage_mailbox_filter'. $mailbox_id.' manage_mailbox_filter" id="' . $options['container'] .'" >' . $this->manage_filters_inner($mailbox_id, $options) . '</div>';
        }
        
        function display_add_filter( $mailbox_id ){
            ob_start(); ?>
            Name: <input id="new_filter_name" ><br/>
            <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;  
            
        }
        
        
        
        function get_all_filters($mailbox_id){
            $result = $this->db->query("SELECT * FROM eml_filters WHERE mailbox_id = '$mailbox_id'");
            $return = array();
            while($row = $this->db->fetch_assoc($result)){
                //$tmp = array();
                $row['qualify'] = unserialize($row['qualify']);
                $row['process'] = unserialize($row['process']);
                $return[] = $row;
                
            }
            return $return;
        }
        function get_description($info){
            $description = '';
            include('modules/email/process.' . $info['type'] . '.desc.php');
            return $description;
        }
        
        function get_quialify_filter_options( $selected='' ){
            if( count( $this->qa_filter_options) == 0 ){
                $options = array();
                $dir = getcwd();
                $dir .= '/modules/email';
                if (is_dir($dir)) {
                    if ($dh = opendir($dir)) {
                        while (($file = readdir($dh)) !== false) {
                            $fexp = explode('.', $file);
                            if( count( $fexp ) == 4 ){
                                if( $fexp[2] == 'qualify'){
                                    $options[] =  array( 'name' => $fexp[1] , 'value' => $fexp[1] );
                                }
                            }
                        }
                        closedir($dh);
                    }
                }
                $this->qa_filter_options = $options;
                return '<option>Select</option>'.$this->page->array_to_options($options, array('selected' => $selected));
            } else {
                return '<option>Select</option>'.$this->page->array_to_options($this->qa_filter_options , array('selected' => $selected));
            }
            
        }
        function get_qualify_column_options( $selected=''){
            $options = array();
            $options[] = array('name' => 'subject' , 'value' => 'subject');
            $options[] = array('name' => 'body' , 'value' => 'body');
            $options[] = array('name' => 'from_mailbox' , 'value' => 'from_mailbox');
            $options[] = array('name' => 'from_host' , 'value' => 'from_host');
            return '<option>Select</option>'. $this->page->array_to_options($options, array('selected' => $selected));
        }
        function get_filter( $eml_filter_id ){
            $result = $this->db->query("SELECT * FROM eml_filters WHERE eml_filter_id = '$eml_filter_id'");
            $return = array();
            while($row = $this->db->fetch_assoc($result)){
                //$tmp = array();
                $row['qualify'] = unserialize($row['qualify']);
                $row['process'] = unserialize($row['process']);
                $return = $row;
                
            }
            return $return;
        }
        
        function new_qualify( $eml_filter_id ){
            $info=array();
            $info['type'] = 'pass';
            $info['info'] = 'Auto Pass';
            $info['field'] = 'subject';
            $current = $this->get_filter( $eml_filter_id );
            $qualify = $current['qualify'];
            $qualify[] = $info;
            $this->db->update('eml_filters', array('qualify' => serialize($qualify) ), 'eml_filter_id', $eml_filter_id);
            return $this->edit_filter_qualify($eml_filter_id);
            
        }
        function new_process($eml_filter_id){
            $info=array();
            $info['type'] = 'flags';
            $info['vars'] = array();
            $info['vars']['flag_type_id'] = array('type' => 'string' , 'info' => '1');
            
            $current = $this->get_filter( $eml_filter_id );
            $process = $current['process'];
            $process[] = $info;
            $this->db->update('eml_filters', array('process' => serialize($process) ), 'eml_filter_id', $eml_filter_id);
            return $this->edit_filter_process($eml_filter_id);
            
        }
        function delete_qualify( $eml_filter_id , $old_data ){
            $old_data = base64_decode($old_data);
            $current = $this->get_filter( $eml_filter_id );
            $qualify_array = array();
            $new_data = array();
            foreach( $current['qualify'] as $qa ){
                if(serialize($qa) != $old_data ){
                    //$qa[$field] = $value;
                    $new_data[] = $qa;
                }
                
            }
            $info = serialize($new_data);
            $this->db->update('eml_filters', array('qualify' => $info ), 'eml_filter_id', $eml_filter_id);
            return  $this->edit_filter_qualify($eml_filter_id);
            
        }
        
        function update_filter_action( $eml_filter_id , $field , $value , $old_data=''){
            $old_data = base64_decode($old_data);
            $current = $this->get_filter( $eml_filter_id );
            $qualify_array = array();
            $new_data = array();
            $ran = false;
            $field_xp = explode( ":" , $field );
            foreach( $current['process'] as $pr ){
                if(serialize($pr) == $old_data && $ran == false){
                    $ran = true;
                    switch( count( $field_xp)){
                        case "1":
                        case 1:
                           $pr[$field] = $value; 
                        break;
                        case "2":
                        case 2:
                           $pr[$field_xp[0]][$field_xp[1]] = $value; 
                        break;
                        case "3":
                        case 3:
                           $pr[$field_xp[0]][$field_xp[1]][$field_xp[2]] = $value; 
                        break;
                    }
                    
                    
                    
                    if( count( $field_xp) == 1 ){
                        
                    } elseif( count()) {
                        $pr[$field_xp[0]][$field_xp[1]] = $value;
                    }
                }
                $new_data[] = $pr;
            }
            $info = serialize($new_data);
            $this->db->update('eml_filters', array('process' => $info ), 'eml_filter_id', $eml_filter_id);
            return  $this->edit_filter_process($eml_filter_id);
        }
        function delete_filter( $eml_filter_id){
            $arr = $this->db->fetch_assoc($this->db->query("SELECT mailbox_id FROM eml_filters WHERE eml_filter_id = '$eml_filter_id'") );
            $this->db->query("DELETE FROM eml_filters WHERE eml_filter_id = '$eml_filter_id'");
            return $this->manage_filters_inner($arr['mailbox_id'] );
        }
        function update_qualify($eml_filter_id , $field , $value , $old_data=''){
            $old_data = base64_decode($old_data);
            $current = $this->get_filter( $eml_filter_id );
            $qualify_array = array();
            $new_data = array();
            $ran = false;
            foreach( $current['qualify'] as $qa ){
                if(serialize($qa) == $old_data && $ran == false ){
                    $qa[$field] = $value;
                    $ran = true;
                }
                $new_data[] = $qa;
            }
            $info = serialize($new_data);
            $this->db->update('eml_filters', array('qualify' => $info ), 'eml_filter_id', $eml_filter_id);
            return  $this->edit_filter_qualify($eml_filter_id);
            
        }
        
        function edit_filter_process( $eml_filter_id , $overide=array()){
            $filter = $this->get_filter( $eml_filter_id );
            ob_start();
            ?>
<table>
   <tr>
        <td class="admin_edit_filter_left"></td>
        <td class="admin_edit_filter_center" >Edit Options</td>
        <td class="admin_edit_filter_right"></td>
        <td class="admin_edit_filter_options" ></td>
    </tr>
<?php
                foreach( $filter['process'] as $id => $pr ){
                    ?>
       <tr>
        <td class="admin_edit_filter_left"></td>
        <td class="admin_edit_filter_center" >
    <?php
                    include('modules/email/process.' . $pr['type'] . '.edit.php');
?>
 </td>
        <td class="admin_edit_filter_right"></td>
        <td class="admin_edit_filter_options" ></td>           
            <?php
                }
                                    ?>
<tr id="admin_edit_filter_add_option_<?php echo $eml_filter_id;?>" ><td class="admin_edit_filter_left"><div class="add_button" onclick="emaildash.new_process('<?php echo $eml_filter_id; ?>' , {target: '<?php echo 'dialog_' . $filter['mailbox_id'] ; ?>'} );">&nbsp;</div></td><td class="admin_edit_filter_center" ></td><td class="admin_edit_filter_right"></td><td class="admin_edit_filter_options" ></td></tr>        
</table>
                    <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html; 
        }
        
        function edit_filter_qualify( $eml_filter_id , $overide=array() ){
            $filter = $this->get_filter( $eml_filter_id );
            ob_start();
            ?>
<table style="width: 100%;" >
    <tr>
        <td class="admin_edit_filter_left">Type</td>
        <td class="admin_edit_filter_center" >Filter Text</td>
        <td class="admin_edit_filter_right">Column</td>
        <td class="admin_edit_filter_options" ></td>
    </tr>
  <?php foreach( $filter["qualify"] as $qa ) {?>
    <tr>
        <td class="admin_edit_filter_left"><select class="admin_edit_filter_type" onchange="emaildash.update_qualify('<?php echo $eml_filter_id; ?>' , 'type' , $(this).val() , '<?php echo base64_encode( serialize($qa)); ?>' , {target: '<?php echo 'dialog_' . $filter['mailbox_id'] ; ?>'})" ><?php echo $this->get_quialify_filter_options( $qa['type'] ); ?></select></td>
        <td class="admin_edit_filter_center" ><input type="text" class="admin_edit_filter_info" value="<?php echo $qa['info'];?>" onchange="emaildash.update_qualify('<?php echo $eml_filter_id; ?>' , 'info' , $(this).val() , '<?php echo base64_encode( serialize($qa)); ?>' , {target: '<?php echo 'dialog_' . $filter['mailbox_id'] ; ?>'})"  /></td>
        <td class="admin_edit_filter_right"><select class="admin_edit_filter_field" onchange="emaildash.update_qualify('<?php echo $eml_filter_id; ?>' , 'field' , $(this).val() , '<?php echo base64_encode( serialize($qa)); ?>' , {target: '<?php echo 'dialog_' . $filter['mailbox_id'] ; ?>'})"  ><?php echo $this->get_qualify_column_options($qa['field']); ?></select></td>
        <td class="admin_edit_filter_options" ><div class="trash_can" onclick="emaildash.delete_qualify( '<?php echo $eml_filter_id; ?>' , '<?php echo base64_encode( serialize($qa)); ?>' , {target: '<?php echo 'dialog_' . $filter['mailbox_id'] ; ?>'});">&nbsp;</div> </td>
    </tr>
  <?php } ?>
    <tr id="admin_edit_filter_add_option_<?php echo $eml_filter_id;?>" ><td class="admin_edit_filter_left"><div class="add_button" onclick="emaildash.new_qualify('<?php echo $eml_filter_id; ?>' , {target: '<?php echo 'dialog_' . $filter['mailbox_id'] ; ?>'} );">&nbsp;</div></td><td class="admin_edit_filter_center" ></td><td class="admin_edit_filter_right"></td><td class="admin_edit_filter_options" ></td></tr>
</table>
            <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;          
        }
        /*
        function display_filter( $filter_array ){
            ob_start();
            ?>
<div onclick="$('.manage_mailbox_filter_item').hide();$('#manage_mailbox_filter_item<?php echo $filter_array['eml_filter_id']; ?>').show()" class="manage_mailbox_filter_title" id="manage_mailbox_filter_title<?php echo $filter_array['eml_filter_id']; ?>" ><table style="width: 100%" ><td style="width: 33%" ></td><td style="width: 33%" ><?php echo $filter_array['title']; ?><td style="width: 20%" ></td><td style="width: 13%" class="manage_mailbox_filter_options"><div class="trash_can default_button" >&nbsp;</div></td></tr></table></div>
            <div class="manage_mailbox_filter_item" id="manage_mailbox_filter_item<?php echo $filter_array['eml_filter_id']; ?>" >
                <div class="manage_mailbox_filter_item_qa_title" ><table style="width: 100%"><tr><td width="15%">&nbsp;</td><td>Check For</td><td width="15%"><div class="edit_button_20 default_button" onclick="$('#<?php echo 'dialog_' . $filter_array['mailbox_id'] ; ?>').attr('title','Edit Qualifications For <?php echo $filter_array['title']; ?>');emaildash.edit_filter_qualify( '<?php echo $filter_array['eml_filter_id']; ?>' , { target: '<?php echo 'dialog_' . $filter_array['mailbox_id'] ; ?>' , onUpdate: function( root , response ){ $('#<?php echo 'dialog_' . $filter_array['mailbox_id'] ; ?>').dialog(); } } );" >&nbsp;</div></td></tr></table></div>
                <?php foreach($filter_array['qualify'] as $qa ){
                    ?>
                <div class="manage_mailbox_filter_item_qa" ><table style="width: 100%"><tr><td width="28%"><a class="manage_mailbox_filter_item_qa_field" href="#" ><?php echo $qa['field']; ?></a></td><td width="28%"><a class="manage_mailbox_filter_item_qa_type" href="#" ><?php echo $qa['type']; ?></a></td><td width="28%"><a class="manage_mailbox_filter_item_qa_info" href="#" ><?php echo $qa['info']; ?></a></div></td><td></td></tr></table>
                <?php } ?>
                    <div class="manage_mailbox_filter_item_pr_title" >Actions  <div class="edit_button_20 default_button" onclick="$('#<?php echo 'dialog_' . $filter_array['mailbox_id'] ; ?>').attr('title','Edit Actions For <?php echo $filter_array['title']; ?>');emaildash.edit_filter_process( '<?php echo $filter_array['eml_filter_id']; ?>' , { target: '<?php echo 'dialog_' . $filter_array['mailbox_id'] ; ?>' , onUpdate: function( root , response ){ $('#<?php echo 'dialog_' . $filter_array['mailbox_id'] ; ?>').dialog(); } } );" >&nbsp;</div></div>
                <?php foreach($filter_array['process'] as $pr ){
                    ?>
                    <div class="manage_mailbox_filter_item_pr_description" ><?php echo $this->get_description( $pr ); ?></div>
                <?php } ?>
            </div>
            <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }*/
        
        function create_new_filter( $mailbox_id , $name = 'Sample'){
            $insert = array();
            $insert['mailbox_id'] = $mailbox_id;
            $insert['title'] = $name;
            $this->db->insert('eml_filters', $insert);
            return $this->manage_filters_inner($mailbox_id);
        }
        
        function display_filter( $filter_array ){
            ob_start();
            ?>
<h3><a href='#' ><?php echo $filter_array['title']; ?></a></h3>
<div>
    <a class="main_button" onclick="$('#content_area').append('<div id=<?php echo 'dialog_' . $filter_array['mailbox_id'] ; ?> ></div>');$('#<?php echo 'dialog_' . $filter_array['mailbox_id'] ; ?>').attr('title','Edit Qualifications For <?php echo $filter_array['title']; ?>');emaildash.edit_filter_qualify( '<?php echo $filter_array['eml_filter_id']; ?>' , { target: '<?php echo 'dialog_' . $filter_array['mailbox_id'] ; ?>' , onUpdate: function( root , response ){ $('#<?php echo 'dialog_' . $filter_array['mailbox_id'] ; ?>').dialog( {close: function(event , ui){ $(this).remove();$(this).dialog('destroy'); } } ); } } );" ><button class="main_button" >Edit Qualifications<div class="edit_button in_button" >&nbsp;</div></button></a>
    <a class="main_button" onclick="$('#content_area').append('<div id=<?php echo 'dialog_' . $filter_array['mailbox_id'] ; ?> ></div>');$('#<?php echo 'dialog_' . $filter_array['mailbox_id'] ; ?>').attr('title','Edit Actions For <?php echo $filter_array['title']; ?>');emaildash.edit_filter_process( '<?php echo $filter_array['eml_filter_id']; ?>' , { target: '<?php echo 'dialog_' . $filter_array['mailbox_id'] ; ?>' , onUpdate: function( root , response ){ $('#<?php echo 'dialog_' . $filter_array['mailbox_id'] ; ?>').dialog({close: function(event , ui){ $(this).remove('');$(this).dialog('destroy'); } }); } } );" ><button class="main_button" >Edit Actions<div class="edit_button in_button" >&nbsp;</div></button></a>
<a class="main_button" onclick="$('#content_area').append('<div id=<?php echo 'dialog_' . $filter_array['mailbox_id'] ; ?> ></div>');$('#<?php echo 'dialog_' . $filter_array['mailbox_id'] ; ?>').attr('title','Delete <?php echo $filter_array['title']; ?>');
   $( '#<?php echo 'dialog_' . $filter_array['mailbox_id'] ; ?>' ).dialog({ close: function(event , ui){  $(this).remove();$(this).dialog('destroy'); } , resizable: false,height:140,modal: true,buttons: {'Delete <?php echo $filter_array['title']; ?>': function() {$('#manage_mailbox<?php echo $filter_array['mailbox_id']; ?>').accordion( 'destroy' ); $(this).remove();emaildash.delete_filter( '<?php echo $filter_array['eml_filter_id']; ?>' , { target: 'manage_mailbox<?php echo $filter_array['mailbox_id']; ?>' , onUpdate: function( root , response ){ $('#manage_mailbox1').accordion();} } ); },Cancel: function() { $(this).remove();$( this ).dialog( 'close' );}}});
   " ><button class="main_button" >Delete Filter<div class="delete_button in_button" >&nbsp;</div></button></a>
    <table style="width: 100%;"><tr><td style="width: 50%; border: 2px white;" >
           <?php foreach($filter_array['qualify'] as $info ){
               echo "<p>";
                include 'modules/email/filter.' . $info['type'] . '.desc.php';
                echo '</p>';
               } 
               ?>
            </td> <td style="width: 50%; border: 2px white;" >
                <?php
               foreach($filter_array['process'] as $info ){
                   echo "<p>";
                include 'modules/email/process.' . $info['type'] . '.desc.php';
                echo '</p>';
                     } ?>
            </td></tr></table>


</div>
            <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
        
        function manage_filters_inner( $mailbox_id , $overide=array() ){
            $options=array();
            foreach( $overide as $n => $v){
                $options[$n] = $v;
            }
            ob_start();
            ?>
<?php   
            $filters = $this->get_all_filters($mailbox_id);
           
            foreach( $filters as $filter_array ){
                echo $this->display_filter($filter_array);
            }
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
        
        
        function apply_bulk_actions($mids=array() , $action = '', $value = '' , $overide = array()){
            foreach( $mids as $mid ){
                switch( $action ){
                    case "flags":
                        foreach( $value as $flag){
                            $this->flags->add_flags_by_module('EMAIL', $mid , $flag);
                        }
                    break;
                    case "owner":
                        $update = array();
                        $update['owner_user_id'] = $value;
                        $this->db->update('eml_message', $update, 'mid', $mid);
                    break;
                    case "active":
                        $update = array();
                        $update['active'] = $value;
                        $this->db->update('eml_message', $update, 'mid', $mid);
                    break;
                }
            }
            //CTLTODO make this pass modulename and module_id
            return $this->display_email_by_module( '' , '' , $overide );
            
        }
        
        function custom_bulk_owner_button(){
            $owner_div_id = "custom_bulk_owner_button";
            ob_start();?>
<span id="<?php echo $owner_div_id; ?>" class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item" >
							  <?php
							   /*if( $option["owner_user_id"] != 0 ){ 
								   $user_group = $option["first_name"].' '.$option["last_name"];
								} else { 
								   $user_group = 'Unclaimed';
								  }*/
								echo $this->displayUserModuleLink( '0', $owner_div_id , array('search' => 'yes') );
								//echo $this->displayUserModuleLink("TBL_USER" , $option["owner_user_id"] , $user_group, $option["mid"]); ?>
							</span>
<?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
        
        
        function custom_bulk_flags_button(){
            ob_start();
            $name = 'bulk_actions';
            $onclick = "alert('ok');";
            $onclick = "
    if(\$(this).ctl_checked()==true){ email_client.bulk_flags['#flag_type_id#'] = '#flag_type_id#'; } 
    else { delete email_client.bulk_flags['#flag_type_id#']; }";
            
            $closejs = "$('#toggleshowflags$name' ).toggle(600);";
            ?>
<script>
    email_client.bulk_flags = [];
</script>
<a onclick='$("#toggleshowflags<?php echo $name; ?>" ).toggle(600);'><img src="images/flag_fct/default_flag.gif" alt="default"  /></a>
                                  <div id="toggleshowflags<?php echo $name; ?>" class="toggleshowflags show_flags_left" style="top: 0px;">
                                          <div id="show_flags<?php echo $name; ?>" class="" >
                                             <?php echo $this->flags->display_all_flags( 'SEARCH' , '' , '' , '' , array('onclick' => $onclick , 'closejs' => $closejs , 'custom_class' => 'bulk_actions' )); ?>
                                          </div>
                                      <a onclick="emaildash.apply_bulk_actions( email_client.bulk_emails , 'flags', email_client.bulk_flags ,a_default_search , { target: 'show_info' } );email_client.clean_bulk_emails();$('.bulk_actions').attr('checked' , false );email_client.bulk_flags=[];" ><button>add flags<div class="add_button in_button" >&nbsp;</div></button></a>
                                  </div>
<?php
            
            $html = ob_get_contents();
            ob_end_clean();
            return $html; 
        }
        
        function searchMail(){
            ob_start();
            ?>
<script>
             function get_mail_info(){
                        return a_default_search;
                 }
                 
                 function check_flag(){

                   }
           </script>
           <form id="emaildashboard_search" ></form>
            <?php
            $bulk_flags = $this->custom_bulk_flags_button();
            $bulk_owner = $this->custom_bulk_owner_button();
            
            $display = array();
            $display["email_address"] = array( "name" => "Email Address" , "type" => "text");
            $display["client_id"] = array("name" => "Client Name" , "type" => "autocomplete" , "autocomplete_url" => "account_lookup.php", "autocomplete_column" => "Email" , "search_column" => "email_address");
            $display["unixtime"] = array( "name" => "Received Dt" , "type" => "daterange");
            $display["archive"] = array("name" => "Status" , "type" => "dropdown_select" , "dropdown_name" => "Archive Options" , 'selected' => '1' , 'rename_select' => 'ALL' );
            $display["read"] = array("name" => "Read/Unread" , "type" => "dropdown_select" , "dropdown_name" => "email_read",'rename_select' => 'ALL');
            //$display["order_id"] = array( "name" => "Order #" , "type" => "text" );
            //$display["bucket_name"] = array("name" => "Bucket Name" , "type" => "autocomplete" , "autocomplete_url" => "fct_lookup.php", "autocomplete_column" => "global_task_id" , "search_column" => "global_task_id");
            $display["body"] = array( "name" => "Body" , "type" => "text");
            $display["flags"] = array( "name" => "Flags" , "type" => "flag_dropdown");
            //$display["Bulk1"] = array("show_name" => "no" , "name" => "" , "type" => "rawcode" , "code" => '<div class="bulk_actions" >Bulk: &nbsp;&nbsp;' . $bulk_flags . ' &nbsp; ' . $bulk_owner . '</div>');
            /*$display["Bulk2"] = array("show_name" => "no" , "name" => "b" , "type" => "rawcode" , "code" => '<div class="bulk_actions" ><a onclick="' . "emaildash.apply_bulk_actions( email_client.bulk_emails , 'active', '1' , { target: 'show_info' } );". '" ><button>Archive<div class="trash_can_normal in_button"/>&nbsp;</div></button></a>
                <a onclick="' . "emaildash.apply_bulk_actions( email_client.bulk_emails , 'active', '0' , { target: 'show_info' } );". '" ><button>Un-Archive<div class="trash_can_normal in_button"/>&nbsp;</div></button></a></div>');
            
             * 
             */
            $options = array();
            $options['run_on_update_only'] = "if(name != 'page'  ){ #JS_OBJECT#.page=1;}";
            $options["run_on_update"] = "emaildash.display_email_by_module( '' , '' ,  #RESULTS# , { target: 'show_info', onUpdate: function(response,root){ run_on_email_load(); } } );slimcrm.toggle_archive_button(#RESULTS#);";
            echo $this->page->array_to_searchbar($display , $options);
            
            
            $this->page->menuFromArray($array);
        $html = ob_get_contents();
                ob_end_clean();
                //$html = $this->searchMail_old();
                return $html;  
        }
        
        function use_template( $module_name , $module_id , $eml_template_id ){
            $this->db->query("INSERT INTO eml_template_used ( `module_name` , `module_id` , `eml_template_id` , `use_count` ) VALUES( '$module_name' , '$module_id' , '$eml_template_id' , '1' ) ON DUPLICATE KEY UPDATE `use_count` = use_count + 1");
        }
        function delete_template($module_name , $module_id , $eml_template_id){
            $this->db->query("DELETE from eml_template WHERE eml_template_id = '$eml_template_id'");
            return $this->manage_templates_inner($module_name, $module_id);
        }
        function create_template($module_name , $module_id , $title="New Template" ){
            $insert = array();
            $insert['module_name'] = $module_name;
            $insert['module_id'] = $module_id;
            $insert['title'] = $title;
            $this->db->insert('eml_template' , $insert );
            return $this->manage_templates_inner($module_name, $module_id);
        }
        function save_template( $module_name , $module_id , $eml_template_id, $title , $subject , $body){
            
            $update = array();
            $update['title'] = $title;
            $update['subject'] = $subject;
            $update['body'] = $body;
            $this->db->update('eml_template', $update, 'eml_template_id', $eml_template_id, false , '', '' , 0);
            return $this->manage_templates_inner($module_name, $module_id);
        }
        
    function manage_templates( $module_name , $module_id){
        ob_start();
        ?>
           <a onclick="$('body').append('<div id=create_template<?php  echo $module_name . $module_id ; ?> title=Create >Title <input id=new_title /></div>');
                          $('#create_template<?php  echo $module_name . $module_id ; ?>').dialog(
                     {
			resizable: false,
			height:140,
			modal: true,
			buttons: {
				'Create Template': function() {
                                        emaildash.create_template('<?php echo $module_name; ?>' , '<?php echo $module_id; ?>' , $('#new_title').val() ,{ target: 'manage_templates<?php  echo $module_name . $module_id ; ?>', onUpdate: function(result, root){ run_on_reload(); } });
					$( this ).dialog( 'destroy' ).remove();
				},
				Cancel: function() {
					$( this ).dialog( 'destroy' ).remove();
				}
			}
                    });
                        " ><button>Create Template<div class="trash_can_normal in_button" >&nbsp;</div></button></a>
           <?php
        $html=  ob_get_contents();
        ob_end_clean();
        return $html . '<div id="manage_templates' . $module_name . $module_id . '" class="manage_template accordion">' . $this->manage_templates_inner($module_name, $module_id) . "</div>";
    }
    function manage_templates_inner($module_name , $module_id){
        ob_start();
        $templates = $this->get_templates($module_name, $module_id, 'ALL');
        foreach($templates as $template){
            ?>
           <H3><a href='#' ><?php echo $template["title"]; ?></a></H3>
           <div ><div style="height: 220px;">
               <table class="manage_template_table">
                   <tr><td colspan="2"><a onclick="
                       emaildash.save_template( '<?php echo $module_name; ?>' ,
                       '<?php echo $module_id; ?>' ,
                       '<?php echo $template['eml_template_id']; ?>',
                       $('#manage_template_title_<?php echo $template['eml_template_id'];?>').val() ,
                       $('#manage_template_subject_<?php echo $template['eml_template_id'];?>').val() ,
                       tinyMCE.get('manage_template_textarea_<?php echo $template['eml_template_id'] ;?>').getContent(),
                   { target: 'manage_templates<?php  echo $module_name . $module_id ; ?>', onUpdate: function(result, root){ run_on_reload(); } })" >
                               <button>Save Template<div class="save_button in_button" >&nbsp;</div></button></a>
                       <a onclick="$('body').append('<div id=delete_template<?php  echo $template['eml_template_id'];?> title=Delete >Delete Template?</div>');
                          $('#delete_template<?php  echo $template['eml_template_id'];?>').dialog(
                     {
			resizable: false,
			height:140,
			modal: true,
			buttons: {
				'Delete': function() {
                                        emaildash.delete_template('<?php echo $module_name; ?>' , '<?php echo $module_id; ?>' , '<?php echo $template['eml_template_id'];?>',{ target: 'manage_templates<?php  echo $module_name . $module_id ; ?>', onUpdate: function(result, root){ run_on_reload(); } });
					$( this ).dialog( 'destroy' ).remove();
				},
				Cancel: function() {
					$( this ).dialog( 'destroy' ).remove();
				}
			}
                    });
                        " ><button>Delete Template<div class="trash_can_normal in_button" >&nbsp;</div></button></a></td></tr>
                   <tr><td colspan="2" >&nbsp;</td></tr>
                   <tr><td style="width: 100px;">Title: </td><td><input style="width: 400px;"  name="manage_template_title_<?php echo $template['eml_template_id'];?>" value="<?php echo $template['title']; ?>" id="manage_template_title_<?php echo $template['eml_template_id'];?>" ></td></tr>
                   <tr><td style="width: 100px;">Subject: </td><td><input style="width: 400px;"  name="manage_template_subject_<?php echo $template['eml_template_id'];?>" value="<?php echo $template['subject']; ?>" id="manage_template_subject_<?php echo $template['eml_template_id'];?>" ></td></tr></table><br/>
               <textarea class="manage_template_textarea mceeditor_500" id="manage_template_textarea_<?php echo $template['eml_template_id'] ;?>" ><?php echo $template["body"]; ?></textarea></div></div>
           <?php
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;  
    }
        
    function get_templates( $module_name , $module_id , $top='5' ,$access='MODULE' , $overide=array() ){
        $options = array();
        $options['return_sql']=false;
        $options['query'] ='';
        foreach($overide as $n => $v){
            $options[$n] = $v;
        }
        if( strtoupper( $top ) == 'ALL'){
            $limit = '';
        } else {
            $limit = "LIMIT $top";
        }
        $WHERE = '';
        
        foreach( $_SESSION['group_id'] as $group ){
           $WHERE .= " OR ( a.module_name = 'TBL_USERGROUP' and a.module_id = '$group' )";
        }
        $WHERE .= " OR ( a.module_name = 'GLOBAL' and a.module_id = '0' )";
        if( $access == 'MODULE'){
            $WHERE = '';
        }
        $WHERE = "( a.module_name = '$module_name' AND a.module_id = '$module_id' ) $WHERE";
        if($options['query'] != '' ){
            $query_str = "%" . addslashes($options['query']) . "%";
            $WHERE = "( $WHERE ) AND ( title LIKE '$query_str' OR subject LIKE '$query_str' ) ";
        }
        $sql = "SELECT a.* FROM eml_template a LEFT JOIN eml_template_used b ON a.eml_template_id = b.eml_template_id AND b.module_name = '$module_name' AND b.module_id = '$module_id' WHERE $WHERE ORDER BY b.use_count DESC $limit ";
        $result = $this->db->query($sql);
        $return = array();
        
        while( $row = $this->db->fetch_assoc($result)){
            $return[] = $row;
        }
        if( $options['return_sql'] == true ){
            return $sql;
        }
        return $return;
    }
    function display_templates( $module_name , $module_id ){
        ob_start();
        $templates = $this->get_templates($module_name, $module_id , '5' , 'ALL');
        foreach( $templates as $n => $v){ 
            if( strlen( $v['title']) > 10 ){
                $text = substr($v['title'], 0 , 9) . "...";
            } else {
                $text = $v['title'];
            }
            $onclick = '';

            $onclick .= "slimcrm.use_template('" . $v['eml_template_id'] . "');";
            //$onclick .= "tinyMCE.ctl_if_set('message_emaildashboard' , '" . str_replace(  array( "'" , '"' ) , array( "\\'" , "\\'" ),$v['body']) . "' , { format : 'raw'} );";
            $onclick .= "emaildash.use_template( '$module_name' , '$module_id' , '" . $v['eml_template_id'] . "' , {} );";

            
            echo "<a style='z-index: 100;' onclick=\"$onclick\" title=\"" . $v['title'] . "\"><button style='z-index: 100;' >$text<div class='template_button in_button' style='z-index: 100;'>&nbsp;</div></button></a>";
            
            }
            ?>
            <a onclick="$('body').append('<div id=search_template title=Search >Title <input id=template_input /></div>');
                          $('#search_template').dialog(
                     {
			resizable: false,
			height:140,
			modal: true,
                        open: function( event , ui ){
                            $('#template_input').autocomplete(
                                {
                                    source: 'search_templates.php',
                                    select: function( event , ui ){
                                        if( ui.item.subject != ''){
                                            $('.email_subject').val( ui.item.subject ); 
                                        }
                                        tinyMCE.set_on_all( ui.item.body);
                                        emaildash.use_template( '<?php echo $module_name; ?>' , '<?php echo $module_id; ?>' , ui.item.subject , {} );
                                        $( '#search_template' ).dialog( 'destroy' ).remove();
                                    }
                                }
                            );
                        },
			buttons: {
				'Create Template': function() {
					$( this ).dialog( 'destroy' ).remove();
				},
				Cancel: function() {
					$( this ).dialog( 'destroy' ).remove();
				}
			}
                    });
                        " ><button>search<div class="search_button in_button" >&nbsp;</div></button></a>
           
           <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
        
    }
    
    function searchMail_old() {
       ob_start();
           $formName = "emaildashboard_search";?>
           <script>
             function get_mail_info(){
                        var mail_information = {
                               
                                from_mailbox: document.getElementById('mail_address').value,
                                evntstart_date: document.getElementById('evntstart_date').value,
                                evntend_date: document.getElementById('evntend_date').value,
                                archive: document.getElementById('archive').value,
                                body_txt: document.getElementById('body_txt').value,
                                read: document.getElementById('read').value,
                                flag: document.getElementById('hidden_flag').value
                        };
                        return mail_information;
                 }
                 
                 function check_flag(){
                                var id=0;
                                var len = document.emaildashboard_search.search_checkbox_flag.length;
                                //alert(len);
                                for(i=0;i<len;i++){
                                        if(eval("document.emaildashboard_search.search_checkbox_flag[" + i + "].checked") == true)
                                                {
                                                val=eval("document.emaildashboard_search.search_checkbox_flag[" + i + "].value");
                                                //alert(val);
                                                id=id+','+ val;
                                                //alert(id);                          
                                                }
                                }
                                if(len>0){
                                 document.getElementById('hidden_flag').value=id;
                                }else{
                                document.getElementById('hidden_flag').value=1;
                                }
                   }
           </script>
           
           <form name="<?php echo $formName;?>" method="post" action="">
                 <table class="emaildashboard_search_panel" >
                        <tr>
                           <td>Email Address :</td>
                           <td width="13%">
                                <input type="text" name="mail_address" id="mail_address"
                                                        onchange="javascript:var get_info = get_mail_info();
                                                                   emaildash.display_email_by_module(
                                                                                                                document.getElementById('client_name').value,
                                                                                                                document.getElementById('client_id').value,
                                                                                                                get_info,
                                                                                                               
                                                                                                                {preloader:'prl',
                                                                                                                onUpdate: function(response,root){
                                                                                                                document.getElementById('show_info').innerHTML=response;
                                                                                                        $('#search_table')
                                                                                                        .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
                                                                                                                );"/>
                           </td>
                           <td>Client Name :</td>
                           <td width="13%">
                                        <input type="text" name="client_name" id="client_name"
                                                        onchange="javascript:var get_info = get_mail_info();
                                                                   emaildash.display_email_by_module(
                                                                                                                document.getElementById('client_name').value,
                                                                                                                document.getElementById('client_id').value,
                                                                                                                get_info,
                                                                                                                       
                                                                                                                        {preloader:'prl',
                                                                                                                        onUpdate: function(response,root){
                                                                                                                        document.getElementById('show_info').innerHTML=response;
                                                                                                        $('#search_table')
                                                                                                        .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
                                                                                                                );"/>
                                </td>
                               
                            <td>Received :</td>
                                <td width="13%">
                                   <input type="text" name="evntstart_date" id="evntstart_date" value="<?php //echo $row_order['event_date']; ?>"/>
                                                 <script type="text/javascript">        
                                                 function start_cal(){
                                                 new Calendar({
                                                 inputField     : "evntstart_date",
                                                 dateFormat             : "%Y-%m-%d",
                                                 trigger                : "evntstart_date",
                                                 weekNumbers    : true,
                                                 bottomBar              : true,                          
                                                 onSelect               : function() {
                                                                                                this.hide();
                                                                                                        document.getElementById('evntstart_date').value=this.selection.print("%Y-%m-%d");
                                                                                                        var get_info = get_mail_info();
                                                                                 emaildash.display_email_by_module(
                                                                                                                                document.getElementById('client_name').value,
                                                                                                                                document.getElementById('client_id').value,
                                                                                                                                get_info,
                                                                                                                               
                                                                                                                                {preloader:'prl',
                                                                                                                                onUpdate: function(response,root){
                                                                                                                document.getElementById('show_info').innerHTML=response;
                                                                                                        $('#search_table')
                                                                                                        .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] }) }});                                                                                          
                                                                                                                }
                                                                                   });                
                                                        }
                                                        start_cal();
                                                        </script>
                                </td>
                                <td>
                                   <a href="javascript:void(0);"
                                      onclick="javacsript:document.getElementById('evntstart_date').value = '';
                                                                                  var get_info = get_mail_info();
                                                                                           emaildash.display_email_by_module(
                                                                                                                document.getElementById('client_name').value,
                                                                                                                document.getElementById('client_id').value,
                                                                                                                get_info,
                                                                                                               
                                                                                                                {preloader:'prl',
                                                                                                                onUpdate: function(response,root){
                                                                                                                document.getElementById('show_info').innerHTML=response;
                                                                                                        $('#search_table')
                                                                                                        .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
                                                                                                                );"> <img src="images/trash_can.png" border="0" /></a> to </td>
                                <td width="13%">
                                   <input type="text" name="evntend_date" id="evntend_date" value="<?php //echo $row_order['event_date']; ?>"/>
                                                 <script type="text/javascript">        
                                                 function start_cal(){
                                                 new Calendar({
                                                 inputField     : "evntend_date",
                                                 dateFormat             : "%Y-%m-%d",
                                                 trigger                : "evntend_date",
                                                 weekNumbers    : true,
                                                 bottomBar              : true,                          
                                                 onSelect               : function() {
                                                                                                this.hide();
                                                                                                        document.getElementById('evntend_date').value=this.selection.print("%Y-%m-%d");
                                                                                                        var get_info = get_mail_info();
                                                                                  emaildash.display_email_by_module(
                                                                                                                                document.getElementById('client_name').value,
                                                                                                                                document.getElementById('client_id').value,
                                                                                                                                get_info,
                                                                                                                               
                                                                                                                                {preloader:'prl',
                                                                                                                                onUpdate: function(response,root){
                                                                                                                                        document.getElementById('show_info').innerHTML=response;
                                                                                                        $('#search_table')
                                                                                                        .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] }) }});                                                                                          
                                                                                                                }
                                                                                   });                
                                                        }
                                                        start_cal();
                                                        </script>
                                </td>
                                <td>
                                   <a href="javascript:void(0);"
                                      onclick="javacsript:document.getElementById('evntend_date').value = '';
                                                                                  var get_info = get_mail_info();
                                                                            emaildash.display_email_by_module(
                                                                                                                document.getElementById('client_name').value,
                                                                                                                document.getElementById('client_id').value,
                                                                                                                get_info,
                                                                                                       
                                                                                                        {preloader:'prl',
                                                                                                        onUpdate: function(response,root){
                                                                                                                document.getElementById('show_info').innerHTML=response;
                                                                                                        $('#search_table')
                                                                                                        .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
                                                                                                                );"> <img src="images/trash_can.png" border="0" /></a>
                                </td>
                         </tr>
                         <tr>
                                <td>Archive :</td>
                            <td>
                                   <select style="width:94%;" name="archive" id="archive"
                                                onchange="javascript:var get_info = get_mail_info();
													   emaildash.display_email_by_module(
																			document.getElementById('client_name').value,
																			document.getElementById('client_id').value,
																			get_info,
																   
																	{preloader:'prl',
																	onUpdate: function(response,root){
																	document.getElementById('show_info').innerHTML=response;
															$('#search_table')
															.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
																	);">
					  <option value="">--Select--</option>
					  <?php echo $this->array2options( $this->get_dropdown(DROPDOWN_OPTION, 'Archive Options' ), 'identifier', 'name' ); ?>
                                </select>
                                </td>
                                <td>Client ID :</td>
                            <td>
                                   <input type="text" name="client_id" id="client_id"
                                                        onchange="javascript:var get_info = get_mail_info();
                                                                                   emaildash.display_email_by_module(
                                                                                                                        document.getElementById('client_name').value,
                                                                                                                        document.getElementById('client_id').value,
                                                                                                                        get_info,
                                                                                                               
                                                                                                                {preloader:'prl',
                                                                                                                onUpdate: function(response,root){
                                                                                                                document.getElementById('show_info').innerHTML=response;
                                                                                                        $('#search_table')
                                                                                                        .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
                                                                                                                );"/>
                                </td>
                                <td>Read :</td>
                                <td>
                                   <select style="width:94%;" name="read" id="read"
                                                onchange="javascript:var get_info = get_mail_info();
                                                                                   emaildash.display_email_by_module(
                                                                                                                        document.getElementById('client_name').value,
                                                                                                                        document.getElementById('client_id').value,
                                                                                                                        get_info,
                                                                                                               
                                                                                                                {preloader:'prl',
                                                                                                                onUpdate: function(response,root){
                                                                                                                document.getElementById('show_info').innerHTML=response;
                                                                                                        $('#search_table')
                                                                                                        .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
                                                                                                                );">
                                                  <option value="">--Select--</option>
                                                  <?php echo $this->array2options( $this->get_dropdown(DROPDOWN_OPTION, 'read Options' ), 'identifier', 'name' ); ?>
                                   </select>
                                </td>
                                <td>flags:</td>
                                <td>
                                  <a onmouseover='$("#show_flags").show(600);'><img src="images/flag_fct/default_flag.gif" alt="default" /></a>
                                  <div id="toggleshowflags">
                                          <div id="show_flags">
                                             <?php echo $this->flags->display_all_flags( 'SEARCH' ); ?>
                                          </div>
                                  </div>
                                </td>
                                <td><input type="hidden" id="hidden_flag" name="hidden_flag" value="" /></td>
                         </tr>
                         <tr>
                           <td>Body :</td>
                           <td>
                             <input type="text" name="body_txt" id="body_txt"
                                                        onchange="javascript:var get_info = get_mail_info();
                                                                   emaildash.display_email_by_module(
                                                                                                                document.getElementById('client_name').value,
                                                                                                                document.getElementById('client_id').value,
                                                                                                                get_info,
                                                                                                               
                                                                                                                {preloader:'prl',
                                                                                                                onUpdate: function(response,root){
                                                                                                                document.getElementById('show_info').innerHTML=response;
                                                                                                        $('#search_table')
                                                                                                        .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
                                                                                                                );"/>
                           </td>
                         </tr>
           </table>
               </form>
                <?php    
        $html = ob_get_contents();
                ob_end_clean();
                return $html;    
    }
        function get_message_id_by_email( $email ){
           $result = $this->db->query("SELECT DISTINCT mid FROM `eml_address` WHERE CONCAT_WS('@', mailbox, host) LIKE '%$email%'");
           $return = array();
           while($row = $this->db->fetch_assoc($result)){
               $return[] = $row["mid"];
           }
           return $return;
        }
        function get_email_by_modules( $module_name='' , $module_id='' , $overide=array() ){
           
           $options["archive"] = 1;
           $options['pagation'] = false;
           $options['page'] = 1;
           $options['limit'] = 100;
		   /*$options["evntstart_date"] = '';
		   $options["evntend_date"] = '';
		   $options["archive"] = '';
           $options["body_txt"] = '';*/
           foreach( $overide as $n => $v ){
                    $options[$n] = '';
                    $options[$n] = $v;
           }
           //print_r($options); echo $options["archive"];
           $flag_id = str_replace("0,","",$options["flag"]);
           
           $start_date = strtotime($options["evntstart_date"]);
           $end_date = strtotime($options["evntstart_date"]);
           
           /*
            * Makes start_date and end_date backwards compatible
            */
           if( $start_date == strtotime('')){
               if(array_key_exists('unixtime_min', $options) ){
                    $start_date = strtotime($options['unixtime_min']);
               } else {
                   $start_date = '';
               }
           }
           if( $end_date == strtotime('')){
               if(array_key_exists('unixtime_max', $options) ){
                $end_date = strtotime($options['unixtime_max']);
                $end_date += 86399; //Sets it to the end of the day
               } else {
                   $end_date = '';
               }
           }
         
           
           
           if( $options["archive"] == '' || $options["archive"] == 0 ){ $archive = false; }
           elseif( $options["archive"] == 2 ){$archive = 1; }
           elseif( $options["archive"] == 1 ){ $archive = 0; }
           
           if( $options["read"] == 'read' ) $read = 1;
           elseif( $options["read"] == 'unread' ) $read = 0;
           
           $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT `mid`, `subject`, `body`, `encoding`, `unixtime`, `importetime`, `from_displayname`, `from_mailbox`, `from_host`, `read`, `active`, a.module_name, a.module_id, `group_id`, `owner_user_id`, `imap_id`, `sent_by_user_id` FROM ".EML_MESSAGE." a ";
           
           if( is_array($options["flags"]) ){ $sql .= " LEFT JOIN ".FLAGS." c ON a.mid = c.module_id "; }
           if( $options["from_mailbox"] ){ $sql .= " LEFT JOIN ".EML_ADDRESS." d ON a.mid = d.mid ";}
           $sql .= " WHERE 1 ";
		   
//		   if( $options["archive"] == '' ){ $sql .= " and a.active = '0' "; }
           
           if( $module_name ){ $sql.=" and CONCAT_WS(' ', b.first_name,b.last_name) LIKE '%$module_name%' "; }
           
           if( $module_id ){ $sql.=" and a.owner_user_id = '$module_id' "; }
           
           if( $options["email_address"] ){ 
               
                $tmp_array = $this->get_message_id_by_email($options[email_address]);
                  if( count( $tmp_array ) != 0){
                      $tmp = ' AND ( a.mid=\'';
                      $tmp .= implode( "' OR a.mid ='" , $tmp_array );
                      $tmp .= "' ) ";
                      $sql .= $tmp;
                  } else {
                      $sql .= " AND 1=0 ";
                  }
                  
                      
               }
           
           if( $options["from_mailbox"] ){ $sql.=" and CONCAT_WS('@', d.mailbox, d.host) LIKE '%$options[from_mailbox]%' "; }
           
           if( $start_date != '' ){ $sql.=" and a.unixtime >= '$start_date' "; }
                       
           if( $end_date != '' ){ $sql.=" and a.unixtime <= '$end_date' "; }
               
           if( $options["evntstart_date"] != '' and $options["evntend_date"] != '' ){ $sql.=" and a.unixtime BETWEEN '$start_date' and '$end_date' "; }
           
           if( $options["archive"] != '' ){  $sql.=" and a.active = '$archive' ";}
           
           if( $options["body"] ){ $sql.=" and a.body LIKE '%" . $options["body"] . "%' "; }
           
           if( $options["read"] ){ $sql.=" and a.read = '$read' "; }
           
           if( is_array($options["flags"] ) ){
               if( count($options["flags"] ) >= 1 ){
                   $tmp_flag = array();
                   foreach( $options["flags"] as $n => $v ){
                       if( $v != "NULL" ){
                           $tmp_flag[$n] = " = '$v'";
                       } else {
                           $tmp_flag[$n] = " IS $v";
                       }
                   }
                    
                    $sql .= " and ( c.flag_type_id" . implode(" OR c.flag_type_id", $tmp_flag) . " )";
               }
               }
           
           $sql .=" ORDER BY a.unixtime DESC ";
//           $options['pagation'] = false;
//           $options['page'] = 1;
//           $options['limit'] = 30;
           if( $options['pagation'] !== false && $options['limit'] != '' ){
               $offset = ( $options['page'] - 1 ) * $options['limit'];
               $sql .= "LIMIT $offset , " . $options['limit'];
           }
           
//           echo '<br/>'. $sql;
           $result = $this->db->query($sql,__FILE__,__LINE__);
           if( $options['pagation'] !== false && $options['limit'] != '' ){
               $res = $this->db->query("SELECT FOUND_ROWS() fr");
               $tr_ar = $this->db->fetch_assoc($res);
               $page_count = ceil( $tr_ar['fr'] / $options["limit"] );
               //$pages = $tr_ar['fr'] / $options['limit'];
           }
           $return = array();
           while($row=$this->db->fetch_assoc($result)){
                    //$row['subject'] = date("y-m-d" ,$start_date );
                  $this->cache_message($row);
                  $return[] = $row;
           }
           if($options['pagation'] !== false){
               $tmp_r =$return;
               $return=array();
               $return['page_count'] = $page_count;
               $return['data'] = $tmp_r;
               $return['sql'] = $sql;
               $return['options'] = $options;
               $return['trfr'] = $tr_ar;
               $return['per_page'] = $options["limit"];
               //$return['ct'] = mysql_num_rows($res);
           }
       return $return;
        }
       
        function get_email_address_from_module( $module_name='' , $module_id='' ){
            $result = $this->db->query("SELECT * FROM ".EML_ADDRESS." WHERE 1");
       
       
       
       
        }
       
        function get_email_by_mid( $mid='' ){
           /*$host_to = EML_INTERNAL;
           $result_message = $this->db->query("SELECT * FROM ".EML_MESSAGE." WHERE mid = '$mid'");
           $row_message = $this->db->fetch_assoc($result_message);
           if( $row_message[group_id] == 0 ){
               $result_add = $this->db->query("SELECT * FROM ".EML_ADDRESS." WHERE mid = '$mid'");
                   $return_add = array();
                   while($row_add = $this->db->fetch_assoc($result_add)){
                          $return_add[] = $row_add;
                   }
           
           } else {
             
           
           
           
           
           }*/
           
           
           $sql ="SELECT a.*, b.first_name, b.last_name, c.email_address FROM ".EML_MESSAGE." a LEFT JOIN ".TBL_USER." b ON a.owner_user_id = b.user_id LEFT JOIN ".EML_MAILBOX." c ON a.module_name = c.module_name AND a.module_id = c.module_id WHERE a.mid = '$mid'";
           $result = mysql_query($sql);
           //echo $sql;
           $return = array();
           while($row=$this->db->fetch_assoc($result)){
                  $return[] = $row;
           }
     
       return $return;
       
        }
		
		function get_email_by_id( $mid='' ){
          $result = $this->db->query("SELECT * FROM ".EML_MESSAGE." WHERE mid = '$mid'");
          $return = array();
          while($row=$this->db->fetch_assoc($result)){
                  $return[] = $row;
          }
          return $return;
        }
       
        function get_related_mails( $mid='' ){
           $sql ="SELECT a.*, b.source, b.mailbox, b.host, c.first_name, c.last_name FROM ".EML_MESSAGE." a LEFT JOIN ".EML_ADDRESS." b ON a.mid = b.mid LEFT JOIN ".TBL_USER." c ON a.owner_user_id = c.user_id WHERE a.mid = '$mid' and b.source = 'TO'";
           $result = mysql_query($sql);
           echo $sql;
           $return = array();
           while($row=$this->db->fetch_assoc($result)){
                  $return[] = $row;
           }
     
       return $return;
        }
       
        function get_mail_by_group( $mid='' ){
       
           $result_message = $this->db->query("SELECT group_id FROM ".EML_MESSAGE." WHERE mid = '$mid'");
           $row_message = $this->db->fetch_assoc($result_message);
           $return = array();
           if( $row_message[group_id] == 0 ){
           
               $sql ="SELECT a.*, b.first_name, b.last_name, c.email_address, d.first_name sent_by_firstname, d.last_name sent_by_lastname FROM ".EML_MESSAGE." a LEFT JOIN ".TBL_USER." b ON a.owner_user_id = b.user_id LEFT JOIN ".EML_MAILBOX." c ON a.module_name = c.module_name AND a.module_id = c.module_id LEFT JOIN ".TBL_USER." d ON a.sent_by_user_id = d.user_id WHERE a.mid = '$mid'";
                   $result = mysql_query($sql);
                   //echo $sql;
                   while($row=$this->db->fetch_assoc($result)){
                          $return[] = $row;
                   }
           
           } else {
                   $result = mysql_query("SELECT a.*, b.first_name, b.last_name, c.email_address, d.first_name sent_by_firstname, d.last_name sent_by_lastname FROM ".EML_MESSAGE." a LEFT JOIN ".TBL_USER." b ON a.owner_user_id = b.user_id LEFT JOIN ".EML_MAILBOX." c ON a.module_name = c.module_name AND a.module_id = c.module_id LEFT JOIN ".TBL_USER." d ON a.sent_by_user_id = d.user_id WHERE group_id = '$row_message[group_id]' ORDER BY unixtime DESC");
                   while($row = $this->db->fetch_assoc($result)){
                          $return[] = $row;
                   }
           
           }
           return $return;
       
        }
       
        
       
        function get_email_address( $mid='' ){
          $result = $this->db->query("SELECT * FROM ".EML_ADDRESS." WHERE mid = '$mid'");
          $return = array();
          while($row=$this->db->fetch_assoc($result)){
                  $return[] = $row;
          }
     
          return $return;
        }
		
	function check_mid_by_group_id( $mid='', $group_id='' ){
          $result = $this->db->query("SELECT mid FROM ".EML_MESSAGE." WHERE group_id = '$group_id' ORDER BY unixtime DESC");
          $row=$this->db->fetch_assoc($result);
		  $true = true;
		  $false = false;
          if( $row["mid"] == $mid ) return $true;
          else return $false;
        }
       
	function get_fct_by_id( $mid='' ){
	   $result = $this->db->query("SELECT b.name FROM ".ASSIGN_FCT." a LEFT JOIN ".GLOBAL_TASK." b ON a.flow_chart_id = b.global_task_id WHERE a.module_id = '$mid' and a.module = 'email' and a.task_status = 'Active'");
	   $return = array();
	   while($row=$this->db->fetch_assoc($result)){
			  $return[] = $row;
	   }
     
      return $return;
       
     }
       
	function get_user_by_id( $module_id='' ){
	    $result = $this->db->query("SELECT * FROM ".TBL_USER." WHERE user_id = '$module_id'");
        $return = array();
        while($row=$this->db->fetch_assoc($result)){
              $return[] = $row;
        }
     return $return;
	 }
       
         function display_message( $mid='' ){
         
         
         
         
         }
         
         function set_archive( $mid='', $group_id='' ){
		    if( $group_id == 0 ){ $option = 'mid'; $id = $mid; }
			elseif( $group_id != 0 ){ $option = 'group_id'; $id = $group_id; }
			
            $archive = array('active' => 1 );
            $this->db->update(EML_MESSAGE, $archive, $option, $id );
			//return '<div>ma</div>';
         }
         
         function get_dropdown( $table_name='', $field_name='' , $overide = array()){
             $options['selected'] = '';
             foreach($overide as $n => $v ){
                 $options[$n] = $v;
             }
            $sql = "SELECT * FROM $table_name WHERE `active` = 1";
                if( $field_name ) $sql .= " and option_name = '$field_name'";
            $result = $this->db->query($sql,__FILE__,__LINE__);
                $return = array();
                while( $row = $this->db->fetch_assoc($result) ){
                    $return[] = $row;
                }
            return $return;
         }
         
         function array2options( $array, $valKey, $displayKey, $overide = array() ){
            $return = '';
            $options = array();
            $options['second_val'] = '';
            $options['selected'] = '';
            foreach( $overide as $n => $v){
                $options[$n] = $v;
            }
                foreach( $array as $option ){
                    
				   if( ( $option["name"] == 'Active' && $options['selected'] == '' ) ){
					   $select= "SELECTED";
				    } else { $select = ''; }
                                    if( $option[$valKey] == $options['selected'] || $option[$displayKey] == $options['selected'] ){
                                        $select= "SELECTED";
                                    }

//CTLTODO: Fix this, I was in a rush and had to fix the crap code quickly
		   if( $options['second_val'] != '' ){
                       $sv = " " . $option[$options['second_val']];
                   } else {
                       $sv = '';
                   }
                   $return .= '<option value="' . $option[$valKey] . '" ' . $select . ' >' .  $option[$displayKey] . $sv .  '</option>';
                }
            return $return;
         }
         
         function display_ccbox(){
            echo 'cc';
         
         }
		 
		 function check_last_email( $last_mailbox='' , $last_host='' ){
            if( $last_host == 'eapi.com' ){
                return 'internal';
            } else {
                return 'external';
             }
          } //end of function check_last_email
	function check_global_email(){
             if( is_array($this->server_hosts) != true ){
                $this->server_hosts=array();
                //CTLTODO Make this use module name and address
                $result2 = $this->db->query("SELECT email_address FROM eml_mailboxs " , __LINE__ , __FILE__ );
                while( $row2 = $this->db->fetch_assoc($result2)){
                    $this->server_hosts[$row2['email_address']] = $row2['email_address'];
                }
            }
            return print_r( $this->server_hosts , true );
        }	 
        
        function check_status_by_mid( $mid='', $group_id='' ){
            $this->check_global_email();
            if( $group_id != 0 ){
                    $option = 'group_id';
                $option_id = $group_id;
                } else {
                    $option = 'mid';
                    $option_id = $mid;
                }
                    //echo "SELECT mid FROM ".EML_MESSAGE." WHERE $option = '$option_id' ORDER BY unixtime DESC";
            $result = $this->db->query("SELECT `from_mailbox`, `from_host`, `read` FROM ".EML_MESSAGE." WHERE $option = '$option_id' ORDER BY unixtime DESC");
                $row=$this->db->fetch_assoc($result);
//                return print_r($row , true ) . print_r($this->server_hosts , true );
                if( $row["read"] == 0 ){ return 'unread'; }
                elseif( ($row["read"] == 1) and ( array_key_exists( $row["from_mailbox"] . "@" . $row["from_host"] , $this->server_hosts  ) == true ) ){
                    //CTLTODO: REMOVE THIS FROM MAIN BUILD, eapi CUSTOM CODE
                    if( $this->db->num_rows($this->db->query("SELECT * FROM eml_address WHERE mid='$mid' AND host = '" . $row['from_host'] . "' AND mailbox = '" . $row['from_mailbox']. "' AND source = 'TO'")) == 0 ){
                        return 'outbound';                
                    } else {
                        return 'inbound';
                    }
                    //END OF CUSTOM CODE
                }
                
                else { return 'inbound'; }
        }
         function remove_active_email( $mid , $user_id = '' ){
             if( $user_id == ''){
                 $user_id = $_SESSION['user_id'];
             }
             $this->db->query("DELETE FROM eml_open WHERE mid='$mid' AND user_id='$user_id'");
         }
         function set_active_email( $mid , $user_id = ''){
             if( $user_id == ''){
                 $user_id = $_SESSION['user_id'];
             }
             $this->db->query("INSERT INTO eml_open (`mid` , `user_id` ) VALUES('$mid' , '$user_id') ON DUPLICATE KEY UPDATE user_id = '$user_id' ");
             
         }
         
         function display_email_list_by_module( $module_name='' , $module_id='' , $overide=array() ){
             ob_start();
             $emails = $this->get_email_by_modules( $module_name , $module_id , $overide );
             $jquery_mouseover = "$('.display_email_list').removeClass('light_blue');$(this).addClass('light_blue');";
             if( count( $emails)!= 0 ){
             ?> 
           <table style="margin: 5px; padding: 5px;">
               <tr>
                   <th>Date</th>
                   <th>From</th>
                   <th>Subject</th>
               </tr>
           <?php
             }
             foreach( $emails as $e ){ ?>
               <tr onmouseover="$('.display_email_list').removeClass('light_blue');$(this).addClass('light_blue');"
                   onmouseout="$(this).removeClass('light_blue');"
                   class="display_email_list" onclick="email_client.open_flyout('<?php echo $e['mid']; ?>')" >
                   <td style="margin-left: 5px;margin-right: 5px; padding-left: 5px;padding-right: 5px;" ><a onclick="email_client.open_flyout('<?php echo $e['mid']; ?>')"  ><?php if( date('ymd') == date('ymd' , $e['unixtime'])){ echo date('H:i' , $e['unixtime']);} else { echo date("Y-m-d" , $e['unixtime']); } ?></a></td>
                   <td style="margin-left: 5px;margin-right: 5px; padding-left: 5px;padding-right: 5px;" ><a onclick="email_client.open_flyout('<?php echo $e['mid']; ?>')"  ><?php echo $e['from_mailbox'] . "@" . $e['from_host']; ?></a></td>
                   <td style="margin-left: 5px;margin-right: 5px; padding-left: 5px;padding-right: 5px;" ><a onclick="email_client.open_flyout('<?php echo $e['mid']; ?>')"  ><?php  if( strlen($e['subject']) > 25 ){ echo substr($e['subject'], 0, 22 ) . "...";} else { echo $e['subject']; } ?></a></td>
               </tr>      
      <?php    }
        ?> </table> <?php
             $html = ob_get_contents();
             ob_end_clean();
             return $html;
         }
         function cache_message($mid_arr){
             if( array_key_exists($mid_arr['mid'], $this->cache['message'] ) == false ){
                 $this->cache['message'][$mid_arr['mid']] = $mid_arr;
             }
         }
         function get_message( $mid ){
             if( array_key_exists($mid, $this->cache['message'] ) == true ){
                 return $this->cache['message'][$mid];
                 
             } else {
                 $mid_arr = $this->db->fetch_assoc($this->db->query("SELECT `mid`, `subject`, `body`, `encoding`, `unixtime`, `importetime`, `from_displayname`, `from_mailbox`, `from_host`, `read`, `active`, `module_name`, `module_id`, `group_id`, `owner_user_id`, `imap_id`, `sent_by_user_id`  FROM eml_message WHERE mid='$mid'"));
                 $this->cache['message'][$mid] = $mid_arr ;
                 return $mid_arr;
             }
         }
         function display_email_by_module( $module_name='' , $module_id='' , $overide=array() ) {
             $orig_overide = $overide;
             $overide = array();
             foreach($orig_overide as $n => $v){
                 $overide[$n] = $v;
             }
           $start_time = strtotime('NOW') + microtime();
           $tmp_time = $start_time;
           $ta = array();
           ob_start();
           //define("TESTEMAIL" , true);
           //print_r($overide);
           //email_pagation
           //$overide['pagation'] = false;
           $now = strtotime('NOW') + microtime();
           $ta[] = array('total' => $now - $start_time ,'from_last' => $now - $tmp_time , 'line' => __LINE__ );
           $tmp_time = $now;
           
           $emails_data = $this->get_email_by_modules( $module_name , $module_id , $overide );
           
           $now = strtotime('NOW') + microtime();
           $ta[] = array('total' => $now - $start_time ,'from_last' => $now - $tmp_time , 'line' => __LINE__ );
           $tmp_time = $now;
           
           $emails = $emails_data['data']; //['data'];
//           echo $emails_data['sql'];
           //$emails = $emails_data;
             //var_dump( $emails);       
           //$emails = $this->get_email_details();
           //print_r($emails);
           //1 of 100
           $page = $overide['page'] . " of " . $emails_data['page_count'];
           $ta[] = array('time' => (strtotime('NOW') + microtime()) - $start_time , 'line' => __LINE__ );
           
           ?>
           <script>
               function run_on_email_load(){
                   $('.email_pagation').html('<?php echo $page; ?>');
                   a_default_search.page_count = '<?php echo $emails_data['page_count']; ?>';
               }
           </script>
           <div style="width: 100%;" >
           <?php
           $x = 0;
            foreach( $emails as $option ){
                $now = strtotime('NOW') + microtime();
           $ta[] = array('total' => $now - $start_time ,'from_last' => $now - $tmp_time , 'line' => __LINE__ );
           $tmp_time = $now;
                ?>
           <table class="emaildashboard_list">
           <?php
                $x++;
               if($x&1) {
                $xeo = "odd";
                } else {
                $xeo = "even";
                }
			  $owner_div_id = 'leftpanel_owner_'.$option["mid"];

			  if( $option["group_id"] != 0){ $check_parent = $this->check_mid_by_group_id( $option["mid"], $option["group_id"] ); }
			  if( $option["group_id"] == 0 or $check_parent == true ){
			?>
				 <tr class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item email_item_mid_<?php echo $option['mid']; ?>"  >
				   <td class="checkbox_td" style="width: 20px;" ><?php
                                          //This allows me to get the mid from jquery
                                       ?><div style="display: none; width: 0px; height: 0px;" class="mid" ><?php echo $option['mid']; ?></div>
                                       <input class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item email_item_checkbox" type="checkbox" name="check" id="check" onchange="email_client.message_checkbox( this , '<?php echo $option['mid']; ?>')"/>
				   </td>
				   <td class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item" style="width: 20px;" >
                   		<div title="<?php $option["sent_by_user_id"]; ?>" class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item" id="message_status_<?php echo $option["mid"]; ?>"><?php echo $this->displayStatusImage($option["mid"], $option["group_id"]); ?></div>
				   </td>
				   <td class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item" style="">
					 <table class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item emaildashboard_sublist" width="100%"
                        onclick="javascript:
                                email_client.message_click( this , { mid:  '<?php  echo $option['mid']; ?>', group_id: '<?php echo $option["group_id"]; ?>'} );">
					   <tr>
<!--						 <td width="5%" style="background: yellowgreen;" class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item" >&nbsp;</td>-->
						 <td colspan="4" style="" class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item" >
						    <span id="<?php echo $owner_div_id; ?>" class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item" >
							  <?php
							   /*if( $option["owner_user_id"] != 0 ){ 
								   $user_group = $option["first_name"].' '.$option["last_name"];
								} else { 
								   $user_group = 'Unclaimed';
								  }*/
								echo $this->displayUserModuleLink( $option["mid"], $owner_div_id );
								//echo $this->displayUserModuleLink("TBL_USER" , $option["owner_user_id"] , $user_group, $option["mid"]); ?>
							</span>
						   <span class="emaildashboard_menutitle" ><?php
                                                   if( strlen( $option["from_displayname"]) > 25 ){
                                                        echo substr($option["from_displayname"], 0 , 22) . "..."; 
                                                    } else {
                                                        $option["from_displayname"];
                                                    }
                                                   //echo $option["from_displayname"]; 
                                                   
                                                   ?></span>
						 </td>
<!--						 <td width="20%" class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item" ><div class="emaildashboard_menudate" ><?php echo date("m/d/y", $option["unixtime"]); ?></div></td>-->
						 <td width="20%" class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item" style="" ><div class="emaildashboard_menutime" ><?php if( date("myd") == date("myd" , $option["unixtime"] )){ echo date("h:ia", $option["unixtime"]); } else { echo date("m/d/y", $option["unixtime"]); } ?></div></td>
					   </tr>
					   <tr>
						 <td width="5%"  class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item" >&nbsp;</td>
                                                 <td colspan="2"  class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item" ><div class="emaildashboard_menusubject" ><?php if(strlen($option["subject"]) < 33 ){ echo $option["subject"]; } else { echo substr($option["subject"], 0 , 30 ) . "...";}?></div></td>
						 <td align="center" style="width: 20px;" class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item" >
						   <a href="javascript:void(0);"
								onclick="javascript: var get_info = get_mail_info();
								
										 emaildash.set_archive('<?php echo $option["mid"]; ?>',
										                       '<?php echo $option["group_id"]; ?>',
													{preloader:'prl',
													onUpdate: function(response,root){
													emaildash.display_email_by_module('<?php echo $module_name; ?>',
																					  '<?php echo $module_id; ?>',
																					  get_info,
														   
																{preloader:'prl',
																onUpdate: function(response,root){
																document.getElementById('show_info').innerHTML=response;
													 }});	
												}});"> <img src="images/trash_can.png" alt="delete" /> </a>
						 
						     
						 </td>
						 <td style="" width="20%" class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item"  >
                         	<table style="width: 100%;">
                            	<tr class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item" >
                                	<td class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item">
                                    <div id="emaildashboard_flags_<?php echo $option["mid"]; ?>">
                                     <?php echo $this->flags->display_flags_by_module( 'EMAIL', $option["mid"], $email_detail=array("email_details") ); ?>
                                   </div>
                                   </td>
                                   <td class="email_item_<?php echo $x;?> email_item_<?php echo $xeo;?> email_item" style="text-align: left;width:21px;" >
                                    <div class="fct_image">
                                      <?php //echo $this->FlowChartDiv( 'email' , $option["mid"] , 'email_list');
                                      
                                      //$check_fct = $this->check_fct_by_id( $option["mid"] );
                                      $case_arr = $this->cases->search_cases( array('module_name' => 'EMAIL' , 'module_id' => $option['mid']) , array('glue' => ' AND '));
                                      $result = $this->db->query("SELECT * FROM cases_activity WHERE module_name = 'EMAIL' AND module_id = '" . $option['mid'] . "'");
                                      //var_dump( $case_arr );
                                      if( count( $case_arr ) != 0 || mysql_num_rows($result) != 0 ){ ?>
                                          <img title="<?php echo mysql_num_rows($result); ?>" src="images/case.png" alt="FCT"  />
                                      <?php } ?>
                                    </div>
                                   </td>
                                </tr>
                             </table>
					     </td>
					   </tr>
					  </table>
				   </td>
				 </tr>
	   <?php }
           ?>
          </table> 
  <?php         } ?>
            </div>
           <?php
            $now = strtotime('NOW') + microtime();
           $ta[] = array('total' => $now - $start_time ,'from_last' => $now - $tmp_time , 'line' => __LINE__ );
           $tmp_time = $now;
           
//           foreach($ta as $v){
//               echo "<div>" . $v['from_last'] . "<br/>" . $v['line'] . "<br/>" . $v['total'] . "</div>";
//           }
          // var_dump($ta);
                $html=ob_get_contents();
                ob_end_clean();
                return $html;
                               
        }//////end of function display_email_by_module
		
		function check_fct_by_id( $module_id='' ){
		   $true = true;
		   $false = false;
		   $result = $this->db->query("SELECT chart_assign_id FROM ".ASSIGN_FCT." WHERE module_id = '$module_id' and module = 'email'");
           if( $this->db->num_rows($result) > 0 ) return $true;
		   else return $false;
		}
       
	    function displayStatusImage($mid='' , $group_id=0){
		   ob_start();
		   
		   $check_read_unread = $this->check_status_by_mid( $mid, $group_id );
		   
		   if( $check_read_unread == 'unread' ){
			   $image_name = 'unread_mail.png';
		   } 
		   elseif( $check_read_unread == 'outbound' ){
			   $image_name = 'outbound_mail.png';
		   } 
		   elseif( $check_read_unread == 'inbound' ){
			   $image_name = 'inbound_mail.png';
		   }
		   ?><img title="hover text" src="images/<?php echo $image_name; ?>" alt="unread mail" /><?php 
	   	   $html=ob_get_contents();
		   ob_end_clean();
		   return $html;		   		
		}
	   
        function check_to( $mid='', $options=array() ){
       
           if( $options[1] == 'LEFT PANEL' ){
               $to = '';
                   $result = $this->db->query("SELECT from_mailbox, from_host FROM ".EML_MESSAGE." WHERE group_id = '$options[0]' ORDER BY unixtime DESC");
                   while( $row = $this->db->fetch_assoc($result) ){
                           $to = $row[from_mailbox].'@'.$row[from_host];
                        }
                        return $to;
                }
           else {
                   $address = $this->get_email_address( $mid );
//                   var_dump( $address );
                   //print_r($address);
                   $option = '';
                   
                   foreach( $address as $option_add ){
                          $mail_to_from = $option_add["mailbox"].'@'.$option_add["host"];
                         
                          if( $option_add["source"] == 'FR' ){ $original_from = $option_add["mailbox"].'@'.$option_add["host"]; }
                         
                          if( $mail_to_from == $options[0] ){  $option = $option_add["source"];
                                 
                          } else { $address_to .= $option_add["mailbox"].'@'.$option_add["host"].','; }
                         
                   }
//                   echo $original_from . ":" . $option . ":" . $options[1];
//                   var_dump( $address_to);
                        //echo $option.'<>'.$address_to;
                        if( $options[1] == "REPLY ALL"){
                            $result = $this->db->query("SELECT * FROM eml_address WHERE mid='$mid'");
                            $ta = array();
                            while( $row = $this->db->fetch_assoc($result)){
                                  if($row['mailbox'] . '@' . $row['host'] != $options['from'] ){
                                    $ta[] = $row['mailbox'] . '@' . $row['host'];
                                  }
                                
                            }
                            return implode(',' , $ta );
                        }
                        if( ( $option == 'FR' ) or ( $option == 'TO' and $options[1] == 'REPLY ALL' ) ) return $address_to;
                       
                        if( $options[1] == 'REPLY' ) return $original_from;
                }
           
        }
       
        function get_email_addr_by_mailbox( $mailbox ){
            $eml = $this->db->fetch_assoc($this->db->query("SELECT email_address FROM eml_mailboxs WHERE mailbox_id = '$mailbox'"));
            return $eml["email_address"];
        }
        
        function save_signature_by_module($module_name , $module_id, $text ){
            $result = $this->db->query("SELECT signature FROM eml_signature WHERE module_name = '$module_name' AND module_id = '$module_id'");
                if($this->db->num_rows($result) != 0 ){
                    $text = addslashes($text);
                    $this->db->query("UPDATE eml_signature SET signature = '$text' WHERE module_name = '$module_name' AND module_id = '$module_id'");

                } else {
                    $insert = array();
                    $insert['module_name'] = $module_name;
                    $insert['module_id'] = $module_id;
                    $insert['signature'] = $text;
                    $this->db->insert('eml_signature', $insert, false, '', '', 0);
                }
        }
        function get_signature_by_module( $module_name , $module_id ){
            ob_start();
                $result = $this->db->query("SELECT signature FROM eml_signature WHERE module_name = '$module_name' AND module_id = '$module_id'");
                if($this->db->num_rows($result) != 0 ){
                    $sa = $this->db->fetch_assoc($result);
                    echo $sa['signature'];
                }
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
        
        
        
         function display_mail_content( $mid='', $panel='', $options='' , $overide=array() ) {
             $rand_id = rand(0,99999999999999);
             $md5_rand_id = md5($rand_id . $mid . $panel . $options);
             
             $object = array();
             foreach( $overide as $n => $v){
                 $object[$n] = $v;
             }
           ob_start();
           $read_only = false;
           if(strtolower( str_replace( " ", '', $panel)) == 'leftpanel' ){
              $read_only = true; 
           } 
		   if( $options == 'COMPOSE' ){
		       $email_content = $this->get_user_by_id( $mid );
                       $email_content[0]["email_address"] = $this->get_email_addr_by_mailbox(1);
			   $mail_option = 'COMPOSE';
			} else {
		       $email_content = $this->get_email_by_mid( $mid );
			   $mail_option = 'LEFT PANEL';
			}
			
		   if( $panel == 'emaildashboard' ){ $all_mail_div = 'email_content'; }
		   else{ $all_mail_div = 'content_left_panel'; }
		   
		   //print_r($email_content);
                   if( $read_only != true ){
           ?>
           <script>     
			function fileAttachment(){
				$(function() {
					$(".custom_file_upload_<?php echo $panel.'_'.$mid.'_'; ?>").uploadify({
                                                                "wmode": 'transparent',
								"uploader"       : "./upload_attachment/uploadify.swf",
								"script"         : "./uploadify.php?rand=<?php echo $md5_rand_id;?>",
								"cancelImg"      : "./upload_attachment/cancel.png",
								"folder"         : "<?php echo $md5_rand_id;?>",
								"multi"          : true,
								"auto"           : true,
								"queueID"        : "custom-queue_<?php echo $panel.'_'.$mid.'_'; ?>",
								"queueSizeLimit" : 3,
								"simUploadLimit" : 3,
                                                                "buttonImg"     : './images/uploadify_icon2.png',
								"removeCompleted": false,
								"onSelectOnce"   : function(event,data) {},
								"onAllComplete"  : function(event,data) {},
                                                                "onCancel" : function(event,ID,fileObj,data){
                                                                    alert( $.ajax('uploadify.php?rand=<?php echo $rand_id;?>&action=remove&filename=' + fileObj.name) );
                                                                }
					});				
				});	
			 }			
		          		  
			 function set_mail_to(to,cc,bcc){
				if(cc != '' && bcc == '') {
						return to_all = to + ',' + cc; 
				}
				else if(bcc != '' && cc == '') {
						return to_all = to + ',' + bcc; 
				}
				else if(cc != '' && bcc != '') {
						return to_all = to + ',' + cc + ',' + bcc;     
				}
				else {
						return to_all = to;
				}
			 }
           </script>
           
           <div id="compose_mail_div_<?php echo $panel; ?>">
               <?php// echo $panel . ":" . $read_only . ":" . $options . ":" . print_r($overide,true);//$mid='', $panel='', $options='' , $overide=array() ?>
             <form name="form" enctype="multipart/form-data" onsubmit="return false;">
                 <?php if(strtolower(str_replace(" ", "", $options)) != 'leftpanel') { ?>
                 <table class="emaildashboard_compose emaildashboard_compose_<?php echo strtolower(str_replace(" ", "", $panel)); ?>_<?php echo strtolower(str_replace(" ", "", $options)); ?>" width="100%" height="425px">
                   <?php
					foreach( $email_content as $option ){
					
					   $from = $option["from_mailbox"].'@'.$option["from_host"];
                                           if(array_key_exists("email_address", $option)){
                                               $from = $option['email_address'];
                                           }
					   $owner_div_id = $panel.'compose_owner_'.$option["mid"];
					   
					   if( $options == 'COMPOSE' ){ $from_email_address = $option["email_address"]; }
					   else{ $from_email_address = $option["email_address"]; }
			       ?>
					   <tr>
						  <th>&nbsp;</th>
						  <td>
							<span id="<?php echo $owner_div_id; ?>">
							   <?php //if( $options != 'COMPOSE' ) echo $this->displayUserModuleLink( $option["mid"], $owner_div_id ); ?>
							</span>
							<em class="emaildashboard_group">
							  <?php //echo $this->display_case_by_mid( $option["mid"], '' ); ?>
							</em>
						  </td>
					   </tr>
                                           <td colspan="2"><?php echo $this->display_templates( 'TBL_USER' , $_SESSION['user_id'] ); ?></td>
					   <tr>
						  <th><em class="FR">From :</em></th>
						  <td>
						   <input class="left_<?php echo $panel; ?>" type="text" name="from_<?php echo $panel; ?> email_autocomplete"  id="from_<?php echo $panel; ?>" 
						          value="<?php echo $from_email_address; ?>" <?php if( $options != 'COMPOSE' ) echo 'readonly="true" disabled'; ?> />
						  </td>
					   </tr>
					   <tr>
						  <th><em class="TO">To :</em></th>
						  <td>
							<input class="left_<?php echo $panel; ?> email_autocomplete" type="text" name="to_<?php echo $panel; ?>"  id="to_<?php echo $panel; ?>"
                            value="<?php 
                                                                if( $options == 'LEFT PANEL' ){ if( $option["group_id"] == 0 ) echo $from; else echo $this->check_to( $mid, $options = array( $option["group_id"], 'LEFT PANEL' ) );  }
                                                                if( $options == 'REPLY' ){ echo rtrim( $this->check_to( $mid, $options = array( $option["email_address"], 'REPLY' ) ), ',' ); }
								if( $options == 'REPLY ALL' ){ echo rtrim( $this->check_to( $mid, $options = array( $option["email_address"], 'REPLY ALL' , 'from' => $from_email_address ) ), ',' ); }
								if( $options == 'FORWARD' or $options == 'COMPOSE' ){ 
                                                                        if(array_key_exists( 'module_name', $object) == true && array_key_exists( 'module_id', $object) == true){
                                                                            //echo $object["module_name"] . $object["module_id"];
                                                                            echo $this->page->get_email_addr_by_module($object["module_name"], $object["module_id"]);
                                                                            //var_dump( $this->page );
                                                                            //echo $this->page->eapi_account->search_account( $object['module_id'] );
                                                                            //echo $this->page->get_email_addr_by_module();
                                                                            
                                                                        }
                                                                    } 
                                                                  ?>"  /> <?php //" ?>
						  </td>
					   </tr>
                                           <?php $rand_id = rand(1, 99999999); ?>
					   <tr id="mail_ccbox_<?php echo $panel; ?>" >
						  <th><div class="email_cc email_cc<?php echo $rand_id;?>" style="display:none;" >Cc:</div></th>
						  <td><div class="email_cc email_cc<?php echo $rand_id;?>" style="display:none;">
							<input type="text" name="cc_<?php echo $panel; ?>" id="cc_<?php echo $panel; ?>" value="" class="left_<?php echo $panel; ?> email_autocomplete" />
                                                      </div></td>
					   </tr>
					   <tr id="mail_bccbox_<?php echo $panel; ?>" >
						  <th><div class="email_bcc email_bcc<?php echo $rand_id;?>" style="display:none;" >Bcc:</div></th>
						  <td><div class="email_bcc email_bcc<?php echo $rand_id;?>" style="display:none;" >
							<input type="text" name="bcc_<?php echo $panel; ?>" id="bcc_<?php echo $panel; ?>" value="" class="left_<?php echo $panel; ?> email_autocomplete" />
                                                      </div></td>
					  </tr>
					  <tr>
						  <th>&nbsp;</th>
						  <td class="emaildashboard_cc_bcc">
							  <em id="mail_cc_<?php echo $panel; ?>">
								 <a href="javascript:void(0);"
									   onclick="javascript: $('.email_cc<?php echo $rand_id;?>').show(); document.getElementById('mail_ccbox_<?php echo $panel; ?>').style.visibility='visible';
															document.getElementById('mail_cc_<?php echo $panel; ?>').style.display='none';">add cc</a> | </em>
							  <em id="mail_bcc_<?php echo $panel; ?>">
								 <a href="javascript:void(0);"
									   onclick="javascript: $('.email_bcc<?php echo $rand_id;?>').show();document.getElementById('mail_bccbox_<?php echo $panel; ?>').style.visibility='visible';
															document.getElementById('mail_bcc_<?php echo $panel; ?>').style.display='none';">add bcc</a> | </em>
						   
							  Attach a file:
							  <div id="custom-queue_<?php echo $panel.'_'.$mid.'_'; ?>"></div>
							  <input id="custom_file_upload_<?php echo $panel.'_'.$mid.'_'; ?>" type="file" name="Filedata" class="left_<?php echo $panel; ?> custom_file_upload_<?php echo $panel.'_'.$mid.'_'; ?>" />						
						  </td>
					  </tr>
					  <tr>
						  <th><em class="SUB">Subject:</em></th>
						  <td>
								<input <?php if( $options != 'COMPOSE') echo 'disabled'; ?> type="text" name="subject_<?php echo $panel; ?>" id="subject_<?php echo $panel; ?>" value="<?php if( $options != 'COMPOSE') echo 'Re:'.$option["subject"]; ?>" class="left_<?php echo $panel; ?> email_subject" />
						  </td>
					  </tr>
					  <tr>
						  <th>&nbsp;</th>
						  <td>
							  <textarea name="message_<?php echo $panel; ?>" id="message_<?php echo $panel; ?>" class="left_<?php echo $panel; ?> mceeditor_500 email_body" ><?php 
                                                          if( $options != 'LEFT PANEL' && $options != 'COMPOSE' ){  ?>
                                                             <p><br/><br/><br/><br/></p>
                                                             <p class="comment_upper_line" ><em><span style="color: #999999;" >-----------------</span></em></p>
                                                             <div class="comment_text" >
                                                             <p><em><span style="color: #999999;">From: <?php echo $option['from_mailbox'] . '@' . $option['from_host']; ?></span></em></p>
                                                             <p><em><span style="color: #999999;">Sent On: <?php echo date('Y-m-d H:i:s' ,  $option['unixtime']); ?></span></em></p>
                                                             <p><em><span style="color: #999999;">Subject: <?php echo $option['subject'] ; ?></span></em></p>
                                                             <div style="color: #999999 !important;">
                                                             <?php
                                                             //CTLTODO Implement into a function
                                                             //Possibly look at parcing it with dom
                                                             //http://www.php.net/manual/en/book.dom.php
                                                             $except = array( ); // declare your exceptions 
                                                                $allow = implode($except, '|'); 
                                                                $regexp = '@([^;"]+)?(?<!'.$allow.'):(?!\/\/(.+?)\/)((.*?)[^;"]+)(;)?@is'; 
                                                                $out =  $option["body"];
                                                                $out = strip_tags($out, "<br><p><a><div><span>");
//                                                                 $out = preg_replace('/<html*>/' , '' , $out);
//                                                                $out = preg_replace('/<\/html*>/' , '' , $out);
                                                                $out = preg_replace($regexp, '', $out );
//                                                                $regexp2 = '@([^;"]+)?(?<!color):(?!\/\/(.+?)\/)((.*?)[^;"]+)(;)?@is';
//                                                                $out = preg_replace($regexp2, 'color: #999999;', $out ); 
//                                                                $out = preg_replace('@[a-z]*=""@is', '', $out);
                                                               
                                                                echo $out;
                                                             ?>
                                                              </div>
                                                             <p><em><span style="color: #999999;">-----------------</span></em></p>
                                                             </div>
                                                               <?php  } 
                                                          
                                                          ?></textarea>
						  </td>
					  </tr>
                                          <tr>
                                              <th>Signature:</th>
                                              <td><div id="signature_div" ><?php 
                                              //CTLTODO: Change to dynamic for muliple mailbox support
                                              echo $this->get_signature_by_module( 'MAILBOX' , '1' );
                                              ?></div></td>
                                          </tr>
					  <tr>
						<td>&nbsp;</td>
                        <td ><table><?php } ?><tr><td style="width: 400px;">
                          <label for="email_compose_personal_<?php echo $rand_id; ?>">  Use personal signature </label><input name="email_compose_personal_<?php echo $rand_id; ?>" type="checkbox" id="email_compose_personal_<?php echo $rand_id; ?>" onchange="if( $(this).ctl_checked() == false ){ emaildash.get_signature_by_module( 'MAILBOX' , '1' , { target: 'signature_div'} ); } else { emaildash.get_signature_by_module( 'TBL_USER' , '<?php echo $_SESSION['user_id']; ?>' , { target: 'signature_div'} ); }" />
                                    </td><td>
                            <button type="button" name="send" id="send" value="send"
						       onclick="javascript: var to_mail_all = set_mail_to(document.getElementById('to_<?php echo $panel; ?>').value, 
							   														document.getElementById('cc_<?php echo $panel; ?>').value, 
																					document.getElementById('bcc_<?php echo $panel; ?>').value);
												   var file_array = new Array();
												   <?php $i=0; 
									               $result_attached_file = $this->getFilePath($mid);		   
												   foreach( $result_attached_file as $row_attached_file) { ?>
												   		file_array[<?php echo $i; ?>] = '<?php echo $row_attached_file;?>';<?php
													  $i++;
												   }
												   if( $options != 'COMPOSE' ){
													   $mail_module_name = $option["module_name"];
													   $mail_module_id = $option["module_id"];
												   }
												   else {
													   $mail_module_name = 'TBL_GROUP';
													   $mail_module_id = '1';
													}
												   
												   ?>
												   smtp.send_email('<?php echo $mail_module_name; ?>',
																   '<?php echo $mail_module_id; ?>',
																   to_mail_all,
																   document.getElementById('from_<?php echo $panel; ?>').value,
																   document.getElementById('subject_<?php echo $panel; ?>').value,
																   tinyMCE.activeEditor.getContent() + '<br/>' + $('#signature_div').html(),
																   '<?php echo $md5_rand_id;?>',
																   { parrent_mid: '<?php echo $mid; ?>'},
																   {onUpdate:function(response,root){
                                                                                                                                            slimcrm.log( response );
																		preloader:'prl',
																		emaildash.display_mail_content('<?php echo $mid; ?>',
																									   '<?php echo $panel; ?>',
																									   '<?php echo $mail_option; ?>',
																		  {preloader:'prl',
																		   onUpdate: email_client.display_compose
                                                                                                                                                    });
																		
																		emaildash.updateFileStatus('<?php echo $mid; ?>',{});
                                                                                                                                                $('#tab_left_panel').click();
															}});" >send<div class="send_button in_button">&nbsp;</div></button> <?php //" ?>
                        </td></tr></table></td>
				  </tr>
				  <?php } //if( $options != 'COMPOSE' ) echo '}'; ?>
                        </table>
                        </form>
           </div>
           <?php 
           
           }
           
           if( $options != 'COMPOSE' ){ echo $this->get_email_details( $mid, $panel ); }
		   
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
                               
        }//////end of function display_mail_content
	
	 function getFilePath($mid){
         $return = array();
		 $result_attached_file = $this->db->query("Select * from ".EML_FILES." where mid = '$mid' and upload_status ='sending'");		   
	     while( $row_attached_file = $this->db->fetch_assoc($result_attached_file) ){
		 	$return[] = $row_attached_file['filepath'];
		 }	
		 return $return; 
	 }	
	 	
	 function updateFileStatus($mid){
	  	 $this->db->query("Update ".EML_FILES." set upload_status = 'sent' where mid = '$mid' and upload_status = 'sending'");				
	 }
	 function get_to_address($mid){
             $result = $this->db->query("SELECT mailbox,host FROM eml_address WHERE mid='$mid' AND source = 'TO'");
             $return = array();
             while($row=$this->db->fetch_assoc($result)){
                 $return[] = $row['mailbox'] . '@' . $row['host'];
             }
             return $return;
         }
         
         
     function get_email_details( $mid='', $panel='' ){
        $all_mail_div = 'content_left_panel'; 
	   
	   $address = $this->get_email_address( $mid );
	   //print_r($address);
	   foreach( $address as $option_add ){
		  if( $option_add["source"] == 'TO' ){
			  $address_name .= $option_add["mailbox"].'@'.$option_add["host"].',<br/>';
			   }
	   }
	   $fct = $this->get_fct_by_id( $mid );
	   foreach( $fct as $option_fct ){
		  $fct_name = $option_fct["name"];
	   }
	   
	   $all_mail = $this->get_mail_by_group( $mid );
	   //print_r($all_mail);
	   foreach( $all_mail as $option ){
	      $owner_div_id = $panel.'group_owner_'.$option["mid"];
		  $case_div = $panel.'case_group_'.$option["mid"];
	   //print_r($option);
        ?>
           
           <div id="emaildashboard_group_div">
             <table class="emaildashboard_group_table" width="100%">
			   <tr>
				 <td style="width: 30px;padding-left: 10px;">
				   <?php
					   $check_read_unread = $this->check_status_by_mid( $option["mid"], $option["group_id"] );
					   
					   if( $check_read_unread == 'unread' ){
					       $image_name = 'unread_mail.png';
					   } elseif( $check_read_unread == 'outbound' ){
					       $image_name = 'outbound_mail.png';
					    } elseif( $check_read_unread == 'inbound' ){
					       $image_name = 'inbound_mail.png';
					      }
					?>
					<img title="<?php  if( $option['sent_by_user_id'] != '' && $option['sent_by_user_id'] != '0' ){ echo $option["sent_by_firstname"] . " " . $option['sent_by_lastname'] ; } ?>" src="images/<?php echo $image_name; ?>" alt="unread mail" />
                                        
				 </td>
				 <td colspan="3" >
					 
				
							 <em id="emaildashboard_reply">
								   <a href="javascript:void(0);"
												 onclick="javascript: 
$('.emaildashboard_compose_emaildashboard').show();
emaildash.display_mail_content(
    '<?php echo $option["mid"]; ?>',
    '<?php echo $panel; ?>',
    'REPLY',
    {
        preloader:'prl',
        onUpdate: email_client.display_compose
    }
);"><button>reply <div class="reply_button in_button"/>&nbsp;</div></button></a>
							 </em>
							 <em id="emaildashboard_replyall">
							   <a href="javascript:void(0);"
											onclick="javascript: $('.emaildashboard_compose_emaildashboard').show();
													   emaildash.display_mail_content('<?php echo $option["mid"]; ?>',
																					  '<?php echo $panel; ?>',
																					  'REPLY ALL',
															{preloader:'prl',
															onUpdate: email_client.display_compose }
															);"><button>reply all <div class="reply_all_button in_button"/>&nbsp;</div></button></a>
							 </em>
							 <em id="emaildashboard_forward">
							   <a href="javascript:void(0);"
									  onclick="javascript: $('.emaildashboard_compose_emaildashboard').show();
													emaildash.display_mail_content('<?php echo $option["mid"]; ?>',
																				   '<?php echo $panel; ?>',
																				   'FORWARD',
														{preloader:'prl',
														onUpdate: email_client.display_compose}
													);"><button>forward <div class="forward_button in_button"/>&nbsp;</div></button></a>
							 </em>
                                     <span class="<?php echo $case_div; ?>">
					   <?php echo $this->display_case_by_mid( $option["mid"], $case_div ); ?>
					 </span>
						   </div>
                               </td>
                           </tr>
			   <tr>
				 <td colspan="4" class="emaildashboard_group_table_td">
					 <table class="emaildashboard_distinct_mail" width="100%">
					   <tr>
						 <td colspan="2">
						   <div class="emaildashboard_distinct_from">
							 <em class="FR">From: <?php echo $option["from_mailbox"].'@'.$option["from_host"];?></em>
						   </div>
						 </td>
						 <td align="right">
						   
						 </td>
					   </tr>
					   <tr>
						 <td colspan="2">
						   <div class="emaildashboard_distinct_to">
							 <em class="TO">To: 
                                                   <?php
                                                        $to_addrs = $this->get_to_address($option["mid"]);
                                                         echo rtrim( implode(',' , $to_addrs ),",<br/>");
                                                    ?></em>
						   </div>
						 </td>
						 <td align="right">
						   <div class="emaildashboard_distinct_time"><?php echo date("m/d/Y h:i:s A", $option["unixtime"]); ?></div>
						 </td>
					   </tr>
					   <tr>
						 <td colspan="3">
							   <div class="emaildashboard_distinct_subject">
								 <em class="SUB">Subject: <?php echo $option["subject"];?></em>
							   </div>
							 </td>
					   </tr>
					   <tr>
						 <td colspan="3">&nbsp;</td>
					   </tr>
					   <tr>
						 <td colspan="3">
						   <div class="emaildashboard_distinct_body"><?php echo $this->page->decode_text( $option['encoding'] , $option["body"] );?></div>
						 </td>
					   </tr>
					 </table>
				 </td>
			   </tr>
			   <tr>
				 <td>
				   <?php
					$file = $this->get_files_by_message( $option["mid"] );
				   ?>
				   <table class="emaildashboard_group_file_<?php echo $panel; ?>">
				   <?php foreach( $file as $files ){ ?>
					 <tr>
						   <td></td>
						   <td>
							 <b><?php echo $files["filename"]; ?></b><br/>
								 <span>
								   <a href="email_get_file.php?file_id=<?php echo $files["eml_file_id"]; ?>">DownLoad</a>
								 </span>
						   </td>
						 </tr>
				   <?php } ?>
				   </table>
				 </td>
			   </tr>
             </table>
           
        <?php }
        } //////end of function display_realted_mail_by_id
	
        function link_module_to_case( $module_name , $module_id , $case_id ){
            $insert = array();
            $insert['case_id'] = $case_id;
            $insert["module_name"] = $module_name;
            $insert["module_type"] = $module_name;
            $insert["module_id"] = $module_id;
            $this->db->insert('cases_activity', $insert);
            return '';
        }
        
        function display_link_to_case( $module_name , $module_id){
            ob_start();
            switch( strtolower($module_name) ){
                case "email":
                    $cases = array();
                    $eml = $this->db->fetch_assoc( $this->db->query("SELECT from_mailbox , from_host FROM eml_message WHERE mid='$module_id'"));
                    $emls = $this->db->query("SELECT mid FROM eml_message WHERE from_mailbox = '" . $eml["from_mailbox"] . "' AND from_host = '" . $eml["from_host"] . "' ");
                    $where_arr = array();
                    while($row = $this->db->fetch_assoc($emls)){
                        $where_arr[] = "( module_name = 'EMAIL' AND module_id = '" . $row["mid"]. "' )";
                    }
                    $where = "WHERE " . implode(" OR " , $where_arr );
                    $sql = "SELECT * FROM cases_activity $where";
                    //echo $sql;
                    $result = $this->db->query($sql);
                    while($row2 = $this->db->fetch_assoc($result)){
                        $cases[] = $row2['case_id'];
                    }
                    
                break;
            }
            ?>
               <script >
                   function link_cases_onload(){
               var link_cases = ['<?php echo implode( "','", $cases)?>'];
               $('#link_case_input_<?php echo $module_name . $module_id; ?>').autocomplete({ 
                    source: link_cases,
                    select: function( event , ui ){
                       
                       $(this).val( ui.item.value ); 
                    }});
                }
               </script>
               <input style="width: 100%;" id="link_case_input_<?php echo $module_name . $module_id; ?>" />
               <?php
            $html=ob_get_contents();
		ob_end_clean();
		return $html;
        }
        
        
		function display_case_by_mid( $mid='', $case_div='' ){
		   $case_tr = $case_div.'_tr';
		   $case_span_case = $case_div.'case_span';
		   $case_span_group = $case_div.'group_span';
		   
		   $cases = $this->get_case_by_mid( $mid, 'CASE' );
		   $groups = $this->get_case_by_mid( $mid, 'GROUP' );
		   //print_r($cases);
		   foreach( $cases as $case ){
				    $case_id .= $case["case_id"].',';
		    }
			foreach( $groups as $group ){
				     $group_id = $group["case_id"];
		    }
		   //return 'ma';
		   ob_start();
//		   echo '<em class="emaildashboard_group">Group:'. $group_id .' Case:'. rtrim($case_id,",");
		   if( $case_div != '' ){ 
		?>
                 <a href="javascript:void(0);" onclick="email_client.create_case('<?php echo $mid; ?>');">
                     <button >create case <div class="add_button in_button">&nbsp;</div></button></a>

                 <a href="javascript:void(0);" onclick="javascript: email_client.link_to_case( 'EMAIL' , '<?php echo $mid; ?>' );">
    <button>link to case<div class="add_button in_button" >&nbsp;</div> </button></a>
</em>
	  <?php }
	  $html=ob_get_contents();
	  ob_end_clean();
	  return $html;
		}
		
		function get_case_by_mid( $module_id='', $module_name='' ){
		   $result = $this->db->query("SELECT case_id FROM ".CASES_ACTIVITY." WHERE module_id = '$module_id' AND module_type = '$module_name'");
		   $return = array();
		   while($row=$this->db->fetch_assoc($result)){
		   $return[] = $row;
		   }
		   return $return;
		}
		
		function get_groups_by_cases(){
		    $result = $this->db->query("SELECT DISTINCT group_id FROM ".CASES." WHERE group_id <> '0'");
			$return = array();
			while($row=$this->db->fetch_assoc($result)){
			$return[] = $row;
			}
			return $return;
		}
		
		function set_case( $module_name='', $module_id='', $case_id='' ){
		   $update_sql_array=array();
		   $update_sql_array["case_id"] = $case_id;
		   $update_sql_array["module_name"] = 'EMAIL';
		   $update_sql_array["module_id"] = $module_id;
		   $update_sql_array["module_type"] = $module_name;
		   
		   $this->db->insert(CASES_ACTIVITY,$update_sql_array);
		   
		   //$this->db->update(CASES,$update_sql_array,'case_id',$case_id);
		}
       
        function get_files_by_message( $mid ){
			$result = $this->db->query("SELECT * FROM ".EML_FILES." where mid = '$mid' ");
			$return = array();
			while($row=$this->db->fetch_assoc($result)){
			$return[] = $row;
			}
			return $return;
        }

        function get_file_by_id( $file_id ) {
			$result = $this->db->query("SELECT * FROM ".EML_FILES." where eml_file_id = '$file_id' ");
			$row = $this->db->fetch_assoc($result);
		   
			$return = file_get_contents($row['filepath']);
		   
			return $return;
        }

        function get_fileinfo_by_id( $file_id , $type) {
			$result = $this->db->query("SELECT $type FROM ".EML_FILES." where eml_file_id = '$file_id' ");
			$row = $this->db->fetch_assoc($result);
			return $row[$type];
        }
    function get_default_flowchart( $module_name ){
        $da = $this->db->fetch_assoc($this->db->query("SELECT a.* , b.global_task_tree_id tree FROM `tbl_global_task_default` a LEFT JOIN tbl_global_task b ON a.global_task_id = b.global_task_id WHERE module_name = '$module_name'"));
        return $da;

    }
    function FlowChartDiv( $module_name , $module_id , $div_type , $overide = array() ){
                $disable_tree = false;
                if( $this->db->num_rows($this->db->query("SELECT * FROM cases WHERE case_id = '$module_id' AND Status = 'Completed' ",__LINE__,__FILE__)) != 0 ){
                    $disable_tree = true;
                }
                $rand = rand( 0 , 99999999999999);
                $module_name_true = $module_name;
                $module_name = str_replace( ' ' , '' , $module_name );
                ob_start();
                $div_type .= "_$rand";
                $global_task = new GlobalTask();
                ?>
                <div id="flowcharttask_<?php echo $div_type . '_' . $module_name . '_' . $module_id;?>">
                        <table >
                          <tr >
                                <td colspan="2">
                                    <a href="javascript:void(0);" 
                                       <?php 
                                       if( !$global_task->has_active_flowchart_task($module_name_true , $module_id )){ 
                                        $default_task = $this->get_default_flowchart($module_name);
                                        if( $default_task !== false && $global_task->has_flowchart_task($module_name, $module_id) == false ){ 
                                            $div_id = "flowcharttask_" . $div_type . '_' . $module_name . '_' . $module_id; ?>
                                           onclick="javascript:slimcrm.case_fct_single=true;setTimeout( function(){ if( slimcrm.case_fct_single == true ){global_task.AddFlowChartTask('<?php echo $module_name_true; ?>', 
																  '<?php echo $module_id; ?>',
																  '<?php echo $default_task['global_task_id']; ?>',
																  '<?php echo $default_task['global_task_id']; ?>',
																  '<?php  echo str_replace( "'" , "\\'" , $whenDonejs); ?>', 
																  '<?php echo $div_id; ?>',
																  { target:'<?php echo $div_id; ?>' , onUpdate: function(response , root){$('.right_tab_right_arrow_active').click();}} ); }} , 500 );"
                                        <?php } else { ?>
                                           onclick="$('.flowcharttask_add_<?php echo $div_type . '_' . $module_name . '_' . $module_id ; ?>').show();" 
                                        <?php }
                                            
                                       ?>
                                       ondblclick="slimcrm.case_fct_single=false;$('.flowcharttask_add_<?php echo $div_type . '_' . $module_name . '_' . $module_id ; ?>').show();"
                                       <?php } ?>
                                       ><button class="add_flowchart" <?php if( $global_task->has_active_flowchart_task($module_name_true , $module_id )){ ?> disabled  <?php } ?> >create bucket task<div class="add_button in_button" >&nbsp;</div></button></a>
                                </td>
                          </tr>
                          <tr colspan="2">
                                <td>
                                  <div style="display:none;" class="flowcharttask_add_<?php echo $div_type . '_' . $module_name . '_' . $module_id;?> flowcharttask_add_<?php echo $module_name;?>" id="flowcharttask_add_<?php echo $div_type . '_' . $module_name . '_' . $module_id;?>">
                                    <?php 
                                    //   $global_task->AddFlowChartTask($module_name,      $module_id, $tree, $global_task_id, $whenDonejs, $div_id)
                                    echo $global_task->AddFlowChartTask($module_name_true, $module_id , $global_task->get_default_tree( $module_name)  , ''             , "$('.flowcharttask_add_" . $div_type . '_' . $module_name . '_' . $module_id . "_$rand" . "').hide();$('.right_tab_right_arrow_active').click();","flowcharttask_add_" . $div_type . '_' . $module_name . '_' . $module_id , $disable_tree);?>
                                  </div>
                                </td>
                          </tr>
                          <tr >
                                <td colspan="2">
                                    <div id="flowcharttask_options_<?php echo $div_type . '_' . $module_name . '_' . $module_id;?>" class="flowcharttask_options flowcharttask_options_<?php echo $module_name; ?>">
                                    <?php echo $global_task->displayByModuleId($module_name_true, $module_id, "flowcharttask_options_" . $div_type . '_' . $module_name . '_' . $module_id,'');?>
                                    </div>
                                </td>
                          </tr>
                        </table>
                </div>
                <?php
                $html=ob_get_contents();
                ob_end_clean();
                return $html;
   }
   
   function set_top_list( $user_id , $value ){
       if( $value == 'true'){
           $value = '1';
       }
       if( $value == 'false'){
           $value = '0';
       }
       $this->db->query( "INSERT INTO eml_owner_top (`user_id` , `top_list` ) VALUES('$user_id' , '$value' ) on duplicate key update `top_list` = '$value' ");
       return $this->display_manage_top_list_inner();
   }
   function display_manage_top_list(){
       return '<div id=display_manage_top_list >' . $this->display_manage_top_list_inner() . "</div>";
   }
   function display_manage_top_list_inner(){
       ob_start();
       $array = $this->get_posible_users_by_module();
       ?>
       <table>
       <?php
        foreach( $array as $n => $v ){ 
            ?>
           <tr>
               <td> <?php echo $v["display_name"]; ?> </td>
               <td><input type="checkbox" <?php 
               if($v['top_list'] == '1' ){ 
                   echo 'CHECKED'; 
                   } else { echo ''; }  ?> 
                          onchange="
                              emaildash.set_top_list( 
                                '<?php echo $v["module_id"]; ?>' , 
                                $(this).ctl_checked() , 
                                { target: 'display_manage_top_list'} 
                              );" />
               </td>
               
           </tr>
       <?php 
       
       }
       ?> </table>  <?php
       
       $html = ob_get_contents();
       ob_end_clean();
       return $html;
   }
   
        function download_file($file_id=''){
            $file_info = $this->db->fetch_assoc($this->db->query("SELECT * FROM eml_files WHeRE eml_file_id = '$file_id'",__LINE__,__FILE__));
            //var_dump($file_info);
          $ctype = @mime_content_type($file_info["filepath"]);
          header("cache-control:must-revalidate,must-revalidate,post-check=0,");
          header("content-disposition:attachment,filename=".$file_info['filename']);
          header("content-length:" . filesize($file_info["filepath"]));
          if( $ctype != '' ){
            header("content-type:$ctype");
          }
          echo file_get_contents($file_info["filepath"]);
          //readfile($doc);
          exit();
        }
		
  	/*function getUserModuleName($owner_module_id){
		$result_user = $this->db->query("SELECT * FROM tbl_user WHERE user_id = '" . $owner_module_id . "'");
		$row_user = $this->db->fetch_assoc($result_user);
		$owner_module_name = $row_user["first_name"]." ".$row_user["last_name"];
		return $owner_module_name;		
	}*/
	
	function get_posible_users_by_module() {
		  $return = array();
		  $result_user = $this->db->query("SELECT a.* , b.top_list FROM tbl_user a LEFT JOIN eml_owner_top b ON a.user_id = b.user_id");
                  $x = 0;
		  while($row_user=$this->db->fetch_assoc($result_user)){
			  $return[$row_user['first_name'] .$row_user['last_name']. $x ] = array( "module_name" => "TBL_USER" , "module_id" => $row_user['user_id'] , "display_name" => $row_user['first_name'] . " " . $row_user['last_name'] , "top_list" => $row_user['top_list'] ); 
                          $x++;
                  }
		  ksort( $return );
		  return $return;
	} 
  
  	//function displayUserModuleLink($module , $module_id , $owner_module_name, $mid){
	function displayUserModuleLink( $mid='', $div_id='' , $overide=array()){
            $options['search'] = '';
            foreach($overide as $n => $v ){
                $options[$n] = $v;
            }
            if( $options["search"] == ''){
                $owner = $this->get_email_by_mid( $mid );
            } else {
                return $this->displayUserModuleDropDown( $option["mid"] , $div_id , array('search' => $options["search"] ) );
                $owner = array( array( 'first_name' => 'Bulk' , 'last_name' => 'Update' , 'owner_user_id' => 1) );
            }
	   foreach( $owner as $option ){
	   $user_group = $option["first_name"].' '.$option["last_name"];
		   if( $option["owner_user_id"] != 0 ){ 
			   $user_group = $option["first_name"].' '.$option["last_name"];
			   $class_name = 'emaildashboard_owner';
			   //$color = '#3366CC';
			} else { 
			   $user_group = 'Unclaimed';
			   $class_name = 'emaildashboard_unclaimed';
			   //$color = '#FF0000';
			  }
		   /*if( $owner_module_name != 'Unclaimed' ){ $color = '#3366CC'; }
		   else{ $color = '#FF0000'; }*/
		   ob_start();
		   ?>
			<a href="javascript:void(0);" onclick="javascript: email_client.singleclicktmp=1; email_client.sdftmptimer=setTimeout(function() {
                            if(email_client.singleclicktmp==1){
                            <?php if( $options['search'] == '' ){ ?>var get_info = get_mail_info();
		                  emaildash.updateUserInfo('<?php echo $mid; ?>',
                                                   'owner_user_id',
												   '<?php echo $_SESSION['user_id']; ?>',
												  {preloader:'prl',
												   onUpdate: function(response,root){
												   emaildash.displayUserModuleLink('<?php echo $mid; ?>',
																				   '<?php echo $div_id; ?>',
																	    {preloader:'prl',
																		onUpdate: function(response,root){
													document.getElementById('<?php echo $div_id; ?>').innerHTML=response;
								
													emaildash.display_email_by_module(
																		document.getElementById('client_name').value,
																		document.getElementById('client_id').value,
																		get_info,
														   
																{preloader:'prl',
																onUpdate: function(response,root){
																document.getElementById('show_info').innerHTML=response;
													 }});}});	
												}}); <?php } else { echo "emaildash.apply_bulk_actions( email_client.bulk_emails , 'owner', '" . $_SESSION['user_id'] . "' , a_default_search ,  { target: 'show_info' } );email_client.clean_bulk_emails();emaildash.displayUserModuleLink( '1', '$div_id' , {search: 'yes' } , { target: '$div_id' })";  }?> }},300 );"
                           
                           
                           ondblclick="email_client.singleclicktmp=0;clearTimeout(email_client.sdftmptimer ); emaildash.displayUserModuleDropDown('<?php echo $option["mid"]; ?>',
			                                                                           '<?php echo $div_id; ?>', { search: '<?php echo $options['search']; ?>'},
												{preloader:'prl',target:'<?php echo $div_id; ?>' , onUpdate: function(responce , root ){ $('.select_user_<?php $mid; ?>').focus(); } });;"> <?php echo '<b class="'. $class_name .' " style="color:'.$color.';">' . $user_group . '</b>'; ?> </a>	
	  <?php
		$html=ob_get_contents();
		ob_end_clean();
		return $html;	 }	
	  }
	
	function displayUserModuleDropDown( $mid='', $div_id='' , $overide=array() ){
	//function displayUserModuleDropDown( $module_name , $module_id ,$mid){
		ob_start();
                $options['search'] = '';
            foreach($overide as $n => $v ){
                $options[$n] = $v;
            }
            $owner = $this->get_email_by_mid( $mid );
            $owner_id = $owner[0]['owner_user_id'];
		$owner_module_info = $this->get_posible_users_by_module();
//                var_dump( $owner_id);
//                var_dump( $owner);
		?>
    <select class="select_user_<?php $mid; ?>"
        <?php if( $options['search'] == '' ){ ?>
        onblur="emaildash.displayUserModuleLink('<?php echo $mid; ?>',
                                                            '<?php echo $div_id; ?>', { target: '<?php echo $div_id; ?>'} );"  
        <?php } ?>
        onchange="<?php if( $options['search'] == '' ){ ?>javascript: var get_info = get_mail_info();
            emaildash.updateUserInfo('<?php echo $mid; ?>',
                            'owner_user_id',
                                this.value,
                                {preloader:'prl',
                                onUpdate: function(response,root){
                                emaildash.displayUserModuleLink('<?php echo $mid; ?>',
                                                            '<?php echo $div_id; ?>',
                                    {preloader:'prl',
                                        onUpdate: function(response,root){
                                    document.getElementById('<?php echo $div_id; ?>').innerHTML=response;

                                    emaildash.display_email_by_module(
                                                                            document.getElementById('client_name').value,
                                                                            document.getElementById('client_id').value,
                                                                            get_info,

                                                            {preloader:'prl',
                                                            onUpdate: function(response,root){
                                                            document.getElementById('show_info').innerHTML=response;
                                        }});}});	
                            }}); <?php } else { echo "emaildash.apply_bulk_actions( email_client.bulk_emails , 'owner', $(this).val() , a_default_search ,  { target: 'show_info' } );email_client.clean_bulk_emails();emaildash.displayUserModuleLink( '1', '$div_id' , {search: 'yes' } , { target: '$div_id' })";  }?>">
			<option value="">-Select User-</option>
                        <?php foreach($owner_module_info as $owner_info){ 
                            if( $owner_info['module_id'] == $_SESSION['user_id']){    ?>
                                    <option value="<?php echo $owner_info['module_id']; ?>"><?php echo $owner_info['display_name']; ?></option>
                            <?php
                            }
                        
                        } ?>
                                    <option value="0" <?php if($owner_id == '0' ){ echo "SELECTED"; } ?> >Unclaimed</option>
                         <?php foreach($owner_module_info as $owner_info){ 
                            if( $owner_info['top_list'] == '1'){    ?>
                                    <option value="<?php echo $owner_info['module_id']; ?>"><?php echo $owner_info['display_name']; ?></option>
                            <?php
                            }
                        
                        } ?>           
                        
                        <option value="0">-----</option>
			<?php foreach($owner_module_info as $owner_info){ ?>
				<option <?php if($owner_id == $owner_info['module_id'] ){ echo "SELECTED"; } ?> value="<?php echo $owner_info['module_id']; ?>"><?php echo $owner_info['display_name']; ?></option>
			<?php } ?>
 		</select>
		<?php
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function updateUserInfo($mid  , $col_name , $value){
		$update_sql_array = array();		
		$update_sql_array[$col_name] = $value;
		
		$this->db->update(EML_MESSAGE,$update_sql_array,'mid',$mid );
		/*$name = $this->getUserModuleName($owner_module_id);
		return $name;*/
	}


  }//////end of class
?> 