<?php
    require ('../inc/db_config.php');
    require ('../inc/essentials.php');
    adminLogin();

    if(isset($_POST['get_general'])){
        $q = "SELECT * FROM `settings` WHERE `sr_no`=?";
        $values = [1];
        $res =select($q,$values, "i" );
        $data= mysqli_fetch_assoc($res);
        $json_data = json_encode($data);
        echo $json_data;
    }

    if(isset($_POST['upd_general']))
    {
        $frm_data = filteration($_POST);

        $q = "UPDATE `settings` SET `site_title`=?,`site_about`=? WHERE `sr_no`=?";
        $values = [$frm_data['site_title'],$frm_data['site_about'],1];
        $res = update($q,$values,'ssi');
        echo $res;
    }

    if(isset($_POST['upd_shutdown']))
    {

        $frm_data = $_POST['upd_shutdown'];

        $q = "UPDATE `settings` SET `shutdown`=? WHERE `sr_no`=?";
        $values = [$frm_data,1];
        $res = update($q,$values,'ii');
        echo $res;
    }

    if(isset($_POST['get_contacts'])){
        $q = "SELECT * FROM `contact_details` WHERE `sr_no`=?";
        $values = [1];
        $res =select($q,$values, "i" );
        $data= mysqli_fetch_assoc($res);
        $json_data = json_encode($data);
        echo $json_data;
    }

    if(isset($_POST['upd_contacts']))
    {
        $frm_data = filteration($_POST);

        $q = "UPDATE `contact_details` SET `address`=?, `gmap`=?, `pn1`=?, `pn2`=?, `email`=?, `fb`=?, `insta`=?, `tw`=?, `iframe`=? WHERE `sr_no`=?";
        $values = [$frm_data['address'],
                    $frm_data['gmap'],
                    $frm_data['pn1'],
                    $frm_data['pn2'],
                    $frm_data['email'],
                    $frm_data['fb'],
                    $frm_data['insta'],
                    $frm_data['tw'],
                    $frm_data['iframe'],
                    1];
        $res = update($q,$values,'sssssssssi');
        echo $res;
    }

    if(isset($_POST['add_member'])) {
        $frm_data = filteration($_POST);

        // Debug log
        error_log("Processing add_member request");
        error_log("Name: " . $frm_data['name']);
        error_log("Files: " . print_r($_FILES, true));

        // Validate input
        if(empty($frm_data['name'])) {
            echo 'inv_name';
            exit;
        }

        if(!isset($_FILES['picture']) || !isset($_FILES['picture']['name']) || empty($_FILES['picture']['name'])) {
            echo 'inv_image';
            exit;
        }

        // Upload image
        $img_r = uploadImage($_FILES['picture'], ABOUT_FOLDER);
        error_log("Image upload result: " . $img_r);

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
            try {
                $q = "INSERT INTO `team_details`(`name`, `picture`) VALUES (?,?)";
                $values = [$frm_data['name'], $img_r];
                $res = insert($q, $values, 'ss');
                error_log("Insert result: " . $res);
                echo $res;
            } catch (Exception $e) {
                error_log("SQL Error: " . $e->getMessage());
                echo 'db_error';
            }
        }
    }

    if(isset($_POST['get_members'])){
        $res = selectAll('team_details');

        while ($row = mysqli_fetch_assoc($res)){
            $path = ABOUT_IMG_PATH;
            echo <<<data
                <div class="col-md-2 mb-3">
                    <div class="card bg-dark text-white">
                        <img src="$path$row[picture]" class="card-img">
                        <div class="card-img-overlay text-end">
                            <button type="button" onclick="rem_member($row[sr_no])" class="btn btn-danger btn-sm shadow-none">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <p class="card-text text-center px-3 py-2">$row[name]</p>
                    </div>
                </div>
            data;
        }
    }

    if(isset($_POST['rem_member'])){
        $frm_data = filteration($_POST);
        $values = [$frm_data['rem_member']];

        $pre_q = "SELECT * FROM `team_details` WHERE `sr_no`=?";
        $res = select($pre_q,$values,'i');
        $img = mysqli_fetch_assoc($res);

        if(deleteImage($img['picture'], ABOUT_FOLDER)){
            $q = "DELETE FROM `team_details` WHERE `sr_no`=?";
            $res = delete($q,$values,'i');
            echo $res;
        }
        else{
            echo 0;
        }

    }

?>
