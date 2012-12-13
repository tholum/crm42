var email_client = new Object();

email_client.active_emails = Object();
email_client.timeout = 1000;
email_client.deactivate = function( key , object ){
    var old_mid = $(object).children('.checkbox_td').children('.mid').html();
    emaildash.remove_active_email( old_mid , page_object.user_id , {} );
}

email_client.bulk_emails = [];

email_client.clean_bulk_emails = function(){
   $('.email_item_checkbox').attr('checked' , false);
   delete email_client.bulk_emails;
   email_client.bulk_emails = []; 
}
email_client.message_checkbox = function( cb , mid ){
    
    if($(cb).ctl_checked()==true){ 
        email_client.bulk_emails[mid] = mid;} 
    else {delete email_client.bulk_emails[mid];}
}

email_client.setup_panel = function(){
    $('.email_autocomplete').autocomplete(
                { 
                    source: 'email_lookup.php', 
                    select: function(event, ui){
                        $(this).val(ui.item.email);
                    }
                });
   
}

email_client.compose_email = function( module_name , module_id ){
    
    if( module_name != '' && module_id != '' ){
        var options = {'module_name': module_name , 'module_id': module_id};
        
    } else {
        var options = {};
    }
    casecreation.display_mail_content( page_object.user_id ,'flyout', 'COMPOSE',options,
        {preloader:'prl', 
            onUpdate: email_client.display_compose
        }
    );
    
}

email_client.toggle_comment = function(){
    $(this).toggleClass('comment_upper_line_active');
    $(this).next('.comment_text').toggle();
}

email_client.message_click = function( object , options ){
    $('.emaildashboard_compose').hide();
     $('.email_item_active').each(email_client.deactivate);
     
     $('.email_item').removeClass('email_item_active');
      emaildash.set_active_email( options.mid , page_object.user_id , {} );
     $(object).parent().parent().addClass('email_item_active');
     
    emaildash.updateUserInfo(
    options.mid,
    'read',
    '1',
    {preloader:'prl',
    onUpdate: function(response,root){
        emaildash.displayStatusImage(
        options.mid,
        options.group_id,
        {preloader:'prl',
        onUpdate: function(response,root){
            document.getElementById('message_status_' + options.mid ).innerHTML=response;
            emaildash.display_mail_content(options.mid,'emaildashboard' , 'flyout' , {onUpdate: function(responce,root){$('#compose_mail_div_flyout').html(responce);}} );   
            emaildash.display_mail_content(options.mid,
            'emaildashboard',
            'LEFT PANEL',
            {preloader:'prl',
            onUpdate: function(response,root){
                document.getElementById('email_content').innerHTML=response;
                fileAttachment();
                tinyMCE.init({
                mode : 'specific_textareas',
                editor_selector : 'mceeditor_500',
                theme : 'advanced' ,
                width: '500px',
                theme_advanced_buttons1 : 'bold,italic,underline,justifyleft,justifycenter,justifyright,justifyfull,undo,redo,link,unlink,forecolor',
                theme_advanced_buttons2 : '',
                theme_advanced_buttons3 : ''
                });
                $('.comment_upper_line').click(email_client.toggle_comment);
            }});
        }
        });
        casecreation.right_bottom_panel(options.mid,
            {preloader:'prl',
            onUpdate: function(response,root){$('#right_bottom_panel').html(response);}});
        }
    }
);
    
}

email_client.create_case = function( mid ){
        casecreation.create_case( 'EMAIL' , mid ,page_object.user_id ,{preloader:'prl', target:'right_bottom_panel', onUpdate: function(response,root){slimcrm.flash_sidepanel();}});
}

email_client.check_server = function( object ){
    $.each( email_client.active_emails , function( i ){
        email_client.active_emails[i] = "inactive";
    });
    $(object).each( function(i , val ){
        email_client.active_emails[val.mid] = "active";
        if($('.email_item_mid_' + val.mid ).hasClass('email_item_user') == false &&$('.email_item_mid_' + val.mid ).hasClass('email_item_active') == false ){
            $('.email_item_mid_' + val.mid ).addClass('email_item_user');
        }
    });
    $.each( email_client.active_emails , function( i ){
        if( email_client.active_emails[i] == "inactive" ){
            email_client.active_emails[i] = '';
            $('.email_item_mid_' + i ).removeClass('email_item_user');
        }
    });
    
    var current_time = new Date().getTime();
    var diff_time = current_time - email_client.last_sent;
    var tick_overage = email_client.timeout - diff_time;
    email_client.tick_overage = tick_overage;
    if( tick_overage < ( email_client.timeout / 2 ) &&  email_client.timeout < 10000 ){
        email_client.up_tick_timeout();
    }
    if( tick_overage > ( email_client.timeout ) &&  email_client.timeout > 500 ){
        

        email_client.down_tick_timeout();
    }
    
    email_client.currently_running == false;
}
email_client.check_server_tick = function(){
    if( email_client.currently_running == false ){
        email_client.currently_running == true;
        email_client.last_sent = new Date().getTime(); 
        $.ajax(
            {
                type: "GET", 
                url: 'ajax.php?type=active_email&format=json&user_id=' + page_object.user_id  , 
                dataType: "json" ,
                timeout: email_client.timeout - 100,
                success: email_client.check_server,
                error: function(){email_client.currently_running == false;if( email_client.timeout < 10000 ){email_client.up_tick_timeout();}}

            });
    }
}
email_client.up_tick_timeout = function( timeout_time ){
    if(!timeout_time){
        timeout_time = 250;
    }
    clearInterval(email_client.tick);
    email_client.timeout = email_client.timeout + timeout_time;
  email_client.tick = setInterval ( 'email_client.check_server_tick()', email_client.timeout );
}
email_client.down_tick_timeout = function(timeout_time){
    if(!timeout_time){
        timeout_time = 250;
    }
    clearInterval(email_client.tick);
    email_client.timeout = email_client.timeout - timeout_time;
  email_client.tick = setInterval ( 'email_client.check_server_tick()', email_client.timeout );
}
email_client.start_tick = function(){
  email_client.tick = setInterval ( 'email_client.check_server_tick()', email_client.timeout );
}
email_client.currently_running = false;

email_client.link_to_case = function( module_name , module_id ){
    $('#content_area').append('<div id="link_to_case_'+ module_name + module_id + '" class=dialog ><div>');
    emaildash.display_link_to_case( module_name , module_id , {
        onUpdate: function( responce , root ){
            $( '#link_to_case_' + module_name + module_id ).html( responce );
            $( '#link_to_case_' + module_name + module_id ).dialog(
                { 
                    close: function(event , ui){ 
                        $(this).dialog('destroy'); 
                    } , 
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'link to case': function() {
                            emaildash.link_module_to_case( module_name , module_id , $('#link_case_input_' + module_name + module_id ).val() , {} );
                             $(this).remove();$( this ).dialog( 'destroy' );
                        },
                        Cancel: function() {$(this).remove();$( this ).dialog( 'destroy' );}}

                }
            ); 
                link_cases_onload();
        }
        
    } );
    
}
email_client.check_email_flyout = function(){
    return $('.emaildashboard_compose').is(':visible');
}
email_client.display_email = function(response,root,overide,from_compose){
      display = true;
      if(email_client.check_email_flyout() == true && overide == undefined ){
          display=false;
          if(from_compose==undefined){
            slimcrm.confirm({yes: function(){email_client.display_email(response, root, 'yes');} , question: 'Email Currently being composed,<br/>Would you like to delete it' });
          } else {
             slimcrm.confirm({yes: function(){email_client.display_compose(response, root, 'yes');} , question: 'Email Currently being composed,<br/>Would you like to delete it' }); 
          }
    }
      if(display==true){
        $('.left_panel_flyout').show();
        document.getElementById('content_left_panel').innerHTML=response;
                fileAttachment();
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
}
email_client.display_compose = function(response,root,overide){
    email_client.display_email(response,root,overide,true);
    slimcrm.setup_tinyMCE();
    email_client.setup_panel();
   
}

email_client.open_flyout =  function( mid ){
    $('.emaildashboard_compose_emaildashboard').show();
emaildash.display_mail_content(
	mid,
	'flyout',
	'leftpanel',
	{
		preloader:'prl',
		onUpdate: email_client.display_compose
    
	}

);
}