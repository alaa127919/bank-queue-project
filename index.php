<?php 
session_start();
include "connect.php";

$page_title = 'login';

// إذا كان المستخدم مسجل دخول بالفعل
if (isset($_SESSION['username']) && isset($_SESSION['window_number'])) {
    header('Location: employees.php');
    exit();
}

// عند إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['User_Name'];
    $password = $_POST['Password'];
    $window_number = $_POST['windows_num'];
    $hashedPassword = sha1($password); // يُفضّل مستقبلاً الانتقال لـ password_hash()

    $stmt = $con->prepare("SELECT userid, username, password FROM users WHERE username = ? AND password = ? AND admin = 0 LIMIT 1");
    $stmt->execute([$username, $hashedPassword]);
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    if ($count > 0) {
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $row['userid'];
        $_SESSION['window_number'] = $window_number;

        // منع التكرار في users_online
        $insert_stmt = $con->prepare("INSERT INTO users_online (username, windows_number) 
                                      VALUES (?, ?)
                                      ON DUPLICATE KEY UPDATE windows_number = VALUES(windows_number), login_time = CURRENT_TIMESTAMP");
        $insert_stmt->execute([$username, $window_number]);

        header('Location: employees.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="css/log_in.css">
    <script src="js/jquery.js"></script>
</head>
<body class="log_in">
    <form method="post" class="log_in_form">
        <div class="log_in_dev"> 
            <label for="windows_num" class="label_log">اختر رقم النافذة</label>
            <select id="windows_num" name="windows_num">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
        </div>
        <br>
        <div class="log_in_dev">
            <label for="User_Name" class="label_log">اسم المستخدم</label>
            <input type="text" name="User_Name" id="User_Name" required>
        </div>
        <br>
        <div class="log_in_dev">
            <label for="Password" class="label_log" id="pass_label">كلمة المرور</label>
            <input type="password" name="Password" id="Password" required>
        </div>
        <br>
        <input type="submit" value="تسجيل الدخول" class="log_in_sub">
    </form>
</body>
</html>
