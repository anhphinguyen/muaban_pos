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

            if (isset($_REQUEST['id_extra_arr'])) {
                if ($_REQUEST['id_extra_arr'] == '') {
                    unset($_REQUEST['id_extra_arr']);
                } else {
                    $id_extra_arr = $_REQUEST['id_extra_arr'];
                }
            }

            if (isset($_REQUEST['product_point'])) {
                if ($_REQUEST['product_point'] == '') {
                    unset($_REQUEST['product_point']);
                } else {
                    $product_point = $_REQUEST['product_point'];
                }
            }
            
            if (isset($_FILES['product_img'])) { // up avatar
                $avatar = 'product_img';
                $dir_save_avatar = "images/product_product/";
            } else {
                returnError("Nhập product_img");
            }


            $dir_save_thumb = handing_file_img($avatar, $dir_save_avatar);
            $sql = "INSERT INTO `tbl_product_product` SET 
                            `id_category` = '{$id_category}',
                            `id_unit` = '{$id_unit}',
                            `id_business` = '{$id_business}',
                            `product_title` = '{$product_title}',
                            `product_code` = '{$product_code}',
                            `product_sales_price` = '{$product_sales_price}',
                            `product_img` = '{$dir_save_thumb}'";
            
            if(isset($product_point) && !empty($product_point)){
                $sql .= " ,`product_point` = '{$product_point}'";
            }
            if(isset($product_point) && !empty($product_point)){
                $sql .= " ,`product_description` = '{$product_description}'";
            }

            $success = array();
            if(db_qr($sql)){
                $id_insert = mysqli_insert_id($conn);

                if(isset($id_extra_arr) && !empty($id_extra_arr)){
                    foreach($id_extra_arr as $id_extra){
                        $sql_extra = "INSERT INTO `tbl_product_extra`
                                        SET `id_product` = '{$id_insert}',
                                            `id_product_extra` = '{$id_extra}',
                                            `id_business` = '{$id_business}'
                                        ";
                        db_qr($sql_extra);
                    }
                }

                returnSuccess("Tạo thành công");
            }else{
                returnError("Tạo thất bại");

            }
            

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
                            'id_business' => $row['id_business'],
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
                    returnSuccess("Danh sách trống");
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
                returnSuccess("Danh sách trống");
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
            $sql .= " ORDER BY `tbl_product_product`.`id` DESC LIMIT {$start},{$limit}";


            $product_arr['success'] = 'true';
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
                        'id_business' => $row['id_business'],
                        'product_title' => $row['product_title'],

                    );

                    array_push($product_arr['data'], $product_item);
                }
                reJson($product_arr);
            } else {
                returnSuccess("Danh sách trống");
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
