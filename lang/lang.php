<?php
if (isset($_GET['lang'])){
	$curLang = $_GET[lang];
	if ($curLang != $_COOKIE["lang"] ){
		setcookie("lang", $curLang, time()+60*60*24*30);
		header( 'refresh: 0; url=' );	
	}
}

$curLang = $_COOKIE["lang"];
if (!isset($_COOKIE["lang"])){
	setcookie("lang", "cn", time()+60*60*24*30);
	$curLang = "cn";
}
include_once "lang/" . $curLang . ".php";


?>