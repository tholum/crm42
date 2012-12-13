<?php
class TimeTracker {

    //////////////////////////////////////////////////////////////////
    // PROPERTIES
    //////////////////////////////////////////////////////////////////

    public $moduleID;
    public $moduleName;
      
    
    //////////////////////////////////////////////////////////////////
    // Constructor
    //////////////////////////////////////////////////////////////////
    
    public function __construct(){
        $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
    }
    
    //////////////////////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////////////////////
    
    public function setModuleName($moduleName) {
        $this->moduleName = $moduleName;
    }
    
    public function setModuleID($moduleID) {
        $this->moduleID = $moduleID;
    }     
 
    
    //////////////////////////////////////////////////////////////////
    // getTimeEntries
    //////////////////////////////////////////////////////////////////
    
    public function getTimeEntries() {
        $sql = 'SELECT * FROM time_tracker WHERE `module_name`="'.$this->moduleName.'" AND `module_id`="'.$this->moduleID.'" ORDER BY `start_time`';
        
        $result = $this->db->query( $sql );
        while($row=$this->db->fetch_assoc($result)){
            $sql_row[] = $row;
        }
        
        return $sql_row;
    }
    
   
    //////////////////////////////////////////////////////////////////
    // getOpenEntries
    // check for etries with no end time and return thier id's
    // Theoretically, there should one by one open at any time
    //////////////////////////////////////////////////////////////////
    public function getOpenEntryId($entries , $me = true ){
        $ar = $this->getOpenEntry($entries , $me );
        if( $ar ){
            return $ar[0];
        } else {
            return false;
        }
        
    }
    public function getOpenEntry($entries , $me = true ) {
        if (empty($entries)) {
            return false;
        } else {
            $open_entry = array();
            foreach($entries as $entry) {
                if ($entry['end_time'] == '0000-00-00 00:00:00' && (( $entry['user_id'] == $_SESSION['user_id'] ) || $me == false ) ) {
                    $open_entry[] = $entry['time_tracker_id'];
                }
            }   
            return $open_entry;
        }
    }

    
    //////////////////////////////////////////////////////////////////
    // newTimeEntry
    //////////////////////////////////////////////////////////////////
    
    public function newTimeEntry($user_id,$module_name,$module_id,$start_time) {
 
        $insert_sql_array = array();
        $insert_sql_array["user_id"] = $user_id;
        $insert_sql_array["module_name"] = $module_name;
        $insert_sql_array["module_id"] = $module_id;
        $insert_sql_array["start_time"] = $start_time;
        $insert_sql_array["end_time"] = '';
        $this->db->insert('time_tracker',$insert_sql_array);
        
        return $this->db->last_insert_id();
    }

    //////////////////////////////////////////////////////////////////
    // setTimeStart
    //////////////////////////////////////////////////////////////////
    
    public function setTimeStart($tt_id,$time='') {
        if (empty($time)) { $time = date('Y-m-d H:i:s'); }
        $result = $this->db->query("UPDATE time_tracker SET `start_time` = '$time' WHERE `time_tracker_id`='$tt_id'");
        if ($result) { return true; } else { return false; }
    }
    
    //////////////////////////////////////////////////////////////////
    // setTimeEnd
    //////////////////////////////////////////////////////////////////
    
    public function setTimeEnd($module_name,$module_id,$endTime='') {
        $result = $this->db->query("UPDATE time_tracker SET `end_time` = '$endTime' WHERE `module_name` = '$module_name' AND `module_id`='$module_id' AND `end_time`='0000-00-00 00:00:00'");
        if ($result) { return true; } else { return false; }
    }
    
    //////////////////////////////////////////////////////////////////
    // delTimeEntry
    //////////////////////////////////////////////////////////////////
    
    public function delTimeEntry($tt_id) {
        $result = $this->db->query("DELETE FROM time_tracker WHERE `time_tracker_id` = $tt_id");
        if ($result) { return true; } else { return false; }
    }    
}

?>