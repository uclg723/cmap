<?php
error_reporting(E_ALL & ~E_NOTICE);
$progressSuccess = false;
include '../include/functions.php';
include "../payment-functions.php";
include "unipay_config.php";
include '../include/functions2.php';
include "unipay_config.php";
session_start();

/* return後 先記錄下LOG備查*/
connect();
$callback_time = date("Y-m-d H:i:s");
$data_post     = json_encode($_POST, true);
$data_get      = json_encode($_GET, true);
$data_session  = json_encode($_SESSION, true);
$sql_log       = "INSERT INTO callback_log
			(callback_time, data_post, data_get, data_session)
			VALUES
			('" . $callback_time . "', '" . $data_post . "', '" . $data_get . "', '" . $data_session . "');";
$query_log = mysql_query($sql_log);

$private = '-----BEGIN RSA PRIVATE KEY-----MIIEpAIBAAKCAQEA5htSUllmf9pOr4PHgeuylY4ICGldF0Xyyoh0GuvmnL7yXUhHdr4e5RVTLX4cXhZJ0Fd87IXjPk8Nw6K1cS91GvZgZvNGG1q2X6xSs4HkWMrGWHiljL2YMNs+5Yii5MUNxnedxYD+amFZPhDnJG9r7yuSqkW/Qvsmt6b6Y26ZieP88h+sx28kBMqi4L1SElCglLZ3JjzNdjMKSb2a6Mzly7QlzfrKpgLYj+uRX8i747NrcPcRSJeYehs7Aghx4xupMZSkgnwlBC9N29Wbw3E1yJQW1VtxmRk/HdBIettzSq8xyyWQwDuAsrpwQffVdNt/Rh41ssuQp/R94l+1eKsbKQIDAQABAoIBADsvwd/uoN+37k9U7Qcs8fVn4AcR2uD2m/meWYbnMJ0WvE5kHgWb52RzNL7Z1kAZKYlOR5Kq6YWIXfj5LARd+Zgi0S8J2fmFPq2jLG6BtZNlLNlqxuJJjhDbA/WUTJndWaq377vybv475fGB9YI4qqGNKp9ja0u2QAIzD+ptc060+ctw91T6v3h2I8iYwgaOL+7BU8MggIQVsSQXOE3cE2MYpNHmbljPdPGO/cCuogPvGW5Y8yKED6ypOBn1i4nVAsIWS9vUfVTU6g0mmVV9jQJ86f3KpJ4wee9Q7PvsWFJMLxtv4iXmDVBspq7gFLaf7/6PcaNQwxLazhan7xVmzBECgYEA/Z9BzpwzHapVqhvPnQHjOejxO6V1gKwUgWrXvEc+BXBnox/NwAJKcpdP+vW1ydTBdTNiNySfYVq9GPF15HXnlM3w69r3Kxon2eiZixTcTO07wUcW1YdktJvdj8nXBXtsaL0tsKjDfah2BgjS6xIEl4pPwsGkNWUbu1ewLtG2wa0CgYEA6EOffCAOUlQdZvtwqrPBc4jtsmBpc89ix5gy73yqBrcdzEBEVYFye6wo2SoOh+VJYqp2n56dBF+MOlhpCj1AR9riNwE0vj3DncXgriKMTnBuybEgcbdjp84/YPId+/YZtvkeA9/iBq16U1wEIk64+m+X9OHHYvtR36mezz0mxu0CgYEA+5A06Oe8N+eVctaFFtohdXZdmOkkZM3KxReiBymvFhwJYB8SqSpch88YUPfZKenzPfoKqGGWCS3EtPIEGujsHIgtOPSHB+S4tIbnjbHSVidVhv+pYYEbXZ3gpVfyBIkI3dcPc9PhnEk1UAT+mJXVoi5ZsPb7ls7ENA3lPntbSD0CgYAu07tqzhe1F4paAeWkNNuSzI+HwBJiq/QxY4kZaZvqAK88R9S/HHsVXg9aQTVuxpZ2EwIZimQDxrPB66M50kGMex/SkSauGEvBGm0DDqoVAmwDs/djleK8qUNz6zyKWQK0NiOMCm5KhF0+QzX+KLr4mtkbIQ4O8bZ/DnabN6wCvQKBgQCyFcCmFkeIOJ6i/MwXogbaRkkwsgs+WA8LnEUqQ1XEbvG/rBwL5BokT6CBjPsELEvWL85gCryut2vh+cfKz3IZL2z1Sq6xDr5wXaw1DF0KIwlqPiYShhCnSwGHr7QCQ7RHJSXYEIkhwrVfdNXZA+2JsOhikcHs1/05xLhYxhbM2Q==-----END RSA PRIVATE KEY-----';

$rsa = new Crypt_RSA();

$rsa->loadKey($private);
$key_param_arr = $rsa->decrypt(base64_decode($_POST['info']));

$_REQUEST = $key_param_arr;

/*找出後面要用的 ID 和 TID*/
$SQL   = "SELECT * FROM `paypal_booking` WHERE ID = '" . $_REQUEST['out_trade_no'] . "'";
$query = mysql_query($SQL);
if ($query) {

    $nor = mysql_num_rows($query);
    if ($nor > 0) {
        $row = mysql_fetch_assoc($query);

        $id  = $row['UID'];
        $tid = $row['ID'];

    } else {
        die("Error:trade_no :" . $_REQUEST['out_trade_no'] . " not fond!");
    }

}

// call back parameters
$parameters = array(
    'partner'      => $_REQUEST['partner'],
    'out_trade_no' => $_REQUEST['out_trade_no'],
    'pay_no'       => $_REQUEST['pay_no'],
    'amount'       => $_REQUEST['amount'],
    'pay_result'   => $_REQUEST['pay_result'],
    'sett_date'    => $_REQUEST['sett_date'],
    'base64_memo'  => $_REQUEST['base64_memo'],
);
//echo print_r($parameters);

$from_sign = $_REQUEST['sign'];
//echo "<br>" . $from_sign;

$sign = sign($parameters);

//echo "<br>" . $sign;
//var_dump($parameters);
$pay_result_array = array("1", 1, "Success", "success", "SUCCESS", "Y", "y", "OK", "ok", "Ok");

if ($from_sign == $sign) {
    if (in_array($_REQUEST['pay_result'], $pay_result_array)) {
        // verified
        connect();

        // Get user info
        $SQL   = "SELECT * FROM users WHERE id = '$id'";
        $query = mysql_query($SQL);
        if ($query) {

            $nor = mysql_num_rows($query);
            if ($nor > 0) {
                $row = mysql_fetch_assoc($query);

                $fname    = $row['firstname'];
                $lname    = $row['lastname'];
                $cc       = $row['country'];
                $phone    = $row['phone'];
                $email    = $row['email'];
                $passport = $row['passport'];

            }

        } else {
            die("Error:user_id not fond!");
        }

        $SQL = "SELECT * FROM `paypal_booking` WHERE `ID` ='" . $tid . "' AND `status` = '2'";
        //$SQL = "SELECT * FROM paypal_booking ORDER BY ID DESC";

        $query = mysql_query($SQL);
        if ($query) {

            $nor = mysql_num_rows($query);
            if ($nor > 0) {
                $booking = mysql_fetch_assoc($query);

                $sql_check   = "SELECT * FROM `booking` WHERE `txn_id` = '" . $booking['ID'] . "' AND status = '1' ";
                $query_check = mysql_query($sql_check);
                if (mysql_num_rows($query_check) == 0) {

                    $datediff = strtotime($booking['to_date']) - strtotime($booking['from_date']);
                    $nod      = floor($datediff / (60 * 60 * 24));
                    $add_info = 'Unipay Transaction ID: ' . $_REQUEST['pay_no'];
                    $sql      = "INSERT INTO `booking` (`uid`,`txn_id`,`date_in`,`date_out`,`add_info`,`days`,`rid`,`status`,`btype`) " .
                        " VALUES('$id','$tid','" . $booking['from_date'] . "','" . $booking['to_date'] . "','{$add_info}','$nod','" . $booking['room'] . "','2','2')";
                    //echo $sql;
                    // temporarily skip the booking log
                    $query = mysql_query($sql);
                    if (!$query) {
                        echo mysql_error();
                    }

                    $sql   = "SELECT * FROM `rooms` WHERE ID='" . $booking['room'] . "'";
                    $query = mysql_query($sql);
                    if ($query) {
                        $nor = mysql_num_rows($query);
                        if ($nor == 1) {
                            $room = mysql_fetch_assoc($query);
                        } else {
                            header('LOCATION:logout.php');
                        }
                    } else {
                        echo die(mysql_query());
                    }

                    $din    = date("d-m-Y", strtotime($booking['from_date']));
                    $dout   = date("d-m-Y", strtotime($booking['to_date']));
                    $txn_id = $tid;

                    require '../module/SMTPMailer/SMTPMailer.php';

                    $smtp = new SMTPMailer();
                    $smtp->setEmailTemplateById(1);
                    $context = array(
                        '{$email}'          => $email,
                        '{$last_name}'      => $lname,
                        '{$first_name}'     => $fname,
                        '{$pay_type}'       => "Unipay ",
                        '{$room_name}'      => $room['room_name'],
                        '{$date_in}'        => $din, // date in
                        '{$date_out}'       => $dout, // date out
                        '{$nod}'            => $nod,
                        '{$item_amount}'    => $booking['amt'],
                        '{$currency}'       => $booking['currency'],
                        '{$transaction_id}' => $txn_id, // transaction id

                    );
                    //$result = $smtp->send($context);
                    if (!$smtp->send($context)) {
                        echo "郵件寄送失敗";
                    } else {
                        echo "成功";
                    }

                }

                $sql = "UPDATE `paypal_booking` SET status='1' WHERE id='$tid'";
                //echo $sql;
                $query = mysql_query($sql);

            }
        }

        unset($_SESSION['tid']);
        $_SESSION['booking'] = "Completed";
// temporarily disable the redirection.
        header("Location: ../booking.php?id=" . $booking['room']);
        //echo "Room no:" . $booking['room'];

        $progressSuccess = true;
        //die("");

    } else {
        // payment failed return by unipay
        $progressSuccess     = false;
        $_SESSION['booking'] = "Could not complete payment!";
        header("Location: ../booking.php?id=" . $booking['room']);

    }

} else {
    // fail
    $progressSuccess     = false;
    $_SESSION['booking'] = "No payment data!";
    header("Location: ../booking.php?id=" . $booking['room']);

}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Unipay payment status</title>
	<meta name="generator" content="" />
</head>
<body>

<?php

if (!$progressSuccess) {
    echo "Payment Failed";
}

if ($progressSuccess) {
    echo "Payment Success";
}

?>
</body>
</html>
