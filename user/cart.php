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
    $userPoints = $_SESSION['user_points'];
    if (isset($_SESSION['cart'])){
      $cartItems = $_SESSION['cart'];
    }




        // echo "<pre>";
        // print_r($_SESSION);
        // echo "</pre>";
}

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
  <title>سلة RecyPoints</title>
    <link rel="stylesheet" href="style/cart.css">
    <link rel="stylesheet" href="style/navbar.css">
</head>
<body>

<!-- شريط التنقل -->
<?php include("inc/navbar.php"); ?>


  
  <main class="container">
    <section class="page-title">
      <h2>سلة التسوق</h2>
      <p class="sub">راجع منتجاتك واستمر لإتمام عملية الدفع</p>
    </section>


    <?php if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){ ?>

<div class="content-grid">

  <div class="cart-left card">
    <table class="cart-table">
      <thead>
        <tr>
          <th>المنتج</th>
          <th>النقاط</th>
          <th>إجراء</th>
        </tr>
      </thead>

      <tbody>
        <?php
        $totalPoints = 0;     // إجمالي نقاط الطلب
        $itemCount = count($cartItems); // عدد المنتجات المختلفة

        foreach ($cartItems as $item) {

            $proid = $item['proid'];

            // استعلام لجلب بيانات المنتج
            $stmt = $pdo->prepare("
                SELECT
                    p.*,
                    i.img AS img
                FROM products p 
                JOIN pro_imgs i ON p.proid = i.proid
                WHERE p.proid = ?
            ");
            $stmt->execute([$proid]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            $points = $product["points"];  // نقاط المنتج

            // جمع الإجماليات
            $totalPoints += $points;
        ?>

        <tr class="product-row">
          <td class="product-info">
            <img src="../imgs/<?php echo htmlspecialchars($product["img"]) ?>" class="thumb">
            <div class="info-text">
              <div class="prod-name"><?php echo htmlspecialchars($product["title"]) ?></div>
              <div class="prod-desc"><?php echo htmlspecialchars($product["comment"]) ?></div>
            </div>
          </td>

          <td class="points-per-item"><?php echo $points ?></td>

          <td>
            <form method="post" action="inc/remove_from_cart.php">
              <input type="hidden" name="proid" value="<?php echo $proid ?>">
              <button type="submit" class="remove-btn">🗑️</button>
            </form>
          </td>
        </tr>

        <?php } ?>

      </tbody>
    </table>
  </div>

  <!-- =================  ملخص السلة  =================== -->

  <aside class="cart-right summary-card card">
    <h3>ملخص الطلب</h3>

    <div class="summary-row">
      <span>عدد المنتجات في السلة</span>
      <span id="summary-items"><?php echo $itemCount ?></span>
    </div>

    <div class="summary-row">
      <span>رصيدك</span>
      <span id="summary-balance"><?php echo $_SESSION["user_points"] ?></span>
    </div>

    <div class="summary-row big">
      <span>إجمالي النقاط</span>
      <span id="summary-total"><?php echo $totalPoints ?></span>
    </div>

    <div class="remaining-note">
      سيبقى لديك 
      <strong id="remaining-points">
        <?php echo $_SESSION["user_points"]  ?>
      </strong> نقطة
    </div>

    
    <?php if (!empty($cartItems)): ?>
        <a href="checkout.php">
            <button id="checkout-btn" class="btn primary">المتابعة للدفع</button>
        </a>
    <?php else: ?>
        <button id="checkout-btn" class="btn primary" disabled style="cursor: not-allowed;">
            السلة فارغة
        </button>
    <?php endif; ?>

     <a href="shop.php"><button class="btn ghost">مواصلة التسوق</button></a>
  </aside>

</div>
<?php } else { ?>
  <div class="empty-cart">
    <h3>سلة التسوق فارغة</h3>
    <p>يبدو أنك لم تضف أي منتجات إلى سلة التسوق بعد.</p>
    <a href="shop.php"><button class="btn primary">تسوق الآن</button></a>
  </div>
<?php } ?>

  </main>

  <script src="cart.js"></script>
</body>
</html>


