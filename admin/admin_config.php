<?php

// set the root include path
if (!defined('SET_CMAPHK_INCLUDE_PATH')) {
	$root_path = realpath(dirname(__FILE) . '/../');
	$include_path = get_include_path();
	if (strpos($include_path, $root_path)==false){
		$all_include_path = $include_path . ';' . $root_path;
		set_include_path($all_include_path);
	}
	define('SET_CMAPHK_INCLUDE_PATH', $root_path);	
}

?>