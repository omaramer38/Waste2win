<?php

    include("inc/fetch_orders.php");

        // select waste categories
    $get_categories = $pdo->prepare("SELECT * FROM wastes ORDER BY  name ASC");
    $get_categories->execute();
    $categories = $get_categories->fetchAll();


    // get all cities 
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

    <section class="content" >
      <div class="">
        <form id="recycle-form" class="card form-card" method="POST" action="inc/add_recycle.php">
          
            <input type="hidden" name="total_points" id="total_points">

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

            <button id="submit-btn" type="submit" class="btn primary" style="  background: #9fb878;">
              إرسال الطلب
            </button>
          </div>

          <div class="calc-result" id="calc-result" aria-live="polite"></div>
        </form>
      </div>
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

