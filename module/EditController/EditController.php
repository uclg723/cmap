<?php

if (!class_exists('GenericController')){
//	include('module/GenericController/GenericController.php');
	include(__DIR__ .'/../../module/GenericController/GenericController.php');
	
	
	
	class EditController extends GenericController{
		
		public function __construct(){
			parent::__construct();
			$this->processQueue = array(
				'processRequest' =>array(),
			);
		}
					
		protected function processRequest($param){
			// parse the request and generate the internal object
			
			if (is_null($this->focus)) return false; // not processed
			$focus =& $this->focus; // alias
			$pk = $focus->getPrimaryKey();
			if (sizeof($pk)>1){
				// multiple primary key, not handled 
			}
			$pk_name = $pk[0];
			if (!(empty($_REQUEST[$pk_name]))){
				// means we have a request of the primary key name,
				// we will load the object from database
				$pk_id = $_REQUEST[$pk_name];
				$focus->retrieve($pk_id);
			}
			// No matter retrieved the record or not, we fill out the record
			$desc = $focus->getDescription();
			foreach ($desc as $key=>$cur_desc){
				// check if the _$REQUEST is valid?
				if (!empty($_REQUEST[$key])){
					$focus->setField($key, $_REQUEST[$key]);
				}
			}
			
			if (isset($_REQUEST['submit'])){
				// kind of post back.
				try{
					$sql = $focus->save();
				}catch (Exception $e){
					$this->view->pushError($e->getMessage());
				}
			}	
			
			return true; // done.		
		} // processRequest
		
	} // class
}
?>