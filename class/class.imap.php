<?php
/*
 * I would like each class to require everything that it use's
 */

ob_start();
require_once('app_code/global.config.php');
require_once('class/config.inc.php');
require_once('class/class.email_client.php');
require_once('class/class.flags.php');
require_once('class/class.GlobalTask.php');
require_once('class/class.smtp.php');
require_once('class/class.display.php');
require_once('class/class.casecreation.php');
require_once('class/class.dynamicpage.php');
require_once('class/class.FctSearchScreen.php');
require_once('class/class.eapi_order.php');
require_once('class/class.eapi_account.php');
require_once('class/class.cases.php');
require_once('class/class.note.php');
require_once('class/class.knowledgebase.php');
ob_end_clean();

class imap {
    var $db;
    var $filters = array();
    var $page;
    function __construct(){
        $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
        $this->get_filters();
        $this->page = new basic_page;
        $this->case = new case_creation();
    }
    function clean_filters(){
        $this->filters = array();
    }
    function get_filters(){
        $result = $this->db->query("SELECT * FROM eml_filters");
        while( $row=$this->db->fetch_assoc($result)){
            if(array_key_exists($row['mailbox_id'], $this->filters ) == false ){
                $this->filters[ $row['mailbox_id'] ] = array();
            }
            $this->filters[ $row['mailbox_id'] ][] = array( 'qualify' => unserialize($row['qualify']) , 'process' => unserialize($row['process']) , 'eml_filter_id' => $row['eml_filter_id'] );
        }
    }
    function check_filters( $mailbox_id ,  $message_data=array() ){
        $return = array();
        $message_data['body'] = $this->page->decode_text($message_data['encoding'], $message_data['body']);
        if(array_key_exists($mailbox_id, $this->filters ) == true ){
            foreach( $this->filters[$mailbox_id] as $filter ){
                $pass = 0;
                $checked = 0;
                foreach($filter['qualify'] as $info ){
                    
                    $string = $message_data[$info["field"]];
                     $passfail = 'NotSet';
                    include("modules/email/filter." . $info["type"] . ".qualify.php");
                    $checked++;
                }
                if( $pass == $checked && $checked != 0 ){
                    foreach( $filter['process'] as $proc){
                       
                        $return[] = $proc;
                    }
                }
            }
        }
        return $return;
    }
    function get_info_data( $info , $message_data ){
        $message_data['body'] = $this->page->decode_text($message_data['encoding'], $message_data['body']);
        foreach( $info as $name => $info_data ){
            $clean = '';
            $string = $info_data['info'];
            include("modules/email/filter." . $info_data["type"] . ".variable.php");
            $return[$name] = $clean;
        }
        return $return;
    }
    function apply_filters( $filter_data , $message_data=array() ){
        $message_data['body'] = $this->page->decode_text($message_data['encoding'], $message_data['body']);
        foreach( $filter_data as $info ){
            $data = $this->get_info_data($info['vars'] , $message_data);
            include("modules/email/process." . $info['type'] . ".php");
            
        }
    }
    function get_email_boxes(){
        $result = $this->db->query("SELECT * FROM eml_mailboxs");
        $return = array();
        while( $row=$this->db->fetch_assoc($result)){
            $return[] = $row;
        }
        return $return;
    }
    function clean_message_id( $message_id ){
        return str_replace( array("<" , ">") , '' , $message_id );
        
    }
    function split_email_address( $address ){
        $addr_array = explode("<", $address);
        $return = array();
        if( count($addr_array) == 2){
            $return["display_name"] = $addr_array[0];
            $arr2 = explode("@", $addr_array[1]);
            if( count( $arr2) == 2 ){
                $return["mailbox"] = $arr2[0];
                $return["host"] = $arr2[1];
            } else {
                $arr3 = explode("@", $addr_array[0]);
                if( count($arr3)==2 ){
                    $return["mailbox"] = $arr3[0];
                    $return["host"] = $arr3[1];                   
                }
            }
            
        } else {
             $arr4 = explode("@", $addr_array[0]);
                if( count($arr4)==2 ){
                    $return["mailbox"] = $arr4[0];
                    $return["host"] = $arr4[1];                   
                }
        }
        $return["host"] = str_replace(">", '', $return["host"]);
        return $return;
    }
    function connect_to_mailbox_by_module( $module_name , $module_id){
        $mailbox = $this->db->fetch_assoc($this->db->query("SELECT * FROM eml_mailboxs WHERE module_name = '$module_name' AND module_id = '$module_id'"));
        //print_r( $mailbox);
        $inbox = imap_open($mailbox["connectstring"] , $mailbox["username"] , $mailbox["password"] , NULL , 1 , array('DISABLE_AUTHENTICATOR' => 'GSSAPI') );
        $ec = " \$inbox = imap_open(" . $mailbox["connectstring"] . " , " . $mailbox["username"]  . ", " . $mailbox["password"] . " , NULL , 1 , array('DISABLE_AUTHENTICATOR' => 'GSSAPI') )\n";
        file_put_contents('log.txt', $ec);
        return $inbox;
    }
    function read_email($mid){
        //echo "READ_EMAIL\n";
        $info = $this->db->fetch_assoc($this->db->query("SELECT * FROM eml_message WHERE mid = '$mid'") );
        if( is_array($info) ){
            //print_r($info);
            $inbox = $this->connect_to_mailbox_by_module($info["module_name"], $info["module_id"]);
            //$emails = imap_search($inbox,'ALL');
            $status = imap_setflag_full( $inbox ,  $info["imap_id"] , "\\Seen", ST_UID);
            //echo gettype($status) . "\n";
            imap_close($inbox);
            
        }
    }
    function check_for_group($subject , $from_host='' , $from_mailbox='' , $to_host='' , $to_mailbox='' ){
        $substr = strpos($subject , "RE");
        $substr = true;
        echo "
  \n\n\n\n\CHECK_FOR_GROUP: $subject \n $from_host\n$from_mailbox          
";
        if( $substr !== false ){
            $subject_orig = $subject;
            $subject = str_replace("RE: " , "" , $subject );
            $subject = str_replace("RE:" , "" , $subject );
            $subject = str_replace("re: " , "" , $subject );
            $subject = str_replace("re:" , "" , $subject );
            $subject = str_replace("Fwd: " , "" , $subject );
            $subject = str_replace("fwd:" , "" , $subject );
            $subject = preg_replace('/[rR][eE]\s*:\s*/' ,'', $subject);
            $subject = preg_replace('/[rR][eE]:\s*/' ,'', $subject);
            $subject = preg_replace('/[rR][eE]\s*:/' ,'', $subject);
            $subject = preg_replace('/[rR][eE]:/' ,'', $subject);
            $sql = "SELECT a.* , b.mailbox , b.host FROM `eml_message` a LEFT JOIN eml_address b ON a.mid = b.mid WHERE a.subject LIKE '%" . addslashes( $subject ) . "%' AND b.mailbox = '$from_mailbox' AND b.host = '$from_host'  ORDER BY unixtime DESC";
            $result = $this->db->query( $sql );
            //CTLTODO create a log function
            $log = "LOG: " .__LINE__  . "," . __LINE__.",".date("Y/m/d H:i:s") . ",\n" .  $sql . "\n" . mysql_num_rows($result);
//            file_put_contents('/var/log/imap.log',$log, FILE_APPEND);
            echo $log;
            //This sorts through all the results and sees if any of them have the same subject Excluding all spaces ext..
            //Then picks the latest message to set
            $data = array();
            while( $row = $this->db->fetch_assoc($result)){
                
                $log = "\n" . preg_replace("/[^a-zA-Z0-9]/", "", strtolower($row['subject']) ) . " = " . preg_replace("/[^a-zA-Z0-9]/", "", strtolower($subject) ) . "\n" ;
                file_put_contents('/var/log/imap.log',$log, FILE_APPEND);
                if( preg_replace("/[^a-zA-Z0-9]/", "", strtolower($row['subject']) ) == preg_replace("/[^a-zA-Z0-9]/", "", strtolower($subject) ) ){
                    file_put_contents('/var/log/imap.log',count( $data) . "\n", FILE_APPEND);
                    if( count( $data) == 0 ){
                        $data = $row;
                    }
                }
            }
            
            file_put_contents('/var/log/imap.log',count( $data) . "\n" . print_r( $data , true ) . "\n", FILE_APPEND);
            if( count( $data) == 0 ){
                return '0';
            } else {
                if( $data["group_id"] != '0'){
                    return $data["group_id"];
                } else {
                    $this->db->query("UPDATE eml_message SET group_id = '" . $data["mid"] . "' WHERE mid = '". $data["mid"] ."'");
                    return $data["mid"];
                }
            }
            
        }
    }
    
    function check_group_archive( $group_id ){
        $sql = "SELECT * FROM eml_message WHERE `group_id` = '$group_id' AND `group_id` <> '0' AND `active` = 0 ORDER BY `unixtime` DESC";
        $result = $this->db->query( $sql );
        $data = array();
        $return = '0';
        while( $row = $this->db->fetch_assoc($result)){
            if( count($data) == 0 ){
                $data = $row;
                $return = $row['owner_user_id'];
            }
        }
        return $return;
        
    }
    
    
    function import_message( $overview , $message , $mailbox , $imap_id , $inbox , $email_id , $overide = array() ){
        
        //Check if message is already imported
        //$imap_id = $this->clean_message_id($overview[0]->message_id);
        $result = $this->db->query("SELECT * FROM eml_message WHERE imap_id = '$imap_id'");
        //If not imported, Import Message
        if(mysql_num_rows($result) == 0 ){
            $tmp_to = array();
            $tmp_fr = array();
            
//            $done_to = array();
//            $done_from = array();
            $eml_message = array();
            $eml_message["subject"] = $overview[0]->subject;
            $eml_message["body"] = $this->process_message( $message );
            if( $overide['found'] == 'PLAIN'){
                $eml_message["encoding"] ='text';
            } else {
                $eml_message["encoding"] = "plain";
            }
            
            $eml_message["unixtime"] = strtotime($overview[0]->date );
            $eml_message["importetime"] = date("Y-m-d H:i:s");
            $from = $this->split_email_address($overview[0]->from );
            $eml_message["from_displayname"] = $from["display_name"];
            $eml_message["from_mailbox"] = $from["mailbox"];
            $eml_message["from_host"] = $from["host"];
            $eml_message["read"] = $overview[0]->seen;
            $eml_message["active"] = 0;
            $eml_message["module_name"] = $mailbox["module_name"];
            $eml_message["module_id"] = $mailbox["module_id"];
            $eml_message["imap_id"] = $imap_id;
            $eml_message["group_id"] = $this->check_for_group($eml_message["subject"], $eml_message["from_host"], $eml_message["from_mailbox"]);
            //Check if current thread is archived
            if( $eml_message['group_id'] != 0 ){
                $eml_message['owner_user_id'] = $this->check_group_archive( $eml_message["group_id"] );
            }
            $eml_message['tmp'] = print_r($overide,true);
            //check filters
            $filter_data = $this->check_filters($mailbox['mailbox_id'], $eml_message);
            //print_r( $filter_data);
            //$table,$DataArray,$printSQL = false,$keep_tags='',$remove_tags='',$filterhtml=1,$overide=array()
            $this->db->insert('eml_message', $eml_message , false , '' , '' , 0 , array('show_error' => false ));
            $mid = $this->db->last_insert_id();
	/*	
            if( $eml_message['group_id'] == '0' || $eml_message['group_id'] == 0 ){
                $this->case->create_case('EMAIL', $mid);
            } else {
                $result = $this->db->query("SELECT mid FROM eml_message WHERE group_id ='". $eml_message['group_id'] . "'");
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
                
            }*/
            $fr = array();
            $fr["source"] = "FR";
            $fr["mid"] = $mid;
            $fr["mailbox"] = $from["mailbox"];
            $fr["host"] = $from["host"];
            $this->db->insert('eml_address', $fr);
            $to = array();
            $to["source"] = "TO";
            $to["mid"] = $mid;
            $to_arr = $this->split_email_address($overview[0]->to);
            $to["mailbox"] = $to_arr["mailbox"];
            $to["host"] = $to_arr["host"];
            $this->db->insert('eml_address', $to, false , '' , '' , 0 , array('show_error' => false ));
            $tmp_to[ $to['mailbox'] . "@" . $to['host']] = $to['mailbox'] . "@" . $to['host'];
            
            $eml_message['mid'] = $mid;
            $this->apply_filters($filter_data , $eml_message);
            $attachments = $this->extract_attachments($inbox, $email_id);
            $folder = IMAP_ATTACHMENTS . "mailbox_" . $mailbox['mailbox_id'];
            if( is_dir( $folder)=== false ){
                mkdir($folder);
            }
            $fileno = 1;
            foreach( $attachments as $a ){
                if( $a['is_attachment'] == 1 ){
                    $ext = explode(".", $a["filename"]);
                    $exten = '';
                    foreach( $ext as $exta){
                        $exten = preg_replace("/[^a-zA-Z0-9]+/", "",$exta);
                    }
                    $filename= $mid . "_" . $fileno . "_" . md5($a["filename"]) . ".$exten";
                    file_put_contents($folder."/".$filename, $a["attachment"]);
                    $file_insert = array();
                    $file_insert["mid"] = $mid;
                    $file_insert["filename"] = $a["filename"];
                    $file_insert["filepath"] = $folder."/".$filename;
                    $this->db->insert('eml_files', $file_insert, false , '' , '' , 0 , array('show_error' => false ));
                    $fileno++;
                }
            }
            //We are putting this at the end becouse a few messages if they have 500+ recipiance will kill it
            $header_info = imap_headerinfo($inbox, $email_id);
            echo "\n\n\n\n\n======================\n";
            var_dump( $header_info);
            foreach( $header_info->fromaddress as $from_addr ){
                if( $fr['mailbox'] != $from_addr['mailbox'] || $fr['host'] != $from_addr['host']){
                    $fr1 = array();
                    $fr1["source"] = "FR";
                    $fr1["mid"] = $mid;
                    $fr1["mailbox"] = $from_addr["mailbox"];
                    $fr1["host"] = $from_addr["host"];
                    $this->db->insert('eml_address', $fr1, false , '' , '' , 0 , array('show_error' => false ));
                    echo "\n==============================================";
                }
            }
            foreach( $header_info->to as $to_addr ){
                if(array_key_exists($to_addr->mailbox . "@" . $to_addr->host , $tmp_to ) == false ){
                    if( $to['mailbox'] != $to_addr->mailbox || $to["host"] != $to_addr->host){
                        $fr1 = array();
                        $fr1["source"] = "TO";
                        $fr1["mid"] = $mid;
                        $fr1["mailbox"] = $to_addr->mailbox;
                        $fr1["host"] = $to_addr->host;
                        $this->db->insert('eml_address', $fr1, false , '' , '' , 0 , array('show_error' => false ));
                        echo "\n==============================================";
                    }
                }
            }
            foreach( $header_info->cc as $cc_addr ){
                if( $to['mailbox'] != $cc_addr->mailbox || $to['host'] != $cc_addr->host){
                    $fr1 = array();
                    $fr1["source"] = "CC";
                    $fr1["mid"] = $mid;
                    $fr1["mailbox"] = $cc_addr->mailbox;
                    $fr1["host"] = $cc_addr->host;
                    $this->db->insert('eml_address', $fr1, false , '' , '' , 0 , array('show_error' => false ));
                     echo "\n==============================================";
                }
            }
            foreach( $header_info->bcc as $bcc_addr ){
                if( $to['mailbox'] != $bcc_addr->mailbox || $to['host'] != $bcc_addr->host){
                    $fr1 = array();
                    $fr1["source"] = "BC";
                    $fr1["mid"] = $mid;
                    $fr1["mailbox"] = $bcc_addr->mailbox;
                    $fr1["host"] = $bcc_addr->host;
                    $this->db->insert('eml_address', $fr1, false , '' , '' , 0 , array('show_error' => false ));
                     echo "\n==============================================";
                }
            }
        }
        
        
    }
    function process_message( $message ){
        
//        $message = imap_utf8($message);
//          $carimap = array("=C3=A9", "=C3=A8", "=C3=AA", "=C3=AB", "=C3=A7", "=C3=A0", "=20", "=C3=80", "=C3=89");
//          $carhtml = array("é", "è", "ê", "ë", "ç", "à", "&nbsp;", "À", "É");
//          $message = str_replace($carimap, $carhtml, $message);
//        $message = nl2br($message);
        $linearr = explode("\n", $message);
        $return = '';
        foreach( $linearr as $line ){
                if( $line[75] == "=" && strlen($line) == '77'){
                    $return .= substr($line , 0 , -2 );//$line[strlen($line) -2]. "\n";
                } else {
                    $return .= $line . "\n";
                }
        }
        return $return;
    }
    
    function extract_attachments($connection, $message_number) {
   
    $attachments = array();
    $structure = imap_fetchstructure($connection, $message_number);
   
    if(isset($structure->parts) && count($structure->parts)) {
   
        for($i = 0; $i < count($structure->parts); $i++) {
   
            $attachments[$i] = array(
                'is_attachment' => false,
                'filename' => '',
                'name' => '',
                'attachment' => ''
            );
           
            if($structure->parts[$i]->ifdparameters) {
                foreach($structure->parts[$i]->dparameters as $object) {
                    if(strtolower($object->attribute) == 'filename') {
                        $attachments[$i]['is_attachment'] = true;
                        $attachments[$i]['filename'] = $object->value;
                    }
                }
            }
           
            if($structure->parts[$i]->ifparameters) {
                foreach($structure->parts[$i]->parameters as $object) {
                    if(strtolower($object->attribute) == 'name') {
                        $attachments[$i]['is_attachment'] = true;
                        $attachments[$i]['name'] = $object->value;
                    }
                }
            }
           
            if($attachments[$i]['is_attachment']) {
                $attachments[$i]['attachment'] = imap_fetchbody($connection, $message_number, $i+1);
                if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                    $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                }
                elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                    $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                }
            }
           
        }
       
    }
   
    return $attachments;
   
}       

    
    function check_mailbox( $mailbox ){
        $inbox = imap_open($mailbox["connectstring"] , $mailbox["username"] , $mailbox["password"] , NULL , NULL , array('DISABLE_AUTHENTICATOR' => 'GSSAPI') );
        
        echo ":" .$inbox . ":\n";
//        $inbox = imap_open("{egnxcs1.lab.eapi.com:993/imap/ssl/novalidate-cert}","Serviced-crmmail","P0stm@st3r" , NULL , 1, array('DISABLE_AUTHENTICATOR' => 'GSSAPI') );
        //This is just temporary
        echo $mailbox['username'];
        if( $mailbox['username'] == 'pythonholum@gmail.com' && 1 == 2 ){
            $emails = imap_search($inbox,'ON ' . date('j F Y'));
            foreach($emails as $email ){
                echo "\n\n===========================";
                
                var_dump(imap_fetchheader( $inbox , $email ));
                echo "\n==";
                var_dump(imap_fetch_overview($inbox, $email));
                echo "\n==";
                var_dump(imap_headerinfo($inbox, $email));
                echo "\n=============================";
            }
        } else {
            //$search = "SINCE '" .date(""). "'";
            $search='SINCE "' . date("j F Y" , strtotime('yesterday')) . '"';
            
            $search = utf8_encode($search);
            echo "\n" . $search . "\n";
            $emails = imap_search($inbox,$search);
            
            if( $emails ){
                rsort( $emails );
                foreach( $emails as $email_id){
                     $message_object = imap_fetchstructure($inbox, $email_id );
                     $overview = imap_fetch_overview($inbox, $email_id);
                    $use = "1";
                    $enc = 0;
                    $found = "NONE";
                    $rank = array();
                    $rank['NONE'] = 1;
                    $rank['PLAIN'] = 2;
                    $rank['HTML'] = 3;
                    
                    if( $rank[$message_object->subtype] > $rank[$found]){
                        $found = $message_object->subtype;
                        $use = "1";
                        $enc = $message_object->encoding;
                    }
                    
                    
                    foreach( $message_object->parts as $n => $partarr ){
                        $large = $n + 1;
                        if(isset($partarr->parts)){
                            foreach($partarr->parts as $nn => $sp ){
                               $small = $nn + 1;
                               if(array_key_exists($sp->subtype , $rank) ){
                                   if( $rank[$sp->subtype] > $rank[$found]){
                                       $found = $sp->subtype;
                                       $use = "$large.$small";
                                       $enc = $sp->encoding;
                                   }
                               }
                            }
                        }
                        else {
                            if(array_key_exists($partarr->subtype , $rank) ){
                                   if( $rank[$partarr->subtype] > $rank[$found]){
                                       $found = $partarr->subtype;
                                       
                                       $use = $large;
                                       $enc = $partarr->encoding;
                                   }
                               }
                        }
                    }
                    
                    $message = imap_fetchbody($inbox, $email_id, $use , FT_PEEK );
                   
                    $message_1 = imap_fetchbody($inbox, $email_id, '1' , FT_PEEK );
                    $message_11 = imap_fetchbody($inbox, $email_id, '1.1' , FT_PEEK );
                    $message_12 = imap_fetchbody($inbox, $email_id, '1.2' , FT_PEEK );
                    $message_2 = imap_fetchbody($inbox, $email_id, '2' , FT_PEEK );
                    ob_start();
                    var_dump($message_object);
                    $message_object_vd = ob_get_contents();
                    ob_end_clean();
                    $debug = array(
                        'found' => $found , 
                        'use' => $use ,
                        'enc' => $enc,
                        'message_1' => $message_1,
                        'message_11' => $message_11,
                        'message_12' => $message_12,
                        'message_2' => $message_2,
                        'message_object' => $message_object_vd
                        );
                    switch( $enc ){
                        case 0:
                            //$message = imap_utf7_decode($message);
                        break;
                        case 1: 
                            $message = quoted_printable_decode(imap_8bit($message));
                        break;
                        case 3:
                            $message=base64_decode($message);
                        break;
                        case 4:
                            $message = quoted_printable_decode($message);
                        break;
                    }
                    $this->import_message( $overview , $message , $mailbox , imap_uid($inbox, $email_id) ,$inbox, $email_id , $debug );
                }            
            }
        }
    }
    
    function check_all_mailboxes(){
        $mailboxes = $this->get_email_boxes();
        foreach( $mailboxes as $mailbox){
            $this->check_mailbox($mailbox);
        }
    }
    
}
?>
