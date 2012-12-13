<?php

class Event_Staffing extends Company_Global
{
	var $staffing_id;
	var $event_id;
	var $contact_id;
	var $status;
	var $type;
	var $notes;
	var $db;
	var $zip_obj;
	var $Form;
	var $Validity;
	
	function __construct()
	{
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->zip_obj = new zipcode_class();
		$this->Form = new ValidateForm();
		$this->Validity = new ClsJSFormValidation();

	}
	
	function addStaff($event_id)
	{
		ob_start();
		?>
		<div class="prl">&nbsp;</div>
		<div id="lightbox">
				<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle">Staff</div>
					<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_credential').style.display='none'; staff.open_Position('local',<?php echo $event_id?>,'','','','','front',{onUpdate: function(response,root){
document.getElementById('eventstaff').innerHTML=response;
 }}); return false;"><img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;" >
				
				<div id="div_open_position" style="float:left; width:450px; padding-bottom:25px;">
				<?php echo $this->open_Position('local',$event_id); ?>
				</div>
				
				<div style="float:right">
				<form name="frm_manual_contact" id="frm_manual_contact">
				  <table class="table">
				    <tr><td colspan="3" class="textb">Manual Add:</td> </tr>
				    <tr> <td>User ID:</td><td><input type="text" name="manual_contact" id="manual_contact"> </td><td><input type="button" name="go" id="go" value="Go" onclick="javascript:
	if(get_radio_value('frmOpenPosition','rdStaff')){
		staff.validId(this.form.manual_contact.value,
					get_radio_value('frmOpenPosition','rdStaff'),
					{preloader:'prl',
					onUpdate: function(response,root){
						if(response){
							staff.assignStaff('<?php echo $event_id; ?>',
								get_radio_value('frmOpenPosition','rdStaff'),
								document.getElementById('manual_contact').value,
								{preloader:'prl',
								onUpdate:function(response,root){
									document.getElementById('manual_contact').value='';
									staff.searchStaff('<?php echo $event_id?>',
									{ onUpdate: function(response,root){
										document.getElementById('showstaff').innerHTML=response;
										$('#search_table').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], 
										headers: { 0:{sorter: false}, 16:{sorter: false}, 17:{sorter: false}}})},preloader:'prl'});
										staff.open_Position('local','<?php echo $event_id?>',{target:'div_open_position',preloader:'prl'});
										}});
							} 
						else {
							alert('Please enter a valid contact id');
							document.getElementById('manual_contact').value='';
							}
					}});
				} 
			else {
				alert('Please select a position'); 
				}
				 return false;" /></td> </tr>
				  </table>
				  </form>
				</div>
				<p>&nbsp;</p>
				
				
				<table width="100%" class="table">
				<tr><td align="right"><a href="javascript:void(0);" onclick="javascript: if(get_radio_value('frmOpenPosition','rdStaff')){				if(get_radio_value('frm_search_staff','rd_contact')){staff.assignStaff('<?php echo $event_id; ?>',get_radio_value('frmOpenPosition','rdStaff'),get_radio_value('frm_search_staff','rd_contact'),{preloader:'prl',onUpdate: function(response,root){ staff.searchStaff('<?php echo $event_id?>',{onUpdate: function(response,root){document.getElementById('showstaff').innerHTML=response;$('#search_table').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], headers: { 0:{sorter: false}, 16:{sorter: false}, 17:{sorter: false}}})},preloader:'prl'});	staff.open_Position('local','<?php echo $event_id?>',{target:'div_open_position',preloader:'prl'});	}});
}else { alert('Please select a staff'); }}else { alert('Please select a position'); } return false;">assign staff</a>
				</td></tr>
				<tr>
				<td>
				<div align="right"> 
				<a href="#" onclick="table2CSV($('#search_table')); return false;"> 
				<img src="images/csv.png"  alt="Export to CSV" /> 
				</a> 
				</div>				
				<?php $this->staffSearchBox($event_id); ?>
				</td>
				</tr>
				
				  <tr>
				    <td><div id="showstaff"><?php echo $this->searchStaff($event_id); ?></div></td>
				  </tr>
				</table>
				<div  align="right"><a href="advance_staff_search.php?event_id=<?php echo $event_id; ?>">more...</a> </div>
					<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
			</div>			
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function addStaffFromUnstaff($event_staff)
	{
		ob_start();
		?>
		<div class="prl">&nbsp;</div>
		<div id="lightbox">
				<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle">Staff</div>
					<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_credential').style.display='none'; staff.getUnstaffedEvents('all','','','','','','','','','','','Unstaffed',{onUpdate: function(response,root){
					document.getElementById('div_unstaff').innerHTML=response;
					$('#search_unstaffedEvent')
					.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
					headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );">
					<img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;" >
				<?php
				$evt_stt = explode('#',$event_staff);
				$event_id = $evt_stt[0];
				$staffing_id = $evt_stt[1];
				
				?>
				<form name="frmOpenPosition" id="frmOpenPosition">
				<input type="hidden" name="rdStaff" id="rdStaff1" value="<?php echo $staffing_id; ?>" />
				<table width="100%" class="table">
				<tr><td align="right"><a href="javascript:void(0);" onclick="javascript: if(get_radio_value('frm_search_staff','rd_contact')){ staff.assignStaff('<?php echo $event_id; ?>','<?php echo $staffing_id; ?>',get_radio_value('frm_search_staff','rd_contact'),{preloader:'prl',onUpdate: function(response,root){ 
						staff.searchStaff('<?php echo $event_id?>',{target:'showstaff',preloader:'prl',onUpdate: function(response,root){
						document.getElementById('div_credential').style.display='none'; 
						staff.getUnstaffedEvents('all','','','','','','','','','','','Unstaffed',{onUpdate: function(response,root){
					document.getElementById('div_unstaff').innerHTML=response;
					$('#search_unstaffedEvent')
					.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]],
					headers: { 10:{sorter: false}, 8:{sorter: false}, 7:{sorter: false}}}) }} );
						}});
						 }}); }
else { alert('Please select a staff'); }  return false;">assign staff</a>
				</td></tr>
				<tr>
				<td><?php $this->staffSearchBox($event_id,$staffing_id); ?></td>
				</tr>
				
				  <tr>
				    <td><div id="showstaff"><?php echo $this->searchStaff($event_id,'','',$staffing_id); ?></div></td>
				  </tr>
				</table>
				</form>
				<div  align="right"><a href="advance_staff_search.php?event_id=<?php echo $event_id; ?>">more...</a> </div>
				
					<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
			</div>			
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function open_Position($runat,$event_id,$type='',$status='',$contact='',$notes='',$location='popup')
	{
	  ob_start();
	  
	  switch ($runat){
	  	case 'local':
			$sql_staff = "select * from ".EM_STAFFING." where event_id='$event_id'";
			$result_staff = $this->db->query($sql_staff,__FILE__,__LINE__);
			?>
			<form action="" enctype="multipart/form-data"  name="frmOpenPosition" id="frmOpenPosition" method="POST">
			<table id="open_position" width="100%" class="event_form small_text">
			<thead>
			  <tr>
			   <th>&nbsp;</th>
			   <th>Type</th>
			   <th>Status</th>
			   <th>Staff</th>
			   <th>Note</th>
			   <th>Action</th>
			   <?php if($location!='front') echo '<th>Phone</th>'; ?>
			  </tr>
			</thead>
			<tbody>  
			
			  <?php
			  $i=1; 
			  if($this->db->num_rows($result_staff)>0){
			  	while ($row_staff = $this->db->fetch_array($result_staff)){
			  	 if($location!='front'){
				?>
			  	<tr <?php if($i%2==0) { echo 'class="alt2"'; } ?> >
			  	    <td><input type="radio" name="rdStaff" id="rdStaff<?php echo $row_staff[staffing_id]; ?>" value="<?php echo $row_staff[staffing_id]; ?>" onclick="staff.searchStaff(<?php echo $event_id ?>,document.frm_search_box.search1.value,document.frm_search_box.search2.value,this.value, {onUpdate: function(response,root){
					document.getElementById('showstaff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], headers: { 0:{sorter: false}, 16:{sorter: false}, 17:{sorter: false}}})
			 	},preloader:'prl'});"></td>
			  	    <td><?php $sql_cert = "select * from ".EM_CERTIFICATION_TYPE." where certification_id='".$row_staff[type]."'"; $result_cert = $this->db->query($sql_cert,__FILE__,__LINE__); $row_cert = $this->db->fetch_array($result_cert); echo $row_cert[cert_type]; ?></td>
			  	    <td><div id="statusdiv" ><select name="status" id="status" onchange="javascript: staff.updateStaffing('<?php echo $row_staff[staffing_id]; ?>',this.value,{ preloader: 'prl'});  return false;">
					<option value="empty">-Select-</option>
				<?PHP
				$sql_status = "select * from ".EM_STAFFING_STATUS;
			    $result_status = $this->db->query($sql_status,__FILE__,__LINE__);
				while($row_status = $this->db->fetch_array($result_status))
				{
					?>
					<option value="<?php echo $row_status['status_id'] ?>" <?php if($row_staff[status]==$row_status['status_id']) echo "selected"; ?>>
					<?php echo $row_status['status'] ?></option>
					<?php 
				} 	
				?>
			    </select></div><?php //echo $row_staff[status]; ?></td>
			  	    <td><?php 
					$sql_cont = "select first_name,last_name from ".TBL_CONTACT." where contact_id='".$row_staff[contact_id]."'"; 
					$result_cont = $this->db->query($sql_cont,__FILE__,__LINE__); 
					$row_cont = $this->db->fetch_array($result_cont); 
					?>
					<a href="contact_profile.php?contact_id=<?php echo $row_staff[contact_id]; ?>"><?php echo $row_cont[first_name].' '.$row_cont[last_name]; ?></a>
					</td>
					
			  	    <td><input type="text" name="notes" id="notes" value="<?php echo $row_staff[notes]; ?>" onchange="javascript:staff.updateStaffing('<?php echo $row_staff[staffing_id]; ?>','nll',this.value,{preloader:'prl'}); return false;" /></td>
			  	    <td><a href="javascript:void(0);" onclick="javascript:if(confirm('Are you sure?')){ staff.deletePosition('<?php echo $row_staff[staffing_id]?>','<?php echo $event_id?>',{target:'div_open_position',preloader:'prl',onUpdate: function(response,root){ 
						staff.searchStaff('<?php echo $event_id?>',{onUpdate: function(response,root){
					document.getElementById('showstaff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], headers: { 0:{sorter: false}, 16:{sorter: false}, 17:{sorter: false}}})
			 	},preloader:'prl'}); }}); } return false;"><img src="images/trash.gif" /></a><?php if($row_staff[contact_id]){ ?> <a href="javascript:void(0);" onclick="javascript:if(confirm('Are you sure?')){ staff.deletePosition('<?php echo $row_staff[staffing_id]?>','<?php echo $event_id?>','unstaff',{target:'div_open_position',preloader:'prl',onUpdate: function(response,root){ 
						staff.searchStaff('<?php echo $event_id?>',{onUpdate: function(response,root){
					document.getElementById('showstaff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[5,0]], headers: { 0:{sorter: false}, 16:{sorter: false}, 17:{sorter: false}}})
			 	},preloader:'prl'}); }}); } return false;">unstaff</a><?php } ?></td>
				
				<td><?php 
					$sql = "select a.number,b.ic from ".CONTACT_PHONE." a, ".EM_CONTACT_STATUS." b where a.contact_id='$row_staff[contact_id]' and b.contact_id='$row_staff[contact_id]'";
					$result = $this->db->query($sql,__FILE__,__LINE__);
					$row = $this->db->fetch_array($result);
					if($row[ic]=='Yes') echo 'IC '.substr($row[number], 0, 3).'-'.substr($row[number], 3, 3).'-'.substr($row[number], 6, 4);
					else echo substr($row[number], 0, 3).'-'.substr($row[number], 3, 3).'-'.substr($row[number], 6, 4);
				?></td>
			  	</tr>
			  	<?php $i++;
				}
				if($location=='front'){
				?>
			  	<tr>
			  	    <td></td>
			  	    <td><?php $sql_cert = "select * from ".EM_CERTIFICATION_TYPE." where certification_id='".$row_staff[type]."'"; $result_cert = $this->db->query($sql_cert,__FILE__,__LINE__); $row_cert = $this->db->fetch_array($result_cert); echo $row_cert[cert_type]; ?></td>
			  	    <td><?php
					$sql_status = "select * from ".EM_STAFFING_STATUS." where status_id='$row_staff[status]'";
			    	$result_status = $this->db->query($sql_status,__FILE__,__LINE__);
					$row_status = $this->db->fetch_array($result_status);
					 echo $row_status[status]; ?></td>
			  	    <td><?php $sql_cont = "select * from ".TBL_CONTACT." where contact_id='".$row_staff[contact_id]."'";
					$result_cont = $this->db->query($sql_cont,__FILE__,__LINE__);
					$row_cont = $this->db->fetch_array($result_cont); ?>
					<a href="contact_profile.php?contact_id=<?php echo $row_staff[contact_id]; ?>">
					<?php echo $row_cont[first_name].' '.$row_cont[last_name]; ?></a></td>
			  	    <td><?php echo $row_staff[notes]; ?></td>
			  	    <td><?php echo $this->getSuggestedStaff($event_id,$row_staff[type],$row_staff[staffing_id],$row_staff[contact_id]);  ?></td>
			  	</tr>
				
				<?php
				
				}
			  	}
			  }
			  ?>
			</tbody>  
			</table>
			</form>
			<?php if($location!='front'){ ?>
			<div id="add_position" style="display:none; clear:both">
			  <form action="" enctype="multipart/form-data" method="POST">		  
			  <table width="100%" class="table"><tr>  
			  <td><select name="type" id="type">
			  <option value="">-Select-</option>
			    <?php
			    $sql_cert = "select * from ".EM_CERTIFICATION_TYPE;
			    $result_cert = $this->db->query($sql_cert,__FILE__,__LINE__);
			    while ($row_cert = $this->db->fetch_array($result_cert)) {
			    ?>
			    <option value="<?php echo $row_cert[certification_id] ?>"><?php echo $row_cert[cert_type] ?></option>
			    <?php
			    }
			    ?>
			    </select></td>
			    <td><select name="status" id="status" >
					<option value="empty">-Select-</option>
				<?PHP
				$sql_status = "select * from ".EM_STAFFING_STATUS;
			    $result_status = $this->db->query($sql_status,__FILE__,__LINE__);
				while($row_status = $this->db->fetch_array($result_status))
				{
					?>
					<option value="<?php echo $row_status['status_id'] ?>" <?php if($row_staff[status]==$row_status['status_id']) echo "selected"; ?>><?php echo $row_status['status'] ?></option>
					<?php 
				} 	
				?>
			    </select>&nbsp;</td>
			    <td  colspan="2"><input type="text" name="notes" id="notes" /></td>
			    <td><input type="button" name="go" id="go" value="Go" style="width:auto" onclick="javascript: staff.open_Position('server','<?php echo $event_id; ?>',this.form.type.value,this.form.status.value,'',this.form.notes.value,{target:'div_open_position', preloader: 'prl'}); return false;" /></td>
				<td> or <a href="javascript:void(0);" onclick="javascript:document.getElementById('add_position').style.display='none'; document.getElementById('addbutton').style.display=''; return false;">cancel</a></td>
			    </tr></table>
			    <div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
			  </form>
			 </div>		
			 <div style="padding:15px; float:right; " id="addbutton" ><a href="javascript:void(0)" onclick="javascript:document.getElementById('add_position').style.display=''; document.getElementById('addbutton').style.display='none'; return false;">add another</a></div>
			<?php }
			break;
	  	case 'server':
	  		
	  		$this->event_id = $event_id;
	  		$this->type = $type;
	  		$this->status = $status;
	  		$this->contact_id = $contact;
	  		$this->notes = $notes;
	  		
	  		$insert_sql_array = array();
	  		$insert_sql_array[event_id] = $this->event_id;
	  		$insert_sql_array[type] = $this->type;
	  		$insert_sql_array[status] = $this->status;
	  		$insert_sql_array[contact_id] = $this->contact_id;
	  		$insert_sql_array[notes] = $this->notes;
	  		$this->db->insert(EM_STAFFING,$insert_sql_array);
	  		echo $this->open_Position('local',$this->event_id);
	  		break;
	  }
	  $html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function staffSearchBox($event_id,$staffing_id='')
	{
		?>
		<div>
		<form name="frm_search_box" onsubmit="return false;">
		<table class="table" width="100%" >
		<tr>
			<td>FirstName:</td>
			<td><input name="search1" type="text" id="search1" value="" size="60" onkeyup="staff.searchStaff('<?php echo $event_id ?>',this.value,document.getElementById('search2').value,<?php if($staffing_id==''){ ?>get_radio_value('frmOpenPosition','rdStaff') <?php } else { echo $staffing_id; } ?> , {onUpdate: function(response,root){
					document.getElementById('showstaff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'],  headers: { 0:{sorter: false}, 16:{sorter: false}, 17:{sorter: false}}})
			 	}});" autocomplete='off' /></td>
			<td>LastName:</td>
			<td><input name="search2" type="text" id="search2" value="" size="60" onkeyup="staff.searchStaff('<?php echo $event_id ?>',document.getElementById('search1').value,this.value, <?php if($staffing_id==''){ ?>get_radio_value('frmOpenPosition','rdStaff') <?php } else { echo $staffing_id; } ?>,{onUpdate: function(response,root){
					document.getElementById('showstaff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'],  headers: { 0:{sorter: false}, 16:{sorter: false}, 17:{sorter: false}}})
			 	}});" autocomplete='off' /></td>
		</tr>
		</table>
		<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
		</form>
		</div>
		<?php
	}
	
	function getAssignedJobs($contact_id)
	{
		ob_start();
		$sql = "select * from ".EM_STAFFING." where contact_id=' ".$contact_id."'";
		$result = $this->db->query($sql,__FILE__,__LINE__);		
			
		?>
		<table>
		  <tr>
		    <td colspan="9"><h2>Assigned Jobs</h2></td>
		  </tr>
		<?php
		while($row = $this->db->fetch_array($result)){
		
		$sql_eve = "select * from ".EM_EVENT." where event_id='$row[event_id]'";
		$result_eve = $this->db->query($sql_eve,__FILE__,__LINE__);
		$row_eve = $this->db->fetch_array($result_eve);
		
		$sql_cert = "select * from ".EM_CERTIFICATION_TYPE." where certification_id='$row[type]'";
		$result_cert = $this->db->query($sql_cert,__FILE__,__LINE__);
		$row_cert = $this->db->fetch_array($result_cert);
		?>
		  <tr>
		    <td>GEID <?php echo $row_eve[group_event_id]; ?></td>
			<td>::</td>
			<td><?php echo $row_cert[cert_type]; ?></td>
			<td>::</td>
			<td><?php $evt_obj->start_date($row_eve[event_id])?> - <?php $eve_obj->end_date($row_eve[event_id]); ?></td>
			<td>::</td>
			<td><?php echo $row_eve[city].', '.$row_eve[state]; ?></td>
			<td>::</td>
			<td><a href="#">enter QA Report</a></td>
		  </tr>		
		<?php
		}
		
		?></table><?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function advanceStaffSearchBox($event_id)
	{
		?>
		<div>
		<div align="right"> 
		<a href="#" onclick="table2CSV($('#search_table')); return false;"> 
		<img src="images/csv.png"  alt="Export to CSV" /> 
		</a> 
		</div>
		<form name="frm_search_box" onsubmit="return false;">
		<h2>Search Options</h2>
		<table class="table" width="100%" >
		<tr>
		    <td>License/Cert</td>
			<td>FirstName</td>
			<td>LastName:</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
		    <td><select name="credential_type" id="credential_type" style="width:100%" onchange="staff.searchStaff(<?php echo $event_id ?>,this.form.f_n.value,this.form.l_n.value,'',this.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,this.form.company.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'],  headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	}});"> 
		    <option value="">-Select-</option>
			<?php 
			$sql_cert2="select * from ".EM_CERTIFICATION_TYPE;
			$result_cert2 = $this->db->query($sql_cert2,__FILE__,__LINE__);
			while($row_cert2 = $this->db->fetch_array($result_cert2))
			{
			?>
		    <option value="<?php echo $row_cert2['certification_id']; ?>"><?php echo $row_cert2['cert_type']; ?></option>
			<?php } ?>
		    </select>		    </td>
			<td><input name="f_n" type="text" id="f_n" value="" size="60" onkeyup="staff.searchStaff(<?php echo $event_id ?>,this.value,this.form.l_n.value,'',this.form.credential_type.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,this.form.company.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	}});" autocomplete='off' /></td>
			<td><input name="l_n" type="text" id="l_n" value="" size="60" onkeyup="staff.searchStaff(<?php echo $event_id ?>,this.form.f_n.value,this.value,'',this.form.credential_type.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,this.form.company.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	}});" autocomplete='off' /></td>
			<td></td>
		</tr>
		<tr>
		    <td>Event Count</td>
		    <td>User Status</td>
		    <td>System Status</td>
		    <td>Recruiting Status</td>
		</tr>
		<tr>
		    <td><input name="e_c" type="text" id="e_c" value="" size="60" onkeyup="staff.searchStaff(<?php echo $event_id ?>,this.form.f_n.value,this.form.l_n.value,'',this.form.credential_type.value,this.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,this.form.company.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	}});" autocomplete='off' /></td>
		    <td><select name="u_s" id="u_s" style="width:100%" onchange="staff.searchStaff(<?php echo $event_id ?>,this.form.f_n.value,this.form.l_n.value,'',this.form.credential_type.value,this.form.e_c.value,this.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,this.form.company.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	},preloader:'prl'});">
		    <option value="">-Select-</option>
			<option value="Active">Active</option>
		    <option value="Inactive">Inactive</option>
			<option value="Not_defined">No Status Set</option>
		    </select> </td>
		    <td><select name="s_s" id="s_s" style="width:100%" onchange="staff.searchStaff(<?php echo $event_id ?>,this.form.f_n.value,this.form.l_n.value,'',this.form.credential_type.value,this.form.e_c.value,this.form.u_s.value,this.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,this.form.company.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	},preloader:'prl'});">
		    <option value="">-Select-</option>
			<option value="User">User</option>
		    <option value="Team Lead">Team Lead</option>
			<option value="Inactive">Inactive</option>
		    </select> </td>
		    <td><select name="r_s" id="r_s" style="width:100%" onchange="staff.searchStaff(<?php echo $event_id ?>,this.form.f_n.value,this.form.l_n.value,'',this.form.credential_type.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,this.form.company.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'],headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	}});">
		    <option value="">-Select-</option>
			<?php 
			$sql_rct="select * from ".EM_RECRUITING_STATUS;
			$result_rct = $this->db->query($sql_rct,__FILE__,__LINE__);
			while($row_rct = $this->db->fetch_array($result_rct))
			{
			?>
		    <option value="<?php echo $row_rct['recruiting_status_id']; ?>" ><?php echo $row_rct['recruiting_status']; ?></option>
			<?php } ?>
		    </select> </td>
		</tr>
		<tr>
		    <td>City</td>
		    <td>State</td>
		    <td>Zip</td>
		    <td>Radius</td>
		</tr>
		<tr>
		    <td><input name="ct" type="text" id="ct" value="" size="60" onkeyup="staff.searchStaff(<?php echo $event_id ?>,this.form.f_n.value,this.form.l_n.value,'',this.form.credential_type.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,this.form.company.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	}});" autocomplete='off' /></td>
		    <td><select name="st" id="st" style="width:100%" onchange="staff.searchStaff(<?php echo $event_id ?>,this.form.f_n.value,this.form.l_n.value,'',this.form.credential_type.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.value,this.form.zip.value,this.form.rad.value,this.form.company.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	}});" >
				<option value="">Select State</option>
				<?php
					$state=file("../state_us.inc");
					foreach($state as $val){
					$state = trim($val);
				?>
				<option <?php if($_POST['state']==$state){echo 'selected="selected"';}?> value="<?php echo $state;?>"><?php echo $state;?></option>
				<?php
					}
				?>
				</select>
				</td>
		    <td><input name="zip" type="text" id="zip" value="" size="60" onkeyup="staff.searchStaff(<?php echo $event_id ?>,this.form.f_n.value,this.form.l_n.value,'',this.form.credential_type.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.value,this.form.rad.value,this.form.company.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	}});" autocomplete='off' /></td>
		    <td><input name="rad" type="text" id="rad" value="" size="60" onkeyup="staff.searchStaff(<?php echo $event_id ?>,this.form.f_n.value,this.form.l_n.value,'',this.form.credential_type.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.value,this.form.company.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	}});" autocomplete='off' /></td>
		</tr>
		
		<tr>
			<td>Company:</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
		    <td><input name="company" type="text" id="company" value="" size="60" onkeyup="staff.searchStaff(<?php echo $event_id ?>,this.form.f_n.value,this.form.l_n.value,'',this.form.credential_type.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,this.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	}});" autocomplete='off' /></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		</table>
		
		</form>
		</div>
		<?php
	}
	
	function searchStaff($event_id,$first_name='',$last_name='',$position='',$cert_type='',$event_count='',$user_status='',$system_status='',$rec_status='',$city='',$state='',$zip='',$radius=0,$company='')
	{
		
		ob_start();
		
		
		
		$sql_event="select distinct state,zip from ".EM_EVENT." where event_id='".$event_id."'";
		$result_event=$this->db->query($sql_event,__FILE__,__LINE__);
		$row_event=$this->db->fetch_array($result_event);
		
		$sql_date="select event_date from ".EM_DATE." where event_id='".$event_id."' order by event_date asc limit 1";
		$result_date=$this->db->query($sql_date,__FILE__,__LINE__);
		$row_date=$this->db->fetch_array($result_date);
		
		$sql_cre = "Select distinct a.*,b.* from ".EM_STAFFING." a,".EM_CERTIFICATION_TYPE." b where a.staffing_id = '".$position."' and a.type=b.certification_id";
		$result_cre = $this->db->query($sql_cre,__FILE__,__LINE__);
		$row_cre=$this->db->fetch_array($result_cre);
				
		
		$sql = "select distinct a.*,c.*,d.*,b.* from ".TBL_CONTACT." a,".EM_CERTIFICATION." b,".EM_CONTACT_STATUS." c,".CONTACT_ADDRESS." d where a.contact_id=c.contact_id and a.contact_id=b.contact_id and a.contact_id=d.contact_id and a.first_name like '$first_name%' and a.last_name like '$last_name%' and c.user_status='Active' and b.expiration_date>'".$row_date[event_date]."' and a.type='People' ";
		
		if($user_status){
			if($user_status!='Not_defined')
			$sql = "select distinct a.*,c.*,d.*,b.* from ".TBL_CONTACT." a,".EM_CERTIFICATION." b,".EM_CONTACT_STATUS." c,".CONTACT_ADDRESS." d where a.contact_id=c.contact_id and a.contact_id=b.contact_id and a.contact_id=d.contact_id and a.first_name like '$first_name%' and a.last_name like '$last_name%' and c.user_status='".$user_status."' and b.expiration_date>'".$row_date[event_date]."' and a.type='People' ";
			else
				$sql = "select distinct a.*,d.*,b.* from ".TBL_CONTACT." a,".EM_CERTIFICATION." b,".CONTACT_ADDRESS." d where a.contact_id=b.contact_id and a.contact_id=d.contact_id and a.first_name like '$first_name%' and a.last_name like '$last_name%' and b.expiration_date>'".$row_date[event_date]."' and a.type='People' and a.contact_id not in ( select distinct contact_id from ".EM_CONTACT_STATUS.")";
		}
		
		if($row_cre[credential_type]=='License'){
			$sql .= " and b.state='".$row_event[state]."' and b.certification_type_id='$row_cre[certification_id]'";
		}

		if($row_cre[credential_type]=='Certification'){
			$sql .= " and b.certification_type_id='$row_cre[certification_id]'";
		}
		
		if($system_status){
			$sql .= " and c.system_status = '$system_status'";
		}
		
		if($rec_status!=''){
			$sql .= " and c.recruitment_status = '".$rec_status."'";
		}
		
		if ($city) {
			$sql .=" and d.city like '$city%'";
		}
		
		if ($state) {
			$sql .=" and d.state like '$state%'";
		}
		
		if ($zip) {
			$sql .=" and d.zip like '$zip%'";
		}
		$sql .=" order by a.contact_id";
		if ($company) {
			 $sql_com = "select contact_id from ".TBL_CONTACT." where company_name like '%$company%' and type= 'Company'";
			 $result_com=$this->db->query($sql_com,__FILE__,__LINE__);
			 $company_list='-1,';
			 while($row_com = $this->db->fetch_array($result_com)) {
				$company_list .= $row_com[contact_id].',';
			 }
			$company_list = substr($company_list,0,strlen($company_list)-1);
			$sql .=" and a.company in (".$company_list.")";
		}
		if($cert_type){
		$sql_cre2 = "Select distinct * from ".EM_CERTIFICATION_TYPE."  where  certification_id='$cert_type'";
		$result_cre2 = $this->db->query($sql_cre2,__FILE__,__LINE__);
		$row_cre2=$this->db->fetch_array($result_cre2);
			if($row_cre2[credential_type]=='License'){
				$sql .= " and d.state='".$row_event[state]."' and b.certification_type_id='$row_cre2[certification_id]'";
			}
			if($row_cre2[credential_type]=='Certification'){
				$sql .= " and b.certification_type_id='$row_cre2[certification_id]'";
			}
		}
		
		//echo $sql;
		$result = $this->db->query($sql,__FILE__,__LINE__);
		/************************************************************************/
		$contact = array();
		$x=0;
		while($row=$this->db->fetch_array($result)){
			
			foreach($row as $key=>$value){
				$contact[$x][$key] = $value;
			}
			$x++;
		}
		
		/*************************************************************************/
		$x=0;
		$contact_list = array();
		$row = $this->zip_obj->get_zip_point($row_event[zip]);
		if($row[lat]){
			$sql = "SELECT Distinct e.*,c.lat,c.lon,c.zip_code , (((acos( sin( ( ".$row[lat]." * pi( ) /180 ) ) * sin( (c.lat * pi( )/180 ) ) + cos( ( ".$row[lat]." * pi( ) /180 ) ) * cos( (c.lat * pi( ) /180 )) * cos( ((".$row[lon]." - c.lon) * pi( ) /180 )))) *180 / pi( )) *60 * 1.1515) AS distance FROM ".ZIP_CODE." c,".CONTACT_ADDRESS." e WHERE e.zip = c.zip_code";
			if($radius>0){
				$sql .= " HAVING distance<=$radius";
			} 
			
			$sql .= " ORDER BY distance ASC";
			
			$result = $this->db->query($sql,__FILE__,__LINE__);	
			
			while($row_c = $this->db->fetch_array($result)){
			
				foreach($row_c as $key=>$value){
					$contact_list[$x][$key] = $value;
				}
				$x++;
			}
			
		}
		$contactInRange = array();
		$x=0;
		foreach($contact as $key=>$value){				
			foreach($contact_list as $key_zip=>$value_zip){
				if($value_zip[zip]==$value[zip]){
					$contactInRange[$x] = $value;
					$contactInRange[$x]['distance'] = $value_zip['distance'];
					$sql_staff = "select Distinct count(*) as event_count from ".EM_STAFFING." where contact_id='$value[contact_id]' ";
					$result_staff = $this->db->query($sql_staff,__FILE__,__LINE__);	
					$row_staff = $this->db->fetch_array($result_staff);
					$contactInRange[$x]['event_count'] = $row_staff['event_count'];
					$x++;
					break;
				}
			}
		}
		
		$tmp1 = array();
		$tmp2 = array();
		foreach($contactInRange as &$ma)
			$tmp1[] = &$ma["event_count"];
		foreach($contactInRange as &$ma)
			$tmp2[] = &$ma["distance"];
		array_multisort($tmp1,SORT_DESC,$tmp2, $contactInRange);
		if($event_count!=''){
			$contact_temp = array();
			$x=0;
			foreach($contactInRange as $key=>$value){				
				if($value[event_count]>=$event_count){
					$contact_temp[$x] = $value;
					$x++;
				}
			}
			while(count($contactInRange)) array_shift($contactInRange);
			$contactInRange = $contact_temp;
		}
				
		?>
		<form action=""  method="post" enctype="multipart/form-data" name="frm_search_staff">	
		<table id="search_table" class="event_form small_text" width="100%">
		<thead>
		<tr>
			<th>&nbsp;</th>
			<th>ID</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Phone Number</th>
			<th>Dist(miles)</th>
			<th>Event Count</th>
			<th>User Status</th>
			<th>Recruiting Status</th>
			<th>IC</th>
			<th>Email</th>
			<th>Date of Application</th>
			<th>Position Applied For</th>

		</tr>
		</thead>
		<tbody>
		<?php	
		$i=0;
		$contacts='';
		$contact_processed_array = array();
		foreach($contactInRange as $key=>$value)
		{		
				if(!(in_array($value['contact_id'],$contact_processed_array))){

				$sql_cert = "select * from ".EM_CERTIFICATION." where contact_id='".$value['contact_id']."' and status='active'";
				$result_cert = $this->db->query($sql_cert,__FILE__,__LINE__);

				$sql_unavailabledate="select * from ".EM_CONTACT_UNAVAILABILITY." where contact_id='".$value[contact_id]."'";
				$result_unavailabledate=$this->db->query($sql_unavailabledate,__FILE__,__LINE__);
				$unavailabledateArray = array();
				$x=0;
				while($row_unavailabledate=$this->db->fetch_array($result_unavailabledate))
				{
					$unavailabledateArray[$x++] = $row_unavailabledate['unavailable_date'];
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
				if($this->db->num_rows($result_cert)){	
				$i++;
				$contacts= $contacts . $value[contact_id].',';
			?>	
				<tr id="stafflist_<?php echo $value['contact_id'];?>">
					 <td><?php if($user_status!='Inactive'){ ?><input type="radio" name="rd_contact" id="rd_contact" value="<?php echo $value['contact_id'];?>" /><?php } ?></td>
					 <td><a href="contact_profile.php?contact_id=<?php echo $value['contact_id']; ?>"><?php echo $value['contact_id'];?></a></td>
					 <td><a href="contact_profile.php?contact_id=<?php echo $value['contact_id']; ?>"><?php echo $value['first_name'];?></a></td>
					 <td><a href="contact_profile.php?contact_id=<?php echo $value['contact_id']; ?>"><?php echo $value['last_name'];?></a></td>
					 <td><?php
					 $sql_refine = "select number from ".CONTACT_PHONE." where contact_id='".$value[contact_id]."' and type like '%Work%'";
					 $result_refine=$this->db->query($sql_refine,__FILE__,__LINE__);
					 $row_refine = $this->db->fetch_array($result_refine);					 
					 echo $row_refine['number'];?>
					 <td><?php echo number_format($value['distance'],1,'.','');?></td>
					 <td><?php echo $value['event_count'];?></td>
					 <td><?php echo $value['user_status'];?></td>
					 <td><?php
					    $sql_rct="select * from ".EM_RECRUITING_STATUS." where recruiting_status_id='".$value['recruitment_status']."'";
						$result_rct = $this->db->query($sql_rct,__FILE__,__LINE__);
			            $row_rct = $this->db->fetch_array($result_rct);
					  echo $row_rct['recruiting_status'];
					  
					  ?></td>

 					  <td><?php if($value['ic']=='Yes') echo 'Y'; else echo 'N'?></td>
					  
					  <td><?php
					  $sql_rct="select email from ".CONTACT_EMAIL." a, ".EM_STAFFING." b where a.contact_id='".$value['contact_id']."' and a.contact_id = b.contact_id";
					  $result_rct = $this->db->query($sql_rct,__FILE__,__LINE__);
					  $row_rct = $this->db->fetch_array($result_rct);
					  if($row_rct['email']) echo '<a href="mailto: '.$row_rct['email'].'">'.$row_rct['email'].'</a>';					  
					  ?></td>
					  
					  <td><?php
					  echo $value['timestamp'];					  
					  ?></td>
					  
					  <td><?php
					  $sql_rct="select position_applied from ".EM_APPLICATION_GENERAL." where contact_id='".$value['contact_id']."'";
					  $result_rct = $this->db->query($sql_rct,__FILE__,__LINE__);
					  $row_rct = $this->db->fetch_array($result_rct);
					  echo $row_rct['position_applied'];					  
					  ?></td>
 
					 
				</tr>
			<?php 
			$contact_processed_array[] = $value['contact_id'];
			}

			}
			}
		}
		if($i==0){ ?>
			<tr><td colspan="13" align="center">no result </td></tr>
		<?php }
		else { 
		$contacts = substr($contacts,0,strlen($contacts)-1);
		?>
		<tr>
		<td colspan="13" align="right">
			<a href="javascript:void(0);" onclick="javascript: staff.emailToAll('local','<?php echo $contacts;?>','staff',
													{ preloader: 'prl',
													onUpdate: function(response,root){
													 document.getElementById('div_event').innerHTML=response;
													 document.getElementById('div_event').style.display='';
													 }});">Email To Contacts</a>
			</td></tr>
		<?php }	?>
		</tbody>
		</table>
		<div class="smalltext form_bg" align="center">*Here distance given is the distance relative to the event you came from.</div>
		</form>		
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
//	function getContactSearch($zip){
//		$x=0;
//		$contacts = array();
//		$row = $this->zip_obj->get_zip_point($zip);
//		if($row[lat]){
//			$sql = "SELECT e.*,c.lat,c.lon,c.zip_code , (((acos( sin( ( ".$row[lat]." * pi( ) /180 ) ) * sin( (c.lat * pi( )/180 ) ) + cos( ( ".$row[lat]." * pi( ) /180 ) ) * cos( (c.lat * pi( ) /180 )) * cos( ((".$row[lon]." - c.lon) * pi( ) /180 )))) *180 / pi( )) *60 * 1.1515) AS distance FROM ".ZIP_CODE." c,".CONTACT_ADDRESS." e WHERE e.zip = c.zip_code ORDER BY distance ASC";
//			
//			$result = $this->db->query($sql,__FILE__,__LINE__);	
//			
//			while($row_c = $this->db->fetch_array($result)){
//			
//				foreach($row_c as $key=>$value){
//					$contacts[$x][$key] = $value;
//				}
//				$x++;
//			}
//			
//		}
//		
//		return $contacts;
//	}
//
//	function getContactInRange($contact,$contact_list){
//		$contactInRange = array();
//		$x=0;
//		foreach($contact_list as $key_zip=>$value_zip){
//			foreach($contact as $key=>$value){				
//				if($value_zip[zip]==$value[zip]){
//					$contactInRange[$x] = $value;
//					$contactInRange[$x]['distance'] = $value_zip['distance'];
//					$x++;
//					break;
//				}
//			}
//		}
//		return $contactInRange;
//	}
	
	function getContactRange()
	{
		$sql = "SELECT * FROM ".EM_SUGGESTION_MASTER." where suggestion_type = 'STAFF'";
		$result = $this->db->query($sql,__FILE__,__LINE__);	
		$row = $this->db->fetch_array($result);
		return $row[distance];
	}	
	
	function deletePosition($staffing_id,$event_id,$action='staff',$return='true')
	{
		ob_start();

		$sql_staff = "select * from ".EM_STAFFING." where staffing_id='".$staffing_id."'";
		$result_staff = $this->db->query($sql_staff,__FILE__,__LINE__);	
		$row = $this->db->fetch_array($result_staff);
		$contact_id=$row['contact_id'];
		if($action=='staff'){
			$sql = "delete from ".EM_STAFFING." where staffing_id='".$staffing_id."'";
			$this->db->query($sql,__FILE__,__LINE__);
		}
		if($action=='unstaff'){
			$update_sql_array = array();
			$update_sql_array[contact_id] = '';
			//$update_sql_array[status] = '';
			$this->db->update(EM_STAFFING,$update_sql_array,'staffing_id',$staffing_id);
		}
		if($contact_id!=''){
		$sql_un = "delete from ".EM_CONTACT_UNAVAILABILITY." where contact_id='".$contact_id."' and event_id='".$event_id."'";
		$this->db->query($sql_un,__FILE__,__LINE__);
		}
		
		if($return=='true')
			echo $this->open_Position('local',$event_id);

		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function assignStaff($event_id='',$staffing_id='',$contact_id='')
	{
		ob_start();
		$sql_staff = "select * from ".EM_STAFFING." where staffing_id='".$staffing_id."'";
		$result_staff = $this->db->query($sql_staff,__FILE__,__LINE__);	
		$row = $this->db->fetch_array($result_staff);
		$event_id=$row['event_id'];
		
		if($row['contact_id']!=''){	
		$this->deletePosition($staffing_id,$event_id,'unstaff','false');
		}
		
		$con = new Event_Contacts();
		$update_sql_array = array();
		$update_sql_array['contact_id'] = $contact_id;
		//$update_sql_array['status'] = 'Staffed';
		$this->db->update(EM_STAFFING,$update_sql_array,'staffing_id',$staffing_id);
		$sql = "select * from ".EM_DATE." where event_id= '".$event_id."'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		while($row = $this->db->fetch_array($result)) {
			$con->Availability($contact_id,$event_id,$row['event_date'],$row['start_time'],$row['end_time'],'Event Assigned');
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	
	function popup_addToEvent($contact_id,$event_id='')
	{
		ob_start();
		$cert = new Certification_Type();
		?>
		

		<div class="prl">&nbsp;</div>
		<div id="lightbox">
			
				<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
					<div id="TB_ajaxWindowTitle"></div>
					<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_eve_staff').style.display='none'; "><img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;" >
				<table width="100%" class="table">
				  <tr><td><div align="left"> <?php $this->getContactStatusHeader($contact_id); ?> </div>
				  	<div align="right"> <a href="javascript:void(0);" onclick="javascript: if(get_radio_value('frm_search_event', 'rd_event')){ staff.assignStaff('',get_radio_value('frm_search_event', 'rd_event'),'<?php echo $contact_id ; ?>',{preloader:'prl',onUpdate: function(response,root){ 
					document.getElementById('div_eve_staff').style.display='none';
					<?php if($event_id) echo 'staff.searchStaff('.$event_id.','; 
					else echo 'staff.searchAllStaff('; ?>{ onUpdate: function(response,root){
											document.getElementById('div_search_staff').innerHTML=response;
											document.getElementById('div_search_staff').style.display='';
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'],  headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})						
											}
												} );	
					}});} else alert('Select an event'); return false;">Assign Event</a></div>			 
					
					 		  
				  	</td> </tr>
				  <tr><td><div class="profile_box1" ><?php
							echo $this->Get_Credentials($contact_id); ?>
							</div></td> </tr>
				<tr><td><?php /*?><div class="profile_box1" ><?php
							echo $this->getAssignedJobs($contact_id); ?>
							</div><?php */?></td> </tr>
				<tr><td><div id="content_column_header" ><?php
							echo $this->eventSearchBox($contact_id); ?>
							</div></td> </tr>
				<tr><td><div id="div_search_event"><div class="small_text " align="center">*select one credential to show open positions available</div><?php //echo $this->searchEvent($contact_id); ?>
							</div></td> </tr>
				</table>
				</div>
			</div>
		</div>
		<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function Get_Credentials($contact_id)
	{
		ob_start();
		$this->contact_id = $contact_id;
		$sql = "select * from ".EM_CERTIFICATION." where ".CONTACT_ID." = '".$this->contact_id."'";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		$formName = 'frm_cre';
		?><h2 align="left">License or Certification</h2>
		<form name="<?php echo $formName; ?>" id="<?php echo $formName; ?>">
		<table class="table"><?php
		while($row = $this->db->fetch_array($record)){
			$this->showCredential($row,$contact_id);
		}
		?>
		</table>
		</form>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	function showCredential($row,$contact_id)
	{
		  $sql_type = "select * from ".EM_CERTIFICATION_TYPE." where ".CERTIFICATION_ID." = '".$row[CERTIFICATION_TYPE_ID]."'";
		  $rec_type = $this->db->query($sql_type,__FILE__,__LINE__);
		  $row_type = $this->db->fetch_array($rec_type);
		  		      
		  ?>		
		  <tr>
		    <td><input type="radio" name="rd_cre" id="rd_cre" value="<?php echo $row_type[certification_id]; ?>" onclick="staff.searchEvent('<?php echo $contact_id; ?>',document.forms['frm_eventSearchBox'].elements['e_start'].value,document.forms['frm_eventSearchBox'].elements['e_e'].value,document.forms['frm_eventSearchBox'].elements['gi_id'].value,document.forms['frm_eventSearchBox'].elements['e_s'].value,document.forms['frm_eventSearchBox'].elements['s_s'].value,'',document.forms['frm_eventSearchBox'].elements['ct'].value,<?php 
			if(trim($row[state])!='') { echo "'".trim($row[state])."'";    }
			 else { echo "document.forms['frm_eventSearchBox'].elements['st'].value";  } ?>
			,document.forms['frm_eventSearchBox'].elements['zip'].value,document.forms['frm_eventSearchBox'].elements['rad'].value,this.value, 
			{ onUpdate: function(response,root){ 
			 document.getElementById('div_search_event').innerHTML=response;
			 <?php 
			if(trim($row[state])!='') { ?>
			document.forms['frm_eventSearchBox'].elements['st'].className='noshow'; 
			document.forms['frm_eventSearchBox'].elements['st'].value = '<?php echo $row[state]; ?>';
			<?php   } else { ?>	
			document.forms['frm_eventSearchBox'].elements['st'].className='show'; 
			<?php
			}
			?>
			$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]],
											headers: { 0:{sorter: false}, 6:{sorter: false}, 8:{sorter: false}}});
			}});" /></td>
			<td><?php echo $row_type[CERT_TYPE]; ?></td>
			<td>:: <?php echo $row_type[CREDENTIAL_TYPE] ?><?php if($row_type[CREDENTIAL_TYPE]=='License'){ ?> (<?php echo $row[state]; ?>)<?php } ?></td>
		    <td>:: Effective Date <?php echo $row[START_DATE] ?></td>
		    <td>:: Exp <?php echo $row[EXPIRATION_DATE] ?></td>					
		  </tr>		
		  
		<?php
	}
	
	
	function getContactStatusHeader($contact_id)
	{
		$sql = "select * from ".TBL_CONTACT." a,".EM_CONTACT_STATUS." b where a.contact_id=b.contact_id and a.contact_id='".$contact_id."'";
		$result = $this->db->query($sql,__FILE__,__LINE__);
		$row = $this->db->fetch_array($result);
		
		$sql1 = "select * from ".EM_RECRUITING_STATUS." where recruiting_status_id='".$row[recruitment_status]."'";
		$result1 = $this->db->query($sql1,__FILE__,__LINE__);
		$row1 = $this->db->fetch_array($result1);	
		?>
		<table>
		  <tr>
		    <td><h2>Staff:</h2></td>
		    <td><?php echo $row[first_name].' '.$row[last_name]; ?></td>
		    <th>User Status:</th>
		    <td><?php echo $row[user_status] ?></td>
		    <th>System Status:</th>
		    <td><?php echo $row[system_status] ?></td>
		    <th>Recruiting Status:</th>
		    <td><?php echo $row1[recruiting_status] ?></td>
		  </tr>
		</table>
		<?php
	}
	
	function getSuggestedStaff($event_id,$type,$staffing_id,$contact_id='')
	{
		if($contact_id){
			$sql = "select a.number,b.ic from ".CONTACT_PHONE." a, ".EM_CONTACT_STATUS." b where a.contact_id='$contact_id' and b.contact_id='$contact_id'";
			$result = $this->db->query($sql,__FILE__,__LINE__);
			$row = $this->db->fetch_array($result);
			if($row[ic]=='Yes') return 'IC '.substr($row[number], 0, 3).'-'.substr($row[number], 3, 3).'-'.substr($row[number], 6, 4);
			else return substr($row[number], 0, 3).'-'.substr($row[number], 3, 3).'-'.substr($row[number], 6, 4);
		}
		else {
		$sql_eve = "select * from ".EM_EVENT." where event_id='$event_id'";
		$result_eve = $this->db->query($sql_eve,__FILE__,__LINE__);
		$row_eve = $this->db->fetch_array($result_eve);
		
		$x=0;
		$contact_list = array();
		$row = $this->zip_obj->get_zip_point($row_eve[zip]);
		if($row[lat]){
			$sql = "SELECT e.*,c.lat,c.lon,c.zip_code , (((acos( sin( ( ".$row[lat]." * pi( ) /180 ) ) * sin( (c.lat * pi( )/180 ) ) + cos( ( ".$row[lat]." * pi( ) /180 ) ) * cos( (c.lat * pi( ) /180 )) * cos( ((".$row[lon]." - c.lon) * pi( ) /180 )))) *180 / pi( )) *60 * 1.1515) AS distance FROM ".ZIP_CODE." c,".CONTACT_ADDRESS." e WHERE e.zip = c.zip_code ORDER BY distance ASC";

			$result = $this->db->query($sql,__FILE__,__LINE__);	
			while($row_c = $this->db->fetch_array($result)){
			
				foreach($row_c as $key=>$value){
					$contact_list[$x][$key] = $value;
				}
				$x++;
			}
			$sql_contact = "select * from ".TBL_CONTACT." a, ".EM_CERTIFICATION." b,".EM_CONTACT_STATUS." c where a.contact_id=b.contact_id and b.certification_type_id='$type' and b.expiration_date>'".date('Y-m-d',time())."' and c.contact_id=a.contact_id and 	user_status='Active' ";
			$cre_type = $this->getCertificationType($type);
			if($cre_type=='License'){
				$sql_contact .= " and state='$row_eve[state]'";
			}
			$result_contact = $this->db->query($sql_contact,__FILE__,__LINE__);
			$x=0;
			$contacts = array();
			while($row_con = $this->db->fetch_array($result_contact)){
				foreach($row_con as $key=>$value){
					$contacts[$x][$key] = $value;
				}
				$x++;
			}

			$found = false;
			foreach($contact_list as $key_zip=>$value_zip){
			if(!$found){
				foreach($contacts as $key=>$value){				
					if($value_zip[contact_id]==$value[contact_id]){
						$sql_unavailabledate="select * from ".EM_CONTACT_UNAVAILABILITY." where contact_id='".$value[contact_id]."'";
						$result_unavailabledate=$this->db->query($sql_unavailabledate,__FILE__,__LINE__);
						$unavailabledateArray = array();
						$x=0;
						while($row_unavailabledate=$this->db->fetch_array($result_unavailabledate))
						{
							$unavailabledateArray[$x++] = $row_unavailabledate['unavailable_date'];
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
						$sql="select * from ".EM_STAFFING." where event_id='".$event_id."' and contact_id='".$value[contact_id]."'";
						$result=$this->db->query($sql,__FILE__,__LINE__);
						
						if($this->db->num_rows($result)<=0){
						?>
						<table>
						  <tr>
						    <td><a href="javascript:void(0);" onclick="javascript:staff.assignStaff('<?php  echo $event_id;?>' ,'<?php echo $staffing_id;?>','<?php echo $value[contact_id];?>',{onUpdate: function(response,root){ 
							staff.open_Position('local','<?php echo $event_id;?>','','','','','front',{target:'eventstaff',preloader:'prl'});
							},preloader:'prl'})">add</a></td>
							<td><a href="contact_profile.php?contact_id=<?php echo $value[contact_id] ?>"><?php echo $value[first_name].' '.$value[last_name]; ?></a></td>
							<td>-dist<?php echo " ".number_format($value_zip[distance],1,'.','')." miles"; ?></td>
						  </tr>
						</table>
						<?php
						$found = true;
						}
						break;
						}
					}
				}
			}
			}
		}
		}
	}
	
	function getCertificationType($type)
	{
		$sql = "select * from ".EM_CERTIFICATION_TYPE." where certification_id='".$type."'";
		$result=$this->db->query($sql,__FILE__,__LINE__);
		$row = $this->db->fetch_array($result);
		return $row[credential_type];
	}
	
	
	function eventSearchBox($contact_id='',$unstaffed='')
	{
		ob_start();
		$formName = 'frm_eventSearchBox';
		$this->unstaffed=$unstaffed;
		?>
		<div>
		<form method="post" name="<?php echo $formName; ?>" id="<?php echo $formName; ?>"  onsubmit="return false;">
		<h2>Search Options</h2>
		<?php 
		if($contact_id) {
			?>
			<table class="table" width="100%" >
			<tr>
				<td>Event From</td>
				<td>Event To </td>
				<td>GI ID</td>
			</tr>
			<tr>
				<td><input name="e_start" type="text" id="e_start" value="" size="60"  autocomplete='off' readonly="true"/>				
				 <script type="text/javascript">
				 function start_cal()  {
				 new Calendar({
				 inputField   	: "e_start",
				 dateFormat		: "%Y-%m-%d",
				 trigger		: "e_start",
				 weekNumbers   	: true,
				 bottomBar		: true,				 
				 onSelect		: function() {
										this.hide();
										document.<?php echo $formName;?>.e_start.value=this.selection.print("%Y-%m-%d");
										end_cal(this.selection.get()+1);
										staff.searchEvent('<?php echo $this->contact_id; ?>',
										document.<?php echo $formName;?>.e_start.value,
										document.<?php echo $formName;?>.e_e.value,
										document.<?php echo $formName;?>.gi_id.value,
										document.<?php echo $formName;?>.e_s.value,
										document.<?php echo $formName;?>.s_s.value,'',
										document.<?php echo $formName;?>.ct.value,
										document.<?php echo $formName;?>.st.value,
										document.<?php echo $formName;?>.zip.value,
										document.<?php echo $formName;?>.rad.value,
										get_radio_value('frm_cre', 'rd_cre'),
										{onUpdate: function(response,root){
											document.getElementById('div_search_event').innerHTML=response;
											$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]],
											headers: { 0:{sorter: false}, 6:{sorter: false}, 8:{sorter: false}}});
										}});
																
									}
				 	});
				  }	
				</script>
				<a href="javascript:void(0);" onclick="javascript: document.<?php echo $formName;?>.e_start.value=''; staff.searchEvent('<?php echo $this->contact_id; ?>',document.<?php echo $formName;?>.e_start.value,document.<?php echo $formName;?>.e_e.value,document.<?php echo $formName;?>.gi_id.value,document.<?php echo $formName;?>.e_s.value,document.<?php echo $formName;?>.s_s.value,'',document.<?php echo $formName;?>.ct.value,document.<?php echo $formName;?>.st.value,document.<?php echo $formName;?>.zip.value,document.<?php echo $formName;?>.rad.value,get_radio_value('frm_cre', 'rd_cre'), {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 8:{sorter: false}}})}}); 
								"><img src="images/trash.gif" border="0"/></a>			
				</td>		
				
				<td><input name="e_e" type="text" id="e_e" value="" size="60" autocomplete='off' readonly="true"/>
				<script type="text/javascript">	
							 
				 function end_cal(minDate){
				 new Calendar({
				 inputField   	: "e_e",
				 dateFormat		: "%Y-%m-%d",
				 trigger		: "e_e",
				 weekNumbers   	: true,
				 bottomBar		: true,
				 min			: minDate,				 
				 onSelect		: function() {
										this.hide();
										document.<?php echo $formName;?>.e_e.value=this.selection.print("%Y-%m-%d");									
										staff.searchEvent('<?php echo $this->contact_id; ?>',
										document.<?php echo $formName;?>.e_start.value,
										document.<?php echo $formName;?>.e_e.value,
										document.<?php echo $formName;?>.gi_id.value,
										document.<?php echo $formName;?>.e_s.value,
										document.<?php echo $formName;?>.s_s.value,'',
										document.<?php echo $formName;?>.ct.value,
										document.<?php echo $formName;?>.st.value,
										document.<?php echo $formName;?>.zip.value,
										document.<?php echo $formName;?>.rad.value,
										get_radio_value('frm_cre', 'rd_cre'),
										{onUpdate: function(response,root){
											document.getElementById('div_search_event').innerHTML=response;
											$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]],
											headers: { 0:{sorter: false}, 6:{sorter: false}, 8:{sorter: false}}});
									}}); 
									}				
				  });
				}
				</script>
				<a href="javascript:void(0);" onclick="javascript: document.<?php echo $formName;?>.e_e.value=''; staff.searchEvent('<?php echo $this->contact_id; ?>',document.<?php echo $formName;?>.e_start.value,document.<?php echo $formName;?>.e_e.value,document.<?php echo $formName;?>.gi_id.value,document.<?php echo $formName;?>.e_s.value,document.<?php echo $formName;?>.s_s.value,'',document.<?php echo $formName;?>.ct.value,document.<?php echo $formName;?>.st.value,document.<?php echo $formName;?>.zip.value,document.<?php echo $formName;?>.rad.value,get_radio_value('frm_cre', 'rd_cre'), {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 8:{sorter: false}}})}}); 
								"><img src="images/trash.gif" border="0"/></a>	
				
				</td>
				
				<td><input name="gi_id" type="text" id="gi_id" value="" size="60" onkeyup="staff.searchEvent('<?php echo $this->contact_id; ?>',this.form.e_start.value,this.form.e_e.value,this.value,this.form.e_s.value,this.form.s_s.value,'',this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,get_radio_value('frm_cre', 'rd_cre'), {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 8:{sorter: false}}})} });" autocomplete='off' /></td>
			</tr>
			<tr>
				<td>Event Status</td>
				<td>Staff Status</td>
				<td>City</td>
			</tr>
			<tr>
				<td>
				<select name="e_s" id="e_s" style="width:100%" onchange="staff.searchEvent('<?php echo $this->contact_id; ?>',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.value,this.form.s_s.value,'',this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,get_radio_value('frm_cre', 'rd_cre'), {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 8:{sorter: false}}})} });">
							<option value="">-Select-</option>
							  <?php
						  $sql = "select * from ".EM_EVENT_STATUS;
						  $result = $this->db->query($sql,__FILE__,__lINE__);
						  while($row = $this->db->fetch_array($result)){
							?>
							  <option value="<?php echo $row[event_status_id] ?>" ><?php echo $row[event_status] ?></option>
							  <?php
						  }
						  ?>
				  </select>
				</td>
				<td><select name="s_s" id="s_s" style="width:100%" onchange="staff.searchEvent('<?php echo $this->contact_id; ?>',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,this.value,'',this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,get_radio_value('frm_cre', 'rd_cre'), {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 8:{sorter: false}}})} });">
							<option value="">-Select-</option>
							  <?php
						  $sql = "select * from ".EM_STAFFING_STATUS;
						  $result = $this->db->query($sql,__FILE__,__lINE__);
						  while($row = $this->db->fetch_array($result)){
							?>
							  <option value="<?php echo $row[status_id] ?>" ><?php echo $row[status] ?></option>
							  <?php
						  }
						  ?>
							</select> </td>
				<td><input name="ct" type="text" id="ct" value="" size="60" onkeyup="staff.searchEvent('<?php echo $this->contact_id; ?>',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,this.form.s_s.value,'',this.value,this.form.st.value,this.form.zip.value,this.form.rad.value,get_radio_value('frm_cre', 'rd_cre'), {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 8:{sorter: false}}})} });" autocomplete='off' /></td>
				
			</tr>
			<tr>
				<td>Zip</td>
				<td>Radius</td>
				<td>State</td>
			</tr>
			<tr>
				<td><input name="zip" type="text" id="zip" value="" size="60" onkeyup="staff.searchEvent('<?php echo $this->contact_id; ?>',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,this.form.s_s.value,'',this.form.ct.value,this.form.st.value,this.value,this.form.rad.value,get_radio_value('frm_cre', 'rd_cre'), {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 8:{sorter: false}}})} });" autocomplete='off' /></td>
				<td><input name="rad" type="text" id="rad" value="" size="60" onkeyup="staff.searchEvent('<?php echo $this->contact_id; ?>',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,this.form.s_s.value,'',this.form.ct.value,this.form.st.value,this.form.zip.value,this.value,get_radio_value('frm_cre', 'rd_cre'), {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 8:{sorter: false}}})} });" autocomplete='off' /></td>
				<td><select name="st" id="st" style="width:100%" onchange="staff.searchEvent('<?php echo $this->contact_id; ?>',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,this.form.s_s.value,'',this.form.ct.value,this.value,this.form.zip.value,this.form.rad.value,get_radio_value('frm_cre', 'rd_cre'), {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 8:{sorter: false}}})} });">
						<option value="">Select State</option>
						<?php
							$state=file("../state_us.inc");
							foreach($state as $val){
							$state = trim($val);
						?>
						<option  value="<?php echo $state;?>"><?php echo $state;?></option>
						<?php
							}
						?>
					 </select>
							 </td>
			</tr>
			</table>
			
		<?php } 
		
		
		
		else {
		?>
			<table class="table" width="100%" >
				<tr>
					<td>Event From</td>
					<td>Event To</td>
					<td>GI ID</td>
					<td>Event Status</td>
				</tr>
				
				<tr>
				
				<td><input name="e_start" type="text" id="e_start" value="" size="60" autocomplete='off' readonly="true" />
				
				<script type="text/javascript">
				 function start_cal(){				
				 new Calendar({
				 inputField   	: "e_start",
				 dateFormat		: "%Y-%m-%d",
				 trigger		: "e_start",
				 weekNumbers   	: true,
				 bottomBar		: true,				 
				 onSelect		: function() {
										
									this.hide();
									document.<?php echo $formName;?>.e_start.value=this.selection.print("%Y-%m-%d");
									staff.searchEvent('<?php echo $this->contact_id; ?>',
									document.<?php echo $formName;?>.e_start.value,
									document.<?php echo $formName;?>.e_e.value,
									document.<?php echo $formName;?>.gi_id.value,
									document.<?php echo $formName;?>.e_s.value,'','',
									document.<?php echo $formName;?>.ct.value,
									document.<?php echo $formName;?>.st.value,
									document.<?php echo $formName;?>.zip.value, 
									document.<?php echo $formName;?>.rad.value,'','',
									document.<?php echo $formName;?>.branch.value,
									document.<?php echo $formName;?>.s_code.value,
									{onUpdate: function(response,root){
									document.getElementById('div_search_event').innerHTML=response;
									$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]],
									headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})}});
									end_cal(this.selection.get()+1);
									}				
				  });
				  }
				  start_cal();				  
				</script>
				<a href="javascript:void(0);" onclick="javascript: document.<?php echo $formName;?>.e_start.value=''; staff.searchEvent('<?php echo $this->contact_id; ?>',document.<?php echo $formName;?>.e_start.value,document.<?php echo $formName;?>.e_e.value,document.<?php echo $formName;?>.gi_id.value,document.<?php echo $formName;?>.e_s.value,'','',document.<?php echo $formName;?>.ct.value,document.<?php echo $formName;?>.st.value,document.<?php echo $formName;?>.zip.value,document.<?php echo $formName;?>.rad.value,'','',document.<?php echo $formName;?>.branch.value,document.<?php echo $formName;?>.s_code.value, {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})}});
								"><img src="images/trash.gif" border="0"/></a>				
				</td>		
				
				<td><input name="e_e" type="text" id="e_e" value="" size="60"  autocomplete='off' readonly="true" />
				<script type="text/javascript">	
				 
				 function end_cal(minDate){ 
				 new Calendar({
				 inputField   	: "e_e",
				 dateFormat		: "%Y-%m-%d",
				 trigger		: "e_e",
				 weekNumbers   	: true,
				 bottomBar		: true,
				 min			: minDate,				 
				 onSelect		: function() {
										this.hide();
										document.<?php echo $formName;?>.e_e.value=this.selection.print("%Y-%m-%d");		
										staff.searchEvent('<?php echo $this->contact_id; ?>',
										document.<?php echo $formName;?>.e_start.value,
										document.<?php echo $formName;?>.e_e.value,
										document.<?php echo $formName;?>.gi_id.value,
										document.<?php echo $formName;?>.e_s.value,'','',
										document.<?php echo $formName;?>.ct.value,
										document.<?php echo $formName;?>.st.value,
										document.<?php echo $formName;?>.zip.value, 
										document.<?php echo $formName;?>.rad.value,'','',
										document.<?php echo $formName;?>.branch.value,
										document.<?php echo $formName;?>.s_code.value,
										{onUpdate: function(response,root){
										document.getElementById('div_search_event').innerHTML=response;
										$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]],
										headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})}}); 
									}				
				  });
				  }
				  dd = '0000-01-01';
				  end_cal(dd);
				
				</script>
				<a href="javascript:void(0);" onclick="javascript: document.<?php echo $formName;?>.e_e.value=''; staff.searchEvent('<?php echo $this->contact_id; ?>',document.<?php echo $formName;?>.e_start.value,document.<?php echo $formName;?>.e_e.value,document.<?php echo $formName;?>.gi_id.value,document.<?php echo $formName;?>.e_s.value,'','',document.<?php echo $formName;?>.ct.value,document.<?php echo $formName;?>.st.value,document.<?php echo $formName;?>.zip.value,document.<?php echo $formName;?>.rad.value,'','',document.<?php echo $formName;?>.branch.value,document.<?php echo $formName;?>.s_code.value, {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})}});
								"><img src="images/trash.gif" border="0"/></a>	
				
				</td>
					
					<td><input name="gi_id" type="text" id="gi_id" value="" size="60" onkeyup="staff.searchEvent('',this.form.e_start.value,this.form.e_e.value,this.value,this.form.e_s.value,'','',this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,'','',this.form.branch.value,this.form.s_code.value, {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})}});" autocomplete='off' /></td>
					
					<td>
					<select name="e_s" id="e_s" style="width:100%" onchange="staff.searchEvent('',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.value,'','',this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,'','',this.form.branch.value,this.form.s_code.value, {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})}});">
								<option value="">-Select-</option>
								  <?php
							  $sql = "select * from ".EM_EVENT_STATUS;
							  $result = $this->db->query($sql,__FILE__,__lINE__);
							  while($row = $this->db->fetch_array($result)){
								?>
								  <option value="<?php echo $row[event_status_id] ?>" ><?php echo $row[event_status] ?></option>
								  <?php
							  }
							  ?>
								</select>					</td>
				</tr>
				<tr>
					<td>City</td>
					<td>State</td>
					<td>Zip</td>
				    <td>Radius</td>
				</tr>
				<tr>
					<td><input name="ct" type="text" id="ct" value="" size="60" onkeyup="staff.searchEvent('',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,'','',this.value,this.form.st.value,this.form.zip.value,this.form.rad.value,'','',this.form.branch.value,this.form.s_code.value, {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})}});" autocomplete='off' /></td>
					
					<td><select name="st" id="st" style="width:100%" onchange="staff.searchEvent('',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,'','',this.form.ct.value,this.value,this.form.zip.value,this.form.rad.value,'','',this.form.branch.value,this.form.s_code.value, {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})}});">
					<option value="">Select State</option>
						<?php
							$state=file("../state_us.inc");
							foreach($state as $val){
							$state = trim($val);
						?>
						<option  value="<?php echo $state;?>"><?php echo $state;?></option>
						<?php
							}
						?>
					 </select>
					</td>
					
					<td><input name="zip" type="text" id="zip" value="" size="60" onkeyup="staff.searchEvent('',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,'','',this.form.ct.value,this.form.st.value,this.value,this.form.rad.value,'','',this.form.branch.value,this.form.s_code.value, {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})}});" autocomplete='off' /></td>
				    <td><input name="rad" type="text" id="rad" value="" size="60" onkeyup="staff.searchEvent('',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,'','',this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,'','',this.value,this.form.branch.value,this.form.s_code.value, {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})}});" autocomplete='off' /></td>
			    </tr>
				
				<tr>
					<td>Branch</td>
					<td>Service Code</td>
					<td>&nbsp;</td>
				    <td>&nbsp;</td>
				</tr>
				
				<tr>
				<td><select name="branch" id="branch" style="width:100%" onchange="staff.searchEvent('',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,'','',this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,'','',this.form.branch.value,this.form.s_code.value, {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})}});">
							<option value="">-Select-</option>
							  <?php
						  $sql = "select distinct(customer_name) from ".EM_EVENT;
						  $result = $this->db->query($sql,__FILE__,__LINE__);
						  while($row = $this->db->fetch_array($result)){
							if(trim($row[customer_name])!=''){
							?>
							  <option value="<?php echo $row[customer_name] ?>" ><?php echo $row[customer_name] ?></option>
							  <?php
						  	}
						  }
						  ?>
				  </select>
				  </td>

				<td><select name="s_code" id="s_code" style="width:100%" onchange="staff.searchEvent('',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,'','',this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,'','',this.form.branch.value,this.form.s_code.value, {onUpdate: function(response,root){
document.getElementById('div_search_event').innerHTML=response;
$('#search_event').tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,1]], headers: { 0:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}})}});">
							<option value="">-Select-</option>
							  <?php
						  $sql = "select * from ".EM_SERVICES_TYPE;
						  $result = $this->db->query($sql,__FILE__,__LINE__);
						  while($row = $this->db->fetch_array($result)){
							?>
							  <option value="<?php echo $row[services_id] ?>" ><?php echo $row[services_type] ?></option>
							  <?php
						  }
						  ?>
				  </select>
				  </td>
				</tr>
				
				
				</table>
		<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
		<?php } ?>
		</form>
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	
	function searchEvent($contact_id='',$start_date='',$end_date='',$ge_id='',$event_status='',$staff_status='',$staff_type='',$city='',$state='',$zip='',$rad=0,$type='',$unstaffed='',$branch='',$s_code='')
	{
		ob_start();
		
		if($start_date=='') $start_date='0000-01-01';
		if($end_date=='') $end_date='9999-12-31';
		
		if($contact_id)	{
			
			$this->contact_id = $contact_id;		
		
			$sql = "select a.*,b.* from ".EM_CERTIFICATION_TYPE." a, ".CONTACT_ADDRESS." b where a.certification_id in ( select certification_type_id from ".EM_CERTIFICATION." where contact_id= '".$this->contact_id."') and b.contact_id= '".$this->contact_id."'";
			
			$result = $this->db->query($sql,__FILE__,__LINE__);
			$row = $this->db->fetch_array($result);
			
			//$sql_staffing= "select distinct a.*, b.* from ".EM_STAFFING." a,".EM_EVENT." b,".EM_DATE." c where a.type in ( select distinct(certification_type_id) from ".EM_CERTIFICATION." where contact_id='".$this->contact_id."' and expiration_date>'".date('Y-m-d',time())."') and  a.event_id = b.event_id and a.event_id = c.event_id and a.contact_id=''";//commented on 30 july
			
			$sql_staffing= "select distinct a.*, b.* from ".EM_STAFFING." a,".EM_EVENT." b,".EM_DATE." c where a.type='$type' and  a.event_id = b.event_id and a.event_id = c.event_id and a.contact_id=''";
			
		
			if($staff_status!=''){
			$sql_staffing.= " and a.status= '$staff_status'";
			}
			
			if($start_date!='' or $end_date!=''){
				$sql_staffing .= " and c.event_date between '$start_date' and '$end_date'";
			}
			
			if($ge_id!=''){
				$sql_staffing .= " and b.group_event_id like '$ge_id%'";
			}
			
			if($event_status!=''){
				$sql_staffing .= " and b.event_status like '$event_status%'";
			}
									
			if($city!=''){
				$sql_staffing .= " and b.city like '$city%'";
			}
			
			if($state!=''){
				$sql_staffing .= " and b.state like '$state%'";
			}
			
			if($zip!='' and $rad==0){
				$sql_staffing .= " and b.zip like '$zip%'";
			}
			
			/*if($type!='' and $type){
				$sql_staffing .= " and a.type = '$type'";
			}*///commented on 30 july
			
			$sql_con = "select * from ".CONTACT_ADDRESS." where contact_id='$this->contact_id'";
			$result_con = $this->db->query($sql_con,__FILE__,__LINE__);
			$row_con = $this->db->fetch_array($result_con);
			
			if($staff_type=='License'){
				$sql_staffing .= " and b.state='".$row[state]."' and a.type='$row[certification_id]'";
			}
			if($staff_type=='Certification'){
				$sql_staffing .= " and a.type='$row[certification_id]'";
			}
		}
		

		
		else {
			
					
			$sql_staffing= "select distinct b.* from ".EM_EVENT." b";
			
			if($start_date!='0000-01-01' or $end_date!='9999-12-31'){
				$sql_staffing .= " ,".EM_DATE." c ";
			}
			
			if($s_code){
				$sql_staffing .= " ,".EM_SERVICES." d,".EM_SERVICES_TYPE." e ";
			}
			
			$sql_staffing .= "  where 1";
			
			if($start_date!='0000-01-01' or $end_date!='9999-12-31'){
				$sql_staffing .= " and b.event_id = c.event_id and c.event_date between '$start_date' and '$end_date'";
			}
	
			if($ge_id!=''){
				$sql_staffing .= " and b.group_event_id like '$ge_id%'";
			}
			
			if($event_status!=''){
				$sql_staffing .= " and b.event_status like '$event_status%'";
			}
									
			if($city!=''){
				$sql_staffing .= " and b.city like '$city%'";
			}
			
			if($state!=''){
				$sql_staffing .= " and b.state like '$state%'";
			}
			
			if($branch){
				$sql_staffing .= " and b.customer_name like '%$branch%'";
			}
			
			if($s_code){
				$sql_staffing .= " and b.event_id=d.event_id and d.service_type=e.services_id and d.service_type='$s_code' ";
			}
		
		}
		//echo $sql_staffing;
		$result_staffing=$this->db->query($sql_staffing,__FILE__,__LINE__);
		/*******************************************************************/
		
		$contact = array();
		$x=0;
		while($row_staff=$this->db->fetch_array($result_staffing)){
			foreach($row_staff as $key=>$value){
				$contact[$x][$key] = $value;
			}
			$x++;
		}
		$contactInRange = array();
		if($rad!=0 ){
		$x=0;
		if($contact_id)
		$zip = $row[zip];
		$contact_list = array();
		$row_pnt = $this->zip_obj->get_zip_point($zip);
		if($row_pnt[lat]){
			$sql = "SELECT e.zip,e.event_id,c.lat,c.lon,c.zip_code , (((acos( sin( ( ".$row_pnt[lat]." * pi( ) /180 ) ) * sin( (c.lat * pi( )/180 ) ) + cos( ( ".$row_pnt[lat]." * pi( ) /180 ) ) * cos( (c.lat * pi( ) /180 )) * cos( ((".$row_pnt[lon]." - c.lon) * pi( ) /180 )))) *180 / pi( )) *60 * 1.1515) AS distance FROM ".ZIP_CODE." c,".EM_EVENT." e WHERE e.zip = c.zip_code";
			if($rad>0){
				$sql .= " HAVING distance<=$rad";
			} 
			
			$sql .= " ORDER BY distance ASC";

			$result = $this->db->query($sql,__FILE__,__LINE__);	
			
			while($row_c = $this->db->fetch_array($result)){
			
				foreach($row_c as $key=>$value){
					$contact_list[$x][$key] = $value;
				}
				$x++;
			}
			
		}
		$x=0;
		
			foreach($contact as $key=>$value){				
				foreach($contact_list as $key_zip=>$value_zip){
					if($value_zip[zip]==$value[zip]){
						$contactInRange[$x] = $value;
						$contactInRange[$x]['distance'] = $value_zip['distance'];
						$x++;
						break;
					}
				}
			}
			
		$tmp = array();
		foreach($contactInRange as $ma)
			$tmp[] = $ma['distance'];
		array_multisort($tmp,SORT_ASC,SORT_NUMERIC,$contactInRange);
		
		} else {
			$contactInRange = $contact;
		}
		/*******************************************************************/		
		?>
		<form action=""  method="post" enctype="multipart/form-data" name="frm_search_event">	
		<table id="search_event" class="event_form small_text" width="100%">
		<thead>
		<tr>
			<th></th>
			<th>GE ID</th>
			<th>Start Date</th>
			<th>City</th>
			<th>State</th>		
			<th>Event Status</th>
			<?php if($contact_id){ ?>
				<th>Event Position</th>
				<th>Staff Status</th>
			<?php } ?>
			<th>Note</th>
		</tr>
		</thead>
		<tbody>
		
		<?php  	
		$i=0;	
		foreach($contactInRange as $key=>$value)
		{
			$sql_status = "select * from ".EM_EVENT_STATUS." where event_status_id='".$value['event_status']."'";
			$result_status = $this->db->query($sql_status,__FILE__,__LINE__);			
			$row_status = $this->db->fetch_array($result_status);
			
/*				$sql_date="select * from ".EM_DATE." where event_id='".$value[event_id]."'";
				$result_date=$this->db->query($sql_date,__FILE__,__LINE__);
				$dateArray = array();
				$x=0;
				while($row_date=$this->db->fetch_array($result_date))
				{
					$dateArray[$x++] = $row_date['event_date'];
				}
				$flag = "available";
				if($start_date!='' or $end_date!=''){
					$flag = "unavailable";
					$dates = array();
					$s_date = explode('-',$start_date);
					$e_date = explode('-',$end_date);
					$date1 = mktime(0,0,0,$s_date[1],$s_date[2],$s_date[0]);
					$date2 = mktime(0,0,0,$e_date[1],$e_date[2],$e_date[0]);
					$dateDiff = $date2 - $date1;
					$fullDays = floor($dateDiff/(60*60*24));	
					for($j=0;$j<$fullDays;$j++){
						$dates[$j] = date('Y-m-d',mktime(0,0,0,$s_date[1],$s_date[2]+$j,$s_date[0]));
					}
					foreach($dateArray as $key1)
					{
						foreach ($dates as $key2)
						{	
							if ($key1 == $key2)
							{
								$flag = "available";
								break;
							}
						}
					}	
				}		
			if($flag == "available"){
*/			?>
			<?php
			$sql_st = "select * from ".EM_STAFFING_STATUS." where status_id= '".$value['status']."'";
			$result_st = $this->db->query($sql_st,__FILE__,__lINE__);			
			$row_st = $this->db->fetch_array($result_st);
			
			$sql_tp = "select * from ".EM_CERTIFICATION_TYPE." where certification_id= '".$value['type']."'";
			$result_tp = $this->db->query($sql_tp,__FILE__,__lINE__);			
			$row_tp = $this->db->fetch_array($result_tp);	
			
			//print_r($value);
			?>
			<tr>
				 <td><input type="radio" name="rd_event" id="rd_event<?php echo $value['staffing_id']; ?>" value="<?php echo $value['staffing_id']; ?>" /></td>
				 <td><a href="event_profile.php?event_id=<?php echo $value['event_id']; ?>"><?php echo $value['group_event_id']; ?></a></td>
				 <td><?php echo $this->start_date($value['event_id']); ?></td>
				 <td><?php echo $value['city']; ?></td>
				 <td><?php echo $value['state']; ?></td>
				 <td><?php echo $row_status['event_status']; ?></td>
				 <?php if($contact_id){ ?>
				 	 <td><?php echo $row_st['status']; ?></td>	
					 <td><?php echo $row_tp['cert_type']; ?></td>
				 <?php } ?>			 
				 <td><?php if($value['note']) echo $value['note']; ?></td>
			</tr>
			<?php 
			$i++; 
			//}
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
			<?php if($contact_id){ ?>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			<?php } ?>
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
	
	function start_date($event_id) {		
		$this->event_id=$event_id;
		$sql = "select * from ".EM_DATE." where event_id= ".$this->event_id." order by  event_date asc limit 1";
		$result = $this->db->query($sql,__FILE__,__lINE__);			
		$row = $this->db->fetch_array($result);		
		return $row['event_date'];				
	}
	
	function end_date($event_id) {		
		$this->event_id=$event_id;
		$sql = "select * from ".EM_DATE." where event_id= ".$this->event_id." order by  event_date desc limit 1";
		$result = $this->db->query($sql,__FILE__,__lINE__);			
		$row = $this->db->fetch_array($result);		
		return $row['event_date'];				
	}

	function assignEvent($contact_id,$event_id)
	{
		ob_start();
				
		$update_sql_array = array();
		$update_sql_array['contact_id'] = $contact_id;
		
		$this->db->update(EM_STAFFING,$update_sql_array,'event_id',$event_id);
		echo $this->open_Position('local',$event_id);
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function unstaffedSearchBox()
	{
		ob_start();
		$formName = 'frm_unstaffedSearchBox';
		?>
		<div>
		<form method="post" name="<?php echo $formName; ?>" id="<?php echo $formName; ?>"  onsubmit="return false;">
		<h2>Search Unstaffed Positions</h2>
			<table class="table" width="100%" >
			<tr>
				<td>Event From</td>
				<td>Event To </td>
				<td>GI ID</td>
				<td>Event Status</td>
			</tr>
			<tr>
				<td><input name="e_start" type="text" id="e_start" value="" size="60" autocomplete='off' readonly="true" />
				
				<script type="text/javascript">	 
				 
				 function start_cal(){
				 new Calendar({
				 inputField   	: "e_start",
				 dateFormat		: "%Y-%m-%d",
				 trigger		: "e_start",
				 weekNumbers   	: true,
				 bottomBar		: true,				 
				 onSelect		: function() {
										
										this.hide();
										document.<?php echo $formName;?>.e_start.value=this.selection.print("%Y-%m-%d");										
										staff.getUnstaffedEvents('all',document.<?php echo $formName;?>.e_start.value,
										document.<?php echo $formName;?>.e_e.value,
										document.<?php echo $formName;?>.gi_id.value,
										document.<?php echo $formName;?>.e_s.value,
										document.<?php echo $formName;?>.s_t.value,
										document.<?php echo $formName;?>.ct.value,
										document.<?php echo $formName;?>.st.value,
										document.<?php echo $formName;?>.zip.value,
										document.<?php echo $formName;?>.rad.value,
										document.<?php echo $formName;?>.s_s.value,
										document.<?php echo $formName;?>.p_type.value,
										document.<?php echo $formName;?>.branch.value,
										document.<?php echo $formName;?>.scode.value,
										{onUpdate: function(response,root){
										document.getElementById('div_unstaff').innerHTML=response;
										$('#search_unstaffedEvent')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]],
										headers: { 0:{sorter: false}, 9:{sorter: false}}}) }} );
										end_cal(this.selection.get()+1);
									}				
				  });
				}
				start_cal();
				</script>
				<a href="javascript:void(0);" onclick="javascript: document.<?php echo $formName;?>.e_start.value=''; staff.getUnstaffedEvents('all',document.<?php echo $formName;?>.e_start.value,document.<?php echo $formName;?>.e_e.value,document.<?php echo $formName;?>.gi_id.value,document.<?php echo $formName;?>.e_s.value,document.<?php echo $formName;?>.s_t.value,document.<?php echo $formName;?>.ct.value,document.<?php echo $formName;?>.st.value,document.<?php echo $formName;?>.zip.value,document.<?php echo $formName;?>.rad.value,document.<?php echo $formName;?>.s_s.value,document.<?php echo $formName;?>.p_type.value,document.<?php echo $formName;?>.branch.value,document.<?php echo $formName;?>.scode.value, {onUpdate: function(response,root){
										document.getElementById('div_unstaff').innerHTML=response;
										$('#search_unstaffedEvent')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]],
										headers: { 0:{sorter: false}, 9:{sorter: false}}}) }} );
								"><img src="images/trash.gif" border="0"/></a>			
				</td>		
				
				<td><input name="e_e" type="text" id="e_e" value="" size="60"  autocomplete='off' readonly="true" />
				<script type="text/javascript">	
				 function end_cal(minDate){ 
				 new Calendar({
				 inputField   	: "e_e",
				 dateFormat		: "%Y-%m-%d",
				 trigger		: "e_e",
				 weekNumbers   	: true,
				 bottomBar		: true,	
				 min			: minDate,			 
				 onSelect		: function() {
										this.hide();
										document.<?php echo $formName;?>.e_e.value=this.selection.print("%Y-%m-%d");
										staff.getUnstaffedEvents('all',document.<?php echo $formName;?>.e_start.value,
										document.<?php echo $formName;?>.e_e.value,
										document.<?php echo $formName;?>.gi_id.value,
										document.<?php echo $formName;?>.e_s.value,
										document.<?php echo $formName;?>.s_t.value,
										document.<?php echo $formName;?>.ct.value,
										document.<?php echo $formName;?>.st.value,
										document.<?php echo $formName;?>.zip.value,
										document.<?php echo $formName;?>.rad.value,
										document.<?php echo $formName;?>.s_s.value,
										document.<?php echo $formName;?>.p_type.value,
										document.<?php echo $formName;?>.branch.value,
										document.<?php echo $formName;?>.scode.value,
										{onUpdate: function(response,root){
										document.getElementById('div_unstaff').innerHTML=response;
										$("#search_unstaffedEvent")
										.tablesorter({widthFixed: true, widgets: ["zebra"], sortList: [[2,0]],
										headers: { 0:{sorter: false}, 9:{sorter: false}}}) }} );
									}				
				  });
				  }
				  dd = '0000-01-01';
				  end_cal(dd);
				</script>	
				<a href="javascript:void(0);" onclick="javascript: document.<?php echo $formName;?>.e_e.value=''; staff.getUnstaffedEvents('all',document.<?php echo $formName;?>.e_start.value,document.<?php echo $formName;?>.e_e.value,document.<?php echo $formName;?>.gi_id.value,document.<?php echo $formName;?>.e_s.value,document.<?php echo $formName;?>.s_t.value,document.<?php echo $formName;?>.ct.value,document.<?php echo $formName;?>.st.value,document.<?php echo $formName;?>.zip.value,document.<?php echo $formName;?>.rad.value, document.<?php echo $formName;?>.s_s.value,document.<?php echo $formName;?>.p_type.value,document.<?php echo $formName;?>.branch.value,document.<?php echo $formName;?>.scode.value, {onUpdate: function(response,root){
										document.getElementById('div_unstaff').innerHTML=response;
										$('#search_unstaffedEvent')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]],
										headers: { 0:{sorter: false}, 9:{sorter: false}}}) }} );
								"><img src="images/trash.gif" border="0"/></a>
				</td>
				
				<td><input name="gi_id" type="text" id="gi_id" value="" size="60" onkeyup="staff.getUnstaffedEvents('all',this.form.e_start.value,this.form.e_e.value,this.value,this.form.e_s.value,this.form.s_t.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,this.form.s_s.value,this.form.p_type.value,this.form.branch.value,this.form.scode.value,{onUpdate: function(response,root){
										document.getElementById('div_unstaff').innerHTML=response;
										$('#search_unstaffedEvent')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]],
										headers: { 0:{sorter: false}, 9:{sorter: false}}}) }} );" autocomplete='off' /></td>
				<td>
				<select name="e_s" id="e_s" style="width:100%" onchange="staff.getUnstaffedEvents('all',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.value,this.form.s_t.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,this.form.s_s.value,this.form.p_type.value,this.form.branch.value,this.form.scode.value, {onUpdate: function(response,root){
										document.getElementById('div_unstaff').innerHTML=response;
										$('#search_unstaffedEvent')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]],
										headers: { 0:{sorter: false}, 9:{sorter: false}}}) }} );">
							<option value="">-Select-</option>
							  <?php
						  $sql = "select * from ".EM_EVENT_STATUS;
						  $result = $this->db->query($sql,__FILE__,__lINE__);
						  while($row = $this->db->fetch_array($result)){
							?>
							  <option value="<?php echo $row[event_status_id] ?>" ><?php echo $row[event_status] ?></option>
							  <?php
						  }
						  ?>
				  </select>
				</td>


			</tr>
			<tr>
				<td>Staff Type</td>
				<td>City</td>
				<td>State</td>
				<td>Zip</td>
			</tr>
			<tr>
				<td><select name="s_t" id="s_t" style="width:100%" onchange="staff.getUnstaffedEvents('all',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,this.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,this.form.s_s.value,this.form.p_type.value,this.form.branch.value,this.form.scode.value, {onUpdate: function(response,root){
										document.getElementById('div_unstaff').innerHTML=response;
										$('#search_unstaffedEvent')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]],
										headers: { 0:{sorter: false}, 9:{sorter: false}}}) }} );">
							<option value="">-Select-</option>
							<?php
						  $sql = "select * from ".EM_CERTIFICATION_TYPE;
						  $result = $this->db->query($sql,__FILE__,__lINE__);
						  while($row = $this->db->fetch_array($result)){
							?>
							  <option value="<?php echo $row[certification_id] ?>" ><?php echo $row[cert_type] ?></option>
							  <?php
						  }
						  ?>
							</select> </td>
				<td><input name="ct" type="text" id="ct" value="" size="60" onkeyup="staff.getUnstaffedEvents('all',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,this.form.s_t.value,this.value,this.form.st.value,this.form.zip.value,this.form.rad.value,this.form.s_s.value,this.form.p_type.value,this.form.branch.value,this.form.scode.value, {onUpdate: function(response,root){
										document.getElementById('div_unstaff').innerHTML=response;
										$('#search_unstaffedEvent')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]],
										headers: { 0:{sorter: false}, 9:{sorter: false}}}) }} );" autocomplete='off' /></td>
				
				<td><select name="st" id="st" style="width:100%" onchange="staff.getUnstaffedEvents('all',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,this.form.s_t.value,this.form.ct.value,this.value,this.form.zip.value,this.form.rad.value, this.form.s_s.value,this.form.p_type.value,this.form.branch.value,this.form.scode.value, {onUpdate: function(response,root){
										document.getElementById('div_unstaff').innerHTML=response;
										$('#search_unstaffedEvent')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]],
										headers: { 0:{sorter: false}, 9:{sorter: false}}}) }} );">
						<option value="">Select State</option>
						<?php
							$state=file("../state_us.inc");
							foreach($state as $val){
							$state = trim($val);
						?>
						<option value="<?php echo $state;?>"><?php echo $state;?></option>
						<?php
							}
						?>
					 </select>
							 </td>
										
				<td><input name="zip" type="text" id="zip" value="" size="60" onkeyup="staff.getUnstaffedEvents('all',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,this.form.s_t.value,this.form.ct.value,this.form.st.value,this.value,this.form.rad.value,this.form.s_s.value,this.form.p_type.value,this.form.branch.value,this.form.scode.value, {onUpdate: function(response,root){
										document.getElementById('div_unstaff').innerHTML=response;
										$('#search_unstaffedEvent')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]],
										headers: { 0:{sorter: false}, 9:{sorter: false}}}) }} );" autocomplete='off' /></td>
			
			</tr>

			<tr>
				<td>Radius</td>
				<td>Staff Status</td>
				<td>Possition Type</td>
				<td>Branch</td>
			</tr>
			<tr>
										
				<td><input name="rad" type="text" id="rad" value="" size="60" onkeyup="staff.getUnstaffedEvents('all',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,this.form.s_t.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.value,this.form.s_s.value,this.form.p_type.value,this.form.branch.value,this.form.scode.value, {onUpdate: function(response,root){
										document.getElementById('div_unstaff').innerHTML=response;
										$('#search_unstaffedEvent')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]],
										headers: { 0:{sorter: false}, 9:{sorter: false}}}) }} );" autocomplete='off' /></td>

				<td><select name="s_s" id="s_s" style="width:100%" onchange="staff.getUnstaffedEvents('all',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,this.form.s_t.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,this.value,this.form.p_type.value,this.form.branch.value,this.form.scode.value,  {onUpdate: function(response,root){
										document.getElementById('div_unstaff').innerHTML=response;
										$('#search_unstaffedEvent')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]],
										headers: { 0:{sorter: false}, 9:{sorter: false}}}) }});">
							<option value="">-Select-</option>
							  <?php
						  $sql = "select * from ".EM_STAFFING_STATUS;
						  $result = $this->db->query($sql,__FILE__,__LINE__);
						  while($row = $this->db->fetch_array($result)){
							?>
							  <option value="<?php echo $row[status_id] ?>" ><?php echo $row[status] ?></option>
							  <?php
						  }
						  ?>
				  </select>
				  </td>
				<td><select name="p_type" id="p_type" style="width:100%" onchange="staff.getUnstaffedEvents('all',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,this.form.s_t.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,this.form.s_s.value,this.value,this.form.branch.value,this.form.scode.value,  {onUpdate: function(response,root){
										document.getElementById('div_unstaff').innerHTML=response;
										$('#search_unstaffedEvent')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]],
										headers: { 0:{sorter: false}, 9:{sorter: false}}}) }});">
							<option value="">-Select-</option>
							<option value="Unstaffed" selected="selected" >Unstaffed</option>
							<option value="Staffed" >Staffed</option>
						
				  </select></td>

				<td><select name="branch" id="branch" style="width:100%" onchange="staff.getUnstaffedEvents('all',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,this.form.s_t.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,this.form.s_s.value,this.form.p_type.value,this.form.branch.value,this.form.scode.value,  {onUpdate: function(response,root){
										document.getElementById('div_unstaff').innerHTML=response;
										$('#search_unstaffedEvent')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]],
										headers: { 0:{sorter: false}, 9:{sorter: false}}}) }});">
							<option value="">-Select-</option>
							  <?php
						  $sql = "select distinct(customer_name) from ".EM_EVENT;
						  $result = $this->db->query($sql,__FILE__,__LINE__);
						  while($row = $this->db->fetch_array($result)){
							if(trim($row[customer_name])!=''){
							?>
							  <option value="<?php echo $row[customer_name] ?>" ><?php echo $row[customer_name] ?></option>
							  <?php
						  	}
						  }
						  ?>
				  </select>
				  </td>

			</tr>
			
			<tr>
				<th>Service Code</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
			</tr>
			
			<tr>
				<td><select name="scode" id="scode" style="width:100%" onchange="staff.getUnstaffedEvents('all',this.form.e_start.value,this.form.e_e.value,this.form.gi_id.value,this.form.e_s.value,this.form.s_t.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.rad.value,this.form.s_s.value,this.form.p_type.value,this.form.branch.value,this.form.scode.value,  {onUpdate: function(response,root){
										document.getElementById('div_unstaff').innerHTML=response;
										$('#search_unstaffedEvent')
										.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]],
										headers: { 0:{sorter: false}, 9:{sorter: false}}}) }});">
							<option value="">-Select-</option>
							  <?php
						  $sql = "select * from ".EM_SERVICES_TYPE;
						  $result = $this->db->query($sql,__FILE__,__LINE__);
						  while($row = $this->db->fetch_array($result)){
							?>
							  <option value="<?php echo $row[services_id] ?>" ><?php echo $row[services_type] ?></option>
							  <?php
						  }
						  ?>
				  </select>
				  </td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			</table>
		
		</form>
		<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;		
	}
	
	function getUnstaffedEvents($all='',$start_date='',$end_date='',$ge_id='',$event_status='',$staff_type='',$city='',$state='',$zip='',$rad=0,$staff_status='',$possitiontype='',$branch='',$s_code='')
	{
		
		ob_start();
		$formName = 'frm_UnstaffedEvents';
		if($start_date=='') $start_date='0000-01-01';
		if($end_date=='') $end_date='9999-12-31';

		if($all=='all')
		{
			if($s_code) 
			$sql= "select distinct a.*, b.*,e.services_type from ".EM_EVENT." a, ".EM_STAFFING." b, ".EM_DATE." c, ".EM_SERVICES." d, ".EM_SERVICES_TYPE." e where a.event_id=b.event_id and b.event_id=c.event_id and a.event_id=c.event_id and a.event_id=d.event_id and d.service_type=e.services_id and d.service_type='$s_code'";
			
			else
			$sql= "select distinct a.*, b.* from ".EM_EVENT." a, ".EM_STAFFING." b, ".EM_DATE." c where a.event_id=b.event_id and b.event_id=c.event_id and a.event_id=c.event_id";
			
			if($staff_status!=''){
				$sql .= " and b.status='".$staff_status."'";
			}
			
			if($possitiontype=='Unstaffed')
			{
				$sql .=" and b.contact_id=''";
			}
			if($possitiontype=='Staffed')
			{
				$sql .=" and b.contact_id!=''";
			}
			if($start_date!='' or $end_date!=''){
				$sql .= " and c.event_date between '$start_date' and '$end_date'";
			}
			if($ge_id!=''){
				$sql .= " and a.group_event_id like '$ge_id%'";
			}
			
			if($event_status!=''){
				$sql .= " and a.event_status like '$event_status%'";
			}

			if($staff_type!=''){
				$sql .= " and b.type ='$staff_type'";
			}
									
			if($city!=''){
				$sql .= " and a.city like '$city%'";
			}
			
			if($state!=''){
				$sql .= " and a.state like '$state%'";
			}
			
			if($branch!=''){
				$sql .= " and a.customer_name like '$branch%'";
			}

		$sql .= " order by c.event_date asc";
		}
		else
		{
		$sql= "select distinct a.*, b.* from ".EM_EVENT." a, ".EM_STAFFING." b, ".EM_DATE." c where a.event_id=b.event_id and b.contact_id='' and b.event_id=c.event_id and a.event_id=c.event_id and c.event_date>'".date("Y-m-d")."' order by c.event_date asc limit 5";
		}
		$result = $this->db->query($sql,__FILE__,__LINE__);			
		/*****************************Distance search*******************************************/
		$contact = array();
		$contactInRange = array();
		$x=0;
		while($row=$this->db->fetch_array($result)){
			foreach($row as $key=>$value){
				$contact[$x][$key] = $value;
			}
			$x++;
		}

		if($zip!='' and $rad!=0)
		{
			$x=0;
			$contact_list = array();
			$row = $this->zip_obj->get_zip_point($zip);
			if($row[lat]){
				$sql = "SELECT e.*,c.lat,c.lon,c.zip_code , (((acos( sin( ( ".$row[lat]." * pi( ) /180 ) ) * sin( (c.lat * pi( )/180 ) ) + cos( ( ".$row[lat]." * pi( ) /180 ) ) * cos( (c.lat * pi( ) /180 )) * cos( ((".$row[lon]." - c.lon) * pi( ) /180 )))) *180 / pi( )) *60 * 1.1515) AS distance FROM ".ZIP_CODE." c,".EM_EVENT." e WHERE e.zip = c.zip_code";
				if($rad>0){
					$sql .= " HAVING distance<=$rad";
				} 
				
				$sql .= " ORDER BY distance ASC";
	
				$result = $this->db->query($sql,__FILE__,__LINE__);	
				
				while($row_c = $this->db->fetch_array($result)){
				
					foreach($row_c as $key=>$value){
						$contact_list[$x][$key] = $value;
					}
					$x++;
				}
				
			}
			
			$x=0;
			foreach($contact as $key=>$value){				
				foreach($contact_list as $key_zip=>$value_zip){
					if($value_zip[zip]==$value[zip]){
						$contactInRange[$x] = $value;
						$contactInRange[$x]['distance'] = $value_zip['distance'];
						$x++;
						break;
					}
				}
			}
		$tmp = array();
		foreach($contactInRange as $ma)
			$tmp[] = $ma['distance'];
		array_multisort($tmp,SORT_ASC,SORT_NUMERIC,$contactInRange);
		} else {
			$contactInRange = $contact;
		}
		/*************************************************************************/
		?>
		<form name="<?php echo $formName; ?>" id="<?php echo $formName; ?>">
		<table id="search_unstaffedEvent" class="event_form small_text" width="100%">
		<thead>
		<tr>
		<th></th>
		<th>GE ID::</th>
		<th>Start Day::</th>
		<th>End Day::</th>
		<th>City::</th>
		<th>St::</th>
		<th>Staff Type::</th>
		<th>Staff Status::</th>
		<th>Staff::</th>
		<th>Note::</th>
		</tr>
		</thead>
		<tbody>
	
		<?php
		 $i=0;
		 foreach($contactInRange as $key=>$value) {	
		$sql_status = "select * from ".EM_STAFFING_STATUS." where status_id='".$value[status]."'";
		$result_status = $this->db->query($sql_status,__FILE__,__lINE__);
		$row_status = $this->db->fetch_array($result_status);
		
		
		
		$sql_positn = "select * from ".EM_CERTIFICATION_TYPE." where certification_id='".$value[type]."'";
		$result_positn = $this->db->query($sql_positn,__FILE__,__lINE__);
		$row_positn = $this->db->fetch_array($result_positn);		
		
		
		
		?>
		<tr>
		<td>
		<?php if($all!=''){?>
		<input type="radio" name="rd_unstaff" id="rd_unstaff<?php echo $value[event_id]; ?>" value="<?php echo $value[event_id].'#'.$value[staffing_id]; ?>" />
		<?php } ?>
		</td>
	    <td><a href= "event_profile.php?event_id=<?php echo $value[event_id];?>"><?php echo $value[group_event_id]; ?></a></td>
	    <td><?php echo $this->start_date($value['event_id']); ?></td>
	    <td><?php echo $this->end_date($value[event_id]); ?></td>
	    <td><?php echo $value[city]; ?></td>
	    <td><?php echo $value[state]; ?></td>
		<td><?php echo $row_positn[cert_type]; ?></td>
	    <td><?php echo $row_status[status]; ?></td>
		<td><?php $sql = "select * from ".EM_STAFFING." a,".TBL_CONTACT." b where staffing_id='$value[staffing_id]' and a.contact_id=b.contact_id";
				  $result = $this->db->query($sql,__FILE__,__lINE__);
				  $row = $this->db->fetch_array($result);?>
				  <a href="contact_profile.php?contact_id=<?php echo $row[contact_id] ?>"><?php echo $row[first_name].' '.$row[last_name]; ?></a></td>
		<td><?php echo $value[notes]; ?></td>
	    </tr>
		
		<?php $i++;  
		
		} // end of for each
		?>
		
		<?php if($i==0){ ?>
		  <tr style="line-height:30px;">
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>no result</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
		  <?php } ?>
		</tbody>
		</table>
		 <div class="verysmall_text form_bg">*Hold 'Shift Key' to sort multiple field</div>
		<?php 
		if($all !='all')
		{
		?>
		<div align="right"><a href="show_all.php?action=positions">more..</a></div>
	<?php } ?> 
		</form>
		
		<?php		
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;		
	}
	
	function validId($contact_id,$staffing_id)
	{
		ob_start();
		$msg = '';
		$sql_staff = "select type,event_id from ".EM_STAFFING." where staffing_id='".$staffing_id."'";
		$res_staff = $this->db->query($sql_staff,__FILE__,__LINE__);
		$row_staff = $this->db->fetch_array($res_staff);
		/*******************************************/
		$sql = "select * from ".TBL_CONTACT." where contact_id='".$contact_id."'";
		$result3 = $this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($result3)==0)
		$msg .= "The contact id does not exists. ";
		/*******************************************/
		else{
		/*******************************************/
		$sql = "select * from ".EM_CONTACT_STATUS." where contact_id='".$contact_id."' and user_status='Active'";
		$result1 = $this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($result1)==0)
		$msg .= "The user status of this contact is not \"Active\". ";
		/*******************************************/
		/*******************************************/
		$sql = "select * from ".EM_CERTIFICATION." where contact_id='".$contact_id."' and certification_type_id='".$row_staff[type]."' and expiration_date>'".date('Y-m-d',time())."'";
		$result2 = $this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($result2)==0)
		$msg .= "Either this contact does not have the required certification type or the license of this contact has expired. ";
		/*******************************************/
		}
		if($msg!=''){
		  return "<script type=\"text/javascript\">alert('".$msg."');</script>";
		  return false;
		} else {
		  return true;
		}
		/*if($this->db->num_rows($result1)>0 and $this->db->num_rows($result2)>0)
		  return true;
		else return false;*/
		$html = ob_get_contents();
		ob_end_clean();
		return $html;		
	}
	
	function updateStaffing($staffing_id,$status='nll',$notes='nll')
	{
		ob_start();
		$update_sql_array = array();
		if($status!='nll'){
		  if($status=='empty')
		    $update_sql_array[status] = '';
		  else
		    $update_sql_array[status] = $status;
		}
		if($notes!='nll')
		  $update_sql_array[notes] = $notes;
		$this->db->update(EM_STAFFING,$update_sql_array,'staffing_id',$staffing_id);
		$html = ob_get_contents();
		ob_end_clean();
		return $html;		
	}
	
	function addStaffingStatus($runat)
	{
		switch ($runat){
			case 'local':
			
				$sql = "select * from ".EM_STAFFING_STATUS;
				$result = $this->db->query($sql,__FILE__,__lINE__);
				$result2 = $this->db->query($sql,__FILE__,__lINE__);
				?>
				<script language="javascript" type="text/javascript">
					function validate_status() {						
						 if(document.getElementById("status").value=='')
						 {
							document.getElementById('span_status').innerHTML="Please enter Staffing Status type";
							return false;
						 }						 		
					}				
					function validateFeild_status() {		
											
						if(document.getElementById("type_select_status").value==document.getElementById("type_select_replace_status").value) {
							alert("Plz.. select different different status.");
							return false;
						}
						else {					
						var location="manage_masterfields.php?id="+document.getElementById("type_select_status").value + "&replcedstatuswith="+document.getElementById("type_select_replace_status").value+ "&action=delete_staffing_status";						
							window.location=location;
						
						}				
					}
				
					</script>
				<ul id="error_list">
				<li><span id="span_status" class="required" ></span></li>
				</ul>
				<div>
				<form action="" method="POST" name="<?php echo $formName; ?>" id="<?php echo $formName; ?>" enctype="multipart/form-data">
				<table width="100%" class="table">
				  <tr>
				    <td width="21%"><h2>Staffing Status:</h2></td>
				    <td width="32%"><input type="text" name="status" id="status"></td>
				    <td width="6%"><input type="submit" name="submit_status" id="submit_status" value="Go" style="width:auto"  onclick="return validate_status();" ></td>
					<td width="5%">&nbsp;</td>
					<td width="13%"><select name="type_select_status" id="type_select_status">
                          <?php
				      
				      while($row = $this->db->fetch_array($result)){
				      	?>
                          <option value="<?php echo $row['status_id'] ?>" ><?php echo $row['status'] ?></option>
                          <?php
				      }
				      ?>
                        </select></td>
						
				        <td width="13%">
				      <select name="type_select_replace_status" id="type_select_replace_status">
				      <?php
				      
				      while($row = $this->db->fetch_array($result2)){
				      	?>
				      	<option value="<?php echo $row['status_id'] ?>" ><?php echo $row['status'] ?></option>
				      	<?php
				      }
				      ?>
				      </select></td>
				      <td width="10%"><a href="#" onclick= "javascript: if(confirm('Are you sure?')) return validateFeild_status();"><img src="images/trash.gif" border="0" /></a></td>
					
				  </tr>
				</table>
				</form>
				</div>
				<?php
				break;
			case 'server':
				
				$status=$_POST['status'];				
				$return =true;				
				if($this->Form->ValidField($status,'empty','Please enter status')==false)
					$return =false;
					
				if($return){
					$insert_sql_array = array();
					$insert_sql_array['status'] = $status;
					$this->db->insert(EM_STAFFING_STATUS,$insert_sql_array);
					$_SESSION[msg] = 'Staffing Status has been added';
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
	
	
	function Delete_Staffing_Status($type_select_status, $type_select_replace_status){

			$sql = "delete from ".EM_STAFFING_STATUS." where status_id = '".$type_select_status."'";
			$this->db->query($sql,__FILE__,__LINE__);

			if($type_select_replace_status!='')
			{
				$sql_staff = "update ".EM_STAFFING." set status = '".$type_select_replace_status."' where status = '".$type_select_status."'";	
				$this->db->query($sql_staff,__FILE__,__LINE__);
			}				

		$_SESSION[msg]="Staffing Status Has Been Replaced Successfully!!";
		?>
		<script type="text/javascript">
		 window.location = '<?php echo $_SERVER[PHP_SELF] ?>';
		</script>
		<?php			
		
		
	}
	
	/******************************************************************************************************************************/

/*function advanceAllStaffSearchBox()
	{
		$formName='frm_all_contact';
		?>
		<div>
		<div align="right"> 
		<a href="#" onclick="table2CSV($('#search_table')); return false;"> 
		<img src="images/csv.png"  alt="Export to CSV" /> 
		</a> 
		</div>
		<form onsubmit="return false;" name="<?php echo $formName; ?>">
		<h2>Search Options</h2>
		<table class="table" width="100%" >
		<tr>
		    <td>License/Cert</td>
			<td>License/Cert State</td>
			<td>FirstName</td>
			<td>LastName:</td>
		</tr>
		<tr>
		    <td><select name="credential_type" id="credential_type" style="width:100%" onchange="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });">
		    <option value="">-Select-</option>
			<?php 
			$sql_cert2="select * from ".EM_CERTIFICATION_TYPE;
			$result_cert2 = $this->db->query($sql_cert2,__FILE__,__LINE__);
			while($row_cert2 = $this->db->fetch_array($result_cert2))
			{
			?>
		    <option value="<?php echo $row_cert2['certification_id']; ?>"><?php echo $row_cert2['cert_type']; ?></option>
			<?php } ?>
		    </select>		    </td>
			<td><input name="f_n" type="text" id="f_n" value="" size="60" onkeyup="staff.searchAllStaff(this.form.credential_type.value,this.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });" autocomplete='off' /></td>
			
			<td><input name="l_n" type="text" id="l_n" value="" size="60" onkeyup="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });" autocomplete='off' /></td>
			
			<td><input name="e_c" type="text" id="e_c" value="" size="60" onkeyup="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	}});" autocomplete='off' /></td>
		</tr>
		<tr>
		    
		    <td>User Status</td>
		    <td>System Status</td>
		    <td>Recruiting Status</td>
			<td>City</td>
		</tr>
		<tr>
		    
		    <td><select name="u_s" id="u_s" style="width:100%" onchange="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value, this.form.rad.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });">
		    <option value="">-Select-</option>
			<option value="Active">Active</option>
		    <option value="Inactive">Inactive</option>
			<option value="no-setting">no-setting</option>
		    </select> </td>
		    <td><select name="s_s" id="s_s" style="width:100%" onchange="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });">
		    <option value="">-Select-</option>
			<option value="User">User</option>
		    <option value="Team Lead">Team Lead</option>
			<option value="Inactive">Inactive</option>
		    </select> </td>
		    <td><select name="r_s" id="r_s" style="width:100%" onchange="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });">
		    <option value="" selected="selected">-Select-</option>
			<?php 
			$sql_rct="select * from ".EM_RECRUITING_STATUS;
			$result_rct = $this->db->query($sql_rct,__FILE__,__LINE__);
			while($row_rct = $this->db->fetch_array($result_rct))
			{
			?>
		    <option value="<?php echo $row_rct['recruiting_status_id']; ?>" ><?php echo $row_rct['recruiting_status']; ?></option>
			<?php } ?>
		    </select> </td>
			<td><input name="ct" type="text" id="ct" value="" size="60" onkeyup="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });" autocomplete='off' /></td>
		</tr>
		<tr>
		    
		    <td>State</td>
		    <td>Zip</td>
		    <td>Available On</td>
			<td>Available Till</td>
		</tr>
		<tr>
		    
		    <td><select name="st" id="st" style="width:100%" onchange="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });">
						<option value="">Select State</option>
						<?php
							$state=file("../state_us.inc");
							foreach($state as $val){
							$state = trim($val);
						?>
						<option value="<?php echo $state;?>"><?php echo $state;?></option>
						<?php
							}
						?>
					 </select>
					 </td>
		    
			<td><input name="zip" type="text" id="zip" value="" size="60" onkeyup="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });" autocomplete='off' /></td>
			
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
									staff.searchAllStaff(document.<?php echo $formName;?>.credential_type.value,document.<?php echo $formName;?>.f_n.value,document.<?php echo $formName;?>.l_n.value,document.<?php echo $formName;?>.e_c.value,document.<?php echo $formName;?>.u_s.value,document.<?php echo $formName;?>.s_s.value,document.<?php echo $formName;?>.r_s.value,document.<?php echo $formName;?>.ct.value,document.<?php echo $formName;?>.st.value,document.<?php echo $formName;?>.zip.value,document.<?php echo $formName;?>.available_on.value,document.<?php echo $formName;?>.available_till.value,document.<?php echo $formName;?>.rad.value, 
{onUpdate: function(response,root){ 
document.getElementById('div_search_staff').innerHTML=response;
$('#search_table')
.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
} });
end_cal(this.selection.get()+1);
     			}				
			  });
			  }
			 start_cal();
			</script>	
			<a href="javascript:void(0);" onclick="javascript: document.<?php echo $formName;?>.available_on.value=''; staff.searchAllStaff(document.<?php echo $formName;?>.credential_type.value,document.<?php echo $formName;?>.f_n.value,document.<?php echo $formName;?>.l_n.value,document.<?php echo $formName;?>.e_c.value,document.<?php echo $formName;?>.u_s.value,document.<?php echo $formName;?>.s_s.value,document.<?php echo $formName;?>.r_s.value,document.<?php echo $formName;?>.ct.value,document.<?php echo $formName;?>.st.value,document.<?php echo $formName;?>.zip.value,document.<?php echo $formName;?>.available_on.value,document.<?php echo $formName;?>.available_till.value,document.<?php echo $formName;?>.rad.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} }); return false;"><img src="images/trash.gif" border="0"/></a>			</td>
			
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
									staff.searchAllStaff(document.<?php echo $formName;?>.credential_type.value,document.<?php echo $formName;?>.f_n.value,document.<?php echo $formName;?>.l_n.value,document.<?php echo $formName;?>.e_c.value,document.<?php echo $formName;?>.u_s.value,document.<?php echo $formName;?>.s_s.value,document.<?php echo $formName;?>.r_s.value,document.<?php echo $formName;?>.ct.value,document.<?php echo $formName;?>.st.value,document.<?php echo $formName;?>.zip.value,document.<?php echo $formName;?>.available_on.value,document.<?php echo $formName;?>.available_till.value,document.<?php echo $formName;?>.rad.value,
									{onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });
								}				
			  });
			  }
			
			</script>	
			<a href="javascript:void(0);" onclick="javascript: document.<?php echo $formName;?>.available_till.value=''; staff.searchAllStaff(document.<?php echo $formName;?>.credential_type.value,document.<?php echo $formName;?>.f_n.value,document.<?php echo $formName;?>.l_n.value,document.<?php echo $formName;?>.e_c.value,document.<?php echo $formName;?>.u_s.value,document.<?php echo $formName;?>.s_s.value,document.<?php echo $formName;?>.r_s.value,document.<?php echo $formName;?>.ct.value,document.<?php echo $formName;?>.st.value,document.<?php echo $formName;?>.zip.value,document.<?php echo $formName;?>.available_on.value,document.<?php echo $formName;?>.available_till.value,document.<?php echo $formName;?>.rad.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	}}); return false;"><img src="images/trash.gif" border="0"/></a>			</td>
		</tr>
		<tr>
		  <td>Radius</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
		<tr>
		  <td><input name="rad" type="text" id="rad" value="" size="60" onkeyup="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });" autocomplete='off' /></td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  </tr>
		</table>
		
		</form>
		</div>
		<?php
	}*/
	
	function advanceAllStaffSearchBox()
	{
		$formName='frm_all_contact';
		?>
		<div>
		<div align="right"> 
		<a href="#" onclick="table2CSV($('#search_table')); return false;"> 
		<img src="images/csv.png"  alt="Export to CSV" /> 
		</a> 
		</div>
		<form onsubmit="return false;" name="<?php echo $formName; ?>">
		<h2>Search Options</h2>
		<table class="table" width="100%" >
		<tr>
		    <td>License/Cert</td>
			<td>License/Cert State</td>
			<td>FirstName</td>
			<td>LastName:</td>
		</tr>
		<tr>
		    <td><select name="credential_type" id="credential_type" style="width:100%" onchange="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,this.form.cert_st.value, this.form.company.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });">
		    <option value="">-Select-</option>
			<?php 
			$sql_cert2="select * from ".EM_CERTIFICATION_TYPE;
			$result_cert2 = $this->db->query($sql_cert2,__FILE__,__LINE__);
			while($row_cert2 = $this->db->fetch_array($result_cert2))
			{
			?>
		    <option value="<?php echo $row_cert2['certification_id']; ?>"><?php echo $row_cert2['cert_type']; ?></option>
			<?php } ?>
		    </select>
			</td>
			<td><select name="cert_st" id="cert_st" style="width:100%" onchange="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,this.value,  this.form.company.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });">
						<option value="">Select State</option>
						<?php
							$state=file("../state_us.inc");
							foreach($state as $val){
							$state = trim($val);
						?>
						<option value="<?php echo $state;?>"><?php echo $state;?></option>
						<?php
							}
						?>
					 </select></td>
			<td><input name="f_n" type="text" id="f_n" value="" size="60" onkeyup="staff.searchAllStaff(this.form.credential_type.value,this.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,this.form.cert_st.value, this.form.company.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });" autocomplete='off' /></td>
			
			<td><input name="l_n" type="text" id="l_n" value="" size="60" onkeyup="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,this.form.cert_st.value, this.form.company.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });" autocomplete='off' /></td>
			
		</tr>
		<tr>
		    
			<td>Event Count</td>
		    <td>User Status</td>
		    <td>System Status</td>
		    <td>Recruiting Status</td>
		</tr>
		<tr>
		    
			<td><input name="e_c" type="text" id="e_c" value="" size="60" onkeyup="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,this.form.cert_st.value, this.form.company.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	}});" autocomplete='off' /></td>
		    <td><select name="u_s" id="u_s" style="width:100%" onchange="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value, this.form.rad.value,this.form.cert_st.value, this.form.company.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });">
		    <option value="">-Select-</option>
			<option value="Active">Active</option>
		    <option value="Inactive">Inactive</option>
			<option value="Not_defined">No Status Set</option>			
		    </select> </td>
		    <td><select name="s_s" id="s_s" style="width:100%" onchange="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,this.form.cert_st.value,this.form.company.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });">
		    <option value="">-Select-</option>
			<option value="User">User</option>
		    <option value="Team Lead">Team Lead</option>
			<option value="Inactive">Inactive</option>
		    </select> </td>
		    <td><select name="r_s" id="r_s" style="width:100%" onchange="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,this.form.cert_st.value,this.form.company.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });">
		    <option value="" selected="selected">-Select-</option>
			<?php 
			$sql_rct="select * from ".EM_RECRUITING_STATUS;
			$result_rct = $this->db->query($sql_rct,__FILE__,__LINE__);
			while($row_rct = $this->db->fetch_array($result_rct))
			{
			?>
		    <option value="<?php echo $row_rct['recruiting_status_id']; ?>" ><?php echo $row_rct['recruiting_status']; ?></option>
			<?php } ?>
		    </select> </td>
		</tr>
		<tr>
		    
			<td>City</td>
		    <td>State</td>
		    <td>Available On</td>
			<td>Available Till</td>
		</tr>
		<tr>
		    
			<td><input name="ct" type="text" id="ct" value="" size="60" onkeyup="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,this.form.cert_st.value,this.form.company.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });" autocomplete='off' /></td>
		    <td><select name="st" id="st" style="width:100%" onchange="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,this.form.cert_st.value,this.form.company.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });">
						<option value="">Select State</option>
						<?php
							$state=file("../state_us.inc");
							foreach($state as $val){
							$state = trim($val);
						?>
						<option value="<?php echo $state;?>"><?php echo $state;?></option>
						<?php
							}
						?>
					 </select>
					 </td>
		    
			
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
									staff.searchAllStaff(document.<?php echo $formName;?>.credential_type.value,document.<?php echo $formName;?>.f_n.value,document.<?php echo $formName;?>.l_n.value,document.<?php echo $formName;?>.e_c.value,document.<?php echo $formName;?>.u_s.value,document.<?php echo $formName;?>.s_s.value,document.<?php echo $formName;?>.r_s.value,document.<?php echo $formName;?>.ct.value,document.<?php echo $formName;?>.st.value,document.<?php echo $formName;?>.zip.value,document.<?php echo $formName;?>.available_on.value,document.<?php echo $formName;?>.available_till.value,document.<?php echo $formName;?>.rad.value,document.<?php echo $formName;?>.cert_st.value, document.<?php echo $formName;?>.company.value,
{onUpdate: function(response,root){ 
document.getElementById('div_search_staff').innerHTML=response;
$('#search_table')
.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
} });
end_cal(this.selection.get()+1);
     			}				
			  });
			  }
			 start_cal();
			</script>	
			<a href="javascript:void(0);" onclick="javascript: document.<?php echo $formName;?>.available_on.value=''; staff.searchAllStaff(document.<?php echo $formName;?>.credential_type.value,document.<?php echo $formName;?>.f_n.value,document.<?php echo $formName;?>.l_n.value,document.<?php echo $formName;?>.e_c.value,document.<?php echo $formName;?>.u_s.value,document.<?php echo $formName;?>.s_s.value,document.<?php echo $formName;?>.r_s.value,document.<?php echo $formName;?>.ct.value,document.<?php echo $formName;?>.st.value,document.<?php echo $formName;?>.zip.value,document.<?php echo $formName;?>.available_on.value,document.<?php echo $formName;?>.available_till.value,document.<?php echo $formName;?>.rad.value,document.<?php echo $formName;?>.cert_st.value, document.<?php echo $formName;?>.company.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} }); return false;"><img src="images/trash.gif" border="0"/></a>			</td>
			
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
									staff.searchAllStaff(document.<?php echo $formName;?>.credential_type.value,document.<?php echo $formName;?>.f_n.value,document.<?php echo $formName;?>.l_n.value,document.<?php echo $formName;?>.e_c.value,document.<?php echo $formName;?>.u_s.value,document.<?php echo $formName;?>.s_s.value,document.<?php echo $formName;?>.r_s.value,document.<?php echo $formName;?>.ct.value,document.<?php echo $formName;?>.st.value,document.<?php echo $formName;?>.zip.value,document.<?php echo $formName;?>.available_on.value,document.<?php echo $formName;?>.available_till.value,document.<?php echo $formName;?>.rad.value,document.<?php echo $formName;?>.cert_st.value, document.<?php echo $formName;?>.company.value,
									{onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });
								}				
			  });
			  }
			
			</script>	
			<a href="javascript:void(0);" onclick="javascript: document.<?php echo $formName;?>.available_till.value=''; staff.searchAllStaff(document.<?php echo $formName;?>.credential_type.value,document.<?php echo $formName;?>.f_n.value,document.<?php echo $formName;?>.l_n.value,document.<?php echo $formName;?>.e_c.value,document.<?php echo $formName;?>.u_s.value,document.<?php echo $formName;?>.s_s.value,document.<?php echo $formName;?>.r_s.value,document.<?php echo $formName;?>.ct.value,document.<?php echo $formName;?>.st.value,document.<?php echo $formName;?>.zip.value,document.<?php echo $formName;?>.available_on.value,document.<?php echo $formName;?>.available_till.value,document.<?php echo $formName;?>.rad.value, document.<?php echo $formName;?>.cert_st.value,   document.<?php echo $formName;?>.company.value, {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	}}); return false;"><img src="images/trash.gif" border="0"/></a>			</td>
		</tr>
		<tr>
		  <td>Company</td>
		  <td>Zip</td>
		  <td>Radius</td>
		  <td>&nbsp;</td>
		  </tr>
		<tr>
			
		  	<td>
			<input name="company" type="text" id="company" value="" size="60" onkeyup="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,this.form.cert_st.value,this.form.company.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });" autocomplete='off' /></td>		
		
			<td><input name="zip" type="text" id="zip" value="" size="60" onkeyup="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.value,this.form.available_on.value,this.form.available_till.value,this.form.rad.value,this.form.cert_st.value,this.form.company.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });" autocomplete='off' /></td>
		  <td><input name="rad" type="text" id="rad" value="" size="60" onkeyup="staff.searchAllStaff(this.form.credential_type.value,this.form.f_n.value,this.form.l_n.value,this.form.e_c.value,this.form.u_s.value,this.form.s_s.value,this.form.r_s.value,this.form.ct.value,this.form.st.value,this.form.zip.value,this.form.available_on.value,this.form.available_till.value,this.value,this.form.cert_st.value,this.form.company.value,  {onUpdate: function(response,root){
					document.getElementById('div_search_staff').innerHTML=response;
					$('#search_table')
		.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[2,0]], headers: { 0:{sorter: false}, 4:{sorter: false}, 7:{sorter: false}}})
			 	} });" autocomplete='off' /></td>
			
			<td>&nbsp;</td>
		  </tr>
		</table>
		
		</form>
		</div>
		<?php
	}

/********************************************************************************************************************************/
	
	
	function searchAllStaff($credential_type='',$first_name='',$last_name='',$event_count='',$user_status='',$system_status='',$rec_status='',$city='',$state='',$zip='',$available_on='',$available_till='',$rad=0,$cert_st='',$company='')
	{
		
		ob_start();
		if($available_on=='') $available_on='0000-01-01';
		if($available_till=='') $available_till='9999-12-31';		

		if($company){
			 $sql_com = "select contact_id from ".TBL_CONTACT." where company_name like '$company%' and type= 'Company'";
			 $result_com=$this->db->query($sql_com,__FILE__,__LINE__);
			 $company_list='-1,';
			 while($row_com = $this->db->fetch_array($result_com)) {
				$company_list .= $row_com[contact_id].',';
			 }
			$company_list = substr($company_list,0,strlen($company_list)-1);
		}
		
		if($zip!='' and $rad!=0){
			$x=0;
			$contact_list = array();
			$contact_string = '0,';
			$row = $this->zip_obj->get_zip_point($zip);
			if($row[lat]){
				$sql = "SELECT e.*,c.lat,c.lon,c.zip_code , (((acos( sin( ( ".$row[lat]." * pi( ) /180 ) ) * sin( (c.lat * pi( )/180 ) ) + cos( ( ".$row[lat]." * pi( ) /180 ) ) * cos( (c.lat * pi( ) /180 )) * cos( ((".$row[lon]." - c.lon) * pi( ) /180 )))) *180 / pi( )) *60 * 1.1515) AS distance FROM ".ZIP_CODE." c,".CONTACT_ADDRESS." e WHERE e.zip = c.zip_code";
				if($rad>0){
					$sql .= " HAVING distance<=$rad";
				} 
				
				$sql .= " ORDER BY distance ASC";
	
				$result = $this->db->query($sql,__FILE__,__LINE__);	
				
				while($row_c = $this->db->fetch_array($result)){
				
					foreach($row_c as $key=>$value){
						$contact_list[$x][$key] = $value;
					}
					$contact_string .= $row_c[contact_id].',';
					$x++;
				}
			}
			$contact_string = substr($contact_string,0,strlen($contact_string)-1);
		}
				
		$sql = "select distinct * from ".TBL_CONTACT." a";
		
		if($credential_type or $cert_type or $cert_st){
			$sql .=" ,".EM_CERTIFICATION." b";
		}
		
		if($user_status or $system_status or $rec_status){
			if($user_status!='Not_defined'){
			$sql .=" ,".EM_CONTACT_STATUS." c";
			}
		}		
		
		if($city or $state or $zip!='' ) {
			$sql .=" ,".CONTACT_ADDRESS." d";
		}
		
		$sql .=" where a.type='People'";
		
		if($user_status or $system_status or $rec_status){
		 	if($user_status!='Not_defined'){
			$sql .=" and a.contact_id=c.contact_id";
			}
		}
		
		if($user_status){
			if($user_status!='Not_defined')
				$sql .= " and c.user_status='$user_status'";
			else
				$sql .= " and a.contact_id not in ( select distinct contact_id from ".EM_CONTACT_STATUS.")";
		}
		
		if($credential_type or $cert_type or $cert_st){
			$sql .=" and a.contact_id=b.contact_id and b.expiration_date>'".date('Y-m-d',time())."'";
		}
		
		
		
		if($city or $state or $zip!='' ) {
			$sql .=" and a.contact_id=d.contact_id";
		}
		
		if($credential_type){
		  
			$sql .= " and b.certification_type_id='$credential_type'";
			
			$sql_ty = "select * from ".EM_CERTIFICATION_TYPE." where certification_id='$credential_type'";
			$result_ty = $this->db->query($sql_ty,__FILE__,__LINE__);
			$row_ty=$this->db->fetch_array($result_ty);
			if($row_ty[credential_type]=='License'){
				if($cert_st){
					$sql .= " and b.state='$cert_st'";
				}				
			}
		}
		
		
		if($first_name){
			$sql .= " and a.first_name like '$first_name%'";
		}
		
		if($last_name){
			$sql .= " and a.last_name like '$last_name%'";
		}
		
		
		
		if($cert_type){
			$sql .= " and b.certification_type_id='$cert_type'";
		}
		
		if($system_status){
			$sql .= " and c.system_status like '$system_status'";
		}
		
		if($rec_status){
			$sql .= " and c.recruitment_status like '$rec_status'";
		}
		
		if($city) {
			$sql .=" and d.city like '$city%'";
		}
		
		if($state) {
			$sql .=" and d.state like '$state%'";
		}
		
		if($zip!='' and $rad==0) {
			$sql .=" and d.zip like '$zip%'";
		}
		
		if($zip!='' and $rad!=0){
			$sql .=" and a.contact_id in (".$contact_string.")";
		}
		
		if($company){
			$sql .=" and a.company in (".$company_list.")";
		}
		$sql .=" limit 0,100";
		
		//	echo '<br>'.$sql;	
		$result = $this->db->query($sql,__FILE__,__LINE__);
		
		
		$contact = array();
		$x=0;
		while($row=$this->db->fetch_array($result)){
			foreach($row as $key=>$value){
				$contact[$x][$key] = $value;
			}
			$sql_staff = "select count(*) as event_count from ".EM_STAFFING." where contact_id='$row[contact_id]' ";
			$result_staff = $this->db->query($sql_staff,__FILE__,__LINE__);	
			$row_staff = $this->db->fetch_array($result_staff);
			$contact[$x]['event_count'] = $row_staff['event_count'];
			$x++;
		}
		if($event_count!=''){
			$contact_temp = array();
			$x=0;
			foreach($contact as $key=>$value){				
				if($value[event_count]>=$event_count){
					$contact_temp[$x] = $value;
					$x++;
				}
			}
			while(count($contact)) array_shift($contact);
			$contact = $contact_temp;
		}
		$contactInRange = array();
		if($zip!='' and $rad!=0){
		/*$x=0;
		$contact_list = array();
		$row = $this->zip_obj->get_zip_point($zip);
		if($row[lat]){
			$sql = "SELECT e.zip,c.lat,c.lon,c.zip_code , (((acos( sin( ( ".$row[lat]." * pi( ) /180 ) ) * sin( (c.lat * pi( )/180 ) ) + cos( ( ".$row[lat]." * pi( ) /180 ) ) * cos( (c.lat * pi( ) /180 )) * cos( ((".$row[lon]." - c.lon) * pi( ) /180 )))) *180 / pi( )) *60 * 1.1515) AS distance FROM ".ZIP_CODE." c,".CONTACT_ADDRESS." e WHERE e.zip = c.zip_code";
			if($rad>0){
				$sql .= " HAVING distance<=$rad";
			} 
			
			$sql .= " ORDER BY distance ASC";

			$result = $this->db->query($sql,__FILE__,__LINE__);	
			
			while($row_c = $this->db->fetch_array($result)){
			
				foreach($row_c as $key=>$value){
					$contact_list[$x][$key] = $value;
				}
				$x++;
			}
			
		}*/

		$x=0;
		foreach($contact as $key=>$value){				
			foreach($contact_list as $key_zip=>$value_zip){
				if($value_zip[zip]==$value[zip]){

					$contactInRange[$x] = $value;
					$contactInRange[$x]['distance'] = $value_zip['distance'];
					$x++;
					break;
				}
			}
		}
			$tmp = array();
			foreach($contactInRange as $ma)
				$tmp[] = $ma['distance'];
			array_multisort($tmp,SORT_ASC,SORT_NUMERIC,$contactInRange);
		} else $contactInRange = $contact;
		
				
				
		?>
		<form action=""  method="post" enctype="multipart/form-data" name="frm_search_all_staff">	
		<table id="search_table" class="event_form small_text" width="100%">
		<thead>
		<tr>
			<th></th>
			<th>ID</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Phone Number</th>
			<th>User Status</th>
			<th>Recruiting Status</th>
			<th>IC</th>
			<th>Email</th>
			<th>Date of Application</th>
			<th>Position Applied For</th>
		</tr>
		</thead>
		<tbody>
		
		<?php	
			$i=0;
			$contacts='0,';
			$contact_processed_array = array();
			foreach($contactInRange as $key=>$value){		
			
				if(!(in_array($value['contact_id'],$contact_processed_array))){
				$flag = "available";
				if($available_on!='0000-01-01' or $available_till!='9999-12-31'){
				if($available_on!='0000-01-01' and $available_till=='9999-12-31'){
					$sql_unavailabledate="select * from ".EM_CONTACT_UNAVAILABILITY." where contact_id='".$value[contact_id]."' and unavailable_date='$available_on'";
				} else
					$sql_unavailabledate="select * from ".EM_CONTACT_UNAVAILABILITY." where contact_id='".$value[contact_id]."' and unavailable_date between '$available_on' and '$available_till'";
				$result_unavailabledate=$this->db->query($sql_unavailabledate,__FILE__,__LINE__);
				
				if($this->db->num_rows($result_unavailabledate)>0)				
				{
					$flag = "unavailable";
				}
				}
				if($flag == "available")
				{
				$i++;
				$contacts .= $value[contact_id].',';
				?>
				
				<tr id="stafflist_<?php echo $value['contact_id'];?>" <?php if($i%2==0) { echo 'class="alt2"'; } ?> >
					 <td><?php if($user_status!='Inactive'){ ?>
					 <input type="radio" name="rd_contact" id="rd_contact" value="<?php echo $value['contact_id'];?>" /><?php } ?>
					 </td>
					 <td><a href="contact_profile.php?contact_id=<?php echo $value['contact_id']; ?>"><?php echo $value['contact_id'];?></a></td>
					 <td><?php echo $value['first_name'];?></td>
					 <td><?php echo $value['last_name'];?></td>
					 <td><?php 
					 $sql_refine = "select * from ".CONTACT_PHONE." where contact_id='".$value[contact_id]."' and type like '%Work%'";
					 $result_refine=$this->db->query($sql_refine,__FILE__,__LINE__);
					 $row_refine = $this->db->fetch_array($result_refine);					 
					 echo $row_refine['number'];?></td>
					 
					 <td><?php 
					 $sql_status = "select a.user_status,b.recruiting_status from ".EM_CONTACT_STATUS." a, ".EM_RECRUITING_STATUS." b where a.contact_id='".$value[contact_id]."' and a.recruitment_status = b.recruiting_status_id ";
					 $result_status=$this->db->query($sql_status,__FILE__,__LINE__);
					 $row_status = $this->db->fetch_array($result_status);		
					 echo $row_status['user_status'];?></td>
					 
					 
					  <td><?php echo $row_status['recruiting_status']; ?></td>
					  
					  <td><?php if($row_status['ic']=='Yes') echo 'Y'; else echo 'N'?></td>
					  
					  <td><?php
					  $sql_rct="select email from ".CONTACT_EMAIL." a, ".EM_STAFFING." b where a.contact_id='".$value['contact_id']."' and a.contact_id = b.contact_id";
					  $result_rct = $this->db->query($sql_rct,__FILE__,__LINE__);
					  $row_rct = $this->db->fetch_array($result_rct);
					  $email = explode('@',$row_rct['email']);
					  if($row_rct['email']) echo $email[0].'<br>@'.$email[1];					  
					  ?></td>
					  
					  <td><?php
					  echo $value['timestamp'];					  
					  ?></td>
					  
					  <td><?php
					  $sql_rct="select position_applied from ".EM_APPLICATION_GENERAL." where contact_id='".$value['contact_id']."'";
					  $result_rct = $this->db->query($sql_rct,__FILE__,__LINE__);
					  $row_rct = $this->db->fetch_array($result_rct);
					  echo $row_rct['position_applied'];					  
					  ?></td>
					  
				</tr>
				<?php
				$contact_processed_array[] = $value['contact_id'];
				 }
				 }
				}
			$contacts = substr($contacts,0,strlen($contacts)-1);	
			if($i==0){?>
				<tr><td colspan="11" align="center">no result </td></tr>
			<?php
			}
			else { ?>
			<tr><td colspan="11" align="right"><a href="javascript:void(0);" onclick="javascript: staff.emailToAll('local','<?php echo $contacts;?>','staff',
													{ preloader: 'prl',
													onUpdate: function(response,root){
													 document.getElementById('div_event').innerHTML=response;
													 document.getElementById('div_event').style.display='';
													 }});">Email To Contacts</a></td>
			</tr>
			<?php }	?>
		</tbody>		
		</table>
		</form>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	
	
/********************************************************************************************************************************/


}

?>