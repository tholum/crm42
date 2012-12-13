<?php 
class ReprtBuilder{
	
	var $db;
	var $validity;
	var $Form;	
	
	function __construct(){
	  $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	  $this->validity = new ClsJSFormValidation();
	  $this->Form = new ValidateForm();
	}
	
	function customizeReportBuilder(){
		ob_start();
		$i=0;
		$sql = "show tables FROM ".DATABASE_NAME;
		$result = $this->db->query($sql,__FILE__,__lINE__);
		?>
		<form name="frm_report_builder" action="" method="post">
		<input name="cnt_option" id="cnt_option" type="hidden" value="" />
		<input name="cnt_search" id="cnt_search" type="hidden" value="1" />
		<input name="cnt_cross_tables" id="cnt_cross_tables" type="hidden" value="1" />
		<input name="search_report_id" id="search_report_id" type="hidden" value="" />
		<select name="field_name" style="display:none;" ></select>
		<input name="field_text" value="" type="hidden" />
		<select name="search_type" style="display:none"></select>
		<select style="display:none;" name="link_field"></select>
		<table width="60%" class="table"><tr>
		<th>Database Table:</th>
		<td>
		<select id="table_name" name="table_name" onChange="javascript:
												report_builder.returnSelection(																		
												document.frm_report_builder.table_name.value,
												'<?php echo $i; ?>',
												{preloader:'prl',target:'div_selections',
												onUpdate: function(response,root){
													document.getElementById('div_show_search').innerHTML='';
													document.getElementById('cross_search_div').innerHTML='';
													document.getElementById('div_result_report_builder').innerHTML='';	
													document.getElementById('cnt_option').value='1';	
													document.getElementById('cnt_search').value='1';	
													document.getElementById('cnt_cross_tables').value='1';	
													}
												});">
		<option value="" >--Select Table--</option><?php
		while($row = $this->db->fetch_array($result)){
			?><option value="<?php echo $row[0];?>"><?php echo $row[0];?></option><?php
		}
		?>
		</select>
		</td>
		<th>Report Title:</th>
		<td><input id="report_title" name="report_title" onChange="javascript:searchOption();"/></td>

		</tr></table>
		<div id="div_selections" style="padding:0px"></div>
		<div id="div_show_search" ></div>
		<div id="cross_search_div"></div>
		<a href="javascript:void(0);" onclick="javascript:searchOption();">Search</a>
		</form>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	

	
	function returnSelection($table_name,$i='',$search_report_id=''){
		ob_start();		
			
	
		$z = 0;
		if($search_report_id){
			$sql_r = "select * from tbl_search_report where search_report_id='$search_report_id'";
			$result_r = $this->db->query($sql_r,__FILE__,__lINE__); 
			$row_r = $this->db->fetch_array($result_r);
					
			$table_name =  $row_r[table_name];
			$report_title = $row_r[report_title];
			$fields = unserialize($row_r[fields]);
			$fieldHeads = unserialize($row_r[fieldHeads]);
			$field_types = unserialize($row_r[field_types]);
			$link_fields = unserialize($row_r[link_fields]);
			$link_table_names = unserialize($row_r[link_table_names]);
			$link_cols = unserialize($row_r[link_cols]);
			$links = unserialize($row_r[links]);
			$link_field_texts = unserialize($row_r[link_field_texts]);
			$link_field_types = unserialize($row_r[link_field_types]);
			foreach($fields as $field){
				echo $this->showAddOption($table_name,$z,$search_report_id,$fields[$z],$fieldHeads[$z],$field_types[$z],$link_fields,$link_table_names,$link_cols,$link_field_texts,$link_field_types,$links,count($fields),count($link_cols)); 
				$z++;
			}
		}
		else {
			echo $this->showAddOption($table_name,$i);
		} 		
		?>
		<table width="100%" class="table">
		<tr>	
			<td align="center">
					<a href="javascript:void(0);" onClick="javascript: 
													var i  = document.getElementById('cnt_option').value;
													var trgt = 'div_selections_' + i;
													report_builder.showAddOption(
														'<?php echo $table_name; ?>',
														i,
														{target:trgt,preloader:'prl'});">add</a>
			</td>	
		</tr>
		</table>
		<?php echo $this->returnAddCrossTableLink($table_name,$i='');?>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	
	
	function showAddOption($table_name,$i='',$search_report_id='',$field='',$fieldHead='',$field_type='',$link_fields= array(),$link_table_names= array(),$link_cols= array(),$link_field_texts= array(),$link_field_types= array(),$links=array(),$cnt='',$cnt_cross=''){	    
		ob_start();
		$sql = "show fields FROM $table_name";		
    	$result1 = $this->db->query($sql,__FILE__,__lINE__);			
	
		?>
		<script>document.getElementById('cnt_option').value = <?php echo ($i+1);?>;</script>
		<table class="table" width="100%" id="tbl_<?php echo $i; ?>">
		<?php if($i <= 0){ ?>
		<tr>
		    <th width="15%">&nbsp;</th>
			<th width="15%">Select Field</th>
			<th width="20%">Field Head</th>
			<th width="20%">Filter Builder Searchable</th>
		    <th width="15%">Search Type</th>
			<th width="15%">&nbsp;</th>
		</tr>
		<?php } ?>
		<tr>
		
			<td width="15%"><?php echo 'Selection '.($i+1);?></td>
			<td width="15%">
				<select id="field_name" name="field_name">
					<option value="" >--Select Field--</option>
					<?php
					while($row = $this->db->fetch_array($result1)){
						?><option value="<?php echo $row[0];?>" 
						<?php if($row[0]==$field) echo 'selected="selected"';?>>
						<?php echo $row[0];?></option><?php
					}
					?>
				</select>
		  </td>
			<td width="20%">
				<input id="field_text" name="field_text" value="<?php echo $fieldHead;?>" />
		  </td>
			<td width="20%">
				<select id ="filter_builder_<?php echo $i; ?>" name="filter_builder" 
											onchange="javascript: 
												if (this.value == 'yes'){
													document.getElementById('search_type_<?php echo $i; ?>').style.display = 'block';
												}
												else {
													document.getElementById('search_type_<?php echo $i; ?>').style.display = 'none';
													document.getElementById('search_type_<?php echo $i; ?>').value = '';
													}">
					<option value="yes" <?php if($field_type) echo 'selected="selected"';?> >Yes</option>
					<option value="no" <?php if(!$field_type) echo 'selected="selected"';?>>No</option>
				</select>
			</td>											
			<td width="15%">
				<select name="search_type" id="search_type_<?php echo $i; ?>" <?php if(!$field_type) echo 'style="display:none"';?>>
					<option value="">--Select--</option>
					<option value="timestamp" <?php if($field_type == 'timestamp') echo 'selected="selected"';?>>Timestamp</option>
					<option value="text_num" <?php if($field_type == 'text_num') echo 'selected="selected"';?>>Text/Num</option>
					<option value="range" <?php if($field_type == 'range') echo 'selected="selected"';?>>Range</option>
					<option value="dropdown" <?php if($field_type == 'dropdown') echo 'selected="selected"';?>>Dropdown</option>
					<option value="cal_range" <?php if($field_type == 'cal_range') echo 'selected="selected"';?>>Caleder Range</option>
				</select>
			</td>
			<td width="15%">
			<?php 
			if($i==0) {?><a href="javascript:void(0);" onclick="javascript:
													var field_names = new Array();
													var field_texts = new Array();
													var field_types = new Array();
													
													var field_name = document.frm_report_builder.field_name;
													var field_text = document.frm_report_builder.field_text;
													var field_type = document.frm_report_builder.search_type;

													for(i = 0; i < field_name.length; i++) {
														if (field_name[i].value != '' && field_text[i].value != ''){
															field_names[i] = field_name[i].value;
															field_texts[i] = field_text[i].value;
															field_types[i] = field_type[i].value;
															}
														}												
													 report_builder.show_search(document.frm_report_builder.table_name.value,																																								                                                            document.frm_report_builder.report_title.value,
															field_names,field_texts,field_types,
															{target:'div_show_search',preloader:'prl',
															onUpdate: function(response,root){
																start_cal();
															}});" >Show Search Option</a> <?php }
			else { ?>
			<img src="images/trash.gif" border="0" onclick="javascript: 
													document.getElementById('tbl_<?php echo $i; ?>').style.display = 'none';
													document.getElementById('search_type_<?php echo $i; ?>').value = '';" /> 
			<?php }	?>
			</td>
		</tr>
		</table>
		<div id="div_selections_<?php echo ($i+1); ?>" style="padding:"></div>
		<?php 
		if($cnt and $i == ($cnt-1)){ 
			?>
			<div id="div_cross_tables<?php echo $cnt_cross; ?>">
			<?php
			$z = 0;		
			if($search_report_id){
				foreach($link_fields as $link_field){  
					echo $this->addCrossTable($table_name,($z+1),$link_fields[$z],$link_table_names[$z],$link_cols[$z],$link_field_texts[$z],$link_field_types[$z],$links[$z]); 
					$z++;
				}
			}
			?>
			</div>
			<?php
			}
		else if($i<1){ ?>
			<div id="div_cross_tables<?php echo ($i+1); ?>"></div>
		<?php }
		$i++;
		$html = ob_get_contents();
		ob_end_clean();
		return $html;			
	}
	
	function show_search($table_name='',$report_title='',$field_name=array(),$field_text=array(),$field_type=array(),$search_report_id=''){
		ob_start();
		?>
		<script>
		function searchOption(){
				var field_names = new Array();
				var field_texts = new Array();
				var field_types = new Array();
				var search_patterns = new Array();
				
				var field_name = document.frm_report_builder.field_name;
				var field_text = document.frm_report_builder.field_text;
				var field_type = document.frm_report_builder.search_type;
				var search_pattern = document.frm_report_builder.text_search_value;
				var j =0;
				for(i = 0; i < field_name.length; i++) {
					if(field_name[i].value != '' && field_text[i].value != ''){
						field_names[i] = field_name[i].value;
						field_texts[i] = field_text[i].value;
						field_types[i] = field_type[i].value;
						var from = 'rangefrom_' + j;
						var to = 'rangeto_' + j;
						var txt_search = 'text_search_' + j;
						var timstamp = 'timestamp' + j;
						var dropdown = 'dropdown'+ j;
						var cal_range_from = 'cal_rangefrom_'+ j;
						var cal_range_to = 'cal_rangeto_'+ j;

						if(field_types[i]=='range'){
							search_patterns[j] = document.getElementById(from).value + 
													'/' +
														document.getElementById(to).value;
						}
						else if(field_types[i]=='text_num'){
							search_patterns[j] = document.getElementById(txt_search).value;
							}
						else if(field_types[i]=='timestamp'){
							search_patterns[j] = document.getElementById(timstamp).value;
							}
						else if(field_types[i]=='cal_range'){
							search_patterns[j] = document.getElementById(cal_range_from).value + 
													'/' +
														document.getElementById(cal_range_to).value;
							}
						else if(field_types[i]=='dropdown'){
							search_patterns[j] = document.getElementById(dropdown).value;
							}
						else {
							search_patterns[j] ='';
						}
						j++;
						}
						
					
					}
				
				/********************************************************************************************/
				var link_fields = new Array();
				var link_table_names = new Array();
				var link_cols = new Array();
				var links = new Array();
				var link_field_texts = new Array();
				var link_field_types = new Array();
				var link_search_patterns = new Array();
				var j =0;
				var len = document.frm_report_builder.link_field.length;												
				for(i = 1; i < len; i++) {
					var link_field = 'link_field' + i;
					var link_table_name = 'link_table_name' + i;
					var link_col = 'link_col' + i;
					var links_ = 'link_' + i;
					var link_field_text = 'field_text_link' + i;
					var link_field_type = 'search_type_link' + i;
					var cross_text_search = 'cross_text_search_' + (i-1);
					var cross_range_from = 'cross_rangefrom_' + (i-1);
					var cross_range_to = 'cross_rangeto_' + (i-1);
					var cross_timestamp = 'cross_timestamp' + (i-1);
					var cross_dropdown = 'cross_dropdown'+(i-1);
					var cross_cal_range_from = 'cross_cal_rangefrom_'+(i-1);
					var cross_cal_range_to = 'cross_cal_rangeto_'+(i-1);
					
					if(document.getElementById(link_field).value !='' && document.getElementById(link_table_name).value !=''													&& document.getElementById(link_col).value !='' && document.getElementById(link_field_text).value){

							link_fields[j] = document.getElementById(link_field).value;
							link_table_names[j] = document.getElementById(link_table_name).value;
							link_cols[j] = document.getElementById(link_col).value;
							links[j] = document.getElementById(links_).value;
							link_field_texts[j] = document.getElementById(link_field_text).value ;
							link_field_types[j] = document.getElementById(link_field_type).value;
													
							if(document.getElementById(link_field_type).value=='range'){
								link_search_patterns[j] = document.getElementById(cross_range_from).value + 
														'/' +
															document.getElementById(cross_range_to).value;
								}
							else if(document.getElementById(link_field_type).value=='text_num'){
								link_search_patterns[j] = document.getElementById(cross_text_search).value;
							}
							else if(document.getElementById(link_field_type).value=='timestamp'){
								link_search_patterns[j] = document.getElementById(cross_timestamp).value;
							}
							else if(document.getElementById(link_field_type).value=='cal_range'){
								link_search_patterns[j] = document.getElementById(cross_cal_range_from).value + 
														'/' +
															document.getElementById(cross_cal_range_to).value;
							}
							else if(document.getElementById(link_field_type).value=='dropdown'){
								link_search_patterns[j] = document.getElementById(cross_dropdown).value;
							}
							else {
								link_search_patterns[j] = '';
							}
							j++;
						}
					}												
				
				/********************************************************************************************/				

				report_builder.returnSearchResult(100,
										  	document.getElementById('table_name').value,
										  	document.getElementById('report_title').value,
											field_names,
											field_texts,
											field_types,
											search_patterns,
											link_fields,
											link_table_names,
											link_cols,
											link_field_texts,
											link_field_types,
											link_search_patterns,
											document.getElementById('search_report_id').value,
											links,
											{target:'div_result_report_builder',preloader:'prl',
											onUpdate: function(response,root){
												$(function() {		
													$('#search_table') .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],} );																		
												});
												}					
											});
			}	
		</script>
		Filters :<br />
		<table class="table"> 
		<?php
		$i=0;
		foreach($field_type as $type){ ?>			
				<tr><?php
				if($type) echo '<td>'.$field_text[$i].'&nbsp;</td>'; 
				if($type == 'text_num'){ ?>
   					 <td colspan="2">
					 <input type="text" id="text_search_<?php echo $i;?>" name="text_search_value"/>
					 <img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('text_search_<?php echo $i;?>').value='';" />
					 </td>

				<?php } 
				else if($type == 'range'){ ?>
					<td>
					From:<input type="text" id="rangefrom_<?php echo $i;?>" name="range_from" />
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('rangefrom_<?php echo $i;?>').value='';" />
					</td>
					<td>
					To:<input type="text" id="rangeto_<?php echo $i;?>" name="range_to" />
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('rangeto_<?php echo $i;?>').value='';" />
					</td>
				<?php }
				
				else if($type == 'cal_range'){ ?>
					<td>
					From:<input type="text" id="cal_rangefrom_<?php echo $i;?>" name="cal_range_from" readonly=""/>
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cal_rangefrom_<?php echo $i;?>').value='';" />
					</td>
					<td>
					To:<input type="text" id="cal_rangeto_<?php echo $i;?>" name="cal_range_to" readonly="" />
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cal_rangeto_<?php echo $i;?>').value='';" />
					</td>
				<?php }
				else if($type == 'timestamp'){ ?>
					<td colspan="2">
					Timestamp:<input type="text" id="timestamp<?php echo $i;?>" name="timestamp" readonly="" />
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('timestamp<?php echo $i;?>').value='';" />
					</td>
				<?php } 
				else if($type == 'dropdown'){ ?>
					<td colspan="2"><?php
					$sql = "select distinct($field_name[$i]) FROM $table_name";
					$result = $this->db->query($sql,__FILE__,__lINE__); ?>
					<select id="dropdown<?php echo $i;?>" name="dropdown" >
					<option value="">--Select--</option>
					<?php while($row = $this->db->fetch_array($result)){ ?>
						<option value="<?php echo $row[$field_name[$i]];?>"><?php echo $row[$field_name[$i]];?></option>
					<?php } ?>
					</select>
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('dropdown<?php echo $i;?>').value='';" />
					</td>
				<?php }?>
					
		 		</tr>
		 <?php 
		 $i++; } ?>
		</table>

		<script>
			function start_cal()  { <?php 
				$i=0; 
				foreach($field_type as $type){ 
					if($type == 'cal_range'){ ?>
					  
					  var cal<?php echo $i;?>=new Calendar({
							  inputField   	: "cal_rangefrom_<?php echo $i;?>",
							  dateFormat	: "%Y-%m-%d",
							  trigger		: "cal_rangefrom_<?php echo $i;?>",
							  weekNumbers   : true,
							  bottomBar		: true,
							  showTime      : 12,
							  onSelect		: function() {
													this.hide();
													document.getElementById('cal_rangefrom_<?php echo $i;?>').value=this.selection.print("%Y-%m-%d");														
												},
					  });
					  
					  var cal<?php echo $i;?>=new Calendar({
							  inputField   	: "cal_rangeto_<?php echo $i;?>",
							  dateFormat	: "%Y-%m-%d",
							  trigger		: "cal_rangeto_<?php echo $i;?>",
							  weekNumbers   : true,
							  bottomBar		: true,
							  showTime      : 12,
							  onSelect		: function() {
													this.hide();
													document.getElementById('cal_rangeto_<?php echo $i;?>').value=this.selection.print("%Y-%m-%d");											
												},
					  });
					<?php } 
					
					else if($type == 'timestamp'){ ?>
		
					  var cal<?php echo $i;?>=new Calendar({
							  inputField   	: "timestamp<?php echo $i;?>",
							  dateFormat	: "%Y-%m-%d",
							  trigger		: "timestamp<?php echo $i;?>",
							  weekNumbers   : true,
							  bottomBar		: true,
							  showTime      : 12,
							  onSelect		: function() {
													this.hide();
													document.getElementById('timestamp<?php echo $i;?>').value=this.selection.print("%Y-%m-%d");														
												},
					  });
					<?php }
				$i++;
				}?>
			}
		<?php if($search_report_id) { ?> start_cal(); <?php } ?>
		</script><?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;		
	}
	
	function returnAddCrossTableLink($table_name,$i=''){
		ob_start();	?>	
		<table width="100%" class="table">
		<tr>	
			<td align="center">
					<a href="javascript:void(0);" onClick="javascript: 
													var i  = document.getElementById('cnt_cross_tables').value;
													var trgt = 'div_cross_tables' + i;
													report_builder.addCrossTable(
														'<?php echo $table_name; ?>',i,
														{target:trgt,preloader:'prl'});">add cross table</a>
			</td>	
		</tr>
		</table>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	
	function addCrossTable($table='',$i='',$link_field='',$link_table_name='',$link_col='',$link_field_text='',$link_field_type='',$link=''){
		ob_start();
		$sql = "show tables FROM ".DATABASE_NAME;
		$result = $this->db->query($sql,__FILE__,__lINE__);
		?>
		<script>document.getElementById('cnt_cross_tables').value = <?php echo ($i+1);?>;</script>
		<table width="100%" class="table" id="cross_tbl_<?php echo $i;?>">
		<?php 
		if($i<=1){ ?>
			<tr>
			<th>Linked Field</th>
			<th>Linked Table</th>
			<th>Linked Column</th>
			<th>Extract Column</th>
			<th>Column Head</th>
			<th>Search Required</th>
			<th>Search Type</th>
			<th>&nbsp;</th>
			</tr>
		<?php }
		
		?>
		
		
		<tr>
		<td width="11%">
		<?php
		echo $this->returnTableFields($table,'link_field','link_field'.$i,$link_field);
		?>		</td>
		<td width="12%">
		<select id="link_table_name<?php echo $i;?>" name="link_table_name" onchange="javascript :
															report_builder.returnTableFields(this.value,'link','link_<?php echo $i;?>',
																	{preloader:'prl',target:'link_column1_div<?php echo $i;?>'});
															report_builder.returnTableFields(this.value,'link_col','link_col<?php echo $i;?>',
																	{preloader:'prl',target:'link_column_div<?php echo $i;?>'});">
		<option value="" >--Select Table--</option><?php
		while($row = $this->db->fetch_array($result)){
			?><option value="<?php echo $row[0];?>" <?php if($row[0]==$link_table_name) echo 'selected="selected"';?> ><?php echo $row[0];?></option><?php
		}
		?>
		</select>
		</td>
		<td width="14%"><div id="link_column1_div<?php echo $i;?>">
		<?php if($link) echo $this->returnTableFields($link_table_name,$i,'link_'.$i,$link);?>
		</div></td>		

		<td width="16%"><div id="link_column_div<?php echo $i;?>">
		<?php if($link_col) echo $this->returnTableFields($link_table_name,$i,'link_col'.$i,$link_col);?>
		</div></td>
		<td width="15%">
			<input id="field_text_link<?php echo $i;?>" name="field_text_link"  value="<?php echo $link_field_text;?>"/>
		</td>
		<td width="7%">
			<select id ="filter_builder<?php echo $i; ?>" name="filter_builder" 
										onchange="javascript: 
											if (this.value == 'yes'){
												document.getElementById('search_type_link<?php echo $i; ?>').style.display = 'block';
											}
											else {
												document.getElementById('search_type_link<?php echo $i; ?>').style.display = 'none';
												document.getElementById('search_type_link<?php echo $i; ?>').value = '';
												}">
				<option value="yes" <?php if($link_field_type) echo 'selected="selected"';?> >Yes</option>
				<option value="no" <?php if(!$link_field_type) echo 'selected="selected"';?>>No</option>
			</select>
		</td>											
		<td width="13%">
			<select name="search_type_link" id="search_type_link<?php echo $i; ?>" <?php if(!$link_field_type) echo 'style="display:none;"';?> >
				<option value="">--Select--</option>
				<option value="timestamp" <?php if($link_field_type == "timestamp") echo 'selected="selected"';?>>Timestamp</option>
				<option value="text_num" <?php if($link_field_type == "text_num") echo 'selected="selected"';?>>Text/Num</option>
				<option value="range" <?php if($link_field_type == "range") echo 'selected="selected"';?>>Range</option>
				<option value="dropdown" <?php if($link_field_type == "dropdown") echo 'selected="selected"';?>>Dropdown</option>
				<option value="cal_range" <?php if($link_field_type == "cal_range") echo 'selected="selected"';?>>Caleder Range</option>
			</select>
		  </td>
			<td width="12%">
			<?php 
			if($i<=1) {?><a href="javascript:void(0);" onclick="javascript:
												var link_fields = new Array();
												var link_table_names = new Array();
												var links = new Array();
												var link_cols = new Array();
												var link_field_texts = new Array();
												var link_field_types = new Array();
												var j =0;
												var len = document.frm_report_builder.link_field.length;												
												for(i = 1; i < len; i++) {
													var link_field = 'link_field' + i;
													var link_table_name = 'link_table_name' + i;
													var link = 'link_' + i;
													var link_col = 'link_col' + i;
													var link_field_text = 'field_text_link' + i;
													var link_field_type = 'search_type_link' + i;
													if(document.getElementById(link_field).value !='' && document.getElementById(link_table_name).value !=''													&& document.getElementById(link_col).value !='' && document.getElementById(link).value !='' && document.getElementById(link_field_text).value !=''){

															link_fields[j] = document.getElementById(link_field).value;
															link_table_names[j] = document.getElementById(link_table_name).value;
															links[j] = document.getElementById(link).value;
															link_cols[j] = document.getElementById(link_col).value;
															link_field_texts[j] = document.getElementById(link_field_text).value ;
															link_field_types[j] = document.getElementById(link_field_type).value;
															j++;
														}
													}												
												  report_builder.show_cross_search(document.frm_report_builder.table_name.value,
												  									link_fields,
																					link_table_names,
																					link_cols,
																					link_field_texts,
																					link_field_types,
																					links,
												  								{target:'cross_search_div',preloader:'prl',
																				onUpdate: function(response,root){
																					cross_start_cal();
																				}});" >Show Cross Search Option</a><?php }
			else { ?>
			<img src="images/trash.gif" border="0" onclick="javascript: 
													document.getElementById('cross_tbl_<?php echo $i; ?>').style.display = 'none';
													document.getElementById('search_type_link<?php echo $i; ?>').value = '';" /> 
			<?php }	?>			</td>
		</tr></table>
		<div id="div_cross_tables<?php echo ($i+1); ?>"></div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;			
	}
	
	function returnTableFields($table='',$element_name='',$element_id='',$selected=''){
		ob_start();
		$sql1 = "show fields FROM $table";		
    	$result1 = $this->db->query($sql1,__FILE__,__lINE__);			
		?>
			<select id="<?php echo $element_id;?>" name="<?php echo $element_name;?>">
				<option value="" >--Select Field--</option>
				<?php
				while($row = $this->db->fetch_array($result1)){
					?><option value="<?php echo $row[0];?>" <?php if($row[0]==$selected) echo 'selected="selected"';?> >
					<?php echo $row[0];?></option><?php
				}
				?>
			</select>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;			
	}

	function show_cross_search($table_name='',$link_fields=array(),$link_table_names=array(),$link_cols=array(),$link_field_texts=array(),$link_field_types=array(),$link=array()){
		ob_start();
/*		echo '<br>Table';
		echo $table_name;
		echo '<br>title';
		echo $report_title;
		echo '<br>fields';
		print_r($fields);
		echo '<br>heads';
		print_r($fieldHeads);
		echo '<br>type';
		print_r($field_types);
		echo '<br>field_values';
		print_r($field_values);
		echo '<br>link_fields';
		print_r($link_fields);
		echo '<br>link_table_names';
		print_r($link_table_names);
		echo '<br>link_cols';
		print_r($link_cols);
		echo '<br>link_field_texts';
		print_r($link_field_texts);
		echo '<br>link_field_types';
		print_r($link_field_types);
		echo '<br>link_search_patterns';
		print_r($link_search_patterns);*/

		
		?>
		<table class="table"> 
		<?php
		$i=0;
		foreach($link_field_types as $type){ ?>			
				<tr><?php 
				if($type) echo '<td>'.$link_field_texts[$i].'&nbsp;</td>'; 
				if($type == 'text_num'){ ?>
   					 <td>
					 <input type="text" id="cross_text_search_<?php echo $i;?>" name="cross_text_search_value"/>
					 <img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cross_text_search_<?php echo $i;?>').value='';" />
					 </td>
				<?php } 
				else if($type == 'range'){ ?>
					<td>From:<input type="text" id="cross_rangefrom_<?php echo $i;?>" name="cross_range_from" />
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cross_rangefrom_<?php echo $i;?>').value='';" />
					To:<input type="text" id="cross_rangeto_<?php echo $i;?>" name="cross_range_to"  />
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cross_rangeto_<?php echo $i;?>').value='';" />
					</td>
				<?php }
				else if($type == 'cal_range'){ ?>
					<td>From:<input type="text" id="cross_cal_rangefrom_<?php echo $i;?>" name="cross_cal_range_from" readonly=""/>
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cross_cal_rangefrom_<?php echo $i;?>').value='';" />
					To:<input type="text" id="cross_cal_rangeto_<?php echo $i;?>" name="cross_cal_range_to" readonly="" />
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cross_cal_rangeto_<?php echo $i;?>').value='';" />
					</td>
				<?php }
				else if($type == 'timestamp'){ ?>
					<td>Timestamp:<input type="text" id="cross_timestamp<?php echo $i;?>" name="cross_timestamp" readonly="" />
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cross_timestamp<?php echo $i;?>').value='';" /></td>
				<?php }
				else if($type == 'dropdown'){ ?>
					<td><?php
					$sql = "select distinct($link_cols[$i]) FROM $link_table_names[$i]";
					$result = $this->db->query($sql,__FILE__,__lINE__); ?>
					<select id="cross_dropdown<?php echo $i;?>" name="dropdown" >
					<option value="">--Select--</option>
					<?php while($row = $this->db->fetch_array($result)){ ?>
						<option value="<?php echo $row[$link_cols[$i]];?>"><?php echo $row[$link_cols[$i]];?></option>
					<?php } ?>
					</select>
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cross_dropdown<?php echo $i;?>').value='';" />
					</td>
				<?php }
				?>
		 		</tr>
		 <?php 
		 $i++; } ?>
		</table>
		<script>
			function cross_start_cal()  { <?php 
				$i=0; 
				foreach($link_field_types as $type){ 
					if($type == 'cal_range'){ ?>
					  
					  var cross_cal<?php echo $i;?>=new Calendar({
							  inputField   	: "cross_cal_rangefrom_<?php echo $i;?>",
							  dateFormat	: "%Y-%m-%d",
							  trigger		: "cross_cal_rangefrom_<?php echo $i;?>",
							  weekNumbers   : true,
							  bottomBar		: true,
							  showTime      : 12,
							  onSelect		: function() {
													this.hide();
													document.getElementById('cross_cal_rangefrom_<?php echo $i;?>').value=this.selection.print("%Y-%m-%d");														
												},
					  });
					  
					  var cross_cal<?php echo $i;?>=new Calendar({
							  inputField   	: "cross_cal_rangeto_<?php echo $i;?>",
							  dateFormat	: "%Y-%m-%d",
							  trigger		: "cross_cal_rangeto_<?php echo $i;?>",
							  weekNumbers   : true,
							  bottomBar		: true,
							  showTime      : 12,
							  onSelect		: function() {
													this.hide();
													document.getElementById('cross_cal_rangeto_<?php echo $i;?>').value=this.selection.print("%Y-%m-%d");											
												},
					  });
					<?php } 
					
					else if($type == 'timestamp'){ ?>
		
					  var cross_cal<?php echo $i;?>=new Calendar({
							  inputField   	: "cross_timestamp<?php echo $i;?>",
							  dateFormat	: "%Y-%m-%d",
							  trigger		: "cross_timestamp<?php echo $i;?>",
							  weekNumbers   : true,
							  bottomBar		: true,
							  showTime      : 12,
							  onSelect		: function() {
													this.hide();
													document.getElementById('cross_timestamp<?php echo $i;?>').value=this.selection.print("%Y-%m-%d");														
												},
					  });
					<?php }
				$i++;
				}?>
			}
		<?php if($search_report_id) { ?> cross_start_cal(); <?php } ?>
		</script><?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;		
	}
	
	
	function returnSearchResult($limit='all',$table_name='',$report_title='',$fields=array(),$fieldHeads=array(),$field_types=array(),$field_values=array(),$link_fields=array(),$link_table_names=array(),$link_cols=array(),$link_field_texts=array(),$link_field_types=array(),$link_search_patterns=array(),$search_report_id='',$links= array()){
		
		ob_start();
		/*echo '<br>Table';
		echo $table_name;
		echo '<br>title';
		echo $report_title;
		echo '<br>fields';
		print_r($fields);
		echo '<br>heads';
		print_r($fieldHeads);
		echo '<br>type';
		print_r($field_types);
		echo '<br>field_values';
		print_r($field_values);
		echo '<br>link_fields';
		print_r($link_fields);
		echo '<br>link_table_names';
		print_r($link_table_names);
		echo '<br>link_cols';
		print_r($link_cols);
		echo '<br>links';
		print_r($links);
		echo '<br>link_field_texts';
		print_r($link_field_texts);
		echo '<br>link_field_types';
		print_r($link_field_types);
		echo '<br>link_search_patterns';
		print_r($link_search_patterns);*/

		$sql = "select ";
		$x = 0;
		foreach($fields as $field){
			if($x >0) $sql .=",";			
			$sql .=" a."."$fields[$x]";
			$x++;
		}
		
		$y = 0;
		foreach($link_table_names as $link_table_name){
			$sql .=", a".($y+1)."."."$link_cols[$y]";
			$y++;
		}

		if($x==0) $sql .= " * ";
		
		$sql .=" from $table_name"." a";
		$y=0;
		foreach($link_table_names as $link_table_name){			
			$sql .=", ".$link_table_name." a".($y+1);
			$y++;
		}

		$sql .=" where 1";
		
		$i=0;
		$j = 0;
		foreach($fields as $field){
		   if($field_values[$i] and $field_types[$i]){ 
		   	   //echo  strlen($field_values[$i]).'<br>';
			   if($field_types[$i]=='text_num'){
			   		$sql .=" and a."."$field like '%$field_values[$i]%'";
				}
				else if(($field_types[$i]=='range' or $field_types[$i]=='cal_range') and  strlen($field_values[$i]) > 1) {
					$range = explode('/',$field_values[$i]);
					if($range[0] and $range[1]) $sql .=" and a."."$field between '$range[0]' and '$range[1]'";
					else if($range[0] and !$range[1]) $sql .=" and a."."$field >= '$range[0]'";
					else if(!$range[0] and $range[1]) $sql .=" and a."."$field <= '$range[1]'";
				}
				else if($field_types[$i]=='timestamp') {
					$sql .=" and a."."$field >= '".strtotime($field_values[$i])."'";
				}
				else if($field_types[$i]=='dropdown') {
					$sql .=" and a."."$field = '".$field_values[$i]."'";
				}
			 }
		   $i++;
		 }
		 
		$i=0;
		foreach($link_table_names as $link_table_name){
		   if($link_search_patterns[$i] and $link_field_types[$i]){
	 	       if($link_field_types[$i]=='text_num'){
			   		$sql .=" and a".($i+1)."."."$link_cols[$i] like '%$link_search_patterns[$i]%'";
				}
				else if($link_field_types[$i]=='range' or $link_field_types[$i]=='cal_range'){
					$range = explode('/',$link_search_patterns[$i]);
					if($range[0] and $range[1]) $sql .=" and a".($i+1)."."."$link_cols[$i] between '$range[0]' and '$range[1]'";
					else if($range[0] and !$range[1]) $sql .=" and a".($i+1)."."."$link_cols[$i] >= '$range[0]'";
					else if(!$range[0] and $range[1]) $sql .=" and a".($i+1)."."."$link_cols[$i] <= '$range[1]'";
				}
				else if($link_field_types[$i]=='timestamp'){
					$sql .=" and a".($i+1)."."."$link_cols[$i] >= '".strtotime($link_search_patterns[$i])."'";
				}
				else if($link_field_types[$i]=='dropdown'){
					$sql .=" and a".($i+1)."."."$link_cols[$i] = '".$link_search_patterns[$i]."'";
				}
		   }
		   if($link_field_types[$i]) $sql .=" and a."."$link_fields[$i] = a".($i+1)."."."$links[$i]";
		   $i++;
		 }

		if($limit !='all') $sql .=" limit $limit";
		
		//echo $sql;
		$result = $this->db->query($sql,__FILE__,__lINE__);
		
if(!$search_report_id){
		?>
		<script>
		function saveSearchOption(){
				var field_names = new Array();
				var field_texts = new Array();
				var field_types = new Array();
				var search_patterns = new Array();
				
				var field_name = document.frm_report_builder.field_name;
				var field_text = document.frm_report_builder.field_text;
				var field_type = document.frm_report_builder.search_type;
				var search_pattern = document.frm_report_builder.text_search_value;
				var j =0;
				for(i = 0; i < field_name.length; i++) {
					if(field_name[i].value != '' && field_text[i].value != ''){
						field_names[i] = field_name[i].value;
						field_texts[i] = field_text[i].value;
						field_types[i] = field_type[i].value;
						var from = 'rangefrom_' + j;
						var to = 'rangeto_' + j;
						var txt_search = 'text_search_' + j;
						if(field_types[i]=='range'){
							search_patterns[j] = document.getElementById(from).value + 
													'/' +
														document.getElementById(to).value;
						}
						else if(field_types[i]=='text_num'){
							search_patterns[j] = document.getElementById(txt_search).value
							}
						j++;
						}
						
					
					}
				
				var link_fields = new Array();
				var link_table_names = new Array();
				var link_cols = new Array();
				var links = new Array();
				var link_field_texts = new Array();
				var link_field_types = new Array();
				var link_search_patterns = new Array();
				var j =0;
				var len = document.frm_report_builder.link_field.length;												
				for(i = 1; i < len; i++) {
					var link_field = 'link_field' + i;
					var link_table_name = 'link_table_name' + i;
					var link_col = 'link_col' + i;
					var links_ = 'link_' + i;
					var link_field_text = 'field_text_link' + i;
					var link_field_type = 'search_type_link' + i;
					var cross_text_search = 'cross_text_search_' + (i-1);
					var cross_range_from = 'cross_rangefrom_' + (i-1);
					var cross_range_to = 'cross_rangeto_' + (i-1);
					if(document.getElementById(link_field).value !='' && document.getElementById(link_table_name).value !=''													&& document.getElementById(link_col).value !='' && document.getElementById(link_field_text).value !='' && document.getElementById(link_field_type).value !=''){

							link_fields[j] = document.getElementById(link_field).value;
							link_table_names[j] = document.getElementById(link_table_name).value;
							link_cols[j] = document.getElementById(link_col).value;
							links[j] = document.getElementById(links_).value;
							link_field_texts[j] = document.getElementById(link_field_text).value ;
							link_field_types[j] = document.getElementById(link_field_type).value;
													
							if(document.getElementById(link_field_type).value=='range'){
								link_search_patterns[j] = document.getElementById(cross_range_from).value + 
														'/' +
															document.getElementById(cross_range_to).value;
								}
							else if(document.getElementById(link_field_type).value=='text_num'){
								link_search_patterns[j] = document.getElementById(cross_text_search).value;
							}
							j++;
						}
					}												
				

				report_builder.saveSearch(document.getElementById('table_name').value,
										  document.getElementById('report_title').value,
											field_names,
											field_texts,
											field_types,
											link_fields,
											link_table_names,
											link_cols,
											link_field_texts,
											link_field_types,
											document.getElementById('search_report_id').value,
											links,
											{preloader:'prl',target:'srch_div'});
		}	
		
		
		
		var field_names = new Array();
		var field_texts = new Array();
		var field_types = new Array();
		var search_patterns = new Array();
		<?php
		$k = 0;
		foreach($field_name as $field) {
			if($field_name[$k] != '' and $field_text[$k] != ''){ 
				?>
				field_names[<?php echo $k;?>] = '<?php echo $fields[$k]; ?>';
				field_texts[<?php echo $k;?>] = '<?php echo $fieldHeads[$k]; ?>';
				field_types[<?php echo $k;?>] = '<?php echo $field_types[$k]; ?>';
				<?php
			}
			$k++;
		}
		?>
		
		var k = <?php echo $k;?>;
		
		var link_fields = new Array();
		var link_table_names = new Array();
		var link_cols = new Array();
		var links = new Array();
		var link_field_texts = new Array();
		var link_field_types = new Array();
		var link_search_patterns = new Array();
		<?php 
		$j= 0;
		foreach($link_fields as $link_field) {
			
			if($link_fields[$j] !='' and $link_table_names[$j] !='' and $link_cols[$j] !=''and $link_field_texts[$j] !=''){ ?>
				link_fields[<?php echo $j;?>] = '<?php echo $link_fields[$j]; ?>';
				link_table_names[<?php echo $j;?>] = '<?php echo $link_table_names[$j]; ?>';
				link_cols[<?php echo $j;?>] = '<?php echo $link_cols[$j]; ?>';
				links[<?php echo $j;?>] = '<?php echo $links[$j]; ?>';
				link_field_texts[<?php echo $j;?>] = '<?php echo $link_field_texts[$j]; ?>';
				link_field_types[<?php echo $j;?>] = '<?php echo $link_field_types[$j]; ?>';
			<?php }
			$j++;
		}
		?>var j = <?php echo $j;?>;
		
		function searchOptionAll(){
				var y=0;
				for(x=0; x < k; x++) {
					if(field_names[x] != '' && field_texts[x] != ''){ 
						
						var from = 'rangefrom_' + y ;
						var to = 'rangeto_' + y ;
						var txt_search = 'text_search_' + y ;
						var timstamp = 'timestamp' + y ;
						var dropdown = 'dropdown' + y ;
						var cal_range_from = 'cal_rangefrom_' + y ;
						var cal_range_to = 'cal_rangeto_' + y ;	
						
						
						if(field_types[x]=='range'){ 
							search_patterns[y] = document.getElementById(from).value + 
													'/' +
														document.getElementById(to).value;
							}
						else if(field_types[x]=='text_num'){ 
							search_patterns[y] = document.getElementById(txt_search).value;
							}
						else if(field_types[x]=='timestamp'){ 
							search_patterns[y] = document.getElementById(timstamp).value;
							}
						else if(field_types[x]=='cal_range'){
							search_patterns[y] = document.getElementById(cal_range_from).value + 
													'/' +
														document.getElementById(cal_range_to).value;
							}
						else if(field_types[x]=='dropdown'){
							search_patterns[y] = document.getElementById(dropdown).value;
							}
						else {
							search_patterns[y] ='';
							}
						y++;
						}
				}
				

				var q=0;
				for(p=0; p < j; p++) {
				
					var cross_text_search = 'cross_text_search_' + p;
					var cross_range_from = 'cross_rangefrom_' + p;
					var cross_range_to = 'cross_rangeto_' + p;
					var cross_timestamp = 'cross_timestamp' + p;
					var cross_dropdown = 'cross_dropdown' + p;
					var cross_cal_range_from = 'cross_cal_rangefrom_' + p;
					var cross_cal_range_to = 'cross_cal_rangeto_' + p;

					if(link_fields[p] !='' && link_table_names[p] !='' && link_cols[p] !='' && link_field_texts[p] !=''){
	
							if(link_field_types[p]=='range'){ 
								link_search_patterns[q] = document.getElementById(cross_range_from).value + 
														'/' +
															document.getElementById(cross_range_to).value;
								} 
							else if(link_field_types[p]=='text_num'){
								link_search_patterns[q] = document.getElementById(cross_text_search).value;
								}
							else if(link_field_types[p]=='timestamp'){
								link_search_patterns[q] = document.getElementById(cross_timestamp).value;	
								}
							else if(link_field_types[p]=='cal_range'){
								link_search_patterns[q] = document.getElementById(cross_cal_range_from).value + 
														'/' +
															document.getElementById(cross_cal_range_to).value;
								}
							else if(link_field_types[p]=='dropdown'){
								link_search_patterns[q] = document.getElementById(cross_dropdown).value;
								}
							q++;
						}
					}
				

				report_builder.returnSearchResult('all',
										  	'<?php echo $table_name;?>',
										  	'<?php echo $report_title;?>',
											field_names,
											field_texts,
											field_types,
											search_patterns,
											link_fields,
											link_table_names,
											link_cols,
											link_field_texts,
											link_field_types,
											link_search_patterns,
											document.getElementById('search_report_id').value,
											links,
											{target:'div_result_report_builder',preloader:'prl',
											onUpdate: function(response,root){
												$(function() {		
													$('#search_table') .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],} );																		
												});
												}					
											});
			}
		</script>
<?php } ?>
		<div id="content_column_header">
			<div style="float:left">
			<?php if($report_title) echo $report_title; else echo 'Report'?>
			<a href="#" onclick="table2CSV($('#search_table')); return false;"><img src="images/csv.png"  alt="Export to CSV" /></a>
			</div>
			<div style="float:right">
			<?php /*?><a href="#" onclick="javascript: searchOptionAll(); return false;">Show All Result</a><?php */?>
			<?php if(!$search_report_id){?><a href="javascript:void(0);" onclick="javascript:
								if(document.getElementById('report_title').value != ''){
									if(confirm('Are you want to save the report ?')){ saveSearchOption(); }
									else { return false; }
								}
								else { alert('Enter Report Title');}">Save Search Report</a>
			<?php } ?>
			</div>
			<div style="clear:both" id="srch_div"></div>
		</div>
		<table id="search_table" class="event_form small_text" width="100%">
		<thead>
		<tr><?php
			if(count($fieldHeads) > 0 or count($link_field_texts) > 0){
				foreach($fieldHeads as $fieldHead){
					?><th><?php echo $fieldHead;?></th><?php
				}
				foreach($link_field_texts as $link_field_text){
					?><th><?php echo $link_field_text;?></th><?php
				}
			}
			else {
				$sql1 = "show fields FROM $table_name";		
				$result1 = $this->db->query($sql1,__FILE__,__lINE__);
				while($row1 = $this->db->fetch_array($result1)){
					?><th><?php echo $row1[0];?></th><?php	
				}			
				
				
			}
		?></tr></thead>
		<tbody>
		<?php
		$x= 0;
		while($row = $this->db->fetch_array($result)){
			$x++;
			?><tr>
			<?php
			if(count($fields) > 0) { 
				foreach($fields as $field){?>
					<td><?php echo $row[$field];?></td>
				<?php }
				foreach($link_cols as $link_col){?>
					<td><?php echo $row[$link_col];?></td>
				<?php } 

			}
			else {
				$result2 = $this->db->query($sql1,__FILE__,__lINE__);
				while($row2 = $this->db->fetch_array($result2)){
					?><th><?php echo $row[$row2[0]];?></th><?php	
				}			
			}?>
			</tr><?php
		}
		if($x<1) {?>
			<tr>
			<?php foreach($fields as $field){?>
			<td>&nbsp;</td>
			<?php } ?>
			</tr>
		<?php }
		?></tbody></table><?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function saveSearch($table_name='',$report_title='',$fields=array(),$fieldHeads=array(),$field_types=array(),$link_fields=array(),$link_table_names=array(),$link_cols=array(),$link_field_texts=array(),$link_field_types=array(),$search_report_id='',$links=array()){

		ob_start();
/*		echo '<br>Table';
		echo $table_name;
		echo '<br>title';
		echo $report_title;
		echo '<br>fields';
		print_r($fields);
		echo '<br>heads';
		print_r($fieldHeads);
		echo '<br>type';
		print_r($field_types);
		echo '<br>link_fields';
		print_r($link_fields);
		echo '<br>link_table_names';

		print_r($link_table_names);
		echo '<br>link_cols';
		print_r($link_cols);
		echo '<br>link_field_texts';
		print_r($link_field_texts);
		echo '<br>link_field_types';
		print_r($link_field_types);
*/		
		 $sql_array = array();
		 $sql_array[table_name] = $table_name;
		 $sql_array[report_title] = $report_title;
		 $sql_array[fields] = serialize($fields);
		 $sql_array[fieldHeads] = serialize($fieldHeads);
		 $sql_array[field_types] = serialize($field_types);
		 $sql_array[link_fields] = serialize($link_fields);
		 $sql_array[link_table_names] = serialize($link_table_names);
		 $sql_array[link_cols] = serialize($link_cols);
		 $sql_array[links] = serialize($links);
		 $sql_array[link_field_texts] = serialize($link_field_texts);
		 $sql_array[link_field_types] = serialize($link_field_types);
		
		if($search_report_id){
		$this->db->update('tbl_search_report',$sql_array,'search_report_id',$search_report_id);
		}
		else{
		$this->db->insert('tbl_search_report',$sql_array);
		}
		
		
		$report= $this->db->last_insert_id();
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;		
	}
	
	function showReports(){
		ob_start();
		$sql = "select report_title,search_report_id,cur_time from tbl_search_report";
		//echo $sql;
		$result = $this->db->query($sql,__FILE__,__lINE__); 
		
		$sql_auth = "Select * from tbl_usergroup a,group_access b where a.group_name = 'Admin' and a.group_id = b.group_id and b.user_id = '$_SESSION[user_id]'";
		//echo $sql_auth;
		$result_auth = $this->db->query($sql_auth,__FILE__,__lINE__);
		?>
		<table id="search_table" class="event_form small_text" width="50%">
		<thead>
		<th>Report Id</th>
		<th>Report Title</th>
		<th>Published on</th>
		<?php if($this->db->num_rows($result_auth) > 0) { ?>
				<th>Groups</th>
				<th>&nbsp;</th>		       
		<?php } ?>

		</thead>
		<tbody><?php
		while($row = $this->db->fetch_array($result)){
			if($row[report_title]){?>
			
				<tr>
				<td><a href="reports.php?search_report_id=<?php echo $row[search_report_id];?>"><?php echo $row[search_report_id];?></a></td>
				<td><a href="reports.php?search_report_id=<?php echo $row[search_report_id];?>"><?php echo $row[report_title];?></a></td>
				<td><?php echo date("h:i:s a Y-m-d",strtotime($row[cur_time]));?></td>
				
				<?php 
				if($this->db->num_rows($result_auth) > 0) { ?>			
						<td><div id="div_groups<?php echo $row[search_report_id];?>"><?php echo $this->showGroupsByReport($row[search_report_id]);?></div></td>
						<td><a href="edit_report.php?search_report_id=<?php echo $row[search_report_id];?>">Edit</a></td>
				<?php } ?>
				</tr>
			<?php
			}
		}
		?>
		</tbody>
		</table>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;		
	}
	
	function showGroupsByReport($search_report_id){
		ob_start();
		$sql = "select b.* from tbl_report_access a, tbl_usergroup b where a.group_id = b.group_id and a.search_report_id='$search_report_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		while($row = $this->db->fetch_array($result)){
			echo $row[group_name];
			?>
			<img src="images/trash.gif" border="0" onclick="javascript: report_builder.removeGroupFromReport(
																			'<?php echo $row[group_id];?>',
																			'<?php echo $search_report_id;?>',
																			{preloader:'prl',
																			onUpdate: function(response,root){
																				report_builder.showGroupsByReport(
																					'<?php echo $search_report_id;?>',
																					{preloader:'prl',target:'div_groups<?php echo $search_report_id;?>'})
																				}					
																			});" />;&nbsp;
			<?php 
		}
		$sql = "select * from tbl_usergroup where group_id not in (select distinct(group_id) from tbl_report_access where search_report_id='$search_report_id')";
		$result = $this->db->query($sql,__FILE__,__lINE__); ?>
		<br />
		<select style="width:auto;" id="add_group" name="add_group" onchange="javascript: report_builder.addGroupToReport(
																			this.value,
																			'<?php echo $search_report_id;?>',
																			{preloader:'prl',
																			onUpdate: function(response,root){
																				report_builder.showGroupsByReport(
																					'<?php echo $search_report_id;?>',
																					{preloader:'prl',target:'div_groups<?php echo $search_report_id;?>'})
																				}					
																			});">
		<option value="" >--Select Group--</option>
		<?php while($row = $this->db->fetch_array($result)){ ?>
			<option value="<?php echo $row[group_id];?>" ><?php echo $row[group_name];?></option>
			<?php }
		$html = ob_get_contents();
		ob_end_clean();
		return $html;		
		
	}
	
	function addGroupToReport($group_id,$search_report_id){
		ob_start();
		 
		$sql_array = array();
		$sql_array[group_id] = $group_id;
		$sql_array[search_report_id] = $search_report_id;
		 
		$this->db->insert('tbl_report_access',$sql_array);

		$html = ob_get_contents();
		ob_end_clean();
		return $html;		
	}
	
	function removeGroupFromReport($group_id,$search_report_id){
		ob_start();
		
		$sql = "delete from tbl_report_access where group_id='$group_id' and search_report_id='$search_report_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__); 
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;		
	}
	
	
	function showReportPage($search_report_id=''){
		ob_start();
		
		$sql = "select * from tbl_search_report where search_report_id='$search_report_id'";
		$result = $this->db->query($sql,__FILE__,__lINE__); 
		$row = $this->db->fetch_array($result);
		
		//print_r($row);		
		$table_name =  $row[table_name];
		$report_title = $row[report_title];
		$fields = unserialize($row[fields]);
		$fieldHeads = unserialize($row[fieldHeads]);
		$field_types = unserialize($row[field_types]);
		$link_fields = unserialize($row[link_fields]);
		$link_table_names = unserialize($row[link_table_names]);
		$link_cols = unserialize($row[link_cols]);
		$links = unserialize($row[links]);
		$link_field_texts = unserialize($row[link_field_texts]);
		$link_field_types = unserialize($row[link_field_types]);		
		
		
		
		echo $this->show_search_user($table_name,$report_title,$fields,$fieldHeads,$field_types,$link_fields,$link_table_names,$link_cols,$link_field_texts,$link_field_types,$links,$search_report_id);
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;		
	}
	
	function checkIsAuthorizeToReport($user_id,$search_report_id){
		
		$sql = "select user_id from group_access where user_id = '$user_id' and group_id in (select distinct(group_id) from tbl_report_access where search_report_id='$search_report_id') limit 1";
		$result = $this->db->query($sql,__FILE__,__lINE__);
		if($this->db->num_rows($result) > 0) return true;
		else return false;
	}
	
	
	function show_search_user($table_name='',$report_title='',$field_name=array(),$field_text=array(),$field_type=array(),$link_fields=array(),$link_table_names=array(),$link_cols=array(),$link_field_texts=array(),$link_field_types=array(),$links=array(),$search_report_id=''){
		ob_start();
/*		echo '<br>table_name';
		echo $table_name;
		echo '<br>report_title';
		echo $report_title;
		echo '<br>fields';
		print_r($field_name);
		echo '<br>fieldHeads';
		print_r($field_text);
		echo '<br>field_types';
		print_r($field_type);
		echo '<br>link_fields';
		print_r($link_fields);
		echo '<br>link_table_names';
		print_r($link_table_names);
		echo '<br>link_cols';
		print_r($link_cols);
		echo '<br>link_field_texts';
		print_r($link_field_texts);
		echo '<br>link_field_types';
		print_r($link_field_types);
		echo '<br>';
*/		?>
		<script>
		var field_names = new Array();
		var field_texts = new Array();
		var field_types = new Array();
		var search_patterns = new Array();
		<?php
		$k = 0;
		foreach($field_name as $field) {
			if($field_name[$k] != '' and $field_text[$k] != ''){ 
				?>
				field_names[<?php echo $k;?>] = '<?php echo $field_name[$k]; ?>';
				field_texts[<?php echo $k;?>] = '<?php echo $field_text[$k]; ?>';
				field_types[<?php echo $k;?>] = '<?php echo $field_type[$k]; ?>';
				<?php
			}
			$k++;
		}
		?>var k = <?php echo $k;?>;
		
		var link_fields = new Array();
		var link_table_names = new Array();
		var link_cols = new Array();
		var links = new Array();
		var link_field_texts = new Array();
		var link_field_types = new Array();
		var link_search_patterns = new Array();
		<?php 
		$j= 0;
		foreach($link_fields as $link_field) {
			
			if($link_fields[$j] !='' and $link_table_names[$j] !='' and $link_cols[$j] !=''and $link_field_texts[$j] !=''){ ?>
				link_fields[<?php echo $j;?>] = '<?php echo $link_fields[$j]; ?>';
				link_table_names[<?php echo $j;?>] = '<?php echo $link_table_names[$j]; ?>';
				link_cols[<?php echo $j;?>] = '<?php echo $link_cols[$j]; ?>';
				links[<?php echo $j;?>] = '<?php echo $links[$j]; ?>';
				link_field_texts[<?php echo $j;?>] = '<?php echo $link_field_texts[$j]; ?>';
				link_field_types[<?php echo $j;?>] = '<?php echo $link_field_types[$j]; ?>';
			<?php }
			$j++;
		}
		?>var j = <?php echo $j;?>;
		
		function searchOption(){
				var y=0;
				for(x=0; x < k; x++) {
					if(field_names[x] != '' && field_texts[x] != ''){ 
						
						var from = 'rangefrom_' + y ;
						var to = 'rangeto_' + y ;
						var txt_search = 'text_search_' + y ;
						var timstamp = 'timestamp' + y ;
						var dropdown = 'dropdown' + y ;
						var cal_range_from = 'cal_rangefrom_' + y ;
						var cal_range_to = 'cal_rangeto_' + y ;
						
						
						if(field_types[x]=='range'){ 
							search_patterns[y] = document.getElementById(from).value + 
													'/' +
														document.getElementById(to).value;
							}
						else if(field_types[x]=='text_num'){ 
							search_patterns[y] = document.getElementById(txt_search).value;
							}
						else if(field_types[x]=='timestamp'){ 
							search_patterns[y] = document.getElementById(timstamp).value;
							}
						else if(field_types[x]=='cal_range'){
							search_patterns[y] = document.getElementById(cal_range_from).value + 
													'/' +
														document.getElementById(cal_range_to).value;
							}
						else if(field_types[x]=='dropdown'){
							search_patterns[y] = document.getElementById(dropdown).value;
							}
						else {
							search_patterns[y] ='';
							}
						y++;
						}
				}
				
				/********************************************************************************************/

				var q=0;
				for(p=0; p < j; p++) {
				
					var cross_text_search = 'cross_text_search_' + p;
					var cross_range_from = 'cross_rangefrom_' + p;
					var cross_range_to = 'cross_rangeto_' + p;
					var cross_timestamp = 'cross_timestamp' + p;
					var cross_dropdown = 'cross_dropdown' + p;
					var cross_cal_range_from = 'cross_cal_rangefrom_' + p;
					var cross_cal_range_to = 'cross_cal_rangeto_' + p;

					if(link_fields[p] !='' && link_table_names[p] !='' && link_cols[p] !='' && link_field_texts[p] !=''){
	
							if(link_field_types[p]=='range'){ 
								link_search_patterns[q] = document.getElementById(cross_range_from).value + 
														'/' +
															document.getElementById(cross_range_to).value;
								} 
							else if(link_field_types[p]=='text_num'){
								link_search_patterns[q] = document.getElementById(cross_text_search).value;
								}
							else if(link_field_types[p]=='timestamp'){
								link_search_patterns[q] = document.getElementById(cross_timestamp).value;	
								}
							else if(link_field_types[p]=='cal_range'){
								link_search_patterns[q] = document.getElementById(cross_cal_range_from).value + 
														'/' +
															document.getElementById(cross_cal_range_to).value;
								}
							else if(link_field_types[p]=='dropdown'){
								link_search_patterns[q] = document.getElementById(cross_dropdown).value;
								}
							q++;
						}
					}
				
				/********************************************************************************************/				

				report_builder.returnSearchResult(100,
										  	'<?php echo $table_name;?>',
										  	'<?php echo $report_title;?>',
											field_names,
											field_texts,
											field_types,
											search_patterns,
											link_fields,
											link_table_names,
											link_cols,
											link_field_texts,
											link_field_types,
											link_search_patterns,
											'<?php echo $search_report_id;?>',
											links,
											{target:'div_result_report_builder',preloader:'prl',
											onUpdate: function(response,root){
												$(function() {		
													$('#search_table') .tablesorter({widthFixed: true, widgets: ['zebra'], sortList: [[0,0]],} );																		
												});
												}					
											});
			}	
		</script>
		<form name="frm_report_builder" action="" method="post" >
		Filters :<br />
		<table class="table"> 
		<?php
		$i=0;
		foreach($field_type as $type){ ?>			
				<tr>
				<?php 
				if($type) echo '<td>'.$field_text[$i].'&nbsp;</td>'; 
				if($type == 'text_num'){ ?>
   					 <td colspan="2">
					 <input type="text" id="text_search_<?php echo $i;?>" name="text_search_value"/>
					 <img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('text_search_<?php echo $i;?>').value='';" />
					 </td>

				<?php } 
				else if($type == 'range'){ ?>
					<td> 
					From:<input type="text" id="rangefrom_<?php echo $i;?>" name="range_from" />
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('rangefrom_<?php echo $i;?>').value='';" />
					</td>
					<td>
					To:<input type="text" id="rangeto_<?php echo $i;?>" name="range_to" />
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('rangeto_<?php echo $i;?>').value='';" />
					</td>
				<?php }
				
				else if($type == 'cal_range'){ ?>
					<td>
					From:<input type="text" id="cal_rangefrom_<?php echo $i;?>" name="cal_range_from" readonly=""/>
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cal_rangefrom_<?php echo $i;?>').value='';" />
					</td>
					<td>
					To:<input type="text" id="cal_rangeto_<?php echo $i;?>" name="cal_range_to" readonly="" />
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cal_rangeto_<?php echo $i;?>').value='';" />
					</td>
				<?php }
				else if($type == 'timestamp'){ ?>
					<td colspan="2">
					Timestamp:<input type="text" id="timestamp<?php echo $i;?>" name="timestamp" readonly="" />
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('timestamp<?php echo $i;?>').value='';" />
					</td>
				<?php } 
				else if($type == 'dropdown'){ ?>
					<td colspan="2"><?php
					$sql = "select distinct($field_name[$i]) FROM $table_name";
					$result = $this->db->query($sql,__FILE__,__lINE__); ?>
					<select id="dropdown<?php echo $i;?>" name="dropdown" >
					<option value="">--Select--</option>
					<?php while($row = $this->db->fetch_array($result)){ ?>
						<option value="<?php echo $row[$field_name[$i]];?>"><?php echo $row[$field_name[$i]];?></option>
					<?php } ?>
</select>
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('dropdown<?php echo $i;?>').value='';" />
					</td>
				<?php }?>
					
		 		</tr>
		 <?php 
		 $i++; } ?>
		
		<?php
		$i=0;
		foreach($link_field_types as $type){ ?>			
				<tr><?php
				if($type) echo '<td>'.$link_field_texts[$i].'&nbsp;</td>'; 
				if($type == 'text_num'){ ?>
   					 <td>
					 <input type="text" id="cross_text_search_<?php echo $i;?>" name="cross_text_search_value"/>
					 <img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cross_text_search_<?php echo $i;?>').value='';" />
					 </td>
				<?php } 
				else if($type == 'range'){ ?>
					<td>From:<input type="text" id="cross_rangefrom_<?php echo $i;?>" name="cross_range_from" />
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cross_rangefrom_<?php echo $i;?>').value='';" />
					To:<input type="text" id="cross_rangeto_<?php echo $i;?>" name="cross_range_to"  />
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cross_rangeto_<?php echo $i;?>').value='';" />
					</td>
				<?php }
				else if($type == 'cal_range'){ ?>
					<td>From:<input type="text" id="cross_cal_rangefrom_<?php echo $i;?>" name="cross_cal_range_from" readonly=""/>
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cross_cal_rangefrom_<?php echo $i;?>').value='';" />
					To:<input type="text" id="cross_cal_rangeto_<?php echo $i;?>" name="cross_cal_range_to" readonly="" />
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cross_cal_rangeto_<?php echo $i;?>').value='';" />
					</td>
				<?php }
				else if($type == 'timestamp'){ ?>
					<td>Timestamp:<input type="text" id="cross_timestamp<?php echo $i;?>" name="cross_timestamp" readonly="" />
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cross_timestamp<?php echo $i;?>').value='';" /></td>
				<?php }
				else if($type == 'dropdown'){ ?>
					<td><?php
					$sql = "select distinct($link_cols[$i]) FROM $link_table_names[$i]";
					$result = $this->db->query($sql,__FILE__,__lINE__); ?>
					<select id="cross_dropdown<?php echo $i;?>" name="dropdown" >
					<option value="">--Select--</option>
					<?php while($row = $this->db->fetch_array($result)){ ?>
						<option value="<?php echo $row[$link_cols[$i]];?>"><?php echo $row[$link_cols[$i]];?></option>
					<?php } ?>
					</select>
					<img src="images/trash.gif" border="0" onclick="javascript: document.getElementById('cross_dropdown<?php echo $i;?>').value='';" />
					</td>
				<?php }
				?>
		 		</tr>
		 <?php 
		 $i++; } ?>
		</table>
		</form>
		<script>
			function start_cal()  { <?php 
				$i=0; 
				foreach($field_type as $type){ 
					if($type == 'cal_range'){ ?>
					  
					  var cal<?php echo $i;?>=new Calendar({
							  inputField   	: "cal_rangefrom_<?php echo $i;?>",
							  dateFormat	: "%Y-%m-%d",
							  trigger		: "cal_rangefrom_<?php echo $i;?>",
							  weekNumbers   : true,
							  bottomBar		: true,
							  showTime      : 12,
							  onSelect		: function() {
													this.hide();
													document.getElementById('cal_rangefrom_<?php echo $i;?>').value=this.selection.print("%Y-%m-%d");														
												},
					  });
					  
					  var cal<?php echo $i;?>=new Calendar({
							  inputField   	: "cal_rangeto_<?php echo $i;?>",
							  dateFormat	: "%Y-%m-%d",
							  trigger		: "cal_rangeto_<?php echo $i;?>",
							  weekNumbers   : true,
							  bottomBar		: true,
							  showTime      : 12,
							  onSelect		: function() {
													this.hide();
													document.getElementById('cal_rangeto_<?php echo $i;?>').value=this.selection.print("%Y-%m-%d");											
												},
					  });
					<?php } 
					
					else if($type == 'timestamp'){ ?>
		
					  var cal<?php echo $i;?>=new Calendar({
							  inputField   	: "timestamp<?php echo $i;?>",
							  dateFormat	: "%Y-%m-%d",
							  trigger		: "timestamp<?php echo $i;?>",
							  weekNumbers   : true,
							  bottomBar		: true,
							  showTime      : 12,
							  onSelect		: function() {
													this.hide();
													document.getElementById('timestamp<?php echo $i;?>').value=this.selection.print("%Y-%m-%d");														
												},
					  });
					<?php }
				$i++;
				}?>
			}
		start_cal();	
			
		function cross_start_cal()  { <?php 
				$i=0; 
				foreach($link_field_types as $type){ 
					if($type == 'cal_range'){ ?>
					  
					  var cross_cal<?php echo $i;?>=new Calendar({
							  inputField   	: "cross_cal_rangefrom_<?php echo $i;?>",
							  dateFormat	: "%Y-%m-%d",
							  trigger		: "cross_cal_rangefrom_<?php echo $i;?>",
							  weekNumbers   : true,
							  bottomBar		: true,
							  showTime      : 12,
							  onSelect		: function() {
													this.hide();
													document.getElementById('cross_cal_rangefrom_<?php echo $i;?>').value=this.selection.print("%Y-%m-%d");														
												},
					  });
					  
					  var cross_cal<?php echo $i;?>=new Calendar({
							  inputField   	: "cross_cal_rangeto_<?php echo $i;?>",
							  dateFormat	: "%Y-%m-%d",
							  trigger		: "cross_cal_rangeto_<?php echo $i;?>",
							  weekNumbers   : true,
							  bottomBar		: true,
							  showTime      : 12,
							  onSelect		: function() {
													this.hide();
													document.getElementById('cross_cal_rangeto_<?php echo $i;?>').value=this.selection.print("%Y-%m-%d");											
												},
					  });
					<?php } 
					
					else if($type == 'timestamp'){ ?>
		
					  var cross_cal<?php echo $i;?>=new Calendar({
							  inputField   	: "cross_timestamp<?php echo $i;?>",
							  dateFormat	: "%Y-%m-%d",
							  trigger		: "cross_timestamp<?php echo $i;?>",
							  weekNumbers   : true,
							  bottomBar		: true,
							  showTime      : 12,
							  onSelect		: function() {
													this.hide();
													document.getElementById('cross_timestamp<?php echo $i;?>').value=this.selection.print("%Y-%m-%d");														
												},
					  });
					<?php }
				$i++;
				}?>
			}
		cross_start_cal();
		</script>
		<a href="javascript:void(0);" onclick="javascript:searchOption();">Search</a>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;		
	}
	
	
	function customizeReportBuilderEdit($search_report_id=''){
		ob_start();
		$i=0;
		
		$sql = "show tables FROM ".DATABASE_NAME;
		$result = $this->db->query($sql,__FILE__,__lINE__);
		
		$sql_r = "select * from tbl_search_report where search_report_id='$search_report_id'";
		$result_r = $this->db->query($sql_r,__FILE__,__lINE__); 
		$row_r = $this->db->fetch_array($result_r);
				
		$table_name =  $row_r[table_name];
		$report_title = $row_r[report_title];
		$fields = unserialize($row_r[fields]);
		$fieldHeads = unserialize($row_r[fieldHeads]);
		$field_types = unserialize($row_r[field_types]);
		$link_fields = unserialize($row_r[link_fields]);
		$link_table_names = unserialize($row_r[link_table_names]);
		$link_cols = unserialize($row_r[link_cols]);
		$link_field_texts = unserialize($row_r[link_field_texts]);
		$link_field_types = unserialize($row_r[link_field_types]);		
		

		?>
		<form name="frm_report_builder" action="" method="post">
		<input name="cnt_option" id="cnt_option" type="hidden" value="" />
		<input name="cnt_search" id="cnt_search" type="hidden" value="1" />
		<input name="cnt_cross_tables" id="cnt_cross_tables" type="hidden" value="1" />
		<input name="search_report_id" id="search_report_id" type="hidden" value="<?php echo $search_report_id;?>" />
		<select name="field_name" style="display:none;" ></select>
		<input name="field_text" value="" type="hidden" />
		<select name="search_type" style="display:none"></select>
		<select style="display:none;" name="link_field"></select>
		<table width="60%" class="table"><tr>
		<th>Database Table:</th>
		<td>
		<select id="table_name" name="table_name" onChange="javascript:
												report_builder.returnSelection(																		
												document.frm_report_builder.table_name.value,
												'<?php echo $i; ?>',
												{preloader:'prl',target:'div_selections',
												onUpdate: function(response,root){
													document.getElementById('div_show_search').innerHTML='';
													document.getElementById('cross_search_div').innerHTML='';
													document.getElementById('div_result_report_builder').innerHTML='';	
													document.getElementById('cnt_option').value='1';	
													document.getElementById('cnt_search').value='1';	
													document.getElementById('cnt_cross_tables').value='1';	
													}
												});">
		<option value="" >--Select Table--</option><?php
		while($row = $this->db->fetch_array($result)){
			?><option value="<?php echo $row[0];?>" 
			<?php if($table_name == $row[0]) echo 'selected="selected"';?>>
			<?php echo $row[0];?></option><?php
		}
		?>
		</select>
		</td>
		<th>Report Title:</th>
		<td><input id="report_title" name="report_title" onChange="javascript:searchOption();" value="<?php echo $report_title;?>"/></td>

		</tr></table>
		<div id="div_selections" style="padding:0px">
		<?php 
			echo $this->returnSelection($table_name,'',$search_report_id);
		?>	
		</div>
		<div id="div_show_search" >
		<?php	
			echo $this->show_search($table_name,$report_title,$fields,$fieldHeads,$field_types,$search_report_id);
		?>
		</div>
		<div id="cross_search_div">
		<?php 
			echo $this->show_cross_search($table_name,$link_fields,$link_table_names,$link_cols,$link_field_texts,$link_field_types);
		?>
		</div>
		<a href="javascript:void(0);" onclick="javascript:searchOption();">Search</a>
		</form>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

}
?>
