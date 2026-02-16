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

    if(isset($_POST["email"]) && isset($_POST["phone"]) && isset($_POST["location"]) && isset($_POST["email_support"])){
        
        $email = trim($_POST["email"]);
        $phone = trim($_POST["phone"]);
        $location = trim($_POST["location"]);
        $email_support = trim($_POST["email_support"]);

        $update_communication_info = $pdo->prepare("UPDATE communication SET email = ? , phone = ? , location = ? , email_support = ?  WHERE comid = 1");
        if($update_communication_info->execute([$email, $phone, $location, $email_support])){
            $_SESSION["alert"] = [
                "type" => "success" ,
                "msg" => "تم تحديث معلومات التواصل بنجاح."
            ];
        }else{
            $_SESSION["alert"] = [
                "type" => "danger" ,
                "msg" => "حدث خطأ أثناء تحديث معلومات التواصل. يرجى المحاولة مرة أخرى."
            ];
        }
        header("location:../setting.php");

    }
}


?>