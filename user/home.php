<?php

include("inc/check_role.php"); // تأكد من أن المستخدم مسجل الدخول وله الدور المناسب
include("../fun/alert.php"); // تضمين ملف التنبيهات

    // select waste categories 
    $get_categories = $pdo->prepare("SELECT * FROM wastes ORDER BY  name ASC");
    $get_categories->execute();
    $categories = $get_categories->fetchAll();

    // file_basic_site info
    include("inc/select_basic_info.php");

    if(!isset($_SESSION["user_points"])){
      //  get user points 
      $selec_points = $pdo->prepare("SELECT points FROM customers WHERE custid = ?");
      $selec_points->execute([$custid]);
      $user_points = $selec_points->fetch();

      $_SESSION["user_points"] = $user_points["points"];
    }

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <title>منصة إعادة التدوير</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> واست 2 ون</title>
  <link rel="stylesheet" href="style/home.css">
  <link rel="stylesheet" href="style/navbar.css">
  <style>
  .chat-toggle {
    position: fixed; 
    bottom: 22px; 
    left: 22px; 
    z-index: 9999;}
  .chat-toggle button {
    background:#f07d5d;
    color:#fff;
    border:none;
    padding:12px 16px;
    border-radius:999px;
    cursor:pointer;
    font-weight:600;
    box-shadow:0 6px 18px rgba(0,0,0,.12);}
  .chat-window {
    position:fixed;
    bottom:80px;
    left:22px;
    width:360px;
    max-width:calc(100% - 44px);
    height:480px;
    background:#fff;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,.15);
    display:none;
    flex-direction:column;
    overflow:hidden;
    z-index:9999;
    direction:rtl;}
.chat-header {
    padding:12px;
    background:linear-gradient(90deg, #f07d5d, #f07d5d);
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.close-btn{
    background:none;
    border:none;
    color:white;
    font-size:18px;
    cursor:pointer;
}
  .chat-messages {
    padding:12px;
    height:calc(100% - 124px);
    overflow-y:auto;
    background:#f9f9f9;}
  .chat-input {
    padding:10px;
    border-top:1px solid #eee;
    display:flex;
    gap:8px;
    background:#fafafa;}
  .chat-input input {
    flex:1;
    padding:10px;
    border-radius:8px;
    border:1px solid #ccc;
  }
  .chat-input button {
    padding:10px 12px;
    background:#f07d5d;
    border:none;
    color:white;border-radius:8px;
    cursor:pointer;}
  .msg{max-width:80%;
  padding:10px;
  border-radius:10px;
  margin-bottom:10px;}
  .msg.user{
    background:#f07d5d;
    color:white;
    margin-left:auto;}
  .msg.bot{
    background:#eee;
    color:#222;
    margin-right:auto;}
  .typing{
    font-size:13px;
    opacity:.7;
    margin-bottom:10px;}
  </style>
</head>
<body>
 
<!-- شريط التنقل -->
<?php include("inc/navbar.php"); ?>
  
<section class="hero">
    <div class="hero-text">
      <p class="tag">منصة إعادة التدوير المستدامة</p>
      <h1><?php echo htmlspecialchars($basic_site["title_1"]) ?></h1>
      <p class="desc"><?php echo htmlspecialchars($basic_site["about"]) ?></p>

      <div class="hero-buttons">
        <a href="Recycle.php"><button id="startRecyclingBtn">ابدأ إعادة التدوير</button></a>
        <a href="shop.php"><button id="browseShopBtn" class="secondary">تصفح المتجر</button></a>
      </div>

      <div class="stats">
        <div><strong>10K+</strong><p>مستخدم نشط</p></div>
        <div><strong>50K+</strong><p>عنصر مُعاد تدويره</p></div>
        <div><strong>2M+</strong><p>نقطة مكتسبة</p></div>
      </div>
    </div>

    <div class="hero-image">
      <img src="../imgs/recycling-2.png" alt="كوب إعادة التدوير">
    </div>
</section>

<section class="categories">
<?php 
  foreach($categories as $category){
?>
    <div class="card recycling-category" data-category="<?php echo htmlspecialchars($category["name"]) ?>">
      <?php echo $category["name"] ?><br>
      <small><?php echo htmlspecialchars($category["points"]) ?> نقطة</small>
    </div>
<?php } ?>
</section>

<section class="why">
    <h2>لماذا واست 2 ون؟</h2>
    <p>انضم إلى مجتمعنا وساهم في حماية البيئة بينما تكسب المكافآت.</p>
    <div class="why-cards">
      <div class="why-card"><h3>إعادة تدوير سهلة</h3><p>عملية بسيطة لإعادة تدوير نفاياتك المنزلية وكسب النقاط.</p></div>
      <div class="why-card"><h3>اكسب النقاط</h3><p>احصل على مكافآت مقابل كل عنصر تعيد تدويره.</p></div>
      <div class="why-card"><h3>صديقة للبيئة</h3><p>ساهم في بيئة أنظف من خلال ممارسات مستدامة.</p></div>
      <div class="why-card"><h3>اصنع تأثيرًا</h3><p>انضم إلى الآلاف الذين يحدثون فرقًا إيجابيًا.</p></div>
    </div>
</section>

<section class="cta">
    <h2>هل أنت مستعد لإحداث فرق؟</h2>
    <p>ابدأ رحلتك في إعادة التدوير اليوم واكسب نقاطًا يمكنك استبدالها.</p>
    <a href="Recycle.php"><button id="ctaRecyclingBtn">ابدأ الآن</button></a>
</section>

<footer>
    <div class="footer-grid">
      <div>
        <h3>حول واست 2 ون</h3>
        <p>نسهّل عملية إعادة التدوير ونجعلها مجزية للجميع.</p>
        <p>انضم إلى الآلاف ممن يصنعون فرقًا إيجابيًا.</p>
      </div>

      <div>
        <h3>روابط سريعة</h3>
        <ul>
          <li><a href="#">كيف تعمل المنصة</a></li>
          <li><a href="#">الأسئلة الشائعة</a></li>
          <li><a href="#">اتصل بنا</a></li>
          <li><a href="#">الشروط والخصوصية</a></li>
        </ul>
      </div>

      <div>
        <h3>تواصل معنا</h3>
        <p>البريد: info@waste2win.com</p>
        <p>الهاتف: ‎+20 123 456 7899</p>
      </div>
    </div>

    <div class="socials">
      <p>تابعنا:</p>
      <div>
        <a href="#"></a><a href="#"></a><a href="#"></a><a href="#"></a>
      </div>
    </div>

    <p class="copy">© 2025  واست 2 ون. جميع الحقوق محفوظة.</p>
</footer>

<script src="../fun/resetalert.js"></script>

<!-- ======================================================================= -->
<!-- ==========================  CHATBOT ============================ -->
<!-- ======================================================================= -->

<div class="chat-toggle">
  <button id="openChatBtn">💬 دردشة المساعدة</button>
</div>

<div class="chat-window" id="chatWindow">
  <div class="chat-header">
      <span>مساعد  واست 2 ون</span>
      <button class="close-btn" id="closeChatBtn">✕</button>
  </div>
  <div class="chat-messages" id="chatMessages"></div>

  <form class="chat-input" id="chatForm">
    <input type="text" id="question" placeholder="اكتب سؤالك">
    <button type="submit">إرسال</button>
  </form>

  
<div id="answer"></div>

</div>

<script>
(() => {

  const openBtn = document.getElementById("openChatBtn");
  const closeBtn = document.getElementById("closeChatBtn");
  const chatWindow = document.getElementById("chatWindow");
  const chatMessages = document.getElementById("chatMessages");
  const chatForm = document.getElementById("chatForm");
  const questionInput = document.getElementById("question");

  // فتح الشات
  openBtn.onclick = () => {
    chatWindow.style.display = "flex";
    if (!chatWindow.dataset.opened) {
      addMsg("مرحبًا! اكتب سؤالك 👋", "bot");
      chatWindow.dataset.opened = "1";
    }
  };

  // قفل الشات
  closeBtn.onclick = () => {
    chatWindow.style.display = "none";
  };

  // إضافة رسالة
  function addMsg(text, who) {
    const div = document.createElement("div");
    div.className = "msg " + who;
    div.textContent = text;
    chatMessages.appendChild(div);
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }

  // إرسال السؤال
  chatForm.onsubmit = async (e) => {
    e.preventDefault();

    const q = questionInput.value.trim();
    if (!q) return;

    addMsg(q, "user");
    questionInput.value = "";

      try {
      const res = await fetch("model/chatbot.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ question: q })
      });

      const data = await res.json();
      const responseLabel = data.response || "unknown"; // ده اللي جاي من PHP (label)
      
      // =========================
      // اختار الرد بناءً على responseLabel
      // =========================
      let botReply = ""; // هنا هتحط الرد النهائي اللي يظهر في الشات

      if(responseLabel === "points") {

        // get user points from database 
        try {

            const pointsRes = await fetch("get_user_points.php", {
                method: "GET"
            });

            const pointsData = await pointsRes.json();
            const botReply = ` رصيدك الحالي هو ${pointsData.points} نقطة تقدر تستخدمهم دلوقتي في استبدال منتجات من المتجر 💚🍃 `;

            // عرض الرد في الشات
            addMsg(botReply, "bot");

            

            addMsg("اقدر اساعدك ازاي؟ 😇", "bot");

        } catch (err) {
            addMsg("حصل خطأ أثناء جلب النقاط 😅", "bot");
            console.error(err);
        }

      }else if(responseLabel === "recommend_products") {
          try {
              // استدعاء PHP اللي بيختار المنتج العشوائي
              const res = await fetch("recommend.php", {
                  method: "GET",
                  credentials: "same-origin" // مهم لو PHP يعتمد على session
              });

              const data = await res.json();
              const product = data.recommended_product;

              if(product) {
                  const botReply = `🎁 المنتج الذي نرشحه لك اليوم: ${product.title}\n${product.comment}\nيحتاج ${product.points} نقطة.`;
                  addMsg(botReply, "bot");
              } else {
                  addMsg("لا يوجد منتجات متاحة للنقاط الحالية 😅", "bot");
              }

          } catch(err) {
              addMsg("حصل خطأ أثناء جلب المنتجات 😅", "bot");
              console.error(err);
          }
        }else if(responseLabel === "project_info") {
          botReply = `الموقع بيخلّيك:
              ,تعيد تدوير المخلفات , تكسب نقاط , تستبدل النقط بمنتجات مفيدة.
              تحب اساعدك ازاي ؟😇♥️`;
      }else if(responseLabel === "recycling_info"){
        
        botReply = `ما هي إعادة التدوير؟
             \n
            إعادة التدوير هي عملية جمع المخلفات (زي البلاستيك، الورق، الزجاج، والمعادن) وإعادة تصنيعها عشان تتحول لمنتجات جديدة بدل ما تتحرق أو تترمي في القمامة.
              \n
            ليه إعادة التدوير مهمة؟
              \n
            تقليل التلوث
            لما نقلل حرق أو رمي النفايات، بيقل تلوث الهواء والمياه والتربة، وده بيحمي صحة الإنسان والكائنات الحية.
            \n
            الحفاظ على الموارد الطبيعية
            إعادة تدوير الورق تقلل قطع الأشجار، وإعادة تدوير المعادن تقلل استخراج المعادن من الأرض، وده يحافظ على مواردنا للأجيال الجاية.
            \n
            تقليل كمية المخلفات
            التدوير بيقلل حجم القمامة اللي بتتراكم في المكبات، وده بيساعد في الحفاظ على نظافة البيئة والمساحات العامة.
            \n
            أمثلة بسيطة على إعادة التدوير:
            \n
            الزجاجات البلاستيك → ملابس أو أدوات بلاستيك جديدة

            الورق القديم → دفاتر أو كرتون

            علب الألومنيوم → علب جديدة أو أدوات معدنية
            \n
            دورنا كأفراد:
            \n
            نفصل القمامة (بلاستيك، ورق، زجاج)

            نستخدم المنتجات القابلة لإعادة الاستخدام

            ننشر الوعي بين أهلنا وأصحابنا`;
      }else{
        botReply = `آسف، سؤالك لا يمكنني الاجابه عليه

        😇  بس أقدر أساعدك في أي حاجة تخص المشروع أو النقاط`;
      }

      // عرض الرد في الشات
      addMsg(botReply, "bot");

  } catch (err) {
      addMsg("حصل خطأ في الاتصال", "bot");
      console.error(err);
  }
  };

})();
</script>


</body>
</html>

