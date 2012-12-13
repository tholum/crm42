<?php
require('class/config.inc.php');

$page = new basic_page();

$notify= new Notification();

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("SIGN OUT");
$page -> setActiveButton('1');
$page -> setInnerNav('0');
//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('form.css'); // each page should have it's own .css sheet.  Link mulitple sheets here and below
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

$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//
$notify->SetNote('Please Wait... . .');
$notify->Notify();
$page->auth->Destroy_Session();
?>

<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>

<?php
$page -> displayPageBottom();
?>