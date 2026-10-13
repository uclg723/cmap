<?php

// Old Config file. the actual connect was moved to include/functions.php already by india programmer
// //141903
// mysql_connect("localhost","webscwi1_cmaphku","cw~0tz+?lzHm") or die("Cannot Connect to sever");
// mysql_select_db("webscwi1_cmaphk") or die("Cannot Connect to database");

// set the root include path
if (!defined('CMAP_FILE_BASE')){
if (!defined('SET_CMAPHK_INCLUDE_PATH')) {
	$root_path = realpath(dirname(__FILE__) );
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

?>