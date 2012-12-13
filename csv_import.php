<?php
require('class/config.inc.php');

require 'class/class.contacts.php';


$page = new basic_page();

$page->auth->Checklogin();

$notify= new Notification();

$contact=new Company_Global();

$contact->SetGroups($page->auth->Get_group());

$contact->SetUserID($page->auth->Get_user_id());

$contact->SetUserName($page->auth->Get_user_name());

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("CSV IMPORT");
$page -> setActiveButton('2');
//$page -> setInnerNav('');
//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('form.css');
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');


$page -> setImportCss3('');
$page -> setImportCss4('');
$page -> setImportCss5('');
//$page -> setExtJavaScripts($external_js); // might not need



//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '
';

$page -> setCustomJavaScripts('

function validateExtension(Form_Name,Upload_File) {
		var filename = document.forms[Form_Name].elements[Upload_File].value;
		if(filename.lastIndexOf(".csv") == "-1"){
		alert("Please upload any CSV file.");
		return false;
		}
	else{
		return true;
		}
}
');


$page -> setPageStyle($page_style);

$page -> displayPageTop();

$notify->Notify();
?>

<div id="content_column_header">
Import Contacts from outlook
</div>
<div >
<?php
if(isset($_POST['submit'])) {
$contact->ImportContacts('server');
}
else {
$contact->ImportContacts('local');
}


if(isset($_POST['s1']))
{ 
	$contact->ImportContacts('csvupld');
}

?>
</div>







<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>
<div class="form_main">
	<div><a href="contact_addperson.php"><img src="images/add_person.jpg" border="0" /></a></div>
	<div><a href="contact_addcompany.php"><img src="images/add_company.jpg" /></a></div>
	<div><a href="csv_export.php"><img src="images/export_outlook.png" /></a></div>
	<div class="form_main">	<?php echo $contact->GetALLTagsAtoZ();?>	</div>
</div>






<?php
$page -> displayPageBottom();
?>