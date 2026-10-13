<?php

$description['uid'] = array(
		'label' 		=> 'LBL_UID',
		'user_input' 	=> true, // can be input
		'valid_function' => function($field_value){
			return true; // check if the field value is valid
		},
//		'relate_list' => 'Users', // related to Users, ask Users to load the RelateList
//		'relate_list_name' => '{$firstname} {$lastname}', // use the syntax {$FIELD_NAME} for display purpose		
);

?>