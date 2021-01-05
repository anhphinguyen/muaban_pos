<?php

$sql = "SELECT * FROM `tbl_customer_point` WHERE 1=1";

$result = db_qr($sql);
$nums = db_nums($result);

$level_arr = array();
if($nums > 0){
    $level_arr['success'] = 'true';
    $level_arr['data'] = array();
    while($row = db_assoc($result)){
        $level_item = array(
            'id' => $row['id'],
            'id_business' => $row['id_business'],
            'customer_level' => $row['customer_level'],
            'customer_point' => $row['customer_point'],
            'customer_discount' => $row['customer_discount'],
            'customer_description' => $row['customer_description'],
        );
        array_push($level_arr['data'], $level_item);
    }

    reJson($level_arr);
}else{
    returnSuccess("Danh sách trống");
}