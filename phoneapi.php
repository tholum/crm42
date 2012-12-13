<?php
require_once('class/global.config.php');
require_once('class/database.inc.php');
/*
 * Please note this is a quick mockup, I will be making this its own class 
 * http://slimcrm.com/webdevel/svn/eapi/trunk/phoneapi.php?action=RING&phone_number=6084060226&user_id=100&call_id=123990sdf&direction=in
 * 
 */

$action = $_REQUEST["action"];
$phone_number = $_REQUEST["phone_number"];
$exten = $_REQUEST["exten"];

//$user_id = $_REQUEST["user_id"];
$call_id = $_REQUEST["uid"];
$ip = $_SERVER["REMOTE_ADDR"];
$direction = strtolower($_REQUEST["direction"]);
if( $call_id == '' ){
    $call_id = md5($user_id . $phone_number);
}
$ok = "NO ACTION";
$db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
if( $exten == "s"){
    $res = $db->query("SELECT * FROM user_settings WHERE name='phone_main_incomming' AND value='true' GROUP BY user_id");
} elseif( substr($exten,0,3 ) == '101' ) {
    $exten_orig = $exten;
    $exten = substr($exten,3 );
    $res = $db->query("SELECT * FROM user_settings WHERE name='phone_extention' AND value='$exten' GROUP BY user_id");
    echo "SELECT * FROM user_settings WHERE name='phone_extention' AND value='$exten' GROUP BY user_id";
}
//$action = "RING";
$phone_number = substr( $phone_number , -10 );
while($row=$db->fetch_assoc($res)){
    $user_id = $row['user_id'];
    switch( strtoupper($action) ){
        case "RING":
            $db->query("DELETE FROM currentcalls WHERE user_id = '$user_id'");
            $db->insert("currentcalls", array(
                "direction" => $direction , 
                "call_id" => $call_id , 
                "user_id" => $row['user_id'] , 
                'phone_number' => $phone_number , 
                'exten' => $exten , 
                'status' => 'RING',
                'ip_address' =>  $ip ) );
            $ok = "OK";
        break;
        case "ANSWER":
            //$db->query("DELETE FROM currentcalls WHERE call_id='$call_id' AND user_id <> '$user_id'");
            $sql = "UPDATE currentcalls SET status = 'ANSWER', exten = '$exten' WHERE call_id = '$call_id' AND user_id = '$user_id'";
            echo $sql . "\n";
            $db->query($sql);
            $ok = "OK";
        break;
        case "HANGUP":
            $db->query("DELETE FROM currentcalls WHERE call_id = '$call_id' AND user_id = '$user_id'");
            $ok = "OK";
        break;    
    }
}
echo "$ok . $phone_number . $exten . $exten_orig";


?>
