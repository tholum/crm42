<?php
//ini_set( "display_errors" , 1 );
$module = $_REQUEST["module"];
$module = ereg_replace("[^A-Za-z0-9]", "", $module );
include 'modules/print.' . $module . '.php';

?>
<script>
window.print()
</script>