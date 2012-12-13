<?php
require_once('class/theme.config.php');
require_once('app_code/config.inc.php');

mysql_connect(DATABASE_HOST,DATABASE_USER,DATABASE_PASSWORD);
mysql_select_db(DATABASE_NAME);
$sql="select * from ".TBL_THEME;
$result=mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_array($result);
?>
<style type="text/css">
/*Copyright (c) 2010,Yahoo! Inc. All rights reserved.Code licensed under the BSD License:http://developer.yahoo.com/yui/license.htmlversion:2.8.2r1*/
	.yui-navset .yui-nav li,.yui-navset .yui-navset-top .yui-nav li,.yui-navset .yui-navset-bottom .yui-nav li {
	    margin:0 .5em 0 0;
	}
	.yui-navset-left .yui-nav li,.yui-navset-right .yui-nav li {
	    margin:0 0 .5em;
	}
	.yui-navset .yui-content .yui-hidden {
	    border:0;
	    height:0;
	    width:0;
	    padding:0;
	    position:absolute;
	    left:-999999px;
	    overflow:hidden;
	    visibility:hidden;
	}
	.yui-navset .yui-navset-left .yui-nav,.yui-navset .yui-navset-right .yui-nav,.yui-navset-left .yui-nav,.yui-navset-right .yui-nav {
	    width:6em;
	}
	.yui-navset-top .yui-nav,.yui-navset-bottom .yui-nav {
	    width:auto;
	}
	.yui-navset .yui-navset-left,.yui-navset-left {
	    padding:0 0 0 6em;
	}
	.yui-navset-right {
	    padding:0 6em 0 0;
	}
	.yui-navset-top,.yui-navset-bottom {
	    padding:auto;
	}
	.yui-nav,.yui-nav li {
	    margin:0;
	    padding:0;
	    list-style:none;
	}
	.yui-navset li em {
	    font-style:normal;
	}
	.yui-navset {
	    position:relative;
	    zoom:1;
	}
	.yui-navset .yui-content,.yui-navset .yui-content div {
	    zoom:1;
	}
	.yui-navset .yui-content:after {
	    content:'';
	    display:block;
	    clear:both;
	}
	.yui-navset .yui-nav li,.yui-navset .yui-navset-top .yui-nav li,.yui-navset .yui-navset-bottom .yui-nav li {
	    display:inline-block;
	    display:-moz-inline-stack;
	    *display:inline;
	    vertical-align:bottom;
	    cursor:pointer;
	    zoom:1;
	}
	.yui-navset-left .yui-nav li,.yui-navset-right .yui-nav li {
	    display:block;
	}
	.yui-navset .yui-nav a {
	    position:relative;
	}
	.yui-navset .yui-nav li a,.yui-navset-top .yui-nav li a,.yui-navset-bottom .yui-nav li a {
	    display:block;
	    display:inline-block;
	    vertical-align:bottom;
	    zoom:1;
	}
	.yui-navset-left .yui-nav li a,.yui-navset-right .yui-nav li a {
	    display:block;
	}
	.yui-navset-bottom .yui-nav li a {
	    vertical-align:text-top;
	}
	.yui-navset .yui-nav li a em,.yui-navset-top .yui-nav li a em,.yui-navset-bottom .yui-nav li a em {
	    display:block;
	}
	.yui-navset .yui-navset-left .yui-nav,.yui-navset .yui-navset-right .yui-nav,.yui-navset-left .yui-nav,.yui-navset-right .yui-nav {
	    position:absolute;
	    z-index:1;
	}
	.yui-navset-top .yui-nav,.yui-navset-bottom .yui-nav {
	    position:static;
	}
	.yui-navset .yui-navset-left .yui-nav,.yui-navset-left .yui-nav {
	    left:0;
	    right:auto;
	}
	.yui-navset .yui-navset-right .yui-nav,.yui-navset-right .yui-nav {
	    right:0;
	    left:auto;
	}
	.yui-skin-sam .yui-navset .yui-nav,.yui-skin-sam .yui-navset .yui-navset-top .yui-nav {
	    border:solid #2647a0;
	    border-width:0 0 5px;
	    zoom:1;
	}
	.yui-skin-sam .yui-navset .yui-nav li,.yui-skin-sam .yui-navset .yui-navset-top .yui-nav li {
	    margin:0 .16em 0 0;
	    padding:1px 0 0;
	    zoom:1;
	}
	.yui-skin-sam .yui-navset .yui-nav .selected,.yui-skin-sam .yui-navset .yui-navset-top .yui-nav .selected {
	    margin:0 .16em -1px 0;
		background:<?php echo $row['tab_sel_color']; ?>;
	}
	.yui-skin-sam .yui-navset .yui-nav a,.yui-skin-sam .yui-navset .yui-navset-top .yui-nav a {
	  /*  background:#d8d8d8 url(../../../../assets/skins/sam/sprite.png) repeat-x;*/
		<?php if($row['tab_default_color']){ ?>
		background: <?php echo $row['tab_default_color']; ?>;
		<?php }
		else {?>
		background:<?php echo TAB_DEFAULT_COLOR; ?>;
		<?php } ?>
	    border:solid #a3a3a3;
	    border-width:0 1px;
		<?php if($row['tab_sel_color']){ ?>
		background: <?php echo $row['tab_sel_color']; ?>;
		<?php }
		else {?>		
	    background:<?php echo TAB_SELECTED_COLOR; ?>;
		<?php } ?>
	    position:relative;
	    text-decoration:none;
	}
	.yui-skin-sam .yui-navset .yui-nav a em,.yui-skin-sam .yui-navset .yui-navset-top .yui-nav a em {
	    border:solid #a3a3a3;
	    border-width:1px 0 0;
	    cursor:hand;
	    padding:.25em .75em;
	    left:0;
	    right:0;
	    bottom:0;
	    top:-1px;
	    position:relative;
		<?php if($row['tab_default_color']){ ?>
		color: <?php echo $row['tab_default_color']; ?>;
		<?php }
		else {?>
		color:<?php echo TAB_SELECTED_COLOR_DEFAULT; ?>;
		<?php } ?>
	}
	.yui-skin-sam .yui-navset .yui-nav .selected a,.yui-skin-sam .yui-navset .yui-nav .selected a:focus,.yui-skin-sam .yui-navset .yui-nav .selected a:hover {
	    background:#2647a0 url(../../../../assets/skins/sam/sprite.png) repeat-x left -1400px;
	    color:#fff;
	}
	.yui-skin-sam .yui-navset .yui-nav a:hover,.yui-skin-sam .yui-navset .yui-nav a:focus {
	    background:#bfdaff url(../../../../assets/skins/sam/sprite.png) repeat-x left -1300px;
	    outline:0;
	}
	.yui-skin-sam .yui-navset .yui-nav .selected a em {
	    padding:.35em .75em;
	}
	.yui-skin-sam .yui-navset .yui-nav .selected a,.yui-skin-sam .yui-navset .yui-nav .selected a em {
	    border-color:#243356;
	}
	.yui-skin-sam .yui-navset .yui-content {
	    <?php if($row['body_server_name']){ ?>
		background: url(<?php echo 'uploads/'.$row['body_server_name']; ?>);
		<?php }
		else {?>
		background:#edf5ff;
		<?php } ?>
	}
	.yui-skin-sam .yui-navset .yui-content,.yui-skin-sam .yui-navset .yui-navset-top .yui-content {
	    border:1px solid #808080;
	    border-top-color:#243356;
	    padding:.25em .5em;
	}
	.yui-skin-sam .yui-navset-left .yui-nav,.yui-skin-sam .yui-navset .yui-navset-left .yui-nav,.yui-skin-sam .yui-navset .yui-navset-right .yui-nav,.yui-skin-sam .yui-navset-right .yui-nav {
	    border-width:0 5px 0 0;
	    Xposition:absolute;
	    top:0;
	    bottom:0;
	}
	.yui-skin-sam .yui-navset .yui-navset-right .yui-nav,.yui-skin-sam .yui-navset-right .yui-nav {
	    border-width:0 0 0 5px;
	}
	.yui-skin-sam .yui-navset-left .yui-nav li,.yui-skin-sam .yui-navset .yui-navset-left .yui-nav li,.yui-skin-sam .yui-navset-right .yui-nav li {
	    margin:0 0 .16em;
	    padding:0 0 0 1px;
	}
	.yui-skin-sam .yui-navset-right .yui-nav li {
	    padding:0 1px 0 0;
	}
	.yui-skin-sam .yui-navset-left .yui-nav .selected,.yui-skin-sam .yui-navset .yui-navset-left .yui-nav .selected {
	    margin:0 -1px .16em 0;
	}
	.yui-skin-sam .yui-navset-right .yui-nav .selected {
	    margin:0 0 .16em -1px;
	}
	.yui-skin-sam .yui-navset-left .yui-nav a,.yui-skin-sam .yui-navset-right .yui-nav a {
	    border-width:1px 0;
	}
	.yui-skin-sam .yui-navset-left .yui-nav a em,.yui-skin-sam .yui-navset .yui-navset-left .yui-nav a em,.yui-skin-sam .yui-navset-right .yui-nav a em {
	    border-width:0 0 0 1px;
	    padding:.2em .75em;
	    top:auto;
	    left:-1px;
	}
	.yui-skin-sam .yui-navset-right .yui-nav a em {
	    border-width:0 1px 0 0;
	    left:auto;
	    right:-1px;
	}
	.yui-skin-sam .yui-navset-left .yui-nav a,.yui-skin-sam .yui-navset-left .yui-nav .selected a,.yui-skin-sam .yui-navset-left .yui-nav a:hover,.yui-skin-sam .yui-navset-right .yui-nav a,.yui-skin-sam .yui-navset-right .yui-nav .selected a,.yui-skin-sam .yui-navset-right .yui-nav a:hover,.yui-skin-sam .yui-navset-bottom .yui-nav a,.yui-skin-sam .yui-navset-bottom .yui-nav .selected a,.yui-skin-sam .yui-navset-bottom .yui-nav a:hover {
	    background-image:none;
	}
	.yui-skin-sam .yui-navset-left .yui-content {
	    border:1px solid #808080;
	    border-left-color:#243356;
	}
	.yui-skin-sam .yui-navset-bottom .yui-nav,.yui-skin-sam .yui-navset .yui-navset-bottom .yui-nav {
	    border-width:5px 0 0;
	}
	.yui-skin-sam .yui-navset .yui-navset-bottom .yui-nav .selected,.yui-skin-sam .yui-navset-bottom .yui-nav .selected {
	    margin:-1px .16em 0 0;
	}
	.yui-skin-sam .yui-navset .yui-navset-bottom .yui-nav li,.yui-skin-sam .yui-navset-bottom .yui-nav li {
	    padding:0 0 1px 0;
	    vertical-align:top;
	}
	.yui-skin-sam .yui-navset .yui-navset-bottom .yui-nav a em,.yui-skin-sam .yui-navset-bottom .yui-nav a em {
	    border-width:0 0 1px;
	    top:auto;
	    bottom:-1px;
	}
	.yui-skin-sam .yui-navset-bottom .yui-content,.yui-skin-sam .yui-navset .yui-navset-bottom .yui-content {
	    border:1px solid #808080;
	    border-bottom-color: #243356;
	}
</style>