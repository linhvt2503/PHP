<?php
require ('inc/essentials.php');
require ('inc/db_config.php');
adminLogin();

// Xử lý POST request cho tất cả thao tác
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
    <title>Admin-User Queries</title>
    <?php require ('inc/links.php');?>
</head>
<body class="bg-light">
<?php require('inc/header.php');?>

<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto p-4 overflow-hidden">
            <h3 class="mb-4">USER QUERIES</h3>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">

                    <div class="text-end mb-4">
                        <form method="POST" style="display: inline;">
                            <button type="submit" name="seen_all" class="btn btn-dark rounded-pill shadow-none btn-sm">
                                <i class="bi bi-check2-all"></i> Mark all read
                            </button>
                        </form>
                        <form method="POST" style="display: inline;">
                            <button type="submit" name="delete_all" class="btn btn-danger rounded-pill shadow-none btn-sm"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa tất cả?')">
                                <i class="bi bi-trash"></i> Delete all
                            </button>
                        </form>
                    </div>

                    <div class="table-responsive-md" style="height: 150px; overflow-y: scroll;">
                        <table class="table table-hover border">
                            <thead class="sticky-top">
                            <tr class="bg-dark text-light">
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Message</th>
                                <th scope="col">Date</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $q = "SELECT * FROM `user_queries` ORDER BY `sr_no` DESC";
                            $data = mysqli_query($con,$q);
                            $i = 1;

                            while($row = mysqli_fetch_assoc($data)){
                                $seen='';
                                if($row['seen']!=1){
                                    $seen="<form method='POST' style='display:inline;'>
                                            <input type='hidden' name='sr_no' value='$row[sr_no]'>
                                            <button type='submit' name='seen_single' class='btn btn-sm rounded-pill btn-primary mb-2'>
                                                Mark as read
                                            </button>
                                        </form><br>";
                                }
                                $seen.="<form method='POST' style='display:inline;'>
                                        <input type='hidden' name='sr_no' value='$row[sr_no]'>
                                        <button type='submit' name='delete_single' class='btn btn-sm rounded-pill btn-danger ' 
                                            onclick=\"return confirm('Bạn có chắc chắn muốn xóa?')\">Delete</button>
                                    </form>";

                                echo<<<query
                                        <tr>
                                            <td>$i</td>
                                            <td>$row[name]</td>
                                            <td>$row[email]</td>
                                            <td>$row[subject]</td>
                                            <td>$row[message]</td>
                                            <td>$row[date]</td>
                                            <td>$seen</td>
                                        </tr>
                                    query;
                                $i++;
                            };
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require ('inc/scripts.php'); ?>
</body>
</html>