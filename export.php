<?
header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=2contacts.csv");
include( "./class/global.config.php");
include( "./app_code/global.config.php");
include( "./class/database.inc.php");
$db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
$sql = 'SELECT contacts.first_name , contacts.last_name , contacts_email.email FROM contacts LEFT JOIN contacts_email ON contacts.contact_id = contacts_email.contact_id LEFT JOIN em_contact_status ON em_contact_status.contact_id = contacts.contact_id WHERE em_contact_status.user_status = "Active"';

$result = $db->query( $sql );
$i = 0;
$num = mysql_num_rows( $result );
while( $num > $i ){
echo str_replace( "," , "" , mysql_result( $result , $i , "first_name" )) . "," . str_replace( "," , "" , mysql_result( $result , $i , "last_name" )) . "," . str_replace( "," , "" , mysql_result( $result , $i , "email" )) . "\n";

$i++;
}



?>