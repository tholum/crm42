<?php
date_default_timezone_set("America/Chicago");
//ini_set("display_errors",1);
require_once('class/config.inc.php');

require_once('class/class.tasks.php');

require_once('class/class.roundcube.php');

//ini_set("display_errors",1);
$page = new basic_page();

$page->auth->Checklogin();

$ajax = new PHPLiveX();

$user = new User();

$task = new Tasks();

$task -> SetUserObject($user);

$task -> SetUserID($page->auth->Get_user_id());

//$ajax -> AjaxifyObjects(array("task"));

$notify = new Notification();

$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("CALENDAR");
$page -> setActiveButton('6');
//$page -> setInnerNav('');
//$page -> setImportCss1('main_style.php');
$page -> SetDynamicCSS_1('main_style.php');
$page -> setImportCss2('form.css');
$page -> setImportCss3('src/css/jscal2.css');
$page -> setImportCss4('src/css/border-radius.css');
$page -> setImportCss5('src/css/win2k/win2k.css');


$page -> setExtJavaScripts1('<script src="js/301a.js" type="text/javascript"></script>'); // might not need
$page -> setExtJavaScripts2('<script type="text/javascript" src="src/js/jscal2.js"></script>'); // might not need
$page -> setExtJavaScripts3('<script type="text/javascript" src="src/js/lang/en.js"></script>'); // might not need
$page -> setExtJavaScripts4(''); // might not need


//*********************Page Style *******************************//
// used to set page styles.  This should be used sparingly.  External css should be used instead.
$page_style = '
';

$page -> setPageStyle($page_style);

//$page -> displayPageTop();
/*$page->printDocType();
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
	//$page->printHeader();
*/


// **********************Start html for content column ****************************//
$notify->Notify();
$zimbra = new zimbra;
$url = $zimbra-> zimbra_auth( $page->auth->Get_user_id() , "calendar" );
//$url = "/zimbra/?loginOp=login&username=" . $emailauth["user"] . "@" . MAIL_DEFAULT_DOMAIN . "&password=" . $emailauth["pass"] . "&client=preferred&skin=fields";
?>
<script type="text/javascript">
window.location='<?php echo $url; ?>';
</script>
<!--


<iframe id=webmail src="<? echo $url; ?>" width="100%" height="75%" frameBorder="0" ></iframe>

<script>
var myWidth = 0, myHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }
  var webmailH = myHeight - 135;
 document.getElementById('webmail').height= webmailH +"px" ;
</script>
-->



<?php
// **********************Closes the Content Column and begins Info Column ****************************//
//$page-> printContentColumnEnd();
// **********************Start code for Info Column ****************************//
?>
<?php
//$page -> displayPageBottom();
?>
