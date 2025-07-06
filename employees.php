<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['window_number'])) {
    header('Location: index.php');
    exit();
}
$username = $_SESSION['username'];
$windowNumber = $_SESSION['window_number'];
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>واجهة الموظف</title>
    <link rel="stylesheet" href="css/employees.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- تأثير بصري -->
    <style>
        @keyframes flashZoom {
            0%   { background-color: #fff; transform: scale(1); }
            50%  { background-color: #ffeaa7; transform: scale(1.25); }
            100% { background-color: #fff; transform: scale(1); }
        }
        #ticketDisplay.flash-effect {
            animation: flashZoom 0.6s ease-in-out 1;
        }
        .user-info {
    position: absolute;
    top: 10px;
    left: 10px;
    background-color: rgba(255, 255, 255, 0.6);
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: bold;
    font-size: 18px;
    color: #333;
    box-shadow: 0 0 5px rgba(0,0,0,0.2);
}
#ticketDisplay {
    font-size: 40px;
    font-weight: bold;
    width: 600px !important;
    height: 70px;
    margin-top: 15px;
    border-radius: 15px;
    border: 2px solid blueviolet;
    background-color: transparent; /* ← شفافية */
    box-shadow: 0 0 8px rgba(0,0,0,0.1);
    color: #333;
    text-align: center;
    transition: background-color 0.4s ease;
}



    </style>

    <!-- ربط ملف الجافاسكريبت الخارجي -->
    <script src="js/employees.js"></script>
</head>

<body class="employees">

    <div  class="user-info">
        المستخدم: <?= htmlspecialchars($username) ?> | النافذة: <?= htmlspecialchars($windowNumber) ?>
    </div>

    <div style="text-align: center; margin-top: 70px;">
        <label for="ticketDisplay" style="font-weight: bold;">رقم البطاقة الحالية:</label><br>
        <input type="text" id="ticketDisplay" readonly style="font-size: 32px; text-align: center; width: 220px;">
    </div>

    <form method="post" id="myForm">
        <div class="dev1">
            <div class="emp_dev">
                <button type="button" id="Next" class="button_emp">البطاقة التالية</button>
            </div>
            <br>
            <div class="emp_dev">
                <button type="button" id="Re-Call" class="button_emp">إعادة النداء</button>
            </div>
        </div>

        <div class="dev2">
            <div class="emp_dev2">
                <button type="button" id="Not_found" class="button_emp2">بطاقة مفقودة</button>
            </div>
            <br>
            <div class="emp_dev2">
                <button type="button" id="Stop" class="button_emp2">إيقاف مؤقت</button>
            </div>
        </div>

        <div class="dev3">
            <button type="button" id="logoutButton" class="button_emp3">تسجيل الخروج</button>
        </div>
    </form>

    <!-- صوت تنبيه -->
    <audio id="dingSound" src="sounds/ding.mp3" preload="auto"></audio>

</body>
</html>
