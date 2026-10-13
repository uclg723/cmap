<?php
//	function convertCurrency($amount, $from, $to){
//	    $url  = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
//	    $data = file_get_contents($url);
//	    preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
//		if (sizeof($converted)>1){
//			$converted = preg_replace("/[^0-9.]/", "", $converted[1]);
//		}
//	    return round($converted,10);
//	}	
?>


<?php 

//include('include/functions.php');
date_default_timezone_set("Asia/Calcutta");
//session_start();
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

/*
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
connect();
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
				 }else{
					 $hkd = floatval(convertCurrency(1, "HKD", "USD"));
					 $total = $total*$hkd;
					 $total = round($total);
					 $currency = "USD";					 
				 }
						
				 $sql = "INSERT INTO paypal_booking (room,UID,from_date,to_date,amt,currency,status) VALUES($rid,$uid,'".$from."','".$to."','".$total."','$currency',2)";
				 $query = mysql_query($sql);
				 if($query){
					 $_SESSION['tid'] = mysql_insert_id();
					 header("Location: payment.php");
				 }else{
					 header("Location: logout.php");
				 }
			}
		}		
	}
}
*/

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
	//	connect();
	//	$dsql = 'SELECT * FROM booking WHERE rid='.$rid.' and "'.$check_from.'" between date_in and date_out';		
	//	$dquery = mysql_query($dsql);
	//	$nor = mysql_num_rows($dquery);
	$nor = 0; // to save time, let's assume no room. >_<
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

// $hkd = floatval(convertCurrency(1, "HKD", "USD"));
?>


  <link type="text/css" href="include/style.css" rel="stylesheet"/>
  <script type='text/javascript' src='include/jquery.min.js'></script>
  <script type='text/javascript' src='include/jquery.cycle.all.min.js'></script>
  <script type="text/javascript">
//top.document.title='Hotel - Service Apartment - China Max Asia Pacific Company Limited ';
</script>
  <style>
	<?php readfile(__DIR__ . '/booking.css'); ?>
</style>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <script language='javascript'>
		var room_id = '<?php echo $room_id;?>';

		<?php readfile(__DIR__ . '/booking.js'); ?>
  </script>

	<!-- Add jQuery library -->
	<!--<script type="text/javascript" src="fancybox/lib/jquery-1.10.1.min.js"></script>-->

	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="vendors/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="vendors/fancybox/source/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="vendors/fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="vendors/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
	<script type="text/javascript" src="vendors/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>


	<script type="text/javascript">
	</script>  
  <table width="930px" cellspacing="5" cellpadding="5" style="margin-left:auto; margin-right:auto" border="0">
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
				echo '<div id="room-desc" class="booking_cancelled">Your Booking has been cancelled due to "'.$booking_cancelled.'"</div>';
				}else{
					echo '<div id="room-desc" class="booking_completed">Your Booking has successfully '.$booking_cancelled.'</div>';
				}
			}
		  ?>
          <div id="left">
          <div class="btitle">Calendar</div>
          <div id="calendar">
              <div id="mcalendar">
              <div id="month-dropdown"> Select Month:
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
                  <div class="key-text">Available</div>
                  <div class="unavailable key">&nbsp;</div>
                  <div class="key-text">Unavailable</div>
                  <div class="in_the_past key">&nbsp;</div>
                  <div class="key-text">Past</div>
                </div>
                <div class="clear"></div>
                <div id="desc" style="margin-top:20px;">
	                <?php echo nl2br($room_data['description']); ?>
                    <h2>Addition Features</h2>
              		<?php echo nl2br($room_data['add_info']); ?>
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
				$base_url='';
				$search_dir = "../admin/uploads/{$room_data['id']}/thumb";
				$images = glob("$search_dir/*.jpg");
				sort($images);											
				if (count($images) > 0) { // make sure at least one image exists
					foreach($images as $img){
						$explode = explode("/",$img);
						$count = count($explode);
						echo '<a class="fancybox-thumbs" data-fancybox-group="thumb" href="'.$base_url.'/../admin/uploads/'.$room_data['id'].'/'.$explode[$count-1].'"><img src="'.$base_url.'/../admin/uploads/'.$room_data['id'].'/thumb/'.$explode[$count-1].'" /></a>';
					}
				}
			?>
            </div>
        </div>
          <div id="right">
          <div class="btitle">Book</div>
          <div class="booking-content">
              <div id="book-type-details"> Booking Type:
              <input type="hidden" id="per_day" value="<?php echo $room_data['damount'];?>" >
              <input type="hidden" id="per_week" value="<?php echo $room_data['wamount'];?>" >
              <input type="hidden" id="per_month" value="<?php echo $room_data['mamount'];?>" >
              <select class="booking_type" id="booking_type" name="booking_type">
                  <option value="1">Per Night</option>
                  <option value="2">Per Week</option>
                  <option value="3">Per Month</option>
                </select>
                <select class="booking_type" id="change_currency" name="change_currency">
                  <option value="1">HKD</option>
                  <option value="2">USD</option>
				  <option value="3">CNY</option>
                </select>
              <div id="type-amt" style="display:block;"><h2>Price: $<?php echo $room_data['damount'];?>  HKD</h2></div>
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
                      <select class="input-tiny" id="number_of_guests" name="number_of_guests">
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>                          
                        </select>
                    </div>                   
                    </div>
                     <h2>No of Night's: <span id="non">0</span></h2>
                    <div class="clear"></div>
                </div>
                </div>
            </div>
              <div id="sub-total" style="background:#fff; padding:20px;">
                  <p style="font-weight:bold;margin:0">Subtotal</p>
                  <div id="sub-amt" style="font-size:36px;">$0 HKD</div>
                  <div id="errors"></div>
				  				  
				  <button type='button' onclick='return book_btn_click();' class='book-bt' id='book-btn-paypal' name='payment_gateway' value='paypal' style='height:60px;'>
				  <img src='images/paypal.jpg' style='width:100px;'></img>
				  </button>
				  <button type="button"  onclick='book_btn_click' class="book-bt" id="book-btn-unipay" name='payment_gateway' value='unipay' 					
					>
					<img src='images/unipay.jpg' style='width:180px;'></img>
				  </button>
				  
                  <div class="info">
                  <?php
				  		$bstatus[1] = '<span class="a">Allowed</span>'; 
						$bstatus[2] = '<span class="na">Not Allowed</span>'; 
				  ?>
                  <br>

                  <table class="gridtable">
<tr>
	<th>Booking Type</th><th>Min no of days.</th><th>Max no of days.</th><th>Status.</th>
</tr>
<tr>
	<td>Daily</td><td>1</td><td>6</td><td><?php echo $bstatus[$room_data['daily_booking']]; ?></td>
</tr>
<tr>
	<td>Weekly</td><td>7</td><td>29</td><td><?php echo $bstatus[$room_data['weekly_booking']]; ?></td>
</tr>
<tr>
	<td>Monthly</td><td>30</td><td>250</td><td><?php echo $bstatus[$room_data['monthly_booking']]; ?></td>
</tr>
</table>
                  	<!--<p>Daily Booking: <?php echo $bstatus[$room_data['daily_booking']]; ?><br>
                    Weekly Booking: <?php echo $bstatus[$room_data['weekly_booking']]; ?><br>
                    Montly Booking: <?php echo $bstatus[$room_data['monthly_booking']]; ?>  (Minum on 30 days) </p>-->
                   <br>

                  </div>
                  <p>The calendar is updated every five minutes and is only an approximation of availability.</p>
              <p>Some hosts set custom pricing for certain days on their calendar, like weekends or holidays. The rates listed are per day and do not include any cleaning fee or rates for extra people the host may have for this listing. Please refer to the listing's Description tab for more details.</p>
              <p>We suggest that you contact the host to confirm availability and rates before submitting a reservation request.</p>             
            </div>
            </div>
        </div>
        </div></td>
    </tr>
  </table>
<script>
	
</script>
<form name="booking_form" id="booking_form" method="post" action="index.php?/frontpage/request_payment">
<input type='hidden' id='id' name='room_id' value='<?php echo $room_data['id']; ?>' />
<input type='hidden' id='bf_payment' name='payment_gateway' value="" />
<input type='hidden' id='bf_prcbank' name='prc_bank' value="" />
<input type="hidden" id="bf_from" name="bf_from" value="">
<input type="hidden" id="bf_to" name="bf_to" value="">
<input type="hidden" id="bfnon" name="bfnon" value="">
<input type="hidden" id="bfnog" name="bfnog" value="">
<?php
  $hkd = "0.129";
  ?>
<input type="hidden" id="bfhkd" name="bfhkd" value="<?php echo $hkd; ?>">
<input type="hidden" id="bfcc" name="bfcc" value="1">
<input type="hidden" id="ba_daily" name="ba_daily" value="<?php echo $room_data['daily_booking']; ?>">
<input type="hidden" id="ba_weekly" name="ba_weekly" value="<?php echo $room_data['weekly_booking']; ?>">
<input type="hidden" id="ba_monthly" name="ba_monthly" value="<?php echo $room_data['monthly_booking']; ?>">
</form>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<div id="dialog-form" title="Create new user">
  <p class="validateTips">Choose the bank.</p> 
  <form>
    <fieldset>
      <label for="name">Bank</label>
	  <select name='pay_id' id='pay_id'>
    <option value='gonghang'>中國工商銀行</option>
	<option value='zhonghang'>中國銀行</option>
	<option value='jiaohang'>中國交通銀行</option>
	<option value='zhaohang'>招商銀行</option>
	<option value='guanda'>中國光大銀行</option>
	<option value='nonghang'>中國農業銀行</option>
	<option value='zhongxin'>中信銀行</option>
	<option value='jianhang' selected='selected' >建設銀行</option>
	<option value='shenfa'>深圳發展銀行</option>
	<option value='minsheng'>民生銀行</option>
	<option value='guangfa'>廣東發展銀行</option>
	<option value='xingye'>興業銀行</option>
	<option value='pufa'>上海浦東發展銀行</option>	
  </select><br>
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
    </fieldset>
  </form>
</div>
