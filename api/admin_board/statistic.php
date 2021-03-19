<?php

if (isset($_REQUEST['type_manager'])) {
    if ($_REQUEST['type_manager'] == '') {
        unset($_REQUEST['type_manager']);
    }
}

if (!isset($_REQUEST['type_manager'])) {
    returnError("type_manager is missing!");
}

$typeManager = $_REQUEST['type_manager'];

switch ($typeManager) {
    case 'statistic_product':{
        $id_business = '';
            if (isset($_REQUEST['id_business']) && !empty($_REQUEST['id_business'])) {
                $id_business = $_REQUEST['id_business'];
            } else {
                returnError("Chọn cửa hàng!");
            }
            
            $sql = "SELECT * FROM tbl_product_product WHERE id_business = '$id_business'";
            $total_customer = count(db_fetch_array($sql));
            $result = array();
            $result['success'] = 'true';
            $result['total'] = strval($total_customer);
            $result['data'] = array();
            reJson($result);
            //type: count

            break;
        break;
    }
    case 'statistic_customer': {
            $id_business = '';
            if (isset($_REQUEST['id_business']) && !empty($_REQUEST['id_business'])) {
                $id_business = $_REQUEST['id_business'];
            } else {
                returnError("Chọn cửa hàng!");
            }
            $type = '';
            if (isset($_REQUEST['type']) && !empty($_REQUEST['type'])) {
                $type = $_REQUEST['type'];
            } else {
                $type = 'count_all';
            }
            switch ($type) {
                case 'count_all': {
                        $sql = "SELECT * FROM tbl_customer_customer WHERE id_business = '$id_business'";
                        $total_customer = count(db_fetch_array($sql));
                        $result = array();
                        $result['success'] = 'true';
                        $result['total'] = strval($total_customer);
                        $result['data'] = array();
                        reJson($result);
                        break;
                    }
                case 'count_new_customer': {
                        // checking parameter date_start
                        if (isset($_REQUEST['date_start'])) {
                            if ($_REQUEST['date_start'] == '') {
                                unset($_REQUEST['date_start']);
                                returnError("Nhập ngày bắt đầu!");
                            } else {
                                $date_start = $_REQUEST['date_start'];
                            }
                        } else {
                            returnError("Nhập ngày bắt đầu!");
                        }

                        // end checking parameter date_start

                        // checking parameter date_end
                        if (isset($_REQUEST['date_end'])) {
                            if ($_REQUEST['date_end'] == '') {
                                unset($_REQUEST['date_end']);
                                returnError("Nhập ngày kết thúc!");
                            } else {
                                $date_end = $_REQUEST['date_end'];
                            }
                        } else {
                            returnError("Nhập ngày kết thúc!");
                        }

                        $sql = "SELECT id FROM tbl_customer_customer WHERE id_business = '$id_business' 
                                AND DATE(tbl_customer_customer.customer_created) >= '" . $date_start . "'
                                AND  DATE(tbl_customer_customer.customer_created) <= '" . $date_end . "'";
                       
                        $total_customer = count(db_fetch_array($sql));
                        $result = array();
                        $result['success'] = 'true';
                        $result['total'] = strval($total_customer);
                        $result['data'] = array();
                        reJson($result);
                        break;
                    }
                    default: {
                        returnError("Khong ton tai type");
                        break;
                    }
            }
            //type: count

            break;
        }
    case 'report_top_product':
        $id_business = '';
        if (isset($_REQUEST['id_business']) && !empty($_REQUEST['id_business'])) {
            $id_business = $_REQUEST['id_business'];
        } else {
            returnError("Chọn cửa hàng!");
        }
        // checking parameter date_start
        if (isset($_REQUEST['date_start'])) {
            if ($_REQUEST['date_start'] == '') {
                unset($_REQUEST['date_start']);
                returnError("Nhập ngày bắt đầu!");
            } else {
                $date_start = $_REQUEST['date_start'];
            }
        } else {
            returnError("Nhập ngày bắt đầu!");
        }

        // end checking parameter date_start

        // checking parameter date_end
        if (isset($_REQUEST['date_end'])) {
            if ($_REQUEST['date_end'] == '') {
                unset($_REQUEST['date_end']);
                returnError("Nhập ngày kết thúc!");
            } else {
                $date_end = $_REQUEST['date_end'];
            }
        } else {
            returnError("Nhập ngày kết thúc!");
        }

        $sql_get_top_product = "SELECT
            
                                tbl_order_order.order_created,
                                SUM(tbl_order_detail.detail_cost) as total_payment_item_order,
                                SUM(tbl_order_detail.detail_quantity) as total_quantity_item_order,
                                tbl_order_detail.id_product as product_detail_id,
                                tbl_product_product.product_title as product_name,
                                tbl_product_product.id as product_id
                            
                                FROM tbl_order_detail
                                LEFT JOIN tbl_order_order
                                ON tbl_order_order.id = tbl_order_detail.id_order
                            
                                LEFT JOIN tbl_product_product
                                ON tbl_product_product.id = tbl_order_detail.id_product
                                
                                WHERE tbl_order_order.order_status = '5' AND tbl_order_order.id_business = '" . $id_business . "'
                                ";

        $sql_get_top_product .= " AND DATE(tbl_order_order.order_created) >= '" . $date_start . "'
                                AND  DATE(tbl_order_order.order_created) <= '" . $date_end . "'";

        $sql_get_top_product .= " GROUP BY tbl_order_detail.id_product
                                 ORDER BY total_payment_item_order DESC LIMIT 20";


        $result = array();
        $result['success'] = 'true';
        $result['data'] = array();

        $result_get_top_product = $conn->query($sql_get_top_product);
        $num = mysqli_num_rows($result_get_top_product);
        if ($num > 0) {
            while ($row = $result_get_top_product->fetch_assoc()) {
                $product_item = array(
                    'product_id' => $row['product_id'],
                    'product_name' => $row['product_name'],
                    'total_payment_item_order' => $row['total_payment_item_order'],
                    'total_quantity_item_order' => $row['total_quantity_item_order']
                );
                // Push to "data"
                array_push($result['data'], $product_item);
            }
        }
        echo json_encode($result);
        break;
    case 'income_manager':
        $is_filter_by_day = false;
        $is_filter_by_year = false;
        $is_filter_by_product = false;

        $id_business = '';
        if (isset($_REQUEST['id_business']) && !empty($_REQUEST['id_business'])) {
            $id_business = $_REQUEST['id_business'];
        } else {
            returnError("Chọn cửa hàng!");
        }

        $title_filter = "";

        // checking parameter date_option
        if (isset($_REQUEST['date_option'])) {
            if ($_REQUEST['date_option'] == '') {
                unset($_REQUEST['date_option']);
            }
        }

        if (isset($_REQUEST['date_option'])) {
            // option today
            if (substr($_REQUEST['date_option'], -4) > 2000 && substr($_REQUEST['date_option'], -4) < 2500) {
                $is_filter_by_year = true;
                // option 2019,2018,2017,...
                $year = substr($_REQUEST['date_option'], -4);
                $date_start = $year . '-1-1';
                $date_end = $year . '-12-31';

                $title_filter = "Năm " . $year;
            }
        } else {
            $title_filter = "Lọc theo ngày";
            $is_filter_by_day = true;
        }

        // checking parameter date_start
        if (isset($_REQUEST['date_start'])) {
            if ($_REQUEST['date_start'] == '') {
                unset($_REQUEST['date_start']);
            }
        }

        // end checking parameter date_start

        // checking parameter date_end
        if (isset($_REQUEST['date_end'])) {
            if ($_REQUEST['date_end'] == '') {
                unset($_REQUEST['date_end']);
            }
        }

        // end checking parameter date_end

        $customer_id = '';
        if (isset($_REQUEST['customer_id']) && !empty($_REQUEST['customer_id'])) {
            $customer_id = $_REQUEST['customer_id'];
        }
        $product_id = '';
        if (isset($_REQUEST['product_id']) && !empty($_REQUEST['product_id'])) {
            $is_filter_by_product = true;
            $product_id = $_REQUEST['product_id'];
        }

        if (isset($_REQUEST['date_start']) && isset($_REQUEST['date_end'])) {
            $date_start = $_REQUEST['date_start'];
            $date_end = $_REQUEST['date_end'];
        }

        if (!isset($date_start) || !isset($date_start)) {
            echo json_encode(array(
                'success' => 'false',
                'message' => 'missing para date_end,date_start or date_option or date_option invalid !'
            ));
            exit();
        }

        $sql_get_total_payment = " SELECT SUM(order_total_cost) as total_payment,
                                            DATE(order_created) as  order_created
                                            FROM tbl_order_order
                                            WHERE order_status = '5' AND id_business = '" . $id_business . "'
                                    ";

        if ($date_start == $date_end) {
            $sql_get_total_payment .= " AND DATE(order_created) = '" . $date_start . "' ";
        } else {
            $sql_get_total_payment .= " AND DATE(order_created) >= '" . $date_start . "'
                                AND  DATE(order_created) <= '" . $date_end . "'";

            $sql_get_total_payment .= " GROUP BY DATE(order_created)
                                ORDER BY DATE(order_created) ASC";
        }


        $result_get_total_payment = mysqli_query($conn, $sql_get_total_payment);

        $result_arr = array();

        $total_month1 = 0;
        $total_month2 = 0;
        $total_month3 = 0;
        $total_month4 = 0;
        $total_month5 = 0;
        $total_month6 = 0;
        $total_month7 = 0;
        $total_month8 = 0;
        $total_month9 = 0;
        $total_month10 = 0;
        $total_month11 = 0;
        $total_month12 = 0;

        $result = array();
        $result['success'] = 'true';
        $result['data'] = array();

        $result_arr['title_filter'] = $title_filter;
        $result_arr['data_chart'] = array();

        $num = mysqli_num_rows($result_get_total_payment);
        if ($num > 0) {
            while ($row = $result_get_total_payment->fetch_assoc()) {

                if ($is_filter_by_day) {
                    $item = array(
                        'title' => $row['order_created'],
                        'value' => ''

                    );
                    $item['value'] = $row['total_payment'];

                    if (!empty($item['title'])) {
                        array_push($result_arr['data_chart'], $item);
                    }
                } else if ($is_filter_by_year) {

                    $dateItem = $row['order_created'];

                    $mDateItem = date("m", strtotime($dateItem));

                    switch ($mDateItem) {
                        case '01':
                            $total_month1 += (int) $row['total_payment'];

                            break;

                        case '02':
                            $total_month2 += (int) $row['total_payment'];

                            break;

                        case '03':
                            $total_month3 += (int) $row['total_payment'];

                            break;

                        case '04':
                            $total_month4 += (int) $row['total_payment'];

                            break;

                        case '05':
                            $total_month5 += (int) $row['total_payment'];

                            break;

                        case '06':
                            $total_month6 += (int) $row['total_payment'];

                            break;

                        case '07':
                            $total_month7 += (int) $row['total_payment'];

                            break;

                        case '08':
                            $total_month8 += (int) $row['total_payment'];

                            break;

                        case '09':
                            $total_month9 += (int) $row['total_payment'];

                            break;

                        case '10':
                            $total_month10 += (int) $row['total_payment'];

                            break;

                        case '11':
                            $total_month11 += (int) $row['total_payment'];

                            break;

                        case '12':
                            $total_month12 += (int) $row['total_payment'];

                            break;
                    }
                }
            }

            if ($is_filter_by_year) {

                $item_month1 = array(
                    'title' => 'Tháng 01:',
                    'value' => strval($total_month1)
                );
                array_push($result_arr['data_chart'], $item_month1);

                $item_month2 = array(
                    'title' => 'Tháng 02:',
                    'value' => strval($total_month2)
                );
                array_push($result_arr['data_chart'], $item_month2);

                $item_month3 = array(
                    'title' => 'Tháng 03:',
                    'value' => strval($total_month3)
                );
                array_push($result_arr['data_chart'], $item_month3);

                $item_month4 = array(
                    'title' => 'Tháng 04:',
                    'value' => strval($total_month4)
                );
                array_push($result_arr['data_chart'], $item_month4);

                $item_month5 = array(
                    'title' => 'Tháng 05:',
                    'value' => strval($total_month5)
                );
                array_push($result_arr['data_chart'], $item_month5);

                $item_month6 = array(
                    'title' => 'Tháng 06:',
                    'value' => strval($total_month6)
                );
                array_push($result_arr['data_chart'], $item_month6);

                $item_month7 = array(
                    'title' => 'Tháng 07:',
                    'value' => strval($total_month7)
                );
                array_push($result_arr['data_chart'], $item_month7);
                $item_month8 = array(
                    'title' => 'Tháng 08:',
                    'value' => strval($total_month8)
                );
                array_push($result_arr['data_chart'], $item_month8);
                $item_month9 = array(
                    'title' => 'Tháng 09:',
                    'value' => strval($total_month9)
                );
                array_push($result_arr['data_chart'], $item_month9);
                $item_month10 = array(
                    'title' => 'Tháng 10:',
                    'value' => strval($total_month10)
                );
                array_push($result_arr['data_chart'], $item_month10);
                $item_month11 = array(
                    'title' => 'Tháng 11:',
                    'value' => strval($total_month11)
                );
                array_push($result_arr['data_chart'], $item_month11);
                $item_month12 = array(
                    'title' => 'Tháng 12:',
                    'value' => strval($total_month12)
                );
                array_push($result_arr['data_chart'], $item_month12);
            }
        }
        array_push($result['data'], $result_arr);

        echo json_encode($result);

        break;

        // case "revenue": {
        //         $sql = "";
        //         break;
        //     }

    default: {
            returnError("Khong ton tai type_manager");
            break;
        }
}
