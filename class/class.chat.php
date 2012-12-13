<?php

class base_chat {
    var $db;
    var $chat_web_group;
    function __construct(){
        $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
        $this->chat_web_group = array( array( "module_name" => "DEMO" , "module_id" => "1") , array( "module_name" => "DEMO" , "module_id" =>  "2") ,array("module_name" => "DEMO" , "module_id" => "3")  );
    }
    function close_window( $session_id , $module_id , $module_name ){
        $u = array();
        $u['status'] = 'closed';
        $this->db->query("UPDATE chat_session_user SET status = 'closed' WHERE session_id = '$session_id' AND module_id='$module_id' AND module_name = '$module_name'");
    }
    function list_sessions( $module_name , $module_id , $type="normal" ){
        $timeout = time() - CHAT_SESSION_TIMEOUT;
        $sqla = "SELECT * FROM " . CHAT_SESSION . " cs LEFT JOIN " . CHAT_SESSION_USER ." csu ON cs.session_id = csu.session_id WHERE csu.status <> 'closed' AND csu.module_id = '$module_id' AND csu.module_name = '$module_name' AND cs.last_activity > '" . $timeout . "'";
        $res = $this->db->query( $sqla );
        $session_list = array();
        while( $row = mysql_fetch_assoc($res)){
            //$session_list[$row["session_id"] ]["name"] = "XXXX";
            $session_list[$row["session_id"] ] = $row;
            $session_list[$row["session_id"] ]["sqla"] = $sqla;
            if( $row["name"] == '' ){
                $sql_CSU = "SELECT * FROM " . CHAT_SESSION_USER . "   WHERE session_id = '" . $row["session_id"] . "' AND ( module_name <> '$module_name' OR module_id <> '$module_id' )";
                $res2 = $this->db->query($sql_CSU);
               // $users = $this->db->fetch_assoc( $res );
                //$session_list[$row["session_id"] ]["users"] = $this->db->num_rows($res) . "|$sql_CSU" . print_r( $users , true);
                    $usersx = array();
                    while( $row2 = $this->db->fetch_assoc($res2)){
                        $usersx[] = $row2;
                    }
                    $nm = '';
                    $x=0;
                    foreach( $usersx as $user ){
                        if( $x != 0 ){ $nm .= " - "; }else{ $x = 1; }
                        
                        $nm .= $this->get_name( $user["module_name"] , $user["module_id"] );
                    }
                    $session_list[$row["session_id"] ]["name"] = $nm ;
                
                
            }
            if( $type == "full" ){
                $session_list[$row["session_id"] ]["messages"] = $this->list_messages($row["session_id"]);
                
            }
            
        }
        
        return $session_list;
    }
    
    function list_users_inSession( $session_id ){
        $res = $this->db->query("SELECT module_name , module_id , session_id FROM " . CHAT_SESSION_USER . " WHERE session_id = '$session_id'");
        $return = array();
        while( $row = $this->db->fetch_assoc($res)){
            $return[$row["session_id"]] = $row;
        }
        return $return;
    }
    /*This Function Checks if an open session exsists between ONLY the two users, 
     * If one exsists it returns the session id otherwise it creates a new one and returns
     * its session id
     */
    function update_status( $module_name , $module_id){
        $date = time();
        $this->db->query("INSERT INTO chat_status (module_id,module_name,datetime) VALUES ($module_id,'$module_name','$date') ON DUPLICATE KEY UPDATE datetime='$date'");
        $name = $this->get_name($module_name, $module_id);
        $this->db->query("INSERT INTO chat_display_name (module_id,module_name,display_name) VALUES ($module_id,'$module_name','$name') ON DUPLICATE KEY UPDATE display_name='$name'");
        //$res = $this->db->query("SELECT * FROM chat_status");
        /*$r = array();
        while( $row=$this->db->fetch_assoc($res)){
            if( $row["datetime"] > ( time() - 300 )){
                $r[] = array(  "module_id" => $row["module_id"] , "module_name" => $row["module_name"] , "status" => "online");
            } else {
                $r[] = array("module_id" => $row["module_id"] , "module_name" => $row["module_name"] , "status" => "offline");               
            }
        }
        return $r;*/
    }
    function open_session( $from_module_name , $from_module_id , $to_module_name , $to_module_id , $initial_message='' ){
        $debug = '';
        $sessions = $this->list_sessions( $to_module_name , $to_module_id );
        foreach( $sessions as $session ){
            $sql = "SELECT module_name , module_id , session_id FROM " . CHAT_SESSION_USER . " WHERE session_id = '" . $session["session_id"] . "'";
            $debug .= ":SQL:$sql:SQL:\n";
            $res = $this->db->query($sql);
            if( mysql_num_rows( $res ) == 2 ){
                while( $row = $this->db->fetch_assoc($res)){
                        $debug .= ":module_id:" . $row["module_id"] .":module_id:\n";
                        $debug .= ":module_name:" . $row["module_name"] .":module_name:\n";
                    if( $row["module_id"] == $from_module_id && $row["module_name"] == $from_module_name ){
                        return array( "session_id" => $row["session_id"] );
                    }
                }
            }
        }
        return array( "session_id" =>  $this->create_chat( $from_module_name , $from_module_id , $to_module_name , $to_module_id ) );
    }   
    
    
    function list_messages( $session_id ){
        $res = $this->db->query("SELECT * FROM `" . CHAT_MESSAGES . "` cm LEFT JOIN `" . CHAT_DISPLAY_NAME . "` cdn ON cm.module_id = cdn.module_id AND cm.module_name = cdn.module_name WHERE session_id = $session_id");
        $messages = array();
        while( $row = $this->db->fetch_assoc($res) ){
            $messages[ $row["timestamp"] ] = $row;
        }
        return $messages;
    }
    
    function get_name( $module_name , $module_id ){
        switch( $module_name ){
            default:
                return "Unknown";
            break;
            case "DEMO":
                $result = $this->db->query("SELECT display_name FROM " . CHAT_DISPLAY_NAME . " WHERE module_name = '" . $module_name . "' AND module_id = '" . $module_id . "'");
                $arr =  $this->db->fetch_assoc($result);
                return $arr["display_name"];
            case "TBL_USER":
                
                $res = $this->db->query("SELECT  first_name , last_name  FROM tbl_user WHERE user_id = '$module_id'");
                $arr = $this->db->fetch_assoc($res );
                return $arr["first_name"] . " " . $arr["last_name"];
            break;
        }
        
        
    }
    
    function send_message( $session_id , $module_name , $module_id , $message ){
        $cm["session_id"] = $session_id;
        $cm["module_id"] = $module_id;
        $cm["module_name"] = $module_name;
        $cm["timestamp"] = time();
        $cm["message"] = $message;
        $this->db->insert(CHAT_MESSAGES, $cm);
        $cs = array();
        $cs["last_activity"] = time();
        $this->db->update(CHAT_SESSION, $cs , "session_id", $session_id );
    }
    function web_session( $module_name , $module_id , $initial_message , $session_id , $display_name){
        $this->db->query("INSERT INTO chat_display_name (module_id,module_name,display_name) VALUES ($module_id,'$module_name','$display_name') ON DUPLICATE KEY UPDATE display_name='$display_name'");
       // if( $session_id == '' ){
            $cs = array();
            $cs["start_time"] = time();
            $cs["name"] = $name;
            $cs["last_activity"] = time();
            $cs["session_type"] = 'first';
            $this->db->insert(CHAT_SESSION , $cs );
            $session_id = $this->db->last_insert_id();
            foreach( $this->chat_web_group as $user ){
                $csu["session_id"] = $session_id;
                $csu["module_name"] = $user["module_name"];
                $csu["module_id"] = $user["module_id"];
                $csu["status"] = "pending";
                $this->db->insert(CHAT_SESSION_USER, $csu);
            }
                $csu["session_id"] = $session_id;
                $csu["module_name"] = $module_name;
                $csu["module_id"] = $module_id;
                $csu["status"] = "active";
                $this->db->insert(CHAT_SESSION_USER, $csu);
        //}
        $cm = array();
        $cm["session_id"] = $session_id;
        $cm["module_id"] = $module_id;
        $cm["module_name"] = $module_name;
        $cm["timestamp"] = time();
        $cm["message"] = $initial_message;
        $this->db->insert(CHAT_MESSAGES, $cm);
        $res = $this->db->query("SELECT * FROM " . CHAT_MESSAGES . " WHERE session_id = '$session_id'");
        return array( array("session_id" => $session_id , "messages" => $this->db->num_rows($res) ));
        
    }
    function create_chat( $from_module_name , $from_module_id , $to_module_name , $to_module_id , $initial_message='' , $name='' ){
        $cs = array();
        $cs["start_time"] = time();
        $cs["name"] = $name;
        $cs["last_activity"] = time();
        $this->db->insert(CHAT_SESSION , $cs );
        $session_id = $this->db->last_insert_id();
        $csu = array();
        $csu["session_id"] = $session_id;
        $csu["module_name"] = $from_module_name;
        $csu["module_id"] = $from_module_id;
        $csu["status"] = "active";
        $this->db->insert(CHAT_SESSION_USER, $csu);
         $csu["module_name"] = $to_module_name;
        $csu["module_id"] = $to_module_id;
        $csu["status"] = "pending";
        $this->db->insert(CHAT_SESSION_USER, $csu);       
        $cm = array();
        $cm["session_id"] = $session_id;
        $cm["module_id"] = $from_module_id;
        $cm["module_name"] = $from_module_name;
        $cm["timestamp"] = time();
        $cm["message"] = $initial_message;
        $this->db->insert(CHAT_MESSAGES, $cm);
        return $session_id;
    }
    function check_session_timeout( $unixtime ){
        if( $unixtime > time() - 300 ){
            return "online";
        } else {
            return "offline";
        }
    }
    function get_status( $module_name , $module_id){
        $sql = "SELECT datetime FROM chat_status WHERE module_id = '$module_id' AND module_name = '$module_name'";
        $res = $this->db->query($sql );
        //echo $sql;
        if(mysql_num_rows($res) == 1){
           return $this->check_session_timeout(mysql_result($res, 0, "datetime") );
        } else {
            return "offline";
        }
    }
    function list_roster( $module_id , $module_name ){
        $res = $this->db->query("SELECT user_id , first_name , last_name  FROM tbl_user u LEFT JOIN chat_status s ON u.first_name = s.module_id AND s.module_name = 'TBL_USER'");
        $array = array();
        while( $row = $this->db->fetch_assoc($res )){
            
            $array[] = array("module_name" => "TBL_USER" , "module_id" => $row["user_id"] , "display_name" => $row["first_name"] . " " . $row["last_name"] , "status" => $this->get_status( "TBL_USER" , $row["user_id"] ) );
        }        return $array;
    }
}

?>
