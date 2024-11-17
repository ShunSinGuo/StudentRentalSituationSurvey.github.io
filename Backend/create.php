<?php
session_start();
$servername = "127.0.0.1";
$username = "course";
$password = "9f8ydwxyuB";
$dbname = "csie113";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $connection = mysqli_connect($servername, $username, $password, $dbname);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email = $_POST["email"];
    $account = $_POST["account"];
    $password = $_POST["password"];

    $emailquery = "SELECT * FROM t08_login WHERE email = '$email'";
    $emailresult = mysqli_query($connection, $emailquery);
    $accountquery = "SELECT * FROM t08_login WHERE account = '$account'";
    $accountresult = mysqli_query($connection, $accountquery);

    if (mysqli_num_rows($emailresult) > 0) {
        echo "<script>alert('已有重複的Email')</script>";
        header("Refresh: 0; url=create.html");
    } else if (mysqli_num_rows($accountresult) > 0) {
        echo "<script>alert('已有重複的帳號')</script>";
        header("Refresh: 0; url=create.html");
    } else {
        $conn->beginTransaction();
        $stmt1 = $conn->prepare("INSERT INTO t08_login (account, email, password) VALUES (:a, :e, :p)");
        $stmt1->bindValue(':a', $account);
        $stmt1->bindValue(':e', $email);
        $stmt1->bindValue(':p', $password);
        $stmt1->execute();
        
        $stmt2 = $conn->prepare("INSERT INTO t08_student_information (email) VALUES (:e)");
        $stmt2->bindValue(':e', $email);
        $stmt2->execute();

        $conn->commit();

        echo "<script>alert('註冊成功')</script>";
        header("Refresh: 0; url=login.html");
    }
} catch (PDOException $e) {
    $conn->rollBack();
    echo $e->getMessage();
    echo "不要問啦";
}
?>
