<?php

include_once("payment_model.php");
class Unipay_model extends payment_model{
	
	function __construct(){
		$this->config->load('unipay_setting', true);
		// $this->config['unipay_setting'];
	}

   protected function unipay_sign_string($params,$md5_key){
    $ignore_params=array('return_url','notify_url'); // ignore turn_url and notify_url
	ksort($params);
	 $sign_str; // for debut output
	$sign_str="";
	foreach ($params as $key=>$val){
	  if (in_array($key, $ignore_params)){
		continue;
	  } // if

	  $sign_str = $sign_str . sprintf("%s=%s&", $key, $val);
	} // foreach
	$sign_str = trim($sign_str, "&") . $md5_key;
	return md5($sign_str); // md5
  }
   function process_payment($request){
	   
	   $this->load->model('logger_model');
	   $this->logger_model->write_log('Unipay_model::process_payment($request=[' . print_r($request, true) . '] .');
	
	   
		 
		//	$paypal_book = $this->insert_paypal_booking($request); 
			$pp_book_id = $request['paypal_book_id'];
			$this->load->model('paypal_booking_model');
			$booking = $this->paypal_booking_model->retrieve($pp_book_id);
			
			$this->load->model('rooms_model');
			$room = $this->rooms_model->retrieve($booking['room']);
			
			$this->load->model('users_model');
			$cur_user = $this->users_model->retrieve($booking['UID']);
		
		// Get the setting from config file.
			$uni_config = $this->config->item('unipay_setting');
			$partner = $uni_config['unipay_partner'];
			$order_number = $booking['ID'];
			$total_fee = $booking['amt'];	
			$pay_id = $booking['prc_bank'];
			$return_url = $uni_config['return_url'];
			$notify_url = $uni_config['notify_url'];

			$cancel_url = $uni_config['cancel_url'];
			
			$md5_key = $uni_config['md5_key'];
			
			$params = array(
				'partner'=>$partner,
				'order_number'=>$order_number,
				'total_fee'=> $total_fee,
				'pay_id' => $pay_id,
				'return_url' => $return_url,
				'notify_url' => $notify_url,
			);
			
			//$config['return_url'] = $base_url . 'index.php?c=unipay_handler&m=payment_success';
			//$config['cancel_url'] = $base_url . 'index.php?c=unipay_handler&m=payment_cancel';
			//$config['notify_url'] = $base_url . 'index.php?c=unipay_handler&mpayment_notify';

			//$config['unipay_url'] = 'https://' . $UNIPAY_SERVER . '/index.php/payapi/'; // no trailing /

			$sign = $this->unipay_sign_string($params, $md5_key);
			
			//$data = $params;
			$params['sign'] = $sign;
			
		// get the payment information from the $request.
		 //$item_name;
		 //$item_desc;
		 //$item_amount;
		 $fname = $cur_user['firstname'];
		 $lname = $cur_user['lastname'];
		 $email = $cur_user['email'];
		 $item_name = $room['room_name'];
		 $item_desc = $room['description'];
		 
		 
			$querystring= "";
		//   $param_name = array('order_number', 'partner', 'pay_id', 'total_fee');
				// Redirect to paypal IPN	
			//header('location: index.php?c=unipay_handler&m=test_submit');
			//exit();
	 

		return $params;
	 
	 
	}

	

  // sometime get notification
  function process_notification($request){
     $this->load->model('logger_model');     $this->logger_model->write_log('Unipay_model::process_success($request=[' . print_r($request, true) . '] .');

    echo "success";
  }
  
  function process_success($request){
  	   $this->load->model('logger_model');
	   $this->logger_model->write_log('Unipay_model::process_success($request=[' . print_r($request, true) . '] .');

	// $request = array ('partner', 'out_trade_no', 'pay_no', 'amount', 'pay_result', 'sett_date', 'base64_memo')
	// partner: unipay provided merchant name
	// out_trade_no: provided out trade number (paypal booking id)
	// pay_no: payment no. of unipay (auto created)
	// amount: payment (2 d.p.)
	// pay_result: 1 (success) or 0 (failed)
	// sett_date: YYYYMMDD
	// base64_memo
	// sign: signature
	// sign_type: only MD5
	// version: version number.
		$pp_book_id = $request['out_trade_no'];
		$unipay_pay_no = $request['pay_no'];
		
		$this->load->model('paypal_booking_model');
		$pp_book = $this->paypal_booking_model->retrieve($pp_book_id);
		
		$this->load->model('rooms_model');
		$room = $this->rooms_model->retrieve($pp_book['room']);
		
		$this->load->model('users_model');
		$cur_user = $this->users_model->retrieve($pp_book['UID']);

	// Verify with paypal about the correctness of transaction (avoid hacking)
	$is_verify = true; // $this->verify_transaction($paypal_tx_id, $request);
	if (!$is_verify){
		return false; // cannot process
	}
	
	// Check if the status is completed
	//if ($status!='Completed'){
	//	return false;
	//}
		
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
		'add_info'=> 'Unipay Transaction ID: ' . $unipay_pay_no,
		'days'=>$no_of_days,
		'rid'=> $room['id'],
		'btype'=>2, // bed-type : 2 means online
		'status'=> 2, 
		// 'created' => , // automatically generated.
		'txn_id'=> $unipay_pay_no,		
	);
	$booking_id = $this->booking_model->insert($booking_info);
		
	return true; // successfully completed.

  }


} // class
