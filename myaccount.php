<?php 
include('include/functions.php');
include_once "lang/lang.php";
include('include/country_code.php');
session_start();
if(!isset($_SESSION['id']))
{
	header('LOCATION:login.php');
}

// Go back to previous page if just login
if (isset($_SESSION['login_back_url'])){
	$url = $_SESSION['login_back_url'];
	unset($_SESSION['login_back_url']);
	header('LOCATION:' . $url);
}

if(isset($_SESSION['acc_updated'])){
	$acc_updated=true;
	unset($_SESSION['acc_updated']);	
}
if(isset($_SESSION['success'])){
	$success=true;
	unset($_SESSION['success']);
}

if(isset($_SESSION['id']))
{
		$user_login = 1;
		connect();
		$id = sanitize($_SESSION['id']);
		
		$SQL = "SELECT * FROM users WHERE id = $id";
		
		$query = mysql_query($SQL);
		if($query){
			
			$nor = mysql_num_rows($query);
			if($nor>0){
			$row = mysql_fetch_assoc($query);
			 $fname=$row['firstname'];
			 $lname=$row['lastname'];
			 $cc=$row['country'];
			 $phone=$row['phone'];
			 $email=$row['email'];
			 $passport=$row['passport'];
			 
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
		}
		
		 $cc= trim($_POST['cc']);
		if(empty($cc)){
			$cc_error = "Enter your Country code";
			$error++;			
		}else if(preg_match("/^[0-9]+$/", $cc) === 0){
			$cc_error = "Invalid Country code";
			$error++;
		}
		
		
        $phone = trim($_POST['phone']);
		if(empty($phone)){
			$phone_error = "Enter your Phone Number";
			$error++;			
		}else if(strlen($phone)<7 || strlen($phone)>12){
			$phone_error = "Min length should be 7 Max length should be 10";
			$error++;
		}else if(preg_match("/^[0-9]+$/", $phone) === 0){
			$phone_error = "Invalid Phone number";
			$error++;
		}
		
		/*$passport = trim($_POST['passport']);
		if(empty($passport)){
			$passport_error = "Enter your Passport No / Travel ID";
			$error++;			
		}else if(strlen($passport)<10 || strlen($passport)>30){
			$passport_error = "Min length should be 10 Max length should be 30";
			$error++;
		}*/
		
	
			$password = trim($_POST['password']);
			if(!empty($password)){
				if(strlen($password)<6){
					$password_error = "Min len should be 6";
					$error++;
				}else if(strlen($password)>15){
					$password_error = "Max len should be 15";
					$error++;
				}else{
					$conpassword = trim($_POST['conpassword']);
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
			$cc=sanitize($cc);
			$phone = sanitize($phone);
			$passport = sanitize($passport);
			$password = md5(sanitize($password));
		    $activation=md5($email.time()); 
			
			$id = $_POST['id'];
			
			if(empty($_POST['password'])){			
				$SQL = "UPDATE users set firstname='$fname',lastname='$lname',country='$cc',email='$email',phone='$phone',passport='$passport' WHERE id='$id'";
			}else{
				$SQL = "UPDATE users set firstname='$fname',lastname='$lname',country='$cc',email='$email',phone='$phone',passport='$passport',password='$password' WHERE id='$id'";
			}					
		
			$query = mysql_query($SQL);
			//$queru=TRUE;
			if($query){
				close();
				$_SESSION['acc_updated'] = TRUE; 
				
				header("Location: myaccount.php");
				
			}else{
				echo mysql_error();
			}			
		}
		
		
}


?>

<!DOCTYPE html>
<html>
<head>

     <?php 
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
     
     
<meta charset="utf-8" />
<meta name="viewport" />
<title>Hotel - Service Apartment - China Max Asia Pacific Company Limited</title>
<link type="text/css" href="include/style.css" rel="stylesheet"/>
<script type='text/javascript' src='include/jquery.min.js'></script>
<script type='text/javascript' src='include/jquery.cycle.all.min.js'></script>
<script type="text/javascript">
//top.document.title='Hotel - Service Apartment - China Max Asia Pacific Company Limited ';
</script>
<style>
.error {
	color: #F00;
	font-size: 12px;
	display: block;
	margin-left: 190px;
	margin-bottom: 5px;
}
form label {
	display: inline-block;
	width: 185px;
}
form input[type=text], form input[type=password] {
	display: inline-block;
	width: 250px;
	border: 1px solid #bcbcbc;
	padding: 3px;
}
form input[type=submit] {
	margin-left: 190px;
	cursor: pointer;
}
.fields {
	margin-bottom: 10px;
}
.success {
	color: #4F8A10;
	background-color: #DFF2BF;
	padding: 5px;
	margin-top: 10px;
	margin-bottom: 10px;
}
</style>

</head>



<body bgcolor="#F0F0F0">
<table width="930px" cellspacing="5" cellpadding="5" style="margin-left:auto; margin-right:auto" border="0">
  <tr valign="middle">
    <td align="left"><a href='home.php' title='China Max Asia Pacific Limited '><img src="images/logo.png" alt="Logo" width="250px"></a>&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/unipay_paypal.png" width="250px"></td>
    <td align="right"><p><a href="?lang=cn" class="navlang"><img src="lang/cn.png" width="16" height="11" alt="简体中文"> 简体中文</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="?lang=en" class="navlang"><img src="lang/us.png" width="16" height="11" alt="Eng"> Eng</a></p>
      <p><img src="images/phone_icon.jpg" alt="Tel"> <span style="font-size:22px;color:#53181a"><?PHP echo $companyPhone; ?></span><br>
        <?php 
	if ($curLang == "cn"){
		echo " 欢迎查询 ";	
	} else {
		echo " Call for Enquiry Now ";	
	}
?>
        <br>
      </p></td>
  </tr>
  <tr valign="top">
    <td colspan="2" style="border-bottom:1px solid #bcbcbc"><table width="98%" cellpadding="0" cellspacing="0">
      <tr>
        <td><a href="home.php" class="nav"><?php echo MENU_HOME; ?></a></td>
        <td width="50px"></td>
        <td><a href="about_us.php" class="nav"><?php echo MENU_ABOUT_US; ?></a></td>
        <td width="50px"></td>
        <td><a href="price.php" class="nav"><?php echo MENU_ONLINE_RESERVATION; ?></a></td>
        <td width="50px"></td>
        <td><a href="terms.php" class="nav"><?php echo MENU_TERMS_AND_CONDITIONS; ?></a></td>
        <td width="50px"></td>
        <td><a href="contact_us.php" class="nav"><?php echo MENU_CONTACT_US; ?></a></td>
        <td width="50px"></td>
        <?php if(isset($user_login)){?>
        <td><a href="myaccount.php" class="nav"><?php echo MENU_MY_ACCOUNT; ?></a></td>
        <td width="50px"></td>
        <!--<td><a href="rooms.php" class="nav">Booking</a></td>-->
        <!--<td width="50px"></td>-->
        <td><a href="logout.php" class="nav"><?php echo MENU_LOGOUT; ?></a></td>
        <?php }else{ ?>
        <td><a href="signup.php" class="nav"><?php echo MENU_SIGN_UP; ?></a></td>
        <td width="50px"></td>
        <td><a href="login.php" class="nav"><?php echo MENU_LOGIN; ?></a></td>
        <?php }?>
      </tr>
    </table></td>
  </tr>
  <tr valign="top">
    <td colspan="2" valign="top" align="left"><?php
			if(isset($acc_updated)){				
				echo '<div class="success">Profile details has successfully updated.</div>';
			}
			if(isset($success)){
				echo '<div class="success">Your registration has successfully completed and logged in</div>';
			}
		?>
      <form method="post">
        <div class="fields"><label><?php echo SIGNUP_FIRST_NAME; ?></label>
          <input type="text" name="fname" id="fname" value="<?php echo $fname; ?>"/>
          <?php echo isset($fname_error)?'<span class="error">'.$fname_error.'</span>':''; ?>
        </div>
        <div class="fields"><label><?php echo SIGNUP_LAST_NAME; ?></label>
          <input type="text" name="lname" id="lname" value="<?php echo $lname; ?>"/>
          <?php echo isset($lname_error)?'<span class="error">'.$lname_error.'</span>':''; ?>
        </div>
        <div class="fields"><label><?php echo SIGNUP_EMAIL; ?></label>
          <input type="text" name="email" id="email" value="<?php echo $email; ?>"/>
          <?php echo isset($email_error)?'<span class="error">'.$email_error.'</span>':''; ?>
        </div>
        <div class="fields"><label><?php echo SIGNUP_COUNTRY_CODE; ?></label>
          <select name="cc" id="cc">
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
          </select>
          <br>
        </div>
        <div class="fields"><label><?php echo SIGNUP_PHONE; ?></label>
          <input type="text" name="phone" id="phone" value="<?php echo $phone; ?>"/>
          <?php echo isset($phone_error)?'<span class="error">'.$phone_error.'</span>':''; ?>
        </div>
        <div class="fields" style="display:none">
          <label> Passport No / Travelling ID </label>
          <input type="text" name="passport" id="passport" value="<?php echo $passport; ?>"/>
          <?php echo isset($passport_error)?'<span class="error">'.$passport_error.'</span>':''; ?>
        </div>
        <div class="fields"><label><?php echo SIGNUP_PASSWORD; ?></label>
          <input type="password" name="password" id="password"/>
        </div>
        <div class="fields"><label><?php echo SIGNUP_CONFIRM_PASSWORD; ?></label>
          <input type="password" name="conpassword" id="conpassword"/>
          <?php echo isset($password_error)?'<span class="error">'.$password_error.'</span>':''; ?></div>
        <input type="submit" name="submit" value="<?php echo SIGNUP_SUBMIT; ?>"/>
        <input type="hidden" value="<?php echo $id; ?>" name="id" />
      </form></td>
  </tr>  
  <tr>
    <td colspan="2" width="100%" style="border-bottom:1px solid #bcbcbc">
    	<div class='my_bookings'>
    		<div class='mybooking_label'><h3><?php echo SIGNUP_MY_BOOKINGS; ?></h3></div>
<?php 

		include(__DIR__ . '/module/Booking/Booking.php');
		$book = new Booking();
		$filter = array(
			'uid'=> $_SESSION['id'],
			'date_in' => array(
				'operator' => '>=',
				'value' => date('Y-m-d', time()),
			),
		);
		$book_list = $book->retrieveByFilter($filter);

		include(__DIR__ . '/module/ListController/ListController.php');
		include(__DIR__ . '/module/ListView/ListView.php');
		$list_ctrl = new ListController();

		$list_ctrl->setModel($book_list);
		$lv = new ListView();

		$lv->setDisplayColumns(array(
			'id', 'date_in', 'date_out', 'days', 'rid',
		));
		$list_ctrl->setView($lv);
		
		$list_ctrl->process();
		
		$list_ctrl->render();
		

?>    	
    	</div>    
    </td>
  </tr>
   <tr>
    <td colspan="2" width="100%" style="border-bottom:1px solid #bcbcbc">&nbsp;</td>
  </tr>
  <tr valign="top">
     <td align="left">&copy; <?PHP echo $companyName;?></td>
     <td align="right"><a href='mailto:<?PHP echo $companyEmail;?>' class='nav'><?PHP echo $companyEmail;?></a></td>
  </tr>
</table>
</body>
</html>