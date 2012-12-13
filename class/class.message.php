<?php
/***********************************************************************************

			Class Discription : Message
								This module deals with Messaging system in which user can send, view inbox or recived messages, view outbox or send 
								messages, reply to inbox messages, reply to outbox messages and delete inbox and outbox messages. 
			
			Class Memeber Functions : SendMessage($runat)
									  GetInbox($user_name)
									  GetOutbox($user_id)
									  ReadMessageInbox($message_id,$imessage_id)
									  ReadReplyMessageInbox($message_id)
									  DeleteMessageInbox($imessage_id)
									  Reply($runat,$omessage_id,$reply_index)
									  ReadMessageOutbox($message_id)
									  ReadReplyMessageOutbox($message_id)
									  GetRecentMessages($user_name,$limit)
									  GetReplyToAll()
									  SendToUser($user_name)
									  SaveMessageToOutbox()
									  SaveMessageToInbox()
									  SaveMessageToSentTo()
									  InsertReplyToMessageId()
									  SendToMultiple($user_array)
									  isValidUserArray()
			
			
			Describe Function of Each Memeber function :
			
        							  1. function SendMessage($runat)   //$runat(local/server)
									  		his function sends the message to the 
									  
									  
									  2. function GetInbox($user_name)
									  		displays all the messages in the inbox
									  
									  
									  3. function GetOutbox($user_id)
									  		displays all the messages send by user.
									  
									  
									  4. function ReadMessageInbox($message_id,$imessage_id) // $message_id= message_id of the message selected from list 
									  															$imessage_id= inbox message id  
									  		this function display the whole message of the selected message in the inbox list. 
									  
									  
									  5. function ReadReplyMessageInbox($message_id)
									  		this function shows all the messages send by the user by the reply to his inbox messages .
											these messages are shown with the inbox message to which he/she has replied. 
									  
									  
									  6. function DeleteMessageInbox($imessage_id)
									  		this function delete the selected message from the inbox.
									  
									  
									  7. function Reply($runat,$omessage_id,$reply_index)
									  		this function user can reply to one person or reply to all or reply to 
											outbox.
									  
									  
									  8. function ReadMessageOutbox($message_id)
									  		this function display the whole outbox message selected. the message_id is passed to view the 
											selected message.
									  
									  
									  9. function ReadReplyMessageOutbox($message_id)
									  		this function displays all the outbox replied messages.
											
												
									  10. function GetRecentMessages($user_name,$limit) // $user_name= user_name of the person loged in
									  													//	$limit= limit of the message to be shown 	 
									  		this function displays the recent messages of the user on the main page. The user_name of the person 
											is passed whom messages are to be displayed. The limit of the number of messages to show can also be
											set.
											 



************************************************************************************/

class Message
{
	var $user_id;
	var $subject;
	var $message;
	var $imessage_id;
	var $omessage_id;
	var $to;
	var $sent_id;
	var $email_id;
	var $user_name;
	var $send_to_text;
	var $limit;
    var $mail_obj;
	var $db;
	var $validity;
	var $Form;
	var $user;
	var $user_array = array();

	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
		$this->mail_obj = new PHPMailer();
		$this->user = new User();
	}
	
	
	
	function SendMessage($runat,$att){
		switch($runat){
		    case 'local' :
						  if(count($_POST)>0 and $_POST['go']=='Go'){
						      extract($_POST);
						      $this->to = $to;
						      $this->subject = $subject;
						      $this->message = $message;
						  }
						  $FormName = "frm_send_message";
						  $ControlNames=array("to"			=>array('to',"OR","One of Users or Groups field is required","span_to_send_message","to_group"),
											"subject"			=>array('subject',"''","Subject field is empty","span_subject_send_message"),
											"message"			=>array('message',"''","Message field is empty","span_message_send_message")
											);

						  $ValidationFunctionName="Validator_send_message";
					
						  $JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						  echo $JsCodeForFormValidation;
						  ?>
						  
						  <form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName;?>" >
						  <table width="90%" class="news">
						  		<tr>
									<td colspan="2" align="right">
										  <ul>
											<li><span id="span_to_send_message"></span></li>
											<li><span id="span_subject_send_message"></span></li>
											<li><span id="span_message_send_message"></span></li>
										  </ul>									 </td>
							  </tr>
							 <tr>
							      <th>Users:</th>
								  <td><ol><li id="facebook-list" class="input-text">
								  <input type="text" name="to"  id="facebook-demo"  value="<?php echo $this->to; ?>" />
								   <div id="facebook-auto"> 
           							 <div class="default">Type the name of an user to select</div>
            							<ul class="feed">
										</ul>
									</div>
									</li></ol>								  </td>
							  </tr>
						      <tr>
							      <th>Groups:</th>
								  <td><ol><li id="facebook-list" class="input-text">
								  <input type="text" name="to_group"  id="facebook-demo3"  value="<?php echo $this->to_group; ?>" />
								   <div id="facebook-auto1"> 
           							 <div class="default">Type the name of a group to select</div>
            							<ul class="feed">
										</ul>
									</div>
									</li></ol>								  </td>
							  </tr>
						      <tr>
							      <th>Subject:</th>
								  <td><input type="text" name="subject" id="subject" value="<?php echo $this->subject; ?>" /></td>
						      </tr>
						      <tr>
						        <th>Attachment</th>
						        <td><div> <?php $att->uploadAttchment('local'); ?> </div></td>
					        </tr>
						      <tr>
							      <th>&nbsp;</th>
								  <td><textarea name="message" id="message" cols="40" rows="8"><?php echo $this->message; ?></textarea></td>
							  </tr>
						      <tr>
							      <th>&nbsp;</th>
								  <td  align="right"><input style="width:auto" type="submit" name="go" id="go" value="send" onclick="tlist2.update();tlist3.update(); <?php echo $FormName;?>.message.value=getText('message'); return <?php echo $ValidationFunctionName?>();" /></td>
							  </tr>
						  </table>
						  </form>
						  <?php
						  break;
			case 'server' :
						  			 
						  
						  extract($_POST);
						  $this->to = $to;
						  $this->to_group = $to_group;
						  $this->subject = $subject;
						  $this->message = $message;
						  $return =true;
						  if($this->Form->ValidField($to,'or','One of Users or Groups field is Required ','',$to_group)==false)
							  $return =false;
						  if($this->Form->ValidField($subject,'empty','Subject field is Empty ')==false)
							  $return =false;
						  if($this->Form->ValidField($message,'empty','Message field is Empty ')==false)
							  $return =false;	
						  
						  $sendTo = "";
						  $sendTo_array = array();
						  if($return)
						  {
								  $this->user_array = explode('###',$this->to);
								  $this->user_array_group = explode('###',$this->to_group);
								  foreach($this->user_array_group as $_group){
									$this->user_array[] = $_group;
								  }
								  $this->send_to_text = implode(',',$this->user_array);
								  foreach($this->user_array as $sendTo){
									if(strpos($sendTo,"roup:")){
										$_group_name = substr($sendTo,7);
										$_sql = "select * from ".TBL_USERGROUP." a,".GROUP_ACCESS." b where a.group_name='$_group_name' and a.group_id = b.group_id ";
										$_record = $this->db->query($_sql,__LINE__,__FILE__);
										while($_row = $this->db->fetch_array($_record)){
											$sendTo_array[] = $this->user->GetUserNameById($_row[user_id]);
										}
									} else {
										$sendTo_array[] = $sendTo;
									}
								  }
								  $key = array_search($_SESSION[user_name],$sendTo_array);
								  if($key!=''){
									array_splice($sendTo_array,$key,1);
								  }
								  while(array_shift($this->user_array)){}
								  $this->user_array = array_unique($sendTo_array);
								  if(count($this->user_array)>1){
									$this->SendToMultiple($this->user_array);
								  } else {
									$this->SendToUser($this->user_array[0]);
								  }
								  $att->uploadAttchment('server',$this->omessage_id);
								  $_SESSION[msg] = "Message has been sent";
								  ?>
								  <script type="text/javascript">
								  location.replace('<?php echo $_SERVER['PHP_SELF'] ?>?index=Outbox')
								  </script>
						  <?php
						  } else {
								echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
								$this->SendMessage('local',$att);
						  }
						  break;
						  
			default :	  echo "Wrong Parameter Passed.";
		}
	}
	
	function GetInbox($user_name){
		$sql = "SELECT * FROM ".TBL_MESSAGE_INBOX." WHERE user_id = '$user_name' order by timestamp desc";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		?>
		<table width="100%" id="message">
		<?php
		if($this->db->num_rows($record)>0){
			while($row = $this->db->fetch_array($record)){
		    	$sql2 = "SELECT * FROM ".TBL_MESSAGE_OUTBOX." WHERE omessage_id = '$row[omessage_id]'";
				$record2 = $this->db->query($sql2,__FILE__,__LINE__);
				$row2 = $this->db->fetch_array($record2);
				?>
		    <tr>
			    <td width="7%" valign="top" align="right" ><img src="images/person.gif" /></td>
				<td width="27%" valign="top"  align="left"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?imessage_id=<?php echo $row['imessage_id'] ?>&omessage_id=<?php echo $row2['omessage_id'] ?>&index=ReadInbox"><?php echo "<span class='name'>".$row2[user_id].' says:</span><br>';?></a>
						<small> <?php  echo date('h:ia \o\n n/j/y',$row2['timestamp']); 
					?></small></td>
				<td width="61%" valign="top" ><a href="<?php echo $_SERVER['PHP_SELF'] ?>?imessage_id=<?php echo $row['imessage_id'] ?>&omessage_id=<?php echo $row2['omessage_id'] ?>&index=ReadInbox"><?php echo "<span>".$row2[subject]."</span>";?></a>
				<?php //echo '<p>'.substr($row[message],0,100).'....</p>'
				?></td>
				<td width="5%"><a class="verysmall_text" href="<?php echo $_SERVER['PHP_SELF'] ?>?index=DeleteInbox&imessage_id=<?php echo $row[imessage_id] ?>" onclick="if(!confirm('are you sure ? ')) return false;"><img src="images/trash.gif" border="0"  align="absmiddle" /></a></td>
		  </tr>
				<?php
				
			}
		} else {
			?>
			<tr>
				<th colspan="4">Inbox is Empty !!</th>
			</tr>
			<?php
		}
		?>
		</table>		
		<?php
	}
	
	function GetOutbox($user_id){		
		$sql = "SELECT * FROM ".TBL_MESSAGE_OUTBOX." WHERE user_id = '$user_id' order by timestamp desc";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		?>
		<table width="100%" id="message">
		<?php
		if($this->db->num_rows($record)>0){
			while($row = $this->db->fetch_array($record)){
		    	$sql2 = "SELECT * FROM ".TBL_MESSAGE_SENT_TO." WHERE omessage_id = '$row[omessage_id]'";
				$record2 = $this->db->query($sql2,__FILE__,__LINE__);
				$to = "";
				while($row2 = $this->db->fetch_array($record2)){
					$to .= $row2['to'].', ';
				}
				$to = substr($to,0,strlen($to)-2);				
				?>				
				
				 <tr>
			    <td width="7%" valign="top" align="right" ><img src="images/person.gif" /></td>
				<td width="32%" valign="top"  align="left"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?omessage_id=<?php echo $row['omessage_id'] ?>&index=ReadOutbox"><?php echo "<span class='name'>to ".$row[send_to_text].' on:</span><br>';?></a>
						<small> <?php  echo date('h:ia \o\n n/j/y',$row['timestamp']); 
					?></small></td>
				<td width="61%" valign="top" ><a href="<?php echo $_SERVER['PHP_SELF'] ?>?omessage_id=<?php echo $row['omessage_id'] ?>&index=ReadOutbox"><?php echo "<span>".$row[subject]."</span>";?></a>
				
				</td>
		  		</tr>
				<tr><td></td>
				<td colspan="2"></td>
				</tr>
				<?php
				
			}
		} else {
			?>
			<tr>
				<th colspan="3">Outbox is Empty !!</th>
			</tr>
			<?php
		}
		?>
		</table>		
		<?php
	}
	
	function ReadMessageInbox($message_id,$imessage_id,$att='',$user_id){
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
		<?php
		if($row[reply_to_message_id]>0){
		$this->ReadReplyMessageInbox($row[reply_to_message_id]);
		} ?>
		<table width="100%" id="message">
		    <tr>
			    <td valign="top" width="10%"><img src="images/person.gif" /></td>
				<td valign="top" width="15%"><?php echo "<span class='name'>".$row[user_id].' says:</span><br>';?>
						<small> <?php  echo date('h:ia \o\n n/j/y',$row['timestamp']); 
					?></small></td>
				<td valign="top" width="75%"><?php echo "<span>".$row[subject]."</span>";
					echo '<p>'.$row[message].'</p>';
					?><p><?php $att->attachmentList($row['omessage_id'],$user_id);?></p></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class="align_right"><a class="verysmall_text" href="<?php echo $_SERVER['PHP_SELF'] ?>?index=DeleteInbox&imessage_id=<?php echo $imessage_id ?>" onclick="if(!confirm('are you sure ? ')) return false;"><img src="images/trash.gif" border="0"  align="absmiddle" /></a>&nbsp;&nbsp;<input style="width:auto" type="submit" name="reply" value="Reply" />&nbsp;&nbsp;<input type="submit" name="reply_to_all" id="reply_to_all" value="Reply to all" style="width:auto" /></td>
			</tr>
		</table>
		</form>
		<?php
	}
	
	function ReadReplyMessageInbox($message_id){
		$sql = "SELECT * FROM ".TBL_MESSAGE_OUTBOX." a WHERE a.omessage_id = '$message_id' ";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		$row = $this->db->fetch_array($record);
		$FormName = "frm_readMessageInbox";
		if($row[reply_to_message_id]>0){
		$this->ReadReplyMessageInbox($row[reply_to_message_id]); 
		} ?>
		<table width="100%" id="message">
		    <tr>
			    <td valign="top" width="10%"><img src="images/person.gif" /></td>
				<td valign="top" width="15%"><?php echo "<span class='name'>".$row[user_id].' says:</span><br>';?>
						<small><?php  echo date('h:ia \o\n n/j/y',$row['timestamp']); 
					?></small></td>
				<td valign="top" width="75%"><?php echo "<span>".$row[subject]."</span>";
					echo '<p>'.$row[message].'</p>';
					?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="right"></td>
			</tr>
		</table>
		<?php
	}

	function DeleteMessageInbox($imessage_id){
		$this->imessage_id = $imessage_id;
		$sql = "delete from ".TBL_MESSAGE_INBOX." where imessage_id='$this->imessage_id'";
		$this->db->query($sql,__FILE__,__LINE__);
		?>
		<script type="text/javascript">
		location.replace('<?php echo $_SERVER['PHP_SELF'] ?>?index=Inbox')
		</script>
		<?php
	}

	function Reply($runat,$omessage_id,$imessage_id,$reply_index){
		$this->omessage_id = $omessage_id;
		$sql = "SELECT * FROM ".TBL_MESSAGE_OUTBOX." a WHERE a.omessage_id = '$omessage_id' ";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		$row = $this->db->fetch_array($record);
		$FormName = "frm_reply";
		switch($runat){
			case 'local' :
						$redirect = "";
						if($reply_index=='reply'){
						    $this->to = $row[user_id];
							$redirect = 'ReadInbox';
						}
						else if($reply_index=='reply_to_all'){
							$this->to = $row[user_id].','.$this->GetReplyToAll();
							$redirect = 'ReadInbox';
						}
						else if($reply_index=='reply_outbox'){
							$this->to = $this->GetReplyToAll();
							$redirect = 'ReadOutbox';
						}
						
			?>
			<form name="<?php echo $FormName?>" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?index=toReply" enctype="multipart/form-data">
			<input type="hidden" name="omessage_id" id="omessage_id" value="<?php echo $omessage_id ?>" />
						<table width="100%" id="message" class="reply">
							<tr>
								<td valign="top" width="10%"><img src="images/person.gif" /></td>
								<td valign="top" width="15%"><?php echo "<span class='name'>".$row[user_id].' says:</span><br>';?>
										<small> <?php  echo date('h:ia \o\n n/j/y',$row['timestamp']); 
									?></small></td>
								<td valign="top" width="75%"><?php echo "<span>".$row[subject]."</span>";
									echo '<p>'.$row[message].'</p>';
									?></td>
							</tr>
							  <tr>
							  	<th colspan="2"><input type="hidden" name="to" id="to" value="<?php echo $this->to ?>" /><input type="hidden" name="subject" id="subject" value="<?php echo $row[subject] ?>" />&nbsp;</th>
								<th align="left"><?php if($_POST[reply]=='Reply'){ ?>Reply<?php } else if($_POST[reply_to_all]=="Reply to all"){ ?>Replying to all<?php }?></th>
							  </tr>
						      <tr>
							      <th colspan="2">&nbsp;</th>
								  <td><textarea name="message" cols="40" rows="8"></textarea></td>
							  </tr>
						      <tr>
							      <th colspan="2">&nbsp;</th>
								  <td class="align_right"><input style="width:auto" 
								  onclick="this.form.message.value=getText('message');"
								   type="submit" name="go" id="go" value="Go" />&nbsp;&nbsp;
								   <input type="button" name="cancel" id="cancel" value="Cancel"  style="width:auto" 
								   onclick="location.replace('message.php?index=<?php echo $redirect; ?>&omessage_id=<?php echo $omessage_id ?>&imessage_id=<?php echo $imessage_id ?>'); return false;" /></td>
							  </tr>
						  </table>
			</form>
			<?php
			break;
			case 'server' :
						  extract($_POST);
						  $this->to = $to;
						  $this->subject = $subject;
						  $this->message = $message;
						  $this->send_to_text = $this->to;
						  $this->reply_to_message_id = $omessage_id;
						  $this->user_array = explode(',',$this->to);
						  if(count($this->user_array)>1){
						  	$this->SendToMultiple($this->user_array);
						  } else {
						    $this->SendToUser($this->to);
						  }
						  $this->InsertReplyToMessageId();
						  ?>
						  <script type="text/javascript">
						  location.replace('message.php?index=Outbox')
						  </script>
						  <?php
						  break;
			default :		echo "Wrong Parameter Passed";
		}
	}
	

	function ReadMessageOutbox($message_id,$att,$user_id){
		$sql = "SELECT * FROM ".TBL_MESSAGE_OUTBOX." a WHERE a.omessage_id = '$message_id' ";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		$row = $this->db->fetch_array($record);
		$sql2 = "SELECT * FROM ".TBL_MESSAGE_SENT_TO." WHERE omessage_id = '$row[omessage_id]'";
		$record2 = $this->db->query($sql2,__FILE__,__LINE__);
		$to = "";
		while($row2 = $this->db->fetch_array($record2)){
			$to .= $row2['to'].', ';
		}
		$to = substr($to,0,strlen($to)-2);
		$FormName = "frm_readMessageOutbox";
		?>
		<form name="<?php echo $FormName?>" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?index=ReplyOutbox" enctype="multipart/form-data">
		<input type="hidden" name="omessage_id" id="omessage_id" value="<?php echo $message_id ?>" />
		<?php
		if($row[reply_to_message_id]>0){
		$this->ReadReplyMessageOutbox($row[reply_to_message_id]); 
		}
		?>
		<table width="100%" id="message">
		    <tr>
			    <td valign="top" width="10%"><img src="images/person.gif" /></td>
				<td valign="top" width="20%"><?php echo "<span class='name'>".$row[user_id].' says to '.$row[send_to_text].':</span><br>';?>
						<small><?php  echo date('h:ia \o\n n/j/y',$row['timestamp']); 
					?></small></td>
				<td valign="top" width="75%"><?php echo "<span>".$row[subject]."</span>";
					echo '<p>'.$row[message].'</p>';
					?><p><?php $att->attachmentList($row['omessage_id'],$user_id);?></p></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class="align_right"><input style="width:auto" type="submit" name="reply" id="reply" value="Reply" /></td>
			</tr>
		</table>
		</form>
		<?php
	}

	function ReadReplyMessageOutbox($message_id){
		$sql = "SELECT * FROM ".TBL_MESSAGE_OUTBOX." a WHERE a.omessage_id = '$message_id' ";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		$row = $this->db->fetch_array($record);
		$sql2 = "SELECT * FROM ".TBL_MESSAGE_SENT_TO." WHERE omessage_id = '$row[omessage_id]'";
		$record2 = $this->db->query($sql2,__FILE__,__LINE__);
		$to = "";
		while($row2 = $this->db->fetch_array($record2)){
			$to .= $row2['to'].', ';
		}
		$to = substr($to,0,strlen($to)-2);
		$FormName = "frm_readMessageOutbox";
		if($row[reply_to_message_id]>0){
		$this->ReadReplyMessageOutbox($row[reply_to_message_id]); 
		}
		?>
		<table width="100%" id="message">
		    <tr>
			    <td valign="top" width="10%"><img src="images/person.gif" /></td>
				<td valign="top" width="20%"><?php echo "<span class='name'>".$row[user_id].' says to '.$to.':</span><br>';?>
						<small><?php  echo date('h:ia \o\n n/j/y',$row['timestamp']); 
					?></small></td>
				<td valign="top" width="75%"><?php echo "<span>".$row[subject]."</span>";
					echo '<p>'.$row[message].'</p>';
					?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td align="right"></td>
			</tr>
		</table>
		<?php
	}

	
	function GetRecentMessages($user_name,$limit)
	{
		$this->limit=$limit;
		$sql="SELECT * FROM ".TBL_MESSAGE_INBOX." WHERE user_id = '$user_name' order by timestamp desc limit 0,$this->limit";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0){
		while($row=$this->db->fetch_array($record)){
	    	$sql2 = "SELECT * FROM ".TBL_MESSAGE_OUTBOX." WHERE omessage_id = '$row[omessage_id]'";
			$record2 = $this->db->query($sql2,__FILE__,__LINE__);
			$row2 = $this->db->fetch_array($record2);
			?>
				<li class="message_icon">
					<span class="message_title">
						<a href="message.php?imessage_id=<?php echo $row['imessage_id'] ?>&omessage_id=<?php echo $row2['omessage_id'] ?>&index=ReadInbox">
							<?php echo "From ".ucfirst($row2['user_id'])." on ".date("n/j/Y",$row[timestamp]); ?>
						</a>
					</span><br />
					<span class="message_summary">
						<?php echo substr($row['subject'],0,50).'...';?>
					</span>
				</li>
			<?php
		} 
		}else {
			?> <li> <?php echo "<span >none to display</span>"; ?></li><?php
		}
	}

	function GetReplyToAll(){
		$sql = "SELECT * FROM ".TBL_MESSAGE_SENT_TO." a WHERE a.omessage_id = '$this->omessage_id' and a.to != '$_SESSION[user_name]'";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		$to = "";
		if($this->db->num_rows($record)>0){
		  while($row = $this->db->fetch_array($record)){
			  $to .= $row['to'].',';
		  }
		  $to = substr($to,0,strlen($to)-1);
		}
		return $to;
	}

	function setMessage($msg){
		$this->message = $msg;
	}
	
	function setSubject($sub){
		$this->subject = $sub;
	}

	function setSendToText($id){
		$this->send_to_text = implode(',',$id);
	}

	function SendToUser($user_name){
		$this->user_name = $user_name;
		if(trim($this->user_name)!='' and trim($this->user_name)!="" and trim($this->user_name)!=NULL){
		  $this->user_id = $user_name;
		  $this->SaveMessageToOutbox();
		  $this->SaveMessageToInbox();
		  $this->SaveMessageToSentTo();
		} else {
			$_SESSION['msg'] .= 'Invalid To Field';
		}
	}
	
	function SaveMessageToOutbox(){
		$insert_sql_array = array();
		$insert_sql_array['user_id'] = $_SESSION[user_name];
		$insert_sql_array['message'] = $this->message;
		$insert_sql_array['subject'] = $this->subject;
		$insert_sql_array['timestamp'] = time();
		$insert_sql_array['send_to_text'] = $this->send_to_text;
		$this->db->insert(TBL_MESSAGE_OUTBOX,$insert_sql_array,false,'<p><strong><em><span><ol><li><ul><img><a>',
										'');
		$this->omessage_id=$this->db->last_insert_id();
	}

	function SaveMessageToInbox(){
		$insert_sql_array = array();
		$insert_sql_array['user_id'] = $this->user_id;
		$insert_sql_array['message'] = $this->message;
		$insert_sql_array['subject'] = $this->subject;
		$insert_sql_array['omessage_id'] = $this->omessage_id;
		$insert_sql_array['timestamp'] = time();
		$this->db->insert(TBL_MESSAGE_INBOX,$insert_sql_array,false,'<p><strong><em><span><ol><li><ul><img><a>',
										'');
	}
	
	function SaveMessageToSentTo(){
		$insert_sql_array = array();
		$insert_sql_array['to'] = $this->user_id;
		$insert_sql_array['omessage_id'] = $this->omessage_id;
		$insert_sql_array['timestamp'] = time();
		$this->db->insert(TBL_MESSAGE_SENT_TO,$insert_sql_array,false,'<p><strong><em><span><ol><li><ul><img><a>',
										'');
	}
	
	function InsertReplyToMessageId(){
		$update_sql_array = array();
		$update_sql_array['reply_to_message_id'] = $this->reply_to_message_id;
		$this->db->update(TBL_MESSAGE_OUTBOX,$update_sql_array,'omessage_id',$this->omessage_id,false,'<p><strong><em><span><ol><li><ul><img><a>',
										'');
	}
	
	function SendToMultiple($user_array){
		$this->user_array = $user_array;
		if($this->isValidUserArray()){
		    $this->SaveMessageToOutbox();
			foreach($this->user_array as $user_name){
				$this->user_name = $user_name;
				if(trim($this->user_name)!='' and trim($this->user_name)!="" and trim($this->user_name)!=NULL){
				  $this->user_id = $user_name;
				  $this->SaveMessageToInbox();
				  $this->SaveMessageToSentTo();
				}
			}
		} else {
			$_SESSION['msg'] .= 'Invalid To Field';
		}
	}
	
	function isValidUserArray(){
		foreach($this->user_array as $user_name){
			if(trim($user_name)!='' and trim($user_name)!="" and trim($user_name)!=NULL)
				return true;
		}
		return false;
	}	
	
}
?>