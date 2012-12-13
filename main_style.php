<?php
mysql_connect(DATABASE_HOST,DATABASE_USER,DATABASE_PASSWORD);
mysql_select_db(DATABASE_NAME);
$sql="select * from ".TBL_THEME;
$result=mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_array($result);
?>

<style type="text/css">

body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	<?php if($row['body_server_name ']){ ?>
	background: url(<?php echo 'uploads/'.$row['body_server_name ']; ?>);
	<?php }
	else {?>
	background: <?php echo MAIN_BG_COLOR; ?>;
	<?php } ?>
}
/* Normalize padding and margins */
 body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, form, fieldset, input, p, blockquote, th, td {
 margin: 0;
 padding: 0;
}

.newcall {
background-color:#3366CC;
background-image:url(images/transparent_60.png);
margin-left:25%;
width:43%;
margin-top:4px;
max-height:90px;
border:none;
position:fixed;
z-index:100;
}


/* Normalize header sizes */
h1, h2, h3, h4, h5, h6 {
 font-size: 100%;
}


/* Normalize list styles */
ol, ul {
 list-style: none;
}

/* Normalize font style and weight on odd elements */
address, caption, cite, code, dfn, em, strong, th, var {
 font-style: normal;
 font-weight: normal;
}

/* Normalize table borders */
table {
 border-collapse: collapse;
 border-spacing: 0;
}

/* Normalize other borders */
fieldset, img {
 border: 0;
}

/* Normalize text-alignment */
caption, th {
 text-align: left;
}

html, body {
 height: 100%;
 font-family: Verdana, arial;
 color: #333333;
 font-size: 14px;
}

.clear {
 clear:both;
}

#header {
 width: 100%;
<?php if($row['header_server_name ']){ ?>
	background: url(<?php echo 'uploads/'.$row['header_server_name ']; ?>);
<?php }
else {?>
	background: <?php echo MAIN_HEADER_COLOR; ?>;
<?php } ?>
}

h1 {
text-indent: -9999px;
height: 45px;
width: 200px;
padding: 5px;
float: left;
margin-bottom: 5px;
}

#general_links {
 color: #ffffff;
 float: right;
 padding: 5px;
 font-size: 12px;
}

#general_links li a {
 color: #ffffff;
}

#general_links li {
 display: inline;
}



#main_navigation {
 position: absolute;
 
 color: #ffffff;
 background: #000000;
 clear: both;
 /*margin-left: 2%; */
/* height: 26px;*/
 float: left;
 padding:4px;
 height: 26px;
 width: 100%;
}

#main_navigation li {
 background: <?php echo MAIN_BG_NAVIGATION; ?>;
 padding: 8px 12px;
 margin-right: 2px;
 display: inline;
 font-size: 15px;
}

#main_navigation li a {
 color: #ffffff;
 text-decoration: none;
}

#main_navigation li.active {
 background: <?php echo MAIN_BG_NAVIGATION_ACTIVE; ?>;
}

#main_navigation li.active  a{
 color: #333333;
 font-weight: bolder;
}

#main_navigation li:hover {
 background: <?php echo MAIN_BG_NAVIGATION_LIST_HOVER; ?>;
}

#main_navigation li.active:hover {
 background: <?php echo MAIN_BG_NAVIGATION_LIST_ACTIVE_HOVER; ?>;
}




#secondary_navigation {
 color: #ffffff;
 margin-right: 2%;
 height: 26px;
 float: right;
}

#support {
 background: <?php echo SUPPORT_BG_NAVIGATION; ?> !important;
 padding: 8px 12px;
 margin-right: 2px;
 display: inline;
 font-size: 15px;
}

#secondary_navigation li a {
 color: #ffffff;
 text-decoration: none;
}

#secondary_navigation li.active {
 background: url('images/transparent_90.png');
}

#secondary_navigation li.active  a{
 color: #333333;
 font-weight: bolder;
}

#secondary_navigation li:hover {
 background: url('images/transparent_30.png');
}

#secondary_navigation li.active:hover {
 background: url('images/transparent_90.png');
}


a{
	text-decoration:none; 
	color:<?php echo A_BG; ?>;
}

a:hover{
 	text-decoration:underline;
	color:<?php echo A_BG; ?>;
}

#content_area {
 clear: both;
 background: url('images/transparent_90.png');
 min-height: 700px;
}

#content_column {
 background: url('images/transparent_90.png');
 border-right: 1px solid #999999;
 border-bottom: 1px solid #999999;
 width: 68%;
 float: left;
}

#content_column_ful {
 background: url('images/transparent_90.png');
 border-right: 1px solid #999999;
 border-bottom: 1px solid #999999;
 width: 100%;
 float: left;
}

#content_column_header {
 padding: 10px;
 background-color: <?php echo CONTENT_COLUMN_HEADER_BG; ?>;
 background-image: url('images/transparent_60.png');
 font-size: 16px;
 font-weight: bolder;
 color:<?php echo $row[h1_color];?>;
}

#content_column_header a {
 font-size: 12px;
}


#info_column {
 width: 30%;
 float: left;
 margin-left: 1.5%;
}

#footer {
 clear: both;
}
.head_border .table {
 font-size:11px;
 }
 
 </style>
