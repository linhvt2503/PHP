<?php
require ('inc/essentials.php');
require ('inc/db_config.php');
adminLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin-Dashboard</title>
    <?php require ('inc/links.php');?>
</head>
<body class="bg-light">
<?php

    require('inc/header.php');
    $is_shutdown = mysqli_fetch_assoc(mysqli_query($con,"SELECT `shutdown` FROM `settings`"));

//    $current_booking = mysqli_fetch_assoc(mysqli_query($con,"SELECT
//        COUNT(CASE WHEN booking_status='booked' AND arrival=0 THEN 1 END) AS `new_bookings`,
//        COUNT(CASE WHEN booking_status='cancelled' AND refund=0 THEN 1 END) AS `refund_bookings`,
//        FROM `booking_order`"));

    $unread_queries = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(sr_no) AS `count` 
        FROM `user_queries` WHERE `seen`=0"));

//    $unread_reviews = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(sr_no) AS `count`
//        FROM `rating_review` WHERE `seen`=0"));
?>

<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto p-4 overflow-hidden">

            <div class="d-flex align-itmes-center justify-content-between mb-4">
                <h3>DASHBOARD</h3>
                <h6 class="badge bg-danger py-2 px-3 rounded">Shutdown Mode is Active!</h6>
            </div>

            <div class="row m-4">
                <div class="col-md-3 mb-4">
                    <a href="new_booking.php" class="text-decoration-none">
                        <div class="card text-center text-success p3">
                            <h6>New Booking</h6>
                            <h1 class="mt-2 mb-0">5</h1>

                        </div>
                    </a>
                </div>
                <div class="col-md-3 mb-4">
                    <a href="refund_booking.php" class="text-decoration-none">
                        <div class="card text-center text-warning p3">
                            <h6>Refund Booking</h6>
                            <h1 class="mt-2 mb-0">4</h1>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 mb-4">
                    <a href="user_queries.php" class="text-decoration-none">
                        <div class="card text-center text-info p3">
                            <h6>User Booking</h6>
                            <h1 class="mt-2 mb-0">4</h1>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 mb-4">
                    <a href="rate_review.php" class="text-decoration-none">
                        <div class="card text-center text-info p3">
                            <h6>Rating & Review</h6>
                            <h1 class="mt-2 mb-0">4</h1>
                        </div>
                    </a>
                </div>
            </div>


            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5>Booking Analytics</h5>
                <select class="form-select shadow-none bg-light w-auto">
                    <option value="1">Past 30 Days</option>
                    <option value="2">Past 90 Days</option>
                    <option value="3">Past 1 Year</option>
                    <option value="4">All time</option>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 mb-4">
                    <div class="card text-center text-primary p3">
                        <h6>Total Booking</h6>
                        <h1 class="mt-2 mb-0">0</h1>
                        <h4 class="mt-2 mb-0">VND 0</h4>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-center text-success p3">
                        <h6>Active Booking</h6>
                        <h1 class="mt-2 mb-0">0</h1>
                        <h4 class="mt-2 mb-0">VND 0</h4>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-center text-primary p3">
                        <h6>Cancelled Booking</h6>
                        <h1 class="mt-2 mb-0">0</h1>
                        <h4 class="mt-2 mb-0">VND 0</h4>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5>User, Queries, Review Analytics</h5>
                <select class="form-select shadow-none bg-light w-auto">
                    <option value="1">Past 30 Days</option>
                    <option value="2">Past 90 Days</option>
                    <option value="3">Past 1 Year</option>
                    <option value="4">All time</option>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-md-3 mb-4">
                    <div class="card text-center text-success p3">
                        <h6>New Registration</h6>
                        <h1 class="mt-2 mb-0">0</h1>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-center text-primary p3">
                        <h6>Queries</h6>
                        <h1 class="mt-2 mb-0">0</h1>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-center text-primary p3">
                        <h6>Reviews</h6>
                        <h1 class="mt-2 mb-0">0</h1>
                    </div>
                </div>
            </div>

            <h5>Users</h5>
            <div class="row mb-3">
                <div class="col-md-3 mb-4">
                    <div class="card text-center text-info p3">
                        <h6>Total</h6>
                        <h1 class="mt-2 mb-0">0</h1>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-center text-success p3">
                        <h6>Active</h6>
                        <h1 class="mt-2 mb-0">0</h1>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-center text-warning p3">
                        <h6>Inactive</h6>
                        <h1 class="mt-2 mb-0">0</h1>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card text-center text-danger p3">
                        <h6>Unverified</h6>
                        <h1 class="mt-2 mb-0">0</h1>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require ('inc/scripts.php'); ?>
</body>
</html>
