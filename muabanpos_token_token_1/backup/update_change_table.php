<?php
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




$success = array();
$sql = "SELECT `order_table` FROM `tbl_order_order` WHERE `id` = '{$id_order}'";
$result = db_qr($sql);
$nums = db_nums($result);
if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $order_table = $row['order_table'];
    }

    $sql = "UPDATE `tbl_organization_table` SET
                                    `table_status` = 'empty'  -- update table empty
                                    WHERE `table_title` = '{$order_table}'
                            ";
    if (db_qr($sql)) {
        $success['table_status'] = "true"; ///
    }
}


if (isset($_REQUEST['order_table']) && !empty($_REQUEST['order_table'])) {
    $order_table = $_REQUEST['order_table'];
    $sql = "UPDATE `tbl_order_order` SET";
    $sql .= " `order_table` = '{$order_table}'";
    $sql .= " WHERE `id` = '{$id_order}'";

    if (mysqli_query($conn, $sql)) {
        $success['order_table'] = "true";
    }

    $sql = "SELECT `order_table` FROM `tbl_order_order` WHERE `id` = '{$id_order}'";
    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums > 0) {
        while ($row = db_assoc($result)) {
            $order_table = $row['order_table'];
        }

        $sql = "UPDATE `tbl_organization_table` SET
                                    `table_status` = 'full'  -- update table full
                                    WHERE `table_title` = '{$order_table}'
                            ";
        if (db_qr($sql)) {
            $success['table_status'] = "true"; ///
        }
    }
}

if (!empty($success)) {
    returnSuccess("Chuyển bàn thành công");
}
