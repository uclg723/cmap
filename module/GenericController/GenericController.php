<?php

// generic controller
if (!class_exists('GenericController')){
	
//include_once('module/BaseClass/BaseClass.php');
include_once(__DIR__ .'/../../module/BaseClass/BaseClass.php');
abstract class GenericController extends BaseClass{
	
	protected $processQueue = array();
		
	
	protected $module_name = null;
	protected $focus = null; // the object of focus
	protected $view = null; // view object

	public function __construct(){
		// nothing to do.
	}
	
	public function processAndRender(){
		$this->process();
		$this->render();
	}
	
	
	// setModelByName: not used
	public function setModelByName($module_name){
		$this->module_name = $module_name;
		if ($this->module_name == null) return false; // not processed
		if (!class_exists($module_name)){
			$model_class_path = 'module/' . $module_name . '/' . $module_name . '.php';
			//if (file_exists($view_class_path)){
			include($model_class_path);
			//}
		}
		
		$class_name = $this->module_name;
		$this->focus = new $class_name();
	}
	
	// setViewByName: not used
	public function setViewByName($view_name){
		// check if we have included the relevent view class yet
		if (!class_exists($view_name)){
			$view_class_path = 'module/' . $view_name . '/' . $view_name . '.php';
			//if (file_exists($view_class_path)){
			include($view_class_path);
			//}
		}
		$this->view = new $view_name();
		return true;
	}
	// setModel: directly setup the model by copy
	public function setModel($model){
		$this->focus = $model;
	}
	// setView: directly assign the view object, which can be customized before
	public function setView($view){
		$this->view = $view;
	}
	
	
	public function process(){
		foreach ($this->processQueue as $process=>$param){
			$this->$process($param);
		}
	
	} // process the request
	
	public function render($return_str=false){
		$this->view->setModel($this->focus);
		return $this->view->display($return_str);
	}
	
		
} // class

} //if
?>