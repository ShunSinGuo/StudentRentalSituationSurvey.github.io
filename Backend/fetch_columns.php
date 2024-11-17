<?php
$servername = "127.0.0.1";
$username = "course";
$password = "9f8ydwxyuB";
$dbname = "csie113";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

$result = $conn->query("SHOW COLUMNS FROM t08_record");

$columns = [];
while ($row = $result->fetch_assoc()) {
    $columns[] = $row['Field'];
}

$columns = array_slice($columns, 19);    //改數字欄位 抓欄位

echo json_encode($columns);

$conn->close();
?>
