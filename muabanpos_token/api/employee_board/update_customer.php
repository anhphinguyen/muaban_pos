<?php
$error = array();
if (isset($_REQUEST['id_customer'])) {
    if ($_REQUEST['id_customer'] == '') {
        unset($_REQUEST['id_customer']);
        $error['id_customer'] = "Nhập id_customer";
    } else {
        $id_customer = $_REQUEST['id_customer'];
    }
}else{
    $error['id_customer'] = "Nhập id_customer";
}

if(empty($error)){
    $success = array();
    if (isset($_REQUEST['customer_name'])&&!empty($_REQUEST['customer_name'])) { //*
        $customer_name = htmlspecialchars($_REQUEST['customer_name']);
        $sql = "UPDATE `tbl_customer_customer` SET";
        $sql .= " `customer_name` = '{$customer_name}'";
        $sql .= " WHERE `id` = '{$id_customer}'";

        if (mysqli_query($conn, $sql)) {
            $success['customer_name'] = "true";
        }
    }

    if (isset($_REQUEST['customer_phone'])&&!empty($_REQUEST['customer_phone'])) {
        $customer_phone = htmlspecialchars($_REQUEST['customer_phone']);
        $sql = "UPDATE `tbl_customer_customer` SET";
        $sql .= " `customer_phone` = '{$customer_phone}'";
        $sql .= " WHERE `id` = '{$id_customer}'";

        if (mysqli_query($conn, $sql)) {
            $success['customer_phone'] = "true";
        }
    }
    if (isset($_REQUEST['customer_address'])&&!empty($_REQUEST['customer_address'])) {
        $customer_address = htmlspecialchars($_REQUEST['customer_address']);
        $sql = "UPDATE `tbl_customer_customer` SET";
        $sql .= " `customer_address` = '{$customer_address}'";
        $sql .= " WHERE `id` = '{$id_customer}'";

        if (mysqli_query($conn, $sql)) {
            $success['customer_address'] = "true";
        }
    }
    if (isset($_REQUEST['customer_email'])&&!empty($_REQUEST['customer_email'])) {
        $customer_email = htmlspecialchars($_REQUEST['customer_email']);
        $sql = "UPDATE `tbl_customer_customer` SET";
        $sql .= " `customer_email` = '{$customer_email}'";
        $sql .= " WHERE `id` = '{$id_customer}'";

        if (mysqli_query($conn, $sql)) {
            $success['customer_email'] = "true";
        }
    }
    if (isset($_REQUEST['customer_taxcode'])&&!empty($_REQUEST['customer_taxcode'])) {
        $customer_taxcode = htmlspecialchars($_REQUEST['customer_taxcode']);
        $sql = "UPDATE `tbl_customer_customer` SET";
        $sql .= " `customer_taxcode` = '{$customer_taxcode}'";
        $sql .= " WHERE `id` = '{$id_customer}'";

        if (mysqli_query($conn, $sql)) {
            $success['customer_taxcode'] = "true";
        }
    }

    if (!empty($success)) {
        echo json_encode(array(
            'success' => 'true',
            'message' => 'Cập nhật thành công',
        ));
    }else{
        returnSuccess("Không có thông tin cập nhật");
    }
}else {
    returnError("Cập nhật không thành công");
}
?>