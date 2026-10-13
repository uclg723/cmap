<?php 
include('../include/functions.php');
include('../include/country_code.php');
session_start();
$success_view=0;
if(!isset($_SESSION['admin']))
{
	header("Location: login.php");
}
$menu="users";

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
			 $phone=$row['phone'];
			 $passport = $row['passport'];
			 $email=$row['email'];
			 $cc=$row['country'];
			 $status=$row['status'];
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
		
        $phone = trim($_POST['phone']);
		if(empty($phone)){
			$phone_error = "Enter your Phone Number";
			$error++;			
		}else if(strlen($phone)<7 || strlen($phone)>12){
			$phone = "Min length should be 7 Max length should be 10";
			$error++;
		}else if(preg_match("/^[0-9]+$/", $phone) === 0){
			$phone_error = "Invalid Phone number";
			$error++;
		}
		
		 $cc= trim($_POST['cc']);
		if(empty($cc)){
			$cc_error = "Enter your Country code";
			$error++;			
		}else if(strlen($cc)>5){
			$cc_error = "Max length should be 5";
			$error++;
		}else if(preg_match("/^[0-9]+$/", $cc) === 0){
			$cc_error = "Invalid Country code";
			$error++;
		}
		
		if(!isset($_POST['status'])){
			$status_error = "Select status";
			$error++;			
		}else{			
			$status = $_POST['status'];
		}

		/*$passport = trim($_POST['passport']);
		if(empty($passport)){
			$passport_error = "Enter your Passport No / Travel ID";
			$error++;			
		}else if(strlen($passport)<10 || strlen($passport)>30){
			$passport_error = "Min length should be 10 Max length should be 30";
			$error++;
		}*/
			
		if($error==0){
			connect();
			$fname = sanitize($fname);
			$lname = sanitize($lname);
			$email = sanitize($email);
			$phone = sanitize($phone);
            $cc=sanitize($cc);
			$status = sanitize($status);
		
			$id = $_POST['id'];
			if(empty($_POST['password'])){
					$SQL = "UPDATE users set firstname='$fname',lastname='$lname',email='$email',phone='$phone',country='$cc', status='$status',passport='$passport' WHERE id='$id'";
			}else{
					$password = md5($password);
					$SQL = "UPDATE users set firstname='$fname',lastname='$lname',email='$email',phone='$phone',country='$cc', status='$status',password='$password',passport='$passport' WHERE id='$id'";				
			}
				
		
			$query = mysql_query($SQL);
			if($query){
				close();
				$_SESSION['user_update']=TRUE;
				header("Location: users_list.php");
				
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
    <label for="">Phone Number</label>
    <input type="text" name="phone" class="form-control" id="" placeholder="Enter phone number" value="<?php echo $phone; ?>"><?php echo isset($phone_error)?'<span class="error">'.$phone_error.'</span>':''; ?>
  </div>
  
  <div class="form-group hide">
    <label for="">Passport No / Travelling ID</label>
    <input type="text" name="passport" class="form-control" id="" placeholder="Enter Passport No / Travelling ID" value="<?php echo $passport; ?>"><?php echo isset($passport_error)?'<span class="error">'.$passport_error.'</span>':''; ?>
  </div>
  
  <div class="form-group">
    <label for="exampleInputcountry">Country code</label>
    
<select name="cc" id="cc" class="form-control">
            <option value="0" selected>Select Country Code</option>
            <?php
				foreach($countries as $i=>$row){
					if(!empty($cc) and $cc==$row['code']){
						echo "<option value=\"{$row['code']}\" selected>{$row['name']}</option>\n";
					}else{
						echo "<option value=\"{$row['code']}\">{$row['name']}</option>\n";
					}					
				}
			?>
          </select><?php echo isset($cc_error)?'<span class="error">'.$cc_error.'</span>':''; ?>  </div>
    

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