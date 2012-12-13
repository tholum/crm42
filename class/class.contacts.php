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
	function json_search_contact( $search ){
            $arr = explode(" ", $search);
            $sql = "SELECT * FROM contacts a ";
            $where = array();
            foreach( $arr as $value ){
                if( $value != ''){
                    $where[] = "( a.company_name LIKE '%$value%' )";
                }
            }
            $where[] = "company_name <> ''";
            if( count($where) != 0 ){
                $sql .= " WHERE " . implode(" AND ", $where);
            }
            
            $result = $this->db->query($sql);
            $json = array();
            while( $row = $this->db->fetch_assoc($result)){
                $row["value"] = $row["company_name"];
                $row["label"] = $row["value"];
                $row["module_name"] = "CONTACTS";
                $row["module_id"] = $row['contact_id'];
                //$row["sql"] = $sql;
                $json[] = $row;
            }
            
            return json_encode($json);
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
        function contact_type_dropdown($subtype,$selected){
            ob_start();
            ?>
<option value="" >Select One</option>
<option value="Work" <?php if( strtolower($selected) == "work"){ echo " selected "; } ?> >Work</option>
<option value="Home" <?php if( strtolower($selected) == "home"){ echo " selected "; } ?> >Home</option>
            <?php 
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
        
        function state_dropdown( $selected ){
            ob_start(); 
            ?>
<option value="" >Select One</option>
<option value="WI" <?php if( strtolower( $selected  ) == "wi" ){ echo " SELECTED "; } ?> >WI</option>
            <?php
            $html =  ob_get_contents();
            ob_end_clean();
            return $html;
        }
        
        function save_contact_phone($phone_id , $col,$value){
            $u = array();
            $u[$col] = $value;
            $this->db->update('contacts_phone',$u,'phone_id',$phone_id);            
        }
        function new_contact_phone($contact_id){
            $i=array('contact_id' => $contact_id);
            $this->db->insert('contacts_phone', $i);
            return $this->edit_contact_phone_ui($contact_id);
        }

        function delete_phone($contact_id,$phone_id){
            $this->db->query("DELETE FROM contacts_phone WHERE phone_id = '$phone_id'");
            return $this->edit_contact_phone_ui($contact_id);
        }
                function edit_contact_phone_ui( $contact_id ){
            ob_start();
            $phone_numbers = $this->db->query("SELECT * FROM `contacts_phone` WHERE `contact_id` ='$contact_id'");
            while($phone = $this->db->fetch_assoc($phone_numbers)){
            ?>
                <div class="edit_phone_container_<?php echo $phone['phone_id']; ?> edit_phone_container">
                    <input style="width: 300px;" value="<?php echo $phone['number']; ?>" onchange="em.save_contact_phone('<?php echo $phone['phone_id']; ?>' , 'number' , $(this).val() , {} );" />
                    <input <?php if($phone['ext']==0){echo('class="ext_blank" onfocus="this.value=\'\';"');} ?> style="width: 50px;" value="<?php if($phone['ext']==0){echo('ext');}else{echo $phone['ext'];} ?>" onkeyup="em.save_contact_phone('<?php echo $phone['phone_id']; ?>' , 'ext' , $(this).val() , {} );" /> 
                    <select onchange="em.save_contact_phone('<?php echo $phone['phone_id']; ?>' , 'type' , $(this).val() , {} );" >
                        <?php echo $this->contact_type_dropdown('phone', $phone['type']); ?>
                    </select>
                    <a onclick="em.delete_phone('<?php echo $contact_id; ?>' ,'<?php echo $phone['phone_id']; ?>' , {target: 'edit_company_phone_<?php echo $contact_id; ?>'}); "><button>delete<div class="in_button trash_can_normal"></div></button></a>
                </div>

            <?php
            } 
            ?> 
            <a onclick="em.new_contact_phone('<?php echo $contact_id; ?>' , {target: 'edit_company_phone_<?php echo $contact_id; ?>'}); ">Add another</a>
            <?php
            $html=  ob_get_contents();
            ob_end_clean();
            return $html;
        }
        function save_contact_email($phone_id , $col,$value){
            $u = array();
            $u[$col] = $value;
            $this->db->update('contacts_email',$u,'email_id',$phone_id);            
        }
        function new_contact_email($contact_id){
            $i=array('contact_id' => $contact_id);
            $this->db->insert('contacts_email', $i);
            return $this->edit_contact_email_ui($contact_id);
        }
        function delete_email($contact_id,$email_id){
            $this->db->query("DELETE FROM contacts_email WHERE email_id = '$email_id'");
            return $this->edit_contact_email_ui($contact_id);
        }
        function edit_contact_email_ui( $contact_id ){
            ob_start();
            $emails = $this->db->query("SELECT * FROM `contacts_email` WHERE `contact_id` ='$contact_id'");
            while($email = $this->db->fetch_assoc($emails)){
            ?>
                <div class="edit_email_container_<?php echo $email['email_id']; ?> edit_email_container">
                    <input style="width: 300px;" value="<?php echo $email['email']; ?>" onchange="em.save_contact_email('<?php echo $email['email_id']; ?>' , 'email' , $(this).val() , {} );" />
                    <select onchange="em.save_contact_email('<?php echo $email['email_id']; ?>' , 'type' , $(this).val() , {} );" >
                        <?php echo $this->contact_type_dropdown('email', $email['type']); ?>
                    </select>
                    <a onclick="em.delete_email('<?php echo $contact_id; ?>' ,'<?php echo $email['email_id']; ?>' , {target: 'edit_company_email_<?php echo $contact_id; ?>'}); "><button>delete<div class="in_button trash_can_normal"></div></button></a>
                </div>

            <?php
            } 
            ?> 
            <a onclick="em.new_contact_email('<?php echo $contact_id; ?>' , {target: 'edit_company_email_<?php echo $contact_id; ?>'}); ">Add another</a>
            <?php
            $html=  ob_get_contents();
            ob_end_clean();
            return $html;
        }
        function save_contact_website($website_id , $col,$value){
            $u = array();
            $u[$col] = $value;
            $this->db->update('contacts_website',$u,'website_id',$website_id);            
        }
        function new_contact_website($contact_id){
            $i=array('contact_id' => $contact_id);
            $this->db->insert('contacts_website', $i);
            return $this->edit_contact_website_ui($contact_id);
        }
        function delete_website($contact_id,$website_id){
            $this->db->query("DELETE FROM contacts_website WHERE website_id = '$website_id'");
            return $this->edit_contact_website_ui($contact_id);
        }
        function edit_contact_website_ui( $contact_id ){
            ob_start();
            $websites = $this->db->query("SELECT * FROM `contacts_website` WHERE `contact_id` ='$contact_id'");
            while($website = $this->db->fetch_assoc($websites)){
            ?>
                <div class="edit_website_container_<?php echo $website['website_id']; ?> edit_website_container">
                    <input style="width: 300px;" value="<?php echo $website['website']; ?>" onchange="em.save_contact_website('<?php echo $website['website_id']; ?>' , 'website' , $(this).val() , {} );" />
                    <select onchange="em.save_contact_website('<?php echo $website['website_id']; ?>' , 'type' , $(this).val() , {} );" >
                        <?php echo $this->contact_type_dropdown('website', $website['type']); ?>
                    </select>
                    <a onclick="em.delete_website('<?php echo $contact_id; ?>' ,'<?php echo $website['website_id']; ?>' , {target: 'edit_company_website_<?php echo $contact_id; ?>'}); "><button>delete<div class="in_button trash_can_normal"></div></button></a>
                </div>

            <?php
            } 
            ?> 
            <a onclick="em.new_contact_website('<?php echo $contact_id; ?>' , {target: 'edit_company_website_<?php echo $contact_id; ?>'}); ">Add another</a>
            <?php
            $html=  ob_get_contents();
            ob_end_clean();
            return $html;
        }
        function save_contact_address($address_id , $col,$value){
            $u = array();
            $u[$col] = $value;
            $this->db->update('contacts_address',$u,'address_id',$address_id);            
        }
        function new_contact_address($contact_id){
            $i=array('contact_id' => $contact_id);
            $this->db->insert('contacts_address', $i);
            return $this->edit_contact_address_ui($contact_id);
        }
        function delete_address($contact_id,$address_id){
            $this->db->query("DELETE FROM contacts_address WHERE address_id = '$address_id'");
            return $this->edit_contact_address_ui($contact_id);
        }
        function delete_contact($contact_id){
            //CTLTODO: Make it so we only deactivate a contact, not actually delete the contact
            $this->db->query("DELETE FROM contacts_address WHERE contact_id = '$contact_id'");
            $this->db->query("DELETE FROM contacts_email WHERE contact_id = '$contact_id'");
            $this->db->query("DELETE FROM contacts_phone WHERE contact_id = '$contact_id'");
            $this->db->query("DELETE FROM contacts WHERE contact_id = '$contact_id'");
            return '';
        }
        function edit_contact_address_ui( $contact_id ){
            ob_start();
            $addresss = $this->db->query("SELECT * FROM `contacts_address` WHERE `contact_id` ='$contact_id'");
            while($address = $this->db->fetch_assoc($addresss)){
            ?>
                <div class="edit_address_container_<?php echo $address['address_id']; ?> edit_address_container">
                    <input style="width: 500px;" value="<?php echo $address['street_address']; ?>" onchange="em.save_contact_address('<?php echo $address['address_id']; ?>' , 'street_address' , $(this).val() , {} );" /><br>
                    <input style="width: 375px;" value="<?php echo $address['city']; ?>" onchange="em.save_contact_address('<?php echo $address['address_id']; ?>' , 'city' , $(this).val() , {} );" />
                    <select style="width: 50px;" onchange="em.save_contact_address('<?php echo $address['address_id']; ?>' , 'state' , $(this).val() , {} );" ><?php echo $this->state_dropdown( $address['state']  ); ?></select>
                    <input style="width: 60px;" value="<?php echo $address['zip']; ?>" onchange="em.save_contact_address('<?php echo $address['address_id']; ?>' , 'zip' , $(this).val() , {} );" />
                    <br>
                    <select onchange="em.save_contact_address('<?php echo $address['address_id']; ?>' , 'type' , $(this).val() , {} );" >
                        <?php echo $this->contact_type_dropdown('address', $address['type']); ?>
                    </select>
                    <a onclick="em.delete_address('<?php echo $contact_id; ?>' ,'<?php echo $address['address_id']; ?>' , {target: 'edit_company_address_<?php echo $contact_id; ?>'}); "><button>delete<div class="in_button trash_can_normal"></div></button></a>
                </div>

            <?php
            } 
            ?> 
            <a onclick="em.new_contact_address('<?php echo $contact_id; ?>' , {target: 'edit_company_address_<?php echo $contact_id; ?>'}); ">Add another</a>
            <?php
            $html=  ob_get_contents();
            ob_end_clean();
            return $html;
        }
        function save_contact_name($contact_id , $name ){
            $u = array();
            $u['company_name'] = $name;
            $this->db->update('contacts',$u,'contact_id',$contact_id);
        }
        function save_first_name($contact_id , $name ){
            $u = array();
            $u['first_name'] = $name;
            $this->db->update('contacts',$u,'contact_id',$contact_id);
        }
        function save_last_name($contact_id , $name ){
            $u = array();
            $u['last_name'] = $name;
            $this->db->update('contacts',$u,'contact_id',$contact_id);
        }
        function create_contact($overide=array()){
            $options = array();
            $options['phone_number'] = '';
            foreach( $overide as $n => $v){
                $options[$n] = $v;
            }
            $this->db->insert('contacts', array('company_name' => 'New Contact','user_id' => $_SESSION['user_id'] , 'type' => 'Company' ));
            $contact_id = $this->db->last_insert_id();
            if( $options['phone_number'] != ''){
                $i = array();
                $i['contact_id'] = $contact_id;
                $i['number'] = $options['phone_number'];
                $i['type'] = 'Work';
                $this->db->insert('contacts_phone' , $i );
            }
            return $contact_id;
        }
        function get_company_contact_id($contact_id){
            $person = $this->db->fetch_assoc($this->db->query("SELECT * FROM contacts WHERE contact_id = '$contact_id' "));
            if( $person['type'] == "People"){
                return $person['company'];
            } else {
                return $contact_id;
            }
            
        }
        function edit_contact_ui($contact_id){
            ob_start();
            $contact_details = $this->db->fetch_assoc($this->db->query("SELECT * FROM `contacts` WHERE `contact_id` ='$contact_id'"));
            //var_dump($contact_details);
            if( $contact_details['type'] != "People"){
            ?>
            <h2>Company Name: </h2>
                <div class="edit_company"><input value="<?php echo $contact_details['company_name']; ?>" onkeypress="em.save_contact_name('<?php echo $contact_id; ?>',$(this).val(),{});" onchange="em.save_contact_name('<?php echo $contact_id; ?>',$(this).val(),{});" /></div>
            <?php } else { ?>
                <h2>First Name: </h2>
                <div class="edit_company"><input value="<?php echo $contact_details['first_name']; ?>" onkeypress="em.save_first_name('<?php echo $contact_id; ?>',$(this).val(),{});" onchange="em.save_contact_name('<?php echo $contact_id; ?>',$(this).val(),{});" /></div>
                <h2>Last Name: </h2>
                <div class="edit_company"><input value="<?php echo $contact_details['last_name']; ?>" onkeypress="em.save_last_name('<?php echo $contact_id; ?>',$(this).val(),{});" onchange="em.save_contact_name('<?php echo $contact_id; ?>',$(this).val(),{});" /></div>
                <?php } ?>
                <h2>Phone Numbers: </h2>
                <div class="edit_company_phone" id="edit_company_phone_<?php echo $contact_id; ?>"><?php echo $this->edit_contact_phone_ui($contact_id); ?></div>
                <h2>Email Address: </h2>
                <div class="edit_company_email" id="edit_company_email_<?php echo $contact_id; ?>"><?php echo $this->edit_contact_email_ui($contact_id); ?></div>
                <h2>Websites: </h2>
                <div class="edit_company_website" id="edit_company_website_<?php echo $contact_id; ?>"><?php echo $this->edit_contact_website_ui($contact_id); ?></div>
                <h2>Addresses: </h2>
                <div class="edit_company_address" id="edit_company_address_<?php echo $contact_id; ?>"><?php echo $this->edit_contact_address_ui( $contact_id ); ?></div>
            <?php
            $html=  ob_get_contents();
            ob_end_clean();
            return $html;
        }
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
							  <option value="Home" <?php if($row[type]=='Home') echo ' selected="selected"'; ?>>Home</option>
							  <option value="Skype" <?php if($row[type]=='Skype') echo ' selected="selected"'; ?>>Skype</option>
							  <option value="Other" <?php if($row[type]=='Other') echo ' selected="selected"'; ?>>Other</option>
			</select>
				<span class="addremove"><a href="#" class="remove">Remove</a></span>
			  </div>


			
			<?php while($row=$this->db->fetch_array($result)) { ?>
			  <div id="phone_number_x7f9e493d006d854b636885" class="contact_method 
			new_contact_method edit_phone">
				<input class="autofocus" 
			id="person_contact_data_phone_numbers__number" 
			name="person[contact_data][phone_numbers][][number]" size="30" onkeyup="this.value = (this.value).replace(/\D/g,'');" 
			type="text" value="<?php echo $row[number]; ?>">


			<select id="person_contact_data_phone_numbers__location" 
			name="person[contact_data][phone_numbers][][location]"><option 
			selected="selected" value="Work">Work</option>
							  <option value="Work" <?php if($row[type]=='Work') echo ' selected="selected"'; ?>>Work</option>
							  <option value="Mobile" <?php if($row[type]=='Mobile') echo ' selected="selected"'; ?> >Mobile</option>
							  <option value="Fax" <?php if($row[type]=='Fax') echo ' selected="selected"'; ?>>Fax</option>
							  <option value="Pager" <?php if($row[type]=='Pager') echo ' selected="selected"'; ?>>Pager</option>
							  <option value="Home" <?php if($row[type]=='Home') echo ' selected="selected"'; ?>>Home</option>
							  <option value="Skype" <?php if($row[type]=='Skype') echo ' selected="selected"'; ?>>Skype</option>
							  <option value="Other" <?php if($row[type]=='Other') echo ' selected="selected"'; ?>>Other</option>
			</select>
				<span class="addremove"><a href="#" class="remove">Remove</a></span>
			  </div>
			  <?php 
			  }?>
			  
			<?php if($this->db->num_rows($result)==0){ ?>
			<div id="add_person_phone_number" class="add_contact_method" 
			style="display: none;">
			<?php }else { ?>
			<div id="add_person_phone_number" class="add_contact_method" 
			style="display: none;">
			<?php } ?>
			  <div class="add"><a href="#">Add another</a></div>
			</div>
			</div></div><script type="text/javascript">
			//<![CDATA[
			new ContactInfo.ContactForm("phone_number_list_person", "\n\n  \u003Cdiv id=\"phone_number_#{safe_id}\" class=\"contact_method new_contact_method edit_phone\"\u003E\n    \u003Cinput class=\"autofocus\" id=\"person_contact_data_phone_numbers__number\" name=\"person[contact_data][phone_numbers][][number]\" onkeyup=\"this.value = (this.value).replace(/\\D/g,\'\');\" size=\"30\" type=\"text\" value=\"\" /\u003E\n    \n    \u003Cselect id=\"person_contact_data_phone_numbers__location\" name=\"person[contact_data][phone_numbers][][location]\"\u003E\u003Coption value=\"Work\"\u003EWork\u003C/option\u003E\n\u003Coption value=\"Mobile\"\u003EMobile\u003C/option\u003E\n\u003Coption value=\"Fax\"\u003EFax\u003C/option\u003E\n\u003Coption value=\"Pager\"\u003EPager\u003C/option\u003E\n\u003Coption value=\"Home\"\u003EHome\u003C/option\u003E\n\u003Coption value=\"Skype\"\u003ESkype\u003C/option\u003E\n\u003Coption value=\"Other\"\u003EOther\u003C/option\u003E\u003C/select\u003E\n    \u003Cspan class=\"addremove\"\u003E\u003Ca href=\"#\" class=\"remove\"\u003ERemove\u003C/a\u003E\u003C/span\u003E\n  \u003C/div\u003E\n\n")
			//]]>
			</script></td>
			</tr>
  		<?php
		break;
		
		case 'server' : 
					//*********Edit Phone Numbers(contacts_phone)****************//
					
					//first write sql to delete all the phone no. of this contact .
					if($cont_id !=''){
						$sql="delete from ".CONTACT_PHONE." where contact_id='$cont_id'";
					}
					else{
						$sql="delete from ".CONTACT_PHONE." where contact_id='$this->contact_id'";
					}
					$this->db->query($sql,__FILE__,__LINE__);
					
					extract($_POST[person]);
					$this->number			=$contact_data[phone_numbers];
					//then insert these phone no. as fresh
					$count=0;
					for(;$count<count($this->number);) {
					if($this->number[$count][number]!=''){
					$symbols= explode(',',SYMBOLS);
					$insert_sql_array = array();
					
					// condition for edit contacts for company
					if($cont_id !=''){
						$insert_sql_array[contact_id] = $cont_id;					
					}
					else{
						$insert_sql_array[contact_id] = $this->contact_id;		
					}
					$insert_sql_array[number] 	= str_replace($symbols,'',$this->number[$count][number]);
					$insert_sql_array[type] 	= $this->number[$count+1][location];
					$this->db->insert(CONTACT_PHONE,$insert_sql_array);
					}
					$count+=2;
					}		
					break;
		default : //empty iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii action.
		
		}
	}
	
	//*************Edit Contact Email*******************************//
	function EditContactEmail($runat,$cont_id='')
	{	
		
		switch($runat){
		
		case 'local' :
		          if($cont_id !=''){
				  	$sql="Select * from ".CONTACT_EMAIL." where contact_id='$cont_id'";
				  }
				  else{
					$sql="Select * from ".CONTACT_EMAIL." where contact_id='$this->contact_id'";
				  }
				  $result=$this->db->query($sql,__FILE,__LINE__);
		?>
		<tr>
		<th><h2>Email</h2></th>
		  <td><div class="contact_forms email_addresses" id="email_address_list_person">
			<?php if($this->db->num_rows($result)==0){ ?>
			<div id="blank_slate_person_email_address" class="blank_slate" style="">Add an email address</div>
			<div style="display: none;"  class="contact_methods">
			<?php }else { ?>
			<div id="blank_slate_person_email_address" class="blank_slate" style="">Add an email address</div>
			<div style="" class="contact_methods">
			<?php }
			
			$row=$this->db->fetch_array($result);
			?>
			
			 <div id="email_address_x04aaa499006d854b636885" class="contact_method 
			new_contact_method">
				<input class="autofocus" id="person_contact_data_email_addresses__address" 
			name="person[contact_data][email_addresses][][address]" size="30" type="text" value="<?php echo $row[email]?>">
				
				<select id="person_contact_data_email_addresses__location" 
			name="person[contact_data][email_addresses][][location]">
							  <option value="Work" <?php if($row[type]=='Work') echo ' selected="selected"'; ?>>Work</option>
							  <option value="Home" <?php if($row[type]=='Home') echo ' selected="selected"'; ?>>Home</option>
							  <option value="Other" <?php if($row[type]=='Other') echo ' selected="selected"'; ?>>Other</option>
				</select>
				<span class="addremove"><a href="#" class="remove">Remove</a></span>
			  </div>
			
			
			<?php while($row=$this->db->fetch_array($result)) { ?>
			  <div id="email_address_x04aaa499006d854b636885" class="contact_method 
			new_contact_method">
				<input class="autofocus" id="person_contact_data_email_addresses__address" 
			name="person[contact_data][email_addresses][][address]" size="30" type="text" value="<?php echo $row[email]?>">
				
				<select id="person_contact_data_email_addresses__location" 
			name="person[contact_data][email_addresses][][location]">
							  <option value="Work" <?php if($row[type]=='Work') echo ' selected="selected"'; ?>>Work</option>
							  <option value="Home" <?php if($row[type]=='Home') echo ' selected="selected"'; ?>>Home</option>
							  <option value="Other" <?php if($row[type]=='Other') echo ' selected="selected"'; ?>>Other</option>
				</select>
				<span class="addremove"><a href="#" class="remove">Remove</a></span>
			  </div>
			<?php	} ?>
		
			<?php if($this->db->num_rows($result)==0){ ?>
			<div id="add_person_email_address" class="add_contact_method" 
			style="display: none;">
			<? } else { ?>
			<div id="add_person_email_address" class="add_contact_method" style="">
			<?php } ?>
			<div class="add"><a href="#">Add another</a></div>
			</div>
			</div></div><script type="text/javascript">
			//<![CDATA[
			new ContactInfo.ContactForm("email_address_list_person", "\n\n  \u003Cdiv id=\"email_address_#{safe_id}\" class=\"contact_method new_contact_method\"\u003E\n    \u003Cinput class=\"autofocus\" id=\"person_contact_data_email_addresses__address\" name=\"person[contact_data][email_addresses][][address]\" size=\"30\" type=\"text\" /\u003E\n    \n    \u003Cselect id=\"person_contact_data_email_addresses__location\" name=\"person[contact_data][email_addresses][][location]\"\u003E\u003Coption value=\"Work\"\u003EWork\u003C/option\u003E\n\u003Coption value=\"Home\"\u003EHome\u003C/option\u003E\n\u003Coption value=\"Other\"\u003EOther\u003C/option\u003E\u003C/select\u003E\n    \u003Cspan class=\"addremove\"\u003E\u003Ca href=\"#\" class=\"remove\"\u003ERemove\u003C/a\u003E\u003C/span\u003E\n  \u003C/div\u003E\n\n")
			//]]>
			</script></td>
</tr>
			<?php
		break;
		
		case 'server' : 
					
					//*********Edit Email Address(contacts_email)****************//
					
					//first write sql to delete all the phone no. of this contact .
					if($cont_id !=''){
						$sql="delete from ".CONTACT_EMAIL." where contact_id='$cont_id'";
					}
					else{
						$sql="delete from ".CONTACT_EMAIL." where contact_id='$this->contact_id'";
					}
					$this->db->query($sql,__FILE__,__LINE__);
					
					extract($_POST[person]);
					$this->email			=$contact_data[email_addresses];
					//then insert these phone no. as fresh
					$count=0;
					for(;$count<count($this->email);){
					if($this->email[$count][address]!='') {
					$insert_sql_array = array();
					
					// condition for edit contacts for company
					if($cont_id !=''){
						$insert_sql_array[contact_id] = $cont_id;					
					}
					else{
						$insert_sql_array[contact_id] = $this->contact_id;		
					}
					$insert_sql_array[email]	= $this->email[$count][address];
					$insert_sql_array[type]		= $this->email[$count+1][location];

					$this->db->insert(CONTACT_EMAIL,$insert_sql_array);
					}
					$count+=2;
					}	
			break;
		
		default : // empty runat Action
		}	
	}
					
					
	//*************Edit Contact IM*******************************//
	function EditContactIm($runat)
	{	

		switch($runat){
		
		case 'local' :
					$sql="Select * from ".CONTACT_IM." where contact_id='$this->contact_id'";
					$result=$this->db->query($sql,__FILE,__LINE__);
		?>
		<tr>
			  <th><h2>IM</h2></th>
			  <td><div class="contact_forms instant_messengers" id="instant_messenger_list_person">
			<?php if($this->db->num_rows($result)==0){ ?>
			<div id="blank_slate_person_instant_messenger" class="blank_slate" style="">Add an instant message account</div>
			 <div style="display: none;" class="contact_methods">
			<?php }else { ?>
			<div id="blank_slate_person_instant_messenger" class="blank_slate" style="">Add an instant message account</div>
			 <div style="" class="contact_methods">
			<?php 
			} 
			
			$row=$this->db->fetch_array($result);
			?>
			
			
			 <div id="instant_messenger_xc24ff66a006d854b636885" class="contact_method new_contact_method">
				<div class="instant_messenger">
				  <input class="autofocus" 
			id="person_contact_data_instant_messengers__address" 
			name="person[contact_data][instant_messengers][][address]" size="30" 
			type="text" value="<?php echo $row['im']?>">
			
			<select class="protocols" id="person_contact_data_instant_messengers__protocol" 
			name="person[contact_data][instant_messengers][][protocol]">
			<option <?php if($row['im_network']=='AIM') echo ' selected="selected"'; ?> value="AIM">AIM</option>
			<option <?php if($row['im_network']=='MSN') echo ' selected="selected"'; ?> value="MSN">MSN</option>
			<option <?php if($row['im_network']=='ICQ') echo ' selected="selected"'; ?> value="ICQ">ICQ</option>
			<option <?php if($row['im_network']=='Jabber') echo ' selected="selected"'; ?> value="Jabber">Jabber</option>
			<option <?php if($row['im_network']=='Jabber') echo ' selected="selected"'; ?> value="Jabber">Yahoo</option>
			<option <?php if($row['im_network']=='Skype') echo ' selected="selected"'; ?> value="Skype">Skype</option>
			<option <?php if($row['im_network']=='QQ') echo ' selected="selected"'; ?> value="QQ">QQ</option>
			<option <?php if($row['im_network']=='Sametime') echo ' selected="selected"'; ?> value="Sametime">Sametime</option>
			<option <?php if($row['im_network']=='Gadu-Gadu') echo ' selected="selected"'; ?> value="Gadu-Gadu">Gadu-Gadu</option>
			<option <?php if($row['im_network']=='Google Talk') echo ' selected="selected"'; ?> value="Google Talk">Google Talk</option>
			<option <?php if($row['im_network']=='Other') echo ' selected="selected"'; ?> value="Other">Other</option>
			</select>
				  
				  <select id="person_contact_data_instant_messengers__location" 
			name="person[contact_data][instant_messengers][][location]">							  
			<option value="Work" <?php if($row['type']=='Work') echo ' selected="selected"'; ?>>Work</option>
		  <option value="Personal" <?php if($row['type']=='Personal') echo ' selected="selected"'; ?>>Personal</option>
		  <option value="Other" <?php if($row['type']=='Other') echo ' selected="selected"'; ?>>Other</option>
		  </select>

				  <span class="addremove"><a href="#" class="remove">Remove</a></span>
				</div>
			  </div>
			
			<?php while($row=$this->db->fetch_array($result)) { ?>
		
			 <div id="instant_messenger_xc24ff66a006d854b636885" class="contact_method new_contact_method">
				<div class="instant_messenger">
				  <input class="autofocus" 
			id="person_contact_data_instant_messengers__address" 
			name="person[contact_data][instant_messengers][][address]" size="30" 
			type="text" value="<?php echo $row['im']?>">
			
			<select class="protocols" id="person_contact_data_instant_messengers__protocol" 
			name="person[contact_data][instant_messengers][][protocol]">
			<option <?php if($row['im_network']=='AIM') echo ' selected="selected"'; ?> value="AIM">AIM</option>
			<option <?php if($row['im_network']=='MSN') echo ' selected="selected"'; ?> value="MSN">MSN</option>
			<option <?php if($row['im_network']=='ICQ') echo ' selected="selected"'; ?> value="ICQ">ICQ</option>
			<option <?php if($row['im_network']=='Jabber') echo ' selected="selected"'; ?> value="Jabber">Jabber</option>
			<option <?php if($row['im_network']=='Jabber') echo ' selected="selected"'; ?> value="Jabber">Yahoo</option>
			<option <?php if($row['im_network']=='Skype') echo ' selected="selected"'; ?> value="Skype">Skype</option>
			<option <?php if($row['im_network']=='QQ') echo ' selected="selected"'; ?> value="QQ">QQ</option>
			<option <?php if($row['im_network']=='Sametime') echo ' selected="selected"'; ?> value="Sametime">Sametime</option>
			<option <?php if($row['im_network']=='Gadu-Gadu') echo ' selected="selected"'; ?> value="Gadu-Gadu">Gadu-Gadu</option>
			<option <?php if($row['im_network']=='Google Talk') echo ' selected="selected"'; ?> value="Google Talk">Google Talk</option>
			<option <?php if($row['im_network']=='Other') echo ' selected="selected"'; ?> value="Other">Other</option>
			</select>
				  
				  <select id="person_contact_data_instant_messengers__location" 
			name="person[contact_data][instant_messengers][][location]">							  
			<option value="Work" <?php if($row['type']=='Work') echo ' selected="selected"'; ?>>Work</option>
		  <option value="Personal" <?php if($row['type']=='Personal') echo ' selected="selected"'; ?>>Personal</option>
		  <option value="Other" <?php if($row['type']=='Other') echo ' selected="selected"'; ?>>Other</option>
		  </select>

				  <span class="addremove"><a href="#" class="remove">Remove</a></span>
				</div>
			  </div>
			<?php } ?>
			
			<?php if($this->db->num_rows($result)==0){ ?>
			<div id="add_person_instant_messenger" class="add_contact_method" 
			style="display: none;">
			<?php } else { ?>
			<div id="add_person_instant_messenger" class="add_contact_method" 
			style="display: none;">
			<?php } ?>
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
					//*********Edit IM Accounts(contacts_im)****************//
					
					//first write sql to delete all the phone no. of this contact .
					$sql="delete from ".CONTACT_IM." where contact_id='$this->contact_id'";
					$this->db->query($sql,__FILE__,__LINE__);
					
					extract($_POST[person]);
					$this->im				=$contact_data[instant_messengers];
				
					//*********Insert IM Ids(contacts_im)****************//
					$count=0;
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
	
	
		//*************Edit Contact Website*******************************//
	function EditContactWebsite($runat)
	{	
		switch($runat){
		
		case 'local' :
					$sql="Select * from ".CONTACT_WEBSITE." where contact_id='$this->contact_id'";
					$result=$this->db->query($sql,__FILE,__LINE__);
		?>
			<tr>
			  <th><h2>Websites</h2></th>
			  <td><div class="contact_forms web_addresses" 
			id="web_address_list_person">
			<?php if($this->db->num_rows($result)==0){ ?>
			<div id="blank_slate_person_web_address" class="blank_slate" style="">Add a website address</div>
			<div style="display: none;" class="contact_methods">
			<?php }else { ?>
			<div id="blank_slate_person_web_address" 
			class="blank_slate" style="">Add a website address</div><div 
			style="" class="contact_methods">			
			<?php } 
			
			$row=$this->db->fetch_array($result);
			?>
			
			
			  <div id="web_address_xbc8d699c006d854b636885" class="contact_method 
			new_contact_method">
				<input class="autofocus" id="person_contact_data_web_addresses__url"
			 name="person[contact_data][web_addresses][][url]" size="30" type="text" value="<?php echo $row[website]?>">
				
				<select id="person_contact_data_web_addresses__location" 
			name="person[contact_data][web_addresses][][location]">							  
			<option value="Work" <?php if($row[type]=='Work') echo ' selected="selected"'; ?>>Work</option>
			 <option value="Personal" <?php if($row[type]=='personal') echo ' selected="selected"'; ?>>Personal</option>
			 <option value="Other" <?php if($row[type]=='Other') echo ' selected="selected"'; ?>>Other</option>
				</select>
				<span class="addremove"><a href="#" class="remove">Remove</a></span>
			  </div>
			
			<?php while($row=$this->db->fetch_array($result)) { ?>
			  <div id="web_address_xbc8d699c006d854b636885" class="contact_method 
			new_contact_method">
				<input class="autofocus" id="person_contact_data_web_addresses__url"
			 name="person[contact_data][web_addresses][][url]" size="30" type="text" value="<?php echo $row[website]?>">
				
				<select id="person_contact_data_web_addresses__location" 
			name="person[contact_data][web_addresses][][location]">							  
			<option value="Work" <?php if($row[type]=='Work') echo ' selected="selected"'; ?>>Work</option>
			 <option value="Personal" <?php if($row[type]=='personal') echo ' selected="selected"'; ?>>Personal</option>
			 <option value="Other" <?php if($row[type]=='Other') echo ' selected="selected"'; ?>>Other</option>
				</select>
				<span class="addremove"><a href="#" class="remove">Remove</a></span>
			  </div>
			<?php } ?>
			<?php if($this->db->num_rows($result)==0){ ?>
			<div id="add_person_web_address" class="add_contact_method" 
			style="display: none;">
			<?php } else {?>
			<div id="add_person_web_address" class="add_contact_method" 
			style="">
			<?php } ?>
			  <div class="add"><a href="#">Add another</a></div>
			</div>
			</div></div><script type="text/javascript">
			//<![CDATA[
			new ContactInfo.ContactForm("web_address_list_person", "\n\n  \u003Cdiv id=\"web_address_#{safe_id}\" class=\"contact_method new_contact_method\"\u003E\n    \u003Cinput class=\"autofocus\" id=\"person_contact_data_web_addresses__url\" name=\"person[contact_data][web_addresses][][url]\" size=\"30\" type=\"text\" value=\"\" /\u003E\n    \n    \u003Cselect id=\"person_contact_data_web_addresses__location\" name=\"person[contact_data][web_addresses][][location]\"\u003E\u003Coption value=\"Work\"\u003EWork\u003C/option\u003E\n\u003Coption value=\"Personal\"\u003EPersonal\u003C/option\u003E\n\u003Coption value=\"Other\"\u003EOther\u003C/option\u003E\u003C/select\u003E\n    \u003Cspan class=\"addremove\"\u003E\u003Ca href=\"#\" class=\"remove\"\u003ERemove\u003C/a\u003E\u003C/span\u003E\n  \u003C/div\u003E\n\n")
			//]]>
			</script></td>
			</tr>		<?php
		break;
		case 'server' :

					$sql="delete from ".CONTACT_WEBSITE." where contact_id='$this->contact_id'";
					$this->db->query($sql,__FILE__,__LINE__);
					
					extract($_POST[person]);
					$this->website 			=$contact_data[web_addresses];

					$count=0;
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
					

				
	//*************Edit Contact Twitter*******************************//
	function EditContactTwitter($runat)
	{	
		switch($runat){
		
		case 'local' :
					$sql="Select * from ".CONTACT_TWITTER." where contact_id='$this->contact_id'";
					$result=$this->db->query($sql,__FILE,__LINE__);
		?>
		
		<tr>
			  <th><h2>Twitter</h2></th>
			  <td><div class="contact_forms twitter_accounts" 
			id="twitter_account_list_person">
			
			<?php if($this->db->num_rows($result)==0){ ?>
				<div id="blank_slate_person_twitter_account" class="blank_slate" style="">Add
			 a twitter account</div><div style="display: none;" 
			class="contact_methods">
			<?php }else { ?>
				<div id="blank_slate_person_twitter_account" class="blank_slate" style="">Add
			 a twitter account</div><div style="" class="contact_methods">
			<?php }
			
			$row=$this->db->fetch_array($result);
			 ?>
			
			<div id="twitter_account_x8d276c2c006d854b636885" 
			class="contact_method new_contact_method">
				<input class="autofocus" 
			id="person_contact_data_twitter_accounts__username" 
			name="person[contact_data][twitter_accounts][][username]" size="30" 
			type="text" value="<?php echo $row[twitter];?>">
				
				<select id="person_contact_data_twitter_accounts__location" 
			name="person[contact_data][twitter_accounts][][location]">
							  <option value="Business" <?php if($row[type]=='Business') echo ' selected="selected"'; ?>>Business</option>
							  <option value="Personal" <?php if($row[type]=='Personal') echo ' selected="selected"'; ?>>Personal</option>
							  <option value="Other" <?php if($row[type]=='Other') echo ' selected="selected"'; ?>>Other</option>
			</select>
			<span class="addremove"><a href="#" class="remove">Remove</a></span>
			  </div>
			
			
			<?php while($row=$this->db->fetch_array($result)) { ?>
			  <div id="twitter_account_x8d276c2c006d854b636885" 
			class="contact_method new_contact_method">
				<input class="autofocus" 
			id="person_contact_data_twitter_accounts__username" 
			name="person[contact_data][twitter_accounts][][username]" size="30" 
			type="text" value="<?php echo $row[twitter];?>">
				
				<select id="person_contact_data_twitter_accounts__location" 
			name="person[contact_data][twitter_accounts][][location]">
							  <option value="Business" <?php if($row[type]=='Business') echo ' selected="selected"'; ?>>Business</option>
							  <option value="Personal" <?php if($row[type]=='Personal') echo ' selected="selected"'; ?>>Personal</option>
							  <option value="Other" <?php if($row[type]=='Other') echo ' selected="selected"'; ?>>Other</option>
			</select>
			<span class="addremove"><a href="#" class="remove">Remove</a></span>
			  </div>
			<? } ?>  
			<?php if($this->db->num_rows($result)==0){ ?>
			<div id="add_person_twitter_account" class="add_contact_method" 
			style="display: none;">
			<? } else { ?>
			<div id="add_person_twitter_account" class="add_contact_method" 
			style="">
			<?php } ?>
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
					//*********Edit Twitter(contacts_twitter)****************//
					
					//first write sql to delete all the phone no. of this contact .
					$sql="delete from ".CONTACT_TWITTER." where contact_id='$this->contact_id'";
					$this->db->query($sql,__FILE__,__LINE__);
					
					extract($_POST[person]);
					$this->twitter_id		=$contact_data[twitter_accounts];
			
					//*********Insert Twitter Ids(contacts_twitter)****************//
					$count=0;
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
	
	
	//*************Edit Contact Address*******************************//
	function EditContactAddress($runat)
	{	
		switch($runat){
		
		case 'local' :
					$sql="Select * from ".CONTACT_ADDRESS." where contact_id='$this->contact_id'";
					$result=$this->db->query($sql,__FILE,__LINE__);	
					$num_rows=$this->db->num_rows($result);
					if(!$num_rows) {   $this->AddContactAddress($runat); break;   }
		?>
			  
			<tr class="addresses">
			  <th><h2>Address</h2></th>
			  <td>
			  <div class="contact_forms addresses" id="address_list_person">
			<?php if($this->db->num_rows($result)==0){ ?>
				  <div id="blank_slate_person_address" class="blank_slate" style="display:none;">Add an address</div>
			<?php }else { ?>
				  <div id="blank_slate_person_address" class="blank_slate" style="display:none;">Add an address</div>
			<?php }

			
			?>
			<div  class="contact_methods">
			<div class="contact_method new_contact_method street" id="address_xf6e911b9006d854b636885">
			  <div class="fields">
			  <?php while($row=$this->db->fetch_array($result)){ ?>
				<div style="position: relative;">
				
				  <p class="address">
				  <textarea	 class="autofocus overlayable" id="person_contact_data_addresses_address_xf6e911b9006d854b636885_street"
			 name="person[contact_data][addresses][][street]" title="Address"><?php echo $row[street_address]?></textarea>
				</p>
				  <p><input	 class="city overlayable" 	id="person_contact_data_addresses_address_xf6e911b9006d854b636885_city" 
			name="person[contact_data][addresses][][city]" title="City" type="text" value="<?php echo $row[city]?>" >
				 
				 <!--<input	 class="state overlayable" 
			id="person_contact_data_addresses_address_xf6e911b9006d854b636885_state"
			 name="person[contact_data][addresses][][state]" title="State" 
			type="text" value="<?php echo $row[state]?>" >-->
			<select 	 class="state overlayable" 
			id="person_contact_data_addresses_address_xf6e911b9006d854b636885_state"
			 name="person[contact_data][addresses][][state]" style="width:auto" >
			<option value="">Select State</option>
			<?php
				$state=file("state_us.inc");
				foreach($state as $val){
				$state = trim($val);
			?>
			<option <?php if($row['state']==$state){echo 'selected="selected"';}?> value="<?php echo $state;?>"><?php echo $state;?></option>
			<?php
				}
			?>
			</select>
			
				<input	 class="zip overlayable" 
			id="person_contact_data_addresses_address_xf6e911b9006d854b636885_zip" 
			name="person[contact_data][addresses][][zip]" title="Zip" type="text" value="<?php echo $row[zip]?>" >
			 </p>
				  <p><select class="country" 
			id="person_contact_data_addresses__country" 
			name="person[contact_data][addresses][][country]">
				<option selected="selected" value="">Choose a country...</option>
			<?php 
				$sql="select * from ".TBL_COUNTRIES;
				$record_t=$this->db->query($sql,__FILE__,__LINE__);
				while($rows=$this->db->fetch_array($record_t))
				{			
			?>
			<option value="<?php echo $rows[value]; ?>" <?php if($rows[value]==$row[country]) echo 'selected="selected"'; ?> >
			<?php echo $rows[value]; ?></option>
			<?php } ?>
		</select></p>
				
			<div class="loc_remove">
			<select id="person_contact_data_addresses__location" 
			name="person[contact_data][addresses][][location]"><option 
			selected="selected" value="Work">Work</option>
			<option value="Home">Home</option>
			<option value="Other">Other</option></select>
					<span class="addremove"><a href="#" class="remove">Remove</a></span>
				  </div>
				</div>
				<?php } ?>
			  </div>
			</div>

			
			
			
			<?php if($this->db->num_rows($result)==0){ ?>
			<div id="add_person_address" class="add_contact_method" style="display: 
			none;">
			<?php } else { ?>
			<div id="add_person_address" class="add_contact_method" style="">
			<?php } ?>
			  <div class="add"><a href="#">Add another</a></div>
			</div>
			</div></div><script type="text/javascript">
			//<![CDATA[
			new ContactInfo.ContactForm("address_list_person", "\n\n\u003Cdiv class=\"contact_method new_contact_method street\" id=\"address_#{safe_id}\"\u003E\n  \u003Cdiv class=\"fields\"\u003E\n    \u003Cdiv style=\"position: relative;\"\u003E\n      \u003Cp class=\"address\"\u003E\u003Ctextarea class=\"autofocus overlayable\" id=\"person_contact_data_addresses_address_#{safe_id}_street\" name=\"person[contact_data][addresses][][street]\" title=\"Address\"\u003E\u003C/textarea\u003E\u003C/p\u003E\n      \u003Cp\u003E\u003Cinput class=\"city overlayable\" id=\"person_contact_data_addresses_address_#{safe_id}_city\" name=\"person[contact_data][addresses][][city]\" title=\"City\" type=\"text\" /\u003E\n   \u003Cselect class=\"state overlayable\" id=\"person_contact_data_addresses_address_xf6e911b9006d854b636885_state\" name=\"person[contact_data][addresses][][state]\" style=\"width:auto\" \u003E \u003Coption selected=\"selected\" value=\"\"\u003ESelect State\u003C/option\u003E \u003Coption  value=\"Alaska\"\u003EAlaska\u003C/option\u003E \u003Coption  value=\"Alabama\"\u003EAlabama\u003C/option\u003E \u003Coption  value=\"Arkansas\"\u003EArkansas\u003C/option\u003E \u003Coption  value=\"Arizona\"\u003EArizona\u003C/option\u003E \u003Coption  value=\"California\"\u003ECalifornia\u003C/option\u003E \u003Coption  value=\"Colorado\"\u003EColorado\u003C/option\u003E \u003Coption  value=\"Connecticut\"\u003EConnecticut\u003C/option\u003E \u003Coption  value=\"D.C.\"\u003ED.C.\u003C/option\u003E \u003Coption  value=\"Delaware\"\u003EDelaware\u003C/option\u003E \u003Coption  value=\"Federated States of Micronesia\"\u003EFederated States of Micronesia\u003C/option\u003E \u003Coption  value=\"Florida\"\u003EFlorida\u003C/option\u003E \u003Coption  value=\"Georgia\"\u003EGeorgia\u003C/option\u003E \u003Coption  value=\"Hawaii\"\u003EHawaii\u003C/option\u003E \u003Coption  value=\"Iowa\"\u003EIowa\u003C/option\u003E \u003Coption  value=\"Idaho\"\u003EIdaho\u003C/option\u003E \u003Coption  value=\"Illinois\"\u003EIllinois\u003C/option\u003E \u003Coption  value=\"Indiana\"\u003EIndiana\u003C/option\u003E\u003Coption  value=\"Kansas\"\u003EKansas\u003C/option\u003E \u003Coption  value=\"Kentucky\"\u003EKentucky\u003C/option\u003E \u003Coption  value=\"Louisiana\"\u003ELouisiana\u003C/option\u003E \u003Coption  value=\"Massachusetts\"\u003EMassachusetts\u003C/option\u003E \u003Coption  value=\"Maryland\"\u003EMaryland\u003C/option\u003E \u003Coption  value=\"Marshall Islands\"\u003EMarshall Islands\u003C/option\u003E \u003Coption  value=\"Maine\"\u003EMaine\u003C/option\u003E  \u003Coption  value=\"Michigan\"\u003EMichigan\u003C/option\u003E \u003Coption  value=\"Minnesota\"\u003EMinnesota\u003C/option\u003E \u003Coption  value=\"Missouri\"\u003EMissouri\u003C/option\u003E \u003Coption  value=\"Mississippi\"\u003EMississippi\u003C/option\u003E \u003Coption  value=\"Montana\"\u003EMontana\u003C/option\u003E \u003Coption  value=\"North Carolina\"\u003ENorth Carolina\u003C/option\u003E \u003Coption  value=\"North Dakota\"\u003ENorth Dakota\u003C/option\u003E \u003Coption  value=\"Nebraska\"\u003ENebraska\u003C/option\u003E \u003Coption  value=\"New Hampshire\"\u003ENew Hampshire\u003C/option\u003E \u003Coption  value=\"New Jersey\"\u003ENew Jersey\u003C/option\u003E \u003Coption  value=\"New Mexico\"\u003ENew Mexico\u003C/option\u003E \u003Coption  value=\"Nevada\"\u003ENevada\u003C/option\u003E \u003Coption  value=\"New York\"\u003ENew York\u003C/option\u003E \u003Coption  value=\"Northern Mariana Islands\"\u003ENorthern Mariana Islands\u003C/option\u003E \u003Coption  value=\"Ohio\"\u003EOhio\u003C/option\u003E \u003Coption  value=\"Oklahoma\"\u003EOklahoma\u003C/option\u003E \u003Coption  value=\"Oregon\"\u003EOregon\u003C/option\u003E \u003Coption  value=\"Palau\"\u003EPalau\u003C/option\u003E \u003Coption  value=\"Puerto Rico\"\u003EPuerto Rico\u003C/option\u003E \u003Coption  value=\"Pennsylvania\"\u003EPennsylvania\u003C/option\u003E \u003Coption  value=\"Rhode Island\"\u003ERhode Island\u003C/option\u003E \u003Coption  value=\"South Carolina\"\u003ESouth Carolina\u003C/option\u003E  \u003Coption  value=\"South Dakota\"\u003ESouth Dakota\u003C/option\u003E \u003Coption  value=\"Tennessee\"\u003ETennessee\u003C/option\u003E \u003Coption  value=\"Texas\"\u003ETexas\u003C/option\u003E \u003Coption  value=\"Utah\"\u003EUtah\u003C/option\u003E \u003Coption  value=\"Virgin Islands\"\u003EVirgin Islands\u003C/option\u003E \u003Coption  value=\"Virginia\"\u003EVirginia\u003C/option\u003E \u003Coption  value=\"Vermont\"\u003EVermont\u003C/option\u003E \u003Coption  value=\"Washington\"\u003EWashington\u003C/option\u003E \u003Coption  value=\"Wisconsin\"\u003EWisconsin\u003C/option\u003E \u003Coption  value=\"West Virginia\"\u003EWest Virginia\u003C/option\u003E \u003Coption  value=\"Wyoming\"\u003EWyoming\u003C/option\u003E \u003C/select\u003E      \u003Cinput class=\"zip overlayable\" id=\"person_contact_data_addresses_address_#{safe_id}_zip\" name=\"person[contact_data][addresses][][zip]\" title=\"Zip\" type=\"text\" /\u003E \u003C/p\u003E\n      \u003Cp\u003E\u003Cselect class=\"country\" id=\"person_contact_data_addresses__country\" name=\"person[contact_data][addresses][][country]\"\u003E\u003Coption value=\"\"\u003EChoose a country...\u003C/option\u003E\n\u003Coption value=\"United States\"\u003EUnited States\u003C/option\u003E\n\u003Coption value=\"Canada\"\u003ECanada\u003C/option\u003E\n\u003Coption value=\"Denmark\"\u003EDenmark\u003C/option\u003E\n\u003Coption value=\"France\"\u003EFrance\u003C/option\u003E\n\u003Coption value=\"United Kingdom\"\u003EUnited Kingdom\u003C/option\u003E\n\u003Coption value=\"Australia\"\u003EAustralia\u003C/option\u003E\n\u003Coption value=\"Italy\"\u003EItaly\u003C/option\u003E\n\u003Coption value=\"Japan\"\u003EJapan\u003C/option\u003E\n\u003Coption value=\"Mexico\"\u003EMexico\u003C/option\u003E\n\u003Coption value=\"Spain\"\u003ESpain\u003C/option\u003E\n\u003Coption value=\"Sweden\"\u003ESweden\u003C/option\u003E\u003Coption value=\"\" disabled=\"disabled\"\u003E-------------\u003C/option\u003E\n\u003Coption value=\"Afghanistan\"\u003EAfghanistan\u003C/option\u003E\n\u003Coption value=\"\u00c5land Islands\"\u003E\u00c5land Islands\u003C/option\u003E\n\u003Coption value=\"Albania\"\u003EAlbania\u003C/option\u003E\n\u003Coption value=\"Algeria\"\u003EAlgeria\u003C/option\u003E\n\u003Coption value=\"American Samoa\"\u003EAmerican Samoa\u003C/option\u003E\n\u003Coption value=\"Andorra\"\u003EAndorra\u003C/option\u003E\n\u003Coption value=\"Angola\"\u003EAngola\u003C/option\u003E\n\u003Coption value=\"Anguilla\"\u003EAnguilla\u003C/option\u003E\n\u003Coption value=\"Antarctica\"\u003EAntarctica\u003C/option\u003E\n\u003Coption value=\"Antigua and Barbuda\"\u003EAntigua and Barbuda\u003C/option\u003E\n\u003Coption value=\"Argentina\"\u003EArgentina\u003C/option\u003E\n\u003Coption value=\"Armenia\"\u003EArmenia\u003C/option\u003E\n\u003Coption value=\"Aruba\"\u003EAruba\u003C/option\u003E\n\u003Coption value=\"Australia\"\u003EAustralia\u003C/option\u003E\n\u003Coption value=\"Austria\"\u003EAustria\u003C/option\u003E\n\u003Coption value=\"Azerbaijan\"\u003EAzerbaijan\u003C/option\u003E\n\u003Coption value=\"Bahamas\"\u003EBahamas\u003C/option\u003E\n\u003Coption value=\"Bahrain\"\u003EBahrain\u003C/option\u003E\n\u003Coption value=\"Bangladesh\"\u003EBangladesh\u003C/option\u003E\n\u003Coption value=\"Barbados\"\u003EBarbados\u003C/option\u003E\n\u003Coption value=\"Belarus\"\u003EBelarus\u003C/option\u003E\n\u003Coption value=\"Belgium\"\u003EBelgium\u003C/option\u003E\n\u003Coption value=\"Belize\"\u003EBelize\u003C/option\u003E\n\u003Coption value=\"Benin\"\u003EBenin\u003C/option\u003E\n\u003Coption value=\"Bermuda\"\u003EBermuda\u003C/option\u003E\n\u003Coption value=\"Bhutan\"\u003EBhutan\u003C/option\u003E\n\u003Coption value=\"Bolivia\"\u003EBolivia\u003C/option\u003E\n\u003Coption value=\"Bosnia and Herzegovina\"\u003EBosnia and Herzegovina\u003C/option\u003E\n\u003Coption value=\"Botswana\"\u003EBotswana\u003C/option\u003E\n\u003Coption value=\"Bouvet Island\"\u003EBouvet Island\u003C/option\u003E\n\u003Coption value=\"Brazil\"\u003EBrazil\u003C/option\u003E\n\u003Coption value=\"British Indian Ocean Territory\"\u003EBritish Indian Ocean Territory\u003C/option\u003E\n\u003Coption value=\"Brunei Darussalam\"\u003EBrunei Darussalam\u003C/option\u003E\n\u003Coption value=\"Bulgaria\"\u003EBulgaria\u003C/option\u003E\n\u003Coption value=\"Burkina Faso\"\u003EBurkina Faso\u003C/option\u003E\n\u003Coption value=\"Burundi\"\u003EBurundi\u003C/option\u003E\n\u003Coption value=\"Cambodia\"\u003ECambodia\u003C/option\u003E\n\u003Coption value=\"Cameroon\"\u003ECameroon\u003C/option\u003E\n\u003Coption value=\"Canada\"\u003ECanada\u003C/option\u003E\n\u003Coption value=\"Cape Verde\"\u003ECape Verde\u003C/option\u003E\n\u003Coption value=\"Cayman Islands\"\u003ECayman Islands\u003C/option\u003E\n\u003Coption value=\"Central African Republic\"\u003ECentral African Republic\u003C/option\u003E\n\u003Coption value=\"Chad\"\u003EChad\u003C/option\u003E\n\u003Coption value=\"Chile\"\u003EChile\u003C/option\u003E\n\u003Coption value=\"China\"\u003EChina\u003C/option\u003E\n\u003Coption value=\"Christmas Island\"\u003EChristmas Island\u003C/option\u003E\n\u003Coption value=\"Cocos (Keeling) Islands\"\u003ECocos (Keeling) Islands\u003C/option\u003E\n\u003Coption value=\"Colombia\"\u003EColombia\u003C/option\u003E\n\u003Coption value=\"Comoros\"\u003EComoros\u003C/option\u003E\n\u003Coption value=\"Congo\"\u003ECongo\u003C/option\u003E\n\u003Coption value=\"Congo, The Democratic Republic of the\"\u003ECongo, The Democratic Republic of the\u003C/option\u003E\n\u003Coption value=\"Cook Islands\"\u003ECook Islands\u003C/option\u003E\n\u003Coption value=\"Costa Rica\"\u003ECosta Rica\u003C/option\u003E\n\u003Coption value=\"C\u00f4te d'Ivoire\"\u003EC\u00f4te d'Ivoire\u003C/option\u003E\n\u003Coption value=\"Croatia\"\u003ECroatia\u003C/option\u003E\n\u003Coption value=\"Cuba\"\u003ECuba\u003C/option\u003E\n\u003Coption value=\"Cyprus\"\u003ECyprus\u003C/option\u003E\n\u003Coption value=\"Czech Republic\"\u003ECzech Republic\u003C/option\u003E\n\u003Coption value=\"Denmark\"\u003EDenmark\u003C/option\u003E\n\u003Coption value=\"Djibouti\"\u003EDjibouti\u003C/option\u003E\n\u003Coption value=\"Dominica\"\u003EDominica\u003C/option\u003E\n\u003Coption value=\"Dominican Republic\"\u003EDominican Republic\u003C/option\u003E\n\u003Coption value=\"Ecuador\"\u003EEcuador\u003C/option\u003E\n\u003Coption value=\"Egypt\"\u003EEgypt\u003C/option\u003E\n\u003Coption value=\"El Salvador\"\u003EEl Salvador\u003C/option\u003E\n\u003Coption value=\"Equatorial Guinea\"\u003EEquatorial Guinea\u003C/option\u003E\n\u003Coption value=\"Eritrea\"\u003EEritrea\u003C/option\u003E\n\u003Coption value=\"Estonia\"\u003EEstonia\u003C/option\u003E\n\u003Coption value=\"Ethiopia\"\u003EEthiopia\u003C/option\u003E\n\u003Coption value=\"Falkland Islands (Malvinas)\"\u003EFalkland Islands (Malvinas)\u003C/option\u003E\n\u003Coption value=\"Faroe Islands\"\u003EFaroe Islands\u003C/option\u003E\n\u003Coption value=\"Fiji\"\u003EFiji\u003C/option\u003E\n\u003Coption value=\"Finland\"\u003EFinland\u003C/option\u003E\n\u003Coption value=\"France\"\u003EFrance\u003C/option\u003E\n\u003Coption value=\"French Guiana\"\u003EFrench Guiana\u003C/option\u003E\n\u003Coption value=\"French Polynesia\"\u003EFrench Polynesia\u003C/option\u003E\n\u003Coption value=\"French Southern Territories\"\u003EFrench Southern Territories\u003C/option\u003E\n\u003Coption value=\"Gabon\"\u003EGabon\u003C/option\u003E\n\u003Coption value=\"Gambia\"\u003EGambia\u003C/option\u003E\n\u003Coption value=\"Georgia\"\u003EGeorgia\u003C/option\u003E\n\u003Coption value=\"Germany\"\u003EGermany\u003C/option\u003E\n\u003Coption value=\"Ghana\"\u003EGhana\u003C/option\u003E\n\u003Coption value=\"Gibraltar\"\u003EGibraltar\u003C/option\u003E\n\u003Coption value=\"Greece\"\u003EGreece\u003C/option\u003E\n\u003Coption value=\"Greenland\"\u003EGreenland\u003C/option\u003E\n\u003Coption value=\"Grenada\"\u003EGrenada\u003C/option\u003E\n\u003Coption value=\"Guadeloupe\"\u003EGuadeloupe\u003C/option\u003E\n\u003Coption value=\"Guam\"\u003EGuam\u003C/option\u003E\n\u003Coption value=\"Guatemala\"\u003EGuatemala\u003C/option\u003E\n\u003Coption value=\"Guernsey\"\u003EGuernsey\u003C/option\u003E\n\u003Coption value=\"Guinea\"\u003EGuinea\u003C/option\u003E\n\u003Coption value=\"Guinea-Bissau\"\u003EGuinea-Bissau\u003C/option\u003E\n\u003Coption value=\"Guyana\"\u003EGuyana\u003C/option\u003E\n\u003Coption value=\"Haiti\"\u003EHaiti\u003C/option\u003E\n\u003Coption value=\"Heard Island and McDonald Islands\"\u003EHeard Island and McDonald Islands\u003C/option\u003E\n\u003Coption value=\"Holy See (Vatican City State)\"\u003EHoly See (Vatican City State)\u003C/option\u003E\n\u003Coption value=\"Honduras\"\u003EHonduras\u003C/option\u003E\n\u003Coption value=\"Hong Kong\"\u003EHong Kong\u003C/option\u003E\n\u003Coption value=\"Hungary\"\u003EHungary\u003C/option\u003E\n\u003Coption value=\"Iceland\"\u003EIceland\u003C/option\u003E\n\u003Coption value=\"India\"\u003EIndia\u003C/option\u003E\n\u003Coption value=\"Indonesia\"\u003EIndonesia\u003C/option\u003E\n\u003Coption value=\"Iran, Islamic Republic of\"\u003EIran, Islamic Republic of\u003C/option\u003E\n\u003Coption value=\"Iraq\"\u003EIraq\u003C/option\u003E\n\u003Coption value=\"Ireland\"\u003EIreland\u003C/option\u003E\n\u003Coption value=\"Isle of Man\"\u003EIsle of Man\u003C/option\u003E\n\u003Coption value=\"Israel\"\u003EIsrael\u003C/option\u003E\n\u003Coption value=\"Italy\"\u003EItaly\u003C/option\u003E\n\u003Coption value=\"Jamaica\"\u003EJamaica\u003C/option\u003E\n\u003Coption value=\"Japan\"\u003EJapan\u003C/option\u003E\n\u003Coption value=\"Jersey\"\u003EJersey\u003C/option\u003E\n\u003Coption value=\"Jordan\"\u003EJordan\u003C/option\u003E\n\u003Coption value=\"Kazakhstan\"\u003EKazakhstan\u003C/option\u003E\n\u003Coption value=\"Kenya\"\u003EKenya\u003C/option\u003E\n\u003Coption value=\"Kiribati\"\u003EKiribati\u003C/option\u003E\n\u003Coption value=\"Korea, Democratic People's Republic of\"\u003EKorea, Democratic People's Republic of\u003C/option\u003E\n\u003Coption value=\"Korea, Republic of\"\u003EKorea, Republic of\u003C/option\u003E\n\u003Coption value=\"Kuwait\"\u003EKuwait\u003C/option\u003E\n\u003Coption value=\"Kyrgyzstan\"\u003EKyrgyzstan\u003C/option\u003E\n\u003Coption value=\"Lao People's Democratic Republic\"\u003ELao People's Democratic Republic\u003C/option\u003E\n\u003Coption value=\"Latvia\"\u003ELatvia\u003C/option\u003E\n\u003Coption value=\"Lebanon\"\u003ELebanon\u003C/option\u003E\n\u003Coption value=\"Lesotho\"\u003ELesotho\u003C/option\u003E\n\u003Coption value=\"Liberia\"\u003ELiberia\u003C/option\u003E\n\u003Coption value=\"Libyan Arab Jamahiriya\"\u003ELibyan Arab Jamahiriya\u003C/option\u003E\n\u003Coption value=\"Liechtenstein\"\u003ELiechtenstein\u003C/option\u003E\n\u003Coption value=\"Lithuania\"\u003ELithuania\u003C/option\u003E\n\u003Coption value=\"Luxembourg\"\u003ELuxembourg\u003C/option\u003E\n\u003Coption value=\"Macao\"\u003EMacao\u003C/option\u003E\n\u003Coption value=\"Macedonia, Republic of\"\u003EMacedonia, Republic of\u003C/option\u003E\n\u003Coption value=\"Madagascar\"\u003EMadagascar\u003C/option\u003E\n\u003Coption value=\"Malawi\"\u003EMalawi\u003C/option\u003E\n\u003Coption value=\"Malaysia\"\u003EMalaysia\u003C/option\u003E\n\u003Coption value=\"Maldives\"\u003EMaldives\u003C/option\u003E\n\u003Coption value=\"Mali\"\u003EMali\u003C/option\u003E\n\u003Coption value=\"Malta\"\u003EMalta\u003C/option\u003E\n\u003Coption value=\"Marshall Islands\"\u003EMarshall Islands\u003C/option\u003E\n\u003Coption value=\"Martinique\"\u003EMartinique\u003C/option\u003E\n\u003Coption value=\"Mauritania\"\u003EMauritania\u003C/option\u003E\n\u003Coption value=\"Mauritius\"\u003EMauritius\u003C/option\u003E\n\u003Coption value=\"Mayotte\"\u003EMayotte\u003C/option\u003E\n\u003Coption value=\"Mexico\"\u003EMexico\u003C/option\u003E\n\u003Coption value=\"Micronesia, Federated States of\"\u003EMicronesia, Federated States of\u003C/option\u003E\n\u003Coption value=\"Moldova\"\u003EMoldova\u003C/option\u003E\n\u003Coption value=\"Monaco\"\u003EMonaco\u003C/option\u003E\n\u003Coption value=\"Mongolia\"\u003EMongolia\u003C/option\u003E\n\u003Coption value=\"Montenegro\"\u003EMontenegro\u003C/option\u003E\n\u003Coption value=\"Montserrat\"\u003EMontserrat\u003C/option\u003E\n\u003Coption value=\"Morocco\"\u003EMorocco\u003C/option\u003E\n\u003Coption value=\"Mozambique\"\u003EMozambique\u003C/option\u003E\n\u003Coption value=\"Myanmar\"\u003EMyanmar\u003C/option\u003E\n\u003Coption value=\"Namibia\"\u003ENamibia\u003C/option\u003E\n\u003Coption value=\"Nauru\"\u003ENauru\u003C/option\u003E\n\u003Coption value=\"Nepal\"\u003ENepal\u003C/option\u003E\n\u003Coption value=\"Netherlands\"\u003ENetherlands\u003C/option\u003E\n\u003Coption value=\"Netherlands Antilles\"\u003ENetherlands Antilles\u003C/option\u003E\n\u003Coption value=\"New Caledonia\"\u003ENew Caledonia\u003C/option\u003E\n\u003Coption value=\"New Zealand\"\u003ENew Zealand\u003C/option\u003E\n\u003Coption value=\"Nicaragua\"\u003ENicaragua\u003C/option\u003E\n\u003Coption value=\"Niger\"\u003ENiger\u003C/option\u003E\n\u003Coption value=\"Nigeria\"\u003ENigeria\u003C/option\u003E\n\u003Coption value=\"Niue\"\u003ENiue\u003C/option\u003E\n\u003Coption value=\"Norfolk Island\"\u003ENorfolk Island\u003C/option\u003E\n\u003Coption value=\"Northern Mariana Islands\"\u003ENorthern Mariana Islands\u003C/option\u003E\n\u003Coption value=\"Norway\"\u003ENorway\u003C/option\u003E\n\u003Coption value=\"Oman\"\u003EOman\u003C/option\u003E\n\u003Coption value=\"Pakistan\"\u003EPakistan\u003C/option\u003E\n\u003Coption value=\"Palau\"\u003EPalau\u003C/option\u003E\n\u003Coption value=\"Palestinian Territory, Occupied\"\u003EPalestinian Territory, Occupied\u003C/option\u003E\n\u003Coption value=\"Panama\"\u003EPanama\u003C/option\u003E\n\u003Coption value=\"Papua New Guinea\"\u003EPapua New Guinea\u003C/option\u003E\n\u003Coption value=\"Paraguay\"\u003EParaguay\u003C/option\u003E\n\u003Coption value=\"Peru\"\u003EPeru\u003C/option\u003E\n\u003Coption value=\"Philippines\"\u003EPhilippines\u003C/option\u003E\n\u003Coption value=\"Pitcairn\"\u003EPitcairn\u003C/option\u003E\n\u003Coption value=\"Poland\"\u003EPoland\u003C/option\u003E\n\u003Coption value=\"Portugal\"\u003EPortugal\u003C/option\u003E\n\u003Coption value=\"Puerto Rico\"\u003EPuerto Rico\u003C/option\u003E\n\u003Coption value=\"Qatar\"\u003EQatar\u003C/option\u003E\n\u003Coption value=\"Reunion\"\u003EReunion\u003C/option\u003E\n\u003Coption value=\"Romania\"\u003ERomania\u003C/option\u003E\n\u003Coption value=\"Russian Federation\"\u003ERussian Federation\u003C/option\u003E\n\u003Coption value=\"Rwanda\"\u003ERwanda\u003C/option\u003E\n\u003Coption value=\"Saint Barth\u00e9lemy\"\u003ESaint Barth\u00e9lemy\u003C/option\u003E\n\u003Coption value=\"Saint Helena\"\u003ESaint Helena\u003C/option\u003E\n\u003Coption value=\"Saint Kitts and Nevis\"\u003ESaint Kitts and Nevis\u003C/option\u003E\n\u003Coption value=\"Saint Lucia\"\u003ESaint Lucia\u003C/option\u003E\n\u003Coption value=\"Saint Martin (French part)\"\u003ESaint Martin (French part)\u003C/option\u003E\n\u003Coption value=\"Saint Pierre and Miquelon\"\u003ESaint Pierre and Miquelon\u003C/option\u003E\n\u003Coption value=\"Saint Vincent and the Grenadines\"\u003ESaint Vincent and the Grenadines\u003C/option\u003E\n\u003Coption value=\"Samoa\"\u003ESamoa\u003C/option\u003E\n\u003Coption value=\"San Marino\"\u003ESan Marino\u003C/option\u003E\n\u003Coption value=\"Sao Tome and Principe\"\u003ESao Tome and Principe\u003C/option\u003E\n\u003Coption value=\"Saudi Arabia\"\u003ESaudi Arabia\u003C/option\u003E\n\u003Coption value=\"Senegal\"\u003ESenegal\u003C/option\u003E\n\u003Coption value=\"Serbia\"\u003ESerbia\u003C/option\u003E\n\u003Coption value=\"Seychelles\"\u003ESeychelles\u003C/option\u003E\n\u003Coption value=\"Sierra Leone\"\u003ESierra Leone\u003C/option\u003E\n\u003Coption value=\"Singapore\"\u003ESingapore\u003C/option\u003E\n\u003Coption value=\"Slovakia\"\u003ESlovakia\u003C/option\u003E\n\u003Coption value=\"Slovenia\"\u003ESlovenia\u003C/option\u003E\n\u003Coption value=\"Solomon Islands\"\u003ESolomon Islands\u003C/option\u003E\n\u003Coption value=\"Somalia\"\u003ESomalia\u003C/option\u003E\n\u003Coption value=\"South Africa\"\u003ESouth Africa\u003C/option\u003E\n\u003Coption value=\"South Georgia and the South Sandwich Islands\"\u003ESouth Georgia and the South Sandwich Islands\u003C/option\u003E\n\u003Coption value=\"Spain\"\u003ESpain\u003C/option\u003E\n\u003Coption value=\"Sri Lanka\"\u003ESri Lanka\u003C/option\u003E\n\u003Coption value=\"Sudan\"\u003ESudan\u003C/option\u003E\n\u003Coption value=\"Suriname\"\u003ESuriname\u003C/option\u003E\n\u003Coption value=\"Svalbard and Jan Mayen\"\u003ESvalbard and Jan Mayen\u003C/option\u003E\n\u003Coption value=\"Swaziland\"\u003ESwaziland\u003C/option\u003E\n\u003Coption value=\"Sweden\"\u003ESweden\u003C/option\u003E\n\u003Coption value=\"Switzerland\"\u003ESwitzerland\u003C/option\u003E\n\u003Coption value=\"Syrian Arab Republic\"\u003ESyrian Arab Republic\u003C/option\u003E\n\u003Coption value=\"Taiwan\"\u003ETaiwan\u003C/option\u003E\n\u003Coption value=\"Tajikistan\"\u003ETajikistan\u003C/option\u003E\n\u003Coption value=\"Tanzania, United Republic of\"\u003ETanzania, United Republic of\u003C/option\u003E\n\u003Coption value=\"Thailand\"\u003EThailand\u003C/option\u003E\n\u003Coption value=\"Timor-Leste\"\u003ETimor-Leste\u003C/option\u003E\n\u003Coption value=\"Togo\"\u003ETogo\u003C/option\u003E\n\u003Coption value=\"Tokelau\"\u003ETokelau\u003C/option\u003E\n\u003Coption value=\"Tonga\"\u003ETonga\u003C/option\u003E\n\u003Coption value=\"Trinidad and Tobago\"\u003ETrinidad and Tobago\u003C/option\u003E\n\u003Coption value=\"Tunisia\"\u003ETunisia\u003C/option\u003E\n\u003Coption value=\"Turkey\"\u003ETurkey\u003C/option\u003E\n\u003Coption value=\"Turkmenistan\"\u003ETurkmenistan\u003C/option\u003E\n\u003Coption value=\"Turks and Caicos Islands\"\u003ETurks and Caicos Islands\u003C/option\u003E\n\u003Coption value=\"Tuvalu\"\u003ETuvalu\u003C/option\u003E\n\u003Coption value=\"Uganda\"\u003EUganda\u003C/option\u003E\n\u003Coption value=\"Ukraine\"\u003EUkraine\u003C/option\u003E\n\u003Coption value=\"United Arab Emirates\"\u003EUnited Arab Emirates\u003C/option\u003E\n\u003Coption value=\"United Kingdom\"\u003EUnited Kingdom\u003C/option\u003E\n\u003Coption value=\"United States\"\u003EUnited States\u003C/option\u003E\n\u003Coption value=\"United States Minor Outlying Islands\"\u003EUnited States Minor Outlying Islands\u003C/option\u003E\n\u003Coption value=\"Uruguay\"\u003EUruguay\u003C/option\u003E\n\u003Coption value=\"Uzbekistan\"\u003EUzbekistan\u003C/option\u003E\n\u003Coption value=\"Vanuatu\"\u003EVanuatu\u003C/option\u003E\n\u003Coption value=\"Venezuela\"\u003EVenezuela\u003C/option\u003E\n\u003Coption value=\"Viet Nam\"\u003EViet Nam\u003C/option\u003E\n\u003Coption value=\"Virgin Islands, British\"\u003EVirgin Islands, British\u003C/option\u003E\n\u003Coption value=\"Virgin Islands, U.S.\"\u003EVirgin Islands, U.S.\u003C/option\u003E\n\u003Coption value=\"Wallis and Futuna\"\u003EWallis and Futuna\u003C/option\u003E\n\u003Coption value=\"Western Sahara\"\u003EWestern Sahara\u003C/option\u003E\n\u003Coption value=\"Yemen\"\u003EYemen\u003C/option\u003E\n\u003Coption value=\"Zambia\"\u003EZambia\u003C/option\u003E\n\u003Coption value=\"Zimbabwe\"\u003EZimbabwe\u003C/option\u003E\u003C/select\u003E\u003C/p\u003E\n      \u003Cdiv class=\"loc_remove\"\u003E\n        \n        \u003Cselect id=\"person_contact_data_addresses__location\" name=\"person[contact_data][addresses][][location]\"\u003E\u003Coption value=\"Work\"\u003EWork\u003C/option\u003E\n\u003Coption value=\"Home\"\u003EHome\u003C/option\u003E\n\u003Coption value=\"Other\"\u003EOther\u003C/option\u003E\u003C/select\u003E\n        \u003Cspan class=\"addremove\"\u003E\u003Ca href=\"#\" class=\"remove\"\u003ERemove\u003C/a\u003E\u003C/span\u003E\n      \u003C/div\u003E\n    \u003C/div\u003E\n  \u003C/div\u003E\n\u003C/div\u003E\n\u003Cscript type=\"text/javascript\"\u003E\n//\u003C![CDATA[\n$$('.street .overlayable').each(function(input) { ContactInfo.Overlay.wrap(input); });\n//]]\u003E\n\u003C/script\u003E\n\n")
			//]]>
			</script></td>
			</tr>

		<?php	
		break;
		case 'server' :
					//*********Edit Address(contacts_address)****************//
					
					//first write sql to delete all the phone no. of this contact .
					$sql="delete from ".CONTACT_ADDRESS." where contact_id='$this->contact_id'";
					$this->db->query($sql,__FILE__,__LINE__);
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


	//*************Display Edit Contact Submit button********************//
	function EditContactSubmitButton($runat)
	{	
		switch($runat){
		
		case 'local' :
					?>
		<tr>
			  <td colspan="2" align="center"><input type="submit" name="save" value="Save" onClick="return <?php echo $this->ValidationFunctionName?>();" style="width:auto;" />&nbsp;or &nbsp;<a href="contacts.php">Cancel</a></td>
		</tr>
					<?php
		break;
		
		case 'server' : ;
		break;
		}			
	}

	//*************Get Contact Type( People/Company )********************//
	function getContactType($contact_id, $edit_head='')
	{ob_start();
		$this->contact_id=$contact_id;
		$sql="Select * from ".TBL_CONTACT." where contact_id='$this->contact_id'";
		$result=$this->db->query($sql,__FILE__,__LINE__);
		$row=$this->db->fetch_array($result);
		if($edit_head=='')
		echo $row[type];
		else {
		
		?>
		<div class="heading">
				<a href="contact_profile.php?contact_id=<?php echo $this->contact_id; ?>" >
		<?php 
				if($row[picture]!='') { ?>
				<img src="thumb.php?file=<?php echo $row[directory].'/'.$row[picture]; ?>&size=70" 
				 alt=""  border="0"  align="absmiddle"/>
				<?php }
				else { ?>
				<img src="thumb.php?file=images/person.gif&size=40" alt="" border="0"  align="absmiddle"/>
				<?php } ?>
				</a>
				 Editing <?php echo $row[type]; ?>
		</div>
		<?php
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;

		
	}
	function GetContactByPhone($phone) {
	//	ob_start();

		$sql="select contact_id from ".CONTACT_PHONE." where number='$phone'";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		$row = $this->db->fetch_array($record);
		//$contacts='';
		return $row[contact_id];
/*		while($row = $this->db->fetch_array($record)){
			$contacts .= $row[contact_id].',';
		}
	$contacts = substr($contacts,0,strlen($contacts)-1);
		return $contacts;

		$html = ob_get_contents();
		ob_end_clean();
		return $html;
*/	
	}
                /* search the database for contacts, returns array of contact info.. Added by Tim Holum
         * Usage is contact_search( "whatever_string" , array( "phone" , "fname" , "lname" ) , "standard"); ....
         *
         * this returns an array with all of the contacts info for example, in the future I will write it so you can change
         * amount to "full" and it will return all numbers, emails ext.. but for now I dont need that so I will not write that
         * array(
         *  "type" => "People",
         *  "name" => "First Last OR company_name",
         *  "first_name" => "First",
         *  "last_name" => "Last" ,
         *  "company_name" => "company name",
         *  )
         */
         function get_contact_phone( $contact_id ){
             $query = "SELECT * FROM contacts_phone WHERE contact_id = '$contact_id' ";
             $res = $this->db->query($query);
             $return = array();
             while( $row=$this->db->fetch_assoc($res)){
                 $return[] = $row;
             }
             return $return;
         }
        function get_contact_address( $contact_id ){
             $query = "SELECT * FROM contacts_address WHERE contact_id = '$contact_id' ";
             $res = $this->db->query($query);
             $return = array();
             while( $row=$this->db->fetch_assoc($res)){
                 $return[] = $row;
             }
             return $return;
        }
                function get_contact_email( $contact_id ){
             $query = "SELECT * FROM contacts_email WHERE contact_id = '$contact_id' ";
             $res = $this->db->query($query);
             $return = array();
             while( $row=$this->db->fetch_assoc($res)){
                 $return[] = $row;
             }
             return $return;
        }
                function get_contact_im( $contact_id ){
             $query = "SELECT * FROM contacts_im WHERE contact_id = '$contact_id' ";
             $res = $this->db->query($query);
             $return = array();
             while( $row=$this->db->fetch_assoc($res)){
                 $return[] = $row;
             }
             return $return;
        }
                function get_contact_twitter( $contact_id ){
             $query = "SELECT * FROM contacts_twitter WHERE contact_id = '$contact_id' ";
             $res = $this->db->query($query);
             $return = array();
             while( $row=$this->db->fetch_assoc($res)){
                 $return[] = $row;
             }
             return $return;
        }
         function contact_search( $string , $search_arr = array() , $dataamount = "standard" ){
            $fields = array();
            $query = "SELECT a.type type, first_name , last_name, company_name, a.contact_id contact_id FROM contacts a ";
            foreach( $search_arr as $sa ){
                switch( $sa ){
                    case "phone":
                        $fields[] = "number";
                        $query .= "LEFT JOIN contacts_phone d ON a.contact_id = d.contact_id ";
                    break;
                    case "email":
                        $fields[] = "email";
                        $query .= "LEFT JOIN contacts_email c ON a.contact_id = c.contact_id ";
                    break;
                    case "address":
                        $fields[] = "street_address";
                        $fields[] = "city";
                        $fields[] = "state";
                        $fields[] = "zip";
                        $query .= "LEFT JOIN contacts_address b ON a.contact_id = b.contact_id ";

                    break;
                    case "website":
                        $fields[] = "website";
                        $query .= "LEFT JOIN contacts_website e ON a.contact_id = e.contact_id ";
                    break;
                }

            }
            $query = $query . "WHERE " . $this->write_search_query( $fields , $string);
            //echo $query . "<br/>" . print_r($fields);
            $result = $this->db->query( $query );
            $return = array();
            while( $row=$this->db->fetch_assoc($result)){
                if( $row["type"] == "People" ){
                 $row["name"] = $row["first_name"] . " " . $row["last_name"];
                } else {

                    $row["name"] = $row["company_name"];
                }
                $row["query"] = $query;
                 $return[] = $row;

             }
             return $return;


        }

        /* The following will probably be added to database class, but for now I do not want to add it there
         * - Tim Holum

         */
        function write_search_query( $fields , $string ){
            $return = '(';
            $string_explode = explode( " " , $string );

            foreach( $string_explode as $substr ){
                $return .= "(";
                foreach( $fields as $field ){
                                $return .= "`$field` LIKE '%$substr%' OR";
                }
                        $return = substr_replace($return, "", -2);
                $return .= ") AND";
            }
            $return = substr_replace($return, "", -3);
            $return .= ")";
            return $return;
        }

}





class People_Contacts extends Contacts
{
	var $first_name;
	var $last_name;
	var $title;
	var $company;
	var $comments;
		
	//////Methods/////////
	function AddContactPeople($runat)  // $runat=local/server 
	{
		switch($runat){
		
			case 'local':
					//Display Form
					if(count($_POST[person])>0){
					extract($_POST[person]);
					$this->first_name=$first_name;
					$this->last_name=$last_name;
					$this->title=$title;
					$this->company=$company_name;
					$this->comments=$comments;
					$this->type='People';
					}
					//create client side validation
					$FormName='frm_People_Contacts';
					$ControlNames=array("person_first_name"			=>array('person_first_name',"''","Required !! ","span_person_first_name"),
										"person_last_name"			=>array('person_last_name',"''","Required !! ","span_person_last_name"),
										);

					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,
											$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;

					?>
					<div class="new_party_dialog" id="new_person_dialog" style="overflow: visible;">
				 	 <div class="container edit">
					<div class="Left">
					  <div class="col">	
					 <form action="" autocomplete="off" class="new_person" id="new_person" method="post" 
					 enctype="multipart/form-data" name="<?php echo $FormName; ?>">
					<?php $this->basicinfo_contact();  ?>
					<div class="innercol">
					  <table class="contact_types" id="contact_section" cellpadding="0" cellspacing="0">
					  <tbody>
					<?php 
					$this->AddContactDeatils('local');
					?>
					  </tbody></table></div>
					  </form></div></div></div></div>
					  <script type="text/javascript">
							new Autocomplete("query", { serviceUrl:"CompanyList.php" });
						</script>
					<?php
					
					//create client side validation
					$ControlNames=array("frm_People_Contacts'"=>
					array('frm_People_Contacts',"Type","Invalid Image Type !!","spanpicture")
										);
					$this->ValidationFunctionName = 'Type';

					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
			break;
		case 'server':
					//Reading Post Date
					extract($_POST[person]);
					$this->first_name=$first_name;
					$this->last_name=$last_name;
					$this->title=$title;
					//$this->company=$company_name;
					$this->comments=$comments;
					//directory initialization
					//picture initialization
					$this->type='People';
						//server side validation
					$return =true;
					if($this->Form->ValidField($first_name,'empty','Please Enter Your First Name')==false)
						$return =false;
					if($this->Form->ValidField($last_name,'empty','Please Enter Your Last Name')==false)
						$return =false;

					$this->company=$this->GetValidCompany($company_name);
					if($return){
					if($_FILES[picture][name]!=""){//file uploading
					
							$type = $this->objFileUpload->CheckFileType($_FILES[picture][type]);
							
							if( $type == 'true') 
							{
								$this->objFileUpload->UploadMode         = "Add";
								$this->objFileUpload->IsSaveByRandomName = true;
								$this->objFileUpload->UploadContent =$_FILES[picture];
								$this->objFileUpload->UploadFolder =$this->directory;
								$this->objFileUpload->NeedReturnStatement = true;
								$file=$this->objFileUpload->Upload();
								$file_names=explode("|",$file);
								$this->picture=$file_names[1];
								
								$insert_sql_array = array();
								$insert_sql_array[user_id] = $this->user_id;
								$insert_sql_array[first_name] = $this->first_name;
								$insert_sql_array[last_name] = $this->last_name;	
								$insert_sql_array[title] = $this->title;
								$insert_sql_array[company] = $this->company;
								$insert_sql_array[comments] = $this->comments;
								$insert_sql_array[type] = $this->type;
								
								$insert_sql_array[picture] = $this->picture;//insertion of picture n directory
								$insert_sql_array[directory] = $this->directory;
										
								$this->db->insert(TBL_CONTACT,$insert_sql_array);		
								$this->contact_id=$this->db->last_insert_id();
								$this->AddContactDeatils('server');				
					        }
							else
							{
								echo $this->Form->ErrtxtPrefix.'<li>Invalid image, please uplaod jpg, gif or png images only</li>'.$this->Form->ErrtxtSufix; 
								$this->AddContactPeople('local');
								exit();
							}

						}
						
						else
						{
							$insert_sql_array = array();
							$insert_sql_array[user_id] = $this->user_id;
							$insert_sql_array[first_name] = $this->first_name;
							$insert_sql_array[last_name] = $this->last_name;	
							$insert_sql_array[title] = $this->title;
							$insert_sql_array[company] = $this->company;
							$insert_sql_array[comments] = $this->comments;
							$insert_sql_array[type] = $this->type;
							
							$insert_sql_array[picture] = $this->picture;//insertion of picture n directory
							$insert_sql_array[directory] = $this->directory;
									
							$this->db->insert(TBL_CONTACT,$insert_sql_array);		
							$this->contact_id=$this->db->last_insert_id();
							$this->AddContactDeatils('server');	
								
							$_SESSION['msg']='Contact successfully created ';
						}	
					?>
					<script type="text/javascript">
					window.location="contacts.php";
					</script>
					<?php
				}
				else
				{
				echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
				$this->AddContactPeople('local');
				}
					break;
		default : echo 'Wrong Paramemter passed';
		
		}
	
	}
	
	function GetValidCompany($company_name){
		if(trim($company_name)=='') return '';
		$sql = "select * from ".TBL_CONTACT." where company_name='$company_name' and type='Company'";
		$result=$this->db->query($sql,__FILE__,__LINE__);
		
		if($this->db->num_rows($result)>0){
			$row=$this->db->fetch_array($result);
			return $row[contact_id];
		} else {
			$contact_info['name'] = $company_name;
			$contact_info['type'] = 'Company';
			$contact_id = $this->Addcontact_On_Fly($contact_info,$tag,true);
			return $contact_id;
		}
	}
	
	function EditContactPeople($runat,$contact_id,$editFunction='EditContactPeople')  // $runat=local/server 
	{
		$this->contact_id=$contact_id;
		switch($runat){
		
			case 'local':
					//Display Form
					$sql="Select * from ".TBL_CONTACT." where contact_id='$this->contact_id'";
					$result=$this->db->query($sql,__FILE__,__LINE__);
					$row=$this->db->fetch_array($result);
					$this->first_name=$row[first_name];
					$this->last_name=$row[last_name];
					$this->title=$row[title];
					$this->company=$row[company];
					$this->comments=$row[comments];

				
					if(count($_POST[person])>0){
					extract($_POST[person]);
					$this->first_name=$first_name;
					$this->last_name=$last_name;
					$this->title=$title;
					$this->comments=$comments;
					$this->type='People';
					}
					//create client side validation
					$FormName='frm_People_Contacts';
					$ControlNames=array("person_first_name"			=>array('person_first_name',"''","Required !! ","span_person_first_name"),
										"person_last_name"			=>array('person_last_name',"''","Required !! ","span_person_last_name"),
										);

					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,
											$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;

					?>
					<div class="new_party_dialog" id="new_person_dialog" style="overflow: visible;">
				 	 <div class="container edit">
					<div class="Left">
					  <div class="col">	
					 <form action="" autocomplete="off" class="new_person" id="new_person" method="post" 
					 enctype="multipart/form-data" name="<?php echo $FormName; ?>">
						<input type="hidden" name="person[old_file_name]" value="<?php echo $row[picture];?>" />
					
					<?php $this->basicinfoedit_contact($row);  ?>
					
					<div class="innercol">
					  <table class="contact_types" id="contact_section" cellpadding="0" cellspacing="0">
					  <tbody>
					<?php 
					$this->EditContactDeatils('local');
					?>
					  </tbody></table></div>
					  </form></div></div></div></div>
					   <script type="text/javascript">
							new Autocomplete("query", { serviceUrl:"CompanyList.php" });
						</script>

					<?php				
					//create client side validation
					$ControlNames=array("frm_People_Contacts'"=>
					array('person_company_name',"Type","Invalid Image Type !!","spanpicture")
										);
					$this->ValidationFunctionName = 'Type';

					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;					
					
			break;
		case 'server':
					extract($_POST[person]);
					$this->first_name=$first_name;
					$this->last_name=$last_name;
					$this->title=$title;
					$this->company=$this->GetValidCompany($company_name);
					$this->comments=$comments;
					$this->old_file_name=$old_file_name;
					//directory initialization
					//picture initialization
					$this->type='People';
					//server side validation
					$return =true;
					if($this->Form->ValidField($first_name,'empty','Please Enter Your First Name')==false)
						$return =false;
					if($this->Form->ValidField($last_name,'empty','Please Enter Your Last Name')==false)
						$return =false;
					
					if($return){
					
					if($_FILES[picture][name]!="")
					{
							$type = $this->objFileUpload->CheckFileType($_FILES[picture][type]);
							
							if( $type == 'true') 
							{
								$this->objFileUpload->UploadMode    = "Edit";
								$this->objFileUpload->IsSaveByRandomName = true;
								$this->objFileUpload->OldFileName=$this->old_file_name;
								$this->objFileUpload->UploadContent =$_FILES[picture];
								$this->objFileUpload->UploadFolder =$this->directory;
								$this->objFileUpload->NeedReturnStatement = true;
								$file=$this->objFileUpload->Upload();
								$file_names=explode("|",$file);
								$this->picture=$file_names[1];
							}
							else
							{
								echo $this->Form->ErrtxtPrefix.'<li>Invalid image, please uplaod jpg, gif or png images only</li>'.$this->Form->ErrtxtSufix; 
								$this->$editFunction('local',$this->contact_id);
								exit();
							}

					}
					else
					$this->picture=$this->old_file_name;
										
					$update_sql_array = array();
					$update_sql_array[title] = $this->title;	
					$update_sql_array[last_name] = $this->last_name;
					$update_sql_array[first_name] = $this->first_name;
					$update_sql_array[company] = $this->company;
					$update_sql_array[comments] = $this->comments;
					$update_sql_array[picture] = $this->picture;
					$update_sql_array[directory] = $this->directory;
					$this->db->update(TBL_CONTACT,$update_sql_array,"contact_id",$this->contact_id);
					$this->EditContactDeatils('server');
						
					$_SESSION['msg']='Contact has been saved successfully ';
					?>
					<script type="text/javascript">
					window.location="contacts.php";
					</script>
					<?php
				}
				else
				{
				echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
				$this->$editFunction('local',$this->contact_id);
				}
			
			break;
		default : echo 'Wrong Paramemter passed';
		
		}	
	}
		
  function basicinfo_contact(){
	?>
	<div class="page_header">
	  <table class="subject_header">
		<tbody>
		<tr>
		  <td class="name" style="vertical-align: middle;">
		  <table class="contact_types" cellpadding="0" cellspacing="0">
			 <tbody><tr class="name first_name">
				<th><h2>First name </h2></th>
				<td>
				<input class="name" id="person_first_name" name="person[first_name]" size="30" type="text" value="<?php echo $this->first_name; ?>">
				<ul id="error_list"><li><span id="span_person_first_name"></span></li></ul>
				</td>
			  </tr>
			  <tr class="name last_name">
				<th><h2>Last name </h2></th>
				<td><input class="name" id="person_last_name" name="person[last_name]" size="30" type="text" value="<?php echo $this->last_name; ?>">
				<ul id="error_list"><li><span id="span_person_last_name"></span></li></ul></td>
			  </tr>
			  <tr class="title name" id="title_field_person">
				<th><h2>Title </h2></th>
				<td><div class="contact_methods"><div class="contact_method">
					<input id="person_title" name="person[title]" size="30" class="title"  type="text" value="<?php echo $this->title; ?>">
					</div></div>
					<?php /*?><div id="title_person" class="contact_forms"><div class="blank_slate" style="">Add a title</div>
				<div style="display: none;" class="contact_methods">
				<div class="contact_method">
				  <input type="text" style="background: none repeat scroll 0% 0% white;" size="30" name="person[title]" id="person_title" class="autofocus">
				</div>
				<script type="text/javascript">
				//&lt;![CDATA[
				new ContactInfo.ContactForm('title_person');
				//]]&gt;
				</script>
			   </div>
				</div><?php */?>
				</td>
			  </tr>
			<!--<tr class="company name">
			  <th><h2>Picture </h2></th>
				<td><div class="contact_methods"><div class="contact_method contact_forms"><input type="file" name="picture" id="person_picture" value=""  />
			<span id="spanpicture"></span></div></div></td>
			</tr>	-->						 
			
`						<?php /*?><tr  class="company name">
			<th><h2>Comments </h2></th>
			<td><div class="contact_method"><div class="contact_method"><textarea name="person[comments]" id="person_comments"><?php echo $this->comments; ?></textarea>
			<span id="spancomments"></span></div></div>
			</td></tr><?php */?>					  
			 <tr class="company name">
				<th><h2>Company </h2></th>
				<td><div class="contact_method">
				  <input type="text" id="query" name="person[company_name]" class="country" value="<?php echo $this->company; ?>"> 
				  <div id="results"></div>
			
			</div>
			</td>
			  </tr></tbody>
			
			</table>
		  </td>
		</tr>
	  </tbody></table>
	</div>
	<?php
  }// end of bacisinfo_contact()
	
  function basicinfoedit_contact($row){
	?>
	<div class="page_header">
	  <table class="subject_header">
		<tbody><tr>
		  <td class="name" style="vertical-align: middle;">
			<table class="contact_types" cellpadding="0" cellspacing="0">
			  <tbody><tr class="name first_name">
				<th><h2>First name </h2></th>
				<td><input class="name" id="person_first_name" 	name="person[first_name]" size="30" type="text" value="<?php echo $row[first_name];?>">
				<ul id="error_list"><li><span id="span_person_first_name" ></span></li></ul></td>
			  </tr>
			  <tr class="name last_name">
				<th><h2>Last name </h2></th>
				<td><input class="name" id="person_last_name" name="person[last_name]" size="30" type="text" value="<?php echo $row[last_name];?>">
				<ul id="error_list"><li><span id="span_person_last_name" ></span></li></ul></td>
			  </tr>
			  <tr class="title name" id="title_field_person">
				<th><h2>Title </h2></th>
				<td><div class="contact_methods"><div class="contact_method">
					<input id="person_title" name="person[title]" size="30" class="title"  type="text" value="<?php echo $row[title];?>">
					</div></div>
				</td>
			  </tr>
			<tr class="company name">
			  <th><h2>Picture </h2></th>
				<td><div class="contact_methods"><div class="contact_method contact_forms"><input type="file" name="picture" id="person_picture" value=""  />
			</div></div></td>
			</tr>							 
			<?php /*?>
			<tr  class="company name">
			<th><h2>Comments </h2></th>
			<td><div class="contact_method"><div class="contact_method"><textarea name="person[comments]" id="person_comments"><?php echo $this->comments; ?></textarea>
			<span id="spancomments"></span></div></div>
			</td></tr>		<?php */?>			  
			 <tr class="company name">
				<th><h2>Company </h2></th>
				<td><div class="contact_method"><div class="contact_method contact_forms">
				<input type="text" id="query" name="person[company_name]" class="country" value="<?php echo $this->GetCompany_name($row[company],'yes'); ?>"> 
			</div></div>
			</td>
			  </tr></tbody>
			
			</table>
		  </td>
		</tr>
	  </tbody></table>
	</div>
	<?php
	} // end of basicinfoedit_contact()
 }

class Company_Contacts extends People_Contacts
{
	var $company_name;
	
	//////Methods/////////
	function AddContactCompany($runat)  // $runat=local/server 
	{
		switch($runat){
		
			case 'local':
					//Display Form
										//Display Form
					if(count($_POST[person])>0){
					extract($_POST[person]);
					$this->company_name=$company_name;
					$this->type='Company';
					}
					//create client side validation
					$FormName='frm_Company_Contacts';
					$ControlNames=array("person_company_name"=>
					array('person_company_name',"''","Required !!","span_person_company_name")
										);

					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;

					?>
					<div class="new_party_dialog" id="new_person_dialog" style="overflow: visible;">
				 	 <div class="container edit">
					<div class="Left">
					  <div class="col">	
					 <form action="" autocomplete="off" class="new_person" id="new_person" method="post" 
					 enctype="multipart/form-data" name="<?php echo $FormName; ?>">
					<div class="page_header">
					  <table class="subject_header">
						<tbody><tr>
						  <td class="name" style="vertical-align: middle;">
							<table class="contact_types" cellpadding="0" cellspacing="0">
							  <tbody><tr class="name first_name">
								<th><h2>Company Name</h2></th>
								<td><input class="name" id="person_company_name" 	name="person[company_name]" size="30" 
								type="text" value="<?php echo $this->company_name; ?>" >
								<div id="results"></div>
								<ul id="error_list"><li><span id="span_person_company_name" ></span></li></ul>
								</td>
							  </tr>
							 
							<!--<tr class="company name">
							  <th><h2>Picture </h2></th>
								<td><div class="contact_methods"><div class="contact_method contact_forms"><input type="file" name="picture" id="person_picture" value=""  />
							<span id="spanpicture"></span></div></div></td>
							</tr>	-->						 
						</tbody>
							
							</table>
						  </td>
						</tr>
					  </tbody></table>
					</div>
					<div class="innercol">
					  <table class="contact_types" id="contact_section" cellpadding="0" cellspacing="0">
					  <tbody>
					<?php 
					$this->AddContactDeatils('local');
					?>
					  </tbody></table></div>
					  </form></div></div></div></div>
					<?
						
					//create client side validation
					$ControlNames=array("person_company_name'"=>
					array('person_company_name',"Type","Invalid Image Type !!","spanpicture")
										);
					$this->ValidationFunctionName = 'Type';

					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
						
					
		
			break;
		case 'server':
					//Reading Post Date
					extract($_POST[person]);
					$this->company_name=$company_name;
					$this->type='Company';
					//extra 2nd last initialization
					$return =true;
					if($this->Form->ValidField($company_name,'empty','Please Enter Company Name')==false)
						$return =false;

					if($return)
					{
					    
						if($_FILES[picture][name]!="")
						{
							$type = $this->objFileUpload->CheckFileType($_FILES[picture][type]);
							
							if( $type == 'true') 
							{
								$this->objFileUpload->UploadMode         = "Add";
								$this->objFileUpload->IsSaveByRandomName = true;
								$this->objFileUpload->UploadContent =$_FILES[picture];
								$this->objFileUpload->UploadFolder =$this->directory;
								$this->objFileUpload->NeedReturnStatement = true;
								$file=$this->objFileUpload->Upload();
								$file_names=explode("|",$file);
								$this->picture=$file_names[1];
								$insert_sql_array = array();
								$insert_sql_array[user_id] = $this->user_id;
								$insert_sql_array[company_name] = $this->company_name;
								$insert_sql_array[type] = $this->type;
								$insert_sql_array[picture] = $this->picture;//extra 
								$insert_sql_array[directory] = $this->directory;
										
								$this->db->insert(TBL_CONTACT,$insert_sql_array);		
								$this->contact_id=$this->db->last_insert_id();
								$this->AddContactDeatils('server');					
								$_SESSION['msg']='Company has been added successfully';
							}
							else
							{
								echo $this->Form->ErrtxtPrefix.'<li>Invalid image, please uplaod jpg, gif or png images only</li>'.$this->Form->ErrtxtSufix; 
								$this->AddContactCompany('local');
								exit();
							}

						}
						
					else	
					{	
					$insert_sql_array = array();
					$insert_sql_array[user_id] = $this->user_id;
					$insert_sql_array[company_name] = $this->company_name;
					$insert_sql_array[type] = $this->type;
					$insert_sql_array[picture] = $this->picture;//extra 
					$insert_sql_array[directory] = $this->directory;
							
					$this->db->insert(TBL_CONTACT,$insert_sql_array);		
					$this->contact_id=$this->db->last_insert_id();
					$this->AddContactDeatils('server');					
					$_SESSION['msg']='Company has been added successfully';
					}
					?>
					<script type="text/javascript">
					window.location="contact_profile.php?contact_id=<?php echo $this->contact_id; ?>";
					</script>
					<?php
				}
				else
				{
				echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
				$this->AddContactCompany('local');
				}

			break;
		default : echo 'Wrong Paramemter passed';
		
		}
	
	}
	
	function EditContactCompany($runat,$contact_id)  // $runat=local/server 
	{	
		$this->contact_id=$contact_id;
		switch($runat){
		
			case 'local':
					//Display Form
					$sql="Select * from ".TBL_CONTACT." where contact_id='$this->contact_id'";
					$result=$this->db->query($sql,__FILE__,__LINE__);
					$row=$this->db->fetch_array($result);
					$this->company_name=$row[company_name];
					$this->type='Company';
					
					if(count($_POST[person])>0){
					extract($_POST[person]);
					$this->company_name=$company_name;
					}
					
					//create client side validation
					$FormName='frm_EditContactCompany';
					$ControlNames=array("person_company_name"=>
					array('person_company_name',"''","Required !!","span_person_company_name")
										);

					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$this->ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
					?>
						<div class="new_party_dialog" id="new_person_dialog" style="overflow: visible;">
				 	 <div class="container edit">
					<div class="Left">
					  <div class="col">	
					 <form action="" autocomplete="off" class="new_person" id="new_person" method="post" 
					 enctype="multipart/form-data" name="<?php echo $FormName; ?>">
					 <input type="hidden" name="person[old_file_name]" id="person_old_file_name" value="<?php echo $row[picture];?>" />
					<div class="page_header">
					  <table class="subject_header">
						<tbody><tr>
						  <td class="name" style="vertical-align: middle;">
							<table class="contact_types" cellpadding="0" cellspacing="0">
							  <tbody><tr class="name first_name">
								<th><h2>Company Name</h2></th>
								<td><input class="name" id="person_company_name" 	name="person[company_name]" size="30" 
								type="text" value="<?php echo $this->company_name; ?>" />
								<ul id="error_list"><li><span id="span_person_company_name"></span></li></ul>
								</td>
							  </tr>
							 
							<tr class="company name">
							  <th><h2>Picture </h2></th>
								<td><div class="contact_methods"><div class="contact_method contact_forms"><input type="file" name="picture" id="person_picture" value="<?php echo $this->company_name; ?>"  />
							</div></div></td>
							</tr>							 
						</tbody>
							
							</table>
						  </td>
						</tr>
					  </tbody></table>
					</div>
					<div class="innercol">
					  <table class="contact_types" id="contact_section" cellpadding="0" cellspacing="0">
					  <tbody>
					<?php 
					$this->EditContactDeatils('local');
					?>
					  </tbody></table></div>
					  </form></div></div></div></div>
					<?
		
			break;
		case 'server':
					extract($_POST[person]);
					$this->company_name=$company_name;
					$this->old_file_name=$old_file_name;
					
					$return =true;
					if($this->Form->ValidField($company_name,'empty','Please Enter Company Name')==false)
						$return =false;

					if($return)
					{
					
					if($_FILES[picture][name]!="")
						{
							$type = $this->objFileUpload->CheckFileType($_FILES[picture][type]);

							if( $type == 'true') 
							{
								$this->objFileUpload->UploadMode         = "Edit";
								$this->objFileUpload->IsSaveByRandomName = true;
								$this->objFileUpload->OldFileName=$this->old_file_name;
								$this->objFileUpload->UploadContent =$_FILES[picture];
								$this->objFileUpload->UploadFolder =$this->directory;
								$this->objFileUpload->NeedReturnStatement = true;
								$file=$this->objFileUpload->Upload();
								$file_names=explode("|",$file);
								$this->picture=$file_names[1];
							}
							else
							{
								echo $this->Form->ErrtxtPrefix.'<li>Invalid image, please uplaod jpg, gif or png images only</li>'.$this->Form->ErrtxtSufix; 
								$this->EditContactCompany('local',$this->contact_id);
								exit();
							}

						}
					else
					$this->picture=$this->old_file_name;///end extra file uploading code
					
					$update_sql_array = array();
					$update_sql_array[company_name] = $this->company_name;	
					$update_sql_array[picture] = $this->picture;
					$update_sql_array[directory] = $this->directory;	

					$this->db->update(TBL_CONTACT,$update_sql_array,"contact_id",$this->contact_id);
					$this->EditContactDeatils('server');
						
					$_SESSION['msg']='Company has been saved successfully';
					?>
					<script type="text/javascript">
					window.location="contact_profile.php?contact_id=<?php echo $this->contact_id; ?>";
					</script>
					<?php
				}
				else
				{
				echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
				$this->EditContactCompany('local',$this->contact_id);
				}

			break;
		default : echo 'Wrong Paramemter passed';
		
		}
	
	}
	
}




class Company_Global extends Company_Contacts
{


	function FillContact($contact_id)
	{
		$this->contact_id=$contact_id;
		//todo : write code to fill the object completly & create properteid to utilize these values
	}

	//////Methods/////////
	
	/*********************Add Contact***********************/
	function AddContact($runat,$type)   
	{
		switch($type){
		
			case 'People':
							$this->AddContactPeople($runat);
							break;
			
			case 'Company':
							$this->AddContactCompany($runat);
							break;
		
			default : 		echo 'Invalid Contact Type';
		
		}
	}	
	
	/*********************Edit Contact***********************/
	function EditContact($runat,$contact_id)  
	{	
		$type=$this->getContactType($contact_id,'');
		switch($type){
		
			case 'People':
							$this->EditContactPeople($runat,$contact_id);
							break;
			
			case 'Company':
							$this->EditContactCompany($runat,$contact_id);
							break;
		
			default : 		echo 'Invalid Contact Type';
							break;
		
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function GetContactProfileById($contact_id){ 
		$sql="select contact_id,user_id,type,first_name,last_name,title,company,comments,company_name,timestamp,picture,directory from ".TBL_CONTACT." where contact_id='$contact_id'";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		$rows = $this->db->fetch_array($record);
		?>
		<div class="field">
		<a  href="contact_profile.php?contact_id=<?php echo $contact_id; ?>"  >
		<?php if($rows['type']=='People') { ?>
			<h1><?php echo $rows['first_name'].' '.$rows['last_name'] ?></h1>
		<?php }else { ?>
			<h1><?php echo $rows['company_name']?></h1>
		<?php } ?></a>
		</div>
		<?php
		$temp_sql="select * from ".CONTACT_PHONE." where contact_id='$contact_id'";
		$temp_recrod=$this->db->query($temp_sql, __FILE__, __LINE__);
		$temp_rows=$this->db->fetch_array($temp_recrod);
		?>
		<div class="clear" id="phone_no">
		<?php if($temp_rows['number']!='') {
		if($temp_rows['type']!=Mobile and $temp_rows['type']!='Cell')
		echo '('.substr($temp_rows['number'], 0, 3).')'.substr($temp_rows['number'], 3, 3).'-'.substr($temp_rows['number'], 6, 4).' ('.$temp_rows['type'].')';
		else
		echo $temp_rows['number'].' ('.$temp_rows['type'].')';
		}?>
		</div>
		<?php 
	}	
	
	function AddQuickContact($runat,$phone,$f_name='',$l_name='',$company='',$email=''){
		ob_start();
		switch($runat){
			case 'local':
				?>
				<table width="100%">
				<tr>
				<td>First Name</td>
				<td><input name="f_name" id="f_name" value="<?php echo $f_name; ?>"/></td>
				</tr>
				
				<tr>
				<td>Last Name</td>
				<td><input name="l_name" id="l_name" value="<?php echo $l_name; ?>"/></td>
				</tr>

				<tr>
				<td>Company</td>
				<td><input name="company" id="company" value="<?php echo $company; ?>"/></td>
				</tr>
				
				<tr>
				<td>Email</td>
				<td><input name="email" id="email" value="<?php echo $email; ?>"/></td>
				</tr>
				
				<tr>
				<td>Phone</td>
				<td><input name="phone" id="phone" value="<?php echo $phone; ?>"/></td>
				</tr>
				
				</table>
				<?php
				break;
			case 'server':
				
				$insert_sql_array = array();
				$insert_sql_array['first_name'] = $f_name;
				$insert_sql_array['last_name'] = $l_name;
				$insert_sql_array['type'] = 'People';
				$insert_sql_array['company_name'] = $company;
				$insert_sql_array['user_id'] = $_SESSION['user_id'];

				$this->db->insert(TBL_CONTACT,$insert_sql_array);
				$this->contact_id=$this->db->last_insert_id();
				
				$insert_sql_array = array();
				$insert_sql_array['first_name'] = $f_name;
				$insert_sql_array['contact_id'] = $this->contact_id;
				$this->db->insert(CONTACT_PHONE,$insert_sql_array);

				$insert_sql_array = array();
				$insert_sql_array['email'] = $f_name;
				$insert_sql_array['contact_id'] = $this->contact_id;
				$this->db->insert(CONTACT_EMAIL,$insert_sql_array);
				break;
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	/*********************ContactSearchBox***********************/
	function ContactSearchBox($obj='contact')
	{
	?>
<div> <form onsubmit="return false;">
<input name="search" type="text" id="search" size="60" onkeyup="<?php echo $obj; ?>.GetContact(this.value,'','','','<?php echo $obj; ?>', {target: 'search_result'});" autocomplete='off' /> <div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
</form>
  <!--or  <a href="#">More Options</a> --></div>
	<?
	}

	/*********************ContactSearchContainer***********************/
	function ContactSearchContainer($obj='contact')
	{
		?>
			<div id="search_result">
			<?PHP echo $this->GetContact('','','','',$obj);?>
			</div>
		<?
	}
	
	/*********************ContactSearchContainer***********************/
	function TagSearchContainer($tag_id)
	{	if($tag_id!=''){
		?>
			<div id="search_result">
			<?PHP echo $this->GetContactsInTag($tag_id);?>
			</div>
		<?
		}
	}

	/*********************GetContactHead***********************/
	function GetContactHead($contact_id,$target,$obj='contact')
	{
		ob_start();
		$this->contact_id=$contact_id;
		$sql="select * from ".TBL_CONTACT." where contact_id='$this->contact_id'";
		$recrod=$this->db->query($sql, __FILE__, __LINE__);
		$rows=$this->db->fetch_array($recrod);
		 
		?>
	    <div id="header_contact_data">
		<?php
		if($rows['type']=='People')
			{
				?>
				<h4><?php echo $rows['first_name'].' '.$rows['middle_name'].' '.$rows['last_name']; ?></h4>	
				<?php 
			} 
			else
			{
				?>
					<h4><?php echo $rows['company_name']; ?></h4>
				<?php
			}
			?>
			<p class="contact_title"><a onclick="<?php /*echo $this->dynamic_page->phplivex_subpage_link('contacts','contact_profile','display_contact_area',array('contact_id'=> $rows['contact_id'])); */?>"><?php echo $rows['title']; ?></a>
			<?php if(trim($this->GetCompany_name($rows['company']))!='') { 
			echo ' at '.$this->GetCompany_name($rows['company']);
			}
			?>
			</p>
			
			<img class="tags_thumb" alt="" src="images/tag_icon.png"  align="absmiddle"/>
			
			<ul class="link_list" style="display:inline">
				<span id="alltags">
				<?php echo $this->ShowTags($obj, 'TBL_CONTACT', $contact_id, '');?> 
				</span>
				<li id="edit_link"> - <a href="#" onclick="javascript: 
													document.getElementById('edit_link').style.display='none';
													<?php echo $obj; ?>.TagModule_id('local',
																		'<?php echo $obj; ?>',
																		'TBL_CONTACT',
																		<?php echo $contact_id; ?>,
																		'','',
																		'alltags',
																		'alltags',
																		'document.getElementById(\'edit_link\').style.display=\'\';',
																		{target: 'alltags', preloader: 'prl'});" 
				style="color:#666666;">Edit tags</a></li>
			</ul>
			</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	
	}
			

	/*********************GetContact***********************/
	function GetContact($pattern='',$page='',$list='',$type='',$obj='contact')
	{
                $this->dynamic_page = new dynamic_page();
                
		ob_start();
		if($page == "" ) {
			$page = 1;
		}
                // ( phone LIKE '%part%' OR email LIKE '%part%' .... ) AND
                if( $pattern != '' ){
                $pattern_arr = explode( " " , $pattern );
                $WHERE = '';
                foreach( $pattern_arr as $subpat ){
                    if( $subpat != '' ){
                        
                        $WHERE .= " (a.first_name LIKE '%$subpat%' OR a.last_name LIKE '%$subpat%' or a.company_name  LIKE '%$subpat%') and";
                        //echo '$WHERE .= (a.first_name LIKE '%$subpat%' OR a.last_name LIKE '%$subpat%' or a.company_name  LIKE '%$subpat%') and';
                    }
                    
                }
                if( $WHERE == ''){
                    $WHERE = "(a.first_name LIKE '%$pattern%' OR a.last_name LIKE '%$pattern%' or a.company_name  LIKE '%$pattern%') and";
                }
                } else {
                    $WHERE = "(a.first_name LIKE '%$pattern%' OR a.last_name LIKE '%$pattern%' or a.company_name  LIKE '%$pattern%') and";
                }
               /* $sql2="select DISTINCT a.contact_id,a.user_id,a.type,a.first_name,a.last_name,a.title,a.company,a.comments,a.company_name,a.timestamp,a.picture,a.directory  from ".TBL_CONTACT." a, ".TBL_ELEMENT_PERMISSION.
			" b, " . CONTACT_EMAIL . " e, " . CONTACT_PHONE . " p  where $WHERE (b.module='TBL_CONTACT' and b.module_id=a.contact_id ) 
			and ( (((b.access_to in $this->groups and e.contact_id = a.contact_id and p.contact_id = a.contact_id and (b.access_to_type='TBL_USERGROUP' or b.access_to_type='*')) or (b.access_to='$this->user_name' and b.access_to_type='TBL_USER')) 
			and (access_type='FULL' or access_type='VIEWONLY')) or  a.user_id='$this->user_id') order by a.last_name , a.company_name LIMIT ";
                //echo $sql2 . "<br>";
                //echo $WHERE;
                */
                $sql = "select DISTINCT a.contact_id,a.user_id,a.type,a.first_name,a.last_name,a.title,a.company,a.comments,a.company_name,
a.timestamp,a.picture,a.directory  from contacts a 
LEFT JOIN  tbl_element_permission b ON b.module_id = a.contact_id
where 
$WHERE type='Company'
";
                
		$allRecords=$this->db->record_number($sql);
                $this->SetPagelength(30);
		$record=$this->db->pagination($sql, $this->GetPagelength(), $page);
		if($pattern!='' and $list=='' and $type=='') {
		?>
		<div class="heading"><?php echo $allRecords; ?> Contact Matching <?php echo "'$pattern'"; ?></div>
		<?php
		 }
		  if($list=='' and $type=='') {
		?><div class="form_main"><div class="cf-pagination" align="right"><?php $this->db->DisplayAjaxPage($this->GetPagelength(),$page,$allRecords,$obj)?></div></div>
		<?php
		}
		while($rows=$this->db->fetch_array($record))
		{  
		   if($list=='' and $type=='') {
/*		   $temp_sql_em="select * from ".EM_WEB_APP_INFO." where contact_id='$rows[contact_id]'";
			$temp_recrod_em=$this->db->query($temp_sql_em, __FILE__, __LINE__);
			$temp_rows_em=$this->db->fetch_array($temp_recrod_em);*/
			?>
			<div class="contact_match">
			<div class="label" class="contact_list" >	
			<a  class="contact_list" onclick="<?php echo $this->dynamic_page->phplivex_subpage_link('contacts','contact_profile','display_contact_area',array('contact_id'=> $rows['contact_id'])); ?>"  >
			</a></div>
			<div class="field contact_list">
			<a  onclick="<?php echo $this->dynamic_page->phplivex_subpage_link('contacts','contact_profile','display_contact_area',array('contact_id'=> $rows['contact_id'])); ?>"  >
			<?php /*if($rows['type']=='People') {  ?>
				<div class="heading bcolor"><?php echo $rows['first_name'].' '.$rows['last_name'] ?></div>
			<?php }else { */?>
				<div class="heading bcolor contact_list contact_option"><?php echo $rows['company_name']; ?></div>
			<?php //} ?></a>
			
			<div class="verysmall_text">
			<ul class="link_list" style="display:inline">
			<?php echo $this->ShowTags($obj, 'TBL_CONTACT', $rows[contact_id], '');  ?>
			</ul>
			</div>
			<?php
			$temp_sql="select * from ".CONTACT_EMAIL." where contact_id='$rows[contact_id]'";
			$temp_recrod=$this->db->query($temp_sql, __FILE__, __LINE__);
			$temp_rows=$this->db->fetch_array($temp_recrod);
			
			
			?>			
			</div>			
			</div>
			<?php 
		}
		else if($list!='')
			{
				?>
				<li onclick="window.location='contact_profile.php?contact_id=<?php echo $rows[contact_id]; ?>';" on>
				<?php 
				if($rows[picture]!='') { ?>
				<img src="thumb.php?file=<?php echo $rows[directory].'/'.$rows[picture]; ?>&size=40" 
				 alt=""  border="0"  align="absmiddle"/>
				<?php }
				else { ?>
				<img src="thumb.php?file=images/person.gif&size=40" alt="" border="0"  align="absmiddle"/>
				<?php } ?>
				<a onclick="<?php echo $this->dynamic_page->phplivex_subpage_link('contacts','contact_profile','display_contact_area',array('contact_id'=> $rows['contact_id'])); ?>"  >
				<?php if($rows['type']=='People') { 
						echo $rows['first_name'].' '.$rows['last_name'];
						}else { echo $rows['company_name']; } ?></a>
				</li>
				<?php
			}
			else if($type!='' and $rows[type]=='Company')
			{
				$suggestions[]=$rows[company_name];
				$data[]=$rows[company_name];
			}
		}
		
			if($list=='' and $type=='') {?><div class="contact_match"><div align="right" class="cf-pagination"><?php $this->db->DisplayAjaxPage($this->GetPagelength(),$page,$allRecords,$obj)?></div></div>
		<?php }
		
		if($type!='') {
		echo "suggestions:['".@implode("','",$suggestions)."'],";
		echo "data:['".@implode("','",$data)."']";
		}
		$html = ob_get_contents();
		ob_end_clean();
		return utf8_encode( $html );
	}

	function GetContact1($pattern='',$page='',$list='',$type='',$obj='contact')
	{
		ob_start();
		if($page == "" ) {
			$page = 1;
		}
                // ( phone LIKE '%part%' OR email LIKE '%part%' .... ) AND
                if( $pattern != '' ){
                $pattern_arr = explode( " " , $pattern );
                $WHERE = '';
                foreach( $pattern_arr as $subpat ){
                    if( $subpat != '' ){
                        
                        $WHERE .= " (a.first_name LIKE '%$subpat%' OR a.last_name LIKE '%$subpat%' or a.company_name  LIKE '%$subpat%' or c.number LIKE '%$pattern%' or or (c.number LIKE '%$pattern%' and c.contact_id = a.contact_id) or ((d.order_id LIKE '%$pattern%') and (d.contact_id = a.contact_id or d.vendor_contact_id = a.contact_id))) and";
                        echo '$WHERE .= (a.first_name LIKE '%$subpat%' OR a.last_name LIKE '%$subpat%' or a.company_name  LIKE '%$subpat%') and';
                    }
                    
                }
                if( $WHERE == ''){
                    $WHERE = "(a.first_name LIKE '%$pattern%' OR a.last_name LIKE '%$pattern%' or a.company_name  LIKE '%$pattern%' or (c.number LIKE '%$pattern%' and c.contact_id = a.contact_id) or ((d.order_id LIKE '%$pattern%') and (d.contact_id = a.contact_id or d.vendor_contact_id = a.contact_id)) ) and";
                }
                } else {
                    $WHERE = "(a.first_name LIKE '%$pattern%' OR a.last_name LIKE '%$pattern%' or a.company_name  LIKE '%$pattern%' or (c.number LIKE '%$pattern%' and c.contact_id = a.contact_id) or (d.order_id LIKE '%$pattern%' and (d.contact_id = a.contact_id or d.vendor_contact_id = a.contact_id)) ) and";
                }
               /* $sql2="select DISTINCT a.contact_id,a.user_id,a.type,a.first_name,a.last_name,a.title,a.company,a.comments,a.company_name,a.timestamp,a.picture,a.directory  from ".TBL_CONTACT." a, ".TBL_ELEMENT_PERMISSION.
			" b, " . CONTACT_EMAIL . " e, " . CONTACT_PHONE . " p  where $WHERE (b.module='TBL_CONTACT' and b.module_id=a.contact_id ) 
			and ( (((b.access_to in $this->groups and e.contact_id = a.contact_id and p.contact_id = a.contact_id and (b.access_to_type='TBL_USERGROUP' or b.access_to_type='*')) or (b.access_to='$this->user_name' and b.access_to_type='TBL_USER')) 
			and (access_type='FULL' or access_type='VIEWONLY')) or  a.user_id='$this->user_id') order by a.last_name , a.company_name LIMIT ";
                //echo $sql2 . "<br>";
                //echo $WHERE;
                */
                $sql = "select DISTINCT a.contact_id,a.user_id,a.type,a.first_name,a.last_name,a.title,a.company,a.comments,a.company_name,
						a.timestamp,a.picture,a.directory  from contacts_phone c, contacts a 
						LEFT JOIN  tbl_element_permission b ON b.module_id = a.contact_id
						where $WHERE
						 a.type='Company' and 
						b.module='TBL_CONTACT'
						and ( (((b.access_to in $this->groups and (b.access_to_type='TBL_USERGROUP' or b.access_to_type='*')) or (b.access_to='$this->user_name' and b.access_to_type='TBL_USER')) 
						and (access_type='FULL' or access_type='VIEWONLY')) or  a.user_id='$this->user_id') order by a.last_name , a.company_name limit 50
						";

		/*$sql="select DISTINCT a.contact_id,a.user_id,a.type,a.first_name,a.last_name,a.title,a.company,a.comments,a.company_name,a.timestamp,a.picture,a.directory  from ".TBL_CONTACT." a, ".TBL_ELEMENT_PERMISSION.
			" b where (a.first_name LIKE '$pattern%' OR a.last_name LIKE '$pattern%' or 
			a.company_name  LIKE '$pattern%') and (b.module='TBL_CONTACT' and b.module_id=a.contact_id ) 
			and ( (((b.access_to in $this->groups and (b.access_to_type='TBL_USERGROUP' or b.access_to_type='*')) or (b.access_to='$this->user_name' and b.access_to_type='TBL_USER')) 
			and (access_type='FULL' or access_type='VIEWONLY')) or  a.user_id='$this->user_id') order by a.last_name , a.company_name ";
		*/
                 /*$sql="select DISTINCT a.contact_id,a.user_id,a.type,a.first_name,a.last_name,a.title,a.company,a.comments,a.company_name,a.timestamp,a.picture,a.directory,c.company_name  from contacts a 
                 
LEFT JOIN platform_coulee.tbl_element_permission  b ON a.contact_id = b.module_id LEFT JOIN platform_coulee.contacts c ON a.company = c.contact_id
WHERE  b.access_to_type='TBL_USERGROUP' 
AND ( b.access_type='FULL' or b.access_type='VIEWONLY' or a.user_id='41' ) 
AND ( a.first_name LIKE '%co%' OR a.last_name LIKE '%co%' OR a.company_name LIKE '%co%' OR c.company_name LIKE '%co%')
order by a.last_name , a.company_name"; */
                /*
                $sql="select DISTINCT a.contact_id,a.user_id,a.type,a.first_name,a.last_name,a.title,a.company,a.comments,a.company_name,a.timestamp,a.picture,a.directory  from ".TBL_CONTACT." a, ".TBL_ELEMENT_PERMISSION.
			" b where $WHERE (b.module='TBL_CONTACT' and b.module_id=a.contact_id ) 
			and ( (((b.access_to in $this->groups and (b.access_to_type='TBL_USERGROUP' or b.access_to_type='*')) or (b.access_to='$this->user_name' and b.access_to_type='TBL_USER')) 
			and (access_type='FULL' or access_type='VIEWONLY')) or  a.user_id='$this->user_id') order by a.last_name , a.company_name ";
                
                */
                //echo $sql;
                
		$allRecords=$this->db->record_number($sql);
		$record=$this->db->pagination($sql, $this->GetPagelength(), $page);
		if($pattern!='' and $list=='' and $type=='') {
		?>
		<div class="heading"><?php echo $allRecords; ?> Contact Matching <?php echo "'$pattern'"; ?></div>
		<?php
		 }
		  if($list=='' and $type=='') {
		?><div class="form_main"><div align="right" class="cf-pagination"><?php $this->db->DisplayAjaxPage($this->GetPagelength(),$page,$allRecords,$obj)?></div></div>
		<?php
		}
		while($rows=$this->db->fetch_array($record))
		{  
		   if($list=='' and $type=='') {
/*		   $temp_sql_em="select * from ".EM_WEB_APP_INFO." where contact_id='$rows[contact_id]'";
			$temp_recrod_em=$this->db->query($temp_sql_em, __FILE__, __LINE__);
			$temp_rows_em=$this->db->fetch_array($temp_recrod_em);*/
			?>
			<div class="contact_match">
			<div class="label">	
			<a  onclick="<?php echo $this->dynamic_page->phplivex_subpage_link('contacts','contact_profile','display_contact_area',array('contact_id'=> $rows['contact_id'])); ?>"  >
			
			</a></div>
			<div class="field">
			<a  onclick="<?php echo $this->dynamic_page->phplivex_subpage_link('contacts','contact_profile','display_contact_area',array('contact_id'=> $rows['contact_id'])); ?>"  >
			<?php /*if($rows['type']=='People') {  ?>
				<div class="heading bcolor"><?php echo $rows['first_name'].' '.$rows['last_name'] ?></div>
			<?php }else { */?>
				<div class="heading bcolor"><?php echo $rows['company_name']; ?></div>
			<?php //} ?></a>
			<?php
			$temp_sql="select * from ".CONTACT_EMAIL." where contact_id='$rows[contact_id]'";
			$temp_recrod=$this->db->query($temp_sql, __FILE__, __LINE__);
			$temp_rows=$this->db->fetch_array($temp_recrod);
			?>
			<div><a href="<?php echo 'mailto:'.$temp_rows['email']; ?>"><?php echo $temp_rows['email']; ?></a></div>
			<?php 
			$temp_sql="select * from ".CONTACT_PHONE." where contact_id='$rows[contact_id]'";
			$temp_recrod=$this->db->query($temp_sql, __FILE__, __LINE__);
			$temp_rows=$this->db->fetch_array($temp_recrod);
			?>
			<div class="clear">
			<?php if($temp_rows['number']!='') {
			if($temp_rows['type']!=Mobile and $temp_rows['type']!='Cell')
			echo '('.substr($temp_rows['number'], 0, 3).')'.substr($temp_rows['number'], 3, 3).'-'.substr($temp_rows['number'], 6, 4).' ('.$temp_rows['type'].')';
			else
			echo $temp_rows['number'].' ('.$temp_rows['type'].')';
			}?>
			</div> </div>
			<div class="dis">
			<a onclick="<?php echo $this->dynamic_page->phplivex_subpage_link('contacts','contact_profile','display_contact_area',array('contact_id'=> $rows['contact_id'])); ?>"><?php echo $rows['title']; ?></a>
			<?php if(trim($this->GetCompany_name($rows['company']))!='') { 
			echo ' at '.$this->GetCompany_name($rows['company']);
			}
			?>
			<div class="verysmall_text">
			<ul class="link_list" style="display:inline">
			<?php echo $this->ShowTags($obj, 'TBL_CONTACT', $rows[contact_id], '');  ?>
			</ul>
			</div>
			<?php
			$temp_sql="select * from ".CONTACT_EMAIL." where contact_id='$rows[contact_id]'";
			$temp_recrod=$this->db->query($temp_sql, __FILE__, __LINE__);
			$temp_rows=$this->db->fetch_array($temp_recrod);
			
			
			?>			
			</div>			
			</div>
			<?php 
		}
		else if($list!='')
			{
				?>
				<li onclick="window.location='contact_profile.php?contact_id=<?php echo $rows[contact_id]; ?>';" on>
				<?php 
				if($rows[picture]!='') { ?>
				<img src="thumb.php?file=<?php echo $rows[directory].'/'.$rows[picture]; ?>&size=40" 
				 alt=""  border="0"  align="absmiddle"/>
				<?php }
				else { ?>
				<img src="thumb.php?file=images/person.gif&size=40" alt="" border="0"  align="absmiddle"/>
				<?php } ?>
				<a onclick="<?php echo $this->dynamic_page->phplivex_subpage_link('contacts','contact_profile','display_contact_area',array('contact_id'=> $rows['contact_id'])); ?>"  >
				<?php if($rows['type']=='People') { 
						echo $rows['first_name'].' '.$rows['last_name'];
						}else { echo $rows['company_name']; } ?></a>
				</li>
				<?php
			}
			else if($type!='' and $rows[type]=='Company')
			{
				$suggestions[]=$rows[company_name];
				$data[]=$rows[company_name];
			}
		}
		
			if($list=='' and $type=='') {?><div class="contact_match"><div align="right" class="cf-pagination"><?php $this->db->DisplayAjaxPage($this->GetPagelength(),$page,$allRecords,$obj)?></div></div>
		<?php }
		
		if($type!='') {
		echo "suggestions:['".@implode("','",$suggestions)."'],";
		echo "data:['".@implode("','",$data)."']";
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	

	
	function GetContactProfile($contact_id)
	{
		ob_start();
		$this->contact_id=$contact_id;
		$sql="select * from ".TBL_CONTACT." where contact_id='$this->contact_id'";
		$recrod=$this->db->query($sql, __FILE__, __LINE__);
		$rows=$this->db->fetch_array($recrod);
		if($rows['type']=='People')
			{
				?>
				<h5><?php echo 'Contact '.$rows['first_name'].' '.$rows['last_name'];?></h5>	
				<?php 
			} 
			else
			{
				?>
				<h5><?php echo 'Contact '.$rows['company_name']; ?></h5>
				<?php
			}
			?>
			<table>
			<?php
				$temp_sql="select * from ".CONTACT_PHONE." where contact_id='$this->contact_id'";
				$temp_recrod=$this->db->query($temp_sql, __FILE__, __LINE__);
				while($temp_rows=$this->db->fetch_array($temp_recrod))
				{			
				if($temp_rows['number']!="") 
				{
				?>
				<tr><td class="label">phone:</td>
				<td class="data">
				<?php 
				if($temp_rows['type']!='Mobile' and $temp_rows['type'] !='Cell')
				echo '('.substr($temp_rows[number], 0, 3).')'.substr($temp_rows[number], 3, 3).'-'.substr($temp_rows[number], 6, 4).' ('.$temp_rows['type'].')'; 
				else 
				echo $temp_rows[number].' ('.$temp_rows['type'].')';
				?>
				</td>
				</tr>
				<?
				}
				}					
				?>
				
				
				<?php 
					$temp_sql="select * from ".CONTACT_EMAIL." where contact_id='$this->contact_id'";
					$temp_recrod=$this->db->query($temp_sql, __FILE__, __LINE__);
					while($temp_rows=$this->db->fetch_array($temp_recrod))
					{
				?>
					<tr><td class="label">email:</td>
						<td class="data"><a href="<?php echo 'mailto:'.$temp_rows['email']; ?>"><?php echo $temp_rows['email']; ?></a>&nbsp;(<?php echo $temp_rows['type']; ?>)</td>
					</tr>
					
				<?php 
					}
					$temp_sql="select * from ".CONTACT_TWITTER." where contact_id='$this->contact_id'";
					$temp_recrod=$this->db->query($temp_sql, __FILE__, __LINE__);
					while($temp_rows=$this->db->fetch_array($temp_recrod))
					{
				?>
					<tr><td class="label">Twitter:</td>
					<td class="data"><?php echo $temp_rows['twitter']; ?>&nbsp;(<?php echo $temp_rows['type']; ?>)
					</td></tr>
					
				
				<?php 
					}
					$temp_sql="select * from ".CONTACT_WEBSITE." where contact_id='$this->contact_id'";
					$temp_recrod=$this->db->query($temp_sql, __FILE__, __LINE__);
					while($temp_rows=$this->db->fetch_array($temp_recrod))
					{
				?>
					<tr><td class="label">website:</td>&nbsp;
						<td class="data"><a href="<?php echo $temp_rows['website']; ?>" target="_blank"><?php echo $temp_rows['website']; ?></a>&nbsp;(<?php echo $temp_rows['type']; ?>)</td>
					</tr>
					
				<?php 
					}
					$temp_sql="select * from ".CONTACT_ADDRESS." where contact_id='$this->contact_id'";
					$temp_recrod=$this->db->query($temp_sql, __FILE__, __LINE__);
					while($temp_rows=$this->db->fetch_array($temp_recrod))
					{
				?>
					<tr><td class="label">address:</td>
						<td class="data"><?php echo $temp_rows['street_address'].' '.$temp_rows['city'].' '.$temp_rows['state'].' '.$temp_rows['zip'].' '.$temp_rows['country'] ?>
									&nbsp;(<?php echo $temp_rows['type']; ?>)
						</td>
					</tr>
				<?php
					}
					echo $this->GetCompanyAddress($this->contact_id);
					?>
			</table>
			<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}


	   function Get_Company($company_id)
		{	ob_start();
			$sql="select * from ".TBL_CONTACT." where type='Company'";
			$result=$this->db->query($sql,__FILE__,__LINE__);
			while($row=$this->db->fetch_array($result))
			{				
				?>
				<option value="<?php echo $row['contact_id'];?>" <?php if($row['contact_id']==$company_id) { echo 'selected="selected"'; }?> ><?php echo $row['company_name'] ?></option>  
				<?php
			}

			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}
	
		function GetCompany_name($company_id,$textonly='')
		{	ob_start();
			$sql="select * from ".TBL_CONTACT." where type='Company' and contact_id='$company_id'";
			$result=$this->db->query($sql,__FILE__,__LINE__);
			if($this->db->num_rows($result)>0){
			$row=$this->db->fetch_array($result);
			if($textonly==''){
				?>
				<a href="contact_profile.php?contact_id=<?php echo $row['contact_id'];?>" ><?php echo $row['company_name'] ?></a> 
				<?php
			}
			else echo $row['company_name'];
			}
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}
	
		function GetCompanyAddress($contact_id)
		{
			ob_start();
		
			$sql="select * from ".TBL_CONTACT." where contact_id='$contact_id'";
			$recrod=$this->db->query($sql, __FILE__, __LINE__);
			$rows=$this->db->fetch_array($recrod);
			if($rows['type']!='Company')
			{					
						$temp_sql="select a.street_address,a.city,a.state,a.zip,a.country,a.type,b.contact_id,a.contact_id from ".
									CONTACT_ADDRESS." a, ".TBL_CONTACT." b where a.contact_id='$rows[company]' and a.contact_id=b.contact_id";
						$temp_recrod=$this->db->query($temp_sql, __FILE__, __LINE__);
						while($temp_rows=$this->db->fetch_array($temp_recrod))
						{
					?>
						<tr><td class="label">address:</td>
							<td class="data"><?php echo $temp_rows['street_address'].' '.$temp_rows['city'].' '.$temp_rows['state'].' '.$temp_rows['zip'].' '.$temp_rows['country'] ?>
											&nbsp;(<?php echo $temp_rows['type']; ?>@company)
							</td>
						</tr>
					<?php
						}
			}
			/*********************************/
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}
		
		function GetPeopleInCompnay($contact_id,$obj='',$image='')
		{
			ob_start();
                        $dynamic_page = new dynamic_page();
			if($_REQUEST[contact_id] && $_REQUEST[cont_id]){
			     if($_REQUEST['editbtnSubmit']=='Submit'){
					echo $this->editContactsInCompany('server','em',$_REQUEST['cont_id'],$_REQUEST['contact_id']); 
				  }
				  else{
					echo $this->editContactsInCompany('local','em',$_REQUEST['cont_id'],$_REQUEST['contact_id']); 
				  }
			 }
             else{
				if($image == 'no_image') {
					$sql="select * from ".TBL_CONTACT." where contact_id='$contact_id'";
					$recrod=$this->db->query($sql, __FILE__, __LINE__);
					$rows=$this->db->fetch_array($recrod);
					
					if($rows['type']=='Company'){
						$temp_sql="select * from ".TBL_CONTACT." a where a.company='$rows[contact_id]' ";
						$temp_recrod=$this->db->query($temp_sql, __FILE__, __LINE__);
						?>
						<h5>Contacts in this company:</h5>					
						<table width="100%" class="contact_match">
						<?php
						while($temp_rows=$this->db->fetch_array($temp_recrod)){
						?>
						<tr >
							<td class="contact_match">
								<div>
									<h5><a onclick="<?php echo $dynamic_page->phplivex_subpage_link('contacts', 'edit', 'display_contact_area', array('contact_id' => $temp_rows['contact_id'] ) ); ?>" ><?php echo ucfirst($temp_rows[first_name])." ".ucfirst($temp_rows[last_name]);?></a>
									&nbsp;<a href="<?php echo $_SERVER['PHP_SELF']; ?>?contact_id=<?php echo $contact_id; ?>&cont_id=<?php echo $temp_rows[contact_id]; ?>">
												   
									</a>
									<a href="javascript:void(0);" 
										onclick="if(confirm('Are you sure ?')){	
											 javascript:<?php echo $obj; ?>.deleteContact('<?php echo $contact_id; ?>',
																						  '<?php echo $obj; ?>',
																						  '<?php echo $temp_rows[contact_id] ?>',
																						  { preloader: 'prl',
																						  onUpdate: function(response,root){
																						  <?php echo $obj; ?>.GetPeopleInCompnay(
																													'<?php echo $contact_id; ?>',
																													'<?php echo $obj; ?>',
																													'no_image',
																													{target:'div_contacts_in_company',
																													 preloader:'prl'}); }});
									} else return false;">
									<img src="images/trash.gif" border="0" /></a>																						  
									<br />
									<?php echo $temp_rows["title"]; ?></h5>
									<?php 
									$phone_sql="select * from ".CONTACT_PHONE." where contact_id='$temp_rows[contact_id]'";
									$phone_record=$this->db->query($phone_sql, __FILE__, __LINE__);
									if($this->db->num_rows($phone_record)>0) {
									?>
									phone:
									<?php 	
									}							
									while($phone_rows=$this->db->fetch_array($phone_record)){			
										if($phone_rows['number']!=""){
										echo '('.substr($phone_rows[number], 0, 3).')'.substr($phone_rows[number], 3, 3).'-'.substr($phone_rows[number], 6, 4).' ('.$phone_rows['type'].')'; 
										echo '<br>';
										}
									}
									$mail_sql="select * from ".CONTACT_EMAIL." where contact_id='$temp_rows[contact_id]'";
									$mail_record=$this->db->query($mail_sql, __FILE__, __LINE__);
									if($this->db->num_rows($mail_record)>0) {
									?>
									<br />email:
									<?php 	
									}							
									while($mail_rows=$this->db->fetch_array($mail_record)){	
										if($mail_rows['email']!=""){ ?>
										<a href="mailto:"><?php echo $mail_rows['email'].' ('.$mail_rows['type'].')'; ?></a>
										<?php
										echo '<br>';
										}
									}
                                                                        $web_sql="select * from contacts_website where contact_id='$temp_rows[contact_id]'";
									$web_record=$this->db->query($web_sql, __FILE__, __LINE__);
									if($this->db->num_rows($web_record)>0) {
									?>
									<br />websites:
									<?php 	
									}							
									while($web_rows=$this->db->fetch_array($web_record)){	
										if($web_rows['website']!=""){ ?>
										<a href="<?php echo $web_rows['website']; ?>"><?php echo $web_rows['website'].' ('.$mail_rows['type'].')'; ?></a>
										<?php
										echo '<br>';
										}
									}
									?>
								</div>
							</td>
						</tr>
						<?php
						}
					?><br />
					</table>
					<?php
					} 
				}
		    else {
				$sql="select * from ".TBL_CONTACT." where contact_id='$contact_id'";
				$recrod=$this->db->query($sql, __FILE__, __LINE__);
				$rows=$this->db->fetch_array($recrod);
				
				if($rows['type']=='Company'){
					$temp_sql="select * from ".TBL_CONTACT." a where a.company='$rows[contact_id]' ";
					$temp_recrod=$this->db->query($temp_sql, __FILE__, __LINE__);
					?>
					<div class="data_box form_main">
					<h5>Contacts in this company:</h5>
					<table width="100%" class="contact_match">
					<?php
					while($temp_rows=$this->db->fetch_array($temp_recrod)){
					?>
					<tr>
						<td colspan="2"  align="left" class="contact_match">
							<a href="<?php echo $_SERVER['PHP_SELF'] ?>?contact_id=<?php echo $temp_rows[contact_id] ?>">
							<?php 
								if($temp_rows[picture]!='') { ?>
								<img src="thumb.php?file=<?php echo $temp_rows[directory].'/'.$temp_rows[picture]; ?>&size=40" 
								 alt=""  border="0"  align="absmiddle"/>
								<?php }
								else { ?>
								<img src="thumb.php?file=images/person.gif&size=40" alt="" border="0"  align="absmiddle"/>
								<?php } 
								echo ucfirst($temp_rows[first_name])." ".ucfirst($temp_rows[last_name]); ?>
							</a>
						</td>
					</tr>
					<tr>
						<td><?php echo $temp_rows["title"]; ?></td>
					</tr>
					<?php
					}
				?>
				</table>
				</div>
				<?php
				} 
			}
		}			
		$html = ob_get_contents();
		ob_end_clean();
		return $html;		
		}	
	
	function deleteContact($contact_id='',$obj='',$selected_contact_id=''){
		ob_start();

		$update_sql_array = array();
		$update_sql_array[company] = '';
		$this->db->update(TBL_CONTACT,$update_sql_array,"contact_id",$selected_contact_id);		
		?>
<?php /*?>		<script>
		<?php echo $obj; ?>.GetPeopleInCompnay('<?php echo $contact_id; ?>',
											   '<?php echo $obj; ?>',
											   'no_image',
											   {target:'div_contacts_in_company',preloader:'prl'});
		</script><?php */?>
		<?php		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;			
	}
	
	function DisplayContact($module,$module_id)
	{
		ob_start();
	
		$sql="select * from ". TBL_CONTACT. " where contact_id=$module_id";
		
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0)
		{
		$rows=$this->db->fetch_array($record);
		?>
			<div class="contact_match">
			<div class="label">	
			<a onclick="<?php echo $this->dynamic_page->phplivex_subpage_link('contacts','contact_profile','display_contact_area',array('contact_id'=> $rows['contact_id'])); ?>"  >
			<?php 
			if($rows[picture]!='') { 
		$directory = $rows[directory];
		$pic = $rows[picture]; 
		$path= $directory."/".$pic;
		$flag = false;
			 if( file_exists( $path ) )
			 {
				  $flag = true;
			 }
			if( $flag == true )
			{
			 ?>
			 <img src="thumb.php?file=<?php echo $rows[directory].'/'.$rows[picture]; ?>&size=50" 
		 alt="Current Picture"  class="image_border" />
			 
			 <?php 
			}	 
			else
			{
			?>
			<img src="images/person.gif"  alt="Current Picture"  class="image_border" />
			<?php
			}
		}
			else { ?>
			<img src="images/person.gif"  alt="Current Picture"  class="image_border" />
			<?php } ?>
			</a></div>
			<div class="field">
			<a onclick="<?php echo $this->dynamic_page->phplivex_subpage_link('contacts','contact_profile','display_contact_area',array('contact_id'=> $rows['contact_id'])); ?>"  >
			<? if($rows['type']=='People') { ?>
				<div class="heading bcolor"><?php echo $rows['first_name'].' '.$rows['last_name'] ?></div>
			<? }else { ?>
				<div class="heading bcolor"><?php echo $rows['company_name']?></div>
			<? } ?></a>
			<?php
			$temp_sql="select * from ".CONTACT_EMAIL." where contact_id='$rows[contact_id]'";
			$temp_recrod=$this->db->query($temp_sql, __FILE__, __LINE__);
			$temp_rows=$this->db->fetch_array($temp_recrod);
			?>
			<div><a href="<?php echo 'mailto:'.$temp_rows['email']; ?>"><?php echo $temp_rows['email']; ?></a></div>
			<?php 
			$temp_sql="select * from ".CONTACT_PHONE." where contact_id='$rows[contact_id]'";
			$temp_recrod=$this->db->query($temp_sql, __FILE__, __LINE__);
			$temp_rows=$this->db->fetch_array($temp_recrod);
			?>
			<div class="clear">
			<?php if($temp_rows['number']!='') {echo $temp_rows['number'].' ('.$temp_rows['type'].')'; }?>
			</div> </div>
			<div class="dis">
			<a onclick="<?php echo $this->dynamic_page->phplivex_subpage_link('contacts','contact_profile','display_contact_area',array('contact_id'=> $rows['contact_id'])); ?>"><?php echo $rows['title']; ?></a>
			<?php if(trim($this->GetCompany_name($rows['company']))!='') { 
			echo ' at '.$this->GetCompany_name($rows['company']);
			}
			
			?>
			<div class="verysmall_text">
			<img class="tags_thumb" alt="" src="images/tag_icon.png"  align="absmiddle"/>
			<ul class="link_list" style="display:inline;">
			<?php echo $this->ShowTags('', $module, $module_id, ''); ?>
			</ul></div>
			</div>
			</div>
			<?php
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function RemoveContact($contact_id)
	{	

		$sql="delete from ".TBL_CONTACT." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);
		
		$sql="delete from ".CONTACT_PHONE." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);
		
		$sql="delete from ".CONTACT_EMAIL." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);
		
		$sql="delete from ".CONTACT_IM." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);
		
		$sql="delete from ".CONTACT_WEBSITE." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);
		
		$sql="delete from ".CONTACT_TWITTER." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);
		
		$sql="delete from ".CONTACT_ADDRESS." where contact_id=$contact_id ";
		$this->db->query($sql,__FILE__,__LINE__);	
		
		$_SESSION['msg']='Contact has been Successfully removed';
		?>
		<script type="text/javascript">
		window.location="contacts.php";
		</script>
		<?php
	}
	
    // function Addcontact_On_Fly
	function Addcontact_On_Fly($contact_info,$tag,$return=false)
	{
		switch($contact_info['type']){
				case 'Company':
				$insert_sql_array = array();
				$insert_sql_array['company_name'] = $contact_info['name'];
				$insert_sql_array['type'] = $contact_info['type'];
				
				$this->db->insert(TBL_CONTACT,$insert_sql_array);
				$this->contact_id=$this->db->last_insert_id();
				
				if($contact_info['phone']!=''){
					$insert_sql_array = array();
					$insert_sql_array['contact_id'] = $this->contact_id;
					$insert_sql_array['number'] = $contact_info['phone'];
					$insert_sql_array['type'] = 'Work';
					$this->db->insert(CONTACT_PHONE,$insert_sql_array);
				}

				$insert_sql_array = array();
				$insert_sql_array['contact_id'] = $this->contact_id;
				$insert_sql_array['email'] = $contact_info['email'];
				$insert_sql_array['type'] = 'Work';
				$this->db->insert(CONTACT_EMAIL,$insert_sql_array);
				
				//////security setting /////////////////
				$this->security->SetModule_name($this->module);
				$this->security->SetModule_id($this->contact_id);
				$this->security->Add_Rule_Webform('server');
				///////////////////////////////////////

				
				if($return) return $this->contact_id;
				
				
					break;
					
			case 'People' :
				
				$insert_sql_array = array();
				$insert_sql_array['first_name'] = $contact_info['name'];
				$insert_sql_array['title'] = $contact_info['eln'];
				$insert_sql_array['type'] = $contact_info['type'];
				$insert_sql_array['user_id'] = $contact_info['user_id'];
				$insert_sql_array['last_name'] = $contact_info['lname'];

				$this->db->insert(TBL_CONTACT,$insert_sql_array);
				$this->contact_id=$this->db->last_insert_id();


				if($contact_info['phone']!=''){
				  if(count($contact_info['phone'])>0){
				    foreach($contact_info['phone'] as $key=>$value){
					  $insert_sql_array = array();
					  $insert_sql_array['contact_id'] = $this->contact_id;
					  $insert_sql_array['number'] = $value['number'];
					  $insert_sql_array['type'] = $value['type'];
					  $this->db->insert(CONTACT_PHONE,$insert_sql_array);
					}
				  } else {
					$insert_sql_array = array();
					$insert_sql_array['contact_id'] = $this->contact_id;
					$insert_sql_array['number'] = $contact_info['phone'];
					$insert_sql_array['type'] = 'Work';
					$this->db->insert(CONTACT_PHONE,$insert_sql_array);
				  }
				}

				if($contact_info['email']!=''){
				  if(count($contact_info['email'])>0){
				    foreach($contact_info['email'] as $key=>$value){
					  $insert_sql_array = array();
					  $insert_sql_array['contact_id'] = $this->contact_id;
					  $insert_sql_array['email'] = $value['email'];
					  $insert_sql_array['type'] = $value['type'];
					  $this->db->insert(CONTACT_EMAIL,$insert_sql_array);
					}
				  } else {
					$insert_sql_array = array();
					$insert_sql_array['contact_id'] = $this->contact_id;
					$insert_sql_array['email'] = $contact_info['email'];
					$insert_sql_array['type'] = 'Work';
					$this->db->insert(CONTACT_EMAIL,$insert_sql_array);
				  }
				}

				if($contact_info['note']!=''){
				  if(count($contact_info['note'])>0){
				    foreach($contact_info['note'] as $key=>$value){
					  $insert_sql_array = array();
					  $insert_sql_array['module_id'] = $this->contact_id;
					  $insert_sql_array['user_id'] = $value['user_id'];
					  $insert_sql_array['description'] = $value['description'];
					  $insert_sql_array['module_name'] = $value['module_name'];
					  $this->db->insert(TBL_NOTE,$insert_sql_array);
					}
				  } else {
					$insert_sql_array = array();
					$insert_sql_array['module_id'] = $this->contact_id;
					$insert_sql_array['user_id'] = $contact_info['user_id'];
					$insert_sql_array['description'] = $contact_info['description'];
					$this->db->insert(TBL_NOTE,$insert_sql_array);
				  }
				}

				$tag_id=$this->AddTagOnFly($tag);
					
				$this->ApplyAnExistingTag('TBL_CONTACT',$this->contact_id,$tag_id);

				$this->security->SetModule_name($this->module);
				$this->security->SetModule_id($this->contact_id);
				$this->security->Add_Rule_Webform('server');


				if($return) return $this->contact_id;
				break;
				default : echo 'Wrong Paramemter passed';
			}	
	}
	
	
	
		/**************Import CSV************************/
	
			function ImportContacts($runat)//import jobs
			{
 
			switch($runat){
			case 'local':
						$FormName = "frm_ImportJobs";						
			?>
			<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName;?>">
			<table width="100%" class="table">
			<tr><td >	
				<ul>
				<li><span id="spanmy_file" class="normal"></span></li>
				</ul>
			</td></tr>			
			<tr><td>
			<input type="file" name="my_file" id="my_file" value="" size="40" />
			</td>
			</tr>
			<tr>
			<td>
			<input name="submit" id="submit" type="submit" style="width:auto;" onclick="return validateExtension('<?php echo $FormName;?>','my_file');" value="Upload" />
			
			</td>
			</tr></table>
			</form>
			<?php
			
			break;
			
			case 'server':
			
			extract($_POST);
			/*$return =true;
			if($this->Form->ValidField($my_file,'empty','File field is Empty or Invalid')==false)
				$return =false;
			if($return){*/
			$tmpname = 'tmpname.csv';
			$handle = fopen($_FILES['my_file']['tmp_name'],'r');
			move_uploaded_file($_FILES["my_file"]["tmp_name"],"csv-upload/".$tmpname);
	  		
			
			$row_=0;
			$row_cnt=0;
			$i=0;
			$j=0;
			while (($data = fgetcsv($handle, 10000, ",")) != FALSE)
			{
				
			 if($i==0)
			  {
			  	$head= array();
				$head= $data;
				
								
			  }// end of if
			  else
			  {
			  		        ?>
							<form method="post" action="" enctype="multipart/form-data" name="frm_Importcvs" id="frm_Importcvs" >
							<input type="hidden" value="<?php echo "csv-upload/".$tmpname;?>" name="my_file" id="my_file" />
							<table width="100%">
							<?php
							$n= count($head);
                        	for($z=0;$z<$n;$z++)
							{
							if(!($head[$j]=='')){
							?>
							<tr><td width="40%">
							<select name="map[]" id="sel1">
							<option value="">NO MATCH, SELECT</option>
							<option value="">Do not import this field</option>
							<option value="TBL_CONTACT.title">Title</option>
							<option value="TBL_CONTACT.first_name">First name</option>
							<option value="TBL_CONTACT.last_name">Last name</option>
							<option value="TBL_CONTACT.company">Company</option>
							<option value="TBL_CONTACT.company_name">Company name</option>
							
                            <optgroup label="Work Address">
							<option value="CONTACT_ADDRESS.street_address.Work">Street Address</option>
							<option value="CONTACT_ADDRESS.city.Work">Work city</option>
							<option value="CONTACT_ADDRESS.state.Work">Work state</option>
							<option value="CONTACT_ADDRESS.zip.Work">Work zip/postal code</option>
							<option value="CONTACT_ADDRESS.country.Work">Work country</option>
                            </optgroup>
							
							<optgroup label="Home Address">
       						<option value="CONTACT_ADDRESS.street_address.Home">Street Address</option>
							<option value="CONTACT_ADDRESS.city.Home">Home city</option>
							<option value="CONTACT_ADDRESS.state.Home">Home state</option>
							<option value="CONTACT_ADDRESS.zip.Home">Home zip/postal code</option>
							<option value="CONTACT_ADDRESS.country.Home">Home country</option>
							</optgroup>
							
                            <optgroup label="Phone">
							<option value="CONTACT_PHONE.number.Home">Home phone</option>
							<option value="CONTACT_PHONE.number.Work">Work phone</option>
							<option value="CONTACT_PHONE.number.Mobile">Mobile</option>
							<option value="CONTACT_PHONE.number.Fax">Fax</option>
							<option value="CONTACT_PHONE.number.Pager">Pager</option>
						    </optgroup>
                            
							<optgroup label="Email Address">
							<option value="CONTACT_EMAIL.email.Work">Work email</option>
							<option value="CONTACT_EMAIL.email.Personal">Personal email</option>
							</optgroup>
                            
							<optgroup label="Instant Messenger">
							<option value="CONTACT_IM.im.Work">Work IM</option>
							<option value="CONTACT_IM.im_network.Work">Work IM Network</option>
							<option value="CONTACT_IM.im.Personal">Personal IM</option>
							<option value="CONTACT_IM.im_network.Personal">Personal IM Network</option>
							</optgroup>
                            
							<optgroup label="Web Address">
							<option value="CONTACT_WEBSITE.website.Work">Work web site</option>
							<option value="CONTACT_WEBSITE.website.Personal">Personal web site</option>
							</optgroup>
							</select>
							
							</td><td width="60%">
							<?php echo $head[$j];?>
                            </td>
							</tr>
							<?php }
							$j++;
							} // end of for loop
							
					        ?></table><?php
							
					
		  	  }// end of else
			
			$i++;
			}// end of while
			$this->security->Add_Rule_Webform('local');
			?><br />
			<input type="submit" name="s1" id="s1" value="Submit CSV" />
			</form>
			<?php	
			/*} else {
				echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
				$this->ImportContacts('local');
			}*/
			break;
			
			case 'csvupld':
				 
				 $mapping = array();
				 $mapping = $_REQUEST['map'];
				 $handle = fopen($_POST['my_file'],'r');
				 $i=0;
			     $j=0;
			  while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)
			  { 
						if($i==0)
						{
							$head= array();
							$head= $data;
						}// end of if
					  else
					  {
						 $n = count($head);
						 $insert_sql_array_contact = array();
						 $insert_sql_array_contact_work = array();
						 $insert_sql_array_contact_home = array();
						 $insert_sql_array_phone_work = array();
						 $insert_sql_array_phone_home = array();
						 $insert_sql_array_phone_mobile = array();
						 $insert_sql_array_phone_fax = array();
						 $insert_sql_array_phone_pager = array();
						 $insert_sql_array_email_work = array();
						 $insert_sql_array_email_personal = array();
						 $insert_sql_array_im_work = array();
						 $insert_sql_array_im_personal = array();
						 $insert_sql_array_website_work = array();
						 $insert_sql_array_website_personal = array();
						 
						 for($k=0;$k<$n;$k++)
						 {
							$val = $mapping[$k];
							$ar= explode(".",$val);
																					
							if($ar[0] == 'TBL_CONTACT')
							{	
								$c = $ar[1];
								if($c=='company'){
								} else if($c=='company_name'){
									$company_id=$this->GetValidCompany($data[$k]);
									$insert_sql_array_contact['company'] = $company_id;
									$insert_sql_array_contact[$c] = $data[$k];
								} else
								$insert_sql_array_contact[$c] = $data[$k];
							}
							
							elseif($ar[0]== 'CONTACT_ADDRESS' && $ar[2]== 'Work')
							{
								$c = $ar[1];
								$insert_sql_array_contact_work[$c] = $data[$k];
			
							}
							
							elseif($ar[0]== 'CONTACT_ADDRESS' && $ar[2]== 'Home')
							{
								$c = $ar[1];
								$insert_sql_array_contact_home[$c] = $data[$k];
			
							}
												
							elseif($ar[0]== 'CONTACT_PHONE' && $ar[2]== 'Work')
							{
								$c = $ar[1];
								$insert_sql_array_phone_work[$c] = $data[$k];

							}
							
							elseif($ar[0]== 'CONTACT_PHONE' && $ar[2]== 'Home')
							{
								$c = $ar[1];
								$insert_sql_array_phone_home[$c] = $data[$k];

							}
							
							elseif($ar[0]== 'CONTACT_PHONE' && $ar[2]== 'Mobile')
							{
								$c = $ar[1];
								$insert_sql_array_phone_mobile[$c] = $data[$k];

							}

							elseif($ar[0]== 'CONTACT_PHONE' && $ar[2]== 'Fax')
							{
								$c = $ar[1];
								$insert_sql_array_phone_fax[$c] = $data[$k];

							}

							elseif($ar[0]== 'CONTACT_PHONE' && $ar[2]== 'Pager')
							{
								$c = $ar[1];
								$insert_sql_array_phone_pager[$c] = $data[$k];

							}

							elseif($ar[0]== 'CONTACT_EMAIL' && $ar[2]== 'Personal')
							{
								$c = $ar[1];
								$insert_sql_array_email_personal[$c] = $data[$k];

							}
							
							elseif($ar[0]== 'CONTACT_EMAIL' && $ar[2]== 'Work')
							{
								$c = $ar[1];
								$insert_sql_array_email_work[$c] = $data[$k];
	
							}
							
							elseif($ar[0]== 'CONTACT_IM' && $ar[2]== 'Work')
							{
								$c = $ar[1];
								$insert_sql_array_im_work[$c] = $data[$k];

							}																						
							
							elseif($ar[0]== 'CONTACT_IM' && $ar[2]== 'Personal')
							{
								$c = $ar[1];
								$insert_sql_array_im_personal[$c] = $data[$k];

							}	
							
							elseif($ar[0]== 'CONTACT_WEBSITE' && $ar[2]== 'Work')
							{
								$c = $ar[1];
								$insert_sql_array_website_work[$c] = $data[$k];

							}
							
							elseif($ar[0]== 'CONTACT_WEBSITE' && $ar[2]== 'Personal')
							{
								$c = $ar[1];
								$insert_sql_array_website_personal[$c] = $data[$k];
	
							}

							
							elseif($ar[0]== '')
							{
														
							}																						
										
						 }// end of for
						 
						 $insert_sql_array_contact['type'] = 'People';
						 $insert_sql_array_contact['user_id'] = $this->user_id;
						 $insert_sql_array_contact_work['type'] = 'Work';
						 $insert_sql_array_contact_home['type'] = 'Home';
						 $insert_sql_array_phone_work['type'] = 'Work';	
						 $insert_sql_array_phone_home['type'] = 'Home';	
						 $insert_sql_array_phone_mobile['type'] = 'Mobile';	
						 $insert_sql_array_phone_fax['type'] = 'Fax';	
						 $insert_sql_array_phone_pager['type'] = 'Pager';	
						 $insert_sql_array_email_personal['type'] = 'Home';
						 $insert_sql_array_email_work['type'] = 'Work';
						 $insert_sql_array_im_work['type'] = 'Work';
						 $insert_sql_array_im_personal['type'] = 'Personal';
						 $insert_sql_array_website_work['type'] = 'Work';
						 $insert_sql_array_website_personal['type'] = 'Personal';
						 
						 if($insert_sql_array_contact['first_name'] != '' || $insert_sql_array_contact['last_name'] != '')
						 $this->db->insert(TBL_CONTACT,$insert_sql_array_contact);
						 
						 $this->contact_id=$this->db->last_insert_id();
						 
						 $insert_sql_array_contact_work['contact_id'] =  $this->contact_id;
						 $insert_sql_array_contact_home['contact_id'] = $this->contact_id;
						 $insert_sql_array_phone_work['contact_id'] = $this->contact_id;	
						 $insert_sql_array_phone_home['contact_id'] = $this->contact_id;	
						 $insert_sql_array_phone_mobile['contact_id'] = $this->contact_id;	
						 $insert_sql_array_phone_fax['contact_id'] = $this->contact_id;	
						 $insert_sql_array_phone_pager['contact_id'] = $this->contact_id;	
						 $insert_sql_array_email_personal['contact_id'] = $this->contact_id;
						 $insert_sql_array_email_work['contact_id'] = $this->contact_id;
						 $insert_sql_array_im_work['contact_id'] = $this->contact_id;
						 $insert_sql_array_im_personal['contact_id'] = $this->contact_id;
						 $insert_sql_array_website_work['contact_id'] = $this->contact_id;
						 $insert_sql_array_website_personal['contact_id'] = $this->contact_id;
						 

						
						 
						 if($insert_sql_array_contact_work['street_address'] != '' || $insert_sql_array_contact_work['city'] != ''  || $insert_sql_array_contact_work['state'] != ''  || $insert_sql_array_contact_work['zip'] != ''  || $insert_sql_array_contact_work['country'] != '')
						 $this->db->insert(CONTACT_ADDRESS,$insert_sql_array_contact_work);
						 
						  if($insert_sql_array_contact_home['street_address'] != '' || $insert_sql_array_contact_home['city'] != ''  || $insert_sql_array_contact_home['state'] != ''  || $insert_sql_array_contact_home['zip'] != ''  || $insert_sql_array_contact_home['country'] != '')
						 $this->db->insert(CONTACT_ADDRESS,$insert_sql_array_contact_home);
						 
						 if($insert_sql_array_phone_work['number'] != '')
						 $this->db->insert(CONTACT_PHONE,$insert_sql_array_phone_work);
						 
						 if($insert_sql_array_phone_home['number'] != '')
						 $this->db->insert(CONTACT_PHONE,$insert_sql_array_phone_home);
						 
						 if($insert_sql_array_phone_mobile['number'] != '')
						 $this->db->insert(CONTACT_PHONE,$insert_sql_array_phone_mobile);

						 if($insert_sql_array_phone_fax['number'] != '')
						 $this->db->insert(CONTACT_PHONE,$insert_sql_array_phone_fax);

						 if($insert_sql_array_phone_pager['number'] != '')
						 $this->db->insert(CONTACT_PHONE,$insert_sql_array_phone_pager);

						 if($insert_sql_array_email_personal['email'] != '')
						 $this->db->insert(CONTACT_EMAIL,$insert_sql_array_email_personal);
						 
						 if($insert_sql_array_email_work['email'] != '')
						 $this->db->insert(CONTACT_EMAIL,$insert_sql_array_email_work);
						 
						 if($insert_sql_array_im_work['im'] != '' || $insert_sql_array_im_work['im'] != '') 
						 $this->db->insert(CONTACT_IM,$insert_sql_array_im_work);
						 
						 if($insert_sql_array_im_personal['im'] != '' || $insert_sql_array_im_personal['im'] != '')
						 $this->db->insert(CONTACT_IM,$insert_sql_array_im_personal);
						 
						 if($insert_sql_array_website_work['website'] != '')
						 $this->db->insert(CONTACT_WEBSITE,$insert_sql_array_website_work);
						 
						 if($insert_sql_array_website_personal['website'] != '')
						 $this->db->insert(CONTACT_WEBSITE,$insert_sql_array_website_personal);
						 
						 
						 $this->security->SetModule_name($this->module);
						 $this->security->SetModule_id($this->contact_id);
						 $this->security->Add_Rule_Webform('server');
					   
					   
					  }// end of else
					  $i++;
				  }// end of while
					
				$_SESSION['msg'] = "Contacts Uploaded";
				?>
				<script type="text/javascript">
				window.location = "<?php echo $_SERVER['PHP_SELF'] ?>";
				</script>
				<?php	
						
			break;
			default : echo 'Wrong Paramemter passed';
			}// end of switch
		}	
	
	
	/**************End of Import CSV************************/
	
	/**************Export CSV************************/
	
	function ExportCsv($runat)
	{
		switch($runat)
		{
			case 'local':

					$temp_sql="select DISTINCT a.contact_id,a.user_id,a.type,a.first_name,a.last_name,a.title,a.company,a.comments,a.company_name,a.timestamp,a.picture,a.directory 
					 from ".TBL_CONTACT." a, ".TBL_ELEMENT_PERMISSION." b where (b.module='TBL_CONTACT' and b.module_id=a.contact_id ) and ( (((b.access_to in $this->groups and 
					 (b.access_to_type='TBL_USERGROUP' or b.access_to_type='*')) or (b.access_to='$this->user_name' and b.access_to_type='TBL_USER'))and (access_type='FULL' or
					  access_type='VIEWONLY')) or  a.user_id='$this->user_id') order by a.last_name , a.company_name ";
						
						$temp_recrod=$this->db->query($temp_sql, __FILE__, __LINE__);

								
					?>
					<form method="post" action="exportCsv.php" enctype="multipart/form-data" name="frm_Exportcvs" id="frm_Exportcvs" target="_blank" >
					<table class="table">
                    <tr><td><h2>Select contacts To Export</h2></td></tr>
					<?php
					while ($temp_rows=$this->db->fetch_array($temp_recrod))
					{
    					?>
						<tr><td><input type="checkbox" name="chk[]" id="chk" value="<?php echo $temp_rows['contact_id']; ?>" style="width:auto !important;" />&nbsp;
						<a href="contact_profile.php?contact_id=<?php echo $temp_rows[contact_id]; ?>"  >
						<?php 
							if($temp_rows[picture]!='') { ?>
							<img src="thumb.php?file=<?php echo $temp_rows[directory].'/'.$temp_rows[picture]; ?>&size=50" 
							 alt=""  class="image_border" />
							<?php }
							else { ?>
							<img src="images/person.gif"  alt=""  class="image_border" />
							<?php } ?>
							<?php if($temp_rows['type']=='People') { ?>
								<span class="heading bcolor"><?php echo $temp_rows['first_name'].' '.$temp_rows['last_name'] ?></span>
							<?php }else { ?>
								<span class="heading bcolor"><?php echo $temp_rows['company_name']?></span>
							<?php } 
						?></a>
                        </td></tr>
				<?php	}
				?>
                
                <tr><td><input type="submit" name="s2" id="s2" style="width:auto;" value="Export Contacts To CSV"  /></td></tr>
				</table></form>
				<?php
				break;
				
			case 'server': 
				
				$mapping = array();
				$mapping = $_REQUEST['chk'];
				$csv_terminated = "\n";
				$csv_separator = ",";
				$csv_enclosed = '"';
				$csv_escaped = "\\";
				$aa = implode(',', $mapping);
							
				
				$sql_query1 = "select contact_id, title, first_name, last_name, company, company_name from ".TBL_CONTACT." where contact_id in (".$aa.")";
				
				/*$sql_query2 = "select street_address, city, state, zip, country, type from ".CONTACT_ADDRESS." where contact_id in (".$aa.")";
				
				$sql_query3="select email, type from ".CONTACT_EMAIL." where contact_id   in (".$aa.")";
			
				$sql_query4="select im, im_network, type from  ".CONTACT_IM." where contact_id in (".$aa.")";
			
				$sql_query5="select number, type from ".CONTACT_PHONE." where contact_id in (".$aa.")";
				
				$sql_query6="select website, type from ".CONTACT_WEBSITE." where contact_id in (".$aa.")";*/
				
				
				
				$result1 = $this->db->query($sql_query1,__FILE__,__LINE__);
				/*$result2 = $this->db->query($sql_query2,__FILE__,__LINE__);
				$result3 = $this->db->query($sql_query3,__FILE__,__LINE__);
				$result4 = $this->db->query($sql_query4,__FILE__,__LINE__);
				$result5 = $this->db->query($sql_query5,__FILE__,__LINE__);
				$result6 = $this->db->query($sql_query6,__FILE__,__LINE__);*/
				
				 		
				//$rows_cnt1 = $this->db->num_rows($result1);
				/*$fields_cnt1 = $this->db->num_fields($result1);
				$fields_cnt2 = $this->db->num_fields($result2);
				$fields_cnt3 = $this->db->num_fields($result3);
				$fields_cnt4 = $this->db->num_fields($result4);
				$fields_cnt5 = $this->db->num_fields($result5);
				$fields_cnt6 = $this->db->num_fields($result6);*/
/*while($rows = $this->db->fetch_assoc($result5)){
echo "<pre>";
print_r($rows);
echo "</pre>";
}
exit();*/
				$resultarr = array();
				while($rows = $this->db->fetch_assoc($result1)){
					$result = array();
					foreach($rows as $key=>$val){
						$result[$key] = $val;
					}
					$ind = 1;
					$sql_address = "select street_address, city, state, zip, country, type from ".CONTACT_ADDRESS." where contact_id='".$rows[contact_id]."'";
					$result_address = $this->db->query($sql_address,__FILE__,__LINE__);

					while($row_address = $this->db->fetch_assoc($result_address)){
						foreach($row_address as $key=>$val){
							if($key=='type') $key='address_type';
							$result[$key.$ind] = $val;
						}
						$ind++;
					}
					$ind = 1;
					$sql_email = "select email, type from ".CONTACT_EMAIL." where contact_id='".$rows[contact_id]."'";
					$result_email = $this->db->query($sql_email,__FILE__,__LINE__);
					while($row_email = $this->db->fetch_assoc($result_email)){
						foreach($row_email as $key=>$val){
							if($key=='type') $key='email_type';
							$result[$key.$ind] = $val;
						}
						$ind++;
					}
					$ind = 1;
					$sql_im = "select im, im_network, type from ".CONTACT_IM." where contact_id='".$rows[contact_id]."'";
					$result_im = $this->db->query($sql_im,__FILE__,__LINE__);
					while($row_im = $this->db->fetch_assoc($result_im)){
						foreach($row_im as $key=>$val){
							if($key=='type') $key='im_type';
							$result[$key.$ind] = $val;
						}
						$ind++;
					}
					$ind = 1;
					$sql_phone = "select number, type from ".CONTACT_PHONE." where contact_id='".$rows[contact_id]."'";
					$result_phone = $this->db->query($sql_phone,__FILE__,__LINE__);
					while($row_phone = $this->db->fetch_assoc($result_phone)){
						foreach($row_phone as $key=>$val){
							if($key=='type') $key='number_type';
							$result[$key.$ind] = $val;
						}
						$ind++;
					}
					$ind = 1;
					$sql_web = "select website, type from ".CONTACT_WEBSITE." where contact_id='".$rows[contact_id]."'";
					$result_web = $this->db->query($sql_web,__FILE__,__LINE__);
					while($row_web = $this->db->fetch_assoc($result_web)){
						foreach($row_web as $key=>$val){
							if($key=='type') $key='website_type';
							$result[$key.$ind] = $val;
						}
						$ind++;
					}
					$resultarr[] = $result;
				}

				$sortedArr = $this->getSortedArray($resultarr);
				/*echo "<pre>";
				print_r($sortedArr);
				echo "<pre>";
				exit();*/
				
				$csv = $this->db2csv->create_csv_file($sortedArr);
				$_SESSION[csv] = $csv;
				/*header("Content-type: application/eml");
				header("Content-Disposition: attachment; filename=employer.csv");
				echo $csv;*/
			
			
				//header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				//header("Content-Length: " . strlen($out));
				// Output to browser with appropriate mime type, you choose ;)
				//header("Content-type: text/x-csv");
				//header("Content-type: text/csv");
				//header("Content-type: application/csv");
				//header("Content-Disposition: attachment; filename=$filename");
				//echo $out;*/
				/*echo "<br /> Contacts Exported to CSV ";
				$fp = fopen('csv-export/export.csv','w');
				fwrite($fp,$csv);
				fclose($fp);*/

			    break;
			
			default:  echo 'Wrong Paramemter passed';

		
		}// end of switch
	}// end of exportcsv
	
	
	function getSortedArray($resultarr)
	{
		$temparr = array();
		foreach($resultarr as $key=>$value){
			if(count($resultarr[0])<count($value)){
				$temparr = array();
				$temparr[0] = $resultarr[0];
				$resultarr[0] = $value;
				$resultarr[$key] = $temparr[0];
			}
		}
		return $resultarr;
	}
	
	/**************End of Export CSV************************/

		function ImportVcard($runat)//import jobs
			{
			switch($runat){
			case 'local':
						$FormName = "frm_ImportVcard";						
			//display form
			?>
			<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName; ?>" >
			<table width="100%" class="table">
			<tr><td >	
				<ul>
				<li><span id="spanmy_file" class="normal"></span></li>
				</ul>
			</td></tr>			
			
			<tr><td>
			<input type="file" name="my_file" id="my_file" value="" size="30" />
			</td></tr>
			<tr><td>
			<?php $this->security->Add_Rule_Webform('local'); ?>
			</td></tr>
			<tr>
			<td>
			<input name="submit" id="submit" type="submit" style="width:auto" onclick="return validateExtension('<?php echo $FormName;?>','my_file');" value="Upload" />
			</td>
			</tr></table>
			</form>
			<?php
			
			break;
			
			case 'server':
			
			$tmpname = 'tempvcard.txt';
			$handle = fopen($_FILES['my_file']['tmp_name'],'r');
			move_uploaded_file($_FILES["my_file"]["tmp_name"],"vcf-upload/".$tmpname);
	  		
			////////////////Start of contact_vcard_parse/////////////////////////
			
			
			$parse = new Contact_Vcard_Parse();
			
			$cardinfo = $parse->fromFile("vcf-upload/".$tmpname);
			
			/*echo '<pre>';
			
   			print_r($cardinfo);
			
  			echo '</pre>';	
			exit();*/

			
			$tmppp = array();
			
			$tmppp = $cardinfo[0];
			$comments = $tmppp[NOTE][0][value][0][0];
			$tmp8 = array();
			$tmp8 = $tmppp[N];
			
			$tmp9 = array();
			$tmp9 = $tmp8[0];
			
			$tmp10 = array();
			$tmp10 = $tmp9[value];
			
			$tmp11 = array();
			$tmp11 = $tmp10[family];
			
			$familyname = implode(',', $tmp11);
			
			$tmp12 = array();
			$tmp12 = $tmp10[given];
			
			$given = implode(',', $tmp12);
			
			$tmp13 = array();
			$tmp13 = $tmp10[prefix];
					
			$prefix = implode(',', $tmp13);
			
			$tmp14 = array();
			$tmp14 = $tmppp[FN];
			
			$tmp15 = array();
			$tmp15 = $tmp14[0];
			
			$tmp16 = array();
			$tmp16 = $tmp15[value];
			
			$tmp17 = array();
			$tmp17 = $tmp16[0];
			
			$fullname = implode(',', $tmp17);
			
			$tmp18 = array();
			$tmp18 = $tmppp[ORG];
			
			$tmp19 = array();
			$tmp19 = $tmp18[0];
			
			$tmp20 = array();
			$tmp20 = $tmp19[value];
			
			$tmp21 = array();
			$tmp21 = $tmp20[0];
			
			$company = implode(',', $tmp21);
			
			$tmp22 = array();
			$tmp22 = $tmppp[TITLE];
			
			$tmp23 = array();
			$tmp23 = $tmp22[0];
			
			$tmp24 = array();
			$tmp24 = $tmp23[value];
			
			$tmp25 = array();
			$tmp25 = $tmp24[0];
			
			$title = implode(',',$tmp25 );
			
			$tmp26 = array();
			$tmp26 = $tmppp[NOTE];
			
			$tmp27 = array();
			$tmp27 = $tmp26[0];
			
			$tmp28 = array();
			$tmp28 = $tmp27[param];
			
			$tmp29 = array();
			$tmp29 = $tmp28[ENCODING];
			
			$note = implode(',', $tmp29);
			
			$tmp30 = array();
			$tmp30 = $tmp27[value];
			
			$tmp31 = array();
			$tmp31 = $tmp30[0];
			
			$notevalue = implode(',', $tmp31);
			
			$tmp32 = array();
			$tmp32 = $tmppp[TEL];
			
			$tmp33 = array();
			$tmp33 = $tmp32[0];
			
			$tmp34 = array();
			$tmp34 = $tmp33[param];
			
			$tmp35 = array();
			$tmp35 = $tmp34[TYPE];
			
			$teltype0 = $tmp35[0];
			
			$tmp36 = array();
			$tmp36 = $tmp33[value];
			
			$tmp37 = array();
			$tmp37 = $tmp36[0];
			
			$telnum0 = $tmp37[0];
			
			$tmp38= array();
			$tmp38 = $tmp32[1];
			
			$tmp39 = array();
			$tmp39 = $tmp38[param];
			
			$tmp40 = array();
			$tmp40 = $tmp39[TYPE];
			
			$teltype1 = $tmp40[0];
			
			$tmp41 = array();
			$tmp41 = $tmp38[value];
			
			$tmp42 = array();
			$tmp42 = $tmp41[0];
			
			$telnum1 = implode(',', $tmp42);
			
			$tmp43 = array();
			$tmp43 = $tmp32[2];
			
			$tmp44 = array();
			$tmp44 = $tmp43[param];
			
			$tmp45 = array();
			$tmp45 = $tmp44[TYPE];
			
			$teltyp2 = $tmp45[0];
			
			$tmp46 = array();
			$tmp46 = $tmp43[value];
			
			$tmp47 = array();
			$tmp47 = $tmp46[0];
			
			if(count($tmp47)>0) $telnum2 = implode(',', $tmp47);
			
			$tmp48 = array();
			$tmp48 = $tmp32[3];
			
			$tmp49 = array();
			$tmp49 = $tmp48[param];
			
			$tmp50 = array();
			$tmp50 = $tmp49[TYPE];
			
			$teltyp3 = $tmp50[1];
			
			$tmp51 = array();
			$tmp51 = $tmp48[value];
			
			$tmp52 = array();
			$tmp52 = $tmp51[0];
			
			if(count($tmp51)>0) $telnum3 = implode(',', $tmp52);
			
			$tmp53 = array();
			$tmp53 = $tmp32[4];
			
			$tmp54 = array();
			$tmp54 = $tmp53[param];
			
			$tmp55 = array();
			$tmp55 = $tmp54[TYPE];
			
			$teltyp4 = $tmp55[0];
			
			$tmp56 = array();
			$tmp56 = $tmp53[value];
			
			$tmp57 = array();
			$tmp57 = $tmp56[0];
			
			if(count($tmp56)>0) $telnum4 = implode(',', $tmp57);
			
			
			$tmp58 = array();
			$tmp58 = $tmppp[ADR];
			
			$tmp59 = array();
			$tmp59 = $tmp58[0];
			
			$tmp60 = array();
			$tmp60 = $tmp59[param];
			
			$tmp61 = array();
			$tmp61 = $tmp60[TYPE];

			$adrtype0 = $tmp61[0];
			
			$tmp62 = array();
			$tmp62 = $tmp59[value];
			
			$tmp63 = array();
			$tmp63 = $tmp62[street];
			
			$street = implode(',', $tmp63);
			
			$tmp64 = array();
			$tmp64 = $tmp62[locality];
			
			$locality = implode(',', $tmp64);
			
			$tmp65 = array();
			$tmp65 = $tmp62[region];
			
			$region = implode(',', $tmp65);
			
			$tmp66 = array();
			$tmp66 = $tmp62[postcode];
			
			$postcode = implode(',',$tmp66);
			
			$tmp67 = array();
			$tmp67 = $tmp62[country];
			
			$country = implode(',', $tmp67);
			
			$tmp68 = array();
			$tmp68 = $tmp58[1];
		
			$tmp69 = array();
			$tmp69 = $tmp68[param];
			
			$tmp70 = array();
			$tmp70 = $tmp69[TYPE];
			
			if(count($tmp69)>0) $adrtype1 = implode(',', $tmp70);
			
			$tmp71 = array();
			$tmp71 = $tmp68[value];
			
			$tmp72 = array();
			$tmp72 = $tmp71[country];
			
			if(count($tmp71)>0) $country1 = implode(',', $tmp72);
			
			$tmp73 = array();
			$tmp73 = $tmppp[LABEL];
			
			$tmp74 = array();
			$tmp74 = $tmp73[0];
			
			$tmp75 = array();
			$tmp75 = $tmp74[param];
			
			$tmp76 = array();
			$tmp76 = $tmp75[TYPE];
			
			if(count($tmp75)>0) $labetypl = implode(',', $tmp76);
			
			$tmp77 = array();
			$tmp77 = $tmp75[ENCODING];
			
			if(count($tmp75)>0) $labeencl = implode(',', $tmp77);
			
			$tmp78 = array();
			$tmp78 = $tmp74[value];
			
			$tmp79 = array();
			$tmp79 = $tmp78[0];
			
			if(count($tmp78)>0) $labevalue = implode(',', $tmp79);
			
			$xmsoldefaultpostaladdress='X-MS-OL-DEFAULT-POSTAL-ADDRESS';
			
			$tmp80 = array();
			$tmp80 = $tmppp[$xmsoldefaultpostaladdress];
			
			$tmp81 = array();
			$tmp81 = $tmp80[0];
			
			$tmp82 = array();
			$tmp82 = $tmp81[value];
			
			$tmp83 = array();
			$tmp83 = $tmp82[0];
			
			if(count($tmp82)>0) $XMSOLDEFAULTPOSTALADDRESS = implode(',', $tmp83);
			
			$tmp84 = array();
			$tmp84 = $tmppp[URL];
			
			$tmp85 = array();
			$tmp85 = $tmp84[0];
			
			$tmp86 = array();
			$tmp86 = $tmp85[param];
			
			$tmp87 = array();
			$tmp87 = $tmp86[TYPE];
			
			$urltyp = $tmp87[0];
			
			$tmp88 = array();
			$tmp88 = $tmp85[value];
			
			$tmp89 = array();
			$tmp89 = $tmp88[0];
			
			$url = implode(',', $tmp89);
			
			$tmp90 = array();
			$tmp90 = $tmppp[BDAY];
			
			$tmp91 = array();
			$tmp91 = $tmp90[0];	
			
			$tmp92 = array();
			$tmp92 = $tmp91[value];	
			
			$tmp93 = array();
			$tmp93 = $tmp92[0];	
			
			if(count($tmp93)>0) $bday = implode(',', $tmp93);
			
			$tmp94 = array();
			$tmp94 = $tmppp[EMAIL];
			
			$tmp95 = array();
			$tmp95 = $tmp94[0];
			
			$tmp96 = array();
			$tmp96 = $tmp95[param];
			
			$tmp97 = array();
			$tmp97 = $tmp96[TYPE];
			
			$emailtyp = $tmp97[1];
			
			$tmp98 = array();
			$tmp98 = $tmp95[value];
			
			$tmp99 = array();
			$tmp99 = $tmp98[0];
			
			$emailval = implode(',', $tmp99);
			
			$XMSIMADDRESS='X-MS-IMADDRESS';
			
			$tmp100 = array();
			$tmp100 = $tmppp[$XMSIMADDRESS];
			
			$tmp101 = array();
			$tmp101 = $tmp100[0];
			
			$tmp102 = array();
			$tmp102 = $tmp101[value];
			
			$tmp103 = array();
			$tmp103 = $tmp102[0];
			
			if(count($tmp103)>0) $xmsimaddress = implode(',', $tmp103);
			
			$XMSCARDPICTURE='X-MS-CARDPICTURE';
			
			$tmp104 = array();
			$tmp104 = $tmppp[$XMSCARDPICTURE];
			
			$tmp105 = array();
			$tmp105 = $tmp104[0];
			
			$tmp106 = array();
			$tmp106 = $tmp105[param];
			
			$tmp107 = array();
			$tmp107 = $tmp106[TYPE];
			
			if(count($tmp107)>0) $XMSCARDPICTUREtyp = implode(',', $tmp107);
			
			$tmp108 = array();
			$tmp108 = $tmp106[ENCODING];
			
			if(count($tmp108)>0) $XMSCARDPICTUREval = implode(',', $tmp108);
			
			$tmp109 = array();
			$tmp109 = $tmp105[value];
			
			$tmp110 = array();
			$tmp110 = $tmp109[0];
			
			if(count($tmp110)>0) $XMSCARDPICTUREpic = implode(',', $tmp110);
			
			$XMSOLDESIGN='X-MS-OL-DESIGN';
			
			$tmp111 = array();
			$tmp111 = $tmppp[$XMSOLDESIGN];
			
			$tmp112 = array();
			$tmp112 = $tmp111[0];
			
			$tmp113 = array();
			$tmp113 = $tmp112[param];
			
			$tmp114 = array();
			$tmp114 = $tmp113[CHARSET];
			
			if(count($tmp114)>0) $XMSOLDESIGN = implode(',', $tmp114);
			
			$tmp115 = array();
			$tmp115 = $tmp112[value];
			
			$tmp116 = array();
			$tmp116 = $tmp115[0];
			
			if(count($tmp116)>0) $XMSOLDESIGNval = implode(',', $tmp116);
			
			$tmp117 = array();
			$tmp117 = $tmppp[REV];
			
			$tmp118 = array();
			$tmp118 = $tmp117[0];
			
			$tmp119 = array();
			$tmp119 = $tmp118[value];
			
			$tmp120 = array();
			$tmp120 = $tmp119[0];
			
			$REV = implode(',', $tmp120);
			
			
			
			/////////////////End of contact_vcard-parse//////////////////////////
			
			/////////////////uploadcase/////////////////////////////////////////
			
			
			$insert = array();
			$insert = explode(',', $type1);
			
			
			
			$insert1 = array();
			$insert1 = explode(',', $phonenum);
			
			
			
			$insert2 = array();
			$insert2 = explode(',', $familyname);
			
			
			$insert3 = array();
			$insert3 = explode(',', $prefix);
			
			
			
			$insert4 = array();
			$insert4 = explode(',', $fullname);
			
			
			$insert5 = array();
			$insert5 = explode(',', $company);
			
			
			$insert6 = array();
			$insert6 = explode(',', $title);
			
			
			$insert7 = array();
			$insert7 = explode(',', $note);
			
			
			$insert8 = array();
			$insert8 = explode(',', $notevalue);
			
			
			$insert9 = array();
			$insert9 = explode(',', $teltype0);
			
			
			$insert10 = array();
			$insert10 = explode(',', $telnum0);
			
			
			$insert11 = array();
			$insert11 = explode(',', $teltype1);
			
			
			$insert12 = array();
			$insert12 = explode(',', $telnum1);
			
			
			$insert13 = array();
			$insert13 = explode(',', $teltyp2);
			
			
			$insert14 = array();
			$insert14 = explode(',', $telnum2);
			
			
			$insert15 = array();
			$insert15 = explode(',', $teltyp3);
			
			
			$insert16 = array();
			$insert16 = explode(',', $telnum3);
			
			
			$insert17 = array();
			$insert17 = explode(',', $teltyp4);
			
			
			$insert18 = array();
			$insert18 = explode(',', $telnum4);
			
			
			$insert19 = array();
			$insert19 = explode(',', $adrtype);
			
			
			
			$insert20 = array();
			$insert20 = explode(',', $street);
			
			
			
			$insert21 = array();
			$insert21 = explode(',', $locality);
			
			
			$insert22 = array();
			$insert22 = explode(',', $region);
			
			
			$insert23 = array();
			$insert23 = explode(',', $postcode);
			
			
			
			$insert24 = array();
			$insert24 = explode(',', $country);
			
			
			$insert25 = array();
			$insert25 = explode(',', $adrtype1);
			
			
			$insert26 = array();
			$insert26 = explode(',', $country1);
			
			
			$insert27 = array();
			$insert27 = explode(',', $labetypl);
			
			
			$insert28 = array();
			$insert28 = explode(',', $labeencl);
			
			
			$insert29 = array();
			$insert29 = explode(',', $labevalue);
			
			
			$insert30 = array();
			$insert30 = explode(',', $XMSOLDEFAULTPOSTALADDRESS);
			
			
			$insert31 = array();
			$insert31 = explode(',', $urltyp);
			
			
			$insert32 = array();
			$insert32 = explode(',', $url);
			
			
			$insert33 = array();
			$insert33 = explode(',', $bday);
			
			
			$insert34 = array();
			$insert34 = explode(',', $emailtyp);
			
			
			$insert35 = array();
			$insert35 = explode(',', $emailval);
			
			
			$insert36 = array();
			$insert36 = explode(',', $xmsimaddress);
			
			
			$insert37 = array();
			$insert37 = explode(',', $XMSCARDPICTUREtyp);
			
			
			$insert38 = array();
			$insert38 = explode(',', $XMSCARDPICTUREval);
			
			
			$insert39 = array();
			$insert39 = explode(',', $XMSCARDPICTUREpic);
			
			
			$insert40 = array();
			$insert40 = explode(',', $XMSOLDESIGN);
			
			$insert41 = array();
			$insert41 = explode(',', $XMSOLDESIGNval);
			
			
			$insert42 = array();
			$insert42 = explode(',', $REV);
			
			$company_id=$this->GetValidCompany($company);
			$contact_table_array= array();
			
			$contact_table_array[first_name] = $given;
			$contact_table_array[last_name] = $familyname;
			$contact_table_array[company_name] = $company;
			$contact_table_array[company] = $company_id;
			$contact_table_array[type] = 'People';
			$contact_table_array[user_id] = $this->user_id;
			$contact_table_array[title] = $title;
			$contact_table_array[comments] = $comments;
			$contact_table_arraytypework= array();
			$contact_table_arraytypework[street_address] = $street;
			$contact_table_arraytypework[city] = $locality;
			$contact_table_arraytypework[state] = $region;
			$contact_table_arraytypework[zip] = $postcode;
			$contact_table_arraytypework[country] = $country;
			$contact_table_arraytypework[type] = ucfirst(strtolower("$adrtype0"));
			$contact_table_arraytypehome= array();
			$contact_table_arraytypehome[country] = $country1;
			$contact_table_arraytypehome[type] = ucfirst(strtolower("$adrtype1"));
			
			
			$contact_table_arraytelwork= array();		
			$contact_table_arraytelwork[type]= ucfirst(strtolower("$teltype0"));
			$contact_table_arraytelwork[number]=$telnum0;
			$contact_table_arraytelhome= array();
			$contact_table_arraytelhome[type]=ucfirst(strtolower("$teltype1"));
			$contact_table_arraytelhome[number]=$telnum1;
			$contact_table_arraytelcell= array();
			$contact_table_arraytelcell[type]="Mobile";
			$contact_table_arraytelcell[number]=$telnum2;
			$contact_table_arraytelfax= array();
			$contact_table_arraytelfax[type]=ucfirst(strtolower("$teltyp3"));
			$contact_table_arraytelfax[number]=$telnum3;
			
			
			$contact_table_arrayurlwork= array();
			$contact_table_arrayurlwork[type] = ucfirst(strtolower("$urltyp"));
			$contact_table_arrayurlwork[website] = $url;
			
			
			$contact_table_arraybday= array();
			$contact_table_arraybday[] = $bday;
			
			$contact_table_arrayemailpref= array();
			$contact_table_arrayemailpref[type] = "Work";
			$contact_table_arrayemailpref[email] = $emailval;
			
			
			
			
						
			$this->db->insert(TBL_CONTACT,$contact_table_array);
			
			$this->contact_id=$this->db->last_insert_id();
			$contact_table_arraytypework[contact_id]=$this->contact_id;
			$contact_table_arraytypehome[contact_id]=$this->contact_id;
			$contact_table_arraytelwork[contact_id]=$this->contact_id;
			$contact_table_arraytelhome[contact_id]=$this->contact_id;
			$contact_table_arraytelcell[contact_id]=$this->contact_id;
			$contact_table_arraytelfax[contact_id]=$this->contact_id;
			$contact_table_arrayurlwork[contact_id]=$this->contact_id;
			$contact_table_arrayemailpref[contact_id]=$this->contact_id;
			$this->db->insert(CONTACT_ADDRESS,$contact_table_arraytypework);
			$this->db->insert(CONTACT_ADDRESS,$contact_table_arraytypehome);
			$this->db->insert(CONTACT_PHONE,$contact_table_arraytelwork);
			$this->db->insert(CONTACT_PHONE,$contact_table_arraytelhome);
			$this->db->insert(CONTACT_PHONE,$contact_table_arraytelcell);
			$this->db->insert(CONTACT_PHONE,$contact_table_arraytelfax);
			$this->db->insert(CONTACT_WEBSITE,$contact_table_arrayurlwork);
			$this->db->insert(CONTACT_EMAIL,$contact_table_arrayemailpref);
	
			
			
			
		    $this->security->SetModule_name($this->module);
			$this->security->SetModule_id($this->contact_id);
			$this->security->Add_Rule_Webform('server');
			$_SESSION['msg'] = 'Record Inserted Sucessfully';
			?>
			<script type="text/javascript">
			window.location = "<?php echo $_SERVER['PHP_SELF'] ?>";
			</script>
			<?php
			exit();
			break;
			
			default : echo 'Wrong Paramemter passed';
			
			}// end of switch
	}
	/**************End of Import CSV************************/
	function ExportVcard($contact_id)
	{	
	//query database for the contact details & generate vcard
		
		
		$r="select * from ".TBL_CONTACT." where contact_id = ".$contact_id;
		$temp_recrod=$this->db->query($r, __FILE__, __LINE__);
		$temp_rows=$this->db->fetch_array($temp_recrod);
		
		$_SESSION[vcardname] =$temp_rows['first_name'].' '.$temp_rows['last_name'];
		$this->vcard->setName($temp_rows['last_name'], $temp_rows['first_name']);
		$this->vcard->setNote($temp_rows['comments']);
		$this->vcard->setCompany($temp_rows['company_name']);
		$this->vcard->setTitle($temp_rows['title']);
		
		
		$s="select * from ".CONTACT_ADDRESS." where contact_id = ".$contact_id;
		$temp_recrod=$this->db->query($s, __FILE__, __LINE__);
		while($temp_rows=$this->db->fetch_array($temp_recrod))
		$this->vcard->setAddress('', '', $temp_rows['street_address'], $temp_rows['city'], $temp_rows['state'], $temp_rows['zip'], $temp_rows['country'], $temp_rows['type']);
		
		
		
		$t="select * from ".CONTACT_EMAIL." where contact_id = ".$contact_id;
		$temp_recrod=$this->db->query($t, __FILE__, __LINE__);
		while($temp_rows=$this->db->fetch_array($temp_recrod))
		$this->vcard->setEmail($temp_rows['email']);
	
	
		$u="select * from ".CONTACT_WEBSITE." where contact_id = ".$contact_id;
		$temp_recrod=$this->db->query($u, __FILE__, __LINE__);
		while($temp_rows=$this->db->fetch_array($temp_recrod))
		$this->vcard->setURL($temp_rows['website'], $temp_rows['type']);	
		


		$ph="select * from ".CONTACT_PHONE." where contact_id = ".$contact_id;
		$temp_recrod=$this->db->query($ph, __FILE__, __LINE__);
		while($temp_rows=$this->db->fetch_array($temp_recrod)){
		    if($temp_rows['type']=='Mobile'){
				$this->vcard->setPhoneNumber($temp_rows['number'], 'CELL;VOICE');	
			} else if($temp_rows['type']=='Fax'){
				$this->vcard->setPhoneNumber($temp_rows['number'], 'WORK;FAX');	
			} else
			$this->vcard->setPhoneNumber($temp_rows['number'], $temp_rows['type']);	
		}


		$output = $this->vcard->getvcard();
		$filename = $this->vcard->getFileName();

		$_SESSION[vcard] = $output;
		$_SESSION[filename] = $filename;
				
		
	}

	function GetContactsJson($pattern=''){
		ob_start();
		$contact_json = "";
		$sql="select * from ".TBL_CONTACT." where first_name LIKE '$pattern%' or company_name  LIKE '$pattern%' limit 0, 20";
		$record=$this->db->query($sql,__LINE__,__FILE__);
		while($row = $this->db->fetch_array($record)){
			$contact_json .= '{"caption":"'.$row[first_name].' '.$row[last_name].'","value":"'.$row[contact_id].'"},';
		}
		$contact_json = '['.substr($contact_json,0,strlen($contact_json)-1).']';
		return $contact_json;
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	
		function emailToAll($runat,$contacts,$obj='',$from='',$to='',$subject='',$message=''){
		ob_start();
		$this->mail_obj = new PHPMailer();
		switch ($runat) {
			case 'local':
				?>
				<div class="prl">&nbsp;</div>
				<div id="lightbox">
				<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
				<div id="TB_ajaxWindowTitle">Email To All Contacts</div>							
				<div id="TB_closeAjaxWindow">
				<a href="javascript:void(0)" onclick="javascript: document.getElementById('div_event').style.display='none';">
				<img border="0" src="images/close.gif" alt="close" /></a></div>
				</div>
				<div  class="white_content"> 
				<div style="padding:20px;" >
				<ul id="error_list">
				</ul>
				<form name="emailtoall" id="emailtoall" action="" method="post">
				<table width="100%" class="table">
				<?php
				$sql = "select email from ".CONTACT_EMAIL." where contact_id in ($contacts)";
				$result = $this->db->query($sql,__FILE__,__LINE__);	
				
				$sql_c = "select email_id,user_name from ".TBL_USER." where user_id ='$_SESSION[user_id]'";
				$result_c = $this->db->query($sql_c,__FILE__,__LINE__);	
				$row_c = $this->db->fetch_array($result_c);
				$to='';
				while($row = $this->db->fetch_array($result)) {
					$to .= $row['email'].',';
				}
				$to = substr($to,0,strlen($to)-1);
				?>
				<tr>
					<th>Reply To:</th><td><input name="from" id="from" value="<?php echo $row_c[email_id];?>" /></td>
				</tr>
				<?php /*?><tr>
					<th>To:</th><td><input type="hidden" name="to" id="to"<?php echo $to; ?> /></td>
				</tr><?php */?>
				<input type="hidden" name="to" id="to"<?php echo $to; ?> />
				<tr>
					<th>Subject:</th><td><input type="text" name="subject" id="subject"></td>
				</tr>
				<tr>
					<th>Message:</th><td><textarea name="message" id="message" style="width:100%"></textarea></td>
				</tr>
				
				<tr><td colspan="2" align="center">
				<input type="button" name="send" value="Send" id="send" style="width:auto;" onclick="javascript: <?php echo $obj; ?>.emailToAll('server',
					'<?php echo $contacts;?>',
					'<?php echo $obj;?>',
					this.form.from.value,
					this.form.to.value,
					this.form.subject.value,
					this.form.message.value,
					{ preloader: 'prl',
					onUpdate: function(response,root){
						 document.getElementById('div_event').innerHTML=response;
						 document.getElementById('div_event').style.display='';
					 }});" />
				</td></tr>
				</table>
				</form>
				</div></div></div>
				<?php
				break;
			case 'server':
				$to = explode(',',$to);
				for($i=0 ; $i<count($to) ; $i++){
					$this->mail_obj->ClearAddresses();
					$this->mail_obj->ClearAttachments();
					$this->mail_obj->IsHTML(true);  
					$this->mail_obj->From = $from;
					//$this->mail_obj->FromName = $row_c[user_name];
					$this->mail_obj->AddAddress("$to[$i]");
					$this->mail_obj->Subject = $subject;
					$this->mail_obj->Body = $message;
					$this->mail_obj->WordWrap = 50;
					$this->mail_obj->Send();
					//echo $to[$i].'<br>'.$subject.'<br>'.$message.'<br>';
				}
				break;
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
        function SetContactRefreshJs($js){
            $this->refresh_js = $js;
            
        }
 	function addContactsInCompany($runat,$obj,$contact_id){
		ob_start();
		switch($runat) {
			case 'local':
				?>
				<div class="prl">&nbsp;</div>

				<div id="lightbox" style=" position:absolute;!important;" >		
				<div style="background-color:#ADC2EB;" class="ajax_heading">
					<div id="TB_ajaxWindowTitle"><?php echo 'Add Contacts In the Company';?> </div>
					
					<div id="TB_closeAjaxWindow"><a href="contact_profile.php?contact_id=<?php echo $contact_id; ?>">
					<img border="0" src="images/close.gif" alt="close" /></a></div>	
					</div>
				<div class="white_content"> 
				<p id="upload_process" style="visibility:hidden"><img src="loader.gif" /></p>												
				<script>
					///////////////////   For Adding Multiple Addresses and Email Ids   /////////////// 
					var multi_phone = 0;
					var multi_emailid = 0;
					function addPhone() {
					
						var tr = document.createElement('tr');
						var cnt = 'cnt';
						var id = 'multi_number-'+multi_phone;
						multi_phone++;
						
						document.getElementById(cnt).value= parseInt(document.getElementById(cnt).value)+1;
						tr.setAttribute('id', id);
						var td = document.createElement('td');
					
						td.innerHTML = '<div><table><tr><td><input id="person_phone'+document.getElementById(cnt).value+'" name="person[phone][]" size="30" type="text"></td><td><select id="phone_type'+cnt+'" name="person[phone_type]"><option selected="selected" value="Work">Work</option><option value="Mobile">Mobile</option><option value="Fax">Fax</option><option value="Pager">Pager</option><option value="Home">Home</option><option value="Skype">Skype</option><option value="Other">Other</option></select></td><td><span onclick="removeLinks(\''+id+'\',\''+cnt+'\');" style="cursor:pointer;"><img src="images/trash.gif" border="0" /></span></td></tr></table></div>';
						
						tr.appendChild(td);
						document.getElementById('root-link_phone').appendChild(tr);
					}
						
					function addEmail(){
							var tr = document.createElement('tr');
							var cnt = 'cnt2';
							var id = 'multi_email-'+multi_emailid;
							multi_emailid++;
							
							document.getElementById(cnt).value= parseInt(document.getElementById(cnt).value)+1;
							tr.setAttribute('id', id);
							var td = document.createElement('td');
						
							td.innerHTML = '<div><table><tr><td><input id="person_email'+document.getElementById(cnt).value+'" name="person[email][]" size="30" type="text"></td><td><select id="email_type'+cnt+'" name="person[email_type]"><option selected="selected" value="Work">Work</option><option value="Home">Home</option><option value="Other">Other</option></select></td><td><span onclick="removeLinks(\''+id+'\',\''+cnt+'\');" style="cursor:pointer;"><img src="images/trash.gif" border="0" /></span></td></tr></table></div>';
							
							tr.appendChild(td);
							document.getElementById('root-link_email').appendChild(tr);	
						}
				
					function removeLinks(aId,div_id) {
						document.getElementById(div_id).value= parseInt(document.getElementById(div_id).value)-1; 
						var obj = document.getElementById(aId);
						obj.parentNode.removeChild(obj);
					}
				
				</script>  				
				<form action="" id="add_contact_in_company" name="add_contact_in_company" method="post">
				   <input type="hidden" id="cnt2" name="cnt2" value="0"/>
				   <input type="hidden" id="cnt" name="cnt" value="0"/>	
				   <table width="100%" class="table">   
					 <tr class="name first_name">
						<th><h2>First name </h2></th>
						<td>
						<input class="name" id="person_first_name" name="person[first_name]" size="30" type="text">
                                                <input type="hidden" name="person[contact_id]" value="<?php echo $contact_id; ?>" >
						<ul id="error_list"><li><span id="span_person_first_name"></span></li></ul>
						</td>
					  </tr>
					  <tr class="name last_name">
						<th><h2>Last name </h2></th>
						<td><input class="name" id="person_last_name" name="person[last_name]" size="30" type="text">
						<ul id="error_list"><li><span id="span_person_last_name"></span></li></ul></td>
					  </tr>
					  <tr class="title name" id="title_field_person">
						<th><h2>Title </h2></th>
						<td><div class="contact_methods"><div class="contact_method">
							<input id="person_title" name="person[title]" size="30" class="title"  type="text">
							</div></div>
						</td>
					  </tr>
					  <tr>
					  	<th><h2>Phone </h2></th>
					  	<td>
							<table width="100%">
								<tbody id="root-link_phone">
									<tr>										
										<td>
											<a href="javascript:void(0);" onclick="javascript:addPhone(); ">Add a Phone Number</a>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					  </tr>
					  <tr>
						<th><h2>Email Id </h2></th>
						<td>
							<table width="100%">
								<tbody id="root-link_email">
									<tr>
										<td>
											<a href="javascript:void(0);" onclick="javascript:addEmail(); ">Add an Email Id</a>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					  </tr>					    
					  <tr>
						<td>
							<input onclick="$.post('ajax.add_subcontact.php' , $('#add_contact_in_company').serialize() , function(a,b,c){ <?php echo $this->refresh_js; ?> } );return false;" type="submit" value="Submit" name="btnSubmit" id="btnSubmit" />
						</td>
					  </tr>
				   </table>
                 </p>
                 </form>		 					
				</div></div>
				<?php  
				break;
				
				
			case 'server':
                extract($_POST[person]);
				$f_name = $first_name;
				$l_name = $last_name;
				$title = $title;
				
				$insert_sql_array[type] = "People";
				$insert_sql_array[first_name] = $f_name;
				$insert_sql_array[last_name] = $l_name;	
                $insert_sql_array[title] = $title;	
				$insert_sql_array[company] = $contact_id;														 
				$this->db->insert(TBL_CONTACT,$insert_sql_array);

				$last_inserted_contact_id = $this->db->last_insert_id();
				//echo $last_inserted_contact_id;
				//echo $this->db->last_insert_id();
				foreach($phone as $phone_no){
					$insert_number = array();	
					$insert_number[number] = $phone_no;
					$insert_number[type] = $phone_type;
					$insert_number[contact_id] = $last_inserted_contact_id;														 
					$this->db->insert(CONTACT_PHONE,$insert_number);			
				} 
				foreach($email as $email_id){
					$insert_email = array();	
					$insert_email[email] = $email_id;
					$insert_email[type] = $email_type;
					$insert_email[contact_id] = $last_inserted_contact_id;														 
					$this->db->insert(CONTACT_EMAIL,$insert_email);			
				} 
				break;		
		}		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
 	function editContactsInCompany($runat,$obj,$edit_contact_id,$contact_id){
		ob_start();
		switch($runat) {
			case 'local':
			    $sql = "Select * from ".TBL_CONTACT." where contact_id = '$edit_contact_id'";
				$record=$this->db->query($sql,__FILE__,__LINE__);
				$row=$this->db->fetch_array($record);				
				?>
				<div class="prl">&nbsp;</div>

				<div id="lightbox" style=" position:absolute;!important;" >		
				<div style="background-color:#ADC2EB;" class="ajax_heading">
					<div id="TB_ajaxWindowTitle"><?php echo 'Edit Contacts';?> </div>
					
					<div id="TB_closeAjaxWindow"><a href="contact_profile.php?contact_id=<?php echo $contact_id; ?>">
					<img border="0" src="images/close.gif" alt="close" /></a></div>	
					</div>
				<div class="white_content"> 
				<p id="upload_process" style="visibility:hidden"><img src="loader.gif" /></p>
				<script>
					///////////////////   For Editing Multiple Addresses and Email Ids   /////////////// 
					var multi_phone = 0;
					var multi_emailid = 0;
					function editPhone(phone_number) {
					
						var tr = document.createElement('tr');
						var cnt = 'cnt';
						var id = 'multi_number-'+multi_phone;
						multi_phone++;
						
						document.getElementById(cnt).value= parseInt(document.getElementById(cnt).value)+1;
						tr.setAttribute('id', id);
						var td = document.createElement('td');
					
						td.innerHTML = '<div><table><tr><td><input id="person_phone'+document.getElementById(cnt).value+'" name="person[phone][]" size="30" type="text" value="'+phone_number+'"></td><td><select id="phone_type'+cnt+'" name="person[phone_type]"><option selected="selected" value="Work">Work</option><option value="Mobile">Mobile</option><option value="Fax">Fax</option><option value="Pager">Pager</option><option value="Home">Home</option><option value="Skype">Skype</option><option value="Other">Other</option></select></td><td><span onclick="removeLinks(\''+id+'\',\''+cnt+'\');" style="cursor:pointer;"><img src="images/trash.gif" border="0" /></span></td></tr></table></div>';
						
						tr.appendChild(td);
						document.getElementById('root-link_phone').appendChild(tr);
					}
						
					function editEmail(email){
							var tr = document.createElement('tr');
							var cnt = 'cnt2';
							var id = 'multi_email-'+multi_emailid;
							multi_emailid++;
							
							document.getElementById(cnt).value= parseInt(document.getElementById(cnt).value)+1;
							tr.setAttribute('id', id);
							var td = document.createElement('td');
						
							td.innerHTML = '<div><table><tr><td><input id="person_email'+document.getElementById(cnt).value+'" name="person[email][]" size="30" type="text" value="'+email+'"></td><td><select id="email_type'+cnt+'" name="person[email_type]"><option selected="selected" value="Work">Work</option><option value="Home">Home</option><option value="Other">Other</option></select></td><td><span onclick="removeLinks(\''+id+'\',\''+cnt+'\');" style="cursor:pointer;"><img src="images/trash.gif" border="0" /></span></td></tr></table></div>';
							
							tr.appendChild(td);
							document.getElementById('root-link_email').appendChild(tr);	
						}
				
					function removeLinks(aId,div_id) {
						document.getElementById(div_id).value= parseInt(document.getElementById(div_id).value)-1; 
						var obj = document.getElementById(aId);
						obj.parentNode.removeChild(obj);
					}
				
				</script>  												
				<form action="" id="edit_contact_in_company" name="edit_contact_in_company" method="post">
				   <input type="hidden" id="cnt2" name="cnt2" value="0"/>
				   <input type="hidden" id="cnt" name="cnt" value="0"/>	
				   <table width="100%" class="table">   
					 <tr class="name first_name">
						<th><h2>First name </h2></th>
						<td>
						<input class="name" id="person_first_name" name="person[first_name]" size="30" type="text" value="<?php echo $row['first_name']; ?>">
						<ul id="error_list"><li><span id="span_person_first_name"></span></li></ul>
						</td>
					  </tr>
					  <tr class="name last_name">
						<th><h2>Last name </h2></th>
						<td><input class="name" id="person_last_name" name="person[last_name]" size="30" type="text" value="<?php echo $row['last_name']; ?>">
						<ul id="error_list"><li><span id="span_person_last_name"></span></li></ul></td>
					  </tr>
					  <tr class="title name" id="title_field_person">
						<th><h2>Title </h2></th>
						<td><div class="contact_methods"><div class="contact_method">
							<input id="person_title" name="person[title]" size="30" class="title"  type="text" value="<?php echo $row['title']; ?>">
							</div></div>
						</td>
					  </tr>
					  <?php 
					  $sql_phone = "select * from ".CONTACT_PHONE." where contact_id = '$edit_contact_id'";
					  $record_phone=$this->db->query($sql_phone,__FILE__,__LINE__);
					  ?>
					  <tr>
					  	<th><h2>Phone </h2></th>
					  	<td>
							<table width="100%">
								<tbody id="root-link_phone">
									<?php  while($row_phone=$this->db->fetch_array($record_phone)) { ?>
										<tr>
											<td>
												<script type="text/javascript">editPhone('<?php echo $row_phone[number] ?>');</script>
											</td>
										</tr>
									<?php }	?>									
									<tr>										
										<td>
											<a href="javascript:void(0);" onclick="javascript:editPhone(''); ">Add a Phone Number</a>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					  </tr>
					  <?php 
					  $sql_email = "select * from ".CONTACT_EMAIL." where contact_id = '$edit_contact_id'";
					  $record_email=$this->db->query($sql_email,__FILE__,__LINE__);
					  ?>					  
					  <tr>
						<th><h2>Email Id </h2></th>
						<td>
							<table width="100%">
								<tbody id="root-link_email">
									<?php  while($row_email=$this->db->fetch_array($record_email)) { ?>
										<tr>
											<td>
												<script type="text/javascript">editEmail('<?php echo $row_email[email]; ?>');</script>
											</td>
										</tr>
									<?php }	?>										
									<tr>
										<td>
											<a href="javascript:void(0);" onclick="javascript:editEmail(''); ">Add an Email Id</a>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					  </tr>					    
					 <tr>
					     <td colspan="3">&nbsp;</td>
					 	 <td>
						      <input type="submit" value="Submit" name="editbtnSubmit" id="editbtnSubmit" />
<?php /*?>						    <input type="button" value="Submit" name="btnSubmit" id="btnSubmit"
							   onclick="javascript: <?php echo $obj; ?>.editContactsInCompany('server',
							   																  '<?php echo $obj; ?>',
																							  '<?php echo $edit_contact_id; ?>',
																							  '<?php echo $contact_id; ?>',
																							  {preloader:'prl'});"/><?php */?>
                         </td>
					 </tr>
				   </table>
                  </p>
                 </form>		 					
				</div></div>
				<?php  
				break;
				
				
			case 'server':
    			  
					extract($_POST[person]);
					$f_name = $first_name;
					$l_name = $last_name;
					$title = $title;
					
					$update_sql_array[first_name] = $f_name;
					$update_sql_array[last_name] = $l_name;	
					$update_sql_array[title] = $title;
					
					$this->db->update(TBL_CONTACT,$update_sql_array,"contact_id",$edit_contact_id);	
						
					$sql_remove_phone = $this->db->query("DELETE FROM ".CONTACT_PHONE." WHERE `contact_id` = '$edit_contact_id'");	
					$sql_remove_email = $this->db->query("DELETE FROM ".CONTACT_EMAIL." WHERE `contact_id` = '$edit_contact_id'");
						
					foreach($phone as $phone_no){
						$insert_number = array();	
						$insert_number[number] = $phone_no;	
						$insert_number[type] = $phone_type;	
						$insert_number[contact_id] = $edit_contact_id;										 
						$this->db->insert(CONTACT_PHONE,$insert_number);			
					} 
					foreach($email as $email_id){
						$insert_email = array();	
						$insert_email[email] = $email_id;	
						$insert_email[type] = $email_type;	
						$insert_email[contact_id] = $edit_contact_id;											 
						$this->db->insert(CONTACT_EMAIL,$insert_email);			
					} 
					?>
					<script>
					window.location = "contact_profile.php?contact_id=<?php echo $contact_id; ?>";
					</script>
					<?php					
					break;		
		}		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
	function generateorder($vendor_contact_id='',$obj=''){
		 ob_start();
		    			
		    $created = date("Y-m-d h:i A");
			$insert_array= array();
			$insert_array['grant_total'] = 0.0;
			$insert_array['CSR'] = $_SESSION[user_id];
			$insert_array['vendor_contact_id'] = $vendor_contact_id;
			$insert_array['status'] = 'In Progress';
			$insert_array['created'] = $created;
			$this->db->insert(erp_ORDER,$insert_array);
			 $order_id=$this->db->last_insert_id();
			
			$sql = "SELECT * FROM ". TBL_CONTACTS_ADDRESS ." WHERE contact_id='$vendor_contact_id'";
			$result = $this->db->query($sql);
			while($row = $this->db->fetch_array($result)){
				$ins_array= array();
				$ins_array['module_id'] = $order_id;
				$ins_array['module_name'] = 'order';
				$ins_array['street_address'] = $row['street_address'];
				$ins_array['city'] = $row['city'];
				$ins_array['state'] = $row['state'];
				$ins_array['zip'] = $row['zip'];
				$ins_array['country'] = $row['country'];
				$ins_array['type'] = $row['type'];
				$this->db->insert(TBL_MODULE_ADDRESS,$ins_array);
			}
			 
/*			$sql = "SELECT address_id FROM ". TBL_MODULE_ADDRESS ." WHERE module_id='$order_id'";
			$result = $this->db->query($sql);
			$row = $this->db->fetch_array($result);
			
			$upd_array = array();
			$upd_array['shipping_address'] = $row[address_id];
			$upd_array['billing_address'] = $row[address_id];
			
			$this->db->update(erp_ORDER,$upd_array,"order_id",$order_id);
*/			
			//echo $order_id;
			 ?>
			 <script>
			 	window.location="order.php?order_id=<?php  echo $order_id;?>";
			 </script>
		 <?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}	
	
	
	function show_order($vendor_id='')
	{
	ob_start();
	//echo $vendor_id;?>
	<table id="search_table" class="event_form small_text">
	  <thead>
		<tr>
			<th>Order</th>
			<th>Customer</th>
			<th>Event Date</th>
			<th>Ship Date</th>
			<th>Status</th>
			<th>CSR</th>
			<th>Grand Total</th>
			<th>Created</th>
		</tr>
	 </thead>	
	 <tbody>
	 <?php
	 	$sql="select * from erp_order where vendor_contact_id=$vendor_id";
		$result=$this->db->query($sql,__FILE__,__LINE__);
		while($row=$this->db->fetch_array($result))
		{?>
		<tr>
			<td><a href="order.php?order_id=<?php echo $row['order_id'];?>"><?php echo $row['order_id'];?></a></td>
			<?php 
			$sql_cust="SELECT * FROM `contacts` WHERE contact_id=$vendor_id";
			$result_cust=$this->db->query($sql_cust,__FILE__,__LINE__);
			$row_cust=$this->db->fetch_array($result_cust);
			?>
			<td><?php echo $row_cust['first_name'].' '.$row_cust['last_name'];?></td>	  
			<td><?php echo $row['event_date'];?></td>
			<td><?php echo $row['ship_date'];?></td>
			<td><?php echo $row['status'];?></td>
			<?php 
			$sql_csr="select a.*,b.* from erp_contactscreen_custom a,tbl_user b where a.contact_id=$vendor_id and a.csr=b.user_id";
			$result_csr=$this->db->query($sql_csr,__FILE__,__LINE__);
			$row_csr=$this->db->fetch_array($result_csr);
			?>
			<td><?php echo $row_csr['first_name'].' '.$row_csr['last_name'];?></td>
			<td><?php echo '$ '.number_format($row['grant_total']);?></td>
			<td><?php echo $row['created'];?></td>
		 </tr> 
	   <?php
		} ?>
	 </tbody>
	 </table>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
	}
	
}	
?>