<?php
// lay chuôi order_location
// $sql = "SELECT * FROM `tbl_order_order`";
// $result = db_qr($sql);
// $nums = db_nums($result);
// if($nums > 0){
//     $order_arr = array();
//     $order_arr['floor'] = array();
//     $order_arr['table'] = array();
//     while($row = db_assoc($result)){
//         $table_title = substr($row['order_location'], -6);
//         $floor_title = substr($row['order_location'], 0, 8);
//         array_push($order_arr['floor'], $floor_title);
//         array_push($order_arr['table'], $table_title);
//     }
//     reJson($order_arr);
// }


$sql = "SELECT *
                FROM  `tbl_organization_table`
                WHERE 1=1";


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

$table_arr = array();

if (empty($error)) {
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
        returnError("Không tồn tại bàn");
    }
} else {
    $error['success'] = 'false';
    $error['message'] = 'Lấy danh sách bàn không thành công';
    reJson($error);
}
