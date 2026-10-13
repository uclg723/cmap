<div class='div-login'>
	<style>
		.div-error{
			color: red;
		}
	</style>
	<div class='div-login-header'>
	  <h3><?php echo $this->lang->line("page_login"); ?></h3>
	  <div class='div-error'>
	  <?php 
		echo validation_errors(); 
		
	  ?>
	  <br>
	  <?php
		if (isset($login_error)){
			echo $login_error;
		}
	  ?>
	  
	  </div>
	  
	</div>
	<form method='post' action='index.php?/frontpage/login'>
		<input type='hidden' name='redirect_url' value='<?php echo $redirect_url;?>' />
		<div>
			<label class='login-form-label' for='email'><?php echo $this->lang->line("page_email"); ?></label>
			<input type='text' id='email' name='email' value='<?php if (isset($email)) echo $email;?>' ></input>
		</div>
		<div>
			<label class='login-form-label' for='email'><?php echo $this->lang->line("page_password"); ?></label>
			<input type='password' id='password' name='password' value='<?php  if (isset($password)) echo $password;?>'></input>
		</div>
		<div>
			<input type='submit' class='btn btn-default' value='<?php echo $this->lang->line("page_login_submit"); ?>' />
			&nbsp;
			<a href='index.php?/frontpage/forget_password'>
				<?php echo $this->lang->line("page_forget_password"); ?>
			</a>
		</div>
	</form>
</div> <!-- .div-login -->