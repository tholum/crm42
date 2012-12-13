<?php
if( $format_options['return_csv'] != true && $format_options['return_csv'] != 1 && $original != 0 ){
    $clean="<a  href='#' onclick=\"" . $this->page_link("order_search" , array('order_id' => $original ) ) . "\">$original</a>";
} else {
    $clean=preg_replace("/[^a-zA-Z0-9:\s]/", '', $original );
}
?>
