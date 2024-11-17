<?php
date_default_timezone_set("Asia/Taipei");
// 設定資料庫連線資訊
$servername = "127.0.0.1";
$username = "course";
$password = "9f8ydwxyuB";
$dbname = "csie113";

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 接收表單提交的資料
$landlord_name = $_POST['landlord-name'];
$landlord_phone = $_POST['landlord-phone'];
$address = $_POST['address'];
$student_comments = $_POST['student-comments'];
$visit = $_POST['visit'];
$visit_time = $_POST['daterange']; // 假設這裡是從日期範圍選擇器中取得的訪問時間
$image_name = $_FILES['imageUpload']['name']; // 取得上傳圖片的檔案名稱

// SQL 指令：插入基本資料到資料庫中
$sql = "INSERT INTO t08_record (landlord_name, landlord_phone, address, student_comments, visit, visit_time, image_name,write_date) 
        VALUES ('$landlord_name', '$landlord_phone', '$address', '$student_comments', '$visit', '$visit_time', '$image_name','".date('Y-m-d')."')";

if ($conn->query($sql) === TRUE) {
    echo "基本資料插入成功！";

    // 取得新增的記錄的自增ID
    $record_id = $conn->insert_id;

    // 處理動態新增的環境項目
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'additional-field-') === 0) {
            // 獲取評分（good或bad）
            $rating = $value;

            // 解析出欄位索引
            $index = substr($key, strlen('additional-field-'));

            // SQL 指令：插入環境項目評分到資料庫中
            $sql_insert_env = "INSERT INTO t08_environment (record_id, field_index, rating) VALUES ('$record_id', '$index', '$rating')";

            // 執行SQL指令
            if ($conn->query($sql_insert_env) !== TRUE) {
                echo "Error inserting environment data: " . $conn->error;
            }
        }
    }

} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// 關閉資料庫連線
$conn->close();
?>