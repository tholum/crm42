<?php

$arr = $this->db->fetch_assoc($this->db->query("SELECT subject FROM eml_message WHERE mid = '$module_id'"));
$string = $arr["subject"];
if( $format_options['return_csv'] != true && $format_options['return_csv'] != 1 ){
$string = (strlen($string) > 13) ? substr($string,0,10).'...' : $string;

$display_name = "<a href='#' onclick=\"$('.emaildashboard_compose_emaildashboard').show();
emaildash.display_mail_content(
    '$module_id',
    'flyout',
    'leftpanel',
    {
        preloader:'prl',
        onUpdate: function(response,root){
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
);\" >Email: $string</a>"; 
} else {
$display_name = preg_replace("/[^a-zA-Z0-9:\s]/", '',  $string );
}
?>
