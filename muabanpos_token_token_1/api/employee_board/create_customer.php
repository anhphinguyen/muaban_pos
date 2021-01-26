<?php

$error = array();

if (isset($_REQUEST['id_business'])) {
    if ($_REQUEST['id_business'] == '') {
        unset($_REQUEST['id_business']);
        returnSuccess("Nhap id_business");
    } else {
        $id_business = $_REQUEST['id_business'];
    }
} else {
    returnSuccess("Nhap id_business");

}

if (isset($_REQUEST['id_account'])) {
    if ($_REQUEST['id_account'] == '') {
        unset($_REQUEST['id_account']);
        returnSuccess("Nhap id_account");

    } else {
        $id_account = $_REQUEST['id_account'];
    }
} else {
    returnSuccess("Nhap id_account");

}

if (isset($_REQUEST['customer_name'])) {   //*
    if ($_REQUEST['customer_name'] == '') {
        unset($_REQUEST['customer_name']);
        returnSuccess("Nhap customer_name");

    } else {
        $customer_name = htmlspecialchars($_REQUEST['customer_name']);
    }
} else {
    returnSuccess("Nhap customer_name");

}


if (isset($_REQUEST['customer_phone'])) {  //*
    if ($_REQUEST['customer_phone'] == '') {
        unset($_REQUEST['customer_phone']);
        returnSuccess("Nhap customer_phone");

    } else {
        $customer_phone = htmlspecialchars($_REQUEST['customer_phone']);
    }
} else {
    returnSuccess("Nhap customer_phone");

}


if (isset($_REQUEST['customer_address'])) {
    if ($_REQUEST['customer_address'] == '') {
        unset($_REQUEST['customer_address']);
    } else {
        $customer_address = htmlspecialchars($_REQUEST['customer_address']);
    }
}

if (isset($_REQUEST['customer_email'])) {
    if ($_REQUEST['customer_email'] == '') {
        unset($_REQUEST['customer_email']);
    } else {
        $customer_email = htmlspecialchars($_REQUEST['customer_email']);
    }
}

if (isset($_REQUEST['customer_taxcode'])) {
    if ($_REQUEST['customer_taxcode'] == '') {
        unset($_REQUEST['customer_taxcode']);
    } else {
        $customer_taxcode = htmlspecialchars($_REQUEST['customer_taxcode']);
    }
}

if (empty($error)) {
// check customer exist
    $sql = "SELECT * FROM `tbl_customer_customer` 
            WHERE `customer_phone` = '{$customer_phone}'
            AND `id_business` = '{$id_business}'
            ";
    $result = db_qr($sql);
    $nums = db_nums($result);
    if($nums > 0){
        returnSuccess("Đã tồn tại khách hàng này");
    }
    $sql = "SELECT `store_prefix` FROM `tbl_business_store` WHERE `id` = '{$id_business}'";
    $result = db_qr($sql);
    $nums = db_nums($result);
    if($nums > 0){
        while($row = db_assoc($result)){
            $store_prefix = $row['store_prefix'];
        }
    }
    $customer_code = $store_prefix . "KH" . substr(time(), -8);
    $sql = "INSERT INTO `tbl_customer_customer` SET 
                                `id_business` = '{$id_business}',
                                `id_account` = '{$id_account}',
                                `customer_name` = '{$customer_name}',
                                `customer_code` = '{$customer_code}',
                                `customer_phone` = '{$customer_phone}'
                                ";
    if(isset($customer_address) && !empty($customer_address)){
        $sql .= " ,`customer_address` = '{$customer_address}'";
    }
    if(isset($customer_email) && !empty($customer_email)){
        $sql .= " ,`customer_email` = '{$customer_email}'";
    }
    if(isset($customer_taxcode) && !empty($customer_taxcode)){
        $sql .= " ,`customer_taxcode` = '{$customer_taxcode}'";
    }

    if(mysqli_query($conn, $sql)){
        $id_insert = mysqli_insert_id($conn);

        $sql = "SELECT * FROM `tbl_customer_customer` WHERE `id` = '{$id_insert}'";
        $result = db_qr($sql);
        $nums = db_nums($result);
        $customer_arr = array();
        if($nums > 0){
            $customer_arr['success'] = 'true';
            $customer_arr['data'] = array();
            while($row = db_assoc($result)){
                $customer_item = array(
                    'id_customer' => $row['id'],
                    'customer_name' => htmlspecialchars_decode($row['customer_name']),
                    'customer_code' => htmlspecialchars_decode($row['customer_code']),
                    'customer_phone' => htmlspecialchars_decode($row['customer_phone']),
                    'customer_address' => htmlspecialchars_decode($row['customer_address']),
                    'customer_email' => htmlspecialchars_decode($row['customer_email']),
                    'customer_birthday' => htmlspecialchars_decode($row['customer_birthday']),
                    'customer_sex' => htmlspecialchars_decode($row['customer_sex']),
                    'customer_point' => htmlspecialchars_decode($row['customer_point']),
                    'customer_level' => "",
                    'customer_taxcode' => htmlspecialchars_decode($row['customer_taxcode']),
                );
                array_push($customer_arr['data'], $customer_item);
            }
            reJson($customer_arr);
        }
    }else{
        returnError("Tạo khách hàng không thành công");
    }
}
