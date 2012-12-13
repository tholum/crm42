<?php
$case = new cases();
$info = array();
$info['CreatedOn'] = date('Y-m-d H:i:s');
$info['CreatedBy'] = $_SESSION['user_id'];
$info['Owner'] = $_SESSION['user_id'];
$info['Status'] = 'Active';
$avalible_keys = array(
    'Status' => 'Status',
    'status' => 'Status',
    'group_id' => 'group_id',
    'module_name' => 'module_name',
    'module_id' => 'module_id',
    'subject' => 'subject',
    'contact_module_name' => 'contact_module_name',
    'contact_module_id' => 'contact_module_id',
    'CreatedOn' => 'CreatedOn',
    'CaseType' => 'CaseType',
    'CaseOrigin' => 'CaseOrigin',
    'Owner' => 'Owner',
    'createdon' => 'CreatedOn',
    'casetype' => 'CaseType',
    'caseorigin' => 'CaseOrigin',
    'owner' => 'Owner'
);
foreach($options as $n => $v){
    if(array_key_exists($n , $avalible_keys)){
        $info[$avalible_keys[$n]] = $v;
    }
}

$array['case'] = $case->create_case($info);
?>
