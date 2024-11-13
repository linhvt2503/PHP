<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

// Thêm tính năng
if(isset($_POST['add_feature'])) {
    error_log("Dữ liệu POST nhận được: " . print_r($_POST, true));
    $frm_data = filteration($_POST);
    error_log("Dữ liệu đã lọc: " . print_r($frm_data, true));

    $q = "INSERT INTO 'd_trung'('ten') VALUES (?)";
    $values = [$frm_data['name']];
    error_log("Truy vấn: " . $q);
    error_log("Giá trị: " . print_r($values, true));

    $res = insert($q, $values, 's');
    error_log("Kết quả chèn: " . $res);
    echo $res;
}

// Lấy danh sách tính năng
if(isset($_POST['get_features'])){
    $res = selectAll('d_trung');
    $i=1;

    while ($row = mysqli_fetch_assoc($res)){
        echo <<<data
                <tr>
                    <td>$i</td>
                    <td>$row[ten]</td>
                     <td>
                        <button type="button" onclick="rem_feature($row[id])" class="btn btn-danger btn-sm shadow-none">
                                <i class="bi bi-trash"></i>
                        </button>
                     </td>
                </tr>
            data;
        $i++;
    }
}

// Xóa tính năng
if(isset($_POST['rem_feature'])){
    $frm_data = filteration($_POST);
    $values = [$frm_data['rem_feature']];

    $q = "DELETE FROM 'd_trung' WHERE 'id'=?";
    $res = delete($q, $values, 'i');
    echo $res;
}

// Thêm tiện ích
if(isset($_POST['add_facility'])) {
    error_log("Dữ liệu POST nhận được cho tiện ích: " . print_r($_POST, true));
    error_log("Dữ liệu FILES nhận được: " . print_r($_FILES, true));

    $frm_data = filteration($_POST);

    $img_r = uploadSVGImage($_FILES['icon'], FACILITIES_FOLDER);
    error_log("Kết quả tải lên ảnh: " . $img_r);

    if($img_r == 'inv_img') {
        echo 'inv_img';
    }
    else if($img_r == 'inv_size') {
        echo 'inv_size';
    }
    else if($img_r == 'upd_failed') {
        echo 'upd_failed';
    }
    else {
        $q = "INSERT INTO 'tien_ich'('icon', 'ten', 'mo_ta') VALUES (?,?,?)";
        $values = [$img_r, $frm_data['name'], $frm_data['desc']];

        error_log("Truy vấn SQL: " . $q);
        error_log("Giá trị để chèn: " . print_r($values, true));

        try {
            $res = insert($q, $values, 'sss');
            error_log("Kết quả chèn: " . $res);
            echo $res;
        } catch (Exception $e) {
            error_log("Lỗi khi chèn tiện ích: " . $e->getMessage());
            echo "Lỗi: " . $e->getMessage();
        }
    }
}

// Lấy danh sách tiện ích
if(isset($_POST['get_facilities'])) {
    $res = selectAll('tien_ich');
    $i=1;
    $path = FACILITIES_IMG_PATH;

    while ($row = mysqli_fetch_assoc($res)){
        echo <<<data
                <tr class="align-middle">
                    <td>$i</td>
                    <td><img src="$path$row[icon]" width="100px"></td>
                    <td>$row[ten]</td>
                    <td>$row[mo_ta]</td>
                    <td>
                        <button type="button" onclick="rem_facility($row[id])" class="btn btn-danger btn-sm shadow-none">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            data;
        $i++;
    }
}

// Xóa tiện ích
if(isset($_POST['rem_facility'])){
    $frm_data = filteration($_POST);
    $values = [$frm_data['rem_facility']];

    $pre_q = "SELECT * FROM 'tien_ich' WHERE 'id'=?";
    $res = select($pre_q, $values, 'i');
    $img = mysqli_fetch_assoc($res);

    if(deleteImage($img['icon'], FACILITIES_FOLDER)){
        $q = "DELETE FROM 'tien_ich' WHERE 'id'=?";
        $res = delete($q, $values, 'i');
        echo $res;
    }
    else{
        echo 0;
    }
}
?>
