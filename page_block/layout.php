<?php
/**
 * Generic Layout of a web page for the CMAPHK.com project
 */
	// simple setting
	$block['page-title'] = 'Hotel - Service Apartment - China Max Asia Pacific Company Limited';
	session_start();
	$success_view=0;
	if(isset($_SESSION['id']))
	{
		$success_view = 1;
	}
	// $block['page-body'] is the place to put the content.
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" />
<title> <?php echo $block['page-title']; ?> </title>
<link type="text/css" href="include/style.css" rel="stylesheet"/>
<link type="text/css" href="page_block/layout.css" rel="stylesheet"/>
<script type='text/javascript' src='include/jquery.min.js'></script>
<script type='text/javascript' src='include/jquery.cycle.all.min.js'></script>

<script type="text/javascript">
//top.document.title='Hotel - Service Apartment - China Max Asia Pacific Company Limited ';
$(document).ready(function() {
	$('.slideshow').cycle({
	fx: 'fade',
	pause: 1
	});
});
</script>
</head>
<body bgcolor="#F0F0F0">
<div class='header-container page-content'>
<?php include ("header.php"); ?>
</div>
<div class='menu-container page-content'>
<?php
	include ("menu.php");
?>
</div>
<div class='body-container page-content'>
	&nbsp;
<?php 
	echo $block['body-content'];
?>
</div>
<div class='footer-container page-content'>
<div class='footer-ruler page-content' style='border-bottom:1px solid #bcbcbc'>&nbsp;</div>
<?php
  include ("footer.php"); 
?>
</div>
</body>
</html>
