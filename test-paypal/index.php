<form class="paypal" action="payments.php" method="post" id="paypal_form" target="_blank">    
	<input type="hidden" name="cmd" value="_xclick" /> 
    <input type="hidden" name="no_note" value="1" />
    <input type="hidden" name="lc" value="UK" />
    <input type="hidden" name="currency_code" value="HKD" />
    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
    <input type="hidden" name="first_name" value=""  />
    <input type="hidden" name="last_name" value=""  />
    <input type="hidden" name="payer_email" value=""  />
    <input type="hidden" name="item_number" value="<?php echo time(); ?>" / >
    <input type="submit"  value="Submit Payment"/>
</form>