<?php
//ini_set("display_errors" , 1 );
?>
<html >
<head>
    <meta http-equiv="X-UA-Compatible" content="chrome=1"> 
<?
$DEBUG_TIME = __LINE__ . ":::" . date("H:i:s") . ":" . microtime();

function Get_current_folder(){
                    $request = $_SERVER["REQUEST_URI"];
                    $r_arr = explode("/" , $request);
                    $count = count($r_arr);
                    $x=0;
                    while( $x < $count - 1 ){
                        $return = $r_arr[$x];
                        $x++;
                    }
                    return  $return;
                }
//ini_set( "display_errors" , 1 );
require_once('yui/sam/tabview.php');
require_once('app_code/config.inc.php');
require_once('class/class.yui.php');
require_once( 'class/class.contacts.php');
require_once('app_code/zipcode.class.php');
require_once('app_code/class.Event_Contacts.php');
require_once 'class/class.bugs.php';
$page = new basic_page();
$debug = $_GET["debug"];
$page->auth->Checklogin();
$yui = new phpyui("/yui2/");
if( PHONE_SYSTEM == "asterisk"){
    require_once('class/class.asterisk.php');
    $asterisk = new Asterisk;
}
$uid = $page->auth->Get_user_id();
$ajax = new PHPLiveX();
$bugs = new Bugs();
$ajax -> AjaxifyObjects(array("bugs")); 
$LIVE_PHONE_STATUS = $_GET["LIVE_PHONE_STATUS"];
if( (LIVE_PHONE_STATUS && $LIVE_PHONE_STATUS != "off" && PHONE_SYSTEM != "none" ) || $LIVE_PHONE_STATUS == "on" ){
    $yui->add_datatable( "asterisk" ,  "xml" , "temp.xml.php?user_id=$uid"  ,  array( 0 => array("key" => "call")) , array() , array( "RefreshEvery" => "1000")  );
}



  //  ini_set("display_errors" , 1 ); 
    if( PHONE_SYSTEM == 'asterisk'){
   $web = $asterisk->get_web_ext( $page->auth->Get_user_id() );
//      print_r( $web );
   }
	


?>



<!-- Just Playing Around With YUI, DELETE if needed -->

<?php /*?><link rel="stylesheet" type="text/css" href="/yui2/tabview/assets/skins/sam/tabview.css" />
<?php */?>
        <script type="text/javascript" >
            var chat_module_name = "TBL_USER";
            var chat_module_id = "<?php echo $_SESSION["user_id"]; ?>";
        </script>
<link rel="stylesheet" type="text/css" href="sidebar.css" />
<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.13.custom.css" />
<link rel="stylesheet" type="text/css" href="/yui2/fonts/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="/yui2/datatable/assets/skins/sam/datatable.css" >

<?php
$DEBUG_TIME .= "\n" . __LINE__ . ":::" . date("H:i:s") . ":" . microtime();
 echo $yui->get_header(); ?>
<style type="text/css" >

#asterisk {
    position: absolute;
    top: 0px;
    right: 0px;
}
#asterisk tr.yui-dt-even { background: url("images/transparent_90.png");
background-position: center;} /* white */
#asterisk tr.yui-dt-odd { background: url("images/transparent_60.png");
background-position: center; } /* light blue */
#asterisk thead { display: none; }

</style>

        <script type="text/javascript" src="./js/xml.js"></script>
        <script type="text/javascript" src="./js/chat.js"></script>
        <script type="text/javascript" src="./js/dom.js"></script>
        <script type="text/javascript" src="./js/jquery.uc.js"></script>
        <script type="text/javascript" src="./js/jquery-ui-1.8.13.custom.min.js"></script>

<script type="text/javascript" src="/yui2/yahoo-dom-event/yahoo-dom-event.js"></script>

<script type="text/javascript" src="/yui2/yahoo/yahoo.js" ></script>
<script type="text/javascript" src="/yui2/yahoo-dom-event/yahoo-dom-event.js" ></script>
<script type="text/javascript" src="/yui2/connection/connection-min.js" ></script>
<script type="text/javascript" src="/yui2/element/element-min.js"></script>
<script type="text/javascript" src="/yui2/datasource/datasource-min.js"></script>
<script type="text/javascript" src="/yui2/datatable/datatable-min.js"></script>

<script type="text/javascript" src="/yui2/event/event.js" ></script>
<script type="text/javascript" src="/yui2/dom/dom.js" ></script>
<script type="text/javascript" src="/yui2/animation/animation.js" ></script>
<script type="text/javascript" src="/yui2/container/container_core.js" ></script>
<script type="text/javascript" src="/yui2/menu/menu.js" ></script>

<script type="text/javascript" src="/yui2/element/element-min.js"></script>
<script type="text/javascript" src="/yui2/tabview/tabview-min.js"></script>

<script type="text/javascript" src="/yui2/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="/yui2/datasource/datasource-min.js"></script>
<script type="text/javascript" src="/yui2/connection/connection-min.js"></script>
<script type="text/javascript" src="/yui2/dragdrop/dragdrop-min.js"></script>
<script type="text/javascript" src="/yui2/element/element-min.js"></script>
<script type="text/javascript" src="/yui2/datatable/datatable-min.js"></script>
<script type="text/javascript" src="/yui2/dragdrop/dragdrop.js"></script>
<script type="text/javascript" src="/yui2/slider/dom.js"></script>
<script type="text/javascript" src="sidebar.js"></script>





<?php
$DEBUG_TIME .= "\n" . __LINE__ . ":::" . date("H:i:s") . ":" . microtime();
$page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("IFRAME");
$page -> setActiveButton('1');
$page -> setInnerNav('0');
//$page -> setImportCss1('main_style.css');
//$page -> setImportCss2('welcome.css');
//$page -> setImportCss3('form.css');

$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_2('form.php');
//$page -> SetDynamicCSS_1('main_style.php');
$page -> SetDynamicCSS_3('welcome_1.php');



$page -> setImportCss4('autocomplete/jssuggest.css');
$page -> setImportCss5('src/css/jscal2.css');
$page -> setImportCss6('src/css/border-radius.css');
$page -> setImportCss7('src/css/win2k/win2k.css');

$page -> setExtJavaScripts1('<script src="js/301a.js" type="text/javascript"></script>'); // might not need
$page -> setExtJavaScripts2('<script type="text/javascript" src="src/js/jscal2.js"></script>'); // might not need
$page -> setExtJavaScripts3('<script type="text/javascript" src="src/js/lang/en.js"></script>'); // might not need
//$page -> setExtJavaScripts4('<script type="text/javascript" src="autocomplete/jquery-1.2.6.min.js"></script>');
$page -> setExtJavaScripts5('<script type="text/javascript" src="autocomplete/jquery.jSuggest.1.0.js"></script>');
$page ->setExtJavaScripts6('<script type="text/javascript" src="/tholum/platform/audio-player/audio-player.js"></script>');
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
	
    ?>
<?php
$DEBUG_TIME .= "\n" . __LINE__ . ":::" . date("H:i:s") . ":" . microtime();
$chat = $_GET["CHAT"];
if( $chat == '' ){
    $chat = $_GET["chat"];
}
/*
if( CHAT_SYSTEM == "ejabberd" && $chat != "off"){ 
    switch($chat){
        case "ijab":
    
    ?>
    <script type="text/javascript" language="javascript" src="ijab_config.js"></script>
    <script type="text/javascript" language="javascript" src="ijab_i18n_en.js"></script>
    <script type="text/javascript" language="javascript" src="/ijab/ijab.nocache.js"></script>
<script type="text/javascript">
    	var loginiJab = function()
    	{
    	var userName = document.getElementById("login").value;
    	var password = document.getElementById("password").value;
    	if(userName == ""||password=="")
    	{
    		alert("username or password is empty!");
    	};
    	var handler = 
		{
			onBeforeLogin:function()
			{
				alert("On Before login");
			},
			onEndLogin:function()
			{
				alert("On end login");
			},
			onError:function(message)
			{
				iJab.login('test','test');
			},
			onLogout:function()
			{
				alert("On logout");
			},
			onResume:function()
			{
				alert("On resume");
			},
			onSuspend:function()
			{
				alert("On suspend")
			},
			onAvatarClicked:function(x,y,username,jid)
			{
				alert("onAvatarClicked posX:"+x+" posY:"+y+" username:"+username+" jid:"+jid)
			},
			onAvatarMouseOver:function(x,y,username,jid)
			{
				alert("onAvatarMouseOver posX:"+x+" posY:"+y+" username:"+username+" jid:"+jid)
			},
			 onStatusTextUpdated:function(text)
			 {
			 	alert(" onStatusTextUpdated:+"+text);
			 }
		};
    	iJab.loginWithStatus(userName,password,"STATUS_INVISIBLE");
    	}
    </script>
    <?php
        break;
    case "jappix":
        default:
            ?>
            <link rel="stylesheet" type="text/css" href="/jappix/css/mini.css" />
            <script type="text/javascript" src="/jappix/php/get.php?l=en&amp;t=js&amp;g=mini.xml&amp;f=jquery.js"></script>
            

            
            <?php
            break;
        }

            
    
    
    }
*/
 ?>
 <script type="text/javascript">
		
         var tabView = new YAHOO.widget.TabView();

         	    var handleClose = function(e, tab) {
	        YAHOO.util.Event.preventDefault(e);
	        tabView.removeTab(tab);
	    };
		var tabView2 = new YAHOO.widget.TabView();

         	    var handleClose = function(e, tab) {
	        YAHOO.util.Event.preventDefault(e);
	        tabView.removeTab(tab);
	    };
		



YAHOO.util.Event.addListener(window, "resize", function() {
    winwidth = YAHOO.util.Dom.getDocumentWidth()
	if( winwidth < 840 ){
		document.getElementById("searchdiv").style.display = "none";
	} else {
		document.getElementById("searchdiv").style.display = "inline";
	}
        document.getElementById('container').style.height = YAHOO.util.Dom.getDocumentHeight() - 85;
        document.getElementById('container2').style.height = YAHOO.util.Dom.getDocumentHeight() - 85;
 
		
    //alert(  YAHOO.util.Dom.getRegion('container') );

});

     function initiate_tabview(){
     document.getElementById('container').style.height = YAHOO.util.Dom.getDocumentHeight() - 85;
    <?php 
        foreach( config_slimcrm_autostart() as $item ){
            if( $item["access"] == "ALL" || $this->auth->inGroup( $item["access"]) ){
                echo 'add_newtab( "' . $item["url"]  . '" , "' . $item["name"] . '");';
            }
        }
    
    ?>
   
    tabView.appendTo('container');
	tabView2.appendTo('container2');
}
	function add_newtab_side( url , label ){
		container1 = document.getElementById('container');
		container1.style.left = '0';
		container1.style.width = '75%';
		container2 =  document.getElementById('container2');
		container2.style.right = '0';
		container2.style.width = '25%';
	tmp_rand =Math.floor(Math.random()*100000000000001);
        rand_id = tmp_rand + "_" + label;
        rand_onclick = 'document.getElementById("' + rand_id + '").src = "' + url + '";';
        newTab = new YAHOO.widget.Tab({
            label: label + "&nbsp;<span class='reload' ><img src='images/reload.png' class='reload' onclick='" + rand_onclick + "' style='width: 10px; height: 10px;'></span>&nbsp;<span class='close'><img src='images/close.png' class='close' style='left: 5px; width: 10px; height: 10px;' ></span>",
            content: '<iframe name="' + rand_id + '" id="' + rand_id + '" src="' + url + '" style="width: 100%; height: 100%;  border: none;" ></iframe>',
        active: true
         });

        tabView2.addTab( newTab );
        YAHOO.util.Event.on(newTab.getElementsByClassName('close')[0], 'click', handleClose, newTab);
        return rand_id;
	}
        var side_bar_status = "closed";
        function open_sideBar(){
            winwidth = YAHOO.util.Dom.getDocumentWidth();
            winheight = YAHOO.util.Dom.getDocumentHeight();
            container = document.getElementById('container');
            container.style.left = '0';
            container.style.width = winwidth - 370;
            sidebar = document.getElementById("sidebar");
            sidebar.style.display = "inline";
            sidebar.display = "inline";
            sidebar.style.right = '10px';
            sidebar.style.width = '325px';
            sidebar.style.height = winheight - 45;
            sidebar.style.top = '50';
            sidebar.style.overflow = "auto";
            sidebar.style.background = "url(images/transparent_60.png)";
            side_bar_status = "open";
            init_sidebar();
            
        }
        function close_sideBar(){
            winwidth = YAHOO.util.Dom.getDocumentWidth();
            container = document.getElementById('container');
            container.style.left = '0';
            container.style.width = "100%";
            sidebar = document.getElementById("sidebar");
            sidebar.style.display = "none";
            sidebar.style.right = '0';
            sidebar.style.width = '200';
            sidebar.style.top = '50';
            side_bar_status = "closed";
            
        }

        function toggle_sidebar(){
            if( side_bar_status == "closed"){
                open_sideBar();

            } else if( side_bar_status == "open"){
                close_sideBar();

            }
        }
    function add_newtab( url , label ){
        tmp_rand =Math.floor(Math.random()*100000000000001);
        rand_id = tmp_rand + "_" + label;
        rand_onclick = 'document.getElementById("' + rand_id + '").src = "' + url + '";';
        newTab = new YAHOO.widget.Tab({
            label: label + "&nbsp;<span class='reload' ><img src='images/reload.png' class='reload' onclick='" + rand_onclick + "' style='width: 10px; height: 10px;'></span>&nbsp;<span class='close'><img src='images/close.png' class='close' style='left: 5px; width: 10px; height: 10px;' ></span>",
            content: '<iframe name="' + rand_id + '" id="' + rand_id + '" src="' + url + '" style="width: 100%; height: 100%;  border: none;" ></iframe>',
        active: true
         });

        tabView.addTab( newTab );
        YAHOO.util.Event.on(newTab.getElementsByClassName('close')[0], 'click', handleClose, newTab);
        return rand_id;

    }
    function googlesearch(){
        form = document.getElementById("cse-search-box");
        form.action="https://www.google.com/cse";
        form.target= add_newtab( '' , "Google");
        form.submit();
        form.action="javascript:googlesearch()";


    }
</script>
    <?php
 $onload .= "initiate_tabview();";
 $chat_system = $_GET["CHAT_SYSTEM"];
 if(strpos($_SERVER['HTTP_USER_AGENT'] , "MSIE") == true && strpos($_SERVER['HTTP_USER_AGENT'] , "chromeframe") == false ){
     $chat_system = "off";
 }
 
 
 if( strtolower ( $chat_system ) != "off" ){
     $onload .= "chat_start();";
    //$onload .= "chat_update_roster();schedule_chat_tick();";
 }
 ?>
<?php
$DEBUG_TIME .= "\n" . __LINE__ . ":::" . date("H:i:s") . ":" . microtime();

$DEBUG_TIME .= "\n" . __LINE__ . ":::" . date("H:i:s") . ":" . microtime() . ":"  . __FILE__ ;
?>

    <style id="dynamic_css">
        html, body {
   height:100%;
}
#container,
#container.yui-navset,
#container.yui-content {
   top: 75px;
 

}
    </style>
 <script type="text/javascript" src="js/CFInstall.min.js"></script>
<?php
$DEBUG_TIME .= "\n" . __LINE__ . ":::" . date("H:i:s") . ":" . microtime() . ":"  . __FILE__ ;
$page->printHeadEnd();
$DEBUG_TIME .= "\n" . __LINE__ . ":::" . date("H:i:s") . ":" . microtime() . ":"  . __FILE__ ;
?>    

<body style="overflow: hidden;"  class="yui-skin-sam" id="main" onLoad="<?php echo $onload; ?>" >
    <!--[if IE]>    
      <script type="text/javascript" src="js/CFInstall.min.js"></script>
      <style>
     .chromeFrameInstallDefaultStyle {
       width: 100%; 
       border: 5px solid blue;
     }
    </style>

    <div id="prompt">SlimCRM is built on Web Standard's that are currently not built into IE<br/>This pluggin will allow for IE to work with SlimCRM</div>
     <script>
     // The conditional ensures that this code will only execute in IE,
     // Therefore we can use the IE-specific attachEvent without worry
     window.attachEvent("onload", function() {       CFInstall.check({         mode: "overlay", // the default
         node: "prompt"       });
     });   
  </script>
  <![endif]--> 
    <div id="div_bugs"   class="" style="display:none;"></div>
    <div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
   <?php $ajax->Run(); ?>
      
    <?php
$DEBUG_TIME .= "\n" . __LINE__ . ":::" . date("H:i:s") . ":" . microtime();
 echo $yui->get_body();
$DEBUG_TIME .= "\n" . __LINE__ . ":::" . date("H:i:s") . ":" . microtime();?>



        <?php $page->printHeader();
$DEBUG_TIME .= "\n" . __LINE__ . ":::" . date("H:i:s") . ":" . microtime(); ?>
        <div id="container" style="position: absolute; top: 50px; left: 0; width: 100%; height: 500px;" ></div>
		<div id="container2" style="position: absolute; top: 50; right: 0; background: #fff; bottom: 0; width: 0%;display: none" ></div>
    
    <!--
     <iframe src="welcome.php" style="width: 100%; height: 100%; position: absolute; left: 0; top: 75; border: none;" id="main_iframe"></iframe>
   -->
 <!--  <div id="searchdiv" style="position: absolute; top: 10; right: 20; width: 150px; height: 20px;">

       <div>
           <form  id="cse-search-box" action="javascript:googlesearch()">
 
    <input type="hidden" name="cx" value="partner-pub-3419146025422895:1039394803" />
    <input type="hidden" name="ie" value="UTF-8" />
    <input type="text" name="q" size="20" value="" />
    <input type="hidden" name="sa" value="Search" />
</form>
       <button style="display: none;" onclick="googlesearch()">Search </button>
  </div>

   </div>
 
   <button style="position: absolute; top: 10px; right: 400px;" onclick="toggle_sidebar()" >SideBar</button>
   <button style="position: absolute; top: 10px; right: 300px;" onclick="add_widget('SomeName' , 'My Title' , 'My Body <button>Test</button>')">AddWidget</button>
<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&amp;lang=en"></script> -->
<div id="asterisk" ></div>
<script type="text/javascript">
	YAHOO.util.Event.addListener(window, "load", yui_init_page  );
        $('#main_navigation').sortable();
        //$('.yui-nav').sortable();
</script>
<div id="sidebar" style=" height: 100%; position: absolute;display: none;border: true;" >
    <div style="color: white;align: center;v-align: center;position: relative;top: 0px;left: 0px;background: url(images/bluebar3.png) repeat-x;border:solid #a3a3a3;border-width:0 1px;height:32px;width:350px;background-position: top;text-align:center<a style="color: white;">Widgets</a></div>
   	
		<div id="ColumnsidebarDropZone">
			<div id="About" class="Rec">

				<div id="AboutHandle" class="Handle">About</div>
				<div class="Info">
					 This is a simple prototype of a dashboard that allows you to reorder containers much like iGoogle or Newsvine. <p>Select a container and drag it to another column.  As you do so, a marker appears between the place where to drop the element. <p> Drag and drop is facilitated by the <a href="http://developer.yahoo.com/yui/">YUI Library.</a></p> -- Oliver				</div>
			</div>
			<div id="Rec1" class="Rec">

				<div id="Rec1Handle" class="Handle">Container 1</div>
				<h1>1</h1>
			</div>
		</div>




<?php
$DEBUG_TIME .= "\n" . __LINE__ . ":::" . date("H:i:s") . ":" . microtime();
$test = $_GET["test"];
if( $test == 'yes' ){
    $chater = "ckucera101gmailcom.tholum@slimcrm.com";
    echo '<button onclick="chat(\'chat\' , bareXID(\'' . $chater . '\') , bareXID(\'' . $chater . '\') , hex_md5(\'' . $chater .  '\') );" style="z-index: 100;position:absolute; left: 600px; top: 65;" >Chat</button>';
}

$debug = $_GET["DEBUG"];
if( $debug == "yes"){?>
<textarea style="width:800px; height: 300px; position: absolute; top: 0; right: 0;z-index: 10000;">

        </div>
Debug Window:
<?php echo $DEBUG_TIME . print_r( $_SESSION );?>
</textarea>
<?php } ?>
</body>
</html>
