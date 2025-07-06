<?php
session_start();
include 'connect.php';

// تحقق من أن هناك بطاقة حالية محفوظة
if (!isset($_SESSION['last_called_id'])) {
    http_response_code(400);
    echo "لا توجد بطاقة حالية للتأجيل";
    exit();
}

$ticket_id = $_SESSION['last_called_id'];

// تعليم البطاقة كـ "مؤجلة" بدلًا من حذفها
$stmt = $con->prepare("UPDATE in_progress_transactions SET postponed = 1 WHERE id = ?");
$stmt->execute([$ticket_id]);

// إزالة البطاقة من الجلسة مؤقتًا
unset($_SESSION['last_called_id']);
unset($_SESSION['last_called_ticket']);

echo "تم تأجيل البطاقة";
?>
