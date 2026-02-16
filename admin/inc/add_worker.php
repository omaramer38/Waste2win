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

    if( isset($_POST["user_name"], $_POST["phone"], $_POST["salary"], 
              $_POST["status"], $_POST["city_work"], $_POST["email"])
        ) {
        $user_name  = trim($_POST["user_name"]);
        $phone      = trim($_POST["phone"]);
        $email      = trim($_POST["email"] ?? '');
        $salary     = trim($_POST["salary"]);
        $password   = trim($_POST["password"] ?? '');
        $pass_md5   = md5($password);
        $status     = trim($_POST["status"]);
        $city_work     = trim($_POST["city_work"]);
        $worker_role = 2; // دور العامل

        // إضافة عامل جديد
        $insert_worker = $pdo->prepare("INSERT INTO users (user_name, phone, email, password, salary, role, statusid, city_work) VALUES (?,?,?,?,?,?,?,?)");
        if($insert_worker->execute([$user_name, $phone, $email, $pass_md5, $salary, $worker_role, $status, $city_work])) {
            $_SESSION["alert"] = ["type" => "success", "msg" => "تم إضافة العامل بنجاح"];
            header("location:../worker.php");
            exit;
        } else {
            $_SESSION["alert"] = ["type" => "danger", "msg" => "حدث خطأ أثناء إضافة العامل"];
            header("location:../worker.php");
            exit;
        }
    } else {
        $_SESSION["alert"] = ["type" => "danger", "msg" => "جميع الحقول مطلوبة لإضافة عامل جديد"];
        header("location:../worker.php");
        exit;
    }

}


?>