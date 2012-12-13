<?php
$this->use_page();
$saturday = $this->page->get_global_setting( 'global_task_work_sat');
$sunday =  $this->page->get_global_setting( 'global_task_work_sun');
if( $est_day_dep == '' || $est_day_dep == 0 ){
    $due_date=strtotime('now');
} else {
    $x = $est_day_dep;
    //$x = $x - 1;
    $current_day = strtotime('yesterday');
    file_put_contents('/tmp/est_day', $est_day_dep . "\n");
    while( $x >= 0 ){
        $use_day = true;
        $current_day = strtotime("+1 day" , $current_day);
        //Check if today is a saturday, and if we can use saturdays
        if( $saturday != '1' && date('N' , $current_day ) == 6){
            $use_day = false;
        }
        //Check if today is a sunday, and if we can use sundays
        if( $sunday != '1' && date('N' , $current_day ) == 7){
            $use_day = false;
        }
        //Check if today is a holiday date
        if( $use_day == true ){
            $sql = "SELECT * FROM tbl_global_task_holidays WHERE date = '" . date('Y-m-d' , $current_day) . "'";
            $ct = $this->db->num_rows($this->db->query( $sql ));
            if( $ct != 0 ){
                $use_day = false;
            }
        }
        //Check if any of the critira for today as a day off, if not then decrease dates
        if( $use_day == true ){
            $x = $x - 1;
            file_put_contents('/tmp/est_day', $x . "\n" . date("Y-m-d" , $current_day) . "\n$sql\n", FILE_APPEND);
        }
        
    }
    $due_date=$current_day;
}




//$current_day = strtotime('July 4 2012');

$due_date = date("Y-m-d H:i:s" , $current_day );
?>
