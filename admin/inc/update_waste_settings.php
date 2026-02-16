<?php
require("../../conn.php");
include("../../fun/alert.php");
session_start();

if(!isset($_SESSION["userid"])){
    header("location:../../index.php");
    exit;
}else{
    $userid = $_SESSION["userid"];
    $user_name = $_SESSION["user_name"];
    $role = $_SESSION["role"];

    if(isset($_POST["بلاستيك"]) && isset($_POST["ورق"]) && isset($_POST["معدن"]) && isset($_POST["الكتروني"]) && isset($_POST["بطاريات"])){
        
        $plastic_points = trim($_POST["بلاستيك"]);
        $paper_points = trim($_POST["ورق"]);
        $metal_points = trim($_POST["معدن"]);
        $electronic_points = trim($_POST["الكتروني"]);
        $battery_points = trim($_POST["بطاريات"]);

        $update_waste_settings = $pdo->prepare("UPDATE wastes SET points = CASE 
            WHEN name = 'بلاستيك' THEN ? 
            WHEN name = 'ورق' THEN ? 
            WHEN name = 'معدن' THEN ? 
            WHEN name = 'الكتروني' THEN ? 
            WHEN name = 'بطاريات' THEN ? 
            END
            WHERE name IN ('بلاستيك', 'ورق', 'معدن', 'الكتروني', 'بطاريات')");
        
        if($update_waste_settings->execute([$plastic_points, $paper_points, $metal_points, $electronic_points, $battery_points])){
            $_SESSION["alert"] = [
                "type" => "success" ,
                "msg" => "تم تحديث إعدادات النفايات بنجاح."
            ];
        }else{
            $_SESSION["alert"] = [
                "type" => "danger" ,
                "msg" => "حدث خطأ أثناء تحديث إعدادات النفايات. يرجى المحاولة مرة أخرى."
            ];
        }

        header("location:../setting.php");

    }
}

?>