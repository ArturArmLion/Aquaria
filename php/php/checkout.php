<?php
session_start();
require 'db.php'; // Подключаем PDO

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['user_id']) || !$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Неверные данные или неавторизован']);
    exit;
}

$userId = $_SESSION['user_id'];
$cartItems = $data['cart'] ?? [];
$loyaltyCard = $data['loyaltyCard'] ?? null;
$total = 0;

try {
    $pdo->beginTransaction();

    foreach ($cartItems as $item) {
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, product_name, total, status, created_at, loyalty_card) VALUES (?, ?, ?, 'В обработке', NOW(), ?)");
        $stmt->execute([$userId, $item['name'], $item['price'], $loyaltyCard]);
        $total += $item['price'];
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'total' => $total]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка при сохранении заказа: ' . $e->getMessage()]);
}
