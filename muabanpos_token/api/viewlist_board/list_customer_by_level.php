<?php
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

$sql_level = "SELECT * FROM `tbl_customer_point` WHERE `id_business` = '{$id_business}'";
$result_level = db_qr($sql_level);
$nums_level = db_nums($result_level);
if ($nums_level) {
    $point_arr = array();
    $point_arr['id_level'] = array();
    $point_arr['point'] = array();
    $point_arr['level'] = array();
    while ($row_level = db_assoc($result_level)) {
        $id_level = $row_level['id'];
        $customer_point = $row_level['customer_point'];
        $customer_level = $row_level['customer_level'];

        array_push($point_arr['id_level'], $id_level);
        array_push($point_arr['point'], $customer_point);
        array_push($point_arr['level'], $customer_level);
    }
    $point_arr = arrange_position($point_arr['point'], $point_arr['level'], $point_arr['id_level']);
} else {
    returnError("Danh sách trống");
}

if (isset($_REQUEST['id_level'])) {
    if ($_REQUEST['id_level'] == '') {
        unset($_REQUEST['id_level']);
        returnError("Nhập id_level");
    } else {
        $id_level = $_REQUEST['id_level'];
    }
} else {
    returnError("Nhập id_level");
}
/////////////////////////////////////////
// $sql = get_list_customer_by_level($id_level, $point_arr['point'], $point_arr['id_level']);
$sql = "SELECT * FROM `tbl_customer_customer` 
        WHERE 1=1 AND `id_business` = '{$id_business}'
        ";

if (isset($_REQUEST['filter'])) {
    if ($_REQUEST['filter'] == '') {
        unset($_REQUEST['filter']);
    } else {
        $filter = $_REQUEST['filter'];
        $sql .= " AND `customer_name` LIKE '%{$filter}%'";
    }
} 


for ($i = 0; $i < count($point_arr['id_level']); $i++) {
    for ($j = $i + 1; $j <= count($point_arr['id_level']); $j++) {
        if ($j == count($point_arr['id_level'])) {
            if ($id_level == $point_arr['id_level'][$i]) {
                $point_end = (int)$point_arr['point'][$i];
                $sql .= " AND `customer_point` >= {$point_end}
                         ";
            }
            break;
        } else {
            if ($id_level == $point_arr['id_level'][$i]) {
                $point_begin = (int)$point_arr['point'][$i];
                $point_end = (int)$point_arr['point'][$j];
                $sql .= " AND `customer_point` >= {$point_begin}
                          AND `customer_point` < {$point_end}
                        ";
            }
            break;
        }
    }
}
/////////////////////////////////////////

$customer_arr = array();

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
$sql .= " ORDER BY `tbl_customer_customer`.`id` DESC LIMIT {$start},{$limit}";
// returnError($sql);

$customer_arr['success'] = 'true';
$customer_arr['refresh_token'] = $token;

$customer_arr['total'] = strval($total);
$customer_arr['total_page'] = strval($total_page);
$customer_arr['limit'] = strval($limit);
$customer_arr['page'] = strval($page);
$customer_arr['data'] = array();
$result = db_qr($sql);
$nums = db_nums($result);

if ($nums > 0) {
    while ($row = db_assoc($result)) {
        $customer_item = array(
            'id_customer' => $row['id'],
            'customer_name' => htmlspecialchars_decode($row['customer_name']),
            'customer_code' => htmlspecialchars_decode($row['customer_code']),
            'customer_phone' => htmlspecialchars_decode($row['customer_phone']),
            'customer_address' => htmlspecialchars_decode($row['customer_address']),
            'customer_email' => htmlspecialchars_decode($row['customer_email']),
            'customer_birthday' => htmlspecialchars_decode($row['customer_birthday']),
            'customer_sex' => htmlspecialchars_decode($row['customer_sex']),
            'customer_point' => htmlspecialchars_decode($row['customer_point']),
            'customer_level' => "",
            'customer_taxcode' => htmlspecialchars_decode($row['customer_taxcode']),
        );

        for ($i = 0; $i < count($point_arr['point']); $i++) {
            if ($row['customer_point'] >= $point_arr['point'][$i]) {
                $customer_item['customer_level'] = $point_arr['level'][$i];
            }
        }

        array_push($customer_arr['data'], $customer_item);
    }
    reJson($customer_arr);
} else {
    returnSuccess("Không có khách hàng", $token);
}
