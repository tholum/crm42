<?php
require_once(dirname(__FILE__) . '/adLDAP.php');
$adldap = new adLDAP( 
	array( 
		"account_suffix" => "@test.slimcrm.com" , 
		"base_dn" => "DC=test,DC=slimcrm,DC=com" , 
		"domain_controllers" => array("50.57.184.4") , 
		"admin_username" => "administrator" , 
		"admin_password" => "CTL-tmp-domaintestD5v5mqV6D"
	  ) );
echo $adldap->authenticate("tholum", "Password1");
echo "\n";
$c = $adldap->user()->groups('tholum');
var_dump( $c );

?>
