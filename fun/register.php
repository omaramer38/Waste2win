<?php
require("../conn.php"); 
include("alert.php"); 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if(!empty($_POST['username']) && 
       !empty($_POST['email']) && 
       !empty($_POST['password']) && 
       !empty($_POST['phone'])
    ){
        $name     = trim($_POST['username']);
        $email    = trim($_POST['email']);
        $password = md5($_POST['password']); 
        $phone    = trim($_POST['phone']);
        $city_id  = isset($_POST['city']) ? trim($_POST['city']) : null;

        $checkEmail = $pdo->prepare("SELECT email,phone,status_account FROM customers WHERE email = ? OR phone = ?");
        $checkEmail->execute([$email , $phone]);

        if ($checkEmail->rowCount() > 0) {

            $status_account = $checkEmail->fetch(PDO::FETCH_ASSOC)['status_account'];
            if($status_account == '1'){
                $_SESSION["alert"] = [
                    "type" => "danger",
                    "msg"  => "هذا الحساب محظور لدينا مخالفته لشروط الاستخدام."
                ];
                header("Location: ../index.php");
                exit;
            }elseif($status_account == '2'){
                $_SESSION["alert"] = [
                    "type" => "danger",
                    "msg"  => "هذا الحساب موجود بالفعل  تحقق من البريد الالكتروني او رقم الهاتف."
                ];
                header("Location: ../index.php");
                exit;

            }

            
        }
    // insert code
        $stmt = $pdo->prepare("INSERT INTO customers (cust_name, email, password, phone , cityid) VALUES (?, ?, ?, ?,?)");
        

        if ($stmt->execute([$name, $email, $password, $phone , $city_id])) {
            $_SESSION["alert"] = [
                "type" => "success",
                "msg"  => "تم التسجيل بنجاح!"
            ];
            $_SESSION['user_email'] = $email;
            header("Location: ../index.php");
            exit;
        } else {
            $_SESSION["alert"] = [
                "type" => "danger",
                "msg"  => "حدث خطأ أثناء التسجيل. حاول مرة أخرى."
            ];
            header("Location: ../index.php");
            exit;
        }
    }
}
?>