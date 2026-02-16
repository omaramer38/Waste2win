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

    if(isset($_POST["title"]) && isset($_POST["quantity"]) && isset($_POST["type_of_pro"]) && isset($_POST["points"]) && isset($_POST["status"])){
        
        $title = trim($_POST["title"]);
        $comment = trim($_POST["comment"]);
        $quantity = trim($_POST["quantity"]);
        $type_of_pro = trim($_POST["type_of_pro"]);
        $points = trim($_POST["points"]);
        $status = trim($_POST["status"]);

        $img = $_FILES["img"];
        $img_name = $img["name"];
        $img_tmp_name = $img["tmp_name"];
        $img_error = $img["error"];
        $img_size = $img["size"];
        $img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
        $allowed_ext = array("jpg", "jpeg", "png", "gif");
        if(in_array($img_ext, $allowed_ext)){
            if($img_error === 0){
                if($img_size <= 5 * 1024 * 1024){ // 5MB
                    $new_img_name = uniqid("pro_", true) . "." . $img_ext;
                    $img_upload_path = "../../imgs/" . $new_img_name;
                    move_uploaded_file($img_tmp_name, $img_upload_path);

                    $insert_product = $pdo->prepare("INSERT INTO products (title, comment, quantity, type_of_pro, points, status) VALUES (?,?,?,?,?,?)");
                    if($insert_product->execute([$title, $comment, $quantity, $type_of_pro, $points, $status])){


                        $proid = $pdo->lastInsertId();
                        $insert_img = $pdo->prepare("INSERT INTO pro_imgs (proid, img) VALUES (?,?)");
                        $insert_img->execute([$proid, $new_img_name]);

                        $_SESSION["alert"] = [
                            "type" => "success" ,
                            "msg" => "تم إضافة المنتج بنجاح."
                        ];
                }else{
                    $_SESSION["alert"] = [
                        "type" => "danger" ,
                        "msg" => "حجم الصورة كبير جدًا. الحد الأقصى المسموح به هو 5 ميجابايت."
                    ];
                    header("location:../store.php");
                    exit;
                }
            }else{
                $_SESSION["alert"] = [
                    "type" => "danger" ,
                    "msg" => "حدث خطأ أثناء رفع الصورة. يرجى المحاولة مرة أخرى."
                ];
                header("location:../store.php");
                exit;
            }

        }else{
            $_SESSION["alert"] = [
                "type" => "danger" ,
                "msg" => "حدث خطأ أثناء إضافة المنتج. يرجى المحاولة مرة أخرى."
            ];
        }
        header("location:../store.php");

    }
}

}
?>