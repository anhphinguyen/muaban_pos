<?php
if (isset($_REQUEST['id_business']) && $_REQUEST['id_business'] != '') {
    $id_business = $_REQUEST['id_business'];
    if (isset($_REQUEST['type_manager']) && $_REQUEST['type_manager'] != '') {
        $type_manager = $_REQUEST['type_manager'];

        //chon type_mânger
        switch ($type_manager) {
            //list category
            case "list_category":
                $sql = "SELECT * FROM `tbl_product_category`
                        WHERE tbl_product_category.id_business = '$id_business'";

                $result = $conn->query($sql);
                // Get row count
                $num = mysqli_num_rows($result);

                $module_arr['success'] = 'true';
                $module_arr['data'] = array();
                if ($num > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $module_item = array(
                            'id' => $row['id'],
                            'category_icon' => $row['category_icon'],
                            'category_title' => $row['category_title']
                        );

                        // Push to "data"
                        array_push($module_arr['data'], $module_item);
                    }
                    echo json_encode($module_arr);
                } else {
                    returnError("Không tìm thấy module");
                }
                break;

            //update category
            case "update_category":
                $id_category = '';
                $category_title = '';
                $check = 0;

                if (isset($_REQUEST['category_title']) && $_REQUEST['category_title'] != '') {
                    $category_title = $_REQUEST['category_title'];
                } else {
                    returnError("Nhập category_title");
                }

                if (isset($_REQUEST['id_category']) && $_REQUEST['id_category'] != '') {
                    $id_category = $_REQUEST['id_category'];
                } else {
                    returnError("Nhập id_category");
                }

                if (isset($_FILES['category_icon'])) {
                    $category_icon = saveImage($_FILES['category_icon'], 'images/product_category/');

                    if ($category_icon == "error_size_img") {
                        returnError("category_icon > 5MB !");
                    }

                    if ($category_icon == "error_type_img") {
                        returnError("category_icon error type");
                    }
                }
                //unset image
                $sql_tmp = "SELECT * FROM tbl_product_category WHERE id = '" . $id_category . "' ";

                $result_tmp = mysqli_query($conn, $sql_tmp);
                while ($row_tmp = $result_tmp->fetch_assoc()) {
                    $p_img = $row_tmp['category_icon'];
                    if (! empty($p_img) && file_exists('../' . $p_img)) {
                        unlink('../' . $p_img);
                    }
                }

                $sql = "UPDATE tbl_product_category SET ";
                if (!empty($category_title)) {
                    $sql .= " category_title = '" . $category_title . "'";
                }
                if (!empty($category_icon)) {
                    $sql .= ", category_icon = '" . $category_icon . "'";
                }

                $sql .= " WHERE id ='$id_category'";

                if ($conn->query($sql)) {
                    returnSuccess("Cập nhật thành công!");
                } else {
                    returnError("Cập nhật không thành công!");
                }

                break;

            case "create_category":

                $category_title = '';
                $check = 0;

                if (isset($_REQUEST['category_title']) && $_REQUEST['category_title'] != '') {
                    $category_title = $_REQUEST['category_title'];
                } else {
                    returnError("Nhập category_title");
                }

                if (isset($_FILES['category_icon'])) {
                    $category_icon = saveImage($_FILES['category_icon'], 'images/product_category/');

                    if ($category_icon == "error_size_img") {
                        returnError("category_icon > 5MB !");
                    }

                    if ($category_icon == "error_type_img") {
                        returnError("category_icon error type");
                    }
                }

                $sql = "INSERT INTO tbl_product_category SET ";

                if (!empty($category_title)) {
                    $sql .= " category_title = '" . $category_title . "'";
                }
                if (!empty($category_icon)) {
                    $sql .= ", category_icon = '" . $category_icon . "'";
                }
                $sql .= " ,id_business = '$id_business'";

                if ($conn->query($sql)) {
                    returnSuccess("Tạo mới thành công!");
                } else {
                    returnError("Tạo mới không thành công!");
                }

                break;

            case "delete_category":
                $id_category = '';

                if (isset($_REQUEST['id_category']) && $_REQUEST['id_category'] != '') {
                    $id_category = $_REQUEST['id_category'];
                } else {
                    returnError("Nhập id_category");
                }

                $sql_check_product_exists = "SELECT * FROM tbl_product_category WHERE id = '" . $id_category . "'";

                $result_check = mysqli_query($conn, $sql_check_product_exists);
                $num_result_check = mysqli_num_rows($result_check);

                if ($num_result_check > 0) {

                    $check_product_product = "SELECT *
                            FROM tbl_product_product
                            WHERE  id_category = '" . $id_category . "'
                          ";

                    $result_check_product = mysqli_query($conn, $check_product_product);
                    $num_result_check_product = mysqli_num_rows($result_check_product);
                    if ($num_result_check_product > 0) {
                        returnError("Danh mục không thể xóa, đã có sản phẩm!");
                    }

                    while ($rowItem = $result_check->fetch_assoc()) {
                        $category_icon = $rowItem['category_icon'];

                        if (file_exists('../' . $category_icon)) {
                            unlink('../' . $category_icon);
                        }
                    }

                    $sql_delete_category = "
                            DELETE FROM tbl_product_category
                            WHERE  id = '" . $id_category . "'
                          ";
                    if ($conn->query($sql_delete_category)) {
                        returnSuccess("Xóa danh mục thành công!");
                    } else {
                        returnError("Xóa danh mục không thành công!");
                    }
                }
                break;
        }

    }else{
        returnError("type_manager is missing");
    }
}else{
    returnError("Chọn cửa hàng");
}