<?php
/***********************************************************************************

			Class Discription : News
								This Module helps in Adding new News to the database.
								User can Add news edit or modify news by selecting a specific news.
								User can also delete the news from the data base.
			
			Class Memeber Functions : SetUserID($user_id)
									  GetUserID()
									  AddNews($runat)
									  Get_Recent_News($limit)
									  Read_News($news_id,$ajax=false)
									  Show_All_News()
									  Edit_News($runat,$news_id,$title='',$content='')
									  delete_news($news_id)
			
			
			Describe Function of Each Memeber function :
			
									  1. function SetUserID($user_id) // $user_id= User ID of the User
									  	   Set The value of User ID
									  
									  2. function GetUserID()
									       Get the value of User ID 
									  
									  3. function AddNews($runat) // $runat=local/server
									  	   Add News to the DataBase in news Table 
									  
									  4. function Get_Recent_News($limit) //$limit= Specifies the limit of the news Items
									  	   User can get the news from the database table news. 
										   user can get selected number of news by passing the limit.  
									  
									  5. function Read_News($news_id,$ajax=false)
									   	   reads the News from table NEWS on the basis of $news_id and $user_id
									  
									  6. function Show_All_News()
									  	   Shows all the News from the table NEWS order by timestamp
									  
									  7. function Edit_News($runat,$news_id,$title='',$content='') // $runat=local/server  $news_id= ID of the news selected                                                                                                      $title= Title of the news (optional) $content= Content                                                                                                      of the news (optional)
									  	   User can edit the selected News. The news_id of the selected news is passed and User can make changes.
									  
									  
									  8. function delete_news($news_id)  // $news_id= ID of the news selected
									  	   User can Delete the news from the database news selected by $news_id  
						



************************************************************************************/
class News // Basic class for contact 
{
var $news_id;
var $user_id ;
var $title;
var $content;
var	$db;
var $limit;
var $auth;
var $Form;
var $Validity;
	
	function __construct(){
	$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	$this->Validity=new ClsJSFormValidation();
	$this->Form=new ValidateForm();
	$this->auth=new Authentication();
	}
	
	function SetUserID($user_id)
	{
		$this->user_id=$user_id;
	}
	
	function GetUserID()
	{
		return $this->user_id;
	}

	/////Methods///////
	//Add news
	function AddNews($runat)
	{	
	
		if($this->auth->isAdmin()){
		switch($runat){
		case 'local':
					if(count($_POST)>0 ){
					extract($_POST);
					$this->title=$title;
					$this->content=$content;
					}
					//create client side validation
					$FormName='frm_news';
										
					$ControlNames=array("title"			=>array('title',"","Please enter Title","spantitle_frm_news"),
										"content"			=>array('content',"","Please enter Content","spancontent_frm_news"),
									);
					
					$ValidationFunctionName="frm_news_CheckValidity";
					
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;
					
					//display form
					?>
					<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName; ?>">
					<table width="100%" class="news news_t">
					<tr><td colspan="3"><div class="heading">New News Articles</div></td></tr>
					<tr>
						<th width="11%">Title:</th>
					  <td width="67%"><input type="text" name="title" id="title" value="<?php echo $this->title;?>"></td>
						<td width="22%"><span id="spantitle_frm_news"></span></td>
					</tr>
					<tr>
						<th>Message:</th>
						<td><textarea name="content" id="content" cols="50" rows="10" ><?php echo $this->content;?></textarea></td>
						<td><span id="spancontent_frm_news"></span></td>
					</tr>
					<tr>
					<td>&nbsp;</td>
					<td align="right"><input type="submit" name="submit" id="submit" style="width:auto" value="add news"  onclick="this.form.content.value=getText('content'); return <?php echo $ValidationFunctionName?>();"></td>
					<td>&nbsp;</td>
					</tr>
					</table>
					</form>
					<?php
					
				break;
					
		case 'server':
					//Reading Post Date
					extract($_POST);
					$this->title=$title;
					$this->content=$content;
					$return =true;
					if($this->Form->ValidField($title,'empty','Please Enter News title')==false)
					$return =false;
					if($this->Form->ValidField($content,'empty','Please Enter News Contents')==false)
					$return =false;
					if($return) {
					$insert_sql_array = array();
					$insert_sql_array['title'] = $this->title;
					$insert_sql_array['content'] = $this->content;
					$insert_sql_array['user_id'] = $this->user_id;
					$this->db->insert(NEWS,$insert_sql_array,false,'p|strong|em|span|ol|li|ul|img|a',
									'script|style|noframes|select|option|html|head|meta|title|body|input|textarea|link|h1|form|table|tr|tbody|td|th|div');
					$_SESSION['msg']='News Article created successfully ';
					?>
					<script type="text/javascript">
					window.location="<?php echo $_SERVER['PHP_SELF'];?>";
					</script>
					<?php
					}
					else
					{
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
					$this->AddNews('local');
					}
					break;
		default : echo 'Wrong Paramemter passed';
		}
		}
	}
	/**************************************************************************************************************/
	//Get recent news
	function Get_Recent_News($limit)
	{
		$this->limit=$limit;
		$sql="select a.user_id,a.news_id, a.title,a.content, UNIX_TIMESTAMP(a.timestamp) as timestamp ,b.first_name,b.last_name,b.user_id,b.user_name from ".NEWS." a , ".TBL_USER." b where a.user_id=b.user_id order by a.timestamp Desc limit 0,$this->limit";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		if($this->db->num_rows($record)>0){
		while($row=$this->db->fetch_array($record)){
		?>
				<li class="news_icon">
					<span class="message_title">
						<a href="read_news.php?id=<?php echo $row['news_id']; ?>">
							<?php echo 'From '.$row[first_name].' '.$row[last_name].' on '.date('d/m/Y',$row[timestamp]); ?>
						</a>
					</span>
					</span><br />
					<span class="message_summary">
						<?php echo substr($row['title'],0,50).'...';?>
					</span>
				</li>
		<?php
		}
		} else {
			?> <li> <?php echo "<span >none to display</span>"; ?></li><?php
		}
	} 
	/**************************************************************************************************************/
	function Read_News($news_id,$ajax=false)
	{	
		ob_start();
		$this->news_id=$news_id;
		$sql="select a.user_id,a.news_id, a.title,a.content, UNIX_TIMESTAMP(a.timestamp) as timestamp ,b.first_name,b.last_name,b.user_id,b.user_name from ".NEWS." a , ".TBL_USER." b where a.news_id='$this->news_id' and a.user_id=b.user_id";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		$row=$this->db->fetch_array($record);
		if(!$ajax) {
		?>

		<div class="Clear" id="news<?php echo $row[news_id];?>"
		onmouseover="if(document.getElementById('action_<?php echo $row[news_id];?>'))document.getElementById('action_<?php echo $row[news_id];?>').style.display=''; " 
		onmouseout="if(document.getElementById('action_<?php echo $row[news_id];?>'))document.getElementById('action_<?php echo $row[news_id];?>').style.display='none'; ">
		<?php
		 }
		if($this->auth->isAdmin()){
		 ?>
		<span id="action_<?php echo $row[news_id];?>"  style="display:none;" class="news_action">
			<img src="images/edit.gif" border="0"  align="absmiddle" 
			onclick="news.Edit_News('local',<?php echo $row[news_id];?>,
										{
										onUpdate: function(response,root){ 
											document.getElementById('news<?php echo $row[news_id];?>').innerHTML=response;
											tinyMCE.init({
											mode : 'textareas',
											theme : 'advanced' ,
											theme_advanced_buttons1 : 'bold,italic,underline,ustifyleft,justifycenter,justifyright,justifyfull,undo,redo,link,unlink,forecolor',
											theme_advanced_buttons2 : '',
											theme_advanced_buttons3 : ''
																		});
										function getText(id)
										{
										return tinyMCE.get(id).getContent();
										}
								
										}, preloader: 'prl'
										}  
										);return false;"/>
		<img src="images/trash.gif" border="0"  align="absmiddle"
		onclick="if(confirm('Are you sure ?')){
					news.delete_news(<?php echo $row[news_id]; ?>,{target: 'news<?php echo $row[news_id];?>', preloader: 'prl'});
					} else return false;"
		/>
		  </span>
		 <? } ?>
		<table width="95%" class="nopadding">
			<tr class="nopadding">
				<td width="9%" align="right" valign="top" class="nopadding">
					<img src="images/person.gif"  border="0"/>				</td>
				<td width="19%" align="left" valign="top" class="nopadding">
				<div id="newsFrom"><?php echo $row[first_name].' '.$row[last_name].' says:'; ?></div>
				<span id="newsOn"><?php echo date('h:sa',$row[timestamp]).' on '.date('d/m/y',$row[timestamp]); ?> </span>
			  </td>
				<td width="72%" valign="top" class="nopadding">
					<ul><li><?php echo $row['title']; ?></li>
					<p><?php echo $row['content']; ?></p></ul>
			  </td>
			</tr>
		</table>
		<?php
		if(!$ajax) {
		?>
		</div>
		<?php 
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} 
	/**************************************************************************************************************/
	function Show_All_News()
	{
		$sql="select * from ".NEWS." order by timestamp Desc";
		$record=$this->db->query($sql,__FILE__,__LINE__);
		?>
		<div id="group_header">News - Newest to Oldest</div>
		<?
		while($row = $this->db->fetch_array($record))
		{
		?>
		<div class="border">
		<?php
		echo $this->Read_News($row['news_id']);
		?>
		</div>
		<?
		}
	} 
	/**************************************************************************************************************/
	function Edit_News($runat,$news_id,$title='',$content='')
	{
	if($this->auth->isAdmin()){
	ob_start();
	$this->news_id=$news_id;
	switch($runat){
		case 'local':
			if(count($_POST)>0 ){
					extract($_POST);
					$this->title=$title;
					$this->content=$content;
					}
					//create client side validation
					$FormName='frm_news_edit';
										
					$ControlNames=array("title"			=>array('title',"","Please enter Title","spantitle_frm_news_edit"),
										"content"			=>array('content',"","Please enter Content","spancontent_frm_news_edit"),
									);
					
					$ValidationFunctionName="frm_news_editCheckValidity";
					
					$JsCodeForFormValidation=$this->Validity->ShowJSFormValidationCode($FormName,$ControlNames,$ValidationFunctionName,$SameFields,$ErrorMsgForSameFields);
					echo $JsCodeForFormValidation;

					$sql="select a.user_id,a.news_id, a.title,a.content, UNIX_TIMESTAMP(a.timestamp) as timestamp ,b.first_name,b.last_name,b.user_id,b.user_name from ".NEWS." a , ".TBL_USER." b where a.news_id='$this->news_id' and a.user_id=b.user_id";
					$record=$this->db->query($sql,__FILE__,__LINE__);
					$row=$this->db->fetch_array($record);
					//display form
					?>
					<form method="post" action="" enctype="multipart/form-data" name="<?php echo $FormName; ?>">
					
					<table width="98%" class="nopadding" id="Edit_news" >
					<tr class="nopadding">
					  <td align="right" valign="top" class="nopadding">&nbsp;</td>
					  <td  align="left" valign="top" class="nopadding">&nbsp;</td>
					  <td>&nbsp;</td>
					  </tr>
					<tr class="nopadding">
						<td width="14%" align="center" valign="top" class="nopadding leftpadding">
						<img src="images/person.gif"  border="0"/>						</td>
						<td width="21%"  align="left" valign="top" class="nopadding">
							<div id="newsFrom"><?php echo $row[first_name].' '.$row[last_name].' says:'; ?></div>
					  <span id="newsOn"><?php echo date('h:sa',$row[timestamp]).' on '.date('d/m/y',$row[timestamp]); ?> </span>					  </td>
					  <td width="65%" valign="top">
						<table width="100%" class="news">
						<tr>
							<td><div id="spantitle_frm_news_edit"></div>
								<div id="spancontent_frm_news_edit"></div>							</td>
						</tr>						
						<tr>
							<td><input type="text" name="title" id="title" value="<?php echo $row['title']?>" /></td>
						</tr>
						<tr>
							<td><textarea name="content" id="content" cols="50" rows="10"><?php echo $row['content']?></textarea></td>
						</tr>
						<tr>
						<td align="right"><input type="button" name="update" id="update" value="Update" style="width:auto"  
										onclick="this.form.content.value=getText('content');
										if(<?php echo $ValidationFunctionName?>()) {
										 news.Edit_News('server',<?php echo $row[news_id];?>,this.form.title.value , this.form.content.value,
											{
											onUpdate: function(response,root){ 
												document.getElementById('news<?php echo $row[news_id];?>').innerHTML=response;
												}, preloader: 'prl'
											} );
										}"> 
										
										or
										
										
						<a href="" onclick="news.Read_News(<?php echo $row[news_id];?>,true,
										{
										onUpdate: function(response,root){ 
											document.getElementById('news<?php echo $row[news_id];?>').innerHTML=response;
											}, preloader: 'prl'
										}  
										);return false;"> cancel </a>						</td>
						</tr>
					  </table>					</td>
					</tr></table>
					</form>
					<?php
					break;
			case 'server':
					//Reading Post Date
					$this->title=$title;
					$this->content=$content;
					$return =true;
					if($this->Form->ValidField($title,'empty','Please Enter News title')==false)
					$return =false;
					if($this->Form->ValidField($content,'empty','Please Enter News Contents')==false)
					$return =false;
					if($return) {
					$update_sql_array = array();
					$update_sql_array['title'] = $this->title;
					$update_sql_array['content'] = $this->content;
					$this->db->update(NEWS,$update_sql_array,"news_id",$this->news_id,false,'p|strong|em|span|ol|li|ul|img|a',
										'script|style|noframes|select|option|html|head|meta|title|body|input|textarea|link|h1|form|table|tr|tbody|td|th|div');
					echo $this->Read_News($news_id,true);
					}
					else
					{
					echo $this->Form->ErrtxtPrefix.$this->Form->ErrorString.$this->Form->ErrtxtSufix; 
					echo $this->Edit_News('local',$this->news_id);
					}
					break;
			default : echo 'Wrong Paramemter passed';
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	} 
	
	function delete_news($news_id)
	{
		$this->news_id=$news_id;
		$sql="delete from ".NEWS." where news_id=$this->news_id";
		$this->db->query($sql,__FILE__,__LINE__);
		return '';
	}
			
}
	?>