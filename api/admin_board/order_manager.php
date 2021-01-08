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
    case "cancel_order": {
            if (isset($_REQUEST['id_order'])) {
                if ($_REQUEST['id_order'] == '') {
                    unset($_REQUEST['id_order']);
                    returnError("Nhập id_order");
                } else {
                    $id_order = $_REQUEST['id_order'];
                }
            } else {
                returnError("Nhập id_order");
            }

            if (isset($_REQUEST['order_comment'])) {
                if ($_REQUEST['order_comment'] == '') {
                    unset($_REQUEST['order_comment']);
                } else {
                    $order_comment = $_REQUEST['order_comment'];
                }
            }

            $sql = "UPDATE `tbl_order_order` 
                SET `order_status` = '6',
                    `order_comment` = '{$order_comment}'
                WHERE `id` = '{$id_order}'
                ";
            if (db_qr($sql)) {
                returnSuccess("Hủy đơn thành công");
            } else {
                returnError("Lỗi hủy đơn");
            }
            break;
        }

    case "list_order_detail": {
            include_once "./viewlist_board/list_order_detail.php";
            break;
        }
    case "list_order": {
            $sql = "SELECT
                    `tbl_organization_floor`.`floor_type` as `order_type`,

                    `tbl_order_order`.`id` as `id_order`,
                    `tbl_order_order`.`order_code` as `order_code`,
                    `tbl_order_order`.`order_created` as `order_created`,
                    `tbl_order_order`.`order_total_cost` as `order_total_cost`,
                    `tbl_order_order`.`order_status` as `order_status`

                    FROM `tbl_order_order`
                    LEFT JOIN `tbl_organization_floor` ON `tbl_order_order`.`order_floor` = `tbl_organization_floor`.`floor_title`
                    WHERE 1=1
                    ";

            if (isset($_REQUEST['type_order'])) {
                if ($_REQUEST['type_order'] == '') {
                    unset($_REQUEST['type_order']);
                    returnError("Nhập type_order");
                } else {
                    $type_order = $_REQUEST['type_order'];
                }
            } else {
                returnError("Nhập type_order");
            }

            switch ($type_order) {
                case "carry_out": {
                        $sql .= " AND `tbl_organization_floor`.`floor_type` = 'carry-out'";
                        break;
                    }
                case "eat_in": {
                        $sql .= " AND `tbl_organization_floor`.`floor_type` = 'eat-in'";
                        break;
                    }
                default: {
                        returnError("type_order has been failed");
                        break;
                    }
            }

            if (isset($_REQUEST['id_business'])) {
                if ($_REQUEST['id_business'] == '') {
                    unset($_REQUEST['id_business']);
                    returnError("Nhập id_business");
                } else {
                    $id_business = $_REQUEST['id_business'];
                    $sql .= " AND `tbl_order_order`.`id_business` = '{$id_business}'";


                    if (isset($_REQUEST['filter'])) {
                        if ($_REQUEST['filter'] == '') {
                            unset($_REQUEST['filter']);
                        } else {
                            $filter = $_REQUEST['filter'];
                            $sql .= " AND `tbl_order_order`.`order_code` LIKE '%{$filter}%'";
                        }
                    }

                    if (isset($_REQUEST['filter_status'])) {
                        if ($_REQUEST['filter_status'] == '') {
                            unset($_REQUEST['filter_status']);
                        } else {
                            $filter_status = $_REQUEST['filter_status'];
                            $sql .= " AND `tbl_order_order`.`order_status` = '{$filter_status}'";
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

            $total = count(db_fetch_array($sql));
            $limit = 20;
            $page = 1;

            if (isset($_REQUEST['limit']) && !empty($_REQUEST['limit'])) {
                $limit = $_REQUEST['limit'];
            }
            if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
                $page = $_REQUEST['page'];
            }


            $total_pages = ceil($total / $limit);
            $start = ($page - 1) * $limit;

            $sql .= " ORDER BY `tbl_order_order`.`id` DESC LIMIT {$start},{$limit}";
            $result = db_qr($sql);
            $nums = db_nums($result);
            $order_arr = array();
            if ($nums > 0) {
                $order_arr['success'] = 'true';
                $order_arr['total'] = strval($total);
                $order_arr['total_pages'] = strval($total_pages);
                $order_arr['limit'] = strval($limit);
                $order_arr['page'] = strval($page);
                $order_arr['start'] = strval($start);
                $order_arr['data'] = array();

                while ($row = db_assoc($result)) {

                    $sql_total_cost_tmp = "SELECT * FROM `tbl_order_detail`
                                                       WHERE `id_order` = '{$row['id_order']}'";
                    $result_total_cost_tmp = db_qr($sql_total_cost_tmp);
                    $nums_total_cost_tmp = db_nums($result_total_cost_tmp);
                    $total_cost_tmp = 0;
                    $total_acture = 0;
                    if ($nums_total_cost_tmp > 0) {
                        while ($row_total_cost_tmp = db_assoc($result_total_cost_tmp)) {
                            //neus status == Y
                            //=> ++  total
                            //else
                            if ($row_total_cost_tmp['detail_status'] == 'Y') {
                                $total_acture += $row_total_cost_tmp['detail_cost'] * $row_total_cost_tmp['detail_quantity'];
                            } else {
                                $total_cost_tmp += $row_total_cost_tmp['detail_cost'] * $row_total_cost_tmp['detail_quantity'];
                            }
                        }
                    }
                    if ($total_acture > 0) {
                        $total_cost_tmp = $total_acture;
                    } else {
                        $total_cost_tmp = $total_cost_tmp;
                    }

                    $order_item = array(
                        'id' => $row['id_order'],
                        'order_type' => $row['order_type'],
                        'order_status' => $row['order_status'],
                        'order_code' => $row['order_code'],
                        'order_total_cost' => ($row['order_status'] == 5)?$row['order_total_cost']:strval($total_cost_tmp),
                        'order_created' => $row['order_created'],
                        'total_detail' => ""
                    );

                    $sql_total_detail = "SELECT `id` FROM `tbl_order_detail`
                                                WHERE `id_order` = '{$row['id_order']}'                                           
                                                ";
                    $total_detail = count(db_fetch_array($sql_total_detail));
                    $order_item['total_detail'] = strval($total_detail);
                    array_push($order_arr['data'], $order_item);
                }

                reJson($order_arr);
            } else {
                returnSuccess("Danh sách trống");
            }
            break;
        }
    default: {
            returnError("Khong ton tai type_manager");
            break;
        }
}
