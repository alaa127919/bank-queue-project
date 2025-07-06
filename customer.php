<?php
include 'connect.php'; // الاتصال بقاعدة البيانات
session_start();
// التحقق من وجود بيانات قادمة عبر POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['transaction_type'])) {
    $transaction_type = intval($_POST['transaction_type']); // نوع العملية (إيداع أو سحب)
    $user_id = 1; // افترض أن المستخدم مسجل مسبقًا، يمكنك استبداله بالجلسات (SESSION)
    
    // جلب الوقت المطلوب لإنهاء المعاملة
    $query = "SELECT time_to_finish FROM transactions_type WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $transaction_type);
    $stmt->execute();
    $stmt->bind_result($time_to_finish);
    $stmt->fetch();
    $stmt->close();

    if ($time_to_finish) {
        $transaction_duration = date('Y-m-d H:i:s', strtotime("+$time_to_finish hours")); // حساب وقت انتهاء المعاملة
        
        // تحديد النافذة المناسبة
        $window_id = null;
        $query = "SELECT window_id FROM transaction_window 
                  WHERE transaction_type_id = ? AND transaction_duration > NOW() 
                  GROUP BY window_id ORDER BY COUNT(*) ASC LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $transaction_type);
        $stmt->execute();
        $stmt->bind_result($window_id);
        $stmt->fetch();
        $stmt->close();

        if (!$window_id) {
            // إذا لم يكن هناك نافذة مشغولة، اختر أول نافذة متاحة
            $query = "SELECT id FROM windows WHERE accepted_transaction_id = ? LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $transaction_type);
            $stmt->execute();
            $stmt->bind_result($window_id);
            $stmt->fetch();
            $stmt->close();
        }

        // إضافة المعاملة إلى جدول in_progress_transactions
        $query = "INSERT INTO in_progress_transactions (user_id, transactions_type_id, window_id, transaction_duration)
                  VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiis", $user_id, $transaction_type, $window_id, $transaction_duration);
        $stmt->execute();
        $stmt->close();

        // تحديث سجل transaction_window لتحديد وقت انتهاء المعاملة
        $query = "INSERT INTO transaction_window (window_id, transaction_type_id, transaction_duration)
                  VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iis", $window_id, $transaction_type, $transaction_duration);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('تم تسجيل المعاملة بنجاح!');</script>";
    } else {
        echo "<script>alert('حدث خطأ، تأكد من نوع المعاملة!');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <title>customer</title>
        <meta name="description" content="This Is customer interface"/>
        <link rel="stylesheet" href="css/customer.css" media="screen">
        <script src="js/jquery.js"></script>
        <script src="js/customer.js"></script>
    </head>
    <body class="customer">
        <form action="process_transaction.php" method="post">
            <div>
                <button type="submit" name="transaction_type" value="1" class="button_cust">إيداع</button>
            </div>
            <br>
            <div>
                <button type="submit" name="transaction_type" value="2" class="button_cust">سحب</button>
            </div>
            <br>
            <div>
                <button type="submit" name="transaction_type" value="3" class="button_cust">إنشاء حساب</button>
            </div>

        </form>


    </body>
</html>