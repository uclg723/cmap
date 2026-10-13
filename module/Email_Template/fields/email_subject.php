<?php
	$description['email_subject'] = array(
		'label' 		=> 'LBL_EMAIL_SUBJECT',
		'user_input' 	=> true, // true: can be input, false: cannot be input
		'valid_function' => function($field_value){
			return true; // check if the field value is valid
		},
	);

?>