<?php
$host = 'localhost';          // اسم المضيف 
$dbname = 'recypoints';           // اسم قاعدة البيانات
$username = 'root';           // اسم المستخدم
$password = '';               // كلمة المرور 

try {
    // إنشاء الاتصال باستخدام PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // إعدادات PDO: تشغيل الاستثناءات عند حدوث أخطاء
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // في حال حدوث خطأ
    echo "فشل الاتصال بقاعدة البيانات: " . $e->getMessage();
    exit();
}
?>