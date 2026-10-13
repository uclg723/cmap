<?php
include('include/functions.php');
if(isset($_GET['activation']))
{
	$act=$_GET['activation'];
    connect();
	$sql = "SELECT * FROM users WHERE activation='".$act."' and status=1";
	$query = mysql_query($sql);
	if($query){
		$nor = mysql_num_rows($query);
		if($nor==1){
			$SQL = "UPDATE users set status='2' WHERE activation='".$act."'";
			$query = mysql_query($SQL);
			if($query){
				session_start();
				$_SESSION['activated']=TRUE;
				header("Location: login.php");
			}
			else
				die("ERROR:");
		}else{
			header("Location: login.php");
		}
	}
}
?>