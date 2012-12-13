<?php
//ini_set("display_errors" , "1");
require('class/config.inc.php');

$page = new basic_page();

$user=new User();

$notify= new Notification();

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("LOGIN");
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
 background: #3d94f6;
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
 
#forgetpassword_form {
 margin: 51px 10px 0px 10px;
}

#forgetpassword_form p {
border-top:1px dashed #CCCCCC;
margin-left: 0px;
}

#forgetpassword_form #help_text {
color:#222222;
font-size:12px;
line-height:1.4em;
margin:0 0 15px;
}

#forgetpassword_form input[type=text] {
padding:3px;
width:250px;
margin-bottom:5px;
}

input[type=submit] {
font-size:14px;
padding:3px;
}

#forgetpassword_form h2 {
margin-bottom:5px;
font-size:13px;
}
 
#forgetpassword_form #error_message_email {
left:15px;
position:absolute;
top:8px;
color: #FFFFFF;
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
 
a { color: white; text-decoration: none; }
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
visiblity : hidden;
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

}
a{
    color: white !important;
}
#signin_form {
    background:white;border-radius: 15px;  box-shadow: 0 0 10px 0 #000 inset; border: 5px;padding: 10px;
}
';
$page->setExtJavaScripts('<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js" ></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" ></script>
        <link rel="stylesheet" type="text/css" href="css/workspace.css" /> ');
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
<div id="container"> 
	<div id="signin_window"> 
		<div id="error_message_username"> 
		<?php echo $_SESSION[error]; $_SESSION[error]=''; ?>
		</div> 
		<div id="error_message_password">
		</div>
	
<?php
extract($_REQUEST);
switch($index){
	case 'Forget' :
		?>
		 <?php
			if($submit=="Send me reset instructions")
				$user->ForgetPassword("server");
			else
				$user->ForgetPassword("local");	
			?>
			<?php
			break;
	default :
		?><div id="signin_form"> <?php
		if(!$page->auth->checkAuthentication()){
			if($_POST[login]=='Login')
				$user->UserLogin('server');
			else 
				$user->UserLogin('local');
		}
		else
                    //header("Location: iframe.php");
                    $page->auth->GotoWelcomePage();
		?>
		</div> 
		<p><a href="<?php echo $_SERVER['PHP_SELF'] ?>?index=Forget">Forgot your <span class="italic_text">username</span>
		 or <span class="italic_text">password</span>?</a></p> 
		<?php
}
?>
		
	</div> 
</div> 

<?php
	$uname = $_REQUEST["username"];
	if( $uname != '' ){
		echo '<script type="text/javascript"> document.getElementById("user_name").value = "' . $uname . '";</script>';
	}
	$page->printBodyEnd();
	$page->printHTMLEnd();?>
