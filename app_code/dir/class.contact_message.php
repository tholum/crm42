<?php
class Contact_Message
{
	var $db;
	var $event_id;
	var $contact_id;
	
	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	}
	
	function setEventId($event_id)
	{
		$this->event_id = $event_id;
	}
	
	function setContactId($contact_id)
	{
		$this->contact_id = $contact_id;
	}

	function GetNewsforContact($user_name,$limit=''){
		$sql = "SELECT * FROM ".TBL_MESSAGE_INBOX." WHERE user_id = '$user_name' order by timestamp desc";
		if($limit)
			$sql .= " limit 0,".$limit;
		$record = $this->db->query($sql,__FILE__,__LINE__);
		?>
		<table width="100%" >
		<?php
		if($this->db->num_rows($record)>0){
			while($row = $this->db->fetch_array($record)){
		    	$sql2 = "SELECT * FROM ".TBL_MESSAGE_OUTBOX." WHERE omessage_id = '$row[omessage_id]'";
				$record2 = $this->db->query($sql2,__FILE__,__LINE__);
				$row2 = $this->db->fetch_array($record2);
				?>
		    <tr>
			  <td <?php if($limit==''){ ?>class="head"<?php } ?>><a href="readMessage.php?height=480&width=480&imessage_id=<?php echo $row['imessage_id'] ?>&omessage_id=<?php echo $row2['omessage_id'] ?>&index=News" class="thickbox" title="News Details"><?php echo $row2['subject'] ?></a></td>
			</tr>
		    <tr>
			  <td><?php echo substr(strip_tags($row2['message']),0,100).'...'; ?></td>
			</tr>
		    <tr>
			  <td><?php  echo date('m.d.Y',$row2['timestamp']); ?></td>
			</tr>
				<?php
				
			}
			?>
		<tr>
		  <td ><?php if($limit){ ?><br /><a href="news_all.php">more &gt;&gt;</a><?php } ?></td>
		</tr>
		<?php
		} else {
			?>
			<tr>
				<th >No news !!</th>
			</tr>
			<?php
		}
		?>
		</table>		
		<?php
	}
	
	function GetUpdatesforContact($user_name,$limit=''){
		$sql = "SELECT * FROM ".TBL_MESSAGE_INBOX." WHERE user_id = '$user_name' order by timestamp desc";
		if($limit)
			$sql .= " limit 0,".$limit;
		$record = $this->db->query($sql,__FILE__,__LINE__);
		?>
		<table width="100%" >
		<?php
		if($this->db->num_rows($record)>0){
			while($row = $this->db->fetch_array($record)){
		    	$sql2 = "SELECT * FROM ".TBL_MESSAGE_OUTBOX." WHERE omessage_id = '$row[omessage_id]'";
				$record2 = $this->db->query($sql2,__FILE__,__LINE__);
				$row2 = $this->db->fetch_array($record2);
				?>
		    <tr>
			  <td <?php if($limit==''){ ?>class="head"<?php } ?>><?php  echo date('m.d.Y',$row2['timestamp']); ?></td>
			</tr>
		    <tr>
			  <td><a href="readMessage.php?height=480&width=480&imessage_id=<?php echo $row['imessage_id'] ?>&omessage_id=<?php echo $row2['omessage_id'] ?>&index=Update" class="thickbox" title="Upload Details"><?php echo substr(strip_tags($row2['message']),0,100).'...'; ?></a></td>
			</tr>
				<?php
				
			}
			?>
		<tr>
		  <td><?php if($limit!=''){ ?><br /><a href="updates_all.php">more &gt;&gt;</a><?php } ?></td>
		</tr>
		<?php
		} else {
			?>
			<tr>
				<th>No updates !!</th>
			</tr>
			<?php
		}
		?>
		</table>		
		<?php
	}

	function ReadMessageInboxUpdate($message_id,$imessage_id,$att='',$user_id){
		$sql_inbox = "SELECT * FROM ".TBL_MESSAGE_INBOX." b WHERE b.imessage_id='$imessage_id'";
		$record_inbox = $this->db->query($sql_inbox,__FILE__,__LINE__);
		$row_inbox = $this->db->fetch_array($record_inbox);
		$sql = "SELECT * FROM ".TBL_MESSAGE_OUTBOX." a WHERE a.omessage_id = '$row_inbox[omessage_id]' ";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		$row = $this->db->fetch_array($record);
		$FormName = "frm_readMessageInbox";
		?>
		<form name="<?php echo $FormName?>" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?index=Reply" enctype="multipart/form-data">
		<input type="hidden" name="omessage_id" id="omessage_id" value="<?php echo $message_id ?>" />
		<input type="hidden" name="imessage_id" id="imessage_id" value="<?php echo $imessage_id ?>" />
		<div class="head"><h2>Update Detail Screen</h2> </div>
		<div class="head_border">
		<table width="100%" >
		    <tr>
			  <td colspan="2" class="head"><h2>Update Details</h2></td>
			</tr>
			<tr>
			  <td>Update Date:</td>
			  <td><?php  echo date('m.d.Y',$row['timestamp']); ?></td>
			</tr>
		    <tr>
			  <td colspan="2">Update Description</td>
			</tr>
		    <tr>
			  <td colspan="2"><?php echo $row[message]?></td>
			</tr>
		    <tr>
			  <td colspan="2"><p><?php $att->attachmentList($row['omessage_id'],$user_id,'admin/uploads/');?></p></td>
			</tr>
		</table>
		</div>
		</form>
		<?php
	}

	function ReadMessageInboxNews($message_id,$imessage_id,$att='',$user_id){
	
		$sql_inbox = "SELECT * FROM ".TBL_MESSAGE_INBOX." b WHERE b.imessage_id='$imessage_id'";
		$record_inbox = $this->db->query($sql_inbox,__FILE__,__LINE__);
		$row_inbox = $this->db->fetch_array($record_inbox);
		$sql = "SELECT * FROM ".TBL_MESSAGE_OUTBOX." a WHERE a.omessage_id = '$row_inbox[omessage_id]' ";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		$row = $this->db->fetch_array($record);
		$FormName = "frm_readMessageInbox";
		?>
		<form name="<?php echo $FormName?>" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?index=Reply" enctype="multipart/form-data">
		<input type="hidden" name="omessage_id" id="omessage_id" value="<?php echo $message_id ?>" />
		<input type="hidden" name="imessage_id" id="imessage_id" value="<?php echo $imessage_id ?>" />
		<div class="head">News Detail Screen </div>
		<div class="head_border">
		<table width="100%" >
		    <tr>
			  <td colspan="2" class="head">News Details</td>
			</tr>
			<tr>
			  <td>News  Date:</td>
			  <td><?php  echo date('m.d.Y',$row['timestamp']); ?></td>
			</tr>
		    <tr>
			  <td>News Title:</td>
			  <td><?php echo $row[subject]?></td>
			</tr>
		    <tr>
			  <td colspan="2"><p><?php $att->attachmentList($row['omessage_id'],$user_id,'admin/uploads/');?></p></td>
			</tr>
		    <tr>
			  <td colspan="2">News Description:</td>
			</tr>
		    <tr>
			  <td colspan="2"><?php echo $row[message]?></td>
			</tr>
		</table>
		</div>
		</form>
		<?php
	}	
	
	function getEvents($contact_id)
	{
		$sql = "SELECT * FROM ".EM_STAFFING." WHERE contact_id = '$contact_id' ";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		$curr_date = explode('-',date('Y-m-d',time()));
		$past_3_date = date('Y-m-d',mktime(0,0,0,$curr_date[1],$curr_date[2]-10,$curr_date[0]));
		?>
		<table width="100%">
		<tr>
		<td>GEID</td>
		<td>City</td>
		<td>St</td>
		<td>Start Day</td>
		<td>End Day</td>
		</tr>
		<?php
		while($row = $this->db->fetch_array($record)){
			//$event_end_date = $this->end_date($row['event_id']);
			
			$sql_eve= "select * from ".EM_EVENT." a,".EM_DATE." b where a.event_id='".$row['event_id']."' and a.event_id=b.event_id and '".$this->end_date($row[event_id])."'>'".$past_3_date."'";
			$result_eve= $this->db->query($sql_eve,__FILE__,__LINE__);
			
			if($this->db->num_rows($result_eve)>0){
			$row_eve = $this->db->fetch_array($result_eve);		
			?>		
			<tr>
			<td><a href="event_detail.php?event_id=<?php echo $row['event_id'] ?>&contact_id=<?php echo $contact_id ?>"><?php echo $row_eve[group_event_id]; ?></a></td>
			<td><a href="event_detail.php?event_id=<?php echo $row['event_id'] ?>&contact_id=<?php echo $contact_id ?>"><?php echo $row_eve[city]; ?></a></td>
			<td><?php echo $row_eve[state]; ?></td>
			<td><?php echo $this->start_date($row[event_id]); ?></td>
			<td><?php echo $this->end_date($row[event_id]); ?></td>
			</tr>				
			<?php 
		}
		}
		?>
		</table>
		<?php
	}

	function start_date($event_id) {		
		$sql = "select * from ".EM_DATE." where event_id= ".$event_id." order by  event_date asc limit 1";
		$result = $this->db->query($sql,__FILE__,__LINE__);			
		$row = $this->db->fetch_array($result);		
		$start_date=$row['event_date'];		
		return $start_date;		
	}

	function end_date($event_id) {		
		$sql = "select * from ".EM_DATE." where event_id= ".$event_id." order by  event_date desc limit 1";
		$result = $this->db->query($sql,__FILE__,__LINE__);			
		$row = $this->db->fetch_array($result);		
		$end_date=$row['event_date'];		
		return $end_date;		
	}

	function start_time($event_id) {		
		$sql = "select * from ".EM_DATE." where event_id= ".$event_id." order by  event_date asc limit 1";
		$result = $this->db->query($sql,__FILE__,__LINE__);			
		$row = $this->db->fetch_array($result);		
		$start_time=$row['start_time'];		
		return $start_time;		
	}

	function end_time($event_id) {		
		$sql = "select * from ".EM_DATE." where event_id= ".$event_id." order by  event_date asc limit 1";
		$result = $this->db->query($sql,__FILE__,__LINE__);			
		$row = $this->db->fetch_array($result);		
		$end_time=$row['end_time'];		
		return $end_time;		
	}

	function getEventsDetails($event_id,$contact_id)
	{
		$sql = "select * from ".EM_EVENT." where event_id= '".$event_id."'";
		$result = $this->db->query($sql,__FILE__,__LINE__);			
		$row = $this->db->fetch_array($result);	
		
		$s_date = explode('-',$this->start_date($event_id));
		$e_date = explode('-',$this->end_date($event_id));
	?>
	<table width="100%">
	  <tr>
	    <td>
		  <table width="100%" class="head_border">
		    <tr>
			  <td colspan="4" class="head"><h2>Event Details</h2></td>
			</tr>
		    <tr>
			  <td>GE ID:</td>
			  <td><?php echo $row[group_event_id] ?></td>
			  <td>Location:</td>
			  <td><?php echo $row[city].', '.$row[state] ?></td>
			</tr>
		    <tr>
			  <td colspan="2">Dates:</td>
			  <td colspan="2"><?php
			    $sql_dt = "select * from ".EM_DATE." where event_id='".$event_id."'";
				$result_dt = $this->db->query($sql_dt,__FILE__,__lINE__);
				while($row_dt=$this->db->fetch_array($result_dt)) {
					$s_date = explode("-",$row_dt['event_date']);	
					echo date("D jS M " ,mktime(0, 0, 0, $s_date[1], $s_date[2], $s_date[0]));
					echo $row_dt['start_time']." - ".$row_dt['end_time'];
					echo "<br>";
				}
			  ?></td>
			</tr>
		    <tr>
			  <td colspan="2">Times:</td>
			  <td colspan="2">Call Team Leads for details On Call #: 608-443-8109</td>
			</tr>
			<tr>
			  <td valign="top" colspan="2">Hours of operation</td>
			  <td colspan="2">: <?php echo "Hours are approximately ".$this->start_time($event_id)." to ".$this->end_time($event_id).'<br><br>'; ?></td>
			</tr>
			<tr>
			  <td valign="top" colspan="2">Services</td>
			  <td colspan="2">:<?php
			  $sql_service = "select * from ".EM_SERVICES." where event_id='".$event_id."'";
			  $result_service = $this->db->query($sql_service,__FILE__,__LINE__);	
			  while($row_service=$this->db->fetch_array($result_service)){
			  		$sql_ser = "select * from ".EM_SERVICES_TYPE." where services_id='".$row_service['service_type']."'";
			  		$result_ser = $this->db->query($sql_ser,__FILE__,__LINE__);
					$row_ser=$this->db->fetch_array($result_ser);
					echo ' Type:'.$row_ser['services_type'].'<br> Qty. Requested:'.$row_service['quantity_requested'].'<br> Service Code:'.$row_service['service_code'].'<br><br>'; 	
			  }
			  ?>
			  </td>
			 </tr>
			 
			 <tr>
			  <td valign="top" colspan="2">Location</td>
			  <td colspan="2">:<?php echo $row[customer_name].'<br>'.$row[address_name].';<br>'.$row[street_name_1].' '.$row[street_name_2].';<br>'.$row[city].';<br>'.$row[state].'-'.$row[zip] ?></td>
			</tr>
			
		  </table>
		</td>
	  </tr>
	  <tr>
	    <td>
		  <table width="100%" class="head_border">
		    <tr>
			  <td class="head" ><h2>Event Documents</h2></td>
			</tr>
			<?php 
			$sql_st = "select * from ".EM_CONTACT_STATUS." where contact_id='$contact_id'";
			$result_st = $this->db->query($sql_st,__FILE__,__LINE__);	
			$row_st=$this->db->fetch_array($result_st);
			
			if($row_st[system_status]=='User'){
				$sql_doc = "select * from ".EM_DOCUMENTS." where event_id = '".$event_id."' and document_status='User'";
			}
			if($row_st[system_status]=='Team Lead'){
				$sql_doc = "select * from ".EM_DOCUMENTS." where event_id = '".$event_id."' and (document_status='User' or document_status='Team Lead')";
			}
			
			$result_doc = $this->db->query($sql_doc,__FILE__,__LINE__);	
			while($row_doc=$this->db->fetch_array($result_doc)) {?>
					<tr>
					  <td>&nbsp;</td>
					</tr>
					<tr>
					<td ><a href="admin/uploads/<?php echo $row_doc[document_server_name]; ?>" target="_blank"> <?php echo $row_doc[document_name]; ?> </a></td>
					</tr>
			<?php } ?>
			<tr>
			  <td>&nbsp;</td>
			</tr>
		  </table>
		</td>
	  </tr>
	  <tr>
	    <td>
		  <table width="100%" class="head_border">
		    <tr>
			  <td class="head" colspan="2"><h2>Event Staff</h2></td>
			</tr>
			  <?php
			  $sql_stt = "select * from ".EM_STAFFING." a,".EM_CERTIFICATION_TYPE." b where a.event_id='$event_id' and a.type=b.certification_id and a.contact_id";
			  if($row_st[system_status]=='User'){
			    $sql_stt .= " and a.contact_id in (select contact_id from ".EM_CONTACT_STATUS." where system_status='Team Lead') ";
			  }
			  $result_stt = $this->db->query($sql_stt,__FILE__,__LINE__);				  
			  while($row_stt=$this->db->fetch_array($result_stt)){
			  	$sql_det = "select * from ".TBL_CONTACT." where contact_id='$row_stt[contact_id]'";
				$result_det = $this->db->query($sql_det,__FILE__,__LINE__);				  
				$row_det=$this->db->fetch_array($result_det);
				?>
		    <tr>
			  <td width="55%" valign="top"><div><span style="color:#0066FF"><?php echo $row_stt[cert_type].':'; ?></span> <?php echo $row_det[first_name].' '.$row_det[last_name];?></div>
			  <div class="small_text gray"><?php echo $row_stt[notes]; ?> </div></td>
			    <?php
				$sql_ph = "select * from ".CONTACT_PHONE."  where contact_id='$row_stt[contact_id]'";
				$result_ph = $this->db->query($sql_ph,__FILE__,__LINE__);				  
				?><td width="45%"><?php
				while($row_ph=$this->db->fetch_array($result_ph)){				  
				  echo '('.substr($row_ph[number], 0, 3).')'.substr($row_ph[number], 3, 3).'-'.substr($row_ph[number], 6, 4).' ('.$row_ph[type].')<br>';
				}
			   ?><br /></td>			   
			</tr>				
				<?php
			  }
			   ?>
		  </table>
		</td>
	  </tr>
	  <tr>
	    <td>
		  <table width="100%" class="head_border">
		    <tr>
			  <td class="head"><h2>Event Notes</h2></td>
			</tr>
		    <tr>
			  <td><?php echo $row[note]; ?></td>
			</tr>
		  </table>
		</td>
	  </tr>
	  <tr>
	    <td>
		  <table width="100%" class="head_border">
		    <tr>
			  <td colspan="2" class="head"><h2>Itinerary Document</h2></td>
			</tr>
			<?php
			$sql_st = "select * from ".TBL_CONTACT." a,".EM_CONTACT_STATUS." b where b.contact_id='$contact_id' and a.contact_id=b.contact_id and b.user_status='Active'";
			$result_st = $this->db->query($sql_st,__FILE__,__LINE__);	
			$row_st=$this->db->fetch_array($result_st);
			if($row_st[system_status]=='Team Lead'){
					?>
					<tr>
					  <td valign="top">POC Name and Number</td>
					  <td>: <?php
					  $sql_poc = "select * from ".EM_POC." where event_id='$event_id'";
					  $result_poc = $this->db->query($sql_poc,__FILE__,__LINE__);	
					  $row_poc=$this->db->fetch_array($result_poc);
					  echo $row_poc[first_name].' '.$row_poc[last_name].'<br>'.$row_poc[phone].'<br>'.$row_poc[cell].'<br>'.$row_poc[email].'<br><br>';
					  ?></td>
					</tr>
					<tr>
					  <td valign="top">Alternate POC</td>
					  <td>: <?php
					  $row_poc=$this->db->fetch_array($result_poc);
					  echo $row_poc[first_name].' '.$row_poc[last_name].'<br>'.$row_poc[phone].'<br>'.$row_poc[cell].'<br>'.$row_poc[email].'<br><br>';
					  ?></td>
					</tr>
					<?php } ?>
					
			<tr>
			  <td valign="top">Security Protocol</td>
			  <td>: ID <br /><br /></td>
			</tr>
			
					
			<!--<tr>
			  <td>Team Lead</td>
			  <td>: 
			  <?php
			  $sql_staf = "select * from ".EM_STAFFING." a,".TBL_CONTACT." b,".CONTACT_PHONE." c where a.event_id='$event_id' and a.contact_id=b.contact_id and a.contact_id=c.contact_id and c.type='Work' and a.contact_id in (select contact_id from ".EM_CONTACT_STATUS." where system_status='Team Lead')";
			  $result_staf = $this->db->query($sql_staf,__FILE__,__LINE__);	
			  			  
			  while($row_staf=$this->db->fetch_array($result_staf)){
			  	echo $row_staf[first_name].' '.$row_staf[last_name].'/'.$row_staf[number].'<br><br>';
			  }
			  ?>
			  </td>
			</tr>-->
			
			<tr>
			  <td valign="top">Rental Car <?php echo $i; ?></td>
			  <td>:			
			 <?php
			 $sql_rent = "select * from ".EM_RENTAL." where event_id='$event_id'";
			 $result_rent = $this->db->query($sql_rent,__FILE__,__LINE__);	
			 while($row_rent=$this->db->fetch_array($result_rent)) { 
			  echo $row_rent[name].'<br>'.$row_rent[address1].' '.$row_rent[address2].'<br>'.$row_rent[city].', '.$row_rent[state].', '.$row_rent[zip].'<br>'.$row_rent[phone].'<br>'.'Confirmation: '.$row_rent[confirmation_code].'<br><br>';
			   } ?></td>
			</tr>
			
			<tr>
			  <td valign="top">Hotel <?php echo $i; ?></td>
			  <td>: <?php
			  $sql_hotel = "select * from ".EM_HOTEL." a,".EM_HOTEL_STAY." b where b.event_id='$event_id' and a.hotel_id=b.hotel_id";
			  $result_hotel = $this->db->query($sql_hotel,__FILE__,__LINE__);	
			  while($row_hotel=$this->db->fetch_array($result_hotel)) { 			  
			  echo $row_hotel[name].'<br>'.$row_hotel[address1].' '.$row_hotel[address2].'<br>'.$row_hotel[city].', '.$row_hotel[state].', '.$row_hotel[zip].'<br>'.$row_hotel[phone].'<br>'.'Confirmation: '.$row_hotel[confirmation_no].'<br><br>';
			  } ?></td>
			</tr>
			<?php
			if($row_st[system_status]=='Team Lead'){
				?>
				<tr>
				  <td valign="top">Equipments:</td>
				  <td>: <?php
				  $sql_equip = "select * from ".EM_EVENT_EQUIPMENT." a,".EM_EQUIPMENT." b,".EM_EQUIPMENT_CATEGORY." c where a.equipment_id=b.equipment_id and a.event_id='$event_id' and b.equipment_category=c.category_id";
				  $result_equip = $this->db->query($sql_equip,__FILE__,__LINE__);	
				  while($row_equip=$this->db->fetch_array($result_equip)){
					echo $row_equip[equipment_name].'<br>Category: '.$row_equip[category_name].'<br><br>';
				  }
				  ?></td>
				</tr>				
				<tr>
				  <td valign="top">Shipping</td>
				  <td>:<?php
				  $sql_ship = "select * from ".EM_SHIPPING." a,".EM_EQUIPMENT." b where a.event_id='$event_id' and b.equipment_id=a.equipment_id";
				  $result_ship = $this->db->query($sql_ship,__FILE__,__LINE__);	
				  while($row_ship=$this->db->fetch_array($result_ship)){
					
					$sql_dt = "select unavailability_date from ".EM_EQUIPMENT_AVAILABILITY." where event_id='$event_id' order by unavailability_date asc limit 1";
					$result_dt = $this->db->query($sql_dt,__FILE__,__LINE__);
					$row_dt=$this->db->fetch_array($result_dt);
						
					echo $row_ship[equipment_name].'<br>To: '.$row_ship[to].'<br>Tracking Number: '.$row_ship[tracking_number].'<br>Shipping Vendor: '.$row_ship[shipping_vendor].'<br>Shipping Date:'.$row_dt['unavailability_date'].'<br>'.$row_ship[address1].' '.$row_ship[address2].'<br>'.$row_ship[city].', '.$row_ship[state].', '.$row_ship[zip].'<br>'.$row_ship[phone].'<br>Note: '.$row_ship[note].'<br><br>';
				  }
				  ?></td>
				</tr>
				<?php }?>
			
		  </table>
		</td>
	  </tr>
	</table>
	<?php
	}
	
	function getMonthName($mon)
	{
		switch($mon){
			case '01':
				return 'Jan';
				break;
			case '02':
				return 'Feb';
				break;
			case '03':
				return 'Mar';
				break;
			case '04':
				return 'Apr';
				break;
			case '05':
				return 'May';
				break;
			case '06':
				return 'Jun';
				break;
			case '07':
				return 'Jul';
				break;
			case '08':
				return 'Aug';
				break;
			case '09':
				return 'Sep';
				break;
			case '10':
				return 'Oct';
				break;
			case '11':
				return 'Nov';
				break;
			case '12':
				return 'Dec';
				break;
		}
	}
	
	function getPhoneNoLink($contact_id)
	{
		$sql_st = "select * from ".TBL_CONTACT." a,".EM_CONTACT_STATUS." b where b.contact_id='$contact_id' and a.contact_id=b.contact_id";
		$result_st = $this->db->query($sql_st,__FILE__,__LINE__);	
		$row_st=$this->db->fetch_array($result_st);
		/*if($row_st[system_status]=='User'){
		?>		
		<div class="head"><a href="phoneNumber.php?height=480&width=480&contact_id=<?php echo $contact_id ?>" class="thickbox" title="Team Lead Phone Number list">Team Lead Phone Number list</a></div>
		<?php
		}*/
		if($row_st[system_status]=='Team Lead'){
		?>
		<div class="head"><a href="phoneNumber.php?height=480&width=480&contact_id=<?php echo $contact_id ?>" class="thickbox" title="User Phone Number list">User Phone Number list</a></div>
		<?php
		}
	}
	
	function getTeamLeadPhoneNoList($contact_id){
		$sql_st = "select * from ".TBL_CONTACT." a,".EM_CONTACT_STATUS." b where b.contact_id='$contact_id' and a.contact_id=b.contact_id";
		//echo $sql_st;
		$result_st = $this->db->query($sql_st,__FILE__,__LINE__);	
		$row_st=$this->db->fetch_array($result_st);
		
		if($row_st[system_status]=='User'){
			$sql_con = "select * from ".TBL_CONTACT." a,".EM_CONTACT_STATUS." b where a.contact_id=b.contact_id and b.system_status='Team Lead' and a.type='People' and b.user_status='Active' order by a.last_name,a.first_name asc ";
			$result_con = $this->db->query($sql_con,__FILE__,__LINE__);	
			$n=1;
			?>
			<table width="100%" >
			  <tr class="head">
				<th>#</th>
				<th>Name</th>
				<th>Phone</th>
			  </tr>
			  <tr>
				<td valign="top" align="center"><?php echo $n++; ?></td>
				<td valign="top" align="center"><?php echo $row_st[first_name].' '.$row_st[last_name]; ?></td>
				<td valign="top" align="center"><?php
				$sql_det_con = "select * from ".CONTACT_PHONE." where contact_id='$row_st[contact_id]' ";			  
				$result_det_con = $this->db->query($sql_det_con,__FILE__,__LINE__);	
				while($row_det_con=$this->db->fetch_array($result_det_con)){		
				 echo $row_det_con[number].' ('.$row_det_con[type].')<br>'; 
				 } ?></td>
			  </tr>
			  <?php 
				while($row_con=$this->db->fetch_array($result_con)){	
			  ?>
			  <tr>
			    <td valign="top" align="center"><?php echo $n++; ?></td>
				<td valign="top" align="center"><?php echo $row_con[first_name].' '.$row_con[last_name]; ?></td>
				<td valign="top" align="center"><?php
				$sql_det = "select * from ".CONTACT_PHONE." where contact_id='$row_con[contact_id]'";			  
				$result_det = $this->db->query($sql_det,__FILE__,__LINE__);	
				while($row_det=$this->db->fetch_array($result_det)){		
				 echo $row_det[number].' ('.$row_det[type].')<br>'; 
				 } ?></td>
			  </tr>
			  <?php } ?>
			</table>
			<?php
		}
		if($row_st[system_status]=='Team Lead'){
			$sql_con = "select * from ".TBL_CONTACT." a,".EM_CONTACT_STATUS." b where a.contact_id=b.contact_id and b.system_status='User' and a.type='People' and b.user_status='Active' order by a.last_name,a.first_name asc" ;
			$result_con = $this->db->query($sql_con,__FILE__,__LINE__);	
			$n=1;
			?>
			<table width="100%" >
			  <tr class="head">
				<th>#</th>
				<th>Name</th>
				<th>Phone</th>
			  </tr>
			  <?php 
				while($row_con=$this->db->fetch_array($result_con)){	
			  ?>
			  <tr>
			    <td valign="top" align="center"><?php echo $n++; ?></td>
				<td valign="top" align="center"><?php echo $row_con[first_name].' '.$row_con[last_name]; ?></td>
				<td valign="top" align="center"><?php
				$sql_det = "select * from ".CONTACT_PHONE." where contact_id='$row_con[contact_id]'";			  
				$result_det = $this->db->query($sql_det,__FILE__,__LINE__);	
				while($row_det=$this->db->fetch_array($result_det)){		
				 echo $row_det[number].' ('.$row_det[type].')<br>'; 
				 } ?></td>
			  </tr>
			  <?php } ?>
			</table>
			<?php
		}
	}
	
	function getNewUpdates($contact_id,$user_name)
	{
		
		$sql_login = "SELECT * FROM ".EM_WEB_APP_INFO." WHERE contact_id = '$contact_id' ";
		$result_login = $this->db->query($sql_login,__FILE__,__LINE__);
		$row_login = $this->db->fetch_array($result_login);
		if($row_login[last_accessed]){
		$sql = "SELECT * FROM ".TBL_MESSAGE_INBOX." WHERE user_id = '$user_name' and timestamp>$row_login[last_accessed] order by timestamp desc";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		?>
		<table width="100%" >
		<?php
		if($this->db->num_rows($record)>0){
			while($row = $this->db->fetch_array($record)){
		    	$sql2 = "SELECT * FROM ".TBL_MESSAGE_OUTBOX." WHERE omessage_id = '$row[omessage_id]'";
				$record2 = $this->db->query($sql2,__FILE__,__LINE__);
				$row2 = $this->db->fetch_array($record2);
				?>
		    <tr>
			  <td class="head"><?php  echo date('m.d.Y',$row2['timestamp']); ?></td>
			</tr>
		    <tr>
			  <td><a href="readMessage.php?height=480&width=480&imessage_id=<?php echo $row['imessage_id'] ?>&omessage_id=<?php echo $row2['omessage_id'] ?>&index=Update" class="thickbox" title="Upload Details"><?php echo substr($row2['message'],0,125).'...'; ?></a></td>
			</tr>
				<?php
				
			}
			?>
		<tr>
		  <td><?php if($limit!=''){ ?><br /><a href="updates_all.php">more &gt;&gt;</a><?php } ?></td>
		</tr>
		<?php
		} else {
			?>
			<tr>
				<th>No updates !!</th>
			</tr>
			<?php
		}
		?>
		</table>
		<?php
		} else echo "No updates !!";
	}
	
	function isTeamLead($contact_id)
	{
		$sql_st = "select * from ".TBL_CONTACT." a,".EM_CONTACT_STATUS." b where b.contact_id='$contact_id' and a.contact_id=b.contact_id";
		$result_st = $this->db->query($sql_st,__FILE__,__LINE__);	
		$row_st=$this->db->fetch_array($result_st);
		if($row_st[system_status]=='Team Lead')
			return true;
		else return false;
	}
	
	function isNewUpdate($contact_id,$user_name)
	{
		$sql_login = "SELECT * FROM ".EM_WEB_APP_INFO." WHERE contact_id = '$contact_id' ";
		$result_login = $this->db->query($sql_login,__FILE__,__LINE__);
		$row_login = $this->db->fetch_array($result_login);
		if($row_login[last_accessed]){
		$sql = "SELECT * FROM ".TBL_MESSAGE_INBOX." WHERE user_id = '$user_name' and timestamp>$row_login[last_accessed] order by timestamp desc";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0){
			return true;
		} else {
			return false;
		}
		} else return false;
	}
	
	function isEventAssigned($event_id,$contact_id)
	{
		$sql = "SELECT * FROM ".EM_STAFFING."  WHERE contact_id = '$contact_id' and event_id='$event_id'";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		return $this->db->num_rows($record);
	}
	
	function updateLastAccess($contact_id){
		$sql_login="select * from ".EM_WEB_APP_INFO." where contact_id='$contact_id'";
		$result_login=$this->db->query($sql_login,__FILE__,__LINE__);
		$row_login=$this->db->fetch_array($result_login);
		
		$update_sql_array = array();
		$update_sql_array[last_accessed] = $row_login[last_login];
		$this->db->update(EM_WEB_APP_INFO,$update_sql_array,'contact_id',$contact_id);
	}
	
}
?>