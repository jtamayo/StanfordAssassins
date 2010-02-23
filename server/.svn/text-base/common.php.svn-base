<?php
	function getDate() {
		return gmdate("Y-m-d H:i:s");
	}
	
	function getDateLimit() {
		$offsetHours = 96;
		$day = intval(gmdate("N")) - 1; // 0 (for Monday) through 6 (for Sunday)
		for($h = 0; $h < $offsetHours; $h+=24) {
			if(5 <= $day) $offsetHours+12;
			$day = ($day+1) % 7;
		}
		return gmdate("Y-m-d H:i:s", time()+$offsetHours*60*60);
	}
?>