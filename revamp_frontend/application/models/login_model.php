<?php
class Login_model extends CI_Model {

	var $field_list = array(
		'company_name_en',
		'company_name_hk',
		'company_name_cn',
		'company_tel',
		'company_fax',
		'company_email',
	);
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	public function check_login($email, $password){
	
//		$query = $this->db->get('company');
//		return $query->result_array();

	
		$query = $this->db->get_where(
			'users', 
			array(
				'email' => $email,
				'password' => md5($password),
			)
		);
		$user_info = $query->row_array();
		
		$is_login = count($user_info)>=1;
		
		if ($is_login){
			$user_id = $user_info['id'];
			$user_first_name = $user_info['firstname'];
			$user_last_name = $user_info['lastname'];
			$user_utype = $user_info['utype']; // 1-users,2-admin,3-manager,4-staff
			$user_is_admin = ($user_utype == 2);
		}else{
			$user_id = $user_first_name = $user_last_name = $user_utype = null;
			$user_is_admin = false;
		}
		$user_type_name = array(
			1=>'users',
			2=>'admin',
			3=>'manager',
			4=>'staff',
		);
		$data = array(
			'is_login' => $is_login,
			'user_id'  => $user_id,
			'user_first_name' => $user_first_name,
			'user_last_name' => $user_last_name,
			'user_utype' => $user_utype,
			'user_utype_name' => array_key_exists($user_utype,$user_type_name)?$user_type_name[$user_utype]:'',
			'user_is_admin' => $user_is_admin,
		);
		return $data;
	}
	
	public function store_login($data){
	
	}
	
	public function get($company_id = FALSE)
	{
	} // function get_page
	
	public function insert()
	{
	} // set_page
	public function update($company_id)
	{
	} // set_page
	
	public function delete($company_id){
	}
	
	
	
} // class