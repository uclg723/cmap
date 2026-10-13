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

		$termsAndConditionsText1 = $_POST['termsAndConditionsText1'];
		$termsAndConditionsText1cn = $_POST['termsAndConditionsText1cn'];

		
		connect();
		$SQL = "UPDATE pages SET termsAndConditionsText1='" . $termsAndConditionsText1 . "', termsAndConditionsText1cn='" . $termsAndConditionsText1cn . "'";
	
		$query = mysql_query($SQL);
		if($query){

			$returnSql = " <div class=''>updated</div>";
				}
			echo $returnSql;
		close();
		


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

    <title>Edit Home page</title>

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
		width: 480,
		toolbar: 'undo redo | styleselect | bold italic | fontsizeselect | forecolor backcolor | table | link | code'
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
					 $termsAndConditionsText1=$row['termsAndConditionsText1'];
					 $termsAndConditionsText1cn=$row['termsAndConditionsText1cn'];
					}
				}
				
				close();
	 ?>
     
     
<form method="post" action="?type=t">

<div class="container">
  <h2>Edit - Terms &amp; Conditions</h2>
  <div class="row" >
    <div class="col-md-5">
	  <span class="h4">Eng</span><br>
    <textarea id="termsAndConditionsText1" name="termsAndConditionsText1" cols="50" rows="40">

	<?php echo $termsAndConditionsText1;?>
    
    </textarea>
	</div>

    <div class="col-md-5">
    <span class="h4">中文</span><br>
    <textarea  id="termsAndConditionsText1cn" name="termsAndConditionsText1cn" cols="50" rows="40">

	<?php echo $termsAndConditionsText1cn;?>
    
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




<div id="inputBoxContainer">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</div>
</div>
</form>
</body>
</html>
