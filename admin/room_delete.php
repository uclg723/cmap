<?php include('../include/functions.php'); 
session_start();
if(!isset($_SESSION['admin']))
{
	header("Location: login.php");
}
if(isset($_POST['delete'])){
	connect();
	$id = sanitize($_POST['delete']);
	$sql = "SELECT * FROM rooms WHERE id=".$id;
	$query = mysql_query($sql);
	if($query){
		if(mysql_num_rows($query)==1){
			$sql = "DELETE FROM rooms WHERE id=".$id;
			$query = mysql_query($sql);
			$_SESSION['room_delete'] = true;
			header("Location: room_list.php");
		}
	}
	header("Location: room_list.php");
}
?>