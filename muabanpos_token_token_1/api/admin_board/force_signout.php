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

if (isset($_REQUEST['target'])) {
    if ($_REQUEST['target'] == '') {
        unset($_REQUEST['target']);
    }
}

if (! isset($_REQUEST['target'])) {
    returnError("target is missing!");
}

$target = $_REQUEST['target'];

//push notity admin
$title = "Thông báo đăng nhập!!!";
$bodyMessage = "Phiên làm việc đã kết thúc, vui lòng đăng nhập lại để tiếp tục.";
$action = "check_sign_out";
$type_send = 'topic';
$to = 'muaban_pos_notification';
switch ($target) {
    case 'admin':
        $to = "muaban_pos_notification_admin";
        
        $query = "UPDATE tbl_account_account SET ";
        $query .= " force_sign_out  = '1' WHERE id_type = '1' AND `id_business` = '{$id_business}'";
        $conn->query($query);
        
        break;
    case 'employee':
        $to = "muaban_pos_notification_employee";
        
        $query = "UPDATE tbl_account_account SET ";
        $query .= " force_sign_out  = '1' WHERE id_type != '1' AND `id_business` = '{$id_business}'";
        $conn->query($query);
        
        break;
}

pushNotification($title, $bodyMessage, $action, $to, $type_send);
returnSuccess("Gửi thông báo thành công!");