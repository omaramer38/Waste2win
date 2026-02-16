<?php
include("inc/check_role.php"); // تأكد من أن المستخدم مسجل الدخول وله الدور المناسب
include("../fun/alert.php"); 

// select waste categories
$get_categories = $pdo->prepare("SELECT * FROM wastes ORDER BY name ASC");
$get_categories->execute();
$categories = $get_categories->fetchAll();

if(isset($_SESSION["alert"])){
  showAlert($_SESSION["alert"]["type"], $_SESSION["alert"]["msg"]);
  unset($_SESSION['alert']);
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>طلب إعادة التدوير</title>
  <link rel="stylesheet" href="style/recycle.css">
  <link rel="stylesheet" href="style/navbar.css">
</head>

<body>
<?php include("inc/navbar.php"); ?>
<div class="container">
  <form method="POST" action="inc/add_recycle.php" enctype="multipart/form-data" class="form-card">

    <?php
      $options_html = '';
      foreach($categories as $category){
          $options_html .= '<option value="'.$category['wasteid'].'">'.htmlspecialchars($category['name']).'</option>';
      }
      ?>


    <div id="types-container" data-options='<?php echo json_encode($options_html); ?>'>

      <!-- First Type -->
      <div class="type-block">

        <div class="form-row">
          <label>نوع النفايات *</label>
          <select name="wasteid[]" required>
            <option value="">اختر النوع</option>
            <?php foreach($categories as $category){ ?>
              <option value="<?php echo $category['wasteid']; ?>">
                <?php echo $category['name']; ?>
              </option>
            <?php } ?>
          </select>
        </div>



        <div class="form-row">
          <label>رفع صورة *</label>
          <input type="file" name="waste_image[]" accept="image/*" required>
        </div>

      </div>

    </div>

    <button type="button" class="add-type-btn" id="add-type">
      + إضافة نوع آخر
    </button>

    <button type="submit" class="submit-btn">
      إرسال الطلب
    </button>

  </form>
</div>

<script src="../fun/resetalert.js"></script>
 <script src="style/recycle.js"></script>

</body>
</html>
