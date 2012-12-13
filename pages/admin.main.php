<?php
ini_set('display_errors' , 1);
ob_start();
    require_once('app_code/global.config.php');
    require_once('class/config.inc.php');
    require_once('class/class.email_client.php');
    require_once('class/class.flags.php');
    require_once('class/class.GlobalTask.php');
    require_once('class/class.smtp.php');
    require_once('class/class.display.php');
    require_once('class/class.casecreation.php');
    require_once('class/class.dynamicpage.php');
    require_once('class/class.FctSearchScreen.php');
    require_once('class/class.eapi_order.php');
    require_once('class/class.cases.php');
    require_once('class/class.welcome.php');
    ob_end_clean(); 
    $flags = new Flags();
    $page = new basic_page;
    $emaildash = new email_client();
    $casecreation = new case_creation($page);
    $run_on_start=array();
    $user=new user();
    $welcome=new welcome();
    $welcome_news = $welcome->get_news();
    $global_task = new GlobalTask();
    
    
    ?>
<script>

</script>

<table style="width: 80%;padding-left: 10%;padding-right: 10%;" ><tr><td style="width: 50%;vertical-align: top;">
<div class="admin_page_header page_header" >Admin Page</div>
<?php 
    if( $page->auth->inGroup( 'csradmin' ) ){
        ?>
<div style=" width: 550px" class="ui-accordion ui-accordion-icons" >
<h3 class="ui-accordion-header  a ui-helper-reset ui-state-default ui-state-active ui-corner-top single_accordion" 
    onclick="
        $('#manage_top_users').toggle();
        $(this).toggleClass('ui-state-active');$(this).children('span').toggleClass('ui-icon-triangle-1-s').toggleClass('ui-icon-triangle-1-e')" >
    <span class="ui-icon ui-icon-triangle-1-s"></span>
    <a href="#" tabindex="-1">Top Users</a>
</h3>
    <div id="manage_top_users" class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active"  role="tabpanel">


    <?php echo $emaildash->display_manage_top_list(); ?>
    </div>
</div>
<div style=" width: 550px" class="ui-accordion ui-accordion-icons" >
<h3 class="ui-accordion-header  a ui-helper-reset ui-state-default ui-state-active ui-corner-top single_accordion" 
    onclick="
        $(this).siblings('.ui-accordion-content').toggle();
        $(this).toggleClass('ui-state-active');
        $(this).children('span').toggleClass('ui-icon-triangle-1-s').toggleClass('ui-icon-triangle-1-e');" >
    <span class="ui-icon ui-icon-triangle-1-s"></span>
    <a href="#" tabindex="-1">Daily News</a>
</h3>
<div  class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active"  role="tabpanel">
<a onclick="welcome.edit_news( tinyMCE.get('edit_news_textarea').getContent() , {} );" ><button>Save news<div class="save_button in_button">&nbsp;</div></button></a>
<textarea class="mceeditor_500" id="edit_news_textarea" ><?php echo base64_decode($welcome_news['news']);?></textarea> </div>
<?php 
    } else {
        echo "";
    }
    if( $page->auth->inGroup( 'Admin' ) ){
?>
<div style=" width: 550px" class="ui-accordion ui-accordion-icons" >
<h3 class="ui-accordion-header  a ui-helper-reset ui-state-default ui-state-active ui-corner-top single_accordion" 
    onclick="
        $(this).siblings('.ui-accordion-content').toggle();
        $(this).toggleClass('ui-state-active');
        $(this).children('span').toggleClass('ui-icon-triangle-1-s').toggleClass('ui-icon-triangle-1-e');" >
    <span class="ui-icon ui-icon-triangle-1-s"></span>
    <a href="#" tabindex="-1">Email Filters & Automation</a>
</h3>
<div  class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active"  role="tabpanel">


<div class="admin_option_body option_body">
    <?php 
    
    echo $emaildash->manage_filters('1');
    //Tryed to automate this by puting it in globals but that didnt work 
    $run_on_start[] = "$('#manage_mailbox1').accordion();";
    $run_on_start[] = "$('.accordion').accordion();";
    ?>
</div>
</div></div>
<div style=" width: 550px" class="ui-accordion ui-accordion-icons" >
<h3 class="ui-accordion-header  a ui-helper-reset ui-state-default ui-state-active ui-corner-top single_accordion" 
    onclick="
        $(this).siblings('.ui-accordion-content').toggle();
        $(this).toggleClass('ui-state-active');
        $(this).children('span').toggleClass('ui-icon-triangle-1-s').toggleClass('ui-icon-triangle-1-e');" >
    <span class="ui-icon ui-icon-triangle-1-s"></span>
    <a href="#" tabindex="-1">Domain Integration</a>
</h3>
    <div  class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active"  role="tabpanel">
        <table>
        	<?php 
                echo '<tr><td><a title="example: @eapi.com" >Account Suffix</a></td><td>' . $page->manage_global_setting('auth_ad_account_suffix') . '</td></tr>';
                echo '<tr><td><a title="example: DC=eapi,DC=com" >Base DN</a></td><td>' . $page->manage_global_setting('auth_ad_base_dn') . '</td></tr>';
                echo '<tr><td><a title="examples 10.0.0.1,10.0.0.2 or 10.0.0.1 Seperate by , if there are muliple" >Domain Controller(s)</a></td><td>' . $page->manage_global_setting('auth_ad_domain_controllers') . '</td></tr>';
                echo '<tr><td><a title="" >Domain Admin Username</a></td><td>' . $page->manage_global_setting('auth_ad_admin_username') . '</td></tr>';
                echo '<tr><td><a title="We store this in the database using AES Encryption">Domain Admin Password</a></td><td>' . $page->manage_global_setting('auth_ad_admin_password' , true) . '</td></tr>';
                ?>
        </table>
    </div></div>

<?php } else {
        echo "";
    }?>
<?php 
    if( $page->auth->inGroup( 'Admin' ) ){
        ?>
<div style=" width: 550px" class="ui-accordion ui-accordion-icons" >
<h3 class="ui-accordion-header  a ui-helper-reset ui-state-default ui-state-active ui-corner-top single_accordion" 
    onclick="
        $('#manage_users').toggle();
        $(this).toggleClass('ui-state-active');$(this).children('span').toggleClass('ui-icon-triangle-1-s').toggleClass('ui-icon-triangle-1-e')" >
    <span class="ui-icon ui-icon-triangle-1-s"></span>
    <a href="#" tabindex="-1">Manage Users</a>
</h3>
    <div id="manage_users" class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active"  role="tabpanel">


    <?php echo $user->ManageUsers(); ?>
    </div>
</div>
<?php 
    } else {
        echo "";
    } ?>
<?php 
    if( $page->auth->inGroup( 'Admin' ) ){
        ?>
<div style=" width: 550px" class="ui-accordion ui-accordion-icons" >
<h3 class="ui-accordion-header  a ui-helper-reset ui-state-default ui-state-active ui-corner-top single_accordion" 
    onclick="
        $('#manage_groups').toggle();
        $(this).toggleClass('ui-state-active');$(this).children('span').toggleClass('ui-icon-triangle-1-s').toggleClass('ui-icon-triangle-1-e')" >
    <span class="ui-icon ui-icon-triangle-1-s"></span>
    <a href="#" tabindex="-1">Manage Groups</a>
</h3>
    <div id="manage_groups" class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active"  role="tabpanel">


    <?php echo $user->ManageGroups(); ?>
    </div>
</div>
<?php 
    } else {
        echo "";
    } ?>

        </td>
        <td style="width: 50%;vertical-align: top;">
            <?php if( $page->auth->inGroup( 'csradmin' ) ){ ?>
            <br/>
           
        <div style=" width: 450px" class="ui-accordion ui-accordion-icons" >
<h3 class="ui-accordion-header  a ui-helper-reset ui-state-default ui-state-active ui-corner-top single_accordion" 
    onclick="
        $('#manage_flags').toggle();
        $(this).toggleClass('ui-state-active');
        $(this).children('span').toggleClass('ui-icon-triangle-1-s').toggleClass('ui-icon-triangle-1-e');" >
    <span class="ui-icon ui-icon-triangle-1-s"></span>
    <a href="#" tabindex="-1">Manage Flags</a>
</h3>
    <div id="manage_flags" class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active"  role="tabpanel">

       
        <a onclick="$('#add_flag_input').colorpicker('open');" ><button>add flag<div class="add_button in_button">&nbsp;</div></button></a>
        <input style="width: 0px;" id="add_flag_input" >
        <?php 
            $run_on_start[] = 'cp_addflag' . " = $('#add_flag_input').colorpicker({
showOn: '',
top:  $('#add_flag_input').position().top + 'px',
left: ( $('#add_flag_input').position().left + 30 ) + 'px',
layout: { map: [0,0,1,3] },
onClose: function(hex, rgba, inst){
   var value = hex.substr(1);
   flags.add_flag_color(  value , { onUpdate: function( response , root ){ " . $page->page_link('admin') ." } } );
},
parts: ['bar', 'map']
}); 
$('#add_flag_input').hide();";
        ?>
        <table>
<?php $flag_arr = $flags->get_all_flags(); 

foreach( $flag_arr as $flag){
    $run_on_start[] = 'cp_' . $flag['flag_type_id']. " = $('#colorpicker" . $flag['flag_type_id'] ."').colorpicker({
showOn: '',
top:  $('#colorpicker" . $flag['flag_type_id'] ."').position().top + 'px',
left: ( $('#colorpicker" . $flag['flag_type_id'] ."').position().left + 30 ) + 'px',
layout: { map: [0,0,1,3] },
onClose: function(hex, rgba, inst){
   var value = hex.substr(1);
   flags.edit_flag_color( '" .$flag['flag_type_id']. "' , value , {} );
    $('#color_button" . $flag['flag_type_id']. "').css('background' , 'url(image.flag.php?size=16&color=' + value + ')' );
},
parts: ['bar', 'map']
}); 
$('#colorpicker" . $flag['flag_type_id'] . "').hide();";
    echo '<tr><td><div><input id=colorpicker' . $flag['flag_type_id'] .'  onchange="change_button_image( $(this).val() , \'' . $flag['flag_type_id']. '\');" value=\'' . $flag['color']. '\'/>
        
        <a onclick="cp_' . $flag['flag_type_id']. '.colorpicker(\'open\');" ><div id=color_button' . $flag['flag_type_id']. ' class="in_button" style="background-size: 15px 15px;
    background-repeat:no-repeat;width: 15px; height: 15px; background: url(\'image.flag.php?size=16&color=' . $flag['color'] . '\')">&nbsp;</div></a></div></td><td><input value="' . $flag['description'] . '" onchange="flags.edit_flag_description( \'' . $flag['flag_type_id'] . '\' , $(this).val() , {} );" /></td><td style="padding-left: 20px;" ><a onclick="delete_flag( \'' . $flag['flag_type_id']. '\' )" ><button>delete flag<div class="trash_can_normal in_button" >&nbsp;</div></button></a></td></tr>';
}
?>
        </table>
    </div></div>
        <div style=" width: 450px" class="ui-accordion ui-accordion-icons" >
<h3 class="ui-accordion-header  a ui-helper-reset ui-state-default ui-state-active ui-corner-top single_accordion" 
    onclick="
        $(this).siblings('.ui-accordion-content').toggle();
        $(this).toggleClass('ui-state-active');
        $(this).children('span').toggleClass('ui-icon-triangle-1-s').toggleClass('ui-icon-triangle-1-e');" >
    <span class="ui-icon ui-icon-triangle-1-s"></span>
    <a href="#" tabindex="-1">Manage Case Types</a>
</h3>
<div  class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active"  role="tabpanel">
        
        <?php echo $casecreation->manage_case_types(); 
        
            } else {
        echo "";
    }
   
    ?>
</div></div>
                    <div style=" width: 450px" class="ui-accordion ui-accordion-icons" >
<h3 class="ui-accordion-header  a ui-helper-reset ui-state-default ui-state-active ui-corner-top single_accordion" 
    onclick="
        $(this).siblings('.ui-accordion-content').toggle();
        $(this).toggleClass('ui-state-active');
        $(this).children('span').toggleClass('ui-icon-triangle-1-s').toggleClass('ui-icon-triangle-1-e');" >
    <span class="ui-icon ui-icon-triangle-1-s"></span>
    <a href="#" tabindex="-1">Dates</a>
    
</h3>
                        <div  class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active"  role="tabpanel">
                            <a onclick="add_holiday_date();" ><button>Add Holiday Date<div class="in_button add_button"></div></button></a>
                            <table style="width: 100%">
                                <tr><td style="width: 200px">Work Saturday</td><td style="" > <?php echo $page->manage_global_setting('global_task_work_sat' , false , array('type' => 'checkbox')); ?></td></tr>
                                <tr><td style="width: 200px">Work Sunday</td><td style="text-align: left;"  > <?php echo $page->manage_global_setting('global_task_work_sun' , false , array('type' => 'checkbox')); ?></td></tr>
                                <?php 
                                $holidays = $global_task->get_holidays();
                                foreach( $holidays as $holiday){
                                    ?>
                                <tr><td style="width: 200px"><?php echo $holiday['date']; ?></td><td style="text-align: right;"  > <?php echo $holiday['name']; ?><a onclick="global_task.delete_holiday('<?php echo $holiday['date_id']; ?>' , {onUpdate: function(response,root){<?php echo $page->page_link('admin'); ?>;}});" ><button>Delete Holiday<div class="in_button trash_can_normal"></div></button></a> </td></tr>
                                <?php
                                }
                                
                                ?>
                            </table>    
                        </div>
                    </div>
            <div style=" width: 450px" class="ui-accordion ui-accordion-icons" >
<h3 class="ui-accordion-header  a ui-helper-reset ui-state-default ui-state-active ui-corner-top single_accordion" 
    onclick="
        $(this).siblings('.ui-accordion-content').toggle();
        $(this).toggleClass('ui-state-active');
        $(this).children('span').toggleClass('ui-icon-triangle-1-s').toggleClass('ui-icon-triangle-1-e');" >
    <span class="ui-icon ui-icon-triangle-1-s"></span>
    <a href="#" tabindex="-1">Misc Commands</a>
    
</h3>
                        <div  class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active"  role="tabpanel">
                            <a onclick="page.reset_all_page_tablecolumn({onUpdate: function(response,root){alert('Search Options Reset');}});" ><button>Reset Search Options<div class="in_button add_button"></div></button></a>
                               
                        </div>
                    </div>
        </td></tr>
    </table>
<script>
    function add_holiday_date(){  
         var rand_id = Math.floor( Math.random() * 11 );
         var div_id = 'holidaydate' + rand_id;
         var tmphtml = 'Date: <input id="add_holiday_ui_input" class="add_holiday_ui_input daterange" > <br/>Name: <input id="add_holiday_ui_input_name" >';
         $('body').append('<div id=' + div_id + ' >' + tmphtml + '</div>');
         
        $( '#' + div_id  ).dialog(
    { 
        open: function(event,ui){
            
            $('#add_holiday_ui_input').datepicker({ dateFormat: 'yy-mm-dd' });
        },
        close: function(event , ui){  
            $(this).remove();$(this).dialog('destroy'); 
        } , 
        resizable: false,
        height:140,
        modal: true,
        buttons: {
            'Add': function() {
                global_task.set_holiday( $('.add_holiday_ui_input').val() , $('#add_holiday_ui_input_name').val() , {} );
                $(this).remove().dialog( 'close' );
                <?php echo $page->page_link('admin'); ?>;
            },
            'Cancel': function() {

                $(this).remove();$( this ).dialog( 'close' );
            }
        }
     });}
    function delete_flag( flag_type_id ){
        var rand_id = Math.floor( Math.random() * 11 );
        $('body').append('<div id=delflag' + rand_id + ' ></div>');
        $('#delflag' + rand_id ).attr('title','Delete Flag');
   $( '#delflag' + rand_id  ).dialog(
    { 
        close: function(event , ui){  
            $(this).remove();$(this).dialog('destroy'); 
        } , 
        resizable: false,
        height:140,
        modal: true,
        buttons: {
            'Delete Flag': function() {
                flags.remove_flag_color( flag_type_id , 
                { 
                    onUpdate: function(response,root){
                             <?php echo $page->page_link('admin'); ?>
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



    
    function run_on_reload(){
        $('.accordion').accordion('destroy');
        $('#manage_mailbox1').accordion('destroy');
        run_on_start();
    }
    function run_on_start(){
        //$('#colorswitcher').themeswitcher();
        //$('.jpicker').jPicker({window:{position:{x:'0',y:'0'},expandable: false,liveUpdate: false}} , function(color , context ){ alert(color.val('hex') ); email_client.color = color; email_client.context = context; });
        <?php foreach( $run_on_start as $run ){
            echo $run ."\n";
        } ?>
            try{
                tinyMCE.remove_all();
            } catch( err ) {

            }
            email_client.remove_all_tinymce();
            tinyMCE.init({
                mode : 'specific_textareas',
                editor_selector : 'mceeditor_500',
                theme : 'advanced' ,
                width: '500px',
                theme_advanced_buttons1 : 'bold,italic,underline,justifyleft,justifycenter,justifyright,justifyfull,undo,redo,link,unlink,forecolor',
                theme_advanced_buttons2 : '',
                theme_advanced_buttons3 : ''
            });
    }
    </script>
    



  
  <script>
  $(document).ready(function(){
    $('#switcher').themeswitcher();
  });
  </script>
<div id="switcher"></div>



