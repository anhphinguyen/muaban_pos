<?php

$error = array();

// if (isset($_REQUEST['id_business'])) {
//     if ($_REQUEST['id_business'] == '') {
//         unset($_REQUEST['id_business']);
//         returnError("Nhập id_business");
//     }
// }

// if (!isset($_REQUEST['id_business'])) {
//     returnError("Nhập id_business");
// }

// $id_business = $_REQUEST['id_business'];


if (isset($_REQUEST['id_account'])) {
    if ($_REQUEST['id_account'] == '') {
        unset($_REQUEST['id_account']);
        returnError("Nhập id_account");
    }
}

if (!isset($_REQUEST['id_account'])) {
    returnError("Nhập id_account");
}

$id_account = $_REQUEST['id_account'];


$sql = "SELECT * FROM `tbl_account_account` WHERE `id` = '{$id_account}' ";
$result = mysqli_query($conn, $sql);
$nums = mysqli_num_rows($result);
if ($nums > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $password = $row['account_password'];
    }
}

if (isset($_REQUEST['old_pass'])) {
    if ($_REQUEST['old_pass'] == '') {
        unset($_REQUEST['old_pass']);
        $error['old_pass'] = "Nhập mật khẩu cũ";
    } elseif (md5($_REQUEST['old_pass']) != $password) {
        $error['old_pass'] = "Mật khẩu cũ không trùng khớp";
    } else {
        $old_pass = md5($_REQUEST['old_pass']);
    }
} else {
    $error['old_pass'] = "Nhập mật khẩu cũ";
}

if (isset($_REQUEST['new_pass'])) {
    if ($_REQUEST['new_pass'] == '') {
        unset($_REQUEST['new_pass']);
        $error['new_pass'] = "Nhập mật khẩu mới";
    } elseif ($_REQUEST['new_pass'] == $_REQUEST['old_pass']) {
        $error['new_pass'] = "Mật khẩu mới phải khác mật khẩu cũ";
    } else {
        $new_pass = md5($_REQUEST['new_pass']);
    }
} else {
    $error['new_pass'] = "Nhập mật khẩu mới";
}

if (empty($error)) {
    $sql = "UPDATE `tbl_account_account` 
                SET `account_password` = '{$new_pass}' 
                WHERE `id` = '{$id_account}'
                ";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        returnSuccess("Đổi mật khẩu thành công", $token);
    }else{
        returnError("Đổi mật khẩu không thành công.");
    }
} else {
    returnError("Sai mật khẩu");
}
