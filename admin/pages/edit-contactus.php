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

		$contactUsText1 = str_replace("'","",$_POST['contactUsText1']);
		$contactUsText1cn = str_replace("'","",$_POST['contactUsText1cn']);

		
		connect();
		$SQL = "UPDATE pages SET contactUsText1='" . $contactUsText1 . "', contactUsText1cn='" . $contactUsText1cn . "'";
	
		$query = mysql_query($SQL);
		if($query){

			$returnSql = "<blockquote><p class='bg-success'>Page details has successfully updated.</p></blockquote>";
				}
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

    <title>Edit Contact Us</title>

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
		width: 380,
		toolbar: 'undo redo | styleselect | bold italic | fontsizeselect | forecolor backcolor | table | link | code'
	 });
</script>



<!-- dropzone photo upload -->
<script src="dropzone.min.js"></script>
<link rel="stylesheet" href="dropzone.min.css">
<script type="text/javascript">
		Dropzone.options.contactUsForm = {
		  init: function () {
			this.on("complete", function (file) {
				this.removeFile(file);
				document.getElementById("contact_us").innerHTML = "<img src='../../images/contact_us.jpg?timestamp=" + new Date().getTime() + "' >";
			});
		  }
		};

	</script>
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
					 $contactUsText1=$row['contactUsText1'];
					 $contactUsText1cn=$row['contactUsText1cn'];
					}
				}
				
				close();
	 ?>
     
  <div class="container">   
<form method="post" action="?type=t">


  <h2>Edit - Contact Us</h2>

<?PHP 		
	if (isset($returnSql)){
		echo $returnSql;
	}
?>
  <div class="row bg-info" >
    <div class="col-md-3"><br><img src="images/5.png" width="250" height="187"></div>
    <div class="col-md-4">
	  <span class="h4">Eng Version</span><br>
    <textarea id="contactUsText1" name="contactUsText1" cols="80" rows="18">

	<?php echo $contactUsText1;?>
    
    </textarea>
	</div>

    <div class="col-md-4">
    <span class="h4">中文</span><br>
    <textarea  id="contactUsText1cn" name="contactUsText1cn" cols="80" rows="18">

	<?php echo $contactUsText1cn;?>
    
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
  
  <table cellpadding="8" cellspacing="10" class="table">
    <tr>
      <td class="h2">Photos </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
    
      <td width="400" height="304" align="center"><div id="contact_us"><img src="../../images/contact_us.jpg" width="312" height="450"></div></td>
      
      <td width="250"> 
	  
	  <p>      
      <form id="contactUsForm" action="dz-upload.php" class="dropzone"> 
      	<input type="hidden" name="name" value="contact_us.jpg">
		<input type="hidden" name="w" value="312">
		<input type="hidden" name="h" value="450">
      </form>
      </p>
      <p>Size: 312 x 450</p>
      
      </td>

    </tr>
    <tr>
      <td height="189" align="center">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</div>
</div>
</body>
</html>
