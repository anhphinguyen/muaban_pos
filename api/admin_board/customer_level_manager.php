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
    case "delete":{
        if (isset($_REQUEST['id_level'])) {
            if ($_REQUEST['id_level'] == '') {
                unset($_REQUEST['id_level']);
                returnError("Nhập id_level");
            } else {
                $id_level = $_REQUEST['id_level'];
            }
        } else {
            returnError("Nhập id_level");
        }

        $sql = "DELETE FROM `tbl_customer_point` WHERE `id` = '{$id_level}'";
        if(db_qr($sql)){
            returnSuccess("Xoa thanh cong");
        }
        break;
    }
    case "update": {
            if (isset($_REQUEST['id_level'])) {
                if ($_REQUEST['id_level'] == '') {
                    unset($_REQUEST['id_level']);
                    returnError("Nhập id_level");
                } else {
                    $id_level = $_REQUEST['id_level'];
                }
            } else {
                returnError("Nhập id_level");
            }

            $success = array();
            if (isset($_REQUEST['customer_level']) && !empty($_REQUEST['customer_level'])) { //*
                $customer_level = htmlspecialchars($_REQUEST['customer_level']);
                $sql = "UPDATE `tbl_customer_point` SET";
                $sql .= " `customer_level` = '{$customer_level}'";
                $sql .= " WHERE `id` = '{$id_level}'";

                if (mysqli_query($conn, $sql)) {
                    $success['customer_level'] = "true";
                }
            }

            if (isset($_REQUEST['customer_point']) && !empty($_REQUEST['customer_point'])) { //*
                $customer_point = htmlspecialchars($_REQUEST['customer_point']);
                $sql = "UPDATE `tbl_customer_point` SET";
                $sql .= " `customer_point` = '{$customer_point}'";
                $sql .= " WHERE `id` = '{$id_level}'";

                if (mysqli_query($conn, $sql)) {
                    $success['customer_point'] = "true";
                }
            }

            if (isset($_REQUEST['customer_discount']) && !empty($_REQUEST['customer_discount'])) { //*
                $customer_discount = htmlspecialchars($_REQUEST['customer_discount']);
                $sql = "UPDATE `tbl_customer_point` SET";
                $sql .= " `customer_discount` = '{$customer_discount}'";
                $sql .= " WHERE `id` = '{$id_level}'";

                if (mysqli_query($conn, $sql)) {
                    $success['customer_discount'] = "true";
                }
            }

            if (isset($_REQUEST['customer_description']) && !empty($_REQUEST['customer_description'])) { //*
                $customer_description = htmlspecialchars($_REQUEST['customer_description']);
                $sql = "UPDATE `tbl_customer_point` SET";
                $sql .= " `customer_description` = '{$customer_description}'";
                $sql .= " WHERE `id` = '{$id_level}'";

                if (mysqli_query($conn, $sql)) {
                    $success['customer_description'] = "true";
                }
            }

            if(!empty($success)){
                returnSuccess("update thành công");
            }else{
                returnSuccess("Không có thông tin cập nhật");
            }
            break;
        }
    case "create": {
            $error = array();
            if (isset($_REQUEST['id_business'])) {
                if ($_REQUEST['id_business'] == '') {
                    unset($_REQUEST['id_business']);
                    $error['id_business'] = "Nhap id_business";
                } else {
                    $id_business = $_REQUEST['id_business'];
                }
            } else {
                $error['id_business'] = "Nhap id_business";
            }

            if (isset($_REQUEST['customer_level'])) {   //*
                if ($_REQUEST['customer_level'] == '') {
                    unset($_REQUEST['customer_level']);
                    $error['customer_level'] = "Nhap customer_level";
                } else {
                    $customer_level = htmlspecialchars($_REQUEST['customer_level']);
                }
            } else {
                $error['customer_level'] = "Nhap customer_level";
            }

            if (isset($_REQUEST['customer_point'])) {   //*
                if ($_REQUEST['customer_point'] == '') {
                    unset($_REQUEST['customer_point']);
                    $error['customer_point'] = "Nhap customer_point";
                } else {
                    $customer_point = htmlspecialchars($_REQUEST['customer_point']);
                }
            } else {
                $error['customer_point'] = "Nhap customer_point";
            }

            if (isset($_REQUEST['customer_discount'])) {   //*
                if ($_REQUEST['customer_discount'] == '') {
                    unset($_REQUEST['customer_discount']);
                    $error['customer_discount'] = "Nhap customer_discount";
                } else {
                    $customer_discount = htmlspecialchars($_REQUEST['customer_discount']);
                }
            } else {
                $error['customer_discount'] = "Nhap customer_discount";
            }

            if (isset($_REQUEST['customer_description'])) {   //*
                if ($_REQUEST['customer_description'] == '') {
                    unset($_REQUEST['customer_description']);
                } else {
                    $customer_description = htmlspecialchars($_REQUEST['customer_description']);
                }
            }

            if (empty($error)) {
                // check customer exist
                $sql = "SELECT * FROM `tbl_customer_point` 
                            WHERE convert(`customer_point`, decimal) = {$customer_point}";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    returnSuccess("Đã tồn tại loại cấp độ này");
                }

                $sql = "INSERT INTO `tbl_customer_point` SET 
                                                `id_business` = '{$id_business}',
                                                `customer_level` = '{$customer_level}',
                                                `customer_point` = '{$customer_point}',
                                                `customer_discount` = '{$customer_discount}'
                                                ";
                if (isset($customer_description) && !empty($customer_description)) {
                    $sql .= " ,`customer_description` = '{$customer_description}'";
                }


                if (mysqli_query($conn, $sql)) {
                    returnSuccess("Tạo cấp độ thành công");
                    // $id_insert = mysqli_insert_id($conn);

                    // $sql = "SELECT * FROM `tbl_customer_point` WHERE `id` = '{$id_insert}'";
                    // $result = db_qr($sql);
                    // $nums = db_nums($result);
                    // $level_arr = array();
                    // if ($nums > 0) {
                    //     $level_arr['success'] = 'true';
                    //     $level_arr['data'] = array();
                    //     while ($row = db_assoc($result)) {
                    //         $level_item = array(
                    //             'id' => $row['id'],
                    //             'id_business' => $row['id_business'],
                    //             'customer_level' => htmlspecialchars_decode($row['customer_level']),
                    //             'customer_point' => htmlspecialchars_decode($row['customer_point']),
                    //             'customer_discount' => htmlspecialchars_decode($row['customer_discount']),
                    //             'customer_description' => htmlspecialchars_decode($row['customer_description']),

                    //         );
                    //         array_push($level_arr['data'], $level_item);
                    //     }
                    //     reJson($level_arr);
                    // }
                } else {
                    returnError("Tạo cấp độ không thành công");
                }
            } else {
                returnError("Vui lòng điền đầy đủ thông tin");
            }
            break;
        }
    case "list_customer_by_level":{
        include_once "./viewlist_board/list_customer_by_level.php";
        break;
    }
    case "list_level": {
            include_once "./viewlist_board/list_customer_level.php";
            break;
        }
    default: {
            returnError("Khong ton tai type_manager");
            break;
        }
}
