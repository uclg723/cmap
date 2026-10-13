<?php
if (!defined('SET_CMAPHK_INCLUDE_PATH')) {
	$root_path = realpath(dirname(__FILE__) . '/../../' );
	$include_path = get_include_path();
	if (strpos($include_path, $root_path)==false){
		$all_include_path = $include_path . ';' . $root_path;
		set_include_path($all_include_path);
	}
	define('SET_CMAPHK_INCLUDE_PATH', $root_path);
}
require('module/SMTPMailer/SMTPMailer.php');

$smtp = new SMTPMailer();
$smtp->setEmailTemplateById(1);
$context = array(
	'{$email}'=>'fungcl@yahoo.com',
	'{$first_name}' => 'Ben',
	'{$last_name}'  => 'Fung',
);
$result = $smtp->send($context);

echo "Email Result:" . (($result)?'SEND':'NOT SEND') . "<br>";

?>