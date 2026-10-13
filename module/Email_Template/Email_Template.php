<?php
if (!class_exists('Email_Template')){
	//include('module/MysqlObject/MysqlObject.php');
	
	include(__DIR__ . '/../MysqlObject/MysqlObject.php');
	
	class Email_Template extends MysqlObject{
		function __construct(){
			parent::__construct();
		}
	} // class

}