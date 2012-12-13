<?php
    $message = $this->db->fetch_assoc($this->db->query("SELECT * FROM eml_message WHERE mid = '$module_id'"));
    $email_arr = $this->db->fetch_assoc($this->db->query("SELECT b.* FROM contacts_email a LEFT JOIN contacts b ON a.contact_id = b.contact_id WHERE email = '" . $message['from_mailbox'] . "@" . $message["from_host"] . "'"));    
    if( count($email_arr) != 0 ){
        if( $email_arr['type'] == 'People'){
            $id = $email_arr['company'];
        } else {
            $id = $email_arr['contact_id'];
        }
        //$id = $email_arr['contact_id'];
        if( $id != ''){
            //$this->page->eapi_account->cache_account( $id );
            $matches = array();
            $defaults["contact_module_id"] = $id;
            $defaults["contact_module_name"] = "CONTACTS";
        }
    }
    $defaults["CaseOrigin"] = "email";
    $defaults["Status"] = "Active";
?>
