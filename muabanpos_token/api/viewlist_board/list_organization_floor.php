<?php

// Thực Đơn theo từng cửa hàng
$sql = "SELECT *
                FROM  `tbl_organization_floor`
                WHERE 1=1";


$error = array();
if (isset($_REQUEST['id_business'])) {
    if ($_REQUEST['id_business'] == '') {
        unset($_REQUEST['id_business']);
        $error['id_business'] = "Nhập id_business";
    } else {
        $id_business = $_REQUEST['id_business'];
        $sql .= " AND `id_business` = '{$id_business}'";

        if (isset($_REQUEST['filter'])) {
            if ($_REQUEST['filter'] == '') {
                unset($_REQUEST['filter']);
            } else {
                $filter = $_REQUEST['filter'];
                $sql .= " AND `floor_title` LIKE '%{$filter}%'";
            }
        }
    }
} else {
    $error['id_business'] = "Nhập id_business";
}

$sql .= " ORDER BY `floor_priority` ASC";
$floor_arr = array();

if (empty($error)) {
    $floor_arr['success'] = 'true';
    $floor_arr['refresh_token'] = $token;

    $floor_arr['data'] = array();
    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums > 0) {
        while ($row = db_assoc($result)) {
            $floor_item = array(
                'id_floor' => $row['id'],
                'id_business' => $row['id_business'],
                'floor_priority' => $row['floor_priority'],
                'floor_title' => $row['floor_title'],
                'floor_type' => $row['floor_type'],
                'total_table_open' => "",
                'total_table_close' => "",
                'floor_table' => array(),
            );

            // floor table
            $sql_table = "SELECT *
                FROM  `tbl_organization_table`
                WHERE `id_floor` = '{$row['id']}'";

            $result_table = db_qr($sql_table);
            $nums_table = db_nums($result_table);

            $total_table = count(db_fetch_array($sql_table));

            $sql_table_open = "SELECT * FROM `tbl_organization_table` 
                                WHERE `table_status` = 'full'
                                AND `id_floor` = '{$row['id']}'";
            $total_table_open = count(db_fetch_array($sql_table_open));
            $total_table_close = $total_table - $total_table_open;

            $floor_table_arr = array();

            if ($nums_table > 0) {
                while ($row_table = db_assoc($result_table)) {
                    $table_item = array(
                        'id_table' => $row_table['id'],
                        'id_floor' => $row_table['id_floor'],
                        'table_title' => $row_table['table_title'],
                        'table_status' => $row_table['table_status'],
                        'table_order' => array(),
                    );

                    // table order

                    if ($row_table['table_status'] == 'full') {

                        $sql_order = "SELECT * FROM `tbl_order_order` WHERE `id_business` = '{$id_business}' ORDER BY `id` DESC"; ///
                        $result_order = db_qr($sql_order);
                        $nums_order = db_nums($result_order);
                        if ($nums_order > 0) {
                            while ($row_order = db_assoc($result_order)) {

                                $sql_product_order = "SELECT * FROM `tbl_order_detail` 
                                            WHERE `id_order` = '{$row_order['id']}'";
                                $total_product_order = count(db_fetch_array($sql_product_order));

                                $sql_product_finished = "SELECT * FROM `tbl_order_detail` 
                                                         WHERE `id_order` = '{$row_order['id']}' 
                                                         AND `detail_status` != 'N'";

                                $total_product_finished = count(db_fetch_array($sql_product_finished));
                                $total_product_notyet = $total_product_order - $total_product_finished;


                                $sql_total_cost_tmp = "SELECT * FROM `tbl_order_detail`
                                                       WHERE `id_order` = '{$row_order['id']}'";
                                $result_total_cost_tmp = db_qr($sql_total_cost_tmp);
                                $nums_total_cost_tmp = db_nums($result_total_cost_tmp);
                                $total_cost_tmp = 0;
                                $total_acture = 0;
                                if ($nums_total_cost_tmp > 0) {
                                    while ($row_total_cost_tmp = db_assoc($result_total_cost_tmp)) {
                                        //neus status == Y
                                        //=> ++  total
                                        //else
                                        if($row_total_cost_tmp['detail_status'] == 'Y'){
                                            $total_acture += $row_total_cost_tmp['detail_cost'] * $row_total_cost_tmp['detail_quantity'];
                                        }else{
                                            $total_cost_tmp += $row_total_cost_tmp['detail_cost'] * $row_total_cost_tmp['detail_quantity'];
                                        }
                                    }
                                }
                                if($total_acture > 0){
                                    $total_cost_tmp = $total_acture;
                                }else{
                                    $total_cost_tmp = $total_cost_tmp;
                                }


                                if ($row_order['order_status'] < '5') {
                                    $table_order = array(
                                        'id_order' => $row_order['id'],
                                        'order_status' => $row_order['order_status'],
                                        'order_floor' => $row_order['order_floor'],
                                        'order_table' => $row_order['order_table'],
                                        'total_product_finished' => strval($total_product_finished),
                                        'total_product_notyet' => strval($total_product_notyet),
                                        'order_check_time' => $row_order['order_check_time'],
                                        // 'order_total_cost' => $row_order['order_total_cost'],
                                        'total_cost_tmp' => strval($total_cost_tmp),
                                    );
                                    if (strtolower($row_order['order_floor']) == strtolower($row['floor_title'])) {
                                        if (strtolower($row_order['order_table']) == strtolower($row_table['table_title'])) {
                                            array_push($table_item['table_order'], $table_order);
                                        }
                                    }
                                }
                            }
                        }
                    }


                    if ($row_table['id_floor'] == $row['id']) {
                        $floor_item['total_table_open'] = strval($total_table_open);
                        $floor_item['total_table_close'] = strval($total_table_close);
                        array_push($floor_item['floor_table'], $table_item);
                    }
                }
            }


            array_push($floor_arr['data'], $floor_item);
        }
        reJson($floor_arr);
    } else {
        returnSuccess("Không tồn tại tầng", $token);
    }
} else {
    $error['success'] = 'false';
    $error['message'] = 'Lấy danh sách tầng không thành công';
    reJson($error);
}
