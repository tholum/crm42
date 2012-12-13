<?php
 class DateHelper{
 	
	const oneday = 86400; // 86400 seconds = 1 Day 
	const weekend = 172800; // 172800 seconds = 2 Days

 function addBusinessDays( $start_date='', $business_seconds='' ){
	
	 // If $start_date is on the weekend, start on following Monday
	 
		$business_days = $business_seconds/(24*60*60);
		if (date('N', $start_date) == 6) { // If start date is on Saturday 
			$new_start_date = $start_date + self::weekend; 
			} 
		elseif (date('N', $start_date) == 7) { // If start date is on Sunday 
			$new_start_date = $start_date + self::oneday; 
			} 
		else { 
			$new_start_date = $start_date; 
			} // Add business days to the start date 
		$due_date = $new_start_date + $business_days * self::oneday; // For every 5 business days, add 2 more for the weekend 
		$due_date += floor($business_days / 5) * self::weekend; // If remainder of business days causes due date to land on or after the weekend 
		if (($business_days % 5) + date('N', $new_start_date) >= 6) { 
			$due_date += self::weekend; // Add 2 days to compensate for the weekend 
			} 
		/*foreach($this->holidays as $holiday){ 
			$time_stamp = strtotime($holiday); // If the holiday falls between the start date and end date // and is on a weekday // Or if $new_start_date is on a holiday 
			if (($start_date <= $time_stamp && $time_stamp <= $due_date && date("N", $time_stamp) < 6) || date("Y-m-d", $new_start_date) == $holiday) { 
				$due_date += self::oneday; 
				if (date('N', $due_date) >= 6) { // If due date on Saturday or Sunday 
					$due_date += self::weekend; 
				} 
			} 
		} */
		return $due_date;
		
	}
	
	function getWorkingDays($startDate, $endDate){

		// Calculate weekday number. Monday is 1, Sunday is 7
		$firstWeekdayNumber = date("N", strtotime($startDate));
		$lastWeekdayNumber = date("N", strtotime($endDate));
		
		// Normalize the dates if they're weekends or holidays as they count for full days (24 hours)
		if ($firstWeekdayNumber == 6 || $firstWeekdayNumber == 7)
		$startDate = date("Y-m-d 00:00:00", strtotime($startDate));
		if ($lastWeekdayNumber == 6 || $lastWeekdayNumber == 7)
		$endDate = date("Y-m-d 00:00:00", strtotime("+1 days", strtotime( $endDate )));
		
		// Compute the floating-point differences in the dates
		$daysDifference = (strtotime($endDate) - strtotime($startDate)) / 86400;
		$fullWeeksDifference = floor($daysDifference / 7);
		$remainingDaysDifference = fmod($daysDifference, 7);
		
		// Subtract the weekends; In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
		if ($firstWeekdayNumber <= $lastWeekdayNumber){
		if ($firstWeekdayNumber <= 6 && 6 <= $lastWeekdayNumber && $remainingDaysDifference >= 1) $remainingDaysDifference--;
		if ($firstWeekdayNumber <= 7 && 7 <= $lastWeekdayNumber && $remainingDaysDifference >= 1) $remainingDaysDifference--;
		}
		else{
		if ($firstWeekdayNumber <= 6 && $remainingDaysDifference >= 1) $remainingDaysDifference--;
		// In the case when the interval falls in two weeks, there will be a Sunday for sure
		$remainingDaysDifference--;
		}
		
		// Compute the working days based on full weeks +
		$workingDays = $fullWeeksDifference * 5;
		if ($remainingDaysDifference > 0 )
		$workingDays += $remainingDaysDifference;
		
		// End of calculation, return the result now
		return ($workingDays);
    }

	}

/*$date = new DateHelper; 
$start = time(); 
echo date("l, M d, Y H:i:s A", $start); 
echo "<br>Days = " . date("l, M d, Y H:i:s A", $date->addBusinessDays($start,(4752000))); 
echo "<br>".$date->getWorkingDays(date("Y-m-d"),'2011-12-1')
*/?>