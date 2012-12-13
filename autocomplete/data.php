<?php
mysql_connect('localhost','root','vertrigo');
mysql_select_db('event_management');
$contact_json = "";
	$sql="select * from contacts where type='People'";
	$record=mysql_query($sql);
	while($row = mysql_fetch_array($record)){
		$contact_json .= '{"caption":"'.$row[first_name].' '.$row[last_name].'","value":"'.$row[contact_id].'"},';
	}
	$contact_json = '['.substr($contact_json,0,strlen($contact_json)-1).']';
	echo $contact_json;
?>