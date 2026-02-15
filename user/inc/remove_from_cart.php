<?php
session_start();

if (!isset($_POST['proid'])) {
    header("Location: cart.php");
    exit;
}

$remove_proid = $_POST['proid'];

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {

    foreach ($_SESSION['cart'] as $index => $item) {

        if ($item['proid'] == $remove_proid) {

            // رجع نقاط المنتج للمستخدم
            if (isset($item['points_needed'])) {
                $_SESSION['user_points'] += $item['points_needed'];
            }

            // احذف المنتج من السلة
            unset($_SESSION['cart'][$index]);
        }
    }

    // ترتيب السلة
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

header("Location: ../cart.php");
exit;