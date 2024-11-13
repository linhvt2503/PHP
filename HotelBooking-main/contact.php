<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel-CONTACT</title>
    <?php require ('inc/links.php');?>
</head>
<body class="bg-light">
    <!--Header-->
    <?php require ('inc/header.php'); ?>
    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">LIÊN HỆ</h2>
        <hr class="w-50 mx-auto">
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit.
            Aspernatur at atque, cumque deleniti dolorem dolorum ex id,<br>
            ipsa natus nemo quibusdam quo quos ratione recusandae sed tempore vitae.
            Accusamus, porro.
        </p>
    </div>

    <?php
    $contact_q= "SELECT * FROM `contact_details` WHERE `sr_no`=?"; //Lấy dữ liệu từ bảng contact_details với sr_no=?
    $values = [1];
    $contact_r = mysqli_fetch_assoc(select($contact_q,$values,'i')); //Lấy 1 dòng kết quả dạng array
    ?>

    <div class="container">
        <div class="row">
            <!--Items1-->
            <div class="col-lg-6 col-md-6 md-5 px-4 mb-4">
                <div class="bg-white rounded shadow p-4">

                    <!--MAP-->
                    <iframe class="w-100 rounded mb-4" height="320px" src="<?php echo $contact_r['iframe'] ?>""></iframe>
                    <h5>Địa chỉ</h5>
                    <a href="<?php echo $contact_r['gmap'] ?>" target="_blank" class="d-inline-block text-decoration-none text-dark mb-2">
                        <i class="bi bi-geo-alt-fill"></i><?php echo $contact_r['address'] ?>
                    </a>

                    <!--HOTLINE-->
                    <h5>Hotline</h5>
                    <a href="tel: +<?php echo $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i>+<?php echo $contact_r['pn1'] ?>
                    </a>
                    <br>
                    <?php
                        if($contact_r['pn2']!=''){
                            echo <<<data
                            <a href="tel: +$contact_r[pn2]" class="d-inline-block mb-2 text-decoration-none text-dark">
                                <i class="bi bi-telephone-fill"></i> +$contact_r[pn2]
                            </a>
                        data;
                        }
                    ?>
                    <!--Mail-->
                    <h5 class="mt-4">Email</h5>
                    <a href="mail: <?php echo $contact_r['email'] ?>"  class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-envelope-fill"></i> <?php echo $contact_r['email'] ?>
                    </a>

                    <h5 class="mt-4 ">Follow us</h5>
                    <?php
                        if($contact_r['tw']!=''){
                            echo <<<data
                                <a href="$contact_r[tw]" class="d-inline-block text-dark fs-5 me-2">
                                    <i class="bi bi-twitter-x"></i>
                                </a>
                            data;
                        }
                    ?>
                    <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block text-dark fs-5 me-2">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block text-dark fs-5 me-2">
                        <i class="bi bi-instagram"></i>
                    </a>
                </div>
            </div>
            <!--Items2-->
            <div class="col-lg-6 col-md-6 md-5 px-4 mb-4">
                <div class="bg-white rounded shadow p-4">
                    <form method="POST">
                        <h5>
                            Send me a messasge!
                        </h5>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" style="font-weight: 500;" class="form-label">Name</label>
                            <input name="name" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" style="font-weight: 500;" class="form-label">Email</label>
                            <input name="email" required type="email" class="form-control shadow-none">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" style="font-weight: 500;" class="form-label">Subject</label>
                            <input name="subject" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" style="font-weight: 500;" class="form-label">Email</label>
                            <textarea name="message" required class="form-control shadow-none row='5" style="resize: none;" id=""></textarea>
                        </div>
                        <button type="submit" name="send" class="btn text-white custom-bg mt-3">
                            GỬI
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <?php
        if(isset($_POST['send'])){
            $frm_data = filteration($_POST);

            $q = "INSERT INTO `user_queries`(`name`, `email`, `subject`, `message`) VALUES (?,?,?,?)";
            $values = [$frm_data['name'],$frm_data['email'],$frm_data['subject'],$frm_data['message']];

            $res = insert($q,$values,'ssss');
            if($res==1){
                alert('success', 'Đã gửi!');
            }
            else{
                alert('error','Gửi lỗi');
            }
        }

    ?>



    <!--Footer-->
    <?php require ('inc/footer.php'); ?>


</body>
</html>