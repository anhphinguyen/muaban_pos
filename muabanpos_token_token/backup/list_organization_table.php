<?php



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

$total = count(db_fetch_array($sql));
$limit = 20;
$page = 1;

if (isset($_REQUEST['limit']) && !empty($_REQUEST['limit'])) {
    $limit = $_REQUEST['limit'];
}
if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
    $page = $_REQUEST['page'];
}


$total_page = ceil($total / $limit);
$start = ($page - 1) * $limit;
$sql .= " ORDER BY `tbl_organization_table`.`id` ASC LIMIT {$start},{$limit}";


if (empty($error)) {
    $table_arr['success'] = 'true';
    $table_arr['success'] = 'true';
    $table_arr['total'] = strval($total);
    $table_arr['total_page'] = strval($total_page);
    $table_arr['limit'] = strval($limit);
    $table_arr['page'] = strval($page);
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
