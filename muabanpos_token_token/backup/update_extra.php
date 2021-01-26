<?php

if (isset($_REQUEST['id_extra']) && !empty($_REQUEST['id_extra'])) {
    $id_extra = explode(",", $_REQUEST['id_extra']);
    if (isset($_REQUEST['id_product_extra']) && !empty($_REQUEST['id_product_extra'])) {
        $id_product_extra = explode(",", $_REQUEST['id_product_extra']);

        if (count($id_product_extra) >= count($id_extra)) {
            while (count($id_extra) < count($id_product_extra)) {
                array_push($id_extra, "null");
            }
            for ($i = 0; $i < count($id_extra); $i++) {
                if ($id_extra[$i] == "null") {
                    $sql = "INSERT INTO `tbl_product_extra` 
                            SET `id_product_extra` = '{$id_product_extra[$i]}',
                                `id_product` = '{$id_product}'
                                `id_business` = '{$id_business}'
                                ";
                    if (mysqli_query($conn, $sql)) {
                        $success['add_id_product_extra'] = 'true';
                    }
                } else {
                    $sql = "UPDATE `tbl_product_extra` 
                            SET `id_product_extra` = '{$id_product_extra[$i]}'
                            WHERE `id` = '{$id_extra[$i]}'";
                    if (mysqli_query($conn, $sql)) {
                        $success['edit_id_product_extra'] = 'true';
                    }
                }
            }
        } else {
            // xoá 
            $sql = "DELETE FROM `tbl_product_extra` 
                        WHERE `id_product` = '{$id_product}'";
            if (mysqli_query($conn, $sql)) {
                $success['del_id_product_extra'] = 'true';
            }
            // thêm mới lại
            while (count($id_extra) < count($id_product_extra)) {
                array_push($id_extra, "null");
            }
            for ($i = 0; $i < count($id_extra); $i++) {
                if ($id_extra[$i] != "null") {
                    $sql = "INSERT INTO `tbl_product_extra` 
                            SET `id_product_extra` = '{$id_product_extra[$i]}',
                                `id_product` = '{$id_product}'
                                `id_business` = '{$id_business}'
                                ";
                    if (mysqli_query($conn, $sql)) {
                        $success['add_id_product_extra'] = 'true';
                    }
                } else {
                    $sql = "UPDATE `tbl_product_extra` 
                            SET `id_product_extra` = '{$id_product_extra[$i]}'
                            WHERE `id` = '{$id_extra[$i]}'";
                    if (mysqli_query($conn, $sql)) {
                        $success['edit_id_product_extra'] = 'true';
                    }
                }
            }
        }
    } else {
        for ($i = 0; $i < count($id_extra); $i++) {
            $sql = "DELETE FROM `tbl_product_extra` 
                    WHERE `id` = '{$id_extra[$i]}'";
            if (mysqli_query($conn, $sql)) {
                $success['del_id_product_extra'] = 'true';
            }
        }
    }
} else {
    if (isset($_REQUEST['id_product_extra']) && !empty($_REQUEST['id_product_extra'])) {
        $id_product_extra = explode(",", $_REQUEST['id_product_extra']);
        foreach ($id_product_extra as $item) {
            if (!empty($item)) {
                $sql = "INSERT INTO `tbl_product_extra` SET 
                        `id_product_extra` = '{$item}',
                        `id_product` = '{$id_product}',
                        `id_business` = '{$id_business}'
                        ";
                if (mysqli_query($conn, $sql)) {
                    $success['add_id_product_extra'] = 'true';
                }
            }
        }
    }
}