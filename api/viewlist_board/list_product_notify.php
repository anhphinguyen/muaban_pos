<?php

$sql = "SELECT
        `tbl_order_order`.`id` as `id_order`,
        `tbl_order_order`.`order_floor` as `order_floor`,
        `tbl_order_order`.`order_table` as `order_table`,

        `tbl_product_product`.`id` as `id_product`,
        `tbl_product_product`.`product_title` as `product_title`,

        `tbl_order_detail`.`id` as `id_detail`,
        `tbl_order_detail`.`detail_extra` as `detail_extra`,
        `tbl_order_detail`.`detail_quantity` as `detail_quantity`,
        `tbl_order_detail`.`detail_view` as `detail_view`,
        `tbl_order_detail`.`detail_status` as `detail_status`,
        `tbl_order_detail`.`id_business` as `id_business`
        FROM `tbl_order_detail` 
        LEFT JOIN `tbl_product_product` 
        ON `tbl_product_product`.`id` = `tbl_order_detail`.`id_product`
        LEFT JOIN `tbl_order_order` 
        ON `tbl_order_order`.`id` = `tbl_order_detail`.`id_order`
        WHERE  1=1
        ";


if(isset($_REQUEST['id_business'])){
    if($_REQUEST['id_business'] == ''){
        unset($_REQUEST['id_business']);
        returnError("Nhập id_business");
    }else{
        $id_business = $_REQUEST['id_business'];
        $sql .= " AND `tbl_order_detail`.`id_business` = '{$id_business}'";
        $sql .= " AND `tbl_order_detail`.`detail_status` != 'N'";
        $sql .= " AND `tbl_order_detail`.`detail_view` = 'N'";
    }
}else{
    returnError("Nhập id_business");
}


$result = db_qr($sql);
$nums = db_nums($result);
$total = count(db_fetch_array($sql));

$product_arr = array();
if ($nums > 0) {
    $product_arr['success'] = 'true';
    $product_arr['total'] = strval($total);

    $product_arr['data'] = array();

    while ($row = db_assoc($result)) {
        $product_item = array(
            'id' => $row['id_detail'],
            'id_order' => $row['id_order'],
            'id_product' => $row['id_product'],
            'order_floor' => $row['order_floor'],
            'order_table' => $row['order_table'],
            'order_location' => $row['order_floor'] ." - " .$row['order_table'],
            'product_title' => $row['product_title'],
            'detail_quantity' => $row['detail_quantity'],
            'detail_view' => $row['detail_view'],
            'detail_status' => $row['detail_status'],
            'product_extra' => array(),
            'id_business' => $row['id_business'],
        );

        // product extra
        $id_extra_arr = explode(",", $row['detail_extra']);
        $product_extra = array();
        for ($i = 0; $i < count($id_extra_arr); $i++) {
            $sql_extra = "SELECT 
                            `tbl_product_product`.`id` as `id`,
                            `tbl_product_product`.`product_title` as `product_title_extra`
                            FROM `tbl_product_product` 
                            WHERE `id` = '{$id_extra_arr[$i]}'
                            ";
            $result_extra = db_qr($sql_extra);
            $nums_extra = db_nums($result_extra);
            if ($nums_extra > 0) {
                while ($row_extra = db_assoc($result_extra)) {
                    $product_extra_item = array(
                        'id' => $row_extra['id'],
                        'product_title_extra' => $row_extra['product_title_extra']
                    );
                }
                array_push($product_extra, $product_extra_item);
            }
        }

        $product_item['product_extra'] = $product_extra;
        array_push($product_arr['data'], $product_item); 
    }

    reJson($product_arr);
}else{
    returnSuccess("Danh sách trống");
}
