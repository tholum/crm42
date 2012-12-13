<?php
//**************** Notification class Created for displaying notification messages across the website ****************
	class Notification
	{
	
		var $notice;	
		var $timeout;
	
		function __construct()
		{
			$this->notice=$_SESSION[msg];
			$this->timeout=10000;
		}
		function SetNote($note)
		{
			$this->notice=$note;
		}
		
		function SetTimeout($SetTimeout)
		{
			$this->SetTimeout=$SetTimeout;
		}
		
		function Notify()
		{
			if($this->notice!='') {
			?>
			<script type="text/javascript">
			setTimeout('document.getElementById("message_t").style.display="none";',<?php echo $this->timeout; ?>);
			</script> 
			<div id="note"><div  id="message_t"><?php echo $this->notice; ?></div></div>
			<?php
			$this->destroy_note();
			}
		}
		
		function destroy_note()
		{
			$_SESSION['msg']='';
		}
	
	}

?>