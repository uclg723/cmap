<?php
include('include/functions.php');
include_once "lang/lang.php";
session_start();
$success_view=0;
if(isset($_SESSION['id']))
{
	$user_login = true;
}

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
$(document).ready(function() {
	$('.slideshow').cycle({
	fx: 'fade',
	pause: 1
	});
});
</script>
<style type="text/css">
body{background:url(images/bg.jpg) #f0f0f0 repeat-x;}
.bg-table {background:url(images/home_background) #f0f0f0 repeat-x;}
.three-col {
	width:280px;
	height: 200px;
	display:inline-block;
	overflow:hidden;
	float:left;
	margin-top:10px;
}
.three-col img {max-width:100%;min-width:100%; height:auto;}
</style>


</head>
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

					 $homeText1=$row['homeText1'];
					 $homeText2=$row['homeText2'];
					 $homeText1cn= $row['homeText1cn'];
					 $homeText2cn= $row['homeText2cn'];
					}
				}
				
				close();
	 ?>

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
	<td colspan="2" width="100%" style="border-bottom:1px solid #bcbcbc">
	<table width="98%" cellpadding="0" cellspacing="0">
<tr>
	<td><a href="home.php" class="nav"><?php echo MENU_HOME; ?> </a></td>
	<td width="50px"></td>
	<td><a href="about_us.php" class="nav"><?php echo MENU_ABOUT_US; ?> </a></td>
	<td width="50px"></td>
	<td><a href="price.php" class="nav"><?php echo MENU_ONLINE_RESERVATION; ?> </a></td>
	<td width="50px"></td>
	<td><a href="terms.php" class="nav"><?php echo MENU_TERMS_AND_CONDITIONS; ?> </a></td>
	<td width="50px"></td>
	<td><a href="contact_us.php" class="nav"><?php echo MENU_CONTACT_US; ?> </a></td>
     <td width="50px"></td>
	<?php if(isset($user_login)){?>
      <td><a href="myaccount.php" class="nav"><?php echo MENU_MY_ACCOUNT; ?> </a></td>
      <td width="50px"></td>
       <!--<td><a href="rooms.php" class="nav">Booking</a></td>-->    
       <!--<td width="50px"></td>-->
	<td><a href="logout.php" class="nav"><?php echo MENU_LOGOUT; ?> </a></td>
    <?php }else{ ?>
		<td><a href="signup.php" class="nav"><?php echo MENU_SIGN_UP; ?> </a></td>
        <td width="50px"></td>
	    <td><a href="login.php" class="nav"><?php echo MENU_LOGIN; ?> </a></td>
     <?php }?>
</tr>
</table>
	</td>
</tr>
<tr valign="top">
	<td colspan="2" valign="top" align="left">
<div class='bg-table'>
<table class='bg-table' cellspacing='0' cellpadding='0' width='100%' border='0' style=''>
<tr valign='top'>
	<td width='310px' height='286px' style='background:url(images/search.jpg)'>
	<div style='color:#FFFFFF;margin:20px; display: block; padding: 5px; text-align: center;'>

	<?php 
	if ($curLang == "cn"){
		echo $homeText1cn;	
	} else {
		echo $homeText1;	
	}
	
	?>

	</div>
	</td>
	<td width='600px' height='286px'>
	<div class='slideshow'>
		<img src="images/slide1.jpg" height="286" width="600px" />
		<img src="images/slide2.jpg" height="286" width="600px" />
		<img src="images/slide3.jpg" height="286" width="600px" />
	</div>
	</td>
</tr>
</table>
<div>
	<div class='three-col'><img src='images/home1.jpg' width="270px;" height="180" border="0" style='' /></div>	
	<div class='three-col' style="width:330px;margin-left:9px;">
		<div style='font-size:12px;display:inline-block;text-align:center;border:1px black solid; padding: 10px;height:164px; width:307px;' >
		  
          	<?php 
	if ($curLang == "cn"){
		echo $homeText2cn;	
	} else {
		echo $homeText2;	
	}
	
	?>
          
          
		</div>	
	</div>
	<div class='three-col' style="text-align:right;float:right;"><img src='images/home2.jpg' width="270" height="180" border="0" style='' /></div>
	<br>
	<div style='display:block;float:right;clear:both;'>
		<a href="price.php" style="margin-top:100px;">
			<img src=
			<?php 
			if ($curLang == "cn"){
				echo "'images/unionpay.png' width='240px' height='58px'";
			} else {
				echo "'images/paypal.jpg' width='200px' height='104px'";	
			}

			?> style='display:block; margin-top: 10px; margin-left: auto; margin-right:auto;'
            >
			</a>
	</div>
	
</div>
	</td>
</tr>
<tr><td colspan="2" width="100%" style="border-bottom:1px solid #bcbcbc">&nbsp;</td></tr>
<tr valign="top">
  <td align="left">&copy; <?PHP echo $companyName;?></td>
  <td align="right"><a href='mailto:<?PHP echo $companyEmail;?>' class='nav'><?PHP echo $companyEmail;?></a></td>
</tr>
</table>
</div>
</body>
</html>
