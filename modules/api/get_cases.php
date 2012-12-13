<?php 
$case = new cases();
$search = array();
//I did it this way to allow alias's

$avalible_keys = array(
    'case_id' => 'case_id' , 
    'group_id' => 'group_id' , 
    'module_name' => 'module_name' , 
    'subject' => 'subject' , 
    'OrderNumber' => 'OrderNumber' , 
    'module_id' => 'module_id' , 
    'contact_module_id' => 'contact_module_id' , 
    'contact_module_name' => 'contact_module_name' , 
    'TicketNumber' => 'TicketNumber' , 
    'Title' => 'Title' , 
    'CreatedOn_min' => 'CreatedOn_min' , 
    'CreatedOn_max' => 'CreatedOn_max' , 
    'CaseType' => 'CaseType' , 
    'Priority' => 'Priority' , 
    'Owner' => 'Owner' , 
    'owner' => 'Owner' , 
    'Status' => 'Status' ,
    'status' => 'Status' ,
    'CaseOrigin' => 'CaseOrigin' , 
    'client_name' => 'client_name' , 
    'client_id' => 'client_id' , 
    'no_account' => 'no_account',
    'search' => 'search'
);


foreach($options as $n => $v){
    if(array_key_exists($n , $avalible_keys)){
        $search[$avalible_keys[$n]] = $v;
    }
    switch( $n ){
        case 'self':
            if( $v == 'true'){
                $search['Owner'] = $_SESSION['user_id'];
            }
        break;
    }
}

$sql = $case->search_query( $search );
$result = $this->db->query($sql);


while($row = $this->db->fetch_assoc($result)){
    $array[] = $row;
}

//$array=array('sql' => $sql);
?>