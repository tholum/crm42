<?php
require('class/config.inc.php');
require('class/class.message.php');
require('class/class.news.php');
require('class/class_attachment.php');

$att= new Attachment();
$page = new basic_page();



/*******Checking Authentication****************/

$page->auth->Checklogin();

$notify = new Notification();

$news= new News();

$message= new Message();

/**********************************************/

/*******Setting Page access Rules & checking Authorization****************/


/************************************************************************/
					
$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("MESSAGE");
$page -> setActiveButton('7');
$page -> setInnerNav('');

$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php'); // each page should have it's own .css sheet.  Link mulitple sheets here and below

$page -> setImportCss3('css/news.css');
//$page -> setImportCss4('css/message.css');
$page -> SetDynamicCSS_3('css/message.php');

$page -> setImportCss5('test.css');
//$page -> setImportCss6('welcome.css');

$page -> setExtJavaScripts3('<script src="protoculous-effects-shrinkvars.js" type="text/javascript" charset="utf-8"></script>'); // might not need
$page -> setExtJavaScripts4('<script src="textboxlist.js" type="text/javascript" charset="utf-8"></script>'); // might not need
$page -> setExtJavaScripts5('<script src="test.js" type="text/javascript" charset="utf-8"></script>'); // might not need
$page -> setExtJavaScripts1('<script src="tiny_mce/tiny_mce.js" type="text/javascript" charset="utf-8"></script>'); // might not need

$page -> setCustomJavaScripts('
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
	Messages
	<div align="right" class="verysmall_text"><a href="<?php echo $_SERVER['PHP_SELF'].'?index=Inbox' ?>">Received Messages</a> | <a href="<?php echo $_SERVER['PHP_SELF'].'?index=Outbox' ?>">Sent Messages</a> | <a href="<?php echo $_SERVER['PHP_SELF'] ?>">Compose Messages</a> </div>
</div>
<div class="form_main">
<?php
if($_GET[index]=='' or $_GET[index]=='Reply' or $_GET[index]=='toReply' or $_GET[index]=='ReplyOutbox') { ?>
<script type="text/javascript">
tinyMCE.init({
								mode : "textareas",
								theme : "advanced" ,
								theme_advanced_buttons1 : "bold,italic,underline,ustifyleft,justifycenter,justifyright,justifyfull,undo,redo,link,unlink,forecolor",
								theme_advanced_buttons2 : "",
								theme_advanced_buttons3 : ""
								});
								
        document.observe("dom:loaded", function() {
                  // init
          tlist2 = new FacebookList("facebook-demo", "facebook-auto");
          
          // fetch and feed
          new Ajax.Request("Usersjson.php?to=user", {
            onSuccess: function(transport) {
                transport.responseText.evalJSON(true).each(function(t){tlist2.autoFeed(t)});
            }
          });
		  
		  tlist3 = new FacebookList("facebook-demo3", "facebook-auto1");
          
          // fetch and feed
          new Ajax.Request("Usersjson.php?to=group", {
            onSuccess: function(transport) {
                transport.responseText.evalJSON(true).each(function(t){tlist3.autoFeed(t)});
            }
          });
        });  
							
</script>

<?php } ?>
<?php
switch($_GET[index]){
	case 'Inbox' :
					$message->GetInbox($_SESSION['user_name']);
					break;
	case 'Outbox' :
					$message->GetOutbox($_SESSION['user_name']);
					break;
	case 'ReadInbox' :
					$message->ReadMessageInbox($_GET['omessage_id'],$_GET['imessage_id'],$att,$_SESSION['user_name']);
					break;
	case 'ReadOutbox' :
					$message->ReadMessageOutbox($_GET['omessage_id'],$att,$_SESSION['user_name']);
					break;
	case 'Reply' :
					if($_POST[reply]=="Reply")
						$message->Reply('local',$_POST['omessage_id'],$_POST['imessage_id'],'reply');
					else if($_POST[reply_to_all]=="Reply to all")
						$message->Reply('local',$_POST['omessage_id'],$_POST['imessage_id'],'reply_to_all');
					break;
	case 'toReply' :
					if($_POST[go]=="Go")
						$message->Reply('server',$_POST['omessage_id'],$_POST['imessage_id'],'reply');
					else
						$message->Reply('local',$_POST['omessage_id'],$_POST['imessage_id'],'reply');
					break;
	case 'ReplyOutbox' :
					if($_POST[go]=="Go")
						$message->Reply('server',$_POST['omessage_id'],$_POST['imessage_id'],'reply_outbox');
					else
						$message->Reply('local',$_POST['omessage_id'],$_POST['imessage_id'],'reply_outbox');
					break;

	case 'DeleteInbox' :
						$message->DeleteMessageInbox($_GET[imessage_id]);
						break;

	default :
					if($_POST[go]=='send')
						$message->SendMessage('server',$att);
					else 
						$message->SendMessage('local',$att);
						

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
<?php
if($page->auth->isAdmin()){
?>
	<div><a href="news.php"><img src="images/new_news.gif" border="0" alt="" /></a></div>
<?php } ?>
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
	</div>
</div>

<?php
$page -> displayPageBottom();
?>