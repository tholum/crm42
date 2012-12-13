<?php
Zend_Loader::loadClass('Zend_Gdata');
 Zend_Loader::loadClass('Zend_Gdata_AuthSub');
 Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
 Zend_Loader::loadClass('Zend_Gdata_Calendar');
 Zend_Loader::loadClass('Zend_Gdata_Gapps');
 
$domain = 'couleetechlink.com'; //CTLTODO make this a global setting
$email = $username . '@' . $domain;
ob_start();
try{
        $client = Zend_Gdata_ClientLogin::getHttpClient($email, $password, 'apps');
        $apps = new Zend_Gdata_Gapps( $client , $domain);
        $gdClient = new Zend_Gdata($client);
        $user = $apps->retrieveUser($username);
        $logged_in = true;

} catch (Zend_Gdata_App_CaptchaRequiredException $cre) {

    echo 'URL of CAPTCHA image: ' . $cre->getCaptchaUrl() . "\n";

    echo 'Token ID: ' . $cre->getCaptchaToken() . "\n";
        $logged_in = false;
        file_put_contents('/var/log/email_client.log', 'URL of CAPTCHA image: ' . $cre->getCaptchaUrl() , FILE_APPEND );
} catch (Zend_Gdata_App_AuthException $ae) {

   echo 'Problem authenticating: ' . $ae->exception() . "\n";
   file_put_contents('/var/log/email_client.log', 'Problem authenticating: ' . $ae->exception() . "\n$username $password\n", FILE_APPEND );
        $logged_in = false;
}


ob_end_clean();




if( $logged_in == true ){
    $user_info = array();
    $user_info['user_name'] = $username;
    $user_info['password'] = hash("sha256", $password . SALT );
    $user_info['first_name'] = $user->name->givenName;
    $user_info['last_name'] = $user->name->familyName;
    $result = $this->db->query("SELECT * FROM tbl_user WHERE user_name = '$username'");
    if( $this->db->num_rows($result ) == 0 ){
        $this->db->insert('tbl_user' , $user_info );
    } else {
        $row = $this->db->fetch_assoc($result);
        $this->db->update('tbl_user', $user_info , 'user_id' , $row['user_id']);
//        $this->db->query("UPDATE tbl_user SET password = '" . md5($password) . "' WHERE user_id = '" . $row['user_id'] . "'");
    }
    $preauth = true;
    
}
?>
