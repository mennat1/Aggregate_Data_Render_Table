<?php

include "connect_to_mysql_server.php";

if(isset($_POST)){
	$_POST = json_decode(file_get_contents('php://input'), true);
	$db_name = mysqli_real_escape_string($mysql_srvr,$_POST['db_name']);
   	mysqli_select_db($mysql_srvr, $db_name);
   	// $table_name = mysqli_real_escape_string($mysql_srvr,$_POST['table_name']);
}



$servers_arr = array();

$table_to_be_checked = 'SUMMARY_T';
$has_SUMMARY_T = table_exists($db_name, $table_to_be_checked, $mysql_srvr);

$table_to_be_checked = 'System_Info';
$has_System_Info = table_exists($db_name, $table_to_be_checked, $mysql_srvr);

if($has_SUMMARY_T){
	$servers_info= mysqli_query($mysql_srvr, "SELECT DISTINCT `System Name` FROM SUMMARY_T");

}elseif($has_System_Info){
	$servers_info = mysqli_query($mysql_srvr, "SELECT DISTINCT `Hostname` FROM System_Info");
}else{
	echo("ERROR FETCHING SERVERS");
}

while($row = mysqli_fetch_array($servers_info)) { 
	
	$servers_arr[] = utf8_encode($row[0]);;

}
mysqli_close($mysql_srvr);
$servers_json_str = utf8_encode(json_encode($servers_arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT|JSON_FORCE_OBJECT| JSON_PRETTY_PRINT ));

echo $servers_json_str;



function table_exists($db_name, $table_name, $mysql_srvr){
	$query = sprintf("SHOW TABLES FROM `%s` LIKE '%s'", $db_name, $table_name);
	$result = mysqli_query($mysql_srvr, $query);
	$row_cnt = mysqli_num_rows($result);
	if( $row_cnt == 1 ){
		//echo($table_name." was found\n");
	    return TRUE;
	}
	else{
		//echo($table_name." was NOT found\n");
	    return FALSE;
	}
	mysqli_free_result($result);
}