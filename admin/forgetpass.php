<?php 
include('../include/functions.php');

if(isset($_POST['submit']))
{
	$email="";
		$error = 0;
	$email = trim($_POST['email']);
	if(empty($email)){
			$email_error = "Enter your email";
			$error++;			
		}else if(preg_match("/^[A-Za-z0-9._+\-\']+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/", $email) === 0){
			$email_error = "Invalid email";
			$error++;
		}
		
		
		
			if($error==0){
			connect();
		    $password=random_password(); 
			$newpassword=md5($password);
			
			$SQL = "UPDATE users set password='$newpassword' WHERE email='$email' and utype>1";
		
			$query = mysql_query($SQL);
			//$queru=TRUE;
			if($query){
				close();
				$mess="check your email id to get new password";
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				 $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				 
				 // Additional headers
				 $headers .= 'To: <'.$email.'>' . "\r\n";
				 $headers .= 'From: China Max Asia Pacific Limited  <test@webscarlets.in>' . "\r\n";
				 $headers .= 'Cc: wsfreelanzer@gmail.com' . "\r\n"; 				 
				 $to = $email;
				 $subject = "China Max Asia Pacific Limited - Forget Password"; 
				$message='Hi , <br/> <br/> Here you can find a new password as your request send to our server<br/> <br/> '.$password;
			
				mail( $to, $subject, $message, $headers);
												
				
				
						
			}else{
				echo mysql_error();
			}			
		}
}
else
  $email="";

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
<h2 class="form-signin-heading">Forgot Password</h2>
	<?php echo isset($email_error)?'<p class="text-danger">'.$email_error.'</p>':''; ?>
    <?php echo isset($mess)?'<span class="text-success">'.$mess.'</span>':''; ?>

    <input type="text" name="email" id="email" value="<?php echo $email; ?>" class="form-control" placeholder="Email address"/>

 <a href="index.php">Login</a>
        <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Reset Password</button>
    </form>
    </div>
</body>
</html>