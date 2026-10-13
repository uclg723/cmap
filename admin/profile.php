<?php 
include('../include/functions.php');
session_start();
$menu="admin";
if(isset($_SESSION['profile_update'])){
	$profile_update=true;
	unset($_SESSION['profile_update']);
}
if(isset($_SESSION['admin'])){
		connect();
		$id = sanitize($_SESSION['admin']);
		$SQL = "SELECT * FROM users WHERE id = $id";
		$query = mysql_query($SQL);
		if($query){
			$nor = mysql_num_rows($query);
			if($nor>0){
				
			$row = mysql_fetch_assoc($query);
			 $fname=$row['firstname'];
			 $lname=$row['lastname'];
			 $phone=$row['phone'];
			 $email=$row['email'];
			 $cc=$row['country'];
			}
			
		}else{
			die("Error: ".mysql_error());
		}
		close();
}else{
	header("Location: login.php");
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
				
		if($error==0){
			connect();
			$fname = sanitize($fname);
			$lname = sanitize($lname);
			$email = sanitize($email);
		
			$id = $_POST['id'];
			if(empty($_POST['password'])){
				$SQL = "UPDATE users set firstname='$fname',lastname='$lname',email='$email' WHERE id='$id'";
			}else{
				$password = md5($password);
				$SQL = "UPDATE users set firstname='$fname',lastname='$lname',email='$email',password='$password' WHERE id='$id'";				
			}
		
		
			$query = mysql_query($SQL);
			if($query){
				close();
				$_SESSION['profile_update'] = TRUE;
				header("Location: profile.php");
				
			}else{
				echo mysql_error();
			}			
		}
}
?>
<?php include('include/head.php'); ?>
<body>

<!-- Fixed navbar -->
<?php include('include/nav.php'); ?>
<div class="container">
  <div class="page-header">
    <h1>Admin Profile</h1>
  </div>
  <div class="bs-example">
    <div class="col-lg-6">
      <?php
	if(isset($profile_update)){
		?>
      <div class="big">
        <p class="bg-success">Profile details successfully updated.</p>
      </div>
      <?php } ?>
      <form method="post" role="form">
        <div class="form-group">
          <label for="exampleInputfname">First Name</label>
          <input type="text" name="fname" class="form-control" id="exampleInputfname" placeholder="Enter first name" value="<?php echo $fname; ?>">
          <?php echo isset($fname_error)?'<span class="error">'.$fname_error.'</span>':''; ?> </div>
        <div class="form-group">
          <label for="exampleInputlname">Last Name</label>
          <input type="text" name="lname" class="form-control" id="exampleInputlname" placeholder="Enter last name" value="<?php echo $lname; ?>">
          <?php echo isset($lname_error)?'<span class="error">'.$lname_error.'</span>':''; ?> </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Email Address</label>
          <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email address" value="<?php echo $email; ?>">
          <?php echo isset($email_error)?'<span class="error">'.$email_error.'</span>':''; ?> </div>
          <div class="form-group">
    <label for="">Password</label>
    <input type="password" name="password" class="form-control" id="" placeholder="Enter new password" value=""><?php echo isset($password_error)?'<span class="error">'.$password_error.'</span>':''; ?>
  </div>
  <div class="form-group">
    <label for="">Confirm Password</label>
    <input type="password" name="cpassword" class="form-control" id="" placeholder="Re-type your password" value="">
  </div>                
        
        <button type="submit" name="submit" class="btn btn-default">Submit</button>
        <input type="hidden" value="<?php echo $id; ?>" name="id" />
      </form>
    </div>
  </div>
</div>
<!-- /.container --> 

<?php include('include/footer.php'); ?>