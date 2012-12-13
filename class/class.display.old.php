<?php
if( PHONE_SYSTEM == "asterisk"){
 require_once( "class/class.asterisk.php");
}
 /***********************************************************************************

		Class Discription : basic_page 
		
		Class Memeber Functions : setPageKeywords($keywords)
								  setAccessRules($rule)
								  setAccessRulesType($type)
								  setPageDescription($description)
								  setPageTitle($title)
								  setInnerNav($type)
								  setActiveButton($ab)
								  setImportCss1($css_1)
								  setPageStyle($style)
								  setExtJavaScripts1($ext_custom_scripts)
								  setExtJavaScripts2($ext_custom_scripts)
								  setCustomJavaScripts($custom_scripts)
								  setBodyScript($script)
								  displayPageTop()
								  displayPageBottom()
								  printDoctype()
								  printHTMLStart()
								  printHeadStart()
								  printCharEncod()
								  printTitle()
								  printMetaAuthor()
								  printMetaKeywords()
								  printMetaDesc()
								  printFOUC()
								  printExtJavaScripts()
								  printCustomJavaScripts()
								  printMainStyle()
								  printPageStyle()
								  printHeadEnd()
								  printBodyStart()
								  printHeader()
								  printContentAreaStart()
								  printContentColumnStart()
								  printContentColumnEnd()
								  printInfoColumnEnd()
								  printFooter()
								  printContentAreaEnd()
								  printGoogAna()
								  printBodyEnd()
								  printHTMLEnd()
								  CheckAuthorization()
								  gotoPage($page)
		
		
		Describe Function of Each Memeber function :
		
								  1. function setPageKeywords($keywords)
								  
								  2. function setAccessRules($rule)
								  
								  3. function setAccessRulesType($type)
								  
								  4. function setPageDescription($description)
								  
								  5. function setPageTitle($title)
								  
								  6. function setInnerNav($type)
								  
								  7. function setActiveButton($ab)
								  
								  8. function setImportCss1($css_1)
								  
								  9. function setPageStyle($style)
								  
								  10. function setExtJavaScripts1($ext_custom_scripts)
								  
								  11. function setExtJavaScripts2($ext_custom_scripts)
								  
								  12. function setCustomJavaScripts($custom_scripts)
								  
								  13. function setBodyScript($script)
								  
								  14. function displayPageTop()
								  
								  15. function displayPageBottom()
								  
								  16. function printDoctype()
								  
								  17. function printHTMLStart()
								  
								  18. function printHeadStart()
								  
								  19. function printCharEncod()
								  
								  20. function printTitle()
								  
								  21. function printMetaAuthor()
								  
								  22. function printMetaKeywords()
								  
								  23. function printMetaDesc()
								  
								  24. function printFOUC()
								  
								  25. function printExtJavaScripts()
								  
								  26. function printCustomJavaScripts()
								  
								  27. function printMainStyle()
								  
								  28. function printPageStyle()
								  
								  29. function printHeadEnd()
								  
								  30. function printBodyStart()
								  
								  31. function printHeader()
								  
								  32. function printContentAreaStart()
								  
								  33. function printContentColumnStart()
								  
								  34. function printContentColumnEnd()
								  
								  35. function printInfoColumnEnd()
								  
								  36. function printFooter()
								  
								  37. function printContentAreaEnd()
								  
								  38. function printGoogAna()
								  
								  39. function printBodyEnd()
								  
								  40. function printHTMLEnd()
								  
								  41. function CheckAuthorization()
								  
								  42. function gotoPage($page)

					



************************************************************************************/
class basic_page
{
  var $page_keywords;
  var $page_description;
  var $page_title;
  var $active_button;  // The active button for navagation (navagation section)
  var $inner_nav; // The active page for navagation
  var $css1; // normally main_style.css which is the style sheet that define the standard elements of all pages.
  var $css2;
  var $css3;
  var $css4;
  var $css5;
  var $css6;
  var $css7;
  var $css8;
  var $css9;
  var $css10;
  var $css11;
  var $css12;
  var $css13;
  
  var $dynamic_css1;
  var $dynamic_css2;
  var $dynamic_css3;
  var $dynamic_css4;
  var $dynamic_css5;
  var $dynamic_css6;
  var $dynamic_css7;
  var $dynamic_css8;
  var $dynamic_css10;
  var $dynamic_css11;
  var $dynamic_css12;
  var $dynamic_css13;
    
  var $page_style; // this should be used sparingly; Use external style sheets.
  var $ext_java_scripts1; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts2; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts3; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts4; /* should be in the form of "<script language="javascript" SRC="the_file_url.js"></script>" */
  var $ext_java_scripts5;
  var $ext_java_scripts6;
  var $ext_java_scripts7;
  var $ext_java_scripts8;
  var $ext_java_scripts9;
  var $ext_java_scripts10;
  var $ext_java_scripts11;
  var $ext_java_scripts12;
  var $ext_java_scripts13;
  var $ext_java_scripts14;  
  var $ext_java_scripts15;
  var $ext_java_scripts16;
  var $ext_java_scripts17; 
  
  
  var $custom_java_scripts;  /* the <script> tags are already printed.  This if for javascript functions */
  var $body_script; // Add an onLoad script into the <body> tag.  Should be in the form of 'onLoad="javascriptFunction()"'
  var $auth;	//Authentication variable
  var $access_rules=array();			// It stores the user group & their types in which user needs to be in to access this page (may be all or them or any of them)
  var $access_rules_type=array();		// any or all , it determines how rules condition should be applied
  var $asterisk;
   function __construct()
   {
       if( PHONE_SYSTEM == "asterisk"){
            $this->asterisk = new Asterisk;
        }
	 $this->auth=new Authentication();
   }
  // sets the meta-keywords for the new page
  function setPageKeywords($keywords)
  {
	$this->page_keywords = $keywords;
  }
  
  
  function setAccessRules($rule)
  {
  	$this->access_rules = $rule;
  }
  
  function setAccessRulesType($type)
  {
  	$this->access_rules_type = $type;
  }

  // sets the meta-description for the new page
  function setPageDescription($description)
  {
	$this->page_description = $description;
  }

  // sets the page title for the new page
  function setPageTitle($title)
  {
    $this->page_title = $title;
  }

  // sets the inner nav of selected page
  function setInnerNav($type)
  {
    $this->inner_nav = $type;
  }

  // sets active button for navagation
  function setActiveButton($ab)
  {
	$this->active_button = $ab;
  }

  // sets imported css.  #1 is the main_style.css
  function setImportCss1($css_1)
  {
    $this->css1 = $css_1;
  }
  
  // sets next css import file
  function setImportCss2($css_2)
  {
    $this->css2 = $css_2;
  }

  // sets next css import file
  function setImportCss3($css_3)
  {
    $this->css3 = $css_3;
  }

  // sets next css import file
  function setImportCss4($css_4)
  {
    $this->css4 = $css_4;
  }

  // sets next css import file
  function setImportCss5($css_5)
  {
    $this->css5 = $css_5;
  }
  
  // sets next css import file
  function setImportCss6($css_6)
  {
    $this->css6 = $css_6;
  }
  
    // sets next css import file
  function setImportCss7($css_7)
  {
    $this->css7 = $css_7;
  }
  
    // sets next css import file
  function setImportCss8($css_8)
  {
    $this->css8 = $css_8;
  }
  
    // sets next css import file
  function setImportCss9($css_9)
  {
    $this->css9 = $css_9;
  }
  
    // sets next css import file
  function setImportCss10($css_10)
  {
    $this->css6 = $css_10;
  }
  
    // sets next css import file
  function setImportCss11($css_8)
  {
    $this->css11 = $css_8;
  }
  
    // sets next css import file
  function setImportCss12($css_9)
  {
    $this->css12= $css_9;
  }
  
    // sets next css import file
  function setImportCss13($css_10)
  {
    $this->css13 = $css_10;
  }	
    
	
	
	
	
	
	// sets next css import file
  function SetDynamicCSS_1($css_10)
  {
    $this->dynamic_css1 = $css_10;
  }	
    // sets next css import file
  function SetDynamicCSS_2($css_10)
  {
    $this->dynamic_css2 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_3($css_10)
  {
    $this->dynamic_css3 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_4($css_10)
  {
    $this->dynamic_css4 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_5($css_10)
  {
    $this->dynamic_css5 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_6($css_10)
  {
    $this->dynamic_css6 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_7($css_10)
  {
    $this->dynamic_css7 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_8($css_10)
  {
    $this->dynamic_css8 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_9($css_10)
  {
    $this->dynamic_css9 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_10($css_10)
  {
    $this->dynamic_css10 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_11($css_10)
  {
    $this->dynamic_css11 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_12($css_10)
  {
    $this->dynamic_css12 = $css_10;
  }	
  
    // sets next css import file
  function SetDynamicCSS_13($css_10)
  {
    $this->dynamic_css13 = $css_10;
  }	  
  
  
  
  
    // sets any additional css that the page requires
  function setPageStyle($style)
  {
	$this->page_style = $style;
  }

  // sets external java scripts that the page requires
  function setExtJavaScripts1($ext_custom_scripts)
  {
	$this->ext_java_scripts1 = $ext_custom_scripts;
  }

  function setExtJavaScripts2($ext_custom_scripts)
  {
	$this->ext_java_scripts2 = $ext_custom_scripts;
  }

  function setExtJavaScripts3($ext_custom_scripts)
  {
	$this->ext_java_scripts3 = $ext_custom_scripts;
  }

  function setExtJavaScripts4($ext_custom_scripts)
  {
	$this->ext_java_scripts4 = $ext_custom_scripts;
  }

  function setExtJavaScripts5($ext_custom_scripts)
  {
	$this->ext_java_scripts5 = $ext_custom_scripts;
  }

  function setExtJavaScripts6($ext_custom_scripts)
  {
	$this->ext_java_scripts6 = $ext_custom_scripts;
  }
  
  function setExtJavaScripts7($ext_custom_scripts)
  {
	$this->ext_java_scripts7 = $ext_custom_scripts;
  }
  
    function setExtJavaScripts8($ext_custom_scripts)
  {
	$this->ext_java_scripts8 = $ext_custom_scripts;
  }
  
    function setExtJavaScripts9($ext_custom_scripts)
  {
	$this->ext_java_scripts9 = $ext_custom_scripts;
  }
  
    function setExtJavaScripts10($ext_custom_scripts)
  {
	$this->ext_java_scripts10 = $ext_custom_scripts;
  }
  
    function setExtJavaScripts11($ext_custom_scripts)
  {
	$this->ext_java_scripts11 = $ext_custom_scripts;
  }
  
    function setExtJavaScripts12($ext_custom_scripts)
  {
	$this->ext_java_scripts12 = $ext_custom_scripts;
  }
  
    function setExtJavaScripts13($ext_custom_scripts)
  {
	$this->ext_java_scripts13 = $ext_custom_scripts;
  }  
  
      function setExtJavaScripts14($ext_custom_scripts)
  {
	$this->ext_java_scripts14 = $ext_custom_scripts;
  }
  
      function setExtJavaScripts15($ext_custom_scripts)
  {
	$this->ext_java_scripts15= $ext_custom_scripts;
  }
  
      function setExtJavaScripts16($ext_custom_scripts)
  {
	$this->ext_java_scripts16 = $ext_custom_scripts;
  }
  
      function setExtJavaScripts17($ext_custom_scripts)
  {
	$this->ext_java_scripts17 = $ext_custom_scripts;
  }
  
    // sets internal java scripts functions that the page requires
  function setCustomJavaScripts($custom_scripts)
  {
	$this->custom_java_scripts = $custom_scripts;
  }
	
  // sets any onLoad javascripts in <body> tag
  function setBodyScript($script)
  {
	$this->body_script = ' '.$script;
  }

  function displayPageTop($full='' , $header = '')
  {
    $this->printDocType();
	$this->printHTMLStart();
	$this->printHeadStart();
	$this->printCharEncod();
	$this->printTitle();
	$this->printMetaAuthor();
	$this->printMetaKeywords();
    $this->printMetaDesc();
	$this->printFOUC();
	$this->printMainStyle();
	$this->printPageStyle();
	$this->printExtJavaScripts();
	$this->printCustomJavaScripts();
	$this->printHeadEnd();
	$this->printBodyStart();
        if( $header == "yes"){
            $this->printHeader();
        }
        $this->printContentAreaStart();
	$this->printContentColumnStart($full);
  }

  function displayPageBottom()
  {
	$this->printInfoColumnEnd();
	$this->printFooter();
	$this->printContentAreaEnd();
	$this->printGoogAna();
	$this->printBodyEnd();
	$this->printHTMLEnd();
  }

  // display functions

  function printDoctype()
  {
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
  }
  
  function printHTMLStart()
  {
    echo '
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">';
  }
  
  function printHeadStart()
  {
    echo '
<head>';
  }
  
  function printCharEncod()
  {
    echo '
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
  }
  
  function printTitle()
  {
    echo '
<title>'.$this->page_title.'</title>';
  }

  function printMetaAuthor()
  {
    echo '
<meta name="author" content="TimIvey.com" />';
  }
  
  function printMetaKeywords()
  {
    echo '
<meta name="keywords" content="'.$this->page_keywords.'" />';
  }

  function printMetaDesc()
  {
    echo '
<meta name="description" content="'.$this->page_description.'" />';
  }
    
  function printFOUC() // stops unstyled html from appear.  May be obsolete now.
  {
    echo '
';
  }
  
  function printExtJavaScripts()
  {
     if ( !empty($this->ext_java_scripts1) )
	 {
     	echo $this->ext_java_scripts1;
     }
     if ( !empty($this->ext_java_scripts2) )
	 {
     	echo $this->ext_java_scripts2;
     }
     if ( !empty($this->ext_java_scripts3) )
	 {
     	echo $this->ext_java_scripts3;
     }
     if ( !empty($this->ext_java_scripts4) )
	 {
     	echo $this->ext_java_scripts4;
     }
     if ( !empty($this->ext_java_scripts5) )
	 {
     	echo $this->ext_java_scripts5;
     }
	 if ( !empty($this->ext_java_scripts6) )
	 {
     	echo $this->ext_java_scripts6;
     }
	 if ( !empty($this->ext_java_scripts7) )
	 {
     	echo $this->ext_java_scripts7;
     }
	 if ( !empty($this->ext_java_scripts8) )
	 {
     	echo $this->ext_java_scripts8;
     }
	 if ( !empty($this->ext_java_scripts9) )
	 {
     	echo $this->ext_java_scripts9;
     }
	 if ( !empty($this->ext_java_scripts10) )
	 {
     	echo $this->ext_java_scripts10;
     }
	 if ( !empty($this->ext_java_scripts11) )
	 {
     	echo $this->ext_java_scripts11;
     }
	 if ( !empty($this->ext_java_scripts12) )
	 {
     	echo $this->ext_java_scripts12;
     }	 
	 if ( !empty($this->ext_java_scripts13) )
	 {
     	echo $this->ext_java_scripts13;
     }
	 if ( !empty($this->ext_java_scripts14) )
	 {
     	echo $this->ext_java_scripts14;
     }	 
	 if ( !empty($this->ext_java_scripts15) )
	 {
     	echo $this->ext_java_scripts15;
     }
	 if ( !empty($this->ext_java_scripts16) )
	 {
     	echo $this->ext_java_scripts16;
     }
	 if ( !empty($this->ext_java_scripts17) )
	 {
     	echo $this->ext_java_scripts17;
     }
}
 
  function printCustomJavaScripts()
  {
     if ( !empty($this->custom_java_scripts) )
	 {
     echo '
	<script language="javascript" type="text/javascript">'.
	$this->custom_java_scripts.'
	
	
	
	</script>';
	 }
	?>
	<script type="text/javascript">
function CheckCall( ext )
{
var cid;
try //Internet Explorer
  {
  xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
  }
catch(e)
  {
  try //Firefox, Mozilla, Opera, etc.
    {
    xmlDoc=document.implementation.createDocument("","",null);
    }
  catch(e)
    {
    alert(e.message);
    return;
    }
  }
xmlDoc.async=false;
xmlDoc.load("/asterisk/phone/checkcall.beta.php?ext=" + ext + "&s=YES&account=<?php echo ASTERISK_ACCOUNT_CODE;?>" );
cid=xmlDoc.getElementsByTagName("root")[0].childNodes[0].nodeValue;
return cid;
}

 function sleep(delay)
 {
     var start = new Date().getTime();
     while (new Date().getTime() < start + delay);
 }

function loopcheck_ext( ext ){
var cid;

	while( 0 < 1 ){
		cid = CheckCall( ext );
		if( cid != 'NONE' ){
			document.getElementById('phone_call').src='tab.php?phone='+cid;
		//	document.getElementById('phone_call').contentWindow.location.reload(true);
			document.getElementById('phone_call').style.display='';
                        setTimeout("loopcheck_ext(" + ext + ")",2500);
			break;
			
		} else {
                   document.getElementById('phone_call').style.display='none';
                        setTimeout("loopcheck_ext(" + ext + ")",2500);
			break;
                }
				
	}
}

	</script>
	<?
  }
  
  function printMainStyle()
  {
	// the first css are for the drop down navigation on the home page
	echo '
<style type="text/css" media="all">';
	echo '@import url(css/ui-lightness/jquery-ui-1.8.6.custom.css);';	
    if ( !empty($this->css1) ) echo '@import url('.$this->css1.');';
    if ( !empty($this->css2) ) echo '@import url('.$this->css2.');';
    if ( !empty($this->css3) ) echo '@import url('.$this->css3.');';
    if ( !empty($this->css4) ) echo '@import url('.$this->css4.');';
    if ( !empty($this->css5) ) echo '@import url('.$this->css5.');';
	if ( !empty($this->css6) ) echo '@import url('.$this->css6.');';
	if ( !empty($this->css7) ) echo '@import url('.$this->css7.');';
	if ( !empty($this->css8) ) echo '@import url('.$this->css8.');';
	if ( !empty($this->css9) ) echo '@import url('.$this->css9.');';
	if ( !empty($this->css10) ) echo '@import url('.$this->css10.');';
	if ( !empty($this->css11) ) echo '@import url('.$this->css11.');';
	if ( !empty($this->css12) ) echo '@import url('.$this->css12.');';
	if ( !empty($this->css13) ) echo '@import url('.$this->css13.');';    
	echo '

</style>';
	if ( !empty($this->dynamic_css1) ) include($this->dynamic_css1);
	if ( !empty($this->dynamic_css2) ) include($this->dynamic_css2);
	if ( !empty($this->dynamic_css3) ) include($this->dynamic_css3);
	if ( !empty($this->dynamic_css4) ) include($this->dynamic_css4);
	if ( !empty($this->dynamic_css5) ) include($this->dynamic_css5);
	if ( !empty($this->dynamic_css6) ) include($this->dynamic_css7);
	if ( !empty($this->dynamic_css7) ) include($this->dynamic_css7);
	if ( !empty($this->dynamic_css8) ) include($this->dynamic_css8);
	if ( !empty($this->dynamic_css9) ) include($this->dynamic_css9);
	if ( !empty($this->dynamic_css10) ) include($this->dynamic_css10);
	if ( !empty($this->dynamic_css11) ) include($this->dynamic_css11);
	if ( !empty($this->dynamic_css12) ) include($this->dynamic_css12);
	if ( !empty($this->dynamic_css13) ) include($this->dynamic_css13);

  }
  
  function printPageStyle()
  {
    if ( !empty($this->page_style) )
	{
    	echo '
<style type="text/css">'.
$this->page_style.'
</style>';
     }
  }

  function printHeadEnd()
  {
    echo '
</head>';
  }

  function printBodyStart()
  {
    echo '
<body'.$this -> body_script.'>'; 
echo '<!--[if lte IE 6]><script src="ie6/warning.js"></script><script>window.onload=function(){e("ie6/")}</script><![endif]-->';
  }
  
  
  
  function menuLineString( $item ){
                 if ($this -> active_button == '1'){ 
                 $returnHtml .= '<li class="active">'; 
             } else {
                 $returnHtml .= '<li>';
             }
                if(array_key_exists("url", $item)){
                    $returnHtml .= '<a id=\'' . $item["id"] . '\' onclick="add_newtab(\'' . $item["url"] . '\', \'' . $item["name"] . '\');"><img style="width: 35px; height:35px" src="' . $item["image"] . '" /></a></li>';    
                } elseif( array_key_exists("script", $item) ){
                    $returnHtml .= '<a id=\'' . $item["id"] . '\' onclick="' . $item["script"] . '"><img style="width: 35px; height:35px" src="' . $item["image"] . '" /></a></li>';
                }
                  return $returnHtml;
      
  }
  
  function menuFromArray( $array ){
      $returnHtml = '<ul id="main_navigation">';
      foreach( $array as $item ){
          if( $item["access"] == "ALL" || $this->auth->inGroup( $item["access"]) ){
              if ($this -> active_button == '1'){ 
                 $returnHtml .= '<li class="active">'; 
             } else {
                 $returnHtml .= '<li>';
             }
                if(array_key_exists("url", $item)){
                    $returnHtml .= '<a id=\'' . $item["id"] . '\' onclick="add_newtab(\'' . $item["url"] . '\', \'' . $item["name"] . '\');"><img style="width: 35px; height:35px" src="' . $item["image"] . '" /></a></li>';    
                } elseif( array_key_exists("script", $item) ){
                    $returnHtml .= '<a id=\'' . $item["id"] . '\' onclick="' . $item["script"] . '"><img style="width: 35px; height:35px" src="' . $item["image"] . '" /></a></li>';
                }
          } 
         

      }
  
      $returnHtml .= '</ul>';
      
      return $returnHtml;
  }
  
  function printHeader()
  {
  ?>
  <iframe src="tab.php" class="newcall" id="phone_call" name="phone_call" style="display:none;" ></iframe>
  <?php 
	echo '
<div id="header">
	<h1>Platform</h1>';
	if($this->auth->checkAuthentication()) { 
	echo '
       <script type="text/javascript" >
                function red5username(){
                        return "101220";
                }
                function red5password(){
                        return "";
                }
        </script>

        '; 
       

	}

        /*  I made it so the config is in an array 
         * config_slimcrm_menu() is in the global.config.php
         * any question email me
         * tholum@couleetechlink.com
         */
        global $DEBUG_TIME ;
        $DEBUG_TIME .= "\n" . __LINE__ . ":" . __FILE__ . "::" . date("H:i:s") . ":" . microtime();
        echo  $this->menuFromArray( config_slimcrm_menu() );    
   $DEBUG_TIME .= "\n" . __LINE__ . ":" . __FILE__ . "::" . date("H:i:s") . ":" . microtime();
echo '</div>

';
	}
	
	function printContentAreaStart()
	{
		echo '
<div id="content_area">';
	}
 
	function printContentColumnStart($full='')
	{
		if($full=='full')
		echo '<div id="content_column1">';
		else
		echo '<div id="content_column">';
	}
  
	function printContentColumnEnd()
	{
		echo '
	</div>
	
	<div id="info_column">';
	}
	
	function printInfoColumnEnd()
	{
		echo '
	</div>';
	}
	
	function printFooter()
	{
	echo '
	<div id="footer"></div>
	';
	}
	
	function printContentAreaEnd()
	{
		echo '
        </div>';
	}
  
  function printGoogAna() // google analytics code
  {
	echo '';
  }

  function printBodyEnd()
  {
  ?>
  <?php 
	echo '
    </body>';
  }
  
  function printHTMLEnd()
  {
    echo '
    </html>';
  }
  
  function CheckAuthorization()
  {
  	$this->auth->CheckAuthorization($this->access_rules, $this->access_rules_type);
  }
  
  function gotoPage($page)
  {
    echo '<script type="text/javascript">
			window.location="'.$page.'";
		  </script>';
  }
  
}

?>


