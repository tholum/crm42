<?php
class case_client {

   function dispaly_search_panel(){
      ob_start();
	  $formName = "casedashboard_search";?>
	  
	  <form name="<?php echo $formName;?>" method="post" action="">
			 <table class="casedashboard_search_panel" >
					<tr>
					   <td>Case #</td>
					   <td>
							<input type="text" name="mail_address" id="mail_address"
								onchange="javascript:var get_info = get_mail_info();
											  emaildash.display_email_by_module(
															document.getElementById('client_name').value,
															document.getElementById('client_id').value,
															get_info,
														   
															{preloader:'prl',
															onUpdate: function(response,root){
															document.getElementById('show_info').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
															);"/>
					   </td>
					   <td>Client #</td>
					   <td>
							<input type="text" name="client_name" id="client_name"
								onchange="javascript:var get_info = get_mail_info();
											   emaildash.display_email_by_module(
																		document.getElementById('client_name').value,
																		document.getElementById('client_id').value,
																		get_info,
																			   
																		{preloader:'prl',
																		onUpdate: function(response,root){
																	document.getElementById('show_info').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
																);"/>
							</td>
						   
						<td>Created From:</td>
							<td width="13%">
							   <input type="text" name="evntstart_date" id="evntstart_date" value="<?php //echo $row_order['event_date']; ?>"/>
										 <script type="text/javascript">        
										 function start_cal(){
										 new Calendar({
										 inputField     : "evntstart_date",
										 dateFormat             : "%Y-%m-%d",
										 trigger                : "evntstart_date",
										 weekNumbers    : true,
										 bottomBar              : true,                          
										 onSelect               : function() {
											this.hide();
													document.getElementById('evntstart_date').value=this.selection.print("%Y-%m-%d");
													var get_info = get_mail_info();
													emaildash.display_email_by_module(
																		document.getElementById('client_name').value,
																		document.getElementById('client_id').value,
																		get_info,
																	   
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
											  var get_info = get_mail_info();
													   emaildash.display_email_by_module(
																			document.getElementById('client_name').value,
																			document.getElementById('client_id').value,
																			get_info,
																		   
																			{preloader:'prl',
																			onUpdate: function(response,root){
																	document.getElementById('show_info').innerHTML=response;
																	$('#search_table')
																	.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
																	);"> <img src="images/trash.gif" border="0" /></a> to </td>
							<td>
							   <input type="text" name="evntend_date" id="evntend_date" value="<?php //echo $row_order['event_date']; ?>"/>
								   <script type="text/javascript">        
										 function start_cal(){
										 new Calendar({
										 inputField     : "evntstart_date",
										 dateFormat             : "%Y-%m-%d",
										 trigger                : "evntstart_date",
										 weekNumbers    : true,
										 bottomBar              : true,                          
										 onSelect               : function() {
											this.hide();
													document.getElementById('evntstart_date').value=this.selection.print("%Y-%m-%d");
													var get_info = get_mail_info();
													emaildash.display_email_by_module(
																		document.getElementById('client_name').value,
																		document.getElementById('client_id').value,
																		get_info,
																	   
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
													  var get_info = get_mail_info();
														emaildash.display_email_by_module(
																			document.getElementById('client_name').value,
																			document.getElementById('client_id').value,
																			get_info,
																   
																	{preloader:'prl',
																	onUpdate: function(response,root){
																document.getElementById('show_info').innerHTML=response;
																$('#search_table')
																.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
																		);"> <img src="images/trash.gif" border="0" /></a>
							</td>
					 </tr>
					 <tr>
						<td>Group #</td>
						<td>
						   <select style="width:94%;" name="archive" id="archive"
									onchange="javascript:var get_info = get_mail_info();
												   emaildash.display_email_by_module(
																	document.getElementById('client_name').value,
																	document.getElementById('client_id').value,
																	get_info,
														   
															{preloader:'prl',
															onUpdate: function(response,root){
															document.getElementById('show_info').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
										);">
									  <option value="">--Select--</option>
									  <?php //echo $this->array2options( $this->get_dropdown(DROPDOWN_OPTION, 'Archive Options' ), 'identifier', 'name' ); ?>
						</select>
						</td>
						<td>Client Name</td>
						<td>
						   <input type="text" name="client_id" id="client_id"
									onchange="javascript:var get_info = get_mail_info();
									   emaildash.display_email_by_module(
																document.getElementById('client_name').value,
																document.getElementById('client_id').value,
																get_info,
													   
														{preloader:'prl',
														onUpdate: function(response,root){
														document.getElementById('show_info').innerHTML=response;
														$('#search_table')
														.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
																);"/>
						</td>
						<td>Status</td>
						<td>
						   <select style="width:94%;" name="read" id="read"
									 onchange="javascript:var get_info = get_mail_info();
												   emaildash.display_email_by_module(
																			document.getElementById('client_name').value,
																			document.getElementById('client_id').value,
																			get_info,
																   
																	{preloader:'prl',
																	onUpdate: function(response,root){
															document.getElementById('show_info').innerHTML=response;
															$('#search_table')
															.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
																	);">
	  <option value="">--Select--</option>
	  <?php //echo $this->array2options( $this->get_dropdown(DROPDOWN_OPTION, 'read Options' ), 'identifier', 'name' ); ?>
						   </select>
						</td>
						<td colspan="3"></td>
					 </tr>
					 <tr>
					   <td>Phone #</td>
					   <td>
						 <input type="text" name="body_txt" id="body_txt"
									onchange="javascript:var get_info = get_mail_info();
											   emaildash.display_email_by_module(
																		document.getElementById('client_name').value,
																		document.getElementById('client_id').value,
																		get_info,
																	   
																		{preloader:'prl',
																		onUpdate: function(response,root){
																document.getElementById('show_info').innerHTML=response;
																$('#search_table')
																.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
																		);"/>
					   </td>
					   <td>CS Rep Owner</td>
					   <td>
						 <input type="text" name="body_txt" id="body_txt"
									onchange="javascript:var get_info = get_mail_info();
											   emaildash.display_email_by_module(
																		document.getElementById('client_name').value,
																		document.getElementById('client_id').value,
																		get_info,
																	   
																		{preloader:'prl',
																		onUpdate: function(response,root){
																document.getElementById('show_info').innerHTML=response;
																$('#search_table')
																.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
															);"/>
					   </td>
					   <td colspan="5"></td>
					 </tr>
					 <tr>
					   <td>Order #</td>
					   <td>
						 <input type="text" name="body_txt" id="body_txt"
									onchange="javascript:var get_info = get_mail_info();
											   emaildash.display_email_by_module(
																		document.getElementById('client_name').value,
																		document.getElementById('client_id').value,
																		get_info,
																	   
																		{preloader:'prl',
																		onUpdate: function(response,root){
																document.getElementById('show_info').innerHTML=response;
																$('#search_table')
																.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
																		);"/>
					   </td>
					   <td>Priority #</td>
					   <td>
						 <input type="text" name="body_txt" id="body_txt"
									onchange="javascript:var get_info = get_mail_info();
											   emaildash.display_email_by_module(
																		document.getElementById('client_name').value,
																		document.getElementById('client_id').value,
																		get_info,
																	   
																		{preloader:'prl',
																		onUpdate: function(response,root){
																document.getElementById('show_info').innerHTML=response;
																$('#search_table')
																.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]] })}}
															);"/>
					   </td>
					   <td colspan="5"></td>
					 </tr>
	        </table>
        </form>
   
   
   

   <?php
     $html=ob_get_contents();
	 ob_end_clean();
	 return $html;
    }




}
?>