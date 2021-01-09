<?php

$sql = "SELECT * FROM `tbl_customer_point` WHERE 1=1";

if (isset($_REQUEST['id_business'])) {
    if ($_REQUEST['id_business'] == '') {
        unset($_REQUEST['id_business']);
        returnError("Nhập id_business");
    } else {
        $id_business = $_REQUEST['id_business'];
        $sql .= " AND `id_business` = '{$id_business}'";
    }
} else {
    returnError("Nhập id_business");
}

$result = db_qr($sql);
$nums = db_nums($result);

// arrange level
$sql_level = "SELECT * FROM `tbl_customer_point` WHERE `id_business` = '{$id_business}'";
$result_level = db_qr($sql_level);
$nums_level = db_nums($result_level);
if ($nums_level) {
    $point_arr = array();
    $point_arr['id_level'] = array();
    $point_arr['point'] = array();
    $point_arr['level'] = array();
    while ($row_level = db_assoc($result_level)) {
        $id_level = $row_level['id'];
        $customer_point = $row_level['customer_point'];
        $customer_level = $row_level['customer_level'];

        array_push($point_arr['id_level'], $id_level);
        array_push($point_arr['point'], $customer_point);
        array_push($point_arr['level'], $customer_level);
    }
    $point_arr = arrange_position($point_arr['point'], $point_arr['level'], $point_arr['id_level']);
}
// end arrange level


$level_arr = array();
if ($nums > 0) {
    $level_arr['success'] = 'true';
    $level_arr['data'] = array();
    while ($row = db_assoc($result)) {
        $level_item = array(
            'id' => $row['id'],
            'id_business' => $row['id_business'],
            'customer_level' => $row['customer_level'],
            'customer_point' => $row['customer_point'],
            'customer_discount' => $row['customer_discount'],
            'customer_description' => $row['customer_description'],
            'customer_total' => ""
        );

        // customer_customer_point
        $id_level = $row['id'];
        /////////////////////////////////////////////////////////
        $sql_customer = get_list_customer_by_level($id_level,$id_business, $point_arr['point'], $point_arr['id_level']);
        ///////////////////////////////////////////////////////////////
        // end 
        $customer_total = count(db_fetch_array($sql_customer));
        $level_item['customer_total'] = strval($customer_total);

        array_push($level_arr['data'], $level_item);
    }

    reJson($level_arr);
} else {
    returnSuccess("Danh sách trống");
}
