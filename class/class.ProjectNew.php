<?php 
class ProjectNew{

var $project_id;
var $db;
var $validity;
var $Form;
var $user_id;

	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
	}
	
	function GetCustomerJson($pattern=''){
		ob_start();
		$contact_json = "";
		$sql="select first_name,last_name,contact_id from ".TBL_CONTACT." where first_name LIKE '%$pattern%' and type='People' limit 0, 20";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		while($row = $this->db->fetch_array($record)){
		$cmp = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $row[first_name].' '.$row[last_name]);
		$contact_json .='{"caption":"'.$cmp.'","value":"'.$row[contact_id].'"},';
		}
		$contact_json = '['.substr($contact_json,0,strlen($contact_json)-1).']';
		return $contact_json;
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} ///////end of function GetVendorJson

	function GetUserJson($pattern=''){
		ob_start();
		$contact_json = "";
		$sql="select distinct b.user_id,b.first_name, b.last_name from ".USER_IN_PROJECT." a, ".TBL_USER." b where a.user_id=b.user_id";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		while($row = $this->db->fetch_array($record)){
		$cmp = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $row[first_name].' '.$row[last_name]);
		$contact_json .='{"caption":"'.$cmp.'","value":"'.$row[user_id].'"},';
		}
		$contact_json = '['.substr($contact_json,0,strlen($contact_json)-1).']';
		return $contact_json;
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} ///////end of function GetVendorJson

	function showProjectSearch(){
		$formName = "frm_project_search";
		?>
		<form name="<?php echo $formName;?>" method="post" action="">
			<table class="table" width="84%">
				<tr>
					<th>Customer</th>
					<th>User</th>
					<th>&nbsp;</th>
				</tr>
				<tr>
					<td>
						<select name="cust_id" id="cust_id" onChange="javascript: document.getElementById('customer_id').value='';
											for(i=0; i<document.getElementById('cust_id').length; i++){ 
												if(document.getElementById('cust_id')[i].selected==true){
												   document.getElementById('customer_id').value += 
												   document.getElementById('cust_id')[i].value+',';
												 }
											}
											document.getElementById('customer_id').value = 											                                            document.getElementById('customer_id').value.substr(0,
											document.getElementById('customer_id').value.length-1);"></select> 
						<input type="hidden" id="customer_id" name="customer_id" value="" />					
					</td>
					<td>
						<select name="u_id" id="u_id" onChange="javascript: document.getElementById('user_id').value='';
											for(i=0; i<document.getElementById('u_id').length; i++){ 
												if(document.getElementById('u_id')[i].selected==true){
												   document.getElementById('user_id').value += 
												   document.getElementById('u_id')[i].value+',';
												 }
											}
											document.getElementById('user_id').value = 											                                            document.getElementById('user_id').value.substr(0,
											document.getElementById('user_id').value.length-1);"></select> 
						<input type="hidden" id="user_id" name="user_id" value="" />
					</td>
					<?php /*?><td><input type="submit" name="search1" id="search1" value="Go"/></td><?php */?>
					<td>
						<a href="javascript:void(0);" onclick="javascript: <?php /*?>document.getElementById('search1').style.display='none';<?php */?>
																			document.getElementById('show_advance_search').style.display='block';">
							Advance Search</a>

					</td>
				</tr>
				<tr>
					<td>
						<div id="show_advance_search" style="display:none">
							<table class="table">
								<tr>
									<td colspan="2">
										<input type="checkbox" name="chk_unread" id="chk_unread" style="vertical-align:middle; width:0;"/>
																						Show Unread Messages
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<input type="checkbox" name="chk_important" id="chk_important" style="vertical-align:middle; width:0;" />
																						Important Only
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<input type="checkbox" name="chk_unclaimed" id="chk_unclaimed" style="vertical-align:middle; width:0;"/>
																						Show Unclaimed
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<input type="checkbox" name="chk_complete" id="chk_complete" style="vertical-align:middle; width:0;"/>
																						Show Completed Projects and Tasks 
									</td>
								</tr>								
								<tr>
									<td><input type="submit" name="search" value="Apply"/></td>
									<td>
										<input type="button" name="cancel" id="cancel" value="Hide" 
													onclick="javascript: document.getElementById('show_advance_search').style.display='none';"/>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>			
		</form>	
	<?php
	}
	
	function searchProjectList($customer_id='',$user_id='',$unread='',$imp_type='',$unclaimed='',$project_id='',$project_complete=''){
		ob_start();
	if($unclaimed=='claimed'){	
			$sql="select distinct a.project_id,a.title,a.due_date,a.importance_type_id,d.user_id,d.first_name as user_first_name,d.last_name as user_last_name,f.importance_type_id,f.importance_type_value";
			
			if($customer_id){
				$sql .=" ,b.first_name as contact_first_name, b.last_name as contact_last_name";
			}
				
			$sql .= " from ".PROJECT." a ,".IMPORTANCE_TYPE." f ,".TBL_USER." d, ".USER_IN_PROJECT." e ";
			
			if($customer_id){
				$sql .= " ,".TBL_CONTACT." b, ".CONTACT_IN_PROJECT." c";		
			}
			
			if($unread != ''){
				$sql .= " ,".TBL_NOTE_STATUS." s";
			}	
			
			$sql .= " where 1 ";
					
			if($project_id != ''){
				$sql .= " and a.importance_type_id = f.importance_type_id and a.user_id = d.user_id and a.complete <> '100' and a.due_date > '".date('Y-m-d')."' and a.project_id = '$project_id' and a.parent_project_id > '0'";
			}
			
			else if($project_complete == 'yes'){
				$sql .= " and a.complete = '100' and a.importance_type_id = f.importance_type_id and a.user_id = d.user_id";
			}				
						
			else{
				$sql .= " and a.importance_type_id = f.importance_type_id and a.user_id = d.user_id and a.complete <> '100' and a.parent_project_id = '0' and a.due_date > '".date('Y-m-d')."'";
			}

			if($customer_id !=''){
				$sql .= " and a.project_id = c.project_id and b.contact_id = c.contact_id and c.contact_id = '$customer_id'";
			}
			
			if($user_id != ''){
				$sql .= " and a.project_id = e.project_id and d.user_id = e.user_id and d.user_id = '$user_id'";
			}		

			if($unread != ''){
				$sql .= " and a.project_id = s.module_id and s.module_name = 'Project' and s.note_status = 'unread'";
			}	
			
			if($imp_type == 'important'){
				$sql .= " and a.importance_type_id = '1'";
			}
			
		}
		else{
	    	$sql = "select distinct a.project_id,a.title,a.importance_type_id,a.due_date,f.importance_type_value from ".PROJECT." a ,".IMPORTANCE_TYPE." f ";

			if($unread != ''){
				$sql .= " ,".TBL_NOTE_STATUS." s";
			}			
			
			$sql .= "where a.user_id = '0' and a.importance_type_id = f.importance_type_id";
			
			if($imp_type == 'important'){
				$sql .= " and a.importance_type_id = '1'";
			}
			if($unread != ''){
				$sql .= " and a.project_id = s.module_id and s.module_name = 'Project' and s.note_status = 'unread'";
			}	
		}
		
		//echo $sql;
		$result=$this->db->query($sql,__FILE__,__lINE__); 

		if($this->db->num_rows($result)){
			echo $this->showSlider($result,$unclaimed,$project_complete,$project_id);
			//echo $project_id;
			if($project_id !=''){
				echo $this->showSubProject($result,$unclaimed,$project_complete,$project_id);
			}
		}
		else {
			echo 'No Records Found !!!';
		}
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function showSlider($result,$unclaimed='',$project_complete='',$proj_id=''){
		ob_start();
		?>
		<script type="text/javascript">
			$(function() {
				$("#tree").treeview({
					collapsed: true,
					animated: "medium",
					control:"#sidetreecontrol",
					prerendered: true,
					persist: "location"
				});
			})
			
		</script>
		<div id="treecontrol">
			<a title="Collapse the entire tree below" href="#"> Collapse All</a> | 
			<a title="Expand the entire tree below" href="#"> Expand All</a> | 
			<a title="Toggle the tree below, opening closed branches, closing open branches" href="#">Toggle All</a>
		</div>
	
		<ul id="red" class="treeview-red">
		<?php 
		while($row=$this->db->fetch_array($result)){ 
		//if($row['project_id'] == $proj_id){
		?>
			<li><span>
				<table width="100%">
				<tr>
					<td width="4%">
					<span id="importance_<?php echo $row['project_id']; ?>">
						<?php echo $this->returnImportance($row['project_id'],$row['importance_type_id'],'project'); ?>
					</span>
					</td>
					<td width="36%"> 
					<a href="javascript:void(0);" onclick="project_new.showProjectDetails('local',
																'<?php echo $row['project_id']; ?>',
																{onUpdate: function(response,root){ 
																	document.getElementById('divProduct').innerHTML=response;
																	start_cal();
																	autosuggest2();
																	autosuggest3(); }});"><?php echo $row['title']; ?></a>
					</td>
					<td width="4%">&nbsp;</td>
					<td width="4%" align="left">
					<span id="notes<?php echo $row['project_id']; ?>">
						<?php
						$sql_note = "Select * from ".TBL_NOTE_STATUS." where module_id = '$row[project_id]' and module_name = 'Project'";
						$result_note=$this->db->query($sql_note,__FILE__,__lINE__);
						if($this->db->num_rows($result_note)>0){
							while($row_note = $this->db->fetch_array($result_note)){
								if($row_note['note_status']=='unread'){
									$note_status='unread';
									$note_id=$row_note['note_id'];
								}
								else if($row_note['note_status']=='read'){
									$note_status='read';
									$note_id=$row_note['note_id'];
								}									
							}
							echo $this->returnNoteLink($row['project_id'],$note_id,$note_status);
						}
						else{
							echo $this->returnNoteLink($row['project_id']);
						}
						?>
					</span>
					</td>
					<td width="4%" align="left">
					<span id="documents">
						<?php	
						$sql_doc = "Select * from ".PROJECT_DOCUMENT." where project_id = '$row[project_id]'";
						$result_doc=$this->db->query($sql_doc,__FILE__,__lINE__);
						while($row_doc=$this->db->fetch_array($result_doc)){ 
							echo $this->returnDocumentLink($row_doc['document_name'],$row_doc['document_server_name']);
						}  
						?>
					</span>	
					</td>	
					<td width="8%">&nbsp;</td>
					<td width="24%" align="right"> 				
					<span>
					<span style="color:#666666" id="span_user_id<?php echo $row['project_id']; ?>">
					<?php	if($row['user_first_name']!=''){
								 echo $this->returnLink($row['user_id'],$row['project_id'],$row['user_first_name'],$row['user_last_name']); 
							 }
							else{
								echo $this->returnLink('',$row['project_id'],'','');
							} ?>
					</span>
					</span>
					</td>
					<td width="2%">&nbsp;</td>						
					<td width="18%">
					<span>
					<?php 
					if($project_complete=='yes') echo "Completed";
					else if($unclaimed=='unclaimed') echo "Due Date : N/A";
					else{ ?>
								<span id="due_date"><?php echo $this->calcDueDay($row['due_date']);?></span>
							<?php  } ?>
					</span>
					</td>
					</tr>
				</table>
					</span>
						<ul>
						<li><span>
							<div id="div_task_project">
							<?php echo $this->TaskForProject('','','','','','','',1,'PROJECT',$row['project_id'],'','','',$project_complete);?>
							</div>
							</span>
						</li>
						<li>
							<span>
								<?php 
									$sql_sub = "Select connected_project_id from ".CONNECTED_PROJECT." where project_id = '$row[project_id]'";
									$result_sub=$this->db->query($sql_sub,__FILE__,__lINE__);
									if($this->db->num_rows($result_sub)){
										while($row_sub = $this->db->fetch_array($result_sub)){
											echo $this->searchProjectList('','','','','claimed',$row_sub['connected_project_id']); 
										}
									}	
								?>
							</span>
						</li>
					</ul>
				</li>
			
		<?php
			//}
		} // End of while
		?></ul>
		<?php 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
	function returnImportance($type_id='',$importance_type_id='',$type=''){
		ob_start();
	   	  if($importance_type_id == '1') { ?>
				<a href="javascript:void(0);"
       			onclick="javascript: project_new.returnImportance('<?php echo $type_id; ?>',
															      '3',
															      {preloader:'prl',
															      onUpdate: function(response,root){ 
															   	  document.getElementById('importance_<?php echo $type_id;?>').innerHTML=response;
																  project_new.setImportance('','',
																  							'<?php echo $type_id; ?>',
															    						    '3',
																							'<?php echo $type; ?>',
																							{preloader:'prl'});	
																}});">
				<img src="images/red_alert.png" /></a>		
		  <?php }
     	  if($importance_type_id == '3') { ?>
				<a href="javascript:void(0);" 
				onclick="javascript: project_new.returnImportance('<?php echo $type_id; ?>',
																  '4',
																  {preloader:'prl',
															      onUpdate: function(response,root){ 
															   	  document.getElementById('importance_<?php echo $type_id;?>').innerHTML=response;
																  project_new.setImportance('','',
																  							'<?php echo $type_id; ?>',
															    						    '',
																							'<?php echo $type; ?>',
																							{preloader:'prl'});	
																}});">
				<img src="images/orange_alert.png" /></a>		
		  <?php } 
     	  if($importance_type_id == '4' || $importance_type_id == '') { ?>
				<a href="javascript:void(0);" 
				onclick="javascript: project_new.returnImportance('<?php echo $type_id; ?>',
																  '1',
																  {preloader:'prl',
															      onUpdate: function(response,root){ 
															   	  document.getElementById('importance_<?php echo $type_id;?>').innerHTML=response;
																  project_new.setImportance('','',
																  							'<?php echo $type_id; ?>',
															    						    '1',
																							'<?php echo $type; ?>',
																							{preloader:'prl'});	
																}});">
				<img src="images/no_importance.JPG" /></a>		
		  <?php } 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function returnNoteLink($type_id='',$note_id='',$note_status='',$div=''){
		ob_start();
		//echo '$type_id'.$type_id.',$note_id'.$note_id.',$note_status'.$note_status.',$div'.$div;
		if($note_id!=''){
			if($note_status == 'read'){
				if($div == 'task_note'){ ?>
					<a href="javascript:void(0);" onclick="project_new.updateNoteStatus('local',
																						'<?php echo $type_id; ?>',
																						'<?php echo $note_id; ?>',
																						'','','',
																						'Task',
																						'task_note<?php echo $type_id; ?>',
																						{preloader:'prl',
																						target:'task_note<?php echo $type_id; ?>'});">
																						<img src="images/chat_bubble.gif" /></a>		
				
				<?php
				}
				if($div != 'task_note') { ?>
					<a href="javascript:void(0);" onclick="project_new.updateNoteStatus('local',
																						'<?php echo $type_id; ?>',
																						'<?php echo $note_id; ?>',
																						'','','',
																						'Project',
																						'notes<?php echo $type_id; ?>',
																						{preloader:'prl',
																						target:'notes<?php echo $type_id; ?>'});">
																						<img src="images/chat_bubble.gif" /></a>		
				<?php }
			}
			if($note_status == 'unread') {
				if($div == 'task_note'){ ?>
					<a href="javascript:void(0);" onclick="project_new.updateNoteStatus('local',
																						'<?php echo $type_id; ?>',
																						'<?php echo $note_id; ?>',
																						'','','',
																						'Task',
																						'task_note<?php echo $type_id; ?>',
																						{preloader:'prl',
																						target:'task_note<?php echo $type_id; ?>'});">
																						<img src="images/chat_bubble_unread.gif" /></a>		
				
				<?php
				}
				if($div != 'task_note') { ?>
					<a href="javascript:void(0);" onclick="project_new.updateNoteStatus('local',
																						'<?php echo $type_id; ?>',
																						'<?php echo $note_id; ?>',
																						'','','',
																						'Project',
																					    'notes<?php echo $type_id; ?>',
																						{preloader:'prl',
																						target:'notes<?php echo $type_id; ?>'});">
																						<img src="images/chat_bubble_unread.gif" /></a>		
				<?php }
			}
		}
		else { 
			if($div == 'task_note'){ ?>
				<a href="javascript:void(0);" onclick="project_new.updateNoteStatus('local',
																					'<?php echo $type_id; ?>',
																					'','','',
																					'add',
																					'Task',
																					'task_note<?php echo $type_id; ?>',
																					{preloader:'prl',target:'task_note<?php echo $type_id; ?>'});">
																					<img src="images/chat_bubble_unread.gif" /></a>		
			
			<?php
			}
			if($div != 'task_note') { ?>
				<a href="javascript:void(0);" onclick="project_new.updateNoteStatus('local',
																					'<?php echo $type_id; ?>',
																					'','','',
																					'add',
																					'Project',
																					'notes<?php echo $type_id; ?>',
																					{preloader:'prl',
																					target:'notes<?php echo $type_id; ?>'});">
																					<img src="images/chat_bubble_unread.gif" /></a>			
		<?php }
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} 
	
	function returnDocumentLink($document_name='',$document_server_name='',$type=''){
		ob_start();
		if($type==''){
			$array=explode(".",$document_name);
			$extension = $array[1];
			if($extension == 'pdf' || $extension == 'PDF') { ?>
				<a href="uploads/<?php echo $document_server_name; ?>" target="_blank"><img src="images/pdf.jpg" border="0"  align="absmiddle"/></a>
			<?php }
			else if($extension == 'xlsx' || $extension == 'XLSX' || $extension == 'xls' || $extension == 'XLS'){ ?>
				<a href="uploads/<?php echo $document_server_name; ?>" target="_blank"><img src="images/csv.jpg" border="0"  align="absmiddle"/></a>								
			<?php }
			else if($extension == 'txt' || $extension == 'TXT') { ?>
				<a href="uploads/<?php echo $document_server_name; ?>" target="_blank"><img src="images/pdf.jpg" border="0"  align="absmiddle"/></a>
			<?php }
			else{ ?>
				<a href="uploads/<?php echo $document_server_name; ?>" target="_blank"><img src="images/doc.png" border="0"  align="absmiddle"/></a>
			<?php }			
		}
		else{
			$array=explode(".",$document_name);
			$extension = $array[1];
			if($extension == 'pdf' || $extension == 'PDF') { ?>
				<a href="uploads/<?php echo $document_server_name; ?>" target="_blank">
					<img src="images/pdf_gray.jpg" border="0"  align="absmiddle"/></a>
			<?php }
			else if($extension == 'xlsx' || $extension == 'XLSX' || $extension == 'xls' || $extension == 'XLS'){ ?>
				<a href="uploads/<?php echo $document_server_name; ?>" target="_blank">
					<img src="images/csv_gray.jpg" border="0"  align="absmiddle"/></a>								
			<?php }
			else if($extension == 'txt' || $extension == 'TXT') { ?>
				<a href="uploads/<?php echo $document_server_name; ?>" target="_blank">
					<img src="images/pdf_gray.jpg" border="0"  align="absmiddle"/></a>
			<?php }
			else{ ?>
				<a href="uploads/<?php echo $document_server_name; ?>" target="_blank">
					<img src="images/doc_gray.PNG" border="0"  align="absmiddle"/></a>
			<?php }					
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} 
	
	function calcDueDay($due_date=''){
		
		ob_start();
		$date1 = $due_date;  		
		$date2 = date('Y-m-d');		
		$diff = abs(strtotime($date2) - strtotime($date1));	
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
	
		if($years!=0 && $months!=0 && $days!=0){
			$dat2 = $years*365 + $months*30 + $days;
			
			$x = floor($dat2/7); 
			$y = $x * 2; 
			$ttr = $dat2 - $y;
			
			printf(" Due:%d days",$ttr);
			//printf(' Due:%d years,%d months,%d days',$years, $months, $days);
		}
		else if($years==0 && $months!=0 && $days!=0)
		{
			$dat2 = $months*30 + $days;
			
			$x = floor($dat2/7); 
			$y = $x * 2; 
			$ttr = $dat2 - $y;
			$a = floor($ttr/30);
			if($a==1){
				$mon = $ttr - 30;
				
				if($mon>0){
					printf(" Due:%d month, %d day ",$a, $mon);
				}
				else{
					printf(" Due:%d days",$a);
				}
			}
			else if($a > 1){
				$tt = $ttr;
				for($i=0;$i<$a;$i++){
					$tt = $tt - 30;
				}
				$mon = $tt;
				if($mon>0){
					printf(" Due:%d month, %d day ",$a, $mon);
				}
				else{
					printf(" Due:%d days",$a);
				}
			}
			else{
				printf(" Due:%d days",$ttr);
			}
			//printf(" Due:%d months,%d days",$months, $days);
		}
		else if($years!=0 && $months==0 && $days!=0)
		{
			$dat2 = $years*365 + $days;
			
			$x = floor($dat2/7); 
			$y = $x * 2; 
			$ttr = $dat2 - $y;
			
			printf(" Due:%d days",$ttr);
			//printf(" Due:%d years,%d days",$years, $days);
		}
		else if($months==0 && $days!=0)
		{
			$dat2 = $days;
			if($dat2 > 5){
				$x = floor($dat2/7); 
				$y = $x * 2; 
				$ttr = $dat2 - $y;
			}
			else if($dat2 <= 5 && $dat2 >2){
				$ttr = $dat2 - 2;
			}
			else if($dat2 <= 2){
				$ttr = $dat2;
			}
			printf(" Due:%d days",$ttr);
		}
		else if($months!=0 && $years==0 && $days == 0){
			$dat2 = $months*30;
			
			$x = floor($dat2/7); 
			$y = $x * 2; 
			$ttr = $dat2 - $y;
			$a = floor($ttr/30);
			if($a==1){
				$mon = $ttr - 30;
				printf(" Due:%d month, %d day",$a, $mon);
			}
			else if($a > 1){
				$tt = $ttr;
				for($i=0;$i<$a;$i++){
					$tt = $tt - 30;
				}
				$mon = $tt;
				printf(" Due:%d month, %d day ",$a, $mon);
			}
			else{
				printf(" Due:%d days",$ttr);
			}
		}
		
		$html = ob_get_contents();

		ob_end_clean();
		return $html;
	}	
	
	function showProjectDetails($runat,$project_id=''){
		ob_start();
		
		$sql = "Select * from ".PROJECT." where project_id = '$project_id'";
		$result=$this->db->query($sql,__FILE__,__lINE__); 
		$row=$this->db->fetch_array($result);

		switch($runat){
			case 'local':
				$formName="projectDetails";
				$ControlNames=array("project_name"		=>array('project_name',"''","Please Enter Project Title !! ","title_span")
									);
				$ValidationFunctionName="CheckValidity";
				$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($formName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
				echo $JsCodeForFormValidation;
				?>		
				<form name="<?php echo $formName; ?>" action="" method="post" enctype="multipart/form-data">
				<ul id="error_list">
				  	<li><span id="title_span" class="normal"></span></li>
				</ul>

				
				<input type="hidden" name="project_id" id="project_id" value="<?php echo $project_id; ?>" />

				<table class="table" width="30%" align="left" style="background-color:#eee;">
					<tr>
						<td colspan="2">Customer
							<select name="edit_cust_id" id="edit_cust_id" onChange="javascript: document.getElementById('edit_customer_id').value='';
												for(i=0; i<document.getElementById('edit_cust_id').length; i++){ 
													if(document.getElementById('edit_cust_id')[i].selected==true){
													   document.getElementById('edit_customer_id').value += 
													   document.getElementById('edit_cust_id')[i].value+',';
													 }
												}
												document.getElementById('edit_customer_id').value = 											                                            document.getElementById('edit_customer_id').value.substr(0,
												document.getElementById('edit_customer_id').value.length-1);"></select> 
							<input type="hidden" id="edit_customer_id" name="edit_customer_id" value="" />					
						</td>
						<?php
						$sql_user="select * from ".TBL_USER." where user_id='$row[user_id]'";
						$record_user=$this->db->query($sql_user,__LINE__,__FILE__);
						$row_user = $this->db->fetch_array($record_user);
						?>
					</tr>
					<tr>
						<td colspan="2">User
							<select name="edit_u_id" id="edit_u_id" onChange="javascript: document.getElementById('edit_user_id').value='';
												for(i=0; i<document.getElementById('edit_u_id').length; i++){ 
													if(document.getElementById('edit_u_id')[i].selected==true){
													   document.getElementById('edit_user_id').value += 
													   document.getElementById('edit_u_id')[i].value+',';
													 }
												}
												document.getElementById('edit_user_id').value = 											                                            document.getElementById('edit_user_id').value.substr(0,
												document.getElementById('edit_user_id').value.length-1); ">
								<?php  if($row_user['user_id'] !='') { ?>
								<option value="<?php echo $row_user['user_id']; ?>">
									<?php echo $row_user['first_name'].' '.$row_user['last_name']; ?>
								</option>
								<?php } ?>
							</select> 
							<input type="hidden" id="edit_user_id" name="edit_user_id" value="<?php if($row_user['user_id'] !='') echo $row_user['user_id']; else echo ''; ?>" />
						</td>
					</tr>
					<tr>
						<td>Project Name</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><input type="text" name="project_name" id="project_name" style="width:300px;" value="<?php echo $row['title']; ?>"/></td>
					</tr>
					<tr>
						<td>Comment</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><textarea name="comment" id="comment" rows="10" cols="36"><?php echo $row['description']; ?></textarea></td>
					</tr>
					<tr>
						<td colspan="2">When's it due : <input type="text" name="due_dt" id="due_dt" readonly="true" style="width:auto" autocomplete='off' value="<?php echo $row['due_date']; ?>"/>
							<script type="text/javascript">
			
							function start_cal(){
								new Calendar({
								
									inputField	 : "due_dt",
									dateFormat	 : "%Y-%m-%d",
									trigger 	 : "due_dt",
									weekNumbers  : true,
									bottomBar    : true,
									onSelect 	: function() {
														var day = Array;
														day = this.selection.getDates();
														var partA = day.toString().split(' ');
														if(partA[0] == 'Sun' || partA[0] == 'Sat')
														{
															alert('You cannot have weekends as your due date');
															return true;
															document.<?php echo $formName;?>.due_dt.value = '';
														}
														else{
															this.hide();
															document.<?php echo $formName;?>.due_dt.value=this.selection.print("%Y/%m/%d");
														}
													},
								});
							}
							start_cal();
							
							</script>				  
						</td>
					</tr>
					<tr>
						<td colspan="2"><input type="file" name="myfile"></td>
					</tr>
					<tr>
						<td><input type="submit" name="add_project" id="add_project" style="width:auto;" value="<?php if($project_id!='') echo 'Update'; else echo 'Add This Project' ; ?>" onclick="return <?php echo $ValidationFunctionName?>();" /></td>
					</tr>
					<tr>
						<td><?php /*?><a href="javascript:void(0);">Advance</a><?php */?></td>
						<?php if($project_id !=''){ ?>
						<td><a href="javascript:void(0);" 
						onclick="javascript:project_new.showTaskDetails('local',
																	    '',
																		document.getElementById('project_id').value,
																	    {onUpdate: function(response,root){ 
																			document.getElementById('divAddTask').innerHTML=response;
																			document.getElementById('divAddTask').style.display='block';
																			autosuggest4();
																			start_cal(); }});">Add Task to Project</a></td>
						<?php } ?>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<?php if($project_id !=''){ ?>
						<td><a href="javascript:void(0);" 
						onclick="javascript:project_new.showConnectedProject('local',
																		document.getElementById('project_id').value,
																	    {onUpdate: function(response,root){ 
																			document.getElementById('divAddTask').innerHTML=response;
																			document.getElementById('divAddTask').style.display='block';
																			start_cal();
																			autosuggest5();
																			autosuggest6();
																			 }});">Add Connected Project</a></td>
						<?php } ?>
					</tr>
				</table>
				</form>			
				<?php
				break;
		
		case 'server':
			extract($_POST);
				if($_POST['edit_user_id'] == '')
					$user_id = '0';
				else 
					$user_id = $_POST['edit_user_id'];
					
					
				$insert_sql_array = array();
				$insert_sql_array[title] = $_POST['project_name'];
				$insert_sql_array[description] = $_POST['comment'];
				$insert_sql_array[due_date] = $_POST['due_dt'];
				$insert_sql_array[importance_type_id] = '4';
				$insert_sql_array[user_id] = $user_id;
				$insert_sql_array[started] = date('Y-m-d');
				if($project_id!=''){
					$this->db->update(PROJECT,$insert_sql_array,'project_id',$project_id);
					$last_project_id = $project_id;
				}
				else{
					$this->db->insert(PROJECT,$insert_sql_array);
					$last_project_id = $this->db->last_insert_id();	
				}	
				
				$document_name = $_FILES['myfile']['name'];
				
				$file_array = explode(".",$document_name);
				$file_ext = end($file_array);
				$document_server_name = uniqid().date('m').date('d').date('Y').date('G').date('i').date('s').".".$file_ext;
				
				$target_path = 'uploads/'. basename( $document_server_name);
				move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path);		
				
				$insert_sql_array = array();
				$insert_sql_array[project_id] = $last_project_id;
				$insert_sql_array[document_name] = $document_name;
				$insert_sql_array[document_server_name] = $document_server_name;	
				$insert_sql_array[user_id] = $user_id;	
				$this->db->insert(PROJECT_DOCUMENT,$insert_sql_array);
				
				$insert_sql_array = array();
				$insert_sql_array[project_id] = $last_project_id;
				$insert_sql_array[user_id] = $user_id;	
				$this->db->insert(USER_IN_PROJECT,$insert_sql_array);				
				?>
				<script>
					window.location = "<?php echo $_SERVER['PHP_SELF']; ?>";
				</script>
				<?php	
			break;
			
		default: break;
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function showConnectedProject($runat,$parent_project_id=''){
		ob_start();
		
		switch($runat){
			case 'local':
				$formName="projectDetails";
				$ControlNames=array("project_name"		=>array('project_name',"''","Please Enter Project Title !! ","title_span")
									);
				$ValidationFunctionName="CheckValidity";
				$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($formName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
				echo $JsCodeForFormValidation;
				?>		
				<form name="<?php echo $formName; ?>" action="" method="post" enctype="multipart/form-data">
				<ul id="error_list">
				  	<li><span id="title_span" class="normal"></span></li>
				</ul>

				
				<input type="text" name="parent_project_id" id="parent_project_id" value="<?php echo $parent_project_id; ?>" />

				<table class="table" width="30%" align="left" style="background-color:#eee;">
					<tr>
						<td colspan="2">Customer
							<select name="conn_edit_cust_id" id="conn_edit_cust_id" onChange="javascript: document.getElementById('conn_edit_customer_id').value='';
												for(i=0; i<document.getElementById('conn_edit_cust_id').length; i++){ 
													if(document.getElementById('conn_edit_cust_id')[i].selected==true){
													   document.getElementById('conn_edit_customer_id').value += 
													   document.getElementById('conn_edit_cust_id')[i].value+',';
													 }
												}
												document.getElementById('conn_edit_customer_id').value = 											                                            document.getElementById('conn_edit_customer_id').value.substr(0,
												document.getElementById('conn_edit_customer_id').value.length-1);"></select> 
							<input type="hidden" id="conn_edit_customer_id" name="conn_edit_customer_id" value="" />					
						</td>
						<?php
						$sql_user="select * from ".TBL_USER." where user_id='$row[user_id]'";
						$record_user=$this->db->query($sql_user,__LINE__,__FILE__);
						$row_user = $this->db->fetch_array($record_user);
						?>
					</tr>
					<tr>
						<td colspan="2">User
							<select name="conn_edit_u_id" id="conn_edit_u_id" onChange="javascript: document.getElementById('conn_edit_u_id').value='';
												for(i=0; i<document.getElementById('conn_edit_u_id').length; i++){ 
													if(document.getElementById('conn_edit_u_id')[i].selected==true){
													   document.getElementById('conn_edit_user_id').value += 
													   document.getElementById('conn_edit_u_id')[i].value+',';
													 }
												}
												document.getElementById('conn_edit_user_id').value = 											                                            document.getElementById('conn_edit_user_id').value.substr(0,
												document.getElementById('conn_edit_user_id').value.length-1); ">
								<?php  if($row_user['user_id'] !='') { ?>
								<option value="<?php echo $row_user['user_id']; ?>">
									<?php echo $row_user['first_name'].' '.$row_user['last_name']; ?>
								</option>
								<?php } ?>
							</select> 
							<input type="hidden" id="conn_edit_user_id" name="conn_edit_user_id" value="" />
						</td>
					</tr>
					<tr>
						<td>Project Name</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><input type="text" name="project_name" id="project_name" style="width:300px;" value=""/></td>
					</tr>
					<tr>
						<td>Comment</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><textarea name="comment" id="comment" rows="10" cols="36"></textarea></td>
					</tr>
					<tr>
						<td colspan="2">When's it due : <input type="text" name="due_dt2" id="due_dt2" readonly="true" style="width:auto" autocomplete='off'/>
							<script type="text/javascript">
			
							function start_cal(){
								new Calendar({
								
									inputField	 : "due_dt2",
									dateFormat	 : "%Y-%m-%d",
									trigger 	 : "due_dt2",
									weekNumbers  : true,
									bottomBar    : true,
									onSelect 	: function() {
														var day = Array;
														day = this.selection.getDates();
														var partA = day.toString().split(' ');
														if(partA[0] == 'Sun' || partA[0] == 'Sat')
														{
															alert('You cannot have weekends as your due date');
															return true;
															document.<?php echo $formName;?>.due_dt2.value = '';
														}
														else{
															this.hide();
															document.<?php echo $formName;?>.due_dt2.value=this.selection.print("%Y/%m/%d");
														}
													},
								});
							}
							start_cal();
							
							</script>				  
						</td>
					</tr>
					<tr>
						<td colspan="2"><input type="file" name="myfile"></td>
					</tr>
					<tr>
						<td><input type="submit" name="add_project" id="add_project" style="width:auto;" value="Add Connected Project" onclick="return <?php echo $ValidationFunctionName?>();" /></td>
					</tr>
				</table>
				</form>			
				<?php
				break;
		
		case 'server':
			extract($_POST);
				if($_POST['conn_edit_user_id'] == '')
					$user_id = '0';
				else 
					$user_id = $_POST['conn_edit_user_id'];
					
					
				$insert_sql_array = array();
				$insert_sql_array[title] = $_POST['project_name'];
				$insert_sql_array[description] = $_POST['comment'];
				$insert_sql_array[due_date] = $_POST['due_dt2'];
				$insert_sql_array[importance_type_id] = '4';
				$insert_sql_array[user_id] = $user_id;
				$insert_sql_array[started] = date('Y-m-d');
				$insert_sql_array[parent_project_id] = $_POST['parent_project_id'];
				$this->db->insert(PROJECT,$insert_sql_array);
				$last_project_id = $this->db->last_insert_id();	
								
				$document_name = $_FILES['myfile']['name'];
				
				$file_array = explode(".",$document_name);
				$file_ext = end($file_array);
				$document_server_name = uniqid().date('m').date('d').date('Y').date('G').date('i').date('s').".".$file_ext;
				
				$target_path = 'uploads/'. basename( $document_server_name);
				move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path);		
				
				$insert_sql_array = array();
				$insert_sql_array[project_id] = $last_project_id;
				$insert_sql_array[document_name] = $document_name;
				$insert_sql_array[document_server_name] = $document_server_name;	
				$insert_sql_array[user_id] = $user_id;	
				$this->db->insert(PROJECT_DOCUMENT,$insert_sql_array);
				
				$insert_sql_array = array();
				$insert_sql_array[connected_project_id] = $last_project_id;
				$insert_sql_array[project_id] = $_POST['parent_project_id'];
				$this->db->insert(CONNECTED_PROJECT,$insert_sql_array);				
				
				$insert_sql_array = array();
				$insert_sql_array[project_id] = $last_project_id;
				$insert_sql_array[user_id] = $user_id;	
				$this->db->insert(USER_IN_PROJECT,$insert_sql_array);
				?>
				<script>
					window.location = "<?php echo $_SERVER['PHP_SELF']; ?>";
				</script>
				<?php	
			break;
			
		default: break;
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
	function showTaskDetails($runat,$task_id='',$project_id=''){
		ob_start();
		$sql = "Select * from ".TASKS." where task_id = '$task_id'";
		$result=$this->db->query($sql,__FILE__,__lINE__); 
		$row=$this->db->fetch_array($result);

		switch($runat){
			case 'local':
				$formName="taskDetails";
				$ControlNames=array("task_name"		=>array('task_name',"''","Please Enter Task Title !! ","title_span")
									);
				$ValidationFunctionName="CheckValidity";
				$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($formName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
				echo $JsCodeForFormValidation;
				?>		
				<form name="<?php echo $formName; ?>" action="" method="post" enctype="multipart/form-data">
				<ul id="error_list">
				  	<li><span id="title_span" class="normal"></span></li>
				</ul>

				<input type="hidden" name="project_id" id="project_id" value="<?php echo $project_id; ?>" />
				<input type="hidden" name="task_id" id="task_id" value="<?php echo $task_id; ?>" />
 				<h2><?php if($task_id!='') echo 'Edit Task'; else echo 'Add Task';?></h2>				
 				<table class="table" width="30%" align="left"  style="background-color:#eee;">
					<tr>
						<th>User</th>
						<th>&nbsp;</th>
					</tr>
					<tr>
						<?php
						$sql_user="select * from ".TBL_USER." where user_id='$row[user_id]'";
						$record_user=$this->db->query($sql_user,__LINE__,__FILE__);
						$row_user = $this->db->fetch_array($record_user);
						?>
						<td>
							<select name="task_edit_u_id" id="task_edit_u_id" 
							onChange="javascript: document.getElementById('task_edit_user_id').value='';
												for(i=0; i<document.getElementById('task_edit_u_id').length; i++){ 
													if(document.getElementById('task_edit_u_id')[i].selected==true){
													   document.getElementById('task_edit_user_id').value += 
													   document.getElementById('task_edit_u_id')[i].value+',';
													 }
												}
												document.getElementById('task_edit_user_id').value = 											                                            document.getElementById('task_edit_user_id').value.substr(0,
												document.getElementById('task_edit_user_id').value.length-1);">
								<?php  if($row_user['user_id'] !='') { ?>
								<option value="<?php echo $row_user['user_id']; ?>">
									<?php echo $row_user['first_name'].' '.$row_user['last_name']; ?>
								</option>
								<?php } ?>
							</select> 
							<input type="hidden" id="task_edit_user_id" name="task_edit_user_id" value="<?php if($row_user['user_id'] !='') echo $row_user['user_id']; else echo ''; ?>" />
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Task</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><input type="text" name="task_name" id="task_name" style="width:300px;" value="<?php echo $row['title']; ?>"/></td>
					</tr>
					<tr>
						<td>Comment</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><textarea name="comment" id="comment" rows="10" cols="36"><?php echo $row['comment']; ?></textarea></td>
					</tr>
					<tr>
						<td colspan="2">When's it due : <input type="text" name="due_dt1" id="due_dt1" readonly="true" style="width:auto" autocomplete='off' value="<?php echo $row['due_date']; ?>"/>
							<script type="text/javascript">
							function start_cal(){
								new Calendar({
								
									inputField	 : "due_dt1",
									dateFormat	 : "%Y-%m-%d",
									trigger 	 : "due_dt1",
									weekNumbers  : true,
									bottomBar    : true,
									onSelect 	: function() {
														var day = Array;
														day = this.selection.getDates();
														var partA = day.toString().split(' ');
														if(partA[0] == 'Sun' || partA[0] == 'Sat')
														{
															alert('You cannot have weekends as your due date');															
															document.<?php echo $formName;?>.due_dt1.value = '';
															return true;
														}
														else{
															this.hide();
															document.<?php echo $formName;?>.due_dt1.value=this.selection.print("%Y/%m/%d");
														}
													},
								});
							}
							start_cal();
							</script>				  
						</td>
					</tr>
					<tr>
						<td>
							<span>
							<select name="cat_id" id="cat_id" 
							onChange="if(this.value=='NewCat'){ 
										category = prompt('Enter name of category','');
										if(category!=null){
											if(category.length>0){
												task.Add_Fly_Cat('server',
																  category,
																  '#0000FF',
																  {onUpdate: function(response,root){  
																	if(response==1)
																		task.GetTaskCategoryJson({content_type:'json',
																								 target:'cat_id', 
																								 preloader:'prl'});
																	else {
																		alert('Sorry !! category with name '+category+' already exists');
																		document.getElementById('cat_id').options[0].selected = true;
																		document.getElementById('cat_id').selectedIndex=0;
																		return true;
																	}}});
										    }
										    else{
												this.options[0].selected = true;
												this.selectedIndex=0;
												return true;
											} 
										}
										else {
											this.options[0].selected = true;
											this.selectedIndex=0;
											return true;
										}
									}">
								<option value="">select category</option>
								<?php
								$sql_cat="select * from ".TASKS_CATEGORY." order by name";
								$result_cat=$this->db->query($sql_cat,__FILE__,__LINE__);
								while($row_cat=$this->db->fetch_array($result_cat)){
								?>
								<option value="<?php echo $row_cat['cat_id'];?>" <?php if($row['cat_id']==$row_cat['cat_id']) echo "selected = 'selected'"; ?>><?php echo $row_cat['name']; ?></option>
								 <?php
								 }
								 ?>
								 <option value="NewCat" >new category</option>
							</select>
							</span>&nbsp;
							<span>
								<a href="categoryManagement.php">edit categories</a>
							</span>
							<span id="spancat_id"></span>
						</td>
					</tr>
					<tr>
						<td colspan="2"><input type="file" name="myfile"></td>
					</tr>
					<tr>
						<td><input type="submit" name="add_task" id="add_task" style="width:auto;" value="<?php if($task_id!='') echo 'Update'; else echo 'Add This Task' ; ?>" onclick="return <?php echo $ValidationFunctionName?>();" /></td>
					</tr>
				</table>
				</form>			
				<?php
				break;
		
		case 'server':
			extract($_POST);
				if($_POST['task_edit_user_id'] == '')
					$user_id = '0';
				else 
					$user_id = $_POST['task_edit_user_id'];

				$document_name = $_FILES['myfile']['name'];
				
				$file_array = explode(".",$document_name);
				$file_ext = end($file_array);
				$document_server_name = uniqid().date('m').date('d').date('Y').date('G').date('i').date('s').".".$file_ext;
				
				$target_path = 'uploads/'. basename( $document_server_name);
				move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path);		

				$insert_sql_array = array();
				$insert_sql_array[title] = $_POST['task_name'];
				$insert_sql_array[comment] = $_POST['comment'];
				$insert_sql_array[importance_type_id] = '4';
				$insert_sql_array[due_date] = strtotime($due_dt1);
				$insert_sql_array[doc_name] = $document_name;
				$insert_sql_array[doc_server_name] = $document_server_name;	
				$insert_sql_array[user_id] = $user_id;	
				$insert_sql_array[cat_id] = $cat_id;	
				
				if($task_id!=''){
					$this->db->update(TASKS,$insert_sql_array,'task_id',$task_id);
					$last_task_id = $task_id;
				}
				else{
					$this->db->insert(TASKS,$insert_sql_array);
					$last_task_id = $this->db->last_insert_id();	
					
					$insert_sql_array = array();
					$insert_sql_array['task_id'] = $last_task_id;
					$insert_sql_array['module'] = "Project";
					$insert_sql_array['module_id'] = $project_id;
					$insert_sql_array['profile_page'] = "project_profile.php";
					$insert_sql_array['profile_id'] = "project_id";
					$this->db->insert(ASSIGN_TASK,$insert_sql_array);
					
					$insert_sql_array = array();
					$insert_sql_array['task_id'] = $last_task_id;
					$insert_sql_array['module'] = "TBL_USER";
					$insert_sql_array['module_id'] = $task_edit_user_id;
					$this->db->insert("task_relation",$insert_sql_array);				
					?>
					<script>
						window.location = "<?php echo $_SERVER['PHP_SELF']; ?>";
					</script>
					<?php				
				}
			break;
			
		default: break;

		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	
	
	function TaskForProject($module_id='', $cat_id='', $completed='', $upcomming='', $assigned='', $tagModule='', $tagModule_id='',$listonly=0,$assigned_to_module='',$assigned_to_module_id='',$assigned_to='',$module_name='',$include_all_visible=0,$complete_task='')
	{
		//either it is assigned to user or it belong to user's group
		ob_start();
		$filter='';
		$sql="select distinct a.*,b.*,c.module as rel_module,c.module_id as rel_module_id from ".TASKS." a, ".ASSIGN_TASK." b,".TASK_RELATION." c where a.task_id=b.task_id and a.task_id=c.task_id";
		if($completed=='' and $upcomming=='' and $assigned=='' and $assigned_to=='')
			$upcomming='yes';
		
		if($tagModule_id!=''){
		 $sql.=" and a.task_id=$tagModule_id";
		}
		if($module_id!=''){
		 $sql.=" and c.module_id='$module_id' and c.module='$module_name'";
		}
		
		if($cat_id!=''){
		 $sql.=" and a.cat_id=$cat_id";
		}
		
		if($assigned!=''){
		 $filter='assigned:';
		 $sql.=" and a.user_id=$assigned";
		}
		
		if($assigned_to_module!='' and $assigned_to_module_id!=''){
		 $sql.=" and b.module='$assigned_to_module' and b.module_id='$assigned_to_module_id' ";
		}
		
		if($assigned_to!=''){
		 $filter='assigned to me:';
		 $sql.=" and c.module_id='$assigned_to' ";
		}
		
		$sql.="order by due_date asc";
		//echo $sql;
		
        $mm=0;
		$overdue = array();
		$dueToday = array();
		$dueTomorrow = array();
		$dueThisWeek = array();
		$dueNextWeek = array();
		$dueThisMonth = array();
		$dueNextMonth = array();
		$dueLaterThisYear = array();
		$dueNextYear= array();
		$later = array(); 
		$task_id_array = array();
		
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0){
			$this->showTaskForProject($record,$listonly,$assigned_to_module,$assigned_to_module_id,$assigned_to,$upcomming,$completed,'',$complete_task);
		} 
		
		else {
			$filter = 'no tasks';
			echo 'No Tasks';
		}
	 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function showTaskForProject($record,$listonly=0,$assigned_to_module='',$assigned_to_module_id='',$assigned_to='',$upcomming='',$completed='',$showday='',$complete_task='')
	{?>
	  <table id="display_search" class="event_form small_text" width="100%">
	   <tbody>	
	   <?php
		$i = 1; 
		while($row=$this->db->fetch_array($record))	{
			if($i%2==0){ ?>
				<tr class="even">
			<?php }
			else { ?>
				<tr class="odd">
			<?php }?>
		<td>	 
			<div id="divmaintask_<?php echo $row[task_id];?>">
			<?php 
			if($complete_task!='yes'){
				if($row['completed']=='Yes'){ ?>
					<div style="color:#CCCCCC" id="taskmain<?php echo $row[task_id];?>" <?php if(!$listonly) { ?>
												onmouseover="document.getElementById('<?php echo $row[task_id];?>').style.display='';
															 document.getElementById('task_action_<?php echo $row[task_id];?>').style.display=''; " 
												onmouseout="document.getElementById('<?php echo $row[task_id];?>').style.display='none'; 
														   document.getElementById('task_action_<?php echo $row[task_id];?>').style.display='none';" 			
													   <?php } ?> class="<?php if(!$listonly) echo 'task_padding'; ?>">
				<?php if(!$listonly) { ?>
						<span id="task_action_<?php echo $row[task_id];?>"  style="display:none; " class="task_action">				
								<a href="edit_task.php?task_id=<?php echo $row[task_id]; ?>" onClick="">
								<img src="images/edit.gif" border="0"  align="absmiddle"/></a>&nbsp;
								<a href="#" onClick="if(confirm('Are you sure ?')){	task.DeleteTask(<?php echo $row[task_id]; ?>,
															   {onUpdate: function(response,root)
															   {document.getElementById('divmaintask_<?php echo $row[task_id];?>').innerHTML='';},
																target: 'taskmain<?php echo $row[task_id];?>', preloader:'prl'});
													 } else return false;">
								<img src="images/trash.gif" border="0"  align="absmiddle"/>&nbsp;</a>
						</span>
				<?php } ?>
				<table width="100%">
					<tr>
						<td width="2%">					
							<input type="checkbox" name="chk_task[]" id="chk_task[]" disabled="disabled" checked="checked">
						</td>
						<td width="2%">&nbsp;</td>
						<td width="27%" align="left">
						   <span style="font-weight:bold; font-size:16px" id="taskdiv<?php echo $row[task_id];?>" class="complete" >
						 	  <span class="small_text"><?php echo $row['title']; ?></span>
							</span>
						</td>			   
						<td width="10%" align="left">
							<span class="complete" style="margin-left:70px; margin-right:20px">
								<img src="images/chat_bubble.gif" />
								<?php
								/*$sql_note = "Select * from ".TBL_NOTE_STATUS." where module_id = '$row[task_id]' and module_name = 'Task'";
								$result_note=$this->db->query($sql_note,__FILE__,__lINE__);
								if($this->db->num_rows($result_note)>0){
									while($row_note = $this->db->fetch_array($result_note)){
										if($row_note['note_status']=='unread'){
											$note_status='unread';
											$note_id=$row_note['note_id'];
										}
										else if($row_note['note_status']=='read'){
											$note_status='read';
											$note_id=$row_note['note_id'];
										}	
									}				
									echo $this->returnNoteLink($row['task_id'],$note_id,$note_status,'task');
								}
								else{
									echo $this->returnNoteLink($row['project_id']);
								}					
								*/?>
							</span>
						</td>
						<td width="10%">
							<span id="task_document">
								<?php					  
								$sql_doc = "Select * from ".TASKS." where task_id = '$row[task_id]'";
								$result_doc=$this->db->query($sql_doc,__FILE__,__lINE__);
								while($row_doc=$this->db->fetch_array($result_doc)){ 
									echo $this->returnDocumentLink($row_doc['doc_name'],$row_doc['doc_server_name'],'complete');
								}  
								?>
							</span>	
						</td>
						<td width="35%" align="right">							
							<span class="complete" style="float:right">
								<span class="complete">
								<?php 
								if($assigned_to!='' or $upcomming!='') {
									$user = new User();
									echo $user->GetUserNameById($row['completed_by']);					
								 } ?>
								 </span>
							 </span>
						</td>
					</tr>
				</table>				 
			</div>	
		  <?php }  // End of If Condition
		 if($row['completed']=='No') { ?>
			<div id="taskmain<?php echo $row[task_id];?>" <?php if(!$listonly) { ?>
									onmouseover="document.getElementById('<?php echo $row[task_id];?>').style.display='';
												 document.getElementById('task_action_<?php echo $row[task_id];?>').style.display=''; " 
									onmouseout="document.getElementById('<?php echo $row[task_id];?>').style.display='none'; 
											   document.getElementById('task_action_<?php echo $row[task_id];?>').style.display='none';" 			
										   <?php } ?> class="<?php if(!$listonly) echo 'task_padding'; ?>">
				<?php if(!$listonly) { ?>
						<span id="task_action_<?php echo $row[task_id];?>"  style="display:none; " class="task_action">				
								<a href="edit_task.php?task_id=<?php echo $row[task_id]; ?>" onClick="">
								<img src="images/edit.gif" border="0"  align="absmiddle"/></a>&nbsp;
								<a href="#" onClick="if(confirm('Are you sure ?')){	task.DeleteTask(<?php echo $row[task_id]; ?>,
															   {onUpdate: function(response,root)
															   {document.getElementById('divmaintask_<?php echo $row[task_id];?>').innerHTML='';},
																target: 'taskmain<?php echo $row[task_id];?>', preloader:'prl'});
													 } else return false;">
								<img src="images/trash.gif" border="0"  align="absmiddle"/>&nbsp;</a>
						</span>
				<?php } ?>					
				<table class="table" width="100%">
					<tr>
						<td width="2%">	
							<input type="checkbox" name="chk_task[]" id="chk_task[]" onClick="javascript: 
								document.getElementById('taskdiv<?php echo $row[task_id];?>').className=<?php if($row[completed]=='Yes') {
																												 ?>'' <?php 
																											  }else {  
																												  ?>'complete'<?php 
																											  } ?>; 
										task.Complete_Task(<?php echo $row[task_id]; ?>,  
										<?php if(!$listonly) { ?>
												document.getElementById('contact_id').value,
												document.getElementById('category').value, 
												<?php } 
											 else { ?>							
												'','',
												<?php } ?>
												'<?php echo $listonly; ?>',
												'<?php echo $assigned_to_module;?>',
												'<?php echo $assigned_to_module_id;?>',
												'search',
												'<?php echo $_SESSION[user_id]; ?>',
												{target: 'divmaintask_<?php echo $row[task_id];?>', preloader:'prl'});">
						</td>
						<td width="2%">					
							<span id="importance_<?php echo $row['task_id']; ?>">
								<?php echo $this->returnImportance($row['task_id'],$row['importance_type_id'],'task'); ?>
							</span>
						</td>
						<td width="26%" align="left">
							<span style="color:#009933; font-weight:bold; font-size:16px" id="taskdiv<?php echo $row[task_id];?>" >
								<span class="small_text">
									<a href="javascript:void(0);" onclick="project_new.showTaskDetails('local',
																				'<?php echo $row['task_id']; ?>',
																				'<?php echo $assigned_to_module_id; ?>',
																				{onUpdate: function(response,root){ 
																					document.getElementById('divProduct').innerHTML=response;
																					document.getElementById('divAddTask').style.display='none';
																					start_cal();
																					autosuggest4(); }});"><?php echo $row['title']; ?></a>
								</span>
							</span>
						</td>
						<td width="5%" align="left">
							<span id="task_note<?php echo $row['task_id']; ?>" style="margin-left:70px; margin-right:20px">
								<?php
								$sql_note = "Select * from ".TBL_NOTE_STATUS." where module_id = '$row[task_id]' and module_name = 'Task'";
								$result_note=$this->db->query($sql_note,__FILE__,__lINE__);
								if($this->db->num_rows($result_note)>0){
									while($row_note = $this->db->fetch_array($result_note)){
										if($row_note['note_status']=='unread'){
											$note_status='unread';
											$note_id=$row_note['note_id'];
										}
										else if($row_note['note_status']=='read'){
											$note_status='read';
											$note_id=$row_note['note_id'];
										}	
									}				
									echo $this->returnNoteLink($row['task_id'],$note_id,$note_status,'task_note');
								}
								else{
									echo $this->returnNoteLink($row['task_id'],'','','task_note');
								}
								?>
							</span>
						</td>
						<td width="10%">				
							<span id="task_document">
								<?php					  
								$sql_doc = "Select * from ".TASKS." where task_id = '$row[task_id]'";
								$result_doc=$this->db->query($sql_doc,__FILE__,__lINE__);
								while($row_doc=$this->db->fetch_array($result_doc)){ 
									echo $this->returnDocumentLink($row_doc['doc_name'],$row_doc['doc_server_name']);
								}  
								?>
							</span>				
						</td>			
						<td width="35%" align="right">
							<span class="small_text" style="float:right"> 
								<?php 
								if($assigned_to!='' or $upcomming!='') {
									$user = new User();
									echo $user->GetUserNameById($row[user_id]);					
								}
								?>
							</span>
						</td>
					</tr>
				</table>				
			</div>		  
		  <?php } 
		  
		  }
		  else{
 ?>
					<div style="color:#CCCCCC" id="taskmain<?php echo $row[task_id];?>" <?php if(!$listonly) { ?>
												onmouseover="document.getElementById('<?php echo $row[task_id];?>').style.display='';
															 document.getElementById('task_action_<?php echo $row[task_id];?>').style.display=''; " 
												onmouseout="document.getElementById('<?php echo $row[task_id];?>').style.display='none'; 
														   document.getElementById('task_action_<?php echo $row[task_id];?>').style.display='none';" 			
													   <?php } ?> class="<?php if(!$listonly) echo 'task_padding'; ?>">
				<?php if(!$listonly) { ?>
						<span id="task_action_<?php echo $row[task_id];?>"  style="display:none; " class="task_action">				
								<a href="edit_task.php?task_id=<?php echo $row[task_id]; ?>" onClick="">
								<img src="images/edit.gif" border="0"  align="absmiddle"/></a>&nbsp;
								<a href="#" onClick="if(confirm('Are you sure ?')){	task.DeleteTask(<?php echo $row[task_id]; ?>,
															   {onUpdate: function(response,root)
															   {document.getElementById('divmaintask_<?php echo $row[task_id];?>').innerHTML='';},
																target: 'taskmain<?php echo $row[task_id];?>', preloader:'prl'});
													 } else return false;">
								<img src="images/trash.gif" border="0"  align="absmiddle"/>&nbsp;</a>
						</span>
				<?php } ?>
				<table width="100%">
					<tr>
						<td width="2%">					
							<input type="checkbox" name="chk_task[]" id="chk_task[]" disabled="disabled" checked="checked">
						</td>
						<td width="2%">&nbsp;</td>
						<td width="27%" align="left">
						   <span style="font-weight:bold; font-size:16px" id="taskdiv<?php echo $row[task_id];?>" class="complete" >
						 	  <span class="small_text"><?php echo $row['title']; ?></span>
							</span>
						</td>			   
						<td width="10%" align="left">
							<span class="complete" style="margin-left:70px; margin-right:20px">
								<img src="images/chat_bubble.gif" />
								<?php
								/*$sql_note = "Select * from ".TBL_NOTE_STATUS." where module_id = '$row[task_id]' and module_name = 'Task'";
								$result_note=$this->db->query($sql_note,__FILE__,__lINE__);
								if($this->db->num_rows($result_note)>0){
									while($row_note = $this->db->fetch_array($result_note)){
										if($row_note['note_status']=='unread'){
											$note_status='unread';
											$note_id=$row_note['note_id'];
										}
										else if($row_note['note_status']=='read'){
											$note_status='read';
											$note_id=$row_note['note_id'];
										}	
									}				
									echo $this->returnNoteLink($row['task_id'],$note_id,$note_status,'task');
								}
								else{
									echo $this->returnNoteLink($row['project_id']);
								}					
								*/?>
							</span>
						</td>
						<td width="10%">
							<span id="task_document">
								<?php					  
								$sql_doc = "Select * from ".TASKS." where task_id = '$row[task_id]'";
								$result_doc=$this->db->query($sql_doc,__FILE__,__lINE__);
								while($row_doc=$this->db->fetch_array($result_doc)){ 
									echo $this->returnDocumentLink($row_doc['doc_name'],$row_doc['doc_server_name'],'complete');
								}  
								?>
							</span>	
						</td>
						<td width="35%" align="right">							
							<span class="complete" style="float:right">
								<span class="complete">
								<?php 
								if($assigned_to!='' or $upcomming!='') {
									$user = new User();
									echo $user->GetUserNameById($row['completed_by']);					
								 } ?>
								 </span>
							 </span>
						</td>
					</tr>
				</table>				 
			</div>	
		  <?php 	  
		  }
		  $i++;}?>
	</div>	
	</td>
	</tr>
	</tbody>
	</table>
	<?php
	}

	function updateNoteStatus($runat,$type_id='',$note_id='', $user_id='', $desc='',$type='',$module='',$target=''){
		ob_start();
		switch($runat) {
			case 'local':
				$sql_notes = "Select * from ".TBL_NOTE_STATUS." a, ".TBL_USER." b where a.user_id=b.user_id and a.module_id='$type_id' and a.module_name='$module'";
				$result_notes=$this->db->query($sql_notes,__FILE__,__lINE__);	
				$row_notes=$this->db->fetch_array($result_notes); 
				?>
				<input type="hidden" name="module" id="module" value="<?php echo $module; ?>" />
				<div class="prl">&nbsp;</div>
				<div id="lght12" style="margin-top:0px;!important;">
					<div id="lightbox" style="position:fixed;" >		
						<div style="background-color:#ADC2EB;" class="ajax_heading">
							<div id="TB_ajaxWindowTitle"><?php echo 'Note';?> </div>
							
							<div id="TB_closeAjaxWindow">
								<a href="javascript:void(0);" 
								onclick="javascript:  document.getElementById('lght12').style.display='none';
													  project_new.updateNoteStatus('server',
																					'<?php echo $type_id; ?>',
																					'<?php echo $note_id; ?>',
																					'',
																					'',
																					'',
																					document.getElementById('module').value,
																					{preloader:'prl',
																					 onUpdate: function(response,root){
																					if(document.getElementById('module').value == 'Project'){
																						project_new.returnNoteLink('<?php echo $type_id; ?>',
																												   '<?php echo $note_id; ?>',
																												   'read','',
																												   {target:'notes<?php echo $type_id; ?>',
																												   	preloader:'prl'});
																					} 
																					if(document.getElementById('module').value == 'Task'){  
																						project_new.returnNoteLink('<?php echo $type_id; ?>',
																												   '<?php echo $note_id; ?>',
																												   'read','task_note',
																												   {target:'task_note<?php echo $type_id; ?>',
																												   	preloader:'prl'});
																					}}});">
									<img border="0" src="images/close.gif" alt="close" />
								</a>
							</div>	
						</div>
						<div  class="white_content"> 
						<p id="upload_process" style="visibility:hidden"><img src="loader.gif" /></p>
                            <div id="show_notes">
								<?php echo $this->show_notes($type_id,$module,$type); ?>
                            </div>  
						</div>
					</div>
				</div>
				<?php
		 		break;
			case 'server':
				$sql_notes = "Select * from ".TBL_NOTE_STATUS." where module_id='$type_id' and module_name = '$module' and note_status='unread'";
				//echo $sql_notes;
				$result_notes=$this->db->query($sql_notes,__FILE__,__lINE__);	
				while($row_notes=$this->db->fetch_array($result_notes)){
					$update_sql_array = array();
					$update_sql_array['note_status'] ='read';
					$this->db->update(TBL_NOTE_STATUS,$update_sql_array,'note_id',$row_notes[note_id]);
				}
				if($module == 'Project'){
					//echo $this->returnNoteLink($type_id,$note_id,'read','');
				}
				else if($module == 'Task') {
					//echo $this->returnNoteLink($type_id,$note_id,'read','task_note');
				}
				break;
			case 'server_add':
				$insert_sql_array = array();
				$insert_sql_array['module_id'] = $type_id;
				$insert_sql_array['module_name'] = $module;
				$insert_sql_array['user_id'] = $user_id;
				$insert_sql_array['description'] = $desc;
				$insert_sql_array['note_status'] = 'unread';
				$this->db->insert(TBL_NOTE_STATUS,$insert_sql_array);			
				$note_id =  $this->db->last_insert_id();
				
				echo $note_id;
				if($module == 'Project'){
					//echo $this->returnNoteLink($type_id,$note_id,'unread','');
				}
				else if($module == 'Task') {
					//echo $this->returnNoteLink($type_id,$note_id,'unread','task_note');
				}				
				break;
			}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;				
	}

	function deleteProject($project_id){
		ob_start();		
		$sql = "delete from ".PROJECT." where project_id= ".$project_id;
		$this->db->query($sql,__FILE__,__lINE__);	
		
		$sql = "delete from ".CONNECTED_PROJECT." where project_id= ".$project_id;
		$this->db->query($sql,__FILE__,__lINE__);	

		$sql = "update ".PROJECT." set parent_project_id ='' where parent_project_id= ".$project_id;
		$this->db->query($sql,__FILE__,__lINE__);	

		?>
		<script type="text/javascript">
		 project.projectList('',
		{ onUpdate: function(response,root){
			 document.getElementById('div_project_list').innerHTML=response;
			 document.getElementById('div_project_list').style.display='';		
			 $(function() {		
			$('#search_table')
			.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]], headers: { } } )						
			});
			 }, preloader: 'prl'
		} );
		</script>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
	function setImportance($bug_info_id,$type='',$type_id='',$importance_type_id='',$type1=''){
		ob_start();
		if($bug_info_id!=''){
			$sql="update ".TBL_BUG." set importance = '$type' where bug_info_id = '$bug_info_id'";
			$result = $this->db->query($sql,__FILE__,__LINE__);
		}
		if($type1 == 'project'){
			$sql="update ".PROJECT." set importance_type_id = '$importance_type_id' where project_id = '$type_id'";
			$result = $this->db->query($sql,__FILE__,__LINE__);
		}
		if($type1 == 'task'){
			$sql="update ".TASKS." set importance_type_id = '$importance_type_id' where task_id = '$type_id'";
			$result = $this->db->query($sql,__FILE__,__LINE__);
		}
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function updateField($field='',$value='',$project_id=''){
		ob_start();
		$sql = "update ".PROJECT." set $field= '$value' where project_id='$project_id'";
		//echo $sql;
		$result = $this->db->query($sql,__FILE__,__LINE__);	
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	
	
	function returnStarted($project_id=''){
		ob_start();
		$sql = "select started from ".PROJECT." where project_id='$project_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		$row=$this->db->fetch_array($result);
		?><input type="hidden" name="started_p" id="started_p" value="<?php echo $row['started'];?>"/><?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	

	function returnDueDate($project_id=''){
		ob_start();
		$sql = "select due_date from ".PROJECT." where project_id='$project_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		$row=$this->db->fetch_array($result);
		?><input type="hidden" name="due_dt_p" id="due_dt_p" value="<?php echo $row['due_date'];?>"/><?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	


	function returnTitle($project_id=''){
		ob_start();
		$sql = "select started from ".PROJECT." where project_id='$project_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		$row=$this->db->fetch_array($result);
		?><input type="hidden" name="started_p" id="started_p" value="<?php echo $row['started'];?>"/><?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	

	function returnDescription($project_id=''){
		ob_start();
		$sql = "select started from ".PROJECT." where project_id='$project_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		$row=$this->db->fetch_array($result);
		?><input type="hidden" name="started_p" id="started_p" value="<?php echo $row['started'];?>"/><?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	

	function addDocuments($runat,$project_id,$doc_id='',$document_name='',$document_server_name='',$user_id='') {
		$this->project_id=$project_id;
		switch($runat) {
			case 'local':
				$sql = "select * from ".PROJECT_DOCUMENT." where document_id = '".$doc_id."'";
				$result = $this->db->query($sql,__FILE__,__lINE__);
				$row=$this->db->fetch_array($result);
				?>
				<script language="javascript" type="text/javascript">
				function startUpload(){
					  document.getElementById('upload_process').style.visibility = 'visible';
					  document.getElementById('upload_form').style.visibility = 'hidden';					  
					  return true;
				}
				
				function stopUpload(success,error){
				
					  if (success == 1){
						alert('Document Uploaded Successfully..');
						window.location="project_profile.php?project_id=<?php echo $this->project_id; ?>";
						}
					  else if(success == 2){
						alert('Record Updated Successfully..');
						window.location="project_profile.php?project_id=<?php echo $this->project_id; ?>";
						}
					  else if(success == 3){
						 result = '<span>Selected File Is Of 0 Bytes!!<br/><\/span><br/><br/>';
						 document.getElementById('upload_process').style.visibility = 'hidden';
					  	 document.getElementById('upload_form').innerHTML = result + '<label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>';
						 document.getElementById('upload_form').style.visibility = 'visible';
					  	}
					 else if(success == 0){
						 result = '<span>One Or More Fields are Empty!!<\/span><br/><br/>';
						 document.getElementById('upload_process').style.visibility = 'hidden';
					  	 document.getElementById('upload_form').innerHTML = result + '<label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>';
						 document.getElementById('upload_form').style.visibility = 'visible';
					  	}else
					  
					        
					  return true;   
				}
				
				</script> 			   
				
				<div class="prl">&nbsp;</div>
               <div id="lght1" style="margin-top:-400px;!important;">
				<div id="lightbox" style="position:fixed;" >		
				<div style="background-color:#ADC2EB;" class="ajax_heading">
					<div id="TB_ajaxWindowTitle"><?php if($doc_id) echo 'Edit Document'; else echo'Add Document' ?> </div>
					
					<div id="TB_closeAjaxWindow"><a href="project_profile.php?project_id=<?php echo $this->project_id; ?>" 
					onclick="javascript: document.getElementById('upload_target').display='none';">
					<img border="0" src="images/close.gif" alt="close" /></a></div>	
					</div>
				<div  class="white_content"> 
				<p id="upload_process" style="visibility:hidden"><img src="loader.gif" /></p>
				
				
				
				<form action="upload.php?project_id=<?php echo $this->project_id; if($doc_id) echo '&doc_id='.$doc_id.'&document_server_name='.$row['document_server_name']?>&flag=project&user_id=<?php echo $user_id;?>" id="upload_form" method="post" enctype="multipart/form-data" target="upload_target" onSubmit="startUpload();" >
					 <table width="100%" class="table">   
					 <tr><?php /*?><td>
                         Document Status:
						 </td>
						 <td>  
                              <select name="document_status">
							  <option value="" selected="selected">--Select--</option>
							  <option value="Team Lead" <?php if($row['document_status']=='Team Lead') echo 'selected="selected"';?> >Team Lead</option>
							  <option value="User" <?php if($row['document_status']=='User') echo 'selected="selected"';?> >User</option>

							  <option value="Internal Staff" <?php if($row['document_status']=='Internal Staff') echo 'selected="selected"';?> >Internal</option>
						
							  </select>
                         </td><?php */?>
						 <td>File:</td>
						 <td>  
                              <input name="myfile" type="file" size="30" value=''/>
                         </td>
                         <td>
                             <input type="submit" name="submitBtn" class="sbtn" value="Upload" />
                         </td>

					</tr>
					</table>
                     </p>
					 </form>
					 </div>
					 </div></div>		 					
	
				<?php  
				break;
				
				
			case 'server':
				
				$this->document_name=$document_name;
				$this->document_server_name=$document_server_name;
				$this->$user_id = $user_id;
				if($doc_id){
					$update_sql_array = array();
					$this->db->update(PROJECT_DOCUMENT,$update_sql_array,'document_id',$doc_id);				
					}
				else {
					
					$insert_sql_array = array();
					$insert_sql_array[project_id] = $this->project_id;
					$insert_sql_array[document_name] = $this->document_name;
					$insert_sql_array[document_server_name] = $this->document_server_name;	
					$insert_sql_array[user_id] = $this->$user_id ;	
					$this->db->insert(PROJECT_DOCUMENT,$insert_sql_array);	
				}				
				break;
				
				
						
			}		
		}//end of add document function

	function deleteDocument($project_id,$doc_id){
		ob_start();		
		
		$sql = "select * from ".PROJECT_DOCUMENT." where document_id = '".$doc_id."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		$row=$this->db->fetch_array($result);
		unlink("uploads/".$row[document_server_name]);		
		$sql = "delete from ".PROJECT_DOCUMENT." where document_id= ".$doc_id;
		$this->db->query($sql,__FILE__,__lINE__);	
				
		?>
		<script type="text/javascript">
		project.showDocuments('<?php echo $project_id ?>',{target:'documents'});
		</script>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
		
	function showDocuments($project_id) {		
		
		ob_start();	
		$this->project_id=$project_id;
		$sql = "select a.*,b.first_name,b.last_name from ".PROJECT_DOCUMENT." a, ".TBL_USER." b where a.user_id=b.user_id and a.project_id = '".$this->project_id."'";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		?>		
		<table width="100%" >
		<?php while($row=$this->db->fetch_array($result)) {?>
		<tr>
		<td  width="25%"><a href="uploads/<?php echo $row[document_server_name]; ?>" target="_blank"> <?php echo $row[document_name]; ?> </a></td>
	<?php /*?>		<td > <?php echo $row[document_status]; ?> </td>
	<td><a href="project_profile.php?project_id=<?php echo $event_id; ?>&action=upload&doc_id=<?php echo $row['document_id'];?>">edit</a></td><?php */?>
		<td  width="25%" ><a href="#" onClick="javascript: if(confirm('Are you sure?')){project.deleteDocument('<?php echo $this->project_id; ?>','<?php echo $row['document_id'];?>', {preloader: 'prl'} ); } return false;" ><img src="images/trash.gif" border="0" /></a></td>
		<td  width="50%" align="right"><?php echo 'User: '.date('Y/m/d h:i:s A',strtotime($row[cur_timestamp]));?>&nbsp;&nbsp;&nbsp;&nbsp;
		<?php echo $row[first_name].' '.$row[last_name];?></td>
		</tr>
		<?php } ?>				
</table>	
		<?php	
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function projectStat($project_id){
		$sql_p= "select * from ".PROJECT." where project_id= $project_id";
		$result_p = $this->db->query($sql_p,__FILE__,__lINE__);		
		$row_p=$this->db->fetch_array($result_p);
		?>
		<table class="table" width="100%">
		<tr><th>Project Status ::</th></tr>
		<tr><td><?php 
		$sql = "select * from ".PROJECT_STATUS;
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		?><select name="status_id" id="status_id"  onchange="project.updateField('status_id',this.value,'<?php echo $project_id;?>',{ preloader: 'prl' });" >
		<option value="" >--Select--</option>
		<?php while($row=$this->db->fetch_array($result)){ ?>
		<option value="<?php echo $row[status_id]; ?>" <?php if($row[status_id]==$row_p[status_id]) echo 'selected="selected"';?> >
		<?php echo $row[status]; ?> </option>
		<?php } ?>
		</select>
		</td></tr>
		
		<tr><th>User Involved In Project ::
		<a  href="#"
		onclick="javascript: project.addPersonToProject('local','<?php echo $project_id;?>','user','div_project',
			{ onUpdate: function(response,root){
					 document.getElementById('div_project').innerHTML=response;
					 document.getElementById('div_project').style.display='';
					 }, preloader: 'prl'
				} ); return false;" >add</a>
		</th></tr>
		<tr><td>
		<div id="user_list"><?php echo $this->userList($project_id);?></div>
		</td></tr>

		<tr><td>&nbsp;</td></tr>
		
		<tr><th>Contacts ::
		<a  href="#"
		onclick="javascript: project.addPersonToProject('local','<?php echo $project_id;?>','contact','div_project',
			{ onUpdate: function(response,root){
					 document.getElementById('div_project').innerHTML=response;
					 document.getElementById('div_project').style.display='';
					 initializeFacebook();
					}, preloader: 'prl'
				} ); return false;" >add</a>
		</th></tr>
		<tr><td>
		<div id="contact_list"><?php echo $this->contactList($project_id);?></div>
		</td></tr>

		</table>
		<?php
	}
	
	function addPersonToProject($runat,$project_id,$role='',$person_id='',$location=''){
		ob_start();
		switch($runat){
			case 'local':	
				$formName='frm_addperson';
				if($role=='contact')
				$ControlNames=array("contact"	=>array('contact',"''","Please Select Someone !! ","person_span") );
				else
				$ControlNames=array("user_id"	=>array('user_id',"''","Please Select Someone !! ","person_span") );
				
				$ValidationFunctionName="CheckValidity";
				$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($formName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
				echo $JsCodeForFormValidation;
				?>
				<div class="prl">&nbsp;</div>
				<div id="lightbox">
					<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle"><?php if($role=='contact') echo 'Add Contact'; else echo 'Add User';?></div>
					<div id="TB_closeAjaxWindow">
						<a href="javascript:void(0)" onClick="javascript: document.getElementById('div_project').style.display='none';">
							<img border="0" src="images/close.gif" alt="close" />
						</a>
					</div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;" class="form_main">			
				<ul id="error_list">
					<li><span id="person_span" class="normal"></span></li>
				</ul>
				<form metdod="post" action="" enctype="multipart/form-data" name="<?php echo $formName; ?>">
				<table class="table" width="100%" >
				<tr>
				<?php echo $this->addPersonToProjectBox($project_id,$role,$person_id);?>
				<?php if($location !='add_project'){ ?>
				<td><input type="button" value="Add" name="submit" id="submit" 
				  onClick="<?php if($role=='contact') { echo 'fillcontact();'; }?>		
							if(<?php echo $ValidationFunctionName?>()) {
							project.addPersonToProject('server','<?php echo $project_id;?>','<?php echo $role;?>',
							this.form.<?php if($role=='contact') echo 'contact'; else echo 'user_id' ;?>.value,
							'div_project',{ onUpdate: function(response,root){
									 document.getElementById('div_project').innerHTML=response;
									 document.getElementById('div_project').style.display='';
									}, preloader: 'prl'
								} );			
							}" /></td> <?php } ?>
				</tr>
				</table>
				</form>
				</div></div></div>
				<?php
				break;
				
			case 'server':
				if(isset($_REQUEST) && $location!='add_project'){
					extract($_REQUEST);
					if($role=='contact'){ 
						$person_id = $_REQUEST['plxa']['3'];
						}
					else {
						//$person_id = $user_id;
                          $person_id = $_REQUEST['plxa']['3'];
					}
				}

				$insert_sql_array = array();
				if($role=='contact'){
					foreach(explode("," , $person_id, -1) as $arr){
						$insert_sql_array[contact_id] = $arr;
						$insert_sql_array[project_id] = $project_id;
						$this->db->insert(CONTACT_IN_PROJECT,$insert_sql_array);
					}
				}
				else {
					$insert_sql_array[user_id] = $person_id;
					$insert_sql_array[project_id] = $project_id;
					$this->db->insert(USER_IN_PROJECT,$insert_sql_array);
				}
                                $debug = $_GET["debug"];
                                if( $debug == "yes"){
                                echo "<textarea style='position: absolute; bottom: 0; left: 0; width: 400px; height: 300px;'>";
                                    echo "insert_sql_array\n";
                                    print_r($insert_sql_array);
                                    echo "\nREQUEST\n";
                                    print_r( $_REQUEST);
                                    echo "\nTEST";
                                    echo $person_id = $_REQUEST['plxa']['3'];
                                    echo "</textarea>";
                                }
                                if($location!='add_project'){
				?>
                               
				<script type="text/javascript">
				document.getElementById('div_project').style.display='none';
				project.userList('<?php echo $project_id;?>', { target : 'user_list' , preloader: 'prl' });
				project.contactList('<?php echo $project_id;?>', { target : 'contact_list' , preloader: 'prl' });
				</script>
				<?php
				}
				
				break;
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function userList($project_id){
		ob_start();
		$sql = "select b.user_id,b.first_name, b.last_name from ".USER_IN_PROJECT." a, ".TBL_USER." b where a.project_id='$project_id' and a.user_id=b.user_id";
		$result = $this->db->query($sql,__FILE__,__lINE__);		
		?><table class="table" width="100%"><?php
		while($row=$this->db->fetch_array($result)){
			?>
			<tr>
			<td><?php echo $row[first_name].' '.$row[last_name];?></td>
			<td><a href="javascript:void(0);" onClick="javascript: project.deleteUserFromProject('<?php echo $project_id;?>',
																'<?php echo $row['user_id'];?>',{ preloader: 'prl'} );" >Del</a></td>
			</tr><?php
		}	
		?>
		</table>
		<?php	
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	
	function contactList($project_id){
		ob_start();
		$sql = "select b.contact_id,b.first_name, b.last_name from ".CONTACT_IN_PROJECT." a, ".TBL_CONTACT." b where a.project_id='$project_id' and a.contact_id=b.contact_id";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		?><table class="table" width="100%"><?php
		while($row=$this->db->fetch_array($result)){
			?><tr>
			<td><?php echo $row[first_name].''.$row[last_name];?></td>
			<td><a  href="javascript:void(0);" onClick="javascript: project.deleteUserFromProject('<?php echo $project_id;?>',
																'<?php echo $row['contact_id'];?>',{ preloader: 'prl'} );" >Del</a></td>
			</tr><?php
		}	
		?>
		</table>
		<?php	
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function deleteUserFromProject($project_id='',$user_id=''){
		ob_start();
		$sql = "delete from ".USER_IN_PROJECT." where project_id='$project_id' and user_id='$user_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		?><script>
		project.userList('<?php echo $project_id;?>', { target : 'user_list' , preloader: 'prl' });
		</script><?php 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} 
	
	function deleteContactFromProject($project_id='',$contact_id=''){
		ob_start();
		$sql = "delete from ".CONTACT_IN_PROJECT." where project_id='$project_id' and contact_id='$contact_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		?><script>
		project.contactList('<?php echo $project_id;?>', { target : 'contact_list' , preloader: 'prl' });
		</script><?php 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} 	
	
	function show_notes($project_id='',$module='', $type='')
	{
		ob_start();
		
		$sql_notes = "Select * from ".TBL_NOTE_STATUS." a, ".TBL_USER." b where a.user_id=b.user_id and a.module_id='$project_id' and a.module_name='$module'";
		$result_notes=$this->db->query($sql_notes,__FILE__,__lINE__);
		?>
            <form name="showNotes" action="" method="post">
                <input type="hidden" name="project_id" id="project_id" value="<?php echo $project_id; ?>" />
				<input type="hidden" name="module" id="module" value="<?php echo $module; ?>" />
                <div id="notesDetails" style="float:left">   
					<table class="table" width="100%">                 
                    	<?php
						if($type!='add'){
						   if($this->db->num_rows($result_notes) >0){
						 	   while($row_notes=$this->db->fetch_array($result_notes)){ ?>
								<tr>
									<td colspan="2"><?php echo $row_notes['description']; ?></td>
								</tr>
								<tr>
									<td colspan="2"><?php echo ' by '.$row_notes['first_name'].' '.$row_notes['last_name']; ?></td>
								</tr>
                                <tr>
								<td><input type="hidden" name="note_id" id="note_id" value="<?php echo $row_notes['note_id']; ?>" /></td>
                                </tr>
								<?php } } }
								$sql_name="select * from ".TBL_USER." where user_id=".$_SESSION[user_id];
								$result_name=$this->db->query($sql_name,__FILE__,__lINE__);
								$row_name=$this->db->fetch_array($result_name);
								$user_id=$_SESSION[user_id];
								//echo $row_name[first_name]." ".$row_name[last_name];
								?>  
								<tr>
                                     <td><textarea name="desc" id="desc" rows="2" cols="60"></textarea>
                             			 <input type="button" name="add" value="ADD" style="width:70px;" 
                                                                 onclick="javascript: if(document.getElementById('desc').value == ''){
																 					   		alert('Please enter notes !!!');
																							return false;
																					   } 
																					   else{	
																					   var project_id = document.getElementById('project_id').value;												 																						project_new.updateNoteStatus(
																								'server_add',
																								document.getElementById('project_id').value,
																								'',
																								'<?php echo $user_id; ?>',
																								document.getElementById('desc').value,
																								'',
																								document.getElementById('module').value,
																								{preloader:'prl',
																								 onUpdate: function(response,root){
																					if(document.getElementById('module').value == 'Project'){
																						project_new.returnNoteLink('<?php echo $project_id; ?>',
																												   response,
																												   'unread','',
																												   {target:'notes<?php echo $project_id; ?>',
																												   	preloader:'prl'});
																					} 
																					if(document.getElementById('module').value == 'Task'){  
																						project_new.returnNoteLink('<?php echo $project_id; ?>',
																												   response,
																												   'unread','task_note',
																												   {target:'task_note<?php echo $project_id; ?>',
																												   	preloader:'prl'});
																					}}}); }" /></td>                                           
								</tr>
							 </table>
                </div>
            </form>
		
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	
	
	function showSubProject($result,$unclaimed='',$project_complete='',$project_id=''){
		ob_start();
		?>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#browser").treeview({});
			});
		</script>
		<div id="main">
		<ul id="browser" class="filetree treeview-famfamfam">
		<?php
		while($row=$this->db->fetch_array($result)){ 
			//if($row['project_id']!=$project_id) { ?>
			<li class="closed"><span class="folder">
				<table width="100%">
				<tr>
					<td width="4%">
					<span id="sub_importance_<?php echo $row['project_id']; ?>">
						<?php echo $this->returnImportance($row['project_id'],$row['importance_type_id'],'project'); ?>
					</span>
					</td>
					<td width="36%"> 
					<a href="javascript:void(0);" onclick="project_new.showProjectDetails('local',
																'<?php echo $row['project_id']; ?>',
																{onUpdate: function(response,root){ 
																	document.getElementById('divProduct').innerHTML=response;
																	start_cal(); }});"><?php echo $row['title']; ?></a>
					</td>
					<td width="4%">&nbsp;</td>
					<td width="4%" align="left">
					<span id="sub_notes<?php echo $row['project_id']; ?>">
						<?php
						$sql_note = "Select * from ".TBL_NOTE_STATUS." where module_id = '$row[project_id]' and module_name = 'Project'";
						$result_note=$this->db->query($sql_note,__FILE__,__lINE__);
						if($this->db->num_rows($result_note)>0){
							while($row_note = $this->db->fetch_array($result_note)){
								if($row_note['note_status']=='unread'){
									$note_status='unread';
									$note_id=$row_note['note_id'];
								}
								else if($row_note['note_status']=='read'){
									$note_status='read';
									$note_id=$row_note['note_id'];
								}									
							}
							echo $this->returnNoteLink($row['project_id'],$note_id,$note_status);
						}
						else{
							echo $this->returnNoteLink($row['project_id']);
						}
						?>
					</span>
					</td>
					<td width="4%" align="left">
					<span id="sub_documents">
						<?php	
						$sql_doc = "Select * from ".PROJECT_DOCUMENT." where project_id = '$row[project_id]'";
						$result_doc=$this->db->query($sql_doc,__FILE__,__lINE__);
						while($row_doc=$this->db->fetch_array($result_doc)){ 
							echo $this->returnDocumentLink($row_doc['document_name'],$row_doc['document_server_name']);
						}  
						?>
					</span>	
					</td>	
					<td width="8%">&nbsp;</td>
					<td width="24%" align="right"> 				
					<span>
					<span style="color:#666666" id="span_user_id<?php echo $row['project_id']; ?>">
					<?php	if($row['user_first_name']!=''){
								 echo $this->returnLink($row['user_id'],$row['project_id'],$row['user_first_name'],$row['user_last_name']); 
							 }
							else{
								echo $this->returnLink('',$row['project_id'],'','');
							} ?>
					</span>
					</span>
					</td>
					<td width="2%">&nbsp;</td>						
					<td width="18%">
					<span>
					<?php 
					if($project_complete=='yes') echo 'Completed';
					else if($unclaimed=='unclaimed') echo "Due Date : N/A";
					else{ ?>
								<span id="due_date"><?php echo $this->calcDueDay($row['due_date']);?></span>
							<?php  } ?>
					</span>
					</td>
					</tr>
				</table>
					</span>
						<ul>
						<li><span class="">
							<div id="sub_div_task_project">
							<?php echo $this->TaskForProject('','','','','','','',1,'PROJECT',$row['project_id'],'','','',$project_complete);?>
							</div>
							</span>
						</li>
						<li>
							<span class="folder">
								<?php 
								$sql_sub = "Select connected_project_id from ".CONNECTED_PROJECT." where project_id = '$row[project_id]'";
								$result_sub=$this->db->query($sql_sub,__FILE__,__lINE__);
								if($this->db->num_rows($result_sub)){
									while($row_sub = $this->db->fetch_array($result_sub)){
										echo $this->searchProjectList('','','','','',$row_sub['connected_project_id']); 
									}
								}	
								?>
							</span>
						</li>
					</ul>
				</li>
			
		<?php //}
		} // End of while
		?></ul></div>
		<?php 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
	function showDropDown($useridd='',$project_id='',$fname='',$lname=''){
	 
	 ob_start();
	 if($fname == '' && $lname == ''){
	 	$u_name = 'UNCLAIMED';
	 }
	 else {
	 	$u_name = $fname.' '.$lname;
	 }
	 $sql_dropdown = "select * from ".TBL_USER;
	 $result_dropdown = $this->db->query($sql_dropdown,__FILE__,__lINE__);	?>

	 <select name="userid" id="userid" style="width:80%" 
	 onblur="javascript: var username; 
						   var userid = this.value; 
					  	    project_new.getName(this.value,
							  {preloader:'prl',
							   onUpdate: function(response,root){
								username = response;
								if(confirm('Are you sure you want to change user name from <?php echo $u_name; ?> to '+ username)){																					
									project_new.updateField('user_id',
															 userid,
															 '<?php echo $project_id;?>',
															 { onUpdate: function(response,root){
																project_new.returnLink(userid,
																				   '<?php echo $project_id; ?>',
																				   username,
																				   {preloader:'prl',
																				   target:'span_user_id<?php echo $project_id; ?>'});
																}, preloader: 'prl' 
															  })
								  }
								  else{
									  project_new.returnLink('<?php echo $useridd; ?>',
														   '<?php echo $project_id;?>',
														   '<?php echo $fname;?>',
														   '<?php echo $lname ;?>',
														   {target:'span_user_id<?php echo $project_id; ?>',preloader:'prl'}																		   
														   
									  );	
								}}});">
		<option value="" >--Select--</option>
		<?php while($row_dropdown=$this->db->fetch_array($result_dropdown)){ ?>
		<option value="<?php echo $row_dropdown[user_id]; ?>" <?php if($row_dropdown[user_id]==$useridd) echo 'selected="selected"';?>>
			<?php echo $row_dropdown[first_name].' '.$row_dropdown[last_name]; ?> 
		</option>
		<?php } ?>
	</select>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	return $html;	
	}
	
	function returnLink($userid='',$project_id='',$fname='',$lname=''){
		ob_start();
		echo $user_id;
		if($userid !=''){ ?>
			<a href="javascript:void(0);" onclick="javascript: project_new.showDropDown('<?php echo $userid; ?>',
																						'<?php echo $project_id; ?>',
																						'<?php echo $fname; ?>',
																						'<?php echo $lname; ?>',
																						{ target: 'span_user_id<?php echo $project_id; ?>'}
																						); ">
			<?php echo $fname.' '.$lname; ?></a><?php 
		}
		if($userid =='' && $fname == ''){ ?>
			<a href="javascript:void(0);" onclick="javascript: project_new.showDropDown('',
																						'<?php echo $project_id; ?>',
																						'',
																						'',
																						{ target: 'span_user_id<?php echo $project_id; ?>'}
																						); ">
			UNCLAIMED</a><?php
		} 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	} 
	
	function getName($id=''){
		ob_start();
		 $sql = "select * from ".TBL_USER." where user_id = '$id'";
		 $result = $this->db->query($sql,__FILE__,__lINE__);	
		 $row=$this->db->fetch_array($result);
		 echo $row[first_name].' '.$row[last_name];
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
}
?>