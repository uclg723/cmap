<?php 
include('../include/functions.php');
session_start();

if(isset($_SESSION['admin']))
{
	header('LOCATION:users_list.php');
}

$fname="";
if(isset($_POST['submit']))
{

	$error = 0;
	
		$email = trim($_POST['email']);
		if(empty($email)){
			$email_error = "Enter your Email address";
			$error++;	
		}
		$password = md5(trim($_POST['password']));
			if(empty($password)){
				$password_error = "Enter your password";
				$error++;	
			}
	  if($error==0)
	  {
            connect();
		$SQL = "SELECT * FROM users WHERE email='".$email."' and utype>1";		
	
		$query = mysql_query($SQL);
		$nor=mysql_num_rows($query);
		if($query){
				
			if($nor==1){
				
				     $row = mysql_fetch_array($query);
					 $dbemail=$row['email'];
					 $dbpassword=$row['password'];
					 $dbstatus=$row['status'];
				
				  if(($email==$dbemail) && ($password==$dbpassword))
				  {
					  if($dbstatus==2)
					  {
						 
						   $_SESSION['admin']=$row['id'];
						    header('Location: users_list.php');
					  }
					  else
					      $error_login="You are not a Authorised Person to Login";
				  }
				  else
				
					  $error_login="Enter Valid Username and password";
				   
			} 
			else
			 	$fname_error = "Enter your Valid Username";
		}else{
			die("Error: ".mysql_error());
		}
	close();
	
	  }

}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
   
    <title>Signin Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">        


  <form class="form-signin" role="form" method="post">
        <h2 class="form-signin-heading">Please sign in</h2>
        <?php echo isset($error_login)?'<p class="text-danger">'.$error_login.'</p>':""?>
        <input type="text" name="email" class="form-control" placeholder="Email address" autofocus><p class="text-danger"><?php echo isset($email_error)?$email_error :""?></p>
        <input type="password" name="password" class="form-control" placeholder="Password"><p class="text-danger"><?php echo isset($password_error)?$password_error :""?></p>
        <a href="forgetpass.php">Forget Password</a>
        <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Sign in</button>
      </form>
      
      
      
    </div> 
  </body>
</html>
