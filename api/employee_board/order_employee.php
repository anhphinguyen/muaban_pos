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

if (isset($type_manager)) {
    switch ($type_manager) {

        case "delete_detail": {
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

                $sql = "SELECT * FROM `tbl_order_order` WHERE `order_status` > '1' AND `id` = '{$id_order}'";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    returnError("Đơn đã chuyển trạng thái, không thể hủy món");
                }

                $sql = "DELETE FROM `tbl_order_detail` WHERE `id` = '{$id_detail}'";
                if (db_qr($sql)) {
                    returnSuccess("Xóa thành công");
                } else {
                    returnError("Xóa thất bại");
                }
                break;
            }
        case "update_detail": {



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

                if (isset($_REQUEST['detail_quantity'])) {
                    if ($_REQUEST['detail_quantity'] == '') {
                        unset($_REQUEST['detail_quantity']);
                    } else {
                        $detail_quantity = $_REQUEST['detail_quantity'];
                        $sql = "UPDATE `tbl_order_detail`
                                SET `detail_quantity` = '{$detail_quantity}'
                                WHERE `id` = '{$id_detail}'";
                        if (db_qr($sql)) {
                            $success['detail_quantity'] = "true";
                        }
                    }
                }

                if (!empty($success)) {
                    returnSuccess("Tạo đơn hàng thành công");
                } else {
                    returnError("Tao don hang khong thanh cong");
                }
                break;
            }
            // case "update_detail_status": { // chef
            //         include_once "chef_update_status.php";
            //         break;
            //     }
        case "update_change_table": {


                if (isset($_REQUEST['id_table_before'])) {
                    if ($_REQUEST['id_table_before'] == '') {
                        unset($_REQUEST['id_table_before']);
                        returnError("Nhập id_table_before");
                    } else {
                        $id_table_before = $_REQUEST['id_table_before'];
                    }
                } else {
                    returnError("Nhập id_table_before");
                }

                $sql = "SELECT `table_status` FROM `tbl_organization_table`
                        WHERE `id` = '{$id_table_before}' 
                        AND `table_status` = 'empty'
                        ";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if($nums > 0){
                    returnError("Bàn đang không có đơn");
                }

                if (isset($_REQUEST['id_table_after'])) {
                    if ($_REQUEST['id_table_after'] == '') {
                        unset($_REQUEST['id_table_after']);
                        returnError("Nhập id_table_after");
                    } else {
                        $id_table_after = $_REQUEST['id_table_after'];
                    }
                } else {
                    returnError("Nhập id_table_after");
                }

                if (isset($_REQUEST['table_before'])) {
                    if ($_REQUEST['table_before'] == '') {
                        unset($_REQUEST['table_before']);
                        returnError("Nhập table_before");
                    } else {
                        $table_before = $_REQUEST['table_before'];
                    }
                } else {
                    returnError("Nhập table_before");
                }


                if (isset($_REQUEST['table_after'])) {
                    if ($_REQUEST['table_after'] == '') {
                        unset($_REQUEST['table_after']);
                        returnError("Nhập table_after");
                    } else {
                        $table_after = $_REQUEST['table_after'];
                    }
                } else {
                    returnError("Nhập table_after");
                }

                if (isset($_REQUEST['floor_after'])) {
                    if ($_REQUEST['floor_after'] == '') {
                        unset($_REQUEST['floor_after']);
                        returnError("Nhập floor_after");
                    } else {
                        $floor_after = $_REQUEST['floor_after'];
                    }
                } else {
                    returnError("Nhập floor_after");
                }

                if (isset($_REQUEST['floor_before'])) {
                    if ($_REQUEST['floor_before'] == '') {
                        unset($_REQUEST['floor_before']);
                        returnError("Nhập floor_before");
                    } else {
                        $floor_before = $_REQUEST['floor_before'];
                    }
                } else {
                    returnError("Nhập floor_before");
                }

                $success = array();
                $sql = "UPDATE `tbl_organization_table` 
                        SET `table_status` = 'empty'
                        WHERE `id` = '{$id_table_before}'
                        ";
                if (db_qr($sql)) {
                    $success['close_table'] = 'true';
                }

                $sql = "UPDATE `tbl_organization_table` 
                        SET `table_status` = 'full'
                        WHERE `id` = '{$id_table_after}'
                        ";
                if (db_qr($sql)) {
                    $success['open_table'] = 'true';
                }



                $sql = "SELECT `id` FROM `tbl_order_order` 
                        WHERE `order_table` = '{$table_before}'
                        AND `order_floor` = '{$floor_before}'";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    while ($row = db_assoc($result)) {
                        $id_order = $row['id'];
                    }
                } else {
                    returnError("Danh sách trống");
                }
                $sql = "UPDATE `tbl_order_order`
                        SET `order_floor` = '{$floor_after}',
                            `order_table` = '{$table_after}'
                        WHERE `id` = '{$id_order}'
                        ";

                if (db_qr($sql)) {
                    $success['update_order'] = "true";
                }

                if (!empty($success)) {
                    $message = array(
                            'success' => "true",
                            'message' => "Chuyển bàn thành công",
                            'data' => array()
                        );
                    $data = array(
                        'id_order' => $id_order,
                    );
                    array_push($message['data'], $data);
                    reJson($message);
                }
                break;
            }
            // case "update_order_status": {
            //         include_once "update_order_status.php";
            //         break;
            //     }
        case "update_add_product": {

                if (isset($_REQUEST['id_business'])) {
                    if ($_REQUEST['id_business'] == '') {
                        unset($_REQUEST['id_business']);
                        returnError("Nhập id_business");
                    } else {
                        $id_business = $_REQUEST['id_business'];
                    }
                } else {
                    returnError("Nhập id_business");
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

                if (isset($_REQUEST['element'])) {
                    if ($_REQUEST['element'] == '') {
                        unset($_REQUEST['element']);
                        returnError("Nhập element");
                    } else {
                        $element  = $_REQUEST['element'];
                    }
                } else {
                    returnError("Nhập element");
                }

                if (isset($_REQUEST['order_comment'])) {
                    if ($_REQUEST['order_comment'] == '') {
                        unset($_REQUEST['order_comment']);
                    } else {
                        $order_comment  = $_REQUEST['order_comment'];
                        $sql = "UPDATE `tbl_order_order`
                                SET `order_comment` = '{$order_comment}'
                                WHERE `id` = '{$id_order}'";
                        if (db_qr($sql)) {
                            $success['order_comment'] = "true";
                        }
                    }
                }

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
                                        SET `id_order` = '{$id_order}',
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
                                    $success['order_add'] = "true";
                                }
                            } else {
                                returnError("Lỗi dữ liệu");
                            }
                        }
                    }
                }
                if (!empty($success)) {
                    $sql = "SELECT * FROM `tbl_order_detail` 
                                    WHERE `id_order` = '{$id_order}'
                                    ORDER BY `id` ASC";
                    $result = db_qr($sql);
                    $nums = db_nums($result);
                    $detail_arr = array();
                    if ($nums > 0) {
                        $detail_arr['success'] = 'true';
                        $detail_arr['data'] = array();
                        while ($row = db_assoc($result)) {
                            $detail_item = array(
                                'id' => $row['id'],
                                'id_order' => $row['id_order'],
                                'id_product' => $row['id_product'],
                                'detail_quantity' => $row['detail_quantity'],
                            );
                            array_push($detail_arr['data'], $detail_item);
                        }
                        reJson($detail_arr);
                    }
                    // returnSuccess("Tạo đơn hàng thành công");
                } else {
                    returnError("Tao don hang khong thanh cong");
                }
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

                if (empty($error)) {
                    $success = array();

                    // $sql = "SELECT * FROM `tbl_organization_table` 
                    //         WHERE `id` = '{$id_table}' 
                    //         ";
                    // $result = db_qr($sql);
                    // $nums = db_nums($result);

                    // if ($nums > 0) {
                    //     while ($row = db_assoc($result)) {
                    //         if ($row['table_status'] == 'full') {
                    //             returnError("Bàn này đã có order");
                    //         }
                    //         // $id_floor = $row['id_floor'];
                    //     }
                    // }else{
                    //     returnError("Không tồn tại bàn");
                    // }

                    $sql = "SELECT * FROM `tbl_organization_floor` 
                            WHERE `id` = '{$id_floor}' 
                            ";
                    $result = db_qr($sql);
                    $nums = db_nums($result);
                    if ($nums > 0) {
                        while ($row = db_assoc($result)) {
                            if ($row['floor_type'] == 'carry-out') {
                                returnError("Đây là loại tầng mang đi");
                            }
                        }
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
                                `order_floor` = '{$order_floor}',
                                `order_table` = '{$order_table}',
                                `order_status` = '1',
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
                            $sql = "SELECT * FROM `tbl_order_detail` WHERE `id_order` = '{$id_insert}'";
                            $result = db_qr($sql);
                            $nums = db_nums($result);
                            $detail_arr = array();
                            if ($nums > 0) {
                                $detail_arr['success'] = 'true';
                                $detail_arr['data'] = array();
                                while ($row = db_assoc($result)) {
                                    $detail_item = array(
                                        'id' => $row['id'],
                                        'id_order' => $row['id_order'],
                                        'id_product' => $row['id_product'],
                                        'detail_quantity' => $row['detail_quantity'],
                                    );
                                    array_push($detail_arr['data'], $detail_item);
                                }
                                reJson($detail_arr);
                            }
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
}
