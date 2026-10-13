<?php 
include('include/functions.php');
include_once "lang/lang.php";

session_start();

if(isset($_SESSION['id']))
{
	header('LOCATION:myaccount.php');
}

if(isset($_SESSION['activated'])){
	$activation=true;
	unset($_SESSION['activated']);
}

if(isset($_POST['submit']))
{
	$error = 0;
	
		$email = trim($_POST['email']);
		if(empty($email)){
			$email_error = "Enter your email";
			$error++;	
		
		}
		$password = md5(sanitize($_POST['password']));
			if(empty($password)){
				$password_error = "Enter your password";
				$error++;	
		
			}
	  if($error==0)
	  {
            connect();
		$SQL = "SELECT * FROM users WHERE email='".$email."' and utype=1";
		$query = mysql_query($SQL);
		$nor=mysql_num_rows($query);

		if($query){
			if($nor>0){
				     $row = mysql_fetch_array($query);
					 $dbemail=$row['email'];
					 $dbpassword=$row['password'];
					 $dbstatus=$row['status'];
		             $dbid=$row['id'];
				     if(($email==$dbemail) && ($password==$dbpassword))
				     {
					     if($dbstatus==2)
						 {
							/*$_SESSION['email']=$email;
							$_SESSION['password']=$password;*/
							$_SESSION['id']=$dbid;
							setcookie("email", $_SESSION['email'], time()+60*60*24);
						    setcookie("password", $_SESSION['password'], time()+60*60*24);
						    setcookie("id", $_SESSION['id'], time()+60*60*24);
						    header('LOCATION:myaccount.php');
						 }
					     else
					         $error_login="You are not a Authorised Person to Login";
				     }
				     else
					    $error_login="Invalid Email and Password";	   
			} 
			else
			 	$email_error = "Enter your Valid email";
		}else{
			die("Error: ".mysql_error());
		}
	close();

	  }

		
}
else
{
	$email="";
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
					
					 $contactUsText1=$row['contactUsText1'];
					 $contactUsText1cn=$row['contactUsText1cn'];

					}
				}
				
				close();
	 ?>
<meta charset="utf-8" />
<meta name="viewport" />
<title>Hotel - Service Apartment - China Max Asia Pacific Limited </title>
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
	margin-left: 160px;
	margin-bottom:5px;
}
form label {
	display: inline-block;
	width: 155px;
}
form input[type=text], form input[type=password] {
	display: inline-block;
	width: 250px;
	border: 1px solid #bcbcbc;
	padding: 3px;
}
form input[type=submit]{
	margin-left:160px;
	cursor:pointer;
}
.fields {
	/*margin-bottom: 10px;*/
}
.success{
	color: #4F8A10;
	background-color: #DFF2BF;
	padding: 5px;
	margin-top: 10px;
	margin-bottom: 10px;
}
.link{
	margin-left:5px;
	font-size:14px;
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
	<td colspan="2" valign="top" align="left">
    <h3><?PHP echo MENU_LOGIN;?></h3>
    <?php
			if(isset($activation)){
				echo '<div class="success">Your account has successfully activated. You can login now.</div>';
			}
		?>
    <form method="post">
    <?php echo isset($error_login)?'<span class="error">'.$error_login.'</span>':""?>
    <div class="fields">
    <label> <?PHP echo EMAIL_ID;?></label>
    <input type="text" name="email" id="email" value="<?php echo $email; ?>"/><?php echo isset($email_error)?'<span class="error">'.$email_error.'</span>':''; ?></div>
    <div class="fields">
    <label><?PHP echo PASSWORD;?></label>
    <input type="password" name="password" id="password"/>
    </div>
    <input type="submit" name="submit" value="<?PHP echo MENU_LOGIN;?>"/><a href="forgotpass.php" class="link"><?PHP echo FORGOT_PASSWORD;?></a>
    </div>
    
    </form>

	</td>
</tr>
<tr><td colspan="2" width="100%" style="border-bottom:1px solid #bcbcbc">&nbsp;</td></tr>
<tr valign="top">
  <td align="left">&copy; <?PHP echo $companyName;?></td>
  <td align="right"><a href='mailto:<?PHP echo $companyEmail;?>' class='nav'><?PHP echo $companyEmail;?></a></td>
</tr>
</table>
</body>
</html>
