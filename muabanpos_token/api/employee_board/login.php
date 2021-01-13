<?php

use \Firebase\JWT\JWT;

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
//             'nbf' => time(),
//             'exp' => time() + 30,
//             'id' => $data->id,
//             'id_model' => $data->id_model,
//             'business_model' => $data->business_model,
//             'id_business' => $data->id_business,
//             'store_name' => $data->store_name,
//             'store_prefix' => $data->store_prefix,
//             'store_phone' => $data->store_phone,
//             'store_address' => $data->store_address,
//             'id_type' => $data->id_type,
//             'username' => $data->username,
//             'fullname' => $data->fullname,
//             'email' => $data->email,
//             'account_status' => $data->account_status,
//             'type_account' => $data->type_account,
//             'type_description' => $data->type_description,
//             'role_permission' => $data->role_permission,
//         );
//         $token = JWT::encode($payload_tmp, $secret_key);
//         // returnSuccess($token);
//         $result_arr['success'] = "true";
//         $result_arr['data'] = array();

//         array_push($result_arr['data'], $result_item = ['token' => $token]);

//         reJson($result_arr);
//     }
//     $user_arr['success'] = "true";
//     $user_arr['data'] = array();
//     $user_item = array(
//         'id' => $data->id,
//         'id_model' => $data->id_model,
//         'business_model' => $data->business_model,
//         'id_business' => $data->id_business,
//         'store_name' => $data->store_name,
//         'store_prefix' => $data->store_prefix,
//         'store_phone' => $data->store_phone,
//         'store_address' => $data->store_address,
//         'id_type' => $data->id_type,
//         'username' => $data->username,
//         'fullname' => $data->fullname,
//         'email' => $data->email,
//         'account_status' => $data->account_status,
//         'type_account' => $data->type_account,
//         'type_description' => $data->type_description,
//         'role_permission' => $data->role_permission,
//     );
//     array_push($user_arr['data'], $user_item);
//     reJson($user_arr);
// }

$error = array();
if (isset($_REQUEST['store_code'])) {
    if ($_REQUEST['store_code'] == "") {
        unset($_REQUEST['store_code']);
        $error['store_code'] = "Nhập store_code";
    } else {
        $store_code = htmlspecialchars($_REQUEST['store_code']);
    }
} else {
    $error['store_code'] = "Nhập store_code";
}

if (isset($_REQUEST['username'])) {
    if ($_REQUEST['username'] == "") {
        unset($_REQUEST['username']);
        $error['username'] = "Nhập username";
    } else {
        $username = htmlspecialchars($_REQUEST['username']);
    }
} else {
    $error['username'] = "Nhập username";
}

if (isset($_REQUEST['password'])) {
    if ($_REQUEST['password'] == "") {
        unset($_REQUEST['password']);
        $error['password'] = "Nhập password";
    } else {
        $password = md5($_REQUEST['password']);
    }
} else {
    $error['password'] = "Nhập password";
}


if (empty($error)) {
    $sql = "SELECT 
            `tbl_business_model`.`id` as `id_model`,
            `tbl_business_model`.`business_model` as `business_model`,

            `tbl_business_store`.`id` as `id_business`,
            `tbl_business_store`.`store_code` as `store_code`,
            `tbl_business_store`.`store_name` as `store_name`,
            `tbl_business_store`.`store_phone` as `store_phone`,
            `tbl_business_store`.`store_address` as `store_address`,
            `tbl_business_store`.`store_prefix` as `store_prefix`,

            `tbl_account_account`.`id` as `id_account`,
            `tbl_account_account`.`id_type` as `id_type`,
            `tbl_account_account`.`account_username` as `username`,
            `tbl_account_account`.`account_password` as `password`,
            `tbl_account_account`.`account_fullname` as `fullname`,
            `tbl_account_account`.`account_email` as `email`,
            `tbl_account_account`.`account_status` as `account_status`,
            `tbl_account_account`.`force_sign_out` as `force_sign_out`,

            `tbl_account_type`.`type_account` as `type_account`,
            `tbl_account_type`.`description` as `type_description`
            FROM `tbl_account_account`
            LEFT JOIN `tbl_business_store` ON `tbl_business_store`.`id` = `tbl_account_account`.`id_business`
            LEFT JOIN `tbl_account_type` ON `tbl_account_type`.`id` = `tbl_account_account`.`id_type`
            LEFT JOIN `tbl_business_model` ON `tbl_business_model`.`id` = `tbl_business_store`.`id_business_model`
            WHERE `tbl_account_account`.`account_username` = '{$username}' 
            AND `tbl_account_account`.`account_password` = '{$password}'
            AND `tbl_business_store`.`store_code` = '{$store_code}'
            ";
    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums > 0) {
        $user_arr = array();
        $user_arr['success'] = 'true';
        $user_arr['data'] = array();
        while ($row = db_assoc($result)) {
            if ($row['account_status'] == "N") {
                returnError("Tài khoản này đã bị khóa");
            }

            $query = "UPDATE tbl_account_account SET ";
            $query .= " force_sign_out  = '0' WHERE id = '" . $row['id_account'] . "'";
            db_qr($query);

            $user_item = array(
                'id' => $row['id_account'],
                'id_model' => $row['id_model'],
                'business_model' => $row['business_model'],
                'id_business' => $row['id_business'],
                'store_name' => $row['store_name'],
                'store_prefix' => $row['store_prefix'],
                'store_phone' => $row['store_phone'],
                'store_address' => $row['store_address'],
                'id_type' => $row['id_type'],
                'username' => $row['username'],
                'fullname' => $row['fullname'],
                'email' => $row['email'],
                'account_status' => $row['account_status'],
                'type_account' => $row['type_account'],
                'type_description' => $row['type_description'],
                'token' => "" //// token

            );


            if ($row['id_type'] == '1') {
                $user_item['role_permission'] = getRolePermission($row['id_account']);
            }

            $payload = array(
                "nbf" => time(),  //cho phép sử dụng token tại thời điểm này
                "exp" => time() + 60, // token hết hạn
                'id_business' => $row['id_business'],
                'username' => $row['username'],
                'password' => $row['password'],
                'email' => $row['email'],
                'id_type' => $row['id_type'],
            );

            $token = JWT::encode($payload, $secret_key);
            $user_item['token'] = $token;

            array_push($user_arr['data'], $user_item);
        }
        reJson($user_arr);
    } else {
        returnError("Sai tên đăng nhập hoặc mật khẩu");
    }
} else {
    $error['success'] = "false";
    $error['message'] = "Đăng nhập không thành công";
    reJson($error);
}
