<?php
class DateHelper extends AppHelper{
	/** 
	 * Format for the date is : '%d/%m/%Y %H:%M:%S'
	 */
	function ago($date){
		$year = substr($date, 0, 4);
		$month = substr($date, 5, 2);
		$day = substr($date, 8, 2);
		$hour = substr($date, 12, 2);
		$min = substr($date, 14, 2);
		
		$pureNumberDate = $year.$month.$day.','.$hour.$min;
		$timestamp = strtotime($pureNumberDate);
		
		$now = time();	
		$days = intval(($now-$timestamp)/(3600*24));
		$hours = intval(($now-$timestamp) / 3600);
		$minutes = intval(($now-$timestamp) / 60);
		if(intval($now-$timestamp) > intval(3600*24*7)){
			return date("M jS Y", $timestamp).' at '.date("H:i",$timestamp);
		}elseif($days > 0){
			return $days.' day(s) ago';
		}elseif($hours > 0){
			return $hours. ' hour(s) ago';
		}else{
			return $minutes.' mn(s) ago';
		}
	}
}
?>