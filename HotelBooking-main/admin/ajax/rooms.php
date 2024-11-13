<?php
error_reporting(E_ALL);
require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

//Add Room
if (isset($_POST['action']) && $_POST['action'] == 'add_room') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        error_log("CSRF validation failed");
        echo 0;
        exit;
    }

    // Validate and sanitize input
    try {
        $frm_data = filteration($_POST);
        $features = json_decode($_POST['features'], true);
        $facilities = json_decode($_POST['facilities'], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON data");
        }

        // Begin transaction
        mysqli_begin_transaction($GLOBALS['con']);

        $q1 = "INSERT INTO `rooms`(`name`, `area`, `price`, `quantity`, `adult`, `children`, `description`) 
               VALUES (?,?,?,?,?,?,?)";
        $values = [
            $frm_data['name'],
            $frm_data['area'],
            $frm_data['price'],
            $frm_data['quantity'],
            $frm_data['adult'],
            $frm_data['children'],
            $frm_data['desc']
        ];

        if (!insert($q1, $values, 'siiiiis')) {
            throw new Exception("Failed to insert room");
        }

        $room_id = mysqli_insert_id($GLOBALS['con']);

        // Insert facilities
        if (!empty($facilities)) {
            $q2 = "INSERT INTO `room_facilities` (`room_id`, `facilities_id`) VALUES (?,?)";
            $stmt2 = mysqli_prepare($GLOBALS['con'], $q2);

            if (!$stmt2) {
                throw new Exception("Failed to prepare facilities statement");
            }

            foreach ($facilities as $f) {
                mysqli_stmt_bind_param($stmt2, 'ii', $room_id, $f);
                if (!mysqli_stmt_execute($stmt2)) {
                    throw new Exception("Failed to insert facility");
                }
            }
            mysqli_stmt_close($stmt2);
        }

        // Insert features
        if (!empty($features)) {
            $q3 = "INSERT INTO `room_features` (`room_id`, `features_id`) VALUES (?,?)";
            $stmt3 = mysqli_prepare($GLOBALS['con'], $q3);

            if (!$stmt3) {
                throw new Exception("Failed to prepare features statement");
            }

            foreach ($features as $f) {
                mysqli_stmt_bind_param($stmt3, 'ii', $room_id, $f);
                if (!mysqli_stmt_execute($stmt3)) {
                    throw new Exception("Failed to insert feature");
                }
            }
            mysqli_stmt_close($stmt3);
        }

        // Commit transaction
        mysqli_commit($GLOBALS['con']);
        echo 1;

    } catch (Exception $e) {
        // Rollback on error
        mysqli_rollback($GLOBALS['con']);
        error_log("Error in add_room: " . $e->getMessage());
        echo 0;
    }
    exit;
}

if (isset($_POST['get_all_rooms'])) {
    $res = selectAll('rooms');
    $i = 1;
    $data = "";

    while ($row = mysqli_fetch_assoc($res)) {
        if($row['status']==1){
            $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>active</button>";
        }
        else{
            $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-warning btn-sm shadow-none'>inactive</button>";
        }

        $data .= "
            <tr class='align-middle'>
                <td>$i</td>
                <td>$row[name]</td>
                <td>$row[area]</td>
                <td>
                    <span class='badge rounded-pill bg-light text-dark'>
                        Người lớn: $row[adult]
                    </span><br>
                    <span class='badge rounded-pill bg-light text-dark'>
                        Trẻ em: $row[children]
                    </span>
                </td>
                <td>$row[price] VND</td>
                <td>$row[quantity]</td>
                <td>$status</td>
                <td>
                    <button type='button' onclick='edit_details($row[id])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-room'>
                        <i class='bi bi-pencil-square'></i>
                    </button>
                    <button type='button' onclick=\"room_images($row[id],'$row[name]')\" class='btn btn-info shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#room-images'>
                        <i class='bi bi-images'></i>
                    </button>
                    <button type='button' onclick='remove_room($row[id])' class='btn btn-danger shadow-none btn-sm'>
                        <i class='bi bi-trash'></i>
                    </button>
                </td>
            </tr>
        ";
        $i++;
    }
    echo $data;
}

//Edit Room
if(isset($_POST['get_room']))
{
    $frm_data = filteration($_POST);
    $res1 = select("SELECT * FROM `rooms` WHERE `id`=?",[$frm_data['get_room']],'i');
    $res2 = select("SELECT * FROM `room_features` WHERE `room_id`=?",[$frm_data['get_room']],'i');
    $res3 = select("SELECT * FROM `room_facilities` WHERE `room_id`=?",[$frm_data['get_room']],'i');

    $roomdata = mysqli_fetch_assoc($res1);
    $features = [];
    $facilities = [];

    if(mysqli_num_rows($res2)>0){
        while($row = mysqli_fetch_assoc($res2)){
            array_push($features,$row['features_id']);
        }
    }

    if(mysqli_num_rows($res3)>0){
        while($row = mysqli_fetch_assoc($res3)){
            array_push($facilities,$row['facilities_id']);
        }
    }

    $data = ["room" => $roomdata, "features" => $features, "facilities" => $facilities];

    echo json_encode($data);
}

if(isset($_POST['action']) && $_POST['action']=='edit_room')
{
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        error_log("CSRF validation failed");
        echo 0;
        exit;
    }

    $frm_data = filteration($_POST);
    $flag = true;

    try {
        // Start transaction
        mysqli_begin_transaction($GLOBALS['con']);

        $q1 = "UPDATE `rooms` SET `name`=?,`area`=?,`price`=?,`quantity`=?,
            `adult`=?,`children`=?,`description`=? WHERE `id`=?";

        $values = [
            $frm_data['name'],
            $frm_data['area'],
            $frm_data['price'],
            $frm_data['quantity'],
            $frm_data['adult'],
            $frm_data['children'],
            $frm_data['desc'],
            $frm_data['room_id']
        ];

        if(!update($q1,$values,'siiiiisi')){
            $flag = false;
            throw new Exception("Failed to update room details");
        }

        // Delete existing features and facilities
        $del_features = delete("DELETE FROM `room_features` WHERE `room_id`=?", [$frm_data['room_id']], 'i');
        $del_facilities = delete("DELETE FROM `room_facilities` WHERE `room_id`=?", [$frm_data['room_id']], 'i');

        if(!$del_features || !$del_facilities){
            $flag = false;
            throw new Exception("Failed to delete existing features/facilities");
        }

        // Insert new features
        $features = json_decode($_POST['features']);
        $q2 = "INSERT INTO `room_features`(`room_id`,`features_id`) VALUES (?,?)";

        if(!empty($features)){
            $stmt = mysqli_prepare($GLOBALS['con'], $q2);
            foreach($features as $f){
                mysqli_stmt_bind_param($stmt,'ii',$frm_data['room_id'],$f);
                if(!mysqli_stmt_execute($stmt)){
                    $flag = false;
                    throw new Exception("Failed to insert features");
                }
            }
            mysqli_stmt_close($stmt);
        }

        // Insert new facilities
        $facilities = json_decode($_POST['facilities']);
        $q3 = "INSERT INTO `room_facilities`(`room_id`,`facilities_id`) VALUES (?,?)";

        if(!empty($facilities)){
            $stmt = mysqli_prepare($GLOBALS['con'], $q3);
            foreach($facilities as $f){
                mysqli_stmt_bind_param($stmt,'ii',$frm_data['room_id'],$f);
                if(!mysqli_stmt_execute($stmt)){
                    $flag = false;
                    throw new Exception("Failed to insert facilities");
                }
            }
            mysqli_stmt_close($stmt);
        }

        if($flag){
            mysqli_commit($GLOBALS['con']);
            echo 1;
        }
        else {
            throw new Exception("Operation failed");
        }

    } catch(Exception $e) {
        mysqli_rollback($GLOBALS['con']);
        error_log("Error in edit_room: " . $e->getMessage());
        echo 0;
    }
}

if (isset($_POST['toggle_status'])) {
    $frm_data = filteration($_POST);

    $q = "UPDATE `rooms` SET `status`=? WHERE `id`=?";
    $v = [$frm_data['value'], $frm_data['toggle_status']];

    if(update($q, $v, 'ii')){
        echo 1;
    } else {
        echo 0;
    }
}

//Add Image
if(isset($_POST['add_image'])){

    $frm_data = filteration($_POST);

    $img_r = uploadImage($_FILES['image'],ROOMS_FOLDER);

    if($img_r == 'inv_img') {
        echo $img_r;
    }
    else if($img_r == 'inv_size') {
        echo $img_r;
    }
    else if ($img_r == 'upd_failed') {
        echo $img_r;
    }
    else{
        $q = "INSERT INTO `room_images`(`room_id`, `image`) VALUES (?,?)";
        $values = [$frm_data['room_id'], $img_r];
        $res = insert($q, $values, 'is');
        echo $res;
    }
}

//Get image
if(isset($_POST['get_room_images'])){

    $frm_data = filteration($_POST);
    $res = select("SELECT * FROM `room_images` WHERE `room_id`=?",[$frm_data['get_room_images']],'i');

    $path = ROOMS_IMG_PATH;

    while($row = mysqli_fetch_assoc($res)){
        if($row['thumb']==1){
            $thumb_btn = "<i class='bi bi-check-lg text-light bg-success px-2 py-1 rounded fs-5'></i>";
        }else{
            $thumb_btn = "<button onclick='thumb_image($row[sr_no],$row[room_id])' class='btn btn-secondary shadow-none'>
                    <i class='bi bi-check-lg'></i>
                    </button>";
        }
        echo <<< data
            <tr class='align-middle'>
                <td><img src='$path$row[image]' class='img-fluid'></td>
                <td>$thumb_btn</td>
                <td>
                   <button onclick='rem_image($row[sr_no],$row[room_id])' class='btn btn-danger shadow-none'>
                    <i class='bi bi-trash'></i>
                    </button>
                </td>
            </tr>
        data;

    }
}

//Remove Image
if(isset($_POST['rem_image'])){
    $frm_data = filteration($_POST);

    // Debug logging
    error_log("Image ID: " . $frm_data['image_id']);
    error_log("Room ID: " . $frm_data['room_id']);

    $values = [$frm_data['image_id'],$frm_data['room_id']];

    // Check if image exists
    $pre_q = "SELECT * FROM `room_images` WHERE `sr_no`=? AND `room_id`=?";
    $res = select($pre_q,$values,'ii');

    // Debug logging for query result
    error_log("Query result: " . ($res ? "Success" : "Failed"));
    error_log("Number of rows: " . ($res ? mysqli_num_rows($res) : 0));

    // If image not found
    if(!$res || mysqli_num_rows($res) == 0) {
        error_log("Image not found in database");
        echo 0;
        exit;
    }

    $img = mysqli_fetch_assoc($res);

    // Debug logging for image path
    error_log("Attempting to delete image: " . $img['image']);

    if(deleteImage($img['image'], ROOMS_FOLDER)){
        $q = "DELETE FROM `room_images` WHERE `sr_no`=? AND `room_id`=?";
        $res = delete($q,$values,'ii');

        // Debug logging for delete operation
        error_log("Delete query result: " . ($res ? "Success" : "Failed"));

        echo $res;
    }
    else{
        error_log("Failed to delete image file");
        echo 0;
    }
}

//Thumbnail Image
if(isset($_POST['thumb_image'])){
    $frm_data = filteration($_POST);

    $pre_q = "UPDATE `room_images` SET `thumb`=? WHERE `room_id`=?";
    $pre_v = [0,$frm_data['room_id']];
    $pre_res = update($pre_q,$pre_v,'ii');

    $q = "UPDATE `room_images` SET `thumb`=? WHERE `sr_no`=? AND `room_id`=?";
    $v = [1,$frm_data['image_id'],$frm_data['room_id']];
    $res = update($q,$v,'iii');

    echo $res;
}

//Trash Rooms
if(isset($_POST['remove_room']))
{
    $frm_data = filteration($_POST);
    try {
        // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
        mysqli_begin_transaction($GLOBALS['con']);

        // Lấy và xóa tất cả hình ảnh của phòng từ thư mục uploads
        $res1 = select("SELECT * FROM `room_images` WHERE `room_id`=?",[$frm_data['room_id']],'i');

        while($row = mysqli_fetch_assoc($res1)){
            deleteImage($row['image'],ROOMS_FOLDER);
        }

        // Xóa tất cả bản ghi hình ảnh của phòng trong database
        $q1 = "DELETE FROM `room_images` WHERE `room_id`=?";
        $res1 = delete($q1, [$frm_data['room_id']], 'i');

        // Xóa tất cả tiện nghi của phòng
        $q2 = "DELETE FROM `room_facilities` WHERE `room_id`=?";
        $res2 = delete($q2, [$frm_data['room_id']], 'i');

        // Xóa tất cả tính năng của phòng
        $q3 = "DELETE FROM `room_features` WHERE `room_id`=?";
        $res3 = delete($q3, [$frm_data['room_id']], 'i');

        // Cuối cùng xóa thông tin phòng từ bảng chính
        $q4 = "DELETE FROM `rooms` WHERE `id`=?";
        $res4 = delete($q4, [$frm_data['room_id']], 'i');

        // Nếu tất cả các thao tác trên thành công thì commit transaction
        mysqli_commit($GLOBALS['con']);
        echo 1; // Trả về 1 để thông báo xóa thành công

    } catch(Exception $e) {
        // Nếu có lỗi thì rollback lại toàn bộ thay đổi
        mysqli_rollback($GLOBALS['con']);
        // Ghi log lỗi để debug
        error_log("Lỗi khi xóa phòng: " . $e->getMessage());
        echo 0; // Trả về 0 để thông báo xóa thất bại
    }
}
?>

