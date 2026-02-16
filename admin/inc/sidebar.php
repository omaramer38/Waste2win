<?php

$page_name = basename($_SERVER['PHP_SELF']);



?>


  <button id="toggle-btn">☰</button>
  <aside class="sidebar">
    <div class="logo">
      <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png" alt="الشعار">
      <div>
        <h2>إعادة التدوير والربح</h2>
        <p>لوحة المدير</p>
      </div>
    </div>
    <ul class="menu">
      <a href="users.php"><li <?php if($page_name == "users.php"){echo "class='active'"; } ?>>المستخدمون</li></a>
      <a href="worker.php"><li <?php if($page_name == "worker.php"){echo "class='active'"; } ?>>مندوبون</li></a>
      <a href="recycling.php"><li <?php if($page_name == "recycling.php"){echo "class='active'"; } ?>>طلبات إعادة التدوير</li></a>
      <a href="store.php"><li <?php if($page_name == "store.php"){echo "class='active'"; } ?>>المتجر</li></a>
      <a href="setting.php"><li <?php if($page_name == "setting.php"){echo "class='active'"; } ?>>الإعدادات</li></a>
      <a href="../fun/logout.php"><li>تسجيل خروج</li></a>
    </ul>
  </aside>