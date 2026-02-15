<?php
session_start();
include("../conn.php");
if(!isset($_SESSION["custid"])){
    header("location:../index.php");
    exit;
}else{
    $custid = $_SESSION["custid"];
    $cust_name = $_SESSION["cust_name"];



// جلب منتج واحد عشوائي مؤهل
$select_product = $pdo->prepare("
    SELECT * 
    FROM products 
    WHERE status = 1 
      AND points <= (SELECT points FROM customers WHERE custid = ?)
    ORDER BY RAND()
    LIMIT 1
");
$select_product->execute([$custid]);
$recommended_product = $select_product->fetch();

// إرسال النتيجة كـ JSON
echo json_encode([
    "recommended_product" => $recommended_product
], JSON_UNESCAPED_UNICODE);

}










?>