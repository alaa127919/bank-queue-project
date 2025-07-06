<?php
include 'connect.php'; // الاتصال بقاعدة البيانات

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['transaction_type'])) {
    try {
        $transaction_type = intval($_POST['transaction_type']); // نوع المعاملة

        // 🔹 **تحديد `window_id` بناءً على نوع المعاملة (الخدمة)**
        switch ($transaction_type) {
            case 1:
                $window_id = 1; // نافذة الإيداع
                $start_number = 1;
                break;
            case 2:
                $window_id = 2; // نافذة السحب
                $start_number = 400;
                break;
            case 3:
                $window_id = 3; // نافذة إنشاء الحساب
                $start_number = 800;
                break;
            default:
                die("<script>alert('⚠ نوع معاملة غير صالح!'); window.location.href='customer.php';</script>");
        }

        // 🔍 **جلب آخر رقم تسلسلي لهذه الخدمة**
        $query = "SELECT MAX(id) FROM in_progress_transactions WHERE transactions_type_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $transaction_type, PDO::PARAM_INT);
        $stmt->execute();
        $last_number = $stmt->fetchColumn();
        $transaction_number = $last_number ? $last_number + 1 : $start_number;

        // ✅ **إدراج المعاملة بدون `user_id`**
        $query = "INSERT INTO in_progress_transactions (id, transactions_type_id, window_id, transaction_duration)
                  VALUES (?, ?, ?, NOW())";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $transaction_number, PDO::PARAM_INT);
        $stmt->bindParam(2, $transaction_type, PDO::PARAM_INT);
        $stmt->bindParam(3, $window_id, PDO::PARAM_INT);
        $stmt->execute();

                // بعد تنفيذ المعاملة بنجاح
        header("Location: print_ticket.php?num=$transaction_number&type=$transaction_type");
        exit;

    } catch (PDOException $e) {
        die("⚠ خطأ SQL: " . $e->getMessage());
    }
} else {
    die("<script>alert('⚠ البيانات غير صحيحة! تأكد من إرسال نوع المعاملة.');</script>");
}
?>
