<?php
class Page_block_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	public function get_by_prent_page_id($parent_page_id){
		$this->db->where(array(
			'parent_page_id' => $parent_page_id,
			
		));
		$query = $this->db->get('page_block');
		return $query->result_array();
	}
	
	public function get($page_block_id = FALSE)
	{
		if ($page_block_id === FALSE)
		{
			$query = $this->db->get('page_block');
			return $query->result_array();
		}

		$query = $this->db->get_where(
			'page_block', 
			array('page_block_id' => $page_block_id));
		return $query->row_array();
	} // function get_page
	
	public function insert($data=array())
	{

		$field_list = array(
			'parent_page_id',
			'display_order',
			'is_visible',
			'div_class',
			'content_en',
			'content_tc',
			'content_sc',
			'block_image',
		);
		foreach ($field_list as $cur_field){
			$cur_val = $data[$cur_field]; // $this->input->post($cur_field);
			 if (isset($cur_val)){
				$data[$cur_field] = $cur_val;
			 }
		}// foreach

		return $this->db->insert('page_block', $data);
	} // set_page
	
	
	public function update($page_block_id)
	{

		$field_list = array(
			'parent_page_id',
			'display_order',
			'is_visible',
			'div_class',
			'content_en',
			'content_tc',
			'content_sc',
			'upload_file',
		);
		foreach ($field_list as $cur_field){
			$data[$cur_field] = $this->input->post($cur_field);
		}// foreach
		$this->db->where('page_block_id', $page_block_id);
		return $this->db->update('page_block', $data);
	} // set_page
	
	public function delete($page_block_id){
		$this->db->where('page_block_id', $page_block_id);
		$this->db->delete('page_block'); 
	}
	
} // class