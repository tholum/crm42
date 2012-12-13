<?php

/***********************************************************************************

			Class Discription : This class will handle the creation modification 
								and deletion of events on the calender. 
			
			Class Memeber Functions : login($user,$pass)
									  view_event($q='',$limit='',$link='',$display='short')
									  add_event($runat)
									  edit_event($runat,$calendar_id)
									  Delete_Event($calendar_id)							  
			
			
			Describe Function of Each Memeber function :
			
				1. login($user,$pass): // setter function for user, pass, client, gcal.
				
				2. view_event($q='',$limit='',$link='',$display='short'):   //  this function displays the events in the calender. the limit of the events can
							can also be set.
				
				3. add_event($runat):    // $runat=local/server  // this function adds an event to the calender. 
				
				4. edit_event($runat,$calendar_id):   // $runat=local/server   // user can edit the existing event in the calender.
				
				5. Delete_Event($calendar_id):  // user can delete the event from the calender.



************************************************************************************/
class dummycls {
    function __CALL( $a , $b ){
        return true;
    }
}
class GCalendar {

	private $user;
	private $pass;
	private $gcal;
	private $client;
	private $query;
	private $feed;
	private $event;
	
	var $content;
	var $event_title;
	var $start_date;
	var $start_time;
	var $end_date;
	var $end_time;
	var $Validity;
	var $Form;
	var $calendar_id;
	var $auth;


	function __construct($user,$pass)
  	{
	    $this->gcal = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
		$this->login($user,$pass);
		$this->Validity= new dummycls();
		$this->Form=new dummycls();
		$this->auth = new dummycls();
	}

	/******************************************************************************************************/

	function login($user,$pass)
	{
		$this->user=$user;
		$this->pass=$pass;
	    $this->client = Zend_Gdata_ClientLogin::getHttpClient($this->user, $this->pass, $this->gcal);
		$this->gcal = new Zend_Gdata_Calendar($this->client);
	}


	/******************************************************************************************************/
	function view_event($q='',$limit='',$link='',$display='short')
	{
		$this->query = $this->gcal->newEventQuery();
		$this->query->setUser('default');
		$this->query->setVisibility('private');
		$this->query->setProjection('full');
		$this->query->setOrderby('starttime');
		if($limit!='')
		$this->query->setMaxResults($limit);
		if($q!='') {
		  $this->query->setQuery($q);      
		}
		
		try {
		  $this->feed = $this->gcal->getCalendarEventFeed($this->query);
		} catch (Zend_Gdata_App_Exception $e) {
		  echo "Error: " . $e->getResponse();
		}
		$i=0;
		foreach ($this->feed as $event) {
		$i++;
		$when = $event->getWhen();
		$startTime = strtotime($when[0]->getStartTime());
		$this->start_date = date('h:i A  Y/m/d', $startTime);
		$id = substr($event->id->text, strrpos($event->id->text, '/')+1);
		?>
		<li class="date_icon" 
		<?php if($this->auth->isAdmin()){ ?>
		onmouseover="if(document.getElementById('cal<?php echo $id; ?>'))document.getElementById('cal<?php echo $id; ?>').style.display=''; " 
		onmouseout="if(document.getElementById('cal<?php echo $id; ?>'))document.getElementById('cal<?php echo $id; ?>').style.display='none'; "
		<?php } ?>
		>
		<?php 
			if($limit=='') {
		 	?>
			<span id="cal<?php echo $id; ?>"  style="display:none;" class="task_action">
				<a href="?index=Edit&id=<?php echo $id; ?>"><img src="images/edit.gif" border="0"  align="absmiddle" /></a>
				<a href="?index=delete&id=<?php echo $id; ?>" onclick="if(confirm('Are you sure ?'))return true; else return false;"><img src="images/trash.gif" border="0"  align="absmiddle" /></a>
			</span>
			<?php } ?>
			<span class="message_title">
		 	<a href="<?php echo $link;?>" ><?php echo stripslashes($event->title->text) . ' on '.$this->start_date; ?></a>
			</span><br />
			<span class="message_summary">
			<?php  if($display=='short') echo substr(stripslashes($event->content->text),0,16) . "..";
					else echo stripslashes($event->content->text);
			 ?>	
			</span>
		</li>
		<?php
		}
		if($i==0) echo 'none to display';
	}
	
	/******************************************************************************************************/
	
	function add_event($runat)
	{
		switch($runat) {
		
		case 'local' : 
		if(count($_POST)>0 ){
					extract($_POST);
					$this->event_title=$event_title;
					$this->content=$content;
					$sdate=explode('-',$start_date);
					$this->start_date=$sdate[2].'-'.$sdate[1].'-'.$sdate[0];
					$this->start_time=$start_time;
					$edate=explode('-',$end_date);
					$this->end_date=$edate[2].'-'.$edate[1].'-'.$edate[0];
					$this->end_time=$end_time;
					}
					$FormName='frm_addevent';
		$ControlNames=array(	"event_title"			=>array('event_title',"''","Please Enter Event title","spanevent_title_frm_addevent"),
										"content"			=>array('content',"''","Please enter Remark","spancontent_frm_addevent"),
										"start_date"			=>array('start_date',"''","Please enter Start Date","spanstart_date_frm_addevent"),
										"end_date"			=>array('end_date',"''","Please enter End Date ","spanend_date_frm_addevent"),
										);

					$ValidationFunctionName="frm_addevent_CheckValidity";
					
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
					
					
		?>
				<form method="post" action="" name="<?php echo $FormName; ?>">
					<input type="hidden" value="" name="start_date" id="start_date" />
					<input type="hidden" value="" name="sdate_hh"  id="sdate_hh"/>
					<input type="hidden" value="" name="sdate_ii" id="sdate_ii" />
					<input type="hidden" value="" name="end_date" id="end_date" />
					<input type="hidden" value="" name="edate_hh"  id="edate_hh"/>
					<input type="hidden" value="" name="edate_ii" id="edate_ii" />


				<table width="100%" class="table">
				<tr><td colspan="3">	
					<ul id="error_list">
					<li><span id="spanevent_title_frm_addevent" class="normal"></span></li>
					<li><span id="spancontent_frm_addevent" class="normal"></span></li>
					<li><span id="spanstart_date_frm_addevent" class="normal"></span></li>
					<li><span id="spanstart_time_frm_addevent" class="normal"></span></li>
					<li><span id="spanend_date_frm_addevent" class="normal"></span></li>
					<li><span id="spanend_time_frm_addevent" class="normal"></span></li>
					</ul>
				</td></tr>			

				<tr>
				<td colspan="2" class="textb"> Event title:</th>
				</tr>
				<tr>
				<td colspan="2"><input type="text" name="event_title" id="event_title" value="<?php echo $this->event_title;?>" /></td>
				</tr>
				<tr>
				<th>Comments:</th>
				<td><textarea name="content" id="content"><?php echo $this->content;?></textarea></td>
				</tr>
				<tr>
					<th>Start:</th>
					<td> 
					<input name="start_date_display" type="text"  id="start_date_display" value="" readonly="true"/>
					<script type="text/javascript">
						  var cal2;
						  var cal1=new Calendar({
								  inputField   	: "start_date_display",
								  dateFormat	: "%I:%M %p on %Y-%m-%d",
								  trigger		: "start_date_display",
								  weekNumbers   : true,
								  bottomBar		: true,
								  showTime      : 12,
								  onSelect		: function() {
														
														document.<?php echo $FormName;?>.end_date_display.value='';
														document.<?php echo $FormName;?>.start_date.value=this.selection.print("%Y-%m-%d");
														document.<?php echo $FormName;?>.sdate_hh.value=this.getHours();
														document.<?php echo $FormName;?>.sdate_ii.value=this.getMinutes();
													  	delete cal2;
														setCal2(this.selection.get());
														this.hide();
														document.<?php echo $FormName;?>.start_date_display.value=this.selection.print("%I:%M %p on %Y-%m-%d");
													},
													
								onTimeChange  : function() {
							
														document.<?php echo $FormName;?>.start_date.value=this.selection.print("%Y-%m-%d");
														document.<?php echo $FormName;?>.sdate_hh.value=this.getHours();
														document.<?php echo $FormName;?>.sdate_ii.value=this.getMinutes();
														document.<?php echo $FormName;?>.start_date_display.value=this.selection.print("%I:%M %p on %Y-%m-%d");
													}
						  });
               		 </script>

					</td>
				</tr>
				<tr>
				<th>End</th>
				<td><input type="text" name="end_date_display" id="end_date_display" value="" readonly="true" />
					<script type="text/javascript">
						  function setCal2(minDate){
						  	cal2=new Calendar({
								  inputField	: "end_date_display",
								  dateFormat	: "%I:%M %p on %Y-%m-%d",
								  trigger		: "end_date_display",
								  weekNumbers   : true,
								  showTime      : 12,
								  bottomBar		: true,
								  min			: minDate,
								  onSelect		: function() {
														document.<?php echo $FormName;?>.end_date.value=this.selection.print("%Y-%m-%d");
														document.<?php echo $FormName;?>.edate_hh.value=this.getHours();
														document.<?php echo $FormName;?>.edate_ii.value=this.getMinutes();
														this.hide();
													    document.<?php echo $FormName;?>.end_date_display.value=this.selection.print("%I:%M %p on %Y-%m-%d");
												  },
								onTimeChange  : function() {
							
														document.<?php echo $FormName;?>.end_date.value=this.selection.print("%Y-%m-%d");
														document.<?php echo $FormName;?>.edate_hh.value=this.getHours();
														document.<?php echo $FormName;?>.edate_ii.value=this.getMinutes();
														document.<?php echo $FormName;?>.end_date_display.value=this.selection.print("%I:%M %p on %Y-%m-%d");
											}
						  });
						  }
               		 </script>
				</td>
				</tr>
				<tr>
				<td colspan="2" align="right"><input name="submit" style="width:auto;" type="submit" value="add event" onClick="return <?php echo $ValidationFunctionName ?>();"/></td>
				</tr>
				</table>
				</form>
		<?
		break;
		case 'server' :
					extract($_POST);
					$this->event_title=htmlentities($event_title);
					$this->content=htmlentities($content);
					$sdate=explode('-',$start_date);
					$this->start_date=$sdate[2].'-'.$sdate[1].'-'.$sdate[0];
					$edate=explode('-',$end_date);
					$this->end_date=$edate[2].'-'.$edate[1].'-'.$edate[0];
					//server side validation
					$return =true;
					if($this->Form->ValidField($event_title,'empty','Please Enter Event title')==false)
					$return =false;
					if($this->Form->ValidField($content,'empty','Please enter Remark')==false)
					$return =false;
					if($this->Form->ValidField($start_date,'empty','Please enter Start Date')==false)
					$return =false;
					if($this->Form->ValidField($end_date,'empty','"Please enter End Date')==false)
					$return =false;
					
					//print_r($_POST);
					if($return) {
						  $sdate=explode('-',$this->start_date);	
						  $edate=explode('-',$this->end_date);	
						  $start = date(DATE_ATOM, mktime($_POST['sdate_hh'], $_POST['sdate_ii'], 0, $sdate[1], $sdate[0], $sdate[2]));
						  $end = date(DATE_ATOM, mktime($_POST['edate_hh'], $_POST['edate_ii'], 0, $edate[1], $edate[0], $edate[2]));
							
						  echo $start.' '.$end;
						  // construct event object
						  // save to server      
						  try {
							$this->event = $this->gcal->newEventEntry();        
							$this->event->title = $this->gcal->newTitle($this->event_title);  
							$this->event->content = $this->gcal->newContent("$this->content");
								  
							$when = $this->gcal->newWhen();
							$when->startTime = $start;
							$when->endTime = $end;
							$this->event->when = array($when);        
							$this->gcal->insertEvent($this->event);   
						  } catch (Zend_Gdata_App_Exception $e) {
							echo "Error: " . $e->getResponse();
						  }
						$_SESSION['msg']='Event created successfully';
					?>
					<script type="text/javascript">
					window.location="<?php echo $_SERVER['PHP_SELF'];?>";
					</script>
					<?php
					}
					else
					{
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
					$this->add_event('local');
					}
		
		break;
		default : echo 'Wrong Paramemter passed';
		}	
	}
	
	/******************************************************************************************************/
	
	
	function edit_event($runat,$calendar_id)
	{
		$this->calendar_id=$calendar_id;
		switch($runat) {
		
		case 'local' : 
		
		try {          
			  $this->event = $this->gcal->getCalendarEventEntry('http://www.google.com/calendar/feeds/default/private/full/' .$calendar_id );
			} catch (Zend_Gdata_App_Exception $e) {
			  echo "Error: " . $e->getResponse();
			}
		  
		  $this->event_title = $this->event->title->text;
		  $this->content = $this->event->content->text;
		  $when = $this->event->getWhen();
		  
		  
		  
		  $startTime = strtotime($when[0]->getStartTime());
		  $this->start_date = date('Y-m-d', $startTime);
		  $this->sdate_hh = date('H', $startTime);
		  $this->sdate_ii = date('i', $startTime);
		  $endTime = strtotime($when[0]->getEndTime());
		  $this->end_date = date('Y-m-d', $endTime);
		  $this->edate_hh = date('H', $endTime);
		  $this->edate_ii = date('i', $endTime);  
		  $mindate = date('Ymd', $startTime);   
		  $FormName='frm_Editevent';
		  
		 // echo date('Y-m-d h :i A', $startTime).'  '.date('Y-m-d h :i A', $endTime);
		  
		  $ControlNames=array("event_title"=>array('event_title',"''","Please Enter Event title","spanevent_title_frm_editevent"),
		  "content"=>array('content',"''","Please enter Remark","spancontent_frm_editevent"),
		  "start_date"=>array('start_date',"''","Please enter Start Date","spanstart_date_frm_editevent"),
		  "end_date"=>array('end_date',"''","Please enter End Date ","spanend_date_frm_editevent")
		);

			$ValidationFunctionName="frm_Editevent_CheckValidity";
					
			$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
										
		?>
				<form method="post" action="" name="<?php echo $FormName; ?>">
			     	<input type="hidden" name="id" id="id" value="<?php echo $this->calendar_id; ?>">
					<input type="hidden" value="" name="start_date" id="start_date" value="<?php echo $this->start_date;?>" />
					<input type="hidden" value="" name="sdate_hh"  id="sdate_hh" value="<?php echo $this->sdate_hh;?>"/>
					<input type="hidden" value="" name="sdate_ii" id="sdate_ii"  value="<?php echo $this->sdate_ii;?>"/>
					<input type="hidden" value="" name="end_date" id="end_date" value="<?php echo $this->end_date;?>" />
					<input type="hidden" value="" name="edate_hh"  id="edate_hh" value="<?php echo $this->edate_hh;?>"/>
					<input type="hidden" value="" name="edate_ii" id="edate_ii" value="<?php echo $this->edate_ii;?>" />
				<table width="100%" class="table">
				<tr><td colspan="3">	
					<ul id="error_list">
					<li><span id="spanevent_title_frm_editevent" class="normal"></span></li>
					<li><span id="spancontent_frm_editevent" class="normal"></span></li>
					<li><span id="spanstart_date_frm_editevent" class="normal"></span></li>
					<li><span id="spanstart_time_frm_editevent" class="normal"></span></li>
					<li><span id="spanend_date_frm_editevent" class="normal"></span></li>
					<li><span id="spanend_time_frm_editevent" class="normal"></span></li>
					</ul>
				</td></tr>			

				<tr>
				<td colspan="2" class="textb"> Event title:</td>
				</tr>
				<tr>
				<td colspan="2"><input type="text" name="event_title" id="event_title" value="<?php echo $this->event_title;?>" /></td>
				</tr>
				<tr>
				<th>Comments</th>
				<td><textarea name="content" id="content"><?php echo $this->content;?></textarea></td>
				</tr>
				<tr>
					<th>Start date :</th>
					<td> 
					<input name="start_date_display" type="text"  id="start_date_display" value="<?php echo date('h:i A',$startTime).' on '.date('Y-m-d',$startTime); ?>" readonly="true"/>
					<script type="text/javascript">
					  var cal2;
					  var cal1=new Calendar({
								  inputField   	: "start_date_display",
								  dateFormat	: "%I:%M %p on %Y-%m-%d",
								  trigger		: "start_date_display",
								  weekNumbers   : true,
								  selection     : <?php echo date("Ymd",$startTime); ?>,
								  bottomBar		: true,
								  showTime      : 12,
								  onSelect		: function() {
														
														document.<?php echo $FormName;?>.end_date_display.value='';
														document.<?php echo $FormName;?>.start_date.value=this.selection.print("%Y-%m-%d");
														document.<?php echo $FormName;?>.sdate_hh.value=this.getHours();
														document.<?php echo $FormName;?>.sdate_ii.value=this.getMinutes();
														delete cal2;
													  	setCal2(this.selection.get());
														this.hide();
													},
													
								onTimeChange  : function() {
							
														document.<?php echo $FormName;?>.start_date.value=this.selection.print("%Y-%m-%d");
														document.<?php echo $FormName;?>.sdate_hh.value=this.getHours();
														document.<?php echo $FormName;?>.sdate_ii.value=this.getMinutes();
														//document.<?php echo $FormName;?>.start_date_display.value=this.selection.print("%I:%M %p on %Y-%m-%d");
													}
						  });
						  
               		 </script>

					</td>
				</tr>
				<tr>
				<th> End  date :</th>
				<td><input type="text" name="end_date_display" id="end_date_display" value="<?php echo date('h:i A',$endTime).' on '.date('Y-m-d',$endTime); ?>" readonly="true" />
				  <script type="text/javascript">
				  
				  		 function setCal2(minDate){
						   cal2=new Calendar({
								  inputField	: "end_date_display",
								  dateFormat	: "%I:%M %p on %Y-%m-%d",
								  trigger		: "end_date_display",
								  weekNumbers   : true,
								  selection     : <?php echo date("Ymd",$endTime); ?>,
								  showTime      : 12,
								  bottomBar		: true,
								  min			: minDate,
								  onSelect		: function() {
														document.<?php echo $FormName;?>.end_date.value=this.selection.print("%Y-%m-%d");
														document.<?php echo $FormName;?>.edate_hh.value=this.getHours();
														document.<?php echo $FormName;?>.edate_ii.value=this.getMinutes();
													  this.hide();
												  },
								onTimeChange  : function() {
							
														document.<?php echo $FormName;?>.end_date.value=this.selection.print("%Y-%m-%d");
														document.<?php echo $FormName;?>.edate_hh.value=this.getHours();
														document.<?php echo $FormName;?>.edate_ii.value=this.getMinutes();
														//document.<?php echo $FormName;?>.end_date_display.value=this.selection.print("%I:%M %p on %Y-%m-%d");
											}
						  });
						  }
						  setCal2(<?php echo $mindate; ?>);

               		 </script>
				</td>
				</tr>
				<tr>
				<td colspan="2" align="right"><input name="submit" style="width:auto;" type="submit" value="save event" onClick="return <?php echo $ValidationFunctionName ?>();"/> or 
				<input  style="width:auto;" type="button" value="cancel" onClick="window.location='calendar_management.php';"/></td>
				</tr>
				</table>
				</form>
		<?
		break;
		case 'server' :
					extract($_POST);
					$this->event_title=htmlentities($event_title);
					$this->content=htmlentities($content);
					$sdate=explode('-',$start_date);
					$this->start_date=$sdate[2].'-'.$sdate[1].'-'.$sdate[0];
					$edate=explode('-',$end_date);
					$this->end_date=$edate[2].'-'.$edate[1].'-'.$edate[0];
					$param="";
					//server side validation
					$return =true;
					if($this->Form->ValidField($event_title,'empty','Please Enter Event title')==false)
					$return =false;
					if($this->Form->ValidField($content,'empty','Please enter Remark')==false)
					$return =false;
					if($this->Form->ValidField($start_date,'empty','Please enter Start Date')==false)
					$return =false;
					if($this->Form->ValidField($end_date,'empty','"Please enter End Date')==false)
					$return =false;
					
					
					if($return) {
					 $sdate=explode('-',$this->start_date);	
					 $edate=explode('-',$this->end_date);	
					 $start = date(DATE_ATOM, mktime($_POST['sdate_hh'], $_POST['sdate_ii'], 0, $sdate[1], $sdate[0], $sdate[2]));
					 $end = date(DATE_ATOM, mktime($_POST['edate_hh'], $_POST['edate_ii'], 0, $edate[1], $edate[0], $edate[2]));
						  // construct event object
						  // save to server 
   
				 try {
					$this->event = $this->gcal->getCalendarEventEntry('http://www.google.com/calendar/feeds/default/private/full/' . $_POST['id']);
					$this->event->title = $this->gcal->newTitle($this->event_title);  
					$this->event->content = $this->gcal->newContent("$this->content");
						  
					$when = $this->gcal->newWhen();
					$when->startTime = $start;
					$when->endTime = $end;
					$this->event->when = array($when);        
					$this->event->save();   
					$_SESSION['msg']='Event saved successfully';
				  } catch (Zend_Gdata_App_Exception $e) {
					$_SESSION['msg'] = 'Start date must come before end date';
					$param = "?index=Edit&id=".$id;
					echo("Error: " . $e->getResponse());
				  }
				
				?>
					<script type="text/javascript">
					window.location="<?php echo $_SERVER['PHP_SELF'].$param;?>";
					</script>
					<?php
					}
					else
					{
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
					$this->edit_event('local',$id);
					}
		
		break;
		default : echo 'Wrong Paramemter passed';
		}	
	}
	
	
	/******************************************************************************************************/
	
	function Delete_Event($calendar_id)
	{
		$this->calendar_id=$calendar_id;
		  try {          
			  $this->event = $this->gcal->getCalendarEventEntry('http://www.google.com/calendar/feeds/default/private/full/' .$this->calendar_id );
			  $this->event->delete();
		  } catch (Zend_Gdata_App_Exception $e) {
			  echo "Error: " . $e->getResponse();
		  }        
		$_SESSION['msg']='Event deleted successfully';
		?>
			<script type="text/javascript">
			window.location="<?php echo $_SERVER['PHP_SELF'];?>";
			</script>
			<?php

	}
	
	
}	
?>