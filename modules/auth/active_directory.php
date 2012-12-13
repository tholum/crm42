<?php
//$db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
try {
$settings = array( 
		"account_suffix" => $this->page->get_global_setting('auth_ad_account_suffix') , 
		"base_dn" => $this->page->get_global_setting('auth_ad_base_dn'), 
		"domain_controllers" => explode( ',' , $this->page->get_global_setting('auth_ad_domain_controllers') ), 
		"admin_username" =>$this->page->get_global_setting('auth_ad_admin_username') , 
		"admin_password" => $this->page->get_global_setting('auth_ad_admin_password' , true )
	  );

$adldap = new adLDAP($settings);
$auth = $adldap->authenticate("$username", "$password");
$c = $adldap->user()->groups($username);
ob_start();
var_dump($auth);
$html = ob_get_contents();
ob_end_clean();
echo "<br/>";
$d = $adldap->user()->info($username , array('displayname'));
echo $d[0]['displayname'][0];

if( $adldap->authenticate("$username", "$password") == 1 ){
    $ln = array();
    foreach( explode(" ", $d[0]['displayname'][0] ) as $n => $v ){
        if( $n == 0 ){
            $first_name = $v;
        } else {
            $ln[] = $v;
        }
    }
    
    
    $user_info = array();
    $user_info['user_name'] = $username;
    $user_info['password'] = hash("sha256", $password . SALT );
    $user_info['first_name'] = $first_name;
    $user_info['last_name'] = implode(" ", $ln );
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
$user_info = "u: $username p: $password Auth:" . $html ;
//var_dump( $d[0]['displayname'] );
} catch ( Exception $e ) {
    
}
?>
