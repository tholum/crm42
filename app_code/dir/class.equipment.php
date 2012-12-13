<?php

class Equipment
{
	var $category_id;
	var $equipment_id;
	var $equipment_category;
	var $equipment_name;
	var $equipment_description;
	var $equipment_status;
	var $notes;
	var $db;
	var $Validity;
	var $Form;
	var $event_id;

	
	function __construct()
	{
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->Validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
	}
	
	function addCategory($runat)
	{
		switch ($runat){
			case 'local':
			
				$sql = "select * from ".EM_EQUIPMENT_CATEGORY;
				$result = $this->db->query($sql,__FILE__,__lINE__);
				$result2 = $this->db->query($sql,__FILE__,__lINE__);
				
				$formName = 'frm_addCategory';
				$ControlNames=array("category_name"			=>array('category_name',"''","Please enter equipment category","span_category_name")
							);
					
				$ValidationFunctionName="frm_addCategory_CheckValidity";
				
				$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($formName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
				echo $JsCodeForFormValidation;
				?>
				<script language="javascript" type="text/javascript">
					function validateFeildEquipment() {						
						//alert(document.getElementById("cert_type_select_credential").selectedIndex);
						if(document.getElementById("type_select2").value==document.getElementById("type_select_replace2").value) {
							alert("Plz.. select different different equipment category");
							return false;
						}
						else {					
						var location="manage_masterfields.php?id="+document.getElementById("type_select2").value + "&replcedwith="+document.getElementById("type_select_replace2").value+ "&action=delete_equipment";						
							window.location=location;
						
						}
					
					
					}
				
					</script>
				<ul id="error_list">
				<li><span id="span_category_name" class="normal" ></span></li>
				</ul>
				
				<form action="" method="POST" name="<?php echo $formName; ?>" id="<?php echo $formName; ?>" enctype="multipart/form-data">
				<table width="100%" class="table">
				  <tr>
				    <th width="21%">Equipment Category:</th>
				    <td width="32%"><input type="text" name="category_name" id="category_name"></td>
				    <td width="6%"><input type="submit" name="go" id="go" value="Go" style="width:auto"  onclick="return <?php echo $ValidationFunctionName ?>();" ></td>
					<td width="5%">&nbsp;</td>
					<td width="13%"><select name="type_select2" id="type_select2">
                          <?php
				      
				      while($row = $this->db->fetch_array($result)){
				      	?>
                          <option value="<?php echo $row[category_id] ?>" ><?php echo $row[category_name] ?></option>
                          <?php
				      }
				      ?>
                        </select></td>
						
				      <td width="13%">
				      <select name="type_select_replace2" id="type_select_replace2">
				      <?php
				      
				      while($row = $this->db->fetch_array($result2)){
				      	?>
				      	<option value="<?php echo $row[category_id] ?>" ><?php echo $row[category_name] ?></option>
				      	<?php
				      }
				      ?>
				      </select></td>
				      <td width="10%"><a href="#" onclick= "javascript: if(confirm('Are you sure?')) return validateFeildEquipment();">
					  					<img src="images/trash.gif" border="0" /></a></td>
					
				  </tr>
				</table>
				</form>
				
				<?php
				break;
			case 'server':
				extract($_POST);
				$this->equipment_category = $category_name;
				
				$return =true;
				if($this->Form->ValidField($this->equipment_category,'empty','Please enter category name')==false)
					$return =false;
					
				if($return){
					$insert_sql_array = array();
					$insert_sql_array['category_name'] = $this->equipment_category;
					$this->db->insert(EM_EQUIPMENT_CATEGORY,$insert_sql_array);
					$_SESSION[msg] = 'Equipment Category has been added';
					?>
					<script type="text/javascript">
					window.location = "<?php echo $_SERVER['PHP_SELF'] ?>";
					</script>
					<?php
				}
				else {
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
					$this->addCategory('local');
				}
				break;
			default:
				echo "Wrong parameter passed";
		}
	}
	
	  function Delete_Equipment($type_select, $type_select_replace){
		
				$sql_del="delete from ".EM_EQUIPMENT_CATEGORY." where  category_id= '".$type_select."'";
				$this->db->query($sql_del,__FILE__,__LINE__);
				//echo $sql_del;
				if($type_select_replace!='')
				{
					$sql_equip = "update ".EM_EQUIPMENT." set equipment_category = '".$type_select_replace."' where equipment_category = '".$type_select."'";
					//echo $sql_equip;
					$this->db->query($sql_equip,__FILE__,__LINE__);
				}

			   // $_SESSION[msg]="Event Status Replaced Successfully!!";
			?>
				<script type="text/javascript">
				 window.location = '<?php echo $_SERVER[PHP_SELF] ?>';
				</script>
			<?php			
		
		
	} // end of Del Equipment
	
	function addEquipment($runat,$equipment_category='',$equipment_name='',$equipment_description='',$equipment_status='',$notes='')
	{
		ob_start();
		switch ($runat) {
			case 'local':
				$formName = 'frm_add_category';
				$ControlNames=array("equipment_category"			=>array('equipment_category',"''","Please select Category name","span_equipment_category"),
									"equipment_name"			=>array('equipment_name',"''","Please enter equipment name","span_equipment_name")
									);
					
				$ValidationFunctionName="frm_addEquipment_CheckValidity";
				
				$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($formName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
				echo $JsCodeForFormValidation;
				?>
				
				<div class="prl">&nbsp;</div>
				<div id="lightbox">
				<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
				<div id="TB_ajaxWindowTitle">Add To Event</div>							
				<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_equip').style.display='none';"><img border="0" src="images/close.gif" alt="close" /></a></div>

				</div>
				<div  class="white_content"> 
				<div style="padding:20px;" >	
				
				<form action="" method="POST" name="<?php echo $formName; ?>" id="<?php echo $formName; ?>" enctype="multipart/form-data">
				<table width="100%" class="table">
				  <tr>
				    <td colspan="2">
				      <ul id="error_list">
				        <li id="span_equipment_category"></li>
				        <li id="span_equipment_name"></li>
				      </ul>
				    </td>
				  </tr>
				  <tr>
				    <th>Equipment Category:</th>
				    <td><select name="equipment_category" id="equipment_category" style="width:100%">
					<option value="">---select---</option>
				    <?php
				    $sql = "select * from ".EM_EQUIPMENT_CATEGORY;
				    $result = $this->db->query($sql,__FILE__,__LINE__);
				    while ($row = $this->db->fetch_array($result)){
				    ?>
				    	<option value="<?php echo $row[category_id] ?>"><?php echo $row[category_name] ?></option>
				    <?php
				    }
				    ?>
				    </select></td>
				   </tr>
				  <tr>
				    <th>Equipment Name:</th>
				    <td><input type="text" name="equipment_name" id="equipment_name"></td>
				  </tr>
				  
				  <tr>
				    <th>Equipment Description:</th>
				    <td><input type="text" name="equipment_description" id="equipment_description"></td>
				  </tr>	
				  
				  <tr>
				    <th>Equipment Status:</th>
				    <?php
					$sql = "select * from ".EM_EQUIPMENT_STATUS;
					$result = $this->db->query($sql,__FILE__,__lINE__);					
					?>				
					<td><select name="equipment_status" id="equipment_status">
					<option value="">---select---</option>
					<?php while($row = $this->db->fetch_array($result)){
				      	?>
				      	<option value="<?php echo $row['status_id'] ?>" ><?php echo $row['status'] ?></option>
				      	<?php } ?>					
					</select>					
					</td>
				  </tr>				  
				  
				  <tr>				  
				    <th>Note:</th>
				    <td><textarea name="notes" id="notes" style="width:100%"></textarea></td>
				  </tr>
				  <tr>
				    <td colspan="2" align="right"><input type="submit" name="add" id="add" value="Add" style="width:auto" onclick="if(<?php echo $ValidationFunctionName ?>()){ equipment.addEquipment('server',this.form.equipment_category.value,this.form.equipment_name.value,this.form.equipment_description.value,this.form.equipment_status.value,this.form.notes.value,{preloader:'prl',onUpdate: function(response,root){
					document.getElementById('div_equip').style.display='none';
					equipment.getEquipments({onUpdate: function(response,root){document.getElementById('showequip').innerHTML=response;
$('#search_equip')
.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[1,0]], headers: { 0:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
}})
					}}); } return false;"></td>
				  </tr>
				</table>
				</form>
				</div></div></div>
				<?php
				break;
				
			case 'server':
				
				$this->equipment_category = $equipment_category;
				$this->equipment_name = $equipment_name;
				$this->equipment_description = $equipment_description;
				$this->equipment_status = $equipment_status;				
				$this->notes = $notes;
				
				$return =true;
				if($this->Form->ValidField($this->equipment_category,'empty','Please select category name')==false)
					$return =false;
				if($this->Form->ValidField($this->equipment_name,'empty','Please enter equipment name')==false)
					$return =false;
				
				if($return){
					$insert_sql_array = array();
					$insert_sql_array['equipment_category'] = $this->equipment_category;
					$insert_sql_array['equipment_name'] = $this->equipment_name;
					$insert_sql_array['equipment_description'] = $this->equipment_description;
					$insert_sql_array['equipment_status'] = $this->equipment_status;
					$insert_sql_array['note'] = $this->notes;
					$this->db->insert(EM_EQUIPMENT,$insert_sql_array);				
				}
				else {
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
					$this->addEquipment('local');
				}
				break;
		
			default:
				echo 'wrong parameter passed';
				break;
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function editEquipment($runat,$equipment_id,$equipment_category='',$equipment_name='',$equipment_description='',$equipment_status='',$notes='')
	{
		ob_start();
		$this->equipment_id = $equipment_id;		
		switch ($runat) {
			case 'local':
				$formName = 'frm_add_category';
				$ControlNames=array("equipment_category"			=>array('equipment_category',"''","Please select Category name","span_equipment_category"),
									"equipment_name"			=>array('equipment_name',"''","Please enter equipment name","span_equipment_name")
									);
					
				$ValidationFunctionName="frm_addEquipment_CheckValidity";
				
				$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($formName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
				echo $JsCodeForFormValidation;
				
				$sql = "select * from ".EM_EQUIPMENT." where equipment_id= '".$this->equipment_id."'";
				$result = $this->db->query($sql,__FILE__,__LINE__);
				$row_equip = $this->db->fetch_array($result);
				?>
				
				<div class="prl">&nbsp;</div>
				<div id="lightbox">
				
				<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
				<div id="TB_ajaxWindowTitle">Edit Equipment</div>							
				<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_equip').style.display='none';"><img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;" class="form_main">
					
				<form action="" method="POST" name="<?php echo $formName; ?>" id="<?php echo $formName; ?>" enctype="multipart/form-data">
				<ul id="error_list">
				        <li id="span_equipment_category" class="normal"></li>
				        <li id="span_equipment_name" class="normal"></li>
				      </ul>
				<table width="100%" class="table">
				  <tr>
				    <th>Equipment Category:</th>
				    <td><select name="equipment_category" id="equipment_category">
					<option value="">---select---</option>
				    <?php
				    $sql = "select * from ".EM_EQUIPMENT_CATEGORY;
				    $result = $this->db->query($sql,__FILE__,__LINE__);
				    while ($row = $this->db->fetch_array($result)){
				    ?>
				    	<option value="<?php echo $row['category_id'] ?>" <?php if($row_equip['equipment_category']==$row['category_id']) echo "selected"; ?>><?php echo $row['category_name'] ?></option>
				    <?php
				    }
				    ?>
				    </select></td>
				   </tr>
				  <tr>
				    <th>Equipment Name:</th>
				    <td><input type="text" name="equipment_name" id="equipment_name" value="<?php echo $row_equip['equipment_name'] ?>"></td>
				  </tr>
				  <tr>
				    <th>Equipment Description:</th>
				    <td><input type="text" name="equipment_description" id="equipment_description" value="<?php echo $row_equip['equipment_description'] ?>" ></td>
				  </tr>
				  <tr>
				    <th>Equipment Status:</th>
				    <td><?php
					$sql = "select * from ".EM_EQUIPMENT_STATUS;
					$result = $this->db->query($sql,__FILE__,__LINE__);
					?>
				      <select name="equipment_status" id="equipment_status">
					<option value="">---Select---</option>
					<?php while($row = $this->db->fetch_array($result)){ ?>
					<option value="<?php echo $row['status_id']; ?>" <?php if($row_equip['equipment_status']==$row['status_id']) echo "selected"; ?> >
					<?php echo $row['status']; ?></option>
					<?php } ?>
					</select>					
					</td>				
				  </tr>
				  <tr>
				    <th>Note:</th>
				    <td><textarea name="notes" id="notes"><?php echo $row_equip[note] ?></textarea></td>
				  </tr>
				  <tr>
				    <td colspan="2" align="right"><input type="submit" name="save" id="save" value="Save" style="width:auto" onclick="javascript: if(<?php echo $ValidationFunctionName ?>()){ equipment.editEquipment('server','<?php echo $this->equipment_id; ?>',this.form.equipment_category.value,this.form.equipment_name.value,this.form.equipment_description.value,this.form.equipment_status.value,this.form.notes.value,{preloader:'prl',onUpdate: function(response,root){
					document.getElementById('div_equip').style.display='none';
					equipment.getEquipments({onUpdate: function(response,root){
						equipment.getEquipments({onUpdate: function(response,root){document.getElementById('showequip').innerHTML=response;
$('#search_equip')
.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[1,0]], headers: { 0:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
}});
						},preloader: 'prl'
						})
					}}); } return false;"></td>
				  </tr>
				</table>
				</form>
				</div></div></div>
				<?php
				break;
				
			case 'server':
				extract($_POST);
				$this->equipment_category = $equipment_category;
				$this->equipment_name = $equipment_name;
				$this->equipment_description = $equipment_description;
				$this->equipment_status = $equipment_status;
				$this->notes = $notes;
				
				$return =true;
				if($this->Form->ValidField($this->equipment_category,'empty','Please select category name')==false)
					$return =false;
				if($this->Form->ValidField($this->equipment_name,'empty','Please enter equipment name')==false)
					$return =false;
				
				if($return){
					$update_sql_array = array();
					$update_sql_array['equipment_category'] = $this->equipment_category;
					$update_sql_array['equipment_name'] = $this->equipment_name;
					$update_sql_array['equipment_description'] = $this->equipment_description;
					$update_sql_array['equipment_status'] = $this->equipment_status;
					$update_sql_array['note'] = $this->notes;
					$this->db->update(EM_EQUIPMENT,$update_sql_array,'equipment_id',$this->equipment_id);
					
				}
				else {
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
					$this->editEquipment('local',$this->equipment_id);
				}
				break;
		
			default:
				echo 'wrong parameter passed';
				break;
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function EquipmentSearchBox($obj='equipment',$functn='getEquipments',$target='showequip',$event_id='')
	{	
		?>
		<div class="form_main"> <form onsubmit="return false;" name="srch" id="srch">
		<table width="100%" class="table">
		<tr>
		<td width="25%">Equipment Name:</td>
		<td width="25%"><input name="search" type="text" id="search" size="60" onkeyup="<?php echo $obj; ?>.<?php echo $functn; ?>('popup',this.value,'<?php echo $event_id; ?>',this.form.searchType.value,
		{onUpdate: function(response,root){document.getElementById('<?php echo $target; ?>').innerHTML=response;
$('#search_equip')
.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[1,0]], headers: { 0:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
}}); " autocomplete='off' /></td>
		<td width="25%">Equipment Catagory:</td>
		<td width="25%">
		<select name="searchType" id="searchType" onchange="<?php echo $obj; ?>.<?php echo $functn; ?>('popup',this.form.search.value,'<?php echo $event_id; ?>',this.value,
		{onUpdate: function(response,root){document.getElementById('<?php echo $target; ?>').innerHTML=response;
$('#search_equip')
.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[1,0]], headers: { 0:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
}});" >
			<option value="">---select---</option>
			<?php
			$sql = "select * from ".EM_EQUIPMENT_CATEGORY;
			$result = $this->db->query($sql,__FILE__,__LINE__);
			while ($row = $this->db->fetch_array($result)){
			?>
				<option value="<?php echo $row['category_id'] ?>"><?php echo $row['category_name'] ?></option>
			<?php
			}
			?>
				</select>
	</td></tr>
	
	</table>
	 <div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
	</form>
	  </div>
	<?

	} // end of HoetlSearchBox
	
		function EquipmentSearchBox2($obj='equipment',$functn='getEquipments2',$target='showequip',$event_id='')
	{	
		$formName='SearchEquip';
		?>
		<div class="form_main"> <form onsubmit="return false;" name="<?php echo $formName;?>" id="<?php echo $formName;?>">
		<table width="100%" class="table">
		<tr>
		<td width="25%" align="right">Equipment Name:</td>
		<td width="25%"><input name="searchname" type="text" id="searchname" size="60" onkeyup="<?php echo $obj; ?>.<?php echo $functn; ?>('',this.value,'<?php echo $event_id; ?>',this.form.searchType.value,this.form.available_on.value,this.form.available_till.value,
		{onUpdate: function(response,root){document.getElementById('<?php echo $target; ?>').innerHTML=response;
$('#search_equip')
.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[1,0]], headers: { 0:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
}}); " autocomplete='off' /></td>
		<td width="25%" align="right">Equipment Catagory:</td>
		<td width="25%">
		<select name="searchType" id="searchType" onchange="<?php echo $obj; ?>.<?php echo $functn; ?>('',this.form.searchname.value,'<?php echo $event_id; ?>',this.value,this.form.available_on.value,this.form.available_till.value,
		{onUpdate: function(response,root){document.getElementById('<?php echo $target; ?>').innerHTML=response;
$('#search_equip')
.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[1,0]], headers: { 0:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
}});" >
			<option value="">---select---</option>
			<?php
			$sql = "select * from ".EM_EQUIPMENT_CATEGORY;
			$result = $this->db->query($sql,__FILE__,__LINE__);
			while ($row = $this->db->fetch_array($result)){
			?>
				<option value="<?php echo $row['category_id'] ?>"><?php echo $row['category_name'] ?></option>
			<?php
			}
			?>
				</select>
	</td></tr>
	<tr>
				<td align="right">Available On</td>
				<td><input name="available_on" type="text" id="available_on" value="" size="60"  autocomplete='off' readonly="true"/>
			<script type="text/javascript">	
			 function start_cal(){	 
			 new Calendar({
			 inputField   	: "available_on",
			 dateFormat		: "%Y-%m-%d",
			 trigger		: "available_on",
			 weekNumbers   	: true,
			 bottomBar		: true,				 
			 onSelect		: function() {
									this.hide();
									document.<?php echo $formName;?>.available_on.value=this.selection.print("%Y-%m-%d");
									<?php echo $obj; ?>.<?php echo $functn; ?>('',document.<?php echo $formName;?>.searchname.value,'<?php echo $event_id; ?>',document.<?php echo $formName;?>.searchType.value,document.<?php echo $formName;?>.available_on.value,document.<?php echo $formName;?>.available_till.value,
		{onUpdate: function(response,root){document.getElementById('<?php echo $target; ?>').innerHTML=response;
$('#search_equip')
.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[1,0]], headers: { 0:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
}});		
									
end_cal(this.selection.get()+1);
     			}				
			  });
			  }
			 start_cal();
			</script>	
			<a href="javascript:void(0);" onclick="javascript: document.<?php echo $formName;?>.available_on.value='';
			<?php echo $obj; ?>.<?php echo $functn; ?>('',document.<?php echo $formName;?>.searchname.value,'<?php echo $event_id; ?>',document.<?php echo $formName;?>.searchType.value,document.<?php echo $formName;?>.available_on.value,document.<?php echo $formName;?>.available_till.value,
		{onUpdate: function(response,root){document.getElementById('<?php echo $target; ?>').innerHTML=response;
$('#search_equip')
.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[1,0]], headers: { 0:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
}});	 return false;"><img src="images/trash.gif" border="0"/></a>
			</td>
			<td align="right">Available Till</td>
			<td><input name="available_till" type="text" id="available_till" value="" size="60"  autocomplete='off' readonly="true"/>
			<script type="text/javascript">	
			function end_cal(minDate){		 
			 new Calendar({
			 inputField   	: "available_till",
			 dateFormat		: "%Y-%m-%d",
			 trigger		: "available_till",
			 weekNumbers   	: true,
			 bottomBar		: true,	
			 min			: minDate,			 
			 onSelect		: function() {
									this.hide();
									document.<?php echo $formName;?>.available_till.value=this.selection.print("%Y-%m-%d");		
									<?php echo $obj; ?>.<?php echo $functn; ?>('',document.<?php echo $formName;?>.searchname.value,'<?php echo $event_id; ?>',document.<?php echo $formName;?>.searchType.value,document.<?php echo $formName;?>.available_on.value,document.<?php echo $formName;?>.available_till.value,
		{onUpdate: function(response,root){document.getElementById('<?php echo $target; ?>').innerHTML=response;
$('#search_equip')
.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[1,0]], headers: { 0:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
}});	
									
								}				
			  });
			  }
			
			</script>	
			<a href="javascript:void(0);" onclick="javascript: document.<?php echo $formName;?>.available_till.value=''; 
			 <?php echo $obj; ?>.<?php echo $functn; ?>('',document.<?php echo $formName;?>.searchname.value,'<?php echo $event_id; ?>',document.<?php echo $formName;?>.searchType.value,document.<?php echo $formName;?>.available_on.value,document.<?php echo $formName;?>.available_till.value,
		{onUpdate: function(response,root){document.getElementById('<?php echo $target; ?>').innerHTML=response;
$('#search_equip')
.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[1,0]], headers: { 0:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
}});	 return false;"><img src="images/trash.gif" border="0"/></a>
			</td>
			</tr>
	</table>
	 <div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
	</form>
	  </div>
	<?

	} // end of HoetlSearchBox2


	function getEquipments2($location='',$equip_name='',$event_id='',$equip_type='',$available_on='',$available_till='')
	{
		ob_start();
		$sql = "select * from ".EM_EQUIPMENT.' where 1'; 
		if($equip_name){
		  $sql .= " and equipment_name like '$equip_name%'";
		}
		if($equip_type){
		  $sql .= " and equipment_category='$equip_type'";
		}
		$sql .= " order by equipment_name ";
		
		

		$result = $this->db->query($sql,__FILE__,__LINE__);
		$formName = 'frm_getEquipments';
		?>
		<form action=""  method="post" enctype="multipart/form-data" name="<?php echo $formName; ?>">
		<table id="search_equip" class="event_form small_text" width="100%">
		<thead>
			<tr>
				<th></th>
				<th>Equipment Category</th>
				<th>Equipment Name</th>
				<th>Equipment Description</th>
				<th>Equipment Status</th>
				<th>Note</th>
				<th></th>
				<th></th>
			</tr>
		 </thead>
		 <tbody>	
		
			<?php	
				$i=0;
				while($row=$this->db->fetch_array($result))
				{
					$sql_unavailabledate="select * from ".EM_EQUIPMENT_AVAILABILITY." where equipment_id='".$row[equipment_id]."'";
					$result_unavailabledate=$this->db->query($sql_unavailabledate,__FILE__,__LINE__);
					$unavailabledateArray = array();
					$x=0;
					while($row_unavailabledate=$this->db->fetch_array($result_unavailabledate))
					{
						$unavailabledateArray[$x++] = $row_unavailabledate['unavailability_date'];
					}
					
					$sql_availabledate="select * from ".EM_DATE." where event_id='".$event_id."'";
					$result_availabledate=$this->db->query($sql_availabledate,__FILE__,__LINE__);
					$availabledateArray = array();
					$x=0;
					while($row_availabledate=$this->db->fetch_array($result_availabledate))
					{
						$availabledateArray[$x++] = $row_availabledate['event_date'];
					}
					
					$flag = "available";
					if($available_on!='' and $available_till!=''){
					$dates = array();
					$s_date = explode('-',$available_on);
					$e_date = explode('-',$available_till);
					$date1 = mktime(0,0,0,$s_date[1],$s_date[2],$s_date[0]);
					$date2 = mktime(0,0,0,$e_date[1],$e_date[2],$e_date[0]);
					$dateDiff = $date2 - $date1;
					$fullDays = floor($dateDiff/(60*60*24));	
					for($j=0;$j<$fullDays;$j++){
						$dates[$j] = date('Y-m-d',mktime(0,0,0,$s_date[1],$s_date[2]+$j,$s_date[0]));
					}
					foreach($unavailabledateArray as $key1)
					{
						foreach ($dates as $key2)
						{	
							if ($key1 == $key2)
							{
								$flag = "unavailable";
								break;
							}
						}
					}	
					}
					if($flag == "available")
					{	
					  						
					?>	
						
						<tr >
							<td><input type="radio" name="rd_equip" id="rd_equip" value="<?php echo $row[equipment_id] ?>" /></td>
							<td><?php
							$sql_cat = "select * from ".EM_EQUIPMENT_CATEGORY." where category_id='".$row['equipment_category']."'";
							$result_cat = $this->db->query($sql_cat,__FILE__,__LINE__);
							$row_cat = $this->db->fetch_array($result_cat); 
							echo $row_cat['category_name'];?></td>
							<td><?php echo $row['equipment_name'];?></td>
							<td><?php echo $row['equipment_description'];?></td>
							<td><?php
							$sql_st = "select * from ".EM_EQUIPMENT_STATUS." where status_id='$row[equipment_status]'";
							$result_st = $this->db->query($sql_st,__FILE__,__LINE__);
							$row_st = $this->db->fetch_array($result_st); 
							 echo $row_st['status'];?></td>
							<td><?php echo $row['note'];?></td>
							<td><?php if($location!='popup'){ ?><a href="#" onclick="javascript: equipment.editEquipment('local','<?php echo $row['equipment_id'];?>',
											{ onUpdate: function(response,root){
													 document.getElementById('div_equip').innerHTML=response;
													 document.getElementById('div_equip').style.display='';						 					 
													 }, preloader: 'prl'
												} ); return false;" >edit</a><?php } ?>							</td>
						
						<td><?php if($location!='popup'){ ?>
						<a href="javascript:void(0)" onclick="javascript: if(confirm('Are you sure?')){ equipment.deleteEquipment('<?php echo $row['equipment_id'];?>', 
						
						{onUpdate: function(response,root){
						equipment.getEquipments({onUpdate: function(response,root){document.getElementById('showequip').innerHTML=response;
$('#search_equip')
.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[1,0]], headers: { 0:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
}});
						},preloader: 'prl'
						} );} return false;"><img src="images/trash.gif" /></a><?php } ?>						</td>
						</tr>
					<?php
					$i++;
					}
		
				}
				if($i==0)
					{
					?>
					<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>no result </td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					</tr>
					<?php
					}
				?>
		</tbody>		
		</table>
		<div class="verysmall_text form_bg">*Hold 'Shift Key' to sort multiple field</div>
		</form>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
		}
	
	
	function getEquipments($location='',$equip_name='',$event_id='',$equip_type='',$avalabledate='')
	{
		ob_start();
		$sql = "select * from ".EM_EQUIPMENT.' where 1'; 
		if($equip_name){
		  $sql .= " and equipment_name like '$equip_name%'";
		}
		if($equip_type){
		  $sql .= " and equipment_category='$equip_type'";
		}
		$sql .= " order by equipment_name ";
		
		

		$result = $this->db->query($sql,__FILE__,__LINE__);
		$formName = 'frm_getEquipments';
		?>
		<form action=""  method="post" enctype="multipart/form-data" name="<?php echo $formName; ?>">
		<table id="search_equip" class="event_form small_text" width="100%">
		<thead>
			<tr>
				<th></th>
				<th>Equipment Category</th>
				<th>Equipment Name</th>
				<th>Equipment Description</th>
				<th>Equipment Status</th>
				<th>Note</th>
				<th></th>
				<th></th>
			</tr>
		 </thead>
		 <tbody>	
		
			<?php	
				$i=0;
				while($row=$this->db->fetch_array($result))
				{
					$sql_unavailabledate="select * from ".EM_EQUIPMENT_AVAILABILITY." where equipment_id='".$row[equipment_id]."'";
					$result_unavailabledate=$this->db->query($sql_unavailabledate,__FILE__,__LINE__);
					$unavailabledateArray = array();
					$x=0;
					while($row_unavailabledate=$this->db->fetch_array($result_unavailabledate))
					{
						$unavailabledateArray[$x++] = $row_unavailabledate['unavailability_date'];
					}
					
					$sql_availabledate="select * from ".EM_DATE." where event_id='".$event_id."'";
					$result_availabledate=$this->db->query($sql_availabledate,__FILE__,__LINE__);
					$availabledateArray = array();
					$x=0;
					while($row_availabledate=$this->db->fetch_array($result_availabledate))
					{
						$availabledateArray[$x++] = $row_availabledate['event_date'];
					}
					
					$flag = "available";
					foreach($unavailabledateArray as $key1)
					{
						foreach ($availabledateArray as $key2)
						{	
							if ($key1 == $key2)
							{
								$flag = "unavailable";
								break;
							}
						}
					}	
					
					if($flag == "available")
					{	
					  						
					?>	
						
						<tr >
							<td><input type="radio" name="rd_equip" id="rd_equip" value="<?php echo $row[equipment_id] ?>" /></td>
							<td><?php
							$sql_cat = "select * from ".EM_EQUIPMENT_CATEGORY." where category_id='".$row['equipment_category']."'";
							$result_cat = $this->db->query($sql_cat,__FILE__,__LINE__);
							$row_cat = $this->db->fetch_array($result_cat); 
							echo $row_cat['category_name'];?></td>
							<td><?php echo $row['equipment_name'];?></td>
							<td><?php echo $row['equipment_description'];?></td>
							<td><?php
							$sql_st = "select * from ".EM_EQUIPMENT_STATUS." where status_id='$row[equipment_status]'";
							$result_st = $this->db->query($sql_st,__FILE__,__LINE__);
							$row_st = $this->db->fetch_array($result_st); 
							 echo $row_st['status'];?></td>
							<td><?php echo $row['note'];?></td>
							<td><?php if($location!='popup'){ ?><a href="#" onclick="javascript: equipment.editEquipment('local','<?php echo $row['equipment_id'];?>',
											{ onUpdate: function(response,root){
													 document.getElementById('div_equip').innerHTML=response;
													 document.getElementById('div_equip').style.display='';						 					 
													 }, preloader: 'prl'
												} ); return false;" >edit</a><?php } ?>							</td>
						
						<td><?php if($location!='popup'){ ?>
						<a href="javascript:void(0)" onclick="javascript: if(confirm('Are you sure?')){ equipment.deleteEquipment('<?php echo $row['equipment_id'];?>', 
						
						{onUpdate: function(response,root){
						equipment.getEquipments({onUpdate: function(response,root){document.getElementById('showequip').innerHTML=response;
$('#search_equip')
.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[1,0]], headers: { 0:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
}});
						},preloader: 'prl'
						} );} return false;"><img src="images/trash.gif" /></a><?php } ?>						</td>
						</tr>
					<?php
					$i++;
					}
		
				}
				if($i==0)
					{
					?>
					<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>no result </td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					</tr>
					<?php
					}
				?>
		</tbody>		
		</table>
		<div class="verysmall_text form_bg">*Hold 'Shift Key' to sort multiple field</div>
		</form>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
		}
		
	function deleteEquipment($equipment_id)	{
		ob_start();
		$this->equipment_id = $equipment_id;
		
		$sql = "select * from ".EM_EVENT_EQUIPMENT." where equipment_id='".$this->equipment_id."'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		$geID=array();
		while($row=$this->db->fetch_array($result))
		{
			$sql_gid = "select * from ".EM_EVENT." where event_id='".$row['event_id']."'";
			$result_gid = $this->db->query($sql_gid,__FILE__,__LINE__);
			$row_gid=$this->db->fetch_array($result_gid);
			$geID[]=$row_gid['group_event_id'];
		}
		
		
		$showtID= implode(', ',$geID);
		
		if($this->db->num_rows($result)>0){
		?>
		<script language="javascript" type="text/javascript">
		alert('<?php echo "Can not DELETE EQUIPMENT. EQUIPMENT Assigned to an event first remove EQUIPMENT from ".$showtID." Events"; ?>');
		</script>
		<?php 
		}
		else 
		{
		
		$sql_del = "delete from ".EM_EQUIPMENT." where equipment_id='".$this->equipment_id."'";
		$this->db->query($sql_del,__FILE__,__LINE__);
		
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function addEquipmentToEvent($event_id)
	{
		ob_start();
		$this->event_id=$event_id;
		?>
		<div class="prl">&nbsp;</div>
		<div id="lightbox">
		<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
		<div id="TB_ajaxWindowTitle">Add Equipment To Event</div>							
		<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_credential').style.display='none'; equipment.getAssignedEquip('<?php echo $this->event_id; ?>',{target:'event_equip',preloader:'prl'});"><img border="0" src="images/close.gif" alt="close" /></a></div>
		</div>
		<div  class="white_content"> 
		<div style="padding:20px;" >	
		
		<table width="100%" class="table">
		<tr>
		  <td><div class="profile_box1" align="left">Equipment</div></td>
		</tr>
		<tr>
		  <td><div id="div_assign_equip">	  
			  <?php echo $this->getAssignedEquip($this->event_id,'popup'); ?>
			  </div>
		  </td>
		</tr>
		<tr>
			<td>
			<div >
			  <div align="right"> 
			  <a href="#" onclick="table2CSV($('#search_equip')); return false;"> 
			  <img src="images/csv.png"  alt="Export to CSV" /> 
			  </a> 
			  </div>
			<?php $this->EquipmentSearchBox('equipment','getEquipments','div_equip_list',$this->event_id); ?>
			</div>
		<tr>
		  <td><a href="javascript:void(0);" onclick="javascript:if(get_radio_value('frm_getEquipments','rd_equip')){equipment.assignEquipment(get_radio_value('frm_getEquipments','rd_equip'),'<?php echo $this->event_id; ?>',{preloader:'prl',onUpdate: function(response,root){
		  equipment.getAssignedEquip('<?php echo $this->event_id; ?>','popup',{target:'div_assign_equip',preloader:'prl'});
		  equipment.getEquipments('popup','','<?php echo $this->event_id; ?>',{onUpdate: function(response,root){document.getElementById('div_equip_list').innerHTML=response;
$('#search_equip')
.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[1,0]], headers: { 0:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
}});
		  }}); } else { alert('Please select an equipment to add to event') } return false;">assign equipment</a></td>
		</tr>						
		<tr>
		  <td><div id="div_equip_list"><?php echo $this->getEquipments('popup','',$this->event_id); ?></div></td>
		</tr>
		</table>
		</div></div></div>
		<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function getAssignedEquip($event_id,$location='')
	{
		ob_start();
		$this->event_id = $event_id;
		$sql = "select * from ".EM_EVENT_EQUIPMENT." a,".EM_EQUIPMENT." b where a.event_id='$this->event_id' and a.equipment_id =b.equipment_id";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		?>
		<?php
		while($row = $this->db->fetch_array($result)){
		?>
		<table width="100%" class="table">
		  <tr>
		    <td width="50%"><?php echo $row[equipment_name]; ?></td>
			<td width="50%"><?php if($location=='popup'){ ?><a href="#" onclick="javascript:if(confirm('Are you sure?')){ equipment.unassignEquipment('<?php echo $row[equipment_id]; ?>','<?php echo $event_id; ?>',{preloader:'prl',onUpdate: function(response,root){
		  equipment.getAssignedEquip('<?php echo $event_id; ?>','popup',{target:'div_assign_equip',preloader:'prl'});
		  equipment.getEquipments('popup','','<?php echo $event_id; ?>',{onUpdate: function(response,root){document.getElementById('div_equip_list').innerHTML=response;
$('#search_equip')
.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[1,0]], headers: { 0:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})
}});
		  }}); } return false;">unassign</a><? } ?></td>
		  </tr>
		</table>
		 <?php
		 }
		 ?>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function dateArray($event_id,$max_day_start,$max_day_end){
		$dtm = array();
		$sql_start = "select * from ".EM_DATE." where event_id= ".$event_id." order by  event_date asc limit 1";
		$result_start = $this->db->query($sql_start,__FILE__,__lINE__);			
		$row_start = $this->db->fetch_array($result_start);		
		$start_date=$row_start['event_date'];
		$d = explode("-",$row_start['event_date']);			
		$j=0;
		for($i=$max_day_start; $i > 0; $i-=1){
			$dtm[$j] = date("Y-m-d",mktime(0, 0, 0, $d[1], ($d[2]-$i), $d[0]));
			$j+=1;			
		}			
		$sql_end = "select * from ".EM_DATE." where event_id= ".$event_id." order by  event_date desc limit 1";
		$result_end = $this->db->query($sql_end,__FILE__,__lINE__);			
		$row_end = $this->db->fetch_array($result_end);		
		$end_date=$row_end['event_date'];
		$d = explode("-",$row_start['event_date']);		
		for($i=1; $i <= $max_day_end; $i+=1){
			$dtm[$j] = date("Y-m-d",mktime(0, 0, 0, $d[1], ($d[2]+$i), $d[0]));
			$j+=1;			
		}
		
		return $dtm;
	}
	
	
	function reserveDaysForShipping($equipment_id,$event_id){
		
		$sql_max_day = "select * from ".EM_MAX_DAY_SHIPPING;
		$result_max_day = $this->db->query($sql_max_day,__FILE__,__lINE__);			
		$row_max_day = $this->db->fetch_array($result_max_day);	
		$max_day = $row_max_day['max_day'];	
			
		$dtm = $this->dateArray($event_id,$max_day,$max_day);
		
		$sql = "select * from ".EM_DATE." where event_id= '".$event_id."'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		
		
		$sql_equip = "select equipment_status from ".EM_EQUIPMENT." where equipment_id = '".$equipment_id."'";
		$result_equip = $this->db->query($sql_equip,__FILE__,__LINE__);
		$row_equip = $this->db->fetch_array($result_equip);	
		
		if($this->db->num_rows($result) > 0)
		{
			
			for($i=0; $i < $max_day*2 ; $i+=1) {				
				
				$insert_sql_array['unavailability_date'] = $dtm[$i];
				$insert_sql_array['start_time'] = $row['start_time'];
				$insert_sql_array['end_time'] = $row['end_time'];
				$insert_sql_array['note'] = 'Shipping';
				$insert_sql_array['equipment_id'] = $equipment_id;
				$insert_sql_array['event_id'] = $event_id;		
				$insert_sql_array['status'] = $row_equip['equipment_status'];
				$this->db->insert(EM_EQUIPMENT_AVAILABILITY,$insert_sql_array);				
			}			
		}
		
			
	}
	
	function assignEquipment($equipment_id,$event_id)
	{		
		if($equipment_id and $event_id){
				
			$insert_sql_array = array();
			$insert_sql_array['equipment_id'] = $equipment_id;
			$insert_sql_array['event_id'] = $event_id;
			$this->db->insert(EM_EVENT_EQUIPMENT,$insert_sql_array);
						
			$sql = "select * from ".EM_DATE." where event_id= '".$event_id."'";
			$result = $this->db->query($sql,__FILE__,__LINE__);
			
			$sql_event = "select group_event_id from ".EM_EVENT." where event_id= '".$event_id."'";
			$result_event = $this->db->query($sql_event,__FILE__,__LINE__);
			$row_event = $this->db->fetch_array($result_event);	
			
			$sql_equip = "select equipment_status from ".EM_EQUIPMENT." where equipment_id = '".$equipment_id."'";
			$result_equip = $this->db->query($sql_equip,__FILE__,__LINE__);
			$row_equip = $this->db->fetch_array($result_equip);
						
			while($row = $this->db->fetch_array($result)) {
				$insert_sql_array['unavailability_date'] = $row['event_date'];
				$insert_sql_array['start_time'] = $row['start_time'];
				$insert_sql_array['end_time'] = $row['end_time'];
				$insert_sql_array['note'] = 'Equipment Assigned';
				$insert_sql_array['equipment_id'] = $equipment_id;
				$insert_sql_array['event_id'] = $event_id;
				$insert_sql_array['status'] = $row_equip['equipment_status'];
				$this->db->insert(EM_EQUIPMENT_AVAILABILITY,$insert_sql_array);			
			}
			$this->reserveDaysForShipping($equipment_id,$event_id);				
		}
	}
	
	function unassignEquipment($equipment_id,$event_id)
	{
		$sql = "delete from ".EM_EVENT_EQUIPMENT." where event_id= '".$event_id."' and equipment_id='$equipment_id'";
		$this->db->query($sql,__FILE__,__LINE__);
		$sql = "delete from ".EM_EQUIPMENT_AVAILABILITY." where event_id= '".$event_id."' and equipment_id='$equipment_id'";
		$this->db->query($sql,__FILE__,__LINE__);
	}
	
	function addEquipmentIssue($runat,$obj='equipment',$equip_id,$status='',$event_id='',$note=''){
		ob_start();
		switch ($runat) {
			case 'local':
				$formName = 'frm_add_equipment_issue';
				$ControlNames=array("status"	=>array('status',"''","Please select status","span_equip_status"),
									"note_"		=>array('note_',"''","Please enter note","span_note")
									);
														
				$ValidationFunctionName="frm_addEquipment_CheckValidity";				
				$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($formName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
				echo $JsCodeForFormValidation;
				?>		
				<div class="prl">&nbsp;</div>
				<div id="lightbox">		
				<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
				<div id="TB_ajaxWindowTitle">Add Equipment Issue</div>							
				<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_equip').style.display='none';"><img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;" class="form_main">
				<ul id="error_list">
					<li><span id="span_equip_status"></span></li>					
					<li><span id="span_note"></span></li>		
				</ul>
				<form name="<?php echo $formName; ?>" method="post">
				<table class="table">
				
				<tr>
				<td>Equipment Status ::</td>
				<td>
				<?php
				$sql = "select * from ".EM_EQUIPMENT_STATUS;
				$result = $this->db->query($sql,__FILE__,__LINE__);
				?>
				<select name="status" id="status">
				<option value="">---Select---</option>
				<?php while($row = $this->db->fetch_array($result)){ ?>
				<option value="<?php echo $row['status_id']; ?>"><?php echo $row['status']; ?></option>
				<?php } ?>
				</select>								
				</td>
				</tr>
				
				<tr>
				<td>Event ::</td>
				<td>
				<?php
				$sql = "select distinct a.* from ".EM_EVENT." a, ".EM_EQUIPMENT_AVAILABILITY." b where a.event_id=b.event_id and b.equipment_id='".$equip_id."'";
				$result = $this->db->query($sql,__FILE__,__LINE__);
				?>
				<select name="event_id" id="event_id">
				<option value="">---Select---</option>
				<?php while($row = $this->db->fetch_array($result)){ ?>
				<option value="<?php echo $row['event_id']; ?>"><?php echo $row['group_event_id']; ?></option>
				<?php } ?>			
				</select>
				</td>
				</tr>
				
				<tr>
				<td>Note ::</td>
				<td><textarea name="note_" id="note_"></textarea></td>
				</tr>
				
				<tr>
				<td>
				
				</td>
				</tr>
				
				<tr>
				<td><input name="ok" id="ok" value="Submit" type="button" onclick="javascript: 
					 if(<?php echo $ValidationFunctionName?>()) { <?php echo $obj ?>.addEquipmentIssue('server','<?php echo $obj ?>','<?php echo $equip_id; ?>',this.form.status.value,this.form.event_id.value,this.form.note_.value,{target:'div_equip', preloader: 'prl'}); }"/></td>
				</tr>
				</table>
				</form>
				</div></div></div>
				<?php
				break;
			case 'server':
				
				$return =true;				
				if($this->Form->ValidField($status,'empty','Please select status')==false)
					$return =false;
				
				if($this->Form->ValidField($note,'empty','Please enter note')==false)
					$return =false;
					
				if($return){
					$insert_sql_array = array();
					$insert_sql_array['status'] = $status;
					$insert_sql_array['event_id'] = $event_id;
					$insert_sql_array['note'] = $note;
					$insert_sql_array['equipment_id'] = $equip_id;
					$this->db->insert(EM_EQUIPMENT_ISSUE,$insert_sql_array);
					}
				else {
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
					$this->addEquipmentIssue('local',$obj,$equip_id,$status,$event_id,$note);
				}
				break;
			}		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function eqiupmentInfo($equipment_id){
		ob_start();			
		
		$sql = "select * from ".EM_EQUIPMENT." where equipment_id = '".$equipment_id."'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		$row = $this->db->fetch_array($result);
		
		$sql1 = "select category_name from ".EM_EQUIPMENT_CATEGORY." where category_id = '".$row['equipment_category']."'";
		$result1 = $this->db->query($sql1,__FILE__,__LINE__);
		$row1 = $this->db->fetch_array($result1);
		
		$sql2 = "select status from ".EM_EQUIPMENT_STATUS." where status_id = '".$row['equipment_status']."'";
		$result2 = $this->db->query($sql2,__FILE__,__LINE__);
		$row2 = $this->db->fetch_array($result2);		
		?>
		<div class="prl">&nbsp;</div>
		<div id="lightbox">		
		<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
		<div id="TB_ajaxWindowTitle">Equipment Report</div>							
		<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_equip').style.display='none';"><img border="0" src="images/close.gif" alt="close" /></a></div>
		</div>
		<div  class="white_content"> 
		<div style="padding:20px;" class="form_main">
		<div id="div_equip_det" class="profile_box1">
		<table>		
		<tr>
		<td>Name</td><td>::</td><td><?php echo $row['equipment_name']; ?></td>
		</tr>
		
		<tr>
		<td>Category</td><td>::</td><td><?php echo $row1['category_name']; ?></td>
		</tr>
		
		<tr>
		<td>Description</td><td>::</td><td><?php echo $row['equipment_description']; ?></td>
		</tr>

		<tr>
		<td>Status</td><td>::</td><td><?php echo $row2['status']; ?></td>
		</tr>
		
		<tr>
		<td>Note</td><td>::</td><td><?php echo $row['note']; ?></td>
		</tr>		
		</table>
		</div>
		<div id="div_equip_rpt">		
		<?php echo $this->reportList($equipment_id); ?>
		</div>		
		</div></div></div>
		
		<?php	
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
		
	}
	
	
	function reportList($equipment_id){
		ob_start();
		$i=0;		
		$sql = "select * from ".EM_EQUIPMENT_ISSUE." where equipment_id ='".$equipment_id."'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		?>
		<table width="100%" id="search_issue" class="event_form small_text">
		<thead>		
		<tr>
		<th width="15%">Issue Id</th>
		<th width="20%">Equipment Status </th>
		<th width="25%">Event </th>
		<th width="40%">Note</th>		
		</tr>
		</thead>
		<tbody>
		
		<?php while($row = $this->db->fetch_array($result)){
			$i++;
			$sql_dts = "select status from ".EM_EQUIPMENT_STATUS." where status_id= '".$row['status']."'";
			$result_dts = $this->db->query($sql_dts,__FILE__,__LINE__);			
			$row_dts	= $this->db->fetch_array($result_dts);
			
			$sql_dte = "select group_event_id from ".EM_EVENT." where event_id= '".$row['event_id']."'";
			$result_dte = $this->db->query($sql_dte,__FILE__,__LINE__);			
			$row_dte	= $this->db->fetch_array($result_dte);					
			?>		
			<tr>			
			<td><?php echo $row['equipment_issue_id']; ?></td>
			<td><?php echo $row_dts['status']; ?></td>
			<td><a href="event_profile.php?event_id=<?php echo $row['event_id']; ?>" ><?php echo $row_dte['group_event_id']; ?></a></td>
			<td><p><?php echo $row['note']; ?></p></td>
			</tr>
			<?php }
			
			if($i==0){
				?>
				<tr id="hotellist_<?php echo $value['hotel_id'];?>" onClick="javascript:click(<?php echo $x;?>)" style="line-height:50px;" >
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>no result </td>
				<td>&nbsp;</td>
				
				</tr>
				<?php
				}

			
			?>		
		</tbody>
		</table>
		<div class="verysmall_text form_bg">*Hold 'Shift Key' to sort multiple field</div>
		
		<?php	
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
		
	}
	
	function addEquipmentStatus($runat)
	{
		switch ($runat){
			case 'local':
			
				$sql = "select * from ".EM_EQUIPMENT_STATUS;
				$result = $this->db->query($sql,__FILE__,__lINE__);
				$result2 = $this->db->query($sql,__FILE__,__lINE__);
					$formName = 'frm_addEquipmentStatus';
					
										
					$ControlNames=array("status_eqiup"			=>array('status_eqiup',"''","please enter equipment status ","span_equip")
									);
					
					$ValidationFunctionName="frm_addEquipmentStatus_CheckValidity";
					
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($formName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
				?>
				<script language="javascript" type="text/javascript">
					function frm_addEquipmentStatus_validateFeild_equip() {		
											
						if(document.getElementById("type_select_equip").value==document.getElementById("type_select_replace_equip").value) {
							alert("Plz.. select different equipment status.");
							return false;
						}
						else {					
						var location="manage_masterfields.php?id="+document.getElementById("type_select_equip").value + "&replcedstatuswith="+document.getElementById("type_select_replace_equip").value+ "&action=delete_equipment_status";						
							window.location=location;
						
						}				
					}
				
					</script>
				<ul id="error_list">
				<li><span id="span_equip" class="normal" ></span></li>
				</ul>
				<div>
				<form action="" method="POST" name="<?php echo $formName; ?>" id="<?php echo $formName; ?>" enctype="multipart/form-data">
				<table width="100%" class="table">
				  <tr>
				    <th width="21%">Equipment Status:</th>
				    <td width="32%"><input type="text" name="status_eqiup" id="status_eqiup"></td>
				    <td width="6%"><input type="submit" name="submit_equip" id="submit_equip" value="Go" style="width:auto"  
									onclick="return <?php echo $ValidationFunctionName ?>();" ></td>
					<td width="5%">&nbsp;</td>
					<td width="13%"><select name="type_select_equip" id="type_select_equip">
                          <?php
				      
				      while($row = $this->db->fetch_array($result)){
				      	?>
                          <option value="<?php echo $row['status_id'] ?>" ><?php echo $row['status'] ?></option>
                          <?php
				      }
				      ?>
                        </select></td>
						
				        <td width="13%">
				      <select name="type_select_replace_equip" id="type_select_replace_equip">
				      <?php
				      
				      while($row = $this->db->fetch_array($result2)){
				      	?>
				      	<option value="<?php echo $row['status_id'] ?>" ><?php echo $row['status'] ?></option>
				      	<?php
				      }
				      ?>
				      </select></td>
				      <td width="10%"><a href="#" onclick= "javascript: if(confirm('Are you sure?')) return frm_addEquipmentStatus_validateFeild_equip();">
					  				  <img src="images/trash.gif" border="0" /></a>
					  </td>
					
				  </tr>
				</table>
				</form>
				</div>
				<?php
				break;
			case 'server':
				
				$status=$_POST['status_eqiup'];				
				$return =true;				
				if($this->Form->ValidField($status,'empty','Please enter status')==false)
					$return =false;
					
				if($return){
					$insert_sql_array = array();
					$insert_sql_array['status'] = $status;
					$this->db->insert(EM_EQUIPMENT_STATUS,$insert_sql_array);
					$_SESSION[msg] = 'Equipment Status has been added';
					?>
					<script type="text/javascript">
					window.location = '<?php echo $_SERVER['PHP_SELF'] ?>';
					</script>
					<?php
				}
				else {
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
					$this->addStaffingStatus('local');
				}
				break;
			default:
				echo "Wrong parameter passed";
		}
	}
	
	
	function Delete_Equipment_Status($type_select_equip, $type_select_replace_equip){
	
			$sql = "delete from ".EM_EQUIPMENT_STATUS." where status_id = '".$type_select_equip."'";
			$this->db->query($sql,__FILE__,__LINE__);

			if($type_select_replace_equip!='')
			{
				$sql_equip = "update ".EM_EQUIPMENT." set equipment_status = '".$type_select_replace_equip."' where equipment_status = '".$type_select_equip."'";	
				$sql_issue = "update ".EM_EQUIPMENT_ISSUE." set status = '".$type_select_replace_equip."' where status = '".$type_select_equip."'";	
				$this->db->query($sql_equip,__FILE__,__LINE__);
				$this->db->query($sql_issue,__FILE__,__LINE__);
			}
			
			
		$_SESSION[msg]="Equipment Status Has Been Replaced Successfully!!";
		?>
		<script type="text/javascript">
		 window.location = '<?php echo $_SERVER[PHP_SELF] ?>';
		</script>
		<?php			
		
		
	}
}

?>