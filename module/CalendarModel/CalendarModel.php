<?php


if (!class_exists('CalendarModel')){
	//include('module/MysqlObject/MysqlObject.php');
	include(__DIR__ .'/../../module/MysqlObject/MysqlObject.php');
	include_once(__DIR__ .'/../../module/Booking/Booking.php');
	include_once(__DIR__ .'/../../module/Rooms/Rooms.php');
	
	class CalendarModel extends MysqlObject {

		public function __construct(){
			//echo "Mysqlobject::connect_to_pdo();<br>";
			Mysqlobject::connect_to_pdo(); // get ready of the PDO connection first
			//echo "Mysqlobject::get_class();<br>";
			//$class_name = get_class($this);
			$this->_table_name = ''; //strtolower($class_name);
			//echo "Mysqlobject::describe();<br>";
			//$this->describe(); // ask the mysql to describe the table and record in
			//echo "Mysqlobject::_load_field_description();<br>";
			$this->_load_field_description();
		
		} // function __construct
		
		public function __destruct(){
			//$this->dbh = null; // disconnect;
		} // function __destruct

		function get_booking_map($date_start='2014-06-01', $date_end='2014-08-31'){
			$book = new Booking();
			$booking_list = $book->get_booking_list_between($date_start, $date_end);

			
			
			
			$room_list = $this->get_room_list();
			$date_list = $this->get_date_list($date_start, $date_end);
			$booking_map = array();
			foreach ($room_list as $room_id=>$room_obj){
				foreach ($date_list as $date_id=>$date_str){
					$booking_map[$room_id][$date_id] = null;
					
					//array(
					//	'booking_id' => null,
					//	'booking_type'	 => 'none',
					//	'booking_type_last'=> 'none',
					//);
				}
			}
			$booking_type_map = array(
				1 => 'phone',
				2 => 'online',
				3 => 'vip',
			);
			foreach($booking_list as $cur_book_id=>$cur_book){
				$date_in = $cur_book->date_in;
				$date_out = $cur_book->date_out;
				$di = DateTime::createFromFormat('Y-m-d', $date_in);				
				$do = DateTime::createFromFormat('Y-m-d', $date_out);
				$do_ts = $do->getTimestamp();
				
				if ($di->format('Y-m-d')<$date_start){
					// advance the date until it is our target range.
					$di->add(new DateInterval('P1D'));
				}
				
				while ($di->getTimestamp()<$do_ts && 
					   $di->format("Y-m-d")<=$date_end){
					$rid = $cur_book->rid;
//					$booking_id = $cur_book->id;
					$cur_date_str = $di->format('Y-m-d');
					$booking_map[$rid][$cur_date_str] = $cur_book;
					
//					array(
//						'booking_id' => $cur_book->id,
//						'booking_type'=> $booking_type_map[$cur_book->btype],
//					);
					$di->add(new DateInterval('P1D'));
				}
			}
			
			return $booking_map;
		} // function get_booking_map
		
		public function get_room_list(){
			$room = new Rooms();
			$room_list = $room->retrieveByFilter(); // all the rooms
			return $room_list;
		} // function get_room_list
		
		public function get_date_list($date_start='2014-06-01', $date_end='2014-08-31', $index_format='Y-m-d', $content_format='Y-m-d'){
			$ds = DateTime::createFromFormat('Y-m-d', $date_start);
			$de = DateTime::createFromFormat('Y-m-d', $date_end);
			$date_end_ts = $de->getTimestamp();
				
			$cur_date = DateTime::createFromFormat('Y-m-d', $date_start);
				
			$date_list = array();
			while ($cur_date->getTimestamp()<=$date_end_ts){
				$cur_date_idx = $cur_date->format($index_format); // index
				$cur_date_str = $cur_date->format($content_format); // content	
				$date_list[$cur_date_str] = $cur_date_str;
				$cur_date->add(new DateInterval('P1D')); // advance 1 day.			
			}
			return $date_list;	
		} // function get_date_list
		
	} // class Booking
} //if


?>