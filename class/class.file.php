 <?php
 /***********************************************************************************

Class Discription :  This module will help manage all user submitted files including documents and images. Files
					can also have permissions allowing only certain user groups to see the file. Files need the
					ability to be put into categories such as “how tos” and ”contracts”.
					"Manage user uploaded files such as images and documents".

Class Memeber Functions : AddFile($runat)
						  AddFileCategory($runat)	
						  EditFileCategory($runat, $file_id)
						  getFileOfCategory($file_category_id)
						  getFileOfUser($user_id)
						  getAllFiles()

Describe Function of Each Memeber function: 
											1.AddFile($runat):add the files with file name,title,category,description and  
											uploaded files stored in directory,//tablename(Files_category);	//$runat(local/server)	 					
											
											2.AddFileCategory($runat):add the category of that uploaded file to the 
											directory;//tablename(Files_category);//$runat(local/server)
											
											3.EditFile($runat, $file_id):edit that uploaded files with diff title name,description,
											and usergroup which uploaded new file;//tablename(tbl_file);$runat(local/server) 
											 where a.file_id=$this->file_id and a.file_id=b.file_id";
											
											
											4.EditFileCategory($runat, $file_id): edit the category of file in 
											Files_category(file_category_id,category) where file_category_id=$this->file_id";
											//tablename(Files_category);
											
											5.getFileOfCategory($file_category_id):get all files on the basis of $file_category_id
											//(files_category)
											6.getFileOfUser($user_id);get the files for particular user_id frm tbl_file;//tbl_file;
											
											7.getAllFiles():get all the files uploaded by all user
************************************************************************************/

class File extends Tags
{

const MODULE='TBL_FILE';
var $file_id;
var $user_id;
var $directory;
var $name;
var $modified;
var $uploaded;
var $title;
var $description;
var $user_group;
var $category;
var $isprivate;
var $db;
var $objFileUpload; //object of class FileUpload
var $old_file_name;
var $validity;
var $Form;
var $mail_obj;
var $not_sent_cont_name= array();
var $sent_cont_name= array();
var $to;
	
	function __construct($UploadDirectory=''){
		parent::__construct();
		$this->objFileUpload = new FileUpload();
		$this->directory=$UploadDirectory;
		$this->modified=time();
		$this->uploaded=time();
		$this->mail_obj = new PHPMailer();
	}

	function SetUserID($user_id)
	{
		$this->user_id=$user_id;
	}
	function SetUserGroup($user_group){
	
		$this->user_group = $user_group;
	}
		

	/////Methods///////
	
	function AddFile($runat)
	{	
		switch($runat){
		case 'local':
						if(count($_POST)>0 and $_POST['submit']=='add this file'){
						  extract($_POST);
						  $this->name = $name;
						  $this->category = $category;
						  $this->title = $title;
						  $this->user_group = $user_group;
						  $this->description = $description;
						  $this->isprivate = $isprivate;
						}
						$FormName = "file";
						$ControlNames=array("name"			=>array('name',"''","*","spanname"),
											"category"			=>array('category',"''","*","spancategory1"),
											"title"			=>array('title',"''","*","spantitle"),
											);

						$ValidationFunctionName="CheckValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
					
					//display form
					?>
					<form method="post" action="" enctype="multipart/form-data" name="file">
					<table class="table">
					<tr>
					<th>Add this file :</th>
					<td><input type="file" name="name" id="name" value="<?php echo $this->name; ?>"></td>
					<td><span id="spanname"></span></td>
					</tr>
					<tr>
					<th>Who can see this :</th>
					<td>
					  <select name="user_group" id="user_group" >
                        <option value="">All User's</option>
					<?php
					$sql="select * from ".TBL_USERGROUP." order by group_name";
					$result=$this->db->query($sql,__FILE__,__LINE__);
					while($row=$this->db->fetch_array($result)){
					?>
                    <option value="<?php echo $row['group_name'];?>" <?php if($row['group_name']==$this->user_group) echo 'selected'; ?>><?php echo $row['group_name']; ?></option>
					 <?php
					 }
					 ?> </select></td>
					  <td><span id="spanuser_group"></span></td>
					</tr>
					<tr>
					<th>Category :</th>
					<td><select name="category" id="category" onchange="if(this.value=='NewCat'){ 
																	category_name = prompt('Enter name of category','');
																	if(category_name!=null){
																	if(category_name.length>0)
																	{
																		filesobj.AddFileCategoryOnFly('server',category_name,
																		{	onUpdate: function(response,root){  
																			if(response==1){
																			filesobj.GetFileCategoryJson({content_type:'json', target:'category', preloader:'prl'});
																			} else {
																			alert('Sorry !! category with name '+category_name+' already exists');
																			document.getElementById('category').options[0].selected = true;
																			document.getElementById('category').selectedIndex=0;
																			return true;
																				}
																			}
																		}
																		);
																	}
																	else{
																	this.options[0].selected = true;
																	this.selectedIndex=0;
																	return true;
																	} }
																	else 
																	{
																	this.options[0].selected = true;
																	this.selectedIndex=0;
																	return true;
																	}
																}">
																 
																 
																 
                        <option value="">select category</option>
                        <?php
						$sql="select * from ".FILES_CATEGORY." order by category";
						$result=$this->db->query($sql,__FILE__,__LINE__);
						while($row=$this->db->fetch_array($result)){
						?>
                        <option value="<?php echo $row['file_category_id'];?>" <?php if($row['file_category_id']=='$this->category') echo 'selected'; ?>><?php echo $row['category']; ?></option>
                        <?php
						 }//onclick="document.getElementById('display_cat').style.display=''; filesobj.AddFileCategory_onfly('local', { target:'display_cat', preloader: 'prl'});"
						?>
						<option value="NewCat" >new category</option>
                      </select>&nbsp;<span><a href="fileManagement.php">edit categories</a></span></td>
					<td>  <span id="spancategory1"></span></td>
					<tr>
					<th>Display title :</th>
					<td><input type="text" name="title" id="title" <?php echo $this->title; ?>></td>
					<td><span id="spantitle"></span></td>
					</tr>
					<tr>
					<th>Description :</th>
					<td><textarea name="description" id="description"><?php echo $this->description; ?></textarea></td>
					<td><span id="spandescription"></span></td>
					</tr>
					<tr>
					<th></th>
					<?php /*?><th colspan="2"><label><input type="checkbox" name="isprivate" id="isprivate" value="yes" <?php if($this->isprivate=='yes') echo 'checked'; ?> />&nbsp;Make this file private.</label></th><?php */?>
					</tr>
					<td>&nbsp;<input type="submit" name="submit" id="submit" value="add this file"  style="width:auto;" onclick="return <?php echo $ValidationFunctionName?>(); " /></td>
					<td colspan="2"></td>
					</tr>
					</table>
					</form>
			
					<?php
					
				break;
					
		case 'server':
					//Reading Post File
					extract($_POST);
					$this->title=$title;
					$this->description=$description;
					$this->file_id=$file_id;
					//$this->user_groups=$user_group;
					$this->category=$category;
					if($isprivate!='')
						$this->isprivate = 'yes';
					else
						$this->isprivate = 'no';
					
					if($_FILES['name']['name']!=""){
					$this->objFileUpload->UploadMode         = "Add";
					$this->objFileUpload->IsSaveByRandomName = true;
					$this->objFileUpload->UploadContent =$_FILES['name'];
					$this->objFileUpload->UploadFolder =$this->directory;
					$this->objFileUpload->NeedReturnStatement = true;
					$file=$this->objFileUpload->Upload();
					$file_names=explode("|",$file);
					$this->name=$file_names[1];
				
					}
					$this->title=$title;
					$this->description=$description;
					
					//server side validation
					$return =true;
					if($this->Form->ValidField($category,'empty','Category field is Empty or Invalid')==false)
						$return =false;	
					if($this->Form->ValidField($title,'empty','Title field is Empty or Invalid')==false)
						$return =false;
					
					if($return){
					$insert_sql_array = array();
					$insert_sql_array['name'] = $this->name;
					$insert_sql_array['title'] = $this->title;
					$insert_sql_array['file_id'] = $this->file_id;
					$insert_sql_array['user_id'] = $this->user_id;
					$insert_sql_array['modified'] = $this->modified;
					$insert_sql_array['uploaded'] = $this->uploaded;
					$insert_sql_array['directory'] = $this->directory;
					$insert_sql_array['category'] = $this->category;
					$insert_sql_array['description'] = $this->description;
					$insert_sql_array['group'] = $this->user_group;
					$insert_sql_array['isprivate'] = $this->isprivate;			

					$this->db->insert(TBL_FILE,$insert_sql_array);
					$_SESSION['msg'] = "File Added successfully";
					?>
					<script type="text/javascript">
					window.location = 'file.php';
					</script>
					<?php
					/*$this->file_id=$this->db->last_insert_id();
					
					$insert_sql_array1 = array();
					$insert_sql_array1['file_id'] = $this->file_id;
					$insert_sql_array1['user_group'] = $this->user_group;
					//print_r($insert_sql_array1);
					$this->db->insert(FIlES_USER_CONTROL,$insert_sql_array1);*/
					} else {
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
					$this->AddFile('local');
					}
					break;
		default : echo 'Wrong Paramemter passed';
		}
	}		
	
	
	/*function AddFileCategory($runat)  // $runat=local/server 
	{
		switch($runat){
		
			case 'local':
					//Display Form	
							
					?>			
					<form method="post" action="" enctype="multipart/form-data" name="frm_file_category">
					<div class="Clear">
					<div class="Label">Category Name</div>
					<div class="Field">
					<input type="text" name="category" id="category" />
					<span id="spancategory"></span>
					</div>
					</div>
					<div class="Clear">
					<div class="Field">
					<input type="submit" name="go" value="go" />
					<span id="spango"></span>
					</div>
					</div>
					</form>
					<?php
					
		
			break;
		case 'server':
					//Reading Post CATEGORY
					extract($_POST);
					$this->category=$category;
					$insert_sql_array = array();
					$insert_sql_array['category'] = $this->category;
					$this->db->insert(FILES_CATEGORY,$insert_sql_array);
					break;
					
		
		default : echo 'Wrong Paramemter passed';
		
		}
	}*/
	
	function EditFile($runat, $file_id)
	{
		$this->file_id=$file_id;
		switch($runat){
		
			case 'local':
			
					$sql="select * from ".TBL_FILE." a where a.file_id=$this->file_id ";
					$result=$this->db->query($sql,__FILE__,__LINE__);
					$row=$this->db->fetch_array($result);
						if(count($_POST)>0 and $_POST['submit']=='save this file'){
						  extract($_POST);
						  $this->name = $name;
						  $this->category = $category;
						  $this->title = $title;
						  $this->user_group = $user_group;
						  $this->description = $description;
						  $this->isprivate = $isprivate;
						}
						$FormName = "file";
						$ControlNames=array("name"			=>array('name',"''","*","spanname"),
											"category"			=>array('category',"''","*","spancategory1"),
											"title"			=>array('title',"''","*","spantitle"),
											);

						$ValidationFunctionName="CheckValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
					
			?>		
					<form method="post" action="" enctype="multipart/form-data" name="file">
					<input type="hidden" name="file_id" id="file_id" value="<?php echo $this->file_id ?>" />
					<table class="table">
					<tr>
					<th><?php echo $row[title] ?></th>
					<td><a href="<?php echo 'files/'.$row['name']; ?>" target="_blank">View Current File</a></td>
					<td><span id="spanname"></span></td>
					</tr>
					<tr>
					<th>Who can see this :</th>
					<td>
					  <select name="user_group" id="user_group" >
                        <option value="">All User's</option>
					<?php
					$sql_temp="select * from ".TBL_USERGROUP." order by group_name";
					$result_temp=$this->db->query($sql_temp,__FILE__,__LINE__);
					while($row_temp=$this->db->fetch_array($result_temp)){
					?>
                    <option value="<?php echo $row_temp['group_name'];?>" <?php if($row['user_group']==$row_temp['group_name']) echo 'selected="selected"';  ?>><?php echo $row_temp['group_name']; ?></option>
					 <?php
					 }
					 ?> </select></td>
					  <td><span id="spanuser_group"></span></td>
					</tr>
					<tr>
					<th>Category :</th>
					<td><select name="category" id="category" onchange="if(this.value=='NewCat'){ 
																	category_name = prompt('Enter name of category','');
																	if(category_name!=null){
																	if(category_name.length>0)
																	{
																		filesobj.AddFileCategoryOnFly('server',category_name,
																		{	onUpdate: function(response,root){  
																			if(response==1){
																			filesobj.GetFileCategoryJson({content_type:'json', target:'category', preloader:'prl'});
																			} else {
																			alert('Sorry !! category with name '+category_name+' already exists');
																			document.getElementById('category').options[0].selected = true;
																			document.getElementById('category').selectedIndex=0;
																			return true;
																				}
																			}
																		}
																		);
																	}
																	else{
																	this.options[0].selected = true;
																	this.selectedIndex=0;
																	return true;
																	} }
																	else 
																	{
																	this.options[0].selected = true;
																	this.selectedIndex=0;
																	return true;
																	}
																}">
                        <option value="">select category</option>
                        <?php
						$datasql="select * from ".FILES_CATEGORY." order by category";
						$dataresult=$this->db->query($datasql,__FILE__,__LINE__);
						while($datarow=$this->db->fetch_array($dataresult)){
						?>
                        <option value="<?php echo $datarow['file_category_id'];?>" <?php if($row['category']==$datarow['file_category_id']) echo 'selected="selected"';  ?>><?php echo $datarow['category']; ?></option>
                        <?php
						 }//onclick="document.getElementById('display_cat').style.display=''; filesobj.AddFileCategory_onfly('local', { target:'display_cat', preloader: 'prl'});"
						?>
						<option value="NewCat" >new category</option>
                      </select>&nbsp;<span><a href="fileManagement.php">edit categories</a></span></td>
					<td>  <span id="spancategory1"></span></td>
					<tr>
					<th>Display title :</th>
					<td><input type="text" name="title" id="title" value="<?php echo $row[title]; ?>"></td>
					<td><span id="spantitle"></span></td>
					</tr>
					<tr>
					<th>Description :</th>
					<td><textarea name="description" id="description"><?php echo $row[description]; ?></textarea></td>
					<td><span id="spandescription"></span></td>
					</tr>
					<tr>
					<th></th>
					<th colspan="2"><label><input type="checkbox" name="isprivate" id="isprivate" value="yes" <?php if($row[isprivate]=='yes') echo 'checked'; ?> />&nbsp;Make this file private.</label></th>
					</tr>
					<td>&nbsp;<input type="submit" name="submit" id="submit" value="save this file"  style="width:auto;" onclick="return <?php echo $ValidationFunctionName?>();" />&nbsp;or&nbsp;<a href="file.php">cancel</a></td>
					<td colspan="2"></td>
					</tr>
					</table>
					</form>
			<?php	
			
				break;
				
			case 'server':
					extract($_POST);
					$this->title=$title;
					$this->description=$description;
					$this->file_id=$file_id;
					//$this->user_group=$user_group;
					$this->category=$category;
					if($isprivate!='')
						$this->isprivate = 'yes';
					else
						$this->isprivate = 'no';
					//$this->old_file_name=$old_file_name;
					
					
					$return =true;
					if($this->Form->ValidField($category,'empty','Category field is Empty or Invalid')==false)
						$return =false;	
					if($this->Form->ValidField($title,'empty','Title field is Empty or Invalid')==false)
						$return =false;
					
					if($return){
					$update_sql_array = array();
					//$update_sql_array['name'] = $this->name;
					$update_sql_array['title'] = $this->title;
					$update_sql_array['description'] = $this->description;
					$update_sql_array['category'] = $this->category;
					$update_sql_array['isprivate'] = $this->isprivate;
					$update_sql_array['modified'] = time();
					$this->db->update(TBL_FILE,$update_sql_array,"file_id",$this->file_id);
					$_SESSION['msg'] = "File saved successfully !!";
					/*$update_sql_array1 = array();
					$update_sql_array1['user_group'] = $this->user_group;
					$this->db->update(FILES_USER_CONTROL,$update_sql_array1,"file_id",$this->file_id);*/
					?>
					<script type="text/javascript">
					location.replace('file.php');
					</script>
					<?php
					} else {
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
					$this->EditFile('local',$this->file_id);
					}

				break;
				
			}
	
	}
	
	
	/*function EditFileCategory($runat, $file_id)
	{
	
		switch($runat){
		
			case 'local':
			
					$sql="select * from ".FILES_CATEGORY." where file_category_id=$this->file_id";
					$result=$this->db->query($sql,__FILE__,__LINE__);
					$row=$this->db->fetch_array($result);
						?>
					<form method="post" action="" enctype="multipart/form-data" name="frm_file_category">
					<div class="Clear">
					<div class="Label">Choose a category</div>
					<div class="Field">
					<input type="text" name="category" id="category" value="<?php echo $row[name]; ?>" />
					<span id="spancategory"></span>
					</div>
					</div>
					<div class="Clear">
					<div class="Field">
					<input type="submit" name="go" value="Go" />
					<span id="spango"></span>
					</div>
					</div>
					</form>
						
						<?php
				break;
				
			case 'server':
					extract($_POST);
					//$this->cat_id=$cat_id;
					$this->category=$category;
					$update_sql_array = array();
					$update_sql_array['category'] = $this->category;	
					$this->db->update(FILES_CATEGORY,$update_sql_array,"file_id",$this->file_id);
				break;
				}	
	}*/
			
	function AddFileCategory($runat){
		switch($runat){
			case 'local' :
						if(count($_POST)>0 and $_POST['go']=='Add Category'){
						  extract($_POST);
						  $this->category = $category;
						}
						$FormName = "frm_file_category";
						$ControlNames=array("category"			=>array('category',"''","Please enter Category Name","spancategory"));

						$ValidationFunctionName="CheckValidity";
					
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;
						
						?>		
						<form method="post" action="" enctype="multipart/form-data" name="frm_file_category" id="frm_file_category">
						<table><tr>
						<td>Category Name :</td><td> <input type="text" name="category" id="category" /></td>
						<td><span id="spancategory"></span></td></tr>
						<tr><td></td><td colspan="2"><input type="submit" name="go" id="go" value="Add Category" onclick="return <?php echo $ValidationFunctionName?>();" />&nbsp;<input type="button" name="cancel" value="Cancel" onclick="location.replace('fileManagement.php'); return false;" /> </td>
						</tr></table>
						</form>
						<?php
						break;
			case 'server' :
						extract($_POST);
						$this->category=$category;
						//server side validation
						$return =true;
						if($this->Form->ValidField($category,'empty','Category name field is Empty or Invalid')==false)
							$return =false;
						if($return){
						$valid_user = $this->CheckCategoryName($this->category);
						if($valid_user){
						$insert_sql_array = array();
						$insert_sql_array['category'] = $this->category;
						$this->db->insert(FILES_CATEGORY,$insert_sql_array);
						$_SESSION['msg'] = "Category added successfully";
						?>
						<script type="text/javascript">
							window.location = "<?php echo $_SERVER['PHP_SELF']; ?>"
						</script>
						<?php
						}else {
									echo '<div class="errortxt"><li>Sorry !! This Category name already exists.</li></div>'; 
									$this->AddFileCategory('local');							
								}
						} else {
						echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
						$this->AddFileCategory('local');
						}
						
						break;
			default :	echo 'Wrong Paramemter passed';
		}
	}
	
	function EditFileCategory($runat, $file_category_id)
	{
		$this->file_category_id = $file_category_id;
		switch($runat){
		
			case 'local':
			
					if(count($_POST)>0 and $_POST['go']=='Go'){
					  extract($_POST);
					  $this->category = $category;
					}
					$FormName = "frm_file_category";
					$ControlNames=array("category"			=>array('category',"''","Please enter Category Name","spancategory"));
					$ValidationFunctionName="CheckValidity";
					$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
					$sql="select * from ".FILES_CATEGORY." where file_category_id=$this->file_category_id";
					$result=$this->db->query($sql,__FILE__,__LINE__);
					$row=$this->db->fetch_array($result);
						?>
					<form method="post" action="" enctype="multipart/form-data" name="frm_file_category" id="frm_file_category">
					<table><tr>
					<td>Choose a category</td>
					<td><input type="text" name="category" id="category" value="<?php echo $row[category]; ?>" /></td>
					<td><span id="spancategory"></span></td>
					</tr>
					<tr>
					<td></td>
					<td colspan="2"><input type="submit" name="go" id="go" value="Go" onclick="return <?php echo $ValidationFunctionName?>();" />&nbsp;<input type="button" name="cancel" id="cancel" value="Cancel" onclick="location.replace('fileManagement.php'); return false;" /></td>
					</tr></table>
					</form>
						
						<?php
				break;
				
			case 'server':
					extract($_POST);
					//$this->cat_id=$cat_id;
					$this->category=$category;
					//server side validation
					$return =true;
					if($this->Form->ValidField($category,'empty','Category name field is Empty or Invalid')==false)
						$return =false;
					if($return){
					$valid_user = $this->CheckCategoryName($this->category,$this->file_category_id);
					if($valid_user){
					$update_sql_array = array();
					$update_sql_array['category'] = $this->category;	
					$this->db->update(FILES_CATEGORY,$update_sql_array,"file_category_id",$this->file_category_id);
					$_SESSION['msg'] = "Category saved successfully";
					?>
					<script type="text/javascript">
						window.location = "<?php echo $_SERVER['PHP_SELF']; ?>"
					</script>
					<?php
					}else {
								echo '<div class="errortxt"><li>Sorry !! This Category name already exists.</li></div>'; 
								$this->EditFileCategory('local',$this->file_category_id);							
							}
					} else {
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
					$this->EditFileCategory('local',$this->file_category_id);
					}
				break;
		default :	echo 'Wrong Paramemter passed';
				}	
	}
	function CheckCategoryName($category,$file_category_id='')
	{
		$sql="select * from ".FILES_CATEGORY." where category='$category' and file_category_id != '$file_category_id'";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0)
		return false;
		else return true;
	}	
	function GetAllCategory(){
		$sql = "select * from ".FILES_CATEGORY." order by category";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		return $record;
	}	
	
	function ShowAllCategory(){
		$record = $this->GetAllCategory();
		if($this->db->num_rows($record)>0){
		?>	
		<table class="table">
			<tr>
			  <th>Category Name</th>
			  <th>Action</th>
			</tr>
		<?php  while($row = $this->db->fetch_array($record)){ ?>
			<tr>
			  <td><?php echo $row[category]; ?></td>
			  <td><a href="<?php $_SERVER['PHP_SELF']?>?file_category_id=<?php echo $row[file_category_id]; ?>&index=Edit">edit</a>&nbsp;|&nbsp;<a href="<?php $_SERVER['PHP_SELF']?>?file_category_id=<?php echo $row[file_category_id]; ?>&index=Delete" onclick="return confirm('Do You want to delete this category')">Delete</a></td>
			</tr>
		  <?php 
		  }
		?></table><?php
		} else {
			echo "No File Category currently Added";
		}	
	}
	function DeleteCategory($file_category_id){
			$this->file_category_id=$file_category_id;
			$sql="delete from ".FILES_CATEGORY." where file_category_id='$this->file_category_id'";
			$this->db->query($sql,__FILE__,__LINE__);
			$_SESSION['msg'] = "Category has been deleted successfully";
			?>
			<script type="text/javascript">
				window.location = "<?php echo $_SERVER['PHP_SELF']; ?>"
			</script>
			<?php
			exit();
	}

	function AddFileCategoryOnFly($runat,$category='')  // $runat=local/server 
	{
			//ob_start();
		switch($runat){
		
			case 'local':
				
					//Display Form	
					?>		
					<form method="post" action="" enctype="multipart/form-data" name="frm_file_category" id="frm_file_category">
					<div class="Clear">
					Category Name : <input type="text" name="category_onfly" id="category_onfly" />
					<span id="spancategory"></span>
					</div>
					<p>
					<input type="button" name="go" id="go" value="Add Category" onclick=
					"filesobj.AddFileCategory_onfly('server',document.getElementById('category_onfly').value,
					{target: 'display_cat', preloader: 'prl'});" /> 
					or
					 <span class="verysmall_text" onclick="document.getElementById('display_cat').style.display='none'; 
					 filesobj.GetFileCategoryJson({content_type:'json', target:'category', preloader:'prl'});  "> 
					 Close </span>
					</p>
					</form>
					<?php	
			break;
			
		case 'server':
					//Reading Post CATEGORY
					$valid_category = $this->CheckCategory($category);
					if($valid_category){
						$this->category=$category;
						$insert_sql_array = array();
						$insert_sql_array['category'] = $this->category;
						$this->db->insert(FILES_CATEGORY,$insert_sql_array);
						return 1;
					} else {
					return 0;
					}
		
		}
/*					$html = ob_get_contents();
					ob_end_clean();
					return $html;	
*/	}
	
	
	function CheckCategory($category)
	{
		$sql="select * from ".FILES_CATEGORY." where category='$category'";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0)
		return false;
		else return true;
	}	

			
	function getFileOfCategory($file_category_id)
	{
		$sql="select * from ".FILES_CATEGORY." where file_category_id='$this->file_category_id'";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		return $row;
	}
	
	
	function getFileOfUser($user_id)
	{	
		$sql="select * from ".TBL_FILE." where user_id='$this->user_id'";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		return $row;
	}
	
	
	function getAllFiles()
	{
		$sql="select * from ".TBL_FILE." ";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		return $row;
	
	}
	
	
	function displayFileOfCategorySummary($file_category_id='',$type='', $user_id='',$public='')
	{
		ob_start();
		
		$sql="select * from ".FILES_CATEGORY;
		if($file_category_id!='')
		{
		$this->category=$file_category_id;
		$sql.=" where file_category_id='$this->category'" ;
		}
		
		$t_record=$this->db->query($sql,__FILE__,__LINE__);
		while($temp_row=$this->db->fetch_array($t_record))
		{
			$this->category=$temp_row['file_category_id'];

			if( $type=='' and $user_id=='' and $public!='') {
			//$sql="select * from ".TBL_FILE." f , ".FIlES_USER_CONTROL." u where f.category='$this->category' and u.file_id=f.file_id";
			$sql="select * from ".TBL_FILE." f  where f.category='$this->category' and f.isprivate='no'";
			}
			elseif( $type=='' and $user_id=='')
			{
			$sql="select * from ".TBL_FILE." f  where f.category='$this->category' and (f.isprivate='no' or ( f.isprivate='yes' and f.user_id=$this->user_id))";
			}
			else
			{
				if($user_id==''){
					//$sql="select * from ".TBL_FILE." f , ".FIlES_USER_CONTROL." u where f.category='$this->category' and u.file_id=f.file_id and f.isprivate='no'";
					$sql="select * from ".TBL_FILE." f  where f.category='$this->category'";
					}
				else{
					//$sql="select * from ".TBL_FILE." f , ".FIlES_USER_CONTROL." u where f.category='$this->category' and u.file_id=f.file_id and f.user_id=$user_id";
					$sql="select * from ".TBL_FILE." f  where f.category='$this->category' and f.user_id=$user_id";
					}
			}			

			$total_record=$this->db->num_rows($this->db->query($sql,__FILE__,__LINE__));
			
			$sql.=" limit 0,5";
			$record=$this->db->query($sql,__FILE__,__LINE__);
			if($this->db->num_rows($record)>0){
			?>
			<div class="file_manager"><span><?php echo $temp_row['category']; ?></span>
			<?
			}
			while($rows=$this->db->fetch_array($record))
			{ 
			 ?>  
			<span id="filemain<?php echo $rows[file_id];?>">
			<li onmouseover="document.getElementById('task_action_<?php echo $rows[file_id];?>').style.display='';" 
			onmouseout="document.getElementById('task_action_<?php echo $rows[file_id];?>').style.display='none';" >
			<?php /*********************************/ ?>
			<span id="task_action_<?php echo $rows[file_id];?>"  style="display:none; " class="file_action">
		
		<a href="edit_file.php?file_id=<?php echo $rows[file_id]; ?>" 
		onclick="">
		<img src="images/edit.gif" border="0"  align="absmiddle"/></a>&nbsp;
		<a href="javascript:void(0);" onclick="javascript: document.getElementById('div_file').style.display=''; filesobj.sendToUsers('local','<?php echo $rows[file_id] ?>',{preloader:'prl', onUpdate: function(response,root){
		document.getElementById('div_file').innerHTML=response;
		initializeFacebook();
		        

		}})">Send</a>
		<a href="#" 
		onclick="if(confirm('Are you sure ?')){
					filesobj.DeleteFile(<?php echo $rows[file_id]; ?>,'<?php echo $rows[name]; ?>',{target: 'filemain<?php echo $rows[file_id];?>', 	preloader:'prl'});
					} return false;">
		<img src="images/trash.gif" border="0"  align="absmiddle"/>&nbsp;</a>
		</span>
			<?php /*********************************/ ?>
			<p class="task_padding" style="display:inline;"><a href="DocumentInfo.php?width=400&doc_id=<?php echo $rows[file_id]; ?>" class="jTip" id="file_<?php echo $rows['file_id'];?>" name="<?php echo $rows['title']; ?>" 
			onclick="window.location='<?php echo $rows['directory'].'/'.$rows['name']; ?>';return false;" target="_blank"><?php echo $rows['title']; ?></a>&nbsp;&nbsp;
			
			<img class="tags_thumb" alt="" src="images/tag_icon.png"  align="absmiddle"/>
			<span class="verysmall_text">
			<ul  style="display:inline" class="link_list">
			<span id="alltags<?php echo $rows[file_id]; ?>">
			<?php echo $this->ShowTags('', 'TBL_FILE', $rows[file_id]); ?>
			</span>
			<li id="edit_link_<?php echo $rows[file_id];?>"> - <a id="<?php echo $rows[file_id];?>" class="verysmall_text"  href="#"
			onclick="javascript: document.getElementById('edit_link_<?php echo $rows[file_id];?>').style.display='none'; 
			filesobj.TagModule_id('local',
								'filesobj',
								'TBL_FILE',
								 <?php echo $rows[file_id]; ?>,
								 '','',
								 'alltags<?php echo $rows[file_id]; ?>',
								 'alltags<?php echo $rows[file_id]; ?>',
								 'document.getElementById(\'edit_link_<?php echo $rows[file_id];?>\').style.display=\'\';',
								 {target: 'alltags<?php echo $rows[file_id]; ?>', preloader: 'prl'}
								);" style="color:#666666;">
			Edit tags</a>
		</li>
			</ul>
			</span>
			</li></span>
			</p>	
			<br />
			<?php
			}
			if($this->db->num_rows($record)>0){
			?>
			<li><a onclick="filesobj.DisplayAllOfCategory(<?php echo $this->category;?>, {target: 'file_area', preloader:'prl'}); return false;"><?php  if(($total_record - 5)>0) echo '(See '.($total_record - 5).' more )'; ?></a></li>
			</div>
			<?
			}

		}	
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function sendToUsers($runat,$file_id,$to='',$subject='',$msg='')
	{
		ob_start();
		
		$this->file_id = $file_id;
		switch($runat){
			case 'local':
				$formName = 'frm_sendToUsers';
			  $ControlNames=array("select2"			=>array('select2',"''","please enter at least a contact.","span_to_send_message")
								);

			  $ValidationFunctionName="Validator_send_message";
		
			  $JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($formName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
			  echo $JsCodeForFormValidation;
				?>
				<div class="prl">&nbsp;</div>
						<div id="lightbox">
							<div style="background-color:#ADC2EB;" align="left" class="ajax_heading">
							<div id="TB_ajaxWindowTitle">Send Document</div>
							<div id="TB_closeAjaxWindow"><a href="javascript:void(0)" onclick="javascript: document.getElementById('div_file').style.display='none';"><img border="0" src="images/close.gif" alt="close" /></a></div>
						</div>
						<div  class="white_content"> 
						<div style="padding:20px;"  class="form_main">
						<form action="" name="<?php echo $formName; ?>" enctype="multipart/form-data" method="post">
						<table width="100%" class="table">
						  		<tr>
									<td>
										  <ul>
											<li><span id="span_to_send_message"></span></li>
										  </ul>									 </td>
							  </tr>
						  <tr>
							<td><h2>Select Contact:</h2></td>
						  </tr>
						  <tr>
							<td><select id="select2" name="select2">
      							</select>
	  						</td>
							
						  </tr>
						  <tr>
							<td><h2>Subject:</h2></td>
						  </tr>
						  <tr>
							<td><input type="text" name="subject" id="subject" style="width:90%"/></td>
						  </tr>
						  <tr>
							<td><h2>Message:</h2></td>
						  </tr>
						  <tr>
							<td><textarea name="msg" id="msg" style="width:90%"></textarea></td>
						  </tr>
						  <tr>
							<td align="right"><input type="button" name="go" id="go" value="Go" onclick="javascript: to=''; for(i=0; i<document.getElementById('select2').length; i++){ if(document.getElementById('select2')[i].selected==true) { to += document.getElementById('select2')[i].value+','; } } to=to.substr(0,to.length-1); if(document.getElementById('select2').length>0){ filesobj.sendToUsers('server','<?php echo $this->file_id ?>',to,this.form.subject.value,this.form.msg.value,{preloader:'prl',onUpdate: function(response,root){ 	
							
							resp = response.split('|');
							res_not = resp[0].split('^');
							res_sent = resp[1].split('^');
							mssg='';
							if(res_not[0]!=''){
							mssg += 'Document not sent to: ';
							for(i=0;i<res_not.length;i++)
								mssg += res_not[i]+', ';
							}
							if(res_sent[0]!=''){
							mssg += 'Document sent to: ';
							for(i=0;i<res_sent.length;i++)
								mssg += res_sent[i]+', ';
							}
							alert(mssg);
							document.getElementById('div_file').innerHTML='';
							}}); } else { alert('please enter at least a contact'); } return false;" style="width:auto" /></td>
						  </tr>
						</table>
						</form>
						</div></div></div>
				<?php
				break;
			case 'server' :
				$sql_file = "select * from ".TBL_FILE." where file_id='$this->file_id'";			
				$record_file=$this->db->query($sql_file,__FILE__,__LINE__);
				$rows_file=$this->db->fetch_array($record_file);
				$path = $rows_file[directory].'/'.$rows_file[name];
				$contact_id = explode(',',$to);
				$x=0;
				$y=0;
				for($i=0 ; $i<count($contact_id) ; $i++){
					$sql = "select * from ".CONTACT_EMAIL." a,".TBL_CONTACT." b where b.contact_id='$contact_id[$i]' and a.contact_id=b.contact_id";
					$record=$this->db->query($sql,__FILE__,__LINE__);
					if($this->db->num_rows($record)>0){					
						$this->mail_obj->ClearAddresses();
						$this->mail_obj->ClearAttachments();
						$this->mail_obj->IsHTML(true);  
						$this->mail_obj->From = "admin@cmd.com";
						$this->mail_obj->FromName = "No Reply";
						while($rows=$this->db->fetch_array($record)){
							$this->mail_obj->AddAddress("$rows[email]");
							$contact_name = $rows[first_name].' '.$rows[last_name];
						}
						$this->mail_obj->Subject = $subject;
						$this->mail_obj->Body = $msg;
						$this->mail_obj->WordWrap = 50;
						$this->mail_obj->AddAttachment($path);
						$res = $this->mail_obj->Send();
						if(!$res){
							$this->not_sent_cont_name[$x++] .= $contact_name.' ';
						}
						else {
							$this->sent_cont_name[$y++] .= $contact_name.' ';
						}
					} else {
						$sql_cont = "select * from ".TBL_CONTACT." where contact_id='$contact_id[$i]'";			
						$record_cont=$this->db->query($sql_cont,__FILE__,__LINE__);
						$rows_cont=$this->db->fetch_array($record_cont);
						$this->not_sent_cont_name[$x++] .= $rows_cont[first_name].' '.$rows_cont[last_name].' ';						
					}
				}
				$temp1 = implode('^',$this->not_sent_cont_name);
				$temp2 = implode('^',$this->sent_cont_name);
				$temp = $temp1.'|'.$temp2;
				return $temp;
		}
		
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	/*function getUsers()
	{
		$sql = "select * from ".TBL_CONTACT." where type='People' ";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		$n = 1;
		$x=0;
		$email_temp= array();
		?>
		<table width="100%" class="table">
		<tr>
		<?php
		while($rows=$this->db->fetch_array($record)){
			?>
			<td>
			<input type="checkbox" style="width:auto" name="contact[]" id="contact" value="<?php echo $rows[contact_id]; ?>" /><?php echo $rows[first_name].' '.$rows[last_name]; ?></td>
			<?php
			$n++;
			if($n==6){
				$n=1;
				echo "</tr><tr>";
			}
		}
		?></tr></table><?php
	}*/
	
	
	function displayFileOfUser($user_id)
	{	
	$sql="select * from ".TBL_FILE." where user_id='$user_id'";
	$record=$this->db->query($sql,__FILE__,__LINE__);
		?>

		<?php
		while($rows=$this->db->fetch_array($record))
		{
		 ?>  
		<li><a href="<?php echo $rows['directory'].'/'.$rows['name']; ?>"><?php echo $rows['title']; ?></a></li>
	<?
		}	
	
	}
	
	function DisplayAllOfCategory($file_category_id='')
	{
		ob_start();
		if($file_category_id=='')
		echo $this->displayFileOfCategorySummary();
		else
		{
		$sql="select * from ".FILES_CATEGORY;
		if($file_category_id!='')
		{
		$this->category=$file_category_id;
		$sql.=" where file_category_id='$this->category'" ;
		}
		$record=$this->db->query($sql,__FILE__,__LINE__);
		$temp_row=$this->db->fetch_array($record);
		?>
		<div class="file_manager"><span><?php echo $temp_row['category']; ?></span>
		<?php
	$sql="select * from ".TBL_FILE." f  where f.category='$this->category' and (f.isprivate='no' or ( f.isprivate='yes' and f.user_id=$this->user_id))";		
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0){
		while($rows=$this->db->fetch_array($record))
		{ 
		 ?>  
			<span id="filemain<?php echo $rows[file_id];?>">
			<li onmouseover="document.getElementById('task_action_<?php echo $rows[file_id];?>').style.display='';" 
			onmouseout="document.getElementById('task_action_<?php echo $rows[file_id];?>').style.display='none';" >
			<?php /*********************************/ ?>
			<span id="task_action_<?php echo $rows[file_id];?>"  style="display:none; " class="file_action">
		
		<a href="edit_file.php?file_id=<?php echo $rows[file_id]; ?>" 
		onclick="">
		<img src="images/edit.gif" border="0"  align="absmiddle"/></a>&nbsp;
		<a href="#" 
		onclick="if(confirm('Are you sure ?')){
					filesobj.DeleteFile(<?php echo $rows[file_id]; ?>,'<?php echo $rows[name]; ?>',{target: 'filemain<?php echo $rows[file_id];?>', 	preloader:'prl'});
					} return false;">
		<img src="images/trash.gif" border="0"  align="absmiddle"/>&nbsp;</a>
		</span>
			<?php /*********************************/ ?>
			<p class="task_padding" style="display:inline;"><a href="DocumentInfo.php?width=400&doc_id=<?php echo $rows[file_id]; ?>" class="jTip" id="file_<?php echo $rows['file_id'];?>" name="<?php echo $rows['title']; ?>" 
			onclick="window.location='<?php echo $rows['directory'].'/'.$rows['name']; ?>';return false;" target="_blank"><?php echo $rows['title']; ?></a>&nbsp;&nbsp;
			
			<img class="tags_thumb" alt="" src="images/tag_icon.png"  align="absmiddle"/>
			<span class="verysmall_text">
			<ul  style="display:inline" class="link_list">
			<span id="alltags<?php echo $rows[file_id]; ?>">
			<?php echo $this->ShowTags('', 'TBL_FILE', $rows[file_id]); ?>
			</span>
			<li id="edit_link_<?php echo $rows[file_id];?>"> - <a id="<?php echo $rows[file_id];?>" class="verysmall_text"  href="#"
			onclick="javascript: document.getElementById('edit_link_<?php echo $rows[file_id];?>').style.display='none'; 
			filesobj.TagModule_id('local',
								'filesobj',
								'TBL_FILE',
								 <?php echo $rows[file_id]; ?>,
								 '','',
								 'alltags<?php echo $rows[file_id]; ?>',
								 'alltags<?php echo $rows[file_id]; ?>',
								 'document.getElementById(\'edit_link_<?php echo $rows[file_id];?>\').style.display=\'\';',
								 {target: 'alltags<?php echo $rows[file_id]; ?>', preloader: 'prl'}
								);" style="color:#666666;">
			Edit tags</a>
		</li>
			</ul>
			</span>
			</li></span>
			</p>	
			<br />
			<?php
			
		}
		} else {
			echo "<li><span class='verysmall_text'>No files in this category</span></li>";
		}
		?></div>
		<?php	
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;

	}
	//onclick="javascript: filesobj.displayFileOfCategorySummary('','public', {target: 'file_area', preloader:'prl', eval_scripts: true});return false;"
  ///////displayFileHeadnew()
	function displayFileHead()///////////////new function display
	{
		?>
		<div align="right" class="head"><a href="" onclick="javascript: filesobj.displayFileOfCategorySummary('','','', 'yes',
											{ 	onUpdate: function(response,root){ 
												document.getElementById('file_area').innerHTML=response;
												//$(document).ready(JT_init);
											}, preloader: 'prl'
										} );return false; ">
		Public Files</a> | 
		<a href="#" onclick="javascript: filesobj.displayFileOfCategorySummary('','','<?php echo $this->user_id; ?>',
													{ 	onUpdate: function(response,root){ 
												document.getElementById('file_area').innerHTML=response;
												//$(document).ready(JT_init);
											}, preloader: 'prl'
										} );return false; ">My files</a></div>
			<div class="head">File Manger</div>
			<div class="head">Show files in category: 
					<select name="category_head" id="category_head"  onchange="filesobj.DisplayAllOfCategory(this.value, {target: 'file_area', preloader:'prl'});">
					<option value="">select category</option>
					<?php
					$sql="select * from ".FILES_CATEGORY." order by category";
					$result=$this->db->query($sql,__FILE__,__LINE__);
					while($row=$this->db->fetch_array($result)){
					?>
                    <option value="<?php echo $row['file_category_id'];?>"><?php echo  $row['category']; ?></option>
					 <?php
					 }
					 ?>
                    </select>
					<div id="prl" style="visibility:hidden;"><img  id="prl_image" src="images/spinner.gif"  /></div>
	    </div>

		<?php
	}
	
	function DocumentInfo($doc_id)
	{
		
		//$sql="select * from ".TBL_FILE." a , ".FIlES_USER_CONTROL." b , ".TBL_USER." c where  a.file_id=b.file_id and a.file_id=$doc_id and c.user_id=a.user_id";
		
		$sql="select * from ".TBL_FILE." a , ".TBL_USER." c where a.file_id=$doc_id and c.user_id=a.user_id";
		
		$record=$this->db->query($sql,__FILE__,__LINE__);
		$rows=$this->db->fetch_array($record);
		 ?>
		 <ul> 
		 <li> <?php echo $rows['description']; ?></li>
		 <li> Uploaded by <?php echo $rows[first_name].' '.$rows[last_name].'('.$rows[user_name].')';?> </li>
		 <li> Created on <?php echo date("d/m/y : H:i:s", $rows['uploaded']); ?></li>
		 <li> Last modified on <?php echo date("d/m/y : H:i:s", $rows['modified']); ?></li>
		 <?php /*?><li> User's in '<?php echo $rows['user_group']; ?>' Group can view the doucment. </li><?php */?>
		 </ul>
		 <?php
	}
	
	
	function GetFileCategoryJson()
	{
		ob_start();
		//$options[0] = array("value" => "", "text" => "Select Category");		
		$sql="select * from ".FILES_CATEGORY." order by category";
		$result=$this->db->query($sql,__FILE__,__LINE__);
		echo '[{"value":"","text":"select category"},';
		while($row=$this->db->fetch_array($result)){
		 echo '{"value":"'.$row["file_category_id"].'","text":"'.$row["category"].'"},';
		 //$options[] = array("value" => $row["file_category_id"], "text" => $row["category"]); 
		 }
		 echo '{"value":"NewCat","text":"new category"}]';
		//return $options;
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function DisplayFile($module='', $module_id='')
	{
		ob_start();
			$sql="select * from ".TBL_FILE." where file_id='$module_id'";
			$record=$this->db->query($sql,__FILE__,__LINE__);
			if($this->db->num_rows($record)>0)
			{
			$rows=$this->db->fetch_array($record);
			?> 
			<div class="contact_match file_manager">
			<div class="Clear"> 
			<a href="DocumentInfo.php?width=400&doc_id=<?php echo $module_id; ?>" class="jTip" id="file_<?php echo $module_id;?>" name="<?php echo $rows['title']; ?>" 
			onclick="window.location='file.php';return false;"><span class="heading bcolor"><?php echo $rows['title']; ?></span></a>&nbsp;&nbsp;
			<img class="tags_thumb" alt="" src="images/tag_icon.png"  align="absmiddle"/>
			<span class="verysmall_text">
			<ul  style="display:inline" class="link_list">
			<span id="alltags<?php echo $rows[file_id]; ?>">
			<?php echo $this->ShowTags('', $module, $module_id, ''); ?>
			</span>
			</ul>
			</div>
			</div>
			<?php
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function DeleteFile($file_id,$name){
		$this->file_id=$file_id;
		$this->name=$name;
		$sql="delete from ".TBL_FILE." where file_id='$this->file_id'";
		$this->db->query($sql,__FILE__,__LINE__);
		$sql="delete from ".TAGS_DATA." where module='TBL_FILE' and module_id='$this->file_id'";
		$this->db->query($sql,__FILE__,__LINE__);
		unlink('files/'.$this->name);
		return '';
	}
}	
?>