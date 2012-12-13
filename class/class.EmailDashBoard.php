<?php

class emaildashboard{

	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
	}

    function searchMail() {
       ob_start();
	   $formName = "frm_search";?>
 	   <form name="<?php echo $formName;?>" method="post" action="">
		 <table width="100%" class="table" >
			<tr>
			   <td>Email Address :</td>
			   <td width="13%">
			        <input type="text" name="mail_address" id="mail_address" 
							onchange="javascript:emaildash.showMails(
														document.getElementById('mail_address').value,
														document.getElementById('client_name').value,
														document.getElementById('evntstart_date').value,
														document.getElementById('evntend_date').value,
														document.getElementById('archive').value,
														
														document.getElementById('client_id').value,
														document.getElementById('body_txt').value,
														
														{preloader:'prl',
														onUpdate: function(response,root){
														document.getElementById('show_info').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
														);"/>
			   </td>
			   <td>Client Name :</td>
			   <td width="13%">
					<input type="text" name="client_name" id="client_name" 
							onchange="javascript:emaildash.showMails(
															document.getElementById('mail_address').value,
															document.getElementById('client_name').value,
															document.getElementById('evntstart_date').value,
															document.getElementById('evntend_date').value,
															document.getElementById('archive').value,
															
															document.getElementById('client_id').value,
															document.getElementById('body_txt').value,
															
															{preloader:'prl',
															onUpdate: function(response,root){
															document.getElementById('show_info').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
														);"/>
				</td>
				
			    <td>Received :</td>
				<td width="13%">
				   <input type="text" name="evntstart_date" id="evntstart_date" value="<?php //echo $row_order['event_date']; ?>"/>
						 <script type="text/javascript">	 
						 function start_cal(){
						 new Calendar({
						 inputField   	: "evntstart_date",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "evntstart_date",
						 weekNumbers   	: true,
						 bottomBar		: true,				 
						 onSelect		: function() {
												this.hide();
													document.getElementById('evntstart_date').value=this.selection.print("%Y-%m-%d");
													emaildash.showMails(
																document.getElementById('mail_address').value,
																document.getElementById('client_name').value,
																document.getElementById('evntstart_date').value,
																document.getElementById('evntend_date').value,
																document.getElementById('archive').value,
																
																document.getElementById('client_id').value,
																document.getElementById('body_txt').value,
																
																{preloader:'prl',
																onUpdate: function(response,root){
												                document.getElementById('show_info').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] }) }});												
														}
										   });			
							}
							start_cal();
							</script>
				</td>
				<td>
				   <a href="javascript:void(0);" 
				      onclick="javacsript:document.getElementById('evntstart_date').value = '';
					  					  emaildash.showMails(
														document.getElementById('mail_address').value,
														document.getElementById('client_name').value,
														document.getElementById('evntstart_date').value,
														document.getElementById('evntend_date').value,
														document.getElementById('archive').value,
														
														document.getElementById('client_id').value,
														document.getElementById('body_txt').value,
														
														{preloader:'prl',
														onUpdate: function(response,root){
														document.getElementById('show_info').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
														);"> <img src="images/trash.gif" border="0" /></a> to </td>
				<td width="13%">
				   <input type="text" name="evntend_date" id="evntend_date" value="<?php //echo $row_order['event_date']; ?>"/>
						 <script type="text/javascript">	 
						 function start_cal(){
						 new Calendar({
						 inputField   	: "evntend_date",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "evntend_date",
						 weekNumbers   	: true,
						 bottomBar		: true,				 
						 onSelect		: function() {
												this.hide();
													document.getElementById('evntend_date').value=this.selection.print("%Y-%m-%d");
													emaildash.showMails(
																document.getElementById('mail_address').value,
																document.getElementById('client_name').value,
																document.getElementById('evntstart_date').value,
																document.getElementById('evntend_date').value,
																document.getElementById('archive').value,
																
																document.getElementById('client_id').value,
																document.getElementById('body_txt').value,
																
																{preloader:'prl',
																onUpdate: function(response,root){
																	document.getElementById('show_info').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] }) }});												
														}
										   });			
							}
							start_cal();
							</script>
				</td>
				<td>
				   <a href="javascript:void(0);" 
				      onclick="javacsript:document.getElementById('evntend_date').value = '';
					  					  emaildash.showMails(
													document.getElementById('mail_address').value,
													document.getElementById('client_name').value,
													document.getElementById('evntstart_date').value,
													document.getElementById('evntend_date').value,
													document.getElementById('archive').value,
													
													document.getElementById('client_id').value,
													document.getElementById('body_txt').value,
													
													{preloader:'prl',
													onUpdate: function(response,root){
														document.getElementById('show_info').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
														);"> <img src="images/trash.gif" border="0" /></a>
				</td>
			 </tr>
			 <tr>
				<td>Archive :</td>
			    <td>
				   <select style="width:100%;" name="archive" id="archive" 
				                onchange="javascript:emaildash.showMails(
													document.getElementById('mail_address').value,
													document.getElementById('client_name').value,
													document.getElementById('evntstart_date').value,
													document.getElementById('evntend_date').value,
													document.getElementById('archive').value,
													
													document.getElementById('client_id').value,
													document.getElementById('body_txt').value,
													
													{preloader:'prl',
													onUpdate: function(response,root){
														document.getElementById('show_info').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
														);">
						  <option value="">--Select--</option>
						  
				</select>
				</td>
				<td>Client ID :</td>
			    <td>
				   <input type="text" name="client_id" id="client_id" 
							onchange="javascript:emaildash.showMails(
													document.getElementById('mail_address').value,
													document.getElementById('client_name').value,
													document.getElementById('evntstart_date').value,
													document.getElementById('evntend_date').value,
													document.getElementById('archive').value,
													
													document.getElementById('client_id').value,
													document.getElementById('body_txt').value,
													
													{preloader:'prl',
													onUpdate: function(response,root){
														document.getElementById('show_info').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
														);"/>
				</td>
				<td colspan="5">&nbsp;</td>
			 </tr>
			 <tr>
			   <td>Body :</td>
			   <td>
			     <input type="text" name="body_txt" id="body_txt" 
							onchange="javascript:emaildash.showMails(
														document.getElementById('mail_address').value,
														document.getElementById('client_name').value,
														document.getElementById('evntstart_date').value,
														document.getElementById('evntend_date').value,
														document.getElementById('archive').value,
														
														document.getElementById('client_id').value,
														document.getElementById('body_txt').value,
														
														{preloader:'prl',
														onUpdate: function(response,root){
														document.getElementById('show_info').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
														);"/>
			   </td>
			 </tr>
           </table>
	       </form>
  		<?php    
        $html = ob_get_contents();
		ob_end_clean();
		return $html;    
    }   //////end of function SearchFct
	
	
	 function showMails() {
	   ob_start(); ?>
	   <table class="table">
	     <tr>
		   <td>
		     <input type="checkbox" name="check" id="check" />
		   </td>
		   <td>
		     <a href="javescript:void(0);"><img src="images/mail-search.png" alt="unread mail" /></a>
		   </td>
		   <td>&nbsp;
		     
		   </td>
		   <td>
		     <?php echo date("m/d/Y"); ?>
		   </td>
		   <td>
		     <?php echo date("h:i:s"); ?>
		   </td>
		 </tr>
	   
	   </table>
	   <?php
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
				
	}//////end of function showMails
	
	 function mailContent() {
	   ob_start(); ?>
	   <table width="90%" class="table">
		   <tr>
			  <th>From :</th>
			  <td>
			   <input type="text" name="from"  id="from" value="" />
			  </td>
		   </tr>
		   <tr>
			  <th>To :</th>
			  <td>
			    <input type="text" name="to"  id="to" value="" />
			  </td>
		  </tr>
		  
		  <tr>
			  <th>Subject:</th>
			  <td>
			    <input type="text" name="subject" id="subject" value="" />
			  </td>
		  </tr>
		  <tr>
			  <th>&nbsp;</th>
			  <td>
			    <div id='user'><?php //echo $name; ?>
			      <textarea name="message" id="message" cols="40" rows="8" >Respected Sir/Madam,
			       Thanks and Regards.
			      </textarea>
			  </div>
			  </td>
		  </tr>
		  <tr>
			  <td colspan="2">&nbsp;</td>
		  </tr>
		  <tr>
		     <th></th>
			 <td align="right">Signature <select name="signature" id="signature">
			                 <option value="">default</option>
						   </select>
			 </td>
		  </tr>
		</table>
	   
	   
	   <?php
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
				
	}//////end of function mailContent
	
  }//////end of class
?>