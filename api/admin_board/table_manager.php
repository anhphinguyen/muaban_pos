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
    case 'delete':{ 
        // if (isset($_REQUEST['id_table'])) {
        //     if ($_REQUEST['id_table'] == '') {
        //         unset($_REQUEST['id_table']);
        //         returnError("Truyền vào id_table");
        //     } else {
        //         $id_table  = $_REQUEST['id_table'];
        //     }
        // } else {
        //     returnError("Truyền vào id_table");
        // }


        // $sql = "DELETE FROM `tbl_organization_table` WHERE `id` = '{$id_table}'";
        // if(db_qr($sql)){
        //     returnSuccess("Xóa thành công");
        // }
        // break;
    }
    case 'update': {
            if (isset($_REQUEST['id_table'])) {
                if ($_REQUEST['id_table'] == '') {
                    unset($_REQUEST['id_table']);
                    returnError("Truyền vào id_table");
                } else {
                    $id_table  = $_REQUEST['id_table'];
                }
            } else {
                returnError("Truyền vào id_table");
            }

            $success = array();

            if(isset($_REQUEST['table_title']) && !empty($_REQUEST['table_title'])){
                $table_title = $_REQUEST['table_title'];
                $sql = "UPDATE `tbl_organization_table` SET
                        `table_title` = '{$table_title}'
                        WHERE `id` = '{$id_table}'
                        ";
                if(db_qr($sql)){
                    $success['table_title'] = 'true';
                }
            }

            if(!empty($success)){
                returnSuccess("Cập nhật thành công");
            }else{
                returnSuccess("Không có thông tin cập nhật");
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

            if (isset($_REQUEST['id_floor'])) {
                if ($_REQUEST['id_floor'] == '') {
                    unset($_REQUEST['id_floor']);
                    $error['id_floor'] = "Truyền vào id_floor";
                } else {
                    $id_floor  = $_REQUEST['id_floor'];
                }
            } else {
                $error['id_floor'] = "Truyền vào id_floor";
            }
            

            if (isset($_REQUEST['table_title'])) {
                if ($_REQUEST['table_title'] == '') {
                    unset($_REQUEST['table_title']);
                    $error['table_title'] = "Nhập table_title";
                } else {
                    $table_title  = $_REQUEST['table_title'];
                }
            } else {
                $error['table_title'] = "Nhập table_title";
            }


            if (empty($error)) {
                $sql = "SELECT * FROM `tbl_organization_table` 
                        WHERE `id_business` = '{$id_business}'
                        AND `table_title` = '{$table_title}'
                        AND `id_floor` = '{$id_floor}'
                        ";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    returnSuccess("Đã tồn tại bàn này");
                }

                $sql = "INSERT INTO `tbl_organization_table`
                        SET 
                        `id_business` = '{$id_business}',
                        `id_floor` = '{$id_floor}',
                        `table_title` = '{$table_title}'
                        ";
                if (db_qr($sql)) {
                    returnSuccess("Tạo mới bàn thành công");
                } else {
                    returnError("Tạo mới thất bại");
                }
            } else {
                $error['success'] = 'false';
                reJson($error);
            }
            break;
        }
    case 'list_table_title': {
            if(isset($_REQUEST['id_floor'])){
                if($_REQUEST['id_floor'] == ''){
                    unset($_REQUEST['id_floor']);
                    returnError("Nhập id_floor");
                }else{
                    $id_floor = $_REQUEST['id_floor'];
                }
            }else{
                returnError("Nhập id_floor");
            }

            $sql = "SELECT * FROM `tbl_organization_table` WHERE `id_floor` = '{$id_floor}'";
            $result = db_qr($sql);
            $nums = db_nums($result);
            $table_arr = array();
            if ($nums > 0) {
                $table_arr['success'] = "true";
                $table_arr['data'] = array();
                while ($row = db_assoc($result)) {
                    $table_item = array(
                        'id' => $row['id'],
                        'id_floor' => $row['id_floor'],
                        'table_title' => $row['table_title']
                    );

                    array_push($table_arr['data'], $table_item);
                }
                reJson($table_arr);
            } else {
                returnSuccess("Danh sách trống");
            }
            break;
        }
    default: {
            returnError("Khong ton tai type_manager");
            break;
        }
}