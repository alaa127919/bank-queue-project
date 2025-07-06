<?php
session_start();

$page_title = 'Dashbord';

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
} else {
    include "connect.php";

    // جلب جميع أسماء المستخدمين لعرضها في القائمة المنسدلة
    $users = [];
    $stmt = $con->prepare("SELECT username FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // دوال إضافية للوحة التحكم
    function getlatest($select, $table, $order, $Limet) {
        global $con;
        $getstmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $Limet");
        $getstmt->execute();
        return $getstmt->fetchAll();
    }

    function countItems($item, $table) {
        global $con;
        $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
        $stmt2->execute();
        return $stmt2->fetchColumn();
    }

    $LatestUsers = 5;
    $TheLatest = getlatest("*", "users", "userid", $LatestUsers);
}

// ✅ معالجة إنشاء مستخدم جديد
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_user'])) {
    $username = $_POST['username'];
    $password = sha1($_POST['password']); // تشفير كلمة المرور

    $stmt = $con->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $exists = $stmt->fetchColumn();

    if ($exists == 0) {
        $stmt = $con->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $password]);
        echo "<script>alert('✅ تم إنشاء المستخدم بنجاح!');</script>";
    } else {
        echo "<script>alert('⚠ هذا الاسم مستخدم مسبقًا!');</script>";
    }
}

// ✅ معالجة تعديل كلمة المرور لمستخدم موجود
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $selected_user = $_POST['update_username'];
    $new_password = sha1($_POST['update_password']);

    $stmt = $con->prepare("UPDATE users SET password = ? WHERE username = ?");
    $stmt->execute([$new_password, $selected_user]);

    echo "<script>alert('✅ تم تحديث كلمة المرور بنجاح للمستخدم $selected_user');</script>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <script src="js/jquery.js"></script>
    <link rel="stylesheet" href="css/setting.css">
    <style>
        #back_button {
    width: 65px;
    height: 65px;
    position: fixed;
    background-color: rgba(0,0,0,0);
    background-image: url(../img/icon.png);
    background-size: cover;
    border: none;
    left: 35px;
    top: 180px ;
}
#log_out {
    width: 125px;
    height: 125px;
    position: fixed;
    background-color: transparent;
    background-image: url(img/log_out.ico);
    background-size: contain; /* تغيير من cover إلى contain */
    background-repeat: no-repeat;
    background-position: center;
    border: none;
    left: 5px;
    top: 500px;
    transition: background-color 0.3s ease, border-radius 0.3s ease;
}

#log_out:hover {
    background-color: rgba(128, 128, 128, 0.3); /* رمادي شفاف بدل رمادي صلب */
    border-radius: 15px;
}


    </style>
    <script src="js/setting.js"></script>

</head>
<body>
    <form action="" method="post" id="new_user_form">
        <input type="button" value="إنشاء مستخدم جديد" id="new_user" class="user_button">
        <input type="button" value="تعديل على مستخدم موجود"  id="update_user" class="user_button">
        <div id="div_new_user">
            <input type="text" name="username" id="new_user_id" class="new_user_input">
            <label for="new_user_id"> :اسم المستخدم</label>
            <br>
            <br>
            <input type="password" name="password" id="password_new_user" class="new_user_input">
            <label for="password_new_user"> :كلمة المرور</label>
            <br>
            <br>
            <input type="submit"  name="new_user" value="تأكيد" id="new_user_submit">
        </div>
        <div id="div_update_user">
            <select name="update_username" id="update_user_id" class="update_user_input">
                <?php foreach ($users as $user): ?>
                    <option value="<?= htmlspecialchars($user) ?>"><?= htmlspecialchars($user) ?></option>
                <?php endforeach; ?>
            </select>
            <label for="update_user_id"> :اسم المستخدم</label>
            <br>
            <br>
            <input type="password" name="update_password" id="pupdate_user_password" class="update_user_input">
            <label for="pupdate_user_password"> :كلمة المرور</label>
            <br>
            <br>
            <input type="submit" name="update_user" value="تعديل" id="update_button" >
        </div>
    </form>
    <form id="background">
        <div class="file-upload">
            <label for="fileUpload">اضغط لتغيير الخلفية</label>
            <input type="file" id="fileUpload" accept=".jpg, .jpeg, .png, .pdf">
        </div>
        <p id="error-message"></p>
        <div>
            <input type="button" value="تأكيد" id="background_submit" class="file-upload">
        </div>
    </form>

    <div id="alertBox">
        <h2>تنبيه</h2>
        <p>تم تغيير الخلفية بنجاح!</p>
        <button onclick="closeAlert()">إغلاق</button>
    </div>

        
    <form action="">
        <input type="button" value="" id="new_user_button" title="إضافة مستخدم جديد">
        <input type="button" value="" id="back_button" title="خلفية جهاز القطع">
        <a href="logout.php" id="log_out" title="تسجيل الخروج" onclick="return confirm('هل أنت متأكد من تسجيل الخروج؟')"></a>
    </form>
</body>
</html>