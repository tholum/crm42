
<?php
//ini_set("display_errors",1);
require_once('app_code/global.config.php');
require_once('class/class.flags.php');
require_once('class/class.GlobalTask.php');
require_once('class/class.email_client.php');
require_once('class/ClsJSFormValidation.cls.php');
require_once('class/class.FormValidation.php');
require_once('class.fileserver.php');
require_once('class.timeTracker.php');
require_once('PHPLiveX.php');
/*
$ajax = new PHPLiveX();
$case_creation = new case_creation();
$ajax->AjaxifyObjects(array("case_creation"));  
$ajax->Run('phplivex.js'); // Must be called inside the 'html' or 'body' tags 
*/
class case_creation extends email_client {

 var $flags;
 var $global_task;
 var $page;
 var $timetracker;
 
    function __construct( $page='' ){
                //parent::__construct($page);
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
		$this->flags = new Flags();
		$this->global_task = new GlobalTask();
                
                if( $page == ''){
                        $this->page = new basic_page();
                    } else {
                        $this->page = $page;
                    }
                
                
      }
      function get_case_data( $case_id ){
          return $this->db->fetch_assoc($this->db->query("SELECT a.* , b.company_name FROM `cases` a LEFT JOIN contacts b ON a.contact_module_id = b.contact_id AND a.contact_module_name = 'CONTACTS' WHERE a.case_id = '$case_id'"));
          
      }
      function show_updated_case_from_table( $module_name , $module_id ){
          $original = $module_name . ":|:" . $module_id;
          include 'modules/display/dataformat.case_by_module.php';
          return $clean;
          
      }
      function get_defaults_by_module( $module_name='' , $module_id=''){
          $defaults = array();
          $module_name = strtolower($module_name);
          //echo "./modules/cases/defaults.$module_name.php";
          if(file_exists("./modules/cases/defaults.$module_name.php")){
                include("modules/cases/defaults.$module_name.php");
            }
          return $defaults;
      }
      function delete_case_type_option( $option_id ){
          $this->db->query("UPDATE erp_dropdown_options SET active = '0' WHERE id='$option_id'");
          return $this->manage_case_types_inner();
      }
      function add_case_sub_type( $name , $parrent ){
                    $insert = array();
          $insert['option_name'] = 'Case Type ' . $parrent;
          $insert['identifier'] = strtolower('Case Type ' . $parrent);
          $insert['name'] = $name;
          $this->db->insert('erp_dropdown_options', $insert);
          return $this->manage_case_types_inner();
      }
      function add_case_type( $name ){
          $insert = array();
          $insert['option_name'] = 'Case Type';
          $insert['identifier'] = strtolower($name);
          $insert['name'] = $name;
          $this->db->insert('erp_dropdown_options', $insert);
          return $this->manage_case_types_inner();
      }
      
      function manage_case_types(){
          ob_start(); ?>
<script >
function delete_case_option(id ){
    //casecreation.delete_case_type_option('<?php echo $sub_option['id']; ?>');
        
        var rand_id = Math.floor( Math.random() * 11 );
        var tmp_id = 'del_case_opt' + rand_id;
        $('body').append('<div id=' + tmp_id + ' ><input type=hidden value="' + id + '" id="case_option_to_delete" ></div>');
        $('#' + tmp_id ).attr('title','Delete Case Type');
   $( '#' + tmp_id ).dialog(
    { 
        close: function(event , ui){  
            $(this).remove();$(this).dialog('destroy'); 
        } , 
        resizable: false,
        height:140,
        modal: true,
        buttons: {
            'Delete': function() {
                casecreation.delete_case_type_option( $('#case_option_to_delete').val() , 
                { 
                    target: 'manage_case_types',
                    onUpdate: function(response,root){
                            run_on_reload();
                            
                    }
                });
                $(this).remove().dialog( 'close' );
            },
            Cancel: function() { 
                $(this).remove();$( this ).dialog( 'close' );
            }
        }
     });
}    
    
    
function add_case_type(){
        var rand_id = Math.floor( Math.random() * 11 );
        $('body').append('<div id=add_case_type' + rand_id + ' >Name: <input id=newcasetype /></div>');
        $('#add_case_type' + rand_id ).attr('title','Add Case Type');
   $( '#add_case_type' + rand_id  ).dialog(
    { 
        close: function(event , ui){  
            $(this).remove();$(this).dialog('destroy'); 
        } , 
        resizable: false,
        height:140,
        modal: true,
        buttons: {
            'Add Case Type': function() {
                casecreation.add_case_type( $('#newcasetype').val() , 
                { 
                    target: 'manage_case_types',
                    onUpdate: function(response,root){
                            run_on_reload();
                            
                    }
                });
                $(this).remove().dialog( 'close' );
            },
            Cancel: function() { 
                $(this).remove();$( this ).dialog( 'close' );
            }
        }
     });
     
 }
 function add_sub_case_type(main_name){
        var rand_id = Math.floor( Math.random() * 11 );
        $('body').append('<div id=add_case_type' + rand_id + ' >Name: <input id=newsubcasetype /><input type=hidden value="' + main_name + '" id="newcasesubtype_main_name" ></div>');
        $('#add_case_type' + rand_id ).attr('title', main_name + ': Add Sub Case Type' );
   $( '#add_case_type' + rand_id  ).dialog(
    { 
        close: function(event , ui){  
            $(this).remove();$(this).dialog('destroy'); 
        } , 
        resizable: false,
        height:140,
        modal: true,
        buttons: {
            'Add Case Type': function() {
                casecreation.add_case_sub_type( $('#newsubcasetype').val() , $('#newcasesubtype_main_name').val() ,
                { 
                    target: 'manage_case_types',
                    onUpdate: function(response,root){
                            run_on_reload();
                            
                    }
                });
                $(this).remove().dialog( 'close' );
            },
            Cancel: function() { 
                $(this).remove();$( this ).dialog( 'close' );
            }
        }
     });
     
 }
 </script>
<a onclick="add_case_type();" ><button>add case type<div class="add_button in_button" >&nbsp;</div></button></a>
          <div id="manage_case_types" class="accordion" style="width: 400px;">
              <?php echo $this->manage_case_types_inner(); ?>
          </div>

    <?php $html = ob_get_contents();
          ob_end_clean();
          return $html;
      }
      function manage_case_types_inner(){
          ob_start();
          $options = $this->page->get_dropdown_array( 'case type' );
          foreach( $options as $option ){
              echo $this->manage_case_types_option($option);
          }
          
          $html = ob_get_contents();
          ob_end_clean();
          return $html;
      }
      function manage_case_types_option($option ){
          ob_start(); ?>
<h3><a> <?php echo $option['name'];?></a></h3>
                <div>
                    <a onclick="add_sub_case_type('<?php echo $option['name']; ?>');"><button>add sub type<div class="add_button in_button">&nbsp;</div></button></a>
                    <a onclick="delete_case_option('<?php echo $option['id']; ?>');"><button>delete type<div class="trash_can_normal in_button">&nbsp;</div></button></a>
                    <div>
                    <?php 
                $sub_options = $this->page->get_dropdown_array( 'case type ' . $option['identifier'] );
                foreach( $sub_options as $sub_option){ ?>
                        <div style="width: 200px;float: left;" id="suboption<?php echo $sub_option['id'] ?>" ><?php echo $sub_option['name']; ?></div>  <a onmouseout="$('#suboption<?php echo $sub_option['id'] ?>').removeClass('div_hover_dark');" onmouseover="$('#suboption<?php echo $sub_option['id'] ?>').addClass('div_hover_dark');" onclick="delete_case_option('<?php echo $sub_option['id']; ?>');"><button>delete sub type<div class="trash_can_normal in_button">&nbsp;</div></button></a></br>
                        <?php
                }
                ?></div>
                </div>
                <?php
          $html = ob_get_contents();
          ob_end_clean();
          return $html;
      }
      
      
      function set_case_activity($module_name , $module_id , $case_id ){
          switch (strtolower($module_name)){
              case "email":
                  $insert = array();
                  $insert["case_id"] = $case_id;
                  $insert["module_name"] = "EMAIL";
                  $insert["module_id"] = $module_id;
                  $insert["module_type"] = "EMAIL";
                  $this->db->insert('cases_activity', $insert);
              break;
          }
      }
	function create_case( $module_name ='' , $module_id='' ,$user_id='' , $overide=array() ){
            $defaults = $this->get_defaults_by_module($module_name, $module_id);
            $defaults['Owner'] = $_SESSION['user_id'];
           
            foreach( $overide as $n => $v){
                $defaults[$n] = $v;
            }
            if( $user_id == ''){ $user_id = $_SESSION['user_id']; }
            $defaults["module_name"] = $module_name;
            $defaults["module_id"] = $module_id;
            $defaults['CreatedBy'] = $_SESSION['user_id'];
            $this->db->insert('cases', $defaults);
            $case_id = $this->db->last_insert_id();
            $this->set_case_activity($module_name , $module_id , $case_id );
            if(strtolower($module_name) == "email"){
                return $this->right_bottom_panel($module_id);
            } else {
                return $this->right_bottom_panel($case_id);
            }
            
        }
	function left_panel( $html, $overide=array() ){
       echo '
	   <div id="content_left_panel" class="left_panel_flyout">' . $this->display_mail_content( '10', 'flyout', 'COMPOSE' ) . '</div>
	 <div id="tab_left_panel" class="left_tab_right_arrow" onclick="$(\'.emaildashboard_compose\').hide();if(document.getElementById(\'content_left_panel\').style.display==\'none\'){ $(\'#content_left_panel\').show(); $(\'#tab_left_panel\').removeClass(\'left_tab_right_arrow\'); $(\'#tab_left_panel\').addClass(\'left_tab_left_arrow\'); } else { $(\'#content_left_panel\').hide(); $(\'#tab_left_panel\').removeClass(\'left_tab_left_arrow\'); $(\'#tab_left_panel\').addClass(\'left_tab_right_arrow\');$(\'.no_focus\').focus(); } "></div>
	 ';
  
  
     }
  
    function right_top_panel( $html, $overide=array() ){
       ?>
	   <div 
               id="tab_right_top_panel">
               <!-- Quick Search / Knowlage base -->
               <div  
               class="right_tab_left_arrow top_right_arrows qskb_tab" 
               onclick="
                   //alert($('#content_right_top_panel').attr('display'));
                   if(document.getElementById('content_right_top_panel').style.display=='none'){
                       //alert('1');
                       $('#content_right_top_panel').show(); 
                       $('.top_right_arrows').removeClass('right_tab_left_arrow').addClass('right_tab_right_arrow');
                       $(this).removeClass('right_tab_right_arrow').addClass('right_tab_right_arrow_active');
                       //$('#tab_right_top_panel').addClass('right_tab_right_arrow'); 
                   } else {
                       //alert('2');
                       $('.top_right_arrows').removeClass('right_tab_right_arrow_active').addClass('right_tab_right_arrow');
                       $(this).removeClass('right_tab_right_arrow').addClass('right_tab_right_arrow_active');
                      
                   }
                   knowledgebase.display_flyout({target: 'content_right_top_panel' , onUpdate: function(data,root){ kb_run_on_start(); } });
                   " 
                   ondblclick="
                   if(document.getElementById('content_right_top_panel').style.display=='none'){
                       //alert('3');
                       $('#content_right_top_panel').show(); 
                       $('.top_right_arrows').removeClass('right_tab_left_arrow').addClass('right_tab_right_arrow');
                       $(this).removeClass('right_tab_right_arrow').addClass('right_tab_right_arrow_active');
                   } else { 
                       //alert('4');
                       $('#content_right_top_panel').hide(); 
                       $('.top_right_arrows').removeClass('right_tab_right_arrow_active').removeClass('right_tab_right_arrow').addClass('right_tab_left_arrow'); 
                   }"
                   ></div>
               <!-- Phone DIV -->
           
        <div  
               class="right_tab_left_arrow top_right_arrows phone_tab" 
               onclick="
                   if(document.getElementById('content_right_top_panel').style.display=='none'){
                       $('#content_right_top_panel').show(); 
                       $('.top_right_arrows').removeClass('right_tab_left_arrow').addClass('right_tab_right_arrow'); 
                       $(this).removeClass('right_tab_right_arrow').addClass('right_tab_right_arrow_active');
                       //$('#tab_right_top_panel').addClass('right_tab_right_arrow'); 
                   } else { 
                       $('.top_right_arrows').removeClass('right_tab_right_arrow_active').addClass('right_tab_right_arrow');
                       $(this).removeClass('right_tab_right_arrow').addClass('right_tab_right_arrow_active');
                       
                   } 
                   knowledgebase.phone_popout( slimcrm.tick.phone.current_call , {target : 'content_right_top_panel'  , onUpdate: function(data,root){ kb_run_on_start(); } }  );" 
                   ondblclick="
                   if(document.getElementById('content_right_top_panel').style.display=='none'){
                       $('#content_right_top_panel').show(); 
                       $('.top_right_arrows').removeClass('right_tab_left_arrow').addClass('right_tab_right_arrow'); 
                       $(this).removeClass('right_tab_right_arrow').addClass('right_tab_right_arrow_active');
                   } else { 
                       $('#content_right_top_panel').hide(); 
                       $('.top_right_arrows').removeClass('right_tab_right_arrow_active').removeClass('right_tab_right_arrow').addClass('right_tab_left_arrow'); 
                   }"
                   ></div>
           </div>
           
        
        <div id="content_right_top_panel" style="display:none;"></div>
	 <?php
  
  
  
     }
  /*
   * CTLTODO: Why would you set this to mid INSTEAD of $module_name , $module_id!!
   * CTLTODO: Swap module_name and mid's location and replace all instanse to fix it
   * CTLTODO: What is the difference between mid and module_id
   */
     
     function get_cases_by_module( $module_name , $module_id , $overide=array() ){
         $options = array();
         $options['display_submodules'] = false;
         $options['show_active'] = true;
         $options['show_none'] = true;
         $options['show_complete'] = true;
         $options['only_active'] = false;
         $options['only_none'] = false;
         $options['only_complete'] = false;
         $options['from_contact'] = false;
         $options['limit'] = 6;
         $options['return_sql'] = false; // mainly for debuging
         foreach($overide as $n => $v){
             $options[$n] = $v;
         }
         $where = '';
         if($options['only_active'] == true ){
            $options['show_active'] = true;
            $options['show_none'] = false;
            $options['show_complete'] = false;
         }
         if($options['only_none'] == true ){
            $options['show_active'] = false;
            $options['show_none'] = true;
            $options['show_complete'] = false;
         }
         if($options['only_complete'] == true ){
            $options['show_active'] = false;
            $options['show_none'] = false;
            $options['show_complete'] = true;
         }
         
         if($options['show_complete'] == false){
             $where .= " AND `Status` <> 'Completed'";
         }
         if($options['show_none'] == false){
             $where .= " AND `Status` <> 'None'";
             $where .= " AND `Status` <> ''";
         }
         if($options['show_active'] == false){
             $where .= " AND `Status` <> 'Active'";
         }
         $prepend_name = '';
         if( $options['from_contact'] == true ){
             $prepend_name = 'contact_';
         }
         if($options['limit'] != ''){
             $limit = 'LIMIT ' . $options['limit'];
         }
         $sql = "SELECT * FROM cases WHERE $prepend_name" . "module_name = '$module_name' AND $prepend_name" . "module_id = '$module_id' $where ORDER BY `CreatedOn` DESC $limit ";
         if( $options['return_sql'] == true ){
             return $sql;
         }
         $result = $this->db->query( $sql );
         $cases = array();
         while($row=$this->db->fetch_assoc($result)){
             $cases[] = $row;
         }
         return $cases;
     }
     
     function right_bottom_by_module($module_name , $module_id , $overide=array() ){
         
         $cases = $this->get_cases_by_module($module_name, $module_id , $overide );
         

         return $this->right_bottom_by_cases($cases);
                           
     }
     function right_bottom_by_case_id_array( $cases ){
         $where = implode("' OR case_id = '" , $cases );
         $result = $this->db->query( "SELECT * FROM cases WHERE case_id = '$where'");
         $cases = array();
         while($row=$this->db->fetch_assoc($result)){
             $cases[] = $row;
         }
         return $this->right_bottom_by_cases($cases);
                           
     }
     function right_bottom_by_case($case_id ){
         $result = $this->db->query( "SELECT * FROM cases WHERE case_id = '$case_id'");
         $cases = array();
         while($row=$this->db->fetch_assoc($result)){
             $cases[] = $row;
         }
         return $this->right_bottom_by_cases($cases);
                           
     }
     function create_case_by_array( $overide=array() ){
         $options = array();
         foreach( $overide as $n => $v){
             $options[$n] = $v;
         }
         $options['CreatedBy'] = $_SESSION['user_id'];
         $this->db->insert('cases' , $options);
         $case_id = $this->db->last_insert_id();
         return $this->right_bottom_by_case($case_id);
             
     }
     
     function right_bottom_by_cases( $case_array = array() ){
        $return = '';
        $count = count( $case_array );
        $ct = $count;
        $return .= '<div id="tab_right_bottom_panel"  >';
        $count = 1;
        if(!empty($case_array)){
            foreach( $case_array as $cases ){
                $case_id[] = $cases["case_id"];
                $case_tab_div_id = 'tab_right_bottom_panel_'.$cases["case_id"];
                if($count > 1){ $margin_top = "-4"; $ac = ''; } else { $ac="_active";} 
                $return .= '<div 
                    id="' . $case_tab_div_id . '" style="margin-top: ' . $margin_top . 'px;position: relative; " 
                    class="right_tab_right_arrow' . $ac . ' bottom_right_tabs" 
                    onclick="
                        $(\'#content_right_bottom_panel\').show();
                        $(\'.bottom_right_tabs\').removeClass(\'right_tab_right_arrow_active\').addClass(\'right_tab_right_arrow\');
                        $(\''.$case_tab_div_id.'\').removeClass(\'right_tab_left_arrow\'); 
                        $(\''.$case_tab_div_id.'\').addClass(\'right_tab_right_arrow\');
                        $(this).addClass(\'right_tab_right_arrow_active\');
                        case_creation.display_case_creation(\'' . $cases["case_id"] .'\',{target:\'content_right_bottom_panel\'});" 
                   
                    ondblclick="
                        $(\'#content_right_bottom_panel\').hide(); 
                        $(\''.$case_tab_div_id.'\').removeClass(\'right_tab_right_arrow\'); 
                        $(\''.$case_tab_div_id.'\').addClass(\'right_tab_left_arrow\');
                     "></div>';
                $count++;
            }
        }
        else{		
            return '';
           // $return =  '<div id="tab_right_bottom_panel" class="right_tab_right_arrow" onclick="if(document.getElementById(\'content_right_bottom_panel\').style.display==\'none\'){ $(\'#content_right_bottom_panel\').show(); $(\'#tab_right_bottom_panel\').removeClass(\'right_tab_left_arrow\'); $(\'#tab_right_bottom_panel\').addClass(\'right_tab_right_arrow\'); } else { $(\'#content_right_bottom_panel\').hide(); $(\'#tab_right_bottom_panel\').removeClass(\'right_tab_right_arrow\'); $(\'#tab_right_bottom_panel\').addClass(\'right_tab_left_arrow\'); } "> </div>';
        }
        
        $return .= '</div><div id="content_right_bottom_panel">';
        if( !empty($case_array) ){
            $return .= $this->display_case_creation( $case_array[0]['case_id'] );
        }
        $return .= '</div>';
        return $return;
     }
     
     
     
     function right_bottom_panel( $module_id='', $mid='', $module_name = "EMAIL" , $overide=array() ){
         $case_activity = $this->get_number_of_cases( $module_id , $module_name );
         
	    
	    if( $module_id == '' or $module_id == 'CREATE' ){
		    $return = '<div id="tab_right_bottom_panel" class="right_tab_right_arrow_active bottom_right_tabs" onclick="
                        if(document.getElementById(\'content_right_bottom_panel\').style.display==\'none\'){ 
                            $(\'#content_right_bottom_panel\').show(); 
                            $(\'#tab_right_bottom_panel\').removeClass(\'right_tab_left_arrow\');
                            $\'.bottom_right_tabs\').removeClass(\'right_tab_right_arrow_active\').addClass(\'right_tab_right_arrow\');
                            $(\'#tab_right_bottom_panel\').addClass(\'right_tab_right_arrow_active\'); 
                        } else { 
                            $(\'#content_right_bottom_panel\').hide(); 
                            $(\'#tab_right_bottom_panel\').removeClass(\'right_tab_right_arrow\').removeClass(\'right_tab_right_arrow_active\'); 
                            $(\'#tab_right_bottom_panel\').addClass(\'right_tab_left_arrow\'); 
                       } "> </div>';
		} else { 
                    
                    $case_activity = $this->get_number_of_cases( $module_id , $module_name );
                    return  $this->right_bottom_by_cases($case_activity);
//                    //print_r( $case_activity );
//                    $count = 1;
//                    $margin_top = 0;
//                    $case_id = array();
//
//                    $return .= '<div id="right_bottom_panel" style="position:fixed;right: 300px;top: 200px;"  >';
//                    
//                    if(!empty($case_activity)){
//                        foreach( $case_activity as $cases ){
//                            $case_id[] = $cases["case_id"];
//                            $case_tab_div_id = 'tab_right_bottom_panel'.$module_id.'_'.$cases["case_id"];
//                            if($count > 1){ $margin_top = "-4"; $ac = '';} else { $ac = '_active'; }
//                            $return .= '
//                                <div id="' . $case_tab_div_id . '" style="margin-top: ' . $margin_top . 'px;" class="right_tab_right_arrow' . $ac . ' bottom_right_tabs" 
//                                 onclick="
//                                    $(\'#content_right_bottom_panel\').show(); 
//                                    $(\'.bottom_right_tabs\').removeClass(\'right_tab_left_arrow\').addClass(\'right_tab_right_arrow\'); 
//                                    $(this).removeClass(\'right_tab_right_arrow\').addClass(\'right_tab_right_arrow_active\');
//                                    case_creation.display_case_creation(\'' . $cases["case_id"] .'\',{target:\'content_right_bottom_panel\'});" ondblclick="$(\'#content_right_bottom_panel\').hide(); $(\''.$case_tab_div_id.'\').removeClass(\'right_tab_right_arrow\'); $(\''.$case_tab_div_id.'\').addClass(\'right_tab_left_arrow\');"></div>';
//                            $count++;
//                        }
//                    }
//                    
//                    else{		    
//                        $return = '<div id="tab_right_bottom_panel" class="right_tab_right_arrow" onclick="if(document.getElementById(\'content_right_bottom_panel\').style.display==\'none\'){ $(\'#content_right_bottom_panel\').show(); $(\'#tab_right_bottom_panel\').removeClass(\'right_tab_left_arrow\'); $(\'#tab_right_bottom_panel\').addClass(\'right_tab_right_arrow\'); } else { $(\'#content_right_bottom_panel\').hide(); $(\'#tab_right_bottom_panel\').removeClass(\'right_tab_right_arrow\'); $(\'#tab_right_bottom_panel\').addClass(\'right_tab_left_arrow\'); } "> </div>';
//                    }                    
//                    $div_active = "tab_right_bottom_panel".$module_id .'_'.$case_id[0]; 
//                    $return .= '<input type="hidden" name ="disp_div_id" id="disp_div_id" value="1"></div>';
		 }
		$return .= '<div id="content_right_bottom_panel">';
		
		if( $module_id != '' ){
			$return .= $this->display_case_creation( $case_id[0] );
		}
		
		if( $module_id == 'CREATE' ){ $return .= $this->display_case_creation_panel($mid , $module_name ); }
		
		$return .= '</div>';
                if(empty($case_activity)){
                    $return = '';
                }
		return $return;
     }
	 
	 function get_number_of_cases( $module_id='', $module_name='EMAIL' ){
             if(strtoupper($module_name) != 'CASES'){
                $result = $this->db->query("SELECT case_id FROM ".CASES_ACTIVITY." WHERE module_id = '$module_id' and module_name = '$module_name' ORDER BY case_id DESC");
             
		$return = array();
		while( $row = $this->db->fetch_assoc($result) ){
		       $return[] = $row;
		}
	    return $return;
            } else {
                 return array( array("case_id" => $module_id ) );
             }
	 }
		
	function get_owner_by_id( $user_id='' ){
           
	   $sql ="SELECT b.first_name, b.last_name, c.email_address FROM ".EML_MESSAGE." a LEFT JOIN ".TBL_USER." b ON a.owner_user_id = b.user_id LEFT JOIN ".EML_MAILBOX." c ON a.module_name = c.module_name AND a.module_id = c.module_id WHERE a.mid = '$user_id'";
	   $result = mysql_query($sql);
	   //echo $sql;
	   $return = array();
	   while($row=$this->db->fetch_assoc($result)){
			  $return[] = $row;
	   }
       return $return;
     }
	 
	 function get_mail_by_case_id( $case_id='' ){
	   $result = $this->db->query("SELECT b.* FROM ".CASES_ACTIVITY." a LEFT JOIN ".EML_MESSAGE." b ON a.module_id = b.mid WHERE a.case_id = '$case_id'");
	   $return = array();
	   while($row=$this->db->fetch_assoc($result)){
			  $return[] = $row;
	   }
       return $return;
     }
	 
	 function get_cases_by_id( $case_id='' ){
	    $result = $this->db->query("SELECT * FROM ".CASES." WHERE case_id = '$case_id'");
		//echo "SELECT * FROM ".CASES." WHERE case_id = '$case_id'";
	    $return = array();
	    while($row=$this->db->fetch_assoc($result)){
			  $return[] = $row;
	    }
        return $return;
	 }
	 
	 function get_display_name_by_module( $module_name='' , $module_id='' ){
            $return = '';
                switch(strtoupper($module_name)){
                case "eapi_ACCOUNT":
                    $result = $this->db->query("SELECT display_name FROM ". ACCOUNT_DISPLAY_NAME ." WHERE account_id = '$module_id'");
                    if( mysql_num_rows($result) != 0){
                        $row = $this->db->fetch_assoc($result);
                        $return = $row['display_name'];

                    } else {
                        $return = $this->page->eapi_account->cache_account( $module_id );
                    }
                 break;
                 case "CONTACTS":
                     $row = $this->db->fetch_assoc( $this->db->query("SELECT * FROM contacts  WHERE contact_id = '$module_id'") );
                     if( $row !== false ){
                         if( $row['type'] == 'People' ){
                             $return = $row['first_name'] . ' ' . $row['last_name'];
                         } else {
                             $return = $row['company_name'];
                         }
                     }
                     break;
                     
		}
	    
            return $return;
	 }
	 
	 function get_notes_by_module_id( $module_name='' , $module_id='' ){
	    $result = $this->db->query("SELECT a.*, b.first_name, b.last_name FROM ". CASE_NOTES ." a LEFT JOIN ".TBL_USER." b ON a.user_id = b.user_id WHERE module_id = '$module_id' AND module_name = '$module_name' ORDER BY a.n_time DESC");
		$return = array();
	    while( $row = $this->db->fetch_assoc($result) ){
		       $return[] = $row;
		}
        return $return;
	 }
	 
	function GetVendorJson($pattern=''){
		ob_start();
		$contact_json = "";
		$sql="select * from ".ACCOUNT_DISPLAY_NAME." where display_name LIKE '%$pattern%' limit 0, 20";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		while($row = $this->db->fetch_array($record)){
			$cmp = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $row[display_name]);
			$contact_json .='{"caption":"'.$cmp.'","value":"'.$row[account_id].'"},';
		}
		$contact_json = '['.substr($contact_json,0,strlen($contact_json)-1).']';
		echo $contact_json;
		
			$result = $this->db->query("SELECT display_name FROM ". ACCOUNT_DISPLAY_NAME ." WHERE account_id = '$module_id'");
			while($row = $this->db->fetch_assoc($result)){
			$cmp = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $row[display_name]);
			$contact_json .='{"caption":"'.$cmp.'","value":"'.$row[inventory_id].'"},';
			}
			$contact_json = '['.substr($contact_json,0,strlen($contact_json)-1).']';
			return $contact_json;
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} ///////end of function GetVendorJson
	
        
        function display_email_activity( $email ){
            ob_start();?>
					    <a href="javascript:void(0);"
						   onclick="javascript:
$('.emaildashboard_compose_emaildashboard').show();
emaildash.display_mail_content(
    '<?php echo $email["mid"]; ?>',
    'flyout',
    'leftpanel',
    {
        preloader:'prl',
        onUpdate: email_client.display_compose
    }
);"><?php if( $email['subject'] != '' ){ echo ' '.$email["subject"].'</br>'; } else { echo ' '.$email["from_mailbox"] . '@' . $email["from_host"].'</br>'; } ?></a>
				   <?php 
          $html = ob_get_contents();
          ob_end_clean();
          return $html;
          }
        
        
        
        
	 function display_case_creation_panel($mid='', $module_name="EMAIL"){
	    ob_start();
		 $emails = $this->get_email_by_id( $mid );
		 $case_origin = 'casecreation_Case_Origin.';
		 $case_owner = 'casecreation_Owner.';
		 $case_type = 'casecreation_Case_Type.';
		 $case_status = 'casecreation_Case_Status.';
		 $case_option = 'casecreation_sub_option';
		 ?>
		<script>
		  function get_case_info(){
				var case_information = {
						customer_id: document.getElementById('customer_id').value,
						case_order: document.getElementById('casecreation_order_creat').value,
						case_origin: document.getElementById('<?php echo $case_origin; ?>').value,
						case_owner: document.getElementById('<?php echo $case_owner; ?>').value,
						case_type: document.getElementById('<?php echo $case_type; ?>').value,
						case_status: document.getElementById('<?php echo $case_status; ?>').value,
						case_note: document.getElementById('create_case_note').value,
						mid: <?php echo $mid; ?>,
                                                module_name: '<?php echo $module_name; ?>',
						suboption: document.getElementById('hidden_option').value
				};
				return case_information;
			}
		</script>
		
		<div class="case_creation_div" >
		   <table class="case_creation_table">
			 <tr>
			   <td colspan="2"><div class="casecreation_case">Case:  </div></td>
			   <td colspan="2"><div class="casecreation_group">Group:  </div></td>
			 </tr>
			 <tr>
			   <td><b class="casecreation_text">Customer :</b></td>
			   <td colspan="3">
			     <div class="casecreation_customer_creat_div">
			       <select name="casecreation_customer_creat" id="casecreation_customer_creat"
				           onChange="javascript: document.getElementById('customer_id').value='';
											for(i=0; i<document.getElementById('casecreation_customer_creat').length; i++){ 
												if(document.getElementById('casecreation_customer_creat')[i].selected==true){
												   document.getElementById('customer_id').value += 
												   document.getElementById('casecreation_customer_creat')[i].value+',';
												 }
											}
											document.getElementById('customer_id').value = 											                                            document.getElementById('customer_id').value.substr(0,
											document.getElementById('customer_id').value.length-1);">
		            </select>
					<input name="customer_id" type="hidden" id="customer_id" value="" />
			     </div>
			   </td>
			 </tr>
			 <tr>
			   <td><b class="casecreation_text">Order :</b></td>
			   <td colspan="3">
			     <div class="casecreation_order_creat_div">
			       <input type="text" name="casecreation_order_creat" id="casecreation_order_creat" />
				 </div>
			   </td>
			 </tr>
			 <tr>
			   <td><b class="casecreation_text">Case Origin :</b></td>
			   <td colspan="3">
			     <div id="<?php echo 'div_'.$case_origin; ?>" class="casecreation_caseorigin">
				   <?php
				    echo $this->display_case_options( $case_origin, '' );
				   ?>
			     </div>
			   </td>
			 </tr>
			 <tr>
			   <td><b class="casecreation_text">Owner :</b></td>
			   <td colspan="3">
			     <div id="<?php echo 'div_'.$case_owner; ?>" class="casecreation_owner">
				   <?php
				   echo $this->display_case_options( $case_owner, '' );
				   ?>
			     </div>
			   </td>
			 </tr>
			 <tr>
			   <td><b class="casecreation_text">Case Type :</b></td>
			   <td colspan="3">x
			     <div id="<?php echo 'div_'.$case_type; ?>" class="casecreation_casetype">
				   <?php
				   echo $this->display_case_options( $case_type, '' );
				   ?>
			     </div>
			   </td>
			 </tr>
			 <tr>
			   <td><b class="casecreation_text">Case Status :</b></td>
			   <td colspan="3" style="background: greenyellow;"  >
			     <div id="<?php echo 'div_'.$case_status; ?>" class="casecreation_casestatus">
				   <?php echo $this->display_case_options( $case_status, '' ); ?>
			     </div>
			   </td>
			 </tr>

			 <tr>
			   <td colspan="4" style="background: pink;" ><span id="casecreation_type_option"> </span></td>
			 </tr>
			 <tr>
			   <td colspan="4">
			   </td>
			 </tr>			 
			 <tr>
			   <td colspan="4"></td>
			 </tr>
			 <tr>
			   <td><b class="casecreation_heading">Follow Up :</b></td>
			   <td colspan="3"></td>
			 </tr>
			 <tr>
			 	<td colspan="4"><div id="show_fct"><?php //echo $this->showFlowChartTask('case',$options["case_id"]); ?></div></td>
			 </tr>
			 <tr>
			   <td colspan="4" align="left"><b class="casecreation_heading">Activity :</b></td>
			 </tr>
			 <tr>
			   <td colspan="4" align="center">
			     <div class="flyout_compose">
			       <?php
				     foreach( $emails as $email ){
                                         echo date('ymd', strtotime($email['unittime']));
                                        //echo $this->display_email_activity($email);
                                     } ?>
				  </div>
			   </td>
			 </tr>
			 <tr>
			   <td colspan="4" align="right">
			     <div class="fct_image">
				   <?php //echo $this->FlowChartDiv('case',$options["case_id"],'case'); ?>
				 </div>
			   </td>
			 </tr>
			 <tr>
			   <td colspan="4"><b class="casecreation_heading">Notes :</b></td>
			 </tr>
			 <tr>
			   <td colspan="4" align="right" >
			     <span id="casecreation_note_creat_span">
				   <textarea name="create_case_note" id="create_case_note" rows="4" cols="32"></textarea>
				 </span>
			   </td>
			 </tr>
			 <tr>
			   <td colspan="4" align="right" class="case_flyout_display_note">
			     <input type="button" name="casecreation_create_case" id="casecreation_create_case" value="Create Case"
				        onclick="javascript: var get_info = get_case_info();
						                     case_creation.set_created_case(get_info,
											                                {preloader:'prl',
																			onUpdate: function(response,root){
																			
																			case_creation.display_case_creation(response,
																			{target:'content_right_bottom_panel'});
																		}});
						                                              " />
			   </td>
			 </tr>
		  </table>
	   </div>
     <?php
	 $html=ob_get_contents();
	 ob_end_clean();
	 return $html;
	 }
	 
	 function set_created_case( $overide=array() ){
	    foreach( $overide as $n => $v ){
                    $options[$n] = '';
                    $options[$n] = $v;
           }
        print_r($options);
		$suboption = explode("~",$options["suboption"]);
		$num = count($suboption);
		
		   $insert_sql_array=array();
		   $insert_sql_array["OrderNumber"] = $options["case_order"];
		   $insert_sql_array["CaseOrigin"] = $options["case_origin"];
		   $insert_sql_array["Owner"] = $options["case_owner"];
		   $insert_sql_array["CaseType"] = $options["case_type"];
		   $insert_sql_array["Status"] = $options["case_status"];
		   $insert_sql_array["module_name"] = $options["module_name"];
                   $insert_sql_array["module_id"] = $options["mid"];
		   $this->db->insert(CASES,$insert_sql_array);
		   $last_case_id = $this->db->last_insert_id();
		   
		   $insert_array=array();
		   $insert_array["module_id"] = $last_case_id;
		   $insert_array["user_id"] = $options["case_owner"];
		   $insert_array["description"] = $options["case_note"];
		   $insert_array["module_name"] = 'CASES';
		   
		   $this->db->insert(CASE_NOTES,$insert_array);
		   
		   $insert_array=array();
		   $insert_array["case_id"] = $last_case_id;
		   $insert_array["module_name"] = 'EMAIL';
		   $insert_array["module_id"] = $options["mid"];
		   $insert_array["module_type"] = 'CASE';
		   
		   $this->db->insert(CASES_ACTIVITY,$insert_array);
		   
		   for($i=1;$i<$num;$i++){
		       $insert_array=array();
			   $insert_array["case_id"] = $last_case_id;
			   $insert_array["option_id"] = 'case type '.$options["case_type"];
			   $insert_array["SubOption"] = $suboption[$i];
			   
			   $this->db->insert(CASE_SUBOPTION,$insert_array);
		   }	   
		   
		   return $last_case_id;
	 }
	 
	 function get_owner_name($user_id='',$selected=''){
             
             if( $user_id != ''){
                 $where = " WHERE user_id = '$user_id' ";
             }
	    $result = $this->db->query("SELECT user_id, first_name , last_name FROM ".TBL_USER."$where");
	    $return = array();
	    while($row=$this->db->fetch_assoc($result)){
                            if( $row['user_id'] == $selected){
                                $row["name"] = 'Active';//Another quick hack
                            }
			  $return[] = $row;
	    }
        return $return;
	 }
	 
	 function set_option_by_case( $value='', $option='', $case_id='', $type='' ){
	    $update_sql_array = array();
                if($value!='ONBLURDONTRUN'){
                    if( $type == '' ){			
                            $update_sql_array[$option] = ucwords($value);

                            $this->db->update(CASES, $update_sql_array, 'case_id', $case_id);
                            return ucwords($value);
                    } else {
                            $update_sql_array["case_id"] = $case_id;
                            $update_sql_array["option_id"] = $option;
                            $update_sql_array["SubOption"] = $value;

                            $this->db->insert('cases_suboptions', $update_sql_array);
                    }
                } else {
                    $result = $this->db->fetch_assoc($this->db->query("SELECT * FROM cases WHERE case_id = '$case_id"));
                    return ucwords($result[$option]);
                }
	 }
	 function delete_case($case_id){
             $this->db->query("DELETE FROM cases WHERE case_id = '$case_id'");
             $this->db->query("DELETE FROM cases_activity WHERE case_id = '$case_id'");
         }
         
	 function display_case_options( $module_name='', $case_id='' , $overide=array() ){
                $options = array();
                $options['option'] = '';
                foreach( $overide as $n => $v ){
                    $options[$n] = $v;
                }
		$div_id = 'div_'.$module_name;
		$module_array = explode(".",$module_name);
		$module_array = explode("_",$module_array[0]);
		$module = $module_array[1] . ' ' . $module_array[2];
		
		if( $module_array[2] == 'Status' ){
		      $option = $module_array[2];
		} elseif( $module_array[1] == 'Owner' ){
		          $option = $module_array[1];
		 } else {
		          $option = $module_array[1] . '' . $module_array[2];
		 
		   }
		ob_start();
		 ?>
			<select name="<?php echo $module_name; ?>" id="<?php echo $module_name; ?>"
                                onblur="setTimeout(slimcrm.cases.refresh,100);"
			        onchange="javascript: 
                                        var option = this.value;
                                        var module_name = '<?php echo $module_name; ?>';
                                        var case_id = '<?php echo $case_id; ?>';
                                        var active_fct = '<?php if( $active_fct ){echo "true";} else { echo "false"; } ?>';
                                        var any_fct = '<?php if( $any_fct ){echo "true";} else { echo "false"; } ?>';
                                                if(this.value=='status_cancel' ){
                                                    slimcrm.confirm(
                                                        {
                                                            title: 'Cancel Case <?php echo $case_id; ?>?',
                                                            question: 'Cancel Case <?php echo $case_id; ?>?',
                                                            yes: function(){
                                                                case_creation.delete_case( 
                                                                    '<?php echo $case_id; ?>' , 
                                                                    { 
                                                                        onUpdate: function(response,root){
                                                                            $('#right_bottom_panel').html('');
                                                                        }
                                                                    }
                                                                );
                                                            }
                                                        }
                                                        
                                                    );
                                                } else if ( active_fct == 'true' && this.value == 'completed' ){
                                                    slimcrm.close_case();
                                                } else {    
                                                 
					         <?php 
                                                 
                                                 if( $case_id != '' ){ ?>
                                                    <?php if( $any_fct == true ){ echo "$('.add_flowchart').dblclick().attr('disabled',true).parent().click(function(){});"; 
                                                    
                                                    } ?>
					             case_creation.display_case_options_link('<?php echo $module_name; ?>',
																		 '<?php echo $case_id; ?>',
																		 option,
																	 {preloader:'prl',
																	 onUpdate:function(response,root){
														document.getElementById('<?php echo $div_id; ?>').innerHTML = response;
														
														case_creation.set_option_by_case(option,
																						 '<?php echo $option; ?>',
																						 '<?php echo $case_id; ?>',
																					 {preloader:'prl'
																		<?php if( $module_array[2] == 'Type' ){ ?>
																					 ,
																					 onUpdate:function(response,root){
														case_creation.unset_sub_option('<?php echo $case_id; ?>',
														                               '<?php echo 'Case'.$module_array[2]; ?>',
														                    {preloader:'prl'});
														case_creation.display_case_type_option('<?php echo 'Case '.$module_array[2]; ?>',
															                                  option,
																							  '<?php echo $case_id; ?>',
																							  {target:'casecreation_caseType'}
															); }
															<?php } ?>
																						 });			 
																				 
					                            }});
							  <?php } elseif( $case_id == '' and $module_array[2] == 'Type' ) { ?>
							               case_creation.display_case_type_option('<?php echo 'Case '.$module_array[2]; ?>',
																				  option,
																				  '<?php echo $case_id; ?>',
																				  {target:'casecreation_type_option'});
															            document.getElementById('hidden_option').value = 'none';
							  
							 
											<?php } ?> }
                                                          "
					
												>
			  <option value="">-Select-</option>
			  <?php
			  if( $module_array[1] == 'Owner' ){
                             
			      echo $this->array2options( $this->get_owner_name('' , $options['option'] ), 'user_id', 'first_name', array('second_val'=>'last_name') );
			  } else {
			      echo $this->array2options( $this->get_dropdown( DROPDOWN_OPTION, $module  ), 'identifier', 'name' , array('selected' => $options['option'] ) );
			   } ?>
                          <?php
                            if($option=="Status"){
                                $result = $this->db->query("SELECT * FROM assign_flow_chart_task WHERE module='cases' AND module_id='$case_id'");
                                if( $this->db->num_rows($result) == 0 ){
                                    echo "<option value='status_cancel'>Cancel</option>";
                                }
                            } 
                          ?>
			</select>
		
	 <?php
	 $html=ob_get_contents();
	 ob_end_clean();
	 return $html;
	 }
	 
	 function display_case_options_link( $module_name='', $case_id='', $option='' ){
	    
		$div_id = 'div_'.$module_name;
		$module_array = explode(".",$module_name);
		$module_array = explode("_",$module_array[0]);
		$module = $module_array[1] . ' ' . $module_array[2];
		if( $module_array[1] == 'Owner' ){
		    $owner = $this->get_owner_name( $option );
			foreach( $owner as $options ){
			         $display = $options["first_name"] . " " . $options['last_name'];
			}
		} else {
		    $display = $option;
		 }
		 ob_start();
                 //$display="$module_name:$case_id:$option";
		?>
	    <a href="javascript:void(0);" onclick="case_creation.display_case_options('<?php echo $module_name; ?>',
			                                                                      '<?php echo $case_id; ?>', {'option': '<?php echo $option; ?>'} ,
																	              {preloader:'prl',
																				  target:'<?php echo $div_id; ?>'});"><?php if( $display == '' ){ $display = 'No '.$module; } echo ucwords($display); ?></a>
	 <?php
	 $html=ob_get_contents();
	 ob_end_clean();
	 return $html;
	 }
	 
	 function get_sub_options( $case_id='', $option='' ){
	    $result = $this->db->query("SELECT * FROM cases_suboptions WHERE case_id='$case_id' AND option_id ='$option'");
	    $return = array();
	    while($row=$this->db->fetch_assoc($result)){
			  $return[] = $row;
	    }
        return $return;
	 }
	 
	 function display_case_type_option( $module_name='', $option='', $case_id='' ){
	    
	    ob_start();
		$module = strtolower($module_name . ' ' . ucwords($option));
		
		//echo '<span id="casecreation_option" class="casecreation_heading">' . ucwords($option) . '</span>';
		?>
		  <select name="<?php echo $module_name; ?>" id="<?php echo $module_name; ?>" style="width:85px;"
onchange="javascript: 
    var suboption = this.value;
    <?php if( $case_id != '' ){ ?>
        if( $(this).val() != 'Cancel'){
        case_creation.set_option_by_case(
            suboption,
            '<?php echo $module; ?>',
            '<?php echo $case_id; ?>',
            '1',
            {
                preloader:'prl',
                onUpdate:function(response,root){
                    case_creation.display_case_sub_option_name('<?php echo $module_name; ?>',
                        '<?php echo $option; ?>',
                        '<?php echo $case_id; ?>',
                        {
                            preloader:'prl',
                            onUpdate:function(response,root){
                                document.getElementById('casecreatin_subOption_display').innerHTML = response;
                            }
                        }
                    );
                }
            });
            } else {
            slimcrm.confirm({ yes: function(){
                case_creation.set_option_by_case(
                suboption,
                '<?php echo $module; ?>',
                '<?php echo $case_id; ?>',
                '1',
                {
                    preloader:'prl',
                    onUpdate:function(response,root){
                        case_creation.display_case_sub_option_name('<?php echo $module_name; ?>',
                            '<?php echo $option; ?>',
                            '<?php echo $case_id; ?>',
                            {
                                preloader:'prl',
                                onUpdate:function(response,root){
                                    document.getElementById('casecreatin_subOption_display').innerHTML = response;
                                }
                            }
                        );
                    }
                });
            } , question: 'Do you rilly want to delete this',title: 'delete object'});
            }
    <?php } else { ?>
        selected_suboptions(suboption);
        case_creation.display_typeSubOption('<?php echo $module_name; ?>',
        '<?php echo $option; ?>',
        suboption,
        document.getElementById('hidden_option').value,
        {preloader:'prl',
        onUpdate:function(response,root){
        document.getElementById('casecreatin_subOption_display').innerHTML = response;
        }});
    <?php } ?>	
">
			  <option value="">-Select-</option>
			  <?php
			      echo $this->array2options( $this->get_dropdown( DROPDOWN_OPTION, $module ), 'name', 'name' );
			  ?>
			</select></br>
			
			<span id="casecreatin_subOption_display" class="casecreatin_subOption_display">
			  <?php if( $case_id != '' ) echo $this->display_case_sub_option_name( $module_name, $option, $case_id ); ?>
			</span>
			
			<script>
			   function selected_suboptions(suboption){
					var id = '';
					id = document.getElementById('hidden_option').value;
					id += '~' + suboption ;
					document.getElementById('hidden_option').value = id;
					//alert(document.getElementById('hidden_option').value);
			   }
			</script>
	 <?php
	 $html=ob_get_contents();
	 ob_end_clean();
	 return $html;
	 }
	 
	 function display_typeSubOption( $module_name='', $option='', $suboption='', $selected_suboption='' ){
	    ob_start();
		$selected = explode("~",$selected_suboption);
		$num = count($selected);
		for($i=1;$i<$num;$i++){
		     
	         echo $selected[$i];
			 if( $selected[$i+1] != '' ) echo ','; ?> 
			 <?php /*?><a href="javascript:void(0);" onclick="javascript:
			                                         case_creation.update_subOption('<?php echo $module_name;?>',
																					'<?php echo $option;?>',
																					'',
																					'<?php echo $selected_suboption;?>',
													{onUpdate:function(response,root){
													case_creation.display_typeSubOption('<?php echo $module_name;?>',
																						'<?php echo $option;?>',
																						'<?php echo $option;?>',
																						'<?php echo $selected_suboption;?>',
													{target:'casecreatin_subOption_display'});}});"><img src="images/trash.gif" alt="delete" /></a><?php */?>
		<?php
		}
		//echo $selected_suboption;
		
	 $html=ob_get_contents();
	 ob_end_clean();
	 return $html;
	 }
	 
	 function unset_sub_option( $case_id='', $option='', $suboption='' ){
	    if( $suboption != '' ){
			$result = $this->db->query("DELETE FROM cases_suboptions WHERE case_id='$case_id' AND option_id ='$option' AND SubOption ='$suboption'");
		} else {
		    $this->db->query("DELETE FROM cases_suboptions WHERE case_id='$case_id'");
		    $this->db->query("UPDATE".CASES." SET '$option' = '' WHERE case_id='$case_id'");
		 }
	 }
	 
	 function display_case_sub_option_name( $module_name='', $option='', $case_id='' ){
	    $module = strtolower($module_name . ' ' . ucwords($option));
	    $sub_option = $this->get_sub_options( $case_id, $module );
		
		ob_start();
		//print_r($sub_option);
		
		foreach( $sub_option as $options ){
	             echo $options["SubOption"]; ?>
				 <a href="javascript:void(0);" 
				    onclick="javascript:case_creation.unset_sub_option('<?php echo $options["case_id"] ?>',
					                                                   '<?php echo $options["option_id"] ?>',
																	   '<?php echo $options["SubOption"] ?>',
																	   {preloader:'prl',
																	   onUpdate:function(response,root){
														
														case_creation.display_case_type_option('<?php echo $module_name; ?>',
															                                  '<?php echo $option; ?>',
																							  '<?php echo $case_id; ?>',
																							  {target:'casecreation_caseType'}
															); 
					                    }});"><img src="images/trash.gif" alt="delete" /></a><br/>
		
	  <?php  }
	 $html=ob_get_contents();
	 ob_end_clean();
	 return $html;
	 }
         
         function change_order_number( $case_id , $order_number ){
             
         }
         
         
         
         
         function change_customer( $module_name , $module_id , $case_id , $display_name = ''){
             $update = array();
             $update["contact_module_name"] = $module_name;
             $update["contact_module_id"] = $module_id;
             $this->db->update('cases', $update, 'case_id', $case_id);
             $my_display_name = addslashes( str_replace(array("'" , '"' , '`'), '', $display_name) );
             if( $module_id != '' && $module_id = '0'){
                $this->db->query("INSERT INTO eapi_account_displayname ( `account_id` , `display_name` ) VALUES ( '$module_id' , '$my_display_name' ) ON DUPLICATE KEY UPDATE display_name = '$my_display_name'");
             }
             return $this->display_customer_option($display_name , $case_id);
             
         }
          function change_owner(  $owner , $case_id , $display_name = ''){
             $update = array();
             
             $update["Owner"] = $owner;
             $this->db->update('cases', $update, 'case_id', $case_id);
             //$this->db->query("INSERT INTO eapi_account_displayname ( `account_id` , `display_name` ) VALUES ( '$module_id' , '$display_name' ) ON DUPLICATE KEY UPDATE display_name = '$display_name'");
             
             return $this->display_owner_option($owner , $case_id);
             
         }
	 function display_customer_option( $name , $case_id ){
             ob_start();
             if( $name == ''){ $name = 'No Customer Found';}
             $case_info = $this->get_cases_by_id($case_id);
             ?>
             <a id="case_cust_name<?php echo $case_id;?>" 
                        
                        onclick="$('#casecreation_customer_input<?php echo $case_id; ?>').show().autocomplete(
                 {
                     source: 'contact_lookup.php' , 
                     select: function( event, ui ) { 
                         casecreation.change_customer( 
                            'CONTACTS' , 
                            ui.item.contact_id , 
                            '<?php echo $case_id; ?>' , 
                            ui.item.Studio , 
                            { target: 'casecreation_customer_<?php echo $case_id; ?>', onUpdate: function(response,root){$('.right_tab_right_arrow_active').click();}} 
                        );}});
                        
                    $(this).hide();" ><?php echo $name; ?></a>
                    <input onblur="setTimeout(slimcrm.cases.refresh,100);"  id="casecreation_customer_input<?php echo $case_id; ?>" value="<?php echo $name; ?>"style="display: none;" >
                            
             <?php
             $html=ob_get_contents();
            ob_end_clean();
            return $html;
             }
             	 function display_owner_option( $user_id , $case_id ){
             ob_start();
             $user_info = $this->get_user_by_id($user_id);
             $name = $user_info['0']['first_name'] . ' ' . $user_info['0']['last_name'];
             if( $name == '' || ( $user_info['0']['first_name'] == '' && $user_info['0']['last_name'] == '' )){ $name = 'No User Assigned';}
             $case_info = $this->get_cases_by_id($case_id);
             ?>
             <a id="case_user_id<?php echo $case_id;?>" 
                        onclick="slimcrm.case_owner_single_click=true;setTimeout( 
                                function(){ 
                                    if(slimcrm.case_owner_single_click){
                                        casecreation.change_owner( 
                                            '<?php echo $_SESSION['user_id']; ?>' , 
                                            '<?php echo $case_id; ?>' , 
                                            '<?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name'];?>' , 
                                            { target: 'casecreation_user_<?php echo $case_id; ?>'} 
                                        );
                                    }
                                } , 700 );"
                                
                        ondblclick="
                            slimcrm.case_owner_single_click=false;
                            $('#casecreation_user_input<?php echo $case_id; ?>').show().autocomplete(
                 {
                     source: 'user_lookup.php' , 
                     select: function( event, ui ) { 
                         casecreation.change_owner( 
                            ui.item.user_id , 
                            '<?php echo $case_id; ?>' , 
                            ui.item.Studio , 
                            { target: 'casecreation_user_<?php echo $case_id; ?>'} 
                        );}});
                    $(this).hide();" ><?php echo $name; ?></a>
                    <input onblur="setTimeout(slimcrm.cases.refresh,100);"  id="casecreation_user_input<?php echo $case_id; ?>" value="<?php echo $name; ?>"style="display: none;" >
                            
             <?php
             $html=ob_get_contents();
            ob_end_clean();
            return $html;
             }
             
    function display_edit_case_order( $case_id ){
        ob_start();
        $case = $this->db->fetch_assoc($this->db->query("SELECT contact_module_name , contact_module_id , OrderNumber FROM cases WHERE case_id = '$case_id'"));
        if( $case["contact_module_name"] == "eapi_ACCOUNT"){
            $orders = $this->page->eapi_account->get_account_orders( $case["contact_module_id"] );
            //echo $case["contact_module_id"];
            
            ?>
         <script>
             function edit_order(){
                var display_edit_case_order = [ '<?php echo implode( "','", $orders) ?>' ];
                $('#display_edit_case_order_input_<?php echo $case_id; ?>').autocomplete({ 
                    source: display_edit_case_order,
                    select: function( event , ui ){
                       
                       $(this).val( ui.item.value ); 
                       casecreation.edit_case_order('<?php echo $case_id; ?>' , ui.item.value ,{target: 'casecreation_order<?php echo $case_id; ?>'});
                       //email_client.test_uiitem = ui.item;
                       //alert( ui.item );
                    }});
             }
          
         </script>
         <input value="<?php echo $case['OrderNumber']; ?>" onblur="$('.right_tab_right_arrow_active').click();" id="display_edit_case_order_input_<?php echo $case_id; ?>" class="display_edit_case_order" />
                
                <?php
            
        } else {
            ?>
        <input  onblur="$('.right_tab_right_arrow_active').click();" id="display_edit_case_order_input_<?php echo $case_id; ?>" class="display_edit_case_order" /><button onclick="casecreation.edit_case_order('<?php echo $case_id; ?>' ,$('#display_edit_case_order_input_<?php echo $case_id; ?>').val() ,{onUpdate: function(response , root ){ if( response == 'invalid' ){ alert('Invalid Order Number, Please enter a valid order');} else { $('#casecreation_order<?php echo $case_id; ?>').html(response);$('.right_tab_right_arrow_active').click(); } } } );" ><div class="edit_button in_button" >&nbsp;</div></button>

         <?php
            
        }
        $html=ob_get_contents();
            ob_end_clean();
            return $html;
        
    }
    
    function edit_case_order( $case_id , $order_id){
        $case_info = $this->db->fetch_assoc($this->db->query("SELECT * FROM cases WHERE case_id = '$case_id'"));
        $update = array();
        $eapi_api = new eapi_api();
        $json = $eapi_api->order_detail_lookup($order_id);
        $order_info = json_decode($json);
        if( $order_info->Id == $order_id && ( $order_info->Account == $case_info["contact_module_id"] || $case_info["contact_module_id"] == '0' || $case_info["contact_module_id"] == '' )){
            $update["OrderNumber"] = $order_id;
            $update['contact_module_id'] = $order_info->Account;
            $update['contact_module_name'] = 'eapi_ACCOUNT';
            $this->db->update('cases', $update, 'case_id', $case_id);
            return $this->display_order_div( $case_id , $order_id);
        }
        return 'invalid';
    }
    
    function display_order_div( $case_id , $order_number){
        ob_start();
        ?>
        <a href="javascript:void(0);" onclick="casecreation.display_edit_case_order('<?php echo $case_id; ?>' , {target: 'casecreation_order<?php echo $case_id; ?>' , onUpdate: function(a,b){edit_order();}})" ><?php echo $order_number; ?></a>
  <?php $html=ob_get_contents();
            ob_end_clean();
            return $html;
                   }
    function edit_subject( $case_id ){
        $options_arr = $this->get_cases_by_id( $case_id );
        $options = $options_arr[0];
        if( $options['subject'] == ''){
            $text = "";
        }   else {
            $text = $options['subject'];            
        } 
        ob_start();
        ?>
        <input 
            class="case_subject"
            onchange="casecreation.set_subject( $(this).val(), '<?php echo $case_id; ?>' , { target: 'casecreation_subject_<?php echo $case_id; ?>'});" 
            value="<?php echo $text; ?>"
            onblur="casecreation.subject_link('<?php echo $case_id; ?>' , { target: 'casecreation_subject_<?php echo $case_id; ?>'})">
        
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
    function set_subject( $subject ,  $case_id ){
        $this->db->update('cases', array('subject' => $subject ), 'case_id', $case_id);
        return $this->subject_link( $case_id );
    }
    function subject_link( $case_id ){
        $options_arr = $this->get_cases_by_id( $case_id );
        $options = $options_arr[0];
        if( $options['subject'] == ''){
            $text = "click here for subject";
            $style = "color: lightgray !important;";
        }   else {
            $text = $options['subject'];  
            $style='';
        } 
        ob_start();
        ?>
        <a  style="<?php echo $style; ?>" onclick="casecreation.edit_subject( '<?php echo $case_id; ?>' , { target: 'casecreation_subject_<?php echo $case_id; ?>'});" ><?php echo $text; ?></a>
        
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
    function count_cases( $module_name , $module_id ){
        $case_count = $this->db->fetch_assoc($this->db->query("SELECT  count(case_id) count FROM `cases` WHERE Status = 'Active' AND contact_module_name = '$module_name' AND contact_module_id = '$module_id'"));
        return $case_count['count'];
    }
    function display_case( $case_id ){
        ob_start();
            $options_arr = $this->get_cases_by_id( $case_id );
            $options = $options_arr[0];
			 //print_r($options);
			 $name = $this->get_display_name_by_module( $options["contact_module_name"], $options["contact_module_id"] );
			 $emails = $this->get_mail_by_case_id( $options["case_id"] );
			 
                    $case_origin = 'casecreation_Case_Origin.' . $options["case_id"];
		    $case_owner = 'casecreation_Owner.' . $options["case_id"];
		    $case_type = 'casecreation_Case_Type.' . $options["case_id"];
		    $case_status = 'casecreation_Case_Status.' . $options["case_id"];
                    $case_option = 'casecreation_sub_option' . $options["case_id"];
		   ?>
			 <tr>
			   <th colspan="2"><div class="casecreation_case">Case: <?php echo $options["case_id"]; ?></div></th>
			   <th><div class="casecreation_case"><?php if( $options['contact_module_id'] != '' && $options['contact_module_id'] != NULL && $options['contact_module_id'] != '0'){
                               echo "Acct #:".$options['contact_module_id'];
                           };?></div></th>
                            <th><div class="casecreation_case"><a onclick="<?php echo $this->page->page_link('accountdashboard' , array('account_name' => '' , 'account_id' => $options['contact_module_id']) ); ?>" ><?php echo $this->count_cases(  $options['contact_module_name'] ,  $options['contact_module_id'] ); ?></a></div></th>
                           
			 </tr>
                         <tr>
                             <td colspan="4" > <div class="flyout_compose">
			       <a href="javascript:void(0);"
						     onclick="javascript: email_client.compose_email('<?php echo $options['contact_module_name']; ?>' , '<?php echo $options['contact_module_id']; ?>');"><button>compose email <div class="compose_button in_button"/>&nbsp;</div></button></a>
				  
                  <button onclick="$.getJSON('print_label.php?case_id=<?php echo $options['case_id']; ?>',function(data){ alert('label printed');})" >print label</button>
                  </div></td>
                         </tr>
                         <tr>
                             <td colspan="4" >
                                 <div id="casecreation_subject_<?php echo $options["case_id"]; ?>" class="casecreation_subject">
                                     <?php echo $this->subject_link($options["case_id"]); ?>
                                 </div>
                             </td>
                         </tr>
			 <tr>
			   <td><b class="casecreation_text">Customer :</b></td>
			   <td colspan="3"><div class="casecreation_customer" id="casecreation_customer_<?php echo $options["case_id"]; ?>"><?php echo $this->display_customer_option( $name , $options["case_id"] ); ?></div></td>
			 </tr>
<!--			 <tr>
			   <td><b class="casecreation_text">Order :</b></td>
			   <td colspan="3"><div class="casecreation_order" id="casecreation_order<?php echo $options["case_id"]; ?>"><?php echo $this->display_order_div( $options["case_id"] , $options["OrderNumber"] ); ?> </div></td>
			 </tr>
                         <tr>
			   <td><b class="casecreation_text">Order Files:</b></td>
			   <td colspan="3"><div class="casecreation_order" ><a target="_blank" href="order_images.php?case_id=<?php echo $options["case_id"]; ?>" >Order Files Link</a></div></td>
			 </tr>-->
			 <tr>
			   <td><b class="casecreation_text">Case Origin :</b></td>
			   <td colspan="3">
			     <div id="<?php echo 'div_'.$case_origin; ?>" class="casecreation_caseorigin">
				   <?php
				   if( $options["CaseOrigin"]  == '' ){ echo $this->display_case_options( $case_origin, $options["case_id"] ); }
				   else { echo $this->display_case_options_link( $case_origin, $options["case_id"], $options["CaseOrigin"] ); }
				   ?>
			     </div>
			   </td>
			 </tr>
                         <tr>
			   <td><b class="casecreation_text">Owner :</b></td>
			   <td colspan="3"><div class="casecreation_owner" id="casecreation_user_<?php echo $options["case_id"]; ?>"><?php echo $this->display_owner_option( $options["Owner"] , $options["case_id"] ); ?></div></td>
			 </tr>
<!--			 <tr>
			   <td><b class="casecreation_text">Owner :</b></td>
			   <td colspan="3">
			     <div id="<?php echo 'div_'.$case_owner; ?>" class="casecreation_owner">
				   <?php
				   if( $options["Owner"]  == 0 ){ echo $this->display_case_options( $case_owner, $options["case_id"] ); }
				   else { echo $this->display_case_options_link( $case_owner, $options["case_id"], $options["Owner"] ); }
				   ?>
			     </div>
			   </td>
			 </tr>-->
                         <tr>
			   <td><b class="casecreation_text">Case Status :</b></td>
			   <td colspan="3">
			     <div id="<?php echo 'div_'.$case_status; ?>" class="casecreation_casestatus">
				   <?php
				   if( $options["Status"]  == '' ){ echo $this->display_case_options( $case_status, $options["case_id"] ); }
				   else { echo $this->display_case_options_link( $case_status, $options["case_id"], $options["Status"] ); }
				   ?>
			     </div>
			   </td>
			 </tr>
			 <tr>
			   <td><b class="casecreation_text">Case Type :</b></td>
			   <td colspan="3">
			     <div id="<?php echo 'div_'.$case_type; ?>" class="casecreation_casetype">
				   <?php
				   if( $options["CaseType"]  == '' ){ echo $this->display_case_options( $case_type, $options["case_id"] ); }
				   else { echo $this->display_case_options_link( $case_type, $options["case_id"], $options["CaseType"] ); }
				   ?>
			     </div>
			   </td>
			 </tr>

                         
			 <tr>
			   <td colspan="4">
				 <span id="casecreation_caseType">
				   <?php
				   if( $options["CaseType"]  != '' ){ echo $this->display_case_type_option( 'Case Type', $options["CaseType"], $options["case_id"] ); }
				   ?>
				 </span>
			   </td>
			 </tr>			 
			 <tr>
                             <td></td>
			   <td colspan="3"></td>
			 </tr>
             <tr>


            <?php
            
            $this->timetracker = new TimeTracker();
            $this->timetracker->setModuleName('CASES');
            $this->timetracker->setModuleID($case_id);
            
            // get the previous entries
            $previous_entries = $this->timetracker->getTimeEntries();
            
            // check for any open entries (entries without an end time)
            $open_entry_id = $this->timetracker->getOpenEntry($previous_entries);
            
            if (empty($open_entry_id)) {
                // create a new entry when button is clicked.
                $button_action = 'tt.newEntry(\''.$_SESSION['user_id'].'\',\'CASES\',\''.$case_id.'\');';
                $button_text = 'Start';
            } elseif ( count($open_entry_id) > 1) {
                // there is more then one open entry.
                $button_action = 'tt.setTimeEnd(\'CASES\',\''.$case_id.'\',\''.$_SESSION['user_id'].'\')';
                $button_text = 'Stop';
            } else {
                $button_action = 'tt.setTimeEnd(\'CASES\',\''.$case_id.'\',\''.$_SESSION['user_id'].'\')';
                $button_text = 'Stop';
            }
                
            ?>
            <tr>
                <th><b>Time Track:</b></th>
                <td colspan="2"><input id="tt_button" type="button" value="<?php echo $button_text; ?>" onclick="<?php echo $button_action; ?>"></td>
                <td></td>
            </tr>             
                <td colspan="4">            
            <table id="tt_table" style="width:100%; margin-bottom: 1.5em;">
            
            <?php
            foreach($previous_entries as $entry) {
                $start_stt = strtotime($entry['start_time']);
                $start_time = date('g:i', $start_stt);
                $start_date = date('n/j', $start_stt);
                
                if ($entry['end_time'] == '0000-00-00 00:00:00') {
                    $end_time = '--';
                    $diff = '--';
                } else {
                    $end_stt = strtotime($entry['end_time']);
                    $end_time = date('g:i', $end_stt);
                    
                    $diff_time = $end_stt - $start_stt;
                    
                    $diff = $diff_time / (60*60);
                    $diff = number_format($diff, 1);                    
                }
            ?>
            <tr data-id="<?php echo $entry['time_tracker_id']; ?>" >
                <td><?php echo $start_time.' - '.$end_time; ?></td>
                <td><?php echo $start_date; ?></td>
                <td><?php echo $diff; ?> hr</td>
                <td><img onclick="tt.delTimeEntry(<?php echo $entry['time_tracker_id']; ?>);" src="images/trash_can.png" alt="delete" /></td>
            </tr>                 
                <?php
            }
                ?>
            </table>   
                </td>
            </tr>
            
			 <tr>
			   <th><b class="casecreation_heading"> Bucket Tasks :</b></th>
			   <td colspan="3"></td>
			 </tr>
                         <?php /*
			 <tr>
			 	<td colspan="4"><div id="show_fct_<?php echo $options["case_id"]; ?>"><?php echo $this->showFlowChartTask('cases',$options["case_id"]); ?></div></td>
			 </tr> */ ?>
                         <tr>
                             <td colspan="4" ><div class="fct_image">
                                     
				   <?php echo $this->FlowChartDiv('cases',$options["case_id"],'case'); ?>
				 </div></td>
                         </tr>
			 <tr>
			   <th><b class="casecreation_heading">Activity :</b></th>
			   <td>
			    
			   </td>
			   <td colspan="2" align="right">
			     
			   </td>
			 </tr>
			 <tr>
			   <td colspan="4">
				 <div class="case_creation_activity_div">
				   <?php
                                   $activity = array();
                                     $result = $this->db->query("SELECT a.* , b.first_name , b.last_name FROM assign_flow_chart_task a LEFT JOIN tbl_user b ON a.completed_by = b.user_id WHERE task_status = 'Complete' AND module = 'cases' AND module_id = '" . $options['case_id'] . "'");
                                     while( $row = $this->db->fetch_assoc($result)){
                                         $activity[strtotime($row['completion_date'] ) .  rand(100,999)] = array('type' => 'bucket' , 'data' => $row );
                                         
                                     }
                                   
				     foreach( $emails as $email ){
                                         $activity[$email['unixtime'] . rand(100,999)] = array( 'type' => 'email' , 'data' => $email );
                                          
                                         
                                     }
                                     $activity_log = $this->page->get_activity_log_by_module('cases' , $case_id);
                                     foreach( $activity_log as $act){
                                         $activity[strtotime($act['timestamp'])] = array('type' => 'activity' , 'data' => $act );
                                     }
                                     ksort($activity) ;
                                     $activity = array_reverse($activity, true);
                                     echo "<table>";
                                     foreach( $activity as $n => $a ){
                                         if( $a['type'] == 'email'){
                                             echo '<tr><td style="vertical-align: top;white-space: nowrap;" >eml: ' . date('m/d' , $a['data']['unixtime'] ) . ':</td><td><p>' .$this->display_email_activity($a['data']) . '</p></td></tr>'; 
                                         }
                                         if( $a['type'] == 'bucket'){
                                             echo '<tr><td style="vertical-align: top;" >bckt:' . date('m/d' , strtotime($a['data']['completion_date'])) . ":</td><td><p title='" . $a['data']['first_name'] . " " . $a['data']['last_name'] . "'>" . $a['data']['completion_result'] . '</p></td></tr>';
                                         }
                                         if( $a['type'] == 'activity' && $a['data']['action'] == 'due_date_change'){
                                             
                                             echo '<tr><td style="vertical-align: top;" >date:' . date('m/d' , strtotime($a['data']['completion_date'])) . ":</td><td><p title='" . $a['data']['first_name'] . " " . $a['data']['last_name'] . "'>" . date( "Y-m-d" , strtotime( $a['data']['to'] ) ) . '</p></td></tr>';
                                         }
                                     }
                                     echo "</table>";
                                         
                                         ?>
                                    
				 </div>
			   </td>
			 </tr>
			 <tr>
			   <td><b class="casecreation_heading">Notes :</b></td>
			   <td colspan="3" align="left">
                               <span id="casecreation_note_add">
				   <a href="javascript:void(0);"
				       onclick="javascript: <?php $md5_rand_id = md5( rand(10 , 999999999999) . "note" . $case_id ); ?> 
                            document.getElementById('casecreation_note_creat_tr').style.visibility='visible';
                            document.getElementById('casecreation_note_add').style.display='none';
                            
                            $('#custom_file_upload_<?php echo $md5_rand_id; ?>').uploadify({
                                'wmode': 'transparent',
                                'hideButton' : true,
                                'uploader'       : './upload_attachment/uploadify.swf',
                                'script'         : './uploadify.php?rand=<?php echo $md5_rand_id;?>',
                                'cancelImg'      : './upload_attachment/cancel.png',
                                'folder'         : '<?php echo $md5_rand_id;?>',
                                'multi'          : true,
                                'auto'           : true,
                                'queueID'        : 'custom_file_upload_status<?php  echo $md5_rand_id; ?>',
                                'queueSizeLimit' : 3,
                                'simUploadLimit' : 3,
                                'buttonImg'     : './images/uploadify_icon2.png',
                                'removeCompleted': false,
                                'onSelectOnce'   : function(event,data) {},
                                'onAllComplete'  : function(event,data) {},
                                'onCancel' : function(event,ID,fileObj,data){
                                    alert( $.ajax('uploadify.php?rand=<?php echo $md5_rand_id;?>&action=remove&filename=' + fileObj.name) );
                                }
                            });                            
                            
                                   ">add note</a><script> function set_loader_img(){ }</script>
				 </span>
			   </td>
			 </tr>
			 <tr id="casecreation_note_creat_tr" style="visibility:collapse;">
			   <td colspan="4" align="right">
			     <span id="casecreation_note_creat_span">
				   <textarea id="case_note" rows="4" cols="32"></textarea></br>
                                   <form name="Filedata" id="Filedata" action="javascript: void();" >
                                       <div id="custom_file_upload_status<?php  echo $md5_rand_id; ?>" style="display: inline-block;width: 175px; height: 20px;"></div>
                                       
                 
                                   </form>
				   <input id="custom_file_upload_<?php  echo $md5_rand_id; ?>" type="file" name="Filedata" class="left_<?php echo $panel; ?> custom_file_upload_<?php echo $panel.'_'.$mid.'_'; ?>" /><a href="javascript:void(0);"
    onclick="javascript:case_creation.set_note('<?php echo $options["case_id"]; ?>',
    '<?php echo $_SESSION['user_id']; ?>',
    document.getElementById('case_note').value,
    '<?php echo $md5_rand_id; ?>' ,
    {preloader:'prl',
    onUpdate:function(response,root){
        
    
    document.getElementById('casecreation_note_creat_tr').style.visibility='collapse';

    casecreation.display_note_by_id('<?php echo $options["case_id"]; ?>',
    {preloader:'prl',
    onUpdate:function(response,root){
    document.getElementById('casecreation_allnote_span').innerHTML=response;
    document.getElementById('casecreation_note_add').style.display='block';
    $('.right_tab_right_arrow_active').click();
    }});
    }}
);"><b class="casecreation_note_link"><button style="position:relative;top: -10px;" >Add Note<div class="add_button in_button"></div></button></a>
                                   
				 </span>
			   </td>
			 </tr>
			 <tr>
			   <td colspan="4">
			     <span id="casecreation_allnote_span">
			       <?php echo $this->display_note_by_id( $options["case_id"] ); ?>
				 </span>
			   </td>
			 </tr>
			 <?php
                            $html=ob_get_contents();
            ob_end_clean();
            return $html;
    }     
          //"
         
    function display_case_creation( $module_id , $module_name = 'EMAIL' ){
	   ob_start();
	   $user_id = $_SESSION['user_id'];
	   //$information = $this->get_owner_by_id( $user_id );
	   //$cases = $this->get_case_by_mid($module_id, $module_name)
	   //$cases = $this->get_cases_by_id( $module_id ); ?>
	   <div class="case_creation_div">
		   <table class="case_creation_table">
		   <?php
                        //echo $module_id . $module_name . print_r( $cases , true);
                        echo $this->display_case( $module_id );
		     //foreach( $cases as $options ){ echo $this->display_case( $options['case_id'] ); } ?>
		  </table>
	   </div>
    <?php
     $html=ob_get_contents();
	 ob_end_clean();
	 return $html;
     }

	 function showFlowChartTask($module_name='',$module_id=''){
		 ob_start();
		 $sql = "SELECT * FROM ".ASSIGN_FCT." a LEFT JOIN ".GLOBAL_TASK." b ON a.flow_chart_id=b.global_task_id LEFT JOIN ".TBL_USERGROUP." c ON b.department_id = c.group_id WHERE a.module = '$module_name' AND a.module_id = '$module_id' and a.task_status = 'Active'";
		 //echo $sql;
		 $result = $this->db->query($sql);
		 $global_task = new GlobalTask();
		 ?>
		 <table>
		 	<?php while($row = $this->db->fetch_assoc($result)){ ?>
				<tr>
					<td><span class="emaildashboard_menutitle"><?php echo $row['group_name']; ?></span> - </td>
					<td>&nbsp;<span class="emaildashboard_menutitle"><?php echo $row['name']; ?></span> - </td>
					<td>&nbsp;
							<?php 
							$owner_module_name = $global_task->getOwnerModuleName($row['owner_module_name'],$row['owner_module_id']);
							echo $global_task->displayOwnerModuleLink($row['module'],$row['module_id'],$row['flow_chart_id'],$owner_module_name, $row['chart_assign_id']); ?>
                    </td>
					<td>&nbsp;<span class="emaildashboard_menutitle">
							<?php 
							$date_part = explode(" ",$row['created_date']);
							$dt = explode("-",$date_part[0]);
							echo $dt[1]."/".$dt[2]; ?>
                        </span></td>
				</tr>
			<?php } ?>
		 </table>
		 <?php 
		 $html=ob_get_contents();
		 ob_end_clean();
		 return $html;
	 }
	 
	 function set_note( $case_id='', $user_id='', $note='' , $file_id='' ){
             ob_start();
	    $insert_sql_array = array();				
		$insert_sql_array[module_id] = $case_id;
		$insert_sql_array[user_id] = $user_id;
		$insert_sql_array[description] = $note;
		$insert_sql_array[module_name] = 'CASES';
	
		$this->db->insert(CASE_NOTES,$insert_sql_array);
                $note_id = $this->db->last_insert_id();
                $this->page->log_activity( 'CASES' ,  $case_id , 'note_added' , '', $note , 'notes' , $note_id );
                //FILESERVER_LOCAL_PATH
                //TMP_UPLOAD
                $fileserver = new fileserver();
                echo TMP_UPLOAD . $file_id . "\n";
                echo is_dir( TMP_UPLOAD . $file_id ) . "\n";
                if( is_dir( TMP_UPLOAD . $file_id ) == true && $file_id != '' ){
                    $from_dir = TMP_UPLOAD . $file_id;
                    echo __LINE__ . "\n";
                    $dh = opendir($from_dir);
                    while( $file = readdir( $dh )){
                            if(is_file( $from_dir . "/" . $file )){
                                $filearr = array();
                                $filearr["tmp_name"] = $from_dir . "/" . $file;
                                $filearr["name"] = $file;
                                //var_dump( $filearr);
                                echo $fileserver->upload_file($note_id, 'notes', '', $filearr);
                            }
                    }
                }
                $html = ob_get_contents();
                ob_end_clean();
                //return $html;
	 }
	 
         
	 function display_note_by_id( $case_id='' ){
	    $note = $this->get_notes_by_module_id( 'CASES', $case_id );
            $fileserver = new fileserver();
		ob_start();?>
		<table class="casecreation_note_table">
		<?php
		if(is_array($note) == TRUE ){
		  foreach( $note as $notes ){
		?>
			 <tr>
			   <td>
			     <span class="casecreation_noteby"><?php echo $notes["first_name"].' '.$notes["last_name"].' '.$notes["n_time"]; ?></span>
			   </td>
			 </tr>
			 <tr>
			   <td style="word-wrap: break-word;" >
			     <div class="casecreation_notes"><?php echo $notes["description"]; ?></div>
			   </td>
                         </tr>
                         
                           <?php 
                                $files = $fileserver->get_files( $notes['note_id'] ,'notes');
                                if( count( $files) != 0 ){
                                   ?>
                         <tr>
                             <td>Files:
                         <?php 
                            foreach( $files as $file ){
                                echo "<a href='" . FILESERVER_REMOTE_PATH . $file['path'] . "' target='_BLANK' >" . $file['name'] . "</a>";
                            }
                               
                           ?> 
                            </td>
			 </tr> 
		 <?php  } 
                 
                 } 
                 
                 } ?>
		 </table>
	 <?php
	  $html=ob_get_contents();
	  ob_end_clean();
	  return $html;
	 }
	 
	 


}

?>