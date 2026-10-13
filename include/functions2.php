<?php
//$base_url="http://www.cmaphk.com/";
// Debug setting from Ben Fung on 2014-05-01
//if ($GLOBALS['_SERVER']['SERVER_NAME']=='localhost'){
//	$base_url="http://localhost:8088/cmaphk/";
//}
//include_once("analyticstracking.php");

if (!defined('FUNCTION_FILE_INCLUDED')){
	define('FUNCTION_FILE_INCLUDED',true);

	//$root_path = dirname(__FILE) . '/..';
	//set_include_path($root_path);
	if (!defined('SET_CMAPHK_INCLUDE_PATH')) {
		$root_path = realpath(dirname(__FILE__) . '/..' );
		$include_path = get_include_path();
		if (strpos($include_path, $root_path)==false){
			$all_include_path = $include_path . ';' . $root_path;
			set_include_path($all_include_path);
		}
		define('SET_CMAPHK_INCLUDE_PATH', $root_path);
	}
	
	include(__DIR__ .'/../config/paypal_config.php');
	global $base_url;
	
	global $mysql_link;
	include(__DIR__ .'/../config/db_config.php');
	
	function connect(){
		global $mysql_link;
		global $db_config;
		 $mysql_link = mysql_connect($db_config['hostname'],$db_config['username'],$db_config['password']) or die("Cannot Connect to sever");
		 mysql_select_db($db_config['database']) or die("Cannot Connect to database");
		return $mysql_link;
	} // connect
	  
	function close(){
		  mysql_close();
		  $mysql_link = null;
	} // close
	  
	  // To clean the input
	  function cleanInput($input) {
	 
		  $search = array(
			'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
			'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
			'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
			'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
		  );
		 
		  $output = preg_replace($search, '', $input);
		  return $output;
	  }
	  
	  function sanitize($input) {
			if (is_array($input)) {
				foreach($input as $var=>$val) {
					$output[$var] = sanitize($val);
				}
			}
			else {
				if (get_magic_quotes_gpc()) {
					$input = stripslashes($input);
				}
				$input  = cleanInput($input);
				$output = mysql_real_escape_string($input);
			}
			return $output;
		}
	
	
	function random_password($chars = 8) {
	   $letters = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	   return substr(str_shuffle($letters), 0, $chars);
	}
	function convertCurrency($amount, $from, $to){
	    $url  = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
	    $data = file_get_contents($url);
	    preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
	    $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
	    return round($converted,10);
	}	

} // if defined
?>