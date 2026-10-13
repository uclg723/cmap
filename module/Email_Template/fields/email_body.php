<?php
	$description['email_body'] = array(
		'label' 		=> 'LBL_EMAIL_BODY',
		'user_input' 	=> true, // can be input
		'valid_function' => function($field_value){
			return true; // check if the field value is valid
		},
	);

?>