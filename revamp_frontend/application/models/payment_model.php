<?php
// Generic Payment model (for interaction with the web server)
//require_once('paypal_booking_model.php');
abstract class Payment_model extends CI_Model {

	protected $paypal_booking = null;

	 function __construct(){
		$this->load->model('paypal_booking_model');
		//$this->paypal_booking = new Paypal_booking_model();
	}
	  
	protected function set_paypal_booking_object($obj){
		$this->paypal_booking_model = $obj;
	}
	
	
	public function insert_paypal_booking($req){
		//
		$field_list = array('room', 'UID', 'from_date', 'to_date', 'amt', 'currency', 'status', 'gateway', 'prc_bank');
		// status = 2: pending
		// gateway = paypal or unipay
		$data_map = array();
		foreach ($field_list as $cur_field){
			if (array_key_exists($cur_field, $req)){
				$data_map[$cur_field] = $req[$cur_field];
			}
		}
		
		$pp_book_id = $this->paypal_booking_model->insert($data_map);
		return $pp_book_id;
	}

  // process an payment request  
  abstract function process_payment($request);

  // sometime get notification
  abstract function process_notification($request);
  
  public function process_success($request){
	// a dummy function, to be override by corresponding class.
  }
} //
?>