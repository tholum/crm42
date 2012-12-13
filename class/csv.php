<?php
require_once('app_code/global.config.php');
require_once('class/config.inc.php');

	echo 'func';
	$csv_terminated = "\n";
    $csv_separator = ",";
    $csv_enclosed = '"';
    $csv_escaped = "\\";
	$status=$_POST[Status];
	echo $status;
	if(($status=='') or ($status=='Active'))
	   		{
				$Status_value='Active';
			}else
			{
				$Status_value='Complete';
			}
			
			$sql = "Select a.module, a.flow_chart_id, a.product_id,a.task_status, a.due_date, a.created_date, b.name, c.group_id, c.group_name, d.event_date, d.ship_date, d.created, e.first_name, e.last_name,f.contact_id, g.company_name from ". erp_ASSIGN_FCT ." a, tbl_global_task b, tbl_usergroup c, erp_order d, tbl_user e, erp_contactscreen_custom f, contacts g where a.flow_chart_id = b.global_task_id and b.department_id = c.group_id and a.product_id = d.order_id and d.vendor_contact_id = f.contact_id and f.csr = e.user_id and  d.vendor_contact_id = g.contact_id " ;
			if($Status_value)
			{
			$sql.="and a.task_status like '%$Status_value%'";}
			
			if($order_id){
			$sql.=" and a.product_id like '%$order_id%' "; }
			
			if($customer){
			$sql.=" and g.company_name like '%$customer%' "; }
			
			if($csr){
			$sql.=" and e.user_id = '$csr' "; }
			
			if($event_start_date != '' and $event_end_date == ''){
			$sql.=" and d.event_date >= '$event_start_date' "; }
			
			if($event_start_date == '' and $event_end_date != ''){
			$sql.=" and d.event_date <= '$event_end_date' "; }
			
			if($event_start_date != '' and $event_end_date != ''){
			$sql.=" and d.event_date BETWEEN '$event_start_date' and '$event_end_date' "; }
			
			if($fct_name){
			$sql.=" and b.global_task_id = '$fct_name' "; }
			
			if($creat_start_date != '' and $creat_end_date == ''){
			$sql.=" and a.created_date >= '$creat_start_date' "; }
			
			if($creat_start_date == '' and $creat_end_date != ''){
			$sql.=" and a.created_date <= '$creat_end_date' "; }
			
			if($creat_start_date != '' and $creat_end_date != ''){
			$sql.=" and a.created_date BETWEEN '$creat_start_date' and '$creat_end_date' "; }
			
			if($dep_name){
			$sql.=" and c.group_id = '$dep_name' "; }
			
			if($ship_start_date != '' and $ship_end_date == ''){
			$sql.=" and d.ship_date >= '$ship_start_date' "; }
			
			if($ship_start_date == '' and $ship_end_date != ''){
			$sql.=" and d.ship_date <= '$ship_end_date' "; }
			
			if($ship_start_date != '' and $ship_end_date != ''){
			$sql.=" and d.ship_date BETWEEN '$ship_start_date' and '$ship_end_date' "; }
			
			if($product_id){
			$sql.=" and a.module_id = '$product_id' and a.module = 'work order' "; }
			
			$sql .= " order by created_date ASC";
			$result= $this->db->query($sql,__FILE__,__LINE__);
			//echo $sql;
			$total_rows=$this->db->num_rows($result);
   
 			$fields_cnt=$this->db->num_fields($result);
   
    
 
 
    $schema_insert = '';
 
    for ($i = 0; $i < $fields_cnt; $i++)
    {
        $l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,
            stripslashes($this->db->field_name($result, $i))) . $csv_enclosed;
        $schema_insert .= $l;
        $schema_insert .= $csv_separator;
    } // end for
 
    $out = trim(substr($schema_insert, 0, -1));
    $out .= $csv_terminated;
 
    // Format the data
    while ($row = $this->db->fetch_array($result))
    {
        $schema_insert = '';
        for ($j = 0; $j < $fields_cnt; $j++)
        {
            if ($row[$j] == '0' || $row[$j] != '')
            {
 
                if ($csv_enclosed == '')
                {
                    $schema_insert .= $row[$j];
                } else
                {
                    $schema_insert .= $csv_enclosed . 
					str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $row[$j]) . $csv_enclosed;
                }
            } else
            {
                $schema_insert .= '';
            }
 
            if ($j < $fields_cnt - 1)
            {
                $schema_insert .= $csv_separator;
            }
        } // end for
 
        $out .= $schema_insert;
        $out .= $csv_terminated;
    } // end while
 
    $filename="data.xls";
	header("Content-Disposition: attachment; filename=$filename");
    echo $out;
   	$sql_delete= "DROP TABLE testtest";
	$this->db->query($sql_delete,__FILE__,__LINE__);	
	exit;
?>
