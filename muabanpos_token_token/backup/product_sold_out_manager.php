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

    case "enable_product": {
            if (isset($_REQUEST['id_product'])) {
                if ($_REQUEST['id_product'] == '') {
                    unset($_REQUEST['id_product']);
                    returnError("Nhập id_product");
                } else {
                    $id_product = $_REQUEST['id_product'];
                }
            } else {
                returnError("Nhập id_product");
            }

            $sql = "UPDATE `tbl_product_product` 
                    SET `product_sold_out` = 'N'
                    WHERE `id` = '{$id_product}'
                    ";
            if(db_qr($sql)){
                returnSuccess("Đã hồi phục thành công");
            }else{
                returnError("Lỗi hồi phục");
            }
            break;
        }
    case "list_product_sold_out": {
            include_once "./viewlist_board/list_product_sold_out.php";
            break;
        }
    default: {
            returnError("Khong ton tai type_manager");
            break;
        }
}
