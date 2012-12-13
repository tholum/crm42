<?php 
$rand = rand(0 , 99999999999999999999 );
$rand=  md5($rand . serialize($pr) . $id );
echo 'Apply Flag <img onclick="flags.display_flags_radio( { onclick: \'emaildash.update_filter_action( #singlequote#' . $eml_filter_id . '#singlequote# , #singlequote#vars:flag_type_id:info#singlequote# , #singlequote##flag_type_id##singlequote# , #singlequote#' . base64_encode(serialize($pr)).'#singlequote# , { target: #singlequote#dialog_' . $filter['mailbox_id'] . '#singlequote# } );\'} , { target : \'process_edit_flags_' . $rand . '\'});" style="width: 16px; height:16px;" src="image.flag.php?color=' . $this->flags->get_flag_color( $pr['vars']['flag_type_id']['info'] ) . 
        '&size=16" ><div id="process_edit_flags_' . $rand . '" >&nbsp;</div>';
?>
