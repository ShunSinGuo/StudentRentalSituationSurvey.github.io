<?php
session_start();
$servername = "127.0.0.1";
$username = "course";
$password = "9f8ydwxyuB";
$dbname = "csie113";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 確認 $_POST['account'] 和 $_POST['password'] 存在
    if (!isset($_POST['account']) || !isset($_POST['password'])) {
        echo '<script>alert("請輸入帳號和密碼");</script>';
        echo '<script>window.location.href = "login.html";</script>';
        exit();
    }

    // 檢查驗證碼
    $enteredCaptcha = $_POST['verification_code'];
    if ($enteredCaptcha != $_SESSION['captcha']) {
        echo '<script>alert("驗證碼錯誤");</script>';
        echo '<script>window.location.href = "login.html";</script>';
        exit();
    }

    // 檢查帳號和密碼
    $account = $_POST['account'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM t08_login WHERE account = :account");
    $stmt->bindParam(':account', $account);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        echo '<script>alert("找不到此學號的帳號");</script>';
        echo '<script>window.location.href = "login.html";</script>';
        exit();
    }

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row['password'] == $password) {
        $_SESSION['account'] = $account;
        $_SESSION['email'] = $row['email'];

        // 檢查是否為管理者
        $stmt1 = $conn->prepare("SELECT * FROM t08_manager WHERE manager_id = :account");
        $stmt1->bindParam(':account', $account);
        $stmt1->execute();

        if ($stmt1->rowCount() > 0) {
            $_SESSION['is_manager'] = true;
        } else {
            $_SESSION['is_manager'] = false;
        }

        // 檢查有沒有資料
        $stmt2 = $conn->prepare("SELECT student_id FROM t08_student_information WHERE email = :email");
        $stmt2->bindParam(':email', $row['email']);
        $stmt2->execute();
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

        echo "<script>alert('登入成功：" . $_SESSION['account'] . "');</script>";
        if($_SESSION['is_manager'] == false){
            // 檢查用戶是否已經登錄並且有學號信息
            if ($row2['student_id'] == null) {
                header("Location: student_page.php");
            } 
            echo '<script>window.location.href = "student_server.html";</script>';
        } else{
            echo '<script>window.location.href = "admin_server.html";</script>';
        }

    } else {
        echo '<script>alert("密碼錯誤");</script>';
        echo '<script>window.location.href = "login.html";</script>';
    }
} catch (PDOException $e) {
    echo "資料庫錯誤：" . $e->getMessage();
}

$conn = null;
?>
