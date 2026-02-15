<?php
require("conn.php");
include("fun/alert.php");
session_start();

// لو فيه كوكيز موجودة بالفعل → دخّل المستخدم مباشرة
if (isset($_COOKIE["email"]) && isset($_COOKIE["password"])) {
    $email = $_COOKIE["email"];
    $password = $_COOKIE["password"];
    $has_pass = md5($password);

    // تحقق من العملاء أولاً
    $check_user = $pdo->prepare("SELECT custid, cust_name FROM customers WHERE email = ? AND password = ?");
    $check_user->execute([$email, $has_pass]);
    $user = $check_user->fetch();

    if ($user) {
        $_SESSION["custid"] = $user["custid"];
        $_SESSION["cust_name"] = $user["cust_name"];
        $_SESSION["role"] = "customer"; // تحديد الدور كعميل
        header("location:user/home.php");
        exit;
    }

    // تحقق من المستخدمين
    $check_user = $pdo->prepare("SELECT userid, user_name, role FROM users WHERE email = ? AND password = ?");
    $check_user->execute([$email, $has_pass]);
    $user = $check_user->fetch();

    if ($user) {
        $_SESSION["userid"] = $user["userid"];
        $_SESSION["user_name"] = $user["user_name"];
        $_SESSION["role"] = $user["role"];

        if ($user["role"] == 1) {
            header("location:admin/home.php");
            exit;
        } elseif ($user["role"] == 2) {
            header("location:worker/home.php");
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        $has_pass = md5($password);

        if (!empty($email) && !empty($has_pass)) {

            // تحقق من العملاء
            $check_user = $pdo->prepare("
                SELECT custid, cust_name
                FROM customers
                WHERE email = ? AND password = ?
            ");
            $check_user->execute([$email, $has_pass]);
            $user = $check_user->fetch();

            if ($user) {
                $_SESSION["custid"] = $user["custid"];
                $_SESSION["cust_name"] = $user["cust_name"];

                // ✅ نحفظ الكوكيز لمدة سنة
                setcookie("email", $email, time() + (365*24*60*60), "/");
                setcookie("password", $password, time() + (365*24*60*60), "/");

                header("location:user/home.php");
                exit;
            } else {
                // تحقق من جدول المستخدمين
                $check_user = $pdo->prepare("
                    SELECT userid, user_name, role
                    FROM users
                    WHERE email = ? AND password = ?
                ");
                $check_user->execute([$email, $has_pass]);
                $user = $check_user->fetch();

                if ($user) {
                    $_SESSION["userid"] = $user["userid"];
                    $_SESSION["user_name"] = $user["user_name"];
                    $_SESSION["role"] = $user["role"];

                    // ✅ نحفظ الكوكيز لمدة سنة
                    setcookie("email", $email, time() + (365*24*60*60), "/");
                    setcookie("password", $password, time() + (365*24*60*60), "/");

                    if ($user["role"] == 1) {
                        header("location:admin/home.php");
                        exit;
                    } elseif ($user["role"] == 2) {
                        header("location:worker/home.php");
                        exit;
                    }
                } else {
                    showAlert("danger", "خطأ، تأكد من البريد الإلكتروني وكلمة المرور!");
                }
            }
        } else {
            showAlert("danger", "من فضلك املأ جميع الحقول!");
        }
    }
}


$select_citys = $pdo->prepare("SELECT * FROM citys WHERE 1=1;");
$select_citys->execute();
$citys = $select_citys->fetchAll();

// alerts 

if(isset($_SESSION["alert"])){
  showAlert($_SESSION["alert"]["type"] , $_SESSION["alert"]["msg"]);
  unset($_SESSION['alert']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Media Query Example</title>
  <meta charset="UTF-8">
  <title>Waste2Win</title>
  <link rel="stylesheet" href="style/index.css">

  <style>
    body {
      background-color: rgb(240, 242, 243);
      font-size: 20px;
      text-align: center;
    }

    @media (max-width: 600px) {
      body {
        background-color: rgb(234, 225, 225);
        font-size: 16px;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="logo">
      <div class="logo">Waste2Win</div>
      <p>أعد التدوير. اربح. استبدل.</p>
    </div>

    <div class="tabs">
      <button class="tab active" id="loginTab">تسجيل الدخول</button>
      <button class="tab" id="registerTab">إنشاء حساب</button>
    </div>

    <!-- تسجيل الدخول -->
    <form id="loginForm" class="form active" method="POST">
      <label>البريد الإلكتروني</label>
      <input type="email" name="email" placeholder="example@domain.com" required>

      <label>كلمة المرور</label>
      <input type="password" name="password" placeholder="••••••••" required>

      <button type="submit" class="btn">دخول</button>
    </form>

    <!-- إنشاء حساب -->
    <form id="registerForm" class="form" method="POST" action="fun/register.php">
      <label>اسم المستخدم</label>
      <input type="text" name="username" placeholder="اسمك الكامل" required>

      <label>البريد الإلكتروني</label>
      <input type="email" name="email" placeholder="example@domain.com" required>

      <label>كلمة المرور</label>
      <input type="password" name="password" placeholder="••••••••" required>

      <label>رقم الهاتف</label>
      <input type="tel" name="phone" placeholder="رقم الهاتف" required max="11">

      <label for="city">المنطقه</label>
      <select name="city" id="city" required>
        <?php
          foreach($citys as $city){
            ?>
            <option value="<?php echo htmlspecialchars($city["cityid"]) ?>"><?php echo htmlspecialchars($city["cityname"]) ?></option>
            <?php
          }       
        ?>
      </select>

      <button type="submit" class="btn">تسجيل</button>
    </form>
  </div>

  <script src="style/index.js"></script>
  <script>
    <?php include("fun/resetalert.js"); ?>
  </script>

</body>
</html>

   