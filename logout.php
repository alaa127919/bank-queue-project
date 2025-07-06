<?php
session_start();
include 'connect.php';

// حذف سجل المستخدم من جدول users_online إذا كان مسجّل
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $delete_stmt = $con->prepare("DELETE FROM users_online WHERE username = ?");
    $delete_stmt->execute([$username]);
}

// حذف جميع بيانات الجلسة
session_unset();
session_destroy();

// حذف الكوكيز إذا كانت هناك بيانات مخزنة
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// إعادة التوجيه إلى صفحة تسجيل الدخول
header("Location: index.php");
exit();
?>
