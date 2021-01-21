<?php
$sql = "SELECT 
            `tbl_customer_customer`.`id` as `id_customer`,
            `tbl_customer_customer`.`customer_code` as `customer_code`,
            `tbl_customer_customer`.`customer_name` as `customer_name`,

            `tbl_account_account`.`id` as `id_account`,
            `tbl_account_account`.`account_username` as `account_username`,

            `tbl_order_order`.`id` as `id_order`,
            -- `tbl_order_order`.`id_floor` as `id_floor`,
            -- `tbl_order_order`.`id_table` as `id_table`,
            `tbl_order_order`.`order_code` as `order_code`,
            `tbl_order_order`.`order_status` as `order_status`,
            `tbl_order_order`.`order_floor` as `order_floor`,
            `tbl_order_order`.`order_table` as `order_table`,
            `tbl_order_order`.`order_direct_discount` as `order_direct_discount`,
            `tbl_order_order`.`order_created` as `order_created`,
            `tbl_order_order`.`order_comment` as `order_comment`,
            `tbl_order_order`.`order_total_cost` as `order_total_cost`,
            `tbl_order_order`.`id_business` as `id_business`
            FROM `tbl_order_order`
            LEFT JOIN `tbl_customer_customer` ON `tbl_customer_customer`.`id`= `tbl_order_order`.`id_customer`
            LEFT JOIN `tbl_account_account` ON `tbl_account_account`.`id`= `tbl_order_order`.`id_account`
            WHERE 1=1
        ";

$error = array();
// $error['success'] = 'false';
if (isset($_REQUEST['id_order'])) {
    if ($_REQUEST['id_order'] == '') {
        unset($_REQUEST['id_order']);
        returnError("Nhập id_order");
    } else {
        $id_order = $_REQUEST['id_order'];
        $sql .= " AND `tbl_order_order`.`id` = '{$id_order}'";
    }
} else {
    returnError("Nhập id_order");
}




$order_arr = array();
if (empty($error)) {
    $order_arr['success'] = 'true';
    $order_arr['data'] = array();

    $result = db_qr($sql);
    $nums = db_nums($result);



    if ($nums > 0) {
        while ($row = db_assoc($result)) {
            $order_item = array(
                'id_order' => $row['id_order'],
                // 'id_floor' => $row['id_floor'],
                // 'id_table' => $row['id_table'],
                'id_customer' => (!empty($row['id_customer'])) ? $row['id_customer'] : "0", //$row['id_customer']
                'customer_code' => (!empty($row['customer_code'])) ? $row['customer_code'] : "", //$row['customer_code']
                'customer_name' => (!empty($row['customer_name'])) ? $row['customer_name'] : "", //$row['customer_code']
                'customer_level' => "",
                'id_account' => $row['id_account'], 
                'id_business' => $row['id_business'], 
                'account_username' => $row['account_username'],
                'order_code' => $row['order_code'],
                'order_status' => $row['order_status'],
                'order_floor' => $row['order_floor'],
                'order_table' => $row['order_table'],
                'order_created' => $row['order_created'],
                'order_comment' => $row['order_comment'] != null ? $row['order_comment'] : "",
                'total_product' => "",
                'total_cost_tmp' => "",
                'order_direct_discount' => $row['order_direct_discount'],
                'order_total_cost' => $row['order_total_cost'] != null ?  $row['order_total_cost'] : "0",
                'order_detail' => array()
            );

            if ($row['id_customer'] > 0) {
                // sắp xếp cấp độ
                $sql_level = "SELECT * FROM `tbl_customer_point` WHERE `id_business` = '{$row['id_business']}'";
                $result_level = db_qr($sql_level);
                $nums_level = db_nums($result_level);
                if ($nums_level > 0) {
                    $point_arr = array();
                    $point_arr['id_level'] = array();
                    $point_arr['point'] = array();
                    $point_arr['level'] = array();
                    while ($row_level = db_assoc($result_level)) {
                        $id_level = $row_level['id'];
                        $customer_point = $row_level['customer_point'];
                        $customer_level = $row_level['customer_level'];

                        array_push($point_arr['id_level'], $id_level);
                        array_push($point_arr['point'], $customer_point);
                        array_push($point_arr['level'], $customer_level);
                    }
                    $point_arr = arrange_position($point_arr['point'], $point_arr['level'], $point_arr['id_level']);
                }
                // kết thúc sắp xếp cấp độ

                $sql_customer = "SELECT * FROM `tbl_customer_customer` WHERE `id` = '{$row['id_customer']}'";
                // returnError($sql_customer);
                $result_customer = db_qr($sql_customer);
                $nums_customer = db_nums($result_customer);
                if ($nums_customer > 0) {
                    while ($row_customer = db_assoc($result_customer)) {
                        if (!empty($point_arr)) {
                            for ($i = 0; $i < count($point_arr['point']); $i++) {
                                if ($row_customer['customer_point'] >= $point_arr['point'][$i]) {
                                    $order_item['customer_level'] = $point_arr['level'][$i];
                                }
                            }
                        }
                    }
                }
            }
            $sql_order_detail = "SELECT 
                                `tbl_product_product`.`id` as `id_product`,
                                `tbl_product_product`.`product_title` as `product_title`,
                                `tbl_product_product`.`product_img` as `product_img`,

                                `tbl_order_detail`.`id` as `id_detail`,
                                `tbl_order_detail`.`detail_extra` as `detail_extra`,
                                `tbl_order_detail`.`detail_cost` as `detail_cost`,
                                `tbl_order_detail`.`detail_quantity` as `detail_quantity`,
                                `tbl_order_detail`.`detail_status` as `detail_status`
                                 FROM `tbl_order_detail` 
                                 LEFT JOIN `tbl_product_product` 
                                 ON `tbl_product_product`.`id` = `tbl_order_detail`.`id_product`
                                 WHERE `id_order` = '{$id_order}'
                                
                                 ";
            // $sql_total_order_detail = $sql_order_detail." GROUP BY `tbl_order_detail`.`id_order`,
            //                                                        `tbl_order_detail`.`id_product`,
            //                                                        `tbl_order_detail`.`detail_extra` ";

            $sql_order_detail .= " ORDER BY `tbl_order_detail`.`detail_status` ASC";
            $total_order_detail = count(db_fetch_array($sql_order_detail));
            $order_item['total_product'] = strval($total_order_detail);

            $order_item['order_detail'] = array();

            $result_detail = db_qr($sql_order_detail);
            $nums_detail = db_nums($result_detail);
            if ($nums_detail > 0) {
                $total_cost_tmp = 0;
                $total_acture = 0;
                while ($row_detail = db_assoc($result_detail)) {
                    $order_detail = array(
                        'id_detail' => $row_detail['id_detail'],
                        'id_product' => $row_detail['id_product'],
                        'product_title' => $row_detail['product_title'],
                        'product_img' => $row_detail['product_img'],
                        'detail_cost' => $row_detail['detail_cost'],
                        'detail_quantity' => $row_detail['detail_quantity'],
                        'detail_status' => $row_detail['detail_status'],
                        'product_extra' => array()
                    );

                    // total_tmp
                    if ($row_detail['detail_status'] == 'Y') {
                        $total_acture += $row_detail['detail_cost'] * $row_detail['detail_quantity'];
                    } else {
                        $total_cost_tmp += $row_detail['detail_cost'] * $row_detail['detail_quantity'];
                    }


                    // product_extra
                    $id_extra_arr = explode(",", $row_detail['detail_extra']);
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

                    $order_detail['product_extra'] = $product_extra;


                    array_push($order_item['order_detail'], $order_detail);
                }
                if ($total_acture > 0) {
                    $total_cost_tmp = $total_acture;
                } else {
                    $total_cost_tmp = $total_cost_tmp;
                }
                $order_item['total_cost_tmp'] = strval($total_cost_tmp);
            }
            array_push($order_arr['data'], $order_item);
        }
        reJson($order_arr);
    } else {
        returnSuccess("Danh sách trống");
    }
}
