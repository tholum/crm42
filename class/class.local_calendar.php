<?php
class calendar {
    public $db;
    public $page;
    function __construct() {
       
        $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
        $this->page = new basic_page;
    }
    function get_avalbile_calendar_options(){
        $user_id = $_SESSION['user_id'];
        //CTLTODO Create authentication for this
        $result = $this->db->query("SELECT * FROM calendar");
        $selected = 0;
        $return = array();
        while($row = $this->db->fetch_assoc($result) ){
            if( $row['module_name'] == "TBL_USER" && $row['module_id'] == $user_id ){
                $selected = $row['calendar_id'];
            }
            $row['value'] = $row['calendar_id'];
            $row['name'] = $row['title'];
            $return[] = $row;
        }
        return $this->page->array_to_options($return , array('selected' => $selected));
    }
    function get_default_calendar(){
        $default_cal = $this->db->fetch_assoc($this->db->query("SELECT * FROM calendar WHERE module_name = 'TBL_USER' AND module_id = '" .$_SESSION['user_id']. "'"));
        $calendar_id = $default_cal['calendar_id'];
        if( $calendar_id == 0 ){
            $calendar_id = $this->create_calendar();
        }
        return $calendar_id;
    }
    function create_calendar($module_name='TBL_USER' , $module_id='' , $title='' , $source='local' ){
        if( $module_id == '' && $module_name == 'TBL_USER'){
            $module_id=$_SESSION['user_id'];
        }
        if( $module_name == 'TBL_USER' && $title == ''){
            $user_info = $this->db->fetch_assoc($this->db->query("SELECT first_name , last_name FROM tbl_user WHERE user_id = '$module_id'"));
            $title = $user_info['first_name'] . " " . $user_info['last_name'];
        }
        $i = array();
        $i['module_name'] = $module_name;
        $i['module_id'] = $module_id;
        $i['title'] = $title;
        $i['source'] = $source;
        $this->db->insert('calendar', $i);
        return $this->db->last_insert_id();
    }
    
    function create_event($defaults=array()){
        $i = array();
        $default_cal = $this->db->fetch_assoc($this->db->query("SELECT * FROM calendar WHERE module_name = 'TBL_USER' AND module_id = '" .$_SESSION['user_id']. "'"));
        if(count($default_cal) == 0 ){
            $i['calendar_id'] = $this->create_calendar();
        } else {
            $i['calendar_id'] = $default_cal['calendar_id'];
        }
        if($i['calendar_id'] == 0 ){
            $i['calendar_id'] = $this->create_calendar();
        }
        $i['start'] = date("Y-m-d H:i:s");
        $i['end'] = '';
        $i['all_day'] = false;
        $i['title'] = "New Event";
        foreach( $defaults as $n => $v){
            if( ( $n == "start" || $n == "end" )){
                if( is_numeric($v) == false ){
                    $v = strtotime($v);
                }
                if($v > 9338854400 ){
                    $v = $v / 1000;
                }
                $v = date("Y-m-d H:i:s" , $v);
            }
            $i[$n] = $v;
        }
        if( $i['end'] == '' && $i['all_day'] == false){
            $i['end'] = date("Y-m-d H:i:s" , strtotime("+1 hour" , strtotime($i['start'])) );
        }
        $this->db->insert('calendar_data', $i);
         return $this->db->last_insert_id();   
    }
    
    function edit_calendar( $calendar_event_id , $overide=array() ){
        $u = array();
        $return = '';
        foreach( $overide as $n => $v){
            if( ( $n == "start" || $n == "end" )){
                if( is_numeric($v) == false ){
                    $v = strtotime($v);
                }
                if($v > 9338854400 ){
                    $v = $v / 1000;
                }
                $v = date("Y-m-d H:i:s" , $v);
            }
            $u[$n] = $v;
            $return .= "$n = $v:";
        }
        $this->db->update('calendar_data', $u , 'calendar_event_id' , $calendar_event_id);
        return $return . print_r($overide,true);
    }
    function display_event($calendar_event_id){
        $event = $this->db->fetch_assoc($this->db->query("SELECT * FROM calendar_data WHERE calendar_event_id = '$calendar_event_id'"));
        ob_start();
        ?>
<table>
    <tr><td>Title</td><td><select value="<?php echo $event['title']; ?>" onchange="calendar.edit_calendar('<?php echo $calendar_event_id; ?>' , {calendar_id: $(this).val() },{onUpdate: function(response,root){$('#mycal').fullCalendar( 'refetchEvents' );}});" id="event_title" /><?php echo $this->get_avalbile_calendar_options(); ?></select> </td></tr>
    <tr><td>Title</td><td><input value="<?php echo $event['title']; ?>" onchange="calendar.edit_calendar('<?php echo $calendar_event_id; ?>' , {title: $(this).val() },{onUpdate: function(response,root){$('#mycal').fullCalendar( 'refetchEvents' );}});" id="event_title" /> </td></tr>
    <tr><td>Start Time</td><td><input value="<?php echo $event['start']; ?>" onchange="calendar.edit_calendar('<?php echo $calendar_event_id; ?>' , {start: $(this).val() },{onUpdate: function(response,root){$('#mycal').fullCalendar( 'refetchEvents' );}});" class="datetimepicker" id="event_start" /> </td></tr>
    <tr><td>End Time</td><td><input value="<?php echo $event['end']; ?>" onchange="calendar.edit_calendar('<?php echo $calendar_event_id; ?>' , {end: $(this).val() },{onUpdate: function(response,root){$('#mycal').fullCalendar( 'refetchEvents' );}});" class="datetimepicker" id="event_end" /> </td></tr>
</table>

        <?php
        $html=  ob_get_contents();
        ob_end_flush();
        ob_clean();
        return $html;
    }
    function get_events($calendar_id,$color='ff00ff'){
        $agenda = array();
        $result = $this->db->query("SELECT * FROM calendar_data WHERE calendar_id = '$calendar_id'");
        while( $row = $this->db->fetch_assoc($result)){
            $event = array();
            $event['id'] = $row['calendar_event_id'];
            $event['title'] = $row['title'];
            $event['url'] = "calendar_event_id:".$row['calendar_event_id'];
            $start = strtotime($row['start']);
            $end = strtotime($row['end']);
            if( $row['all_day'] == 0 ){
                $event['allDay'] = false;
                $event['start'] =  $date = date("Y-m-d" , $start ) . "T" . date("H:i:s" , $start);
                $event['end'] =  $date = date("Y-m-d" , $end ) . "T" . date("H:i:s" , $end);
            } else {
                $event['allDay'] = true;
                $event['start'] =  $date = date("Y-m-d" , $start);
            }
            $event['eventColor'] = "#$color";
            $agenda[] = $event;
            $id++;
        }
        return $agenda;
    }
}
?>
