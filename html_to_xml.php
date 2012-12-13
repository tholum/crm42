<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
ini_set("display_errors" , 1);
require_once 'class/class.yui.php';
$yui = new phpyui("/yui2");
echo $yui->string_to_xml('<div id="About" class="Rec"><div id="HANDELNAME" class="Handle">TITLE</div><div class="Info">INNERHTML</div></div>');
?>
<div style="background:#2647a0 url(images/bluebar2.png) repeat-x;border:solid #a3a3a3;border-width:0 1px;color:#000;position:relative;text-decoration:none;height: 80px;width: 100px;">&nbsp;</div>