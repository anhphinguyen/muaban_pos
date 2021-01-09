<?php
$error = array();

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
        returnError("Nhập id_order");
    } else {
        $id_order = $_REQUEST['id_order'];
    }
} else {
    returnError("Nhập id_order");
}

switch ($type_manager) {
    case "cancel": {
            $sql = "SELECT `order_table` FROM `tbl_order_order` WHERE `id` = '{$id_order}'";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                while ($row = db_assoc($result)) {
                    $order_table = $row['order_table'];
                }
            } else {
                returnError("Không tìm thấy order");
            }


            $success = array();
            $sql = "UPDATE `tbl_order_order` 
                SET `order_status` = '6'
                WHERE `id` = '{$id_order}'
                ";
            if (db_qr($sql)) {
                $success['order_status'] = "true";
            }

            $sql = "UPDATE `tbl_order_detail` 
                SET `detail_status` = 'C'
                WHERE `id_order` = '{$id_order}'
                ";
            if (db_qr($sql)) {
                $success['detail_status'] = "true";
            }

            $sql = "UPDATE `tbl_organization_table`
                    SET `table_status` = 'empty'
                    WHERE `table_title` = '{$order_table}'
                    ";
            if (db_qr($sql)) {
                $success['table_status'] = "true";
            }

            if (!empty($success)) {
                returnSuccess("Hủy đơn hàng thành công");
            } else {
                returnError("Hủy đơn hàng thất bại");
            }
            break;
        }
    case "finished": {

            // check detail status
            // $sql = ""
            // add point customer
            $sql = " SELECT `id_customer` FROM `tbl_order_order`
                     WHERE `id` = '{$id_order}'
                    ";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                while ($row = db_assoc($result)) {
                    $id_customer = $row['id_customer'];
                }
            }
            if ($id_customer > 0) {
                $sql = " SELECT `customer_point` FROM `tbl_customer_customer`
                     WHERE `id` = '{$id_customer}'
                    ";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    while ($row = db_assoc($result)) {
                        $customer_point = $row['customer_point'];
                    }
                }

                $sql = "SELECT 
                    `id_product`,   
                    `detail_extra` 
                    FROM `tbl_order_detail` 
                    WHERE `id_order` = '{$id_order}'
                    AND `detail_status` = 'Y'
                    ";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    $id_str_tmp = "";
                    while ($row = db_assoc($result)) {
                        $id_str_tmp .= "," . $row['id_product'] . "," . $row['detail_extra'];
                    }
                    $id_str = substr($id_str_tmp, 1);
                }

                if (!empty($id_str)) {
                    $id_product_arr = explode(",", $id_str);
                    // reJson(count($id_product_arr));
                    $total_product_point = 0;
                    for ($i = 0; $i < count($id_product_arr); $i++) {

                        if (!empty($id_product_arr[$i])) {
                            $sql = "SELECT `product_point` 
                                FROM `tbl_product_product` 
                                WHERE `id` = '{$id_product_arr[$i]}'";
                            $result = db_qr($sql);
                            $nums = db_nums($result);
                            if ($nums > 0) {
                                while ($row = db_assoc($result)) {
                                    $total_product_point += $row['product_point'];
                                }
                            }
                        }
                    }
                    $update_customer_point = $customer_point + $total_product_point;
                    //add poit customer here
                    $sql_update_customer_point = "UPDATE `tbl_customer_customer` 
                                                    SET `customer_point` = '{$update_customer_point}'
                                                    WHERE `id` = '{$id_customer}' 
                                                    ";
                    if (db_qr($sql_update_customer_point)) {
                        $success['customer_point'] = "true";
                    }
                }
            }
            // end update point
            $sql = "SELECT `order_table` FROM `tbl_order_order` WHERE `id` = '{$id_order}'";  // use update table status
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                while ($row = db_assoc($result)) {
                    $order_table = $row['order_table'];   // order table
                }
            }

            $success = array();
            $sql = "SELECT * FROM `tbl_order_order`
                 WHERE `id` = '{$id_order}' 
                 AND `order_status` = '4' "; // payment -> finished
            $result = db_qr($sql);
            $nums = db_nums($result);

            if ($nums > 0) {
                while ($row = db_assoc($result)) {
                    $time_payment = $row['order_check_time'];
                    $time_finished = time();
                    $denta_payment = date('00:' . 'i:s', $time_finished - $time_payment);

                    $id_business = $row['id_business'];
                }

                $sql_order_log = "INSERT INTO `tbl_order_log`
                              SET `id_order` = '{$id_order}',
                                  `log_status` = 'payment',
                                  `time_log` = '{$denta_payment}',
                                  `id_business` = '{$id_business}'
                                ";
                if (db_qr($sql_order_log)) {
                    $success['order_log'] = "true";
                }

                $sql_update_order_status = "UPDATE `tbl_order_order` 
                                        SET `order_status` = '5',
                                            `order_check_time` = '{$time_finished}'
                                        WHERE `id` = '{$id_order}'    
                                        ";
                if (db_qr($sql_update_order_status)) {
                    $success['update_order_status'] = "true";
                }

                // //add poit customer here
                // $sql_update_customer_point = "UPDATE `tbl_customer_customer` 
                //         SET `customer_point` = '{$update_customer_point}'
                //         WHERE `id` = '{$id_customer}' 
                //         ";
                // if (db_qr($sql_update_customer_point)) {
                //     $success['customer_point'] = "true";
                // }


                $sql_update_table_status = "UPDATE `tbl_organization_table`
                                            SET `table_status` = 'empty'
                                            WHERE `table_title` = '{$order_table}'
                                            ";
                if (db_qr($sql_update_table_status)) {
                    $success['table_status'] = "true";
                }

                if (!empty($success)) {
                    returnSuccess("Cập nhật trạng thái finished thành công");
                } else {
                    returnError("Cập nhật thất bại");
                }
            } else {
                returnSuccess("Đã qua trạng thái thanh toán");
            }
            break;
        }
    case "payment": {
            $success = array();

            if (isset($_REQUEST['id_customer'])) {
                if ($_REQUEST['id_customer'] == '') {
                    unset($_REQUEST['id_customer']);
                } else {
                    $id_customer = $_REQUEST['id_customer'];
                    $sql = "UPDATE `tbl_order_order`
                            SET `id_customer` = '{$id_customer}'
                            WHERE `id` = '{$id_order}'";
                    if (db_qr($sql)) {
                        $success['id_customer'] = "true";
                    };
                }
            }

            if (isset($_REQUEST['order_direct_discount'])) {
                if ($_REQUEST['order_direct_discount'] == '') {
                    unset($_REQUEST['order_direct_discount']);
                } else {
                    $order_direct_discount = $_REQUEST['order_direct_discount'];
                    $sql = "UPDATE `tbl_order_order`
                            SET `order_direct_discount` = '{$order_direct_discount}'
                            WHERE `id` = '{$id_order}'";
                    if (db_qr($sql)) {
                        $success['order_direct_discount'] = "true";
                    };
                }
            }

            if (isset($_REQUEST['order_total_cost'])) {
                if ($_REQUEST['order_total_cost'] == '') {
                    unset($_REQUEST['order_total_cost']);
                } else {
                    $order_total_cost = $_REQUEST['order_total_cost'];
                    $sql = "UPDATE `tbl_order_order`
                            SET `order_total_cost` = '{$order_total_cost}'
                            WHERE `id` = '{$id_order}'";
                    if (db_qr($sql)) {
                        $success['order_total_cost'] = "true";
                    };
                }
            }

            
            $sql = "SELECT * FROM `tbl_order_order`
                     WHERE `id` = '{$id_order}' 
                     "; // delivery -> payment
            if(isset($_REQUEST['business_model'])){
                if ($_REQUEST['business_model'] == 'S') {
                    $sql .= " AND `order_status` = '1'";
                    //update detail status for small store
                    $sql_update_detail = "UPDATE `tbl_order_detail` SET
                                            `detail_status` = 'Y'
                                            WHERE `id_order` = '{$id_order}'
                                            AND `detail_status` = 'N'
                                            ";
                    db_qr($sql_update_detail);
                }else{
                    $sql .= " AND `order_status` = '3'";
                }
            }else{
                $sql .= " AND `order_status` = '3'";
            }
            


            $result = db_qr($sql);
            $nums = db_nums($result);

            if ($nums > 0) {
                while ($row = db_assoc($result)) {
                    $time_delivery = $row['order_check_time'];
                    $time_payment = time();
                    $denta_delivery = date('00:' . 'i:s', $time_payment - $time_delivery);

                    $id_business = $row['id_business'];
                }

                $sql_order_log = "INSERT INTO `tbl_order_log`
                                  SET `id_order` = '{$id_order}',
                                      `log_status` = 'delivery',
                                      `time_log` = '{$denta_delivery}',
                                      `id_business` = '{$id_business}'
                                    ";
                if (db_qr($sql_order_log)) {
                    $success['order_log'] = "true";
                }

                $sql_update_order_status = "UPDATE `tbl_order_order` 
                                            SET `order_status` = '4',
                                                `order_check_time` = '{$time_payment}'
                                            WHERE `id` = '{$id_order}'    
                                            ";
                if (db_qr($sql_update_order_status)) {
                    $success['update_order_status'] = "true";
                }

                if (!empty($success)) {
                    returnSuccess("Cập nhật trạng thái payment thành công");
                } else {
                    returnError("Cập nhật thất bại");
                }
            } else {
                returnSuccess("Đã qua trạng thái lên món");
            }
            break;
        }
    
    case "processing": {

            $success = array();
            $sql = "SELECT * FROM `tbl_order_order`
                         WHERE `id` = '{$id_order}' 
                         AND `order_status` = '1' "; // wait -> processing
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                while ($row = db_assoc($result)) {
                    $time_wait = $row['order_check_time'];
                    $time_processing = time();
                    $denta_wait = date('00:' . 'i:s', $time_processing - $time_wait);

                    $id_business = $row['id_business'];
                }

                $sql_order_log = "INSERT INTO `tbl_order_log`
                                      SET `id_order` = '{$id_order}',
                                          `log_status` = 'waiting',
                                          `time_log` = '{$denta_wait}',
                                          `id_business` = '{$id_business}'
                                        ";
                if (db_qr($sql_order_log)) {
                    $success['order_log'] = "true";
                }

                $sql_update_order_status = "UPDATE `tbl_order_order` 
                                                SET `order_status` = '2',
                                                    `order_check_time` = '{$time_processing}'
                                                WHERE `id` = '{$id_order}'    
                                                ";
                if (db_qr($sql_update_order_status)) {
                    $success['update_order_status'] = "true";
                }

                if (!empty($success)) {
                    returnSuccess("Cập nhật trạng thái processing thành công");
                } else {
                    returnError("Cập nhật thất bại");
                }
            }else {
                returnSuccess("Đã qua trạng thái chờ");
            }
            break;
        }
    default: {
            returnError("Khong ton tai type_manager");
            break;
        }
}
