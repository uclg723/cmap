<?php 
include('../../include/functions.php');
session_start();
$success_view=0;
if(!isset($_SESSION['admin']))
{
	header("Location: ../login.php");
}
$menu="users";





// Manage submit forms
	if(isset($_GET['type'])){
	$myType = $_GET['type'];
	} else {
	$myType = "unindentified";	
	}
	if($myType == "t"){

		$onlineReservationText1 = str_replace("'","",$_POST['onlineReservationText1']);
		$onlineReservationText1cn = str_replace("'","",$_POST['onlineReservationText1cn']);
		
		
		
		
		
	
		if ($onlineReservationText1 != "") {
			connect();
			$SQL = "UPDATE pages SET onlineReservationText1='" . $onlineReservationText1 . "', onlineReservationText1cn='" . $onlineReservationText1cn . "'";

			$query = mysql_query($SQL);
			if($query){
	
				echo " <div >updated</div>";
			}
			close();
		}


	}




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

    <title>Edit Online Reservation</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/starter-template.css" rel="stylesheet">

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>

    <!-- Tinymce text editor -->
    <script type="text/javascript" src="tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
    tinymce.init({
        selector: 'textarea',
		plugins: 'code textcolor table link paste',
    	paste_as_text: true,
		menubar: false,
		width: 370,
		toolbar: 'undo redo | styleselect | bold italic | forecolor backcolor | table | link | code'
     });
    </script>
    


  </head>

<body>

     <!-- Fixed navbar -->
     <?php include('../include/pagenav.php'); 

				connect();
				$SQL = "SELECT * FROM pages";
				
				$query = mysql_query($SQL);
				if($query){

					$nor = mysql_num_rows($query);
					if($nor>0){

					$row = mysql_fetch_assoc($query);
					 $onlineReservationText1=$row['onlineReservationText1'];
					 $onlineReservationText1cn=$row['onlineReservationText1cn'];
					}
				}
				
				close();
	 ?>
     
     
<form method="post" action="?type=t">

<div class="container">
  <h2>Edit - Online Reservation</h2>
  <div class="row bg-info" >
    <div class="col-md-3"><br><img src="images/4.png" width="250" height="417"></div>
    <div class="col-md-4">
	  <span class="h4">Eng Version</span><br>
    <textarea id="onlineReservationText1" name="onlineReservationText1" cols="50" rows="15">

	<?php echo $onlineReservationText1;?>
    
    </textarea>
	</div>

    <div class="col-md-4">
    <span class="h4">中文</span><br>
    <textarea  id="onlineReservationText1cn" name="onlineReservationText1cn" cols="50" rows="15">

	<?php echo $onlineReservationText1cn;?>
    
    </textarea>
    </div>
  </div>
  

<div class="col-md-5"> 
  <p>
    <br>
    <input class="btn-lg btn-info" type="submit" name="save" id="save" value="Save">

  </p>
  <p>&nbsp;</p>
</div>
</form>



<div id="inputBoxContainer">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</div>
</body>
</html>
