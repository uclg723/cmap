<?php
session_start();
$success_view=0;
if(isset($_SESSION['email']) && isset($_SESSION['password']))
{
	$success_view=1;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv='refresh' content="0; url=home.php">
<meta charset="utf-8" />
<meta name="viewport" />
<title>Hotel - Service Apartment - China Max Asia Pacific Limited </title>
<link type="text/css" href="include/style.css" rel="stylesheet"/>
<script type='text/javascript' src='include/jquery.min.js'></script>
<script type='text/javascript' src='include/jquery.cycle.all.min.js'></script>

<script type="text/javascript">
//top.document.title='Hotel - Service Apartment - China Max Asia Pacific Company Limited ';
$(document).ready(function() {
	$('.slideshow').cycle({
	fx: 'fade',
	pause: 1
	});
});
</script>
</head>
<body bgcolor="#F0F0F0">
<table width="930px" cellspacing="5" cellpadding="5" style="margin-left:auto; margin-right:auto" border="0">
<tr valign="middle">
	<td align="left"><a href='home.php' title='China Max Asia Pacific Limited '><img src="images/logo.png" width="250px"></a></td>
	<td align="right">
		<img src="images/phone_icon.jpg">
		<span style="font-size:22px;color:#53181a">(852) 9700 6180</span><br>
		Call for Enquiry Now <br><br>
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
	<?php if($success_view==1){?>
      <td><a href="myaccount.php" class="nav">My Account </a></td>
     <td width="50px"></td>
	<td><a href="logout.php" class="nav">Logout </a></td>
    <?php }else{ ?>
		<td><a href="signup.php" class="nav">Sign Up </a></td>
        <td width="50px"></td>
	    <td><a href="login.php" class="nav">Login </a></td>
		
     <?php }?>
</tr>
</table>
	</td>
</tr>
<tr valign="top">
	<td colspan="2" valign="top" align="left">
	<style type="text/css">
body{background:url(images/bg.gif) #f0f0f0 repeat-x;}
</style>
<table cellspacing='0' cellpadding='0' width='100%' border='0'>
<tr valign='top'>
	<td width='310px' height='286px' style='background:url(images/search.jpg)'>
	<div style='color:#FFFFFF;margin:20px'>
	Hotel - Service Apartment - China Max Asia Pacific Limited 
	</div>
	</td>
	<td width='600px' height='286px'>
	<div class='slideshow'>
		<img src="images/slide1.jpg" width="600px" />
		<img src="images/slide2.jpg" width="600px" />
		<img src="images/slide3.jpg" width="600px" />
	</div>
	</td>
</tr>
</table>
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
