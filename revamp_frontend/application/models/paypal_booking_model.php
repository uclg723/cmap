<?php


require_once('basic_model.php');
class Paypal_booking_model extends Basic_model{
	
		
	public function calculate_amount($room_id, $from_date=null, $to_date=null, $currency="HKD"){
	
		// if only 1 parameter, it is an array
		if (is_array($room_id)){
			$pp_book = $room_id;
			$from_date = $pp_book['from_date'];
			$to_date = $pp_book['to_date'];
			$currency = $pp_book['currency'];
			$room_id = $pp_book['room'];
		}
		
	
		$this->load->model('rooms_model');		
		$amount = $this->rooms_model->calculate_amount($room_id, $from_date, $to_date);
		
		// handle currency
		$this->load->model('currency_fx_model');
		// original amount in HKD
		$from_fx = "HKD";
		$to_fx = $currency;
		$amount = $this->currency_fx_model->convertCurrency($amount, $from_fx, $to_fx);
		
		return $amount;
	}// calculate_amount
	
	public function insert_booking($data){
	
		$field_set = array(
		'room',
		'UID',
		'from_date',
		'to_date',
		'amt',
		'currency',
		'status',
		'gateway',
		'prc_bank',
		);
	
		// data has all of the above, 
		$room_id = &$data['room'];
		$uid = &$data['uid'];
		$from_date = &$data['from_date']; // in Y-m-d format
		$to_date = &$data['to_date'];
		$currency = &$data['currency'];
		$gateway = &$data['gateway'];
		
		$amt = $this->calculate_amount($room_id, $from_date, $to_date, $currency);
		$data['amt'] = $amt;
		$data['status'] = 2; // 2: pending, 1: completed
		
		
		$pp_book_id = $this->insert($data);
		

		return $pp_book_id;
	}
	
	// update_completed: mark the colument to be completed and save to database.
	public function update_completed($id){
		return $this->update($id, array('status'=>1)); // status: 1: completed, 2: pending.
	}
} // class

?>