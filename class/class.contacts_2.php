<?php
require_once("class/class.tags.php");
require_once("class/class.element_permission.php");
require_once("class/class.vcard.php");
require_once("class/csv.lib.php");
/***********************************************************************************

			Class Discription : Contacts extends Tags
			
			Class Memeber Functions :SetContactID($contact_id)
									 SetGroups($groups)
									 GetContactID()
									 SetUserID($user_id)
									 SetUserName($user_name)
									 SetPagelength($length)
									 GetOwnerID($contact_id)
									 GetPagelength()
									 AddContactDeatils($runat)
									 EditContactDeatils($runat)
									 AddContactPhone($runat)
									 AddContactEmail($runat)
									 AddContactIm($runat)
									 AddContactWebsite($runat)
									 AddContactTwitter($runat)
									 AddContactAddress($runat)
									 AddContactSubmitButton($runat)
									 EditContactPhone($runat)
									 EditContactEmail($runat)
									 EditContactIm($runat)
									 EditContactWebsite($runat)
									 EditContactTwitter($runat)
									 EditContactAddress($runat)
									 EditContactSubmitButton($runat)
									 getContactType($contact_id, $edit_head='')
			
			
			Describe Function of Each Memeber function :
			
								     1. function SetContactID($contact_id) // setter function for the contact id.
									 
									 2. function SetGroups($groups) // setter function for groups
									 
									 3. function GetContactID() // returns the value of contact_id
									 
									 4. function SetUserID($user_id) // setter function for user_id
									 
									 5. function SetUserName($user_name) // setter function for user_nama
									 
									 6. function SetPagelength($length) //sets the no. of records to be displayed on per page when searching contacts.
									 
									 7. function GetOwnerID($contact_id) // the the id of the owner for this contact.
									 
									 8. function GetPagelength()  // gets  the no. of records to be displayed on per page when searching contacts.
									 
									 9. function AddContactDeatils($runat)  // $runat=local/server 
									 		
											Add contact deatils number, email, im, website, twitter id, street address in database table
											contacts, contacts_address, contacts_email, contacts_im, contacts_phone, contacts_twitter,
											and contacts_website.
											Also provide the secirity setting for the visitores to view contact.
									 
									 10. function EditContactDeatils($runat)  // $runat=local/server 
									 		
											Edit the contact informations number, email, im, website, twitter id, street address saved in database table
											contacts, contacts_address, contacts_email, contacts_im, contacts_phone, contacts_twitter,
											and contacts_website.
											the security settings can also be edited
									 
									 11. function AddContactPhone($runat)  // $runat=local/server 
									 		
											Add contact phone numbers and there type (Work/Home) to database table contacts_phone.											
									 
									 12. function AddContactEmail($runat)  // $runat=local/server 
									 
									 		Add email contacts and there type (Work/Home) to database table contacts_email.
									 
									 13. function AddContactIm($runat)  // $runat=local/server
									 
									 		 Add IM IDs and there type (Work/Home) to database table contacts_im.
									 
									 14. function AddContactWebsite($runat)  // $runat=local/server 
									 
									 		Add websites and there type (Work/Home) to database table contacts_website.
									 
									 15. function AddContactTwitter($runat)  // $runat=local/server
									 
									 		Add Twitter IDs and there type (Work/Home) to database table contacts_twitter. 
									 
									 16. function AddContactAddress($runat)  // $runat=local/server 
									 
									 		Add contact address (contact_id, city, state, zip, country, type=Work/Home) to database table
											contacts_address
										
									 17. function AddContactSubmitButton($runat)  // $runat=local/server 
									 
									 		Display Add contact button
									 
									 18. function EditContactPhone($runat)  // $runat=local/server 
									 
									 		Edit phone numbers saved in database table contacts_phone
									 
									 19. function EditContactEmail($runat)  // $runat=local/server 
									 
									 		Edit email contacts saved in database table contacts_email
									 
									 20. function EditContactIm($runat)  // $runat=local/server 
									 
									 		Edit IM IDs saved in database table contacts_im
									 
									 21. function EditContactWebsite($runat)  // $runat=local/server
									 
									 		Edit website address saved in database table contacts_website 
									 
									 22. function EditContactTwitter($runat)  // $runat=local/server 
									 		
											Edit twitter IDs saved in database table contacts_twitter
									 
									 23. function EditContactAddress($runat)  // $runat=local/server 
									 
									 		Edit contact address deatils (contact_id, city, state, zip, country, type=Work/Home) to database
											table contacts_address
									 
									 24. function EditContactSubmitButton($runat)  // $runat=local/server 
									 
									 		Displays the Edit contact button
									 
									 25. function getContactType($contact_id, $edit_head='')
									 
									 		get the type of the contact People/Company by passing contact_id
			



************************************************************************************/

/***********************************************************************************

			Class Discription : People_Contacts extends Contacts
							This module Add, Edit and delete the details of the contact information saved 
							in database table contacts.
			
			Class Memeber Functions : AddContactPeople($runat) 
									  GetValidCompany($company_name)
									  EditContactPeople($runat,$contact_id)
			
			
			Describe Function of Each Memeber function :
			
									  1. function AddContactPeople($runat)  // $runat=local/server 
									  
									  		Add first_name, last_name, title, company, comments and type='People'
											to database table contacs.
									  
									  2. function GetValidCompany($company_name) 
									  		gets the company_id for the company_name entered in database table contacts. If the company does 
											not exist it adds the company to the database and generate the company_id and provide it.
									  
									  3. function EditContactPeople($runat,$contact_id)
									  
									  		Edit first_name, last_name, title, company, comments and type='People'
											to database table contacs of selected contact_id



************************************************************************************/

/***********************************************************************************

			Class Discription : Company_Contacts extends People_Contacts
			
			Class Memeber Functions : AddContactCompany($runat) 
									  EditContactCompany($runat,$contact_id)
			
			
			Describe Function of Each Memeber function

									  1. function AddContactCompany($runat)   // $runat=local/server 
									  
									  		Add company details user_id, company_name, type, picture, directory to database table contacts.
											Also uploads the company picture.
									  
									  2. function EditContactCompany($runat,$contact_id)
									  
									  		Edit company details user_id, company_name, type, picture, directory to database table contacts.
											Can also change the image and uploads new company picture.
									  
									  		


************************************************************************************/

/***********************************************************************************

			Class Discription : Company_Global extends Company_Contacts
			
			Class Memeber Functions : FillContact($contact_id)
									  AddContact($runat,$type) 
									  EditContact($runat,$contact_id) 
									  ContactSearchBox()
									  ContactSearchContainer()
									  TagSearchContainer($tag_id)
									  GetContactHead($contact_id,$target)
									  GetContact($pattern='',$page='',$list='',$type='')
									  GetContactProfile($contact_id)
									  Get_Company($company_id)
									  GetCompany_name($company_id,$textonly='')
									  GetCompanyAddress($contact_id)
									  GetPeopleInCompnay($contact_id)
									  DisplayContact($module,$module_id)
									  RemoveContact($contact_id)
									  Addcontact_On_Fly($contact_info,$tag,$return=false)
									  ImportContacts($runat)//import jobs
									  ExportCsv($runat)
									  ImportVcard($runat)
									  ExportVcard($contact_id)
			
			
			Describe Function of Each Memeber function :

									  FillContact($contact_id)
									  
									  
									  AddContact($runat,$type) 
									  
									  		this function add the contacts of type "people" and "company". If type "people" is selected the
											function AddContactPeople($runat) is called from the class "People_Contacts extends Contacts"
											and if type "company" is selected AddContactCompany($runat) is called from the class "Company_Contacts 
											extends People_Contacts".
									  
									  
									  EditContact($runat,$contact_id) 
									  
									  		this function edits the contacts of type "people" and "company" selected by user. If type "people" is 
											selected the function EditContactPeople($runat,$contact_id) is called from the class "People_Contacts 
											extends Contacts" and if type "company" is selected EditContactCompany($runat,$contact_id) is called 
											from the class "Company_Contacts extends People_Contacts".
									  
									  
									  ContactSearchBox()  // displays the contact search box
									  
									  		this function displays the contact search box where the when the user enter the value in search box
											field the contact.GetContact(this.value, {target: 'search_result'}) is called to display the
											result in the search_result box.
									  
									  
									  ContactSearchContainer() // displays the contact search container with the contacts 
									  
									  
									  TagSearchContainer($tag_id)  //  $tag_id- tag_id of the tag where the contacts is to displayed 
									   		
											displays the contacts in the tag this functon calls the GetContact() function to display the 
											contacts in tag.
									  
									  
									  GetContactHead($contact_id,$target) // $contact_id= contact_id of the record selected  
									  										 $target= target where the created contact head is to displayed
																			 
											This function creates the head view of the contact selected from the list of contacts.
											It displays the first-name, middle_name, last_name and the image of the contact selected.
									  
									  
									  GetContact($pattern='',$page='',$list='',$type='') 
									  
									  		return the list of contacts matching the pattern, list may be detailed list, compact list, & also can be 
											filtered using type parameter to provide a specific type of <ul><li> list.
									  
									  
									  GetContactProfile($contact_id)
									  
									  		Displays Profile detais of the contact selected in the profile box.
									  
									  
									  Get_Company($company_id)
									  
									  		Displays the list of companies.
									  
									  
									  GetCompany_name($company_id,$textonly='')
									  	
											Displays the hyperlink of companies. The detail of the company is displayed click on selected company.
									  
									  
									  GetCompanyAddress($contact_id)
									  
									  		Displays the complete address (street_address, city, state, zip, country) of the company from the
											table contacts_address  
									  
									  
									  GetPeopleInCompnay($contact_id)
									  
									  		Display the list of all the people in selected company. 
									  
									  
									  DisplayContact($module,$module_id)  
									  
									  		Display the contact information of the selected contact_id. The contact information is
											fetched from tables contacts, contacts_address,	contacts_email, contacts_im, contacts_phone, 
											contacts_twitter and contacts_website								  		
									  
									  
									  RemoveContact($contact_id) // $contact_id= contact_id of the selected contact to be deleted
									  
									  		Delete the selected contact from the database . All record of the selected contact get deleted 
											from tables contacts, contacts_address,	contacts_email, contacts_im, contacts_phone, 
											contacts_twitter and contacts_website
									  
									  
									  Addcontact_On_Fly($contact_info,$tag,$return=false)
									  
									  
									  ImportContacts($runat)    // $runat=local/server 
									  
									  		import contacts from CSV file to database table contacts, contacts_address, contacts_email, 
											contacts_im, contacts_phone and contacts_website. 
																				  
									  
									  ExportCsv($runat)    // $runat=local/server 
									  
									  		Export contacts selected by user from database to CSV file. The contacts are fetched from
											table contacts, contacts_address, contacts_email, contacts_im, contacts_phone and contacts_website. 
									  
									  
									  ImportVcard($runat)    // $runat=local/server 
									  
									  		import contacts from Vcard file to database table contacts, contacts_address, contacts_email, 
											contacts_im, contacts_phone and contacts_website.
									  
									  
									  ExportVcard($contact_id)    // $runat=local/server 
									  
									  		Export contacts from database to Vcard file. The contacts are fetched from
											table contacts, contacts_address, contacts_email, contacts_im, contacts_phone and contacts_website. 


************************************************************************************/



class Contacts extends Tags // Basic class for contact 
{
	var $module;
	var $module_id;
    var $contact_id;
	var $user_id;
	var $type;
	var $number				=array();
	var $number_type		=array();
	var $email				=array();
	var $email_type			=array();
	var $im					=array();
	var $im_network			=array();
	var $im_type			=array();
	var $website			=array();
	var $website_type		=array();
	var $twitter_id			=array();
	var $twitter_id_type	=array();
	var $street_address		=array();
	var $city				=array();
	var $state				=array();
	var $zip				=array();
	var $country			=array();
	var $address_type		=array();
	var $db;
	var $directory;
	var $picture;
	var $objFileUpload; //object of class FileUpload
	var $old_file_name;
	var $ValidationFunctionName;
	var $pagelength;
	var $security;
	var $groups;
	var $user_name;
	var $vcard;
	var $db2csv;
	var $auth;
	
	function __construct()
	{
	parent::__construct();
	$this->directory='Picture';
	$this->objFileUpload = new FileUpload();
	$this->Validity=new ClsJSFormValidation();
	$this->ValidationFunctionName="CheckValidity";
	$this->pagelength=15;
	$this->module="TBL_CONTACT";
	$this->security=new Element_Permission();
	$this->vcard=new vCard();
	$this->db2csv = new export2CSV(',','\n');
	$this->auth = new Authentication();
	}
	
	function SetContactID($contact_id)
	{
		$this->contact_id=$contact_id;	
	}
	
	function SetGroups($groups)
	{
		$this->groups="('".implode("','",$groups)."','*')";	
	}
	function GetContactID()
	{
		return $this->contact_id;
	}
	
	function SetUserID($user_id)
	{
		$this->user_id=$user_id;
	}
	
	function SetUserName($user_name)
	{
		$this->user_name=$user_name;
	}
	function SetPagelength($length)
	{	
		if(intval($length))
		$this->pagelength=$length;
	}
	
	function GetOwnerID($contact_id)
	{
		$sql="select user_id from ".TBL_CONTACT." where contact_id=$contact_id";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		$row=$this->db->fetch_array($record);
		return $row['user_id'];
	}
	
	
	function GetPagelength()
	{
		return 	$this->pagelength;
	}
	
	/////Methods///////
	
	//Add Contact Details
	function AddContactDeatils($runat,$type='')
	{
		if($runat=='server'){
					extract($_POST[person]);
					$this->number			=$contact_data[phone_numbers];
					$this->email			=$contact_data[email_addresses];
					$this->im				=$contact_data[instant_messengers];
					$this->website 			=$contact_data[web_addresses];
					$this->twitter_id		=$contact_data[twitter_accounts];
					$this->street_address	=$contact_data[addresses];
		}
		
		if($type == 'add_contacts'){
			$this->AddContactPhone($runat);
			$this->AddContactEmail($runat);
			$this->AddContactSubmitButton($runat);
		}
		
		else{
			$this->AddContactPhone($runat);
			$this->AddContactEmail($runat);
			$this->AddContactIm($runat);
			$this->AddContactWebsite($runat);
			$this->AddContactTwitter($runat);
			$this->AddContactAddress($runat);
			
			//////security setting /////////////////
			$this->security->SetModule_name($this->module);
			$this->security->SetModule_id($this->contact_id);
			$this->security->Add_Rule_Webform($runat);
			///////////////////////////////////////
			
			$this->AddContactSubmitButton($runat);
		}					
	}
	
	//Edit Contact Details
	function EditContactDeatils($runat)
	{
		if($runat=='server'){
					extract($_POST[person]);
					$this->number			=$contact_data[phone_numbers];
					$this->email			=$contact_data[email_addresses];
					$this->im				=$contact_data[instant_messengers];
					$this->website 			=$contact_data[web_addresses];
					$this->twitter_id		=$contact_data[twitter_accounts];
					$this->street_address	=$contact_data[addresses];
		}
		
		$this->EditContactPhone($runat);
		$this->EditContactEmail($runat); 
		$this->EditContactIm($runat);
		$this->EditContactWebsite($runat);
		$this->EditContactTwitter($runat);
		$this->EditContactAddress($runat);
		
		//////security setting /////////////////
		$this->security->SetModule_name($this->module);
		$this->security->SetModule_id($this->contact_id);
		$this->security->Edit_Permission($runat);
		////////////////////////////////////////
		
		$this->EditContactSubmitButton($runat);
						
	}

	//*************Add Contact Phone************************************//
	function AddContactPhone($runat,$tbl='',$cont_id='')
	{	
	
		switch($runat){
		
		case 'local' :	
		?>
			<tr>
			<th><h2>Phone</h2></th>
			<td>
			 <div class="contact_forms phone_numbers" id="phone_number_list_person"><div id="blank_slate_person_phone_number" class="blank_slate" style="">Add a phone number</div>
			 <div style="display: none;" class="contact_methods">
			
			   <div id="phone_number_x7f9e493d006d854b636885" class="contact_method new_contact_method edit_phone">
				<input class="autofocus" id="person_contact_data_phone_numbers__number" name="person[contact_data][phone_numbers][][number]" onkeyup="this.value = (this.value).replace(/\D/g,'');"
			type="text" value="<?php echo $_REQUEST['phone'];?>">
				
				<select id="person_contact_data_phone_numbers__location" name="person[contact_data][phone_numbers][][location]">
					<option selected="selected" value="Work">Work</option>
					<option value="Mobile">Mobile</option>
					<option value="Fax">Fax</option>
					<option value="Pager">Pager</option>
					<option value="Home">Home</option>
					<option value="Skype">Skype</option>
					<option value="Other">Other</option>
			    </select>
				<span class="addremove"><a href="#" class="remove">Remove</a></span>
			  </div>
			
			
			<div id="add_person_phone_number" class="add_contact_method" style="display:none;">
			  <div class="add"><a href="#">Add another</a></div>
			</div>
			</div></div><script type="text/javascript">
			//<![CDATA[
			new ContactInfo.ContactForm("phone_number_list_person", "\n\n  \u003Cdiv id=\"phone_number_#{safe_id}\" class=\"contact_method new_contact_method edit_phone\"\u003E\n    \u003Cinput class=\"autofocus\" id=\"person_contact_data_phone_numbers__number\" name=\"person[contact_data][phone_numbers][][number]\" onkeyup=\"this.value = (this.value).replace(/\\D/g,\'\');\" type=\"text\" value=\"\" /\u003E\n    \n    \u003Cselect id=\"person_contact_data_phone_numbers__location\" name=\"person[contact_data][phone_numbers][][location]\"\u003E\u003Coption value=\"Work\"\u003EWork\u003C/option\u003E\n\u003Coption value=\"Mobile\"\u003EMobile\u003C/option\u003E\n\u003Coption value=\"Fax\"\u003EFax\u003C/option\u003E\n\u003Coption value=\"Pager\"\u003EPager\u003C/option\u003E\n\u003Coption value=\"Home\"\u003EHome\u003C/option\u003E\n\u003Coption value=\"Skype\"\u003ESkype\u003C/option\u003E\n\u003Coption value=\"Other\"\u003EOther\u003C/option\u003E\u003C/select\u003E\n    \u003Cspan class=\"addremove\"\u003E\u003Ca href=\"#\" class=\"remove\"\u003ERemove\u003C/a\u003E\u003C/span\u003E\n  \u003C/div\u003E\n\n")
			//]]>
			</script></td>
			</tr>
			<tr>		
		<?php
		break;
		
		case 'server' : 
					//*********Insert Phone Numbers(contacts_phone)****************//
					if($tbl=='' or $cont_id==''){
						$table_name = CONTACT_PHONE;
						$contacts_id = $this->contact_id;
					} else {
						$table_name = $tbl;
						$contacts_id = $cont_id;
					}
					$count=0;
					extract($_POST[person]);
					$this->number=$contact_data[phone_numbers];
					for(;$count<count($this->number);) {
					if($this->number[$count][number]!=''){
					$symbols= explode(',',SYMBOLS);
					$insert_sql_array = array();
					$insert_sql_array[contact_id] = $contacts_id;
					$insert_sql_array[number] 	= str_replace($symbols,'',$this->number[$count][number]);
					$insert_sql_array[type] 	= $this->number[$count+1][location];
					$this->db->insert($table_name,$insert_sql_array);
					}
					$count+=2;
					}		
					break;
		

		default : //empty runat action.
		
		}
	}
					
					
	//*************Add Contact Email*******************************//
	function AddContactEmail($runat,$tbl='',$cont_id='')
	{	
		
		switch($runat){
		
		case 'local' :
		?>
		<th><h2>Email</h2></th>
			<td><div class="contact_forms email_addresses" id="email_address_list_person">
			<div id="blank_slate_person_email_address" class="blank_slate" style="">Add an email address</div>
			<div style="display: none;" class="contact_methods">
			
			  <div id="email_address_x04aaa499006d854b636885" class="contact_method 
			new_contact_method">
				<input class="autofocus" 
			id="person_contact_data_email_addresses__address" 
			name="person[contact_data][email_addresses][][address]" 
			type="text">
				
				<select id="person_contact_data_email_addresses__location" 
			name="person[contact_data][email_addresses][][location]"><option 
			selected="selected" value="Work">Work</option>
			<option value="Home">Home</option>
			<option value="Other">Other</option></select>
				<span class="addremove"><a href="#" class="remove">Remove</a></span>
			  </div>
			
			<div id="add_person_email_address" class="add_contact_method" 
			style="display: none;">
			  <div class="add"><a href="#">Add another</a></div>
			</div>
			</div></div><script type="text/javascript">
			//<![CDATA[
			new ContactInfo.ContactForm("email_address_list_person", "\n\n  \u003Cdiv id=\"email_address_#{safe_id}\" class=\"contact_method new_contact_method\"\u003E\n    \u003Cinput class=\"autofocus\" id=\"person_contact_data_email_addresses__address\" name=\"person[contact_data][email_addresses][][address]\"  type=\"text\" /\u003E\n    \n    \u003Cselect id=\"person_contact_data_email_addresses__location\" name=\"person[contact_data][email_addresses][][location]\"\u003E\u003Coption value=\"Work\"\u003EWork\u003C/option\u003E\n\u003Coption value=\"Home\"\u003EHome\u003C/option\u003E\n\u003Coption value=\"Other\"\u003EOther\u003C/option\u003E\u003C/select\u003E\n    \u003Cspan class=\"addremove\"\u003E\u003Ca href=\"#\" class=\"remove\"\u003ERemove\u003C/a\u003E\u003C/span\u003E\n  \u003C/div\u003E\n\n")
			//]]>
			</script></td>
			</tr> <?php
		break;
		
		case 'server' : 
					//*********Insert Emails (contacts_email)****************//
					if($tbl=='' or $cont_id==''){
						$table_name = CONTACT_EMAIL;
						$contacts_id = $this->contact_id;
					} else {
						$table_name = $tbl;
						$contacts_id = $cont_id;
					}
					$count=0;
					extract($_POST[person]);
					$this->email			=$contact_data[email_addresses];
					for(;$count<count($this->email);){
					if($this->email[$count][address]!='') {
					$insert_sql_array = array();
					$insert_sql_array[contact_id] = $contacts_id;
					$insert_sql_array[email]	= $this->email[$count][address];
					$insert_sql_array[type]		= $this->email[$count+1][location];

					$this->db->insert($table_name,$insert_sql_array);
					}
					$count+=2;
					}	
		break;
		
		default : // empty runat Action
		}	
	}
					
					
	//*************Add Contact IM*******************************//
	function AddContactIm($runat)
	{	

		switch($runat){
		
		case 'local' :
		?>
		<tr>
			  <th><h2>IM</h2></th>
			  <td><div class="contact_forms instant_messengers" 
			id="instant_messenger_list_person"><div 
			id="blank_slate_person_instant_messenger" class="blank_slate" style="">Add
			 an instant message account</div><div style="display: none;" 
			class="contact_methods">
			
			  <div id="instant_messenger_xc24ff66a006d854b636885" 
			class="contact_method new_contact_method">
				<div class="instant_messenger">
				  <input class="autofocus" 
			id="person_contact_data_instant_messengers__address" 
			name="person[contact_data][instant_messengers][][address]" size="30" 
			type="text">&nbsp;<select class="protocols" 
			id="person_contact_data_instant_messengers__protocol" 
			name="person[contact_data][instant_messengers][][protocol]"><option 
			selected="selected" value="AIM">AIM</option>
			<option value="MSN">MSN</option>
			<option value="ICQ">ICQ</option>
			<option value="Jabber">Jabber</option>
			<option value="Yahoo">Yahoo</option>
			<option value="Skype">Skype</option>
			<option value="QQ">QQ</option>
			<option value="Sametime">Sametime</option>
			<option value="Gadu-Gadu">Gadu-Gadu</option>
			<option value="Google Talk">Google Talk</option>
			<option value="Other">Other</option></select>
				  
				  <select id="person_contact_data_instant_messengers__location" 
			name="person[contact_data][instant_messengers][][location]"><option 
			selected="selected" value="Work">Work</option>
			<option value="Personal">Personal</option>
			<option value="Other">Other</option></select>
				  <span class="addremove"><a href="#" class="remove">Remove</a></span>
				</div>
			  </div>
			
			<div id="add_person_instant_messenger" class="add_contact_method" 
			style="display: none;">
			  <div class="add"><a href="#">Add another</a></div>
			</div>
			</div></div><script type="text/javascript">
			//<![CDATA[
			new ContactInfo.ContactForm("instant_messenger_list_person", "\n\n  \u003Cdiv id=\"instant_messenger_#{safe_id}\" class=\"contact_method new_contact_method\"\u003E\n    \u003Cdiv class=\"instant_messenger\"\u003E\n      \u003Cinput class=\"autofocus\" id=\"person_contact_data_instant_messengers__address\" name=\"person[contact_data][instant_messengers][][address]\" size=\"30\" type=\"text\" value=\"\" /\u003E\u0026nbsp;\u003Cselect class=\"protocols\" id=\"person_contact_data_instant_messengers__protocol\" name=\"person[contact_data][instant_messengers][][protocol]\"\u003E\u003Coption value=\"AIM\"\u003EAIM\u003C/option\u003E\n\u003Coption value=\"MSN\"\u003EMSN\u003C/option\u003E\n\u003Coption value=\"ICQ\"\u003EICQ\u003C/option\u003E\n\u003Coption value=\"Jabber\"\u003EJabber\u003C/option\u003E\n\u003Coption value=\"Yahoo\"\u003EYahoo\u003C/option\u003E\n\u003Coption value=\"Skype\"\u003ESkype\u003C/option\u003E\n\u003Coption value=\"QQ\"\u003EQQ\u003C/option\u003E\n\u003Coption value=\"Sametime\"\u003ESametime\u003C/option\u003E\n\u003Coption value=\"Gadu-Gadu\"\u003EGadu-Gadu\u003C/option\u003E\n\u003Coption value=\"Google Talk\"\u003EGoogle Talk\u003C/option\u003E\n\u003Coption value=\"Other\"\u003EOther\u003C/option\u003E\u003C/select\u003E\n      \n      \u003Cselect id=\"person_contact_data_instant_messengers__location\" name=\"person[contact_data][instant_messengers][][location]\"\u003E\u003Coption value=\"Work\"\u003EWork\u003C/option\u003E\n\u003Coption value=\"Personal\"\u003EPersonal\u003C/option\u003E\n\u003Coption value=\"Other\"\u003EOther\u003C/option\u003E\u003C/select\u003E\n      \u003Cspan class=\"addremove\"\u003E\u003Ca href=\"#\" class=\"remove\"\u003ERemove\u003C/a\u003E\u003C/span\u003E\n    \u003C/div\u003E\n  \u003C/div\u003E\n\n")
			//]]>
			</script></td>
</tr>
		 <?php
		break;
		case 'server' :
					//*********Insert IM Ids(contacts_im)****************//
					$count=0;
					extract($_POST[person]);
					$this->im				=$contact_data[instant_messengers];
					for(;$count<count($this->im);){					
					if($this->im[$count][address]!=''){
					$insert_sql_array = array();
					$insert_sql_array[contact_id] = $this->contact_id;
					$insert_sql_array[im]			= $this->im[$count][address];
					$insert_sql_array[im_network] = $this->im[$count+1][protocol];
					$insert_sql_array[type] 		= $this->im[$count+2][location];

					$this->db->insert(CONTACT_IM,$insert_sql_array);
					
					}
					$count+=3;
					}	
		break;
		
		default : //empty runat action.
		}			
	}
					
					
	//*************Add Contact Website*******************************//
	function AddContactWebsite($runat)
	{	
		switch($runat){
		
		case 'local' :
		?>
			<tr>
			  <th><h2>Websites</h2></th>
			  <td><div class="contact_forms web_addresses" 
			id="web_address_list_person"><div id="blank_slate_person_web_address" 
			class="blank_slate" style="">Add a website address</div><div 
			style="display: none;" class="contact_methods">
			
			  <div id="web_address_xbc8d699c006d854b636885" class="contact_method 
			new_contact_method">
				<input class="autofocus" id="person_contact_data_web_addresses__url"
			 name="person[contact_data][web_addresses][][url]" size="30" type="text">
				
				<select id="person_contact_data_web_addresses__location" 
			name="person[contact_data][web_addresses][][location]"><option 
			selected="selected" value="Work">Work</option>
			<option value="Personal">Personal</option>
			<option value="Other">Other</option></select>
				<span class="addremove"><a href="#" class="remove">Remove</a></span>
			  </div>
			
			<div id="add_person_web_address" class="add_contact_method" 
			style="display: none;">
			  <div class="add"><a href="#">Add another</a></div>
			</div>
			</div></div><script type="text/javascript">
			//<![CDATA[
			new ContactInfo.ContactForm("web_address_list_person", "\n\n  \u003Cdiv id=\"web_address_#{safe_id}\" class=\"contact_method new_contact_method\"\u003E\n    \u003Cinput class=\"autofocus\" id=\"person_contact_data_web_addresses__url\" name=\"person[contact_data][web_addresses][][url]\" size=\"30\" type=\"text\" value=\"\" /\u003E\n    \n    \u003Cselect id=\"person_contact_data_web_addresses__location\" name=\"person[contact_data][web_addresses][][location]\"\u003E\u003Coption value=\"Work\"\u003EWork\u003C/option\u003E\n\u003Coption value=\"Personal\"\u003EPersonal\u003C/option\u003E\n\u003Coption value=\"Other\"\u003EOther\u003C/option\u003E\u003C/select\u003E\n    \u003Cspan class=\"addremove\"\u003E\u003Ca href=\"#\" class=\"remove\"\u003ERemove\u003C/a\u003E\u003C/span\u003E\n  \u003C/div\u003E\n\n")
			//]]>
			</script></td>
			</tr>
			<?php
		break;
		case 'server' :
					//*********Insert Websites(contacts_website)****************//
					$count=0;
					extract($_POST[person]);
					$this->website 			=$contact_data[web_addresses];
					for(;$count<count($this->website);){
					if($this->website[$count][url]!='') {
					$insert_sql_array = array();
					$insert_sql_array[contact_id] = $this->contact_id;
					$insert_sql_array[website] 	= $this->website[$count][url];
					$insert_sql_array[type]		= $this->website[$count+1][location];

					$this->db->insert(CONTACT_WEBSITE,$insert_sql_array);
					}
					$count+=2;
					}	
		break;
		
		default : //empty runat action.
		}			
	}
					
					
	//*************Add Contact Twitter*******************************//
	function AddContactTwitter($runat)
	{	
		switch($runat){
		
		case 'local' :
		?>
				<tr>
			  <th><h2>Twitter</h2></th>
			  <td><div class="contact_forms twitter_accounts" 
			id="twitter_account_list_person"><div 
			id="blank_slate_person_twitter_account" class="blank_slate" style="">Add
			 a twitter account</div><div style="display: none;" 
			class="contact_methods">
			
			  <div id="twitter_account_x8d276c2c006d854b636885" 
			class="contact_method new_contact_method">
				<input class="autofocus" 
			id="person_contact_data_twitter_accounts__username" 
			name="person[contact_data][twitter_accounts][][username]" size="30" 
			type="text">
				
				<select id="person_contact_data_twitter_accounts__location" 
			name="person[contact_data][twitter_accounts][][location]"><option 
			selected="selected" value="Personal">Personal</option>
			<option value="Business">Business</option>
			<option value="Other">Other</option></select>
				<span class="addremove"><a href="#" class="remove">Remove</a></span>
			  </div>
			
			<div id="add_person_twitter_account" class="add_contact_method" 
			style="display: none;">
			  <div class="add"><a href="#">Add another</a></div>
			</div>
			</div></div><script type="text/javascript">
			//<![CDATA[
			new ContactInfo.ContactForm("twitter_account_list_person", "\n\n  \u003Cdiv id=\"twitter_account_#{safe_id}\" class=\"contact_method new_contact_method\"\u003E\n    \u003Cinput class=\"autofocus\" id=\"person_contact_data_twitter_accounts__username\" name=\"person[contact_data][twitter_accounts][][username]\" size=\"30\" type=\"text\" value=\"\" /\u003E\n    \n    \u003Cselect id=\"person_contact_data_twitter_accounts__location\" name=\"person[contact_data][twitter_accounts][][location]\"\u003E\u003Coption value=\"Personal\"\u003EPersonal\u003C/option\u003E\n\u003Coption value=\"Business\"\u003EBusiness\u003C/option\u003E\n\u003Coption value=\"Other\"\u003EOther\u003C/option\u003E\u003C/select\u003E\n    \u003Cspan class=\"addremove\"\u003E\u003Ca href=\"#\" class=\"remove\"\u003ERemove\u003C/a\u003E\u003C/span\u003E\n  \u003C/div\u003E\n\n")
			//]]>
			</script></td>
			</tr><?php
		break;
		case 'server' :
					//*********Insert Twitter Ids(contacts_twitter)****************//
				
					$count=0;
					extract($_POST[person]);
					$this->twitter_id		=$contact_data[twitter_accounts];
					for(;$count<count($this->twitter_id);) {
					if($this->twitter_id[$count][username]!='') {
					$insert_sql_array = array();
					$insert_sql_array[contact_id] = $this->contact_id;
					$insert_sql_array[twitter] = $this->twitter_id[$count][username];
					$insert_sql_array[type]		= $this->twitter_id[$count+1][location];
					$this->db->insert(CONTACT_TWITTER,$insert_sql_array);
					}
					$count+=2;
					}		
		break;
		
		default : //empty runat action.
		}			

	}
	
	
	//*************Add Contact Address*******************************//
	function AddContactAddress($runat)
	{	
		switch($runat){
		
		case 'local' :
		?>
	
			<tr class="addresses">
			  <th><h2>Address</h2></th>
			  <td><div class="contact_forms addresses" id="address_list_person"><div
			 id="blank_slate_person_address" class="blank_slate" style="display: none;">Add an 
			address</div><div  class="contact_methods">
			
			<div class="contact_method new_contact_method street" 
			id="address_xf6e911b9006d854b636885">
			  <div class="fields">
				<div style="position: relative;">
				  <p class="address"><textarea
			 class="autofocus overlayable" 
			id="person_contact_data_addresses_address_xf6e911b9006d854b636885_street"
			 name="person[contact_data][addresses][][street]" title="Address"></textarea></p>
				  <p><input
			 class="city overlayable" 
			id="person_contact_data_addresses_address_xf6e911b9006d854b636885_city" 
			name="person[contact_data][addresses][][city]" title="City" type="text">
				
				<!--<input
			 class="state overlayable" 
			id="person_contact_data_addresses_address_xf6e911b9006d854b636885_state"
			 name="person[contact_data][addresses][][state]" title="State" 
			type="text">-->
			
			<select
			 class="state overlayable" 
			id="person_contact_data_addresses_address_xf6e911b9006d854b636885_state"
			 name="person[contact_data][addresses][][state]" style="width:auto" >
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
			
			<input
			 class="zip overlayable" 
			id="person_contact_data_addresses_address_xf6e911b9006d854b636885_zip" 
			name="person[contact_data][addresses][][zip]" title="Zip" type="text">
			 </p>
				  <p><select class="country" 
			id="person_contact_data_addresses__country" 
			name="person[contact_data][addresses][][country]">
			<option selected="selected" value="">Choose a country...</option>
			<?php 
				$sql="select * from ".TBL_COUNTRIES;
				$record=$this->db->query($sql,__FILE__,__LINE__);
				while($row=$this->db->fetch_array($record))
				{			
			?>
			<option value="<?php echo $row[value]; ?>"><?php echo $row[value]; ?></option>
			<?php } ?>
			</select>
			</p>
				  <div class="loc_remove">
					
					<select id="person_contact_data_addresses__location" 
			name="person[contact_data][addresses][][location]"><option 
			selected="selected" value="Work">Work</option>
			<option value="Home">Home</option>
			<option value="Other">Other</option>
			</select>
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
			
			<div id="add_person_address" class="add_contact_method" style="display: 
			none;">
			  <div class="add"><a href="#">Add another</a></div>
			</div>
			</div></div><script type="text/javascript">
			//<![CDATA[
			new ContactInfo.ContactForm("address_list_person", "\n\n\u003Cdiv class=\"contact_method new_contact_method street\" id=\"address_#{safe_id}\"\u003E\n  \u003Cdiv class=\"fields\"\u003E\n    \u003Cdiv style=\"position: relative;\"\u003E\n      \u003Cp class=\"address\"\u003E\u003Ctextarea class=\"autofocus overlayable\" id=\"person_contact_data_addresses_address_#{safe_id}_street\" name=\"person[contact_data][addresses][][street]\" title=\"Address\"\u003E\u003C/textarea\u003E\u003C/p\u003E\n      \u003Cp\u003E\u003Cinput class=\"city overlayable\" id=\"person_contact_data_addresses_address_#{safe_id}_city\" name=\"person[contact_data][addresses][][city]\" title=\"City\" type=\"text\" /\u003E\n     \u003Cselect class=\"state overlayable\" id=\"person_contact_data_addresses_address_xf6e911b9006d854b636885_state\" name=\"person[contact_data][addresses][][state]\" style=\"width:auto\" \u003E \u003Coption selected=\"selected\" value=\"\"\u003ESelect State\u003C/option\u003E \u003Coption  value=\"Alaska\"\u003EAlaska\u003C/option\u003E \u003Coption  value=\"Alabama\"\u003EAlabama\u003C/option\u003E \u003Coption  value=\"Arkansas\"\u003EArkansas\u003C/option\u003E \u003Coption  value=\"Arizona\"\u003EArizona\u003C/option\u003E \u003Coption  value=\"California\"\u003ECalifornia\u003C/option\u003E \u003Coption  value=\"Colorado\"\u003EColorado\u003C/option\u003E \u003Coption  value=\"Connecticut\"\u003EConnecticut\u003C/option\u003E \u003Coption  value=\"D.C.\"\u003ED.C.\u003C/option\u003E \u003Coption  value=\"Delaware\"\u003EDelaware\u003C/option\u003E \u003Coption  value=\"Federated States of Micronesia\"\u003EFederated States of Micronesia\u003C/option\u003E \u003Coption  value=\"Florida\"\u003EFlorida\u003C/option\u003E \u003Coption  value=\"Georgia\"\u003EGeorgia\u003C/option\u003E \u003Coption  value=\"Hawaii\"\u003EHawaii\u003C/option\u003E \u003Coption  value=\"Iowa\"\u003EIowa\u003C/option\u003E \u003Coption  value=\"Idaho\"\u003EIdaho\u003C/option\u003E \u003Coption  value=\"Illinois\"\u003EIllinois\u003C/option\u003E \u003Coption  value=\"Indiana\"\u003EIndiana\u003C/option\u003E\u003Coption  value=\"Kansas\"\u003EKansas\u003C/option\u003E \u003Coption  value=\"Kentucky\"\u003EKentucky\u003C/option\u003E \u003Coption  value=\"Louisiana\"\u003ELouisiana\u003C/option\u003E \u003Coption  value=\"Massachusetts\"\u003EMassachusetts\u003C/option\u003E \u003Coption  value=\"Maryland\"\u003EMaryland\u003C/option\u003E \u003Coption  value=\"Marshall Islands\"\u003EMarshall Islands\u003C/option\u003E \u003Coption  value=\"Maine\"\u003EMaine\u003C/option\u003E  \u003Coption  value=\"Michigan\"\u003EMichigan\u003C/option\u003E \u003Coption  value=\"Minnesota\"\u003EMinnesota\u003C/option\u003E \u003Coption  value=\"Missouri\"\u003EMissouri\u003C/option\u003E \u003Coption  value=\"Mississippi\"\u003EMississippi\u003C/option\u003E \u003Coption  value=\"Montana\"\u003EMontana\u003C/option\u003E \u003Coption  value=\"North Carolina\"\u003ENorth Carolina\u003C/option\u003E \u003Coption  value=\"North Dakota\"\u003ENorth Dakota\u003C/option\u003E \u003Coption  value=\"Nebraska\"\u003ENebraska\u003C/option\u003E \u003Coption  value=\"New Hampshire\"\u003ENew Hampshire\u003C/option\u003E \u003Coption  value=\"New Jersey\"\u003ENew Jersey\u003C/option\u003E \u003Coption  value=\"New Mexico\"\u003ENew Mexico\u003C/option\u003E \u003Coption  value=\"Nevada\"\u003ENevada\u003C/option\u003E \u003Coption  value=\"New York\"\u003ENew York\u003C/option\u003E \u003Coption  value=\"Northern Mariana Islands\"\u003ENorthern Mariana Islands\u003C/option\u003E \u003Coption  value=\"Ohio\"\u003EOhio\u003C/option\u003E \u003Coption  value=\"Oklahoma\"\u003EOklahoma\u003C/option\u003E \u003Coption  value=\"Oregon\"\u003EOregon\u003C/option\u003E \u003Coption  value=\"Palau\"\u003EPalau\u003C/option\u003E \u003Coption  value=\"Puerto Rico\"\u003EPuerto Rico\u003C/option\u003E \u003Coption  value=\"Pennsylvania\"\u003EPennsylvania\u003C/option\u003E \u003Coption  value=\"Rhode Island\"\u003ERhode Island\u003C/option\u003E \u003Coption  value=\"South Carolina\"\u003ESouth Carolina\u003C/option\u003E  \u003Coption  value=\"South Dakota\"\u003ESouth Dakota\u003C/option\u003E \u003Coption  value=\"Tennessee\"\u003ETennessee\u003C/option\u003E \u003Coption  value=\"Texas\"\u003ETexas\u003C/option\u003E \u003Coption  value=\"Utah\"\u003EUtah\u003C/option\u003E \u003Coption  value=\"Virgin Islands\"\u003EVirgin Islands\u003C/option\u003E \u003Coption  value=\"Virginia\"\u003EVirginia\u003C/option\u003E \u003Coption  value=\"Vermont\"\u003EVermont\u003C/option\u003E \u003Coption  value=\"Washington\"\u003EWashington\u003C/option\u003E \u003Coption  value=\"Wisconsin\"\u003EWisconsin\u003C/option\u003E \u003Coption  value=\"West Virginia\"\u003EWest Virginia\u003C/option\u003E \u003Coption  value=\"Wyoming\"\u003EWyoming\u003C/option\u003E \u003C/select\u003E      \u003Cinput class=\"zip overlayable\" id=\"person_contact_data_addresses_address_#{safe_id}_zip\" name=\"person[contact_data][addresses][][zip]\" title=\"Zip\" type=\"text\" /\u003E \u003C/p\u003E\n      \u003Cp\u003E\u003Cselect class=\"country\" id=\"person_contact_data_addresses__country\" name=\"person[contact_data][addresses][][country]\"\u003E\u003Coption value=\"\"\u003EChoose a country...\u003C/option\u003E\n\u003Coption value=\"United States\"\u003EUnited States\u003C/option\u003E\n\u003Coption value=\"Canada\"\u003ECanada\u003C/option\u003E\n\u003Coption value=\"Denmark\"\u003EDenmark\u003C/option\u003E\n\u003Coption value=\"France\"\u003EFrance\u003C/option\u003E\n\u003Coption value=\"United Kingdom\"\u003EUnited Kingdom\u003C/option\u003E\n\u003Coption value=\"Australia\"\u003EAustralia\u003C/option\u003E\n\u003Coption value=\"Italy\"\u003EItaly\u003C/option\u003E\n\u003Coption value=\"Japan\"\u003EJapan\u003C/option\u003E\n\u003Coption value=\"Mexico\"\u003EMexico\u003C/option\u003E\n\u003Coption value=\"Spain\"\u003ESpain\u003C/option\u003E\n\u003Coption value=\"Sweden\"\u003ESweden\u003C/option\u003E\u003Coption value=\"\" disabled=\"disabled\"\u003E-------------\u003C/option\u003E\n\u003Coption value=\"Afghanistan\"\u003EAfghanistan\u003C/option\u003E\n\u003Coption value=\"\u00c5land Islands\"\u003E\u00c5land Islands\u003C/option\u003E\n\u003Coption value=\"Albania\"\u003EAlbania\u003C/option\u003E\n\u003Coption value=\"Algeria\"\u003EAlgeria\u003C/option\u003E\n\u003Coption value=\"American Samoa\"\u003EAmerican Samoa\u003C/option\u003E\n\u003Coption value=\"Andorra\"\u003EAndorra\u003C/option\u003E\n\u003Coption value=\"Angola\"\u003EAngola\u003C/option\u003E\n\u003Coption value=\"Anguilla\"\u003EAnguilla\u003C/option\u003E\n\u003Coption value=\"Antarctica\"\u003EAntarctica\u003C/option\u003E\n\u003Coption value=\"Antigua and Barbuda\"\u003EAntigua and Barbuda\u003C/option\u003E\n\u003Coption value=\"Argentina\"\u003EArgentina\u003C/option\u003E\n\u003Coption value=\"Armenia\"\u003EArmenia\u003C/option\u003E\n\u003Coption value=\"Aruba\"\u003EAruba\u003C/option\u003E\n\u003Coption value=\"Australia\"\u003EAustralia\u003C/option\u003E\n\u003Coption value=\"Austria\"\u003EAustria\u003C/option\u003E\n\u003Coption value=\"Azerbaijan\"\u003EAzerbaijan\u003C/option\u003E\n\u003Coption value=\"Bahamas\"\u003EBahamas\u003C/option\u003E\n\u003Coption value=\"Bahrain\"\u003EBahrain\u003C/option\u003E\n\u003Coption value=\"Bangladesh\"\u003EBangladesh\u003C/option\u003E\n\u003Coption value=\"Barbados\"\u003EBarbados\u003C/option\u003E\n\u003Coption value=\"Belarus\"\u003EBelarus\u003C/option\u003E\n\u003Coption value=\"Belgium\"\u003EBelgium\u003C/option\u003E\n\u003Coption value=\"Belize\"\u003EBelize\u003C/option\u003E\n\u003Coption value=\"Benin\"\u003EBenin\u003C/option\u003E\n\u003Coption value=\"Bermuda\"\u003EBermuda\u003C/option\u003E\n\u003Coption value=\"Bhutan\"\u003EBhutan\u003C/option\u003E\n\u003Coption value=\"Bolivia\"\u003EBolivia\u003C/option\u003E\n\u003Coption value=\"Bosnia and Herzegovina\"\u003EBosnia and Herzegovina\u003C/option\u003E\n\u003Coption value=\"Botswana\"\u003EBotswana\u003C/option\u003E\n\u003Coption value=\"Bouvet Island\"\u003EBouvet Island\u003C/option\u003E\n\u003Coption value=\"Brazil\"\u003EBrazil\u003C/option\u003E\n\u003Coption value=\"British Indian Ocean Territory\"\u003EBritish Indian Ocean Territory\u003C/option\u003E\n\u003Coption value=\"Brunei Darussalam\"\u003EBrunei Darussalam\u003C/option\u003E\n\u003Coption value=\"Bulgaria\"\u003EBulgaria\u003C/option\u003E\n\u003Coption value=\"Burkina Faso\"\u003EBurkina Faso\u003C/option\u003E\n\u003Coption value=\"Burundi\"\u003EBurundi\u003C/option\u003E\n\u003Coption value=\"Cambodia\"\u003ECambodia\u003C/option\u003E\n\u003Coption value=\"Cameroon\"\u003ECameroon\u003C/option\u003E\n\u003Coption value=\"Canada\"\u003ECanada\u003C/option\u003E\n\u003Coption value=\"Cape Verde\"\u003ECape Verde\u003C/option\u003E\n\u003Coption value=\"Cayman Islands\"\u003ECayman Islands\u003C/option\u003E\n\u003Coption value=\"Central African Republic\"\u003ECentral African Republic\u003C/option\u003E\n\u003Coption value=\"Chad\"\u003EChad\u003C/option\u003E\n\u003Coption value=\"Chile\"\u003EChile\u003C/option\u003E\n\u003Coption value=\"China\"\u003EChina\u003C/option\u003E\n\u003Coption value=\"Christmas Island\"\u003EChristmas Island\u003C/option\u003E\n\u003Coption value=\"Cocos (Keeling) Islands\"\u003ECocos (Keeling) Islands\u003C/option\u003E\n\u003Coption value=\"Colombia\"\u003EColombia\u003C/option\u003E\n\u003Coption value=\"Comoros\"\u003EComoros\u003C/option\u003E\n\u003Coption value=\"Congo\"\u003ECongo\u003C/option\u003E\n\u003Coption value=\"Congo, The Democratic Republic of the\"\u003ECongo, The Democratic Republic of the\u003C/option\u003E\n\u003Coption value=\"Cook Islands\"\u003ECook Islands\u003C/option\u003E\n\u003Coption value=\"Costa Rica\"\u003ECosta Rica\u003C/option\u003E\n\u003Coption value=\"C\u00f4te d'Ivoire\"\u003EC\u00f4te d'Ivoire\u003C/option\u003E\n\u003Coption value=\"Croatia\"\u003ECroatia\u003C/option\u003E\n\u003Coption value=\"Cuba\"\u003ECuba\u003C/option\u003E\n\u003Coption value=\"Cyprus\"\u003ECyprus\u003C/option\u003E\n\u003Coption value=\"Czech Republic\"\u003ECzech Republic\u003C/option\u003E\n\u003Coption value=\"Denmark\"\u003EDenmark\u003C/option\u003E\n\u003Coption value=\"Djibouti\"\u003EDjibouti\u003C/option\u003E\n\u003Coption value=\"Dominica\"\u003EDominica\u003C/option\u003E\n\u003Coption value=\"Dominican Republic\"\u003EDominican Republic\u003C/option\u003E\n\u003Coption value=\"Ecuador\"\u003EEcuador\u003C/option\u003E\n\u003Coption value=\"Egypt\"\u003EEgypt\u003C/option\u003E\n\u003Coption value=\"El Salvador\"\u003EEl Salvador\u003C/option\u003E\n\u003Coption value=\"Equatorial Guinea\"\u003EEquatorial Guinea\u003C/option\u003E\n\u003Coption value=\"Eritrea\"\u003EEritrea\u003C/option\u003E\n\u003Coption value=\"Estonia\"\u003EEstonia\u003C/option\u003E\n\u003Coption value=\"Ethiopia\"\u003EEthiopia\u003C/option\u003E\n\u003Coption value=\"Falkland Islands (Malvinas)\"\u003EFalkland Islands (Malvinas)\u003C/option\u003E\n\u003Coption value=\"Faroe Islands\"\u003EFaroe Islands\u003C/option\u003E\n\u003Coption value=\"Fiji\"\u003EFiji\u003C/option\u003E\n\u003Coption value=\"Finland\"\u003EFinland\u003C/option\u003E\n\u003Coption value=\"France\"\u003EFrance\u003C/option\u003E\n\u003Coption value=\"French Guiana\"\u003EFrench Guiana\u003C/option\u003E\n\u003Coption value=\"French Polynesia\"\u003EFrench Polynesia\u003C/option\u003E\n\u003Coption value=\"French Southern Territories\"\u003EFrench Southern Territories\u003C/option\u003E\n\u003Coption value=\"Gabon\"\u003EGabon\u003C/option\u003E\n\u003Coption value=\"Gambia\"\u003EGambia\u003C/option\u003E\n\u003Coption value=\"Georgia\"\u003EGeorgia\u003C/option\u003E\n\u003Coption value=\"Germany\"\u003EGermany\u003C/option\u003E\n\u003Coption value=\"Ghana\"\u003EGhana\u003C/option\u003E\n\u003Coption value=\"Gibraltar\"\u003EGibraltar\u003C/option\u003E\n\u003Coption value=\"Greece\"\u003EGreece\u003C/option\u003E\n\u003Coption value=\"Greenland\"\u003EGreenland\u003C/option\u003E\n\u003Coption value=\"Grenada\"\u003EGrenada\u003C/option\u003E\n\u003Coption value=\"Guadeloupe\"\u003EGuadeloupe\u003C/option\u003E\n\u003Coption value=\"Guam\"\u003EGuam\u003C/option\u003E\n\u003Coption value=\"Guatemala\"\u003EGuatemala\u003C/option\u003E\n\u003Coption value=\"Guernsey\"\u003EGuernsey\u003C/option\u003E\n\u003Coption value=\"Guinea\"\u003EGuinea\u003C/option\u003E\n\u003Coption value=\"Guinea-Bissau\"\u003EGuinea-Bissau\u003C/option\u003E\n\u003Coption value=\"Guyana\"\u003EGuyana\u003C/option\u003E\n\u003Coption value=\"Haiti\"\u003EHaiti\u003C/option\u003E\n\u003Coption value=\"Heard Island and McDonald Islands\"\u003EHeard Island and McDonald Islands\u003C/option\u003E\n\u003Coption value=\"Holy See (Vatican City State)\"\u003EHoly See (Vatican City State)\u003C/option\u003E\n\u003Coption value=\"Honduras\"\u003EHonduras\u003C/option\u003E\n\u003Coption value=\"Hong Kong\"\u003EHong Kong\u003C/option\u003E\n\u003Coption value=\"Hungary\"\u003EHungary\u003C/option\u003E\n\u003Coption value=\"Iceland\"\u003EIceland\u003C/option\u003E\n\u003Coption value=\"India\"\u003EIndia\u003C/option\u003E\n\u003Coption value=\"Indonesia\"\u003EIndonesia\u003C/option\u003E\n\u003Coption value=\"Iran, Islamic Republic of\"\u003EIran, Islamic Republic of\u003C/option\u003E\n\u003Coption value=\"Iraq\"\u003EIraq\u003C/option\u003E\n\u003Coption value=\"Ireland\"\u003EIreland\u003C/option\u003E\n\u003Coption value=\"Isle of Man\"\u003EIsle of Man\u003C/option\u003E\n\u003Coption value=\"Israel\"\u003EIsrael\u003C/option\u003E\n\u003Coption value=\"Italy\"\u003EItaly\u003C/option\u003E\n\u003Coption value=\"Jamaica\"\u003EJamaica\u003C/option\u003E\n\u003Coption value=\"Japan\"\u003EJapan\u003C/option\u003E\n\u003Coption value=\"Jersey\"\u003EJersey\u003C/option\u003E\n\u003Coption value=\"Jordan\"\u003EJordan\u003C/option\u003E\n\u003Coption value=\"Kazakhstan\"\u003EKazakhstan\u003C/option\u003E\n\u003Coption value=\"Kenya\"\u003EKenya\u003C/option\u003E\n\u003Coption value=\"Kiribati\"\u003EKiribati\u003C/option\u003E\n\u003Coption value=\"Korea, Democratic People's Republic of\"\u003EKorea, Democratic People's Republic of\u003C/option\u003E\n\u003Coption value=\"Korea, Republic of\"\u003EKorea, Republic of\u003C/option\u003E\n\u003Coption value=\"Kuwait\"\u003EKuwait\u003C/option\u003E\n\u003Coption value=\"Kyrgyzstan\"\u003EKyrgyzstan\u003C/option\u003E\n\u003Coption value=\"Lao People's Democratic Republic\"\u003ELao People's Democratic Republic\u003C/option\u003E\n\u003Coption value=\"Latvia\"\u003ELatvia\u003C/option\u003E\n\u003Coption value=\"Lebanon\"\u003ELebanon\u003C/option\u003E\n\u003Coption value=\"Lesotho\"\u003ELesotho\u003C/option\u003E\n\u003Coption value=\"Liberia\"\u003ELiberia\u003C/option\u003E\n\u003Coption value=\"Libyan Arab Jamahiriya\"\u003ELibyan Arab Jamahiriya\u003C/option\u003E\n\u003Coption value=\"Liechtenstein\"\u003ELiechtenstein\u003C/option\u003E\n\u003Coption value=\"Lithuania\"\u003ELithuania\u003C/option\u003E\n\u003Coption value=\"Luxembourg\"\u003ELuxembourg\u003C/option\u003E\n\u003Coption value=\"Macao\"\u003EMacao\u003C/option\u003E\n\u003Coption value=\"Macedonia, Republic of\"\u003EMacedonia, Republic of\u003C/option\u003E\n\u003Coption value=\"Madagascar\"\u003EMadagascar\u003C/option\u003E\n\u003Coption value=\"Malawi\"\u003EMalawi\u003C/option\u003E\n\u003Coption value=\"Malaysia\"\u003EMalaysia\u003C/option\u003E\n\u003Coption value=\"Maldives\"\u003EMaldives\u003C/option\u003E\n\u003Coption value=\"Mali\"\u003EMali\u003C/option\u003E\n\u003Coption value=\"Malta\"\u003EMalta\u003C/option\u003E\n\u003Coption value=\"Marshall Islands\"\u003EMarshall Islands\u003C/option\u003E\n\u003Coption value=\"Martinique\"\u003EMartinique\u003C/option\u003E\n\u003Coption value=\"Mauritania\"\u003EMauritania\u003C/option\u003E\n\u003Coption value=\"Mauritius\"\u003EMauritius\u003C/option\u003E\n\u003Coption value=\"Mayotte\"\u003EMayotte\u003C/option\u003E\n\u003Coption value=\"Mexico\"\u003EMexico\u003C/option\u003E\n\u003Coption value=\"Micronesia, Federated States of\"\u003EMicronesia, Federated States of\u003C/option\u003E\n\u003Coption value=\"Moldova\"\u003EMoldova\u003C/option\u003E\n\u003Coption value=\"Monaco\"\u003EMonaco\u003C/option\u003E\n\u003Coption value=\"Mongolia\"\u003EMongolia\u003C/option\u003E\n\u003Coption value=\"Montenegro\"\u003EMontenegro\u003C/option\u003E\n\u003Coption value=\"Montserrat\"\u003EMontserrat\u003C/option\u003E\n\u003Coption value=\"Morocco\"\u003EMorocco\u003C/option\u003E\n\u003Coption value=\"Mozambique\"\u003EMozambique\u003C/option\u003E\n\u003Coption value=\"Myanmar\"\u003EMyanmar\u003C/option\u003E\n\u003Coption value=\"Namibia\"\u003ENamibia\u003C/option\u003E\n\u003Coption value=\"Nauru\"\u003ENauru\u003C/option\u003E\n\u003Coption value=\"Nepal\"\u003ENepal\u003C/option\u003E\n\u003Coption value=\"Netherlands\"\u003ENetherlands\u003C/option\u003E\n\u003Coption value=\"Netherlands Antilles\"\u003ENetherlands Antilles\u003C/option\u003E\n\u003Coption value=\"New Caledonia\"\u003ENew Caledonia\u003C/option\u003E\n\u003Coption value=\"New Zealand\"\u003ENew Zealand\u003C/option\u003E\n\u003Coption value=\"Nicaragua\"\u003ENicaragua\u003C/option\u003E\n\u003Coption value=\"Niger\"\u003ENiger\u003C/option\u003E\n\u003Coption value=\"Nigeria\"\u003ENigeria\u003C/option\u003E\n\u003Coption value=\"Niue\"\u003ENiue\u003C/option\u003E\n\u003Coption value=\"Norfolk Island\"\u003ENorfolk Island\u003C/option\u003E\n\u003Coption value=\"Northern Mariana Islands\"\u003ENorthern Mariana Islands\u003C/option\u003E\n\u003Coption value=\"Norway\"\u003ENorway\u003C/option\u003E\n\u003Coption value=\"Oman\"\u003EOman\u003C/option\u003E\n\u003Coption value=\"Pakistan\"\u003EPakistan\u003C/option\u003E\n\u003Coption value=\"Palau\"\u003EPalau\u003C/option\u003E\n\u003Coption value=\"Palestinian Territory, Occupied\"\u003EPalestinian Territory, Occupied\u003C/option\u003E\n\u003Coption value=\"Panama\"\u003EPanama\u003C/option\u003E\n\u003Coption value=\"Papua New Guinea\"\u003EPapua New Guinea\u003C/option\u003E\n\u003Coption value=\"Paraguay\"\u003EParaguay\u003C/option\u003E\n\u003Coption value=\"Peru\"\u003EPeru\u003C/option\u003E\n\u003Coption value=\"Philippines\"\u003EPhilippines\u003C/option\u003E\n\u003Coption value=\"Pitcairn\"\u003EPitcairn\u003C/option\u003E\n\u003Coption value=\"Poland\"\u003EPoland\u003C/option\u003E\n\u003Coption value=\"Portugal\"\u003EPortugal\u003C/option\u003E\n\u003Coption value=\"Puerto Rico\"\u003EPuerto Rico\u003C/option\u003E\n\u003Coption value=\"Qatar\"\u003EQatar\u003C/option\u003E\n\u003Coption value=\"Reunion\"\u003EReunion\u003C/option\u003E\n\u003Coption value=\"Romania\"\u003ERomania\u003C/option\u003E\n\u003Coption value=\"Russian Federation\"\u003ERussian Federation\u003C/option\u003E\n\u003Coption value=\"Rwanda\"\u003ERwanda\u003C/option\u003E\n\u003Coption value=\"Saint Barth\u00e9lemy\"\u003ESaint Barth\u00e9lemy\u003C/option\u003E\n\u003Coption value=\"Saint Helena\"\u003ESaint Helena\u003C/option\u003E\n\u003Coption value=\"Saint Kitts and Nevis\"\u003ESaint Kitts and Nevis\u003C/option\u003E\n\u003Coption value=\"Saint Lucia\"\u003ESaint Lucia\u003C/option\u003E\n\u003Coption value=\"Saint Martin (French part)\"\u003ESaint Martin (French part)\u003C/option\u003E\n\u003Coption value=\"Saint Pierre and Miquelon\"\u003ESaint Pierre and Miquelon\u003C/option\u003E\n\u003Coption value=\"Saint Vincent and the Grenadines\"\u003ESaint Vincent and the Grenadines\u003C/option\u003E\n\u003Coption value=\"Samoa\"\u003ESamoa\u003C/option\u003E\n\u003Coption value=\"San Marino\"\u003ESan Marino\u003C/option\u003E\n\u003Coption value=\"Sao Tome and Principe\"\u003ESao Tome and Principe\u003C/option\u003E\n\u003Coption value=\"Saudi Arabia\"\u003ESaudi Arabia\u003C/option\u003E\n\u003Coption value=\"Senegal\"\u003ESenegal\u003C/option\u003E\n\u003Coption value=\"Serbia\"\u003ESerbia\u003C/option\u003E\n\u003Coption value=\"Seychelles\"\u003ESeychelles\u003C/option\u003E\n\u003Coption value=\"Sierra Leone\"\u003ESierra Leone\u003C/option\u003E\n\u003Coption value=\"Singapore\"\u003ESingapore\u003C/option\u003E\n\u003Coption value=\"Slovakia\"\u003ESlovakia\u003C/option\u003E\n\u003Coption value=\"Slovenia\"\u003ESlovenia\u003C/option\u003E\n\u003Coption value=\"Solomon Islands\"\u003ESolomon Islands\u003C/option\u003E\n\u003Coption value=\"Somalia\"\u003ESomalia\u003C/option\u003E\n\u003Coption value=\"South Africa\"\u003ESouth Africa\u003C/option\u003E\n\u003Coption value=\"South Georgia and the South Sandwich Islands\"\u003ESouth Georgia and the South Sandwich Islands\u003C/option\u003E\n\u003Coption value=\"Spain\"\u003ESpain\u003C/option\u003E\n\u003Coption value=\"Sri Lanka\"\u003ESri Lanka\u003C/option\u003E\n\u003Coption value=\"Sudan\"\u003ESudan\u003C/option\u003E\n\u003Coption value=\"Suriname\"\u003ESuriname\u003C/option\u003E\n\u003Coption value=\"Svalbard and Jan Mayen\"\u003ESvalbard and Jan Mayen\u003C/option\u003E\n\u003Coption value=\"Swaziland\"\u003ESwaziland\u003C/option\u003E\n\u003Coption value=\"Sweden\"\u003ESweden\u003C/option\u003E\n\u003Coption value=\"Switzerland\"\u003ESwitzerland\u003C/option\u003E\n\u003Coption value=\"Syrian Arab Republic\"\u003ESyrian Arab Republic\u003C/option\u003E\n\u003Coption value=\"Taiwan\"\u003ETaiwan\u003C/option\u003E\n\u003Coption value=\"Tajikistan\"\u003ETajikistan\u003C/option\u003E\n\u003Coption value=\"Tanzania, United Republic of\"\u003ETanzania, United Republic of\u003C/option\u003E\n\u003Coption value=\"Thailand\"\u003EThailand\u003C/option\u003E\n\u003Coption value=\"Timor-Leste\"\u003ETimor-Leste\u003C/option\u003E\n\u003Coption value=\"Togo\"\u003ETogo\u003C/option\u003E\n\u003Coption value=\"Tokelau\"\u003ETokelau\u003C/option\u003E\n\u003Coption value=\"Tonga\"\u003ETonga\u003C/option\u003E\n\u003Coption value=\"Trinidad and Tobago\"\u003ETrinidad and Tobago\u003C/option\u003E\n\u003Coption value=\"Tunisia\"\u003ETunisia\u003C/option\u003E\n\u003Coption value=\"Turkey\"\u003ETurkey\u003C/option\u003E\n\u003Coption value=\"Turkmenistan\"\u003ETurkmenistan\u003C/option\u003E\n\u003Coption value=\"Turks and Caicos Islands\"\u003ETurks and Caicos Islands\u003C/option\u003E\n\u003Coption value=\"Tuvalu\"\u003ETuvalu\u003C/option\u003E\n\u003Coption value=\"Uganda\"\u003EUganda\u003C/option\u003E\n\u003Coption value=\"Ukraine\"\u003EUkraine\u003C/option\u003E\n\u003Coption value=\"United Arab Emirates\"\u003EUnited Arab Emirates\u003C/option\u003E\n\u003Coption value=\"United Kingdom\"\u003EUnited Kingdom\u003C/option\u003E\n\u003Coption value=\"United States\"\u003EUnited States\u003C/option\u003E\n\u003Coption value=\"United States Minor Outlying Islands\"\u003EUnited States Minor Outlying Islands\u003C/option\u003E\n\u003Coption value=\"Uruguay\"\u003EUruguay\u003C/option\u003E\n\u003Coption value=\"Uzbekistan\"\u003EUzbekistan\u003C/option\u003E\n\u003Coption value=\"Vanuatu\"\u003EVanuatu\u003C/option\u003E\n\u003Coption value=\"Venezuela\"\u003EVenezuela\u003C/option\u003E\n\u003Coption value=\"Viet Nam\"\u003EViet Nam\u003C/option\u003E\n\u003Coption value=\"Virgin Islands, British\"\u003EVirgin Islands, British\u003C/option\u003E\n\u003Coption value=\"Virgin Islands, U.S.\"\u003EVirgin Islands, U.S.\u003C/option\u003E\n\u003Coption value=\"Wallis and Futuna\"\u003EWallis and Futuna\u003C/option\u003E\n\u003Coption value=\"Western Sahara\"\u003EWestern Sahara\u003C/option\u003E\n\u003Coption value=\"Yemen\"\u003EYemen\u003C/option\u003E\n\u003Coption value=\"Zambia\"\u003EZambia\u003C/option\u003E\n\u003Coption value=\"Zimbabwe\"\u003EZimbabwe\u003C/option\u003E\u003C/select\u003E\u003C/p\u003E\n      \u003Cdiv class=\"loc_remove\"\u003E\n        \n        \u003Cselect id=\"person_contact_data_addresses__location\" name=\"person[contact_data][addresses][][location]\"\u003E\u003Coption value=\"Work\"\u003EWork\u003C/option\u003E\n\u003Coption value=\"Home\"\u003EHome\u003C/option\u003E\n\u003Coption value=\"Other\"\u003EOther\u003C/option\u003E\u003C/select\u003E\n        \u003Cspan class=\"addremove\"\u003E\u003Ca href=\"#\" class=\"remove\"\u003ERemove\u003C/a\u003E\u003C/span\u003E\n      \u003C/div\u003E\n    \u003C/div\u003E\n  \u003C/div\u003E\n\u003C/div\u003E\n\u003Cscript type=\"text/javascript\"\u003E\n//\u003C![CDATA[\n$$('.street .overlayable').each(function(input) { ContactInfo.Overlay.wrap(input); });\n//]]\u003E\n\u003C/script\u003E\n\n")
			//]]>
			</script></td>
			</tr>

		<?php	
		break;
		case 'server' :

					//*********Insert Addresses(contacts_address)****************//
					$count=0;
					for(;$count<count($this->street_address);) {
					if( $this->street_address[$count][street]!='') {
					$insert_sql_array = array();
					
					$insert_sql_array = array();
					$insert_sql_array[contact_id]		= $this->contact_id;
					$insert_sql_array[street_address]	= $this->street_address[$count][street];
					$insert_sql_array[city] 			= $this->street_address[$count+1][city];
					$insert_sql_array[state] 			= $this->street_address[$count+2][state];
					$insert_sql_array[zip] 				= $this->street_address[$count+3][zip];
					$insert_sql_array[country] 			= $this->street_address[$count+4][country];
					$insert_sql_array[type]				= $this->street_address[$count+5][location];
										
					$this->db->insert(CONTACT_ADDRESS,$insert_sql_array);
					}
					$count+=6;
					}		
		break;
		
		default : //empty runat action.
		}			
	}

	//*************Display Add Contact Submit button********************//
	function AddContactSubmitButton($runat)
	{	
		switch($runat){
		
		case 'local' :
					?>
		<tr>
			  <td colspan="2" align="center"><input type="submit" name="submit" value="Add Contact" 
						onClick="return <?php echo $this->ValidationFunctionName?>();" style="width:auto;" /> &nbsp;or &nbsp;<a href="contacts.php">Cancel</a></td>
		</tr>
	<?php
		break;
		default :
		 //empty runat action.
		}			
	}

	
	//*************Edit Contact Phone*******************************//
	function EditContactPhone($runat,$cont_id='')
	{	
	
		switch($runat){
		
		case 'local' :	
		          if($cont_id !=''){
				  	$sql="Select * from ".CONTACT_PHONE." where contact_id='$cont_id'";
				  }
				  else
				  {
					$sql="Select * from ".CONTACT_PHONE." where contact_id='$this->contact_id'";
				  }	
				  $result=$this->db->query($sql,__FILE,__LINE__);
		?>
			<tr>
			<th><h2>Phone</h2></th>
			  <td><div class="contact_forms phone_numbers" 	id="phone_number_list_person">
			<?php if($this->db->num_rows($result)==0){ ?>
					<div id="blank_slate_person_phone_number" class="blank_slate" style="">Add a phone number</div>
					<div style="display: none;" class="contact_methods">
			<?php }else { ?>
					<div id="blank_slate_person_phone_number" class="blank_slate" style="display: none;">Add a phone number</div>
					<div style="" class="contact_methods">
			<?php } 
			$row=$this->db->fetch_array($result);
			?>
			
			<div id="phone_number_x7f9e493d006d854b636885" class="contact_method new_contact_method edit_phone">
				<input class="autofocus" id="person_contact_data_phone_numbers__number" 
			name="person[contact_data][phone_numbers][][number]" onkeyup="this.value = (this.value).replace(/\D/g,'');" size="30" 
			type="text" value="<?php echo $row[number]; ?>" onkeyup="this.value = (this.value).replace(/\D/g,'');" />


			<select id="person_contact_data_phone_numbers__location" 
			name="person[contact_data][phone_numbers][][location]"><option 
			selected="selected" value="Work">Work</option>
							  <option value="Work" <?php if($row[type]=='Work') echo ' selected="selected"'; ?>>Work</option>
							  <option value="Mobile" <?php if($row[type]=='Mobile') echo ' selected="selected"'; ?> >Mobile</option>
							  <option value="Fax" <?php if($row[type]=='Fax') echo ' selected="selected"'; ?>>Fax</option>
							  <option value="Pager" <?php if($row[type]=='Pager') echo ' selected="selected"'; ?>>Pager</option>
							  <option value="Home"