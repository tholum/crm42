<?php
require_once('class/class.cases.php');
class workspace {
    var $db;
    var $cases;
    function __construct(){
        global $database;
       
        $this->cases = new cases();
         $this->db = $this->cases->db;
    }
    
    function format_display($line){
        $max = 35;
        $display=$line['display'];
        if($display==''){
            $display=$line['module_name'] . ": " . $line['module_id'];
        }
        if( strlen($display) > $max ){
            $display = substr($display,0,$max-3) . "...";
        } 

        return $display;
    }
    
    function display_list($data_array){
        ob_start();
        foreach($data_array as $line ){
            ?>
             <li class="workspace_object workspace_<?php echo $line['type']; ?>"  onclick="dynamic_page.phplivex_subpage('workspace' , 'tasks' ,{'module_name': '<?php echo $line['module_name']; ?>' , 'module_id': <?php    echo $line['module_id'];?> } , {onUpdate: function(response,root){ $('.workspace_tasks').html(response);} });" ><div>&nbsp;</div><?php echo $this->format_display($line); ?></li>
            <?php
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
    
    function search($options=array(),$module_name="cases"){
        //This is to make $object an array if $options is an object or array
        $object = array();
        foreach($options as $n => $v ){
            $object[$n] = $v;
        }
        switch(strtolower($module_name)){
            case "cases":
                $sql = $this->cases->search_query( $object );
                $display_column='subject';
                $module_id='case_id';
                $module_name="CASES";
            break;
        }
        $result = $this->db->query($sql);
        $data_array = array();
        while($row=$this->db->fetch_assoc($result)){
            $data_row['display'] = $row[$display_column];
            $data_row['module_id'] = $row[$module_id];
            $data_row['module_name'] = $module_name;
            $data_row['type'] = "heading";
            $data_array[] = $data_row;
        }
        return $data_array;
    }
    
    function display_search($options,$module_name){
        return $this->display_list($this->search($options,$module_name));
    }
    
    function display_name_by_module($module_name,$module_id){
        switch(strtolower($module_name)){
            case 'cases':
                
            break;    
        }
    }
    
    
    function display_tasks($module_name , $module_id){
        
    }
    
}

?>