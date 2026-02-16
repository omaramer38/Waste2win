<?php

    include("inc/check_role.php");
    include("../fun/alert.php");

    $role = $_SESSION["role"];

    // select all recycling requests
        $status_filter = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tab'])) {
            $tab = $_POST['tab'];

            if ($tab === 'pending') {
                $status_filter = " AND s.status = 'قيد الانتظار'";
            } elseif ($tab === 'approved') {
                $status_filter = " AND s.status = 'تمت الموافقه'";
            } elseif ($tab === 'rejected') {
                $status_filter = " AND s.status = 'تم الرفض'";
            }
        }

       $select_requests = $pdo->prepare("
        SELECT
            orr.recyid,
            orr.custid,
            orr.cityid,
            orr.location,
            orr.statusid,
            orr.date,
            orr.workerid,
            orr.comment_rej,
            orr.type_of_order,

            s.status,
            c.cityname,

            cust.custid,
            cust.cust_name,
            cust.email,
            cust.points AS cust_points,

            COALESCE(SUM(oi.points), 0) AS total_points,
            COUNT(oi.infoid) AS total_items

        FROM recy_order orr

        JOIN status_of_recy_order s 
            ON orr.statusid = s.statusid

        JOIN citys c 
            ON orr.cityid = c.cityid

        JOIN customers cust 
            ON orr.custid = cust.custid

        LEFT JOIN order_info oi 
            ON orr.recyid = oi.recyid

        
        WHERE 1 = 1 $status_filter

         GROUP BY orr.recyid
    ");

$select_requests->execute();
$requests = $select_requests->fetchAll(PDO::FETCH_ASSOC);



            
    // count requests by status
    $select_status_counts = $pdo->prepare("
        SELECT 
        SUM(CASE WHEN s.statusid = 1 THEN 1 ELSE 0 END) AS  pending,
        SUM(CASE WHEN s.statusid = 2 THEN 1 ELSE 0 END) AS  Approved,
        SUM(CASE WHEN s.statusid = 3 THEN 1 ELSE 0 END) AS  Rejected
        FROM recy_order orr
        JOIN status_of_recy_order s ON orr.statusid = s.statusid
    ");
    $select_status_counts->execute();
    $status_counts = $select_status_counts->fetch(PDO::FETCH_ASSOC);



    // fetch all workers for assignment
    $select_workers = $pdo->prepare("SELECT * FROM users WHERE role = 2 AND statusid = 1");
    $select_workers->execute();
    $workers = $select_workers->fetchAll(PDO::FETCH_ASSOC);



// alerts 

if(isset($_SESSION["alert"])){
  showAlert($_SESSION["alert"]["type"] , $_SESSION["alert"]["msg"]);
  unset($_SESSION['alert']);
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبات إعادة التدوير | لوحة المدير</title>
    <link rel="stylesheet" href="style/recy.css">
    <link rel="stylesheet" href="style/sidebar.css">
</head>
<body>

    <div class="dashboard-container">
    <?php include("inc/sidebar.php");?>

        <main class="main-content">
            <header class="page-header">
                <h2>طلبات إعادة التدوير</h2>
                <p>راجع وأدرِ طلبات إعادة التدوير المقدمة من المستخدمين</p>
            </header>

            <div class="status-summary">
                <div class="status-card pending">قيد الانتظار <span><?php  echo htmlspecialchars($status_counts["pending"]) ?> </span> </div>
                <div class="status-card approved">تمت الموافقة <span><?php  echo htmlspecialchars($status_counts["Approved"]) ?></span> </div>
                <div class="status-card rejected">مرفوضة <span><?php  echo htmlspecialchars($status_counts["Rejected"]) ?></span></div>
            </div>

            <div class="tabs">
                <form action="" method="POST">
                    <button class="tab-button <?php echo (!isset($_POST['tab']) || $_POST['tab'] === 'pending') ? 'active' : ''; ?>" name="tab" value="pending">قيد الانتظار</button>
                    <button class="tab-button <?php echo (isset($_POST['tab']) && $_POST['tab'] === 'approved') ? 'active' : ''; ?>" name="tab" value="approved">الموافق عليها</button>
                    <button class="tab-button <?php echo (isset($_POST['tab']) && $_POST['tab'] === 'rejected') ? 'active' : ''; ?>" name="tab" value="rejected">المرفوضة</button> 
                </form>
            </div>

            <section class="requests-grid" id="pending-requests">
                <?php foreach($requests as $request){ 

                    $recyid = $request["recyid"];

                    $select_or_info = $pdo->prepare("
                        SELECT 
                            oi.*
                        FROM order_info oi
                        WHERE recyid = ?
                    ");
                    $select_or_info->execute([$recyid]);
                    $order_infos = $select_or_info->fetchAll(PDO::FETCH_ASSOC);
                    
                    ?>
                
                <div class="request-card" data-request-id="1"> 
                    <div class="card-details">
                        <h4><?php echo htmlspecialchars($request["cust_name"]) ; ?></h4>
                        <p class="email"><?php echo htmlspecialchars($request["email"]) ; ?></p>
                        <p class="time-ago"><?php echo date("Y-m-d h:i A", strtotime($request["date"])); ?></p>
                        <?php 
                            // نجمع كل الصور في array
                            $imgArr = [];
                            foreach($order_infos as $info){
                                if(!empty($info["img"])) {
                                    $imgArr[] = "../user/uploads/" . htmlspecialchars($info["img"]);
                                }
                            }
                            ?>

                            <!-- عرض الصور المصغرة -->
                            <?php foreach($imgArr as $idx => $imgPath): ?>
                                <img 
                                    src="<?php echo $imgPath; ?>" 
                                    style="width:200px; max-height:100px; cursor:pointer; margin:5px;" 
                                    onclick='openPopupMulti(<?php echo json_encode($imgArr); ?>, <?php echo $idx; ?>)' 
                                    alt="صورة الطلب"
                                >
                            <?php endforeach; ?>

                    </div>

                    <?php if($request["statusid"] == 1){ ?>
                    <div class="card-actions">
                            <button class="btn btn-approve-assign" data-recyid="<?php echo htmlspecialchars($request["recyid"]) ?>">تحصيل</button>
                            <form action="inc/reject_order.php" method="POST">
                                <input type="hidden" name="recyid" value="<?php echo htmlspecialchars($request["recyid"]) ?>">
                                <button class="btn btn-reject">رفض</button>
                            </form>
                    </div>
                    <?php }elseif($request["statusid"] == 3){ ?>
                        <div class="rej">
                            <span>تم رفض هذا الطلب</span>
                            <p><?php  echo htmlspecialchars($request["comment_rej"]) ?></p>
                        </div>
                      
                    <?php }else{ ?>
                        <div class="rej">
                            <span>تمت الموافقة على هذا الطلب</span>
                            <p>المندوب المعين: 
                                <?php 
                                    $worker_name = "لم يتم تعيين مندوب";
                                    foreach($workers as $worker){
                                        if($worker["userid"] == $request["workerid"]){
                                            $worker_name = $worker["user_name"];
                                            break;
                                        }
                                    }
                                    echo htmlspecialchars($worker_name);
                                ?>
                            </p>
                        </div>
                       <?php } ?>
                </div>
                
                <?php } ?>
            </section>
        </main>
    </div>

    <div id="assignModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>تعيين المندوب</h3>
                <span class="close-button">&times;</span>
            </div>
            <form action="inc/approve_req.php" method="POST">
                <p>اختر المندوب الذي سيتولى استلام هذا الطلب.</p>
                <div class="form-group">
                    
                        <label for="salesman-select">المندوبون المتاحون</label>
                        <select id="salesman-select" name="workerid" >
                            <option value="" disabled selected>اختر المندوب</option>
                            <?php foreach($workers as $worker){ ?>
                                <option value="<?php echo htmlspecialchars($worker["userid"]); ?>">
                                    <?php echo htmlspecialchars($worker["user_name"]); ?>
                                </option>   
                            <?php } ?>
                        </select>
                        <input type="hidden" name="recyid" id="recyid">
                        <label >عدد النقاط</label>
                        <input type="number" name="points" id="points">
                    
                </div>
                <div class="modal-actions">
                    <button class="btn btn-cancel" id="cancelAssign">إلغاء</button>
                    <button type="submit" class="btn btn-assign-approve-modal">تحصيل </button>
                </div>
            </form>
        </div>
    </div>
<!-- Popup -->
<div id="imagePopup" style="
    display:none; 
    position:fixed; 
    top:0; left:0; 
    width:100%; height:100%; 
    background:rgba(0,0,0,0.8); 
    justify-content:center; 
    align-items:center;
    z-index:9999;
">
    <button onclick="prevImage()" style="position:absolute; left:10px; font-size:30px; color:white; background:none; border:none; cursor:pointer;">&#10094;</button>
    <img id="popupImage" src="" alt="Full Size Image" style="max-width:95%; max-height:80%;">
    <button onclick="nextImage()" style="position:absolute; right:10px; font-size:30px; color:white; background:none; border:none; cursor:pointer;">&#10095;</button>
    <span onclick="closePopup()" style="
        position:absolute; top:10px; right:15px; 
        font-size:24px; width:30px; height:30px; 
        line-height:30px; color:white; cursor:pointer;"
    >×</span>
</div>
      <script src="../fun/resetalert.js"></script>
      <script src="style/sidebar.js"></script>
      <script src="style/recy.js"></script>
<script>
let images = [];
let currentIndex = 0;

function openPopupMulti(imgs, idx) {
    images = imgs;
    currentIndex = idx;
    document.getElementById("popupImage").src = images[currentIndex];
    document.getElementById("imagePopup").style.display = "flex";
}

function nextImage() {
    if(images.length === 0) return;
    currentIndex = (currentIndex + 1) % images.length;
    document.getElementById("popupImage").src = images[currentIndex];
}

function prevImage() {
    if(images.length === 0) return;
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    document.getElementById("popupImage").src = images[currentIndex];
}

function closePopup() {
    document.getElementById("imagePopup").style.display = "none";
}
</script>

</body> 
</html>

