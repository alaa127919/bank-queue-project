<?php
include 'connect.php';

$transaction_number = isset($_GET['num']) ? intval($_GET['num']) : 0;
$type = isset($_GET['type']) ? intval($_GET['type']) : 0;

// جلب الحرف أو الاسم بناءً على نوع الخدمة من جدول windows
$letter_query = $con->prepare("SELECT name FROM windows WHERE id = ? LIMIT 1");
$letter_query->bindParam(1, $type, PDO::PARAM_INT);
$letter_query->execute();
$letter = $letter_query->fetchColumn();
if (!$letter) {
    $letter = '?';
}

// تنسيق الرقم ليظهر كـ 001 - 002 - 003...
$formatted_number = str_pad($transaction_number, 3, "0", STR_PAD_LEFT);

// حساب الوقت المتوقع للانتظار
$query = "SELECT COUNT(*) FROM in_progress_transactions WHERE transactions_type_id = ?";
$stmt = $con->prepare($query);
$stmt->bindParam(1, $type, PDO::PARAM_INT);
$stmt->execute();
$count = $stmt->fetchColumn();
$estimated_wait = ($count - 1) * 15;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>بطاقة الدور</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            direction: ltr;
        }
        .ticket {
            margin-top: 100px;
        }
        .number {
            font-size: 60px;
            font-weight: bold;
        }
        @media print {
            button { display: none; }
        }
    </style>
</head>
<body onload="window.print();">
    <div class="ticket">
        <h2>bank queue system project</h2>
        <h3>مشروع تنظيم دور في بنك</h3>
        <p class="number"><?= $letter . $formatted_number ?></p>
        <p>⏳ الوقت المتوقع للانتظار: <?= $estimated_wait ?> دقيقة</p>
        <button onclick="window.print()">إعادة الطباعة</button>
    </div>
</body>
</html>
