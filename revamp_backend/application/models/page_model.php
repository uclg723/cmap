<?php
class Page_model extends CI_Model {

	var $field_list = array(
		'page_name',
		'page_type', // page_type: what to display in frontend, content, price, contact_us, sign_up, login
		'page_description',
//		'style_sheet_id',
		'menu_order',
		'menu_visible',
		'div_class',
		'menu_en',
		'content_en',
		'menu_hk',
		'content_hk',
		'menu_cn',
		'content_cn',	
	);
	
	var $page_type_list = array(
		'content',
		'price',
		'contact_us',
		'sign_up',
		'login',
	);
	
	public function get_page_type_list(){
		return $this->page_type_list;
	}
	
	public function __construct()
	{
		$this->load->database();
	}
	
	/**
	* get_option_list: used for other modules to grab the page name and page_id as pair
	* return array of (id, page_name)
	**/
	public function get_option_list($limit_visible=FALSE){
		$this->db->select(array('page_id', 'page_name'));
		$query = $this->db->get('page');
		return $query->result_array();	
	}
	
	public function get_page($page_id = FALSE)
	{
		if ($page_id === FALSE)
		{
			//$query = $this->db->get('page');
			//$this->db->select('*');
			//$this->db->from('page');
			$this->db->order_by('menu_order');
			$this->db->order_by('page_id');
			$query = $this->db->get('page');
			return $query->result_array();
		}

		$query = $this->db->get_where('page', array('page_id' => $page_id));
		return $query->row_array();
	} // function get_page
	
	public function set_page()
	{
//		$this->load->helper('url');

		$data = array();
		foreach ($this->field_list as $cur_field){
			$cur_data = $this->input->post($cur_field);
			if (isset($cur_data)){
				$data[$cur_field] = $cur_data;
			}
		}

		return $this->db->insert('page', $data);
	} // set_page
	public function update_page($page_id)
	{
//		$this->load->helper('url');

		$data = array();
		foreach ($this->field_list as $cur_field){
			$cur_data = $this->input->post($cur_field);
			if (isset($cur_data)){
				$data[$cur_field] = $cur_data;
			}
		}

		$this->db->where('page_id', $page_id);
		return $this->db->update('page', $data);
	} // set_page
	
	public function delete_page($page_id){
		$this->db->where('page_id', $page_id);
		$this->db->delete('page'); 
	}
	
} // class