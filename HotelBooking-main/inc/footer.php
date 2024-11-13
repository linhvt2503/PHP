<?php
$contact_q= "SELECT * FROM `contact_details` WHERE `sr_no`=?"; //Lấy dữ liệu từ bảng contact_details với sr_no=?
$values = [1];
$contact_r = mysqli_fetch_assoc(select($contact_q,$values,'i')); //Lấy 1 dòng kết quả dạng array
?>
<!-- Footer -->
<div class="contrainer-fluid bg-white mt-5">
    <div class="row">
        <div class="col-lg-4 p-4">
            <h3 class="h-font fw-bold fs-3">Vinpearl Nha Trang</h3>
            <p>
                Vinpearl Nha Trang là nơi có các khách sạn là một điểm đến lý tưởng cho du khách, nổi tiếng với không gian sang trọng,
                dịch vụ chuyên nghiệp và tiện nghi hiện đại. Với vị trí thuận lợi, gần các điểm du lịch nổi tiếng và trung tâm thương mại,
                Comodo mang đến cho khách hàng trải nghiệm lưu trú tuyệt vời.
            </p>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Links</h5>
            <a href="index.php" class="d-inline-block mb-2 text-dark text-decoration-none">Home</a> <br>
            <a href="rooms.php" class="d-inline-block mb-2 text-dark text-decoration-none">Rooms</a> <br>
            <a href="facilities.php" class="d-inline-block mb-2 text-dark text-decoration-none">Facilities</a> <br>
            <a href="contact.php" class="d-inline-block mb-2 text-dark text-decoration-none">Contact us</a> <br>
            <a href="about.php" class="d-inline-block mb-2 text-dark text-decoration-none">About</a>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Follow us</h5>
        <?php
            if($contact_r['tw']){
                echo <<<data
                <a href="$contact_r[tw]" class="d-inline-block text-dark text-decoration-none mb-2">
                    <i class="bi bi-twitter-x"></i> Twitter
                </a><br>
                data;
            }
        ?>

            <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block text-dark text-decoration-none mb-2">
                <i class="bi bi-facebook"></i> Facebook
            </a><br>
            <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block text-dark text-decoration-none">
                <i class="bi bi-instagram"></i> Instagram
            </a><br>
        </div>
    </div>
</div>

<h6 class="text-center bg-dark text-white p-3 m-0">Design and Devoloped by CEO GROUP</h6>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
    function setActive() {
        let navbar=document.getElementById('nav-bar');
        let a_tags= navbar.getElementsByTagName('a');

        for(i=0; i<a_tags.length;i++){
            let file = a_tags[i].href.split('/').pop();
            let file_name = file.split('.')[0];

            if(document.location.href.indexOf(file_name) >= 0){
                a_tags[i].classList.add('active');
            }
        }
    }
    setActive();
</script>