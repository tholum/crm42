<?php
//ini_set('display_errors',1);
ob_start();
require_once('app_code/global.config.php');
require_once('class/config.inc.php');
require_once('class/class.email_client.php');
require_once('class/class.flags.php');
require_once('class/class.GlobalTask.php');
require_once('class/class.smtp.php');
require_once('class/class.tasks.php');
require_once('class/class.display.php');
require_once('class/class.casecreation.php');
require_once('class/class.dynamicpage.php');
require_once('class/class.contacts.php');
require_once('class/class.FctSearchScreen.php');
require_once('class/class.eapi_order.php');
require_once('class/class.eapi_account.php');
require_once('class/class.cases.php');
require_once('class/class.note.php');
require_once('class/class.knowledgebase.php');
require_once('class/class.welcome.php');
require_once('app_code/config.inc.php');
require_once( 'class/class.contacts.php');
require_once('app_code/zipcode.class.php');
require_once('app_code/class.Event_Contacts.php');
require_once('class/class.local_calendar.php');
ob_end_clean();
$calendar = new calendar();
$calendar_id = $_REQUEST['calendar'];
//$calendar_id = '1';
$source = $_REQUEST['source'];
$color=$_REQUEST['color'];
if($color == ''){
    $color='ff00ff';
}
$page = new basic_page();
        $agenda = array();
        $id = 0;
if( $source == 'flow_chart_tasks'){
    $result = $page->db->query("SELECT a.module , a.module_id , a.due_date , b.name FROM assign_flow_chart_task a LEFT JOIN tbl_global_task b ON a.flow_chart_id = b.global_task_id WHERE task_status = 'Active'");
            $year = date('Y');
            $month = date('m');


    while( $row = $page->db->fetch_assoc($result)){
        $start_time = strtotime($row['due_date']);
        $date = date("Y-m-d" , $start_time);
        $agenda[] = array('id' => $id,
                            'title' => "FCT: " . $row['name'],
                            'start' => "$date",
                            'allDay' => true ,
                            'color' => "#$color",
                            'className' => 'cal_flowchart',
                            'url' => "Flow Chart Task attached to " . $row['module'] . " " . $row['module_id']);
                            
        $id++;
    }
}
if( $source == 'local'){
    $agenda = $calendar->get_events($calendar_id);
}
	echo json_encode($agenda);
$agenda[] = array(
			'id' => 111,
			'title' => "Event1",
			'start' => "$year-$month-05T14:30:00",
                        'end' => "$year-$month-05T15:30:00",
                        'allDay' => false ,
    'className' => 'cal_tim',
                        'url' => 'test'
		);
		
		$agenda[] = array(
			'id' => 222,
			'title' => "Event2",
			'start' => "$year-$month-06",
			'end' => "$year-$month-07",
			'url' => "http://yahoo.com/"
		);
?>