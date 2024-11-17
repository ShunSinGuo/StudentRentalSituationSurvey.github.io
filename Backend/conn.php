<?php
    $servername = "127.0.0.1";
    $username = "course";
    $password = "9f8ydwxyuB";
    $dbname = "csie113";

    // mysqli 的四個參數分別為：伺服器名稱、帳號、密碼、資料庫名稱
    $conn = new mysqli($servername, $username, $password, $dbname);

    if (!empty($conn->connect_error)) {
        die('連接失敗:' . $conn->connect_error);    // die()：終止程序
    }
?>