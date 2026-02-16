<?php

include("inc/check_role.php"); // تأكد من أن المستخدم مسجل الدخول وله الدور المناسب
include("../fun/alert.php"); // تضمين ملف التنبيهات


  $user_point = $_SESSION["user_points"];

  // select products based on filter
  $typeFilter = 'all';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['care'])) {
          $typeFilter = 'Personal Care';
      } elseif (isset($_POST['lifestyle'])) {
          $typeFilter = 'Lifestyle';
      } elseif (isset($_POST['garden'])) {
          $typeFilter = 'Garden';
      } elseif (isset($_POST['elec'])) {
          $typeFilter = 'Electronics';
      } else {
          $typeFilter = 'all';
      }
  }

  // get products from database
  $sql = "
  SELECT 
    p.*,
    top.type_name,
    sop.statusid,
    sop.status,
    ip.img
  FROM products p
  JOIN type_of_pro top ON p.type_of_pro = top.typeid
  JOIN status_of_pro sop ON p.status = sop.statusid
  JOIN pro_imgs ip ON p.proid = ip.proid
  WHERE 1=1
  ";

  // filter by type if not 'all'
  if ($typeFilter !== 'all') {
      $sql .= " AND top.type_name = :type_name";
  }

  $select_products = $pdo->prepare($sql);

  // filter by type if not 'all'
  if ($typeFilter !== 'all') {
      $select_products->bindParam(':type_name', $typeFilter);
  }

  $select_products->execute();
  $products = $select_products->fetchAll();



  
// alerts 

if(isset($_SESSION["alert"])){
  showAlert($_SESSION["alert"]["type"] , $_SESSION["alert"]["msg"]);
  unset($_SESSION['alert']);
}

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>متجر نقاط </title>
  <link rel="stylesheet" href="style/shop.css" />
  <link rel="stylesheet" href="style/navbar.css">
</head>
<body>
<style>
  .redeem.disabled {
  background-color: #ccc;
  cursor: not-allowed;
  color: #666;
}

</style>

<!-- شريط التنقل -->
<?php include("inc/navbar.php"); ?>

  <main class="container">
  <section class="shop-header">
  <h2>متجر المكافآت</h2>

  <div class="balance">
    <p>رصيدك الحالي</p>
    <span id="balance">
      <?php echo htmlspecialchars($_SESSION["points"]) ?> نقطة
    </span>
  </div>
</section>


    <section class="products">
      <?php foreach($products as $product){ ?>
      <div class="product-card">
        <form action="inc/redeem.php" method="POST">
          <input type="hidden" name="proid" id="" value="<?php echo $product["proid"] ?>">
          <input type="hidden" name="points_needed" id="" value="<?php echo $product["points"] ?>">
  
          <img src="../imgs/<?php echo $product["img"] ?>" alt="<?php echo $product["title"] ?>"/>
          <h3><?php echo $product["title"] ?></h3>
          <p><?php echo $product["comment"] ?></p>
          <p><?php echo $product["status"] ?></p>
          <div class="card-footer">
            <span class="points"><?php echo $product["points"] ?></span> نقطة 
            <?php if($product["statusid"] != 2){ ?>
              <button class="redeem">استبدال</button>
            <?php } else { ?>
              <button class="redeem disabled" disabled>غير متاح</button>
            <?php } ?>
          </form>      
        </div>
      </div>
      <?php } ?>
    </section>
  </main>

 <script src="../fun/resetalert.js"></script>


</body>
</html>
