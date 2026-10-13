<?php 
include('include/functions.php');
include('include/country_code.php');
session_start();
if(!isset($_SESSION['id']))
{
	header('LOCATION:login.php');
}
if(isset($_SESSION['acc_updated'])){
	$acc_updated=true;
	unset($_SESSION['acc_updated']);
}
if(isset($_SESSION['id']))
{
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
		}else if(strlen($phone)<7 || strlen($phone)>10){
			$phone_error = "Min length should be 7 Max length should be 10";
			$error++;
		}else if(preg_match("/^[0-9]+$/", $phone) === 0){
			$phone_error = "Invalid Phone number";
			$error++;
		}
		
		$passport = trim($_POST['passport']);
		if(empty($passport)){
			$passport_error = "Enter your Passport No / Travel ID";
			$error++;			
		}else if(strlen($passport)<10 || strlen($passport)>30){
			$passport_error = "Min length should be 10 Max length should be 30";
			$error++;
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

function draw_calendar($month,$year){

	/* draw table */
	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

	/* table headings */
	$headings = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
	$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar.= '<tr class="calendar-row">';

	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
		if($list_day<10){
			$lday="0$list_day";
		}else{
			$lday = $list_day;
		}
		if($month<10){
			$lmonth="0$month";
		}else{
			$lmonth = $month;
		}
		
		$cdate = $lday."-".$lmonth."-".$year;
		$cdate = strtotime($cdate);
		//echo date("d-m-Y",$cdate)."<br>";
		$tdate = strtotime(date("d-m-Y"));
		if($cdate<$tdate){
			$calendar.= '<td class="calendar-day past">';
		}else{
			$calendar.= '<td class="calendar-day available">';
		}
			/* add in the day number */
			$calendar.= '<div class="day-number">'.$list_day.'</div>';

			/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
			//$calendar.= str_repeat('<p> </p>',2);
			
		$calendar.= '</td>';
		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np"> </td>';
		endfor;
	endif;

	/* final row */
	$calendar.= '</tr>';

	/* end the table */
	$calendar.= '</table>';
	
	/* all done, return result */
	return $calendar;
}
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
#room-title {
	font-size: 36px;
	font-weight: bold;
}
#left {
	width: 581px;
	float: left;
	margin-top: 15px;
	border: 1px solid #dbdbdb;
}
#right {
	width: 303px;
	float: right;
	margin-top: 15px;
	border: 1px solid #dbdbdb;
}
.btitle {
	background: #E3E2E2;
	padding: 10px 0 5px 10px;
	color: #393c3d;
	border-bottom: 1px solid #dbdbdb;
}
#calendar {
	padding: 10px;
	font-size: 12px;
}
#month-dropdown {
	margin-bottom: 10px;
}
#mcalendar {
	float: left;
	width: 330px;
}
#mcalendar-desc {
	float: left;
	width: 220px;
	margin-left: 10px;
	font-size: 12px;
}
/* calendar */
table.calendar {
	border-left: 1px solid #999;
}
tr.calendar-row {
}
td.calendar-day {
	min-height: 80px;
	font-size: 11px;
	position: relative;
}
* html div.calendar-day {
	height: 80px;
}
td.calendar-day-np {
	background: #eee;
	min-height: 80px;
}
* html div.calendar-day-np {
	height: 80px;
}
td.calendar-day-head {
	background: #ccc;
	font-weight: bold;
	text-align: center;
	padding: 5px 10px;
	border-bottom: 1px solid #999;
	border-top: 1px solid #999;
	border-right: 1px solid #999;
	font-size: 14px;
}
div.day-number {
	color: #000;
	font-weight: bold;
	float: right;
	text-align: center;
}
/* shared */
td.calendar-day, td.calendar-day-np {
	border-bottom: 1px solid #999;
	border-right: 1px solid #999;
	padding: 5px 10px;
}
.available {
	background: #acdba8;
	cursor: pointer;
}
.past {
	background: #e1e1e1;
}
.clear {
	float: none;
	clear: both;
}
#legend {
	margin-top: 10px;
}
.key {
	width: 30px;
	margin-right: 5px;
	float: left;
}
.key-text {
	margin-right: 10px;
	float: left;
}
.unavailable {
	background-color: #e07272;
}
.in_the_past {
	background-color: #e1e1e1;
}
.booking-content {
	padding: 10px;
}
#ui-datepicker-div {
	font-size: 12px;
}
.col-6 {
	float: left;
	width: 106px;
	margin: 10px 0;
}
.col-3 {
	float: left;
	width: 59px;
	margin: 10px 0;
}
#number_of_guests {
	padding: 6px 9px;
	width: 73px;
	border-radius: 5px;
	border: 1px solid #ccc;
}
.col-6 input[type=text] {
	padding: 6px 9px;
	width: 73px;
	border-radius: 5px;
	border: 1px solid #ccc;
}
#book-type-details {
	padding-bottom: 10px;
	margin-bottom: 10px;
	border-bottom: 1px solid #ccc;
}
.book-bt{
	background: #27b8f6;
	width: 250px;
	height: 41px;
	border: none;
	margin-top: 15px;
	color: #fff;
	font-size: 16px;
	border-radius: 3px;
	cursor:pointer;	
}
</style>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <script>
  $(function() {
    	$( "#check-in" ).datepicker();
		$( "#check-out" ).datepicker();
		
  });
  </script>

	<!-- Add jQuery library -->
	<!--<script type="text/javascript" src="fancybox/lib/jquery-1.10.1.min.js"></script>-->

	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="fancybox/source/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
	<script type="text/javascript" src="fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>


	<script type="text/javascript">
		$(document).ready(function() {
			

			$('.fancybox-thumbs').fancybox({
				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,
				arrows    : false,
				nextClick : true,

				helpers : {
					thumbs : {
						width  : 50,
						height : 50
					}
				}
			});
									

		});
	</script>  
  </head>
  <body bgcolor="#F0F0F0">
  <table width="930px" cellspacing="5" cellpadding="5" style="margin-left:auto; margin-right:auto" border="0">
    <tr valign="middle">
      <td align="left"><a href='home.php' title='China Max Asia Pacific Limited '><img src="images/logo.png" width="250px"></a></td>
      <td align="right"><img src="images/phone_icon.jpg"> <span style="font-size:22px;color:#53181a">(852) 9700 6180</span><br>
        Call for Enquiry Now <br>
        <br>
        <!--<a href="" class="nav">Eng</a> | <a href="" class="nav">中文 </a>--></td>
    </tr>
    <tr valign="top">
      <td colspan="2" width="100%" style="border-bottom:1px solid #bcbcbc"><table cellspacing="0" cellpadding="0">
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
          <td><a href="myaccount.php" class="nav">My Account </a></td>
          <td width="50px"></td>
          <td><a href="logout.php" class="nav">Logout </a></td>
        </tr>
        </table></td>
    </tr>
    <tr valign="top">
      <td colspan="2" valign="top" align="left"><div id="booking">
          <div id="room-title">MKEOC18ST</div>
          <div id="room-desc">Location at Momkok Studio In-room facilities: 40</div>
          <div id="left">
          <div class="btitle">Calendar</div>
          <div id="calendar">
              <div id="mcalendar">
              <div id="month-dropdown"> Select Month:
                  <select class="mdropdown" id="mdropdown" name="mdropdown">
                  <option value="1">February 2014</option>
                  <option value="2">March 2014</option>
                  <option value="2">April 2014</option>
                </select>
                </div>
                <b>February 2014</b>
              <?php echo draw_calendar(2,2014); ?>
              <br>
              <b>March 2014</b>
              <?php echo draw_calendar(3,2014); ?>
              <div id="legend">
                  <div class="available key">&nbsp;</div>
                  <div class="key-text">Available</div>
                  <div class="unavailable key">&nbsp;</div>
                  <div class="key-text">Unavailable</div>
                  <div class="in_the_past key">&nbsp;</div>
                  <div class="key-text">Past</div>
                </div>
            </div>
              <div id="mcalendar-desc">
              <p>The calendar is updated every five minutes and is only an approximation of availability.</p>
              <p>Some hosts set custom pricing for certain days on their calendar, like weekends or holidays. The rates listed are per day and do not include any cleaning fee or rates for extra people the host may have for this listing. Please refer to the listing's Description tab for more details.</p>
              <p>We suggest that you contact the host to confirm availability and rates before submitting a reservation request.</p>
            </div>
              <div class="clear"></div>
            </div>
            <div id="gallery" style="margin-top:25px; padding:10px;">
            	<a class="fancybox-thumbs" data-fancybox-group="thumb" href="admin/uploads/10/dsc_0246.jpg"><img src="admin/uploads/10/dsc_0246.jpg" alt="" style="width:150px;" /></a>
                <a class="fancybox-thumbs" data-fancybox-group="thumb" href="admin/uploads/10/dsc_0244.jpg"><img src="admin/uploads/10/dsc_0244.jpg" alt="" style="width:150px;" /></a>
                <a class="fancybox-thumbs" data-fancybox-group="thumb" href="admin/uploads/10/dsc_0264.jpg"><img src="admin/uploads/10/dsc_0264.jpg" alt="" style="width:150px;" /></a>
                <a class="fancybox-thumbs" data-fancybox-group="thumb" href="admin/uploads/10/img_0330.jpg"><img src="admin/uploads/10/img_0330.jpg" alt="" style="width:150px;" /></a>
                <a class="fancybox-thumbs" data-fancybox-group="thumb" href="admin/uploads/10/img_0331.jpg"><img src="admin/uploads/10/img_0331.jpg" alt="" style="width:150px;" /></a>
            </div>
        </div>
          <div id="right">
          <div class="btitle">Book</div>
          <div class="booking-content">
              <div id="book-type-details"> Booking Type:
              <select class="booking_type" id="booking_type" name="booking_type">
                  <option value="1">Per Night</option>
                  <option value="2">Per Week</option>
                  <option value="2">Per Month</option>
                </select>
              <div id="type-amt" style="display:inline-block;">$50 USD</div>
            </div>
              <div class="row row-condensed" style="margin-bottom:10px; border-bottom:1px solid #ccc;">
              <div class="col-9">
                  <div class="row row-condensed">
                  <div class="col-6">
                      <label for="checkin"> Check in </label>
                      <input class="checkin ui-datepicker-target" id="check-in" name="checkin" placeholder="mm/dd/yyyy" type="text">
                    </div>
                  <div class="col-6">
                      <label for="checkout"> Check out </label>
                      <input class="checkout ui-datepicker-target" id="check-out" name="checkout" placeholder="mm/dd/yyyy" type="text">
                    </div>
                  <div class="col-3">
                      <label for="number_of_guests"> Guests </label>
                      <div class="select">
                      <select class="input-tiny" id="number_of_guests" name="number_of_guests" onchange="Airbnb.refreshSubTotal();">
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>                          
                        </select>
                    </div>
                    </div>
                    <div class="clear"></div>
                </div>
                </div>
            </div>
              <div id="sub-total" style="background:#fff; padding:20px;">
                  <p style="font-weight:bold;margin:0">Subtotal</p>
                  <div id="sub-amt" style="font-size:36px;">$1324</div>
                  <input type="submit" value="Book it" class="book-bt">                
            </div>
            </div>
        </div>
        </div></td>
    </tr>
    <tr>
      <td colspan="2" width="100%" style="border-bottom:1px solid #bcbcbc">&nbsp;</td>
    </tr>
    <tr valign="top">
      <td align="left">&copy; China Max Asia Pacific Limited </td>
      <td align="right"><a href='mailto:cs@cmaphk.com' class='nav'>cs@cmaphk.com</a></td>
    </tr>
  </table>
</body>
</html>