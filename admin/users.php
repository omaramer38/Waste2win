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
    
    $select_role = $pdo->prepare("SELECT role FROM users WHERE userid = ?");
    $select_role->execute([$userid]);
    $user_role = $select_role->fetch();

    if($user_role["role"] != 1){
        header("location:../index.php");
        exit;
    }
// جلب قيمة البحث إن وُجدت
$search = isset($_POST['search']) ? trim($_POST['search']) : '';

// الأساس: جملة SQL
$sql = "
SELECT
    c.*,
    s.status,
    ci.cityname,
    COUNT(DISTINCT ro.recyid) AS orders_count
FROM customers c
JOIN status_of_account s ON c.status_account = s.statusid
JOIN citys ci ON c.cityid = ci.cityid
LEFT JOIN recy_order ro ON ro.custid = c.custid
WHERE 1=1
";

// لو في قيمة بحث، نضيف شرط البحث على رقم الهاتف
if (!empty($search)) {
    $sql .= " AND c.phone LIKE :search";
}

$sql .= " GROUP BY c.custid";

// إعداد وتنفيذ الاستعلام
$select_customers = $pdo->prepare($sql);

// تمرير قيمة البحث إن وُجدت
if (!empty($search)) {
    $select_customers->bindValue(':search', "%$search%");
}

$select_customers->execute();
$customers = $select_customers->fetchAll();

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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>لوحة إدارة المستخدمين</title>
  <link rel="stylesheet" href="style/users.css">
  <link rel="stylesheet" href="style/sidebar.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  
</head>
<body>
  <style>
  /* الخلفية السوداء */

.modal {
  display: none;
  position: fixed;
  inset: 0; 
  background: rgba(0,0,0,0.6);
  z-index: 1000;

  justify-content: center; 
  align-items: center;    
}

/* محتوى الـ Modal */
.modal-content {
  background: #fff;
  width: 500px;
  max-width: 90%;
  border-radius: 10px;
  padding: 25px 30px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.3);
  position: relative;
  font-family: Arial, sans-serif;

  /* optional: animation */
  transform: translateY(0);
  transition: transform 0.3s ease;
}


  /* زر الإغلاق */
  .close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 25px;
    font-weight: bold;
    color: #333;
    cursor: pointer;
  }

  /* العناوين */
  .modal-content h2 {
    margin-bottom: 20px;
    text-align: center;
    color: #333;
  }

  /* الحقول */
  .edit-form label {
    display: block;
    margin-top: 10px;
    color: #555;
    font-weight: bold;
  }

  .edit-form input, .edit-form select {
    width: 100%;
    padding: 8px 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
  }

  /* زر الحفظ */
  .save-btn {
    margin-top: 20px;
    width: 100%;
    padding: 10px;
    background-color: #28a745;
    border: none;
    border-radius: 6px;
    color: #fff;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
  }

  .save-btn:hover {
    background-color: #218838;
  }


  </style>
  <div class="dashboard"> 

  <?php include("inc/sidebar.php");?>

    <main class="main-content">
      <header>
        <h1>إدارة المستخدمين</h1>
        <div class="actions">
          <form action="" method="POST">
            <input type="text" id="search" name="search" placeholder="ابحث عن مستخدم برقم الهاتف...">
            <button type="submit">بحث</button>
          </form>
          
        </div>
      </header>

      <table class="users-table">
        <thead>
          <tr>
           <th>الاسم</th> <th>البريد الإلكتروني</th><th>النقاط</th><th>الحالة</th>
            <th>تاريخ التسجيل</th><th>الطلبات</th><th>المنطقه</th>
            <th>الهاتف</th><th>الإجراءات</th>
          </tr>
        </thead>
        <tbody id="usersBody">

        <?php
        foreach($customers as $customer){
          ?>
          <tr>
            <td class="user-info"><div class="user-icon"><?php echo htmlspecialchars($customer["custid"]) ?></div><span> <?php echo htmlspecialchars($customer["cust_name"]) ?></span></td>
            <td><?php echo htmlspecialchars($customer["email"]) ?></td>
            <td><?php echo htmlspecialchars($customer["points"]) ?></td>
            <td><span class="status <?php if($customer["status_account"] == 1){echo "banned";}else{echo "active";} ?>"><?php echo htmlspecialchars($customer["status"]) ?></span></td>
            <td><?php echo htmlspecialchars($customer["registration_date"]) ?></td>
            <td><?php echo htmlspecialchars($customer["orders_count"]) ?></td>
            <td><?php echo htmlspecialchars($customer["cityname"]) ?></td>
            <td><?php  echo htmlspecialchars($customer["phone"]) ?></td>
            <td class="actions-buttons">
            <button 
                class="edit"
                data-id="<?= htmlspecialchars($customer['custid']) ?>"
                data-name="<?= htmlspecialchars($customer['cust_name']) ?>"
                data-email="<?= htmlspecialchars($customer['email']) ?>"
                data-points="<?= htmlspecialchars($customer['points']) ?>"
                data-status="<?= htmlspecialchars($customer['status_account']) ?>"
                data-date="<?= htmlspecialchars($customer['registration_date']) ?>"
                data-orders="<?= htmlspecialchars($customer['orders_count']) ?>"
                data-cityid="<?= htmlspecialchars($customer['cityid']) ?>"
                data-phone="<?= htmlspecialchars($customer['phone']) ?>"
            >
            تعديل
            </button>

            </td>
          </tr>

          <?php
        }
        ?>
        </tbody>
      </table>
    </main>


    <!-- Modal for editing user details -->

<!-- Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>تعديل بيانات العميل</h2>
    <form action="inc/update_users.php" method="POST" class="edit-form">
      <input type="hidden" name="custid" id="custid" required>

      <label>الاسم</label>
      <input type="text" name="cust_name" id="cust_name">

      <label>الإيميل</label>
      <input type="email" name="email" id="email">

      <label>النقاط</label>
      <input type="number" name="points" id="points">

      <label>الحالة</label>
      <select name="status_account" id="status_account">
        <?php
        $select_status = $pdo->prepare("SELECT * FROM status_of_account");
        $select_status->execute();
        $statuses = $select_status->fetchAll();
        foreach($statuses as $status){
          echo '<option value="'.htmlspecialchars($status["statusid"]).'">'.htmlspecialchars($status["status"]).'</option>';
        }
        ?>
      </select>

      <label>تاريخ التسجيل</label>
      <input type="date" name="registration_date" id="registration_date">

      <label>عدد الطلبات</label>
      <input type="number" name="orders_count" id="orders_count">

      <label>المدينة</label>
      <select name="cityid" id="cityid">
        <?php
        $select_city = $pdo->prepare("SELECT * FROM citys");
        $select_city->execute();
        $cities = $select_city->fetchAll();
        foreach($cities as $city){
          echo '<option value="'.htmlspecialchars($city["cityid"]).'">'.htmlspecialchars($city["cityname"]).'</option>';
        }
        ?>
      </select>

      <label>الهاتف</label>
      <input type="text" name="phone" id="phone">

      <button type="submit" class="save-btn">حفظ التعديلات</button>
    </form>
  </div>
</div>



  </div>

  <script src="style/users.js"></script>
  <script src="style/sidebar.js"></script>

  <!-- script edit popup -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("editModal");
  const custid = document.getElementById("custid");
  const cust_name = document.getElementById("cust_name");
  const email = document.getElementById("email");
  const points = document.getElementById("points");
  const status_account = document.getElementById("status_account");
  const registration_date = document.getElementById("registration_date");
  const orders_count = document.getElementById("orders_count");
  const cityid = document.getElementById("cityid");
  const phone = document.getElementById("phone");
  const closeBtn = document.querySelector(".close");

  document.querySelectorAll(".edit").forEach(btn => {
    btn.addEventListener("click", function() {
      custid.value = this.dataset.id;
      cust_name.value = this.dataset.name;
      email.value = this.dataset.email;
      points.value = this.dataset.points;
      status_account.value = this.dataset.status;
      registration_date.value = this.dataset.date;
      orders_count.value = this.dataset.orders;
      cityid.value = this.dataset.cityid;
      phone.value = this.dataset.phone;

      modal.style.display = "flex";
    });
  });

  closeBtn.addEventListener("click", () => modal.style.display = "none");
  window.addEventListener("click", e => {
    if (e.target === modal) modal.style.display = "none";
  });
});
</script>

</body>
</html>