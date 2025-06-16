<?php
session_start();

// Генерация случайного номера заказа
$order_number = uniqid("ORD-", true);

// Текущая дата и время
$order_time = date("d-m-Y H:i:s");

// Если заказ был оформлен (можно добавить дополнительные условия для проверки)
$is_order_successful = isset($_SESSION['cart']) && empty($_SESSION['cart']); // Например, если корзина пуста — заказ оформлен
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Спасибо за заказ</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Подключаем внешний CSS файл -->
    <link rel="stylesheet" href="order-information.css">
</head>
<body>

<div class="confirmation-container">
    <h1>Спасибо за заказ!</h1>
    
    <p>Ваш заказ был успешно оформлен.</p>
    
    <div class="details">
        <p>Номер заказа: <span class="order-number"><?= $order_number ?></span></p>
        <p>Дата оформления: <?= $order_time ?></p>
    </div>

    <a href="../user_index.php" class="home-button">На главную</a>
</div>

</body>
</html>
