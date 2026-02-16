<?php

include("../inc/check_role.php"); // تأكد من أن المستخدم مسجل الدخول وله الدور المناسب
include("../../fun/alert.php"); // تضمين ملف التنبيهات

    // select user points
    $select_user_points = $pdo->prepare("SELECT points FROM customers WHERE custid = ?");
    $select_user_points->execute([$custid]);
    $user_points = $select_user_points->fetch();


    $cartItems = $_SESSION['cart'];
    if ($_SERVER['REQUEST_METHOD'] == 'POST' 
        && isset($_POST['city']) 
        && isset($_POST['street']) 
        && isset($_POST['phone']) 
        && !empty($_POST["city"]) 
        && !empty($_POST["street"]) 
        && !empty($_POST["phone"]) 
        && isset($_POST["products"]) 
        && !empty($_POST["products"])) {

        // ======================
        //  استقبال البيانات
        // ======================

        $fullname = trim($_POST["fullname"]);
        $phone    = trim($_POST["phone"]);
        $email    = trim($_POST["email"]);
        $city_id  = trim($_POST["city"]);
        $address  = trim($_POST["street"]);
        $products = $_POST["products"];  // ← مصفوفة جاهزة
        $order_date = date("Y-m-d H:i:s");

        $custid = $_SESSION["custid"];

        // حساب إجمالي النقاط
        $totalPoints = 0;
        foreach ($products as $p) {
            $totalPoints += $p['points'];
        }

        try {

            // ======================
            //  تحميل PDO
            // ======================
            $pdo->beginTransaction();

            // ======================
            // 1) إدخال الطلب في جدول الطلبات (recy_order)
            // ======================
            
            if( $totalPoints > $user_points["points"] ){
                throw new Exception("ليس لديك نقاط كافية لإتمام هذا الطلب.");
            }
            $stmt = $pdo->prepare("
                INSERT INTO recy_order 
                    (custid, cityid, location, statusid, date, comment_rej,total_points,type_of_order) 
                VALUES 
                    (?, ?, ?, ?, ?,? , ?,?)
            ");

            $statusid = 1; 
            $comment  = NULL;
            $type_of_order = 2;

            $stmt->execute([
                $custid,
                $city_id,
                $address,
                $statusid,
                $order_date,
                $comment,
                $totalPoints,
                $type_of_order

            ]);

            //  جلب رقم الطلب
            $recyid = $pdo->lastInsertId();

            // ======================
            // 2) إدخال المنتجات في جدول order_info
            // ======================
            $stmtItem = $pdo->prepare("
                INSERT INTO order_info (recyid, points)
                VALUES (?, ?)
            ");

            foreach ($products as $item) {
                $stmtItem->execute([
                    $recyid,
                    $item['points']
                ]);
            }

            // ======================
            // 3) تحديث نقاط المستخدم
            // ======================
            $newPoints = $user_points["points"] - $totalPoints;
            $_SESSION['user_points'] = $newPoints;

            $stmt2 = $pdo->prepare("UPDATE customers SET points = ? , phone = ? WHERE custid = ?");
            $stmt2->execute([$newPoints, $phone, $custid]);

            // نجاح
            $pdo->commit();

            $_SESSION["alert"] = [
                "type" => "success",
                "msg"  => "تم تسجيل طلبك بنجاح! رقم الطلب هو #$recyid"
            ];

            $_SESSION['cart'] = []; // تفريغ السلة

        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION["alert"] = [
                "type" => "error",
                "msg"  => "حدث خطأ أثناء معالجة طلبك: " . $e->getMessage()
            ];
        }

        header("Location: ../Recycle.php");
    }





?>