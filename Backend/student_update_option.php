<?php
date_default_timezone_set("Asia/Taipei");
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

// 獲取 POST 數據
$student_id = isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : '';
$visit_status = isset($_POST['visit']) ? htmlspecialchars($_POST['visit']) : '';
$student_opinion = isset($_POST['student_opinion']) ? htmlspecialchars($_POST['student_opinion']) : '';
$landlord_name = isset($_POST['landlord_name']) ? htmlspecialchars($_POST['landlord_name']) : '';
$landlord_phone = isset($_POST['landlord_phone']) ? htmlspecialchars($_POST['landlord_phone']) : '';
$address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '';
$reserve_date = isset($_POST['reserve_date']) ? htmlspecialchars($_POST['reserve_date']) : '';

$clean_status = isset($_POST['admin-0-clean']) ? ($_POST['admin-0-clean'] == 'good' ? '良好' : '異常') : '';
$study_status = isset($_POST['admin-0-study']) ? ($_POST['admin-0-study'] == 'good' ? '良好' : '異常') : '';
$relationship_status = isset($_POST['admin-0-relationship']) ? ($_POST['admin-0-relationship'] == 'good' ? '良好' : '異常') : '';
$lighting_status = isset($_POST['admin-0-lighting']) ? ($_POST['admin-0-lighting'] == 'good' ? '良好' : '異常') : '';
$friends_status = isset($_POST['admin-0-friends']) ? ($_POST['admin-0-friends'] == 'good' ? '良好' : '異常') : '';
$parents_visit_status = isset($_POST['admin-0-parents-visit']) ? ($_POST['admin-0-parents-visit'] == 'good' ? '良好' : '異常') : '';
$image = null;

if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] == 0) {
    $image = file_get_contents($_FILES['imageUpload']['tmp_name']);
}

// if ($image === null) {
//     echo "<script>alert('No image uploaded.');</script>";
// }

// 初始化 status 為 '良好'
$status = '良好';

// 根據各個項目的狀態更新 status 為 '異常' 如果有任何一個項目是 '異常'
if ($clean_status == '異常' || $study_status == '異常' || $relationship_status == '異常' || $lighting_status == '異常' || $friends_status == '異常' || $parents_visit_status == '異常') {
    $status = '異常';
}

// 處理新增的欄位
$new_fields = isset($_POST['new_fields']) ? $_POST['new_fields'] : [];
$additional_values = [];
foreach ($new_fields as $index => $field) {
    echo "<script>alert('No find new fields.');</script>";
    $field_status = isset($_POST["additional-field-{$index}"]) ? ($_POST["additional-field-{$index}"] == 'good' ? '良好' : '異常') : '';
    $additional_values["additional_field_{$index}"] = $field_status;
    if ($field_status == '異常') {
        $status = '異常';
    }
}

// 將新增欄位的資料更新到數據庫中
foreach ($additional_values as $field => $value) {
    $sql_alter = "ALTER TABLE t08_record ADD $field VARCHAR(10) DEFAULT '良好'";
    $conn->query($sql_alter);
    $sql_update_additional = "UPDATE t08_record SET $field = ? WHERE student_id = ?";
    $stmt_additional = $conn->prepare($sql_update_additional);
    $stmt_additional->bind_param('ss', $value, $student_id);
    $stmt_additional->execute();
    $stmt_additional->close();
}

if ($image == null){
    // 更新 t08_record 表
    $sql_update = "UPDATE t08_record SET visit_status = ?, student_opinion = ?, landlord_name = ?, landlord_phone = ?, address = ?, reserve_date = ?, clean_status = ?, study_status = ?, relationship_status = ?, lighting_status = ?, friends_status = ?, parents_visit_status = ?, status = ? , write_date = '".date('Y-m-d')."' WHERE student_id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssssssssssssss", $visit_status, $student_opinion, $landlord_name, $landlord_phone, $address, $reserve_date, $clean_status, $study_status, $relationship_status, $lighting_status, $friends_status, $parents_visit_status, $status, $student_id);

}else{
    // 更新 t08_record 表
    $sql_update = "UPDATE t08_record SET visit_status = ?, student_opinion = ?, landlord_name = ?, landlord_phone = ?, address = ?, reserve_date = ?, clean_status = ?, study_status = ?, relationship_status = ?, lighting_status = ?, friends_status = ?, parents_visit_status = ?, status = ?, imgData = ? , write_date = '".date('Y-m-d')."' WHERE student_id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sssssssssssssss", $visit_status, $student_opinion, $landlord_name, $landlord_phone, $address, $reserve_date, $clean_status, $study_status, $relationship_status, $lighting_status, $friends_status, $parents_visit_status, $status, $image, $student_id);

}



if ($stmt->execute()) {
    echo "<script>alert('記錄更新成功');</script>";
    echo "<script>window.location.href = 'student_fart.php?student_id={$student_id}';</script>";
} else {
    echo "<script>alert('更新記錄時出錯: {$stmt->error}');</script>";
    echo "<script>window.location.href = 'student_fart.php?student_id={$student_id}';</script>";   
}

$stmt->close();
$conn->close();
?>
