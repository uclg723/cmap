<?php
include('include/functions.php');
include_once "lang/lang.php";

session_start();
$success_view=0;
if(isset($_SESSION['id']))
{
	$user_login = true;
}


// Page variables
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


// rooms
$SQL = "SELECT * FROM rooms WHERE status=2";
$query = mysql_query($SQL);
$nor=mysql_num_rows($query);

?>
<!DOCTYPE html>
<html>
	<head>
	<meta charset="utf-8" />
	<meta name="viewport" />
	<title>Hotel - Service Apartment - China Max Asia Pacific Limited</title>
	<link type="text/css" href="include/style.css" rel="stylesheet"/>
	<script type='text/javascript' src='include/jquery.min.js'></script>
	<script type='text/javascript' src='include/jquery.cycle.all.min.js'></script>
	<script type="text/javascript">
//top.document.title='Hotel - Service Apartment - China Max Asia Pacific Company Limited ';
</script>
	<style type="text/css">
.container {
	margin: 0 auto;
	width: 960px;
}
.pricing-table {
	-webkit-box-shadow: 0px 0px 3px #26292e;
	box-shadow: 0px 0px 3px #26292e;
	/*border: 1px solid #26292e;*/	
	display: inline-block;
	margin: 10px 10px;
	width: 450px;
	/*height: 470px;*/
}
.featured {
	-webkit-transform: scale(1.1, 1.1);
	-moz-transform: scale(1.1, 1.1);
	-ms-transform: scale(1.1, 1.1);
	-o-transform: scale(1.1, 1.1);
	transform: scale(1.1, 1.1);
}
.pricing-table-header {
	height:35px;
	background: #65707f; /* Old browsers */
	background: -moz-linear-gradient(top, #65707f 0%, #4a5564 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #65707f), color-stop(100%, #4a5564)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #65707f 0%, #4a5564 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #65707f 0%, #4a5564 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #65707f 0%, #4a5564 100%); /* IE10+ */
	background: linear-gradient(to bottom, #65707f 0%, #4a5564 100%); /* W3C */
 filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#65707f', endColorstr='#4a5564', GradientType=0 ); /* IE6-9 */
	background-color: #586272;
	padding: 16px;
	text-align: center;
}
.pricing-table-header h1 {
	color: #fff;
	font-size: 14px;
	text-transform: uppercase;
}
.pricing-table-content {
	background-color: #fff;
	padding: 15px;
	font-size:14px;
	height: 169px;
	overflow:hidden;
}
.pricing-table-content img{
	margin-bottom:10px;
}
.pricing-table-content ul {
	list-style: none;
}
.pricing-table-content ul li {
	border-bottom: 1px solid #efeff0;
	font-size: 14px;
	/*padding: 16px 56px;*/
}
.pricing-table-footer {
	background-color: #f5f7f8;
	padding: 16px 0;
}
.pricing-table-footer {
	padding:15px;
}
.pricing-table-footer p {
	font-size: 12px;
	text-transform: uppercase;
}
.pricing-table-footer h2{
	font-size:16px;
}
.pricing-table-footer a{
	float:right;
}
.pricing-table-footer ul{
	list-style:none;
	padding:0;
	margin:0
}
.pricing-table-footer a {
	background: #50b7e4; /* Old browsers */
	background: -moz-linear-gradient(top, #50b7e4 0%, #3098c4 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #50b7e4), color-stop(100%, #3098c4)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #50b7e4 0%, #3098c4 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #50b7e4 0%, #3098c4 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #50b7e4 0%, #3098c4 100%); /* IE10+ */
	background: linear-gradient(to bottom, #50b7e4 0%, #3098c4 100%); /* W3C */
 filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#50b7e4', endColorstr='#3098c4', GradientType=0 ); /* IE6-9 */
	background-color: #3ea6d2;
	border: 1px solid #1481b0;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	color: #fff;
	display: inline-block;
	font-weight: bold;
	margin-top: 10px;
	padding: 10px;
	text-decoration: none;
}
.price-img{
	float:left;
	width:150px;
	margin-right:20px;
}
.price-info{
	float:left;
	width:250px;
}
.clear{
	float:none;
	clear:both;
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
        <td colspan="2" valign="top" align="left"><div id="rooms">
         <div class="container">
        	<?php
				if(isset($query) and $nor>0){
					$slno=0;
				while($row = mysql_fetch_array($query))
				  {
					   $search_dir = "admin/uploads/{$row['id']}/thumb";
						$images = glob("$search_dir/*.jpg");
						sort($images);											
						if (count($images) > 0) { // make sure at least one image exists
							$img = $images[0]; // first image
							$img = "<img src='$img' /> ";
						}
					  ?>
           
            <div class="pricing-table">
                <div class="pricing-table-header">
                <h1><?php echo $row['room_name']; ?></h1>
              </div>
                <div class="pricing-table-content">
               		<div class="price-img"><?php echo $img; ?></div>
                    <div class="price-info">
						<?php echo '<p><strong>'.$row['sq_feet'].'</strong> Square feet</p>'; ?>
                        <?php 
						if ($curLang == "cn") $desc = $row['descriptioncn'];
						else $desc = $row['description'];
						echo substr($desc,0,300); 
						?>...
                    </div>
                    <div class="clear"></div>
              </div>
                <div class="pricing-table-footer">  
                <a href="booking.php?id=<?php echo $row['id']; ?>"><?PHP echo PRICE_BOOK;?></a>   
                	<ul>
                    	<li><h2><?php echo PRICE_PERDAY . " $" . $row['damount'];?> HKD</h2></li>
                        <li><h2><?php echo PRICE_PERWEEK . " $" . $row['wamount'];?> HKD</h2></li>
                        <li><h2><?php echo PRICE_PERMONTH . " $" . $row['mamount'];?> HKD</h2></li>
                    </ul>            
                
                <div class="clear"></div>
                </div>
              </div>                        
         
          <?php } } ?>
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
