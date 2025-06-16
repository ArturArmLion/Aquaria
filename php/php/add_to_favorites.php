<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Пользователь не авторизован']);
    exit;
}

if (!isset($_POST['product_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Нет ID товара']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = (int)$_POST['product_id'];

// Подключаемся к базе через db.php
require 'db.php';

// Проверяем, есть ли уже этот товар в избранном у пользователя
$stmt = $pdo->prepare('SELECT COUNT(*) FROM favorites WHERE user_id = ? AND product_id = ?');
$stmt->execute([$user_id, $product_id]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    echo json_encode(['status' => 'exists', 'message' => 'Товар уже в избранном']);
    exit;
}

// Добавляем товар в избранное
$stmt = $pdo->prepare('INSERT INTO favorites (user_id, product_id) VALUES (?, ?)');
if ($stmt->execute([$user_id, $product_id])) {
    echo json_encode(['status' => 'ok', 'message' => 'Товар добавлен в избранное']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка при добавлении в избранное']);
}
