<?php 
session_start();

include('include/functions.php');
include_once "lang/lang.php";
date_default_timezone_set("Asia/Calcutta");


function getAllDatesBetweenTwoDates($strDateFrom,$strDateTo)
{
    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

    if ($iDateTo>=$iDateFrom)
    {
        array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange,date('Y-m-d',$iDateFrom));
        }
    }
    return $aryRange;
}

if(!isset($_SESSION['id']))
{
	// not login yet. ($_GET['id'] is the room id )
	$_SESSION['login_back_url'] = 'booking.php?id=' . $_REQUEST['id'];
	header('LOCATION:login.php');
}
if(!isset($_GET['id'])){
	header('LOCATION:price.php');
}

$uid = $_SESSION['id'];

// Get system variables
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
	 
	 $onlineReservationText1=$row['onlineReservationText1'];
	 $onlineReservationText1cn=$row['onlineReservationText1cn'];

	}
}
				
				


//connect();
$rid = sanitize($_GET['id']);
$SQL = "SELECT * FROM rooms WHERE id=".$rid." and status=2";
$query = mysql_query($SQL);
$nor=mysql_num_rows($query);
if(!$query or $nor!=1){
	header('LOCATION:price.php');
}else{
	$room_data = mysql_fetch_assoc($query);
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

if(isset($_POST['bfnon'])){
	
	
	if(empty($_POST['bf_from'])){
		$error="Select From and To Date";
	}else if(empty($_POST['bf_to'])){
		$error="Select From and To Date";
	}else{
		$from = strtotime($_POST['bf_from']);
		$to = strtotime($_POST['bf_to']);
		//$to = strtotime("03/2/2014");
		if($from>=$to){
			$error="Invalid Booking dates";
		}else{
			 $datediff = $to - $from;
		     $nod = floor($datediff/(60*60*24));
			 $month = 0;
			 $week = 0;
			 $days = 0;
			 if($nod>=30){				
				 $month = intval($nod/30);
				 $modulo = intval($nod%30);
				 
				 if($modulo>=7){
					 $week = intval($modulo/7);
					 $days = intval($modulo%7);
				 }else{
					 $days = $modulo;
				 }
			 }else if($nod>=7){
				 $week = intval($nod/7);
				 $days = intval($nod%7);
			 }else{
				 $days = $nod;
			 }
			 
			 $month = $month*$room_data['mamount'];
			 $week = $week*$room_data['wamount'];
			 $days = $days*$room_data['damount'];
			 
			 $total = $month+$week+$days;
			 
			 $from = date("Y-m-d",$from);
			 $to = date("Y-m-d",$to);	
			 
			 $dateArray = getAllDatesBetweenTwoDates($from, $to);
			 connect();
			 foreach($dateArray as $date){
				$class="";
				$dsql = 'SELECT * FROM booking WHERE rid='.$rid.' and "'.$date.'" between date_in and date_out';	
						
				$dquery = mysql_query($dsql);
				$nor = mysql_num_rows($dquery);
				$class = "available";
				if($nor>1){
					while($row= mysql_fetch_assoc($dquery)){
						$check_out[] = $row['date_out'];
						$check_in[] = $row['date_in'];
					}					
					if (in_array($date, $check_out)) {
						$class = "available";
					}
					if (in_array($date, $check_in)) {
						$class = "unavailable";
					}				
				}else{
					if($nor==1){
						$book_data = mysql_fetch_assoc($dquery);
						if($book_data['date_out']==$date){
							$class = "available";
						}else{
							if($book_data['date_in']==$date){
								$class = "available";
							}else{
								$class = "unavailable";
							}
						}
					}
				}
				
				if($class=="unavailable"){
					$booking_error = TRUE;
				}
				
				//echo $dsql." ".$nor." ".$class."<br>";	
			}
			
			if(!isset($booking_error)){	
				 if($_POST['bfcc']=="1"){
					  $currency = "HKD";
				 }else if($_POST['bfcc']=="2"){
					 $hkd = floatval(convertCurrency(1, "HKD", "USD"));
					 $total = $total*$hkd;
					 $total = round($total);
					 $currency = "USD";	
					 $gateway = "paypal";				 
				 }else{
					 $cny = floatval(convertCurrency(1, "HKD", "CNY"));
					 $total = $total*$cny;
					 $total = round($total);
					 $currency = "RMB";
					 $gateway = "unipay";						 
				 }
						
				 $sql = "INSERT INTO paypal_booking (room,UID,from_date,to_date,amt,currency,status,gateway) VALUES($rid,$uid,'".$from."','".$to."','".$total."','$currency',2,'".$gateway."')";
				 $query = mysql_query($sql);
				 if($query){
					 $_SESSION['tid'] = mysql_insert_id();
					 if ($gateway == "unipay") {header("Location: unipay.php");}
					 else {header("Location: payment.php");}
				 }else{
					 header("Location: logout.php");
				 }
			}
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
		$check_from = date("Y-m-d",$cdate);
		$lday =  date("D",$cdate);
		global $rid;
		connect();
		$dsql = 'SELECT * FROM booking WHERE rid='.$rid.' and "'.$check_from.'" between date_in and date_out';		
		$dquery = mysql_query($dsql);
		$nor = mysql_num_rows($dquery);
		$class = "";
		if($nor>0){
			if($nor>1){
				while($row= mysql_fetch_assoc($dquery)){
					$check_out[] = $row['date_out'];
					$check_in[] = $row['date_in'];
				}				
				if (in_array($check_from, $check_out)) {
					$class = "available";
				}
				if (in_array($check_from, $check_in)) {
					$class = "unavailable";
				}								
			}else{
				$book_data = mysql_fetch_assoc($dquery);
				if($book_data['date_out']==$check_from){
					$class = "available";
				}else{
						$class = "unavailable";
					}
			}
		}		
		if($cdate<$tdate){
			$calendar.= '<td class="calendar-day past '.$class.'">';
		}else{
			$calendar.= '<td class="calendar-day available '.$class.'">';
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
	if($lday!="Sat"){
		if($days_in_this_week < 8):
			for($x = 1; $x <= (8 - $days_in_this_week); $x++):
				$calendar.= '<td class="calendar-day-np"> </td>';
			endfor;
		endif;
	}

	/* final row */
	$calendar.= '</tr>';

	/* end the table */
	$calendar.= '</table>';
	
	/* all done, return result */
	return $calendar;
}

if(isset($booking_error)){
	$booking_error="Booking dates are not available";
}
if(isset($_SESSION['booking'])){
	$booking_cancelled = $_SESSION['booking'];
	unset($_SESSION['booking']);
}

if ($curLang != "cn") $hkd = floatval(convertCurrency(1, "HKD", "USD"));
if ($curLang == "cn") $cny = floatval(convertCurrency(1, "HKD", "CNY"));
?>

<!DOCTYPE html>
<html>
  <head>
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
	/*width: 330px;*/
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
	font-size: 11px;
	position: relative;
}
td.calendar-day-np {
	background: #eee;
}
td.calendar-day-head {
	background: #ccc;
	font-weight: bold;
	text-align: center;
	padding: 10px 25px;
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
	padding: 10px 10px;
	height:15px;
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
#gallery a{	
	margin-right:10px;
	display:inline-block;
	background:#fff;
	padding:5px;
	margin-bottom:5px;
	border:1px solid #ccc;
	border-radius:5px;
}
#errors{
	display:none;
	background:#e07272;
	padding:10px 10px;
	color:#fff;
	font-size:12px;
	margin-top:10px;
	border-top:1px solid #ccc;
}
#berror{
	color:#F00;
	font-size:26px;
	display:inline-block;
	float:right;
	line-height:44px;
}
.booking_cancelled{
	padding:10px;
	background:#FF7575;
	color:#FFF;
	margin:5px 0;
}
.booking_completed{
	padding:10px;
	background:#307D56;
	color:#FFF;
	margin:5px 0;
}
.a{
	color:#060;
	font-weight:bold;
}
.na{
	color:#F00;
	font-weight:bold;	
}
table.gridtable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}
table.gridtable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
}
table.gridtable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}
</style>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <script>
  $(function() {
    	$( "#check-in" ).datepicker(
			{
				changeMonth: true, 
				changeYear: true, 
				minDate: 0 // 0 days offset = today
			}
		);
		
		$( "#check-out" ).datepicker(
			{
				changeMonth: true, 
				changeYear: true, 
				minDate: 0 // 0 days offset = today
			}
		);
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
			
			$("#mdropdown").val($("#active_m").val());			
			
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
      <td colspan="2" valign="top" align="left"><div id="booking">
          <div id="room-title">
		  		<?php echo $room_data['room_name']; ?>
                <?php
					if(isset($booking_error)){
						echo '<span id="berror">'.$booking_error.'</span>';
					}
				?>
          </div>          
          <?php
		  	if(isset($booking_cancelled)){
				if($booking_cancelled!="Completed"){
				echo '<div id="room-desc" class="booking_cancelled">' . PAYMENT_FAILED . ' ' . $booking_cancelled . '</div>';
				}else{
					echo '<div id="room-desc" class="booking_completed">'. PAYMENT_SUCCESS . '</div>';
				}
			}
		  ?>
          <div id="left">
          <div class="btitle"><?PHP echo BOOKING_CALENDAR; ?></div>
          <div id="calendar">
              <div id="mcalendar">
              <div id="month-dropdown"> <?PHP echo BOOKING_SELECT_MONTH; ?>:
              	<?php
				$current_m = date("n");
				$active_m = $current_m;
					/*if(isset($_GET['m'])){
						$cm = date("n");
						$cm = $cm+6;
						if($_GET['m']<=$cm){
							$cm = date("n");
							$current_m = $_GET['m'];
							$current_m = $current_m-$cm;							
							$time = strtotime(date("Y-m-d"));
							$drop='<option value="'.$current_m.'">'.date("F Y", strtotime("+$current_m month", $time)).'</option>';
						}else{
							$current_m = 1;
							$drop='<option value="'.$current_m.'" selected>'.date("F Y").'" selected>'.date("F Y").'</option>';
						}
					}else{
						$current_m = 1;
						$drop='<option value="'.$current_m.'" selected>'.date("F Y").'</option>';
					}*/
					
					if(isset($_GET['m'])){
						$cm = date("n");
						$cm = $cm+6;
						if($_GET['m']<=$cm){
							$cm = date("n");
							$active_m = $_GET['m'];
						}else{
							$active_m = 1;
							$drop='<option value="'.$current_m.'" selected>'.date("F Y").'" selected>'.date("F Y").'</option>';
						}
					}else{
						$active_m = 1;
						$drop='<option value="'.$current_m.'" selected>'.date("F Y").'</option>';
					}

					$drop='<option value="'.$current_m.'" selected>'.date("F Y").'</option>';
					
					$sno=2;					
					for($i=1;$i<6;$i++){
						$m = $i;
						$time = strtotime(date("Y-m-d"));
						$drop.='<option value="'.date("n", strtotime("+$m month", $time)).'">'.date("F Y", strtotime("+$m month", $time)).'</option>';
					}										
				?>
                	<input type="hidden" value="<?php echo $active_m; ?>" id="active_m">
                  <select class="mdropdown" id="mdropdown" name="mdropdown">
                  <?php echo $drop; ?>                
                </select>
                </div>
                <?php if(!isset($_GET['m'])){ ?>
                      <b><?php echo date('F Y'); ?></b>
                      <?php echo draw_calendar(date('n'),date('Y')); ?>
                      <br>
                      <?php
                        $time = strtotime(date("Y-m-d"));
                        $final = date("F Y", strtotime("+1 month", $time));
                        $next_n = date("n", strtotime("+1 month", $time));
                        $next_Y = date("Y", strtotime("+1 month", $time));
                      ?>
                      <b><?php echo $final; ?></b>
                      <?php echo draw_calendar($next_n,$next_Y); ?>
                 <?php } else { ?>
                	<?php
						$cm = date("n");
						$current_m = $_GET['m'];
						$current_m = $current_m-$cm;
						$final = date("F Y", strtotime("+$current_m month", $time));
						$next_n = date("n", strtotime("+$current_m month", $time));
						$next_Y = date("Y", strtotime("+$current_m month", $time));
					 ?>
                 	 <b><?php echo $final; ?></b>                 	
                       <?php echo draw_calendar($next_n,$next_Y); ?>
                      <br>
                      <?php						
						$cm = date("n");
						$current_m = $_GET['m'];
						$current_m = $current_m-$cm;
						$nm = intval($current_m+1);
						$final = date("F Y", strtotime("+$nm month", $time));
						$next_n = date("n", strtotime("+$nm month", $time));
						$next_Y = date("Y", strtotime("+$nm month", $time));
                      ?>
                      <b><?php echo $final; ?></b>
                      <?php echo draw_calendar($next_n,$next_Y); ?>
                 <?php } ?>
              <div id="legend">
                  <div class="available key">&nbsp;</div>
                  <div class="key-text"><?PHP echo BOOKING_AVAILABLE; ?></div>
                  <div class="unavailable key">&nbsp;</div>
                  <div class="key-text"><?PHP echo BOOKING_UNAVAILABLE; ?></div>
                  <div class="in_the_past key">&nbsp;</div>
                  <div class="key-text"><?PHP echo BOOKING_PAST; ?></div>
                </div>
                <div class="clear"></div>
                <div id="desc" style="margin-top:20px;">
	                <?php if ($curLang == "cn") echo nl2br($room_data['descriptioncn']); 
					else echo nl2br($room_data['description']);?>
                    <h2><?PHP echo BOOKING_ADDITION_FEATURES; ?></h2>
              		<?php if ($curLang == "cn") echo nl2br($room_data['add_infocn']); 
					else echo nl2br($room_data['add_info']); ?>
                </div>
                <div>
                	<br>




                </div>
            </div>
              <!--<div id="mcalendar-desc">
              <?php echo nl2br($room_data['description']); ?>
              <h2>Addition Features</h2>
              <?php echo nl2br($room_data['add_info']); ?>
              
              
            </div>-->
              <div class="clear"></div>
            </div>
            <div id="gallery" style="margin-top:25px; padding:10px;">
            <?php
				$search_dir = "admin/uploads/{$room_data['id']}/thumb";
				$images = glob("$search_dir/*.jpg");
				sort($images);											
				if (count($images) > 0) { // make sure at least one image exists
					foreach($images as $img){
						$explode = explode("/",$img);
						$count = count($explode);
						echo '<a class="fancybox-thumbs" data-fancybox-group="thumb" href="'.$base_url.'/admin/uploads/'.$room_data['id'].'/'.$explode[$count-1].'"><img src="'.$base_url.'/admin/uploads/'.$room_data['id'].'/thumb/'.$explode[$count-1].'" /></a>';
					}
				}
			?>
            </div>
        </div>
          <div id="right">
          <div class="btitle"><?PHP echo BOOKING_BOOK; ?></div>
          <div class="booking-content">
              <div id="book-type-details"> <?PHP echo BOOKING_BOOK_TYPE; ?>:
              <input type="hidden" id="per_day" value="<?php if ($curLang == "cn") echo round($room_data['damount']*$cny); else echo $room_data['damount'];?>" >
              <input type="hidden" id="per_week" value="<?php if ($curLang == "cn") echo round($room_data['wamount']*$cny); else echo $room_data['wamount'];?>" >
              <input type="hidden" id="per_month" value="<?php if ($curLang == "cn") echo round($room_data['mamount']*$cny); else echo $room_data['mamount'];?>" >
              <select class="booking_type" id="booking_type" name="booking_type">
                  <option value="1"><?PHP echo PRICE_PERDAY; ?></option>
                  <option value="2"><?PHP echo PRICE_PERWEEK; ?></option>
                  <option value="3"><?PHP echo PRICE_PERMONTH; ?></option>
                </select>
                
              
                <select class="booking_type" id="change_currency" name="change_currency" <?PHP if ($curLang == "cn") echo "disabled";?>>
                  <option value="1">HKD</option>
                  <option value="2">USD</option>
                  <?PHP if($curLang == "cn"){ ?><option value="3" selected>RMB</option><?PHP } ?>
                </select>
             
                
              <div id="type-amt" style="display:block;"><h2>
			  
			  <?PHP 
			  if ($curLang == "cn"){
				echo BOOKING_PRICE . " ¥" . round($room_data['damount']*$cny) . " RMB" ;
			  } else {
			  echo BOOKING_PRICE . " $" . $room_data['damount'] . " HKD" ;
              }?>
              </h2></div>
            </div>
              <div class="row row-condensed" style="margin-bottom:10px; border-bottom:1px solid #ccc;">
              <div class="col-9">
                  <div class="row row-condensed">
                  <div class="col-6">
                      <label for="checkin"> <?PHP echo BOOKING_CHECK_IN; ?> </label>
                      <input class="checkin ui-datepicker-target" id="check-in" name="checkin" placeholder="mm/dd/yyyy" type="text">
                    </div>
                  <div class="col-6">
                      <label for="checkout"> <?PHP echo BOOKING_CHECK_OUT; ?> </label>
                      <input class="checkout ui-datepicker-target" id="check-out" name="checkout" placeholder="mm/dd/yyyy" type="text">
                    </div>
                  <div class="col-3">
                      <label for="number_of_guests"> <?PHP echo BOOKING_GUESTS; ?> </label>
                      <div class="select">
                      <select class="input-tiny" id="number_of_guests" name="number_of_guests">
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>                          
                        </select>
                    </div>
                    </div>
                    <div class="clear">
                     <h2>
                     	<?PHP echo BOOKING_NO_OF_NIGHTS; ?>: <span id="non">0</span>
                     </h2>
                    
                    </div>
                </div>
                </div>
            </div>
              <div id="sub-total" style="background:#fff; padding:20px;">
                  <p style="font-weight:bold;margin:0"><?PHP echo BOOKING_SUBTOTAL; ?></p>
                  <div id="sub-amt" style="font-size:36px;">
                  <?PHP
				  if($curLang == "cn"){echo "¥0 RMB";}
				  else {echo "$0 HKD";}
                  ?>
                  
          </div>
                  <div id="errors"></div>
				  <?PHP 
				  if($curLang == "cn"){echo '<a href="#"><img src="images/unionpaycheckout.png" id="book-btn"></a>';}
				  else {echo '<a href="#"><img src="images/paypalcheckout.png" id="book-btn"></a>';}
				  ?>
                  
                <?php
				  		$bstatus[1] = '<span class="a">' . BOOKING_ALLOW .'</span>'; 
						$bstatus[2] = '<span class="na">' . BOOKING_NOT_ALLOWED .'</span>'; 
				  ?>
            <br>

                  <table class="gridtable">
<tr>
	<th><?PHP echo BOOKING_BOOK_TYPE; ?></th><th><?PHP echo BOOKING_MIN_NO_OF_DAYS; ?></th><th><?PHP echo BOOKING_MAX_NO_OF_DAYS; ?></th><th><?PHP echo BOOKING_STATUS; ?></th>
</tr>
<tr>
	<td><?PHP echo BOOKING_DAILY; ?></td><td>1</td><td>6</td><td><?php echo $bstatus[$room_data['daily_booking']]; ?></td>
</tr>
<tr>
	<td><?PHP echo BOOKING_WEEKLY; ?></td><td>7</td><td>29</td><td><?php echo $bstatus[$room_data['weekly_booking']]; ?></td>
</tr>
<tr>
	<td><?PHP echo BOOKING_MONTHLY; ?></td><td>30</td><td>250</td><td><?php echo $bstatus[$room_data['monthly_booking']]; ?></td>
</tr>
</table>
                  	<!--<p>Daily Booking: <?php echo $bstatus[$room_data['daily_booking']]; ?><br>
                    Weekly Booking: <?php echo $bstatus[$room_data['weekly_booking']]; ?><br>
                    Montly Booking: <?php echo $bstatus[$room_data['monthly_booking']]; ?>  (Minum on 30 days) </p>-->
                   <br>

                  </div>
                  <p>
                  
                  <?php 
					if ($curLang == "cn"){
						echo $onlineReservationText1cn;	
					} else {
						echo $onlineReservationText1;	
					}
					
					?>

                  </p>
</div>
            </div>
        </div>
        </div></td>
    </tr>
    <tr>
      <td colspan="2" width="100%" style="border-bottom:1px solid #bcbcbc">&nbsp;</td>
    </tr>
    <tr valign="top">
      <td align="left">&copy; <?PHP echo $companyName;?></td>
      <td align="right"><a href='mailto:<?PHP echo $companyEmail;?>' class='nav'><?PHP echo $companyEmail;?></a></td>
    </tr>
  </table>
<script>
$( "#booking_type" ).change(function() {
     var type = $(this).val();
	 if(type==1){
		 amt = $("#per_day").val();		 
	 }
	 if(type==2){
		 amt = $("#per_week").val();		 
	 }
	 if(type==3){
		 amt = $("#per_month").val();
	 }
	 <?PHP 
	 if ($curLang == "cn") {
		echo  '$("#type-amt").html(" <h2>' . BOOKING_PRICE . ': ¥ "+amt+" RMB</h2>")';
	 } else {
		echo  '$("#type-amt").html(" <h2>' . BOOKING_PRICE . ': $ "+amt+" HKD</h2>")';
	 }
	 
	 ?>
 	 
});
$( "#check-in" ).change(function() {
	var v1 = document.getElementById('check-in').value;
	var v2 = document.getElementById('check-out').value;
	$("#bf_from").val(v1);
	$("#bf_to").val(v2);
	if(v1!="" && v2!=""){
		var from_date = $("#check-in").datepicker('getDate'),
		to_date   = $("#check-out").datepicker('getDate');
		if(to_date.getTime()<=from_date.getTime()){
			alert("Booking dates not valid");
		}else{
			var parts1 = v1.split('/'); 
			var parts2 = v2.split('/');
			var date1 = new Date(parts1[2],(parts1[0]-1),parts1[1]);
			var date2 = new Date(parts2[2],(parts2[0]-1),parts2[1]);
			var diffDays = Math.round(Math.abs((date1.getTime() - date2.getTime())/(24*3600*1000)));
			$("#non").html(diffDays);						
			cal_amt(diffDays);
		}
	}
});
$( "#check-out" ).change(function() {
	var from_date = $("#check-in").datepicker('getDate'),
     to_date   = $("#check-out").datepicker('getDate');

	var v1 = document.getElementById('check-in').value;
	var v2 = document.getElementById('check-out').value;
	$("#bf_from").val(v1);
	$("#bf_to").val(v2);
	
	if(v1!="" && v2!=""){
		var from_date = $("#check-in").datepicker('getDate'),
		 to_date   = $("#check-out").datepicker('getDate');
		if(to_date.getTime()<=from_date.getTime()){
			alert("Booking dates not valid");
		}else{
			var parts1 = v1.split('/'); 
			var parts2 = v2.split('/');
			var date1 = new Date(parts1[2],(parts1[0]-1),parts1[1]);
			var date2 = new Date(parts2[2],(parts2[0]-1),parts2[1]);
			var diffDays = Math.round(Math.abs((date1.getTime() - date2.getTime())/(24*3600*1000)));
			$("#non").html(diffDays);				
			cal_amt(diffDays);
		}
	}
});
$( "#mdropdown" ).change(function() {
     var month = $(this).val();
	 var current_m = $("#current_m").val();
	 if(month!=current_m){
		 window.location.href='booking.php?id=<?php echo $rid;?>&m='+month;
	 }
});

$( "#change_currency" ).change(function() {
     var cc = $("#change_currency").val();
	 var fcc = $("#bfcc").val();	
	 var hkd = $("#bfhkd").val(); 
	 var cny = $("#bfcny").val();
	 if(cc!=fcc){
		 var type = $("#booking_type").val();
		 if(type==1){
			 amt = $("#per_day").val();		 
		 }
		 if(type==2){
			 amt = $("#per_week").val();		 
		 }
		 if(type==3){
			 amt = $("#per_month").val();
		 }
		 if(cc==2){
			 amt = amt*hkd;
			 amt = Math.round(amt,2);
			 $("#type-amt").html("<h2><?PHP echo BOOKING_PRICE;?>: $"+amt+"  USD</h2>");
		 }else if(cc==3){
			 amt = amt*cny;
			 amt = Math.round(amt,2);
			 $("#type-amt").html("<h2><?PHP echo BOOKING_PRICE;?>: $"+amt+"  RMB</h2>");
		 }else{
			 $("#type-amt").html("<h2><?PHP echo BOOKING_PRICE;?>: $"+amt+"  HKD</h2>");
		 }
		 
		 $("#bfcc").val(cc);
		 
		 var subamt = $("#sub-amt").html();		
		 subamt = subamt.replace("$", "");
		 subamt = subamt.replace("USD", "");
		 subamt = subamt.replace("HKD", "");
		 subamt = subamt.replace("RMB", "");
		 
		 if(cc==2){
			 var usd = $("#bf_s_hkd").val();			
			 subamt = subamt*hkd;			 
			 var subamt = subamt.toFixed(2);
			 $("#sub-amt").html("$"+subamt+" USD");
			 $("#bf_s_hkd").val(subamt);
		 }else if(cc==3){
		 
			 var subamt = subamt.toFixed(2);
			 $("#sub-amt").html("¥"+subamt+" RMB");
			 $("#bf_s_cny").val(subamt);
		 } else {			 
			 convert_hkd();			 
		 }
		 
	 }else{
	 }
});


function convert_hkd(){	
	var v1 = document.getElementById('check-in').value;
	var v2 = document.getElementById('check-out').value;
	$("#bf_from").val(v1);
	$("#bf_to").val(v2);
	
	if(v1!="" && v2!=""){
		var from_date = $("#check-in").datepicker('getDate'),
		 to_date   = $("#check-out").datepicker('getDate');
		if(to_date.getTime()<=from_date.getTime()){
			alert("Booking dates not valid");
		}else{
			var parts1 = v1.split('/'); 
			var parts2 = v2.split('/');
			var date1 = new Date(parts1[2],(parts1[0]-1),parts1[1]);
			var date2 = new Date(parts2[2],(parts2[0]-1),parts2[1]);
			var diffDays = Math.round(Math.abs((date1.getTime() - date2.getTime())/(24*3600*1000)));
			$("#non").html(diffDays);				
			cal_amt(diffDays);
		}
	}else{	
		 var cc = $("#change_currency").val();
		 if(cc==1){
		 	$("#sub-amt").html("$0 HKD");
		 }else{
			 $("#sub-amt").html("$0 USD");
		 }
	}
}

function cal_amt(totalDays){
	$("#bfnon").val(totalDays);
	totalDays = parseInt(totalDays);
	months = 0;
	weeks = 0;
	days = 0;
	if(totalDays>=30){
		months = parseInt(totalDays/30);
		modulo = parseInt(totalDays%30);
		if(modulo>0){
			if(modulo>=7){
				weeks =  parseInt(modulo/7);
				days =  parseInt(modulo%7);
			}else{
				days =  modulo;
			}
		}
	}else if(totalDays>=7){
		  weeks =  parseInt(totalDays/7);
		  days =  parseInt(totalDays%7);
	}else{
		days =  totalDays;
	}	
		
	mamt = parseInt($("#per_month").val());
	wamt = parseInt($("#per_week").val());
	damt = parseInt($("#per_day").val());
	
	months = months*mamt;
	weeks = weeks*wamt;
	days = days*damt;
	total = months+weeks+days;
	
	var cc = $("#change_currency").val();
	var hkd = $("#bfhkd").val();
	var cny = $("#bfcny").val();
		
	if(cc==2){
		total = total*hkd;
		total = Math.round(total,2);
	 	$("#sub-amt").html("$"+total+"  USD");
	}else if(cc==3){
		total = total;
		total = Math.round(total,2).toString();
	 	$("#sub-amt").html("¥"+total+"  RMB");
	}else{	 	
		$("#sub-amt").html("$"+total+"  HKD");
	}		 		
}
$( "#book-btn" ).click(function() {
	var from = $.trim($("#bf_from").val());
	var to = $.trim($("#bf_to").val());
	if(from==""){
		$("#errors").html("<?php echo ERROR_SELECT_FROM_AND_TO_DATE;?>");
		$("#errors").show(1000);
	}	
	else if(to==""){
		$("#errors").html("<?php echo ERROR_SELECT_FROM_AND_TO_DATE;?>");
		$("#errors").show(1000);
	}
	else {
		non = $("#bfnon").val();
		if(non<7){
			ba_daily = $("#ba_daily").val();
			if(ba_daily==2){
				$("#errors").html("<?php echo ERROR_DAILY_BOOKING_IS_NOT_ALLOWED;?>");
				$("#errors").show(1000);
				return false;
			}
		}else{
			$("#errors").hide();
			$("#errors").html("");
		}
		if(non>=7 && non<30){
			ba_weekly = $("#ba_weekly").val();
			if(ba_weekly==2){
				$("#errors").html("<?php echo ERROR_WEEKLY_BOOKING_IS_NOT_ALLOWED;?>");
				$("#errors").show(1000);
				return false;
			}
		}else{
			$("#errors").hide();
			$("#errors").html("");
		}
		if(non>=30){
			ba_monthly = $("#ba_monthly").val();
			if(ba_monthly==2){
				$("#errors").html("<?php echo ERROR_MONTHLY_BOOKING_IS_NOT_ALLOWED;?>");
				$("#errors").show(1000);
				return false;
			}
		}else{
			$("#errors").hide();
			$("#errors").html("");
		}
				
		var from_date = $("#check-in").datepicker('getDate'),
		    to_date   = $("#check-out").datepicker('getDate');
			if(to_date.getTime()<=from_date.getTime()){
				$("#errors").html("Booking dates not valid");
				$("#errors").show(1000);
			}else{
				$("#booking_form").submit();
			}
	}
});

<?PHP 


?>
</script>
<form name="booking_form" id="booking_form" method="post" action="booking.php?id=<?php echo $rid; ?>">
<input type="hidden" id="bf_from" name="bf_from" value="">
<input type="hidden" id="bf_to" name="bf_to" value="">
<input type="hidden" id="bfnon" name="bfnon" value="">
<input type="hidden" id="bfhkd" name="bfhkd" value="<?php echo $hkd; ?>">
<input type="hidden" id="bfcny" name="bfcny" value="<?php echo $cny; ?>">
<input type="hidden" id="bfcc" name="bfcc" value="<?php if ($curLang == "cn") echo "3"; else echo "1"; ?>">
<input type="hidden" id="ba_daily" name="ba_daily" value="<?php echo $room_data['daily_booking']; ?>">
<input type="hidden" id="ba_weekly" name="ba_weekly" value="<?php echo $room_data['weekly_booking']; ?>">
<input type="hidden" id="ba_monthly" name="ba_monthly" value="<?php echo $room_data['monthly_booking']; ?>">
</form>
</body>
</html>