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


        // select waste categories
    $get_categories = $pdo->prepare("SELECT * FROM wastes ORDER BY  name ASC");
    $get_categories->execute();
    $categories = $get_categories->fetchAll();


    // get all cities 
    $get_cities = $pdo->prepare("SELECT * FROM citys WHERE 1=1");
    $get_cities->execute();
    $cities = $get_cities->fetchAll();

    include("inc/fetch_orders.php");

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
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>RecyPoints — طلبات إعادة التدوير الخاصة بي</title>  
  <link rel="stylesheet" href="style/sub.css">
  <link rel="stylesheet" href="style/Recycle.css">
  <link rel="stylesheet" href="style/navbar.css">
</head>
<body>

<style>

</style>
<!-- شريط التنقل -->
<?php include("inc/navbar.php"); ?>


  <main class="container" >
    <section class="hero">
      <button class="big-btn">ابدأ التدوير</button>
      <h1>أعد تدوير نفاياتك</h1>
      <p class="sub">أرسل نفاياتك القابلة لإعادة التدوير وتتبع طلباتك</p>

      <div class="toggle">
        <button class="tab active">إرسال إعادة التدوير</button>
        <button class="tab">طلباتي</button>
      </div>
    </section>

    <section class="orders" style="display: none;">
      <h2>طلبات إعادة التدوير الخاصة بي</h2>
      <p class="hint">تابع حالة طلباتك لإعادة التدوير</p>

      <div class="table-wrap">
        <table class="orders-table">
          <thead>
            <tr>
              <th>رقم الطلب</th>
              <th>التاريخ</th>
              <th>نوع النفايات</th>
              <th>الوزن (كجم)</th>
              <th>الحالة</th>
              <th>تعليق الرفض</th>
              <th>المنتج</th>
            </tr>
            
          </thead>

          <tbody id="orders-body">
          <?php if(empty($groupedOrders)){ ?>
          <tr>
            <td colspan="6">لا توجد طلبات لإعادة التدوير حتى الآن.</td>
          </tr>
          <?php } else { ?>
          <?php foreach($groupedOrders as $order){ ?>
          <tr class="order-row" data-status="<?php echo strtolower($order['status']); ?>">
            <td>#<?php echo $order['recyid']; ?></td>
            <td><?php echo date("d-m-Y", strtotime($order['date'])); ?></td>
            <td><?php echo $order['waste_names']; ?></td>
            <td><?php echo $order['amount']; ?></td>
            <td><?php echo $order['status']; ?></td>
            <td><?php echo htmlspecialchars($order['comment_reject']); ?></td>
            <td><?php echo htmlspecialchars($order['products']); ?></td>
          </tr>
          <?php } } ?>
          </tbody>


        </table>
      </div>
    </section>

    <section class="content" >
      <!-- Left: Form Card -->
      <div class="left-col">
        <form id="recycle-form" class="card form-card" method="POST" action="inc/add_recycle.php">
          <div class="form-row">
            <label>أدخل الوزن (كجم) *</label>
            <input id="weight" type="number" name="amount" min="0" step="0.1" value="0" required>
            <input type="hidden" name="total_points" id="total_points">
          </div>

          <div class="form-row">
            <label>نوع النفايات *</label>
            <div class="select-wrap">
              <select id="waste-type" name="wasteid">
                <?php
                foreach($categories as $category){  
                  ?>
                  <option value="<?php echo $category['wasteid']; ?>"><?php echo $category['name']; ?></option>
                  <?php
                } 
                ?>
              </select>
            </div>
          </div>

          <div class="two-cols">
            <div class="form-row">
              <label>اختر المدينة *</label>
              <div class="select-wrap">
                <select id="city" name="city">
                  <?php
                  foreach($cities as $city){
                    ?>
                    <option value="<?php echo $city['cityid']; ?>"><?php echo $city['cityname']; ?></option>
                    <?php
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>

          <div class="selected-summary">
            <div class="summary-icon"></div>
            <div class="summary-text">
              <div class="summary-title" id="summary-title"></div>
              <div class="summary-sub"></div>
            </div>
          </div>

          <div class="form-actions">
            <button id="calc-btn" type="button" class="btn ghost">
              احسب النقاط
            </button>

            <button id="submit-btn" type="submit" class="btn primary">
              إرسال الطلب
            </button>
          </div>

          <div class="calc-result" id="calc-result" aria-live="polite"></div>
        </form>
      </div>

      <!-- Right: Sidebar -->
      <aside class="right-col">
        <div class="points-card card">
          <h3>قيم النقاط</h3>

          <?php foreach($categories as $category){  ?>
          <div class="point-item" data-key="<?php echo strtolower($category['name']); ?>">
            <div class="meta">
              <div class="p-title"><?php echo strtolower($category['name']); ?></div>
              <div class="p-sub">لكل كجم</div>
            </div>
            <div class="p-value"><?php echo strtolower($category['points']); ?><br/><span>نقاط</span></div>
          </div>
          <?php }  ?>
        </div>

        <div class="tips card">
          <h4>نصائح لإعادة التدوير</h4>
          <ul>
            <li>اشطف الحاويات قبل الإرسال</li>
            <li>افصل الإلكترونيات الهشة</li>
            <li>ضع البطاريات بأمان</li>
          </ul>
        </div>
      </aside>
    </section>

  </main>


<script src="style/Recycle.js"></script>
<script src="../fun/resetalert.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", () => {

    // العناصر
    const tabs = document.querySelectorAll(".toggle .tab");
    const contentSection = document.querySelector(".content");
    const ordersSection = document.querySelector(".orders");

    tabs.forEach((tab, index) => {
      tab.addEventListener("click", () => {

        // إزالة active من كل التابات
        tabs.forEach(t => t.classList.remove("active"));

        // إضافة active للزر اللي اخترته
        tab.classList.add("active");

        // إظهار/إخفاء السكاشن
        if (index === 0) {
          // إرسال إعادة التدوير
          contentSection.style.display = "flex";
          ordersSection.style.display = "none";
        } else {
          // طلباتي
          contentSection.style.display = "none";
          ordersSection.style.display = "block";
        }

      });
    });

  });


 const categories = <?php 
    $jsArr = [];
    foreach($categories as $c){
        $jsArr[] = [
            'id' => $c['wasteid'],          // يطابق قيمة option
            'key' => strtolower($c['name']),
            'points' => (int)$c['points'],
            'display' => $c['name']
        ];
    }
    echo json_encode($jsArr);
?>;


document.addEventListener("DOMContentLoaded", () => {

    const weightInput = document.getElementById("weight");
    const wasteSelect = document.getElementById("waste-type");
    const total_pointsInput = document.getElementById("total_points");
    const summaryTitle = document.getElementById("summary-title");
    const summarySub = document.querySelector(".summary-sub");
    const calcResult = document.getElementById("calc-result");
    const calcBtn = document.getElementById("calc-btn");

    calcBtn.addEventListener("click", () => {
        const weight = parseFloat(weightInput.value) || 0;
        const selectedId = wasteSelect.value;
        

        // العثور على الكاتيجوري المختار
        const category = categories.find(c => c.id == selectedId);

        if (!category) {
            calcResult.textContent = "خطأ: نوع النفايات غير معروف";
            return;
        }

        // حساب النقاط
        const totalPoints = weight * category.points;

        // تحديث summary
        summaryTitle.textContent = category.display;
        summarySub.textContent = category.points + " نقطة لكل كجم";

        // تحديث حقل النقاط
        total_pointsInput.value = totalPoints;

        // عرض النتيجة
        calcResult.textContent = `الوزن: ${weight} كجم → مجموع النقاط: ${totalPoints}`;
    });

});
</script>

</body>
</html>

