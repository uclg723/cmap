<?php
// BaseClass: provide basic function for Model View and Controller to inherit.
if (!class_exists('BaseClass')){

	// What to load up for every single module class?
	// set the root include path
	if (!defined('CMAP_FILE_BASE')){
		if (!defined('SET_CMAPHK_INCLUDE_PATH')) {
			$root_path = realpath(__DIR__ . '/../../' );
			$include_path = get_include_path();
			if (strpos($include_path, $root_path)<0){
				$all_include_path = $include_path . ';' . $root_path;
				set_include_path($all_include_path);
			}
			define('SET_CMAPHK_INCLUDE_PATH', $root_path);
		}
	
		global $CMAP_FILE_BASE;
		$CMAP_FILE_BASE = realpath(__DIR__);
		define('CMAP_FILE_BASE', $CMAP_FILE_BASE);;
	} // if not defined
	
	
	class BaseClass{
		static function loadClass($targetClass){
			if (!class_exists($targetClass)){
				// try to locate the class definition
				global $CMAP_FILE_BASE;
				$class_file_path = $CMAP_FILE_BASE . "/module/{$targetClass}/{$targetClass}.php";
				if (file_exists($class_file_path)){
					require_once($class_file_path);
					return true;
				}else{
					return false;
				}
			}
			return true;				
		}
	} // class

} // if class not defined.