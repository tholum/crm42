<?php

class OrderSearch{

var $db;
var $ad;
var $company_id;
var $validity;

	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
	}

    function SearchOrder() {
       ob_start();
	   $formName = "frm_search";?>
 	   <form name="<?php echo $formName;?>" method="post" action="">
		 <table width="100%" class="table" >
			<tr>
			   <td>Order</td>
			   <td width="13%">
			        <input type="text" name="order_txt" id="order_txt" 
							onchange="javascript:ordersearch.showOrderSearch(
										document.getElementById('order_txt').value,
										document.getElementById('customer_txt').value,
										document.getElementById('csr_type').value,
										document.getElementById('evntstart_date').value,
										document.getElementById('evntend_date').value,
										document.getElementById('status_typ').value,
										document.getElementById('creat_start_date').value,
										document.getElementById('creat_end_date').value,
										document.getElementById('ship_start_date').value,
										document.getElementById('ship_end_date').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} })}}
												);"/>
			   </td>
			   <td>Customer</td>
			   <td width="13%">
					<input type="text" name="customer_txt" id="customer_txt" 
							onchange="javascript:ordersearch.showOrderSearch(
										document.getElementById('order_txt').value,
										document.getElementById('customer_txt').value,
										document.getElementById('csr_type').value,
										document.getElementById('evntstart_date').value,
										document.getElementById('evntend_date').value,
										document.getElementById('status_typ').value,
										document.getElementById('creat_start_date').value,
										document.getElementById('creat_end_date').value,
										document.getElementById('ship_start_date').value,
										document.getElementById('ship_end_date').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} })}}
												);"/>
				</td>
				<td>CSR</td>
				<td width="13%">
				     <select style="width:100%;" name="csr_type" id="csr_type" 
				                onchange="javascript:ordersearch.showOrderSearch(
										document.getElementById('order_txt').value,
										document.getElementById('customer_txt').value,
										document.getElementById('csr_type').value,
										document.getElementById('evntstart_date').value,
										document.getElementById('evntend_date').value,
										document.getElementById('status_typ').value,
										document.getElementById('creat_start_date').value,
										document.getElementById('creat_end_date').value,
										document.getElementById('ship_start_date').value,
										document.getElementById('ship_end_date').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} })}}
												);">
					    <option value="">--Select--</option>
					    <?php $sql_csr = "Select * from ".TBL_USER;
						$result_csr = $this->db->query($sql_csr,__FILE__,__LINE__);		
						while($row_csr=$this->db->fetch_array($result_csr)){?>
						<option value="<?php echo $row_csr[user_id];?>">
						<?php echo $row_csr[first_name].' '.$row_csr[last_name];?>
						</option>
						<?php } ?>
				    </select>
				</td>
			    <td>Event Date</td>
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
													ordersearch.showOrderSearch(
																document.getElementById('order_txt').value,
																document.getElementById('customer_txt').value,
																document.getElementById('csr_type').value,
																document.getElementById('evntstart_date').value,
																document.getElementById('evntend_date').value,
																document.getElementById('status_typ').value,
																document.getElementById('creat_start_date').value,
																document.getElementById('creat_end_date').value,
																document.getElementById('ship_start_date').value,
																document.getElementById('ship_end_date').value,
													
														{preloader:'prl',onUpdate: function(response,root){
														document.getElementById('task_area').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} }) }});								
														}
										   });			
							}
							start_cal();
						</script>
				</td>
				<td>
				   <a href="javascript:void(0);" 
				      onclick="javacsript:document.getElementById('evntstart_date').value = '';
							   ordersearch.showOrderSearch(
										document.getElementById('order_txt').value,
										document.getElementById('customer_txt').value,
										document.getElementById('csr_type').value,
										document.getElementById('evntstart_date').value,
										document.getElementById('evntend_date').value,
										document.getElementById('status_typ').value,
										document.getElementById('creat_start_date').value,
										document.getElementById('creat_end_date').value,
										document.getElementById('ship_start_date').value,
										document.getElementById('ship_end_date').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} })}}
												);"> 
					<img src="images/trash.gif" border="0" /></a> to </td>
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
													ordersearch.showOrderSearch(
																document.getElementById('order_txt').value,
																document.getElementById('customer_txt').value,
																document.getElementById('csr_type').value,
																document.getElementById('evntstart_date').value,
																document.getElementById('evntend_date').value,
																document.getElementById('status_typ').value,
																document.getElementById('creat_start_date').value,
																document.getElementById('creat_end_date').value,
																document.getElementById('ship_start_date').value,
																document.getElementById('ship_end_date').value,
													
														{preloader:'prl',onUpdate: function(response,root){
														document.getElementById('task_area').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} }) }});										
														}
										   });			
							}
							start_cal();
							</script>
				</td>
				<td>
				   <a href="javascript:void(0);" 
				      onclick="javacsript:document.getElementById('evntend_date').value = '';
							   ordersearch.showOrderSearch(
										document.getElementById('order_txt').value,
										document.getElementById('customer_txt').value,
										document.getElementById('csr_type').value,
										document.getElementById('evntstart_date').value,
										document.getElementById('evntend_date').value,
										document.getElementById('status_typ').value,
										document.getElementById('creat_start_date').value,
										document.getElementById('creat_end_date').value,
										document.getElementById('ship_start_date').value,
										document.getElementById('ship_end_date').value,
										
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} })}}
												);"> 
					<img src="images/trash.gif" border="0" /></a>
				</td>
			 </tr>
			 <tr>
			    <td colspan="4">&nbsp;</td>
				<td>Status</td>
			    <td>
				   <select style="width:100%;" name="status_typ" id="status_typ" 
				                onchange="javascript:ordersearch.showOrderSearch(
										document.getElementById('order_txt').value,
										document.getElementById('customer_txt').value,
										document.getElementById('csr_type').value,
										document.getElementById('evntstart_date').value,
										document.getElementById('evntend_date').value,
										document.getElementById('status_typ').value,
										document.getElementById('creat_start_date').value,
										document.getElementById('creat_end_date').value,
										document.getElementById('ship_start_date').value,
										document.getElementById('ship_end_date').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} })}}
												);">
				  <option value="In Progress">In Progress</option>
				  <option value="Complete">Complete</option>
				</select>
				</td>
				<td>Created Date</td>
			    <td>
				   <input type="text" name="creat_start_date" id="creat_start_date" value="<?php //echo $row_order['event_date']; ?>"/>
						 <script type="text/javascript">	 
						 function start_cal(){
						 new Calendar({
						 inputField   	: "creat_start_date",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "creat_start_date",
						 weekNumbers   	: true,
						 bottomBar		: true,				 
						 onSelect		: function() {
												this.hide();
													document.getElementById('creat_start_date').value=this.selection.print("%Y-%m-%d");
													ordersearch.showOrderSearch(
																document.getElementById('order_txt').value,
																document.getElementById('customer_txt').value,
																document.getElementById('csr_type').value,
																document.getElementById('evntstart_date').value,
																document.getElementById('evntend_date').value,
																document.getElementById('status_typ').value,
																document.getElementById('creat_start_date').value,
																document.getElementById('creat_end_date').value,
																document.getElementById('ship_start_date').value,
																document.getElementById('ship_end_date').value,
													
														{preloader:'prl',onUpdate: function(response,root){
														document.getElementById('task_area').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} }) }});												
														}
										   });			
							}
							start_cal();
							</script>
				</td>
				<td>
				   <a href="javascript:void(0);" 
				      onclick="javacsript:document.getElementById('creat_start_date').value = '';
							   ordersearch.showOrderSearch(
										document.getElementById('order_txt').value,
										document.getElementById('customer_txt').value,
										document.getElementById('csr_type').value,
										document.getElementById('evntstart_date').value,
										document.getElementById('evntend_date').value,
										document.getElementById('status_typ').value,
										document.getElementById('creat_start_date').value,
										document.getElementById('creat_end_date').value,
										document.getElementById('ship_start_date').value,
										document.getElementById('ship_end_date').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} })}}
												);"> 
					 <img src="images/trash.gif" border="0" /></a> to </td>
				<td>
				   <input type="text" name="creat_end_date" id="creat_end_date" value="<?php //echo $row_order['event_date']; ?>"/>
						 <script type="text/javascript">	 
						 function start_cal(){
						 new Calendar({
						 inputField   	: "creat_end_date",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "creat_end_date",
						 weekNumbers   	: true,
						 bottomBar		: true,				 
						 onSelect		: function() {
												this.hide();
													document.getElementById('creat_end_date').value=this.selection.print("%Y-%m-%d");
													ordersearch.showOrderSearch(
																document.getElementById('order_txt').value,
																document.getElementById('customer_txt').value,
																document.getElementById('csr_type').value,
																document.getElementById('evntstart_date').value,
																document.getElementById('evntend_date').value,
																document.getElementById('status_typ').value,
																document.getElementById('creat_start_date').value,
																document.getElementById('creat_end_date').value,
																document.getElementById('ship_start_date').value,
																document.getElementById('ship_end_date').value,
													
														{preloader:'prl',onUpdate: function(response,root){
														document.getElementById('task_area').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} }) }});												
														}
										   });			
							}
							start_cal();
							</script>
				</td>
				<td>
				   <a href="javascript:void(0);" 
				      onclick="javacsript:document.getElementById('creat_end_date').value = '';
							   ordersearch.showOrderSearch(
										document.getElementById('order_txt').value,
										document.getElementById('customer_txt').value,
										document.getElementById('csr_type').value,
										document.getElementById('evntstart_date').value,
										document.getElementById('evntend_date').value,
										document.getElementById('status_typ').value,
										document.getElementById('creat_start_date').value,
										document.getElementById('creat_end_date').value,
										document.getElementById('ship_start_date').value,
										document.getElementById('ship_end_date').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} })}}
												);"> 
					 <img src="images/trash.gif" border="0" /></a>
			    </td>
			 </tr>
			 <tr>
			   <td colspan="6">&nbsp;</td>
			   <td>Ship Date</td>
			   <td>
			      <input type="text" name="ship_start_date" id="ship_start_date" value="<?php //echo $row_order['event_date']; ?>"/>
						 <script type="text/javascript">	 
						 function start_cal(){
						 new Calendar({
						 inputField   	: "ship_start_date",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "ship_start_date",
						 weekNumbers   	: true,
						 bottomBar		: true,				 
						 onSelect		: function() {
												this.hide();
													document.getElementById('ship_start_date').value=this.selection.print("%Y-%m-%d");
													ordersearch.showOrderSearch(
																document.getElementById('order_txt').value,
																document.getElementById('customer_txt').value,
																document.getElementById('csr_type').value,
																document.getElementById('evntstart_date').value,
																document.getElementById('evntend_date').value,
																document.getElementById('status_typ').value,
																document.getElementById('creat_start_date').value,
																document.getElementById('creat_end_date').value,
																document.getElementById('ship_start_date').value,
																document.getElementById('ship_end_date').value,
													
														{preloader:'prl',onUpdate: function(response,root){
														document.getElementById('task_area').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} }) }});												
														}
										   });			
							}
							start_cal();
							</script>
			   </td>
			   <td>
			      <a href="javascript:void(0);" 
				      onclick="javacsript:document.getElementById('ship_start_date').value = '';
							   ordersearch.showOrderSearch(
										document.getElementById('order_txt').value,
										document.getElementById('customer_txt').value,
										document.getElementById('csr_type').value,
										document.getElementById('evntstart_date').value,
										document.getElementById('evntend_date').value,
										document.getElementById('status_typ').value,
										document.getElementById('creat_start_date').value,
										document.getElementById('creat_end_date').value,
										document.getElementById('ship_start_date').value,
										document.getElementById('ship_end_date').value,
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} })}}
												);"> 
					 <img src="images/trash.gif" border="0" /></a> to </td>
			   <td>
				  <input type="text" name="ship_end_date" id="ship_end_date" value="<?php //echo $row_order['event_date']; ?>"/>
						 <script type="text/javascript">	 
						 function start_cal(){
						 new Calendar({
						 inputField   	: "ship_end_date",
						 dateFormat		: "%Y-%m-%d",
						 trigger		: "ship_end_date",
						 weekNumbers   	: true,
						 bottomBar		: true,				 
						 onSelect		: function() {
												this.hide();
													document.getElementById('ship_end_date').value=this.selection.print("%Y-%m-%d");
													ordersearch.showOrderSearch(
																document.getElementById('order_txt').value,
																document.getElementById('customer_txt').value,
																document.getElementById('csr_type').value,
																document.getElementById('evntstart_date').value,
																document.getElementById('evntend_date').value,
																document.getElementById('status_typ').value,
																document.getElementById('creat_start_date').value,
																document.getElementById('creat_end_date').value,
																document.getElementById('ship_start_date').value,
																document.getElementById('ship_end_date').value,
													
														{preloader:'prl',onUpdate: function(response,root){
														document.getElementById('task_area').innerHTML=response;
													$('#search_table')
													.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} }) }});												
														}
										   });			
							}
							start_cal();
							</script>
			   </td>
			   <td>
			      <a href="javascript:void(0);" 
				      onclick="javacsript:document.getElementById('ship_end_date').value = '';
							   ordersearch.showOrderSearch(
										document.getElementById('order_txt').value,
										document.getElementById('customer_txt').value,
										document.getElementById('csr_type').value,
										document.getElementById('evntstart_date').value,
										document.getElementById('evntend_date').value,
										document.getElementById('status_typ').value,
										document.getElementById('creat_start_date').value,
										document.getElementById('creat_end_date').value,
										document.getElementById('ship_start_date').value,
										document.getElementById('ship_end_date').value,
										
											{preloader:'prl',onUpdate: function(response,root){
												document.getElementById('task_area').innerHTML=response;
											$('#search_table')
											.tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],headers: {5:{sorter: false}} })}}
												);"> 
					 <img src="images/trash.gif" border="0" /></a>
			   </td>
			 </tr>
           </table>
	    </form>
  		<?php    
        $html = ob_get_contents();
		ob_end_clean();
		return $html;    
    }   //////end of function SearchOrder

    function showOrderSearch($order_id='',$customer='',$csr='',$event_start_date='',$event_end_date='',$status='',$creat_start_date='',$creat_end_date='',$ship_start_date='',$ship_end_date='') {
	   ob_start();
			
			$sql = "SELECT a.order_id, a.event_date, a.status, a.grant_total, a.vendor_contact_id, a.ship_date, a.created, c.company_name, a.csr";
			
			$sql .= " FROM erp_order a, contacts c";
			if($csr) $sql .= " ,".TBL_USER." d";
			
			$sql .= " where a.vendor_contact_id = c.contact_id";			
			
			if($order_id){
			$sql.=" and a.order_id like '%$order_id%' "; }
			
			if($customer){
			$sql.=" and c.first_name like '%$customer%' "; }
			
			if($csr){
			$sql.=" and d.user_id = '$csr'"; }
			
			if($event_start_date != '' and $event_end_date == ''){
			$sql.=" and a.event_date >= '$event_start_date' "; }
			
			if($event_start_date == '' and $event_end_date != ''){
			$sql.=" and a.event_date <= '$event_end_date' "; }
			
			if($event_start_date != '' and $event_end_date != ''){
			$sql.=" and a.event_date BETWEEN '$event_start_date' and '$event_end_date' "; }
			
			if($status){
			$sql.=" and status = '$status' "; }
			
			if($creat_start_date != '' and $creat_end_date == ''){
			$sql.=" and a.created >= '$creat_start_date' "; }
			
			if($creat_start_date == '' and $creat_end_date != ''){
			$sql.=" and a.created <= '$creat_end_date' "; }
			
			if($creat_start_date != '' and $creat_end_date != ''){
			$sql.=" and a.created BETWEEN '$creat_start_date' and '$creat_end_date' "; }
			
			if($ship_start_date != '' and $ship_end_date == ''){
			$sql.=" and a.ship_date >= '$ship_start_date' "; }
			
			if($ship_start_date == '' and $ship_end_date != ''){
			$sql.=" and a.ship_date <= '$ship_end_date' "; }
			
			if($ship_start_date != '' and $ship_end_date != ''){
			$sql.=" and a.ship_date BETWEEN '$ship_start_date' and '$ship_end_date' "; }
			
			$sql.=" ORDER BY a.order_id ASC";
			
			$result= $this->db->query($sql,__FILE__,__LINE__);
			$total_rows=$this->db->num_rows($result); ?>
			<table id="search_table" class="event_form small_text" width="100%">
				<thead>
				   <tr>
					  <th width="12%">Order</th>
					  <th width="14%">Customer</th>
					  <th width="14%">Event Date</th>
					  <th width="13%">Ship Date</th>
					  <th width="9%">Status</th>
					  <th width="12%">CSR</th>
					  <th width="12%">Grand Total</th>
					  <th width="14%">Created</th>
				  </tr>
				</thead>
				<tbody>
				  <?php 
				  if($total_rows >0 ){
					 while( $row=$this->db->fetch_array($result) ){ 
					    $sql_csr = "SELECT first_name, last_name FROM ".TBL_USER." WHERE user_id = $row[csr]";
						$result_csr = $this->db->query($sql_csr,__FILE__,__LINE__);
						$row_csr = $this->db->fetch_array($result_csr); ?>
						 <tr>
							<td><a href="order.php?order_id=<?php echo $row[order_id];?>"><?php echo $row[order_id];?></a></td>
							<td><a href="contact_profile.php?contact_id=<?php echo $row[vendor_contact_id]; ?>"><?php echo $row[company_name];?></a></td>
							<td><?php echo $row[event_date]; ?></td>
							<td><?php echo $row[ship_date]; ?></td>
							<td><?php echo $row[status]; ?></td>
							<td><?php echo $row_csr[first_name].' '.$row_csr[last_name]; ?></td>
							<td><?php if($row[grant_total]) echo '$ '.number_format("$row[grant_total]");  ?></td>
							<td><?php echo $row[created]; ?></td>
						</tr> 
				<?php } 
				}
				else { ?>
					<tr><td colspan="8" align="center">No Record Found!!!!</td></tr>
			    <?php } ?>
			</tbody>
		</table>
		<?php
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
				
	}//////end of function showOrderSearch
  }//////end of class
?>
