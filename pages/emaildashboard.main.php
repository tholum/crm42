<?php
//ini_set('display_errors' , 1 );
require_once('app_code/global.config.php');
require_once('class/config.inc.php');
require_once('class/class.email_client.php');
require_once('class/class.flags.php');
require_once('class/class.GlobalTask.php');
require_once('class/class.smtp.php');
require_once('class/class.display.php');
require_once('class/class.casecreation.php');
$emaildash = new email_client();
$user_id = $_SESSION["user_id"];
$page = new basic_page;
$flyout_over_email = $page->get_setting($user_id, "flyout_over_email");
$defaults = array("archive"=>"0");//Change Back to 1
$defaults['pagation'] = true;
$defaults['page'] = '1';
$defaults['limit'] = 15;
if( $flyout_over_email === false ){
            $flyout_over_email = "true";
}

            $bulk_flags = $emaildash->custom_bulk_flags_button();
            $bulk_owner = $emaildash->custom_bulk_owner_button();

?>

<div id="emaildashboard_full_screen">
	   <?php echo $emaildash->searchMail(); ?>
		
	<div id="form_main">
	 
	 <div id="emaildashboard_mail_info">
             <table class="email_structure"><tr><td class="email_list"> 
                         <div id="bulk_actions" class="email_bull_actions main_color" >
                             <div class="bulk_actions" style="display: inline-block" ><span class="bulk_actions_label">Bulk Actions:</span>
                                 <?php echo $bulk_flags . ' &nbsp; ' . $bulk_owner; ?> 
<!--                                 <a onclick="emaildash.apply_bulk_actions( email_client.bulk_emails , 'active', '1' , { target: 'show_info' } );" >
                                     <button>Archive<div class="trash_can_normal in_button"/>&nbsp;</div></button></a>-->
                <a onclick="
                    //alert();
                    if( $('.archive_button_text').html() == 'un-archive' ){ slimcrm.tmp_set_archive = 0;} else { slimcrm.tmp_set_archive = 1; }
                    emaildash.apply_bulk_actions( email_client.bulk_emails , 'active', slimcrm.tmp_set_archive , a_default_search ,  { target: 'show_info' } );
                   " ><button><span class="archive_button_text" >Archive</span><div class="trash_can_normal in_button"/>&nbsp;</div></button></a> 
                            <div style="display: inline-block;position: relative;top: 4px;" >
                                 <ul id="icons" class="ui-widget ui-helper-clearfix" style="height: 16px !important;" >
                                     <li class="ui-state-default ui-corner-all" style="height: 16px !important;display: inline-block;" >
                                         <span class="ui-icon ui-icon-circle-arrow-w" style="display: inline-block;" onclick="if( parseInt(a_default_search.page ) > 1 ){ a_default_search.page = parseInt(a_default_search.page) - 1;refresh_search();}" ></span>
                                         <span style="display: inline-block; font-size: 11px;position: relative; bottom: 4px;" class="email_pagation" >error</span>
                                         <span class="ui-icon ui-icon-circle-arrow-e" style="height: 16px !important;display: inline-block;" onclick="if(parseInt(a_default_search.page) < parseInt(a_default_search.page_count) ){ a_default_search.page =  parseInt(a_default_search.page) + 1;refresh_search();}" ></span>
                                     </li>
                                     
                                 </ul></div></div>
                         </div>
	   <div id="show_info" class="email_display_list">
		  <?php echo $emaildash->display_email_by_module('' , '' , $defaults ); ?>
	   </div>
                     </td><td class="email_content_list">
	   <div id="email_content" class="email_content" >
		  <?php 
                  if(array_key_exists( "mid", $vars)){  
                    echo $emaildash->display_mail_content($vars["mid"] , "emaildashboard" , "LEFT PANEL" ); 
                  }
                  ?>
	   </div>
                     </td>
                     <td class="hide_flags_<?php echo $flyout_over_email;?>"></td>
                 </tr>
         </table>
	   <div style="clear:both;"></div>
	 </div>
	</div>
</div>
<script>
    function email_set_defaults(){
<?php 
foreach($defaults as $n => $v){
    echo "a_default_search.$n = '$v';\n";
}
?>
}
</script>