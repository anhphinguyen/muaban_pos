<?php

$sql = "SELECT 
        SUM(tbl_order_detail.detail_quantity) as total_detail_quantity, 
        tbl_order_detail.detail_extra as detail_extra,
        tbl_order_detail.detail_status as detail_status,
        tbl_order_detail.detail_cost as detail_cost,

        tbl_product_product.id as id_product,
        tbl_product_product.product_title as product_title,
        tbl_product_product.product_img as product_img
        FROM tbl_order_detail
        LEFT JOIN tbl_product_product ON tbl_product_product.id = tbl_order_detail.id_product
        WHERE detail_status = 'N'  
        GROUP BY id_product, detail_extra";

$result_arr = array();
$result_arr['success'] = 'true';
$result_arr['data'] = array();

$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $result_item = array(
            'id_detail' => "",
            'id_product' => $row['id_product'],
            'product_title' => $row['product_title'],
            'product_img' => $row['product_img'],
            'detail_cost' => $row['detail_cost'],
            'detail_quantity' => $row['total_detail_quantity'],
            'detail_status' => $row['detail_status'],
            'product_extra' => array()
        );

        // product_extra
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
                        'product_title_extra' => $row_extra['product_title_extra'],
                    );
                }
                array_push($product_extra, $product_extra_item);
            }
        }

        $result_item['product_extra'] = $product_extra;

        array_push($result_arr['data'], $result_item);
    }
    
    reJson($result_arr);
}else{
    returnError("Không có sản phẩm được gọi");
}
