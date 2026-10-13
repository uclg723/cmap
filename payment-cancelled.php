<?php
/**
 * Ben Debugging
 */
include('debug_paypal_log_script.php');
$post_string = print_r($_POST,true); // capture in string
$get_string  = print_r($_GET,true);
debug_paypal_log_script('Calling payment-cancelled.php with post [' . $post_string . '] and GET [' . $get_string . ']\n'); // for writing to the file.

//echo "GLOBAL: " . print_r($_GLOBAL) . "<br>";
//echo "REQUEST:" . print_r($_REQUEST) . "<br>";
//echo "ENV:" . print_r($_ENV) . "<br>";
//echo "GET:" . print_r($_GET) . "<br>";
//echo "POST:" . print_r($_POST)  . "<br>";

?>
<?php 
  $block['body-content'] = '<h3>Payment Cancelled</h3><br>' .
    'Your payment was cancelled in paypal. Please contact cs staff at (852) 8170 3484 for assistance.'; 
  include ('page_block/layout.php');
?>