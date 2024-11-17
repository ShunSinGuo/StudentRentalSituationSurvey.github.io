<?php
session_start();
$servername = "127.0.0.1";
$username = "course";
$password = "9f8ydwxyuB";
$dbname = "csie113";

// 建立連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['email']; // 或者從 POST 請求中獲取
    $field = $_POST['field'];
    $value = $_POST['value'];

    // 根據 POST 的字段名和值更新資料庫
    $sql = "UPDATE t08_record SET $field = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $value, $email);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
