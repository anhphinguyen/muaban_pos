<?php

// $secret_key = base64_encode(md5("my_name_is_JunoPhraend"));

use \Firebase\JWT\JWT;

$header_arr = apache_request_headers();
global $secret_key, $time_expire;
if (isset($header_arr['Authorization']) && !empty($header_arr['Authorization'])) {
    $author = explode(" ", $header_arr['Authorization']);
    if (count($author) != 2) {
        errorToken("4003", "4003");
    }
    if ($author[0] != "Bearer") {
        errorToken("4003", "4003");
    }
    $author['token'] = $author[1];

    $token = $author['token'];
    $data = JWT::decode($token, $secret_key, array('HS256'));

    $_SESSION['destroy_token']['destroy_count'] = strval((int)$_SESSION['destroy_token']['destroy_count'] + 1);


    if ($data->exp < time()) {
        errorToken("4001", "4001");
    }

    $payload_tmp = array(
        "nbf" => time(),  //cho phép sử dụng token tại thời điểm này
        "exp" => time() + $time_expire, // token hết hạn
        'id_business' => $data->id_business,
        'id_account' => $data->id_account,
        'username' => $data->username,
        'password' => $data->password,
        'email' => $data->email,
        'id_type' =>  $data->id_type,
        'store_code' => $data->store_code,
        'destroy_count' => $data->destroy_count + 1
    );
    $token = JWT::encode($payload_tmp, $secret_key);

    // returnSuccess($_SESSION['destroy_token'],$data->destroy_count);

    $sql = "SELECT * FROM `tbl_account_account` WHERE `account_username` = '{$data->username}' 
            AND `account_password` = '$data->password' AND `id_business` = '{$data->id_business}'";
    // returnSuccess($sql);
    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums == 0) {
        errorToken("4003", "4003");
    }
} else {
    errorToken("4003", "4003");
}
