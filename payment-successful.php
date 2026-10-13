<?php
include('include/functions.php');
//http://www.phpbuilder.com/articles/application-architecture/shopping-carts/setting-up-paypal-ipn-in-php.html
/**
 * Using the Paypal developer classic api
 * this will create a header redirection and redirect to paypal for processing the payment.
 * 
 * the expected get string is
 * ?tx=1JD8084673697301P&st=Completed&amt=1480.00&cc=HKD&cm=73&item_number=149
 * tx: transaction ID
 * st: status (Completed)
 * amt: amount of payment
 * cc: curency (HKD / USD)
 * cm: ?
 * item_number: booking number
 */

	/* return後 先記錄下LOG備查*/
	connect();
	$callback_time = date("Y-m-d H:i:s");
	$data_post = json_encode($_POST,true);
	$data_get = json_encode($_GET,true);
	$data_session = json_encode($_SESSION,true);
	$sql_log = "INSERT INTO callback_log 
			(callback_time, data_post, data_get, data_session) 
			VALUES 
			('".$callback_time."', '".$data_post."', '".$data_get."', '".$data_session."');";
	$query_log = mysql_query($sql_log);
	





session_start();
if(!isset($_SESSION['id']))
{
	header('LOCATION:logout.php');
}
if(isset($_SESSION['id']))
{
		$user_login = 1;
		
		$id = sanitize($_SESSION['id']);

		$SQL = "SELECT * FROM users WHERE id = $id";
		
		$query = mysql_query($SQL);
		if($query){
			
			$nor = mysql_num_rows($query);
			if($nor>0){
			$row = mysql_fetch_assoc($query);
			$uid=  sanitize($_SESSION['id']);
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
		
}
if(isset($_SESSION['tid'])){
	
	
	
	$tid = $_SESSION['tid'];

	$sql = "SELECT * FROM paypal_booking WHERE ID=".$tid;
	$query = mysql_query($sql);
	if($query){
		$nor = mysql_num_rows($query);
		if($nor==1){
			$booking = mysql_fetch_assoc($query);
		}else{
			header('LOCATION:logout.php');
		}
	}else{
		echo die(mysql_error());
	}
	
	$sql = "SELECT * FROM rooms WHERE ID=".$booking['room'];
	$query = mysql_query($sql);
	if($query){
		$nor = mysql_num_rows($query);
		if($nor==1){
			$room = mysql_fetch_assoc($query);
		}else{
			header('LOCATION:logout.php');
		}
	}else{
		echo die(mysql_query());
	}
	
}else{
	header('LOCATION:logout.php');
}
// PayPal settings
//$paypal_email = 'cs@cmaphk.com';
//$return_url = 'http://www.cmaphk.com/payment-successful.php';
//$cancel_url = 'http://www.cmaphk.com/payment-cancelled.htm';
//$notify_url = 'http://www.cmaphk.com/paypal/payments.php';

if (file_exists('config/paypal_config.php')){
	include('config/paypal_config.php');
}

/**
 * Ben Debugging
 */
include('debug_paypal_log_script.php');
$get_string = print_r($_GET,true); // capture in string
debug_paypal_log_script('payment-success.php:: Calling payment-success.php with get [' . $get_string . ']\n'); // for writing to the file.
debug_paypal_log_script('payment-success.php:: Info retrieved before processing  with session[tid] = [' . $_SESSION['tid'] . ']\n'); // for writing to the file.
debug_paypal_log_script('payment-success.php:: $booking = [' . print_r($booking,true) . ']\n'); // for writing to the file.
debug_paypal_log_script('payment-success.php:: $room = [' . print_r($room, true) . ']\n'); // for writing to the file.


$currency_code = $booking['currency'];
$item_name = $room['room_name'];
$item_amount = sprintf('%01.2f', $booking['amt']);

// Include Functions
include("payment-functions.php");

//Database Connection

$querystring= "";
// Check if paypal request or response
if (!isset($_REQUEST['tx'])){
	// tx from the request, transaction number
//if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])){

	debug_paypal_log_script('payment-success.php with no post txt_id, POST is [' . print_r($_POST) . '\n'); // for writing to the file.
	

	

	// Firstly Append paypal account to querystring
	$querystring .= "?business=".urlencode($paypal_email)."&";	
	
	// Append amount& currency (£) to quersytring so it cannot be edited in html
	
	//The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
	$querystring .= "item_name=".urlencode($item_name)."&";
	$querystring .= "amount=".urlencode($item_amount)."&";
	
	$querystring .= "cmd=".urlencode('_xclick')."&";
	$querystring .= "no_note=".urlencode('1')."&";
	$querystring .= "lc=".urlencode('USA')."&";
	$querystring .= "currency_code=".urlencode($booking['currency'])."&";
	$querystring .= "bn=".urlencode('PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest')."&";
	$querystring .= "first_name=".urlencode($fname)."&";
	$querystring .= "last_name=".urlencode($lname)."&";
	$querystring .= "item_number=".urlencode($tid)."&";
	
	// Append paypal return addresses
	$querystring .= "return=".urlencode(stripslashes($return_url))."&";
	$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
	$querystring .= "notify_url=".urlencode($notify_url);
	
	// Append querystring with custom field
	$querystring .= "&custom=".$_SESSION['id'];
	
	// Redirect to paypal IPN	
//	header('location:https://www.paypal.com/cgi-bin/webscr'.$querystring);

	debug_paypal_log_script("Payment-success:: Originally redirect back to paypal.<br>");
	debug_paypal_log_script("Payment-success:: <pre>");
	debug_paypal_log_script("Payment-success:: POST: " . print_r($_POST,true) . "<br>\n");
	debug_paypal_log_script("Payment-success:: GET:" . print_r($_GET, true) . "<br>\n");
	debug_paypal_log_script("Payment-success:: SESSION:" . print_r($SESSION, true) . "<br>\n");
	exit();

}else{
	/*echo "<pre>";
		print_r($_POST);
	echo "</pre>";*/

	// Response from Paypal
	debug_paypal_log_script('payment-success.php with get response from paypal, Request is [' . print_r($_REQUEST) . '\n'); // for writing to the file.
	
	

	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-validate';
	foreach ($_POST as $key => $value) {
		$value = urlencode(stripslashes($value));
		$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
		$req .= "&$key=$value";
	}
		
	// assign posted variables to local variables
	$data['item_name']			= $_POST['item_name'];
	$data['item_number'] 		= $_POST['item_number'];
	$data['payment_status'] 	= $_POST['payment_status'];
	$data['payment_amount'] 	= $_POST['mc_gross'];
	$data['payment_currency']	= $_POST['mc_currency'];
	$data['txn_id']				= $_POST['txn_id'];
	$data['receiver_email'] 	= $_POST['receiver_email'];
	$data['payer_email'] 		= $_POST['payer_email'];
	$data['custom'] 			= $_POST['custom'];
			
	// post back to PayPal system to validate
	$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Host: www.paypal.com\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	
	  // Open a socket for the acknowledgement request
	  
debug_paypal_log_script('payment-success.php connect to paypal, header is [' . $header . '] and $req = [' . $req . ']\n'); // for writing to the file.
	
/* Connect to Paypal to confirm, skipped
	require("config/paypal_config.php");
	global $PAYPAL_SERVER;
	if (empty($PAYPAL_SERVER)){
		$PAYPAL_SERVER = 'www.paypal.com';
	}
	  $fp = fsockopen('ssl://' . $PAYPAL_SERVER , 443, $errno, $errstr, 30);
	  	
	  // Send the HTTP POST request back to PayPal for validation
	  fputs($fp, $header . $req);
  	
	if (!$fp) {
		echo "HTTP ERROR";
	} else {			
		fputs ($fp, $header . $req);		
		while (!feof($fp)) {
			$res = fgets ($fp, 1024);			

debug_paypal_log_script('payment-success.php result from ssl connection to paypal $res = ' . $res . ''); 
			//if (strcmp($res, "VERIFIED") == 0) {											
*/
				
			// * ?tx=1JD8084673697301P&st=Completed&amt=1480.00&cc=HKD&cm=73&item_number=149
				if ($_REQUEST['st'] !='Completed'){
					// Old Condition, the post content seems wrong? 
					//	if ($_POST['payment_status'] != 'Completed') { 
				
					unset($_SESSION['tid']);
					$_SESSION['booking'] = "Payment not completed";
					header("Location: booking.php?id=".$room['id']);
				}else if ($_REQUEST['cc'] != $currency_code)  {
							
				// }else if ($_POST['cc'] != $currency_code)  {
				// original code
				//}else if ($_POST['mc_currency'] != $currency_code)  {
					unset($_SESSION['tid']);
					$_SESSION['booking'] = "Incorrect currency code (paid in " . $_REQUEST['cc'] . ') and (booking in ' . $currency_code . ')';
					header("Location: booking.php?id=".$room['id']);
				}else if ($_REQUEST['amt'] != $item_amount) {	
					// original code			
					//	}else if ($_POST['mc_gross'] != $item_amount) {
					unset($_SESSION['tid']);
					$_SESSION['booking'] = "Incorrect product price (paid in " . $_REQUEST['amt'] . ') and (booking in ' . $item_amount . ')';
					header("Location: booking.php?id=".$room['id']);
// No user information given from the payl reply						
//				}else if ($_POST['custom'] !=$uid ) {
//					unset($_SESSION['tid']);
//					$_SESSION['booking'] = "Invalid User";
//					header("Location: booking.php?id=".$room['id']);						
				}else{

					// Normal flow, mean insert the booking into the database.
					
					
					$txn_id = mysql_real_escape_string($_REQUEST['tx']);
// Old Code, using post and incorrect post name
//					$txn_id = mysql_real_escape_string($_POST['txn_id']);
 					$item_number = mysql_real_escape_string($_REQUEST['item_number']);
// avoid the checking temporarily
//					$sql = "SELECT 0 as count FROM booking WHERE 0=1;";
					$sql = "SELECT COUNT(*) AS count FROM booking WHERE txn_id = '$item_number'";
					$q = mysql_query($sql);
					$f = mysql_fetch_array($q);
 
					if($f['count'] > 0) {
						//$err_str = "Transaction already processed";
					//	$errors[] = $err_str;
					//	$_SESSION['booking'] = $err_str;
						unset($_SESSION['tid']);
						$_SESSION['booking'] = "Completed";
						header("Location: booking.php?id=".$room['id']);
					} else {
						// the booking is not recorded yet.
						$datediff = strtotime($booking['to_date']) - strtotime($booking['from_date']);
					    $nod = floor($datediff/(60*60*24));
					    $add_info = 'Paypal Transaction ID: ' . $txn_id ;
						$sql = "INSERT INTO booking (uid,txn_id,date_in,date_out,add_info,days,rid,status,btype) " . 
							   " VALUES('$uid','$item_number','".$booking['from_date']."','".$booking['to_date']."','{$add_info}','$nod','".$booking['room']."','2','2')";
// temporarily skip the booking log
						$query = mysql_query($sql);
						if(!$query){
							echo mysql_error();
						}
						
						
						$din = date("d-m-Y",strtotime($booking['from_date']));
						$dout = date("d-m-Y",strtotime($booking['to_date']));

						include('config/db_config.php');
						require('module/SMTPMailer/SMTPMailer.php');

						$smtp = new SMTPMailer();
						$smtp->setEmailTemplateById(1);
						$context = array(
							'{$email}'		=> $email,
							'{$last_name}'	=> $lname,
							'{$first_name}'	=> $fname,
							'{$pay_type}'	=> "Paypal ",
							'{$room_name}'	=> $room['room_name'],
							'{$date_in}' 	=> $din,	 // date in
							'{$date_out}'	=> $dout,	 // date out
							'{$nod}'		=> $nod,
							'{$item_amount}'	=> $booking['amt'],
							'{$currency}'		=> $booking['currency'],
							'{$transaction_id}' => $txn_id, // transaction id
						);
						$result = $smtp->send($context);
						
						$sql = "UPDATE paypal_booking SET status=1 WHERE id=".$tid;
						$query = mysql_query($sql);
						
						unset($_SESSION['tid']);
						$_SESSION['booking'] = "Completed";
// temporarily disable the redirection. 
						header("Location: booking.php?id=".$room['id']);
					} // if
/* Close tag of While (!eof($fp))	
				}

			
			//}else if (strcmp ($res, "INVALID") == 0) {			
			//	$_SESSION['booking'] = "Cannot Verify Payment";
			//	header("Location: booking.php?id=".$room['id']);
			//}	
		} // while	
	fclose ($fp);
*/		
	}	
		
	
} // if 
close();
?>