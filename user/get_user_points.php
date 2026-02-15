<?php
session_start();
include("../conn.php");

if(!isset($_SESSION["custid"])){
    header("location:../../index.php");
    exit;
}else{
    $custid = $_SESSION["custid"];
    $cust_name = $_SESSION["cust_name"];


    $selec_points = $pdo->prepare("SELECT points FROM customers WHERE custid = ?");
    $selec_points->execute([$custid]);
    $user_points = $selec_points->fetch();


    echo json_encode([
        "points" => $user_points["points"]
    ]);

    
        

}







?>