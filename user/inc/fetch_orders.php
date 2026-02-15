<?php

include("inc/check_role.php"); // تأكد من أن المستخدم مسجل الدخول وله الدور المناسب
include("../fun/alert.php"); // تضمين ملف التنبيهات
        
//  get all recycling orders for customer
$stmtOrders = $pdo->prepare("
    SELECT 
        ro.recyid,
        ro.date,
        ro.comment_rej,
        sop.status
    FROM recy_order ro
    JOIN status_of_recy_order sop ON ro.statusid = sop.statusid
    WHERE ro.custid = ?
    ORDER BY ro.date DESC
");
$stmtOrders->execute([$custid]);
$orders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);

//  get orders info
$groupedOrders = [];

foreach ($orders as $order) {

    $stmtItems = $pdo->prepare("
        SELECT
            CASE 
                WHEN oi.wasteid IS NOT NULL THEN wt.name
                ELSE NULL
            END AS waste_name,

            CASE
                WHEN oi.proid IS NOT NULL THEN p.title
                ELSE NULL
            END AS product_title,

            COALESCE(oi.amount, 0) AS amount,
            COALESCE(oi.points_needed, 0) AS points
        FROM order_info oi
        LEFT JOIN wastes wt ON oi.wasteid = wt.wasteid
        LEFT JOIN products p ON oi.proid = p.proid
        WHERE oi.recyid = ?
    ");

    $stmtItems->execute([$order['recyid']]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

    // 
    $wasteNames = [];
    $productTitles = [];
    $totalAmount = 0;
    $totalPoints = 0;

    foreach ($items as $item) {

        if (!empty($item['waste_name'])) {
            $wasteNames[] = $item['waste_name'];
            $totalAmount += (int)$item['amount'];
        }

        if (!empty($item['product_title'])) {
            $productTitles[] = $item['product_title'];
            $totalPoints += (int)$item['points'];
        }
    }

    $groupedOrders[] = [
        'recyid' => $order['recyid'],
        'date' => $order['date'],
        'status' => $order['status'],
        'waste_names' => implode(', ', $wasteNames),
        'products' => implode(', ', $productTitles),
        'amount' => $totalAmount,
        'points' => $totalPoints,
        'comment_reject' => $order['comment_rej'] ?? ''
    ];
}


// echo '<pre>';
// print_r($groupedOrders);
// echo '</pre>';
// exit;

// echo "<pre>";
// print_r($orders);
// exit;



?>