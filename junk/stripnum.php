<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include "../class/global.config.php";
include "../class/database.inc.php";
$db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
$result = $db->query("SELECT * FROM contacts_phone");
while( $row = mysql_fetch_assoc($result)){
    foreach( $row as $n => $v ){
        if($n == "number"){
            echo "$v = " . preg_replace('/[^0-9.]*/','', $v) . "\n" ;
            $tmpnum = $v;
            $tmppr = preg_replace('/[^0-9.]*/','', $v);
        } elseif( $n == "type"){
            $tmptype = $v;
        } elseif( $n == "phone_id") {
           $tmpid = $v;
        }
    }
    $db->query("UPDATE contacts_phone SET `number` = '$tmppr' WHERE `number` = '$tmpnum' ");
    //echo "\n";
    $num = preg_match("/\D/",'', $row["number"]);
    //echo $row['number'] . " = " . $num . "\n";
    
}

?>
