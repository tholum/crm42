<?php
//ini_set( "display_errors" , 1 );
require_once('app_code/config.inc.php');
require_once('class/class.yui.php');
require_once( 'class/class.contacts.php');
require_once('app_code/zipcode.class.php');
require_once('app_code/class.Event_Contacts.php');
require_once('class/class.yui.php');
$page = new basic_page();

$page->auth->Checklogin();
$yui = new phpyui("/yui2/");
if( PHONE_SYSTEM == "asterisk"){
    require_once('class/class.asterisk.php');
    $asterisk = new Asterisk;
}
$uid = $page->auth->Get_user_id();
$yui->add_datatable( "asterisk" ,  "xml" , "temp.xml.php?user_id=$uid"  ,  array( 0 => array("key" => "call")) , array() , array( "RefreshEvery" => "1000")  );




  //  ini_set("display_errors" , 1 ); 
    if( PHONE_SYSTEM == 'asterisk'){
   $web = $asterisk->get_web_ext( $page->auth->Get_user_id() );
//      print_r( $web );
   }
	


?>
<html >
<head>
<link rel="stylesheet" type="text/css" href="sidebar.css" />
<!-- Just Playing Around With YUI, DELETE if needed -->
<link rel="stylesheet" type="text/css" href="/yui2/fonts/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="/yui2/tabview/assets/skins/sam/tabview.css" />
<link rel="stylesheet" type="text/css" href="/yui2/datatable/assets/skins/sam/datatable.css" >

<?php echo $yui->get_header(); ?>
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
    $page -> setPageKeywords('');
$page -> setPageDescription('');
$page -> setPageTitle("IFRAME BACKUP");
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
$page -> setExtJavaScripts4('<script type="text/javascript" src="autocomplete/jquery-1.2.6.min.js"></script>');
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
	$page->printHeadEnd();
    ?>
    <script type="text/javascript" language="javascript" src="ijab_config.js"></script>
    <script type="text/javascript" language="javascript" src="ijab_i18n_en.js"></script>
    <script type="text/javascript" language="javascript" src="ijab/ijab.nocache.js"></script>
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
        document.getElementById('container').style.height = YAHOO.util.Dom.getDocumentHeight() - 92;
        //document.getElementById('container2').style.height = YAHOO.util.Dom.getDocumentHeight() - 75;
       // document.getElementById("container").style.height =  YAHOO.util.Dom.getDocumentHeight() - ;
 
		
    //alert(  YAHOO.util.Dom.getRegion('container') );

});

     function initiate_tabview(){
     document.getElementById('container2').style.height = "400px";
    
    add_newtab( "welcome.php" , "Welcome");
   
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
    <script type="text/javascript" >
    function onload_helper(){
        var tmpheight =  YAHOO.util.Dom.getDocumentHeight() - 92;
        document.getElementById("container").style.height =  tmpheight + "px";
        
    }
    </script>
    <?php $onload .= "initiate_tabview();onload_helper();"; ?>
<?php
if( CHAT_SYSTEM == "ejabberd"){
    require_once( "class/class.ejabberd.php");
    $chat = new ejabberd;
    $chat->get_login($page->auth->Get_user_name());
    $onload .= "iJab.loginWithStatus( '" . $chat->username . "' , '" . $chat->password . "');";
    $arr = $chat->get_chats_by_contact("11079");
}
?>
    <style>
        html, body {
   height:100%;
}


.yui-content {
width: 100%;
height: 100%;
}
    </style>
	

</head>    

<body style="overflow: auto;"  class="yui-skin-sam" id="main" onLoad="<?php echo $onload; ?>" >

    <?php echo $yui->get_body();?>

        <?php $page->printHeader(); ?>
        <div id="container" style="position: absolute; top: 50px; height: 500px; width: 100%; left: 0px;" ></div>
		<div id="container2" style="position: absolute; top: 50; right: 0; background: #fff; bottom: 0; width: 0%;display: none" ></div>

   <button style="position: absolute; top: 10px; right: 400px;" onClick="toggle_sidebar()" >SideBar</button>
   <button style="position: absolute; top: 10px; right: 300px;" onClick="add_widget('SomeName' , 'My Title' , 'My Body <button>Test</button>')">AddWidget</button>
<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&amp;lang=en"></script>
<div id="asterisk" ></div>
<script type="text/javascript">
	YAHOO.util.Event.addListener(window, "load", yui_init_page  );
</script>
<div id="sidebar" style=" height: 100%; position: absolute;display: none;border: true;" >
    <div style="color: white;align: center;v-align: center;position: relative;top: 0px;left: 0px;background: url(images/bluebar3.png) repeat-x;border:solid #a3a3a3;border-width:0 1px;height:32px;width:350px;background-position: top;text-align:center<a style="color: white;text-align: center;font-weight:bold;width 100%;">Widgets</a></div>
   	
		<div id="ColumnsidebarDropZone">
                    
                    <?php 
                    $widgetCT = 1;    
                    while( $widgetCT < 30){
                    
                    ?>
			<div id="Widget<?php echo $widgetCT; ?>" class="Rec" <?php if( $widgetCT > 3){ echo "style='display:none'";} ?> >

				<div id="WidgetHandle<?php echo $widgetCT; ?>" class="Handle"></div>
				<div id="WidgetBody<?php echo $widgetCT; ?>"class="Info">
					 
				</div>
			</div>
                    <?php 
                    $widgetCT++;
                    } ?>
		</div>

</div>
<!--<button style='position: absolute; top: 10px; right: 100px; display: true;' onclick='alert( document.getElementById("ProjectDropBox").innerHTML )'>TEST</button>-->
<div id='ProjectDropBox' style='display: none' >
<table id='ProjectDropBoxTable' >
<thead>
<tr><td>Email ID</td></tr>
</thead>
<tbody id="ProjectDropBoxBody">
</tbody>
</div>
</body>
</html>
