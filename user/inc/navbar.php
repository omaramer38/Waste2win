<?php

$page_name = basename($_SERVER['PHP_SELF']);



?>

<header class="navbar">
    <div class="logo"> Waste2Win</div>
    <nav>
      <a href="home.php" <?php if($page_name == "home.php"){echo "class='active'"; } ?> >الصفحه الرئيسيه</a>
      <a href="Recycle.php" <?php if($page_name == "Recycle.php"){echo "class='active'"; } ?> >تدوير</a>
      <a href="shop.php" <?php if($page_name == "shop.php"){echo "class='active'"; } ?>>المتجر</a>
      <a href="cart.php" <?php if($page_name == "cart.php" || $page_name == "checkout.php"){echo "class='active'"; } ?>>السله</a>
      <a href="profile.php" <?php if($page_name == "profile.php"){echo "class='active'"; } ?>>الصفحه الشخصيه</a>
    </nav>
  </header>