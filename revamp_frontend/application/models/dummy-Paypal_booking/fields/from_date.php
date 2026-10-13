<?php

$description['from_date'] = array(
		'label' 		=> 'LBL_DATE_IN',
		'user_input' 	=> true, // can be input
		'valid_function' => function($field_value){
			return true; // check if the field value is valid
		},
);

?>