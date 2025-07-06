<?php
session_start();

if (isset($_SESSION['last_called_ticket'])) {
    echo $_SESSION['last_called_ticket'];
} else {
    echo "لا توجد بطاقة تم استدعاؤها بعد";
}
?>
