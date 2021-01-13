<?php
$id_business = '';
$type_manager = '';

if (isset($_REQUEST['id_business']) && $_REQUEST['id_business'] != '') {
    $id_business = $_REQUEST['id_business'];

    if (isset($_REQUEST['type_manager']) && $_REQUEST['type_manager'] != '') {
        $type_manager = $_REQUEST['type_manager'];

        //chon type_mânger
        switch ($type_manager) {
            //list unit
            case "list_unit":
                $sql = "SELECT * FROM `tbl_product_unit`
                        WHERE tbl_product_unit.id_business = '$id_business'";

                $result = $conn->query($sql);
                // Get row count
                $num = mysqli_num_rows($result);

                $module_arr['success'] = 'true';
                $module_arr['data'] = array();
                if ($num > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $module_item = array(
                            'id' => $row['id'],
                            'unit_title' => $row['unit_title'],
                            'unit_symbol' => $row['unit']
                        );

                        // Push to "data"
                        array_push($module_arr['data'], $module_item);
                    }
                    echo json_encode($module_arr);
                } else {
                    returnError("Không tìm thấy module");
                }
                break;

            //update unit
            case "update_unit":

                $id_unit = '';
                $unit_title = '';
                $unit_symbol = '';

                if (isset($_REQUEST['id_unit']) && $_REQUEST['id_unit'] != ''){
                    $id_unit = $_REQUEST['id_unit'];
                }else{
                    returnError("Nhập id_unit");
                }
                if (isset($_REQUEST['unit_title']) && $_REQUEST['unit_title'] != ''){
                    $unit_title = $_REQUEST['unit_title'];
                }else{
                    returnError("Nhập unit_title");
                }
                if (isset($_REQUEST['unit_symbol']) && $_REQUEST['unit_symbol'] != ''){
                    $unit_symbol = $_REQUEST['unit_symbol'];
                }else{
                    returnError("Nhập unit_symbol");
                }

                $sql = "UPDATE tbl_product_unit SET ";
                if (!empty($unit_title)) {
                    $sql .= " unit_title = '" . $unit_title . "'";
                }
                if (!empty($unit_symbol)) {
                    $sql .= " ,unit = '" . $unit_symbol . "'";
                }

                $sql .= " WHERE id ='$id_unit'";


                if ($conn->query($sql)) {
                    returnSuccess("Cập nhật thành công!");
                } else {
                    returnError("Cập nhật không thành công!");
                }

                break;

                // create unit
            case "create_unit":
                $unit_title = '';
                $unit_symbol = '';

                if (isset($_REQUEST['unit_title']) && $_REQUEST['unit_title'] != ''){
                    $unit_title = $_REQUEST['unit_title'];
                }else{
                    returnError("Nhập unit_title");
                }
                if (isset($_REQUEST['unit_symbol']) && $_REQUEST['unit_symbol'] != ''){
                    $unit_symbol = $_REQUEST['unit_symbol'];
                }else{
                    returnError("Nhập unit_symbol");
                }

                $sql = "INSERT INTO tbl_product_unit SET ";
                if (!empty($unit_title)) {
                    $sql .= " unit_title = '" . $unit_title . "'";
                }
                if (!empty($unit_symbol)) {
                    $sql .= " ,unit = '" . $unit_symbol . "'";
                }

                $sql .= ", id_business ='$id_business'";

                if ($conn->query($sql)) {
                    returnSuccess("Tạo mới thành công!");
                } else {
                    returnError("Tạo mới không thành công!");
                }

                break;

            case "delete_unit":

                $id_unit = '';
                if (isset($_REQUEST['id_unit']) && ! empty($_REQUEST['id_unit'])) {
                    $id_unit = $_REQUEST['id_unit'];
                } else {
                    returnError("Nhập id_unit!");
                }

                $sql_check_unit_exists = "SELECT *
                FROM tbl_product_unit
                WHERE id = '" . $id_unit . "'
             ";

                $result_check = mysqli_query($conn, $sql_check_unit_exists);
                $num_result_check = mysqli_num_rows($result_check);

                if ($num_result_check > 0) {

                    $sql_delete_product_unit = "
                            DELETE FROM tbl_product_unit
                            WHERE  id = '" . $id_unit . "'
                          ";
                    if ($conn->query($sql_delete_product_unit)) {
                        returnSuccess("Xóa đơn vị tính thành công!");
                    } else {
                        returnError("Xóa đơn vị tính không thành công!");
                    }
                } else {
                    returnError("Không tìm thấy đơn vị tính!");
                }

                break;
        }
    } else {
        returnError("Chọn type_manager");
    }
} else {
    returnError("Chọn cửa hàng");
}