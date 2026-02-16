<?php
include("../inc/check_role.php"); // تأكد من أن المستخدم مسجل الدخول وله الدور المناسب
include("../../fun/alert.php"); // تضمين ملف التنبيهات

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    try {

        $custid = $_SESSION['custid']; // تأكد انك معرف الcustid من الجلسة
        $select_user_info = $pdo->prepare("SELECT * FROM customers WHERE custid = ?");
        $select_user_info->execute([$custid]);  
        $user_info = $select_user_info->fetch();

        $type_of_order = 1; // نوع الطلب: إعادة التدوير
        $cityid = $user_info['cityid'];
        $location = $_POST['location'] ?? NULL; // لو عندك location من الفورم
        $wasteids = $_POST['wasteid'];        // Array of waste ids
        $images = $_FILES['waste_image'];     // Array of uploaded images

        // -------------------------
        // INSERT into recy_order
        // -------------------------
        $insert_order = $pdo->prepare("
            INSERT INTO recy_order
            (custid, cityid, location, statusid, date, workerid, comment_rej, type_of_order)
            VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)
        ");

        $insert_order->execute([
            $custid,
            $cityid,
            $location,
            1,    // حالة الطلب: جديد
            NULL, // workerid
            NULL, // comment_rej
            $type_of_order
        ]);

        $order_id = $pdo->lastInsertId();

        // -------------------------
        // INSERT into recy_order_details (order_info)
        // -------------------------
        for($i=0; $i<count($wasteids); $i++){
            $wasteid = $wasteids[$i];

            // رفع الصورة
            $image_name = time() . '_' . basename($images['name'][$i]);
            $image_tmp  = $images['tmp_name'][$i];
            if(!move_uploaded_file($image_tmp, "../uploads/$image_name")){
                throw new Exception("فشل رفع الصورة رقم " . ($i+1));
            }

            $insert_details = $pdo->prepare("
                INSERT INTO order_info
                (recyid,img)
                VALUES (?, ?)
            ");
            $insert_details->execute([
                $order_id,
                $image_name
            ]);
        }

        // -------------------------
        // رسالة نجاح
        // -------------------------
        $_SESSION['alert'] = [
            'type' => 'success',
            'msg' => 'تم تقديم طلب إعادة التدوير بنجاح! رقم الطلب الخاص بك هو #' . $order_id
        ];

    } catch (Exception $e) {
        // لو حصل أي خطأ
        $_SESSION['alert'] = [
            'type' => 'error',
            'msg' => 'حدث خطأ: ' . $e->getMessage()
        ];
    }

    header("location:../Recycle.php");
    exit;
}
?>
