<?php
    //Connect SQL
    $hname = 'localhost';
    $uname = 'root';
    $pass = '';
    $db = 'hbwebsite';
    $con = mysqli_connect($hname, $uname, $pass, $db);

    if(!$con){
        die("Cannot Connect to Database".mysqli_connect_error());
    }
    //Bảo mật dữ liệu đầu vào
    function filteration($data){
        foreach ($data as $key => $value) {
            $value = trim($value);
            $value = stripslashes($value);
            $value = htmlspecialchars($value);
            $value = strip_tags($value);
            $data[$key] = $value; // Gán giá trị đã xử lý vào mảng
        }
        return $data;
    }

    function selectAll($table) {
        $con = $GLOBALS['con'];
        $res = mysqli_query($con, "SELECT * FROM $table");
        if (!$res) {
            die("Query failed: " . mysqli_error($con));
        }
        return $res;
    }
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

    function update($sql, $values, $datatypes) {
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

    function insert($sql, $values, $datatypes) {
    $con = $GLOBALS['con'];

    // Kiểm tra kết nối
    if (!$con) {
        error_log("Database connection failed");
        return false;
    }

    // Chuẩn bị statement
    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($con));
        return false;
    }

    // Bind parameters
    try {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
    } catch (Exception $e) {
        error_log("Bind param failed: " . $e->getMessage());
        mysqli_stmt_close($stmt);
        return false;
    }

    // Thực thi query
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Execute failed: " . mysqli_stmt_error($stmt));
        mysqli_stmt_close($stmt);
        return false;
    }

    $affected_rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    return $affected_rows;
}

    function delete($sql,$values,$datatypes){
    $con =$GLOBALS['con'];
    if($stmt = mysqli_prepare($con,$sql)){
        mysqli_stmt_bind_param($stmt, $datatypes,...$values);
        if(mysqli_stmt_execute($stmt)){
            $res = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        }
        else{
            mysqli_stmt_close($stmt);
            die("Query cannot be executed -Delete");
        }

    }
    else{
        die("Query cannot be prepared -Delete");
    }
}
?>
