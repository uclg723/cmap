<?php
class Frontpage_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	public function get_frontpage_by_name($page_name = FALSE)
	{
		if ($page_name === FALSE)
		{
			$query = $this->db->get('page');
			return $query->result_array();
		}

		$query = $this->db->get_where('page', array('page_name' => $page_name));
		return $query->row_array();
	} // function get_page
	
	
	public function get_blocks_by_page_id($page_id=null)
	{

/*	$this->db->select('*');
		$this->db->from('page_block');
		$this->db->where(array(
			'parent_page_id'=> $page_id
		));
		$query = $this->db->get();
*/				
		$query = $this->db->get_where(
			'page_block',
			array(
				'parent_page_id'=> $page_id
			)
		);		
		return $query->result_array();	
	}
	
	
	public function get_menu($lang='en'){
		if (!in_array($lang, array('en', 'hk', 'cn'))){
			$lang = 'en';
		}
		$field_name = 'menu_' . $lang;
		
		
		$this->db->select($field_name .' , menu_order, menu_en as menu_default, page_name, page_id ');
		$this->db->from('page');
		$this->db->where(array(
			'menu_visible'=> 1,
		));
		$this->db->order_by('menu_order',$field_name);
		$query = $this->db->get();
		$result_array = $query->result_array();
		$return_arr = array();
		foreach ($result_array as &$temp){			
			if (strlen($temp[$field_name])>0){
				$temp['menu_name'] = $temp[$field_name];
			}elseif (strlen($temp['menu_default'])>0){
				$temp['menu_name'] = $temp['menu_default'];
			}else{
				$temp['menu_name'] = $temp['page_name'];
			}
			$return_arr[$temp['page_id']] = $temp;
		}
		return $return_arr;	
	} // function get_menu
} // class