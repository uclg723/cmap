<?php
if(isset($_GET['id'])){
	$id = $_GET['id'];
	$storeFolder = 'uploads/'.$id.'/';
	if(!is_dir($storeFolder)){
		mkdir($storeFolder);
		mkdir($storeFolder."thumb/");		
	}	
	if (!empty($_FILES)) {
		$filename = strtolower($_FILES['file']['name']);
	    $tempFile = $_FILES['file']['tmp_name'];
    	$targetPath = $storeFolder;
	    $targetFile = $targetPath. $filename;
    	move_uploaded_file($tempFile,$targetFile);
		include("resize-class.php");
		$resizeObj = new resize($storeFolder.$filename);
		$resizeObj -> resizeImage(150, 150, 'auto');
		$resizeObj -> saveImage($storeFolder.'thumb/'.$filename, 100);
	}
}
?>