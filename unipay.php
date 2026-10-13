<?php

include 'include/functions.php';
include "payment-functions.php";
include "unipay/unipay_config.php";

session_start();
if (!isset($_SESSION['id'])) {
    header('LOCATION:logout.php');
}
if (isset($_SESSION['id'])) {
    $user_login = 1;
    connect();
    $id = sanitize($_SESSION['id']);

}

if (!isset($_SESSION['tid'])) {
    header('LOCATION:booking.php');
} else {
    $tid = $_SESSION['tid'];
}

connect();
$sql   = "SELECT * FROM paypal_booking WHERE ID=" . $tid;
$query = mysql_query($sql);
if ($query) {
    $nor = mysql_num_rows($query);
    if ($nor == 1) {
        $booking = mysql_fetch_assoc($query);
    } else {
        header('LOCATION:logout.php');
    }
} else {
    echo die(mysql_error());
}

$orderNumber = $tid;
$totalFee    = $booking['amt'];

?>

<!DOCTYPE html>
<html>
<head>

	<title>Pay by Unipay</title>
	<meta charset="utf-8" />
    <link href="admin/css/bootstrap.min.css" rel="stylesheet">

<script>
function getsign(str) {
		document.getElementById("submitBtn").disabled = true;

	    //document.getElementById("sign").value = "unipay/getsign.php?id=" + str + "<?php echo "&orderNumber=$orderNumber&totalFee=$totalFee"; ?>"  ;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("info").value = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "unipay/getsign.php?id=" + str + "<?php echo "&orderNumber=$orderNumber&totalFee=$totalFee"; ?>", true);
        xmlhttp.send();

		document.getElementById("submitBtn").disabled = false;

}
</script>

</head>
<body>
<div class="container text-center">
	<br><img src="images/unionpay.png"><p>&nbsp</p>
</div>

<div class="container well">

	<div class="row">
        <div class="col-sm-8 col-md-offset-3"><h2>选择付款银行</h2></div>
	</div>
<!--<form action="https://www.unipaygo.com/index.php/payapi/" method="post" accept-charset="utf-8">-->
<form action="https://www.unipaygo.com/index.php/payin/proxy" method="post" accept-charset="utf-8">

    <div class="row">

        <div class="col-md-6 col-md-offset-3">
            <div class="btn-group" data-toggle="buttons">
                <label  >
                    <input type="radio" id="pay_id" name="pay_id" value="gonghang" onclick="getsign(this.value)"/> 中国工商银行 </label>
                <label  >
                    <input type="radio" id="pay_id" name="pay_id" value="zhonghang" onclick="getsign(this.value)"/> 中国银行
              </label>
                <label  >
                  <input type="radio" id="pay_id" name="pay_id" value="jiaohang" onclick="getsign(this.value)"/> 中国交通银行
                </label>
                <label  >
                  <input type="radio" id="pay_id" name="pay_id" value="zhaohang" onclick="getsign(this.value)"/> 招商银行
                </label>
          </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="btn-group" data-toggle="buttons">
                <label  >
                    <input type="radio" id="pay_id" name="pay_id" value="guangda" onclick="getsign(this.value)"/> 中国光大银行  </label>
                <label  >
                    <input type="radio" id="pay_id" name="pay_id" value="nonghang" onclick="getsign(this.value)"/> 中国农业银行
              </label>
                <label  >
                  <input type="radio" id="pay_id" name="pay_id" value="zhongxin" onclick="getsign(this.value)"/> 中信银行
                </label>
                <label  >
                  <input type="radio" id="pay_id" name="pay_id" value="jianhang" onclick="getsign(this.value)"/> 建设银行
                </label>
          </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="btn-group" data-toggle="buttons">
                <label  >
                    <input type="radio" id="pay_id" name="pay_id" value="shenfa" onclick="getsign(this.value)"/> 深圳发展银行  </label>
                <label  >
                    <input type="radio" id="pay_id" name="pay_id" value="minsheng" onclick="getsign(this.value)"/> 民生银行
              </label>
                <label  >
                  <input type="radio" id="pay_id" name="pay_id" value="xingye" onclick="getsign(this.value)"/> 兴业银行
                </label>
                <label  >
                  <input type="radio" id="pay_id" name="pay_id" value="pufa" onclick="getsign(this.value)"/> 上海浦东发展银行
                </label>
          </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="btn-group" data-toggle="buttons">
                <label  >
                    <input type="radio" id="pay_id" name="pay_id" value="nonghangb2b" onclick="getsign(this.value)"/> 农行企业网银 </label>
                <label  >
                    <input type="radio" id="pay_id" name="pay_id" value="jianhangb2b" onclick="getsign(this.value)"/> 建行企业网银
              </label>
                <label  >
                  <input type="radio" id="pay_id" name="pay_id" value="gonghangb2b" onclick="getsign(this.value)"/> 工行企业网银
                </label>
                <label  >
                  <input type="radio" id="pay_id" name="pay_id" value="zhonghangb2b" onclick="getsign(this.value)"/> 招行企业网银
                </label>
          </div>
        </div>
    </div>

    <div class="row">
    <p>&nbsp</p>
        <div class="col-md-6 col-md-offset-3">
 <!--<input type="hidden" name="partner" value="<?php echo $partner; ?>">
 <input type="hidden" name="order_number" value="<?php echo $orderNumber; ?>">-->

 共付: <?php echo $totalFee; ?> RMB

 <!--<input type="hidden" name="total_fee" value="<?php echo $totalFee; ?>">
 <input type="hidden" name="return_url" value="<?php echo $returnURL; ?>">
 <input type="hidden" name="notify_url" value="<?php echo $notifyURL; ?>">
 <input type="hidden" name="sign" id="sign" value=''>-->
 <input type="hidden" name="partner" id="partner" value='MAP'>
 <input type="hidden" name="info" id="info" value=''>
<input type="submit" id="submitBtn" class="btn btn-success btn-lg" disabled>

	</div>
	</div>
</form>

</div>
</body>
</html>
