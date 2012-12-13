<?php 
class ThemeManagement{
	
	var $db;      ///////platform\class\class.tags.php//////
	var $validity;
	var $Form;	
	
	function __construct(){
	  $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	  $this->validity = new ClsJSFormValidation();
	  $this->Form = new ValidateForm();
	  }
	
	function picker($clr_clr,$clr_txt,$clr_witness,$function_name,$target_name)
          {
		  ob_start();
		  ?>
		  <table border="1px" style="margin:10px 20px 10px 20px;">
		  <tr><td><input type="text" name="<?php echo $clr_txt; ?>" id="<?php echo $clr_txt; ?>" style='width:124px;height:15px;'/></td></tr>
		  <tr><td><div id="<?php echo $clr_witness; ?>" style='width:128px;height:15px;'> </div></td></tr>
		  <tr><td>
	                <script language="Javascript" type="text/javascript">
		            var total=1657;var X=Y=j=RG=B=0;
				    var aR=new Array(total);var aG=new Array(total);var aB=new Array(total);
					for (var i=0;i<256;i++){
					aR[i+510]=aR[i+765]=aG[i+1020]=aG[i+5*255]=aB[i]=aB[i+255]=0;
					aR[510-i]=aR[i+1020]=aG[i]=aG[1020-i]=aB[i+510]=aB[1530-i]=i;
					aR[i]=aR[1530-i]=aG[i+255]=aG[i+510]=aB[i+765]=aB[i+1020]=255;
					if(i<255){aR[i/2+1530]=127;aG[i/2+1530]=127;aB[i/2+1530]=127;}
					}
					var hexbase=new Array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F");
					var i=0; var jl=new Array();
					for(x=0;x<16;x++)for(y=0;y<16;y++)jl[i++]=hexbase[x]+hexbase[y];
					document.write('<'+'table border="0" cellspacing="0" cellpadding="0" onMouseover="<?php $name=$target_name.'(event)'; echo $name;?>" onClick="<?php $name=$function_name.'()'; echo $name;  ?>">');
					var H=W=63;
					for (Y=0;Y<=H;Y++){
						s='<'+'tr height=2>';j=Math.round(Y*(510/(H+1))-255)
						for (X=0;X<=W;X++){
							i=Math.round(X*(total/W))
							R=aR[i]-j;if(R<0)R=0;if(R>255||isNaN(R))R=255
							G=aG[i]-j;if(G<0)G=0;if(G>255||isNaN(G))G=255
							B=aB[i]-j;if(B<0)B=0;if(B>255||isNaN(B))B=255
							s=s+'<'+'td width=2 bgcolor=#'+jl[R]+jl[G]+jl[B]+'><'+'/td>'
						}
						document.write(s+'<'+'/tr>')
					}
					document.write('<'+'/table>');	
					var ns6=document.getElementById&&!document.all
					var ie=document.all
					var artabus=''
					function <?php $name=$function_name.'()'; echo $name;  ?>{
					     var a='<?php echo $clr_clr; ?>';
						 var b='<?php echo $clr_txt; ?>';
						 var jla=document.getElementById(a);
						 var jl=document.getElementById(b);
						  jl.value=artabus;
						  jla.style.backgroundColor=artabus;
						 }
					function <?php $name=$target_name.'(e)'; echo $name;?>{
					source=ie?event.srcElement:e.target
					if(source.tagName=="TABLE")return
					while(source.tagName!="TD" && source.tagName!="HTML")source=ns6?source.parentNode:source.parentElement
					document.getElementById('<?php echo $clr_witness; ?>').style.backgroundColor=artabus=source.bgColor
					}
					</script></td></tr>
	   <tr><td></td></tr>
	   
</table>
     <?php	
	 $html=ob_get_contents();
	 ob_get_clean();
	 return $html;
	 		  }	
		  
	function getRandomName($filename) {           ///////platform\upload.php///////
	$file_array = explode(".",$filename);
	$file_ext = end($file_array);
	$new_file_name = uniqid().date('m').date('d').date('Y').date('G').date('i').date('s').".".$file_ext;
	return $new_file_name;
 }
	function manageTheme($runat){
		switch($runat){
			case 'local':          /////////HTML PART/////////
				$frmName = 'frm_manage_theme';
				?>
				
				<form name="<?php echo $frmName;?>" action="" method="post" enctype="multipart/form-data">
				<table>
				  <tr><td><input type="file" name="upload0" id="upload0" value="Browse" /></td><th>Logo</th></tr>
				  <tr><td><input type="file" name="upload1" id="upload1" value="Browse" /></td><th>Header Image</th></tr>
				  <tr><td><input type="file" name="upload2" id="upload2" value="Browse"/></td><th>Body Image</th></tr>
				  <tr><td><input type="file" name="upload3" id="upload3" value="Browse" /></td><th>Footer Image</b></th></tr>
				  <tr><td><?php echo $this->picker('heading_color_1','heading_txt_1','heading_witness_1','function_p_1','function_target_1'); ?></td>
     			  <td><div id='heading_color_1' style='float:right;width:20px;height:15px;border:1px solid #ccc; background-color:#FFFFFF;'> </div></td>
				  <th>Heading Color 1</th></tr>
				  <tr><td><?php echo $this->picker('heading_color_2','heading_txt_2','heading_witness_2','function_p_2','function_target_2'); ?> </td>
				  <td><div id="heading_color_2" style='float:right;width:20px;height:15px;border:1px solid #ccc; background-color:#FFFFFF;'> </div></td>
				  <th>Heading Color 2</th></tr>
				  <tr><td><?php echo $this->picker('tab_default_color','tab_default_txt','tab_default_witness','tab_default_function','tab_default_target'); ?> </td>
				  <td><div id="tab_default_color" style='float:right;width:20px;height:15px;border:1px solid #ccc; background-color:#FFFFFF;'> </div></td>
				  <th>Tab Default color</th></tr>
				  <tr><td><?php echo $this->picker('tab_selected_color','tab_selected_txt','tab_selected_witness','tab_selected_function','tab_selected_target');?></td>
				  <td><div id="tab_selected_color" style='float:right;width:20px;height:15px;border:1px solid #ccc; background-color:#FFFFFF;'> </div></td>
				  <th>Tab Selected Color</th></tr>
				  <tr><td><?php echo $this->picker('tab_default_letter_color','tab_default_letter_txt','tab_default_letter_witness','tab_default_letter_function','tab_default_letter_target');?></td>
		   		  <td><div id="tab_default_letter_color" style='float:right;width:20px;height:15px;border:1px solid #ccc; background-color:#FFFFFF;'> </div></td>
				  <th>Tab Default Letter Color</th></tr>
	    		  <tr><td><?php echo $this->picker('tab_selected_color_default','tab_selected_color_default_txt','tab_selected_color_default_witness','tab_selected_color_default_function','tab_selected_color_default_target');?></td>
				  <td><div id="tab_selected_color_default" style='float:right;width:20px;height:15px;border:1px solid #ccc; background-color:#FFFFFF;'> </div></td>
				  <th>Tab Selected Color Default</th></tr>
			      <tr><td><?php echo $this->picker('text_color','text_color_txt','text_color_witness','text_color_function','text_color_target');?></td>
				  <td><div id="text_color" style='float:right;width:20px;height:15px;border:1px solid #ccc; background-color:#FFFFFF;'> </div></td>
				  <th>Text Color</th></tr>
                  <tr><td><?php echo $this->picker('hyperlink_color','hyperlink_txt','hyperlink_witness','hyperlink_function','hyperlink_target');?></td>
				  <td><div id="hyperlink_color" style='float:right;width:20px;height:15px;border:1px solid #ccc; background-color:#FFFFFF;'> </div></td>
				  <th>Hyperlink Color</th></tr>
				  <tr><td rowspan="2"><input type="submit" name="submit" value="Submit"/></td></tr>
				
				</table>
				</form>
				<?php
				break;
			case 'server':  
			
			$sql="select * from ".TBL_THEME; 
			$record=$this->db->record_number($sql,__FILE__,__LINE__);
				 if($record<=0)
				 {
				    extract($_POST);
					 $insert_sql_array = array();
					 $insert_sql_array['id'] = '1';
					 $destination_path='uploads/'; 
					 if($_FILES[upload0][name]){
					 	$file_name = $_FILES[upload0][name];
					 	$file_array = explode(".",$file_name);
						$file_ext = end($file_array);
						$target=$destination_path. basename('logo.'.$file_ext);
						//echo $target;
					 	move_uploaded_file($_FILES['upload0']['tmp_name'], $target);
						$insert_sql_array['logo_file_name'] = $file_name;
						$insert_sql_array['logo_server_name'] = 'logo.'.$file_ext;
					 }
					 if($_FILES[upload1][name]){
					 	$file_name = $_FILES[upload1][name];
					 	$file_array = explode(".",$file_name);
						$file_ext = end($file_array);
					 	move_uploaded_file($_FILES['upload1']['tmp_name'], $destination_path. basename('header.'.$file_ext));
						$insert_sql_array['header_file_name'] = $file_name;
						$insert_sql_array['header_server_name'] = 'header.'.$file_ext;
					 }
					 if($_FILES[upload2][name]){
					 	$file_name = $_FILES[upload2][name];
					 	$file_array = explode(".",$file_name);
						$file_ext = end($file_array);
					 	move_uploaded_file($_FILES['upload2']['tmp_name'], $destination_path. basename('body.'.$file_ext));
						$insert_sql_array['body_file_name'] = $file_name;
						$insert_sql_array['body_server_name'] = 'body.'.$file_ext;
					 }
					 if($_FILES[upload3][name]){
					 	$file_name = $_FILES[upload3][name];
					 	$file_array = explode(".",$file_name);
						$file_ext = end($file_array);
					 	move_uploaded_file($_FILES['upload3']['tmp_name'], $destination_path. basename('footer.'.$file_ext));
						$insert_sql_array['footer_file_name'] = $file_name;
						$insert_sql_array['footer_server_name'] = 'footer.'.$file_ext;
					 }
					 
					 	
						//$insert_sql_array['id'] = '1';// =  $name['name'];      /////// TABLE column name----1//////
						$insert_sql_array['h1_color'] = $heading_txt_1; 
						$insert_sql_array['h2_color'] = $heading_txt_2; 
						$insert_sql_array['tab_default_color'] = $tab_default_txt;
						$insert_sql_array['tab_sel_color'] = $tab_selected_txt; 
						$insert_sql_array['default_letter_color'] = $tab_default_letter_txt;
						$insert_sql_array['tab_sel_color_default'] = $tab_selected_color_default_txt; 
						$insert_sql_array['text_color'] = $text_color_txt; 
						$insert_sql_array['hyperlink_color'] = $hyperlink_txt; 
						
						//print_r($insert_sql_array);
						
						
					$this->db->insert(TBL_THEME,$insert_sql_array);       ////////********platform\app_code\global.config.php********////////
				 }
				 else
				 {
				 
					 extract($_POST);
					 $insert_sql_array = array();
					 $insert_sql_array['id'] = '1';
					 $destination_path='uploads/'; 
					 if($_FILES[upload0][name]){
					 	$file_name = $_FILES[upload0][name];
					 	$file_array = explode(".",$file_name);
						$file_ext = end($file_array);
					 	move_uploaded_file($_FILES['upload0']['tmp_name'], $destination_path. basename('logo.'.$file_ext));
						$insert_sql_array['logo_file_name'] = $file_name;
						$insert_sql_array['logo_server_name'] = 'logo.'.$file_ext;
					 }
					 if($_FILES[upload1][name]){
					 	$file_name = $_FILES[upload1][name];
					 	$file_array = explode(".",$file_name);
						$file_ext = end($file_array);
					 	move_uploaded_file($_FILES['upload1']['tmp_name'], $destination_path. basename('header.'.$file_ext));
						$insert_sql_array['header_file_name'] = $file_name;
						$insert_sql_array['header_server_name'] = 'header.'.$file_ext;
					 }
					 if($_FILES[upload2][name]){
					 	$file_name = $_FILES[upload2][name];
					 	$file_array = explode(".",$file_name);
						$file_ext = end($file_array);
					 	move_uploaded_file($_FILES['upload2']['tmp_name'], $destination_path. basename('body.'.$file_ext));
						$insert_sql_array['body_file_name'] = $file_name;
						$insert_sql_array['body_server_name'] = 'body.'.$file_ext;
					 }
					 if($_FILES[upload3][name]){
					 	$file_name = $_FILES[upload3][name];
					 	$file_array = explode(".",$file_name);
						$file_ext = end($file_array);
					 	move_uploaded_file($_FILES['upload3']['tmp_name'], $destination_path.basename('footer.'.$file_ext));
						$insert_sql_array['footer_file_name'] = $file_name;
						$insert_sql_array['footer_server_name'] = 'footer.'.$file_ext;
					 }
					 
					 	if($heading_txt_1)
						{
						$insert_sql_array['h1_color'] = $heading_txt_1; 
						}
						if($heading_txt_2)
						$insert_sql_array['h2_color'] = $heading_txt_2; 
						
						if($tab_default_txt)
						$insert_sql_array['tab_default_color'] = $tab_default_txt;
						
						if($tab_selected_txt)
						$insert_sql_array['tab_sel_color'] = $tab_selected_txt; 
						
						if($tab_default_letter_txt)
						$insert_sql_array['default_letter_color'] = $tab_default_letter_txt;
						
						if($tab_selected_color_default_txt)
						$insert_sql_array['tab_sel_color_default'] = $tab_selected_color_default_txt; 
						
						if($text_color_txt)
						$insert_sql_array['text_color'] = $text_color_txt; 
						
						if($hyperlink_txt)
						$insert_sql_array['hyperlink_color'] = $hyperlink_txt;
						//echo $heading_txt_1;
						//echo "blank";
						//$insert_sql_array['id'] = '1';// =  $name['name'];      /////// TABLE column name----1//////
						/*$insert_sql_array['h1_color'] = $heading_txt_1; 
						$insert_sql_array['h2_color'] = $heading_txt_2; 
						$insert_sql_array['tab_default_color'] = $tab_default_txt;
						$insert_sql_array['tab_sel_color'] = $tab_selected_txt; 
						$insert_sql_array['default_letter_color'] = $tab_default_letter_txt;
						$insert_sql_array['tab_sel_color_default'] = $tab_selected_color_default_txt; 
						$insert_sql_array['text_color'] = $text_color_txt; 
						$insert_sql_array['hyperlink_color'] = $hyperlink_txt; */

					  $this->db->update(TBL_THEME,$insert_sql_array,id,'1');
					 }
				 	  ?>
					  <script>
					  window.location="<?= $_SERVER['PHP_SELF'] ?>";
					  </script>
					  <?php
		
				break;
			default: break;
		}
	}
}
?>