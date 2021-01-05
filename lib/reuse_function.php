<?php
// xu ly hinh anh

function handing_files_img($myfile, $dir_save)
{   // myfile = file nhập vào, $max_size = kích thước lớn nhất của file, 
    // $allow_file_type = các đuôi file cho phép, $dir_save = thư mục lưu trữ
    $total = count($_FILES[$myfile]['name']);
    for ($i = 0; $i < $total; $i++) {
        if ($_FILES[$myfile]['error'][$i] == 0) {
            $target_dir = $dir_save;
            $target_dir_4_upload = '../' . $dir_save;
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
        $target_dir_4_upload = '../' . $dir_save;
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

// function check_img_name($target_save_file, $img_info, $target_dir, $target_dir_4_upload){
//     if (file_exists($target_save_file)) {
//         $k = 0;
//         $name_copy = $img_info['filename'] . "_Copy_" . $k;
//         $target_file = $target_dir . $name_copy . "." . $img_info['extension'];
//         $target_save_file = $target_dir_4_upload . $name_copy . "." . $img_info['extension'];
//         while (file_exists($target_save_file)) {
//             $k++;
//             $name_copy = $img_info['filename'] . "_Copy_" . $k;
//             $target_file = $target_dir . $name_copy . "." . $img_info['extension'];
//             $target_save_file = $target_dir_4_upload . $name_copy . "." . $img_info['extension'];
//         }
//     }
// }

function db_assoc($result){
    return mysqli_fetch_assoc($result);
}
function db_nums($result){
    $nums = mysqli_num_rows($result);
    return $nums;
}

function db_qr($sql){
    global $conn;
    $result = mysqli_query($conn, $sql);
    if(!empty($result)){
        return $result;
    }
    return false;
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
function returnSuccess($string)
{
    echo json_encode(
        array('success' => 'true', 'message' => $string)
    );
    exit();
}

function getRolePermission($idUser = '')
{
    global $conn;
    $sql = "SELECT * FROM tbl_account_permission";
    
    if (! empty($idUser)) {
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
        $target_dir_4_upload = '../' . $target_save;
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
