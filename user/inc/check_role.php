<?php
/**
 * check_role.php
 * -----------------------
 * شامل للتحقق من الجلسة وصلاحية المستخدم (Customer / Admin / Worker)
 * يمكن include في أي صفحة داخل user/ أو user/inc/
 */

// --------------------
// 1️⃣ بدء الـ session بشكل آمن (مرة واحدة فقط)
// --------------------
if (session_status() == PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 0,             // تنتهي عند إغلاق المتصفح
        'cookie_httponly' => true,          // يمنع الوصول للكوكي من JS
        'cookie_secure' => isset($_SERVER['HTTPS']), // يعمل فقط على HTTPS
        'use_strict_mode' => true,          // منع session fixation
    ]);
}



// --------------------
// 2️⃣ include قاعدة البيانات من أي مكان
// --------------------
$root_path = realpath(__DIR__ . "/../../"); // المسار الرئيسي للمشروع
if (file_exists($root_path . "/conn.php")) {
    require_once $root_path . "/conn.php";
} else {
    die("Database connection file not found!");
}

// --------------------
// 3️⃣ إعداد timeout للجلسة
// --------------------
$timeout = 900; // 15 دقيقة

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
    header("Location: /index.php?msg=session_expired");
    exit;
}

$_SESSION['last_activity'] = time();

// --------------------
// 4️⃣ تحديد الدور لكل نوع مستخدم
// --------------------
if (isset($_SESSION['custid'])) {

    // Customer
    $_SESSION['role'] = "customer";
    $custid = $_SESSION['custid'];
    $username = $_SESSION['cust_name'];

} elseif (isset($_SESSION['userid'])) {

    // Admin أو Worker
    try {
        $stmt = $pdo->prepare("SELECT role , user_name FROM users WHERE userid = ?");
        $stmt->execute([$_SESSION['userid']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user["role"] == 1) {
                $_SESSION['role'] = "admin";
                $_SESSION['user_name'] = $user["user_name"];
            } elseif ($user["role"] == 2) {
                $_SESSION['role'] = "worker";
                $_SESSION['user_name'] = $user["user_name"];
            } else {
                $_SESSION['role'] = "customer"; // احتياطي
                $_SESSION['user_name'] = $user["user_name"];
            }
        } else {
            session_unset();
            session_destroy();
            header("Location: /index.php?msg=unauthorized");
            exit;
        }

    } catch (PDOException $e) {
        error_log("Role check DB error: " . $e->getMessage());
        session_unset();
        session_destroy();
        header("Location: /index.php?msg=db_error");
        exit;
    }

} else {
    // لا يوجد مستخدم مسجل دخول
    session_unset();
    session_destroy();
    header("Location: /index.php?msg=login_required");
    exit;
}

// --------------------
// 5️⃣ دالة للتحقق من الدور قبل أي صفحة حساسة
// --------------------
function require_role($requiredRole) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $requiredRole) {
        header("Location: /index.php?msg=unauthorized");
        exit;
    }
}
?>
