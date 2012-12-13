<?php
	
	define("PATHDRASTICTOOLS", "app_code/classes.datagrid/");

	define("TBL_EVENT_PRIMARY",'event_id',true);
	/*********Contact Module***************/
	define("EM_WEB_APP_INFO",'em_web_app_info',true);
	
	/*********Field names in EM_CERTIFICATION_TYPE table*********************/
	define("EM_CERTIFICATION_TYPE",'em_certification_type',true);
	
	define("CERTIFICATION_ID",'certification_id',true);
	define("CERT_TYPE",'cert_type',true);
	define("CREDENTIAL_TYPE",'credential_type',true);
	
	
	
	/*********Field names in EM_CERTIFICATION table*********************/
	define("EM_CERTIFICATION",'em_certification',true);
	
	define("CERTIFICATION_ID",'certification_id',true);
	define("CONTACT_ID",'contact_id',true);
	define("CERTIFICATION_TYPE_ID",'certification_type_id',true);
	define("START_DATE",'start_date',true);
	define("EXPIRATION_DATE",'expiration_date',true);
	define("NOTE",'note',true);
	define("STATUS",'status',true);
	
	
	define("EM_CONTACT_STATUS","em_contact_status",true);
	
	define("EM_STAFFING_STATUS","em_staffing_status",true);
	
	define("EM_UNAVAILABILITY_STATUS","em_unavailability_status",true);
	
	define("EM_EQUIPMENT_STATUS","em_equipment_status",true);
	
	define("EM_EQUIPMENT_ISSUE","em_equipment_issue",true);


	define("EM_CONTACT_LOCATION","em_contact_location",true);
	
	define("EM_CONTACT_DOCUMENTS","em_contact_documents",true);
	
	define("EM_MAX_DAY_SHIPPING","em_max_day_shipping",true);
	
	define("EM_DOCUMENTS",'em_documents',true);
	
	define("EM_EVENT","em_event",true);
	
	define("EM_DATE","em_date",true);
	
	define("EM_POC","em_poc",true);
	
	define("EM_SERVICES",'em_services',true);

		
	/************************APPLICATION****************************************/
	define("EM_APPLICATION_EDUCATION","em_application_education",true);
	define("EM_APPLICATION_GENERAL","em_application_general",true);
	define("EM_APPLICATION_REFERENCES","em_application_references",true);
	define("EM_APPLICATION_PREVIOUS_EMPLOYMENT","em_application_previous_employment",true);
	define("EM_APPLICATION_MILITARY_SERVICE","em_application_military_service",true);
	
	define("EM_HOTEL","em_hotel",true);
	define("EM_HOTEL_STAY","em_hotel_stay",true);
	define("EM_EVENT_STATUS","em_event_status",true);
	
	define("EM_SUGGESTION_MASTER","em_suggestion_master",true);
	
	define("EM_STAFFING","em_staffing",true);
	
	define("ZIP_CODE","zip_code",true);
	
	define("EM_RENTAL","em_rental",true);
	
	define("EM_EQUIPMENT_CATEGORY","em_equipment_category",true);
	
	define("EM_EQUIPMENT","em_equipment",true);
	
	define("EM_EVENT_EQUIPMENT","em_event_equipment",true);
	
	define("EM_EQUIPMENT_AVAILABILITY","em_equipment_availability",true);
	
	define("EM_QA_CHECK","em_qa_check",true);
	
	define("EM_CONTACT_UNAVAILABILITY","em_contact_unavailability",true);
	
	define("EM_RECRUITING_STATUS","em_recruiting_status",true);
	
	define("EM_SHIPPING","em_shipping",true);
	
	define("EM_SERVICES_TYPE","em_services_type",true);
	/********************
	MOD_ACCDEN Variables
	********************/
	define( "MOD_ACCDEN" , true );
	define( "MOD_ACCDEN_ACCEPT_EMAIL_BODY" , "
We Accept GEID: %|em_event:group_event_id|%  for %|em_event:city|%, %|em_event:state|%<br/>
%|staffline|%

" );
define( "MOD_ACCDEN_ACCEPT_SUBJECT", "We Accept GEID: %|em_event:group_event_id|% for %|em_event:city|%, %|em_event:state|%");

define( "MOD_ACCDEN_DENY_EMAIL_BODY" , "
We Deny GEID: %|em_event:group_event_id|%  for %|em_event:city|%, %|em_event:state|%
" );
define( "MOD_ACCDEN_DENY_SUBJECT", "We Deny GEID: %|em_event:group_event_id|%  for %|em_event:city|%, %|em_event:state|%");
define( "MOD_ACCDEN_EMAIL_TO" , "cmassign@logisticshealth.com" );
define( "MOD_ACCDENY_EMAIL_FROM_EMAIL" , "eventplatform@completemobiledentistry.com" );
define( "MOD_ACCDENY_EMAIL_FROM_NAME" , "Complete Mobile Dentistry" );
define( "MOD_ACCDEN_STAFF_CERTTYPE" , "DDS" );
define( "MOD_ACCDEN_PERSTAFF_LINE" , "%|staffinfo:cert_type|% - %|staffinfo:first_name|% %|staffinfo:last_name|%<br/>");
define( "MOD_ACCDEN_STAFFING_STATUS" , 20 );
	
	
	
	  define("TBL_UPLOAD","upload",true);
	  define("TBL_THEME","tbl_theme",true);
	  
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
	  define("TBL_FABRIC_ROLLS" , "erp_fabric_rolls" , true); 
	  define("erp_DROPDOWN_OPTION","erp_dropdown_options",true);
	  define("erp_INVENTORY_PRICE","erp_inventory_price",true);
	  define("erp_REWORK","erp_rework",true);	  
	  
	  //// inventry file//////	  
	  
	/****************************Field names in TBL_INVENTORY_DETAILS and TBL_VENDOR_DETAIL table******************************************/
    define("erp_SALESDATA_CUSTOM", "erp_salesdata_custom",true);
	define("erp_PRODUCT", "erp_product",true);
	//define("erp_GROUP_PRODUCT", "erp_group_product",true);	
	define("TBL_INVENTORY_DETAILS" , "erp_inventory_details" , true);
	define("erp_ASSIGN_INVENTORY" , "erp_assign_inventory" , true);
	define("erp_GROUP_INVENTORY" , "erp_inventory_group" , true);
	define("erp_PRODUCT_ORDER","erp_product_order",true);
	define("erp_ORDER","erp_order",true);	
	define("TBL_VENDOR_DETAIL" , "vendor_details" , true);
	define("TBL_INVE_CONTACTS" , "contacts" , true);
	define("TBL_CONTACTS_ADDRESS" , "contacts_address" , true);
	define("TBL_MODULE_ADDRESS" , "module_address" , true);    // For billing_address and shipping_address  
	define("TBL_CONTACTS_EMAIL" , "contacts_email" , true);
	define("TBL_CONTACTS_PHONE" , "contacts_phone" , true);
	define("erp_ITEM_TYPE", "erp_item_type",true);
	define("TBL_FABRIC_ROLLS" , "erp_fabric_rolls" , true);
    define("erp_PRINTER_PAPER" , "erp_printer_paper" , true);
	define("erp_WINDOW" , "erp_window" , true);
	define("erp_WINDOW_POSITION" , "erp_window_position" , true);
	define("TBL_NOTE_STATUS",'tbl_note_status',true);
	define("erp_GLOBAL_TASK" , "tbl_global_task" , true);
	define("erp_GLOBAL_TASK_STATUS" , "tbl_global_task_status" , true);
	define("erp_GLOBAL_TASK_STATUS_RESULT" , "tbl_global_task_status_result" , true);
	define("GLOBAL_TASK_LINK" , "tbl_global_task_link");
	define("GLOBAL_TASK" , "tbl_global_task" , true);
	define("GLOBAL_TASK_STATUS" , "tbl_global_task_status" , true);
	define("GLOBAL_TASK_TREE" , "tbl_global_task_tree" , true);
    define("erp_WORK_ORDER", "erp_work_order" , true);
	define("erp_ASSIGN_FCT", "assign_flow_chart_task" , true);
	define("erp_USER", "tbl_user" , true);
	define("erp_GROUP" , "erp_create_group" , true);
	define("TBL_USERGROUP" , "tbl_usergroup" , true);
	
	define("erp_INVENTORY_LOG" , "erp_inventory_log" , true);
	define("erp_SIZE" , "erp_size" , true);
	define("erp_SIZE_DEPENDENT" , "erp_size_dependant" , true);
	
	//..............//
	define("ASSIGN_TASK" , "assign_task" , true);
	define("TASKS" , "tasks" , true);
	define("erp_TASKS_RELATION" , "task_relation" , true);
	
	////////////////////////////////////********eapi********///////////////////////////////////////////////
	
	define("EML_INTERNAL" , "eapi.com" , true);
	//define("EML_INTERNAL" , "couleetechlink.com" , true);
	define("EML_MESSAGE" , "eml_message" , true);
	define("EML_ADDRESS" , "eml_address" , true);
	define("EML_SIGNATURE" , "eml_signature" , true);
	define("TBL_USER" , "tbl_user" , true);
	define("FLAGS" , "flags" , true);
	define("FLAG_TYPE" , "flag_type" , true);
	define("DROPDOWN_OPTION","erp_dropdown_options",true);
	define("ASSIGN_FCT","assign_flow_chart_task",true);
	define("EML_FILES" , "eml_files" , true);
	define("EML_MAILBOX" , "eml_mailboxs" , true);
	
	define("CASES" , "cases" , true);
	define("CASES_ACTIVITY" , "cases_activity" , true);
	define("ACCOUNT_DISPLAY_NAME" , "eapi_account_displayname" , true);
	define("CASE_NOTES" , "tbl_note" , true);
	define("CASE_SUBOPTION" , "cases_suboptions" , true);
	
	?>
