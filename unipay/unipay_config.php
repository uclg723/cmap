<?php
set_include_path("phpseclib");
include 'phpseclib/Crypt/RSA.php';

$md5key    = "MAP001";
$partner   = "MAP";
$returnURL = "http://www.cmaphk.com/unipay/return.php";
$notifyURL = "http://www.cmaphk.com/unipay/return.php";

function sign2($params, $md5_key)
{
    $ignore_params = array('return_url', 'notify_url');
    ksort($params);
    $sign_str = "";
    foreach ($params as $key => $val) {
        if (in_array($key, $ignore_params)) {
            continue;
        }
        $sign_str = $sign_str . sprintf("%s=%s&", $key, $val);
    }
    $sign_str = trim($sign_str, "&") . $md5_key;
    $r        = md5($sign_str);
    return $r;
}

function sign($params = false)
{
    $public = '-----BEGIN PUBLIC KEY-----MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtzaewGeFrQXHhKkvfH6MePZsVHf7dwR1TljvM5GqO7gBI8bgZldLrfaQGQVZjTFprqkFVgP2IqhEQukLJz/5QdUH9R+fUfjEN+fLLx+zsTN+2+I+EpOxNFiHevE5t8P5rnEtICRTlgS7yfTqgpO/SOlT6Y0zCHO75DSuT5mFJ2K4jd2iTHbKs5RClkbzeaQ6fxGTlQIPJAPfHUmp+FnC7hY4zdYNNTLzVTMSbIwmw7Ude8IyCSrvgQV/R8qnamT1NgkykKjoYLlsb3iIzQ3qX82VFqz+Ow8lx5Jl0pK8A9Lcm4ITmvYhKe6wkddBUlxIYIm77TdJ+Z+GVW0Q8hOohwIDAQAB-----END PUBLIC KEY-----';

    $payin_arr = array(
        "base64_memo"  => "test",
        "notify_url"   => "http://www.cmaphk.com/unipay/return.php",
        "order_number" => $params['order_number'],
        "pay_id"       => $params['pay_id'],
        "card_type"    => "01",
        "return_url"   => "http://www.cmaphk.com/unipay/return.php",
        "total_fee"    => $params['total_fee'],
        "version"      => "5.0",
    );

    //client payin information
    $info = http_build_query($payin_arr);

    $rsa = new Crypt_RSA();

    $rsa->loadKey($public);
    $ciphertext = $rsa->encrypt($info);

    //$POST_data['partner'] = "MAP";
    $POST_data = base64_encode($ciphertext);

    return $POST_data;
}
