<?php 
include('../../include/functions.php');
header("Content-Type:text/html; charset=utf-8");
session_start();
$success_view=0;
if(!isset($_SESSION['admin']))
{
	header("Location: login.php");
}
$menu="staff";

// Manage submit forms
	if(isset($_GET['type'])){
	$myType = $_GET['type'];
	} else {
	$myType = "unindentified";	
	}
	if($myType == "t" && isset($_POST['companyName'])){

		$companyName = $_POST['companyName'];
		$companyNamecn = $_POST['companyNamecn'];
		$companyEmail = $_POST['companyEmail'];
		$companyPhone = $_POST['companyPhone'];

		if ($companyName != "") {
			connect();
			
			$SQL = "UPDATE pages SET companyName='" . $companyName . "', companyNamecn='" . $companyNamecn . "', companyEmail='" . $companyEmail . "', companyPhone='" . $companyPhone . "'";

			$query = mysql_query($SQL);
			if($query){
	
				$returnSql = " <div class=''>updated</div>";
					}
				echo $returnSql;
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
    <link rel="shortcut icon" href="file:///C|/Work/Web/assets/ico/favicon.ico">

    <title>Edit Company Info</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/starter-template.css" rel="stylesheet">

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>

<!-- dropzone photo upload -->
<script src="dropzone.min.js"></script>
<link rel="stylesheet" href="dropzone.min.css">
<script type="text/javascript">
		Dropzone.options.logoForm = {
		  init: function () {
			this.on("complete", function (file) {
				this.removeFile(file);
				document.getElementById("logo").innerHTML = "<img src='../../images/logo.png?timestamp=" + new Date().getTime() + "' >";
			});
		  },
		 acceptedFiles:".png"
		};


</script>

<style>
.error
{
	color:#F00;
}
</style>

  </head>

  <body>

     <?php include('../include/pagenav.php'); 

				connect();
				$SQL = "SELECT * FROM pages";
				
				$query = mysql_query($SQL);
				if($query){

					$nor = mysql_num_rows($query);
					if($nor>0){

					 $row = mysql_fetch_assoc($query);
					 $companyName=$row['companyName'];
					 $companyNamecn=$row['companyNamecn'];
					 $companyEmail=$row['companyEmail'];
					 $companyPhone=$row['companyPhone'];

					}
				}
				
				close();
	 ?>



  <div class="container">
<div class="page-header">
  <h1>Company Information</h1>
</div>


 
	
<form method="post" action="?type=t">
  <div class="form-group">
    <label for="">Company Name (English)</label>
    
    (This will be shown on footer left side)
    <input type="text" name="companyName" class="form-control" id="companyName" placeholder="" value="<?php echo $companyName; ?>"><?php echo isset($fname_error)?'<span class="error">'.$fname_error.'</span>':''; ?>
  </div>
  
  <div class="form-group">
    <label for="exampleInputlname">公司名稱 (中文)</label><input type="text" name="companyNamecn" class="form-control" id="companyNamecn" placeholder="" value="<?php echo $companyNamecn; ?>"><?php echo isset($lname_error)?'<span class="error">'.$lname_error.'</span>':''; ?>
  </div>
  
  <div class="form-group">
    <label for="exampleInputEmail1">Email Address</label>
    (This will be shown on footer right side)
    <input type="email" name="companyEmail" class="form-control" id="companyEmail" placeholder="Enter CS email address" value="<?php echo $companyEmail; ?>"><?php echo isset($email_error)?'<span class="error">'.$email_error.'</span>':''; ?>
  </div>
  <div class="form-group">
    <label for="">Main Tel</label>
    
    (This will be shown on header right side)
    <input type="text" name="companyPhone" class="form-control" id="companyPhone" placeholder="" value="<?php echo $companyPhone; ?>">
    <?php echo isset($password_error)?'<span class="error">'.$password_error.'</span>':''; ?>
  </div>
  <div class="form-group">
  <input class="btn-lg btn-info" type="submit" name="save" id="save" value="Save">

</div>
 </form>
   
  <p>&nbsp;</p>
  <p>&nbsp;</p>
   
<div id="form-group">
   <table class="table" cellpadding="8" cellspacing="10">
     <tr>
       <td class="h2">Company Logo </td>
       <td>&nbsp;</td>
     </tr>
     <tr>
         <td width="642" height="304" align="center"><div id="logo"><img src="../../images/logo.png" alt="" width="250"></div></td>
         <td width="192">
         
         
	  <p>      
      <form id="logoForm" action="dz-upload.php" class="dropzone"> 
      	<input type="hidden" name="name" value="logo.png">
		<input type="hidden" name="w" value="250">
		<input type="hidden" name="h" value="0">
      </form>
      </p>
      <p>*Only accept .png format<br>
        Size: Width 250, Height no limit</p>
         
</td>
     </tr>
     <tr>
       <td height="189" align="center">&nbsp;</td>
       <td>&nbsp;</td>
     </tr>
   </table>
</div>
  </div><!-- /.container -->

<?php include('../include/footer.php'); ?>
</body>
</html>