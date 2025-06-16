<?php
require '../php/db.php';

$token = $_GET['token'] ?? '';

if (!$token) {
    die("Токен не указан.");
}

// Поиск пользователя с таким токеном и не подтвержденного
$stmt = $pdo->prepare("SELECT id, is_confirmed FROM users WHERE token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    die("Неверный токен подтверждения.");
}

if ($user['is_confirmed'] == 1) {
    echo "Аккаунт уже подтвержден.";
    exit();
}

// Обновляем статус на подтвержденный
$stmt = $pdo->prepare("UPDATE users SET is_confirmed = 1, token = NULL WHERE id = ?");
$stmt->execute([$user['id']]);

echo "Спасибо! Ваш аккаунт подтверждён. <a href='login.php'>Войти</a>";
?>
