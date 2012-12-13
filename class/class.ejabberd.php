<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author tholum
 */
class ejabberd {

    var $db;
    var $ejabberd_db;
    var $username;
    var $password;
    function __construct(){
        $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
        $this->ejabberd_db = new database(EJABBERD_DATABASE_HOST,EJABBERD_DATABASE_PORT,EJABBERD_DATABASE_USER,EJABBERD_DATABASE_PASSWORD,EJABBERD_DATABASE_NAME);

    }
    function ejabberdctl( $run ){
        $command = "/usr/bin/ssh -i /var/www/.ssh/id_rsa ejabberd@localhost '/usr/sbin/ejabberdctl $run'";
       // echo $command;
            $output =  shell_exec( $command  );
            return array( "output" => $output );
            }
    function create_user( $username , $password ){
        $this->ejabberdctl("register $username slimcrm.com $password");
    }
    function get_roster( $username ){
        $roster_out = $this->ejabberdctl( "get_roster $username slimcrm.com" );
        $R_1 = explode( "\n" , $roster_out );
        $roster = array();
        foreach( $R_1 as $line ){
            $RL_1 = explode( " " , $line );
            $roster[] = $RL_1[0];
        }
        return $roster;
    }
    
    function populate_roster( $username ){
       // echo "Populating Roster";
        $roster = $this->get_roster($username);
        $WHERE_EXCLUDE = '(';
        foreach( $roster as $user ){
            $user_name = explode( "@" , $user );
            $WHERE_EXCLUDE .=  " username <> '$user_name' AND";
        }
        $WHERE_EXCLUDE = substr($WHERE_EXCLUDE , 0 , -3 );
        if( $WHERE_EXCLUDE != '' ){
            $WHERE_EXCLUDE = "AND " . $WHERE_EXCLUDE . ' )';
        }
        $res = $this->ejabberd_db->query("SELECT * FROM users WHERE username LIKE '%" . Get_current_folder() . "' $WHERE_EXCLUDE ");
        while( $row=$this->ejabberd_db->fetch_array($res) ){
            $ejab = $this->ejabberdctl("add-rosteritem $username slimcrm.com " . $row["username"] . " slimcrm.com " . $row["username"] . " users both");
           // echo "Adding " . $row["username"] . "<br>";
            //echo $ejab["output"];
            
        }
    }
    
    function get_login($username){
        $this->populate_roster($username);
        $res2 = $this->ejabberd_db->query("SELECT password FROM users WHERE username = '$username'" , __FILE__ , __LINE__);
        $_SESSION["test"] = $this->ejabberd_db->num_rows($res2);
        if( mysql_num_rows( $res2 ) == 0 ){
            $pass = md5( rand( 0 , 2000000) . rand( 0 , 2000000) . rand( 0 , 2000000) . "askdfjl;aksdjf;laksdjfl;ajsdfl;kjasd;lfkjasd;f" );
            $this->create_user($username, $pass);
            $res2 = $this->ejabberd_db->query("SELECT password FROM users WHERE username = '$username'" , __FILE__ , __LINE__);
        }
        $assoc2 = $this->ejabberd_db->fetch_assoc( $res2 );
        $password = $assoc2["password"];
        $this->username = $username;
        $this->password = $password;
        return array( "username" => $username , "password" => $password);
    }

    function proccess_im( $im , $im_network ){
        $server = '';
        $client = '';
        $xmpp = false;
        $im_arr = explode("@" , $im);
        switch( count($im_arr)){
            case 2:
            case "2":
                $server = $im_arr[1];
            default:
            case "1":
                $client = $im_arr[0];
            break;
        }

        switch( $im_network ){
            case "Google Talk":
                if( $server == '' ){
                    $server = "gmail.com";
                }
                $xmpp = true;

            break;
            case "Jabber":
                $xmpp = true;

        }
        
        return array( "server" => $server , "client" => $client , "xmpp" => $xmpp );

    }
    //NOTE TO KEEP IN MIND, dir in archive_collections 0 is from the other person 1 is from us
    function get_chats_by_contact( $contact_id , $limit = "10" , $start = "0"){
        $result = $this->db->query("SELECT im , im_network FROM contacts_im WHERE contact_id = '$contact_id'");
        $accounts = array();
        while( $row=$this->db->fetch_assoc($result)){
            $tmp = $this->proccess_im( $row["im"], $row["im_network"] );
            if( $tmp["xmpp"] == true ){
                $accounts[] = $tmp;
            }
        }
        $WHERE = '';
        foreach( $accounts as $account ){
            $WHERE .= "( with_user = '" . $account["client"] . "' AND with_server = '" . $account["server"] . "') OR";
        }
        $WHERE = substr($WHERE, 0, -3);
        if( $WHERE == ''){
            $WHERE = '1 = 0';
        }
        $sql = "SELECT a.us, a.with_user , a.with_server, a.id , a.change_utc , b.utc , b.dir , b.body FROM ( SELECT * FROM `archive_collections` WHERE $WHERE LIMIT $start , $limit ) AS a JOIN archive_messages b ON a.id = b.coll_id";
        //$sql = "SELECT * ( SELECT * FROM `archive_collections` WHERE $WHERE LIMIT $start , $limit ) AS a JOIN archive_messages b ON a.id = b.coll_id";
        $res2 = $this->ejabberd_db->query($sql , __LINE__ , __FILE__ );
        $return = array();
        while( $row=$this->ejabberd_db->fetch_assoc($res2)){
            if( is_array( $return[ $row["id"] ] ) == false ){
                $return[$row["id"]] = array();
            }
           $return[$row["id"]][ strtotime($row["utc"]) ] = array( "body" => $row["body"] , "with_user" => $row["with_user"] , "with_server" => $row["with_server"] , "dir" => $row["dir"] );
        }
        return $return;


    }


}
?>
