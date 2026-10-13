<?php 
if( !session_id() ) session_start();
include('include/functions.php');

if(isset($_SESSION['forgot_pwd'])){
	$forgot_pwd=true;
	//unset($_SESSION['forgot_pwd']);
}
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
		}else{
			connect();
			$sql = "SELECT * FROM users WHERE email='".$email."' and utype=1";
			$query = mysql_query($sql);
			if($query){
				if(mysql_num_rows($query)!=1){
					$email_error = "Invalid email";
					$error++;
				}else{
					$data = mysql_fetch_assoc($query);
					$name = $data['firstname']." ".$data['lastname'];
				}
			}else{
				$error++;
			}
			close();
		}
		
			if($error==0){
			connect();
		    $password=random_password(); 
			$newpassword=md5($password);
			$SQL = "UPDATE users set password='$newpassword' WHERE email='$email' and utype=1";
		
			$query = mysql_query($SQL);
			//$queru=TRUE;
			if($query){
				close();				
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				 
				 // Additional headers
				 $headers .= 'To: <'.$email.'>' . "\r\n";
				 $headers .= 'From: China Max Asia Pacific Limited  <test@webscarlets.in>' . "\r\n";
				 $headers .= 'Cc: wsfreelanzer@gmail.com' . "\r\n"; 
				 $to = $email;
				 $subject = "China Max Asia Pacific Limited - Forgot Password"; 
				 $message='Hi , <br/> <br/> Your New Password: '.$password;
				//echo $message;
				//mail( $to, $subject, $message, $headers,'-ftest@webscarlets.in' );
				$_SESSION['forgot_pwd']=TRUE;
				
				require 'email/PHPMailerAutoload.php';
				
				//Create a new PHPMailer instance
				$mail = new PHPMailer();
				//Tell PHPMailer to use SMTP
				$mail->isSMTP();
				//Enable SMTP debugging
				// 0 = off (for production use)
				// 1 = client messages
				// 2 = client and server messages
				$mail->SMTPDebug = 0;
				//Ask for HTML-friendly debug output
				$mail->Debugoutput = 'html';
				$mail->SMTPSecure = "tls";
				//Set the hostname of the mail server
				$mail->Host = "smtp.gmail.com";
				//Set the SMTP port number - likely to be 25, 465 or 587
				$mail->Port = 587;
				//Whether to use SMTP authentication
				$mail->SMTPAuth = true;
				//Username to use for SMTP authentication
				$mail->Username = "hkcmap@gmail.com";
				//Password to use for SMTP authentication
				$mail->Password = "chinamax";
				//Set who the message is to be sent from
				$mail->setFrom('hkcmap@gmail.com', 'China Max');
				//Set an alternative reply-to address
				$mail->addReplyTo('hkcmap@gmail.com', 'China Max');
				//Set who the message is to be sent to
				$mail->addAddress($email, $name);
				//$mail->addAddress('hkcmap@gmail.com', 'China Max');
				//Set the subject line
				$mail->Subject = 'Forgot Password - China Max Asia Pacific Limited';
				//Read an HTML message body from an external file, convert referenced images to embedded,
				//convert HTML into a basic plain-text alternative body
				$mail->msgHTML('Hi , <br/> <br/> Your New Password: '.$password);
				//Replace the plain text body with one created manually
				$mail->AltBody = 'Hi , <br/> <br/> Your New Password: '.$password;
				
				//send the message, check for errors
				$mail->send();
				
				
				header("Location: forgotpass.php");
			}else{
				echo mysql_error();
			}			
		}
}
else
  $email="";

?>

<!DOCTYPE html>
<html>
<head>
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
	<td align="left"><a href='home.php' title='China Max Asia Pacific Limited '><img src="images/logo.png" width="250px"></a>&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/unipay_paypal.png" width="250px"></td>
	<td align="right">
		<img src="images/phone_icon.jpg">
		<span style="font-size:22px;color:#53181a">(852) 9700 6180</span><br>
		Call for Enquiry Now <br><br>
<a href="" class="nav">Eng</a>
|
<a href="" class="nav">中文 </a>
	</td>
</tr>
<tr valign="top">
	<td colspan="2" width="100%" style="border-bottom:1px solid #bcbcbc">
	<table cellspacing="0" cellpadding="0">
<tr>
	<td><a href="home.php" class="nav">Home </a></td>
	<td width="50px"></td>
	<td><a href="about_us.php" class="nav">About Us </a></td>
	<td width="50px"></td>
	<td><a href="price.php" class="nav">Online Reservation </a></td>
	<td width="50px"></td>
	<td><a href="terms.php" class="nav">Terms & Conditions </a></td>
	<td width="50px"></td>
	<td><a href="contact_us.php" class="nav">Contact Us </a></td>
     <td width="50px"></td>
	<td><a href="signup.php" class="nav">Sign Up </a></td>
    <td width="50px"></td>
	<td><a href="login.php" class="nav">Login </a></td>
</tr>
</table>
	</td>
</tr>
<tr valign="top">
	<td colspan="2" valign="top" align="left">
    <h3>Forgot Password</h3>
    <?php
			if(isset($forgot_pwd)){
				echo '<div class="success">New Password has sent to your email.</div>';
			}
		?>
   <form method="post">
	 <label>Email Id</label>
    <input type="text" name="email" id="email" value="<?php echo $email; ?>"/><?php echo isset($email_error)?'<span class="error">'.$email_error.'</span>':''; ?>
    <?php echo isset($mess)?'<span class="error">'.$mess.'</span>':''; ?><br>
     <input type="submit" name="submit" /><a href="login.php" class="link">Back to login</a>
    </form>


	</td>
</tr>
<tr><td colspan="2" width="100%" style="border-bottom:1px solid #bcbcbc">&nbsp;</td></tr>
<tr valign="top">
	<td align="left">&copy; China Max Asia Pacific Limited </td>
	<td align="right"><a href='mailto:cs@cmaphk.com' class='nav'>cs@cmaphk.com</a></td>
</tr>
</table>
</body>
</html>