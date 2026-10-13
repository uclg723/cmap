<?php
global $option_list;

$module = 'Paypal_booking';
$lang   = 'en';

$option_list[$module][$lang]['status']=array(
//	0=>'Disabled',
	1=>'Processed',
	2=>'Pending',
);
/*
$option_list[$module][$lang]['btype']=array(
	1=>'phone',
	2=>'online',
	3=>'VIP',
);
*/


?>