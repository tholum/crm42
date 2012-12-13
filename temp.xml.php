<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//ini_set("display_errors", 1 );
header ("content-type: text/xml");
$user_id = $_GET["user_id"];
echo '<?xml version="1.0"?>';
require_once 'class/global.config.php';
require_once 'class/database.inc.php';
require_once 'class/config.inc.php';
require_once 'class/class.yui.php';
require_once 'class/class.asterisk.php';
$yui = new phpyui("/yui2");
if(PHONE_SYSTEM == "asterisk"){
    $phone = new Asterisk;

$calls = $phone->get_current_calls( $user_id );
$printr = print_r( $calls , true );
/* cid = number
 * name = full name
 * contact_id = cid
 * type = Type
 *
 */

$printr = "<textarea style='width: 300px;height: 300px;'> $printr </textarea>";
$printr = $yui->string_to_xml($printr);

} else {
    $calls = array();
}

$tmparr = array();
function display_number( $t ){
    return "(" . $t[0] . $t[1] . $t[2] . ") " . $t[3] . $t[4] . $t[5] . "-" . $t[6] . $t[7] . $t[8] . $t[9];
}

function create_button( $id , $text , $class , $onclick ){
    $return = "<div id='$id' style='border: 2;background: url(images/$class.png);background-position:right top;height: 28px; align: center; width: 70px;float: left;' onMouseOver='document.getElementById(\"$id\").style.background=\"url(images/$class-mo.png)\"' onMouseOut='document.getElementById(\"$id\").style.background=\"url(images/$class.png)\"' onMouseOver='document.getElementById(\"$id\").style.background=\"url(images/$class-mo.png)\"'  onMouseup='document.getElementById(\"$id\").style.background=\"url(images/$class.png)\"' onClick='document.getElementById(\"$id\").style.background=\"url(images/$class-press.png)\";$onclick'><a style='font-weight: bold;padding: 10px;position: relative; top: 5px;color: white;' >$text</a></div>";
    return $return;

}
$array = array();
foreach( $calls as $call){
    if(key_exists("contact_id", $call)){
        $rand = rand(0 , 10000000);
        $project_id = $call["cid"] . "project$rand";
        $project_click = "alert(\"Project Button Pressed $project_id\");";
        $task_id = $call["cid"] . "task$rand";
        $task_click = "add_newtab( \"contact_profile.php?contact_id=" . $call["contact_id"] . "&open_task=yes\" , \"" . $call["name"] ."\" )";
        $contact_id = $call["cid"] . "contact$rand";
        $contact_click = "add_newtab( \"contact_profile.php?contact_id=" . $call["contact_id"] . "\" , \"" . $call["name"] ."\" )";
        $html = "
            <div>
                <div style='width:250px;align: center;text-align: center;'>
                    <span style='font-size: 24px;font-weight: bold;width: 100%;text-align: center;' >" . $call["name"] ."</span>
                    <div>
                       <span style='font-size: 12px;font-weight: bold;'>" . display_number( $call["cid"] ) . "</span>
                   </div>
                </div>
            <span><div style='width: 10px;float: left;'></div><div id='$project_id'
             style='margin-left: 1px;margin-right: 1px;margin-bottom: 5px;float: left;border: 2;background: url(images/bluebar3.png);background-position:right top;height: 28px; align: center; width: 70px;'
             onMouseOver='document.getElementById(\"$project_id\").style.background=\"url(images/bluebar3-mo.png)\"'
             onMouseOut='document.getElementById(\"$project_id\").style.background=\"url(images/bluebar3.png)\"'
             onMouseup='document.getElementById(\"$project_id\").style.background=\"url(images/bluebar3.png)\"'
             onClick='document.getElementById(\"$project_id\").style.background=\"url(images/bluebar3-press.png)\";$project_click'>
             <a style='font-weight: bold;padding: 10px;position: relative; top: 5px;color: white;'  >Project</a>
           </div></span><span style='margin: 10px;' >
            <div id='$task_id'
                style='margin-left: 1px;margin-right: 1px;float: left;border: 2;background: url(images/bluebar3.png);background-position:right top;height: 28px; align: center; width: 70px;'
                onMouseOver='document.getElementById(\"$task_id\").style.background=\"url(images/bluebar3-mo.png)\"'
                onMouseOut='document.getElementById(\"$task_id\").style.background=\"url(images/bluebar3.png)\"'
                onMouseup='document.getElementById(\"$task_id\").style.background=\"url(images/bluebar3.png)\"'
                onClick='document.getElementById(\"$task_id\").style.background=\"url(images/bluebar3-press.png)\";$task_click'>
             <a style='font-weight: bold;padding: 18px;position: relative; top: 5px;color: white;' >Task</a></div>
        </span><span>
                    <div id='$contact_id'
                style='margin-left: 1px;margin-right: 1px;float: left;border: 2;background: url(images/bluebar3.png);background-position:right top;height: 28px; align: center; width: 78px;'
                onMouseOver='document.getElementById(\"$contact_id\").style.background=\"url(images/bluebar3-mo.png)\"'
                onMouseOut='document.getElementById(\"$contact_id\").style.background=\"url(images/bluebar3.png)\"'
                onMouseup='document.getElementById(\"$contact_id\").style.background=\"url(images/bluebar3.png)\"'
                onClick='document.getElementById(\"$contact_id\").style.background=\"url(images/bluebar3-press.png)\";$contact_click'>
             <a style='font-weight: bold;position: relative; top: 5px;color: white;width: 100%; text-align: center;padding: 12px;' >Contact</a></div>
        </span></div>";
    } else {
        $rand = rand(0 , 10000000000000000000);
            $newnum_id = $rand . "NewContact";
            $newnum_click = "add_newtab( \"contact_addperson.php?phone=" . $call["cid"] . "\" , \"Add Person\" )";
                $html = "
            <div>
                <div style='width:250px;align: center;text-align: center;'>
                    <span style='font-size: 24px;font-weight: bold;width: 100%;text-align: center;' >" . display_number( $call["cid"] ) ."</span>

                </div>
            <span><div style='width: 10px;float: left;'></div><div id='$newnum_id'
             style='margin-left: 1px;margin-right: 1px;margin-bottom: 5px;float: left;border: 2;background: url(images/bluebar3.png);background-position:right top;height: 28px; align: center; width: 110px;'
             onMouseOver='document.getElementById(\"$newnum_id\").style.background=\"url(images/bluebar3-mo.png)\"'
             onMouseOut='document.getElementById(\"$newnum_id\").style.background=\"url(images/bluebar3.png)\"'
             onMouseup='document.getElementById(\"$newnum_id\").style.background=\"url(images/bluebar3.png)\"'
             onClick='document.getElementById(\"$newnum_id\").style.background=\"url(images/bluebar3-press.png)\";$newnum_click'>
             <a style='font-weight: bold;padding: 10px;position: relative; top: 5px;color: white;'  >Add Contact</a>
           </div></span></div>";
    }
    $array[] = array("call" => $yui->string_to_xml( $html ) );

}
//    array("call" => $yui->string_to_xml( $printr ) . "&lt;br/&gt;&lt;a href='http://www.google.com' &gt;Test&lt;/a&gt;" ),

//$array[] = array("call" => "Count: " . count( $calls) . " User:  $user_id Time: " . date("H:i:s") );
echo $yui->array_to_yuixml($array);
?>
