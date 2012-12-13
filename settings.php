<?php
require_once('class/config.inc.php');
$page = new basic_page();

/*******Checking Authentication****************/

$page->auth->Checklogin();

$user = new User();
$notify = new Notification();

$ajax=new PHPLiveX();

$ajax->AjaxifyObjects(array("user"));  
//$user->Set_User_Id($page->auth->Get_user_id());


/**********************************************/

/*******Setting Page access Rules & checking Authorization****************/

$access_rule = array(	"Admin"		=>"Admin" 
					);
$page -> setAccessRules($access_rule);
$page -> setAccessRulesType('all');		// any or all
//$page -> CheckAuthorization();

/************************************************************************/
					
$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("SETTINGS");
$page -> setActiveButton('5');
$page -> setInnerNav('');
//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('form.css'); // each page should have it's own .css sheet.  Link mulitple sheets here and below

$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');

$page -> setImportCss3('css/JTip.css');
$page -> setImportCss4('');
$page -> setImportCss5('');
$page -> setExtJavaScripts2('<script language="javascript" SRC="js/jtip.js"></script>'); // might not need
$page -> setExtJavaScripts1('<script language="javascript" SRC="js/jquery.js"></script>'); // might not need
$page -> setExtJavaScripts3('<script language="javascript" SRC="tiny_mce/tiny_mce.js"></script>'); // might not need
$page -> setExtJavaScripts4(''); // might not need
$page -> setCustomJavaScripts('tinyMCE.init({
								mode : "textareas",
								theme : "advanced" ,
								theme_advanced_buttons1 : "bold,italic,underline,ustifyleft,justifycenter,justifyright,justifyfull,undo,redo,link,unlink,forecolor",
								theme_advanced_buttons2 : "",
								theme_advanced_buttons3 : "",
								width: "400px"
								});
	function sendForm(form){  
     return PLX.Submit(form, {  
         "preloader":"prl",  
         "onFinish": function(response){  
             alert(response);  
         }  
     });  
 } ');


//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '';

$page -> setPageStyle($page_style);

$page -> displayPageTop();

$ajax->Run('js/phplivex.js'); // Must be called inside the 'html' or 'body' tags  

// **********************Start html for content column ****************************//

$notify->Notify();

$index=$_REQUEST[index];

?>
<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif" /></div>
<div id="content_column_header" class="textb small_text">
	<span>Settings</span>
</div>
<div class="form_main">

<?php
if(isset($_POST[filename])){
	$user->UpdatePhoto($_POST);
}

extract($_POST);

switch($index){ 
case 'Edit' :
		if($submit=='Update Profile'){
			$user->Update_Profile('server',$page->auth->Get_user_id());
		}
		else{
			$user->Update_Profile('local',$page->auth->Get_user_id());
		}
		break;
default :
		$user->Display_Profile($page->auth->Get_user_id()); 
}
?>
</div>

<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>
<div id="content_column_header">
	<h2>Change Password: </h2>
</div>

<div class="form_bg">
<div class="form_main">
<?php 
	$user->profilePassword_Change();
?>
</div>
</div>


<?php
$page -> displayPageBottom();
?>