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
    case 'delete': {

        if (isset($_REQUEST['id_customer'])) {
            if ($_REQUEST['id_customer'] == '') {
                unset($_REQUEST['id_customer']);
                returnError("Nhập id_customer");
            } else {
                $id_customer = $_REQUEST['id_customer'];
            }
        } else {
            returnError("Nhập id_customer");
        }

        $sql = "DELETE FROM `tbl_customer_customer` WHERE `id` = '{$id_customer}'";
        if(db_qr($sql)){
            returnSuccess("Xóa khách hàng thành công", $token);
        }else{
            returnError("Không xóa được");
        }

            break;
        }
    case 'update': {

            if (isset($_REQUEST['id_customer'])) {
                if ($_REQUEST['id_customer'] == '') {
                    unset($_REQUEST['id_customer']);
                    returnError("Nhập id_customer");
                } else {
                    $id_customer = $_REQUEST['id_customer'];
                }
            } else {
                returnError("Nhập id_customer");
            }

            $success = array();
            if (isset($_REQUEST['customer_name']) && !empty($_REQUEST['customer_name'])) { //*
                $customer_name = htmlspecialchars($_REQUEST['customer_name']);
                $sql = "UPDATE `tbl_customer_customer` SET";
                $sql .= " `customer_name` = '{$customer_name}'";
                $sql .= " WHERE `id` = '{$id_customer}'";

                if (mysqli_query($conn, $sql)) {
                    $success['customer_name'] = "true";
                }
            }

            if (isset($_REQUEST['customer_phone']) && !empty($_REQUEST['customer_phone'])) {
                $customer_phone = htmlspecialchars($_REQUEST['customer_phone']);
                $sql = "UPDATE `tbl_customer_customer` SET";
                $sql .= " `customer_phone` = '{$customer_phone}'";
                $sql .= " WHERE `id` = '{$id_customer}'";

                if (mysqli_query($conn, $sql)) {
                    $success['customer_phone'] = "true";
                }
            }
            if (isset($_REQUEST['customer_address']) && !empty($_REQUEST['customer_address'])) {
                $customer_address = htmlspecialchars($_REQUEST['customer_address']);
                $sql = "UPDATE `tbl_customer_customer` SET";
                $sql .= " `customer_address` = '{$customer_address}'";
                $sql .= " WHERE `id` = '{$id_customer}'";

                if (mysqli_query($conn, $sql)) {
                    $success['customer_address'] = "true";
                }
            }
            if (isset($_REQUEST['customer_email']) && !empty($_REQUEST['customer_email'])) {
                $customer_email = htmlspecialchars($_REQUEST['customer_email']);
                $sql = "UPDATE `tbl_customer_customer` SET";
                $sql .= " `customer_email` = '{$customer_email}'";
                $sql .= " WHERE `id` = '{$id_customer}'";

                if (mysqli_query($conn, $sql)) {
                    $success['customer_email'] = "true";
                }
            }
            if (isset($_REQUEST['customer_birthday']) && !empty($_REQUEST['customer_birthday'])) {
                $customer_birthday = htmlspecialchars($_REQUEST['customer_birthday']);
                $sql = "UPDATE `tbl_customer_customer` SET";
                $sql .= " `customer_birthday` = '{$customer_birthday}'";
                $sql .= " WHERE `id` = '{$id_customer}'";

                if (mysqli_query($conn, $sql)) {
                    $success['customer_birthday'] = "true";
                }
            }
            if (isset($_REQUEST['customer_sex']) && !empty($_REQUEST['customer_sex'])) {
                $customer_sex = htmlspecialchars($_REQUEST['customer_sex']);
                $sql = "UPDATE `tbl_customer_customer` SET";
                $sql .= " `customer_sex` = '{$customer_sex}'";
                $sql .= " WHERE `id` = '{$id_customer}'";

                if (mysqli_query($conn, $sql)) {
                    $success['customer_sex'] = "true";
                }
            }
            if (isset($_REQUEST['customer_taxcode']) && !empty($_REQUEST['customer_taxcode'])) {
                $customer_taxcode = htmlspecialchars($_REQUEST['customer_taxcode']);
                $sql = "UPDATE `tbl_customer_customer` SET";
                $sql .= " `customer_taxcode` = '{$customer_taxcode}'";
                $sql .= " WHERE `id` = '{$id_customer}'";

                if (mysqli_query($conn, $sql)) {
                    $success['customer_taxcode'] = "true";
                }
            }

            if (!empty($success)) {
                returnSuccess("Cập nhật thành công", $token);

            }else{
                returnSuccess("Không có thông tin cập nhật", $token);
            }
            break;
        }
    case 'create': {
            $error = array();
            if (isset($_REQUEST['id_business'])) {
                if ($_REQUEST['id_business'] == '') {
                    unset($_REQUEST['id_business']);
                    $error['id_business'] = "Nhap id_business";
                } else {
                    $id_business = $_REQUEST['id_business'];
                }
            } else {
                $error['id_business'] = "Nhap id_business";
            }

            if (isset($_REQUEST['id_account'])) {
                if ($_REQUEST['id_account'] == '') {
                    unset($_REQUEST['id_account']);
                    $error['id_account'] = "Nhap id_account";
                } else {
                    $id_account = $_REQUEST['id_account'];
                }
            } else {
                $error['id_account'] = "Nhap id_account";
            }

            if (isset($_REQUEST['customer_name'])) {   //*
                if ($_REQUEST['customer_name'] == '') {
                    unset($_REQUEST['customer_name']);
                    $error['customer_name'] = "Nhap customer_name";
                } else {
                    $customer_name = htmlspecialchars($_REQUEST['customer_name']);
                }
            } else {
                $error['customer_name'] = "Nhap customer_name";
            }


            if (isset($_REQUEST['customer_phone'])) {  //*
                if ($_REQUEST['customer_phone'] == '') {
                    unset($_REQUEST['customer_phone']);
                    $error['customer_phone'] = "Nhap customer_phone";
                } else {
                    $customer_phone = htmlspecialchars($_REQUEST['customer_phone']);
                }
            } else {
                $error['customer_phone'] = "Nhap customer_phone";
            }


            if (isset($_REQUEST['customer_address'])) {
                if ($_REQUEST['customer_address'] == '') {
                    unset($_REQUEST['customer_address']);
                } else {
                    $customer_address = htmlspecialchars($_REQUEST['customer_address']);
                }
            }

            if (isset($_REQUEST['customer_email'])) {
                if ($_REQUEST['customer_email'] == '') {
                    unset($_REQUEST['customer_email']);
                } else {
                    $customer_email = htmlspecialchars($_REQUEST['customer_email']);
                }
            }
            if (isset($_REQUEST['customer_birthday'])) {
                if ($_REQUEST['customer_birthday'] == '') {
                    unset($_REQUEST['customer_birthday']);
                } else {
                    $customer_birthday = htmlspecialchars($_REQUEST['customer_birthday']);
                }
            }
            if (isset($_REQUEST['customer_sex'])) {
                if ($_REQUEST['customer_sex'] == '') {
                    unset($_REQUEST['customer_sex']);
                } else {
                    $customer_sex = $_REQUEST['customer_sex'];
                }
            }

            if (isset($_REQUEST['customer_taxcode'])) {
                if ($_REQUEST['customer_taxcode'] == '') {
                    unset($_REQUEST['customer_taxcode']);
                } else {
                    $customer_taxcode = htmlspecialchars($_REQUEST['customer_taxcode']);
                }
            }

            if (empty($error)) {
                // check customer exist
                $sql = "SELECT * FROM `tbl_customer_customer` 
                            WHERE `customer_phone` = '{$customer_phone}'
                            AND `id_business` = '{$id_business}'";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    returnSuccess("Đã tồn tại khách hàng này", $token);
                }
                $sql = "SELECT `store_prefix` FROM `tbl_business_store` WHERE `id` = '{$id_business}'";
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    while ($row = db_assoc($result)) {
                        $store_prefix = $row['store_prefix'];
                    }
                }
                $customer_code = $store_prefix . "KH" . substr(time(), -8);
                $sql = "INSERT INTO `tbl_customer_customer` SET 
                                                `id_business` = '{$id_business}',
                                                `id_account` = '{$id_account}',
                                                `customer_name` = '{$customer_name}',
                                                `customer_code` = '{$customer_code}',
                                                `customer_phone` = '{$customer_phone}'
                                                ";
                if (isset($customer_address) && !empty($customer_address)) {
                    $sql .= " ,`customer_address` = '{$customer_address}'";
                }
                if (isset($customer_email) && !empty($customer_email)) {
                    $sql .= " ,`customer_email` = '{$customer_email}'";
                }
                if (isset($customer_birthday) && !empty($customer_birthday)) {
                    $sql .= " ,`customer_birthday` = '{$customer_birthday}'";
                }
                if (isset($customer_sex) && !empty($customer_sex)) {
                    $sql .= " ,`customer_sex` = '{$customer_sex}'";
                }
                if (isset($customer_taxcode) && !empty($customer_taxcode)) {
                    $sql .= " ,`customer_taxcode` = '{$customer_taxcode}'";
                }

                if (mysqli_query($conn, $sql)) {
                    $id_insert = mysqli_insert_id($conn);

                    $sql = "SELECT * FROM `tbl_customer_customer` WHERE `id` = '{$id_insert}'";
                    $result = db_qr($sql);
                    $nums = db_nums($result);
                    $customer_arr = array();
                    if ($nums > 0) {
                        $customer_arr['success'] = 'true';
                        $customer_arr['refresh_token'] = $token;
                        $customer_arr['data'] = array();
                        while ($row = db_assoc($result)) {
                            $customer_item = array(
                                'id_customer' => $row['id'],
                                'customer_name' => html_entity_decode($row['customer_name']),
                                'customer_code' => html_entity_decode($row['customer_code']),
                                'customer_phone' => html_entity_decode($row['customer_phone']),
                                'customer_address' => html_entity_decode($row['customer_address']),
                                'customer_email' => html_entity_decode($row['customer_email']),
                                'customer_birthday' => html_entity_decode($row['customer_birthday']),
                                'customer_sex' => html_entity_decode($row['customer_sex']),
                                'customer_point' => html_entity_decode($row['customer_point']),
                                'customer_level' => "",
                                'customer_taxcode' => html_entity_decode($row['customer_taxcode']),
                            );
                            array_push($customer_arr['data'], $customer_item);
                        }
                        reJson($customer_arr);
                    }
                } else {
                    returnError("Tạo khách hàng không thành công");
                }
            } else {
                returnError("Điền đầy đủ thông tin");
            }
            break;
        }
    case 'list_customer_order_detail': {
            include_once "./viewlist_board/list_order_detail.php";
            break;
        }

    case 'list_customer_order': {
            $sql = "SELECT 
                `tbl_order_order`.`id` as `id_order`,
                `tbl_order_order`.`id_business` as `id_business`,
                `tbl_order_order`.`order_code` as `order_code`,
                `tbl_order_order`.`order_status` as `order_status`,

                `tbl_customer_customer`.`id` as `id_customer`,
                `tbl_customer_customer`.`customer_name` as `customer_name`,
                `tbl_customer_customer`.`customer_phone` as `customer_phone`
                FROM  `tbl_order_order`
                LEFT JOIN `tbl_customer_customer` 
                ON `tbl_order_order`.`id_customer` = `tbl_customer_customer`.`id`
                WHERE `tbl_order_order`.`order_status` = '5'";

            if (isset($_REQUEST['id_business'])) {
                if ($_REQUEST['id_business'] == '') {
                    unset($_REQUEST['id_business']);
                    returnError("Nhập id_business");
                } else {
                    $id_business = $_REQUEST['id_business'];
                    $sql .= " AND `tbl_order_order`.`id_business` = '{$id_business}'";

                    if (isset($_REQUEST['id_customer'])) {
                        if ($_REQUEST['id_customer'] == '') {
                            unset($_REQUEST['id_customer']);
                            returnError("Nhập id_customer");
                        } else {
                            $id_customer = $_REQUEST['id_customer'];
                            $sql .= " AND `tbl_order_order`.`id_customer` = '{$id_customer}'";
                        }
                    } else {
                        returnError("Nhập id_customer");
                    }

                    if (isset($_REQUEST['filter'])) {
                        if ($_REQUEST['filter'] == '') {
                            unset($_REQUEST['filter']);
                        } else {
                            $filter = $_REQUEST['filter'];
                            $sql .= " AND `tbl_order_order`.`order_code` LIKE '%{$filter}%'";
                        }
                    }

                    if (isset($_REQUEST['date_begin'])) {
                        if ($_REQUEST['date_begin'] == '') {
                            unset($_REQUEST['date_begin']);
                        } else {
                            $date_begin = $_REQUEST['date_begin'];
                            $sql .= " AND `tbl_order_order`.`order_created` >= '{$date_begin}'";
                        }
                    } else {
                        $month = date("Y-m", time());
                        $sql .= " AND `tbl_order_order`.`order_created` >= '" . $month . "-1 00:00:00'";
                    }

                    if (isset($_REQUEST['date_end'])) {
                        if ($_REQUEST['date_end'] == '') {
                            unset($_REQUEST['date_end']);
                        } else {
                            $date_end = $_REQUEST['date_end'];
                            $sql .= " AND `tbl_order_order`.`order_created` <= '{$date_end}'";
                        }
                    } else {
                        $month = date("Y-m", time());
                        $sql .= " AND `tbl_order_order`.`order_created` <= '" . $month . "-31 23:59:59'";
                    }
                }
            } else {
                returnError("Nhập id_business");
            }


            $order_arr = array();

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
            $sql .= " ORDER BY `tbl_order_order`.`id` DESC LIMIT {$start},{$limit}";

            if (empty($error)) {
                $order_arr['success'] = 'true';
                $order_arr['total'] = strval($total);
                $order_arr['total_page'] = strval($total_page);
                $order_arr['limit'] = strval($limit);
                $order_arr['page'] = strval($page);
                $order_arr['data'] = array();
                $result = db_qr($sql);
                $nums = db_nums($result);
                if ($nums > 0) {
                    while ($row = db_assoc($result)) {
                        $order_item = array(
                            'id_order' => $row['id_order'],
                            'id_business' => $row['id_business'],
                            'id_customer' => $row['id_customer'],
                            'order_code' => $row['order_code'],
                            'customer_name' => $row['customer_name'],
                            'customer_phone' => $row['customer_phone'],
                        );

                        array_push($order_arr['data'], $order_item);
                    }
                    reJson($order_arr);
                } else {
                    returnSuccess("Danh sách trống", $token);
                }
            }

            break;
        }
    case 'list_customer_customer': {
            include_once "./viewlist_board/list_customer_customer.php";
            break;
        }
    default: {
            returnError("Khong ton tai type_manager");
            break;
        }
}
