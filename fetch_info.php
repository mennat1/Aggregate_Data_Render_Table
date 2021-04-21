<?php

include "connect_to_mysql_server.php";

if(isset($_POST)){
	$_POST = json_decode(file_get_contents('php://input'), true);
	$db_name = mysqli_real_escape_string($mysql_srvr,$_POST['db_name']);
	mysqli_select_db($mysql_srvr, $db_name);

	$selected_servers = $_POST['selected_servers'];
	// foreach($selected_servers as $value) {

	// 	echo($value."\n");

	// }

}

$table_to_be_checked = 'SUMMARY_T';
$has_SUMMARY_T = table_exists($db_name, $table_to_be_checked, $mysql_srvr);
// echo($has_SUMMARY_T."----1111\n");
$table_to_be_checked = 'System_Info';
$has_System_Info = table_exists($db_name, $table_to_be_checked, $mysql_srvr);
// echo($has_System_Info."---222222\n");

$total_number_of_servers = count($selected_servers);
// echo("total_number_of_servers = ".$total_number_of_servers."\n");
$required_data = array();

for($j = 0; $j <= ($total_number_of_servers-1); $j++){
	// echo($j."\n");
	$server_name = $selected_servers[$j];
	if($has_SUMMARY_T){
		// echo("1111111111\n");
		$query = sprintf("SELECT `System Name`, `Processor Counter`, `Processor Speed (Mhz)`, `Processor Core`, `Available Physical Memory (bytes)` FROM SUMMARY_T WHERE `System Name`='%s'", $server_name);

	}elseif($has_System_Info){
		$query = sprintf("SELECT `System Name`, `Processor Counter`, `Processor Speed (Mhz)`, `Processor Core`, `Available Physical Memory (bytes)` FROM System_Info WHERE `Hostname`='%s'", $server_name);

	}else{
		echo("YALAHWII---11111\n");
	}

	$required_info = mysqli_query($mysql_srvr, $query);
	$row_cnt = mysqli_num_rows($required_info);
	// echo($row_cnt."\n");
	$server_data = array();
	while($data = mysqli_fetch_assoc($required_info)){
		$server_speed_mhz = $data["Processor Counter"] * $data["Processor Speed (Mhz)"] * $data["Processor Core"];
		$server_speed_ghz = $server_speed_mhz/1000;
		$server_mem_bytes = $data["Available Physical Memory (bytes)"];

		$server_data["server_name"] = $data["System Name"];
		$server_data["server_speed_ghz"] = $server_speed_ghz;
		$server_data["server_mem_bytes"] = $server_mem_bytes;
		

	}
	$required_data[] = $server_data;
	unset($server_data);
}

// echo("lalalalala\n");
// echo($required_data[0]."\n");
$servers_json_str = utf8_encode(json_encode($required_data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT|JSON_FORCE_OBJECT| JSON_PRETTY_PRINT ));

echo $servers_json_str;


function table_exists($db_name, $table_name, $mysql_srvr){
	$query = sprintf("SHOW TABLES FROM `%s` LIKE '%s'", $db_name, $table_name);
	$result = mysqli_query($mysql_srvr, $query);
	$row_cnt = mysqli_num_rows($result);
	if( $row_cnt == 1 ){
		// echo($table_name." was found\n");
	    return TRUE;
	}
	else{
		// echo($table_name." was NOT found\n");
	    return FALSE;
	}
	mysqli_free_result($result);
}