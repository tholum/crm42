<?
ini_set("display_errors" , 1 );
require_once("class/class.yui.php");
$yui = new phpyui("/yui2/");
//"/asterisk/phone/checkcall.all.php?ext=200&s=YES&account=101"
$yui->add_datatable( "asterisk" ,  "xml" , "temp.xml.php"  ,  array( 0 => array("key" => "call")) , array() , array( "RefreshEvery" => "1000")  );

?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="/yui2/fonts/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="/yui2/tabview/assets/skins/sam/tabview.css" />
<link rel="stylesheet" type="text/css" href="/yui2/datatable/assets/skins/sam/datatable.css" >

<?php echo $yui->get_header(); ?>
<style type="text/css" >

#asterisk {
    position: absolute;
    top: -2px;
    left: 500px;
}
#asterisk tr.yui-dt-even { background: url("images/greenTB.png");
background-position: center;} /* white */
#asterisk tr.yui-dt-odd { background: url("images/blueTB.png");
background-position: center; } /* light blue */
#asterisk thead { display: none; }

</style>
</head>
<body class="yui-skin-sam" >

<?php echo $yui->get_body();?>
<div id="asterisk"></div>
