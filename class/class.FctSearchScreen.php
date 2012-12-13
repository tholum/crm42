<?php

class FctSearchScreen{

var $db;
var $ad;
var $company_id;
var $validity;
var $page;


	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
                $this->page = new basic_page();
	}
    function display_flowchart_task2( $search_object = array() , $pagify_overide = array()  ){
        ini_set('display_errors' , 1);
        $tmp = $search_object;
        $search_object = array();
        $search_object['department_id'] = '';
        
        foreach( $tmp as $n => $v){
            $search_object[$n] = $v;
        }
        $pagify_options=array();
        $pagify_options["table_id"] = "bucket_search";
        $pagify_options["user_id"] = $_SESSION['user_id'];
        $pagify_options["return_csv"] = false;
        
        foreach($pagify_overide as $n => $v){
            $pagify_options[$n] = $v;
        }
//        global_task_id
//        contact_module_id
         $overide = array("column_options" => 
                array(
                    "profile_page" => array( "display_column" => false ) ,
                    "tree_id"      => array( "display_column" => false ) ,
                    "chart_assign_id"      => array( "display_column" => false ) ,
                    "flow_chart_id"      => array( "display_column" => false ) ,
                    "created_date"      => array( "dataformat" => "default_date" , "name" => "Created On" , "width" => '50' ) ,
                    "completion_date"      => array( "display_column" => false ) ,
                    "completion_result"      => array( "display_column" => false ) ,
                    "module_id"      => array( "display_column" => false ) ,
                    "module"      => array( "display_column" => false ) ,
                    "status_id"      => array( "display_column" => false ) ,
                    "name"      => array( "display_column" => false ) ,
                    "owner_module_name"      => array( "display_column" => false ) ,
                    "owner_module_id"      => array( "display_column" => false ) ,
                    "projected_path_due_date"      => array( "display_column" => false ) ,
                    "due_date"     => array( "name" => "Due" , "dataformat" => "edit_bucket_date" , "width" => 50 ),
                    
                    "owner_module_info" => array( "name" => "For Who", "dataformat" => "module_display", "width" => 100  ),
                    "custom_display_name" => array("name" => "Customer", "width" => 100 ),
                    "email" => array("display_column"=>false),
                    "account_id" => array("display_column"=>false),
                    "case_info" => array("name" => "Case #" , "dataformat" => "case_by_module", "width" => 50 ),
                    "order_info" => array("name" => "Order ID" , "dataformat" => "order_by_module", "width" => 50 )
                    
                ),
               "joined_columns" => 
                 array(
                    "owner_module_info" => array("owner_module_name" , "owner_module_id" , "custom_display_name"),
                     "case_info" => array("module" , "module_id"),
                     "order_info" => array("module" , "module_id"),
                     "display_modulejoined" => array('module' , "module_id"),
                     "display_modulejoined2" => array('module' , "module_id")
                     
                 )
            );
        $search_array = array();
        foreach( $search_object as $n => $val ){
            $search_array[$n] = $val;
        }      
        
        $result = $this->db->query("SELECT a.name , a.global_task_id , b.global_task_tree_name FROM tbl_global_task a LEFT JOIN tbl_global_task_tree b ON a.global_task_tree_id = b.global_task_tree_id");
       $search_overide = array(); 
       $search_overide["extra_joins"] = ' LEFT JOIN cases d ON a.module = \'cases\' AND a.module_id = d.case_id LEFT JOIN eapi_account_displayname c ON d.contact_module_name =\'eapi_ACCOUNT\'  AND d.contact_module_id = c.account_id ';
       $search_overide["extra_columns"] = ", c.display_name custom_display_name ,CASE
WHEN a.due_date < NOW() THEN 'yes'
ELSE 'no'
END as overdue";
       $search_overide["return_sql"] = "yes";
        $return = '';
        $tmp_gtid = $search_array['global_task_id'];
        if( $pagify_options["return_csv"] == true ){
            $search = $this->search_flow_chart_tasks( $search_array , $search_overide );
//            echo $search . "<br/>\n";
            $array = array();
            $result = $this->db->query($search);
            while( $row = $this->db->fetch_assoc($result)){
                $array[] = $row;
            }
            return $this->page->table_from_array($array, $overide , $pagify_options );
        }
        if( array_key_exists('global_task_id',$search_array) == true ){
                    if($search_array['global_task_id'] != '' ){
                        $pagify_options['return_no_data'] = false;
                    }
                    
                }
        while( $row = $this->db->fetch_array($result)){
            if( $tmp_gtid == '' || $row["global_task_id"] == $tmp_gtid ){
                $search_array['global_task_id'] = $row["global_task_id"];

                $search = $this->search_flow_chart_tasks( $search_array , $search_overide );
               // echo $search;
//                $return .= $search;
                //$return .= $search . "<br/>";
               // echo $search;
                //$pagify_options['per_page'] = '3';
                $pagify_options['auto_tablesort'] = 'false';
                $pagify_options['pre_sort'] = '';
                $pagify_options['add_class'] = 'bucket_search_%overdue%_%task_status%';
                $pagify_options["pre_number_html"] = "<div style='float: left;' class='bucket_search_name_title' >" . $row["global_task_tree_name"] . ": " . $row["name"] . "</div>";
                
                $data = $this->page->pagify_query($search, $overide , $pagify_options );
                if( $data != "no data" ){
                    
                // $return .= "<div class='bucket_search_name_title' >" . $row["global_task_tree_name"] . ": " . $row["name"] . "</div>";
                    $return .= "<div class='bucket_search_table' id='bucket_search_table_" .$row["global_task_id"] . "'> ";
                    $return .= $data;//$this->page->table_from_array($search , $overide );
                    $return .= "</div>";
                }
            }
                   
        }
         
        return $return;
    }
        
    function display_search_bar( ){
            $display = array();
            $display["client_id"] = array("name" => "Client Lookup" , "type" => "autocomplete" , "autocomplete_url" => "account_lookup.php", "autocomplete_column" => "Id" , "search_column" => "contact_module_id");
//            $display["Owner"] = array("name" => "Owner" , "type" => "autocomplete" , "autocomplete_url" => "user_lookup.php", "autocomplete_column" => "user_id" , "search_column" => "Owner");
            $display["Owner"] = array("name" => "Owner" , "type" => "autocomplete" , "autocomplete_url" => "user_and_group_lookup.php", "autocomplete_column" => "module_id" , "search_column" => "owner_module_id" , "autocomplete_column2" => "module_name" , "search_column2" => "owner_module_name");
//            $display["due_date"] = array( "name" => "Due Date" , "type" => "daterange");
            $display["due_date"] = array( "name" => "Due Date" , "type" => "daterange");
//            $display["order_id"] = array( "name" => "Order #" , "type" => "text" );
//            $display["CaseOrigin"] = array( "name" => "Origin" , "type" => "text");
            
//            $display["module_id"] = array( "name" => "Case #" , "type" => "text");
//            $display["CaseType"] = array( "name" => "Case Type" , "type" => "text");
            $display["department_id"] = array( "name" => "Department" , "type" => "dropdown_sql" , "sql" => "SELECT group_id value , group_name name FROM `tbl_usergroup` ORDER BY name ASC" , 'rename_select' => 'ALL');
            $display["bucket_name"] = array("name" => "Bucket Name" , "type" => "autocomplete" , "autocomplete_url" => "fct_lookup.php", "autocomplete_column" => "global_task_id" , "search_column" => "global_task_id");
            $display["CreatedOn"] = array( "name" => "Created" , "type" => "daterange");
            $display["task_status"] = array("name" => "Status" , "type" => "dropdown_select" , "dropdown_name" => "bucket_status", 'rename_select' => 'ALL' , 'selected' => 'Active');
            $display["optional1"] = array("name" => "Location" , "type" => "dropdown_sql" , "sql" => "SELECT DISTINCT optional1 value , optional1 name FROM assign_flow_chart_task WHERE optional1 <> ''", 'rename_select' => 'ALL' );
            $display['raw'] = array('name' => 'Commands' , 'type'=> 'rawcode' , 'code' => '<a onclick="refresh_search();" ><button>Refresh</button></a><a onclick="a_default_search=new Object();slimcrm.bucket=a_default_search;$(\'#bucket_search_button\').click();" ><button>Reset</button></a>');
            
            
            $options = array();
            $options["run_on_update"] = "fctsearch.display_flowchart_task2( #RESULTS# , { user_id: page_object.user_id} , { target: 'search_result' } );slimcrm.bucket=a_default_search;";
            $options["table_id"] = "bucket_search";
            return $this->page->array_to_searchbar($display , $options);
            
        }
        
    function search_flowchart_tasks2(){
         
        $form_name = "bucket_search";
        $js_object = "$form_name" . "_search_object";
        $js_sfunc = $form_name . "search_function";
        ob_start();
        ?>
            <script>
                var <?php echo $js_object;?> = new Object;
                function refresh_search(){
                    fctsearch.display_flowchart_task2( <?php echo $js_object;?> , { target:'bucket_search' , onUpdate: function(response,root){
                                   
                                   bucket_searchscreen(); 
                            }} );
                        
                }
                function <?php echo $js_sfunc; ?>( option_name , option_value ){
                    <?php echo $js_object; ?>[ option_name ] = option_value;
                            fctsearch.display_flowchart_task2( <?php echo $js_object;?> , { target:'bucket_search' , onUpdate: function(response,root){
                                  bucket_searchscreen(); 
                                    
                            }} );
                        
                }
                function bucket_searchscreen(){
                    start_cal();
                    end_cal();
                }
                
            </script>
            <div class="search_screen_container bucket_search_screen_container" >
            <table >

                <tr>
                    <td><span class="search_text bucket_search_screen" >Bucket Name</span></td>
                    <td><input onchange="<?php echo $js_sfunc; ?>( 'name' , this.value );" name="bucket_name" id="bucket_name" type="text" class="search_screen_input"></td>
                    <td><span class="search_text bucket_search_screen" >Client #</span></td>
                    <td>
                        <input disabled onchange="<?php echo $js_sfunc; ?>( 'client_number' , this.value );" name="bucket_client_num" id="bucket_client_num" type="text" class="search_screen_input">
                    </td>
                    <td><span class="search_text bucket_search_screen" >Created from</span></td>
                    <td>
                        <input type="text" name="created_after_date" id="created_after_date" class="searchscreen_date" value="<?php //echo $row_order['event_date']; ?>"/>
						 <script type="text/javascript">	 
						 function start_cal(){
						 new Calendar({
						 inputField   	: "created_after_date",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "created_after_date",
						 weekNumbers   	: true,
						 bottomBar		: true,				 
						 onSelect		: function() {
									this.hide();
									document.getElementById('created_after_date').value=this.selection.print("%Y-%m-%d");
                                                                        <?php echo $js_sfunc; ?>( 'created_after_date' , document.getElementById('created_after_date').value );
													
										  } });			
							}
							start_cal();
							</script>
                    </td>
                    <td><span class="search_text bucket_search_screen" > to </span></td>
                    <td>
                        <input type="text" name="created_befor_date" id="created_befor_date" class="searchscreen_date" value="<?php //echo $row_order['event_date']; ?>"/>
						 <script type="text/javascript">	 
						 function end_cal(){
						 new Calendar({
						 inputField   	: "created_befor_date",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "created_befor_date",
						 weekNumbers   	: true,
						 bottomBar		: true,				 
						 onSelect		: function() {
									this.hide();
									document.getElementById('created_befor_date').value=this.selection.print("%Y-%m-%d");
                                                                        <?php echo $js_sfunc; ?>( 'created_befor_date' , document.getElementById('created_befor_date').value );
													
										  } });			
							}
							end_cal();
							</script>
                                                        
                    </td>
    
                   </tr>
                   <tr>
                       <td><span class="search_text bucket_search_screen" >Department</span></td><td>
                                                  <?php /*TODO: REPLACE WITH A FUNCTION */ ?>
                        <select onchange="<?php echo $js_sfunc; ?>( 'owner_module' , this.value );">
                            <option value="">ALL</option>
                            <?php 
                            $result = $this->db->query("SELECT user_id,first_name,last_name FROM tbl_user");
                            while( $row = $this->db->fetch_assoc($result)){
                                ?>
                            <option value="TBL_USER:|:<?php echo $row["user_id"]; ?>"><?php echo $row["first_name"] . " " . $row["last_name"]; ?></option>
                                <?php
                            }
                            $result = $this->db->query("SELECT group_id,group_name FROM tbl_usergroup");
                            while( $row = $this->db->fetch_assoc($result)){
                                ?>
                            <option value="TBL_GROUP:|:<?php echo $row["group_id"]; ?>"><?php echo $row["group_name"]; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                       </td>
                       <td><span class="search_text bucket_search_screen" >Client name</span></td>
                       <td><input onchange="<?php echo $js_sfunc; ?>( 'client_name' , this.value );" name="bucket_client_name" id="bucket_client_name" type="text" class="search_screen_input"></td>
                       <td><span class="search_text bucket_search_screen" >Status</span></td><td colspan="3" >
                           <select onchange="<?php echo $js_sfunc; ?>( 'task_status' , this.value );">
                            <?php echo $this->page->get_dropdown_options( "Case Status"); ?>
                        </select></td>                       
                   </tr>
                   <tr>
                       <td><span class="search_text bucket_search_screen" >Phone #</span></td>
                       <td><input  onchange="<?php echo $js_sfunc; ?>( 'client_phone_number' , this.value );" name="bucket_client_phone" id="bucket_client_phone" type="text" class="search_screen_input"></td>
                       <td><span class="search_text bucket_search_screen" >CS rep Owner</span></td>
                       <td><input  onchange="<?php echo $js_sfunc; ?>( 'cs_rep_owner' , this.value );" name="cs_rep_owner" id="cs_rep_owner" type="text" class="search_screen_input" value="??"></td>
                       <td><span class="search_text bucket_search_screen" >Catagory</span></td><td colspan="3">??</td>
                   </tr>
                   <tr>
                       <td><span class="search_text bucket_search_screen" >Order #</span></td>
                       <td><input  onchange="<?php echo $js_sfunc; ?>( 'order_number' , this.value );" name="order_number" id="order_number" type="text" class="search_screen_input" ></td>
                       <td><span class="search_text bucket_search_screen" >Priority</span></td><td>??</td>
                       <td><span class="search_text bucket_search_screen" ></span></td><td colspan="3"></td>
                   </tr>
            </table>
            </div>
        <?php
        $html = ob_get_contents();
		ob_end_clean();
		return $html;
    }    
        
    function SearchFct() {
       ob_start();
	   $formName = "frm_search";?>
 	   <form name="<?php echo $formName;?>" method="post" action="">
		 <table width="100%" class="table" >
			<tr>
			   <td>Order</td>
			   <td width="13%">
			        <input type="text" name="order_txt" id="order_txt" 
							onchange="javascript:fctsearch.showFctSearch(
											document.getElementById('order_txt').value,
											document.getElementById('customer_txt').value,
											document.getElementById('csr_type').value,
											document.getElementById('evntstart_date').value,
											document.getElementById('evntend_date').value,
											document.getElementById('fct_name').value,
											document.getElementById('creat_start_date').value,
											document.getElementById('creat_end_date').value,
											document.getElementById('dep_name').value,
											document.getElementById('ship_start_date').value,
											document.getElementById('ship_end_date').value,
											document.getElementById('product_type').value,
											document.getElementById('duestart_date').value,
											document.getElementById('dueend_date').value,
											document.getElementById('Status').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
												);"/>
			   </td>
			   <td>Customer</td>
			   <td width="13%">
					<input type="text" name="customer_txt" id="customer_txt" 
							onchange="javascript:fctsearch.showFctSearch(
											document.getElementById('order_txt').value,
											document.getElementById('customer_txt').value,
											document.getElementById('csr_type').value,
											document.getElementById('evntstart_date').value,
											document.getElementById('evntend_date').value,
											document.getElementById('fct_name').value,
											document.getElementById('creat_start_date').value,
											document.getElementById('creat_end_date').value,
											document.getElementById('dep_name').value,
											document.getElementById('ship_start_date').value,
											document.getElementById('ship_end_date').value,
											document.getElementById('product_type').value,
											document.getElementById('duestart_date').value,
											document.getElementById('dueend_date').value,
											document.getElementById('Status').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
												);"/>
				</td>
				<td>CSR</td>
				<td width="13%">
				     <select style="width:100%;" name="csr_type" id="csr_type" 
				                onchange="javascript:fctsearch.showFctSearch(
											document.getElementById('order_txt').value,
											document.getElementById('customer_txt').value,
											document.getElementById('csr_type').value,
											document.getElementById('evntstart_date').value,
											document.getElementById('evntend_date').value,
											document.getElementById('fct_name').value,
											document.getElementById('creat_start_date').value,
											document.getElementById('creat_end_date').value,
											document.getElementById('dep_name').value,
											document.getElementById('ship_start_date').value,
											document.getElementById('ship_end_date').value,
											document.getElementById('product_type').value,
											document.getElementById('duestart_date').value,
											document.getElementById('dueend_date').value,
											document.getElementById('Status').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
												);">
						  <option value="">--Select--</option>
						  <?php $sql_csr = "Select * from ".TBL_USER;
						  $result_csr = $this->db->query($sql_csr,__FILE__,__LINE__);		
						  while($row_csr=$this->db->fetch_array($result_csr)){?>
						  <option value="<?php echo $row_csr[user_id];?>">
						  <?php echo $row_csr[first_name].' '.$row_csr[last_name];?>
						  </option>
						  <?php } ?>
				     </select>
				</td>
			    <td>Event Date</td>
				<td width="13%">
				   <input type="text" name="evntstart_date" id="evntstart_date" value="<?php //echo $row_order['event_date']; ?>"/>
						 <script type="text/javascript">	 
						 function start_cal(){
						 new Calendar({
						 inputField   	: "evntstart_date",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "evntstart_date",
						 weekNumbers   	: true,
						 bottomBar		: true,				 
						 onSelect		: function() {
												this.hide();
													document.getElementById('evntstart_date').value=this.selection.print("%Y-%m-%d");
													fctsearch.showFctSearch(
																document.getElementById('order_txt').value,
																document.getElementById('customer_txt').value,
																document.getElementById('csr_type').value,
																document.getElementById('evntstart_date').value,
																document.getElementById('evntend_date').value,
																document.getElementById('fct_name').value,
																document.getElementById('creat_start_date').value,
																document.getElementById('creat_end_date').value,
																document.getElementById('dep_name').value,
																document.getElementById('ship_start_date').value,
																document.getElementById('ship_end_date').value,
																document.getElementById('product_type').value,
																document.getElementById('duestart_date').value,
											                    document.getElementById('dueend_date').value,
																document.getElementById('Status').value,
														{preloader:'prl',onUpdate: function(response,root){
														document.getElementById('task_area').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] }) }});												
														}
										   });			
							}
							start_cal();
							</script>
				</td>
				<td>
				   <a href="javascript:void(0);" 
				      onclick="javacsript:document.getElementById('evntstart_date').value = '';
					  					  fctsearch.showFctSearch(
											document.getElementById('order_txt').value,
											document.getElementById('customer_txt').value,
											document.getElementById('csr_type').value,
											document.getElementById('evntstart_date').value,
											document.getElementById('evntend_date').value,
											document.getElementById('fct_name').value,
											document.getElementById('creat_start_date').value,
											document.getElementById('creat_end_date').value,
											document.getElementById('dep_name').value,
											document.getElementById('ship_start_date').value,
											document.getElementById('ship_end_date').value,
											document.getElementById('product_type').value,
											document.getElementById('duestart_date').value,
											document.getElementById('dueend_date').value,
											document.getElementById('Status').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
												);"> <img src="images/trash.gif" border="0" /></a> to </td>
				<td width="13%">
				   <input type="text" name="evntend_date" id="evntend_date" value="<?php //echo $row_order['event_date']; ?>"/>
						 <script type="text/javascript">	 
						 function start_cal(){
						 new Calendar({
						 inputField   	: "evntend_date",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "evntend_date",
						 weekNumbers   	: true,
						 bottomBar		: true,				 
						 onSelect		: function() {
												this.hide();
													document.getElementById('evntend_date').value=this.selection.print("%Y-%m-%d");
													fctsearch.showFctSearch(
																document.getElementById('order_txt').value,
																document.getElementById('customer_txt').value,
																document.getElementById('csr_type').value,
																document.getElementById('evntstart_date').value,
																document.getElementById('evntend_date').value,
																document.getElementById('fct_name').value,
																document.getElementById('creat_start_date').value,
																document.getElementById('creat_end_date').value,
																document.getElementById('dep_name').value,
																document.getElementById('ship_start_date').value,
																document.getElementById('ship_end_date').value,
																document.getElementById('product_type').value,
																document.getElementById('duestart_date').value,
											                    document.getElementById('dueend_date').value,
																document.getElementById('Status').value,
														{preloader:'prl',onUpdate: function(response,root){
														document.getElementById('task_area').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] }) }});												
														}
										   });			
							}
							start_cal();
							</script>
				</td>
				<td>
				   <a href="javascript:void(0);" 
				      onclick="javacsript:document.getElementById('evntend_date').value = '';
					  					  fctsearch.showFctSearch(
											document.getElementById('order_txt').value,
											document.getElementById('customer_txt').value,
											document.getElementById('csr_type').value,
											document.getElementById('evntstart_date').value,
											document.getElementById('evntend_date').value,
											document.getElementById('fct_name').value,
											document.getElementById('creat_start_date').value,
											document.getElementById('creat_end_date').value,
											document.getElementById('dep_name').value,
											document.getElementById('ship_start_date').value,
											document.getElementById('ship_end_date').value,
											document.getElementById('product_type').value,
											document.getElementById('duestart_date').value,
											document.getElementById('dueend_date').value,
											document.getElementById('Status').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
												);"> <img src="images/trash.gif" border="0" /></a>
				</td>
			 </tr>
			 <tr>
			    <td colspan="4">&nbsp;</td>
				<td>FCT</td>
			    <td>
				<?php
				  $sql_fct = " Select * from ".GLOBAL_TASK;
				  $result_fct = $this->db->query($sql_fct,__FILE__,__LINE__);
				?>
				   <select style="width:100%;" name="fct_name" id="fct_name" 
				                onchange="javascript:fctsearch.showFctSearch(
											document.getElementById('order_txt').value,
											document.getElementById('customer_txt').value,
											document.getElementById('csr_type').value,
											document.getElementById('evntstart_date').value,
											document.getElementById('evntend_date').value,
											document.getElementById('fct_name').value,
											document.getElementById('creat_start_date').value,
											document.getElementById('creat_end_date').value,
											document.getElementById('dep_name').value,
											document.getElementById('ship_start_date').value,
											document.getElementById('ship_end_date').value,
											document.getElementById('product_type').value,
											document.getElementById('duestart_date').value,
											document.getElementById('dueend_date').value,
											document.getElementById('Status').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
												);">
						  <option value="">--Select--</option>
						  <?php while($row_fct = $this->db->fetch_array($result_fct)){ ?> 
						  <option value="<?php  echo $row_fct[global_task_id];?>"><?php  echo $row_fct[name];?></option>
						  <?php } ?>
				</select>
				</td>
				<td>Created Date</td>
			    <td>
				   <input type="text" name="creat_start_date" id="creat_start_date" value="<?php //echo $row_order['event_date']; ?>"/>
						 <script type="text/javascript">	 
						 function start_cal(){
						 new Calendar({
						 inputField   	: "creat_start_date",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "creat_start_date",
						 weekNumbers   	: true,
						 bottomBar		: true,				 
						 onSelect		: function() {
												this.hide();
													document.getElementById('creat_start_date').value=this.selection.print("%Y-%m-%d");
													fctsearch.showFctSearch(
																document.getElementById('order_txt').value,
																document.getElementById('customer_txt').value,
																document.getElementById('csr_type').value,
																document.getElementById('evntstart_date').value,
																document.getElementById('evntend_date').value,
																document.getElementById('fct_name').value,
																document.getElementById('creat_start_date').value,
																document.getElementById('creat_end_date').value,
																document.getElementById('dep_name').value,
																document.getElementById('ship_start_date').value,
																document.getElementById('ship_end_date').value,
																document.getElementById('product_type').value,
																document.getElementById('duestart_date').value,
											                    document.getElementById('dueend_date').value,
																document.getElementById('Status').value,
														{preloader:'prl',onUpdate: function(response,root){
														document.getElementById('task_area').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] }) }});												
														}
										   });			
							}
							start_cal();
							</script>
				</td>
				<td>
				   <a href="javascript:void(0);" 
				      onclick="javacsript:document.getElementById('creat_start_date').value = '';
					  					  fctsearch.showFctSearch(
											document.getElementById('order_txt').value,
											document.getElementById('customer_txt').value,
											document.getElementById('csr_type').value,
											document.getElementById('evntstart_date').value,
											document.getElementById('evntend_date').value,
											document.getElementById('fct_name').value,
											document.getElementById('creat_start_date').value,
											document.getElementById('creat_end_date').value,
											document.getElementById('dep_name').value,
											document.getElementById('ship_start_date').value,
											document.getElementById('ship_end_date').value,
											document.getElementById('product_type').value,
											document.getElementById('duestart_date').value,
											document.getElementById('dueend_date').value,
											document.getElementById('Status').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
												);"> <img src="images/trash.gif" border="0" /></a> to </td>
				<td>
				   <input type="text" name="creat_end_date" id="creat_end_date" value="<?php //echo $row_order['event_date']; ?>"/>
						 <script type="text/javascript">	 
						 function start_cal(){
						 new Calendar({
						 inputField   	: "creat_end_date",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "creat_end_date",
						 weekNumbers   	: true,
						 bottomBar		: true,				 
						 onSelect		: function() {
												this.hide();
													document.getElementById('creat_end_date').value=this.selection.print("%Y-%m-%d");
													fctsearch.showFctSearch(
																document.getElementById('order_txt').value,
																document.getElementById('customer_txt').value,
																document.getElementById('csr_type').value,
																document.getElementById('evntstart_date').value,
																document.getElementById('evntend_date').value,
																document.getElementById('fct_name').value,
																document.getElementById('creat_start_date').value,
																document.getElementById('creat_end_date').value,
																document.getElementById('dep_name').value,
																document.getElementById('ship_start_date').value,
																document.getElementById('ship_end_date').value,
																document.getElementById('product_type').value,
																document.getElementById('duestart_date').value,
											                    document.getElementById('dueend_date').value,
																document.getElementById('Status').value,
														{preloader:'prl',onUpdate: function(response,root){
														document.getElementById('task_area').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] }) }});												
														}
										   });			
							}
							start_cal();
							</script>
				</td>
				<td>
				   <a href="javascript:void(0);" 
				      onclick="javacsript:document.getElementById('creat_end_date').value = '';
					  					  fctsearch.showFctSearch(
											document.getElementById('order_txt').value,
											document.getElementById('customer_txt').value,
											document.getElementById('csr_type').value,
											document.getElementById('evntstart_date').value,
											document.getElementById('evntend_date').value,
											document.getElementById('fct_name').value,
											document.getElementById('creat_start_date').value,
											document.getElementById('creat_end_date').value,
											document.getElementById('dep_name').value,
											document.getElementById('ship_start_date').value,
											document.getElementById('ship_end_date').value,
											document.getElementById('product_type').value,
											document.getElementById('duestart_date').value,
											document.getElementById('dueend_date').value,
											document.getElementById('Status').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
												);"> <img src="images/trash.gif" border="0" /></a>
				</td>
			 </tr>
			 <tr>
			   <td colspan="4">&nbsp;</td>
			   <td>Dept</td>
			    <td>
				<?php
				  $sql_dep = " Select * from tbl_usergroup ";
				  $result_dep = $this->db->query($sql_dep,__FILE__,__LINE__);
				?>
				   <select style="width:100%;" name="dep_name" id="dep_name" 
				                onchange="javascript:fctsearch.showFctSearch(
											document.getElementById('order_txt').value,
											document.getElementById('customer_txt').value,
											document.getElementById('csr_type').value,
											document.getElementById('evntstart_date').value,
											document.getElementById('evntend_date').value,
											document.getElementById('fct_name').value,
											document.getElementById('creat_start_date').value,
											document.getElementById('creat_end_date').value,
											document.getElementById('dep_name').value,
											document.getElementById('ship_start_date').value,
											document.getElementById('ship_end_date').value,
											document.getElementById('product_type').value,
											document.getElementById('duestart_date').value,
											document.getElementById('dueend_date').value,
											document.getElementById('Status').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
												);">
				          <option value="">--Select--</option>
						  <?php while($row_dep = $this->db->fetch_array($result_dep)){ ?> 
						  <option value="<?php  echo $row_dep[group_id];?>"><?php  echo $row_dep[group_name];?></option>
						  <?php } ?>
				</select>
				</td>
				<td>Ship Date</td>
			   <td>
				  <input type="text" name="ship_start_date" id="ship_start_date" value="<?php //echo $row_order['event_date']; ?>"/>
						 <script type="text/javascript">	 
						 function start_cal(){
						 new Calendar({
						 inputField   	: "ship_start_date",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "ship_start_date",
						 weekNumbers   	: true,
						 bottomBar		: true,				 
						 onSelect		: function() {
												this.hide();
													document.getElementById('ship_start_date').value=this.selection.print("%Y-%m-%d");
													fctsearch.showFctSearch(
																document.getElementById('order_txt').value,
																document.getElementById('customer_txt').value,
																document.getElementById('csr_type').value,
																document.getElementById('evntstart_date').value,
																document.getElementById('evntend_date').value,
																document.getElementById('fct_name').value,
																document.getElementById('creat_start_date').value,
																document.getElementById('creat_end_date').value,
																document.getElementById('dep_name').value,
																document.getElementById('ship_start_date').value,
																document.getElementById('ship_end_date').value,
																document.getElementById('product_type').value,
																document.getElementById('duestart_date').value,
											                    document.getElementById('dueend_date').value,
																document.getElementById('Status').value,
														{preloader:'prl',onUpdate: function(response,root){
														document.getElementById('task_area').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] }) }});												
														}
										   });			
							}
							start_cal();
							</script>
			   </td>
			   <td>
			      <a href="javascript:void(0);" 
				      onclick="javacsript:document.getElementById('ship_start_date').value = '';
					  					  fctsearch.showFctSearch(
											document.getElementById('order_txt').value,
											document.getElementById('customer_txt').value,
											document.getElementById('csr_type').value,
											document.getElementById('evntstart_date').value,
											document.getElementById('evntend_date').value,
											document.getElementById('fct_name').value,
											document.getElementById('creat_start_date').value,
											document.getElementById('creat_end_date').value,
											document.getElementById('dep_name').value,
											document.getElementById('ship_start_date').value,
											document.getElementById('ship_end_date').value,
											document.getElementById('product_type').value,
											document.getElementById('duestart_date').value,
											document.getElementById('dueend_date').value,
											document.getElementById('Status').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
												);"> <img src="images/trash.gif" border="0" /></a> to </td>
			   <td>
			     <input type="text" name="ship_end_date" id="ship_end_date" value="<?php //echo $row_order['event_date']; ?>"/>
						 <script type="text/javascript">	 
						 function start_cal(){
						 new Calendar({
						 inputField   	: "ship_end_date",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "ship_end_date",
						 weekNumbers   	: true,
						 bottomBar		: true,				 
						 onSelect		: function() {
												this.hide();
													document.getElementById('ship_end_date').value=this.selection.print("%Y-%m-%d");
													fctsearch.showFctSearch(
																document.getElementById('order_txt').value,
																document.getElementById('customer_txt').value,
																document.getElementById('csr_type').value,
																document.getElementById('evntstart_date').value,
																document.getElementById('evntend_date').value,
																document.getElementById('fct_name').value,
																document.getElementById('creat_start_date').value,
																document.getElementById('creat_end_date').value,
																document.getElementById('dep_name').value,
																document.getElementById('ship_start_date').value,
																document.getElementById('ship_end_date').value,
																document.getElementById('product_type').value,
																document.getElementById('duestart_date').value,
											                    document.getElementById('dueend_date').value,
																document.getElementById('Status').value,
														{preloader:'prl',onUpdate: function(response,root){
														document.getElementById('task_area').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] }) }});												
														}
										   });			
							}
							start_cal();
							</script>
			   </td>
			   <td>
			      <a href="javascript:void(0);" 
				      onclick="javacsript:document.getElementById('ship_end_date').value = '';
					  					  fctsearch.showFctSearch(
											document.getElementById('order_txt').value,
											document.getElementById('customer_txt').value,
											document.getElementById('csr_type').value,
											document.getElementById('evntstart_date').value,
											document.getElementById('evntend_date').value,
											document.getElementById('fct_name').value,
											document.getElementById('creat_start_date').value,
											document.getElementById('creat_end_date').value,
											document.getElementById('dep_name').value,
											document.getElementById('ship_start_date').value,
											document.getElementById('ship_end_date').value,
											document.getElementById('product_type').value,
											document.getElementById('duestart_date').value,
											document.getElementById('dueend_date').value,
											document.getElementById('Status').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
												);"> <img src="images/trash.gif" border="0" /></a></td>
			 </tr>
			 <tr>
			   <td colspan="4">&nbsp;</td>
			   <td>Product</td>
			    <td>
				<?php
				  $sql_pro = " Select * from ".erp_PRODUCT;
				  $result_pro = $this->db->query($sql_pro,__FILE__,__LINE__);
				?>
				   <select style="width:100%;" name="product_type" id="product_type" 
				                onchange="javascript:fctsearch.showFctSearch(
											document.getElementById('order_txt').value,
											document.getElementById('customer_txt').value,
											document.getElementById('csr_type').value,
											document.getElementById('evntstart_date').value,
											document.getElementById('evntend_date').value,
											document.getElementById('fct_name').value,
											document.getElementById('creat_start_date').value,
											document.getElementById('creat_end_date').value,
											document.getElementById('dep_name').value,
											document.getElementById('ship_start_date').value,
											document.getElementById('ship_end_date').value,
											document.getElementById('product_type').value,
											document.getElementById('duestart_date').value,
											document.getElementById('dueend_date').value,
											document.getElementById('Status').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
												);">
						  <option value="">--Select--</option>
						  <?php while($row_pro = $this->db->fetch_array($result_pro)){ ?> 
						  <option value="<?php  echo $row_pro[product_id];?>"><?php  echo $row_pro[product_name];?></option>
						  <?php } ?>
				</select>
				</td>
				<td>Due Date</td>
				<td width="13%">
				   <input type="text" name="duestart_date" id="duestart_date" value="<?php //echo $row_order['event_date']; ?>"/>
						 <script type="text/javascript">	 
						 function start_cal(){
						 new Calendar({
						 inputField   	: "duestart_date",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "duestart_date",
						 weekNumbers   	: true,
						 bottomBar		: true,				 
						 onSelect		: function() {
												this.hide();
													document.getElementById('duestart_date').value=this.selection.print("%Y-%m-%d");
													fctsearch.showFctSearch(
																document.getElementById('order_txt').value,
																document.getElementById('customer_txt').value,
																document.getElementById('csr_type').value,
																document.getElementById('evntstart_date').value,
																document.getElementById('evntend_date').value,
																document.getElementById('fct_name').value,
																document.getElementById('creat_start_date').value,
																document.getElementById('creat_end_date').value,
																document.getElementById('dep_name').value,
																document.getElementById('ship_start_date').value,
																document.getElementById('ship_end_date').value,
																document.getElementById('product_type').value,
																document.getElementById('duestart_date').value,
											                    document.getElementById('dueend_date').value,
																document.getElementById('Status').value,
													
														{preloader:'prl',onUpdate: function(response,root){
														document.getElementById('task_area').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] }) }});												
														}
										   });			
							}
							start_cal();
							</script>
				</td>
				<td>
				   <a href="javascript:void(0);" 
				      onclick="javacsript:document.getElementById('duestart_date').value = '';
					  					  fctsearch.showFctSearch(
											document.getElementById('order_txt').value,
											document.getElementById('customer_txt').value,
											document.getElementById('csr_type').value,
											document.getElementById('evntstart_date').value,
											document.getElementById('evntend_date').value,
											document.getElementById('fct_name').value,
											document.getElementById('creat_start_date').value,
											document.getElementById('creat_end_date').value,
											document.getElementById('dep_name').value,
											document.getElementById('ship_start_date').value,
											document.getElementById('ship_end_date').value,
											document.getElementById('product_type').value,
											document.getElementById('duestart_date').value,
											document.getElementById('dueend_date').value,
											document.getElementById('Status').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
												);"> <img src="images/trash.gif" border="0" /></a> to </td>
				<td width="13%">
				   <input type="text" name="dueend_date" id="dueend_date" value="<?php //echo $row_order['event_date']; ?>"/>
						 <script type="text/javascript">	 
						 function start_cal(){
						 new Calendar({
						 inputField   	: "dueend_date",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "dueend_date",
						 weekNumbers   	: true,
						 bottomBar		: true,				 
						 onSelect		: function() {
												this.hide();
													document.getElementById('dueend_date').value=this.selection.print("%Y-%m-%d");
													fctsearch.showFctSearch(
																document.getElementById('order_txt').value,
																document.getElementById('customer_txt').value,
																document.getElementById('csr_type').value,
																document.getElementById('evntstart_date').value,
																document.getElementById('evntend_date').value,
																document.getElementById('fct_name').value,
																document.getElementById('creat_start_date').value,
																document.getElementById('creat_end_date').value,
																document.getElementById('dep_name').value,
																document.getElementById('ship_start_date').value,
																document.getElementById('ship_end_date').value,
																document.getElementById('product_type').value,
																document.getElementById('duestart_date').value,
											                    document.getElementById('dueend_date').value,
																document.getElementById('Status').value,
													
														{preloader:'prl',onUpdate: function(response,root){
														document.getElementById('task_area').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] }) }});												
														}
										   });			
							}
							start_cal();
							</script>
				</td>
				<td>
				   <a href="javascript:void(0);" 
				      onclick="javacsript:document.getElementById('dueend_date').value = '';
					  					  fctsearch.showFctSearch(
											document.getElementById('order_txt').value,
											document.getElementById('customer_txt').value,
											document.getElementById('csr_type').value,
											document.getElementById('evntstart_date').value,
											document.getElementById('evntend_date').value,
											document.getElementById('fct_name').value,
											document.getElementById('creat_start_date').value,
											document.getElementById('creat_end_date').value,
											document.getElementById('dep_name').value,
											document.getElementById('ship_start_date').value,
											document.getElementById('ship_end_date').value,
											document.getElementById('product_type').value,
											document.getElementById('duestart_date').value,
											document.getElementById('dueend_date').value,
											document.getElementById('Status').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
												);"> <img src="images/trash.gif" border="0" /></a>
				</td>
			 </tr>
			  <tr>
			   <td colspan="4">&nbsp;</td>
			   <td>Status</td>
			    <td>
				   <select style="width:100%;" name="Status" id="Status" 
				                onchange="javascript:fctsearch.showFctSearch(
											document.getElementById('order_txt').value,
											document.getElementById('customer_txt').value,
											document.getElementById('csr_type').value,
											document.getElementById('evntstart_date').value,
											document.getElementById('evntend_date').value,
											document.getElementById('fct_name').value,
											document.getElementById('creat_start_date').value,
											document.getElementById('creat_end_date').value,
											document.getElementById('dep_name').value,
											document.getElementById('ship_start_date').value,
											document.getElementById('ship_end_date').value,
											document.getElementById('product_type').value,
											document.getElementById('duestart_date').value,
											document.getElementById('dueend_date').value,
											document.getElementById('Status').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
												);">
						  <option value="">--Select--</option>
						  <option value="Active" selected="selected">Active</option>
						  <option value="Complete">Complete</option>
					</select>
				</td>
				<td colspan="4">&nbsp;</td>
			 </tr>
           </table>
	       </form>
  		<?php    
        $html = ob_get_contents();
		ob_end_clean();
		return $html;    
    }   //////end of function SearchFct
	
	
        function search_flow_chart_tasks( $search_arr=array() , $overide=array() ){
            $search_values = array();
            $search_values["task_status"] = "Active"; // Sets the default value of task_status to active 
            $options["extra_joins"] = '';
            $options["extra_columns"] = '';
            $options["return_sql"] = '';
            foreach( $search_arr as $n => $val ){
                $search_values[$n] = $val;
            }
            $options = array();
            foreach( $overide as $n => $v){
                $options[$n] = $v;
            }
            
            $sql = "SELECT a.* , b.name " . $options["extra_columns"] . " FROM `assign_flow_chart_task` a LEFT JOIN tbl_global_task b ON a.flow_chart_id = b.global_task_id " . $options["extra_joins"];
            $warr = array();
            foreach( $search_values as $column => $value ){
                if( $value != '' ){
                    switch( $column ){
                        case "chart_assign_id":
                        case "tree_id":
                        case "module":
                        case "flow_chart_id":
                        
                        case "task_status":
                        case "profile_page":
                        case "module_id":
                        case "created_date":
                        case "optional1":
                        case "due_date":
                        case "completion_date":
                        case "projected_path_due_date":
                        case "owner_module_name":
                        case "owner_module_id":
                          if( $value != '' ){
                            $warr[] = "a.$column = '$value'";  
                          }
                        break;
                        case "Owner":
                        
                            if( $value != '' && 1 == 2 ){
                                $warr[] = "a.owner_module_name = 'TBL_USER'";
                                $warr[] = "a.owner_module_id = '$value'";
                            }
                        break;    
                        case "due_date_min":
                            $warr[] = "a.due_date >= '$value 00:00:00'";
                        break;
                        case "due_date_max":
                            $warr[] = "a.due_date <= '$value 23:59:59'";
                        break;
                        case "CreatedOn_min":
                        case "created_after_date":
                            $warr[] = "a.created_date >= '$value 00:00:00'";
                        break;
                        case "CreatedOn_max":
                        case "created_befor_date":
                            $warr[] = "a.created_date <= '$value 23:59:59'";
                        break;
                        
                        case "attached_module":
                           $ep = explode(":|:" , $value);
                            if( count($ep) == 2 ){
                               $warr[] = "a.module = '" .$ep[0] . "'";
                               $warr[] = "a.module_id = '" .$ep[1] . "'";
                            }
                        break;
                        case "owner_module":
                           $ep = explode(":|:" , $value);
                            if( count($ep) == 2 ){
                               $warr[] = "a.owner_module_name = '" .$ep[0] . "'";
                               $warr[] = "a.owner_module_id = '" .$ep[1] . "'";
                            }
                        break;
                        case "status_id":
                        case "completion_result":
                         $warr[] = "a.$column LIKE '%$value%'";  
                        break;   
                        case "name":
                            $warr[] = "b.$column LIKE '%$value%'";
                        break;
                        case "global_task_id":
                        case "department_id":
                            if( $value != '' ){
                                $warr[] = "b.$column = '$value'";
                            }
                        break;
                        case "client_id":
                            //$warr[] = "c.";
                        break;
                        case "client_name":
                            //, c.display_name custom_display_name , c.email , c.account_id , c.phone_number
                            $warr[] = "c.display_name LIKE '%$value%'";
                        break;
                        case "client_email":
                            $warr[] = "c.email LIKE '%$value%'";
                        break;
                        case "client_account_id":
                            $warr[] = "c.account_id LIKE '%$value%'";
                        break;
                        case "client_phone_number":
                            $warr[] = "c.phone_number LIKE '%$value%'";
                        break;
                        case "order_id":
                            $warr[] = "d.OrderNumber = '$value'";
                        break;
                        case "contact_module_id":
                        $warr[] = "d.$column = '$value'";
                        break;
                    }
                }
            }
            if( count( $warr ) != 0 ){
                $where = " WHERE " . implode(" AND ", $warr);
            }
            $sql .= $where;
            if($options["return_sql"] != "yes" ){
                $result = $this->db->query($sql);
            // echo $sql;
                $return = array();
                while( $row=$this->db->fetch_assoc($result)){
                    //$row["sql"] = $sql;
                    $return[] = $row;
                }
                return $return;
            } else {
                return $sql;
            }
        }
    
	 function showFctSearch($order_id='',$customer='',$csr='',$event_start_date='',$event_end_date='',$fct_name='',$creat_start_date='',$creat_end_date='',$dep_name='',$ship_start_date='',$ship_end_date='',$product_id='',$due_start='',$due_end='',$Status='') {
	   ob_start();
	   
	   if(($Status=='') or ($Status=='Active'))
	   		{
				$Status_value='Active';
			}else
			{
				$Status_value='Complete'; 
			}
			$sql = "Select distinct a.module, a.flow_chart_id, a.task_status, a.due_date, a.created_date, b.name, c.group_id, c.group_name, d.event_date, d.ship_date, d.created, d.csr, g.contact_id, g.company_name from assign_flow_chart_task a , tbl_global_task b, tbl_usergroup c, erp_order d, contacts g ";
			
			if($csr) $sql .= " ,".TBL_USER." e ";
			
			$sql .=" where a.flow_chart_id = b.global_task_id and b.department_id = c.group_id  and d.vendor_contact_id = g.contact_id " ;
			
			
			if($Status_value)
			{
			$sql.="and a.task_status like '%$Status_value%'";}
			

			
			if($customer){
			$sql.=" and g.company_name like '%$customer%' "; }
			
			if($csr){
			$sql.=" and e.user_id = '$csr' and d.csr = e.user_id"; }
			
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
			
			if($due_start != '' and $due_end == ''){
			$sql.=" and a.due_date >= '$due_start' "; }
			
			if($due_start == '' and $due_end != ''){
			$sql.=" and a.due_date <= '$due_end' "; }
			
			if($due_start != '' and $due_end != ''){
			$sql.=" and a.due_date BETWEEN '$due_start' and '$due_end' "; }
			
			$sql .= " order by created_date ASC";
			$result= $this->db->query($sql,__FILE__,__LINE__);
			$total_rows=$this->db->num_rows($result);
		?>
		<table id="search_table" class="event_form small_text" width="100%">
			<thead>
			   <tr>
				  <th width="14%">FCT</th>
				  <th width="11%">Due Date</th>
				  <th width="7%">Order ID</th>
				  <th width="10%">Department</th>
				  <th width="17%">Customer</th>
				  <th width="11%">Event Date</th>
				  <th width="10%">Ship Date</th>
				  <th width="12%">CSR</th>
				  <th width="8%">Created</th>
			  </tr>
			</thead>
			<tbody>
			  <?php 
			     if($total_rows > 0 ){
				 while($row=$this->db->fetch_array($result)){
				 $sql_csr = "SELECT first_name, last_name FROM ".TBL_USER." WHERE user_id = $row[csr]";
				 $result_csr = $this->db->query($sql_csr,__FILE__,__LINE__);
				 $row_csr = $this->db->fetch_array($result_csr);?>
				 <tr>
					<td><?php echo $row[name]; ?></td>	  
					<td><?php echo $row[due_date]; ?></td>
					<td><a href="order.php?order_id=<?php echo $row[product_id]; ?>"><?php echo $row[product_id]; ?></a></td>
				    <td><?php echo $row[group_name]; ?></td>
					<td><a href="contact_profile.php?contact_id=<?php echo $row[contact_id]; ?>"><?php echo $row[company_name]; ?></a></td>
					<td><?php echo $row[event_date]; ?></td>
					<td><?php echo $row[ship_date]; ?></td>
					<td><?php echo $row_csr[first_name]. ' ' .$row_csr[last_name]; ?></td>
					<td><?php echo $row[created_date]; ?></td>
				</tr>
				<?php
			     } 
				}
			else
			{
			?>
			<tr><td colspan="9" align="center"> No Record Found!!!!</td></tr>
			<?php
			}
			?>
			</tbody>
		</table>
		<?php
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}//////end of function showFctSearch
  }//////end of class
?>