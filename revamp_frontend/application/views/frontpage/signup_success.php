<div class='div-sign-up'>
  <h2><?php echo $this->lang->line("page_signup_success"); ?></h2>
  <style>
	.field-label{
		display: inline-block;
		width: 150px;
		padding: 5px;
	}
  </style>
  <div class='div-form'>	
		<?php
			$field_list = array('firstname', 'lastname', 'email', 'country', 'phone', 'password', 'confirm_password');
		
			foreach ($field_list as $cur_field){
		?>		
		<div class='fields'>
			<span class='field-label'>
				<label class='field-label' for='<?php echo $cur_field;?>'>
					<?php echo $this->lang->line("page_signup_" . $cur_field); ?>
				</label>				
			</span>
			<span class='field-input'>
				<?php
					if ($cur_field=='country'){
					  // country code
				?>
					<select name='country' id='country' disabled='disabled'>
				<?php include('country_code_option.php'); ?>
					</select>
				<?php
					if (!empty($country)){
				?>
					<script language='javascript'>
						$('#country').val('<?php echo $country; ?>');
					</script>
				<?php
					}
				?>
					
				<?php
					}elseif ($cur_field=='password' || $cur_field=='confirm_password'){
					
				?>
				<input type='password' disabled='disabled' name='<?php echo $cur_field;?>' value='<?php echo (isset($$cur_field))? $$cur_field:""; ?>' />
				<?php
					}else{ // all other fields
				?>
				<span><?php echo (isset($$cur_field))? $$cur_field:""; ?></span>
				<?php
					} // switch of input type.
				?>
				<span class='input_warn' style='color:red;display:inline-block;'>
					<?php echo form_error($cur_field); ?>
				</span>
			</span>

		</div>
		<?php
			}
		?>
		<div class='fields'>
			<span class='field-label'>
			</span>
		</div>
				
	</form>
  </div> <!-- .div-form -->
</div><!-- .div-sign-up -->