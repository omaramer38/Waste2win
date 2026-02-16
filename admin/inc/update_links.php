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


    if(isset($_POST["facebook"]) && isset($_POST["instagram"]) && isset($_POST["twitter"]) && isset($_POST["linkedin"])){
        
        $facebook = trim($_POST["facebook"]);
        $instagram = trim($_POST["instagram"]);
        $twitter = trim($_POST["twitter"]);
        $linkedin = trim($_POST["linkedin"]);

        $update_links = $pdo->prepare("UPDATE communication_links SET facebook = ? , insta = ? , twitter = ? , linkedin = ?  WHERE link_id = 1");
        if($update_links->execute([$facebook, $instagram, $twitter, $linkedin])){
            $_SESSION["alert"] = [
                "type" => "success" ,
                "msg" => "تم تحديث روابط التواصل الاجتماعي بنجاح."
            ];
        }else{
            $_SESSION["alert"] = [
                "type" => "danger" ,
                "msg" => "حدث خطأ أثناء تحديث روابط التواصل الاجتماعي. يرجى المحاولة مرة أخرى."
            ];
        }
        header("location:../setting.php");

    }

}

?>