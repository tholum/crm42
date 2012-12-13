<?php
class ManageCategories{

var $db;

	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
	}
	
	function set_archive($runat){
	switch($runat){
		case 'local':
			 ?>
			 <script language="javascript" type="text/javascript">
								
				function frm_manage_Recruiting_type_validateFeildRecruiting6() {						
					//alert(document.getElementById("cert_type_select_credential").selectedIndex);
					if(document.getElementById("event12").value==document.getElementById("event13").value) {
						alert("Plz.. select different different Archive Options");
						return false;
					}
					else {					
						var location="manage_categories.php?event12="+document.getElementById("event12").value + "&event13="+document.getElementById("event13").value+ "&action6=delete_archive";						
						window.location=location;						
					}					
				}
				
			</script>
			
			<form method="post" action="" enctype="multipart/form-data">
			<table width="100%" class="table">  
				  <tr>
						<th>Archive Options</th>
						<td><input type="text" name="archive" id="archive"/></td>
						<td><input type="submit" name="go7" value="go"  style="width:auto"/></td>
						<td><select name="event12" id="event12">
								<option value="">Select</option>
								<?php
								$sql_archive = "select * from ".erp_DROPDOWN_OPTION." where option_name='Archive Options'";
								$result_archive = $this->db->query($sql_archive,__FILE__,__lINE__);
								while($row_archive=$this->db->fetch_array($result_archive))
								{
									?>
									<option  value="<?php echo $row_archive['identifier']; ?>"><?php echo $row_archive['name']; ?></option> 
									<?php
								}
								
								?>
							</select>
						</td>
						<td><select name="event13" id="event13">
									<option value="">Select</option>
									<?php
									$sql = "select * from ".erp_DROPDOWN_OPTION." where option_name='Archive Options'";
									$result = $this->db->query($sql,__FILE__,__lINE__);
									while($row=$this->db->fetch_array($result))
									{
										?>
										<option  value="<?php echo $row['identifier']; ?>"><?php echo $row['name']; ?></option> 
										<?php
									}						
									?>
							</select>
						</td>
						<td><a href="javascript:void(0);" onclick= "javascript: if(confirm('Are you sure?')) return frm_manage_Recruiting_type_validateFeildRecruiting6();">
								<img src="images/trash.gif" border="0" /></a></td>		
			
  			  </tr>
			  </table>
				</form>
		 <?php
	break;
	
	case 'server':
	                extract($_POST);
					$name = $_POST['archive'];

					$sql="select * from ".erp_DROPDOWN_OPTION." where option_name= 'Archive Options'";
					$result =$this->db->query($sql,__FILE__,__lINE__);	
					$i=$this->db->num_rows($result);	


					$insert_sql_array = array();
					$insert_sql_array['option_name'] = 'Archive Options';
					$insert_sql_array['identifier'] = str_replace(" ","_",strtolower($name));
	   				$insert_sql_array['name'] = $name;
		            $this->db->insert(erp_DROPDOWN_OPTION,$insert_sql_array);
					
					$_SESSION['msg']= "Archive Option is Created Successfully!!";
				    ?>
					<script type="text/javascript">
					 window.location = '<?php echo $_SERVER['PHP_SELF']; ?>';
					</script>
					<?php	
					break;
	}
   }
   
	function delete_archive($event12, $event13){
   
		$sql = "delete from ".erp_DROPDOWN_OPTION." where option_name='Archive Options' and identifier = '".$event13."'";
		mysql_query($sql);
			        
            if($event13!=''){		
				$sql_contact = "UPDATE ".erp_DROPDOWN_OPTION." SET identifier = '".$event13."' where option_name='Archive Options' and identifier = '".$event12."'";		
			mysql_query($sql_contact);
			}
			?>
			<script>
				window.location='<?php echo $_SERVER['PHP_SELF']; ?>';
			</script>
			<?php
	}
	
	function set_read($runat){
	switch($runat){
		case 'local':
			 ?>
			 <script language="javascript" type="text/javascript">
								
				function frm_manage_Recruiting_type_validateFeildRecruiting7() {						
					//alert(document.getElementById("cert_type_select_credential").selectedIndex);
					if(document.getElementById("event12").value==document.getElementById("event13").value) {
						alert("Plz.. select different different Archive Options");
						return false;
					}
					else {					
						var location="manage_categories.php?event12="+document.getElementById("event12").value + "&event13="+document.getElementById("event13").value+ "&action6=delete_read";						
						window.location=location;						
					}					
				}
				
			</script>
			
			<form method="post" action="" enctype="multipart/form-data">
			<table width="100%" class="table">  
				  <tr>
						<th>read Options</th>
						<td><input type="text" name="read" id="read"/></td>
						<td><input type="submit" name="go8" value="go"  style="width:auto"/></td>
						<td><select name="event12" id="event12">
								<option value="">Select</option>
								<?php
								$sql_archive = "select * from ".erp_DROPDOWN_OPTION." where option_name='read Options'";
								$result_archive = $this->db->query($sql_archive,__FILE__,__lINE__);
								while($row_archive=$this->db->fetch_array($result_archive))
								{
									?>
									<option  value="<?php echo $row_archive['identifier']; ?>"><?php echo $row_archive['name']; ?></option> 
									<?php
								}
								
								?>
							</select>
						</td>
						<td><select name="event13" id="event13">
									<option value="">Select</option>
									<?php
									$sql = "select * from ".erp_DROPDOWN_OPTION." where option_name='read Options'";
									$result = $this->db->query($sql,__FILE__,__lINE__);
									while($row=$this->db->fetch_array($result))
									{
										?>
										<option  value="<?php echo $row['identifier']; ?>"><?php echo $row['name']; ?></option> 
										<?php
									}						
									?>
							</select>
						</td>
						<td><a href="javascript:void(0);" onclick= "javascript: if(confirm('Are you sure?')) return frm_manage_Recruiting_type_validateFeildRecruiting7();">
								<img src="images/trash.gif" border="0" /></a></td>		
			
  			  </tr>
			  </table>
				</form>
		 <?php
	break;
	
	case 'server':
	                extract($_POST);
					$name = $_POST['read'];

					$sql="select * from ".erp_DROPDOWN_OPTION." where option_name= 'read Options'";
					$result =$this->db->query($sql,__FILE__,__lINE__);	
					$i=$this->db->num_rows($result);	


					$insert_sql_array = array();
					$insert_sql_array['option_name'] = 'read Options';
					$insert_sql_array['identifier'] = str_replace(" ","_",strtolower($name));
	   				$insert_sql_array['name'] = $name;
		            $this->db->insert(erp_DROPDOWN_OPTION,$insert_sql_array);
					
					$_SESSION['msg']= "read/unread Option is Created Successfully!!";
				    ?>
					<script type="text/javascript">
					 window.location = '<?php echo $_SERVER['PHP_SELF']; ?>';
					</script>
					<?php	
					break;
	}
   }
   
	function delete_read($event12, $event13){
   
		$sql = "delete from ".erp_DROPDOWN_OPTION." where option_name='read Options' and identifier = '".$event13."'";
		mysql_query($sql);
			        
            if($event13!=''){		
				$sql_contact = "UPDATE ".erp_DROPDOWN_OPTION." SET identifier = '".$event13."' where option_name='read Options' and identifier = '".$event12."'";		
			mysql_query($sql_contact);
			}
			?>
			<script>
				window.location='<?php echo $_SERVER['PHP_SELF']; ?>';
			</script>
			<?php
	}    
	
	function set_fct_search_level($runat){
	switch($runat){
		case 'local':
			 ?>
			 <script language="javascript" type="text/javascript">
								
				function frm_manage_Recruiting_type_validateFeildRecruiting8() {						
					//alert(document.getElementById("cert_type_select_credential").selectedIndex);
					if(document.getElementById("event14").value==document.getElementById("event15").value) {
						alert("Plz.. select different different Flow Chart Task Level");
						return false;
					}
					else {					
						var location="manage_categories.php?event14="+document.getElementById("event14").value + "&event15="+document.getElementById("event15").value+ "&action_fct_search_level=delete_fct_search_level";						
						window.location=location;						
					}					
				}
				
			</script>
			
			<form method="post" action="" enctype="multipart/form-data">
			<table width="100%" class="table">  
				  <tr>
						<th>Flow Chart Task Options</th>
						<td><input type="text" name="fct_search_level" id="fct_search_level"/></td>
						<td><input type="submit" name="go_fct_search_level" value="go"  style="width:auto"/></td>
						<td><select name="event14" id="event14">
								<option value="">Select</option>
								<?php
								$sql_fct_search_level = "select * from ".erp_DROPDOWN_OPTION." where option_name='Flow Chart Task Options'";
								$result_fct_search_level = $this->db->query($sql_fct_search_level,__FILE__,__lINE__);
								while($row_fct_search_level=$this->db->fetch_array($result_fct_search_level))
								{
									?>
									<option  value="<?php echo $row_fct_search_level['identifier']; ?>"><?php echo $row_fct_search_level['name']; ?></option> 
									<?php
								}
								
								?>
							</select>
						</td>
						<td><select name="event15" id="event15">
									<option value="">Select</option>
									<?php
									$sql_fct_search_level1 = "select * from ".erp_DROPDOWN_OPTION." where option_name='Flow Chart Task Options'";
									$result_fct_search_level1 = $this->db->query($sql_fct_search_level1,__FILE__,__lINE__);
									while($row_fct_search_level1=$this->db->fetch_array($result_fct_search_level1))
									{
										?>
										<option  value="<?php echo $row_fct_search_level1['identifier']; ?>"><?php echo $row_fct_search_level1['name']; ?></option> 
										<?php
									}						
									?>
							</select>
						</td>
						<td><a href="javascript:void(0);" onclick= "javascript: if(confirm('Are you sure?')) return frm_manage_Recruiting_type_validateFeildRecruiting8();">
								<img src="images/trash.gif" border="0" /></a></td>		
			
  			  </tr>
			  </table>
				</form>
		 <?php
	break;
	
	case 'server':
	                extract($_POST);
					$name = $_POST['fct_search_level'];

					$sql="select * from ".erp_DROPDOWN_OPTION." where option_name= 'Flow Chart Task Options'";
					$result =$this->db->query($sql,__FILE__,__lINE__);	
					$i=$this->db->num_rows($result);	


					$insert_sql_array = array();
					$insert_sql_array['option_name'] = 'Flow Chart Task Options';
					$insert_sql_array['identifier'] = str_replace(" ","_",strtolower($name));
	   				$insert_sql_array['name'] = $name;
		            $this->db->insert(erp_DROPDOWN_OPTION,$insert_sql_array);
					
					$_SESSION['msg']= "Flow Chart Task Option is Created Successfully!!";
				    ?>
					<script type="text/javascript">
					 window.location = '<?php echo $_SERVER['PHP_SELF']; ?>';
					</script>
					<?php	
					break;
	}
   }
   
	function delete_fct_search_level($event14, $event15){
   
		$sql = "delete from ".erp_DROPDOWN_OPTION." where option_name='Flow Chart Task Options' and identifier = '".$event15."'";
		mysql_query($sql);
			        
            if($event15!=''){		
				$sql_fct_search_level = "UPDATE ".erp_DROPDOWN_OPTION." SET identifier = '".$event15."' where option_name='Flow Chart Task Options' and identifier = '".$event14."'";		
			mysql_query($sql_fct_search_level);
			}
			?>
			<script>
				window.location='<?php echo $_SERVER['PHP_SELF']; ?>';
			</script>
			<?php
	}    	
	
	function set_origin($runat){
	switch($runat){
		case 'local':
			 ?>
			 <script language="javascript" type="text/javascript">
								
				function frm_manage_Recruiting_type_validateFeildRecruiting9() {						
					//alert(document.getElementById("cert_type_select_credential").selectedIndex);
					if(document.getElementById("event16").value==document.getElementById("event17").value) {
						alert("Plz.. select different different Case Origin");
						return false;
					}
					else {					
						var location="manage_categories.php?event16="+document.getElementById("event16").value + "&event17="+document.getElementById("event17").value+ "&action_origin=delete_origin";						
						window.location=location;						
					}					
				}
				
			</script>
			
			<form method="post" action="" enctype="multipart/form-data">
			<table width="100%" class="table">  
				  <tr>
						<th>Case Origin Options</th>
						<td><input type="text" name="origin" id="origin"/></td>
						<td><input type="submit" name="go_origin" value="go"  style="width:auto"/></td>
						<td><select name="event16" id="event16">
								<option value="">Select</option>
								<?php
								$sql_fct_search_level = "select * from ".erp_DROPDOWN_OPTION." where option_name='Case Origin'";
								$result_fct_search_level = $this->db->query($sql_fct_search_level,__FILE__,__lINE__);
								while($row_fct_search_level=$this->db->fetch_array($result_fct_search_level))
								{
									?>
									<option  value="<?php echo $row_fct_search_level['identifier']; ?>"><?php echo $row_fct_search_level['name']; ?></option> 
									<?php
								}
								
								?>
							</select>
						</td>
						<td><select name="event17" id="event17">
									<option value="">Select</option>
									<?php
									$sql_fct_search_level1 = "select * from ".erp_DROPDOWN_OPTION." where option_name='Case Origin'";
									$result_fct_search_level1 = $this->db->query($sql_fct_search_level1,__FILE__,__lINE__);
									while($row_fct_search_level1=$this->db->fetch_array($result_fct_search_level1))
									{
										?>
										<option  value="<?php echo $row_fct_search_level1['identifier']; ?>"><?php echo $row_fct_search_level1['name']; ?></option> 
										<?php
									}						
									?>
							</select>
						</td>
						<td><a href="javascript:void(0);" onclick= "javascript: if(confirm('Are you sure?')) return frm_manage_Recruiting_type_validateFeildRecruiting9();">
								<img src="images/trash.gif" border="0" /></a></td>		
			
  			  </tr>
			  </table>
				</form>
		 <?php
	break;
	
	case 'server':
	                extract($_POST);
					$name = $_POST['origin'];

					$sql="select * from ".erp_DROPDOWN_OPTION." where option_name= 'Case Origin'";
					$result =$this->db->query($sql,__FILE__,__lINE__);	
					$i=$this->db->num_rows($result);	


					$insert_sql_array = array();
					$insert_sql_array['option_name'] = 'Case Origin';
					$insert_sql_array['identifier'] = str_replace(" ","_",strtolower($name));
	   				$insert_sql_array['name'] = $name;
		            $this->db->insert(erp_DROPDOWN_OPTION,$insert_sql_array);
					
					$_SESSION['msg']= "Case Origin Option is Created Successfully!!";
				    ?>
					<script type="text/javascript">
					 window.location = '<?php echo $_SERVER['PHP_SELF']; ?>';
					</script>
					<?php	
					break;
	}
   }
   
	function delete_origin($event16, $event17){
   
		$sql = "delete from ".erp_DROPDOWN_OPTION." where option_name='Case Origin' and identifier = '".$event17."'";
		mysql_query($sql);
			        
            if($event17!=''){		
				$sql_origin = "UPDATE ".erp_DROPDOWN_OPTION." SET identifier = '".$event17."' where option_name='Case Origin' and identifier = '".$event16."'";		
			mysql_query($sql_origin);
			}
			?>
			<script>
				window.location='<?php echo $_SERVER['PHP_SELF']; ?>';
			</script>
			<?php
	}    	


	function set_type($runat){
	switch($runat){
		case 'local':
			 ?>
			 <script language="javascript" type="text/javascript">
								
				function frm_manage_Recruiting_type_validateFeildRecruiting10() {						
					//alert(document.getElementById("cert_type_select_credential").selectedIndex);
					if(document.getElementById("event18").value==document.getElementById("event19").value) {
						alert("Plz.. select different different Case Type");
						return false;
					}
					else {					
						var location="manage_categories.php?event18="+document.getElementById("event18").value + "&event19="+document.getElementById("event19").value+ "&action_type=delete_type";						
						window.location=location;						
					}					
				}
				
			</script>
			
			<form method="post" action="" enctype="multipart/form-data">
			<table width="100%" class="table">  
				  <tr>
						<th>Case Type Options</th>
						<td><input type="text" name="case_type" id="case_type"/></td>
						<td><input type="submit" name="go_type" value="go"  style="width:auto"/></td>
						<td><select name="event18" id="event18">
								<option value="">Select</option>
								<?php
								$sql_fct_search_level = "select * from ".erp_DROPDOWN_OPTION." where option_name='Case Type'";
								$result_fct_search_level = $this->db->query($sql_fct_search_level,__FILE__,__lINE__);
								while($row_fct_search_level=$this->db->fetch_array($result_fct_search_level))
								{
									?>
									<option  value="<?php echo $row_fct_search_level['identifier']; ?>"><?php echo $row_fct_search_level['name']; ?></option> 
									<?php
								}
								
								?>
							</select>
						</td>
						<td><select name="event19" id="event19">
									<option value="">Select</option>
									<?php
									$sql_fct_search_level1 = "select * from ".erp_DROPDOWN_OPTION." where option_name='Case Type'";
									$result_fct_search_level1 = $this->db->query($sql_fct_search_level1,__FILE__,__lINE__);
									while($row_fct_search_level1=$this->db->fetch_array($result_fct_search_level1))
									{
										?>
										<option  value="<?php echo $row_fct_search_level1['identifier']; ?>"><?php echo $row_fct_search_level1['name']; ?></option> 
										<?php
									}						
									?>
							</select>
						</td>
						<td><a href="javascript:void(0);" onclick= "javascript: if(confirm('Are you sure?')) return frm_manage_Recruiting_type_validateFeildRecruiting10();">
								<img src="images/trash.gif" border="0" /></a></td>		
			
  			  </tr>
			  </table>
				</form>
		 <?php
	break;
	
	case 'server':
	                extract($_POST);
					$name = $_POST['case_type'];

					$sql="select * from ".erp_DROPDOWN_OPTION." where option_name= 'Case Type'";
					$result =$this->db->query($sql,__FILE__,__lINE__);	
					$i=$this->db->num_rows($result);	


					$insert_sql_array = array();
					$insert_sql_array['option_name'] = 'Case Type';
					$insert_sql_array['identifier'] = str_replace(" ","_",strtolower($name));
	   				$insert_sql_array['name'] = $name;
		            $this->db->insert(erp_DROPDOWN_OPTION,$insert_sql_array);
					
					$_SESSION['msg']= "Case Type Option is Created Successfully!!";
				    ?>
					<script type="text/javascript">
					 window.location = '<?php echo $_SERVER['PHP_SELF']; ?>';
					</script>
					<?php	
					break;
	}
   }
   
	function delete_type($event18, $event19){
   
		$sql = "delete from ".erp_DROPDOWN_OPTION." where option_name='Case Type' and identifier = '".$event19."'";
		mysql_query($sql);
			        
            if($event19!=''){		
				$sql_type = "UPDATE ".erp_DROPDOWN_OPTION." SET identifier = '".$event19."' where option_name='Case Type' and identifier = '".$event18."'";		
			mysql_query($sql_type);
			}
			?>
			<script>
				window.location='<?php echo $_SERVER['PHP_SELF']; ?>';
			</script>
			<?php
	}    	


	function set_staus($runat){
	switch($runat){
		case 'local':
			 ?>
			 <script language="javascript" type="text/javascript">
								
				function frm_manage_Recruiting_type_validateFeildRecruiting11() {						
					//alert(document.getElementById("cert_type_select_credential").selectedIndex);
					if(document.getElementById("event20").value==document.getElementById("event21").value) {
						alert("Plz.. select different different Case Status");
						return false;
					}
					else {					
						var location="manage_categories.php?event20="+document.getElementById("event20").value + "&event21="+document.getElementById("event21").value+ "&action_staus=delete_staus";						
						window.location=location;						
					}					
				}
				
			</script>
			
			<form method="post" action="" enctype="multipart/form-data">
			<table width="100%" class="table">  
				  <tr>
						<th>Case Status Options</th>
						<td><input type="text" name="staus" id="staus"/></td>
						<td><input type="submit" name="go_staus" value="go"  style="width:auto"/></td>
						<td><select name="event20" id="event20">
								<option value="">Select</option>
								<?php
								$sql_fct_search_level = "select * from ".erp_DROPDOWN_OPTION." where option_name='Case Status'";
								$result_fct_search_level = $this->db->query($sql_fct_search_level,__FILE__,__lINE__);
								while($row_fct_search_level=$this->db->fetch_array($result_fct_search_level))
								{
									?>
									<option  value="<?php echo $row_fct_search_level['identifier']; ?>"><?php echo $row_fct_search_level['name']; ?></option> 
									<?php
								}
								
								?>
							</select>
						</td>
						<td><select name="event21" id="event21">
									<option value="">Select</option>
									<?php
									$sql_fct_search_level1 = "select * from ".erp_DROPDOWN_OPTION." where option_name='Case Status'";
									$result_fct_search_level1 = $this->db->query($sql_fct_search_level1,__FILE__,__lINE__);
									while($row_fct_search_level1=$this->db->fetch_array($result_fct_search_level1))
									{
										?>
										<option  value="<?php echo $row_fct_search_level1['identifier']; ?>"><?php echo $row_fct_search_level1['name']; ?></option> 
										<?php
									}						
									?>
							</select>
						</td>
						<td><a href="javascript:void(0);" onclick= "javascript: if(confirm('Are you sure?')) return frm_manage_Recruiting_type_validateFeildRecruiting11();">
								<img src="images/trash.gif" border="0" /></a></td>		
			
  			  </tr>
			  </table>
				</form>
		 <?php
	break;
	
	case 'server':
	                extract($_POST);
					$name = $_POST['staus'];

					$sql="select * from ".erp_DROPDOWN_OPTION." where option_name= 'Case Status'";
					$result =$this->db->query($sql,__FILE__,__lINE__);	
					$i=$this->db->num_rows($result);	


					$insert_sql_array = array();
					$insert_sql_array['option_name'] = 'Case Status';
					$insert_sql_array['identifier'] = str_replace(" ","_",strtolower($name));
	   				$insert_sql_array['name'] = $name;
		            $this->db->insert(erp_DROPDOWN_OPTION,$insert_sql_array);
					
					$_SESSION['msg']= "Case Status Option is Created Successfully!!";
				    ?>
					<script type="text/javascript">
					 window.location = '<?php echo $_SERVER['PHP_SELF']; ?>';
					</script>
					<?php	
					break;
	}
   }
   
	function delete_staus($event20, $event21){
   
		$sql = "delete from ".erp_DROPDOWN_OPTION." where option_name='Case Status' and identifier = '".$event21."'";
		mysql_query($sql);
			        
            if($event21!=''){		
				$sql_status = "UPDATE ".erp_DROPDOWN_OPTION." SET identifier = '".$event21."' where option_name='Case Status' and identifier = '".$event20."'";		
			mysql_query($sql_status);
			}
			?>
			<script>
				window.location='<?php echo $_SERVER['PHP_SELF']; ?>';
			</script>
			<?php
	}
	
	///////****CASE TYPE SUBOPTIONS*****///////////	  
  function set_type_suboptions($runat='',$option=''){
	switch($runat){
		case 'local':
		ob_start();
		$options = 'Case Type '.$option; $options = ucwords($options);
					echo $options;			
			 ?>
			 <script language="javascript" type="text/javascript">
								
				function frm_manage_Recruiting_type_validateFeildRecruiting15() {						
					//alert(document.getElementById("cert_type_select_credential").selectedIndex);
					if(document.getElementById("event28").value==document.getElementById("event29").value) {
						alert("Plz.. select different different Case Type Suboptions");
						return false;
					}
					else {					
						var location="manage_categories.php?event28="+document.getElementById("event28").value + "&event29="+document.getElementById("event29").value+ "&option_name="+document.getElementById("option_name").value + "&name_hiden="+document.getElementById("name_hiden").value + "&action_type_suboption=delete_type_suboption";						
						window.location=location;						
					}					
				}
		//suboption
		function frm_manage_Recruiting_type_validateFeildRecruiting16() {						
					//alert(document.getElementById("cert_type_select_credential").selectedIndex);
					 if(document.getElementById("event30").value== "")
					{
						alert("Plz.. select Case Type Options");
						return false;
					}
					else if(document.getElementById("case_type_suboptions").value== "")
					 {
						alert("Plz.. Enter The Suboption Name");
						return false;
				   	}
					else {					
						return true;				
					}					
				}
				
			</script>
			
			<form method="post" action="" enctype="multipart/form-data">
			<table width="100%" class="table">  
				  <tr>
						<th width="21%">Case Type Suboptions 
						<input type="text" name="option_name" id="option_name" value="<?php echo "$options";?>" />
							 </th>
						<td width="18%">
						    <select name="event30" id="event30"
						            onchange="javascript:obj.set_type_suboptions('local',
									                              this.value,
																  {target:'case_type_suboption'}
									);">
								<option value="">Select</option>
								<?php
								$sql_fct_search_level = "select * from ".erp_DROPDOWN_OPTION." where option_name='Case Type'";
								$result_fct_search_level = $this->db->query($sql_fct_search_level,__FILE__,__lINE__);
								while($row_fct_search_level=$this->db->fetch_array($result_fct_search_level))
								{
									?>
									<option  value="<?php echo $row_fct_search_level['identifier']; ?>"<?php if($option==$row_fct_search_level['identifier']) echo 'selected="selected"'; ?>><?php echo $row_fct_search_level['name']; ?></option> 
									<?php
								}
								
								?>
							</select></td>
							<td width="20%"><input type="text" name="case_type_suboptions" id="case_type_suboptions" /></td>
						<td width="6%"><input name="go_type_suboption" type="submit" id="go_type_suboption"  style="width:auto" value="go" onclick="return frm_manage_Recruiting_type_validateFeildRecruiting16();"/></td>
						<td width="14%"><select name="event28" id="event28">
								<option value="">Select</option>
								<?php if( $option != '' ){
								$sql_fct_search_level = "select * from ".erp_DROPDOWN_OPTION." where option_name='$options'";
								$result_fct_search_level = $this->db->query($sql_fct_search_level,__FILE__,__lINE__);
								while($row_fct_search_level=$this->db->fetch_array($result_fct_search_level))
								{
									?>
									<option  value="<?php echo $row_fct_search_level['name']; ?>"><?php echo $row_fct_search_level['name']; ?></option> 
									<?php }
								}
								
								?>
							</select>						</td>
						<td width="14%"><select name="event29" id="event29">
									<option value="">Select</option>
									<?php if( $option != '' ){
									$sql_fct_search_level1 = "select * from ".erp_DROPDOWN_OPTION." where option_name='$options'";
								$result_fct_search_level1 = $this->db->query($sql_fct_search_level1,__FILE__,__lINE__);
									while($row_fct_search_level1=$this->db->fetch_array($result_fct_search_level1))
									{
										?>
										<option  value="<?php echo $row_fct_search_level1['name']; ?>"><?php echo $row_fct_search_level1['name']; ?></option> 
										<?php
									} }					
									?>
							</select>						</td>
						<td width="7%"><a href="javascript:void(0);" onclick= "javascript: if(confirm('Are you sure?')) return frm_manage_Recruiting_type_validateFeildRecruiting15();">
								<img src="images/trash.gif" border="0" /></a></td>		
  			  </tr>
			  </table>
			</form>
		 <?php
		 $html=ob_get_contents();
		 ob_end_clean();
		 return $html;
	break;
	
	case 'server':
	                extract($_POST);
					$name = $_POST['case_type_suboptions'];
					$option_name="Case Type"." ".$_POST['event30'];
					$sql="select * from ".erp_DROPDOWN_OPTION." where option_name= '$option_name' AND name='$name'";
					$result =$this->db->query($sql,__FILE__,__lINE__);	
					$i=$this->db->num_rows($result);
					if($i!='')
					{
						$_SESSION['msg']= "Case Type Suboption is already exist!!";
					}	
					else{

					$insert_sql_array = array();
					$insert_sql_array['option_name'] = 'Case Type'." ".$_POST['event30'];
					$insert_sql_array['identifier'] = strtolower($insert_sql_array['option_name']);
	   				$insert_sql_array['name'] = $name;
		           $this->db->insert(erp_DROPDOWN_OPTION,$insert_sql_array);
					}
					$_SESSION['msg']= "Case Type Suboption is Created Successfully!!";
				    ?>
					<script type="text/javascript">
					 window.location = '<?php echo $_SERVER['PHP_SELF']; ?>';
					</script>
					<?php	
					break;
			
	}
   }
   
	function delete_typ_suboption($event28, $event29, $options){
   
		$sql = "delete from ".erp_DROPDOWN_OPTION." where option_name='$options' and name = '".$name_hiden."'";
		mysql_query($sql);
			        
            if($name_hiden!=''){		
				$sql_type = "UPDATE ".erp_DROPDOWN_OPTION." SET identifier = '".$event29."' where option_name='$options' and identifier = '".$event28."'";		
			mysql_query($sql_type);
			}
			?>
			<script>
				window.location='<?php echo $_SERVER['PHP_SELF']; ?>';
			</script>
			<?php
	}
	
 }  //end of class
?>



