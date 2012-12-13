 <?php
 /***********************************************************************************

Class Discription : Dates
					This module will be responsible for adding, editing and deleting dates for the various functions
					in the platform. Dates can be a single date, a recurring date such as once a week, and be
					stretch over multiple days such as april 1st – april 5th

Class Memeber Functions :AddDate($runat)
                         EditDate($runat,$date_id)
						 GetDate($date_id)


Describe Function of Each Memeber function:
					1. function AddDate($runat)  // $runat=local/server 
						Add Start_Date,end_date,title,description,category,recurring in the database in tbl_date table	
					
					2. function EditTask($runat,$task_id) // $runat=local/server,,$task_id=task id of the task(uniqe)
						Edit category,module,module_id,description,due_date in the database in task table
					



************************************************************************************/
class Date // Basic class for contact 
{
	
	//var $user_id;
	const module='TBL_DATE';
	var $start_date	;	
	var $end_date	;	    
	var $title		;	
	var $description;		
	var $category	;	
	var $recuring	;
	var $db;	
	 function __construct()
	 {
	    $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	 }   
	
	function AddDate($runat)
	{
		switch($runat){
		
		case 'local':
					//Display Form
					
					?>
					<form action="" enctype="multipart/form-data" method="post">
					<div class="Clear">
				
					<div class="Label">Start Date</div>
					<div class="Field">
					<input type="text" name="start_date" id="start_date" />
					<span id="spanstart_date"></span></div>
					
					<div class="Label">End Date</div><div class="Field">
					<input type="text" name="end_date" id="end_date" />
					<span id="spanend_date"></span></div>
					</div>
					<div class="Clear">
					<div class="Label">Title</div><div class="Field">
					<input type="text" name="title" id="title" />
					<span id="spantitle"></span></div>
					</div>
					<div class="Clear">
					<div class="Label">Description</div><div class="Field">
					  <textarea name="description" id="description"></textarea>
					  <span id="spandescription"></span></div>
					  </div>
					  <div class="Clear">
					<div class="Label">Category</div><div class="Field">
					<select name="category" id="category">
					    <option value="Birthday">Birthday</option>
					    <option value="Anniversary">Anniversary</option>
						</select>
					<span id="spancategory"></span></div>
					</div>
					<div class="Clear">
					<div class="Label">Recurring</div><div class="Field">
					<select name="recurring" id="recurring">
					    <option value="Once">Once</option>
					    <option value="daily">daily</option>
						<option value="weekly">weekly</option>
						<option value="bi-weekly">bi-weekly</option>
						<option value="monthly">monthly</option>
						<option value="bi-yearly">bi-yearly</option>
						<option value="yearly">yearly</option>
					</select>
					<span id="spancategory"></span></div>
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
					$this->start_date=$start_date;
					$this->end_date=$end_date;
					$this->title=$title;
					$this->description=$description;
					$this->category=$category;
					$this->recurring=$recurring;
					$insert_sql_array = array();
					$insert_sql_array['start_date'] = $this->start_date;
					$insert_sql_array['end_date'] = $this->end_date;
					$insert_sql_array['title'] = $this->title;
					$insert_sql_array['description'] = $this->description;
					$insert_sql_array['category'] = $this->category;
					$insert_sql_array['recurring'] = $this->recurring;
					$this->db->insert(TBL_DATE,$insert_sql_array);
			break;
		default : echo 'Wrong Paramemter passed';
		
	}
	}
	
    function EditDate($runat, $date_id)
	{
		$this->date_id=$date_id;
		switch($runat){
		
		case 'local':
					//Edit date
					
					$sql="select * from TBL_DATE where date_id='$this->date_id'";
					$record=$this->db->query($sql,__FILE__,__LINE__);
					$row=$this->db->fetch_array($record);
					?>
					<form action="" enctype="multipart/form-data" method="post">
					<div class="Clear">
				
					<div class="Label">Start Date</div>
					<div class="Field">
					<input type="text" name="start_date" id="start_date" value="<?php echo $row['start_date'];?>" />
					<span id="spanstart_date"></span></div>
					
					<div class="Label">End Date</div><div class="Field">
					<input type="text" name="end_date" id="end_date" value="<?php echo $row['end_date'];?>"/>
					<span id="spanend_date"></span></div>
					</div>
					<div class="Clear">
					<div class="Label">Title</div><div class="Field">
					<input type="text" name="title" id="title" value="<?php echo $row['title'];?>"/>
					<span id="spantitle"></span></div>
					</div>
					<div class="Clear">
					<div class="Label">Description</div><div class="Field">
					  <textarea name="description" id="description" ><?php echo $row['description']; ?></textarea>
					  <span id="spandescription"></span></div>
					  </div>
					  <div class="Clear">
					<div class="Label">Category</div><div class="Field">
					<select name="category" id="category">
					    <option value="Birthday" <?php if($row['category']=="Birthday") echo 'selected="selected"'; ?>>
						Birthday</option>
					    <option value="Anniversary" <?php if($row['category']=="Anniversary") echo 'selected="selected"'; ?>>
						Anniversary </option>
						</select>
					<span id="spancategory"></span></div>
					</div>
					<div class="Clear">
					<div class="Label">Recurring</div><div class="Field">
					<select name="recurring" id="recurring">
					    <option value="Once" <?php if($row['recurring']=="Once") echo 'selected="selected"'; ?>>Once</option>
					    <option value="daily" <?php if($row['recurring']=="daily") echo 'selected="selected"'; ?>>daily</option>
						<option value="weekly" <?php if($row['recurring']=="weekly") echo 'selected="selected"'; ?>>weekly</option>
						<option value="bi-weekly" <?php if($row['recurring']=="bi-weekly") echo 'selected="selected"'; ?>>
						bi-weekly</option>
						<option value="monthly" <?php if($row['recurring']=="monthly") echo 'selected="selected"'; ?>>
						monthly</option>
						<option value="bi-yearly" <?php if($row['recurring']=="bi-yearly") echo 'selected="selected"'; ?>>
						bi-yearly</option>
						<option value="yearly" <?php if($row['recurring']=="yearly") echo 'selected="selected"'; ?>>
						yearly</option>
					</select>
					<span id="spancategory"></span></div>
					</div>
					<div class="Clear">
					<div class="Field">
					  <input type="submit" name="submit" id="submit" value="Save" />
					  <span id="spansubmit"></span></div>
					  </div>
					</form>	
					<?php
						
		
			break;
			case 'server':
					//Reading Post Date
					extract($_POST);
					$this->start_date=$start_date;
					$this->end_date=$end_date;
					$this->title=$title;
					$this->description=$description;
					$this->category=$category;
					$this->recurring=$recurring;
					$update_sql_array = array();
					$update_sql_array['start_date'] = $this->start_date;
					$update_sql_array['end_date'] = $this->end_date;
					$update_sql_array['title'] = $this->title;
					$update_sql_array['description'] = $this->description;
					$update_sql_array['category'] = $this->category;
					$update_sql_array['recurring'] = $this->recurring;
					$this->db->update(TBL_DATE,$update_sql_array,"date_id",$this->date_id);		
			break;
		default : echo 'Wrong Paramemter passed';
		
	
	}
	

  }
   function GetDate($date_id)
	{
				
		$sql="select * from ".TBL_DATE." where date_id='$this->date_id'";

		$record=$this->db->query($sql,__FILE__,__LINE__);
		$row=$this->db->fetch_array($record);
		return $row;
		
	}
}
?>
