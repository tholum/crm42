
<?php
//ini_set("display_errors" , 1 );
//echo "TEST";
require_once('app_code/config.inc.php');

require_once 'class/class.tasks.php';

require_once('class/class.message.php');

require_once('class/class.news.php');

require_once 'class/class.contacts.php';

require_once 'class/class.bugs.php';

require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Calendar');
Zend_Loader::loadClass('Zend_Http_Client');

require_once('class/class.calendar.php');

if( PHONE_SYSTEM == "asterisk"){
    require_once "class/class.asterisk.php";
    $asterisk = new Asterisk;
}
if( EMAIL_SYSTEM == 'gmail' ){
$cal=new GCalendar(USER,PASS);
}
$page = new basic_page();

$user=new User();

$page->auth->Checklogin();

$notify= new Notification();

$contact = new Company_Global();

$contact->SetUserID($page->auth->Get_user_id());

$contact->SetUserName($page->auth->Get_user_name());

$news= new News();

$message= new Message();

$ajax = new PHPLiveX();

$user = new User();

$task = new Tasks();

$bugs = new Bugs();

$task -> SetUserObject($user);

$task -> SetUserID($page->auth->Get_user_id());

$ajax -> AjaxifyObjects(array("task","bugs"));  

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("BUG LIST");
$page -> setActiveButton('1');
$page -> setInnerNav('0');
$page -> setImportCss1('main_style.css');
$page -> setImportCss2('welcome.css');
$page -> setImportCss3('form.css');

$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_3('welcome_1.php');



$page -> setImportCss4('autocomplete/jssuggest.css');
$page -> setImportCss5('src/css/jscal2.css');
$page -> setImportCss6('src/css/border-radius.css');
$page -> setImportCss7('src/css/win2k/win2k.css');
$page -> setImportCss8('css/jquery.treeview.css');

$page -> setExtJavaScripts1('<script src="js/301a.js" type="text/javascript"></script>'); // might not need
$page -> setExtJavaScripts2('<script type="text/javascript" src="src/js/jscal2.js"></script>'); // might not need
$page -> setExtJavaScripts3('<script type="text/javascript" src="src/js/lang/en.js"></script>'); // might not need
$page -> setExtJavaScripts4('<script type="text/javascript" src="autocomplete/jquery-1.2.6.min.js"></script>');
$page -> setExtJavaScripts5('<script type="text/javascript" src="autocomplete/jquery.jSuggest.1.0.js"></script>');
$page ->setExtJavaScripts6('<script type="text/javascript" src="/tholum/platform/audio-player/audio-player.js"></script>
         <script type="text/javascript">
             AudioPlayer.setup("/tholum/platform/audio-player/player.swf", {
                 width: 290
             });
         </script>');
$page -> setExtJavaScripts7('<script type="text/javascript" src="js/demo.js"></script>');
$page -> setExtJavaScripts8('<script type="text/javascript" src="js/jquery.treeview.js"></script>');
$page -> setExtJavaScripts9('<script type="text/javascript" src="js/jquery.cookie.js"></script>'); 

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
form ul.holder {
    width: 99% !important;
	}
#lightbox { margin-top: 50px; !important }';


$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//
$notify->Notify();
$ajax->Run(); // Must be called inside the 'html' or 'body' tags   


if($_REQUEST['submit']=='submit'){
	echo $bugs->bugTracking('server');
}

?>
<div id="div_bugs"   class="" style="display:none;"></div>
<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>

<div id="content_column_header">
	<?php echo $page->auth->WelcomeMessage(); ?>
</div>

<?php echo $bugs->showBugProject();?>

<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>

<h3>Find a Contact</h3>
<input type="text" class="info_column_search" id="search" name="search" autocomplete='off'/>
<div id="display">
</div>

<a  href="#"
 onclick="javascript: bugs.bugTracking('local',
					{ onUpdate: function(response,root){
							 document.getElementById('div_bugs').innerHTML=response;
		  				 	 document.getElementById('div_bugs').style.display='';		
							 }, preloader: 'prl'
						} ); return false;" ><img src="images/add_bugs.jpg" alt="Bug Tracking" />
</a>		
<?php
$page -> displayPageBottom();
?>