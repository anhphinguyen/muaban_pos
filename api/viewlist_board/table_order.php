<?php

$sql = "SELECT * FROM `tbl_order_order` WHERE `order_status` < '5'";

if (isset($_REQUEST['id_order'])) {
    if ($_REQUEST['id_order'] == '') {
        unset($_REQUEST['id_order']);
        returnError("Nhập id_order");
    } else {
        $id_order = $_REQUEST['id_order'];
        $sql .= " AND `id` = '{$id_order}'";
    }
} else {
    returnError("Nhập id_order");
}

if (isset($_REQUEST['id_floor'])) {
    if ($_REQUEST['id_floor'] == '') {
        unset($_REQUEST['id_floor']);
    } else {
        $id_floor = $_REQUEST['id_floor'];
    }
}
$order_arr = array();

$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    $order_arr['success'] = 'true';
    $order_arr['data'] = array();
    if (isset($id_floor) && !empty($id_floor)) {
        $sql_total_table = "SELECT * FROM `tbl_organization_table` 
                        WHERE `id_floor` = '{$id_floor}'";
        $total_table = count(db_fetch_array($sql_total_table));

        $sql_total_table_empty = "SELECT * FROM `tbl_organization_table` 
                                WHERE `id_floor` = '{$id_floor}' 
                                AND `table_status` = 'empty'";
        $total_table_empty = count(db_fetch_array($sql_total_table_empty));

        $total_table_full = $total_table - $total_table_empty;
    }
    while ($row = db_assoc($result)) {

        $sql_product_order = "SELECT * FROM `tbl_order_detail` 
                                            WHERE `id_order` = '{$row['id']}'";
        $total_product_order = count(db_fetch_array($sql_product_order));

        $sql_product_finished = "SELECT * FROM `tbl_order_detail` 
                                                         WHERE `id_order` = '{$row['id']}' 
                                                         AND `detail_status` != 'N'";

        $total_product_finished = count(db_fetch_array($sql_product_finished));
        $total_product_notyet = $total_product_order - $total_product_finished;


        $sql_total_cost_tmp = "SELECT * FROM `tbl_order_detail`
                                WHERE `id_order` = '{$row['id']}'";
        $result_total_cost_tmp = db_qr($sql_total_cost_tmp);
        $nums_total_cost_tmp = db_nums($result_total_cost_tmp);
        $total_cost_tmp = 0;
        $total_acture = 0;
        if ($nums_total_cost_tmp > 0) {
            while ($row_total_cost_tmp = db_assoc($result_total_cost_tmp)) {
                //neus status == Y
                //=> ++  total
                //else
                if ($row_total_cost_tmp['detail_status'] == 'Y') {
                    $total_acture += $row_total_cost_tmp['detail_cost'] * $row_total_cost_tmp['detail_quantity'];
                } else {
                    $total_cost_tmp += $row_total_cost_tmp['detail_cost'] * $row_total_cost_tmp['detail_quantity'];
                }
            }
        }
        if ($total_acture > 0) {
            $total_cost_tmp = $total_acture;
        } else {
            $total_cost_tmp = $total_cost_tmp;
        }


        if ($row['order_status'] < '5') {
            $order_item = array(
                'id_order' => $row['id'],
                'total_table_empty' => (isset($total_table_empty))?strval($total_table_empty):"",
                'total_table_full' => (isset($total_table_full))?strval($total_table_full):"",
                'order_status' => $row['order_status'],
                'order_floor' => $row['order_floor'],
                'order_table' => $row['order_table'],
                'total_product_finished' => strval($total_product_finished),
                'total_product_notyet' => strval($total_product_notyet),
                'order_check_time' => $row['order_check_time'],
                'total_cost_tmp' => strval($total_cost_tmp),
            );

            array_push($order_arr['data'], $order_item);
        }
    }
    reJson($order_arr);
}
