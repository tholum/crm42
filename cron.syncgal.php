<?php
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
$db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
$gal = shell_exec('ldapsearch -L -b "ou=Employees,dc=lab,dc=eapi,dc=com" -D "serviced crmmail" -x -w P0stm@st3r -h 10.0.10.43 cn mail displayname givenName sn');
//echo $gal;
$gal_arr = explode("#" , $gal );
foreach( $gal_arr as $person ){
    $pa = explode( "\n" , $person );
    if(strstr($pa[1], "dn:" ) !== false && strstr($pa[2], "cn:" ) !== false && strstr($pa[3], "sn:" ) !== false  ){
        $fna = explode(":" , $pa[4] );
        $fn = trim($fna[1]);
        $lna = explode(":" , $pa[3] );
        $ln = trim($lna[1]);
        $ema = explode(":" , $pa[6] );
        $em = trim($ema[1]);
        $i = array();
        $i['type'] = 'People';
        $i['first_name'] = $fn;
        $i['last_name'] = $ln; 
        $i['title'] = '';
        $i['company'] = '1';
        $result = $db->query("SELECT * FROM contacts_email WHERE email ='$em'");
        if(mysql_num_rows($result) == 0 ){
            $db->insert('contacts', $i);
            $cid = $db->last_insert_id();
            $i = array();
            $i['contact_id'] = $cid;
            $i['email'] = $em;
            $i['type'] = 'Work';
            $db->insert('contacts_email', $i);
        }
        
    }
}
?>
