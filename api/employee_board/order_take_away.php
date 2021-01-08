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

    case "cancel": {
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
    case "close_table": {
            if (isset($_REQUEST['id_table'])) {
                if ($_REQUEST['id_table'] == '') {
                    unset($_REQUEST['id_table']);
                    returnError("Nhập id_table");
                } else {
                    $id_table = $_REQUEST['id_table'];
                }
            } else {
                returnError("Nhập id_table");
            }

            $sql_update_table_status = "UPDATE `tbl_organization_table`
                                            SET `table_status` = 'empty'
                                            WHERE `id` = '{$id_table}'
                                            ";
            if (db_qr($sql_update_table_status)) {
                returnSuccess("Đóng bàn thành công");
            }
           
        }
    case "finished": {
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

            // $sql = "SELECT * FROM `tbl_order_detail` 
            //             WHERE `id` = '{$id_detail}'
            //             AND `detail_status` = 'Y'
            //             ";
            // $result = db_qr($sql);
            // $nums = db_nums($result);
            // if($nums > 0){
            //     returnError("Món này đã hoàn thành");
            // }
            $sql = "UPDATE `tbl_order_detail` 
                        SET `detail_status` = 'Y'
                        WHERE `id` = '{$id_detail}'
                        ";
            if (db_qr($sql)) {
                returnSuccess("Đã làm xong món");
            }
            break;
        }
    case "finished_all": {
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
             AND `order_status` = '2' "; // processing -> finished
            $result = db_qr($sql);
            $nums = db_nums($result);

            if ($nums > 0) {
                while ($row = db_assoc($result)) {
                    $time_processing = $row['order_check_time'];
                    $time_finished = time();
                    $denta_processing = date('00:' . 'i:s', $time_finished - $time_processing);

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
                                            SET `order_status` = '5',
                                                `order_check_time` = '{$time_finished}'
                                            WHERE `id` = '{$id_order}'    
                                            ";
                if (db_qr($sql_update_order_status)) {
                    $success['update_order_status'] = "true";
                }

                $sql = "UPDATE `tbl_order_detail` 
                        SET `detail_status` = 'Y'
                        WHERE `id_order` = '{$id_order}'
                        AND `detail_status` = 'N'
                        ";
                if (db_qr($sql)) {
                    $success['finised'] = "true";
                }

            } else {
                returnSuccess("Đã qua trạng thái thanh toán");
            }


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
                    $sql_update_customer_point = "UPDATE `tbl_customer_customer` 
                    SET `customer_point` = '{$update_customer_point}'
                    WHERE `id` = '{$id_customer}' 
                    ";

                    if (db_qr($sql_update_customer_point)) {
                        $success['customer_point'] = "true";
                    }
                }
                //add poit customer here
            }

            // end update point

            if (!empty($success)) {
                returnSuccess("Cập nhật trạng thái finished thành công");
            } else {
                returnError("Cập nhật thất bại");
            }

            break;
        }

    case "processing": {

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
            } else {
                returnSuccess("Đã qua trạng thái chờ");
            }

            break;
        }
    case "create": {
            $error = array();
            if (isset($_REQUEST['id_business'])) {
                if ($_REQUEST['id_business'] == '') {
                    unset($_REQUEST['id_business']);
                    $error['id_business'] = "Nhập id_business";
                } else {
                    $id_business = $_REQUEST['id_business'];
                }
            } else {
                $error['id_business'] = "Nhập id_business";
            }

            if (isset($_REQUEST['id_account'])) {
                if ($_REQUEST['id_account'] == '') {
                    unset($_REQUEST['id_account']);
                    $error['id_account'] = "Nhập id_account";
                } else {
                    $id_account = $_REQUEST['id_account'];
                }
            } else {
                $error['id_account'] = "Nhập id_account";
            }

            if (isset($_REQUEST['id_customer'])) {
                if ($_REQUEST['id_customer'] == '') {
                    unset($_REQUEST['id_customer']);
                    $id_customer = 0;
                } else {
                    $id_customer = $_REQUEST['id_customer'];
                }
            } else {
                $id_customer = 0;
            }

            if (isset($_REQUEST['id_floor'])) {
                if ($_REQUEST['id_floor'] == '') {
                    unset($_REQUEST['id_floor']);
                    $error['id_floor'] = "Nhập id_floor";
                } else {
                    $id_floor = $_REQUEST['id_floor'];
                }
            } else {
                $error['id_floor'] = "Nhập id_floor";
            }

            if (isset($_REQUEST['id_table'])) {
                if ($_REQUEST['id_table'] == '') {
                    unset($_REQUEST['id_table']);
                    $error['id_table'] = "Nhập id_table";
                } else {
                    $id_table = $_REQUEST['id_table'];
                }
            } else {
                $error['id_table'] = "Nhập id_table";
            }


            if (isset($_REQUEST['order_floor'])) {
                if ($_REQUEST['order_floor'] == '') {
                    unset($_REQUEST['order_floor']);
                    $error['order_floor'] = "Nhập order_floor";
                } else {
                    $order_floor = $_REQUEST['order_floor'];
                }
            } else {
                $error['order_floor'] = "Nhập order_floor";
            }

            if (isset($_REQUEST['order_table'])) {
                if ($_REQUEST['order_table'] == '') {
                    unset($_REQUEST['order_table']);
                    $error['order_table'] = "Nhập order_table";
                } else {
                    $order_table = $_REQUEST['order_table'];
                }
            } else {
                $error['order_table'] = "Nhập order_table";
            }


            if (isset($_REQUEST['order_comment'])) {
                if ($_REQUEST['order_comment'] == '') {
                    unset($_REQUEST['order_comment']);
                } else {
                    $order_comment = $_REQUEST['order_comment'];
                }
            }

            if (isset($_REQUEST['order_direct_discount'])) {
                if ($_REQUEST['order_direct_discount'] == '') {
                    unset($_REQUEST['order_direct_discount']);
                    $order_direct_discount = "0";
                } else {
                    $order_direct_discount = $_REQUEST['order_direct_discount'];
                }
            } else {
                $order_direct_discount = "0";
            }

            if (isset($_REQUEST['order_total_cost'])) {
                if ($_REQUEST['order_total_cost'] == '') {
                    unset($_REQUEST['order_total_cost']);
                    $error['order_total_cost'] = "Nhập order_total_cost";
                } else {
                    $order_total_cost = $_REQUEST['order_total_cost'];
                }
            } else {
                $error['order_total_cost'] = "Nhập order_total_cost";
            }

            if (isset($_REQUEST['element'])) {
                if ($_REQUEST['element'] == '') {
                    unset($_REQUEST['element']);
                    $error['element'] = "Truyền vào element";
                } else {
                    $element  = $_REQUEST['element'];
                }
            } else {
                $error['element'] = "Truyền vào element";
            }

            if (empty($errorr)) {
                $success = array();

                $sql = "SELECT * FROM `tbl_organization_floor` 
                            WHERE `id` = '{$id_floor}' 
                            ";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    while ($row = db_assoc($result)) {
                        if ($row['floor_type'] == 'eat-in') {
                            returnError("Đây không phải tầng mang đi");
                        }
                    }
                }

                $sql = "SELECT * FROM `tbl_organization_table` 
                            WHERE `id` = '{$id_table}' 
                            ";
                $result = db_qr($sql);
                $nums = db_nums($result);

                if ($nums > 0) {
                    while ($row = db_assoc($result)) {
                        if ($row['table_status'] == 'full') {
                            returnError("Bàn này đã có order");
                        }
                    }
                } else {
                    returnError("Không tồn tại bàn");
                }

                $sql = "SELECT `tbl_business_store`.`store_prefix` 
                    FROM `tbl_business_store` 
                    WHERE `id` = '{$id_business}'";


                $result = db_qr($sql);
                $nums = db_nums($result);
                $store_prefix = "";
                if ($nums > 0) {
                    while ($row = db_assoc($result)) {
                        $store_prefix = $row['store_prefix'];
                    }
                }

                $order_code = $store_prefix . "SO" . substr(time(), -8);

                $sql = "INSERT INTO `tbl_order_order` 
                    SET `id_business` = '{$id_business}', 
                        `id_account` = '{$id_account}',
                        `id_customer` = '{$id_customer}',
                        `order_code` = '{$order_code}',
                        `order_table` = '{$order_table}',
                        `order_floor` = '{$order_floor}',
                        `order_status` = '1',
                        `order_direct_discount` = '{$order_direct_discount}',
                        `order_total_cost` = '{$order_total_cost}',
                        `order_check_time` = '" . time() . "'
                        ";
                if (isset($order_comment) && !empty($order_comment)) {
                    $sql .= ", `order_comment` = '{$order_comment}'";
                }
                if (db_qr($sql)) {
                    $id_insert = mysqli_insert_id($conn);

                    if (isset($element) && !empty($element)) {
                        $element_convert_arr = explode("|", $element);
                        for ($i = 0; $i < count($element_convert_arr); $i++) {
                            if (!empty($element_convert_arr[$i])) {

                                $element_item = explode("-", $element_convert_arr[$i]);

                                if (!empty($element_item)) {
                                    $id_product = $element_item[0];
                                    $qty_product = $element_item[1];
                                    $extra_product = substr($element_item[2], 0, -1);
                                    $detail_cost_product = $element_item[3];

                                    $sql = "INSERT INTO `tbl_order_detail`
                                            SET `id_order` = '{$id_insert}',
                                                `id_product` = '{$id_product}',
                                                `detail_quantity` = '{$qty_product}',
                                                `detail_cost` = '{$detail_cost_product}',
                                                `id_business` = '{$id_business}',
                                                `detail_status` = 'N'       
                                                ";
                                    if ($extra_product != 'N') {
                                        $sql .=  ", `detail_extra` = '{$extra_product}'";
                                    }
                                    if (db_qr($sql)) {
                                        $sql_update_table_status = "UPDATE `tbl_organization_table` 
                                                            SET `table_status` = 'full'
                                                            WHERE `id` = '{$id_table}'";

                                        // khởi tạo thời gian mở bàn tại đây (nếu có khởi tạo)
                                        if (mysqli_query($conn, $sql_update_table_status)) {
                                            $success['order'] = "true";
                                        }
                                    }
                                } else {
                                    returnError("Lỗi dữ liệu");
                                }
                            }
                        }
                    }
                    if (!empty($success)) {
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////

                        $sql = "SELECT 
                                `tbl_customer_customer`.`id` as `id_customer`,
                                `tbl_customer_customer`.`customer_code` as `customer_code`,
                    
                                `tbl_account_account`.`id` as `id_account`,
                                `tbl_account_account`.`account_username` as `account_username`,
                    
                                `tbl_order_order`.`id` as `id_order`,

                                `tbl_order_order`.`order_code` as `order_code`,
                                `tbl_order_order`.`order_status` as `order_status`,
                                `tbl_order_order`.`order_floor` as `order_floor`,
                                `tbl_order_order`.`order_table` as `order_table`,
                                `tbl_order_order`.`order_direct_discount` as `order_direct_discount`,
                                `tbl_order_order`.`order_created` as `order_created`,
                                `tbl_order_order`.`order_comment` as `order_comment`,
                                `tbl_order_order`.`order_total_cost` as `order_total_cost`
                                FROM `tbl_order_order`
                                LEFT JOIN `tbl_customer_customer` ON `tbl_customer_customer`.`id`= `tbl_order_order`.`id_customer`
                                LEFT JOIN `tbl_account_account` ON `tbl_account_account`.`id`= `tbl_order_order`.`id_account`
                                WHERE `tbl_order_order`.`id` = '{$id_insert}'";


                        $order_arr = array();
                        $order_arr['success'] = 'true';
                        $order_arr['data'] = array();
                        $result = db_qr($sql);
                        $nums = db_nums($result);
                        // returnSuccess($nums);
                        if ($nums > 0) {
                            while ($row = db_assoc($result)) {
                                $order_item = array(
                                    'id_order' => $row['id_order'],
                                    // 'id_floor' => $row['id_floor'],
                                    // 'id_table' => $row['id_table'],
                                    'id_customer' => "", //$row['id_customer']
                                    'customer_code' => "", //$row['customer_code']
                                    'id_account' => $row['id_account'],
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
                                    // 'order_total_cost' => $row['order_total_cost'],
                                    'order_detail' => array()
                                );

                                if ($row['id_customer'] > 0) {
                                    $order_item['id_customer'] = $row['id_customer'];
                                    $order_item['customer_code'] = $row['customer_code'];
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
                                                    WHERE `id_order` = '{$id_insert}'
                                                    ORDER BY `tbl_order_detail`.`detail_status` ASC
                                                    ";
                                $total_order_detail = count(db_fetch_array($sql_order_detail));
                                $order_item['total_product'] = strval($total_order_detail);

                                $order_item['order_detail'] = array();

                                $result_detail = db_qr($sql_order_detail);
                                $nums_detail = db_nums($result_detail);
                                if ($nums_detail > 0) {
                                    $total_cost_tmp = 0;
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
                                        $total_cost_tmp += $row_detail['detail_cost'] * $row_detail['detail_quantity'];


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
                                        // $total_cost_tmp *= $row_detail['detail_quantity'];

                                        $order_detail['product_extra'] = $product_extra;
                                        array_push($order_item['order_detail'], $order_detail);
                                    }
                                    $order_item['total_cost_tmp'] = strval($total_cost_tmp);
                                }
                                array_push($order_arr['data'], $order_item);
                            }
                            reJson($order_arr);
                        } else {
                            returnSuccess("Danh sách trống");
                        }
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////
                        // returnSuccess("Tạo đơn hàng thành công");
                    } else {
                        returnError("Tao don hang khong thanh cong");
                    }
                } else {
                    returnError("insert khong thanh cong");
                }
            } else {
                reJson($error);
            }

            break;
        }

    default: {
            returnError("Không tồn tại type_manager");
            break;
        }
}
