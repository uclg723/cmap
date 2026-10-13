<?php

	$storeFolder = '../../images/';
	if(!is_dir($storeFolder)){
		mkdir($storeFolder);
	}	
	if (!empty($_FILES)) {
		//$filename = strtolower($_FILES['file']['name']);
	    $filename = $_POST['name'];
		$tempFile = $_FILES['file']['tmp_name'];
    	$targetPath = $storeFolder;
	    $targetFile = $targetPath. $filename;
    	move_uploaded_file($tempFile,$targetFile);
		include("../resize-class.php");
		$resizeObj = new resize($storeFolder.$filename);
		$resizeObj -> resizeImage($_POST['w'], $_POST['h'], 'crop');
		$resizeObj -> saveImage($storeFolder.$filename, 100);
	}

?>