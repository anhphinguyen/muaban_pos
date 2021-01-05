<?php
$id_business = '';
$type_manager = '';

if (isset($_REQUEST['id_business']) && $_REQUEST['id_business'] != '') {
    $id_business = $_REQUEST['id_business'];

    if (isset($_REQUEST['type_manager']) && $_REQUEST['type_manager'] != '') {
        $type_manager = $_REQUEST['type_manager'];

        //chon type_mânger
        switch ($type_manager) {
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
                if (isset($_REQUEST['id_module']) && $_REQUEST['id_module'] != ''){
                    $id_module = $_REQUEST['id_module'];
                }else{
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
                $id_type= '';
                if (isset($_REQUEST['description'])) {
                    if ($_REQUEST['description'] == '') {
                        unset($_REQUEST['description']);
                    } else {
                        $description = $_REQUEST['description'];
                    }
                }
                if (isset($_REQUEST['id_type']) && $_REQUEST['id_type'] != ''){
                    $id_type = $_REQUEST['id_type'];
                }else{
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
        }
    } else {
        returnError("Chọn type_manager");
    }
} else {
    returnError("Chọn cửa hàng");
}