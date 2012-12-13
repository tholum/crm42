<?php

class twitter {
    var $db;
    function __construct(){
        $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
    }
    
    function update_tweet( $data , $result , $search_string='' ){
        $clean = array();
        foreach($data as $n => $v ){
            $clean[$n] = $v;
        }
        $id = $clean['id'];
        $user_id = $_SESSION['user_id'];
        $twitter_name = addslashes($clean['from_user']);
        $tweet = addslashes($clean['text']);
        $search_string=addslashes($search_string);
        $sent_on = date( "Y-m-d H:i:s" ,strtotime($clean['created_at']));
        $sql = "INSERT INTO twitter (`twitter_id`,`user_id`,`twitter_name`,`tweet`,`result`,`sent_on`,`search_string`) VALUES('$id' , '$user_id' , '$twitter_name' ,  '$tweet' , '$result' , '$sent_on' , '$search_string' ) ON DUPLICATE KEY UPDATE result = '$result' ";
        $this->db->query($sql);
        return $sql;
    }
    
    
}

?>