<?php

$sql = "SELECT * FROM  `tbl_organization_table` WHERE 1=1";

if (isset($_REQUEST['id_business'])) {
    if ($_REQUEST['id_business'] == '') {
        unset($_REQUEST['id_business']);
        returnSuccess("Nhập id_business");
    } else {
        $id_business = $_REQUEST['id_business'];
        $sql .= " AND `id_business` = '{$id_business}'";
    }
} else {
    returnSuccess("Nhập id_business");
}

if (isset($_REQUEST['id_floor'])) {
    if ($_REQUEST['id_floor'] == '') {
        unset($_REQUEST['id_floor']);
        returnSuccess("Nhập id_floor");
    } else {
        $id_floor = $_REQUEST['id_floor'];
        $sql .= " AND `id_floor` = '{$id_floor}'";
    }
} else {
    returnSuccess("Nhập id_floor");
}

if (isset($_REQUEST['id_table'])) {
    if ($_REQUEST['id_table'] == '') {
        unset($_REQUEST['id_table']);
    } else {
        $id_table = $_REQUEST['id_table'];
        $sql .= " AND `id` = '{$id_table}'";
    }
}

$sql .= " AND `table_status` = 'empty'";




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
            'table_status' => $row['table_status'],
        );

        array_push($table_arr['data'], $table_item);
    }
    reJson($table_arr);
} else {
    returnSuccess("Không tồn tại bàn");
}
