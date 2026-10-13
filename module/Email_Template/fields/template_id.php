<?php
	$description['template_id'] = array(
		'label' 		=> 'LBL_TEMPLATE_ID',
		'user_input' 	=> false, // cannot be input, primary key will make it hidden, so, ignored.
		'valid_function' => function($field_value){
			return true; // check if the field value is valid
		},
	);

?>