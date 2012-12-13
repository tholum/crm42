<?php
ob_start();
require_once('app_code/global.config.php');

require_once('class/config.inc.php');
//echo __LINE__ . "\n";
require_once('class/class.email_client.php');
//echo __LINE__ . "\n";
require_once('class/class.flags.php');
//echo __LINE__ . "\n";
require_once('class/class.GlobalTask.php');

//echo __LINE__ . "\n";
require_once('class/class.smtp.php');
//echo __LINE__ . "\n";
require_once('class/class.display.php');
//echo __LINE__ . "\n";
require_once('class/class.casecreation.php');
//echo __LINE__ . "\n";
require_once('class/class.dynamicpage.php');
//echo __LINE__ . "\n";
require_once('class/class.FctSearchScreen.php');

//echo __LINE__ . "\n";
require_once('class/class.eapi_order.php');
require_once('class/class.eapi_account.php');
require_once('class/class.cases.php');
require_once('class/class.note.php');
require_once('class/class.knowledgebase.php');
ob_end_clean();
    require_once('class/class.eapi_account.php');
    /*
    $eapi_api = new eapi_api;
    $account = $eapi_api->full_account_info('41190');
    $json = json_decode( $account);
    */
    $eapi_account = new eapi_account();
    $cases = new cases;
    $note = new Note;
    $emaildash = new email_client;
    $global_task = new GlobalTask;
    ?>
<script>
    var textarea = '';
var ac_dash = new Object;

function open_account( item ){
    ac_dash.run_all = [];
    ac_dash.run_on_load = function(){
        for( run in ac_dash.run_all ){
            eval(ac_dash.run_all[run]);
        }
    }
    $('.account_loader').html('<center><img src="images/loader.gif"/></center>');
   eapi_account.display_account_info( item.Id , '75' , {target: 'account_target' , 
       onUpdate: function( root , response ){ 
           $('#account_orders').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,1]] } ); 
       }  });
   cases.case_by_module( 'eapi_ACCOUNT' , item.Id , { limit: '30'} , { target: 'account_case_target' , onUpdate: function( root , response ){ $('#case_table').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,1]] } ); } } );
   note.display_note_by_module( 'eapi_ACCOUNT' , item.Id , page_object.user_id , { target: 'account_create_note_target' } );
   emaildash.display_email_list_by_module( '' , '' , { email_address : item.Email } ,  { target: 'account_email_target' } );
//   eapi_account.display_contact_info( item.Id ,{target: 'account_contact_info_target'});
}
<?php 
//$vars['account_id'] = '59891';
//$vars['account_id'] = 'Woodys';
?>
    function open_first_case(){
        <?php
    
if( array_key_exists( "account_id", $vars) == true ){
   $a_js = $eapi_account->search_account( $vars['account_id'] );
   $account_arr = json_decode( $a_js);
   $ac = false;
   foreach( $account_arr->SearchResult as $result ){
       if( $result->Id == $vars['account_id'] ){
           $ac = $result;
       }
   }
       if( $ac !== false ){
        ?>
           var item = new Object();
           item.Id = '<?php echo $ac->Id; ?>';
           item.Email = '<?php echo $ac->Email; ?>';
           item.Studio = '<?php echo $ac->Studio; ?>';
           slimcrm.last_account = item;
           open_account( item );
       <?
       
       }
    } else {
        ?>
        if( typeof(slimcrm.last_account) != 'undefined'){
//            alert(typeof(slimcrm.last_account));
            open_account(slimcrm.last_account);
        }

        <?php
    }


?>
        }
</script>
<div class="account_search_header search_header">
    <?php// var_dump($account_arr);var_dump($a_js);var_dump( $vars); ?>
    <div class="account_search_title search_title" >Search For Account</div>
    <div class="account_search_searchbox_container searchbox_container" ><input class="account_autocomplete search_searchbox" <?php 
if(array_key_exists( "account_name", $vars)){
  echo 'value="' . $vars["account_name"] . '" ';  
}
?> id="account_name" /></div>
</div>

<div class="search_results">
<!--    <div style="display:none">
    <div id="account_contact_info_target" class="account_loader" >&nbsp;<?php 
//if(array_key_exists( "account_id", $vars) && 1 == 2){
//    echo $eapi_account->display_contact_info($vars['account_id'] );
//}
?></div>
    </div>-->
<div id="account_target" class="account_loader" >&nbsp;<?php 
//if(array_key_exists( "account_id", $vars)&& 1 == 2){
//    echo $eapi_account->display_account_info( $vars['account_id'] , '75' );
//}
?></div>
<div id="account_case_target" class="account_loader account_padding" >&nbsp;<?php 
//if(array_key_exists( "account_id", $vars)){
//    echo $cases->case_by_module( 'eapi_ACCOUNT' , $vars['account_id'] , array( 'limit' => '10' ) );
//}
?></div>
<div id="account_create_note_target" class="account_loader account_padding" >&nbsp;<?php 
if(array_key_exists( "account_id", $vars) && 1 == 2){
    echo $note->display_note_by_module( 'eapi_ACCOUNT' , $vars['account_id'] , $_SESSION['user_id'] );
}
?></div>
<button id="do_nothing" style="display: none"></button>
</div>
<div class="search_sidepanel" >
<div id="account_email_target" class="account_loader" >&nbsp;<?php 
//if(array_key_exists( "email_address", $vars)){
//    echo $emaildash->display_email_by_module( '' , '' , array( 'email_address' => $vars['email_address'] ) ) ;
//}
?></div>

</div>
