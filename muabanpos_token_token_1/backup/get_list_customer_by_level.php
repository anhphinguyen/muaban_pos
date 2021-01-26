<?php

$sql_customer = "SELECT `customer_point` FROM `tbl_customer_customer` WHERE 1=1 ";
for ($i = 0; $i < count($point_arr['id_level']); $i++) {
    for ($j = $i + 1; $j <= count($point_arr['id_level']); $j++) {
        if ($j == count($point_arr['id_level'])) {
            if ($id_level == $point_arr['id_level'][$i]) {
                $point_end = (int)$point_arr['point'][$i];
                $sql_customer .= " AND `customer_point` >= {$point_end}
                                 ";
            }
        } else {
            if ($id_level == $point_arr['id_level'][$i]) {
                $point_begin = (int)$point_arr['point'][$i];
                $point_end = (int)$point_arr['point'][$j];
                $sql_customer .= " AND `customer_point` >= {$point_begin}
                                  AND `customer_point` < {$point_end}
                                ";
            }
        }
    }
}
