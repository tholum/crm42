<?php
require('class/config.inc.php');

require 'class/class.tasks.php';

require('class/class.message.php');

require('class/class.news.php');

$page = new basic_page();

$user=new User();

$page->auth->Checklogin();

$notify= new Notification();

$task = new Tasks();

$news= new News();

$message= new Message();

$task -> SetUserObject($user);

$task -> SetUserID($page->auth->Get_user_id());

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("SUPPORT");
$page -> setActiveButton('support');
$page -> setInnerNav('0');
//$page -> setImportCss1('main_style.css');

$page -> SetDynamicCSS_1('main_style.php');
//$page -> SetDynamicCSS_2('form.php');


$page -> setImportCss2('support.css');
$page -> setImportCss3('autocomplete/jssuggest.css');
$page -> setImportCss4('');
$page -> setImportCss5('');
$page -> setExtJavaScripts1('<script type="text/javascript" src="autocomplete/jquery-1.2.6.min.js"></script>');
$page -> setExtJavaScripts2('<script type="text/javascript" src="autocomplete/jquery.jSuggest.1.0.js"></script>');
$page -> setExtJavaScripts3('');
$page -> setExtJavaScripts4('');
$page -> setCustomJavaScripts('
$(function(){
	$("#search").jSuggest({
		url: "search.php",
		data: "pattern",
		autoChange: false,
		minchar: 1,
		delay: 0,
		type: "GET"
		
	});
});

');


//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '
';

$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//
$notify->Notify();
?>

<div id="content_column_header">
	<p>Looking for Help?  You've Come to the Right Place!</p>
</div>

<h2>Please Select a Support Option Below:</h2>

<div class="content_column_box">
	<h3>Option #1: <span class="emp_text">Self Support</span></h3>
	<img src="images/support_1.jpg" alt="self support" class="support_image" />
	<p class="desc_para">Find solutions to your problems online.</p>
	<p class="info_para">We are in the process of creating a self-help section including tutorials, videos, and a Frequently Asked Questions (FAQ) section.</p>
	<p class="info_para">Until this option is complete, please contact your manager for support or use the paid support option to the right.  Thanks!</p>
</div>

<div class="content_column_box">
	<h3>Option #2: <span class="emp_text">Paid Support</span></h3>
	<img src="images/support_2.jpg" alt="self support" class="support_image" />
	<p class="desc_para">Real time support.</p>
	<br class="clear" />
	<ul>
		<li>Outlook setup</li>
		<li>Virus removal</li>
		<li>Computer repair</li>
		<li>Managed services</li>
		<li>Software upgrades</li>
		<li>Network setup</li>
	</ul>
	<ul>
		<li>Printer issues</li>
		<li>Data backup service</li>
		<li>Server maint.</li>
		<li>Hardware installs</li>
		<li>Guaranteed results</li>
	</ul>

	<p class="info_para">Most issues can be resolved via remote support in 15 minutes or less.  We’ll fix your computer where it stands minimizing your downtime.</p>
	<p class="footer_left">$19.95 for first 15 min, $14.95 after.</p>
	<div class="footer_right"><input type="button" value="Instant support" onclick="window.location='http://www.couleetechlink.com/remote/'"/></div>
	<br class="clear" />
</div>





<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>

<h3>Find a Contact</h3>
<input type="text" class="info_column_search" id="search" name="search" autocomplete='off'/>
<div id="display">
</div>


<?php
$page -> displayPageBottom();
?>