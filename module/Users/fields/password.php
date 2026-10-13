<?php

$description['password'] = array(
		'label' 		=> 'LBL_PASSWORD',
		'user_input' 	=> true, // can be input
		'valid_function' => function($field_value){
			return true; // check if the field value is valid
		},
);

?>