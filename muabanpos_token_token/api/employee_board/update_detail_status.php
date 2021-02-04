<?php

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

            $sql = "UPDATE `tbl_product_product` SET `product_disable` = 'N' WHERE `id` = '{$id_product}'";
            if (db_qr($sql)) {
                returnSuccess("Phục hồi món thành công", $token);
            } else {
                returnError("Món đã được phục hồi");
            }
            break;
        }

    case 'finished_all': {
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
            }

            $sql = "UPDATE `tbl_order_detail` SET
                        `detail_status` = 'Y'
                        WHERE `id_order` = '{$id_order}'
                        AND `detail_status` = 'N'
                    ";
            if (db_qr($sql)) {
                $success['finised'] = "true";
            }

            if (!empty($success)) {

                $sql = "SELECT * FROM `tbl_order_order` WHERE `id` ='{$id_order}'";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    while ($row = db_assoc($result)) {
                        $order_floor = $row['order_floor'];
                        $order_table = $row['order_table'];
                    }
                }
                ///push notify
                $title = "Thông báo món ăn!!!";
                $bodyMessage = $order_floor . " - " . $order_table . " đã làm xong";
                $action = "dish_finished";
                $type_send = 'topic';
                $to = 'order_notifycation_' . strval($id_business);
                pushNotification($title, $bodyMessage, $action, $to, $type_send);
                /// end

                returnSuccess("Cập nhật trạng thái delivery thành công", $token);
            } else {
                returnSuccess("Đã hoàn thành món", $token);
            }
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
            }


            $sql = "UPDATE `tbl_order_detail` SET
                        `detail_status` = 'Y'
                        WHERE `id` = '{$id_detail}'
                ";
            if (db_qr($sql)) {
                $success['finised'] = "true";
            }

            if (!empty($success)) {

                $sql = "SELECT * FROM `tbl_order_order` WHERE `id` ='{$id_order}'";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    while ($row = db_assoc($result)) {
                        $order_floor = $row['order_floor'];
                        $order_table = $row['order_table'];
                    }
                }

                $sql = "SELECT
                                `tbl_product_product`.`product_title`
                                FROM `tbl_order_detail`
                                LEFT JOIN `tbl_product_product` ON `tbl_product_product`.`id` = `tbl_order_detail`.`id_product`
                                WHERE `tbl_order_detail`.`id` = '{$id_detail}'
                                ";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    while ($row = db_assoc($result)) {
                        $product_title = $row['product_title'];
                    }
                }
                ///push notify
                $title = "Thông báo món ăn!!!";
                $bodyMessage = $order_floor . " - " . $order_table . " - " . $product_title . " vừa hoàn thành";
                $action = "dish_finished";
                $type_send = 'topic';
                $to = 'order_notifycation_' . strval($id_business);
                pushNotification($title, $bodyMessage, $action, $to, $type_send);
                /// end

                returnSuccess("Cập nhật trạng thái delivery thành công", $token);
            } else {
                returnError("Cập nhật thất bại");
            }

            break;
        }
    case 'lock': {
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

            if (isset($_REQUEST['id_order'])) {
                if ($_REQUEST['id_order'] == '') {
                    unset($_REQUEST['id_order']);
                } else {
                    $id_order = $_REQUEST['id_order'];
                }
            }

            $success = array();
            $sql = "UPDATE `tbl_product_product` SET
                    `product_disable` = 'Y' 
                    WHERE `id` = '{$id_product}'
                    ";
            if (db_qr($sql)) {
                $success['disable_product'] = "true";
            }

            if (isset($id_order) && !empty($id_order)) {

                $sql = "UPDATE `tbl_order_detail` SET
                            `detail_status` = 'C' 
                            WHERE `id_product` = '{$id_product}'
                            AND `id_order` = '{$id_order}'
                            AND `detail_status` = 'N'
                            ";
                if (db_qr($sql)) {
                    $success['disable_product_order'] = "true";
                }
            }

            if (!empty($success)) {
                returnSuccess("Món ăn đã được khóa", $token);
            } else {
                returnSuccess("disable loi", $token);
            }
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

            $sql = "UPDATE `tbl_order_detail` SET
                        `detail_status` = 'C'
                        WHERE `id` = '{$id_detail}'
                ";
            if (db_qr($sql)) {
                $success['detail_status'] = "true";
            }


            if (!empty($success)) {
                returnSuccess("Hủy món thành công", $token);
            }
            break;
        }
    default: {
            returnError("Không tồn tại type_manager");
            break;
        }
}
