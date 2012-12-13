<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require_once('app_code/global.config.php');
require_once('class/config.inc.php');
require_once('class/class.timeTracker.php');

$timetracker = new TimeTracker();

switch($_GET['action']){
    
    case 'newTimeEntry':
        
        $cur_ts = time();
        $cur_time = date('Y-m-d H:i:s',$cur_ts);
        
        $tt_id = $timetracker->newTimeEntry($_REQUEST['user_id'],$_REQUEST['module_name'],$_REQUEST['module_id'],$cur_time);
        if ($tt_id) {
            
            $start_time = date('g:i', $cur_ts);
            $start_date = date('n/j', $cur_ts);            
            
            ?>
            <tr data-id="<?php echo $tt_id; ?>">
                <td><?php echo $start_time; ?></td>
                <td><?php echo $start_date; ?></td>
                <td>--</td>
                <td><img src="images/trash_can.png" alt="delete" onclick="tt.delTimeEntry(<?php echo $tt_id; ?>);" /></td>
            </tr>             
            <?php
            
        }
        break;
    case 'setTimeEnd':
        $end_time = date('Y-m-d H:i:s');
        
        $result = $timetracker->setTimeEnd($_REQUEST['module_name'],$_REQUEST['module_id'],$end_time);
        if ($result) {
            echo 'pass';
        }
        break;
        
    case 'getUpdatedEntries':
        $timetracker->setModuleName($_REQUEST['module_name']);
        $timetracker->setModuleID($_REQUEST['module_id']);
        $entries = $timetracker->getTimeEntries();
        
        foreach($entries as $entry) {
            $start_stt = strtotime($entry['start_time']);
            $start_time = date('g:i', $start_stt);
            $start_date = date('n/j', $start_stt);
            
            if ($entry['end_time'] == '0000-00-00 00:00:00') {
                $end_time = '--';
                $diff = '--';
            } else {
                $end_stt = strtotime($entry['end_time']);
                $end_time = date('g:i', $end_stt);
                
                $diff_time = $end_stt - $start_stt;
                
                if ($diff_time < 60) {
                    $diff_time = 60;
                }
                
                $diff = $diff_time / (60*60);
                $diff = number_format($diff, 1);                
            }
            

            ?>
        <tr data-id="<?php echo $entry['time_tracker_id']; ?>">
            <td><?php echo $start_time.' - '.$end_time; ?></td>
            <td><?php echo $start_date; ?></td>
            <td><?php echo $diff; ?>hr</td>
            <td><img src="images/trash_can.png" alt="delete" onclick="tt.delTimeEntry(<?php echo $entry['time_tracker_id']; ?>);" /></td>
        </tr>
            <?php
        }
        break;
    case 'delTimeEntry':        
        $result = $timetracker->delTimeEntry($_REQUEST['tt_id']);
        if ($result) {
            echo 'pass';
        }
        break;
}

?>