<?php

// Thực Đơn theo từng cửa hàng
$sql = "SELECT `tbl_product_product`.*,

                `tbl_product_unit`.`unit` as `product_unit`,

                `tbl_product_category`.`category_title` as `category_title`
                FROM  `tbl_product_product`
                LEFT JOIN `tbl_product_unit` ON `tbl_product_product`.`id_unit` = `tbl_product_unit`.`id`
                LEFT JOIN `tbl_product_category` ON `tbl_product_product`.`id_category` = `tbl_product_category`.`id`
                WHERE `tbl_product_product`.`product_sold_out` = 'Y'";


$error = array();
if (isset($_REQUEST['id_business'])) {
    if ($_REQUEST['id_business'] == '') {
        unset($_REQUEST['id_business']);
        $error['id_business'] = "Nhập id_business";
    } else {
        $id_business = $_REQUEST['id_business'];
        $sql .= " AND `tbl_product_product`.`id_business` = '{$id_business}'";

        if (isset($_REQUEST['id_category'])) {
            if ($_REQUEST['id_category'] == '') {
                unset($_REQUEST['id_category']);
            } else {
                $id_category = $_REQUEST['id_category'];
                $sql .= " AND `tbl_product_product`.`id_category` = '{$id_category}'";
            }
        }


        if (isset($_REQUEST['filter'])) {
            if ($_REQUEST['filter'] == '') {
                unset($_REQUEST['filter']);
            } else {
                $filter = $_REQUEST['filter'];
                $sql .= " AND `tbl_product_product`.`product_title` LIKE '%{$filter}%'";
            }
        }
    }
} else {
    $error['id_business'] = "Nhập id_business";
}

$product_arr = array();

$total = count(db_fetch_array($sql));
$limit = 20;
$page = 1;

if (isset($_REQUEST['limit']) && !empty($_REQUEST['limit'])) {
    $limit = $_REQUEST['limit'];
}
if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
    $page = $_REQUEST['page'];
}


$total_page = ceil($total / $limit);
$start = ($page - 1) * $limit;
$sql .= " ORDER BY `tbl_product_product`.`id` DESC LIMIT {$start},{$limit}";


if (empty($error)) {
    $product_arr['success'] = 'true';
    $product_arr['total'] = strval($total);
    $product_arr['total_page'] = strval($total_page);
    $product_arr['limit'] = strval($limit);
    $product_arr['page'] = strval($page);
    $product_arr['data'] = array();
    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums > 0) {
        while ($row = db_assoc($result)) {
            $product_item = array(
                'id' => $row['id'],
                'id_business' => $row['id_business'],
                'id_category' => $row['id_category'],
                'category_title' => $row['category_title'],
                'product_img' => $row['product_img'],
                'product_title' => $row['product_title'],
                'product_code' => $row['product_code'],
                'product_sales_price' => $row['product_sales_price'],
                'product_unit' => $row['product_unit'],
                'product_description' => $row['product_description'],
                'product_point' => $row['product_point'],
                'product_disable' => $row['product_disable'],
                'product_sold_out' => $row['product_sold_out'],
                'product_extra' => array()
            );

            // product_extra
            $sql_extra = "SELECT 
                          `tbl_product_extra`.`id` as `id`,
                          `tbl_product_extra`.`id_product` as `id_product`,
                          `tbl_product_extra`.`id_product_extra` as `id_product_extra`,
                          `tbl_product_product`.`product_title` as `product_title`,
                          `tbl_product_product`.`product_sales_price` as `product_extra_sales_price`
                          FROM `tbl_product_extra`
                          LEFT JOIN `tbl_product_product` 
                          ON `tbl_product_extra`.`id_product_extra` = `tbl_product_product`.`id`
                          WHERE 1=1
                          ";
            $result_extra = db_qr($sql_extra);
            $nums_extra = db_nums($result_extra);
            if($nums > 0){
                while($row_extra = db_assoc($result_extra)){
                    $product_extra = array(
                        // 'id' => $row_extra['id'],
                        // 'id_product' => $row_extra['id_product'],
                        'id_product_extra' => $row_extra['id_product_extra'],
                        'product_title_extra' => $row_extra['product_title'],
                        'product_extra_sales_price' => $row_extra['product_extra_sales_price'],
                    );

                    if($row_extra['id_product'] == $row['id']){
                        array_push($product_item['product_extra'], $product_extra);
                    }
                }
            }



            array_push($product_arr['data'], $product_item);
        }
        reJson($product_arr);
    } else {
        returnSuccess("Không tồn tại sản phẩm");
    }
} else {
    $error['success'] = 'false';
    $error['message'] = 'Lấy danh sách sản phẩm không thành công';
    reJson($error);
}
