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
<meta charset="utf-8" />
<meta name="viewport" />
<title>Hotel - Service Apartment - China Max Asia Pacific Company Limited </title>
<link type="text/css" href="include/style.css" rel="stylesheet"/>
<script type='text/javascript' src='include/jquery.min.js'></script>
<script type='text/javascript' src='include/jquery.cycle.all.min.js'></script>

<script type="text/javascript">

</script>
</head>
<body bgcolor="#F0F0F0">
<table width="930px" cellspacing="5" cellpadding="5" style="margin-left:auto; margin-right:auto" border="0">
<tr valign="middle">
	<td align="left"><a href='home.php' title='China Max Asia Pacific Limited '><img src="images/logo.png" width="250px"></a></td>
	<td align="right">
		<img src="images/phone_icon.jpg">
		<span style="font-size:22px;color:#53181a">(852) 8170 3484</span><br>
		Call for Enquiry Now <br><br>
<a href="?lang=en" class="nav">Eng</a>
|
<a href="?lang=big5" class="nav">中文 </a>
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
	<td><a href="price.php" class="nav">Price </a></td>
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
	<div style='width:700px;line-height:150%'>
<h1>Conditions of Use</h1>
A. Check-in Notice
<ol>
<li>Room Check-in time is 14:00. Check-out time is 12:00. In case of late Check-out, please notice our staff to handle the relevant procedures. Final Check-out time should be in accordance with the rooms status and vacancy.</li>
<li>Please check-in before you reach the service apartments. Please show your passport or identity card and inform us which booking have been made. The staff will confirm your arrival time and name, then provide you with the room key.</li>
<li>Check-out time is the next day 12:00. Late check-out will be charges as following:  After 12:00 will be charged half the daily price. If after 18:00, we will charge customer a whole day price. Please notice us in advance for the late Check-out.</li>
<li>Please keep your valuable belongings into the safe we provided. We are not responsible for any lost.</li>
<li>Deposit should be made when Check-in. The rates of deposit are accordance with the room type. The deposit will be used as the settlement of the room fee and other consumers when you Check-out.</li>
<li>After Check-out, please return us the room key card. Any lost of the key will be charged in accordance with the terms and conditions.
</ol>
B. Regulations of Accommodation<br>
<ol>
We hope the customers follow the rules. In case of violation, the hotel will reserve the right to terminate the offenders to stay. If there is fire, injury accident and etc caused by the violation of the regulations, the customer is solely responsibile for all economic losses and liabilities caused.
<li>When check in, please present your passport, identity card and valid documents for the registration.</li>
<li>Please take good care of the valuable belongings. We are not responsible for any lost.</li>
<li>If there is visit of relatives, friends, colleagues or customers during stay, please notice our staff in advance for the visit registration.</li>
<li>Flammable, explosive, toxic, radioactive are strictly prohibited. Please minimize disturbing noise and do not carry pets into the hotel.</li>
<li>Do not smoke in bed and throw cigarette butts. Please put ash and butts into the ashtray. Candles and other flammable are prohibited. Do not spitting, littering, throwing in the corridor and room. There are trash bin in each floor and in room.</li>
<li>Burning of item and paper are prohibited in the hotel.</li>
<li>Do not install any electrical appliances in room without the hotel permission.</li>
<li>Do not advertising, taking commercial photographs or taking any commercial actions without the hotel permission.</li>
<li>Prostitution, the sale of narcotics, drugs, smuggling, gambling, fighting or other illegal and criminal activities are strictly prohibited.</li>
<li>In order not to affect other guests, please minimize the noise and any annoying activities in the hotel.</li>
<li>Please take good care of the properties of the hotel. Do not draw in the lobby, corridors, elevators, on the room walls and etc. Do not move the furniture and equipment in the room except for specific usage.</li>
<li>For your safety, please do not wash clothes in the bathroom and walk with barefoot. For washing, please use the self-service facility provided.</li>
<li>In case of damage, lost or defaced found in room equipment, facilities and etc, you will be required to compensate. Please check the room facilites when check-in, report the abnormal to our staff as soon as possible.</li>
<li>When check-out, the room will be clean and checked. Upon discovery of the matters mentioned above, the hotel will charge you according to the terms and conditions . In severe case, we will ask the police for help.</li>
</ol>
</div>
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
