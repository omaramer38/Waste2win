<?php
include("inc/check_role.php"); // تأكد من أن المستخدم مسجل الدخول وله الدور المناسب
include("../fun/alert.php"); // تضمين ملف التنبيهات



// جلب منتج واحد عشوائي مؤهل
$select_product = $pdo->prepare("
    SELECT * 
    FROM products 
    WHERE status = 1 
      AND points <= (SELECT points FROM customers WHERE custid = ?)
    ORDER BY RAND()
    LIMIT 1
");
$select_product->execute([$custid]);
$recommended_product = $select_product->fetch();

// إرسال النتيجة كـ JSON
echo json_encode([
    "recommended_product" => $recommended_product
], JSON_UNESCAPED_UNICODE);











?>