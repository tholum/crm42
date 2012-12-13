<?php
require('class/config.inc.php');
require('class/class.news.php');
require('class/class.message.php');

$page = new basic_page();

/*******Checking Authentication****************/

$page->auth->Checklogin();

$notify = new Notification();

/**********************************************/

$message= new Message();

$news= new News();

$news->SetUserID($page->auth->Get_user_id());

/*******Setting Page access Rules & checking Authorization****************/


/************************************************************************/
					
$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("NEWS");
$page -> setActiveButton('7');
$page -> setInnerNav('');
//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('form.css'); // each page should have it's own .css sheet.  Link mulitple sheets here and below
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');

$page -> setImportCss3('css/news.css');
$page -> setImportCss4('css/message.css');
//$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_3('css/message.php');


$page -> setImportCss5('');
$page -> setExtJavaScripts3('<script src="tiny_mce/tiny_mce.js" type="text/javascript" charset="utf-8"></script>'); // might not need
$page -> setExtJavaScripts4(''); // might not need


$page -> setCustomJavaScripts('tinyMCE.init({
								mode : "textareas",
								theme : "advanced" ,
								theme_advanced_buttons1 : "bold,italic,underline,ustifyleft,justifycenter,justifyright,justifyfull,undo,redo,link,unlink,forecolor",
								theme_advanced_buttons2 : "",
								theme_advanced_buttons3 : ""
								});
function getText(id)
{
return tinyMCE.get(id).getContent();
}
								');
								
//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '';

$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//

$notify->Notify();

?>
<div id="content_column_header">
News
</div>
<div class="form_main">
	<?php 
	if($page->auth->isAdmin()){
		if($_POST['submit']=='add news')
		$news->AddNews('server');
		else
		$news->AddNews('local');
	}
	?>
</div>

<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>

<div class="form_main">
	<div><a href="message.php"><img src="images/new_message.gif" border="0" alt="" /></a></div>
</div>


<div id="content_column_right">
	<div class="data_list">
		<h3>Your Recent Messages</h3>
		<a class="view_all_link" href="message.php?index=Inbox">view all</a>
		<ul class="item_list">
			<?php $message->GetRecentMessages($page->auth->Get_user_name(),5); ?>
		</ul>
	</div>
	
	<div class="data_list">
		<h3>Recent News</h3>
		<a class="view_all_link" href="read_news.php">view all</a>
		<ul class="item_list">
			<?php $news->Get_Recent_News(4); ?>
		</ul>
	</div></div>
<?php
$page -> displayPageBottom();
?>