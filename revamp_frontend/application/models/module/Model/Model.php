<?php
// Base Class for all Model, mainly MysqlObject
if (!class_exists('Model')){
//include_once('config.php');
//include_once('module/BaseClass/BaseClass.php');

include_once(__DIR__ .'/../../config.php');
include_once(__DIR__ . '/../../module/BaseClass/BaseClass.php');


 abstract class Model extends BaseClass{
	abstract public function create(); 		// Create new
	abstract public function getPrimaryKey(); // return the primary key description
	abstract public function retrieve($id); 	// Retrieve
	abstract public function save(); 		// Update
	abstract public function delete(); 		// Delete	
	abstract protected function describe(); // describe the model
	protected $_cached_module_label = null;	
	function translateLabel($label, $lang='en',$force_reload=false){
		
		if ($forced_reload || (is_null($this->_cached_module_label))){
			$class_name = get_class($this);
			//global $CMAP_FILE_BASE;
			$module_label_file =  __DIR__ . '/../../module/' . $class_name . '/lang/module_label.php'; 
			if (file_exists($module_label_file)){
				include($module_label_file);
				// $module_label defined.
				$this->_cached_module_label = $module_label;
			}
		} // load the _cached_module_label
		$label_str = $this->_cached_module_label[$lang][$label];
		if (empty($label_str)){
			$label_str = $this->_cached_module_label['en'][$label];
		}		
		return $label_str;
	} // function
	protected $_option_list = null;
	function getOptionList($field_name, $lang='en', $force_reload=false){
		if ($forced_reload || (is_null($this->_cached_module_label))){
			$class_name = get_class($this);
			//global $CMAP_FILE_BASE;
			$option_list_file = __DIR__ . '/../../module/' . $class_name . '/lang/option_list.php';
			if (file_exists($option_list_file)){
				include($option_list_file);
				// $module_label defined.
				$this->_option_list = $option_list;
			}
		} // load the _cached_module_label
		$cur_option_list = $this->_option_list[$class_name][$lang][$field_name];
		if (empty($cur_option_list)){
			$cur_option_list = $this->_option_list[$class_name]['en'][$field_name];
		}
		return $cur_option_list;
		
	}
	
} // class
} // if not defined.

?>