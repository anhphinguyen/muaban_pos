<?php

if (isset($_REQUEST['type_status'])) {
    if ($_REQUEST['type_status'] == '') {
        unset($_REQUEST['type_status']);
        returnError("Nhập type_status");
    } else {
        $type_status = $_REQUEST['type_status'];
    }
} else {
    returnError("Nhập type_status");
}

switch ($type_status) {
    case 'open': { // phục hồi món đã hết hàng
            if (isset($_REQUEST['id_product'])) {
                if ($_REQUEST['id_product'] == '') {
                    unset($_REQUEST['id_product']);
                    returnError("Nhập id_product");
                } else {
                    $id_product = $_REQUEST['id_product'];
                }
            } else {
                returnError("Nhập id_product");
            }

            $sql = "UPDATE `tbl_product_product` SET `product_sold_out` = 'N' WHERE `id` = '{$id_product}'";
            if (db_qr($sql)) {
                returnSuccess("Phục hồi món thành công");
            } else {
                returnError("Món đã được phục hồi");
            }
            break;
        }
    case 'list_product_sold_out': {
            $sql = "SELECT * FROM `tbl_product_product` WHERE `product_sold_out` = 'Y'";
            $result = db_qr($sql);
            $nums = db_nums($result);
            $product_sold_old_arr = array();
            if ($nums > 0) {
                $product_sold_old_arr['success'] = 'true';
                $product_sold_old_arr['data'] = array();
                while ($row = db_assoc($result)) {
                    $product_sold_old_item = array(
                        'id' => $row['id'],
                        'product_img' => $row['product_img'],
                        'product_title' => $row['product_title'],
                    );
                    array_push($product_sold_old_arr['data'], $product_sold_old_item);
                }

                reJson($product_sold_old_arr);
            } else {
                returnSuccess("Không có sản phẩm");
            }
            break;
        }
    case 'finished': {
            $success = array();
            if (isset($_REQUEST['id_order'])) {
                if ($_REQUEST['id_order'] == '') {
                    unset($_REQUEST['id_order']);
                    returnError("Nhập id_order");
                } else {
                    $id_order = $_REQUEST['id_order'];
                }
            } else {
                returnError("Nhập id_order");
            }
            if (isset($_REQUEST['id_detail'])) {
                if ($_REQUEST['id_detail'] == '') {
                    unset($_REQUEST['id_detail']);
                    returnError("Nhập id_detail");
                } else {
                    $id_detail = $_REQUEST['id_detail'];
                }
            } else {
                returnError("Nhập id_detail");
            }

            $sql = "UPDATE `tbl_order_detail` SET
                        `detail_status` = 'Y'
                        WHERE `id` = '{$id_detail}'
                ";
            if (db_qr($sql)) {
                $success['finised'] = "true";
            }

            $sql = "SELECT * FROM `tbl_order_order`
                         WHERE `id` = '{$id_order}' 
                         AND `order_status` = '2' "; // processing -> delivery
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                while ($row = db_assoc($result)) {
                    $time_processing = $row['order_check_time'];
                    $time_delivery = time();
                    $denta_processing = date('00:' . 'i:s', $time_delivery - $time_processing);

                    $id_business = $row['id_business'];
                }

                $sql_order_log = "INSERT INTO `tbl_order_log`
                                      SET `id_order` = '{$id_order}',
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
                                                WHERE `id` = '{$id_order}'    
                                                ";
                if (db_qr($sql_update_order_status)) {
                    $success['update_order_status'] = "true";
                }

                if (!empty($success)) {
                    returnSuccess("Cập nhật trạng thái delivery thành công");
                } else {
                    returnError("Cập nhật thất bại");
                }
            }
            //  else {
            //     returnSuccess("Đã qua trạng thái chế biến");
            // }
            break;
        }
    case 'cancel': {
            if (isset($_REQUEST['id_detail'])) {
                if ($_REQUEST['id_detail'] == '') {
                    unset($_REQUEST['id_detail']);
                    returnError("Nhập id_detail");
                } else {
                    $id_detail = $_REQUEST['id_detail'];
                }
            } else {
                returnError("Nhập id_detail");
            }

            $success = array();
            $sql = "SELECT `id_product` FROM `tbl_order_detail` WHERE `id` = '{$id_detail}'";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                while ($row = db_assoc($result)) {
                    $id_product = $row['id_product'];
                }
            }

            $sql = "UPDATE `tbl_order_detail` SET
                        `detail_status` = 'C'
                        WHERE `id` = '{$id_detail}'
                ";
            if (db_qr($sql)) {
                $success['detail_status'] = "true";
            }

            $sql = "UPDATE `tbl_product_product` SET
                        `product_sold_out` = 'Y'
                        WHERE `id` = '{$id_product}'
                ";
            if (db_qr($sql)) {
                $success['product_sold_out'] = "true";
            }

            if (!empty($success)) {
                returnSuccess("Hủy món thành công");
            }
            break;
        }
    default: {
            returnError("Không tồn tại type_manager");
            break;
        }
}
