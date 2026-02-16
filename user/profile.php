<?php
include("inc/fetch_orders_limit5.php");

// get user info
$stmtUser = $pdo->prepare("SELECT * FROM customers WHERE custid = ?");
$stmtUser->execute([$custid]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

$name = trim($_SESSION["cust_name"]);
$cust_name = $_SESSION["cust_name"];

$words = preg_split('/\s+/u', $name);
$initials = '';

foreach ($words as $word) {
    if ($word !== '') {
        $initials .= mb_substr($word, 0, 1, 'UTF-8');
        if (mb_strlen($initials, 'UTF-8') >= 2) break;
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>الملف الشخصي</title>

<link rel="stylesheet" href="style/navbar.css">

<style>

/* ===== General ===== */
*{
  box-sizing:border-box;
}

body{
  margin:0;
  font-family:Arial, sans-serif;
  background:#f9f7f3;
  color:#333;
}

/* ===== Layout ===== */
.container{
  width:100%;
  padding:40px;
  display:grid;
  grid-template-columns:1fr 2fr;
  gap:40px;
}

/* ===== Card ===== */
.card{
  background:#fff;
  border-radius:16px;
  padding:30px;
  box-shadow:0 5px 15px rgba(0,0,0,0.05);
  margin-bottom:30px;
}

/* ===== Profile ===== */
.profile-header{
  display:flex;
  align-items:center;
  gap:20px;
}

.avatar{
  width:70px;
  height:70px;
  background:#9fb878;
  color:#fff;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:50%;
  font-size:20px;
  font-weight:bold;
}

.profile-header h3{
  margin:0;
}

.profile-header p{
  margin:5px 0 0;
  font-size:14px;
  color:#777;
}

/* ===== Progress ===== */
.progress-bar{
  width:100%;
  height:10px;
  background:#eee;
  border-radius:10px;
  margin-top:20px;
  overflow:hidden;
}

.progress{
  height:10px;
  background:#9fb878;
  width:0%;
}

.progress-text{
  margin-top:8px;
  font-size:14px;
  color:#666;
}

/* ===== Orders ===== */
.orders-card h4{
  margin-bottom:20px;
}

.order-item{
  padding:15px 0;
  border-bottom:1px solid #eee;
}

.order-item:last-child{
  border-bottom:none;
}

.order-header{
  display:flex;
  justify-content:space-between;
  flex-wrap:wrap;
  font-size:14px;
  margin-bottom:10px;
}

.order-details p{
  margin:6px 0;
  font-size:14px;
}

.rejection-comment{
  color:#c0392b;
  font-weight:600;
}

.no-orders{
  color:#777;
  font-size:14px;
}

/* ===== Logout Button ===== */
.logout{
  display:block;
  width:100%;
  margin-top:25px;
  padding:12px;
  border:none;
  border-radius:12px;
  background:#9fb878;
  color:#fff;
  font-size:15px;
  cursor:pointer;
  
}

.logout:hover{
  opacity:0.9;
}

/* ===== Responsive ===== */
@media (max-width:900px){
  .container{
    grid-template-columns:1fr;
  }
}

</style>
</head>

<body>

<?php include("inc/navbar.php"); ?>

<main class="container">

  <!-- Left -->
  <div>
    <div class="card">

      <div class="profile-header">
        <div class="avatar"><?php echo htmlspecialchars($initials); ?></div>
        <div>
          <h3><?php echo htmlspecialchars($cust_name); ?></h3>
          <p>عضو منذ: <?php echo htmlspecialchars($user['registration_date']); ?></p>
        </div>
      </div>

      <div class="progress-bar">
        <div class="progress" id="progress"></div>
      </div>

      <p class="progress-text">
        <?php echo htmlspecialchars($user['points']); ?> نقطة
      </p>

      <a href="../fun/logout.php" style="text-decoration:none;">
        <button class="logout">تسجيل خروج</button>
      </a>

    </div>
  </div>

  <!-- Right -->
  <div>
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
                الكمية: <?= htmlspecialchars($order['amount']); ?> ،
                النقاط: <?= htmlspecialchars($order['points']); ?>
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

<script src="../fun/resetalert.js"></script>
</body>
</html>
