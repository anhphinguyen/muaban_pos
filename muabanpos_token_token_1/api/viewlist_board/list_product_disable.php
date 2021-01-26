<?php

$sql = "SELECT * FROM `tbl_product_product` WHERE `product_disable` = 'Y'";

if (isset($_REQUEST['id_business'])) {
    if ($_REQUEST['id_business'] == '') {
        unset($_REQUEST['id_business']);
        returnError("Nhập id_business");
    } else {
        $id_business = $_REQUEST['id_business'];
        $sql .= " AND `id_business` = '{$id_business}'";
    }
} else {
    returnError("Nhập id_business");
}

if (isset($_REQUEST['id_product'])) {
    if ($_REQUEST['id_product'] == '') {
        unset($_REQUEST['id_product']);
    } else {
        $id_product = $_REQUEST['id_product'];
        $sql .= " AND `id` = '{$id_product}'";
    }
}

$result = db_qr($sql);
$nums = db_nums($result);
$product_sold_old_arr = array();
if ($nums > 0) {
    $product_sold_old_arr['success'] = 'true';
    $product_sold_old_arr['refresh_token'] = $token;

    $product_sold_old_arr['data'] = array();
    while ($row = db_assoc($result)) {
        $product_sold_old_item = array(
            'id_disable' => $row['id'],
            'product_img' => $row['product_img'],
            'product_title' => $row['product_title'],
        );
        array_push($product_sold_old_arr['data'], $product_sold_old_item);
    }

    reJson($product_sold_old_arr);
} else {
    returnSuccess("Không có sản phẩm", $token);
}
