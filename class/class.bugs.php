<?php 
class Bugs {

	var $db;
	
	function __construct(){
	    $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	}  
	
	function bugTracking($runat,$location='self'){
	
		ob_start();
		switch($runat){
			case 'local' : 
				if($location!='client'){
				?>
				<div class="prl">&nbsp;</div>
				<div id="lightbox">
				<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
				<div id="TB_ajaxWindowTitle">Enter Bug</div>							
				<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_bugs').style.display='none';">
				<img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;" >
				<div id="showalldates">
				<?php } ?>	
				<form name="frm_bugs" enctype="multipart/form-data" action="" method="post" >
				<table class="table" align="center" width="100%">
				<tr><td>Title</td></tr>
				<tr><td><input name="title" id="title" type="text" style="width:100%"/></td></tr>
				<tr><td>Project Description</td></tr>
				<tr><td><textarea name="description" id="description" style="width:100%"></textarea></td></tr>
				<tr><td>Importance</td></tr>
				<tr><td>
					<?php 
					$sql = "select * from ".IMPORTANCE_TYPE;
					$result = $this->db->query($sql,__FILE__,__lINE__);		
					?><select name="importance_type_id" id="importance_type_id" style="width:100%">
					<option value="" >--Select--</option>
					<?php while($row=$this->db->fetch_array($result)){ ?>
					<option value="<?php echo $row[importance_type_id]; ?>"><?php echo $row[importance_type_value]; ?> </option>
					<?php } ?>
					</select>
				
				<?php 
						$sql = "select * from ".PROJECT_STATUS." where status = 'Bug Report'" ;
						$result = $this->db->query($sql,__FILE__,__lINE__);		
						$row=$this->db->fetch_array($result);
						?><input readonly="true" name="status" id="status" type="text" style="display:none; width:100%" value="<?php echo $row[status]; ?>" />
						<input name="status_id" type="hidden" id="status_id" style="width:100%" value="<?php echo $row[status_id]; ?>" />
						<?php
							$sql = "select * from ".DEPARTMENT." where department_value = 'Debugging'";
							$result = $this->db->query($sql,__FILE__,__lINE__);
							$row=$this->db->fetch_array($result);
							?><input readonly="true" name="department" type="text" id="department" style="display:none; width:100%" 
							value="<?php echo $row['department_value'];?>" / >
							<input name="department_id" type="hidden" id="department_id" style="width:100%" value="<?php echo $row['department_id'];?>" / >
				
				</td></tr>
				<tr><td>Upload Screen Shot</td></tr>
				<tr><td><input type="file" name="myfile" id="myfile"  style="width:100%"/>
				<div id="my_div"></div>
				<script>
				document.getElementById('my_div').innerHTML = window.opener.document.getElementById('test_div').innerHTML;
				</script>
				</td></tr>
				<tr><td><input style="width:auto" name="submit" id="submit" value="submit" type="submit"/></td></tr>
				</table/>
				</form>
				<?php 
				
				
				if($location!='client'){ ?>
				</div>		
				</div></div></div> 
				<?php
				}
				break;
				
			case 'server'; 
				
				extract($_POST);
				$i = 0;			
				$insert_sql_array = array();
				$insert_sql_array[title] = $title;
				$insert_sql_array[description] = $description;
				$insert_sql_array[importance_type_id] = $importance_type_id;
				$insert_sql_array[department_id] = $department_id;
				$insert_sql_array[status_id] = $status_id;
				$insert_sql_array[started] = date("Y-m-d");
				$this->db->insert(PROJECT,$insert_sql_array);
				
				$project_id = $this->db->last_insert_id();

				$doc_name = $this->getRandomName($_FILES[myfile][name]);
				$doc_target_path = 'uploads/'.basename( $doc_name);
				if(move_uploaded_file($_FILES['myfile']['tmp_name'], $doc_target_path)){
					$insert_sql_array = array();
					$insert_sql_array[document_name] = $_FILES[myfile][name];
					$insert_sql_array[document_server_name] = $doc_name;
					$insert_sql_array[project_id] = $project_id;
					$insert_sql_array[user_id] = $_SESSION[user_id];
					$this->db->insert(PROJECT_DOCUMENT,$insert_sql_array);
				}

				$insert_sql_array = array();
				$insert_sql_array[project_id] = $project_id;
				$this->db->insert('tbl_bug_linker',$insert_sql_array);
				
				$bug_id = $this->db->last_insert_id();
				
				if($frm_type == 'client'){
					foreach($field_name as $k){
					$insert_sql_array = array();
					$insert_sql_array['variable'] = $field_name[$i];	
					$insert_sql_array['value'] = $field_value[$i];
					$insert_sql_array['bug_id'] = $bug_id;
					$insert_sql_array['importance'] = 'normal';
					$this->db->insert('tbl_bug',$insert_sql_array);
					$i++;
					}
					?>
					<script> alert('Bug Reported!!');  this.window.close(); </script>
					<?php
				}
				else {
					foreach($_SERVER as $key => $value ){
						$insert_sql_array = array();
						$insert_sql_array['variable'] = $key;	
						$insert_sql_array['value'] = $value;
						$insert_sql_array['bug_id'] = $bug_id;
						$insert_sql_array['importance'] = 'normal';
						$this->db->insert('tbl_bug',$insert_sql_array);
						}
					foreach($_SESSION as $key => $value ){
						$insert_sql_array = array();
						$insert_sql_array['variable'] = $key;	
						$insert_sql_array['value'] = $value;	
						$insert_sql_array['bug_id'] = $bug_id;
						$this->db->insert('tbl_bug',$insert_sql_array);
						}
				}

				
			}

		$html = ob_get_contents();
		ob_end_clean();
		return $html;			
	} 
	
	function getRandomName($filename) {
	$file_array = explode(".",$filename);
	$file_ext = end($file_array);
	$new_file_name = uniqid().date('m').date('d').date('Y').date('G').date('i').date('s').".".$file_ext;
	return $new_file_name;
    }


	function showBugProject(){
		ob_start();
		
		$sql_bug="select b.bug_id,a.title from ".PROJECT." a, ".TBL_BUG_LINKER." b where a.project_id = b.project_id";
		$result_bug = $this->db->query($sql_bug,__FILE__,__LINE__);
		?>
		<div id="treecontrol">
		<a title="Collapse the entire tree below" href="#">Collapse All</a> | 
		<a title="Expand the entire tree below" href="#"> Expand All</a>  
		<?php /*?><a title="Toggle the tree below, opening closed branches, closing open branches" href="#">Toggle All</a>
		<?php */?>
		</div>
		
		<ul id="red" class="treeview-gray">
		<?php
		while($row_bug = $this->db->fetch_array($result_bug)){ ?>	
			<li><span><div id="content_column_header" ><?php echo $row_bug[title];?></div></span>
				<ul>
					<div class="profile_box1" id="div_show_event">
					<?php echo $this->displayBugInfo($row_bug[bug_id]);	?>
					</div>
				</ul>
			</li>
			<?php 
			}
		?>
		</ul>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function displayBugInfo($bug_id){
		ob_start();
		$sql_bug_info="select * from ".TBL_BUG." where bug_id = $bug_id";
		$result_bug_info = $this->db->query($sql_bug_info,__FILE__,__LINE__);
		?><table width="100%" class="table">
		<th>Variable Name</th>
		<th>Value</th>
		<tr></tr><?php 
		while($row_bug_info = $this->db->fetch_array($result_bug_info)){ ?>		
			<tr>
			<td><?php echo $row_bug_info[variable];?></td>
			<td><?php 
				if(strlen($row_bug_info[value]) > 100 ) echo str_replace('","','",<br>"',$row_bug_info[value]);
				else echo $row_bug_info[value];?></td>
			</tr>
		<?php } 
		?></table><?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	
	}
	
}

?>