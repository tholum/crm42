<?php
    //ini_set("display_errors" , 1);
        include_once("class/chat.config.php");
	/*DEFAULT HASH SALT*/
	define("SALT" , "dZrY1DqK7hKdjYnyhnHfix7riajvy4ngB6HhVqhLi8DNjvNL6fD6844lKKLy4lK",true);

	define("SYMBOLS","~,!,@,#,$,%,^,&,*,(,),_,-,+,=,{,},[,]",true);
	/*********User Module***************/
	define("TBL_USER",'tbl_user',true);
	define("TBL_USER_PRIMARY",'user_id',true);
	define("TBL_USER_TITLE",'user_name',true);
	define("TBL_USERGROUP","tbl_usergroup",true);
	define("GROUP_ACCESS","group_access",true);

	define("TBL_USERGROUP_PRIMARY","group_id",true);
	
	define("TBL_EVENT_PRIMARY",'event_id',true);
	
	define("TBL_THEME",'tbl_theme',true);
	
	/*********Bug Module***************/
	define("TBL_bug",'tbl_bug',true);
	define("TBL_BUG_LINKER","tbl_bug_linker",true);
	
	
	
	/*********Date Module***************/
	define("TBL_DATE",'tbl_date',true);
	define("FIlES_USER_CONTROL","files_user_control",true);
	define("FILES_CATEGORY","files_category",true);
	define("TBL_DATE_PRIMARY",'date_id',true);
	define("TBL_DATE_TITLE",'title',true);
	
	
	
	/*********File Module***************/
	define("TBL_FILE",'tbl_file',true);
	define("TBL_FILE_PRIMARY",'file_id',true);
	define("TBL_FILE_TITLE",'title',true);
	
	
	/*********Tag Module***************/
	define("TAGS","tags_name",true);
	define("TAGS_DATA","tags",true);
	
	
	/*********Contact Module***************/
	define("TBL_CONTACT",'contacts',true);
	define("CONTACT_PHONE","contacts_phone",true);
	define("CONTACT_EMAIL","contacts_email",true);
	define("CONTACT_IM","contacts_im",true);
	define("CONTACT_WEBSITE","contacts_website",true);
	define("CONTACT_TWITTER","contacts_twitter",true);
	define("CONTACT_ADDRESS","contacts_address",true);
	define("TBL_CONTACT_PRIMARY",'contact_id',true);
	define("TBL_CONTACT_TITLE",'first_name',true);
	define("TBL_NOTE",'tbl_note',true);
	
	/*********Task Module***************/
	define("TASKS","tasks",true);
	define("TASKS_CATEGORY","tasks_category",true);
	define("ASSIGN_TASK","assign_task",true);
	define("TASK_RELATION","task_relation",true);
	
	/*********Message Module***************/
	define("ALERT","alert",true);
	define("NEWS","news",true);
	define("NEWS_CATEGORY","news_category",true);
	define("ALERT_STATUS","alert_status",true);
	
	define("TBL_MESSAGE_INBOX", "tbl_message_inbox",true);
	define("TBL_MESSAGE_OUTBOX", "tbl_message_outbox",true);
	define("TBL_MESSAGE_SENT_TO", "tbl_message_sent_to",true);

	define("TBL_ATTACHMENT",'tbl_attachment',true);
	
	
	define("TBL_COUNTRIES","tbl_countries",true);
	
	define("TBL_ELEMENT_PERMISSION", "tbl_element_permission",true);

// 
	define("USER", "couleetechlink@gmail.com",true);
	define("PASS", "+pjLH@2:",true);
	define("MASTER_BROWSER_TITLE","Coulee Techlink",true);
	define("TBL_EXTERNAL_MODULE" , "user_asterisk");
	
	/**********************tables for project mod*********************/
	
	define("DEPARTMENT","department",true);
	define("IMPORTANCE_TYPE","importance_type",true);
	define("ME_TO_PROJECT",'me_to_project',true);
	define("PROJECT",'project',true);
	define("PROJECT_DOCUMENT",'project_document',true);
	define("PROJECT_USER",'project_user',true);
	define("PROJECT_CONTACT",'project_contact',true);
	define("PROJECT_STATUS",'project_status',true);
	define("USER_IN_PROJECT",'user_in_project',true);
	define("CONTACT_IN_PROJECT",'contact_in_project',true);
	define("TBL_PROJECT_PRIMARY",'project_id',true);	
	define("CONNECTED_PROJECT",'connected_project',true);
	
	/**********************Email Mod**********************/
        define("EML_EMAIL" , "eml_email" , true);
        define("EML_MESSAGE" , "eml_message" , true);
        define("WEBMAIL_ENCKEY" , "asldkbmewrqwvjlkjFLKDJSasdf" , true);
        define("EML_ADDRESS" , "eml_address" , true);
        define("EML_MESSAGE" , "eml_message" , true);
	
	//************* erp Contact Screen Custom Table ************************************//	
		define("erp_CONTACTSCREEN_CUSTOM",'erp_contactscreen_custom',true);


	  	  
	  //define("TBL_BINDING_OPTION","erp_binding_option",true);
	  define("erp_REWORK","erp_rework",true);	  
	  define("TBL_FABRIC_OPTION","erp_fabric_option",true);
	  define("TBL_SEAM_OPTION","erp_seamoption",true);
	  define("TBL_ZIPPER_OPTION","erp_zipper_option",true);
	  define("TBL_VARIBALE_INFO","erp_variableinfo",true);
	  define("TBL_PAD_OPTION","erp_pad_option",true);	 
	  define("TBL_PROFILEJV5","erp_profilejv5",true);
	  define("TBL_ELASTIC_OPTION","erp_elastic_option",true);
	  define("TBL_SEAM_CROSSING","erp_seamcrossing",true);	  
	  define("TBL_LINING_OPTION","erp_lining_option",true);
	  define("TBL_GROUPID","erp_groupid",true);
	  define("TBL_BINDING_OPTION","erp_binding_option",true);
	  define("TBL_DEFECT_CATEGORY","erp_defect_category",true);	  
	  define("TBL_NOTES","erp_notes",true);	 
	  define("TBL_WORK_ORDER_DOCUMENT","erp_work_order_document",true);
	  define("TBL_PRODUCT_SCREEN","erp_product_screen",true);	 

	  
	  //// inventry file//////	  
	  
	/****************************Field names in TBL_INVENTORY_DETAILS and TBL_VENDOR_DETAIL table******************************************/
    define("erp_PRODUCT","erp_product",true);	
	define("TBL_INVENTORY_DETAILS" , "erp_inventory_details" , true);
	define("erp_PRODUCT_ORDER","erp_product_order",true);
	define("TBL_VENDOR_DETAIL" , "vendor_details" , true);
	define("TBL_INVE_CONTACTS" , "contacts" , true);
	define("TBL_CONTACTS_ADDRESS" , "contacts_address" , true);
	define("TBL_CONTACTS_EMAIL" , "contacts_email" , true);
	define("TBL_CONTACTS_PHONE" , "contacts_phone" , true);
	

	//****************** Mt erp work order**********************************************//
	  define("TBL_FABRIC_OPTION","fabric_option",true);
	  define("TBL_SEAMOPTION","tbl_seamoption",true);
	  define("TBL_ZIPPEROPTION","zipper_option",true);
	  define("TBL_VARIBALEINFO","tbl_variableinfo",true);
	  define("TBL_PADOPTION","pad_option",true);
	 
	  define("TBL_PROFILEJV5","tbl_profilejv5",true);
	  define("TBL_ELASTICOPTION","elastic_option",true);
	  define("TBL_SEAMCROSSING","tbl_seamcrossing",true);
	  
	  define("TBL_LININGOPTION","lining_option",true);
	  define("TBL_GROUPID","tbl_groupid",true);
	  define("TBL_BINDINGOPTION","binding_option",true);	
	  
	  define("TBL_ASSIGN_REPORT_TO_SYSTEM_TASK","assign_report_to_system_task",true);	
	  define("TBL_TEMPLATE","template",true);	
	  	
	
?>
