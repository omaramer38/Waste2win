<?php
    include("check_role.php");
    include("../../fun/alert.php");
    $role = $_SESSION["role"];

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['workerid']) && isset($_POST['recyid'])) {
            $workerid = $_POST['workerid'];
            $recyid = $_POST['recyid'];
            $points = $_POST['points'];

            // Update the recycling request to assign the worker and change status to approved
            $update_request = $pdo->prepare("
                UPDATE recy_order
                SET workerid = ? , statusid = 2, total_points = ?
                WHERE recyid = ?   
            ");

            $update_customers = $pdo->prepare("
                UPDATE customers
                SET points = points + ?
                WHERE custid = (SELECT custid FROM recy_order WHERE recyid = ?)
            ");
            
            

            if($update_request->execute([$workerid, $points, $recyid])){
                $update_customers->execute([$points, $recyid]);
                $_SESSION["alert"] = [
                    "type" => "success" ,
                    "msg" => "تم تعيين المندوب والموافقة على الطلب بنجاح."
                ];
            }else{
                $_SESSION["alert"] = [
                    "type" => "danger" ,
                    "msg" => "حدث خطأ أثناء تحديث الطلب. يرجى المحاولة مرة أخرى."
                ];
            }

            header("location:../recycling.php");

        } else {
            $_SESSION["alert"] = [
                "type" => "danger" ,
                "msg" => "لم يتم استلام جميع البيانات المطلوبة. يرجى المحاولة مرة أخرى."
            ];
            header("location:../recycling.php");
        }

    } else {
        header("location:../recycling.php");
        exit;
    }

 
?>