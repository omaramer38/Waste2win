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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $search = trim($_POST['search'] ?? '');
    } else {
        $search = '';
    }

    $sql = "
        SELECT
            u.*,
            c.cityname,
            s.status
        FROM users u
        LEFT JOIN citys c ON u.city_work = c.cityid
        LEFT JOIN status_of_worker s ON u.statusid = s.statusid
        WHERE role = 2
    ";

    if (!empty($search)) {
        $sql .= " AND (u.user_name LIKE :search OR u.phone LIKE :search)";
    }

    $select_workers = $pdo->prepare($sql);

    if (!empty($search)) {
        $select_workers->bindValue(':search', "%$search%");
    }

    $select_workers->execute();
    $workers = $select_workers->fetchAll(PDO::FETCH_ASSOC);

    $select_counts = $pdo->prepare("
    SELECT
        COUNT(*) AS total,
        SUM(CASE WHEN s.status = 'نشط' THEN 1 ELSE 0 END) AS active,
        SUM(CASE WHEN s.status IN ('اجازه', 'تحت التجربة', 'محظور') THEN 1 ELSE 0 END) AS inactive
    FROM users u
    LEFT JOIN status_of_worker s ON u.statusid = s.statusid
    WHERE u.role = 2
    ");
    $select_counts->execute();
    $counts = $select_counts->fetch(PDO::FETCH_ASSOC);

    $total = $counts['total'];
    $active = $counts['active'];
    $inactive = $counts['inactive'];

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
  <title>إدارة مندوبي المبيعات | إعادة التدوير وكسب النقاط</title>
  <link rel="stylesheet" href="style/worker.css">
  <link rel="stylesheet" href="style/sidebar.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <style>
    .modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .modal-content {
      background: #fff;
      padding: 25px 30px;
      border-radius: 10px;
      width: 500px;
      max-width: 90%;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    .close {
      position: absolute;
      top: 75px;
      left: 530px;
      font-size: 25px;
      cursor: pointer;
    }
    .edit-form, label
    .add-form, label {
      display: block;
      margin-top: 15px;
      font-weight: 600;
    }
    .edit-form input,
    .edit-form select,
    .add-form input,
    .add-form select {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }
    .save-btn {
      margin-top: 20px;
      padding: 10px 15px;
      background-color: #28a745;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .save-btn:hover {
      background-color: #218838;
    }

  </style>
  <div class="dashboard">

    <?php include("inc/sidebar.php");?>

    <!-- المحتوى الرئيسي -->
    <main class="main-content">
      <header>
        <h1>إدارة مندوبي المبيعات</h1>
        <p>إدارة مندوبي المبيعات الميدانيين والمناطق المخصصة لهم</p>
      </header>

      <div class="search-bar">
        <form action="" method="POST">
          <input type="text" name="search" id="search" placeholder="ابحث بالاسم أو الرقم...">
          <button class="btn search">بحث</button>
        </form>
        
        <div class="filters">
          <div class="btninfo">
              <button class="btn total">الإجمالي: <?php echo $total; ?></button>
              <button class="btn active">النشطون: <?php echo $active; ?></button>
              <button class="btn inactive">غير نشط: <?php echo $inactive; ?></button>
          </div>
        </div>
        <button class="add-btn">➕ إضافة مندوب جديد</button>
      </div>

      <table class="salesmen-table">
        <thead>
          <tr>
            <th style="direction: rtl; text-align: right;">رقم المندوب</th>
            <th>الاسم</th>
            <th>المنطقة المخصصة</th>
            <th> رقم الهاتف</th>
            <th> البريد الإلكتروني</th>
            <th> الراتب</th>
            <th>الحالة</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody id="salesmenBody">
          <?php foreach($workers as $worker){ ?>
          <tr>
            <td><?php  echo htmlspecialchars($worker["userid"])  ?></td>
            <td><div class="user-info"><?php  echo htmlspecialchars($worker["user_name"])  ?></div></td>
            <td><?php if(isset($worker["cityname"]) && !empty($worker["cityname"])){echo htmlspecialchars($worker["cityname"]);} ?></td>
            <td><?php echo htmlspecialchars($worker["phone"]); ?></td>
            <td><?php echo htmlspecialchars($worker["email"]); ?></td>
            <td><?php echo htmlspecialchars($worker["salary"]) . " جنيه" ; ?></td>
            <td><span class="status <?php if(isset($worker["status"]) && $worker["status"] == "اجازه"){echo "holiday"; }elseif(isset($worker["status"]) && $worker["status"] == 3){echo "محظور";}else{echo "active";} ?>"><?php if(isset($worker["status"]) && !empty($worker["status"])){echo htmlspecialchars($worker["status"]);} ?></span></td>
            <td class="actions">
              <!-- <button class="view">عرض الزيارات</button> -->
              <button 
                  class="edit"
                  data-id="<?= htmlspecialchars($worker['userid']) ?>"
                  data-name="<?= htmlspecialchars($worker['user_name']) ?>"
                  data-cityid="<?= htmlspecialchars($worker['city_work'] ?? '') ?>"
                  data-phone="<?= htmlspecialchars($worker['phone']) ?>"
                  data-email="<?= htmlspecialchars($worker['email']) ?>"
                  data-salary="<?= htmlspecialchars($worker['salary']) ?>"
                  data-status="<?= htmlspecialchars($worker['status']) ?>"
                  data-statusid="<?= htmlspecialchars($worker['statusid']) ?>"
                >
                تعديل
              </button>
              <!-- <button class="delete">حذف</button> -->
            </td>
          </tr>
          <?php } ?>
          
        </tbody>
      </table>
    </main>

    <div id="editWorkerModal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2>تعديل بيانات العامل</h2>
        <form action="inc/update_workers.php" method="POST" class="edit-form">
          <input type="hidden" name="userid" id="worker_id" required>

          <label>الاسم</label>
          <input type="text" name="user_name" id="worker_name">

          <label>المدينة</label>
          <select name="cityid" id="worker_cityid">
            <?php
            $select_city = $pdo->prepare("SELECT * FROM citys");
            $select_city->execute();
            $cities = $select_city->fetchAll();
            foreach($cities as $city){
              ?>
              <option value="<?php echo htmlspecialchars($city['cityid']); ?>"><?php echo htmlspecialchars($city['cityname']); ?></option>
              <?php
            }
            ?>
          </select>

          <label>الهاتف</label>
          <input type="text" name="phone" id="worker_phone">

          <label>البريد الإلكتروني</label>
          <input type="email" name="email" id="worker_email">

          <label>الراتب</label>
          <input type="number" name="salary" id="worker_salary">

          <label>الحالة</label>
          <select name="status" id="worker_status">
            <?php
            $select_status = $pdo->prepare("SELECT * FROM status_of_worker");
            $select_status->execute();
            $statuses = $select_status->fetchAll();
            foreach($statuses as $status){
              ?>
              <option value="<?php echo htmlspecialchars($status['statusid']); ?>"><?php echo htmlspecialchars($status['status']); ?></option>
              <?php
            }
            ?>
          </select>

          <button type="submit" class="save-btn">حفظ التعديلات</button>
        </form>
      </div>
    </div>

    <div id="addWorkerModal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2>إضافة مندوب جديد</h2>
        <form action="inc/add_worker.php" method="POST" class="add-form">
          
          <label>الاسم</label>
          <input type="text" name="user_name" id="new_worker_name" placeholder="ادخل اسم العامل" required>

          <label>المدينة</label>
          <select name="city_work" id="new_worker_city" required>
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
          <input type="text" name="phone" id="new_worker_phone" placeholder="رقم الهاتف" required>
          <label>البريد الإلكتروني</label>
          <input type="email" name="email" id="new_worker_email" placeholder="البريد الإلكتروني" required>

          <label>password</label>
          <input type="password" name="password" id="new_worker_password" placeholder="كلمة المرور" required>

          <label>الراتب</label>
          <input type="number" name="salary" id="new_worker_salary" placeholder="الراتب" required>

          <label>الحالة</label>
          <select name="status" id="new_worker_status" required>
            <?php
            $select_status = $pdo->prepare("SELECT * FROM status_of_worker");
            $select_status->execute();
            $statuses = $select_status->fetchAll();
            foreach($statuses as $status){
             ?>
              <option value="<?php echo htmlspecialchars($status['statusid']); ?>"><?php echo htmlspecialchars($status['status']); ?></option>
              <?php
            }
            ?>
          </select>

          <button type="submit" class="save-btn">إضافة العامل</button>
        </form>
      </div>
    </div>


  </div>

  
  <script src="style/sidebar.js"></script>
  <script>
  document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("editWorkerModal");
    const worker_id = document.getElementById("worker_id");
    const worker_name = document.getElementById("worker_name");
    const worker_cityid = document.getElementById("worker_cityid");
    const worker_phone = document.getElementById("worker_phone");
    const worker_email = document.getElementById("worker_email");
    const worker_salary = document.getElementById("worker_salary");
    const worker_status = document.getElementById("worker_status");
    const closeBtn = document.querySelector("#editWorkerModal .close");

    document.querySelectorAll(".edit").forEach(btn => {
      btn.addEventListener("click", function() {
        worker_id.value = this.dataset.id;
        worker_name.value = this.dataset.name;
        worker_cityid.value = this.dataset.cityid;
        worker_phone.value = this.dataset.phone;
        worker_email.value = this.dataset.email;
        worker_salary.value = this.dataset.salary;
        worker_status.value = this.dataset.statusid;

        modal.style.display = "flex";
      });
    });

    closeBtn.addEventListener("click", () => modal.style.display = "none");
    window.addEventListener("click", e => {
      if(e.target === modal) modal.style.display = "none";
    });
  });
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("addWorkerModal");
  const addBtn = document.querySelector(".add-btn");
  const closeBtn = modal.querySelector(".close");

  addBtn.addEventListener("click", () => {
    // فتح المودال
    modal.style.display = "flex";

    // تفريغ الحقول
    document.getElementById("new_worker_name").value = "";
    document.getElementById("new_worker_city").selectedIndex = 0;
    document.getElementById("new_worker_phone").value = "";
    document.getElementById("new_worker_salary").value = "";
    document.getElementById("new_worker_status").selectedIndex = 0;
  });

  closeBtn.addEventListener("click", () => modal.style.display = "none");
  window.addEventListener("click", e => {
    if(e.target === modal) modal.style.display = "none";
  });
});
</script>


</body>
</html>

