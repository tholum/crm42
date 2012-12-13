<?php
class knowledge_base {
    var $page;
    var $db;
    var $eapi_api;
    public function __construct( $page='' ) {
        if( $page ==''){
            $this->page = new basic_page();
        } else {
            $this->page = $page;
        }
        $this->db = $this->page->db;
        $this->eapi_api = new eapi_api;
        $this->casecreation = new case_creation($this->page);
    }
    function display_knowledgebase( $knowledgebase_id ){
        ob_start();
        $result = $this->db->query("SELECT * FROM knowledgebase WHERE `knowledgebase_id` = '$knowledgebase_id'");
        $kb = $this->db->fetch_assoc( $result);
        ?>
<div class="knowledgebase" >
    <?php echo $kb['body']; ?>
</div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
    function knowledgebase_search( $query ){
        $result = $this->db->query("SELECT * FROM knowledgebase WHERE title LIKE '%$query%' OR knowledgebase_id LIKE '%$query%'");
        $array=array();
        while( $row = $this->db->fetch_assoc($result)){
            $row['value'] = $row['title'];
            $array[] = $row;
        }
        return $array;
    }
    function quicksearch($query=''){
        $json = $this->eapi_api->query_account($query);
        $prep_arr = json_decode($json);
        $array = array();
        //$array[] = array('value' => is_numeric($query) . "_" . strlen($query) , 'name' => is_numeric($query) . "_" . strlen($query) , 'onclick' => 'alert("ok");');
        if(is_numeric($query) && strlen($query) >= 6 ){
            $json2 = $this->eapi_api->order_detail_lookup($query);
            $prep_arr2 = json_decode($json2);
           // $array[] = array('value' => is_numeric($query) . "_" . strlen($query) , 'name' => is_numeric($query) . "_" . strlen($query) , 'onclick' => 'alert("ok");');
            //foreach($prep_arr2 as $n => $v ){
            if( $prep_arr2->Id != ''){
              $array[] = array('value' => 'Order: ' . $prep_arr2->Id , 'Order: ' . $prep_arr2->Id , 'onclick' => $this->page->page_link( 'order_search' , array('order_id' => $prep_arr2->Id )  ) );  
            }
        }
        
        //
        if(is_numeric($query)){
            $sql = "SELECT* 
FROM`cases` WHERE case_id LIKE '%$query%'
ORDER BY `case_id` ASC 
LIMIT 0 , 10";
             $result = $this->db->query($sql);
             while( $row = $this->db->fetch_assoc($result)){
                 $array[] = array( 'value' =>  'Case: ' . $row['case_id'] , 'name' =>  'Case: ' . $row['case_id'] , 'onclick' => "casecreation.right_bottom_by_case( '" . $row['case_id'] . "',
            {
                preloader:'prl',
                onUpdate: function(response,root){ 
                    $('#right_bottom_panel').html(response);
                }
             });" );
             }
        }
        
        foreach($prep_arr->SearchResult as $n => $v ){
            $tmparr = array();
            $tmparr['email_address'] = $v->Email;
            $tmparr['account_id'] = $v->Id;
            $tmparr['account_name'] = $v->Studio;
            $array[] = array('value' => 'Account: ' . $v->Studio , 'Account: ' . $v->Studio , 'onclick' => $this->page->page_link( 'accountdashboard' , $tmparr  ) );
           
        }


        return $array;
    }
    function phone_cid_lookup($phone_number){
        //https://api.opencnam.com/v1/phone/6087838324?format=json
                ob_start();
            $curl = curl_init();
            curl_setopt( $curl , CURLOPT_URL , "https://api.opencnam.com/v1/phone/$phone_number?format=json" );
            curl_setopt( $curl , CURLOPT_HTTPHEADER , array("Accept: application/json") );
            curl_exec( $curl );
        $html = ob_get_contents();
        ob_end_clean();
        if($html != ''){
            $json_object = json_decode($html);
            $return = $json_object->cnam;
        } else { $return = ''; }
        return $return;
    }
    function phone_popout($phone_number=''){
         ob_start(); ?>
            
            
            <?php 
            $sql = "SELECT a.* , b.* , a.type phone_type, c.company_name contact_company_name , c.contact_id company_contact_id FROM contacts_phone a LEFT JOIN contacts b ON a.contact_id = b.contact_id LEFT JOIN contacts c ON b.company = c.contact_id AND b.type='People' WHERE a.number = '$phone_number' ";
            $result=$this->db->query($sql);
            $info = $this->db->fetch_assoc( $result);
            //var_dump($info);
            //echo $sql;
                if( $info !== false){
                   if( $info['type'] == "Company"){
                       $contact_id = $info['contact_id'];
                   } else {
                       $contact_id = $info['company_contact_id'];
                   }
                    $active =  $this->casecreation->get_cases_by_module(
                                    'CONTACTS', $contact_id , 
                                    array(
                                        'from_contact' => true,
                                        'only_active' => true ,
                                        'limit' => ''
                                        ) 
                                );

                    ?>
            <div class="phone_header" style="width: 100%; font-weight: bold; font-size: 14px" >
               <a onclick="<?php 
               if( $info['type'] == "Company"){
                   
               echo $this->page->page_link( 'contacts' , array('contact_id' => $info['contact_id'] ) ); 
               
               } else {
                   
                   
                   echo $this->page->page_link( 'contacts' , array('contact_id' => $info['company_contact_id'] ) );
               } ?>" >
                   <?php if( $info['type'] == "Company"){ echo $info['company_name']; } 
                   else 
                       {
                       echo $info['contact_company_name'] . "<br/>";
                       echo "<a style='font-size: 12px;' onclick=\"" . $this->page->page_link( 'contacts' , array('contact_id' => $info['company_contact_id'] ) ) . "\"  >" . $info['first_name'] . " " . $info['last_name'] . '(' . $info['phone_type']  . ') </a>'; } ?></a></div>
            <div>Cases:</div>    
            <div id="phone_case_info" style="width: 100%; height: 75px; overflow-y: auto;">
                <?php 
                
                foreach( $active as $case ){
                    $button_text = $case['case_id'] . " No Subject" ;
                    if( $case['subject'] != '' ){
                        $button_text = $case['case_id'] .": " . $case['subject'];
                    }
                $button_text = substr($button_text , 0 , 35 );
                //casecreation.right_bottom_by_case( '738', { preloader:'prl', onUpdate: function(response,root){ $('#right_bottom_panel').html(response); } });
                    ?><a style="display: inline;" onclick="casecreation.right_bottom_by_case( '<?php echo $case['case_id']; ?>', { preloader:'prl', onUpdate: function(response,root){ $('#right_bottom_panel').html(response); } });" /><?php echo $button_text; ?></a><br/>  <?php
                }
                ?>
            </div>
            
            
                    <?php
                    //casecreation.create_case_by_array({'module_name': 'eapi_ACCOUNT','module_id': '59891' ,'contact_module_name': 'eapi_ACCOUNT','contact_module_id': '59891' ,'Status': 'Active', 'Owner': '10'} , { onUpdate: function(response,root){ $('#right_bottom_panel').html(response);} });
                    ?>
            <a onclick="casecreation.create_case_by_array(
                {
                    'module_name': 'CONTACTS',
                    'module_id': '<?php echo $contact_id; ?>' ,
                    'contact_module_name': 'CONTACTS',
                    'contact_module_id': '<?php echo $contact_id; ?>' ,
                    'Status': 'Active', 
                    'Owner': '<?php echo $_SESSION['user_id']; ?>',
                    'CaseOrigin': 'Phone'
                } , { onUpdate: function(response,root){ $('#right_bottom_panel').html(response);slimcrm.flash_sidepanel();} });" >
                
                <button>create case<div class="add_button in_button" >&nbsp;</div></button></a>
                    <?php
                } else {
                    $lookup_phone=true;
                //var_dump($ac_info);
            ?>
            <div class="phone_header" style="width: 100%; font-weight: bold; font-size: 14px" ><?php echo $phone_number; ?><div class="phone_lookup_target" ></div>
                <a onclick="<?php echo $this->page->page_link( 'contacts' , array('create_contact' => true , 'phone_number' => $phone_number ) ); ?>"><button>Create Contact<div class="in_button add_button">&nbsp;</div></button></a>
                <a onclick="<?php echo $this->page->page_link( 'contacts' ); ?>"><button class="in_button">Search Contacts<div class="in_button search_button">&nbsp;</div></button></a>
            </div>
            
            <?php }
            
            ?> 
                <script>
            function kb_run_on_start(){
                <?php if($lookup_phone){ echo "knowledgebase.phone_cid_lookup('$phone_number',{ onUpdate: function(response,root){ $('.phone_lookup_target').html(response);} });"; }?>
                    
            }
            </script>
                <?php
            $html = ob_get_contents();
        ob_end_clean();
        return $html;       
    }
    
    
    function display_flyout(){
        ob_start(); ?>
<script>
function kb_run_on_start(){
    $('#kbqs_flyout_quicksearch').autocomplete({ 
        source: 'quicksearch.php', 
        select: function( event , ui ){
            eval( ui.item.onclick );
        }  
    });
        $('#kbqs_flyout_knowledgebase').autocomplete({ 
        source: 'knowledgebase_search.php', 
        select: function( event , ui ){
            kb_object.open_popup( ui.item.knowledgebase_id , ui.item.title );
        }  
    });
    //kb_object.open_popup('1');
}
</script>
        <h2>Quick Search</h2>
        <input id="kbqs_flyout_quicksearch" onfocus="$(this).select()" />
        <h2>Knowledge Base Search</h2>
        <input id="kbqs_flyout_knowledgebase" onfocus="$(this).select()" />
 <?php  $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
    
    function create_knowledgebase( $title ){
        $insert = array();
        $insert['title'] = $title;
        $insert['last_edited_user'] = $_SESSION['user_id'];
        $this->db->insert('knowledgebase' , $insert);
        return $this->manage_knowledgebase_inner();
    }
    
    function manage_knowledgebase(){
        ob_start(); ?>
        <a onclick="$('body').append('<div id=create_knowledgebase title=Create >Title <input id=new_title /></div>');
                          $('#create_knowledgebase').dialog(
                     {
			resizable: false,
			height:140,
			modal: true,
			buttons: {
				'Create Template': function() {
                                        knowledgebase.create_knowledgebase( $('#new_title').val() ,{ target: 'manage_knowledgebase', onUpdate: function(result, root){ run_on_reload(); } });
					$( this ).dialog( 'destroy' ).remove();
				},
				Cancel: function() {
					$( this ).dialog( 'destroy' ).remove();
				}
			}
                    });
                        " ><button>create knowledgebase<div class="trash_can_normal in_button" >&nbsp;</div></button></a>
        <div class="manage_knowledgebase accordion" id="manage_knowledgebase">
            <?php echo $this->manage_knowledgebase_inner(); ?>
        </div>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
    
    function delete_knowledgebase( $kb_id ){
        $this->db->query("DELETE FROM knowledgebase WHERE knowledgebase_id = '$kb_id'");
        return $this->manage_knowledgebase_inner();
    }
    
    function save_knowledgebase( $kb_id , $title , $body ){
        $update = array();
        $update['title'] = $title;
        $update['body'] = $body;
        $update['last_edited_user'] = $_SESSION['user_id'];
        $this->db->update('knowledgebase' , $update , 'knowledgebase_id' , $kb_id , false , '' , '' , 0 );
        return $this->manage_knowledgebase_inner();
    }
    function manage_knowledgebase_inner(){
        $result = $this->db->query('SELECT * FROM `knowledgebase`');
        ob_start(); 
        while( $row = $this->db->fetch_assoc($result) ){
        ?>
            <H3><a href='#' ><?php echo $row["title"]; ?></a></H3>
            <div>
                <div style="height: 220px;">
                    <a onclick="knowledgebase.save_knowledgebase(<?php echo $row["knowledgebase_id"]; ?> , $('#kb_title_<?php echo $row["knowledgebase_id"]; ?>').val() , tinyMCE.get('kb_body_<?php echo $row["knowledgebase_id"]; ?>').getContent()  , { onUpdate: function(responce , root){ $('#manage_knowledgebase').html(response); run_on_reload();}})" ><button>save knowledgebase<div class="save_button in_button" >&nbsp;</div></button></a>
                    <a onclick="$('body').append('<div id=delete_template<?php  echo $template['eml_template_id'];?> title=Delete >Delete Knowledgebase Entery?</div>');
                          $('#delete_template<?php  echo $template['eml_template_id'];?>').dialog(
                     {
			resizable: false,
			height:140,
			modal: true,
			buttons: {
				'Delete': function() {
                                        knowledgebase.delete_knowledgebase('<?php echo $row["knowledgebase_id"]; ?>',{ target: 'manage_knowledgebase', onUpdate: function(result, root){ run_on_reload(); } });
					$( this ).dialog( 'destroy' ).remove();
				},
				Cancel: function() {
					$( this ).dialog( 'destroy' ).remove();
				}
			}
                    });
                        " ><button>Delete Knowledgebase<div class="trash_can_normal in_button" >&nbsp;</div></button></a>
                    <br/>
                    <input id="kb_title_<?php echo $row["knowledgebase_id"]; ?>" style="width: 500px;" value="<?php echo $row['title'] ?>">
                    <textarea  id="kb_body_<?php echo $row["knowledgebase_id"]; ?>" class="mceeditor_500" ><?php echo $row["body"]; ?></textarea>
                </div>
            </div>
        <?php
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
    
}
?>
