<?php
require_once("class/class.user.php");
require_once("class/class.contacts.php");
class BigBlue {
    function  __construct() {

    }
    function run_url( $url ){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        $output = curl_exec( $ch );
        curl_close($ch);
        return $output;
    }
    
    function hash_salt( $string ){
        //echo $string . " " . BIGBLUEBUTTON_SALT . "<br>";
        return sha1( $string . BIGBLUEBUTTON_SALT );
    }
    function get_meetings(){
        $rand = md5( microtime() . rand() );
        $url = "random=$rand";
        $xml =  $this->run_url( BIGBLUEBUTTON_URL . "/bigbluebutton/api/getMeetings?$url&checksum=" . $this->hash_salt("getMeetings" . $url) );
        $meetings = new SimpleXMLElement( $xml );
        $meets = array();
        foreach ( $meetings->meetings->meeting as $mt ){
            $id = $mt->meetingID;
            $admin_pass = $mt->moderatorPW;
            $attendeePW = $mt->attendeePW;
            $running = $mt->running;
            $meets["$id"] = array();
            $meets["$id"]["id"] = "$id";
            $meets["$id"]["admin"] = "$admin_pass";
            $meets["$id"]["user"] = "$attendeePW";
            $meets["$id"]["running"] = "$running";
            
        }
        return $meets;
        
    }
    function create_meeting( $name , $adminpass = '' ,  $pass='' ){
        $url = 'name=' . urlencode(  $name );
        $id = md5( rand() . microtime() );
        if( $adminpass != ''){
            $url .= "&moderatorPW=$adminpass";
        }
        if( $pass != '' ){
            $url .= "&attendeePW=$pass";
        }
        
        $url .=  "&meetingID=" . $id ;
        $url = $url . "&checksum=" . $this->hash_salt("create" . $url);
        //echo BIGBLUEBUTTON_URL . "/" . $url;
        //echo BIGBLUEBUTTON_URL . "/bigbluebutton/api/create?" . $url . "<br>";
        return array( "result" => $this->run_url( BIGBLUEBUTTON_URL . "/bigbluebutton/api/create?" . $url) , 
                      "id" => $id );
    }
    function join_meeting( $id , $name='' , $pass='' ){
        $url = "fullName=" . urlencode( $name ) . "&meetingID=" . urlencode( $id ) . "&password=" . urlencode( $pass);
        return BIGBLUEBUTTON_URL . "/bigbluebutton/api/join?$url&checksum=" . $this->hash_salt( "join$url" );
        
    }
    
    
}
  ?>