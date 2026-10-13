<?php

// Deprecated, should use the module/Email_Template/Email_Template.php instead

global $mail_template_config;
$mail_template_config = array();
$mail_template_config['payment-success'] = array(
	'from_name' => 'China Max Asia Pacific Limited',
	'from_email' => 'hkcmap@gmail.com',
	'cc_email_list' => 'fungcl@yahoo.com',
	'bcc_email_list' => '', //'hkcmap@gmail.com',
	'email_subject' => 'China Max Asia Pacific Limited Booking Confirmation',
	'message_template' =>
			'Dear {$fname}, <br/> <br/>' .
			"  Your booking was made successfully, here is the details of your booking. <br/> <br/>" .
			'  Unit Name: {$room[\'room_name\']} <br>' .
			'  Day in  - {$din} <br>'.
			'  Day out  - {$dout}<br>' .
			'  Total night stay: {$nod} <br>' . 
			'  Amt: {$item_amount} {$booking[\'currency\']}<br><br>' .
//			"<br>謝�����使���������������,���������������次������������ <br>"
			" Thank you for staying with us, we look forward to services again.<br>" .
			"<br><br>" .
			" The Metropolis Motel / China Max Asia Pacific Limited<br>" .
			" No.A on 2nd Floor, Dolford Mansion, Nos. 1 & 3 Chatham Court, Tsim Sha Tsui Kln<br>" .
			" Tel: 8170-3484 Fax: 30206793 www.cmaphk.com E-mail address: cs@cmaphk.com ", 
); 