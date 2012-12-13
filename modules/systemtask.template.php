<?php
$selected_option = $args[0];
$chart_assign_id = $args[1];
$module_name = $args[2];
$module_id = $args[3];
$return = array(); // The only way to retern varables
$return["stop"] = ""; // SET to anything to not allow the flowchart task to complete, USE SPARINGLY
$return["javascript"] = "";// SET to anything to exictue code on submition, for example 'alert('Hello");'
$return["html"] = "";
?>