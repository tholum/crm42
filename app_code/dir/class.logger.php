<?php
class Logger
{
	var $log_id;
	var $event_id;
	var $user_id;
	var $field;
	var $action;
	var $old_value;
	var $new_value;
	var $note;
	var $timestamp;
	var $db;
	
	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	}
	
	function setUserId($user_id){
		$this->user_id = $user_id;
	}

	function setEventId($event_id){
		$this->event_id = $event_id;
	}
	
	function setParam($event_id='',$field='',$action='',$old_value='',$new_value='',$note=''){
		if($event_id) { $this->event_id = $event_id; }
		$this->field = $field;
		$this->action = $action;
		$this->old_value = $old_value;
		$this->new_value = $new_value;
		$this->note = $note;
	}

	
	function addLog(){
		$insert_sql_array = array();
		$insert_sql_array[event_id] = $this->event_id;
		$insert_sql_array[user_id] = $this->user_id;
		$insert_sql_array[field] = $this->field;
		$insert_sql_array[action] = $this->action;
		$insert_sql_array[old_value] = $this->old_value;
		$insert_sql_array[new_value] = $this->new_value;
		$insert_sql_array[note] = $this->note;
		$insert_sql_array[timestamp] = time();
		
		$this->db->insert(EM_EVENT_LOGGER,$insert_sql_array);
	}
	
	function getOldValue($table,$id,$value,$field_name,$new_value=''){
		$sql = "Select ".$field_name." from ".$table." where ".$id." ='".$value."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);	
		$row = $this->db->fetch_array($result);		
		if($new_value!=$row[$field_name])
			return $row[$field_name];
		else
			return false;
	}
}
?>