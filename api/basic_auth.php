<?php

$secret_key = base64_encode(md5("my_name_is_JunoPhraend"));

// use \Firebase\JWT\JWT;

// $header_arr = apache_request_headers();
// global $secret_key;
// // returnError($secret_key);
// if (isset($header_arr['Authorization']) && !empty($header_arr['Authorization'])) {
//     $author = explode(" ", $header_arr['Authorization']);
//     $author['token'] = $author[1];

//     $token = $author['token'];
//     // returnError($token);
//     // $token = $header_arr['Authorization'];
//     $data = JWT::decode($token, $secret_key, array('HS256'));
//     if ($data->exp < time()) {
//         $payload_tmp = array(
//             "nbf" => time(),  //cho phép sử dụng token tại thời điểm này
//             "exp" => time() + 60, // token hết hạn
//             'username' => $row['username'],
//            'password' => $row['password'],
//             'email' => $row['email'],
//         );
//         $token = JWT::encode($payload_tmp, $secret_key);
//         $result_arr['success'] = "true";
//         $result_arr['data'] = array();
//         array_push($result_arr['data'], $result_item = ['token' => $token]);
//         reJson($result_arr);
//     }

//     $sql = "SELECT * FROM `tbl_account_account` WHERE `account_username` = '{$data->username}' 
//             AND `account_password` = '$data->password'";
//     $result = db_qr($sql);
//     $nums = db_qr($result);
//     if($nums == 0){
//         echo json_encode(array(
//             'success' => 'false',
//             'error_code' => 'T0123N',
//             'message' => 'Lỗi token'
//         ));
//     }
// }else{
//     echo json_encode(array(
//         'success' => 'false',
//         'error_code' => 'T0123N',
//         'message' => 'Lỗi token'
//     ));
// }
