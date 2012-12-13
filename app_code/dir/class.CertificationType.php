<?php

class Certification_Type{
	
	var $certification_id;
	var $type_description;
	var $type_short_description;
	var $cert_type;
	var $note;
	var $state;
	var $db;
	var $Validity;
	var $Form;
	var $contact_id;
	var $type_select;
	var $type_select_replace;
	
	function __construct(){
		
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->Validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
	}
	
	function Add_Credentials($runat,$contact_id,$target,$certification_type_id='',$start_date='',$expiration_date='',$cre_note='',$state='')
	{
		ob_start();
		$this->contact_id = $contact_id;
		switch ($runat){
			
			case 'local' :
			

					$this->certification_type_id=$certification_type_id;
					$this->certification=$certification;
					$this->start_date=$start_date;
					$this->expiration_date=$expiration_date;
					$this->note=$note;
					$this->state=$state;

					//create client side validation
					$formName = 'frm_Add_Credentials';
					
										
					$ControlNames=array("certification_type_id"			=>array('certification_type_id',"","Please Select Credential Type ","spancertification_type_id_frm_Add_Credentials"),
										"start_date"			=>array('start_date',"","Please enter Start date","spanstart_date_frm_Add_Credentials"),
										"expiration_date"			=>array('expiration_date',"","Please enter Expiration date","spanexpiration_date_frm_Add_Credentials")
									);
					
					$ValidationFunctionName="frm_Add_Credentials_CheckValidity";
					
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($formName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
				?>
				<div class="prl">&nbsp;</div>
				<div id="lightbox">
					<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle">Add Credential</div>
					<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_credential').style.display='none';"><img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;"  class="form_main">
				<ul id="error_list">
					<li><span id="spancertification_type_id_frm_Add_Credentials"  class="normal" ></span></li>
					<li><span id="spanstart_date_frm_Add_Credentials"  class="normal"></span></li>
					<li><span id="spanexpiration_date_frm_Add_Credentials"  class="normal"></span></li>
				</ul>
				<form method="post" action="" enctype="multipart/form-data" name="<?php echo $formName?>" >
				
				  <table  class="table" align="center">
				    <tr>
				      <th>Credential Type</th>
					  <th>State </th>
				    </tr>
					
				    <tr>
				      <td ><select name="certification_type_id" id="certification_type_id" style="width:100%" onchange="javascript:					  cert.checkcerttype(this.value,{ preloader: 'prl'});  return false;">
                       <option value="">--select--</option>
					   <?php
					   $sql = "select * from ".EM_CERTIFICATION_TYPE;
				      $result = $this->db->query($sql,__FILE__,__LINE__);
				      while($row = $this->db->fetch_array($result)){
				      	?>
                        <option value="<?php echo $row[certification_id] ?>">
                          <?php echo $row[cert_type] ?></option>
                        <?php
				      }
				      ?>
                      </select></td>
					  <td>
					  <select name="state" id="state" disabled="disabled" >
						<option value="">Select State</option>
						<?php
							$state=file("state_us.inc");
							foreach($state as $val){
							$state = trim($val);
						?>
						<option <?php if($_POST['state']==$state){echo 'selected="selected"';}?> value="<?php echo $state;?>"><?php echo $state;?></option>
						<?php
							}
						?>
					</select>
					  </td>
					  </tr>
					  <tr>
					   <th>Effective Date</th>
				      <th>Expiration Date</th>
					  </tr>
					  <tr>
					  <td ><input name="start_date" type="text"  id="start_date" value="<?php echo $this->start_date;?>" readonly="true"/>		
					  	
						<script type="text/javascript">
						
						 var exp_date;
						 function start_cal()  {
						  var cal11=new Calendar({
								  inputField   	: "start_date",
								  dateFormat	: "%Y-%m-%d",
								  trigger		: "start_date",
								  weekNumbers   : true,
								  bottomBar		: true,
								  showTime      : 12,
								  onSelect		: function() {
														this.hide();
														document.<?php echo $formName;?>.start_date.value=this.selection.print("%Y-%m-%d");														
														if(exp_date<=this.selection.get()) {
															document.getElementById('expiration_date').value="";
														}														
														end_cal(this.selection.get()+1);	
													},
													
								
						  });
						  }
						
						</script>					  </td>
				      <td><input name="expiration_date" type="text"  id="expiration_date" value="<?php echo $this->expiration_date;?>" readonly="true"/>
						
						<script type="text/javascript">
						  
						 function end_cal(minDate) { 
						  var cal12=new Calendar({
								  inputField   	: "expiration_date",
								  dateFormat	: "%Y-%m-%d",
								  trigger		: "expiration_date",
								  weekNumbers   : true,
								  bottomBar		: true,
								  min			: minDate,
								  showTime      : 12,
								  onSelect		: function() {
														this.hide();
														document.<?php echo $formName;?>.expiration_date.value=this.selection.print("%Y-%m-%d");
														exp_date=this.selection.get();
													},
													
								
						  });
						  }
						 
					
               		 </script>					  </td>	      
					 			     
					 
				    </tr>
				    <tr>
				      <td colspan="2"><textarea name="cre_note" id="cre_note" style="width:100%" rows="3" ><?php echo $this->cre_note;?></textarea>
					  <span id="spancre_note_frm_Add_Credentials"></span></td>
				    </tr>
					<tr>
					<td colspan="2" align="right"><input type="button" name="submit" id="submit" style="width:auto" value="go" onclick="javascript: 
					 if(<?php echo $ValidationFunctionName?>()) {
					 if(document.getElementById('state').disabled==false && document.getElementById('state').value=='')
					 { alert('select state'); }
					 else{ 
					  cert.Add_Credentials('server',<?php echo $contact_id ?>,'<?php echo $target ?>',this.form.certification_type_id.value,this.form.start_date.value,this.form.expiration_date.value,this.form.cre_note.value,this.form.state.value,{target:'div_credential', preloader: 'prl'}  ); } } return false;"></td>
					  </tr>
				  </table>
				</form>
				</div></div></div>
				<?php
				break;
				
			case 'server' :

					$this->certification_type_id=$certification_type_id;
					$this->start_date=$start_date;
					$this->expiration_date=$expiration_date;
					$this->note=$note;
					$this->state=$state;
					
					$return =true;
					if($this->Form->ValidField($certification_type_id,'empty','Please Select Credential Type ')==false)
					$return =false;
					
					if($this->Form->ValidField($start_date,'empty','Please Enter start date')==false)
					$return =false;
					
					if($this->Form->ValidField($expiration_date,'empty','Please Enter expiry date')==false)
					$return =false;
					
					if($return) {
				
					if($this->contact_id){
					$insert_sql_array = array();
					$insert_sql_array[contact_id] = $this->contact_id;
					$insert_sql_array[certification_type_id] = $certification_type_id;
					$insert_sql_array[start_date] = $start_date;
					$insert_sql_array[expiration_date] = $expiration_date;
					$insert_sql_array[note] = $cre_note;
					$insert_sql_array[state] = $state;
					$this->db->insert(EM_CERTIFICATION,$insert_sql_array);
						}
				
				?>
				<script type="text/javascript">
				  document.getElementById('div_credential').style.display='none'; 
				  cert.Get_Credentials(<?php echo $this->contact_id ?>,{target:'div_allCre',preloader: 'prl'})
				</script>
				<?php
				
				}
				break;
			}	
					
				
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function checkcerttype($crettype)
	{
		ob_start();
		$sql="select * from ".EM_CERTIFICATION_TYPE." where certification_id='".$crettype."'";
		$result=$this->db->query($sql,__FILE__,__LINE__);
		$row=$this->db->fetch_array($result);
		
	
		if($row['credential_type'] == 'License')
		{
			?>
			<script>
			document.getElementById('state').disabled=false;
			</script>
			<?php 
			
		}
		else
		{
			?>
			<script>
			document.getElementById('state').disabled=true;
			</script>
			<?php 
			
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
		
		
	}
	/*******************************************************************************************************/

	function Edit_Credentials($runat,$contact_id,$cert_id,$target,$certification_type_id='',$start_date='',$expiration_date='',$cre_note='',$state)
	{

		ob_start();
		$this->certification_id = $cert_id;
		$this->contact_id = $contact_id;
		switch ($runat){
			
			case 'local' :
			if(count($_POST)>0 ){
					extract($_POST);
					$this->certification_type_id=$certification_type_id;
					$this->start_date=$start_date;
					$this->expiration_date=$expiration_date;
					$this->note=$note;
					
					}
					//create client side validation
					$formName = 'frm_edit_Credentials';
					
										
					$ControlNames=array("certification_type_id"			=>array('certification_type_id',"","Please Select Credential Type ","spancertification_type_id_frm_edit_Credentials"),
										
										"start_date"			=>array('start_date',"","Please enter Start date","spanstart_date_frm_edit_Credentials"),
										"expiration_date"			=>array('expiration_date',"","Please enter Expiration date","spanexpiration_date_frm_edit_Credentials"),
										
									);
					
					$ValidationFunctionName="frm_edit_Credentials_CheckValidity";
					
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($formName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
			
			
				$sql = "select * from ".EM_CERTIFICATION." where ".CERTIFICATION_ID." = '".$cert_id."'";
				$result = $this->db->query($sql,__FILE__,__lINE__);
				$arr = $this->db->fetch_array($result);
				?>
				<div class="prl">&nbsp;</div>
				<div id="lightbox" >
				
					<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle">Edit Credential</div>
					<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onClick="javascript: document.getElementById('div_credential').style.display='none';"><img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;"  class="form_main">
				<ul id="error_list">
					<li><span id="spancertification_type_id_frm_edit_Credentials"  class="normal"></span></li>
					<li><span id="spanstart_date_frm_edit_Credentials"  class="normal"></span></li>
					<li><span id="spanexpiration_date_frm_edit_Credentials"  class="normal"></span></li>
					
				  </ul>
				<form method="post" action="" enctype="multipart/form-data" name="<?php echo $formName?>" >
				    <table  class="table" align="center" >
	
				    <tr>
				      <th>Credential Type</th>
					  <th><?php 
					    $sql="select * from ".EM_CERTIFICATION_TYPE." where certification_id='".$arr[certification_type_id]."'";
						$result=$this->db->query($sql,__FILE__,__LINE__);
						$row=$this->db->fetch_array($result);
						if($row['credential_type'] == 'License')
						{
					  ?>
					  State
					  <?php } ?>
					  </th>
				      <th>Effective Date</th>
				      <th>Expiration Date</th>
					  
				    </tr>
				    <tr>
				      <td>  <input type="hidden" name="certification_type_id" id="certification_type_id" value="<?php echo $row[certification_id] ?>" />                       
                        <?php
				      	$sql = "select * from ".EM_CERTIFICATION_TYPE." where certification_id='".$arr[certification_type_id]."'";
				      	$result = $this->db->query($sql,__FILE__,__LINE__);
				      	$row = $this->db->fetch_array($result);
				      	 echo $row[cert_type]; 				     
				      ?>
                      </td>
					  <td><?php
					    $sql="select * from ".EM_CERTIFICATION_TYPE." where certification_id='".$arr[certification_type_id]."'";
						$result=$this->db->query($sql,__FILE__,__LINE__);
						$row=$this->db->fetch_array($result);
						
					
						if($row['credential_type'] == 'License')
						{
					  ?>
					  <select name="state" id="state">
						<option value="">Select State</option>
						<?php
							$state=file("../state_us.inc");
							foreach($state as $val){
							$state = trim($val);
						?>
						<option <?php if($arr['state']==$state){echo 'selected="selected"';}?> value="<?php echo $state;?>"><?php echo $state;?></option>
						<?php
							}
						?>
					</select>
					<?php } 
					else
					{
						?>
						<input type="hidden" name="state" id="state" value="NA" />
						<?php 
					}
					
					?>
					  </td>

				      <td>
				      <input name="start_date" type="text"  id="start_date" value="<?php echo $arr[START_DATE] ?>" readonly="true"/>					
					 <script type="text/javascript">
						
						 var exp_date;
						 function start_cal()  {
						  var cal11=new Calendar({
								  inputField   	: "start_date",
								  dateFormat	: "%Y-%m-%d",
								  trigger		: "start_date",
								  weekNumbers   : true,
								  bottomBar		: true,
								  showTime      : 12,
								  onSelect		: function() {
														this.hide();
														document.<?php echo $formName;?>.start_date.value=this.selection.print("%Y-%m-%d");														
														if(exp_date<=this.selection.get()) {
															document.getElementById('expiration_date').value="";
														}														
														end_cal(this.selection.get()+1);														
													},
													
								
						  });
						  }
						
						</script>               		 </td>
					 <td>
				      <input name="expiration_date" type="text"  id="expiration_date" value="<?php echo $arr[EXPIRATION_DATE] ?>" readonly="true"/>
					  <script type="text/javascript">
						 function end_cal(minDate) { 
						  var cal12=new Calendar({
								  inputField   	: "expiration_date",
								  dateFormat	: "%Y-%m-%d",
								  trigger		: "expiration_date",
								  weekNumbers   : true,
								  bottomBar		: true,
								  min			: minDate,
								  showTime      : 12,
								  onSelect		: function() {
														
														
														this.hide();
														document.<?php echo $formName;?>.expiration_date.value=this.selection.print("%Y-%m-%d");
														exp_date = this.selection.get();
													},
													
								
						  });
						  }
               		 </script>				      </td>
				      <td><input type="button" name="submit" id="submit" style="width:auto" value="save" onClick="javascript: 
					 if(<?php echo $ValidationFunctionName?>()) { 
					 if(document.getElementById('state').value=='')
					 { alert('select state'); }
					 else{ 
					 if(document.getElementById('state').value=='NA'){document.getElementById('state').value=''}
					 cert.Edit_Credentials('server',<?php echo $contact_id ?>,<?php echo $cert_id ?>,'<?php echo $target ?>',this.form.certification_type_id.value,this.form.start_date.value,this.form.expiration_date.value,this.form.cre_note.value,this.form.state.value,{target:'div_credential', preloader: 'prl'}); } }"></td>
				    </tr>
				    <tr>
				      <td colspan="6"><textarea name="cre_note" id="cre_note" style="width:100%;" rows="3" ><?php echo $arr[NOTE] ?></textarea> </td>
				    </tr>
				  </table>
				</form></div></div></div>
				<?php
				break;
				
				
			case 'server' :
					
					$return =true;
					if($this->Form->ValidField($certification_type_id,'empty','Please Select Credential Type ')==false)
					$return =false;
					if($this->Form->ValidField($start_date,'empty','Please Enter start date')==false)
					$return =false;
					if($this->Form->ValidField($expiration_date,'empty','Please Enter expiry date')==false)
					$return =false;
					
					if($return) {
				
						if($this->contact_id){
							$update_sql_array = array();
							$update_sql_array[contact_id] = $this->contact_id;
							$update_sql_array[certification_type_id] = $certification_type_id;
							$update_sql_array[start_date] = $start_date;
							$update_sql_array[expiration_date] = $expiration_date;
							$update_sql_array[note] = $cre_note;
							$update_sql_array[state] = $state;
							
							$this->db->update(EM_CERTIFICATION,$update_sql_array,CERTIFICATION_ID,$this->certification_id);
							}
						?>
						<script type="text/javascript">
						  document.getElementById('div_credential').style.display='none'; 
						  cert.Get_Credentials(<?php echo $this->contact_id ?>,{target:'div_allCre',preloader: 'prl'})
						</script>
						<?php
			
				}
				break;
			}	
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
		
	/*******************************************************************************************************/
	function Get_Credentials($contact_id)
	{		
		ob_start();
		$this->contact_id = $contact_id;
		$sql = "select * from ".EM_CERTIFICATION." where ".CONTACT_ID." = '".$this->contact_id."'";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		?><h2>License or Certification</h2>
		<table class="table"><?php
		while($row = $this->db->fetch_array($record)){
			$this->showCredential($row,$contact_id);
		}
		?>
		<tr>
		  <td colspan="5"><a href="javascript:void(0)" onclick="javascript:  cert.Add_Credentials('local',<?php echo $contact_id ?>,'div_credential',
			{ onUpdate: function(response,root){ 	
		   					 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';
							 start_cal();
							 }, preloader: 'prl'
						} ); return false;">Add</a></td>
		</tr>
		
		</table>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	
	function showCredential($row,$contact_id)
	{
		?>		
		  <tr>
		    <td>
		      <?php
		      $sql_type = "select * from ".EM_CERTIFICATION_TYPE." where ".CERTIFICATION_ID." = '".$row[CERTIFICATION_TYPE_ID]."'";
		      $rec_type = $this->db->query($sql_type,__FILE__,__LINE__);
		      $row_type = $this->db->fetch_array($rec_type);
		      echo $row_type[CERT_TYPE];
		      ?>
		    </td>
		    <td>:: <?php echo $row_type[CREDENTIAL_TYPE]; if($row_type[CREDENTIAL_TYPE] == 'License'){echo '('.$row['state'].')';}?></td>
		    <td>:: Effective Date <?php echo $row[START_DATE] ?></td>
		    <td>:: Exp <?php echo $row[EXPIRATION_DATE] ?></td>
		    <td><a href="javascript:void(0)" onclick="javascript: cert.Edit_Credentials('local',<?php echo $contact_id ?>,<?php echo $row[CERTIFICATION_ID] ?>,'div_credential',
			{ onUpdate: function(response,root){ 	
		   					 document.getElementById('div_credential').innerHTML=response;
		  				 	 document.getElementById('div_credential').style.display='';
							 start_cal();
							 end_cal();
							 }, preloader: 'prl'
						} ); return false;"> Edit </a>
						&nbsp;|&nbsp;
						<a href="#" onclick="javascript: if(confirm('are you sure ?')){ cert.Delete_Certification(<?php echo $row[CERTIFICATION_ID] ?>,<?php echo $contact_id ?>,
			{ target: 'div_allCre', preloader: 'prl'
						} ); }return false; "><img src="images/trash.gif" border="0"  align="absmiddle" /></a></td>
						
		  </tr>		
		  
		<?php
	}
	function Delete_Certification($certification_id,$contact_id) {
		$sql = "delete from ".EM_CERTIFICATION." where ".CERTIFICATION_ID." = '".$certification_id."'";
		$this->db->query($sql,__FILE__,__LINE__);
		return $this->Get_Credentials($contact_id);
	
	}
	function Show_Type($runat)
	{
		switch($runat)
		{
			
			case 'local' :
				
				$formName = 'frm_manage_credential_type';
				
				?>
					<script language="javascript" type="text/javascript">
					function frm_manage_credential_type_validate() {						
						 if(document.getElementById("cert_type_credential").value=='')
						 {
							document.getElementById('spancertification_type_id_frm_edit_Credentials').innerHTML="Please enter credential type";
							return false;
						 }						 		
					}				
					function frm_manage_credential_type_validateFeild() {						
						//alert(document.getElementById("cert_type_select_credential").selectedIndex);
						if(document.getElementById("cert_type_select_credential").value==document.getElementById("cert_type_select_replace_credential").value) {
							alert("Plz.. select different different credential type");
							return false;
						}
						else {					
						var location="manage_masterfields.php?id="+document.getElementById("cert_type_select_credential").value + "&replcedwith="+document.getElementById("cert_type_select_replace_credential").value+ "&action=delete";						
							window.location=location;
						
						}
					
					
					}
				
					</script>
					
					
					
				  <table width="100%" class="table">
				    <tr>
				      <td width="19%" >&nbsp;</td>
				      <td width="45%">&nbsp;</td>
				      <td width="36%" >replace deleted with</td>
				    </tr>
					<tr>
						<td colspan="30">
						<ul id="error_list">
						<li><span id="spancertification_type_id_frm_edit_Credentials" class="required" ></span></li>
						</ul>
						</td>
				    <tr>
					 <td align="right" class="textb" style="padding-left:10px;">Credential Type :</td>
				      <td >
				      <form method="post" action="" enctype="multipart/form-data" name="<?php echo $formName?>" >
				      <table width="100%">
					  <tr>					  
					  <td width="41%" ><input type="text" name="cert_type_credential" id="cert_type_credential" /></td>
					  <td width="33%" ><select name="credential_type" id="credential_type">
				      <option value="License" <?php if($arr[CERTIFICATION]=="License") echo "selected"; ?>>License</option>
				      <option value="Certification" <?php if($arr[CERTIFICATION]=="Certification") echo "selected"; ?>>Certification</option>
					  <option value="Insurance" <?php if($arr[CERTIFICATION]=="Insurance") echo "selected"; ?>>Insurance</option> 
				      </select></td>
				      <td width="26%" ><input type="submit" name="add_credential" id="add_credential" style="width:auto" value="go" onclick="return frm_manage_credential_type_validate();"/></td>
					  </tr>
					  </table>
					  </form>
					  					  
				      </td>
				      <td>
				      <form method="post" action="" enctype="multipart/form-data" name="<?php echo $formName?>" >
				      <table width="100%" class="table">
				      <tr>
				        <td width="42%" ><select name="cert_type_select_credential" id="cert_type_select_credential">
                          <?php
				      $sql = "select * from ".EM_CERTIFICATION_TYPE;
				      $result = $this->db->query($sql,__FILE__,__LINE__);
				      while($row = $this->db->fetch_array($result)){
				      	?>
                          <option value="<?php echo $row[certification_id] ?>" ><?php echo $row[cert_type] ?></option>
                          <?php
				      }
				      ?>
                        </select></td>
				        <td width="36%" >
				      <select name="cert_type_select_replace_credential" id="cert_type_select_replace_credential">
				      <?php
				      $sql = "select * from ".EM_CERTIFICATION_TYPE;
				      $result = $this->db->query($sql,__FILE__,__LINE__);
				      while($row = $this->db->fetch_array($result)){
				      	?>
				      	<option value="<?php echo $row[certification_id] ?>" ><?php echo $row[cert_type] ?></option>
				      	<?php
				      }
				      ?>
				      </select>				      </td>
				      <td width="22%"><a href="#" onclick= "javascript: if(confirm('Are you sure?')) return frm_manage_credential_type_validateFeild();"><img src="images/trash.gif" border="0" /></a></td>
				      </tr></table>
				      </form>
				      </td>
				    </tr>
				  </table>
			
				<?php
				break;
				
			case 'server' :
				
				extract($_POST);
				$this->cert_type = $cert_type_credential;	
				
				$sql="select * from ".EM_CERTIFICATION_TYPE." where ".CERT_TYPE." = '$this->cert_type'";
				if($this->db->record_number($sql)==0) 
				{
							
					if($this->cert_type)
					{
						$insert_sql_array = array();
						$insert_sql_array[CERT_TYPE] = $this->cert_type;
						$insert_sql_array[CREDENTIAL_TYPE]= $credential_type;
						$this->db->insert(EM_CERTIFICATION_TYPE,$insert_sql_array);
						$this->certification_id = $this->db->last_insert_id();
						$_SESSION[msg]="Credential Added Successfully!!";
					}
					else {
						$_SESSION[msg]="certification type is empty!!";
					}
				}
				else
				{
				
				$_SESSION[msg]="Credential of name '$this->cert_type' already exist!!";
				}
					?>
						<script type="text/javascript">
						 window.location = '<?php echo $_SERVER[PHP_SELF] ?>';
						</script>
					<?php
			break;
		}
	}
		
	function Delete_Cert($type_select, $type_select_replace){

			$sql = "delete from ".EM_CERTIFICATION_TYPE." where ".CERTIFICATION_ID." = '".$type_select."'";
			//echo $sql;
			$this->db->query($sql,__FILE__,__LINE__);

			if($type_select_replace!='')
			{
				$sql_contact = "update ".EM_CERTIFICATION." set ".CERTIFICATION_TYPE_ID." = '".$type_select_replace."' where ".CERTIFICATION_TYPE_ID." = '".$type_select."'";
				//echo $sql_contact;
				$sql_position = "update ".EM_STAFFING." set type = '".$type_select_replace."' where type = '".$type_select."'";
				//echo $sql_position;
				$this->db->query($sql_contact,__FILE__,__LINE__);
				$this->db->query($sql_position,__FILE__,__LINE__);
			}
			
			$_SESSION[msg]="Credential Replaced Successfully!!";
			?>
				<script type="text/javascript">
				 window.location = '<?php echo $_SERVER[PHP_SELF] ?>';
				</script>
			<?php			
	}
	
	

	
	
}

?>