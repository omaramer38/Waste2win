<?php

require("../../conn.php");
include("../../fun/alert.php");
session_start();

if (!isset($_SESSION["userid"])) {
    header("location:../../index.php");
    exit;
}
if (isset($_POST["recyid"])) {
    $recyid = trim($_POST["recyid"]);
    // تحديث حالة الطلب إلى "تم الرفض"
    $update_status = $pdo->prepare("UPDATE recy_order SET statusid = 3 WHERE recyid = ?");
    if ($update_status->execute([$recyid])) {
        $_SESSION["alert"] = [
            "type" => "success",
            "msg" => "تم رفض طلب إعادة التدوير بنجاح."
        ];
    } else {
        $_SESSION["alert"] = [
            "type" => "danger",
            "msg" => "حدث خطأ أثناء رفض طلب إعادة التدوير."
        ];
    }
    header("location:../recycling.php");
    exit;
} else {
    header("location:../recycling.php");
    exit;
}







?>