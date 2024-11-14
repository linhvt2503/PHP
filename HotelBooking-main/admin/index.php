<?php
// Kết nối cơ sở dữ liệu
$hname = 'localhost';
$uname = 'root';
$pass = '';
$db = 'hbwebsite';
$con = mysqli_connect($hname, $uname, $pass, $db);

if(!$con){
    die("Cannot connect to Database: " . mysqli_connect_error());
}

// Bảo mật dữ liệu đầu vào
function filteration($data){
    foreach ($data as $key => $value) {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        $value = strip_tags($value);
        $data[$key] = $value;
    }
    return $data;
}

// Hàm select cho tất cả bảng
function selectAll($table) {
    $con = $GLOBALS['con'];
    $res = mysqli_query($con, "SELECT * FROM `$table`");
    if (!$res) {
        die("Query failed: " . mysqli_error($con));
    }
    return $res;
}

// Hàm select với truy vấn và tham số
function select($sql, $values, $datatypes) {
    $con = $GLOBALS['con'];

    if (!$con) {
        error_log("Database connection failed");
        return false;
    }

    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($con));
        return false;
    }

    if (!empty($values)) {
        try {
            mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
        } catch (Exception $e) {
            error_log("Bind param failed: " . $e->getMessage());
            mysqli_stmt_close($stmt);
            return false;
        }
    }

    if (!mysqli_stmt_execute($stmt)) {
        error_log("Execute failed: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return false;
    }

    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    return $result;
}


// Hàm cập nhật
function update($sql, $values, $datatypes) {
    $con = $GLOBALS['con'];
    $stmt = mysqli_prepare($con, $sql);

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($con));
    }

    if (!empty($values)) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
    }

    if (!mysqli_stmt_execute($stmt)) {
        die("Execute failed: " . mysqli_stmt_error($stmt));
    }

    $result = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $result;
}

// Hàm xóa dữ liệu
function delete($sql, $values, $datatypes){
    $con = $GLOBALS['con'];
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            mysqli_stmt_close($stmt);
            die("Query cannot be executed - Delete");
        }
    } else {
        die("Query cannot be prepared - Delete");
    }
}
?>
