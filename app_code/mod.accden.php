<?
/*
Version 1.0 9/30/10
Auther: Tim Holum

REQUIRED FILES:
app_code/mod.accden.php
app_code/class.phpmailer-lite.php
images/accept.jpg
images/deny.jpg

TABLES REQUIRED
1)All Standard Tables
2) em_accept ( sql below ) 

CREATE TABLE `em_accept` (
  `event_id` int(11) NOT NULL,
  `accept_id` bigint(20) NOT NULL auto_increment,
  `import_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `accept_status` varchar(15) collate latin1_general_ci NOT NULL,
  `accept_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`accept_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=0 ;

REQUIRED PATCHES
	app_code/class.Event.php
		Near Line 2 ( Just after <? )
		*********************************************
		if( MOD_ACCDEN ){ require('mod.accden.php');}
		*********************************************
		Near line 50 ( Directly after all varibles being declared )
		*********************************************
		var $mod_accden;
		*********************************************
		Near line 55 after $this->db =  new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		*********************************************
		if( MOD_ACCDEN ){
			$this->mod_accden = new mod_accden();
			$this->mod_accden->proccess_request_from_get();
		}
		*********************************************
		Near Line 525 
		Directly before <tr align="left"><td colspan="2"><h2>Note:</h2></td></tr>
		*********************************************
		<? if( MOD_ACCDEN ){	$this->mod_accden->gui_from_get(); } ?>
		*********************************************

REQUIRED GLOBAL DEFINITIONS
define( "MOD_ACCDEN" , true );
define( "MOD_ACCDEN_ACCEPT_EMAIL_BODY" , "We Accept GEID: %|em_event:group_event_id|%" ); // CAN BE WHAT EVER YOU WOULD LIKE
define( "MOD_ACCDEN_ACCEPT_SUBJECT", "We Accept GEID: %|em_event:group_event_id|%"); // CAN BE WHAT EVER YOU WOULD LIKE
define( "MOD_ACCDEN_DENY_EMAIL_BODY" , "We Deny GEID: %|em_event:group_event_id|%" ); // CAN BE WHAT EVER YOU WOULD LIKE
define( "MOD_ACCDEN_DENY_SUBJECT", "We Deny GEID: %|em_event:group_event_id|%"); // CAN BE WHAT EVER YOU WOULD LIKE
define( "MOD_ACCDEN_EMAIL_TO" , "pythonholum@gmail.com" ); // CAN BE WHAT EVER YOU WOULD LIKE
define( "MOD_ACCDENY_EMAIL_FROM_EMAIL" , "platform@couleetechlink.com" ); // CAN BE WHAT EVER YOU WOULD LIKE
define( "MOD_ACCDENY_EMAIL_FROM_NAME" , "The Platform" ); // CAN BE WHAT EVER YOU WOULD LIKE

DEFINITIONS EXPLAINED:
MOD_ACCDEN: set this to true to enable the mod, false to disable it
MOD_ACCDEN_ACCEPT_EMAIL_BODY: Email body of events that you accept, Note this accept's custum variable's explained below
MOD_ACCDEN_ACCEPT_SUBJECT: Email subject of events that you accept, Note this accept's custum variable's explained below
MOD_ACCDEN_DENY_EMAIL_BODY: Email body of events that you deny, Note this accept's custum variable's explained below
MOD_ACCDEN_DENY_SUBJECT: Email subject of events that you deny, Note this accept's custum variable's explained below
MOD_ACCDEN_EMAIL_TO: The email address of the person you want to tell that you accepted the event
MOD_ACCDENY_EMAIL_FROM_EMAIL: The email address you want to say the email is from
MOD_ACCDENY_EMAIL_FROM_NAME: The name of the person you want to say the email address is from

CUSTUM VARIABLES:
MOD_ACCDEN_ACCEPT_EMAIL_BODY , MOD_ACCDEN_ACCEPT_SUBJECT , MOD_ACCDEN_DENY_EMAIL_BODY , and MOD_ACCDEN_DENY_SUBJECT Accept these custum variables
%|em_event:field_name|%
change the field_name to any field in em_event to substitue the variable with the value, 
example
%|em_event:group_event_id|% Will be replaced with the group_event_id field of the em_event



*/


require_once("class.phpmailer-lite.php");
class mod_accden 
{
var $db;
var $mail;
var $event_id;
function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->mail = new PHPMailerLite();
		}

		
function mod_accden_send_email(  $subject , $body , $event_id ){
	$where_patch = '';
	if( MOD_ACCDEN_STAFFING_STATUS != NULL && MOD_ACCDEN_STAFFING_STATUS != '' ){
		$where_patch .= " AND status = '" . MOD_ACCDEN_STAFFING_STATUS . "'";
	
	}
	$res2 = $this->db->query( "SELECT * FROM `em_staffing` LEFT JOIN em_certification_type ON em_staffing.type = certification_id LEFT JOIN contacts ON em_staffing.contact_id = contacts.contact_id WHERE event_id = '$event_id'$where_patch" );
	$staff = '';
	while( $row = mysql_fetch_assoc( $res2 ) ){
		if( $row["cert_type"] == MOD_ACCDEN_STAFF_CERTTYPE ){
			$tmp_line = MOD_ACCDEN_PERSTAFF_LINE;
			foreach( $row as $n => $v ){
				$tmp_line = str_replace( "%|staffinfo:$n|%" , $v , $tmp_line );
			}
			$staff .= "$tmp_line\n";
		}
	}
	
	$result = $this->db->query( "SELECT * FROM em_event WHERE event_id = '$event_id' LIMIT 0 , 1" );
	$res_array = mysql_fetch_assoc( $result );
	foreach( $res_array as $colname => $value ){
		$body = str_replace( "%|em_event:$colname|%" , $value , $body );
		$subject = str_replace( "%|em_event:$colname|%" , $value , $subject );
	}
	$body = str_replace( "%|staffline|%" , $staff , $body );
	
	$this->mail->Subject = $subject;
	$this->mail->SetFrom( MOD_ACCDENY_EMAIL_FROM_EMAIL , MOD_ACCDENY_EMAIL_FROM_NAME );
	$this->mail->AddAddress( MOD_ACCDEN_EMAIL_TO );
	//$this->mail->AddAddress( "pythonholum@gmail.com" );
	$result = $this->db->query("SELECT document_name , document_server_name FROM em_documents WHERE event_id = '$event_id' AND document_name LIKE '%csv' ORDER BY `document_id` DESC");
	$this->mail->AddAttachment( "uploads/" . mysql_result( $result , 0 , "document_server_name" ) , mysql_result( $result , 0 , "document_name") );
	$this->mail->msgHTML( $body );
	$this->mail->AltBody = $body ;
	
		$this->mail->Send();
}
		
		


		
/*
function mod_accden_send_email( $to , $from , $subject , $body , $event_id ){

	$header = 'From: ' . MOD_ACCDENY_EMAIL_FROM . "\r\n" .
	"Content-type: text/html\r\n";
	$result = $this->db->query( "SELECT * FROM em_event WHERE event_id = '$event_id' LIMIT 0 , 1" );
	$res_array = mysql_fetch_assoc( $result );
	foreach( $res_array as $colname => $value ){
		$body = str_replace( "%|em_event:$colname|%" , $value , $body );
		$subject = str_replace( "%|em_event:$colname|%" , $value , $subject );
	}
	mail( $to , $subject , $body , $header );
}
*/

function mod_accden_event_accdeny( $event_id , $accden ){
	//global $this->db;
	switch( $accden ){
		case "accept":
			$sql = "UPDATE em_accept SET accept_status = 'accept' , accept_date = NOW() WHERE event_id = '$event_id'";
			$this->db->query( $sql , __LINE__ , __FILE__ );
			$this->mod_accden_send_email(  MOD_ACCDEN_ACCEPT_SUBJECT , MOD_ACCDEN_ACCEPT_EMAIL_BODY , $event_id  );
		break;
		case "deny":
			$sql = "UPDATE em_accept SET accept_status = 'deny' , accept_date = NOW() WHERE event_id = '$event_id'";
			$this->db->query( $sql , __LINE__ , __FILE__ );
			$this->mod_accden_send_email(  MOD_ACCDEN_DENY_SUBJECT , MOD_ACCDEN_DENY_EMAIL_BODY , $event_id  );
		break;
	}
}

function mod_accden_get_status( $event_id ){
	//echo "<tr><td span=2 > IS SET: " . DATABASE_HOST . "</td></tr>";
	$sql = "SELECT * FROM em_accept WHERE event_id = '$event_id' LIMIT 0 , 1";
	//echo "<tr><td span=2 >  " . $sql . "</td></tr>";
	$result = $this->db->query( $sql , __LINE__ , __FILE__ );
	
	//$result = $this->db->query( "SELECT * FROM em_accept WHERE event_id = '$event_id' LIMIT 0 , 1" );
	
	if( mysql_num_rows( $result ) != 0 ){
		$return = mysql_fetch_assoc( $result );
		$return["error"] = "";
	
	} else {
		$return = array( "error" => "no_event" );
	}
	return $return;
	
	//return array( "import_date" => 'somedate' , "error" => '' , "accept_status" => '' );
	}
	
	function proccess_request_from_get(){
		$event_id = $_GET["event_id"];
		$accden = $_GET["accden"];
		$status = $this->mod_accden_get_status( $event_id );
		
		if( $accden == "accept" AND $status["accept_status"] == '' ){
			$this->mod_accden_event_accdeny( $event_id , $accden );
		} elseif ( $accden == "deny" AND $status["accept_status"] == '' ){
			$this->mod_accden_event_accdeny( $event_id , $accden );
		}
	
	}
	
	function gui_from_get(){
		$event_id = $_GET["event_id"];
		$this->mod_accden_gui( $event_id ); 
		
	}
	
function mod_accden_gui( $event_id , $debug=false){
	
	$array = $this->mod_accden_get_status( $event_id );
	//$array = array( "error" => "no_event" ); // Tests the error function
	//$array = array( "import_date" => 'somedate' , "error" => '' , "accept_status" => '' );
	//$array = array( "accept_status" => "accept" , "import_date" => "Some Date", "error" => '' , "accept_date" => "another date" );
	if( $array["error"] == "no_event" ){
		echo '
		<tr align="left">
			<td span=2 >Unable to get info on Acceptance Status</td>
		</tr>
		<tr align="left">
			<td span=2 >Probably due to event being manualy added</td>
		</tr>
		<tr align="left">
			<td span=2 >Or being imported prior to mod being installed</td>
		</tr>
		';
	} else {
		echo '
		<tr align="left">
			<td >Recieved:</td><td>' . $array["import_date"] . '</td>
		</tr>
		';
		if( $array["accept_status"] == '' ){
			echo '
				<tr align="left">
				<td></td><td ><a href="http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '&accden=accept" ><img src="images/accept.jpg" ></a></td>
				</tr>
				<tr align="left">
				<td></td><td ><a href="http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '&accden=deny" ><img src="images/deny.jpg" ></a></td>
				</tr>
				';
		
		
		
		
		} else {
		echo '
		<tr align="left">
			<td >Event Acceptance:</td><td>' . ucwords( $array["accept_status"] ) . " - " . $array["accept_date"] . '</td>
		</tr>';
		}
	
	
	
	}


}

	//$event_id = $_GET["event_id"];
	//$accden = $_GET["accden"];
	//if( $accden == "accept" ){
	//	mod_accden_event_accdeny( $event_id , $accden );
	//} elseif ( $accden == "deny" ){
	//	mod_accden_event_accdeny( $event_id , $accden );
	//}
	//mod_accden_gui( $event_id , true );
}
?>
