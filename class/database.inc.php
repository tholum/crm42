<?php

$total_page_querys = 0;
$querys_ran = array();
	/*****************************************************************************
		Database Class for MySQL Server. Please do not change anything
	*****************************************************************************/
        $tmp_db = 0;
	class database {
		var $Con;
                var $json_error;
		function __construct($database_host,$database_port,$database_user,$database_password,$database_name , $json_error=false) {
                    $this->json_error = $json_error;
			$this->Con = @mysql_connect($database_host.":".$database_port,$database_user,$database_password) or die($this->error(mysql_error(),__FILE__,__LINE__));
			@mysql_select_db($database_name,$this->Con) or die($this->error(mysql_error()));
		}
		
		function __destruct() {
      		$this->close();
   		}
		
		function connect(){
			$this->Con = @mysql_connect(DATABASE_HOST.":".DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD) or die($this->error(mysql_error(),__FILE__,__LINE__));
			@mysql_select_db(DATABASE_NAME,$this->Con) or die($this->error(mysql_error()));
		}
		/*
            I make this a function so just in case there becomes an issue with mysql_real_escape_string I can change it in only one spot
        */
        function clean_string($string){
            return mysql_real_escape_string($string);
        }
        
		function query($sql,$errorFile='__FILE__',$errorLine='__LINE__' , $overide = array(),$cacheable=true ) {
                        global $tmp_db;
                        global $total_page_querys, $querys_ran;
                        $tmp_db++;
                        $options=array();
                        $options["show_error"] = "true";
                        foreach( $overide as $n => $v){
                            $options[$n] = $v;
                        }
                        if( $cacheable == true ){
                            $hash = hash('sha256', $sql);
                            if(array_key_exists($hash, $this->cache)){
                                mysql_data_seek ( $this->cache[$hash] , 0);
                                return $this->cache[$hash];
                            } else {
                                $total_page_querys++;
                                //$querys_ran[] = $sql;
                                $time = strtotime('NOW') + microtime();
                                if( $options["show_error"] == "true"){
                                    $result = @mysql_query($sql,$this->Con) or die($this->error($sql."<br />$tmp_db<br/>".mysql_error(),$errorFile,$errorLine));
                                } else {
                                    $result = @mysql_query($sql,$this->Con);                            
                                }
                                $time2 = strtotime('NOW') + microtime();
                                //$result = @mysql_query($sql,$this->Con) or die($this->error($sql."<br />".mysql_error(),$errorFile,$errorLine));
                                $querys_ran[] = array('sql' => $sql , 'time' => $time2 - $time );
                                $this->cache[$hash] = $result;
                                return $result;
                            }
                        } else {
                            $total_page_querys++;
                            $time = strtotime('NOW') + microtime();
                        //    $result = @mysql_query($sql,$this->Con) or die($this->error($sql."<br />".mysql_error(),$errorFile,$errorLine));
                            if( $options["show_error"] == "true"){
                                $result = @mysql_query($sql,$this->Con) or die($this->error($sql."<br />$tmp_db<br/>".mysql_error(),$errorFile,$errorLine));
                            } else {
                                $result = @mysql_query($sql,$this->Con);                            
                            }
                            $time2 = strtotime('NOW') + microtime();
                            $querys_ran[] = array('sql' => $sql , 'time' => $time2 - $time );
                            return $result;
                        }


			return $result;
		}
		
		function fetch_field($result,$i)
		{
			return mysql_fetch_field($result,$i);
		}
		
		function fetch_array($result) {
		$row=mysql_fetch_array($result);
		
		if(count($row)-1>0)
		foreach($row as $key=>$value)
		$row[$key]=stripslashes($value);
		
		return $row;
		}
		
		function fetch_row($result) {
		$row=mysql_fetch_row($result);
		
		if(count($row)-1>0)
		foreach($row as $key=>$value)
		$row[$key]=stripslashes($value);
		
		return $row;
		}
		
		function insert($table,$DataArray,$printSQL = false,$keep_tags='',$remove_tags='',$filterhtml=1,$overide=array() ) {
                    $options = array();
                    $options["show_error"] = "true";
                    foreach($overide as $n => $v){
                        $overide[$n] = $v;
                    }
			if(count($DataArray) == 0) {
				die($this->error("INSERT INTO statement has not been created",__FILE__,__LINE__));
			}
			foreach($DataArray as $key => $val) {
				$strFields.= "`".$key."`,";
				if($val == "CURDATE()" && $val != '' && $val != NULL) {
					$strValues.= "CURDATE(),";
				} elseif($val == "CURTIME()" && $val != '' && $val != NULL) {
					$strValues.= "CURTIME(),";
				} else {
				
				if($filterhtml==1) {
					if($keep_tags!='')
					$val=strip_tags($val,$keep_tags);
					else
					$val=strip_tags($val);
				}
					
					$strValues.= "'".addslashes($val)."',";	
				}
			}
			$strFields = substr($strFields,0,strlen($strFields)-1);
			$strValues = substr($strValues,0,strlen($strValues)-1);
			$sql = "INSERT INTO `".$table."`(".$strFields.") VALUES(".$strValues.")";
			if($printSQL == true) {
				echo $this->error($sql,__FILE__,__LINE__);
			} else {
				$this->query($sql,__FILE__,__LINE__,$options);
			}
		}

		function update($table,$DataArray,$updateOnField,$updateOnFieldValue,$printSQL = false,$keep_tags='',$remove_tags='',$filterhtml=1) {
			if(count($DataArray) == 0) {
				die($this->error("UPDATE statement has not been created",__FILE__,__LINE__));
			}
			$sql = "UPDATE ".$table." SET ";
			foreach($DataArray as $key => $val) {
				$strFields = "`".$key."`";
				if($val == "CURDATE()" && $val != '' && $val != NULL) {
					$strValues = "CURDATE()";
				} elseif($val == "CURTIME()" && $val != '' && $val != NULL) {
					$strValues = "CURTIME()";
				} else {
                                        if($filterhtml == 1){
                                            if($keep_tags!='')
                                            $val=strip_tags($val,$keep_tags);
                                            else
                                            $val=strip_tags($val);
                                        }
					$strValues = "'".addslashes($val)."'";
				}
				$sql.= $strFields."=".$strValues.", ";
			}
			$sql = substr($sql,0,strlen($sql)-2);
			$sql.= " WHERE `".$updateOnField."`='".addslashes($updateOnFieldValue)."'";
			if($printSQL == true) {
				echo $this->error($sql,__FILE__,__LINE__);
			} else {
				$this->query($sql,__FILE__,__LINE__);
			}
		}
		
		function last_insert_id() {
			return mysql_insert_id($this->Con);
		}
		
		function result($result,$row,$column) {
			return mysql_result($result,$row,$column);
		}
		
		function num_rows($result) {
			return mysql_num_rows($result);
		}
		
		function getDateDiff($coming_date) {
			$diff_sql = "SELECT DATEDIFF('".$coming_date."','".date('Y-m-d')."')";
			$diff_res = $this->query($diff_sql,__FILE__,__LINE__);
			return $this->result($diff_res,0,0);
		}
		
		function getTimeStampDiff($comming_timestamp)
		{
			$startdate = time();
			$enddate = $comming_timestamp;
			
			$time_period = ( $enddate - $startdate );
			
			$days = 0;
			$hours = 0;
			$minutes = 0;
			$seconds = 0;
			
			$time_increments = array( 'Days' => 86400,
			'Hours' => 3600,
			'Minutes' => 60,
			'Seconds' => 1 );
			
			$time_span = array();
			
			while( list( $key, $value ) = each( $time_increments )) {
			$this_value = (int) ( $time_period / $value );
			$time_period = ( $time_period % $value );
	
			$time_span[$key] = $this_value;
			}
			
			return $time_span;
		}	
		
			
		function record_number($sql) {
			$result = $this->query($sql,__FILE__,__LINE__);
			$cnt = $this->num_rows($result);
			return $cnt;
		}
		
		function pagination($sql,$rowsPerPage,$Page) {
		
			$PageResult = $this->record_number($sql);
			if($Page == "" || $Page == 1) {
				$Page = 0;
			} else {
				$Page = ($Page-1) * $rowsPerPage;
			}
			$RecordPerPage = ceil($PageResult/$rowsPerPage);
			$ReturnResult = $this->query($sql." limit ".$Page.",".$rowsPerPage."",__FILE__,__LINE__);
			return $ReturnResult;
		}
		
		function DisplayAjaxPage($rowsPerPage,$Page,$allCount,$object='contact',$method='GetContact',$target='search_result')
		{
			/**************************************************
			
			** $rowsPerPage = number of recrods per page
			** $Page = Current page no.
			** $allCount = Total No. of Recrods

			*****************************************************/
			if($Page > 1){ ?>
			<a onclick="javascript:<?php echo $object; ?>.<?php echo $method; ?>(document.getElementById('search').value, <?php echo $Page-1; ?> ,'','','<?php echo $object; ?>',{target: '<?php echo $target; ?>', preloader: 'prl'});" href="#">Previous</a>
			<?PHP }
			
			if($allCount <= $rowsPerPage) $limit = 0;
			elseif(($allCount % $rowsPerPage) == 0) $limit = ($allCount / $rowsPerPage) + 1;
			else $limit = ($allCount / $rowsPerPage) + 1;
			
			if($limit > 10 && $Page > 5){
			if($Page + 4 <= $limit){
			$start = $Page - 5;
			$end = $Page + 4;
			}else{
			$start = $limit - 9;
			$end = $limit;
			}
			}elseif($limit > 10){
			$start = 1;
			$end = 10;
			}else{
			$start = 1;
			$end = $limit;
			}
			
			if($start > 1) echo "...&nbsp;";
			$start = ceil($start);
			$end = ceil($end);
			for($i=$start;$i<$end;$i++){
			if($i != $Page)
			 $ext = ' onclick="javascript:'.$object.'.'.$method.'(document.getElementById(\'search\').value, '.($i).','."'','','".$object."',".'{target: \''.$target.'\', preloader: \'prl\'});" href="#" ';
			
			else $ext = ' style="color:#FF0000; text-decoration:none;" ';
			echo '<a' . $ext . '>' . $i . '</a>&nbsp;';
			}
			if($end < ceil($limit)) echo "...";
			if($Page < ($allCount / $rowsPerPage) and $limit>1){ ?>
			<a onclick="javascript:<?php echo $object; ?>.<?php echo $method; ?>(document.getElementById('search').value, <?php echo $Page+1; ?> ,'','','<?php echo $object; ?>',{target: '<?php echo $target; ?>', preloader: 'prl'});" href="#">Next</a>
			<?PHP } 
				
		}
		
		function pagination_page_number($sql,$DividedRecordNumber,$Page,$PageName,$QueryString) 
		{
			$PageResult = $this->record_number($sql);
			$RecordPerPage = ceil($PageResult/$DividedRecordNumber);
			if($Page == "") {
				$Page = 1;
			}
			
			$GET_INDEX = $_GET["index"];
			if( $GET_INDEX == 'List' ){ $QueryString = "index=List"; }
				
				
				$str = "<select class=\"txt\" name=\"cmbPage\" id=\"cmbPage\" onchange=\"javascript:_doPagination('".$PageName."','".$QueryString."');\">\n";
				for($i = 1;$i <= $RecordPerPage;$i++) {
					if($Page == $i) {
						$selected = ' selected';
					} else {
						$selected = '';
					}
					$str.= "<option value=\"".$i."\"".$selected.">Page ".$i."</option>\n";
				}
				$str.= "</select>";
				echo $str;
		}
		
		function pagination_page_number_new($sql,$DividedRecordNumber,$Page,$PageName,$QueryString) {
			$PageResult = $this->record_number($sql);
			$RecordPerPage = ceil($PageResult/$DividedRecordNumber);
			if($Page == "") {
				$Page = 1;
			}
			
				
			$str = "<select class=\"txt\" name=\"cmbPage\" id=\"cmbPage\" onchange=\"javascript:_doPagination('".$PageName."','".$QueryString."');\">\n";
			for($i = 1;$i <= $RecordPerPage;$i++) {
				if($Page == $i) {
					$selected = ' selected';
				} else {
					$selected = '';
				}
				$str.= "<option value=\"".$i."\"".$selected.">Page ".$i."</option>\n";
			}
			$str.= "</select>";
			echo $str;
		}
		
		function paging($sql,$DividedRecordNumber,$Page,$PageName,$QueryStringName,$Class) {
			$PageResult = $this->record_number($sql);
			if($PageResult > $DividedRecordNumber):
				$RecordPerPage = ceil($PageResult/$DividedRecordNumber);
				//echo $RecordPerPage;
				if($Page == "") {
					$Page = 1;
				}
				$PageCount = $Page - 1;
				if($PageCount > 0) {
					if(empty($QueryStringName)) {
						echo "<a href='".$PageName."?page=".$PageCount."' class='".$Class."'>&lt;&lt; Prev</a>&nbsp;";
					} else {
						echo "<a href='".$PageName."?page=".$PageCount."&".$QueryStringName."' class='".$Class."'>&lt;&lt; Prev</a>&nbsp;&nbsp;";
					}
				} else {
					echo "";
				}
				for($i = 1;$i <= $RecordPerPage;$i++) {
					if($Page == $i) {
						echo "<b>".$i."</b>&nbsp;";
					} else {
						echo "<a href='".$PageName."?page=".$i."&".$QueryStringName."' class='".$Class."'>".$i."</a>&nbsp;";
					}
				}
				$PageCount = $Page + 1;
				if($PageCount < $RecordPerPage + 1) {
					if(empty($QueryStringName)) {
						echo "<a href='".$PageName."?page=".$PageCount."' class='".$Class."'>Next &gt;&gt;</font>";
					} else {
						echo "<a href='".$PageName."?page=".$PageCount."&".$QueryStringName."' class='".$Class."'>Next &gt;&gt;</a>";
					}
				} else {
					echo "";
				}
			else:
				echo "&nbsp;";
			endif;
		}
		
		function close() {
			@mysql_close($this->Con);
		}		
		
		 function error($arg_error_msg,$arg_file,$arg_line) {
                    
			if(empty($arg_error_msg)==false) {
                            
				$error_msg = "<div style=\"font-family: Tahoma; font-size: 11px; padding: 10px; background-color: #FFD1C4; color: #990000; font-weight: bold; border: 1px solid #FF0000; text-align: center;\">";
				$error_msg.= $arg_error_msg."<br>in file ".$arg_file." at line number ".$arg_line;
				$error_msg.= "</div>";
				if( $this->json_error == false ){
                                    return $error_msg;
                                } else {
                                    return json_encode( array( 'status' => 'error' , 'error' => $arg_error_msg , 'line' => $arg_line , 'file' => $arg_file ) );
                                }
			}
		}
		
	
	
	
	
	 function remove_HTML($s , $keep = 'p|strong|em|span|ol|li|ul|img|a' , 
	 							$expand = 'script|style|noframes|select|option|html|head|meta|title|body|input|textarea|link|h1|form|table|tr|tbody|td|th|div')
	{
        /**///prep the string
        $s = ' ' . $s;
       
        /**///initialize keep tag logic
        if(strlen($keep) > 0){
            $k = explode('|',$keep);
            for($i=0;$i<count($k);$i++){
                $s = str_replace('<' . $k[$i],'[{(' . $k[$i],$s);
                $s = str_replace('</' . $k[$i],'[{(/' . $k[$i],$s);
            }
        }
       
        //begin removal
        /**///remove comment blocks
        while(stripos($s,'<!--') > 0){
            $pos[1] = stripos($s,'<!--');
            $pos[2] = stripos($s,'-->', $pos[1]);
            $len[1] = $pos[2] - $pos[1] + 3;
            $x = substr($s,$pos[1],$len[1]);
            $s = str_replace($x,'',$s);
        }
       
        /**///remove tags with content between them
        if(strlen($expand) > 0){
            $e = explode('|',$expand);
            for($i=0;$i<count($e);$i++){
                while(stripos($s,'<' . $e[$i]) > 0){
                    $len[1] = strlen('<' . $e[$i]);
                    $pos[1] = stripos($s,'<' . $e[$i]);
                    $pos[2] = stripos($s,$e[$i] . '>', $pos[1] + $len[1]);
                    $len[2] = $pos[2] - $pos[1] + $len[1];
                    $x = substr($s,$pos[1],$len[2]);
                    $s = str_replace($x,'',$s);
                }
            }
        }
       
        /**///remove remaining tags
        while(stripos($s,'<') > 0){
            $pos[1] = stripos($s,'<');
            $pos[2] = stripos($s,'>', $pos[1]);
            $len[1] = $pos[2] - $pos[1] + 1;
            $x = substr($s,$pos[1],$len[1]);
            $s = str_replace($x,'',$s);
        }
       
        /**///finalize keep tag
        for($i=0;$i<count($k);$i++){
            $s = str_replace('[{(' . $k[$i],'<' . $k[$i],$s);
            $s = str_replace('[{(/' . $k[$i],'</' . $k[$i],$s);
        }
       
        return trim($s);
    }
	
	/**///feald name
	   function field_name($result,$num) {
			$field_name = mysql_field_name($result,$num);
			return $field_name;
		}
		
		/**/// count number of feald
	   function num_fields($result) {
			$field_count = mysql_num_fields($result);
			return $field_count;
		}
		
		function fetch_assoc($result) {
			$row=mysql_fetch_assoc($result);
			return $row;
		}
		
		function field_seek($result, $i) {
			return mysql_field_seek($result,$i);
		}
		
		function free_result($result) {
			return mysql_free_result($result);
		}
		
		function affected_rows($con) {
			return mysql_affected_rows($con->Con);
		}
		
		function data_seek($result,$i) {
			return mysql_data_seek($result , $i);
		}
		


}
?>