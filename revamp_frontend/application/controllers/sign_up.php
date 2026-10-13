<?php
// class Page_template: Base class for all pages, setup the basic structure
//
class Sign_up extends CI_Controller {

	function __construct(){
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	}

	function index(){ // default
		echo "Calling sign_up() - Deprecated page..<br>";
		$field_list = array(
			'firstname' => 'fname', 
			'lastname' => 'lname', 
			'email' => 'email', 
			'country' => 'cc', 
			'phone' => 'phone', 
			'password' =>'password', 
			'confirm_password' => 'conpassword',
		);
		//$input_array = array('fname', 'lname', 'email', 'cc', 'phone', 'password','conpassword');


		
		$no_input = 0;
		$data = array();
		foreach ($field_list  as $input_name=>$field_name){
			$data[$field_name] = $this->input->post($input_name);
			if (strlen($data[$field_name])>0){
				// have some input
				$no_input++;
			}
		}
		if ($no_input==0){
			// report error
			echo "no input.";
		}else{
			$this->load->model('users');
			$user =& $this->users;
			$user->create($data);
			$user->save();

		}
		echo " finish sign_up(); ";
	}
}

?>