<?php

$description['status'] = array(
		'label' 		=> 'LBL_STATUS',
		'user_input' 	=> true, // can be input
		'valid_function' => function($field_value){
			return true; // check if the field value is valid
		},
		'option_list'   => 'status',
);

?>