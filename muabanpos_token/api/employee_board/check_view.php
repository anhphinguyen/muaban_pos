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

switch($type_manager){
    case "view_one" :{
        if(isset($_REQUEST['id_detail'])){
            if($_REQUEST['id_detail'] == ''){
                unset($_REQUEST['id_detail']);
                returnError("Nhập id_detail");
            }else{
                $id_detail = $_REQUEST['id_detail'];             
            }
        }else{
            returnError("Nhập id_detail");
        }

        $sql = "UPDATE `tbl_order_detail` SET `detail_view` = 'Y' WHERE `id` = '{$id_detail}'";
        if(db_qr($sql)){
            returnSuccess("Đã xem");
        }
        break;

        break;
    }
    case "view_all":{
        if(isset($_REQUEST['id_business'])){
            if($_REQUEST['id_business'] == ''){
                unset($_REQUEST['id_business']);
                returnError("Nhập id_business");
            }else{
                $id_business = $_REQUEST['id_business'];             
            }
        }else{
            returnError("Nhập id_business");
        }

        $sql = "UPDATE `tbl_order_detail` SET `detail_view` = 'Y' WHERE `id_business` = '{$id_business}'";
        if(db_qr($sql)){
            returnSuccess("Đã xem");
        }
        break;
    }
    default:{
        returnError("Không tồn tại type_manager");
        break;
    }

}


?>