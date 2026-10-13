<?php

// Paypal_handler: 
//   It is the HTTP controller, which will redirect those input into paypal_model
//   for further processing.
class Paypal_handler extends CI_Controller {

	// 

	public function __construct(){
		parent::__construct();
		// loading necessary stuff
		$this->load->helper(array('form', 'url'));
		
		$this->load->model('paypal_model'); // paypal_model will handle all the details for different operations.
		
	//	$this->load->model('frontpage_model');
	//	$this->load->model('login_model');
	//	$this->load->model('rooms_model'); 
		// libraries
	//	$this->load->library('form_validation');
	//	$this->load->library('encrypt');
	//	$this->load->library('session');
	}

  public function payment_cancel(){
		$this->load->model('logger_model');
	   $this->logger_model->write_log('Paypal_handler::payment_cancel with $_POST =[' . $_POST . '] and $_GET = [' . $_GET . '].');
	//echo "Payment canceled.";
	$url = base_url() . 'index.php?/frontpage/payment_cancel';
	redirect($url);
  }
  
  
  
  public function payment_success(){
  		$this->load->model('logger_model');
	   $this->logger_model->write_log('Paypal_handler::payment_success with $_POST =[' . $_POST . '] and $_GET = [' . $_GET . '].');

	// what to do ?k
	
	// tx=15203616040863516&st=Completed&amt=2.00&cc=HKD&cm=&item_number=215
	
  	$post_var = $_POST;
	
	$input_list = array('tx', 'st', 'amt', 'cc', 'cm', 'item_number');
	$filter_request = array(); // filtered request
	foreach ($input_list as $cur_var){
		$value = $this->input->get_post($cur_var);
		$filter_request[$cur_var] = $value;
	}
	
	$result = $this->paypal_model->process_success($filter_request);
	
  
	//echo "Payment Success.";
		$url = base_url() . 'index.php?/frontpage/payment_success';
	redirect($url);

	// what is there in the input?
	

	
  }
  
  public function payment_notify(){
       $this->load->model('logger_model');
	   $this->logger_model->write_log('Paypal_handler::payment_success with $_POST =[' . $_POST . '] and $_GET = [' . $_GET . '].');
		
		$filter_request = array();
		$this->paypal_model->process_notification($filter_request);
  }

  // 
  
  } // class

?>