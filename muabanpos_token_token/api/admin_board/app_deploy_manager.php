<?php
if (isset($_REQUEST['type_manager'])) {
    if ($_REQUEST['type_manager'] == '') {
        unset($_REQUEST['type_manager']);
    }
}

if (! isset($_REQUEST['type_manager'])) {
    returnError("type_manager is missing!");
}

$typeManager = $_REQUEST['type_manager'];

switch ($typeManager) {
        
    case 'update_live_version':
        
        if (isset($_REQUEST['live_version'])) {
            if ($_REQUEST['live_version'] == '') {
                unset($_REQUEST['live_version']);
                returnError("Nhập live_version!");
            } else {
                $live_version = $_REQUEST['live_version'];
            }
        }else{
            returnError("Nhập live_version!");
        }
		$id = '';
        if (isset($_REQUEST['id']) && ! empty($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
        }
        
        $query = "UPDATE tbl_app_deploy SET ";
        $query .= " live_version  = '" . $live_version . "' ";
		if (!empty($id)){
            $query .= " WHERE id = '".$id."'";
        }
        // Create post
        if ($conn->query($query)) {
            returnSuccess("Cập nhật thành công!");
        }
        
        break;

        // tbl_employee_profile
    // case 'delete_apple_test_employee':
        
    //     $sql_check_test_employee_exists = "SELECT * FROM tbl_employee_profile WHERE live_version = '0'";
        
    //     $result_check = mysqli_query($conn, $sql_check_test_employee_exists);
    //     $num_result_check = mysqli_num_rows($result_check);
        
    //     if ($num_result_check > 0) {
            
    //         $sql_delete_test_employee = "
    //                         DELETE FROM tbl_employee_profile
    //                         WHERE  live_version = '0'
    //                       ";
    //         if ($conn->query($sql_delete_test_employee)) {
    //             returnSuccess("Xóa test_employee thành công!");
    //         } else {
    //             returnError("Xóa test_employee không thành công!");
    //         }
    //     }else{
    //         returnError("Không tồn tại test_employee!");
    //     }
        
    //     break;
        // 
    case 'check_app_deploy_version':
        $sql_check_deploy_version = "SELECT * FROM tbl_app_deploy";
		$id = '';
        if (isset($_REQUEST['id']) && ! empty($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
        }
        if (!empty($id)){
            $sql_check_deploy_version .= " WHERE id = '".$id."'";
        }
        $result = $conn->query($sql_check_deploy_version);
        
        // Get row count
        $num = mysqli_num_rows($result);
        
        $result_arr = array();
        $result_arr['success'] = 'true';
        $result_arr['data'] = array();
        
        if ($num > 0) {
            while ($row = $result->fetch_assoc()) {
                $version_item = array(
                    'live_version' => $row['live_version']
                );
                
                // Push to "data"
                array_push($result_arr['data'], $version_item);
            }
        }
        
        // Turn to JSON & output
        reJson($result_arr);
        
        break;
        
        
    default:
        returnError("type_manager is not accept!");
        break;
}