<?php
	$description['template_name'] = array(
		'label' 		=> 'LBL_TEMPLATE_NAME',
		'user_input' 	=> true, // true: can be input, false: cannot be input
		'valid_function' => function($field_value){
			return true; // check if the field value is valid
		},
	);

?>