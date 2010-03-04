<?php
function codewords() {
	return array('age','air','anger','animal','answer','apple','area','arm','art','atom','baby','back','ball','band','bank','bar','base','bat','bear','beauty','bell','bird','bit','block','blood','blow','board','boat','body','bone','book','bottom','box','boy','branch','bread','break','brother','call','camp','capital','captain','car');
}

function arg($arg) {
	if(isset($_POST[$arg])) return ini_get('magic_quotes_gpc') ? $_POST[$arg] : addslashes($_POST[$arg]);
	if(isset($_GET[$arg])) return ini_get('magic_quotes_gpc') ? $_GET[$arg] : addslashes($_GET[$arg]);
	return false;
}
	
function getDateNow() {
	return gmdate("Y-m-d H:i:s");
}

function getDateLimit() {
	$offsetHours = 142;
	$day = intval(gmdate("N")) - 1; // 0 (for Monday) through 6 (for Sunday)
	for($h = 0; $h < $offsetHours; $h+=24) {
		if(5 <= $day) $offsetHours+12;
		$day = ($day+1) % 7;
	}
	return gmdate("Y-m-d H:i:s", time()+$offsetHours*60*60);
}

function sql_error_report($sql, $file) {		
	$date = getDateNow();
	$sqlError = mysql_error();
	mysql_query(sprintf("INSERT INTO errors (type, error, extra, date) VALUES ('SQL', '%s', '%s:%s', '%s');", addslashes($sqlError), $file, addslashes($sql), $date));
	
	$ret = array(
		"status" => "SQL_ERROR",
		"error" => $sqlError,
		"sql" => $sql
	);
	
	die(json_encode($ret));
}
?>