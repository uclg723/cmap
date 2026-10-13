<?php

include_once("payment_model.php");

class Paypal_model extends payment_model{

	var $paypal_config;
	function __construct(){
		$this->config->load('paypal_setting', true);
		// $this->config['paypal_setting'];
	}


   function process_payment($request){
   	
   		// 	insert the record into the paypal_booking table, return the object back?
   	//	$paypal_book = $this->insert_paypal_booking($request); 
   		$pp_book_id = $request['paypal_book_id'];
		$this->load->model('paypal_booking_model');
		$booking = $this->paypal_booking_model->retrieve($pp_book_id);
		
		$this->load->model('rooms_model');
		$room = $this->rooms_model->retrieve($booking['room']);
		
		$this->load->model('users_model');
		$cur_user = $this->users_model->retrieve($booking['UID']);
   	
   	// Get the setting from config file.
		$ppl_set = $this->config->item('paypal_setting');
		
		
		foreach ($ppl_set as $name=>$val){
			$$name = $val;
		}
		
	// get the payment information from the $request.
	 //$item_name;
	 //$item_desc;
	 //$item_amount;
	 $fname = $cur_user['firstname'];
	 $lname = $cur_user['lastname'];
	 $email = $cur_user['email'];
	 $item_name = $room['room_name'];
	 $item_desc = $room['description'];
	 $item_amount = $booking['amt'];
	 
	 $tid = $booking['ID'];
		
		$querystring= "";
		// Check if paypal request or response

			// Reference of all the HTML variables:
			// https://developer.paypal.com/docs/classic/button-manager/integration-guide/ButtonManagerHTMLVariables/
			
			// Firstly Append paypal account to querystring
			$querystring .= "?business=".urlencode($paypal_email)."&";	
			
			// Append amount& currency (¢G) to quersytring so it cannot be edited in html
			
			//The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
			$querystring .= "item_name=".urlencode($item_name)."&";
			$querystring .= "item_desc=" . urlencode($item_desc) . "&";
			$querystring .= "amount=".urlencode($item_amount)."&";
			
			$querystring .= "cmd=".urlencode('_xclick')."&";
			$querystring .= "no_note=".urlencode('1')."&";
			$querystring .= "lc=".urlencode('USA')."&";
			$querystring .= "currency_code=".urlencode($booking['currency'])."&";
			$querystring .= "bn=".urlencode('PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest')."&";
			$querystring .= "first_name=".urlencode($fname)."&";
			$querystring .= "last_name=".urlencode($lname)."&";
			$querystring .= "item_number=".urlencode($tid)."&";
			
			$querystring .= "option_index=4&";
			
			$querystring .= "on0=Description&";
			$querystring .= "os0=&"; // not to use description, rather by individual items.
			$querystring .= "on1=Room&";
			$querystring .= "os1=" . urlencode($room['room_name']) . "&";
			$querystring .= "on2=FromDate&";
			$querystring .= "os2=" . urlencode($booking['from_date']) . "&";
			$querystring .= "on3=ToDate&";
			$querystring .= "os3=" . urlencode($booking['to_date']). "&";
			$querystring .= "on4=Email&";
			$querystring .= "os4=" . urlencode($email). "&";
			
			
			//loop for posted values and append to querystring
			/*foreach($_POST as $key => $value){
				$value = urlencode(stripslashes($value));
				$querystring .= "$key=$value&";
			}*/
			
			// Append paypal return addresses
			$querystring .= "return=".urlencode($return_url)."&";
			$querystring .= "rm=0&"; // return method, for return url, 0=GET, 1 = GET with not data, 2 = POST
			$querystring .= "cancel_return=".urlencode($cancel_url)."&";
			$querystring .= "notify_url=".urlencode($notify_url);
			
			// Append querystring with custom field
			$querystring .= "&custom=".$_SESSION['id'];
			
//			debug_paypal_log_script('payment.php: redirecting to paypal [' . $paypal_url . $querystring . ']');
			
			// Redirect to paypal IPN	
			header('location:'.$paypal_url.$querystring);
			exit();

	 } // function 

  // sometime get notification
  function process_notification($request){
    echo "processNotification.";
  }

  
  function process_success($request){
	// request is the array of input
	// tx=15203616040863516&st=Completed&amt=2.00&cc=HKD&cm=&item_number=215
	// * the expected get string is
	// * ?tx=1JD8084673697301P&st=Completed&amt=1480.00&cc=HKD&cm=73&item_number=149
	// * tx: transaction ID
	// * st: status (Completed)
	// * amt: amount of payment
	// * cc: curency (HKD / USD)
	// * cm: ?
	// * item_number: booking number

	$paypal_tx_id 	= $request['tx']; // 
	$status 		= $request['st']; // expected Completed
	$amount 		= $request['amt'];
	$currency 		= $request['cc'];
	$cm     		= $request['cm'];
	$pp_book_id 	= $request['item_number'];
	
	// load the relevant data from database.
		$this->load->model('paypal_booking_model');
		$pp_book = $this->paypal_booking_model->retrieve($pp_book_id);
		
		$this->load->model('rooms_model');
		$room = $this->rooms_model->retrieve($pp_book['room']);
		
		$this->load->model('users_model');
		$cur_user = $this->users_model->retrieve($pp_book['UID']);

	// Verify with paypal about the correctness of transaction (avoid hacking)
	$is_verify = $this->verify_transaction($paypal_tx_id, $request);
	if (!$is_verify){
		return false; // cannot process
	}
	
	// Check if the status is completed
	if ($status!='Completed'){
		return false;
	}
		
	// Update the paypal booking status to completed	
	$this->paypal_booking_model->update_completed($pp_book_id);
		
	// Insert the booking record
	$dt_in = DateTime::createFromFormat('Y-m-d', $pp_book['from_date']);
	$dt_out = DateTime::createFromFormat('Y-m-d', $pp_book['to_date']);
	$dt_int = $dt_out->diff($dt_in);
	$no_of_days = $dt_int->format('%a'); // total number of days.
	$this->load->model('booking_model');
	$booking_info = array(
		'uid'=>$cur_user['id'],
		'date_in'=>$pp_book['from_date'],
		'date_out'=> $pp_book['to_date'],
		'add_info'=> 'Paypal Transaction ID: ' . $paypal_tx_id,
		'days'=>$no_of_days,
		'rid'=> $room['id'],
		'btype'=>2, // bed-type : 2 means online
		'status'=> 2, 
		// 'created' => , // automatically generated.
		'txn_id'=> $paypal_tx_id,		
	);
	$booking_id = $this->booking_model->insert($booking_info);
		
	return true; // successfully completed.
  }
  protected function verify_transaction($tx, $request){
	// verify with paypal about the $tx transaction id and the request are matched.
	// not implemented yet
	return true;
  }
}

?>