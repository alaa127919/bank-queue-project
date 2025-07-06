<?php 
session_start();
$page_title='login';

// التحقق إذا كانت الجلسة موجودة وإعادة التوجيه
if(isset($_SESSION['username'])){
    header('Location:admin.php');
    exit(); // يجب إضافة exit() بعد header
}

// تضمين الملفات المطلوبة
include "connect.php";

// التحقق من طلب POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['User_Name'];
    $password = $_POST['Password'];
    $hashedPassword = sha1($password);

    // التحقق من وجود المستخدم في قاعدة البيانات
    $stmt = $con->prepare("SELECT 
                                userid,username,password 
                            FROM 
                                users 
                            WHERE 
                                username = ?
                             AND 
                                password = ? 
                            AND 
                                admin = 1
                            LIMIT 1");
    $stmt->execute(array($username, $hashedPassword));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    // إذا كان المستخدم موجود، إعادة التوجيه إلى لوحة القيادة
    if($count > 0){
        $_SESSION['username'] = $username;
        $_SESSION['id']=$row['userid'];
        header('Location: admin.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>log_in</title>
    <link rel="stylesheet" href="css/log_in.css" media="screen">
    <script src="js/jquery.js"></script>
</head>
<body class="log_in">
    <form  method="post" class="log_in_form">
         <div class="log_in_dev">
            <label for="User_Name" class="label_log">اسم المستخدم</label>
            <input type="text" name="User_Name" id="User_Name" required >
         </div>
         <br>
         <div class="log_in_dev">
            <label for="Password" class="label_log" id="pass_label">كلمة المرور</label>
            <input type="password" name="Password" id="Password" required >
         </div>
         <br>
         <input type="submit" value="تسجيل الدخول" class="log_in_sub">
    </form>
</body>
</html>