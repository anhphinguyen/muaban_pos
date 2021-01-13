<?php

$sql = "SELECT * FROM `tbl_order_order` WHERE 1=1";

if (isset($_REQUEST['id_business'])) {
    if ($_REQUEST['id_business'] == '') {
        unset($_REQUEST['id_business']);
        $error['id_business'] = "Nhập id_business";
    } else {
        $id_business = $_REQUEST['id_business'];
        $sql .= " AND `id_business` = '{$id_business}'";
        $sql .= " AND `order_status` = '5'";

        if (isset($_REQUEST['id_account'])) {
            if ($_REQUEST['id_account'] == '') {
                unset($_REQUEST['id_account']);
            } else {
                $id_account = $_REQUEST['id_account'];
                $sql .= " AND `id_account` = '{$id_account}'";
            }
        }

        if (isset($_REQUEST['filter'])) {
            if ($_REQUEST['filter'] == '') {
                unset($_REQUEST['filter']);
            } else {
                $filter = $_REQUEST['filter'];
                $sql .= " AND `order_code` LIKE '%{$filter}%'";
            }
        }
        
        if (isset($_REQUEST['date_begin'])) {
            if ($_REQUEST['date_begin'] == '') {
                unset($_REQUEST['date_begin']);
            } else {
                $date_begin = $_REQUEST['date_begin'];
                $sql .= " AND `order_created` >= '{$date_begin}"." 00:00:00'";
            }
        }else{
            $month = date("Y-m", time());
            $sql .= " AND `order_created` >= '".$month."-1 00:00:00'";
            
        }
        
        if (isset($_REQUEST['date_end'])) {
            if ($_REQUEST['date_end'] == '') {
                unset($_REQUEST['date_end']);
            } else {
                $date_end = $_REQUEST['date_end'];
                $sql .= " AND `order_created` <= '{$date_end}"." 23:59:59'";
            }
        }else{
            $month = date("Y-m", time());
            $sql .= " AND `order_created` <= '".$month."-31 23:59:59'";
        }

    }
}else{
    $error['id_business'] = "Nhập id_business";
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
                'id' => $row['id'],
                'id_business' => $row['id_business'],
                'id_account' => $row['id_account'],
                'order_code' => $row['order_code'],
                'order_floor' => $row['order_floor'],
                'order_table' => $row['order_table'],
                'order_status' => $row['order_status'],
                'order_total_cost' => !empty($row['order_total_cost'])? $row['order_total_cost']:"0",
                'order_created' => $row['order_created'],
            );

            $sql_order_detail = "SELECT * FROM `tbl_order_detail` WHERE `id_order` = '{$row['id']}'";
            $total_order_detail = count(db_fetch_array($sql_order_detail));
            $order_item['total_product'] = strval($total_order_detail);
            array_push($order_arr['data'], $order_item);
        }
        reJson($order_arr);
    } else {
        returnSuccess("Danh sách trống");
    }
} else {
    $error['success'] = 'false';
    $error['message'] = 'Lấy danh sách sản phẩm không thành công';
    reJson($error);
}


