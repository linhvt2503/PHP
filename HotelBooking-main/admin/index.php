<?php
require ('inc/essentials.php');
require ('inc/db_config.php');

session_start();
if(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] != true){
    redirect("dashboard.php");
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <?php require ('inc/links.php'); ?>
    <style>
        div.login-form{
            position: absolute;
            top:50%;
            left: 50%;
            transform: translate(-50%,-50%);
            width: 400px;
        }
    </style>
</head>
<body class="bg-light">

<div class="login-form text-center rounded bg-white shadow overflow-hidden">
    <form method="POST">
        <h4 class="bg-dark text-white py-3">LOGIN</h4>
        <div class="p-4">
            <div class="mb-3">
                <input name="username" required type="text" class="form-control shadow-none text-center" placeholder="Username">
            </div>
            <div class="mb-4">
                <div class="input-group">
                    <input name="password" required type="password" class="form-control shadow-none text-center" placeholder="Password">
                    <button class="btn btn-outline-secondary d-none" type="button" id="togglePass">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            <button name="login" type="submit" class="btn text-white custom-bg shadow-none">LOGIN</button>
        </div>
    </form>
</div>

<?php
if(isset($_POST['login']))
{
    $frm_data = filteration($_POST);

    // Check if it's a user login attempt
    if($frm_data['username'] === 'user' && $frm_data['password'] === '123') {
        $_SESSION['userLogin'] = true;
        redirect('//localhost/HotelBooking-main/index.php');
    }
    // Check if it's an admin login attempt
    else {
        $query = "SELECT * FROM `admin_cred` WHERE `admin_name`=? AND `admin_pass`=?";
        $values = [$frm_data['username'], $frm_data['password']];

        $res = select($query, $values, "ss");
        if($res->num_rows == 1) {
            $row = mysqli_fetch_assoc($res);
            $_SESSION['adminLogin'] = true;
            $_SESSION['adminId'] = $row['sr_no'];
            redirect('dashboard.php');
        } else {
            alert('error', 'Login failed - Invalid Credentials!');
        }
    }
}
?>

<?php require ('inc/scripts.php'); ?>
<script>
    const togglePass = document.querySelector('#togglePass');
    const passInput = document.querySelector('input[name="password"]');

    passInput.addEventListener('focus', () => {
        togglePass.classList.remove('d-none');
    });

    togglePass.addEventListener('click', () => {
        const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passInput.setAttribute('type', type);

        togglePass.querySelector('i').classList.toggle('bi-eye');
        togglePass.querySelector('i').classList.toggle('bi-eye-slash');
    });
</script>
</body>
</html>