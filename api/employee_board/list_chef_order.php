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

$sql_total = "SELECT
        `tbl_organization_floor`.`floor_type` as `order_type`,

        `tbl_order_order`.`id` as `id_order`,
        `tbl_order_order`.`order_floor` as `order_floor`,
        `tbl_order_order`.`order_table` as `order_table`,
        `tbl_order_order`.`order_status` as `order_status`,
        `tbl_order_order`.`order_check_time` as `order_check_time`,

        `tbl_order_detail`.`detail_status` as `detail_status`
        FROM `tbl_order_order`
        LEFT JOIN `tbl_organization_floor` ON `tbl_order_order`.`order_floor` = `tbl_organization_floor`.`floor_title`
        LEFT JOIN `tbl_order_detail` ON `tbl_order_order`.`id` = `tbl_order_detail`.`id_order`
        WHERE `tbl_order_order`.`order_status` < '4' 
        AND `tbl_order_detail`.`detail_status` = 'N'
        GROUP BY `tbl_order_order`.`id` 
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

if (isset($_REQUEST['id_order'])) {
    if ($_REQUEST['id_order'] == '') {
        unset($_REQUEST['id_order']);
    } else {
        $id_order = $_REQUEST['id_order'];
        $sql .= " AND `tbl_order_order`.`id` = '{$id_order}'";
    }
}



$sql_total = "SELECT
        `tbl_organization_floor`.`floor_type` as `order_type`,

        `tbl_order_order`.`id` as `id_order`,
        `tbl_order_order`.`order_floor` as `order_floor`,
        `tbl_order_order`.`order_table` as `order_table`,
        `tbl_order_order`.`order_status` as `order_status`,
        `tbl_order_order`.`order_check_time` as `order_check_time`,

        `tbl_order_detail`.`detail_status` as `detail_status`
        FROM `tbl_order_order`
        LEFT JOIN `tbl_organization_floor` ON `tbl_order_order`.`order_floor` = `tbl_organization_floor`.`floor_title`
        LEFT JOIN `tbl_order_detail` ON `tbl_order_order`.`id` = `tbl_order_detail`.`id_order`
        WHERE `tbl_order_order`.`order_status` < '4' 
        AND `tbl_order_order`.`id_business` = '{$id_business}'
        AND `tbl_order_detail`.`detail_status` = 'N'
        GROUP BY `tbl_order_order`.`id` 
        ";
$total = count(db_fetch_array($sql_total));

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

// $total = count(db_fetch_array($sql));

$sql .= "ORDER BY `tbl_order_order`.`order_status` DESC";


$result = db_qr($sql);
$nums = db_nums($result);
$order_arr = array();
if ($nums > 0) {
    $order_arr['success'] = 'true';
    $order_arr['total'] = strval($total);
    $order_arr['data'] = array();

    while ($row = db_assoc($result)) {
        $sql_total_product_notyet = "SELECT `id` FROM `tbl_order_detail`
                                        WHERE `id_order` = '{$row['id_order']}'
                                        AND `detail_status` = 'N'
                                        ";
        $total_product_notyet = count(db_fetch_array($sql_total_product_notyet));
        if ($total_product_notyet > 0) {

            $sql_id_floor = "SELECT `id` FROM `tbl_organization_floor` 
                             WHERE `floor_title` = '{$row['order_floor']}'
                             AND `id_business` = '{$id_business}'
                             ";
            $result_id_floor = db_qr($sql_id_floor);
            $nums_id_floor = db_nums($result_id_floor);
            if($nums_id_floor > 0){
                while($row_id_floor = db_assoc($result_id_floor)){
                    $id_floor = $row_id_floor['id'];
                }
            }

            $sql_id_table = "SELECT `id` FROM `tbl_organization_table` 
                             WHERE `table_title` = '{$row['order_table']}'
                             AND `id_floor` = '{$id_floor}'";
            $result_id_table = db_qr($sql_id_table);
            $nums_id_table = db_nums($result_id_table);
            if($nums_id_table > 0){
                while($row_id_table = db_assoc($result_id_table)){
                    $id_table = $row_id_table['id'];
                }
            }

            $order_item = array(
                'id_order' => $row['id_order'],
                'id_floor' => isset($id_floor)?$id_floor:"",
                'id_table' => isset($id_table)?$id_table:"",
                'order_type' => $row['order_type'],
                'order_status' => $row['order_status'],
                'order_location' => $row['order_floor'] . " - " . $row['order_table'],
                'table_title' => $row['order_table'],
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
    $order_arr['success'] = 'false';
    $order_arr['data'] = array();
    reJson($order_arr);
}
