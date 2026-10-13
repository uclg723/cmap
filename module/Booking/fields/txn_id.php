<?php

$description['txn_id'] = array(
		'label' 		=> 'LBL_TXN_ID',
		'user_input' 	=> true, // can be input
		'valid_function' => function($field_value){
			return true; // check if the field value is valid
		},
);

?>