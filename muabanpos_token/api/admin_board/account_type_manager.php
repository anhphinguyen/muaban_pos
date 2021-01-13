<?php
$id_business = '';
$type_manager = '';

if (isset($_REQUEST['id_business']) && $_REQUEST['id_business'] != '') {
    $id_business = $_REQUEST['id_business'];

    if (isset($_REQUEST['type_manager']) && $_REQUEST['type_manager'] != '') {
        $type_manager = $_REQUEST['type_manager'];

        //chon type_mânger
        switch ($type_manager) {
                // check role
            case "check_role": {
                    if (isset($_REQUEST['id_user']) && !empty($_REQUEST['id_user'])) {
                        $id_user = $_REQUEST['id_user'];
                    } else {
                        returnError("Nhập id_user");
                    }

                    $sql = "SELECT 
                            `tbl_business_model`.`id` as `id_model`,
                            `tbl_business_model`.`business_model` as `business_model`,

                            `tbl_business_store`.`id` as `id_business`,
                            `tbl_business_store`.`store_code` as `store_code`,
                            `tbl_business_store`.`store_name` as `store_name`,
                            `tbl_business_store`.`store_phone` as `store_phone`,
                            `tbl_business_store`.`store_address` as `store_address`,
                            `tbl_business_store`.`store_prefix` as `store_prefix`,

                            `tbl_account_account`.`id` as `id_account`,
                            `tbl_account_account`.`id_type` as `id_type`,
                            `tbl_account_account`.`account_username` as `username`,
                            `tbl_account_account`.`account_password` as `password`,
                            `tbl_account_account`.`account_fullname` as `fullname`,
                            `tbl_account_account`.`account_email` as `email`,
                            `tbl_account_account`.`account_status` as `account_status`,
                            `tbl_account_account`.`force_sign_out` as `force_sign_out`,

                            `tbl_account_type`.`type_account` as `type_account`,
                            `tbl_account_type`.`description` as `type_description`
                            FROM `tbl_account_account`
                            LEFT JOIN `tbl_business_store` ON `tbl_business_store`.`id` = `tbl_account_account`.`id_business`
                            LEFT JOIN `tbl_account_type` ON `tbl_account_type`.`id` = `tbl_account_account`.`id_type`
                            LEFT JOIN `tbl_business_model` ON `tbl_business_model`.`id` = `tbl_business_store`.`id_business_model`
                            WHERE `tbl_account_account`.`id` = '{$id_user}' 
                            AND `tbl_account_account`.`id_business` = '{$id_business}'
                            ";
                    $result = db_qr($sql);
                    $nums = db_nums($result);
                    if ($nums > 0) {
                        $user_arr = array();
                        $user_arr['success'] = 'true';
                        $user_arr['data'] = array();
                        while ($row = db_assoc($result)) {
                
                            $user_item = array(
                                'id' => $row['id_account'],
                                'id_model' => $row['id_model'],
                                'business_model' => $row['business_model'],
                                'id_business' => $row['id_business'],
                                'store_name' => $row['store_name'],
                                'store_prefix' => $row['store_prefix'],
                                'store_phone' => $row['store_phone'],
                                'store_address' => $row['store_address'],
                                'id_type' => $row['id_type'],
                                'username' => $row['username'],
                                'fullname' => $row['fullname'],
                                'email' => $row['email'],
                                'account_status' => $row['account_status'],
                                'type_account' => $row['type_account'],
                                'type_description' => $row['type_description'],
                            );
                
                
                            if ($row['id_type'] == '1') {
                                $user_item['role_permission'] = getRolePermission($row['id_account']);
                            }
                
                            array_push($user_arr['data'], $user_item);
                        }
                        reJson($user_arr);
                    } else {
                        returnSuccess("Không tìm thấy user");
                    }
                }
                //list module
            case "list_module":
                $sql = "SELECT * FROM `tbl_account_permission`
                        WHERE tbl_account_permission.id_business = '$id_business'";

                $result = $conn->query($sql);
                // Get row count
                $num = mysqli_num_rows($result);

                $module_arr['success'] = 'true';
                $module_arr['data'] = array();
                if ($num > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $module_item = array(
                            'id' => $row['id'],
                            'permission' => $row['permission'],
                            'description' => $row['description']
                        );

                        // Push to "data"
                        array_push($module_arr['data'], $module_item);
                    }
                    echo json_encode($module_arr);
                } else {
                    returnError("Không tìm thấy module");
                }
                break;

                //update module
            case "update_module":
                $description = '';
                $id_module = '';
                if (isset($_REQUEST['description'])) {
                    if ($_REQUEST['description'] == '') {
                        unset($_REQUEST['description']);
                    } else {
                        $description = $_REQUEST['description'];
                    }
                }
                if (isset($_REQUEST['id_module']) && $_REQUEST['id_module'] != '') {
                    $id_module = $_REQUEST['id_module'];
                } else {
                    returnError("Nhập id_module");
                }
                $sql = "UPDATE tbl_account_permission SET ";
                if (!empty($description)) {
                    $sql .= " description = '" . $description . "'";
                }
                $sql .= " WHERE id ='$id_module'";

                if ($conn->query($sql)) {
                    returnSuccess("Cập nhật thành công!");
                } else {
                    returnError("Cập nhật không thành công!");
                }

                break;

                //list type account
            case "list_type":
                $sql = "SELECT * FROM `tbl_account_type`
                        WHERE tbl_account_type.id_business = '$id_business'";

                $result = $conn->query($sql);
                // Get row count
                $num = mysqli_num_rows($result);

                $module_arr['success'] = 'true';
                $module_arr['data'] = array();
                if ($num > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $module_item = array(
                            'id' => $row['id'],
                            'type_account' => $row['type_account'],
                            'description' => $row['description']
                        );

                        // Push to "data"
                        array_push($module_arr['data'], $module_item);
                    }
                    echo json_encode($module_arr);
                } else {
                    returnError("Không tìm thấy type");
                }
                break;

                //update module
            case "update_type":
                $description = '';
                $id_type = '';
                if (isset($_REQUEST['description'])) {
                    if ($_REQUEST['description'] == '') {
                        unset($_REQUEST['description']);
                    } else {
                        $description = $_REQUEST['description'];
                    }
                }
                if (isset($_REQUEST['id_type']) && $_REQUEST['id_type'] != '') {
                    $id_type = $_REQUEST['id_type'];
                } else {
                    returnError("Nhập id_type");
                }
                $sql = "UPDATE tbl_account_type SET ";
                if (!empty($description)) {
                    $sql .= " description = '" . $description . "'";
                }
                $sql .= " WHERE id ='$id_type'";

                if ($conn->query($sql)) {
                    returnSuccess("Cập nhật thành công!");
                } else {
                    returnError("Cập nhật không thành công!");
                }

                break;
            default:
                returnError("type_manager has been failed");
        }
    } else {
        returnError("Chọn type_manager");
    }
} else {
    returnError("Chọn cửa hàng");
}
