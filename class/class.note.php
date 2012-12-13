<?php
/***********************************************************************************

			Class Discription : Note
								This Module helps in creation or viewing the notes.
								User can create a note for any person or company.
								User can see or delete the notes send to him.
			
			Class Memeber Functions : Create_Note($runat,$contact_id,$user_id)
									  Get_Note($contact_id)
			
			Describe Function of Each Memeber function :
								
							  1. function Create_Note($runat,$contact_id,$user_id)// $runat=local/server,$contact_id=contact_id of the user,$user_id= user_id of the company/person to whom the note is to send
							  
									this function provides a module in which a user can leave note for any person or company whose page he has visited
							  
							  2. function Get_Note($contact_id)  $contact_id= contact_id of the user
									this function displays all the notes left for him.

************************************************************************************/
class Note
{
	var $contact_id;
	var $description;
	var $user_id;
	function __construct()
	{
            $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
            $this->page = new basic_page;
	}
	/*************************************************************************************/
	// function for create note
        function add_note( $note , $module_name , $module_id , $user_id , $overide=array() ){
            ob_start();
            $options=array();
            $options["target"] = $module_name . "_" . $module_id;
            foreach( $overide as $n => $v){
                $options[$n] = $v;
            }
            $insert=array();
            $insert["module_id"] = $module_id;
            $insert["module_name"] = strtoupper($module_name);
            $insert["description"] = $note;
            if( $user_id == ''){
                $insert['user_id'] = $_SESSION['user_id'];
            } else {
                $insert["user_id"] = $user_id;
            }
            $this->db->insert("tbl_note", $insert);
            $note_id = $this->db->last_insert_id();
            echo $this->display_note_by_module_inner( $module_name , $module_id , $user_id , $options );
            $html = ob_get_contents();
            ob_end_clean();
            $this->page->log_activity( $module_name ,  $module_id , 'note_added' , '', $note , 'notes' , $note_id );
            return $html;
            
        }
        function get_user_array(){
            $result = $this->db->query("SELECT * FROM tbl_user");
            $return = array();
            while( $row = $this->db->fetch_assoc($result)){
                $row['value'] = $row['user_id'];
                $row['name'] = $row['first_name'] . " " . $row['last_name'];
                $return[] = $row;
            }
            return $return;
        }
        function display_note_by_module( $module_name , $module_id , $user_id , $overide=array() ){
            //$users
            ob_start();
            $options=array();
            $options["target"] = "notes_" .$module_name . "_" . $module_id;
            foreach( $overide as $n => $v){
                $options[$n] = $v;
            }
            ?>
<div class="note_title title note_title_<?php echo $module_name; ?>"><div class="note_tilte_internal">Account Notes: 
        <input id="<?php echo $options["target"] ?>_note" class="note_search_input" type="text" onchange="note.display_note_by_module_inner(
            '<?php echo $module_name; ?>' , 
            '<?php echo $module_id; ?>' , 
            '<?php echo $user_id; ?>', 
            {
                'target': '<?php echo $options["target"]; ?>' , 
                'user_id': $('#<?php echo $options["target"] ?>_user_id').val(),
                'note': $('#<?php echo $options["target"] ?>_note').val()
            } , 
            { 
                target: '<?php echo $options["target"]; ?>'
            });" 
           />
        <select id="<?php echo $options["target"] ?>_user_id" onchange="note.display_note_by_module_inner(
            '<?php echo $module_name; ?>' , 
            '<?php echo $module_id; ?>' , 
            '<?php echo $user_id; ?>', 
            {
                'target': '<?php echo $options["target"]; ?>' , 
                'user_id': $('#<?php echo $options["target"] ?>_user_id').val(),
                'note': $('#<?php echo $options["target"] ?>_note').val()
            } , 
            { 
                target: '<?php echo $options["target"]; ?>'
            });">
            
            
            <option value="" >Select</option>
            <?php echo $this->page->array_to_options($this->get_user_array() ); ?>
        </select><a onclick="note.display_note_by_module_inner(
            '<?php echo $module_name; ?>' , 
            '<?php echo $module_id; ?>' , 
            '<?php echo $user_id; ?>', 
            {
                'target': '<?php echo $options["target"]; ?>' , 
                'user_id': $('#<?php echo $options["target"] ?>_user_id').val(),
                'note': $('#<?php echo $options["target"] ?>_note').val()
            } , 
            { 
                target: '<?php echo $options["target"]; ?>'
            });"><button class="note_search_button">search<div class="in_button search_button"></div></button></a></div></div>
            <div id="<?php echo $options["target"]; ?>"class="note note_<?php echo $module_name;?>" >
              <?php echo $this->display_note_by_module_inner( $module_name , $module_id , $user_id , $options ); ?>
            </div>
            <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;    
        }
        

        
        function display_note_by_module_inner( $module_name , $module_id , $user_id , $overide=array() ){
            ob_start();
            $options=array();
            $options["target"] = $module_name . "_" . $module_id;
            $options['note'] = '';
            $options['user_id'] = '';
            foreach( $overide as $n => $v){
                $options[$n] = $v;
            }
             echo $this->Get_Note($module_id,$module_name , $options );?>
                <textarea class="add_note_textarea" id="add_note_<?php echo $options["target"]?>" ></textarea>
                <br/><a href="#" onclick="note.add_note( $('#add_note_<?php echo $options["target"]?>').val() , '<?php echo $module_name;?>' , '<?php echo $module_id;?>' , '<?php echo $user_id;?>' ,{ target: '<?php echo $options["target"]?>'}, { target: '<?php echo $options["target"]?>',onUpdate: function(response,root){$('.note_search_button').focus();}})"><button>Add Note</button></a>
            <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;  
        }
        function SetRefreshJs($js){
            $this->refresh_js = $js;
        }
	function Create_Note($runat,$module_id,$module,$user_id , $overide=array())
	{	
            $rand=rand(1,999999);
            $FormName = 'content_form';
            $options=array();
            $options["return"]= "no";
            foreach( $overide as $n => $v ){
                $options[$n] = $v;
            }
            ob_start();
		$this->module_id=$module_id;
		$this->module=$module;
		$this->user_id=$_SESSION['user_id'];
		switch($runat){
		
		case 'local' :	
			switch($this->module) {
				case 'TBL_CONTACT':
					$sql="select first_name, last_name , company_name, type from ".TBL_CONTACT."  where contact_id='$this->module_id'";
					$record=$this->db->query($sql,__FILE__,__LINE__);
					$row=$this->db->fetch_array($record);
					?>
					<h3 id="note_header">
					Add Notes for <?php
					if($row['type']=='People')
					 echo $row['first_name'].' '.$row['last_name'];
					else
					 echo $row['company_name'];
					 ?>
					</h3>
					<?php
					break;
				case 'Event':

					break;
				}
				?>
		<form name="<?php echo $FormName; ?>" id="content_form" enctype="multipart/form-data" action="" method="post">
		  <textarea name="description" id="description" style="width: 98%;"></textarea>
                  <input name="module_id" value="<?php echo $this->module_id; ?>" type="hidden" />
                  <input name="module" value="<?php echo $this->module; ?>" type="hidden" />
		  <div style="width: 98%; text-align: right;">
                      <input type="submit"  class="subbut"  name="submit" id="submit" value="add message" onclick="javascript: if(this.form.description.value!=''){ $.post('ajax.note.php' , $('#<?php echo $FormName; ?>').serialize() )} <?php echo $this->refresh_js; ?>;return false;" />
		  </div>
		</form>
		<?php
		break;
		
		case 'server' : 
		extract($_POST);
		if(trim($description)!='')
		{
			$this->description=$description;
                        $this->module_id=$module_id;
                        $this->module=$module;
                        $this->user_id=$_SESSION['user_id'];
			$insert_sql_array = array();
			$insert_sql_array['module_id'] = $this->module_id;
			$insert_sql_array['module_name'] = $this->module;
			$insert_sql_array['user_id'] = $this->user_id;
			$insert_sql_array['description'] = $this->description;
			$this->db->insert('tbl_note',$insert_sql_array);
			$_SESSION['msg']='Your note created successfully';
			?>
			<script type="text/javascript">
			window.location="<?php echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];?>";		
			</script>
			<?php
		}
		else
		{
			$_SESSION['msg']='Sorry!! Note can not be created, Please type some note and try again';
			?>
			<script type="text/javascript">
			window.location="<?php echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];?>";		
			</script>
			<?php
		}
		break;
		default : echo 'Wrong Paramemter passed';
		}
                $html = ob_get_contents();
                ob_end_clean();
                if($options["return"] == "no"){
                    echo $html;
                }
                return $html;
	}

	/*************************************************************************************/
	function Get_Note($module_id,$module,$overide=array()){
            $options=array();
            $options["target"] = $module_name . "_" . $module_id;
            $options['note'] = '';
            $options['user_id'] = '';
            foreach( $overide as $n => $v){
                $options[$n] = $v;
            }
            $where = '';
            if($options['note'] != ''){
                $where='';
            }
	    ob_start();
		$this->module_id=$module_id;
		$this->module=$module;		
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		
		$sql="select distinct a.user_id, b.first_name from tbl_note a, tbl_user b where a.user_id=b.user_id and a.module_id= '".$this->module_id."' and a.module_name= '".$this->module."' order by a.n_time desc";
		
		$record = $this->db->query($sql);
		if($this->db->num_rows($record)>0){
			if($this->module=='TBL_CONTACT' || $this->module=='ORDER'){
			?>
			<h3 class="full_bar_header">Past interactions:</h3>
			Show notes for:
				<select id="show_notes" name="show_notes" 
						onchange="javascript: note.show_search_record('<?php echo $this->module_id; ?>',
																	  '<?php echo $this->module; ?>',
																	  this.value,
																	  document.getElementById('search_notes').value,
                                                                      {preloader:'prl',onUpdate: function(response,root){
																	   document.getElementById('records').innerHTML=response;
																	   $('#display_search')
																	   .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}
																	   });">
					<option value="">-Select-</option>
					<?php 
					while($row=$this->db->fetch_array($record)){?>
					<option value="<?php echo $row['user_id'];?>"><?php echo $row['first_name'];?></option>
					<?php } ?>
				</select>
				
				<input type="text" name="search_notes" id="search_notes" 
						onchange="javascript:  note.show_search_record('<?php echo $this->module_id; ?>',
																	   '<?php echo $this->module; ?>',
																	   document.getElementById('show_notes').value,
																	   this.value,																																	                                                                       {preloader:'prl',onUpdate: function(response,root){
																		document.getElementById('records').innerHTML=response;
																		$('#display_search')
																		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]]})}
																		});" />
			<?php  } ?>
                       
   		    <div id="records"><?php echo $this->show_search_record($this->module_id,$this->module,$options['user_id'],$options['note']); ?></div>
			 <?php }
	  $html = ob_get_contents();
	  ob_end_clean();
	  return $html;
	}
	
function show_search_record($module_id='',$module='',$selected_contact_id='',$show_notes=''){
	ob_start();
	
	$sql="select * from ".TBL_NOTE." a, ".TBL_USER." b where a.user_id=b.user_id and a.module_id= ".$module_id." and a.module_name= '".$module."'";
	if($selected_contact_id !=''){
		$sql .=" and a.user_id like '%$selected_contact_id%'";			
	}		
	if($show_notes){
		$sql .=" and a.description like '%$show_notes%'";
	}		
	$sql .= " order by a.n_time desc";
	//echo $sql.'<br><br>';
	$result = $this->db->query($sql,__FILE__,__lINE__);	
	?>
	<table id="display_search" class="event_form small_text" width="100%">
	<tbody>	
	<?php
	if($this->db->num_rows($result)>0) {
		$i=0;
		while($row=$this->db->fetch_array($result)) { ?>
			<tr <?php if($i%2==0) echo 'class="odd"'; else echo 'class="even"';?>>
				<td width="19%"><?php echo date("l, F jS h:i A",strtotime($row['n_time']));?></td>
				<td width="9%"><?php echo ' by '.$row['first_name'].' '.$row['last_name']; ?></td>
				<td width="50%"><?php echo ' : '.str_replace("\n" , "\n<br/>" , $row['description']); ?></td>
			</tr>
		<?php $i++; }
	}
	else{ ?>
		<tr><td><?php echo "No Records Found!!!!!!!!"; ?></td></tr>
	 <?php } ?>
	</tbody>
	</table>
	<?php		
	$html = ob_get_contents();
	ob_end_clean();
	return $html;	
}
}
?>
