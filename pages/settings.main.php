<?php
//ini_set('display_errors' , 1);
ob_start();
require_once('app_code/global.config.php');
require_once('class/config.inc.php');
require_once('class/class.email_client.php');
require_once('class/class.flags.php');
require_once('class/class.GlobalTask.php');
require_once('class/class.smtp.php');
require_once('class/class.display.php');
require_once('class/class.casecreation.php');
require_once('class/class.knowledgebase.php');
$emaildash = new email_client();

ob_end_clean();
$page = new basic_page();
$kb = new knowledge_base($page);
$user_id = $_SESSION['user_id'];
$flyout_over_email = $page->get_setting($user_id, 'flyout_over_email');
$phone_main_incomming = $page->get_setting($user_id, 'phone_main_incomming');
$phone_extention = $page->get_setting($user_id , 'phone_extention');
?>
<script>
function run_on_reload(){
   $('.accordion').accordion('destroy');
   run_on_start();
}
function run_on_start(){
    try{
        tinyMCE.remove_all();
    } catch( err ) {

    }
    $('.accordion').accordion();
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
<h2 style="padding: 10px;margin: 10px;">Settings</h2>


<table style="width: 80%;padding-left: 10%;padding-right: 10%;" ><tr><td style="width: 50%;vertical-align: top;">
            <div style=" width: 575px" class="ui-accordion ui-accordion-icons" >
<h3 class="ui-accordion-header  a ui-helper-reset ui-state-default ui-state-active ui-corner-top" 
    onclick="
        $(this).siblings('.ui-accordion-content').toggle();
        $(this).toggleClass('ui-state-active');
        $(this).children('span').toggleClass('ui-icon-triangle-1-s').toggleClass('ui-icon-triangle-1-e');" >
    <span class="ui-icon ui-icon-triangle-1-s"></span>
    <a href="#" tabindex="-1">Manage Settings</a>
</h3>
<div  class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active"  role="tabpanel">
            <table style="padding: 10px;margin: 10px;">
                <tr><td style="padding: 10px;margin: 10px;" >Allow Flyouts to cover Email</td><td style="padding: 10px;margin: 10px;" ><input <?php if( $flyout_over_email== "true"){ echo "CHECKED";} ?> type="checkbox" onchange="if($(this).attr('checked') == 'checked' ){ page.set_setting( page_object.user_id , 'flyout_over_email' , 'true' , {}); } else { page.set_setting( page_object.user_id , 'flyout_over_email' , 'false' , {} );}"  /></td><td colspan="3"></td></tr>
                <tr><td style="padding: 10px;margin: 10px;" >Phone Alert Main Line</td><td style="padding: 10px;margin: 10px;" ><input <?php if( $phone_main_incomming== "true"){ echo "CHECKED";} ?> type="checkbox" onchange="if($(this).attr('checked') == 'checked' ){ page.set_setting( page_object.user_id , 'phone_main_incomming' , 'true' , {}); } else { page.set_setting( page_object.user_id , 'phone_main_incomming' , 'false' , {} );}"  /></td><td colspan="3"></td></tr>
                <tr><td style="padding: 10px;margin: 10px;" >Phone Ext</td><td style="padding: 10px;margin: 10px;" ><input value="<?php echo $phone_extention;?>" onkeyup="page.set_setting( page_object.user_id , 'phone_extention' , $(this).val() , {});"  /></td><td colspan="3"></td></tr>
                </table>
            </div></div>
            <div style=" width: 575px" class="ui-accordion ui-accordion-icons" >
<h3 class="ui-accordion-header  a ui-helper-reset ui-state-default ui-state-active ui-corner-top" 
    onclick="
        $(this).siblings('.ui-accordion-content').toggle();
        $(this).toggleClass('ui-state-active');
        $(this).children('span').toggleClass('ui-icon-triangle-1-s').toggleClass('ui-icon-triangle-1-e');" >
    <span class="ui-icon ui-icon-triangle-1-s"></span>
    <a href="#" tabindex="-1">Manage Signitures</a>
</h3>
<div  class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active"  role="tabpanel">
            <table>
                <tr><td>Personal Signature:</td><td colspan="3" ></td><td><a onclick="emaildash.save_signature_by_module('TBL_USER' , '<?php echo $_SESSION['user_id']; ?>', tinyMCE.get('personal_signature').getContent() , {} );" ><button>save signature<div class="save_button in_button">&nbsp;</div></button></a></td></tr>
                <tr style="margin-top: 3px;"><td colspan="5"><textarea class="mceeditor_500" id="personal_signature"><?php echo $emaildash->get_signature_by_module('TBL_USER', $_SESSION['user_id']); ?></textarea></td></tr>
            
                    <?php 
if( $page->auth->inGroup( 'csradmin' ) ){
    ?>
            
 <tr><td>Global Signature:</td><td colspan="3" ></td><td><a onclick="emaildash.save_signature_by_module('MAILBOX' , '1', tinyMCE.get('global_signature').getContent() , {} );" ><button>save signature<div class="save_button in_button">&nbsp;</div></button></a></td></tr>
                <tr style="margin-top: 3px;"><td colspan="5"><textarea class="mceeditor_500" id="global_signature"><?php echo $emaildash->get_signature_by_module('MAILBOX', '1'); ?></textarea></td></tr>               
                <?php
}
?>
                
                </table>
            </div></div>
<table>
                <tr><td><h2>Manage Knowledgebase</h2></td><td colspan="4"></td></tr>
                <tr><td colspan="5"><?php echo $kb->manage_knowledgebase(); ?></td></tr>
            </table>        
            
        </td><td style="width: 50%;vertical-align: top;">
<table style="padding: 10px;margin: 10px;">
    <?php 
        $flyout_over_email = $page->get_setting($user_id, "flyout_over_email");
        //sets the default if not currently set
        if( $flyout_over_email === false ){
            $flyout_over_email = "true";
        }
    ?>
    
    <tr><td>            <div style=" width: 450px" class="ui-accordion ui-accordion-icons" >
<h3 class="ui-accordion-header  a ui-helper-reset ui-state-default ui-state-active ui-corner-top single_accordion" 
    onclick="
        $(this).siblings('.ui-accordion-content').toggle();
        $(this).toggleClass('ui-state-active');
        $(this).children('span').toggleClass('ui-icon-triangle-1-s').toggleClass('ui-icon-triangle-1-e');" >
    <span class="ui-icon ui-icon-triangle-1-s"></span>
    <a href="#" tabindex="-1">Misc Commands</a>
    
</h3>
                        <div  class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active"  role="tabpanel">
                            <a onclick="page.reset_my_page_tablecolumn({onUpdate: function(response,root){alert('Search Options Reset');}});" ><button>Reset My Search Options<div class="in_button add_button"></div></button></a>
                               
                        </div>
                    </div></td></tr>
    <tr><td><h2>Personal Templates</h2></td><td colspan="4"></td></tr>
    <tr><td colspan="5" ><?php echo $emaildash->manage_templates('TBL_USER', $_SESSION['user_id']);  ?></td></td>
        <?php
        if( $page->auth->isAdmin() == true ){
            $result = $page->db->query('SELECT * FROM tbl_usergroup');
            while( $row=$page->db->fetch_assoc($result)){ ?>
                <tr><td><h2>Group <?php echo $row["group_name"]; ?> Templates</h2></td><td colspan="4"></td></tr>
    <tr><td colspan="5" ><?php echo $emaildash->manage_templates('TBL_USERGROUP', $row['group_id']);  ?></td></td>
        
        
            <?php }
        }
        ?>
</table>
        </td></tr></table>
