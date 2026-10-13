<?php
	if (strpos(strtolower($_SERVER['HTTP_HOST']),'localhost')>=0){		
		$url = 'http://' . $_SERVER['HTTP_HOST'] . '';
	}else{
		$url = 'http://' . $_SERVER['HTTP_HOST'] . '';
	}
	if (strpos(strtolower($_SERVER["REQUEST_URI"]),'admin')>0){
		$url .= '/admin/';
	}
//	header('Location: ' . $url);
//	die();
?>
<html>
<head>
<meta http-equiv="refresh" content="2;url=<?php echo $url;?>">
</head>
<body>
	<h3>Incorrect Location</h3>
	<p> Redirect to <a href='<?php echo $url;?>'><?php echo $url;?></a> in a few seconds.</p></body>
</html>