<?php

require ('inc/essentials.php');
require ('inc/db_config.php');
adminLogin();

// Tạo CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF token for all POST requests
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    if (isset($_POST['seen_all'])) {
        $q = "UPDATE `user_queries` SET `seen`=?";
        $values = [1];
        if (update($q, $values, 'i')) {
            alert('success', 'Marked all as read');
        } else {
            alert('error', 'Operation Failed');
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['seen_single'])) {
        $sr_no = filter_var($_POST['sr_no'], FILTER_VALIDATE_INT);
        if ($sr_no) {
            $q = "UPDATE `user_queries` SET `seen`=? WHERE `sr_no`=?";
            $values = [1, $sr_no];
            if (update($q, $values, 'ii')) {
                alert('success', 'Marked as read');
            } else {
                alert('error', 'Operation Failed');
            }
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['delete_all'])) {
        $stmt = mysqli_prepare($GLOBALS['con'], "DELETE FROM `user_queries`");
        if (mysqli_stmt_execute($stmt)) {
            alert('success', 'Đã xóa tất cả');
        } else {
            alert('error', 'Xóa thất bại');
        }
        mysqli_stmt_close($stmt);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['delete_single'])) {
        $sr_no = filter_var($_POST['sr_no'], FILTER_VALIDATE_INT);
        if ($sr_no) {
            $q = "DELETE FROM `user_queries` WHERE `sr_no`=?";
            $values = [$sr_no];
            if (delete($q, $values, 'i')) {
                alert('success', 'Đã xóa');
            } else {
                alert('error', 'Xóa thất bại');
            }
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] == 'add_room') {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            error_log("CSRF token validation failed");
            echo 0;
            exit;
        }

        // Log request data
        error_log("Received POST data: " . print_r($_POST, true));

        // Validate required fields
        $required_fields = ['name', 'area', 'price', 'quantity', 'adult', 'children', 'desc'];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                error_log("Missing required field: $field");
                echo 0;
                exit;
            }
        }

        $name = mysqli_real_escape_string($GLOBALS['con'], $_POST['name']);
        $area = filter_var($_POST['area'], FILTER_VALIDATE_INT);
        $price = filter_var($_POST['price'], FILTER_VALIDATE_INT);
        $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);
        $adult = filter_var($_POST['adult'], FILTER_VALIDATE_INT);
        $children = filter_var($_POST['children'], FILTER_VALIDATE_INT);
        $desc = mysqli_real_escape_string($GLOBALS['con'], $_POST['desc']);

        $features = json_decode($_POST['features']);
        $facilities = json_decode($_POST['facilities']);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 0;
            exit;
        }

        if (!$area || !$price || !$quantity || !$adult || !$children) {
            echo 0;
            exit;
        }

        $q = "INSERT INTO `rooms`(`name`, `area`, `price`, `quantity`, `adult`, `children`, `description`) 
              VALUES (?,?,?,?,?,?,?)";
        $values = [$name, $area, $price, $quantity, $adult, $children, $desc];

        if (insert($q, $values, 'siiiiis')) {
            $room_id = $GLOBALS['con']->insert_id;

            // Insert features
            $q_feature = "INSERT INTO `room_features`(`room_id`, `features_id`) VALUES (?,?)";
            foreach ($features as $f) {
                $f = filter_var($f, FILTER_VALIDATE_INT);
                if ($f) {
                    $values = [$room_id, $f];
                    insert($q_feature, $values, 'ii');
                }
            }

            // Insert facilities
            $q_facility = "INSERT INTO `room_facilities`(`room_id`, `facilities_id`) VALUES (?,?)";
            foreach ($facilities as $f) {
                $f = filter_var($f, FILTER_VALIDATE_INT);
                if ($f) {
                    $values = [$room_id, $f];
                    insert($q_facility, $values, 'ii');
                }
            }

            echo 1;
        } else {
            echo 0;
        }
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin-Rooms</title>
    <?php require ('inc/links.php');?>
</head>
<body class="bg-light">
<?php require('inc/header.php');?>

<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto p-4 overflow-hidden">
            <h3 class="mb-4">Rooms</h3>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="text-end mb-4">
                        <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-room">
                            <i class="bi bi-plus-square"></i> Add
                        </button>
                    </div>
                    <div class="table-responsive-lg" style="height: 450px; overflow-y: scroll;">
                        <table class="table table-hover border text-center">
                            <thead class="sticky-top">
                            <tr class="bg-dark text-light">
                                <th scope="col">#</th>
                                <th scope="col">Tên</th>
                                <th scope="col">Khu vực</th>
                                <th scope="col">Khách</th>
                                <th scope="col">Giá tiền</th>
                                <th scope="col">Số lượng</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Tùy chọn</th>
                            </tr>
                            </thead>
                            <tbody id="room-data">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Add room Modal -->
<div class="modal fade" id="add-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="add_room_form" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Room</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" for="name">Tên</label>
                            <input type="text" id="name" name="name" class="form-control shadow-none" autocomplete="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" for="area">Khu vực</label>
                            <input type="number" min="1" id="area" name="area" class="form-control shadow-none" autocomplete="area" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" for="price">Giá tiền</label>
                            <input type="number" min="1" id="price" name="price" class="form-control shadow-none" autocomplete="price" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" for="quantity">Số lượng</label>
                            <input type="number" min="1" id="quantity" name="quantity" class="form-control shadow-none" autocomplete="quantity" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" for="adult">Người lớn (Max.)</label>
                            <input type="number" min="1" id="adult" name="adult" class="form-control shadow-none" autocomplete="adult" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" for="children">Trẻ em (Max.)</label>
                            <input type="number" min="1" id="children" name="children" class="form-control shadow-none" autocomplete="children" required>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Đặc trưng</label>
                        <div class="row">
                            <?php
                            $res = selectAll('features');
                            while($opt = mysqli_fetch_assoc($res)){
                                echo "
                                        <div class='col-md-3 mb-1'>
                                            <label>
                                                <input type='checkbox' name='features' value='$opt[id]' class='form-check-input shadow-none'>
                                                $opt[name]
                                            </label>
                                        </div>
                                        ";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Tiện ích</label>
                        <div class="row">
                            <?php
                            $res = selectAll('facilities');
                            while($opt = mysqli_fetch_assoc($res)){
                                echo "
                                        <div class='col-md-3 mb-1'>
                                            <label>
                                                <input type='checkbox' name='facilities' value='$opt[id]' class='form-check-input shadow-none'>
                                                $opt[name]
                                            </label>
                                        </div>
                                        ";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold" for="description">Description</label>
                        <textarea id="desc" name="desc" rows="4" class="form-control shadow-none" autocomplete="desc" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_room" class="btn custom-bg text-white shadow-none">Submit</button>
                </div>

            </div>
        </form>
    </div>
</div>


<!-- Edit room Modal -->
<div class="modal fade" id="edit-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="edit_room_form" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Room</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="room_id" id="edit_room_id">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tên</label>
                            <input type="text" name="name" id="edit_name" class="form-control shadow-none" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Khu vực</label>
                            <input type="number" min="1" name="area" id="edit_area" class="form-control shadow-none" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Giá tiền</label>
                            <input type="number" min="1" name="price" id="edit_price" class="form-control shadow-none" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Số lượng</label>
                            <input type="number" min="1" name="quantity" id="edit_quantity" class="form-control shadow-none" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Người lớn (Max.)</label>
                            <input type="number" min="1" name="adult" id="edit_adult" class="form-control shadow-none" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Trẻ em (Max.)</label>
                            <input type="number" min="1" name="children" id="edit_children" class="form-control shadow-none" required>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Đặc trưng</label>
                        <div class="row" id="edit_features">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <?php
                            $res = selectAll('features');
                            while($opt = mysqli_fetch_assoc($res)){
                                echo "
                                    <div class='col-md-3 mb-1'>
                                        <label>
                                            <input type='checkbox' name='features' value='$opt[id]' class='form-check-input shadow-none'>
                                            $opt[name]
                                        </label>
                                    </div>
                                ";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Tiện ích</label>
                        <div class="row" id="edit_facilities">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <?php
                            $res = selectAll('facilities');
                            while($opt = mysqli_fetch_assoc($res)){
                                echo "
                                    <div class='col-md-3 mb-1'>
                                        <label>
                                            <input type='checkbox' name='facilities' value='$opt[id]' class='form-check-input shadow-none'>
                                            $opt[name]
                                        </label>
                                    </div>
                                ";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Mô tả</label>
                        <textarea name="desc" id="edit_desc" rows="4" class="form-control shadow-none" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn custom-bg text-white shadow-none">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Manage room iamges modal -->
<div class="modal fade" id="room-images" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title">Room Name</h1>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="image-alert"></div>
                <div class="border-bottom border-3 pb-3 mb-3">
                    <form id="add_image_form">
                        <label class="form-label fw-bold">Add Image</label>
                        <input type="file" name="image" accept=".jpg, .png, .webp, .jpeg, .jfif" class="form-control shadow-none mb-3" required>
                        <button  class="btn custom-bg text-white shadow-none">Add</button>
                        <input type="hidden" name="room_id">
                    </form>
                </div>
                <div class="table-responsive-lg" style="height: 350px; overflow-y: scroll;">
                    <table class="table table-hover border text-center">
                        <thead class="sticky-top">
                            <tr class="bg-dark text-light sticky-top">
                                <th scope="col" width="60%">Image</th>
                                <th scope="col">Thumbnail</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody id="room-image-data">
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require ('inc/scripts.php'); ?>

<script>
    let add_room_form = document.getElementById('add_room_form');

    function alert(type, msg) {
        let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
        let element = document.createElement('div');
        element.innerHTML = `
            <div class="alert ${bs_class} alert-dismissible fade show custom-alert" role="alert">
                <strong>${msg}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        document.body.append(element);
        setTimeout(() => element.remove(), 2000);
    }

    add_room_form.addEventListener('submit', function(e) {
        e.preventDefault();
        add_room();
    });

    <!--Add Room-->
    function add_room(){
        let data = new FormData(add_room_form);
        data.append('action', 'add_room');

        // Validate required fields
        const requiredFields = ['name', 'area', 'price', 'quantity', 'adult', 'children', 'desc'];
        for (let field of requiredFields) {
            if (!data.get(field)) {
                alert('error', `Please fill in the ${field} field`);
                return;
            }
        }

        // Validate numeric fields
        const numericFields = ['area', 'price', 'quantity', 'adult', 'children'];
        for (let field of numericFields) {
            if (isNaN(data.get(field)) || data.get(field) <= 0) {
                alert('error', `${field} must be a positive number`);
                return;
            }
        }

        let features = [];
        let facilities = [];

        document.querySelectorAll('input[name="features"]:checked').forEach(el => {
            features.push(el.value);
        });

        document.querySelectorAll('input[name="facilities"]:checked').forEach(el => {
            facilities.push(el.value);
        });

        data.append('features', JSON.stringify(features));
        data.append('facilities', JSON.stringify(facilities));

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);

        xhr.onload = function() {
            try {
                var myModal = document.getElementById('add-room');
                var modal = bootstrap.Modal.getInstance(myModal);

                if (this.responseText == 1) {
                    alert('success', 'Phòng đã được thêm!');
                    add_room_form.reset();
                    modal.hide();
                    get_all_rooms();
                } else {
                    alert('error', 'Không thể thêm phòng!');
                }
            } catch (e) {
                console.error('Error:', e);
                alert('error', 'An unexpected error occurred');
            }
        }

        xhr.onerror = function() {
            alert('error', 'Connection error occurred!');
        }

        xhr.send(data);
    }

    function get_all_rooms(){
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            document.getElementById('room-data').innerHTML = this.responseText;
        }

        xhr.onerror = function() {
            alert('error', 'Có lỗi xảy ra khi tải dữ liệu!');
        }

        xhr.send('get_all_rooms=1&csrf_token=<?php echo $_SESSION['csrf_token']; ?>');
    }

    <!--Edit room-->
    let edit_room_form = document.getElementById('edit_room_form');

    function edit_details(id) {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            try {
                let data = JSON.parse(this.responseText);

                if (!data || !data.room) {
                    alert('error', 'Failed to load room data');
                    return;
                }

                // Populate basic room details
                document.getElementById('edit_room_id').value = data.room.id;
                document.getElementById('edit_name').value = data.room.name;
                document.getElementById('edit_area').value = data.room.area;
                document.getElementById('edit_price').value = data.room.price;
                document.getElementById('edit_quantity').value = data.room.quantity;
                document.getElementById('edit_adult').value = data.room.adult;
                document.getElementById('edit_children').value = data.room.children;
                document.getElementById('edit_desc').value = data.room.description;

                // Reset all checkboxes first
                document.querySelectorAll('#edit_features input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
                document.querySelectorAll('#edit_facilities input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Check features
                if (Array.isArray(data.features)) {
                    data.features.forEach(featureId => {
                        let checkbox = document.querySelector(`#edit_features input[value="${featureId}"]`);
                        if (checkbox) {
                            checkbox.checked = true;
                        }
                    });
                }

                // Check facilities
                if (Array.isArray(data.facilities)) {
                    data.facilities.forEach(facilityId => {
                        let checkbox = document.querySelector(`#edit_facilities input[value="${facilityId}"]`);
                        if (checkbox) {
                            checkbox.checked = true;
                        }
                    });
                }

            } catch (e) {
                console.error('Error:', e);
                alert('error', 'Failed to parse room data');
            }
        }

        xhr.onerror = function() {
            alert('error', 'Connection error occurred!');
        }

        xhr.send('get_room=' + id + '&csrf_token=' + document.querySelector('input[name="csrf_token"]').value);
    }

    function submit_edit_room() {
        let form = document.getElementById('edit_room_form');
        let data = new FormData(form);

        // Get selected features
        let features = [];
        form.querySelectorAll('#edit_features input[type="checkbox"]:checked').forEach(checkbox => {
            features.push(checkbox.value);
        });

        // Get selected facilities
        let facilities = [];
        form.querySelectorAll('#edit_facilities input[type="checkbox"]:checked').forEach(checkbox => {
            facilities.push(checkbox.value);
        });

        // Add to form data
        data.append('features', JSON.stringify(features));
        data.append('facilities', JSON.stringify(facilities));
        data.append('action', 'edit_room');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);

        xhr.onload = function() {
            if (this.status === 200) {
                try {
                    if (this.responseText == 1) {
                        alert('success', 'Cật nhật phòng thành công!');
                        let modal = bootstrap.Modal.getInstance(document.getElementById('edit-room'));
                        modal.hide();
                        get_all_rooms();
                    } else {
                        alert('error', 'Cật nhật phòng thất bại');
                        console.error('Server response:', this.responseText);
                    }
                } catch (e) {
                    console.error('Error:', e);
                    alert('error', 'An error occurred while processing the response');
                }
            }
        }

        xhr.onerror = function() {
            alert('error', 'Connection error occurred!');
        }

        xhr.send(data);
    }
    // Add event listener to form
    document.getElementById('edit_room_form').addEventListener('submit', function(e) {
        e.preventDefault();
        submit_edit_room();
    });

    function toggle_status(id,val){
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            if(this.responseText==1){
                alert('success','Đã chuyển đổi trạng thái!');
                get_all_rooms();
            }
            else{
                alert('success','Server Down!');
            }
        }
        xhr.send('toggle_status='+id+'&value='+val+'&csrf_token=<?php echo $_SESSION['csrf_token']; ?>');
    }

    <!--Up Images room-->
    let add_image_form = document.getElementById('add_image_form');

    add_image_form.addEventListener('submit',function (e){
        e.preventDefault();
        add_image();
    });
    <!--Add Images room-->
    function add_image(){
        let data = new FormData();
        data.append('image',add_image_form.elements['image'].files[0]);
        data.append('room_id',add_image_form.elements['room_id'].value);
        data.append('add_image','');

        let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/rooms.php",true);

        xhr.onload = function() {
            console.log(this.responseText);

            if(this.responseText == 'inv_img'){
                alert('error','Chỉ nhận file ảnh JPG, WEBP và PNG!','image-alert');
            }
            else if (this.responseText == 'inv_size'){
                alert('error','Chỉ cho ảnh nhỏ hơn 2MB!','image-alert');
            }
            else if (this.responseText == 'upd_failed'){
                alert('error','Tải ảnh thất bại!','image-alert');
            }
            else{
                alert('success','Ảnh đã thêm thành công!','image-alert');
                room_images(add_image_form.elements['room_id'].value,document.querySelector("#room-images .modal-title").innerText);
                add_image_form.reset();
            }
        }

        xhr.send(data);
    }

    function room_images(id,rname){
        document.querySelector("#room-images .modal-title").innerText = rname;
        add_image_form.elements['room_id'].value=id;
        add_image_form.elements['image'].value= '';

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms.php", true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            document.getElementById('room-image-data').innerHTML = this.responseText;

        }
        xhr.send('get_room_images='+id);
    }

    <!--Remove Images room-->
    function rem_image(img_id,room_id){
        let data = new FormData();
        data.append('image_id',img_id);
        data.append('room_id',room_id);
        data.append('rem_image','');

        let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/rooms.php",true);

        xhr.onload = function() {
            console.log(this.responseText);

            if(this.responseText == 1){
                alert('success','Ảnh đã được xóa!','image-alert');
                room_images(room_id,document.querySelector("#room-images .modal-title").innerText);

            }

            else{
                alert('error','Xóa ảnh thất bại!','image-alert');
            }
        }
        xhr.send(data);
    }

    <!--Thumb Images room-->
    function thumb_image(img_id,room_id){
        let data = new FormData();
        data.append('image_id',img_id);
        data.append('room_id',room_id);
        data.append('thumb_image','');

        let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/rooms.php",true);

        xhr.onload = function() {
            console.log(this.responseText);

            if(this.responseText == 1){
                alert('success','Ảnh nền đã được thêm!','image-alert');
                room_images(room_id,document.querySelector("#room-images .modal-title").innerText);

            }

            else{
                alert('error','Xóa ảnh nền thất bại!','image-alert');
            }
        }
        xhr.send(data);
    }

    <!--Trash room-->
    function remove_room(room_id){
        if(confirm("Bạn có chắc chắn sẽ xóa chứ?")){
            let data = new FormData();
            data.append('room_id',room_id);
            data.append('remove_room','');

            let xhr = new XMLHttpRequest();
            xhr.open("POST","ajax/rooms.php",true);

            xhr.onload = function() {
                console.log(this.responseText);

                if(this.responseText == 1){
                    alert('success','Đã xóa phòng!');
                    get_all_rooms();
                }
                else{
                    alert('error','Xóa phòng thất bại!');
                }
            }
            xhr.send(data);
        }
    }

    window.onload = function (){
        get_all_rooms();
    }

</script>
</body>
</html>