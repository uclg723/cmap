<?php

require_once('basic_model.php');
class Booking_model extends Basic_model{

	// CRUD is provided by Basic_model
	function is_available($room_id, $from_date, $to_date){
		if (empty($room_id) ||
			empty($from_date) || 
			empty($to_date)
		   ){
			return false;
		}
		$table_name = $this->_table_name;
		$id_field = $this->get_id_field();
		
		$sql = 'SELECT COUNT(' . $id_field . ') FROM ' . 
			   ' FROM ' . $table_name . 
			   ' WHERE ' . 
			   " room_id = ? AND " .
			   " status = 2 AND " .  
			   " ((date_in between ? AND ?) OR " . 
			   "  (date_out between ? AND ?)) ";
		
		$query = $this->db->query($sql, array ($room_id, $from_date, $to_date, $from_date, $to_date));
		$no_of_crash =  $query->row_array();
		
		
		
	}

} // class

?>