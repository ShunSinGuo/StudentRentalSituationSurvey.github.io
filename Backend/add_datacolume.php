<?php
$servername = "127.0.0.1";
$username = "course";
$password = "9f8ydwxyuB";
$dbname = "csie113";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

if (!isset($_POST['new_fields'])) {
    echo "<script>alert('輸入有誤再試一次');</script>";
    header("Refresh: 0; url=admin_changefart.html");
    exit;
}

$new_fields = $_POST['new_fields'];

if (!is_array($new_fields)) {
    echo "<script>alert('格式有誤再試一次');</script>";
    header("Refresh: 0; url=admin_changefart.html");
    exit;
}

foreach ($new_fields as $field_name) {
    $field_name = preg_replace("/[^\p{L}\p{N}_]/u", "", $field_name);

    $result = $conn->query("SHOW COLUMNS FROM t08_record LIKE '$field_name'");
    if ($result->num_rows == 0) {
        $sql = "ALTER TABLE t08_record ADD `$field_name` VARCHAR(255)";
        if (!$conn->query($sql)) {
            echo "<script>alert('輸入 $field_name 時出錯: " . $conn->error . "');</script>";
            header("Refresh: 0; url=admin_changefart.html");
            exit;
        } else {
            echo "<script>alert(' $field_name 添加成功');</script>";
        }
    } else {
        echo "<script>alert(' $field_name 已存在');</script>";
    }
}

header("Refresh: 0; url=admin_changefart.html");
?>
