<?php
require('admin_config.php');

//include('module/Email_Template/Email_Template.php');
//include('module/EditController/EditController.php');
//include_once('module/EditView/EditView.php');

//include('include/functions.php');

include('../module/Email_Template/Email_Template.php');
include('../module/EditController/EditController.php');
include_once('../module/EditView/EditView.php');

include('../include/functions.php');


session_start();
if(!isset($_SESSION['admin'])){
	echo "NO Admin logged ";
}else{
	echo "Admin logged ";
}
echo "<br>";

echo 'Function connect:';
if (function_exists('connect')){
	echo 'defined.';
}else{
	echo 'not defined.';
}
echo "<br>";

echo 'Class Email_Template:';
if (class_exists('Email_Template')){
	echo 'defined.';
}else{
	echo 'not defined.';
}
echo "<br>";

echo "Include Path:[" . get_include_path() . "]\n<br>";

echo "SET_CMAPHK_INCLUDE_PATH:[" . defined

?>