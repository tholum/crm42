<?php 
ob_start();
echo '<?php';
?>


	require_once('class/tables.config.php');

	/*
	!IMPORTANT READ ME!!!!
	Table Definitions are no longer kept in this file
	This is file SHOULD NEVER ( Or very very very very rairly be edited )
	Only things that change on a per install bases is kept in here
	for example, Database ext...

         * 	If you change something email tholum@couleetechlink.com, I will have to implement it on ALL of our production configs manually

        !IMPORTANT READ ME!!!!
        Table Definitions are no longer kept in this file
        This is file SHOULD NEVER ( Or very very very very rairly be edited )
        Only things that change on a per install bases is kept in here
        for example, Database ext...

	If you change something email tholum@couleetechlink.com, I will have to implement it on ALL of our production configs manually

        !IMPORTANT READ ME!!!!
        Table Definitions are no longer kept in this file
        This is file SHOULD NEVER ( Or very very very very rairly be edited )
        Only things that change on a per install bases is kept in here
        for example, Database ext...

	If you change something email tholum@couleetechlink.com, I will have to implement it on ALL of our production configs manually

        !IMPORTANT READ ME!!!!
        Table Definitions are no longer kept in this file
        This is file SHOULD NEVER ( Or very very very very rairly be edited )
        Only things that change on a per install bases is kept in here
        for example, Database ext...



	If you change something email tholum@couleetechlink.com, I will have to implement it on ALL of our production configs manually



        !IMPORTANT READ ME!!!!
        Table Definitions are no longer kept in this file
        This is file SHOULD NEVER ( Or very very very very rairly be edited )
        Only things that change on a per install bases is kept in here
        for example, Database ext...

  Common Module Names
  Cases: CASES
  Contacts: TBL_CONTACTS
  


	*/
	#Database Confiugreation
        //ALT_AUTH is the first name of any module in modules/auth, Current examples are active_directory and gapps
        define('ALT_AUTH' , '');
        //Salt for any encryption methods, I go to grc.com/passwords to get a random 64 char salt
        define("SALT" , "<?php 
        $char_set = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890asdf";
        $x = 0;
        while( $x != 64 ){
            echo $char_set[rand(0 , 61 )];
            $x++;
        }
?>",true);
	define("DATABASE_HOST","<?php echo $_REQUEST['database_hostname']; ?>",true);
        define("DATABASE_PORT","<?php echo $_REQUEST['database_port']; ?>",true);
        define("DATABASE_USER","<?php echo $_REQUEST['database_user']; ?>",true);
        define("DATABASE_PASSWORD","<?php echo $_REQUEST['database_password']; ?>",true);
	define("DATABASE_NAME","<?php echo $_REQUEST['database_name']; ?>",true);

        //****** MAIL TEST *****//
        //define("MAIL_DEFAULT_DOMAIN" , "voip.couleetechlink.com" , true );
        //define("WEBMAIL" , true , true );

        //can be changed to any imap host, if they have there own email server
        define("EML_IMAP_HOST","imap.gmail.com" , true );

        //********* Phone System Config ********/
        //define("PHONE_SYSTEM" , "asterisk" , true); // Currently only none and asterisk is avalible
	define("PHONE_SYSTEM" , "none" , true );
        //Have not used this for a while, I may remove it
	define("ASTERISK_DATABASE_HOST" , "server1.voip.couleetechlink.com" , true );
        define("ASTERISK_DATABASE_PORT" , "3306" , true );
        define("ASTERISK_DATABASE_USER" , "" , true );
        define("ASTERISK_DATABASE_PASSWORD", "" , true );
        define("ASTERISK_DATRABASE_NAME" , "" , true );
        define("ASTERISK_ACCOUNT_CODE" , "101" , true);
        define("ASTERISK_COMPANY_NAME" , "ctl" , true);
        define("ASTERISK_CONTEXT" , ASTERISK_COMPANY_NAME . "-" . ASTERISK_ACCOUNT_CODE . "-main" , true);
        define("ASTERISK_MAILBOX_CONTEXT" , ASTERISK_COMPANY_NAME . ASTERISK_ACCOUNT_CODE , true);
        define("USER_ASTERISK" , 'user_asterisk' , true);
        //For Imap attachments
        define("TMP_UPLOAD","/home/apache/tmp/" );
        define("IMAP_ATTACHMENTS","/home/apache/imap_attachments/");
        //*************** ZIMBRA CONFIG ****************//
        // The code is still in here to make it work, but I have not used it in a while
        define("ZIMBRA_EMAIL" , "" , true );
        define("ZIMBRA_PREAUTH_KEY" , "", true);
        define("ZIMBRA_PREAUTH_URL" , "" , true );
        //*************** WEBMAIL CONFIG ***************//
        //we use use round cube before we created our own email system
        define("RCMAIL_DEFAULT_HOST" , 'ssl://imap.gmail.com' , true );
        define("RCMAIL_DEFAULT_PORT" , '993' , true );
        define("RCMAIL_DEFAULT_DOMAIN" , "@gmail.com" , true );
        define("EMAIL_SYSTEM" , "zimbra" , true); //zimbra , roundcube and probably gmail and exchange in future builds
        //***************Chat Config ******************//
        // At one time we used ejabberd for a chat system, again not used as of now, but he code is probably still in here
        define("CHAT_SYSTEM" , "ejabberd");
        //******** FOR EJABBERD CHAT *******************//
        /* currently the only supported chat server     */
        define("EJABBERD_DATABASE_HOST","127.0.0.1",true);
        define("EJABBERD_DATABASE_PORT","3306",true);
        define("EJABBERD_DATABASE_USER","",true);
        define("EJABBERD_DATABASE_PASSWORD","",true);
        define("EJABBERD_DATABASE_NAME","ejabberd",true);

        //**************************** EXTRA CONFIGS ***********************************/
        define("WELCOME_SCREEN_TITLE" , "Welcome to SlimCRM ");
        define("WELCOME_SCREEN_BODY" , "Old Welcome Screen, Not used" , true);

        /*********************Feature Settings ******************/
        define("LIVE_PHONE_STATUS" , true , true );
        /********************* PayFlow Pro Settings *************/
        /* We had payflow pro working, there are a few system task's for recieving payments
         * This has never been in a production system though, so I can not attest to its stability */
        define("PAYFLOW_VENDOR","",true);
        define("PAYFLOW_USER" , "" , true);
        define("PAYFLOW_PARTNER" , "" , true);
        define("PAYFLOW_PASSWORD" , "",true);
        define("PAYFLOW_MODE", 1 , true); // 0 for production 1 for testing
        /********************* UPS INFO ************************/
        /* As with the PayFlow Pro, this is complete but has never been in a production system */
        define("UPS_API_KEY" , "" , true );
        define("UPS_API_SERVER" , "https://wwwcie.ups.com" );
        define("UPS_USERNAME" , "");
        define("UPS_PASSWORD" , "");
        define("UPS_DEFAULT_FROM_STREET" , "");
        define("UPS_DEFAULT_FROM_CITY" , "");
        define("UPS_DEFAULT_FROM_STATE" , "");
        define("UPS_DEFAULT_FROM_ZIP" , "");
        define("UPS_DEFAULT_FROM_COUNTRY" , "");
        define("UPS_DEFAULT_SHIPPER_NAME", "");
        define("UPS_DEFAULT_SHIPPER_PHONE", "");
        define("UPS_DEFAULT_SHIPPER_NUMBER" , "");
        /********************* FILESERVER INFO ****************/
        /* This is for files, currently FILESERVER_LOCAL_PATH is the onlyone which as to be set, we had an option 
         * Where we made custum links for the remote ( but we could not get mac support on opening files ) so we removed
         * that option */
        define("FILESERVER_LOCAL_PATH", "/var/www/webdevel/tmp/");
        define("FILESERVER_REMOTE_PATH" , "smb:\\\\192.168.1.58/public/tmp/");
        //define( "UPS_DEFAULT_SHIPPER_NUMBER" , "");
        //define("UPS_DEFAULT_SHIPPER_ATTN", "");
	 function config_slimcrm_autostart(){
            $array = array();
            $array[] = array( "url" => 'welcome.php' , "name" => 'Home' , "image" => "images/home.png" , "access" => "ALL" );
            return $array;
        }

        function config_slimcrm_menu(){
            $array = array();
            $array[] = array( "id" => "workspace" , "url" => 'workspace' , "name" => 'Work Space' , "image" => "images/lock.png" , "access" => "ALL" );
            $array[] = array( "id" => "email_button" , "url" => 'emaildashboard' , "name" => 'Email' , "image" => "images/lock.png" , "access" => "ALL" );
            $array[] = array( "id" => "Contacts" , "url" => 'contacts' , "name" => 'Contacts' , "image" => "images/lock.png" , "access" => "ALL" );
            $array[] = array( "id" => "Cases" , "url" => 'casedashboard' , "name" => 'Cases' , "image" => "images/lock.png" , "access" => "ALL" );
            $array[] = array( "id" => "Calendar" , "url" => 'calendar' , "name" => 'Calendar' , "image" => "images/lock.png" , "access" => "ALL" );
            $array[] = array( "id" => "bucket_search_button" , "url" => 'bucket_searchscreen' , "name" => 'Flow Chart Tasks' , "image" => "images/lock.png" , "access" => "ALL" );
            $array[] = array( "id" => "twitter" , "url" => 'twitter' , "name" => 'Twitter' , "image" => "images/lock.png" , "access" => "ALL" );
            $array[] = array( "id" => "Settings" , "url" => 'settings' , "name" => 'Settings' , "image" => "images/lock.png" , "access" => "ALL" );
            $array[] = array( "id" => "Admin" , "url" => 'admin' , "name" => 'Admin' , "image" => "images/lock.png" , "access" => "ALL" );
            $array[] = array( "id" => "logout_button" , "script" => 'window.location=\'signout.php\';' , "name" => 'Sign out' , "image" => "images/close.png" , "access" => "ALL" );
            $array[] = array( "id" => "bucket" , "script" => 'window.open(\'FlowChartTasks.php\' , \'fct\');' , "name" => 'Flow Chart Task Editor' , "image" => "images/close.png" , "access" => "ALL" );

            return $array;
        }



<?php 
echo '?>';
$default_config = ob_get_contents();
ob_end_clean();
?>