<?php
ini_set("dislpay_errors", 1);
require_once('class/global.config.php');
require_once('class/database.inc.php');
//Requires Pear, SO include the pear to the include path

if( defined(PEAR_INCLUDED) == false ){
    define("PEAR_INCLUDED" , true );
    set_include_path(get_include_path() . PATH_SEPARATOR . "./pear/");
}
require_once("Mail.php");
require_once("Mail/mime.php");
class smtp {
    var $db;
    var $smtp;
   
    function __construct(){
        $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
        
    }
    function connect_to_smtp($module_name , $module_id ){
        $result = $this->db->query("SELECT * FROM eml_mailboxs WHERE module_name = '$module_name' and module_id = '$module_id' ");
        $info = $this->db->fetch_assoc($result);
         $smtp_info = array('host' => $info["smtp_host"] , 'port' => $info["smtp_port"] , 'auth' => true , 'username' => $info["username"] , 'password' => $info["password"] );
        var_dump( $smtp_info);
        $this->smtp =  Mail::factory('smtp' , $smtp_info);
        return $this->smtp;
    }
    /*
     *$files is an array of files ie array( '/var/path/to/file/1.txt' , '/path2/file.docx')
     * 
     */
    function send_email( $module_name , $module_id , $to , $from , $subject , $message , $files = '' , $overide='' ){
        $options = array();
        $options['parrent_mid'] = '';
        $options['active'] = '0';
        $options['read'] = '1';
        //CTLTODO: Find a better way to do this
        $options['module_name'] = 'TBL_GROUP';
        $options['module_id'] = '1';
        $options['cases'] = array();
        foreach( $overide as $n => $v ){
            $options[$n] = $v;
        }
        $group = '';
        if( $options['parrent_mid'] != '' ){
//            $this->db->query($sql, $errorFile, $errorLine, $overide);
            $parrent = $this->db->fetch_assoc($this->db->query("SELECT mid , group_id FROM eml_message WHERE mid='" . $options['parrent_mid'] . "'"));
            if($parrent['group_id'] == '' || $parrent['group_id'] == '0' ){
                $this->db->update('eml_message', array('group_id' => $parrent['mid'] ), 'mid', $parrent['mid']);
                $group = $parrent['mid'];
            } else {
                $group = $parrent['group_id'];
            }
            
        }
        $from_arr = explode('@' , $from );
        $i = array();
        $i['subject'] = $subject;
        $i['body'] = $message;
        $i['encoding'] = 'plain';
        $i['unixtime'] = strtotime('NOW');
        $i['importetime'] = date('Y-m-d H:i:s');
        $i['from_displayname'] = '';
        $i['from_mailbox'] = $from_arr[0];
        $i['from_host'] = $from_arr[1];
        $i['read'] = $options['read'];
        $i['active'] = $options['active'];
        $i['module_name'] = $options['module_name'];
        $i['module_id'] = $options['module_id'];
        $i['owner_user_id'] = $_SESSION['user_id'];
        $i['group_id'] = $group;
        $i['sent_by_user_id'] = $_SESSION['user_id'];
//        $this->db->insert($table, $DataArray, $printSQL, $keep_tags, $remove_tags, $filterhtml)
        $this->db->insert('eml_message' , $i , false , '' , '' , 0);
        $mid = $this->db->last_insert_id();
        
        $result = $this->db->query("SELECT mid FROM eml_message WHERE group_id ='$group'");
        //$cases = array();
        $mid_arr = array();
        while( $row = $this->db->fetch_assoc($result)){
            if( $row['mid'] != $mid ){
                $mid_arr[$row['mid']] = $row['mid'];
            }
        }
        $where = "( module_name = 'EMAIL' AND module_id = '" . implode( "' ) OR ( module_name = 'EMAIL' AND module_id = '" , $mid_arr) . "' )";
        $result2 = $this->db->query("SELECT case_id FROM cases_activity WHERE $where");
        $case_arr = array();
        while( $row2 = $this->db->fetch_assoc($result2)){
                $case_arr[$row2['case_id']] = $row2['case_id'];
        }
        foreach( $case_arr as $n => $v){
            $i = array();
            $i['case_id'] = $v;
            $i['module_name'] = 'EMAIL';
            $i['module_id'] = $mid;
            $this->db->insert('cases_activity' , $i );
        }
        
        $to_arr = explode(',' , $to);
        $x = array();
        $x['source'] = 'FR';
        $x['mid'] = $mid;
        $ta = explode("@" , $from);
        $x['mailbox'] = $ta[0];
        $x['host'] = $ta[1];
        $this->db->insert('eml_address' , $x);
        foreach( $to_arr as $person ){
            $x = array();
            $x['source'] = 'TO';
            $x['mid'] = $mid;
            $ta = explode("@" , $person);
            $x['mailbox'] = $ta[0];
            $x['host'] = $ta[1];
            $this->db->insert('eml_address' , $x);
        }
//        $message .= print_r( $options , true) . print_r($parrent,true);
        $dir = TMP_UPLOAD . "/". $files;
        $this->connect_to_smtp($module_name, $module_id);
        //$mime = new Mail_mime(array('eol' => $crlf));
        $mime = new Mail_mime();
        $finfo = new finfo(FILEINFO_MIME, "/etc/magic");
        if(file_exists($dir) != false ){
            $d = @dir($dir);
            while( false !== ( $e = $d->read() ) ){
                    if( $e != '.' && $e != '..'){
                        
                        $mime->addAttachment( $dir ."/". $e);
                    }
            }
        }
        $mime->setHTMLBody($message);
//        echo $to . "\n";
//        var_dump($mime->headers( array('From' => $from , 'Subject' => $subject ) ) ); echo "\n";
//        var_dump($mime->get() ); echo "\n";
//        echo $from . "\n$subject\n$to\n$message\n";
//        var_dump( $mime->get() );
        $error = $this->smtp->send( $to , $mime->headers( array('From' => $from , 'Subject' => $subject ) ) ,  $mime->get() ) . "\n";
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir") rmdir($dir."/".$object); else unlink($dir."/".$object);
            }
            }
            reset($objects);
            rmdir($dir); 
        }
        return $error;
        
        
    }

}  
?>