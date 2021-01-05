<?php
$sql = "SELECT
        `tbl_organization_floor`.`floor_type` as `order_type`,

        `tbl_order_order`.`id` as `id_order`,
        `tbl_order_order`.`order_floor` as `order_floor`,
        `tbl_order_order`.`order_table` as `order_table`,
        `tbl_order_order`.`order_status` as `order_status`,
        `tbl_order_order`.`order_check_time` as `order_check_time`

        FROM `tbl_order_order`
        LEFT JOIN `tbl_organization_floor` ON `tbl_order_order`.`order_floor` = `tbl_organization_floor`.`floor_title`
        WHERE `tbl_order_order`.`order_status` < '4'
        ";
if (isset($_REQUEST['id_business'])) {
    if ($_REQUEST['id_business'] == '') {
        unset($_REQUEST['id_business']);
        returnError("Nhập id_business");
    } else {
        $id_business = $_REQUEST['id_business'];
        $sql .= " AND `tbl_order_order`.`id_business` = '{$id_business}'";
    }
} else {
    returnError("Nhập id_business");
}

if (isset($_REQUEST['type_manager'])) {
    if ($_REQUEST['type_manager'] == '') {
        unset($_REQUEST['type_manager']);
        returnError("Nhập type_manager");
    } else {
        $type_manager = $_REQUEST['type_manager'];
    }
} else {
    returnError("Nhập type_manager");
}

switch ($type_manager) {
    case "carry_out": {
            $sql .= " AND `tbl_organization_floor`.`floor_type` = 'carry-out'";
            break;
        }
    case "eat_in": {
            $sql .= " AND `tbl_organization_floor`.`floor_type` = 'eat-in'";
            break;
        }
    default: {
            returnError("type_manager has been failed");
            break;
        }
}

$total = count(db_fetch_array($sql));
$limit = 20;
$page = 1;

if (isset($_REQUEST['limit']) && !empty($_REQUEST['limit'])) {
    $limit = $_REQUEST['limit'];
}
if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
    $page = $_REQUEST['page'];
}

$total_pages = ceil($total / $limit);
$start = ($page - 1) * $limit;

$sql .= " ORDER BY `tbl_order_order`.`id` DESC LIMIT {$start},{$limit}";
$result = db_qr($sql);
$nums = db_nums($result);
$order_arr = array();
if ($nums > 0) {
    $order_arr['success'] = 'true';
    $order_arr['total'] = strval($total);
    $order_arr['total_pages'] = strval($total_pages);
    $order_arr['limit'] = strval($limit);
    $order_arr['page'] = strval($page);
    $order_arr['start'] = strval($start);
    $order_arr['data'] = array();

    while ($row = db_assoc($result)) {
        $sql_total_product_notyet = "SELECT `id` FROM `tbl_order_detail`
                                        WHERE `id_order` = '{$row['id_order']}'
                                        AND `detail_status` = 'N'
                                        ";
        $total_product_notyet = count(db_fetch_array($sql_total_product_notyet));
        if ($total_product_notyet > 0) {
            $order_item = array(
                'id' => $row['id_order'],
                'order_type' => $row['order_type'],
                'order_status' => $row['order_status'],
                'order_floor' => $row['order_floor'],
                'order_table' => $row['order_table'],
                'total_product_finished' => "",
                'total_product_notyet' => "",
                'order_check_time' => $row['order_check_time'],
            );

            $sql_total_product_finished = "SELECT `id` FROM `tbl_order_detail`
                                            WHERE `id_order` = '{$row['id_order']}'
                                            AND `detail_status` != 'N'
                                            ";
            $total_product_finished = count(db_fetch_array($sql_total_product_finished));
            $order_item['total_product_finished'] = strval($total_product_finished);

            $order_item['total_product_notyet'] = strval($total_product_notyet);
            array_push($order_arr['data'], $order_item);
        }

    }

    reJson($order_arr);
} else {
    returnSuccess("Danh sách trống");
}
