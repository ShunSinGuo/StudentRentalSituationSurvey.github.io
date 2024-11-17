<?php
session_start();
$servername = "127.0.0.1";
$username = "course";
$password = "9f8ydwxyuB";
$dbname = "csie113";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the POST data
$student_id = isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : '';
$visit_check = isset($_POST['visit_check']) ? htmlspecialchars($_POST['visit_check']) : '';
$close_case = isset($_POST['close_case']) ? htmlspecialchars($_POST['close_case']) : '';


// Update the t08_record table
$sql_update = "UPDATE t08_record SET visit_check = ?, close_case = ? WHERE student_id = ?";
$stmt = $conn->prepare($sql_update);
$stmt->bind_param("sss", $visit_check, $close_case, $student_id);


if ($stmt->execute()) {
    echo "<script>alert('Record updated successfully');</script>";
    echo "<script>window.location.href = 'timecheck_fart.php?student_id={$student_id}';</script>";
} else {
    echo "<script>alert('Error updating record:');</script>";
    echo "<script>window.location.href = 'timecheck_fart.php.php?student_id={$student_id}';</script>";   
}

$stmt->close();
$conn->close();
?>
