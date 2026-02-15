<?php
include("inc/check_role.php"); // تأكد من أن المستخدم مسجل الدخول وله الدور المناسب
include("../fun/alert.php"); // تضمين ملف التنبيهات



    $selec_points = $pdo->prepare("SELECT points FROM customers WHERE custid = ?");
    $selec_points->execute([$custid]);
    $user_points = $selec_points->fetch();


    echo json_encode([
        "points" => $user_points["points"]
    ]);



?>