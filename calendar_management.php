<?php
require_once('class/config.inc.php');

$page = new basic_page();

/*******Checking Authentication****************/

$page->auth->Checklogin();

$user = new User();

$notify = new Notification();

require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Calendar');
Zend_Loader::loadClass('Zend_Http_Client');

require_once('class/class.calendar.php');

$cal=new GCalendar(USER,PASS);


/**********************************************/

/*******Setting Page access Rules & checking Authorization****************/

/*$access_rule = array(	"Admin"		=>"Admin" 
					);
$page -> setAccessRules($access_rule);
$page -> setAccessRulesType('all');		// any or all
$page -> CheckAuthorization();*/

/************************************************************************/
					
$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("CALENDAR MANAGEMENT");
$page -> setActiveButton('6');
$page -> setInnerNav('');

//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('form.css'); // each page should have it's own .css sheet.  Link mulitple sheets here and below
$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');

$page -> setImportCss3('src/css/jscal2.css');
$page -> setImportCss4('src/css/border-radius.css');
$page -> setImportCss5('src/css/win2k/win2k.css');
//$page -> setImportCss6('css/message.css');
$page -> SetDynamicCSS_1('css/message.php');



$page -> setExtJavaScripts1('<script src="js/301a.js" type="text/javascript"></script>'); // might not need
$page -> setExtJavaScripts2('<script type="text/javascript" src="src/js/jscal2.js"></script>'); // might not need
$page -> setExtJavaScripts3('<script type="text/javascript" src="src/js/lang/en.js"></script>'); // might not need
$page -> setExtJavaScripts4(''); // might not need


//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '';

$page -> setPageStyle($page_style);

$page -> displayPageTop();

// **********************Start html for content column ****************************//

$notify->Notify();

?>
<div id="content_column_header">
Manage Events
</div>
	<div class="data_list">
		<h3>Upcoming Dates</h3>
		<ul class="item_list">
		<?php $cal->view_event('','','#','full'); ?>
		</ul>
	</div>

<?php
// **********************Closes the Content Column and begins Info Column ****************************//
$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
extract($_REQUEST);
	switch($index){
		case 'Edit' :
					?>
					<div id="content_column_header">
						<h2>Edit Event</h2>
					</div>
					<div class="form_bg">
					<?php
					if($_POST['submit']=='save event')
						$cal->edit_event('server',$_GET[id]);
					else
						$cal->edit_event('local',$_GET[id]);
					?>
					</div>
					<?php
					break;
		case 'delete' :
					$cal->Delete_Event($_GET[id]);
					break;
		default :
					if($page->auth->isAdmin()){
					?>
					<div id="content_column_header">
						<h2>Add new Event</h2>
					</div>
					<div class="form_bg">
					<?php
					if($_POST[submit]=='add event')
					$cal->add_event('server');
					else
					$cal->add_event('local');
					?>
					</div>
					<?php
					}
	}

$page -> displayPageBottom();
?>