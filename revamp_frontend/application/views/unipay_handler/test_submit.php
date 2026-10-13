<h3>Payment Start</h3>
We will start the payment process in a few seconds.
<form id='form_unipay' method='post' action='http://www.unipaygo.com/index.php/payapi/'>
<?php 
	foreach ($params as $name=>$value){?>
<label><?php echo $name; ?></label>
<input type='text' name='<?php echo $name; ?>' value='<?php echo $value; ?>'><br>
<?php
	} // foreach?>
<input type='submit'>
</form>

<script language='javascript'>
  document.forms[0].submit();
</script>