<?php 
session_start();

include('include/functions2.php');
include_once "lang/lang.php";
date_default_timezone_set("Asia/Calcutta");
connect();
$return_message = array("status" => "ERROR","message" => "Room id is null.");
if(isset($_POST["check_booking_rid"])){

	if(isset($_POST["booking_id"])){
		$sql_query = " AND `id` != '".$_POST["booking_id"]."'";
	}

	$_POST[date_from] = date("Y-m-d",strtotime($_POST[date_from]));
	$_POST[date_to] = date("Y-m-d",strtotime($_POST[date_to]));
	$sql = "SELECT * 
			FROM `booking`
			WHERE `rid` = '$_POST[check_booking_rid]' 
			AND (
					(`date_in` <= '$_POST[date_from]' AND `date_out` > '$_POST[date_from]')
					OR(`date_in` < '$_POST[date_to]' AND `date_out` >= '$_POST[date_to]')
					OR(`date_in` >= '$_POST[date_from]' AND `date_out` <= '$_POST[date_to]')
				)".$sql_query;
	$result = mysql_query($sql);
	$rows_num = mysql_num_rows($result);
	
	$return_message = array("status" => "ERROR","message" => "We are sorry to disoblige you, but the rooms you desire are already reserved.","SQL" => $sql);
	if($rows_num == 0){
		$return_message = array("status" => "SUCCESS","message" => "","SQL" => $sql);
	}
}
echo json_encode($return_message);
?>