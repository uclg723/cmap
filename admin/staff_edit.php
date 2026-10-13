<?php 
include('../include/functions.php');
include('../include/country_code.php');
session_start();
$success_view=0;
if(!isset($_SESSION['admin']))
{
	header("Location: login.php");
}
$menu="staff";

if(isset($_GET['id'])){
		connect();
		$id = sanitize($_GET['id']);
		$SQL = "SELECT * FROM users WHERE id = $id";
		$query = mysql_query($SQL);
		if($query){
			$nor = mysql_num_rows($query);
			if($nor>0){
				
			$row = mysql_fetch_assoc($query);
			 $fname=$row['firstname'];
			 $lname=$row['lastname'];
			 $email=$row['email'];
			 $status=$row['status'];
			 $utype=$row['utype'];
			}
			
		}else{
			die("Error: ".mysql_error());
		}
		close();
	}

if(isset($_POST['submit']))
{
		$error = 0;
	
		$fname = trim($_POST['fname']);
		if(empty($fname)){
			$fname_error = "Enter your First name";
			$error++;			
		}else if(strlen($fname)>15){
			$name_error = "Max len should be 15";
			$error++;
		}else if(preg_match("/^[A-Za-z][a-zA-Z -]+$/", $fname) === 0){
			$fname_error = "Invalid First Name";
			$error++;
		}
        
		$lname = trim($_POST['lname']);
		if(!empty($lname))
		{
			if(strlen($lname)>15){
				$lname_error = "Max len should be 15";
				$error++;
			}else if(preg_match("/^[A-Za-z][a-zA-Z -]+$/ ", $lname) === 0){
				$lname_error = "Invalid Last Name";
				$error++;
			}
		}
	
		$email = trim($_POST['email']);
		if(empty($email)){
			$email_error = "Enter your email";
			$error++;			
		}else if(preg_match("/^[A-Za-z0-9._+\-\']+@[A-Za-z0-9.\-]+\.[A-Za-z]{2,}$/", $email) === 0){
			$email_error = "Invalid email";
			$error++;
		}else{
		 
		}		

		$password = trim($_POST['password']);
		if(!empty($password)){
			if(strlen($password)<6){
				$password_error = "Min len should be 6";
				$error++;				
			}else if(strlen($password)>15){
				$password_error = "Max len should be 15";
				$error++;
			}else{
				$conpassword = trim($_POST['cpassword']);
				if($conpassword!=$password){
					$password_error = "Password & confirm password not match";
					$error++;				
				}
			}
		}		
		
		if(!isset($_POST['utype'])){
			$utype_error = "Select User Type";
			$error++;			
		}else{			
			$utype= $_POST['utype'];
		}
        
		
		if(!isset($_POST['status'])){
			$status_error = "Select status";
			$error++;			
		}else{			
			$status = $_POST['status'];
		}

		
		if($error==0){
			connect();
			$fname = sanitize($fname);
			$lname = sanitize($lname);
			$email = sanitize($email);
			$status = sanitize($status);
			$utype=sanitize($utype);
			$country="NULL";
			$phone="NULL";
			$passport="NULL";
			$activation="NULL";
		
			$id = $_POST['id'];
			if(empty($_POST['password'])){
					$SQL = "UPDATE users set firstname='$fname',lastname='$lname',email='$email',phone='$phone',country='$cc', status='$status', utype='$utype', passport='$passport' WHERE id='$id'";
			}else{
					$password = md5($password);
					$SQL = "UPDATE users set firstname='$fname',lastname='$lname',email='$email',phone='$phone',country='$cc', status='$status',password='$password',passport='$passport' WHERE id='$id'";				
			}
				
		
			$query = mysql_query($SQL);
			if($query){
				close();
				$_SESSION['user_update']=TRUE;
				header("Location: staff_list.php");
				
			}else{
				echo mysql_error();
			}			
		}
		
		
}


?>
<style>
.error
{
	color:#F00;
}
</style>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>Starter Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/starter-template.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>


        <!-- Fixed navbar -->
     <?php include('include/nav.php'); ?>



  <div class="container">
    <div class="page-header">
        <h1>Users</h1>
      </div>
   <div class="bs-example">

 <div class="col-lg-6">
	
  <form method="post" role="form">
  <div class="form-group">
    <label for="">First Name</label>
    <input type="text" name="fname" class="form-control" id="" placeholder="Enter first name" value="<?php echo $fname; ?>"><?php echo isset($fname_error)?'<span class="error">'.$fname_error.'</span>':''; ?>
  </div>
  
  <div class="form-group">
    <label for="exampleInputlname">Last Name</label>
    <input type="text" name="lname" class="form-control" id="exampleInputlname" placeholder="Enter last name" value="<?php echo $lname; ?>"><?php echo isset($lname_error)?'<span class="error">'.$lname_error.'</span>':''; ?>
  </div>
  
  <div class="form-group">
    <label for="exampleInputEmail1">Email Address</label>
    <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email address" value="<?php echo $email; ?>"><?php echo isset($email_error)?'<span class="error">'.$email_error.'</span>':''; ?>
  </div>
  <div class="form-group">
    <label for="">Password</label>
    <input type="password" name="password" class="form-control" id="" placeholder="Enter new password" value=""><?php echo isset($password_error)?'<span class="error">'.$password_error.'</span>':''; ?>
  </div>
  <div class="form-group">
    <label for="">Confirm Password</label>
    <input type="password" name="cpassword" class="form-control" id="" placeholder="Re-type your password" value="">
  </div>
 
  <div class="form-group">
    <label for="exampleInpututype">User Type</label>
         
          <div class="radio">
          <label>
            <input type="radio" name="utype" id="optionsRadios1" value="3" <?php echo (isset($utype) && ($utype==3))?'checked':"";?>  >
             Manager
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="utype" id="optionsRadios2" value="4" <?php echo (isset($utype) && ($utype==4))?'checked':"";?> >
            Staff
          </label>
        </div>

	<?php echo isset($utype_error)?'<span class="error">'.$utype_error.'</span>':''; ?>
  </div>     
  
  <div class="form-group">
    <label for="exampleInputstatus">Status</label>
     	<div class="radio">
          <label>
            <input type="radio" name="status" id="optionsRadios1" value="1"  <?php echo (isset($status) && ($status==1))?'checked':"";?> >
            Inactive
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="status" id="optionsRadios2" value="2" <?php  echo (isset($status) && ($status==2))?'checked':"";?>>
            Active
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="status" id="optionsRadios3" value="3" <?php  echo (isset($status) && ($status==3))?'checked':"";?>>
            Disabled
          </label>
        </div>
        <?php echo isset($status_error)?'<span class="error">'.$status_error.'</span>':''; ?>
  </div>
  
  
   <button type="submit" name="submit" class="btn btn-default">Submit</button>
   <input type="hidden" value="<?php echo $id; ?>" name="id" />
</form>
</div>
    
</div>
    
 </div><!-- /.container -->

<?php include('include/footer.php'); ?>