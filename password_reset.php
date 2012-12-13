<?php
require('class/config.inc.php');

$page = new basic_page();

$user=new User();

$notify= new Notification();

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle(MASTER_BROWSER_TITLE);
$page -> setActiveButton('1');
$page -> setInnerNav('0');
$page -> setImportCss4('');
$page -> setImportCss5('');
$page -> setExtJavaScripts3(''); // might not need
$page -> setExtJavaScripts4(''); // might not need

//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '
body {
 font-family: verdana,arial,sans-serif;
 font-size: 12px;
 line-height: 14px;
}
 
#container {
 height: 100%;
 width: 100%;
}
 
#signin_window {
 width: 312px;
 height: 261px;
 position: absolute; top: 50%; left: 50%;
 margin-top: -130px;
 margin-left: -156px;
 background: url(images/login_window_bg.gif);
}
 
.lable {
 font-size: 14px;
 text-align: right;
 margin-right: 10px;
 color: #005588;
}
 
.field {
}
 
input, password{
 font-size: 15px;
 font-family: verdana;
 width: 155px;
 height: 20px;
}
 
#signin_form {
 margin: 75px 10px 0px 10px;
}
 
#subbutt {
 height: auto;
 width: auto;
 font-size: 11px;
}
 
.italic_text {
 font-style: italic;
}
 
p {
 margin-left: 45px;
 color: #347BB3;
}
 
a { color: #347BB3; text-decoration: none; }
a:hover { color: #347BB3; text-decoration: underline; }
 
.required {
padding:3px;
font-family: Tahoma, Verdana; 
font-size: 11px;
color: #FF0000;
}
.normal {
padding:3px;
font-family: Tahoma, Verdana; 
font-size: 11px;
color: #000000;
}

#note{
position:fixed; top:0; text-align:center; width: 100%;
}
#message_t{
width:300px; margin: 0 auto; background-color: #CC0000; color:#FFFFFF ;padding: 4px; font-size:12px;
font-weight:bold;
}

#error_message_username {
 position: absolute; top: 45px; left: 125px;
 color: red;
}
#error_message_username li {
padding:3px;
font-family: Tahoma, Verdana; 
font-size: 11px;
color: #FF0000;
list-style:none outside none;
line-height: 10px;
}
#error_message_password {
 position: absolute; top: 60px; left: 125px;
 color: red;
}
#note{
position:fixed; top:0; text-align:center; width: 100%;
}
#message_t{
width:300px; margin: 0 auto; background-color: #CC0000; color:#FFFFFF ;padding: 4px; font-size:12px;
font-weight:bold;
}

}

';

$page -> setPageStyle($page_style);

    $page->printDocType();
	$page->printHTMLStart();
	$page->printHeadStart();
	$page->printCharEncod();
	$page->printTitle();
	$page->printMetaAuthor();
	$page->printMetaKeywords();
    $page->printMetaDesc();
	$page->printFOUC();
	$page->printMainStyle();
	$page->printPageStyle();
	$page->printExtJavaScripts();
	$page->printCustomJavaScripts();
	$page->printHeadEnd();
	$page->printBodyStart();

// **********************Start html for content column ****************************//
$notify->Notify();
?>
<?php
$id=$_REQUEST[id];
if($user->checkResetlink($id) and $id!=''){
	$user_id=$user->GetUserIDfromflag($id);
	?><div id="container"> 
	<div id="signin_window"> 
		<div id="error_message_username"> 
		<?php echo $_SESSION[error]; $_SESSION[error]=''; ?>
		</div> 
		<div id="error_message_password">
		</div>
	
		<div id="signin_form"> 
		<?php
	

	if($_POST[save]=='reset password')
	 $user->Reset_password('server',$user_id);
	else 
	 $user->Reset_password('local',$user_id);		
	 ?>
		 </div> 
		</div> 
	</div> 
	<?php
}
else{
$notify->SetNote('Sorry this link is not valid!!');
$notify->SetTimeout(50000);
$notify->Notify();
}
?>

<?php
	$page->printBodyEnd();
	$page->printHTMLEnd();?>