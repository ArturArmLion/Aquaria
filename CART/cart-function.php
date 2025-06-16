<?php
session_start();
require_once('../php/db.php'); // Подключение к БД

// Добавление товара в корзину
function addToCart($product_id, $quantity) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Удаление товара из корзины
function removeFromCart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Добавление заказа
function addOrder($product_id, $quantity, $final_price, $delivery_address, $payment_method) {
    global $pdo;

    // Получаем цену товара из базы данных
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        // Если товара нет в базе, выходим из функции
        return;
    }

    $price = $product['price'];
    $total = $price * $quantity; // Общая стоимость без скидки
    $user_id = $_SESSION['user_id'] ?? 0;

    try {
        // 1. Создаем заказ в таблице orders
        $order_stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, discount, final_price, delivery_address, payment_method)
                                    VALUES (?, ?, ?, ?, ?, ?)");
        $order_stmt->execute([
            $user_id,
            $total,
            0, // Параметр скидки можно передать отдельно, если нужно
            $final_price,
            $delivery_address,
            $payment_method
        ]);

        $order_id = $pdo->lastInsertId(); // Получаем ID последнего заказа

        // 2. Добавляем товар в таблицу order_items
        $item_stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price)
                                    VALUES (?, ?, ?, ?)");
        $item_stmt->execute([
            $order_id,
            $product_id,
            $quantity,
            $price
        ]);

    } catch (PDOException $e) {
        // Выводим ошибку, если запрос не был выполнен
        error_log('Ошибка при добавлении заказа: ' . $e->getMessage());
        return false; // Возвращаем false, если не удалось создать заказ
    }

    return true; // Возвращаем true, если заказ успешно добавлен
}
?>
