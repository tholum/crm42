<?php
ini_set("display_errors" , 1 );
$project_id = $_GET["project_id"];
require_once("class/class.projects.php");
require_once("class/config.inc.php");
require_once("class/global.config.php");
require_once("class/class.displaynew.php");
require_once("class/class.displaynew.php");
require_once("class/class.note.php");



$disp = new displaynew;
$proj = new projects;
$yui = new phpyui( '/yui2/' );
$note = new Note;
$columbsPR = array();
$columbsPR[] = array( "key" => "title" , "label" => "Position" , "sortable" => "true" , "resizeable" => "true" );
$columbsPR[] = array( "key" => "first_name" , "label" => "First");
$columbsPR[] = array( "key" => "last_name" , "label" => "Last " );
$columbsPR[] = array( "key" => "email_id" , "label" => "Email" , "hidden" => "true" );
$columbsPR[] = array( "key" => "phone" , "hidden" => "true" );
$columbsPR[] = array("key" => "mobile" , "hidden" => "true" );
$columbsPR[] = array( "key" => "user_id" , "hidden" => "true" );

$propertys = array();
$propertys["width"] = "300px";
$propertys["visible"] = "false";
$propertys["fixedcenter"] = "true";
$functions = array();
$sub = array();
$sub[] = array( "event" => "cellClickEvent" , "code" => "var target = oArgs.target; 
var record = this.getRecord(target); 
alert('User ID' + record.getData('user_id') );");

$more = array();
$more["tablePropertys"] = array();
$more["tablePropertys"]["initialRequest"] = "q=";
$more["tablePropertys"]["dynamicData"] = "true";
//$more["tablePropertys"]["paginator"] = 'new YAHOO.widget.Paginator({ rowsPerPage: 2 })';

$sub2 = array();
$sub2[] = array( "event" => "cellClickEvent" , "code" => "var target = oArgs.target; 
var record = this.getRecord(target); 
document.getElementById('pf_user_id').value = record.getData('user_id');
document.getElementById('pf_action').value = 'add';
yui_ajax_post_call_user_edit( this.parrentnode );
yui_datatable_load_projectUserEdit();
yui_datatable_load_projectUsers();
");


$yui->add_datatable( "projectUserEdit" , "xml" , "project_users_xml.php?project_id=" . $project_id , $columbsPR , $sub2 );
$yui->add_datatable( "projectUsers" ,  "xml" , "project_users_xml.php?project_id=" . $project_id  , $columbsPR , $sub );
$yui->add_datatable( "addUserAllUsers" , "xml" , "user_search_xml.php?" , $columbsPR , $sub2 , $more  );


$yui->add_yui_script( "paginator/paginator-min.js");
$yui->add_yui_css( "paginator/assets/skins/sam/paginator.css");
$yui->add_yui_css( "build/fonts/fonts-min.css" );
$yui->add_yui_css( "build/paginator/assets/skins/sam/paginator.css" );
$yui->add_yui_css( "build/datatable/assets/skins/sam/datatable.css" );
$yui->add_yui_script( "yahoo-dom-event/yahoo-dom-event.js" );

$yui->add_yui_script( "connection/connection-min.js" );
$yui->add_yui_script( "json/json-min.js" );
$yui->add_yui_script( "element/element-min.js" );
$yui->add_yui_script( "paginator/paginator-min.js" );
$yui->add_yui_script( "datasource/datasource-min.js" );
$yui->add_yui_script( "datatable/datatable-min.js" );


$linksCols = array();
$linksCols[] = array( "key" => "name" , "label" => "Name" , "sortable" => "true" , "width" => "400px" , "resizeable" => "true" );
$linksCols[] = array( "key" => "phone" , "hidden" => "true" );
$linksCols[] = array( "key" => "email" , "hidden" => "true" );
$linksCols[] = array( "key" => "onclick" , "hidden" => "true" );
$linksCols[] = array( "key" => "id" , "hidden" => "true" );
$linkssub = array();
$linkssub[] = array( "event" => "cellClickEvent" , "code" => "var target = oArgs.target; 
var record = this.getRecord(target); 
window.location=record.getData('onclick') + record.getData('id');");
$yui->add_datatable( "projectLinks" , "xml" , "project_links_xml.php?project_id=" . $project_id , $linksCols , $linkssub );

$panelProp = array();
$panelProp["width"] = "800px";
$panelProp["visible"] = "false";
$panelProp["fixedcenter"] = "true";
$yui->add_panel( "addUser" , $panelProp , array() );
$yui->add_panel( "addLink" , $panelProp , array() );
$pf = array();
$pf[] = array( "post" => "user_id" , "id" => "pf_user_id" );
$pf[] = array( "post" => "action" , "id" => "pf_action" );
$pf[] = array( "post" => "project_id" , "id" => "pf_project_id" );

$yui->add_ajax_post( "user_edit" , "project_user_ajax.php" , $pf  );

$page = new basic_page();
$page->auth->Checklogin();
$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("PROJECT INFO");
$page -> setActiveButton('8');

$page -> setImportCss9('contact_profile.css');
$page -> setImportCss1('main_style.css');
$page -> setImportCss2('form.css');
$page -> setImportCss3('css/all.css');
$page -> setImportCss4('src/css/win2k/win2k.css');
$page -> setImportCss5('autocomplete/jssuggest.css');
$page -> setImportCss6('src/css/jscal2.css');
$page -> setImportCss7('src/css/border-radius.css');
$page -> setImportCss8('tablesort/themes/blue/style.css');

$page -> setExtJavaScripts1('<script src="js/301a.js" type="text/javascript"></script>'); // might not need //
$page -> setExtJavaScripts2('<script type="text/javascript" src="yahoo-dom-event.js"></script>'); // might not need // 
$page -> setExtJavaScripts3('<link rel="stylesheet" type="text/css" href="yui/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="yui/sam/datatable.css" />
<link rel="stylesheet" type="text/css" href="yui/sam/container.css" />

<script>
function getUserQuery(){
	var auq = document.getElementById(\'AllUserQuery\');
	if( auq == null ){
		return \'user_search_xml.php?q=\';
	} else { 	
		return "user_search_xml.php?q=" + document.getElementById(\'AllUserQuery\').value;
	}
}
</script>
<script>

</script>
' . $yui->get_header() . '
');




$page_style = '
';
$page->setBodyScript('class="yui-skin-sam"');
$page -> setPageStyle($page_style);
$page -> displayPageTop();

echo $yui->get_body();
?>
<!-- Hidden Fields for ajax type posting, I know there is a better way, I dont have time to research it now thought-->
<input type="hidden" name="pf_user_id" id="pf_user_id" />
<input type="hidden" name="pf_action" id="pf_action" />
<input type="hidden" name="pf_project_id" id="pf_project_id" value="<?php echo $project_id; ?>" />


<div id="div_event"   class="" style="display:none;"></div>
<div id="div_event"   class="" style="display:none;"></div>
<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
<div id="content_column_header" >Projects</div>
<div class="form_main">
	<div>My Projects</div>
	<div id="basic"></div>
</div>
<div>Project Notes</div>
	<?php 
	if($_POST["submit"]=='add message') 
	{	$note->Create_Note('server',$project_id,"PROJECTS",$page->auth->Get_user_id()); 
		exit();
	}
	else
		$note->Create_Note('local',$project_id, "PROJECTS",$page->auth->Get_user_id()); 
	?>
<?php
echo $note->Get_Note( $project_id , "PROJECTS");
$page-> printContentColumnEnd(); ?>
<div>Project Users <a href="#" onclick="<?php echo $yui->show_panel('addUser'); ?>" >Edit Users</a></div>
<div id="projectUsers"></div>
<div style="height: 15px">&nbsp;</div>
<div>Project Links<a href="#" onclick="<?php echo $yui->show_panel('addLink'); ?>" >Edit Links</a></div>
<div id="projectLinks" ></div>



<div id="addUser"> 
		<div class="hd">Edit User's</div>
		<div class="bd">
		<table summary="" >
	<tr><td>
		<input type="text" name="AllUserQuery" id="AllUserQuery" onchange='<?php echo $yui->query_datatable( 'addUserAllUsers' , '"q=" + document.getElementById("AllUserQuery").value' );?>' ><button onclick='<?php echo $yui->query_datatable( 'addUserAllUsers' , '"q=" + document.getElementById("AllUserQuery").value' );?>'>Submit</button><br/>
		<div id="addUserAllUsers"></div>
		</td><td><div id="projectUserEdit"></div></td></tr>
		</table>
		</div>
		<div class="ft"><button style="position:relative; left: 675px; width: 50px;" onclick="javascript:alert('NOT DONE YET')" >Submit</button></div>

</div>
<div id="addLink"> 
		<div class="hd">Links User's</div>
		<div class="bd">
		This is soon to be the edit Links panel, It is not yet implemented
		</div>
		<div class="ft"><button style="position:relative; left: 675px; width: 50px;" onclick="javascript:alert('NOT DONE YET')" >Submit</button></div>

</div>

