<?php 
include('../../include/functions.php');
include('../../include/country_code.php');
session_start();
$success_view=0;
if(!isset($_SESSION['admin']))
{
	header("Location: ../login.php");
}
$menu="users";

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>Starter Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/starter-template.css" rel="stylesheet">

  </head>

<body>
<a href="edit-home.php">Home

     <!-- Fixed navbar -->
     <?php include('../include/nav.php'); ?>

</a>










</body>
</html>
