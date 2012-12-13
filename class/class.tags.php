<?php
 /***********************************************************************************

Class Discription : Tags
					Tags are simple one or two word descriptions tied to a specific entry of one of the modules
					such as a date, a contact or a file.
					Some examples of a tag for a contact might be accountant, prospect, ceo
					Some examples of a tag for a date could be staff,shareholders
					Each entry can be tagged with any number of tags. Tags also need to be able to be created
					on the fly.
					Using tags give you the ability to click on accountant and get any piece of information that
					happens to be tagged as accountant whether that is a contact, a file, a date, or whatever.
					The only way to implement tags over multiple tables is to use the table name, module and
					the table_id of that module, module_id.

Class Memeber Functions :AddTag($runat)
                         EditTag($runat,$tag_id)
						 AddTagOnFly($tag_name)
						 GetTagInfo($tag_id)
						 ApplyAnExistingTag($module, $module_id, $tag_id)
						 GetAllTags()
						 GetALLTagsAtoZ()
						 RemoveAnExistingTag($object, $module, $module_id, $tag_id,$target)
						 ShowTags($object='', $module, $module_id, $target='', $removelink='')
						 TagModule_id($runat, $object, $module, $module_id, $tag_name, $tag_id , $target ,$alltag ,$editlinkjs)
						 GetModule_id_inTag($tag_id, $contactobj='', $taskobj='', $fileobj='',$page='')


Describe Function of Each Memeber function:
					1. AddTag($runat) // $runat=local/server
						Add name in the database in tags_name table

					2. function  EditTag($runat,$tag_id)// $runat=local/server,,$tag_id=tag_id of the tag which is uniqe
						Edit name in the database in tags_name table

					3. function AddTagOnFly($tag_name)// $runat=local/server,,$tag_name=tag_name of the tag which is uniqe
						Add name in the database in tags_name table

					3. function GetTagInfo($tag_id)// $runat=local/server,,$tag_id=tag_id of the tag which is uniqe
						Get name in the database in tags_name table

					4. function ApplyAnExistingTag($module, $module_id, $tag_id) //
						Applies an existion tag. Save the tag in the database table.

					6. function GetAllTags()
						Display all tags saved in database.

					7. function GetALLTagsAtoZ()
						Displays all tags in alphabedical order.

					8. function RemoveAnExistingTag($object, $module, $module_id, $tag_id,$target)
						this function removes the existing tag from the database. the tag_id, module' and module_id is passed to delete
						the existing tag from the database table.

					9. function ShowTags($object='', $module, $module_id, $target='', $removelink='')
						displays the selected tag. When the tag is selected the tag_id, module' and module_id is passed to show the
						details of the tag.

					10. function TagModule_id($runat, $object, $module, $module_id, $tag_name, $tag_id , $target ,$alltag ,$editlinkjs)

					11. function GetModule_id_inTag($tag_id, $contactobj='', $taskobj='', $fileobj='',$page='')




************************************************************************************/
class Tags
{
	var $tag_id;
	var $name;
	var $db;
	var $validity;
	var $Form;

	 function __construct()
	 {
	    $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		$this->validity = new ClsJSFormValidation();
		$this->Form = new ValidateForm();
	}

	function AddTags($runat)
	{
		switch($runat){

		case 'local':
					//Display Form

					?>
					<form action="" enctype="multipart/form-data" method="post">
					<div class="Clear">

					<div class="Label">Name</div>
					<div class="Field">
					<input type="text" name="name" id="name" />
					<span id="spanname"></span></div>
					</div>
					<div class="Clear">
					<div class="Field">
					  <input type="submit" name="submit" id="submit" value="Submit" />
					</div>
					  </div>
					</form>
					<?php


			break;
			case 'server':
					//Reading Post Date
					extract($_POST);
					$this->name=$name;
					$insert_sql_array = array();
					$insert_sql_array['name'] = $this->name;
					$this->db->insert(TAGS,$insert_sql_array);
			break;
		default : echo 'Wrong Paramemter passed';
		}

	}

	function EditTags( $runat, $tag_id)
	{
		$this->tag_id=$tag_id;
		switch($runat){

		case 'local':
					$sql="select * from tags_name where tag_id='$this->tag_id'";
					$record=$this->db->query($sql,__FILE__,__LINE__);
					$row=$this->db->fetch_array($record);

					?>
					<form action="" enctype="multipart/form-data" method="post">
					<div class="Clear">

					<div class="Label">Name</div>
					<div class="Field">
					<input type="text" name="name" id="name" value="<?php echo $row['name']; ?>"/>
					<span id="spanname"></span></div>
					</div>
					<div class="Clear">
					<div class="Field">
					  <input type="submit" name="save" id="save" value="Save" />
					</div>
					  </div>
					</form>

					<?php


			break;
			case 'server':
					//Reading Post Date
					extract($_POST);
					$this->name=$name;
					$update_sql_array = array();
					$update_sql_array ['name'] = $this->name;
					$this->db->update(TAGS,$update_sql_array,"tag_id",$this->tag_id);
		    break;
		default : echo 'Wrong Paramemter passed';
		}

	}
	/* function DeleteTag($tag_id)
	{

		$sql="delete * from TBL_TAGS where tag_id='$this->tag_id'";

		$this->db->query($sql,__FILE__,__LINE__);
	}*/
	 function AddTagOnFly($tag_name)
	{
		$sql="select * from ".TAGS." where name='$tag_name'";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)==0) {
		$this->name=$tag_name;
		$insert_sql_array = array();
		$insert_sql_array['name'] = $this->name;
		$this->db->insert(TAGS,$insert_sql_array);
		return $this->db->last_insert_id();
		}
		else
		{
		$row=$this->db->fetch_array($record);
		return	$row['tag_id'];
		}
	}

	function GetTagInfo($tag_id)
	{

		$sql="select * from ".TAGS_DATA. " where tag_id='$this->tag_id'";

		return $this->db->query($sql,__FILE__,__LINE__);

	}

	function Get_Tag_Name($tag_id){

		$sql="select * from ".TAGS. " where tag_id='$tag_id'";
		$record = $this->db->query($sql,__FILE__,__LINE__);
		$row = $this->db->fetch_array($record);
		return $row[name];
	}

	function ApplyAnExistingTag($module, $module_id, $tag_id)
	{
		$this->tag_id=$tag_id;
		$insert_sql_array = array();
		$insert_sql_array['module'] = $module;
		$insert_sql_array['module_id'] = $module_id;
		$insert_sql_array['tag_id'] = $this->tag_id;
		$this->db->insert(TAGS_DATA,$insert_sql_array);

	}


	function GetAllTags()
	{
		ob_start();
		$sql="select * from ".TAGS." order by name";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		while($row=$this->db->fetch_array($record))
		{
		?>
		<option value="<?php echo $row[tag_id]; ?>" ><?php echo $row[name]; ?></option>
		<?
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	function GetALLTagsAtoZ()
	{
		ob_start();
		?>
		<div class="lefthead"><img src="images/icon.gif"  /> Tags</div>
		<?php
		$AtoZ=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		foreach($AtoZ as $char) {
		$sql="select * from ".TAGS." where name like '$char%' order by name";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0) {
		?> <div class="rowborder"> <span><?php echo $char; ?>&nbsp;</span>
		<?php
			$i=0;
			while($row=$this->db->fetch_array($record))	{
				if($i!=0) echo ", ";
				?>
				<span>
				<a href="tag.php?tag_id=<?php echo $row[tag_id]; ?>"><?php echo $row[name]; ?></a>
				</span>
				<?
				$i++;
			}
		?>
		</div>
		<?php
		}
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}


	function RemoveAnExistingTag($object, $module, $module_id, $tag_id,$target)
	{
		ob_start();
		$sql="delete from ".TAGS_DATA." where tag_id=$tag_id and module='$module' and module_id='$module_id'";
		$this->db->query($sql,__FILE__,__LINE__);
		echo $this->ShowTags($object, $module,$module_id,$target,'yes');
		$html = ob_get_contents();
		ob_end_clean();
		return $html;

	}

	function ShowTags($object='contact', $module, $module_id, $target='', $removelink='')
	{
		ob_start();
		?>
		<?php
		$sql=" select * from ".TAGS." a ,".TAGS_DATA." b where b.module='$module' and b.module_id=$module_id and  a.tag_id=b.tag_id ";
		$result=$this->db->query($sql,__FILE__,__LINE__);
		$i=0;
		while($row=$this->db->fetch_array($result))
		{
			if($i!=0) echo ", </li>";
			?>
			<li><a href="tag.php?tag_id=<?php echo $row['tag_id']; ?>" ><?php echo $row['name'] ?></a>
			<?php
			if($removelink=='yes'){
			?>
			<img onClick="if(confirm('are you sure ?')) {<?php echo $object; ?>.RemoveAnExistingTag('<?php echo $object; ?>','<?php echo $module;?>', <?php echo $module_id; ?>,
			<?php echo $row['tag_id']; ?>,'<?php echo $target; ?>', {target: '<?php echo $target; ?>', preloader: 'prl'}); } else { return false; }" src="images/trash.gif"  border="0"/>
			<?php
			}
			$i++;
		}
		?>
		<li>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}


	function TagModule_id($runat, $object, $module, $module_id, $tag_name, $tag_id , $target ,$alltag ,$editlinkjs)
	{
		//todo : later add feture to delete previously applied tags
		ob_start();

		switch($runat){

		case 'local':

					$FormName='frm_tag';

					$ControlNames=array("tag"			=>array('tag',"Tag","please select a tag or type a tag name","span_frm_tag",'tag_id'));

					$ValidationFunctionName="CheckValidity";
						$JsCodeForFormValidation=$this->validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
						echo $JsCodeForFormValidation;		?>
					<span id='current_tags'>
					<?php 	echo $this->ShowTags($object, $module,$module_id, 'current_tags','yes'); ?></span>

			<form method="post" action="" enctype="multipart/form-data" name="frm_tag" id="frm_tag">
			<table class="table" >
					<tr><td colspan="2">
					<ul>
					<li><span id="span_frm_tag" class="normal"></span></li>
					</ul>
					</td></tr>

			<tr>
				<th>select tag:</th>
				<td><select name="tag_id" id="tag_id" style="width:100%" >
				<option value="">---select--</option>
				<?php echo $this->GetAllTags(); ?>
				</select><span id="spantag_id"></span>
				</td>
			</tr>
			<tr>
				<th>or type tag:</th>
				<td><input type="text" name="tag" id="tag" /><span id="spantag"></span></td>
			</tr>
			<tr><td colspan="2" align="right"><input type="button" name="Add" id="Add" value="add tag"  style=" width:auto;"
			onclick="if(<?php echo $ValidationFunctionName; ?>()){ <?php echo $object; ?>.TagModule_id(
														'server',
														'<?php echo $object; ?>',
														'<?php echo $module; ?>',
														<?php echo $module_id; ?>,
														this.form.tag.value,
														this.form.tag_id.value,
														'<?php echo $target; ?>',
														'<?php echo $alltag; ?>',
														'<?php echo addslashes($editlinkjs); ?>',
														{target: '<?php echo $target; ?>', preloader: 'prl'}
														); }" />

										or <a href="#" onclick="

										<?php
										 echo stripslashes($editlinkjs);
										 echo $object; ?>.ShowTags('<?php echo $object; ?>',
										 '<?php echo $module; ?>',
										 <?php echo $module_id; ?>,
										  '',
										  {target: '<?php echo $alltag; ?>', preloader: 'prl'}); return false;">Close</a>
					</td>
				</tr>
			</table>
			</form>
		<?php
				break;
		case 'server' :

				$return =true;
				if(($this->Form->ValidField($tag_name,'empty','Tag field is Empty or Tag is not selected')==false) and ($this->Form->ValidField($tag_id,'empty','')==false))
					$return =false;

				if($return){
				if($tag_name!='')
				$tag_id=$this->AddTagOnFly($tag_name);

				$this->ApplyAnExistingTag($module,$module_id,$tag_id);
				} else {
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix;
				}
				echo $this->TagModule_id('local',$object, $module, $module_id, $tag_name, $tag_id , $target ,$alltag, $editlinkjs);

			break;
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;

	}


	function GetModule_id_inTag($tag_id, $contactobj='', $taskobj='', $fileobj='',$page='')
	{
		$sql="select * from ".TAGS_DATA." where tag_id=$tag_id order by module ";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0){
		while($row=$this->db->fetch_array($record))
		{
			switch($row[module])
			{
			case 'TBL_CONTACT':
			if(	$page->auth->checkPermessionView('TBL_CONTACT',$row[module_id])>0 or $page->auth->isOwner(TBL_CONTACT," and contact_id='$row[module_id]'")>0)
			echo $contactobj->DisplayContact('TBL_CONTACT',$row[module_id]);
			break;
			case 'TASKS':
			echo $taskobj->GetTask_info('', '', '', '', '', '', $row[module_id] );
			break;
			case 'TBL_FILE':
			echo $fileobj->DisplayFile('TBL_FILE', $row[module_id] );
			break;
			}
		}
		} else {
			echo "no item to display";
		}
	}


}
?>	