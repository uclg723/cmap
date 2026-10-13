<?php
class Rooms_model extends CI_Model {

	var $field_list = array(
		'id',
		'room_name', //
		'nor',  // no. of rooms
		'description', // visible to user
		'add_info', // for admin use
		'sq_feet',
		'mamount', // amount in USD
		'wamount',
		'damount',
		'status', // 1: not active, 2: active
		'daily_booking', //1-allowed,2-not allowed
		'weekly_booking',
		'monthly_booking',
		'created', // timestamp
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
		$this->db->select(array('id', 'room_name'));
		$query = $this->db->get('rooms');
		return $query->result_array();	
	}
	
	public function retrieve($room_id){
		return $this->get($room_id);
	}
	
	public function get($room_id = FALSE)
	{
		if ($room_id === FALSE)
		{
			$query = $this->db->get('rooms');
			return $query->result_array();
		}

		$query = $this->db->get_where('rooms', array('id' => $room_id));
		return $query->row_array();
	} // function get_page
	
	// calculate the amount in local currency (assume HKD), 
	// return -1 if invalid selection.
	public function calculate_amount($room_id, $from_date, $to_date){
		$room = $this->get($room_id);
		
		$dt_from = DateTime::createFromFormat('Y-m-d', $from_date);
		$dt_to   = DateTime::createFromFormat('Y-m-d', $to_date);
		$no_of_night = (int) $dt_from->diff($dt_to)->format('%a'); 
		$mamount = $room['mamount'];
		$damount = $room['damount'];
		$wamount = $room['wamount'];
		
		$is_daily_book = ($room['daily_booking']==1); // 1: available, 2: not available
		$is_weekly_book = ($room['weekly_booking']==1); // 1: available, 2: not available
		$is_monthly_book = ($room['monthly_booking']==1); // 1: available, 2: not available
		$room_active = ($room['status']==2); // 2: active, 1: inactive
		
		if (!$room_active){ return -1; }
		
		$month_amount = 0;
		$week_amount = 0;
		$day_amount = 0;		
		$original_amount = $no_of_night * $damount;		if ($is_monthly_book){
			// can use monthly rate
			$no_of_month = intval($no_of_night / 30);
			$month_amount = $no_of_month * (double) $mamount;
			$no_of_night = $no_of_night % 30;
		}
		if ($is_weekly_book){
			$no_of_week = intval($no_of_night / 7); 
			$week_amount = $no_of_week * (double) $wamount;
			$no_of_day  = $no_of_night % 7; 		}
		if ($is_daily_book){
			$day_amount = $no_of_night * (double) $damount;
		}
	
		$total_amount = $month_amount + $week_amount + $day_amount;

		
		
		if ($total_amount==0){ return -1; } // error, no free nights.
		return $total_amount;
	}
	
	/*
	* Disable all the Create-Update-Delete functions
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
	*/
	
} // class