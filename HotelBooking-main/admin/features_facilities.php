<?php
require ('inc/essentials.php');
require ('inc/db_config.php');
adminLogin();


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['seen_all'])) {
        $q = "UPDATE `user_queries` SET `seen`=?";
        $values = [1];
        if(update($q,$values,'i')){
            alert('success','Marked all as read');
        }
        else{
            alert('error','Operation Failed');
        }
        // Redirect sau khi thực hiện xong
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

    if(isset($_POST['seen_single'])) {
        $sr_no = $_POST['sr_no'];
        $q = "UPDATE `user_queries` SET `seen`=? WHERE `sr_no`=?";
        $values = [1,$sr_no];
        if(update($q,$values,'ii')){
            alert('success','Marked as read');
        }
        else{
            alert('error','Operation Failed');
        }
        // Redirect sau khi thực hiện xong
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

    if(isset($_POST['delete_all'])) {
        $q = "DELETE FROM `user_queries`";
        if(mysqli_query($con,$q)){
            alert('success','Đã xóa tất cả');
        }
        else{
            alert('error','Xóa thất bại');
        }
        // Redirect sau khi thực hiện xong
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

    if(isset($_POST['delete_single'])) {
        $sr_no = $_POST['sr_no'];
        if(is_numeric($sr_no)) {
            $q = "DELETE FROM `user_queries` WHERE `sr_no`=?";
            $values = [$sr_no];
            if(delete($q,$values,'i')){
                alert('success','Đã xóa');
            }
            else{
                alert('error','Xóa thất bại');
            }
            // Redirect sau khi thực hiện xong
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        }
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
    <title>Admin-Features & Facilities</title>
    <?php require ('inc/links.php');?>
</head>
<body class="bg-light">
<?php require('inc/header.php');?>

<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto p-4 overflow-hidden">
            <h3 class="mb-4">FEATURES & FACILITIES</h3>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-center justify-content-between mb-3">
                        <h5 class="card-title m-0">Features</h5>
                        <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#feature-s">
                            <i class="bi bi-plus-square"></i> Add
                        </button>
                    </div>
                    <div class="table-responsive-md" style="height: 350px; overflow-y: scroll;">
                        <table class="table table-hover border">
                            <thead class="sticky-top">
                            <tr class="bg-dark text-light">
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody id="features_data">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-center justify-content-between mb-3">
                        <h5 class="card-title m-0">Facilities</h5>
                        <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#facility-s">
                            <i class="bi bi-plus-square"></i> Add
                        </button>
                    </div>
                    <div class="table-responsive-md" style="height: 350px; overflow-y: scroll;">
                        <table class="table table-hover border">
                            <thead class="sticky-top">
                            <tr class="bg-dark text-light">
                                <th scope="col">#</th>
                                <th scope="col">Icon</th>
                                <th scope="col">Name</th>
                                <th scope="col" width="40%">Description</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody id="facilities_data">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

    <!-- Features Modal -->
    <div class="modal fade" id="feature-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="feature_s_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Feature</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="feature_name" class="form-control shadow-none" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn custom-bg text-white sahdow-none">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!--Facility Modal-->
    <div class="modal fade" id="facility-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="facility_s_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Facility</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name</label>
                        <input type="text" name="facility_name" class="form-control shadow-none" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Icon</label>
                        <input type="file" name="facility_icon" accept=".svg" class="form-control shadow-none" required>
                    </div>
                    <div class="mb-3">
                        <label classs="form-label">Description</label>
                        <textarea name="facility_desc" class="form-control shadow-none" rows="3" id=""></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn custom-bg text-white sahdow-none">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>





<?php require ('inc/scripts.php'); ?>
<script src="scripts/features_facilities.js"></script>


</body>
</html>