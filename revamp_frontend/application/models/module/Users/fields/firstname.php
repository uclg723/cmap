<?php

$description['firstname'] = array(
		'label' 		=> 'LBL_FIRST_NAME',
		'user_input' 	=> true, // can be input
		'valid_function' => function($field_value){
			return true; // check if the field value is valid
		},
);

?>