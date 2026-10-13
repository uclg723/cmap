<?php

$description['rid'] = array(
		'label' 		=> 'LBL_RID',
		'user_input' 	=> true, // can be input
		'valid_function' => function($field_value){
			return true; // check if the field value is valid
		},
		'relate_list' => 'Rooms', // related to Rooms, ask Rooms to load the RelateList
		'relate_list_name' => '{$room_name}', // use the syntax {$FIELD_NAME} for display purpose
);

?>