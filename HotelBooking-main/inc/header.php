<?php
    require ('admin/inc/db_config.php');
    require ('admin/inc/essentials.php');
    $contact_q= "SELECT * FROM `contact_details` WHERE `sr_no`=?"; //Lấy dữ liệu từ bảng contact_details với sr_no=?
    $values = [1];
    $contact_r = mysqli_fetch_assoc(select($contact_q,$values,'i')); //Lấy 1 dòng kết quả dạng array
?>

<!--Header-->
<nav id="nav-bar" class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 px-lg-2 shadow-sm ssticky-top">
  <div class="container-fluid">
    <a class="navbar-brand mx-2 fw-bold fs-3 h-font" href="index.php">HOTEL</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link mx-2" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link mx-2" href="rooms.php">Rooms</a>
        </li>
        <li class="nav-item">
          <a class="nav-link mx-2" href="facilities.php">Facilities</a>
        </li>
        <li class="nav-item">
          <a class="nav-link mx-2" href="contact.php">Contact us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link mx-2" href="about.php">About</a>
        </li>
      </ul>
      <div class="d-flex">
        <button type="button" class="btn btn-outline-dark shadow-none mx-lg-3 mx-3" data-bs-toggle="modal" data-bs-target="#loginModal">
    Login
        </button>
        <button type="button" class="btn btn-outline-primary shadow-none" data-bs-toggle="modal" data-bs-target="#registerModal">
    Register
        </button>
      </div>
    </div>
  </div>
</nav>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">
            <i class="bi bi-person-circle fs-3 mx-2"></i> User Login
</h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control shadow-none">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input type="password" id="password" class="form-control">
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between">
            <button type="submit" class="btn btn-dark shadow-none">
                LOGIN
            </button>
            <a href="#" class="text-secondary text-decoration-none">Forgot Password?</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">
          <i class="bi bi-person-vcard"></i> User Registration
</h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="container">
          <div class="row">
            <div class="col-md-6 ps-0 mb-3">
              <label classs="form-label">Name</label>
              <input type="text" class="form-control shadow-none">
            </div>
            <div class="col-md-6 p-0">
              <label classs="form-label">Email</label>
              <input type="email" class="form-control shadow-none">
            </div>
          </div>
          <div class="row">
            <div class="col-lg-7 col-md-6 ps-0 mb-3">
              <label classs="form-label">Phone Number</label>
              <input type="number" class="form-control shadow-none">
            </div>
            <div class="col-lg-5 col-md-6 p-0">
              <label classs="form-label">Picture</label>
              <input type="file" class="form-control shadow-none">
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-6 ps-0 mb-3">
              <label classs="form-label">Andress</label>
              <textarea class="form-control shadow-none" id=""></textarea>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-6 ps-0 mb-3">
              <label classs="form-label">Date of Birth</label>
              <input type="date" class="form-control shadow-none">
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6 col-md-6 ps-0 mb-3">
              <label for="password1" class="form-label">Password</label>
              <div class="input-group">
                  <input type="password" id="password1" class="form-control shadow-none">
                  <button class="btn btn-outline-secondary" type="button" id="togglePassword1">
                      <i class="bi bi-eye" id="eyeIcon1"></i>
                  </button>
              </div>
            </div>
            <div class="col-lg-6 col-md-6 ps-0 mb-3">
              <label for="password2" class="form-label">Confirm Password</label>
              <div class="input-group">
                  <input type="password" id="password2" class="form-control">
                  <button class="btn btn-outline-secondary" type="button" id="togglePassword2">
                      <i class="bi bi-eye" id="eyeIcon2"></i>
                  </button>
              </div>
            </div>

          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between">
            <button type="submit" class="btn btn-dark shadow-none">
REGISTER
            </button>
            <a href="#" class="text-secondary text-decoration-none">Already have account?</a>
        </div>
      </div>
    </div>
  </div>
</div>
