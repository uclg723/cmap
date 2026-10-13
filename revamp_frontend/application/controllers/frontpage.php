<?php
/**
* class Frontpage: Frontend page class controller
**/
class Frontpage extends CI_Controller {

    protected function require_login($redirect_url=''){
		$login_info = $this->session->userdata('login_info');
		$all_session = $this->session->all_userdata();
//		echo "Login Info: " . var_dump($login_info);
		
		if ($login_info == false){
			redirect(base_url() . 'index.php?/frontpage/login?redirect_url=' . $redirect_url);
		}
	}
	protected function get_is_login(){
		return (($this->get_login_info()));
	}
	protected function get_login_info(){
		return $this->session->userdata('login_info');
	}

	//var $data = array();
	public function __construct(){
		parent::__construct();
		// loading necessary stuff
		$this->load->helper(array('form', 'url'));
		
		$this->load->model('frontpage_model');
		$this->load->model('login_model');
		$this->load->model('rooms_model'); 
		// libraries
		$this->load->library('form_validation');
		$this->load->library('encrypt');
		$this->load->library('session');
		
		//$this->session->userdata('login_id',''); // initialized.
		// $this->session->set_userdata('some_name', 'some_value');
		// get all: $this->session->all_userdata();
		//$this->session->unset_userdata('some_name');
		
		$is_session_ok = session_start(); // start a session.
		if (!$is_session_ok){
			echo "NO Session,";
		}

		//$lang = "simplified_chinese";

		$lang = $this->input->cookie('lang');
		if (!isset($lang)){			
			$lang = "english";
		}
		/*
			$lang = $this->input->get('lang');
			$_SESSION['lang'] = $lang;
		}
		*/
		/*
		else{
			if (isset($_SESSION['lang'])){
				$lang = $_SESSION['lang'];
			}else{
				$lang = "english";
			}
		}
		*/

		$this->lang->load("page", $lang);
		// $enquiry = $this->lang->line("page_enquiry");
		
	
	} // __construct()
	
	function get_lang(){
		$lang = $this->input->cookie('lang');
		if (!isset($lang) || (strlen($lang)<=0)) $lang = 'english'; // default
		return $lang;
	}
	
	public function get_company_info(){
		$this->load->model('company_model');
		$company_list = $this->company_model->get();
		$company = $company_list[0];
	
		$lang = $this->get_lang();
		
		$data['company_name'] = (array_key_exists('company_name_' . $lang, $company) && strlen($company['company_name_' . $lang])>0)? $company['company_name_' . $lang]: $company['company_name_en'];
		$data['company_address'] = (array_key_exists('company_address_' . $lang, $company) && strlen($company['company_address_' . $lang])>0)? $company['company_address_' . $lang]: $company['company_address_en']; //$company['company_address_' . $lang];
		$data['company_tel'] = $company['company_tel'];
		$data['company_fax'] = $company['company_fax'];
		$data['company_email'] = $company['company_email'];
		return $data;
	}
	
	public function view_by_id($page_id, $data=array()){
		// setup language
		$lang = $this->get_lang();
		$menu = $this->frontpage_model->get_menu($lang);
		

		
		$page_name = $menu[$page_id]['page_name'];
		
		$cur_page = $this->frontpage_model->get_frontpage_by_name($page_name);
		$cur_page_type = $cur_page['page_type'];
		if (method_exists($this, $cur_page_type)){
			$data_temp = $this->$cur_page_type();
			// join the data
			if (is_array($data_temp)){
				$data = array_merge($data, $data_temp); // merge the data
			}
		}else{
			$this->view($page_name, $data);
		}
	}

	
	public function view($page_name = 'home', $data=array())
	{
		$page_name = urldecode($page_name);
		$page = strtolower($page_name);
		$page = str_replace(' ', '_', $page);

		// setup language
		$lang = $this->get_lang();

		
		// load the data
		$company_data = $this->get_company_info(); // get the company data first.
		foreach ($company_data as $key=>$value){
			$data[$key] = $value;
		}
		$data['page'] = array();
		$data['menu'] = $this->frontpage_model->get_menu($lang);
	
		
			// added logout
		
		if ($this->get_is_login()!=false){
			// login already
			
			$menu = $data['menu'];
			foreach ($menu as $menu_index=>$menu_content){
				if (strtolower($menu[$menu_index]['menu_name']) == 'login'){
					// turn login into logout
					$menu[$menu_index]['menu_name'] = 'Logout';
					$menu[$menu_index]['menu_url']  = 'index.php?/frontpage/logout';
				}
				if (strtolower($menu[$menu_index]['menu_name']) == 'signup'){
					// turn login into logout
					$menu[$menu_index]['menu_name'] = 'My Account';
					$menu[$menu_index]['menu_url']  = 'index.php?/frontpage/my_account';
				}
				
			} // foreach
			$data['menu'] = $menu;
		}
		
		$cur_page = $this->frontpage_model->get_frontpage_by_name($page_name);
		if (sizeof($cur_page)<=0){
			$cur_page_type = strtolower($page_name);
		}else{
			$cur_page_type = $cur_page['page_type']; // content, login, about_us, etc
		}
		
		
		//if ( ! file_exists(APPPATH.'/views/frontpage/'.$page.'.php'))
		if ($cur_page_type == 'content')
		//(!file_exists(APPPATH.'/views/frontpage/'.$page.'.php'))
		{
				// Whoops, we don't have a page for that!
			$data['title'] = ucfirst($page); // Capitalize the first letter
			// Generic Page


			$data['page'] = $cur_page;
			//$block_list = $this->frontpage_model->get_blocks_by_page_id($cur_page['page_id']);
			//$data['page_block_list'] = $block_list;
			
			
			$data['page_content'] = (array_key_exists('content_' . $lang, $cur_page) && strlen($cur_page['content_' . $lang])>0)?
				$cur_page['content_' . $lang] : 
				$cur_page['content_en'];

			$this->load->view('templates/header', $data);
			$this->load->view('templates/header_logo', $data);
			$this->load->view('templates/menu', $data);
			$this->load->view('frontpage/generic', $data);
			$this->load->view('templates/footer', $data);

			return;
		}else{

			// have the specific page
			if (file_exists(APPPATH.'/views/frontpage/'.$cur_page_type.'.php')){
				$data['title'] = ucfirst($page); // Capitalize the first letter
				$this->load->view('templates/header', $data);
				$this->load->view('templates/header_logo', $data);
				$this->load->view('templates/menu', $data);
				$this->load->view('frontpage/' . $cur_page_type , $data);
				$this->load->view('templates/footer', $data);
			}else{
				echo "Page Type: [" . $cur_page_type . "] not found.";
			}
		}
	} // view
	
	public function index()
	{
		$this->view('home');
	} // index
	
	public function booking($room_id=null){
		$this->require_login('/frontpage/booking/' . $room_id); // a session required login.
		
		// setup language
		$lang = $this->get_lang();

		$data = array();
		$data['room_id'] = $room_id;
		$data['room_data'] = $this->rooms_model->get($room_id);
				// load the data
		$company_data = $this->get_company_info(); // get the company data first.
		foreach ($company_data as $key=>$value){
			$data[$key] = $value;
		}
		$data['page'] = array();
		$data['menu'] = $this->frontpage_model->get_menu($lang);

	// booking page
		$this->view('booking', $data);
		//$this->load->view('templates/header', $data);
		//$this->load->view('templates/header_logo', $data);
		//$this->load->view('templates/menu', $data);
		//$this->load->view('frontpage/booking', $data);
		//$this->load->view('templates/footer', $data);
	}
	
	public function login(){
		$data = array();
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$data['redirect_url'] = $this->input->get_post('redirect_url');
		if ((!isset($email))|| (empty($email))){
			$this->view('login', $data);
			return array(); // there is no login information, no need to check.
		}
		if (!isset($email)){
			$email = '';
		}
		if (!isset($password)){
			$password = '';
		}
	//echo 'calling login(' . $email . ', ' . $password . ');';
		
		
		$login_info = $this->login_model->check_login($email, $password);
		
		//$data = array();
		if ($login_info['is_login'] == true){
			// login successfully, store the user info into session
//			echo "LOGIN SUCESSFUL.";
			$this->session->set_userdata('login_info', $login_info);

			//$data['redirect_url'] = $redirect_url;
			$this->view('login_successful', $data);
			$data['is_login'] = true;
		}else{
			// login failed.
//			echo "LOGIN FAILED.";
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
			//$this->form_validation->set_rules('password', 'Password', 'required');
			$is_valid_form = $this->form_validation->run();
			$data['email'] = $email;
			$data['password'] = $password;
			$data['login_error'] = 'Incorrect Login. Please try again.';
			$this->view('login', $data); // display 
		}
		

		return $data;
	} // function login
	
	public function logout(){
		$this->session->unset_userdata('login_info');
		$data = array();
		$this->view('logout_successful', $data);
	}
	
	// validate on the input list
	protected function get_request(){
		$input_list = array(
					'room_id',   // room id
					'payment_gateway', // either unipay or paypal
					'prc_bank',
					'bf_from', // date-from (mm/dd/yyyy)
					'bf_to',  // date-to (mm/dd/yyyy)
					'bfnon', // no. of nights
					'bfnog', // no. of guests
					'bfhkd', // FX rate (e.g. 0.129, means HKD * 0.129 = USD amount)
					'bfcc', // currency 1: HKD, 2: USD, 3: CNY
					'ba_daily', // daily available ?
					'ba_weekly', // booking available weekly ?
					'ba_monthly', // booking available monthly ?
		);
			
		// grab the input
		$request = array();
		foreach ($input_list as $cur_input){
			$cur_post = $this->input->post($cur_input);
			$cur_get  = $this->input->get($cur_input);
			if (!empty($cur_get)) { $request[$cur_input] = $cur_get; }
			if (!empty($cur_post)){ $request[$cur_input] = $cur_post; }
		}
		
		return $request;
	}
	
	// utility for request_payment()
	protected function convert_request_to_paypal_booking($request){
		// $this->load->model('room_model'); // loaded in constructor
		
		// all valid input in $request
		// paypal_booking(
		$pp_book = array();
		
		// check the input values.
		
		// room id, assume correct, as incorrect record wll only get not available.
		$obj_room = $this->rooms_model->get($request['room_id']);
		$pp_book['room'] = $obj_room['id']; // id is the room_id 
		
		$dt_from = DateTime::createFromFormat('m/d/Y', $request['bf_from']);
		if ($dt_from==false) return false; // invalid form.
		$pp_book['from_date'] = $dt_from->format('Y-m-d'); // convert the format to MySQL

		$dt_to = DateTime::createFromFormat('m/d/Y', $request['bf_to']);
		if ($dt_to==false) return false; // invalid form.
		$pp_book['to_date'] = $dt_to->format('Y-m-d');
		
		// user id
		$login_info = $this->get_login_info();
		//
		if ($login_info==false){
			return false;
		}
		$user_id = $login_info['user_id'];
		$pp_book['uid'] = $user_id;
		
		// days
		//$days = $request['bfnon'];
		//if ($days<=0){
		//	return false;
		//}
		//$pp_book['days'] = $days;
		
		$fx_name = array(1=>'HKD', 2=>'USD', 3=>'CNY');
		if (array_key_exists($request['bfcc'], $fx_name)){
			$pp_book['currency'] = $fx_name[$request['bfcc']];
		}
		
		$payment_gateway = $request['payment_gateway'];
		if ($payment_gateway !='paypal' && $payment_gateway != 'unipay'){
			return false;
		}
		$pp_book['gateway'] = $payment_gateway;
		
		// prc_bank
		if ($pp_book['gateway']=='unipay'){
			$prc_bank = $request['prc_bank'];
			$pp_book['prc_bank'] = $prc_bank;
		}

		// amount
				
		
		return $pp_book;
	} // function convert_request_to_paypal_booking()
		
	// request payment for both paypal and unipay
	public function request_payment(){
		// process the payment request
		
		//echo "request payment.";
		
		
		$this->load->model('paypal_model');
		$this->load->model('unipay_model');
		//echo "model loaded.";
		
		$request = $this->get_request();
		$pp_book = $this->convert_request_to_paypal_booking($request);
		
	
		
		// insert into paypal_booking		
		$this->load->model('paypal_booking_model');		
		
		$amt = $this->paypal_booking_model->calculate_amount($pp_book);
		if ($amt<0){
			echo "Invalid Booking request.";
			return;
		}		
		$pp_book_id = $this->paypal_booking_model->insert_booking($pp_book);

		$request['paypal_book_id'] = $pp_book_id;
		
		$payment_gateway = $this->input->post('payment_gateway');
		
		
		if ($payment_gateway=='paypal'){
		//echo "paypal payment.";
		
			$this->paypal_model->process_payment($request);
		}else if ($payment_gateway=='unipay'){
		//echo "unipay payment.";
			$params = $this->unipay_model->process_payment($request);
			$data = array();
			$data['params'] = $params;
			// unipay need to display a form and auto submit;
			$this->load->view('unipay_handler/test_submit', $data);
		}else{
			//echo "Unknown Payment Method :" . $payment_gateway ;
		}
		//echo "<p> Finish Payment.";
	}
	
	// Protected methods - in the same name as the page_type
	protected function price(){
		// price table page.
		// look for all the room information
		$room_list = $this->rooms_model->get(); // get all
		$data['room_list'] = $room_list;
		$this->view('price', $data);
		return $data;
	}

	public function sign_up(){
	
//		echo "Calling Frontpage->sign_up();\n<br>";
		
		$field_list = array(
		'firstname' => array('db'=>'fname', 'rule'=>'required'),
		'lastname' => array('db'=>'lname', 'rule'=>'required'),
		'email' => array('db'=>'email', 'rule'=>'required|valid_email'),
		'country' => array('db'=>'cc', 'rule'=>'required'),
		'phone' => array('db'=>'phone', 'rule'=>'required'),
		'password' => array('db'=>'password', 'rule'=>'required|matches[confirm_password]'),
		'confirm_password' => array('db'=>'', 'rule'=>'required'),
		);
		//$input_array = array('fname', 'lname', 'email', 'cc', 'phone', 'password','conpassword');
		$no_input = 0;
		$db_data = array();
		$input_data = array();
		$data = array(); // data for return and rendering.
		foreach ($field_list  as $input_name=>$field_content){
			$field_name = $field_content['db'];
			$rule = $field_content['rule'];
			$this->form_validation->set_rules($input_name, ucfirst($input_name), $rule);
			$db_data[$field_name] = $this->input->post($input_name);
			$input_data[$input_name] = $this->input->post($input_name);
			
			$data[$input_name] = $input_data[$input_name];
			if (strlen($db_data[$field_name])>0){
				// have some input
				$no_input++;
			}
		}

//		echo "running validation?";

		if ($no_input>0 && $this->form_validation->run() == FALSE)
		{
//		echo "<p>error input.";
//		echo "<p>Validation Error:[" . validation_errors() . "]";
			$page_id = 12;
			$lang = $this->get_lang();
			$menu = $this->frontpage_model->get_menu($lang);
			$page_name = $menu[$page_id]['page_name'];
			
			$this->view($page_name, $data); // page 12 is sign up
		}
		elseif ($no_input>0)
		{
			$this->load->model('users_model');
			unset($data['confirm_password']); // no such db_field
			$data['password'] = md5($data['password']);
			$data['activation'] = md5($data['email'].time()); 
			$data['status'] = '2';
			$this->users_model->insert($data);
		
			$this->view('signup_success', $input_data);
		}else{
			// case of no input, follow normal flow, view_by_id will display
			$this->view('sign_up', $data);
			$data['_supress_view'] = true;
		}

		return $data;
	}
	
	public function payment_success(){	
		$this->view('payment_success');
	} // payment_successful page
	
	public function payment_cancel(){
		$this->view('payment_cancel');
	} // payment_successful page
	
	// for jquery
	public function convert_fx(){
		$this->load->model('currency_fx_model');
		$amount = $this->input->get_post('amt');
		$from   = $this->input->get_post('from');
		$to     = $this->input->get_post('to');
		$result = $this->currency_fx_model->convertCurrency($amount, $from, $to);

		echo round($result, 2);
	}
	// for jquery
	public function calculate_amount(){
		$this->load->model('paypal_booking_model');
		
		$room_id = $this->input->get_post('room_id');
		$dt_from   = DateTime::createFromFormat('m/d/Y', $this->input->get_post('from_date'));
		
		$from_date = $dt_from->format("Y-m-d");
		$dt_to     = Datetime::createFromFormat("m/d/Y", $this->input->get_post('to_date'));
		$to_date   = $dt_to->format("Y-m-d");
		$fx			 = $this->input->get_post('fx');
		$result = $this->paypal_booking_model->calculate_amount($room_id, $from_date, $to_date, $fx);

		echo round($result, 2);	
	} // calculate_amount in fx
	
} // class Pages
?>