<?php
class fileserver {
    var $db;
    public function __construct() {
        $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
        $this->page = new basic_page;
    }
    /*
     * filearr is the $_FILES["fileid"] of the file to be attached
     * 
     */
    function upload_file( $module_id , $module_name , $status ,  $filearr ){

      
         if( is_dir(FILESERVER_LOCAL_PATH . str_replace(" ", "_", $module_name ) . "_" . $module_id ) == false ){
             mkdir(FILESERVER_LOCAL_PATH . str_replace(" ", "_", $module_name ) . "_" . $module_id);         
         }
         $tmp_name = str_replace(" ", "_", $filearr["name"]);
         $name_ep = explode("." , $tmp_name );
         $x = count( $name_ep ) -1 ;
         $new_name = 'file';
         foreach( $name_ep as $part ){
             if( $x == 0 ){
                $new_name .= "." . time() ."." . $part;
             } else {
                 $new_name .= "." . $part;
             }
             $x = $x -1;
         }

         $f = array();
         $f["module_id"] = $module_id;
         $f["module_name"] = $module_name;
         $f["name"] = $filearr["name"];
         $f["path"] = str_replace(" ", "_", $module_name ) . "_" . "$module_id/$new_name";
         $f["status"] = $status;
         $this->db->insert("erp_fileserver", $f);
         $file_id = $this->db->last_insert_id();
         $this->page->log_activity( $module_name ,  $module_id , 'file_added' , '', '' , 'file' , $file_id );
         echo $filearr["tmp_name"] . ":" . FILESERVER_LOCAL_PATH . $f["path"];
         return copy( $filearr["tmp_name"] , FILESERVER_LOCAL_PATH . $f["path"]);
          
    }
    
    
    function get_files( $module_id , $module_name ){
        $sql = "SELECT * FROM erp_fileserver a LEFT JOIN erp_fileserver_status b ON a.status = b.status_id WHERE a.module_name = '$module_name' AND a.module_id = '$module_id'";
        $result=$this->db->query($sql);
        //echo $sql;
        $return=array();
        while($row=$this->db->fetch_assoc($result)){
            $return[]=$row;
        }
        //return $sql;
        return $return;
   }
   
   function get_file_status(){
       //fileserver_style
       $result = $this->db->query("select * from erp_dropdown_options WHERE option_name = 'fileserver_style'");
       $return = array();
       while( $row=$this->db->fetch_assoc($result)  ){
           $row["status_id"] = $row["id"];  // This will make it so If I miss anywhere where this function was used befor, it will still work
           $row["status_name"] = $row["identifier"];// This will make it so If I miss anywhere where this function was used befor, it will still work
           $return[] = $row;
       }
       return $return;
   }
   function check_fileUpload(){
       $module_id = $_REQUEST["file_module_id"];
       $module_name = $_REQUEST["file_module_name"];
       $status = $_REQUEST["file_status"];
       $filearr = $_FILES["file_upload"];
       
       if( $module_id != '' && $module_name != '' ){
            $this->upload_file($module_id, $module_name, $status, $filearr);
       }
       return "$module_id $module_name $status";
   }
   
   
   function get_file_status_select( $override = array() ){
       $options["id"] = '';
       $options["selected"] = '';
       $options["onclick"] = '';
       $options["onchange"] = '';
       foreach( $override as $n => $v ){
           $options[$n] = $v;
       }

       $return = "\n\n" . '<select ';
              
       if( $options["onclick"] != ''){
           $return .= ' onclick="' . $options["onclick"] . '" ';
       }
       if( $options["onchange"] != ''){
           $return .= ' onchange="' . $options["onchange"] . '" ';
       }       
       $return .= ' id="file_status_' . $options["id"] . '" name="file_status" >
    <option value="" >--SELECT--</option>
    ';
         foreach( $this->get_file_status() as $stat ){
             
            $return .= '<option value="' . $stat["status_id"] . '" ';
            if( $stat["status_name"] == $options["selected"]){
                $return .= 'SELECTED';
            }
            $return .= ' >' . $stat["status_name"] . "</option>";    
         }
         
    $return .= '</select>';
    return $return;
   }
   
   function display_update_status( $file_id , $status_id ){
       $fql = array('status' => $status_id );
       $this->db->update('erp_fileserver', $fql , 'file_id', $file_id );
       $file = $this->db->fetch_assoc($this->db->query("SELECT * FROM erp_fileserver a LEFT JOIN erp_fileserver_status b ON a.status = b.status_id WHERE a.file_id = '$file_id' "));
       
       $return = $this->get_file_status_select( array( 'selected' => $file["status_name"], "onchange" => "javascript: fileserver.display_update_status( '" . $file["file_id"] . "' , this.value , { target: 'file_select_" . $file["file_id"] . "' } )" )  );
       return $return;
   }
   
   function display_file_rename( $file_id , $newname ){
       
   }
   
   function display_files($module_id,$module_name , $override=array()){
         $options=array();
         $options["main_width"] = "200px";
         $options["header_color"] = "#777";
         $options["body_color"] = '#d5d5d5';
         $options["table_width"] = '100%';
         $options["main_position"] = "relative";
         $options["main_left"] = "0px";
         $options["main_display"] = "block";
         $options["nostyle"] = false;
         $options["class"] = '';
         $options["main_style_overide"] = '';
         $options["header_text_style"] = '';
         foreach( $override as $n => $v){
             $options[$n] = $v;
         }
         $module_name_true = $module_name;
         $module_name = str_replace(" ", "_", $module_name);
         $files = $this->get_files($module_id, $module_name_true);
         $return = "<div id='fileserver_$module_id-$module_name' ";
         if($options["nostyle"] == false && $options["main_style_overide"] == '' ){
            $return .= " style='position: " . $options["main_position"] . ";left: " . $options["main_left"] . ";width:" . $options["main_width"] . ";background: " . $options["body_color"] . ";display: " . $options["main_display"] . ";' ";
         }
         if( $options["main_style_overide"] != ''){
             $return .= " style='" . $options["main_style_overide"] . "' ";
         }
         
         if( $options["class"] != '' ){
             $return .= " class='" . $options["class"] . "' ";
         }
         $return .= "><table cellspacing=0 cellpadding=0 border=0 style='width:" . $options["table_width"] . "; '>
             <tr style='background: " . $options["header_color"] . "' ><td style='background: " . $options["header_color"] . "' ><p style='" . $options["header_text_style"] . "'>Documents</p></td><td style='background: " . $options["header_color"] . "' ><p style='" . $options["header_text_style"] . "'>Style</p></td></tr>";
         foreach( $files as $file ){
             $return .= "<tr ><td><a href='" . FILESERVER_REMOTE_PATH . $file["path"] . "' >" . $file["name"] . "</a>&nbsp;&nbsp;<a onclick='\$(\"#rename_" . $file["file_id"] . "\").show()' >rename</a><input onblur='' style='display: none;' id='rename_" . $file["file_id"] . "' /></td><td><div id='file_select_" . $file["file_id"] . "' >" . $this->get_file_status_select( array( 'selected' => $file["status_name"], "onchange" => "javascript: fileserver.display_update_status( '" . $file["file_id"] . "' , this.value , { target: 'file_select_" . $file["file_id"] . "' } )" )  )  . "</div></td></tr>\n";
         }
         $return .= '</table><a onclick="javascript:$(\'#fileserver_' . $module_id . '-' . $module_name . '_upload\').toggle()" ><button>Upload File</button></a>';
         
         $return .= '<div style="display:none;background: #d5d5d5;width:300px;" id="fileserver_' . $module_id . '-' . $module_name . '_upload" ><form name="' . $module_name . $module_id . '" action="" method="post" enctype="multipart/form-data"  >
    <input id="file_upload" type="file" name="file_upload"></input>
    <input name="file_module_id" value="' . $module_id . '" type="hidden" >
        <input name="file_module_name" value="' . $module_name_true . '" type="hidden" >
    <div style="width: 100px;float: left;" >Type:</div><select  id="file_status" name="file_status" >
    <option value="" >--SELECT--</option>
    ';
         foreach( $this->get_file_status() as $stat ){
            $return .= '<option value="' . $stat["status_id"] . '" >' . $stat["status_name"] . "</option>";    
         }
         
    $return .= '</select>
        <input type="submit"  value="Upload File" />    
</form></div></div>';
         return $return;
   }
}
?>
