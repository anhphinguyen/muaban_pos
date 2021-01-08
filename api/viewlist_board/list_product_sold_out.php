<?php

$sql = "SELECT * FROM `tbl_product_product` WHERE `product_sold_out` = 'Y'";

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

if (isset($_REQUEST['filter'])) {
    if ($_REQUEST['filter'] == '') {
        unset($_REQUEST['filter']);
    } else {
        $filter = $_REQUEST['filter'];
        $sql .= " AND `product_title` LIKE '%{$filter}%'";
    }
}


$product_sold_old_arr = array();

// $total = count(db_fetch_array($sql));
// $limit = 20;
// $page = 1;

// if (isset($_REQUEST['limit']) && !empty($_REQUEST['limit'])) {
//     $limit = $_REQUEST['limit'];
// }
// if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
//     $page = $_REQUEST['page'];
// }


// $total_page = ceil($total / $limit);
// $start = ($page - 1) * $limit;
// $sql .= " ORDER BY `tbl_product_product`.`id` DESC LIMIT {$start},{$limit}";


$result = db_qr($sql);
$nums = db_nums($result);

if ($nums > 0) {
    $product_sold_old_arr['success'] = 'true';

    // $product_sold_old_arr['total'] = strval($total);
    // $product_sold_old_arr['total_page'] = strval($total_page);
    // $product_sold_old_arr['limit'] = strval($limit);
    // $product_sold_old_arr['page'] = strval($page);

    $product_sold_old_arr['data'] = array();
    while ($row = db_assoc($result)) {
        $product_sold_old_item = array(
            'id_disable' => $row['id'],
            'product_img' => $row['product_img'],
            'product_title' => $row['product_title'],
            'product_sales_price' => $row['product_sales_price'],
            'product_code' => $row['product_code'],
        );
        array_push($product_sold_old_arr['data'], $product_sold_old_item);
    }

    reJson($product_sold_old_arr);
} else {
    returnSuccess("Không có sản phẩm");
}
