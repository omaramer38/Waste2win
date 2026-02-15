<?php

session_start();
require("../conn.php");
include("../fun/alert.php");
if(!isset($_SESSION["custid"])){
    header("location:../index.php");
    exit;
}else{
    $custid = $_SESSION["custid"];
    $cust_name = $_SESSION["cust_name"];



    // get user info
    $stmtUser = $pdo->prepare("SELECT * FROM customers WHERE custid = ?");
    $stmtUser->execute([$custid]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);



    // user name in div

    $name = trim($cust_name);

    // تقسيم الاسم على المسافات (يدعم العربي)
    $words = preg_split('/\s+/u', $name);

    $initials = '';

    foreach ($words as $word) {
        if ($word !== '') {
            $initials .= mb_substr($word, 0, 1, 'UTF-8');

            // نوقف لما نوصل لحرفين
            if (mb_strlen($initials, 'UTF-8') >= 2) {
                break;
            }
        }
    }

    // get recy orders
    include("inc/fetch_orders_limit5.php");
    

}
?>



<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>الملف الشخصي</title>
  <link rel="stylesheet" href="style/profile.css"/>
  <link rel="stylesheet" href="style/navbar.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>


</head>
<body>
 <style>
  .order-header span {
    margin-right: 45px;
  }
 </style>
<!-- navbar -->
<?php include("inc/navbar.php"); ?>

<main class="container">
  <div class="left-column">
    <div class="card profile-card">
      <div class="profile-header">
        <div class="avatar"><?php echo htmlspecialchars($initials); ?></div>
        <div>
          <h3> <?php echo htmlspecialchars($cust_name); ?></h3>
          <p> عضو منذ : <?php echo htmlspecialchars($user['registration_date']); ?></p>
        </div>
      </div>
      <div class="progress-bar">
        <div class="progress" id="progress"></div>
      </div>
      <p class="progress-text"><span id="points"></span> <?php echo htmlspecialchars($user['points']); ?> /  نقطة</p>
<div>
   
</label>
</div>      <a href="../inc/logout.php"><button class="logout">تسجيل خروج</button></a>
    </div>

    <div class="card stats-card">
      <h4>النقاط المتاحة</h4>
      <p class="points-display"><?php echo htmlspecialchars($user['points']); ?></p>
      <span class="small-text">جاهزة للاستبدال في المتجر</span>
    </div>

    <div class="card stats-card">
      <h4>إجمالي عمليات إعادة التدوير</h4>
      <p class="points-display"><?php 
        // حساب إجمالي عمليات إعادة التدوير
        $stmtTotalRecy = $pdo->prepare("SELECT COUNT(*) AS total_recy FROM recy_order WHERE custid = ?");
        $stmtTotalRecy->execute([$custid]);
        $totalRecy = $stmtTotalRecy->fetch(PDO::FETCH_ASSOC);
        echo htmlspecialchars($totalRecy['total_recy']);
      ?></p>
      <span class="small-text">عدد طلبات إعادة التدوير التي قمت بها</span>
  </div>
  </div>

  <div class="right-column">
    <div class="card orders-card">
      <h4>طلبات إعادة التدوير الأخيرة</h4>
      <?php if (count($groupedOrders) > 0): ?>
        <?php foreach ($groupedOrders as $order): ?>
          <div class="order-item">
            <div class="order-header">
              <span>رقم الطلب: <?= htmlspecialchars($order['recyid']); ?></span>
              <span>التاريخ: <?= htmlspecialchars($order['date']); ?></span>
              <span>الحالة: <?= htmlspecialchars($order['status']); ?></span>
            </div>

            <div class="order-details">
              <p>
                النفايات:
                <?= !empty($order['waste_names'])
                    ? htmlspecialchars($order['waste_names'])
                    : 'لا توجد نفايات'; ?>
              </p>

              <p>
                المنتجات:
                <?= !empty($order['products'])
                    ? htmlspecialchars($order['products'])
                    : 'لا توجد منتجات'; ?>
              </p>

              <p>
                الإجمالي:
                <?= htmlspecialchars($order['amount']); ?> الكميه ،
                <?= htmlspecialchars($order['points']); ?> نقطة
              </p>

              <?php if (!empty($order['comment_reject'])): ?>
                <p class="rejection-comment">
                  تعليق الرفض: <?= htmlspecialchars($order['comment_reject']); ?>
                </p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="no-orders">لا توجد طلبات لإعادة التدوير حتى الآن.</p>
      <?php endif; ?>
    </div>





  </div>
</main>

<script src="style/profile.js"></script>
 <script src="../fun/resetalert.js"></script>
</body>
</html>
