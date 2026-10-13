<?php
error_reporting(E_ALL & ~E_NOTICE);
$progressSuccess = false;
	include('../include/functions.php');
	include("../payment-functions.php");
	include("unipay_config.php");
	

		
		require('../module/SMTPMailer/SMTPMailer.php');

		$smtp = new SMTPMailer();
		$smtp->setEmailTemplateById(1);
		$context = array(
			'{$email}'		=> "roger791024@gmail.com",
			'{$last_name}'	=> "123",
			'{$first_name}'	=> "456",
			'{$pay_type}'	=> "Unipay ",
			'{$room_name}'	=> "room_name",
			'{$date_in}' 	=> $din,	 // date in
			'{$date_out}'	=> $dout,	 // date out
			'{$nod}'		=> $nod,
			'{$item_amount}'	=> $booking['amt'],
			'{$currency}'		=> $booking['currency'],
			'{$transaction_id}' => $txn_id, // transaction id

		);
		if(!$smtp->send($context)){
			var_dump(json_decode($smtp));
		}else{
			echo "成功";
		}
?>