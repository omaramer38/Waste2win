<?php
session_start();

// 🧹 امسح كل السيشن
session_unset();
session_destroy();

// 🧹 امسح الكوكيز الخاصة بالبريد وكلمة المرور
if (isset($_COOKIE['email'])) {
    setcookie('email', '', time() - 3600, '/');
}
if (isset($_COOKIE['password'])) {
    setcookie('password', '', time() - 3600, '/');
}

// 🔁 رجّعه للصفحة الرئيسية
header("Location: ../index.php");
exit();
?>
