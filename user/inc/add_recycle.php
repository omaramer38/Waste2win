<?php
include("inc/check_role.php"); // تأكد من أن المستخدم مسجل الدخول وله الدور المناسب
include("../fun/alert.php"); // تضمين ملف التنبيهات

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $select_user_info = $pdo->prepare("SELECT * FROM customers WHERE custid = ?");
        $select_user_info->execute([$custid]);  
        $user_info = $select_user_info->fetch();

        $type_of_order = 1; // إعادة التدوير
        $cityid = $user_info['cityid'];
        $wasteid = $_POST['wasteid'];
        $amount = $_POST['amount'];
        $total_points = $_POST['total_points'];
        

        // insert order

        $insert_order = $pdo->prepare("
            INSERT INTO recy_order
            (custid, cityid, location, statusid, date, workerid, comment_rej, total_points, type_of_order)
            VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?)
        ");

        $insert_order = $insert_order->execute([
            $custid,
            $cityid,
            NULL,
            1, // حالة الطلب: جديد
            NULL, // workerid
            NULL, // comment_rej
            $total_points,
            $type_of_order
        ]);
        $order_id = $pdo->lastInsertId();
        if($insert_order){

            // insert order details
            $insert_order_details = $pdo->prepare("
                INSERT INTO order_info
                (recyid, wasteid, amount)
                VALUES (?, ?, ?)
            ");
            $insert_order_details = $insert_order_details->execute([
                $order_id,
                $wasteid,
                $amount
            ]);

            $_SESSION['alert'] = [
                'type' => 'success',
                'msg' => 'تم تقديم طلب إعادة التدوير بنجاح! رقم الطلب الخاص بك هو #' . $order_id
            ];
        }else{
            $_SESSION['alert'] = [
                'type' => 'error',
                'msg' => 'حدث خطأ أثناء تقديم طلب إعادة التدوير. يرجى المحاولة مرة أخرى.'
            ];
        }

        header("location:../Recycle.php");
        exit;

    }





?>