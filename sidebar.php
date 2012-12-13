<?php
mysql_connect(DATABASE_HOST,DATABASE_USER,DATABASE_PASSWORD);
mysql_select_db(DATABASE_NAME);
$sql="select * from ". TBL_THEME;
$d=mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_array($d);
?>

<style type="text/css">
/* 
    Document   : sidebar
    Created on : Jan 29, 2011, 4:46:22 PM
    Author     : tholum
    Description:
        Purpose of the stylesheet follows.
*/

/* 
   TODO customize this sample style
   Syntax recommendation http://www.w3.org/TR/REC-CSS2/
*/

			body {
				font-family: verdana;
				background-color: #fff;
			}
			.Rec {
				position: relative;
				border: 1px solid #dbe5cf;
				background-color: #fff;
				color: #000;
				width: 310px;
				height: 125px;
				margin: 5px;
				margin-bottom: 20px;
				text-align: center;
				font-size: 100%;
			}
			#Column1, #Column2, #Column3 {
				position: relative;
				float: left;
				width: 325px;
				height: 100%;
			}
			.Handle {
				width: auto;
				height: 27px;
				background-image: url("images/bluebar1.png");
				background-repeat: repeat-x;
				border-bottom: 0;
				text-align: left;
				color: #376F18;
				font-size: 77%;
				font-weight: bold;
				padding: 5px 4px;
				cursor: move;
			}
			.Rec h1 {
				color: #ebebeb;
			}
			#Rec1 {height:150px;}
			#Rec6 {height:175px;}
			#About {height:200px;}
			.Info {
				padding: 5px;
				text-align: left;
				font-size: 77%;
			}
                        div.sidebarheader{
                            text-align: center;
                            color: white;
                            position: relative;
                            top: 0px;
                            left: 0px;
                            background: url(images/bluebar3.png) repeat-x;
                            border:solid #a3a3a3;
                            border-width:0 1px;
                            height:32px;
                            width:310px;
                            background-position: top;
                            text-align:center;
                        }
                        a.sidebarheader{
                            position: relative;
                            color: white;
                            font-weight: bold;
                            top: 5px;
                        }
</style>