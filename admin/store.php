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

    // Fetch products from the store
    $search = '';
    $search_sql = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['search'])) {
        $search = trim($_POST['search']);

        if (strpos($search, ' ') === false && strlen($search) == 1) {
            // لو حرف واحد → البحث عن المنتجات اللي تبدأ بالحرف ده
            $search_sql = " AND p.title LIKE ?";
            $search_param = "$search%";
        } else {
            // لو نص أطول أو اسم كامل → البحث عن المنتج اللي يحتوي النص بالكامل
            $search_sql = " AND p.title LIKE ?";
            $search_param = "%$search%";
        }
    }

    $select_products = $pdo->prepare("
        SELECT
            p.*,
            top.type_name,
            s.status,
            i.img
        FROM products p
        JOIN type_of_pro top ON p.type_of_pro = top.typeid
        JOIN status_of_pro s ON p.status = s.statusid
        JOIN pro_imgs i ON p.proid = i.proid
        WHERE 1 = 1 $search_sql
    ");

    // تنفيذ الاستعلام
    if (!empty($search)) {
        $select_products->execute([$search_param]);
    } else {
        $select_products->execute();
    }

    $products = $select_products->fetchAll(PDO::FETCH_ASSOC);



    // Count total products and total stock
    $select_totals = $pdo->prepare("
    SELECT 
        COUNT(*) AS total_products,
        SUM(quantity) AS total_quantity
    FROM products
");
$select_totals->execute();
$totals = $select_totals->fetch(PDO::FETCH_ASSOC);

$total_products = $totals['total_products']; // إجمالي عدد المنتجات
$total_quantity = $totals['total_quantity']; // إجمالي المخزون



// get types of products
$select_types = $pdo->prepare("SELECT * FROM type_of_pro");
$select_types->execute();
$types_of_pro = $select_types->fetchAll(PDO::FETCH_ASSOC);


// get status of products
$select_status = $pdo->prepare("SELECT * FROM status_of_pro");
$select_status->execute();
$statuses = $select_status->fetchAll(PDO::FETCH_ASSOC);


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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>إدارة المتجر | أعد التدوير واربح</title>
  <link rel="stylesheet" href="style/store.css">
  <link rel="stylesheet" href="style/sidebar.css">
  <style>
    .modal {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.6);
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.modal-content {
  background: #fff;
  padding: 20px;
  width: 400px;
  border-radius: 8px;
  position: relative;
}

.modal-content h2 {
  margin-bottom: 15px;
  text-align: center;
}

.modal-content input,
.modal-content textarea,
.modal-content select {
  width: 100%;
  margin-bottom: 10px;
  padding: 8px;
}

.modal-content textarea {
  resize: none;
  height: 80px;
}

.submit-btn {
  background: #28a745;
  color: #fff;
  border: none;
  padding: 10px;
  width: 100%;
  cursor: pointer;
}

.close-btn {
  position: absolute;
  right: 10px;
  top: 10px;
  cursor: pointer;
  font-size: 22px;
}

.image-preview {
  text-align: center;
  margin-bottom: 15px;
}

.image-preview img {
  width: 150px;
  height: 150px;
  object-fit: cover;
  border-radius: 10px;
  border: 2px dashed #ccc;
  margin-bottom: 10px;
}

.upload-btn {
  display: inline-block;
  padding: 6px 15px;
  background: #007bff;
  color: #fff;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
}
  </style>
</head>
<body>
  <div class="dashboard">
    
    <?php include("inc/sidebar.php");?>

    
    <main class="main-content">
      <div class="header">
        <h1>إدارة المتجر</h1>
        <button class="add-btn" id="openModal">+ إضافة منتج جديد</button>
      </div>


      <!-- بوب اب اضافة منتج -->

      <div class="modal" id="productModal">
        <div class="modal-content">
          <span class="close-btn" id="closeModal">&times;</span>

          <h2>إضافة منتج جديد</h2>

          <form action="inc/add_product.php" method="POST" enctype="multipart/form-data">

            <input type="text" name="title" placeholder="اسم المنتج" required>

            <textarea name="comment" placeholder="وصف المنتج" required></textarea>

            <input type="number" name="quantity" placeholder="الكمية" required>

            <input type="file" name="img" accept="image/*" required>

            <select name="type_of_pro" id="">
              <?php foreach($types_of_pro as $type){ ?>
                <option value="<?php echo htmlspecialchars($type["typeid"]) ?>"><?php echo htmlspecialchars($type["type_name"]) ?></option>
              <?php } ?>
            </select>

            <input type="number" name="points" placeholder="النقاط" required>

            <select name="status" required>
              <?php foreach($statuses as $status){ ?>
                <option value="<?php echo htmlspecialchars($status["statusid"]) ?>"><?php echo htmlspecialchars($status["status"]) ?></option>
              <?php } ?>
            </select>

            <button type="submit" class="submit-btn">حفظ المنتج</button>
          </form>
        </div>
      </div>


      <div class="stats">
        <div class="stat-box">
          <span>إجمالي المنتجات</span>
          <strong id="totalProducts"><?php echo htmlspecialchars($total_products)  ?></strong>
        </div>
        <div class="stat-box">
          <span>إجمالي المخزون</span>
          <strong id="totalStock"><?php echo htmlspecialchars($total_quantity)  ?></strong>
        </div>
      </div>

        <form action="" method="POST">
            <div class="action">
                <input
                    type="text"
                    name="search"
                    id="searchBox"
                    placeholder="ابحث عن منتج..."
                    class="search-box"
                />
                <button type="submit" class="add-btn">بحث</button>
            </div>
        </form>


      <?php if (count($products) === 0): ?>
        <p>لا توجد منتجات في المتجر.</p>
      <?php else: ?>
        
      <div class="products" id="productList">
        <?php foreach($products as $product){ ?> 
        <div class="product-card">
          <img src="../imgs/<?php echo htmlspecialchars($product["img"]) ?>" alt="حقيبة تسوق قابلة لإعادة الاستخدام" />
          <h3><?php echo htmlspecialchars($product["title"]) ?></h3>
          <h6><?php echo htmlspecialchars($product["comment"]) ?></h6>
          <p><?php echo htmlspecialchars($product["points"]) ?>  نقطه </p>
          <p>المخزون: <?php echo htmlspecialchars($product["quantity"]) ?></p>
          <p><?php echo htmlspecialchars($product["type_name"]) ?></p>
          <p><?php echo htmlspecialchars($product["status"]) ?></p>
          <div class="actions">
            <form action="inc/delete_pro.php" method="POST">
              <input type="hidden" name="proid" value="<?= $product['proid'] ?>">
              <button class="delete-btn">حذف</button>
            </form>
            <button 
              class="edit-btn"
              data-proid="<?= $product['proid'] ?>"
              data-title="<?= htmlspecialchars($product['title']) ?>"
              data-comment="<?= htmlspecialchars($product['comment']) ?>"
              data-quantity="<?= $product['quantity'] ?>"
              data-points="<?= $product['points'] ?>"
              data-type="<?= $product['type_of_pro'] ?>"
              data-status="<?= $product['status'] ?>"
              data-img="<?= htmlspecialchars($product['img'], ENT_QUOTES, 'UTF-8') ?>"
            >
              تعديل
            </button>

          </div>
        </div>
              <?php } ?>
        <?php endif; ?>
      </div>

      <!-- pop up edit product -->
       <div class="modal" id="editProductModal">
        <div class="modal-content">
          <span class="close-btn" id="closeEditModal">&times;</span>

          <h2>تعديل المنتج</h2>

          <form action="inc/edit_product.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="proid" id="edit_proid">
            <input type="hidden" name="old_img" id="old_img">

            <input type="text" name="title" id="edit_title" placeholder="اسم المنتج" required>

            <textarea name="comment" id="edit_comment" placeholder="وصف المنتج" required></textarea>

            <input type="number" name="quantity" id="edit_quantity" placeholder="الكمية" required>

            <!-- Image Preview -->
            <div class="image-preview">
              <img id="edit_img_preview" src="" alt="صورة المنتج">
              <label class="upload-btn">
                تغيير الصورة
                <input type="file" name="img" id="edit_img" accept="image/*" hidden>
              </label>
            </div>

            <select name="type_of_pro" id="edit_type">
              <?php foreach($types_of_pro as $type){ ?>
                <option value="<?= $type['typeid'] ?>"><?= htmlspecialchars($type['type_name']) ?></option>
              <?php } ?>
            </select>

            <input type="number" name="points" id="edit_points" placeholder="النقاط" required>

            <select name="status" id="edit_status">
              <?php foreach($statuses as $status){ ?>
                <option value="<?= $status['statusid'] ?>"><?= htmlspecialchars($status['status']) ?></option>
              <?php } ?>
            </select>

            <button type="submit" class="submit-btn">حفظ التعديلات</button>
          </form>
        </div>
      </div>


    </main>
  </div>

  <script src="style/sidebar.js"></script>

  <!-- pop up add product -->
  <script>
        
    const openBtn = document.getElementById("openModal");
    const closeBtn = document.getElementById("closeModal");
    const modal = document.getElementById("productModal");

    openBtn.onclick = () => {
      modal.style.display = "flex";
    };

    closeBtn.onclick = () => {
      modal.style.display = "none";
    };

    window.onclick = (e) => {
      if (e.target === modal) {
        modal.style.display = "none";
      }
    };

  </script>

  <!-- pop up edit product -->
   <script>
const editModal = document.getElementById("editProductModal");
const closeEditModal = document.getElementById("closeEditModal");

document.querySelectorAll(".edit-btn").forEach(btn => {
  btn.onclick = () => {

    document.getElementById("edit_proid").value = btn.dataset.proid;
    document.getElementById("edit_title").value = btn.dataset.title;
    document.getElementById("edit_comment").value = btn.dataset.comment;
    document.getElementById("edit_quantity").value = btn.dataset.quantity;
    document.getElementById("edit_points").value = btn.dataset.points;
    document.getElementById("edit_type").value = btn.dataset.type;
    document.getElementById("edit_status").value = btn.dataset.status;

    document.getElementById("old_img").value = btn.dataset.img;
    document.getElementById("edit_img_preview").src = "../imgs/" + btn.dataset.img;

    editModal.style.display = "flex";
  };
});

closeEditModal.onclick = () => {
  editModal.style.display = "none";
};

// live image preview
document.getElementById("edit_img").addEventListener("change", function(){
  const file = this.files[0];
  if(file){
    document.getElementById("edit_img_preview").src = URL.createObjectURL(file);
  }
});
</script>

</body>
</html>





