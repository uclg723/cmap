<?php


require_once('basic_model.php');
class logger_model extends Basic_model{

	public function write_log($log_msg, $log_level='debug'){
		$data = array(
			//logger_id: auto increment id
			// create_time: system time
			'log_level'=>$log_level,
			'content'=> $log_msg,
		);
		$id = $this->insert($data);
		return $id;
	}
} //
?>