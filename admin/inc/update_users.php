<?php
require("../../conn.php");
include("../../fun/alert.php");
session_start();

if (!isset($_SESSION["userid"])) {
    header("location:../../index.php");
    exit;
}

// echo "<pre>";
// print_r($_POST);
// exit;

if (
    isset($_POST["custid"], $_POST["cust_name"], $_POST["email"], $_POST["points"],
          $_POST["status_account"], $_POST["cityid"], $_POST["phone"]
    ) ) {

   $custid            = $_POST['custid'];
    $cust_name         = $_POST['cust_name'];
    $email             = $_POST['email'];
    $points            = $_POST['points'];
    $status_account    = $_POST['status_account'];
    $registration_date = $_POST['registration_date'];
    $orders_count      = $_POST['orders_count'];
    $cityname          = $_POST['cityname'];
    $phone             = $_POST['phone'];
    $cityid            = $_POST['cityid'];
    // تحديث بيانات العميل
    $update_customer = $pdo->prepare("UPDATE customers SET cust_name=?, email=?, points=?, status_account=?, cityid=?, phone=? WHERE custid=?");
    $update_customer->execute([$cust_name, $email, $points, $status_account, $cityid, $phone, $custid]);
    $_SESSION["alert"] = ["type" => "success", "msg" => "تم تحديث بيانات العميل بنجاح"];
    header("location:../users.php");
    exit;

} else {
    $_SESSION["alert"] = ["type" => "danger", "msg" => "جميع الحقول مطلوبة"];
    header("location:../users.php");
    exit;
}






?>