<?php

function get_list_customer_by_level($id, $id_business, $point_arr = array(), $id_level_arr = array())
{
    $sql_customer = "SELECT `customer_point` FROM `tbl_customer_customer` WHERE `id_business` = '{$id_business}' ";
    for ($i = 0; $i < count($id_level_arr); $i++) {
        for ($j = $i + 1; $j <= count($id_level_arr); $j++) {
            if ($j == count($id_level_arr)) {
                if ($id == $id_level_arr[$i]) {
                    $point_end = (int)$point_arr[$i];
                    $sql_customer .= " AND `customer_point` >= {$point_end}
                             ";
                }
                break;
            } else {
                if ($id == $id_level_arr[$i]) {
                    $point_begin = (int)$point_arr[$i];
                    $point_end = (int)$point_arr[$j];
                    $sql_customer .= " AND `customer_point` >= {$point_begin}
                              AND `customer_point` < {$point_end}
                            ";
                }
                break;
            }
        }
    }

    return $sql_customer;
}
function arrange_position($point_arr = array(), $level_arr = array(), $id_arr = array())
{
    $tmp_point = "";
    $tmp_level = "";
    $tmp_id = "";
    $result = array();

    for ($i = 0; $i < count($point_arr); $i++) {
        for ($j = $i + 1; $j < count($point_arr); $j++) {
            if ($point_arr[$i] > $point_arr[$j]) {
                $tmp_point = $point_arr[$i];
                $point_arr[$i] = $point_arr[$j];
                $point_arr[$j] = $tmp_point;

                $tmp_level = $level_arr[$i];
                $level_arr[$i] = $level_arr[$j];
                $level_arr[$j] = $tmp_level;

                $tmp_id = $id_arr[$i];
                $id_arr[$i] = $id_arr[$j];
                $id_arr[$j] = $tmp_id;
            }
        }
    }
    $result['id_level'] = $id_arr;
    $result['point'] = $point_arr;
    $result['level'] = $level_arr;
    return $result;
}


function handing_files_img($myfile, $dir_save)
{   // myfile = file nhập vào, $max_size = kích thước lớn nhất của file, 
    // $allow_file_type = các đuôi file cho phép, $dir_save = thư mục lưu trữ
    $total = count($_FILES[$myfile]['name']);
    for ($i = 0; $i < $total; $i++) {
        if ($_FILES[$myfile]['error'][$i] == 0) {
            $target_dir = $dir_save;
            $target_dir_4_upload = '../../' . $dir_save;
            $target_file = $target_dir . basename($_FILES[$myfile]['name'][$i]);
            $target_save_file = $target_dir_4_upload . basename($_FILES[$myfile]['name'][$i]);

            $allow_file_type = array('jpg', 'jpeg', 'png');
            $max_file_size = 5242880;
            $img_file_type = pathinfo($target_file, PATHINFO_EXTENSION);

            // kiem tra co phai file anh
            $check = getimagesize($_FILES[$myfile]['tmp_name'][$i]);
            if ($check !== false) {
                $img_info = pathinfo($_FILES[$myfile]['name'][$i]);
                if (file_exists($target_save_file)) {
                    $k = 0;
                    $name_copy = $img_info['filename'] . "_Copy_" . $k;
                    $target_file = $target_dir . $name_copy . "." . $img_info['extension'];
                    $target_save_file = $target_dir_4_upload . $name_copy . "." . $img_info['extension'];
                    while (file_exists($target_save_file)) {
                        $k++;
                        $name_copy = $img_info['filename'] . "_Copy_" . $k;
                        $target_file = $target_dir . $name_copy . "." . $img_info['extension'];
                        $target_save_file = $target_dir_4_upload . $name_copy . "." . $img_info['extension'];
                    }
                }

                if ($_FILES[$myfile]['size'][$i] > $max_file_size) {
                    return_error("file size is greater than {$max_file_size}");
                }

                if (!in_array(strtolower($img_file_type), $allow_file_type)) {
                    return_error("file type is not allow, {$allow_file_type}");
                }

                move_uploaded_file($_FILES[$myfile]['tmp_name'][$i], $target_save_file);

                $file[] = $target_file;
            } else {
                return_error("Không phải ảnh");
            }
        } else {

            return_error("Lỗi dữ liệu");
        }
    }
    if (isset($file) && !empty($file)) {
        return $file;
    }
}





function handing_file_img($myfile, $dir_save)
{    // myfile = file nhập vào, $max_size = kích thước lớn nhất của file, 
    // $allow_file_type = các đuôi file cho phép, $dir_save = thư mục lưu trữ
    if ($_FILES[$myfile]['error'] == 0) {
        $target_dir = $dir_save;
        $target_dir_4_upload = '../../' . $dir_save;
        $target_file = $target_dir . basename($_FILES[$myfile]['name']);
        $target_save_file = $target_dir_4_upload . basename($_FILES[$myfile]['name']);


        $allow_file_type = array('jpg', 'jpeg', 'png');
        $max_file_size = 5242880;
        $img_file_type = pathinfo($target_file, PATHINFO_EXTENSION);

        // kiem tra co phai file anh
        $check = getimagesize($_FILES[$myfile]['tmp_name']);
        if ($check !== false) {
            $img_info = pathinfo($_FILES[$myfile]['name']);
            if (file_exists($target_save_file)) {
                $k = 0;
                $name_copy = $img_info['filename'] . "_Copy_" . $k;
                $target_file = $target_dir . $name_copy . "." . $img_info['extension'];
                $target_save_file = $target_dir_4_upload . $name_copy . "." . $img_info['extension'];
                while (file_exists($target_save_file)) {
                    $k++;
                    $name_copy = $img_info['filename'] . "_Copy_" . $k;
                    $target_file = $target_dir . $name_copy . "." . $img_info['extension'];
                    $target_save_file = $target_dir_4_upload . $name_copy . "." . $img_info['extension'];
                }
            }

            if ($_FILES[$myfile]['size'] > $max_file_size) {
                return_error("file size is greater than {$max_file_size}");
            }

            if (!in_array(strtolower($img_file_type), $allow_file_type)) {
                return_error("file type is not allow, {$allow_file_type}");
            }

            move_uploaded_file($_FILES[$myfile]['tmp_name'], $target_save_file);
            // return_success($target_file);
            return $target_file;
        } else {
            return_error("Không phải ảnh");
        }
    } else {
        return_error("Lỗi dữ liệu");
    }
}


function db_assoc($result)
{
    return mysqli_fetch_assoc($result);
}
function db_nums($result)
{
    $nums = mysqli_num_rows($result);
    return $nums;
}

function db_qr($sql)
{
    global $conn;
    $result = mysqli_query($conn, $sql);
    if (!empty($result)) {
        return $result;
    }
    return false;
}

function errorToken($error_code, $msg = "", $data = array())
{
    echo json_encode(array(
        'success' => ($error_code == '4001')?'true':'false',
        'message' => $msg,
        'error_code' => $error_code,
        'data' => $data
    ));
    exit();
}
function return_error($message)
{
    echo json_encode(array(

        'success' => 'false',
        'message' => $message,
    ));
    exit();
}

function reJson($array)
{
    echo json_encode($array);
    exit();
}
function returnError($string)
{
    echo json_encode(
        array('success' => 'false', 'message' => $string)
    );
    exit();
}
function returnSuccess($string, $token = "")
{
    echo json_encode(
        array(
            'success' => 'true',
            'refresh_token' => $token,
            'message' => $string
        )
    );
    exit();
}

function getRolePermission($idUser = '')
{
    global $conn;
    $sql = "SELECT * FROM tbl_account_permission";

    if (!empty($idUser)) {
        $sql = " SELECT 
            tbl_account_permission.id,
            tbl_account_permission.permission,
            tbl_account_permission.description

            FROM tbl_account_permission
            LEFT JOIN tbl_account_authorize
            ON tbl_account_permission.id = tbl_account_authorize.grant_permission

            WHERE tbl_account_authorize.id_admin = '" . $idUser . "'
			
			ORDER BY tbl_account_authorize.grant_permission ASC
        ";
    }

    $result = mysqli_query($conn, $sql);
    // mysqli_close($conn);
    // Get row count
    $num = mysqli_num_rows($result);
    $arr_result = array();
    // Check if any item
    if ($num > 0) {

        while ($row = $result->fetch_assoc()) {

            $role_item = array(
                'id' => $row['id'],
                'permission' => $row['permission'],
                'description' => $row['description']
            );
            // Push to "data"
            array_push($arr_result, $role_item);
        }
    }

    return $arr_result;
}

function saveImage($file, $target_save = '')
{
    $link_image = '';
    if (isset($file) && is_uploaded_file($file['tmp_name'])) {
        // check file size (1048576: 1MB) 5242880

        if ($file['size'] >= 5242880) {
            //  returnError("only accept file size < 5MB!");

            return "error_size_img";
        }

        // check file type
        $allowedTypes = array(
            IMAGETYPE_PNG,
            IMAGETYPE_JPEG,
            IMAGETYPE_GIF
        );
        $detectedType = exif_imagetype($file['tmp_name']);
        $error = !in_array($detectedType, $allowedTypes);

        if ($error) {
            //returnError("only accept PNG, JPEG, GIF !");
            return "error_type_img";
        }

        $target_dir = $target_save;
        $target_dir_4_upload = '../../' . $target_save;
        $final_name = basename($file["name"]);

        $path = $file['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $final_name = generateRandomString(60) . '.' . $ext;

        // end handle way to rename

        while (file_exists($target_dir_4_upload . $final_name)) {
            // doi ten file
            $final_name = generateRandomString(60) . '.' . $ext;
        }

        // upload file toi folder icon
        $target_file_upload = $target_dir_4_upload . $final_name;
        $target_file = $target_dir . $final_name;

        move_uploaded_file($file["tmp_name"], $target_file_upload);

        $link_image = $target_file;
    }

    return $link_image;
}
function generateRandomString($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function pushNotification($title, $message, $action, $to, $type_send = 'topic', $server_key = 'AAAA0MM4ZB0:APA91bFYwE1Pusx0ftTdqeK3yKoiRU0DmhANyeYx4m74oQbP8UNnwP71c1K4PYmoszje9cl9SthdlyCy30KS1qTV8i088DGcA5jkacav0x56vO8mKldD6kxqIsNliORhtAdZfmepWQyj')
{
    $message_data = array(
        'title' => $title,
        'body' => $message,
        "click_action" => $action,
        "badge" => "1"
    );
    $headers = array(
        'Authorization: key=' . $server_key,
        'Content-Type: application/json'
    );

    $data = array();

    if (!empty($type_send) && $type_send == 'single') {
        require_once 'notification.php';
        $notification = new Notification();

        $notification->setTitle($title);
        $notification->setMessage($message);
        $notification->setAction($action);

        $requestData = $notification->getNotificatin();

        $data['to'] = $to;
        $data['data'] = $requestData;
    } else {
        $data['to'] = "/topics/" . $to;
        $data['notification'] = $message_data;
    }

    $data = json_encode($data);

    // print_r($data);
    // exit

    $url = 'https://fcm.googleapis.com/fcm/send';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    curl_close($ch);
}
