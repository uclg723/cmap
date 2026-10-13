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

		$homeText1 = $_POST['homeText1'];
		$homeText1cn = $_POST['homeText1cn'];
		$homeText2 = $_POST['homeText2'];
		$homeText2cn = $_POST['homeText2cn'];
		
		connect();
		$SQL = "UPDATE pages SET homeText1='" . $homeText1 . "', homeText1cn='" . $homeText1cn . "', homeText2='" . $homeText2 . "', homeText2cn='" . $homeText2cn . "'";
	
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
		width: 350,
		toolbar: 'undo redo | styleselect | bold italic | fontsizeselect | forecolor backcolor | table | link | code'
     });
	 
</script>


<!-- dropzone photo upload -->
<script src="dropzone.min.js"></script>
<link rel="stylesheet" href="dropzone.min.css">
<script type="text/javascript">
	Dropzone.options.slide1Form = {
	  init: function () {
		this.on("complete", function (file) {
			this.removeFile(file);
			document.getElementById("slide1").innerHTML = "<img src='../../images/slide1.jpg?timestamp=" + new Date().getTime() + "' >";
		});
	  }
	};

	Dropzone.options.slide2Form = {
	  init: function () {
		this.on("complete", function (file) {
			this.removeFile(file);
			document.getElementById("slide2").innerHTML = "<img src='../../images/slide2.jpg?timestamp=" + new Date().getTime() + "' >";
		});
	  }
	};

	Dropzone.options.slide3Form = {
	  init: function () {
		this.on("complete", function (file) {
			this.removeFile(file);
			document.getElementById("slide3").innerHTML = "<img src='../../images/slide3.jpg?timestamp=" + new Date().getTime() + "' >";
		});
	  }
	};
	
	Dropzone.options.home1Form = {
	  init: function () {
		this.on("complete", function (file) {
			this.removeFile(file);
			document.getElementById("home1").innerHTML = "<img src='../../images/home1.jpg?timestamp=" + new Date().getTime() + "' >";
		});
	  }
	};
	
	Dropzone.options.home2Form = {
	  init: function () {
		this.on("complete", function (file) {
			this.removeFile(file);
			document.getElementById("home2").innerHTML = "<img src='../../images/home2.jpg?timestamp=" + new Date().getTime() + "' >";
		});
	  }
	};
</script>

  <style type="text/css">
  #inputBox {
	width: 400px;
	margin-top: 20px;
	margin-left: 20px;
	float: left;
}
  #inputBoxContainer {
	width: 1024px;
	margin-left: auto;
	margin-right: auto;
	float: left;
}
  </style>
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
					 $homeText1=$row['homeText1'];
					 $homeText2=$row['homeText2'];
					 $homeText1cn= $row['homeText1cn'];
					 $homeText2cn= $row['homeText2cn'];
					}
				}
				
				close();
	 ?>
     
     
<form method="post" action="?type=t">

<div class="container">
  <h2>Edit - Home</h2>
<?PHP 		
	if (isset($returnSql)){
		echo $returnSql;
	}
?>
  
  
  <div class="row bg-info" >
    <div class="col-md-3"><br><img src="images/1.png" width="250" height="208"></div>
    <div class="col-md-4">
	  <span class="h4">Eng Version</span><br>
    <textarea id="homeText1" name="homeText1" cols="50" rows="10">

	<?php echo $homeText1;?>
    
    </textarea>
	</div>

    <div class="col-md-4">
    <span class="h4">中文</span><br>
    <textarea id="homeText1cn" name="homeText1cn" cols="50" rows="10">

	<?php echo $homeText1cn;?>
    
    </textarea>
    
    </div>
  </div>
  
  <br>
  <div class="row bg-info">
    <div class="col-md-3"><br><img src="images/2.png" width="250" height="208"></div>
    <div class="col-md-4">
    <span class="h4"> Eng Version</span><br>
    <textarea  id="homeText2" name="homeText2" cols="50" rows="10">

	<?php echo $homeText2;?>
    
    </textarea>
	</div>



	<div class="col-md-4">
    <span class="h4"> 中文</span>
<textarea  id="homeText2cn" name="homeText2cn" cols="50" rows="10">
	<?php echo $homeText2cn;?>
    
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
  <table class="table" cellpadding="8" cellspacing="10">
    <tr>
      <td class="h2">Main Slider Photos </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
   
    
      <td width="642" height="304" align="center"><div id="slide1"><img src="../../images/slide1.jpg" width="600" height="285"></div></td>
      <td width="192"> <p>
      <form id="slide1Form" action="dz-upload.php" class="dropzone"> 
      	<input type="hidden" name="name" value="slide1.jpg">
		<input type="hidden" name="w" value="600">
		<input type="hidden" name="h" value="285">
      </form></p>
<p>Size: 600 x 285</p></td>

    </tr>
    <tr>

      <td height="304" align="center"><div id="slide2"><img src="../../images/slide2.jpg" width="600" height="285"></div></td>
      <td><p>      
      <form id="slide2Form" action="dz-upload.php" class="dropzone"> 
      	<input type="hidden" name="name" value="slide2.jpg">
		<input type="hidden" name="w" value="600">
		<input type="hidden" name="h" value="285">
      </form></p>
<p>Size: 600 x 285</p></td>

    </tr>
    <tr>

      <td height="311" align="center"><div id="slide3"><img src="../../images/slide3.jpg" width="600" height="285"></div></td>
      <td><p>      
      <form id="slide3Form" action="dz-upload.php" class="dropzone"> 
      	<input type="hidden" name="name" value="slide3.jpg">
		<input type="hidden" name="w" value="600">
		<input type="hidden" name="h" value="285">
      </form></p>
<p>Size: 600 x 285</p></td>

    </tr>
    <tr>

      <td height="201" align="center"><div id="home1"><img src="../../images/home1.jpg" width="270" height="180"></div></td>
      <td><p>      
      <form id="home1Form" action="dz-upload.php" class="dropzone"> 
      	<input type="hidden" name="name" value="home1.jpg">
		<input type="hidden" name="w" value="270">
		<input type="hidden" name="h" value="180">
      </form></p>
<p>Size: 270 x 180</p></td>

    </tr>
    <tr>

      <td height="189" align="center"><div id="home2"><img src="../../images/home2.jpg" width="270" height="180"></div></td>
      <td><p>      
      <form id="home2Form" action="dz-upload.php" class="dropzone"> 
      	<input type="hidden" name="name" value="home2.jpg">
		<input type="hidden" name="w" value="270">
		<input type="hidden" name="h" value="180">
      </form></p>
<p>Size: 270 x 180</p></td>

    </tr>
    <tr>
      <td height="189" align="center">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</div>
</body>
</html>
