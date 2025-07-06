<?php 
include 'connect.php';
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
            <label for="windows_num" class="label_log">اختر رقم النافذة</label>
            <select id="windows_num">
                <option value="win_1">1</option>
                <option value="win_2">2</option>
                <option value="win_3">3</option>
            </select>
         </div>
         <br>
         <div class="log_in_dev">
            <label for="User_Name" class="label_log">اسم المستخدم</label>
            <input type="text" name="User_Nmae" id="User_Name" required >
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