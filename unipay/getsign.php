<?php

include '../include/functions2.php';
include "unipay_config.php";

session_start();
if (!isset($_SESSION['id'])) {
    header('LOCATION:logout.php');
}
if (isset($_SESSION['id'])) {
    $user_login = 1;
    connect();
    $id = sanitize($_SESSION['id']);

}

$payID       = $_GET['id'];
$orderNumber = $_GET['orderNumber'];
$totalFee    = $_GET['totalFee'];

// Manual cal signature
//$var = "order_number=" . $orderNumber . "&partner=" . $partner . "&pay_id=" . $payID . "&total_fee=" . $totalFee;
//$var = $var . $md5key;
//$signature = md5($var);

$parameters = array(
    'order_number' => $orderNumber,
    'partner'      => $partner,
    'pay_id'       => $payID,
    'total_fee'    => $totalFee,
);

$sig = sign($parameters);
echo $sig;
