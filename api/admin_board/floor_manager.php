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
    case 'delete': {
            if (isset($_REQUEST['id_floor'])) {
                if ($_REQUEST['id_floor'] == '') {
                    unset($_REQUEST['id_floor']);
                    returnError("Truyền vào id_floor");
                } else {
                    $id_floor  = $_REQUEST['id_floor'];
                }
            } else {
                returnError("Truyền vào id_floor");
            }

            $sql = "SELECT * FROM `tbl_organization_table` WHERE `id_floor` = '{$id_floor}'";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                returnError("Vẫn còn bàn trong tầng");
            }

            $sql = "DELETE FROM `tbl_organization_floor` WHERE `id` = '{$id_floor}'";
            if (db_qr($sql)) {
                returnSuccess("Xóa thành công", $token);
            }
            break;
        }
    case 'update': {
            if (isset($_REQUEST['id_floor'])) {
                if ($_REQUEST['id_floor'] == '') {
                    unset($_REQUEST['id_floor']);
                    returnError("Truyền vào id_floor");
                } else {
                    $id_floor  = $_REQUEST['id_floor'];
                }
            } else {
                returnError("Truyền vào id_floor");
            }
            
            $sql = "SELECT `id_business` FROM `tbl_organization_floor` WHERE `id` = '{$id_floor}'";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                while ($row = db_assoc($result)) {
                    $id_business = $row['id_business'];
                }
            }

            $success = array();
            if (isset($_REQUEST['floor_priority']) && !empty($_REQUEST['floor_priority'])) {
                $floor_priority = $_REQUEST['floor_priority'];

                $sql = "SELECT * FROM `tbl_organization_floor` 
                        WHERE `id_business` = '{$id_business}'
                        AND `id` != '{$id_floor}'
                        AND `floor_priority` = '{$floor_priority}'";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if($nums > 0){
                    returnError("Số thứ tự đã tồn tại");
                }
                
                $sql = "UPDATE `tbl_organization_floor` SET
                        `floor_priority` = '{$floor_priority}'
                        WHERE `id` = '{$id_floor}'
                        ";
                if (db_qr($sql)) {
                    $success['floor_priority'] = 'true';
                }
            }
            if (isset($_REQUEST['floor_title']) && !empty($_REQUEST['floor_title'])) {

                $floor_title = $_REQUEST['floor_title'];

                $sql = "SELECT * FROM `tbl_organization_floor` 
                        WHERE `id_business` = '{$id_business}'
                        AND `id` != '{$id_floor}'
                        AND `floor_title` = '{$floor_title}'";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if($nums > 0){
                    returnError("Tầng này đã tồn tại");
                }

                $sql = "UPDATE `tbl_organization_floor` SET
                        `floor_title` = '{$floor_title}'
                        WHERE `id` = '{$id_floor}'
                        ";
                if (db_qr($sql)) {
                    $success['floor_title'] = 'true';
                }
            }


            if (!empty($success)) {
                returnSuccess("Cập nhật thành công", $token);
            } else {
                returnSuccess("Không có thông tin cập nhật", $token);
            }

            break;
        }
    case 'create': {
            $error = array();

            if (isset($_REQUEST['id_business'])) {
                if ($_REQUEST['id_business'] == '') {
                    unset($_REQUEST['id_business']);
                    $error['id_business'] = "Truyền vào id_business";
                } else {
                    $id_business  = $_REQUEST['id_business'];
                }
            } else {
                $error['id_business'] = "Truyền vào id_business";
            }

            if (isset($_REQUEST['floor_priority'])) {
                if ($_REQUEST['floor_priority'] == '') {
                    unset($_REQUEST['floor_priority']);
                    $error['floor_priority'] = "Nhập floor_priority";
                } else {
                    $floor_priority  = $_REQUEST['floor_priority'];
                }
            } else {
                $error['floor_priority'] = "Nhập floor_priority";
            }

            if (isset($_REQUEST['floor_title'])) {
                if ($_REQUEST['floor_title'] == '') {
                    unset($_REQUEST['floor_title']);
                    $error['floor_title'] = "Nhập floor_title";
                } else {
                    $floor_title  = $_REQUEST['floor_title'];  // htmlspecialchars
                }
            } else {
                $error['floor_title'] = "Nhập floor_title";
            }

            if (isset($_REQUEST['floor_type'])) {
                if ($_REQUEST['floor_type'] == '') {
                    unset($_REQUEST['floor_type']);
                    $error['floor_type'] = "Nhập floor_type";
                } else {
                    $floor_type  = $_REQUEST['floor_type'];
                }
            } else {
                $error['floor_type'] = "Nhập floor_type";
            }


            if (empty($error)) {
                $sql = "SELECT * FROM `tbl_organization_floor` 
                        WHERE `id_business` = '{$id_business}'
                        -- AND `floor_priority` = '{$floor_priority}'
                        -- OR `floor_title` = '{$floor_title}'
                        ";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    while($row = db_assoc($result)){
                        if($row['floor_title'] == $floor_title){
                            returnError("Đã tồn tại tầng này");
                        }
                        if($row['floor_priority'] == $floor_priority){
                            returnError("Đã tồn tại STT này");
                        }
                    }
                }

                $sql = "INSERT INTO `tbl_organization_floor`
                        SET 
                        `id_business` = '{$id_business}',
                        `floor_priority` = '{$floor_priority}',
                        `floor_type` = '{$floor_type}',
                        `floor_title` = '{$floor_title}'
                        ";
                if (db_qr($sql)) {
                    returnSuccess("Tạo mới tầng thành công", $token);
                } else {
                    returnError("Tạo mới thất bại");
                }
            } else {
                $error['success'] = 'false';
                reJson($error);
            }
            break;
        }
    case 'list_floor_title': {
            if (isset($_REQUEST['id_business'])) {
                if ($_REQUEST['id_business'] == '') {
                    unset($_REQUEST['id_business']);
                    returnError("Truyền vào id_business");
                } else {
                    $id_business  = $_REQUEST['id_business'];
                }
            } else {
                returnError("Truyền vào id_business");
            }
            $sql = "SELECT * FROM `tbl_organization_floor` WHERE `id_business` = '{$id_business}'";
            $result = db_qr($sql);
            $nums = db_nums($result);
            $floor_arr = array();
            if ($nums > 0) {
                $floor_arr['success'] = "true";
                $floor_arr['refresh_token'] = $token;

                $floor_arr['data'] = array();
                while ($row = db_assoc($result)) {
                    $floor_item = array(
                        'id' => $row['id'],
                        'floor_title' => $row['floor_title'],
                        'floor_priority' => $row['floor_priority'],
                        'floor_type' => $row['floor_type'],
                    );

                    array_push($floor_arr['data'], $floor_item);
                }
                reJson($floor_arr);
            } else {
                returnSuccess("Danh sách trống",$token);
            }
            break;
        }
    default: {
            returnError("Khong ton tai type_manager");
            break;
        }
}
