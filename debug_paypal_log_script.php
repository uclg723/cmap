<?php
//global $paypal_log_fid;

if (!function_exists('debug_paypal_log_script')){
	function debug_paypal_log_script($str='called debug_paypal_log_script'){
		$debugOn = false;
		if ($debugOn){
			$path_parts = pathinfo(__FILE__);	
			//if (strpos(strtolower($_SERVER['SERVER_NAME']),'cmaphk.com')>=0){
			//	$dir = '/home/cmaphk';
			//}else{
				$dir = $path_parts['dirname']; //current directory for debugging.
			//}
			$file_name_only = $path_parts['filename'];
			$file_name = $dir . 'admin/uploads/' . $file_name_only . '_log.pdf';
			$time_now = date('Y-m-d H:i:s', time());
		//	echo "debug_paypal_log_script file name [{$file_name}].<br>";
		
			if (!file_exists($file_name)){
				$paypal_log_fid = fopen($file_name, 'c+');
				fwrite($paypal_log_fid, "<?php \n");
			}else{
				// normal flow, append
				$paypal_log_fid = fopen($file_name,'a+');
			}
			
			if (!$paypal_log_fid){
				die ("fopen error for debug_paypal_log_script.php");
			}
			fwrite($paypal_log_fid, '\n/*');
			fwrite($paypal_log_fid, "// {$time_now} : {$str}\n ");
			fwrite($paypal_log_fid, '*/\n');
			
		//	fwrite($paypal_log_fid, "{$time_now} : SESSION DUMP\n");
		//	$session_str = print_r($_SESSION, true);
		//	fwrite($paypal_log_fid, "{$time_now} : {$session_str}\n");
			fclose($paypal_log_fid);
			$paypal_log_fid = null;
		}
	} // function
}
?>