<?php

include_once 'secret_key.php';
include_once "../lib/database.php";
include_once "../lib/connect.php";
include_once "../lib/reuse_function.php";

include_once "../lib/jwt/php-jwt-master/src/JWT.php";

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Allow-Methods: GET");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

// check if data recived is from raw - if so, assign it to $_REQUEST
if (!isset($_REQUEST['detect'])) {
    // get raw json data
    $_REQUEST = json_decode(file_get_contents('php://input'), true);
    if (!isset($_REQUEST['detect'])) {
        echo json_encode(array(
            'message' => 'detect parameter not found !'
        ));
        exit();
    }
}
// handle detect value
// handle socket refresh token
if (isset($_REQUEST['type_socket']) && !empty($_REQUEST['type_socket'])) {
    $type_socket = $_REQUEST['type_socket'];
}


$detect = $_REQUEST['detect'];

switch ($detect) {

        /* admin board */

    case 'force_signout': {
            include_once 'basic_auth.php';
            include_once 'admin_board/force_signout.php';
            break;
        }
    case 'account_type_manager': {
            include_once 'basic_auth.php';
            include_once 'admin_board/account_type_manager.php';
            break;
        }
    case 'account_manager': {
            include_once 'basic_auth.php';
            include_once 'admin_board/account_manager.php';
            break;
        }

    case 'product_category_manager': {
            include_once 'basic_auth.php';
            include_once 'admin_board/product_category_manager.php';
            break;
        }
    case 'unit_manager': {
            include_once 'basic_auth.php';
            include_once 'admin_board/unit_manager.php';
            break;
        }

    case 'product_manager': {
            include_once 'basic_auth.php';
            include_once 'admin_board/product_manager.php';
            break;
        }
    case 'order_manager': {
            include_once 'basic_auth.php';
            include_once 'admin_board/order_manager.php';
            break;
        }
    case 'customer_level_manager': {
            include_once 'basic_auth.php';
            include_once 'admin_board/customer_level_manager.php';
            break;
        }
    case 'customer_customer_manager': {
            include_once 'basic_auth.php';
            include_once 'admin_board/customer_customer_manager.php';
            break;
        }
    case 'table_manager': {
            include_once 'basic_auth.php';
            include_once 'admin_board/table_manager.php';
            break;
        }
    case 'floor_manager': {
            include_once 'basic_auth.php';
            include_once 'admin_board/floor_manager.php';
            break;
        }
    case 'change_pass': {
            include_once 'basic_auth.php';
            include_once 'admin_board/change_pass.php';
            break;
        }
        /* employee board */

    case 'login_test': {
            include_once 'employee_board/login_test.php';
            break;
        }
    case 'update_detail_status': {
            include_once 'basic_auth.php';
            include_once 'employee_board/update_detail_status.php';
            break;
        }
    case 'update_order_status': {
            include_once 'basic_auth.php';
            include_once 'employee_board/update_order_status.php';
            break;
        }

    case 'check_view': {
            include_once 'basic_auth.php';
            include_once 'employee_board/check_view.php';
            break;
        }
    case 'order_take_away': {
            include_once 'basic_auth.php';
            include_once 'employee_board/order_take_away.php';
            break;
        }
    case 'order_employee': {
            include_once 'basic_auth.php';
            include_once 'employee_board/order_employee.php';
            break;
        }
    case 'login': {
            include_once 'employee_board/login.php';
            break;
        }
    case 'create_customer': {
            include_once 'basic_auth.php';
            include_once 'employee_board/create_customer.php';
            break;
        }
    case 'check_sign_out': {
            include_once 'basic_auth.php';
            include_once 'employee_board/check_sign_out.php';
            break;
        }

    case 'update_customer': {
            include_once 'basic_auth.php';
            include_once 'employee_board/update_customer.php';
            break;
        }


        /* viewlist board */


    case 'list_product_sold_out': {
            include_once 'basic_auth.php';
            include_once 'viewlist_board/list_product_sold_out.php';
            break;
        }
    case 'list_product_disable': {
            include_once 'basic_auth.php';
            include_once 'viewlist_board/list_product_disable.php';
            break;
        }
    case 'list_customer_by_level': {
            include_once 'basic_auth.php';
            include_once 'viewlist_board/list_customer_by_level.php';
            break;
        }

    case 'list_level': {
            include_once 'basic_auth.php';
            include_once 'viewlist_board/list_customer_level.php';
            break;
        }
    case 'list_order_detail': {
            include_once 'basic_auth.php';
            include_once 'viewlist_board/list_order_detail.php';
            break;
        }
    case 'list_order_order': {
            include_once 'basic_auth.php';
            include_once 'viewlist_board/list_order_order.php';
            break;
        }

    case 'list_floor': {
            include_once 'basic_auth.php';
            include_once 'viewlist_board/list_organization_floor.php';
            break;
        }
    case 'list_product_category': {
            include_once 'basic_auth.php';
            include_once 'viewlist_board/list_product_category.php';
            break;
        }
    case 'list_product_product': {
            include_once 'basic_auth.php';
            include_once 'viewlist_board/list_product_product.php';
            break;
        }
    case 'list_product_notify': {
            include_once 'basic_auth.php';
            include_once 'viewlist_board/list_product_notify.php';
            break;
        }
    case 'list_customer_customer': {
            include_once 'basic_auth.php';
            include_once 'viewlist_board/list_customer_customer.php';
            break;
        }
        /* socket board */
    case 'list_chef_order': {
            if (isset($type_socket) && !empty($type_socket)) {
                include_once 'socket_board/list_chef_order.php';
            } else {
                include_once 'basic_auth.php';
                include_once 'socket_board/list_chef_order.php';
            }

            break;
        }
    case 'table_order': {
            if (isset($type_socket) && !empty($type_socket)) {
                include_once 'socket_board/table_order.php';
            } else {
                include_once 'basic_auth.php';
                include_once 'socket_board/table_order.php';
            }
            break;
        }
    case 'list_table_empty': {
            if (isset($type_socket) && !empty($type_socket)) {
                include_once 'socket_board/list_table_empty.php';
            } else {
                include_once 'basic_auth.php';
                include_once 'socket_board/list_table_empty.php';
            }
            break;
        }
    default: {
            echo json_encode(array(
                'success' => 'false',
                'massage' => 'detect has been failed'
            ));
        }
}
