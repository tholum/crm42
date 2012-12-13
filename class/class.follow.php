<?php
class follow {
    public $page;
    public $db;
    function __construct($page=''){
        if($page!=''){
            $this->page = $page;
        } else {
            $this->page = new basic_page();
        }
        $this->db = $this->page->db;
    }
    function get_followers_by_module($module_name,$module_id){
        $result = $this->db->query("SELECT * FROM follow WHERE module_name = '$module_name' AND module_id = '$module_id'");
        $return = array();
        while( $row = $this->db->fetch_assoc($result)){
            $return[$row['user_id']] = $row;
        }
        return $return;
    }
    function follow_module( $module_name , $module_id , $user_id=''){
        if( $user_id == ''){
            $user_id = $_SESSION['user_id'];
        }
        $module_name = $this->db->clean_string($module_name);
        $module_id = $this->db->clean_string($module_id);
        $user_id = $this->db->clean_string($user_id);
        $this->db->query("INSERT IGNORE INTO follow (`module_name` , `module_id` , `user_id`) VALUES('$module_name','$module_id','$user_id')");
    }
    function unfollow_module( $module_name , $module_id , $user_id=''){
        if( $user_id == ''){
            $user_id = $_SESSION['user_id'];
        }
        $module_name = $this->db->clean_string($module_name);
        $module_id = $this->db->clean_string($module_id);
        $user_id = $this->db->clean_string($user_id);
        $this->db->query("DELETE FROM follow WHERE user_id = '$user_id' AND module_name = '$module_name' AND module_id = '$module_id'");
    }
    function followed_activity($user_id=''){
        if( $user_id == ''){
            $user_id = $_SESSION['user_id'];
        }
        $user_id = $this->db->clean_string($user_id);
        $sql="SELECT b.* FROM `follow` a LEFT JOIN activity_log b ON a.module_name = b.module_name AND a.module_id = b.module_id WHERE a.user_id = '$user_id'";
        $result = $this->db->query($sql);
        $return = array();
        while( $row = $this->db->fetch_assoc($result)){
            $return[] = $row;
        }
        
        return $return;
    }
}

?>