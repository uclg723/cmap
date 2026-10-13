<?php
global $option_list;

$module = 'Booking';
$lang   = 'en';

$option_list[$module][$lang]['status']=array(
	0=>'Disabled',
	1=>'Inactive',
	2=>'Active',
);

$option_list[$module][$lang]['btype']=array(
	1=>'phone',
	2=>'online',
	3=>'VIP',
);


?>