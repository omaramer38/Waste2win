<?php
header("Content-Type: application/json; charset=UTF-8");

// قراءة السؤال القادم من JS
$input = json_decode(file_get_contents("php://input"), true);
$question = trim($input["question"] ?? "");

// حفظ السؤال في question.json
file_put_contents(
    "question.json",
    json_encode(
        ["question" => $question],
        JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
    )
);

// ============================
// تشغيل موديل الـ Python
// ============================

// لو python مش في PATH استخدم المسار الكامل
$pythonPath = "python"; 
$scriptPath = __DIR__ . "/predict_model.py";

// تنفيذ السكريبت
exec("$pythonPath $scriptPath 2>&1", $output, $status);

// لو حصل خطأ في تشغيل بايثون
if ($status !== 0) {
    echo json_encode([
        "response" => "خطأ أثناء تشغيل الموديل",
        "status" => $status,
        "debug" => $output,
        "command" => "$pythonPath $scriptPath"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}


// ============================
// قراءة النتيجة
// ============================

$response = "لا توجد إجابة حالياً";

if (file_exists("output_answer.json")) {
    $data = json_decode(file_get_contents("output_answer.json"), true);
    if (isset($data["response"])) {
        $response = $data["response"];
    }
}

// إرسال النتيجة
echo json_encode(
    ["response" => $response],
    JSON_UNESCAPED_UNICODE
);
?>