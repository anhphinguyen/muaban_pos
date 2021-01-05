<?php
use \Firebase\JWT\JWT;

$header_arr = apache_request_headers();
// returnError($token);
// returnError($header_arr['Authorization']);
$secret_key = "sercurity";
if (isset($header_arr['Authorization']) && !empty($header_arr['Authorization'])) {
    $token = $header_arr['Authorization'];
    $data = JWT::decode($token,$secret_key,array('ES256'));
    
    returnSuccess($token);
}else{
    returnError("loi");
}