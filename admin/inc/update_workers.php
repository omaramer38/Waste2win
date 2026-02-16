<?php
require("../../conn.php");
include("../../fun/alert.php");
session_start();

if (!isset($_SESSION["userid"])) {
    header("location:../../index.php");
    exit;
}

if( isset($_POST["userid"], $_POST["user_name"], $_POST["phone"], $_POST["salary"], 
          $_POST["status"], $_POST["cityid"], $_POST["email"])
    ) {
    $userid     = trim($_POST["userid"]);
    $user_name  = trim($_POST["user_name"]);
    $phone      = trim($_POST["phone"]);
    $email      = trim($_POST["email"] ?? '');
    $salary     = trim($_POST["salary"]);
    $status     = trim($_POST["status"]);
    $cityid     = trim($_POST["cityid"]);
    // تحديث بيانات العامل
    $update_worker = $pdo->prepare("UPDATE users SET user_name=?, phone=?, email=?, salary=?, statusid=?, city_work=? WHERE userid=?");
    $update_worker->execute([$user_name, $phone, $email, $salary, $status, $cityid, $userid]);
    $_SESSION["alert"] = ["type" => "success", "msg" => "تم تحديث بيانات العامل بنجاح"];
    header("location:../worker.php");
    exit;
} else {
    $_SESSION["alert"] = ["type" => "danger", "msg" => "حدث خطأ أثناء تحديث بيانات العامل"];
    header("location:../worker.php");
    exit;
}




?>