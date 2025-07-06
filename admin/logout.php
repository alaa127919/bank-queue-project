<?php
session_start();

// حذف جميع بيانات الجلسة
$_SESSION = [];
session_unset();
session_destroy();

// حذف الكوكيز إذا كانت مُستخدمة
if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, '/');
}
if (isset($_COOKIE['window_number'])) {
    setcookie('window_number', '', time() - 3600, '/');
}

// الانتقال إلى صفحة تسجيل الدخول
header("Location: index.php");
exit();
