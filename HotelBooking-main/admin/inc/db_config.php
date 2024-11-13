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
    function select($sql,$values,$datatypes){
        $con =$GLOBALS['con'];
        if($stmt = mysqli_prepare($con,$sql)){
            mysqli_stmt_bind_param($stmt, $datatypes,...$values);
            if(mysqli_stmt_execute($stmt)){
                $res = mysqli_stmt_get_result($stmt);
                mysqli_stmt_close($stmt);
                return $res;
            }
            else{
                mysqli_stmt_close($stmt);
                die("Query cannot be executed -Select");
            }

        }
        else{
            die("Query cannot be prepared -Select");
        }
    }

    function update($sql,$values,$datatypes){
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
                die("Query cannot be executed -Update");
            }

        }
        else{
            die("Query cannot be prepared -Update");
        }
    }

    function insert($sql,$values,$datatypes) {
        $con = $GLOBALS['con'];
        if($stmt = mysqli_prepare($con,$sql)) {
            mysqli_stmt_bind_param($stmt, $datatypes,...$values);
            if(mysqli_stmt_execute($stmt)) {
                $res = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                return $res;
            } else {
                error_log("MySQL Error: " . mysqli_stmt_error($stmt));
                mysqli_stmt_close($stmt);
                return "Query cannot be executed - Insert: " . mysqli_stmt_error($stmt);
            }
        } else {
            error_log("MySQL Prepare Error: " . mysqli_error($con));
            return "Query cannot be prepared - Insert: " . mysqli_error($con);
        }
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
