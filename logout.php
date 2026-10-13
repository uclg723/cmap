<?php
session_start();
if(isset($_SESSION['id']))
{
	 setcookie("email", $_SESSION['email'], time()-60*60*24);
	 setcookie("password", $_SESSION['password'], time()-60*60*24);
	 setcookie("id", $_SESSION['id'], time()-60*60*24);
	 session_destroy();
	 header('LOCATION:login.php');
}else{
	 header('LOCATION:login.php');
}

?>