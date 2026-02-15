<?php

include("inc/check_role.php"); // تأكد من أن المستخدم مسجل الدخول وله الدور المناسب
include("../fun/alert.php"); // تضمين ملف التنبيهات
    $user_points = $_SESSION["user_points"];

    if(isset($_POST["proid"]) && isset($_POST["points_needed"])){
        $proid = $_POST["proid"];
        $points_needed = $_POST["points_needed"];

        if($user_points >= $points_needed){
           
            $_SESSION["user_points"] = $user_points - $points_needed;

            $_SESSION["cart"][] = [
                "proid" => $proid,
                "points_needed" => $points_needed
            ];

            $_SESSION["alert"] = [
                    "type" => "success" ,
                    "msg" => "تم إضافة المنتج إلى سلة المشتريات بنجاح."
                ];
                
        }else{
           $_SESSION["alert"] = [
                    "type" => "danger" ,
                    "msg" => "ليس لديك نقاط كافية لاستبدال هذا المنتج."
                ];
                
        }

        header("location:../shop.php");
        

    }




?>