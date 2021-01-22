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
    case "delete": {
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

            $success = array();

            $sql = "SELECT * FROM `tbl_order_detail` 
                    ";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if($nums > 0){
                while($row = db_assoc($result)){
                    if($row['id_product'] == $id_product){
                        returnError("Sản phẩm đã được bán, không thể xóa");
                    }
                    
                    if(!empty($row['detail_extra'])){
                        foreach(explode(",", $row['detail_extra']) as $detail_extra){
                            if($detail_extra == $id_product){
                                returnError("Sản phẩm đã được bán, không thể xóa");
                            }
                        }
                    }
                }
            }
            
            $sql = "SELECT `tbl_product_product`.`product_img` FROM `tbl_product_product` WHERE `id` = '{$id_product}'";
            $result = mysqli_query($conn, $sql);
            $nums = mysqli_num_rows($result);
            if ($nums > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $product_img =  $row['product_img'];
                }
                if (file_exists("../../" . $product_img)) {
                    @unlink("../../" . $product_img);
                }
            }
            $sql = "SELECT * FROM `tbl_order_order` 
                    WHERE `id_product` = '{$id_product}'
                    OR `detail_extra` LIKE '%{$id_product}%'
                    ";
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                returnError("Không thể xóa sản phẩm");
            }

            $sql = "DELETE FROM `tbl_product_extra` WHERE `id_product` = '{$id_product}'";
            if (db_qr($sql)) {
                $success['delete_extra'] = "true";
            }
            $sql = "DELETE FROM `tbl_product_extra` WHERE `id_product_extra` = '{$id_product}'";
            if (db_qr($sql)) {
                $success['delete_id_extra'] = "true";
            }
            $sql = "DELETE FROM `tbl_product_product` WHERE `id` = '{$id_product}'";
            if (db_qr($sql)) {
                $success['delete_product'] = "true";
            }

            if (!empty($success)) {
                returnSuccess("Xóa thành công", $token);
            } else {
                returnError("Xóa thất bại");
            }
            break;
        }
    case "update": {
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
            if (isset($_REQUEST['id_business'])) {
                if ($_REQUEST['id_business'] == '') {
                    unset($_REQUEST['id_business']);
                    returnError("Nhập id_business");
                } else {
                    $id_business = $_REQUEST['id_business'];
                }
            } else {
                returnError("Nhập id_business");
            }



            if (isset($_REQUEST['id_category']) && !empty($_REQUEST['id_category'])) {
                $id_category = $_REQUEST['id_category'];
                $sql = "UPDATE `tbl_product_product` 
                            SET `id_category` = '{$id_category}' 
                            WHERE `id` = '{$id_product}'";
                if (mysqli_query($conn, $sql)) {
                    $success['id_category'] = 'true';
                }
            }

            if (isset($_REQUEST['id_unit']) && !empty($_REQUEST['id_unit'])) {
                $id_unit = $_REQUEST['id_unit'];
                $sql = "UPDATE `tbl_product_product` 
                            SET `id_unit` = '{$id_unit}' 
                            WHERE `id` = '{$id_product}'";
                if (mysqli_query($conn, $sql)) {
                    $success['id_unit'] = 'true';
                }
            }

            if (isset($_REQUEST['product_title']) && !empty($_REQUEST['product_title'])) {
                $product_title = $_REQUEST['product_title'];
                $sql = "UPDATE `tbl_product_product` 
                            SET `product_title` = '{$product_title}' 
                            WHERE `id` = '{$id_product}'";
                if (mysqli_query($conn, $sql)) {
                    $success['product_title'] = 'true';
                }
            }

            if (isset($_REQUEST['product_code']) && !empty($_REQUEST['product_code'])) {
                $product_code = $_REQUEST['product_code'];
                $sql = "UPDATE `tbl_product_product` 
                            SET `product_code` = '{$product_code}' 
                            WHERE `id` = '{$id_product}'";
                if (mysqli_query($conn, $sql)) {
                    $success['product_code'] = 'true';
                }
            }

            if (isset($_REQUEST['product_sales_price']) && !empty($_REQUEST['product_sales_price'])) {
                $product_sales_price = $_REQUEST['product_sales_price'];
                $sql = "UPDATE `tbl_product_product` 
                            SET `product_sales_price` = '{$product_sales_price}' 
                            WHERE `id` = '{$id_product}'";
                if (mysqli_query($conn, $sql)) {
                    $success['product_sales_price'] = 'true';
                }
            }

            if (isset($_REQUEST['product_description']) && !empty($_REQUEST['product_description'])) {
                $product_description = $_REQUEST['product_description'];
                $sql = "UPDATE `tbl_product_product` 
                            SET `product_description` = '{$product_description}' 
                            WHERE `id` = '{$id_product}'";
                if (mysqli_query($conn, $sql)) {
                    $success['product_description'] = 'true';
                }
            }

            if (isset($_REQUEST['product_point']) && !empty($_REQUEST['product_point'])) {
                $product_point = $_REQUEST['product_point'];
                $sql = "UPDATE `tbl_product_product` 
                            SET `product_point` = '{$product_point}' 
                            WHERE `id` = '{$id_product}'";
                if (mysqli_query($conn, $sql)) {
                    $success['product_point'] = 'true';
                }
            }

            if (isset($_REQUEST['product_disable']) && !empty($_REQUEST['product_disable'])) {
                $product_disable = $_REQUEST['product_disable'];
                $sql = "UPDATE `tbl_product_product` 
                            SET `product_disable` = '{$product_disable}' 
                            WHERE `id` = '{$id_product}'";
                if (mysqli_query($conn, $sql)) {
                    $success['product_disable'] = 'true';
                }
            }

            if (isset($_REQUEST['product_sold_out']) && !empty($_REQUEST['product_sold_out'])) {
                $product_sold_out = $_REQUEST['product_sold_out'];
                $sql = "UPDATE `tbl_product_product` 
                            SET `product_sold_out` = '{$product_sold_out}' 
                            WHERE `id` = '{$id_product}'";
                if (mysqli_query($conn, $sql)) {
                    $success['product_sold_out'] = 'true';
                }
            }


            if (isset($_FILES['product_img'])) {
                $sql = "SELECT * FROM `tbl_product_product` WHERE `id` = '{$id_product}'";
                $result = mysqli_query($conn, $sql);
                $nums = mysqli_num_rows($result);
                if ($nums > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $product_img = $row['product_img'];
                        if (file_exists("../../" . $product_img)) {
                            @unlink("../../" . $product_img);
                        }
                    }
                }
                $product_img = 'product_img';
                $dir_save_product_img = "images/product_product/";
                $dir_save_thumb = handing_file_img($product_img, $dir_save_product_img);
                $sql = "UPDATE `tbl_product_product`
                    SET `product_img` = '{$dir_save_thumb}' 
                    WHERE `id` = '{$id_product}'";
                if (mysqli_query($conn, $sql)) {
                    $success['product_img'] = 'true';
                }
            }

            // extra

            if (isset($_REQUEST['id_product_extra']) && !empty($_REQUEST['id_product_extra'])) {
                $sql = "DELETE FROM `tbl_product_extra` WHERE `id_product` = '{$id_product}'";
                db_qr($sql);
                $id_product_extra = explode(",", $_REQUEST['id_product_extra']);
                if (!empty($id_product_extra)) {
                    foreach ($id_product_extra as $id_item) {
                        if (!empty($id_item)) {
                            $sql = "INSERT INTO `tbl_product_extra` SET
                                `id_product` = '{$id_product}',
                                `id_product_extra` = '{$id_item}',
                                `id_business` = '{$id_business}'
                                ";
                            if (db_qr($sql)) {
                                $success['product_img'] = 'true';
                            }
                        }
                    }
                }
            }

            // end extra

            if (!empty($success)) {
                returnSuccess("Cập nhật thông tin thành công", $token);
            } else {
                returnError("Không có thông tin cập nhật");
            }
            break;
        }
    case "create": {
            if (isset($_REQUEST['id_business'])) {
                if ($_REQUEST['id_business'] == '') {
                    unset($_REQUEST['id_business']);
                    returnError("Nhập id_business");
                } else {
                    $id_business = $_REQUEST['id_business'];
                }
            } else {
                returnError("Nhập id_business");
            }

            if (isset($_REQUEST['product_title'])) {
                if ($_REQUEST['product_title'] == '') {
                    unset($_REQUEST['product_title']);
                    returnError("Nhập product_title");
                } else {
                    $product_title = $_REQUEST['product_title'];
                }
            } else {
                returnError("Nhập product_title");
            }

            if (isset($_REQUEST['product_code'])) {
                if ($_REQUEST['product_code'] == '') {
                    unset($_REQUEST['product_code']);
                    returnError("Nhập product_code");
                } else {
                    $product_code = $_REQUEST['product_code'];
                }
            } else {
                returnError("Nhập product_code");
            }

            if (isset($_REQUEST['id_category'])) {
                if ($_REQUEST['id_category'] == '') {
                    unset($_REQUEST['id_category']);
                    returnError("Nhập id_category");
                } else {
                    $id_category = $_REQUEST['id_category'];
                }
            } else {
                returnError("Nhập id_category");
            }

            if (isset($_REQUEST['id_unit'])) {
                if ($_REQUEST['id_unit'] == '') {
                    unset($_REQUEST['id_unit']);
                    returnError("Nhập id_unit");
                } else {
                    $id_unit = $_REQUEST['id_unit'];
                }
            } else {
                returnError("Nhập id_unit");
            }

            if (isset($_REQUEST['product_sales_price'])) {
                if ($_REQUEST['product_sales_price'] == '') {
                    unset($_REQUEST['product_sales_price']);
                    returnError("Nhập product_sales_price");
                } else {
                    $product_sales_price = $_REQUEST['product_sales_price'];
                }
            } else {
                returnError("Nhập product_sales_price");
            }

            if (isset($_REQUEST['product_description'])) {
                if ($_REQUEST['product_description'] == '') {
                    unset($_REQUEST['product_description']);
                } else {
                    $product_description = $_REQUEST['product_description'];
                }
            }


            // $id_product_extra = '';
            if (isset($_REQUEST['id_product_extra'])) {
                if ($_REQUEST['id_product_extra'] == '') {
                    unset($_REQUEST['id_product_extra']);
                } else {
                    $id_extra_arr = explode(",", $_REQUEST['id_product_extra']);
                }
            }

            if (isset($_REQUEST['product_point'])) {
                if ($_REQUEST['product_point'] == '') {
                    unset($_REQUEST['product_point']);
                    $product_point = "0";
                } else {
                    $product_point = $_REQUEST['product_point'];
                }
            } else {
                $product_point = "0";
            }

            if (isset($_FILES['product_img'])) { // up product_img
                $product_img = 'product_img';
                $dir_save_product_img = "images/product_product/";
            } else {
                returnError("Nhập product_img");
            }


            // $sql = "SELECT * FROM `tbl_product_product` WHERE `product_code` = '{$}'";

            $dir_save_thumb = handing_file_img($product_img, $dir_save_product_img);
            $sql = "INSERT INTO `tbl_product_product` SET 
                            `id_category` = '{$id_category}',
                            `id_unit` = '{$id_unit}',
                            `id_business` = '{$id_business}',
                            `product_title` = '{$product_title}',
                            `product_code` = '{$product_code}',
                            `product_sales_price` = '{$product_sales_price}',
                            `product_img` = '{$dir_save_thumb}'";

            if (isset($product_point) && !empty($product_point)) {
                $sql .= " ,`product_point` = '{$product_point}'";
            }
            if (isset($product_description) && !empty($product_description)) {
                $sql .= " ,`product_description` = '{$product_description}'";
            }

            $success = array();
            if (db_qr($sql)) {
                $id_insert = mysqli_insert_id($conn);

                if (isset($id_extra_arr) && !empty($id_extra_arr)) {
                    foreach ($id_extra_arr as $id_extra) {
                        if (!empty($id_extra)) {
                            $sql_extra = "INSERT INTO `tbl_product_extra`
                                            SET `id_product` = '{$id_insert}',
                                                `id_product_extra` = '{$id_extra}',
                                                `id_business` = '{$id_business}'
                                        ";
                            db_qr($sql_extra);
                        }
                    }
                }


                returnSuccess("Tạo thành công", $token);
            } else {
                returnError("Tạo thất bại");
            }

            break;
        }
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
            if (db_qr($sql)) {
                returnSuccess("Đã hồi phục thành công", $token);
            } else {
                returnError("Lỗi hồi phục");
            }
            break;
        }
    case "list_product_sold_out": {
            include_once "./viewlist_board/list_product_sold_out.php";
            break;
        }
    case "list_product_category": {
            include_once "./viewlist_board/list_product_category.php";
            break;
        }
    case "list_product_extra": {
            $sql = "SELECT *
                    FROM  `tbl_product_product`
                    
                    WHERE 1=1
                    ";

            if (isset($_REQUEST['id_business'])) {
                if ($_REQUEST['id_business'] == '') {
                    unset($_REQUEST['id_business']);
                    returnError("Nhập id_business");
                } else {
                    $id_business = $_REQUEST['id_business'];
                    $sql .= " AND `id_business` = '{$id_business}'";

                    if (isset($_REQUEST['filter'])) {
                        if ($_REQUEST['filter'] == '') {
                            unset($_REQUEST['filter']);
                        } else {
                            $filter = $_REQUEST['filter'];
                            $sql .= " AND `tbl_product_product`.`product_title` LIKE '%{$filter}%'";
                        }
                    }
                }
            } else {
                returnError("Nhập id_business");
            }

            $product_arr = array();

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
            $sql .= " ORDER BY `id` DESC LIMIT {$start},{$limit}";


            if (empty($error)) {
                $product_arr['success'] = 'true';
                $product_arr['refresh_token'] = $token;

                $product_arr['total'] = strval($total);
                $product_arr['total_page'] = strval($total_page);
                $product_arr['limit'] = strval($limit);
                $product_arr['page'] = strval($page);
                $product_arr['data'] = array();
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    while ($row = db_assoc($result)) {
                        $product_item = array(
                            'id' => $row['id'],
                            'product_title' => $row['product_title'],
                            'product_extra' => array()
                        );

                        // product_extra
                        $sql_extra = "SELECT 
                          `tbl_product_extra`.`id` as `id`,
                          `tbl_product_extra`.`id_product` as `id_product`,
                          `tbl_product_extra`.`id_product_extra` as `id_product_extra`,
                          `tbl_product_product`.`product_title` as `product_title`
                        --   `tbl_product_product`.`product_sales_price` as `product_extra_sales_price`
                          FROM `tbl_product_extra`
                          LEFT JOIN `tbl_product_product` 
                          ON `tbl_product_extra`.`id_product_extra` = `tbl_product_product`.`id`
                          WHERE 1=1
                          ";
                        $result_extra = db_qr($sql_extra);
                        $nums_extra = db_nums($result_extra);
                        if ($nums > 0) {
                            while ($row_extra = db_assoc($result_extra)) {
                                $product_extra = array(
                                    'id' => $row_extra['id'],
                                    // 'id_product' => $row_extra['id_product'],
                                    // 'id_product_extra' => $row_extra['id_product_extra'],
                                    'product_title_extra' => $row_extra['product_title'],
                                    // 'product_extra_sales_price' => $row_extra['product_extra_sales_price'],
                                );

                                if ($row_extra['id_product'] == $row['id']) {
                                    array_push($product_item['product_extra'], $product_extra);
                                }
                            }
                        }



                        array_push($product_arr['data'], $product_item);
                    }
                    reJson($product_arr);
                } else {
                    returnSuccess("Danh sách trống", $token);
                }
            }
            break;
        }
    case "list_product_unit": {
            $sql = "SELECT `id`,
                       `unit`,
                       `unit_title`,
                       `id_business`

                FROM  `tbl_product_unit`     
                WHERE 1=1        
               ";
            if (isset($_REQUEST['id_business'])) {
                if ($_REQUEST['id_business'] == '') {
                    unset($_REQUEST['id_business']);
                    returnError("Nhập id_business");
                } else {
                    $id_business = $_REQUEST['id_business'];
                    $sql .= " AND `tbl_product_unit`.`id_business` = '{$id_business}'";


                    if (isset($_REQUEST['filter'])) {
                        if ($_REQUEST['filter'] == '') {
                            unset($_REQUEST['filter']);
                        } else {
                            $filter = $_REQUEST['filter'];
                            $sql .= " AND `tbl_product_unit`.`unit_title` LIKE '%{$filter}%'";
                            $sql .= " OR `tbl_product_unit`.`unit` LIKE '%{$filter}%'";
                        }
                    }
                }
            } else {
                returnError("Nhập id_business");
            }

            $product_arr = array();

            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                $product_arr['success'] = 'true';
                $product_arr['refresh_token'] = $token;

                $product_arr['data'] = array();

                while ($row = db_assoc($result)) {
                    $product_item = array(
                        'id' => $row['id'],
                        'unit' => $row['unit'],
                        'unit_title' => $row['unit_title'],
                        'id_business' => $row['id_business'],
                    );

                    array_push($product_arr['data'], $product_item);
                }
                reJson($product_arr);
            } else {
                returnSuccess("Danh sách trống", $token);
            }

            break;
        }
    case "list_extra": {
            $sql = "SELECT `id`,
                            `id_business`,
                            `product_title`
                            FROM  `tbl_product_product`     
                            WHERE 1=1        
                        ";
            if (isset($_REQUEST['id_business'])) {
                if ($_REQUEST['id_business'] == '') {
                    unset($_REQUEST['id_business']);
                    returnError("Nhập id_business");
                } else {
                    $id_business = $_REQUEST['id_business'];
                    $sql .= " AND `tbl_product_product`.`id_business` = '{$id_business}'";


                    if (isset($_REQUEST['filter'])) {
                        if ($_REQUEST['filter'] == '') {
                            unset($_REQUEST['filter']);
                        } else {
                            $filter = $_REQUEST['filter'];
                            $sql .= " AND `tbl_product_product`.`product_title` LIKE '%{$filter}%'";
                        }
                    }
                }
            } else {
                returnError("Nhập id_business");
            }

            $product_arr = array();

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
            $sql .= " ORDER BY `tbl_product_product`.`id` DESC "; //LIMIT {$start},{$limit}


            $product_arr['success'] = 'true';
            $product_arr['refresh_token'] = $token;

            $product_arr['total'] = strval($total);
            $product_arr['total_page'] = strval($total_page);
            $product_arr['limit'] = strval($limit);
            $product_arr['page'] = strval($page);
            $product_arr['data'] = array();
            $result = db_qr($sql);
            $nums = db_nums($result);
            if ($nums > 0) {
                while ($row = db_assoc($result)) {
                    $product_item = array(
                        'id' => $row['id'],
                        // 'id_business' => $row['id_business'],
                        'product_extra_title' => $row['product_title'],

                    );

                    array_push($product_arr['data'], $product_item);
                }
                reJson($product_arr);
            } else {
                returnSuccess("Danh sách trống", $token);
            }

            break;
        }
    case "list_product": {
            include_once "./viewlist_board/list_product_product.php";
            break;
        }
    default: {
            returnError("Khong ton tai type_manager");
            break;
        }
}
