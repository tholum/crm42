 <?php
mysql_connect(DATABASE_HOST,DATABASE_USER,DATABASE_PASSWORD);
mysql_select_db(DATABASE_NAME);
$sql="select * from ". TBL_THEME;
$d=mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_array($d);

?>

<style type="text/css">

/* Form CSS */
a{
	color:<?php echo $row['hyperlink_color']; ?> !important;
	text-decoration:none;
}

a:hover{
	color:#000000;
	text-decoration:none;
}

/* Form CSS */
.Label{
float:left;
width:30%;
text-align:right;
font-weight:bold;
}
.Field{
float:left;
width:50%;
}
.Clear{
clear:both;
padding:10px;
width:100%;
}
.space{
	float:left;
	width:2%;
}
.heading{
	font-size:120%;
	font-weight:bold;
}
.bcolor{
	color:<?php echo BG_COLOR; ?>;
}
.textb{
	font-weight:bold;
}
.form_bg{
	background-color:<?php echo FORM_BG_COLOR; ?>;
	font-family:Arial, Helvetica, sans-serif;
	font-size:74%;
	font-weight:normal;
	padding:6px;
}
.form_bg input[type=file]{
	font-size:90%;
}

.small_text {
font-size:12px;
}
.verysmall_text {
font-size:10px;
}
.form_main{
	padding:10px;
	font-family:Arial, Helvetica, sans-serif;
	font-size:90%;
	font-weight:normal;
}
.form_main .contact_match{
	border-top:1px #CCCCCC dotted;
	padding:15px;
	clear:both;
}
.form_main .label{
	float:left; width:10%;
}
.form_main .field{
	float:left; width:40%
}
.form_main .dis{
	float:left;
	/*width:50%;*/
}
.form_main .head{
	font-size:80%;
	font-weight:bold;
}
.form_main div{
	padding-bottom:5px;
}

.contact_textbox input[type=text]{
	width:70%;
	font-size:180%;
}
.form_main select{
	width:20%;
	border-top:1px #a2a2a2 solid;
	border-left:1px #a2a2a2 solid;
	font-size:90%;
}

.form_main .table select{
	width:100% !important; 
	border-top:1px #a2a2a2 solid;
	border-left:1px #a2a2a2 solid;
	font-size:90%;
}

.file_manager{
	border-bottom:1px #CCCCCC dotted;
	padding:2%;
}

.file_manager li {
	list-style:none;
	padding-bottom:2px;
}
.file_manager li a{
	font-size:90%;
	color:<?php echo FILE_MANAGER_LI_COLOR; ?>;
	font-weight:bold;
}
.contact_form{
	padding:10px;
}
.contact_form_fields {
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	font-weight:normal;
	margin: 0 auto;
}
.contact_form_fields tr{
	height:32px;
}
.contact_form_fields tr th{
	text-align:right;
	font-weight:bold;
}
.contact_form_fields select{
	width:150px;
}
.border_t{
	border-top:1px #CCCCCC dotted;
}
.border_b{
	border-bottom:1px #CCCCCC dotted;
}
.image_border
{
border:1px solid #CCCCCC;
}
.contact_match a{
	text-decoration:none;
	color:#666666;
}
.gray{
color:#999999;
}

.floatleft {
float:left;
}
.paddingLeft {
padding-left:58px;
}

.paddingRight {
padding-right:58px;
}

.alignRight{
text-align:right;
}
.clear_pbox{
	clear:both;
	padding:10px;
	width:100%;
}
.profile_box1{
	/*float:left;*/
	background-color:#f3f3f3;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	font-weight:normal;
	/*line-height:22px;*/
	/*width:50%;*/
	border-bottom:1px #cccccc solid;
	border-right:1px #cccccc solid;
	padding:10px;
}
.profile_box1 h2{
	font-weight:bold;
}
.profile_space{
	float:left;
}
.profile_box2{
	float:left;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	font-weight:normal;
	line-height:22px;
	background-color:#f3f3f3;
	padding:10px;
	width:40%;
	border-bottom:1px #cccccc solid;
	border-right:1px #cccccc solid;
}
.display_tag{
	position:fixed;
	background:#CCCCFF;
	border:1px #FFFFFF solid;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	padding:10px;
}
.rowborder {
border-bottom:1px solid #CCCCCC;
padding:10px;
}

.lefthead{
background:<?php echo LEFT_HEAD; ?>;
border: 1px solid #DCDCDC;
font-weight:bold;
margin-bottom:5px;
padding:5px 10px;
}
.errorhead {
	font-size: 14px;
	font-weight: bold;
	color: #FF0000;
	background-color: #CCFFCC;
}
.errortxt {
    text-align:left;
	font-size: 11px;
	color: #FF0000;
	padding-left:30px;
}

.required {
padding:3px;
font-family: Tahoma, Verdana; 
font-size: 11px;
color: #000000;
display:block;
font-weight:normal !important;

}
.normal {
padding:3px;
font-family: Tahoma, Verdana; 
font-size: 11px;
color: #000000;
display:none;
}

#note{
position:fixed; top:0; text-align:center; width: 100%;
}
#message_t{
width:300px; margin: 0 auto; background-color: #CC0000; color:#FFFFFF ;padding: 4px; font-size:12px;
font-weight:bold;
}

.complete{
text-decoration:line-through; color:#CCCCCC
}
.form_main a{
	text-decoration:none;
	color:#666666;
}

.recording_content  {
-moz-border-radius:10px 10px 10px 10px;
border:2px solid #E7E7E7;
padding:10px 0 0 15px;
margin-bottom:15px;
}

.recording_content li {
color:#777777;
font-size:11px;
line-height:1.5;
list-style:none outside none;
margin:0;
padding:0;
}

#notehead{
margin-bottom: 15px;
}

li.date  {
color:#000000;
font-weight:bold;
}

#new_note_container h2 {
font-size:14px;
line-height:15px;
margin:0;
padding:0 0 10px;
}

#submitbutton{
padding-top:15px;
padding-right:56px;
padding-bottom:20px;
}

#description{
padding-bottom:15px;
font-size:12px;
}

#descriptionbox{
font-size:12px;
}

.table td {
padding:4px;
font-size:12px;
vertical-align:top;
}

/*.table td {
background-color:#FFFFFF;
}
.table tr :hover{
background-color:#F4F9FC;
}
*/
.table th {
padding:4px;
font-size:12px;
font-weight:bold;
/*text-align:right;*/
vertical-align:middle;
}
.table td input {
font-size:12px;
width:100%;
vertical-align:top;
}


.dueTask{
/*line-height:50px;
font-size:16px;
font-weight:bold;
padding-left:50px;*/
padding-bottom:10px;
}
.file_m_padding{
	padding:10px;
}

.task_action {
z-index:10; 
position:absolute;
margin:-5px 0 0 -86px;
}

.office_action {
z-index:10; 
position:absolute;
margin:-11px 0 0 -12px;
}

.file_action {
z-index:10; 
position:absolute;
margin:2px 0 0 -5px;
}

.task_padding {
padding-left:80px;
}

#group_header{
	border-bottom:1px #CCCCCC dotted;
	font-size:18px;
	font-weight:bold;
	padding-top:15px;
	clear:both;
}

.group_action {
z-index:10; 
position:absolute;
margin:31px 0 0 -26px;
}

.textareadiv {
	width: 268px;
	height: 79px;
	border: 1px solid #CCCCCC;
	padding: 5px;
	background-color:#FFFFFF;
	overflow:auto;
	}

.edit_border {
	width:	93%;
	border: 3px solid #3366cc;
	padding: 8px;
	background-color:#3366CC;
	background-image:url("images/transparent_60.png");

}

.addGroup select {
width:200px;
}

.width70{
width:70%;
padding:5px;
}

#prl{
height:100%;
left:0;
position:fixed;
top:0;
width:100%;
z-index:90;
background-color:#000000;
  opacity: 0.5;
  filter: alpha(opacity = 50);
}

.prl{
height:100%;
left:0;
position:fixed;
top:0;
width:100%;
z-index:90;
background-color:#000000;
  opacity: 0.5;
  filter: alpha(opacity = 50);
}

#lightbox{
display:block;
margin-left:-215px;
margin-top:119px;
left:33%;
position:fixed;
text-align:left;
z-index:100;
}

.white_content {
-moz-border-radius-bottomright: 2em; -moz-border-radius-bottomleft: 2em;
background-color:#FFFFFF;
max-width:900px;
max-height:450px;
overflow: auto;
border: 16px solid #ADC2EB;
z-index:110;
  opacity: 1;
  filter: alpha(opacity = 100);
/*width:810px;;
*/
min-width:800px;}

.ajax_heading {
-moz-border-radius-topright: 2em; -moz-border-radius-topleft: 2em;
overflow: hidden;
border: 16px solid #ADC2EB;
z-index:110;
  opacity: 1;
  filter: alpha(opacity = 100);
  max-width:900px;
min-width:800px;
}

	
#TB_ajaxWindowTitle {
color:BlueViolet;
float:left;
/*font-family:Tahoma;*/
font-size:15px;
/*font-style:italic;*/
font-weight:bold;
margin-bottom:1px;
padding:7px 0 5px 10px;
text-decoration:none;
}

#TB_closeAjaxWindow {
float:right;
margin-bottom:1px;
padding:0px 0px 0px 0;
text-align:right;
}

#prl_image{
margin-left: 500px;
margin-right: auto;
margin-top:300px;
}

#adminnav{
float:right; padding-right:10px;
}

.red {
color:#FF0000;
}

.link_list {
}

.link_list li{
 display: inline;
}

.profile_head
{

font-size:14px !important;
font-style:italic;
font-weight:bold;
}


#error_list li span{
padding-left:20px;
text-align:left;
 padding-bottom:13px;
 color:#FF0000 !important;
}

.info_column_search {
height:25px;
margin-bottom:40px;
width:100%;
}

#info_column h3 {
margin-bottom:3px;
}

.event_form th{
 
 background-color:#e6e6e6;
 text-align:left;
 font-weight:bold;
 padding:4px;
}
/*
.event_form tr td{
vertical-align:top;
 padding:4px;
}

.alt2{
 background-color:#e6e7fb;
}
*/
.hotel{
vertical-align:top;
float:left;
padding:15px;
width:25%;
}

.noshow {visibility: hidden; display:none;}

.show {visibility: visible; display:block;}

.task_day {
margin-left:-39px;
padding-right:10px;
}

</style>