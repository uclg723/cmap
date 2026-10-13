<?php
class Company_model extends CI_Model {

	var $field_list = array(
		'company_name_en',
		'company_name_hk',
		'company_name_cn',
		'company_address_en',
		'company_address_hk',
		'company_address_cn',
		'company_tel',
		'company_fax',
		'company_email',
	);
	public function __construct()
	{
		$this->load->database();
	}
	
	/**
	* get_option_list: used for other modules to grab the page name and company_id as pair
	* return array of (id, company_name)
	**/
	public function get_option_list($limit_visible=FALSE){
		$this->db->select(array('company_id', 'company_name'));
		$query = $this->db->get('company');
		return $query->result_array();	
	}
	
	public function get($company_id = FALSE)
	{
		if ($company_id === FALSE)
		{
			$query = $this->db->get('company');
			return $query->result_array();
		}

		$query = $this->db->get_where('company', array('company_id' => $company_id));
		return $query->row_array();
	} // function get_page
	
	public function insert()
	{
//		$this->load->helper('url');

		$data = array();
		foreach ($this->field_list as $cur_field){
			$cur_data = $this->input->post($cur_field);
			if (isset($cur_data)){
				$data[$cur_field] = $cur_data;
			}
		}

		return $this->db->insert('company', $data);
	} // set_page
	public function update($company_id)
	{
//		$this->load->helper('url');

		$data = array();
		foreach ($this->field_list as $cur_field){
			$cur_data = $this->input->post($cur_field);
			if (isset($cur_data)){
				$data[$cur_field] = $cur_data;
			}
		}

		$this->db->where('company_id', $company_id);
		return $this->db->update('company', $data);
	} // set_page
	
	public function delete($company_id){
		$this->db->where('company_id', $company_id);
		$this->db->delete('company'); 
	}
	
} // class