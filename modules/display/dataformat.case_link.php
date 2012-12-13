<?php 
$case_id = $original;
if( $format_options['return_csv'] != true && $format_options['return_csv'] != 1 ){
ob_start();
?>
<a onclick="casecreation.right_bottom_by_case( '<?php echo $case_id; ?>',
            {
                preloader:'prl',
                onUpdate: function(response,root){ 
                    $('#right_bottom_panel').html(response);
                }
             });" ><?php echo $case_id; ?></a>
<?php
$clean = ob_get_contents();
ob_end_clean();
} else {
    $clean = $case_id;
}

?>
