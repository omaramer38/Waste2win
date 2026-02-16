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

    if(isset($_POST["site_name"]) && isset($_POST["logo"]) && isset($_POST["title_1"]) && isset($_POST["about"])){
        
        $site_name = trim($_POST["site_name"]);
        $logo = trim($_POST["logo"]);
        $title_1 = trim($_POST["title_1"]);
        $about = trim($_POST["about"]);


        $update_basic_site = $pdo->prepare("UPDATE basic_site SET site_name = ? , logo = ? , title_1 = ? , about = ?  WHERE site_id = 1");
        
        if($update_basic_site->execute([$site_name, $logo, $title_1, $about])){
            $_SESSION["alert"] = [
                "type" => "success" ,
                "msg" => "تم تحديث إعدادات الموقع بنجاح."
            ];
        }else{
            $_SESSION["alert"] = [
                "type" => "danger" ,
                "msg" => "حدث خطأ أثناء تحديث إعدادات الموقع. يرجى المحاولة مرة أخرى."
            ];
        }

        header("location:../setting.php");

    }
}


?>