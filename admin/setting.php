<?php
require("../conn.php");
include("../fun/alert.php");
session_start();

if(!isset($_SESSION["userid"])){
    header("location:../index.php");
    exit;
}else{
    $userid = $_SESSION["userid"];
    $user_name = $_SESSION["user_name"];
    $role = $_SESSION["role"];


    // select basic site

    $selec_basic_site = $pdo->prepare("SELECT * FROM basic_site WHERE site_id = 1");
    $selec_basic_site->execute();
    $basic_site = $selec_basic_site->fetch();

    // select communictaion info
    $select_communication_info = $pdo->prepare("SELECT * FROM communication WHERE comid = 1");
    $select_communication_info->execute();
    $communication_info = $select_communication_info->fetch();


    // select waste settings
    $select_wastes = $pdo->prepare("SELECT * FROM wastes WHERE 1 = 1");
    $select_wastes->execute();
    $wastes = $select_wastes->fetchAll();


    // select links 

    $select_links = $pdo->prepare("SELECT * FROM communication_links WHERE link_id = 1");
    $select_links->execute();
    $links = $select_links->fetch();


    // alerts 

if(isset($_SESSION["alert"])){
  showAlert($_SESSION["alert"]["type"] , $_SESSION["alert"]["msg"]);
  unset($_SESSION['alert']);
}

}


?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>إعدادات المنصة</title>
  <link rel="stylesheet" href="style/settings.css">
  <link rel="stylesheet" href="style/sidebar.css">
</head>
<body>

<?php include("inc/sidebar.php");?>


<main class="content">
  <header>
    <h1>إدارة إعدادات المنصة </h1>
  </header>

  <section class="grid">
    <!-- basic site -->
    <div class="card">
      <form action="inc/update_basic_site.php" method="POST">
        <h3>الإعدادات العامة</h3>

        <label>اسم الموقع</label>
        <input name="site_name" type="text" value="<?php  echo htmlspecialchars($basic_site["site_name"]) ?>">

        <label>رابط اللوجو</label>
        <input name="logo" type="text" value="<?php  echo htmlspecialchars($basic_site["logo"]) ?>">

        <label>جملة تعريفية</label>
        <input name="title_1" type="text" value="<?php  echo htmlspecialchars($basic_site["title_1"]) ?>">

        <label>نبذة عن المنصة</label>
        <textarea name="about"><?php  echo htmlspecialchars($basic_site["about"]) ?></textarea>
          <br><br>
        <button type="submit" class="saveBtn">حفظ  التغييرات</button>
      </form>
      
    </div>
    <!-- communication info -->
    <div class="card">
      <form action="inc/update_contact_info.php" method="POST">
        <h3>معلومات التواصل</h3>

        <label>البريد الإلكتروني</label>
        <input type="text" name="email" value="<?php if(isset($communication_info['email']) && !empty($communication_info['email'])){echo htmlspecialchars($communication_info['email']);}else{echo '--';}  ?>">

        <label>رقم الهاتف</label>
        <input type="text" name="phone" value="<?php if(isset($communication_info['phone']) && !empty($communication_info['phone'])){echo htmlspecialchars($communication_info['phone']);}else{echo '--';}?>">

        <label>عنوان المكتب</label>
        <input type="text" name="location" value="<?php if(isset($communication_info['location']) && !empty($communication_info['location'])){echo htmlspecialchars($communication_info['location']);}else{echo '--';}?>">

        <label>بريد الدعم</label>
        <input type="text" name="email_support" value="<?php if(isset($communication_info['email_support']) && !empty($communication_info['email_support'])){echo htmlspecialchars($communication_info['email_support']);}else{echo '--';}?>">
        <br><br>
        <button type="submit" class="saveBtn">حفظ  التغييرات</button>
      </form>
      
    </div>

    <!-- waste -->
    <div class="card">
      <h3>إعدادات المنصة</h3>

      <form action="inc/update_waste_settings.php" method="POST">
        <?php foreach($wastes as $waste){ ?>
          <label>نقاط لكل كجم (<?php echo htmlspecialchars($waste["name"]) ?>)</label>
          <input type="number" name="<?php echo htmlspecialchars($waste["name"]) ?>" value="<?php echo htmlspecialchars($waste["points"]) ?>">
        <?php } ?>
          <br><br>
          <button type="submit" class="saveBtn">حفظ  التغييرات</button>
      </form>
      
    </div>

    <!-- links -->
    <div class="card">
      <form action="inc/update_links.php" method="POST">
        <h3>روابط التواصل الاجتماعي</h3>

      <label>فيسبوك</label>
      <input type="text" name="facebook" value="<?php if(isset($links['facebook']) && !empty($links['facebook'])){echo htmlspecialchars($links['facebook']);}else{echo '--';}  ?>">

      <label>انستجرام</label>
      <input type="text" name="instagram" value="<?php if(isset($links['insta']) && !empty($links['insta'])){echo htmlspecialchars($links['insta']);}else{echo '--';}  ?>">

      <label>تويتر</label>
      <input type="text" name="twitter" value="<?php if(isset($links['twitter']) && !empty($links['twitter'])){echo htmlspecialchars($links['twitter']);}else{echo '--';}  ?>">

      <label>لينكدإن</label>
      <input type="text" name="linkedin" value="<?php if(isset($links['linkedin']) && !empty($links['linkedin'])){echo htmlspecialchars($links['linkedin']);}else{echo '--';}  ?>">
      <br><br>
      <button type="submit" class="saveBtn">حفظ  التغييرات</button>
      </form>
    </div>

  </section>

  <div class="footer-btn">
    <button id="saveBtn">حفظ جميع التغييرات</button>
  </div>
</main>
<script src="../fun/resetalert.js"></script>
<script src="style/sidebar.js"></script>
</body>
</html>
