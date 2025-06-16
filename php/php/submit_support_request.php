<?php
session_start();
require 'db.php';

// Проверяем, что пользователь авторизован
if (!isset($_SESSION['user_id'])) {
    die("Пожалуйста, войдите в свой аккаунт.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка CSRF токена
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Неверный CSRF токен.");
    }

    // Получаем сообщение из формы
    $message = $_POST['message'] ?? '';

    if (empty($message)) {
        die("Сообщение не может быть пустым.");
    }

    $user_id = $_SESSION['user_id'];

    // Вставляем запрос в базу данных (или отправляем на почту)
    $stmt = $pdo->prepare("INSERT INTO support_requests (user_id, message, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([$user_id, $message]);

    // Выводим сообщение о успешной отправке
    echo json_encode(['status' => 'success', 'message' => 'Ваш запрос отправлен. Мы свяжемся с вами в ближайшее время.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Некорректный запрос.']);
}
