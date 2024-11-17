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
$manager_opinion = isset($_POST['manager_opinion']) ? htmlspecialchars($_POST['manager_opinion']) : '';
$close_case = isset($_POST['close_case']) ? htmlspecialchars($_POST['close_case']) : '';

$status = '良好';


// Update the t08_record table
$sql_update = "UPDATE t08_record SET manager_opinion = ?, close_case = ? WHERE student_id = ?";
$stmt = $conn->prepare($sql_update);
$stmt->bind_param("sss", $manager_opinion, $close_case, $student_id);


if ($stmt->execute()) {
    echo "<script>alert('Record updated successfully');</script>";
    echo "<script>window.location.href = 'admin_fart.php?student_id={$student_id}';</script>";
} else {
    echo "<script>alert('Error updating record:');</script>";
    echo "<script>window.location.href = 'admin_fart.php?student_id={$student_id}';</script>";   
}

$stmt->close();
$conn->close();
?>
