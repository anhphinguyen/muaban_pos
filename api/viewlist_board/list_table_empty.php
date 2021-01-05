<?php

$sql = "SELECT * FROM  `tbl_organization_table` WHERE 1=1";

$error = array();
if (isset($_REQUEST['id_floor'])) {
    if ($_REQUEST['id_floor'] == '') {
        unset($_REQUEST['id_floor']);
        $error['id_floor'] = "Nhập id_floor";
    } else {
        $id_floor = $_REQUEST['id_floor'];
        $sql .= " AND `id_floor` = '{$id_floor}'";
    }
} else {
    $error['id_floor'] = "Nhập id_floor";
}

$sql .= " AND `table_status` = 'empty'";


// if (isset($_REQUEST['id_table'])) {
//     if ($_REQUEST['id_table'] == '') {
//         unset($_REQUEST['id_table']);
//         $error['id_table'] = "Nhập id_table";
//     } else {
//         $id_table = $_REQUEST['id_table'];
//         $sql .= " AND `id_table` = '{$id_table}'";
//     }
// } else {
//     $error['id_table'] = "Nhập id_table";
// }

if (empty($error)) {
    $table_arr = array();
    $table_arr['success'] = 'true';
    $table_arr['data'] = array();
    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums > 0) {
        while ($row = db_assoc($result)) {
            $table_item = array(
                'id' => $row['id'],
                'id_floor' => $row['id_floor'],
                'table_title' => $row['table_title'],
                'table_type' => $row['table_type'],
                'table_status' => $row['table_status'],
            );

            array_push($table_arr['data'], $table_item);
        }
        reJson($table_arr);
    } else {
        returnSuccess("Không tồn tại bàn");
    }
} else {
    $error['success'] = 'false';
    $error['message'] = 'Lấy danh sách bàn không thành công';
    reJson($error);
}
