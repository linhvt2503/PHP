<?php
    require ('../inc/db_config.php');
    require ('../inc/essentials.php');
    adminLogin();

    if(isset($_POST['add_feature'])) {
        error_log("Received POST data: " . print_r($_POST, true));
        $frm_data = filteration($_POST);
        error_log("Filtered data: " . print_r($frm_data, true));

        $q = "INSERT INTO `features`(`name`) VALUES (?)";
        $values = [$frm_data['name']];
        error_log("Query: " . $q);
        error_log("Values: " . print_r($values, true));

        $res = insert($q, $values, 's');
        error_log("Insert result: " . $res);
        echo $res;
    }

    if(isset($_POST['get_features'])){
        $res = selectAll('features');
        $i=1;

        while ($row = mysqli_fetch_assoc($res)){
            echo <<<data
                <tr>
                    <td>$i</td>
                    <td>$row[name]</td>
                     <td>
                        <button type="button" onclick="rem_feature($row[id])" class="btn btn-danger btn-sm shadow-none">
                                <i class="bi bi-trash"></i>
                        </button>
                     </td>
                </tr>
            data;
            $i++;
        }
    }

    if(isset($_POST['rem_feature'])){
        $frm_data = filteration($_POST);
        $values = [$frm_data['rem_feature']];


        $q = "DELETE FROM `features` WHERE `id`=?";
        $res = delete($q,$values,'i');
        echo $res;
    }



    if(isset($_POST['add_facility'])) {
        // Add debugging
        error_log("Received POST data for add_facility: " . print_r($_POST, true));
        error_log("Received FILES data: " . print_r($_FILES, true));

        $frm_data = filteration($_POST);

        $img_r = uploadSVGImage($_FILES['icon'], FACILITIES_FOLDER);
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
            $q = "INSERT INTO `facilities`(`icon`, `name`, `description`) VALUES (?,?,?)";
            $values = [$img_r, $frm_data['name'], $frm_data['desc']];

            // Add debugging
            error_log("SQL Query: " . $q);
            error_log("Values to insert: " . print_r($values, true));

            try {
                $res = insert($q, $values, 'sss');
                error_log("Insert result: " . $res);
                echo $res;
            } catch (Exception $e) {
                error_log("Error inserting facility: " . $e->getMessage());
                echo "Error: " . $e->getMessage();
            }
        }
    }

    if(isset($_POST['get_facilities'])) {
        $res = selectAll('facilities');
        $i=1;
        $path = FACILITIES_IMG_PATH;

        while ($row = mysqli_fetch_assoc($res)){
            echo <<<data
                <tr class="align-middle">
                    <td>$i</td>
                    <td><img src="$path$row[icon]" width="100px"></td>
                    <td>$row[name]</td>
                    <td>$row[description]</td>
                    <td>
                        <button type="button" onclick="rem_facility($row[id])" class="btn btn-danger btn-sm shadow-none">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            data;
            $i++;
        }
    }

    if(isset($_POST['rem_facility'])){
    $frm_data = filteration($_POST);
    $values = [$frm_data['rem_facility']];

    $pre_q = "SELECT * FROM `facilities` WHERE `id`=?";
    $res = select($pre_q,$values,'i');
    $img = mysqli_fetch_assoc($res);

    if(deleteImage($img['icon'], FACILITIES_FOLDER)){
        $q = "DELETE FROM `facilities` WHERE `id`=?";
        $res = delete($q,$values,'i');
        echo $res;
    }
    else{
        echo 0;
    }

}

?>
