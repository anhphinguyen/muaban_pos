<?php


$success = array();
if (isset($_REQUEST['id_business']) && !empty($_REQUEST['id_business'])) {
    $id_business = $_REQUEST['id_business'];
} else {
    returnError("Nhập id_business");
}
if (isset($_REQUEST['id_product']) && !empty($_REQUEST['id_product'])) {
    $id_product = $_REQUEST['id_product'];
} else {
    returnError("Nhập id_product");
}

if (isset($_REQUEST['id_product_extra']) && !empty($_REQUEST['id_product_extra'])) {
    $id_product_extra = $_REQUEST['id_product_extra'];
}

$sql = "SELECT id_order, id FROM tbl_order_detail
         WHERE id_product = '$id_product' ";

if (isset($id_product_extra) && !empty($id_product_extra)) {
    $sql .= "AND detail_extra = '$id_product_extra' ";
}

$id_order_arr = array();
$id_detail_arr = array();
$result = db_qr($sql);
if (db_nums($result) > 0) {
    while ($row = db_assoc($result)) {
        $id_order_sql = $row['id_order'];
        $id_detail_sql = $row['id'];
        array_push($id_order_arr, $id_order_sql);
        array_push($id_detail_arr, $id_detail_sql);
    }
}

///
for ($i = 0; $i < count($id_order_arr); $i++) {
    $sql = "SELECT * FROM `tbl_order_order`
                        WHERE `id` = '{$id_order_arr[$i]}' 
                        AND `order_status` = '2' "; // processing -> delivery
    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums > 0) {
        while ($row = db_assoc($result)) {
            $time_processing = $row['order_check_time'];
            $time_delivery = time();
            $denta_processing = date('00:' . 'i:s', $time_delivery - $time_processing);


        }

        $sql_order_log = "INSERT INTO `tbl_order_log`
                         SET `id_order` = '{$id_order_arr[$i]}',
                             `log_status` = 'processing',
                             `time_log` = '{$denta_processing}',
                             `id_business` = '{$id_business}'
                           ";
        if (db_qr($sql_order_log)) {
            $success['order_log'] = "true";
        }

        $sql_update_order_status = "UPDATE `tbl_order_order` 
                                                SET `order_status` = '3',
                                                    `order_check_time` = '{$time_delivery}'
                                                WHERE `id` = '{$id_order_arr[$i]}'    
                                                ";
        if (db_qr($sql_update_order_status)) {
            $success['update_order_status'] = "true";
        }
    }


    $sql = "UPDATE `tbl_order_detail` SET
                    `detail_status` = 'Y'
                    WHERE `id` = '{$id_detail_arr[$i]}'
            ";
    if (db_qr($sql)) {
        $success['finised'] = "true";
    }
}



if (!empty($success)) {
    ///push notify
    $title = "Thông báo món ăn!!!";
    $bodyMessage = "Bạn ơi! Đã có món ăn hoàn thành. Bạn vui lòng kiểm tra đơn hàng nhé!!!";
    $action = "dish_finished_all";
    $type_send = 'topic';
    $to = 'order_notifycation_finished_all_' . strval($id_business);
    pushNotification($title, $bodyMessage, $action, $to, $type_send);
    /// end

    returnSuccess("Cập nhật trạng thái delivery thành công", $token);
} else {
    returnError("Cập nhật thất bại");
}
