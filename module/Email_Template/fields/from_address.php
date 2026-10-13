<?php
	$description['from_address'] = array(
		'label' 		=> 'LBL_FROM_ADDRESS',
		'user_input' 	=> true, // true: can be input, false: cannot be input
		'valid_function' => function($field_value){
			return true; // check if the field value is valid
		},
	);

?>