<?php
if (!class_exists('Paypal_booking')){
//	include('module/MysqlObject/MysqlObject.php');

	include(__DIR__ .'/../../module/MysqlObject/MysqlObject.php');
//	echo "after MysqlObject class load<br>";
//	echo "after MysqlObject class exist:" . class_exists('MysqlObject'). "\n<br>";
	class Paypal_booking extends MysqlObject{
			
		public function get_booking_by_id($id){
			$sql = "SELECT * FROM {$this->_table_name} " . 
				   " WHERE ( id=:id ) " .
				   " ;";
			$dbh = $this->get_dbh();
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(":id", $id);
			
			
			try{
				$is_done = $stmt->execute();
			}catch (Exception $e){
				echo $e->getMessage();
			}
			//		if ($stmt->rowCount<=0){
			//			throw new Exception("Cannot retrieve object of class " . get_class($this) . " with id = " . $id);
			//		}

			$pk = $this->getPrimaryKey();
			$pk = $pk[0];
			$return_set = array();
			while($row = $stmt->fetchObject('Paypal_booking')){
				$cur_obj = $row;
//				$cur_obj = new $class_name(); // just copy, faster
//				foreach ($this->_description as $field_name=>$field_desc){
//					$cur_obj->$field_name = $row[$field_name];
//				}
				if (isset($pk)){
					$cur_id = $cur_obj->$pk;
					$return_set [$cur_id] = $cur_obj;
				}else{
					$return_set [] = $cur_obj;
				}
			}
			
			$stmt = null;
			$row = null;
			
			if (sizeof($return_set)<=0){
				return false;
			}
			return $return_set;
		} // get_booking_list_between
		
	} // class Paypal_booking
	

} // if class not defined
?>