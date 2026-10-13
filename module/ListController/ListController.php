<?php

if (!class_exists('ListController')){
//include('module/GenericController/GenericController.php');
include(__DIR__ .'/../../module/GenericController/GenericController.php');



class ListController extends GenericController{
	
	protected $filter;
	public function setFilter($filter){
		$this->filter = $filter;
	}
	
	public function __construct(){
		parent::__construct();
		$this->processQueue = array(
			'processRequest' =>array(),
		);
	}
				
	protected function processRequest($param){
		// parse the request and generate the internal object

		
		return true; // done.		
	} // processRequest
	
} // class
} //if
?>