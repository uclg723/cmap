<div clas='div-login-successful'>
	<h2>LOGIN Successfully</h2>

	<?php
		$full_url = base_url() . 'index.php?' . $redirect_url;
	?>
	
	Redirect to <a href='<?php echo $full_url;?>'><?php echo $full_url;?> </a>
	<script language='javascript'>
		window.location.href='<?php echo $full_url;?>';
	</script>
	<div>
	</div>


</div> <!-- .div-login-successful