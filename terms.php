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

</script>
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
					 
					 $termsAndConditionsText1=$row['termsAndConditionsText1'];
					 $termsAndConditionsText1cn=$row['termsAndConditionsText1cn'];

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
<div style='width:700px;line-height:150%'>
  <?php 
	if ($curLang == "cn"){
		echo $termsAndConditionsText1cn;	
	} else {
		echo $termsAndConditionsText1;	
	}
	
	?>




</div>
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
