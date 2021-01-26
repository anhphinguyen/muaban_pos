<?php

// Thực Đơn theo từng cửa hàng
$sql = "SELECT *
                FROM  `tbl_product_category`
                WHERE 1=1";


$error = array();
if (isset($_REQUEST['id_business'])) {
    if ($_REQUEST['id_business'] == '') {
        unset($_REQUEST['id_business']);
        $error['id_business'] = "Nhập id_business";
    } else {
        $id_business = $_REQUEST['id_business'];
        $sql .= " AND `id_business` = '{$id_business}'";
    }
}else{
    $error['id_business'] = "Nhập id_business";
}
$category_arr = array();
if(empty($error)){
    $category_arr['success'] = "true";
    $category_arr['refresh_token'] = $token;

    $category_arr['data'] = array();
    $result = db_qr($sql);
    $nums = db_nums($result);
    if($nums > 0){
        while($row = db_assoc($result)){
            $category_item = array(
                'id' => $row['id'],
                'id_business' => $row['id_business'],
                'category_icon' => $row['category_icon'],
                'category_title' => $row['category_title'],
            );
            array_push($category_arr['data'], $category_item);
        }
        reJson($category_arr);
    }else{
        returnSuccess("Không tồn tại danh muc", $token);
    }

}else{
    $error['success'] = 'false';
    $error['message'] = 'Lấy danh mục món ăn không thành công';
    echo json_encode($error);
}
