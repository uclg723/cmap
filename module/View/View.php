<?php
if (!class_exists('View')){
	//include('module/Model/Model.php');
	//include_once('module/BaseClass/BaseClass.php');
	
	include(__DIR__ .'/../../module/Model/Model.php');
	include_once(__DIR__ .'/../../module/BaseClass/BaseClass.php');
	
	
	abstract class View extends BaseClass{
		protected $view_type;
		protected $option;
		protected $model;
		public function __construct(){
			$this->view_type = strtolower(get_class($this));
			$this->option = array(
				'mode'  => 'normal',
				'header'=>true,
				'menu'  =>true,
				'content'=>true,
				'footer'=>true,			
			); // set option
			$this->model=null;
		} // __construct
	
		// set the display option
		public function setOption($_option){
			foreach ($_option as $key=>$val){
				$this->option[$key] = $val;
			}
		} // setOption
		
		
		public function setModel($model){
			$this->model = $model;
		}
		
		
		public function display($return_display=false){
			return '';
			// display the output
		}
		
	} // class
} //if
?>