<?php
require("../../conn.php");
include("../../fun/alert.php");
session_start();

if (!isset($_SESSION["userid"])) {
    header("location:../../index.php");
    exit;
}

if (
    isset($_POST["proid"], $_POST["title"], $_POST["comment"], $_POST["quantity"],
          $_POST["type_of_pro"], $_POST["points"], $_POST["status"])
) {

    $proid       = trim($_POST["proid"]);
    $title       = trim($_POST["title"]);
    $comment     = trim($_POST["comment"]);
    $quantity    = trim($_POST["quantity"]);
    $type_of_pro = trim($_POST["type_of_pro"]);
    $points      = trim($_POST["points"]);
    $status      = trim($_POST["status"]);
    $old_img     = $_POST["old_img"] ?? null;

    /* ================= IMAGE UPLOAD ================= */
    if (!empty($_FILES["img"]["name"])) {

        $img        = $_FILES["img"];
        $img_ext    = strtolower(pathinfo($img["name"], PATHINFO_EXTENSION));
        $allowed    = ["jpg", "jpeg", "png", "gif"];

        if (!in_array($img_ext, $allowed)) {
            $_SESSION["alert"] = ["type" => "danger", "msg" => "نوع الصورة غير مدعوم"];
            header("location:../store.php");
            exit;
        }

        if ($img["error"] !== 0) {
            $_SESSION["alert"] = ["type" => "danger", "msg" => "حدث خطأ أثناء رفع الصورة"];
            header("location:../store.php");
            exit;
        }

        if ($img["size"] > 5 * 1024 * 1024) {
            $_SESSION["alert"] = ["type" => "danger", "msg" => "حجم الصورة يجب ألا يتجاوز 5MB"];
            header("location:../store.php");
            exit;
        }

        $new_img = uniqid("pro_", true) . "." . $img_ext;
        move_uploaded_file($img["tmp_name"], "../../imgs/" . $new_img);

        // حذف الصورة القديمة
        if ($old_img && file_exists("../../imgs/" . $old_img)) {
            unlink("../../imgs/" . $old_img);
        }

        // تحديث الصورة
        $update_img = $pdo->prepare("UPDATE pro_imgs SET img=? WHERE proid=?");
        $update_img->execute([$new_img, $proid]);
    }

    /* ================= UPDATE PRODUCT ================= */
    $update_product = $pdo->prepare("
        UPDATE products 
        SET title=?, comment=?, quantity=?, type_of_pro=?, points=?, status=?
        WHERE proid=?
    ");

    if ($update_product->execute([
        $title,
        $comment,
        $quantity,
        $type_of_pro,
        $points,
        $status,
        $proid
    ])) {

        $_SESSION["alert"] = [
            "type" => "success",
            "msg"  => "تم تعديل المنتج بنجاح ✅"
        ];
    } else {
        $_SESSION["alert"] = [
            "type" => "danger",
            "msg"  => "فشل تعديل المنتج ❌"
        ];
    }

    header("location:../store.php");
    exit;
}
