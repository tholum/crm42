<?php
$debug = $_GET["debug"];
if( $debug == "yes"){
    ini_set("display_errors" , 1 );
}
require_once ('class/config.inc.php');
require_once('app_code/global.config.php');
require_once 'class/class.contacts.php';
require_once 'class/class.tasks.php';
require_once 'class/class.file.php';

$page = new basic_page();
$page->auth->Checklogin();

$ajax = new PHPLiveX();

$contact = new Company_Global();

$task = new Tasks();

$file = new File();

$ajax->AjaxifyObjects(array("contact"));  

$task -> SetUserObject($user);

$task -> SetUserID($page->auth->Get_user_id());

$user = new User();

$contact->SetUserID($page->auth->Get_user_id());

$contact->SetUserName($page->auth->Get_user_name());

$contact->SetContactID($_REQUEST[contact_id]);


$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("TAG");
$page -> setActiveButton('2');
//$page -> setInnerNav('');
//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('form.css');
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');


$page -> setImportCss3('css/JTip.css');
$page -> setImportCss4('');
$page -> setImportCss5('');
$page -> setExtJavaScripts2('<script language="javascript" SRC="js/jtip.js"></script>'); // might not need
$page -> setExtJavaScripts1('<script language="javascript" SRC="js/jquery.js"></script>'); // might not need



//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '
';

$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//
$ajax->Run(); // Must be called inside the 'html' or 'body' tags  
?>

<div id="content_column_header">
viewing items tags as: <?php echo $contact->Get_Tag_Name($_REQUEST[tag_id]); ?>
	<div class="form_main">
		<div class="head"></div>
		
	</div>
</div>
<div class="form_main">
	<div id="search_result"><?php $contact->GetModule_id_inTag($_REQUEST[tag_id], $contact, $task, $file,$page); ?></div>
</div>








<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>
<div class="form_main">
	<div><a href="contact_addperson.php"><img src="images/add_person.jpg" border="0" /></a></div>
	<div><a href="contact_addcompany.php"><img src="images/add_company.jpg" /></a></div>
	<div class="form_main">	<?php echo $contact->GetALLTagsAtoZ();?>	</div>
</div>





<?php
$page -> displayPageBottom();
?>