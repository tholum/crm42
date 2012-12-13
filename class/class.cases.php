<?php
class cases {
    var $db;
    var $page;
    var $emaildash;
    var $eapi_api;
        function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
                $this->page = new basic_page;
                $this->eapi_api = new eapi_api;
        }
        function set_page( $page ){
            
        }
        function create_case($info){
            $i = array();
            foreach($info as $n => $v ){
                $i[$this->db->clean_string($n)] = $this->db->clean_string($v);
            }
            $this->db->insert('cases', $i);
            $case_id = $this->db->last_insert_id();
            return $this->db->fetch_assoc($this->db->query("SELECT * FROM cases WHERE case_id = '$case_id'"));
        }
        function complete_case( $case_id ){
            $update=array();
            $update['Status'] = 'Completed';
            $this->db->update('cases' , $update , 'case_id' , $case_id);
        }
        function set_values( $case_id , $type , $options=array() ){
            $array = array('status' => 'error', 'data' => 'case type not found');
            $case_id = $this->db->clean_string($case_id);
            switch($type){
                case "contacts":
                    $u = array();
                    $u['contact_module_name'] = $this->db->clean_string($options['contact_module_name']);
                    $u['contact_module_id'] = $this->db->clean_string($options['contact_module_id']);
                    $this->db->update("cases" , $u , 'case_id' , $case_id);
                    $data = $this->db->fetch_assoc($this->db->query("SELECT * FROM cases WHERE case_id = '$case_id'"));
                    $array = array('status'=>'success','data'=>$data);
                break;
                case "user":
                    $u = array();
                    $u['Owner'] = $this->db->clean_string($options['user_id']);
                    $this->db->update("cases" , $u , 'case_id' , $case_id);
                    $data = $this->db->fetch_assoc($this->db->query("SELECT * FROM cases WHERE case_id = '$case_id'"));
                    $array = array('status'=>'success','data'=>$data);
                break; 
                case "subject":
                    $u = array();
                    $u['subject'] = $this->db->clean_string($options['subject']);
                    $this->db->update("cases" , $u , 'case_id' , $case_id);
                    $data = $this->db->fetch_assoc($this->db->query("SELECT * FROM cases WHERE case_id = '$case_id'"));
                    $array = array('status'=>'success','data'=>$data);               
                break;
                case "status":
                    $u = array();
                    $u['Status'] = $this->db->clean_string($options['status']);
                    $this->db->update("cases" , $u , 'case_id' , $case_id);
                    $data = $this->db->fetch_assoc($this->db->query("SELECT * FROM cases WHERE case_id = '$case_id'"));
                    $array = array('status'=>'success','data'=>$data); 
                break;
            }
            return $array;
        }
        function search_query( $object=array() , $overide=array()){
            $sql = "SELECT a.CreatedBy , a.Status ,  a.case_id , a.group_id , a.module_name , a.module_id , a.OrderNumber , a.CaseOrigin , a.Owner , a.CaseType , a.Priority , a.CreatedOn , a.contact_module_name  , a.contact_module_id , a.subject , b.company_name display_name FROM cases a LEFT JOIN contacts b ON a.contact_module_id = b.contact_id AND a.contact_module_name = 'CONTACTS' ";
            $where = array();
            foreach( $object as $name => $value ){
                $value=addslashes($value);
                switch( $name){
                    default:
                        if( $value != ''){
                            $where[] = " a.$name = '$value'";
                        }
                    break;
                    case "client_name":
                    break;
                    case "subject":
                        $where[] = " a.$name LIKE '%$value%'";
                    break;
                    case "client_id":
                        if( $value != ''){
                            $where[] = " a.contact_module_id = '$value' AND a.contact_module_name = 'CONTACTS'";
                        }
                    break;
                    case "CreatedOn_min":
                        if( $value != ''){
                            $where[] = " a.CreatedOn > '$value 00:00:00'";
                        }
                    break;
                    case "CreatedOn_max":
                        if( $value != ''){
                            $where[] = " a.CreatedOn <= '$value 23:59:59'";
                        }
                    break;
                    case "no_account":
                        if( $value == true ){
                            $where[] = "a.contact_module_id = '0'";
                        }
                    break;
                    case "search":
                        $valexp = explode( " " , $value );
                        foreach( $valexp as $v ){
                            $where[] = "( a.subject LIKE '%$v%' OR a.case_id LIKE '%$v%' OR b.company_name LIKE '%$v%' )";
                        }
                    break;
                
                    
                }
            }
            if( count( $where ) != 0){
                $sql .= "WHERE " . implode(" AND ", $where);
            }
            return $sql;
        }
        function display_search( $object =array() , $overide=array() , $pagify_overide=array()){
            $ob=array();
            $ob['Status'] = 'Active';
            foreach( $object as $n => $v ){
                $ob[$n] = $v;
            }
            $object = $ob;
            
            
            $options=array("per_page" => "30" , "table_id" => "case_search" , "auto_tablesort" => false);
            $options["user_id"] = "1";
            foreach($overide as $n => $v){
                $options[$n] = $v;
            }
            ob_start();
            //var_dump( $object );
            $sql = $this->search_query($object , $overide);
            
            //module_info
            $column_arr = array(
            "joined_columns" => 
                 array(
                    "module_info" => array("module_name" , "module_id","display_name")
                     
                 ) , 
            "copy_columns" =>
                array(
                    "contact_module_id" => "customer_num"
                )
                );
//            echo $sql;
            echo $this->page->pagify_query($sql , $column_arr ,$options);
            $html = ob_get_contents();
        ob_end_clean();
        if( $options["return_csv"] == true ){
            $array = array();
            $result = $this->db->query($sql);
            while( $row = $this->db->fetch_assoc($result)){
                $array[] = $row;
            }
//            var_dump($options);
            return $this->page->table_from_array($array, $column_arr , $options );
        }
        return $html;
        }
        
        function display_search_bar( ){
            $display = array();
            $display["client_name"] = array("name" => "Client Lookup" , "type" => "autocomplete" , "autocomplete_url" => "contact_lookup.php", "autocomplete_column" => "contact_id" , "search_column" => "client_id");           
            $display["Owner"] = array("name" => "Owner" , "type" => "autocomplete" , "autocomplete_url" => "user_lookup.php", "autocomplete_column" => "user_id" , "search_column" => "Owner");
            $display["CreatedOn"] = array( "name" => "Created" , "type" => "daterange");
            
//            $display["OrderNumber"] = array( "name" => "Order #" , "type" => "text" );
            $display["CaseOrigin"] = array( "name" => "Origin" , "type" => "dropdown_select" , "dropdown_name" => "Case Origin", 'rename_select' => 'ALL');
            $display["Status"] = array("name" => "Status" , "type" => "dropdown_select" , "dropdown_name" => "Case Status" , "selected" => 'Active' , 'rename_select' => 'ALL' );
            
            $display["case_id"] = array( "name" => "Case #" , "type" => "text"  );
            $display["CaseType"] = array("name" => "Case Type" , "type" => "dropdown_select" , "dropdown_name" => "Case Type" , 'rename_select' => 'ALL');
            $display['no_account'] = array("name" => "No Account" , "type" => "checkbox");
            
            $display['subject'] = array("name" => "Subject" , "type" => "text");
//            $display["Origin"] = array("name" => "Case Type" , "type" => "dropdown_select" , "dropdown_name" => "Case Type" , 'rename_select' => 'ALL');
            //$display["Owner"] = array( "name" => "SC Rep Owner #" , "type" => "text" );
            
//            $display["Priority"] = array("name" => "Priority" , "type" => "dropdown_select" , "dropdown_name" => "case_priority");
            $options = array();
            $options["run_on_update"] = "cases.display_search( #RESULTS# , { user_id: page_object.user_id} , { target: 'search_result' , onUpdate: function( root , response ){}} );";
            $options["table_id"] = "case_search";
            return $this->page->array_to_searchbar($display , $options);
            
        }
        
        
        function search_cases( $options , $overide=array() ){
            $query_options = array();
            $query_options["limit"] = '';
            $query_options["order_by"] = "CreatedOn";
            $query_options["order_dir"] = "DESC";
            $query_options['glue'] = " OR ";
            $query_options['prepend_where'] = '';
            $query_options['append_where'] = '';
            $query_options['where'] = '';
            $query_options['return_sql'] = false;
            $query_options['columns'] = array();
            //$options["page"] = 0;
             foreach( $overide as $n => $v){
                 $query_options[$n] = $v;
             }            
             $search_arr = array();
             foreach( $options as $n => $v ){
                 switch( $n ){
                     case "OrderNumber":
                     case "module_name":
                     case "module_id":
                         $search_arr[] = "`$n` = '$v'";
                     break;    
                 }
             }
             $where = $query_options['prepend_where'] . implode($query_options['glue'] , $search_arr) . $query_options['append_where'];
             $col = '';
             if( count($query_options['columns']) != 0 ){
                 $col = ' , ' . implode(' , ', $query_options['columns']);
             }
             
             $sql = "SELECT  subject , case_id , group_id , Status , CaseOrigin , CaseType ,   Owner  , OrderNumber ,  CreatedOn $col FROM cases WHERE " . $where ;
             if($query_options["order_by"] != ''){
                 $sql .= " ORDER BY `" . $query_options["order_by"] . "` ". $query_options["order_dir"] ." ";
             }             
             if( $query_options["limit"] != ''){
                 $sql .= " LIMIT 0 , " . $query_options["limit"] . " ";
             }
             if($query_options['return_sql'] == true ){
                 return $sql;
             }
             $result = $this->db->query($sql);
             $return = array();
             while( $row = $this->db->fetch_assoc($result)){
                 $return[] = $row;
             }
             //$return = array( array("sql" => $sql ));
             //$return[] = array("sql" => $sql);
             return $return;
         }
         function case_by_module( $module_name , $module_id , $overide=array()){
             $options["limit"] = '5';
             $options["followup"] = "true";
             foreach( $overide as $n => $v){
                 $options[$n] = $v;
             }
             $overide_options = array("limit" => $options["limit"], "glue" => " AND "  );
             $search_array=array("module_name" => $module_name , "module_id" => $module_id );
             if( $module_name == "eapi_ACCOUNT" || $module_name == "CONTACTS"){
                 $overide_options['prepend_where'] = '(';
                 $overide_options['append_where'] = ") OR ( `contact_module_name` = '$module_name' AND `contact_module_id` = '$module_id' )";
             }
//             $overide_options['columns'] = array('due_date');
             
             $cases = $this->search_cases( $search_array , $overide_options );
             ob_start();
             ?>
<hr class='case_line'>
<table style="width: 100%"><tr><td style="text-align: left;font-weight: bold;">Cases:</td><td style="text-align: right;" > <a onclick="casecreation.create_case_by_array({'module_name': '<?php echo $module_name;?>','module_id': '<?php echo $module_id; ?>'<?php if( $module_name == 'eapi_ACCOUNT'){ ?> ,'contact_module_name': '<?php echo $module_name;?>','contact_module_id': '<?php echo $module_id; ?>' <?php } ?> ,'Status': 'Active', 'Owner': '<?php echo $_SESSION['user_id']; ?>'} , 
                        {       
                            onUpdate: function(response,root){ $('#right_bottom_panel').html(response);}
                        });" ><button>create case<div class="add_button in_button" >&nbsp;</div></button></a></td></tr></table>
                        <div class="account_table order_screen_case_container">
                            <?php 
                            $small = 50;
                            $medium_small = 75;
                            $medium = 100;
                            $large = 200;
                            $options = array(
                                "column_options" => array(
                                    "Owner" => array( "name" => "Owner", "dataformat" => "username" , 'priority' => 60 ,  'width' => $medium ),
                                    "subject" => array( "name" => "Subject" , 'priority' => 60 ,  'width' => $large ),
                                    "CreatedOn" => array( "name" => "Created Dt", "dataformat" => "default_date" , 'priority' => 70 ,  'width' => $medium ),
                                    'group_id' => array('name' => 'Group #' , 'display_column' => false),
                                    'case_id' => array('name' => 'Case #' , 'dataformat' => 'case_link' ,  "sort" => "desc" , 'priority' => 10 , 'width' => $small ),
                                    'Status' => array('name' => 'Status' , 'priority' => 30 ,  'width' => $medium_small),
                                    'CaseOrigin' => array('name' => 'Origin' , 'priority' => 40 ,  'width' => $medium_small ),
                                    'CaseType' => array('name' => 'Case Type', 'priority' => 50 , 'width' => $medium),
                                    'OrderNumber' => array('name' => 'Order #', "dataformat" => "order_link", 'priority' => 20 , 'width' => $medium_small)
                                    
                                    
                                    ) 
                                );
                            // "add_class" => "case_search_table"
                            ?>
                           
                            <div class="case_by_module_container" >
                                
                                <?php
                            echo $this->page->table_from_array($cases , $options , array("add_class" => "case_search_table"));?>
                            
                        </div>
                        
              <?php   
                      $html = ob_get_contents();
        ob_end_clean();
        return $html; 
         }
         
         function case_by_order( $order_id ){
             $cases = $this->search_cases(array("OrderNumber" => $order_id ) );
             $order_info=json_decode($this->eapi_api->order_detail_lookup($order_id));
             ob_start();
             ?>
                        <div class="order_screen_case_container">
                            <div class="order_screen_case order_screen_case_title">Case <div class="case_icon">&nbsp;</div></div>
                            <?php 
                            $options = array(
                                "column_options" => array(
                                    "Owner" => array( "name" => "Owner", "dataformat" => "username" ,  'width' => '200' ),
                                    "CreatedOn" => array( "name" => "Created", "dataformat" => "default_date" , 'width' => '150' ),
                                    "case_id" => array('name' => "Case" , "dataformat" => "case_link", 'priority' => '3'),
                                    "group_id" => array('display_column' => false),
                                    "CaseOrigin" => array('display_column' => true,"name" => "Origin", 'priority' => '5'),
                                    "Status" => array('display_column' => true , "name" => "Status", 'priority' => '4'),
                                    "CaseType" => array('name' => 'Type' , 'width' => '150', 'priority' => '6'),
                                    "OrderNumber" => array('name' => 'Order #'),
                                    'subject' => array('name'=> 'Subject' , 'priority' => '15' ,  'width' => '200')
                                    
                                )  
                           );
                            echo $this->page->table_from_array($cases , $options, array('auto_tablesort' => false , 'autowidth' => true ));?>
                            <a onclick="casecreation.create_case_by_array({'OrderNumber': '<?php echo $order_id;?>','Status': 'Active', 'Owner': '<?php echo $_SESSION['user_id']; ?>' , 'contact_module_name': 'eapi_ACCOUNT' , 'contact_module_id' : '<?php echo $order_info->Account; ?>'} , 
                        {       
                            onUpdate: function(response,root){ $('#right_bottom_panel').html(response);slimcrm.flash_sidepanel();}
                        });" ><button>create case<div class="add_button in_button" >&nbsp;</div></button></a>
                        </div>
                        
              <?php   
                      $html = ob_get_contents();
        ob_end_clean();
        return $html; 
         }
}
?>
