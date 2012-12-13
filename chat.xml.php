<?php
header ("content-type: text/xml");
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

	echo '<?xml version="1.0"?>';
ini_set("DISPLAY_ERRORS" , 0 );
ini_set("error_reporting" , '' );

    /*freeat23
     * Normaly most of these are included automaticly in the config, I just wanted to have the fewest ties to the platform
     * That makes it so it is the most flexable
     */
    require_once('class/global.config.php');
    require_once('class/database.inc.php');
    require_once('class/class.yui.php');
    require_once('class/class.chat.php');

    $yui = new phpyui;
    $chat = new base_chat();
    
    $func = $_GET["function"];
    $format = $_REQUEST["format"];

    switch( $func ){
        case "web_search":
            $module_id = $_REQUEST["module_id"];
            $module_name = $_REQUEST["module_name"];
            $initial_message = $_REQUEST["message"];
            $session_id = $_REQUEST["session_id"]; // This can be left blank
            $display_name = $_REQUEST["display_name"];
            $array = $chat->web_session( $module_name , $module_id , $initial_message , $session_id , $display_name);
        break;
        case "list_sessions_full":
            $module_id = $_REQUEST["module_id"];
            $module_name = $_REQUEST["module_name"];
            $array = $chat->list_sessions($module_name, $module_id , "full");
         break;   
        case "get_display_name":
            $module_id = $_REQUEST["module_id"];
            $module_name = $_REQUEST["module_name"];
            $array = array( array( "display_name" => $chat->get_name( $module_name , $module_id ) ) );
            //$array = $chat->get_name( $module_name , $module_id );
        break;
        case "list_sessions":
            $module_id = $_REQUEST["module_id"];
            $module_name = $_REQUEST["module_name"];
            $array = $chat->list_sessions($module_name, $module_id);
        break;
        case "list_messages":
            $session_id = $_REQUEST["session_id"];
            $array = $chat->list_messages($session_id);
        break;
    
        case "list_roster":
            $module_id = $_REQUEST["module_id"];
            $module_name = $_REQUEST["module_name"];
            $chat->update_status( $module_name , $module_id);
            $array = $chat->list_roster($module_name, $module_id);            
        break;
        case "update_status":
            $module_id = $_REQUEST["module_id"];
            $module_name = $_REQUEST["module_name"];
            $array = $chat->update_status($module_name, $module_id);
            //$array = array(array());
        break;
        case "open_session":
            $from_module_id = $_REQUEST["from_module_id"];
            $from_module_name = $_REQUEST["from_module_name"];
            $to_module_id = $_REQUEST["to_module_id"];
            $to_module_name = $_REQUEST["to_module_name"];
            $array = array( $chat->open_session($from_module_name, $from_module_id, $to_module_name, $to_module_id) );// This is one of the few times that the output is not a double nested array, So I have to make it one
            //print_r( $array );
        break;
        case "send_message":
            $session_id = $_REQUEST["session_id"];
            $module_id = $_REQUEST["module_id"];
            $module_name = $_REQUEST["module_name"];
            $message = $_REQUEST["message"];
            $chat->send_message( $session_id , $module_name , $module_id , $message );
            $array= array(array('Message Worked' => "Yaa!"));
        break;
        case "close_window":
            $session_id = $_REQUEST["session_id"];
            $module_id = $_REQUEST["module_id"];
            $module_name = $_REQUEST["module_name"];
            $chat->close_window( $session_id , $module_id , $module_name );
            $array= array(array('Message Worked' => "Yaa!"));
        break;
    }
    switch( $format ){
        case "yui":
        case "YUI":
        default:
            echo $yui->array_to_yuixml($array);
        break;
        case "xml":
            echo $yui->array_to_xml($array);
        break;
    }
    


?>
