<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['window_number'])) {
    http_response_code(403);
    echo "Unauthorized";
    exit();
}

$window_id = $_SESSION['window_number'];

// إذا كانت هناك بطاقة محفوظة سابقًا، نحذفها وننتقل للي بعدها
if (isset($_SESSION['last_called_id'])) {
    $prev_id = $_SESSION['last_called_id'];
    $delete = $con->prepare("DELETE FROM in_progress_transactions WHERE id = ?");
    $delete->execute([$prev_id]);

    // حذف الجلسة لتفعيل عرض البطاقة التالية الجديدة
    unset($_SESSION['last_called_id']);
    unset($_SESSION['last_called_ticket']);
}

// جلب البطاقة التالية (غير المؤجلة أولًا)
$stmt = $con->prepare("
    SELECT id, transactions_type_id 
    FROM in_progress_transactions 
    WHERE window_id = ? AND postponed = 0 
    ORDER BY id ASC 
    LIMIT 1
");
$stmt->execute([$window_id]);
$row = $stmt->fetch();

// إن لم توجد، نبحث في المؤجّلة
if (!$row) {
    $stmt = $con->prepare("
        SELECT id, transactions_type_id 
        FROM in_progress_transactions 
        WHERE window_id = ? AND postponed = 1 
        ORDER BY id ASC 
        LIMIT 1
    ");
    $stmt->execute([$window_id]);
    $row = $stmt->fetch();
}

if ($row) {
    // حفظ البطاقة في الجلسة لعدم تكرار عرضها إلا بالحذف لاحقًا
    $type_stmt = $con->prepare("SELECT name FROM windows WHERE id = ?");
    $type_stmt->execute([$row['transactions_type_id']]);
    $letter = $type_stmt->fetchColumn() ?: '?';

    $formatted = str_pad($row['id'], 3, "0", STR_PAD_LEFT);
    $fullTicket = $letter . $formatted;

    $_SESSION['last_called_ticket'] = $fullTicket;
    $_SESSION['last_called_id'] = $row['id'];

    echo $fullTicket;
} else {
    echo "لا توجد بطاقات حالياً";
}
?>
