<?php
class Event_Suggestions
{
	var $db;
	//var $zip;
	var $distance;
	var $type;
	var $evt_id;
	
	function __construct()
	{
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
		//$this->zip = new zipcode_class();
		$this->type = array('STAFF','HOTEL');
	}
	
	function hotelSuggestion($runat)
	{
		switch ($runat){
			case 'local':
				$formName = frm_hotelSuggestion_Event_Suggestions;
				$sql = "select * from ".EM_SUGGESTION_MASTER." where suggestion_type='".$this->type[1]."'";
				$result = $this->db->query($sql,__FILE__,__LINE__);
				$row = $this->db->fetch_array($result);
				?>
				<form method="post" action="" enctype="multipart/form-data" name="<?php echo $formName?>" >
				<table >
				  <tr>
				    <td>Suggest Hotels within <input type="text" name="distance" id="distance" value="<?php echo $row[distance]?>"> miles of the event and lowest priced</td>
				    <td><input type="submit" name="submit" value="Go" id="submit" onclick="if(!document.getElementById('distance').value){ alert('please enter distance'); return false; }"></td>
				  </tr>
				</table>
				</form>
				<?php
				break;
				
			case 'server':
				extract($_POST);
				$this->distance = $distance;
				if ($this->distance) {
					$sql = "delete from ".EM_SUGGESTION_MASTER." where suggestion_type='".$this->type[1]."'";
					$this->db->query($sql,__FILE__,__LINE__);
					$insert_sql_array = array();
					$insert_sql_array[suggestion_type] = $this->type[1];
					$insert_sql_array[distance] = $this->distance;
					$this->db->insert(EM_SUGGESTION_MASTER,$insert_sql_array);
					$_SESSION[msg] = 'Distance Added';
					echo '<script type="text/javascript">window.location="'.$_SERVER['PHP_SELF'].'"</script>';
				}
				else {
					$this->hotelSuggestion('local');
								
				}
				
				break;
				
			default:   echo "wrong parameter passed";
		}
	}
	
	/*function getHotelRange()
	{
		$sql = "SELECT * FROM ".EM_SUGGESTION_MASTER." where suggestion_type = 'HOTEL'";
		$result = $this->db->query($sql,__FILE__,__LINE__);	
		$row = $this->db->fetch_array($result);
		return $row[distance];
	}
	
	function getHotelSuggestion($evt_id,$range=0)
	{
		$this->evt_id = $evt_id;
		$sql = "SELECT * FROM ".EM_EVENT." where event_id = '".$evt_id."'";
		$result = $this->db->query($sql,__FILE__,__LINE__);	
		$row = $this->db->fetch_array($result);
		
		if(!$range)
			$range = $this->getHotelRange();
		$zips = $this->zip->get_zips_in_range($row[zip],$range,1,true);
		return $zips;
	}*/
	
	
		function staffSuggestion($runat) 
	{
		switch ($runat){
			case 'local':
				$formName = frm_staffSuggestion_Event_Suggestions;
				$sql = "select * from ".EM_SUGGESTION_MASTER." where suggestion_type='".$this->type[0]."'";
				$result = $this->db->query($sql,__FILE__,__LINE__);
				$row = $this->db->fetch_array($result);
				?>
				<form method="post" action="" enctype="multipart/form-data" name="<?php echo $formName?>" >
				<table >
				  <tr>
				    <td>Suggest Staff within <input type="text" name="distancestaff" id="distancestaff" value="<?php echo $row[distance]?>"> miles of the event</td>
				    <td><input type="submit" name="staffdis" value="Go" id="staffdis" onclick="if(!document.getElementById('distancestaff').value){ alert('please enter distance'); return false; }"></td>
				  </tr>
				</table>
				</form>
				<?php
				break;
				
			case 'server':
				extract($_POST);
				$this->distancestaff = $distancestaff;
				if ($this->distancestaff) {
					$sql = "delete from ".EM_SUGGESTION_MASTER." where suggestion_type='".$this->type[0]."'";
					$this->db->query($sql,__FILE__,__LINE__);
					$insert_sql_array = array();
					$insert_sql_array[suggestion_type] = $this->type[0];
					$insert_sql_array[distance] = $this->distancestaff;
					$this->db->insert(EM_SUGGESTION_MASTER,$insert_sql_array);
					$_SESSION[msg] = 'Distance Added';
					echo '<script type="text/javascript">window.location="'.$_SERVER['PHP_SELF'].'"</script>';
				}
				else {
					$this->staffSuggestion('local');
								
				}
				
				break;
				
			default:   echo "wrong parameter passed";
		}
	}
	
	
	
}
?>