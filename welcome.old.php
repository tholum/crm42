
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
require_once('class/class.CapacityReport.php');
require_once('class/class.WorkOrder.php');

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
$capacity = new CapacityReport();
$workorder = new WorkOrder();

$task -> SetUserObject($user);
$task -> SetUserID($page->auth->Get_user_id());
$ajax -> AjaxifyObjects(array("task","bugs","capacity","workorder"));  

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("WELCOME");
$page -> setActiveButton('1');
$page -> setInnerNav('0');
$page -> setImportCss2('welcome.css');

$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_3('welcome_1.php');



$page -> setImportCss4('autocomplete/jssuggest.css');
$page -> setImportCss5('src/css/jscal2.css');
$page -> setImportCss6('src/css/border-radius.css');
$page -> setImportCss7('src/css/win2k/win2k.css');
$page -> setImportCss8('autocomplete/style.css');

$page -> setExtJavaScripts1('<script src="js/301a.js" type="text/javascript"></script>'); // might not need
$page -> setExtJavaScripts2('<script type="text/javascript" src="src/js/jscal2.js"></script>'); // might not need
$page -> setExtJavaScripts3('<script type="text/javascript" src="src/js/lang/en.js"></script>'); // might not need
$page -> setExtJavaScripts4('<script type="text/javascript" src="autocomplete/jquery-1.2.6.min.js"></script>');
$page -> setExtJavaScripts5('<script type="text/javascript" src="autocomplete/jquery.jSuggest.1.0.js"></script>');
$page -> setExtJavaScripts8('<script src="autocomplete/jquery.fcbkcomplete.js" type="text/javascript" charset="utf-8"></script>');
$page ->setExtJavaScripts6('<script type="text/javascript" src="/tholum/platform/audio-player/audio-player.js"></script>
         <script type="text/javascript">
             AudioPlayer.setup("/tholum/platform/audio-player/player.swf", {
                 width: 290
             });
         </script>');
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

	
	function autoProduct(){
	   $(document).ready(function() {        
		$("#add_product").fcbkcomplete({
		json_url: "useslist.php?type=workorder",
		cache: false,
		filter_hide: true,
		filter_selected: true,
		maxitems: 1,
	  });		 
	});	
	}	


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

?>
<div id="div_bugs"   class="" style="display:none;"></div>
<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>

<div id="content_column_header">
	<?php echo $page->auth->WelcomeMessage(); ?>
</div>

<div id="content_column_left">
	<h2><?php echo WELCOME_SCREEN_TITLE;?></h2>
	<?php echo WELCOME_SCREEN_BODY; ?>
</div>

<div id="content_column_right">

	<?php if( EMAIL_SYSTEM == 'gmail' ){ ?>
	<div class="data_list">
		<h3>Upcoming Dates</h3>
		<a class="view_all_link" href="calendar.php">view all</a>
		<ul class="item_list">
		<?php $cal->view_event('',5,'calendar.php'); ?>
		</ul>
	</div>
	<?php } if( PHONE_SYSTEM == "asterisk" ){ ?>
	<div class="data_list">
		<h3>Voice Mail</h3>
		<a class="view_all_link" href="read_news.php">view all</a>
		<ul class="item_list">
			<?php $asterisk->display_mailbox( $page->auth->Get_user_id() , 5 ); ?>
		</ul>
	</div>
	<?php } ?>
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
<div id="div_capacity_on_fly"   class=""></div>
<a href="contact_addcompany.php">Add Customer</a><br />
<a href="contacts.php">Add Order</a><br />
<a href="CapacityReport.php">Availability</a><br />
<!--<a href="javascript:void(0);" onclick="javascript: capacity.checkCapacityOnFly({ preloader: 'prl',
																				   onUpdate: function(response,root){
																					document.getElementById('div_capacity_on_fly').innerHTML=response;
																					autoProduct();
																				}});">Availability</a><br />-->


	

<div class="form_bg" style="display:none;" id="task_form">
<?
if($_POST['Save']=='Add this Task')
$task->AddTask('server');
else
 $task->AddTask('local');
?>
<div class="Clear"><a href="" onclick="document.getElementById('task_link').style.display=''; document.getElementById('task_form').style.display='none'; return false;">cancel</a></div>
</div>

<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>

<h4>Tasks</h4>
<a href="task.php?contact_id=<?php echo $contact->GetContactID(); ?>" id="task_link" onclick="document.getElementById('task_form').style.display=''; this.style.display='none'; return false;">Add Task</a><br />
<div class="form_main">
<div id="task_area" class="small_text">
<?php echo $task->GetTask('','','','','','','',1); ?>
</div>
</div>
<!--Availability - Contract Pg 3, 1.5
--><?php
$page -> displayPageBottom();
?>

