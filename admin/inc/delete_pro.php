<?php
require("../../conn.php");
include("../../fun/alert.php");
session_start();
if (!isset($_SESSION["userid"])) {
    header("location:../../index.php");
    exit;
}
if (isset($_POST["proid"])) {
    $proid = trim($_POST["proid"]);

    // جلب اسم الصورة لحذفها من المجلد
    $get_img = $pdo->prepare("SELECT img FROM pro_imgs WHERE proid=?");
    $get_img->execute([$proid]);
    $img_data = $get_img->fetch(PDO::FETCH_ASSOC);
    if ($img_data) {
        $img_name = $img_data["img"];
        $img_path = "../../imgs/" . $img_name;
        if (file_exists($img_path)) {
            unlink($img_path); // حذف الصورة من المجلد
        }
    }

    // حذف السجل من جدول الصور
    $delete_img = $pdo->prepare("DELETE FROM pro_imgs WHERE proid=?");
    $delete_img->execute([$proid]);

    // حذف السجل من جدول المنتجات
    $delete_product = $pdo->prepare("DELETE FROM products WHERE proid=?");
    if ($delete_product->execute([$proid])) {
        $_SESSION["alert"] = [
            "type" => "success",
            "msg" => "تم حذف المنتج بنجاح."
        ];
    } else {
        $_SESSION["alert"] = [
            "type" => "danger",
            "msg" => "حدث خطأ أثناء حذف المنتج."
        ];
    }
    header("location:../store.php");
    exit;
} else {
    header("location:../store.php");
    exit;
}