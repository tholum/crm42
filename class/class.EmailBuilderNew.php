<?php
class EmailBuilder{

var $to;
var $mail_obj;
var $db;
var $validity;
var $Form;

	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
		$this->mail_obj = new PHPMailer();
	}
	
	function SendMessage($name='',$runat='',$temp='',$type=''){
		ob_start();
		  switch($runat) {
			  case 'local':
			  
			  if($temp!=''){
				 $sql = "select * from template where id=$temp";
				 $result = $this->db->query($sql,__FILE__,__LINE__);
				 $row = $this->db->fetch_array($result);
				
				 $title=$row['title'];
				 $subject=$row['subject'];
				 $message=mysql_escape_string($row['message']);
				}
			  
			//echo $name;				
			$FormName = "frm_send_message";
			$ControlNames=array("title"=>array('title',"OR","One of Users or Groups field is required","span_to_send_message"),
								"subject"=>array('subject',"''","Subject field is empty","span_subject_send_message"),
								"message"=>array('message',"''","Message field is empty","span_message_send_message")
								);

			$ValidationFunctionName="Validator_send_message";
		    $JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,                                                                                             $SameFields,$ErrorMsgForSameFields);
			echo $JsCodeForFormValidation;
						  
			switch($type) {
				case 'old': 
						$FormName = "frm_send_message";
						$ControlNames=array("title"=>array('title',"OR","One of Users or Groups field is required","span_to_send_message"),
											"subject"=>array('subject',"''","Subject field is empty","span_subject_send_message"),
											"message"=>array('message',"''","Message field is empty","span_message_send_message")
											);
			
						$ValidationFunctionName="Validator_send_message";
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,                                                                                             $SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
				
						?>
					  <form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName;?>" >
					   <input type="hidden" name="hid_report_id" id="hid_report_id" value="<?php echo $row[id]; ?>" />
					  <table width="90%" class="news">
						  <tr>
								<td colspan="2" align="right">
									  <ul>
										<li><span id="span_to_send_message"></span></li>
										<li><span id="span_subject_send_message"></span></li>
										<li><span id="span_message_send_message"></span></li>
									  </ul>									 
								 </td>
						  </tr>
						  <tr>
						  	<td>&nbsp;</td>
							<td style="float:right">
								<a href="javascript:void(0);" 
									onclick="javascript: if(confirm('Are you sure you want to delete?')){
														  e_builder.checkForSystemTask('<?php echo $row[id]; ?>',
														  							   {preloader:'prl',
																					   	onUpdate:function(response,root){
																						if(confirm('Would you like to remove this Report from Flow Chart Task '+ response)){
																							e_builder.deleteReport('<?php echo $row[id]; ?>',
																												   '<?php echo $row[timestamp]; ?>',
																												   {preloader:'prl'}); }   }}); 
														 }
														 else {return false;}">
									<img src="images/trash.gif" border="0" />
								</a>
							</td>
						  </tr>						  
						<?php /*?>	  <tr>
							  <th>Email To:</th>
							  <td>
							  <input type="text" name="to"  id="to"  readonly="true"  value="<?php echo $row[email_to]; ?>" />
							  </td>
						  </tr>	<?php */?>						  
						   <tr>
							  <th>Title:</th>
							  <td>
							  <input type="text" name="title"  id="title"  readonly="true"  value="<?php echo $title; ?>" />
							  </td>
						  </tr>
						  
						  <tr>
							  <th>Subject:</th>
							  <td><input type="text" name="subject" id="subject" readonly="true" value="<?php echo $subject; ?>" />
							  </td>
						  </tr>
						  <tr>
							  <th>&nbsp;</th>
							  <td>
								  <div id='message_body'>
								  <?php 
								  $msg = nl2br($message);
								  $string = strip_tags($msg);
								   ?>
								  <textarea name="message" id="message" cols="40" rows="8" ><?php echo $string; ?></textarea>
								  </div>
							  </td>
						  </tr>
						  <tr>
							  <th>&nbsp;</th>
							  <td  align="right">
							  <input style="width:auto" type="submit" name="save" id="save" value="save"
									 onclick="return <?php echo $ValidationFunctionName?>();" />
							  </td>
						  </tr>
					  </table>
					  </form>
			<?php break;
			case 'new': 
					$FormName = "frm_send_message";
					$ControlNames=array("title"=>array('title',"","One of Users or Groups field is required","span_to_send_message"),
										"subject"=>array('subject',"''","Subject field is empty","span_subject_send_message"),
										"message"=>array('message',"''","Message field is empty","span_message_send_message")
										);
		
					$ValidationFunctionName="Validator_send_message";
					$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,                                                                                             $SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
					$_SESSION[cur_time] = time();
					?>
				  <form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName;?>" >
				  <input type="hidden" name="hid_module" id="hid_module" value="" />
				  <input type="hidden" name="hid_module_id" id="hid_module_id" value="" />
				  <input type="hidden" name="hid_query" id="hid_query" value="" />	
				  <input type="hidden" name="hid_timestamp" id="hid_timestamp" value="<?php echo $_SESSION[cur_time]; ?>" />	
				  <table width="90%" class="news">
					  <tr>
							<td colspan="2" align="right">
								  <ul>
									<li><span id="span_to_send_message"></span></li>
									<li><span id="span_subject_send_message"></span></li>
									<li><span id="span_message_send_message"></span></li>
								  </ul>									 
							 </td>
					  </tr>
					 <?php /*?>	 <tr>
						  <th>Email To:</th>
						  <td>
						  <input type="text" name="to"  id="to"  readonly="true"  value="<?php echo $to; ?>" />
						  </td>
					  </tr>  	<?php */?>
					   <tr>
						  <th>Title:</th>
						  <td>
						  <input type="text" name="title"  id="title" value="" />
						  </td>
					  </tr>
					  
					  <tr>
						  <th>Subject:</th>
						  <td><input type="text" name="subject" id="subject" value="" />
						  </td>
					  </tr>
					  <tr>
						  <th>Body</th>
						  <td><div id='user'><?php echo $name; ?>
						  <textarea name="message" id="message" cols="40" rows="8" >Respected Sir/Madam,
						  Thanks and Regards.
						  </textarea>
						  </div>
						  <div id="user_body"></div>
						  </td>
					  </tr>
					  <tr>
						  <th>&nbsp;</th>
						  <td  align="right">
						  <input style="width:auto" type="submit" name="save" id="save" value="save"
								 onclick="return <?php echo $ValidationFunctionName?>();" />
						  </td>
					  </tr>
				  </table>
				  </form>
						  
			<?php break;
			}
			break;
		  case 'server':
		          extract($_POST);
				  if($hid_module != ''){
				  if($hid_module == 'erp_order'){
				  	$module = "Order";
				  }
				  if($hid_module == 'erp_work_order'){
				  	$module = "Work Order";
				  }	
				  			  
				  $insert_sql_array = array();
				  $insert_sql_array[title] = $title;
				  $insert_sql_array[subject] = $subject;
				  $insert_sql_array[message] = $message;
				  $insert_sql_array[module] = $module;
				  $insert_sql_array[module_id] = $hid_module_id;
				  $insert_sql_array[query] = $hid_query;
				  $insert_sql_array[timestamp] = $_SESSION[cur_time];
				  $insert_sql_array[email_to] = $to;
				  
				  if($hid_report_id==''){
				 	 $this->db->insert(template,$insert_sql_array,false,'<p><strong><em><span><ol><li><ul><img><a>'); 
				  }
 				  else {
				 	 $this->db->update(template,$insert_sql_array,'id',$hid_report_id,false,'<p><strong><em><span><ol><li><ul><img><a>'); 
				  } 
				  ?>
				 <script>
				 	alert("template successfully saved");
				 	window.location="<?php echo $_SERVER['PHP_SELF']; ?>";
				 </script>
			<?php }
				  else {  ?>
				 <script>
				 	alert("Please select values from the right panel also.");
				 </script>
			<?php
				  }
				 break;		
	        }  ///////end of switch
		
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
	    }  ///////end of function SendMessage
	
	function enterDetails(){
		ob_start();
	
		?>
        <table class="table" width="454px" border="0" cellpadding="4">
			<tr>
				<td align="right">Quick search</td>
				<th><select name="search" id="search" style="width:70%"
							 onchange="javascript:e_builder.SendMessage('','local',this.value,'old',{onUpdate:function(response,root){
												document.getElementById('show_message').innerHTML=response;}});" >
					   <?php /*?><select name="search" id="search" style="width:70%"><?php */?>
						<option value="">-Select-</option>
								<?php 
									$sql = "select * from template where message <> 'NULL'";
									$result = $this->db->query($sql,__FILE__,__LINE__);
									while($row = $this->db->fetch_array($result)){
								 ?><option value="<?php echo $row['id']; ?>"><?php echo $row['title']; ?></option>
								 <?php } ?>
																				 
					</select>
				</th>
			</tr>
			 <tr>
				<td align="right"> Module message sent from: 
				</td>
				<td>
					<select style="width:70%" name="module" id="module"
						onchange="javascript: e_builder.show_mod(this.value,{target:'get_mod'});
																 e_builder.show_column(this.value,
																  {onUpdate:function(response,root){
																	document.getElementById('unique').innerHTML=response;}});"  >
					<option value="">-Select-</option>
					<option value="erp_order">Order</option>
					<option value="erp_work_order">Work Order</option>
					
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">Sample Module ID :
				</td>
				<td>
				<div id="get_mod"></div>
				</td>
			</tr>		
			<tr>
				<td align="right">Unique field to use: 
				</td>
				<td>
				<div id="unique">
				
				</div>
				</td>
			</tr>
			 <tr>
				<td align="right">Cross reference table: 
				</td>
				 <td><select name="ref" id="ref" style="width:70%" 
							 onchange="javascript:e_builder.select_column(this.value,{onUpdate:function(response,root){
												  document.getElementById('link').innerHTML=response;}});
												  e_builder.display_column(this.value,{target:'colm'}); 
												  if(document.getElementById('column').value){											 
													  e_builder.select_output(document.getElementById('dis_col').value,
																			  document.getElementById('module').value,
																			  document.getElementById('field').value,
																			  document.getElementById('ref').value,
																			  document.getElementById('column').value,
																			  document.getElementById('mod_id').value,
																			  {target:'output'}); }
												  else { return false; }" >
						<option value="">-Select-</option>
								<?php 
									$sql = "SHOW TABLES FROM platform_coulee";
									$result = $this->db->query($sql,__FILE__,__LINE__);
									while($row = $this->db->fetch_array($result)){
								 ?><option value="<?php echo $row[0]; ?>"><?php echo $row[0]; ?></option>
								 <?php } ?>
																				 
					</select>
				 </td>
			</tr>
			<tr>
				<td align="right">Linking field from Reference Table: 
				</td>
				<td>
				<div id="link"><?php //echo $this->select_column('',''); ?>
				</div>
				</td>
			</tr>
			 <tr>
				<td align="right">Column Name to display: 
				</td>
				<td>
				<div id="colm"><?php //echo $this->select_column('',''); ?>
				</div>
				</td>
			</tr>
			<tr>
				<td align="right">Example Output: 
				</td>
				<td>
				<div id="output"><?php //echo $this->select_output('',''); ?>
				</div>
				</td>
			</tr>
		
<?php /*?>		<tr>
			<td colspan="2"><div id="div_query"></div></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><a href="javascript:void(0);" onclick="e_builder.showQueryGenerator('local',{target:'div_query'});">Generate SQL Query</a></td>			
		</tr><?php */?>
   	 </table>

	
		<?php
        
        $html = ob_get_contents();
        ob_end_clean();
        return $html;	
        
	}
	
	function select_column($table=''){
		ob_start();
		?>
	
		<select name="column" id="column" style="width:70%" onchange="javascript:e_builder.select_output(document.getElementById('dis_col').value,
																										 document.getElementById('module').value,
																										 document.getElementById('field').value,
																										 document.getElementById('ref').value,
																										 document.getElementById('column').value,
																										 document.getElementById('mod_id').value,
																										 {target:'output'});">
            <option value="">-Select-</option>
                    <?php 
                    $sql="SHOW COLUMNS FROM $table";
                    //echo $sql;
                    $result=$this->db->query($sql,__FILE__,__LINE__);
                    while($row =$this->db->fetch_array($result)){ ?>
                    <option value="<?php echo $row['Field']; ?>"><?php echo $row['Field']; ?></option>
			<?php } ?>
    	</select>
			<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	function display_column($table=''){
		ob_start();
		?>
	
		<select name="dis_col" id="dis_col" style="width:70%" onchange="javascript:if(document.getElementById('column').value != ''){
																					 e_builder.select_output(document.getElementById('dis_col').value,
																											 document.getElementById('module').value,
																											 document.getElementById('field').value,
																											 document.getElementById('ref').value,
																											 document.getElementById('column').value,
																											 document.getElementById('mod_id').value,
																											 {target:'output'}); 																					
																					 } 
																			       else { return false; }">
            <option value="">-Select-</option>
                    <?php 
                    $sql="SHOW COLUMNS FROM $table";
                    //echo $sql;
                    $result=$this->db->query($sql,__FILE__,__LINE__);
                    while($row =$this->db->fetch_array($result)){ ?>
                    <option value="<?php echo $row['Field']; ?>"><?php echo $row['Field']; ?></option>
			<?php } ?>
    	</select>
			<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
	
	function select_output($dis_col='',$module='',$field='',$ref='',$column='',$mod_id=''){
			ob_start();
				
			$sql="select b.$dis_col from $module a, $ref b where a.$field=b.$column and a.order_id=$mod_id";
			$result=$this->db->query($sql,__FILE__,__LINE__);
			$row =$this->db->fetch_array($result);
			if($row[$dis_col] != ''){
				echo $row[$dis_col];
				$searchResult = "<span id=".$dis_col.">".$row[$dis_col]."</span>";
			}
			else {
				echo 'No Result';
			}
			?>
			<br />
			<br/>
			<a href="javascript:void(0);" onclick="javascript:  e_builder.assignQuery('<?php echo $sql; ?>',
																					  '<?php echo $module; ?>',
																					  '<?php echo $mod_id; ?>',
																					  '<?php echo $ref; ?>',
																					  '<?php echo $column; ?>',
																					  '<?php echo $dis_col; ?>',
																					  '<?php echo $row[$dis_col]; ?>',
																					  '<?php echo $field; ?>',
																					  {});
														setTextAtCursorPoint('<?php echo $searchResult; ?>');">
															Insert into report</a>
			<br />												
			<a href="javascript:void(0);" 
				onclick="javascript : document.getElementById('to').value = '<?php echo $row[$dis_col]; ?>';">Insert Email ID</a>
			<?php /*?><a href="javascript:void(0);" onClick="javascript:e_builder.show_user('<?php echo $row[$dis_col]; ?>',{target:'user_body'});" >
					Insert into report</a><?php */?>
			<div id="show_1"></div>
			<?php
				
				
			$html = ob_get_contents();
			ob_end_clean();
			return $html;	
   }

   function assignQuery($sql='',$module='',$mod_id='',$ref='',$column='',$dis_col='',$dis_value='',$field=''){
   		ob_start();
		?>
			<script>
				document.getElementById('hid_query').value = "<?php echo $sql; ?>"+"/"+document.getElementById('hid_query').value;
				document.getElementById('hid_module').value = "<?php echo $module; ?>";
				document.getElementById('hid_module_id').value = "<?php echo $mod_id; ?>";
			</script>
		<?php	
		  $insert_sql_array = array();
		  $insert_sql_array[table_name] = $ref;
		  $insert_sql_array[field_name] = $dis_col;
		  $insert_sql_array[field_value] = $dis_value;
		  $insert_sql_array[column_name] = $column;
		  $insert_sql_array[column_name_main] = $field;
		  $insert_sql_array[table_name_main] = $module;
		  $insert_sql_array[timestamp] = $_SESSION[cur_time];
		  
		  $this->db->insert(insert_to_report,$insert_sql_array); 
		  	
		$html = ob_get_contents();
		ob_end_clean();
		return $html;			
   }
   
   function showQueryGenerator($runat,$query=''){
		ob_start();
		switch($runat){
			case 'local': ?>
				<table>
				<tr><td colspan="2">
				<textarea id="txt_query" cols="40" rows="2"></textarea>
				</td>
				</tr>
				<tr>
				<td>
				<a href="javascript:void(0);" onclick="setTextAtCursorPoint(document.getElementById('div_query_result').innerHTML);">Insert Query Result into report</a>
				</td>
				<td>
				<input type="button" name="generate" id="generate" value="Generate" style="size:25px"
					onclick="javascript:if(document.getElementById('txt_query').value != ''){
											e_builder.showQueryGenerator('server',
																		 document.getElementById('txt_query').value,
																		 {target:'div_query_result'});
										  }
										  else {
										  	alert('Type SQL query to generate.');
											document.getElementById('txt_query').focus();
											return false;
										  }" />
				
                </td>
				</tr>
				<tr><td colspan="2">
				<div id="div_query_result"></div>	
				</td>
				</tr>
				</table>
				<?php
				break;
			case 'server' :
			
				$result = $this->db->query(stripslashes($query),__FILE__,__LINE__);
				$row = $this->db->fetch_assoc($result);
				foreach($row as $name => $value) {
					//echo $name .' = '.$row[$name].'<br/> ';
					echo $row[$name].' ';
				}
				/*else{
					?><script>alert(document.getElementById('div_query_result').value); </script><?php 		
				}*/
				break;
		}
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
   }
   
   function show_user($user='')
   {		ob_start();
   			
			echo $user;
   
   			$html = ob_get_contents();
			ob_end_clean();
			return $html;	
   			
   		
   }
	
	function show_column($fields=''){
			ob_start();
			?>
			<select name="field" id="field" style="width:70%" onchange="javascript:if(document.getElementById('column').value != ''){
																					 e_builder.select_output(document.getElementById('dis_col').value,
																											 document.getElementById('module').value,
																											 document.getElementById('field').value,
																											 document.getElementById('ref').value,
																											 document.getElementById('column').value,
																											 document.getElementById('mod_id').value,
																											 {target:'output'}); } 
																		            else { return false; }">
					<option>select</option>
									<?php 
										$sql = "SHOW COLUMNS FROM $fields";
										$result = $this->db->query($sql,__FILE__,__LINE__);
										while($row = $this->db->fetch_array($result)){
									 ?><option value="<?php echo $row['Field']; ?>"><?php echo $row['Field']; ?></option>
									 <?php } ?>
			</select>
			<?php
				
				
			$html = ob_get_contents();
			ob_end_clean();
			return $html;	
	
	}
	function show_mod($fields=''){
			ob_start();
			?>
			<select name="mod_id" id="mod_id" style="width:70%" 
						onchange="javascript:e_builder.select_output(document.getElementById('dis_col').value,
																	 document.getElementById('module').value,
																	 document.getElementById('field').value,
																	 document.getElementById('ref').value,
																	 document.getElementById('column').value,
																	 document.getElementById('mod_id').value,
																	 {onUpdate:function(response,root){
																		document.getElementById('output').innerHTML=response; }});">
			  <option>select</option>
			  				<?php 
                                $sql = "select order_id from $fields";
                                $result = $this->db->query($sql,__FILE__,__LINE__);
                                while($row = $this->db->fetch_array($result)){
                             ?><option value="<?php echo $row[0]; ?>"><?php echo $row[0]; ?></option>
                             <?php } ?>
			</select>
			<?php
				
				
			$html = ob_get_contents();
			ob_end_clean();
			return $html;	
	
	}
	
	function checkForSystemTask($id=''){
		ob_start();
		
		 $sql_tasks = "SELECT * FROM `".GLOBAL_TASK_LINK."` a , tbl_global_task_tree b where a.to_module_id = '$id' and a.to_module_name = 'system' and a.global_task_tree_id = b.global_task_tree_id";	
		 $result=$this->db->query($sql_tasks,__FILE__,__LINE__);
		 while( $row = $this->db->fetch_array($result) ){
	      $tree_id .= $row['global_task_tree_name'].','; 
	     }	 
		 
		echo $tree_id;
		 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	
	
 	function deleteReport($id='',$timestamp=''){
		ob_start();
		echo $timestamp;
/*		sql_del_temp = "DELETE FROM ".TBL_TEMPLATE." WHERE timestamp = '$timestamp'";
	    $this->db->query($sql_del_temp,__FILE__,__LINE__);

		sql_del_ins = "DELETE FROM insert_to_report WHERE timestamp = '$timestamp'";
	    $this->db->query($sql_del_ins,__FILE__,__LINE__);
		
		sql_del_assign = "DELETE FROM assign_report_to_system_task WHERE report_id = '$id'";
	    $this->db->query($sql_del_assign,__FILE__,__LINE__);

		sql_del_glink = "DELETE FROM ".GLOBAL_TASK_LINK." WHERE to_module_id = '$id' and to_module_name = 'system'";
	    $this->db->query($sql_del_glink,__FILE__,__LINE__);
*/				
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
  }	
}
?>