<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel - HOME</title>
    <!-- SwiperJS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <?php require ('inc/links.php');?>
    <style>
        .availabity-form{
          margin-top: -50px;
          z-index: 2;
          position: relative;
        }
        @media screen and (max-width: 575px){
          .availabity-form{
            margin-top: 25px;
            padding: 0 35px;
          }
        }
    </style>
</head>
<body class="bg-light">
    <!--Header-->
    <?php require ('inc/header.php'); ?>
    <?php
        $contact_q= "SELECT * FROM `contact_details` WHERE `sr_no`=?"; //Lấy dữ liệu từ bảng contact_details với sr_no=?
        $values = [1];
        $contact_r = mysqli_fetch_assoc(select($contact_q,$values,'i')); //Lấy 1 dòng kết quả dạng array
    ?>
<!-- Carousel -->
<div class="contrainer-fluid px-lg-4 mt-4">
  <div class="swiper swiper-container">
    <div class="swiper-wrapper">
        <?php
            $res = selectAll('carousel');
            while ($row = mysqli_fetch_assoc($res)){
                $path = CAROUSEL_IMG_PATH;
                echo <<<data
                    <div class="swiper-slide">
                        <img src="$path$row[image] " class="w-100 d-block">
                    </div>
                data;
            }
        ?>
    </div>
  </div>
</div>

    <!-- Form Check Booking -->
    <div class="container availabity-form">
    <div class="row">
      <div class="col-lg-12 bg-white shadow p-4 rounded">
        <h5 class="mb-4"> Check Booking Availabity</h5>
        <form>
          <div class="row align-items-end">
              <div class="col-lg-3 mb-3">
                <label classs="form-label" sytle="font-weight: 500;">Check-in</label>
                <input type="date" class="form-control shadow-none">
              </div>
              <div class="col-lg-3 mb-3">
                <label classs="form-label" sytle="font-weight: 500;">Check-out</label>
                <input type="date" class="form-control shadow-none">
              </div>
              <div class="col-lg-3 mb-3">
                <label classs="form-label" sytle="font-weight: 500;">Adult</label>
                <select class="form-select shadow-none">
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
                </select>
              </div>
              <div class="col-lg-2 mb-3">
                <label classs="form-label" sytle="font-weight: 500;">Children</label>
                <select class="form-select shadow-none">
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <otion value="3">Three</otion>
                </select>
              </div>
              <div class="col-lg-1 mb-lg-3 mt-2">
                <button type="submit" class="btn text-white shadow-none custom-bg">Submit</button>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>


    <!-- OUR Rooms -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Phòng</h2>
    <div class="container">
    <div class="row">
      <!-- Room 1 -->
        <div class="col-lg-4 col-md-6 my-3">
        <div class="card border-0 shadow " style="max-width: 350px; margin: auto;">
          <img src="images/rooms/1.jpg" class="card-img-top">
          <div class="card-body">
            <h5 >Phòng Đơn</h5>
            <h6 class="mb-4">2tr999 VND/ngày</h6>
            <div class="features mb-4">
                <h6 class="mb-1">Mô tả</h6>
                <span class="badge rounded-pill bg-light text-dark text-wrap ">
                  2 Rooms
                </span>
                <span class="badge rounded-pill bg-light text-dark text-wrap ">
                  2 Bathrooms
                </span>
                <span class="badge rounded-pill bg-light text-dark text-wrap ">
                  1 Living Room

            </div>
            <div class="facilites mb-4">
              <h6 class="mb-1">Cơ sở vật chất</h6>
              <span class="badge rounded-pill bg-light text-dark text-wrap ">
                Wifi 5G
              </span>
              <span class="badge rounded-pill bg-light text-dark text-wrap ">
                2 TV
              </span>
              <span class="badge rounded-pill bg-light text-dark text-wrap ">
                1 Infinity Pool

            </div>
            <div class="guests mb-4">
                  <h6 class="mb-1">Số lượng khách</h6>
                  <span class="badge rounded-pill bg-light text-dark text-wrap ">
                5 người lớn
              </span>
                  <span class="badge rounded-pill bg-light text-dark text-wrap ">
                4 trẻ em
              </span>
              </div>
            <div class="rating mb-4">
              <h6 class="mb-1">Đánh giá</h6>
              <span class="badge rounded-pill bg-light">
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
              </span> 
            </div>
            <div class="d-flex justify-content-evenly mb-2">
              <a href="#" class="btn btn-sm text-white custom-bg shadow-none">Đặt phòng</a>
              <a href="#" class="btn btn-sm btn-outline-dark shadow-none">Thêm chi tiết</a>
            </div>
          </div>
        </div>
      </div>
      <!-- Room 2 -->
        <div class="col-lg-4 col-md-6 my-3">
            <div class="card border-0 shadow " style="max-width: 350px; margin: auto;">
                <img src="images/rooms/1.jpg" class="card-img-top">
                <div class="card-body">
                    <h5 >Phòng Đơn</h5>
                    <h6 class="mb-4">2tr999 VND/ngày</h6>
                    <div class="features mb-4">
                        <h6 class="mb-1">Mô tả</h6>
                        <span class="badge rounded-pill bg-light text-dark text-wrap ">
                  2 Rooms
                </span>
                        <span class="badge rounded-pill bg-light text-dark text-wrap ">
                  2 Bathrooms
                </span>
                        <span class="badge rounded-pill bg-light text-dark text-wrap ">
                  1 Living Room

                    </div>
                    <div class="facilites mb-4">
                        <h6 class="mb-1">Cơ sở vật chất</h6>
                        <span class="badge rounded-pill bg-light text-dark text-wrap ">
                Wifi 5G
              </span>
                        <span class="badge rounded-pill bg-light text-dark text-wrap ">
                2 TV
              </span>
                        <span class="badge rounded-pill bg-light text-dark text-wrap ">
                1 Infinity Pool

                    </div>
                    <div class="guests mb-4">
                        <h6 class="mb-1">Số lượng khách</h6>
                        <span class="badge rounded-pill bg-light text-dark text-wrap ">
                5 người lớn
              </span>
                        <span class="badge rounded-pill bg-light text-dark text-wrap ">
                4 trẻ em
              </span>
                    </div>
                    <div class="rating mb-4">
                        <h6 class="mb-1">Đánh giá</h6>
                        <span class="badge rounded-pill bg-light">
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
              </span>
                    </div>
                    <div class="d-flex justify-content-evenly mb-2">
                        <a href="#" class="btn btn-sm text-white custom-bg shadow-none">Đặt phòng</a>
                        <a href="#" class="btn btn-sm btn-outline-dark shadow-none">Thêm chi tiết</a>
                    </div>
                </div>
            </div>
        </div>
      <!-- Room 3 -->
        <div class="col-lg-4 col-md-6 my-3">
            <div class="card border-0 shadow " style="max-width: 350px; margin: auto;">
                <img src="images/rooms/1.jpg" class="card-img-top">
                <div class="card-body">
                    <h5 >Phòng Đơn</h5>
                    <h6 class="mb-4">2tr999 VND/ngày</h6>
                    <div class="features mb-4">
                        <h6 class="mb-1">Mô tả</h6>
                        <span class="badge rounded-pill bg-light text-dark text-wrap ">
                  2 Rooms
                </span>
                        <span class="badge rounded-pill bg-light text-dark text-wrap ">
                  2 Bathrooms
                </span>
                        <span class="badge rounded-pill bg-light text-dark text-wrap ">
                  1 Living Room

                    </div>
                    <div class="facilites mb-4">
                        <h6 class="mb-1">Cơ sở vật chất</h6>
                        <span class="badge rounded-pill bg-light text-dark text-wrap ">
                Wifi 5G
              </span>
                        <span class="badge rounded-pill bg-light text-dark text-wrap ">
                2 TV
              </span>
                        <span class="badge rounded-pill bg-light text-dark text-wrap ">
                1 Infinity Pool

                    </div>
                    <div class="guests mb-4">
                        <h6 class="mb-1">Số lượng khách</h6>
                        <span class="badge rounded-pill bg-light text-dark text-wrap ">
                5 người lớn
              </span>
                        <span class="badge rounded-pill bg-light text-dark text-wrap ">
                4 trẻ em
              </span>
                    </div>
                    <div class="rating mb-4">
                        <h6 class="mb-1">Đánh giá</h6>
                        <span class="badge rounded-pill bg-light">
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
                <i class="bi bi-star-fill text-warning"></i>
              </span>
                    </div>
                    <div class="d-flex justify-content-evenly mb-2">
                        <a href="#" class="btn btn-sm text-white custom-bg shadow-none">Đặt phòng</a>
                        <a href="#" class="btn btn-sm btn-outline-dark shadow-none">Thêm chi tiết</a>
                    </div>
                </div>
            </div>
        </div>

      <div class="col-lg-12 text-center mt-5">
        <a href="#" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Rooms >>></a>
      </div>
    </div>
  </div>

    <!-- OUR FACILITIES -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">TIỆN ÍCH</h2>
    <div class="contrainer">
        <div class="row justify-content-evenly px-lg-0 px-5">
            <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                <img src="images/facilities/Wifi.svg" width="80px">
                <h5 class="mt-3">Wifi</h5>
            </div>
            <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                <img src="images/facilities/air.svg" width="80px">
                <h5 class="mt-3">Máy Lạnh</h5>
            </div>
            <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                <img src="images/facilities/raidio.svg" width="80px">
                <h5 class="mt-3">Radio</h5>
            </div>
            <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                <img src="images/facilities/SPA.svg" width="80px">
                <h5 class="mt-3">Spa</h5>
            </div>
            <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                <img src="images/facilities/TV.svg" width="80px">
                <h5 class="mt-3">TV</h5>
            </div>
        </div>
        <div class="col-lg-12 text-center mt-4">
            <a href="#" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Facilities >>></a>
        </div>
    </div>

    <!-- OUR Reviews -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Đánh giá</h2>
    <div class="contrainer mt-5">
        <!-- Swiper Reviews-->
        <div class="swiper swiper-testimonials">
            <div class="swiper-wrapper mb-5" >
                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex algin-item-center p-4">
                        <img src="images/facilities/star.jpg" width="30px">
                        <h6 class="m-1 ms-2">Random user1</h6>
                    </div>
                    <p>
                        Bản thân chúng tôi mong muốn có trải nghiệm tuyệt vời và
                        những khoảng thời gian tuyệt vời đã là sự thành công rất lớn.
                    </p>
                    <div class="rating">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                </div>
                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex algin-item-center p-4">
                        <img src="images/facilities/star.jpg" width="30px">
                        <h6 class="m-1 ms-2">Random user2</h6>
                    </div>
                    <p>
                       Amazing Good Job Em
                    </p>
                    <div class="rating">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                </div>
                <div class="swiper-slide bg-white p-4">
                    <div class="profile d-flex algin-item-center p-4">
                        <img src="images/facilities/star.jpg" width="30px">
                        <h6 class="m-1 ms-2">Random user3</h6>
                    </div>
                    <p>
                        Trời ơi có cái hotel gì mà xịn thế nào, phải kiểm tra mới được
                    </p>
                    <div class="rating">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <div class="col-lg-12 text-center mt-4">
            <a href="#" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Reviews>>></a>
        </div>
    </div>

    <!-- Reach us -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Liên hệ</h2>
    <div class="contrainer">
        <div class="row">
            <!-- Map -->
            <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
                <iframe class="w-100" height="320px" src="<?php echo $contact_r['iframe'] ?>""></iframe>
            </div>
            <div class="col-lg-4 col-md-4">
                <!-- Phone Number -->
                <div class="bg-white p-4 rounded mb-4">
                    <h5>Hotline</h5>
                    <a href="tel: + <?php echo $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> + <?php echo $contact_r['pn1'] ?>
                    </a>
                    <br>
                    <?php
                    // Kiểm tra nếu số điện thoại 2 không rỗng thì hiển thị link và icon gọi điện
                        if($contact_r['pn2']!=''){
                            echo <<< data
                                <a href="tel: + $contact_r[pn2]" class="d-inline-block mb-2 text-decoration-none text-dark">
                                    <i class="bi bi-telephone-fill"></i> + $contact_r[pn2]
                                </a>
                            data;
                        }
                    ?>
                </div>
                <!-- Platform -->
                <div class="bg-white p-4 rounded mb-4">
                    <h5>Follow us</h5>
                    <?php
                        if($contact_r['tw']!=''){
                            echo <<< data
                            <a href="$contact_r[tw]" class="d-inline-block mb-3">
                                <span class="badge bg-light text-dark fs-6 p-2">
                                <i class="bi bi-twitter-x"></i> Twitter
                                </span>
                            </a>
                            <br>
                            data;
                        }
                    ?>

                    <a href="<?php echo $contact_r['fb']?>" class="d-inline-block mb-3">
                        <span class="badge bg-light text-dark fs-6 p-2">
                            <i class="bi bi-facebook"></i> Facebook
                        </span>
                    </a>
                    <br>
                    <a href="<?php echo $contact_r['insta']?>" class="d-inline-block mb-3">
                        <span class="badge bg-light text-dark fs-6 p-2">
                            <i class="bi bi-instagram"></i> Instagram
                        </span>
                    </a>
                </div>
            </div>

        </div>

    </div>

    <!--Footer-->
    <?php require ('inc/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    // ẩn hiện pass
    document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    const isPassword = passwordInput.type === 'password';

    passwordInput.type = isPassword ? 'text' : 'password';
    eyeIcon.classList.toggle('bi-eye', !isPassword);
    eyeIcon.classList.toggle('bi-eye-slash', isPassword);
    });

    document.getElementById('togglePassword1').addEventListener('click', function () {
    const passwordInput = document.getElementById('password1');
    const eyeIcon = document.getElementById('eyeIcon1');
    const isPassword = passwordInput.type === 'password';
    
    passwordInput.type = isPassword ? 'text' : 'password';
    eyeIcon.classList.toggle('bi-eye', !isPassword);
    eyeIcon.classList.toggle('bi-eye-slash', isPassword);
    });

  // Xử lý ẩn hiện password thứ hai
    document.getElementById('togglePassword2').addEventListener('click', function () {
    const passwordInput = document.getElementById('password2');
    const eyeIcon = document.getElementById('eyeIcon2');
    const isPassword = passwordInput.type === 'password';
    
    passwordInput.type = isPassword ? 'text' : 'password';
    eyeIcon.classList.toggle('bi-eye', !isPassword);
    eyeIcon.classList.toggle('bi-eye-slash', isPassword);
    });
   //Swiper slidebar
    var swiper = new Swiper(".swiper-container", {
      spaceBetween: 30,
      effect: "fade",
      loop: true,
      autoplay: {
        delay: 3500,
        disableonOnInteraction: false,
      }
    });
    //Swiper slidebar  coverflow
    var swiper = new Swiper(".swiper-testimonials", {
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: "auto",
        slidesPerView: "3",
        loop: true,
        coverflowEffect: {
            rotate: 50,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows: false,
        },
        pagination: {
            el: ".swiper-pagination",
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
            },
            640: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        }
    });


</script>
</body>
</html>