function( root , response ){
run_on_email_load();
run_on_start();
try{
    tinyMCE.remove_all( );
} catch( err ) {

}
tinyMCE.init({
            mode : 'specific_textareas',
            editor_selector : 'mceeditor_500',
            theme : 'advanced' ,
            width: '500px',
            theme_advanced_buttons1 : 'bold,italic,underline,justifyleft,justifycenter,justifyright,justifyfull,undo,redo,link,unlink,forecolor',
            theme_advanced_buttons2 : '',
            theme_advanced_buttons3 : ''
});

        $('.email_display_list , .email_content').css('height' , $(window).height() - ( $('.search_header').height() + $('#main_navigation').height() + 35 ) );
email_set_defaults();

}