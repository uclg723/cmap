<?php
	$description['cc_address'] = array(
		'label' 		=> 'LBL_CC_ADDRESS',
		'user_input' 	=> true, // can be input
		'valid_function' => function($field_value){
			return true; // check if the field value is valid
		},
	);

?>