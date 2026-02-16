<?php

include("inc/check_role.php"); // تأكد من أن المستخدم مسجل الدخول وله الدور المناسب
include("../fun/alert.php"); 
// التحقق من وجود بيانات السلة
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$userPoints = $_SESSION['user_points'];
$cartItems = $_SESSION['cart'];

// حساب إجمالي النقاط
$totalPoints = 0;
foreach ($cartItems as $item) {
    $totalPoints += $item['points_needed'];
}



  // select cities from database
    $get_cities = $pdo->prepare("SELECT * FROM citys WHERE 1=1");
    $get_cities->execute();
    $cities = $get_cities->fetchAll();  


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
  <title>إتمام طلب RecyPoints</title>
    <link rel="stylesheet" href="style/checkout.css">
    <link rel="stylesheet" href="style/navbar.css">
</head>

<body>

<style>
  form{
    display: contents;
  }
</style>
<!-- شريط التنقل -->
<?php include("inc/navbar.php"); ?>


  
<div class="container">
  <form method="post" action="inc/process_order.php">
    <div class="form-container">
      <h2>أكمل طلبك</h2>
      <p>أدخل بيانات التوصيل لتستلم منتجاتك الصديقة للبيئة</p>

      <div class="form-group">
        <label>الاسم كامل</label>
        <input type="text" name="fullname" placeholder="أدخل اسمك كامل" required>
      </div>

      <div class="form-group">
        <label>رقم الهاتف المتاح دائما</label>
        <input type="text" name="phone" placeholder="010111111111" required>
      </div>

      <div class="form-group">
        <label>البريد الإلكتروني</label>
        <input type="email" name="email" placeholder="you@example.com" required>
      </div>

      <div class="form-group">
        <label>المدينة *</label>
        <select name="city" required>
          <option value="">اختر مدينتك</option>
          <?php foreach($cities as $city){ ?>
            <option value="<?php echo $city["cityid"]; ?>"><?php echo $city["cityname"]; ?></option>   
          <?php } ?>
        </select>
      </div>

      <div class="form-group">
        <label>الشارع</label>
        <input type="text" name="street" placeholder="ادخل اسم الشارع و رقم المبنى بالتفصيل">
      </div>

      <button type="submit" class="btn btn-confirm">تأكيد الطلب</button>
      <a href="cart.php"><button type="button" class="btn btn-back">العودة إلى السلة</button></a>
    </div>

    <!-- ================= ملخص الطلب مع حقول مخفية للمنتجات =================== -->
    <div class="summary-container">
      <h3>ملخص الطلب</h3>
      <p>العميل: <strong><?php echo htmlspecialchars($cust_name); ?></strong></p>

      <h4>المنتجات في السلة:</h4>
      <ul>
        <?php foreach ($cartItems as $index => $item): 
            $proid = $item['proid'];
            $stmt = $pdo->prepare("SELECT p.title, i.img AS img FROM products p JOIN pro_imgs i ON p.proid = i.proid WHERE p.proid = ?");
            $stmt->execute([$proid]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            $points = $item['points_needed'];
        ?>
        <li>
            <?php echo htmlspecialchars($product['title']); ?> × <?php echo $points; ?> نقطة
        </li>
        <!-- حقول مخفية لإرسال المنتج مع الفورم -->
        <input type="hidden" name="products[<?php echo $index; ?>][proid]" value="<?php echo $proid; ?>">
        <input type="hidden" name="products[<?php echo $index; ?>][points]" value="<?php echo $points; ?>">
        <?php endforeach; ?>
      </ul>

      <p class="total">إجمالي النقاط: <?php echo $totalPoints; ?></p>

      <div class="delivery-info">
        <strong>موعد التسليم المتوقع</strong>
        <p>سيتم التوصيل خلال 3–5 أيام عمل</p>
      </div>
    </div>
  </form>
</div>


  <script src="cart.js"></script>
</body>
</html>



