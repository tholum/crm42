<?php
class Application{
	
	
	var $cont_id;
	var $module;
	var $security;
	var $contact;
	var $education;
	var $reference;
	var $employment;
	
	function __construct()
	 {
	    $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->Validity = new ClsJSFormValidation();
		$this->ValidityAlert = new ClsJSFormValidationAlert();
		$this->Form = new ValidateForm();
		$this->module="TBL_CONTACT";
		$this->security=new Element_Permission();
		$this->contact = new Company_Global();
	}   
	
	function addApplicationEducation($runat)
	{
		switch($runat){
			case 'local':
			
			
			?>
				<tr class="addresses">
			  <th><h2>&nbsp;</h2></th>
			  <td><div class="contact_forms addresses" id="education_list_signup"><div
			 id="blank_slate_signup_education" class="blank_slate" style="display: none;">Add an 
			education</div><div  class="contact_methods">
			
			<div class="contact_method new_contact_method street" 
			id="edu_xf6e911b9006d854b636885">
			  <div class="fields">
				<div style="position: relative;">
				  <p class="address">
				  Title: <select class="overlayable" id="signup_data_education_edu_xf6e911b9006d854b636885_title" name="signup[data][education][][title]" >
				    <option value="High School" >High School</option>
					<option value="College" >College</option>
					<option value="Other" >Other</option>
				  </select>
				  </p>
				  <p>University: <input
			 class="autofocus overlayable textbox8" 
			id="signup_data_education_edu_xf6e911b9006d854b636885_university" 
			name="signup[data][education][][university]" type="text" />
				Address: <input
			 class="state overlayable textbox8" 
			id="signup_data_education_edu_xf6e911b9006d854b636885_address"
			 name="signup[data][education][][address]"
			type="text" /></p>
			<p>From: <input
			 class="zip overlayable date" 
			id="signup_data_education_edu_xf6e911b9006d854b636885_from" 
			name="signup[data][education][][from]"  type="text" readonly="readonly" /><img src="images/b_calendar.png" id='signup_data_education_edu_xf6e911b9006d854b636885_button3' onmouseover="javascript:document.body.style.cursor='hand';" onmouseout="javascript:document.body.style.cursor='default';">&nbsp;
			<script type="text/javascript">
				var cal = new Zapatec.Calendar.setup({
								
					inputField:"signup_data_education_edu_xf6e911b9006d854b636885_from",
					ifFormat:"%m-%d-%Y",
					button:"signup_data_education_edu_xf6e911b9006d854b636885_button3",
					showsTime:false
																		
				});
					
			</script>	
			   To: <input
			 class="zip overlayable date" 
			id="signup_data_education_edu_xf6e911b9006d854b636885_to" 
			name="signup[data][education][][to]"  type="text" readonly="readonly" /><img src="images/b_calendar.png" id='signup_data_education_edu_xf6e911b9006d854b636885_button4' onmouseover="javascript:document.body.style.cursor='hand';" onmouseout="javascript:document.body.style.cursor='default';">&nbsp;
			<script type="text/javascript">
					var cal = new Zapatec.Calendar.setup({
					
					inputField:"signup_data_education_edu_xf6e911b9006d854b636885_to",
					ifFormat:"%m-%d-%Y",
					button:"signup_data_education_edu_xf6e911b9006d854b636885_button4",
					showsTime:false
			
					});
					
			</script>																			
			   Did you graduate? <select class="zip overlayable" id="signup_data_education_edu_xf6e911b9006d854b636885_graduate" name="signup[data][education][][graduate]"  style="width:auto" >
				  <option value="" >Select</option>
				  <option value="yes" >Yes</option>
				  <option value="no" selected="selected" >No</option>
			   </select>
			   
			   Degree: <input
			 class="zip overlayable textbox3" 
			id="signup_data_education_edu_xf6e911b9006d854b636885_degree" 
			name="signup[data][education][][degree]"  type="text" />
			</p>
				  <div class="loc_remove">
					
					
					<span class="addremove"><a href="#" class="remove">Remove</a></span>
				  </div>
				</div>
			  </div>
			</div>
			<script type="text/javascript">
			//<![CDATA[
			$$('.street .overlayable').each(function(input) { ContactInfo.Overlay.wrap(input); });
			//]]>
			</script>
			
			<div id="add_signup_education" class="add_contact_method" style="display: 
			none;">
			  <div class="add"><a href="#">Add another</a></div>
			</div>
			</div></div><script type="text/javascript">
			//<![CDATA[
			new ContactInfo.ContactForm("education_list_signup", "\n\n\u003Cdiv class=\"contact_method new_contact_method street\" id=\"edu_#{safe_id}\"\u003E\n  \u003Cdiv class=\"fields\"\u003E\n    \u003Cdiv style=\"position: relative;\"\u003E\n      Title:\u003Cp class=\"address\"\u003E\u003Cselect class=\"overlayable\" id=\"signup_data_education_edu_#{safe_id}_title\" name=\"signup[data][education][][title]\" \u003E \u003Coption value=\"High School\"\u003EHigh School\u003C/option\u003E\u003Coption value=\"College\"\u003ECollege\u003C/option\u003E\u003Coption value=\"Other\"\u003EOther\u003C/option\u003E\u003C/select\u003E\u003C/p\u003E\n\u003Cp\u003EUniversity:\u003Cinput class=\"autofocus overlayable textbox8\" id=\"signup_data_education_edu_#{safe_id}_university\" name=\"signup[data][education][][university]\"  type=\"text\"\u003EAddress:\u003Cinput class=\"state overlayable textbox8\" id=\"signup_data_education_edu_#{safe_id}_address\" name=\"signup[data][education][][address]\"  type=\"text\"\u003E\u003C/p\u003E\u003Cp\u003EFrom:\u003Cinput class=\"zip overlayable date\" id=\"signup_data_education_edu_#{safe_id}_from\" name=\"signup[data][education][][from]\"  type=\"text\" readonly=\"readonly\"\u003E\u003Cimg src=\"images/b_calendar.png\" id='signup_data_education_edu_#{safe_id}_button3' onmouseover=\"javascript:document.body.style.cursor='hand';\" onmouseout=\"javascript:document.body.style.cursor='default';\"\u003E\u003Cscript type=\"text/javascript\"\u003E\nvar cal = new Zapatec.Calendar.setup({\ninputField:\"signup_data_education_edu_#{safe_id}_from\",\nifFormat:\"%m-%d-%Y\",\nbutton:\"signup_data_education_edu_#{safe_id}_button3\",\nshowsTime:false\n});\n\u003C/script\u003E\nTo:\u003Cinput class=\"zip overlayable date\" id=\"signup_data_education_edu_#{safe_id}_to\" name=\"signup[data][education][][to]\"  type=\"text\" readonly=\"readonly\"\u003E\u003Cimg src=\"images/b_calendar.png\" id='signup_data_education_edu_#{safe_id}_button4' onmouseover=\"javascript:document.body.style.cursor='hand';\" onmouseout=\"javascript:document.body.style.cursor='default';\"\u003E\n\u003Cscript type=\"text/javascript\"\u003E\nvar cal = new Zapatec.Calendar.setup({\ninputField:\"signup_data_education_edu_#{safe_id}_to\",\nifFormat:\"%m-%d-%Y\",\nbutton:\"signup_data_education_edu_#{safe_id}_button4\",\nshowsTime:false\n});\n\u003C/script\u003E\nDid you graduate?\u003Cselect class=\"zip overlayable\" id=\"signup_data_education_edu_#{safe_id}_graduate\" name=\"signup[data][education][][graduate]\"  style=\"width:auto\" \u003E\u003Coption value=\"\" \u003EDid you graduate?\u003C/option\u003E\u003Coption value=\"yes\" \u003EYes\u003C/option\u003E\u003Coption value=\"no\" selected=\"selected\" \u003ENo\u003C/option\u003E\u003C/select\u003E Degree:\u003Cinput class=\"zip overlayable textbox3\" id=\"signup_data_education_edu_#{safe_id}_degree\" name=\"signup[data][education][][degree]\"  type=\"text\"\u003E\u003C/p\u003E      \u003Cdiv class=\"loc_remove\"\u003E\n    \u003Cspan class=\"addremove\"\u003E\u003Ca href=\"#\" class=\"remove\"\u003ERemove\u003C/a\u003E\u003C/span\u003E\n      \u003C/div\u003E\n    \u003C/div\u003E\n  \u003C/div\u003E\n\u003C/div\u003E\n\u003Cscript type=\"text/javascript\"\u003E\n//\u003C![CDATA[\n$$('.street .overlayable').each(function(input) { ContactInfo.Overlay.wrap(input); });\n//]]\u003E\n\u003C/script\u003E\n\n")
			//]]>
			</script>
			</td></tr>
			<?php
				break;
			case 'server' :
						$cnt=0;
						extract($_POST[signup]);
						$this->education = $data[education];
						for(;$cnt<count($this->education);) {
							if($this->education[$cnt+1][university]!=''){
								$insert_sql_array = array();
								$insert_sql_array[contact_id] 		= $this->cont_id;
								$insert_sql_array[education_id] 	= $this->education[$cnt+1][university];
								$insert_sql_array[title] 			= $this->education[$cnt][title];
								$insert_sql_array[address] 			= $this->education[$cnt+2][address];
								$insert_sql_array[education_from] 	= $this->education[$cnt+3][from];
								$insert_sql_array[education_to] 	= $this->education[$cnt+4][to];
								$insert_sql_array[graduate] 		= $this->education[$cnt+5][graduate];
								$insert_sql_array[graduate_degree] 	= $this->education[$cnt+6][degree];
								$this->db->insert(EM_APPLICATION_EDUCATION,$insert_sql_array);
							}
							$cnt+=7;
						}	
						break;
		}
	}
	
	function addApplicationReference($runat)
	{
		switch($runat){
			case 'local':
			?>
				<tr class="addresses">
			  <th><h2>&nbsp;</h2></th>
			  <td><div class="contact_forms addresses" id="reference_list_signup"><div
			 id="blank_slate_signup_reference" class="blank_slate" style="display: none;">Add a 
			reference</div><div  class="contact_methods">
			
			<div class="contact_method new_contact_method street" 
			id="ref_xf6e911b9006d854b636685">
			  <div class="fields">
				<div style="position: relative;">
				  <p class="address">
				  Full Name: <input class="autofocus overlayable textbox9" name="signup[data][reference][][name]" id="signup_data_reference_ref_xf6e911b9006d854b636685_name"   type="text" />
				  Relationship: <input class="overlayable textbox9" name="signup[data][reference][][relationship]" id="signup_data_reference_ref_xf6e911b9006d854b636685_relationship"   type="text" />
				  </p>
				  <p>Company: <input class="overlayable textbox9" name="signup[data][reference][][company]" id="signup_data_reference_ref_xf6e911b9006d854b636685_company" t type="text" />
				  Phone: <input class="overlayable textbox9" name="signup[data][reference][][phone]" id="signup_data_reference_ref_xf6e911b9006d854b636685_phone" type="text" />
				  </p>
				  <p>Address: <input class="overlayable textbox10" name="signup[data][reference][][address]" id="signup_data_reference_ref_xf6e911b9006d854b636685_address"  type="text" />
			   
			</p>
				  <div class="loc_remove">
					
					
					<span class="addremove"><a href="#" class="remove">Remove</a></span>
				  </div>
				</div>
			  </div>
			</div>
			<script type="text/javascript">
			//<![CDATA[
			$$('.street .overlayable').each(function(input) { ContactInfo.Overlay.wrap(input); });
			//]]>
			</script>
			
			<div id="add_signup_reference" class="add_contact_method" style="display: 
			none;">
			  <div class="add"><a href="#">Add another</a></div>
			</div>
			</div></div><script type="text/javascript">
			//<![CDATA[
			new ContactInfo.ContactForm("reference_list_signup", "\n\n\u003Cdiv class=\"contact_method new_contact_method street\" id=\"ref_#{safe_id}\"\u003E\n  \u003Cdiv class=\"fields\"\u003E\n    \u003Cdiv style=\"position: relative;\"\u003E\n  \u003Cp class=\"address\"\u003EFull Name:\u003Cinput class=\"autofocus overlayable textbox9\" name=\"signup[data][reference][][name]\" id=\"signup_data_reference_ref_#{safe_id}_name\"   type=\"text\" /\u003ERelationship:\u003Cinput class=\"overlayable textbox9\" name=\"signup[data][reference][][relationship]\" id=\"signup_data_reference_ref_#{safe_id}_relationship\"   type=\"text\" /\u003E\u003C/p\u003E\u003Cp\u003ECompany:\u003Cinput class=\"overlayable textbox9\" name=\"signup[data][reference][][company]\" id=\"signup_data_reference_ref_#{safe_id}_company\"  type=\"text\" /\u003EPhone:\u003Cinput class=\"overlayable textbox9\" name=\"signup[data][reference][][phone]\" id=\"signup_data_reference_ref_#{safe_id}_phone\"   type=\"text\" /\u003E\u003C/p\u003E\u003Cp\u003EAddress:\u003Cinput class=\"overlayable textbox10\" name=\"signup[data][reference][][address]\" id=\"signup_data_reference_ref_#{safe_id}_address\"   type=\"text\" /\u003E\u003C/p\u003E \u003Cdiv class=\"loc_remove\"\u003E\n    \u003Cspan class=\"addremove\"\u003E\u003Ca href=\"#\" class=\"remove\"\u003ERemove\u003C/a\u003E\u003C/span\u003E\n      \u003C/div\u003E\n    \u003C/div\u003E\n  \u003C/div\u003E\n\u003C/div\u003E\n\u003Cscript type=\"text/javascript\"\u003E\n//\u003C![CDATA[\n$$('.street .overlayable').each(function(input) { ContactInfo.Overlay.wrap(input); });\n//]]\u003E\n\u003C/script\u003E\n\n")
			//]]>
			</script>
			</td></tr>
			<?php
				break;
			case 'server' :
						$cnt=0;
						extract($_POST[signup]);
						$this->reference = $data[reference];
						for(;$cnt<count($this->reference);) {
							if($this->reference[$cnt][name]!=''){
								$insert_sql_array = array();
								$insert_sql_array[contact_id] 			= $this->cont_id;
								$insert_sql_array[reference_name] 		= $this->reference[$cnt][name];
								$insert_sql_array[reference_relation] 	= $this->reference[$cnt+1][relationship];
								$insert_sql_array[reference_company] 	= $this->reference[$cnt+2][company];
								$insert_sql_array[reference_phone] 		= $this->reference[$cnt+3][phone];
								$insert_sql_array[reference_address] 	= $this->reference[$cnt+4][address];
								$this->db->insert(EM_APPLICATION_REFERENCES,$insert_sql_array);
							}
							$cnt+=5;
						}	
						break;
		}
	}

	function addApplicationEmployment($runat)
	{
		switch($runat){
			case 'local':
			?>
				<tr class="addresses">
			  <th><h2>&nbsp;</h2></th>
			  <td><div class="contact_forms addresses" id="employment_list_signup"><div	 id="blank_slate_signup_employment" class="blank_slate" style="display: none;">Add an 
			employment</div><div  class="contact_methods">
			
			<div class="contact_method new_contact_method street" 
			id="emp_xf6e911b9006d854b636385">
			  <div class="fields">
				<div style="position: relative;">
				  <p class="address">
				  Company: <input class="autofocus overlayable textbox9" name="signup[data][employment][][company]" id="signup_data_employment_emp_xf6e911b9006d854b636385_company"   type="text" />
				  Phone: <input class="overlayable textbox9" name="signup[data][employment][][phone]" id="signup_data_employment_emp_xf6e911b9006d854b636385_phone"   type="text" />
				  </p>
				  <p>Address: <input class="overlayable textbox9" name="signup[data][employment][][address]" id="signup_data_employment_emp_xf6e911b9006d854b636385_address"  type="text" />
				  Supervisor: <input class="overlayable textbox9" name="signup[data][employment][][supervisor]" id="signup_data_employment_emp_xf6e911b9006d854b636385_supervisor"  type="text" />
				  </p>
				  <p>Job: <input class="overlayable textbox11" name="signup[data][employment][][job]" id="signup_data_employment_emp_xf6e911b9006d854b636385_job"  type="text" />
				  Start Salary: <input class="overlayable textbox12" name="signup[data][employment][][start_salary]" id="signup_data_employment_emp_xf6e911b9006d854b636385_start_salary"  type="text" />
				  End Salary: <input class="overlayable textbox12" name="signup[data][employment][][end_salary]" id="signup_data_employment_emp_xf6e911b9006d854b636385_end_salary"  type="text" />
				  </p>
				  <p>
				  Responsibility: <input class="overlayable textbox10" name="signup[data][employment][][responsibility]" id="signup_data_employment_emp_xf6e911b9006d854b636385_responsibility"   type="text" />
				  </p>
				  <p>From: <input class="overlayable date" name="signup[data][employment][][from]" id="signup_data_employment_emp_xf6e911b9006d854b636385_from" type="text" readonly="readonly" /><img src="images/b_calendar.png" id='signup_data_employment_emp_xf6e911b9006d854b636385_button9' onmouseover="javascript:document.body.style.cursor='hand';" onmouseout="javascript:document.body.style.cursor='default';">
				  <script type="text/javascript">
						var cal = new Zapatec.Calendar.setup({
						
						inputField:"signup_data_employment_emp_xf6e911b9006d854b636385_from",
						ifFormat:"%m-%d-%Y",
						button:"signup_data_employment_emp_xf6e911b9006d854b636385_button9",
						showsTime:false
				
						});
						
					</script>
				  To: <input class="overlayable date" name="signup[data][employment][][to]" id="signup_data_employment_emp_xf6e911b9006d854b636385_to"  type="text" readonly="readonly" /><img src="images/b_calendar.png" id='signup_data_employment_emp_xf6e911b9006d854b636385_button10' onmouseover="javascript:document.body.style.cursor='hand';" onmouseout="javascript:document.body.style.cursor='default';">
					  <script type="text/javascript">
						var cal = new Zapatec.Calendar.setup({
						
						inputField:"signup_data_employment_emp_xf6e911b9006d854b636385_to",
						ifFormat:"%m-%d-%Y",
						button:"signup_data_employment_emp_xf6e911b9006d854b636385_button10",
						showsTime:false
				
						});
						
					</script>
				  Reason for Leaving: <input class="overlayable textbox13" name="signup[data][employment][][reason_leaving]" id="signup_data_employment_emp_xf6e911b9006d854b636385_reason_leaving"  type="text" />
				  </p>
				  <p>
				  May we contact your previous supervisor for a reference? <select class="overlayable" id="signup_data_employment_emp_xf6e911b9006d854b636385_previous_supervisor" name="signup[data][employment][][previous_supervisor]" style="width:auto" >
				    <option value="">Select</option>
					<option value="yes" >Yes</option>
				    <option value="no" >No</option>
			   	  </select>
				  </p>
				  <div class="loc_remove">
					
					
					<span class="addremove"><a href="#" class="remove">Remove</a></span>
				  </div>
				</div>
			  </div>
			</div>
			<script type="text/javascript">
			//<![CDATA[
			$$('.street .overlayable').each(function(input) { ContactInfo.Overlay.wrap(input); });
			//]]>
			</script>
			
			<div id="add_signup_employment" class="add_contact_method" style="display: 
			none;">
			  <div class="add"><a href="#">Add another</a></div>
			</div>
			</div></div><script type="text/javascript">
			//<![CDATA[
			new ContactInfo.ContactForm("employment_list_signup", "\n\n\u003Cdiv class=\"contact_method new_contact_method street\" id=\"emp_#{safe_id}\"\u003E\n  \u003Cdiv class=\"fields\"\u003E\n    \u003Cdiv style=\"position: relative;\"\u003E\n  \u003Cp class=\"address\"\u003ECompany:\u003Cinput class=\"autofocus overlayable textbox9\" name=\"signup[data][employment][][company]\" id=\"signup_data_employment_emp_#{safe_id}_company\"   type=\"text\" /\u003EPhone:\u003Cinput class=\"overlayable textbox9\" name=\"signup[data][employment][][phone]\" id=\"signup_data_employment_emp_#{safe_id}_phone\"  type=\"text\" /\u003E\u003C/p\u003E\u003Cp\u003EAddress:\u003Cinput class=\"overlayable textbox9\" name=\"signup[data][employment][][address]\" id=\"signup_data_employment_emp_#{safe_id}_address\"  type=\"text\" /\u003ESupervisor:\u003Cinput class=\"overlayable textbox9\" name=\"signup[data][employment][][supervisor]\" id=\"signup_data_employment_emp_#{safe_id}_supervisor\"  type=\"text\" /\u003E\u003C/p\u003E\u003Cp\u003EJob:\u003Cinput class=\"overlayable textbox11\" name=\"signup[data][employment][][job]\" id=\"signup_data_employment_emp_#{safe_id}_job\"  type=\"text\" /\u003EStart Salary:\u003Cinput class=\"overlayable textbox12\" name=\"signup[data][employment][][start_salary]\" id=\"signup_data_employment_emp_#{safe_id}_start_salary\"  type=\"text\" /\u003EEnd Salary:\u003Cinput class=\"overlayable textbox12\" name=\"signup[data][employment][][end_salary]\" id=\"signup_data_employment_emp_#{safe_id}_end_salary\"  type=\"text\" /\u003E\u003C/p\u003E\u003Cp\u003EResponsibility:\u003Cinput class=\"overlayable textbox10\" name=\"signup[data][employment][][responsibility]\" id=\"signup_data_employment_emp_#{safe_id}_responsibility\"  type=\"text\" /\u003E\u003C/p\u003E\u003Cp\u003EFrom:\u003Cinput class=\"overlayable date\" name=\"signup[data][employment][][from]\" id=\"signup_data_employment_emp_#{safe_id}_from\"  type=\"text\" readonly=\"readonly\" /\u003E\u003Cimg src=\"images/b_calendar.png\" id='signup_data_employment_emp_#{safe_id}_button9' onmouseover=\"javascript:document.body.style.cursor='hand';\" onmouseout=\"javascript:document.body.style.cursor='default';\"\u003E\n\u003Cscript type=\"text/javascript\"\u003E\nvar cal = new Zapatec.Calendar.setup({\ninputField:\"signup_data_employment_emp_#{safe_id}_from\",\nifFormat:\"%m-%d-%Y\",\nbutton:\"signup_data_employment_emp_#{safe_id}_button9\",\nshowsTime:false\n});\n\u003C/script\u003E\nTo:\u003Cinput class=\"overlayable date\" name=\"signup[data][employment][][to]\" id=\"signup_data_employment_emp_#{safe_id}_to\"  type=\"text\" readonly=\"readonly\" /\u003E\u003Cimg src=\"images/b_calendar.png\" id='signup_data_employment_emp_#{safe_id}_button10' onmouseover=\"javascript:document.body.style.cursor='hand';\" onmouseout=\"javascript:document.body.style.cursor='default';\"\u003E\n\u003Cscript type=\"text/javascript\"\u003E\nvar cal = new Zapatec.Calendar.setup({\ninputField:\"signup_data_employment_emp_#{safe_id}_to\",\nifFormat:\"%m-%d-%Y\",\nbutton:\"signup_data_employment_emp_#{safe_id}_button10\",\nshowsTime:false\n});\n\u003C/script\u003E\nReason for Leaving:\u003Cinput class=\"overlayable textbox13\" name=\"signup[data][employment][][reason_leaving]\" id=\"signup_data_employment_emp_#{safe_id}_reason_leaving\" type=\"text\" /\u003E\u003C/p\u003E\u003Cp\u003EMay we contact your previous supervisor for a reference?\u003Cselect class=\"overlayable\" id=\"signup_data_employment_emp_#{safe_id}_previous_supervisor\" name=\"signup[data][employment][][previous_supervisor]\" style=\"width:auto\" \u003E\u003Coption value=\"\"\u003ESelect\u003C/option\u003E\u003Coption value=\"yes\" \u003EYes\u003C/option\u003E\u003Coption value=\"no\" \u003ENo\u003C/option\u003E\u003C/select\u003E\u003C/p\u003E \u003Cdiv class=\"loc_remove\"\u003E\n    \u003Cspan class=\"addremove\"\u003E\u003Ca href=\"#\" class=\"remove\"\u003ERemove\u003C/a\u003E\u003C/span\u003E\n      \u003C/div\u003E\n    \u003C/div\u003E\n  \u003C/div\u003E\n\u003C/div\u003E\n\u003Cscript type=\"text/javascript\"\u003E\n//\u003C![CDATA[\n$$('.street .overlayable').each(function(input) { ContactInfo.Overlay.wrap(input); });\n//]]\u003E\n\u003C/script\u003E\n\n")
			//]]>
			</script>
			</td></tr>
			<?php
				break;
			case 'server' :
						$cnt=0;
						extract($_POST[signup]);
						$this->employment = $data[employment];
						for(;$cnt<count($this->employment);) {
							if($this->employment[$cnt][company]!=''){
								$insert_sql_array = array();
								$insert_sql_array[contact_id] 					= $this->cont_id;
								$insert_sql_array[previous_company] 			= $this->employment[$cnt][company];
								$insert_sql_array[previous_company_phone] 		= $this->employment[$cnt+1][phone];
								$insert_sql_array[previous_company_address] 	= $this->employment[$cnt+2][address];
								$insert_sql_array[previous_company_supervisor] 	= $this->employment[$cnt+3][supervisor];
								$insert_sql_array[previous_job_title] 			= $this->employment[$cnt+4][job];
								$insert_sql_array[previous_starting_salary] 	= $this->employment[$cnt+5][start_salary];
								$insert_sql_array[previous_ending_salary] 		= $this->employment[$cnt+6][end_salary];
								$insert_sql_array[responsibility] 				= $this->employment[$cnt+7][responsibility];
								$insert_sql_array[from] 						= $this->employment[$cnt+8][from];
								$insert_sql_array[to] 							= $this->employment[$cnt+9][to];
								$insert_sql_array[reason] 						= $this->employment[$cnt+10][reason_leaving];
								$insert_sql_array[may_contact_supervisor] 		= $this->employment[$cnt+11][previous_supervisor];
								$this->db->insert(EM_APPLICATION_PREVIOUS_EMPLOYMENT,$insert_sql_array);
							}
							$cnt+=12;
						}	
						break;
		}
	}

	
	function Application_Form($runat,$error='')
	{
		switch($runat){
		
		case 'local':
					$formName = 'frmRegistration';
					$ControlNames=array("first_name"	=>array('first_name',"''","Required !!","spanfirst_name"),
										"last_name"		=>array('last_name',"''","Required !!","spanlast_name"),
										"street_address"	=>array('street_address',"''","Required !!","spanstreet_address"),
										"city"	=>array('city',"''","Required !!","spancity"),
										/*"state"	=>array('state',"","Required !!","spanstate"),*/
										"zip"	=>array('zip',"''","Required !!","spanzip"),
										/*"person_contact_data_phone_numbers__location"	=>array('person_contact_data_phone_numbers__location',"","Required !!","spanphone"),
										"person_contact_data_email_addresses__address"	=>array('person_contact_data_email_addresses__address',"","Required !!","spanemail"),*/
										"position_applied"	=>array('position_applied',"''","Required !!","spanposition_applied"),
										"signature"			=>array('signature',"''","Required !!","spansignature"),
										//"captcha"			=>array('captcha',"''","Required !!","span_captcha"),
									);
					
					$this->ValidationFunctionName="frmRegistration_CheckValidity";
					
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($formName,$ControlNames,$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
					//Display Form
					?>
					<div class="contact_forms">
					<table  align="center" cellpadding="0" cellspacing="0" class="contact_forms" width="100%">
					  <tr>
						<td>
							<?php //include_once("header.php");?>
						</td>
					  </tr>
					  <tr>
						<td>
							<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" name="frmRegistration" id="frmRegistration" >
								<input type="hidden" name="setform" value="set"/>
								<table  border="0" align="center" cellpadding="0" cellspacing="0" width="100%">
									
									<tr>
										<td align="left" valign="top" class="text4">Complete Mobile Dentistry, Inc.</td>
									</tr>
									<tr>
										<td align="left" valign="top">&nbsp;</td>
									</tr>
									<tr>
										<td align="left" valign="top" class="text4">Employment Application - Please answer all questions as completely as possible and do not write "See Resume".   The use of this form does not necessarily indicate that a position is open, nor does it constitute an offer or a contract of employment.  All applications are considered active for 90 days.      </td>
									</tr>
									<tr>
										<td align="left" valign="top"><?php if($error) {
										  $this->Form->ErrorString .= $error;
										  echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
										}
										 ?></td>
									</tr>
									<tr>
										<td align="left" valign="top">
											<table  border="0" cellspacing="0" cellpadding="0" class="border1" width="100%">
												<tr>
													<td height="25" align="left" valign="middle" class="login">APPLICANT INFORMATION</td>
												</tr>
												<tr>
													<td align="left" valign="top">&nbsp;</td>
												</tr>
												<tr>
													<td align="left" valign="top">
														<table  border="0" cellspacing="0" cellpadding="0" width="100%">
															<tr>
																<td  align="left" valign="top">
																	<table  border="0" cellspacing="0" cellpadding="0" width="100%">
																		<tr>
																			<td  height="30" align="left" valign="top" class="text1">Last Name </td>
																			<td  align="left" valign="top">
																				<input name="last_name" id="last_name" value="<?php echo $_POST['last_name'];?>" type="text" class="textbox1" />
																				&nbsp;<span id="spanlast_name" class="normal">&nbsp;</span>														</td>
																		</tr>
																	</table>											</td>
																<td align="left" valign="top">
																	<table  border="0" cellspacing="0" cellpadding="0" width="100%">
																		<tr>
																			<td  height="30" align="left" valign="top" class="text1">First Name </td>
																			<td  align="left" valign="top">
																				<input name="first_name" id="first_name" value="<?php echo $_POST['first_name'];?>" type="text" class="textbox1" />
																				&nbsp;<span id="spanfirst_name" class="normal">&nbsp;</span>														</td>
																		</tr>
																	</table>											</td>
																<td  align="left" valign="top" class="text1">M. I.</td>
																<td  align="left" valign="top">
																<input type="text" name="middle_name" id="middle_name" value="<?php echo $_POST['middle_name'];?>" class="textbox2" /></td>
																<td  align="left" valign="top" class="text1">
																<table  border="0" cellspacing="0" cellpadding="0" width="100%">
																  <tr>
																	<td valign="top">Date&nbsp;<input type="text" name="date_posted" id="date_posted" class="date" readonly="readonly" value="<?php echo $_POST['date_posted'];?>" /></td>
																	<td><img src="images/b_calendar.png" id='button1' onMouseOver="javascript:document.body.style.cursor='hand';" onMouseOut="javascript:document.body.style.cursor='default';">&nbsp;<span id="spandate_posted" class="normal">&nbsp;</span></td>
																  </tr>
																</table>					
																
																
																<script language="javascript" type="text/javascript">
																	var startDate;
																	var endDate;
														
																	function resetDates() {
																		startDate = endDate = null;
																	}
															
																	function filterDates1(cal) {
																		startDate = new Date(cal.date)
																		startDate.setHours(0,0,0,0)	// used for compares without TIME
																		
																		if (endDate == null) { 
																			Zapatec.Calendar.setup({
																				inputField     :    "date_posted",
																				button         :    "button1",  // What will trigger the popup of the calendar
																				ifFormat       :    "%m-%d-%Y",
																				date           :     cal.date,
																				showsTime      :     false,          //no time
																				dateStatusFunc :    disallowDateBefore, //the function to call
																				onUpdate       :    filterDates2
																			});
																		}
																	}
					
																	function filterDates2(cal) {
																		var date = cal.date;
																		endDate = new Date(cal.date)
																		endDate.setHours(0,0,0,0)	// used for compares without TIME
																	}
						
																	
																	function disallowDateBefore(dateCheckOut) {
																		dateCheckOut.setHours(0,0,0,0)
																		if ((startDate != null) && startDate > dateCheckOut)
																			// startDate is defined, make sure cal date is NOT before start date
																			return true; 
																		
																		var now = new Date()
																		now.setHours(0,0,0,0)
																		if (dateCheckOut < now) 
																			// check out date can not be befor today if startDate NOT defined
																			return true;
															
																		return false;
																	}
										
																	
																	function disallowDateAfter(dateCheckIn) {
																		dateCheckIn.setHours(0,0,0,0)
																		if ((endDate != null) && dateCheckIn > endDate)
																			// endDate defined, calendar date can NOT be after endDate
																			return true;
															
																		var now = new Date()
																		now.setHours(0,0,0,0)
															
																		if (dateCheckIn < now)
																			// endDate NOT defined, calendar date can not be before today
																			return true;
															
																		return false;
																	}
									
																	// end hiding contents from old browsers  -->
																</script>
															
																<script type="text/javascript">
																	var cal = new Zapatec.Calendar.setup({
																								
																	 inputField     :    "date_posted",   // id of the input field
																	 button         :    "button1",  // What will trigger the popup of the calendar
																	 ifFormat       :    "%m-%d-%Y",       // format of the input field: Mar 18, 2005
																	 showsTime      :     false,          //no time
																	 dateStatusFunc :    disallowDateAfter, //the function to call
																	 onUpdate       :    filterDates1
															
																	});
															
																</script>											</td>
															</tr>
														</table>								</td>
												</tr>
												<tr>
													<td align="left" valign="top">
														<table  border="0" cellspacing="0" cellpadding="0" width="100%">
															<tr>
																<td  align="left" valign="middle">
																	<table  border="0" cellspacing="0" cellpadding="0" width="100%">
																		<tr>
																			<td  height="30" align="left" valign="top" class="text1">Street Address </td>
																			<td  align="left" valign="top">
																				<input name="street_address" id="street_address" value="<?php echo $_POST['street_address'];?>" type="text" class="textbox4" />
																				&nbsp;<span id="spanstreet_address" class="normal">&nbsp;</span>														</td>
																		</tr>
																	</table>											</td>
																<td align="left" valign="top" class="text1">Apartment/Unit #&nbsp;&nbsp;<input type="text" name="apartment_no" id="apartment_no" value="<?php echo $_POST['apartment_no'];?>" class="textbox3" />&nbsp;<span id="spanapartment_no" class="normal">&nbsp;</span></td>
															</tr>
														</table>								</td>
												</tr>
												<tr>
													<td align="left" valign="top">
														<table  border="0" cellspacing="0" cellpadding="0" width="100%">
															<tr>
																<td  align="left" valign="top">
																	<table  border="0" cellspacing="0" cellpadding="0" width="100%">
																		<tr>
																			<td  height="30" align="left" valign="top" class="text1">City</td>
																			<td  align="left" valign="top">
																				<input name="city" id="city" value="<?php echo $_POST['city'];?>" type="text" class="textbox1" />
																				&nbsp;<span id="spancity" class="normal">&nbsp;</span>																			</td>
																		</tr>
																	</table>																</td>
																<td  align="left" valign="middle">
																	<table  border="0" cellspacing="0" cellpadding="0" width="100%">
																		<tr>
																			<td  height="30" align="left" valign="top" class="text1">State</td>
																			<td  align="left" valign="top">
																				<select name="state" id="state" class="dropdownlist1">
																					<option value="">Select State</option>
																					<?php
																						$state=file("state_us.inc");
																						foreach($state as $val){
																						$state = trim($val);
																					?>
																					<option <?php if($_POST['state']==$state){echo 'selected="selected"';}?> value="<?php echo $state;?>"><?php echo $state;?></option>
																					<?php
																					}
																					?>
																				</select>
																				
																				&nbsp;<span id="spanstate" class="normal">&nbsp;</span>																			</td>
																		</tr>
																	</table>																</td>
																<td  align="left" valign="top" class="text1">Zip Code&nbsp;&nbsp;<input name="zip" id="zip" value="<?php echo $_POST['zip'];?>" type="text" class="textbox5" /> <br />
																&nbsp;<span id="spanzip" class="normal">&nbsp;</span></td>
																<td  align="left" valign="middle">&nbsp;</td>
															</tr>
														</table>													</td>
												</tr>
												<tr>
												<td><table class="table" width="100%">
												<?php $this->contact->AddContactPhone('local'); ?>
												<?php $this->contact->AddContactEmail('local'); ?>
												<?php $this->contact->AddContactIm('local');?>
												<?php $this->contact->AddContactWebsite('local');?>
												<?php $this->contact->AddContactTwitter('local');?>
												</table></td></tr>
												<tr>
													<td align="left" valign="top">
														<table  border="0" cellspacing="0" cellpadding="0" width="100%">
															<tr>
																<td  align="left" valign="top">
																	<table  border="0" cellspacing="0" cellpadding="0" width="100%">
																		<tr>
																			<td  height="30" align="left" valign="middle" class="text1"></td>
																			<td  align="left" valign="middle">
																				
																				&nbsp;<span id="spanphone" class="normal">&nbsp;</span>																			</td>
																		</tr>
																		
																</table>																</td>
																<td  align="left" valign="top">
																	<table  border="0" cellspacing="0" cellpadding="0" width="100%">
																		<tr>
																			<td  height="30" align="left" valign="middle" class="text1"></td>
																			<td  align="left" valign="middle">
																				
																				&nbsp;<span id="spanemail" class="normal">&nbsp;</span>																			</td>
																		</tr>
																	</table>																</td>
															</tr>
														</table>													</td>
												</tr>
												
												<tr>
													<td align="left" valign="top">
														<table  border="0" cellspacing="0" cellpadding="0" width="100%">
															<tr>
																<td  align="left" valign="top">
																	<table  border="0" cellspacing="0" cellpadding="0" width="100%">
																		<tr>
																			<td  height="30" align="left" valign="middle" class="text1">Date Available</td>
																			<td  align="left" valign="middle">
																				<input name="date_available" id="date_available" type="text" value="<?php echo $_POST['date_available'];?>" class="date_available" readonly="readonly" />&nbsp;<img src="images/b_calendar.png" id='button2' onmouseover="javascript:document.body.style.cursor='hand';" onmouseout="javascript:document.body.style.cursor='default';">&nbsp;<span id="spandate_available" class="normal">&nbsp;</span>
																				<script language="javascript" type="text/javascript">
																					var startDate;
																					var endDate;
																		
																					function resetDates() {
																						startDate = endDate = null;
																					}
																			
																					function filterDates1(cal) {
																						startDate = new Date(cal.date)
																						startDate.setHours(0,0,0,0)	// used for compares without TIME
																						
																						if (endDate == null) { 
																							Zapatec.Calendar.setup({
																								inputField     :    "date_available",
																								button         :    "button2",  // What will trigger the popup of the calendar
																								ifFormat       :    "%m-%d-%Y",
																								date           :     cal.date,
																								showsTime      :     false,          //no time
																								dateStatusFunc :    disallowDateBefore, //the function to call
																								onUpdate       :    filterDates2
																							});
																						}
																					}
									
																					function filterDates2(cal) {
																						var date = cal.date;
																						endDate = new Date(cal.date)
																						endDate.setHours(0,0,0,0)	// used for compares without TIME
																					}
										
																					
																					function disallowDateBefore(dateCheckOut) {
																						dateCheckOut.setHours(0,0,0,0)
																						if ((startDate != null) && startDate > dateCheckOut)
																							// startDate is defined, make sure cal date is NOT before start date
																							return true; 
																						
																						var now = new Date()
																						now.setHours(0,0,0,0)
																						if (dateCheckOut < now) 
																							// check out date can not be befor today if startDate NOT defined
																							return true;
																			
																						return false;
																					}
														
																					
																					function disallowDateAfter(dateCheckIn) {
																						dateCheckIn.setHours(0,0,0,0)
																						if ((endDate != null) && dateCheckIn > endDate)
																							// endDate defined, calendar date can NOT be after endDate
																							return true;
																			
																						var now = new Date()
																						now.setHours(0,0,0,0)
																			
																						if (dateCheckIn < now)
																							// endDate NOT defined, calendar date can not be before today
																							return true;
																			
																						return false;
																					}
									
																				// end hiding contents from old browsers  -->
																		</script>
															
																		<script type="text/javascript">
																			var cal = new Zapatec.Calendar.setup({
																										
																			 inputField     :    "date_available",   // id of the input field
																			 button         :    "button2",  // What will trigger the popup of the calendar
																			 ifFormat       :    "%m-%d-%Y",       // format of the input field: Mar 18, 2005
																			 showsTime      :     false,          //no time
																			 dateStatusFunc :    disallowDateAfter, //the function to call
																			 onUpdate       :    filterDates1
																	
																			});
																	
																		</script>														</td>
																		</tr>
																	</table>											</td>
																<td  align="left" valign="top">
																	<table  border="0" cellspacing="0" cellpadding="0" width="100%">
																		<tr>
																			<td  height="30" align="left" valign="top" class="text1"><?
																			if( $global["feature"]["social_security_number"] == true ){
																				echo "Social Security No.";
																			}
																			?></td>
																			<td  align="left" valign="middle">
																			<?
																			if( $global["feature"]["social_security_number"] == true ){
																			echo '
																				<input name="social_security_number" id="social_security_number" value="' . $_POST['social_security_number'] . '" type="text" class="textbox1" />
																				&nbsp;<span id="spansocial_security_number" class="normal">&nbsp;</span>														';
																				} else { echo "&nbsp;"; } 
																			?>																				</td>
																		</tr>
																	</table>											</td>
																<td  align="left" valign="top">
																	<table  border="0" cellspacing="0" cellpadding="0" width="100%">
																		<tr>
																			<td  height="30" align="left" valign="top" class="text1">Desired Salary $</td>
																			<td  align="left" valign="top">
																				<input onFocus="preventOpp('desired_salary');" name="desired_salary" id="desired_salary" value="<?php echo $_POST['desired_salary'];?>" type="text" class="textbox7" />
																		  &nbsp;<span id="spandesired_salary" class="normal">&nbsp;</span>														</td>
																		</tr>
																	</table>											</td>
															</tr>
														</table>								</td>
												</tr>
												<tr>
													<td align="left" valign="top">
														<table  border="0" cellspacing="0" cellpadding="0" width="100%">
															<tr>
																<td  height="30" align="left" valign="middle" class="text1">Position Applied for</td>
																<td  align="left" valign="middle">
																	<select name="position_applied" id="position_applied" class="dropdownlist1">
																		<option value="">---- Select Position For ----</option>
																		<option value="Dentist" <?php if($_POST['position_applied']=="Dentist"){echo 'selected="selected"';}?>>Dentist</option>
																		<option value="Dental Professionals" <?php if($_POST['position_applied']=="Dental Professionals"){echo 'selected="selected"';}?>>Dental Professionals</option>
																		<option value="Team Lead" <?php if($_POST['position_applied']=="Team Lead"){echo 'selected="selected"';}?>>Team Lead</option>
																		<option value="Other">Other</option>
																	</select>
																	&nbsp;<span id="spanposition_applied" class="normal">&nbsp;</span>											</td>
															</tr>
														</table>								</td>
												</tr>
												<tr>
													<td align="left" valign="top">
														<table  border="0" cellspacing="0" cellpadding="0" width="100%">
															<tr>
																<td  height="30" align="left" valign="middle" class="text1">Have you ever applied with this company before?&nbsp;&nbsp;&nbsp;Yes&nbsp;<input  value="yes" name="before_applied" id="before_applied_yes" type="radio" />&nbsp;&nbsp;NO&nbsp;<input  value="no" name="before_applied" id="before_applied_no" type="radio"  />
																&nbsp;<span id="spanbefore_applied" class="normal">&nbsp;</span>											</td>
																<td  align="left" valign="middle" class="text1">If so , When?&nbsp;&nbsp;&nbsp;<input value="" name="before_applied_when" id="before_applied_when" type="text" class="textbox5" />&nbsp;<span id="spanbefore_applied_when" class="normal">&nbsp;</span></td>
															</tr>
														</table>								</td>
												</tr>
												<tr>
													<td align="left" valign="top">
														<table  border="0" cellspacing="0" cellpadding="0" width="100%">
															<tr>
																<td  height="30" align="left" valign="middle" class="text1">Have you ever worked for this company?&nbsp;&nbsp;&nbsp;Yes&nbsp;
																<input name="ever_worked" id="ever_worked_yes" type="radio" value="yes" />
																&nbsp;&nbsp;NO&nbsp;
																<input name="ever_worked" id="ever_worked_no" type="radio" value="no" />&nbsp;<span id="spanever_worked" class="normal">&nbsp;</span></td>
																<td width="340" align="left" valign="middle" class="text1">If so , When?&nbsp;&nbsp;&nbsp;
																<input name="ever_worked_when" id="ever_worked_when" type="text" class="textbox5" />&nbsp;<span id="spanever_worked_when" class="normal">&nbsp;</span>											</td>
															</tr>
														</table>								</td>
												</tr>
												<tr>
													<td align="left" valign="top">
														<table  border="0" cellspacing="0" cellpadding="0" width="100%">
															<tr>
																<td  height="30" align="left" valign="middle" class="text1">Have you ever been convicted of a felony ?&nbsp;&nbsp;&nbsp;Yes&nbsp;
																<input name="felony" id="felony_yes" type="radio" value="yes" />
																&nbsp;&nbsp;NO&nbsp;
																<input name="felony" id="felony_no" type="radio" value="no" />
																&nbsp;<span id="spanfelony" class="normal">&nbsp;</span>											</td>
																<td  align="left" valign="middle" class="text1">If so , When?&nbsp;&nbsp;&nbsp;
																<input name="felony_when" id="felony_when" type="text" class="textbox5" /></td>
															</tr>
														</table>								</td>
												</tr>
											<tr>
											<td align="left" valign="top">&nbsp;</td>
											</tr>
											</table>					</td>
									</tr>
									<tr>
										<td align="left" valign="top">&nbsp;</td>
									</tr>
									<tr>
										<td align="left" valign="top">
											<table  border="0" cellspacing="0" cellpadding="0" class="border1" width="100%">
												<tr>
													<td height="25" align="left" valign="middle" class="login">EDUCATION</td>
												</tr>
												<tr>
													<td align="left" valign="top"><table><?php $this->addApplicationEducation('local');?></table></td>
												</tr>
											</table>					</td>
									</tr>
									<tr>
										<td align="left" valign="top">&nbsp;</td>
									</tr>
									<tr>
										<td align="left" valign="top">
											<table  border="0" cellspacing="0" cellpadding="0" class="border1" width="100%">
												<tr>
													<td height="25" align="left" valign="middle" class="login">REFERENCES</td>
												</tr>
												<tr>
													<td height="30" align="left" valign="middle" class="text2">Please list three professional references.</td>
												</tr>
												<tr>
													<td align="left" valign="top"><table><?php $this->addApplicationReference('local');?></table></td>
												</tr>
											</table>					</td>
									</tr>
									<tr>
										<td align="left" valign="top">&nbsp;</td>
									</tr>
									<tr>
										<td align="left" valign="top">
											<table  border="0" cellspacing="0" cellpadding="0" class="border1" width="100%">
												<tr>
													<td height="25" align="left" valign="middle" class="login">PREVIOUS EMPLOYMENT - BEGINNING WITH YOUR MOST RECENT EMPLOYER</td>
												</tr>
												<tr>
													<td align="left" valign="middle">&nbsp;</td>
												</tr>
											<tr>
												<td align="left" valign="top"><table><?php $this->addApplicationEmployment('local');?></table></td>
											</tr>
										</table>				</td>
									</tr>
									<tr>
									  <td align="left" valign="top">&nbsp;</td>
									</tr>									
								  <tr>
									<td align="left" valign="top">&nbsp;</td>
								</tr>
								<tr>
									<td align="left" valign="top">
										<table  border="0" cellspacing="0" cellpadding="0" class="border1" width="100%">
											<tr>
												<td height="25" align="left" valign="middle" class="login">MILITARY SERVICE</td>
											</tr>
											<tr>
												<td align="left" valign="middle">&nbsp;</td>
											</tr>
											<tr>
												<td align="left" valign="top">
													<table  border="0" cellspacing="0" cellpadding="0" width="100%">
														<tr>
															<td  align="left" valign="top">
																<table  border="0" cellspacing="0" cellpadding="0" width="100%">
																	<tr>
																		<td  height="30" align="left" valign="middle" class="text1">Branch</td>
																		<td  align="left" valign="middle">
																			<input name="military_branch" id="military_branch" value="<?php echo $_POST['military_branch'];?>" type="text" class="textbox9" />													</td>
																	</tr>
																</table>										</td>
															<td  align="left" valign="top">
																<table  border="0" cellspacing="0" cellpadding="0" width="100%">
																	<tr>
																		<td  height="30" align="left" valign="middle" class="text1">From</td>
																		<td  align="left" valign="middle">
																			<table  border="0" cellspacing="0" cellpadding="0" width="100%">
																			  <tr>
																				<td  align="left"><input value="<?php echo $_POST['military_from'];?>" name="military_from" id="military_from" readonly="readonly" type="text" class="date" /></td>
																				<td  align="left">&nbsp;<img src="images/b_calendar.png" id='button18' onmouseover="javascript:document.body.style.cursor='hand';" onmouseout="javascript:document.body.style.cursor='default';"></td>
																			  </tr>
																			  <script type="text/javascript">
																					var cal = new Zapatec.Calendar.setup({
																					
																					inputField:"military_from",
																					ifFormat:"%m-%d-%Y",
																					button:"button18",
																					showsTime:false
																			
																					});
																					
																			</script>
																			</table>
																		</td>
																		<td  align="left" valign="middle" class="text1">To</td>
																		<td  align="left" valign="middle">
																			<table  border="0" cellspacing="0" cellpadding="0" width="100%">
																			  <tr>
																				<td align="left"><input value="<?php echo $_POST['military_to'];?>" name="military_to" id="military_to" readonly="readonly" type="text" class="date" /></td>
																				<td align="left"><img src="images/b_calendar.png" id='button19' onmouseover="javascript:document.body.style.cursor='hand';" onmouseout="javascript:document.body.style.cursor='default';"></td>
																			  </tr>
																			  <script type="text/javascript">
																					var cal = new Zapatec.Calendar.setup({
																					
																					inputField:"military_to",
																					ifFormat:"%m-%d-%Y",
																					button:"button19",
																					showsTime:false
																			
																					});
																					
																			</script>
																			</table>
																		</td>
																	</tr>
																	
					
																		
																</table>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td align="left" valign="top">
													<table border="0" cellspacing="0" cellpadding="0" width="100%">
														<tr>
															<td  align="left" valign="top">
																<table  border="0" cellspacing="0" cellpadding="0" width="100%">
																	<tr>
																		<td  height="30" align="left" valign="middle" class="text1">Rank at Discharge</td>
																		<td  align="left" valign="middle"><input name="rank_at_discharge" id="rank_at_discharge" value="<?php echo $_POST['rank_at_discharge'];?>" type="text" class="textbox9" /></td>
																	</tr>
																</table>										</td>
															<td  align="left" valign="top">
																<table  border="0" cellspacing="0" cellpadding="0" width="100%">
																	<tr>
																		<td  height="30" align="left" valign="middle" class="text1">Type of Discharge</td>
																		<td  align="left" valign="middle"><input name="type_of_discharge" id="type_of_discharge" value="<?php echo $_POST['type_of_discharge'];?>" type="text" class="textbox9" /></td>
																	</tr>
																</table>										</td>
														</tr>
													</table>							</td>
											</tr>
											<tr>
												<td align="left" valign="top">
													<table  border="0" cellspacing="0" cellpadding="0" width="100%">
														<tr>
															<td  height="30" align="left" valign="middle" class="text1">If other than honorable, explain</td>
															<td  align="left" valign="middle"><input name="honorable" id="honorable" value="<?php echo $_POST['honorable'];?>" type="text" class="textbox14" /></td>
														</tr>
													</table>							</td>
											</tr>
											<tr>
												<td align="left" valign="top">&nbsp;</td>
											</tr>
										</table></td>
								</tr>
								<tr>
									<td align="left" valign="top">&nbsp;</td>
								</tr>
								<tr>
									<td align="left" valign="top">
										<table  border="0" cellspacing="0" cellpadding="0" class="border1" width="100%">
											<tr>
												<td height="25" align="left" valign="middle" class="login">DISCLAIMER AND SIGNATURE</td>
											</tr>
											<tr>
												<td align="left" valign="middle">&nbsp;</td>
											</tr>
										
											<tr>
												<td align="left" valign="top" class="text3">I certify that my answers are true and complete to the best of my knowledge.  I authorize investigation and verification of all statements contained in this application of employment.<br /> 
													If this application leads to employment, I understand that false or misleading information in my application or interview 
													may be cause for termination.
												</td>
											</tr>
											<tr>
												<td align="left" valign="top">
													<table  border="0" cellspacing="0" cellpadding="0" width="100%">
														<tr>
															<td  height="30" align="left" valign="middle" class="text1">Signature</td>
															<td  align="left" valign="middle">
																<input name="signature" id="signature" value="<?php echo $_POST['signature'];?>" type="text" class="textbox14" />
																&nbsp;<span id="spansignature">&nbsp;</span>															</td>
														</tr>
														<!--
														<tr>
														  <td height="30" align="left" valign="middle" class="text1"><span class="lable" style="vertical-align:top;">Image Verification:</span></td>
														  <td align="left" valign="middle"><span class="field"><img src="captha.php" alt="Image Verification Captcha" title="Enter Code Below" /></span></td>
													  </tr>
														<tr>
														  <td height="30" align="left" valign="middle" class="text1"><span class="lable"><a href="#?action=1TB_inline&width=35%&height=195&inlineId=Info" class="thickbox" style="font-family:Verdana, Arial, Helvetica, sans-serif;color:#3366CC;font-size:11px;font-weight:bold;font-style:italic;text-decoration:none;">What's this?</a>:</span></td>
														  <td align="left" valign="middle"><span class="field">
														    <input type="text" class="frm1_textbox2" name="captcha" id="captcha" value="Enter text above here"  onfocus="javascript:this.value=''" />
														  </span><span id="span_captcha"></span></td>
													  </tr>
													  -->
													</table>
												</td>
											</tr>
											<tr>
												<td align="left" valign="top">&nbsp;</td>
											</tr>
										</table>				</td>
								</tr>
								<tr>
									<td align="left" valign="top">&nbsp;</td>
								</tr>
								<tr>
									<td align="center" valign="top">
										<input name="btnRegistration" type="image" src="images/sign.gif" id="btnRegistration" onClick="return <?php echo $this->ValidationFunctionName?>();" />
									</td>
								</tr>
								<tr>
									<td align="left" valign="top">&nbsp;</td>
								</tr>
								<tr>
									<td align="left" valign="top">&nbsp;</td>
								</tr>
								</table>
							</form>
						</td>
					  </tr>
					  <tr>
				<td class="lable" style="vertical-align:top;">&nbsp;</td>
				<td class="field">&nbsp;</td>
			</tr>
					  <tr>
						<td>&nbsp;</td>
					  </tr>
					</table>
					</div>
					
					
</div>
<div id="Info" style="display: none;"><br />
	<p align="center" class="thickmsg" style="padding-right:15px;">Image Verification or "Captcha" is an easy way to verify that there is an actual person submitting the form rather than a computer program. Computer programs are unable to read the text on the image which prevents any unauthorized use of our mailing system allowing us to more quickly respond to your inquiries.</p>
    <p></p>	
</div>
				<?php	
				break;
				
			case 'server':
			
				extract($_POST);
				$return =true;
					if($this->Form->ValidField($first_name,'empty','Please Enter Your First Name')==false)
						$return =false;
					if($this->Form->ValidField($last_name,'empty','Please Enter Your Last')==false)
						$return =false;
					if($this->Form->ValidField($street_address,'empty','Please Enter Street Address')==false)
						$return =false;
					if($this->Form->ValidField($city,'empty','Please Enter City')==false)
						$return =false;	
					/*if($this->Form->ValidField($state,'empty','Please Enter State')==false)
						$return =false;*/
					if($this->Form->ValidField($zip,'empty','Please Enter Zip Code')==false)
						$return =false;
					/*if($this->Form->ValidField($phone,'empty','Please Enter Phone Number')==false)
						$return =false;
					if($this->Form->ValidField($email,'empty','Please Enter Email-Id')==false)
						$return =false;	*/
					if($this->Form->ValidField($position_applied,'empty','Please Enter Position Applied For')==false)
						$return =false;
					if($this->Form->ValidField($signature,'empty','Please Enter Signature')==false)
						$return =false;	
					
					if($return){
				
					//if ( $_POST['captcha'] == $_SESSION['security_code'] )
					if (1 == 1)  // rather than remove if / else statement just make statement that is always true.
					{
				/***************************** insert as contact ***************************************/
				
				$insert_sql_array = array();
				$insert_sql_array['first_name'] = $_POST[first_name];
				$insert_sql_array['type'] = 'People';
				$insert_sql_array['user_id'] = $user_id;
				$insert_sql_array['last_name'] = $_POST[last_name];

				$this->db->insert(TBL_CONTACT,$insert_sql_array);
				$this->cont_id=$this->db->last_insert_id();
				
				$this->contact->contact_id = $this->cont_id;
				$this->contact->AddContactPhone('server');
				$this->contact->AddContactEmail('server');
				$this->contact->AddContactIm('server');
				$this->contact->AddContactWebsite('server');
				$this->contact->AddContactTwitter('server');
				
				/*$insert_sql_array = array();
				$insert_sql_array['contact_id'] = $this->contact_id;
				$insert_sql_array['number'] = $_POST[phone];
				$insert_sql_array['type'] = 'Home';
				$this->db->insert(CONTACT_PHONE,$insert_sql_array);
				
				$insert_sql_array = array();
				$insert_sql_array['contact_id'] = $this->contact_id;
				$insert_sql_array['email'] = $_POST[email];
				$insert_sql_array['type'] = 'Home';
				$this->db->insert(CONTACT_EMAIL,$insert_sql_array);*/
				
			/*$contact_info = array();
			
			$contact_info['user_id'] = $user_id;
			$contact_info['name'] = $_POST[first_name];
			$contact_info['lname'] = $_POST[last_name];
			$contact_info['type'] = 'People';
			$contact_info['phone'] = $_POST[phone];
			$contact_info['email'] = $_POST[email];
	
			$this->contact_id = $this->contact->Addcontact_On_Fly($contact_info,'',true);*/
			
			/*****************************************insert address in contact*******************/
			$insert_sql_array = array();
			$insert_sql_array['contact_id'] = $this->cont_id;
			$insert_sql_array['street_address'] = $_POST['street_address'];
			$insert_sql_array['city'] = $_POST['city'];
			$insert_sql_array['state'] = $_POST['state'];
			$insert_sql_array['zip'] = $_POST['zip'];
			$insert_sql_array['type'] = 'Home';
			$this->db->insert(CONTACT_ADDRESS,$insert_sql_array);
			
			/**************************************************************************************/
					//////security setting /////////////////
				$this->security->SetModule_name($this->module);
				$this->security->SetModule_id($this->cont_id);
				$this->security->Add_Rule_Webform('server');
				///////////////////////////////////////
				/*************************************************************************************/
				
				$insert_sql_array = array();
				$insert_sql_array['contact_id'] = $this->cont_id;
				$this->db->insert(EM_WEB_APP_INFO,$insert_sql_array);
				
				/*****************************************************************************************/
				$insert_sql_array = array();
				$insert_sql_array['contact_id'] = $this->cont_id;
				$insert_sql_array['date_available'] = $_POST['date_available'];
				$insert_sql_array['desired_salary'] = $_POST['desired_salary'];
				$insert_sql_array['position_applied'] = $_POST['position_applied'];
				$insert_sql_array['before_applied'] = $_POST['before_applied'];
				$insert_sql_array['felony'] = $_POST['felony'];
				$insert_sql_array['ever_worked'] = $_POST['ever_worked'];
				$insert_sql_array['mi'] = $_POST['middle_name'];
				$insert_sql_array['apartment'] = $_POST['apartment_no'];
				$insert_sql_array['date'] = $_POST['date_posted'];
				$insert_sql_array['when_felony'] = $_POST['felony_when'];
				$insert_sql_array['when_ever_worked'] = $_POST['ever_worked_when'];
				$insert_sql_array['when_before_applied'] = $_POST['before_applied_when'];
				$insert_sql_array['sign'] = $_POST['signature'];
				$this->db->insert(EM_APPLICATION_GENERAL,$insert_sql_array);
				
				/*********************************************************************************************/	
				
				$this->addApplicationEducation('server');
				/*if($_POST['high_school']) {
					$insert_sql_array = array();
					$insert_sql_array['contact_id'] = $this->cont_id;
					$insert_sql_array['education_id'] = $_POST['high_school'];
					$insert_sql_array['title'] = $_POST['title_hs'];
					$insert_sql_array['address'] = $_POST['address_high_school'];
					$insert_sql_array['education_from'] = $_POST['high_school_from'];
					$insert_sql_array['education_to'] = $_POST['high_school_to'];
					$insert_sql_array['graduate'] = $_POST['graduate'];
					$insert_sql_array['graduate_degree'] = $_POST['graduate_degree'];
					$this->db->insert(EM_APPLICATION_EDUCATION,$insert_sql_array);
				}
				
				if($_POST['college']) {
					$insert_sql_array = array();
					$insert_sql_array['contact_id'] = $this->cont_id;
					$insert_sql_array['education_id'] = $_POST['college'];
					$insert_sql_array['title'] = $_POST['title_cg'];
					$insert_sql_array['address'] = $_POST['college_address'];
					$insert_sql_array['education_from'] = $_POST['college_from'];
					$insert_sql_array['education_to'] = $_POST['college_to'];
					$insert_sql_array['graduate'] = $_POST['college_graduate'];
					$insert_sql_array['graduate_degree'] = $_POST['college_graduate_degree'];
					$this->db->insert(EM_APPLICATION_EDUCATION,$insert_sql_array);
				}
				
				if($_POST['other_degree']) {
					$insert_sql_array = array();
					$insert_sql_array['contact_id'] = $this->cont_id;
					$insert_sql_array['education_id'] = $_POST['other_degree'];
					$insert_sql_array['title'] = $_POST['title_ot'];
					$insert_sql_array['address'] = $_POST['other_education_address'];
					$insert_sql_array['education_from'] = $_POST['other_degree_from'];
					$insert_sql_array['education_to'] = $_POST['other_degree_to'];
					$insert_sql_array['graduate'] = $_POST['other_graduate'];
					$insert_sql_array['graduate_degree'] = $_POST['other_degree_details'];
					$this->db->insert(EM_APPLICATION_EDUCATION,$insert_sql_array);
				}*/
								
				/*********************************************************************************************/	
				
				$this->addApplicationReference('server');
				/*$i;
				for($i=1; $i<4; $i+=1){
					if($_POST['reference_name'.$i]) {
						$insert_sql_array = array();
						$insert_sql_array['contact_id'] = $this->cont_id;
						$insert_sql_array['reference_name'] = $_POST['reference_name'.$i];
						$insert_sql_array['reference_relation'] = $_POST['reference_relation'.$i];
						$insert_sql_array['reference_company'] = $_POST['reference_company'.$i];
						$insert_sql_array['reference_phone'] = $_POST['reference_phone'.$i];
						$insert_sql_array['reference_address'] = $_POST['reference_address'.$i];					
						$this->db->insert(EM_APPLICATION_REFERENCES,$insert_sql_array);
					}				
				}*/
				
				/*********************************************************************************************/			
					
				$this->addApplicationEmployment('server');
				/*for($i=1; $i<4; $i+=1){
					if($_POST['previous_company'.$i]) {
						$insert_sql_array = array();
						$insert_sql_array['contact_id'] = $this->cont_id;
						$insert_sql_array['previous_company'] = $_POST['previous_company'.$i];
						$insert_sql_array['previous_company_phone'] = $_POST['previous_company_phone'.$i];
						$insert_sql_array['previous_company_address'] = $_POST['previous_company_address'.$i];
						$insert_sql_array['previous_company_supervisor'] = $_POST['previous_company_supervisor'.$i];
						$insert_sql_array['previous_job_title'] = $_POST['previous_job_title'.$i];
						$insert_sql_array['previous_starting_salary'] = $_POST['previous_starting_salary'.$i];
						$insert_sql_array['previous_ending_salary'] = $_POST['previous_ending_salary'.$i];
						$insert_sql_array['responsibility'] = $_POST['previous_responsibilities'.$i];
						$insert_sql_array['from'] = $_POST['previous_from'.$i];
						$insert_sql_array['to'] = $_POST['previous_to'.$i];
						$insert_sql_array['reason'] = $_POST['previous_reason_for_leaving'.$i];
						$insert_sql_array['may_contact_supervisor'] = $_POST['previous_supervisor'.$i];					
						$this->db->insert(EM_APPLICATION_PREVIOUS_EMPLOYMENT,$insert_sql_array);
					}				
				}*/
				
				
				/*********************************************************************************************/			
					
				
				$insert_sql_array = array();
				$insert_sql_array['contact_id'] = $this->cont_id;
				$insert_sql_array['military_branch'] = $_POST['military_branch'];
				$insert_sql_array['military_from'] = $_POST['military_from'];
				$insert_sql_array['military_to'] = $_POST['military_to'];
				$insert_sql_array['rank_at_discharge'] = $_POST['rank_at_discharge'];
				$insert_sql_array['type_of_discharge'] = $_POST['type_of_discharge'];
				$insert_sql_array['honorable'] = $_POST['honorable'];
				$this->db->insert(EM_APPLICATION_MILITARY_SERVICE,$insert_sql_array);
				
				
				/*******************************************************************************************/
				$_SESSION[msg] = 'Application has been sent';
				?>
				<script type="text/javascript">
				window.location = '<?php echo $_SERVER['PHP_SELF'] ?>'
				</script>
				<?php
				}
				else
				{
					
					
					$this->Application_Form('local',$error='Image verification does not match');
				}
				}
				else
				{
				echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
				$this->Application_Form('local');
				} 

							
				break;
		default : echo 'Wrong Paramemter passed';
		}
	
	}
	
	function Application_Form_Employers($runat,$error='')
	{
		switch($runat){
		
		case 'local':
					$formName = 'Application_Form_Employers';
					$ControlNames=array("first_name"	=>array('first_name',"''","First name is required !!","spanfirst_name"),
										"last_name"		=>array('last_name',"''","Last name is required !!","spanlast_name"),
										"street_address"	=>array('street_address',"''","Address is required !!","spanstreet_address"),
										"city"	=>array('city',"''","City is required !!","spancity"),
										"zip"	=>array('zip',"''","Zip code is required !!","spanzip")
										// "captcha"			=>array('captcha',"''","Image varification is required !!","span_captcha")
									);
					
					$this->ValidationFunctionName="frmApplication_Form_Employers_CheckValidity";
					
					$JsCodeForFormValidation=$this->ValidityAlert->ShowJSFormValidationCodeAlert($formName,$ControlNames,$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
					//Display Form
					?>
					<div class="contact_forms">
					<table width="850" align="center" cellpadding="0" cellspacing="0" class="contact_forms">
					  <tr>
						<td>
							<?php //include_once("header.php");?>						</td>
					  </tr>
					  <tr>
						<td>
							<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" name="<?php echo $formName ?>" id="<?php echo $formName ?>" >
								<input type="hidden" name="setform" value="set"/>
								<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
									
									<tr>
										<td colspan="2" align="left" valign="top" class="text4">Dental Health Management Solutions.</td>
									</tr>
									<tr>
										<td colspan="2" align="left" valign="top">&nbsp;</td>
									</tr>
									<tr>
										<td colspan="2" align="left" valign="top" class="text4">Thanks for considering us for your staffing needs. We look forward to helping you find the high quality professionals you need. Please fill out the below form below to get started.     </td>
									</tr>
									<tr>
										<td colspan="2" align="left" valign="top"><?php if($error) {
										  $this->Form->ErrorString .= $error;
										  echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
										}
										 ?></td>
									</tr>
									<tr>
										<td colspan="2" align="left" valign="top">
											<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border1">
												<tr>
													<td height="25" align="left" valign="middle" class="login">PERSONAL INFORMATION</td>
												</tr>
												<tr>
													<td align="left" valign="top">&nbsp;</td>
												</tr>
												<tr>
													<td align="left" valign="top">
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td width="35%" align="left" valign="top">
																	<table width="35%" border="0" cellspacing="0" cellpadding="0">
																		<tr>
																			<td width="100" height="30" align="left" valign="top" class="text1">Last Name </td>
																			<td width="200" align="left" valign="top">
																				<input name="last_name" id="last_name" value="<?php echo $_POST['last_name'];?>" type="text" class="textbox1" />
																				&nbsp;<span id="spanlast_name" class="normal">&nbsp;</span>														</td>
																		</tr>
																	</table>											</td>
																<td width="35%" align="left" valign="top">
																	<table width="35%" border="0" cellspacing="0" cellpadding="0">
																		<tr>
																			<td width="100" height="30" align="left" valign="top" class="text1">First Name </td>
																			<td width="200" align="left" valign="top">
																				<input name="first_name" id="first_name" value="<?php echo $_POST['first_name'];?>" type="text" class="textbox1" />
																				&nbsp;<span id="spanfirst_name" class="normal">&nbsp;</span>														</td>
																		</tr>
																	</table>											</td>
																<td width="100" align="left" valign="top" class="text1">M. I.</td>
																<td width="200" align="left" valign="top">
																<input type="text" name="middle_name" id="middle_name" value="<?php echo $_POST['middle_name'];?>" class="textbox2" /></td>
																<td width="35%" align="left" valign="top" class="text1">
																<table width="100%" border="0" cellspacing="0" cellpadding="0">
																  <tr>
																	<td valign="top">Date&nbsp;<input type="text" name="date_posted" id="date_posted" class="date" readonly="readonly" value="<?php echo $_POST['date_posted'];?>" /></td>
																	<td><img src="images/b_calendar.png" id='button1' onMouseOver="javascript:document.body.style.cursor='hand';" onMouseOut="javascript:document.body.style.cursor='default';">&nbsp;<span id="spandate_posted" class="normal">&nbsp;</span></td>
																  </tr>
																</table>					
																
																
																<script language="javascript" type="text/javascript">
																	var startDate;
																	var endDate;
														
																	function resetDates() {
																		startDate = endDate = null;
																	}
															
																	function filterDates1(cal) {
																		startDate = new Date(cal.date)
																		startDate.setHours(0,0,0,0)	// used for compares without TIME
																		
																		if (endDate == null) { 
																			Zapatec.Calendar.setup({
																				inputField     :    "date_posted",
																				button         :    "button1",  // What will trigger the popup of the calendar
																				ifFormat       :    "%m-%d-%Y",
																				date           :     cal.date,
																				showsTime      :     false,          //no time
																				dateStatusFunc :    disallowDateBefore, //the function to call
																				onUpdate       :    filterDates2
																			});
																		}
																	}
					
																	function filterDates2(cal) {
																		var date = cal.date;
																		endDate = new Date(cal.date)
																		endDate.setHours(0,0,0,0)	// used for compares without TIME
																	}
						
																	
																	function disallowDateBefore(dateCheckOut) {
																		dateCheckOut.setHours(0,0,0,0)
																		if ((startDate != null) && startDate > dateCheckOut)
																			// startDate is defined, make sure cal date is NOT before start date
																			return true; 
																		
																		var now = new Date()
																		now.setHours(0,0,0,0)
																		if (dateCheckOut < now) 
																			// check out date can not be befor today if startDate NOT defined
																			return true;
															
																		return false;
																	}
										
																	
																	function disallowDateAfter(dateCheckIn) {
																		dateCheckIn.setHours(0,0,0,0)
																		if ((endDate != null) && dateCheckIn > endDate)
																			// endDate defined, calendar date can NOT be after endDate
																			return true;
															
																		var now = new Date()
																		now.setHours(0,0,0,0)
															
																		if (dateCheckIn < now)
																			// endDate NOT defined, calendar date can not be before today
																			return true;
															
																		return false;
																	}
									
																	// end hiding contents from old browsers  -->
																</script>
															
																<script type="text/javascript">
																	var cal = new Zapatec.Calendar.setup({
																								
																	 inputField     :    "date_posted",   // id of the input field
																	 button         :    "button1",  // What will trigger the popup of the calendar
																	 ifFormat       :    "%m-%d-%Y",       // format of the input field: Mar 18, 2005
																	 showsTime      :     false,          //no time
																	 dateStatusFunc :    disallowDateAfter, //the function to call
																	 onUpdate       :    filterDates1
															
																	});
															
																</script>											</td>
															</tr>
														</table>								</td>
												</tr>
												<tr>
													<td align="left" valign="top">
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td width="600" align="left" valign="middle">
																	<table width="600" border="0" cellspacing="0" cellpadding="0">
																		<tr>
																			<td width="100" height="30" align="left" valign="top" class="text1">Street Address </td>
																			<td width="500" align="left" valign="top">
																				<input name="street_address" id="street_address" value="<?php echo $_POST['street_address'];?>" type="text" class="textbox4" />
																				&nbsp;<span id="spanstreet_address" class="normal">&nbsp;</span>														</td>
																		</tr>
																	</table>											</td>
																<td align="left" valign="top" class="text1">Apartment/Unit #&nbsp;&nbsp;<input type="text" name="apartment_no" id="apartment_no" value="<?php echo $_POST['apartment_no'];?>" class="textbox3" />&nbsp;<span id="spanapartment_no" class="normal">&nbsp;</span></td>
															</tr>
														</table>								</td>
												</tr>
												<tr>
													<td align="left" valign="top">
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td width="35%" align="left" valign="top">
																	<table width="35%" border="0" cellspacing="0" cellpadding="0">
																		<tr>
																			<td width="100" height="30" align="left" valign="top" class="text1">City</td>
																			<td width="200" align="left" valign="top">
																				<input name="city" id="city" value="<?php echo $_POST['city'];?>" type="text" class="textbox1" />
																				&nbsp;<span id="spancity" class="normal">&nbsp;</span>																			</td>
																		</tr>
																	</table>																</td>
																<td width="35%" align="left" valign="middle">
																	<table width="35%" border="0" cellspacing="0" cellpadding="0">
																		<tr>
																			<td width="100" height="30" align="left" valign="top" class="text1">State</td>
																			<td width="200" align="left" valign="top">
																				<select name="state" id="state" class="dropdownlist1">
																					<option value="">Select State</option>
																					<?php
																						$state=file("state_us.inc");
																						foreach($state as $val){
																						$state = trim($val);
																					?>
																					<option <?php if($_POST['state']==$state){echo 'selected="selected"';}?> value="<?php echo $state;?>"><?php echo $state;?></option>
																					<?php
																					}
																					?>
																				</select>
																				
																				&nbsp;<span id="spanstate" class="normal">&nbsp;</span>																			</td>
																		</tr>
																	</table>																</td>
																<td width="230" align="left" valign="top" class="text1">Zip Code&nbsp;&nbsp;<input name="zip" id="zip" value="<?php echo $_POST['zip'];?>" type="text" class="textbox5" /> <br />
																&nbsp;<span id="spanzip" class="normal">&nbsp;</span></td>
																<td width="10" align="left" valign="middle">&nbsp;</td>
															</tr>
														</table>													</td>
												</tr>
												<tr>
												<td><table class="table">
												<?php $this->contact->AddContactPhone('local'); ?>
												<?php $this->contact->AddContactEmail('local'); ?>
												<?php $this->contact->AddContactIm('local');?>
												<?php $this->contact->AddContactWebsite('local');?>
												<?php $this->contact->AddContactTwitter('local');?>
												</table></td></tr>
												<tr>
													<td align="left" valign="top">&nbsp;</td>
												</tr>
											</table>					</td>
									</tr>
									<tr>
										<td colspan="2" align="left" valign="top">&nbsp;</td>
									</tr>
								<tr>
									<td colspan="2" align="left" valign="top">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="2" align="left" valign="top">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border1">
											<tr>
												<td height="25" align="left" valign="middle" class="login">TELL US ABOUT YOUR NEEDS: </td>
											</tr>
											<tr>
												<td align="left" valign="middle"><textarea name="notes" class="textbox14" id="notes" style="width:90%; height:130px"><?php echo $_POST['notes'];?></textarea> </td>
											</tr>
										
											<tr>
												<td align="left" valign="top" class="text3">&nbsp;</td>
											</tr>
											<tr>
												<td align="left" valign="top">
													<table width="105" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td width="105" height="30" align="left" valign="middle" class="text1">&nbsp;</td>
														</tr>
														<tr>
														  <td height="30" align="left" valign="middle" class="text1">&nbsp;</td>
													  </tr>
														<tr>
														  <td height="30" align="left" valign="middle" class="text1">&nbsp;</td>
													  </tr>
													</table>												</td>
											</tr>
											<tr>
												<td align="left" valign="top">&nbsp;</td>
											</tr>
										</table>				</td>
								</tr>
								<tr>
								  <td colspan="2" align="left" valign="top">&nbsp;</td>
								  </tr>
								<!--  
								<tr >
									<td width="125" align="left" valign="top" class="text1"><span class="text1"><span class="lable" style="vertical-align:top;">Image Verification:</span></span></td>
								    <td width="817" align="left" valign="top"><span class="field"><img src="captha.php" alt="Image Verification Captcha" title="Enter Code Below" /></span></td>
								</tr>
								<tr>
								  <td align="left" valign="top" class="text1"><span class="text1"><span class="lable"><a href="#?action=1TB_inline&width=35%&height=195&inlineId=Info" class="thickbox" style="font-family:Verdana, Arial, Helvetica, sans-serif;color:#3366CC;font-size:11px;font-weight:bold;font-style:italic;text-decoration:none;">What's this?</a>:</span></span></td>
								  <td align="left" valign="top"><span class="field">
                                  <input type="text" class="frm1_textbox2" name="captcha" id="captcha" value="Enter text above here"  onfocus="javascript:this.value=''" />
                                  </span><span id="span_captcha"></span></td>
								  </tr>
								  -->
								<tr>
									<td colspan="2" align="center" valign="top">
										<input name="btnRegistration" type="image" src="images/sign.gif" id="btnRegistration" onClick="return <?php echo $this->ValidationFunctionName?>();" />									</td>
								</tr>
								<tr>
									<td colspan="2" align="left" valign="top">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="2" align="left" valign="top">&nbsp;</td>
								</tr>
								</table>
							</form>						</td>
					  </tr>
					  <tr>
				<td class="lable" style="vertical-align:top;">&nbsp;</td>
				<td class="field">&nbsp;</td>
			</tr>
					  <tr>
						<td>&nbsp;</td>
					  </tr>
					</table>
					</div>
					
					
</div>
<div id="Info" style="display: none;"><br />
	<p align="center" class="thickmsg" style="padding-right:15px;">Image Verification or "Captcha" is an easy way to verify that there is an actual person submitting the form rather than a computer program. Computer programs are unable to read the text on the image which prevents any unauthorized use of our mailing system allowing us to more quickly respond to your inquiries.</p>
    <p></p>	
</div>
				<?php	
				break;
				
			case 'server':
			
				extract($_POST);
				$return =true;
					if($this->Form->ValidField($first_name,'empty','Please Enter Your First Name')==false)
						$return =false;
					if($this->Form->ValidField($last_name,'empty','Please Enter Your Last')==false)
						$return =false;
					if($this->Form->ValidField($street_address,'empty','Please Enter Street Address')==false)
						$return =false;
					if($this->Form->ValidField($city,'empty','Please Enter City')==false)
						$return =false;	
					if($this->Form->ValidField($zip,'empty','Please Enter Zip Code')==false)
						$return =false;
					
					if($return){
				
					if ( $_POST['captcha'] == $_SESSION['security_code'] ){
				/***************************** insert as contact ***************************************/
				
				$contact_info = array();
				$contact_info['name'] = $_POST[first_name];
				$contact_info['type'] = 'People';
				$contact_info['user_id'] = $user_id;
				$contact_info['lname'] = $_POST[last_name];
				$contact_info['note'][0]['user_id'] = $user_id;
				$contact_info['note'][0]['description'] = $_POST[notes];
				$contact_info['note'][0]['module_name'] = 'TBL_CONTACT';

				$this->cont_id=$this->contact->Addcontact_On_Fly($contact_info,'employers','true');
				
				$this->contact->contact_id = $this->cont_id;
				$this->contact->AddContactPhone('server');
				$this->contact->AddContactEmail('server');
				$this->contact->AddContactIm('server');
				$this->contact->AddContactWebsite('server');
				$this->contact->AddContactTwitter('server');
				
			
			/*****************************************insert address in contact*******************/
			$insert_sql_array = array();
			$insert_sql_array['contact_id'] = $this->cont_id;
			$insert_sql_array['street_address'] = $_POST['street_address'];
			$insert_sql_array['city'] = $_POST['city'];
			$insert_sql_array['state'] = $_POST['state'];
			$insert_sql_array['zip'] = $_POST['zip'];
			$insert_sql_array['type'] = 'Home';
			$this->db->insert(CONTACT_ADDRESS,$insert_sql_array);
			
			/**************************************************************************************/
				/*****************************************************************************************/
				$insert_sql_array = array();
				$insert_sql_array['contact_id'] = $this->cont_id;
				$insert_sql_array['date_available'] = $_POST['date_available'];
				$insert_sql_array['mi'] = $_POST['middle_name'];
				$insert_sql_array['apartment'] = $_POST['apartment_no'];
				$insert_sql_array['date'] = $_POST['date_posted'];
				$this->db->insert(EM_APPLICATION_GENERAL,$insert_sql_array);
				
				/*********************************************************************************************/	
				
				$insert_sql_array = array();
				$insert_sql_array['contact_id'] = $this->cont_id;
				$this->db->insert(EM_WEB_APP_INFO,$insert_sql_array);
				
				
				/*******************************************************************************************/
				$_SESSION[msg] = 'Application has been sent';
				?>
				<script type="text/javascript">
				window.location = '<?php echo $_SERVER['PHP_SELF'] ?>'
				</script>
				<?php
				}
				else
				{
					
					
					$this->Application_Form_Employers('local',$error='Image verification does not match');
				}
				}
				else
				{
				echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
				$this->Application_Form_Employers('local');
				} 

							
				break;
		default : echo 'Wrong Paramemter passed';
		}
	
	}

	
	function ViewResume($contact_id)
	{
		
		$this->cont_id = $contact_id;
			
			$sql_contact="select * from ".TBL_CONTACT." where contact_id='".$this->cont_id."'";
			$result_contact = $this->db->query($sql_contact,__FILE__,__LINE__);
			$row_contact = $this->db->fetch_array($result_contact);
			
			$sql_app_general="select * from ".EM_APPLICATION_GENERAL." where contact_id='".$this->cont_id."'";
			$result_app_general = $this->db->query($sql_app_general,__FILE__,__LINE__);
			$row_app_general = $this->db->fetch_array($result_app_general);
			
			$sql_education_hs="select * from ".EM_APPLICATION_EDUCATION." where contact_id='".$this->cont_id."' and title='High School'";
			$result_education_hs = $this->db->query($sql_education_hs,__FILE__,__LINE__);
			$row_education_hs = $this->db->fetch_array($result_education_hs);
			
			$sql_education_col="select * from ".EM_APPLICATION_EDUCATION." where contact_id='".$this->cont_id."' and title='College'";
			$result_education_col = $this->db->query($sql_education_col,__FILE__,__LINE__);
			$row_education_col = $this->db->fetch_array($result_education_col);
			
			$sql_education_othr="select * from ".EM_APPLICATION_EDUCATION." where contact_id='".$this->cont_id."' and title='Other'";
			$result_education_othr = $this->db->query($sql_education_othr,__FILE__,__LINE__);
			$row_education_othr = $this->db->fetch_array($result_education_othr);
			
			$sql_references="select * from ".EM_APPLICATION_REFERENCES." where contact_id='".$this->cont_id."'";
			$result_references = $this->db->query($sql_references,__FILE__,__LINE__);
			
			$sql_prev_employment="select * from ".EM_APPLICATION_PREVIOUS_EMPLOYMENT." where contact_id='".$this->cont_id."'";
			$result_prev_employment = $this->db->query($sql_prev_employment,__FILE__,__LINE__);
			
			$sql_military="select * from ".EM_APPLICATION_MILITARY_SERVICE." where contact_id='".$this->cont_id."'";
			$result_military = $this->db->query($sql_military,__FILE__,__LINE__);
			$row_military = $this->db->fetch_array($result_military);
			
			
		
		?>
			<table width="100%" class="table">
			<tr>
			<td valign="top">
			<a href="contact_profile.php?contact_id=<?php echo $this->cont_id;?>" >
			<?php if(!file_exists($this->directory.'/'.$row[image]) or $row[image]==''){ ?><img src="images/person.gif" /><?php } else{ ?>
			<div style="max-width:250px; overflow:hidden"><img src="thumb.php?file=<?php echo $row_contact['directory']."/".$row_contact['picture']; ?>&sizex=250"  /></div><?php } ?></a>
			</td>
			
			<td valign="top">
			
			
				<table class="table" width="100%">
					<tr><th colspan="2" id="group_header">APPLICANT INFORMATION</th>
					  <th colspan="2" ><a href="<?php echo $_SERVER['PHP_SELF'] ?>?contact_id=<?php echo $_REQUEST[contact_id] ?>&index=Edit">Edit Profile </a></th>
					</tr>
					<tr><th colspan="2" id="group_header" ><?php echo $row_contact['first_name']; ?> <?php echo $row_contact['last_name']; ?>&nbsp;</th>
					  <td colspan="2" >&nbsp;</td>
					</tr><tr>
					  <th colspan="2">Position Applied for:</th><td colspan="2"><?php echo $row_app_general['position_applied']; ?></td>	  
					</tr><tr>
					<th colspan="2">Desired Salary $:</th><td colspan="2"><?php echo $row_app_general['desired_salary']; ?></td>
					</tr><tr>
					<th>Applied with this company before:</th><td><?php echo $row_app_general['before_applied']; ?></td><th>When:</th><td></td>
					</tr><tr>
					<th>Worked for this company before:</th><td><?php echo $row_app_general['ever_worked']; ?></td><th>When:</th><td></td>
					</tr><tr>
					<th>Ever been convicted of a felony:</th><td><?php echo $row_app_general['felony']; ?></td><th>When:</th><td></td>
					</tr>
			
				<tr><td colspan="4"></td></tr>
				
					
					<tr>
						<th colspan="4" id="group_header">EDUCATION</th>
					<?php if($row_education_hs['education_id']) { ?>
					</tr><tr>
						<th>High School:</th><td colspan="3"> <?php echo $row_education_hs['education_id']; ?> </td>
					</tr><tr>	
						<th>From:</th><td> <?php echo $row_education_hs['education_from']; ?></td><th>To:</th><td> <?php echo $row_education_hs['education_to']; ?></td>
					</tr><tr>	
						<th >Address:</th><td colspan="3"> <?php echo $row_education_hs['address']; ?></td>
					</tr><tr>	
						<th>Did you graduate:</th><td> <?php echo $row_education_hs['graduate']; ?></td><th>Degree:</th><td> <?php echo $row_education_hs['graduate_degree']; ?></td>
					<?php } ?>
					<?php if($row_education_col['education_id']) { ?>
					</tr><tr>
						<th>College:</th><td colspan="3"> <?php echo $row_education_col['education_id']; ?></td>
					</tr><tr>	
						<th>From:</th><td> <?php echo $row_education_col['education_from']; ?></td><th>To:</th><td> <?php echo $row_education_col['education_to']; ?></td>
					</tr><tr>	
						<th>Address:</th><td colspan="3"> <?php echo $row_education_col['address']; ?></td>
					</tr><tr>	
						<th>Did you graduate:</th><td> <?php echo $row_education_col['graduate']; ?></td><th>Degree:</th><td> <?php echo $row_education_col['graduate_degree']; ?></td>
					<?php } ?>
					<?php if($row_education_othr['education_id']) { ?>
					</tr><tr>
						<th>Other:</th><td colspan="3"> <?php echo $row_education_othr['education_id']; ?></td>
					</tr><tr>	
						<th>From:</th><td> <?php echo $row_education_othr['education_from']; ?></td><th>To:</th><td> <?php echo $row_education_othr['education_to']; ?></td>
					</tr><tr>	
						<th>Address:</th><td colspan="3"> <?php echo $row_education_othr['address']; ?></td>
					</tr><tr>	
						<th>Did you graduate:</th><td> <?php echo $row_education_othr['graduate']; ?></td><th>Degree:</th><td> <?php echo $row_education_othr['graduate_degree']; ?></td>
					</tr>
					<?php } ?>
				<tr><td colspan="4"></td></tr>
				
				
					<tr><th colspan="4" id="group_header">REFERENCES</th></tr>
					<?php 
					while($row_references = $this->db->fetch_array($result_references))
					{
					?>
					<tr><th>Full Name:</th><td colspan="3"> <?php echo $row_references['reference_name']; ?></td></tr>
					<tr><th>Company:</th><td colspan="3"> <?php echo $row_references['reference_company']; ?></td></tr>
					<tr><th>Relationship:</th><td colspan="3"> <?php echo $row_references['reference_relation']; ?></td></tr>
					<tr><th>Phone:</th><td colspan="3"> <?php echo $row_references['reference_phone']; ?></td></tr>
					<tr><th>Address:</th><td colspan="3"> <?php echo $row_references['reference_address']; ?></td></tr>
					<tr><th colspan="2">&nbsp;</th></tr>
					<?php } ?>
				
				<tr><td colspan="4"></td></tr>
				

					<tr><th colspan="4" id="group_header">PREVIOUS EMPLOYMENT - BEGINNING WITH YOUR MOST RECENT EMPLOYER</th></tr>
					<?php 
					while($row_prev_employment = $this->db->fetch_array($result_prev_employment))
					{
					?>
					<tr><th>Company:</th><td colspan="3"> <?php echo $row_prev_employment['previous_company']; ?></td></tr>
					<tr><th>Phone:</th><td colspan="3"> <?php echo $row_prev_employment['previous_company_phone']; ?></td></tr>
					<tr><th>Address:</th><td colspan="3"> <?php echo $row_prev_employment['previous_company_address']; ?></td></tr>
					<tr><th>Supervisor:</th><td colspan="3"> <?php echo $row_prev_employment['previous_company_supervisor']; ?></td></tr>
					<tr><th>Job Title:</th><td colspan="3"> <?php echo $row_prev_employment['previous_job_title']; ?></td></tr>
					<tr><th>Starting Salary $:</th><td> <?php echo $row_prev_employment['previous_starting_salary']; ?></td>
					    <th>Ending Salary $:</th><td> <?php echo $row_prev_employment['previous_ending_salary']; ?></td></tr>
					<tr><th>Responsibilities:</th><td colspan="3"> <?php echo $row_prev_employment['responsibility']; ?></td></tr>
					<tr><th>From:</th><td> <?php echo $row_prev_employment['from']; ?> </td>
					    <th>To:</th><td> <?php echo $row_prev_employment['to']; ?> </td></tr>	
					<tr><th>Reason for Leaving:</th><td colspan="3"> <?php echo $row_prev_employment['reason']; ?></td></tr>	
					<tr><th >May we contact your previous supervisor for a reference:</th><td colspan="3"> <?php echo $row_prev_employment['may_contact_supervisor']; ?></td></tr>
					<tr><td colspan="4">&nbsp;</td></tr>
					<?php } ?>
				
				<tr><td colspan="4"></td></tr>
				
			
					<tr><th colspan="4" id="group_header">MILITARY SERVICE</th></tr>
					<tr><th>Branch:</th><td colspan="3"> <?php echo $row_military['military_branch']; ?></td></tr>
					<tr><th>From:</th><td> <?php echo $row_military['military_from']; ?></td>
					    <th>To:</th><td> <?php echo $row_military['military_to']; ?></td></tr>
					<tr><th>Rank at Discharge:</th><td> <?php echo $row_military['rank_at_discharge']; ?></td>
					    <th>Type of Discharge:</th><td> <?php echo $row_military['type_of_discharge']; ?></td></tr>
					<tr><th>Honorable:</th><td colspan="3"> <?php echo $row_military['honorable']; ?></td></tr>
					<tr><td colspan="4">&nbsp;</td></tr>
				</table>
				
			</td>
			</tr>
			</table>
			
			<?php
			
			
	}// end of ViewResume
	
	
	function EditResume($runat,$contact_id)
	{
		
		$this->cont_id = $contact_id;
		$this->contact_id = $contact_id;
		
		
		$FormName='editresume';
		
		switch($runat){
		
			case 'local':
			
			$sql_contact="select * from ".TBL_CONTACT." where contact_id='".$this->cont_id."'";
			$result_contact = $this->db->query($sql_contact,__FILE__,__LINE__);
			$row_contact = $this->db->fetch_array($result_contact);
			
			$sql_app_general="select * from ".EM_APPLICATION_GENERAL." where contact_id='".$this->cont_id."'";
			$result_app_general = $this->db->query($sql_app_general,__FILE__,__LINE__);
			$row_app_general = $this->db->fetch_array($result_app_general);
			if($this->db->num_rows($result_app_general)<=0)
			{
				$insert_sql_array = array();
						 $insert_sql_array[contact_id] = $this->cont_id;
						
						 $this->db->insert(EM_APPLICATION_GENERAL,$insert_sql_array);
			}
			
			$sql_address="select * from ".CONTACT_ADDRESS." where contact_id='".$this->cont_id."'";
			$result_address = $this->db->query($sql_address,__FILE__,__LINE__);
			$row_address = $this->db->fetch_array($result_address);
			
			
			$sql_education_hs="select * from ".EM_APPLICATION_EDUCATION." where contact_id='".$this->cont_id."' and title='High School'";
			$result_education_hs = $this->db->query($sql_education_hs,__FILE__,__LINE__);
			$row_education_hs = $this->db->fetch_array($result_education_hs);
			if($this->db->num_rows($result_education_hs)<=0)
			{
				$insert_sql_array = array();
						 $insert_sql_array[contact_id] = $this->cont_id;
						 
						 $this->db->insert(EM_APPLICATION_EDUCATION,$insert_sql_array);
			}
			
			$sql_education_col="select * from ".EM_APPLICATION_EDUCATION." where contact_id='".$this->cont_id."' and title='College'";
			$result_education_col = $this->db->query($sql_education_col,__FILE__,__LINE__);
			$row_education_col = $this->db->fetch_array($result_education_col);
			
			$sql_education_othr="select * from ".EM_APPLICATION_EDUCATION." where contact_id='".$this->cont_id."' and title='Other'";
			$result_education_othr = $this->db->query($sql_education_othr,__FILE__,__LINE__);
			$row_education_othr = $this->db->fetch_array($result_education_othr);
			
			$sql_references="select * from ".EM_APPLICATION_REFERENCES." where contact_id='".$this->cont_id."'";
			$result_references = $this->db->query($sql_references,__FILE__,__LINE__);
			if($this->db->num_rows($result_references)<=0)
			{
				$insert_sql_array = array();
						 $insert_sql_array[contact_id] = $this->cont_id;
						 
						 $this->db->insert(EM_APPLICATION_REFERENCES,$insert_sql_array);
			}
			
			$sql_prev_employment="select * from ".EM_APPLICATION_PREVIOUS_EMPLOYMENT." where contact_id='".$this->cont_id."'";
			$result_prev_employment = $this->db->query($sql_prev_employment,__FILE__,__LINE__);
			if($this->db->num_rows($result_prev_employment)<=0)
			{
				$insert_sql_array = array();
						 $insert_sql_array[contact_id] = $this->cont_id;
						 
						 $this->db->insert(EM_APPLICATION_PREVIOUS_EMPLOYMENT,$insert_sql_array);
			}
			
			
			$sql_military="select * from ".EM_APPLICATION_MILITARY_SERVICE." where contact_id='".$this->cont_id."'";
			$result_military = $this->db->query($sql_military,__FILE__,__LINE__);
			$row_military = $this->db->fetch_array($result_military);
			if($this->db->num_rows($result_military)<=0)
			{
				$insert_sql_array = array();
						 $insert_sql_array[contact_id] = $this->cont_id;
						 
						 $this->db->insert(EM_APPLICATION_MILITARY_SERVICE,$insert_sql_array);
			}
			
			
			
			
			//create client side validation
			$formName = 'frmRegistration';
					$ControlNames=array("first_name"	=>array('first_name',"''","Required !!","spanfirst_name"),
										"last_name"		=>array('last_name',"''","Required !!","spanlast_name"),
										"street_address"	=>array('street_address',"''","Required !!","spanstreet_address"),
										"city"	=>array('city',"''","Required !!","spancity"),
										/*"state"	=>array('state',"","Required !!","spanstate"),*/
										"zip"	=>array('zip',"''","Required !!","spanzip"),
										/*"person_contact_data_phone_numbers__location"	=>array('person_contact_data_phone_numbers__location',"","Required !!","spanphone"),
										"person_contact_data_email_addresses__address"	=>array('person_contact_data_email_addresses__address',"","Required !!","spanemail"),*/
										"position_applied"	=>array('position_applied',"''","Required !!","spanposition_applied"),
										"signature"			=>array('signature',"''","Required !!","spansignature"),
										//"captcha"			=>array('captcha',"''","Required !!","span_captcha"),
									);
					
					$this->ValidationFunctionName="frmRegistration_CheckValidity";
					
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($formName,$ControlNames,$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
		
		?>
		

<form action="<?php echo $_SERVER['PHP_SELF'];?>?contact_id=<?php echo $this->cont_id;?>&index=Edit" method="post" name="frmRegistration" id="frmRegistration" >
								<input type="hidden" name="setform" value="set"/>
								<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
									<tr>
									<td><?php $em = new  Event_Contacts(); ?>
										<div class="data_box">
										<?php echo  $em->GetContactProfile($this->cont_id); ?>
										</div>
										</td>
									</tr>
									
									<tr>
										<td align="left" valign="top"><?php if($error) {
										  $this->Form->ErrorString .= $error;
										  echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
										}
										 ?></td>
									</tr>
									<tr>
										<td align="left" valign="top">
											<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border1">
												<tr>
													<td height="25" align="left" valign="middle" class="login">APPLICANT INFORMATION</td>
												</tr>
												<tr>
													<td align="left" valign="top">&nbsp;</td>
												</tr>
												<tr>
													<td align="left" valign="top">
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td  align="left" valign="top">
																	<table width="100%" border="0" cellspacing="0" cellpadding="0">
																		<tr>
																			<td  height="30" align="left" valign="top" class="text1">Last Name </td>
																			<td  align="left" valign="top">
																				<input name="last_name" id="last_name" value="<?php echo $row_contact['last_name']; ?>" type="text" class="textbox1" />
																				&nbsp;<span id="spanlast_name" class="normal">&nbsp;</span>														</td>
																		</tr>
																	</table>											</td>
																<td  align="left" valign="top">
																	<table width="100%" border="0" cellspacing="0" cellpadding="0">
																		<tr>
																			<td  height="30" align="left" valign="top" class="text1">First Name </td>
																			<td  align="left" valign="top">
																				<input name="first_name" id="first_name" value="<?php echo $row_contact['first_name']; ?>" type="text" class="textbox1" />
																				&nbsp;<span id="spanfirst_name" class="normal">&nbsp;</span>														</td>
																		</tr>
																	</table>											</td>
																<td  align="left" valign="top" class="text1">M. I.</td>
																<td  align="left" valign="top">
																<input type="text" name="middle_name" id="middle_name" value="<?php echo $row_app_general['mi']; ?>" class="textbox2" /></td>
																<td  align="left" valign="top" class="text1">
																<table width="100%" border="0" cellspacing="0" cellpadding="0">
																  <tr>
																	<td valign="top">Date&nbsp;<input type="text" name="date_posted" id="date_posted" class="date" readonly="readonly" value="<?php echo $row_app_general['date'];?>" /></td>
																	<td><img src="../images/b_calendar.png" id='button1' onMouseOver="javascript:document.body.style.cursor='hand';" onMouseOut="javascript:document.body.style.cursor='default';">&nbsp;<span id="spandate_posted" class="normal">&nbsp;</span></td>
																  </tr>
																</table>					
																
																
																<script language="javascript" type="text/javascript">
																	var startDate;
																	var endDate;
														
																	function resetDates() {
																		startDate = endDate = null;
																	}
															
																	function filterDates1(cal) {
																		startDate = new Date(cal.date)
																		startDate.setHours(0,0,0,0)	// used for compares without TIME
																		
																		if (endDate == null) { 
																			Zapatec.Calendar.setup({
																				inputField     :    "date_posted",
																				button         :    "button1",  // What will trigger the popup of the calendar
																				ifFormat       :    "%m-%d-%Y",
																				date           :     cal.date,
																				showsTime      :     false,          //no time
																				dateStatusFunc :    disallowDateBefore, //the function to call
																				onUpdate       :    filterDates2
																			});
																		}
																	}
					
																	function filterDates2(cal) {
																		var date = cal.date;
																		endDate = new Date(cal.date)
																		endDate.setHours(0,0,0,0)	// used for compares without TIME
																	}
						
																	
																	function disallowDateBefore(dateCheckOut) {
																		dateCheckOut.setHours(0,0,0,0)
																		if ((startDate != null) && startDate > dateCheckOut)
																			// startDate is defined, make sure cal date is NOT before start date
																			return true; 
																		
																		var now = new Date()
																		now.setHours(0,0,0,0)
																		if (dateCheckOut < now) 
																			// check out date can not be befor today if startDate NOT defined
																			return true;
															
																		return false;
																	}
										
																	
																	function disallowDateAfter(dateCheckIn) {
																		dateCheckIn.setHours(0,0,0,0)
																		if ((endDate != null) && dateCheckIn > endDate)
																			// endDate defined, calendar date can NOT be after endDate
																			return true;
															
																		var now = new Date()
																		now.setHours(0,0,0,0)
															
																		if (dateCheckIn < now)
																			// endDate NOT defined, calendar date can not be before today
																			return true;
															
																		return false;
																	}
									
																	// end hiding contents from old browsers  -->
																</script>
															
																<script type="text/javascript">
																	var cal = new Zapatec.Calendar.setup({
																								
																	 inputField     :    "date_posted",   // id of the input field
																	 button         :    "button1",  // What will trigger the popup of the calendar
																	 ifFormat       :    "%m-%d-%Y",       // format of the input field: Mar 18, 2005
																	 showsTime      :     false,          //no time
																	 dateStatusFunc :    disallowDateAfter, //the function to call
																	 onUpdate       :    filterDates1
															
																	});
															
																</script>											</td>
															</tr>
														</table>								</td>
												</tr>
												<tr>
													<td align="left" valign="top">
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td  align="left" valign="middle">
																	<table width="100%" border="0" cellspacing="0" cellpadding="0">
																		<tr>
																			<td  height="30" align="left" valign="top" class="text1">Street Address </td>
																			<td  align="left" valign="top">
																				<input name="street_address" id="street_address" value="<?php echo $row_address['street_address'];?>" type="text" class="textbox4" />
																				&nbsp;<span id="spanstreet_address" class="normal">&nbsp;</span>														</td>
																		</tr>
																	</table>											</td>
																<td align="left" valign="top" class="text1">Apartment/Unit #&nbsp;&nbsp;<input type="text" name="apartment_no" id="apartment_no" value="<?php echo $row_app_general['apartment'];?>" class="textbox3" />&nbsp;<span id="spanapartment_no" class="normal">&nbsp;</span></td>
															</tr>
														</table>								</td>
												</tr>
												<tr>
													<td align="left" valign="top">
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td  align="left" valign="top">
																	<table width="100%" border="0" cellspacing="0" cellpadding="0">
																		<tr>
																			<td  height="30" align="left" valign="top" class="text1">City</td>
																			<td  align="left" valign="top">
																				<input name="city" id="city" value="<?php echo $row_address['city'];?>" type="text" class="textbox1" />
																				&nbsp;<span id="spancity" class="normal">&nbsp;</span>																			</td>
																		</tr>
																	</table>																</td>
																<td  align="left" valign="middle">
																	<table width="100%" border="0" cellspacing="0" cellpadding="0">
																		<tr>
																			<td width="100" height="30" align="left" valign="top" class="text1">State</td>
																			<td width="200" align="left" valign="top">
																				<select style="width:auto" name="state" id="state" class="dropdownlist1">
																					<option value="">Select State</option>
																					<?php
																						$state=file("state_us.inc");
																						foreach($state as $val){
																						$state = trim($val);
																					?>
																					<option <?php if($row_address['state']==$state){echo 'selected="selected"';}?> value="<?php echo $state;?>"><?php echo $state;?></option>
																					<?php
																					}
																					?>
																				</select>
																				
																				&nbsp;<span id="spanstate" class="normal">&nbsp;</span>																			</td>
																		</tr>
																	</table>																</td>
																<td  align="left" valign="top" class="text1">Zip Code&nbsp;&nbsp;<input name="zip" id="zip" value="<?php echo $row_address['zip'];?>" type="text" class="textbox5" /> <br />
																&nbsp;<span id="spanzip" class="normal">&nbsp;</span></td>
																<td  align="left" valign="middle">&nbsp;</td>
															</tr>
														</table>													</td>
												</tr>
												<tr>
												<td>&nbsp;
												<!--<table class="table">-->
												<?php //$this->contact->contact_id = $this->cont_id;
												 //$this->contact->EditContactPhone('local'); ?>
												<?php //$this->contact->EditContactEmail('local'); ?>
												<?php ///$this->contact->EditContactIm('local'); ?>
												<?php //$this->contact->EditContactWebsite('local'); ?>
												<?php //$this->contact->EditContactTwitter('local'); ?>
												<!--</table>--></td></tr>
												<tr>
													<td align="left" valign="top">
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td  align="left" valign="top">
																	<table  border="0" cellspacing="0" cellpadding="0">
																		<tr>
																			<td  height="30" align="left" valign="middle" class="text1"></td>
																			<td  align="left" valign="middle">
																				
																				&nbsp;<span id="spanphone" class="normal">&nbsp;</span>																			</td>
																		</tr>
																</table>																</td>
																<td  align="left" valign="top">
																	<table width="100%" border="0" cellspacing="0" cellpadding="0">
																		<tr>
																			<td  height="30" align="left" valign="middle" class="text1"></td>
																			<td  align="left" valign="middle">
																				
																				&nbsp;<span id="spanemail" class="normal">&nbsp;</span>																			</td>
																		</tr>
																	</table>																</td>
															</tr>
														</table>													</td>
												</tr>
												
												<tr>
													<td align="left" valign="top">
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td  align="left" valign="top">
																	<table width="100%" border="0" cellspacing="0" cellpadding="0">
																		<tr>
																			<td  height="30" align="left" valign="middle" class="text1">Date Available</td>
																			<td  align="left" valign="middle">
																				<input name="date_available" id="date_available" type="text" value="<?php echo $row_app_general['date_available'];?>" class="date_available" readonly="readonly" />&nbsp;<img src="../images/b_calendar.png" id='button2' onMouseOver="javascript:document.body.style.cursor='hand';" onMouseOut="javascript:document.body.style.cursor='default';">&nbsp;<span id="spandate_available" class="normal">&nbsp;</span>
																				<script language="javascript" type="text/javascript">
																					var startDate;
																					var endDate;
																		
																					function resetDates() {
																						startDate = endDate = null;
																					}
																			
																					function filterDates1(cal) {
																						startDate = new Date(cal.date)
																						startDate.setHours(0,0,0,0)	// used for compares without TIME
																						
																						if (endDate == null) { 
																							Zapatec.Calendar.setup({
																								inputField     :    "date_available",
																								button         :    "button2",  // What will trigger the popup of the calendar
																								ifFormat       :    "%m-%d-%Y",
																								date           :     cal.date,
																								showsTime      :     false,          //no time
																								dateStatusFunc :    disallowDateBefore, //the function to call
																								onUpdate       :    filterDates2
																							});
																						}
																					}
									
																					function filterDates2(cal) {
																						var date = cal.date;
																						endDate = new Date(cal.date)
																						endDate.setHours(0,0,0,0)	// used for compares without TIME
																					}
										
																					
																					function disallowDateBefore(dateCheckOut) {
																						dateCheckOut.setHours(0,0,0,0)
																						if ((startDate != null) && startDate > dateCheckOut)
																							// startDate is defined, make sure cal date is NOT before start date
																							return true; 
																						
																						var now = new Date()
																						now.setHours(0,0,0,0)
																						if (dateCheckOut < now) 
																							// check out date can not be befor today if startDate NOT defined
																							return true;
																			
																						return false;
																					}
														
																					
																					function disallowDateAfter(dateCheckIn) {
																						dateCheckIn.setHours(0,0,0,0)
																						if ((endDate != null) && dateCheckIn > endDate)
																							// endDate defined, calendar date can NOT be after endDate
																							return true;
																			
																						var now = new Date()
																						now.setHours(0,0,0,0)
																			
																						if (dateCheckIn < now)
																							// endDate NOT defined, calendar date can not be before today
																							return true;
																			
																						return false;
																					}
									
																				// end hiding contents from old browsers  -->
																		</script>
															
																		<script type="text/javascript">
																			var cal = new Zapatec.Calendar.setup({
																										
																			 inputField     :    "date_available",   // id of the input field
																			 button         :    "button2",  // What will trigger the popup of the calendar
																			 ifFormat       :    "%m-%d-%Y",       // format of the input field: Mar 18, 2005
																			 showsTime      :     false,          //no time
																			 dateStatusFunc :    disallowDateAfter, //the function to call
																			 onUpdate       :    filterDates1
																	
																			});
																	
																		</script>														</td>
																		</tr>
																	</table>											</td>
																<td  align="left" valign="top">
																	<table width="100%" border="0" cellspacing="0" cellpadding="0">
																		<tr>
																			<td   align="left" valign="top" class="text1"><?
																			if( $global["feature"]["social_security_number"] == true ){
																				echo "Social Security No.";
																			}
																			?></td>
																			<td  align="left" valign="middle">
																			<?
																			if( $global["feature"]["social_security_number"] == true ){
																			echo '
																				<input name="social_security_number" id="social_security_number" value="' . $_POST['social_security_number'] . '" type="text" class="textbox1" />
																				&nbsp;<span id="spansocial_security_number" class="normal">&nbsp;</span>														';
																				} else { echo "&nbsp;"; } 
																			?>																				</td>
																		</tr>
																	</table>											</td>
																<td align="left" valign="top">
																	<table width="100%" border="0" cellspacing="0" cellpadding="0">
																		<tr>
																			<td  height="30" align="left" valign="top" class="text1">Desired Salary $</td>
																			<td  align="left" valign="top">
																				<input onFocus="preventOpp('desired_salary');" name="desired_salary" id="desired_salary" value="<?php echo $row_app_general['desired_salary'];?>" type="text" class="textbox7" />
																		  &nbsp;<span id="spandesired_salary" class="normal">&nbsp;</span>														</td>
																		</tr>
																	</table>											</td>
															</tr>
														</table>								</td>
												</tr>
												<tr>
													<td align="left" valign="top">
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td  height="30" align="left" valign="middle" class="text1">Position Applied for</td>
																<td  align="left" valign="middle">
																	<select name="position_applied" id="position_applied" class="dropdownlist1">
																		<option value="">---- Select Position For ----</option>
																		<option value="Dentist" <?php if($row_app_general['position_applied']=="Dentist"){echo 'selected="selected"';}?>>Dentist</option>
																		<option value="Dental Professionals" <?php if($row_app_general['position_applied']=="Dental Professionals"){echo 'selected="selected"';}?>>Dental Professionals</option>
																		<option value="Team Lead" <?php if($row_app_general['position_applied']=="Team Lead"){echo 'selected="selected"';}?>>Team Lead</option>
																		<option value="Other">Other</option>
																	</select>
																	&nbsp;<span id="spanposition_applied" class="normal">&nbsp;</span>											</td>
															</tr>
														</table>								</td>
												</tr>
												<tr>
													<td align="left" valign="top">
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td  height="30" align="left" valign="middle" class="text1">Have you ever applied with this company before?&nbsp;&nbsp;&nbsp;Yes&nbsp;<input  value="yes" name="before_applied" id="before_applied_yes" type="radio" <?php if($row_app_general['before_applied']=="yes" || $row_app_general['before_applied']=="Yes"){echo 'checked="checked"';}?>/>&nbsp;&nbsp;NO&nbsp;<input  value="no" name="before_applied" id="before_applied_no" type="radio" <?php if($row_app_general['before_applied']=="no" || $row_app_general['before_applied']=="No"){echo 'checked="checked"';}?>  />
																&nbsp;<span id="spanbefore_applied" class="normal">&nbsp;</span>											</td>
																<td  align="left" valign="middle" class="text1">If so , When?&nbsp;&nbsp;&nbsp;<input value="<?php echo $row_app_general['when_before_applied'];?>" name="before_applied_when" id="before_applied_when" type="text" class="textbox5" />&nbsp;<span id="spanbefore_applied_when" class="normal">&nbsp;</span></td>
															</tr>
														</table>								</td>
												</tr>
												<tr>
													<td align="left" valign="top">
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td  height="30" align="left" valign="middle" class="text1">Have you ever worked for this company?&nbsp;&nbsp;&nbsp;Yes&nbsp;
																<input name="ever_worked" id="ever_worked_yes" type="radio" value="yes" <?php if($row_app_general['ever_worked']=="yes" || $row_app_general['ever_worked']=="Yes"){echo 'checked="checked"';}?>/>
																&nbsp;&nbsp;NO&nbsp;
																<input name="ever_worked" id="ever_worked_no" type="radio" value="no" <?php if($row_app_general['ever_worked']=="no" || $row_app_general['ever_worked']=="No"){echo 'checked="checked"';}?>/>&nbsp;<span id="spanever_worked" class="normal">&nbsp;</span></td>
																<td  align="left" valign="middle" class="text1">If so , When?&nbsp;&nbsp;&nbsp;
																<input name="ever_worked_when" id="ever_worked_when" type="text" class="textbox5" value="<?php echo $row_app_general['when_ever_worked'];?>" />&nbsp;<span id="spanever_worked_when" class="normal">&nbsp;</span>											</td>
															</tr>
														</table>								</td>
												</tr>
												<tr>
													<td align="left" valign="top">
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td   align="left" valign="middle" class="text1">Have you ever been convicted of a felony ?&nbsp;&nbsp;&nbsp;Yes&nbsp;
																<input name="felony" id="felony_yes" type="radio" value="yes"  <?php if($row_app_general['felony']=="yes" || $row_app_general['felony']=="Yes"){echo 'checked="checked"';}?>/>
																&nbsp;&nbsp;NO&nbsp;
																<input name="felony" id="felony_no" type="radio" value="no"  <?php if($row_app_general['felony']=="no" || $row_app_general['felony']=="No"){echo 'checked="checked"';}?>/>
																&nbsp;<span id="spanfelony" class="normal">&nbsp;</span>											</td>
																<td  align="left" valign="middle" class="text1">If so , When?&nbsp;&nbsp;&nbsp;
																<input name="felony_when" id="felony_when" type="text" class="textbox5" value="<?php echo $row_app_general['when_felony'];?>" /></td>
															</tr>
														</table>								</td>
												</tr>
											<tr>
											<td align="left" valign="top">&nbsp;</td>
											</tr>
											</table>					</td>
									</tr>
									<tr>
										<td align="left" valign="top">&nbsp;</td>
									</tr>
									<tr>
										<td align="left" valign="top">
											<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border1">
												<tr>
													<td height="25" align="left" valign="middle" class="login">EDUCATION</td>
												</tr>
												<tr>
													<td align="left" valign="top"><table><?php $this->EditApplicationEducation('local',$this->cont_id);?></table></td>
												</tr>
											</table>					</td>
									</tr>
									<tr>
										<td align="left" valign="top">&nbsp;</td>
									</tr>
									<tr>
										<td align="left" valign="top">
											<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border1">
												<tr>
													<td  align="left" valign="middle" class="login">REFERENCES</td>
												</tr>
												<tr>
													<td  align="left" valign="middle" class="text2">Please list three professional references.</td>
												</tr>
												<tr>
													<td align="left" valign="top"><table><?php $this->EditApplicationReference('local',$this->cont_id);?></table></td>
												</tr>
											</table>					</td>
									</tr>
									<tr>
										<td align="left" valign="top">&nbsp;</td>
									</tr>
									<tr>
										<td align="left" valign="top">
											<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border1">
												<tr>
													<td  align="left" valign="middle" class="login">PREVIOUS EMPLOYMENT - BEGINNING WITH YOUR MOST RECENT EMPLOYER</td>
												</tr>
												<tr>
													<td align="left" valign="middle">&nbsp;</td>
												</tr>
											<tr>
												<td align="left" valign="top"><table><?php $this->EditApplicationEmployment('local',$this->cont_id);?></table></td>
											</tr>
										</table>				</td>
									</tr>
									<tr>
									  <td align="left" valign="top">&nbsp;</td>
									</tr>									
								  <tr>
									<td align="left" valign="top">&nbsp;</td>
								</tr>
								<tr>
									<td align="left" valign="top">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border1">
											<tr>
												<td height="25" align="left" valign="middle" class="login">MILITARY SERVICE</td>
											</tr>
											<tr>
												<td align="left" valign="middle">&nbsp;</td>
											</tr>
											<tr>
												<td align="left" valign="top">
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td  align="left" valign="top">
																<table width="100%" border="0" cellspacing="0" cellpadding="0">
																	<tr>
																		<td  height="30" align="left" valign="middle" class="text1">Branch</td>
																		<td  align="left" valign="middle">
																			<input name="military_branch" id="military_branch" value="<?php echo $row_military['military_branch'];?>" type="text" class="textbox9" />													</td>
																	</tr>
																</table>										</td>
															<td  align="left" valign="top">
																<table width="100%" border="0" cellspacing="0" cellpadding="0">
																	<tr>
																		<td  height="30" align="left" valign="middle" class="text1">From</td>
																		<td  align="left" valign="middle">
																			<table width="100%" border="0" cellspacing="0" cellpadding="0">
																			  <tr>
																				<td  align="left"><input value="<?php echo $row_military['military_from'];?>" name="military_from" id="military_from" readonly="readonly" type="text" class="date" /></td>
																				<td w align="left">&nbsp;<img src="../images/b_calendar.png" id='button18' onMouseOver="javascript:document.body.style.cursor='hand';" onMouseOut="javascript:document.body.style.cursor='default';"></td>
																			  </tr>
																			  <script type="text/javascript">
																					var cal = new Zapatec.Calendar.setup({
																					
																					inputField:"military_from",
																					ifFormat:"%m-%d-%Y",
																					button:"button18",
																					showsTime:false
																			
																					});
																					
																			</script>
																			</table>																		</td>
																		<td  align="left" valign="middle" class="text1">To</td>
																		<td  align="left" valign="middle">
																			<table width="100%" border="0" cellspacing="0" cellpadding="0">
																			  <tr>
																				<td align="left"><input value="<?php echo $row_military['military_to'];?>" name="military_to" id="military_to" readonly="readonly" type="text" class="date" /></td>
																				<td align="left"><img src="../images/b_calendar.png" id='button19' onMouseOver="javascript:document.body.style.cursor='hand';" onMouseOut="javascript:document.body.style.cursor='default';"></td>
																			  </tr>
																			  <script type="text/javascript">
																					var cal = new Zapatec.Calendar.setup({
																					
																					inputField:"military_to",
																					ifFormat:"%m-%d-%Y",
																					button:"button19",
																					showsTime:false
																			
																					});
																					
																			</script>
																			</table>																		</td>
																	</tr>
																</table>															</td>
														</tr>
													</table>												</td>
											</tr>
											<tr>
												<td align="left" valign="top">
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td  align="left" valign="top">
																<table width="100%" border="0" cellspacing="0" cellpadding="0">
																	<tr>
																		<td  height="30" align="left" valign="middle" class="text1">Rank at Discharge</td>
																		<td  align="left" valign="middle"><input name="rank_at_discharge" id="rank_at_discharge" value="<?php echo $row_military['rank_at_discharge'];?>" type="text" class="textbox9" /></td>
																	</tr>
																</table>										</td>
															<td  align="left" valign="top">
																<table width="100%" border="0" cellspacing="0" cellpadding="0">
																	<tr>
																		<td  height="30" align="left" valign="middle" class="text1">Type of Discharge</td>
																		<td  align="left" valign="middle"><input name="type_of_discharge" id="type_of_discharge" value="<?php echo $row_military['type_of_discharge'];?>" type="text" class="textbox9" /></td>
																	</tr>
																</table>										</td>
														</tr>
													</table>							</td>
											</tr>
											<tr>
												<td align="left" valign="top">
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td width="27%" height="30" align="left" valign="middle" class="text1">If other than honorable, explain</td>
															<td width="600" align="left" valign="middle"><input name="honorable" id="honorable" value="<?php echo $row_military['honorable'];?>" type="text" class="textbox14" /></td>
														</tr>
													</table>							</td>
											</tr>
											<tr>
												<td align="left" valign="top">&nbsp;</td>
											</tr>
										</table></td>
								</tr>
								<tr>
									<td align="center" valign="top">
										<input type="submit" name="btnRegistration" value="Update"   id="btnRegistration" onClick="return <?php echo $this->ValidationFunctionName?>();" /></td>
								</tr>
								<tr>
									<td align="left" valign="top">&nbsp;</td>
								</tr>
								<tr>
									<td align="left" valign="top">&nbsp;</td>
								</tr>
								</table>
</form>

			
	<?php
			break;
			
			case 'server':
				$this->cont_id=$contact_id;
				extract($_POST);
				
				$return =true;
					if($this->Form->ValidField($first_name,'empty','Please Enter Your First Name')==false)
						$return =false;
					if($this->Form->ValidField($last_name,'empty','Please Enter Your Last')==false)
						$return =false;
					if($this->Form->ValidField($street_address,'empty','Please Enter Street Address')==false)
						$return =false;
					if($this->Form->ValidField($city,'empty','Please Enter City')==false)
						$return =false;	
					/*if($this->Form->ValidField($state,'empty','Please Enter State')==false)
						$return =false;*/
					if($this->Form->ValidField($zip,'empty','Please Enter Zip Code')==false)
						$return =false;
					/*if($this->Form->ValidField($phone,'empty','Please Enter Phone Number')==false)
						$return =false;
					if($this->Form->ValidField($email,'empty','Please Enter Email-Id')==false)
						$return =false;	*/
					if($this->Form->ValidField($position_applied,'empty','Please Enter Position Applied For')==false)
						$return =false;
					
					
					if($return){
				
					if (true){
				/***************************** insert as contact ***************************************/
				
				$update_sql_array = array();
				$update_sql_array['first_name'] = $_POST[first_name];
				$update_sql_array['type'] = 'People';
				$update_sql_array['user_id'] = $user_id;
				$update_sql_array['last_name'] = $_POST[last_name];
				
				$this->db->update(TBL_CONTACT,$update_sql_array,"contact_id",$this->cont_id);
				
				$this->contact->contact_id = $this->cont_id;
				/*$this->contact->EditContactPhone('server');
				$this->contact->EditContactEmail('server');
				$this->contact->EditContactIm('server');
				$this->contact->EditContactWebsite('server');
				$this->contact->EditContactTwitter('server');*/
				
				/*$insert_sql_array = array();
				$insert_sql_array['contact_id'] = $this->contact_id;
				$insert_sql_array['number'] = $_POST[phone];
				$insert_sql_array['type'] = 'Home';
				$this->db->insert(CONTACT_PHONE,$insert_sql_array);
				
				$insert_sql_array = array();
				$insert_sql_array['contact_id'] = $this->contact_id;
				$insert_sql_array['email'] = $_POST[email];
				$insert_sql_array['type'] = 'Home';
				$this->db->insert(CONTACT_EMAIL,$insert_sql_array);*/
				
			/*$contact_info = array();
			
			$contact_info['user_id'] = $user_id;
			$contact_info['name'] = $_POST[first_name];
			$contact_info['lname'] = $_POST[last_name];
			$contact_info['type'] = 'People';
			$contact_info['phone'] = $_POST[phone];
			$contact_info['email'] = $_POST[email];
	
			$this->contact_id = $this->contact->Addcontact_On_Fly($contact_info,'',true);*/
			
			/*****************************************insert address in contact*******************/
			
			$update_sql_array = array();
			
			$update_sql_array['street_address'] = $_POST['street_address'];
			$update_sql_array['city'] = $_POST['city'];
			$update_sql_array['state'] = $_POST['state'];
			$update_sql_array['zip'] = $_POST['zip'];
			$update_sql_array['type'] = 'Home';
			
			$this->db->update(CONTACT_ADDRESS,$update_sql_array,"contact_id",$this->cont_id);
			
			/**************************************************************************************/
					//////security setting /////////////////
			/*	$this->security->SetModule_name($this->module);
				$this->security->SetModule_id($this->cont_id);
				$this->security->Add_Rule_Webform('server');*/
				///////////////////////////////////////
				/*************************************************************************************/
				/*$sql="delete from ".EM_WEB_APP_INFO." where contact_id='$this->cont_id'";
			    $this->db->query($sql,__FILE__,__LINE__);
				$insert_sql_array = array();
				$insert_sql_array['contact_id'] = $this->cont_id;
				$this->db->insert(EM_WEB_APP_INFO,$insert_sql_array);*/
				
				/*****************************************************************************************/
				
				$update_sql_array = array();
				
				$update_sql_array['date_available'] = $_POST['date_available'];
				$update_sql_array['desired_salary'] = $_POST['desired_salary'];
				$update_sql_array['position_applied'] = $_POST['position_applied'];
				$update_sql_array['before_applied'] = $_POST['before_applied'];
				$update_sql_array['felony'] = $_POST['felony'];
				$update_sql_array['ever_worked'] = $_POST['ever_worked'];
				$update_sql_array['mi'] = $_POST['middle_name'];
				$update_sql_array['apartment'] = $_POST['apartment_no'];
				$update_sql_array['date'] = $_POST['date_posted'];
				$update_sql_array['when_felony'] = $_POST['felony_when'];
				$update_sql_array['when_ever_worked'] = $_POST['ever_worked_when'];
				$update_sql_array['when_before_applied'] = $_POST['before_applied_when'];
				//$insert_sql_array['sign'] = $_POST['signature'];
				$this->db->update(EM_APPLICATION_GENERAL,$update_sql_array,"contact_id",$this->cont_id);
				
				
				/*********************************************************************************************/	
				
				$this->EditApplicationEducation('server',$this->cont_id);
				/*if($_POST['high_school']) {
					$insert_sql_array = array();
					$insert_sql_array['contact_id'] = $this->cont_id;
					$insert_sql_array['education_id'] = $_POST['high_school'];
					$insert_sql_array['title'] = $_POST['title_hs'];
					$insert_sql_array['address'] = $_POST['address_high_school'];
					$insert_sql_array['education_from'] = $_POST['high_school_from'];
					$insert_sql_array['education_to'] = $_POST['high_school_to'];
					$insert_sql_array['graduate'] = $_POST['graduate'];
					$insert_sql_array['graduate_degree'] = $_POST['graduate_degree'];
					$this->db->insert(EM_APPLICATION_EDUCATION,$insert_sql_array);
				}
				
				if($_POST['college']) {
					$insert_sql_array = array();
					$insert_sql_array['contact_id'] = $this->cont_id;
					$insert_sql_array['education_id'] = $_POST['college'];
					$insert_sql_array['title'] = $_POST['title_cg'];
					$insert_sql_array['address'] = $_POST['college_address'];
					$insert_sql_array['education_from'] = $_POST['college_from'];
					$insert_sql_array['education_to'] = $_POST['college_to'];
					$insert_sql_array['graduate'] = $_POST['college_graduate'];
					$insert_sql_array['graduate_degree'] = $_POST['college_graduate_degree'];
					$this->db->insert(EM_APPLICATION_EDUCATION,$insert_sql_array);
				}
				
				if($_POST['other_degree']) {
					$insert_sql_array = array();
					$insert_sql_array['contact_id'] = $this->cont_id;
					$insert_sql_array['education_id'] = $_POST['other_degree'];
					$insert_sql_array['title'] = $_POST['title_ot'];
					$insert_sql_array['address'] = $_POST['other_education_address'];
					$insert_sql_array['education_from'] = $_POST['other_degree_from'];
					$insert_sql_array['education_to'] = $_POST['other_degree_to'];
					$insert_sql_array['graduate'] = $_POST['other_graduate'];
					$insert_sql_array['graduate_degree'] = $_POST['other_degree_details'];
					$this->db->insert(EM_APPLICATION_EDUCATION,$insert_sql_array);
				}*/
								
				/*********************************************************************************************/	
				
				$this->EditApplicationReference('server',$this->cont_id);
				/*$i;
				for($i=1; $i<4; $i+=1){
					if($_POST['reference_name'.$i]) {
						$insert_sql_array = array();
						$insert_sql_array['contact_id'] = $this->cont_id;
						$insert_sql_array['reference_name'] = $_POST['reference_name'.$i];
						$insert_sql_array['reference_relation'] = $_POST['reference_relation'.$i];
						$insert_sql_array['reference_company'] = $_POST['reference_company'.$i];
						$insert_sql_array['reference_phone'] = $_POST['reference_phone'.$i];
						$insert_sql_array['reference_address'] = $_POST['reference_address'.$i];					
						$this->db->insert(EM_APPLICATION_REFERENCES,$insert_sql_array);
					}				
				}*/
				
				/*********************************************************************************************/			
					
				$this->EditApplicationEmployment('server',$this->cont_id);
				/*for($i=1; $i<4; $i+=1){
					if($_POST['previous_company'.$i]) {
						$insert_sql_array = array();
						$insert_sql_array['contact_id'] = $this->cont_id;
						$insert_sql_array['previous_company'] = $_POST['previous_company'.$i];
						$insert_sql_array['previous_company_phone'] = $_POST['previous_company_phone'.$i];
						$insert_sql_array['previous_company_address'] = $_POST['previous_company_address'.$i];
						$insert_sql_array['previous_company_supervisor'] = $_POST['previous_company_supervisor'.$i];
						$insert_sql_array['previous_job_title'] = $_POST['previous_job_title'.$i];
						$insert_sql_array['previous_starting_salary'] = $_POST['previous_starting_salary'.$i];
						$insert_sql_array['previous_ending_salary'] = $_POST['previous_ending_salary'.$i];
						$insert_sql_array['responsibility'] = $_POST['previous_responsibilities'.$i];
						$insert_sql_array['from'] = $_POST['previous_from'.$i];
						$insert_sql_array['to'] = $_POST['previous_to'.$i];
						$insert_sql_array['reason'] = $_POST['previous_reason_for_leaving'.$i];
						$insert_sql_array['may_contact_supervisor'] = $_POST['previous_supervisor'.$i];					
						$this->db->insert(EM_APPLICATION_PREVIOUS_EMPLOYMENT,$insert_sql_array);
					}				
				}*/
				
				
				/*********************************************************************************************/			
					
				
				$update_sql_array = array();
				
				$update_sql_array['military_branch'] = $_POST['military_branch'];
				$update_sql_array['military_from'] = $_POST['military_from'];
				$update_sql_array['military_to'] = $_POST['military_to'];
				$update_sql_array['rank_at_discharge'] = $_POST['rank_at_discharge'];
				$update_sql_array['type_of_discharge'] = $_POST['type_of_discharge'];
				$update_sql_array['honorable'] = $_POST['honorable'];
				
				$this->db->update(EM_APPLICATION_MILITARY_SERVICE,$update_sql_array,"contact_id",$this->cont_id);
				
				
				/*******************************************************************************************/
				$_SESSION[msg] = 'Contact Details has been Updated';
				?>
				<script type="text/javascript">
				window.location = '<?php echo $_SERVER['PHP_SELF'] ?>?contact_id=<?php echo $this->cont_id;?>'
				</script>
				<?php
				}
				
				}
				else
				{
				echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
				$this->EditResume('local',$this->cont_id);
				} 

							
				break;
				default : echo 'Wrong Paramemter passed';
		}	
			
	}// end of EditResume
	
	function getResumeLink($contact_id){
		$this->cont_id = $contact_id;
		
		$sql_education="select * from ".EM_APPLICATION_EDUCATION." where contact_id='".$this->cont_id."'";
		$result_education = $this->db->query($sql_education,__FILE__,__LINE__);
		
		$sql_references="select * from ".EM_APPLICATION_REFERENCES." where contact_id='".$this->cont_id."'";
		$result_references = $this->db->query($sql_references,__FILE__,__LINE__);
			
		$sql_prev_employment="select * from ".EM_APPLICATION_PREVIOUS_EMPLOYMENT." where contact_id='".$this->cont_id."'";
		$result_prev_employment = $this->db->query($sql_prev_employment,__FILE__,__LINE__);
			
		$sql_military="select * from ".EM_APPLICATION_MILITARY_SERVICE." where contact_id='".$this->cont_id."'";
		$result_military = $this->db->query($sql_military,__FILE__,__LINE__);
		
		if($this->db->fetch_array($result_education)>0 or $this->db->fetch_array($result_references)>0 or $this->db->fetch_array($result_prev_employment)>0 or $this->db->fetch_array($result_military)>0){
			?>
			<a href="application.php?contact_id=<?php echo $this->cont_id ?>">Resume</a>
			<?php
		}
		else
		{
		?>
		<a href="#" onclick="javascript: if(confirm('Do you want to create Resume')){
		window.location='application.php?contact_id=<?php echo $this->cont_id ?>&index=Edit'}">Create Resume</a>
		<?php 
		}
		
	}

	function EditApplicationEducation($runat,$contact_id)
	{
		$this->cont_id=$contact_id;
		switch($runat){
			case 'local':
			
			?>
				<tr class="addresses">
			  <th><h2>&nbsp;</h2></th>
			  <td><div class="contact_forms addresses" id="education_list_signup"><div
			 id="blank_slate_signup_education" class="blank_slate" style="display: none;">Add an 
			education</div><div  class="contact_methods">
			
			<div class="contact_method new_contact_method street" 
			id="edu_xf6e911b9006d854b636885">
			  <div class="fields">
			  <?php 
			  $sql_education_hs="select * from ".EM_APPLICATION_EDUCATION." where contact_id='".$this->cont_id."'";
			$result_education_hs = $this->db->query($sql_education_hs,__FILE__,__LINE__);
			while($row_education_hs = $this->db->fetch_array($result_education_hs))
			{
			?>
				<div style="position: relative;">
			<p class="address">
				  Title: <select class="overlayable" id="signup_data_education_edu_xf6e911b9006d854b636885_title" name="signup[data][education][][title]" >
				    <option value="High School" <?php  if($row_education_hs['title']=='High School'){ echo ' selected="selected"';}?> >High School</option>
					<option value="College" <?php  if($row_education_hs['title']=='College'){ echo ' selected="selected"';}?> >College</option>
					<option value="Other" <?php  if($row_education_hs['title']=='Other'){ echo ' selected="selected"';}?>  >Other</option>
				  </select>
				  </p>
				  <p>University: <input
			 class="autofocus overlayable textbox8" 
			id="signup_data_education_edu_xf6e911b9006d854b636885_university" 
			name="signup[data][education][][university]" type="text" value="<?php echo $row_education_hs['education_id'];?>" />
				<input
			 class="state overlayable textbox8" 
			id="signup_data_education_edu_xf6e911b9006d854b636885_address"
			 name="signup[data][education][][address]" title="Address" 
			type="text"  value="<?php echo $row_education_hs['address'];?>" /></p>
			<p>From: <input
			 class="zip overlayable date" 
			id="signup_data_education_edu_xf6e911b9006d854b636885_from" 
			name="signup[data][education][][from]" type="text" readonly="readonly"   value="<?php echo $row_education_hs['education_from'];?>"  />
			<img src="../images/b_calendar.png" id='signup_data_education_edu_xf6e911b9006d854b636885_button3' onmouseover="javascript:document.body.style.cursor='hand';" onmouseout="javascript:document.body.style.cursor='default';">&nbsp;
			<script type="text/javascript">
				var cal = new Zapatec.Calendar.setup({
								
					inputField:"signup_data_education_edu_xf6e911b9006d854b636885_from",
					ifFormat:"%m-%d-%Y",
					button:"signup_data_education_edu_xf6e911b9006d854b636885_button3",
					showsTime:false
																		
				});
					
			</script>	
			   To: <input
			 class="zip overlayable date" 
			id="signup_data_education_edu_xf6e911b9006d854b636885_to" 
			name="signup[data][education][][to]" type="text" readonly="readonly"  value="<?php echo $row_education_hs['education_to'];?>"   /><img src="../images/b_calendar.png" id='signup_data_education_edu_xf6e911b9006d854b636885_button4' onmouseover="javascript:document.body.style.cursor='hand';" onmouseout="javascript:document.body.style.cursor='default';">&nbsp;
			<script type="text/javascript">
					var cal = new Zapatec.Calendar.setup({
					
					inputField:"signup_data_education_edu_xf6e911b9006d854b636885_to",
					ifFormat:"%m-%d-%Y",
					button:"signup_data_education_edu_xf6e911b9006d854b636885_button4",
					showsTime:false
			
					});
					
			</script>																			
			   Did you graduate? <select class="zip overlayable" id="signup_data_education_edu_xf6e911b9006d854b636885_graduate" name="signup[data][education][][graduate]"  style="width:auto" >
				  <option value="" >-Select-</option>
				  <option value="yes" <?php  if($row_education_hs['graduate']=='yes'){ echo 'selected="selected"';}?> >Yes</option>
				  <option value="no" <?php  if($row_education_hs['graduate']=='no'){ echo 'selected="selected"';}?>  >No</option>
			   </select>
			   
			  Degree: <input
			 class="zip overlayable textbox3" 
			id="signup_data_education_edu_xf6e911b9006d854b636885_degree" 
			name="signup[data][education][][degree]"  type="text" value="<?php echo $row_education_hs['graduate_degree'];?>" />
			</p>
			</div>
			<?php } ?>
			  </div>
			</div>
			<script type="text/javascript">
			//<![CDATA[
			$$('.street .overlayable').each(function(input) { ContactInfo.Overlay.wrap(input); });
			//]]>
			</script>
			
			<div id="add_signup_education" class="add_contact_method" style="display: 
			none;">
			  <div class="add"><a href="#">Add another</a></div>
			</div>
			</div></div><script type="text/javascript">
			//<![CDATA[
			new ContactInfo.ContactForm("education_list_signup", "\n\n\u003Cdiv class=\"contact_method new_contact_method street\" id=\"edu_#{safe_id}\"\u003E\n  \u003Cdiv class=\"fields\"\u003E\n    \u003Cdiv style=\"position: relative;\"\u003E\n      \u003Cp class=\"address\"\u003ETitle:\u003Cselect class=\"overlayable\" id=\"signup_data_education_edu_#{safe_id}_title\" name=\"signup[data][education][][title]\" \u003E \u003Coption value=\"High School\"\u003EHigh School\u003C/option\u003E\u003Coption value=\"College\"\u003ECollege\u003C/option\u003E\u003Coption value=\"Other\"\u003EOther\u003C/option\u003E\u003C/select\u003E\u003C/p\u003E\n\u003Cp\u003EUniversity:\u003Cinput class=\"autofocus overlayable textbox8\" id=\"signup_data_education_edu_#{safe_id}_university\" name=\"signup[data][education][][university]\"  type=\"text\"\u003EAddress:\u003Cinput class=\"state overlayable textbox8\" id=\"signup_data_education_edu_#{safe_id}_address\" name=\"signup[data][education][][address]\"  type=\"text\"\u003E\u003C/p\u003E\u003Cp\u003EFrom:\u003Cinput class=\"zip overlayable date\" id=\"signup_data_education_edu_#{safe_id}_from\" name=\"signup[data][education][][from]\"  type=\"text\" readonly=\"readonly\"\u003E\u003Cimg src=\"../images/b_calendar.png\" id='signup_data_education_edu_#{safe_id}_button3' onmouseover=\"javascript:document.body.style.cursor='hand';\" onmouseout=\"javascript:document.body.style.cursor='default';\"\u003E\u003Cscript type=\"text/javascript\"\u003E\nvar cal = new Zapatec.Calendar.setup({\ninputField:\"signup_data_education_edu_#{safe_id}_from\",\nifFormat:\"%m-%d-%Y\",\nbutton:\"signup_data_education_edu_#{safe_id}_button3\",\nshowsTime:false\n});\n\u003C/script\u003E\nTo:\u003Cinput class=\"zip overlayable date\" id=\"signup_data_education_edu_#{safe_id}_to\" name=\"signup[data][education][][to]\"  type=\"text\" readonly=\"readonly\"\u003E\u003Cimg src=\"../images/b_calendar.png\" id='signup_data_education_edu_#{safe_id}_button4' onmouseover=\"javascript:document.body.style.cursor='hand';\" onmouseout=\"javascript:document.body.style.cursor='default';\"\u003E\n\u003Cscript type=\"text/javascript\"\u003E\nvar cal = new Zapatec.Calendar.setup({\ninputField:\"signup_data_education_edu_#{safe_id}_to\",\nifFormat:\"%m-%d-%Y\",\nbutton:\"signup_data_education_edu_#{safe_id}_button4\",\nshowsTime:false\n});\n\u003C/script\u003E\n\u003Cselect class=\"zip overlayable\" id=\"signup_data_education_edu_#{safe_id}_graduate\" name=\"signup[data][education][][graduate]\" title=\"Did you graduate?\" style=\"width:auto\" \u003E\u003Coption value=\"\" \u003EDid you graduate?\u003C/option\u003E\u003Coption value=\"yes\" \u003EYes\u003C/option\u003E\u003Coption value=\"no\" selected=\"selected\" \u003ENo\u003C/option\u003E\u003C/select\u003E Degree:\u003Cinput class=\"zip overlayable textbox3\" id=\"signup_data_education_edu_#{safe_id}_degree\" name=\"signup[data][education][][degree]\"  type=\"text\"\u003E\u003C/p\u003E      \u003Cdiv class=\"loc_remove\"\u003E\n    \u003Cspan class=\"addremove\"\u003E\u003Ca href=\"#\" class=\"remove\"\u003ERemove\u003C/a\u003E\u003C/span\u003E\n      \u003C/div\u003E\n    \u003C/div\u003E\n  \u003C/div\u003E\n\u003C/div\u003E\n\u003Cscript type=\"text/javascript\"\u003E\n//\u003C![CDATA[\n$$('.street .overlayable').each(function(input) { ContactInfo.Overlay.wrap(input); });\n//]]\u003E\n\u003C/script\u003E\n\n")
			//]]>
			</script>
			</td></tr>
			<?php 
				break;
			case 'server' :
			
						$sql="delete from ".EM_APPLICATION_EDUCATION." where contact_id='$this->cont_id'";
					    $this->db->query($sql,__FILE__,__LINE__);
						$cnt=0;
						extract($_POST[signup]);
						$this->education = $data[education];
						for(;$cnt<count($this->education);) {
							if($this->education[$cnt+1][university]!=''){
								$insert_sql_array = array();
								$insert_sql_array[contact_id] 		= $this->cont_id;
								$insert_sql_array[education_id] 	= $this->education[$cnt+1][university];
								$insert_sql_array[title] 			= $this->education[$cnt][title];
								$insert_sql_array[address] 			= $this->education[$cnt+2][address];
								$insert_sql_array[education_from] 	= $this->education[$cnt+3][from];
								$insert_sql_array[education_to] 	= $this->education[$cnt+4][to];
								$insert_sql_array[graduate] 		= $this->education[$cnt+5][graduate];
								$insert_sql_array[graduate_degree] 	= $this->education[$cnt+6][degree];
								$this->db->insert(EM_APPLICATION_EDUCATION,$insert_sql_array);
							}
							$cnt+=7;
						}	
						break;
		}
	}

	function EditApplicationReference($runat,$contact_id)
	{
		$this->cont_id=$contact_id;
		switch($runat){
			case 'local':
			
			?>
			<tr class="addresses">
			  <th><h2>&nbsp;</h2></th>
			  <td><div class="contact_forms addresses" id="reference_list_signup"><div
			 id="blank_slate_signup_reference" class="blank_slate" style="display: none;">Add a 
			reference</div><div  class="contact_methods">
			
			<div class="contact_method new_contact_method street" 
			id="ref_xf6e911b9006d854b636685">
			  <div class="fields">
			  <?php 
			  $sql_references="select * from ".EM_APPLICATION_REFERENCES." where contact_id='".$this->cont_id."'";
			$result_references = $this->db->query($sql_references,__FILE__,__LINE__);
			while($row_references = $this->db->fetch_array($result_references))
			{
			?>
				<div style="position: relative;">
				  <p class="address">
				  Full Name: <input class="autofocus overlayable textbox9" name="signup[data][reference][][name]" id="signup_data_reference_ref_xf6e911b9006d854b636685_name"  type="text" value="<?php echo $row_references['reference_name'];?>" />
				  Relationship: <input class="overlayable textbox9" name="signup[data][reference][][relationship]" id="signup_data_reference_ref_xf6e911b9006d854b636685_relationship"  type="text"  value="<?php echo $row_references['reference_relation'];?>"  />
				  </p>
				  <p>Company: <input class="overlayable textbox9" name="signup[data][reference][][company]" id="signup_data_reference_ref_xf6e911b9006d854b636685_company"   type="text" value="<?php echo $row_references['reference_company'];?>"  />
				  Phone: <input class="overlayable textbox9" name="signup[data][reference][][phone]" id="signup_data_reference_ref_xf6e911b9006d854b636685_phone"  type="text" value="<?php echo $row_references['reference_phone'];?>"  />
				  </p>
				  <p>Address: <input class="overlayable textbox10" name="signup[data][reference][][address]" id="signup_data_reference_ref_xf6e911b9006d854b636685_address"  type="text" value="<?php echo $row_references['reference_address'];?>"  />
			   
			</p></div>
			<?php }?>
			  </div>
			</div>
			<script type="text/javascript">
			//<![CDATA[
			$$('.street .overlayable').each(function(input) { ContactInfo.Overlay.wrap(input); });
			//]]>
			</script>
			
			<div id="add_signup_reference" class="add_contact_method" style="display: 
			none;">
			  <div class="add"><a href="#">Add another</a></div>
			</div>
			</div></div><script type="text/javascript">
			//<![CDATA[
			new ContactInfo.ContactForm("reference_list_signup", "\n\n\u003Cdiv class=\"contact_method new_contact_method street\" id=\"ref_#{safe_id}\"\u003E\n  \u003Cdiv class=\"fields\"\u003E\n    \u003Cdiv style=\"position: relative;\"\u003E\n  \u003Cp class=\"address\"\u003EFull Name:\u003Cinput class=\"autofocus overlayable textbox9\" name=\"signup[data][reference][][name]\" id=\"signup_data_reference_ref_#{safe_id}_name\"  type=\"text\" /\u003ERelationship:\u003Cinput class=\"overlayable textbox9\" name=\"signup[data][reference][][relationship]\" id=\"signup_data_reference_ref_#{safe_id}_relationship\"   type=\"text\" /\u003E\u003C/p\u003E\u003Cp\u003ECompany:\u003Cinput class=\"overlayable textbox9\" name=\"signup[data][reference][][company]\" id=\"signup_data_reference_ref_#{safe_id}_company\"  type=\"text\" /\u003EPhone:\u003Cinput class=\"overlayable textbox9\" name=\"signup[data][reference][][phone]\" id=\"signup_data_reference_ref_#{safe_id}_phone\"  type=\"text\" /\u003E\u003C/p\u003E\u003Cp\u003EAddress:\u003Cinput class=\"overlayable textbox10\" name=\"signup[data][reference][][address]\" id=\"signup_data_reference_ref_#{safe_id}_address\"  type=\"text\" /\u003E\u003C/p\u003E \u003Cdiv class=\"loc_remove\"\u003E\n    \u003Cspan class=\"addremove\"\u003E\u003Ca href=\"#\" class=\"remove\"\u003ERemove\u003C/a\u003E\u003C/span\u003E\n      \u003C/div\u003E\n    \u003C/div\u003E\n  \u003C/div\u003E\n\u003C/div\u003E\n\u003Cscript type=\"text/javascript\"\u003E\n//\u003C![CDATA[\n$$('.street .overlayable').each(function(input) { ContactInfo.Overlay.wrap(input); });\n//]]\u003E\n\u003C/script\u003E\n\n")
			//]]>
			</script>
			</td></tr>
			<?php 
				break;
			case 'server' :
						$sql="delete from ".EM_APPLICATION_REFERENCES." where contact_id='$this->cont_id'";
					    $this->db->query($sql,__FILE__,__LINE__);
						$cnt=0;
						extract($_POST[signup]);
						$this->reference = $data[reference];
						for(;$cnt<count($this->reference);) {
							if($this->reference[$cnt][name]!=''){
								$insert_sql_array = array();
								$insert_sql_array[contact_id] 			= $this->cont_id;
								$insert_sql_array[reference_name] 		= $this->reference[$cnt][name];
								$insert_sql_array[reference_relation] 	= $this->reference[$cnt+1][relationship];
								$insert_sql_array[reference_company] 	= $this->reference[$cnt+2][company];
								$insert_sql_array[reference_phone] 		= $this->reference[$cnt+3][phone];
								$insert_sql_array[reference_address] 	= $this->reference[$cnt+4][address];
								$this->db->insert(EM_APPLICATION_REFERENCES,$insert_sql_array);
							}
							$cnt+=5;
						}	
						break;
		}
	}

	function EditApplicationEmployment($runat,$contact_id)
	{
		$this->cont_id=$contact_id;
		switch($runat){
			case 'local':
			?>
			<tr class="addresses">
			  <th><h2>&nbsp;</h2></th>
			  <td><div class="contact_forms addresses" id="employment_list_signup"><div	 id="blank_slate_signup_employment" class="blank_slate" style="display: none;">Add an 
			employment</div><div  class="contact_methods">
			
			<div class="contact_method new_contact_method street" 
			id="emp_xf6e911b9006d854b636385">
			  <div class="fields">
			  <?php 
			  $sql_prev_employment="select * from ".EM_APPLICATION_PREVIOUS_EMPLOYMENT." where contact_id='".$this->cont_id."'";
			$result_prev_employment = $this->db->query($sql_prev_employment,__FILE__,__LINE__);
			while($row_prev_employment = $this->db->fetch_array($result_prev_employment))
			{
			?>
				<div style="position: relative;">
				  <p class="address">
				  Company: <input class="autofocus overlayable textbox9" name="signup[data][employment][][company]" id="signup_data_employment_emp_xf6e911b9006d854b636385_company"  type="text" value="<?php echo $row_prev_employment['previous_company'] ?>" />
				  Phone: <input class="overlayable textbox9" name="signup[data][employment][][phone]" id="signup_data_employment_emp_xf6e911b9006d854b636385_phone" type="text" value="<?php echo $row_prev_employment['previous_company_phone'] ?>"  />
				  </p>
				  <p>Address: <input class="overlayable textbox9" name="signup[data][employment][][address]" id="signup_data_employment_emp_xf6e911b9006d854b636385_address"  type="text" value="<?php echo $row_prev_employment['previous_company_address'] ?>"  />
				  Supervisor: <input class="overlayable textbox9" name="signup[data][employment][][supervisor]" id="signup_data_employment_emp_xf6e911b9006d854b636385_supervisor"   type="text" value="<?php echo $row_prev_employment['previous_company_supervisor'] ?>"  />
				  </p>
				  <p>Job: <input class="overlayable textbox11" name="signup[data][employment][][job]" id="signup_data_employment_emp_xf6e911b9006d854b636385_job"   type="text" value="<?php echo $row_prev_employment['previous_job_title'] ?>"  />
				  Start Salary: <input class="overlayable textbox12" name="signup[data][employment][][start_salary]" id="signup_data_employment_emp_xf6e911b9006d854b636385_start_salary"  type="text" value="<?php echo $row_prev_employment['previous_starting_salary'] ?>"  />
				  End Salary: <input class="overlayable textbox12" name="signup[data][employment][][end_salary]" id="signup_data_employment_emp_xf6e911b9006d854b636385_end_salary"  type="text" value="<?php echo $row_prev_employment['previous_ending_salary'] ?>"  />
				  </p>
				  <p>
				  Responsibility: <input class="overlayable textbox10" name="signup[data][employment][][responsibility]" id="signup_data_employment_emp_xf6e911b9006d854b636385_responsibility" type="text" value="<?php echo $row_prev_employment['responsibility'] ?>"  />
				  </p>
				  <p>From: <input class="overlayable date" name="signup[data][employment][][from]" id="signup_data_employment_emp_xf6e911b9006d854b636385_from"  type="text" readonly="readonly" value="<?php echo $row_prev_employment['from'] ?>"  /><img src="../images/b_calendar.png" id='signup_data_employment_emp_xf6e911b9006d854b636385_button9' onmouseover="javascript:document.body.style.cursor='hand';" onmouseout="javascript:document.body.style.cursor='default';">
				  <script type="text/javascript">
						var cal = new Zapatec.Calendar.setup({
						
						inputField:"signup_data_employment_emp_xf6e911b9006d854b636385_from",
						ifFormat:"%m-%d-%Y",
						button:"signup_data_employment_emp_xf6e911b9006d854b636385_button9",
						showsTime:false
				
						});
						
					</script>
				  To: <input class="overlayable date" name="signup[data][employment][][to]" id="signup_data_employment_emp_xf6e911b9006d854b636385_to" type="text" readonly="readonly" value="<?php echo $row_prev_employment['to'] ?>"  /><img src="../images/b_calendar.png" id='signup_data_employment_emp_xf6e911b9006d854b636385_button10' onmouseover="javascript:document.body.style.cursor='hand';" onmouseout="javascript:document.body.style.cursor='default';">
					  <script type="text/javascript">
						var cal = new Zapatec.Calendar.setup({
						
						inputField:"signup_data_employment_emp_xf6e911b9006d854b636385_to",
						ifFormat:"%m-%d-%Y",
						button:"signup_data_employment_emp_xf6e911b9006d854b636385_button10",
						showsTime:false
				
						});
						
					</script>
				  Reason for Leaving: <input class="overlayable textbox13" name="signup[data][employment][][reason_leaving]" id="signup_data_employment_emp_xf6e911b9006d854b636385_reason_leaving"  type="text" value="<?php echo $row_prev_employment['from'] ?>"  />
				  </p>
				  <p>
				  May we contact your previous supervisor for a reference? <select class="overlayable" id="signup_data_employment_emp_xf6e911b9006d854b636385_previous_supervisor" name="signup[data][employment][][previous_supervisor]" style="width:auto" >
				    <option value="">-Select-</option>
					<option value="yes"  <?php  if($row_prev_employment['may_contact_supervisor']=='yes'){ echo 'selected="selected"';}?> >Yes</option>
				    <option value="no"  <?php  if($row_prev_employment['may_contact_supervisor']=='no'){ echo 'selected="selected"';}?> >No</option>
			   	  </select>
				  </p>
				  </div>
			  </div>
			  <?php }?> 
			</div>
			<script type="text/javascript">
			//<![CDATA[
			$$('.street .overlayable').each(function(input) { ContactInfo.Overlay.wrap(input); });
			//]]>
			</script>
			
			<div id="add_signup_employment" class="add_contact_method" style="display: 
			none;">
			  <div class="add"><a href="#">Add another</a></div>
			</div>
			</div></div><script type="text/javascript">
			//<![CDATA[
			new ContactInfo.ContactForm("employment_list_signup", "\n\n\u003Cdiv class=\"contact_method new_contact_method street\" id=\"emp_#{safe_id}\"\u003E\n  \u003Cdiv class=\"fields\"\u003E\n    \u003Cdiv style=\"position: relative;\"\u003E\n  \u003Cp class=\"address\"\u003ECompany:\u003Cinput class=\"autofocus overlayable textbox9\" name=\"signup[data][employment][][company]\" id=\"signup_data_employment_emp_#{safe_id}_company\"  type=\"text\" /\u003EPhone:\u003Cinput class=\"overlayable textbox9\" name=\"signup[data][employment][][phone]\" id=\"signup_data_employment_emp_#{safe_id}_phone\"  type=\"text\" /\u003E\u003C/p\u003E\u003Cp\u003EAddress:\u003Cinput class=\"overlayable textbox9\" name=\"signup[data][employment][][address]\" id=\"signup_data_employment_emp_#{safe_id}_address\"  type=\"text\" /\u003ESupervisor:\u003Cinput class=\"overlayable textbox9\" name=\"signup[data][employment][][supervisor]\" id=\"signup_data_employment_emp_#{safe_id}_supervisor\"   type=\"text\" /\u003E\u003C/p\u003E\u003Cp\u003EJob:\u003Cinput class=\"overlayable textbox11\" name=\"signup[data][employment][][job]\" id=\"signup_data_employment_emp_#{safe_id}_job\"   type=\"text\" /\u003EStart Salary:\u003Cinput class=\"overlayable textbox12\" name=\"signup[data][employment][][start_salary]\" id=\"signup_data_employment_emp_#{safe_id}_start_salary\"   type=\"text\" /\u003EEnd Salary:\u003Cinput class=\"overlayable textbox12\" name=\"signup[data][employment][][end_salary]\" id=\"signup_data_employment_emp_#{safe_id}_end_salary\"  type=\"text\" /\u003E\u003C/p\u003E\u003Cp\u003EResponsibility:\u003Cinput class=\"overlayable textbox10\" name=\"signup[data][employment][][responsibility]\" id=\"signup_data_employment_emp_#{safe_id}_responsibility\"   type=\"text\" /\u003E\u003C/p\u003E\u003Cp\u003EFrom:\u003Cinput class=\"overlayable date\" name=\"signup[data][employment][][from]\" id=\"signup_data_employment_emp_#{safe_id}_from\"  type=\"text\" readonly=\"readonly\" /\u003E\u003Cimg src=\"../images/b_calendar.png\" id='signup_data_employment_emp_#{safe_id}_button9' onmouseover=\"javascript:document.body.style.cursor='hand';\" onmouseout=\"javascript:document.body.style.cursor='default';\"\u003E\n\u003Cscript type=\"text/javascript\"\u003E\nvar cal = new Zapatec.Calendar.setup({\ninputField:\"signup_data_employment_emp_#{safe_id}_from\",\nifFormat:\"%m-%d-%Y\",\nbutton:\"signup_data_employment_emp_#{safe_id}_button9\",\nshowsTime:false\n});\n\u003C/script\u003E\nTo:\u003Cinput class=\"overlayable date\" name=\"signup[data][employment][][to]\" id=\"signup_data_employment_emp_#{safe_id}_to\"  type=\"text\" readonly=\"readonly\" /\u003E\u003Cimg src=\"../images/b_calendar.png\" id='signup_data_employment_emp_#{safe_id}_button10' onmouseover=\"javascript:document.body.style.cursor='hand';\" onmouseout=\"javascript:document.body.style.cursor='default';\"\u003E\n\u003Cscript type=\"text/javascript\"\u003E\nvar cal = new Zapatec.Calendar.setup({\ninputField:\"signup_data_employment_emp_#{safe_id}_to\",\nifFormat:\"%m-%d-%Y\",\nbutton:\"signup_data_employment_emp_#{safe_id}_button10\",\nshowsTime:false\n});\n\u003C/script\u003E\nReason for Leaving:\u003Cinput class=\"overlayable textbox13\" name=\"signup[data][employment][][reason_leaving]\" id=\"signup_data_employment_emp_#{safe_id}_reason_leaving\"  type=\"text\" /\u003E\u003C/p\u003E\u003Cp\u003E\u003Cselect class=\"overlayable\" id=\"signup_data_employment_emp_#{safe_id}_previous_supervisor\" name=\"signup[data][employment][][previous_supervisor]\" title=\"May we contact your previous supervisor for a reference?\" style=\"width:auto\" \u003E\u003Coption value=\"\"\u003ESelect\u003C/option\u003E\u003Coption value=\"yes\" \u003EYes\u003C/option\u003E\u003Coption value=\"no\" \u003ENo\u003C/option\u003E\u003C/select\u003E\u003C/p\u003E \u003Cdiv class=\"loc_remove\"\u003E\n    \u003Cspan class=\"addremove\"\u003E\u003Ca href=\"#\" class=\"remove\"\u003ERemove\u003C/a\u003E\u003C/span\u003E\n      \u003C/div\u003E\n    \u003C/div\u003E\n  \u003C/div\u003E\n\u003C/div\u003E\n\u003Cscript type=\"text/javascript\"\u003E\n//\u003C![CDATA[\n$$('.street .overlayable').each(function(input) { ContactInfo.Overlay.wrap(input); });\n//]]\u003E\n\u003C/script\u003E\n\n")
			//]]>
			</script>
			</td></tr>
			<?php 
				break;
			case 'server' :
						$sql="delete from ".EM_APPLICATION_PREVIOUS_EMPLOYMENT." where contact_id='$this->cont_id'";
					    $this->db->query($sql,__FILE__,__LINE__);
						$cnt=0;
						extract($_POST[signup]);
						$this->employment = $data[employment];
						for(;$cnt<count($this->employment);) {
							if($this->employment[$cnt][company]!=''){
								$insert_sql_array = array();
								$insert_sql_array[contact_id] 					= $this->cont_id;
								$insert_sql_array[previous_company] 			= $this->employment[$cnt][company];
								$insert_sql_array[previous_company_phone] 		= $this->employment[$cnt+1][phone];
								$insert_sql_array[previous_company_address] 	= $this->employment[$cnt+2][address];
								$insert_sql_array[previous_company_supervisor] 	= $this->employment[$cnt+3][supervisor];
								$insert_sql_array[previous_job_title] 			= $this->employment[$cnt+4][job];
								$insert_sql_array[previous_starting_salary] 	= $this->employment[$cnt+5][start_salary];
								$insert_sql_array[previous_ending_salary] 		= $this->employment[$cnt+6][end_salary];
								$insert_sql_array[responsibility] 				= $this->employment[$cnt+7][responsibility];
								$insert_sql_array[from] 						= $this->employment[$cnt+8][from];
								$insert_sql_array[to] 							= $this->employment[$cnt+9][to];
								$insert_sql_array[reason] 						= $this->employment[$cnt+10][reason_leaving];
								$insert_sql_array[may_contact_supervisor] 		= $this->employment[$cnt+11][previous_supervisor];
								$this->db->insert(EM_APPLICATION_PREVIOUS_EMPLOYMENT,$insert_sql_array);
							}
							$cnt+=12;
						}	
						break;
		}
	}



}
?>