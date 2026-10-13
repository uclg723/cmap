<?php
// SMTP config
// for use in payment-success.php
global $phpmailer_config;
$phpmailer_config = array(
	'SMTPDebug' 	=> 0, // 0: not using debug	
	'Debugoutput' 	=> 'html', // format of debug output
	'SMTPSecure'  	=> 'tls',
	'Host'		  	=> 'smtp.gmail.com',
	'Port'		  	=> 587,
	'SMTPAuth'		=> true,
	'Username'		=> 'hkcmap@gmail.com', //"hkcmap@gmail.com",
	'Password' 		=> 'cmap8899', //"",qq654321
);


?>